<?
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once('model/CgmFactory.model.php');

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clpcparam   = new cl_pcparam;
$cldb_config = new cl_db_config;
$db_botao    = true;
$lSqlErro 	 = false;
$db_opcao 	 = 2;

if (isset($oPost->incluir) || isset($oPost->alterar)) {
	
  if ($oPost->pc30_validadepadraocertificado > 0 && $oPost->pc30_tipovalidade == 0) {
      
    $clpcparam->erro_status = 0;    
    $clpcparam->erro_msg    = "Usuário: \\n\\n Ao atualizar a Validade do Certificado você deve selecionar um Tipo de Validade. \\n\\n";
    $lSqlErro               = true;       
  } 
}

if (!$lSqlErro) {
	
	if (isset($oPost->incluir)) {
		
	  if (!$lSqlErro) {
    	
			db_inicio_transacao();
		   
			$clpcparam->pc30_notificaemail = 'false';
			if (isset($pc30_notificaemail)) {
			  $clpcparam->pc30_notificaemail = 'true';  
			}
			
			$clpcparam->pc30_notificacarta = 'false';
			if (isset($pc30_notificacarta)) {
			  $clpcparam->pc30_notificacarta = 'true'; 
			}
			
	    if (isset($pc30_fornecdeb) && $pc30_fornecdeb == 1) {
	      if ($pc30_diasdebitosvencidos == null) {
	        $clpcparam->pc30_diasdebitosvencidos = '0';
	      }
        $clpcparam->pc30_permitirgerarnotifdebitos = 'false'; 
      }
			
		  $clpcparam->incluir($oPost->pc30_instit);  
		  if ($clpcparam->erro_status == 0) {
		  	$lSqlErro = true;
		  }
		  
		  db_fim_transacao($lSqlErro);
    }
	
	} else if(isset($oPost->alterar)) {

		if (!$lSqlErro) {
			
	    db_inicio_transacao();
	   
		  $clpcparam->pc30_notificaemail = 'false';
      if (isset($pc30_notificaemail)) {
        $clpcparam->pc30_notificaemail = 'true';  
      }
      
      $clpcparam->pc30_notificacarta = 'false';
      if (isset($pc30_notificacarta)) {
        $clpcparam->pc30_notificacarta = 'true'; 
      }
	   
		 if (isset($pc30_fornecdeb) && $pc30_fornecdeb == 1) {
		 	 if ($pc30_diasdebitosvencidos == null) {
	       $clpcparam->pc30_diasdebitosvencidos = '0';
	     }
       $clpcparam->pc30_permitirgerarnotifdebitos = 'false'; 
     }
      
	   $clpcparam->alterar($pc30_instit);	  
	   if ($clpcparam->erro_status == 0) {
	  	  $lSqlErro = true;
	   }
	  
	   db_fim_transacao($lSqlErro);
		}
	   
	} else {
		
	  $rsConsultaParametros = $clpcparam->sql_record($clpcparam->sql_query(db_getsession("DB_instit")));
	
	  if ($clpcparam->numrows > 0 ) {	  	
	  	db_fieldsmemory($rsConsultaParametros, 0);
	  } else {
	  	
	  	$rsConfig 	 = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"codigo, nomeinst"));
	  	$oConfig 	   = db_utils::fieldsMemory($rsConfig, 0);
	  	$pc30_instit = $oConfig->codigo;
	  	$nomeinst	   = $oConfig->nomeinst;	  	
	  	$db_opcao    = 1;
	    
	  }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_vericaparamfornecdeb();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?
			  include("forms/db_frmpcparam.php");
			?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($oPost->incluir)  || isset($oPost->alterar)){
	
  if ($clpcparam->erro_status == "0") {
  	
    $db_botao = true;
    $clpcparam->erro(true, false);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clpcparam->erro_campo != "") {
    	
      echo "<script> document.form1.".$clpcparam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcparam->erro_campo.".focus();</script>";
    }
  } else {
    $clpcparam->erro(true,true);
  }
}
?>