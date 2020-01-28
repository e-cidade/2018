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

use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ResultadoHabilitacao;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Licitante as Regra;

/**
 * Class LicitanteLicitaCon
 */
class LicitanteLicitaCon extends ArquivoLicitaCon
{

    /**
     * @var string
     */
    const NOME_ARQUIVO = 'LICITANTE';

    /**
     * @type stdClass[]
     */
    private $aDadosLicitante = array();

    /**
     * LicitanteLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return stdClass[]
     */
    public function getDados()
    {
        $aCampos = array(
            'liclicita.l20_codigo as codigo',
            'cgm.z01_numcgm as numcgm',
            'pcorcamfornelic.pc31_orcamforne as fornecedor_licitacao',
            'pcorcamfornelic.pc31_liclicitatipoempresa as tipo_empresa',
        );

        $this->prepararLicitantesVencedores($aCampos);
        $this->prepararLicitantesGerais($aCampos);
        return $this->aDadosLicitante;
    }

    /**
     * @param stdClass $oDadosLicitacao
     *
     * @return array
     * @throws Exception
     */
    private function getAutoresEvento(stdClass $oDadosLicitacao)
    {
        $oLicitacao = new licitacao($oDadosLicitacao->CODIGO_LICITACAO);

        $oDaoEvento = new cl_liclicitaevento;
        $sSqlEvento = $oDaoEvento->sql_query_file(null, 'l46_cgm as autor', null,
            "l46_liclicita = {$oDadosLicitacao->CODIGO_LICITACAO} and l46_cgm is not null");
        $rsBuscaEvento = db_query($sSqlEvento);
        if (!$rsBuscaEvento) {
            throw new Exception("Ocorreu um erro ao buscar o autor dos eventos da licitação {$oDadosLicitacao->CODIGO_LICITACAO}.");
        }

        $iTotalRegistros = pg_num_rows($rsBuscaEvento);
        if ($iTotalRegistros == 0) {
            return array();
        }

        $resultadoHabilitacao = null;
        if ($oLicitacao->obterNivelJulgamento() == 'G') {
            $resultadoHabilitacao = 'I';
        }

        $aRetorno = array();
        for ($iRowAutor = 0; $iRowAutor < $iTotalRegistros; $iRowAutor++) {

            $iCodigoAutor = db_utils::fieldsMemory($rsBuscaEvento, $iRowAutor)->autor;

            $oDao = new \cl_pcorcamforne();

            $result = db_query($oDao->sql_query(null, '*', null, ' pc21_numcgm = ' . $iCodigoAutor));

            if (pg_num_rows($result) == 0) {
                continue;
            }

            $oStdDadosRetorno = clone $oDadosLicitacao;
            $oStdDadosRetorno->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($iCodigoAutor);
            $oStdDadosRetorno->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($iCodigoAutor);
            $oStdDadosRetorno->NR_DOCUMENTO_REPRES = '';
            $oStdDadosRetorno->TP_DOCUMENTO_REPRES = '';
            $oStdDadosRetorno->TP_RESULTADO_HABILITACAO = $resultadoHabilitacao;
            $oStdDadosRetorno->CGM = $iCodigoAutor;
            $aRetorno[] = $oStdDadosRetorno;
        }
        return $aRetorno;
    }

    /**
     * Prepara os dados dos licitantes vencedores nas modalidades Concurso/Pregão/Leilão
     *
     * @param array $aCampos
     * @return bool
     * @throws Exception
     */
    private function prepararLicitantesVencedores(array $aCampos)
    {
        $aWhere = array(
            'pcorcamjulg.pc24_pontuacao = 1',
            "l44_sigla in ('CNS', 'PRE', 'PRP', 'LEI', 'LEE')",
            "l44_sigla not in ('PRD', 'PRI', 'RPO')"
        );

        $aWherePadrao = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $aWhere = array_merge($aWhere, $aWherePadrao);

        $oDaoLicitacao = new cl_liclicita();
        $sSqlBuscaLicitacoes = $oDaoLicitacao->sql_query_licitantes('distinct ' . implode(',', $aCampos),
            implode(' and ', $aWhere));
        $rsBuscaLicitacoes = db_query($sSqlBuscaLicitacoes . ' order by codigo');
        if (!$rsBuscaLicitacoes) {
            throw new Exception('Não foi possível buscar os licitantes ganhadores.');
        }

        $aFornecedores = array();
        for ($iRow = 0; $iRow < pg_num_rows($rsBuscaLicitacoes); $iRow++) {
            $iFornecedor = db_utils::fieldsMemory($rsBuscaLicitacoes, $iRow)->numcgm;
            if (!in_array($iFornecedor, $aFornecedores)) {
                $aFornecedores[] = $iFornecedor;
            }
        }

        for ($iRow = 0; $iRow < pg_num_rows($rsBuscaLicitacoes); $iRow++) {
            $oStdBaseDados = db_utils::fieldsMemory($rsBuscaLicitacoes, $iRow);

            $oDadosLinha = $this->criarObjetoImpressao($oStdBaseDados);
            $this->adicionarRegistro($oDadosLinha);
            $aAutoresEvento = $this->getAutoresEvento($oDadosLinha);
            foreach ($aAutoresEvento as $oStdAutorEvento) {
                /**
                 * Se não é fornecedor manda como autor de evento
                 */
                if (!in_array($oStdAutorEvento->CGM, $aFornecedores)) {
                    $this->adicionarRegistro($oStdAutorEvento);
                }
            }

            unset($oStdBaseDados, $oAutoresEvento);
        }
        return true;
    }

