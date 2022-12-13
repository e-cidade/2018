<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_libdicionario.php");
  require_once("libs/db_libcontabilidade.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("classes/db_conparametro_classe.php");
  
  $oGet = db_utils::postMemory($_GET);
?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js"); 
  db_app::load("strings.js, grid.style.css, datagrid.widget.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top:25px;">
<center>
  <form>
    <fieldset style="width: 500px;">
      <legend><b>Conta Orçamentária</b></legend>
      <table>
        <tr>
          <td>
            <?php 
              db_ancora("<b>Conta Orçamentária:</b>", "js_pesquisaContaOrcamento(true)", 1);
            ?></td>
          <td>
            <?php 
              db_input("iCodigoContaOrcamento", 8, null, true, "text", 1, "onchange='js_pesquisaContaOrcamento(false);'");
              db_input("sDescricaoContaOrcamento", 40, null, true, "text", 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p>
      <input type="button" name="btnIncluirContaOrcamento" id="btnIncluirContaOrcamento" value="Incluir" />
    </p>
  </form>
  <fieldset style="width: 800px;">
    <legend><b>Contas Orçamentárias Vinculadas</b></legend>
      <div id="divContasVinculadas">
      </div>
  </fieldset>
</center>
</body>
</html>

<script>

/*
 * Codigo da Conta Passada por GET
 */
var iCodigoConta = <?=@$oGet->iCodigoConta?>;

var oGridContasOrcamento              = new DBGrid('oGridContasOrcamento');
    oGridContasOrcamento.nameInstance = 'oGridContasOrcamento';
    oGridContasOrcamento.sName        = 'oGridContasOrcamento';
    oGridContasOrcamento.setCellAlign = (new Array("center","left", "right"));
    aHeaders                          = new Array("Código", "Descrição Conta", "Estrutural", "Ação");
    oGridContasOrcamento.aWidths      = new Array(10, 30, 10, 3);
    oGridContasOrcamento.setHeader(aHeaders);
    oGridContasOrcamento.show($('divContasVinculadas'));


function js_loadContasOrcamento() {

  js_divCarregando("Aguarde, carregando reduzidos...", "msgBox");
  
  var oParam          = new Object();
  oParam.exec         = "getVinculoPlanoOrcamento";
  oParam.iCodigoConta = iCodigoConta;

  var oAjax = new Ajax.Request("con4_conplanoPCASP.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheGridContaOrcamento
                                }
                               );
}

function js_preencheGridContaOrcamento(oAjax) {

  js_removeObj("msgBox");
  oGridContasOrcamento.clearAll(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  oRetorno.aContasOrcamento.each(function (oContaOrcamento, iLinha) {

    var aLinha = new Array();
    aLinha[0]  = oContaOrcamento.c60_codcon;
    aLinha[1]  = oContaOrcamento.c60_descr.urlDecode();
    aLinha[2]  = oContaOrcamento.c60_estrut;
    aLinha[3]  = '<center><input type="button" value="E" id="btnExcluir_'+iLinha+'" onclick="js_excluirVinculo('+oContaOrcamento.c60_codcon+');" ></center>';

    oGridContasOrcamento.addRow(aLinha);
  });
  oGridContasOrcamento.renderRows();
}

function js_excluirVinculo(iCodigoContaOrcamento) {

  if (!confirm("Confirma a exclusão da conta "+iCodigoContaOrcamento+"?")) {
    return false;
  }

  js_divCarregando("Aguarde, excluindo...", "msgBox");
  var oParam                        = new Object();
  oParam.exec                       = "excluiVinculoPlanoOrcamento";
  oParam.iCodigoConta               = iCodigoConta;
  oParam.iCodigoPlanoOrcamento      = iCodigoContaOrcamento;
  
  var oAjax = new Ajax.Request("con4_conplanoPCASP.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                   
                                   js_removeObj("msgBox");
                                   var oRetorno = eval("("+oAjax.responseText+")");
                                   alert(oRetorno.message.urlDecode());
                                   js_loadContasOrcamento();
                                 }
                                }
                               );


  
}

$("btnIncluirContaOrcamento").observe("click", function() {


  js_divCarregando("Aguarde, executando vinculação...", "msgBox");
  var oParam                        = new Object();
  oParam.exec                       = "vinculaPlanoOrcamentario";
  oParam.iCodigoPlanoPCASP          = iCodigoConta;
  oParam.iCodigoPlanoOrcamento      = $("iCodigoContaOrcamento").value;
  
  var oAjax = new Ajax.Request("con4_conplanoPCASP.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoVinculaPlanoOrcamento
                                }
                               );
});

/**
 * Retorno da ação de SALVAR o vinculo entre plano de contas
 */
function js_retornoVinculaPlanoOrcamento(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  js_loadContasOrcamento();
  $("iCodigoContaOrcamento").value = "";
  $("sDescricaoContaOrcamento").value = "";
}

function js_pesquisaContaOrcamento(lMostraWindow) {

  if (lMostraWindow) {
    var sUrl = 'func_conplanoorcamento.php?funcao_js=parent.js_preencheContaOrcamento|c60_codcon|c60_descr';
    js_OpenJanelaIframe('top.corpo.iframe_vinculo','db_iframe_conta_orcamento',sUrl,'Pesquisa',true,'0');
  } else {
    if($("iCodigoContaOrcamento").value != ''){ 
      var sUrl = 'func_conplanoorcamento.php?pesquisa_chave='+$("iCodigoContaOrcamento").value+'&funcao_js=parent.js_completaContaOrcamento';
      js_OpenJanelaIframe('top.corpo.iframe_vinculo','db_iframe_conta_orcamento',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoContaOrcamento").value = ''; 
    }
  }
}
function js_preencheContaOrcamento(iCodigoContaOrcamento, sDescricaoContaOrcamento) {
  $('iCodigoContaOrcamento').value    = iCodigoContaOrcamento;
  $('sDescricaoContaOrcamento').value = sDescricaoContaOrcamento;
  db_iframe_conta_orcamento.hide();
}
function js_completaContaOrcamento(sDescricaoContaOrcamento, lErro) {
  if (!lErro) {
    $('sDescricaoContaOrcamento').value = sDescricaoContaOrcamento;
  } else {
    $('iCodigoContaOrcamento').value    = '';
    $('sDescricaoContaOrcamento').value = sDescricaoContaOrcamento;
  }
}
js_loadContasOrcamento();
</script>