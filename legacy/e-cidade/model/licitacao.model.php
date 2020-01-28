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
require_once(modification("model/licitacao/LicitacaoModalidade.model.php"));

use ECidade\Patrimonial\Licitacao\Licitacon\Julgamento;

/**
 * Class licitacao
 */
class licitacao
{

    /**
     * Constantes para os tipos de julgamento da licitação.
     */
    const TIPO_JULGAMENTO_POR_ITEM = 1;
    const TIPO_JULGAMENTO_GLOBAL = 2;
    const TIPO_JULGAMENTO_POR_LOTE = 3;

    const MODALIDADE_CHAMAMENTO_PUBLICO_CREDENCIAMENTO = 'CPC';

    /**
     * @type integer
     */
    const GERA_DESPESA = 1;

    /**
     * @type integer
     */
    const NAO_GERA_DESPESA = 2;

    /*
     * Sequencial da tabela
     */
    private $iCodLicitacao = null;
    private $aItensLicitacao = array();
    private $oDados = null;
    private $oDaoLicita = null;
    private $oDaoParametros = null;
    protected $aFornecedores = array();

    protected $iCodigoSituacao;
    protected $iNumeroEdital;
    protected $iNumeroLicitacal;
    protected $iAno;
    /**
     * objeto processoProtocolo
     * @var object
     */
    private $oProcessoProtocolo;

    /**
     * Situação da Licitação
     * @var SituacaoLicitacao
     */
    private $oSituacaoLicitacao;

    /**
     * @var integer
     */
    private $iCodigoModalidade;

    /**
     * @var EventoLicitacao[]
     */
    private $aEventos = null;

    /**
     * registro de preco da licitacao
     *
     * @var compilacaoRegistroPreco
     */
    private $oRegistroPreco = null;

    /**
     * @var LicitacaoModalidade
     */
    private $oModalidade;

    /**
     * @var integer
     */
    private $iTipoJulgamento;

    /**
     * @var integer
     */
    private $iFaseLicitacon;

    /**
     * @var bool Diz se a licitacao usa registro de preço.
     */
    private $lUsaRegistroDePreco;

    private $oDataAbertura;
    private $sHoraAbertura;


    /**
     * @param integer $iCodLicitacao Código da Licitação
     */
    public function __construct($iCodLicitacao = null)
    {
        if (!empty($iCodLicitacao)) {
            $this->iCodLicitacao = $iCodLicitacao;

            $oDaoLicitacao = new cl_liclicita;
            $sSqlBuscaLicitacao = $oDaoLicitacao->sql_query_file($iCodLicitacao);
            $rsBuscaLicitacao = $oDaoLicitacao->sql_record($sSqlBuscaLicitacao);
            if ($rsBuscaLicitacao && $oDaoLicitacao->numrows > 0) {
                $oDadoLicitacao = db_utils::fieldsMemory($rsBuscaLicitacao, 0);
                $this->iNumeroEdital = $oDadoLicitacao->l20_numero;
                $this->iCodigoSituacao = $oDadoLicitacao->l20_licsituacao;
                $this->iNumeroLicitacal = $oDadoLicitacao->l20_edital;
                $this->iAno = $oDadoLicitacao->l20_anousu;
                $this->iCodigoModalidade = $oDadoLicitacao->l20_codtipocom;
                $this->iTipoJulgamento = $oDadoLicitacao->l20_tipojulg;
                $this->lUsaRegistroDePreco = $oDadoLicitacao->l20_usaregistropreco == "t";
                $this->oDataAbertura = new DBDate($oDadoLicitacao->l20_dataaber);
                $this->sHoraAbertura = $oDadoLicitacao->l20_horaaber;
                unset($oDadoLicitacao);
            }
        }

        $this->oDaoLicita = db_utils::getDao("liclicita");
    }

    /**
     * Retorna o tipo de julgamento da licitação.
     * @return int
     */
    public function getTipoJulgamento()
    {
        return $this->iTipoJulgamento;
    }

    public function getNumeroLicitacao()
    {
        return $this->iNumeroLicitacal;
    }

    /**
     * retorna os dados do processo do protocolo vinculado a licitação
     * @return processoProtocolo object
     */
    public function getProcessoProtocolo()
    {
        $oDaoLicLicitaProc = db_utils::getDao("liclicitaproc");
        $sSqlProcesso = $oDaoLicLicitaProc->sql_query(null, "l34_protprocesso", null,
            "l34_liclicita = {$this->iCodLicitacao} ");
        $rsProcesso = $oDaoLicLicitaProc->sql_record($sSqlProcesso);
        if ($oDaoLicLicitaProc->numrows > 0) {
            $iProcessoProtocolo = db_utils::fieldsMemory($rsProcesso, 0)->l34_protprocesso;
            $oProcessoProtocolo = new processoProtocolo($iProcessoProtocolo);
            return $oProcessoProtocolo;
        }
    }

    /**
     * setamos o processo do protocolo
     * @param processoProtocolo $oProcessoProtocolo
     */
    public function setProcessoProtocolo(processoProtocolo $oProcessoProtocolo)
    {
        $this->oProcessoProtocolo = $oProcessoProtocolo;
    }

    /**
     * Seta o código da licitação
     * @param $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodLicitacao = $iCodigo;
    }

    /**
     * Retorna o código sequencial da licitação
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodLicitacao;
    }

    /**
     * Retorna o ano da licitação
     * @return integer
     */
    public function getAno()
    {
        return $this->iAno;
    }

    /**
     * traz os Processos de compra VInculadas a licitacao.
     * @return array
     */

    public function getProcessoCompras()
    {
        $aSolicitacoes = array();
        if ($this->iCodLicitacao == null) {
            throw new exception("Código da licitacao nulo");
            return false;
        }
        $oDaoLicitem = db_utils::getDao("liclicitem");
        $sCampos = "distinct pc80_codproc,coddepto, descrdepto,login,pc80_data,pc80_resumo";
        $rsProcessos = $oDaoLicitem->sql_record(
            $oDaoLicitem->sql_query_inf(null, $sCampos, "pc80_codproc",
                "l21_codliclicita = {$this->iCodLicitacao}")
        );
        if ($oDaoLicitem->numrows > 0) {
            for ($iInd = 0; $iInd < $oDaoLicitem->numrows; $iInd++) {
                $aSolicitacoes[] = db_utils::fieldsMemory($rsProcessos, $iInd);
            }
        }
        return $aSolicitacoes;
    }

    /**
     * retorna os Dados da Licitacao
     * @return object
     */
    public function getDados()
    {
        $rsLicita = $this->oDaoLicita->sql_record($this->oDaoLicita->sql_query($this->iCodLicitacao));
        $this->oDados = db_utils::fieldsMemory($rsLicita, 0);
        return $this->oDados;
    }

    /**
     * Retorna os itens da solicitacao num xml
     * @return XML
     */
    public function itensToXml()
    {
        $oDaoLicitaItens = db_utils::getDao("liclicitem");
        $oDaoLicitaItensLote = db_utils::getDao("liclicitemlote");
        $sSqlItens = $oDaoLicitaItens->sql_query_inf(null, "distinct
      liclicitem.*,
      pcprocitem.*,
      solicita.*,
      pcmater.*,
      matunid.*,
      solicitem.*",
            "l21_codigo",
            "l21_codliclicita={$this->iCodLicitacao}");
        $rsItens = $oDaoLicitaItens->sql_record($sSqlItens);
        $aItensLicitacao = db_utils::getCollectionByRecord($rsItens);

        if (count($aItensLicitacao) == 0) {
            throw new BusinessException('Licitação sem itens vinculados.\nProcedimento abortado.');
        }

        $sStringXml = "<?xml version='1.0'  standalone='yes'?>";
        $sStringXml .= "<licitacao>";
        $sStringXml .= "</licitacao>";
        $oXml = new SimpleXMLElement($sStringXml);
        foreach ($aItensLicitacao as $oItemLicitacao) {
            $oItem = $oXml->addChild("item");
            for ($i = 0; $i < pg_num_fields($rsItens); $i++) {
                $sCampo = pg_field_name($rsItens, $i);
                $sValorCampo = $oItemLicitacao->$sCampo;
                $oItem->addChild("$sCampo", utf8_encode($sValorCampo));
            }
            /*
             * Verificamos se o item possui lote
             */
            $sSqlLote = $oDaoLicitaItensLote->sql_query_file(null,
                "*",
                null,
                "l04_liclicitem={$oItemLicitacao->l21_codigo}");
            $rsLote = $oDaoLicitaItensLote->sql_record($sSqlLote);
            $oItemLote = $oItem->addChild("lote");
            if ($oDaoLicitaItensLote->numrows > 0) {
                $aItensLote = db_utils::getCollectionByRecord($rsLote);

                foreach ($aItensLote as $oLote) {
                    $oItemLote->addAttribute("l04_codigo", utf8_encode($oLote->l04_codigo));
                    $oItemLote->addAttribute("l04_liclicitem", utf8_encode($oLote->l04_liclicitem));
                    $oItemLote->addAttribute("l04_descricao", utf8_encode($oLote->l04_descricao));
                }
            }
        }
        /*
         * adicionamos as secretarias no xml
         */
        $sSqlSecretarias = $oDaoLicitaItens->sql_query_orc(null,
            "distinct o40_descr",
            null,
            "l21_codliclicita = {$this->iCodLicitacao}"
        );
        $rsSecretarias = $oDaoLicitaItens->sql_record($sSqlSecretarias);

        $oXmlSecretarias = $oXml->addChild("secretarias");
        $aSecretarias = db_utils::getCollectionByRecord($rsSecretarias);
        foreach ($aSecretarias as $oSecretaria) {
            $oXmlSecretarias->addChild("secretaria", utf8_encode($oSecretaria->o40_descr));
        }

        /*
         * adicionamos os elementos
         */
        $sSqlElemento = $oDaoLicitaItens->sql_query_inf(null,
            "distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural ",
            null,
            "l21_codliclicita={$this->iCodLicitacao}");
        $rsElementos = $oDaoLicitaItens->sql_record($sSqlElemento);
        $oXmlElementos = $oXml->addChild("elementos");
        $aElementos = db_utils::getCollectionByRecord($rsElementos);
        foreach ($aElementos as $oElemento) {
            $oXmlElementos->addChild("elemento", utf8_encode($oElemento->estrutural));
        }

        return $oXml->asXML();
    }

