<?
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$cldiasemana         = new cl_diasemana;
$clperiodoescola     = new cl_periodoescola;
$clregenciahorario   = new cl_regenciahorario;
$clturmaachorario    = new cl_turmaachorario;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clrechumano         = new cl_rechumano;
$oDaoRecHumanoEscola = new cl_rechumanoescola();

$db_opcao   = 1;
$db_botao   = true;
$escola     = db_getsession("DB_coddepto");
$lTemEscola = false;

$sCampos    = "min(ed17_h_inicio) as menorhorario,max(ed17_h_fim) as maiorhorario";
$result_per = $clperiodoescola->sql_record($clperiodoescola->sql_query( "",$sCampos, "", "" ) );

db_fieldsmemory($result_per,0);

$hora1         = (int)substr($menorhorario,0,2);
$hora2         = (int)substr($maiorhorario,0,2)+1;
$horainicial   = $hora1*100;
$horafinal     = $hora2*100;
$tempo_ini     = mktime($hora1,0,0,date("m"),date("d"),date("Y"));
$tempo_fim     = mktime($hora2,0,0,date("m"),date("d"),date("Y"));
$difer_minutos = ($tempo_fim-$tempo_ini)/60;
$alt_tab_hora  = $difer_minutos/2;
$qtd_hora      = $difer_minutos/60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 20;
$tabela1_left  = 2;
$sCampos       = " case when ed20_i_tiposervidor = 1 ";
$sCampos      .= "   then ed284_i_rhpessoal ";
$sCampos      .= "    else ed285_i_cgm ";
$sCampos      .= "   end as identificacao, ";
$sCampos      .= " case when ed20_i_tiposervidor = 1 ";
$sCampos      .= "   then cgmrh.z01_nome ";
$sCampos      .= "     else cgmcgm.z01_nome ";
$sCampos      .= "   end as z01_nome, ";
$sCampos      .= " case when ed20_i_tiposervidor = 1 ";
$sCampos      .= "   then cgmrh.z01_numcgm ";
$sCampos      .= "     else cgmcgm.z01_numcgm ";
$sCampos      .= "   end as z01_numcgm, ";
$sCampos      .= " ed20_i_tiposervidor,cgmcgm.z01_numcgm as rechumanocgm, "; 
$sCampos      .= " cgmrh.z01_numcgm as rhpessoalcgm ";
$sWhere        = " (ed20_i_codigo = $ed20_i_codigo or ed284_i_rechumano = $ed20_i_codigo)";
$result_cgm    = $clrechumano->sql_record($clrechumano->sql_query( "", $sCampos, "", $sWhere ) );

$sTabela = '';
if ($clrechumano->numrows > 0) {

  db_fieldsmemory($result_cgm,0);
  if (empty($rechumanocgm)) {

    $cgmprof = $rhpessoalcgm;
    $sTabela = 'cgmrh';
  } else {

    $cgmprof = $rechumanocgm;
    $sTabela = 'cgmcgm';
  }
} else {
  $cgmprof = 0;
}   

if (isset($identificacao)) {
	
  $where   = " ed20_i_codigo = $ed20_i_codigo";
  $destino = "";
} else {
	
  $where   = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $ed20_i_codigo";
  $destino = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
if (!isset($larg_obj)) {
	
  ?>
  <script>
    largura = document.body.clientWidth;
    location.href = "edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>&larg_obj="+largura;
  </script>
  <?
  exit;
  
}
unset($_SESSION["sess_corhorario"]);
$array_cores     = array("#FFCC99",
                         "#CCCCFF",
                         "#99FFCC",
                         "#CCFF66",
                         "#CC9933",
                         "#FF99FF",
                         "#996699",
                         "#66CC99",
                         "#FFCCCC",
                         "#9999FF"
                        );
$sess_corhorario = array();
$result_cor      = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                                "DISTINCT ed18_i_codigo,ed18_c_nome",
                                                                                "ed18_c_nome",
                                                                                " ed20_i_codigo = $ed20_i_codigo and ed58_ativo is true  "
                                                                               )
                                                 );
if ($clregenciahorario->numrows > 0) {
	
  for ($y = 0; $y < $clregenciahorario->numrows; $y++) {
  	
    db_fieldsmemory($result_cor,$y);
    $sess_corhorario[$ed18_i_codigo] = $array_cores[$y];
  }

  @session_register("sess_corhorario");
}
///////////////Matricula
?>
&nbsp;&nbsp;
<?db_input('ed20_i_codigo',15,@$Ied20_i_codigo,true,'hidden',3,"")?> 
<b><?=@$ed20_i_tiposervidor=='1'?'Matrícula:':'CGM:'?></b>
<?db_input('identificacao',10,@$identificacao,true,'text',3,"")?>
<?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
<?
///////////////Ano
 $result_ano = " select distinct ed52_i_ano from regenciahorario";
 $result_ano .= " inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
 $result_ano .= " inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
 $result_ano .= " inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
 $result_ano .= " inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
 $result_ano .= " inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
 $result_ano .= " inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
 $result_ano .= " inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
 $result_ano .= " inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
 $result_ano .= " inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
 $result_ano .= " inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
 $result_ano .= " inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
 $result_ano .= " where ed58_i_rechumano=$ed20_i_codigo and ed58_ativo is true  "; 
 $result_ano .= " union ";
 $result_ano .= " select distinct ed52_i_ano from turmaachorario ";
 $result_ano .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
 $result_ano .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
 $result_ano .= " where ed270_i_rechumano= $ed20_i_codigo order by ed52_i_ano desc";
 $resultano  = db_query($result_ano) ;
 $linhas2    = pg_num_rows($resultano);
