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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_gestorindicadorregistro_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clgestorindicadorregistro = new cl_gestorindicadorregistro;

$db_opcao  = 1;
$db_botao  = true;
$lSqlErro  = false;

$iInstit   = db_getsession("DB_instit");
$dtDataUsu = date('Y-m-d', db_getsession("DB_datausu"));

if (isset($incluir)) {
	
	if (!$lSqlErro) {
		
		db_inicio_transacao();
		
		$clgestorindicadorregistro->g05_instit = db_getsession("DB_instit");
		$clgestorindicadorregistro->incluir($g05_sequencial);
	  $sMensagem  = $clgestorindicadorregistro->erro_msg;
	  if ($clgestorindicadorregistro->erro_status == 0) {
	    $lSqlErro = true;
	  }
		
	  db_fim_transacao($lSqlErro);
	}
} else if (isset($alterar)) {
	
	$db_opcao = 2;
	if (!$lSqlErro) {
		
		db_inicio_transacao();
		
		$clgestorindicadorregistro->g05_instit     = db_getsession("DB_instit");
	  $clgestorindicadorregistro->g05_processado = "f";
	  $clgestorindicadorregistro->alterar($g05_sequencial);
	  $sMensagem  = $clgestorindicadorregistro->erro_msg;
	  if ($clgestorindicadorregistro->erro_status == 0) {
	    $lSqlErro = true;
	  }  
	  
	  db_fim_transacao($lSqlErro);
	}
} else if (isset($excluir)) {
	
	$db_opcao = 1;
	if (!$lSqlErro) {
		
		db_inicio_transacao();
		
	  $clgestorindicadorregistro->excluir($g05_sequencial);
	  $sMensagem  = $clgestorindicadorregistro->erro_msg;
	  if ($clgestorindicadorregistro->erro_status == 0) {
	    $lSqlErro = true;
	  }
	  
	  db_fim_transacao($lSqlErro);
	}
}

if (isset($excluir) || isset($alterar) || isset($incluir)) {
	
	$g05_sequencial           = "";
  $g05_gestorgrupoindicador = "";
  $g05_gestorindicador      = "";
  $g05_ano                  = "";
  $g05_mes                  = "";
  $g05_valor                = "";
  $g05_meta                 = "";
  $g03_descricao            = "";
  $g04_descricao            = "";  
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
			  include("forms/db_frmgestorindicadorregistro.php");
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
<script>
js_tabulacaoforms("form1","g05_gestorgrupoindicador",true,1,"g05_gestorgrupoindicador",true);
</script>
<?
if (isset($incluir)) {
	
	db_msgbox($sMensagem);
  if ($clgestorindicadorregistro->erro_status=="0") {

    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clgestorindicadorregistro->erro_campo!="") {
    	
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".focus();</script>";
    }
  }
} else if (isset($alterar)) {
	
	db_msgbox($sMensagem);
  if ($clgestorindicadorregistro->erro_status=="0") {
  	
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clgestorindicadorregistro->erro_campo!="") {
    	
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".focus();</script>";
    }
  }
} if(isset($excluir)) {
	
  db_msgbox($sMensagem);
  if ($clgestorindicadorregistro->erro_status=="0") {
    
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clgestorindicadorregistro->erro_campo!="") {
      
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clgestorindicadorregistro->erro_campo.".focus();</script>";
    }
  }
}
?>