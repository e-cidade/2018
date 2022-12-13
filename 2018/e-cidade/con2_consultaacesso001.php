<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$oRotulo  = new rotulocampo;
$oRotulo->label('id_usuario');
$oRotulo->label('nome');
$oRotulo->label('id_item');
$oRotulo->label('descricao');
$oRotulo->label('login');

$dia_inicial = "";
$mes_inicial = "";
$ano_inicial = "";

$dia_final   = "";
$mes_final   = "";
$ano_final   = "";

$sDBHintMetadata  = "db-action='hint' db-hint-text='O filtro de período deve estar dentro do mesmo ano.'";

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("dates.js");
      db_app::load("prototype.js");
      db_app::load("windowAux.widget.js");
      db_app::load("dbmessageBoard.widget.js");
      db_app::load("datagrid.widget.js");
      db_app::load("grid.style.css");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("DBTreeView.widget.js");
      db_app::load("DBHint.widget.js");
      ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <form method="post" action="" class="container">
      <fieldset>
        <legend>Consulta Acesso Clientes:</legend>
        <table class="form-container">
          <TR>
            <td class="bold">Período Inicial:</td>
            <td>
              <?php
               db_inputdata("data_inicial", $dia_inicial, $mes_inicial, $ano_inicial, true, "text", 1, $sDBHintMetadata);
              ?>
            </td>
            <td>
              <label class="bold">Horário:</label>
              <?php
               $hora_inicial = "00:00";
               db_input("hora_inicial", 5, "", true, "text",1,"onChange='js_validaHora(this)'");
              ?>
            </td>
          </TR>

          <TR>
            <td class="bold">Período Final:</td>
            <td>
              <?php
               db_inputdata("data_final", $dia_final, $mes_final, $ano_final, true, "text", 1, $sDBHintMetadata);
              ?>
            </td>
            <td>
              <label class="bold">Horário:</label>
              <?php
               $hora_final = "23:59";
               db_input("hora_final", 5, "", true, "text",1,"onChange='js_validaHora(this)'");
              ?>
            </td>
          </TR>

          <TR>
            <td>
             <? db_ancora ( $Lnome, "js_pesquisaUsuarios(true)", 1 )?>
            </td>
            <td colspan="2">
            <?php
              db_input ( "id_usuario", 10, $Iid_usuario, true, "text", 1, " onchange='js_pesquisaUsuarios(false);' " );
              db_input ( "nome", 41, $Inome, true, "text", 3 );
              db_input ( "login", 41, $Ilogin, true, "hidden", 3 );
            ?>
            </td>
          </TR>

          <tr>
            <td>
              <label class="bold">Módulo:</label>
            </td>
            <td id="modulos" colspan="2"></td>
          </tr>

          <TR>
            <td>
             <? db_ancora ("<b>Item de Menu:</b>", "js_pesquisaMenus(true)", 1 )?>
            </td>
            <td colspan="2">
            <?php
              db_input ( "id_item", 10, $Iid_item, true, "text", 1, " onchange='js_pesquisaMenus(false);' " );
              db_input ( "descricao", 41, $Idescricao, true, "text", 3 );
            ?>
            </td>
          </TR>

          <TR>
            <td class="bold">Tipo de Acesso:</td>
            <td colspan="2">
             <?php

               $aTipoAcesso = array( 0 => "Todas", 1 => "Apenas Acesso a Rotina", 2 => "Acesso a Rotina com Modificações no Sistema" );
               db_select("tipo_acesso", $aTipoAcesso, true,1, "onChange='js_validaTipo();'");
             ?>
            </td>
          </TR>

          <tr>
            <td id="pesquisa_avancada" colspan="3">

              <fieldset class="separator">
                <legend>Pesquisa Avançada ( Somente modificações )</legend>

                <table class="subtable">
                  <tr>
                    <td>
                      <label class="bold">Esquema:</label>
                    </td>
                    <td id="esquema" colspan="2"></td>
                  </tr>

                  <tr>
                    <td>
                      <label class="bold">Tabela:</label>
                    </td>
                    <td id="tabelas" colspan="2"></td>
                  </tr>

                  <tr>
                    <td>
                      <label class="bold">Campo:</label>
                    </td>
                    <td id="campos" colspan="2"></td>
                  </tr>

                  <tr id="linhaValor" style="display: none;">
                    <td>
                      <label class="bold">Valor:</label>
                    </td>
                    <td id="valor" colspan="2"></td>
                  </tr>
                </table>
              </fieldset>

            </td>
          </tr>

        </table>

      </fieldset>
      <input id="reemissao" type="button"  value="Pesquisar"  onclick="js_buscaAcessos();">
    </form>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script>

  var oHintOptions = {
    showEvents: ["onMouseover"],
    hideEvents: ["onMouseout"]
  };

