<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, prototype.js, estilos.css, widgets/DBLancador.widget.js, AjaxRequest.js");
    ?>
  </head>
  <body>
   <div class="container">
     <div id="ctnLancador">
     </div>
     <input type="button" value="Salvar" id="btnSalvar">
   </div>
  </body>
</html>
<?php
db_menu();
?>
<script>

  (function(window) {
    oLancadorTipoAssentamento = new DBLancador("oLancadorTipoAssentamento");
    oLancadorTipoAssentamento.setNomeInstancia("oLancadorTipoAssentamento");
    oLancadorTipoAssentamento.setLabelAncora("Tipo do Assentamento: ");
    oLancadorTipoAssentamento.setParametrosPesquisa("func_tipoasse.php", ['h12_assent', 'h12_descr']);
    oLancadorTipoAssentamento.iTipoValidacao = 3;
    oLancadorTipoAssentamento.sStringPesquisaChave = "chave_assent";
    oLancadorTipoAssentamento.setTituloJanela("Pesquisa de Tipos de Assentamentos");
    oLancadorTipoAssentamento.setTextoFieldset("Tipos de Assentamentos com Controle de Dias do Mês");
    oLancadorTipoAssentamento.show($("ctnLancador"));
    oLancadorTipoAssentamento.retornoPesquisaDigitacao = function () {

      var aArgumentos = arguments;
      var lErro = false;
      var sDescricao = null;
      if (typeof(arguments[0]) == 'boolean') {

        lErro = arguments[0];
      }
      sDescricao = arguments[1];
      this.oElementos.oButtonLancar.disabled = false;

      if (lErro) {

        this.oElementos.oInputCodigo.value = "";
        this.oElementos.oInputDescricao.value = sDescricao;
        this.oElementos.oInputCodigo.focus();
        return;
      }
      this.oElementos.oInputCodigo.value = this.oElementos.oInputCodigo.value.toUpperCase();
      this.oElementos.oInputDescricao.value = sDescricao;
    };

    $('btnSalvar').observe('click', function() {

      var aAssentamentos = [];
      var aRegistros = oLancadorTipoAssentamento.getRegistros();
      aRegistros.each(function(oTipoAssentamento) {
          aAssentamentos.push(oTipoAssentamento.sCodigo);
      });
      var oParametros = {
          exec           : 'salvarTipoAssentamento',
          aAssentamentos : aAssentamentos
      }

      new AjaxRequest('pes4_controlediasassentamento.RPC.php', oParametros, function(oRetorno, erro) {
        alert(oRetorno.message.urlDecode());
      }).setMessage("Aguarde, salvando dados").execute();
    });

    function carregarAssentamentos() {

      oLancadorTipoAssentamento.clearAll();
      var oParametros = {
        exec : 'getTipoDeAssentamentosConfigurados'
      }

      new AjaxRequest('pes4_controlediasassentamento.RPC.php', oParametros, function(oRetorno, erro) {

        oRetorno.assentamentos.each(function(oTipoAssentamento) {
          oLancadorTipoAssentamento.adicionarRegistro(oTipoAssentamento.codigo, oTipoAssentamento.descricao.urlDecode());
        });
      }).setMessage("Aguarde, pesquisando dados").execute();
    }
    carregarAssentamentos();
  })(window);
</script>