if (!isset($calendario) && $linhas2 > 0) {
  $calendario = pg_result($resultano,0,'ed52_i_ano');
} else {
  $calendario = date("Y");
}
?>
<b>Ano:</b>
<select id="calendario" name="calendario" onchange="js_trocaAno(this.value);">
 <?
 for($x=0;$x<$linhas2;$x++){ 	
  $ed52_i_ano=pg_result($resultano,$x,'ed52_i_ano');
  ?>
  <option value="<?=$ed52_i_ano?>" <?=$ed52_i_ano==@$calendario?"selected":""?>><?=$ed52_i_ano?></option>
  <? 	
 }
 ?>
</select>
<?

$sWhereRecHumanoHoraDisp = "ed75_i_escola = {$escola} AND ed75_i_rechumano = {$ed20_i_codigo} AND ed33_ativo is true";
$sSqlRecHumanoHoraDisp   = $clrechumanohoradisp->sql_query( null, "ed75_i_codigo", null, $sWhereRecHumanoHoraDisp );
$rsRecHumanoHoraDisp     = db_query( $sSqlRecHumanoHoraDisp );
$lRecHumanoHoraDisp      = pg_num_rows( $rsRecHumanoHoraDisp ) > 0;

///////////////Escolas
 $resultano = " select distinct ed18_i_codigo,ed18_c_nome from regenciahorario";
 $resultano .= " inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorario.ed58_i_periodo";
 $resultano .= " inner join regencia  on  regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia";
 $resultano .= " inner join rechumano  on  rechumano.ed20_i_codigo = regenciahorario.ed58_i_rechumano";
 $resultano .= " inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorario.ed58_i_diasemana";
 $resultano .= " inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
 $resultano .= " inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
 $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
 $resultano .= " where ed58_i_rechumano=$ed20_i_codigo and ed52_i_ano=$calendario and ed58_ativo is true  ";
 $resultano .= " union ";
 $resultano .= " select distinct ed18_i_codigo, ed18_c_nome from turmaachorario ";
 $resultano .= " inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorario.ed270_i_turmaac";
 $resultano .= " inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
 $resultano .= " inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
 $resultano .= " where ed270_i_rechumano= $ed20_i_codigo and ed52_i_ano=$calendario order by ed18_c_nome";
 $resultano1 = db_query($resultano) ;
 $linhas3    = pg_num_rows($resultano1);
 $lTemEscola = $linhas3 > 0;
?>
<b>Escola:</b>
<select id="esc_horario" style="width:300px;" name="esc_horario" onchange="js_trocaEscola(this.value);">
 <option value="">TODAS</option>
 <?
 if( $lRecHumanoHoraDisp ) {

   for($x=0;$x<$linhas3;$x++){

     $ed18_i_codigo=pg_result($resultano1,$x,'ed18_i_codigo');
     $ed18_c_nome=pg_result($resultano1,$x,'ed18_c_nome');
     ?>
     <option value="<?=$ed18_i_codigo?>" <?=$ed18_i_codigo==@$esc_horario?"selected":""?>><?=$ed18_i_codigo?> - <?=$ed18_c_nome?></option>
   <?
   }
 }
 ?>
</select>
<!--Tabela Dias da Semana-->
<table id="id_dia" style="position:absolute;top:<?=$tabela1_top?>px;left:<?=$tabela1_left?>px;" 
       cellspacing="1" cellpadding="0">
 <tr height="20">
  <td align="center" width="<?=$larg_coluna1?>" 
      style="background:#444444;color:#DEB887"><a style="color:#DEB887;"><b>Horas</b></td>
  <td align="center" width="<?=$larg_coluna2?>" 
      style="background:#444444;color:#DEB887"><a style="color:#DEB887;">&nbsp;</td>
  <?
  $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                "ed32_i_codigo",
                                                                " ed04_i_escola = $escola AND ed04_c_letivo = 'S'"
                                                               )
                                    );
  $larg_dia = floor(($larg_tabela-$larg_coluna1-$larg_coluna2)/$cldiasemana->numrows);
  for ($x = 0; $x < $cldiasemana->numrows; $x++) {
  	
    db_fieldsmemory($result,$x)
    ?>
    <td align="center" width="<?=$larg_dia?>" style="background:#444444;color:#DEB887"><b><?=$ed32_c_descr?></b></td>
    <?
    
  }
  ?>
 </tr>