(function() {


  DBHint.build($('data_inicial'), oHintOptions);
  DBHint.build($('data_final')  , oHintOptions);

  $('data_inicial').observe('data:change', function() {

    var dDataInicial = Date.convertFrom(this.value, 'd/m/Y'),
        dDataFinal   = Date.convertFrom($F('data_final'), 'd/m/Y');

    if ( dDataInicial.getFullYear() != dDataFinal.getFullYear() ) {
      alert("O filtro de período deve estar dentro do mesmo ano.");
      $('data_final').value = '';
    }

  })

  $('data_inicial').observe("change", function() {
    this.fire("data:change")
  });

  $('data_final').observe("change", function() {
    this.fire("data:change")
  });

  $('data_final').observe('data:change', function() {

    var dDataFinal   = Date.convertFrom(this.value, 'd/m/Y'),
        dDataInicial = Date.convertFrom($F('data_inicial'), 'd/m/Y');

    if ( dDataInicial.getFullYear() != dDataFinal.getFullYear() ) {
      alert("O filtro de período deve estar dentro do mesmo ano.");
      $('data_final').value = '';
    }

    this.focus();
  });


})();

/**
 * Scripts básicos para o Formulário
 */
function js_validaHora(oElemento) {

 try {

   var sHoraAtual = oElemento.getValue();

   if ( sHoraAtual == "" ) {
     $('hora_inicial').setValue("00:00");
     sHoraAtual = "00:00";
   }

   var aHora = sHoraAtual.split(":");

   if ( new Number(aHora[0]) > 23 ) {
     throw "Hora não pode ser maior que 23";
   }
   if ( new Number(aHora[1]) > 59 ) {
     throw "Minuto não pode ser maior que 59";
   }
 } catch (sErro) {

   alert ("Hora Incorreto: " + sErro);
   oElemento.focus();
   return;
 }
}

function js_pesquisaUsuarios(lMostra){

 if (lMostra) {
    js_OpenJanelaIframe('',
                        'db_iframe_db_usuarios',
                        'func_db_usuarios.php?funcao_js=parent.js_mostraUsuarioLookUp|nome|id_usuario|login',
                        'Pesquisa Usuários',
                        true);
 } else {

   if ( $F('id_usuario') != '' ) {
      js_OpenJanelaIframe('',
                          'db_iframe_db_usuarios',
                          'func_db_usuarios.php?pesquisa_chave='+$F('id_usuario')+
                          '&login&funcao_js=parent.js_mostraUsuarioDigitacao',
                          'Pesquisa',
                          false);
   } else {

     $('id_usuario').value = '';
     $('nome').value       = '';
     $('login').value      = '';
   }
 }
}

function js_mostraUsuarioDigitacao( sNome, lErro){

  if ( arguments[1] === false ) {

    $('id_usuario').value = '';
    $('login').value      = '';
  } else {
    $('login').value      = arguments[1];
  }
  $('nome').value       = arguments[0];
}

function js_mostraUsuarioLookUp( sNome, iIdUsuario ) {

  $('id_usuario').value = arguments[1];
  $('nome').value       = arguments[0];
  $('login').value      = arguments[2];

  db_iframe_db_usuarios.hide();
}

function js_pesquisaMenus(lMostra) {

  var sParametros = "lAcount=true";

  if ( !empty( oSelectModulos.value ) ) {
    sParametros += "&iModulo=" + oSelectModulos.value;
  }

  if (lMostra) {

    js_OpenJanelaIframe('',
                        'db_iframe',
                        'func_db_itensmenu.php?' + sParametros + '&funcao_js=parent.js_mostraMenusLookup|id_item|dl_caminho',
                        'Pesquisa Usuários',
                        true);

  } else {

    if ( $F('id_item') != '' ) {
    js_OpenJanelaIframe('',
            'db_iframe',
            'func_db_itensmenu.php?' + sParametros + '&pesquisa_chave='+$F('id_item')+
            '&funcao_js=parent.js_mostraMenusDigitacao',
            'Pesquisa',
            false);
    } else {
      $('id_item').setValue('');
    }
  }
}

