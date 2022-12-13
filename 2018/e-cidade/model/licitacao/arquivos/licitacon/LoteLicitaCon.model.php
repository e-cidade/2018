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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Lote as Regra;
use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ProcessoCompraTaxa;

class LoteLicitaCon extends ArquivoLicitaCon
{
    const NOME_ARQUIVO = "LOTE";

    /**
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
     * @return stdClass[]
     * @throws DBException
     */
    public function getDados()
    {
        $aLotes = array();
        $oDaoLicitacao = new cl_liclicita;
        $sTipos = implode(',', array(
            licitacao::TIPO_JULGAMENTO_POR_ITEM,
            licitacao::TIPO_JULGAMENTO_GLOBAL,
        ));
        $aCampos = array(
            'distinct l20_codigo',
            'l20_tipojulg as tipo_julgamento',
            "min(coalesce(case when l20_tipojulg in({$sTipos}) then 1 else l04_codigo end, 1)) as nr_lote",
            "(case when l20_tipojulg in({$sTipos}) then null else l04_descricao end) as ds_lote",
        );
        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
            $this->oCabecalho->getDataGeracao());
        $sGroupBy = 'l20_codigo, tipo_julgamento, ds_lote';
        $sOrderBy = 'l20_codigo asc';
        $sSqlLotes = $oDaoLicitacao->sql_query_lote(implode(', ', $aCampos), implode(' and ', $aWhere), $sGroupBy,
            $sOrderBy);
        $rsLotes = db_query($sSqlLotes);

        if (!$rsLotes) {
            $sMsgErro = "Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.";
            throw new DBException($sMsgErro);
        }

        $iTotalLotes = pg_num_rows($rsLotes);
        for ($iLinha = 0; $iLinha < $iTotalLotes; $iLinha++) {
            $oLinha = db_utils::fieldsMemory($rsLotes, $iLinha);

            $oLicitacao = LicitacaoRepository::getByCodigo($oLinha->l20_codigo);
            $this->oRegra->setLicitacao($oLicitacao);
            $this->oRegra->setLote($oLinha->ds_lote);

            $oFornecedores = $this->oRegra->getFornecedores();

            $processoCompraTaxa = new ProcessoCompraTaxa($oLicitacao->getCodigo(), licitacao::TIPO_JULGAMENTO_POR_LOTE);

            $oStdLote = new stdClass;
            $oStdLote->NR_LICITACAO = $oLicitacao->getEdital();
            $oStdLote->ANO_LICITACAO = $oLicitacao->getAno();
            $oStdLote->CD_TIPO_MODALIDADE = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
            $oStdLote->NR_LOTE = $oLinha->nr_lote;
            $oStdLote->DS_LOTE = $this->oRegra->getDescricaoLote();
            $oStdLote->VL_ESTIMADO = $this->oRegra->getValorEstimado();
            $oStdLote->TP_RESULTADO_LOTE = $this->oRegra->getResultadoLote($oFornecedores);
            $oStdLote->TP_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->tipo;
            $oStdLote->NR_DOCUMENTO_VENCEDOR = $oFornecedores->vencedor->documento;
            $oStdLote->VL_HOMOLOGADO = $this->oRegra->getValorHomologado();
            $oStdLote->TP_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->tipo;
            $oStdLote->NR_DOCUMENTO_FORNECEDOR = $oFornecedores->fornecedor->documento;
            $oStdLote->TP_BENEFICIO_MICRO_EPP = $this->oRegra->obterTipoBeneficioMicroempresaEmpresaPequenoPorte($oLicitacao);
            $oStdLote->PC_TX_ESTIMADA = $processoCompraTaxa->obterValorEstimado();
            $oStdLote->PC_TX_HOMOLOGADA = $processoCompraTaxa->obterValorHomologado();

            $aLotes[] = $oStdLote;
        }

        return $aLotes;
    }

}