</table>
<!--Tabela de fundo-->
<table id="id_fundo" style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$tabela1_left+$larg_coluna1?>px;" 
       cellspacing="0" cellpadding="0">
 <?for ($x = 0; $x < $qtd_hora; $x++) {?>
 
    <tr bgcolor="#f3f3f3">
     <td align="center" width="<?=$larg_coluna2?>" height="<?=$alt_tab_hora/$qtd_hora?>" 
         style="border:1px solid #f3f3f3;">&nbsp;</td>
     <td width="<?=$larg_dia*$cldiasemana->numrows?>" height="<?=$alt_tab_hora/$qtd_hora?>" 
         style="border:1px solid #f3f3f3;">&nbsp;</td>
    </tr>
    
 <?}?>
</table>
<?
////////Grade dos horários
$top_ini = $tabela1_top+20; 
$tt      = 0;
for ($t = $horainicial; $t <= $horafinal; $t += 1) {
	
  $hora         = strlen($t) == 3?"0".$t:$t;
  $hora         = substr($hora,0,2).":".substr($hora,2,2);
  $id_hora      = "H".$hora;
  $id_hora2     = "HH".$hora;
  $id_linhahora = "LH".$hora;
  
  if ($t != 2400) {
  	
    if (($t%100) == 0) {
      $visible = "visible";
    } else {
      $visible = "hidden";
    }
    
    echo "<div id='$id_hora' style='visibility:$visible;position:absolute;top:".($top_ini).
               "px;left:".($larg_coluna1+$tabela1_left-38)."px;'><b>".$hora."</b></div>";
    echo "<div id='$id_hora2' style='color:#FF0000;visibility:hidden;position:absolute;top:".($top_ini).
               "px;left:".($larg_coluna1+$larg_coluna2+$tabela1_left-38)."px;'><b>".$hora."</b></div>";
    echo "<div id='$id_linhahora' style='width:".($larg_dia*$cldiasemana->numrows+$larg_coluna2).
               "px;height:1px;background:#FF0000;visibility:hidden;position:absolute;top:".($top_ini+5).
               "px;left:".($larg_coluna1+$tabela1_left)."px;'></div>";
    
  }
  $tt += 1;
  if ($tt == 60) {
  	
    $t += 40;
    $tt = 0;
    
  }
  $top_ini += 0.5;
}
////////Linhas verticais
$left_ini = $tabela1_left+$larg_coluna1+$larg_coluna2;
for ($x = 0; $x < $cldiasemana->numrows+1; $x++) {
	
  ?>
  <table border="0" style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$left_ini?>px;" 
         cellspacing="0" cellpadding="0">
    <tr>
     <td width="1" bgcolor="#000000" height="<?=$alt_tab_hora?>"></td>
    </tr>
  </table>
  <?
  $left_ini+=$larg_dia;
  
}

