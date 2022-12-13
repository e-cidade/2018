<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

/**
 * 
 * @author I
 * @revision $Author: dbricardo.lopes $
 * @version $Revision: 1.16 $
 */
require(modification("libs/db_stdlib.php"));
require(modification("std/db_stdClass.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_app.utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$clrotulo = new rotulocampo;
$clrotulo->label("pc16_codmater");
$clrotulo->label("pc11_just");
$clrotulo->label("pc11_resum");
$clrotulo->label("pc11_pgto");
$clrotulo->label("pc11_prazo");
$clrotulo->label("pc01_descrmater");
$oDaoUnidades = db_utils::getDao("matunid");
$sSqlUnid     = $oDaoUnidades->sql_query_file(null, "m61_codmatunid,substr(m61_descr,1,20) as m61_descr,
                                                     m61_usaquant,m61_usadec", "m61_descr");
$rsUnid             = $oDaoUnidades->sql_record($sSqlUnid);
$aUnidades          = db_utils::getCollectionByRecord($rsUnid); 
$aParametrosCompras = db_stdClass::getParametro("pcparam",array(db_getsession("DB_anousu")));
$db_opcao           = 1; 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js, AjaxRequest.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()">
  <center>
    <table width="70%">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Adicionar Item</b>
            </legend>
            <table>
              <tr>
                <td>
                <?
                 db_ancora(@$Lpc16_codmater, "js_pesquisapc16_codmater(true);", 1);
                ?>
                </td>
                <td nowrap>
                   <?
                   $pc17_quant = 1;
                   db_input('pc16_codmater', 8, $Ipc16_codmater, true, 'text', 1, " onchange='js_pesquisapc16_codmater(false);'");
                   db_input('pc01_descrmater', 50, $Ipc01_descrmater, true, 'text', 1, '');
                   db_select('pc17_unid',array(), true, 1, "style='width:150px' onchange='js_usaQuantidade(this)'");
                   db_input('pc17_quant', 5, 0, true, 'text', 1, "style='display:none'");
                   ?>
                </td>
              </tr>
              <tr>
                <td>
                </td>
                <td>
                  <input type='button' id='btnOutrasInf' value='Mais Informações'>
                </td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center;">
                 <input type="button" value='Adicionar Item' id='btnAddItem'>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Itens Cadastrados</b>
            </legend>
            <div id='gridItensSolicitacao'>
             
            </div>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <input type="button" value="Salvar Itens" id='btnSalvarItens'>
        </td>
      </tr>
    </table>
  </center>
</body>
<div id='divOUtrasInf' style='display: none; text-align: center;' >
  <table width="100%">
    <tr>
       <td>
         <fieldset>
           <legend>
             <b>Dados Complementares</b>
           </legend>
           <table>
             <tr>
               <td nowrap title="<?=@$Tpc11_prazo?>">
                 <?=@$Lpc11_prazo?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_prazo',3,30,$Ipc11_prazo,true,'text',$db_opcao)
                 ?>
               </td>
             </tr>
             <tr>
               <td nowrap title="<?=@$Tpc11_pgto?>">
                 <?=@$Lpc11_pgto?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_pgto',3,30,$Ipc11_pgto, true, 'text', $db_opcao);
                 ?>
               </td>
             </tr> 
             <tr>
               <td nowrap title="<?=@$Tpc11_resum?>">
                 <?=@$Lpc11_resum?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_resum',3,30,$Ipc11_resum,true,'text',$db_opcao)
                 ?>
               </td>
             </tr>
             <tr>
               <td nowrap title="<?=@$Tpc11_just?>">
                 <?=@$Lpc11_just?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_just',3,30,$Ipc11_just,true,'text',$db_opcao)
                 ?>
               </td>
             </tr> 
           </table>
         </fieldset>
       </td>
    </tr>
    <tr>
      <td colspan="4" style='text-align: center'>
        <input type='button' value='Salvar Informações' id='btnFecharWindowAux' onclick='windowAuxiliar.hide()'>
      </td>
    </tr>
  </table>
</div>
</html>
<div id='div1' style='display: none;'></div> <br>
<script>
var sUrlRC         = 'com4_solicitacaoCompras.RPC.php';
var aItensAbertura = new Array();
function js_init() {
  
  oGridItens              = new DBGrid('gridItens');
  oGridItens.nameInstance = "gridItens";
  oGridItens.setCellAlign(new Array("right","right","left","center", "left","center","center"));
  oGridItens.setCellWidth(new Array("5%","10%","60%","1%","14%","5%","5%"));
  oGridItens.setHeader(new Array("Seq","Codigo","Descrição","cod. Unidade","Unidade","Out.Inf.","Ação"));
  oGridItens.aHeaders[3].lDisplayed  = false; 
  oGridItens.show($('gridItensSolicitacao'));
  js_makeWindow(); 
  $('btnSalvarItens').observe("click", js_salvarItens);
  $('btnAddItem').observe("click", js_adicionarItem);
  $('pc17_unid').style.height = $('pc01_descrmater').style.height+"px";
  $('btnOutrasInf').observe("click",js_maisInformacoes);
  js_parametros();
  $('btnOutrasInf').disabled = true;
}

function js_pesquisapc16_codmater(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('',
                        'db_iframe_pcmater', 
                        'func_pcmatersolicita.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater|pc01_complmater',
                        'Pesquisar Materias/Serviços',
                         true,
                         '0'
                        );
  } else {

    $('btnOutrasInf').disabled = true;
    if ($F('pc16_codmater') != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_pcmater',
                          'func_pcmatersolicita.php?pesquisa_chave='+
                          $F('pc16_codmater')+
                          '&funcao_js=parent.js_mostrapcmater',
                          'Pesquisar Materiais/Serviços',
                          false,'0'
                      );
    } else {
      $('pc16_codmater').value = '';
    }
  }
}

/**
 * Verifica se deve bloquear a edição do Resumo do Item conforme cadastro do material.
 * @param iCodigoMaterial int Código do material.
 */
function buscaLiberaResumo(iCodigoMaterial) {

  if (iCodigoMaterial == "") {
    return;
  }

  oParametros = {
    exec            : 'getDadosMaterial',
    iCodigoMaterial : iCodigoMaterial
  };
  new AjaxRequest("com4_materialsolicitacao.RPC.php", oParametros, function(oRetorno, lErro) {

    if (lErro) {
      $('pc11_resum').disabled = true;
      return;
    }
    $('pc11_resum').disabled = oRetorno.dados.liberaresumo == "f";

  }).execute();
}

function js_mostrapcmater(sDescricaoMaterial,Erro, lVeiculo, sComplemento) {

  var iCodigo = $('pc16_codmater').value;
  js_limparForm();
  $('pc16_codmater').value = iCodigo;
  $('pc01_descrmater').value = sDescricaoMaterial;
  if (Erro == true){

    sComplemento = "";
    $('pc16_codmater').value = "";
  }
  buscaLiberaResumo($('pc16_codmater').value);
  $('pc11_resum').value = sComplemento;
  $('btnOutrasInf').disabled = Erro;
}

function js_mostrapcmater1(iCodigoMaterial, sDescricaoMaterial, sComplemento) {

  js_limparForm();
  buscaLiberaResumo(iCodigoMaterial);
  $('pc16_codmater').value   = iCodigoMaterial;
  $('pc01_descrmater').value = sDescricaoMaterial;
  $('pc11_resum').value      = sComplemento;
  $('btnOutrasInf').disabled = false;
  db_iframe_pcmater.hide();
}

/**
 * Adiciona o item a solicitacao
 */
function js_adicionarItem() {
 
  if ($F('pc16_codmater') == "") {
    
    alert('Informe o material!');
    return false;
     
  }
  js_divCarregando('Aguarde, adicionando item',"msgBox");
  var oParam            = new Object();
  oParam.iCodigoItem    = $F('pc16_codmater');
  oParam.sJustificativa = encodeURIComponent(tagString($F('pc11_just')));
  oParam.sResumo        = encodeURIComponent(tagString($F('pc11_resum')));
  oParam.sPrazo         = encodeURIComponent(tagString($F('pc11_prazo')));
  oParam.sPgto          = encodeURIComponent(tagString($F('pc11_pgto')));
  oParam.iUnidade       = $F('pc17_unid'); 
  oParam.nQuantUnidade  = $F('pc17_quant'); 
  oParam.exec           = "adicionarItemAbertura";
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });
}

