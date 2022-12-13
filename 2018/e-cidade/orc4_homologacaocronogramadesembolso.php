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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Homologação da Perspectiva</legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="perspectiva" id="lbl_perspectiva"><?php db_ancora('Perspectiva:', 'oPerspectiva.pesquisa.busca()', 1, "", "perspectiva-lookup"); ?></label>
              </td>
              <td nowrap>
                <?php
                  db_input('perspectiva', 15, false, true, 'text', 3, null, null, null, 'width: 20%;');
                  db_input('perspectiva_descr', 25, false, true, 'text', 3, null, null, null, 'width: 79%;');
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="homologado" id="lbl_homologado">Situação:</label>
              </td>
              <td><?php db_select("homologado", array( 1 => 'Não Homologado', 2 => 'Homologado' ), 1, 1); ?></td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id="salvar" name="salvar" value="Salvar"/>
      </form>
    </div>
    <?php db_menu(); ?>
    <script type="text/javascript">
      (function(exports) {

        const RPC = "orc4_homologacaocronogramadesembolso.RPC.php";
        const MENSAGENS = "financeiro.orcamento.orc4_homologacaocronogramadesembolso.";

        var oHomologado = $('homologado');
        var oPerspectiva = {
          campos : {
            codigo : $('perspectiva'),
            descricao : $('perspectiva_descr')
          },
          pesquisa : {
            busca : function() {

              js_OpenJanelaIframe( 'top.corpo',
                                   'db_iframe_cronogramaperspectiva',
                                   'func_cronogramaperspectiva.php?funcao_js=parent.oPerspectiva.pesquisa.preenche|o124_sequencial|o124_descricao&tipo=1',
                                   'Pesquisa de Perspectiva',
                                   true );
            },
            preenche : function (iCodigo, sDescricao) {

              db_iframe_cronogramaperspectiva.hide();

              oPerspectiva.campos.codigo.value    = iCodigo;
              oPerspectiva.campos.descricao.value = sDescricao;

              buscarDadosPerspectiva();
            }
          }
        }

        /**
         * busca os dados da perspectiva
         */
        function buscarDadosPerspectiva() {

          var oParametros = {
            exec         : "getDadosPerspectiva",
            iPerspectiva : oPerspectiva.campos.codigo.value
          }

          new AjaxRequest(RPC, oParametros, function(oResposta, lErro) {

            if (lErro) {
              return alert(oResposta.message.urlDecode());
            }

            if (oResposta.acompanhamento) {

              alert( _M(MENSAGENS + "existe_acompanhamento") );
              return limparCampos();
            }

            if (!oResposta.receita) {

              alert( _M(MENSAGENS + "metas_nao_configuradas", {sTipo : "Receita"}) );
              return limparCampos();
            }

            if (!oResposta.despesa) {

              alert( _M(MENSAGENS + "metas_nao_configuradas", {sTipo : "Despesa"}) );
              return limparCampos();
            }

            oHomologado.disabled = false;
            oHomologado.value    = oResposta.homologado;

          }).setMessage("Carregando dados.").execute();
        }

        /**
         * Limpa os Campos do formulario
         */
        function limparCampos() {

          oPerspectiva.campos.codigo.value    = '';
          oPerspectiva.campos.descricao.value = '';
          oHomologado.value    = 1;
          oHomologado.disabled = true;
        }

        $('salvar').observe('click', function(){

          if (empty(oPerspectiva.campos.codigo.value)) {
            return alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Perspectiva"}) );
          }

          var oParametros = {
            exec         : 'alterarSitucacao',
            iPerspectiva : oPerspectiva.campos.codigo.value,
            iHomologado  : oHomologado.value
          }

          new AjaxRequest(RPC, oParametros, function(oResposta, lErro) {

            if (lErro) {
              return alert(oResposta.message.urlDecode());
            }

            alert( _M(MENSAGENS + "salvo_sucesso") );
          }).setMessage("Carregando dados.").execute();
        });

        exports.oPerspectiva = oPerspectiva;

        limparCampos();
        oPerspectiva.pesquisa.busca();
      })(this);
    </script>
  </body>
</html>