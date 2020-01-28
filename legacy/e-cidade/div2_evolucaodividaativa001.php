<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oEvolucaoDividaAtiva = new cl_evolucaodividaativa();
$oEvolucaoDividaAtiva->rotulo->label();
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css, AjaxRequest.js, widgets/DBDownload.widget.js");
    ?>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="div2_evolucaodividaativa002.php">

        <fieldset>
          <legend>Evolução da dívida ativa</legend>

          <table class="form-container">
            <tr>
              <td>
                <label for="datainicial" id="lbl_datainicial">Data Inicial:</label>
              </td>
              <td>
                <?php db_inputdata("datainicial", null, null, null, "true", "text", 2); ?>
              </td>
            </tr>

            <tr>
              <td>
                <label for="datafinal" id="lbl_datafinal">Data Final:</label>
              </td>
              <td>
                <?php db_inputdata("datafinal", null, null, null, "true", "text", 2); ?>
              </td>
            </tr>
          </table>
        </fieldset>

        <input name="emitir" id="emitir" type="button" value="Emitir Relatório" onclick="return js_emitir();" />
        <input name="limpar" id="limpar" type="reset" value="Limpar" />
      </form>
    </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script type="text/javascript">

  var sCaminhoMensagens = "tributario.divida.div2_evolucaodividaativa001.";
  var sRpc              = "div2_evolucaodividaativa.RPC.php"

  function js_emitir() {

    <?php
      echo "var dataHoje = '" . date('d/m/Y', db_getsession("DB_datausu")) . "';\n";
      echo "var iAnoUsu = " . db_getsession("DB_anousu") . ";\n";
    ?>

    if ( empty($F("datainicial")) ) {

      alert( _M(sCaminhoMensagens + "campo_obrigatorio", {sCampo : "Data Inicial"}) );
      return false;
    }

    if ( empty($F("datafinal")) ) {

      alert( _M(sCaminhoMensagens + "campo_obrigatorio", {sCampo : "Data Final"}) );
      return false;
    }

    var validaIntervaloDatas = js_comparadata( $F("datainicial"), $F("datafinal"), '<=' );
    if (validaIntervaloDatas == false) {

      alert( _M(sCaminhoMensagens + "intervalo_invalido") );
      return false;
    }

    var validaDatas = js_comparadata( $F("datainicial"), $F("datafinal"), '==' );
    if (validaDatas == true) {

      alert( _M(sCaminhoMensagens + "data_iguais") );
      return false;
    }

    var validaDataHoje = js_comparadata( $F("datainicial"), dataHoje, '>=' );
    if (validaDataHoje == true) {

      alert( _M(sCaminhoMensagens + "emissao_hoje", {sCampo : "Data Inicial"}) );
      return false;
    }

    var validaDataHoje = js_comparadata( $F("datafinal"), dataHoje, '>=' );
    if (validaDataHoje == true) {

      alert( _M(sCaminhoMensagens + "emissao_hoje", {sCampo : "Data Final"}) );
      return false;
    }

    var aParamentros = {
                        "sExecucao"   : "emitirRelatorio",
                        "dataHoje"    : dataHoje,
                        "datafinal"   : $F("datafinal"),
                        "datainicial" : $F("datainicial"),
                       };

    new AjaxRequest( sRpc, aParamentros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      var oDownload = new DBDownload();
      oDownload.addGroups( 'pdf', 'Relatório de Evolução da Dívida Ativa');
      oDownload.setWindowLabel('Arquivo');
      oDownload.setHelpMessage('Clique no link abaixo para fazer o download do relatório.');
      oDownload.addFile( oRetorno.sArquivo, 'Download do relatório', 'pdf' );
      oDownload.show();

      $('limpar').click();

    } ).setMessage( _M( sCaminhoMensagens + "emitindo" ) ).execute();

    return true;
  }
</script>
<style type="text/css">#msgboard1{width:99% !important;}</style>