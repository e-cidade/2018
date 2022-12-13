<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhfundamentacaolegal_classe.php");
require_once("dbforms/db_funcoes.php");

define('MENSAGENS', 'recursoshumanos.pessoal.pes1_rhfundamentacaolegal.');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoRhfundamentacaolegal = new cl_rhfundamentacaolegal;
$oDaoRubricas             = new cl_rhrubricas();
$db_botao    = false;
$db_opcao    = 33;
$sPosScripts = "";

if (isset($excluir)) {

  try {
  
    db_inicio_transacao();
    $db_opcao = 3;
    
    /**
     * Primeiramente faz a exclusão da fundamentação legal nas rubricas.
     */
    $sCampos              = "distinct rh27_rubric, rh27_descr, rh27_quant, rh27_cond2, rh27_cond3, rh27_form,   ";
    $sCampos             .= "         rh27_form2, rh27_form3, rh27_formq, rh27_calc1 , rh27_calc2, rh27_calc3,  "; 
    $sCampos             .= "         rh27_tipo, rh27_limdat, rh27_presta, rh27_calcp, rh27_propq, rh27_propi,  ";
    $sCampos             .= "         rh27_obs, rh27_instit, rh27_ativo, rh27_pd, rh27_valorpadrao,             ";
    $sCampos             .= "         rh27_quantidadepadrao, rh27_quantidadepadrao, rh27_complementarautomatica ";
    $sSqlFundamentacao    = $oDaoRhfundamentacaolegal->sql_query_fundamentacao_rubrica($rh137_sequencial, $sCampos);
    $rsFundamentacaoLegal = db_query($sSqlFundamentacao);
    
    if(!$rsFundamentacaoLegal) {
      throw new DBException(_M(MENSAGENS ."erro_buscar_vinculo_rubrica"));
    }
    
    for ($iIndice = 0; $iIndice < pg_num_rows($rsFundamentacaoLegal); $iIndice++) {
      
      $oDadosRubrica = db_utils::fieldsMemory($rsFundamentacaoLegal, $iIndice);
      
      $oDaoRubricas->rh27_rubric                 = $oDadosRubrica->rh27_rubric;
      $oDaoRubricas->rh27_descr                  = $oDadosRubrica->rh27_descr;
      $oDaoRubricas->rh27_quant                  = $oDadosRubrica->rh27_quant;
      $oDaoRubricas->rh27_cond2                  = $oDadosRubrica->rh27_cond2;
      $oDaoRubricas->rh27_cond3                  = $oDadosRubrica->rh27_cond3;
      $oDaoRubricas->rh27_form                   = $oDadosRubrica->rh27_form;
      $oDaoRubricas->rh27_form2                  = $oDadosRubrica->rh27_form2;
      $oDaoRubricas->rh27_form3                  = $oDadosRubrica->rh27_form3;
      $oDaoRubricas->rh27_formq                  = $oDadosRubrica->rh27_formq;
      $oDaoRubricas->rh27_calc1                  = $oDadosRubrica->rh27_calc1;
      $oDaoRubricas->rh27_calc2                  = $oDadosRubrica->rh27_calc2;
      $oDaoRubricas->rh27_calc3                  = $oDadosRubrica->rh27_calc3;
      $oDaoRubricas->rh27_tipo                   = $oDadosRubrica->rh27_tipo;
      $oDaoRubricas->rh27_limdat                 = $oDadosRubrica->rh27_limdat;
      $oDaoRubricas->rh27_presta                 = $oDadosRubrica->rh27_presta;
      $oDaoRubricas->rh27_calcp                  = $oDadosRubrica->rh27_calcp;
      $oDaoRubricas->rh27_propq                  = $oDadosRubrica->rh27_propq;
      $oDaoRubricas->rh27_propi                  = $oDadosRubrica->rh27_propi;
      $oDaoRubricas->rh27_obs                    = $oDadosRubrica->rh27_obs;
      $oDaoRubricas->rh27_instit                 = $oDadosRubrica->rh27_instit;
      $oDaoRubricas->rh27_ativo                  = $oDadosRubrica->rh27_ativo;
      $oDaoRubricas->rh27_pd                     = $oDadosRubrica->rh27_pd;
      $oDaoRubricas->rh27_valorpadrao            = $oDadosRubrica->rh27_valorpadrao;
      $oDaoRubricas->rh27_quantidadepadrao       = $oDadosRubrica->rh27_quantidadepadrao;
      $oDaoRubricas->rh27_complementarautomatica = $oDadosRubrica->rh27_complementarautomatica;
      $oDaoRubricas->rh27_rhfundamentacaolegal   = "";
      
      $sRubrica     = $oDadosRubrica->rh27_rubric;
      $iInstituicao = $oDadosRubrica->rh27_instit; 
      
      $GLOBALS["HTTP_POST_VARS"]["rh27_rhfundamentacaolegal"] = '';

      $oDaoRubricas->alterar($sRubrica, $iInstituicao);
      
      if($oDaoRubricas->erro_status == "0") {
        throw new DBException(_M(MENSAGENS ."erro_excluir_fundamentacao_rubrica"));
      }
      
    }
    
    /**
     * Nesta parte faz a exclusão da fundamentação legal.
     */
    $oDaoRhfundamentacaolegal->rh137_instituicao = db_getsession("DB_instit");
    $oDaoRhfundamentacaolegal->excluir($rh137_sequencial);
    
    if ($oDaoRhfundamentacaolegal == "0") {
      throw new DBException(_M(MENSAGENS ."erro_excluir_fundamentacao"));
    }
    
    db_fim_transacao();
  
    $sPosScripts .= 'alert("' . $oDaoRhfundamentacaolegal->erro_msg . '");' . "\n";
    
    if ($oDaoRhfundamentacaolegal->erro_status != "0") {
      $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }
  
  } catch (Exception $eErro) {
    
    db_fim_transacao(true);
    $sPosScripts .= 'alert("'. $eErro->getMessage() .'");';   
  }
  
} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoRhfundamentacaolegal->sql_record( $oDaoRhfundamentacaolegal->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh137_tipodocumentacao", true, 1, "rh137_tipodocumentacao", true);';

include("forms/db_frmrhfundamentacaolegal.php");
?>
