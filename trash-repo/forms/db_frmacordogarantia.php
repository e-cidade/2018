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
$clacordogarantia->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><b>Garantias</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tac11_sequencial?>">
       <?=@$Lac11_sequencial?>
    </td>
    <td> 
<?
db_input('ac11_sequencial',10,$Iac11_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tac11_descricao?>">
       <?=@$Lac11_descricao?>
    </td>
    <td> 
<?
db_input('ac11_descricao',40,$Iac11_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tac11_validade?>">
       <?=@$Lac11_validade?>
    </td>
    <td> 
<?
db_inputdata('ac11_validade',@$ac11_validade_dia,@$ac11_validade_mes,@$ac11_validade_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  
  <br>
  
  <fieldset>
  <legend><?=@$Lac11_obs?></legend>
  
  <table>
  <tr>    
    <td align=center> 
<?
db_textarea('ac11_obs',4,50,$Iac11_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  
  <br>
  
  <fieldset>
  <legend><?=@$Lac11_textopadrao?></legend>
  <table>
  <tr>    
    <td align=center> 
<?
db_textarea('ac11_textopadrao',4,50,$Iac11_textopadrao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  
  <br>
  
  <fieldset>
  <legend><b>Tipos de Acordos</b></legend>
  <div id='cntGridTiposAcordos'>
   </div>  
  </fieldset>
  

</fieldset>

</td></tr>
</table>  
  
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="button" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> 
       onclick="<?=($db_opcao==3?"js_excluiGarantia();":"js_salvaGarantia();")?>" 
        >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>

var lMarca               = false;
var lBloquear            = false;
var aTiposAcordosRetorno = Array();
var sUrl = 'con4_contratos.RPC.php';

/*
 * GRID
 */
oGridTiposAcordos              = new DBGrid("gridTiposAcordos");
oGridTiposAcordos.nameInstance = "oGridTiposAcordos";
oGridTiposAcordos.setCheckbox(0);
oGridTiposAcordos.setCellAlign(new Array("center", "center", "left"));
oGridTiposAcordos.setHeader(new Array("Código", "Tipo de Acordo"));
oGridTiposAcordos.show($('cntGridTiposAcordos'));

function js_consultaTiposAcordos(){
   
  js_divCarregando('Consultando tipos de acordos...','msgBox');
   
  var strJson = '{"exec":"pesquisaTipoAcordo"}';
             
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: 'json='+strJson, 
                                           onComplete: js_completaGrid 
                                         }
                                 );    
}

/*
 * Preenche Grid das Garantias
 */ 
function js_completaGrid(oAjax){

  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");   
  var aTiposAcordos = oRetorno.aTipoAcordo;
  
  oGridTiposAcordos.clearAll(true);
      
  aTiposAcordos.each(function (oTipoAcordo, id) {
     
    var aLinha = new Array();
    
    aLinha[0] = oTipoAcordo.codigo;
    aLinha[1] = oTipoAcordo.descricao.urlDecode();
    
    lMarca    = false;
    lBloquear = false;
    
    if (js_search_in_array(aTiposAcordosRetorno, oTipoAcordo.codigo)) {
      lMarca = true;
    }
        
    if ($('db_opcao').value == 'Excluir') {
      lBloquear = true;
    }
        
    oGridTiposAcordos.addRow(aLinha, false, lBloquear, lMarca);     
    
  });
  
  oGridTiposAcordos.renderRows();
     
}

/*
 * Abre lockup de pesquisa penalidades
 */ 
function js_pesquisa() {

  var sUrl = 'func_acordogarantia.php?funcao_js=parent.js_pesquisarGarantias|ac11_sequencial';
  js_OpenJanelaIframe('','db_iframe_acordogarantia',sUrl,'Pesquisa',true);
  
  aTiposAcordosRetorno = Array();
}

/**
 * Pesquisa os dados da garantia
 */
function js_pesquisarGarantias(chave) {
  
  js_divCarregando('Aguarde Pesquisando Garantia...','msgBox');
   
  aTiposAcordosRetorno = new Array();
  var iCodigo   = chave;
   
  var oParam    = new Object();
  oParam.exec   = "pesquisaGarantia";
  oParam.codigo = iCodigo;
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoDadosGarantia
                                        }
                                ); 

  db_iframe_acordogarantia.hide();
}

/**
 * Preenche os dados na tela
 */
function js_retornoDadosGarantia(oAjax) {

  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  var oAcordoGarantia = oRetorno.oAcordoGarantia;
  
  oGridTiposAcordos.clearAll(true);
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.erro.urlDecode());
    return false;
  }

  $('ac11_sequencial').value   = oAcordoGarantia.iCodigo;
  $('ac11_descricao').value    = oAcordoGarantia.sDescricao.urlDecode();
  $('ac11_validade').value     = oAcordoGarantia.sDataLimite.urlDecode();
  $('ac11_obs').value          = oAcordoGarantia.sObservacao.urlDecode();
  $('ac11_textopadrao').value  = oAcordoGarantia.sTextoPadrao.urlDecode();
  
  oAcordoGarantia.aTiposContratos.each(function(oTipo, id) {;
     aTiposAcordosRetorno.push(oTipo) 
  });
  
  js_consultaTiposAcordos();
  
  $('db_opcao').disabled = false;
}

