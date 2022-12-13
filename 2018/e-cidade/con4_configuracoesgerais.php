<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/dbModeloEtiqueta.model.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost       = db_utils::postMemory($_POST);
$sArquivoXML = 'config/configuracao.xml'; 
function geraXML($sArquivoXML){
  $xmlDoc = new DOMDocument('1.0', 'utf-8');
  $xmlDoc->formatOutput = true;
  
  $eConfig       = $xmlDoc -> createElement('ConfiguracoesGerais');
  $eConfig       = $xmlDoc -> appendChild($eConfig);
  
  $eOrganograma  = $xmlDoc -> createElement('Organograma');
  $eOrganograma  = $eConfig-> appendChild($eOrganograma);
  $eOrganograma->setAttribute("CodigoEstrutura", "0");
  
  $xmlDoc->save($sArquivoXML);
}

if (!file_exists($sArquivoXML) ) {
  geraXML($sArquivoXML);
} elseif (file_exists($sArquivoXML) && file($sArquivoXML) == "") {
	unlink($sArquivoXML);
  geraXML($sArquivoXML);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?

  db_app::load("estilos.css");
  db_app::load("grid.style.css");
  db_app::load("scripts.js"); 
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbtextField.widget.js");
  db_app::load("classes/dbXmlEditor.js");
  db_app::load("classes/dbXmlFactory.js");

?></head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" 	marginheight="0" onLoad="a=1">
<?
db_menu ( db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?

?>

<script>


var sFileNameXml = 'config/configuracao.xml';
var sXmlAlterado = "";


function js_salvaEdita(sXml){ 
  oParam = new Object();
  oParam.exec = "salvaXML";
  oParam.sXml = tagString(sXml).replace("<quebralinha>","");
  oParam.sXml = oParam.sXml.replace("<quebralinha>","");
  oParam.sXml = oParam.sXml.replace("<quebralinha>","");
  oParam.sXml = oParam.sXml.replace("<quebralinha>","");
  oParam.sXml = oParam.sXml.replace("<quebralinha>","");
  var msgDiv = "Aguarde salvando arquivo XML ...";
  js_divCarregando(msgDiv,'msgBox');
  
      
  var sUrl     = 'con1_organograma.RPC.php';
  var oAjax    = new Ajax.Request(
                                    sUrl, 
                                    {
                                      method    : 'post', 
                                      parameters: 'json='+Object.toJSON(oParam), 
                                      onComplete: js_retornoSalvaXml
                                    }
                                  );            
  
}

function js_retornoSalvaXml(oAjax){
  js_removeObj("msgBox");
  
	if (oRetorno.status== "2") {
		  alert(oRetorno.message.urlDecode());
	 } else {
	
	  //me.onSaveComplete(oRetorno);
	}

    
  
  document.location.href = 'con4_configuracoesgerais.php';  
}

function js_janela(){
  
  if(sFileNameXml != ""){  
    oXmlEditor = new DBXmlEditor(sFileNameXml,js_salvaEdita);
    oXmlEditor.show();
    $('windowXmlEditor').style.width  = screen.availWidth  - 150;
    $('windowXmlEditor').style.height = screen.availHeight - 200;
    $('windowwindowXmlEditor_content').style.height = $('windowXmlEditor').clientHeight - 26  ;
    $('windowXmlEditor').style.left   = (screen.availWidth - $('windowXmlEditor').clientWidth) / 2;
    $('windowXmlEditor').style.top    = 20;
  }
       
}
js_janela();
//$('fileXml').disabled=true;

</script>