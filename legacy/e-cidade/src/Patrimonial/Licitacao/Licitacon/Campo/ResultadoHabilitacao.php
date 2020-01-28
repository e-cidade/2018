<?php

namespace ECidade\Patrimonial\Licitacao\Licitacon\Campo;

use cl_pcorcamfornelichabilitacao;
use db_utils;
use DBException;
use licitacao as Licitacao;

/**
 * Class ResultadoHabilitacao
 * @package ECidade\Patrimonial\Licitacao\Licitacon\Campo
 */
class ResultadoHabilitacao
{
    /**
     * @var int
     */
    private $codigoFornecedor;
    /**
     * @var Licitacao
     */
    private $licitacao;
    /**
     * @var string
     */
    private $arquivo;

    /**
     * @var string
     *
     */
    private $versao;

    /**
     * ResultadoHabilitacao constructor.
     * @param $codigoFornecedor
     * @param Licitacao $licitacao
     * @param string $arquivo
     * @param string $versao
     */
    public function __construct($codigoFornecedor, Licitacao $licitacao, $arquivo, $versao = null)
    {
        $this->setCodigoFornecedor($codigoFornecedor)
            ->setLicitacao($licitacao)
            ->setArquivo($arquivo);
        $this->versao = $versao;
    }

    /**
     * @return int
     */
    public function getCodigoFornecedor()
    {
        return $this->codigoFornecedor;
    }

    /**
     * @return Licitacao
     */
    public function getLicitacao()
    {
        return $this->licitacao;
    }

    /**
     * @return string
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @return mixed|string
     */
    public function obterValor()
    {
        if (isset($this->versao) && $this->versao < 1.4) {
           return $this->buscarValor();
        }

        $arquivo = $this->getArquivo();
        $tipoNivelJulgamento = $this->getLicitacao()->obterNivelJulgamento();

        $julgamentoItem = $tipoNivelJulgamento == 'I';
        $julgamentoLote = $tipoNivelJulgamento == 'L';
        $julgamentoGlobal = $tipoNivelJulgamento == 'G';
        $arquivoGlobal = $arquivo == Licitacao::TIPO_JULGAMENTO_GLOBAL;
        $arquivoLote = $arquivo == Licitacao::TIPO_JULGAMENTO_POR_LOTE;
        $arquivoItem = $arquivo == Licitacao::TIPO_JULGAMENTO_POR_ITEM;

        if ($julgamentoItem && $arquivoGlobal) {
            return '';
        }

        if ($julgamentoItem && $arquivoLote) {
            return '';
        }

        if ($julgamentoLote && $arquivoGlobal) {
            return '';
        }

        if ($julgamentoLote && $arquivoItem) {
            return '';
        }

        if ($julgamentoGlobal && $arquivoItem) {
            return '';
        }

        if ($julgamentoGlobal && $arquivoLote) {
            return '';
        }

        return $this->buscarValor();
    }

    /**
     * @param int $codigoFornecedor
     * @return ResultadoHabilitacao
     */
    public function setCodigoFornecedor($codigoFornecedor)
    {
        $this->codigoFornecedor = $codigoFornecedor;
        return $this;
    }

    /**
     * @param Licitacao $licitacao
     * @return ResultadoHabilitacao
     */
    public function setLicitacao(Licitacao $licitacao)
    {
        $this->licitacao = $licitacao;
        return $this;
    }

    /**
     * @param string $arquivo
     * @return ResultadoHabilitacao
     */
    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    /**
     * @return mixed|string
     * @throws DBException
     */
    public function buscarValor()
    {
        $where = 'l17_pcorcamfornelic = ' . $this->getCodigoFornecedor();

        $pcOrcamForneLicHabilitacao = new cl_pcorcamfornelichabilitacao;
        $query = $pcOrcamForneLicHabilitacao->sql_query_file(null, 'l17_situacao', null, $where);
        $resultado = db_query($query);

        if ($resultado === false) {
            throw new DBException('Não foi possível buscar o tipo de habilitação do fornecedor.');
        }

        $situacao = db_utils::fieldsMemory($resultado, 0)->l17_situacao;
        $valores = array(1 => 'H', 2 => 'I', 3 => 'N');

        return pg_num_rows($resultado) ? $valores[$situacao] : '';
    }
}
