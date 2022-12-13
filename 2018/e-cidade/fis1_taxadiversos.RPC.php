<?php
/**
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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'lErro' => false, 'sMessage'=> '', 'erro' => false);

$oDaoTaxadiversos           = new cl_taxadiversos;
$oDaoLancamentoTaxaDiversos = new cl_lancamentotaxadiversos();
$oDaoGrupoTaxaDiversos      = new cl_grupotaxadiversos();

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "getTaxas":

    	$oRetorno->aTaxas = array();

      if(!empty($oParametro->iGrupo)) {

        $sSqlTaxadiversos = $oDaoTaxadiversos->sql_query(null, "*", null, "y119_grupotaxadiversos = ". $oParametro->iGrupo);
        $rsTaxadiversos   = db_query($sSqlTaxadiversos);

        if(!$rsTaxadiversos) {
          throw new DBException("Ocorreu um erro ao consultas as taxas vinculadas ao grupo.");
        }

        if(pg_num_rows($rsTaxadiversos)) {

          $oRetorno->aTaxas = db_utils::makeCollectionFromRecord($rsTaxadiversos, function ($oTaxa) {

            $oItemTaxa = new stdClass;

            $oItemTaxa->codigo                      = $oTaxa->y119_sequencial;
            $oItemTaxa->grupotaxadiversos           = $oTaxa->y119_grupotaxadiversos;
            $oItemTaxa->grupotaxadiversos_descricao = $oTaxa->y118_descricao;
            $oItemTaxa->natureza                    = $oTaxa->y119_natureza;
            $oItemTaxa->formula                     = $oTaxa->y119_formula;
            $oItemTaxa->formula_descricao           = $oTaxa->db148_nome;
            $oItemTaxa->unidade                     = $oTaxa->y119_unidade;
            $oItemTaxa->unidade_descricao           = getUnidades($oTaxa->y119_unidade);
            $oItemTaxa->tipo_periodo                = $oTaxa->y119_tipo_periodo;
            $oItemTaxa->tipo_calculo                = $oTaxa->y119_tipo_calculo;

            return $oItemTaxa;
          });
        }
      }

      break;

    case "salvar":

      if(empty($oParametro->grupotaxadiversos)) {
        throw new ParameterException("Não foi informado o grupo a qual a taxa deve estar vinculada.");
      }

      if(empty($oParametro->natureza)) {
        throw new ParameterException("Não foi informado a natureza para a taxa.");
      }

      if(empty($oParametro->formula)) {
        throw new ParameterException("Não foi informado a fórmula para a taxa.");
      }

      if(empty($oParametro->tipo_periodo)) {
        throw new ParameterException("Não foi informado o tipo de período para a taxa.");
      }

      if(empty($oParametro->tipo_calculo)) {
        throw new ParameterException("Não foi informado o tipo de cálculo para a taxa.");
      }

      $oDaoTaxadiversos->y119_sequencial        = $oParametro->codigo;
      $oDaoTaxadiversos->y119_grupotaxadiversos = $oParametro->grupotaxadiversos;
      $oDaoTaxadiversos->y119_natureza          = pg_escape_string($oParametro->natureza);
      $oDaoTaxadiversos->y119_formula           = $oParametro->formula;
      $oDaoTaxadiversos->y119_unidade           = $oParametro->unidade;
      $oDaoTaxadiversos->y119_tipo_periodo      = $oParametro->tipo_periodo;
      $oDaoTaxadiversos->y119_tipo_calculo      = $oParametro->tipo_calculo;

      if(empty($oDaoTaxadiversos->y119_sequencial)) {
        $oDaoTaxadiversos->incluir(null);
      } else {
        $oDaoTaxadiversos->alterar($oDaoTaxadiversos->y119_sequencial);
      }

      if($oDaoTaxadiversos->erro_status == 0) {
        throw new DBException($oDaoTaxadiversos->erro_msg);
      }

      $oRetorno->sMessage = "Cadastro da taxa salvo com sucesso.";
      break;

    case "excluir":

      if(empty($oParametro->codigo)) {
        throw new ParameterException("Informe o código do cadastro da taxa a excluir.");
      }

      $sSqlLancamentoTaxaDiversos = $oDaoLancamentoTaxaDiversos->sql_query_file(
        null,
        '1',
        null,
        "y120_taxadiversos = {$oParametro->codigo}"
      );
      $rsLancamentoTaxaDiversos = db_query($sSqlLancamentoTaxaDiversos);

      if(!$rsLancamentoTaxaDiversos) {
        throw new DBException('Erro ao validar lançamento existente para Natureza.');
      }

      if(pg_num_rows($rsLancamentoTaxaDiversos) > 0) {
        throw new BusinessException('Exclusão não permitida. A Natureza em questão já possui taxa lançada.');
      }

      $oDaoTaxadiversos->excluir($oParametro->codigo);

      if($oDaoTaxadiversos->erro_status == 0) {
        throw new DBException($oDaoTaxadiversos->erro_msg);
      }

      $oRetorno->sMessage = "Taxa excluída com sucesso.";
      break;

    case "getUnidades":

      $oRetorno->aUnidades = getUnidades();
      break;

    case 'getConfiguracoesTaxa':

      $oRetorno->oConfiguracaoTaxa = null;

      if(empty($oParametro->iCodigoTaxa)) {
        throw new ParameterException("Informe uma taxa para verificar sua unidade e tipo de período.");
      }

      $sSqlTaxadiversos = $oDaoTaxadiversos->sql_query($oParametro->iCodigoTaxa);
      $rsTaxadiversos   = db_query($sSqlTaxadiversos);

      if(!$rsTaxadiversos) {
        throw new DBException("Ocorreu um erro ao consultar a configuração da taxa.");
      }

      if(pg_num_rows($rsTaxadiversos) > 0) {

        $oRetorno->oConfiguracaoTaxa = db_utils::makeFromRecord($rsTaxadiversos, function($oTaxa) {

          switch ($oTaxa->y119_tipo_periodo) {
            case 'A':
              $tipoPeriodo = 'Anual';
              break;
            
            case 'M':
              $tipoPeriodo = 'Mensal';
              break;

            default:
              $tipoPeriodo = 'Diária';
              break;
          }

          $oConfiguracaoTaxa = new stdClass();

          $oConfiguracaoTaxa->unidadeOpcional     = empty($oTaxa->y119_unidade) ? true : false;
          $oConfiguracaoTaxa->unidade             = !empty($oTaxa->y119_unidade) ? getUnidades($oTaxa->y119_unidade) : '';
          $oConfiguracaoTaxa->dataFimOpcional     = $oTaxa->y119_tipo_calculo == 'G' ? true : false;
          $oConfiguracaoTaxa->periodoOpcional     = $oTaxa->y119_tipo_calculo == 'G' ? true : false;
          $oConfiguracaoTaxa->tipoPeriodoNatureza = $tipoPeriodo;

          return $oConfiguracaoTaxa;
        }, 0);
      }

      break;

    case 'calcularTaxasGeral':

      $oRetorno->aTaxas     = array();
      $aLancamentosCalcular = $oParametro->aTaxas;

      $oRetorno->sMessage = 'Cálculo processado.';

      if(count($aLancamentosCalcular) == 0) {
        throw new BusinessException("Não há lançamentos para cálculo geral.");
      }
      
      $aLancamentos = array();

      foreach ($aLancamentosCalcular as $oLancamentoItem) {

        $oLancamentoCalcular = LancamentoTaxaDiversosRepository::getInstanciaPorCodigo($oLancamentoItem->idTaxa);

        if($oLancamentoItem->dataVencimento) {
          $oLancamentoCalcular->setDataVencimento(new DBDate($oLancamentoItem->dataVencimento));
        }

        $aLancamentos[]      = $oLancamentoCalcular;
      }

      $lErroCalculo = false;

      foreach ($aLancamentos as $oLancamento) {

        $sMensagemStatus     = 'Não calculado';
        $sHintMensagemStatus = '';

        try {

          db_query("savepoint calculo_taxa_diversos;");
          calcularTaxas($oLancamento);

          $sMensagemStatus     = 'Calculado';
          $sHintMensagemStatus = 'Data do último cálculo: ' . date('d/m/Y');

        } catch (Exception $e) {

          db_query("rollback to savepoint calculo_taxa_diversos;");
          $sMensagemStatus     = 'Erro';
          $sHintMensagemStatus = $e->getMessage();
          $lErroCalculo        = true;
        }

        $oRetorno->aTaxas[] = montaObjetoRetorno(array(
          'lancamento'         =>$oLancamento,
          'mensagemStatus'     =>$sMensagemStatus,
          'hintMensagemStatus' =>$sHintMensagemStatus,
          'calculou'           =>true,
        ));
      }

      /**
       * Verfica se existem erros.
       */
      if($lErroCalculo) {
        $oRetorno->sMessage .= "\n Verifique os registros com status 'Erro'.";
      }

      break;

    case 'getTaxasCalcular':

      $iNatureza              = $oParametro->natureza != 'T' ? $oParametro->natureza : null;
      $iGrupo                 = $oParametro->iGrupo != 'T' ? $oParametro->iGrupo : null;
      $aLancamentos           = LancamentoTaxaDiversosRepository::getLancamentosParaCalculoGeral($iNatureza, $iGrupo);
      $oRetorno->aLancamentos = array();

      foreach ($aLancamentos as $oLancamento) {

        $lCalculou           = false;
        $sMensagemStatus     = 'Não calculado';
        $sHintMensagemStatus = '';

        if($oLancamento->getDataUltimoCalculoGeral()) {

          $lCalculou           = true;
          $sMensagemStatus     = 'Calculado';
          $sHintMensagemStatus = 'Data do último cálculo: ' . $oLancamento->getDataUltimoCalculoGeral()->getDate(DBDate::DATA_PTBR);
        }

        $oRetorno->aLancamentos[] = montaObjetoRetorno(array(
          'lancamento'         => $oLancamento,
          'calculou'           => $lCalculou,
          'mensagemStatus'     => $sMensagemStatus,
          'hintMensagemStatus' => $sHintMensagemStatus
        ));
      }

      break;
      
    case 'excluirLancamento':

      if(!isset($oParametro->codigo) || empty($oParametro->codigo)) {
        throw new ParameterException("Informe o código do lançamento para excluir.");
      }
      
      $oDaoLancamentotaxa         = new cl_lancamentotaxadiversos;
      $oDaoDiversoslancamentotaxa = new cl_diversoslancamentotaxa();

      /**
       * Verifica os lançamentos de diversos
       */
      $sWhereVerificaLancamentosDiversos = "y120_sequencial = {$oParametro->codigo}";
      $sSqlVerificaLancamentosDiversos   = $oDaoLancamentotaxa->sql_query_join_diversos(null, "*", "dv05_numpre", $sWhereVerificaLancamentosDiversos);
      $rsVerificaLancamentosDiversos     = db_query($sSqlVerificaLancamentosDiversos);
      
      if(!$rsVerificaLancamentosDiversos) {
        throw new DBException("Ocorreu um erro ao verificar os lançamentos de diversos.");
      }

      /** 
       * Armazena os lançamentos verificados para exclui-los
       */
      $aLancamentosDiversos = array();
      if(pg_num_rows($rsVerificaLancamentosDiversos) > 0) {
        $aLancamentosDiversos = db_utils::makeCollectionFromRecord($rsVerificaLancamentosDiversos, function ($oDadosLancamentoDiversos) {
          return $oDadosLancamentoDiversos->dv14_diversos;
        });
      }

      /**
       * Verifica se já foram cancelados os débitos vinculados ao lançamento
       */ 
      $rsVerificaDebitosLancamento = db_query("SELECT k00_numpre as numpre FROM arrecad WHERE k00_numpre IN (SELECT distinct dv05_numpre as numpre FROM ({$sSqlVerificaLancamentosDiversos}) as dados)");
      if(!$rsVerificaDebitosLancamento) {
        throw new DBException("Ocorreu um erro ao verificar os débitos para o lançamento.");
      }

      /**
       * Armazena os numpres dos débitos para informar o usuário no caso de não terem sido cancelados
       */
      $aNumpresDebitosLancamento = array();
      if(pg_num_rows($rsVerificaDebitosLancamento) > 0) {

        $aNumpresDebitosLancamento = db_utils::makeCollectionFromRecord($rsVerificaDebitosLancamento, function ($oDadosDebitosLancamentos) {
          return $oDadosDebitosLancamentos->numpre;
        });
      }

      if(!empty($aNumpresDebitosLancamento)) {
        throw new BusinessException("Existem débitos vinculados ao lançamento que devem ser cancelados antes da exclusão.\n\nCódigos numpre: (". implode(', ', $aNumpresDebitosLancamento) .")");
      }

      /**
       * Verifica se a taxa já foi calculada pra efetuar a exclusão
       */ 
      if(!empty($aLancamentosDiversos)) {

        /**
         * Começa as exclusões fazendo primeiro na tabela de ligação depois nas tabelas de lançamentos e de diversos
         */
        $oDaoDiversoslancamentotaxa->excluir(null, "dv14_lancamentotaxadiversos = {$oParametro->codigo}");
        
        if($oDaoDiversoslancamentotaxa->erro_status == '0') {
          throw new BusinessException($oDaoDiversoslancamentotaxa->erro_msg);
        }
      
        /** 
         * Exclui o diversos
         */ 
        $oDaoDiversos = new cl_diversos;
        $oDaoDiversos->excluir(null, "dv05_coddiver IN (". implode(", ", $aLancamentosDiversos) .")");
        if($oDaoDiversos->erro_status == '0') {
          throw new BusinessException($oDaoDiversos->erro_msg);
        }
      }

      $oDaoLancamentotaxa->excluir($oParametro->codigo);
      if($oDaoLancamentotaxa->erro_status == '0') {
        throw new BusinessException($oDaoLancamentotaxa->erro_msg);
      }

      $oRetorno->sMessage = $oDaoLancamentotaxa->erro_msg;

      break;

    case 'getNaturezas':

      $sWhereNaturezas = $oParametro->iGrupo != 'T' ? "y119_grupotaxadiversos = {$oParametro->iGrupo}" : '';
      $sSqlNaturezas   = $oDaoTaxadiversos->sql_query_file(null, '*', null, $sWhereNaturezas);
      $rsNaturezas     = db_query($sSqlNaturezas);

      if(!$rsNaturezas) {
        throw new DBException('Erro ao buscar as Naturezas cadastradas.');
      }

      $oNaturezaPadrao            = new stdClass();
      $oNaturezaPadrao->codigo    = 'T';
      $oNaturezaPadrao->descricao = 'Todas';
      $aNaturezaPadrao            = array();
      $aNaturezaPadrao[]          = $oNaturezaPadrao;

      $oRetorno->aNaturezas = db_utils::makeCollectionFromRecord($rsNaturezas, function($oRetornoNatureza) {

        $oDadosNatureza            = new stdClass();
        $oDadosNatureza->codigo    = $oRetornoNatureza->y119_sequencial;
        $oDadosNatureza->descricao = $oRetornoNatureza->y119_natureza;

        return $oDadosNatureza;
      });

      $oRetorno->aNaturezas = array_merge($aNaturezaPadrao, $oRetorno->aNaturezas);

      break;

    case 'getGrupos':

      $sCamposGrupoTaxaDiversos = 'y118_sequencial as codigo, y118_descricao as descricao';
      $sSqlGrupoTaxaDiversos    = $oDaoGrupoTaxaDiversos->sql_query_file(null, $sCamposGrupoTaxaDiversos);
      $rsGrupoTaxaDiversos      = db_query($sSqlGrupoTaxaDiversos);

      if(!$rsGrupoTaxaDiversos) {
        throw new DBException('Erro ao buscar os grupos de taxas.');
      }

      $oRetorno->aGrupos = db_utils::getCollectionByRecord($rsGrupoTaxaDiversos);

      break;

    case 'calcularTaxasUnica':

      if(empty($oParametro->codigo)) {
        throw new ParameterException("Informe o código do lançamento para calcular.");
      }

      $oLancamentoCalcular = LancamentoTaxaDiversosRepository::getInstanciaPorCodigo($oParametro->codigo);

      if(empty($oParametro->data_vencimento)) {
        throw new ParameterException("Informe a data de vencimento da taxa.");
      }
      
      $oLancamentoCalcular->setDataVencimento(new DBDate($oParametro->data_vencimento));
      
      $sObservacao = '';
      if(!empty($oParametro->observacao)) {
        $sObservacao = $oParametro->observacao;
      }

      calcularTaxas($oLancamentoCalcular, $sObservacao, false);

      $oRetorno->sMessage = 'Taxa calculada com sucesso.';
      break;
  }

  db_fim_transacao(false);
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->lErro    = true;
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);

