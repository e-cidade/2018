<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

/**
 * @todo modificara para (!USE_PCASP)
 */
if (!USE_PCASP) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Este menu só é acessível com o PCASP ativado.");
}
$db_opcao = 1;
db_app::import("configuracao.UsuarioSistema");
$oUsuario       = new UsuarioSistema(db_getsession('DB_id_usuario'));
$sNomeUsuario   = $oUsuario->getNome();
$sCodigoUsuraio = $oUsuario->getIdUsuario();


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
  <style type="text/css">
    .tamanho-primeira-col{
      width:100px;
    }
    .field-border-topo {
      border: none;
      border-top: inset 1px #FFF;
    }
    .oculto {
      display: none;
    }
    #servico{
      color: #fe3b10;
      font-weight: bold;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <div style="margin-top: 25px;"></div>
  <center>
  <div id='container' style="width: 700px;">
    <form action="" id ='inscricao-passiva'>
      <fieldset>
        <legend><b>Inscrição de Passivo sem Suporte Orçamentário</b></legend>
        <fieldset class='field-border-topo'>
          <legend><b>Dados da Inscricao</b></legend>
          <table >
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'><b>Usuário:</b>
              </td>
              <td nowrap="nowrap">
                <?php
                  db_input('sNomeUsuario', 70, '', true, 'text',3);
                  db_input('sCodigoUsuraio', 70, '', true, 'hidden',3);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <?php
                  db_ancora('<b>Favorecido:</b>', "js_pesquisaFavorecido(true);", $db_opcao);
                ?>
              </td>
              <td nowrap="nowrap">
                <?php
                  db_input('numcgm',     10, '', true, 'text', 1, " onchange='js_pesquisaFavorecido(false);'");
                  db_input('favorecido', 56, '', true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <?php
                  db_ancora("<b>Histórico:</b>","js_pesquisaHistorico(true);", $db_opcao);
                ?>
              </td>
              <td>
                <?php
                  db_input('historico', 10, '', true, 'text', $db_opcao," onchange='js_pesquisaHistorico(false);'");
                  db_input('descricaoHistorico',   56, '', true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend><b>Complemento do Histórico</b></legend>
                  <?php
                    db_textarea("complementoHistorico", 4, 80, "", true, 'text', $db_opcao);
                  ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class='field-border-topo'>
          <legend ><b>Adicionar Item</b></legend>
          <table >
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
	              <?php
	                db_ancora("<b>Item:</b>", "js_pesquisaItem(true);", $db_opcao);
	              ?>
              </td>
              <td colspan="3">
                <?php
                  db_input('codigoItem',    10, '', true, 'text', $db_opcao, " onchange='js_pesquisaItem(false);'");
        	        db_input('descricaoItem', 56, '', true, 'text', 3);
        	      ?>
              </td>
            </tr>
            <tr id='elemento-item' class='oculto' >
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Elemento Item:</b>
              </td>
              <td nowrap="nowrap" colspan="3">
                <select id='elementoItem' onchange="js_verificaElemento(this.value);">
                </select>
             </td>
            </tr>
            <tr id='dados-elemento' class='oculto' >
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Elemento:</b>
              </td>
              <td nowrap="nowrap" colspan="3">
                <?php
                  db_input('elemento',          15, '', true, 'hidden', 3);
                  db_input('estrutural',        15, '', true, 'text',   3);
        	        db_input('elementoDescricao', 51, '', true, 'text',   3);
        	      ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Quantidade:</b>
              </td>
              <td>
                <?php
                  /**
                   * Se Item for SERVIÇO, bloquear campo quantidade setando valor para 1
                   */
                  db_input('quantidade', 10, '', true, 'text', 1,
                              "onchange='js_calculaValorTotal(this);'
                               onKeyPress = 'return js_mask(event,\"0-9|.|,|-\")' ");
                ?>
              </td>
              <td nowrap="nowrap" id='servico'>

              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Valor Unitário:</b>
              </td>
              <td>
                <?php
                  db_input('valor-unitario', 10, '', true, 'text', 1,
                           "onchange='js_calculaValorTotal(this);'
                            onKeyPress = 'return js_mask(event,\"0-9|.|,|-\")'");
                ?>
              </td >
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Valor Total:</b>
              </td>
              <td>
                <?php
                  db_input('valor-total', 10, '', true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <fieldset>
                  <legend><b>Observação:</b></legend>
                  <?php
                    db_textarea("observacao", 4, 80, "", true, 'text', 1);
                  ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input type="button" name="adicionarItem" id="add-item" value='Adicionar Item'
             onclick="js_adicionaItem();"/>
      <input type="button" name="Incluir" id="incluir" value='Incluir' onclick="js_incluir();"/>
    </form>
  </div>
  <fieldset style="width: 800px;">
    <legend><b>Itens incluso</b></legend>
    <div id = 'grid-itens'></div>
  </fieldset>
  </center>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

function js_calculaValorTotalRobson() {

   alert('asdfasfasdfasdf');

}

var sUrlRpc    = 'con4_inscricaopassivoorcamento.RPC.php';
var aItens     = new Array();

var oGridItens          = new DBGrid('grid-itens');
oGridItens.nameInstance = 'oGridItens';
//oGridItens.hasCheckbox = true;
oGridItens.setCellWidth(new Array('50%', '12%', '14%', '11%', '13%'));
oGridItens.setHeader(new Array('Item','Quantidade','Valor Unitário','Total', 'Ação'));
oGridItens.setCellAlign(new Array('left','center','center','center', 'center'));
oGridItens.setHeight(130);
oGridItens.show($('grid-itens'));

/** ****************************************************************
 *                     FUNÇÕES DE PESQUISA
 ***************************************************************** */

/**
 * Busca Favorecido
 */
function js_pesquisaFavorecido(mostra) {

  var sUrl = 'func_nome.php?testanome=true';
  if (mostra) {
    js_OpenJanelaIframe('','db_iframe_cgm', sUrl+'&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true);
  } else {

    if($F('numcgm') != '') {
        js_OpenJanelaIframe('','db_iframe_cgm',
                             sUrl+'&pesquisa_chave='+$F('numcgm')+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
       $('favorecido').value = '';
    }
  }
}

function js_mostracgm(erro, chave) {

  $('favorecido').value = chave;
  if (erro) {

    $('numcgm').focus();
    $('numcgm').value = '';
  }
}

function js_mostracgm1(chave1, chave2) {

  $('numcgm').value     = chave1;
  $('favorecido').value = chave2;
  db_iframe_cgm.hide();
}


/**
 * Pesquisa os Itens
 */
function js_pesquisaItem(mostra) {

  var sUrl      = "func_pcmaterelelibaut.php?";
  var sElemento = "";

  if ($('estrutural').value != '' && aItens.length > 0) {
    sElemento = "&chave_o56_elemento="+$F('estrutural');
  }

  if (mostra) {

    sUrl += "funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater|pc07_codele";
    sUrl += sElemento;
    js_OpenJanelaIframe('', 'db_iframe_pcmaterele', sUrl, 'Pesquisa', true);
  } else {

    if ($F('codigoItem') != '') {

      sUrl += "pesquisa_chave="+$F('codigoItem');
      sUrl += "&funcao_js=parent.js_mostrapcmater"+sElemento;
      js_OpenJanelaIframe('',  'db_iframe_pcmaterele', sUrl, 'Pesquisa', false);
    } else {

      $('descricaoItem').value = '';
    }
  }
}
function js_mostrapcmater(chave, erro, codele) {

  $('descricaoItem').value = chave;
  if (erro) {

    $('codigoItem').focus();
    $('codigoItem').value = '';
  } else {

    $('quantidade').focus();

    js_limpaValores();
    js_buscaElementoItem($F('codigoItem'), codele);
  }
}
function js_mostrapcmater1(chave1, chave2, codele) {

  $('codigoItem').value    = chave1;
  $('descricaoItem').value = chave2;
  db_iframe_pcmaterele.hide();
  $('quantidade').focus();

  js_buscaElementoItem(chave1, codele);
  js_limpaValores();
}

/**
 * Pesquisa Histórico
 */
function js_pesquisaHistorico(mostra) {

  var sUrl = 'func_conhist.php?';
  if (mostra) {

    sUrl += 'funcao_js=parent.js_mostraconhist1|c50_codhist|c50_descr';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_conhist', sUrl, 'Pesquisa', true);
  } else {

    if ($F('historico') != '') {

      sUrl += 'pesquisa_chave='+$F('historico');
      sUrl += '&funcao_js=parent.js_mostraconhist';
      js_OpenJanelaIframe('top.corpo', 'db_iframe_conhist', sUrl, 'Pesquisa', false);
    }else{
      $('descricaoHistorico').value = '';
    }
  }
}
function js_mostraconhist(chave, erro) {

  $('descricaoHistorico').value = chave;
  if (erro) {

    $('historico').focus();
    $('historico').value = '';
  }
}
function js_mostraconhist1(chave1, chave2) {

  $('historico').value          = chave1;
  $('descricaoHistorico').value = chave2;
  db_iframe_conhist.hide();
}

/** ****************************************************************
 *                     FUNÇÕES DE NEGOCIO
 ***************************************************************** */
/**
 * Busca os elementos do item
 */
function js_buscaElementoItem(iCodigoMaterial, iCodigoElemento) {

  var oObject             = new Object();
  oObject.exec            = "buscaElementoItem";
  oObject.iCodigoMaterial = iCodigoMaterial;

  if (aItens.length > 0) {
    oObject.iCodigoElemento = $('elementoItem');
  }

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoBuscaElementoItem
                                        }
                                    );
}

function js_retornoBuscaElementoItem(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message);
  } else {

    $("elementoItem").innerHTML = '';
    var iDados                  = oRetorno.dados.length;
    for (var i = 0; i < iDados; i++) {

      var oOption = new Element('option', {'value': ''+oRetorno.dados[i].codigoElemento+''}).
                                                      update(oRetorno.dados[i].codigoElementoDescricao.urlDecode());
      $("elementoItem").appendChild(oOption);

    }
    $('elementoItem').style.width = '508px';
    $$(".oculto").each(function (object, id) {
  	  object.removeClassName("oculto");
    });

    if (oRetorno.lServico == 't') {
      $('servico').innerHTML = "SERVIÇO";
    } else {
      $('servico').innerHTML = "";
    }

    js_verificaElemento($F('elementoItem'));
  }
}

/**
 * Busca o estrutural de acordo com o codele selecionado
 */
function js_verificaElemento(iCodigoElemento) {

  var oObject             = new Object();
  oObject.exec            = "buscaElementoItem";
  oObject.iCodigoElemento = iCodigoElemento;
  oObject.iCodigoMaterial = $F('codigoItem');

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoElemento
                                        }
                                    );
}

function js_retornoElemento(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  for (var i = 0; i < oRetorno.dados.length; i++) {

    $('elemento').value          = oRetorno.dados[i].elemento;
    $('estrutural').value        = oRetorno.dados[i].estrutural;
    $('elementoDescricao').value = oRetorno.dados[i].descricao.urlDecode();

    if (oRetorno.lServico == 't') {

      $('quantidade').value = 1;
      $('quantidade').style.backgroundColor = ' rgb(222, 184, 135)';
      $('quantidade').readOnly = true;
    } else {
      $('quantidade').style.backgroundColor = ' #FFF';
    }
  }
}

/**
 * Calcula Valor Total
 */
function js_calculaValorTotal(oField) {

  if ($F('quantidade') == '' || $F('valor-unitario') == '' ) {

    $('valor-total').value = '';
    return false;
  }

  js_formata(oField);
  var nQuantidade    = js_strToFloat($F('quantidade'));
  var nValorUnitario = js_strToFloat($F('valor-unitario'));
  $('valor-total').value = js_formatar(new Number(new Number(nQuantidade).toFixed(2) * new Number(nValorUnitario)).toFixed(2), 'f');
  
}

/**
 * Adiciona item na grid
 */
function js_adicionaItem() {

  if ($F('codigoItem') == '') {

    alert ('Você deve selecionar um item.');
    return false;
  }

  if ($F('quantidade') == '' || $F('valor-unitario') == '') {

    alert ('Você deve preencher quantidade e valor unitário.');
    return false;
  }

  var lAchoItem = false;
  if (aItens.length > 0) {

    aItens.each( function (oItem, iItem) {

      if (oItem.c38_pcmater == $F('codigoItem')) {

        alert('Item já adicionado');
        lAchoItem = true;
      }
    });
  }

  if (!lAchoItem) {

    var oDadosItem               = new Object();
    oDadosItem.c38_pcmater       = $F('codigoItem');
    oDadosItem.sItemDescricao    = $F('codigoItem') + ' - '+ $F('descricaoItem');
    oDadosItem.c38_quantidade    = $F('quantidade');
    oDadosItem.c38_valorunitario = $F('valor-unitario');
    oDadosItem.c38_valortotal    = $F('valor-total');
    oDadosItem.c38_observacao    = encodeURIComponent(tagString($F('observacao')));
    aItens.push(oDadosItem);
    js_preencheGrid();
  }
}

/**
 * Remove item do Array
 */
function js_removeItem(iCodigoItem) {

  aItens.each( function (oItem, iItem) {

    if (oItem.c38_pcmater == iCodigoItem) {

      aItens.splice(iItem, 1);
    }
  });

  if (aItens.length == 0) {
    js_limpaEstrutural();
  } else {
    $('elementoItem').setAttribute('disabled', 'disabled');
  }
  js_preencheGrid();
}

/**
 * Preenche a Grid
 */
function js_preencheGrid() {

  oGridItens.clearAll(true);
  aItens.each( function (oItem, iCodigoItem) {

    var sAcao  = "<input type = 'button' value = 'E' onclick='js_removeItem("+oItem.c38_pcmater+");'/>";
    var aLinha = new Array();
    aLinha[0] = oItem.sItemDescricao;
    aLinha[1] = oItem.c38_quantidade;
    aLinha[2] = js_formatar(oItem.c38_valorunitario, 'f');
    aLinha[3] = js_formatar(oItem.c38_valortotal, 'f');
    aLinha[4] = sAcao;
    oGridItens.addRow(aLinha);
  });

  oGridItens.renderRows();
  js_limpaItens();
}

/**
 * Troca o ponto por virgula
 */
function js_formata(oInput) {
  
  if (oInput.value.indexOf(',') > 0) {
    return true;
  }

  oInput.value = oInput.value.replace('.', ',');
}

function js_limpaEstrutural() {

  $('elementoItem').innerHTML   = '';
  $('elementoItem').disabled    = false;
  $('elemento').value           = '';
  $('estrutural').value         = '';
  $('elementoDescricao').value  = '';
}

/**
 * Limpa o campo de valores quando selecionado item
 */
function js_limpaValores() {

  $('quantidade').value     = '';
  $('valor-unitario').value = '';
  $('valor-total').value    = '';
  $('observacao').value     = '';
}

function js_limpaItens() {

  $('codigoItem').value    = '';
  $('descricaoItem').value = '';
  js_limpaValores();
}

/**
 * Incluir
 */
function js_incluir() {

  if ($F('numcgm') == '') {
    alert('Selecione o Favorecido.');
    return false;
  }

  if ($F('historico') == "" || $F('complementoHistorico') == "" ) {

    alert ('Você deve preencher o campo histórico e o complemeno do histórico');
    return false;
  }

  if (aItens.length == 0) {

    alert('Deve-se cadastrar ao menos um item para a inscrição.')
    return false;
  }


  var oObject                   = new Object();
  oObject.exec                  = "incluirInscricao";
  oObject.c36_db_usuarios       = $F('sCodigoUsuraio');
  oObject.c36_cgm               = $F('numcgm');
  oObject.c36_codele            = $F('elementoItem');
  oObject.c36_conhist           = $F('historico');
  oObject.c36_observacaoconhist = encodeURIComponent(tagString($F('complementoHistorico')));
  aItens.each(function(oItem, iSeq) {

     oItem.c38_valorunitario = js_strToFloat(oItem.c38_valorunitario).valueOf();
     oItem.c38_valortotal    = js_strToFloat(oItem.c38_valortotal).valueOf();
  });
  oObject.aItens                = aItens;

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete: js_retornoIncluir
                                        }
                                    );

}
function js_retornoIncluir(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  alert(oRetorno.message.urlDecode());
  $('inscricao-passiva').reset();
  oGridItens.clearAll(true);
  aItens = new Array();
}


</script>