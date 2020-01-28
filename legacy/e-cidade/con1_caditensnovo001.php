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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oDaoDbItensMenu = new cl_db_itensmenu();
$oDaoDbItensMenu->rotulo->label();
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("estilos.css");
      db_app::load("DBMensagem.js");
      db_app::load("DBAbas.widget.js");
      db_app::load("DBLancador.widget.js");
      db_app::load("DBToogle.widget.js");
      db_app::load("DBTreeView.widget.js");
    ?>
  </head>
  <body>
    <div style="margin-top: 20px;" id='ctnAbas'></div>
    
    <!-- CONTAINER DA ABA CADASTRO MENU -->
    <div id="ctnCadastroMenu" class="container">
      <fieldset>
        <legend>Dados do Menu</legend>
        <form method="post" action="#">
          <table class="form-container">
            <tr style="display: none;">
              <td>
                <label>Id do Item:</label>
              </td>
              <td>
                <input id="inputCodigo" type="text" class="field-size2" />
              </td>
            </tr>
            <tr>
              <td>
                <label>Descrição:</label>
              </td>
              <td>
                <input id="inputDescricao" type="text" class="field-size9" />
              </td>
            </tr>
            <tr>
              <td>
                <label>Ajuda:</label>
              </td>
              <td>
                <input id="inputAjuda" type="text" class="field-size9" />
              </td>
            </tr>
            <tr>
              <td>
                <label>Função:</label>
              </td>
              <td>
                <input id="inputFuncao" type="text" class="field-size9" />
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>Descrição Técnica</legend>
                  <div>
                    <textarea id="textAreaDescricaoTecnica"></textarea>
                  </div>
                </fieldset>
            </tr>
            <tr style="display: none;">
              <td>
                <label>Item Ativo:</label>
              </td>
              <td>
                <input id="inputItemAtivo" type="text" class="field-size9" value="1" />
              </td>
            </tr>
            <tr style="display: none;">
              <td>
                <label>Manutenção:</label>
              </td>
              <td>
                <input id="inputManutencao" type="text" class="field-size9" value="1" />
              </td>
            </tr>
            <tr>
              <td>
                <label>Liberado Para Cliente:</label>
              </td>
              <td>
                <select id="selectLiberadoCliente"></select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset id="dadosModulos" class="separator">
                  <legend>Módulo</legend>
                  <table class="sub-table">
                    <tr>
                      <td>
                        <label>Nome:</label>
                      </td>
                      <td>
                        <input id="inputNomeModulo" type="text" />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Descrição:</label>
                      </td>
                      <td>
                        <input id="inputDescricaoModulo" type="text" />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label>Imagem:</label>
                      </td>
                      <td>
                        <input id="inputImagemModulo" type="text" />
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr style="display: none;">
              <td>
                <input id="inputModuloVinculo" type="text" />
              </td>
            </tr>
          </table>
        </form>
      </fieldset>
      <input id="btnIncluirAlterar" type="button" value="Incluir"         />
      <input id="btnExcluir"        type="button" value="Excluir"         disabled="disabled" />
      <input id="btnNovoItem"       type="button" value="Novo Item"       />
      <input id="btnProcurar"       type="button" value="Procurar"        />
      <input id="btnSelecionar"     type="button" value="Selecionar Menu" />
    </div>
    
    <!-- CONTAINER DA ABA ORGANIZAÇÃO MENU -->
    <div id="ctnOrganizacaoMenu"></div>
  </body>
</html>
<script>
require_once( 'scripts/classes/configuracao/DBViewMenus.classe.js' );

var sRpc             = 'sys4_itensmenus.RPC.php';
var lModulo          = false;
var oDados           = new Object();
var oGet             = js_urlToObject();
var oViewMenus       = null;
var sCaminhoMensagem = "configuracao.configuracao.con1_caditensnovo001.";

if ( oGet.iCodigo ) {
  buscaItemMenu( oGet.iCodigo );
}


/**
 * *********************************************************************************************
 * *********************** ELEMENTOS REFERENTES A ABA CADASTRO MENU ****************************
 * *********************************************************************************************
 */
/**
 * Alterações do estado do elemento inputCodigo
 */
$('inputCodigo').readOnly              = true;
$('inputCodigo').style.backgroundColor = '#DEB887';

/**
 * Alterações do estado do elemento inputItemAtivo
 */
$('inputItemAtivo').readOnly              = true;
$('inputItemAtivo').style.backgroundColor = '#DEB887';

/**
 * Alterações do estado do elemento inputManutencao
 */
$('inputManutencao').readOnly              = true;
$('inputManutencao').style.backgroundColor = '#DEB887';

/**
 * Setadas as opções do select de liberação para o cliente
 */