    /**
     * @param array $aCampos
     *
     * @return bool
     * @throws DBException
     * @throws Exception
     */
    private function prepararLicitantesGerais(array $aCampos)
    {
        $aWhere = array(
            "l44_sigla not in ('CNS', 'PRE', 'PRP', 'LEI', 'LEE')",
            "l44_sigla not in ('PRD', 'PRI', 'RPO')"
        );

        $aWhere = array_merge($aWhere, LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao()));

        $oDaoLicitacao = new cl_liclicita;
        $sSqlBuscaLicitacoes = $oDaoLicitacao->sql_query_licitantes('distinct ' . implode(',', $aCampos),
            implode(' and ', $aWhere));
        $rsBuscaLicitacoes = db_query($sSqlBuscaLicitacoes . ' order by codigo');
        if (!$rsBuscaLicitacoes) {
            throw new Exception('Não foi possível realizar a busca geral de licitantes.');
        }

        $aFornecedores = array();
        for ($iRow = 0; $iRow < pg_num_rows($rsBuscaLicitacoes); $iRow++) {
            $iFornecedor = db_utils::fieldsMemory($rsBuscaLicitacoes, $iRow)->numcgm;
            if (!in_array($iFornecedor, $aFornecedores)) {
                $aFornecedores[] = $iFornecedor;
            }
        }

