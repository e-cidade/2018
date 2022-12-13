<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_loteam_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet       = db_utils::postMemory($_GET);
$oPost      = db_utils::postMemory($_POST);

$clloteam   = new cl_loteam;
$db_opcao   = 2;
$lPesquisar = false;
$db_botao   = false;
$lSqlErro   = false;

if ( isset( $oPost->alterar ) ) {
	
  db_inicio_transacao();  

  if ( !$lSqlErro ) {
    
    $clloteam->alterar($j34_loteam);
    $sErroMsg   = $clloteam->erro_msg;
    if ( $clloteam->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }  
  
  db_fim_transacao($lSqlErro);  
} else if ( isset($oGet->chavepesquisa) ) {
	
   $result = $clloteam->sql_record($clloteam->sql_query($oGet->chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   
    echo " <script>                                                                                                   ";
    echo "   parent.iframe_dadoscgm.location.href='cad1_loteamcgm001.php?codigo={$j34_loteam}';                       ";
    echo "   parent.document.formaba.dadosloteamento.disabled = false;                                                ";
    echo "   parent.document.formaba.dadoscgm.disabled = false;                                                       ";
    echo " </script>                                                                                                  ";   
} else {
	$lPesquisar = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
  <tr align="center" valign="top">
    <td>
      <?
        include("forms/db_frmloteam.php");
      ?>    
    </td>
  </tr>
</table>
</body>
</html>
<?
if ( isset($oPost->alterar) ) {
  
  if ($lSqlErro) {
    
    db_msgbox($sErroMsg);
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ( $clloteam->erro_campo != "" ) {
      
      echo "<script> document.form1.".$clloteam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clloteam->erro_campo.".focus();</script>";
    }
    
  } else {	
  	
    db_msgbox($sErroMsg);
    echo " <script>                                                                                                   ";
    echo "   parent.mo_camada('dadoscgm');                                                                            ";    
    echo " </script>                                                                                                  ";
  }
}

if ( $lPesquisar ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>