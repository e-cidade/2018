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

//MODULO: Acordos
$clacordopenalidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac13_sequencial");
$clrotulo->label("ac13_descricao");
$clrotulo->label("ac13_obs");
$clrotulo->label("ac13_textopadrao");
$clrotulo->label("ac13_validade");
?>
<form  name="form1"  action="">
  <fieldset id="fieldsetprincipal">
    <legend><b>Penalidades</b></legend>
    <table border="0" align="center">
      <tr>
        <td title="<?=@$Tac13_sequencial?>" style="width: 80px;">
          <b>Código:</b>
        </td>
        <td>
		      <?
		        db_input('ac13_sequencial',10,$Iac13_sequencial,true,'text',3);
		      ?>
        </td>
      </tr>  
      <tr>
        <td title="<?=@$Tac13_descricao?>" style="width: 80px;">
          <?=@$Lac13_descricao?>
        </td>
        <td>
          <?
            db_input('ac13_descricao',40,$Iac13_descricao,true,'text',$db_opcao);
          ?>
        </td>
      </tr> 
      <tr>
        <td title="<?=@$Tac13_validade?>" style="width: 80px;">
          <?=@$Lac13_validade?>
        </td>
        <td>
		      <?
		        db_inputdata('ac13_validade',@$ac13_validade_dia,@$ac13_validade_mes,@$ac13_validade_ano,true,
		                     'text',$db_opcao,"");
		      ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset id="fieldsetobservacao" class="fieldsetinterno">
            <legend>
              <b>Observação</b>
            </legend>
              <?
                db_textarea('ac13_obs',5,60,$Iac13_obs,true,'text',$db_opcao,"");
              ?>
          </fieldset>
        </td>
      </tr>  
      <tr>
        <td colspan="2">
          <fieldset id="fieldsettextopadrao" class="fieldsetinterno">
            <legend>
              <b>Texto Padrão</b>
            </legend>
              <?
                db_textarea('ac13_textopadrao',5,60,$Iac13_textopadrao,true,'text',$db_opcao,"");
              ?>
          </fieldset>
        </td>
      </tr> 
      <tr>
        <td colspan="2">
          <fieldset id="fieldsettipoacordo">
            <legend>
              <b>Tipos de Acordos</b>
            </legend>
            <div id="listaTipoAcordo"></div>
          </fieldset>
        </td>
      </tr> 
    </table>
  </fieldset>
  <table>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="button" id="db_opcao" 
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?> onClick="return js_processarDados();" > 
      </td>
      <td>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" 
               onClick="return js_pesquisar();" 
               <?//=($db_opcao==1?"disabled":($db_opcao==2||$db_opcao==22?"":""))?> >
      </td>
    </tr>
  </table>
</form>
<script>

  var lMarca               = false;
  var lBloquear            = false;
  var aTiposAcordosRetorno = Array();
  $('ac13_descricao').style.width = '100%';

  var sUrl = 'con4_contratos.RPC.php';
   
  /**
   * Mosnta a grid tipos de acordos na tela
   */
  var oDBGridTipoAcordo          = new DBGrid('TipoAcordo');
  oDBGridTipoAcordo.nameInstance = 'oDBGridTipoAcordo';
  oDBGridTipoAcordo.setCheckbox(0);
  oDBGridTipoAcordo.setHeight(100);
  oDBGridTipoAcordo.setHeader(new Array('Código','Tipo Acordo'));
  oDBGridTipoAcordo.setCellAlign(new Array('center','left','left','left','center'));
  oDBGridTipoAcordo.show($('listaTipoAcordo'));
  
/**
 * Pesquisa tipos de acordos
 */  
function js_pesquisarTipoAcordo(){

  js_divCarregando('Aguarde Pesquisando Tipos de Acordo...','msgBoxTipoAcordo');
   
  var oParam  = new Object();
  oParam.exec = "pesquisaTipoAcordo";
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoDadosTipoAcordo
                                        }
                                );  
}
  
/**
 * Retorna os dados com os tipos de acordos
 */
function js_retornoDadosTipoAcordo(oAjax){
  
  js_removeObj("msgBoxTipoAcordo");
  
  var oRetorno = eval("("+oAjax.responseText+")");
      
  oDBGridTipoAcordo.clearAll(true);
     

  if (oRetorno.status == 1) {
    js_montaGridTipoAcordo(oRetorno.aTipoAcordo);
  }
}
  
/**
 * Monta a grid com os tipos de acordos
 */
function js_montaGridTipoAcordo(aListaTipoAcordo){
  
  var iItensTipoAcordo = aListaTipoAcordo.length;
  
  if (iItensTipoAcordo == 0) {
    oDBGridTipoAcordo.setStatus('Não foram encontrados Registros');
  } else {
    
    aListaTipoAcordo.each(
      function (oTipoAcordo,iInd){
        var aRow = new Array();
        aRow[0]  = oTipoAcordo.codigo;
        aRow[1]  = oTipoAcordo.descricao.urlDecode();
              
        lMarca    = false;
        lBloquear = false;
        if (js_search_in_array(aTiposAcordosRetorno, oTipoAcordo.codigo)) {
          lMarca = true;
        }
        
        if ($('db_opcao').value == 'Excluir') {
          lBloquear = true;
        }
        
        oDBGridTipoAcordo.addRow(aRow, false, lBloquear, lMarca);
      }
    );
  }
      
  oDBGridTipoAcordo.renderRows();
    
}
    
