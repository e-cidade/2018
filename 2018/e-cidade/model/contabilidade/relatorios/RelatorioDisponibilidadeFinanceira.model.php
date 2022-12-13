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

class RelatorioDisponibilidadeFinanceira
{

    /**
     * Conta correte padrão para o relatório.
     */
    const CONTA_CORRENTE = 1;

    /**
     * Tipos de agrupamento
     */
    const AGRUPAMENTO_CARACTERISTICA_PECULIAR = 1;
    const AGRUPAMENTO_RECURSO = 2;

    /**
     * Mostrar lançamentos.
     */
    const MOSTRAR_LANCAMENTOS_SIM = 1;
    const MOSTRAR_LANCAMENTOS_NAO = 2;

    /**
     * @var PDFDocument
     */
    private $oPdf;

    /**
     * @var Instituicao
     */
    private $oInstituicao;

    /**
     * @var DBDate
     */
    private $oDataInicial;

    /**
     * @var DBDate
     */
    private $oDataFinal;

    /**
     * @var array
     */
    private $aRecursos;

    /**
     * @var integer
     */
    private $iReduzido;

    /**
     * @var integer
     */
    private $iAgrupamento;

    /**
     * @var integer
     */
    private $iMostrarLancamentos;

    /**
     * @var integer Altura padrão da linha do relatório.
     */
    private $iAlturaLinha;

    /**
     * @var integer Largura padrão da linha do relatório.
     */
    private $iLarguraPagina;

    /**
     * Códigos das conta correntes para busca de valores.
     * @var integer[]
     */
    private $aContasCorrenteBuscar = array();

    /**
     * Códigos das instituições para busca de valores.
     * @var integer[]
     */
    private $aInstituicoesBuscar = array();

    /**
     * Códigos dos reduzidos para busca de valores.
     * @var integer[]
     */
    private $aReduzidosBuscar = array();

    /**
     * Códigos dos recursos para busca de valores.
     * @var integer[]
     */
    private $aRecursosBuscar = array();

    /**
     * Códigos das características peculiares para busca de valores.
     * @var string[]
     */
    private $aCaracteristicaPeculiarBuscar = array();

    /**
     * RelatorioDisponibilidadeFinanceira constructor.
     * @param Instituicao $oInstituicao
     * @param DBDate $oDataInicial
     * @param DBDate $oDataFinal
     * @param integer $iAGrupamento
     */
    public function __construct(Instituicao $oInstituicao, DBDate $oDataInicial, DBDate $oDataFinal, $iAGrupamento)
    {

        $this->oInstituicao = $oInstituicao;
        $this->oDataInicial = $oDataInicial;
        $this->oDataFinal = $oDataFinal;
        $this->iAgrupamento = $iAGrupamento;
        $this->oPdf = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
        $this->iMostrarLancamentos = self::MOSTRAR_LANCAMENTOS_SIM;
    }

    /**
     * @param integer $iReduzido
     */
    public function setReduzido($iReduzido)
    {
        $this->iReduzido = $iReduzido;
    }

    /**
     * @param array $aRecursos
     */
    public function setRecursos($aRecursos)
    {
        $this->aRecursos = $aRecursos;
    }

    /**
     * @param integer $iMostrarLancamentos
     */
    public function setMostrarLancamentos($iMostrarLancamentos)
    {
        $this->iMostrarLancamentos = $iMostrarLancamentos;
    }

    /**
     * Busca as infomrçaões dos detalhes da conta corrente.
     * @param $iContaCorrente
     * @return stdClass[]
     * @throws DBException
     */
    private function getContaCorrenteDetalhe($iContaCorrente)
    {

        $iInstituicao = $this->oInstituicao->getCodigo();

        $sCampos = " distinct c19_contacorrente, c19_instit, c19_reduz, o15_descr, nomeinst, ";
        $sCampos .= " c60_estrut, c60_descr, c19_orctiporec ";

        $sWhere = " c19_instit = {$iInstituicao} ";
        $sWhere .= " and c19_contacorrente = {$iContaCorrente} ";
        $sWhere .= " and c19_conplanoreduzanousu = " . $this->oDataInicial->getAno();

        $sOrder = " c19_reduz, c19_orctiporec";

        if (!empty($this->iReduzido)) {
            $sWhere .= " and c19_reduz in ({$this->iReduzido}) ";
        }

        if (!empty($this->aRecursos)) {
            $sWhere .= " and c19_orctiporec in (" . implode(",", $this->aRecursos) . ") ";
        }

        if ($this->iAgrupamento == self::AGRUPAMENTO_CARACTERISTICA_PECULIAR) {

            $sCampos .= ", c19_concarpeculiar, c58_descr";
            $sOrder .= ", c19_concarpeculiar";
        }

        $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
        $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_fileAtributos(null, $sCampos, $sOrder,
            $sWhere);
        $rsContaCorrenteDetalhe = db_query($sSqlContaCorrenteDetalhe);

        if (!$rsContaCorrenteDetalhe) {
            throw new DBException("Houve uma falha ao buscar as informações detalhadas para a conta corrente informada.");
        }

        $aContaCorrenteDetalhe = array();
        $iTotalContaCorrenteDetalhe = pg_num_rows($rsContaCorrenteDetalhe);
        for ($iIndice = 0; $iIndice < $iTotalContaCorrenteDetalhe; $iIndice++) {

            $oContaCorrenteDetalhe = db_utils::fieldsMemory($rsContaCorrenteDetalhe, $iIndice);
            $aContaCorrenteDetalhe[] = $oContaCorrenteDetalhe;
            $this->adicionarContaCorrenteDetalhe($oContaCorrenteDetalhe);
        }
        
        return $aContaCorrenteDetalhe;
    }