function js_retornoadicionarItem(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    js_preencheGrid(oRetorno.itens);
    js_limparForm();
    $('btnOutrasInf').disabled = true;
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_preencheGrid(aItens) {

  aItensAbertura = aItens;
  oGridItens.clearAll(true);
  for(var i = 0; i < aItens.length; i++) {

    with (aItens[i]) {
    
      var aLinha = new Array();
      aLinha[0]  = i+1;
      aLinha[1]  = codigoitem;
      aLinha[2]  = descricaoitem.urlDecode();  
      aLinha[3]  = unidade;  
      aLinha[4]  = unidade_descricao.urlDecode();  
      aLinha[5]  = "<span id='justificativa"+indice+"' style='display:none'>"+justificativa.urlDecode()+"</span>";
      aLinha[5] += "<span id='resumo"+indice+"'        style='display:none'>"+resumo.urlDecode()+"</span>";
      aLinha[5] += "<span id='pgto"+indice+"'          style='display:none'>"+pagamento.urlDecode()+"</span>";
      aLinha[5] += "<span id='prazo"+indice+"'         style='display:none'>"+prazo.urlDecode()+"</span>";
      aLinha[5] += "<span><a href='#' onclick='js_showInfo("+indice+", "+i+")'><img src='imagens/edittext.png' border='0' ></a>...</span>";
      aLinha[6]  = "<input type='button' value='excluir' onclick='js_excluirLinha("+indice+", " + i + ")'>";
      oGridItens.addRow(aLinha);  
      oGridItens.aRows[i].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";

    }
  }
  oGridItens.renderRows();
}     

function js_salvarItens() {
 
  js_divCarregando('Aguarde, Salvando Itens',"msgBox");
  var oParam         = new Object();
  oParam.exec        = "salvarItensAbertura";
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoSalvarItem
                                         });

}