function js_mostraMenusLookup(iItemMenu, sCaminho) {

  $('descricao').value = sCaminho;
  $('id_item').value   = iItemMenu;
  db_iframe.hide();
}

function js_mostraMenusDigitacao(sCaminho, lErro) {

  if ( lErro == true ) {

    $('id_item').focus();
    $('id_item').value = '';
    return;
  }
  $('descricao').setValue(sCaminho);
}

/****
 * Comportamento da Rotina
 */
var sPathRPC          = "con2_consultaacesso.RPC.php";
var sCaminhoMensagens = "configuracao.configuracao.con2_consultaacesso001";
var iTabela           = 0;

function js_buscaAcessos() {


  if ( $F('data_inicial') == "" ) {
    alert("A data Inicial deve ser indicada");
    return;
  }
  if ( $F('data_final') == "" ) {
    alert("A data Final deve ser indicada");
    return;
  }

  $('data_final').fire("data:change");

  if ( $F('data_final') == "" ) {
    return;
  }

  /**
   * Validação da pesquisa avançada
   */
  if ( $F('tipo_acesso') == 2 && $F('selectEsquemas') == 0 ) {
    alert("A Pesquisa Avançada deve ser informada.");
    return;
  }

  if ( $F('selectEsquemas') != 0 && $F('selectTabelas') == 0 ) {
    alert("Ao selecionar o campo Esquema, os campos Tabela e Campo devem ser preenchidos.");
    return;
  }

  if ( $F('selectTabelas') != 0 && $F('selectCampos') == 0 ) {
    alert("Ao selecionar o campo Tabela, o campo Campo deve ser preenchido.");
    return;
  }

  if ( $F('selectCampos') != 0 && $F('inputValor') == '' ) {
    alert("Ao selecionar o campo Campo, o campo Valor deve ser preenchido.");
    return;
  }

  var dDataInicial = Date.convertFrom($F('data_inicial'), 'd/m/Y'),
      dDataFinal   = Date.convertFrom($F('data_final')  , 'd/m/Y'),
      lCienteMensagens = false;

  if ( somaDataDiaMesAno(dDataInicial, 0, 1, 0).getTime() < dDataFinal.getTime() ) {

    if ( confirm('O período que você informou é maior de que 1 mês.\nO servidor poderá ficar lento por causa desta operação.') ) {

      /*if ( confirm( 'O servidor poderá ficar lento por causa desta operação.\nPressione OK para abortar a pesquisa.' ) ) {
        return;
      }*/

    } else {
      return;
    }

    lCienteMensagens = true;
  }

  var sMensagemProcessamento  = "Pesquisando\nFavor Aguarde ...";

  var iIndexEsquema = oSelectEsquemas.selectedIndex;
  var iIndexTabela  = oSelectTabelas.selectedIndex;
  var iIndexCampo   = oSelectCampos.selectedIndex;
  iTabela           = oSelectTabelas.value;

  var oParametros                  = new Object();
      oParametros.sExecucao        = 'getAcessos';
      oParametros.sUsuario         = $F('login');
      oParametros.iUsuario         = $F('id_usuario');
      oParametros.iItemMenu        = $F('id_item');
      oParametros.dDataInicio      = js_formatar($F('data_inicial'),"d");
      oParametros.dDataFim         = js_formatar($F('data_final'),   "d");
      oParametros.sHoraInicio      = $F('hora_inicial');
      oParametros.sHoraFim         = $F('hora_final');
      oParametros.iModulo          = oSelectModulos.value;
      oParametros.iTipoAcesso      = $F('tipo_acesso');
      oParametros.sEsquema         = encodeURIComponent( tagString( oSelectEsquemas.options[iIndexEsquema].getAttribute( "label" ) ) );
      oParametros.sTabela          = encodeURIComponent( tagString( oSelectTabelas.options[iIndexTabela].getAttribute( "label" ) ) );
      oParametros.sCampo           = encodeURIComponent( tagString( oSelectCampos.options[iIndexCampo].getAttribute( "label" ) ) );
      oParametros.mValor           = encodeURIComponent( tagString( oInputValor.value ) );
      oParametros.lCienteMensagens = lCienteMensagens;

  js_divCarregando(sMensagemProcessamento,'msgBox');

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = "post";
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = js_retornoAcessos;
      oDadosRequisicao.asynchronous = false;


  new Ajax.Request(sPathRPC, oDadosRequisicao);
}

