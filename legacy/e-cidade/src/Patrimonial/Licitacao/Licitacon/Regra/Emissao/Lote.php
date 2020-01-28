<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 04/11/16
 * Time: 17:08
 */

namespace ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao;

use ECidade\Patrimonial\Licitacao\Licitacon\Julgamento;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\BaseAbstract;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Item;
use ECidade\Patrimonial\Licitacao\Licitacon\Resultado;
use ECidade\Patrimonial\Licitacao\Licitacon\Situacao;
use \stdClass;
use \DBException;
use \OrcamentoLicitacao;
use \EventoLicitacao;
use \LicitacaoAtributosDinamicos;
use \ItemLicitaCon;
use \ParameterException;

class Lote extends BaseAbstract
{

    /**
     * Código do leiaute da versão 1.2
     * @var integer
     */
    const CODIGO_LAYOUT_V12 = 240;

    /**
     * Código do leiaute da versão 1.3
     * @var integer
     */
    const CODIGO_LAYOUT_V13 = 271;
    const CODIGO_LAYOUT_V14 = 293;

    /**
     * @var \licitacao
     */
    private $oLicitacao;

    /**
     * @var string Lote
     */
    private $sLote;

    /**
     * @return int
     */
    public function getCodigoLayout()
    {
        $iCodigoLayout = self::CODIGO_LAYOUT_V12;
        switch ($this->oConfiguracao->getVersao()) {
            case '1.3':
                $iCodigoLayout = self::CODIGO_LAYOUT_V13;
                break;
            case '1.4':
                $iCodigoLayout = self::CODIGO_LAYOUT_V14;
        }
        return $iCodigoLayout;
    }

    /**
     * @param \licitacao $oLicitacao
     */
    public function setLicitacao(\licitacao $oLicitacao)
    {
        $this->oLicitacao = $oLicitacao;
    }

    /**
     * Lote.
     * @param string $sLote
     */
    public function setLote($sLote)
    {
        $this->sLote = $sLote;
    }

    /**
     * Busca o valor estimado dos itens do lote
     *
     * @return float
     * @throws DBException
     */
    public function getValorEstimado()
    {
        $oOrcamentoLicitacao = new OrcamentoLicitacao($this->oLicitacao);
        if (!empty($this->sLote)) {
            $oOrcamentoLicitacao->setDescricaoLote($this->sLote);
        }

        return number_format($oOrcamentoLicitacao->getValorTotal(), 2, ',', '');
    }

    /**
     * Busca o valor homologado dos itens do lote, no caso em que o campo é obrigatório
     *
     * @return null|float
     * @throws DBException
     */
    public function getValorHomologado()
    {
        $nVersao = floatval($this->oConfiguracao->getVersao());
        $aModalidades = array('PRD', 'PRI', 'RPO', 'CPC', 'MAI');
        $sModalidade = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
        if ($nVersao >= 1.3 && in_array($sModalidade, $aModalidades)) {
            return null;
        }

        $iFase = $this->oLicitacao->getFase();

        $oResultado = new Resultado($this->oConfiguracao, $this->oLicitacao);
        $oSituacao = $oResultado->getResultado();

        if (empty($oSituacao) || !$oSituacao->isAdjudicada()) {
            return null;
        }

        if ($iFase != EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
            return null;
        }

        $oOrcamentoLicitacao = new OrcamentoLicitacao($this->oLicitacao);
        if (!empty($this->sLote)) {
            $oOrcamentoLicitacao->setDescricaoLote($this->sLote);
        }
        return number_format($oOrcamentoLicitacao->getValorTotalHomologado(), 2, ',', '');
    }

