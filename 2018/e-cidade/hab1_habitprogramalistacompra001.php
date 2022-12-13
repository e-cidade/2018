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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_habitprogramalistacompraitem_classe.php");
require_once("classes/db_habitprogramalistacompra_classe.php");
require_once("classes/db_habitprograma_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);

$clHabitProgramaListaCompra = new cl_habitprogramalistacompra();
$clHabitProgramaListaCompra->rotulo->label();

$clHabitProgramaListaCompraItem = new cl_habitprogramalistacompraitem();
$clHabitProgramaListaCompraItem->rotulo->label();

$clRotulo = new rotulocampo;
$clRotulo->label("ht07_descricao");

$db_opcao = 1;

if ( isset($oGet->ht17_habitprograma) ) {
	$ht17_habitprograma = $oGet->ht17_habitprograma;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("dbmessageBoard.widget.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_consultaListas();" >
<form name="form1" method="post" action="">
  <table align="center" style="padding-top: 10px;">
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Dados Lista</b>
          </legend>
					<table>
					  <tr>
					    <td nowrap title="<?=@$Tht17_sequencial?>">
					      <?=@$Lht17_sequencial?>
					    </td>
					    <td> 
								<?
								  db_input('ht17_sequencial',10,$Iht17_sequencial,true,'text',3,"");
					  			db_input('ht17_habitprograma',10,'',true,'hidden',3,"");
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tht17_descricao?>">
					      <?=@$Lht17_descricao?>
					    </td>
					    <td> 
								<?
								  db_input('ht17_descricao',54,$Iht17_descricao,true,'text',$db_opcao,"");
								?>
					    </td>
					  </tr>
            <tr>
              <td nowrap title="<?=@$Tht17_formaavaliacao?>">
                <?
                  db_ancora(@$Lht17_formaavaliacao,"js_pesquisaht17_formaavaliacao(true);",$db_opcao);
                ?>
              </td>
              <td> 
                <?
                  db_input('ht17_formaavaliacao',10,$Iht17_formaavaliacao,true,'text',$db_opcao," onchange='js_pesquisaht17_formaavaliacao(false);'");
                  db_input('ht07_descricao',40,$Iht07_descricao,true,'text',3,'');
                ?>
              </td>
            </tr>					  
					  <tr>
					    <td nowrap title="<?=@$Tht17_datalimite?>">
					      <?=@$Lht17_datalimite?>
					    </td>
					    <td> 
								<?
								  db_inputdata('ht17_datalimite',@$ht17_datalimite_dia,@$ht17_datalimite_mes,@$ht17_datalimite_ano,true,'text',$db_opcao,"");
								?>
					    </td>
					  </tr>
				  </table>
				</fieldset>
			</td>
		</tr>		  
		<tr>
		  <td align="center">
          <input type="button" id="btnAcao" class='btnLista' value="Incluir" onClick="js_acao(this.value)">
          <input type="button" id="btnNovo" class='btnLista' value="Novo"    onClick="js_consultaListas();" style="display:none">
		  </td>
		</tr>
		<tr>
		  <td>
		    <fieldset>
		      <legend>
		        <label>
		          <b>Listas Lançadas</b>
		        </label>
		      </legend>
		      <div id="gridListaCompras">
		      </div>
		    </fieldset>
		  </td>
		</tr>  
  </table>
  <div id='telaItensLista' style="display:none;">
    <table align="center" width="100%">
      <tr>
        <td>
			    <fieldset>
			      <legend>
			        <b>Dados Itens Lista</b>
			      </legend>
			      <table align="center">
			        <tr> 
			          <td>
			            <?
			              db_ancora(@$Lht18_pcmater,"js_pesquisaMaterial(true);",$db_opcao);
			            ?>
			          </td>
			          <td>
			            <?
			              db_input('ht18_pcmater'   ,10,$Iht18_pcmater,true,'text',1,"onChange='js_pesquisaMaterial(false);'");
			              db_input('pc01_descrmater',40,'',true,'text',3,'');
			              
			              db_input('ht18_sequencial'              ,10,'',true,'hidden',3,'');
		                db_input('ht18_habitprogramalistacompra',10,'',true,'hidden',3,'');
			            ?>
			          </td>
			        </tr>
			        <tr> 
			          <td>
			            <?
			              db_ancora(@$Lht18_matunid,"js_pesquisaUnidade(true);",$db_opcao);
			            ?>
			          </td>
			          <td>
			            <?
			              db_input('ht18_matunid',10,$Iht18_matunid,true,'text',1,"onChange='js_pesquisaUnidade(false);'");
			              db_input('m61_descr',40,'',true,'text',3,'');
			            ?>
			          </td>
			        </tr>
			        <tr> 
			          <td>
			            <?=@$Lht18_quantidade?>
			          </td>
			          <td>
			            <?
			              db_input('ht18_quantidade',10,$Iht18_quantidade,true,'text',1,'');
			            ?>
			          </td>
			        </tr>                                               
			      </table>
			    </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <center>
				    <input type="button" id="btnAcaoItem" value="Incluir" onClick="js_acaoItem(this.value)">
				    <input type="button" id="btnNovoItem" value="Novo"    onClick="js_consultaItensLista($F('ht18_habitprogramalistacompra'));" style="display:none">
				    <input type="button" id="btnFechar"   value="Fechar"  onClick="">              
          </center>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Itens Lançados</b>
            </legend>
	          <div id="gridItensLista">
	          </div>              
          </fieldset>
        </td>
      </tr>                          
    </table>
  </div>
</form>
</body>
</html>
<script>

var sUrlRPC              = 'hab4_programalistacompra.RPC.php';
var oParam               = new Object();
var oDBGridListaCompras  = new DBGrid('listaCompras');
  
  
oDBGridListaCompras.nameInstance = 'oDBGridListaCompras';
oDBGridListaCompras.setHeight(150);

oDBGridListaCompras.setCellWidth(new Array('10%;','50%;','20%;','20%;'));
oDBGridListaCompras.setCellAlign(new Array('center','left','center','center','center'));
oDBGridListaCompras.setHeader   (new Array('Código','Descrição','Data Limite','','obj'));

oDBGridListaCompras.aHeaders[4].lDisplayed = false;

oDBGridListaCompras.show($('gridListaCompras'));




oDBGridItensLista  = new DBGrid('itensLista');

oDBGridItensLista.nameInstance = 'oDBGridItensLista';
oDBGridItensLista.setHeight(165);
  
oDBGridItensLista.setCellWidth(new Array('50px','350px','150px','80px','80px'));
oDBGridItensLista.setCellAlign(new Array('center','left','left','center','center','center'));
oDBGridItensLista.setHeader   (new Array('Código','Descrição','Unidade','Qtd.','','obj'));
  
oDBGridItensLista.aHeaders[5].lDisplayed = false;
oDBGridItensLista.show($('gridItensLista'));

var sHTMLTelaItens   = $('telaItensLista').innerHTML;
$('telaItensLista').innerHTML = '';

function js_acao(sAcao){
   
  if ( sAcao == 'Incluir' ) {
    js_incluirLista();
  } else if ( sAcao == 'Alterar') {
    js_alterarLista();
  }
 
}


function js_incluirLista(){
  
  if ( $F('ht17_descricao') == '' ) {
    alert('Descrição não informada!');
    return false;
  }
  
  if ( $F('ht17_formaavaliacao') == '' ) {
    alert('Forma de avaliação não informada!');
    return false;
  }
    
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod         = 'incluirLista';
  oParam.iCodPrograma    = $F('ht17_habitprograma');
  oParam.sDescricao      = $F('ht17_descricao');
  oParam.iFormaAvaliacao = $F('ht17_formaavaliacao');
  oParam.dtDataLimite    = $F('ht17_datalimite');
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoIncluirLista
                                }
                              );      
  
}

function js_retornoIncluirLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
  
  alert(oRetorno.sMsg.urlDecode());
    
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaListas();
  }
    
}

