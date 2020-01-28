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
require_once("classes/db_bensmodeloetiqueta_classe.php");
require_once("classes/db_bensmodeloetiquetapadrao_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("model/dbModeloEtiqueta.model.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clbensmodeloetiqueta       = new cl_bensmodeloetiqueta;
$clbensmodeloetiquetapadrao = new cl_bensmodeloetiquetapadrao;

$db_opcao       = 22;
$db_botao       = false;
$sFileNameXml   = '';
$iCodigoFileXml = 0;
if(isset($alterar)){
	//Faz todo o procedimetno via RPC js_salvaEdita();
	/*
  db_inicio_transacao();
  $db_opcao = 2;
  $clbensmodeloetiqueta->alterar($t71_sequencial);
  db_fim_transacao();
  */
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clbensmodeloetiqueta->sql_record($clbensmodeloetiqueta->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   
  $iCodigoFileXml = $chavepesquisa;
  $oXmlEditor     = new modeloEtiqueta($iCodigoFileXml);

	try{
	  $sFileNameXml = $oXmlEditor->leArquivoXml();  
	}catch (Exception $erro){
	  echo $erro->getMessage(); 
	}
   
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,classes/dbXmlEditor.js,
                classes/dbXmlFactory.js,widgets/dbtextField.widget.js");
  db_app::load("estilos.css,grid.style.css");
?></head>
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmbensmodeloetiqueta.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clbensmodeloetiqueta->erro_status=="0"){
    $clbensmodeloetiqueta->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbensmodeloetiqueta->erro_campo!=""){
      echo "<script> document.form1.".$clbensmodeloetiqueta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbensmodeloetiqueta->erro_campo.".focus();</script>";
    }
  }else{
    $clbensmodeloetiqueta->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","t71_descr",true,1,"t71_descr",true);

var sFileNameXml = '<?echo $sFileNameXml;?>';
var sXmlAlterado = "";

function js_retornoEdita(sXml){
  
  sXmlAlterado = sXml;
  oXmlEditor.window.destroy();
  
}

function js_salvaEdita(){ 

  var sQuery  = 'codigo='+<?echo $iCodigoFileXml;?>;
      sQuery += '&sxml='+tagString(sXmlAlterado);
      sQuery += '&sdescr='+encodeURIComponent($('t71_descr').value);
  
  var msgDiv = _M('patrimonial.patrimonio.db_frmbensmodeloetiqueta.salvando_xml');
  js_divCarregando(msgDiv,'msgBox');
  
      
  var sUrl     = 'sys4_salvaXML.RPC.php';
  var oAjax    = new Ajax.Request(
                                    sUrl, 
                                    {
                                      method    : 'post', 
                                      parameters: sQuery, 
                                      onComplete: js_retornoSalvaXml
                                    }
                                  );            
  
}

function js_retornoSalvaXml(oAjax){

  js_removeObj("msgBox");
 
  var oRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
    
  alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));
  
  document.location.href = 'pat1_bensmodeloetiqueta002.php';  
}

function js_janela(){
  
  if(sFileNameXml != ""){  
    oXmlEditor = new DBXmlEditor(sFileNameXml,js_retornoEdita);
    oXmlEditor.show();
  }
       
}

$('fileXml').disabled=true;

</script>