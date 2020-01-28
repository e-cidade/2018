<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

//MODULO: Arrecadacao
$clnumprebloqpag->rotulo->label();
?>
<form name="form1" method="post" action="">
  <table border="0" width="100%">
    <tr>
      <td nowrap title="<?=$Tar22_numpre?>" align="center">
         <?=$Lar22_numpre?>
         <?php
           db_input('ar22_numpre', 10, $Iar22_numpre, true, 'text', $db_opcao);
         ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" nowrap>
       <div name="gridNumpre" id="gridNumpre"> </div>
      </td>
    </tr>
  </table>
  <input name="btnSalvarItens"
         type="button"
         id="btnSalvarItens"
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
         <?=($db_botao == false ? "disabled" : "")?> />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
</form>
<script>
$('ar22_numpre').observe('blur', function() {
  js_getParcelas($F('ar22_numpre'));
});

var sUrlRC = 'arr4_numprebloqpag.RPC.php';
function js_init() {
  
  oGrid = new DBGrid('gridNumpre');
  oGrid.nameInstance = "oGrid";
  oGrid.setCheckbox(0);
  oGrid.setCellAlign(new Array("center","center","center","center","center","center"));
  oGrid.setCellWidth(new Array("10%","7%","30%","35%","9%","9%"));
  oGrid.setHeader(new Array("Numpre","Numpar","Tipo","Receita","Dt. Oper.","Dt. Venc."));
  oGrid.show($('gridNumpre'));

  $('btnSalvarItens').observe("click", js_salvarItens);
  
  if ($("ar22_numpre").value != "") {
    js_getParcelas($("ar22_numpre").value);
  }
}

oAutoComplete = new dbAutoComplete($('ar22_numpre'),'arr4_debitos.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('ar22_numpre'));
oAutoComplete.show();

function js_getParcelas(iNumpre) {

  js_divCarregando('Aguarde, buscando parcelas do numpre',"msgBox");
  var oParam      = new Object();
      oParam.exec = "getDadosNumpre";

  if ($("btnSalvarItens").value == "Excluir") {
    oParam.exec = "getDadosNumpreBloqueado";
  }
  
  oParam.iNumpre = iNumpre;
  var oAjax      = new Ajax.Request(sUrlRC,
                                     {
                                      method: "post",
                                      parameters:'json='+Object.toJSON(oParam),
                                      onComplete: js_montaParcelas
                                     });
}

function js_montaParcelas(oAjax) {

  js_removeObj('msgBox');
  oGrid.clearAll(true);

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.aRegistros.iStatus == 2) {

    alert(oRetorno.aRegistros.sMensagem.urlDecode());
    $('ar22_numpre').value = '';
    return false;
  }

  for(var i = 0; i < oRetorno.aRegistros.length; i++) {
    
    with (oRetorno.aRegistros[i]) {
    
      var aLinha = new Array();
      aLinha[0]  = oRetorno.aRegistros[i]["k00_numpre"];
      aLinha[1]  = oRetorno.aRegistros[i]["k00_numpar"];
      aLinha[2]  = oRetorno.aRegistros[i]["k00_tipo"]+" - "+oRetorno.aRegistros[i]["k00_descr"];  
      aLinha[3]  = oRetorno.aRegistros[i]["k00_receit"]+" - "+oRetorno.aRegistros[i]["k02_descr"];; 
      aLinha[4]  = js_formatar(oRetorno.aRegistros[i]["k00_dtoper"],'d');  
      aLinha[5]  = js_formatar(oRetorno.aRegistros[i]["k00_dtvenc"],'d');  
      oGrid.addRow(aLinha);  
    }
  }

  oGrid.renderRows();
}

function js_salvarItens() {

  if(empty($F('ar22_numpre'))) {

    alert('Nenhum numpre informado.');
    return false;
  }

  if(oGrid.getSelection().length == 0) {

    alert('Selecione ao menos uma parcela.');
    return false;
  }

  var oParam      = new Object();
      oParam.exec = "incluirNumpre";

  if ($("btnSalvarItens").value == "Excluir") {
    oParam.exec = "excluirNumpre";
  }

  var aSelected = oGrid.getSelection();
  var aDados    = new Array();
  
  for (var x = 0; x < aSelected.length; x++) {
     
    var lIncluso = false;
    for (var i = 0; i < aDados.length; i++) {

      if (aDados[i].numpre == aSelected[x][1] && aDados[i].numpar == aSelected[x][2]) {
        lIncluso = true;
      }
    }

    if (lIncluso) {
      continue;
    }

    var oNumpre        = new Object();
        oNumpre.numpre = aSelected[x][1];
        oNumpre.numpar = aSelected[x][2];

    aDados.push(oNumpre);
  }

  js_divCarregando('Aguarde, buscando parcelas do numpre',"msgBox");

  oParam.aDados = aDados;
  var oAjax     = new Ajax.Request(sUrlRC,
                                    {
                                     method: "post",
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: js_retorno
                                    });
}

function js_retorno(oAjax) {

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.sMensagem.urlDecode());

  if (oRetorno.iStatus == 2) {
    return false;
  }
  
  oGrid.clearAll(true);
  $("ar22_numpre").value = "";

  if ($("btnSalvarItens").value == "Excluir") {
    js_pesquisa();
  }
}

function js_pesquisa() {

  js_OpenJanelaIframe(
    'CurrentWindow.corpo',
    'db_iframe_numprebloqpag',
    'func_numprebloqpag.php?funcao_js=parent.js_preenchepesquisa|ar22_sequencial',
    'Pesquisa',
    true
  );
}

function js_preenchepesquisa(chave) {

  db_iframe_numprebloqpag.hide();
  <?php
  if($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>