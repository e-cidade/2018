<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
include_once("libs/db_jsplibwebseller.php");
include_once("classes/db_agendamentos_ext_classe.php");
include_once("classes/db_undmedhorario_ext_classe.php");
include_once("classes/db_ausencias_ext_classe.php");
include_once("classes/db_sau_upsparalisada_ext_classe.php");
include_once("dbforms/db_funcoes.php");
db_app::load("prototype.js");

function verifica_ausencia($hora_ini, $hora_fim, $ausencia, &$iIndice){

  //Ausências
  global $iIndicePeriodoAusencia;
  $cfm=false;
  $vet_hora_ini=explode(":",$hora_ini);
  $vet_hora_fim=explode(":",$hora_fim);
  for ($x=0; $x<count($ausencia); $x++) {

    $vet_aus_ini=explode(":",$ausencia[$x]["sd06_c_horainicio"]);
    $vet_aus_fim=explode(":",$ausencia[$x]["sd06_c_horafim"]);
    $minutos_ini=($vet_hora_ini[0]*60)+$vet_hora_ini[1];
    $minutos_fim=($vet_hora_fim[0]*60)+$vet_hora_fim[1];
    $minutos_aus_ini=($vet_aus_ini[0]*60)+$vet_aus_ini[1];
    $minutos_aus_fim=($vet_aus_fim[0]*60)+$vet_aus_fim[1];
    //echo"<br>hora_ini:$hora_ini hora_fim:$hora_fim  | hora_aus_ini:".$ausencia[$x]["sd06_c_horainicio"]." hora_aus_fim:".$ausencia[$x]["sd06_c_horafim"]."|";
    //echo"<br>IF (
    //  ((MI$minutos_ini >= MAI$minutos_aus_ini)&&(MI$minutos_ini <= MAF$minutos_aus_fim))
    //    ||
    // ((MF$minutos_fim >= MAI$minutos_aus_ini)&&(MF$minutos_fim <= MAF$minutos_aus_fim))
    // ){";
    if(
       (($minutos_ini >= $minutos_aus_ini)&&($minutos_ini <= $minutos_aus_fim))
       ||
       (($minutos_fim > $minutos_aus_ini)&&($minutos_fim <= $minutos_aus_fim))
      ) {
      //echo"true}<br>";
      $iIndice = $x;
      return true;
    }//else{echo"false}<br>";}
  }
   return false;

}

