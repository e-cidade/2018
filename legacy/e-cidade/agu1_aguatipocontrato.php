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
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">
    <form id="form_tipo_contrato">
      <fieldset>
        <legend>Tipo de Contrato</legend>
        <table>
          <tr>
            <td>
              <label for="codigo" class="bold">
                <a id="ancora_tipocontrato">Código:</a>
              </label>
            </td>
            <td>
              <input type="text" name="codigo" id="codigo" data="x39_sequencial" class="readonly field-size2">
              <input type="hidden" name="tipocontrato_descricao" id="tipocontrato_descricao" data="x39_descricao">
            </td>
          </tr>

          <tr>
            <td>
              <label for="descricao" class="bold">Descrição:</label>
            </td>
            <td>
              <input type="text" name="descricao" id="descricao" maxlength="100" class="field-size7">
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="button" value="Salvar" id="salvar" name="salvar">
      <input type="button" value="Excluir" id="excluir" name="excluir">
      <input type="button" value="Pesquisar" id="pesquisar" name="pesquisar">

    </form>
  <div>

  <?php db_menu(); ?>

  <script type="text/javascript">

    (function (exports) {

      const ARQUIVO_RPC = 'agu1_aguatipocontrato.RPC.php';
      const OPCAO_INCLUIR = 1;
      const OPCAO_ALTERAR = 2;
      const OPCAO_EXCLUIR = 3;

      var oGet = js_urlToObject();

      var TipoContrato = function () {

        this.oCodigo = $('codigo');
        this.oDescricao = $('descricao');

        /**
         * Salvar Tipo de Contrato
         */
        this.salvar = function () {

          var oParametros = {
            'exec': 'salvar',
            'iCodigo': this.oCodigo.value,
            'sDescricao': this.oDescricao.value
          };

          new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

            alert(oRetorno.mensagem);
            if (lErro) {
              return false;
            }

            this.oCodigo.value = oRetorno.oTipoContrato.iCodigo;

          }.bind(this)).execute();
        };

        /**
         * Limpar Dados da Tela
         */
        this.limparDados = function () {
          $('form_tipo_contrato').reset();
        };

        /**
         * Excluir Tipo de Contrato
         */
        this.excluir = function () {

          var oParametros = {
            'exec': 'excluir',
            'iCodigo': this.oCodigo.value
          };

          new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

            alert(oRetorno.mensagem);
            if (lErro) {
              return false;
            }

            this.limparDados();

          }.bind(this)).execute();
        };

        this.carregar = function (iCodigo) {

          var oParametros = {
            'exec': 'carregar',
            'iCodigo': iCodigo
          };

          new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

            if (lErro) {

              alert(oRetorno.mensagem);
              return false;
            }

            this.limparDados();
            this.oCodigo.value = oRetorno.oTipoContrato.iCodigo;
            this.oDescricao.value = oRetorno.oTipoContrato.sDescricao;

          }.bind(this)).execute();
        };

        /**
         * Desabilita Campos da Tela
         */
        this.desabilitarCampos = function () {
          this.oDescricao.addClassName('readonly');
          this.oDescricao.writeAttribute('readonly', 'readonly');
        }
      };

      var oTipoContrato   = new TipoContrato();
      var oBotaoSalvar    = $('salvar');
      var oBotaoExcluir   = $('excluir');
      var oBotaoPesquisar = $('pesquisar');

      oBotaoExcluir.hide();
      oBotaoPesquisar.hide();

      var oTipoContratoLookUp = new DBLookUp($('ancora_tipocontrato'), oTipoContrato.oCodigo, $('tipocontrato_descricao'), {
        "sArquivo"      : "func_aguatipocontrato.php",
        "sObjetoLookUp" : "db_iframe_aguatipocontrato",
        "sLabel"        : "Pesquisar",
        'fCallBack'     : function () {

          oTipoContrato.carregar(oTipoContrato.oCodigo.value);

          if (OPCAO_EXCLUIR == oGet.iOpcao) {
            oBotaoExcluir.writeAttribute('disabled', false);
          }

          if (OPCAO_ALTERAR == oGet.iOpcao) {
            oBotaoSalvar.writeAttribute('disabled', false);
          }
        }
      });

      oTipoContratoLookUp.desabilitar();

      oBotaoPesquisar.on('click', function () {
        oTipoContratoLookUp.abrirJanela(true);
      });

      oBotaoSalvar.on('click', function () {
        oTipoContrato.salvar();
      });

      oBotaoExcluir.on('click', function () {
        if (confirm('Confirma a exclusão do Tipo de Contrato?')) {
          oTipoContrato.excluir();
        }

        this.writeAttribute('disabled', true);
      });

      if (OPCAO_ALTERAR == oGet.iOpcao) {

        oBotaoSalvar.writeAttribute('disabled', true);
        oBotaoPesquisar.show();
        oTipoContratoLookUp.abrirJanela(true);
      }

      if (OPCAO_EXCLUIR == oGet.iOpcao) {

        oBotaoExcluir.show();
        oBotaoExcluir.writeAttribute('disabled', true);
        oBotaoSalvar.hide();
        oBotaoPesquisar.show();
        oTipoContrato.desabilitarCampos();
        oTipoContratoLookUp.abrirJanela(true);
      }

    })(this);
  </script>
</body>
</html>