////////Horário do Docente
if( $lTemEscola && $lRecHumanoHoraDisp ) {

?>
  <table style="top:<?=isset( $ini_top ) ? $ini_top :  ""?>px;left:<?=isset( $ini_left ) ? $ini_left : ""?>px;"  cellspacing="0" cellpadding="0">
  <tbody id="disp_rechumano" style="position:absolute;z-index:1"><tr><td>
  <?

  $result0  = $clrechumano->sql_record($clrechumano->sql_query_escola("",
                                                                      "ed75_i_escola as esc_regente",
                                                                      "",
                                                                      " $where AND ed01_c_regencia = 'S'
                                                                        AND ed75_i_escola = $escola"
                                                                     )
                                      );
  $ini_left = $tabela1_left+$larg_coluna1+$larg_coluna2;
  for ($x = 0; $x < $cldiasemana->numrows; $x++) {

    $ini_top = $tabela1_top+25;
    db_fieldsmemory($result,$x);
    $sCampos   = "ed20_i_codigo,";
    $sCampos  .= "case ";
    $sCampos  .= "   when ed20_i_tiposervidor = 1 ";
    $sCampos  .= "then ''||rechumanopessoal.ed284_i_rhpessoal ";
    $sCampos  .= "else 'CGM: '||rechumanocgm.ed285_i_cgm ";
    $sCampos  .= "end as identificacao,";
    $sCampos  .= "case when ed20_i_tiposervidor = 1 ";
    $sCampos  .= " then cgmrh.z01_nome ";
    $sCampos  .= " else cgmcgm.z01_nome ";
    $sCampos  .= " end as nomeprof,ed232_c_abrev,ed58_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,";
    $sCampos  .= "ed17_h_fim,ed57_i_escola,ed57_c_descr,ed232_c_descr,ed11_c_descr,ed10_c_abrev";
    $sGroupBy  = "identificacao,ed232_c_abrev,ed58_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,";
    $sGroupBy .= "ed17_h_fim,ed57_i_escola,ed57_c_descr,ed232_c_descr,ed11_c_descr,ed10_c_abrev,ed58_i_diasemana,";
    $sGroupBy .= " ed17_h_inicio,ed17_h_fim,nomeprof,ed20_i_codigo";
    $sOrder    = "ed58_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere    = "$where AND ed58_i_diasemana = $ed32_i_codigo AND ed52_i_ano = $calendario and ed58_ativo is true  ";

    $result1  = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                             $sCampos,
                                                                             $sOrder,
                                                                             $sWhere,
                                                                             $sGroupBy
                                                                            )
                                              );

    if ($clrechumano->numrows > 0 && $calendario == date("Y")) {

      $notexists  = " AND not exists ";
      $notexists .= " (select * from regenciahorario ";
      $notexists .= "   inner join regencia on ed59_i_codigo = ed58_i_regencia ";
      $notexists .= "   inner join periodoescola as pe on pe.ed17_i_codigo = ed58_i_periodo ";
      $notexists .= "   inner join turma on ed57_i_codigo = ed59_i_turma ";
      $notexists .= "   inner join calendario on ed52_i_codigo = ed57_i_calendario ";
      $notexists .= "  where ed58_i_rechumano = ed20_i_codigo and ed58_ativo is true  ";
      $notexists .= "   and ed58_i_diasemana = ed33_i_diasemana ";
      $notexists .= "   and ed52_i_ano = $calendario ";
      $notexists .= "   and ( ";
      $notexists .= "        ( (pe.ed17_h_inicio > periodoescola.ed17_h_inicio ";
      $notexists .= "           AND pe.ed17_h_inicio < periodoescola.ed17_h_fim) ";
      $notexists .= "           OR (pe.ed17_h_fim  > periodoescola.ed17_h_inicio ";
      $notexists .= "               AND pe.ed17_h_fim < periodoescola.ed17_h_fim) ";
      $notexists .= "        ) ";
      $notexists .= "         OR (pe.ed17_h_inicio <= periodoescola.ed17_h_inicio ";
      $notexists .= "             AND pe.ed17_h_fim >= periodoescola.ed17_h_fim) ";
      $notexists .= "         OR (pe.ed17_h_inicio >= periodoescola.ed17_h_inicio ";
      $notexists .= "              AND pe.ed17_h_fim <= periodoescola.ed17_h_fim) ";
      $notexists .= "         OR (pe.ed17_h_inicio = periodoescola.ed17_h_inicio";
      $notexists .= "              AND pe.ed17_h_fim = periodoescola.ed17_h_fim) ";
      $notexists .= "       ) ";
      $notexists .= " ) ";

    } else {
      $notexists = "";
    }
    $sCampos  = " ed33_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,ed17_h_fim,ed17_i_escola,ed20_i_codigo,";
    $sCampos .= " case ";
    $sCampos .= "  when ed20_i_tiposervidor = 1 ";
    $sCampos .= " then ''||rechumanopessoal.ed284_i_rhpessoal ";
    $sCampos .= " else 'CGM: '||rechumanocgm.ed285_i_cgm end as identificacao";
    $sCampos .= ",case when ed20_i_tiposervidor = 1 ";
    $sCampos .= " then cgmrh.z01_nome ";
    $sCampos .= " else cgmcgm.z01_nome ";
    $sCampos .= " end as nomeprof";
    $sOrder   = " ed33_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere   = " $where AND ed33_i_diasemana = $ed32_i_codigo $notexists";
    $result2  = $clrechumanohoradisp->sql_record($clrechumanohoradisp->sql_query_disponibilidade("",
                                                                                 $sCampos,
                                                                                 $sOrder,
                                                                                 $sWhere
                                                                                )
                                                );
    $sCampos  = " ed270_i_codigo,ed268_i_escola,ed08_c_descr,ed18_c_nome,turno.ed15_c_nome,ed17_h_inicio,ed17_h_fim,";
    $sCampos .= " ed17_i_escola,ed20_i_codigo,ed268_i_codigo,ed268_c_descr,";
    $sCampos .= " case ";
    $sCampos .= "  when ed20_i_tiposervidor = 1 ";
    $sCampos .= " then ''||rechumanopessoal.ed284_i_rhpessoal ";
    $sCampos .= "  else 'CGM: '||rechumanocgm.ed285_i_cgm end as identificacao";
    $sCampos .= ",case when ed20_i_tiposervidor = 1 ";
    $sCampos .= " then cgmrh.z01_nome ";
    $sCampos .= " else cgmcgm.z01_nome ";
    $sCampos .= " end as nomeprof ";
    $sOrder   = " ed270_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere   = " $where AND ed270_i_diasemana = $ed32_i_codigo AND ed52_i_ano = $calendario";
    $result11 = $clturmaachorario->sql_record($clturmaachorario->sql_query("",
                                                                           $sCampos,
                                                                           $sOrder,
                                                                           $sWhere
                                                                          )
                                             );
    $tt       = 0;
    for ($t = $horainicial; $t <= $horafinal; $t += 1) {

      $hora = strlen($t) == 3?"0".$t:$t;
      $hora = substr($hora,0,2).":".substr($hora,2,2);

      if ($clregenciahorario->numrows > 0) {

        for ($y = 0; $y < $clregenciahorario->numrows; $y++) {

          db_fieldsmemory($result1,$y);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
            ?>

            <table id="tab<?=$ed58_i_codigo?>" width="<?=$larg_dia?>" border="0" bgcolor="#CCCCCC" height="<?=$difer?>"
              style="background:<?=$_SESSION["sess_corhorario"][$ed57_i_escola]?>;border:1px outset #000000;
                     position:absolute;top:<?=$ini_top?>px;left:<?=$ini_left?>px;" cellspacing="0" cellpadding="0">
            <tr>
            <?
            $conta   = $y;
            $proximo = true;
            $array   = array();
            while ($proximo == true) {

              $conta++;
              if ($clregenciahorario->numrows > $conta) {

                $oDados = db_utils::fieldsmemory($result1,$conta);
                if ($ed17_h_inicio == $oDados->ed17_h_inicio) {

                  $array[ ] = $conta;
                  $proximo  = true;
                  $y++;

                } else {
                  $proximo = false;
                }

              } else {
                $proximo = false;
              }
            }

            if (count($array) > 0) {

              $lista = "";
              $sep   = "";

              for ($e = 0; $e < count($array); $e++) {

                $lista = $array[$e];
                $sep   = ",";

              }
            }

            if (count($array) > 0) {

              $iHoraInicio     = $ed17_h_inicio;
              $iHoraFim        = $ed17_h_fim;
              $iCodigoEscola   = $ed57_i_escola;
              $sNomeEscola     = $ed18_c_nome;
              $sPeriodoDescr   = $ed08_c_descr;
              $sNomeTurno      = $ed15_c_nome;
              $sNomeTurma      = $ed57_c_descr;
              $sNomeDisciplina = $ed232_c_descr;
              $sNomeSerie      = $ed11_c_descr;
              $sNomeEnsino     = $ed10_c_abrev;
              $iIdent          = $identificacao;
              $sProf           = $nomeprof;
              $iCodigo         = $ed58_i_codigo;
              for ($a = 0; $a < count($array); $a++) {
                $oDados          = db_utils::fieldsmemory($result1,$array[$a]);
                $iHoraInicio     .= ",".$oDados->ed17_h_inicio;
                $iHoraFim        .= ",".$oDados->ed17_h_fim;
                $iCodigoEscola   .= ",".$oDados->ed57_i_escola;
                $sNomeEscola     .= ",".$oDados->ed18_c_nome;
                $sPeriodoDescr   .= ",".$oDados->ed08_c_descr;
                $sNomeTurno      .= ",".$oDados->ed15_c_nome;
                $sNomeTurma      .= ",".$oDados->ed57_c_descr;
                $sNomeDisciplina .= ",".$oDados->ed232_c_descr;
                $sNomeSerie      .= ",".$oDados->ed11_c_descr;
                $sNomeEnsino     .= ",".$oDados->ed10_c_abrev;
                $iIdent          .= ",".$oDados->identificacao;
                $sProf           .= ",".$oDados->nomeprof;
              }
              ?>
              <div id="teste11" style="position:absolute;border-width:2;border-color:black;border-style:solid; z-index:2">
                <td id="teste1" style="font-size:8px;" align="center"
                    onclick ="js_testesimultaneo('<?=$iHoraInicio?>','<?=$iHoraFim?>','<?=$iCodigoEscola?>',
                                                 '<?=$sNomeEscola?>','<?=$sPeriodoDescr?>','<?=$sNomeTurno?>',
                                                 '<?=$sNomeTurma?>','<?=$sNomeDisciplina?>','<?=$sNomeSerie?>',
                                                 '<?=$sNomeEnsino?>','<?=$_SESSION["sess_corhorario"][$ed57_i_escola]?>',
                                                 '<?=$iIdent?>','<?=$sProf?>',event);"
                    onmouseover="js_Mover('tab<?=$ed58_i_codigo?>','<?=$ed17_h_inicio?>',
                                          '<?=$ed17_h_fim?>','<?=$ed57_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>',
                                          '<?=$ed15_c_nome?>','<?=$ed57_c_descr?>','<?=$ed232_c_descr?>','<?=$ed11_c_descr?>',
                                          '<?=$ed10_c_abrev?>','<?=$_SESSION["sess_corhorario"][$ed57_i_escola]?>',
                                          '<?=$identificacao?>','<?=$nomeprof?>')"
                    onmouseout="js_Mout('tab<?=$ed58_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">

                    Atende Simultâneo
                                </td>
             </div>
          <?} else {?>
          <div id="teste11" style="position:absolute;border-width:2;border-color:black;border-style:solid; z-index:2">
              <td id="teste11" style="font-size:8px;" align="center"
                  onclick ="js_testesimultaneo('<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>','<?=$ed57_i_escola?>',
                                               '<?=$ed18_c_nome?>','<?=$ed08_c_descr?>','<?=$ed15_c_nome?>',
                                               '<?=$ed57_c_descr?>','<?=$ed232_c_descr?>','<?=$ed11_c_descr?>',
                                               '<?=$ed10_c_abrev?>','<?=$_SESSION["sess_corhorario"][$ed57_i_escola]?>',
                                               '<?=$identificacao?>','<?=$nomeprof?>',event);"
                  onmouseover="js_Mover('tab<?=$ed58_i_codigo?>','<?=$ed17_h_inicio?>',
                                        '<?=$ed17_h_fim?>','<?=$ed57_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>',
                                        '<?=$ed15_c_nome?>','<?=$ed57_c_descr?>','<?=$ed232_c_descr?>','<?=$ed11_c_descr?>',
                                        '<?=$ed10_c_abrev?>','<?=$_SESSION["sess_corhorario"][$ed57_i_escola]?>',
                                        '<?=$identificacao?>','<?=$nomeprof?>')"
                  onmouseout="js_Mout('tab<?=$ed58_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">
                  Escola: <?=$ed57_i_escola?> Turma: <?=substr($ed57_c_descr,0,10)?><br><?=substr($ed232_c_descr,0,20)?>
          <?}
             ?>
              </td>
             </div>
            </tr>
           </table>

       <?
          }
        }
      }
      if ($clrechumanohoradisp->numrows > 0 && $clrechumano->numrows > 0 && $calendario == date("Y")) {

        for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {

          db_fieldsmemory($result2,$y);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
       ?>
       <table id="tabb<?=$ed33_i_codigo?>" width="<?=$larg_dia?>" border="0" height="<?=$difer?>"
              style="background:<?=isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"]
                     [$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]?>;border:1px outset #000000;
                     position:absolute;top:<?=$ini_top?>px;left:<?=$ini_left?>px;" cellspacing="0" cellpadding="0">
        <tr>
         <td style="font-size:8px;" align="center"
             onmouseover="js_Mover2('tabb<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>',
                                    '<?=$ed17_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>','<?=$ed15_c_nome?>',
                                    '<?=isset($_SESSION["sess_corhorario"][$ed17_i_escola])?$_SESSION["sess_corhorario"]
                                        [$ed17_i_escola]:$_SESSION["sess_cordisp"][$ed17_i_escola]?>',
                                        '<?=$identificacao?>','<?$nomeprof?>',event)"
             onmouseout="js_Mout2('tabb<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">
         </td>
        </tr>
       </table>
       <?
          }
        }
      }
      if ($clturmaachorario->numrows > 0) {

        for ($q = 0; $q < $clturmaachorario->numrows; $q++) {

          db_fieldsmemory($result11,$q);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);

          ?>
            <table id="tab<?=$ed270_i_codigo?>" width="<?=$larg_dia?>" border="0" height="<?=$difer?>"
                   style="background:<?=$_SESSION["sess_corhorario"][$ed17_i_escola]?>;border:1px outset #000000;
                          position:absolute;top:<?=$ini_top?>px;left:<?=$ini_left?>px;" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:8px;" align="center"
                    onmouseover="js_Mover33('tab<?=$ed270_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>',
                                             '<?=$ed17_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>',
                                             '<?=$ed15_c_nome?>','<?=$ed268_c_descr?>','<?=$identificacao?>',
                                             '<?=$nomeprof?>','<?=$_SESSION["sess_corhorario"][$ed17_i_escola]?>',event)"
                    onmouseout="js_Mout('tab<?=$ed270_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">
                   Escola: <?=$ed17_i_escola?> Turma: <?=substr($ed268_c_descr,0,10)?><br>
                </td>
              </tr>
            </table>
         <?
          }
        }
      }
      $tt += 1;
      if ($tt == 60) {

        $t += 40;
        $tt = 0;

      }
      $ini_top+=0.5;
    }
    $ini_left += $larg_dia;
  }
  ?>
  </td></tr></tbody></table>