    /**
     * Vencedor do lote da licitação.
     * @return stdClass
     */
    public function getFornecedores()
    {
        $iFase = $this->oLicitacao->getFase();
        $iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();
        $oJulgamento = new Julgamento($iTipoJulgamento);

        $oFornecedor = new stdClass();
        $oFornecedor->tipo = null;
        $oFornecedor->documento = null;

        $oVencedor = new stdClass();
        $oVencedor->tipo = null;
        $oVencedor->documento = null;

        $oFornecedores = new stdClass();
        $oFornecedores->fornecedor = $oFornecedor;
        $oFornecedores->vencedor = $oVencedor;

        if (!$oJulgamento->isLote()) {
            return $oFornecedores;
        }

        $oOrcamentoLicitacao = new OrcamentoLicitacao($this->oLicitacao);
        if (!empty($this->sLote)) {
            $oOrcamentoLicitacao->setDescricaoLote($this->sLote);
        }
        $oFornecedorBusca = $oOrcamentoLicitacao->getFornecedorVencedor();

        $nVersao = floatval($this->oConfiguracao->getVersao());
        if ($nVersao >= 1.3 && in_array($this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal(),
                array("PRD", "PRI", "RPO"))) {
//			$oFornecedores->fornecedor->tipo = $oFornecedorBusca->tipoPessoa;
//			$oFornecedores->fornecedor->documento = $oFornecedorBusca->documento;
        } else {
            if ($iFase != EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
                return $oFornecedores;
            }

//			$oFornecedores->vencedor->tipo = $oFornecedorBusca->tipoPessoa;
//			$oFornecedores->vencedor->documento = $oFornecedorBusca->documento;
        }
        return $oFornecedores;
    }

    /**
     * Verifica a descrição do lote.
     *
     * @return string
     */
    public function getDescricaoLote()
    {
        $sLote = $this->sLote;

        $oAtributo = new LicitacaoAtributosDinamicos($this->oLicitacao->getCodigo());
        $lLoteUnico = $oAtributo->getAtributo('caracteristicaobjeto', null) == "LU";
        $lObrasServicos = $oAtributo->getAtributo('tipoobjeto') == Item::TP_OBJETO_OBRAS_SERVICO_ENGENHARIA;
        if ($lLoteUnico && $lObrasServicos) {
            $sLote = "LOTE UNICO";
        }
        return $sLote;
    }

    /**
     * Verifica se deve monstrar o lote no arquivo LOTE.TXT.
     * Caso o lote não tenha vencedor na fase de Adjudicação/Homologação o lote está desclassificado;
     * @param stdClass $oFornecedores
     *
     * @return bool
     */
    public function mostrarLote($oFornecedores)
    {
        $iFase = $this->oLicitacao->getFase();
        $iTipoJulgamento = $this->oLicitacao->getTipoJulgamento();
        $oJulgamento = new Julgamento($iTipoJulgamento);

        if ($oFornecedores->vencedor->documento === null
            && $oFornecedores->fornecedor->documento === null
            && $oJulgamento->isLote()
            && $iFase == EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO) {
            return false;
        }
        return true;
    }

    /**
     * Retorna o resultado do lote da licitação conforma regras do LicitaCon.
     *
     * @return null|string
     * @throws ParameterException
     */
    public function getResultadoLote($oFornecedor = null)
    {
        $oResultado = new Resultado($this->oConfiguracao, $this->oLicitacao);
        $oSituacao = $oResultado->getResultadoLote($oFornecedor);
        if (empty($oSituacao)) {
            return null;
        }
        return $oSituacao->getSigla();
    }

    public function obterTipoBeneficioMicroempresaEmpresaPequenoPorte(\licitacao $licitacao)
    {
        $nivelJulgamento = $licitacao->obterNivelJulgamento();
        $siglaTipoCompraTribunal = $licitacao->getModalidade()->getSiglaTipoCompraTribunal();
        $modalidades = array('RIN', 'CNC', 'CNV', 'PRE', 'PRP', 'TMP', 'RDC', 'RDE', 'CHP', 'EST', 'ESE');

        if ($nivelJulgamento == 'L' && in_array($siglaTipoCompraTribunal, $modalidades)) {
            return $licitacao->obterTipoBeneficioMicroempresaEmpresaPequenoPorte();
        }

        return '';
    }
}
