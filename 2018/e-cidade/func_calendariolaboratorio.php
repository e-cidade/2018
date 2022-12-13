<?php
/*
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/controleexameslaboratorio.model.php"));

parse_str($_SERVER['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

class calendario {

   var $sem;//Array com os dias da semana como índice
   var $mes;//Array com os meses do ano
   var $nome_objeto_data;
   var $shutdown_function = '';
   var $centralagenda     = 'N';
   var $oControleExames   = null;

   function __construct() {
     $this->oControleExames = new controleExamesLaboratorio($_GET['la09_i_codigo']);
   }

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

     for ($k = 0; $k < $i; $k++) {
       $retval .= "<td width=\"20\">&nbsp;</td>";
     }

     return $retval;
   }

   function arr_search( $array, $valor ) {

     for( $x=0; $x < count($array); $x++) {

       if ( $array[$x] == $valor ) {
         return true;
       }
     }
     return false;
   }

  function cria($dia, $mes, $ano, $marca = 0, $str_where, $result_diassemana, $fechar = false, $iQuantidade) {

    $this->inicializa();

    $oDaoLabAusencia    = new cl_lab_ausencia();
    $oDaoLabParalizacao = new cl_lab_paralizacao();
    $iTipoControle      = $this->oControleExames->getTipoControle();

    $last = date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/

    if ($last<$dia) {
      $dia = $last;
    }

    $verf              = date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/
    $pieces            = explode("/",$verf);
    $dia               = $pieces[0];
    $mes               = $pieces[1];
    $ano               = $pieces[2];
    $diasem            = date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/
    $str               = "";
    $iLiberarequisicao = 1;

    if ($this->sem[$diasem] != 1) {/*Se dia semana diferente de domingo,completa com colunas em branco*/

      $valor = $this->sem[$diasem] - 1;
      $str   = "<tr align=center >".$this->aux($valor);
    }

    //Pega os dias da semana
    for ( $x=0; $x < pg_num_rows( $result_diassemana ); $x++) {

      $obj_result   = db_utils::fieldsMemory($result_diassemana, $x);
      $arr_diasem[] = $obj_result->la35_i_diasemana;
    }

    for($i = 1; $i < ($last + 1); $i++) {       // pega todos os dias do mes informado....

      $diasem            = date ("D", mktime (0,0,0,$mes,$i,$ano));
      $iLiberarequisicao = 1;

      if($this->sem[$diasem] == 1) {

        $str .= "<tr align=\"center\" >";
        $s    = "$i";
      } else {
        $s = "$i";
      }

      $data_script = "$ano-$mes-$s";
      $str        .= "<td     ";

      if ($marca != 0) {  // marca o dia atual em laranja
        $str .= "onmouseover=\"js_mostra('parecerr".$i."',event); \" onmouseout=\"js_oculta('parecerr".$i."',event);\" ";
      }

      $str .=" width=\"25\" ";

      if (    ( $ano.str_pad($mes,2,'0',STR_PAD_LEFT).str_pad($i,2,'0',STR_PAD_LEFT) >= date("Ymd"))
           && ( isset($arr_diasem) && $this->arr_search( $arr_diasem, $this->sem[$diasem] ) )  ) {

        $booMostradiv = false;
        $str_query  = "select la35_i_codigo, ";
        $str_query .= "       la35_c_horaini, ";
        $str_query .= "       la35_c_horafim, ";
        $str_query .= "       la09_i_codigo, ";
        $str_query .= "       la35_i_codigo ";
        $str_query .= "  from lab_horario ";
        $str_query .= "       inner join lab_setorexame on la35_i_setorexame=la09_i_codigo ";
        $str_query .= " where {$str_where} ";
        $str_query .= "   and la35_i_diasemana = {$this->sem[$diasem]} ";
        $str_query .= "   and (    la35_d_valfim is null ";
        $str_query .= "         or ( la35_d_valfim is not null and la35_d_valfim >= '{$ano}/{$mes}/{$s}' ) ) ";
        $str_query .= "   and (    la35_d_valinicio is null ";
        $str_query .= "         or ( la35_d_valinicio is not null and la35_d_valinicio <= '{$ano}/{$mes}/{$s}' ) )";
        $rsHorario  = db_query( $str_query ) or die( "ERRO: <p> $str_query ");

        if ( pg_num_rows($rsHorario) > 0 ) {

          $oHorario  = db_utils::fieldsMemory($rsHorario,0);
          if ($oHorario->la35_c_horaini == "") { // Condição que não deve ocorrer, pois o horário é obrigatório

             $str    .= " bgcolor=white > ";  // marcar o dia atual
             $str    .=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar);\"><font size='4'> $s</a> ";
             $str_msg = "Profissional não está atendendo.<br> Motivo:  ";
             $booMostradiv = true;
          } else {

            //Profissional ausente
            $sTmp1 = $oDaoLabAusencia->laboratorioausencia($oHorario->la09_i_codigo,
                                                           "'".$oHorario->la35_c_horaini."'",
                                                           "'".$oHorario->la35_c_horafim."'",
                                                           "'$ano-$mes-$s'"
                                                          );
            //Laboratorio Paralizado
            $sTmp2 = $oDaoLabParalizacao->laboratorioparalisado($oHorario->la09_i_codigo,
                                                                "'".$oHorario->la35_c_horaini."'",
                                                                "'".$oHorario->la35_c_horafim."'",
                                                                "'$ano-$mes-$s'"
                                                               );

            // Junto os horários e motivos de ausência e paralisação
            $sAusencias = '';
            if (!empty($sTmp1)) {
              $sAusencias .= 'Ausências: '.$sTmp1;
            }

            if (!empty($sTmp2)) {

              if (!empty($sAusencias)) { // Se houver ausência e paralisação
                $sAusencias .= '<br>';
              }
              $sAusencias .= 'Paralisações: '.$sTmp2;
            }
          }
        }

        if ($booMostradiv == false && pg_num_rows($rsHorario) > 0 ) {

          //Pega mais de uma hora para o mesmo dia
          $str_msg = "";
          $str_br  = "";

          for($iCont = 0; $iCont < pg_num_rows($rsHorario); $iCont++) {

            $oHorario = db_utils::fieldsMemory($rsHorario,$iCont);
            $str_msg .= $str_br."{$oHorario->la35_c_horaini} as {$oHorario->la35_c_horafim}";
            $str_br   = '<br>';
          }

          $str_msg .= empty($sAusencias) ? '' : '<br>';
          $str_msg .= $sAusencias;

          $nSaldo      = 0;
          $nSaldoGasto = 0;

          if ($iTipoControle == 0) { // Não tem nenhum tipo de controle

            $str_msg    .= $str_br.'Saldo: Liberado';
            $nSaldo      = 1; // Seto para 1 para não ficar vermelho
            $nSaldoGasto = $this->oControleExames->getNumeroExamesAgendados("$ano-$mes-$s");
          } else {

            if(    !isset($oInfoControle) || $oInfoControle == null
                || (    !empty($oInfoControle->la56_d_fim)
                     && strtotime($oInfoControle->la56_d_fim) < strtotime("$ano-$mes-$s"))) {
              $oInfoControle = $this->oControleExames->getInfoControle("$ano-$mes-$s");
            }

            if ($oInfoControle == null) {
              $str_msg .=  $str_br.'Saldo: Sem informação de controle.';
            } else {

              $nSaldoGasto = $this->oControleExames->getSaldoGasto($oInfoControle, "$ano-$mes-$s");
              $nSaldo      = $oInfoControle->la56_n_limite - $nSaldoGasto;

              if ($oInfoControle->la56_i_teto == 1) { // Teto físico

                if ($iQuantidade <= $nSaldo) { // Suficiente
                  $str_msg .= $str_br.'Saldo: '. $nSaldo;
                } else { // Insuficiente

                  $str_msg .= $str_br.'Saldo: '.$nSaldo.' (insuficiente)';
                  $nSaldo   = 0;
                }
              } else { // Teto financeiro

                $sProc = $this->oControleExames->getProcedimento();
                if (!empty($sProc)) {

                  $nValorTotalProc  = $this->oControleExames->getValorProcedimento()
                                      + $this->oControleExames->getAcrescimoProcedimento();
                  $str_msg         .= $str_br.'Valor Proc.: R$ '.number_format($nValorTotalProc, 2, ',', '.');
                  $str_msg         .= $str_br.'Quantidade : '.$iQuantidade;
                  $nValorTotalProc  = $nValorTotalProc*$iQuantidade;
                  $str_msg         .= ' &nbsp;&nbsp;&nbsp;';

                  // Verifico se possui saldo suficiente para lançar mais um exame
                  if ($nValorTotalProc <= $nSaldo) { // Suficiente
                    $str_msg .= 'Saldo: R$ '.number_format($nSaldo, 2, ',', '.');
                  } else { // Insuficiente

                    $str_msg .= $str_br.'Saldo: R$ '.number_format($nSaldo, 2, ',', '.').' (insuficiente)';
                    $nSaldo   = 0;
                  }
                } else {

                  $str_msg           .= "<br>O exame não possui procedimento <br>ativo vinculado.";
                  $str_msg           .= "<br>Vincule um procedimento ao exame.";
                  $iLiberarequisicao = 0;
                  $nSaldo            = 0;
                }
              }
            }
          }

          $str_cor = " bgcolor='' > ";
          if ($this->arr_search($arr_diasem, $this->sem[$diasem])) {

            if (!empty($sAusencias)) {
              $str_cor = " 'bgcolor=white > ";
            } elseif ($nSaldo <= 0) {

              $str_cor = " bgcolor=red > ";
              if ($oInfoControle != null && $oInfoControle->la56_i_liberarequisicaosemsaldo == 1 && $iLiberarequisicao == 1) {
                $nSaldo = 1; // Para liberar a requisição
              }
            } elseif ($nSaldoGasto > 0) {
              $str_cor = " bgcolor=yellow > ";
            } else {
              $str_cor = " bgcolor=#00FF00 > "; // Verde
            }
          }

          $str   .= $str_cor;
          $nSaldo = empty($sAusencias) ? $nSaldo : 0; // Se houver alguma ausência que afete o horário do exame, bloqueio o saldo

          if ($nSaldo > 0) {
            $str.=" <a href=\"\" onclick=\"return janela($s,$mes,$ano,$fechar,$nSaldo,'$oHorario->la35_c_horaini');\"><font size='4'> $s</a> ";
          } else { //se o saldo for Zero não cria link
            $str.=" <font size='4'> $s </font> ";
          }

          $booMostradiv = true;
        }

        if ( $booMostradiv ) {

          //*Div
          $str .= " <div  onmouseover=js_oculta('parecerr$i',event)  name='parecerr$i' id='parecerr$i' style='position:absolute;visibility:hidden;left: 0px;top: 0px'>
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
                     </div>";
                 //Fim Div
        } else {
          $str .="><font size='4'> $s";
        }
      } else {
        $str .="><font size='4'> $s";
      }

      $str .="</font> </td>";

      if ($this->sem[$diasem] == 7) {
        $str.="</tr>";
      }
    }

    $diasem=date ("D", mktime (0,0,0,$mes,$last,$ano));

    if ($this->sem[$diasem] != 7) {

      $valor = 7-$this->sem[$diasem];
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
               <a id='previous_year' href=\"func_calendariolaboratorio.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."&".$str_where."&iQuantidade=".$iQuantidade." \"> << </a>
                      $ano
             <a id='next_year' href=\"func_calendariolaboratorio.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."&".$str_where."&iQuantidade=".$iQuantidade." \"> >> </a>
              </font>
             </td>
           </tr>
           <tr align=\"center\">
             <td width=\"100%\" colspan=\"7\" nowrap>
              <FONT SIZE='1' FACE='Verdana' COLOR='black'>
               <a id='previous_month' href=\"func_calendariolaboratorio.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."&".$str_where."&iQuantidade=".$iQuantidade." \"> << </a>
               ".$this->mes[$mes]."
               <a id='next_month' href=\"func_calendariolaboratorio.php?".($this->shutdown_function!=""?"shutdown_function=".$this->shutdown_function."&":"")."nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."&".$str_where."&iQuantidade=".$iQuantidade." \"> >> </a>

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
          ";
    $str .= " <td bgcolor='white'><font size=1>Ausente</td> ";
    $str .= "   <td bgcolor='#00FF00'><font size=1>Liberado</td>
             <td bgcolor='yellow'><font size=1>Marcado</td>
             <td bgcolor='red'><font size=1>Lotado</td>
           </tr>
          </table>";
    echo $str;
  } //fim function
}