<?php
}
?>

<table width="100" style="top:220px;left:500px;position:absolute;"
       cellspacing="2" cellpadding="4">
 <tr>
  <td id="tab_descr" style="top:220px;left:500px;position:absolute;" >
  </td>
 </tr>
</table>
</body>
</html>
<script>
function js_testesimultaneo(horaini,horafim,escola,nomeescola,periodo,turno,turma,disciplina,serie,ensino,
		                    matricula,identificacao,nomeprof,evt) {
	
  if ( typeof(event) != "object" ) {
    PosMouseX = evt.layerX;
	PosMouseY = evt.layerY;
  } else {
    PosMouseX = event.x;
    PosMouseY = event.y;
  }

  aHoraIni    = horaini.split(',');
  aHoraFim    = horafim.split(',');
  aNomeProf   = nomeprof.split(',');
  aTurma      = turma.split(',');
  aEtapa      = serie.split(',');
  aDisciplina = disciplina.split(',');
  aEscola     = escola.split(','); 
  aNomeEscola = nomeescola.split(',');
  aTurno      = turno.split(',');
  aHoraFim    = horafim.split(',');
  aPeriodo    = periodo.split(',');
  aIdenti     = identificacao.split(',');
  $('teste11').style.top = PosMouseY;
  $('teste11').style.left = PosMouseX;
  texto  = "<table bgcolor='#f3f3f3'><tr><td><b>Escola:</b>"+aEscola[0]+"-"+aNomeEscola[0];
  texto += "<br><b>Turno: </b>"+aTurno[0]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  texto += "&nbsp;<b>Período:</b>"+aPeriodo[0];	  
  texto += "<br><b>Hora Inicial: </b>"+aHoraIni[0]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Hora Final:</b>"+aHoraFim[0];
  texto += "<br>______________________________________________________________";
  for (w = 0; w < aHoraIni.length; w++) {
       
    texto += "<br><b>Professor :</b>"+aIdenti[w]+"-"+aNomeProf[w];      
	texto += "<br><b>Turma:</b>"+aTurma[w]+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	texto += "<b>Etapa:</b>"+aEtapa[w];
	texto += "<br><b>Disciplina:</b>"+aDisciplina[w] ;
	texto +="<br></b></td></tr><tr><td>";
  }
  texto += "<input type='button' name='fecharregistro' value='Fechar' ";
  texto += "onclick=\"document.getElementById('teste11').innerHTML = '';\"></td></tr></table>";	  
  $('teste11').innerHTML = texto;	 
}


