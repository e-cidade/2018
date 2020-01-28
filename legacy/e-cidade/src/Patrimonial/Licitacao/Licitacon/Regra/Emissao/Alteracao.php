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

namespace ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao;

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\BaseAbstract;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato as RegraContrato;

class Alteracao extends BaseAbstract
{
    const TIPO_OPERACAO_ACRESCIMO_QUANTITATIVO = 'ACA';
    const TIPO_OPERACAO_ACRESCIMO_ITENS = 'AVI';
    const TIPO_OPERACAO_REDUCAO_ITENS = 'RVP';
    const TIPO_OPERACAO_REDUCAO_QUANTITATIVO = 'RVS';
    const TIPO_OPERACAO_ALTERACAO_DOTACAO = 'ADO';
    const TIPO_OPERACAO_PRORROGACAO = 'PPC';
    const TIPO_OPERACAO_REEQUILIBRIO = 'REF';
    const TIPO_OPERACAO_REAJUSTE = 'RJP';
    const TIPO_OPERACAO_RENOVACAO = 'REN';

    /**
     * @type int
     */
    const CODIGO_LAYOUT = 245;

    const CODIGO_LAYOUT_V14 = 290;

    /**
     * @return int
     */
    public function getCodigoLayout()
    {
        $iCodigoLayout = self::CODIGO_LAYOUT;
        switch ($this->oConfiguracao->getVersao()) {
            case '1.4':
                $iCodigoLayout = self::CODIGO_LAYOUT_V14;
                break;
        }
        return $iCodigoLayout;
    }

    /**
     * Busca os dados da licitação do acordo.
     * @param int $iAcordo Código do acordo.
     * @param \DBDate $oDataGeracao Data da geração do arquivo.
     *
     * @return \stdClass
     */
    public function getDadosLicitacao($iAcordo, \DBDate $oDataGeracao)
    {
        $oRegraContrato = new RegraContrato($oDataGeracao);
        return $oRegraContrato->getDadosDaLicitacaoDoContrato($iAcordo);
    }

    /**
     * Calcula e aplica as regras referentes aos valores e percentuais de acrescimo e redução.
     * @param \stdClass $oPosicaoAtual
     * @param \stdClass|null $oPosicaoAnterior
     *
     * @return \stdClass
     */
    public function getValores(\stdClass $oPosicaoAtual, \stdClass $oPosicaoAnterior = null)
    {
        $oValores = new \stdClass();
        $oValores->valor_acrescimo = null;
        $oValores->valor_reducao = null;
        $oValores->percentual_acrescimo = null;
        $oValores->percentual_reducao = null;

        $nValorAcrescimo = null;
        $nValorReducao = null;
        $nPcAcrescimo = null;
        $nPcReducao = null;

        if (empty($oPosicaoAnterior)) {
            return $oValores;
        }

        $sTipoOperacao = $this->getTipoOperacao(
            $oPosicaoAtual->tipo_aditamento,
            $oPosicaoAtual->tipo_operacao
        );

        $aAditamentosAcrescimo = array(
            self::TIPO_OPERACAO_ACRESCIMO_QUANTITATIVO,
            self::TIPO_OPERACAO_ACRESCIMO_ITENS,
            self::TIPO_OPERACAO_REEQUILIBRIO,
            self::TIPO_OPERACAO_REAJUSTE,
            self::TIPO_OPERACAO_RENOVACAO
        );

        $aAditamentosPc = array(
            self::TIPO_OPERACAO_REAJUSTE,
            self::TIPO_OPERACAO_RENOVACAO
        );

        $aAditamentosReducao = array(
            self::TIPO_OPERACAO_REEQUILIBRIO,
            self::TIPO_OPERACAO_REDUCAO_ITENS,
            self::TIPO_OPERACAO_REDUCAO_QUANTITATIVO,
            self::TIPO_OPERACAO_REAJUSTE,
            self::TIPO_OPERACAO_RENOVACAO
        );

        $lSemAcrescimoValor = $oPosicaoAtual->valores->valor_total == $oPosicaoAnterior->valores->valor_total;

        if (in_array($sTipoOperacao, $aAditamentosAcrescimo)) {
            $nValorAcrescimo = $oPosicaoAtual->valores->valor_total - $oPosicaoAnterior->valores->valor_total;

            if ($sTipoOperacao === self::TIPO_OPERACAO_RENOVACAO) {
                /**
                 * No caso de renovação devemos mandar o valor original mais a diferença.
                 */
                $nValorAcrescimo = $oPosicaoAnterior->valores->valor_total + ($oPosicaoAtual->valores->valor_total - $oPosicaoAnterior->valores->valor_total);
            }

            if (in_array($sTipoOperacao, $aAditamentosPc)) {
                $nPorcentual = (($oPosicaoAtual->valores->valor_total * 100) / $oPosicaoAnterior->valores->valor_total) - 100;
                $nPcAcrescimo = round($nPorcentual, 2);
            }
        }

        /**
         * Descobre a diferença e o percentual de redução
         */
        if (in_array($sTipoOperacao, $aAditamentosReducao)) {
            $nValorReducao = ($oPosicaoAnterior->valores->valor_total - $oPosicaoAtual->valores->valor_total);

            if (in_array($sTipoOperacao, $aAditamentosPc)) {
                $nPorcentual = (($nValorReducao * 100) / $oPosicaoAnterior->valores->valor_total);
                $nPcReducao = round($nPorcentual, 2);
            }
        }

        $nVersao = $this->oConfiguracao->getVersao();
        if ($nVersao == 1.2 || $sTipoOperacao != self::TIPO_OPERACAO_RENOVACAO) {
            if (!empty($nValorAcrescimo) && !empty($nPcAcrescimo)) {
                $nValorReducao = $nPcReducao = null;
            }
        }

        /**
         * Para Renovação o percentual é opcional, porém o valor NÃO deve ser informado.
         */
        if ($sTipoOperacao == self::TIPO_OPERACAO_RENOVACAO) {
            $nValorReducao = null;
        }

        /**
         * Ignora valores negativos ou zerados.
         */
        $oValores->valor_acrescimo = $nValorAcrescimo < 0 || $nValorAcrescimo == 0 ? null : $nValorAcrescimo;
        $oValores->valor_reducao = $nValorReducao < 0 || $nValorReducao == 0 ? null : $nValorReducao;
        $oValores->percentual_acrescimo = $nPcAcrescimo < 0 || $nPcAcrescimo == 0 ? null : $nPcAcrescimo;
        $oValores->percentual_reducao = $nPcReducao < 0 || $nPcReducao == 0 ? null : $nPcReducao;

        /**
         * Caso seja renovação, mas não houve mudança de valores, então mandamos o percentual zerado.
         */
        if ($sTipoOperacao == self::TIPO_OPERACAO_RENOVACAO && $lSemAcrescimoValor) {
            $oValores->percentual_acrescimo = 0;
        }

        return $oValores;
    }

