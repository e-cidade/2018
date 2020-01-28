<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/ppa.model.php"));
require_once(modification("libs/JSON.php"));
require_once(modification('model/ppaVersao.model.php'));

$aParametros         = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
$oParametroOrcamento = $aParametros[0];

$oGet                = db_utils::postMemory($_GET);
$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass;
$oRetorno->status    = 1;
$oRetorno->message   = "";
$oRetorno->itens     = array();

$sNomeTabelaParametroMacroEconomico = 'orccenarioeconomicoconplano';

if (USE_PCASP) {
  $sNomeTabelaParametroMacroEconomico = 'orccenarioeconomicoconplanoorcamento';
}
if ($oParam->exec == "getParametros") {


  $aTipoCalculo = array("1" => "Pela média histórica",
                        "2" => "Pela reestimativa exercício atual");

  if ($oParam->params->fonte != "") {

    $sSqlParametros  = "select o03_descricao, ";
    $sSqlParametros .= "       o03_anoreferencia,";
    $sSqlParametros .= "       o03_anoorcamento,";
    $sSqlParametros .= "       o03_sequencial, ";
    $sSqlParametros .= "       o03_valorparam, ";
    $sSqlParametros .= "       orccenarioeconomicoconplano.* ";
    $sSqlParametros .= "  from orccenarioeconomicoparam ";
    $sSqlParametros .= "       left join orccenarioeconomicoconplano on o04_orccenarioeconomicoparam = o03_sequencial";
    $sSqlParametros .= "       and o04_conplano = '{$oParam->params->conplano}' ";
    $sSqlParametros .= "       and o04_anousu in (select generate_series(".db_getsession("DB_anousu").",".db_getsession("DB_anousu").+ppa::ANOS_PREVISAO_CALCULO.",1))";
    $sSqlParametros .= " where o03_instit = ".db_getsession("DB_instit");
    $sSqlParametros .= " order by o03_sequencial,o03_anoreferencia";
    $rsParametros    = db_query(analiseQueryPlanoOrcamento($sSqlParametros));

    $iNumeroParametros = pg_num_rows($rsParametros);

    if ($rsParametros && $iNumeroParametros > 0) {

      $aColecaoParametros = array();
      for ($iParametro = 0; $iParametro < $iNumeroParametros; $iParametro++ ) {

        $oParametro               = db_utils::fieldsMemory($rsParametros, $iParametro, false, false, true);

        switch ($oParametro->o04_tipocalculo) {
          case 2: $sTipoCalculo = $aTipoCalculo[2];
          break;

          default:$sTipoCalculo = $aTipoCalculo[1];
          break;
        }

        $oParametro->sTipoCalculo = urlencode($sTipoCalculo);
        $aColecaoParametros[]     = $oParametro;
      }

      $oRetorno->status = 1;
      $oRetorno->itens  = $aColecaoParametros;

    }

  } else {

    $oRetorno->status = 2;
    $oRetorno->message = "preencha a fonte";

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "salvarParametros") {

  db_inicio_transacao();
  $oDaoParametrosReceita = db_utils::getDao($sNomeTabelaParametroMacroEconomico);
  if ($oParam->iTipo == 1) {

    $oDaoOrcFonte = db_utils::getDao("orcfontes");
    $sSql = $oDaoOrcFonte->sql_query_previsao(null,
                                              null,
                                              "o57_codfon as codigo_conplano",
                                              null,
                                              "o57_anousu = ".db_getsession("DB_anousu")."
                                              and o57_fonte like '".ppa::criaContaMae($oParam->iEstrutural)."%'	"
                                              );


  } else if ($oParam->iTipo == 2) {

    $oDaoOrcElemento = db_utils::getDao("orcelemento");
    $sSql = $oDaoOrcElemento->sql_query_conplano(null,
                                      null,
                                      "distinct c60_codcon as codigo_conplano",
                                      null,
                                      "o56_anousu=". db_getsession("DB_anousu")."
                                      and c60_estrut like '".ppa::criaContaMae($oParam->iEstrutural)."%'");
  }
  $rsIncluir = db_query($sSql);
  if ($rsIncluir) {

    $aFontes = db_utils::getCollectionByRecord($rsIncluir);
    foreach ($aFontes as $oFonte) {

      $oDaoParametrosReceita->excluir(null, "o04_conplano  = {$oFonte->codigo_conplano}");
      if ($oDaoParametrosReceita->erro_status == 0) {

         $oRetorno->message  = urlencode("{$oDaoParametrosReceita->erro_status}");
         $oRetorno->status   = 2;
         exit;
      }
      if ($oRetorno->status != 2) {

        $iTipoCalculo = $oParam->iTipoCalculo;

        foreach ($oParam->aParametros as $oParametro) {

          $sSqlConplano  = "select 1 from conplano";
          $sSqlConplano .= " where c60_codcon = {$oFonte->codigo_conplano}";
          $sSqlConplano .= "   and c60_anousu = {$oParametro->o03_anoorcamento}";
          $rsConplano    = db_query(analiseQueryPlanoOrcamento($sSqlConplano)) ;

          if ($rsConplano && pg_num_rows($rsConplano) > 0) {

            $oDaoParametrosReceita->o04_orccenarioeconomicoparam = $oParametro->o03_sequencial;
            $oDaoParametrosReceita->o04_anousu                   = $oParametro->o03_anoorcamento;
            $oDaoParametrosReceita->o04_conplano                 = $oFonte->codigo_conplano;
            $oDaoParametrosReceita->o04_tipocalculo              = $iTipoCalculo;
            $oDaoParametrosReceita->incluir(null);

            if ($oDaoParametrosReceita->erro_status == 0) {

              $oRetorno->message  = urlencode("{$oDaoParametrosReceita->erro_status}");
              $oRetorno->status   = 2;
              break;
            }
          }
        }
      }
    }
  }
  if ($oRetorno->status == 2) {
    db_fim_transacao(true);
  } else {
    db_fim_transacao(false);
  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "getParametros") {



  if ($oParam->params->fonte != "") {

    $sSqlParametros  = "select o03_descricao, ";
    $sSqlParametros .= "       o03_anoreferencia,";
    $sSqlParametros .= "       o03_anoorcamento,";
    $sSqlParametros .= "       o03_sequencial, ";
    $sSqlParametros .= "       o04_orccenarioeconomicoparam ";
    $sSqlParametros .= "  from orccenarioeconomicoparam ";
    $sSqlParametros .= "       left join orccenarioeconomicoconplano on o04_orccenarioeconomicoparam = o03_sequencial";
    $sSqlParametros .= "       and o04_conplano = '{$oParam->params->conplano}' and o04_anousu = o03_anoorcamento";
    $sSqlParametros .= " where o03_instit = ".db_getsession("DB_instit");
    $sSqlParametros .= " order by o03_anoreferencia,o03_sequencial";
    $rsParametros    = db_query(analiseQueryPlanoOrcamento($sSqlParametros));
    if ($rsParametros && pg_num_rows($rsParametros) > 0) {

      $oRetorno->status = 1;
      $oRetorno->itens  = db_utils::getCollectionByRecord($rsParametros, false, false, true);

    }
  } else {

    $oRetorno->status = 2;
    $oRetorno->message = "preencha a fonte";

  }

} else if ($oParam->exec == "ProcessaEstimativa") {


  try {

    db_inicio_transacao();
    $oPPA      = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    $iAnoIncio =  $oParam->iAnoInicio - ppa::ANOS_PREVISAO_CALCULO;

    /**
     * Processamos a base de calculo.
     */
    if ($oParam->lProcessaBase) {

      for ($iAno = $iAnoIncio; $iAno < $oParam->iAnoInicio; $iAno++) {
         $oPPA->processaBaseCalculo($iAno);
      }
    }
    /*
     * Processamos a estimativa
     */
    if ($oParam->lProcessaEstimativa) {

      if (isset($oParam->lImportar) && $oParam->lImportar && $oParam->iPerspectiva != "") {
        $oPPA->oObjeto->importarDadosPerspectiva($oParam->iPerspectiva);
      } else {
        $oPPA->processarEstimativasGlobais($oParam->iAnoInicio, $oParam->iAnoFim);
      }
    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode("Erro [".$eErro->getCode()."] ".$eErro->getMessage());
    db_fim_transacao(true);

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "getQuadroEstimativa") {

	/**
	 * @todo - remover
	 * Ativa PCASP quando arquivo de configuração com ano do PCASP existir
	 */
	if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
		$_SESSION["DB_use_pcasp"] = "t";
	}

   $oPPA                  = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
   try {

     $oRetorno->itens       = $oPPA->getQuadroEstimativas($oParam->estrutural);
     $oRetorno->status      = 1;
     $oRetorno->message     = "";

   } catch (Exception $eErro) {

     $oRetorno->status      = 2;
     $oRetorno->message     = urlencode("Erro[".$eErro->getCode()."] - " .$eErro->getMessage());

   }

   /**
    * @todo - remover
    */
   if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
   	$_SESSION["DB_use_pcasp"] = "f";
   }

   echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "saveEstimativa") {

	/**
	 * @todo - remover
	 * Ativa PCASP quando arquivo de configuração com ano do PCASP existir
	 */
	if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
		$_SESSION["DB_use_pcasp"] = "t";
	}

  $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
  try {

    $lContas = true;
    $oRetorno->valor                = $oParam->nValor;
    $oRetorno->iAno                 = $oParam->iAno;
    $oRetorno->lDeducao             = $oParam->lDeducao;
    $oRetorno->nValorOriginal       = $oParam->nValorOriginal;
    $oRetorno->lBase                = $oParam->lBase;
    $oRetorno->lAtualizaEstimativas = $oParam->lAtualizaEstimativas;
    $oRetorno->iConcarPeculiar      = "{$oParam->iConcarPeculiar}";
    $oRetorno->iCodCon              = $oParam->iCodCon;
    db_inicio_transacao();
    if ($oParam->lDesdobrar) {

      $aDesdobramentos      = $oPPA->getDesdobramentos($oPPA->criaContaMae($oParam->iEstrutural),$oParam->iAno);
      $nValorTotal          = 0;
      $iTotalDesdobramentos = count($aDesdobramentos);
      $iDesdobramentoAtual  = 0;
      foreach ($aDesdobramentos as $oDesbramento) {

        $iDesdobramentoAtual++;

        $nValor       = round($oParam->nValor * ($oDesbramento->o60_perc/100),2);
        $nValorTotal += $nValor;
        if ($iDesdobramentoAtual == $iTotalDesdobramentos) {

          $nDiferenca  = $oParam->nValor - $nValorTotal;
          $nValor     += $nDiferenca;
        }
        $oPPA->saveEstimativa($oDesbramento->o57_codfon, $oParam->iAno,$nValor, "{$oParam->iConcarPeculiar}");
        $oConta = new stdClass();
        $oConta->iEstrutural     = $oDesbramento->o57_fonte;
        $oConta->iConcarPeculiar = "{$oParam->iConcarPeculiar}";
        $oConta->valor           = round($nValor,2);
        $oRetorno->itens[]       = $oConta;

      }
    } else {


      $oPPA->saveEstimativa($oParam->iCodCon, $oParam->iAno,$oParam->nValor, "{$oParam->iConcarPeculiar}");

      /**
       * Criamos um array, com os anos que devemos atualizar
       */
       $oRetorno->nValorOriginal  = $oParam->nValorOriginal;
       $oRetorno->lDeducao        = $oParam->lDeducao;

     }
    while ($lContas != false) {

      $iConta = str_pad(db_le_mae_rec($oParam->iEstrutural),15,0,STR_PAD_RIGHT);
      $oConta  = new stdClass();
      $oConta->iEstrutural     = $iConta;
      $oConta->valor           = 0;
      $oRetorno->itens[]       = $oConta;
      $oParam->iEstrutural     = $iConta;
      $oConta->iConcarPeculiar = "{$oParam->iConcarPeculiar}";
      if (db_le_mae_rec($iConta,true) == 1) {
        $lContas = false;
      }
    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }

  /**
   * @todo - remover
   */
  if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
  	$_SESSION["DB_use_pcasp"] = "f";
  }

  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "reprocessaEstimativa") {

	/**
	 * @todo - remover
	 * Ativa PCASP quando arquivo de configuração com ano do PCASP existir
	 */
	if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
		$_SESSION["DB_use_pcasp"] = "t";
	}

  try {

    db_inicio_transacao();
    $oPPA          = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    $aRetornoItens = array();
    foreach ($oParam->aAnos as $iAno) {

      $aDesdobramentos      = $oPPA->getDesdobramentos($oPPA->criaContaMae($oParam->iEstrutural),$iAno);
      if (count($aDesdobramentos) > 0 && $oParam->iTipo == 1)  {

        foreach ($aDesdobramentos as $oDesbramento) {
          $aRetornoItens[] =  $oPPA->processarEstimativas($oDesbramento->o57_codfon, $iAno, "{$oParam->iConcarPeculiar}");
        }
      } else {

        $oValores        = new stdClass();
        $oValores->valor = $oPPA->processarEstimativas($oParam->iCodCon, $iAno, "{$oParam->iConcarPeculiar}");
        $oValores->ano   = $iAno;
        $aRetornoItens[] =  $oValores;

      }
    }

    $oRetorno->itens = $aRetornoItens;
    $oRetorno->iEstrutural = $oParam->iEstrutural;
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }

  /**
   * @todo - remover
   */
  if ( !USE_PCASP && file_exists("config/pcasp.txt") ) {
  	$_SESSION["DB_use_pcasp"] = "f";
  }

  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "calculaValorEstimativa") {

  try {

    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    for ($i = $oParam->iAno+1; $i <= $oParam->iAnoFinal; $i++) {

      $nValorParametro       = ppa::getAcrescimosEstimativa($oParam->iCodCon, $i);
      $oValorCorrigido       = new stdClass;
      $oValorCorrigido->iAno = $i;
      $oValorCorrigido->nValor = $oParam->nValor;
      if ($nValorParametro != 0) {
        $oValorCorrigido->nValor *= $nValorParametro;
      }

      $nValorSalvar = round($oValorCorrigido->nValor);
      if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
        $nValorSalvar = round($oValorCorrigido->nValor, 2);
      }
      $oParam->nValor          = $nValorSalvar;
      $oValorCorrigido->nValor = $nValorSalvar;
      $oRetorno->itens[]= $oValorCorrigido;
    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);
  }
  echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "adicionaEstimativaDespesa") {

  try {

    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    foreach ($oParam->aAnos as $oAnoDotacao) {

      $oDotacao                         = $_SESSION["dotacaoestimativa"];
      $oDotacao->iAno                   = $oAnoDotacao->iAno;
      $nValorSalvar = round($oAnoDotacao->nValor);
      if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
        $nValorSalvar = round($oAnoDotacao->nValor, 2);
      }
      $oDotacao->nValor                 = $nValorSalvar;
      $oDotacao->o08_elemento           = $oParam->iElemento;
      $oDotacao->o08_recurso            = $oParam->iRecurso;
      $oDotacao->o08_localizadorgastos  = $oParam->iLocalizadorgasto;
      $oDotacao->o08_concarpeculiar     = $oParam->sCaracteristicaPeculiar;
      if ($oDotacao->nValor != 0){
        $oPPA->adicionarEstimativa($oDotacao);
      }
    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);

  }
  echo trim($oJson->encode($oRetorno));
} else if ($oParam->exec == "adicionaEstimativaReceita") {

  try {

    db_inicio_transacao();
    $oPPA  = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    foreach ($oParam->aAnos as $oAnoReceita) {

      $oReceita                   = new stdClass();
      $oReceita->iAno             = $oAnoReceita->iAno;
      $oReceita->nValor           = round($oAnoReceita->nValor, 2);
      $oReceita->iCodCon          = $oParam->iCodCon;
      $oReceita->iConcarPeculiar  = $oParam->iConcarPeculiar;
      if ($oReceita->nValor != 0){
        $oPPA->adicionarEstimativa($oReceita);
      }
    }
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode($eErro->getMessage());
    db_fim_transacao(true);

  }
   echo trim($oJson->encode($oRetorno));
} else if ($oParam->exec == "reProcessaEstimativaGlobal") {

  db_inicio_transacao();
  try {

    $oPPA      = new ppa($oParam->iCodigoLei, $oParam->iTipo, $oParam->iCodigoVersao);
    $iAnoIncio =  $oParam->iAnoInicio - ppa::ANOS_PREVISAO_CALCULO;
    /*
     * Processamos a estimativa
     */
    $oPPA->processarEstimativasGlobais($oParam->iAnoInicio, $oParam->iAnoFim);
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode("Erro [".$eErro->getCode()."] ".$eErro->getMessage());
    db_fim_transacao(true);

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "getVersoesPPA") {

  $oPPA     = new ppa($oParam->iCodigoLei, 1);
  if (isset($oParam->getHomolagadas) && $oParam->getHomologadas) {
     $swhere .= " and o119_homologada is true";
  }
  $iTipoConsulta = $oParam->iTipoConsulta;
  $aVersoes = $oPPA->getVersoes($iTipoConsulta, true);
  if (count($aVersoes) > 0) {

    $oRetorno->itens     = $aVersoes;
    $oRetorno->oFunction = $oParam->oFunction;

  } else {

    $oRetorno->status      = 2;
    $oRetorno->message     = urlencode("Não a Versões do ppa");

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec ==  "getDadosVersao") {

    require_once(modification('model/ppaVersao.model.php'));
    $oDaoPpaVersao              = db_utils::getDao("ppaversao");
    $sSqlProximaVersao          = $oDaoPpaVersao->sql_query_file(null,"coalesce(max(o119_versao), 0)+1 as versao");
    $rsProximaVersao            = $oDaoPpaVersao->sql_record($sSqlProximaVersao);
    $oPPaVersao                 = new ppaVersao($oParam->iCodigoVersao);
    $oRetorno->o119_versao      = $oPPaVersao->getVersao();
    $oRetorno->o119_datainicio  = db_formatar($oPPaVersao->getDatainicio(),"d");
    $oRetorno->o119_datatermino = db_formatar($oPPaVersao->getDatatermino(),"d");
    $oRetorno->proximaversao    = db_utils::fieldsMemory($rsProximaVersao, 0)->versao;
    $oRetorno->o119_sequencial  = $oPPaVersao->getCodigoversao();
    $lAtivo                     = $oPPaVersao->getAtivo();
    $oRetorno->o119_ativo       = "Sim";

    if (!$lAtivo) {
      $oRetorno->o119_ativo = urlencode("Não");
    }

   if (isset($oParam->lVersoes)) {

     $sWhere = "";
     $oDaoPpaVersao     = db_utils::getDao("ppaversao");
     $sSqlOutrasVersoes = $oDaoPpaVersao->sql_query_file(null,
                                                    "*",
                                                    "o119_versao",
                                                    "o119_versao < {$oRetorno->o119_versao} {$sWhere}"
                                                   );
     $rsOutrasVersoes = $oDaoPpaVersao->sql_record($sSqlOutrasVersoes);
     $oRetorno->itens = db_utils::getCollectionByRecord($rsOutrasVersoes, true);

   }
   echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "retornaVersaoPPA") {

  require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao                 = new ppaVersao($oParam->iCodigoVersao);
  try {

    db_inicio_transacao();
    $oPPaVersao->retornaVersao($oParam->iCodigoVersaoRetornar);
    db_fim_transacao(false);
    $oRetorno->message = urlencode("Nova versão criada com sucesso");
  }
  catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->erro_status = 2;
    $oRetorno->message     = urlencode("Erro[".$eErro->getCode()."] - ".$eErro->getMessage());

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "novaVersaoPPA") {

  require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao                 = new ppaVersao($oParam->iCodigoVersao);
  try {

    db_inicio_transacao();
    $oPPaVersao->novaVersao($oParam->lHomologar);
    db_fim_transacao(false);
    $oRetorno->message = urlencode("Nova versão criada com sucesso");
  }
  catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->erro_status = 2;
    $oRetorno->message     = urlencode("Erro[".$eErro->getCode()."] - ".$eErro->getMessage());

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "homologarPPA") {

  require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao   = new ppaVersao($oParam->iCodigoVersao);
  try {

    db_inicio_transacao();
    $oPPaVersao->setFinalizada(true);
    $oPPaVersao->setHomologada(true);
    $oPPaVersao->save();
    db_fim_transacao(false);
    $oRetorno->message = urlencode("Perspectiva homologada com sucesso");

  }

  catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->erro_status = 2;
    $oRetorno->message     = urlencode("Erro[".$eErro->getCode()."] - ".$eErro->getMessage());

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "getUltimoAnoIntegrado") {

  require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao   = new ppaVersao($oParam->o119_sequencial);
  $iAno         = $oPPaVersao->getUltimoAnoIntegrado();
  if ($iAno == null) {
    $iAno = $oPPaVersao->getAnoinicio();
  } else {
    $iAno = $iAno+1;
  }
  $oRetorno->anointegrar = $iAno;
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "integrar") {

  require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao   = new ppaVersao($oParam->o119_sequencial);
  db_inicio_transacao();
  try {

    $oPPaVersao->gerarIntegracao();
    db_fim_transacao(false);
  } catch (Exception $eErro) {

    db_fim_transacao(true);
    $oRetorno->status  = 2;
    if ($eErro->getCode() == 199) {

      $oRetorno->status  = 199;
      /**
       * Montamos uma lista de Dotacoes Duplicadas
       */
      $sSqlDotacoes  = " select count(*) as qtd, dot, o08_ano ";
      $sSqlDotacoes .= "   from (SELECT fc_estruturaldotacaoppa(o08_ano,o08_sequencial)||'.'||o08_localizadorgastos as dot,";
      $sSqlDotacoes .= "                o08_ano";
      $sSqlDotacoes .= "           from ppadotacao ";
      $sSqlDotacoes .= "          where o08_ppaversao = {$oParam->o119_sequencial}";
      $sSqlDotacoes .= "            ) ";
      $sSqlDotacoes .= "      as x ";
      $sSqlDotacoes .= "  group by dot,o08_ano ";
      $sSqlDotacoes .= "having count(*) > 1 ";
      $sSqlDotacoes .= " order by dot,o08_ano ";
      $rsDotacoes    = db_query($sSqlDotacoes);
      $oRetorno->itens = db_utils::getCollectionByRecord($rsDotacoes,false,false, true);

    }
    $oRetorno->message = urlencode($eErro->getMessage());

  }
  echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "getAnoCancelado") {

  $sSqAnoCancelar  =  "SELECT o123_ano ";
  $sSqAnoCancelar .=  "  from ppaintegracao ";
  $sSqAnoCancelar .=  " where o123_ano        = ".(db_getsession("DB_anousu")+1);
  $sSqAnoCancelar .=  "   and o123_ppaversao  = {$oParam->o119_sequencial}";
  $sSqAnoCancelar .=  "   and o123_situacao   = 1 ";
  $sSqAnoCancelar .=  "   and o123_tipointegracao = 1 ";
  $sSqAnoCancelar .=  "   and o123_instit     = ".db_getsession("DB_instit");
//  $sSqAnoCancelar .=  "   and exists(select 1 ";
//  $sSqAnoCancelar .=  "                from ppaintegracaodespesa ";
//  $sSqAnoCancelar .=  "               where o121_ppaintegracao = o123_sequencial)";
  $rsAnoCancelar   = db_query($sSqAnoCancelar);
  $iAnoCancelar    = "";
  if (pg_num_rows($rsAnoCancelar) == 1) {
    $iAnoCancelar = db_utils::fieldsMemory($rsAnoCancelar, 0)->o123_ano;
  } else {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode("Não existe integrações a cancelar para esse ano.");

  }
  $oRetorno->anointegrar = $iAnoCancelar;
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "cancelaintegracao") {

   require_once(modification('model/ppaVersao.model.php'));
  $oPPaVersao   = new ppaVersao($oParam->o119_sequencial);
  db_inicio_transacao();
  try {

    $oPPaVersao->cancelarIntegracao();
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($eErro->getMessage());
    db_fim_transacao(true);

  }
  echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "validaVersaoPPA") {

  $oRetorno->leivalida = false;
  $oDaoLei             = db_utils::getDao("ppalei");
  $sSqlPPALei          = $oDaoLei->sql_query_file($oParam->iCodigoLei);
  $rsPPALei            = $oDaoLei->sql_record($sSqlPPALei);
  if ($oDaoLei->numrows > 0) {

    $oDadosLei = db_utils::fieldsMemory($rsPPALei, 0);
    /**
     * Verifica se o ano base da lei é o ano da sessao.
     */
    if ($oDadosLei->o01_anoinicio -1 == db_getsession("DB_anousu")) {
      $oRetorno->leivalida = true;
    } else {

      $oRetorno->message  = "Para o período de referência selecionado será permitido somente consulta. ";
      $oRetorno->message .= "Se você pretende alterar ou processar dados visando a preparação da próxima LDO,";
      $oRetorno->message .= "deverá criar um novo período de referência através do menu:";
      $oRetorno->message .= "Orcamento>Cadastros>PPA>Período de referencia PPA/LOA>Inclusao.";
    }

  } else {

    $oRetorno->leivalida = false;
    $oRetorno->message  = 'Lei não encontrada no sistema.';
  }
  $oRetorno->message  = urlencode($oRetorno->message);
  echo $oJson->encode($oRetorno);

} elseif ($oParam->exec == "alterarStatusAtivacaoPerspectiva") {

  $oPPaVersao = new ppaVersao($oParam->iSequencial);

  try {

    $lTornarAtivo  = true;

    if ($oPPaVersao->getAtivo()) {
      $lTornarAtivo  = false;
    }

    $oPPaVersao->alterarStatusAtivacaoPerspectiva($lTornarAtivo);
    $oRetorno->sAtivo = urlencode("Sim");

    if (!$oPPaVersao->getAtivo()) {
      $oRetorno->sAtivo = urlencode("Não");
    }

  } catch (Exception $eErro) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($eErro->getMessage());
  }

  echo $oJson->encode($oRetorno);

}





?>