$sLoad = '';
if (isset($lMarcarAgendamentos) && $lMarcarAgendamentos) {
  $sLoad = 'parent.js_marcarAgendamentosSelecionados();';
}

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
.corpo2 {
  font-size: 9;
  color: black;
  background-color:white;
}
.corpoAmarelo {
  font-size: 9;
  color: black;
  background-color:#FFFFAA;
}
.opcoes {
  font-size: 16;
  font-weight: bold;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;<?=$sLoad?>" >
<?
db_postmemory($HTTP_POST_VARS);

$ano                         = substr( $sd23_d_consulta, 6, 4 );
$mes                         = substr( $sd23_d_consulta, 3, 2 );
$dia                         = substr( $sd23_d_consulta, 0, 2 );
$clagendamentos              = new cl_agendamentos_ext;
$clundmedhorario             = new cl_undmedhorario_ext;
$clausencias                 = new cl_ausencias_ext;
$clsau_upsparalisada         = new cl_sau_upsparalisada_ext;
$sAusenciaPorCodGradeHorario = "select s139_c_descr from ausencias
                                 inner join sau_motivo_ausencia on ausencias.sd06_i_tipo = sau_motivo_ausencia.s139_i_codigo
                                 where sd06_i_undmedhorario=sd30_i_codigo
                                   and '$ano/$mes/$dia' between ausencias.sd06_d_inicio and ausencias.sd06_d_fim";
$sSql = $clundmedhorario->sql_query_ext(null ,
                                        "*, (select count(sd23_d_consulta)
                                             from agendamentos
                                             where sd23_d_consulta = '$ano/$mes/$dia'
                                               and not exists ( select *
                                             from agendaconsultaanula
                                             where s114_i_agendaconsulta = sd23_i_codigo)
                                               and sd23_i_undmedhor  = undmedhorario.sd30_i_codigo
                                             )as total_agendado
                                          , ($sAusenciaPorCodGradeHorario) as ausencia_grade",
                                        "sd30_i_diasemana, sd30_c_horaini",
                                        "sd27_i_codigo = $sd27_i_codigo
                                         and sd30_i_diasemana = $chave_diasemana
                                         and ( sd30_d_valfinal is null or
                                              ( sd30_d_valfinal is not null and sd30_d_valfinal >= '$ano/$mes/$dia' ))
                                         and ( sd30_d_valinicial is null or
                                              ( sd30_d_valinicial is not null and sd30_d_valinicial <= '$ano/$mes/$dia')
                                             ) ");

$result_undmedhorario = $clundmedhorario->sql_record($sSql) ;
if( $clundmedhorario->numrows == 0  ){

  echo "<script>
          alert('Data inválida para esse profisisonal.');
          parent.document.form1.sd23_d_consulta.value='';
          parent.document.form1.diasemana.value='';
          parent.document.form1.sd23_d_consulta.focus();
        </script>";
  exit;

}
$reservadas    = 0;
$nro_fichas    = 0;
$nro_agendados = 0;
$str_tipograde = "";
$str_separador = "";
$linhas        = 0;
//Calcula nro de fichas/reserva

for( $xHora=0; $xHora < $clundmedhorario->numrows; $xHora++ ){

  $obj_undmedhorario  = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
  $reservadas        += $obj_undmedhorario->sd30_i_reservas;
  $nro_fichas        += $obj_undmedhorario->sd30_i_fichas;
  $nro_agendados     += $obj_undmedhorario->total_agendado;
  $str_tipograde     .= $str_separador.($obj_undmedhorario->sd30_c_tipograde=="I"?" Intervalo ":" Período ");
  $str_separador      = "/";

}
$int_size = strlen($str_tipograde)>80?80:strlen($str_tipograde);

//verifica se é definida variável de transferencia
// if( !isset( $sTransf ) ){

//   echo "<script>";
//   echo " parent.document.form1.saldo.value=".($nro_fichas+$reservadas-$nro_agendados);
//   echo " ;parent.document.form1.sd30_i_fichas.value=".($nro_fichas);
//   echo " ;parent.document.form1.sd30_i_reservas.value=".($reservadas);
//   echo " ;parent.document.getElementById('sd30_c_tipograde').setAttribute('maxlength', 100);";
//   echo " ;parent.document.getElementById('sd30_c_tipograde').setAttribute('size', $int_size);";
//   echo " ;parent.document.form1.sd30_c_tipograde.value='".$str_tipograde."'";
//   echo "</script>";

// }
?>
<table  id          = "tbl_agendados"
        border      = "0"
        cellspacing = "2px"
        width       = "100%"
        cellpadding = "1px"
        bgcolor     = "#cccccc"
>
<?

//Unidade Paralisada
$sSql=$clsau_upsparalisada->sql_query_ext(null,
                                      "s139_c_descr,".
                                      "s140_c_horaini,".
                                      "s140_c_horafim",
                                      's140_c_horaini asc',
                                      "s140_i_unidade = ".db_getsession("DB_coddepto").
                                      " and '$ano/$mes/$dia' between s140_d_inicio and s140_d_fim ");

$rsParalisado = $clsau_upsparalisada->sql_record($sSql);

//Ausências
$sCampos  = " s139_c_descr as sd06_c_tipo, ";
$sCampos .= " sd06_c_horainicio, ";
$sCampos .= " sd06_c_horafim, ";
$sCampos .= " sd06_i_undmedhorario, ";
$sCampos .= " sau_motivo_ausencia.s139_c_descr as motivo";

$sWhere   = " sd06_i_especmed = $sd27_i_codigo ";
$sWhere  .= " and '$ano/$mes/$dia' ";
$sWhere  .= " between sd06_d_inicio and sd06_d_fim ";

$sSql     = $clausencias->sql_query_ext(null,
                                        $sCampos,
                                        'sd06_c_horainicio asc',
                                        $sWhere);
$result_ausencias = $clausencias->sql_record($sSql);

$iIndicePeriodoAusencia = 0;
$iGradePorTempo         = 0;
$sMotivosausencias      = "";
$sSep                   = "";
$iAusenciaTotal         = 0;
for ($y=0; $y < $clausencias->numrows; $y++) {

    $obj_ausencias = db_utils::fieldsMemory($result_ausencias,$y);
    $sMotivosausencias .= $sSep.$obj_ausencias->motivo;
    $sSep               = ", ";
    if(($obj_ausencias->sd06_i_undmedhorario == "") && ($obj_ausencias->sd06_c_horainicio != "")){

      $aPeriodoAusencias[$y]["sd06_c_horainicio"] = $obj_ausencias->sd06_c_horainicio;
      $aPeriodoAusencias[$y]["sd06_c_horafim"]    = $obj_ausencias->sd06_c_horafim;
      $aPeriodoAusencias[$y]['motivo']            = $obj_ausencias->motivo;
      $iGradePorTempo++;

    } elseif (($obj_ausencias->sd06_i_undmedhorario == "") && ($obj_ausencias->sd06_c_horainicio == "")){
      $iAusenciaTotal = 1;
    }

}
for ($i=$y; $i < ($clsau_upsparalisada->numrows+$y); $i++) {

    $obj_paralizado                             = db_utils::fieldsMemory($rsParalisado,$y);
    $aPeriodoAusencias[$y]["sd06_c_horainicio"] = $obj_paralizado->s140_c_horaini;
    $aPeriodoAusencias[$y]["sd06_c_horafim"]    = $obj_paralizado->s140_c_horafim;
    $aPeriodoAusencias[$y]['motivo']            = $obj_paralizado->s139_c_descr;

}
$in = true;
if ($clausencias->numrows > 0 && $iGradePorTempo == 0) {

  if (($clundmedhorario->numrows <= $clausencias->numrows) || ($iAusenciaTotal == 1)) {?>

      <tr class='cabec'>
        <td align="center">
          <font size="4" color="red">Motivo de aus&ecirc;ncia do Profissional: <?=$sMotivosausencias ?></font>
        </td>
      </tr>
     <?
     $in=false;
  }
}else if ($clsau_upsparalisada->numrows > 0) {

  $oParalisado = db_utils::fieldsMemory($rsParalisado,0);
  if ($oParalisado->s140_c_horaini == "") {?>

    <tr class='cabec'>
      <td align="center">
        <font size="4"  color="red">Unidade Paralisada. Motivo: <?=$oParalisado->s139_c_descr?></font>
      </td>
    </tr>
    <?$in=false;

  }
}
if ($in == true) {

  $intCountagenda2 = 0;
  for ($xHora=0; $xHora < $clundmedhorario->numrows; $xHora++) {

    $obj_undmedhorario     = db_utils::fieldsMemory( $result_undmedhorario, $xHora );
    //Se a grede estiver ausente mostrar o motivo
    if($obj_undmedhorario->ausencia_grade != ""){
      ?>
      <tr class='cabec' id="<?=trim($obj_undmedhorario->sd30_i_codigo)?>">
        <td colspan="8" align="center">
          </b>
          <font size="4"  color="red">
            Motivo de aus&ecirc;ncia do Profissional: <?=$obj_undmedhorario->ausencia_grade?>
          </font>
          </b>
        </td>
      </tr>
      <?
    }else{
    $reservadas            = $obj_undmedhorario->sd30_i_reservas;
    $nro_fichas            = $obj_undmedhorario->sd30_i_fichas+$reservadas;
    $hora_ini2             = $hora_ini = $obj_undmedhorario->sd30_c_horaini;
    $hora_fim2 = $hora_fim = $obj_undmedhorario->sd30_c_horafim;
    $minutostrabalhados    = $clagendamentos->minutos($hora_ini,$hora_fim);
    $intervalo2            = 0;
    $intervalo             = 0;
    $strOrdem              = "sd23_i_undmedhor, sd23_d_consulta, sd23_i_codigo";
    if($nro_fichas != 0 && $obj_undmedhorario->sd30_c_tipograde == 'I'){
      $intervalo           = number_format($minutostrabalhados / $nro_fichas,2,'.','');
      $strOrdem            = "sd23_i_undmedhor, sd23_d_consulta, sd23_i_ficha, sd23_i_codigo";
    }else{
      if ($nro_fichas != 0) {
        $intervalo2 = number_format($minutostrabalhados / $nro_fichas,2,'.','');
      }
    }
    //Agenda
    $sCampos    = " sd23_i_codigo, sd23_i_ano, sd23_i_mes, sd23_i_ficha, z01_i_numcgs, z01_v_nome, sd23_i_upssolicitante, ";
    $sCampos   .= " sd23_i_undmedhor, sd23_i_presenca, sd23_t_obs, s102_i_prontuario ";
    $sWhere     = " sd23_d_consulta = '$ano/$mes/$dia' and sd23_i_undmedhor = {$obj_undmedhorario->sd30_i_codigo} ";
    $sSql       = $clagendamentos->sql_query_ext("", $sCampos, $strOrdem, $sWhere);

    $res_agenda = $clagendamentos->sql_record( $sSql );// or die( db_msgbox( pg_errormessage() ) );
    $linhas     = $clagendamentos->numrows;
    $linha      = 0;
    if( $linhas >= $nro_fichas ){
      $reservadas = 0;
      $nro_fichas = $linhas;
    }else{
      $dif = $nro_fichas-$linhas;
      if ($dif < $reservadas) {
        $reservadas = $dif;
      }
    }
    $diferenca   = $nro_fichas-$reservadas;
    $mi_interva1 = 0;
    $mi_interva2 = 1;

    if (isset($lUnificado)) {
      $sDisplayCabecalho = 'none';
    } else {
      $sDisplayCabecalho = "''";
    }

    if (isset($lMostraSeq)) {
      $sDisplaySeq = "''";
    } else {
      $sDisplaySeq = 'none';
    }

    if (!isset($lEscondeFicha)) {
      $sDisplayFicha = "''";
    } else {
      $sDisplayFicha = 'none';
    }

    if (isset($lMostraTipoFicha)) {
      $sDisplayTipoFicha = "''";
    } else {
      $sDisplayTipoFicha = 'none';
    }

    if (!isset($lEscondeHoraFim)) {
      $sDisplayHoraFim = "''";
    } else {
      $sDisplayHoraFim = 'none';
    }

    if (!isset($lEscondeReserva)) {
      $sDisplayReserva = "''";
    } else {
      $sDisplayReserva = 'none';
    }

    if (!isset($lEscondeTipoGrade)) {
      $sDisplayTipoGrade = "''";
    } else {
      $sDisplayTipoGrade = 'none';
    }

    if (isset($lMostraObs)) {
      $sDisplayObs = "''";
    } else {
      $sDisplayObs = 'none';
    }

    if (!isset($lEscondeCkBox)) {
      $sDisplayCkBox = "''";
    } else {
      $sDisplayCkBox = 'none';
    }

    ?>
    <tr class='cabec' id="<?=trim($obj_undmedhorario->sd30_i_codigo)?>" style="display: <?=$sDisplayCabecalho?>;">
      <td colspan="8" align="left">
                <? if (isset($sTransf) && $sTransf == "true" && $sLado == "para") { //verifica se vem da transferencia ?>
                    <input type    = "checkbox"
                           name    = "ckboxPara"
                           value   = "<?=trim($obj_undmedhorario->sd30_i_codigo)?>"
                           id      = "ckboxPara_<?=$obj_undmedhorario->sd30_i_codigo?>"
                           onclick = "js_marcarUm( <?=trim($obj_undmedhorario->sd30_i_codigo)?>, 'ckboxPara' )"
                    >
                <? } elseif (!isset($sTransf)) { ?>
                    <img src="skins/img.php?file=Controles/seta_down.png" onclick="js_ocultar(this,<?=$obj_undmedhorario->sd30_i_codigo ?>)">
                <? } ?>
                <?=$obj_undmedhorario->sd30_i_codigo." - ".$obj_undmedhorario->sd101_c_descr ?>
      </td>
    </tr>
    <tr class='cabec' <? if (isset($lUnificado) && $xHora != 0) { echo 'style="display: none;"'; } ?> >
      <? if( isset($sTransf) && $sTransf == "true" && $sLado == "de") { //verifica se vem da transferencia lado de ?>
      <td class="cabec" align="center" style="display: <?=$sDisplayCkBox?>;">
        <input type="button"
               value="M"
               id="<?="marcarTodos_".trim($obj_undmedhorario->sd30_i_codigo)?>"
               name="marcarTodos"
               onclick="js_marcarTodos(this, '<?=(isset($lUnificado) ? '' : trim($obj_undmedhorario->sd30_i_codigo))?>');">
      </td>
      <?
      } elseif (isset($sTransf) && $sTransf == "true" && $sLado == "para") { //verifica se vem da transferencia lado para
      ?>
        <td class="cabec" align="center" style="display: <?=$sDisplayCkBox?>">
          <input type="button"
               value="M"
               id="<?="marcarTodos_".trim($obj_undmedhorario->sd30_i_codigo)?>"
               name="marcarTodos"
               onclick="js_marcarTodos(this, '<?=trim($obj_undmedhorario->sd30_i_codigo)?>');">
      </td>
      <?
      }

      ?>
      <td class='cabec' align="center" style=" display: <?=$sDisplaySeq?>;">Seq</td>
      <td class='cabec' align="center" style=" display: <?=$sDisplayFicha?>;">Ficha</td>
      <td class='cabec' align="center" style=" display: <?=$sDisplayTipoFicha?>;">Tipo Ficha</td>
      <td class='cabec' align="center">Hs Inicial</td>
      <td class='cabec' align="center" style=" display: <?=$sDisplayHoraFim?>;">Hs Final</td>
      <td class='cabec' align="center" style=" display: <?=$sDisplayReserva?>;">Reserva</td>
      <td class='cabec' align="center" style=" display: <?=$sDisplayTipoGrade?>;">Tipo Grade</td>
      <td class='cabec' align="center">CGS</td>
      <td class='cabec' align="center">Nome do Paciente</td>
      <td class='cabec' align="center" >Opções</td>

    </tr>
      <?$arrAgenda = array(
                            array("codigo"=>"",
                                  "h"=>"",
                                  "hora_ini"=>"",
                                  "hora_fim"=>"",
                                  "reservada"=>"",
                                  "sd30_c_tipograde"=>"",
                                  "cgs"=>"",
                                  "paciente"=>"",
                                  "ano"=>"",
                                  "mes"=>"",
                                  "dia"=>"",
                                  "solicitante"=>"",
                                  "ausente"=>false
                                 )
                          );
      for ($h=0; $h < $nro_fichas; $h++) {
        $arrAgenda[$h]["codigo"]            = "";
        $arrAgenda[$h]["h"]                 = "";
        $arrAgenda[$h]["hora_ini"]          = "";
        $arrAgenda[$h]["hora_fim"]          = "";
        $arrAgenda[$h]["reservada"]         = "";
        $arrAgenda[$h]["sd30_c_tipograde"]  = "";
        $arrAgenda[$h]["cgs"]               = "";
        $arrAgenda[$h]["solicitante"]       = "";
        $arrAgenda[$h]["paciente"]          = "";
        $arrAgenda[$h]["ano"]               = "";
        $arrAgenda[$h]["mes"]               = "";
        $arrAgenda[$h]["dia"]               = "";
        // $arrAgenda[$h]["hora_ini2"]         = "";
        // $arrAgenda[$h]["hora_fim2"]         = "";
        // $arrAgenda[$h]["ausente"]           = "";
        // $arrAgenda[$h]["presente"]          = "";
        // $arrAgenda[$h]["obs"]               = "";
      }

      for ($h=0; $h < $nro_fichas; $h++) {

        $nro_ficha   = str_pad($h,3,0,STR_PAD_LEFT);
        $id_ficha    = $h+1;
        $codigo      = 0;
        $iProntuario = "";
        if ($h >= $diferenca && $linha >= $linhas) {

          $reservada= "Sim";
          $paciente = "-- R E S E R V A D A --";
          $cgs      = "";
          $solicitante = "";
          $natend   = "x x x x x";

        } else {

          $reservada   = "Não";
          $paciente    = "---------";
          $cgs         = "";
          $solicitante = "";
          $natend      = "x x x x x";
          $iProntuario = "";

          if ($linha < $linhas) {

            $obj_agenda = db_utils::fieldsMemory( $res_agenda, $linha );

            $lPresente  = $obj_agenda->sd23_i_presenca == 1 ? true : false;
            $sObs       = $obj_agenda->sd23_t_obs;
            $id_ficha   = $obj_agenda->sd23_i_ficha;
            if( ($obj_agenda->sd23_i_undmedhor == $obj_undmedhorario->sd30_i_codigo) &&
                ( $intervalo == 0 || $id_ficha == 0 || ($intervalo != 0 && ($id_ficha == ($h+1)) ) )
              ){

              $codigo      = $obj_agenda->sd23_i_codigo;
              $cgs         = $obj_agenda->z01_i_numcgs;
              $paciente    = $obj_agenda->z01_v_nome;
              $solicitante = $obj_agenda->sd23_i_upssolicitante;
              $iProntuario = $obj_agenda->s102_i_prontuario;
              $linha++;

            } elseif ($h >= $diferenca) {

              $id_ficha = 0;
              $reservada= "Sim";
              $paciente = "-- R E S E R V A D A --";
              $cgs      = "";
              $solicitante = "";
              $natend   = "x x x x x";

            }else{
              $id_ficha = $h+1;
            }
          } else {

            $lPresente = false;
            $sObs      = '';

          }
        }

        if ($intervalo != 0) {
          $hora_fim2=$hora_fim = $clagendamentos->somahora($hora_ini,$intervalo+$mi_interva1);
        } else {

          $hora_fim = "";
          $hora_fim2 =  $clagendamentos->somahora($hora_ini2,$intervalo2+$mi_interva1);

        }
        //tem id_ficha
        if ((int)$id_ficha > 0 && $obj_undmedhorario->sd30_c_tipograde == 'I') {
          if ((int)$arrAgenda[$id_ficha-1]["codigo"] > 0) {

            db_msgbox("ERRO:\\n\\nFoi modificado a Grade de horário do profissional, ocorrendo confrondo do número da ".
                      "ficha para $paciente com {$arrAgenda[$id_ficha-1]["paciente"]}\\n\\n Entre em contato com o ".
                      "responsável.");

          }

          $arrAgenda[$id_ficha-1]["codigo"]           = $codigo;
          $arrAgenda[$id_ficha-1]["h"]                = $h;
          $arrAgenda[$id_ficha-1]["hora_ini"]         = "$hora_ini";
          $arrAgenda[$id_ficha-1]["hora_fim"]         = "$hora_fim";
          $arrAgenda[$id_ficha-1]["hora_ini2"]        = "$hora_ini2";
          $arrAgenda[$id_ficha-1]["hora_fim2"]        = "$hora_fim2";
          $arrAgenda[$id_ficha-1]["reservada"]        = "$reservada";
          $arrAgenda[$id_ficha-1]["sd30_c_tipograde"] = $obj_undmedhorario->sd30_c_tipograde;
          $arrAgenda[$id_ficha-1]["cgs"]              = "$cgs";
          $arrAgenda[$id_ficha-1]["paciente"]         = "$paciente";
          $arrAgenda[$id_ficha-1]["solicitante"]      = "$solicitante";
          $arrAgenda[$id_ficha-1]["ano"]              = $ano;
          $arrAgenda[$id_ficha-1]["mes"]              = $mes;
          $arrAgenda[$id_ficha-1]["dia"]              = $dia;
          $arrAgenda[$id_ficha-1]["ausente"]          = false;
          $arrAgenda[$id_ficha-1]["presente"]         = $lPresente && $codigo != 0;
          $arrAgenda[$id_ficha-1]["obs"]              = $codigo != 0 ? $sObs : '';
          $arrAgenda[$id_ficha-1]["iProntuario"]      = $iProntuario;

        } else {

          //não tem id_ficha
          for($intCountagenda=0; $intCountagenda < sizeof($arrAgenda); $intCountagenda++){
            if( $arrAgenda[$intCountagenda]["codigo"] == "") {

              $arrAgenda[$intCountagenda]["codigo"]="$codigo";
              $arrAgenda[$intCountagenda]["h"]=$h;
              $arrAgenda[$intCountagenda]["hora_ini"]="$hora_ini";
              $arrAgenda[$intCountagenda]["hora_fim"]="$hora_fim";
              $arrAgenda[$intCountagenda]["hora_ini2"]="$hora_ini2";
              $arrAgenda[$intCountagenda]["hora_fim2"]="$hora_fim2";
              $arrAgenda[$intCountagenda]["sd30_c_tipograde"]=$obj_undmedhorario->sd30_c_tipograde;
              $arrAgenda[$intCountagenda]["cgs"]=$cgs;
              if ($reservada == "Não" || ($reservada == "Sim" && $intCountagenda >= $diferenca )) {

                $arrAgenda[$intCountagenda]["reservada"]="$reservada";
                $arrAgenda[$intCountagenda]["paciente"]="$paciente";
                $arrAgenda[$intCountagenda]["solicitante"]="$solicitante";

              } else {
                $arrAgenda[$intCountagenda]["solicitante"] = "";
              }
              $arrAgenda[$intCountagenda]["ano"]=$ano;
              $arrAgenda[$intCountagenda]["mes"]=$mes;
              $arrAgenda[$intCountagenda]["dia"]=$dia;
              $arrAgenda[$intCountagenda]["ausente"]=false;
              $arrAgenda[$intCountagenda]["presente"] = $lPresente  && $codigo != 0;
              $arrAgenda[$intCountagenda]["obs"] = $codigo != 0 ? $sObs : '';
              $arrAgenda[$intCountagenda]["iProntuario"]    = $iProntuario;
              break;

            }
          }
        }
        if ($intervalo != 0) {

          $hora_ini2   = $hora_ini = $clagendamentos->somahora($hora_ini,($intervalo+$mi_interva2));
          $mi_interva1 = -1;
          $mi_interva2 = 0;

        } else {

          $hora_ini2   = $clagendamentos->somahora($hora_ini2,($intervalo2+$mi_interva2));
          $mi_interva1 = -1;
          $mi_interva2 = 0;

      }
    } // fim for h
    $iFilaProx = 0;
    $iFilaDequeue = 0;
    $aAgendaTmp = array();

    for($intCountagenda=0; $intCountagenda < sizeof($arrAgenda); $intCountagenda++, $intCountagenda2++){


    // bloco que verifica se o profissional esta ausente no horario da ficha que esta sendo computada
    if(isset($aPeriodoAusencias)) {

        if(verifica_ausencia($arrAgenda[$intCountagenda]["hora_ini2"],$arrAgenda[$intCountagenda]["hora_fim2"],$aPeriodoAusencias, $iIndicePeriodoAusencia)) {

          if($arrAgenda[$intCountagenda]["codigo"] == 0) { // se nao tem agendamento nesta ficha, entao ela fica como ausente (verificacao importante
            $arrAgenda[$intCountagenda]['ausente'] = true; //  quando o tipo de grade eh por periodo)
          } else {

            if($intervalo == 0) { // caso a grade de horarios seja por periodo e tenha ausencia

              $aAgendaTmp[$iFilaProx] = $arrAgenda[$intCountagenda];
              $arrAgenda[$intCountagenda]['ausente'] = true;  // eh marcada ausencia nesta ficha e o cara que tava agendado eh jogado para o proximo horario vago
              $iFilaProx++;                   // para isso eh usado o $aAgendaTmp que contem os agendamentos que devem ser jogados para o proximo horario vago

            }

          }

        } else {

          if($arrAgenda[$intCountagenda]['codigo'] == 0 && $iFilaDequeue < $iFilaProx) { // se o horario esta vago e tem agendamentos que foram jogados para o
                                                                  // proximo horario vago, lanco um deles neste horario (isto somente em grade do tipo periodo)
            $arrAgenda[$intCountagenda]['ausente']          = false;
            $arrAgenda[$intCountagenda]["codigo"]           = $aAgendaTmp[$iFilaDequeue]["codigo"];
            $arrAgenda[$intCountagenda]["h"]                = $aAgendaTmp[$iFilaDequeue]["h"];
            $arrAgenda[$intCountagenda]["reservada"]        = $aAgendaTmp[$iFilaDequeue]["reservada"];
            $arrAgenda[$intCountagenda]["sd30_c_tipograde"] = $aAgendaTmp[$iFilaDequeue]["sd30_c_tipograde"];
            $arrAgenda[$intCountagenda]["cgs"]              = $aAgendaTmp[$iFilaDequeue]["cgs"];
            $arrAgenda[$intCountagenda]["paciente"]         = $aAgendaTmp[$iFilaDequeue]["paciente"];
            $arrAgenda[$intCountagenda]['presente']         = $aAgendaTmp[$iFilaDequeue]['presente'];
            $arrAgenda[$intCountagenda]['solicitante']      = $aAgendaTmp[$iFilaDequeue]['solicitante'];
            $arrAgenda[$intCountagenda]['obs']              = $aAgendaTmp[$iFilaDequeue]['obs'];
            $arrAgenda[$intCountagenda]['iProntuario']      = $aAgendaTmp[$iFilaDequeue]['iProntuario'];

            $iFilaDequeue++;

          }
        }

      }

    $codigo                     = $arrAgenda[$intCountagenda]["codigo"];
    $h                          = $arrAgenda[$intCountagenda]["h"];
    $hora_ini                   = $arrAgenda[$intCountagenda]["hora_ini"];
    $hora_fim                   = $arrAgenda[$intCountagenda]["hora_fim"];
    $reservada                  = $arrAgenda[$intCountagenda]["reservada"];
    $sd30_c_tipograde           = $arrAgenda[$intCountagenda]["sd30_c_tipograde"];
    $cgs                        = $arrAgenda[$intCountagenda]["cgs"];
    $paciente                   = $arrAgenda[$intCountagenda]["paciente"];
    $ano_linha                  = $arrAgenda[$intCountagenda]["ano"];
    $mes_linha                  = $arrAgenda[$intCountagenda]["mes"];
    $dia_linha                  = $arrAgenda[$intCountagenda]["dia"];
    $sClasse                    = $arrAgenda[$intCountagenda]['presente'] ? 'corpoAmarelo' : 'corpo';
    $sObs                       = $arrAgenda[$intCountagenda]['obs'];
    $solicitante                = $arrAgenda[$intCountagenda]['solicitante'];
    $sBotoesAcao                = '---------';
    $iProntuario                = $arrAgenda[$intCountagenda]['iProntuario'];
    if ( !empty($codigo) ) {

      $sBotoesAcao     = "<input type='button' value ='Comp' onclick='js_comprovante({$codigo});'>";
      $sDisabledBtnFaa = "disabled='disabled'";
      if ( !empty($iProntuario) ) {
        $sDisabledBtnFaa = "";
      }
      $sBotoesAcao .= "<input type='button' value ='FAA' {$sDisabledBtnFaa} onclick='js_emissaofaa({$codigo});'>";
    }

    if ($arrAgenda[$intCountagenda]["ausente"] == true) {
        ?>
        <tr bgcolor="white">
        <?
        if (isset($sTransf) && $sTransf == "true") {
        ?>
          <td id="td<?='_'.$intCountagenda2?>0" style="border:1px solid #AACCCC; display: <?=$sDisplayCkBox?>;" class='corpo2' align="center">
            <input type="checkbox" id="ckbox_<?=$intCountagenda2?>" name="ckbox"
              value="<?=$codigo.' ## '.$obj_undmedhorario->sd30_i_codigo.' ## '.$intCountagenda2?>" disabled>
            <!-- Campo hidden que indica se o horário está livre ou não. Seu valor consiste em duas informações
                 concatenadas por ' ## ': lLivre ## iIndiceLinha
            -->
            <input type="hidden" value="<?='false ## '.$intCountagenda2?>"
              name="lLivre<?=$obj_undmedhorario->sd30_i_codigo?>" id="livre_<?=$intCountagenda2?>">
            <!-- Campo hidden que vai receber as informações do agendamento que for transferido para este horário.
                 Este campo tem value vazio por default, o que indica que nenhum agendamento foi transferido para
                 o horário
            -->
            <input type="hidden" value="" name="transferencias" id="transf_<?=$intCountagenda2?>">

            <!-- Campo hidden que possui o horário de inicio previsto para o horário de agendamento (mesmo para
                 grades do tipo período)
            -->
            <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_ini2'], 0, 5)?>"
              name="horaini" id="horaini_<?=$intCountagenda2?>">

            <!-- Campo hidden que possui o horário de fim previsto para o horário de agendamento (mesmo para
                 grades do tipo período)
            -->
            <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_fim2'], 0, 5)?>"
              name="horafim" id="horafim_<?=$intCountagenda2?>">

            <!-- Campo hidden que possui o tipo de grade (I ou P) -->
            <input type="hidden" value="<?=$obj_undmedhorario->sd30_c_tipograde?>"
              name="tipograde" id="tipograde_<?=$intCountagenda2?>">

            <!-- Campo hidden que indica se o horário tem ou não ausência (true ou false). -->
            <input type="hidden" value="true"
              name="ausencia" id="ausencia_<?=$intCountagenda2?>">

          </td>
        <?
        }
        ?>

        <td id="td<?='_'.$intCountagenda2?>8" style="border:1px solid #AACCCC; display: <?=$sDisplaySeq?>;"
          class='corpo2' align="center"><?=($intCountagenda2 + 1)?></td>
        <td id="td<?='_'.$intCountagenda2?>1" style="border:1px solid #AACCCC; display: <?=$sDisplayFicha?>;"
          class='corpo2' align="center"><?=($intCountagenda+1)?></td>
        <td id="td<?='_'.$intCountagenda2?>9" style="border:1px solid #AACCCC; display: <?=$sDisplayTipoFicha?>;"
          class='corpo2' align="center"><?=$obj_undmedhorario->sd101_c_descr?></td>
        <td id="td<?='_'.$intCountagenda2?>2" style="border:1px solid #AACCCC;"   class='corpo2' align="center"><?=substr($hora_ini,0,5) ?></td>
        <td id="td<?='_'.$intCountagenda2?>3" style="border:1px solid #AACCCC; display: <?=$sDisplayHoraFim?>;"
          class='corpo2' align="center"><?=substr($hora_fim,0,5) ?></td>
        <td id="td<?='_'.$intCountagenda2?>4" style="border:1px solid #AACCCC; display: <?=$sDisplayReserva?>;"
          class='corpo2' align="center"><?=$reservada ?></td>
        <td id="td<?='_'.$intCountagenda2?>5" style="border:1px solid #AACCCC; display: <?=$sDisplayTipoGrade?>;"
          class='corpo2' align="center"><?=$obj_undmedhorario->sd30_c_tipograde=="I"?"Intervalo":"Período"?></td>
        <td id="td<?='_'.$intCountagenda2?>6" style="border:1px solid #AACCCC;"   class='corpo2' align="center"></td>
        <td id="td<?='_'.$intCountagenda2?>7" class='corpo2' nowrap>
           <b><?=$aPeriodoAusencias[$iIndicePeriodoAusencia]['motivo'];?></b></td>
        <td id="td<?='_'.$intCountagenda2?>10" style="border:1px solid #AACCCC; "  class='corpo' align="center">
          "---------"
        </td>
        <?
    } else {
      ?>
      <tr style="display:<?=$codigo != 0 && $intervalo == 0 && (($linhas - 1) > $intCountagenda) && !isset($sTransf) ? 'none' : '' ?>;"
        title="<?=$sObs?>"
          id="<?=$codigo!=0?($obj_undmedhorario->sd30_i_codigo.'_'.$intCountagenda):'' ?>" >
          <?
          if (isset($sTransf) && $sTransf == "true" && $sLado == "de") { //verifica se vem da transferencia

            $sDisabled = (!isset($codigo) || empty($codigo)) ? "disabled" : "";
          ?>
            <td id="td<?='_'.$intCountagenda2?>0" style="border: 1px solid #AACCCC; display: <?=$sDisplayCkBox?>;" class="<?=$sClasse?>" align="center" name="checkDe">
              <input type="checkbox" id="ckbox_<?=$intCountagenda2?>" name="ckbox"
                value="<?=$codigo.' ## '.$obj_undmedhorario->sd30_i_codigo.' ## '.$intCountagenda2?>" <?=$sDisabled?>>

                <!-- Campo hidden que indica se o horário está livre ou não. Seu valor consiste em duas informações
                     concatenadas por ' ## ': lLivre ## iIndiceLinha
                -->
                <input type="hidden" value="<?=($codigo == 0 ? 'true' : 'false').' ## '.$intCountagenda2?>"
                  name="lLivre<?=$obj_undmedhorario->sd30_i_codigo?>" id="livre_<?=$intCountagenda2?>">


                <!-- Campo hidden que vai receber as informações do agendamento que for transferido para este horário.
                     Este campo tem value vazio por default, o que indica que nenhum agendamento foi transferido para
                     o horário
                -->
                <input type="hidden" value="" name="transferencias" id="transf_<?=$intCountagenda2?>">

                <!-- Campo hidden que possui o horário de inicio previsto para o horário de agendamento (mesmo para
                     grades do tipo período)
                -->
                <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_ini2'], 0, 5)?>"
                  name="horaini" id="horaini_<?=$intCountagenda2?>">

                <!-- Campo hidden que possui o horário de fim previsto para o horário de agendamento (mesmo para
                     grades do tipo período)
                -->
                <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_fim2'], 0, 5)?>"
                  name="horafim" id="horafim_<?=$intCountagenda2?>">

                <!-- Campo hidden que possui o tipo de grade (I ou P) -->
                <input type="hidden" value="<?=$obj_undmedhorario->sd30_c_tipograde?>"
                  name="tipograde" id="tipograde_<?=$intCountagenda2?>">

                <!-- Campo hidden que indica se o horário tem ou não ausência (true ou false). -->
                <input type="hidden" value="false"
                  name="ausencia" id="ausencia_<?=$intCountagenda2?>">

            </td>
          <?
          } elseif (isset($sTransf) && $sTransf == "true" && $sLado == "para") {

          ?>
            <td id="td<?='_'.$intCountagenda2?>0" style="border: 1px solid #AACCCC; display: <?=$sDisplayCkBox?>;" class="<?=$sClasse?>" align="center" name="checkPara">
              <input type="checkbox" id="ckbox_<?=$intCountagenda2?>" name="ckbox"
                value="<?=$codigo.' ## '.$obj_undmedhorario->sd30_i_codigo.' ## '.$intCountagenda2?>" disabled>

                <!-- Campo hidden que indica se o horário está livre ou não. Seu valor consiste em duas informações
                     concatenadas por ' ## ': lLivre ## iIndiceLinha
                -->
                <input type="hidden" value="<?=($codigo == 0 ? 'true' : 'false').' ## '.$intCountagenda2?>"
                  name="lLivre<?=$obj_undmedhorario->sd30_i_codigo?>" id="livre_<?=$intCountagenda2?>">


                <!-- Campo hidden que vai receber as informações do agendamento que for transferido para este horário.
                     Este campo tem value vazio por default, o que indica que nenhum agendamento foi transferido para
                     o horário
                -->
                <input type="hidden" value="" name="transferencias" id="transf_<?=$intCountagenda2?>">

                <!-- Campo hidden que possui o horário de inicio previsto para o horário de agendamento (mesmo para
                     grades do tipo período)
                -->
                <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_ini2'], 0, 5)?>"
                  name="horaini" id="horaini_<?=$intCountagenda2?>">


                <!-- Campo hidden que possui o horário de fim previsto para o horário de agendamento (mesmo para
                     grades do tipo período)
                -->
                <input type="hidden" value="<?=substr($arrAgenda[$intCountagenda]['hora_fim2'], 0, 5)?>"
                  name="horafim" id="horafim_<?=$intCountagenda2?>">

                <!-- Campo hidden que possui o tipo de grade (I ou P) -->
                <input type="hidden" value="<?=$obj_undmedhorario->sd30_c_tipograde?>"
                  name="tipograde" id="tipograde_<?=$intCountagenda2?>">

                <!-- Campo hidden que indica se o horário tem ou não ausência (true ou false). -->
                <input type="hidden" value="false"
                  name="ausencia" id="ausencia_<?=$intCountagenda2?>">

            </td>

          <?
          }
          ?>
        <td id="td<?='_'.$intCountagenda2?>8" style="border:1px solid #AACCCC; display: <?=$sDisplaySeq?>;"
          class="<?=$sClasse?>" align="center"> <?=($intCountagenda2 + 1)?></td>
        <td id="td<?='_'.$intCountagenda2?>1" style="border:1px solid #AACCCC; display: <?=$sDisplayFicha?>;"
          class="<?=$sClasse?>" align="center"><?=($intCountagenda+1)?></td>
        <td id="td<?='_'.$intCountagenda2?>9" style="border:1px solid #AACCCC; display: <?=$sDisplayTipoFicha?>;"
          class="<?=$sClasse?>" align="center"><?=$obj_undmedhorario->sd101_c_descr?></td>
        <td id="td<?='_'.$intCountagenda2?>2" style="border:1px solid #AACCCC;"   class="<?=$sClasse?>" align="center"><?=substr($hora_ini,0,5) ?></td>
        <td id="td<?='_'.$intCountagenda2?>3" style="border:1px solid #AACCCC; display: <?=$sDisplayHoraFim?>;"
          class="<?=$sClasse?>" align="center"><?=substr($hora_fim,0,5) ?></td>
        <td id="td<?='_'.$intCountagenda2?>4" style="border:1px solid #AACCCC; display: <?=$sDisplayReserva?>;"
          class="<?=$sClasse?>" align="center"><?=$reservada ?></td>
        <td id="td<?='_'.$intCountagenda2?>5" style="border:1px solid #AACCCC; display: <?=$sDisplayTipoGrade?>;"
          class="<?=$sClasse?>" align="center"><?=$obj_undmedhorario->sd30_c_tipograde=="I"?"Intervalo":"Período" ?></td>
        <td id="td<?='_'.$intCountagenda2?>6" style="border:1px solid #AACCCC;"   class="<?=$sClasse?>" align="center"><?=$cgs ?></td>
        <td id="td<?='_'.$intCountagenda2?>7" style="border:1px solid #AACCCC;"   class="<?=$sClasse?>" align="center"><?=$paciente ?></td>
        <td id="td<?='_'.$intCountagenda2?>10" style="border:1px solid #AACCCC; " class=<?=$sClasse?> align="center">
          <?=$sBotoesAcao?>
        </td>

      </tr>
      <?
      }
    }//fim for countagenda

    }
  }//fim for
}//fim if ausencias
?>
</table>
</body>
</html>

<script>

//Tempo estimado para recarregar agenda
//window.setInterval( js_reload, 40000 );

<?
if (isset($lUnificado)) {
  echo 'js_habilitaTodos(false);';
}
?>

function js_reload(){
  location.reload();
}


function js_ocultar(obj,id){
  var src = obj.src;
  var ultimo_id = "";
  var table = document.all ? document.all.tbl_agendados : document.getElementById('tbl_agendados');
  id = ""+id+"";

  if( src.lastIndexOf("seta_down.png") != -1 ){
    obj.src = "skins/img.php?file=Controles/seta_up.png";
    for (var r = 0; r < table.rows.length; r++){
      var id2 = table.rows[r].id;
      if( id == id2.substr(0, id.length ) && id2.length > id.length ){
        table.rows[r].style.display = '';
      }
    }

  }else{
    obj.src = "skins/img.php?file=Controles/seta_down.png";
    for (var r = 0; r < table.rows.length; r++){
      var id2 = table.rows[r].id;
      if( id == id2.substr(0, id.length ) && id2.length > id.length ){
        table.rows[r].style.display = 'none';
        ultimo_id = r;
      }
    }
    if( ultimo_id != "" ){
      table.rows[ultimo_id].style.display = '';
    }
  }

}

function js_emissaofaa( sd23_i_codigo ) {

  if (sd23_i_codigo == 0) {
    alert('Paciente não informado.');
  } else {

    sd23_d_consulta = parent.document.getElementById('sd23_d_consulta').value;
    a       =  sd23_d_consulta.substr(6,4);
    m       = (sd23_d_consulta.substr(3,2))-1;
    d       =  sd23_d_consulta.substr(0,2);
    data    = new Date(a,m,d);
    dia     = data.getDay()+1;

    var oParam             = new Object();
    oParam.exec            = 'gerarFAATXT';
    oParam.lAgendamentoFaa = true;
    oParam.iUnidade        = parent.document.form1.sd02_i_codigo.value;
    oParam.iProfissional   = parent.document.form1.sd27_i_codigo.value;
    oParam.iDiasemana      = dia;
    oParam.sd23_d_consulta = sd23_d_consulta;
    oParam.iCodAgendamento = sd23_i_codigo;
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');

  }
}
function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {
    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }

}
function js_emitiefaaPDF (oDados) {


  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";

  WindowObjectReference = window.open(oDados.sArquivo+sChave,"CNN_WindowName", strWindowFeatures);

}
function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('top.corpo', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave,
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );
}