    /**
     * @param             $iCodigoSituacao
     * @param string $sObservacao
     * @param DBDate|null $oData
     *
     * @return bool
     * @throws BusinessException
     * @throws Exception
     */
    public function alterarSituacao($iCodigoSituacao, $sObservacao = "", DBDate $oData = null)
    {
        if (!db_utils::inTransaction()) {
            throw new Exception('Sem transação Ativa');
        }

        switch ($iCodigoSituacao) {
            case SituacaoLicitacao::SITUACAO_DESERTA:

                $bPossuiJulgamento = $this->hasJulgamento();

                if ($bPossuiJulgamento) {
                    throw new Exception('A solicitação já possui Julgamento, impossível alterar situação');
                }
                break;


            case SituacaoLicitacao::SITUACAO_EM_ANDAMENTO:

                $this->retornaAndamento($sObservacao);
                return true;
                break;

            case SituacaoLicitacao::SITUACAO_ANULADA:

                if (in_array($this->iCodigoSituacao, array(2, 3, 4))) {
                    throw new Exception("Esta licitação não encontra-se nas situações Em andamento ou Julgada. Procedimento abortado.");
                }

                break;
        }


        if (!in_array($iCodigoSituacao,
            array(SituacaoLicitacao::SITUACAO_ADJUDICADA, SituacaoLicitacao::SITUACAO_HOMOLOGADA))) {
            $oDaoLiclicitem = new cl_liclicitem();
            $oDaoOrcreservaSol = new cl_orcreservasol();
            $oDaoOrcreserva = new cl_orcreserva();

            $sSqlReserva = $oDaoLiclicitem->sql_query_dotacao_reserva(null, "orcreservasol.*", null,
                "l21_codliclicita = {$this->iCodLicitacao}");
            $rsReserva = $oDaoLiclicitem->sql_record($sSqlReserva);

            /**
             * Verifica se os itens da licitação possuem reserva de saldo e remove elas
             */
            if ($rsReserva && $oDaoLiclicitem->numrows > 0) {
                for ($iRow = 0; $iRow < $oDaoLiclicitem->numrows; $iRow++) {
                    $oReserva = db_utils::fieldsMemory($rsReserva, $iRow);
                    if (empty($oReserva->o82_codres)) {
                        continue;
                    }
                    $oDaoOrcreservaSol->excluir($oReserva->o82_sequencial);

                    if ($oDaoOrcreservaSol->erro_status == 0) {
                        throw new Exception("Erro ao remover as reservas de saldo.2" . pg_last_error());
                    }

                    $oDaoOrcreserva->excluir($oReserva->o82_codres);

                    if ($oDaoOrcreservaSol->erro_status == 0) {
                        throw new Exception("Erro ao remover as reservas de saldo 1." . pg_last_error());
                    }
                }
            }
        }

        /**
         * Incluimos a situacao  para licitacao
         */
        $oDaoLiclicita = new cl_liclicita();
        $oDaoLiclicita->l20_codigo = $this->iCodLicitacao;
        $oDaoLiclicita->l20_licsituacao = $iCodigoSituacao;
        $oDaoLiclicita->alterar($this->iCodLicitacao);

        if ($oDaoLiclicita->erro_status == 0) {
            $sErro = "Erro ao alterar status da licitação:\n\n Erro técnico: erro na alteração de status /{$oDaoLiclicita->erro_msg}";
            throw new Exception($sErro, 4);
        }

        /**
         * Incluimos a nova situação
         */
        if (empty($oData)) {
            $oData = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
        }
        $oDaolicSituacao = new cl_liclicitasituacao();

        if (in_array($iCodigoSituacao,
            array(SituacaoLicitacao::SITUACAO_ADJUDICADA, SituacaoLicitacao::SITUACAO_HOMOLOGADA))) {
            $oDaolicSituacao->excluir(null,
                "l11_liclicita = {$this->iCodLicitacao} and l11_licsituacao = {$iCodigoSituacao}");
        }

        $oDaolicSituacao->l11_data = $oData->getDate();
        $oDaolicSituacao->l11_hora = db_hora();
        $oDaolicSituacao->l11_licsituacao = $iCodigoSituacao;
        $oDaolicSituacao->l11_obs = $sObservacao;
        $oDaolicSituacao->l11_id_usuario = db_getsession("DB_id_usuario");
        $oDaolicSituacao->l11_liclicita = $this->iCodLicitacao;
        $oDaolicSituacao->incluir(null);

        if ($oDaolicSituacao->erro_status == 0) {
            $sErro = "Erro ao alterar status da licitação:\n\n Erro técnico: erro ao incluir nova situação /{$oDaolicSituacao->erro_msg}";
            throw new Exception($sErro, 5);
        }

        return true;
    }

    /*
     * Cancela uma licitacao deserta
     */
    public function retornaAndamento($sObservacao = "")
    {
        $oDaoLiclicita = new cl_liclicita();
        $oDaoLiclicita->l20_codigo = $this->iCodLicitacao;
        $oDaoLiclicita->l20_licsituacao = "0";
        $oDaoLiclicita->alterar($this->iCodLicitacao);

        if ($oDaoLiclicita->erro_status == 0) {
            $sErro = "erro ao incluir licitacao deserta:\n{$oDaoLiclicita->erro_msg}";
            throw new Exception($sErro, 4);
        }

        /*
         * incluimos a situação
         */
        $oDaolicSituacao = db_utils::getDao("liclicitasituacao");
        $oDaolicSituacao->l11_data = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaolicSituacao->l11_hora = db_hora();
        $oDaolicSituacao->l11_licsituacao = "0";
        $oDaolicSituacao->l11_obs = $sObservacao;
        $oDaolicSituacao->l11_id_usuario = db_getsession("DB_id_usuario");
        $oDaolicSituacao->l11_liclicita = $this->iCodLicitacao;
        $oDaolicSituacao->incluir(null);

        if ($oDaolicSituacao->erro_status == 0) {
            $sErro = "erro ao incluir licitacao deserta:\n{$oDaolicSituacao->erro_msg}";
            throw new Exception($sErro, 5);
        }
    }

    public function getInfoLog()
    {
        $oDaoitensLog = db_utils::getDao("liclicitaitemlog");
        $sSqlLog = $oDaoitensLog->sql_query_file($this->iCodLicitacao);
        $rsLog = $oDaoitensLog->sql_record($sSqlLog);
        if ($oDaoitensLog->numrows == 1) {
            $oLog = db_utils::fieldsMemory($rsLog, 0);
            $oXML = new SimpleXMLElement($oLog->l14_xml);
        }
        return $oXML;
    }

