<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhfolhapagamento_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/folhapagamento/FolhaPagamento.model.php");
require_once("model/pessoal/folhapagamento/FolhaPagamentoComplementar.model.php");
define("MENSAGEM", 'recursoshumanos.pessoal.pes4_fechamentocomplementar001.');

$oPost            = db_utils::postMemory($_POST);
db_postmemory($HTTP_POST_VARS);
$rh141_sequencial = '';
$db_opcao         = 3;
$botaoProcessar   = '<input name="processar" type="button" value="Processar" disabled id="processar">';

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
     
     throw new \ECidade\V3\Extension\Exceptions\ResponseException($eException->getMessage()); 
     db_redireciona('corpo.php');
  }

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

  if (!FolhaPagamentoComplementar::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) ) && !isset($_GET['fechado']) ) {
    db_msgbox(_M(MENSAGEM . 'folha_aberta_inexistente'));
  }

  db_inicio_transacao();

  if (isset($oPost->rh141_sequencial)) {

    try{
      
      $oFolhaComplementar = new FolhaPagamentoComplementar($oPost->rh141_sequencial);
      $oFolhaComplementar->setNumero($oPost->rh141_codigo);
      $oFolhaComplementar->setDescricao($oPost->rh141_descricao);
      $oFolhaComplementar->setCompetenciaReferencia(new DBCompetencia($oPost->rh141_anoref, $oPost->rh141_mesref));
      $oFolhaComplementar->setCompetenciaFolha( new DBCompetencia( DBPessoal::getAnofolha(), DBPessoal::getMesFolha() ) );
      $oFolhaComplementar->setInstituicao( InstituicaoRepository::getInstituicaoByCodigo( db_getsession("DB_instit") ) ); 
      $oFolhaComplementar->fechar();

      db_msgbox(_M(MENSAGEM . 'fechado_com_sucesso') );
      db_fim_transacao();
      db_redireciona("pes4_fechamentocomplementar001.php?fechado=true");
    } catch(Exception $oException) {

      db_fim_transacao(true);
      db_msgbox($oException->getMessage);
      db_redireciona("pes4_fechamentocomplementar001.php?fechado=false");
    }

  }

  //Caso tenha folha em aberto, preenche campos.
  if (FolhaPagamentoComplementar::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) )) {

    $oFolhaPagamento  = FolhaPagamentoComplementar::getFolhaAberta();
    $rh141_sequencial = $oFolhaPagamento->getSequencial();
    $rh141_codigo     = $oFolhaPagamento->getNumero();
    $rh141_descricao  = $oFolhaPagamento->getDescricao();
    $rh141_anoref     = $oFolhaPagamento->getCompetenciaReferencia()->getAno();
    $rh141_mesref     = $oFolhaPagamento->getCompetenciaReferencia()->getMes();
    $botaoProcessar   = '<input name="processar" type="button" value="Processar" id="processar">';

    if ( !$oFolhaPagamento->pesquisarPonto() ) {

      db_msgbox(_M(MENSAGEM . 'folha_sem_evento_financeiro'));
      $botaoProcessar   = '<input name="processar" type="button" value="Processar" disabled>';
    }
  }
  db_fim_transacao();
} catch ( Exception $eException ) {
  db_fim_transacao(true);
  db_msgbox( $eException->getMessage() );
}
 
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <form name="form1" method="post" class="container" action="">
      <fieldset>
       <legend align="left">Fechamento da Folha Complementar</legend>
       <?php include("forms/db_frmrhfolhapagamento.php");?>
       </fieldset>
       <?php
         echo $botaoProcessar;
         db_input('rh141_sequencial', 4, $rh141_sequencial, true, 'hidden', 3);
        ?>
    </form>
    <?php db_menu(); ?>
    <script>
      js_tabulacaoforms("form1", "rh141_codigo", true, 1, "rh141_codigo", true);

      $('processar').focus();
      var oBotao = $('processar').addEventListener("click", function() {
        js_calcularFolha();
      });

      function js_calcularFolha() {

        var oJanela = js_OpenJanelaIframe(
          "",
          "db_calculo",
          "pes4_gerafolha002.php?opcao_gml=g&opcao_geral=8&sCallBack=js_callbackCalculo()",
          "Cálculo Folha Complementar",
          true
        );

        if ( oJanela ) {
          oJanela.setAltura("70%");
          oJanela.setLargura("calc(100% - 10px)");
        }
      };

      function js_callbackCalculo() {

        db_calculo.hide();
        document.form1.submit();
      }
    </script>
  </body>
</html>
