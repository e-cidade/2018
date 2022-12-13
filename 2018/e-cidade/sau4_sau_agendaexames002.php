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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("classes/db_sau_agendaexames_classe.php"));
require_once(modification("classes/db_sau_prestadorhorarios_classe.php"));
require_once(modification("classes/db_agendamentos_ext_classe.php" ));
require_once(modification("dbforms/db_funcoes.php"));

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">


</script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<style>
a:hover {
  color:blue;
}
a:visited {
  color: black;
  font-weight: bold;
}
a:active {
  color: black;
  font-weight: bold;
}
.cabec {
  text-align: center;
  font-size: 11;
  color: darkblue;
  background-color:#aacccc ;
  border:1px solid $FFFFFF;
  font-weight: bold;
}
.corpo {
  font-size: 9;
  color: black;
  background-color:#ccddcc;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<?


db_postmemory($_POST);

$sd02_i_codigo = db_getsession("DB_coddepto");
$ano           = substr( $s113_d_exame, 6, 4 );
$mes           = substr( $s113_d_exame, 3, 2 );
$dia           = substr( $s113_d_exame, 0, 2 );

$clsau_agendaexames      = new cl_sau_agendaexames;
$clsau_prestadorhorarios = new cl_sau_prestadorhorarios;
$oDaoCotasMensal         = new cl_cotaprestadoraexamemensal();

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

/**
 * Query mensal na Grid
 */
$sSqlCotas        = $oDaoCotasMensal->sql_query_grupo($s111_i_codigo, $mes, $ano, "age01_quantidade, age02_sequencial");
$rsControleMensal = db_query($sSqlCotas);

$dataAgendamento = "{$ano}-{$mes}-{$dia}";

if (!$rsControleMensal) {
  throw new \Exception("Erro ao consultar dados da agenda mensal do prestador {$s111_i_codigo}");
}
$aWheres = array("s112_c_tipograde <> 'M'", "s112_i_prestadorvinc = $s111_i_codigo", "s112_i_diasemana = {$chave_diasemana}");
$lControleMensal       = false;
$iTotalRegistrosMensal = pg_num_rows($rsControleMensal);
$iQuantidadeMensal     = 0;
$sWhereDatas = " (s112_d_valfinal is null or
                                      (s112_d_valfinal is not null and s112_d_valfinal > '{$dataAgendamento}'::date )
                                  )";
//Agenda
$sWhereExames           = "s113_d_exame = '{$ano}-{$mes}-{$dia}'::date ";
$sWhereExames          .= " and s113_i_prestadorhorarios = {$obj_prestadorhorarios->s112_i_codigo}";
$sWhereSomaTotalExames  = "s113_d_exame = '{$dataAgendamento}'::date and s113_i_prestadorhorarios = sau_prestadorhorarios.s112_i_codigo";
$sInnerJoin             = "";

if ($iTotalRegistrosMensal > 0) {

  $dadosCotaMensal = db_utils::fieldsMemory($rsControleMensal,0);

  $aWheres[0]            = " s112_c_tipograde = 'M' ";
  $sWhereDatas           = "('$dataAgendamento'::date between s112_d_valinicial and s112_d_valfinal)";
  $lControleMensal       = true;
  $iQuantidadeMensal     = $dadosCotaMensal->age01_quantidade;

  $sWhereSomaTotalExames  = "extract(month from s113_d_exame) = $mes and extract(year from s113_d_exame) = $ano";
  $sWhereSomaTotalExames .= " and age03_grupoexame = {$dadosCotaMensal->age02_sequencial} and s112_c_tipograde = 'M'";

  $sInnerJoin             = " inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
  $sInnerJoin            .= " inner join grupoexameprestador   on age03_prestadorvinculos = s111_i_codigo ";
}

$aWheres[]  = $sWhereDatas;
$where     = implode(" and ", $aWheres);

$str_query =  $clsau_prestadorhorarios->sql_query  ("",
                          "*, (select count(s113_d_exame)
                              from sau_agendaexames
                                   inner join sau_prestadorhorarios as a on s113_i_prestadorhorarios = a.s112_i_codigo
                                   {$sInnerJoin}
                              where {$sWhereSomaTotalExames}
                                    and s113_i_situacao = 1
                              )as total_agendado
                          ",
                          "s112_i_diasemana, s112_c_horaini",
                          $where);