/**
 * Abre lockup de pesquisa penalidades
 */
function js_pesquisar() {

  var sOpcao = $('db_opcao').value;
  var sUrl   = 'func_acordopenalidade.php?funcao_js=parent.js_pesquisarPenalidades|ac13_sequencial';
  
  if (sOpcao == 'Incluir') {
    var sUrl = 'func_acordopenalidade.php?funcao_js=parent.js_pesquisarPenalidades|false';
  }
  
  js_OpenJanelaIframe('','db_iframe_acordopenalidade',sUrl,'Pesquisa',true);
  
  aTiposAcordosRetorno = Array();
}

/**
 * Pesquisa os dados da penalidade
 */
function js_pesquisarPenalidades(chave) {

  if (chave != false) {
  
	  js_divCarregando('Aguarde Pesquisando Penalidades...','msgBoxAcordoPenalidade');
	   
	  aTiposAcordosRetorno = new Array();
	  var iCodigo   = chave;
	   
	  var oParam    = new Object();
	  oParam.exec   = "pesquisaAcordoPenalidade";
	  oParam.codigo = iCodigo;
	    
	  var oAjax   = new Ajax.Request( sUrl, {
	                                          method: 'post', 
	                                          parameters: 'json='+js_objectToJson(oParam), 
	                                          onComplete: js_retornoDadosPenalidade
	                                        }
	                                ); 
  }

  db_iframe_acordopenalidade.hide();
}

/**
 * Preenche os dados na tela
 */
function js_retornoDadosPenalidade(oAjax) {

  js_removeObj("msgBoxAcordoPenalidade");
  
  var oRetorno = eval("("+oAjax.responseText+")");
      
  oDBGridTipoAcordo.clearAll(true);
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.erro.urlDecode());
    return false;
  }

  $('ac13_sequencial').value   = oRetorno.iCodigo;
  $('ac13_descricao').value    = oRetorno.sDescricao.urlDecode();
  $('ac13_validade').value     = oRetorno.dtValidade.urlDecode();
  $('ac13_obs').value          = oRetorno.sObservacao.urlDecode();
  $('ac13_textopadrao').value  = oRetorno.sTextoPadrao.urlDecode();
  
  oRetorno.aTiposContratos.each(function(oTipo, id) {;
     aTiposAcordosRetorno.push(oTipo) 
  });
  
  js_pesquisarTipoAcordo();
  
  $('db_opcao').disabled = false;
}

/**
 * Salva os dados penalidade
 */
function js_processarDados(){
   
  var iCodigo       = $('ac13_sequencial').value;
  var sDescricao    = encodeURIComponent(tagString($('ac13_descricao').value));
  var dtValidade    = $('ac13_validade').value;
  var sObservacao   = encodeURIComponent(tagString($('ac13_obs').value));
  var sTextoPadrao  = encodeURIComponent(tagString($('ac13_textopadrao').value)); 
  var aTiposAcordos = oDBGridTipoAcordo.getSelection("object");
  
  if (sDescricao == '') {

    alert('Nenhuma Descrição Informada!');
    return false;  
  }
  
  if (dtValidade == '') {
  
    alert('Nenhuma Data de Validade Informada!');
    return false;  
  }
  
  if (sObservacao == '') {
  
    alert('Nenhuma Observação Informada!');
    return false;  
  }
  
  if (sTextoPadrao == '') {
  
    alert('Nenhum Texto Padrão Informado!');
    return false;  
  }
  
  if (aTiposAcordos.length == 0) {
    
    alert('Nenhum Tipo de Acordo Selecionado');
    return false;
  }   
   
  if ($('db_opcao').value != 'Excluir') {
    var sExecuta = "salvarPenalidade";
  } else {
    var sExecuta = "excluirPenalidade";
  }
   
  js_divCarregando('Aguarde Processando...','msgBoxProcessar');
   
  var oParam           = new Object();
  oParam.exec          = sExecuta;
  oParam.codigo        = iCodigo;
  oParam.descricao     = sDescricao;
  oParam.observacao    = sObservacao;
  oParam.textopadrao   = sTextoPadrao;
  oParam.datalimite    = dtValidade;
   
  oParam.aTiposAcordos = new Array();
  for (var i = 0; i < aTiposAcordos.length; i++) {
  
    var oTipoAcordo                = new Object(); 
        oTipoAcordo.iCodTipoAcordo = aTiposAcordos[i].aCells[0].getValue();
    
        oParam.aTiposAcordos.push(oTipoAcordo);
  }
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoProcessarDados
                                        }
                                ); 
}

/**
 * Retorno Processo Penalidades
 */
function js_retornoProcessarDados(oAjax) {

  js_removeObj("msgBoxProcessar");
  
  oDBGridTipoAcordo.clearAll(true);
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.erro.urlDecode());
    return false;
  }
  
  alert("Processo concluido com sucesso.");
  
  $('ac13_sequencial').value   = '';
  $('ac13_descricao').value    = '';
  $('ac13_validade').value     = '';
  $('ac13_obs').value          = '';
  $('ac13_textopadrao').value  = ''; 
  
  if ($('db_opcao').value != 'Incluir') {
  
    js_pesquisar();
    $('db_opcao').disabled = true;
  } else {
    js_pesquisarTipoAcordo();
  }
}
</script>