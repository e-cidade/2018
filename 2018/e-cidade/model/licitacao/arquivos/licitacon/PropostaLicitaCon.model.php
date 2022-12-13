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

use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ProcessoCompraTaxa;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Proposta;

/**
 * Class PropostaLicitaCon
 */
class PropostaLicitaCon extends ArquivoLicitaCon
{
    /**
     * @var string
     */
    const NOME_ARQUIVO = 'PROPOSTA';

    /**
     * @var array
     */
    private $aDadosProposta = array();

    /**
     * PropostaLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Proposta($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDados()
    {
        if (count($this->aDadosProposta) == 0) {
            $this->preparaPropostas();
        }
        return $this->aDadosProposta;
    }

    /**
     * @throws Exception
     */
    private function preparaPropostas()
    {
        $aSituacoes = array(
            SituacaoLicitacao::SITUACAO_JULGADA,
            SituacaoLicitacao::SITUACAO_ADJUDICADA,
            SituacaoLicitacao::SITUACAO_HOMOLOGADA
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $aWhere[] = 'l20_licsituacao IN (' . implode(', ', $aSituacoes) . ')';
        $aWhere[] = "l44_sigla NOT IN ('RPO','PRD','PRI')";
        $aWhere[] = '(pcorcamfornelichabilitacao.l17_situacao <> 2 or pcorcamfornelichabilitacao.l17_situacao IS NULL)';

        $aCampos = array(
            'pc23_orcamforne AS codigo_fornecedor',
            'l20_codigo AS codigo_licitacao',
            'l20_numero AS numero_licitacao',
            'l20_anousu AS ano_licitacao',
            'l44_sigla AS sigla_modalidade',
            'z01_cgccpf AS documento_fornecedor',
            'z01_numcgm',
            'l20_dataaber AS data_proposta',
            'SUM(pc23_valor) AS valor_total',
            'l20_tipojulg AS tipo_julgamento',
            'SUM(coalesce(pc23_notatecnica, 0)) AS nota_tecnica'
        );

        $sOrder = ' ORDER BY sigla_modalidade, numero_licitacao, documento_fornecedor ';
        $sGroup = ' GROUP BY pc23_orcamforne, z01_numcgm, l20_codigo, l20_numero, l20_anousu, l44_sigla, z01_cgccpf, l20_dataaber, l20_tipojulg';
        $sWhere = implode(' AND ', $aWhere);
        $sWhere .= " {$sGroup} {$sOrder} ";

        $oDaoOrcamento = new cl_pcorcamval;
        $sSqlOrcamento = $oDaoOrcamento->sql_query_proposta_licitacao(implode(',', $aCampos), $sWhere);
        $rsBuscaOrcamento = db_query($sSqlOrcamento);

        if (!$rsBuscaOrcamento) {
            throw new Exception('Não foi possível carregar as propostas para criação do arquivo PROPOSTAS.TXT.');
        }

        $iNumeroLinhas = pg_num_rows($rsBuscaOrcamento);
        $aFornecedorIgnorar = array();
        for ($iRowProposta = 0; $iRowProposta < $iNumeroLinhas; $iRowProposta++) {
            $oStdDadosProposta = db_utils::fieldsMemory($rsBuscaOrcamento, $iRowProposta);
            $sTipoDocumento = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdDadosProposta->z01_numcgm);
            $lJulgamentoGlobal = $oStdDadosProposta->tipo_julgamento == licitacao::TIPO_JULGAMENTO_GLOBAL;

            /*
             * Validação que verifica somente as propostas vencedoras nas modalidades colocadas no array
             */
            if (in_array($oStdDadosProposta->sigla_modalidade, array('CNS', 'PRE', 'PRP', 'LEI', 'LEE')) &&
                !LicitanteLicitaCon::fornecedorGanhouItens($oStdDadosProposta->codigo_fornecedor)) {
                continue;
            }

            if (empty($oStdDadosProposta->valor_total)) {
                continue;
            }

            $sDataProposta = '';
            if ($oStdDadosProposta->data_proposta) {
                $oDataProposta = new DBDate($oStdDadosProposta->data_proposta);
                $sDataProposta = $oDataProposta->getDate(DBDate::DATA_PTBR);
            }

            $licitacao = LicitacaoRepository::getByCodigo($oStdDadosProposta->codigo_licitacao);

            $oProposta = new Proposta($this->oCabecalho->getDataGeracao());
            $oProposta->setLicitacao($licitacao);
            $oProposta->setFornecedor(new OrcamentoFornecedor($oStdDadosProposta->codigo_fornecedor));
            $sTipoResultado = $oProposta->getResultadoLicitacaoGlobal();

            $nValorNotaTecnica = null;
            if ($oStdDadosProposta->nota_tecnica) {
                $nValorNotaTecnica = number_format($oStdDadosProposta->nota_tecnica, 2, ',', '');
            }

            $processoCompraTaxa = new ProcessoCompraTaxa($licitacao->getCodigo(), licitacao::TIPO_JULGAMENTO_GLOBAL);

            $oStdRetornoPropostas = new stdClass;
            $oStdRetornoPropostas->NR_LICITACAO = $oStdDadosProposta->numero_licitacao;
            $oStdRetornoPropostas->ANO_LICITACAO = $oStdDadosProposta->ano_licitacao;
            $oStdRetornoPropostas->CD_TIPO_MODALIDADE = $oStdDadosProposta->sigla_modalidade;
            $oStdRetornoPropostas->TP_DOCUMENTO_LICITANTE = $sTipoDocumento;
            $oStdRetornoPropostas->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oStdDadosProposta->z01_numcgm);
            $oStdRetornoPropostas->DT_PROPOSTA = $sDataProposta;
            $oStdRetornoPropostas->TP_RESULTADO_PROPOSTA = $sTipoResultado;
            $oStdRetornoPropostas->VL_TOTAL_PROPOSTA = str_replace('.', '',
                db_formatar($oStdDadosProposta->valor_total, 'f'));
            $oStdRetornoPropostas->VL_NOTA_TECNICA = $nValorNotaTecnica;
            $oStdRetornoPropostas->DT_HOMOLOGACAO = null;
            $oStdRetornoPropostas->PC_TX = $processoCompraTaxa->obterValorHomologado();

            if ($oStdDadosProposta->sigla_modalidade == licitacao::MODALIDADE_CHAMAMENTO_PUBLICO_CREDENCIAMENTO && $lJulgamentoGlobal) {
                $oStdRetornoPropostas->DT_HOMOLOGACAO = $this->getDataHomologacao($oStdDadosProposta->codigo_licitacao);
            }

            //Colunas que vão sempre vazios.
            $oStdRetornoPropostas->PC_DESCONTO = '0,00';

            if (!$lJulgamentoGlobal) {
                $oStdRetornoPropostas->VL_TOTAL_PROPOSTA = null;
            }

            if (!$this->mostrarNotaTecnica($lJulgamentoGlobal, $oStdDadosProposta->codigo_licitacao)) {
                $oStdRetornoPropostas->VL_NOTA_TECNICA = null;
            }

            $this->aDadosProposta[] = $oStdRetornoPropostas;

            /**
             * @todo refatorar
             * busca os fornecedores que não lançaram valores em nenhum dos itens
             */
            $sSqlBuscaFornecedorSemValorLancado = "
                SELECT DISTINCT pcorcamforne.*
                FROM liclicita
                    INNER JOIN liclicitem ON l20_codigo = l21_codliclicita
                    INNER JOIN pcorcamitemlic ON pc26_liclicitem = l21_codigo
                    INNER JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                    INNER JOIN pcorcamforne ON pc22_codorc = pc21_codorc
                    INNER JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                    INNER JOIN pcorcamfornelichabilitacao ON l17_pcorcamfornelic = pc31_orcamforne
                    LEFT JOIN pcorcamval ON pc23_orcamforne = pc21_orcamforne
                WHERE l20_codigo = {$oStdDadosProposta->codigo_licitacao}
                    AND pc23_orcamforne IS NULL
                    AND (l17_situacao <> 2 OR l17_situacao IS NULL);
            ";
            $rsBuscaFornecedorSemValor = db_query($sSqlBuscaFornecedorSemValorLancado);
            if (!$rsBuscaFornecedorSemValor) {
                throw new Exception('Ocorreu um erro ao buscar os fornecedores sem valor lançado na proposta.');
            }
            $iTotalFornecedor = pg_num_rows($rsBuscaFornecedorSemValor);
            if ($iTotalFornecedor) {
                for ($iRowFornecedor = 0; $iRowFornecedor < $iTotalFornecedor; $iRowFornecedor++) {
                    $oStdDadosFornecedor = db_utils::fieldsMemory($rsBuscaFornecedorSemValor, $iRowFornecedor);

                    if ($aFornecedorIgnorar[$oStdDadosFornecedor->pc21_orcamforne] || in_array($oStdDadosProposta->sigla_modalidade,
                            array('CNS', 'PRE', 'PRP', 'LEI', 'LEE'))) {
                        continue;
                    }

                    $processoCompraTaxa = new ProcessoCompraTaxa($oStdDadosProposta->codigo_licitacao,
                        licitacao::TIPO_JULGAMENTO_GLOBAL);

                    $oStdRetornoPropostas = new stdClass;
                    $oStdRetornoPropostas->NR_LICITACAO = $oStdDadosProposta->numero_licitacao;
                    $oStdRetornoPropostas->ANO_LICITACAO = $oStdDadosProposta->ano_licitacao;
                    $oStdRetornoPropostas->CD_TIPO_MODALIDADE = $oStdDadosProposta->sigla_modalidade;
                    $oStdRetornoPropostas->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdDadosFornecedor->pc21_numcgm);;
                    $oStdRetornoPropostas->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oStdDadosFornecedor->pc21_numcgm);
                    $oStdRetornoPropostas->DT_PROPOSTA = $sDataProposta;
                    $oStdRetornoPropostas->TP_RESULTADO_PROPOSTA = Proposta::RESULTADO_DESCLASSIFICADO;
                    $oStdRetornoPropostas->VL_TOTAL_PROPOSTA = '0,00';
                    $oStdRetornoPropostas->VL_NOTA_TECNICA = null;
                    $oStdRetornoPropostas->DT_HOMOLOGACAO = null;
                    $oStdRetornoPropostas->PC_DESCONTO = '0,00';
                    $oStdRetornoPropostas->PC_TX = $processoCompraTaxa->obterValorHomologado();
                    $this->aDadosProposta[] = $oStdRetornoPropostas;
                    $aFornecedorIgnorar[$oStdDadosFornecedor->pc21_orcamforne] = $oStdDadosFornecedor->pc21_orcamforne;
                }
            }

            unset($oStdPropostas, $oStdDadosProposta);
        }
    }

    /**
     * Verifica se deve mostrar o valor da nota técnica.
     * @param boolean $lJulgamentoGlobal Se o tipo de julgamento é global.
     * @param int $iCodigoLicitacao Código da licitação.
     *
     * @return bool
     */
    private static function mostrarNotaTecnica($lJulgamentoGlobal, $iCodigoLicitacao)
    {
        if (!$lJulgamentoGlobal) {
            return false;
        }

        $oLicitacaoDinamico = new LicitacaoAtributosDinamicos;
        $oLicitacaoDinamico->setCodigoLicitacao($iCodigoLicitacao);
        $sValorAtributo = $oLicitacaoDinamico->getAtributo('tipolicitacao');
        $aTiposLicitacao = array('MCA', 'MOQ', 'MOT', 'MPP', 'MTC', 'MTO', 'MTT', 'TPR');
        return in_array($sValorAtributo, $aTiposLicitacao);
    }

    /**
     * Busca a data de homologação, quando está é necessária.
     * @param int $iLicitacao Código da licitação.
     *
     * @return null|string
     * @throws DBException
     */
    private function getDataHomologacao($iLicitacao)
    {
        $sDataHomologacao = null;

        $sCampos = ' l11_data ';
        $sWhere = " l11_liclicita = {$iLicitacao} AND l11_licsituacao = " . SituacaoLicitacao::SITUACAO_HOMOLOGADA . ' ';

        $oDaoLicitacaoSituacao = new cl_liclicitasituacao;
        $sSqlLicitacaoSituacao = $oDaoLicitacaoSituacao->sql_query_file(null, $sCampos, null, $sWhere);
        $rsLicitacaoSituacao = db_query($sSqlLicitacaoSituacao);

        if (!$rsLicitacaoSituacao) {
            throw new DBException('Houve um erro ao buscar a data de homologação da licitação.');
        }

        if (pg_num_rows($rsLicitacaoSituacao)) {
            $oDataHomologacao = new DBDate(db_utils::fieldsMemory($rsLicitacaoSituacao, 0)->l11_data);
            $sDataHomologacao = $oDataHomologacao->getDate(DBDate::DATA_PTBR);
        }
        return $sDataHomologacao;
    }
}