$result_prestadorhorarios = $clsau_prestadorhorarios->sql_record( $str_query ) ;

if( $clsau_prestadorhorarios->numrows == 0  ){
  echo "<script>
      alert('Data inválida para esse profisisonal.');
      parent.document.form1.s113_d_exame.value='';
      parent.document.form1.diasemana.value='';
      parent.document.form1.s113_d_exame.focus();
    </script>";
  exit;
}


$reservadas    = 0;
$nro_fichas    = 0;
$nro_agendados = 0;
$str_tipograde = "";
$str_separador = "";
$linhas        = 0; //26/02
$iTotalDeFichasNoMes = 0;
if ($lControleMensal) {
  $nro_fichas  = $iQuantidadeMensal;
}

$iLinhasCalculoHorarioPrestadora = $clsau_prestadorhorarios->numrows;

//Calcula nro de fichas/reserva
for( $xHora=0; $xHora < $iLinhasCalculoHorarioPrestadora; $xHora++ ) {

  $obj_prestadorhorarios    = db_utils::fieldsMemory( $result_prestadorhorarios, $xHora );

  $reservadas    += $obj_prestadorhorarios->s112_i_reservas;
  $totalFichas   = $obj_prestadorhorarios->s112_i_fichas;

  if ($lControleMensal) {
    $totalFichas = 0;
  }

  $nro_fichas    += $totalFichas;
  $nro_agendados += $obj_prestadorhorarios->total_agendado;

  switch ($obj_prestadorhorarios->s112_c_tipograde) {
    case 'I':
      $sTipoGrade = ' Intervalo ';
      break;
    case 'P':
      $sTipoGrade = ' Período ';
      break;
    case 'M':
      $sTipoGrade = ' Mensal ';
     break;
    default:
      $sTipoGrade = ' Não Informado ';
      break;
  }
  $str_tipograde .= $str_separador. $sTipoGrade;
  $str_separador = "/";
}
$iTotalDeFichasNoMes = $nro_agendados;

$iAgendamentosMunicipio = 0;
if ( $lCotaMunicipal ) {

  $sCampos = "count(*) agendamentos";
  $sSqlAgendamentoMunicipio = $clsau_agendaexames->sql_query_grupo_competencia($sCampos,
                                                                               $oCotaMunicipio->age04_grupoexame,
                                                                               $mes,
                                                                               $ano);
  $rsAgendamentoMunicipio = db_query($sSqlAgendamentoMunicipio);

  if (empty($rsAgendamentoMunicipio)) {
    throw new \DBException("Erro ao buscar agendamentos do município.");
  }

  $oAgendamentoMunicipio  = db_utils::fieldsMemory($rsAgendamentoMunicipio, 0);
  $iAgendamentosMunicipio = $oAgendamentoMunicipio->agendamentos;
}

$int_size = strlen($str_tipograde)>80?80:strlen($str_tipograde);
echo "<script>";
echo " ;parent.document.getElementById('tipoControle').innerHTML = 'Total de Fichas no Dia'";
echo " ;parent.document.form1.saldo.value=".(($nro_fichas + $reservadas) - $nro_agendados);
echo " ;parent.document.form1.s112_i_fichas.value=".($nro_fichas);
echo " ;parent.document.form1.s112_i_reservas.value=".($reservadas);
echo " ;parent.document.getElementById('s112_c_tipograde').setAttribute('maxlength', 100);";
echo " ;parent.document.getElementById('s112_c_tipograde').setAttribute('size', $int_size);";
echo " ;parent.document.form1.s112_c_tipograde.value='".$str_tipograde."'";
echo " ;parent.document.getElementById('cotaMunicipio').style=\"display: none;\"" ;

