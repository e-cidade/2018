<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_sau_prestadorhorarios_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

class calendario {

  var $sem;//Array com os dias da semana como índice
  var $mes;//Array com os meses do ano
  var $nome_objeto_data;
  var $shutdown_function = "";

  function inicializa() {//Atribui valores para $sem e $mes.

    $this->sem = array(
      'Sun' => 1,
      'Mon' => 2,
      'Tue' => 3,
      'Wed' => 4,
      'Thu' => 5,
      'Fri' => 6,
      'Sat' => 7
    );

    $this->mes = array(
      '1'  => 'JANEIRO',
      '2'  => 'FEVEREIRO',
      '3'  => 'MARÇO',
      '4'  => 'ABRIL',
      '5'  => 'MAIO',
      '6'  => 'JUNHO',
      '7'  => 'JULHO',
      '8'  => 'AGOSTO',
      '9'  => 'SETEMBRO',
      '10' => 'OUTUBRO',
      '11' => 'NOVEMBRO',
      '12' => 'DEZEMBRO'
    );
  }

  function aux($i) {//Complementa a tabela com espaços em branco

    $retval = "";

    for($k = 0; $k < $i; $k++) {
      $retval.="<td width=\"20\">&nbsp;</td>";
    }

    return $retval;
  }

  function arr_search( $array, $valor ) {

    for( $x = 0; $x < count($array); $x++) {

      if( $array[$x] == $valor ) {
        return true;
      }
    }

    return false;
  }

