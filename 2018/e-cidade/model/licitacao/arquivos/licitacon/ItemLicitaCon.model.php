<?php

/**
  * E-cidade Software Publico para Gestao Municipal
  * Copyright (C) 2017 DBSeller Servicos de Informatica
  *                     www.dbseller.com.br
  *                     e-cidade@dbseller.com.br
  *
  * Este programa e software livre; voce pode redistribui-lo e/ou
  * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
  * publicada pela Free Software Foundation; tanto a versao 2 da
  * Licenca como (a seu criterio) qualquer versao mais nova.
  *
  * Este programa e distribuido na expectativa de ser util, mas SEM
  * QUALQUER GARANTIA; sem mesmo a garantia implicita de
  * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
  * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
  * detalhes.
  *
  * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
  * junto com este programa; se nao, escreva para a Free Software
  * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
  * 02111-1307, USA.
  *
  * Copia da licenca no diretorio licenca/licenca_en.txt
  *                               licenca/licenca_pt.txt
  *
  * PHP version 5
  */

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Item as Regra;

/**
 * Class ItemLicitaCon
 */
class ItemLicitaCon extends ArquivoLicitaCon
{

    /**
      * Caminho das mensagens de aviso ao usuário
      *
      * @var string
      */
    const MENSAGEM = 'patrimonial.licitacao.ItemLicitaCon.';

    /**
      * Nome do arquivo .txt
      *
      * @var string
      */
    const NOME_ARQUIVO  = 'ITEM';

    /**
     * ItemLicitaCon constructor.
     *
     * @param CabecalhoLicitaCon $oCabecalho Cabeçalho do arquivo
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * Retorna todos itens da licitação
     *
     * @return array
     * @throws DBException
     */
    public function getDados()
    {
        $aItens = array();
        $sCampos = 'distinct l20_numero, l20_codigo, l21_codigo, l21_ordem, ';
        $sCampos .= 'pc01_descrmater, pc11_quant, m61_codigotribunal, ';
        $sCampos .= 'pc17_codigo, pc23_orcamitem, z01_numcgm, pc23_bdi, pc23_encargossociais, ';
        $sCampos .= '( ';
        $sCampos .= '  select min(l04_codigo) ';
        $sCampos .= '  from liclicitem as l1 ';
        $sCampos .= '  inner join liclicitemlote as l2 on l1.l21_codigo = l2.l04_liclicitem ';
        $sCampos .= '  where l1.l21_codliclicita = liclicita.l20_codigo and l2.l04_descricao = liclicitemlote.l04_descricao ';
        $sCampos .= ') as nr_lote';

        $sWhere = implode(' and ', LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao()));
        $sOrder = ' l20_numero, l20_codigo ';

        $oDaoLiclicitem = new cl_liclicitem;
        $sSqlItensLicitacao = $oDaoLiclicitem->sql_query_item_licitacon($sCampos, $sWhere, $sOrder);
        $rsItensLicitacao = db_query($sSqlItensLicitacao);

        if (!$rsItensLicitacao) {
            throw new DBException(_M(self::MENSAGEM . 'erro_busca_itens'));
        }

        $iTotalRegistros = pg_num_rows($rsItensLicitacao);