//Inicializa classe
$clcalendario = new calendario;

$clcalendario->nome_objeto_data = $nome_objeto_data;
$clcalendario->centralagenda    = isset($sd02_c_centralagenda) && $sd02_c_centralagenda != "S" ? "N" : "S";

$str_centralagenda = "";
$str_where         = "la09_i_codigo={$la09_i_codigo}";

if (!isset($mes_solicitado)) {
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
} elseif ( $mes_solicitado > 12 || $mes_solicitado < 1 ) {

  $ano_solicitado = $mes_solicitado > 12 ? ($ano_solicitado + 1) : ($ano_solicitado - 1);
  $mes_solicitado = $mes_solicitado > 12 ? 1 : 12;
}

if (!isset($ano_solicitado)) {
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}

if (isset($shutdown_function)) {
  $clcalendario->shutdown_function = $shutdown_function;
}

if (  isset($la09_i_codigo) && (int) $la09_i_codigo != 0  ) {

  //Pega total de dias para agendamentos
  $strData  = date("$ano_solicitado")."/";
  $strData .= date("$mes_solicitado")."/";
  $strData .= date("d",db_getsession("DB_datausu"));

  if ( checkdate($mes_solicitado, date("d",db_getsession("DB_datausu")), $ano_solicitado) == false ) {
    $strData  = date("$ano_solicitado")."/".date("$mes_solicitado")."/01";
  }

  $str_query  = "select distinct la35_i_diasemana ";
  $str_query .= "  from lab_horario ";
  $str_query .= "       inner join lab_setorexame on la09_i_codigo = la35_i_setorexame ";
  $str_query .= " where {$str_where} ";
  $str_query .= " order by la35_i_diasemana";
  $result     = db_query($str_query) or die("ERRO: nos horÃ¡rios para o exame.");

  if ( pg_num_rows($result) > 0 ) {

    $obj_result = db_utils::fieldsMemory($result,0);
    $fechar     = isset($fechar) ? true : 0;

    $clcalendario->cria(date("d",db_getsession("DB_datausu")),date("$mes_solicitado"),
                        date("$ano_solicitado"),1,$str_where, $result, $fechar, $iQuantidade);
  } else {
    echo "Nehuma informação encontrada!";
  }
} else {
  echo "Não foi informado Laboratorio>Setor>Exame.";
}
?>

<script>
function js_mostra(nomepar,event) {

  PosMouseX = event.layerX;
  PosMouseY = event.layerY;

  if ( nomepar != undefined && document.getElementById(nomepar) != undefined) {

    document.getElementById(nomepar).style.top = 100;
    if ( PosMouseY > 80 ) {
      document.getElementById(nomepar).style.top = 0;
    }

    document.getElementById(nomepar).style.visibility = "visible";
  }
}

function js_oculta(nomepar,event) {

  if ( nomepar != undefined && document.getElementById(nomepar) != undefined ) {
    document.getElementById(nomepar).style.visibility = "hidden";
  }
}

function janela(d,m,a,fechar,saldo,hora) {

  parent.document.form1.la21_c_hora.value = hora;

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";

  echo "parent.document.getElementById('".$nome_objeto_data."_ano').setfocus;\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')) {
      echo $shutdown_function."\n";
  }
  ?>
  x = 'parent.iframe_data_<?=$nome_objeto_data?>.hide();\n';
  eval( x );
}

function janela_zera() {

  <?php
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = '';\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = '';\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')) {
      echo $shutdown_function."\n";
  }
  ?>
}

</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>