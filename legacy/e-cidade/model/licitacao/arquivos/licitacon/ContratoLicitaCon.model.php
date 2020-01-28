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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato as Regra;

/**
 * Class ContratoLicitaCon
 */
class ContratoLicitaCon extends ArquivoLicitaCon
{

    /**
     * @var string
     */
    const NOME_ARQUIVO = "CONTRATO";

    /**
     * @var array
     */
    protected $aRemoveQuebraLinhas = array("DS_OBJETO");

    /**
     * ContratoLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return array
     * @throws DBException
     */
    public function getDados()
    {
        $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();

        $rsContratos = $this->oRegra->getDadosContrato($this->oCabecalho->getDataGeracao(),
            $this->oCabecalho->getInstituicao());
        $iTotalContratos = pg_num_rows($rsContratos);
        $aContratos = array();
        for ($iContrato = 0; $iContrato < $iTotalContratos; $iContrato++) {
            $oDadosContrato = db_utils::fieldsMemory($rsContratos, $iContrato);
            $oContrato = $this->obterNullObject();

            $aNumeroContrato = explode("/", $oDadosContrato->ac16_numeroprocesso);
            $iNumeroProcesso = $aNumeroContrato[0];
            $iAnoProcesso = $oDadosContrato->ac16_anousu;
            if (isset($aNumeroContrato[1])) {
                $iAnoProcesso = $aNumeroContrato[1];
            }
            $oDadosLicitacao = $this->oRegra->getDadosDaLicitacaoDoContrato($oDadosContrato->ac16_sequencial);
            $oDataVigencia = $this->oRegra->getDataVigencia(
                $oDadosContrato->ac16_sequencial,
                $oDadosContrato->ac16_datainicio,
                $oDadosContrato->ac16_datafim,
                $oDadosContrato->ac16_dependeordeminicio,
                $oDadosContrato->nr_dias_prazo
            );
            $oContratado = $this->oRegra->getContratado($oDadosContrato->z01_numcgm);
            if ($oDataVigencia->inicio) {
                //Busca a ultima posição
                $oAcordoPosicao = new \cl_acordoposicao();
                $sCampos = " ac26_sequencial, ac26_acordoposicaotipo, ac26_acordo";
                $sWhere = " ac26_acordo = {$oDadosContrato->ac16_sequencial} ";
                $sWhere .= " and ac26_sequencial = (Select MAX(acp.ac26_sequencial) as sequencial
                                               from acordoposicao acp
                                              where acp.ac26_acordo = acordoposicao.ac26_acordo)";
                $sSqlAcordoPosicao = $oAcordoPosicao->sql_query_file(null, $sCampos, null, $sWhere);
                $rsAcordoPosicao = db_query($sSqlAcordoPosicao);

                if (!$rsAcordoPosicao) {
                    throw  new DBException("Erro ao consultar Acordo {$oDadosContrato->ac16_sequencial}");
                }

                $oDados = db_utils::fieldsMemory($rsAcordoPosicao, 0);

                //Aditamento de Renovação
                if ($oDados->ac26_acordoposicaotipo == 5) {
                    $oDataVigencia->inicio = db_formatar($oDadosContrato->datainiciooriginal, "d");

                    //Verifica se há ordem de início de contrato
                    if ($oDadosContrato->ac16_dependeordeminicio == 't') {
                        $oDataVigencia = $this->oRegra->getDataVigencia(
                            $oDadosContrato->ac16_sequencial,
                            $oDadosContrato->datainiciooriginal,
                            $oDadosContrato->ac16_datafim,
                            $oDadosContrato->ac16_dependeordeminicio,
                            $oDadosContrato->nr_dias_prazo
                        );

                        $oDataVigencia->fim = db_formatar($oDadosContrato->ac16_datafim, "d");
                    }

                    ////verifica se houve aditamento de prazo antes do aditamento de renovação
                    $rsAditamentoPrazo = $this->verificaAditamentoPrazo($oDadosContrato);

                    //se houve aditamento de prazo, é necessario alterar a data de inicio
                    if (pg_num_rows($rsAditamentoPrazo) > 0) {
                        $oAditamento = db_utils::fieldsMemory($rsAditamentoPrazo, 0);
                        if ($oAditamento->ac26_sequencial < $oDados->ac26_sequencial) {
                            $oDataVigencia->inicio = db_formatar($oAditamento->ac18_datainicio, "d");
                        }
                    }
                }

                //Verifica numero de dias com evento de suspensao
                $this->getDiasEventos($oDadosContrato->ac16_sequencial, $oDataVigencia);
            }

            $oContrato->NR_LICITACAO = $oDadosLicitacao->numero;
            $oContrato->ANO_LICITACAO = $oDadosLicitacao->ano;
            $oContrato->CD_TIPO_MODALIDADE = $oDadosLicitacao->tipo;
            $oContrato->NR_CONTRATO = $oDadosContrato->ac16_numero;
            $oContrato->ANO_CONTRATO = $oDadosContrato->ac16_anousu;
            $oContrato->TP_INSTRUMENTO = $aTiposInstrumento[$oDadosContrato->ac16_tipoinstrumento];
            $oContrato->NR_PROCESSO = $iNumeroProcesso;
            $oContrato->ANO_PROCESSO = $iAnoProcesso;
            $oContrato->TP_DOCUMENTO_CONTRATADO = $oContratado->tipo;
            $oContrato->NR_DOCUMENTO_CONTRATADO = $oContratado->documento;
            $oContrato->DT_INICIO_VIGENCIA = $oDataVigencia->inicio;
            $oContrato->DT_FINAL_VIGENCIA = $oDataVigencia->fim;
            $oContrato->VL_CONTRATO = number_format($oDadosContrato->ac16_valor, 2, ",", "");
            $oContrato->DT_ASSINATURA = db_formatar($oDadosContrato->ac16_dataassinatura, 'd');
            $oContrato->BL_GARANTIA = $oDadosContrato->garantias == 't' ? 'S' : 'N';
            $oContrato->NR_DIAS_PRAZO = $oDadosContrato->nr_dias_prazo + 1;
            $oContrato->NR_CONTRATO_ORIGINAL = '';
            $oContrato->BL_INICIO_DEPENDE_OI = $oDadosContrato->ac16_dependeordeminicio == 't' ? 'S' : 'N';
            $oContrato->CNPJ_ORGAO_GERENCIADOR = null;
            $oContrato->BL_GERA_DESPESA = $oDadosLicitacao->gera_despesa;
            $oContrato->DS_OBSERVACAO = null;
            $oContrato->DS_JUSTIFICATIVA = $this->oRegra->getJustificativaTrocaFornecedor($oDadosContrato->ac16_sequencial);
            $oContrato->DS_OBJETO = substr($oDadosContrato->ac16_objeto, 0, 500);
            $oContrato->CNPJ_CONSORCIO = null;

            $aContratos[] = $oContrato;
        }
        return $aContratos;
    }

    /**
     * Calcula numero de dias dos eventos para somar ao prazo
     * Somente os eventos do tipo:
     *         7  => "Retorno dos efeitos do contrato",
     *         9  => "Suspensão por cautelar",
     *         10 => "Suspensão por determinação judicial",
     *         11 => "Suspensão de ofício"
     *
     * @param integer $iContrato
     * @param stdClass $oDataVigencia
     *
     * @throws DBException
     */
    public function getDiasEventos($iContrato, $oDataVigencia)
    {
        $oDaoEventos = new \cl_acordoevento();
        $sWhere = "ac55_acordo = {$iContrato} and ac55_tipoevento in (7,9,10,11)";
        $sSqlEventos = $oDaoEventos->sql_query_file(null, 'ac55_acordo, ac55_tipoevento, ac55_data', "ac55_data",
            $sWhere);

        $rsEvento = db_query($sSqlEventos);
        $iQuantidadeDiasEvento = 0;

        $dataFinalEvento = null;
        $dataInicialEvento = null;
        $iTimeInicio = 0;
        $iTimeFim = 0;

        $oDataInicioVigencia = new \DBDate($oDataVigencia->inicio);
        $oDataInicioVigencia = $oDataInicioVigencia->getDate(\DBDate::DATA_EN);

        if (!$rsEvento) {
            throw new DBException("Erro ao consultar eventos do contrato {$iContrato}");
        }

        if (pg_num_rows($rsEvento) > 0) {
            $iTotalEventos = pg_num_rows($rsEvento);
            for ($i = 0; $i < $iTotalEventos; $i++) {
                $oEvento = db_utils::fieldsMemory($rsEvento, $i);
                if ($oEvento->ac55_tipoevento == 9 || $oEvento->ac55_tipoevento == 10 || $oEvento->ac55_tipoevento == 11) {
                    //verifica se a data do evento e maior que a data do inicio de vigencia
                    if ($oDataInicioVigencia <= $oEvento->ac55_data) {
                        $oDataEventoInicio = new DBDate($oEvento->ac55_data);
                        $iTimeInicio = $oDataEventoInicio->getTimeStamp();
                        $dataInicialEvento = $oEvento->ac55_data;
                    }
                }

                if ($oEvento->ac55_tipoevento == 7) {
                    $oDataEventoInicio = new DBDate($oEvento->ac55_data);
                    $iTimeFim = $oDataEventoInicio->getTimeStamp();
                    $dataFinalEvento = $oEvento->ac55_data;
                }

                // Calcula a diferença de dias, somente quando a data fim for maior que a de inicio e ambas devem exister.
                if ($dataFinalEvento && $dataInicialEvento && ($dataFinalEvento > $dataInicialEvento)) {
                    $iDiferenca = $iTimeFim - $iTimeInicio;
                    $iQuantidadeDiasEvento += (int)floor($iDiferenca / (60 * 60 * 24));
                    $dataFinalEvento = null;
                    $dataInicialEvento = null;
                }
            }
        }

        $oDataFinal = new \DBDate($oDataVigencia->fim);
        $oDataFinal->modificarIntervalo("+{$iQuantidadeDiasEvento} days ");
        $oDataVigencia->fim = $oDataFinal->getDate(\DBDate::DATA_PTBR);
    }

    /**
     * Se houve aditamentos de prazo e for acordo com ordem de inicio,
     * deve considerar as datas de aditamento de prazo.
     *
     * @param $oDadosContrato
     * @return bool|resource
     * @throws DBException
     */
    public function verificaAditamentoPrazo($oDadosContrato)
    {
        $oAcordoPosicao = new \cl_acordoposicao();
        $sCampos = " ac26_sequencial, ac26_acordoposicaotipo, ac26_acordo, ac18_datainicio";
        $sWhere = " ac26_acordo = {$oDadosContrato->ac16_sequencial} ";
        $sWhere .= " and ac26_acordoposicaotipo = 6 ";
        $sWhere .= " and ac26_sequencial = (Select MAX(acp.ac26_sequencial) as sequencial
                                         from acordoposicao acp
                                        where acp.ac26_acordo = acordoposicao.ac26_acordo
                                          and acp.ac26_acordoposicaotipo = acordoposicao.ac26_acordoposicaotipo)";
        $sSqlAditamentoPrazo = $oAcordoPosicao->sql_query_vigencia(null, $sCampos, null, $sWhere);
        $rsAditamentoPrazo = db_query($sSqlAditamentoPrazo);

        if (!$rsAditamentoPrazo) {
            throw new DBException("Erro ao consultar Acordo {$oDadosContrato->ac16_sequencial}");
        }

        return $rsAditamentoPrazo;
    }

    /**
     * @return stdClass
     */
    private function obterNullObject()
    {
        $contrato = new stdClass;
        $contrato->NR_LICITACAO = null;
        $contrato->ANO_LICITACAO = null;
        $contrato->CD_TIPO_MODALIDADE = null;
        $contrato->NR_CONTRATO = null;
        $contrato->ANO_CONTRATO = null;
        $contrato->TP_INSTRUMENTO = null;
        $contrato->NR_PROCESSO = null;
        $contrato->ANO_PROCESSO = null;
        $contrato->TP_DOCUMENTO_CONTRATADO = null;
        $contrato->NR_DOCUMENTO_CONTRATADO = null;
        $contrato->DT_INICIO_VIGENCIA = null;
        $contrato->DT_FINAL_VIGENCIA = null;
        $contrato->VL_CONTRATO = null;
        $contrato->DT_ASSINATURA = null;
        $contrato->BL_GARANTIA = null;
        $contrato->NR_DIAS_PRAZO = null;
        $contrato->DS_OBJETO = null;
        $contrato->NR_CONTRATO_ORIGINAL = null;
        $contrato->BL_INICIO_DEPENDE_OI = null;
        $contrato->DS_JUSTIFICATIVA = null;
        $contrato->CNPJ_CONSORCIO = null;
        $contrato->CNPJ_ORGAO_GERENCIADOR = null;
        $contrato->BL_GERA_DESPESA = null;
        $contrato->DS_OBSERVACAO = null;

        return $contrato;
    }

}
