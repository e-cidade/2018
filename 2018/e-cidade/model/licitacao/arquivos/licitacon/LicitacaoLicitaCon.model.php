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
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Licitacao as Regra;

class LicitacaoLicitaCon extends ArquivoLicitaCon
{
    const NOME_ARQUIVO = 'LICITACAO';

    protected $aRemoveQuebraLinhas = array('DS_OBJETO');

    /**
     * LicitacaoLicitaCon constructor.
     *
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
        $aLicitacoes = array();
        $aCampos = array(
            'distinct l20_codigo',
            'l20_procadmin',
            'l20_usaregistropreco',
            'l20_tipojulg',
            'l20_licsituacao',
            'l20_objeto',
            'l20_dataaber',
            'l20_tipo',
            'p58_numero',
            'p58_ano',
            'l30_portaria',
            'extract(YEAR from l30_data) as ano_comissao',
            'l30_tipo',
            'l16_cadattdinamicovalorgrupo',
            '(select l11_data from liclicitasituacao where l11_liclicita = l20_codigo and l11_licsituacao = ' . SituacaoLicitacao::SITUACAO_ADJUDICADA . ') as data_adjudicacao',
            '(select l11_data from liclicitasituacao where l11_liclicita = l20_codigo and l11_licsituacao = ' . SituacaoLicitacao::SITUACAO_HOMOLOGADA . ') as data_homologacao'
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $oDaoLicLicita = new cl_liclicita;
        $sSqlLicitacao = $oDaoLicLicita->sql_query_licitacon(implode(', ', $aCampos), implode(' and ', $aWhere));
        $rsLicitacao = db_query($sSqlLicitacao);

        if ($rsLicitacao === false) {
            $sMsgErro = "Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.";
            throw new DBException($sMsgErro);
        }

        $iTotalLicitacoes = pg_num_rows($rsLicitacao);
        for ($iLicitacao = 0; $iLicitacao < $iTotalLicitacoes; $iLicitacao++) {
            $sSqlTotalLicitacao = null;
            $oStdLicitacao = db_utils::fieldsMemory($rsLicitacao, $iLicitacao);

            $oLicitacao = LicitacaoRepository::getByCodigo($oStdLicitacao->l20_codigo);
            $sModalidade = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
            $this->oRegra->setLicitacao($oLicitacao);

            $oDataAbertura = new DBDate($oStdLicitacao->l20_dataaber);

            $aProcesso = explode('/', $oStdLicitacao->l20_procadmin);
            if (count($aProcesso) != 2) {
                $aProcesso[0] = null;
                $aProcesso[1] = $oStdLicitacao->l20_procadmin;
            }
            $numeroProcesso = filter_var($aProcesso[0], FILTER_SANITIZE_NUMBER_INT);
            $iAnoProcesso = filter_var($aProcesso[1], FILTER_SANITIZE_NUMBER_INT);

            $oDados = new stdClass;
            $this->preencheAtributosDinamicos($oStdLicitacao, $oDados, $sModalidade);
            $oFornecedores = $this->oRegra->getFornecedor($oLicitacao);

            $oStdLicitacao->fase_atual = $oLicitacao->getFase();
            $processoCompraTaxa = new ProcessoCompraTaxa($oStdLicitacao->l20_codigo, licitacao::TIPO_JULGAMENTO_GLOBAL);

            $oDados->NR_LICITACAO = $oLicitacao->getEdital();
            $oDados->ANO_LICITACAO = $oLicitacao->getAno();
            $oDados->CD_TIPO_FASE_ATUAL = self::getSiglaFase($oStdLicitacao->fase_atual);
            $oDados->CD_TIPO_MODALIDADE = $sModalidade;
            $oDados->NR_COMISSAO = preg_replace('/[^0-9]/', '', $oStdLicitacao->l30_portaria);
            $oDados->ANO_COMISSAO = $oStdLicitacao->ano_comissao;
            $oDados->TP_COMISSAO = isset(ComissaoLicitaCon::$aTipos[$oStdLicitacao->l30_tipo]) ? ComissaoLicitaCon::$aTipos[$oStdLicitacao->l30_tipo] : '';
            $oDados->NR_PROCESSO = $oStdLicitacao->p58_numero ?: $numeroProcesso;
            $oDados->ANO_PROCESSO = $oStdLicitacao->p58_ano ?: $iAnoProcesso;
            $oDados->TP_NIVEL_JULGAMENTO = $this->oRegra->getTipoJulgamentoSigla();
            $oDados->TP_NATUREZA = $this->oRegra->getTipoNatureza($oLicitacao);
            $oDados->TP_RESULTADO_GLOBAL = $this->oRegra->getResultadoGlobal();
            $oDados->DS_OBJETO = $oStdLicitacao->l20_objeto;
            $oDados->VL_LICITACAO = $this->oRegra->getValorLicitacao($oLicitacao);
            $oDados->DT_ABERTURA = $oDataAbertura->getDate(DBDate::DATA_PTBR);
            $oDados->TP_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->tipo;
            $oDados->NR_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->documento;
            $oDados->TP_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->tipo;
            $oDados->NR_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->documento;
            $oDados->DT_ADJUDICACAO = null;
            $oDados->DT_HOMOLOGACAO = null;
            $oDados->VL_HOMOLOGADO = $this->oRegra->getValorHomologado();
            $oDados->BL_ORCAMENTO_SIGILOSO = $this->oRegra->getBalancoOrcamentoSigiloso($oDados);
            $oDados->BL_GERA_DESPESA = 'S';
            $oDados->DS_OBSERVACAO = null;

            if ($oStdLicitacao->l20_tipo == licitacao::NAO_GERA_DESPESA) {
                $oDados->BL_GERA_DESPESA = 'N';
            }

            if (in_array($oDados->CD_TIPO_MODALIDADE,
                    array('CHP', 'CNC', 'CNV', 'CPC', 'PRE', 'PRP', 'RDC', 'RIN', 'TMP'))
                && empty($oDados->BL_PERMITE_SUBCONTRATACAO)) {
                $oDados->BL_PERMITE_SUBCONTRATACAO = 'N';
            }

            if (!empty($oStdLicitacao->data_adjudicacao)) {
                $oDataAdjudicacao = new DBDate($oStdLicitacao->data_adjudicacao);
                $oDados->DT_ADJUDICACAO = $oDataAdjudicacao->getDate(DBDate::DATA_PTBR);
            }

            if (!empty($oStdLicitacao->data_homologacao)) {
                $oDataHomologacao = new DBDate($oStdLicitacao->data_homologacao);
                $oDados->DT_HOMOLOGACAO = $oDataHomologacao->getDate(DBDate::DATA_PTBR);
            }

            if (in_array($oDados->CD_TIPO_MODALIDADE, array('PRI', 'PRD'))) {
                $oDados->NR_COMISSAO = null;
                $oDados->ANO_COMISSAO = null;
                $oDados->TP_COMISSAO = null;
            }

            if (in_array($oDados->CD_TIPO_MODALIDADE, array('CPC', 'CNS'))) {
                $oDados->DT_ABERTURA = null;
            }

            if (in_array($oDados->CD_TIPO_MODALIDADE, array('CPC'))) {
                $oDados->DT_HOMOLOGACAO = null;
                $oDados->DT_ADJUDICACAO = null;
            }

            $oDados->PC_TX_ESTIMADA = $this->oRegra->getTaxaEstimada();
            $oDados->PC_TX_HOMOLOGADA = $this->oRegra->getTaxaHomologada();
            
            $aLicitacoes[] = $oDados;
        }

        return $aLicitacoes;
    }

    /**
     * Preenche o objeto $oDados com as informações referentes aos atributos dinâmicos de cada Licitação.
     *
     * @param stdClass $oLicitacao
     * @param stdClass $oDados
     * @param string $sModalidade
     */
    private function preencheAtributosDinamicos(stdClass $oLicitacao, $oDados, $sModalidade = null)
    {
        $oDados->TP_OBJETO = null;
        $oDados->TP_LICITACAO = null;
        $oDados->TP_CARACTERISTICA_OBJETO = null;
        $oDados->TP_REGIME_EXECUCAO = null;
        $oDados->BL_PERMITE_SUBCONTRATACAO = null;
        $oDados->TP_BENEFICIO_MICRO_EPP = null;
        $oDados->TP_FORNECIMENTO = null;
        $oDados->PC_TAXA_RISCO = null;
        $oDados->TP_EXECUCAO = null;
        $oDados->TP_DISPUTA = null;
        $oDados->TP_PREQUALIFICACAO = null;
        $oDados->BL_INVERSAO_FASES = 'N';
        $oDados->CD_TIPO_FUNDAMENTACAO = null;
        $oDados->NR_ARTIGO = null;
        $oDados->DS_INCISO = null;
        $oDados->DS_LEI = null;
        $oDados->DT_INICIO_INSCR_CRED = null;
        $oDados->DT_FIM_INSCR_CRED = null;
        $oDados->DT_INICIO_VIGEN_CRED = null;
        $oDados->DT_FIM_VIGEN_CRED = null;
        $oDados->BL_RECEBE_INSCRICAO_PER_VIG = null;
        $oDados->BL_PERMITE_CONSORCIO = null;

        /* Adesão a ata de Registro de Preço */
        $oDados->BL_LICIT_PROPRIA_ORGAO = 'S';
        $oDados->DT_AUTORIZACAO_ADESAO = null;
        $oDados->TP_ATUACAO_REGISTRO = null;
        $oDados->NR_LICITACAO_ORIGINAL = null;
        $oDados->ANO_LICITACAO_ORIGINAL = null;
        $oDados->NR_ATA_REGISTRO_PRECO = null;
        $oDados->DT_ATA_REGISTRO_PRECO = null;
        $oDados->CNPJ_ORGAO_GERENCIADOR = null;
        $oDados->NM_ORGAO_GERENCIADOR = null;


        if (empty($oLicitacao->l16_cadattdinamicovalorgrupo)) {
            return;
        }

        $aValoresAtributosDinamicos = DBAttDinamicoValor::getValores($oLicitacao->l16_cadattdinamicovalorgrupo);
        foreach ($aValoresAtributosDinamicos as $oValor) {
            $sValor = $oValor->getValor();
            switch ($oValor->getAtributo()->getNome()) {
                case "tipoobjeto":
                    $oDados->TP_OBJETO = $sValor;
                    break;

                case "tipolicitacao":
                    $oDados->TP_LICITACAO = $sValor;
                    break;

                case "caracteristicaobjeto":
                    $oDados->TP_CARACTERISTICA_OBJETO = $sValor;
                    break;

                case "regimeexecucao":
                    $oDados->TP_REGIME_EXECUCAO = $sValor;
                    break;

                case "permitesubcontratacao":
                    if ($sValor) {
                        $oDados->BL_PERMITE_SUBCONTRATACAO = strtolower($sValor) == 't' ? 'S' : 'N';
                    }
                    break;

                case "tipobeneficiomicroepp":
                    $oDados->TP_BENEFICIO_MICRO_EPP = $this->oRegra->tipoBeneficioMicroempresaEmpresaPequenoPorte($sValor);
                    break;

                case "tipofornecimento":
                    $oDados->TP_FORNECIMENTO = $sValor;
                    break;

                case "pctaxarisco":
                    $oDados->PC_TAXA_RISCO = $this->oRegra->getPcTaxaRisco($sValor, $sModalidade, $oDados);
                    break;

                case "tipoexecucao":
                    $oDados->TP_EXECUCAO = $sValor;
                    break;

                case "tipodisputa":
                    $oDados->TP_DISPUTA = $this->oRegra->getTpDisputa($sValor, $sModalidade);
                    break;

                case "prequalificacao":
                    $oDados->TP_PREQUALIFICACAO = $this->oRegra->getTpPreQualificacao($sValor, $sModalidade);
                    break;

                case "inversaofases":
                    $oDados->BL_INVERSAO_FASES = $sValor == 't' ? 'S' : 'N';
                    break;

                case "codigofundamentacao":
                    $oDados->CD_TIPO_FUNDAMENTACAO = $sValor;
                    break;

                case "numeroartigo":
                    $oDados->NR_ARTIGO = filter_var($sValor, FILTER_VALIDATE_INT);
                    break;

                case "inciso":
                    $oDados->DS_INCISO = $sValor;
                    break;

                case "lei":
                    $oDados->DS_LEI = $sValor;
                    break;

                case "datainicioinscricaocredenciamento":
                    if ($sValor) {
                        $oDataInicioInscsCred = new DBDate($sValor);
                        $oDados->DT_INICIO_INSCR_CRED = $oDataInicioInscsCred->getDate(DBDate::DATA_PTBR);
                    }
                    break;

                case "datafiminscricaocredenciamento":
                    if ($sValor) {
                        $oDataFimInscsCred = new DBDate($sValor);
                        $oDados->DT_FIM_INSCR_CRED = $oDataFimInscsCred->getDate(DBDate::DATA_PTBR);
                    }
                    break;

                case "datainiciovigenciacredenciamento":
                    if ($sValor) {
                        $oDataInicioVigenciaCred = new DBDate($sValor);
                        $oDados->DT_INICIO_VIGEN_CRED = $oDataInicioVigenciaCred->getDate(DBDate::DATA_PTBR);
                    }
                    break;

                case "datafimvigenciacredenciamento":
                    if ($sValor) {
                        $oDataFimVigenciaCred = new DBDate($sValor);
                        $oDados->DT_FIM_VIGEN_CRED = $oDataFimVigenciaCred->getDate(DBDate::DATA_PTBR);
                    }
                    break;

                case "recebeinscricaoperiodovigencia":
                    $oDados->BL_RECEBE_INSCRICAO_PER_VIG = strtolower($sValor) == 't' ? 'S' : 'N';
                    break;

                case "permiteconsorcio":
                    if ($sValor) {
                        $oDados->BL_PERMITE_CONSORCIO = $sValor == 't' ? 'S' : 'N';
                    }
                    break;
            }

            /*
             * Modalidade de adesão a ata de registro de preço
             */
            if ($sModalidade && $sModalidade == 'RPO') {
                $oDados->BL_LICIT_PROPRIA_ORGAO = null;

                switch ($oValor->getAtributo()->getNome()) {
                    case "cnpjorgaogerenciador":
                        $oDados->CNPJ_ORGAO_GERENCIADOR = str_pad($sValor, 14, '0', STR_PAD_LEFT);
                        break;

                    case "nomeorgaogerenciador":
                        $oDados->NM_ORGAO_GERENCIADOR = $sValor;
                        break;

                    case "numerolicitacao":
                        $oDados->NR_LICITACAO_ORIGINAL = $sValor;
                        break;

                    case "anolicitacao":
                        $oDados->ANO_LICITACAO_ORIGINAL = $sValor;
                        break;

                    case "numeroataregistropreco":
                        $oDados->NR_ATA_REGISTRO_PRECO = $sValor;
                        break;

                    case "dataata":
                        if ($sValor) {
                            $oData = new DBDate($sValor);
                            $oDados->DT_ATA_REGISTRO_PRECO = $oData->getDate(DBDate::DATA_PTBR);
                        }
                        break;

                    case "dataautorizacao":
                        if ($sValor) {
                            $oData = new DBDate($sValor);
                            $oDados->DT_AUTORIZACAO_ADESAO = $oData->getDate(DBDate::DATA_PTBR);
                        }
                        break;

                    case "tipoatuacao":
                        $oDados->TP_ATUACAO_REGISTRO = $sValor;
                        break;
                }
            }
        }
    }

