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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Eventos do Acordo</legend>
        <table>
          <tr>
            <td>
              <label class="bold">
                <a id="acordo_ancora" for="ac16_sequencial">Acordo:</a>
              </label>
            </td>
            <td>
              <?php
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1);
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Tipo de Evento:</label>
            </td>
            <td>
              <select id="tipoevento" name="tipoevento">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="data">Data do Evento:</label>
            </td>
            <td>
              <?php db_inputdata("data", null, null, null, true, 'text', 1); ?>
            </td>
          </tr>
          <tr id="container-processo" style="display: none;">
            <td>
              <label class="bold">Processo:</label>
            </td>
            <td>
              <input class="field-size4" type="text" id="processo" nome="processo" placeholder="Número/Ano"></input>
            </td>
          </tr>
        </table>

        <fieldset id="container-veiculo" class="separator" style="display: none">
          <legend>Veículo de Publicação</legend>
          <table>
            <tr>
              <td>
                <label class="bold">Tipo:</label>
              </td>
              <td>
                <?php

                  $aTiposPublicacao = array(
                    "" => "Selecione",
                    AcordoEvento::PUBLICACAO_DIARIO_ESTADO       => 'Diário Oficial do Estado',
                    AcordoEvento::PUBLICACAO_INTERNET            => 'Internet',
                    AcordoEvento::PUBLICACAO_JORNAL              => 'Jornal',
                    AcordoEvento::PUBLICACAO_MURAL_ENTIDADE      => 'Mural da Entidade',
                    AcordoEvento::PUBLICACAO_DIARIO_MUNICIPIO    => 'Diário Oficial do Município',
                    AcordoEvento::PUBLICACAO_DIARIO_MUNICIPIO_RS => 'Diário Oficial dos Municípios/RS',
                    AcordoEvento::PUBLICACAO_DIARIO_UNIAO        => 'Diário Oficial da União',
                    AcordoEvento::PUBLICACAO_NAO_PUBLICADO       => 'Não publicado'
                  );

                  db_select("publicacao_tipo", $aTiposPublicacao, true,null);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="publicacao_descricao">Descrição:</label>
              </td>
              <td>
                <?php db_input("publicacao_descricao", 65, 0, true, "text", 1, '', null, null, null, 100); ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>

      <input type="button" id="incluir" value="Incluir"></input>

      <fieldset>
        <legend>Eventos Lançados</legend>
        <div style="width: 600px" id="container-eventos"></div>
      </fieldset>
    </form>
  </div>

  <div id="documentos_evento">
    <div class="subcontainer">
      <form name="form2" method="post" enctype="multipart/form-data">
        <fieldset>
          <legend>Documentos do Evento</legend>
          <table>
            <tr>
              <td>
                <label for="tipo_documento" class="bold">Tipo de Documento:</label>
              </td>
              <td>
                <select id="documento_tipo" name="documento_tipo">
                  <option value="">Selecione</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="anexo" class="bold">Arquivo:</label>
              </td>
              <td>
                <input type="file" name="arquivo" id="arquivo" />
              </td>
            </tr>
          </table>
        </fieldset>

        <input type="button" id="incluir-documento" value="Incluir" />

        <fieldset>
          <legend>Documentos Lançados</legend>
          <div style="width: 450px" id="container-documentos"></div>
        </fieldset>
      </form>
    </div>
  </div>
  <?php db_menu(); ?>
  <script type="text/javascript">

    (function(exports) {

      $("processo").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\/?)([0-9]*)(\/?)([0-9]{0,4})(.*)(\/?)/, '$2$3$4')
      });

      $("tipoevento").addEventListener("change", function() {

        if (this.value == 6) {
          $("container-veiculo").show();
        } else {

          $("container-veiculo").hide();
          $("publicacao_tipo").value = '';
          $("publicacao_descricao").value = '';
        }

        if (this.value == 9 || this.value == 10) {
          $("container-processo").show();
        } else {

          $("container-processo").hide();
          $("processo").value = '';
        }
      });

      const sRPC = "con4_acordoeventos.RPC.php";
      const MENSAGEM = "patrimonial.contratos.con4_acordoeventos.";

      var iCodigoEvento = null,
          aTiposEvento = {},
          aTiposDocumentos = {};

      var oAcordoAncora = $('acordo_ancora'),
          oAcordoCodigo = $('ac16_sequencial'),
          oAcordoDescricao = $('ac16_resumoobjeto'),
          oTipoEvento = $("tipoevento");

      /**
       * Licitacação LookUp
       */
      var oLookUpAcordo = new DBLookUp(oAcordoAncora, oAcordoCodigo, oAcordoDescricao, {
        "sArquivo" : "func_acordo.php",
        "sObjetoLookUp" : "db_iframe_acordo",
        "sLabel" : "Pesquisar Acordo",
        "aParametrosAdicionais" : ["descricao=true"]
      });

      /**
       * Carrega dados do acordo
       */
      oLookUpAcordo.setCallBack('onClick', function () {

        iCodigoEvento = null;
        oCollectionEventos.clear();
        oCollectionDocumentos.clear();
        oGridEventos.reload();
        oGridDocumentos.reload();

        carregarEventos(oAcordoCodigo.value);
      });

      oLookUpAcordo.setCallBack("onChange", function(sDescricao, lErro) {

        iCodigoEvento = null;
        oCollectionEventos.clear();
        oCollectionDocumentos.clear();
        oGridEventos.reload();
        oGridDocumentos.reload();

        if (oAcordoCodigo.value) {
          carregarEventos(oAcordoCodigo.value);
        }
      });

      /**
       * Carrega os Tipos de Eventos
       */
      new AjaxRequest(sRPC, { exec : "getTiposDeEventos" }, function(oResposta, lErro) {

        if (lErro) {
          return alert(oResposta.message.urlDecode());
        }

        aTiposEvento = {};

        for (var oTipo of oResposta.aTipos) {

          aTiposEvento[oTipo.codigo] = oTipo.descricao.urlDecode()

          var oOption = new Option(oTipo.descricao.urlDecode(), oTipo.codigo);
          if (oTipo.codigo == 12) {
            oOption.style.display = "none";
          }
          oTipoEvento.appendChild(oOption);
        }

      }).setMessage("Carregando tipos de eventos.")
        .execute();

      /**
       * Carrega os Tipos de Documentos
       */
      new AjaxRequest(sRPC, { exec : "getTiposDocumentos" }, function(oResposta, lErro) {

        if (lErro) {
          return alert(oResposta.message.urlDecode());
        }

        aTiposDocumentos = {};

        for (var oTipo of oResposta.aTipos) {

          aTiposDocumentos[oTipo.codigo] = oTipo.descricao.urlDecode()

          var oOption = new Option(oTipo.descricao.urlDecode(), oTipo.codigo);
          $("documento_tipo").appendChild(oOption);
        }

      }).setMessage("Carregando tipos de documentos.")
        .execute();

      /**
       * Define as coleções
       */
      var oCollectionEventos = new Collection().setId("codigo"),
          oGridEventos = new DatagridCollection(oCollectionEventos).configure({ order : false, height : 200 });

      oGridEventos.addColumn("tipo", { label : "Tipo", width : "300px" }).transform(function(iTipo) {
        return aTiposEvento[iTipo] || '';
      });

      oGridEventos.addColumn("data", { label : "Data", align : "center", width : "100px" });

      var oCollectionDocumentos = new Collection().setId("codigo"),
          oGridDocumentos = new DatagridCollection(oCollectionDocumentos).configure({ order : false, height : 150 });

      oGridDocumentos.addColumn("tipo", { label : "Tipo de Documento", width : "210px" }).transform(function(iTipo) {
        return aTiposDocumentos[iTipo] || '';
      });

      oGridDocumentos.addColumn("nome", { label : "Arquivo", width : "130px" });

      /**
       * Exclusão de eventos
       */
      oGridEventos.addAction("Excluir", null, function(oEvento, oItem) {

        if (!confirm( _M(MENSAGEM + "confirma_exclusao_evento") )) {
          return false;
        }

        var oParametros = {
          exec : "remover",
          iCodigoEvento : oItem.codigo
        };

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());;
          }

          oCollectionEventos.remove(oItem.ID);
          oGridEventos.reload();

          alert( _M(MENSAGEM + "evento_excluido") );
        }).setMessage("Excluindo evento.")
          .execute();
      });

      /**
       * Documentos dos eventos
       */
      oGridEventos.addAction("Documento", null, function(oEvento, oItem) {

        windowDocumentos.show();
        iCodigoEvento = oItem.codigo;

        oCollectionDocumentos.clear();
        oGridDocumentos.reload();

        var oParametros = {
          exec : "getDocumentos",
          iCodigoEvento : oItem.codigo
        }

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          for (var oDocumento of oRetorno.documentos) {

            oCollectionDocumentos.add({
              codigo : oDocumento.codigo,
              tipo : oDocumento.tipo,
              nome : oDocumento.nome.urlDecode()
            });
          }

          oGridDocumentos.reload();
          makeDocumentosHint();

        }).setMessage("Carregando documentos.")
          .execute();
      });

      oGridEventos.show($("container-eventos"));

      /**
       * Exclusão dos documentos dos eventos
       */
      oGridDocumentos.addAction("Excluir", null, function(oEvento, oItem) {

        if (!confirm( _M(MENSAGEM + "confirma_exclusao_documento") )) {
          return false;
        }

        var oParametros = {
          exec : "removerDocumento",
          iCodigoDocumento : oItem.codigo
        };

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          oCollectionDocumentos.remove(oItem.ID);
          oGridDocumentos.reload();

          makeDocumentosHint();

          alert( _M(MESSAGE + "documento_excluido") );
        }).setMessage("Excluindo documento.")
          .execute();

      });

      oGridDocumentos.show($("container-documentos"));

      function carregarEventos(iAcordo) {

        oCollectionEventos.clear();
        oGridEventos.reload();

        var oParametros = {
          exec : "getEventosDoAcordo",
          iCodigoAcordo : iAcordo
        };

        new AjaxRequest(sRPC, oParametros, function (oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          for (var oEvento of oRetorno.eventos) {

            oCollectionEventos.add({
              codigo : oEvento.codigo,
              tipo : oEvento.tipo,
              data : oEvento.data
            });
          }

          oGridEventos.reload();

        }).execute();
      }

      function makeDocumentosHint() {

        var oGrid = oGridDocumentos.getGrid();

        for (var iRow = 0; iRow < oCollectionDocumentos.itens.length; iRow++) {
          oGrid.setHint(iRow, 1, oCollectionDocumentos.itens[iRow].nome);
        }
      }

      /**
       * Inclusão de eventos
       */
      $("incluir").addEventListener("click", function() {

        var oProcesso = $("processo"),
            oTipoPublicacao = $("publicacao_tipo"),
            oDescricaoPublicacao = $("publicacao_descricao"),
            oData = $("data");

        if (oAcordoCodigo.value == '') {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Acordo" }) );
        }

        if (oTipoEvento.value == '') {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Tipo de Evento" }) );
        }

        if (oData.value == '') {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Data do Evento" }) );
        }

        if (oTipoEvento.value == 6 && oTipoPublicacao.value == '' ) {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Tipo de Veículo de Publicação"}) );
        }

        if ( (oTipoEvento.value == 9 || oTipoEvento.value == 10) && oProcesso.value == '' ) {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Processo"}) );
        }

        var oParametros = {
          exec : "salvar",
          iCodigoAcordo : oAcordoCodigo.value,
          iAnoProcesso : oProcesso.value.split('/')[1] || null,
          iNumeroProcesso : oProcesso.value.split('/')[0] || null,
          sDescricaoVeiculo : oDescricaoPublicacao.value,
          sData : oData.value,
          iTipoVeiculo : oTipoPublicacao.value,
          iTipoEvento : oTipoEvento.value
        }

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          oTipoEvento.value = '';
          oData.value = '';
          oProcesso.value = '';
          oTipoPublicacao.value = '';
          oDescricaoPublicacao.value = '';

          oCollectionEventos.add({
            codigo :oRetorno.iCodigoEvento,
            tipo : oParametros.iTipoEvento,
            data : oParametros.sData
          });

          oGridEventos.reload()

          alert( _M(MENSAGEM + "evento_salvo") );
        }).setMessage("Salvando eventos.")
          .execute();

      });

      /**
       * Inclusão de documentos dos eventos
       */
      $("incluir-documento").addEventListener("click", function() {

        var oTipoDocumento = $("documento_tipo"),
            oArquivo = $("arquivo");

        if (oTipoDocumento.value == '') {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Tipo de Documento" }) );
        }

        if (oArquivo.value == '') {
          return alert( _M(MENSAGEM + "campo_obrigatorio", { sCampo : "Arquivo"}) );
        }

        var oParametros = {
          exec : "adicionarDocumento",
          iCodigoEvento : iCodigoEvento,
          iTipoDocumento : oTipoDocumento.value
        };

        new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          oTipoDocumento.value = '';
          oArquivo.value = '';

          oCollectionDocumentos.add({
            codigo : oRetorno.iCodigoDocumento,
            tipo : oParametros.iTipoDocumento,
            nome : oRetorno.sNomeDocumento.urlDecode()
          });

          oGridDocumentos.reload();
          makeDocumentosHint();

          alert( _M(MENSAGEM + "documento_salvo") );

        }).addFileInput(oArquivo)
          .setMessage("Salvando arquivo.")
          .execute();
      });

      /**
       * Windows Aux
       */
      var oMessageBoard = new DBMessageBoard('msgboard1', 'Anexar Documentos aos Eventos', '', $('documentos_evento'));
      oMessageBoard.show();

      var windowDocumentos = new windowAux('windowDocumentos', 'Documentos de Evento do Acordo', 520, 430);
      windowDocumentos.setContent($("documentos_evento"));

      oLookUpAcordo.abrirJanela(true);
    })(this)

  </script>
</body>