    /**
     * Retorna o valor formatado para impressão no arquivo
     * @param $nValor
     * @return string
     */
    public static function formatarValor($nValor)
    {
        return $nValor === null ? null : number_format($nValor, 2, ',', '');
    }

    /**
     * @param \AcordoPosicao|null $oAcordoPosicao
     * @param bool $lTemAnterior
     * @param \stdClass|null $oStdAcordo
     *
     * @return int|mixed
     */
    public function getDiasPrazo(\AcordoPosicao $oAcordoPosicao = null, $lTemAnterior, \stdClass $oStdAcordo = null)
    {
        $iDiasPrazo = null;
        $oAcordoPosicao->getTipo();

        $sTipoOperacao = $this->getTipoOperacao(
            $oAcordoPosicao->getTipo(),
            $oAcordoPosicao->getTipoOperacao()
        );

        $aPrazo = array(self::TIPO_OPERACAO_RENOVACAO, self::TIPO_OPERACAO_PRORROGACAO);
        /**
         * Descobre a diferença de prazo
         */
        if (in_array($sTipoOperacao, $aPrazo)) {
            $dtInicial = new \DBDate($oAcordoPosicao->getVigenciaInicial());
            $dtFinal = new \DBDate($oAcordoPosicao->getVigenciaFinal());
            $iDiasPrazo = \DBDate::getIntervaloEntreDatas($dtInicial, $dtFinal)->days;
        }

        return $iDiasPrazo;
    }

