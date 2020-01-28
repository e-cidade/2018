<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Orcamento de um Fornecedor para licitações/Processos de compras /SOlicitacoes
 * Class OrcamentoFornecedor
 * @package Compras
 */
class OrcamentoFornecedor
{

    /**
     * @var integer do Orcamento
     */
    private $iCodigo;

    /**
     * @var CgmJuridico|CgmFisico
     */
    private $oFornecedor = null;

    /**
     * Cotacoes
     * @var CotacaoItem[]
     */
    private $aCotacoes = array();

    /**
     * @var OrcamentoCompra
     */
    private $oOrcamento = null;

    /**
     * Prazo de entrega dos itens
     * @var DBDate
     */
    private $oPrazoEntrega;

    /**
     * Validade do orçamento
     * @var DBDate
     */
    private $oValidadeOrcamento;

    /**
     * @param null $iCodigo
     */
    public function __construct($iCodigo = null)
    {

        $this->iCodigo = $iCodigo;
        if (empty($this->iCodigo)) {
            return;
        }
    }

    /**
     * @return CotacaoItem[]
     */
    public function getCotacoes()
    {
        return $this->aCotacoes;
    }

    /**
     * @param CotacaoItem $oCotacao
     */
    public function adicionarCotacao(CotacaoItem $oCotacao)
    {
        $this->aCotacoes[] = $oCotacao;
    }

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * @param integer $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodigo = $iCodigo;
    }

    /**
     * @return CgmFisico|CgmJuridico
     * @throws BusinessException
     * @throws DBException
     */
    public function getFornecedor()
    {

        if (empty($this->oFornecedor) && !empty($this->iCodigo)) {

            $daoFornecedor = new cl_pcorcamforne();
            $buscaFornecedor = $daoFornecedor->sql_query_file($this->iCodigo, 'pc21_numcgm');
            $resBuscaFornecedor = db_query($buscaFornecedor);
            if (!$resBuscaFornecedor) {
                throw new DBException("Ocorreu um erro ao consultar os dados do fornecedor.");
            }
            if (pg_num_rows($resBuscaFornecedor) === 0) {
                throw new BusinessException("Não foi encontrado CGM para o fornecedor informado.");
            }
            $this->setFornecedor(CgmRepository::getByCodigo(db_utils::fieldsMemory($resBuscaFornecedor,
                0)->pc21_numcgm));
        }
        return $this->oFornecedor;
    }

    /**
     * @param CgmBase|CgmFisico|CgmJuridico $oFornecedor
     */
    public function setFornecedor(CgmBase $oFornecedor)
    {
        $this->oFornecedor = $oFornecedor;
    }

    /**
     * Prazo de Entrega
     * @return DBDate
     */
    public function getPrazoEntrega()
    {
        return $this->oPrazoEntrega;
    }

    /**
     * Prazo de entrega dos materiais/ servico
     * @param DBDate $oPrazoEntrega
     */
    public function setPrazoEntrega(DBDate $oPrazoEntrega = null)
    {
        $this->oPrazoEntrega = $oPrazoEntrega;
    }

    /**
     * @return DBDate
     */
    public function getValidadeOrcamento()
    {
        return $this->oValidadeOrcamento;
    }

    /**
     * Data de validade do orçamento
     * @param DBDate $oValidadeOrcamento
     */
    public function setValidadeOrcamento(DBDate $oValidadeOrcamento = null)
    {
        $this->oValidadeOrcamento = $oValidadeOrcamento;
    }

    /**
     * Orcameto de compra
     * @param OrcamentoCompra $oOrcamento
     */
    public function setOrcamento(OrcamentoCompra $oOrcamento)
    {
        $this->oOrcamento = $oOrcamento;
    }

    /**
     * Persiste os dados do orcamento
     */
    public function salvar()
    {

        $oDaoOrcamForne = new cl_pcorcamforne();
        $oDaoOrcamForne->pc21_codorc = $this->oOrcamento->getCodigo();
        $oDaoOrcamForne->pc21_numcgm = $this->getFornecedor()->getCodigo();
        $oDaoOrcamForne->pc21_prazoent = '';
        $oDaoOrcamForne->pc21_validadorc = '';
        if (!empty($this->oValidadeOrcamento)) {
            $oDaoOrcamForne->pc21_validadorc = $this->oValidadeOrcamento->getDate();
        }
        if (!empty($this->oPrazoEntrega)) {
            $oDaoOrcamForne->pc21_prazoent = $this->oPrazoEntrega->getDate();
        }

        if ($this->getCodigo() == '') {

            $oDaoOrcamForne->incluir(null);
            $this->iCodigo = $oDaoOrcamForne->pc21_orcamforne;
        } else {

            $oDaoOrcamForne->pc21_orcamforne = $this->getCodigo();
            $oDaoOrcamForne->alterar($this->getCodigo());
        }
        $this->salvarCotacoes();
    }

    /**
     * Persiste as cotacoes do fornecedor
     */
    private function salvarCotacoes()
    {

        $oDaoOrcamVal = new cl_pcorcamval;
        $oDaoOrcamVal->excluir(null, null, "pc23_orcamforne={$this->getCodigo()}");
        foreach ($this->aCotacoes as $oCotacao) {

            $oDaoOrcamVal->pc23_valor = $oCotacao->getValorTotal();
            $oDaoOrcamVal->pc23_quant = $oCotacao->getQuantidade();
            $oDaoOrcamVal->pc23_vlrun = $oCotacao->getValorUnitario();
            $oDaoOrcamVal->pc23_orcamforne = $this->getCodigo();
            $oDaoOrcamVal->pc23_orcamitem = $oCotacao->getItem()->getCodigo();
            $oDaoOrcamVal->pc23_percentualdesconto = $oCotacao->getValorDesconto();
            $oDaoOrcamVal->pc23_bdi = $oCotacao->getBdi();
            $oDaoOrcamVal->pc23_encargossociais = $oCotacao->getEncargosSociais();
            $oDaoOrcamVal->pc23_notatecnica = $oCotacao->getNotaTecnica();
            $oDaoOrcamVal->pc23_data = $oCotacao->getData()->getDate();
            $oDaoOrcamVal->incluir($this->getCodigo(), $oCotacao->getItem()->getCodigo());
        }
    }
}