    /**
     * Retorna o registro de preço da solicitacao
     * @return compilacaoRegistroPreco
     */
    public function getCompilacaoRegistroPreco()
    {
        if ($this->oRegistroPreco == null) {
            $sSqlRegistroPreco = "select distinct pc10_numero                                                                ";
            $sSqlRegistroPreco .= "  from liclicitem                                                                          ";
            $sSqlRegistroPreco .= "       inner join pcprocitem on liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem ";
            $sSqlRegistroPreco .= "       inner join solicitem  on pcprocitem.pc81_solicitem    = solicitem.pc11_codigo       ";
            $sSqlRegistroPreco .= "       inner join solicita   on solicitem.pc11_numero        = solicita.pc10_numero        ";
            $sSqlRegistroPreco .= " where l21_codliclicita = {$this->iCodLicitacao}                                           ";
            $sSqlRegistroPreco .= "   and pc10_solicitacaotipo = 6                                                            ";

            $rsRegistroPreco = db_query($sSqlRegistroPreco);
            if (pg_num_rows($rsRegistroPreco) == 1) {
                $this->oRegistroPreco = new compilacaoRegistroPreco(db_utils::fieldsMemory($rsRegistroPreco,
                    0)->pc10_numero);
            } else {
                throw new Exception('Licitacao não possui registros de preços.');
            }
        }
        return $this->oRegistroPreco;
    }


    static function getItensPorFornecedor(
        $aLicitacoes,
        $iFornecedor,
        $lValidaContrato = true,
        $lEncode = true,
        $aOutrosFiltros = array()
    ) {
        $oDaoLicilicitem = new cl_liclicitem;

        $sLista = trim($aLicitacoes[0]);
        if (empty($sLista)) {
            return array();
        }
        $sCampos = "l21_codigo as codigo, pc01_codmater as codigomaterial,";
        $sCampos .= "pc01_descrmater as material, pc23_vlrun as valorunitario,";
        $sCampos .= "pc01_servico as servico, 1 as origem, pc18_codele as elemento,";
        $sCampos .= "pc23_quant as quantidade, pc23_valor as valortotal,l20_numero as numero";

        $sQuery = "sql_itens_fornecedores";
        $aWhere = array(" pc21_numcgm= {$iFornecedor} ");
        $aWhere[] = " l21_codliclicita in({$sLista})";
        if ($lValidaContrato) {
            $sQuery = "sql_query_soljulg";
            $aWhere[] = "  ac24_sequencial is null ";
        }

        $aWhere = array_merge($aWhere, $aOutrosFiltros);
        $sWhere = implode(" and ", $aWhere);

        $sSqlLicitacoes = $oDaoLicilicitem->$sQuery(null, $sCampos, "l21_codigo, l21_ordem", $sWhere);
        $rsLicitacoes = $oDaoLicilicitem->sql_record($sSqlLicitacoes);
        return db_utils::getCollectionByRecord($rsLicitacoes, false, false, $lEncode);
    }

    /**
     * retorna todas as licitações que possuem um item ganho pelo credor.
     *
     * @param integer $iFornecedor codigo do fornecedor
     * @return array
     */
    static function getLicitacoesByFornecedor($iFornecedor, $lValidaAutorizadas = false, $iInstituicao = null)
    {
        $oDaoLicilicitem = new cl_liclicitem();

        $aWhere = array(" pc21_numcgm = {$iFornecedor} ");

        /**
         * Regra -> Não deve trazer licitações quando:
         * - Licitação já possui contrato para seus itens e a modalidade é diferente de CHAMAMENTO PÚBLICO / CREDENCIAMENTO. (Funcinamento padrão)
         * - Licitação já possui contrato para todos itens vínculado ao fornecedor e a modalidade é de CHAMAMENTO PÚBLICO / CREDENCIAMENTO
         */
        $sValidaTipoLicitacao = "( ";
        $sValidaTipoLicitacao .= " case ";
        $sValidaTipoLicitacao .= "   when l03_pctipocompratribunal != 54 and not exists (select 1 from acordoliclicitem where ac24_liclicitem = l21_codigo ) ";
        $sValidaTipoLicitacao .= "     then true ";

        // Onde l03_pctipocompratribunal = 54, deve existir ao menos um item que ainda não possui acordo para carregar a licitação
        $sValidaTipoLicitacao .= "   when l03_pctipocompratribunal = 54 and not exists( ";
        $sValidaTipoLicitacao .= "                                                     SELECT 1 ";
        $sValidaTipoLicitacao .= "                                                        FROM acordoliclicitem AS itens_acordo ";
        $sValidaTipoLicitacao .= "                                                        join acordoitem    on ac20_sequencial = itens_acordo.ac24_acordoitem ";
        $sValidaTipoLicitacao .= "                                                        join acordoposicao on ac26_sequencial = ac20_acordoposicao ";
        $sValidaTipoLicitacao .= "                                                        join acordo        on ac16_sequencial = ac26_acordo ";
        $sValidaTipoLicitacao .= "                                                       WHERE itens_acordo.ac24_liclicitem in (pc26_liclicitem) ";
        $sValidaTipoLicitacao .= "                                                         and ac16_contratado = {$iFornecedor} ";
        $sValidaTipoLicitacao .= "                                                    ) ";
        $sValidaTipoLicitacao .= "     then true ";
        $sValidaTipoLicitacao .= "   else false ";
        $sValidaTipoLicitacao .= " end ";
        $sValidaTipoLicitacao .= ")";

        $aWhere[] = $sValidaTipoLicitacao;

        if (!empty($iInstituicao)) {
            $aWhere[] = " liclicita.l20_instit = {$iInstituicao} ";
        }

        if ($lValidaAutorizadas) {
            $sValidaAutorizado = " not exists (";
            $sValidaAutorizado .= "                 select 1 ";
            $sValidaAutorizado .= "                   from empautoriza  ";
            $sValidaAutorizado .= "                        inner join empautitem           on e55_autori                      = e54_autori";
            $sValidaAutorizado .= "                        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
            $sValidaAutorizado .= "                                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori";
            $sValidaAutorizado .= "                        inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
            $sValidaAutorizado .= "                        inner join liclicitem           on pc81_codprocitem                = l21_codpcprocitem";
            $sValidaAutorizado .= "                  where l21_codliclicita = l20_codigo";
            $sValidaAutorizado .= "                    and e54_anulad is null";
            $sValidaAutorizado .= " )";

            $aWhere[] = $sValidaAutorizado;
        }

        $sWhere = implode(" and ", $aWhere);
        $sCampos = "distinct l20_codigo as licitacao, l20_objeto as objeto, l20_numero as numero, l03_descr as modalidade";
        $sCampos .= ", pc21_numcgm as cgm, l20_numero as numero_exercicio, l20_datacria as data, l20_usaregistropreco";
        $sSqlLicitacoes = $oDaoLicilicitem->sql_itens_fornecedores(null, $sCampos, "l20_codigo", $sWhere);
        $rsLicitacoes = $oDaoLicilicitem->sql_record($sSqlLicitacoes);
        return db_utils::getCollectionByRecord($rsLicitacoes, false, false, true);
    }

    /**
     * Retorna o valor total parcial da licitacao
     *
     * @param integer $iCodigoItemProcesso
     * @param integer $iCodigoDotacao
     * @param integer $iOrcTipoRec
     * @return \stdClass $oDadoValorParcial
     * @throws \Exception
     */
    public function getValoresParciais($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec = null)
    {
        if (empty($iCodigoItemProcesso)) {
            throw new Exception("Código do item do processo não informado!");
        }

        if (empty($iCodigoDotacao)) {
            throw new Exception("Código da dotação não informado!");
        }

        /**
         * Retorna somentes as autorizacoes das contrapartidas
         */
        $sWhereContrapartida = " and e56_orctiporec is null";
        if (!empty($iOrcTipoRec)) {
            $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}";
        }

        $oDaoEmpAutItem = db_utils::getDao("empautitem");
        $oDaoPcOrcam = db_utils::getDao("pcorcam");

        $oDadoValorParcial = new stdClass();
        $oDadoValorParcial->nValorAutorizacao = 0;
        $oDadoValorParcial->iQuantidadeAutorizacao = 0;
        $oDadoValorParcial->nValorItemJulgado = 0;
        $oDadoValorParcial->iQuantidadeItemJulgado = 0;

