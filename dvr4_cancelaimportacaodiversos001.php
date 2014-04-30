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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");

$clrotulo = new rotulocampo();
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');
?>
<html>
<head>
<?php
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
  db_app::load('estilos.css, grid.style.css');
?>
</head>
<body bgcolor="#CCCCCC">
  <form class="container" name="form1" id="form1">
  
    <fieldset>
      <legend>Cancelamento de Importação de Alvará para Diversos</legend>
      
      <table class="form-contianer">
        <tr> 
			    <td title="<?=$Tq02_inscr?>"> 
			    <?php
			    	db_ancora($Lq02_inscr, 'js_pesquisaInscricao(true);', 4);
			    ?>
			    </td>
			    <td>
			    	<?php 
			    	  db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', 1, "onchange='js_pesquisaInscricao(false)'");
			    		db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
			    	?>			    
			    </td>
			  </tr>
      </table>
    </fieldset>
      <input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos" onclick="js_pesquisaDebitos()" />
      
    <fieldset id="grid" style="margin: 0 auto 10px; width: 750px">
      <legend>
        <strong>Detalhes da Importação:</strong>
      </legend>
      <div id="oGridDebitos"></div>
    </fieldset>
      
      <input type="button" name="processar" id="processar" value="Cancelar Importa&ccedil;&atilde;o" onclick="js_cancelarImportacao()" />
            
  </form>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>

function js_pesquisaInscricao(lMostra) {
  if (lMostra==true) {
    js_OpenJanelaIframe('top.corpo', 'db_iframe', 'func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome', 'Pesquisa', true);
  }else{
    js_OpenJanelaIframe('top.corpo', 'db_iframe', 'func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraInscricaoHide', 'Pesquisa', false);
  }
}

function js_mostraInscricao(iInscricao, sNome) {

	$('q02_inscr').value = iInscricao;
	$('z01_nome').value  = sNome;

	db_iframe.hide();
	
}

function js_mostraInscricaoHide(sNome, lErro) {

	$('z01_nome').value = sNome;
	
	if (lErro == true) {
		$('q02_inscr').value = '';
	}	
	
}	

js_init_table();

var sUrlRPC = 'dvr3_importacaoiptu.RPC.php';

function js_cancelarImportacao() {

	var aRegistrosSelecionados   = oGridDebitos.getSelection();
  var aImportacoesSelecionadas = new Array();
  var lErro                    = false;
  var sMsgErro                 = "Erro: \n";

  if (aRegistrosSelecionados.length == 0) {
	  alert(_M("tributario.diversos.dvr4_cancelaimportacaodiversos001.nenhum_registro_selecionado"));
	  return false;
  }
  
  aRegistrosSelecionados.each(
    function ( aRow ) {
      aImportacoesSelecionadas.push( aRow[0] );
    }
  );
  
  if (lErro) {
	  alert(sMsgErro);
	  return false;
  }

  if( !confirm(_M("tributario.diversos.dvr4_cancelaimportacaodiversos001.deseja_reverter_debitos")) ) {
	  return false;
  }
  var sMsg = _M('tributario.diversos.dvr4_cancelaimportacaodiversos001.revertendo_importacao');
  js_divCarregando(sMsg, 'msgbox');
  //js_divCarregando('Revertendo importação de débitos, aguarde.', 'msgbox');

  var oParam                  = new Object();
  oParam.sExec                = 'cancelaImportacao';
  oParam.aCodigosImportacao   = aImportacoesSelecionadas;

	var oAjax = new Ajax.Request(sUrlRPC,
            									{ 
															 method    : 'POST',
       												 parameters: 'json='+Object.toJSON(oParam), 
       												 onComplete: js_retornoProcessamento
      												});
	
}

function js_retornoProcessamento(oAjax) {

	var oGet = js_urlToObject();
	
	js_removeObj('msgbox');

	var oRetorno  = eval("("+oAjax.responseText+")");

	oGridDebitos.clearAll(true);

	if (oRetorno.status == 1) {

		alert(_M("tributario.diversos.dvr4_cancelaimportacaodiversos001.sucesso_cancelamento_importacao_debitos"));

		window.location = 'dvr4_cancelaimportacaodiversos001.php';
		 
	} else {

		alert(oRetorno.message);
		
	}
}

function js_init_table() {
	
	oGridDebitos              = new DBGrid('oGridDebitos');
  oGridDebitos.nameInstance = 'oGridDebitos';
  oGridDebitos.setHeight(150);
  oGridDebitos.setCheckbox(0);
  oGridDebitos.setCellAlign(new Array('center', 
		  																'center', 
		  																'center', 
		  																'center'  , 
		  																'left'  , 
		  																'left'  ));
	
  oGridDebitos.setCellWidth(new Array('10%', 
		   																'10%', 
		   																'10%' , 
		   																'20%', 
		   																'20%', 
		   																'30%'));
		
  oGridDebitos.setHeader   (new Array('Código'           , 
		  															  'Data'             , 
		  															  'Hora'             , 
		  															  'Tipo Débito'      ,
		  															  'Receitas'         , 
		  															  'Observação'       ));
	  
  oGridDebitos.show($('oGridDebitos'));
  
}

function js_pesquisaDebitos() {

  var oParam            = new Object();

  oParam.iTipoPesquisa  = 5;
  oParam.iChavePesquisa = $F('q02_inscr');    

  oParam.sExec      = 'getDebitosImportados';
  var sMsg2 = _M('tributario.diversos.dvr4_cancelaimportacaodiversos001.pesquisando_debito');
  js_divCarregando(sMsg2, 'msgbox');
	//js_divCarregando('Pesquisando débitos, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrlRPC, { method: 'POST', parameters: 'json='+Object.toJSON(oParam), onComplete: js_retornaDebitos } );
	
}

function js_retornaDebitos(oAjax) {
	
	js_removeObj('msgbox');

	var oRetorno  = eval("("+oAjax.responseText+")");

	oGridDebitos.clearAll(true);

	if (oRetorno.status == 1) {

		$('grid').style.display = '';

		for (var i = 0; i < oRetorno.aDebitos.length; i++) {
		
			with (oRetorno.aDebitos[i]) {

			  aLinha     = new Array();
			  aLinha[0]	 = dv11_sequencial;
			  aLinha[1]  = js_formatar(dv11_data,'d'); 				
			  aLinha[2]  = dv11_hora.urlDecode();                                                                        
			  aLinha[3]  = k00_tipo + ' - ' + k00_descr.urlDecode();                                                             
			  aLinha[4]  = receitas.urlDecode().replace(/,/g, '<BR>');		
			  aLinha[5]  = dv11_obs.urlDecode();		
			}
			oGridDebitos.addRow(aLinha);
			
		}
		
		oGridDebitos.renderRows();

	} else {

		alert(_M("tributario.diversos.dvr4_cancelaimportacaodiversos001.nenhum_registro"));

	}

}

</script>
<script>

$("q02_inscr").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");

</script>