if ($obj_prestadorhorarios->s112_c_tipograde == "M") {

  echo " ;parent.document.getElementById('tipoControle').innerHTML = 'Total de Fichas no Mês' ";

  if ($lCotaMunicipal) {

    $iSaldoMensal = $oCotaMunicipio->age01_quantidade - $iAgendamentosMunicipio;

    if ($iSaldoMensal < 0)  {
      $iSaldoMensal = 0;
    }

    echo " ;parent.document.form1.totalMunicipio.value=$oCotaMunicipio->age01_quantidade";
    echo " ;parent.document.form1.saldoMunicipio.value=$iSaldoMensal" ;
    echo " ;parent.document.getElementById('cotaMunicipio').style=\"display: visible;\"" ;
  }
}
echo "</script>";
?>
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<?php
  $lSemSaldo = false;
  for ($xHora = 0; $xHora < $iLinhasCalculoHorarioPrestadora; $xHora++) {

    $obj_prestadorhorarios  = db_utils::fieldsMemory( $result_prestadorhorarios, $xHora );
    $reservadas         = $obj_prestadorhorarios->s112_i_reservas;
    $nro_fichas         = $obj_prestadorhorarios->s112_i_fichas;
    $tipo               = $obj_prestadorhorarios->s112_c_tipograde;
    //Calcula intervalo
    $hora_ini           = $obj_prestadorhorarios->s112_c_horaini;
    $hora_fim           = $obj_prestadorhorarios->s112_c_horafim;
    $minutostrabalhados = cl_agendamentos_ext::minutos($hora_ini, $hora_fim);
    $intervalo          = 0;
    if ($nro_fichas != 0 && $obj_prestadorhorarios->s112_c_tipograde == 'I' ) {
      $intervalo = number_format($minutostrabalhados / $nro_fichas,2,'.','');
    }

    $str_query = $clsau_agendaexames->sql_query("","s113_i_codigo,s113_i_ficha,z01_i_numcgs,s113_c_hora, z01_v_nome,s113_i_prestadorhorarios","s113_i_ficha",
                          "s113_d_exame = '$ano/$mes/$dia'
                                and s113_i_situacao = 1
                                and s113_i_prestadorhorarios = {$obj_prestadorhorarios->s112_i_codigo}
                                ");

    $res_agenda = $clsau_agendaexames->sql_record( $str_query );
    $linhas     = $clsau_agendaexames->numrows;
    $linha      = 0;

    if( $linhas >= $nro_fichas ){
      $reservadas = 0;
    }
    /**
     * Controle mensal sempre adiciona uma nova linha para lançar, desde que ainda tenha saldo no mes
     */
    if( $linhas > $nro_fichas ) {
      $nro_fichas = $linhas;
    }

    $lMunicipioSemSaldo = false;

    if ($lControleMensal) {


      if ($iTotalDeFichasNoMes == $iQuantidadeMensal) {
        $nro_fichas = $linhas;
        $lSemSaldo = true;
       } else {


        $nro_fichas = $linhas + 1;
      }

      if ( $lCotaMunicipal && $iAgendamentosMunicipio >= $oCotaMunicipio->age01_quantidade ) {

        $nro_fichas         = $linhas;
        $lMunicipioSemSaldo = true;
      }
    }


    $iTotalFichas = $nro_fichas + $reservadas;
    $mi_interva1  = 0;
    $mi_interva2  = 1;

    ?>
      <tr class='cabec'>
        <td colspan="8" align="left">EXAME</td>
      </tr>
      <tr class='cabec'>
        <td class='cabec' align="center">Ficha</td>
        <td class='cabec' align="center">Hs Inicial</td>
        <td class='cabec' align="center">Hs Final</td>
        <td class='cabec' align="center">Reserva</td>
        <td class='cabec' align="center">Tipo Grade</td>
        <td class='cabec' align="center">CGS</td>
        <td class='cabec' align="center">Nome do Paciente</td>
        <td class='cabec' align='center'>Opções</td>

      </tr>

    <?
    for ($h = 1; $h <= $iTotalFichas; $h++) {

      $id_ficha  = 0;
      $codigo    = 0;
      if ($lControleMensal) {
        $hora_ini = $obj_prestadorhorarios->s112_c_horaini;
      }

      if ($h > $nro_fichas && $linha >= $linhas) {

        $reservada= "Sim";
        $paciente = "-- R E S E R V A D A --";
        $cgs      = "";
        $natend   = "x x x x x";

      } else {

        $reservada = "Não";
        $paciente  = "---------";

        $cgs       = "";
        $natend    = "x x x x x";

        if ($linha < $linhas) {

          $obj_agenda = db_utils::fieldsMemory( $res_agenda, $linha );
          $id_ficha = $obj_agenda->s113_i_ficha;

          if ( ($id_ficha == $h) && ($obj_agenda->s113_i_prestadorhorarios == $obj_prestadorhorarios->s112_i_codigo) ) {

            $codigo   = $obj_agenda->s113_i_codigo;
            $cgs      = $obj_agenda->z01_i_numcgs;
            $paciente = $obj_agenda->z01_v_nome;

            /**
           * Verifica se é Cota Mensal e atribui a hora inicial na grid ao
           * lançar exame
           */
            if ($lControleMensal) {
              $hora_ini = $obj_agenda->s113_c_hora;
            }
            $linha++;
          } else if($h >= $nro_fichas) {

            $id_ficha = 0;
            $reservada= "Sim";
            $paciente = "-- R E S E R V A D A --";
            $cgs      = "";
            $natend   = "x x x x x";
          }
        }
      }
      $hora_fim = "";
      if( $intervalo != 0) {

         $hora_fim = cl_agendamentos_ext::somahora($hora_ini,$intervalo+$mi_interva1);
      }


      ?>
      <tr>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=($h)?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_ini,0,5) ?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=substr($hora_fim,0,5) ?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$reservada ?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$obj_prestadorhorarios->s112_c_tipograde=="I"?"Intervalo":"Período" ?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$cgs ?></td>
        <td style="border:1px solid #AACCCC;"   class='corpo' align="center"><?=$paciente ?></td>
        <td class='corpo' nowrap>
          <a title='Lançar conteúdo da linha'  href='#' onclick="js_lancar(<?=$codigo?>,<?=$obj_prestadorhorarios->s112_i_codigo?>,'<?=$ano?>','<?=$mes?>','<?=$dia?>',<?=($h)?>,'<?=substr($hora_ini,0,5) ?>', '<?=$tipo?>');">&nbsp;L&nbsp;</a>
          &nbsp;&nbsp;
          <a title='Excluir conteúdo da linha' href='#' onclick='js_excluir(<?=$codigo?>);return false;'>&nbsp;E&nbsp;</a>
          &nbsp;&nbsp;
          <a title='Comprovante de Agendamento' href='#' onclick='js_comprovante(<?=$codigo?>);return false;'>&nbsp;C&nbsp;</a>
        </td>
      </tr>
      <?
        if( $intervalo != 0){
        $hora_ini    = cl_agendamentos_ext::somahora($hora_ini,($intervalo+$mi_interva2));
        $mi_interva1 = -1;
        $mi_interva2 = 0;
        }
    } // fim for h

  }//fim for
