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

use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ProcessoCompraTaxa;
use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ResultadoHabilitacao;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\LoteProp as RegraLoteProposta;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Proposta as RegraProposta;

/**
 * Class LotePropLicitaCon
 */
class LotePropLicitaCon extends ArquivoLicitaCon
{

    /**
     * @var string
     */
    const NOME_ARQUIVO = 'LOTE_PROP';

    /**
     * LotePropLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new RegraLoteProposta($oCabecalho->getDataGeracao()));
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
            'l44_sigla AS cd_tipo_modalidade',
            'l20_tipojulg AS tipo_julgamento',
            "CASE
                WHEN l44_sigla IN ('CPC', 'MAI', 'RPO', 'PRD', 'PRI') THEN NULL
                WHEN pc32_orcamitem IS NOT NULL THEN 'D'
                WHEN pc23_vlrun IS NULL THEN 'D'
                ELSE 'C'
              END AS tp_resultado_proposta",
            "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_LOTE . " THEN pc23_percentualdesconto
                ELSE NULL
              END AS pc_desconto",
            "ROUND(pc23_vlrun * pc23_quant, 2) AS vl_total_item",
            "CASE
                WHEN l44_sigla IN ('MCA', 'MOQ', 'MOT', 'MPP', 'MTC', 'MTO', 'MTT', 'TPR') AND l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_LOTE . " THEN pc23_notatecnica
                ELSE NULL
              END AS vl_nota_tecnica
              ",
            "MIN(COALESCE(CASE WHEN l20_tipojulg IN ({$sTipos}) THEN 1 ELSE l04_codigo END, 1)) AS nr_lote",
            "l04_descricao AS lote",
            "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_LOTE . " AND l44_sigla = 'CPC' THEN TO_CHAR(MAX(l11_data), 'DD/MM/YYYY')
              END AS dt_homologacao, pc23_orcamforne, pc22_orcamitem"
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $aWhere[] = "l44_sigla NOT IN ('RPO', 'PRD', 'PRI')";
        $aWhere[] = 'l20_licsituacao IN (' . implode(', ', $aSituacoes) . ' ) ';
        $aWhere[] = '(l17_situacao <> 2 OR l17_situacao IS NULL)';

        $sGroupBy = 'l20_codigo, l21_codliclicita, l44_sequencial, z01_numcgm, pc24_pontuacao, pc23_orcamitem, pc23_orcamforne, pc22_orcamitem, l04_descricao, pc32_orcamitem, l20_tipojulg';
        $sSqlLotes = $oDaoLicitacao->sql_query_propostas(implode(', ', $aCampos), implode(' AND ', $aWhere), $sGroupBy);

        $rsLotes = db_query($sSqlLotes);
        if (!$rsLotes) {
            $sMsgErro = "Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.";
            throw new DBException($sMsgErro);
        }

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
                )) && !LicitanteLicitaCon::fornecedorGanhouItens($oLinha->pc23_orcamforne)) {
                continue;
            }

            if (empty($oLinha->vl_total_item)) {
                continue;
            }

            $sAgrupador = $oLinha->lote;

            $oLicitacao = LicitacaoRepository::getByCodigo($oLinha->l20_codigo);

            $oRegra = new RegraProposta($this->oCabecalho->getDataGeracao());
            $oRegra->setLicitacao($oLicitacao);
            $oRegra->setFornecedor(new \OrcamentoFornecedor($oLinha->pc23_orcamforne));
            $oRegra->setItem(new \ItemOrcamento($oLinha->pc22_orcamitem));
            $sResultado = $oRegra->getResultadoLicitacaoPorLote();

            $lJulgamentoLote = true;
            if ($oLinha->tipo_julgamento != licitacao::TIPO_JULGAMENTO_POR_LOTE) {
                $sAgrupador = '1';
                $lJulgamentoLote = false;
            }

            $resultadoHabilitacao = new ResultadoHabilitacao(
                $oLinha->pc23_orcamforne,
                $oLicitacao,
                licitacao::TIPO_JULGAMENTO_POR_LOTE,
                $this->oRegra->getVersao()
            );

            if (!isset($aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$sAgrupador])) {
                $oStdLote = new stdClass;
                $oStdLote->NR_LICITACAO = $oLinha->nr_licitacao;
                $oStdLote->ANO_LICITACAO = $oLinha->ano_licitacao;
                $oStdLote->CD_TIPO_MODALIDADE = $oLinha->cd_tipo_modalidade;
                $oStdLote->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oLinha->z01_numcgm);
                $oStdLote->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oLinha->z01_numcgm);
                $oStdLote->NR_LOTE = $oLinha->nr_lote;
                $oStdLote->PC_DESCONTO = null;

                if ($resultadoHabilitacao->obterValor() == 'I') {
                    $oStdLote->VL_TOTAL_LOTE = '0,00';
                } else {
                    $oStdLote->VL_TOTAL_LOTE = $oLinha->vl_total_item ?: 0;
                }

                $oStdLote->VL_NOTA_TECNICA = null;
                $oStdLote->TP_RESULTADO_PROPOSTA = $sResultado;
                $oStdLote->DT_HOMOLOGACAO = null;
                $oStdLote->TP_RESULTADO_HABILITACAO = $resultadoHabilitacao->obterValor();

                $processoCompraTaxa = new ProcessoCompraTaxa($oLicitacao->getCodigo(),
                    licitacao::TIPO_JULGAMENTO_POR_LOTE, $oLinha->pc22_orcamitem, $oLinha->pc23_orcamforne);

                $oStdLote->PC_TX = $processoCompraTaxa->obterValorHomologado();

                if ($lJulgamentoLote) {
                    if (is_numeric($oLinha->vl_nota_tecnica)) {
                        $oStdLote->VL_NOTA_TECNICA = $oLinha->vl_nota_tecnica;
                    }

                    if ($oLinha->cd_tipo_modalidade == licitacao::MODALIDADE_CHAMAMENTO_PUBLICO_CREDENCIAMENTO) {
                        $oStdLote->DT_HOMOLOGACAO = $oLinha->dt_homologacao;
                    }
                }

                $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$sAgrupador] = $oStdLote;
            } else {
                $oLote = $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$sAgrupador];
                if ($oLinha->nr_lote < $oLote->NR_LOTE) {
                    $oLote->NR_LOTE = $oLinha->nr_lote;
                }

                if ($oLinha->tp_resultado_proposta == 'D') {
                    $oLote->TP_RESULTADO_PROPOSTA = 'D';
                }
                if ($lJulgamentoLote && is_numeric($oLinha->vl_nota_tecnica)) {
                    $oLote->VL_NOTA_TECNICA += $oLinha->vl_nota_tecnica;
                }

                if ($resultadoHabilitacao->obterValor() == 'I') {
                    $oLote->VL_TOTAL_LOTE = '0,00';
                } else {
                    $oLote->VL_TOTAL_LOTE += $oLinha->vl_total_item;
                }
            }
        }

        $aLotes = array();
        foreach ($aLicitacoes as $aLicitante) {
            foreach ($aLicitante as $aLote) {
                foreach ($aLote as $oLote) {
                    if (empty($oLote->VL_TOTAL_LOTE)) {
                        continue;
                    }

                    $oLote->VL_TOTAL_LOTE = $oLote->TP_RESULTADO_HABILITACAO == 'I'
                        ? '0,00'
                        : number_format($oLote->VL_TOTAL_LOTE, 2, ',', '');

                    $oLote->VL_NOTA_TECNICA = is_null($oLote->VL_NOTA_TECNICA) ? null : number_format($oLote->VL_NOTA_TECNICA,
                        2, ',', '');
                    $aLotes[] = $oLote;
                }
            }
        }

        return $aLotes;
    }
}