function js_Mover(quadro,horaini,horafim,escola,nomeescola,periodo,turno,
		          turma,disciplina,serie,ensino,cor,matricula,evt) {
    
  texto  = "<table bgcolor='#f3f3f3' style='border:2px solid #888888' cellpadding='3'>";
  texto += "<tr><td><b>Escola:</b>"+escola+"<br><b>Disciplina:</b>"+ disciplina ;
  texto += "<b>Turma:</b>"+turma+ "<br><br><b>Etapa/Ano:<br></b></td></tr><tr><td>";
  texto += "<input type='button' name='fecharregistro' value='Fechar' ";
  texto += "onclick='fechar(this.value);'></td></tr></table>";
	
  document.getElementById("tab_descr").style.visibility = "visible";
  document.getElementById("tab_descr").style.background = cor;
  document.getElementById(quadro).style.border          = "1px inset";
  tt = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>; t += 1) {
	  
    hora         = new String(t);
    hora         = hora.length==3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    
    if (t != 2400) {
        
      if (horaini == hora || horafim == hora) {
          
        document.getElementById(id_hora).style.visibility = "visible";
        document.getElementById(id_hora).style.zIndex     = 10000;
        
      }
      if (hora >= horaini && hora <= horafim) {
          
        document.getElementById(id_linhahora).style.background = cor;
        document.getElementById(id_linhahora).style.visibility = "visible";
        
      }
    }
    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
      
    }
  }
}


