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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->enviar)) {

  if (isset($oPost->nomearquivo)) {
  	
  	$sDiretorioXML = "./config/require_extensions.xml";
	  $sUploadDir    = "./templates/";
	  $pUploadFile   = trim($sUploadDir.trim($oPost->nomearquivo).".php");
    $pUploadFile   = trim(str_replace(' ','',$pUploadFile));
	  
    $sExtensao     = array_reverse( explode('.',$_FILES['arquivotemplatelogin']['name'] ));
    $sExtensao     = $sExtensao[0];
    
    if ($sExtensao == 'php' || $sExtensao == 'html') {

	    if (move_uploaded_file($_FILES['arquivotemplatelogin']['tmp_name'], $pUploadFile)) {
	    	
        $oSimpleXML = simplexml_load_file($sDiretorioXML);
        $oAttr = $oSimpleXML->template_login->attributes();
        $oAttr->src = $pUploadFile;
        
        file_put_contents($sDiretorioXML , $oSimpleXML->asXML());
        
	      $sMsgErro = "Upload concluído com sucesso.";
	    } else {
	      $sMsgErro = "Upload de arquivo não concluido!";
	    } 
    } else {
    	$sMsgErro = "O arquvio selecionado é inválido!";
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
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><b>Configuração de Tela de Login do Sistema</b></legend>
      <table align="center" border="0">
        <tr>
          <td>
            <b>Nome do Arquivo:</b>&nbsp;
          </td>
          <td>
            <input id="nomearquivo" name="nomearquivo" type="text" size="22">
          </td>
        </tr>
        <tr>
          <td>
            <b>Arquivo de Layout:</b>&nbsp;
          </td>
          <td>
            <input id="arquivotemplatelogin" name="arquivotemplatelogin" type="file" size="22">
          </td>
        </tr>
      </table>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
      <input name="enviar" type="submit" id="enviar" value="Enviar" onclick="return js_enviar();"> 
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if (isset($sMsgErro) && !empty($sMsgErro)) {
	db_msgbox($sMsgErro);
}
?>
<script>
function js_enviar() {

  var sNomeArquivo = document.form1.nomearquivo.value;
  var pArquivo     = document.form1.arquivotemplatelogin.value;
  
  if (sNomeArquivo == '') {
  
    alert("Informe o nome do arquivo!");
    document.form1.nomearquivo.focus();
    return false;
  }
  
  if (pArquivo == '') {
  
    alert("Informe um arquivo de template padrão!");
    document.form1.arquivotemplatelogin.focus();
    return false;
  }
}
</script>
</html>