function js_retornoSalvarItem(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    alert('Itens Salvos Com sucesso.')
  } else {
   alert(oRetorno.message.urlDecode());
  }
}
oAutoComplete = new dbAutoComplete($('pc01_descrmater'),'com4_pesquisamateriais.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('pc16_codmater'));
oAutoComplete.show();

function js_limparForm() {

  $('pc16_codmater').value   = "";
  $('pc01_descrmater').value = "";
  $('pc11_resum').value      = ""; 
  $('pc11_just').value       = "";
  $('pc11_pgto').value       = "";
  $('pc11_prazo').value      = "";
  $('btnOutrasInf').disabled = true;
  $('btnFecharWindowAux').onclick  =  function() {
      windowAuxiliar.hide();
  }
  
}

/**
 * Excluí um dos itens cadastrados.
 * @param iSeq          Sequêncial do item conforme consulta.
 * @param iPosicaoArray Posição no array onde está o elemento que deve ser excluído.
 */
function js_excluirLinha(iSeq, iPosicaoArray) {

  var oRow = oGridItens.aRows[iPosicaoArray];
  var sMsg ='Confirma a Exclusão do item '+oRow.aCells[0].getValue()+'-'+oRow.aCells[2].getValue()+"?";
  if (aItensAbertura[iPosicaoArray].temestimativa) {
    sMsg += '\nExistem Estimativas Lançadas para esse Item.'; 
  }
  if (!confirm(sMsg)) {
    return false;
  }
  js_divCarregando('Aguarde, removendo item',"msgBox"); 
  var oParam         = new Object();
  oParam.exec        = "excluirItens";
  oParam.iItemRemover = iSeq;
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });

}

/**
 * Adicionamos as unidades ao combo pc17_unid
 */
 <?
 
  foreach ($aUnidades as $oUnidade) {
    
    echo "var oOption = new Option('{$oUnidade->m61_descr}',{$oUnidade->m61_codmatunid});\n";
    echo "oOption.setAttribute('usadecimal', '{$oUnidade->m61_usadec}');\n";
    echo "oOption.setAttribute('usaquantidade', '{$oUnidade->m61_usaquant}');\n";
    echo "\$('pc17_unid').add(oOption, null);\n";
    
  }
 ?>

