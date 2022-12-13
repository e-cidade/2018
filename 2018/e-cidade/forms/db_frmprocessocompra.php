<?php
/**
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

$clliclicitem = new cl_liclicitem;
$clrotulo     = new rotulocampo;

$clrotulo->label("pc80_codproc");
$clrotulo->label("pc80_resumo");
$clrotulo->label("pc80_tipoprocesso");
$clrotulo->label("pc80_data");
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");

?>
<style type="text/css">

  textarea {
    resize : none;
  }

</style>

<form id="processocompra" name="processocompra" method="post" action="">

  <div id="conteudo_abas" class="container">

    <div id="aba_dadosprocesso" style="width: 700px;">
      <fieldset>
        <legend>Dados do Processo de Compras</legend>

        <table border="0" style="margin: 0 auto;">
          <tr>
            <td nowrap title="<?php echo $Tpc80_codproc; ?>">
              <label class="bold" for="pc80_codproc" id="lbl_pc80_codproc"><?php echo $Spc80_codproc; ?>:</label>
            </td>
            <td>
              <?php db_input('pc80_codproc', 10, $Ipc80_codproc, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tpc80_data?>">
              <label class="bold" for="pc80_data" id="lbl_pc80_data"><?php echo $Spc80_data; ?>:</label>
            </td>
            <td>
              <?php db_inputdata('pc80_data', date("d"), date("m"), date("Y"), true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tnome; ?>">
              <label class="bold" for="id_usuario" id="lbl_id_usuario"><?php echo $Snome; ?>:</label>
            </td>
            <td colspan="3">
              <?php
                db_input('id_usuario', 10, $Inome, true, 'text', 3);
                db_input('nome', 46, $Inome, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tdescrdepto; ?>">
              <label class="bold" for="coddepto" id="lbl_coddepto">Departamento:</label>
            </td>
            <td colspan="3">
              <?php
                db_input('coddepto', 10, $Idescrdepto, true, 'text', 3);
                db_input('descrdepto', 46, $Idescrdepto, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tpc80_tipoprocesso; ?>">
              <label class="bold" for="pc80_tipoprocesso" id="lbl_pc80_tipoprocesso"><?php echo $Spc80_tipoprocesso; ?>:</label>
            </td>
            <td colspan="3">
              <?php db_input('pc80_tipoprocesso', 10, $Ipc80_tipoprocesso, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td colspan="4">

              <fieldset class="text-center">
                <legend><?php echo $Spc80_resumo; ?></legend>
                <?php db_textarea('pc80_resumo', 4, 80, $Ipc80_resumo, true, 'text', $iAcao, "", "", "", 735); ?>
              </fieldset>

            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend>Itens do Processo de Compras</legend>

        <div id="griditens"></div>
      </fieldset>
    </div>

    <div id="aba_lotes" style="width: 700px;" class="text-center">

      <table style="width: 700px" class="text-center">
        <tr>
          <td>
            <fieldset>
              <legend>Lote</legend>
              <table>
                <tr>
                  <td>
                    <label class="bold" for="lote" id="lbl_lote">Lote:</label>
                  </td>
                  <td>
                    <select name="lote" id="lote">
                      <option value=''>Selecione</option>
                    </select>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>

        <tr>
          <td>
            <input type="button" name="novo_lote" id="novolote" value="Novo"/>
            <input type="button" name="excluir_lote" id="excluirlote" value="Excluir"/>
          </td>
        </tr>

        <tr>
          <td>
            <fieldset>
              <legend>Itens do Processo de Compras</legend>
              <div id="griditenslote"></div>
            </fieldset>
          </td>
        </tr>
      </table>

    </div>
  </div>

  <div class="subcontainer">
    <input name="excluir" type="button" value="Excluir" id="excluir" disabled/>
    <input name="alterar" type="button" value="Alterar" id="alterar" disabled/>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"/>
  </div>
</form>

<script>

  (function(exports) {

    const MENSAGENS = 'patrimonial.compras.com4_processocompra.';

    var sRpc = 'com4_processocompra.RPC.php',
        oParams = js_urlToObject(),
        iCodigoProcesso = null,
        aRowsGrid = {},
        aLotes = {},
        oBotaoOperacao = null,
        oBotoes = {
          incluirLote    : $('novolote'),
          excluirLote    : $('excluirlote'),
          alterarProcesso: $('alterar'),
          excluirProcesso: $('excluir')
        },
        oCampos = {
          codigo_processo : $('pc80_codproc'),
          data            : $('pc80_data'),
          id_usuario      : $('id_usuario'),
          nome            : $('nome'),
          codigo_departamento    : $('coddepto'),
          descricao_departamento : $('descrdepto'),
          resumo                 : $('pc80_resumo'),
          tipo_processo          : $('pc80_tipoprocesso'),
          lote                   : $('lote')
        },
        lRedirecionaLote = false;

    /**
     * Cria as Abas
     */
    var oDBAbas      = new DBAbas($('conteudo_abas')),
        oAbaProcesso = oDBAbas.adicionarAba('Processo de Compras' , $('aba_dadosprocesso')),
        oAbaLote     = oDBAbas.adicionarAba('Lotes', $('aba_lotes'));

    /**
     * Cria a Grid dos lotes
     */
    oGridLote = new DBGrid('oGridLote');
    oGridLote.nameInstance = 'oGridLote';
    oGridLote.setCheckbox(0);
    oGridLote.setCellAlign(new Array('center', 'center', 'center', 'left', 'left', 'center', 'right', 'right', 'center'));
    oGridLote.setCellWidth(new Array("0%", "6%","18%","22%",'9%', '11%', '14%', '11%', '9%'));
    oGridLote.setHeader(new Array('x', 'Item', 'Código do Material', 'Material', 'Unidade', 'Quantidade', 'Valor Unitário', 'Valor Total', 'Lote'));
    oGridLote.aHeaders[1].lDisplayed = false;
    oGridLote.setHeight(150);
    oGridLote.show($('griditenslote'));

    /**
     * Sobrescreve o selectSingle da grid
     */
    var oSelectSingleLote = oGridLote.selectSingle;

    oGridLote.selectSingle = function() {

      oSelectSingleLote.apply(this, arguments);
      js_gridItens.lote.selecionaItem(arguments[2].iRowNumber);
    }

    /**
     * Cria a grid dos itens
     */
    oGridItens = new DBGrid('oGridItens');
    oGridItens.nameInstance = 'oGridItens';
    oGridItens.setCellAlign(new Array('center', 'center', 'center', 'left'));
    oGridItens.setCellWidth(new Array("15%", "15%", "20%", "50%"));
    oGridItens.setHeader(new Array('Solicitação', 'Item', 'Código do Material', 'Material'));
    oGridItens.setHeight(150);
    oGridItens.show($('griditens'));


    /**
     * Reseta os campos e limpa os valores padrões
     */
    function js_clearForm() {

      iCodigoProcesso = null;
      aRowsGrid = {};
      aLotes    = {};

      oCampos.codigo_processo.value        = '';
      oCampos.data.value                   = '';
      oCampos.id_usuario.value             = '';
      oCampos.nome.value                   = '';
      oCampos.codigo_departamento.value    = '';
      oCampos.descricao_departamento.value = '';
      oCampos.resumo.value                 = '';
      oCampos.tipo_processo.value          = '';
      oCampos.lote.value                   = '';

      oBotaoOperacao.disabled = true;
      oAbaLote.bloquear();
      oDBAbas.mostraFilho(oAbaProcesso);
      js_mudaLote();

      oGridItens.clearAll(true);
    }

    /**
     * Pesquisa os Processos de Compras
     */
    function js_pesquisa() {

      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe_pcproc',
                           'func_excautitem.php?exc=ok&funcao_js=parent.js_pesquisa.preenche|pc80_codproc',
                           'Pesquisa de Processo de Compras',
                           true );

    }

    js_pesquisa.preenche = function(sCodigo) {

      if (top.corpo.db_iframe_pcproc) {
        db_iframe_pcproc.hide();
      }

      js_clearForm();

      var oParametros = {
        sExecutar : "getDadosProcesso",
        iProcessoCompra : sCodigo
      }

      new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

          oAbaLote.bloquear();
          oBotaoOperacao.disabled = true;

          if (erro) {
            alert(oRetorno.sMessage.urlDecode())
            return false;
          }

          if (oRetorno.lLicitacao) {
            alert( _M( MENSAGENS + "processo_com_licitacao" ) );
            js_pesquisa();
            return false;
          }

          if (oRetorno.lOrcamento) {
            alert( _M( MENSAGENS + "processo_com_orcamento" ) );
            js_pesquisa();
            return false;
          }

          iCodigoProcesso = oRetorno.pc80_codproc;

          oCampos.codigo_processo.value        = oRetorno.pc80_codproc;
          oCampos.data.value                   = oRetorno.pc80_data;
          oCampos.id_usuario.value             = oRetorno.id_usuario;
          oCampos.nome.value                   = oRetorno.nome.urlDecode();
          oCampos.codigo_departamento.value    = oRetorno.coddepto;
          oCampos.descricao_departamento.value = oRetorno.descrdepto.urlDecode();
          oCampos.resumo.value                 = oRetorno.pc80_resumo.urlDecode();
          oCampos.tipo_processo.value          = (oRetorno.pc80_tipoprocesso == 1 ? "Item" : "Lote");

          aLotes = (Object.isArray(oRetorno.aLotes) ? {} : oRetorno.aLotes);


          js_lotes.renderizaLotes();
          js_gridItens.carregarDados();

          /**
           * Libera a aba dos lotes caso o processo de compras seja por lote
           */
          if (oRetorno.pc80_tipoprocesso == 2) {
            oAbaLote.desbloquear();
          }

          oBotaoOperacao.disabled = false;

          var onKeyDown = new CustomEvent("onkeydown");
          oCampos.resumo.onkeydown(onKeyDown);

        }).setMessage("Aguarde, carregando informações...").execute();
    }

    var js_gridItens = {

      carregarDados : function() {

        var oParametros = {
            sExecutar : "getItens",
            iProcessoCompra : iCodigoProcesso
          }

        new AjaxRequest(sRpc, oParametros, function(oResponse, erro) {

          if (erro) {
            alert(oResponse.sMessage.urlDecode())
            return false;
          }

          oResponse.aItens.each(function(oItem) {
            aRowsGrid[oItem.codigo_item] = oItem;
          });

          js_gridItens.processo.renderizarGrid();

          /**
           * Verifica se deve redirecionar para a aba do lote
           */
          if (lRedirecionaLote) {

            lRedirecionaLote = false;

            oDBAbas.mostraFilho(oAbaLote);
            js_gridItens.lote.renderizarGrid();
          }

        }).setMessage("Aguarde, carregando informações...").execute();

      },
      lote : {
        renderizarGrid : function(iCodigoLote) {

          if (!iCodigoProcesso)  {
            return false;
          }

          if (!iCodigoLote) {
            iCodigoLote = oCampos.lote.value;
          }

          oGridLote.clearAll(true);

          for (var iCodigo in aRowsGrid) {

            sLote = aLotes[aRowsGrid[iCodigo].lote];

            if (!sLote) {
              aRowsGrid[iCodigo].lote = '';
            }

            var aItem = new Array();

            aItem[0] = aRowsGrid[iCodigo].codigo_item;
            aItem[1] = aRowsGrid[iCodigo].sequencial;
            aItem[2] = aRowsGrid[iCodigo].codigo_material;
            aItem[3] = aRowsGrid[iCodigo].descricao_material.urlDecode();
            aItem[4] = aRowsGrid[iCodigo].unidade.urlDecode();
            aItem[5] = aRowsGrid[iCodigo].quantidade;
            aItem[6] = js_formatar( aRowsGrid[iCodigo].vlr_unitario, 'f' );
            aItem[7] = js_formatar( aRowsGrid[iCodigo].vlr_total, 'f' );
            aItem[8] = (sLote ? sLote : '').urlDecode();

            oGridLote.addRow( aItem,
                              null,
                              ((aRowsGrid[iCodigo].lote != '' && iCodigoLote != aRowsGrid[iCodigo].lote) || iCodigoLote == ''),
                              (iCodigoLote == aRowsGrid[iCodigo].lote && iCodigoLote != ''));
          }

          oGridLote.renderRows();
        },

        selecionaItem : function(iIndexLinha){

          var iCodigoItem = oGridLote.aRows[iIndexLinha].aCells[1].getValue(),
              iLote = oCampos.lote.value,
              sLote = aLotes[iLote];

          if (oGridLote.aRows[iIndexLinha].isSelected) {
            aRowsGrid[iCodigoItem].lote = iLote;
          } else {
            aRowsGrid[iCodigoItem].lote = '';
            sLote = '';
          }

          oGridLote.aRows[iIndexLinha].aCells[9].setContent(sLote);
        }
      },
      processo : {
        renderizarGrid : function() {

          if (!iCodigoProcesso)  {
            return false;
          }

          oGridItens.clearAll(true);

          for (var iCodigo in aRowsGrid) {

            var aItem = new Array();

            aItem[0] = aRowsGrid[iCodigo].solicitacao;
            aItem[1] = aRowsGrid[iCodigo].sequencial;
            aItem[2] = aRowsGrid[iCodigo].codigo_material;
            aItem[3] = aRowsGrid[iCodigo].descricao_material.urlDecode();

            oGridItens.addRow( aItem );
          }

          oGridItens.renderRows();
        }
      }

    }

    var js_lotes = {

      incluirLote : function(sDescricao) {

        if (!iCodigoProcesso) {
          return false;
        }

        var oParametros = {
          sExecutar       : "incluirLote",
          iProcessoCompra : iCodigoProcesso,
          sDescricao      : sDescricao
        };

        new AjaxRequest(sRpc, oParametros, function(oResposta, erro) {

          if (erro) {
            alert(oResposta.sMessage.urlDecode());
            return false;
          }

          alert( _M( MENSAGENS + "lote_salvo" ) );

          aLotes[oResposta.iCodigoLote] = sDescricao;
          js_lotes.renderizaLotes();

          js_gridItens.lote.renderizarGrid();

        }).setMessage("Aguarde, carregando informações...").execute();
      },
      removerLote : function(iCodigoLote) {

        if (!iCodigoLote || !iCodigoProcesso) {
          return false;
        }

        var oParametros = {
          sExecutar : "removerLote",
          iProcessoCompra : iCodigoProcesso,
          iCodigoLote : iCodigoLote
        };

        new AjaxRequest(sRpc, oParametros, function(oResposta, erro) {

          if (erro) {
            alert(oResposta.sMessage.urlDecode());
            return false;
          }

          delete aLotes[iCodigoLote];
          js_lotes.renderizaLotes();

          js_gridItens.lote.renderizarGrid();

          alert( _M( MENSAGENS + "lote_removido" ) );
        }).setMessage("Aguarde, carregando informações...").execute();
      },
      renderizaLotes : function() {

        oLote = $('lote');

        $$("#lote option:not(:first-child)").each(function(oElemento) {
          oLote.removeChild(oElemento);
        })

        if (Object.keys(aLotes).length) {

          for (var iValue in aLotes) {
            var oOption = new Option(aLotes[iValue], iValue);

            oLote.options.add(oOption);
          }
        }

        oLote.value = '';
      }
    }

    $('processocompra').observe('submit', function(event) {
      Event.stop(event);
    });

    oBotoes.alterarProcesso.observe('click', function() {

      if (!iCodigoProcesso) {
        return false;
      }

      /**
       * Monta o array de lotes
       */
      var oItensLote = {},
          aItensLote = new Array();

      if (Object.keys(aLotes).length) {

        for (var iValue in aLotes) {
          oItensLote[iValue] = { lote  : iValue,
                                 itens : [] };
        }
      }

      for ( var iCodigo in aRowsGrid ) {

        var iLote = aRowsGrid[iCodigo].lote;

        if (!iLote) {
          continue;
        }

        oItensLote[iLote].itens.push(iCodigo);
      }

      /**
       * Converte em array
       */
      for (var iLote in oItensLote) {
        aItensLote.push(oItensLote[iLote]);
      }

      var oParametros = {
          sExecutar : "salvar",
          iProcessoCompra : iCodigoProcesso,
          sResumo : oCampos.resumo.value,
          aItens : aItensLote
        }

      new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

          if (erro) {
            alert(oRetorno.sMessage.urlDecode());
            return false;
          }

          alert( _M( MENSAGENS + "alterado") );

        }).setMessage("Aguarde, salvando dados...").execute();
    });

    oBotoes.excluirProcesso.observe('click', function() {

      if (!iCodigoProcesso) {
        return false;
      }

      if (!confirm( _M( MENSAGENS + "confirma_exclusao_processo", { iCodigo : iCodigoProcesso}) )) {
        return false;
      }

      var oParametros = {
          sExecutar : "excluir",
          iProcessoCompra : iCodigoProcesso
        }

      new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

          if (erro) {
            alert(oRetorno.sMessage.urlDecode());
            return false;
          }

          alert( _M( MENSAGENS + "removido") );

          js_clearForm();
          js_pesquisa();

        }).setMessage("Aguarde, excluindo...").execute();
    });

    oBotoes.incluirLote.observe('click', function() {

      if (!iCodigoProcesso) {
        return false;
      }

      var oWindowLote = new windowAux( 'WindowLote',
                                       '',
                                       400,
                                       230 );

      var oContentWindow = document.createElement("div");
      var oMessageBoard  = new messageBoard('teste', 'Inclusão de Lote', 'Inclusão de um novo lote.', oContentWindow)

      var oContainer = document.createElement("div");
      oContainer.classList.add("container");


      var oFieldset = document.createElement("fieldset"),
          oLegend   = document.createElement("legend");
      oLegend.innerHTML = "Dados do Lote";

      var oLabel = document.createElement("label");
      oLabel.setAttribute("for", "lotedescricao");
      oLabel.setAttribute("id", "lbl_lotedescricao");
      oLabel.classList.add("bold");
      oLabel.innerHTML = "Descrição:";

      var oTable = document.createElement("table");
      oTable.appendChild(document.createElement("tr"));
      oTable.firstChild.appendChild(document.createElement("td"));
      oTable.firstChild.appendChild(document.createElement("td"));

      oTable.firstChild.firstChild.appendChild(oLabel);

      var oInputDescricao = new DBTextField('txt_lotedescricao', 'oInputDescricao', '', 20);
      oInputDescricao.setMaxLength(100);
      oInputDescricao.show(oTable.firstChild.lastChild);

      oFieldset.appendChild(oLegend);
      oFieldset.appendChild(oTable);

      var oButton = document.createElement("input");
      oButton.setAttribute("type", "button");
      oButton.setAttribute("id", "btn_lotedescricao");
      oButton.setAttribute("name", "lote_descricao");
      oButton.setAttribute("value", "Incluir");

      oButton.onclick = function() {

        if (oInputDescricao.getValue() == '') {

          alert( _M( MENSAGENS + "preenchimento_obrigatorio", { sCampo : "Descrição" } ) );
          return false;
        }

        js_lotes.incluirLote(oInputDescricao.getValue());
      };

      var oButtonClose = document.createElement("input");
      oButtonClose.setAttribute("type", "button");
      oButtonClose.setAttribute("value", "Fechar");

      oButtonClose.onclick = function() {
        oWindowLote.hide();
      }

      oContainer.appendChild(oFieldset);
      oContainer.appendChild(oButton);
      oContainer.insert("&nbsp;");
      oContainer.appendChild(oButtonClose);

      oContentWindow.appendChild(oContainer);

      oWindowLote.setContent(oContentWindow);
      oWindowLote.show();
    });

    oBotoes.excluirLote.observe('click', function() {

      if ( !confirm( _M( MENSAGENS + "confirma_exclusao_lote") ) ) {
        return false;
      }

      js_lotes.removerLote( oCampos.lote.value );
    });

    oCampos.lote.observe('change', function() {
      js_mudaLote();
    })

    function js_mudaLote() {

      oBotoes.excluirLote.disabled = true;

      if (oCampos.lote.value !== '') {
        oBotoes.excluirLote.disabled = false;
      }

      js_gridItens.lote.renderizarGrid( oCampos.lote.value );
    }

    /**
     * Verifica o tipo de operação para mostrar os botões
     */
    oBotoes.alterarProcesso.hide();
    oBotoes.excluirProcesso.hide();

    oBotoes.alterarProcesso.disabled = true;
    oBotoes.excluirProcesso.disabled = true;

    if (oParams.acao == 2) {

      oBotoes.alterarProcesso.show();
      oBotaoOperacao = oBotoes.alterarProcesso;
    } else if (oParams.acao == 3) {

      oCampos.lote.disabled = true;
      oBotoes.incluirLote.disabled = true;

      oBotoes.excluirProcesso.show();
      oBotaoOperacao = oBotoes.excluirProcesso;
    }

    /**
     * Seta o callback na aba para recarregar a grid dos itens
     */
    oAbaLote.setCallback( js_gridItens.lote.renderizarGrid );
    oAbaProcesso.setCallback( js_gridItens.processo.renderizarGrid );
    oAbaLote.bloquear();

    exports.js_pesquisa  = js_pesquisa;
    exports.js_gridItens = js_gridItens;
    exports.oGridLote    = oGridLote;

    js_mudaLote();

    if (!oParams.iCodigo) {
      js_pesquisa();
    } else {

      lRedirecionaLote = true;
      js_pesquisa.preenche(oParams.iCodigo);
    }

  })(this);
</script>