$('selectLiberadoCliente').add( new Option( 'NÃO', 'f' ) );
$('selectLiberadoCliente').add( new Option( 'SIM', 't' ) );

/**
 * Alterações do estado do elemento inputNomeModulo
 */
$('inputNomeModulo').readOnly              = true;
$('inputNomeModulo').style.backgroundColor = '#DEB887';
$('inputNomeModulo').style.width           = '427px';

/**
 * Alterações do estado do elemento inputDescricaoModulo
 */
$('inputDescricaoModulo').readOnly              = true;
$('inputDescricaoModulo').style.backgroundColor = '#DEB887';
$('inputDescricaoModulo').style.width           = '427px';

/**
 * Alterações do estado do elemento inputImagemModulo
 */
$('inputImagemModulo').readOnly              = true;
$('inputImagemModulo').style.backgroundColor = '#DEB887';
$('inputImagemModulo').style.width           = '427px';

/**
 * Toogle para as informações do Módulo
 * Ao expandir/ocultar o fieldset, trata os campos referentes ao módulo para enviar ao RPC
 */
var oToogleModulo            = new DBToogle( 'dadosModulos', false );
    oToogleModulo.afterClick = function() { tratamentoToogle(); };


/**
 * *******************************************************************************************
 * ******************************** EVENTOS DOS BOTÕES  **************************************
 * *******************************************************************************************
 */
/**
 * Ao clicar no botão procurar, carrega a lookup de pesquisa dos itens existentes
 */
$('btnProcurar').onclick = function() {

  var sUrl  = 'func_db_itensmenu.php?funcao_js=parent.mostraItens';
      sUrl += '|id_item|descricao|help|funcao|desctec|libcliente|itemativo|manutencao';

  js_OpenJanelaIframe( '', 'db_iframe', sUrl, 'Pesquisa Menu', true );
};

/**
 * Ao clicar no botão Novo Item, altera o formulário para inclusão e limpa os campos
 */
$('btnNovoItem').onclick = function() {

  $('btnIncluirAlterar').value   = 'Incluir';
  $('btnExcluir').disabled       = true;
  oAbaOrganizacaoMenu.lBloqueada = true;
  
  oToogleModulo.show( false );
  limpaFormulario();
};

/**
 * Ao clicar para incluir/alterar, chama a função que valida se os campos foram preenchidos
 */
$('btnIncluirAlterar').onclick = function() {
  validaPreenchimentoCampos();
};

/**
 * Ao clicar em excluir, chama a função para exclusão do item
 */
$('btnExcluir').onclick = function() {
  excluiItemMenu();
};

/**
 * Ao clicar em Selecionar Menu, busca os menus de um módulo a ser selecionado
 * MANTIDA LÓGICA DO PROGRAMA ANTIGO QUE RETORNA OS DADOS DO MENU SELECIONADO
 */
$('btnSelecionar').onclick = function() {
  js_OpenJanelaIframe( '', 'db_iframe', 'con1_caditens002.php', 'Pesquisa Módulo', true );
};


/**
 * *******************************************************************************************
 * *********************** FUNÇÕES DE CHAMADA DO RPC E RETORNOS ******************************
 * *******************************************************************************************
 */
/**
 * Ao retornar o menu, verifica se o mesmo é um módulo e preenche os dados
 */
function buscaModulo() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'buscaItemMenuModulo';
      oParametro.iCodigo   = $('inputCodigo').value;
  
  oDados.fRetorno  = retornoBuscaModulo;
  oDados.sMensagem = _M( sCaminhoMensagem + "buscando_informacoes_modulo" );
  requisicaoRpc( oParametro, oDados );
}

/**
 * Retorno da pesquisa pelo módulo. Caso retornem informações, habilita o toogle
 */
function retornoBuscaModulo( oResposta ) {

  js_removeObj( "msgBox" );
  limpaCamposModulo();

  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  if ( oRetorno.lModulo ) {

    $('inputNomeModulo').value      = oRetorno.sNome.urlDecode();
    $('inputDescricaoModulo').value = oRetorno.mDescricao.urlDecode();
    $('inputImagemModulo').value    = oRetorno.sImagem.urlDecode();

    oToogleModulo.show( true );
    tratamentoToogle();
  }

  alteraSituacaoTela();
}

/**
 * Salva o item de menu que está sendo incluído ou alterado
 */