function js_alterarLista(){
  
  if ( $F('ht17_descricao') == '' ) {
    alert('Descrição não informada!');
    return false;
  }
  
  if ( $F('ht17_formaavaliacao') == '' ) {
    alert('Forma de avaliação não informada!');
    return false;
  }
    
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod         = 'alterarLista';
  oParam.iCodPrograma    = $F('ht17_habitprograma');
  oParam.iCodLista       = $F('ht17_sequencial');
  oParam.sDescricao      = $F('ht17_descricao');
  oParam.iFormaAvaliacao = $F('ht17_formaavaliacao');
  oParam.dtDataLimite    = $F('ht17_datalimite');
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoAlterarLista
                                }
                              );      
  
}

function js_retornoAlterarLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  alert(oRetorno.sMsg.urlDecode());
  
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaListas();
  }
    
}


function js_excluirLista(iCodLista){
  
  
  if ( !confirm('Deseja realmente excluir a lista nº '+iCodLista)) {
    return false;
  }
  
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod    = 'excluirLista';
  oParam.iCodLista  = iCodLista;
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoExcluirLista
                                }
                              );      
  
}

function js_retornoExcluirLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  alert(oRetorno.sMsg.urlDecode());
  
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaListas();
  }
    
}


function js_consultaListas(){

  $('btnNovo').style.display = 'none';
  
  $('ht17_sequencial').value     = '';  
  $('ht17_descricao').value      = '';
  $('ht07_descricao').value      = '';
  $('ht17_formaavaliacao').value = '';
  $('ht17_datalimite').value     = '';
  
  $('btnAcao').value = "Incluir";


  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod      = 'consultaListas';
  oParam.iCodPrograma = $F('ht17_habitprograma');
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoConsultaListas
                                }
                              );
}


