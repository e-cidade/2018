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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

db_postmemory($_POST);
db_postmemory($_GET);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clferiado    = new cl_feriado;
$clcalendario = new cl_calendario;
$clregencia   = new cl_regencia;
$db_opcao     = 1;
$db_botao     = true;
$erro_fer     = false;

$sWherePeriodo = "ed52_i_codigo = {$oGet->ed54_i_calendario}";
$sSqlPeriodoCalendario = $clcalendario->sql_query("", "ed52_d_inicio, ed52_d_fim", "", $sWherePeriodo);
$rsPeriodoCalendario   = $clcalendario->sql_record($sSqlPeriodoCalendario);

$oPeriodoCalendario = db_utils::fieldsMemory($rsPeriodoCalendario, 0);

$oDBDateInicio = new DBDate( $oPeriodoCalendario->ed52_d_inicio );
$oDBDateFim    = new DBDate( $oPeriodoCalendario->ed52_d_fim );

if (isset($incluir)) {
  
  $clferiado->pagina_retorno = "edu1_feriado001.php?ed54_i_calendario=$ed54_i_calendario&ed52_c_descr=$ed52_c_descr";
  db_inicio_transacao();
  
  $iIntervalo = 0;
  
  if (isset($oPost->ed54_d_data) && !empty($oPost->ed54_d_data)) {
    $oData = new DBDate($oPost->ed54_d_data);
  }
  
  if (isset($oPost->datafinal) && !empty($oPost->datafinal)) {
    $oDataFinal = new DBDate($oPost->datafinal);
  }
  
  if (isset($oData) && isset($oDataFinal)) {
    
    if ($oData->getTimeStamp() > $oDataFinal->getTimeStamp()) {
      
      db_msgbox("Data Inicial maior que a final.");
      return false;
    }
    
    $iIntervalo = DBDate::calculaIntervaloEntreDatas($oDataFinal, $oData, "d");
  }
  
  for ($i = 0; $i <= $iIntervalo; $i++) {
    
    $iTimestamp = mktime (0, 0, 0, $oData->getMes(), $oData->getDia()+$i, $oData->getAno());
    $dtIncluir  = date("Y-m-d", $iTimestamp);
    $iDiaSemana = date("N", $iTimestamp);
    
    $sDiaSemana = "";
    
    switch ($iDiaSemana) {
      
      case 1:
        $sDiaSemana = "SEGUNDA";
        break;
      case 2:
        $sDiaSemana = "TERÇA";
        break;
      case 3:
        $sDiaSemana = "QUARTA";
        break;
      case 4:
        $sDiaSemana = "QUINTA";
        break;
      case 5:
        $sDiaSemana = "SEXTA";
        break;
      case 6:
        $sDiaSemana = "SABADO";
        break;
      case 7:
        $sDiaSemana = "DOMINGO";
        break;
    }
    
    $clferiado->ed54_c_diasemana = "$sDiaSemana";
    $clferiado->ed54_d_data      = "{$dtIncluir}";
    $clferiado->incluir($ed54_i_codigo);
  }
  
  db_fim_transacao();
  
}

if (isset($alterar)) {
  
  $clferiado->pagina_retorno = "edu1_feriado001.php?ed54_i_calendario=$ed54_i_calendario&ed52_c_descr=$ed52_c_descr";
  db_inicio_transacao();
  
  $db_opcao = 2;
  $clferiado->alterar($ed54_i_codigo);
  
  db_fim_transacao();
}

if (isset($excluir)) {
  
  $clferiado->pagina_retorno = "edu1_feriado001.php?ed54_i_calendario=$ed54_i_calendario&ed52_c_descr=$ed52_c_descr";
  db_inicio_transacao();
  
  $db_opcao = 3;
  $clferiado->excluir($ed54_i_codigo);
  
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Feriados e Eventos do Calendário <?=$ed52_c_descr?></b></legend>
    <?include(modification("forms/db_frmferiado.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?php
if(@$erro_fer==true){
 echo "<script> document.form1.ed54_d_".@$campo_erro."_dia.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed54_d_".@$campo_erro."_mes.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed54_d_".@$campo_erro."_ano.style.backgroundColor='#99A9AE';</script>";
 echo "<script> document.form1.ed54_d_".@$campo_erro."_dia.focus();</script>";
}
if (isset($incluir)) {

 if (@$erro_fer==false) {

  if ($clferiado->erro_status=="0") {

   $clferiado->erro(true,false);
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   if($clferiado->erro_campo!=""){
    echo "<script> document.form1.".$clferiado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clferiado->erro_campo.".focus();</script>";
   };
  }else{
   $sql1 = $clcalendario->sql_query("","ed52_i_codigo,ed52_c_aulasabado",""," ed52_i_codigo = $ed54_i_calendario");
   $result1 = $clcalendario->sql_record($sql1);
   db_fieldsmemory($result1,0);
   ?>
   <script>
    iframe_sabado2.location.href = "edu1_calendario004.php?calendario=<?=$ed52_i_codigo?>&sabado=<?=$ed52_c_aulasabado?>&feriado";
   </script>
   <?
   //$clferiado->erro(true,true);
  };
 }
};
if(isset($alterar)){
 if(@$erro_fer==false){
  if($clferiado->erro_status=="0"){
   $clferiado->erro(true,false);
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   if($clferiado->erro_campo!=""){
    echo "<script> document.form1.".$clferiado->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clferiado->erro_campo.".focus();</script>";
   };
  }else{
   $sql1 = $clcalendario->sql_query("","ed52_i_codigo,ed52_c_aulasabado",""," ed52_i_codigo = $ed54_i_calendario");
   $result1 = $clcalendario->sql_record($sql1);
   db_fieldsmemory($result1,0);
   ?>
   <script>
    iframe_sabado2.location.href = "edu1_calendario004.php?calendario=<?=$ed52_i_codigo?>&sabado=<?=$ed52_c_aulasabado?>&feriado";
   </script>
   <?
  };
 }
};
if(isset($excluir)){
 if(@$erro_fer==false){
  if($clferiado->erro_status=="0"){
   $clferiado->erro(true,false);
  }else{
   $sql1 = $clcalendario->sql_query("","ed52_i_codigo,ed52_c_aulasabado",""," ed52_i_codigo = $ed54_i_calendario");
   $result1 = $clcalendario->sql_record($sql1);
   db_fieldsmemory($result1,0);
   ?>
   <script>
    iframe_sabado2.location.href = "edu1_calendario004.php?calendario=<?=$ed52_i_codigo?>&sabado=<?=$ed52_c_aulasabado?>&feriado";
   </script>
   <?
   //$clferiado->erro(true,true);
  };
 }
};

if (isset($cancelar) ) {

  $clferiado->pagina_retorno = "edu1_feriado001.php?ed54_i_calendario=$ed54_i_calendario&ed52_c_descr=$ed52_c_descr";
  echo "<script>location.href='".$clferiado->pagina_retorno."'</script>";
}
?>