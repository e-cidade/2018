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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet                 = db_utils::postMemory($_GET);
$oPost                = db_utils::postMemory($_POST);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/dbtextField.widget.js");
      db_app::load("widgets/dbtextFieldData.widget.js");
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <div class='container' style='width:700px !important;'>
    
      <fieldset>
        <legend><strong>Cancelamento de Importação Geral de Diversos</strong></legend>
          
          <fieldset class="separator">
       	  <legend><strong>Importações Encontradas</strong></legend>
          
       	  <div id="container-grid"></div>
          
          </fieldset>
      </fieldset>
    
      <input name="reemissao" type="button"  value="Processsar"  onclick="js_cancelamentoImportacaoGeralDiversos();">      
    </div>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script type="text/javascript">
var aRegistros = [];
var sUrlRPC    = 'dvr3_importacaoiptu.RPC.php';
			
			(function(){
			
			oGridImportacoes               = new DBGrid('GridImportacoes');
			oGridImportacoes.nameInstance    = 'oGridImportacoes';
			oGridImportacoes.sName           = 'GridImportacoes';
			oGridImportacoes.setCellAlign    (new Array("center","center","left"));
			oGridImportacoes.setCheckbox(0);
			oGridImportacoes.setHeader       (["Código da Importação","Data","Observação"]);
			oGridImportacoes.aWidths			    = new Array('25%','25%','50%');
			oGridImportacoes.show($('container-grid'));
			oGridImportacoes.clearAll(true);
			
			var oDadosRequisicao    		  = new Object();
			oDadosRequisicao.method 		  = 'post';
			oDadosRequisicao.asynchronous = false;
			oDadosRequisicao.parameters   = 'json='+Object.toJSON({sExec:'getImportacaoGeral'});
			oDadosRequisicao.onComplete   = function(oAjax){
			    
			  var oRetorno = eval("("+oAjax.responseText+")");
			  if (oRetorno.status == "2") {
			      
			     alert(oRetorno.message.urlDecode());
			     return;
			  }
			
			  for(var iImportacao=0; iImportacao < oRetorno.aImportacoes.length; iImportacao++ ){
			
			    var oDadosImportacoes = oRetorno.aImportacoes[iImportacao];
			    
				  oGridImportacoes.addRow([oDadosImportacoes.dv11_sequencial,js_formatar(oDadosImportacoes.dv11_data,'d'),oDadosImportacoes.dv11_obs.urlDecode()],'','');
			
			    aRegistros.push(oDadosImportacoes.dv11_sequencial);
			    
			  }
			  oGridImportacoes.renderRows();
			 
			};
			
			var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
			}
			)();
    
		 function js_cancelamentoImportacaoGeralDiversos() {
			 
			 try{

			  if( oGridImportacoes.getSelection().length == 0){
				  throw( _M('tributario.diversos.dvr3_cancelimpiptu001.preenchimento_importacoes_obrigatorio') );
				} 		         
				 
			 } catch (oException) {
 				alert(oException);
 				return false;
			 }

			 if( !confirm(_M('tributario.diversos.dvr3_cancelimpiptu001.deseja_efetuar_cancelamento_importacao_geral')) ) {
				  return false;
			 }
			 
			 var sMsg = _M('tributario.diversos.dvr3_cancelimpiptu001.processando_cancelamento_importacao_geral_debitos');
			 js_divCarregando(sMsg, 'msgbox');

			 /**
        * Envia dados para processamento
        */
			 var oParametros                   = new Object();
			 oParametros.sExec                 = 'cancelaImportacao';
			 var aRegistros 								   = new Array();
			 
			 for(var iRegistros=0; iRegistros < oGridImportacoes.getSelection().length; iRegistros++ ){

				 var aImportacoesSelecionadas    = new Array();
				 aImportacoesSelecionadas        = oGridImportacoes.getSelection();
				 
				 aRegistros.push( aImportacoesSelecionadas[iRegistros][0] );
			 }
			 oParametros.aCodigosImportacao    = aRegistros;
			 
			 var oDadosRequisicao    		  		 = new Object();
		       oDadosRequisicao.method 		   = 'POST';
		       oDadosRequisicao.asynchronous = false;
		       oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
		       oDadosRequisicao.onComplete   = function(oAjax){
			       
		         js_removeObj('msgbox');
		         
		    	   var oRetorno = eval("("+oAjax.responseText+")");
		         if (oRetorno.status == "2") {
		               
		            alert(oRetorno.message.urlDecode());
		            return;
		         }
		         		         
		         alert( oRetorno.message.urlDecode() );
		         window.location = 'dvr3_cancelimpgeraliptu001.php';
			     }

       var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
		 }

</script>