function js_retornoConsultaListas(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  if ( oRetorno.iStatus == 2 ) {
    alert(oRetorno.sMsg.urlDecode());
    return false;
  } else {
    js_montaGridLista(oRetorno.aDadosListas);
  }

}

function js_montaGridLista(aDadosListas){
    
  oDBGridListaCompras.clearAll(true);   
  
  aDadosListas.each(
    function (oListaCompra, iInd) {
      
      var iCodLista = oListaCompra.ht17_sequencial;    
          
      var aCells    = new Array();
          aCells[0] = iCodLista;
          aCells[1] = oListaCompra.ht17_descricao.urlDecode();
          aCells[2] = js_formatar(oListaCompra.ht17_datalimite,'d');
          aCells[3] ="<input type='button' class='btnAcaoGrid' id='alterar' value='A'     onClick='js_telaAlteracaoLista("+iInd+");'>"
                    +"<input type='button' class='btnAcaoGrid' id='excluir' value='E'     onClick='js_excluirLista("+iCodLista+");'>"
                    +"<input type='button' class='btnAcaoGrid' id='item'    value='Itens' onClick='js_montaJanelaItensLista("+iCodLista+");'>";  
          aCells[4] = Object.toJSON(oListaCompra);
          
          oDBGridListaCompras.addRow(aCells);     
          
    }
  );
  
  oDBGridListaCompras.renderRows();    
  
}




function js_telaAlteracaoLista(iIndRow){
   
  var oListaCompra = oDBGridListaCompras.aRows[iIndRow].aCells[4].getValue().evalJSON();
  var sIdRow       = oDBGridListaCompras.aRows[iIndRow].sId;
  var aBtnGrid     = $$('.btnAcaoGrid');
  
  aBtnGrid.each(
    function (oBtn) {
      oBtn.disabled = true;
    }
  );
  
  $('btnNovo').style.display = '';
  $(sIdRow).style.display    = 'none';
  $('btnAcao').value         = "Alterar";
  
  $('ht17_sequencial').value     = oListaCompra.ht17_sequencial;  
  $('ht17_descricao').value      = oListaCompra.ht17_descricao.urlDecode();
  $('ht07_descricao').value      = oListaCompra.ht07_descricao.urlDecode();
  $('ht17_formaavaliacao').value = oListaCompra.ht17_formaavaliacao;
  $('ht17_datalimite').value     = js_formatar(oListaCompra.ht17_datalimite,'d');
  
  
}

function js_montaJanelaItensLista(iCodLista){
                       
  var sContent = sHTMLTelaItens;
                      
  winItensLista = new windowAux('itensLista', '&nbsp;Itens da Lista', 780, 400);
  winItensLista.setContent(sContent);
  winItensLista.show();
                      
  $('window'+winItensLista.idWindow+'_btnclose').observe("click",js_fechaJanelaItensLista);     
  $('btnFechar').observe("click",js_fechaJanelaItensLista);
  
  js_statusBotoes(false); 
  js_consultaItensLista(iCodLista);
  
}

function js_fechaJanelaItensLista(){
  
  js_statusBotoes(true);
  winItensLista.destroy();

}


function js_acaoItem(sAcao){
  
  if ( sAcao == 'Incluir' ) {
    js_incluirItemLista();
  } else if ( sAcao == 'Alterar') {
    js_alterarItemLista();
  }
 
}



function js_consultaItensLista(iCodLista){


  $('btnNovoItem').style.display = 'none';
  $('btnAcaoItem').value         = "Incluir";
  
  $('ht18_habitprogramalistacompra').value = iCodLista;
  $('ht18_sequencial').value  = '';
  $('ht18_pcmater').value     = '';  
  $('pc01_descrmater').value  = '';
  $('ht18_matunid').value     = '';
  $('m61_descr').value        = '';
  $('ht18_quantidade').value  = '';
  

  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod   = 'consultaItensLista';
  oParam.iCodLista = iCodLista;
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoConsultaItensLista
                                }
                              );
}


