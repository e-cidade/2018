<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('dbforms/db_funcoes.php'));

$aOcorrencia = \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoRequestFilters::getOcorrencia();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load('scripts.js, strings.js, numbers.js, prototype.js, estilos.css'); ?>
    <?php db_app::load('widgets/DBLookUp.widget.js, AjaxRequest.js, widgets/DBDownload.widget.js'); ?>
    <style>
      #iCodigoOcorrencia {
        width: 121px;
      }
    </style>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form" id="form">
        <fieldset>
          <legend>Retorno Cobrança Registrada</legend>
          <table class="form-container">
            <tr>
              <td>
                <label id="labelConvenio" for="iCodigoConvenio"><a href="javascript:;">Convênio:</a></label>
              </td>
              <td>
                <?php db_input('iCodigoConvenio', 1, 1, true, 'text', 1, 'data="ar11_sequencial"', null, null, 'width:90px'); ?>
                <?php db_input('sConvenioDescricao', 1, 1, true, 'text', 3, 'data="ar11_nome"'); ?>
              </td>
            </tr>
            <tr>
              <td>
                <label id="labelTipoDebito" for="iCodigoTipoDebito"><a href="javascript:;">Tipo de Débito:</label>
              </td>
              <td>
                <?php db_input('iCodigoTipoDebito', null, 1, true, 'text', null, 'data="k00_tipo"', null, null, 'width:90px'); ?>
                <?php db_input('sTipoDebitoDescricao', 20, 3, true, 'text', 3, 'data="k00_descr"'); ?>
              </td>
            </tr>
            <tr>
              <td>Data de Emissão:</td>
              <td>
                <?php db_inputdata('sDataEmissaoInicio', null, null, null, true, null, 1); ?>
                <strong>a</strong>
                <?php db_inputdata('sDataEmissaoFim', date('d'), date('m'), date('Y'), true, null, 1); ?>
              </td>
            </tr>
            <tr>
              <td>Código de Arrecadação:</td>
              <td>
                <?php db_input('iCodigoArrecadacao', 1, 1, null, true, 1, 1, null, null, 'width:121px'); ?>
              </td>
            </tr>
            <tr>
              <td>Ocorrência:</td>
              <td>
                <?php db_select('iCodigoOcorrencia', $aOcorrencia, true, 1); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" value="Processar" name="processar" id="processar" onclick="return js_processar()" />
        <input type="button" value="Limpar" name="limpar" id="limpar" onclick="form.reset()" />
      </form>
    </div>
    <?php db_menu(db_getsession('DB_id_usuario'),db_getsession('DB_modulo'),db_getsession('DB_anousu'),db_getsession('DB_instit')); ?>
    <script type="text/javascript">

      var RPC = 'arr2_retornocobrancaregistrada.RPC.php';

      var oLookUpConvenio = new DBLookUp($('labelConvenio'), $('iCodigoConvenio'), $('sConvenioDescricao'), {
        'sArquivo'      : 'func_cadconvenio.php',
        'sObjetoLookUp' : 'db_iframe_cadconvenio',
        'sLabel'        : 'Pesquisar Convênio'
      });

      var oTipoDebito = new DBLookUp($('labelTipoDebito'), $('iCodigoTipoDebito'), $('sTipoDebitoDescricao'), {
        'sArquivo'      : 'func_arretipo.php',
        'sObjetoLookUp' : 'db_iframe_arretipo',
        'sLabel'        : 'Pesquisar Tipo de Débito'
      });

      function js_processar() {

        var aParametros = {
          'sExecucao'          : 'relatorioRetornoCobrancaRegistrada',
          'iCodigoConvenio'    : $F('iCodigoConvenio'),
          'iCodigoTipoDebito'  : $F('iCodigoTipoDebito'),
          'sDataEmissaoInicio' : $F('sDataEmissaoInicio'),
          'sDataEmissaoFim'    : $F('sDataEmissaoFim'),
          'iCodigoArrecadacao' : $F('iCodigoArrecadacao'),
          'iCodigoOcorrencia'  : $F('iCodigoOcorrencia')
        }

        new AjaxRequest(RPC, aParametros, function(oRetorno, lErro) {

          if (lErro) {

            alert(oRetorno.sMensagem);
            return false;
          }

          var oDownloadWindow = new DBDownload();

          oDownloadWindow.addFile(oRetorno.sArquivo, 'Relatório Retorno Cobrança Registrada');
          oDownloadWindow.show();

        }).execute();
      }

    </script>
  </body>
</html>