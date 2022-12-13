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

namespace ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao;

use Dompdf\Exception;
use DBDate;
use LicitacaoAtributosDinamicos;
use stdClass;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Licitacao as LicitacaoRegra;

/**
 * Class Proposta
 * @package Ecidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao
 */
class Proposta extends BaseAbstract
{

    /**
     * Código do leiaute da versão 1.2
     * @var integer
     */
    const CODIGO_LAYOUT_V12 = 243;

    /**
     * @var integer
     */
    const CODIGO_LAYOUT_V14 = 296;

    /**
     * Resultado Classificado
     * @var string
     */
    const RESULTADO_CLASSIFICADO = 'C';

    /**
     * Resultado Desclassificado
     * @var string
     */
    const RESULTADO_DESCLASSIFICADO = 'D';

    /**
     * Resultado Pendente
     * @var string
     */
    const RESULTADO_PENDENTE = 'P';

    /**
     * @var \licitacao
     */
    private $oLicitacao;

    /**
     * @var \OrcamentoFornecedor
     */
    private $oFornecedor;

    /**
     * @var \ItemOrcamento
     */
    private $oItem;

    /**
     * Modalidades que não são permitidas nos arquivos
     * @var array
     */
    private static $aModalidadesNaoPermitidas = array('CPC', 'RPO', 'PRD', 'PRI', 'MAI');