    /**
     * Retorna a sigla da fase da Licitação informada.
     * @param integer $iCodigoFase
     *
     * @return string
     */
    public static function getSiglaFase($iCodigoFase)
    {
        $aSiglasFases = array(
            EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO => 'ADH',
            EventoLicitacao::FASE_EDITAL_PUBLICADO => 'EPU',
            EventoLicitacao::FASE_HABILITACAO_PROPOSTAS => 'HAP',
            EventoLicitacao::FASE_INTERNA => 'INT',
            EventoLicitacao::FASE_PUBLICACAO => 'PUB'
        );

        if (!array_key_exists($iCodigoFase, $aSiglasFases)) {
            return null;
        }

        return $aSiglasFases[$iCodigoFase];
    }

    /**
     * Retorna um array com as cláusulas padrão para a busca de licitações
     *
     * @param Instituicao $oInstituicao
     * @param DBDate $oData
     * @return array
     */
    public static function getWhereLicitacao(Instituicao $oInstituicao, DBDate $oData)
    {
        $aWhere = array(
            "l20_instit = {$oInstituicao->getCodigo()}",
            "(l18_sequencial is null or l18_data >= '{$oData->getDate(DBDate::DATA_EN)}')",
            "exists (select 1 from liclicitem itemlicitacao where itemlicitacao.l21_codliclicita = liclicita.l20_codigo)"
        );

        return $aWhere;
    }
}