/*
 * Inclui/Exclui Garantia
 */
function js_salvaGarantia() {

  js_divCarregando('Incluindo Acordo Garantia...','msgBox');
  
  var oParam           = new Object();  
  oParam.exec          = "salvarGarantia";
  oParam.iCodigo       = $F('ac11_sequencial');
  oParam.sDescricao    = encodeURIComponent(tagString($F('ac11_descricao')));
  oParam.sObservacao   = encodeURIComponent(tagString($F('ac11_obs')));
  oParam.sTextoPadrao  = encodeURIComponent(tagString($F('ac11_textopadrao')));
  oParam.sDataLimite   = $F('ac11_validade');
  oParam.aTiposAcordos = new Array(); 
  
  var aTiposAcordos = oGridTiposAcordos.getSelection("object");
  
  aTiposAcordos.each( 
    function (oTipoAcordo, iInd) {
    
      oTipoAcordoN                = new Object();
      oTipoAcordoN.iCodTipoAcordo = oTipoAcordo.aCells[0].getValue(); 
      oParam.aTiposAcordos.push(oTipoAcordoN);
    }
  );
  
  if (aTiposAcordos.length == 0) {
  
    js_removeObj("msgBox");
    alert("Operação Cancelada, você deve selecionar ao menos um Tipo de Acordo!");
    return false;    
  }
   
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: 'json='+js_objectToJson(oParam), 
                                           onComplete: js_retornoSalvaGarantia 
                                        }
                                );
}

function js_retornoSalvaGarantia(oAjax) {

  js_removeObj("msgBox");   
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridTiposAcordos.clearAll(true);
  if (oRetorno.status == 2) {
  
    alert(oRetorno.erro.urlDecode());
    return false;
  }
  
  alert("Registro Salvo com Sucesso.");
  
  $('ac11_sequencial').value   = '';
  $('ac11_descricao').value    = '';
  $('ac11_validade').value     = '';
  $('ac11_obs').value          = '';
  $('ac11_textopadrao').value  = ''; 
  
  if ($('db_opcao').value != 'Incluir') {  
    js_pesquisa();
    $('db_opcao').disabled = true;    
  } else {
    js_consultaTiposAcordos();
  }
}

/*
 * Exclui Garantia
 */
function js_excluiGarantia() {

  js_divCarregando('Excluindo Registro...','msgBox');
  
  var oParam           = new Object();  
  oParam.exec          = "excluiGarantia";
  oParam.iCodigo       = $F('ac11_sequencial');
    
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: 'json='+js_objectToJson(oParam), 
                                           onComplete: js_retornoExcluiGarantia 
                                        }
                                );
}

function js_retornoExcluiGarantia(oAjax) {

  js_removeObj("msgBox");   
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  oGridTiposAcordos.clearAll(true);
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.erro.urlDecode());
    return false;
  }
  
  alert("Registro Excluido com Sucesso.");
  
  $('ac11_sequencial').value   = '';
  $('ac11_descricao').value    = '';
  $('ac11_validade').value     = '';
  $('ac11_obs').value          = '';
  $('ac11_textopadrao').value  = ''; 
  
  js_pesquisa();
}
  

js_consultaTiposAcordos();

<? if ($db_opcao != 1 && $db_opcao != 11) { ?>
      js_pesquisa();
<? } ?>
</script>
