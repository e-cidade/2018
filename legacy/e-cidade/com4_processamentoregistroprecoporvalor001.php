<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oDaoRotulo = new rotulocampo;
$oDaoRotulo->label("descrdepto");
$oDaoRotulo->label("pc50_descr");
$oDaoRotulo->label("pc54_solicita");
$oDaoRotulo->label("pc54_datainicio");
$oDaoRotulo->label("pc54_datatermino");
$oDaoRotulo->label("pc12_vlrap");
$oDaoRotulo->label("pc12_tipo");
$oDaoRotulo->label("o74_sequencial");
$oDaoRotulo->label("o74_descricao");
unset($_SESSION["oSolicita"]);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, grid.style.css");
  ?>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
    <fieldset>
      <legend>Compilação de Registro de Preço</legend>
      <fieldset class="separator">
        <legend>Dados da Compilação</legend>
        <table>
          <tr>
            <td>
              <label for="pc54_solicita'"></label>
              <?php
              db_ancora("<b>Código da Abertura:</b>", "js_pesquisaaberturaprecos(true);", 1);
              ?>
            </td>
            <td>
              <?php
              db_input('pc54_solicita', 10, $Ipc54_solicita, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label for="pc54_datainicio"><b>Data de Vigência:</b></label>
            </td>
            <td>
              <?php
              db_inputdata('pc54_datainicio',null,null,null,true,'text',1);
              ?>
              &nbsp;
              <label for="pc54_datatermino"><b>até</b></label>
             <?php
              db_inputdata('pc54_datatermino',null,null,null,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tpc10_resumo?>" colspan="4">
              <fieldset>
                <legend><label for="pc10_resumo">Resumo</label></legend>
              <?php
              db_textarea("pc10_resumo", 5, 80, "",true,"text", 1,"","","",735);
              ?>
            </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset class="separator">
        <legend>Itens</legend>
        <div id="ctnGridItensAbertura">
        </div>
      </fieldset>
    </fieldset>
    <input type='button' value='Salvar' id='btnSalvar'>
  </div>
  <?php
    db_menu( db_getsession("DB_id_usuario"),
             db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),
             db_getsession("DB_instit") );
  ?>
</body>
  <script>
    const MENSAGEM           = 'patrimonial.compras.com4_processamentoregistroprecoporvalor001.';

    var sUrlRC               = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
    var oGridItens           = new DBGrid('gridItens');
    oGridItens.sNameInstance = "oGridItens";

    oGridItens.setHeader(["Item", "Descrição", "Valor"]);
    oGridItens.setCellWidth(["10%","70%", "20%"]);
    oGridItens.setCellAlign(['center', 'left', 'right'])
    oGridItens.show($('ctnGridItensAbertura'));

    function js_pesquisaaberturaprecos() {

      js_OpenJanelaIframe( '',
                           'db_iframe_solicitaregistropreco',
                           'func_solicitaregistropreco.php?lFiltraInstituicao=true&funcao_js=parent.js_preenche|pc54_solicita'+
                           '&trazsemcompilacao=1&anuladas=1&formacontrole=2',
                           'Abertura de Registro de Preço',
                           true );
    }

    function js_preenche(solicita,reload) {

      if (reload == null) {
        reload = false;
      }

      /**
       * Validação adicionada para sabermos se a abertura de registro de preço possui estimativa criada
       */
      js_divCarregando(_M(MENSAGEM+"carregando_estimativas"), "msgBox");

      var oParam          = new Object();
      oParam.exec         = 'consAberturaDetalhes';
      oParam.detalhe      = 'estimativa';
      oParam.apenasativas = true;
      oParam.pc10_numero  = solicita;

      var oAjax = new Ajax.Request('com4_solicitacaoComprasRegistroPreco.RPC.php',
        {method: 'post',
          parameters: 'json='+Object.toJSON(oParam),
          onComplete: function(oAjax){

            js_removeObj("msgBox");
            var oRetorno = eval("("+oAjax.responseText+")");
            if (oRetorno.dados.length == 0) {

              alert(_M(MENSAGEM+"abertura_sem_estimativa",{"abertura":solicita})) ;
              $('pc54_solicita').value = '';
              $('btnSalvar').disabled = true;
              $('btnImprimir').disabled = true;

              return false;
            } else {

              $('btnSalvar').disabled   = false;
              db_iframe_solicitaregistropreco.hide();
              js_pesquisarItensAbertura(solicita)
            }
          }
        });
    }


    function js_pesquisarItensAbertura(solicita) {

      oGridItens.clearAll(true);
      $('pc54_solicita').value = solicita;

      js_divCarregando(_M(MENSAGEM+"carregando_estimativas"), "msgBox");
      var oParam = {exec: 'consAberturaDetalhes', detalhe: 'itens', "pc10_numero": solicita}
      new Ajax.Request('com4_solicitacaoComprasRegistroPreco.RPC.php', {
          method: 'post',
          parameters: 'json=' + Object.toJSON(oParam),
          onComplete: function (oAjax) {

            js_removeObj("msgBox");
            var oRetorno = eval("(" + oAjax.responseText + ")");
            $('pc10_resumo').value = oRetorno.resumo.urlDecode();

            var onKeyDown = new CustomEvent("onkeyup");
            $('pc10_resumo').onkeyup(onKeyDown);

            oRetorno.dados.each(function (oItem) {
              var aLinha = [
                 oItem.ordem,
                 oItem.material.urlDecode(),
                 js_formatar(oItem.valor_unitario, 'f')
              ];
              oGridItens.addRow(aLinha);
            });
            oGridItens.renderRows();
          }
         });
    }

    function js_salvarCompilacao() {

      /**
       * as Datas devem ser preenchidas.
       */
      if ($F('pc54_datainicio') == '') {

        alert(_M(MENSAGEM+'vigencia_inicial_nao_informada'));
        $('pc54_datainicio').focus();
        return false;

      }

      if ($F('pc54_datatermino') == '') {

        alert(_M(MENSAGEM+'vigencia_final_nao_informada'));
        $('pc54_datatermino').focus();
        return false;

      }

      /**
       * Valida data de inicio e termino - true quando data de termino é menor ou 'i' quando datas são iguais
       * @var mixed bool | string
       */
      var mDiferenca = js_diferenca_datas(js_formatar($F('pc54_datainicio'), 'd'), js_formatar($F('pc54_datatermino'), 'd'), 3);

      if (mDiferenca && mDiferenca != 'i') {

        alert(_M(MENSAGEM+'vigencia_invalida'));
        $('pc54_datatermino').focus();
        return false;
      }

      if ($F('pc54_solicita') == "") {

        alert(_M(MENSAGEM+'sem_abertura'));
        $('pc54_solicita').focus();
        return false;

      }
      js_divCarregando("Aguarde, salvando compilacao Registro de Preço.","msgBox");

      var oParam         = new Object();
      oParam.exec        = "salvarCompilacao";
      oParam.tipo        = 6;
      oParam.datainicio  = $F('pc54_datainicio');
      oParam.datatermino = $F('pc54_datatermino');
      oParam.liberado    = true;
      oParam.iAbertura   = $F('pc54_solicita');
      var sResumo        = tagString($F('pc10_resumo'));
      oParam.resumo      = encodeURIComponent(sResumo);
      var oAjax          = new Ajax.Request(sUrlRC,
        {
          method: "post",
          parameters:'json='+Object.toJSON(oParam),
          onComplete: function (oAjax) {

            js_removeObj("msgBox");
            var oRetorno = eval("(" + oAjax.responseText + ")");
            if (oRetorno.status == 2) {

              alert(oRetorno.message.urlDecode);
            } else {

              alert(_M(MENSAGEM+"compilacao_salva", {solicita:$F('pc54_solicita')}));
              oGridItens.clearAll(true);
              $('pc54_datatermino').value = '';
              $('pc54_datainicio').value  = '';
              $('pc54_solicita').value    = '';
              $('pc10_resumo').value      = '';
              js_pesquisaaberturaprecos();
            }
          }
        });
    }

    $('btnSalvar').observe('click', function() {
      js_salvarCompilacao();
    });
    js_pesquisaaberturaprecos();
  </script>
</html>