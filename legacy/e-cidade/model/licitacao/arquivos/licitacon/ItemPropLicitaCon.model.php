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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Proposta as RegraProposta;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\ItemProp as Regra;
use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ProcessoCompraTaxa;
use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ResultadoHabilitacao;

/**
 * Class ItemPropLicitaCon
 */
class ItemPropLicitaCon extends ArquivoLicitaCon
{

    /**
     * @var string
     */
    const NOME_ARQUIVO = 'ITEM_PROP';

    /**
     * @var string
     */
    const TP_OBJETO_OBRAS_SERVICO_ENGENHARIA = 'OSE';

    /**
     * @var array
     */
    private $aPropostasDesclassificadas = array();

    /**
     * @var array
     */
    private $aItensDesclassificados = array();

    /**
     * @var array
     */
    private $aLotesDesclassificados = array();

    /**
     * ItemPropLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return array
     * @throws DBException
     */
    public function getDados()
    {
        $aSituacoes = array(
            SituacaoLicitacao::SITUACAO_JULGADA,
            SituacaoLicitacao::SITUACAO_ADJUDICADA,
            SituacaoLicitacao::SITUACAO_HOMOLOGADA
        );

        $oDaoLicitacao = new cl_liclicita;

        $sTipos = implode(',', array(
            licitacao::TIPO_JULGAMENTO_POR_ITEM,
            licitacao::TIPO_JULGAMENTO_GLOBAL,
        ));
        $aCampos = array(
            'l20_codigo',
            'z01_numcgm',
            'l20_numero AS nr_licitacao',
            'l20_anousu AS ano_licitacao',
            'l20_tipojulg AS tipo_julgamento',
            'l44_sigla AS cd_tipo_modalidade',
            "CASE
                WHEN l44_sigla IN ('CPC', 'MAI', 'RPO', 'PRD', 'PRI') OR l20_tipojulg <> " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " THEN NULL
                WHEN pc32_orcamitem IS NOT NULL THEN 'D'
                WHEN pc23_vlrun IS NULL OR pc23_vlrun = 0 THEN 'D'
                ELSE 'C'
            END AS tp_resultado_proposta",
            'l16_cadattdinamicovalorgrupo',
            'COALESCE(pc23_bdi, 0) AS pc23_bdi',
            'COALESCE(pc23_encargossociais, 0) AS pc23_encargossociais',
            'l21_ordem AS nr_item',
            "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " AND l44_sigla IN ('MDE') THEN pc23_percentualdesconto
                ELSE NULL
            END AS pc_desconto",
            'ROUND(COALESCE(pc23_vlrun * pc23_quant, 0), 2) AS vl_total_item',
            "COALESCE(pc23_vlrun, 0) AS VL_UNITARIO",
            "CASE
                WHEN l44_sigla IN ('MCA', 'MOQ', 'MOT', 'MPP', 'MTC', 'MTO', 'MTT', 'TPR') AND l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " THEN pc23_notatecnica
                ELSE NULL
            END AS vl_nota_tecnica",
            "MIN(COALESCE(CASE WHEN l20_tipojulg IN ({$sTipos}) THEN 1 ELSE l04_codigo END, 1)) AS NR_LOTE",
            'l04_descricao AS lote',
            "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " AND l44_sigla = 'CPC' THEN TO_CHAR(MAX(l11_data), 'DD/MM/YYYY')
            END AS dt_homologacao",
            'l20_tipojulg AS tp_nivel_julgamento',
            'pc21_orcamforne',
            'pc22_orcamitem'
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $aWhere[] = 'l20_licsituacao IN (' . implode(', ', $aSituacoes) . ' ) ';
        $aWhere[] = "l44_sigla NOT IN ('RPO', 'PRD', 'PRI')";
        $aWhere[] = '(l17_situacao <> 2 OR l17_situacao IS NULL)';

        $sWhereProposta = implode(' and ', $aWhere);
        $sGroupBy = 'l20_codigo, l21_codliclicita, l21_codigo, l44_sequencial, z01_numcgm, pc24_pontuacao, pc22_orcamitem, pc23_orcamitem, pc21_orcamforne, pc23_orcamforne, l04_descricao, pc32_orcamitem, l16_cadattdinamicovalorgrupo, l21_ordem';
        $sSqlLotes = $oDaoLicitacao->sql_query_propostas(implode(', ', $aCampos), $sWhereProposta,
            $sGroupBy . ' order by l20_codigo, l21_codigo');

        $rsLotes = db_query($sSqlLotes);
        if (!$rsLotes) {
            $sMsgErro = "Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.";
            throw new DBException($sMsgErro);
        }

        $this->processarDesclassificacaoPorTipoDeJulgamento($rsLotes);

        $aLicitacoes = array();
        $iTotalLotes = pg_num_rows($rsLotes);
        for ($iLinha = 0; $iLinha < $iTotalLotes; $iLinha++) {
            $oLinha = db_utils::fieldsMemory($rsLotes, $iLinha);

            if (in_array($oLinha->cd_tipo_modalidade, array(
                    'CNS',
                    'PRE',
                    'PRP',
                    'LEI',
                    'LEE'
                )) && !LicitanteLicitaCon::fornecedorGanhouItens($oLinha->pc21_orcamforne)) {
                continue;
            }

            if ($oLinha->vl_nota_tecnica) {
                $oLinha->vl_nota_tecnica = number_format($oLinha->vl_nota_tecnica, 2, ',', '');
            }

            $oLinha->vl_total_item = (float)$oLinha->vl_total_item;
            $oLinha->vl_unitario = (float)$oLinha->vl_unitario;

            if (empty($oLinha->vl_total_item) && empty($oLinha->vl_unitario)) {
                continue;
            }

            $lJulgamentoPorItem = $oLinha->tipo_julgamento == licitacao::TIPO_JULGAMENTO_POR_ITEM;

            $oLicitacao = LicitacaoRepository::getByCodigo($oLinha->l20_codigo);

            $oRegra = new RegraProposta($this->oCabecalho->getDataGeracao());
            $oRegra->setLicitacao($oLicitacao);
            $oRegra->setFornecedor(new \OrcamentoFornecedor($oLinha->pc21_orcamforne));
            $oRegra->setItem(new \ItemOrcamento($oLinha->pc22_orcamitem));
            $sResultadoProposta = $oRegra->getResultadoLicitacaoPorItem();

            $processoCompraTaxa = new ProcessoCompraTaxa($oLicitacao->getCodigo(), licitacao::TIPO_JULGAMENTO_POR_ITEM,
                $oLinha->pc22_orcamitem, $oLinha->pc21_orcamforne);

            $resultadoHabilitacao = new ResultadoHabilitacao($oLinha->pc21_orcamforne, $oLicitacao,
                licitacao::TIPO_JULGAMENTO_POR_ITEM, $this->oRegra->getVersao());

            $oStdItem = new stdClass;
            $oStdItem->NR_LICITACAO = $oLinha->nr_licitacao;
            $oStdItem->ANO_LICITACAO = $oLinha->ano_licitacao;
            $oStdItem->CD_TIPO_MODALIDADE = $oLinha->cd_tipo_modalidade;
            $oStdItem->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oLinha->z01_numcgm);
            $oStdItem->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oLinha->z01_numcgm);
            $oStdItem->NR_LOTE = $oLinha->nr_lote;
            $oStdItem->NR_ITEM = $oLinha->nr_item;
            $oStdItem->PC_DESCONTO = $oLinha->pc_desconto ? number_format($oLinha->pc_desconto, 2, ',', '') : '';

            if ($resultadoHabilitacao->obterValor() == 'I') {
                $oStdItem->VL_TOTAL_ITEM = $oStdItem->VL_UNITARIO = '0,00';
            } else {
                $oStdItem->VL_TOTAL_ITEM = $oLinha->vl_total_item ? number_format($oLinha->vl_total_item, 2,
                    ',', '') : '0,00';
                $oStdItem->VL_UNITARIO = $oLinha->vl_unitario ? number_format($oLinha->vl_unitario, 3, ',',
                    '') : '0,00';
            }

            $oStdItem->VL_NOTA_TECNICA = $oLinha->vl_nota_tecnica;
            $oStdItem->TP_RESULTADO_PROPOSTA = $sResultadoProposta;
            $oStdItem->DT_HOMOLOGACAO = null;
            $oStdItem->TP_NIVEL_JULGAMENTO = $oLinha->tp_nivel_julgamento;
            $oStdItem->lote = $oLinha->lote;
            $oStdItem->PC_BDI = null;
            $oStdItem->PC_ENCARGOS_SOCIAIS = null;
            $oStdItem->pc21_orcamforne = $oLinha->pc21_orcamforne;
            $oStdItem->PC_TX = $processoCompraTaxa->obterValorHomologado();
            $oStdItem->TP_RESULTADO_HABILITACAO = $resultadoHabilitacao->obterValor();

            if ($lJulgamentoPorItem && $oLinha->cd_tipo_modalidade == licitacao::MODALIDADE_CHAMAMENTO_PUBLICO_CREDENCIAMENTO) {
                $oStdItem->DT_HOMOLOGACAO = $oLinha->dt_homologacao;
            }

            $oAtributosDinamicos = $this->getAtributosDinamicos($oLinha->l16_cadattdinamicovalorgrupo);
            if ($oAtributosDinamicos->sTipoObjeto == self::TP_OBJETO_OBRAS_SERVICO_ENGENHARIA) {
                $oStdItem->PC_BDI = empty($oLinha->pc23_bdi) ? null : number_format($oLinha->pc23_bdi, 2, ',', '');
                $oStdItem->PC_ENCARGOS_SOCIAIS = $oLinha->pc23_encargossociais ? number_format($oLinha->pc23_encargossociais,
                    2, ',', '') : null;
            }

            if ($oStdItem->TP_NIVEL_JULGAMENTO == licitacao::TIPO_JULGAMENTO_POR_LOTE || $oStdItem->TP_NIVEL_JULGAMENTO == licitacao::TIPO_JULGAMENTO_GLOBAL) {
                if (!empty($aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][0])) {
                    $oAux = $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][0];

                    foreach ($aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote] as $oItem) {
                        if ($oStdItem->NR_LOTE < $oItem->NR_LOTE) {
                            $oItem->NR_LOTE = $oStdItem->NR_LOTE;
                        } else {
                            $oStdItem->NR_LOTE = $oItem->NR_LOTE;
                        }

                        if ($oStdItem->TP_RESULTADO_PROPOSTA == 'D' || $oAux->TP_RESULTADO_PROPOSTA == 'D') {
                            $oItem->TP_RESULTADO_PROPOSTA = $oStdItem->TP_RESULTADO_PROPOSTA = 'D';
                        }
                    }
                }
            }
            $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][] = $oStdItem;
        }

        $aItens = array();
        foreach ($aLicitacoes as $iCodigoLicitacao => $aLicitante) {
            foreach ($aLicitante as $aLote) {
                foreach ($aLote as $sDescricaoLote => $aItem) {
                    $sHashLote = "{$iCodigoLicitacao}#{$sDescricaoLote}";
                    if (array_key_exists($sHashLote, $this->aLotesDesclassificados)) {
                        continue;
                    }

                    foreach ($aItem as $oItem) {
                        if (in_array($oItem->pc21_orcamforne, $this->aPropostasDesclassificadas)) {
                            continue;
                        }

                        $sHashItem = "{$iCodigoLicitacao}#{$oItem->NR_ITEM}#{$oItem->pc21_orcamforne}";
                        if (array_key_exists($sHashItem, $this->aItensDesclassificados)) {
                            continue;
                        }
                        $aItens[] = $oItem;
                    }
                }
            }
        }

        return $aItens;
    }

    /**
     * @param $rsBuscaPropostas
     * @return bool
     */
    private function processarDesclassificacaoPorTipoDeJulgamento($rsBuscaPropostas)
    {
        $aPropostasLicitacao = array();
        $iTotalRegistros = pg_num_rows($rsBuscaPropostas);
        for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {
            $oStdProposta = db_utils::fieldsMemory($rsBuscaPropostas, $iRow);

            if (empty($aPropostasLicitacao[$oStdProposta->l20_codigo])) {
                $aPropostasLicitacao[$oStdProposta->l20_codigo] = new stdClass;
                $aPropostasLicitacao[$oStdProposta->l20_codigo]->tipo_julgamento = $oStdProposta->tp_nivel_julgamento;
                $aPropostasLicitacao[$oStdProposta->l20_codigo]->itens = array();
            }

            if ($oStdProposta->tp_nivel_julgamento == licitacao::TIPO_JULGAMENTO_POR_LOTE) {
                if (empty($aPropostasLicitacao[$oStdProposta->l20_codigo]->lotes)) {
                    $aPropostasLicitacao[$oStdProposta->l20_codigo]->lotes = array();
                }
                $sHashLote = "{$oStdProposta->l20_codigo}#{$oStdProposta->lote}";
                $aPropostasLicitacao[$oStdProposta->l20_codigo]->lotes[$sHashLote][] = $oStdProposta;
            } else {
                $aPropostasLicitacao[$oStdProposta->l20_codigo]->itens[] = $oStdProposta;
            }
        }

        foreach ($aPropostasLicitacao as $iCodigoLicitacao => $oStdLicitacao) {
            switch ($oStdLicitacao->tipo_julgamento) {
                case licitacao::TIPO_JULGAMENTO_GLOBAL:

                    $aItensAgrupadosPorFornecedor = array();
                    foreach ($oStdLicitacao->itens as $oStdItem) {
                        $sHash = "{$iCodigoLicitacao}#{$oStdItem->pc21_orcamforne}";
                        $aItensAgrupadosPorFornecedor[$sHash][$oStdItem->nr_item] = $oStdItem;
                    }

                    foreach ($aItensAgrupadosPorFornecedor as $sHash => $aItens) {
                        $lPrecisaExcluir = false;
                        foreach ($aItens as $iOrdemItem => $oStdItem) {
                            if (empty($oStdItem->vl_unitario)) {
                                $lPrecisaExcluir = true;
                            }
                        }

                        if ($lPrecisaExcluir) {
                            $aHash = explode('#', $sHash);
                            $this->aPropostasDesclassificadas[$aHash[1]] = $aHash[1];
                        }
                    }
                    break;

                case licitacao::TIPO_JULGAMENTO_POR_ITEM:

                    foreach ($oStdLicitacao->itens as $oStdItem) {
                        if (empty($oStdItem->vl_unitario)) {
                            $sHashExclusao = "{$iCodigoLicitacao}#{$oStdItem->nr_item}#{$oStdItem->pc21_orcamforne}";
                            $this->aItensDesclassificados[$sHashExclusao] = $oStdItem;
                        }
                    }
                    break;

                case licitacao::TIPO_JULGAMENTO_POR_LOTE:

                    foreach ($oStdLicitacao->lotes as $sDescricaoLote => $oStdItem) {
                        if (empty($oStdItem->vl_unitario)) {
                            $sHashLote = "{$iCodigoLicitacao}#{$sDescricaoLote}";
                            $this->aLotesDesclassificados[$sHashLote] = $sHashLote;
                        }
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * @param $iAtributoDinamicoValorGrupo
     * @return stdClass
     */
    private function getAtributosDinamicos($iAtributoDinamicoValorGrupo)
    {
        $oStdAtributosDinamicos = new stdClass;
        $oStdAtributosDinamicos->sTipoObjeto = null;
        $oStdAtributosDinamicos->sTipoOrcamento = null;

        if (empty($iAtributoDinamicoValorGrupo)) {
            return $oStdAtributosDinamicos;
        }

        $aValoresAtributosDinamicos = DBAttDinamicoValor::getValores($iAtributoDinamicoValorGrupo);

        foreach ($aValoresAtributosDinamicos as $oValor) {
            switch ($oValor->getAtributo()->getNome()) {
                case 'tipoobjeto':
                    $oStdAtributosDinamicos->sTipoObjeto = $oValor->getValor();
                    break;
            }
        }

        if ($oStdAtributosDinamicos->sTipoObjeto != self::TP_OBJETO_OBRAS_SERVICO_ENGENHARIA) {
            $oStdAtributosDinamicos->sTipoOrcamento = null;
        }

        return $oStdAtributosDinamicos;
    }

}