/**
 * Cria janela com grid de Acessos
 */
function js_montaJanelaAcessos() {

  /**
   * Criando Window Principal
   */
  oWindowAcessos        = new windowAux('windowAcessos',
                                        'Acessos',
                                        document.body.clientWidth - 10,
                                        document.body.clientheight);

  var sConteudoWindow   = " <center id='headerAcessos' />                                                           \n";
  sConteudoWindow      += " <div id='contentAcessos' >                                                              \n";
  sConteudoWindow      += "   <fieldset>                                                                            \n";
  sConteudoWindow      += "     <legend><b>Acessos:</b></legend>                                                    \n";
  sConteudoWindow      += "     <div id='ctnGridAcessos' />                                                         \n";
  sConteudoWindow      += "   </fieldset>                                                                           \n";
  sConteudoWindow      += " </div>                                                                                  \n";
  sConteudoWindow      += " <div id='footerAcessos'>                                                                \n";
  sConteudoWindow      += "   <center>                                                                              \n";
  sConteudoWindow      += "     <input type='button' value='Fechar' onClick='oWindowAcessos.destroy();'/>           \n";
  sConteudoWindow      += "   <center>                                                                              \n";
  sConteudoWindow      += " </div>                                                                                  \n";

  oWindowAcessos.setContent(sConteudoWindow);
  oWindowAcessos.setShutDownFunction( function () {
    oWindowAcessos.destroy();
  });
  oWindowAcessos.show(20,0);

  /**
   * Criando Message Board
   */
  oMessageBoardAcessos  = new DBMessageBoard("messageBoardAcessoss",
                                             "Acessos ao Sistema",
                                             "Listados abaixo os acessos do sistema agrupados pelo item de menu",
                                             $('headerAcessos'));
  oMessageBoardAcessos.show();

  /**
   * Criando Grid com os Registros
   */

  oGridAcessos              = new DBGrid('gridAcessos');
  oGridAcessos.nameInstance = 'oGridAcessos';
  oGridAcessos.sName        = 'oGridAcessos';
  oGridAcessos.setHeight(oWindowAcessos.getHeight() - 200);
  oGridAcessos.setHeader(["Usuario", "IP", "HorarioAcesso","Modificações"]);
  oGridAcessos.aWidths      = new Array('30%','20%','30%','20%');
  oGridAcessos.setCellAlign(new Array("left","center","center", "center"));
  oGridAcessos.show($('ctnGridAcessos'));

  oGridAcessos.clearAll(true);
}

/**
 * Busca os dados de Acesso, cria janela  e preenche a grid com os dados
 * @returns void
 */
function js_retornoAcessos( oAjax ) {

  js_removeObj('msgBox');

  var oRespostaRequisicao = eval( "("+oAjax.responseText+")" );

  if ( oRespostaRequisicao.iStatus == 2 ) {

    alert( 'Erro ao Buscar os Dados: ' + oRespostaRequisicao.sMessage.urlDecode() );
    return;
  }

  var aAcessos      = oRespostaRequisicao.aAcessos;
  var iCounterLinha = 0;

  if ( aAcessos.length == 0 ) {

    alert( "Não foram encontrados acessos de acordo com os filtros selecionados." );
    return false;
  }

  js_montaJanelaAcessos();

  /**
   * Cria Item de Menus e seu detalhes
   */
  for ( var iIndiceCabecalhos = 0; iIndiceCabecalhos < aAcessos.length; iIndiceCabecalhos++) {

    oCabecalhoAcesso = aAcessos[iIndiceCabecalhos];

    var sCabecalho  = "<b><span style='width:100px;'>" + oCabecalhoAcesso.id_item + "</span> ";
    sCabecalho     += " - "  + oCabecalhoAcesso.path_menu.urlDecode() + "</b><em>(" + oCabecalhoAcesso.arquivo.urlDecode() + ")</em>";

    oGridAcessos.addRow([sCabecalho]);
    oGridAcessos.aRows[iCounterLinha].aCells[0].setUseColspan(true, 4);
    oGridAcessos.aRows[iCounterLinha].aCells[0].sStyle  = "text-align:left; background-color: #dddddd; ";
    iCounterLinha++;

    /**
     * Criando detalhes de Cada Item
     */
    for (var iIndiceDetalhe = 0; iIndiceDetalhe < oCabecalhoAcesso.aDetalhesAcesso.length; iIndiceDetalhe++) {

      oDetalhe = oCabecalhoAcesso.aDetalhesAcesso[iIndiceDetalhe];

      var sHora = oDetalhe.hora.urlDecode();
      var sData = oDetalhe.data.urlDecode();
      var aCelulas     = new Array();
      aCelulas[0]      = "<B>" + oDetalhe.nome.urlDecode() + "</b>(" + oDetalhe.login.urlDecode() + ")";
      aCelulas[1]      = oDetalhe.ip;
      aCelulas[2]      = js_formatar(sData, "d") + " - " + sHora;
      aCelulas[3]      = "";

      if ( oDetalhe.modificacoes == 't' ) {

        var sChamadaFuncao  = "js_mostraAcount(" + oDetalhe.codsequen + ",'" + sData + "', '" + sHora + "',";
            sChamadaFuncao +=  "'" + oDetalhe.login + "','" + oDetalhe.instit + "')";
        aCelulas[3]        = "<a href=\"javascript:" + sChamadaFuncao + "\">CONSULTAR</a>";
      }

      oGridAcessos.addRow(aCelulas);
      oGridAcessos.aRows[iCounterLinha].aCells[0].sStyle = "padding-left: 10;";
      iCounterLinha++;
    }
  }
  oGridAcessos.renderRows();
}