function js_retornoConsultaItensLista(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  if ( oRetorno.iStatus == 2 ) {
    alert(oRetorno.sMsg.urlDecode());
    return false;
  } else {
    js_montaGridItensLista(oRetorno.aDadosItensLista);
  }

}

function js_montaGridItensLista(aDadosItensLista){
    
  oDBGridItensLista.clearAll(true);   
  
  aDadosItensLista.each(
    function (oItemLista, iIndItem) {
      
      var aCells    = new Array();
          aCells[0] = oItemLista.ht18_pcmater;
          aCells[1] = "&nbsp;"+oItemLista.pc01_descrmater.urlDecode();
          aCells[2] = "&nbsp;"+oItemLista.m61_descr.urlDecode();
          aCells[3] = oItemLista.ht18_quantidade;
          aCells[4] ="<input type='button' class='btnAcaoItemGrid' id='alterar' value='A' onClick='js_telaAlteracaoItemLista("+iIndItem+");'>"
                    +"<input type='button' class='btnAcaoItemGrid' id='excluir' value='E' onClick='js_excluirItemLista("+oItemLista.ht18_sequencial+");'>";  
          aCells[5] = Object.toJSON(oItemLista);
          
          oDBGridItensLista.addRow(aCells);     
          
    }
  );
  
  oDBGridItensLista.renderRows();    
  
}



function js_incluirItemLista(){
  
  if ( $F('ht18_pcmater') == '' ) {
    alert('Material não informado!');
    return false;
  }
  
  if ( $F('ht18_matunid') == '' ) {
    alert('Unidade não informada!');
    return false;
  }

  if ( $F('ht18_quantidade') == '' ) {
    alert('Quantidade não informada!');
    return false;
  }
    
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod      = 'incluirItemLista';
  oParam.iCodLista    = $F('ht18_habitprogramalistacompra');
  oParam.iCodMaterial = $F('ht18_pcmater');
  oParam.iCodUnidade  = $F('ht18_matunid');
  oParam.iQuantidade  = $F('ht18_quantidade');
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoIncluirItemLista
                                }
                              );      
  
}

function js_retornoIncluirItemLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  alert(oRetorno.sMsg.urlDecode());
    
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaItensLista($F('ht18_habitprogramalistacompra'));
  }
    
}


function js_telaAlteracaoItemLista(iIndRow){
   
  var oItemLista  = oDBGridItensLista.aRows[iIndRow].aCells[5].getValue().evalJSON();
  var sIdRow      = oDBGridItensLista.aRows[iIndRow].sId;
  var aBtnGrid    = $$('.btnAcaoItemGrid');
  
  aBtnGrid.each(
    function (oBtn) {
      oBtn.disabled = true;
    }
  );
  
  $('btnNovoItem').style.display = '';
  $(sIdRow).style.display        = 'none';
  $('btnAcaoItem').value         = "Alterar";
  
  
  $('ht18_sequencial').value  = oItemLista.ht18_sequencial;
  $('ht18_pcmater').value     = oItemLista.ht18_pcmater;  
  $('pc01_descrmater').value  = oItemLista.pc01_descrmater.urlDecode();
  $('ht18_matunid').value     = oItemLista.ht18_matunid;
  $('m61_descr').value        = oItemLista.m61_descr.urlDecode();
  $('ht18_quantidade').value  = oItemLista.ht18_quantidade;  
  
  
}


function js_alterarItemLista(){
  
    
  if ( $F('ht18_pcmater') == '' ) {
    alert('Material não informado!');
    return false;
  }
  
  if ( $F('ht18_matunid') == '' ) {
    alert('Unidade não informada!');
    return false;
  }

  if ( $F('ht18_quantidade') == '' ) {
    alert('Quantidade não informada!');
    return false;
  }
    
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod      = 'alterarItemLista';
  
  oParam.iSeqItem     = $F('ht18_sequencial');
  oParam.iCodLista    = $F('ht18_habitprogramalistacompra');
  oParam.iCodMaterial = $F('ht18_pcmater');
  oParam.iCodUnidade  = $F('ht18_matunid');
  oParam.iQuantidade  = $F('ht18_quantidade');    
    
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoAlterarItemLista
                                }
                              );      
  
}

function js_retornoAlterarItemLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
  
  alert(oRetorno.sMsg.urlDecode());
    
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaItensLista($F('ht18_habitprogramalistacompra'));
  }
    
}