function js_comprovante( sd23_i_codigo ){
  parent.js_comprovante(sd23_i_codigo);
}

function js_lancar(id_ficha,sd30_i_codigo, ano, mes, dia, sd23_i_ficha, sd23_c_hora,sd23_c_fim,sTipoGrade){

  if( id_ficha == undefined || id_ficha == 0 ){

    data = new Date(ano,mes-1,dia);
    gnow = new Date(<?=date('Y',db_getsession('DB_datausu'))?>,<?=date('m',db_getsession('DB_datausu'))-1?>,<?=date('d',db_getsession('DB_datausu'))?>);
    hora = '"'+new Date(<?=date('H')?>)+'"';
    posicao = hora.indexOf(":", hora)
    hora = hora.substr(posicao-2,5);
    ok = false;

    if(sd23_c_fim != ""){
      if((data == gnow && sd23_c_fim <= hora ) || (data > gnow)){
        ok = true;
      }
    }else{
      ok = true;
    }

    if( data < gnow ){
      alert( 'Você não pode lançar uma agendamento anterior a data atual. ');
    }else if( ok = true ){
      iTop = ( screen.availHeight-600 ) / 2;
      iLeft = ( screen.availWidth-600 ) / 2;
      x  = 'sau4_agendamento003.php';
      x += '?sd30_i_codigo='+sd30_i_codigo;
      x += '&sd23_d_consulta='+ano+'/'+mes+'/'+dia;
      x += '&sd23_i_ficha='+sd23_i_ficha;
      x += '&sd23_c_hora='+sd23_c_hora;
      x += '&db_opcao=1';
      x += '&sd30_c_tipograde='+sTipoGrade;
      x += '&rh70_sequencial='+parent.document.form1.rh70_sequencial.value;
        x += '&sd02_i_codigo='+parent.document.form1.sd02_i_codigo.value;
        x += '&rh70_estrutural='+parent.document.form1.rh70_estrutural.value;
        x += '&iUpssolicitante='+parent.document.form1.upssolicitante.value;
      if( parent.document.form1.s125_i_procedimento != undefined ){
        x += '&s125_i_procedimento='+parent.document.form1.s125_i_procedimento.value;
      }

      js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Paciente',true, iTop, iLeft, 600, 200);

    }else{
      alert('Impossível agendar em dia e hora indicado.');
    }
  }else{
    alert('Já foi lançado registro.');
  }
}


