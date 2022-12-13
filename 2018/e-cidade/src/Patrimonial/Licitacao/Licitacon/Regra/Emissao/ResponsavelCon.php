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

class ResponsavelCon extends BaseAbstract
{

    const CODIGO_LAYOUT_V12 = 249;
    const CODIGO_LAYOUT_V14 = 294;

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
     * Busca o tipo e documento do fornecedor contratado.
     * @param int $iCgm Código do cgm.
     *
     * @return \stdClass
     */
    public function getContratado($iCgm)
    {
        $oContratado = new \stdClass();
        $oContratado->tipo = \LicitanteLicitaCon::getTipoDocumentoPorCGM($iCgm);
        $oContratado->documento = \LicitanteLicitaCon::getDocumentoPorCGM($iCgm);

        return $oContratado;
    }

    /**
     * Aplica as regras referentes a data de vigência.
     *
     * @param int $iCodigoAcordo Código do acordo.
     * @param string $sDataInicio Data de início da vigência.
     * @param string $sDataFim Data de fim da vigência.
     * @param string $sDependeOrdemInicio Se depende da ordem de início.
     * @param int $iDiasPrazo Dias prazo.
     *
     * @return \stdClass
     */
    public function getDataVigencia($iCodigoAcordo, $sDataInicio, $sDataFim, $sDependeOrdemInicio, $iDiasPrazo)
    {
        $oDataVigencia = new \stdClass();
        $oDataVigencia->inicio = db_formatar($sDataInicio, 'd');
        $oDataVigencia->fim = db_formatar($sDataFim, 'd');

        /**
         * Verifica se o acordo depende da orgem de inicio
         */
        if ($sDependeOrdemInicio == 't') {
            $sDataInicioVirgencia = $this->getDataInicioVigencia($iCodigoAcordo);
            $oDataVigencia->inicio = $sDataInicioVirgencia;
            $oDataVigencia->fim = null;


            if ($sDataInicioVirgencia) {
                $oDataFinal = new \DBDate($sDataInicioVirgencia);
                $oDataFinal->modificarIntervalo("+{$iDiasPrazo} days ");
                $oDataVigencia->fim = $oDataFinal->getDate(\DBDate::DATA_PTBR);
            }
        }

        return $oDataVigencia;
    }

    /**
     * Busca possível justificativa de troca de fornecedor.
     * @param int $iSequencialAcordo
     * @return null|string
     * @throws \DBException
     */
    public function getJustificativaTrocaFornecedor($iSequencialAcordo)
    {
        $oDaoAcordo = new \cl_acordo;
        $sWhere = "ac16_sequencial = {$iSequencialAcordo}";
        $sSql = $oDaoAcordo->sql_query_troca_fornecedor("pc25_motivo", $sWhere, 'pc25_codtroca desc', 1);
        $rsTrocas = db_query($sSql);

        if (!$rsTrocas) {
            throw new \DBException("Não foi possível encontrar informações da troca de fornecedores.");
        }

        if (pg_num_rows($rsTrocas) == 0) {
            return null;
        }

        $sJustificativa = \db_utils::fieldsMemory($rsTrocas, 0)->pc25_motivo;
        $sJustificativa = str_replace("|", " ", $sJustificativa);

        return $sJustificativa;
    }

    /**
     * Busca a data de início da vigência.
     * @param $sCodigoAcordo
     * @return string
     * @throws \DBException
     */
    private function getDataInicioVigencia($sCodigoAcordo)
    {
        $oDaoEventoAcordo = new \cl_acordoevento();
        $sWhere = implode(' and ', array(
            "ac55_tipoevento = 5",
            "ac55_acordo = {$sCodigoAcordo}"
        ));
        $sSql = $oDaoEventoAcordo->sql_query_file(null, 'ac55_acordo, ac55_tipoevento, ac55_data', null, $sWhere);
        $rsEvento = db_query($sSql);

        if (!$rsEvento) {
            throw new \DBException("Não foi possível obter as informações de evento para o contrato.");
        }

        $sData = \db_utils::fieldsMemory($rsEvento, 0)->ac55_data ?: null;

        if ($sData) {
            $oData = new \DBDate($sData);
            $sData = $oData->getDate(\DBDate::DATA_PTBR);
        }

        return $sData;
    }

