<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                www.dbseller.com.br
 *             e-cidade@dbseller.com.br
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$db_opcao = 1;

$clprotprocesso = new cl_protprocesso();
$rotulo         = new rotulocampo();
$rotulo->label("p58_numero");

?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("estilos.css");
    ?>
  </head>
  <body>
    <div class="Container">
        <form name="formulario" method="post">
          <fieldset>
            <legend>Impressão de Etiquetas</legend>
            <table>
              <tr>
                <td>
                  <?php
                    db_ancora("Processo Inicial:", "js_pesquisarNumeroProcesso(true, 'numero_inicial');", $db_opcao);
                  ?>
                </td>
                <td>
                  <?php
                    db_input("p58_numero", 10, 4, true, "text", $db_opcao, "onchange='js_pesquisarNumeroProcesso(false, \"numero_inicial\");'", "numero_inicial");
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php
                    db_ancora("Processo Final:", "js_pesquisarNumeroProcesso(true, 'numero_final')", $db_opcao);
                   ?>
                </td>
                <td>
                  <?php
                    db_input("p58_numero", 10, 4, true, "text", $db_opcao, "onchange='js_pesquisarNumeroProcesso(false, \"numero_final\");'", "numero_final");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>

          <input type="button" value="Imprimir" onclick="js_validarCampos();" >

        </form>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
    <script language="JavaScript" type="text/javascript" >
      (function(exports) {

        var sCampo = "";

        const MENSAGENS = "patrimonial.protocolo.pro2_etiquetaprocesso.";

        function js_pesquisarNumeroProcesso(mostra, sNomeCampo) {

          sCampo = sNomeCampo;
          var sUrl = 'func_protprocesso_protocolo.php?lAnoAtual=true';

          if (mostra) {

            sUrl += '&funcao_js=parent.js_pesquisarNumeroProcesso.mostrarNumero|p58_numero';
            js_OpenJanelaIframe('', 'db_iframe_proc', sUrl, 'Pesquisa de Processos', true);
          } else {

            var sValorCampo = $F(sCampo);
            sUrl += '&pesquisa_chave=' + sValorCampo + '&funcao_js=parent.js_pesquisarNumeroProcesso.mostrarNumeroBackground';
            js_OpenJanelaIframe('', 'db_iframe_proc', sUrl, 'Pesquisa de Processos', false);
          }
        }

        js_pesquisarNumeroProcesso.mostrarNumero = function (sNumeroProcesso) {

          var aNumeroProcesso = sNumeroProcesso.split("/");
          $(sCampo).value = aNumeroProcesso[0];
          db_iframe_proc.hide();
        };

        js_pesquisarNumeroProcesso.mostrarNumeroBackground = function (sNumeroProcesso, sNome, erro) {

          if (erro) {

            alert("Processo " + $(sCampo).value + " não encontrado.");
            $(sCampo).focus();
            $(sCampo).value = "";

            return false;
          }

          var aNumeroProcesso = sNumeroProcesso.split("/");
          $(sCampo).value = aNumeroProcesso[0];
        };

        function js_validarCampos() {

          var sNumeroInicial = $('numero_inicial').value;
          var sNumeroFinal   = $('numero_final').value;

          if (empty(sNumeroInicial)) {

            alert( _M(MENSAGENS + "campo_obrigatorio", { sCampo : "Processo Inicial"}) );
            return false;
          }

          if (empty(sNumeroFinal)) {

            alert( _M(MENSAGENS + "campo_obrigatorio", { sCampo : "Processo Final"}) );
            return false;
          }

          if (parseInt(sNumeroInicial) > parseInt(sNumeroFinal)) {

            alert( _M(MENSAGENS + "processo_inicial_maior") );
            return false;
          }

          js_validarCampos.submeterFormulario(sNumeroInicial, sNumeroFinal);
        }

        js_validarCampos.submeterFormulario = function (sNumeroInicial, sNumeroFinal) {

          var url = "pro2_etiquetaprocesso002.php?numero_inicial=" + sNumeroInicial + "&numero_final=" + sNumeroFinal;
          window.open( url, '', 'width=' + (screen.availWidth - 5) + ', height=' + (screen.availHeight - 40) + ', scrollbars=1, location=0' );
        };

        exports.js_pesquisarNumeroProcesso = js_pesquisarNumeroProcesso;
        exports.js_validarCampos           = js_validarCampos;

      })(this);
    </script>
  </body>
</html>