function js_mostraAcount(iCodigoAcesso, dDataAcesso, sHoraAcesso, sUsuario, iInstituicao) {

  if ( iCodigoAcesso == null || dDataAcesso == null || sHoraAcesso == null ) {
    return false;
  }

  /**
   * Executa Requisição das modificações no sistema pelo acesso
   */
  var sMensagemProcessamento    = "Pesquisando Modificações<BR>Favor Aguarde ...";
  var oParametros               = new Object();
  oParametros.sExecucao         = 'getModificacoes';
  oParametros.iCodigoAcesso     = iCodigoAcesso;
  oParametros.dDataAcesso       = dDataAcesso;
  oParametros.sHoraAcesso       = sHoraAcesso;

  var iIndexEsquema = oSelectEsquemas.selectedIndex;
  var iIndexTabela  = oSelectTabelas.selectedIndex;
  var iIndexCampo   = oSelectCampos.selectedIndex;

  oParametros.sEsquema     = encodeURIComponent(tagString(oSelectEsquemas.options[iIndexEsquema].getAttribute("label")));
  oParametros.sTabela      = encodeURIComponent(tagString(oSelectTabelas.options[iIndexTabela].getAttribute("label")));
  oParametros.sCampo       = encodeURIComponent(tagString(oSelectCampos.options[iIndexCampo].getAttribute("label")));
  oParametros.mValor       = encodeURIComponent(tagString(oInputValor.value));
  oParametros.sUsuario     = sUsuario;
  oParametros.iInstituicao = iInstituicao;

  var oDadosRequisicao          = new Object();
  oDadosRequisicao.method       = "post";
  oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
  oDadosRequisicao.onComplete   = js_ProcessaRespostaAcount;
  oDadosRequisicao.asynchronous = false;

  js_divCarregando(sMensagemProcessamento,'msgBox');
  new Ajax.Request(sPathRPC, oDadosRequisicao);
}

function js_ProcessaRespostaAcount(oRetornoAjax) {

  js_removeObj('msgBox');
  var oResposta = eval("("+oRetornoAjax.responseText+")");

  /**
   * Define os Dados a Serem manipulados pela janela
   */
  DadosGridAcount.setDados(oResposta.aRegistros);

  if ( oResposta.iStatus == 2 ) {

    alert( oResposta.sMessage.urlDecode() );
    return;
  }
  js_criaJanelaAcount();

  for (var i in oResposta.aTabelas) {

    var oTabela = oResposta.aTabelas[i];
    var sValor  = (oTabela.codigo_tabela + " - " + oTabela.rotulo_tabela + "(" + oTabela.nome_tabela + ")").urlDecode();
    oComboTabelas.addItem(oTabela.codigo_tabela, sValor);

    if ( oTabela.codigo_tabela == iTabela ) {
      oComboTabelas.setValue( iTabela );
    }
  };

  /**
   * Popula a variável global com array das movimentações
   */
   js_carregaDadosGridModificacoes();
}