        for ($i = 0; $i < $iTotalRegistros; $i++) {

            $oStdItem = db_utils::fieldsMemory($rsItensLicitacao, $i);
            $oLicitacao = LicitacaoRepository::getByCodigo($oStdItem->l20_codigo);
            $this->oRegra->setLicitacao($oLicitacao);
            $oAtributosDinamicos = new LicitacaoAtributosDinamicos($oLicitacao->getCodigo());

            $oDadosOSE = $this->oRegra->getDadosOSE($oStdItem->l21_codigo, $oAtributosDinamicos->getAtributo('tipoobjeto', null));
            $oValoresEstimadoHomologado = $this->oRegra->getValoresEstimadoHomologado($oStdItem->l21_codigo, $oStdItem->z01_numcgm, $oStdItem->pc23_orcamitem);
            $oDbiEngargos = $this->oRegra->getDBIEncargos($oStdItem->pc23_bdi, $oStdItem->pc23_encargossociais, $oAtributosDinamicos->getAtributo('tipoobjeto', null));
            $oFornecedores = $this->oRegra->getFornecedores($oStdItem->z01_numcgm);

            $oDados = new stdClass;
            $oDados->NR_LICITACAO = $oLicitacao->getEdital();
            $oDados->ANO_LICITACAO = $oLicitacao->getAno();
            $oDados->CD_TIPO_MODALIDADE = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
            $oDados->NR_LOTE = $this->oRegra->getNumeroLote($oStdItem->nr_lote);
            $oDados->NR_ITEM = $oStdItem->l21_ordem;
            $oDados->NR_ITEM_ORIGINAL = $oStdItem->l21_codigo;
            $oDados->DS_ITEM = $oStdItem->pc01_descrmater;
            $oDados->QT_ITENS = number_format($oStdItem->pc11_quant, 2, ',', '');
            $oDados->SG_UNIDADE_MEDIDA = $this->oRegra->getSiglaUnidadeMedida($oStdItem->pc17_codigo, $oStdItem->m61_codigotribunal);
            $oDados->VL_UNITARIO_ESTIMADO = $oValoresEstimadoHomologado->unitario_estimado;
            $oDados->VL_TOTAL_ESTIMADO = $oValoresEstimadoHomologado->total_estimado;
            $oDados->DT_REF_VALOR_ESTIMADO = $oDadosOSE->data;
            $oDados->PC_BDI_ESTIMADO = $oDadosOSE->bdiEstimado;
            $oDados->PC_ENCARGOS_SOCIAIS_ESTIMADO = $oDadosOSE->encargosEstimado;
            $oDados->CD_FONTE_REFERENCIA = $oDadosOSE->codigo;
            $oDados->DS_FONTE_REFERENCIA = 'COTACAO';
            $oDados->TP_RESULTADO_ITEM = $this->oRegra->getResultadoItem($oLicitacao, $oStdItem->z01_numcgm, $oStdItem->pc23_orcamitem);
            $oDados->PC_BDI_HOMOLOGADO = $oDbiEngargos->dbi;
            $oDados->PC_ENCARGOS_SOCIAIS_HOMOLOGADO = $oDbiEngargos->encargos;
            $oDados->TP_ORCAMENTO = $oAtributosDinamicos->getAtributo('tipoorcamento', null);
            $oDados->TP_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->tipo;
            $oDados->NR_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->documento;

            $oDados->TP_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->tipo;
            $oDados->NR_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->documento;
            $oDados->VL_UNITARIO_HOMOLOGADO = $oValoresEstimadoHomologado->unitario_homologado;
            $oDados->VL_TOTAL_HOMOLOGADO = $oValoresEstimadoHomologado->total_homologado;
            $oDados->CD_TIPO_FAMILIA = null;
            $oDados->CD_TIPO_SUBFAMILIA = null;

            $oDados->TP_BENEFICIO_MICRO_EPP = $this->oRegra->getTipoBeneficioEpp($oLicitacao);

            $oDados->PC_TX_ESTIMADA = $this->oRegra->getTaxaEstimada($oDados, $oLicitacao, $oStdItem);
            $oDados->PC_TX_HOMOLOGADA = $this->oRegra->getTaxaHomologada($oDados, $oLicitacao, $oStdItem);

            if ($this->_chamamentoPublicoCredenciamento($oLicitacao)) {
                $oDados->TP_DOCUMENTO_VENCEDOR = null;
                $oDados->NR_DOCUMENTO_VENCEDOR = null;
                $oDados->VL_UNITARIO_HOMOLOGADO = null;
                $oDados->VL_TOTAL_HOMOLOGADO = null;
            }

            if ($this->descartarItem($oStdItem)) {
                continue;
            }

            /**
              * Criamos um hash com as informações
              * do item de Licitação, para não
              * duplicar a chave de registro
              */
            $hashItem = "$oDados->NR_LICITACAO|$oDados->ANO_LICITACAO|$oDados->CD_TIPO_MODALIDADE|$oDados->NR_LOTE|$oDados->NR_ITEM";
            $aItens[$hashItem] = $oDados;
        }

        return $aItens;
    }

    /**
      * Método responsável por definir se o item deve ser descartado com base na seguinte regra:
      * Licitação do tipo PRD, PRI ou RPO e que não tenha fornecedor com valor lançado
      *
      * @param  stdClass $oStdItem Item a ser verificado
      * @return bool
      * @throws Exception
      */
    protected function descartarItem(stdClass $oStdItem)
    {
        $oLicitacao = LicitacaoRepository::getByCodigo($oStdItem->l20_codigo);
        $lDescartarModalidade = in_array($oLicitacao->getModalidade()->getSiglaTipoCompraTribunal(), array('PRD', 'PRI', 'RPO'));

        if ($lDescartarModalidade) {
            if (empty($oStdItem->pc23_orcamitem)) {
                return true;
            }

            $condicoes = array(
                "pc23_orcamitem = {$oStdItem->pc23_orcamitem}",
                "z01_numcgm = {$oStdItem->z01_numcgm}",
                'pc23_vlrun > 0'
            );

            $sWhere = implode(' and ', $condicoes);

            $oDaoValores = new cl_pcorcamval;
            $sSqlValor = $oDaoValores->sql_query_julg(null, null, 'pcorcamval.*', null, $sWhere);
            $rsBuscaValor = db_query($sSqlValor);

            if (!$rsBuscaValor) {
                throw new Exception(_M(self::MENSAGEM . 'erro_consulta_valores'));
            }

            if (pg_num_rows($rsBuscaValor) == 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param licitacao $oLicitacao
     * @return bool
     */
    private function _chamamentoPublicoCredenciamento(licitacao $oLicitacao)
    {
        return $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal() == LicitacaoModalidade::CHAMAMENTO_PUBLICO_CREDENCIAMENTO;
    }
}
