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
  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_app.utils.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));

  define('MENSAGENS', "recursoshumanos.pessoal.pes4_processamentodadosponto.");
  $sPosScripts = '';

  $oGet    = db_utils::postMemory($_GET);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <fieldset>
        <legend>Processar Dados do Ponto</legend>
        <table cellpadding="0" cellspacing="0" class="form-container">
          <tr>
            <td width="85px">
              <label>Competência: </label>
            </td>
            <td id="containerCompetencia">
              
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btn_processar" onclick="js_processar()" value="Processar" />
    </div>  
   
    <?php 

      $sMensagem  = "Este menu mudou para:\n";
      $sMensagem .= "Pessoal > Procedimentos > Manutenção do Ponto > Processamento de Dados do Ponto\n";
      $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

      if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
        db_msgbox($sMensagem);
      }
    ?>
    <script type="text/javascript">

      (function(){

        /**
         * Instancia o componente CompetenciaFolha com os campos ano/mes desabilitados
         */
        var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
            oCompetenciaFolha.renderizaFormulario($('containerCompetencia'));
            oCompetenciaFolha.desabilitarFormulario();
      })();

      /**
       * Processa os dados do forumalario, atraves do RPC da tela;
       * @return void
       */
      function js_processar(){

        js_divCarregando("Aguarde, processando...", "msgBox");

        var sUrlRPC         = "pes4_processamentodadosponto.RPC.php";
        var oParam          = new Object();
        oParam.exec         = "processarPonto";

        var oAjax = new Ajax.Request(sUrlRPC, {
                                                method:'post',
                                                parameters:'json='+Object.toJSON(oParam),
                                                onComplete: js_retornoProcessamento
                                              });

      }

      /**
       * Trata o retorno do RPC executado pela função js_processar.
       * @param  Object oAjax
       * @return void
       */
      function js_retornoProcessamento(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("("+oAjax.responseText+")");

        alert(oRetorno.sMessage.urlDecode());
      } 

    </script>
    <?php db_menu(); ?>
    <?php 

      try {

        validarPontoInicializado(new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()));

      } catch (Exception $oException) {

        /**
         * Desabilita o botão processar.
         */
        $sPosScripts .= "$('btn_processar').disable(); ";
        $sPosScripts .= "alert('" . $oException->getMessage() . "');\n";
      }

      function validarPontoInicializado( DBCompetencia $oCompetencia ) {

        /**
         * Se utiliza a estrutura nova de complementar verifica 
         * se existe uma folha de salário aberta.
         */
        if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {

          $lFolhaAberta = FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_SALARIO, $oCompetencia);

          if (!$lFolhaAberta){
            throw new BusinessException(_M(MENSAGENS . 'salario_fechado'));
          }
          return true;
        }

        /**
         * Se não utilizar a estrutura nova de complementar
         * verifica se existe dados no pontofs para a competência, 
         * se existir é porque o ponto foi inicializado.
         */
        $oDaoPontoFs = new cl_pontofs();
        $sSqlPontoFs = $oDaoPontoFs->sql_query_file ( $oCompetencia->getAno(), 
                                                      $oCompetencia->getMes(), 
                                                      null, 
                                                      null, 
                                                      "r10_rubric"
                                                    );
        $rsPontoFs = db_query($sSqlPontoFs);

        if (!$rsPontoFs) {
          throw new DBException(_M(MENSAGENS . 'erro_ponto'));
        }

        if (pg_num_rows($rsPontoFs) == 0) {
          throw new BusinessException(_M(MENSAGENS . 'erro_ponto_nao_inicializado'));
        }
        
        return true;
      }
      echo "<script>{$sPosScripts}</script>";
    ?>
  </body>
</html>