function js_criaJanelaAcount(){

  oWindowAcount        = new windowAux('windowAcount',
                                        'Acount',
                                        ($('windowwindowAcessos_content').clientWidth - 20),
                                        ($('windowwindowAcessos_content').clientHeight - 20));
  oWindowAcount.setChildOf(oWindowAcessos);

  var sConteudoWindow   = " <center id='headerAcount' />                                                           \n";
  sConteudoWindow      += " <div id='contentAcount' >                                                              \n";
  sConteudoWindow      += "   <fieldset>                                                                           \n";
  sConteudoWindow      += "     <legend><b>Acount:</b></legend>                                                    \n";
  sConteudoWindow      += "     <div style='text-align:left; padding-bottom:5px;'>                                 \n";
  sConteudoWindow      += "       <span>                                                                           \n";
  sConteudoWindow      += "         <b>Tabela</b><em>(Arquivo)</em>:                                               \n";
  sConteudoWindow      += "       </span>                                                                          \n";
  sConteudoWindow      += "       <span id='ctnComboTabela'></span>                                                \n";
  sConteudoWindow      += "     </div>                                                                             \n";
  sConteudoWindow      += "     <div id='ctnGridAcount'></div>                                                     \n";
  sConteudoWindow      += "   </fieldset>                                                                          \n";
  sConteudoWindow      += " </div>                                                                                 \n";
  sConteudoWindow      += " <div id='footerAcount'>                                                                \n";
  sConteudoWindow      += "   <center>                                                                             \n";
  sConteudoWindow      += "     <input type='button' value='Fechar' onClick='oWindowAcount.destroy();'/>           \n";
  sConteudoWindow      += "   <center>                                                                             \n";
  sConteudoWindow      += " </div>                                                                                 \n";

  oWindowAcount.setContent(sConteudoWindow);
  oWindowAcount.setShutDownFunction( function () {
    oWindowAcount.destroy();
  });
  oWindowAcount.show(10,10);

  /**
   * Criando Message Board
   */
  var sConteudoHeader  = "";
  oMessageBoardAcount  = new DBMessageBoard("messageBoardAcounts",
                                             "Acount do Sistema",
                                             sConteudoHeader,
                                             $('headerAcount'));
  oMessageBoardAcount.show();

  /**
   * Cria ComboBox com as Tabelas Afetadas
   */
  oComboTabelas  = new DBComboBox("cboTabelas", "oComboTabelas", new Array(), 400, 1);
  oComboTabelas.addEvent("onChange","js_carregaDadosGridModificacoes()");
  oComboTabelas.addItem("0","Todas...");
  oComboTabelas.show($('ctnComboTabela'));

  /**
   * Cria datagrid com os acessos
   */
  oGridAcount              = new DBGrid('gridAcount');
  oGridAcount.nameInstance = 'oGridAcount';
  oGridAcount.sName        = 'oGridAcount';
  oGridAcount.allowSelectColumns(true);
  oGridAcount.setHeight(oWindowAcount.getHeight() - 250);
  oGridAcount.setHeader(["Tabela", "Label", "Campo", "Tipo Modifcação", "Valor Anterior", "Valor Posterior"]);
  oGridAcount.aWidths      = ['10%', '5%','20%','20%','20%', "25%"];
  oGridAcount.setCellAlign(new Array("left", "left", "left", "left", "left", "left"));
  oGridAcount.show($('ctnGridAcount'));
}

function js_carregaDadosGridModificacoes() {

  oGridAcount.clearAll(true);
  for ( var iIndiceMovimentacoes = 0; iIndiceMovimentacoes < DadosGridAcount.getQuantidade(); iIndiceMovimentacoes++ ) {

    oMovimentacao    = DadosGridAcount.getDados()[iIndiceMovimentacoes];

    if ( ( oComboTabelas.getValue() !=0 ) && ( oComboTabelas.getValue() != oMovimentacao.codigo_tabela ) ) {
      continue;
    }

    var sTipoMovimentacao = "";

    switch ( oMovimentacao.tipo_alteracao ) {

      case 'I':
        sTipoMovimentacao = "Inclusão";
      break;

      case 'U':
        sTipoMovimentacao = "Alteração";
      break;

      case 'D':
        sTipoMovimentacao = "Exclusão";
      break;

      default:
        sTipoMovimentacao = "";
      break;
    }

    var aCelulas     = new Array();

    aCelulas[0]      = oMovimentacao.nome_tabela.urlDecode();
    aCelulas[1]      = oMovimentacao.rotulo_campo.urlDecode();
    aCelulas[2]      = oMovimentacao.nome_campo;
    aCelulas[3]      = sTipoMovimentacao;
    aCelulas[4]      = oMovimentacao.valor_antigo.urlDecode();
    aCelulas[5]      = oMovimentacao.valor_novo.urlDecode();

    oGridAcount.addRow(aCelulas);
  }
  oGridAcount.renderRows();
}