//}//fim if ausencias
  if ($lSemSaldo || $lMunicipioSemSaldo) {

    $sMensagem = "<tr><td colspan='8'>Prestadora sem Saldo para agendamentos deste exame.</td></tr>";

    if ($lMunicipioSemSaldo) {
      $sMensagem = "<tr><td colspan='8'>Município sem Saldo para agendamentos deste exame.</td></tr>";
    }

    echo $sMensagem;
  }
 ?>
</table>
</body>
</html>

<script>

function js_comprovante( s113_i_codigo ){

  if( s113_i_codigo != 0 ){
    x  = 'sau2_sau_agendaexames001.php';
    x += '?s113_i_codigo='+s113_i_codigo;
    x += '&diasemana='+parent.document.form1.diasemana.value;

    jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }else{
    alert('Paciente não informado.');
  }

}

function js_lancar(id_ficha,s112_i_codigo, ano, mes, dia, sd23_i_ficha, sd23_c_hora, tipo){

  if( id_ficha == undefined || id_ficha == 0 ){
    iTop  = ( screen.availHeight-600 ) / 2;
    iLeft = ( screen.availWidth-600 ) / 2;
    x  = 'sau4_sau_agendaexames003.php';
    x += '?s112_i_codigo='+s112_i_codigo;
    x += '&s113_d_exame='+ano+'/'+mes+'/'+dia;
    x += '&s113_i_ficha='+sd23_i_ficha;
    x += '&s113_c_hora='+sd23_c_hora;
    x += '&tipo='+tipo;
    x += '&db_opcao=1';

    js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, iTop, iLeft, 600, 230);

  }else{
    alert('Já foi lançado registro.');
  }

}


function js_excluir(id_ficha,z01_i_numcgs){
  if( id_ficha != undefined && id_ficha != 0 ){
    iTop  = ( screen.availHeight-600 ) / 2;
    iLeft = ( screen.availWidth-600 ) / 2;
    x  = 'sau4_sau_agendaexames003.php';
    x += '?chavepesquisaagenda='+id_ficha;
    x += '&db_opcao=3';

    js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, iTop, iLeft, 600, 200);
  }else{
    alert('Registro não pode ser excluído.');
  }
}

</script>
