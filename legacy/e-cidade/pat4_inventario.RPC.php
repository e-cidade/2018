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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/patrimonio/Inventario.model.php"));

require_once(modification("model/patrimonio/InventarioBem.model.php"));
require_once(modification("model/patrimonio/TransferenciaBens.model.php"));
require_once(modification("model/patrimonio/Bem.model.php"));
require_once(modification("model/patrimonio/BemClassificacao.model.php"));
require_once(modification("model/patrimonio/BemTipoAquisicao.php"));
require_once(modification("model/patrimonio/BemTipoDepreciacao.php"));
require_once(modification("model/patrimonio/PlacaBem.model.php"));
require_once(modification("model/patrimonio/BemCedente.model.php"));

require_once(modification("model/configuracao/DBDepartamento.model.php"));
require_once(modification("model/configuracao/DBDivisaoDepartamento.model.php"));

require_once(modification("model/CgmFactory.model.php"));

require_once(modification("classes/db_bensdepreciacao_classe.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("model/patrimonio/depreciacao/CalculoBem.model.php"));
require_once(modification("std/DBNumber.php"));
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
try {

  switch ($oParam->exec) {

    /**
     * case para processamento do inventário
     */
    case "processarReavaliacao":

      db_inicio_transacao();
      $oInventario       = new Inventario($oParam->iInventario);
      $oInventario->processarReavaliacao();
      db_fim_transacao(false);
      $oRetorno->sMessage = _M('patrimonial.patrimonio.pat4_inventario.processamento_realizado');
    break;

    case "incluir":

      db_inicio_transacao();

      $dAbertura       = $oParam->dAbertura       ;
      $dPeriodoInicial = $oParam->dPeriodoInicial ;
      $dPeriodoFinal   = $oParam->dPeriodoFinal   ;
      $iExercicio      = $oParam->iExercicio      ;
      $iProcesso       = $oParam->iProcesso       ;
      $iComissao       = $oParam->iComissao       ;
      $sObservacao     = addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao));
      $iDepartamento   = db_getsession("DB_coddepto");
      //$iSituacao       = $oParam->iSituacao       ;
      /*
       * situações
       * 1 - ativo
       * 2 - anulado
       * 3 - processado
       */

      $oInventario = new Inventario();

      $oInventario->setDataAbertura  ($dAbertura);
      $oInventario->setPeriodoInicial($dPeriodoInicial) ;
      $oInventario->setPeriodoFinal  ($dPeriodoFinal);
      $oInventario->setExercicio     ($iExercicio);
      $oInventario->setProcesso      ($iProcesso);
      $oInventario->setAcordoComissao($iComissao);
      $oInventario->setObservacao    ($sObservacao);
      $oInventario->setSituacao      (1);
      $oInventario->setDepartamento  ($iDepartamento);
      $oInventario->salvar();
      $oRetorno->sMessage = _M('patrimonial.patrimonio.pat4_inventario.inclusao_efetuada');
      db_fim_transacao(false);

      $oRetorno->iSequencial = $oInventario->getInventario();

    break;

    case "anular":

      $iInventario = $oParam->iInventario;
      $sMotivo     = addslashes(db_stdClass::normalizeStringJson($oParam->sMotivo));
      db_inicio_transacao();

      $oInventario = new Inventario($iInventario);
      $oInventario->setMotivo($sMotivo);
      $oInventario->setSituacao(2);
      $oInventario->anular();
      $oRetorno->sMessage = _M('patrimonial.patrimonio.pat4_inventario.anulacao_efetuada');
      db_fim_transacao(false);

    break;

    case "getBensVinculados" :


    	$oInventario = new Inventario($oParam->iInventario);
    	$aBens       = $oInventario->getBens();
    	$aValoresBem = array();
    	foreach ($aBens as $oDadosBem){

    	  $oValores = new stdClass();
    	  $oValores->t52_bem   = $oDadosBem->getBem()->getCodigoBem();
    	  $oValores->t52_descr = urlencode($oDadosBem->getBem()->getDescricao());
    	  $oValores->t41_placa = urlencode($oDadosBem->getBem()->getPlaca()->getNumeroPlaca());
    	  $aValoresBem[] = $oValores;
    	}

    	if (count($aValoresBem) > 0 ) {

    		$oRetorno->aDados = $aValoresBem;
    	} else {

    		throw new BusinessException(_M('patrimonial.patrimonio.pat4_inventario.nenhum_bem_vinculado',$oParam));
    	}

    	break;

    case "excluirBens" :

    	db_inicio_transacao();

    	$aBensSelecionados = explode(",", $oParam->sListaBens);
    	$oInventario       = new Inventario($oParam->iInventario);
    	foreach ($aBensSelecionados as $oBem) {
    		$oInventario->desvincularBens($oBem);
    	}

    	db_fim_transacao(false);
    	$oRetorno->sMessage = _M('patrimonial.patrimonio.pat4_inventario.itens_desvinculados');
    	break;

    case 'getBensFiltroManutencao':

      $lFiltros  = $oParam->lFiltros;

      $aWhere   = array();
      $aWhere[] = "t55_codbem is null";
      $aWhere[] = "t52_instit = ". db_getsession("DB_instit");

      if (!empty($oParam->iOrgao)){
        $aWhere[] = "o40_orgao= {$oParam->iOrgao}";
      }

      if (!empty($oParam->iUnidade)){
        $aWhere[] = "o41_unidade= {$oParam->iUnidade}";
      }

      if (!empty($oParam->iDepartamento)){
        $aWhere[] = "t52_depart= {$oParam->iDepartamento}";
      }

      if (!empty($oParam->iDivisao)) {
        $aWhere[] = "bensdiv.t33_divisao = {$oParam->iDivisao}";
      }

      if (!empty($oParam->iClassificacaoInicial)){
        $aWhere[] = "t64_class >= '{$oParam->iClassificacaoInicial}'";
      }

      if (!empty($oParam->iClassificacaoFinal)){
        $aWhere[] = "t64_class <= '{$oParam->iClassificacaoFinal}'";
      }

      if (!empty($oParam->iBemInicial)){
        $aWhere[] = "t52_bem >= '{$oParam->iBemInicial}'";
      }

      if (!empty($oParam->iBemFinal)){
        $aWhere[] = "t52_bem <= '{$oParam->iBemFinal}'";
      }

      if (!empty($oParam->iPlacaInicial)){
        $aWhere[] = "t52_ident >= '{$oParam->iPlacaInicial}'";
      }

      if (!empty($oParam->iPlacaFinal)){
        $aWhere[] = "t52_ident <= '{$oParam->iPlacaFinal}'";
      }

      if (!empty($oParam->iConvenio)){
        $aWhere[] = "t04_sequencial = '{$oParam->iConvenio}'";
      }

      if (!empty($oParam->nValorAquisicaoInicial)){
        $aWhere[] = "t52_valaqu >= '{$oParam->nValorAquisicaoInicial}'";
      }

      if (!empty($oParam->nValorAquisicaoFinal)){
        $aWhere[] = "t52_valaqu <= '{$oParam->nValorAquisicaoFinal}'";
      }

      if (!empty($oParam->dtAquisicaoInicial)){
        $aWhere[] = "t52_dtaqu >= '" . db_formatar($oParam->dtAquisicaoInicial, "d") . "' ";
      }

      if (!empty($oParam->dtAquisicaoFinal)){
        $aWhere[] = "t52_dtaqu <= '" . db_formatar($oParam->dtAquisicaoFinal, "d") . "' ";
      }

      if (!empty($oParam->iTipoBem) && $oParam->iTipoBem != 1 && $oParam->iTipoBem != 4) {

        if($oParam->iTipoBem == 3) {
          $aWhere[] =  "t53_codbem is not null";
        } else {
          $aWhere[] =  "t54_codbem is not null";
        }
      } else if (!empty($oParam->iTipoBem) && $oParam->iTipoBem == 4) {
        $aWhere[] =  "t64_bemtipos = 3";
      }

      $sWhereBens = implode(" and ",$aWhere);
      $sMetodo    = "sql_query_dados_bem";

      if ($lFiltros == 1) {

        $sMetodo    = "sql_query_dados_bem_inventario";
        $sWhereBens = "t77_inventario = {$oParam->iInventario} ";
      }

      $oDaoBens     = new cl_bens();
      $sSqlBens     = $oDaoBens->$sMetodo(null, "distinct t52_bem", null, $sWhereBens);
      $rsBuscaBens  = $oDaoBens->sql_record($sSqlBens);
      $aBensRetorno = array();
      $iTotalDeBens = $oDaoBens->numrows;

      if ($iTotalDeBens > 2000) {
        throw new BusinessException(_M('patrimonial.patrimonio.pat4_inventario.refine_pesquisa'));
      }

      if ($iTotalDeBens > 0) {

        for ($iRowBem = 0; $iRowBem < $iTotalDeBens; $iRowBem++) {

          $oDadoBem = db_utils::fieldsMemory($rsBuscaBens, $iRowBem);
          $oBem     = new Bem($oDadoBem->t52_bem);

          if ( $oBem->getClassificacao()->getPlanoConta() == null ) {

            $sMensagemErro  = "Bem não encontrado pelo código {$oDadoBem->t52_bem}\n\nPossíveis causas: \n";
            $sMensagemErro .= " - Classificação não configurada, verifique as contas.\n";
            $sMensagemErro .= " - Placa não encontrada.\n";
            $sMensagemErro .= " - Bem não cadastrado.";
            throw new BusinessException($sMensagemErro);
          }

          $oDBDepartamento                      = new DBDepartamento($oBem->getDepartamento());
          $oDBDivisao                           = new DBDivisaoDepartamento($oBem->getDivisao());
          $oStdBem                              = new stdClass();
          $oStdBem->codigo_bem                  = $oBem->getCodigoBem();
          $oStdBem->descricao                   = urlencode($oBem->getDescricao());
          $oStdBem->placa                       = urlencode($oBem->getPlaca()->getNumeroPlaca());
          $oStdBem->codigo_departamento_bem     = $oBem->getDepartamento();
          $oStdBem->descricao_departamento_bem  = urlencode($oDBDepartamento->getNomeDepartamento());
          $oStdBem->codigo_divisao_bem          = $oDBDivisao->getCodigo();
          $oStdBem->descricao_divisao_bem       = urlencode($oDBDivisao->getDescricao());
          $oStdBem->situacao                    = $oBem->getSituacaoBem();
          $oStdBem->codigo_bem_inventario       = null;
          $oStdBem->codigo_inventario           = null;
          $oStdBem->valor_depreciavel           = $oBem->getValorAtual() - $oBem->getValorResidual();
          $oStdBem->valor_residual              = $oBem->getValorResidual();
          $oStdBem->valor_atual                 = $oBem->getValorAtual();
          $oStdBem->vida_util                   = $oBem->getVidaUtil();
          $oStdBem->departamento_inventario     = $oBem->getDepartamento();
          $oStdBem->divisao_inventario          = $oDBDivisao->getCodigo();
          unset($oDBDepartamento);
          unset($oDBDivisao);

          $oDaoInventarioBem      = db_utils::getDao('inventariobem');
          $sSqlBuscaBemInventario = $oDaoInventarioBem->sql_query_inventario(null, "*",
                                                                             null,
                                                                             "t77_bens = {$oBem->getCodigoBem()}
                                                                             and t75_situacao <> 3");
          $rsBuscaInventarioBem   = $oDaoInventarioBem->sql_record($sSqlBuscaBemInventario);

          if ($oDaoInventarioBem->numrows > 0) {

            $oDadoInventarioBem = db_utils::fieldsMemory($rsBuscaInventarioBem, 0);
            unset($rsBuscaInventarioBem);
            $oStdBem->situacao                = $oDadoInventarioBem->t77_situabens;
            $oStdBem->valor_depreciavel       = $oDadoInventarioBem->t77_valordepreciavel;
            $oStdBem->valor_residual          = $oDadoInventarioBem->t77_valorresidual;
            $oStdBem->valor_atual             = $oStdBem->valor_residual + $oStdBem->valor_depreciavel;
            $oStdBem->vida_util               = $oDadoInventarioBem->t77_vidautil;
            $oStdBem->codigo_bem_inventario   = $oDadoInventarioBem->t77_sequencial;
            $oStdBem->codigo_inventario       = $oDadoInventarioBem->t77_inventario;
            $oStdBem->departamento_inventario = $oDadoInventarioBem->t77_db_depart;
            $oStdBem->divisao_inventario      = $oDadoInventarioBem->t77_departdiv;
          }
          $aBensRetorno[] = $oStdBem;

        }
      }

      $oRetorno->aBensEncontrados = $aBensRetorno;
    break;

    case "getDivisaoDepartamentos":

    	$oDaoDepartamento= db_utils::getDao('db_depart');
    	$sSqlDepartamento= $oDaoDepartamento->sql_query_file();
    	$rsDepartamento  = $oDaoDepartamento->sql_record($sSqlDepartamento, "coddepto, descrdepto");
    	$aDepartamento   = array();
    	if ($oDaoDepartamento->numrows > 0) {
    		$aDepartamento = db_utils::getCollectionByRecord($rsDepartamento, false, false, true);
    	}

    	$aDivisaoDepartamento = array();
    	foreach ($aDepartamento as $oStdDepartamento ) {

    		if (!isset($aDivisaoDepartamento[$oStdDepartamento->coddepto])) {

    			$oDaoDepartamento = db_utils::getDao('db_depart');
    			$sSqlBuscaDivisao = $oDaoDepartamento->sql_query_departamento_divisao($oStdDepartamento->coddepto, "t30_codigo,t30_descr");
    			$rsBuscaDivisao   = $oDaoDepartamento->sql_record($sSqlBuscaDivisao);
    			$aDivisaoDepartamento[$oStdDepartamento->coddepto] = db_utils::getCollectionByRecord($rsBuscaDivisao, false, false, true);
    		}
    	}
    	$oRetorno->aDivisoes        = $aDivisaoDepartamento;
    	$oRetorno->aDepartamentos   = $aDepartamento;
    	break;


    case "getSituacoes":

    	$oDaoSituacaoBens = db_utils::getDao('situabens');
    	$sSqlSituacaoBens = $oDaoSituacaoBens->sql_query_file();
    	$rsSituacaoBens   = $oDaoSituacaoBens->sql_record($sSqlSituacaoBens);
    	$aSituacao        = array();
    	if ($oDaoSituacaoBens->numrows > 0) {
    		$aSituacao = db_utils::getCollectionByRecord($rsSituacaoBens, false, false, true);
    	}
    	$oRetorno->aSituacaoBens = $aSituacao;
    	break;


    case "getDivisaoPorDepartamento":

      if (!isset($_SESSION["aDivisaoPorDepartamento"])) {
        $_SESSION["aDivisaoPorDepartamento"] = array();
      }

      if (!isset($_SESSION["aDivisaoPorDepartamento"][$oParam->iCodigoDepartamento])) {

        $oDaoDepartamento = db_utils::getDao('db_depart');
        $sSqlBuscaDivisao = $oDaoDepartamento->sql_query_departamento_divisao($oParam->iCodigoDepartamento, "t30_codigo,t30_descr");
        $rsBuscaDivisao   = $oDaoDepartamento->sql_record($sSqlBuscaDivisao);
        $aDivisao         = array();
        if ($oDaoDepartamento->numrows > 0) {
          $aDivisao = db_utils::getCollectionByRecord($rsBuscaDivisao, false, false, true);
        }
        $_SESSION["aDivisaoPorDepartamento"][$oParam->iCodigoDepartamento] = $aDivisao;
      }
      $oRetorno->aDivisaoDepartamento = $_SESSION["aDivisaoPorDepartamento"][$oParam->iCodigoDepartamento];
      break;

    case "salvarDadosBem":

      $oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
      $oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
      $oInventarioBem->setBem(new Bem($oParam->iCodigoBem));



      $nValorAtual       =  str_replace(",", ".", str_replace(".","", $oParam->nValorAtual));
      $nValorResidual    =  str_replace(",", ".",  str_replace(".","", $oParam->nValorResidual));
      $nValorDepreciavel =  $nValorAtual -  $nValorResidual;

      $oInventarioBem->setValorDepreciavel($nValorDepreciavel);
      $oInventarioBem->setValorResidual($nValorResidual);
      $oInventarioBem->setVidaUtil($oParam->iVidaUtil);
      $oInventarioBem->setSituacao($oParam->iSituacao);
      $oInventarioBem->setDepartamento(new DBDepartamento($oParam->iCodigoDepartamento));
      $oInventarioBem->setDivisaoDepartamento(new DBDivisaoDepartamento($oParam->iCodigoDivisao == "0" ? null : $oParam->iCodigoDivisao));
      $oInventarioBem->salvar();

      $oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();

    break;

    case "alteraValorAtualizado":

      $oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
      $oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
      $oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
      $oInventarioBem->setValorDepreciavel($oParam->nValorAtualizado);
      $oInventarioBem->salvar();
      $oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();

    break;


    case "alteraValorResidual":

      $oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
      $oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
      $oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
      $oInventarioBem->setValorResidual( $oParam->nValorResidual);
      $oInventarioBem->salvar();
      $oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();
    break;

    case "alteraVidaUtil":

    	$oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
    	$oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
    	$oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
    	$oInventarioBem->setVidaUtil($oParam->iVidaUtil);
    	$oInventarioBem->salvar();
    	$oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();
    	break;

  	case "alteraSituacao":

  		$oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
  		$oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
  		$oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
  		$oInventarioBem->setSituacao($oParam->iSituacao);
  		$oInventarioBem->salvar();
  		$oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();
  		break;

  	case "alterarDepartamento":

  	  $oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
  	  $oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
  	  $oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
  	  $oInventarioBem->setDepartamento(new DBDepartamento($oParam->iCodigoDepartamento));
  	  $oInventarioBem->setDivisaoDepartamento(new DBDivisaoDepartamento(null));
  	  $oInventarioBem->salvar();
  	  $oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();

  	  break;

    case "alterarDivisao":

    	$oInventarioBem = new InventarioBem($oParam->iInventarioBem == '0' ? null : $oParam->iInventarioBem);
    	$oInventarioBem->setInventario(new Inventario($oParam->iCodigoInventario));
    	$oInventarioBem->setBem(new Bem($oParam->iCodigoBem));
    	$oInventarioBem->setDivisaoDepartamento(new DBDivisaoDepartamento($oParam->iCodigoDivisao));
    	$oInventarioBem->salvar();
    	$oRetorno->iCodigoBemInventario = $oInventarioBem->getCodigo();

  	break;

    case "desprocessaInventario":

      db_inicio_transacao();
      $oInventarioBem = new Inventario($oParam->iInventario);
      $oInventarioBem->desprocessar();
      db_fim_transacao(false);
      $oRetorno->sMessage = _M('patrimonial.patrimonio.pat4_inventario.inventario_desprocessado');

      break;

    default:
      throw new ParameterException(_M('patrimonial.patrimonio.pat4_inventario.nenhuma_opcao_definida'));
    break;


  }
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;

  $oRetorno->sMessage = urlencode($eErro->getMessage());
  db_fim_transacao(true);
} catch (BusinessException $eBusinessErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eBusinessErro->getMessage());
  db_fim_transacao(true);
}
echo $oJson->encode($oRetorno);