  function cria($dia, $mes, $ano, $marca = 0, $s111_i_codigo, $fechar = false) {

    $oDaoSauPrestadorHorarios = new cl_sau_prestadorhorarios();
    $oDaoAgendaExames         = new cl_sau_agendaexames();
    $oDaoCotasMensal          = new cl_cotaprestadoraexamemensal();
    $this->inicializa();

    $last  = date("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/

    if($last < $dia) {
      $dia = $last;
    }

    $verf   = date("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
    $pieces = explode("/",$verf);
    $dia    = $pieces[0];
    $mes    = $pieces[1];
    $ano    = $pieces[2];
    $diasem = date("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
    $str    = "";
    if($this->sem[$diasem] != 1) {/*Se dia semana diferente de domingo,completa com colunas em branco*/

      $valor = $this->sem[$diasem] - 1;
      $str   = "<tr align=center >".$this->aux($valor);
    }

    /**
     * Buscamos a cota do municipio
     */
    $sCampos        = "age01_quantidade, sd63_i_codigo, age04_grupoexame";

    $sSqlMunicipio  = $oDaoCotasMensal->getAllMunicipio($sCampos);
    $sSqlMunicipio .= " inner join sau_prestadorvinculos on sd63_i_codigo = s111_procedimento ";
    $sSqlMunicipio .= " where s111_i_codigo = $s111_i_codigo";
    $sSqlMunicipio .= "   and age01_mes = $mes";
    $sSqlMunicipio .= "   and age01_ano = $ano";

    $rsCotaMunicipio = db_query($sSqlMunicipio);

    if ( !$rsCotaMunicipio ) {
      throw  new \DBException("Erro ao consultar cota do município no mês.");
    }

    $lCotaMunicipal = false;

    if ( pg_num_rows($rsCotaMunicipio) > 0 ) {
      $lCotaMunicipal = true;
    }

    $oCotaMunicipio = db_utils::fieldsMemory($rsCotaMunicipio, 0);

    if ( $lCotaMunicipal ) {

      $sCampos = "count(*) agendamentos";
      $sSqlAgendamentoMunicipio = $oDaoAgendaExames->sql_query_grupo_competencia($sCampos,
                                                                                 $oCotaMunicipio->age04_grupoexame,
                                                                                 $mes,
                                                                                 $ano);
      $rsAgendamentoMunicipio = db_query($sSqlAgendamentoMunicipio);

      if (empty($rsAgendamentoMunicipio)) {
        throw new \DBException("Erro ao buscar agendamentos do município.");
      }

      $oAgendamentoMunicipio = db_utils::fieldsMemory($rsAgendamentoMunicipio, 0);
    }

    /**
     * Mudar para Query na DAO.
     */
    $sSqlCotas        = $oDaoCotasMensal->sql_query_grupo($s111_i_codigo, $mes, $ano, "age01_quantidade, age02_sequencial");
    $rsControleMensal = db_query($sSqlCotas);
    $sWhereTipoGrade  = " and s112_c_tipograde <> 'M'";

    if (!$rsControleMensal) {
      throw new \Exception("Erro ao consultar dados da agenda mensal do prestador {$s111_i_codigo}");
    }

    $lControleMensal       = false;
    $iTotalRegistrosMensal = pg_num_rows($rsControleMensal);
    $iQuantidadeMensal     = 0;
    $sCampoTotalFichas     = 's112_i_fichas';
    $iTotalExamesNoMes     = 0;
    $iGrupoExame           = null;

    if ($iTotalRegistrosMensal > 0) {

      $lControleMensal        = true;
      $dadosMes               = db_utils::fieldsMemory($rsControleMensal, 0);
      $iQuantidadeMensal      = $dadosMes->age01_quantidade;

      if ( $lCotaMunicipal && $oCotaMunicipio->age01_quantidade < $dadosMes->age01_quantidade) {
        $iQuantidadeMensal = $oCotaMunicipio->age01_quantidade;
      }

      $iGrupoExame            = $dadosMes->age02_sequencial;
      $sCampoTotalFichas      = " {$iQuantidadeMensal} as s112_i_fichas";
      $sWhereSomaTotalExames  = "extract(month from s113_d_exame) = $mes and extract(year from s113_d_exame) = $ano";
      $sWhereSomaTotalExames .= " and age03_grupoexame = {$iGrupoExame} and s112_c_tipograde = 'M'";
      $sSqlTotalExames        = $oDaoAgendaExames->sql_query_agendamento_grupo("count(*) as total", $sWhereSomaTotalExames);
      $sWhereTipoGrade        = " and s112_c_tipograde = 'M'";

      $rsTotalExames          = db_query($sSqlTotalExames);
      if (!$rsTotalExames) {
        throw  new \Exception("Erro ao consultar total de exames no mês.");
      }

      $iTotalExamesNoMes = db_utils::fieldsMemory($rsTotalExames, 0)->total;
    }

    $iDia = date("d",db_getsession("DB_datausu"));

    if ( $iDia == 31 ) {
      $iDia = \DBDate::getQuantidadeDiasMes($mes, $ano);
    }

    $sData   = date("$ano") . '-' . date("$mes") . '-' .$iDia;
    $sWhere  = "s112_i_prestadorvinc = {$s111_i_codigo} ";

    $sWhere .= " and case  ";
    $sWhere .= "     when s112_d_valinicial is null and s112_d_valfinal is null ";
    $sWhere .= "       then true ";
    $sWhere .= "     when     s112_d_valinicial is not null and s112_d_valfinal is not null  ";
    $sWhere .= "          and '{$sData}' between s112_d_valinicial and s112_d_valfinal ";
    $sWhere .= "       then true ";
    $sWhere .= "     when s112_d_valinicial is not null and s112_d_valfinal is null and s112_d_valinicial <= '{$sData}'  ";
    $sWhere .= "       then true ";
    $sWhere .= "     when s112_d_valinicial is null and s112_d_valfinal is not null and s112_d_valfinal >= '{$sData}'  ";
    $sWhere .= "       then true ";
    $sWhere .= "     else  ";
    $sWhere .= "       false ";
    $sWhere .= "   end {$sWhereTipoGrade} ";

    //Pega os dias da semana
    $strSql = $oDaoSauPrestadorHorarios->sql_query(null, "s112_i_diasemana", "s112_i_diasemana, s112_c_horaini", "{$sWhere}" );
    $result_unidademedico = db_query( $strSql ) or die( "<p> $strSql<p>" . pg_errormessage() );

    for( $x = 0; $x < pg_num_rows( $result_unidademedico ); $x++) {

      $obj_result   = db_utils::fieldsMemory($result_unidademedico, $x);
      $arr_diasem[] = $obj_result->s112_i_diasemana;
    }

    for($i = 1; $i < ($last + 1); $i++) {       //; pega todos os dias do mes informado....

      $diasem = date("D", mktime (0,0,0,$mes,$i,$ano));

      if($this->sem[$diasem] == 1) {

        $str .= "<tr align=\"center\" >";
        $s    = "$i";
      } else {
        $s = "$i";
      }

      $data_script  = "$ano-$mes-$s";
      $str         .= "<td id='parecerr{$i}' ";

      if($marca != 0) {  // marca o dia atual em laranja
        $str .= "onmouseover=\"js_mostra('parecerr".$i."',event); \" onmouseout=\"js_oculta('parecerr".$i."',event);\" ";
      }

      $str .=" width=\"25\" ";

      if( ( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd") ) &&
        ( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )
      ) {

        $sCampos  = "  s112_c_horaini, ";
        $sCampos .= "  s112_c_horafim, ";
        $sCampos .= "  {$sCampoTotalFichas}, ";
        $sCampos .= "  s112_i_reservas, ";
        $sCampos .= "  s111_i_codigo, ";
        $sCampos .= "  sd101_c_descr, ";
        $sCampos .= "  ( select count(s113_d_exame) ";
        $sCampos .= "      from sau_agendaexames ";
        $sCampos .= "     where s113_d_exame = '{$ano}/{$mes}/{$s}' ";
        $sCampos .= "       and s113_i_situacao = 1 ";
        $sCampos .= "       and s113_i_prestadorhorarios = s112_i_codigo )::integer as total_agendado ";

        $sWhere  = "     s112_i_prestadorvinc = {$s111_i_codigo} ";

        if ($lControleMensal) {
          $sWhere  = "     age03_grupoexame = {$iGrupoExame} ";
        }

        $sWhere .= " and s112_i_diasemana = {$this->sem[$diasem]} ";
        $sWhere .= " and (  s112_d_valfinal is null ";
        $sWhere .= "       or ( s112_d_valfinal is not null and s112_d_valfinal >= '{$ano}/{$mes}/{$s}' ) ) ";
        $sWhere .= " {$sWhereTipoGrade}";

        $strSql  = $oDaoSauPrestadorHorarios->sql_query( null, $sCampos, null, $sWhere );

        if ($lControleMensal) {
          $strSql  = $oDaoSauPrestadorHorarios->sql_query_grupo( null, $sCampos, null, $sWhere );
        }

        $result_undmedhorario = db_query( $strSql ) or die( "ERRO: <p> $strSql <p>".pg_errormessage() );

        if (pg_num_rows($result_undmedhorario) != 0 ) {

          //Pega mais de uma hora para o mesmo dia
          $str_msg            = "";
          $int_sd112_i_fichas = 0;
          $int_total_agendado = 0;
          $str_br             = "";

          for( $xHora = 0; $xHora < pg_num_rows($result_undmedhorario); $xHora++) {

            $obj_undmedhorario   = db_utils::fieldsMemory($result_undmedhorario,$xHora);
            $int_total_agendado += $obj_undmedhorario->total_agendado;
            $int_sd112_i_fichas += $obj_undmedhorario->s112_i_fichas;

            if ($lControleMensal) {
              $int_sd112_i_fichas = $iQuantidadeMensal;
            }

            $str_msg .= $str_br."{$obj_undmedhorario->sd101_c_descr} {$obj_undmedhorario->s112_c_horaini}";
            $str_msg .= " as {$obj_undmedhorario->s112_c_horafim}";
            $str_msg .= " - Saldo: ".($obj_undmedhorario->s112_i_fichas - $obj_undmedhorario->total_agendado );
            $str_br   = "<br>";
          }

          if( $this->arr_search( $arr_diasem, $this->sem[$diasem] ) ) {

            $str_cor = " bgcolor=#00FF00 > ";  // marcar o dia atual

            if( ( $int_sd112_i_fichas - $int_total_agendado ) == 0 ) {
              $str_cor = " bgcolor=red > ";  // marcar o dia atual
            } else if( $int_total_agendado > 0 ) {
              $str_cor = " bgcolor=yellow > ";  // marcar o dia atual
            }

            if ($lControleMensal && $iTotalExamesNoMes >= $int_sd112_i_fichas) {
              $str_cor = " bgcolor=red >";
            }

            if ( $lControleMensal && $lCotaMunicipal && $oCotaMunicipio->age01_quantidade <= $oAgendamentoMunicipio->agendamentos ) {
              $str_cor = " bgcolor=red >";
            }
          }

          $str         .= $str_cor;
          $str         .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'> $s</a> ";
          $booMostradiv = true;
        }

        if( $booMostradiv ) {

          //*Div
          $str .= "
                     <div  onmouseover=js_oculta('parecerr$i',event)  name='parecerr$i' id='parecerr$i' style='position:absolute;visibility:hidden;left: 0px;top: 0px'>
                      <table  height='100%' width='100%' border='0' cellspacing='0' cellpadding='0'>
                       <tr>
                        <td height='1'>
                        </td>
                       </tr>
                      </table>
                      <table align='left' height='100%' width='100%' border='1' cellspacing='1' cellpadding='1'>
                       <tr>
                        <td  bgcolor='#f3f3f3'   align='justify'>
                          <FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>
                          <center>Data: $s/$mes/$ano  &nbsp;&nbsp;&nbsp; <a title='Fechar' href='' onclick=js_oculta('parecerr$i',event)>F</a> </center> <hr>
                          $str_msg
                          </FONT>
                         </td>
                       </tr>
                      </table>
                     </div>
                     ";
                //Fim Div
        } else {
          $str .="><font size='4'> $s";
        }
      } else {
        $str .="><font size='4'> $s";
      }

      $str .="</font> </td>";

      if($this->sem[$diasem] == 7) {
        $str.="</tr>";
      }
    }//fim for

    $diasem = date ("D", mktime (0,0,0,$mes,$last,$ano));

    if($this->sem[$diasem] != 7) {

      $valor = 7 - $this->sem[$diasem];
      $str   = $str.$this->aux($valor)."</tr>";
    }

    $str = "
             <center>
        <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
        <tr>
         <td colspan='4'>
           <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\" >
           <tr align=\"center\">
             <td width=\"100%\" colspan=\"7\" nowrap>
              <FONT SIZE='1' FACE='Verdana' COLOR='black'>
                 <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."&s111_i_codigo=".@$s111_i_codigo." \"> << </a>
                     $ano
                 <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."&s111_i_codigo=".@$s111_i_codigo." \"> >> </a>
              </font>
             </td>
           </tr>
           <tr align=\"center\">
             <td width=\"100%\" colspan=\"7\" nowrap>
              <FONT SIZE='1' FACE='Verdana' COLOR='black'>
               <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."&s111_i_codigo=".@$s111_i_codigo." \"> << </a>
               ".$this->mes[$mes]."
               <a href=\"func_calendarioexames.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."&s111_i_codigo=".@$s111_i_codigo." \"> >> </a>

        </FONT>
             </td>
           </tr>
           <tr align=\"center\">
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>D </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>T </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>Q </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
             <td class=dias width=\"20\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'>S </font></td>
             </tr>
             ".$str."
           </table>
           </td>
           </tr>
           <tr>
             <td bgcolor='#00FF00'><font size=1>Liberado</td>
             <td bgcolor='yellow'><font size=1>Marcado</td>
             <td bgcolor='red'><font size=1>Lotado</td>
           </tr>
          </table>
           ";
    echo $str;
  } //fim function
}

$clcalendario = new calendario;

if(!isset($mes_solicitado)) {
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
} else if( $mes_solicitado > 12 || $mes_solicitado < 1 ) {

  $ano_solicitado = $mes_solicitado > 12 ? ($ano_solicitado + 1) : ($ano_solicitado - 1);
  $mes_solicitado = $mes_solicitado > 12 ? 1 : 12;
}

if(!isset($ano_solicitado)) {
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}

if(isset($shutdown_function)) {
  $clcalendario->shutdown_function = $shutdown_function;
}

$clcalendario->nome_objeto_data = $nome_objeto_data;

if( isset($s111_i_codigo) && (int) $s111_i_codigo != 0 ) {

  $fechar = isset($fechar) ? true : 0;

  $clcalendario->cria(
    date("d",db_getsession("DB_datausu")),
    date("$mes_solicitado"),
    date("$ano_solicitado"),
    1,
    $s111_i_codigo,
    $fechar
  );
} else {
  echo "Não foi informado Profissional.";
}
?>

<script>
function js_mostra( nomepar, event ) {

  PosMouseX = event.layerX;
  PosMouseY = event.layerY;

  document.getElementById(nomepar).style.top = 100;

  if( PosMouseY > 80 ) {
    document.getElementById(nomepar).style.top = 0;
  }

  document.getElementById(nomepar).style.visibility = "visible";
}

function js_oculta(nomepar,event) {}

function janela( d, m, a, fechar ) {

  data      = new Date(a,m-1,d);
  dia       = data.getDay();
  semana    = new Array(6);
  semana[0] = 'Domingo';
  semana[1] = 'Segunda-Feira';
  semana[2] = 'Terça-Feira';
  semana[3] = 'Quarta-Feira';
  semana[4] = 'Quinta-Feira';
  semana[5] = 'Sexta-Feira';
  semana[6] = 'Sábado';

  parent.document.form1.diasemana.value = semana[dia];

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";

  echo "parent.document.getElementById('".$nome_objeto_data."_ano').setfocus;\n";

  if( isset($shutdown_function) && ($shutdown_function!='none') ) {
    echo $shutdown_function."\n";
  }
  ?>

  if( fechar == true ) {

    x = 'parent.iframe_data_<?=$nome_objeto_data?>.hide();\n';
    eval( x );
  }
}

function janela_zera() {

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";

  if( isset($shutdown_function) && ($shutdown_function!='none') ) {
    echo $shutdown_function."\n";
  }
  ?>
}
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');

  if(input != null) {
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  }
})();
</script>
