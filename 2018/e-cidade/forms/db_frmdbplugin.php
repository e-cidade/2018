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

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" enctype="multipart/form-data">
        <fieldset style="width: 700px;">
          <legend>Arquivo Plugin</legend>
          <table>
            <tr>
              <td>
                <input type="file" name="file" id="file">
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="incluir" type="submit" value="Importar" />
      </form>

      <div id="gridPlugins"></div>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    (function() {

      var sUrlRpc = "con4_dbplugin.RPC.php";

      var oGridPlugins = new DBGrid("gridPlugins");
      oGridPlugins.nameInstance = "oGridPlugins";
      oGridPlugins.setCellWidth(["20%", "25%", "10%", "25%", "25%"]);
      oGridPlugins.setCellAlign(["left", "left", "center", "center", "center"]);
      oGridPlugins.setHeader(["Nome", "Label", "Situação", "&nbsp;", "&nbsp;"]);
      oGridPlugins.show($('gridPlugins'));
      oGridPlugins.clearAll(true);

      js_recarregaGrid()

      function js_recarregaGrid() {

        var oParametros = {
          sExecucao: "getPlugins"
        }

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json='+Object.toJSON(oParametros),
          onComplete   : function(oAjax) {
            var oRetorno = JSON.parse(oAjax.responseText)
            
            if (oRetorno.iStatus == "2") {
              alert(oRetorno.sMessage.urlDecode())
              return false;
            }

            oGridPlugins.clearAll(true);

            oRetorno.aPlugins.forEach(function(oPlugin) {
              
              var sButton = '<input type="button" value="'+(oPlugin.lSituacao ? "Desativar" : "Ativar")+'" class="selectRow" data-method="alterarSituacao" data-id="'+oPlugin.iCodigo+'" />';
              var sButtonConfig = '<input type="button" value="Config" class="configRow" data-id="' + oPlugin.iCodigo + '">';
              var sButtonDesinstalar = '<input type="button" value="Desinstalar" class="selectRow" data-method="desinstalar" data-id="'+oPlugin.iCodigo+'" />';

              var aRow = [
                oPlugin.sNome, 
                oPlugin.sLabel,
                oPlugin.lSituacao ? "Ativo" : "Inativo",
                sButton + (oPlugin.lConfiguracao ? ("&nbsp" + sButtonConfig) : ""),
                sButtonDesinstalar
              ]

              oGridPlugins.addRow(aRow);
            })

            oGridPlugins.renderRows();

            $$(".selectRow").each(function(oButton) {
              oButton.observe("click", function() {
                js_gridButton(this.getAttribute("data-method"), this.getAttribute("data-id"));
              })
            });

            $$(".configRow").each(function(oButton) {

              oButton.observe("click", function() {
                js_configButton(this.getAttribute("data-id"));
              })
            });

          }
        }

        var oAjax  = new Ajax.Request( sUrlRpc, oDadosRequisicao );

      }

      function js_gridButton(sMethod, iCodigo) {

        var oParametros = {
          sExecucao: sMethod,
          iCodigo : iCodigo
        }

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json='+Object.toJSON(oParametros),
          onComplete   : function(oAjax) {
            var oRetorno = JSON.parse(oAjax.responseText)
            
            alert(oRetorno.sMessage.urlDecode())

            if (oRetorno.iStatus == "2") {
              return false;
            }

            location.href = location.href

          }

        }

        var oAjax  = new Ajax.Request( sUrlRpc, oDadosRequisicao );
      }

      function js_configButton(iCodigo) {

        var windowConfig = new windowAux('windowConfig', 'Configuração do Plugin', 700, 400);

        var oParametros = {
          sExecucao: "getConfig",
          iCodigo : iCodigo
        }

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json=' + Object.toJSON(oParametros),
          onComplete   : function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText),
                sInputs  = "";

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

            var sContent = '<div class="container">'
                         +   '<form name="formConfig" method="post" action="">'
                         +     '<fieldset style="width: 600px;">'
                         +       '<legend>Configurações</legend>'
                         +       '<table>'
                         +          sInputs
                         +       '</table>'
                         +     '</fieldset>'
                         +     '<input name="salvar" type="submit" value="Salvar" />'
                         +   '</form>'
                         + '</div>';

            windowConfig.setContent(sContent);
            windowConfig.show();

            $$("form[name=formConfig]")[0].observe("submit", function(event) {

              event.preventDefault()

              oParametros.aConfig   = {};
              oParametros.sExecucao = "saveConfig";

              $$("form[name=formConfig] input:not([type=submit])").each(function(oInput) {
                oParametros.aConfig[oInput.name] = oInput.value;
              })

              var oDadosSalvar = {
                method       : 'POST',
                asynchronous : false,
                parameters   : 'json=' + Object.toJSON(oParametros),
                onComplete   : function(oAjax) {

                  var oRetorno = JSON.parse(oAjax.responseText.urlDecode())

                  alert(oRetorno.sMessage)
                }
              }

              new Ajax.Request( sUrlRpc, oDadosSalvar );
            })

          }

        }

        new Ajax.Request( sUrlRpc, oDadosRequisicao );
      }

    })();

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>