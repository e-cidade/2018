<?php

namespace ECidade\Patrimonial\Licitacao\Licitacon\Campo;

use db_utils;
use DBException;
use licitacao as Licitacao;

/**
 * Class ProcessoCompraTaxa
 * @package ECidade\Patrimonial\Licitacao\Licitacon\Campo
 */
class ProcessoCompraTaxa
{
    /**
     * @var string
     */
    private $colunaTaxaEstimada = 'pc23_taxaestimada';
    /**
     * @var string
     */
    private $colunaTaxaHomologada = 'pc23_taxahomologada';
    /**
     * @var Licitacao
     */
    private $licitacao;
    /**
     * @var integer
     */
    private $codigoItem;
    /**
     * @var integer
     */
    private $codigoFornecedor;
    /**
     * @var integer
     */
    private $arquivo;

    /**
     * ProcessoCompraTaxa constructor.
     * @param $codigoLicitacao
     * @param $arquivo
     * @param integer $codigoItem
     * @param integer $codigoFornecedor
     */
    public function __construct($codigoLicitacao, $arquivo, $codigoItem = null, $codigoFornecedor = null)
    {
        $this->setLicitacao(new Licitacao($codigoLicitacao))
            ->setCodigoItem($codigoItem)
            ->setCodigoFornecedor($codigoFornecedor)
            ->setArquivo($arquivo);
    }

    /**
     * @return string
     */
    public function getColunaTaxaEstimada()
    {
        return $this->colunaTaxaEstimada;
    }

    /**
     * @param string $colunaTaxaEstimada
     * @return ProcessoCompraTaxa
     */
    public function setColunaTaxaEstimada($colunaTaxaEstimada)
    {
        $this->colunaTaxaEstimada = $colunaTaxaEstimada;
        return $this;
    }

    /**
     * @return string
     */
    public function getColunaTaxaHomologada()
    {
        return $this->colunaTaxaHomologada;
    }

    /**
     * @param string $colunaTaxaHomologada
     * @return ProcessoCompraTaxa
     */
    public function setColunaTaxaHomologada($colunaTaxaHomologada)
    {
        $this->colunaTaxaHomologada = $colunaTaxaHomologada;
        return $this;
    }

    /**
     * @return Licitacao
     */
    public function getLicitacao()
    {
        return $this->licitacao;
    }

    /**
     * @param Licitacao $licitacao
     * @return ProcessoCompraTaxa
     */
    public function setLicitacao($licitacao)
    {
        $this->licitacao = $licitacao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoItem()
    {
        return $this->codigoItem;
    }

    /**
     * @param integer $codigoItem
     * @return ProcessoCompraTaxa
     */
    public function setCodigoItem($codigoItem)
    {
        $this->codigoItem = $codigoItem;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoFornecedor()
    {
        return $this->codigoFornecedor;
    }

    /**
     * @param integer $codigoFornecedor
     * @return ProcessoCompraTaxa
     */
    public function setCodigoFornecedor($codigoFornecedor)
    {
        $this->codigoFornecedor = $codigoFornecedor;
        return $this;
    }

    /**
     * @return integer
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @param integer $arquivo
     * @return ProcessoCompraTaxa
     */
    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    /**
     * @return string
     */
    public function obterValorEstimado()
    {
        return $this->obterValor($this->colunaTaxaEstimada);
    }

    /**
     * @return string
     */
    public function obterValorHomologado()
    {
        return $this->obterValor($this->colunaTaxaHomologada);
    }

    /**
     * @param $coluna
     * @return string
     * @throws DBException
     */
    private function obterValor($coluna)
    {
        $licitacao = $this->getLicitacao();
        $tipoJulgamento = $this->getArquivo();
        $tipoNivelJulgamento = $licitacao->obterNivelJulgamento();

        $tipoMenorTaxa = $licitacao->getModalidade()->getSiglaTipoCompraTribunal() == 'MTX';
        $tipoGlobal = $tipoJulgamento == Licitacao::TIPO_JULGAMENTO_GLOBAL && $tipoNivelJulgamento == 'G';
        $tipoLote = $tipoJulgamento == Licitacao::TIPO_JULGAMENTO_POR_LOTE && $tipoNivelJulgamento == 'L';
        $tipoItem = $tipoJulgamento == Licitacao::TIPO_JULGAMENTO_POR_ITEM && $tipoNivelJulgamento == 'I';

        if (!$tipoMenorTaxa || (!$tipoGlobal && !$tipoLote && !$tipoItem)) {
            return '';
        }

        $where = array();
        $where[] = 'l20_codigo = ' . $licitacao->getCodigo();

        if ($codigoItem = $this->getCodigoItem()) {
            $colunaCodigoItem = $tipoGlobal ? 'l21_codigo' : 'pc22_orcamitem';
            $where[] = $colunaCodigoItem . ' = ' . $codigoItem;
        }

        if ($codigoFornecedor = $this->getCodigoFornecedor()) {
            $where[] = 'pc21_orcamforne = ' . $codigoFornecedor;
        }

        $where = implode(' AND ', $where);

        $query = "
            SELECT {$coluna}
            FROM liclicita
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                INNER JOIN pctipocompratribunal ON pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal
                INNER JOIN liclicitasituacao ON liclicitasituacao.l11_liclicita = liclicita.l20_codigo
                INNER JOIN liclicitem ON liclicitem.l21_codliclicita = liclicita.l20_codigo
                INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                INNER JOIN pcorcamitemlic ON pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo
                INNER JOIN pcorcamitem ON pcorcamitem.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem
                INNER JOIN pcorcamforne ON pcorcamforne.pc21_codorc = pcorcamitem.pc22_codorc
                INNER JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                INNER JOIN pcorcamfornelichabilitacao ON l17_pcorcamfornelic = pc31_orcamforne
                INNER JOIN pcorcamval ON pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne
                    AND pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
            WHERE {$where}
            LIMIT 1;
        ";

        $resultado = db_query($query);

        if ($resultado === false) {
            throw new DBException('Houve um erro ao buscar a taxa da licitação.');
        }

        $taxa = db_utils::fieldsMemory($resultado, 0)->$coluna;
        $taxa = filter_var($taxa, FILTER_VALIDATE_FLOAT);
        $taxa = number_format($taxa, 2, ',', '');

        return $taxa ? $taxa : '0,00';
    }
}