/**
 * Retorna as unidades para cálculo das taxas
 */
function getUnidades($indice = null)
{
  $unidades = array(
    '-------',
    'm',
    'm²',
    'm³',
    '100m',
    '30m²',
    '60m²',
    'Lote',
    'Imóvel',
    'Peça',
    'Milheiro',
    'Veículo',
    'Unidade'
  );

  if(!empty($indice) || $indice === 0 || $indice === '0') {
    return $unidades[$indice];
  }

  return $unidades;
}


function calcularTaxas($oLancamento, $sObservacao = '', $lCalculoGeral = true)
{

  $oDataAtual       = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
  $oDataVencimento  = new DBDate(date('Y-m-d', strtotime('next Month', db_getsession('DB_datausu'))));

  if($oLancamento->getDataVencimento()) {
    $oDataVencimento = $oLancamento->getDataVencimento();
  }

  if($lCalculoGeral) {
    $nValorCalculado = $oLancamento->calcularTaxa(ProcessamentoTaxaDiversos::CALCULO_GERAL);
  } else {
    $nValorCalculado = $oLancamento->calcularTaxa(ProcessamentoTaxaDiversos::CALCULO_INDIVIDUAL);
  }

  $oDados = new stdClass;

  $oDados->codigo_lancamento               = $oLancamento->getCodigo();
  $oDados->codigo_diversos                 = null;
  $oDados->codigo_cgm                      = $oLancamento->getCGM()->getCodigo();
  $oDados->data_inscricao                  = $oDataAtual->getDate();
  $oDados->exercicio                       = $oDataAtual->getAno();
  $oDados->codigo_procedencia              = $oLancamento->getNaturezaTaxa()->getGrupoTaxaDiversos()->getCodigoProcedencia();  // Selecionar a procedencia que tem um inflator vinculado
  $oDados->data_primeiro_vencimento        = $oDataVencimento->getDate();
  $oDados->valor_historico                 = $nValorCalculado;
  $oDados->valor_corrigido                 = $nValorCalculado;
  $oDados->data_operacao                   = $oDataAtual->getDate();
  $oDados->total_parcelas                  = 1;
  $oDados->observacao                      = $sObservacao;
  $oDados->data_proximo_vencimento         = $oDataVencimento->getDate();
  $oDados->dia_data_proximo_vencimento     = $oDataVencimento->getDia();
  $oDados->codigo_instituicao              = db_getsession("DB_instit");
  
  if(trim($oLancamento->getInscricaoMunicipal()) != '') {
    $oDados->inscricao_municipal = $oLancamento->getInscricaoMunicipal();
  }

  if($lCalculoGeral) {
    $oDados->data_calculo_geral            = $oDataAtual->getDate();
  }

  $oProcessamentoTaxaDiversos = new ProcessamentoTaxaDiversos();
  $oDiversos = $oProcessamentoTaxaDiversos->lancarDiversos($oDados);
}