    /**
     * Busca as informações do contrato.
     * @param \DBDate $oDataGeracao Data de geração do arquivo.
     * @param \Instituicao $oInstituicao Instituição para a qual o arquivo está sendo gerado.
     *
     * @return bool|resource
     * @throws \DBException
     */
    public function getDadosContrato(\DBDate $oDataGeracao, \Instituicao $oInstituicao)
    {
        $oDaoAcordo = new \cl_acordo;

        $sDataAtual = $oDataGeracao->getDate();
        $sCampos = " distinct ac16_sequencial, ac16_numero, ac16_anousu, ac16_dataassinatura, z01_cgccpf, z01_numcgm, ";
        $sCampos .= "ac16_tipoinstrumento, ac16_dependeordeminicio, ac16_numeroprocesso, ";
        $sCampos .= "ac16_datainicio, ac16_datafim, ac16_valor, ";
        $sCampos .= "exists(select 1 from acordoacordogarantia where ac12_acordo = ac16_sequencial) as garantias,";
        $sCampos .= "(ac18_datafim - ac18_datainicio) as nr_dias_prazo, ac18_datainicio as datainiciooriginal,";
        $sCampos .= "ac16_objeto";
        $sWhere = implode(' and ', array(
            "(ac58_acordo is null or ac58_data >= '{$sDataAtual}')",
            "ac16_instit = {$oInstituicao->getCodigo()}"
        ));
        $sOrderBy = "ac16_sequencial";
        $sSqlContratos = $oDaoAcordo->sql_query_licitacon($sCampos, $sWhere, $sOrderBy);
        $rsContratos = db_query($sSqlContratos);
        if (!$rsContratos) {
            throw  new \DBException("Erro ao consultar dados dos contratos");
        }
        return $rsContratos;
    }

    /**
     * Busca os dados da licitação do contrato.
     * @param int $iContrato Código do contrato.
     * @return \stdClass
     */
    public function getDadosDaLicitacaoDoContrato($iContrato)
    {
        $oDados = new \stdClass();
        $oDados->numero = '';
        $oDados->tipo = '';
        $oDados->ano = '';

        $oDaoAcordoItem = new \cl_acordo();
        $sCampos = "case when l20_codigo is null then null else  l20_codigo end as codigo,";
        $sCampos .= "case when l20_numero is null then ac54_numerolicitacao else  l20_numero::varchar end as numero,";
        $sCampos .= "case when l20_anousu is null then ac54_ano else l20_anousu end as ano,";
        $sCampos .= "case when trib_licitacao.l44_sigla is null then trib_empenho.l44_sigla else  trib_licitacao.l44_sigla end as tipo,";
        $sCampos .= "case when l20_tipo = 2 then 'N' else 'S' end as gera_despesa";
        $sSqlAcordo = $oDaoAcordoItem->sql_query_numero_licitacao($sCampos, null,
            "ac16_sequencial = {$iContrato} limit 1");
        $rsDadosLicitacao = db_query($sSqlAcordo);

        if ($rsDadosLicitacao && pg_num_rows($rsDadosLicitacao) > 0) {
            $oDados = \db_utils::fieldsMemory($rsDadosLicitacao, 0);
            $oDados->numero = (int)$oDados->numero;
        }

        if (empty($oDados->numero)) {
            $sCampos = "l20_codigo as codigo,";
            $sCampos .= "l20_numero as numero,";
            $sCampos .= "l20_anousu as ano,";
            $sCampos .= "l44_sigla as tipo,";
            $sCampos .= "case when l20_tipo = 2 then 'N' else 'S' end as gera_despesa";

            $sSqlLicitacao = $oDaoAcordoItem->sql_query_numero_licitacao_processo_compras($sCampos, null,
                "ac16_sequencial = {$iContrato} limit 1");
            $rsLicitacao = db_query($sSqlLicitacao);

            if ($rsLicitacao && pg_num_rows($rsLicitacao)) {
                $oDados = \db_utils::fieldsMemory($rsLicitacao, 0);
            }
        }


        return $oDados;
    }
}
