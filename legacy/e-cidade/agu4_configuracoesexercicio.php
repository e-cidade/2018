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

$iAno = db_getsession('DB_anousu');
?>
<html>

<head>

    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>

    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="expires" content="0">

    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body class="body-default">

  <div class="container">

    <form action="" method="post" id="form_contrato">

      <fieldset>

        <legend>Configurações do Exercício</legend>

        <table>

          <tr>
            <td>
              <label class="bold" for="ano">Ano:</label>
            </td>
            <td>
              <input class="field-size2 readonly" type="text" name="ano" id="ano" readonly="readonly">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="tipo_debito">
                <a id="tipo_debito_label">Tipo de Débito:</a>
              </label>
            </td>
            <td>
              <input class="field-size2" type="text" name="tipo_debito" id="tipo_debito" data="k00_tipo">
              <input class="field-size8" type="text" name="tipo_debito_descricao" id="tipo_debito_descricao" data="k00_descr">
            </td>
          </tr>

        </table>

        <fieldset class="separator">

          <legend>Características</legend>

          <table>

            <tr>
              <td>
                <label class="bold" for="sem_agua">
                  <a id="sem_agua_label">Sem Água:</a>
                </label>
              </td>

              <td>
                <input class="field-size2" type="text" name="sem_agua" id="sem_agua" data="j31_codigo">
                <input class="field-size8" type="text" name="sem_agua_descricao" id="sem_agua_descricao" data="j31_descr">
              </td>
            </tr>

            <tr>
              <td>
                <label class="bold" for="sem_esgoto">
                  <a id="sem_esgoto_label">Sem Esgoto:</a>
                </label>
              </td>

              <td>
                <input class="field-size2" type="text" name="sem_esgoto" id="sem_esgoto" data="j31_codigo">
                <input class="field-size8" type="text" name="sem_esgoto_descricao" id="sem_esgoto_descricao" data="j31_descr">
              </td>
            </tr>

          </table>

        </fieldset> <!-- /Características -->

      </fieldset>

      <input type="button" value="Salvar" id="salvar">

    </form>

  </div> <!-- /.container -->

<?php db_menu() ?>

<script>
  (function () {

    'use strict';

    var URL_RPC = 'agu4_configuracoesexercicio.RPC.php';

    var oBtnSalvar = $('salvar');

    var oAno = $('ano');

    var oTipoDebito = $('tipo_debito');
    var oTipoDebitoInput = new DBInputInteger(oTipoDebito);
    var oTipoDebitoLabel = $('tipo_debito_label');
    var oTipoDebitoDescricao = $('tipo_debito_descricao');
    var oTipoDebitoLookup = new DBLookUp(oTipoDebitoLabel, oTipoDebito, oTipoDebitoDescricao, {
        'sArquivo' : 'func_arretipo.php'
    });

    var oSemAgua = $('sem_agua');
    var oSemAguaInput = new DBInputInteger(oSemAgua);
    var oSemAguaLabel = $('sem_agua_label');
    var oSemAguaDescricao = $('sem_agua_descricao');
    var oSemAguaLookup = new DBLookUp(oSemAguaLabel, oSemAgua, oSemAguaDescricao, {
        'sArquivo' : 'func_caracter.php'
    });

    var oSemEsgoto = $('sem_esgoto');
    var oSemEsgotoInput = new DBInputInteger(oSemEsgoto);
    var oSemEsgotoLabel = $('sem_esgoto_label');
    var oSemEsgotoDescricao = $('sem_esgoto_descricao');
    var oSemEsgotoLookup = new DBLookUp(oSemEsgotoLabel, oSemEsgoto, oSemEsgotoDescricao, {
      'sArquivo' : 'func_caracter.php'
    });


    var aLookups = [
      oTipoDebitoLookup,
      oSemAguaLookup,
      oSemEsgotoLookup
    ];

    function salvarConfiguracao() {

      var oParametros = {
        'exec' : 'salvar',
        'iTipoDebito' : oTipoDebito.value,
        'iSemAgua' : oSemAgua.value,
        'iSemEsgoto' : oSemEsgoto.value
      };
      new AjaxRequest(URL_RPC, oParametros, function (oRetorno, lErro) {
        alert(oRetorno.message);
      }).execute();
    }

    function carregarConfiguracao() {

       var oParametros = {
         'exec' : 'carregar'
       };
       new AjaxRequest(URL_RPC, oParametros, function (oRetorno, lErro) {

         if (lErro) {

           oBtnSalvar.disable();
           for (var oLookup of aLookups) {
             oLookup.desabilitar();
           }
           return alert(oRetorno.message);
         }

         oAno.value = oRetorno.iAno;

         oTipoDebito.value = oRetorno.iTipoDebito;
         oTipoDebitoDescricao.value = oRetorno.sTipoDebito;

         oSemAgua.value = oRetorno.iSemAgua;
         oSemAguaDescricao.value = oRetorno.sSemAgua;

         oSemEsgoto.value = oRetorno.iSemEsgoto;
         oSemEsgotoDescricao.value = oRetorno.sSemEsgoto;

       }).execute();
    }

    carregarConfiguracao();

    oBtnSalvar.observe('click', function () {
      salvarConfiguracao();
    });

  })();
</script>

</body>
</html>