DadosGridAcount = function () {

  aDados         = [];
  this.setDados  = function (aDadosSetados) {
    aDados = aDadosSetados;
  },

  this.getDados = function () {
    return aDados;
  },

  this.getQuantidade = function() {
    return aDados.length;
  };
};
DadosGridAcount = new DadosGridAcount();

/**
 * Seta a classe para tratamento do tamanho dos campos
 */
$('data_inicial').className = 'field-size2';
$('data_final').className   = 'field-size2';
$('id_usuario').className   = 'field-size2';
$('nome').className         = 'field-size7';
$('id_item').className      = 'field-size2';
$('descricao').className    = 'field-size7';
$('tipo_acesso').setAttribute( "rel", "ignore-css" );
$('tipo_acesso').className  = 'field-size9';

/*
 ******************************************************
 ********* ELEMENTO E PESQUISA DOS MÓDULOS ************
 ******************************************************
 */
var oSelectModulos    = document.createElement( 'select' );
    oSelectModulos.id = 'selectModulos';
    oSelectModulos.add( new Option( "Nenhuma opção", 0 ) );
    oSelectModulos.setAttribute( "rel", "ignore-css" );
    oSelectModulos.className = 'field-size9';

$('modulos').appendChild( oSelectModulos );

function pesquisaModulos() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getModulos';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaModulos;

  js_divCarregando( _M( sCaminhoMensagens+'.pesquisando_modulos' ), "msgBox" );
  new Ajax.Request( sPathRPC, oDadosRequisicao );
}

function retornoPesquisaModulos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus == 1 && oRetorno.aModulos.length > 0 ) {

    oRetorno.aModulos.each(function( oModulo, iSeq ) {
      oSelectModulos.add( new Option( oModulo.sNome.urlDecode(), oModulo.iCodigo ) );
    });
  }
}

pesquisaModulos();

/*
 ******************************************************
 ********* ELEMENTO E PESQUISA DOS ESQUEMAS ***********
 ******************************************************
 */
var oSelectEsquemas    = document.createElement( 'select' );
    oSelectEsquemas.id = 'selectEsquemas';
    oSelectEsquemas.add( new Option( "Nenhuma opção", 0 ) );
    oSelectEsquemas.setAttribute( "rel", "ignore-css" );
    oSelectEsquemas.className = 'field-size9';
    oSelectEsquemas.options[0].setAttribute( "label", "" );

$('esquema').appendChild( oSelectEsquemas );

$('selectEsquemas').observe("change", function() {
  pesquisaTabelas();
});

function pesquisaEsquemas() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getEsquemas';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaEsquemas;

  js_divCarregando( _M( sCaminhoMensagens+'.pesquisando_esquemas' ), "msgBox" );
  new Ajax.Request( sPathRPC, oDadosRequisicao );
}

function retornoPesquisaEsquemas( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus == 1 && oRetorno.aEsquemas.length > 0 ) {

    oRetorno.aEsquemas.each(function( oEsquema, iSeq ) {

      oSelectEsquemas.add( new Option( oEsquema.sNome.urlDecode(), oEsquema.iCodigo ) );
      oSelectEsquemas.options[iSeq + 1].setAttribute( "label", oEsquema.sNome.urlDecode() );
    });
  }
}

pesquisaEsquemas();

/*
 ******************************************************
 ********* ELEMENTO E PESQUISA DAS TABELAS ************
 ******************************************************
 */
var oSelectTabelas    = document.createElement( 'select' );
    oSelectTabelas.id = 'selectTabelas';
    oSelectTabelas.add( new Option( "Nenhuma opção", 0 ) );
    oSelectTabelas.setAttribute( "rel", "ignore-css" );
    oSelectTabelas.className = 'field-size9';
    oSelectTabelas.options[0].setAttribute( "label", "" );