        /**
         * Retorna o valor total da autorizacao de empenho da licitacao
         */
        $sCampos = "sum(e55_vltot) as valorautorizacao,               ";
        $sCampos .= "sum(e55_quant) as quantidadeautorizacao           ";
        $sWhere = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
        $sWhere .= "      and e56_coddot     = {$iCodigoDotacao}      ";
        $sWhere .= "      and e54_anulad is null                      ";
        $sWhere .= "      {$sWhereContrapartida}                      ";
        $sWhere .= " group by e55_vltot,                              ";
        $sWhere .= "          e55_quant                               ";
        $sSqlAutorizacao = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);
        $rsSqlAutorizacao = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
        if ($oDaoEmpAutItem->numrows > 0) {
            for ($iIndEmpAutItem = 0; $iIndEmpAutItem < $oDaoEmpAutItem->numrows; $iIndEmpAutItem++) {
                $oAutorizacao = db_utils::fieldsMemory($rsSqlAutorizacao, $iIndEmpAutItem);
                $oDadoValorParcial->nValorAutorizacao += $oAutorizacao->valorautorizacao;
                $oDadoValorParcial->iQuantidadeAutorizacao += $oAutorizacao->quantidadeautorizacao;
            }
        }

        /**
         * Retorna o valor do item julgado na licitacao
         */
        $sCampos = "pc23_quant, pc23_valor, pc13_valor, pc13_quant, pc11_vlrun, pc11_quant";
        $sWhere = "l21_codpcprocitem = {$iCodigoItemProcesso} and pc24_pontuacao = 1";
        $sWhere .= " and pc13_coddot  = {$iCodigoDotacao} ";
        $sWhereContrapartida = " and pc19_orctiporec is null ";
        if ($iOrcTipoRec > 0) {
            $sWhereContrapartida = "  and pc19_orctiporec = {$iOrcTipoRec} ";
        }
        $sWhere .= $sWhereContrapartida;
        $sSqlPcOrcam = $oDaoPcOrcam->sql_query_valitemjulglic(null, $sCampos, null, $sWhere);
        $rsSqlPcOrcam = $oDaoPcOrcam->sql_record($sSqlPcOrcam);
        if ($oDaoPcOrcam->numrows > 0) {
            for ($iIndPcOrcam = 0; $iIndPcOrcam < $oDaoPcOrcam->numrows; $iIndPcOrcam++) {
                $oItemJulgado = db_utils::fieldsMemory($rsSqlPcOrcam, $iIndPcOrcam);
                $nPercentualDotacao = 0;

                if ($oItemJulgado->pc13_valor > 0 && $oItemJulgado->pc11_vlrun > 0) {
                    $nPercentualDotacao = ($oItemJulgado->pc13_valor * 100) / ($oItemJulgado->pc11_quant * $oItemJulgado->pc11_vlrun);
                }
                /**
                 * retorna o valor novo da dotacao; (pode ter um aumento/diminuição do valor)
                 */
                $nValorDotacao = round(($oItemJulgado->pc23_valor * $nPercentualDotacao) / 100, 2);
                $oDadoValorParcial->valordiferenca = $nValorDotacao;
                $oDadoValorParcial->nValorItemJulgado += $nValorDotacao;
                $oDadoValorParcial->iQuantidadeItemJulgado += $oItemJulgado->pc23_quant;
            }
        }
        $oDadoValorParcial->nValorSaldoTotal = ($oDadoValorParcial->nValorItemJulgado - $oDadoValorParcial->nValorAutorizacao);
        return $oDadoValorParcial;
    }


    /**
     * retorna os itens que podem ser gerados autorizacao de empenho
     *
     */
    public function getItensParaAutorizacao()
    {
        $oDaoPcOrcamJulg = db_utils::getDao("pcorcamjulg");
        $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
        $this->oDaoParametros = db_utils::getDao("empparametro");

        $sCampos = "l21_ordem,";
        $sCampos .= "pc01_codmater as codigomaterial,";
        $sCampos .= "pc01_descrmater as descricaomaterial,";
        $sCampos .= "pc01_servico as servico,";
        $sCampos .= "pc11_quant as quanttotalitem,";
        $sCampos .= "pc11_vlrun as valorunitario,";
        $sCampos .= "pc11_numero,";
        $sCampos .= "pc11_codigo as codigoitemsolicitacao,";
        $sCampos .= "pc11_servicoquantidade as servicoquantidade,";
        $sCampos .= "pc13_coddot as codigodotacao,";
        $sCampos .= "pc13_sequencial as codigodotacaoitem,";
        $sCampos .= "pc13_quant as quanttotaldotacao,";
        $sCampos .= "pc13_anousu as anodotacao,";
        $sCampos .= "pc13_valor as valordotacao,";
        $sCampos .= "pc17_unid,";
        $sCampos .= "pc17_quant,";
        $sCampos .= "pc23_orcamforne,";
        $sCampos .= "pc23_valor as valorfornecedor,";

        /**
         *  Alterado conforme solicitacao do henrique,
         *    antes o sistema buscava o obs do item a ser gravado na empautitem da pcorcamval
         *    troquei para buscar resumo do item da solicitacao, caso nao encontre no item
         *    busca resumo da solicitacao. T57360
         */

        //   $sCampos .= "case when trim(pc23_obs) <> '' then pc23_obs";
        //   $sCampos .= "     else pc10_resumo ";
        //   $sCampos .= " end as observacao,";

        $sCampos .= "case when trim(pc11_resum) <> '' then pc11_resum";
        $sCampos .= "     else pc10_resumo ";
        $sCampos .= " end as observacao,";
        $sCampos .= "pc10_resumo as observacao_solicita,";
        $sCampos .= "pc23_vlrun as valorunitariofornecedor,";
        $sCampos .= "pc23_quant as quantfornecedor,";
        $sCampos .= "z01_numcgm as codigofornecedor,";
        $sCampos .= "z01_nome as fornecedor,";
        $sCampos .= "m61_descr,";
        $sCampos .= "m61_usaquant,";
        $sCampos .= "pc10_numero as codigosolicitacao,";
        $sCampos .= "pc19_orctiporec as contrapartida,";
        $sCampos .= "pc81_codprocitem as codigoitemprocesso,";
        $sCampos .= "pc22_orcamitem,";
        $sCampos .= "pc18_codele as codigoelemento,";
        $sCampos .= "o56_descr as descricaoelemento,";
        $sCampos .= "o56_elemento as elemento";


        $sOrder = "z01_numcgm,pc13_coddot,pc18_codele, pc19_sequencial,l21_ordem, pc19_orctiporec,pc13_sequencial";
        $sWhere = "l20_codigo = {$this->iCodLicitacao} and pc24_pontuacao = 1 and pc10_instit = " . db_getsession("DB_instit");
        $sWhere .= " and l21_codigo not in (select ac24_liclicitem from acordoliclicitem)";

        $sSqlPcOrcamJulg = $oDaoPcOrcamJulg->sql_query_gerautlic(null, null, $sCampos, $sOrder, $sWhere);

        $rsPcOrcamJulg = $oDaoPcOrcamJulg->sql_record($sSqlPcOrcamJulg);
        $iRowPcOrcamJulg = $oDaoPcOrcamJulg->numrows;

        /**
         * busca o parametro de casas decimais para formatar o valor jogado na grid
         */
        $iAnoSessao = db_getsession("DB_anousu");
        $sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
        $sSqlPeriodoParametro = $this->oDaoParametros->sql_query_file(null, "e30_numdec", null,
            $sWherePeriodoParametro);


        $rsPeriodoParametro = $this->oDaoParametros->sql_record($sSqlPeriodoParametro);
        $aItens = array();
        if ($iRowPcOrcamJulg > 0) {
            for ($i = 0; $i < $iRowPcOrcamJulg; $i++) {
                $oDados = db_utils::fieldsMemory($rsPcOrcamJulg, $i, false, false, true);

                /*
                 * calcula o percentual da dotação em relacao ao valor total
                 */
                $nPercentualDotacao = 100;
                if ($oDados->valorunitario > 0) {
                    $nPercentualDotacao = ($oDados->valordotacao * 100) / ($oDados->quanttotalitem * $oDados->valorunitario);
                }
                $oDados->percentual = $nPercentualDotacao;

                /**
                 * retorna o valor novo da dotacao; (pode ter um aumento/diminuição do valor)
                 */
                $nValorDotacao = round(($oDados->valorfornecedor * $nPercentualDotacao) / 100, 2);
                $oDados->valordiferenca = $nValorDotacao;
                if ($oDados->valordotacao > $oDados->valorfornecedor) {
                    $oDados->valordiferenca = $oDados->valorfornecedor;
                }
                /**
                 * Verificamos o valor reservado para o item
                 */
                $sSqlReservaDotacao = $oDaoOrcReservaSol->sql_query_orcreserva(
                    null,
                    null,
                    "o80_codres,o80_valor",
                    "",
                    "o82_pcdotac = {$oDados->codigodotacaoitem}");
                $rsReservaDotacao = $oDaoOrcReservaSol->sql_record($sSqlReservaDotacao);
                $oDados->valorreserva = 0;
                $oDados->dotacaocomsaldo = true;
                $oDados->saldofinaldotacao = 0;

                if ($oDaoOrcReservaSol->numrows == 1) {
                    $oDados->valorreserva = db_utils::fieldsMemory($rsReservaDotacao, 0)->o80_valor;
                }

                $oValoresAutorizados = $this->getValoresParciais($oDados->codigoitemprocesso,
                    $oDados->codigodotacao,
                    $oDados->contrapartida
                );
                $oDados->quantidadeautorizada = $oValoresAutorizados->iQuantidadeAutorizacao;
                $oDados->valorautorizado = $oValoresAutorizados->nValorAutorizacao;
                $oDados->saldoautorizar = $oValoresAutorizados->nValorSaldoTotal;
                $oDotacao = new Dotacao($oDados->codigodotacao, $oDados->anodotacao);
                $oDados->saldofinaldotacao = $oDotacao->getSaldoAtualMenosReservado();
                $oDados->servico = $oDados->servico == 't' ? true : false;

                /**
                 * Verificamos as quantidades executadas do item
                 */
                $oDados->saldoquantidade = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
                $oDados->saldovalor = $oDados->valordiferenca - $oDados->valorautorizado;

                /**
                 * Verifica se a dotação tem saldo para poder autorizar o item
                 */
                if ($oDotacao->getSaldoAtualMenosReservado() <= 0 && $oDados->valorreserva == 0) {
                    $oDados->dotacaocomsaldo = false;
                }

                if ($oDotacao->getSaldoAtualMenosReservado() < $oDados->valorunitario && $oDados->servico == false) {
                    $oDados->dotacaocomsaldo = false;

                    /**
                     * Verifica se o valor da reserva é suficiente para autorizar o item parcialmente
                     */
                    if ($oDados->valorreserva >= ($oDados->valorunitario * $oDados->saldoquantidade)) {
                        $oDados->dotacaocomsaldo = true;
                    }
                }
                /**
                 * Caso for serviço e ele não for controlado por quantidade setamos o saldo de quantidade para 1
                 */
                if ($oDados->servico && $oDados->servicoquantidade == "f") {
                    $oDados->saldoquantidade = 1;
                }
                $oDados->autorizacaogeradas = $this->getAutorizacoes($oDados->codigoitemprocesso,
                    $oDados->codigodotacao);
                $oDados->valorunitariofornecedor = number_format($oDados->valorunitariofornecedor,
                    db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec,
                    '.', '');
                $aItens[] = $oDados;
            }

            return $aItens;
        }
    }

    /**
     * Retorna as autorizações geradas para o item.
     *
     * @param integer $iCodigoItemProcesso
     * @param integer $iCodigoDotacao
     * @param integer $iOrcTipoRec
     * TODO retornar objeto da autorização
     */
    public function getAutorizacoes($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec = null)
    {
        if (empty($iCodigoItemProcesso)) {
            throw new Exception("Código do item do processo não informado!");
        }

        if (empty($iCodigoDotacao)) {
            throw new Exception("Código da dotação não informado!");
        }

        /**
         * Retorna somentes as autorizacoes das contrapartidas
         */
        $sWhereContrapartida = " and e56_orctiporec is null";
        if (!empty($iOrcTipoRec)) {
            $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}";
        }

        $sCampos = "distinct e55_autori as autorizacao                 ";
        $sWhere = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
        $sWhere .= "      and e56_coddot     = {$iCodigoDotacao}      ";
        $sWhere .= "      and e54_anulad is null                      ";
        $sWhere .= "      {$sWhereContrapartida}                      ";
        $oDaoEmpAutItem = db_utils::getDao("empautitem");
        $sSqlAutorizacao = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);
        $rsAutorizacao = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
        $aAutorizacoes = array();

        for ($iRow = 0; $iRow < $oDaoEmpAutItem->numrows; $iRow++) {
            $oDadosAutorizacao = db_utils::fieldsMemory($rsAutorizacao, $iRow);
            $aAutorizacoes[] = $oDadosAutorizacao->autorizacao;
        }

        return $aAutorizacoes;
    }

    /**
     * gera os dados para a autorizacao;
     *
     * @param array $aDados
     */
    public function gerarAutorizacoes($aDadosAutorizacao)
    {
        $aAutorizacoes = array();

        /**
         * calcular reservas para a Solicitacao (quando parcial)
         * calcular orcreserva
         */
        $oDadosLicitacao = $this->getDados();
        $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
        $oDaoOrcReserva = db_utils::getDao("orcreserva");
        $oDaoPcdotac = db_utils::getDao("pcdotac");
        $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");

        /**
         * Percorrendo as autorizações á gerar
         */
        foreach ($aDadosAutorizacao as $oDados) {
            $nValorTotal = 0;

            /**
             * Percorrendo os itens de cada autorização á gerar
             */
            foreach ($oDados->itens as $oItem) {
                /**
                 * Para cada ítem temos uma reserva
                 */
                $nValorTotal += (float)str_replace(',', '.', $oItem->valortotal);
                /**
                 * verificamos se exite reserva de saldo para a solicitacao;
                 * caso exista, devemos calcular a diferença entre o que deve ser gerado para a autorizacao e a solictacao
                 */
                $aReservas = itemSolicitacao::getReservasSaldoDotacao($oItem->pcdotac);
                $nNovoValorReserva = (float)str_replace(',', '.', $oItem->valortotal);
                if (!empty($aReservas)) {
                    $nNovoValorReserva = $aReservas[0]->valor - $oItem->valortotal;
                    if ($nNovoValorReserva < 0) {
                        $nNovoValorReserva = 0;
                    }

                    /**
                     * Excluímos a reserva da solicitação e incluimos uma nova
                     */
                    $oDaoOrcReservaSol->excluir(null, "o82_codres = {$aReservas[0]->codigoreserva}");
                    if ($oDaoOrcReservaSol->erro_status == 0) {
                        throw new Exception($oDaoOrcReservaSol->erro_msg);
                    }

                    /**
                     * Excluir OrcReserva
                     */
                    $oDaoOrcReserva->excluir($aReservas[0]->codigoreserva);
                    if ($oDaoOrcReserva->erro_status == 0) {
                        throw new Exception($oDaoOrcReserva->erro_msg);
                    }
                }
                /**
                 * Incluímos os dados na OrcReserva e orcreservasol, caso o item ainda tenha valor disponível
                 */
                $oSaldo = $this->getValoresParciais($oItem->codigoprocesso, $oDados->dotacao, $oDados->contrapartida);

                if ($nNovoValorReserva > 0 && ($oSaldo->nValorAutorizacao > 0 && $oSaldo->nValorAutorizacao + $oItem->valortotal < $oSaldo->nValorItemJulgado)) {
                    $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
                    $oDaoOrcReserva->o80_coddot = $oDados->dotacao;
                    $oDaoOrcReserva->o80_dtfim = db_getsession("DB_anousu") . "-12-31";
                    $oDaoOrcReserva->o80_dtini = date("Y-m-d", db_getsession("DB_datausu"));
                    $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
                    $oDaoOrcReserva->o80_valor = number_format((float)$nNovoValorReserva, 2, '.', '');
                    $oDaoOrcReserva->o80_descr = "Reserva item Solicitacao";
                    $oDaoOrcReserva->incluir(null);

                    if ($oDaoOrcReserva->erro_status == 0) {
                        $sMsgErro = "Não foi possivel gerar reserva para a dotação: {$oDados->dotacao}.\n";
                        $sMsgErro .= $oDaoOrcReserva->erro_msg;
                        throw new Exception($sMsgErro);
                    }

                    $oDaoOrcReservaSol->o82_codres = $oDaoOrcReserva->o80_codres;
                    $oDaoOrcReservaSol->o82_pcdotac = $oDados->pcdotac;
                    $oDaoOrcReservaSol->o82_solicitem = $oDados->codigoitemsolicitacao;
                    $oDaoOrcReservaSol->incluir(null);
                    if ($oDaoOrcReservaSol->erro_status == 0) {
                        $sMsgErro = "Não foi possivel gerar reserva para a dotação: {$oDados->dotacao}.\n";
                        $sMsgErro .= $oDaoOrcReservaSol->erro_msg;
                        throw new Exception($sMsgErro);
                    }
                }
            }
            /**
             * Salvamos a Autorizacao;
             */

            /**
             * Resumo da autorização
             */
            $rsPcdotac = $oDaoPcdotac->sql_record($oDaoPcdotac->sql_query_solicita(null, null, null, "pc10_resumo",
                null, "pc13_sequencial = {$oItem->pcdotac}"));
            $sResumo = $oDaoPcdotac->numrows > 0 ? db_utils::fieldsMemory($rsPcdotac, 0)->pc10_resumo : $oDados->resumo;

            $oAutorizacao = new AutorizacaoEmpenho();
            /**
             * Não pode-se setar o codigo da reserva da solicitação na Autorizacao.
             * A autorizacao gera um codigo de reserva quando inclusa
             */
            //$oAutorizacao->setCodigoReserva($iCodigoReserva);
            $oAutorizacao->setDesdobramento($oDados->elemento);
            $oAutorizacao->setDotacao($oDados->dotacao);
            $oAutorizacao->setContraPartida($oDados->contrapartida);
            $oFornecedor = CgmFactory::getInstance('', $oDados->cgm);
            $oAutorizacao->setFornecedor($oFornecedor);
            $oAutorizacao->setValor($nValorTotal);
            $oAutorizacao->setTipoEmpenho($oDados->tipoempenho);
            $oAutorizacao->setCaracteristicaPeculiar($oDados->concarpeculiar);

            $aItemSolcitem = array();
            foreach ($oDados->itens as $oItem) {
                $oAutorizacao->addItem($oItem);
                $aItemSolcitem[] = $oItem->solicitem;
            }

            $oAutorizacao->setDestino($oDados->destino);
            $oAutorizacao->setContato($oDados->sContato);
            $oAutorizacao->setResumo(addslashes($sResumo));
            $oAutorizacao->setTelefone($oDados->sTelefone);
            $oAutorizacao->setTipoCompra($oDados->tipocompra);
            $oAutorizacao->setPrazoEntrega($oDados->prazoentrega);
            $oAutorizacao->setTipoLicitacao($oDados->sTipoLicitacao);
            $oAutorizacao->setTipoLicitacao($oDadosLicitacao->l03_tipo);
            $oAutorizacao->setNumeroLicitacao($oDados->iNumeroLicitacao);
            $oAutorizacao->setOutrasCondicoes($oDados->sOutrasCondicoes);
            $oAutorizacao->setCondicaoPagamento($oDados->condicaopagamento);
            $oAutorizacao->setNumeroLicitacao("{$oDadosLicitacao->l20_numero}/{$oDadosLicitacao->l20_anousu}");
            $oAutorizacao->salvar();


            $sProcessoAdministrativo = null;

            if (isset($oDados->e150_numeroprocesso) && !empty($oDados->e150_numeroprocesso)) {
                $sProcessoAdministrativo = db_stdClass::normalizeStringJsonEscapeString($oDados->e150_numeroprocesso);
            }

            /**
             * Buscar o código do processo da tabela solicitaprotprocesso e incluir na empautorizaprotprocesso caso tenha
             */
            $oDaoSolicitem = db_utils::getDao("solicitem");
            $sCodigosItens = implode(",", $aItemSolcitem);
            $sSqlBuscaSolicitem = $oDaoSolicitem->sql_query_solicitaprotprocesso(null, "solicitaprotprocesso.*", null,
                "pc11_codigo in ({$sCodigosItens})");
            $rsBuscaSolicitem = $oDaoSolicitem->sql_record($sSqlBuscaSolicitem);
            $oDadoSolicitaProtProcesso = db_utils::fieldsMemory($rsBuscaSolicitem, 0);

            if (empty($sProcessoAdministrativo) && !empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {
                $sProcessoAdministrativo = $oDadoSolicitaProtProcesso->pc90_numeroprocesso;
            }

            if (!empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {
                $oDaoEmpAutorizaProcesso = db_utils::getDao("empautorizaprocesso");
                $oDaoEmpAutorizaProcesso->e150_sequencial = null;
                $oDaoEmpAutorizaProcesso->e150_empautoriza = $oAutorizacao->getAutorizacao();
                $oDaoEmpAutorizaProcesso->e150_numeroprocesso = $sProcessoAdministrativo;
                $oDaoEmpAutorizaProcesso->incluir(null);
                if ($oDaoEmpAutorizaProcesso->erro_status == 0) {
                    $sMensagemProcessoAdministrativo = "Ocorreu um erro para incluir o número do processo administrativo ";
                    $sMensagemProcessoAdministrativo .= "na autorização de empenho.\n\n{$oDaoEmpAutorizaProcesso->erro_msg}";
                    throw new Exception($sMensagemProcessoAdministrativo);
                }
            }
            $aAutorizacoes[] = $oAutorizacao->getAutorizacao();
        }
        return $aAutorizacoes;
    }

    /**
     * Pega os itens de uma licitação
     * @return ItemLicitacao[]
     */
    public function getItens()
    {
        if (count($this->aItensLicitacao) == 0) {
            $oDaoLicLicitem = db_utils::getDao("liclicitem");
            $sSqlLicLicitem = $oDaoLicLicitem->sql_query(null, "l21_codigo", "l21_ordem",
                "l21_codliclicita = {$this->iCodLicitacao}");

            $rsLicLicitem = $oDaoLicLicitem->sql_record($sSqlLicLicitem);
            $iNumRowsLiclicitem = $oDaoLicLicitem->numrows;


            for ($iRow = 0; $iRow < $iNumRowsLiclicitem; $iRow++) {
                $oDadoLicLicitem = db_utils::fieldsMemory($rsLicLicitem, $iRow);
                $oItemLicitacao = new ItemLicitacao($oDadoLicLicitem->l21_codigo);
                $this->aItensLicitacao[] = $oItemLicitacao;
            }
        }
        return $this->aItensLicitacao;
    }

    /**
     * Pega os itens de acordo com o processo de compras
     * @param integer $iProcesso
     */
    public function getItensPorProcessoDeCompras($iProcesso)
    {
        $aItens = $this->getItens();
        $aItensRetorno = array();
        foreach ($aItens as $oItem) {
            if ($oItem->getProcessoCompra() == $iProcesso) {
                $aItensRetorno[] = $oItem;
            }
        }
        return $aItensRetorno;
    }

    /**
     * Desvincula o processo de compras de uma licitacao
     * @param  integer $iProcesso
     * @return boolean
     */
    public function desvinculaProcessoDeCompras($iProcesso)
    {
        $aItensProcesso = $this->getItensPorProcessoDeCompras($iProcesso);
        $aItensProcessoCompras = array();
        $aCodLicLicitem = array();

        //echo ("<pre>".print_r($aItensProcesso, 1)."</pre>");exit;
        foreach ($aItensProcesso as $oItemLicitacao) {
            $iCodItemProcCompra = $oItemLicitacao->getItemProcessoCompras();
            /**
             * Verifica se existem fornecedores vinculados ao processo de compras
             */
            $sSqlBuscaItem = " select distinct z01_numcgm, z01_nome";
            $sSqlBuscaItem .= "   from pcorcamitemlic";
            $sSqlBuscaItem .= "        inner join pcorcamitem  on pc22_orcamitem = pc26_orcamitem   ";
            $sSqlBuscaItem .= "        inner join pcorcamforne on    pc21_codorc = pc22_codorc      ";
            $sSqlBuscaItem .= "        inner join cgm          on     z01_numcgm = pc21_numcgm      ";
            $sSqlBuscaItem .= "        inner join liclicitem   on     l21_codigo = pc26_liclicitem  ";
            $sSqlBuscaItem .= "  where l21_codpcprocitem  = {$iCodItemProcCompra} ";
            $rsBuscaItem = db_query($sSqlBuscaItem);
            $iTotalLinhas = pg_num_rows($rsBuscaItem);

            /**
             * Caso exista fornecedores cadastrados o processo é abortado, do contrário é excluido os registros em
             * liclicitemlote e liclicitem
             */
            if ($iTotalLinhas > 0) {
                $sItensMovimento = "";
                for ($iRow = 0; $iRow < $iTotalLinhas; $iRow++) {
                    $oDadosItem = db_utils::fieldsMemory($rsBuscaItem, $iRow);
                    $sItensMovimento .= $oDadosItem->z01_numcgm . " - " . $oDadosItem->z01_nome . "\n";
                }
                $sMsgException = "Desvincule os fornecedores abaixo dos processos de compras selecionados.\n\n{$sItensMovimento}";
                throw new Exception($sMsgException);
            } else {
                $oItemLicitacao->remover($oItemLicitacao->getCodigo());
            }
        }
    }

    /**
     * Busca as solicitações que tem dotação do ano anterior.
     * @return mixed
     */
    public function getSolicitacoesDotacaoAnoAnterior()
    {
        $oDaoLicLicitem = db_utils::getDao("liclicitem");
        $sWhereDotacao = "l21_codliclicita = {$this->getCodigo()} and pc13_anousu < " . db_getsession("DB_anousu");
        $sCamposDotacao = "distinct pc11_numero as solicita";
        $sSqlBuscaDotacao = $oDaoLicLicitem->sql_query_orc(null, $sCamposDotacao, null, $sWhereDotacao);
        $rsBuscaDotacao = $oDaoLicLicitem->sql_record($sSqlBuscaDotacao);
        $iRowDotacao = $oDaoLicLicitem->numrows;
        $aSolicitacao = array();

        if ($iRowDotacao > 0) {
            for ($iRow = 0; $iRow < $iRowDotacao; $iRow++) {
                $iSolicita = db_utils::fieldsMemory($rsBuscaDotacao, $iRow)->solicita;
                $aSolicitacao[] = $iSolicita;
            }
        }
        return $aSolicitacao;
    }

    public function getEdital()
    {
        return $this->iNumeroEdital;
    }

    public function alterarObservacaoSituacao($iSequencialLicitacaoSituacao, $sObservacao)
    {
        $oDaoLicLicitaSituacao = db_utils::getDao('liclicitasituacao');
        $oDaoLicLicitaSituacao->l11_obs = "{$sObservacao}";
        $oDaoLicLicitaSituacao->l11_sequencial = $iSequencialLicitacaoSituacao;
        $oDaoLicLicitaSituacao->alterar($iSequencialLicitacaoSituacao);

        if ($oDaoLicLicitaSituacao->erro_status == 0) {
            $sMsgErro = "Não foi possivel alterar a observação\n";
            $sMsgErro .= $oDaoLicLicitaSituacao->erro_msg;
            throw new Exception($sMsgErro);
        }
    }

    public function hasJulgamento()
    {
        $oDaoLicLicita = db_utils::getDao('liclicita');
        $sSQL = $oDaoLicLicita->sql_query_julgamento_licitacao($this->iCodLicitacao);
        $rsLiLicita = $oDaoLicLicita->sql_record($sSQL);
        $iRowJulgamento = $oDaoLicLicita->numrows;

        $lRetorno = false;
        if ($iRowJulgamento > 0) {
            $lRetorno = true;
        }
        return $lRetorno;
    }


    public function hasFornecedor()
    {
        return count($this->getFornecedor()) > 0;
    }

    /**
     * Retorna um array de fornecedores.
     */
    public function getFornecedor()
    {
        if (count($this->aFornecedores) == 0) {
            $sWhereFornecedor = "l20_codigo = {$this->getCodigo()}";
            $oDaoOrcamentoItemLicitacao = db_utils::getDao('pcorcamitemlic');

            $sCampos = "distinct z01_numcgm, z01_nome, pc21_orcamforne, pc21_codorc ";
            $sSqlBuscaFornecedor = $oDaoOrcamentoItemLicitacao->sql_query_fornecedores(null, $sCampos, null,
                $sWhereFornecedor);
            $rsBuscaFornecedor = $oDaoOrcamentoItemLicitacao->sql_record($sSqlBuscaFornecedor);

            if ($oDaoOrcamentoItemLicitacao->numrows > 0) {
                $this->aFornecedores = db_utils::getCollectionByRecord($rsBuscaFornecedor);
            }
        }
        return $this->aFornecedores;
    }


    /**
     * funcao para verifica saldo disponivel num determinada modalidade de licitação
     *
     * @param $iModadalidade  sequencial da modalidade
     * @param $iItem          codigo do item a ser comprado
     * @param $dtJulgamento   data do julgamento
     * @return boolean
     *
     */
    public static function verificaSaldoModalidade($iModalidade, $iItem, $dtJulgamento)
    {
        $oRetornVerificacao = new stdClass();
        $oDaoPcOrcamItem = db_utils::getDao("pcorcamitem");
        $oDaoModalidade = db_utils::getDao("cflicitavalores");

        /*
         * montamos objeto com dados da modalidade, para verificação posterior.
         */
        $sWhere = "     l40_codfclicita = {$iModalidade}";
        $sWhere .= " and l40_datainicial <= '{$dtJulgamento}'";
        $sWhere .= " and l40_datafinal   >= '{$dtJulgamento}'";
        $sSqlModalidade = $oDaoModalidade->sql_query(null, "cflicitavalores.*,cflicita.* ", null, $sWhere);

        $rsModalidade = $oDaoModalidade->sql_record($sSqlModalidade);
        if ($oDaoModalidade->numrows <= 0) {
            //$sMensagem  = "ERRO [ 0 ] - Verifique faixa de valores da Modalidade {$iModalidade} - ";
            //$sMensagem .= licitacao::getDescricaoModalidade($iModalidade)->l03_descr." .";
            //throw new Exception($sMensagem);
            /*
             * se caso nao encontre faixa de valor pra modalidade, significa
             * que ela não sera controlada por valores.
             */
            $oRetornVerificacao->lPossuiSaldo = true;
            $oRetornVerificacao->sMensagem = '';
            return $oRetornVerificacao;
        }
        $oDadosModalidade = db_utils::fieldsMemory($rsModalidade, 0);

        /*
         * buscamos dados do material em questão com base no item dentro do orcamento pc22_orcamitem
         */
        $sCampos = "pc01_codmater, sum(pc11_quant * pc11_vlrun) as total ";
        $sWhereItem = "pc22_orcamitem = {$iItem} group by pc01_codmater";

        $sSqlMaterial = $oDaoPcOrcamItem->sql_query_saldoModalidade(null, $sCampos, null, $sWhereItem);
        $rsMaterial = $oDaoPcOrcamItem->sql_record($sSqlMaterial);

        if ($oDaoPcOrcamItem->numrows <= 0) {
            throw new Exception("ERRO [ 1 ] - Verificando saldo da Modalidade - Item do Processo de Compra Não Encontrado.");
        }

        /*
         * montamos objeto com dados domaterial a ser analisado
         */
        $oDadosMaterial = db_utils::fieldsMemory($rsMaterial, 0);
        $oRetornVerificacao->lPossuiSaldo = true;
        $oRetornVerificacao->sMensagem = '';
        $oMaterial = new MaterialCompras($oDadosMaterial->pc01_codmater);
        $iCodigoMaterialPcMater = $oMaterial->getMaterial();

        /*
         * com o codigo do material da pcmater, verificamos
         * todas compras que ja foram feitas para esse material
         * nessa modalidade nesse periodo de julgamento;
         */

        $sWhereMateriaisComprados = "     pc16_codmater   = {$iCodigoMaterialPcMater} ";
        $sWhereMateriaisComprados .= " and l20_codtipocom  = {$iModalidade}            ";
        $sWhereMateriaisComprados .= " and pc26_orcamitem != {$iItem}                  "; // e o item que esta sendo julgado não venha junto com os ja julgados

        $sCamposMateriaisComprados = "sum(pc11_quant * pc11_vlrun) as total ";
        $sSqlMateriaisComprados = $oDaoPcOrcamItem->sql_query_saldoModalidade(null, $sCamposMateriaisComprados, null,
            $sWhereMateriaisComprados);
        $rsMateriaisComprados = $oDaoPcOrcamItem->sql_record($sSqlMateriaisComprados);


        $aDadosMateriaisComprados = db_utils::getCollectionByRecord($rsMateriaisComprados);

        /*
         * com os dados dos materias ja adquiridos para a modalidade
         * verificamos os valores totais dos itens na adquiridos e o saldo restante da modalidade
         * dentro do preiodo do julgamento
         */
        $oMateriaisComprados = db_utils::fieldsMemory($rsMateriaisComprados, 0);

        /*
         * verificamos se a data do julgamento esta entre o periodo da modalidade
         * para verificarmos valores
         */
        if (($dtJulgamento >= $oDadosModalidade->l40_datainicial) && ($dtJulgamento <= $oDadosModalidade->l40_datafinal)) {
            /*
             * verificamos a soma do total do material julgado, com as somas totais dos
             * materias iguais ja comprados para a modalidade e comparamos se excede o total da modalidade
             */
            $nValorTotalMaterial = $oDadosMaterial->total;
            $nTotalGasto = $oMateriaisComprados->total;
            $nTotalModalidade = $oDadosModalidade->l40_valormaximo;
            $nTotalDesejado = ($nValorTotalMaterial + $nTotalGasto);

            if ($nTotalDesejado > $nTotalModalidade) {
                $sErroMsg = "ERRO [ 2 ] - Modalidade {$iModalidade} - {$oDadosModalidade->l03_descr} ";
                $sErroMsg .= "Sem Saldo para o Item {$iCodigoMaterialPcMater} - {$oMaterial->getDescricao()}";

                $oRetornVerificacao->lPossuiSaldo = false;
                $oRetornVerificacao->sMensagem = $sErroMsg;
            }
        }
        return $oRetornVerificacao;
    }


    public static function getDescricaoModalidade($iModalidade)
    {
        $oDaoModalidade = db_utils::getDao("cflicita");
        $sSqlModalidade = $oDaoModalidade->sql_query_file($iModalidade);
        $rsModalidade = $oDaoModalidade->sql_record($sSqlModalidade);
        $oModalidade = db_utils::fieldsMemory($rsModalidade, 0);
        return $oModalidade;//l03_descr;
    }

    /**
     * @return LicitacaoModalidade
     */
    public function getModalidade()
    {
        if (empty($this->oModalidade)) {
            $this->oModalidade = LicitacaoModalidadeRepository::getByCodigo($this->iCodigoModalidade);
        }
        return $this->oModalidade;
    }

    /**
     * Retorna a situação da licitação
     * @return SituacaoLicitacao
     */
    public function getSituacao()
    {
        if ($this->iCodigoSituacao != "") {
            $this->oSituacaoLicitacao = new SituacaoLicitacao($this->iCodigoSituacao);
        }
        return $this->oSituacaoLicitacao;
    }

    /**
     * @return DBDate|bool
     * @throws Exception
     */
    public function getDataAjudicacao()
    {
        return $this->getDataPorSituacao(new SituacaoLicitacao(SituacaoLicitacao::SITUACAO_ADJUDICADA));
    }

    /**
     * @return bool|DBDate
     * @throws Exception
     */
    public function getDataHomologacao()
    {
        return $this->getDataPorSituacao(new SituacaoLicitacao(SituacaoLicitacao::SITUACAO_HOMOLOGADA));
    }

    /**
     * @param SituacaoLicitacao $oSituacao
     *
     * @return DBDate|bool
     * @throws Exception
     */
    private function getDataPorSituacao(SituacaoLicitacao $oSituacao)
    {
        $aWhere = array(
            "l11_liclicita = {$this->iCodLicitacao}",
            "l11_licsituacao = {$oSituacao->getCodigo()}"
        );

        $oDaoSituacao = new cl_liclicitasituacao();
        $sSqlBuscaSituacao = $oDaoSituacao->sql_query_file(null, 'l11_data', null, implode(' and ', $aWhere));
        $rsBuscaSituacao = db_query($sSqlBuscaSituacao);
        if (!$rsBuscaSituacao) {
            throw new Exception("Ocorreu um erro ao buscar a situação da licitação.");
        }

        $mData = false;
        if (pg_num_rows($rsBuscaSituacao) > 0) {
            $mData = new DBDate(db_utils::fieldsMemory($rsBuscaSituacao, 0)->l11_data);
        }
        return $mData;
    }

    /**
     * Retorna uma coleção com os eventos da licitação
     * @return EventoLicitacao[]
     */
    public function getEventos()
    {
        if ($this->aEventos === null) {
            $aEventos = array();

            if (empty($this->iCodLicitacao)) {
                throw new ParameterException('Licitação não carregada.');
            }

            $oDaoEventos = new cl_liclicitaevento;
            $sWhere = "l46_liclicita = {$this->iCodLicitacao}";
            $sSqlEventos = $oDaoEventos->sql_query_file(null, 'l46_sequencial', null, $sWhere);
            $rsEventos = db_query($sSqlEventos);
            $iQuantidade = pg_num_rows($rsEventos);
            if ($rsEventos && $iQuantidade > 0) {
                for ($iRegistro = 0; $iRegistro < $iQuantidade; $iRegistro++) {
                    $iCodigo = db_utils::fieldsMemory($rsEventos, $iRegistro)->l46_sequencial;
                    $aEventos[] = new EventoLicitacao($iCodigo);
                }
            }
            $this->aEventos = $aEventos;
        }

        return $this->aEventos;
    }

    /**
     * @return int
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     */
    public function getFase()
    {
        if (empty($this->iFaseLicitacon)) {
            $sSiglaTipoCompraTribunal = $this->getModalidade()->getSiglaTipoCompraTribunal();
            if (in_array($sSiglaTipoCompraTribunal, array('PRD', 'PRI', 'RPO'))) {
                if (EventoLicitacao::possuiFaseEvento($this, EventoLicitacao::FASE_PUBLICACAO, 10)) {
                    $this->iFaseLicitacon = EventoLicitacao::FASE_PUBLICACAO;
                    return $this->iFaseLicitacon;
                }

                $this->iFaseLicitacon = EventoLicitacao::FASE_INTERNA;
                return $this->iFaseLicitacon;
            }

            $aWhere = array(
                "l11_liclicita = {$this->iCodLicitacao}",
                "l11_licsituacao in (" . SituacaoLicitacao::SITUACAO_ADJUDICADA . "," . SituacaoLicitacao::SITUACAO_HOMOLOGADA
                . ")"
            );

            $oDaoSituacao = new cl_liclicitasituacao();
            $sSqlSituacao = $oDaoSituacao->sql_query_file(null, "1", null, implode(' and ', $aWhere));
            $rsBuscaSituacao = db_query($sSqlSituacao);
            if (!$rsBuscaSituacao) {
                throw new DBException("Ocorreu um erro ao consultar a situaçao Adjudicada/Homologada.");
            }

            if (pg_num_rows($rsBuscaSituacao) > 0) {
                $this->iFaseLicitacon = EventoLicitacao::FASE_ADJUDICACAO_HOMOLOGACAO;
                return $this->iFaseLicitacon;
            }

            if ($this->hasFornecedor()
                || EventoLicitacao::possuiFaseEvento($this,
                    EventoLicitacao::FASE_HABILITACAO_PROPOSTAS,
                    6)
            ) {
                $this->iFaseLicitacon = EventoLicitacao::FASE_HABILITACAO_PROPOSTAS;
                return $this->iFaseLicitacon;
            }

            if (EventoLicitacao::possuiFaseEvento($this, EventoLicitacao::FASE_EDITAL_PUBLICADO, 11)) {
                $this->iFaseLicitacon = EventoLicitacao::FASE_EDITAL_PUBLICADO;
                return $this->iFaseLicitacon;
            }

            $this->iFaseLicitacon = EventoLicitacao::FASE_INTERNA;
        }
        return $this->iFaseLicitacon;
    }

    /**
     * Verifica se a licitação tem evento de encerramento. Por padrão, considera todos tipos de encerramento.
     *
     * @param boolean $lFracassado Considera eventos de encerramento fracassados como encerramento.
     * @param boolean $lRevogacao Considera eventos de revogação como encerramento.
     * @param boolean $lAnulada Considera eventos de anulação como encerramento.
     *
     * @return bool
     */
    public function encerrada($lFracassado, $lRevogacao, $lAnulada)
    {
        foreach ($this->getEventos() as $oEvento) {
            if ($oEvento->isEncerramentoSucesso()) {
                return true;
            }

            if ($lFracassado && $oEvento->isEncerramentoFracasso()) {
                return true;
            }

            if ($lRevogacao && $oEvento->isRevogacao()) {
                return true;
            }

            if ($lAnulada && $oEvento->isAnulacao()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retorna o código do orçamento da licitação, se houver
     * @return integer|null
     */
    public function getCodigoOrcamento()
    {
        if (empty($this->iCodLicitacao)) {
            throw new ParameterException("O Código da Licitação não foi informado.");
        }

        $oDaoOrcamento = new cl_pcorcam;
        $sWhere = "l20_codigo = {$this->iCodLicitacao}";
        $sSql = $oDaoOrcamento->sql_query_orcamento_licitacao("pc20_codorc", $sWhere);
        $rsOrcamento = db_query($sSql);

        if (!$rsOrcamento) {
            throw new DBException("Não foi possível buscar o orçamento da licitação.");
        }

        if (pg_num_rows($rsOrcamento) == 0) {
            return null;
        }

        return db_utils::fieldsMemory($rsOrcamento, 0)->pc20_codorc;
    }

    /**
     * Diz se a licitacao utiliza registro de preço.
     * @return bool
     * @throws BusinessException
     */
    public function usaRegistroDePreco()
    {
        if (empty($this->iCodLicitacao)) {
            throw new BusinessException("Licitação não carregada.");
        }

        return $this->lUsaRegistroDePreco;
    }

    /**
     * Retorna a data de abertura
     * return DBDate
     */
    public function getDataAbertura()
    {
        return $this->oDataAbertura;
    }

    /**
     * Retorna a hora de abertura
     * return string
     */
    public function getHoraAbertura()
    {
        return $this->sHoraAbertura;
    }

    /**
     * Valida se a modalidade da licitação é CHAMAMENTO PÚBLICO / CREDENCIAMENTO
     * @return boolean true se sim
     */
    public function isChamamentoPublicoComCredenciamento()
    {
        $oDaoLicitacao = new cl_liclicita();

        $sWhere = "     l20_codigo in ( {$this->iCodLicitacao} ) ";
        $sWhere .= " and l03_pctipocompratribunal = 54 ";
        $sSql = $oDaoLicitacao->sql_query_licitacao_encerramento(" distinct 1 ", $sWhere);
        $rs = db_query($sSql);

        if ($rs && pg_num_rows($rs) > 0) {
            return true;
        }

        return false;
    }

    public function obterTipoLicitacao()
    {
        return $this->obterAtributoDinamico('tipolicitacao');
    }

    public function obterTipoBeneficioMicroempresaEmpresaPequenoPorte()
    {
        return $this->obterAtributoDinamico('tipobeneficiomicroepp');
    }

    public function obterNovoRegimeExecucao()
    {
        return $this->obterTipoLicitacao() == 'MRE' ? $this->obterAtributoDinamico('regimeexecucao') : '';
    }

    private function obterAtributoDinamico($atributo)
    {
        $licitacaoAtributosDinamicos = new LicitacaoAtributosDinamicos;
        $licitacaoAtributosDinamicos->setCodigoLicitacao($this->getCodigo());
        return $licitacaoAtributosDinamicos->getAtributo($atributo);
    }

    public function obterNivelJulgamento()
    {
        $julgamento = new Julgamento($this->getTipoJulgamento());
        return $julgamento->getSigla();
    }

}