    /**
     * @param \AcordoPosicao $oAcordoPosicao
     * @return \stdClass
     * @throws \BusinessException
     */
    public function getValoresAtualizadosAtePosicao(\AcordoPosicao $oAcordoPosicao)
    {
        $oDaoAcordoitem = new \cl_acordoitem();

        $iNumeroAditamento = $oAcordoPosicao->getNumero();

        $sCampos = "max(ac26_numero) as numero_aditamento";
        $sWhere = "ac16_sequencial = {$oAcordoPosicao->getAcordo()} and ac26_numero <= {$iNumeroAditamento}";
        $sWhere .= " and ac26_acordoposicaotipo in (" . \AcordoPosicao::TIPO_INCLUSAO . ", " . \AcordoPosicao::TIPO_RENOVACAO . ")";
        $sSqlItens = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem = $oDaoAcordoitem->sql_record($sSqlItens);

        if ($oDaoAcordoitem->numrows > 0) {
            $iNumeroAditamento = \db_utils::fieldsMemory($rsItem, 0)->numero_aditamento;
        }

        $sCampos = "coalesce(sum(case when ac26_acordoposicaotipo <> " . \AcordoPosicao::TIPO_REEQUILIBRIO . " then ac20_quantidade else 0 end ), 0) as quantidade,";
        $sCampos .= "coalesce(sum(ac20_valortotal),0) as valor_total";
        $sWhere = "     ac16_sequencial  = {$oAcordoPosicao->getAcordo()} ";
        $sWhere .= " and ac26_sequencial <= {$oAcordoPosicao->getCodigo()} ";
        $sWhere .= " and ac26_numero >= {$iNumeroAditamento} ";

        $sSqlItens = $oDaoAcordoitem->sql_query_transparencia("distinct " . $sCampos, null, $sWhere);
        $rsItem = db_query($sSqlItens);

        if (!$rsItem) {
            throw new \BusinessException("Ocorreu um erro ao buscar os dados da posição.");
        }

        return \db_utils::fieldsMemory($rsItem, 0);
    }

    /**
     * Retorna o caracter referente ao tipo de instrumento selecionado para o contrato
     * @param $iCodigoTipoInstrumento
     * @return string
     */
    public function getTipoInstrumento($iCodigoTipoInstrumento)
    {
        $sTipoInstrumento = '';
        switch ($iCodigoTipoInstrumento) {
            case \Acordo::TIPO_INSTRUMENTO_CONTRATO:
                $sTipoInstrumento = 'C';
                break;
            case \Acordo::TIPO_INSTRUMENTO_TERMO_ADESAO:
                $sTipoInstrumento = 'A';
                break;
            case \Acordo::TIPO_INSTRUMENTO_TERMO_FOMENTO:
                $sTipoInstrumento = 'F';
                break;
            case \Acordo::TIPO_INSTRUMENTO_TERMO_PARCERIA:
                $sTipoInstrumento = 'P';
                break;
            case \Acordo::TIPO_INSTRUMENTO_TERMO_CREDENCIAMENTO:
                $sTipoInstrumento = 'R';
                break;
            case \Acordo::TIPO_INSTRUMENTO_TERMO_COLABORACAO:
                $sTipoInstrumento = 'T';
                break;
        }
        return $sTipoInstrumento;
    }

    /**
     * Retorna o tipo de operação de acordo com o informado no aditamento
     * @param int $iTipoAditamento Tipo de aditamento.
     * @param int $iTipoOperacao Tipo de operação.
     *
     * @return mixed|string
     */
    public function getTipoOperacao($iTipoAditamento, $iTipoOperacao)
    {
        $aTipoOperacao = array(
            1 => self::TIPO_OPERACAO_ACRESCIMO_QUANTITATIVO,
            2 => self::TIPO_OPERACAO_ACRESCIMO_ITENS,
            3 => self::TIPO_OPERACAO_REAJUSTE,
            4 => self::TIPO_OPERACAO_REDUCAO_ITENS,
            5 => self::TIPO_OPERACAO_REDUCAO_QUANTITATIVO,
        );
        $sTipoOperacao = '';
        switch ($iTipoAditamento) {
            case \AcordoPosicao::TIPO_ADITAMENTO:
            case \AcordoPosicao::TIPO_SUPRESSAO:
                $sTipoOperacao = $aTipoOperacao[$iTipoOperacao];
                break;

            case \AcordoPosicao::TIPO_REEQUILIBRIO:
                $sTipoOperacao = self::TIPO_OPERACAO_REEQUILIBRIO;
                break;

            case \AcordoPosicao::TIPO_ALTERACAO_DOTACAO:
                $sTipoOperacao = self::TIPO_OPERACAO_ALTERACAO_DOTACAO;
                break;

            case \AcordoPosicao::TIPO_VIGENCIA:
                $sTipoOperacao = self::TIPO_OPERACAO_PRORROGACAO;
                break;

            case \AcordoPosicao::TIPO_RENOVACAO:
                $sTipoOperacao = self::TIPO_OPERACAO_RENOVACAO;
                break;
        }
        return $sTipoOperacao;
    }
}