    /**
     * @return int
     */
    public function getCodigoLayout()
    {
        $iCodigoLayout = self::CODIGO_LAYOUT_V12;
        switch ($this->oConfiguracao->getVersao()) {
            case '1.4':
                $iCodigoLayout = self::CODIGO_LAYOUT_V14;
                break;
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
     * @param \OrcamentoFornecedor $oFornecedor
     */
    public function setFornecedor(\OrcamentoFornecedor $oFornecedor)
    {
        $this->oFornecedor = $oFornecedor;
    }

    /**
     * @param \ItemOrcamento $oItem
     */
    public function setItem(\ItemOrcamento $oItem)
    {
        $this->oItem = $oItem;
    }

    /**
     * @param \OrcamentoFornecedor $oOrcamentoFornecedor
     *
     * @return string
     * @throws \Exception
     */
    public function getResultadoPorFornecedor(\OrcamentoFornecedor $oOrcamentoFornecedor)
    {
        if (empty($this->oLicitacao)) {
            throw new \Exception("Objeto do tipo Licitação não informado.");
        }

        $sSiglaTribunal = $this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
        $lTipoJulgamentoGlobal = $this->oLicitacao->getTipoJulgamento() == \licitacao::TIPO_JULGAMENTO_GLOBAL;
        $lModalidadePermitida = !in_array($sSiglaTribunal, self::$aModalidadesNaoPermitidas);
        $lSituacaoJulgada = $this->oLicitacao->getSituacao()->getCodigo() == \SituacaoLicitacao::SITUACAO_JULGADA;
        $sTipoResultado = '';
        if (!$lTipoJulgamentoGlobal || !$lModalidadePermitida) {
            return $sTipoResultado;
        }

        $sTipoResultado = $this->getSiglaResultado($oOrcamentoFornecedor);
        if ((float)$this->oConfiguracao->getVersao() >= 1.3 && $lSituacaoJulgada && (!$this->oLicitacao->getDataAjudicacao() || !$this->oLicitacao->getDataHomologacao())) {
            $sTipoResultado = self::RESULTADO_PENDENTE;
        }
        return $sTipoResultado;
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \ParameterException
     */
    public function getResultadoProposta()
    {
        $lLicitacaoJulgada = $this->oLicitacao->getSituacao()->getCodigo() == \SituacaoLicitacao::SITUACAO_JULGADA;
        $nVersao = (float)$this->oConfiguracao->getVersao();
        $sTipoResultado = self::getSiglaResultado($this->oFornecedor, $this->oItem);
//    if ($nVersao >= 1.3 && $lLicitacaoJulgada && (!$this->oLicitacao->getDataAjudicacao() || !$this->oLicitacao->getDataHomologacao())) {
//      $sTipoResultado = self::RESULTADO_PENDENTE;
//    }
        return $sTipoResultado;
    }


    /**
     * @param \OrcamentoFornecedor $oFornecedor
     * @param \ItemOrcamento|null $oItem
     *
     * @return mixed
     * @throws \Exception
     */
    private static function getSiglaResultado(\OrcamentoFornecedor $oFornecedor, \ItemOrcamento $oItem = null)
    {
        if ($oFornecedor->getCodigo() == "") {
            return self::RESULTADO_DESCLASSIFICADO;
        }

        $aWhere = array("pcorcamforne.pc21_orcamforne = {$oFornecedor->getCodigo()}");
        if (!empty($oItem)) {
            $aWhere[] = "pcorcamitem.pc22_orcamitem = {$oItem->getCodigo()}";
        }

        $sCampos = "case when pc32_orcamitem is not null then '" . self::RESULTADO_DESCLASSIFICADO . "' ";
        $sCampos .= "when pc23_vlrun is null or pc23_vlrun = 0 then '" . self::RESULTADO_DESCLASSIFICADO . "' else '" . self::RESULTADO_CLASSIFICADO . "' end as tipo_resultado";
        $oDaoOrcamento = new \cl_pcorcamitemlic();
        $sSqlBuscaItem = $oDaoOrcamento->sql_query_fornecedor_valores($sCampos, implode(' and ', $aWhere));
        $rsBuscaItem = $oDaoOrcamento->sql_record($sSqlBuscaItem);
        if (!$rsBuscaItem || $oDaoOrcamento->erro_status == "0" || $oDaoOrcamento->numrows == 0) {
            throw new \Exception("Ocorreu um erro ao consultar os dados para o campo TP_RESULTADO_PROPOSTA.");
        }
        return \db_utils::fieldsMemory($rsBuscaItem, 0)->tipo_resultado;
    }

    /**
     * Retorna o resultado da proposta do fornecedor caso a licitação seja julgada por Lote
     * @return string
     * @throws Exception
     */
    public function getResultadoLicitacaoPorLote()
    {
        if (empty($this->oFornecedor) || empty($this->oItem)) {
            throw new Exception("Informe o Fornecedor e Item que deseja saber o resultado da proposta.");
        }
        return $this->verificaModalidadeJulgamento(\licitacao::TIPO_JULGAMENTO_POR_LOTE) ? $this->getResultadoProposta() : '';
    }

    /**
     * Retorna o resultado da proposta do fornecedor caso a licitação seja julgada por ITEM
     * @return string
     * @throws Exception
     */
    public function getResultadoLicitacaoPorItem()
    {
        if (empty($this->oFornecedor) || empty($this->oItem)) {
            throw new Exception("Informe o Fornecedor e Item que deseja saber o resultado da proposta.");
        }
        return $this->verificaModalidadeJulgamento(\licitacao::TIPO_JULGAMENTO_POR_ITEM) ? $this->getResultadoProposta() : '';
    }

    /**
     * Retorna o resultado da proposta quando a licitação seja julgada de forma global.
     * @return string
     * @throws Exception
     */
    public function getResultadoLicitacaoGlobal()
    {
        if (empty($this->oFornecedor)) {
            throw new Exception("Informe o Fornecedor que deseja saber o resultado da proposta.");
        }
        return $this->verificaModalidadeJulgamento(\licitacao::TIPO_JULGAMENTO_GLOBAL) ? $this->getResultadoProposta() : '';
    }

    /**
     * Método que verifica se a modalidade e o julgamento da licitação estão de acordo com o tipo solicitado
     * @param $iTipoJulgamento
     * @return bool
     * @throws Exception
     */
    private function verificaModalidadeJulgamento($iTipoJulgamento)
    {
        if (empty($this->oLicitacao)) {
            throw new Exception("Licitação não informada.");
        }

        $lModalidadePermitida = !in_array($this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal(),
            self::$aModalidadesNaoPermitidas);
        if ($this->oLicitacao->getTipoJulgamento() != $iTipoJulgamento || !$lModalidadePermitida) {
            return false;
        }
        return true;
    }
}