    /**
     * Adicionar as ?nformações necessárias as coleções para futura busca dos totalizadores dos lançamentos
     * @param stdClass $oContaCorrenteDetalhe
     */
    private function adicionarContaCorrenteDetalhe(stdClass $oContaCorrenteDetalhe)
    {

        $this->adicionarContaCorrente($oContaCorrenteDetalhe->c19_contacorrente);
        $this->adicionarInstituicao($oContaCorrenteDetalhe->c19_instit);
        $this->adicionarReduzido($oContaCorrenteDetalhe->c19_reduz);
        $this->adicionarRecurso($oContaCorrenteDetalhe->c19_orctiporec);

        if (!empty($oContaCorrenteDetalhe->c19_concarpeculiar)) {
            $this->adicionarCaracteristicaPeculiar($oContaCorrenteDetalhe->c19_concarpeculiar);
        }
    }

    /**
     * Adiciona a conta corrente a coleção para futura busca dos totalizadores dos lançamentos
     * @param integer $iContaCorrente
     */
    private function adicionarContaCorrente($iContaCorrente)
    {

        if (!in_array($iContaCorrente, $this->aContasCorrenteBuscar)) {
            $this->aContasCorrenteBuscar[] = $iContaCorrente;
        }
    }

    /**
     * Adiciona a instituição a coleção para futura busca dos totalizadores dos lançamentos
     * @param integer $iInstituicao
     */
    private function adicionarInstituicao($iInstituicao)
    {

        if (!in_array($iInstituicao, $this->aInstituicoesBuscar)) {
            $this->aInstituicoesBuscar[] = $iInstituicao;
        }
    }

    /**
     * Adiciona o reduzido a coleção para futura busca dos totalizadores dos lançamentos
     * @param integer $iReduzido
     */
    private function adicionarReduzido($iReduzido)
    {

        if (!in_array($iReduzido, $this->aReduzidosBuscar)) {
            $this->aReduzidosBuscar[] = $iReduzido;
        }
    }

    /**
     * Adiciona o recurso a coleção para futura busca dos totalizadores dos lançamentos.
     * @param integer $iRecurso
     */
    private function adicionarRecurso($iRecurso)
    {

        if (!in_array($iRecurso, $this->aRecursosBuscar)) {
            $this->aRecursosBuscar[] = $iRecurso;
        }
    }

    /**
     * Adiciona a característica peculiar a coleção para busca dos totalizadores dos lançamentos.
     * @param string $sCaracteristicaPeculiar
     */
    private function adicionarCaracteristicaPeculiar($sCaracteristicaPeculiar)
    {

        if (!in_array(trim($sCaracteristicaPeculiar), $this->aCaracteristicaPeculiarBuscar)) {
            $this->aCaracteristicaPeculiarBuscar[] = trim($sCaracteristicaPeculiar);
        }
    }

    /**
     * Busca lançamentos do detalhamento da conta corrente conforme parâmetros informados.
     * @param integer $iContaCorrente
     * @param integer $iInstituicao
     * @param integer $iReduzido
     * @param integer $iRecurso
     * @param integer $iCaracteristicaPeculiar
     * @return stdClass[]
     * @throws DBException
     */
    private function getLancamentosContaCorrenteDetalhe(
        $iContaCorrente,
        $iInstituicao,
        $iReduzido,
        $iRecurso,
        $iCaracteristicaPeculiar = null
    ) {

        $sDataInicial = $this->oDataInicial->getDate(DBDate::DATA_EN);
        $sDataFinal = $this->oDataFinal->getDate(DBDate::DATA_EN);

        $sCampos = " distinct c71_data, ";
        $sCampos .= "c69_codlan, ";
        $sCampos .= "c53_coddoc, ";
        $sCampos .= "c53_descr,  ";
        $sCampos .= "c28_tipo,   ";
        $sCampos .= "c69_valor  ";

        $sWhere = "c69_data between '{$sDataInicial}' and '{$sDataFinal}' ";
        $sWhere .= " and c19_contacorrente = {$iContaCorrente} ";
        $sWhere .= " and c19_reduz = {$iReduzido}  ";
        $sWhere .= " and c19_instit = {$iInstituicao} ";
        $sWhere .= " and c19_orctiporec = {$iRecurso} ";
        $sWhere .= " and (c69_data between '{$sDataInicial}' and '{$sDataFinal}') ";

        $sOrder = "  c69_codlan, c53_coddoc ";

        if (!empty($iCaracteristicaPeculiar)) {
            $sWhere .= " and c19_concarpeculiar = '$iCaracteristicaPeculiar' ";
        }

        $oDaoContaCorrenteDetalheLancamento = new cl_contacorrentedetalheconlancamval();
        $sSqlLancamentos = $oDaoContaCorrenteDetalheLancamento->sql_query_lancamentos($sCampos, $sWhere, $sOrder);
        $rsLancamentos = db_query($sSqlLancamentos);

        if (!$rsLancamentos) {
            throw new DBException("Houve um erro ao buscar os lançamentos da conta corrente detalhada.");
        }

        $aLancamentos = array();
        $iTotalLancamentos = pg_num_rows($rsLancamentos);
        for ($iIndice = 0; $iIndice < $iTotalLancamentos; $iIndice++) {
            $aLancamentos[] = db_utils::fieldsMemory($rsLancamentos, $iIndice);
        }
        return $aLancamentos;
    }

