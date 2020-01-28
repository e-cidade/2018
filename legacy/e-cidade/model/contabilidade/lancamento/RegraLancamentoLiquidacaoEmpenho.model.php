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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));

/**
 * Retorna a regra cadastrada para a arrecadação de receita
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.21 $
 */
class RegraLancamentoLiquidacaoEmpenho implements IRegraLancamentoContabil {


  private $iCodigoDocumento;
  /**
   * @param int                  $iCodigoDocumento
   * @param int                  $iCodigoLancamento
   * @param LancamentoAuxiliarEmpenhoLiquidacao|\ILancamentoAuxiliar $oLancamentoAuxiliar
   *
   * @return bool|\RegraLancamentoContabil
   * @throws \Exception
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $aDocumentosLiquidacaoRP = array(33, 34);
    $this->iCodigoDocumento  = $iCodigoDocumento;
    $iAnoSessao              = db_getsession("DB_anousu");
    $oDaoTransacao           = new cl_contranslr();
    $sWhere                  = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere                 .= " and c45_anousu      = {$iAnoSessao}";
    $sWhere                 .= " and c46_seqtranslan = {$iCodigoLancamento}";

    if (in_array($iCodigoDocumento, $aDocumentosLiquidacaoRP)) {
      $sWhere       .= " and c47_anousu = {$oLancamentoAuxiliar->getEmpenhoFinanceiro()->getAno()}";
    }
    $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", "c114_elemento desc", $sWhere);
    $rsTransacao   = $oDaoTransacao->sql_record($sSqlTransacao);

    if ($oDaoTransacao->erro_status == '0') {
      return false;
    }

    $iNumeroRegistros = $oDaoTransacao->numrows;

    /**
     * Caso o lancamento tenha mais de uma conta configurada, devemos descobrir qual a conta que efetuaremos os
     * lancamentos. Para isso comparamos as contas com base no COMPARA (c47_compara) da regra
     */
    $iCodigoAcordo      = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getCodigoContrato();
    $lRegimeCompetencia = false;
    if (!empty($iCodigoAcordo)) {

      $oRegimeCompetenciaRepository = new RegimeCompetencia();
      $oRegimeCompetencia = $oRegimeCompetenciaRepository->getByAcordo(AcordoRepository::getByCodigo($iCodigoAcordo));
      $lRegimeCompetencia = (!empty($oRegimeCompetencia) && !$oRegimeCompetencia->isDespesaAntecipada());
    }
      
      for ($i = 0; $i < $iNumeroRegistros; $i++) {

      $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $i);

      if ($iNumeroRegistros == 1 && $oDadosTransacao->c46_ordem > 1) {

        $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
        $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
        return $oRegraLancamentoContabil;
      }

      $oRegraLancamentoContabil = false;
      switch ($oDadosTransacao->c47_compara) {

        /**
         * criado case 0 para ser usado em ordem acima de um onde o reduzido do elenento nao precisa ser igual ao da conta
         * configurado na regra.
         */
        case 0:

          if ( $oDadosTransacao->c46_ordem > 1) {
            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            return $oRegraLancamentoContabil;
          }

          break;

        case RegraLancamentoContabil::COMPARA_DEBITO:

          if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_debito && $oDadosTransacao->c46_ordem == 1) {

            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            if (!$lRegimeCompetencia) {
              return $oRegraLancamentoContabil;
            }
          }

          break;

        case RegraLancamentoContabil::COMPARA_CREDITO:

          if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_credito)  {
            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            if (!$lRegimeCompetencia) {
              return $oRegraLancamentoContabil;
            }            
          }

          break;

        case RegraLancamentoContabil::COMPARA_DEBITO_ELEMENTO:

          if (empty($oDadosTransacao->c114_elemento)) {
            throw new Exception("Regra configurada para comparação a Débito / Elemento, porém sem estrutural configurado.");
          }

          $oContaOrcamento = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getContaOrcamento();
          $iReduzido       = $this->getReduzidoPlanoContaPCASP($oContaOrcamento);

          if ($oContaOrcamento->getEstrutural() >= $oDadosTransacao->c114_elemento) {

            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            $oRegraLancamentoContabil->setContaDebito($iReduzido);
            if (!$lRegimeCompetencia) {
              return $oRegraLancamentoContabil;
            }
          }

          break;

        case RegraLancamentoContabil::COMPARA_CREDITO_ELEMENTO:

          if (empty($oDadosTransacao->c114_elemento)) {
            throw new Exception("Regra configurada para comparação a Débito / Elemento, porém sem estrutural configurado.");
          }

          $oContaOrcamento = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getContaOrcamento();
          $iReduzido       = $this->getReduzidoPlanoContaPCASP($oContaOrcamento);
          if ($oContaOrcamento->getEstrutural() >= $oDadosTransacao->c114_elemento) {

            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
            $oRegraLancamentoContabil->setContaCredito($iReduzido);
            if (!$lRegimeCompetencia) {
              return $oRegraLancamentoContabil;
            }
          }
          break;
      }
      
      $oDocumentoContabil = new EventoContabil($iCodigoDocumento, $iAnoSessao);
      $lEstorno           = $oDocumentoContabil->estorno();      
      if ($lRegimeCompetencia && !empty($oRegraLancamentoContabil)) {

        $oEventoContabil    = new EventoContabil(4000, $iAnoSessao);
        $aOrdensLancamentos = $oEventoContabil->getEventoContabilLancamento();
        if (empty($aOrdensLancamentos)) {
           throw new Exception("Não existe lançamentos para o documento 4000.");
         }
         $aRegras = $aOrdensLancamentos[0]->getRegrasLancamento();
         if (empty($aOrdensLancamentos)) {
           throw new Exception("Contra crédito não localizada na transação de ordem 1 (um) do documento 4000.");
         }
         if (!$lEstorno) {
          $oRegraLancamentoContabil->setContaDebito($aRegras[0]->getContaCredito());
        } else {
          $oRegraLancamentoContabil->setContaCredito($aRegras[0]->getContaCredito());
        }
        return $oRegraLancamentoContabil;
      }     
    }

    return false;
  }

  /**
   *
   * @param ContaOrcamento $oContaOrcamento
   * @return integer
   * @throws Exception
   */
  private function getReduzidoPlanoContaPCASP(ContaOrcamento $oContaOrcamento) {


    if (in_array($this->iCodigoDocumento, array(33, 34))) {


      $sEstrutural  = $oContaOrcamento->getEstrutural();
      $iAnoSessao   =  db_getsession("DB_anousu");
      $oInstituicao = new Instituicao(db_getsession("DB_instit"));


      $oContaOrcamento  = ContaOrcamentoRepository::getContaPorEstrutural($sEstrutural, $iAnoSessao, $oInstituicao);
      if (empty($oContaOrcamento)) {
        throw new Exception("A Conta do Orçamento {$sEstrutural} não existe no ano de {$iAnoSessao}. Verifique a configuração.");
      }

    }

    $oPlanoContaPCASP = $oContaOrcamento->getPlanoContaPCASP();
    $iReduzido        = empty($oPlanoContaPCASP) ? null : $oContaOrcamento->getPlanoContaPCASP()->getReduzido();

    if (empty($oPlanoContaPCASP) || empty($iReduzido)) {
      throw new Exception("A Conta do Orçamento não tem vínculo com o Plano de Contas PCASP. Verifique a configuração.");
    }
    return $iReduzido;
  }

}