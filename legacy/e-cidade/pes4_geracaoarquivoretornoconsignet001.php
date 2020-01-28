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
 * @author $Author: dbrenan.silva $
 * @version $Revision: 1.7 $
 *
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$db_opcao = 1;
$oRotulo  = new rotulocampo();
$oRotulo->label("r11_anousu");
$oRotulo->label("r11_mesusu");

$oGet = db_utils::postMemory($_GET);
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="estilos.css"/>
  </head>
  <body>
    <div class="Container">
      <form name="formulario" method="post">
        <fieldset>
          <legend>Geração do Arquivo de Retorno</legend>
          <table class="form-container">
            <tr>
              <td>
                <label>
                  Competência da Folha:
                </label>
              </td>
              <td>
                <div>
                  <span>
                    <?php
                      db_input("r11_anousu", 4 , 1, true, 'text', $db_opcao, "", "txtAno", "", "", 4);
                    ?>
                  </span>
                  /
                  <span>
                    <?php
                      db_input('r11_mesusu', 2 , 1, true, 'text', $db_opcao, "", "txtMes", "", "", 2);
                    ?>
                  </span>
                </div>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id="btnProcessar" name="btnProcessar" value="Processar">
      </form>
    </div>
    <?php

      $sMensagem  = "Este menu mudou para:\n";
      $sMensagem .= "Pessoal > Procedimentos > Manutenção de Empréstimos Consignados > Convênios > Consignet > Gerar Arquivo de Retorno\n";
      $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

      if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
        db_msgbox($sMensagem);
      }

      /**
       * Carrega as bibliotecas do javascripts
       */
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("AjaxRequest.js");
      db_app::load("widgets/DBDownload.widget.js");

      /**
       * Exibi o menu
       */
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );

    ?>

    <script language="JavaScript" type="text/javascript" >

      const ARQUIVO_MENSAGEM = "recursoshumanos.pessoal.pes4_geracaoarquivoretornoconsignado.json.";
      const sUrl             = 'pes4_geracaoarquivoconsignado.RPC.php';

      document.observe("dom:loaded", function() {
        js_carregarFolhasPagamentos();
      });

      $('btnProcessar').observe("click", function(){
        js_validarFormulario();
      });

      /**
       * A função valida o formulário
       */
      function js_validarFormulario() {

        var iAno = $('txtAno').getValue();
        var iMes = $('txtMes').getValue();

        if (empty(iAno) || empty(iMes)) {

          alert(_M(ARQUIVO_MENSAGEM + "competencia_nao_informada"));
          return false;
        }

        js_processarRetornoArquivo(parseInt(iAno), parseInt(iMes));
      }

      /**
       * Retorna a última competência fechada ou a última folha salário fechada
       */
      function js_carregarFolhasPagamentos() {

        var oParam           = new Object();
            oParam.sExecucao = 'retornarCompetencia';

        var oAjax = new Ajax.Request(sUrl, {
          method    : 'post',
          parameters: 'json='+Object.toJSON(oParam),
          onComplete: function(oAjax) {

            var oRetorno  = eval("("+oAjax.responseText.urlDecode()+")");
            var lErro     = oRetorno.erro;
            var sMensagem = oRetorno.sMessage;

            if (!lErro) {

              $('txtAno').setValue(oRetorno.iAno);
              $('txtMes').setValue(oRetorno.iMes);
            } else {
              $('btnProcessar').disable();
            }

            if (!empty(sMensagem)) {
              alert(sMensagem);
            }
          }
        });
      }

      /**
       * Método faz o processamento e retorna um arquivo txt
       *
       * @param {Integer} iAno
       * @param {Integer} iMes
       */
      function js_processarRetornoArquivo(iAno, iMes) {

        var oParam           = new Object();
            oParam.sExecucao = 'processamentoRetornoArquivoConsignet';
            oParam.iAno      = iAno;
            oParam.iMes      = iMes;

        new AjaxRequest(sUrl, oParam, function(oRetorno) {

          var sMensagem = oRetorno.sMensagem.urlDecode();

          if( oRetorno.iStatus != 1 ) {

            if ( !empty(sMensagem) ) {
              alert( sMensagem.urlDecode() );
            }
            return;
          }

          var sNomeArquivo = oRetorno.sArquivo.urlDecode();
          js_mostrarJanelaDownload(sNomeArquivo);

        }).execute();
      }

      /**
       * Exibe a janela para fazer download do arquivo de retorno do Consignet
       *
       * @param {String} sArquivo
       */
      function js_mostrarJanelaDownload(sArquivo) {

        var oDownload = new DBDownload();

        if( $('window01') ){
          $('window01').outerHTML = '';
        }

        if(!empty(sArquivo)) {

          var sNomeArquivo = sArquivo.split('/')[1];
          oDownload.addFile( sArquivo, sNomeArquivo);
          oDownload.show();
        }
      }

    </script>
  </body>
</html>
