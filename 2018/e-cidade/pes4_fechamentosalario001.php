<?php
 require_once(modification("libs/db_stdlib.php"));
 require_once(modification("libs/db_utils.php"));
 require_once(modification("libs/db_app.utils.php"));
 require_once(modification("libs/db_conecta.php"));
 require_once(modification("libs/db_sessoes.php"));
 require_once(modification("libs/db_usuariosonline.php"));
 require_once(modification("classes/db_rhfolhapagamento_classe.php"));
 require_once(modification("dbforms/db_funcoes.php"));
 require_once(modification("model/pessoal/folhapagamento/FolhaPagamento.model.php"));
 require_once(modification("model/pessoal/folhapagamento/FolhaPagamentoSalario.model.php"));
 define("MENSAGEM", 'recursoshumanos.pessoal.pes4_fechamentosalario001.');
 db_postmemory($HTTP_POST_VARS);
 
 $lManutenaoSalario = true;
 
 $oPost            = db_utils::postMemory($_POST);
 $rh141_sequencial = '';
 $db_opcao         = 3;
 $botaoProcessar   = '<input name="processar" type="button" value="Processar" disabled>';
 
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

      throw new BusinessException(_M(MENSAGEM . "rotina_desativada"));
    }
     
  } catch (Exception $eException) {
     
     db_msgbox($eException->getMessage()); 
     db_redireciona('corpo.php');
  }

 try {

   db_inicio_transacao();
   $oCompetenciaAtual = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

   if (!FolhaPagamentoSalario::hasFolhaAberta($oCompetenciaAtual) && !isset($_GET['fechado'])) {
     db_msgbox(_M(MENSAGEM . 'folha_aberta_inexistente'));
   }


   if (FolhaPagamentoComplementar::hasFolhaAberta($oCompetenciaAtual) && !isset($_GET['fechado'])) {
     throw new BusinessException(_M(MENSAGEM . 'folha_complementar_aberta')) ;
   }

   /**
    * Verificamos se existe periodo de férias que ainda não foram processados.
    */
   $lPeriodosFeriasNaoProcessados = 0;
   if (PeriodoGozoFerias::hasPeriodoNaoProcessado($oCompetenciaAtual)) {
     $lPeriodosFeriasNaoProcessados = 1;
   }  


   if (isset($oPost->rh141_sequencial)) {

     $oFolhaSalario = new FolhaPagamentoSalario($oPost->rh141_sequencial);
     $oFolhaSalario->setDescricao($oPost->rh141_descricao);
     $oFolhaSalario->setCompetenciaReferencia(new DBCompetencia($oPost->rh141_anoref, $oPost->rh141_mesref));
     $oFolhaSalario->setCompetenciaFolha($oCompetenciaAtual);
     $oFolhaSalario->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo( db_getsession("DB_instit"))); 

     if ($oFolhaSalario->fechar()) {

       db_msgbox(_M(MENSAGEM . 'fechado_com_sucesso'));
       db_fim_transacao();
       db_redireciona("pes4_fechamentosalario001.php?fechado=true");
       exit;
     }
   }

   //Caso tenha folha em aberto, preenche campos.
   if (FolhaPagamentoSalario::hasFolhaAberta($oCompetenciaAtual)) {

     $oFolhaPagamento  = FolhaPagamentoSalario::getFolhaAberta();
     $rh141_sequencial = $oFolhaPagamento->getSequencial();
     $rh141_codigo     = $oFolhaPagamento->getNumero();
     $rh141_descricao  = $oFolhaPagamento->getDescricao();
     $rh141_anoref     = $oFolhaPagamento->getCompetenciaReferencia()->getAno();
     $rh141_mesref     = $oFolhaPagamento->getCompetenciaReferencia()->getMes();
     $botaoProcessar   = '<input name="processar" type="button" value="Processar" id="processar">';
   }
 } catch (Exception $eException) {

   db_fim_transacao(true);
   db_msgbox($eException->getMessage());
 }
 
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <form name="form1" method="post" class="container" action="">
      <fieldset>
        <legend align="left">Fechamento da Folha Salário</legend>
       <?php include(modification("forms/db_frmrhfolhapagamento.php"));?>
      </fieldset>
      <?php
      echo $botaoProcessar;
      db_input('rh141_sequencial', 4, $rh141_sequencial, true, 'hidden', 3);
      ?>
    </form>
    <?php db_menu(); ?>
    <script>
      $('processar').focus();
      
      $('processar').addEventListener("click", function() {

        var lPeriodosFeriasNaoProcessados = <?php echo $lPeriodosFeriasNaoProcessados ?>;

        if (lPeriodosFeriasNaoProcessados) {
          if (!confirm("Existem períodos de férias não processados, deseja continuar?")) {
            return false;
          }
        }

        js_calcularfixo();
      });

      function js_calcularfixo() {

        var ojanela = js_OpenJanelaIframe(
          "",
          "db_calculo",
          "pes4_gerafolha002.php?opcao_gml=g&opcao_geral=10&sCallBack=js_callbackcalculosalario()",
          "Cálculo Financeiro",
          true
          );

        if ( ojanela ) {
          ojanela.setAltura("70%");
          ojanela.setLargura("calc(100% - 10px)");
        }
      };

      function js_callbackcalculosalario() {

        db_calculo.hide();

        var sUrlRPC = "pes4_rhgeracaofolha.RPC.php";
        
        var oDados = {};
            oDados.opcao_geral = 1;
            oDados.opcao_gml   = 'g';

        var oParam                  = new Object();
            oParam.exec             = 'buscaMatriculas';
            oParam.oDadosFormulario = oDados;

        /**
         * Realiza a consulta das matriculas que devem ser cálculadas.
         */
        var oAjax = new Ajax.Request(sUrlRPC, {
            method    : 'post',
            parameters: 'json='+Object.toJSON(oParam),
            onComplete: function(oAjax) {

              var oRetorno    = eval("("+oAjax.responseText.urlDecode()+")");
              var faixa_regis = oRetorno.aServidores.join();
              
              var ojanelaSalario = js_OpenJanelaIframe(
                "",
                "db_calculo",
                "pes4_gerafolha002.php?opcao_gml=m&opcao_geral=1&opcao_filtro=s&faixa_regis="+faixa_regis+"&sCallBack=js_callbackcalculo()",
                "Cálculo Financeiro",
                true
              );

              if ( ojanelaSalario ) {

                ojanelaSalario.setAltura("70%");
                ojanelaSalario.setLargura("calc(100% - 10px)");
              }
            }
        });

      }

      function js_callbackcalculo() {

        db_calculo.hide();
        document.form1.submit();
      }
    </script>
  </body>
</html>