function js_excluirItemLista(iCodItem){
  
  if ( !confirm('Deseja realmente excluir o item selecionado!')) {
    return false;
  }
  
  js_divCarregando('Aguarde, pesquisando...', 'msgbox');
  
  oParam.sMethod  = 'excluirItemLista';
  oParam.iSeqItem = iCodItem;
  
  var oAjax = new Ajax.Request( sUrlRPC, 
                                {
                                  method: 'post', 
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: js_retornoExcluirItemLista
                                }
                              );      
  
}


function js_retornoExcluirItemLista(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgbox');
    
  alert(oRetorno.sMsg.urlDecode());
  
  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_consultaItensLista($F('ht18_habitprogramalistacompra'));
  }
    
}


function js_statusBotoes(lHabilita){
  
  if (lHabilita) {
    var lDisabled = false;    
  } else {
    var lDisabled = true;
  }

  var aBtnGrid  = $$('.btnAcaoGrid');
  var aBtnLista = $$('.btnLista');
  
  aBtnGrid.each(
    function (oBtn) {
      oBtn.disabled = lDisabled;
    }
  );

  aBtnLista.each(
    function (oBtn) {
      oBtn.disabled = lDisabled;
    }
  );
  

}


function js_pesquisaMaterial(mostra){

  if(mostra){
  
    js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                        'db_iframe_pcmater',
                        'func_pcmater.php?funcao_js=parent.js_mostraMaterial1|pc01_codmater|pc01_descrmater',
                        'Pesquisa',true);
    $('Jandb_iframe_pcmater').style.zIndex = '999999999';                           
  } else {
  
    if ($('ht18_pcmater').value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                            'db_iframe_pcmater',
                            'func_pcmater.php?pesquisa_chave='+$('ht18_pcmater').value+'&funcao_js=parent.js_mostraMaterial',
                            'Pesquisa',false);
    } else {
       $('pc01_descrmater').value = ''; 
    }
  }
}

function js_mostraMaterial(chave,erro){
  $('pc01_descrmater').value = chave;
  if(erro==true){ 
    $('ht18_pcmater').focus(); 
    $('ht18_pcmater').value = ''; 
  }
}

function js_mostraMaterial1(chave1,chave2){
  $('ht18_pcmater').value    = chave1;
  $('pc01_descrmater').value = chave2;
  db_iframe_pcmater.hide();
}



function js_pesquisaUnidade(mostra){

  if(mostra){
  
    js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                        'db_iframe_matunid',
                        'func_matunid.php?funcao_js=parent.js_mostraUnidade1|m61_codmatunid|m61_descr',
                        'Pesquisa',true);
   $('Jandb_iframe_matunid').style.zIndex = '999999999';
  } else {
  
    if ($('ht18_matunid').value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                            'db_iframe_matunid',
                            'func_matunid.php?pesquisa_chave='+$('ht18_matunid').value+'&funcao_js=parent.js_mostraUnidade',
                            'Pesquisa',false);
    } else {
       $('m61_descr').value = ''; 
    }
  }
}

function js_mostraUnidade(chave,erro){
  $('m61_descr').value = chave;
  if(erro==true){ 
    $('ht18_matunid').focus(); 
    $('ht18_matunid').value = ''; 
  }
}

function js_mostraUnidade1(chave1,chave2){
  $('ht18_matunid').value = chave1;
  $('m61_descr').value    = chave2;
  db_iframe_matunid.hide();
}


function js_pesquisaht17_formaavaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                        'db_iframe_habitformaavaliacao',
                        'func_habitformaavaliacao.php?funcao_js=parent.js_mostrahabitformaavaliacao1|ht07_sequencial|ht07_descricao',
                        'Pesquisa',true);
  }else{
     if(document.form1.ht17_formaavaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_habitprogramalistacompra',
                            'db_iframe_habitformaavaliacao',
                            'func_habitformaavaliacao.php?pesquisa_chave='+document.form1.ht17_formaavaliacao.value+'&funcao_js=parent.js_mostrahabitformaavaliacao',
                            'Pesquisa',false);
     }else{
       document.form1.ht07_descricao.value = ''; 
     }
  }
}

function js_mostrahabitformaavaliacao(chave,erro){
  document.form1.ht07_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ht17_formaavaliacao.focus(); 
    document.form1.ht17_formaavaliacao.value = ''; 
  }
}
function js_mostrahabitformaavaliacao1(chave1,chave2){
  document.form1.ht17_formaavaliacao.value = chave1;
  document.form1.ht07_descricao.value = chave2;
  db_iframe_habitformaavaliacao.hide();
}
</script>