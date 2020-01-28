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

use _db_fields;
use cl_acordo;
use cl_acordoevento;
use db_utils;
use DBDate;
use DBException;
use Exception;
use Instituicao;
use LicitanteLicitaCon;
use stdClass;

/**
 * Class Contrato
 * @package ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao
 */
class Contrato extends BaseAbstract
{

    /**
     * @var int
     */
    const CODIGO_LAYOUT_V12 = 253;
    /**
     * @var int
     */
    const CODIGO_LAYOUT_V13 = 275;

    /**
     * @return int
     * @throws Exception
     */
    public function getCodigoLayout()
    {
        switch ($this->oConfiguracao->getVersao()) {
            case '1.2':
                $codigoLayout = self::CODIGO_LAYOUT_V12;
                break;
            case '1.3':
            case '1.4':
                $codigoLayout = self::CODIGO_LAYOUT_V13;
                break;
            default:
                throw new Exception('Versão do layout inválido.');
        }
        return $codigoLayout;
    }

    /**
     * @param $iCgm
     * @return stdClass
     */
    public function getContratado($iCgm)
    {
        $oContratado = new stdClass;
        $oContratado->tipo = LicitanteLicitaCon::getTipoDocumentoPorCGM($iCgm);
        $oContratado->documento = LicitanteLicitaCon::getDocumentoPorCGM($iCgm);

        return $oContratado;
    }

    /**
     * @param $iCodigoAcordo
     * @param $sDataInicio
     * @param $sDataFim
     * @param $sDependeOrdemInicio
     * @param $iDiasPrazo
     * @return stdClass
     */
    public function getDataVigencia($iCodigoAcordo, $sDataInicio, $sDataFim, $sDependeOrdemInicio, $iDiasPrazo)
    {
        $oDataVigencia = new stdClass;
        $oDataVigencia->inicio = db_formatar($sDataInicio, 'd');
        $oDataVigencia->fim = db_formatar($sDataFim, 'd');

        if ($sDependeOrdemInicio == 't') {
            $sDataInicioVirgencia = $this->getDataInicioVigencia($iCodigoAcordo);
            $oDataVigencia->inicio = $sDataInicioVirgencia;
            $oDataVigencia->fim = null;

            if ($sDataInicioVirgencia) {
                $oDataFinal = new DBDate($sDataInicioVirgencia);
                $oDataFinal->modificarIntervalo('+' . $iDiasPrazo . ' days ');
                $oDataVigencia->fim = $oDataFinal->getDate(DBDate::DATA_PTBR);
            }
        }

        return $oDataVigencia;
    }

    /**
     * @param $iSequencialAcordo
     * @return mixed|null
     * @throws DBException
     */
    public function getJustificativaTrocaFornecedor($iSequencialAcordo)
    {
        $oDaoAcordo = new cl_acordo;
        $sWhere = 'ac16_sequencial = ' . $iSequencialAcordo;
        $sSql = $oDaoAcordo->sql_query_troca_fornecedor('pc25_motivo', $sWhere, 'pc25_codtroca DESC', 1);
        $rsTrocas = db_query($sSql);

        if (!$rsTrocas) {
            throw new DBException('Não foi possível encontrar informações da troca de fornecedores.');
        }

        if (pg_num_rows($rsTrocas) == 0) {
            return null;
        }

        $sJustificativa = db_utils::fieldsMemory($rsTrocas, 0)->pc25_motivo;
        $sJustificativa = str_replace('|', ' ', $sJustificativa);

        return $sJustificativa;
    }

    /**
     * @param $sCodigoAcordo
     * @return null|string
     * @throws DBException
     */
    private function getDataInicioVigencia($sCodigoAcordo)
    {
        $oDaoEventoAcordo = new cl_acordoevento;
        $sWhere = implode(' AND ', array(
            'ac55_tipoevento = 5',
            'ac55_acordo = ' . $sCodigoAcordo
        ));
        $sSql = $oDaoEventoAcordo->sql_query_file(null, 'ac55_acordo, ac55_tipoevento, ac55_data', null, $sWhere);
        $rsEvento = db_query($sSql);

        if (!$rsEvento) {
            throw new DBException('Não foi possível obter as informações de evento para o contrato.');
        }

        $sData = db_utils::fieldsMemory($rsEvento, 0)->ac55_data ?: null;

        if ($sData) {
            $oData = new DBDate($sData);
            $sData = $oData->getDate(DBDate::DATA_PTBR);
        }

        return $sData;
    }

