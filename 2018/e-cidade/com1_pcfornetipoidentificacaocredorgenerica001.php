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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 30px;">

<center>
  <form id="form1">
    
    <fieldset style="width: 550px">
      <legend><b>Inscrição Genérica</b></legend>
      <table width="100%">
        <tr>
          <td>
            <?php 
              db_ancora("<b>Inscrição Genérica:</b>", "js_pesquisaInscricaoGenerica(true);", 1);
            ?>
          </td>
          <td>
            <?php 
              db_input("c25_sequencial", 10, null, true, "text", 1, "onchange='js_pesquisaInscricaoGenerica(false);'");
              db_input("c25_descricao", 40, null, true, "text", 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <br />
    <input type="button" name="btnSalvarInscricaoGenerica" id="btnSalvarInscricaoGenerica" value="Incluir" />    
  </form>
  <br>
  
  <fieldset style="width: 550px;">
    <legend><b>Inscrições Cadastradas</b></legend>
    <div id="ctnGridInscricaoCadastrada">
    </div>
  </fieldset>
</center>
</body>
</html>

<script>

var sUrlRPC = "com4_pcfornetipoidentificacaocredorgenerica.RPC.php";
var oGet    = js_urlToObject(window.location.search);

$('btnSalvarInscricaoGenerica').observe('click', function() {

  var iInscricaoGenerica = $F("c25_sequencial");
  if (iInscricaoGenerica == "") {

    alert("Informe a inscrição genérica do fornecedor.");
    return false;
  }

  var oParam            = new Object();
  oParam.exec           = "salvarInscricaoFornecedor";
  oParam.c25_sequencial = iInscricaoGenerica;
  oParam.pc60_numcgm    = oGet.pc81_cgmforn;

  js_divCarregando("Aguarde, incluindo inscrição...", "msgBox");
  var oAjax       = new Ajax.Request(sUrlRPC,
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam), 
                                       onComplete: function (oAjax) {

                                           js_removeObj("msgBox");
                                           var oRetorno = eval("("+oAjax.responseText+")");
                                           alert(oRetorno.message.urlDecode());
                                           if (oRetorno.status == 1) {
  
                                             $("c25_sequencial").value = "";
                                             $("c25_descricao").value = "";
                                             js_pesquisaInscricaoCadastrada();
                                           }
                                         } 
                                      });
});

function js_init() {


  oGridInscricao              = new DBGrid('ctnGridInscricaoCadastrada');
  oGridInscricao.nameInstance = 'oGridInscricao';
  var aHeaders   = new Array("Código", "Descrição", "Ação");
  var aCellAlign = new Array("right", "left", "right");
  var aCellWidth = new Array("10%", "70%", "10%");
  oGridInscricao.setHeight(200);
  oGridInscricao.setCellAlign(aCellAlign);
  oGridInscricao.setCellWidth(aCellWidth);
  oGridInscricao.setHeader(aHeaders);
  oGridInscricao.show($('ctnGridInscricaoCadastrada'));

  js_pesquisaInscricaoCadastrada();
}

function js_pesquisaInscricaoCadastrada() {

  js_divCarregando("Carregando...", "msgBox");

  var oParam         = new Object();
  oParam.exec        = "getInscricoesCadastradas";
  oParam.pc60_numcgm = oGet.pc81_cgmforn;
  
  var oAjax       = new Ajax.Request(sUrlRPC,
                                      {method: 'post',
                                       parameters: 'json='+Object.toJSON(oParam), 
                                       onComplete: js_preencheGridInscricoes 
                                      });
}


function js_preencheGridInscricoes(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  oGridInscricao.clearAll(true);
  oRetorno.aInscricoes.each(function (oInscricao, iLinha) {

    var aLinha = new Array();
    aLinha[0] = oInscricao.c25_sequencial;
    aLinha[1] = oInscricao.c25_descricao.urlDecode();
    aLinha[2] = "<input type='button' value='Excluir' id='btnExcluir_"+oInscricao.c26_sequencial+"' onclick='js_excluirInscricao("+oInscricao.c26_sequencial+");'>";
    oGridInscricao.addRow(aLinha);
  });
  oGridInscricao.renderRows();
}

function js_excluirInscricao(iInscricao) {

  if (!confirm("Confirma a exclusão da inscrição?")) {
    return false;
  }

  var oParam            = new Object();
  oParam.exec           = "excluirInscricao";
  oParam.c26_sequencial = iInscricao;

  js_divCarregando("Aguarde, excluindo inscrição...", "msgBox");
  var oAjax       = new Ajax.Request(sUrlRPC,
                                    {method: 'post',
                                     parameters: 'json='+Object.toJSON(oParam), 
                                     onComplete: function (oAjax) {

                                       js_removeObj("msgBox");
                                       var oRetorno = eval("("+oAjax.responseText+")");
                                       alert(oRetorno.message.urlDecode());
                                       js_pesquisaInscricaoCadastrada();
                                     } 
                                    });
}
/**
 * Abre ANCORA para a seleção da inscrição genérica do fornecedor
 */
function js_pesquisaInscricaoGenerica(lMostra) {

  if (lMostra) {

    var sUrlOpenTrue = 'func_tipoidentificacaocredorgenerica.php?funcao_js=parent.js_preencheInscricaoGenerica|c25_sequencial|c25_descricao';
    js_OpenJanelaIframe('top.corpo.iframe_pcforneidentificacaocredor','db_iframe_pcforneidentificacaocredor', sUrlOpenTrue, 'Pesquisa Inscrição Genérica',true);
  } else {

    var sUrlOpenFalse = 'func_tipoidentificacaocredorgenerica.php?pesquisa_chave='+$F('c25_sequencial')+'&funcao_js=parent.js_completaInscricaoGenerica';
    js_OpenJanelaIframe('top.corpo.iframe_pcforneidentificacaocredor','db_iframe_pcforneidentificacaocredor', sUrlOpenFalse, 'Pesquisa Inscrição Genérica', false);
  }
}

/**
 * Preenche os dados do formulário com a inscrição genérica selecionada pelo usuário
 */
function js_preencheInscricaoGenerica(iCodigoSequencial, sDescricao) {

  $('c25_sequencial').value = iCodigoSequencial;
  $('c25_descricao').value  = sDescricao;
  db_iframe_pcforneidentificacaocredor.hide();
}

/**
 * Completa o formulário caso o usuário tenha digitado o sequencial corretamente
 */
function js_completaInscricaoGenerica(sDescricao, lErro) {

  $('c25_descricao').value  = sDescricao;
  if (lErro == true) {
    $('c25_sequencial').value = "";
  }
}
js_init();
</script>