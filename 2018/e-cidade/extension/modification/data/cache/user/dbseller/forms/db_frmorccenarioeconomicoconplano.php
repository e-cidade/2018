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
?>
<form name="form1" method="post" action="">

        <fieldset style="width:900px">
          <legend>
            <b><?=$sLabelFieldSet?></b>
          </legend>
          <table style="width:100%" border="0">
            <tr>
              <td style="width:100px">
                <?
                db_ancora("<b>$sLabelFormulario</b>","js_pesquisa_estrutural(true);",1);
               ?>
              </td>
              <td style="width:100px"> 
                <? 
                db_input('codfon',15,"",true,'hidden',3);
                db_input('fonte',15,"",true,'text',1,"onchange='js_pesquisa_estrutural(false)'");
                ?>
              </td>
              <td > 
                <? 
                db_input('descricao', 90, "", true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td><b>Tipo de Cálculo</b></td>
              <td colspan="100%">
              <?php 
                $aTipoCalculo = array("1" => "Pela média histórica",
                                      "2" => "Pela reestimativa exercício atual");
                
                db_select("tipocalculo", $aTipoCalculo, true, 1,"style=width:100%");
              ?>
              </td>
            </tr>
            
            <tr>
              <td colspan="3" style='border-top:2px groove white;padding-top:5px'>
                <div id ='gridAssocia' >
                </div>
              </td>
            </tr>
            <tr>
              <td colspan='3' style='text-align:center'>
                 <input type='button' onclick='js_salvar()' id='btnsalvar' value="Salvar Modificações">
              </td>
            </tr>  
          </table>
        </fieldset>

</form>
<script>
              
/**
 * Configurações do programa
 */
sUrlRPC = 'orc4_ppaRPC.php';
iTipo   = <?=$oGet->tipo?>; 
function js_init(){

  oGridAssocia = new DBGrid("gridAssocia");
  oGridAssocia.nameInstance = "oGridAssocia";
  oGridAssocia.setCheckbox(0);
  oGridAssocia.setCellAlign(new Array("right", "Left","center","right"));
  oGridAssocia.setHeader(new Array("Parâmetro","Descrição","Ano Ref","Valor","Ano Orc", "Tipo Calc."));
  oGridAssocia.setHeight(300);
  oGridAssocia.aHeaders[5].lDisplayed =false;
  oGridAssocia.aHeaders[1].lDisplayed =false;

  aTamanhoColuna = new Array();
  aTamanhoColuna[0] = "5%";
  aTamanhoColuna[1] = "40%";
  aTamanhoColuna[2] = "10%";
  aTamanhoColuna[3] = "10%";
  aTamanhoColuna[4] = "40%";
  
  oGridAssocia.setCellWidth(aTamanhoColuna);
  oGridAssocia.allowSelectColumns(true);
  oGridAssocia.show(document.getElementById('gridAssocia'));

}
       
function js_pesquisa_estrutural(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_orcfontes',
                        '<?=$sNomeLookup?>?funcao_js=parent.js_mostraorcfontes1|<?= $sCamposFuncao?>|o57_codfon',
                        'Receitas',true);
  } else {
  
    fonte=document.form1.fonte.value;
        
      while(fonte.search(/\./)!='-1') {
        fonte=fonte.replace(/\./,'');
      }
      if (fonte.length < 15) {
      
        for (var i = fonte.length; i < 15; i++ ) {
          fonte += "0";
        }
        document.form1.fonte.value= fonte;
     } 
    if (fonte!='') {
    
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_orcfontes',
                          '<?=$sNomeLookup?>?pesquisa_chave='+fonte+'&funcao_js=parent.js_mostraorcfontes',
                          'Receitas',false);
    }else{
      document.form1.fonte.value='';
    }  
  }
}

function js_mostraorcfontes(chave,erro,codfon){

  if(erro){ 
  
    document.form1.fonte.focus();
    oGridAssocia.clearAll(true);
     
  } else {
    
    document.form1.descricao.value = chave;
    document.form1.codfon.value    = codfon;
    js_getParametros();
    
  }
}
function js_mostraorcfontes1(chave1,chave2,codfon){
  
  db_iframe_orcfontes.hide();
  document.form1.fonte.value     = chave1;
  document.form1.descricao.value = chave2;
  document.form1.codfon.value    = codfon;
  js_getParametros();
  
} 

function js_getParametros() {

  var iEstrutural = $F('fonte');
  var iCodFonte   = $F('codfon')
  var sParam      = '"fonte":'+iEstrutural+',"conplano":'+iCodFonte;
  var sJson       = '{"exec":"getParametros","params":{'+sParam+'}}'; 
  js_divCarregando('Aguarde, pesquisando parâmetros.', "msgBox");
  var oAjax       = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+sJson, 
                          onComplete: js_retornoGetParametros
                          }
                        );
   
}
function js_retornoGetParametros(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridAssocia.clearAll(true);
  if (oRetorno.status == 1) {
  
    for (var i = 0; i < oRetorno.itens.length; i++) {
      
      with (oRetorno.itens[i]) {
     
        var aLinha = new Array();
        aLinha[0]  = o03_sequencial;      
        aLinha[1]  = o03_descricao.urlDecode();      
        aLinha[2]  = o03_anoreferencia;  
        aLinha[3]  = js_formatar(o03_valorparam,"f");  
        aLinha[4]  = o03_anoorcamento;  
        aLinha[5]  = sTipoCalculo.urlDecode();
        var lMarcado = false;
        if (o04_orccenarioeconomicoparam != "") {
           lMarcado = true
        }    
        oGridAssocia.addRow(aLinha, false, null, lMarcado);
      }
    }
    oGridAssocia.renderRows();
  }
}
function js_objectToJson(oObject) { return JSON.stringify(oObject); 
  
   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;
   
}

function js_salvar() {

  js_divCarregando('Aguarde, Salvando parâmetros.', "msgBox");
  $('btnsalvar').disabled = true;
  var aParametros         = oGridAssocia.getSelection();
  var oParam              = new Object();
  oParam.conplano         = $F('codfon');
  oParam.iEstrutural      = $F('fonte');
  oParam.iTipo            = iTipo;
  oParam.exec             = "salvarParametros";
  oParam.iTipoCalculo     = $F('tipocalculo');
  oParam.aParametros      = new Array();
  
  for (i = 0; i < aParametros.length; i++) {
    
    var oParametroConta              = new Object();
    oParametroConta.o03_sequencial   = aParametros[i][0];
    oParametroConta.o03_anoorcamento = aParametros[i][3];
    oParam.aParametros.push(oParametroConta);
    
  }
  
  var oAjax       = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoSaveParametros
                          }
                        );
  
} 
     
function js_retornoSaveParametros(oAjax) {
  
  js_removeObj("msgBox");
  $('btnsalvar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    alert("Parâmetros salvos com sucesso."); 
    oGridAssocia.clearAll(true);
    $('codfon').value    = '';
    $('fonte').value     = '';
    $('descricao').value = '';
    
  } else {
    alert('Erro ao Salvar Parâmetros');
  }
}


js_init(); 
</script>