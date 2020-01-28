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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam = JSON::create()->parse( str_replace("\\", "", $_POST["json"]) );

$oRetorno           = new stdClass();
$oRetorno->message = '';
$oRetorno->erro     = false;

const CAMINHO_MENSAGENS = "patrimonial.patrimonio.pat1_bensnotaspendentes.";

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvar":

      if (!isset($oParam->aNotas) || empty($oParam->aNotas) || !is_array($oParam->aNotas)) {
        throw new ParameterException(_M(CAMINHO_MENSAGENS . "liquidacao_nao_informada"));
      }

      if (!isset($oParam->aBens) || empty($oParam->aBens) || !is_array($oParam->aBens)) {
        throw new ParameterException(_M(CAMINHO_MENSAGENS . "bem_nao_informado"));
      }

      if (count($oParam->aNotas) > 1 && count($oParam->aBens) > 1) {
        throw new BusinessException(_M(CAMINHO_MENSAGENS . "validacao_numero_notas_bens"));
      }

      foreach ($oParam->aNotas as $iNota) {

        if (!is_numeric($iNota)) {

          $oOpcao = new stdClass();
          $oOpcao->iNota = $iNota;
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "nota_invalida", $oOpcao));
        }
      }

      $sCamposNotaItem  = " distinct e72_sequencial as codigo, e62_item as item, e60_numcgm as fornecedor, ";
      $sCamposNotaItem .= " e69_dtnota as data, e72_valor as valor ";
      $sWhereNotaItem   = " e72_sequencial in (" . implode(", ", $oParam->aNotas).") ";
      $oDaoEmpNotaItem  = new cl_empnotaitembenspendente();
      $sSqlEmpNotaItem  = $oDaoEmpNotaItem->sql_query_bens(null, $sCamposNotaItem, null, $sWhereNotaItem);
      $rsEmpNotaItem    = $oDaoEmpNotaItem->sql_record($sSqlEmpNotaItem);

      if ($rsEmpNotaItem == false || $oDaoEmpNotaItem->numrows != count($oParam->aNotas)) {
        throw new BusinessException(_M(CAMINHO_MENSAGENS . "notas_nao_encontradas"));
      }

      $iItemTeste = 0;
      $iCgmTeste  = 0;
      $sDataTeste = null;

      $nTotalNotas = 0;
      $aNotas      = array();
      for ($iItem = 0; $iItem < $oDaoEmpNotaItem->numrows; $iItem++) {

        $oNota = db_utils::fieldsMemory($rsEmpNotaItem, $iItem);

        if ($iItem == 0) {

          $iItemTeste = $oNota->item;
          $iCgmTeste  = $oNota->fornecedor;
          $sDataTeste = $oNota->data;
        }

        if ($iItemTeste != $oNota->item) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "diferentes_itens_empenho"));
        }

        if ($iCgmTeste != $oNota->fornecedor) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "diferentes_fornecedores"));
        }

        if ($sDataTeste != $oNota->data) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "diferentes_datas"));
        }

        $nTotalNotas += $oNota->valor;
        $aNotas[]     = $oNota;
      }

      $aBens            = array();
      $aCodigosBens     = array();
      $nTotalBens       = 0;
      $lVariosBensNotas = false;
      foreach ($oParam->aBens as $oBemParametro) {

        if (!isset($oBemParametro->nValorAquisicao) || empty($oBemParametro->nValorAquisicao) || !is_numeric($oBemParametro->nValorAquisicao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "valor_aquisicao_obrigatorio"));
        }

        if (!isset($oBemParametro->nValorResidual) || empty($oBemParametro->nValorResidual) || !is_numeric($oBemParametro->nValorResidual)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "valor_residual_obrigatorio"));
        }

        if (!isset($oBemParametro->iQuantidade) || empty($oBemParametro->iQuantidade) || !is_numeric($oBemParametro->iQuantidade)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "quantidade_obrigatorio"));
        }

        if ($oBemParametro->nValorAquisicao < $oBemParametro->nValorResidual) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "residual_maior_aquisicao"));
        }
        $nTotalBens += ($oBemParametro->iQuantidade * $oBemParametro->nValorAquisicao);

        $oBem = new Bem();

        if (!isset($oBemParametro->iSeqPlaca) || empty($oBemParametro->iSeqPlaca)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "seq_placa_obrigatorio"));
        }

        if (!is_numeric($oBemParametro->iSeqPlaca)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "seq_placa_numerico"));
        }

        if (!isset($oBemParametro->sDataAquisicao) || empty($oBemParametro->sDataAquisicao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "data_aquisicao_obrigatorio"));
        }

        if (!isset($oBemParametro->sDescricao) || empty($oBemParametro->sDescricao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "descricao_obrigatorio"));
        }

        if (!isset($oBemParametro->iCodigoDepreciacao) ||empty($oBemParametro->iCodigoDepreciacao) || !is_numeric($oBemParametro->iCodigoDepreciacao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "tipo_depreciacao_obrigatorio"));
        }

        if (!isset($oBemParametro->iClassificacao) || empty($oBemParametro->iClassificacao) || !is_numeric($oBemParametro->iClassificacao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "classificacao_obrigatorio"));
        }

        if (!isset($oBemParametro->iFornecedor) || empty($oBemParametro->iFornecedor) || !is_numeric($oBemParametro->iFornecedor)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "fornecedor_obrigatorio"));
        }

        if (!isset($oBemParametro->iTipoAquisicao) || empty($oBemParametro->iTipoAquisicao) || !is_numeric($oBemParametro->iTipoAquisicao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "tipo_aquisicao_obrigatorio"));
        }

        if (!isset($oBemParametro->iSituacao) || empty($oBemParametro->iSituacao) || !is_numeric($oBemParametro->iSituacao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "situacao_obrigatorio"));
        }

        if (!isset($oBemParametro->iDepartamento) || empty($oBemParametro->iDepartamento) || !is_numeric($oBemParametro->iDepartamento)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "departamento_obrigatorio"));
        }

        if (!isset($oBemParametro->iVidaUtil) || empty($oBemParametro->iVidaUtil) || !is_numeric($oBemParametro->iVidaUtil)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "vida_util_obrigatorio"));
        }

        if (!isset($oBemParametro->sObservacao) || empty($oBemParametro->sObservacao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "observacao_obrigatorio"));
        }

        if (isset($oBemParametro->iConvenio) && !empty($oBemParametro->iConvenio) && !is_numeric($oBemParametro->iConvenio)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "convenio_invalido"));
        }

        if (isset($oBemParametro->iDivisao) && !empty($oBemParametro->iDivisao) && !is_numeric($oBemParametro->iDivisao)) {
          throw new ParameterException(_M(CAMINHO_MENSAGENS . "divisao_invalido"));
        }

        $oDataAquisicao = new DBDate($oBemParametro->sDataAquisicao);

        $oPlacaBem  = new PlacaBem();
        $oPlacaBem->setPlacaSeq($oBemParametro->iSeqPlaca);
        $oPlacaBem->setData(date("d/m/Y", db_getsession("DB_datausu")));
        if (isset($oBemParametro->sPlaca) && !empty($oBemParametro->sPlaca)) {
          $oPlacaBem->setPlaca($oBemParametro->sPlaca);
        }
        $oPlacaBem->setObservacao( db_stdClass::normalizeStringJsonEscapeString($oBemParametro->sObservacao) );

        $oTipoDepreciacao = new BemTipoDepreciacao($oBemParametro->iCodigoDepreciacao);
        if ($oTipoDepreciacao->getCodigo() != $oBemParametro->iCodigoDepreciacao) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "tipo_depreciacao_nao_encontrado"));
        }

        $oBemClassificacao = new BemClassificacao($oBemParametro->iClassificacao);
        if ($oBemClassificacao->getCodigo() != $oBemParametro->iClassificacao) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "classificacao_nao_encontrado"));
        }

        $oBemFornecedor = CgmFactory::getInstanceByCgm($oBemParametro->iFornecedor);
        if ($oBemFornecedor == null || $oBemFornecedor->getCodigo() != $oBemParametro->iFornecedor) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "fornecedor_nao_encontrado"));
        }

        $oBemTipoAquisicao = new BemTipoAquisicao($oBemParametro->iTipoAquisicao);
        if ($oBemTipoAquisicao->getCodigo() != $oBemParametro->iTipoAquisicao) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "tipo_aquisicao_nao_encontrado"));
        }

        $oDaoSituacao = new cl_situabens();
        $sSqlSituacao = $oDaoSituacao->sql_query($oBemParametro->iSituacao);
        $oDaoSituacao->sql_record($sSqlSituacao);
        if ($oDaoSituacao->numrows == 0) {
          throw new BusinessException(_M(CAMINHO_MENSAGENS . "situacao_nao_encontrado"));
        }

        $oValidandoDepartamento = new DBDepartamento($oBemParametro->iDepartamento);

        if (isset($oBemParametro->iDivisao) && !empty($oBemParametro->iDivisao)) {

          if (!is_numeric($oBemParametro->iDivisao)) {
            throw new ParameterException(_M(CAMINHO_MENSAGENS . "divisao_invalido"));
          }

          $oValidandoDivisao = new DBDivisaoDepartamento($oBemParametro->iDivisao);
          $oBem->setDivisao($oBemParametro->iDivisao);
        }

        if (isset($oBemParametro->iConvenio) && !empty($oBemParametro->iConvenio)) {

          $oBemCedente = new BemCedente($oBemParametro->iConvenio);
          if ($oBemCedente->getCedente() == null) {
            throw new BusinessException(_M(CAMINHO_MENSAGENS . "cedente_nao_encontrado"));
          }
          $oBem->setCedente($oBemCedente);
        }

        $nValorDepreciavel = $oBemParametro->nValorAquisicao - $oBemParametro->nValorResidual;
        $oBem->setPlaca($oPlacaBem);
        $oBem->setDataAquisicao($oDataAquisicao->getDate(DBDate::DATA_EN));
        $oBem->setDescricao( db_stdClass::normalizeStringJsonEscapeString($oBemParametro->sDescricao) );
        $oBem->setTipoDepreciacao($oTipoDepreciacao);
        $oBem->setClassificacao($oBemClassificacao);
        $oBem->setFornecedor($oBemFornecedor);
        $oBem->setTipoAquisicao($oBemTipoAquisicao);
        $oBem->setSituacaoBem($oBemParametro->iSituacao);
        $oBem->setValorAquisicao($oBemParametro->nValorAquisicao);
        $oBem->setValorResidual($oBemParametro->nValorResidual);
        $oBem->setValorDepreciavel($nValorDepreciavel);
        $oBem->setDepartamento($oBemParametro->iDepartamento);
        $oBem->setVidaUtil($oBemParametro->iVidaUtil);
        $oBem->setObservacao( db_stdClass::normalizeStringJsonEscapeString($oBemParametro->sObservacao) );
        $oBem->setInstituicao(db_getsession("DB_instit"));

        $oBem->setModelo(0);
        $oBem->setMarca(0);
        $oBem->setMedida(0);

        $aBens[] = $oBem;

        if (count($aNotas) > 1 && $oBemParametro->iQuantidade > 1) {
          $lVariosBensNotas = true;
        }

        $iSeqPlaca = $oBem->getPlaca()->getPlacaSeq();
        for ($iBens = 1; $iBens < $oBemParametro->iQuantidade; $iBens++) {

          $oPlacaClone = clone $oBem->getPlaca();
          $oPlacaClone->setPlacaSeq(++$iSeqPlaca);

          $oBemClone = clone $oBem;
          $oBemClone->setPlaca($oPlacaClone);
          $aBens[]   = $oBemClone;
        }
      }

      if (round($nTotalNotas, 2) != round($nTotalBens, 2)) {
        throw new BusinessException(_M(CAMINHO_MENSAGENS . "valor_bem_nota"));
      }

      $aPlacas         = array();
      $iParametroPlaca = BensParametroPlaca::getCodigoParametro();
      foreach ($aBens as $oBem) {

        $oPlaca    = $oBem->getPlaca();
        $iSeqPlaca = $oPlaca->getPlacaSeq();
        if ($iParametroPlaca != 4) {
          $iSeqPlaca = $oPlaca->getProximaPlaca($oPlaca->getPlaca());
        }
        $oPlaca->setPlacaSeq($iSeqPlaca);

        if ($lVariosBensNotas) {
          $oBem->salvar();
        }

        foreach ($aNotas as $oNota) {
          $oBem->setCodigoItemNota($oNota->codigo);
        }

        if ($lVariosBensNotas) {
          $oBem->criaVinculoBemNotas();
        } else {
          $oBem->salvar();
        }

        $aCodigosBens[] = $oBem->getCodigoBem();
        $aPlacas[]      = $oBem->getPlaca()->getNumeroPlaca();
      }

      /**
       * Para o caso de n bens para n notas, processará os lançamentos separamento, realizando um lançamento por nota
       * e colocando o código de todos os bens na observação do lançamento.
       */
      if ($lVariosBensNotas) {

        $oDataAtual            = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
        $oInstit               = new Instituicao(db_getsession("DB_instit"));
        $lIntegracaoFinanceiro = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstit);

        if ($lIntegracaoFinanceiro) {

          $oBem = $aBens[0];
          $oBem->processaLancamentoContabil($aCodigosBens);
        }
      }

      $oRetorno->aPlacas = $aPlacas;
      break;

    default:
      throw new Exception(_M(CAMINHO_MENSAGENS . "opcao_invalida"));
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->message = $e->getMessage();
  db_fim_transacao(true);
}
$oRetorno->message = urlencode($oRetorno->message);
echo JSON::create()->stringify($oRetorno);