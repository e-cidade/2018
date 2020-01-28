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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <center>
      <form method="POST" action="cai4_devolucaoadiantamento002.php" id="frmDevolucaoAdiantamento">
        <fieldset style="width: 490px;">
          <legend>Devolução de Adiantamento</legend>

          <table class="table-default" style="width: 490px;">
            <tr style="display: none">
              <td>
                <?php db_input('iSequencialEmpPresta', 10, null, true, 'hidden'); ?>
                <?php db_input('iNumeroEmpenho', 10, null, true, 'hidden'); ?>
              </td>
            </tr>
            <tr>
              <td><label for="iCodigoMovimento">
                <?php 
                  db_ancora( 'Movimento:', 
                             "pesquisaCodigoMovimento();", 
                             1 ); 
                ?></label>
              </td>
              <td>
                <?php db_input('iCodigoMovimento', 10, null, true, "text", 3, "", "", "", "width: 80px;"); ?>
              </td>
              <td style="text-align: right;"><label for="dtEmissao"><strong>Emissão:</strong></label></td>
              <td style="width: 1%; padding-right: 10px;">
                <?php db_input('dtEmissao', 10, null, true); ?>
              </td>
            </tr>

            <tr>
              <td><label for="iCodigoEmpenho"><strong>Empenho:</strong></label></td>
              <td>
                <?php db_input('iCodigoEmpenho', 15, null, true, "text", 3, "", "", "", "width: 80px;"); ?>
              </td>
            </tr>

            <tr>
              <td>
                <label for="iFornecedorCgm"><strong>Fornecedor:</strong></label>
              </td>
              <td colspan="3">
                <?php db_input('iFornecedorCgm', 10, null, true, "text", 3, "", "", "", "width: 80px;"); ?>
                <?php db_input('sNomeFornecedor', 10, null, true, "text", 3, "", "", "", "width: 300px;"); ?>
              </td>
            </tr>

            <tr>
              <td>
                <label for="iTipoDevolucao"><strong>Devolução por:</strong></label>
              </td>
              <td colspan="3">
                <select id="iTipoDevolucao" style="width: 384px;">
                  <option value='1'>Arrecadação de Receita</option>
                  <option value='2'>Estorno de Pagamento</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
        <div class="action-buttons-container">
          <input type="submit" value="Continuar" id="btnContinuar">
        </div>
      </form>
    </center>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    /**
     * Abre a func de pesquisa do movimento
     */
    function pesquisaCodigoMovimento() {
        js_OpenJanelaIframe( 'top.corpo', 
                             'db_iframe_empagemov', 
                             'func_empagemov.php?chave_empenho_conferido=1&funcao_js=parent.exibeCodigoMovimento|e81_codmov',
                             'Pesquisa Movimento', true, '25', '10', '775', '450' );
    }

    /**
     * Pega o retorno da func e carrega os dados do movimento
     *
     * @param Integer iCodigo
     * @param boolean lErro
     */
    function exibeCodigoMovimento(iCodigo, lErro) {
      db_iframe_empagemov.hide();

      if (lErro) {
        iCodigo = '';
      }

      $('iCodigoMovimento').value = iCodigo;

      js_divCarregando( _M("financeiro.caixa.cai4_devolucaoadiantamento001.carregando_empenho"), 'oCarregamento');

      var oParam                     = new Object();
          oParam.iCodigoMovimentacao = iCodigo;
          oParam.exec                = 'getDadosEmpenho';
          new Ajax.Request ("cai4_devolucaoadiantamento004.RPC.php", {
                                  method     : 'post',  
                                  parameters : 'json=' + Object.toJSON(oParam),
                                  onComplete : function(oRetorno) {

                                    js_removeObj('oCarregamento');

                                    var oJsonRetorno = JSON.parse( oRetorno.responseText.urlDecode() );

                                    if (oJsonRetorno.iStatus == 2) {

                                      $('iCodigoMovimento').value = '';
                                      preencheCampos(null);

                                      alert( oJsonRetorno.sMessage );
                                    } else {

                                      preencheCampos(oJsonRetorno);
                                    }
                                  }
                                });

    }

    /**
     * Preenche os valores dos movimento nos campos da tela
     *
     * @param Object oValores
     */
    function preencheCampos(oValores) {

      $('iSequencialEmpPresta').value = oValores ? oValores.iSequencialEmpPresta : '';
      $('iNumeroEmpenho').value       = oValores ? oValores.iNumeroEmpenho : '';
      $('dtEmissao').value            = oValores ? oValores.dtEmissao : '';
      $('iCodigoEmpenho').value       = oValores ? oValores.iCodigoEmpenho+"/"+oValores.iAnoEmpenho : '';
      $('iFornecedorCgm').value       = oValores ? oValores.iCgmFornecedor : '';
      $('sNomeFornecedor').value      = oValores ? oValores.sNomeFornecedor : '';

      validaTipoDevolucao( (oValores ? oValores.lHabilitaEstorno : true) );
    }

    /**
     * Valida o tipo de devolução que pode ser selecionado
     *
     * @param boolean lHabilitaEstorno
     */
    function validaTipoDevolucao(lHabilitaEstorno) {
      var oTipoDevolucao = $('iTipoDevolucao');

      if (!lHabilitaEstorno) {
        oTipoDevolucao.value = 1;
        oTipoDevolucao.setAttribute('disabled', 'true');
      } else {
        oTipoDevolucao.removeAttribute('disabled');
      }
    }

    /**
     * Verifica se deve redirecionar para "cai4_devolucaoadiantamento002.php" ou "emp1_emppagamentoestornanota002.php"
     */
    $('frmDevolucaoAdiantamento').observe('submit', function(e) {

      if ($F('iSequencialEmpPresta') == '') {        

        e.preventDefault();
        alert( _M("financeiro.caixa.cai4_devolucaoadiantamento001.movimento_nao_selecionado") );
        return false;

      } else {

        if ($('iTipoDevolucao').value == 2) {

          e.preventDefault();
          top.corpo.location = 'emp1_emppagamentoestornanota002.php?e81_codmov=' + $F('iCodigoMovimento')
                               + '&pag_ord=true&origem_devolucao=true';
          return false;
        }
      }
    });

  </script>
</html>