    /**
     * @param array $aReduzidos
     * @param array $aRecursos
     * @param integer $iCaracteristicaPeculiar
     * @return stdClass
     * @throws DBException
     */
    private function buscaDadosImplantacao($aReduzidos, $aRecursos, $iCaracteristicaPeculiar = null)
    {

        $sCampos = " sum(c29_debito) as debito, sum(c29_credito) as credito";
        $sWhere = " c19_reduz  in (" . implode(",", $aReduzidos) . ") ";

        if (!empty($aRecursos)) {
            $sWhere .= " and c19_orctiporec in (" . implode(",", $aRecursos) . ") ";
        }

        if (!empty($iCaracteristicaPeculiar)) {
            $sWhere .= " and c19_concarpeculiar = '{$iCaracteristicaPeculiar}' ";
        }

        $oDaoContaCorreteSaldo = new cl_contacorrentesaldo();
        $sSql = $oDaoContaCorreteSaldo->sql_query_busca_saldo_implantacao($sCampos, $sWhere,
            $this->oDataInicial->getAno());
        $rsResultado = db_query($sSql);

        if (!$rsResultado) {
            throw new DBException("Houve uma falha ao buscar os dados de implantação.");
        }

        $oStdDetalhe = new stdClass();
        $oStdDetalhe->credito = 0;
        $oStdDetalhe->debito = 0;

        if (pg_num_rows($rsResultado) > 0) {

            $oDetalhe = db_utils::fieldsMemory($rsResultado, 0);
            $oStdDetalhe->credito += $oDetalhe->credito;
            $oStdDetalhe->debito += $oDetalhe->debito;
        }

        return $oStdDetalhe;
    }

    /**
     * Busca saldo inicial para a conta corrente de acordo com os parâmetros.
     * @param integer $iContaCorrente
     * @param array $aReduzidos
     * @param array $aRecursos
     * @param integer $iCaracteristicaPeculiar
     * @return stdClass
     * @throws DBException
     */
    private function getSaldosIniciais($iContaCorrente, $aReduzidos, $aRecursos, $iCaracteristicaPeculiar = null)
    {

        $sDataInicial = $this->oDataInicial->getDate(DBDate::DATA_EN);

        $sCampos = " sum(case when c28_tipo = 'D' ";
        $sCampos .= " then coalesce(c69_valor,0) ";
        $sCampos .= " else 0 ";
        $sCampos .= " end ) as debito, ";
        $sCampos .= " sum(case when c28_tipo = 'C' ";
        $sCampos .= " then coalesce(c69_valor,0) ";
        $sCampos .= " else 0 ";
        $sCampos .= " end ) as credito ";

        $sWhere = "     c69_data < '{$sDataInicial}' and extract(year from c69_data) = " . $this->oDataInicial->getAno();
        $sWhere .= " and c19_contacorrente = {$iContaCorrente} ";

        if (!empty($aReduzidos)) {
            $sWhere .= " and c19_reduz  in (" . implode(",", $aReduzidos) . ") ";
        }
        if (!empty($aRecursos)) {
            $sWhere .= " and c19_orctiporec in (" . implode(",", $aRecursos) . ") ";
        }

        if (!empty($iCaracteristicaPeculiar)) {
            $sWhere .= " and c19_concarpeculiar = '{$iCaracteristicaPeculiar}' ";
        }
        $sWhere .= " group by c19_reduz, c19_numcgm, c19_orcunidadeorgao, c19_orcunidadeunidade ";

        $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
        $sSql = $oDaoContaCorrenteDetalhe->sql_query_lancamentos(null, $sCampos, null, $sWhere);
        $rsSaldos = db_query($sSql);
        if (!$rsSaldos) {
            throw new DBException("Houve um erro ao buscar do saldo inicial da conta corrente informada.");
        }

        $nValorDebito = 0;
        $nValorCredito = 0;
        if (pg_num_rows($rsSaldos) == 1) {
            $nValorDebito = db_utils::fieldsMemory($rsSaldos, 0)->debito;
            $nValorCredito = db_utils::fieldsMemory($rsSaldos, 0)->credito;
        }

        $oSadosIniciais = new stdClass();
        $oSadosIniciais->nTotalDebito = $nValorDebito;
        $oSadosIniciais->nTotalCredito = $nValorCredito;

        $oDadosImplantacao = $this->buscaDadosImplantacao($aReduzidos, $aRecursos, $iCaracteristicaPeculiar);
        $oSadosIniciais->nTotalDebito += $oDadosImplantacao->debito;
        $oSadosIniciais->nTotalCredito += $oDadosImplantacao->credito;

        return $oSadosIniciais;
    }

