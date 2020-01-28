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
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style rel="stylesheet" type="text/css">
      .log-modification-container { position: relative; }
      .log-badge { padding: 1px 5px; border: 1px solid #000; margin-left:3px; border-radius: 2px }
      .log-badge-error { background: #da5d5d;  color: #fff; }
      .log-badge-warning { background: #ecec6e; color: #333;  }

      .log-info { color: white; }
      .log-debug { color: lightgrey; }
      .log-warning { color: #ffbe03; }
      .log-error { color: red; }

      #windowLog { cursor: default !important; background: #222 !important; color: #f0f0f0 !important;}
      #windowLogTitleBar {cursor: pointer !important; }
      #windowwindowLog_content {
        outline: none !important;
        border: 0 !important;
        padding:0 !important;
        height: calc(100% - 22px) !important;
      }

      #windowLog .aba {
        background: #444;
        color: #ccc;
        border:1px solid #666 !important;
        border-bottom: 0 !important;
      }

      #windowLog .abaAtiva {
        background: #222 !important;
        color: #f0f0f0 !important;
      }
    </style>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" enctype="multipart/form-data">
        <fieldset style="width: 700px;">
          <legend>Arquivo do Plugin</legend>
          <table>
            <tr>
              <td>
                <input type="file" name="file" id="file"/>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="incluir" id="incluir" type="button" value="Importar" />
      </form>

      <div id="gridPlugins"></div>
    </div>

    <div id="containerLog" style="display: none;">
      <div id="containerLogPlugin"></div>
      <div id="containerLogModification"></div>
    </div>

    <div id="containerConfig" class="container" style="display: hidden;">
      <form name="formConfig" method="post" action="">
        <fieldset style="width: 600px;">
          <legend>Configurações</legend>

          <table id="tableInput">
          </table>
        </fieldset>
        <input name="salvar" type="submit" value="Salvar" />
      </form>
    </div>

    <?php db_menu(); ?>
  </body>
  <script>

    (function() {

      const RPC = "con4_dbplugin.RPC.php";

      var windowPadding = 50;
      var windowWidth = window.innerWidth - windowPadding > 800 ? 800 : window.innerWidth - windowPadding;
      var windowHeight = window.innerHeight - windowPadding > 600 ? 600 : window.innerHeight - windowPadding;
      var windowLog = new windowAux('windowLog', 'Log do plugin', windowWidth, windowHeight);
      var oContainerLog = $('containerLog');
      var oContainerLogPlugin = $('containerLogPlugin');
      var oContainerLogModification = $('containerLogModification');

      var oAbas = new DBAbas($('containerLog'));
      var oAbaPlugins = oAbas.adicionarAba('Plugin', $('containerLogPlugin'));
      var oAbaModificacoes = oAbas.adicionarAba('Modificações', $('containerLogModification'));

      windowLog.setContent(oContainerLog);

      var oContainerWindowContent = document.querySelector('#windowwindowLog_content');

      var windowConfig = new windowAux('windowConfig', 'Configuração do Plugin', 700, 400),
          oGridPlugins = new DBGrid("gridPlugins"),
          oContainerConfig = $('containerConfig'),
          oTableInput      = $('tableInput');

      windowConfig.setContent(oContainerConfig);

      oGridPlugins.nameInstance = "oGridPlugins";
      oGridPlugins.setCellWidth(["37%", "10%", "10%", "27%", "16%"]);
      oGridPlugins.setCellAlign(["left", "center", "center", "center", "center"]);
      oGridPlugins.setHeader(["Label", "Versão", "Situação", "&nbsp;", "&nbsp;"]);
      oGridPlugins.setHeight(300);
      oGridPlugins.show($('gridPlugins'));

      /**
       * Instala o plugin já validado
       */
      function instalarPlugin(sPlugin) {

        var oParametros = {
            sExecucao : "instalarPlugin",
            sArquivo  : sPlugin
          };

        new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

            if (lErro) {
              alert(oRetorno.sMessage.urlDecode());
              return false;
            }

            alert("Plugin instalado com sucesso.");

            js_recarregaGrid();

          }).setMessage("Aguarde, instalando o plugin.").execute();
      }

      /**
       * Importa o plugin e valida
       */
      $('incluir').observe('click', function() {

        if (empty($('file').value)) {
          return alert("Nenhum arquivo selecionado.");
        }

        var oParametros = { sExecucao : "validarPlugin" },
            oRequest    = new AjaxRequest( RPC, oParametros, function(oRetorno, lErro) {

              if (lErro) {

                alert(oRetorno.sMessage.urlDecode())
                return false;
              }

              if (oRetorno.lAtualizacao) {

                if (confirm("O Plugin já esta instalado, deseja atualizar?")) {
                  return instalarPlugin(oRetorno.sArquivo);
                }

                return false;
              }

              instalarPlugin(oRetorno.sArquivo);
            });

        oRequest.addFileInput($('file'));
        oRequest.setMessage("Aguarde, importando o plugin.");
        oRequest.execute();
      });

      function js_recarregaGrid() {

        new AjaxRequest(RPC, { sExecucao: "getPlugins" }, function(oRetorno, lErro) {

            if (lErro) {

              alert(oRetorno.sMessage.urlDecode())
              return false;
            }

            oGridPlugins.clearAll(true);

            oRetorno.aPlugins.forEach(function(oPlugin) {

              var sButton = '<input type="button" value="' + (oPlugin.lSituacao ? "Desativar" : "Ativar") + '" class="selectRow" data-method="alterarSituacao" data-id="'+oPlugin.iCodigo+'" />';
              var sButtonDesinstalar = '<input type="button" value="Desinstalar" class="selectRow" data-method="desinstalar" data-id="' + oPlugin.iCodigo + '" />';

              var sButtonConfig = '', sButtonLogModificacoes = '';

              if (oPlugin.lConfiguracao) {
                sButtonConfig = '<input type="button" value="Config" class="configRow" data-id="' + oPlugin.iCodigo + '">';
              }

              sButtonLogModificacoes = '<span class="log-modification-container">';
              sButtonLogModificacoes += '<input type="button" class="logRow" value="Log" data-id="'+ oPlugin.iCodigo +'" />';

              if (oPlugin.oErrosModificacoes.warning) {
                sButtonLogModificacoes += '<span title="Avisos" class="log-badge log-badge-warning">'
                  + oPlugin.oErrosModificacoes.warning + '</span>';
              }

              if (oPlugin.oErrosModificacoes.error) {
                sButtonLogModificacoes += '<span title="Erros" class="log-badge log-badge-error">'
                  + oPlugin.oErrosModificacoes.error + '</span>';
              }

              sButtonLogModificacoes += '</span>';

              var sSituacao = oPlugin.lSituacao ? "Ativo" : "Inativo";

              var aRow = [
                oPlugin.sLabel,
                oPlugin.nVersao,
                sSituacao,
                sButton + "&nbsp;" + sButtonConfig + "&nbsp;" + sButtonLogModificacoes,
                sButtonDesinstalar
              ]

              oGridPlugins.addRow(aRow);
            });

            oGridPlugins.renderRows();

            oRetorno.aPlugins.forEach(function(oPlugin, iLinha) {

              var oHintNome = window["oDBHint_"+iLinha+"_1"] = new DBHint("oDBHint_"+iLinha+"_1");
              var sDescricao = "<strong>Label: </strong>" + oPlugin.sLabel
                             + "<br/><strong>Nome: </strong>" + oPlugin.sNome;

              oHintNome.setWidth(300);
              oHintNome.setText(sDescricao);
              oHintNome.setShowEvents(["onmouseover"]);
              oHintNome.setHideEvents(["onmouseout"]);
              oHintNome.setScrollElement($('body-container-gridPlugins'));
              oHintNome.setPosition('B', 'L');

              oHintNome.make($(oGridPlugins.aRows[iLinha].aCells[0].sId));
            });

            $$(".logRow").each(function(oButton) {

              oButton.observe('click', function() {

                var iCodigo = this.getAttribute("data-id")
                loadLogPlugin(iCodigo);
                loadLogModifications(iCodigo);

                oContainerLog.style.display = '';
                windowLog.show();

                oAbaModificacoes.setVisibilidade(false);
                oAbaPlugins.setVisibilidade(true);

                oContainerWindowContent.scrollTop = 0;
              });

            });

            /**
             * Seta a ação dos botões
             */
            $$(".selectRow").each(function(oButton) {

              oButton.observe("click", function() {

                var oParametros = {
                  sExecucao : this.getAttribute("data-method"),
                  iCodigo   : this.getAttribute("data-id")
                };

                if (oParametros.sExecucao == "desinstalar" && !confirm("Confirma a desinstalação do plugin?")) {
                  return false;
                }

                if ( oParametros.sExecucao == "alterarSituacao" && this.value == "Desativar"
                     && !confirm("O Plugin será desativado e todos os seus dados serão removidos, deseja continuar?")) {
                  return false;
                }

                new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

                    alert(oRetorno.sMessage.urlDecode());

                    if (lErro) {
                      return false;
                    }

                    js_recarregaGrid()
                  }).setMessage("Aguarde.").execute();
              })
            });


            $$(".configRow").each(function(oButton) {

              oButton.observe("click", function() {

                var oParametros = {
                  sExecucao : "getConfig",
                  iCodigo : this.getAttribute("data-id")
                };

                new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

                    if (lErro) {
                      alert(oRetorno.sMessage.urlDecode());
                      return false;
                    }

                    var sInputs = '';

                    if (oRetorno.aConfiguracoes) {

                      for (var sLabel in oRetorno.aConfiguracoes) {
                        sInputs += '<tr>'
                                 +   '<td>'
                                 +     '<label class="bold" for="' + sLabel + '" id="' + oRetorno.aConfiguracoes[sLabel] + '">'
                                 +       sLabel
                                 +     ':</label>'
                                 +   '</td>'
                                 +   '<td>'
                                 +     '<input size="35" type="text" id="' + sLabel + '" name="'
                                 + sLabel + '" value="' + oRetorno.aConfiguracoes[sLabel] + '" autocomplete="off">'
                                 +   '</td>'
                                 + '</tr>';
                      }
                    }

                    oContainerConfig.style.display = '';
                    oTableInput.innerHTML          = sInputs;

                    $$("form[name=formConfig]")[0].observe("submit", function(event) {

                      event.preventDefault()

                      oParametros.aConfig   = {};
                      oParametros.sExecucao = "saveConfig";

                      $$("form[name=formConfig] input:not([type=submit])").each(function(oInput) {
                        oParametros.aConfig[oInput.name] = oInput.value;
                      })

                      new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {
                          alert(oRetorno.sMessage.urlDecode());
                        }).setMessage("Aguarde, salvando configuração.").execute();
                    });

                    windowConfig.show();

                  }).setMessage("Aguarde, carregando configuração.").execute();

              })
            });

          }).setMessage("Aguarde, carregando plugins.").execute();
      };

      function loadLogPlugin(iCodigo) {

        var oParametros = {
          sExecucao : 'getLog',
          iCodigo : iCodigo
        };

        var oAjax = new AjaxRequest(RPC, oParametros);

        oAjax.setCallBack(function(oRetorno, lErro) {

          // clear log container
          oContainerLogPlugin.innerHTML = '';

          if (lErro) {
            return alert(oRetorno.sMessage.urlDecode());
          }

          oContainerLogPlugin.innerHTML = '<pre>'+oRetorno.sConteudo.urlDecode()+'</pre>';

        });
        oAjax.setMessage("Buscando log");
        oAjax.execute();
      }

      function loadLogModifications(iCodigo) {

        var oParametros = {
          sExecucao : 'getLogModificacoes',
          iCodigo : iCodigo
        };

        var oAjax = new AjaxRequest(RPC, oParametros);

        oAjax.setCallBack(function(oRetorno, lErro) {

          // clear log container
          oContainerLogModification.innerHTML = '';

          if (lErro) {
            return alert(oRetorno.sMessage.urlDecode());
          }

          if (!oRetorno.lPossuiModificacoes) {
            oContainerLogModification.innerHTML = "<p>Plugin não possui modificações</p>";
            return;
          }

          if (oRetorno.aLogModificacoes.length == 0 || !oRetorno.lSituacao) {
            oContainerLogModification.innerHTML = "<p>Modificações não instaladas.</p>";
            return;
          }

          var header = '';
          for (var idModificacao in  oRetorno.aLogModificacoes) {

            if (typeof(oRetorno.aLogModificacoes[idModificacao]) != 'string') {
              continue;
            }

            var sConteudoLog = oRetorno.aLogModificacoes[idModificacao];
            oContainerLogModification.innerHTML += header + idModificacao + '<pre>' + sConteudoLog + '</pre>';
            header = '<hr />';
          }

        });
        oAjax.setMessage("Buscando log");
        oAjax.execute();
      }

      js_recarregaGrid();
    })();

  </script>
</html>
