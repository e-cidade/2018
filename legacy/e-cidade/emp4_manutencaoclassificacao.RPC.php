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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));

$oParam             = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case "getEmpenhoPorSequencial":

      $oRetorno->iClassificacao = '';
      $oRetorno->sClassificacao = '';
      $oRetorno->lDispensa      = false;
      $oRetorno->sJustificativa = '';
      
      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oParam->iNumeroEmpenho);

      $oRetorno->sNumeroEmpenho = $oEmpenho->getCodigo() . "/" . $oEmpenho->getAno();
      $oListaClassificacao      = $oEmpenho->getListaClassificacaoCredor();
      if (empty($oListaClassificacao)) {
        $oListaClassificacao = ListaClassificacaoCredorRepository::getPorEmpenho($oEmpenho);
      }

      if (!empty($oListaClassificacao)) {
        
        $oRetorno->iClassificacao = $oListaClassificacao->getCodigo();
        $oRetorno->sClassificacao = $oListaClassificacao->getDescricao();
        $oRetorno->lDispensa = $oListaClassificacao->dispensa();

        $oDaoClassificacao = new cl_classificacaocredoresempenho();
        $sSqlJustificativa = $oDaoClassificacao->sql_query_file(null, 'cc31_justificativa', null, "cc31_empempenho = {$oEmpenho->getNumero()}");
        $rsJustificativa   = db_query($sSqlJustificativa);
        if (!$rsJustificativa) {
          throw new Exception("Ocorreu um erro ao buscar a justificativa da classificação de credor do empenho.");
        }
        $oRetorno->sJustificativa = db_utils::fieldsMemory($rsJustificativa, 0)->cc31_justificativa;
      }

      $oRetorno->aNotasLiquidacao = array();
      foreach ($oEmpenho->getNotasDeLiquidacao() as $oNota) {

        if ($oNota->getValorAnulado() == $oNota->getValorNota()) {
          continue;
        }

        $oStdNota = new stdClass();
        $oStdNota->iCodigo            = $oNota->getCodigoNota();
        $oStdNota->sNumero            = $oNota->getNumeroNota();
        $oStdNota->nValor             = $oNota->getValorNota();
        $oStdNota->sDataVencimento    = $oNota->getDataVencimento() instanceof DBDate ? $oNota->getDataVencimento()->getDate(DBDate::DATA_PTBR) : '';
        $oStdNota->sLocalRecebimento  = $oNota->getLocalRecebimento();
        $oRetorno->aNotasLiquidacao[] = $oStdNota;
      }
      break;

    case "salvarClassificacaoCredor":

      $aClassificacaoObrigatoriaData = array();
      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oParam->iNumeroEmpenho);
      $oParam->sJustificativa = db_stdClass::db_stripTagsJsonSemEscape($oParam->sJustificativa);
      $oClassificacaoCredor = ListaClassificacaoCredorRepository::getPorCodigo($oParam->iClassificacao);
      if (empty($oClassificacaoCredor)) {
        throw new BusinessException("Lista de Classificação de Credor com código {$oParam->iClassificacao} não localizado.");
      }
      if ($oClassificacaoCredor->dispensa() && empty($oParam->sJustificativa)) {
        throw new Exception("Para classificação de Dispensa é necessário informar uma Justificativa.");
      }
      $oParam->sJustificativa = $oClassificacaoCredor->dispensa() ? $oParam->sJustificativa : '';
      $oClassificacao = ClassificacaoCredor::vincularEmpenhoEmClassificacao($oEmpenho, $oParam->iClassificacao, $oParam->sJustificativa);

      foreach ($oParam->aNotasLiquidacao as $oStdNota) {

        $oData = null;
        if (!empty($oStdNota->sDataVencimento)){
          $oData = new DBDate($oStdNota->sDataVencimento);
        }

        $oDaoEmpNota     = new cl_empnota();
        $oDaoEmpNota->e69_codnota          = $oStdNota->iCodigo;
        $oDaoEmpNota->e69_dtvencimento     = !empty($oData) ? $oData->getDate() : '';
        $oDaoEmpNota->e69_localrecebimento = pg_escape_string($oStdNota->sLocalRecebimento);
        $oDaoEmpNota->alterar($oDaoEmpNota->e69_codnota);
        if ($oDaoEmpNota->erro_status == "0") {
          throw new Exception("Não foi possível alterar a data de vencimento e local de recebimento da nota {$oStdNota->iCodigo}.");
        }
      }
      $oRetorno->mensagem = "Informações salva com sucesso.";
      break;

    case "pesquisar":

      $aWhere = array();
      $aWhere[] = "e60_instit = {$iInstituicaoSessao}";
      if (!empty($oParam->filtros->sequencial_empenho)) {
        $aWhere[] = "e60_numemp >= {$oParam->filtros->sequencial_empenho}";
      }
      if (!empty($oParam->filtros->sequencial_empenho_final)) {
        $aWhere[] = "e60_numemp <= {$oParam->filtros->sequencial_empenho_final}";
      }
      if (!empty($oParam->filtros->data_inicial)) {
        $aWhere[] = "e60_emiss >= '{$oParam->filtros->data_inicial}'";
      }
      if (!empty($oParam->filtros->data_final)) {
        $aWhere[] = "e60_emiss <= '{$oParam->filtros->data_final}'";
      }
      if (!empty($oParam->filtros->codigo_classificacao)) {
        $aWhere[] = "cc30_codigo = {$oParam->filtros->codigo_classificacao}";
      }
      if (!empty($oParam->filtros->codigo_credor)) {
        $aWhere[] = "e60_numcgm = {$oParam->filtros->codigo_credor}";
      }
      if($oParam->filtros->iSituacao == RelatorioEmpenhoClassificacaoCredores::SITUACAO_APAGAR) {
        $aWhere[] = " (e60_vlrliq - e60_vlrpag) > 0 ";
      }
      if($oParam->filtros->iSituacao == RelatorioEmpenhoClassificacaoCredores::SITUACAO_PAGOS) {
        $aWhere[] = " (e60_vlremp - e60_vlranu) = e60_vlrpag ";
      }

      $aCampos = array(
        'e60_numemp as sequencial',
        "e60_codemp||'/'||e60_anousu as numero",
        'z01_nome as credor',
        'cc30_descricao as classificacao'
      );
      $oDaoEmpenho       = new cl_empempenho();
      $sSqlClassificacao = $oDaoEmpenho->sql_query_classificacao_credor(implode(',',$aCampos), implode(' and ', $aWhere) . " order by sequencial");
      $rsBuscaEmpenhos   = $oDaoEmpenho->sql_record($sSqlClassificacao);

      if ($oDaoEmpenho->numrows == 0) {
        throw new Exception("Nenhum empenho encontrado para os filtros informados.");
      }

      $aEmpenhosRetorno = array();
      for ($iRow = 0; $iRow < $oDaoEmpenho->numrows; $iRow++) {

        $oStdEmpenho = db_utils::fieldsMemory($rsBuscaEmpenhos, $iRow);
        $oStdEmpenho->numero = urlencode($oStdEmpenho->numero);
        $oStdEmpenho->credor = urlencode($oStdEmpenho->credor);
        $oStdEmpenho->classificacao = urlencode($oStdEmpenho->classificacao);
        $aEmpenhosRetorno[] = $oStdEmpenho;
      }
      $oRetorno->empenhos = $aEmpenhosRetorno;
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);