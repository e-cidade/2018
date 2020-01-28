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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$oDaoRelRubRelRubCampos = db_utils::getDao('relrubrelrubcampos', false);
$clrelrub               = db_utils::getDao('relrub');
$clrelrubmov            = db_utils::getDao('relrubmov');
$clselecao              = db_utils::getDao('selecao');
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
  
  try {
    $sqlerro=false;
    db_inicio_transacao();
  	$clrelrub->rh45_instit = db_getsession('DB_instit');
    $clrelrub->incluir($rh45_codigo, db_getsession('DB_instit'));

    if ($clrelrub->erro_status == '0') {
     throw new Exception($clrelrub->erro_msg);
    } 

    foreach (array('1' => 54, '2' => 55) as $iOrdem => $iCampo) {

      $oDaoRelRubRelRubCampos = new cl_relrubrelrubcampos();
      $oDaoRelRubRelRubCampos->rh121_instit       = db_getsession('DB_instit');
      $oDaoRelRubRelRubCampos->rh121_relrub       = $clrelrub->rh45_codigo;
      $oDaoRelRubRelRubCampos->rh121_relrubcampos = $iCampo;
      $oDaoRelRubRelRubCampos->rh121_ordem        = $iOrdem;
      $oDaoRelRubRelRubCampos->incluir(null);    
      
      if ( $oDaoRelRubRelRubCampos->erro_status == '0' ) {
          throw new Exception($oDaoRelRubRelRubCampos->erro_msg);
      }
    }

    $erro_msg = $clrelrub->erro_msg; 
    db_fim_transacao(false);
    $rh45_codigo= $clrelrub->rh45_codigo;

  } catch (Exception $oException) {

    db_fim_transacao(true);
    $sqlerro  = true;
    $erro_msg = $oException->getMessage();
  }
}
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?php include("forms/db_frmrelrub.php"); ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrelrub->erro_campo!=""){
      echo "<script> document.form1.".$clrelrub->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrelrub->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("pes1_relrub005.php?liberaaba=true&chavepesquisa=$rh45_codigo");
  }
}
?>