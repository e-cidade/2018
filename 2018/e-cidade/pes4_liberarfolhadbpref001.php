<?php

/**
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
 *
 * @author Luiz Marcelo Schmitt <luiz.marcelo@dbseller.com.br>
 * @version $Revision: 1.10 $
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';

define("ARQUIVO_MENSAGEM", "recursoshumanos.pessoal.pes4_liberarfolhadbpref.");

try {

   /**
   *  Verifica se o parametro r11_suplementar na tabela cfpess está ativo.
   */
  if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()){

     /**
     * Desativa o formulário
     */
    $lDisabled = true;
    $db_opcao  = 3;

    throw new BusinessException(_M(ARQUIVO_MENSAGEM . "rotina_desativada"));
  }
     
} catch (Exception $eException) {
     
   db_msgbox($eException->getMessage()); 
   db_redireciona('corpo.php');
}

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <link rel="stylesheet" href="estilos.css">
  <link rel="stylesheet" href="estilos/grid.style.css">
</head>
<body onload="js_carregar()">
  <form name="form" id="form-libera-folhas" class="container">
    <fieldset>
      <legend>Liberar Folhas</legend>
      <table class="form-container">
        <tr>
          <td colspan="4" id="msgboard">
          </td>
        </tr>        
        <tr>
          <td width="20%"></td>
          <td width="30%">
            <div>
              <input type="checkbox" id="adiantamento" name="tipofolha">
              <label for="adiantamento">Adiantamento</label>
            </div>
            <div>
              <input type="checkbox" id="13salario" name="tipofolha">
              <label for="13salario">13º Salário</label>
            </div>
            <div>
              <input type="checkbox" id="rescisao" name="tipofolha">
              <label for="recisao">Rescisão</label>
            </div>
          </td>
          <td width="30%">
            <div>
              <input type="checkbox" id="complementar" name="tipofolha" disabled>
              <label for="complementar" style="color: #666">Complementar</label>
            </div>
            <div>
              <input type="checkbox" id="salario" name="tipofolha" disabled>
              <label for="salario" style="color: #666">Salário</label>
            </div>
            <div>
              <input type="checkbox" id="suplementar" name="tipofolha" disabled>
              <label for="suplementar" style="color: #666">Suplementar</label>
            </div>
          </td>
          <td width="20%"></td>
        </tr>
      </table>
    </fieldset>
    <input name="salvar" id="salvar" type="button" value="Salvar" onclick="js_fecharFolhas()">
  </form>
  <?php db_menu(); ?>
  <script src="scripts/scripts.js"></script>
  <script src="scripts/strings.js"></script>
  <script src="scripts/prototype.js"></script>
  <script src="scripts/arrays.js"></script>
  <script src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script>
    const ARQUIVO_MENSAGEM = 'recursoshumanos.pessoal.pes4_liberarfolhadbpref.';

    var oMessageBoard = new DBMessageBoard('msgboard1','Atenção!',_M(ARQUIVO_MENSAGEM + 'liberar_folha_suggest') + "</br>&nbsp;",$('msgboard'));
    oMessageBoard.divContent.style.height = '80px';
    oMessageBoard.divContent.style.border = '2px groove white';
    oMessageBoard.show();

    /**
     * Marca os tipos de folhas selecionadas
     */ 
    function js_carregar() {

      var oParam  = new Object();
      oParam.exec = 'getCarregarFolhas';
      var oAjax   = new Ajax.Request('pes4_liberarfolhadbpref.RPC.php', {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function(oAjax) {

          var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
          if (typeof(oRetorno.dados) != "undefined") {

            for (var sIndice in oRetorno.dados ) {

              var lAtivo = oRetorno.dados[sIndice];
              $(sIndice).checked = lAtivo;

              // Disabilita apenas os pontos de adiantamento, rescisão ou 13º salário
              if (sIndice == 'adiantamento' || sIndice == 'rescisao' || sIndice == '13salario') {

                if (lAtivo == null) {
                  $(sIndice).disable();
                  $(sIndice).next('label').setAttribute("style", "color: #666");
                } else {
                  $(sIndice).enable();
                  $(sIndice).next('label').setAttribute("style", "color: #000");
                }
              }
            }
          }

          if (oRetorno.message != '') {

            alert(oRetorno.message);
            $('salvar').disable();
          } else {
            $('salvar').enable();
          }
        }
      });
    }

    /**
     * Chama o RPC do arquivo para fechar as folhas de adiantamento ou 13º salário ou rescisão
     */
    function js_fecharFolhas() {
      
      var oParam          = new Object();
      oParam.exec         = 'salvar';
      oParam.selecionados = {};
      
      if (!$('adiantamento').disabled) {
        oParam.selecionados.adiantamento = $('adiantamento').checked;
      }
      
      if (!$('13salario').disabled) {
        oParam.selecionados.salario13 = $('13salario').checked;
      }
      
      if (!$('rescisao').disabled) {
        oParam.selecionados.rescisao = $('rescisao').checked;
      }

      var oAjax = new Ajax.Request('pes4_liberarfolhadbpref.RPC.php', {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function(oAjax) {

          var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
          if (oRetorno.status) {

            if (oRetorno.message.length) {
              sMensagem = oRetorno.message;
            } else {
              sMensagem = _M(ARQUIVO_MENSAGEM + 'nenhuma_atualizacao');
            }
          } else {
            sMensagem = (oRetorno.message != '') ? oRetorno.message : _M(ARQUIVO_MENSAGEM + 'liberar_folha_erro');
          }
          alert(sMensagem);
        }
      });
    }
  </script>
</body>
</html>
