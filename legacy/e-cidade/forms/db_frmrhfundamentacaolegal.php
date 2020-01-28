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
$oDaoRhfundamentacaolegal->rotulo->label();

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}
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
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Fundamentação Legal</legend>
          <table>
          <tr>
              <td>
                <?php
                  db_input('rh137_sequencial', 11, null, true, 'hidden', $db_opcao, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh137_tipodocumentacao; ?>" >
                <label class="bold" for="rh137_tipodocumentacao" id="lbl_rh137_tipodocumentacao"><?php echo $Srh137_tipodocumentacao; ?>:</label>
              </td>
              <td>
                <?php
                  $x = array('1' => 'Decreto',
                             '2' => 'Decreto Lei',
                             '3' => 'Emenda Constitucional',
                             '4' => 'Instrução Normativa',
                             '5' => 'Lei',
                             '6' => 'Medida Provisória',
                             '7' => 'Nota',
                             '8' => 'Ordem de Serviço',
                             '9' => 'Portaria',
                             '10' => 'Resolução');
                  db_select('rh137_tipodocumentacao', $x, true, $db_opcao, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh137_numero; ?>" >
                <label class="bold" for="rh137_numero" id="lbl_rh137_numero"><?php echo $Srh137_numero; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('rh137_numero', 10, $Irh137_numero, true, 'text', $db_opcao,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh137_datainicio; ?>" >
                <label class="bold" for="rh137_datainicio" id="lbl_rh137_datainicio"><?php echo $Srh137_datainicio; ?>:</label>
              </td>
              <td>
                <?php db_inputdata( 'rh137_datainicio',
                                    @$rh137_datainicio_dia,
                                    @$rh137_datainicio_mes,
                                    @$rh137_datainicio_ano, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh137_datafim; ?>" >
                <label class="bold" for="rh137_datafim" id="lbl_rh137_datafim"><?php echo $Srh137_datafim; ?>:</label>
              </td>
              <td>
                <?php db_inputdata( 'rh137_datafim',
                                    @$rh137_datafim_dia,
                                    @$rh137_datafim_mes,
                                    @$rh137_datafim_ano, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
          </table>
          <fieldset>
          <legend style="font-weight: bold;">Descrição</legend>
           <?php db_textarea('rh137_descricao', 5, 50, $Irh137_descricao, true, 'text', $db_opcao, ""); ?>
          </fieldset>

        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="hidden" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" >
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> onclick="return js_verificaCampos(<?php echo $db_opcao; ?>);" >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

/**
 * Validação para tentativa de colar caracteres especiais e/ou caracteres no campo numérico
 */
  $('rh137_numero').onpaste = function(event) {

     var self = this;
     return setTimeout(function() {
      var lNumeros = new RegExp(/^[0-9]+$/).test(self.value);
        if (!lNumeros) {
           alert('Número deve ser preenchido somente com números!');
           self.value = '';
         }
       }, 5);
     }
     
    function js_verificaCampos(iOpcao) {
      
      var lSubmit     = true;
      var aDataInicio = new Array();
      var aDataFinal  = new Array();
      
      if($F('rh137_datainicio_dia') == "" || $F('rh137_datainicio_mes') == "" || $F('rh137_datainicio_ano') == "" && $F('rh137_datainicio') != "") {

        aDataInicio = $F('rh137_datainicio').split('/');
        $('rh137_datainicio_dia').value = aDataInicio[0];
        $('rh137_datainicio_mes').value = aDataInicio[1];
        $('rh137_datainicio_ano').value = aDataInicio[2];
      }

      if($F('rh137_datafim_dia') == "" || $F('rh137_datafim_mes') == "" || $F('rh137_datafim_ano') == "" && $F('rh137_datafim') != "") {

        aDataFim = $F('rh137_datafim').split('/');
        $('rh137_datafim_dia').value = aDataFim[0];
        $('rh137_datafim_mes').value = aDataFim[1];
        $('rh137_datafim_ano').value = aDataFim[2];
      }
      
      /**
       *  Na exclusão da fundamentação legal, precisa verificar se
       *  ela está vinculada com alguma rubrica.
       */
      if (iOpcao != 1 && iOpcao != 2 && iOpcao != 22) {
        
        var iCodigoFundamentacao = parseInt($('rh137_sequencial').value);
        lSubmit                  = js_verificarVinculo(iCodigoFundamentacao);
      }
      
      return lSubmit;
    }
    
    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe_rhfundamentacaolegal',
                           'func_rhfundamentacaolegal.php?funcao_js=parent.js_preenchepesquisa|rh137_sequencial|rh137_numero|rh137_descricao',
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_rhfundamentacaolegal.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }
    
    /**
     * Verifica se a fundamentação legal possui algum vínculo com as rubricas.
     * 
     * @param {Integer} iCodigoFundamentacao
     * @returns {Boolean}
     */
    function js_verificarVinculo(iCodigoFundamentacao) {
      
      var sUrlRpc = "pes1_rhfundamentacaolegal.RPC.php";
      
      var oParam                  = {};
      oParam.exec                 = "verificarVinculoRubrica";
      oParam.iCodigoFundamentacao = iCodigoFundamentacao;
        
      var oAjaxRequest = new AjaxRequest(sUrlRpc, oParam, js_callbackVerificarVinculo);
      oAjaxRequest.execute();
      
      return false;
    }
    
    /**
     * Tratamento do callback da função js_verificarVinculo()
     * 
     * @param {Object} oRetorno
     * @param {Boolean} lErro
     */
    function js_callbackVerificarVinculo(oRetorno, lErro) {
      
      var sMensagem = oRetorno.message.urlDecode();
      
      if (!lErro) {
        if(oRetorno.lRubricas) {
          if(confirm(sMensagem)) {
            document.form1.submit();
          }
          return false;
        }
        document.form1.submit();
        return false;
      }
      
      alert(sMensagem);
      return false;
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>