    /**
     * Busca os valores totalizados e agrupados para os lançamentos da conta corrente.
     * @return array
     * @throws DBException
     * @throws ParameterException
     */
    private function getTotalLancamentos()
    {

        if (empty($this->aContasCorrenteBuscar) || !is_array($this->aContasCorrenteBuscar)) {
            return;
        }

        if (empty($this->aInstituicoesBuscar) || !is_array($this->aInstituicoesBuscar)) {
            throw new ParameterException("Atributo iInstituicao informado inválido.");
        }

        if (empty($this->aReduzidosBuscar) || !is_array($this->aReduzidosBuscar)) {
            throw new ParameterException("Atributo aReduzidos informado inválido.");
        }

        if (empty($this->aRecursosBuscar) || !is_array($this->aRecursosBuscar)) {
            throw new ParameterException("Atributo aRecursos informado inválido.");
        }

        if (!empty($this->aCaracteristicaPeculiarBuscar) && !is_array($this->aCaracteristicaPeculiarBuscar)) {
            throw new ParameterException("Atributo aCaracPeculiar informado inválido.");
        }

        if ($this->iAgrupamento == self::AGRUPAMENTO_RECURSO) {
            $this->aCaracteristicaPeculiarBuscar = array();
        }

        $sDataInicial = $this->oDataInicial->getDate(DBDate::DATA_EN);
        $sDataFinal = $this->oDataFinal->getDate(DBDate::DATA_EN);
        $sContasCorrente = implode(",", $this->aContasCorrenteBuscar);
        $sInstituicoes = implode(",", $this->aInstituicoesBuscar);
        $sReduzidos = implode(",", $this->aReduzidosBuscar);
        $sRecursos = implode(",", $this->aRecursosBuscar);
        $sCaracPeculiar = "";
        if (!empty($this->aCaracteristicaPeculiarBuscar)) {
            $sCaracPeculiar = "'" . implode("','", $this->aCaracteristicaPeculiarBuscar) . "'";
        }

        $sCampos = "sum(case when c28_tipo = 'C' then c69_valor else 0 end) as total_credito, ";
        $sCampos .= "sum(case when c28_tipo = 'D' then c69_valor else 0 end) as total_debito, ";
        $sCampos .= "c19_contacorrente, c19_reduz, c19_instit, c19_orctiporec ";
        if (!empty($sCaracPeculiar)) {
            $sCampos .= ", c19_concarpeculiar";
        }

        $sGroupBy = "group by c19_contacorrente, c19_reduz, c19_instit, c19_orctiporec";
        if (!empty($sCaracPeculiar)) {
            $sGroupBy .= ", c19_concarpeculiar";
        }

        $sWhere = "c69_data between '{$sDataInicial}' and '{$sDataFinal}'";
        $sWhere .= " and c19_contacorrente in ({$sContasCorrente}) ";
        $sWhere .= " and c19_reduz in ({$sReduzidos}) ";
        $sWhere .= " and c19_instit in ({$sInstituicoes}) ";
        $sWhere .= " and c19_orctiporec in ({$sRecursos}) ";
        if (!empty($sCaracPeculiar)) {
            $sWhere .= " and c19_concarpeculiar in ({$sCaracPeculiar})";
        }

        $sOrderBy = "c19_contacorrente, c19_reduz, c19_instit, c19_orctiporec";
        if (!empty($sCaracPeculiar)) {
            $sOrderBy .= ", c19_concarpeculiar";
        }

        $oDaoContaCorrenteDetalheLancamento = new cl_contacorrentedetalheconlancamval();
        $sSqlLancamentos = $oDaoContaCorrenteDetalheLancamento->sql_query_lancamentos($sCampos, $sWhere . $sGroupBy,
            $sOrderBy);
        $rsLancamentos = db_query($sSqlLancamentos);
        if (!$rsLancamentos) {
            throw new DBException("Houve um erro ao totalizar os valores dos lançamentos.");
        }

        $iTotalLancamentos = pg_num_rows($rsLancamentos);
        $aTotalLancamentos = array();
        for ($iIndice = 0; $iIndice < $iTotalLancamentos; $iIndice++) {

            $oTotalLancamentos = db_utils::fieldsMemory($rsLancamentos, $iIndice);
            if (!empty($sCaracPeculiar)) {
                $aTotalLancamentos[$oTotalLancamentos->c19_contacorrente][$oTotalLancamentos->c19_reduz][$oTotalLancamentos->c19_instit][$oTotalLancamentos->c19_orctiporec][trim($oTotalLancamentos->c19_concarpeculiar)] = $oTotalLancamentos;
            } else {
                $aTotalLancamentos[$oTotalLancamentos->c19_contacorrente][$oTotalLancamentos->c19_reduz][$oTotalLancamentos->c19_instit][$oTotalLancamentos->c19_orctiporec] = $oTotalLancamentos;
            }
        }
        return $aTotalLancamentos;
    }

