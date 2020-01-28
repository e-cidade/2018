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

require_once(modification("classes/materialestoque.model.php"));

/**
 * Classe para lancamentos de empenhos em liquidacao
 * Realiza os lancamentos dos documentos do tipo 200, 201
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 */
class LancamentoEmpenhoEmLiquidacao {

  /**
   * @param EventoContabil $oEventoContabil
   * @param $oStdDadosLancamento
   * @return null
   * @throws BusinessException
   */
  public static function executarLancamentoContabil(EventoContabil $oEventoContabil, $oStdDadosLancamento) {

    $iCodigoDocumentoExecutar = $oEventoContabil->getCodigoDocumento();
    if (count($oEventoContabil->getEventoContabilLancamento()) == 0) {

      $sMensagemErro  = "Nenhum lancamento encontrado para o documento ";
      $sMensagemErro .= "{$iCodigoDocumentoExecutar} - {$oEventoContabil->getDescricaoDocumento()}.";
      throw new BusinessException($sMensagemErro);
    }

    $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacao();

    $oEmpenho              = new EmpenhoFinanceiro($oStdDadosLancamento->iNumeroEmpenho);
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setEmpenho($oEmpenho);
    $oContaCorrenteDetalhe->setRecurso($oEmpenho->getDotacao()->getDadosRecurso());
    $oContaCorrenteDetalhe->setDotacao($oEmpenho->getDotacao());

    /**
     * Material permanente
     */
    if (in_array($iCodigoDocumentoExecutar, array(208, 209, 214, 215))) {

      $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacaoMaterialPermanente();
      $oLancamentoAuxiliarEmLiquidacao->setClassificacao($oStdDadosLancamento->oClassificacao);
    }

    if (in_array($iCodigoDocumentoExecutar, array(210, 211, 212, 213))) {

      $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado();
      $oLancamentoAuxiliarEmLiquidacao->setGrupoMaterial(new MaterialGrupo($oStdDadosLancamento->iCodigoGrupo));

      if ($oEventoContabil->estorno()) {
        $oLancamentoAuxiliarEmLiquidacao->setSaida(true);
      }
    }

    $oLancamentoAuxiliarEmLiquidacao->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
    $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico($oStdDadosLancamento->sObservacaoHistorico);
    $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oStdDadosLancamento->iFavorecido);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($oStdDadosLancamento->iCodigoElemento);
    $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oStdDadosLancamento->iNumeroEmpenho);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oStdDadosLancamento->iCodigoDotacao);
    $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($oStdDadosLancamento->iCodigoNotaLiquidacao);
    $oLancamentoAuxiliarEmLiquidacao->setValorTotal($oStdDadosLancamento->nValorTotal);

    return $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao);
  }

  /**
   * Processa lancamento contabil
   *
   * @param stdClass $oStdDadosLancamento
   * @param boolean $lEstorno
   * @return boolean
   */
  public static function processar($oStdDadosLancamento, $lEstorno = false) {

    $iTipoDocumento = 200;

    if ($lEstorno) {
      $iTipoDocumento = 201;
    }

    $oEventoContabil = self::buscarEventoContabilPeloDesdobramento($iTipoDocumento, $oStdDadosLancamento->iCodigoElemento);

    $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oStdDadosLancamento->iNumeroEmpenho);
    $iAnoUsu = db_getsession("DB_anousu");
    /**
     * Empenho é um RP de material permanente
     * - troca documentos para 214 ou 215 (estorno)
     */
    if ($oEmpenhoFinanceiro->isRestoAPagar($iAnoUsu) && $oEmpenhoFinanceiro->verificaGrupoDoDesdobramento(GrupoContaOrcamento::MATERIAL_PERMANENTE)) {

      $oEventoContabilRP = new EventoContabil(214, $iAnoUsu);

      if ($oEventoContabil->estorno()) {

        $oEventoContabil = $oEventoContabilRP->getEventoInverso();
        if (!$oEventoContabil) {
          throw new BusinessException("Documento 215 não encontrado para o ano {$iAnoUsu}.");
        }
      } else {
        $oEventoContabil = $oEventoContabilRP;
      }

      return self::executarLancamentoContabil($oEventoContabil, $oStdDadosLancamento);
    }

    /**
     * Empenho é um RP
     * - troca documentos para 212/213
     *
     * A propriedade 'oGrupo' é setada somente na inclusão de bens
     */
    if ($oEmpenhoFinanceiro->isRestoAPagar($iAnoUsu) && empty($oStdDadosLancamento->oGrupo) ) {

      $oEventoContabilRP = new EventoContabil(212, $iAnoUsu);

      /**
       * default 212, quando for estorno troca para 213
       */
      if ($oEventoContabil->estorno()) {

        $oEventoContabil = $oEventoContabilRP->getEventoInverso();
        if (!$oEventoContabil && $oEventoContabilRP->possuiDocumentoEstorno()) {
          throw new Exception("Configure a transação para o documento 213 para o ano {$iAnoUsu}.");
        }

      } else {

        $oEventoContabil = $oEventoContabilRP;
      }
    }

    return self::executarLancamentoContabil($oEventoContabil, $oStdDadosLancamento);
  }

  /**
   * Busca evento contabil pelo desdobramento
   *
   * @param integer $iTipoDocumento
   * @param integer $iCodigoElemento
   * @return EventoContabil
   */
  public static function buscarEventoContabilPeloDesdobramento($iTipoDocumento, $iCodigoElemento) {

    $oDocumentoContabil = SingletonRegraDocumentoContabil::getDocumento($iTipoDocumento);
    $oDocumentoContabil->setValorVariavel("[desdobramento]", $iCodigoElemento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
    return new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
  }

}