        for ($iRow = 0; $iRow < pg_num_rows($rsBuscaLicitacoes); $iRow++) {
            $oStdBaseDados = db_utils::fieldsMemory($rsBuscaLicitacoes, $iRow);
            $oDadosLinha = $this->criarObjetoImpressao($oStdBaseDados);
            $this->adicionarRegistro($oDadosLinha);
            $aAutoresEvento = $this->getAutoresEvento($oDadosLinha);
            foreach ($aAutoresEvento as $oStdAutorEvento) {
                /**
                 * Se não é fornecedor manda como autor de evento
                 */
                if (!in_array($oStdAutorEvento->CGM, $aFornecedores)) {
                    $this->adicionarRegistro($oStdAutorEvento);
                }
            }

            unset($oStdBaseDados, $oAutoresEvento);
        }
        return true;
    }

    /**
     * Cria um objeto do tipo stdClass para que seja passado pro
     * @param stdClass $oStdBaseDados
     * @return stdClass
     * @throws DBException
     */
    private function criarObjetoImpressao(stdClass $oStdBaseDados)
    {
        $iCodigoRepresentante = null;
        $oCgmRepresentante = $this->getRepresentanteLegal($oStdBaseDados->numcgm);
        if ($oCgmRepresentante) {
            $iCodigoRepresentante = $oCgmRepresentante->getCodigo();
        }

        $oLicitacao = LicitacaoRepository::getByCodigo($oStdBaseDados->codigo);
        $sTipoCondicao = '';
        if ($oLicitacao->getModalidade()->getSiglaTipoCompraTribunal() == 'CNV') {
            $sTipoCondicao = self::getTipoCondicaoFornecedor($oStdBaseDados->fornecedor_licitacao);
        }

        $resultadoHabilitacao = new ResultadoHabilitacao(
            $oStdBaseDados->fornecedor_licitacao,
            $oLicitacao,
            licitacao::TIPO_JULGAMENTO_GLOBAL,
            $this->oRegra->getVersao()
        );

        $oStdLicitantes = new stdClass;
        $oStdLicitantes->CODIGO_LICITACAO = $oLicitacao->getCodigo();
        $oStdLicitantes->NR_LICITACAO = $oLicitacao->getEdital();
        $oStdLicitantes->ANO_LICITACAO = $oLicitacao->getAno();
        $oStdLicitantes->CD_TIPO_MODALIDADE = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
        $oStdLicitantes->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdBaseDados->numcgm);
        $oStdLicitantes->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oStdBaseDados->numcgm);
        $oStdLicitantes->NR_DOCUMENTO_REPRES = LicitanteLicitaCon::getDocumentoPorCGM($iCodigoRepresentante);
        $oStdLicitantes->TP_DOCUMENTO_REPRES = LicitanteLicitaCon::getTipoDocumentoPorCGM($iCodigoRepresentante);
        $oStdLicitantes->TP_CONDICAO = $sTipoCondicao;
        $oStdLicitantes->TP_RESULTADO_HABILITACAO = $resultadoHabilitacao->obterValor();
        $oStdLicitantes->BL_BENEFICIO_MICRO_EPP = $this->oRegra->getBeneficioMicroEpp($oLicitacao,
            $oStdBaseDados->tipo_empresa);
        return $oStdLicitantes;
    }

    /**
     * @param stdClass $oStdDado
     */
    private function adicionarRegistro(stdClass $oStdDado)
    {
        $sHash = implode('', array(
            $oStdDado->NR_LICITACAO,
            $oStdDado->ANO_LICITACAO,
            $oStdDado->CD_TIPO_MODALIDADE,
            $oStdDado->TP_DOCUMENTO_LICITANTE,
            $oStdDado->NR_DOCUMENTO_LICITANTE
        ));

        if (!array_key_exists($sHash, $this->aDadosLicitante)) {
            $this->aDadosLicitante[$sHash] = $oStdDado;
        }
    }

    /**
     * @param $iNumeroCGM
     * @return string
     * @throws Exception
     */
    public static function getTipoDocumentoPorCGM($iNumeroCGM)
    {
        if (empty($iNumeroCGM)) {
            return '';
        }

        $oCgm = CgmRepository::getByCodigo($iNumeroCGM);
        if ($oCgm->isFisico() && $oCgm->getNacionalidade() == CgmFisico::NACIONALIDADE_ESTRANGEIRA) {
            return 'E';
        }

        if ($oCgm->isFisico()) {
            return "F";
        }

        if ($oCgm->isJuridico()) {
            return "J";
        }
        return null;
    }

    /**
     * @param $iNumeroCGM
     *
     * @return string
     * @throws Exception
     */
    public static function getDocumentoPorCGM($iNumeroCGM)
    {
        if (empty($iNumeroCGM)) {
            return '';
        }
        $oCgm = CgmRepository::getByCodigo($iNumeroCGM);
        $sCgmRetorno = $oCgm->isFisico() ? $oCgm->getCpf() : $oCgm->getCnpj();
        if ($oCgm->isFisico() && $oCgm->getNacionalidade() == CgmFisico::NACIONALIDADE_ESTRANGEIRA) {
            $sCgmRetorno = $oCgm->getDocumentoEstrangeiro();
        }
        return $sCgmRetorno;
    }


    /**
     * @param $iCodigoFornecedor
     * @return string
     * @throws DBException
     */
    private static function getTipoCondicaoFornecedor($iCodigoFornecedor)
    {
        $oDaoTipoCondicao = new cl_pcorcamfornelic;
        $sSqlBuscaCondicao = $oDaoTipoCondicao->sql_query_file($iCodigoFornecedor, 'pc31_tipocondicao');
        $rsBuscaCondicao = db_query($sSqlBuscaCondicao);
        if (!$rsBuscaCondicao) {
            throw new DBException('Ocorreu um erro ao buscar o Tipo de Condição do fornecedor.');
        }
        $aValores = array(1 => 'CEP', 2 => 'CNP', 3 => 'NCP');
        if (pg_num_rows($rsBuscaCondicao)) {
            $iCodigoSituacao = db_utils::fieldsMemory($rsBuscaCondicao, 0)->pc31_tipocondicao;
            return $iCodigoSituacao ? $aValores[$iCodigoSituacao] : '';
        }
        return '';
    }

    /**
     * Verifica se o fornecedor venceu a disputa de algum item.
     * @param $iCodigoFornecedor
     * @return bool
     * @throws Exception
     */
    public static function fornecedorGanhouItens($iCodigoFornecedor)
    {
        if (empty($iCodigoFornecedor)) {
            return false;
        }

        $sWhere = " pc24_pontuacao = 1 and pc24_orcamforne = {$iCodigoFornecedor} ";

        $oDaoJulgamento = new cl_pcorcamjulg;
        $sSqlBuscaJulgamento = $oDaoJulgamento->sql_query_file(null, null, '*', null, $sWhere);
        $rsBuscaJulgamento = db_query($sSqlBuscaJulgamento);
        if (!$rsBuscaJulgamento) {
            throw new Exception('Não foi possível buscar a pontuação do fornecedor no julgamento.');
        }
        return pg_num_rows($rsBuscaJulgamento) > 0;
    }

    /**
     * Retorna o CGM que representa legalmente a empresa fornecedora
     *
     * @param $iNumCgm
     * @return CgmFisico|CgmJuridico|null
     * @throws DBException
     */
    private function getRepresentanteLegal($iNumCgm)
    {
        $oFornecedor = new fornecedor($iNumCgm);
        return $oFornecedor->getRepresentanteLegal($this->oCabecalho->getDataGeracao());
    }
}