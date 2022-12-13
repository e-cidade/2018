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
        <legend>Dados do Evento</legend>
        <table>
          <tr>
            <td>
              <label for="licitacao_codigo" class="bold">
                <a id="licitacao_ancora">Licitação:</a>
              </label>
            </td>
            <td>
              <?php
                db_input("l20_codigo", 10, 0, true);
                db_input("l20_anousu", 10, 0, true, 'hidden');
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="fase" class="bold">Fase:</label>
            </td>
            <td>
              <?php
                $aFases = array(
                    1 => "Fase Interna",
                    2 => "Edital Publicado",
                    3 => "Publicação",
                    4 => "Habilitação/Propostas",
                    5 => "Adjudicação/Homologação"
                  );
                $fase = 1;
                db_select("fase", $aFases, true, 1, 'style="width:368px;"');
              ?>
            </td>
          </tr>

          <tr>
            <td><label for="evento" class="bold">Evento:</label>
            </td>
            <td>
              <select id="evento" style="width: 368px;">
                <option value="" selected>Selecione</option>
              </select>
              <input type="hidden" id="codigo_evento" name="codigo_evento" />
            </td>
          </tr>

          <tr>
            <td nowrap>
              <label for="data" class="bold">Data do Evento:</label>
            </td>
            <td>
              <?php
                db_inputdata("data", null, null, null, true, 'text', 1);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="autor" class="bold">
                <a id="autor_ancora">Autor:</a>
              </label>
            </td>
            <td>
              <?php
                $Sz01_numcgm = "Autor";
                db_input("z01_numcgm", 10, 1, true, 'text', 1, 'style="width: 93px;"');
                db_input("z01_nome", 10, 0, true, 'text', 3, 'style="width: 272px;"');
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="data_julgamento" class="bold">Data do Julgamento:</label>
            </td>
            <td>
            <?php
              db_inputdata("data_julgamento","","","", true, 'text', 1);
            ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="resultado" class="bold">Resultado:</label>
            </td>
            <td>
              <?php
                $aResultados = array(
                  "" => "Selecione",
                  EventoLicitacao::RESULTADO_DEFERIDO => "Deferido",
                  EventoLicitacao::RESULTADO_INDEFERIDO => "Indeferido",
                  EventoLicitacao::RESULTADO_PARCIALMENTE_DEFERIDO => "Deferido Pacialmente"
                );
                db_select("resultado", $aResultados, true, 1);
              ?>
            </td>
          </tr>
        </table>

        <fieldset id="evento_publicacao" class="separator" style="display:none;">
          <legend>Veículo de Publicação</legend>
          <table>
            <tr>
              <td>
                <label for="publicacao_tipo" class="bold">Tipo:</label>
              </td>
              <td>
                <?php
                  $aTiposPublicacao = array(
                    "" => "Selecione",
                    EventoLicitacao::PUBLICACAO_DIARIO_ESTADO       => 'Diário Oficial do Estado',
                    EventoLicitacao::PUBLICACAO_INTERNET            => 'Internet',
                    EventoLicitacao::PUBLICACAO_JORNAL              => 'Jornal',
                    EventoLicitacao::PUBLICACAO_MURAL_ENTIDADE      => 'Mural da Entidade',
                    EventoLicitacao::PUBLICACAO_DIARIO_MUNICIPIO    => 'Diário Oficial do Município',
                    EventoLicitacao::PUBLICACAO_DIARIO_MUNICIPIO_RS => 'Diário Oficial dos Municípios/RS',
                    EventoLicitacao::PUBLICACAO_DIARIO_UNIAO        => 'Diário Oficial da União',
                    EventoLicitacao::PUBLICACAO_NAO_PUBLICADO       => 'Não publicado'
                  );
                  db_select("publicacao_tipo", $aTiposPublicacao, true,null);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="publicacao_descricao" class="bold">Descrição:</label>
              </td>
              <td>
                <?php
                  db_input("publicacao_descricao", 65, 0, true, "text", 1, 'style="width: 416px;"', null, null, null, 100);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>

      <input type="button" id="incluir_evento" value="Incluir" />
      <input type="button" id="cancelar_evento" value="Cancelar" style="display: none;" />

      <fieldset>
        <legend>Eventos Lançados</legend>
        <div style="width: 750px" id="container-eventos"></div>
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
                <?php
                  $aTiposDocumento = LicitaConTipoDocumento::$aDescricaoTipoDocumento;
                  array_unshift($aTiposDocumento, "Selecione");
                  db_select("tipo_documento", $aTiposDocumento, true,null);
                ?>
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
        <input type="button" id="incluir_documento" value="Incluir" />
        <fieldset>
          <legend>Documentos Lançados</legend>
          <div id="documentos-lancados"></div>
        </fieldset>
      </form>
    </div>
  </div>
  <?php db_menu(); ?>
  <script type="text/javascript">

    var sRPC = "lic4_licitacaoeventos001.RPC.php";
    var oLicitacaoAncora    = $('licitacao_ancora');
    var oLicitacaoCodigo    = $('l20_codigo');
    var oLicitacaoDescricao = $('l20_anousu');

    var oBtnIncluirEvento    = $('incluir_evento');
    var oBtnCancelarEvento   = $('cancelar_evento');
    var oBtnIncluirDocumento = $('incluir_documento');

    var oEventoLicitacao     = {};
    var iCodigoEvento        = null;

    var aTiposEventoPublicacaoEsclarecimento = ['1', '8', '10', '11', '13'];
    var aTiposEventoRecursoImpulgnacao       = ['9','12','16','17', '18', '19'];

    oLicitacaoCodigo.style.width = "93px";

    var resetFormularioEvento = function () {

      $("codigo_evento").value        = "";
      $('fase').value                 = "";
      $('evento').value               = "";
      $('data').value                 = "";
      $('z01_numcgm').value           = "";
      $('z01_nome').value             = "";
      $('data_julgamento').value      = "";
      $('resultado').value            = "";
      $('publicacao_tipo').value      = "";
      $('publicacao_descricao').value = "";
      $('evento_publicacao').hide();

      oBtnIncluirEvento.value = "Incluir";
      oBtnCancelarEvento.hide();
    };

    /**
     * Licitacação LookUp
     */
    var oLookUpLicitacao = new DBLookUp(oLicitacaoAncora, oLicitacaoCodigo, oLicitacaoDescricao, {
      "sArquivo" : "func_liclicita.php",
      "sObjetoLookUp" : "db_iframe_liclicita",
      "sLabel" : "Pesquisar Licitação"
    });

    oLookUpLicitacao.setCallBack('onClick', function () {

      var oParametros = {
        'codigo_licitacao' : oLicitacaoCodigo.value,
        'exec'             : 'getFaseLicitacao'
      };

      new AjaxRequest(sRPC,oParametros, function (oRetorno, lErro) {

        if (lErro) {
          return false;
        }

        $('fase').value = oRetorno.fase;
      }).execute();

      oEventoLicitacao.getEventos(oLicitacaoCodigo.value);
    });

    /**
     * Autor LookUp
     */
    var oLookUpAutor = new DBLookUp($('autor_ancora'), $('z01_numcgm'), $('z01_nome'), {
      "sArquivo"      : "func_nome.php",
      "sObjetoLookUp" : "db_iframe_cgm",
      "sLabel"        : "Pesquisar Autor"
    });

    /**
     * Grid Eventos
     */
    var oEventosCollection = new Collection().setId('codigo');
    var oGridEventos = DatagridCollection.create(oEventosCollection).configure("order", false);

    oGridEventos.addColumn("fase",   {label : "Fase",   "width" : "140px"});
    oGridEventos.addColumn("evento", {label : "Evento", "width" : "145px"});
    oGridEventos.addColumn("autor",  {label : "Autor",  "width" : ""});
    oGridEventos.addColumn("data",   {label : "Data",   "width" : "70px", "align" : "center"});

    /**
     * Grid Documentos
     */
    var oDocumentosCollection = Collection.create().setId('codigo');
    var oGridDocumentos = DatagridCollection.create(oDocumentosCollection).configure({"order" : false, "height" : 150});

    oGridDocumentos.addColumn("tipo",    {label : "Tipo de Documento", "width" : "200px" });
    oGridDocumentos.addColumn('arquivo', {label : "Arquivo",           "width" : "160px" });

    /**
     * Busca os Eventos da Licitação
     */
    oEventoLicitacao.getEventos = function (iCodigoLicitacao) {

      resetFormularioEvento();
      oEventosCollection.clear();
      oGridEventos.reload();

      oParametros = {
        exec             : "getLicitacaoEventos",
        codigo_licitacao : iCodigoLicitacao
      };
      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        if (lErro) {
          alert(oRetorno.message.urlDecode());
          return false;
        }

        if (oRetorno.aEventos.length == 0) {
          return false;
        }

        for (var oEvento of oRetorno.aEventos) {

          oEventosCollection.add({
            codigo : oEvento.codigo,
            fase   : oEvento.fase.urlDecode(),
            evento : oEvento.evento.urlDecode(),
            data   : oEvento.data.urlDecode(),
            autor  : oEvento.autor.urlDecode(),
            cpf_autor : oEvento.cpf_autor,
            cnpj_autor : oEvento.cnpj_autor
          });
        }

        oGridEventos.reload();

        var oGrid = oGridEventos.getGrid()

        for (var iRow in oEventosCollection.itens) {

          if (oEventosCollection.itens[iRow].autor != '') {

            var oSpan = document.createElement("span"),
                sCpf  = oEventosCollection.itens[iRow].cpf_autor.trim(),
                sCnpj = oEventosCollection.itens[iRow].cnpj_autor.trim();

            oSpan.classList.add("bold");
            oSpan.innerHTML = (sCpf != '' ? "CPF:&nbsp;" : '') + (sCnpj != '' ? "CNPJ:&nbsp;" : '');

            var sAutor = oEventosCollection.itens[iRow].autor + "<br>"
                       + oSpan.outerHTML + sCpf + sCnpj;

            oGrid.setHint(iRow, 2, sAutor);
          }

          oGrid.setHint(iRow, 1, oEventosCollection.itens[iRow].evento);
        }
      }).setMessage("Aguarde, carregando eventos da Licitação.")
        .execute();
    };

    /**
     * Busca Documentos do Evento
     */
    oEventoLicitacao.getDocumentos = function (iCodigoEvento) {

      oDocumentosCollection.clear();
      oGridDocumentos.reload();

      var oParametros = {
        "exec"          : "getDocumentos",
        "codigo_evento" : iCodigoEvento
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.message.urlDecode());
          return false;
        }

        for (var oDocumento of oRetorno.aDocumentos) {
          oDocumentosCollection.add({
            "codigo"  : oDocumento.codigo,
            "arquivo" : oDocumento.arquivo.urlDecode(),
            "tipo"    : oDocumento.tipo.urlDecode()
          });
        }

        oGridDocumentos.reload();

        var oGrid = oGridDocumentos.getGrid();

        for (var iRow in oDocumentosCollection.itens) {
          oGrid.setHint(iRow, 0, oDocumentosCollection.itens[iRow].tipo);
          oGrid.setHint(iRow, 1, oDocumentosCollection.itens[iRow].arquivo);
        }

      }).setMessage("Buscando documentos.")
        .execute();
    };

    oGridEventos.addAction("Editar", null, function(oEvento, oItem) {

      var oParametros = {
        "exec"          : "getLicitacaoEvento",
        "codigo_evento" : oItem.ID
      };

      new AjaxRequest(sRPC,oParametros, function (oRetorno, lErro) {

        if (lErro) {
          return false;
        }

        $('evento_publicacao').hide();

        $('codigo_evento').value        = oRetorno.oEvento.codigo;
        $('fase').value                 = oRetorno.oEvento.fase;
        $('evento').value               = oRetorno.oEvento.evento;
        $('data').value                 = oRetorno.oEvento.data;
        $('z01_numcgm').value           = oRetorno.oEvento.autor;
        $('z01_nome').value             = oRetorno.oEvento.autor_nome;
        $('data_julgamento').value      = oRetorno.oEvento.data_julgamento;
        $('resultado').value            = oRetorno.oEvento.resultado;
        $('publicacao_tipo').value      = oRetorno.oEvento.publicacao_tipo;
        $('publicacao_descricao').value = oRetorno.oEvento.publicacao_descricao;

        if (aTiposEventoPublicacaoEsclarecimento.indexOf(oRetorno.oEvento.evento) != -1) {
          $('evento_publicacao').show();
        }

        oBtnIncluirEvento.value = "Salvar";
        oBtnCancelarEvento.show()
      }).setMessage("Carregando Evento.").execute();
    });

    /**
     * Action excluir evento
     */
    oGridEventos.addAction("Excluir", null, function(oEvento, oItem) {

      if (!confirm("Deseja excluir evento da Licitação?")) {
        return false;
      }

      var oParametros = {
        "exec"          : "excluirEvento",
        "codigo_evento" : oItem.ID
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());

        if (lErro) {
          return false;
        }

        oEventoLicitacao.getEventos(oLicitacaoCodigo.value);
      })
        .setMessage("Excluindo evento.")
        .execute();
    });

    /**
     * Action documentos do evento
     */
    oGridEventos.addAction("Documento", null, function(oEvento, oItem) {

      windowDocumentos.show();
      iCodigoEvento = oItem.ID;
      oEventoLicitacao.getDocumentos(oItem.ID);
    });

    oGridEventos.show($("container-eventos"));

    /**
     * Action excluir documento
     */
    oGridDocumentos.addAction("Excluir", null, function(oEvento, oItem) {

      if (!confirm("Deseja excluir documento do evento da Licitação?")) {
        return false;
      }

      var oParametros = {
        "exec"            : "excluirDocumento",
        "codigoDocumento" : oItem.ID
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());

        if (lErro) {
          return false;
        }

        oEventoLicitacao.getDocumentos(iCodigoEvento);
      })
        .setMessage("Excluindo documento.")
        .execute();
    });

    oGridDocumentos.show($('documentos-lancados'));

    oBtnCancelarEvento.on("click", function() {

      resetFormularioEvento();
    });

    /**
     * Incluir Documentos
     */
    oBtnIncluirDocumento.on("click", function () {

      var oDocumento = {
        "tipo_documento" : $F("tipo_documento"),
        "arquivo"        : $F("arquivo"),
        "codigo_evento"  : iCodigoEvento
      };

      if (empty(oDocumento.tipo_documento)) {

        alert("Campo Tipo do Documento é de preenchimento obrigatório.");
        return false;
      }

      if (empty(oDocumento.arquivo)) {

        alert("Campo Arquivo é de preenchimento obrigatório.");
        return false;
      }

      var oParametros = {
        "exec"           : "salvarDocumento",
        "codigo_evento"  : oDocumento.codigo_evento,
        "tipo_documento" : oDocumento.tipo_documento,
        "arquivo"        : oDocumento.arquivo
      };

      new AjaxRequest(sRPC, oParametros, function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());

        if (lErro) {
          return false;
        }

        oEventoLicitacao.getDocumentos(iCodigoEvento);

        $('tipo_documento').value = 0;
        $('arquivo').value = "";
      })
        .addFileInput($("arquivo"))
        .setMessage("Salvando arquivo.")
        .execute();
    });

    /**
     * Busca tipos de eventos
     */
    new AjaxRequest(sRPC, { "exec" : "getTipoEventos" }, function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.message.urlDecode());
        return false;
      }

      var oTipoEventos = $('evento');

      oTipoEventos.on("change", function() {

        if (aTiposEventoPublicacaoEsclarecimento.indexOf(this.value) != -1) {
          $('evento_publicacao').show();
        } else {
          $('evento_publicacao').hide();
        }
      });

      for (var oTipoEvento of oRetorno.aTipoEventos) {

        var oOption = new Option(oTipoEvento.descricao.urlDecode(), oTipoEvento.codigo);
        oTipoEventos.appendChild(oOption);
      }

    }).execute();

    /**
     * Incluir Eventos
     */
    oBtnIncluirEvento.onclick = function() {

      var oEvento = {
        "codigo_evento"        : $F("codigo_evento"),
        "codigo_licitacao"     : $F('l20_codigo'),
        "fase"                 : $F('fase'),
        "evento"               : $F('evento'),
        "data"                 : $F('data'),
        "autor"                : $F('z01_numcgm'),
        "autor_descricao"      : $F('z01_nome'),
        "data_julgamento"      : $F('data_julgamento'),
        "resultado"            : $F('resultado'),
        "publicacao_tipo"      : $F('publicacao_tipo'),
        "publicacao_descricao" : tagString($F('publicacao_descricao'))
      };

      if (empty(oEvento.codigo_licitacao)) {

        alert("Campo Licitação é de preenchimento obrigatório.");
        return false;
      }

      if (empty(oEvento.evento)) {

        alert("Campo Evento é de preenchimento obrigatório.");
        return false;
      }

      if (empty(oEvento.data)) {

        alert("Campo Data do Evento é de preenchimento obrigatório.");
        return false;
      }

      if (aTiposEventoPublicacaoEsclarecimento.indexOf(oEvento.evento) != -1) {

        if (empty(oEvento.publicacao_tipo)) {

          alert("Campo Tipo de Publicação é de preenchimento obrigatório.");
          return false;
        }
      }

      if (aTiposEventoRecursoImpulgnacao.indexOf(oEvento.evento) != -1) {

        if (empty(oEvento.autor)) {

          alert("Campo Autor é de preenchimento obrigatório.");
          return false;
        }

        if (empty(oEvento.data_julgamento)) {

          alert("Campo Data do Julgamento é de preenchimento obrigatório.");
          return false;
        }

        if (empty(oEvento.resultado)) {

          alert("Campo Resultado é de preenchimento obrigatório.");
          return false;
        }
      }

      if (aTiposEventoPublicacaoEsclarecimento.indexOf(oEvento.evento) == -1) {

        oEvento.publicacao_tipo      = "";
        oEvento.publicacao_descricao = "";
      }

      var oParametros = {
        "exec"   : "salvarEvento",
        "evento" : oEvento
      };

      new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());

        if (lErro) {
          return false;
        }

        resetFormularioEvento();

        oEventoLicitacao.getEventos(oLicitacaoCodigo.value);

      })
        .setMessage("Salvando evento.")
        .execute();
    };

    /**
     * Windows Aux
     */

    var oMessageBoard = new DBMessageBoard('msgboard1', 'Anexar Documentos aos Eventos', '', $('documentos_evento'));
    oMessageBoard.show();

    var windowDocumentos = new windowAux('windowDocumentos', 'Documentos de Evento de Licitação', 520, 430);
    windowDocumentos.setContent($("documentos_evento"));
    oLookUpLicitacao.abrirJanela(true);
  </script>
</body>