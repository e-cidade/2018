<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

  require_once ("libs/db_stdlib.php");
  require_once ("libs/db_conecta.php");
  require_once ("libs/db_sessoes.php");
  require_once ("libs/db_usuariosonline.php");
  require_once ("libs/db_app.utils.php");
  require_once ("libs/db_utils.php");
  require_once ("dbforms/db_funcoes.php");

  $oGet  = db_utils::postMemory($_GET);
  $lReprocessamento = (isset($oGet->lReprocessar));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <div class="container" style="width:500px !important;">

    <form method="post" name="form1" action="iss1_processararquivosimplesnacional004.php">

      <fieldset>

        <legend><?php echo ($lReprocessamento ? 'Rep' : 'P'); ?>rocessar Arquivo de Optantes do Simples Nacional</legend>

        <table class="form-container">
          <tr>
            <td width="80px">Arquivo:</td>
            <td>
              <input type="hidden" value="<?php echo $lReprocessamento; ?>" id="lReprocessamento" name="lReprocessamento"></input>
              <?php
                db_select('q64_sequencial', array('' => 'SELECIONE'), '', 1, "onchange='js_getDataVencimento(this.value);'");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" width="80px">Data Limite de Vencimento dos Débitos:
              <?php
                db_inputdata('q64_datalimitevencimentos', '', '', '', true, 'text', 1);
              ?>
            </td>
          </tr>
        </table>

      </fieldset>

      <input type="button" onclick="js_processar(this)" value="<?php echo ($lReprocessamento ? 'Rep' : 'P'); ?>rocessar"/>

    </form>

  </div>
  <?php
    db_menu( db_getsession("DB_id_usuario"),
             db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),
             db_getsession("DB_instit") );
  ?>
  </body>

  <script type="text/javascript">
    var sUrlRPC    = 'iss1_processararquivosimplesnacional.RPC.php',
        MENSAGENS  = 'tributario.issqn.iss4_processararquivosimplesnacional001.';

    (function() {
      js_divCarregando('Aguarde, Carregando Arquivos...', 'oCarregando');

      /**
       * Busca os arquivos existentes na base
       */
      var oParametros               = new Object();
      var oDadosRequisicao          = new Object();

      oParametros.sExecucao         = 'getArquivos';
      oParametros.lReprocessamento  = $('lReprocessamento').value;

      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax) {

        js_removeObj('oCarregando');

        var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

        if (oRetorno.iStatus == "2") {
          alert(oRetorno.sMensagem);
          return;
        }

        var oArquivos = $('q64_sequencial');

        oRetorno.aArquivos.each(function(oArquivo) {

          oOpcao       = document.createElement("option");
          oOpcao.value = oArquivo.iSequencial;
          oOpcao.text  = oArquivo.sLabel;

          oArquivos.appendChild(oOpcao);
        });
      }

      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
    })();

    function js_getDataVencimento(iArquivo) {
      var oDataLimiteVencimento      = $('q64_datalimitevencimentos'),
          oDatepicker                = $$('[name="dtjs_q64_datalimitevencimentos"]')[0];

      oDataLimiteVencimento.value    = '';
      oDataLimiteVencimento.readOnly = false;
      oDataLimiteVencimento.classList.remove('readonly');

      oDatepicker.style.visibility = 'visible';

      if (iArquivo == '') {
        return false;
      }

      js_divCarregando('Aguarde, Carregando Arquivo...', 'oCarregando');

      var oParametros               = new Object();
      var oDadosRequisicao          = new Object();

      oParametros.sExecucao         = 'getDataVencimento';
      oParametros.iArquivo          = iArquivo;

      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);
      oDadosRequisicao.onComplete   = function(oAjax) {

        js_removeObj('oCarregando');

        var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

        if (oRetorno.iStatus == "2") {
          alert(oRetorno.sMensagem);
          return;
        }

        var sVisibility = 'visible';

        if (oRetorno.lProcessado && !$('lReprocessamento').value) {
          sVisibility = 'hidden';
          oDataLimiteVencimento.readOnly = true;
          oDataLimiteVencimento.classList.add('readonly');
        }

        oDatepicker.style.visibility = sVisibility;
        oDataLimiteVencimento.value = oRetorno.dtData;
      }

      var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
    }

    function js_processar() {
      if ($('q64_sequencial').value == '') {
        alert(_M( MENSAGENS + 'campo_obrigatorio', {sCampo : 'Arquivo'}));
        return false;
      } 

      if ($('q64_datalimitevencimentos').value == '') {
        alert(_M( MENSAGENS + 'campo_obrigatorio', { sCampo : 'Data Limite de Vencimento dos Débitos'}) );
        return false;
      }

      if ($('lReprocessamento').value && !confirm(_M( MENSAGENS + 'confirma_reprocessamento'))) {
        return false;
      }

      $$('[name="form1"]')[0].submit();
    }
  </script>
</html>