function montaObjetoRetorno($aPropriedades)
{

  $oLancamento         = $aPropriedades['lancamento'];
  $sMensagemStatus     = $aPropriedades['mensagemStatus'];
  $sHintMensagemStatus = $aPropriedades['hintMensagemStatus'];
  $lCalculou           = $aPropriedades['calculou'];

  $sUnidade = $oLancamento->getUnidade();

  if($oLancamento->getNaturezaTaxa()->getUnidade()) {
   $sUnidade .= ' / '. getUnidades($oLancamento->getNaturezaTaxa()->getUnidade());
  }

  return (object)array(
    'codigo'              => $oLancamento->getCodigo(),
    'cgm'                 => $oLancamento->getCgm()->getCodigo(),
    'cgm_nome'            => $oLancamento->getCgm()->getNome(),
    'natureza'            => $oLancamento->getNaturezaTaxa()->getNatureza(),
    'unidade'             => $sUnidade,
    'data_vencimento'     => $oLancamento->getDataVencimento() != null ? $oLancamento->getDataVencimento()->getDate(DBDate::DATA_PTBR) : date('d/m/Y', strtotime('next Month')),
    'data_fim'            => $oLancamento->getDataFim() != null ? $oLancamento->getDataFim()->getDate(DBDate::DATA_PTBR) : '',
    'inscricao_municipal' => $oLancamento->getInscricaoMunicipal(),
    'status'              => $sMensagemStatus,
    'status_hint'         => $sHintMensagemStatus,
    'calculou'            => $lCalculou
  );
}