function js_usaQuantidade(oSelect) {

  if (oSelect.options[oSelect.selectedIndex].getAttribute("usaquantidade") == "t") {
    $('pc17_quant').style.display = ''; 
  } else {
    $('pc17_quant').style.display = 'none';
  }
    
}
function js_parametros() {
   
  var oParam         = new Object();
  oParam.exec        = "getParametros";
  oParam.sleep       = 5;
  oParam.aParametros = new Array();
  var oParamCompras  = new Object();
  oParamCompras.sParam = "pcparam";
  oParamCompras.aKeys = new Array();
  oParam.aParametros.push(oParamCompras);
  var oParamOrcam  = new Object();
  oParamOrcam.sParam = "orcparametro";
  oParamOrcam.aKeys = new Array();
  oParamOrcam.aKeys.push(2009);
  oParam.aParametros.push(oParamOrcam);
  var oAjax          = new Ajax.Request('sys4_parametros.RPC.php',
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoparametro,
                                          assynchronous: false
                                         });
}

function fechaSemSalvar(prazo, pgto, resum, just, lResumBloqueado) {

  $('pc11_prazo').value    = prazo;
  $('pc11_pgto').value     = pgto;
  $('pc11_resum').value    = resum;
  $('pc11_just').value     = just;
  $('pc11_resum').disabled = lResumBloqueado;
  windowAuxiliar.hide();
}

function js_maisInformacoes() {

  var bkpPrazo = $('pc11_prazo').value;
  var bkpPgto  = $('pc11_pgto').value;
  var bkpResum = $('pc11_resum').value;
  var bkpJust  = $('pc11_just').value;
  bkpDisabled  = $('pc11_resum').disabled;

  windowAuxiliar.show(10,10);
  $('pc11_prazo').focus();
  windowAuxiliar.setShutDownFunction(function() {
    fechaSemSalvar(bkpPrazo, bkpPgto, bkpResum, bkpJust, bkpDisabled)
  });

  $('btnFecharWindowAux').onclick = function() {
    windowAuxiliar.hide();
  }
  
}

function js_makeWindow() {
  
  windowAuxiliar = new windowAux('wndAuxiliar', 'Dados Complementares', 600, 500);
  windowAuxiliar.setObjectForContent($('divOUtrasInf'));
  windowAuxiliar.hide();
  //$('divOUtrasInf').style.display= '';
  
}

function js_showInfo(iIndice, iPosicaoArray) {

  iIndice = iIndice;
  $('pc11_resum').value = $('resumo'+iIndice).innerHTML; 
  $('pc11_just').value  = $('justificativa'+iIndice).innerHTML;
  $('pc11_pgto').value  = $('pgto'+iIndice).innerHTML;
  $('pc11_prazo').value = $('prazo'+iIndice).innerHTML;

  var iCodigo = oGridItens.aRows[iPosicaoArray].aCells[1].getValue();
  buscaLiberaResumo(iCodigo);
  $('btnFecharWindowAux').onclick  = function () {
  
    $('resumo'+iIndice).innerHTML        = $('pc11_resum').value; 
    $('justificativa'+iIndice).innerHTML = $('pc11_just').value ;
    $('pgto'+iIndice).innerHTML          = $('pc11_pgto').value;
    $('prazo'+iIndice).innerHTML         = $('pc11_prazo').value;

    js_limparForm();
    $('btnFecharWindowAux').onclick  =  function() {
      windowAuxiliar.hide();
    };
    js_alterarDados(iIndice);
    windowAuxiliar.hide();
  };
  windowAuxiliar.show(10,10);
  
}

function js_alterarDados(iIndice) {
  
  js_divCarregando('Aguarde, alterando item',"msgBox");
  var oParam            = new Object();
  oParam.iIndice        = iIndice;
  oParam.sJustificativa = encodeURIComponent(tagString($('justificativa'+iIndice).innerHTML));
  oParam.sResumo        = encodeURIComponent(tagString($('resumo'+iIndice).innerHTML)); 
  oParam.sPrazo         = encodeURIComponent(tagString($('prazo'+iIndice).innerHTML));
  oParam.sPgto          = encodeURIComponent(tagString($('pgto'+iIndice).innerHTML));
  oParam.exec           = "alterarItem";
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });
}
function js_retornoparametro(oAjax) {
  
  var oRetorno = eval("("+oAjax.responseText+")");
  for (var iParam = 0; iParam < oRetorno.itens.length; iParam++) {
    
     with(oRetorno.itens[iParam]) {
       eval("o"+name.valueOf()+"=fields");
     }
  }
  $('pc17_unid').value = opcparam[0].pc30_unid;
}
</script>