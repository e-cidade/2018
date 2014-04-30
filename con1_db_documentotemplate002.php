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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
include("classes/db_db_documentotemplate_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldb_documentotemplate = new cl_db_documentotemplate;
$db_opcao               = 22;
$db_botao               = false;
$sqlerro                = false;

if (isset($oGet->oidarq)) {
	
	db_inicio_transacao();
	
	$lOid = pg_lo_open($conn,$oGet->oidarq, "r");
	
	$sWhere                = "db82_arquivo = '{$oGet->oidarq}'";
	$sSqlDocumentoTemplate = $cldb_documentotemplate->sql_query(null,"db_documentotemplate.*",null,$sWhere);
	$rsDocumentoTemplate   = $cldb_documentotemplate->sql_record($sSqlDocumentoTemplate);
	if ($cldb_documentotemplate->numrows > 0) {

		$oDocumentoTemplate = db_utils::fieldsMemory($rsDocumentoTemplate,0);
		$sNomeArquivo       = strtolower(trim($oDocumentoTemplate->db82_descricao));
		
	  header('Accept-Ranges: bytes');
	  header('Keep-Alive: timeout=15, max=100');
	  header('Content-Disposition: attachment; filename="documento_'.$sNomeArquivo.'.sxw"');
	  
	  pg_lo_read_all($lOid);
	}
	
	pg_lo_close ($lOid);
	
  db_fim_transacao($sqlerro);
  exit();
}

if (isset($alterar)) {
	
	db_inicio_transacao();
	
  $ext = array_reverse( explode('.',$_FILES['db82_arquivo']['name'] ));
  $ext = $ext[0];
    
  if( $ext != 'sxw' && $ext != 'SXW' ) {
    $sqlerro = true;    
    $cldb_documentotemplate->erro_status = "0";
    $cldb_documentotemplate->erro_msg = "O arquvio selecionado é inválido!";    
  }
  
	if (!$sqlerro) {
		
	  $db_opcao = 2;
	  $result = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query_file($db82_sequencial,'db82_arquivo as arq'));
	  if($cldb_documentotemplate->numrows > 0){
	  	db_fieldsmemory($result,0);
	  	$arquivoalt = $arq;
	  }
	  
	  if (isset($db82_arquivo) && $db82_arquivo!="") {
	  	$oidgrava = db_geraArquivoOid("db82_arquivo",$arquivoalt,2,$conn);
	  } else if(isset($db82_arquivo1) && $db82_arquivo1!=""){
	  	
	  	$arquivoGrava = fopen($db82_arquivo1, "rb");
	    if ($arquivoGrava == false) {
	    	$sqlerro = true;
	    	$cldb_documentotemplate->erro_msg = "erro arquivograva!!!";
	    }
	    $dados = fread($arquivoGrava, filesize($db82_arquivo1));
	    if ($dados == false) {
	    	$sqlerro = true;
	    	$cldb_documentotemplate->erro_msg = "erro fread!!!";
	    }
	    fclose($arquivoGrava);
	    $oidgrava = pg_lo_create();
	    if ($oidgrava == false) {
	    	$sqlerro = true;
	    	$cldb_documentotemplate->erro_msg = "erro pg_lo_create!!!";
	    }
	    $objeto = pg_lo_open($conn, $oidgrava, "w");
	    if ($objeto != false) {
	      $erro = pg_lo_write($objeto, $dados);
	    if ($erro == false) {
	    	$sqlerro = true;
	    	$cldb_documentotemplate->erro_msg = "erro pg_lo_write!!!";
	    }
	    pg_lo_close($objeto);
	    } else {
	    $cldb_documentotemplate->erro_msg = "Operação Cancelada!!";
	    $sqlerro = true;
	    }
	  	
		}else {
	  	$oidgrava = "null";
	  }
	  
	  $cldb_documentotemplate->db82_instit  = db_getsession('DB_instit');
	  $cldb_documentotemplate->db82_arquivo = $oidgrava;
	  $cldb_documentotemplate->alterar($db82_sequencial);	  
	}
	
  db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {
	
   $db_opcao = 2;
   $result   = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query($chavepesquisa)); 
   $db_botao = true;
   db_fieldsmemory($result,0);
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
	include("forms/db_frmdb_documentotemplate.php");
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
if (isset($alterar)) {
	
  if ($cldb_documentotemplate->erro_status == "0") {
  	
    $cldb_documentotemplate->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($cldb_documentotemplate->erro_campo != "") {
    	
      echo "<script> document.form1.".$cldb_documentotemplate->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_documentotemplate->erro_campo.".focus();</script>";
    }
  } else {
    $cldb_documentotemplate->erro(true,true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","db82_templatetipo",true,1,"db82_templatetipo",true);
</script>