function salvaItemMenu() {

  var oParametro                   = new Object();
      oParametro.sExecucao         = 'salvarItemMenu';
      oParametro.iCodigo           = $('inputCodigo').value;
      oParametro.sDescricao        = encodeURIComponent( tagString( $('inputDescricao').value ) );
      oParametro.sAjuda            = encodeURIComponent( tagString( $('inputAjuda').value ) );
      oParametro.sFuncao           = encodeURIComponent( tagString( $('inputFuncao').value ) );
      oParametro.sDescricaoTecnica = encodeURIComponent( tagString( $('textAreaDescricaoTecnica').value ) );
      oParametro.sLiberadoCliente  = encodeURIComponent( tagString( $('selectLiberadoCliente').value ) );
      oParametro.sManutencao       = $('inputManutencao').value;
      oParametro.iItemAtivo        = $('inputItemAtivo').value;
      oParametro.lModulo           = lModulo;

  /**
   * Caso tenha sido marcado como módulo, passamos os demais dados por parâmetro
   */
  if ( lModulo ) {

    oParametro.iItemAtivo       = 2;
    oParametro.sNomeModulo      = encodeURIComponent( tagString( $('inputNomeModulo').value ) );
    oParametro.sDescricaoModulo = encodeURIComponent( tagString( $('inputDescricaoModulo').value ) );
    oParametro.sImagemModulo    = encodeURIComponent( tagString( $('inputImagemModulo').value ) );
  }
  
  oDados.fRetorno  = retornoSalvaItemMenu;
  oDados.sMensagem = _M( sCaminhoMensagem + "salvando_informacoes" );
  requisicaoRpc( oParametro, oDados );
}

/**
 * Retorno da chamada para salvar o item do menu
 */
function retornoSalvaItemMenu( oResposta ) {

  js_removeObj( "msgBox" );
  
  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( oRetorno.iStatus == 1 ) {

    $('inputCodigo').value = oRetorno.iMenu;
    alteraSituacaoTela();
  }
}

/**
 * Exclui um item de menu
 */
function excluiItemMenu() {

  if ( !confirm( _M( sCaminhoMensagem + "confirma_exclusao" ) ) ) {
    return;
  }

  var oParametro           = new Object();
      oParametro.sExecucao = 'excluirItemMenu';
      oParametro.iCodigo   = $('inputCodigo').value;

  oDados.fRetorno  = retornoExcluiItemMenu;
  oDados.sMensagem = _M( sCaminhoMensagem + "excluindo_item" );
  requisicaoRpc( oParametro, oDados );
}

/**
 * Retorno da exclusão do item
 */
function retornoExcluiItemMenu( oResposta ) {

  js_removeObj( "msgBox" );

  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  alert( oRetorno.sMensagem.urlDecode() );
  
  if ( oRetorno.iStatus == 1 ) {
    limpaFormulario();
  }
}

/**
 * Busca os dados de um item de menu selecionado
 * @param integer iCodigo - Código do item de menu a ser pesquisado
 */
function buscaItemMenu( iCodigo ) {
  
  var oParametro           = new Object();
      oParametro.sExecucao = 'buscaItemMenu';
      oParametro.iCodigo   = iCodigo;
  
  oDados.fRetorno  = retornoBuscaItemMenu;
  oDados.sMensagem = _M( sCaminhoMensagem + "buscando_dados_item" );
  requisicaoRpc( oParametro, oDados );
}

/**
 * Retorno da busca dos dados do item de menu
 */