function js_Mover33(quadro,horaini,horafim,escola,nomeescola,periodo,turno,turma,matricula,cor,evt) {

  texto  = "Escola: <b>"+escola+" - "+nomeescola+"</b><hr><b>"+matricula+"</b><hr><br>Turma:<br><b>"+turma;
  texto += "</b><br>Turno:<br><b>"+turno+"</b><hr>Período: <b>"+periodo+"</b><br>Hora Inicial: <b>"+horaini;
  texto += "</b><br>Hora Final: <b>"+horafim+"</b><br>";
  document.getElementById("tab_descr").style.visibility = "visible";
  document.getElementById("tab_descr").style.background = cor;
  document.getElementById("tab_descr").innerHTML        = texto;
  document.getElementById(quadro).style.border          = "1px inset";
  tt = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>;t += 1) {
	  
	hora         = new String(t);
	hora         = hora.length == 3?"0"+hora:hora;
	hora         = hora.substr(0,2)+":"+hora.substr(2,2);
	id_hora      = "HH"+hora;
	id_linhahora = "LH"+hora;
	
	if (t != 2400) {
		
	  if (horaini == hora || horafim == hora) {
		  
	    document.getElementById(id_hora).style.visibility = "visible";
	    document.getElementById(id_hora).style.zIndex     = 10000;
	    
	  }
	  if (hora >= horaini && hora <= horafim) {
		  
	    document.getElementById(id_linhahora).style.background = cor;
	    document.getElementById(id_linhahora).style.visibility = "visible";
	    
	  }
	}
	tt += 1;
	if (tt == 60) {
		
	  t += 40;
	  tt = 0;
	  
	}
  }
}