    /**
     * Prepara os dados para emissão do relatório.
     * @return stdClass
     * @throws DBException
     * @throws ParameterException
     */
    private function getDados()
    {

        $nSaldoInicialContaDebito = 0;
        $nSaldoInicialContaCredito = 0;
        $nSaldoFinalContaDebito = 0;
        $nSaldoFinalContaCredito = 0;

        $oContaCorrente = new stdClass();
        $oContaCorrente->nTotalMovimentacaoDebito = 0;
        $oContaCorrente->nTotalMovimentacaoCredito = 0;
        $oContaCorrente->sSaldoInicialContaDebito = "";
        $oContaCorrente->sSaldoInicialContaCredito = "";
        $oContaCorrente->sSaldoFinalContaDebito = "";
        $oContaCorrente->sSaldoFinalContaCredito = "";

        $oContaCorrente->aContaCorrenteDetalhe = $this->getContaCorrenteDetalhe(self::CONTA_CORRENTE);
        $aTotalLancamentosContaCorrenteDetalhe = $this->getTotalLancamentos();
        foreach ($oContaCorrente->aContaCorrenteDetalhe as $oContaCorrenteDetalhe) {

            $oContaCorrenteDetalhe->sSaldoInicialRecursoDebito = "";
            $oContaCorrenteDetalhe->sSaldoInicialRecursoCredito = "";
            $oContaCorrenteDetalhe->sSaldoFinalRecursoDebito = "";
            $oContaCorrenteDetalhe->sSaldoFinalRecursoCredito = "";

            $iCaracteristicaPeculiar = isset($oContaCorrenteDetalhe->c19_concarpeculiar) ? $oContaCorrenteDetalhe->c19_concarpeculiar : null;
            $oSaldosIniciais = $this->getSaldosIniciais($oContaCorrenteDetalhe->c19_contacorrente,
                array($oContaCorrenteDetalhe->c19_reduz), array($oContaCorrenteDetalhe->c19_orctiporec),
                $iCaracteristicaPeculiar);

            $oContaCorrenteDetalhe->nTotalInicialRecursoDebito = $oSaldosIniciais->nTotalDebito;
            $oContaCorrenteDetalhe->nTotalInicialRecursoCredito = $oSaldosIniciais->nTotalCredito;

            $nSaldoInicial = $oSaldosIniciais->nTotalDebito - $oSaldosIniciais->nTotalCredito;
            $sSaldoInicial = db_formatar(abs($nSaldoInicial), 'f');

            $nSaldoInicialContaDebito += $oSaldosIniciais->nTotalDebito;
            $nSaldoInicialContaCredito += $oSaldosIniciais->nTotalCredito;

            if ($nSaldoInicial > 0) {
                $oContaCorrenteDetalhe->sSaldoInicialRecursoDebito = $sSaldoInicial;
            } else {
                $oContaCorrenteDetalhe->sSaldoInicialRecursoCredito = $sSaldoInicial;
            }


            $oContaCorrenteDetalhe->aLancamentos = array();
            if ($this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_SIM) {

                $oContaCorrenteDetalhe->aLancamentos = $this->getLancamentosContaCorrenteDetalhe($oContaCorrenteDetalhe->c19_contacorrente,
                    $oContaCorrenteDetalhe->c19_instit, $oContaCorrenteDetalhe->c19_reduz,
                    $oContaCorrenteDetalhe->c19_orctiporec, $iCaracteristicaPeculiar);
            }

            $oTotalLancamentos = $this->getTotalLancamentosContaCorrenteDetalhe($aTotalLancamentosContaCorrenteDetalhe,
                $oContaCorrenteDetalhe);
            $oContaCorrenteDetalhe->nTotalLancamentosDebito = $oTotalLancamentos->total_debito;
            $oContaCorrenteDetalhe->nTotalLancamentosCredito = $oTotalLancamentos->total_credito;

            $nSaldoFinalContaDebito += $oTotalLancamentos->total_debito;
            $nSaldoFinalContaCredito += $oTotalLancamentos->total_credito;

            $oContaCorrenteDetalhe->nSaldoFinalRecursoDebito = $oContaCorrenteDetalhe->nTotalInicialRecursoDebito + $oTotalLancamentos->total_debito;
            $oContaCorrenteDetalhe->nSaldoFinalRecursoCredito = $oContaCorrenteDetalhe->nTotalInicialRecursoCredito + $oTotalLancamentos->total_credito;

            $nSaldoFinalRecurso = $oContaCorrenteDetalhe->nSaldoFinalRecursoDebito - $oContaCorrenteDetalhe->nSaldoFinalRecursoCredito;
            $sSaldoFinalRecurso = db_formatar(abs($nSaldoFinalRecurso), 'f');
            if ($nSaldoFinalRecurso > 0) {
                $oContaCorrenteDetalhe->sSaldoFinalRecursoDebito = $sSaldoFinalRecurso;
            } else {
                $oContaCorrenteDetalhe->sSaldoFinalRecursoCredito = $sSaldoFinalRecurso;
            }

            foreach ($oContaCorrenteDetalhe->aLancamentos as $oLancamento) {

                $oLancamento->nValorCredito = 0;
                $oLancamento->nValorDebito = 0;
                switch ($oLancamento->c28_tipo) {

                    case 'C' :

                        $oLancamento->nValorCredito = $oLancamento->c69_valor;
                        break;

                    case 'D' :

                        $oLancamento->nValorDebito = $oLancamento->c69_valor;
                        break;
                }
            }

        }
        $nSaldoInicialConta = ($nSaldoInicialContaDebito - $nSaldoInicialContaCredito);
        $sSaldoInicialConta = db_formatar(abs($nSaldoInicialConta), 'f');
        if ($nSaldoInicialConta > 0) {
            $oContaCorrente->sSaldoInicialContaDebito = $sSaldoInicialConta;
        } else {
            $oContaCorrente->sSaldoInicialContaCredito = $sSaldoInicialConta;
        }

        $nSaldoFinalConta = ($nSaldoFinalContaDebito - $nSaldoFinalContaCredito) + $nSaldoInicialConta;
        $oContaCorrente->nTotalMovimentacaoDebito = $nSaldoFinalContaDebito;
        $oContaCorrente->nTotalMovimentacaoCredito = $nSaldoFinalContaCredito;
        $sSaldoFinalConta = db_formatar(abs($nSaldoFinalConta), 'f');
        if ($nSaldoInicialConta > 0) {
            $oContaCorrente->sSaldoFinalContaDebito = $sSaldoFinalConta;
        } else {
            $oContaCorrente->sSaldoFinalContaCredito = $sSaldoFinalConta;
        }

        return $oContaCorrente;
    }

