<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("interfaces/IRegraLancamentoContabil.interface.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once("libs/exceptions/BusinessException.php");
db_app::import("exceptions.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iAnoSessao         = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");


switch ($oParam->exec) {
  
  /**
   * Case responsavel por salvar um novo grupo de contas do plano orçamentário
   */
  case "salvarGrupo";
  
    db_inicio_transacao();
    try {

      if ($oParam->c20_sequencial >= 1 && $oParam->c20_sequencial <= 1000) {
        throw new Exception ("Impossível salvar ou alterar este grupo.");
      }
      $oGrupoContaOrcamento = new GrupoContaOrcamento();
      $oGrupoContaOrcamento->setCodigo($oParam->c20_sequencial);
      $oGrupoContaOrcamento->setDescricao(db_stdClass::normalizeStringJson($oParam->c20_descr));
      $oGrupoContaOrcamento->setTipoGrupo(2);
      $oGrupoContaOrcamento->salvar();
      
      $oRetorno->message        = urlencode("Grupo salvo com sucesso!");
      $oRetorno->c20_sequencial = $oGrupoContaOrcamento->getCodigo();
      db_fim_transacao(false);
      
    } catch (BusinessException $eBusiness) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage());
      db_fim_transacao(true);
      
    } catch (Exception $eException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eException->getMessage());
      db_fim_transacao(true);
    }
  
  break;
  
  /**
   * Exclui uma conta que venha armazenada em um array de contas para excluir
   */
  case "excluirConta":
    
    db_inicio_transacao();
    try {
      
      $oGrupoContaOrcamento = new GrupoContaOrcamento($oParam->c20_sequencial);
      foreach ($oParam->aContasExcluir as $iIndice => $iCodigoConta) {
        $oGrupoContaOrcamento->excluirConta($iCodigoConta);
      }
      
      $oRetorno->message        = urlencode("Contas excluídas com sucesso.");
      $oRetorno->c20_sequencial = $oParam->c20_sequencial;
      
      db_fim_transacao(false);
    } catch (BusinessException $eBusiness) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage());
      db_fim_transacao(true);
      
    } catch (Exception $eException) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eException->getMessage());
      db_fim_transacao(true);
    }
  break;
  
  
  /**
   * Cria um vínculo entre o grupo de conta e o plano de contas orçamentário
   */
  case "salvarConta";
  
    db_inicio_transacao();
    try {

      if (trim($oParam->c60_estrut) == "") {
        throw new BusinessException("Estrutural da conta não informado.");
      }

      $aClausulaWhereOrcamento = array();
      $aClausulaWhereOrcamento[] = "c60_anousu = {$iAnoSessao}";
      $aClausulaWhereOrcamento[] = "c60_estrut ilike '{$oParam->c60_estrut}%'";
      
      $aClausulaWhereReceita   = array();
      $aClausulaWhereReceita[] = "c60_anousu = {$iAnoSessao}";
      $aClausulaWhereReceita[] = "c60_estrut ilike '{$oParam->c60_estrut}%'";
      
      foreach ($oParam->aVinculosOrcamento as $iIndice => $oVinculo) {
        if (trim($oVinculo->valor) != "") {
          $aClausulaWhereOrcamento[] = "{$oVinculo->nome_campo} {$oVinculo->regra_compara} ({$oVinculo->valor})";
        }
        
        if (trim($oVinculo->valor) != "" && ($oVinculo->nome_campo == 'o58_codigo' || $oVinculo->nome_campo == 'o58_concarpeculiar') ) {
          $aClausulaWhereReceita[] = "{$oVinculo->nome_campo} {$oVinculo->regra_compara} ({$oVinculo->valor})";
        }
      }

      $sWhereBuscaContaOrcamento = implode(" and ", $aClausulaWhereOrcamento);                                                                       
      
      $sSqlBuscaContasOrcamento  = "select distinct                                                                                         ";
      $sSqlBuscaContasOrcamento .= "       conplanoorcamento.c60_codcon                                                                     ";
      $sSqlBuscaContasOrcamento .= "  from conplanoorcamento                                                                                ";
      $sSqlBuscaContasOrcamento .= "       inner join orcelemento on orcelemento.o56_elemento = substr(conplanoorcamento.c60_estrut, 1, 13) ";
      $sSqlBuscaContasOrcamento .= "       left  join orcdotacao  on orcdotacao.o58_codele    = orcelemento.o56_codele                      ";
      $sSqlBuscaContasOrcamento .= " where {$sWhereBuscaContaOrcamento}                                                                     ";
      $rsBuscaContaOrcamento     = db_query($sSqlBuscaContasOrcamento);
      $iTotalContasOrcamento     = pg_num_rows($rsBuscaContaOrcamento);
      
      $sWhereBuscaContaReceita = implode(" and ", $aClausulaWhereReceita);
      $sSqlBuscaContasReceita  = "select distinct                                                                         ";
      $sSqlBuscaContasReceita .= "       conplanoorcamento.c60_codcon                                                     ";
      $sSqlBuscaContasReceita .= "  from conplanoorcamento                                                                ";
      $sSqlBuscaContasReceita .= "       inner join orcfontes    on  orcfontes.o57_fonte   = conplanoorcamento.c60_estrut ";
      $sSqlBuscaContasReceita .= "       left  join orcreceita   on  orcreceita.o70_codfon = orcfontes.o57_codfon         ";
      $sSqlBuscaContasReceita .= "                              and orcreceita.o70_anousu  = orcfontes.o57_anousu         ";
      $sSqlBuscaContasReceita .= " where {$sWhereBuscaContaReceita}                                                       ";
      
      $rsBuscaContaReceita     = db_query($sSqlBuscaContasReceita);
      $iTotalContasReceita     = pg_num_rows($rsBuscaContaReceita);
      
      
      if ($iTotalContasOrcamento == 0 && $iTotalContasReceita == 0) {
        throw new BusinessException("Nenhuma conta encontrada para o filtro selecionado.");
      }
      
      
      for ($iRowConta = 0; $iRowConta < $iTotalContasOrcamento; $iRowConta++) {
        
        $oDadoConta = db_utils::fieldsMemory($rsBuscaContaOrcamento, $iRowConta);
        $oGrupoContaOrcamento = new GrupoContaOrcamento($oParam->c20_sequencial);
        $oGrupoContaOrcamento->vincularConta($oDadoConta->c60_codcon);
      }
      
      for ($iRowConta = 0; $iRowConta < $iTotalContasReceita; $iRowConta++) {
      
        $oDadoConta = db_utils::fieldsMemory($rsBuscaContaReceita, $iRowConta);
        $oGrupoContaOrcamento = new GrupoContaOrcamento($oParam->c20_sequencial);
        $oGrupoContaOrcamento->vincularConta($oDadoConta->c60_codcon);
      }
      
      
      
      $oRetorno->message = urlencode("Contas salvas com sucesso.");
    	db_fim_transacao(false);
    	
    } catch (BusinessException $eBusiness) {
    
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($eBusiness->getMessage());
    	db_fim_transacao(true);
    
    } catch (Exception $eException) {
    
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($eException->getMessage());
    	db_fim_transacao(true);
    }
  break;
    
  /**
   * Busca as contas do orçamento cadastradas para o grupo de conta
   */
  case "getContasGrupo":
    
    try {

      $oGrupoContaOrcamento = new GrupoContaOrcamento($oParam->c20_sequencial);
      $aContasPlano         = $oGrupoContaOrcamento->getContas();
      
      $aContasRetorno = array();
      foreach ($aContasPlano as $iIndice => $oContaOrcamento) {
        
        $oStdConta               = new stdClass();
        $oStdConta->iCodigoConta = $oContaOrcamento->getCodigoConta();
        $oStdConta->estrutural   = $oContaOrcamento->getEstrutural();
        $oStdConta->descricao    = urlencode($oContaOrcamento->getDescricao());
        $aContasRetorno[] = $oStdConta;
      }
      
      $oRetorno->aContas = $aContasRetorno;
      
    } catch (BusinessException $eBusiness) {
    
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($eBusiness->getMessage());
    	db_fim_transacao(true);
    
    } catch (Exception $eException) {
    
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($eException->getMessage());
    	db_fim_transacao(true);
    }
  
  break;
  
  case "excluiGrupo": 
    
    try {
    
      if ($oParam->c20_sequencial <= 1000) {
        throw new Exception ("Impossível excluir este grupo");
      }
      
      $oGrupoContaOrcamento = new GrupoContaOrcamento($oParam->c20_sequencial);
      $oGrupoContaOrcamento->excluir();
      $oRetorno->message    = urlencode("Excluido grupo com sucesso.");
      db_fim_transacao(false);
    
    } catch (BusinessException $eBusiness) {
    
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eBusiness->getMessage());
      db_fim_transacao(true);
    
    } catch (Exception $eException) {
    
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eException->getMessage());
      db_fim_transacao(true);
    }
    
    break;
  
    
    
}
echo $oJson->encode($oRetorno);