    /**
     * @param DBDate $oDataGeracao
     * @param Instituicao $oInstituicao
     * @return bool|resource
     * @throws DBException
     */
    public function getDadosContrato(DBDate $oDataGeracao, Instituicao $oInstituicao)
    {
        $sDataAtual = $oDataGeracao->getDate();
        $sCampos = '
            DISTINCT 
                ac16_sequencial, 
                ac16_numero, 
                ac16_anousu, 
                ac16_dataassinatura, 
                z01_cgccpf, 
                z01_numcgm, 
                ac16_tipoinstrumento, 
                ac16_dependeordeminicio, 
                ac16_numeroprocesso, 
                ac16_datainicio, 
                ac16_datafim, 
                ac16_valor, 
                EXISTS(SELECT 1 FROM acordoacordogarantia WHERE ac12_acordo = ac16_sequencial) AS garantias,
                (ac18_datafim - ac18_datainicio) AS nr_dias_prazo, 
                ac18_datainicio AS datainiciooriginal,
                ac16_objeto
        ';
        $sWhere = implode(' AND ', array(
            "(ac58_acordo IS NULL OR ac58_data >= '{$sDataAtual}')",
            'ac16_instit = ' . $oInstituicao->getCodigo()
        ));
        $sOrderBy = 'ac16_sequencial';

        $oDaoAcordo = new cl_acordo;
        $sSqlContratos = $oDaoAcordo->sql_query_licitacon($sCampos, $sWhere, $sOrderBy);
        $rsContratos = db_query($sSqlContratos);

        if (!$rsContratos) {
            throw new DBException('Erro ao consultar dados dos contratos.');
        }

        return $rsContratos;
    }

    /**
     * @param $iContrato
     * @return _db_fields|stdClass
     */
    public function getDadosDaLicitacaoDoContrato($iContrato)
    {
        $sCampos = "
            CASE 
                WHEN l20_codigo IS NOT NULL 
                THEN l20_codigo 
            END AS codigo,
            CASE 
                WHEN l20_numero IS NULL 
                THEN ac54_numerolicitacao 
                ELSE l20_numero::VARCHAR 
            END AS numero,
            CASE 
                WHEN l20_anousu IS NULL 
                THEN ac54_ano 
                ELSE l20_anousu 
            END AS ano,
            CASE 
                WHEN trib_licitacao.l44_sigla IS NULL 
                THEN trib_empenho.l44_sigla 
                ELSE trib_licitacao.l44_sigla 
            END AS tipo,
            CASE 
                WHEN l20_tipo = 2 
                THEN 'N' ELSE 'S' 
            END AS gera_despesa
        ";
        $oDaoAcordoItem = new cl_acordo;
        $sSqlAcordo = $oDaoAcordoItem->sql_query_numero_licitacao($sCampos, null,
            'ac16_sequencial = ' . $iContrato . ' LIMIT 1');
        $rsDadosLicitacao = db_query($sSqlAcordo);

        $oDados = new stdClass;
        $oDados->numero = '';
        $oDados->tipo = '';
        $oDados->ano = '';

        if ($rsDadosLicitacao && pg_num_rows($rsDadosLicitacao)) {
            $oDados = db_utils::fieldsMemory($rsDadosLicitacao, 0);
            $oDados->numero = (int)$oDados->numero;
        }

        if (empty($oDados->numero)) {
            $sCampos = "
                l20_codigo AS codigo,
                l20_numero AS numero,
                l20_anousu AS ano,
                l44_sigla AS tipo,
                CASE 
                    WHEN l20_tipo = 2 
                    THEN 'N' 
                    ELSE 'S' 
                END AS gera_despesa
            ";

            $sSqlLicitacao = $oDaoAcordoItem->sql_query_numero_licitacao_processo_compras($sCampos, null,
                'ac16_sequencial = ' . $iContrato . ' LIMIT 1');
            $rsLicitacao = db_query($sSqlLicitacao);

            if ($rsLicitacao && pg_num_rows($rsLicitacao)) {
                $oDados = db_utils::fieldsMemory($rsLicitacao, 0);
            }
        }

        return $oDados;
    }
}