    /**
     * Emite o relatório PDF.
     */
    public function emitir()
    {

        $this->configurarPdf();
        $oContaCorrente = $this->getDados();

        $this->escreverSaldoConta($oContaCorrente);
        foreach ($oContaCorrente->aContaCorrenteDetalhe as $oDado) {

            $this->escreverCabecalhoRecurso($oDado);
            $this->escreverCabecalhoLancamentos();

            if ($this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_NAO) {
                $this->escreverSaldoInicialRecurso($oDado);
            }
            foreach ($oDado->aLancamentos as $oLancamento) {

                if ($this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_NAO) {
                    break;
                }
                $this->escreverLinhaLancamento($oLancamento);
            }
            $this->escreverTotalizadoresRecurso($oDado);
        }
        $this->escreverTotalizadoresConta($oContaCorrente);
        $this->oPdf->showPDF("RelatorioDisponibilidadeFinanceira_" . time());
    }

    /**
     * Realiza as configurações do PDFDocument necessários para emissão do relatório.
     */
    private function configurarPdf()
    {

        $this->iAlturaLinha = 4;
        $iPreenchimento = 235;
        $sFonte = 'arial';
        $iTamanhoFonte = 6;

        $sDataInicialBr = $this->oDataInicial->getDate(DBDate::DATA_PTBR);
        $sDataFinalBr = $this->oDataFinal->getDate(DBDate::DATA_PTBR);

        $iContaCorrente = self::CONTA_CORRENTE;
        $oContaCorrente = new ContaCorrente($iContaCorrente);
        $sContaCorrente = $oContaCorrente->getContaCorrente();
        $sDescricaoConta = $oContaCorrente->getDescricao();

        $this->oPdf->Open();
        $this->oPdf->addHeaderDescription("DISPONIBILIDADE FINANCEIRA");
        $this->oPdf->addHeaderDescription("{$iContaCorrente} - {$sContaCorrente} - {$sDescricaoConta}");
        $this->oPdf->addHeaderDescription("");
        $this->oPdf->addHeaderDescription("");
        $this->oPdf->addHeaderDescription("");
        $this->oPdf->addHeaderDescription("PERÍODO: {$sDataInicialBr} À {$sDataFinalBr}");
        $this->oPdf->setAutoNewLineMulticell(true);
        $this->oPdf->SetFillColor($iPreenchimento);
        $this->oPdf->setFontFamily($sFonte);
        $this->oPdf->SetFontSize($iTamanhoFonte);
        $this->oPdf->addPage();

        $this->iLarguraPagina = $this->oPdf->getAvailWidth();
    }