$('tabelas').appendChild( oSelectTabelas );

$('selectTabelas').observe("change", function() {
  pesquisaCampos();
});

function pesquisaTabelas() {

  limpaSelect( oSelectTabelas );
  limpaSelect( oSelectCampos );
  $('linhaValor').style.display = 'none';

  var oParametro           = new Object();
      oParametro.sExecucao = 'getTabelasModulo';
      oParametro.iEsquema  = oSelectEsquemas.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaTabelas;

  js_divCarregando( _M( sCaminhoMensagens+'.pesquisando_tabelas' ), "msgBox" );
  new Ajax.Request( sPathRPC, oDadosRequisicao );
}

function retornoPesquisaTabelas( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus == 1 && oRetorno.aTabelas.length > 0 ) {

    oRetorno.aTabelas.each(function( oTabela, iSeq ) {

      oSelectTabelas.add( new Option( oTabela.sNome.urlDecode(), oTabela.iCodigo ) );
      oSelectTabelas.options[iSeq + 1].setAttribute( "label", oTabela.sLabel.urlDecode() );
    });
  }
}

/*
 ******************************************************
 ********* ELEMENTO E PESQUISA DOS CAMPOS *************
 ******************************************************
 */
var oSelectCampos       = document.createElement( 'select' );
    oSelectCampos.id    = 'selectCampos';
    oSelectCampos.label = '';
    oSelectCampos.add( new Option( "Nenhuma opção", 0 ) );
    oSelectCampos.setAttribute( "rel", "ignore-css" );
    oSelectCampos.className = 'field-size9';
    oSelectCampos.options[0].setAttribute( "label", "" );

$('campos').appendChild( oSelectCampos );

$('selectCampos').observe("change", function() {

  $('linhaValor').style.display = 'none';
  if ( oSelectCampos.value != 0 ) {
    $('linhaValor').style.display = '';
  }
});

function pesquisaCampos() {

  limpaSelect( oSelectCampos );
  $('linhaValor').style.display = 'none';

  var oParametro           = new Object();
      oParametro.sExecucao = 'getCamposTabela';
      oParametro.iTabela   = oSelectTabelas.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaCampos;

  js_divCarregando( _M( sCaminhoMensagens+'.pesquisando_campos' ), "msgBox" );
  new Ajax.Request( sPathRPC, oDadosRequisicao );
}

function retornoPesquisaCampos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  if ( oRetorno.iStatus == 1 && oRetorno.aCampos.length > 0 ) {

    oRetorno.aCampos.each(function( oCampo, iSeq ) {

      oSelectCampos.add( new Option( oCampo.sNome.urlDecode(), oCampo.iCodigo ) );
      oSelectCampos.options[iSeq + 1].setAttribute( "label", oCampo.sLabel.urlDecode() );
    });
  }
}

/**
 * Elemento input do valor a ser buscado
 */
var oInputValor           = document.createElement( 'input' );
    oInputValor.id        = 'inputValor';
    oInputValor.className = 'field-size2';
    oInputValor.value     = '';

$('valor').appendChild( oInputValor );

/**
 * Limpa o select do elemento passado como parâmetro
 */
function limpaSelect( oElemento ) {

  var iTamanho = oElemento.options.length;

  for ( var iContador = 0; iContador < iTamanho; iContador++ ) {
    oElemento.options.remove( iContador );
  }

  oElemento.add( new Option( "Nenhuma opção", 0 ) );
  oElemento.options[0].setAttribute( "label", "" );
  $('inputValor').value = '';
}

function js_validaTipo() {

  $('pesquisa_avancada').style.display = "";

  if ( $('tipo_acesso').value == 1 ) {

    oSelectEsquemas.value                = '0';
    oSelectTabelas.value                 = '0';
    oSelectCampos.value                  = '0';
    oInputValor.value                    = '';
    $('linhaValor').style.display        = 'none';
    $('pesquisa_avancada').style.display = "none";
  }
}

DBHint.build( oSelectEsquemas, oHintOptions).setText("Ao selecionar o campo Esquema, os campos Tabela e Campo devem ser preenchidos.");
DBHint.build( oSelectTabelas , oHintOptions).setText("Ao selecionar o campo Tabela, o campo Campo deve ser preenchido.");
DBHint.build( oSelectCampos  , oHintOptions).setText("Ao selecionar o campo Campo, o campo Valor deve ser preenchido.");


</script>