function retornoBuscaItemMenu( oResposta ) {

  js_removeObj( "msgBox" );
  limpaFormulario();
  
  var oRetorno = eval( '(' + oResposta.responseText + ')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  $('inputCodigo').value              = oRetorno.iCodigo;
  $('inputDescricao').value           = oRetorno.sDescricao.urlDecode();
  $('inputAjuda').value               = oRetorno.mAjuda.urlDecode();
  $('inputFuncao').value              = oRetorno.sFuncao.urlDecode();
  $('textAreaDescricaoTecnica').value = oRetorno.mDescricaoTecnica.urlDecode();
  $('selectLiberadoCliente').value    = oRetorno.sLiberadoCliente.urlDecode();
  $('inputItemAtivo').value           = oRetorno.iItemAtivo;
  $('inputManutencao').value          = oRetorno.sManutencao.urlDecode();

  if ( oRetorno.aMenusVinculados.length == 1 ) {
    $('inputModuloVinculo').value = oRetorno.aMenusVinculados[0].iModulo;
  }

  buscaModulo();
}

/**
 * Mostra os dados retornados da lookup. Em seguida, verifica se o menu é um módulo
 */
function mostraItens() {

  $('inputCodigo').value = arguments[0];
  buscaItemMenu( $('inputCodigo').value );

  buscaModulo();
  db_iframe.hide();
}


/**
 * *******************************************************************************************
 * ********************************* DEMAIS FUNÇÕES ******************************************
 * *******************************************************************************************
 */
/**
 * Trata os campos do toogle de acordo com ele estar expandido ou oculto
 */
function tratamentoToogle() {

  var sCor            = '#DEB887';
  var lSomenteLeitura = true;
  lModulo             = false;
  
  if ( oToogleModulo.isDisplayed() ) {

    sCor            = '#FFFFFF';
    lSomenteLeitura = false;
    lModulo         = true;
  }
  
  $('inputNomeModulo').readOnly              = lSomenteLeitura;
  $('inputNomeModulo').style.backgroundColor = sCor;

  $('inputDescricaoModulo').readOnly              = lSomenteLeitura;
  $('inputDescricaoModulo').style.backgroundColor = sCor;

  $('inputImagemModulo').readOnly              = lSomenteLeitura;
  $('inputImagemModulo').style.backgroundColor = sCor;
}

/**
 * Valida se todos os campos obrigatórios foram preenchidos antes de enviar ao RPC
 */
function validaPreenchimentoCampos() {

  if ( empty( $('inputDescricao').value ) ) {

    alert( _M( sCaminhoMensagem + "informar_descricao" ) );
    return false;
  }

  if ( empty( $('inputAjuda').value ) ) {

    alert( _M( sCaminhoMensagem + "informar_ajuda" ) );
    return false;
  }

  if ( empty( $('textAreaDescricaoTecnica').value ) ) {

    alert( _M( sCaminhoMensagem + "informar_descricao_tecnica" ) );
    return false;
  }

  /**
   * Caso tenha sido selecionado módulo, valida os campos apresentados
   */
  if ( lModulo ) {

    if ( empty( $('inputNomeModulo').value ) ) {

      alert( _M( sCaminhoMensagem + "informar_nome_modulo" ) );
      return false;
    }

    if ( empty( $('inputDescricaoModulo').value ) ) {

      alert( _M( sCaminhoMensagem + "informar_descricao_modulo" ) );
      return false;
    }
  }

  salvaItemMenu();
}

/**
 * Limpa os campos existentes no formulário
 */
function limpaFormulario() {

  $('inputCodigo').value              = '';
  $('inputDescricao').value           = '';
  $('inputAjuda').value               = '';
  $('inputFuncao').value              = '';
  $('textAreaDescricaoTecnica').value = '';
  $('selectLiberadoCliente').value    = 'f';
  $('inputItemAtivo').value           = 1;
  $('inputManutencao').value          = '1';
  $('inputModuloVinculo').value       = '';

  $('btnIncluirAlterar').value = 'Incluir';
  $('btnExcluir').disabled     = true;
  
  limpaCamposModulo();
}

/**
 * Limpa os campos referente ao módulo
 */
function limpaCamposModulo() {

  oToogleModulo.show( false );
  $('inputNomeModulo').value          = '';
  $('inputDescricaoModulo').value     = '';
  $('inputImagemModulo').value        = '';
}

/**
 * Recarrega a página, passando como parâmetro o id_item do menu selecionado
 * MANTIDA LÓGICA DO PROGRAMA ANTIGO
 */
function js_pesquisaitemcad( iCodigo ) {
  
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?iCodigo=" + iCodigo;
}

/**
 * Realiza a requisição ao RPC e chama a função de retorno
 * @param Object oParametro - Parametros a serem enviados ao RPC
 * @param Object oDados     - Possui 2 parâmetro necessário para requisição:
 *               .......... fRetorno  - Função de retorno
 *               .......... sMensagem - Mensagem apresentada enquato retorna os dados
 */
function requisicaoRpc( oParametro, oDados ) {

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = oDados.fRetorno;

  js_divCarregando( oDados.sMensagem, "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * - Altera o valor do botão Incluir/Alterar
 * - Libera o Excluir 
 * - Libera a aba de Organização
 * - Instancia DbViewMenus
 */
function alteraSituacaoTela() {

  $('btnIncluirAlterar').value   = 'Alterar';
  $('btnExcluir').disabled       = false;
  oAbaOrganizacaoMenu.lBloqueada = false;

  instanciaViewMenus();
}

/**
 * Cria a instancia de DBViewMenus
 */
function instanciaViewMenus() {

  oViewMenus = new DBViewMenus( $('ctnOrganizacaoMenu') );
  oViewMenus.setItemMenu( $('inputCodigo').value );
  oViewMenus.setModuloVinculado( $('inputModuloVinculo').value );
  oViewMenus.show();
}


/**
 * ***************************************************************************************
 * ******************************** CRIAÇÃO DAS ABAS *************************************
 * *************************************************************************************** 
 */
var oDBAba                         = new DBAbas( $('ctnAbas') );
var oAbaCadastroMenu               = oDBAba.adicionarAba( 'Cadastro do Menu',    $('ctnCadastroMenu') );
var oAbaOrganizacaoMenu            = oDBAba.adicionarAba( 'Organização do Menu', $('ctnOrganizacaoMenu') );
    oAbaOrganizacaoMenu.lBloqueada = true;
</script>