function js_Mout(quadro,horaini,horafim) {
	
  document.getElementById("tab_descr").style.visibility = "hidden";
  document.getElementById("tab_descr").innerHTML        = "";
  document.getElementById(quadro).style.border          = "1px outset";
  tt                                                    = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>; t += 1) {
	  
    hora         = new String(t);
    hora         = hora.length==3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    
    if (t != 2400) {
        
      if (horaini == hora || horafim == hora) {
          
        document.getElementById(id_hora).style.visibility = "hidden";
        document.getElementById(id_hora).style.zIndex     = 1000;
        
      }
    }
    
    if (hora >= horaini && hora <= horafim) {
      document.getElementById(id_linhahora).style.visibility = "hidden";
    }
    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
      
    }
  }
}

function js_Mover2(quadro,horaini,horafim,escola,nomeescola,periodo,turno,cor,matricula,evt) {

  texto  = "<b>Horário disponível<br>ainda não marcado<hr></b>Escola: <b>"+escola+" - "+nomeescola;
  texto += "</b><hr><b>"+matricula+"</b><hr>Turno: <b>"+turno+"</b><br>Período: <b>"+periodo;
  texto += "</b><br>Hora Inicial: <b>"+horaini+"</b><br>Hora Final: <b>"+horafim+"</b><br>";
  document.getElementById("tab_descr").style.visibility = "visible";
  document.getElementById("tab_descr").style.background = cor;
  document.getElementById("tab_descr").innerHTML        = texto;
  document.getElementById(quadro).style.border          = "1px inset";
  tt                                                    = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>;t += 1) {
	  
    hora         = new String(t);
    hora         = hora.length == 3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    
    if (t != 2400) {
        
      if (horaini == hora || horafim == hora) {
          
        document.getElementById(id_hora).style.visibility = "visible";
        document.getElementById(id_hora).style.zIndex     = 10000;
        
      }
      if(hora >= horaini && hora <= horafim) {
          
        document.getElementById(id_linhahora).style.background = cor;
        document.getElementById(id_linhahora).style.visibility = "visible";
        
      }
    }
    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
      
    }
  }
}

function js_Mout2(quadro,horaini,horafim) {
	
  document.getElementById("tab_descr").style.visibility = "hidden";
  document.getElementById("tab_descr").innerHTML        = "";
  document.getElementById(quadro).style.border          = "1px outset";
  tt                                                    = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>; t += 1) {
    hora         = new String(t);
    hora         = hora.length == 3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    if (t != 2400) {
      if (horaini == hora || horafim == hora) {
        document.getElementById(id_hora).style.visibility = "hidden";
        document.getElementById(id_hora).style.zIndex     = 1000;
      }
    }
    if(hora >= horaini && hora <= horafim) {
      document.getElementById(id_linhahora).style.visibility = "hidden";
    }
    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
      
    }
  }
}

function js_trocaAno(ano) {
	
  js_divCarregando("Aguarde, alterando ano","msgBox");
  var url     = 'edu3_rechumanohorario002.php';
  var sAction = 'MontaGrade';
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: 'ano='+ano+'&esc_horario=&<?=$destino?>&larg_obj=<?=$larg_obj?>'+
                                                '&sAction='+sAction,
                                    onComplete: js_retornaGrade
                                   });
  var sAction = 'MontaEscola';
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: 'ano='+ano+'&<?=$destino?>&larg_obj=<?=$larg_obj?>&sAction='+sAction,
                                    onComplete: js_retornaEscola
                                   });
 
}

function js_trocaEscola(escola) {

  var oGet = js_urlToObject();

  js_divCarregando("Aguarde, alterando escola","msgBox");
  var sAction = 'MontaGrade';
  var url     = 'edu3_rechumanohorario002.php';
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: 'ano='+document.getElementById("calendario").value+
                                                '&esc_horario='+escola+'&<?=$destino?>&larg_obj=<?=$larg_obj?>'+
                                                '&cod_matricula=' + oGet.ed20_i_codigo + '&sAction='+sAction,
                                    onComplete: js_retornaGrade
                                   });
  
}

function js_retornaGrade(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  $('disp_rechumano').innerHTML = "";
  $('disp_rechumano').innerHTML = oRetorno.urlDecode();
  
}

function js_retornaEscola(oAjax) {
	
  var oRetorno = eval("("+oAjax.responseText+")");
  F = document.getElementById("esc_horario");
  F.length = 0;
  F.options[F.length] = new Option("TODAS","");
  
  for (var i = 0;i < oRetorno.length; i++) {
	  
    with (oRetorno[i]) {
      F.options[F.length] = new Option(ed18_i_codigo.urlDecode()+" - "+ed18_c_nome.urlDecode(),ed18_i_codigo.urlDecode());
    }
  }
}
</script>