function js_excluir(id_ficha,z01_i_numcgs){

  var permissao_anular = <?=db_permissaomenu(db_getsession("DB_anousu"),6952,8146)?>;

  if ( permissao_anular == false ) {
    alert('Você não tem permissão de menu para anular agendamentos!');
  } else {

    if( id_ficha != undefined && id_ficha != 0 ){
      iTop = ( screen.availHeight-600 ) / 2;
      iLeft = ( screen.availWidth-600 ) / 2;
      x  = 'sau1_agendaconsultaanula001.php';
      x += '?s114_i_agendaconsulta='+id_ficha;
      x += '&db_opcao=1';

      js_OpenJanelaIframe('parent','db_iframe_agendamento',x,'Anulação',true, iTop, iLeft, 600, 250);
    }else{
      alert('Registro não pode ser excluído.');
    }

  }

}

function js_emissaopm( cgs ){

  var permissao_emitirpm = <?=db_permissaomenu(db_getsession("DB_anousu"),1000004,1045403)?>;

  if ( permissao_emitirpm == false ) {
    alert('Você não tem permissão de menu para emitir prontuário médico!');
  } else {

    if( cgs != "" ){
      window.open('sau4_prontuariomedico003.php?cgs='+cgs,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }else{
      alert('Deverá informar um CGS.' );
    }

  }

}

function js_marcarTodos(oMarcaTodos, sIdGrade) {

  oElementos = document.getElementsByName('ckbox');
  var oDados = new Array();

  if (oMarcaTodos.value == 'M') {

    for (iIndice = 0; iIndice < oElementos.length; iIndice++) {

      if (sIdGrade == '') { // Marco todos
        oElementos[iIndice].checked = true;
      } else { // Marco somente os que possuirem id da grade = sIdGrade
        /* aDados[0] -> codigo do agendamento
           aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
           aDados[2] -> id da linha */
        aDados = oElementos[iIndice].value.split(' ## ');

        if (!oElementos[iIndice].disabled && aDados[1] == sIdGrade) {
          oElementos[iIndice].checked = true;
        }

      }

    }

    oMarcaTodos.value = 'D';

  } else {

    for (iIndice = 0; iIndice < oElementos.length; iIndice++) {

      if (sIdGrade == '') { // Desmarco todos
        oElementos[iIndice].checked = false;
      } else { // Desmarco somente os que possuirem id da grade = sIdGrade
        /* aDados[0] -> codigo do agendamento
          aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
          aDados[2] -> id da linha */
        aDados = oElementos[iIndice].value.split(' ## ');

        if (!oElementos[iIndice].disabled && aDados[1] == sIdGrade) {
          oElementos[iIndice].checked = false;
        }

      }

    }

    oMarcaTodos.value = 'M';

  }

}

function js_marcarUm(sID, sByName, lDesmarcaIdDiferente) {

  if (lDesmarcaIdDiferente == undefined) {
    lDesmarcaIdDiferente = true;
  }

  sID2 = sByName+"_"+sID;

  oElementos = document.getElementsByName( sByName );

  if (lDesmarcaIdDiferente) {
    //Desmarca todos que não o ID não seja igual do ID2
    for(iIndice = 0; iIndice < oElementos.length; iIndice++) {

        if( oElementos[iIndice].id != sID2 ) {

            oElementos[iIndice].checked = false;
            sMarcarTodos = "marcarTodos_"+oElementos[iIndice].id.substr(7, oElementos[iIndice].id.length );

            if( document.getElementById( sMarcarTodos ) != undefined ){

                document.getElementById( sMarcarTodos ).value = 'M';

            }
        }

    }

  }

}

function js_habilitaTodos(lAusentes) {

  if (lAusentes == undefined) {
    lAusentes = true;
  }

  iTam = document.getElementsByName('ckbox').length;
  for (var iIndice = 0; iIndice < iTam; iIndice++) {

    if (lAusentes) { // Habilito todos os ckbox, sem verificar se possui ausência
      document.getElementById('ckbox_'+iIndice).disabled = false;
    } else {

      // Só marco se não possuir ausência
      if (document.getElementById('ausencia_'+iIndice).value == 'false') {
        document.getElementById('ckbox_'+iIndice).disabled = false;
      }

    }

  }

}

</script>