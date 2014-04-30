<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libdicionario.php");
require_once("classes/db_empparametro_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrotulo        = new rotulocampo;
$clrotulo->label("e60_codemp");

$clempparametro = new cl_empparametro;
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
   db_inicio_transacao();
   $lSqlErro = false;
   if (isset($e30_opimportaresumo) && $e30_opimportaresumo!=""){
     if ($e30_opimportaresumo=="t"){
        $e30_opimportaresumo="true";
     } else {
     	$e30_opimportaresumo="false";
     }	        
   }
   if ($e30_autimportahist!="" && $e30_autimportahist=='t'){
      $clempparametro->e30_autimportahist="true";
   } else {
      $clempparametro->e30_autimportahist="false";
   }      

   $rsParam = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));
   if ($clempparametro->numrows > 0) {

     $oParam = db_utils::fieldsMemory($rsParam, 0);
     if ($oParam->e30_notaliquidacao != '' && $e30_notaliquidacao == '') {

       $lSqlErro                     = true;
       $clempparametro->erro_status  = 0;
       $clempparametro->erro_msg     = "Sistema já convertido para nota de liquidacao.";
       $clempparametro->erro_msg    .= "\\n não pode ser alterado o parametro nota de liquidação";
       $clempparametro->erro_campo   = "e30_notaliquidacao";
       
     }
   }
/*
   if ($e30_trazobsultop!="" && $e30_trazobsultop=='t'){
      $clempparametro->e30_trazobsultop="true";
   } else {
      $clempparametro->e30_trazobsultop="false";
   }      
*/

   if ($e30_empdataserv!="" && $e30_empdataserv=='t'){
      $clempparametro->e30_empdataserv="true";
   } else {
      $clempparametro->e30_empdataserv="false";
   }      

   if ($e30_empdataemp!="" && $e30_empdataemp=='t'){
      $clempparametro->e30_empdataemp="true";
   } else {
      $clempparametro->e30_empdataemp="false";
   }      


   if (!$lSqlErro) {

     $result = $clempparametro->sql_record($clempparametro->sql_query($e39_anousu));
     if($result==false || $clempparametro->numrows==0){
       $clempparametro->incluir($e39_anousu);
     }else{
       $clempparametro->alterar($e39_anousu);
     }
   }
   db_fim_transacao();
}
$db_opcao = 2;
$result = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));
if($result!=false && $clempparametro->numrows>0){
  db_fieldsmemory($result,0);
}
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <?
      include("forms/db_frmempparametro.php");
    ?>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clempparametro->erro_status=="0"){
    $clempparametro->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clempparametro->erro_campo!=""){
      echo "<script> document.form1.".$clempparametro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempparametro->erro_campo.".focus();</script>";
    };
  }else{
    $clempparametro->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>