    /**
     * Escreve o cabeçalho para os lançamentos.
     */
    private function escreverCabecalhoLancamentos()
    {

        $this->oPdf->setBold(true);
        if ($this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_SIM) {

            $this->oPdf->Cell($this->iLarguraPagina * 0.1, $this->iAlturaLinha, "DATA", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, "CÓD. LANÇAMENTO", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.35, $this->iAlturaLinha, "DOCUMENTO", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "DÉBITO", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "CRÉDITO", 'TB', 1, 'C', 1);
        } else {

            $this->oPdf->Cell($this->iLarguraPagina * 0.4, $this->iAlturaLinha, "", '', 0, 'C', 0);
            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "DÉBITO", 'TB', 0, 'C', 1);
            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "CRÉDITO", 'TB', 1, 'C', 1);
        }
        $this->oPdf->setBold(false);
    }

    /**
     * Escreve o saldo da conta.
     * @param stdClass $oContaCorrente
     */
    private function escreverSaldoConta(stdClass $oContaCorrente)
    {

        $this->oPdf->Cell($this->iLarguraPagina * 0.4, $this->iAlturaLinha);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "SALDO INICIAL DA CONTA:", 'TB', 0, 'R', 1);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oContaCorrente->sSaldoInicialContaDebito,
            'TB', 0, 'R', 1);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oContaCorrente->sSaldoInicialContaCredito,
            'TB', 1, 'R', 1);
    }

    /**
     * Escreve o cabeçalho para o recurso.
     * @param stdClass $oDado
     */
    private function escreverCabecalhoRecurso(stdClass $oDado)
    {

        if ($this->iAgrupamento == self::AGRUPAMENTO_CARACTERISTICA_PECULIAR) {

            $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "Característica Peculiar:", 0, 0, 'L');
            $this->oPdf->Cell($this->iLarguraPagina * 0.8, $this->iAlturaLinha, $oDado->c58_descr, 0, 1, 'L');
        }
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "Recurso Vinculado:", 0, 0, 'L');
        $this->oPdf->Cell($this->iLarguraPagina * 0.8, $this->iAlturaLinha,
            "{$oDado->c19_orctiporec} - {$oDado->o15_descr}", 0, 1, 'L');

        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "Instituição:", 0, 0, 'L');
        $this->oPdf->Cell($this->iLarguraPagina * 0.8, $this->iAlturaLinha, $oDado->nomeinst, 0, 1, 'L');

        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "Conta:", 0, 0, 'L');
        $this->oPdf->Cell($this->iLarguraPagina * 0.8, $this->iAlturaLinha,
            "{$oDado->c19_reduz} - " . db_formatar($oDado->c60_estrut, 'receita') . " -  {$oDado->c60_descr} ", 0, 1,
            'L');

        $this->oPdf->Ln($this->iAlturaLinha * 2);
        if ($this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_SIM) {
            $this->escreverSaldoInicialRecurso($oDado);
        }
    }

    /**
     * Escreve a linha referente ao saldo inicial do recurso.
     * @param stdClass $oDado
     */
    private function escreverSaldoInicialRecurso(stdClass $oDado)
    {

        $iPreenchimento = $this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_NAO;
        $sBorda = $this->iMostrarLancamentos == self::MOSTRAR_LANCAMENTOS_NAO ? "TB" : 0;
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.4, $this->iAlturaLinha);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "SALDO INICIAL DO RECURSO:", $sBorda, 0,
            "R", $iPreenchimento);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oDado->sSaldoInicialRecursoDebito, $sBorda,
            0, "R", $iPreenchimento);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oDado->sSaldoInicialRecursoCredito,
            $sBorda, 1, "R", $iPreenchimento);
    }

    /**
     * Escreve as linhas referentes aos totalizadores dos recursos.
     * @param stdClass $oDado
     */
    private function escreverTotalizadoresRecurso(stdClass $oDado)
    {

        $this->oPdf->Cell($this->iLarguraPagina * 0.4, $this->iAlturaLinha);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "TOTAIS DA MOVIMENTAÇÃO:", 'TB', 0, 'R', 1);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oDado->nTotalLancamentosDebito, 'f'), 'TB', 0, 'R', 1);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oDado->nTotalLancamentosCredito, 'f'), 'TB', 1, 'R', 1);

        $this->oPdf->Cell($this->iLarguraPagina * 0.4, $this->iAlturaLinha);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, "SALDO FINAL DO RECURSO:", 'TB', 0, 'R', 1);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oDado->sSaldoFinalRecursoDebito, 'TB', 0,
            'R', 1);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oDado->sSaldoFinalRecursoCredito, 'TB', 1,
            'R', 1);

        $this->oPdf->Ln($this->iAlturaLinha * 2);
    }

    /**
     * Escreve a linha com os valores do lançamento.
     * @param stdClass $oLancamento
     */
    private function escreverLinhaLancamento(stdClass $oLancamento)
    {

        $this->oPdf->Cell($this->iLarguraPagina * 0.1, $this->iAlturaLinha, db_formatar($oLancamento->c71_data, 'd'), 0,
            0, 'C');
        $this->oPdf->Cell($this->iLarguraPagina * 0.15, $this->iAlturaLinha, $oLancamento->c69_codlan, 0, 0, 'R');
        $this->oPdf->Cell($this->iLarguraPagina * 0.35, $this->iAlturaLinha,
            $oLancamento->c53_coddoc . " " . $oLancamento->c53_descr, 0, 0, 'L');
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oLancamento->nValorDebito, 'f'), 0, 0, 'R');
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oLancamento->nValorCredito, 'f'), 0, 1, 'R');
    }

    /**
     * Escreve as linhas com totalizadores referentes a conta.
     * @param stdClass $oContaCorrente
     */
    private function escreverTotalizadoresConta(stdClass $oContaCorrente)
    {

        $this->oPdf->Cell($this->iLarguraPagina * 0.35, $this->iAlturaLinha);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.25, $this->iAlturaLinha, "TOTAIS DA MOVIMENTAÇÃO DA CONTA:", 'TB',
            0, 'R', 1);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oContaCorrente->nTotalMovimentacaoDebito, 'f'), 'TB', 0, 'R', 1);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha,
            db_formatar($oContaCorrente->nTotalMovimentacaoCredito, 'f'), 'TB', 1, 'R', 1);

        $this->oPdf->Cell($this->iLarguraPagina * 0.35, $this->iAlturaLinha);
        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLarguraPagina * 0.25, $this->iAlturaLinha, "SALDO FINAL DA CONTA:", 'TB', 0, 'R', 1);
        $this->oPdf->setBold(false);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oContaCorrente->sSaldoFinalContaDebito,
            'TB', 0, 'R', 1);
        $this->oPdf->Cell($this->iLarguraPagina * 0.2, $this->iAlturaLinha, $oContaCorrente->sSaldoFinalContaCredito,
            'TB', 1, 'R', 1);
    }

    /**
     * Pega o total dos lançamentos para o detalhe da conta corrente.
     * @param array $aTotalLancamentos Coleção com os totalizadores dos lançamentos agrupados.
     * @param stdClass $oContaCorrenteDetalhe Objeto com as informações dos detalhes da conta corrente.
     * @return stdClass
     */
    private function getTotalLancamentosContaCorrenteDetalhe($aTotalLancamentos, stdClass $oContaCorrenteDetalhe)
    {

        $iContaCorrente = $oContaCorrenteDetalhe->c19_contacorrente;
        $iReduzido = $oContaCorrenteDetalhe->c19_reduz;
        $iInstituicao = $oContaCorrenteDetalhe->c19_instit;
        $iRecurso = $oContaCorrenteDetalhe->c19_orctiporec;
        $iCaracPeculiar = isset($oContaCorrenteDetalhe->c19_concarpeculiar) ? $oContaCorrenteDetalhe->c19_concarpeculiar : -1;

        $oTotalLancamentoContaCorrenteDetalhe = new stdClass();
        $oTotalLancamentoContaCorrenteDetalhe->total_credito = 0;
        $oTotalLancamentoContaCorrenteDetalhe->total_debito = 0;

        if ($this->iAgrupamento == self::AGRUPAMENTO_RECURSO) {

            if (isset($aTotalLancamentos[$iContaCorrente][$iReduzido][$iInstituicao][$iRecurso])) {
                $oTotalLancamentoContaCorrenteDetalhe = $aTotalLancamentos[$iContaCorrente][$iReduzido][$iInstituicao][$iRecurso];
            }
        } else {
            if (isset($aTotalLancamentos[$iContaCorrente][$iReduzido][$iInstituicao][$iRecurso][$iCaracPeculiar])) {
                $oTotalLancamentoContaCorrenteDetalhe = $aTotalLancamentos[$iContaCorrente][$iReduzido][$iInstituicao][$iRecurso][$iCaracPeculiar];
            }
        }
        return $oTotalLancamentoContaCorrenteDetalhe;
    }

    /**
     * @return object
     * @throws DBException
     * @throws ParameterException
     */
    public function getDadosSimplificado()
    {
        $dados = $this->getDados();
        $dados->nTotalMovimentacaoDebito *= -1;
        $dadosRetorno = (object)array(
            'totalMovimentacaoDebito' => trim($dados->nTotalMovimentacaoDebito),
            'totalMovimentacaoCredito' => trim($dados->nTotalMovimentacaoCredito),
            'totalDebitoMenosCredito' => abs(($dados->nTotalMovimentacaoDebito - $dados->nTotalMovimentacaoCredito))
        );
        return $dadosRetorno;
    }

}
