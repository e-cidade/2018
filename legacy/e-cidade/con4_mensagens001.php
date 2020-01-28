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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$lExibirMenu = true;

$oGet = db_utils::postMemory($_GET);

if ( !empty($oGet->lIframe) ) {
  $lExibirMenu = false;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load('estilos.css, grid.style.css');
    db_app::load("scripts.js, strings.js, prototype.js, dbcomboBox.widget.js, datagrid.widget.js");
    db_app::load("DBTreeView.widget.js, windowAux.widget.js, dbmessageBoard.widget.js, arrays.js");
  ?>
<style>
textarea {
  resize: vertical;
}
</style>
</head>
<body bgcolor="#cccccc">

  <fieldset style="margin:30px auto 0 auto;width:800px;">
    <legend>Mensagens</legend>
    <div id="ctnTreeView"></div>
  </fieldset>

  <?php if ( $lExibirMenu ) : ?>
    <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
  <?php endif; ?>
</body>
</html>

<script type="text/javascript">

/**
 * Caminho para arquivo de mensagens 
 */
const MENSAGENS = 'configuracao.configuracao.con4_mensagens001.';

/**
 * Arquivo RPC 
 */
const RPC = 'con4_mensagens.RPC.php';

/**
 * Habilita/desabilita opçao para ALTERAR metodos das mensagens, dois clicks no metodo
 */
const PERMITIR_EDITAR_METODO_MENSAGEM = false;

/**
 * Habilita/desabilita botão para ADICIONAR mensagens ao arquivo 
 */
const PERMITIR_ADICIONAR_MENSAGEM = false;

/**
 * Habilita/desabilita botão para REMOVER mensagens ao arquivo 
 */
const PERMITIR_REMOVER_MENSAGEM = false;

/**
 * Variavel para manter uma unica janela com mensagens do arquivo 
 */
var oWindow = null;

/**
 * Menus agrupados por menu, para nao exibir duplicados 
 */
var aMenusPorPai = {};

/**
 * Cria treeview 
 */
oTreeView = new DBTreeView('treeViewArquivos');
oTreeView.show($('ctnTreeView'));

js_buscarMensagens();

/**
 * Busca itens de menu com os arquivos json 
 *
 * @access public
 * @return void
 */
function js_buscarMensagens() {

  js_divCarregando(_M(MENSAGENS + 'mensagem_buscando_itens_menu'), 'msgbox');
  var oParametro  = new Object();
  oParametro.exec = 'associacoes';
  
  new Ajax.Request(RPC,
                   {method:'post',
                    parameters:'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoBuscarMensagens
                  });

}

/**
 * Retorno da funcao js_buscarMensagens
 *
 * @param Object $oAjax
 * @access public
 * @return void
 */
function js_retornoBuscarMensagens(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  /**
   * Processa array com arvore dos menus
   */
  js_processarMenus(oRetorno.aMenus);

  /**
   * Habilita pesquisa
   */
  oTreeView.allowFind(true);
  oTreeView.setFindOptions('matchedonly');
}

/**
 * Processa array com estrutura dos menus
 * percorre o array recursivamente ate o ultimo nivel com arquivos json do item de menu
 *
 * @param array $aMenus
 * @param string $sPai
 * @access public
 * @return void
 */
function js_processarMenus(aMenus, sPai) {

  for ( mMenus in aMenus ) {      

    /**
     * Funcoes da prototype 
     */
    if (typeof aMenus[mMenus] == 'function') {
      continue;
    }

    /**
     * Arquivos json
     */
    if (typeof aMenus[mMenus] == 'string') {

      /**
       * Arquivo ja exibido para esse pai(nó) 
       */
      if ( !empty(aMenusPorPai[sPai]) && aMenusPorPai[sPai].in_array(aMenus[mMenus]) ) {
        continue;
      }

      js_montarMenuArquivos(aMenus[mMenus], sPai)
      continue;
    }

    /**
     * Label
     */
    if (typeof mMenus == 'string') {
      js_montarArvoreMenu(mMenus, sPai);
    }

    /**
     * Possui filhos, inicia recursao
     */
    if (typeof aMenus[mMenus] == 'object') {
      js_processarMenus(aMenus[mMenus], mMenus);
    }

  }

}

/**
 * Monta arvore de menus
 *
 * @param string sIdentificador - id do item com descricao, separados por um #
 * @param string sPai - id do nó pai 
 */
function js_montarArvoreMenu(sIdentificador, sPai) {

  if ( sPai == undefined ) {
    sPai = '0';
  }

  aDescricao = sIdentificador.urlDecode().split('#');
  sDescricao = aDescricao.length > 1 ? aDescricao[1] : sIdentificador;

  oTreeView.addNode(sIdentificador, sDescricao, sPai);
}

/**
 * Adiciona arquivos json abaixo do nó pai(sPai)
 *
 * @param string $sIdentificador
 * @param string $sPai
 * @access public
 * @return void
 */
function js_montarMenuArquivos(sIdentificador, sPai) {

  if ( empty(aMenusPorPai[sPai]) ) {
    aMenusPorPai[sPai] = new Array();
  }

  aMenusPorPai[sPai].push(sIdentificador);
  
  var sDivArquivo = '<a onClick="js_montarJanelaMensagensArquivo(\'' + sIdentificador + '\');" style="color:#333;text-decoration:none;">' + sIdentificador + '</a>';
  oTreeView.addNode(sIdentificador, sDivArquivo, sPai);
}

/**
 * Monta janela com as mensagens do arquivo clickado
 *
 * @param string $sArquivo
 * @access public
 * @return void
 */
function js_montarJanelaMensagensArquivo(sArquivo) {

  /**
   * Destroi janela caso exista
   */
  if ( oWindow instanceof windowAux  ) {
    oWindow.destroy();
  }

  var iWidthBody  = document.body.clientWidth - 10;
  var iHeightBody = document.body.clientHeight - 30;

  var iWidthJanela  = 800;
  var iHeightJanela = 800;

  if ( iWidthBody < 800 ) {
    iWidthJanela = iWidthBody;
  }
  
  if ( iHeightBody < 800 ) {
    iHeightJanela = iHeightBody;
  }

  var iPosicaoLeft = (iWidthBody - iWidthJanela) / 2;
  var iPosicaoTop  = 25;

  var sHTML = '';

  sHTML += '<div id="ctnJanela"> ';
  sHTML += '   <div id="headerJanela"></div> ';
  sHTML += '   <div id="ctnMensagensArquivo" style="width:99%;padding:10px 0 20px 5px;"> ';
  sHTML += '  </div> ';
  sHTML += '  <center>';
  sHTML += '    <input type="button" value="Salvar" onClick="js_editarArquivo(\''+ sArquivo +'\');" style="margin-bottom:10px;" />';

  if ( PERMITIR_ADICIONAR_MENSAGEM ) {
    sHTML += '    <input type="button" value="Adicionar" onClick="js_adicionarMensagem();" style="margin-bottom:10px;" />';
  }

  sHTML += '  </center>';
  sHTML += '</div> ';

  oWindow = new windowAux('window', _M(MENSAGENS + 'mensagem_titulo_janela_editar_mensagem' ), iWidthJanela, iHeightJanela);
  oWindow.setContent(sHTML);
  oWindow.show(iPosicaoTop, iPosicaoLeft); 

  oWindow.setShutDownFunction(function() {
    oWindow.destroy();
  });

  var sDescricaoJanela  = _M(MENSAGENS + 'mensagem_descricao_janela_editar_arquivo');
  var oMessageBox = new DBMessageBoard('msgboard', sArquivo, sDescricaoJanela, $('headerJanela'));

  oMessageBox.show(); 

  js_divCarregando(_M(MENSAGENS + 'mensagem_processando_janela_editar_arquivo'), 'msgbox');
  var oParametro  = new Object();
  oParametro.exec = 'getMensagensArquivo';
  oParametro.sArquivo = sArquivo;
  
  new Ajax.Request(RPC,
                   {method:'post',
                    parameters:'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoMontarJanelaMensagensArquivo
                  });

}

/**
 * Retorno da funcao js_retornoMontarJanelaMensagensArquivo
 *
 * @param Object $oAjax
 * @access public
 * @return void
 */
function js_retornoMontarJanelaMensagensArquivo(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  /**
   * Erro RPC 
   */
  if ( oRetorno.status > 1 ) {

    alert(oRetorno.mensagem.urlDecode());
    return false;
  }

  /**
   * Percorre objeto com mensagens, criando um fieldset para cada propriedade do objeto 
   */
  for ( sMetodo in oRetorno.oMensagens ) {

    var sMensagem = oRetorno.oMensagens[sMetodo].urlDecode();
    
    var oFieldset = document.createElement('fieldset');
    oFieldset.style.cssText = 'position:relative;';

    var oLegend = js_createLegend(sMetodo);

    if ( PERMITIR_REMOVER_MENSAGEM )  {

      var oRemover = document.createElement('img');
      oRemover.src = 'imagens/jan_fechar_on.gif';
      oRemover.height = '15';
      oRemover.style.cssText = 'height:15px;position:absolute;top:0px;right:-4px;cursor:pointer;';
      oRemover.title = 'Remover mensagem';
      oRemover.onclick = function() {
        $('ctnMensagensArquivo').removeChild(this.parentNode);
      };
      oFieldset.appendChild(oRemover);
    }

    var oTextarea = document.createElement('textarea');
    oTextarea.innerHTML = sMensagem;
    oTextarea.id = sMetodo;
    oTextarea.className = 'field-size-max';

    oFieldset.appendChild(oLegend);
    oFieldset.appendChild(oTextarea);

    $('ctnMensagensArquivo').appendChild(oFieldset);
  }

}

/**
 * Editar arquivo, botao "Salvar"
 *
 * @param string $sArquivo
 * @access public
 * @return void
 */
function js_editarArquivo(sArquivo) {

  var aMensagens = $('ctnMensagensArquivo').getElementsByTagName('textarea');
  var oMensagem = new Object();

  for ( var iIndice = 0; iIndice < aMensagens.length; iIndice++ ) {

    var oElemento = aMensagens[iIndice];
    var sMetodo = oElemento.id;
    var sMensagem = encodeURIComponent(tagString(oElemento.value));

    if ( empty(sMetodo) ) {

      alert(_M(MENSAGENS + 'erro_mensagem_sem_id'));
      return;
    }

    if ( empty(sMensagem) ) {

      alert(_M(MENSAGENS + 'erro_mensagem_sem_conteudo'));
      return;
    }

    oMensagem[sMetodo] = sMensagem;
  }

  js_divCarregando(_M(MENSAGENS + 'mensagem_processando_salvar_arquivo'), 'msgbox');
  var oParametro  = new Object();
  oParametro.exec = 'editarMensagensArquivo';
  oParametro.sArquivo = sArquivo;
  oParametro.oMensagem = oMensagem;

  new Ajax.Request(RPC,
                   {method:'post',
                    parameters:'json='+Object.toJSON(oParametro),
                    onComplete: js_retornoEditarArquivo
                  });
}

/**
 * Retorno da funcao js_editarArquivo
 *
 * @param Object $oAjax
 * @access public
 * @return void
 */
function js_retornoEditarArquivo(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("(" + oAjax.responseText + ")");
  var sMensagem = oRetorno.mensagem.urlDecode();

  if ( oRetorno.status > 1 ) {

    alert(sMensagem);
    return false;
  }

  /**
   * Exibe mensagem de arquivo editado e remove janela com as mensagens do arquivo
   */
  alert(sMensagem);
  oWindow.destroy();
}

/**
 * Adiciona uma nova mensagem ao arquivo, botao "Adicionar"
 *
 * @access public
 * @return void
 */
function js_adicionarMensagem() {

  var oFieldset = document.createElement('fieldset');
  oFieldset.style.cssText = 'position:relative;';

  var oLegend = js_createLegend();
  oLegend.ondblclick = doNothing;

  if ( PERMITIR_REMOVER_MENSAGEM ) {

    var oRemover = document.createElement('img');
    oRemover.src = 'imagens/jan_fechar_on.gif';
    oRemover.height = '15';
    oRemover.style.cssText = 'height:15px;position:absolute;top:0px;right:-4px;cursor:pointer;';
    oRemover.title = 'Remover mensagem';
    oRemover.onclick = function() {
      $('ctnMensagensArquivo').removeChild(this.parentNode);
    };
    oFieldset.appendChild(oRemover);
  }

  var oTextarea = document.createElement('textarea');
  oTextarea.innerHTML = '';
  oTextarea.className = 'field-size-max';
  oTextarea.placeholder = 'Conteudo da mensagem';

  var oInput = js_createInput();

  oLegend.appendChild(oInput);
  oFieldset.appendChild(oLegend);
  oFieldset.appendChild(oTextarea);

  $('ctnMensagensArquivo').appendChild(oFieldset);
}

/**
 * Cria um elemento no dom do tipo LEGEND
 *
 * @param string $sTitulo
 * @access public
 * @return <legend>
 */
function js_createLegend(sTitulo) {

  var oLegend = document.createElement('legend');

  if ( !empty(sTitulo) ) {
    oLegend.innerHTML = sTitulo;
  }

  if ( PERMITIR_EDITAR_METODO_MENSAGEM ) {

    oLegend.ondblclick = function() {
   
      oInput = js_createInput(oLegend.innerHTML);
      oLegend.innerHTML = '';
      oLegend.appendChild(oInput);
      oInput.focus();
      this.ondblclick = doNothing;
    }
  }

  return oLegend;
}

/**
 * Cria um elemento no dom do tipo INPUT
 *
 * @param string $sValue
 * @access public
 * @return <input>
 */
function js_createInput(sValue) {

  var oInput = document.createElement('input');

  oInput.type = 'text';
  oInput.placeholder = 'id da mensagem';

  /**
   * Define valor para input, "value" 
   * - guarda valor antigo, caso for inserido valor com caracteres invalidos
   */
  if ( !empty(sValue) ) {

    oInput.value = sValue;
    oInput.antigoValor = sValue;
  }

  oInput.style.width = '300px';

  /**
   * Ao alterar input 
   */
  oInput.onchange = function() {

    /**
     * Valia campo com id da mensagem 
     */
    var lMetodoMensagemValido = js_validarMetodoMensagem(this.value);
    if( !lMetodoMensagemValido ) {

      this.value = this.antigoValor;
      return false;
    }

    var oTextarea = this.parentNode.parentNode.getElementsByTagName('textarea')[0].id = this.value;

    var oLegend       = js_createLegend(this.value);
    var oFieldset     = this.parentNode.parentNode;
    var oAntigoLegend = this.parentNode; 
    oFieldset.appendChild(oLegend);
    oFieldset.removeChild(oAntigoLegend);

    this.onblur = doNothing;
  }

  /**
   * Ao sair do campo, sem editar nada
   */
  oInput.onblur = function() {

    lMetodoValido = js_validarMetodoMensagem(this.value);

    if ( !lMetodoValido ) {

      this.focus();
      return false;
    }

    var oLegend = js_createLegend(this.value);
    var oFieldset = this.parentNode.parentNode;
    var oAntigoLegend = this.parentNode; 

    oFieldset.appendChild(oLegend);
    oFieldset.removeChild(oAntigoLegend);
  }

  return oInput;
}

/**
 * Valida metodos das mensagens
 *
 * @param string $sMetodo
 * @access public
 * @return boolean
 */
function js_validarMetodoMensagem(sMetodo) {

  /**
   * Metodo nao informado 
   */
  if ( empty(sMetodo) ) {

    alert(_M(MENSAGENS + 'erro_mensagem_sem_id'));
    return false;
  }

  var sERValidarCampo = new RegExp("[^A-Za-z0-9\_]+");

  if( sMetodo.match(sERValidarCampo) ) {

    alert(_M(MENSAGENS + 'erro_id_mensagem_invalido'));
    return false;
  }

  return true;
}

function doNothing() {}
</script>