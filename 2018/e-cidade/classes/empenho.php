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

use ECidade\Financeiro\Empenho\Enum\HistoricoDocumento;
use ECidade\Financeiro\Empenho\Model;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\Reconhecimento;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("model/empenho/EmpenhoFinanceiro.model.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

/**
 * Model para controle de empenho (Notas liquidacao)
 * @package empenho
 * @author  Iuri Guntchnigg Revisão $Author: dbstephano.ramos $
 * @version $Revision: 1.246 $
 *
 */
class empenho {

    public  $erro_status      = null;

    public  $erro_msg         = null;

    public  $datausu          = null;

    private $anousu           = null;

    public  $aItensAnulados   = array();

    private $lRecriarReserva  = false;

    public  $iCredor          = null;

    public  $iNumRowsNotas    = 0;

    private $sCamposNotas     = "";

    private $lEncode          = false;

    private $iCodigoMovimento = null;

    public  $lSqlErro         = null;

    public  $sMsgErro         = '';

    private $iCodOrdem;

    /**
     * @var string
     */
    protected $competenciaLiquidacao='';

    /**
     *
     * @var stdClass
     */
    private $oDadosNota = null;

    function empenho() {

        if (!class_exists("cl_empempenho")) {
            require_once modification("classes/db_empempenho_classe.php");
        }
        if (!class_exists("lancamentoContabil")) {
            require_once modification("classes/lancamentoContabil.model.php");
        }
        if (!class_exists("cl_empnota")) {
            require_once modification("classes/db_empnota_classe.php");
        }
        if (!class_exists("db_utils")) {
            require_once modification("libs/db_utils.php");
        }
        if (!class_exists("cl_empempitem")) {
            require_once modification("classes/db_empempitem_classe.php");
        }
        if (!class_exists("cl_empelemento")) {
            require_once modification("classes/db_empelemento_classe.php");
        }
        if (!class_exists("services_json")) {
            require_once modification("libs/JSON.php");
        }
        $this->clempelemento = new cl_empelemento();
        $this->datausu       = date("Y-m-d", db_getsession("DB_datausu"));
        $this->anousu        = db_getsession("DB_anousu");
        $this->clempempenho  = new cl_empempenho();
        $this->sCamposNota   = "e69_codnota,e69_numero,e69_anousu,e50_codord,e60_numemp, e50_anousu,e69_dtnota,";
        $this->sCamposNota .= "e70_vlranu,e70_vlrliq,e70_valor,e53_vlrpag,m51_tipo,m51_codordem,";
        $this->sCamposNota .= "case when cgmordem.z01_numcgm is not null then cgmordem.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,";
        $this->sCamposNota .= "case when cgmordem.z01_nome is not null then cgmordem.z01_nome else cgm.z01_nome end as z01_nome,";
        $this->sCamposNota .= "case when cgmordem.z01_cgccpf is not null then cgmordem.z01_cgccpf else cgm.z01_cgccpf end as z01_cgccpf,";
        $this->sCamposNota .= "fc_valorretencaonota(e50_codord) as vlrretencao";

    }

    public function setDadosNota($oDadosNota) {

        $this->oDadosNota = $oDadosNota;
    }

    public function getDadosNota() {

        return $this->oDadosNota;
    }

    /**
     ** Metodo para set para a variavel recriar saldo
     ** @param boolean $lOpcao - true para recriar
     */
    function setRecriarSaldo($lOpcao) {

        if ($lOpcao) {
            $this->lRecriarReserva = true;
        } else {
            $this->lRecriarReserva = false;
        }
    }

    function getRecriarSaldo() {

        return $this->lRecriarReserva;
    }

    function setCredor($iCredor) {

        $this->iCredor = $iCredor;
    }

    function getCredor() {

        if ($this->iCredor != null) {
            return $this->iCredor;
        } else {
            return false;
        }

    }

    /**
     * @return string
     */
    public function getCompetenciaLiquidacao() {
        return $this->competenciaLiquidacao;
    }

    /**
     * @param string $competenciaLiquidacao
     */
    public function setCompetenciaLiquidacao($competenciaLiquidacao) {
        $this->competenciaLiquidacao = $competenciaLiquidacao;
    }



    function setEncode($lEncode) {

        $this->lEncode = $lEncode;
    }

    function getEncode() {

        return $this->lEncode;
    }

    function liquidar($numemp = "", $codele = "", $codnota = "", $valor = "", $historico = "", $sHistoricoOrdem = '') {

        if ($numemp == "" || $codele == "" || $codnota == "" || $valor == "") {
            $this->erro_status = '0';
            $this->erro_msg    = "Parametros faltando ($numemp,$codele,$codnota,$valor,$historico) ";

            return false;
        }

        // variaveis acessiveis nessa função
        global $o56_elemento, $e60_numemp, $e60_numcgm, $e60_anousu, $e60_coddot, $e60_codcom, $e64_vlrliq, $e60_vlrliq, $e64_codele, $e70_vlrliq, $e70_valor, $erro_msg;

        // busta empenho
        $clempempenho = new cl_empempenho;
        $res          = $clempempenho->sql_record($clempempenho->sql_query($numemp));
        if ($clempempenho->numrows > 0) {
            $oEmpenho = db_utils::fieldsMemory($res, 0);
        } else {

            $this->erro_status = '0';
            $this->erro_msg    = "Empempenho " . $clempempenho->erro_msg;

            return false;
        }

        $oDaoEmpNotaItem = db_utils::getDao("empnotaitem");
        $sCampos         = " exists ( select 1 from bensdispensatombamento where e139_empnotaitem = e72_sequencial ) as tem_dispensa, ";
        $sCampos .= " exists ( select 1 from empnotaitembenspendente where e137_empnotaitem = e72_sequencial ) as tem_pendente   ";
        $sWhere = " e72_codnota = {$codnota} ";

        $rsEmpNotaItem = db_query($oDaoEmpNotaItem->sql_query_file(null, $sCampos, null, $sWhere));
        if (!$rsEmpNotaItem || pg_num_rows($rsEmpNotaItem) == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Informações dos itens da nota não econtrado";

            return false;
        }
        $oDadosEmpNotaItem = db_utils::fieldsMemory($rsEmpNotaItem, 0);

        /*
     * verificamos se a data e maior ou igual a data do empenho.
     * caso a data  da sessao seje maior , nao podemods permitir a liquidação
     */

        if ((db_strtotime($this->datausu) < db_strtotime($oEmpenho->e60_emiss))
          || ($this->anousu < $oEmpenho->e60_anousu)
        ) {

            $this->erro_status = '0';
            $this->erro_msg    = "Data inválida. data da liquidação deve ser maior ou igual a data do empenho";

            return false;
        }

        // busta elemento da empelemento
        $clempelemento = new cl_empelemento;
        $res           = $clempelemento->sql_record($clempelemento->sql_query($numemp));
        if ($clempelemento->numrows > 0) {

            db_fieldsmemory($res, 0);

        } else {

            $this->erro_status = '0';
            $this->erro_msg    = "Empelemento " . $clempelemento->erro_msg;

            return false;
        }

        // busta nota
        $clempnotaele = new cl_empnotaele;
        $res          = $clempnotaele->sql_record($clempnotaele->sql_query($codnota, $codele));
        if ($clempnotaele->numrows > 0) {
            db_fieldsmemory($res, 0);
        } else {
            $this->erro_status = '0';
            $this->erro_msg    = "Empnotaele" . $clempnotaele->erro_msg;

            return false;
        }

        // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho
        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($numemp);

        $lIsPassivo          = $oEmpenhoFinanceiro->isEmpenhoPassivo();
        $isPrestacaoContas   = $oEmpenhoFinanceiro->isPrestacaoContas();
        $isAmortizacaoDivida = $oEmpenhoFinanceiro->isAmortizacaoDivida();
        $isPrecatoria        = $oEmpenhoFinanceiro->isPrecatoria();

        $iAnoSessao = db_getsession("DB_anousu");

        if ($e60_anousu < db_getsession("DB_anousu")) {

            $codteste = HistoricoDocumento::LIQUIDACAO_RP;
            if (self::ordemDeCompraManual($codnota)) {

                $lRegistroEntradaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::REGISTRO_ENTRADA_MATERIAL_VIA_RP);
                $lControleDespesaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MP_RP);
                $lControleLiquidacaoMaterialAlmoxarifado = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MAT_ALMOX);
                if ($lRegistroEntradaRP || $lControleDespesaRP || $lControleLiquidacaoMaterialAlmoxarifado) {
                    $codteste = HistoricoDocumento::LIQUIDACAO_RP_ESTOQUES_PATRIMONIO;
                }
            }

        } else {

            $codteste = HistoricoDocumento::LIQUIDACAO;

            if (USE_PCASP) {

                if ($lIsPassivo) {
                    $codteste = 84;
                }

                if ($isPrestacaoContas) {
                    $codteste = 412;
                }

                if ($isAmortizacaoDivida) {
                    $codteste = 506;
                }

                if ($isPrecatoria) {
                    $codteste = 502;
                }
                /**
                 * Verifico a que grupo o desdobramento do empenho pertence
                 * @var integer
                 */
                $oCodigoGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($oEmpenhoFinanceiro->getDesdobramentoEmpenho(),
                  $iAnoSessao);
                if ($oCodigoGrupoContaOrcamento && !$lIsPassivo && !$isPrestacaoContas) {
                    switch ($oCodigoGrupoContaOrcamento->getCodigo()) {

                        case 7 :
                            $codteste = HistoricoDocumento::LIQUIDACAO_DESPESA_COM_SERVICOS;
                            break;
                        case 8 :
                            $codteste = HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO;
                            break;
                        case 9 :

                            $codteste = HistoricoDocumento::LIQUIDACAO_AQUISICAO_MATERIAL_PERMANENTE;
                            if (!empty($oDadosEmpNotaItem) && $oDadosEmpNotaItem->tem_dispensa == 't') {
                                $codteste = HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO;
                            }
                            break;
                    }
                }

                if ($oEmpenhoFinanceiro->isProvisaoFerias()) {
                    $codteste = HistoricoDocumento::LIQUIDACAO_PROVISAO_FERIAS;
                }

                if ($oEmpenhoFinanceiro->isProvisaoDecimoTerceiro()) {
                    $codteste = HistoricoDocumento::LIQUIDACAO_PROVISAO_13_SALARIO;
                }
            }

            $documentosLancamentoControle = array(
                HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO,
                HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MP,
                HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MAT_ALMOX
            );
            $lPossuiControleEmLiqudacao = self::possuiLancamentoDeControle($numemp, $iAnoSessao, $documentosLancamentoControle);
            if (!$lPossuiControleEmLiqudacao && in_array($codteste, array(HistoricoDocumento::LIQUIDACAO_DESPESA_COM_SERVICOS, HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO))) {
                $codteste = HistoricoDocumento::LIQUIDACAO;
            }
        }

        $sql    = "select fc_verifica_lancamento(" . $numemp . ",'" . date("Y-m-d", db_getsession("DB_datausu")) . "',"
          . $codteste . "," . $valor . ")";
        $result = db_query($sql);
        $status = pg_result($result, 0, 0);
        if (substr($status, 0, 2) > 0) {

            $this->erro_msg    = substr($status, 3);
            $this->erro_status = '0';

            return false;
        }

        // - atualiza conlancamval
        $clempempenho->e60_numemp = $oEmpenho->e60_numemp;
        $clempempenho->e60_vlrliq = ($oEmpenho->e60_vlrliq + $valor);
        $res                      = $clempempenho->alterar($oEmpenho->e60_numemp);
        if ($clempempenho->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Empempenho " . $clempempenho->erro_msg;

            return false;
        }

        $clempelemento->e64_numemp = $oEmpenho->e60_numemp;
        $clempelemento->e64_codele = $e64_codele;
        $clempelemento->e64_vlrliq = ($e64_vlrliq + $valor);
        $res                       = $clempelemento->alterar($numemp, $codele);
        if ($clempelemento->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Empelemento " . $clempelemento->erro_msg;

            return false;
        }

        $clempnotaele->e70_codnota = $codnota;
        $clempnotaele->e70_codele  = $codele;
        $clempnotaele->e70_valor   = $e70_valor;
        $clempnotaele->e70_vlrliq  = ($e70_vlrliq + $valor);
        $res                       = $clempnotaele->alterar($codnota, $codele);
        if ($clempnotaele->erro_status == 0) {
            $this->erro_status = '0';
            $this->erro_msg    = "Empnotaele " . $clempnotaele->erro_msg;

            return false;
        }
        //atualizamos os valores liquidados do item.
        $clempnotaitem = $this->usarDao("empnotaitem", true);
        $rsItens       = $clempnotaitem->sql_record($clempnotaitem->sql_query_file(null,
          "*",
          null,
          "e72_codnota = {$codnota}"));
        if ($clempnotaitem->numrows > 0) {

            (int) $iNumRowsItens = $clempnotaitem->numrows;
            for ($iInd = 0; $iInd < $iNumRowsItens; $iInd++) {

                $oItens                        = db_utils::fieldsMemory($rsItens, $iInd);
                $clempnotaitem->e72_sequencial = $oItens->e72_sequencial;
                $clempnotaitem->e72_vlrliq     = $oItens->e72_valor;
                $clempnotaitem->alterar($oItens->e72_sequencial);
                if ($clempnotaitem->erro_status == 0) {

                    $this->erro_status = '0';
                    $this->erro_msg    = "Empnotaitem " . $clempnotaitem->erro_msg;

                    return false;

                }
            }
        }
        if (empty($sHistoricoOrdem)) {
            $sHistoricoOrdem = $historico;
        }
        $this->lancaOP($numemp, $codele, $codnota, $valor, null, $sHistoricoOrdem);


        if ($this->erro_status != '0') {

            if ($this->anousu == $oEmpenho->e60_anousu) {

                $substrElemento = substr($oEmpenho->o56_elemento, 0, 2);

                if ($substrElemento == '33') {
                    $documento = HistoricoDocumento::LIQUIDACAO;
                } else if ($substrElemento == '34') {
                    $documento = HistoricoDocumento::LIQUIDACAO_DESPESA_CAPITAL;
                }

                if (USE_PCASP) {

                    if ($lIsPassivo) {
                        $documento = HistoricoDocumento::LIQUIDACAO_EMPENHO_PASSIVO_SEM_SUP_ORCAMENT;
                    }

                    if ($isPrestacaoContas) {
                        $documento = HistoricoDocumento::LIQUIDACAO_SUPRIMENTO_FUNDOS;
                    }

                    if ($oCodigoGrupoContaOrcamento && !$lIsPassivo && !$isPrestacaoContas) {

                        switch ($oCodigoGrupoContaOrcamento->getCodigo()) {

                            case 7 :
                                $documento = HistoricoDocumento::LIQUIDACAO_DESPESA_COM_SERVICOS;
                                break;
                            case 8 :
                                $documento = HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO;
                                break;
                            case 9 :
                                $documento = HistoricoDocumento::LIQUIDACAO_AQUISICAO_MATERIAL_PERMANENTE;
                                if (!empty($oDadosEmpNotaItem) && $oDadosEmpNotaItem->tem_dispensa == 't') {
                                    $documento = HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO;
                                }
                                break;
                        }
                    }

                    if ($oEmpenhoFinanceiro->isProvisaoFerias()) {
                        $documento = HistoricoDocumento::LIQUIDACAO_PROVISAO_FERIAS;
                    }

                    if ($oEmpenhoFinanceiro->isProvisaoDecimoTerceiro()) {
                        $documento = HistoricoDocumento::LIQUIDACAO_PROVISAO_13_SALARIO;
                    }

                    if ($isAmortizacaoDivida) {
                        $documento = HistoricoDocumento::LIQUIDACAO_AMORT_DIVIDA;
                    }

                    if ($oEmpenhoFinanceiro->isPrecatoria()) {
                        $documento = HistoricoDocumento::LIQUIDACAO_PRECATORIOS;
                    }
                }

                $documentosLancamentoControle = array(
                    HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO,
                    HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MP,
                    HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MAT_ALMOX,
                    HistoricoDocumento::EMPENHO_SUPRIMENTO_FUNDOS
                );
                $documentosLiquidacaoDespesa = array(
                    HistoricoDocumento::LIQUIDACAO_DESPESA_COM_SERVICOS,
                    HistoricoDocumento::LIQUIDACAO_DESPESA_MATERIAL_CONSUMO
                );
                $lPossuiDocumento = self::possuiLancamentoDeControle($numemp, $iAnoSessao, $documentosLancamentoControle);

                if (!$lPossuiDocumento && in_array($documento, $documentosLiquidacaoDespesa)) {
                    $documento = HistoricoDocumento::LIQUIDACAO;
                }

            } else {

                $documento = HistoricoDocumento::LIQUIDACAO_RP;
                if (self::ordemDeCompraManual($codnota)) {

                    $lRegistroEntradaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::REGISTRO_ENTRADA_MATERIAL_VIA_RP);
                    $lControleDespesaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MP_RP);
                    $lControleLiquidacaoMaterialAlmoxarifado = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MAT_ALMOX);
                    if ($lRegistroEntradaRP || $lControleDespesaRP || $lControleLiquidacaoMaterialAlmoxarifado) {
                        $documento = HistoricoDocumento::LIQUIDACAO_RP_ESTOQUES_PATRIMONIO;
                    }
                }

                $oDaoExec = new \cl_conlancamemp();
                $sWhere ="c75_numemp = {$oEmpenhoFinanceiro->getNumero()} and c71_coddoc = 4010";
                $sqlBuscaocumento = $oDaoExec->sql_query_documentos(null,'c71_coddoc',null,$sWhere);
                $rsBuscaDocumento = db_query($sqlBuscaocumento);

                if (!$rsBuscaDocumento) {
                    throw new Exception('Ocorreu um erro ao consultar o documento 4010.');
                }

                if (pg_num_rows($rsBuscaDocumento) > 0) {

                    $codigoContrato = $oEmpenhoFinanceiro->getCodigoContrato();
                    $oAcordo = AcordoRepository::getByCodigo($codigoContrato);
                    $oRegimeCompetenciaRepository = new RegimeCompetencia();
                    $oRegimeCompetenciaContrato = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);

                    if ($oRegimeCompetenciaContrato !== null && !$oRegimeCompetenciaContrato->isDespesaAntecipada()) {
                        $documento = 333;
                    }
                }
            }

            try {

                /*[Extensao] - Controle Interno Parte 1*/

                if (USE_PCASP) {

                    $oPlanoContaOrcamento = new ContaOrcamento($e64_codele, $iAnoSessao, null, db_getsession("DB_instit"));
                    $oPlanoConta          = $oPlanoContaOrcamento->getPlanoContaPCASP();
                    if (empty($oPlanoConta)) {
                        throw new Exception("Conta do orçamento {$oPlanoContaOrcamento->getEstrutural()} no ano {$iAnoSessao}");
                    }
                } else {
                    $oPlanoConta = new ContaPlanoPCASP($e64_codele, $iAnoSessao, null, db_getsession("DB_instit"));
                }

                /**
                 * Realiza o reconhecimento das competências em aberto do contrato
                 */
                $oInstituicao = new Instituicao(db_getsession("DB_instit"));
                $oDataAtual   = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
                $iContrato    = $oEmpenhoFinanceiro->getCodigoContrato();
                $oParcela     = null;
                if (!empty($iContrato) && !$oEmpenhoFinanceiro->isRestoAPagar($oDataAtual->getAno())) {

                    $oAcordo                      = AcordoRepository::getByCodigo($iContrato);
                    $oRegimeCompetenciaRepository = new RegimeCompetencia();
                    $oRegimeCompetenciaContrato   = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
                    if (!empty($oRegimeCompetenciaContrato) && !$oRegimeCompetenciaContrato->isDespesaAntecipada()) {
                        if ($this->getCompetenciaLiquidacao() == '') {
                            throw new Exception("Competencia para liquidação deve ser informado");
                        }
                        $competencia      = DBCompetencia::createFromString($this->getCompetenciaLiquidacao());
                        $aReconhecimentos = Reconhecimento::getReconhecimentosAbertosAteCompetencia($oInstituicao, $competencia, $oAcordo);

                        if (($competencia->getAno() >= $oDataAtual->getAno()) && $competencia->getMes() > $oDataAtual->getMes()) {
                            throw new BusinessException("Não é possível liquidar uma parcela com competência superior a data da liquidação.");
                        }

                        foreach ($aReconhecimentos as $oReconhecimento) {
                            $oReconhecimento->processar($iAnoSessao);
                        }

                        $oParcela = $oRegimeCompetenciaRepository->getParcelaPorAcordoECompetencia($oAcordo, $competencia);
                    }
                }

                if ($documento == HistoricoDocumento::LIQUIDACAO) {
                    $liquidacaoModel = new Model\Liquidacao();
                    $sHistoricoOrdem = $liquidacaoModel->obterComplemento($oEmpenhoFinanceiro->getCodigoContrato(), $this->getCompetenciaLiquidacao(), $sHistoricoOrdem);
                }

                $oEventoContabil         = new EventoContabil($documento, $iAnoSessao);
                $aLancamentosCadastrados = $oEventoContabil->getEventoContabilLancamento();
                $oLancamentoAuxiliar     = new LancamentoAuxiliarEmpenhoLiquidacao();
                $oLancamentoAuxiliar->setObservacaoHistorico($sHistoricoOrdem);
                $oLancamentoAuxiliar->setCodigoElemento($e64_codele);
                $oLancamentoAuxiliar->setCodigoContaPlano($oPlanoConta->getReduzido());
                $oLancamentoAuxiliar->setCodigoNotaLiquidacao($codnota);
                $oLancamentoAuxiliar->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
                $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
                $oLancamentoAuxiliar->setValorTotal($valor);
                $oLancamentoAuxiliar->setHistorico($aLancamentosCadastrados[0]->getHistorico());
                $oLancamentoAuxiliar->setCodigoOrdemPagamento($this->iPagOrdem);
                $oLancamentoAuxiliar->setNumeroEmpenho($oEmpenhoFinanceiro->getNumero());
                $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
                if (!empty($oParcela)) {
                    $oLancamentoAuxiliar->setParcelaRegimeDeCompetencia($oParcela);
                }

                $oLancamentoAuxiliar->setCaracteristicaPeculiarCredito($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
                $oLancamentoAuxiliar->setCaracteristicaPeculiarDebito($oEmpenhoFinanceiro->getCaracteristicaPeculiar());

                $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
                $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
                $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
                $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

                $oEventoContabil->executaLancamento($oLancamentoAuxiliar);

                /* #1 - modification: ContratosPADRS */

                /**
                 * Lancamentos do contrato:
                 */
                if (USE_PCASP && ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataAtual, $oInstituicao)) {

                    $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
                    $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null,
                      "e100_acordo",
                      null,
                      "e100_numemp = {$oEmpenhoFinanceiro->getNumero()}");
                    $rsContrato          = $oDaoEmpenhoContrato->sql_record($sSqlContrato);
                    if (!$this->lSqlErro && $oDaoEmpenhoContrato->numrows > 0) {

                        $oAcordo                   = new Acordo(db_utils::fieldsMemory($rsContrato, 0)->e100_acordo);
                        $oEventoContabilAcordo     = new EventoContabil(HistoricoDocumento::CONTROLE_EXECUCAO_CONTRATO, $iAnoSessao);
                        $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
                        $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
                        $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
                        $oLancamentoAuxiliarAcordo->setValorTotal($valor);
                        $oLancamentoAuxiliarAcordo->setObservacaoHistorico($sHistoricoOrdem);

                        $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                        $oContaCorrenteDetalhe->setAcordo($oAcordo);
                        $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
                        $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrenteDetalhe);


                        $oEventoContabilAcordo->executaLancamento($oLancamentoAuxiliarAcordo);
                    }
                }

            } catch (Exception $e) {

                $this->erro_status = '0';
                $this->erro_msg    = $e->getMessage();

                return false;
            }
        }

        return true;
    }

    /**
     * estorna liquidação
     */
    function estornaLiq($numemp = "", $codele = "", $codnota = "", $valor = "", $historico = "") {

        if ($numemp == "" || $codele == "" || $codnota == "" || $valor == "") {

            $this->erro_status = '0';
            $this->erro_msg    = "Parametros faltando ($numemp,$codele,$codnota,$valor,$historico) ";

            return false;
        }

        /** [Extensao - Controle Interno - Estorno de Liquidação] */


        // variaveis acessiveis nessa função
        global $o56_elemento, $e60_numemp, $e60_numcgm, $e60_anousu, $e60_coddot, $e60_codcom, $e64_vlrliq, $e60_vlrliq, $e64_codele, $e70_vlrliq, $e70_valor, $erro_msg;

        // busca empenho
        $clempempenho = new cl_empempenho;
        $res          = $clempempenho->sql_record($clempempenho->sql_query($numemp));
        if ($clempempenho->numrows > 0) {
            $oEmpenho = db_utils::fieldsMemory($res, 0);
        } else {

            $this->erro_status = '0';
            $this->erro_msg    = "Empempenho " . $clempempenho->erro_msg;

            return false;

        }

        $oDaoEmpNotaItem = db_utils::getDao("empnotaitem");
        $sCampos         = " exists ( select 1 from bensdispensatombamento where e139_empnotaitem = e72_sequencial ) as tem_dispensa, ";
        $sCampos .= " exists ( select 1 from empnotaitembenspendente where e137_empnotaitem = e72_sequencial ) as tem_pendente   ";
        $sWhere = " e72_codnota = {$codnota} ";

        $rsEmpNotaItem = db_query($oDaoEmpNotaItem->sql_query_file(null, $sCampos, null, $sWhere));
        if (!$rsEmpNotaItem || pg_num_rows($rsEmpNotaItem) == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Informações dos itens da nota não econtrado";

            return false;
        }
        $oDadosEmpNotaItem = db_utils::fieldsMemory($rsEmpNotaItem, 0);

        /*
     * Verificamos a data da sessao. se for maior que a data da nota, nao podemos realizare
     * a operação;
     */
        if (db_strtotime($this->datausu) < db_strtotime($oEmpenho->e60_emiss)
          || ($this->anousu < $oEmpenho->e60_anousu)
        ) {

            $this->erro_status = '0';
            $this->erro_msg    = "Data inválida. data do estorno deve ser maior ou igual que a data do empenho";

            return false;

        }
        // busca elemento da empelemento
        $clempelemento = new cl_empelemento;
        $res           = $clempelemento->sql_record($clempelemento->sql_query_file($numemp));
        if ($clempelemento->numrows > 0) {
            db_fieldsmemory($res, 0);
        } else {

            $this->erro_status = '0';
            $this->erro_msg    = "Empelemento " . $clempelemento->erro_msg;

            return false;

        }

        // busca nota
        $clempnotaele = new cl_empnotaele;
        $res          = $clempnotaele->sql_record($clempnotaele->sql_query($codnota, $codele));
        if ($clempnotaele->numrows > 0) {
            db_fieldsmemory($res, 0);
        } else {
            $this->erro_status = '0';
            $this->erro_msg    = "Empnotaele" . $clempnotaele->erro_msg;

            return false;
        }

        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($numemp);

        // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho
        if ($e60_anousu < db_getsession("DB_anousu")) {


            $codteste = "34";
            if (self::ordemDeCompraManual($codnota)) {
                $lRegistroEntradaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(212);
                $lControleDespesaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(214);
                $lControleLiquidacaoMaterialAlmoxarifado = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(213);
                if ($lRegistroEntradaRP || $lControleDespesaRP || $lControleLiquidacaoMaterialAlmoxarifado) {
                    $codteste = "40";
                }
            }


        } else {

            $codteste = "4";
            /**
             * Verificamos se é um empenho passivo. Caso seja, o documento a ser executado é o 84
             */


            $lIsPassivo = $oEmpenhoFinanceiro->isEmpenhoPassivo();
            if ($lIsPassivo) {
                $codteste = 85;
            }

            $isPrestacaoContas = $oEmpenhoFinanceiro->isPrestacaoContas();
            if ($isPrestacaoContas) {
                $codteste = 413;
            }

            /**
             * Verifico a que grupo o desdobramento do empenho pertence
             * @var integer
             */
            $iAnoSessao                 = db_getsession("DB_anousu");
            $oCodigoGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($oEmpenhoFinanceiro->getDesdobramentoEmpenho(),
              $iAnoSessao);
            if ($oCodigoGrupoContaOrcamento) {
                switch ($oCodigoGrupoContaOrcamento->getCodigo()) {

                    case 7 :
                        $codteste = 203;
                        break;
                    case 8 :
                        $codteste = 205;
                        break;
                    case 9 :
                        $codteste = 207;
                        if (!empty($oDadosEmpNotaItem) && $oDadosEmpNotaItem->tem_dispensa == 't') {
                            $codteste = 205;
                        }
                        break;
                    default:
                        $codteste = $codteste;
                }
            }

            $isProvisaoFerias         = $oEmpenhoFinanceiro->isProvisaoFerias();
            $isProvisaoDecimoTerceiro = $oEmpenhoFinanceiro->isProvisaoDecimoTerceiro();
            $isAmortizacaoDivida      = $oEmpenhoFinanceiro->isAmortizacaoDivida();
            $isPrecatoria             = $oEmpenhoFinanceiro->isPrecatoria();
            /**
             * ESTORNO DA LIQUIDAÇÃO DA PROVISÃO DE FÉRIAS
             */
            if ($isProvisaoFerias) {
                $c71_coddoc = 307;
            }
            /**
             * ESTORNO DA LIQUIDAÇÃO DA PROVISÃO DE 13º SALÁRIO
             */
            if ($isProvisaoDecimoTerceiro) {
                $c71_coddoc = 311;
            }

            /**
             * ESTORNO LIQUIDAÇÃO AMORT. DIVIDA
             */
            if ($isAmortizacaoDivida) {
                $c71_coddoc = 507;
            }

            /**
             * ESTORNO DA LIQUIDAÇÃO DE PRECATÓRIOS
             */
            if ($isPrecatoria) {
                $codteste = 503;
            }

            if (!cl_translan::possuiLancamentoDeControle($numemp, false)) {
                $codteste = 4;
            }

        }


        $sql    = "select fc_verifica_lancamento(" . $numemp . ",'" . date("Y-m-d", db_getsession("DB_datausu")) . "',"
          . $codteste . "," . $valor . ") as teste";
        $result = db_query($sql);
        $status = pg_result($result, 0, "teste");
        if (substr($status, 0, 2) > 0) {

            $this->erro_msg    = "Validação (codigo: fc_verifica_lançamento) " . substr($status, 3);
            $this->erro_status = '0';

            return false;
        }
        // alterações na base de dados
        // - atualiza empenho
        // - atualiza empelemento
        // - atualiza empnotaele
        // - atualiza conlancam
        // - atualiza conlancamcompl [texto complementar]
        // - atualiza conlancamele
        // - atualiza conlancamnota
        // - atualiza conlancamcgm
        // - atualiza conlancamemp
        // - atualiza conlancamdoc
        // - atualiza conlancamdot [exceto RP]
        // - atualiza conlancamval

        $clempempenho1             = new cl_empempenho;
        $clempempenho1->e60_numemp = $oEmpenho->e60_numemp;
        $clempempenho1->e60_vlrliq = "$oEmpenho->e60_vlrliq - $valor";
        $res                       = $clempempenho1->alterar($oEmpenho->e60_numemp);
        if ($clempempenho1->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Empempenho " . $clempempenho1->erro_msg;

            return false;
        }

        $clempelemento1             = new cl_empelemento;
        $clempelemento1->e64_numemp = $oEmpenho->e60_numemp;
        $clempelemento1->e64_codele = $e64_codele;
        $clempelemento1->e64_vlrliq = "$e64_vlrliq - $valor";
        $res                        = $clempelemento1->alterar($numemp, $codele);
        if ($clempelemento1->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Empelemento " . $clempelemento1->erro_msg;
            db_msgbox($this->erro_msg);

            return false;
        }
        /*
       buscamos informacao da ordem.
       caso a ordem seja virtual, devemos zerar o valor liquidado, e lancar o valor da nota
       como anulado;
     */
        $clempnotaord = $this->usarDao("empnotaord", true);
        $rsEmpNotaOrd = $clempnotaord->sql_record($clempnotaord->sql_query($codnota));
        //Verificamos se a nota de liquidacao está agendada. caso sim, nao pode  estornar a liquidação;
        $oEmpNotaOrd                = db_utils::fieldsMemory($rsEmpNotaOrd, 0);
        $clempnotaele1              = new cl_empnotaele;
        $clempnotaele1->e70_codnota = $codnota;
        $clempnotaele1->e70_codele  = $codele;
        $clempnotaele1->e70_vlrliq  = "$e70_vlrliq - $valor";

        if ($oEmpNotaOrd->m51_tipo == 2) {
            $clempnotaele1->e70_vlranu = "$valor";
        }

        $res = $clempnotaele1->alterar($codnota, $codele);
        if ($clempnotaele1->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Empnotaele " . $clempnotaele1->erro_msg;

            return false;
        }

        $clempnotaitem = $this->usarDao("empnotaitem", true);
        $rsItens       = $clempnotaitem->sql_record($clempnotaitem->sql_query_ordemCompra(null,
          "*",
          null,
          "e72_codnota = {$codnota}"));
        if ($clempnotaitem->numrows > 0) {

            (int) $iNumRowsItens = $clempnotaitem->numrows;
            for ($iInd = 0; $iInd < $iNumRowsItens; $iInd++) {

                $oItens                        = db_utils::fieldsMemory($rsItens, $iInd);
                $clempnotaitem->e72_sequencial = $oItens->e72_sequencial;
                if ($oItens->m51_tipo == 2) {
                    $clempnotaitem->e72_vlranu = $oItens->e72_valor;
                } else if ($oItens->m51_tipo == 1) {

                    $clempnotaitem->e72_vlrliq = '0';
                    $clempnotaitem->e72_vlranu = '0';
                }
                $clempnotaitem->alterar($oItens->e72_sequencial);
                if ($clempnotaitem->erro_status == 0) {

                    $this->erro_status = '0';
                    $this->erro_msg    = "Empnotaitem " . $clempnotaitem->erro_msg;

                    return false;

                }
            }
        }
        $documento = null;
        if ($this->anousu == $oEmpenho->e60_anousu) {
            if (substr($oEmpenho->o56_elemento, 0, 2) == '33') {
                $documento = HistoricoDocumento::ANULACAO_LIQUIDACAO;
            } else {
                if (substr($oEmpenho->o56_elemento, 0, 2) == '34') {
                    $documento = HistoricoDocumento::ANULACAO_LIQUIDACAO_CAPITAL;
                }
            }

            if (USE_PCASP) {

                if ($lIsPassivo) {
                    $documento = HistoricoDocumento::ESTORNO_LIQ_EMP_PASSIVO_SEM_SUP_ORCAMENT;
                }

                if ($isPrestacaoContas) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_SUPRIMENTO_FUNDOS;
                }

                if ($oCodigoGrupoContaOrcamento) {
                    switch ($oCodigoGrupoContaOrcamento->getCodigo()) {

                        case 7 :
                            $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_DESPESA_COM_SERVICOS;
                            break;
                        case 8 :
                            $documento = HistoricoDocumento::ESTORNO_LIQ_DESPESA_MATERIAL_CONSUMO;
                            break;
                        case 9 :
                            $documento = HistoricoDocumento::ESTORNO_LIQ_AQ_MATERIAL_PERMANENTE;
                            if (!empty($oDadosEmpNotaItem) && $oDadosEmpNotaItem->tem_dispensa == 't') {
                                $documento = HistoricoDocumento::ESTORNO_LIQ_DESPESA_MATERIAL_CONSUMO;
                            }
                            break;
                    }
                }

                if ($isProvisaoFerias) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_PROVISAO_FERIAS;
                }

                if ($isProvisaoDecimoTerceiro) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_PROVISAO_13_SALARIO;
                }

                if ($isAmortizacaoDivida) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_AMORT_DIVIDA;
                }

                if ($isPrecatoria) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_PRECATORIOS;
                }
            }

            $aDocumentosControleLiquidacao = array(HistoricoDocumento::ESTORNO_LIQUIDACAO_DESPESA_COM_SERVICOS, HistoricoDocumento::ESTORNO_LIQ_DESPESA_MATERIAL_CONSUMO);
            if (!cl_translan::possuiLancamentoDeControle($oEmpenhoFinanceiro->getNumero(), false)
              && in_array($documento,
                $aDocumentosControleLiquidacao)
            ) {
                $documento = HistoricoDocumento::ANULACAO_LIQUIDACAO;
            }

        } else {

            $documento                               = HistoricoDocumento::ANULACAO_LIQUIDACAO_RP;
            if (self::ordemDeCompraManual($codnota)) {
                $lRegistroEntradaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::REGISTRO_ENTRADA_MATERIAL_VIA_RP);
                $lControleDespesaRP                      = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::CONTROLE_DESPESA_LIQUIDACAO_MP_RP);
                $lControleLiquidacaoMaterialAlmoxarifado = $oEmpenhoFinanceiro->empenhoRestosPagarPorDocumento(HistoricoDocumento::ESTORNO_REGISTRO_ENTRADA_MATERIAL_VIA_RP);
                if ($lRegistroEntradaRP || $lControleDespesaRP || $lControleLiquidacaoMaterialAlmoxarifado) {
                    $documento = HistoricoDocumento::ESTORNO_LIQUIDACAO_RP_ESTOQUES_PATRIMONIO;
                }
            }

            $oDaoExec = new \cl_conlancamemp();
            $sWhere ="c75_numemp = {$oEmpenhoFinanceiro->getNumero()} and c71_coddoc = 333";
            $sqlBuscaocumento = $oDaoExec->sql_query_documentos(null,'c71_coddoc',null,$sWhere);
            $rsBuscaDocumento = db_query($sqlBuscaocumento);

            if (!$rsBuscaDocumento) {
                throw new Exception('Ocorreu um erro ao consultar o documento 4010.');
            }

            if (pg_num_rows($rsBuscaDocumento) > 0) {

                $codigoContrato = $oEmpenhoFinanceiro->getCodigoContrato();
                $oAcordo = AcordoRepository::getByCodigo($codigoContrato);
                $oRegimeCompetenciaRepository = new RegimeCompetencia();
                $oRegimeCompetenciaContrato = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);

                if ($oRegimeCompetenciaContrato !== null && !$oRegimeCompetenciaContrato->isDespesaAntecipada()) {
                    $documento = 334;
                }
            }
        }

        try {

            $oNota = db_utils::fieldsmemory($this->getNotas($oEmpenho->e60_numemp, "e69_codnota = $codnota", false), 0);
            /**
             * Verificamos se a nota não está processada para a sefip.
             */
            $oDaoLancamentosAutonomos = db_utils::getDao("rhautonomolanc");
            $sSqlVerificaAutonomos    = $oDaoLancamentosAutonomos->sql_query_file(null,
              "rh89_anousu,
                                                                               rh89_mesusu,
                                                                               rh89_valorretinss",
              null,
              "rh89_codord={$oNota->e50_codord}");
            $rsVerificaAutonomos      = $oDaoLancamentosAutonomos->sql_record($sSqlVerificaAutonomos);
            if ($oDaoLancamentosAutonomos->numrows > 0) {

                $sMsgNotasVinculadas = "Estorno da liquidação das notas selecionadas não foram efetuadas.\n";
                $sMsgNotasVinculadas .= "As notas abaixo estão vinculadas a geração da Sefip:\n";
                $aNotasVinculadas = db_utils::getCollectionByRecord($rsVerificaAutonomos);
                foreach ($aNotasVinculadas as $oNotaVinculada) {

                    $sMsgNotasVinculadas .= "OP : {$oNota->e50_codord}  ";
                    $sMsgNotasVinculadas .= "Período: {$oNotaVinculada->rh89_mesusu}/{$oNotaVinculada->rh89_anousu} ";
                    $sMsgNotasVinculadas .= "Valor INSS: " . db_formatar($oNotaVinculada->rh89_valorretinss, "f") . "\n";
                }
                $sMsgNotasVinculadas .= "Entre em contato com o setor de Recursos Humanos para procederem ";
                $sMsgNotasVinculadas .= "com o cancelamento da Sefip.";
                unset($oNotaVinculada);
                unset($aNotasVinculadas);
                throw new Exception($sMsgNotasVinculadas);
            }

            $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenho->e60_numemp);
            $iAnoSessao         = db_getsession("DB_anousu");
            $oPlanoConta        = null;
            if (USE_PCASP) {

                $oPlanoConta = new ContaOrcamento($e64_codele, $iAnoSessao, null, db_getsession("DB_instit"));
                $oPlanoConta = $oPlanoConta->getPlanoContaPCASP();

            } else {
                $oPlanoConta = new ContaPlanoPCASP($e64_codele, $iAnoSessao, null, db_getsession("DB_instit"));
            }

            if (empty($oPlanoConta)) {
                $sNumeroEmpenho = $oEmpenhoFinanceiro->getCodigo() . "/" . $oEmpenhoFinanceiro->getAno();
                throw new Exception("Conta Orçamentária vinculada ao Empenho {$sNumeroEmpenho} não localizada.");
            }

            $oDataAtual   = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
            $iContrato    = $oEmpenhoFinanceiro->getCodigoContrato();
            $oParcela     = null;
            if (!empty($iContrato) && !$oEmpenhoFinanceiro->isRestoAPagar($oDataAtual->getAno())) {

                $oAcordo = AcordoRepository::getByCodigo($iContrato);
                $oRegimeCompetenciaRepository = new RegimeCompetencia();
                $oRegimeCompetenciaContrato = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
                if (!empty($oRegimeCompetenciaContrato) && !$oRegimeCompetenciaContrato->isDespesaAntecipada()) {
                    if ($this->getCompetenciaLiquidacao() == '') {
                        throw new Exception("Competencia para liquidação deve ser informado");
                    }
                    $competencia      = DBCompetencia::createFromString($this->getCompetenciaLiquidacao());
                    $oParcela = $oRegimeCompetenciaRepository->getParcelaPorAcordoECompetencia($oAcordo, $competencia);
                    if (($competencia->getAno() >= $oDataAtual->getAno()) && $competencia->getMes() > $oDataAtual->getMes()) {
                        throw new BusinessException("Não é possível liquidar uma parcela com competência superior a data da liquidação.");
                    }
                }
            }


            $oEventoContabil         = new EventoContabil($documento, $iAnoSessao);
            $aLancamentosCadastrados = $oEventoContabil->getEventoContabilLancamento();
            $oLancamentoAuxiliar     = new LancamentoAuxiliarEmpenhoLiquidacao();
            $oLancamentoAuxiliar->setObservacaoHistorico($historico);
            $oLancamentoAuxiliar->setCodigoElemento($e64_codele);
            $oLancamentoAuxiliar->setCodigoContaPlano($oPlanoConta->getReduzido());
            $oLancamentoAuxiliar->setCodigoNotaLiquidacao($codnota);
            $oLancamentoAuxiliar->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
            $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
            $oLancamentoAuxiliar->setValorTotal($valor);
            $oLancamentoAuxiliar->setHistorico($aLancamentosCadastrados[0]->getHistorico());
            $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
            $oLancamentoAuxiliar->setCodigoOrdemPagamento($oNota->e50_codord);
            $oLancamentoAuxiliar->setNumeroEmpenho($oEmpenhoFinanceiro->getNumero());
            $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
            $oLancamentoAuxiliar->setCaracteristicaPeculiarCredito($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
            $oLancamentoAuxiliar->setCaracteristicaPeculiarDebito($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
            if (!empty($oParcela)) {
                $oLancamentoAuxiliar->setParcelaRegimeDeCompetencia($oParcela);
            }

            $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
            $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
            $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
            $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
            $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

            $oEventoContabil->executaLancamento($oLancamentoAuxiliar);

            /* #2 - modification: ContratosPADRS */

        } catch (Exception $e) {

            $this->erro_status = '0';
            $this->erro_msg    = $e->getMessage();

            return false;
        }

        /**
         * Lancamentos do contrato:
         */
        $oDataImplantacao = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
        $oInstituicao     = new Instituicao(db_getsession('DB_instit'));

        if (USE_PCASP && ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataImplantacao, $oInstituicao)) {

            $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
            $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null,
              "e100_acordo",
              null,
              "e100_numemp = {$oEmpenho->e60_numemp}");

            $rsContrato = $oDaoEmpenhoContrato->sql_record($sSqlContrato);

            if (!$this->lSqlErro && $oDaoEmpenhoContrato->numrows > 0) {

                try {

                    $oAcordo                   = new Acordo(db_utils::fieldsMemory($rsContrato, 0)->e100_acordo);
                    $oEventoContabilAcordo     = new EventoContabil(904, $iAnoSessao);
                    $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
                    $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
                    $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
                    $oLancamentoAuxiliarAcordo->setValorTotal($valor);
                    $oLancamentoAuxiliarAcordo->setObservacaoHistorico($historico);

                    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                    $oContaCorrenteDetalhe->setAcordo($oAcordo);
                    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
                    $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

                    $oEventoContabilAcordo->executaLancamento($oLancamentoAuxiliarAcordo);

                } catch (Exception $e) {

                    $this->erro_status = '0';
                    $this->erro_msg    = $e->getMessage();

                    return false;
                }
            }
        }

        return true;
    }

    /**
     *  gera registros como se fosse ordem de pagamento (OP)
     *
     */
    private function lancaOP($numemp = "", $codele = "", $codnota = "", $valor = "", $retencoes = "", $historico) {

        if ($numemp == "" || $codele == "" || $codnota == "" || $valor == "") {
            $this->erro_status = '0';
            $this->erro_msg    = "Parametros faltando ($numemp,$codele,$codnota,$valor,$retencoes) ";

            return false;
        }
        // variaveis acessiveis nessa função
        global $e60_numemp, $e71_codord, $key, $value;

        $clpagordemnota = new cl_pagordemnota;
        $res            = $clpagordemnota->sql_record($clpagordemnota->sql_query_file(null, $codnota));
        // se a OP não existe, lança uma op para a nota
        $clpagordem                 = new cl_pagordem;
        $clpagordem->e50_codord     = "";
        $clpagordem->e50_numemp     = $e60_numemp;
        $clpagordem->e50_data       = date("Y-m-d", db_getsession("DB_datausu"));
        $clpagordem->e50_obs        = $historico;
        $clpagordem->e50_id_usuario = db_getsession("DB_id_usuario");
        $clpagordem->e50_hora       = date("H:m", db_getsession("DB_datausu"));
        $clpagordem->e50_anousu     = $this->anousu;
        $clpagordem->incluir($clpagordem->e50_codord);
        if ($clpagordem->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Pagordem:" . $clpagordem->erro_msg;

            return false;

        }
        //inclui elemento.
        $clpagordemele             = new cl_pagordemele;
        $clpagordemele->e53_codord = $clpagordem->e50_codord;
        $clpagordemele->e53_codele = $codele;
        $clpagordemele->e53_valor  = $valor;
        $clpagordemele->e53_vlranu = '0.00';
        $clpagordemele->e53_vlrpag = '0.00';
        $clpagordemele->incluir($clpagordemele->e53_codord, $clpagordemele->e53_codele);
        if ($clpagordemele->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Pagordemele:" . $clpagordemele->erro_msg;

            return false;

        }
        $clpagordemnota              = new cl_pagordemnota;
        $clpagordemnota->e71_codord  = $clpagordem->e50_codord;
        $clpagordemnota->e71_codnota = $codnota;
        $clpagordemnota->e71_anulado = 'false';
        $clpagordemnota->incluir($clpagordemnota->e71_codord, $clpagordemnota->e71_codnota);
        $this->iCodOrdem = $clpagordem->e50_codord;
        if ($clpagordemnota->erro_status == 0) {

            $this->erro_status = '0';
            $this->erro_msg    = "Pagordemnota:" . $clpagordemnota->erro_msg;

            return false;

        }
        //}
        //se foi setado algum credor para essa ordem, gravamos na pagordemconta

        if ($this->getCredor()) {

            $clpagordemconta             = $this->usarDao("pagordemconta", true);
            $clpagordemconta->e49_codord = $clpagordem->e50_codord;
            $clpagordemconta->e49_numcgm = $this->getCredor();
            $clpagordemconta->incluir($clpagordem->e50_codord);
            if ($clpagordemconta->erro_status == 0) {

                $this->erro_status = '0';
                $this->erro_msg    = "Pagordemconta:" . $clpagordemconta->erro_status;

                return false;
            }

        }

        /*
     * Caso o usuário marcou que devemos agendar automaticamente
     * a nota liquidada, fizemos o lancamento.
     */
        $clempparametro = $this->usarDao("empparametro", true);
        $rsParametros   = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"), "*"));
        if ($clempparametro->numrows > 0) {
            $oParametros = db_utils::fieldsMemory($rsParametros, 0);
        } else {
            throw new Exception("Erro [1] - Não foi possível encontrar parametros do empenho para o ano.");
        }
        if (isset($oParametros->e30_agendaautomatico) && $oParametros->e30_agendaautomatico == "t") {

            require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
            $oAgenda = new agendaPagamento();
            $oAgenda->setCodigoAgenda($oAgenda->newAgenda());
            //Criamos o objeto da nota, que sera agendada.

            $sSqlConcarpeculiar     = $this->clempempenho->sql_query_file($e60_numemp, "e60_concarpeculiar");
            $rsConcarpeculiar       = $this->clempempenho->sql_record($sSqlConcarpeculiar);
            $oNota                  = new stdClass;
            $oNota->iNumEmp         = $numemp;
            $oNota->iCodNota        = $clpagordem->e50_codord;
            $oNota->nValor          = $valor;
            $oNota->iCodTipo        = null;
            $oNota->iConcarPeculiar = db_utils::fieldsMemory($rsConcarpeculiar, 0)->e60_concarpeculiar;

            try {

                $this->iCodigoMovimento = $oAgenda->addMovimentoAgenda(1, $oNota);

            } catch (Exception $eErroNota) {

                $this->erro_status = '0';
                $this->erro_msg    = $eErroNota->getMessage();

            }

            //incluimos as retencoes da nota.
            require_once(modification('model/retencaoNota.model.php'));
            try {

                $oRetencaoNota = new retencaoNota($codnota);
                $oRetencaoNota->setInSession(true);
                $oRetencaoNota->setCodigoMovimento($this->iCodigoMovimento);
                $oRetencaoNota->salvar($clpagordem->e50_codord);

            } catch (Exception $eErro) {

                $this->erro_status = '0';
                $this->erro_msg    = $eErro->getMessage();

            }
        }
        $this->iPagOrdem = $clpagordem->e50_codord;

        return true;
    }

    /**
     * quando estornar a nota de liquidação
     * colocar a pagordemele com valor liquidado =0 e valor anulado =valor
     * e colocar pagordemelenota com valor anulado =true
     *
     */

    function estornaOP($numemp = "", $codele = "", $codnota = "", $valor = "", $retencoes = "", $historico) {

        if ($numemp == "" || $codele == "" || $codnota == "" || $valor == "") {

            $this->erro_status = '0';
            $this->erro_msg    = "Parametros faltando ($numemp,$codele,$codnota,$valor,$retencoes) ";

            return false;

        }
        // variaveis acessiveis nessa função
        global $e60_numemp, $e71_codord;

        $clpagordemnota = new cl_pagordemnota;
        $res            = $clpagordemnota->sql_record($clpagordemnota->sql_query(null,
          null,
          "*",
          null,
          "e71_codnota = {$codnota} and e71_anulado is false"));

        if ($clpagordemnota->numrows > 0) {

            $oNota = db_utils::fieldsmemory($res, 0);
            /*
       * Verificamos a data da sessao. se for maior que a data da nota, nao podemos realizare
       * a operação;
       */
            if (db_strtotime($this->datausu) < db_strtotime($oNota->e50_data)) {

                $this->erro_status = '0';
                $this->erro_msg    = "Data inválida. data do estorno deve ser maior ou igual que a data da nota de liquidação";

                return false;

            }
            /*
       * Verificamos se a nota nao possui movimento  na agenda configurado
       * caso ela possua, devemos cancelar  o estorno da liquicao
       */
            $sSqlMov = "select e97_codforma,";
            $sSqlMov .= "       e96_descr,    ";
            $sSqlMov .= "       e90_codgera, ";
            $sSqlMov .= "       e81_codmov, ";
            $sSqlMov .= "       e91_cheque ";
            $sSqlMov .= "  from empord";
            $sSqlMov .= "       inner join empagemov      on e82_codmov   = e81_codmov";
            $sSqlMov .= "       inner join empagemovforma on e81_codmov   = e97_codmov";
            $sSqlMov .= "       inner join empageforma    on e97_codforma = e96_codigo";
            $sSqlMov .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
            $sSqlMov .= "       left  join empageconfgera on e81_codmov   = e90_codmov";
            $sSqlMov .= " where e82_codord = {$oNota->e71_codord}";
            $sSqlMov .= "   and e81_cancelado is null";
            $rsMov = db_query($sSqlMov);
            if ($rsMov && pg_num_rows($rsMov) > 0) {

                $aMovimentos = db_utils::getCollectionByRecord($rsMov);
                $sMsgErro    = "Não foi possível estornar a liquidação da nota {$oNota->e69_numero}!\n";
                $sMsgErro .= "A OP {$oNota->e71_codord} está com os seguintes movimentos configurados:\n";
                $sVirgula = "";
                foreach ($aMovimentos as $oMovimento) {

                    if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque != "") {
                        $sMsgErro .= " - {$oMovimento->e81_codmov}, Cheque {$oMovimento->e91_cheque}.\n";
                    } else if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque == "") {
                        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para emissão de cheque.\n";
                    } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera != "") {
                        $sMsgErro .= " - {$oMovimento->e81_codmov}, no arquivo {$oMovimento->e90_codgera}.\n";
                    } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera == "") {
                        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para emissão de arquivo texto.\n";
                    } else {
                        $sMsgErro .= " - {$oMovimento->e81_codmov}, configurado para {$oMovimento->e96_descr}.\n";
                    }
                    $sVirgula = ", ";
                }
                $this->erro_status = '0';
                $this->erro_msg    = $sMsgErro;

                return false;
            }
            $clpagordemele             = new cl_pagordemele;
            $clpagordemele->e53_codord = $oNota->e71_codord;
            $clpagordemele->e53_codele = $codele;
            $clpagordemele->e53_vlranu = "$valor";
            //$clpagordemele->e53_valor  = '0.00';
            //$clpagordemele->e53_vlrpag = '0.00';
            $clpagordemele->alterar($clpagordemele->e53_codord, $clpagordemele->e53_codele);
            if ($clpagordemele->erro_status == 0) {

                $this->erro_status = '0';
                $this->erro_msg    = "Pagordemele:" . $clpagordemele->erro_msg;

                return false;

            }

            $clpagordemnota              = new cl_pagordemnota;
            $clpagordemnota->e71_codord  = $clpagordemele->e53_codord;
            $clpagordemnota->e71_codnota = $codnota;
            $clpagordemnota->e71_anulado = 'true';
            $clpagordemnota->alterar($clpagordemnota->e71_codord, $clpagordemnota->e71_codnota);
            if ($clpagordemnota->erro_status == 0) {

                $this->erro_status = '0';
                $this->erro_msg    = "Pagordemnota:" . $clpagordemnota->erro_msg;

                return false;
            }
        } else {

            $this->erro_status = '0';
            $this->erro_msg    = "Nota de liquidação não encontrada.";

            return false;

        }
    }

    /**
     *  funcao para retorno dos dados do empenho (retona um objeto db_utils);
     *
     * @param integer $iEmpenho
     * @param         string [$sWhere]
     */
    function getDados($iEmpenho, $sWhere = null) {

        $objEmpenho        = new cl_empempenho();
        $rsEmp             = $objEmpenho->sql_record($objEmpenho->sql_query($iEmpenho, "*", null, $sWhere));
        $this->iNumRowsEmp = $objEmpenho->numrows;
        if ($this->iNumRowsEmp > 0) {
            $this->dadosEmpenho = db_utils::fieldsMemory($rsEmp, 0, false, false, $this->getEncode());

            return true;
        } else {
            return false;
        }
    }

    /**
     *  funcao para retorno das notas do empenho (retona um resource);
     *
     * @param integer iEmpenho
     * @param string  [sWhere]
     * @param boolean [$lNotaCancelada] traz as nota com nota de liquidacao anulada.
     */
    function getNotas($iEmpenho, $sWhere = '', $lNotaCancelada = true) {

        $objNota = new cl_empnota();
        if (trim($sWhere) != '') {
            $sWhere = " and $sWhere";
        }

        if ($lNotaCancelada) {
            $sJoinPag = '';
        } else {
            $sJoinPag = ' and e71_anulado is false';
        }
        $sSqlNota = "SELECT {$this->sCamposNota}";
        $sSqlNota .= "  from empnota ";
        $sSqlNota .= "       inner join empnotaele   on  e69_codnota              = e70_codnota";
        $sSqlNota .= "       inner join db_usuarios  on  db_usuarios.id_usuario   = empnota.e69_id_usuario";
        $sSqlNota .= "       inner join empempenho   on  empempenho.e60_numemp    = empnota.e69_numemp";
        $sSqlNota .= "       inner join cgm          on  cgm.z01_numcgm           = empempenho.e60_numcgm";
        $sSqlNota .= "       inner join db_config    on  db_config.codigo         = empempenho.e60_instit";
        $sSqlNota .= "                              and  e60_instit               =" . db_getsession('DB_instit');
        $sSqlNota .= "       inner join orcdotacao   on  orcdotacao.o58_anousu    = empempenho.e60_anousu";
        $sSqlNota .= "                              and  orcdotacao.o58_coddot    = empempenho.e60_coddot";
        $sSqlNota .= "       inner join pctipocompra on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sSqlNota .= "       inner join emptipo      on  emptipo.e41_codtipo      = empempenho.e60_codtipo";
        $sSqlNota .= "       left join pagordemnota  on  e71_codnota              = empnota.e69_codnota {$sJoinPag}";
        $sSqlNota .= "       left join pagordem      on  e71_codord               = e50_codord";
        $sSqlNota .= "       left join pagordemconta on  e49_codord               = e71_codord";
        $sSqlNota .= "       left join cgm cgmordem  on  e49_numcgm               = cgmordem.z01_numcgm";
        $sSqlNota .= "       left join pagordemele   on  e53_codord               = pagordemnota.e71_codord";
        $sSqlNota .= "       left join empnotaord    on  m72_codnota              = e69_codnota";
        $sSqlNota .= "       left join matordem      on  m72_codordem             = m51_codordem";
        $sSqlNota .= "       left join matordemanu   on  m51_codordem             = m53_codordem";
        $sSqlNota .= " where  e69_numemp = {$iEmpenho} {$sWhere}";
        $rsNota              = $objNota->sql_record($sSqlNota);
        $this->iNumRowsNotas = $objNota->numrows;
        if ($objNota->numrows > 0) {
            return $rsNota;
        } else {
            return false;
        }
    }

    /**
     * Retorna os ITENS de uma nota de Empenho.
     *
     * @param integer $iCodNota
     *
     * @return array
     */
    public function getItensNota($iCodNota) {

        $oNota         = $this->usarDao("empnotaitem", true);
        $sSqlItensNota = "select pc01_descrmater, ";
        $sSqlItensNota .= "       e72_qtd, ";
        $sSqlItensNota .= "       e72_empempitem , ";
        $sSqlItensNota .= "       e72_valor,";
        $sSqlItensNota .= "       e72_vlrliq,";
        $sSqlItensNota .= "       e72_vlranu,";
        $sSqlItensNota .= "       e72_sequencial";
        $sSqlItensNota .= "  from empnotaitem";
        $sSqlItensNota .= "         inner join empempitem on e62_sequencial = e72_empempitem";
        $sSqlItensNota .= "         inner join pcmater    on  e62_item      = pc01_codmater";
        $sSqlItensNota .= "  where e72_codnota = {$iCodNota}";

        $rsNota     = $oNota->sql_record($sSqlItensNota);
        $aItensNota = array();

        if ($rsNota) {
            for ($iInd = 0; $iInd < $oNota->numrows; $iInd++) {
                $aItensNota[] = db_utils::fieldsMemory($rsNota, $iInd, false, false, $this->getEncode());
            }

            return $aItensNota;
        } else {
            return false;
        }
    }

    /**
     * funcao para para converter dados do empenho e notas em string json;
     * @param string $sWhere
     * @param int $itens
     * @param array $aItensPendentesPatrimonio
     * @return string json
     */
    function empenho2Json($sWhere = '', $itens = 0, $aItensPendentesPatrimonio = array()) {

        if (!class_exists("services_json")) {
            require_once modification("libs/JSON.php");
        }
        if (!class_exists("retencaoNota")) {
            require_once modification("model/retencaoNota.model.php");
        }
        $objJson            = new services_JSON();
        $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($this->numemp);

        if ($this->getDados($this->numemp, $sWhere)) {

            $this->getSolicitacoesAnulacoes();

            $strJson["status"]          = 1;
            $strJson["e60_numemp"]      = $this->dadosEmpenho->e60_numemp;
            $strJson["e60_codemp"]      = $this->dadosEmpenho->e60_codemp;
            $strJson["e60_anousu"]      = $this->dadosEmpenho->e60_anousu;
            $strJson["e60_coddot"]      = $this->dadosEmpenho->e60_coddot;
            $strJson["e60_numcgm"]      = $this->dadosEmpenho->e60_numcgm;
            $strJson["lPrestacaoConta"] = $oEmpenhoFinanceiro->isPrestacaoContas();

            $oListaClassificacaoCredor    = $oEmpenhoFinanceiro->getListaClassificacaoCredor();
            $strJson["iClassificacao"]    = empty($oListaClassificacaoCredor) ? "" : $oListaClassificacaoCredor->getCodigo();
            $sDescricaoListaClassificacao = empty($oListaClassificacaoCredor) ? "" : $oListaClassificacaoCredor->getDescricao();
            $strJson["sClassificacao"]    = urlencode($sDescricaoListaClassificacao);

            $sDataVencimento = "";
            if (!empty($oListaClassificacaoCredor)) {

                $oDataAtual      = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
                $oDataVencimento = $oListaClassificacaoCredor->getDataVencimentoPorData($oDataAtual);
                $sDataVencimento = $oDataVencimento->getDate(DBDate::DATA_PTBR);
            }

            $strJson["sDataVencimento"] = urlencode($sDataVencimento);

            $strJson["e60_resumo"] = "";

            /**
             * query para retornar o  e64_codele da empelemento pelo e64_numemp
             *
             */
            $oDaoEmpElemento       = new cl_empelemento();
            $sWhereEmpElemento     = "e64_numemp = {$this->dadosEmpenho->e60_numemp}";
            $sSqlEmpElemento       = $oDaoEmpElemento->sql_query_file(null, null, "e64_codele", null, $sWhereEmpElemento);
            $rsEmpElemento         = $oDaoEmpElemento->sql_record($sSqlEmpElemento);
            $iElemento             = db_utils::fieldsMemory($rsEmpElemento, 0)->e64_codele;
            $strJson["e64_codele"] = $iElemento;
            /**
             * Verifico se o parâmetro que importa o resumo de um empenho está ativo
             * Caso esteja, irá preencher o campo "histórico" com o resumo (e60_resumo) do empenho
             */
            $oDaoEmpParametro    = new cl_empparametro();
            $sWhereEmpParametro  = "e39_anousu = " . db_getsession('DB_anousu');
            $sSqlEmpParametro    = $oDaoEmpParametro->sql_query_file(null, "e30_opimportaresumo", null, $sWhereEmpParametro);
            $rsBuscaEmpParametro = $oDaoEmpParametro->sql_record($sSqlEmpParametro);
            if ($oDaoEmpParametro->numrows == 1) {
                $sImportaResumoOrdemPagamento = db_utils::fieldsMemory($rsBuscaEmpParametro, 0)->e30_opimportaresumo;
                if ($sImportaResumoOrdemPagamento == "t") {
                    $strJson["e60_resumo"] = $this->dadosEmpenho->e60_resumo;
                }
            }
            $strJson["z01_nome"]   = $this->dadosEmpenho->z01_nome;
            $strJson["o58_codigo"] = $this->dadosEmpenho->o58_codigo;
            $strJson["o15_descr"]  = trim($this->dadosEmpenho->o15_descr);
            $strJson["e60_vlremp"] = trim(db_formatar($this->dadosEmpenho->e60_vlremp, "f"));
            $strJson["e60_vlrliq"] = trim(db_formatar($this->dadosEmpenho->e60_vlrliq, "f"));
            $strJson["e60_vlrpag"] = trim(db_formatar($this->dadosEmpenho->e60_vlrpag, "f"));
            $strJson["e60_vlranu"] = trim(db_formatar($this->dadosEmpenho->e60_vlranu, "f"));
            $strJson["numnotas"]   = "0";
            if ($this->operacao == 1) {
                $strJson["saldo_dis"] = trim(db_formatar($this->dadosEmpenho->e60_vlremp - $this->dadosEmpenho->e60_vlranu
                  - $this->dadosEmpenho->e60_vlrliq,
                  "f"));
                $sWhere               = '';

            } else if ($this->operacao == 2) {

                $sWhere               = " e69_anousu = {$this->anousu}";
                $strJson["saldo_dis"] = 0;
                $sSQLPgtoOrdem        = "select  sum(e53_valor) as e53_valor,
          sum(e53_vlranu) as e53_vlranu,
          sum(e53_vlrpag) as e53_vlrpag
            from pagordem
            inner join pagordemele on e50_codord = e53_codord
            where e50_numemp = {$this->dadosEmpenho->e60_numemp}
            ";
                $rsOrdem              = db_query($sSQLPgtoOrdem);
                if (pg_num_rows($rsOrdem) == 1) {
                    $objOrdem = db_utils::fieldsMemory($rsOrdem, 0);

                    $strJson["saldo_dis"] = trim(db_formatar($objOrdem->e53_valor - $objOrdem->e53_vlranu - $objOrdem->e53_vlrpag,
                      "f"));
                }
            }
            if ($itens == 0) {
                $rsNotas = $this->getNotas($this->numemp, "", false);
                if ($rsNotas) {

                    if ($this->iNumRowsNotas > 0) {
                        $strJson["numnotas"] = $this->iNumRowsNotas;

                        for ($i = 0; $i < $this->iNumRowsNotas; $i++) {

                            $objNotas  = db_utils::fieldsMemory($rsNotas, $i);

                            $oNotaLiquidacao         = new NotaLiquidacao($objNotas->e69_codnota);
                            $aParcelasNotaLiquidacao = $oNotaLiquidacao->getProgramacaoFinanceiraParcelas();
                            $sCompetencia            = "";

                            if(count($aParcelasNotaLiquidacao) > 0) {

                                $oUltimaParcelaNotaLiquidacao = (object) $aParcelasNotaLiquidacao[0];
                                $sMesCompetencia = str_pad($oUltimaParcelaNotaLiquidacao->k118_mes, 2, '0', STR_PAD_LEFT);
                                $sCompetencia    = "{$sMesCompetencia}/{$oUltimaParcelaNotaLiquidacao->k118_ano}";
                            }

                            $oRetencao = new retencaoNota($objNotas->e69_codnota);
                            $oRetencao->unsetSession();

                            if ($this->operacao == 1) {
                                $checked = "";
                                if ($objNotas->e70_valor == $objNotas->e70_vlrliq + $objNotas->e70_vlranu) {
                                    $checked = "disabled";
                                }
                            } else {
                                $checked = "disabled";
                                if ($objNotas->e70_valor == $objNotas->e70_vlrliq) {
                                    $checked = "";
                                } else {
                                    $checked = "disabled";
                                }
                            }
                            $sStrNotas         = $this->getInfoAgenda($objNotas->e69_codnota);
                            $strJson["data"][] = array(
                              "e69_codnota" => $objNotas->e69_codnota, "e69_numero" => urlencode($objNotas->e69_numero),
                              "e69_anousu" => $objNotas->e69_anousu, "e50_anousu" => $objNotas->e50_anousu,
                              "e69_dtnota" => db_formatar($objNotas->e69_dtnota, "d"),
                              "e70_vlranu" => trim(db_formatar($objNotas->e70_vlranu, "f")),
                              "e70_vlrliq" => trim(db_formatar($objNotas->e70_vlrliq, "f")),
                              "e70_valor" => trim(db_formatar($objNotas->e70_valor, "f")),
                              "e53_vlrpag" => trim(db_formatar($objNotas->e53_vlrpag, "f")),
                              "vlrretencao" => trim(db_formatar($objNotas->vlrretencao, "f")), "e50_codord" => $objNotas->e50_codord,
                              "sInfoAgenda" => urlencode($sStrNotas), "libera" => $checked,
                              "competencia" => $sCompetencia
                            );
                        }//end for
                    }
                }
            } else if ($itens == 1) {
                $rsItens = $this->getItensSaldo();
                if ($rsItens) {

                    if ($this->iNumRowsItens > 0) {
                        $strJson["numnotas"] = $this->iNumRowsItens;

                        for ($i = 0; $i < $this->iNumRowsItens; $i++) {

                            $objNotas = db_utils::fieldsMemory($rsItens, $i);
                            $checked  = '';
                            if ($objNotas->saldovalor == 0) {
                                $checked = " disabled ";
                            }

                            $strJson["data"][] = array(
                              "pc01_descrmater" => urlencode($objNotas->pc01_descrmater), "e62_sequen" => $objNotas->e62_sequen,
                              "e62_sequencial" => $objNotas->e62_sequencial, "saldo" => $objNotas->saldo,
                              "e62_vlrun" => $objNotas->e62_vlrun, "pc01_fraciona" => $objNotas->pc01_fraciona,
                              "pc01_servico" => $objNotas->pc01_servico, "saldodiferenca" => $objNotas->saldocentavos,
                              "e62_vlrtot" => round($objNotas->saldovalor, 2), "servicoquantidade" => $objNotas->servicoquantidade,
                              "libera" => $checked
                            );
                        }
                    }
                }
            }
            $strJson["itensAnulados"] = $this->aItensAnulados;
        } else {
            $strJson["status"] = 0;

        }

        $strJson["aItensPendentesPatrimonio"] = $aItensPendentesPatrimonio;


        /**
         * Retorno o código, mês e ano das parcelas referentes a um regime de competência de acordo, quando este existir
         */
        $strJson["aParcelasRegimeCompetencia"] = array();
        $icontrato =  $oEmpenhoFinanceiro->getCodigoContrato();

        if (!empty($icontrato)) {

            $oRegimeCompetenciaRepository          = new RegimeCompetencia();
            $oRegime                               = $oRegimeCompetenciaRepository->getByAcordo(
              AcordoRepository::getByCodigo($oEmpenhoFinanceiro->getCodigoContrato())
            );

            if($oRegime !== null) {
                foreach($oRegime->getParcelas() as $oParcela) {
                    $oDadosParcela         = new stdClass();
                    $oDadosParcela->codigo = $oParcela->getCodigo();
                    $oDadosParcela->mes    = $oParcela->getCompetencia()->getMes();
                    $oDadosParcela->ano    = $oParcela->getCompetencia()->getAno();

                    $strJson["aParcelasRegimeCompetencia"][] = $oDadosParcela;
                }
            }
        }

        return $objJson->encode($strJson);
    }

    /**
     * callback apra liquidar as notas via ajax
     *
     * @param integer $iEmpenho numero do empenho,
     * @param mixed   $aNotas   notas a liquidar
     * @param         string    [historico] historico do procedimento
     *
     * @return boolean;
     */
    function liquidarAjax($iEmpenho, $aNotas, $sHistorico = '') {

        (boolean) $this->lSqlErro = false;
        (string) $this->sMsgErro = false;
        if ($sHistorico == "") {
            $sHistorico = "S/Historico";
        }
        /*Consultado dados do empenho
     * TODO Verificar estado do empenho antes de fazer as liquidacoes
     */
        //$aNotas deve ser um array
        if (!is_array($aNotas)) {

            $this->lSqlErro = true;
            $this->sMsgErro = "Erro (0) Notas Inválidas.";
        }
        //verificamos se o empenho existe, e se a valor para liquidar
        if (!$this->getDados($iEmpenho)) {

            $this->lSqlErro = true;
            $this->sMsgErro = "Erro (1) Não foi possível selecionar Empenho.";

        } else {

            if ($this->dadosEmpenho->e60_vlremp == $this->dadosEmpenho->e60_vlrliq) {

                $this->sMsgErro = "Erro (3) Empenho sem valor para Liquidar.";
                $this->lSqlErro = true;
            }
        }
        if (!$this->lSqlErro) {

            $clempelemento = new cl_empelemento();
            $rsEle         = $clempelemento->sql_record($clempelemento->sql_query($iEmpenho, null, "*"));
            if ($clempelemento->numrows == 1) {
                $objEmpElem = db_utils::fieldsMemory($rsEle, 0);
            } else {
                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (2) Empenho sem elemento.";
            }
        }
        if (!$this->lSqlErro) {
            db_inicio_transacao();
            //inciamos lançamentos contabeis para cada nota lancada.
            (float) $totalLiquidado = 0;
            (string) $sV = "";
            (string) $sNotas = "";
            for ($i = 0; $i < count($aNotas); $i++) {

                //pegamos dados das notas e tentamos fazer os lançamentos contábeis.
                $objNota = db_utils::fieldsMemory($this->getNotas($iEmpenho, "e69_codnota = " . $aNotas[$i]), 0);

                //trata string
                $sHistorico = addslashes(stripslashes($sHistorico));

                $this->liquidar($iEmpenho, $objEmpElem->e64_codele, $objNota->e69_codnota, $objNota->e70_valor, $sHistorico);
                if ($this->erro_status == "0") {

                    $this->lSqlErro = true;
                    $this->sMsgErro = $this->erro_msg;
                }
                $sNotas .= $sV . $this->iCodOrdem;
                $sV = ",";
                if (!$this->lSqlErro) {
                    $totalLiquidado += $objNota->e70_valor;
                }
            }//end for
        }

        db_fim_transacao($this->lSqlErro);

        $objJson = new services_JSON();
        if ($this->lSqlErro) {
            $retorno = array("erro" => 2, "mensagem" => urlencode($this->sMsgErro), "e50_codord");
        } else {
            $total = 0;
            if ($totalLiquidado == $this->dadosEmpenho->e60_vlremp) {

                $total = 1;
            }

            $retorno = array("erro" => 1, "mensagem" => "OK", "total" => $total, "sOrdensGeradas" => $sNotas);
        }

        return $objJson->encode($retorno);
    }//end function

    /**
     * callback para estornar a liquidacao via ajax
     *
     * @param  integer $iEmpenho numero do empenho,
     * @param  mixed   $aNotas   notas a liquidar
     * @param          string    [$sHistorico] historico do procedimento
     * @param bool $lTransacao
     * @param array $aCompetenciaNotas
     *
     * @return boolean;
     */
    function estornarLiquidacaoAJAX($iEmpenho, $aNotas, $sHistorico = '', $lTransacao = true, $aCompetenciaNotas = array()) {

        (boolean) $this->lSqlErro = false;
        (string) $this->sMsgErro = false;
        if ($sHistorico == "") {
            $sHistorico = "S/Historico";
        }
        /*
         * Consultado dados do empenho
         * TODO Verificar estado do empenho antes de fazer as liquidacoes
         */
        //$aNotas deve ser um array
        if (!is_array($aNotas)) {

            $this->lSqlErro = true;
            $this->sMsgErro = "Erro (0) Notas Inválidas.";
        }
        //verificamos se o empenho existe, e se a valor para liquidar
        if (!$this->getDados($iEmpenho)) {

            $this->lSqlErro = true;
            $this->sMsgErro = "Erro (1) Não foi possível selecionar Empenho.";

        }
        if (!$this->lSqlErro) {

            $clempelemento = new cl_empelemento();
            $rsEle         = $clempelemento->sql_record($clempelemento->sql_query($iEmpenho, null, "*"));
            if ($clempelemento->numrows == 1) {
                $objEmpElem = db_utils::fieldsMemory($rsEle, 0);
            } else {
                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (2) Empenho sem elemento.";
            }
        }

        if (!$this->lSqlErro) {

            if ($lTransacao) {
                db_inicio_transacao();
            }
            (float) $totalLiquidado = 0;
            for ($i = 0; $i < count($aNotas); $i++) {

                if(count($aCompetenciaNotas) > 0 && array_key_exists($aNotas[$i], $aCompetenciaNotas)) {
                    $this->setCompetenciaLiquidacao($aCompetenciaNotas[$aNotas[$i]]->sCompetencia);
                }

                $objNota = db_utils::fieldsMemory($this->getNotas($iEmpenho, "e69_codnota = " . $aNotas[$i]), 0);
                //verificamos o tipo da ordem , se for virtual devemos anular a ordem de compra e seus itens.
                $sSQLOrdem = "select m51_tipo,";
                $sSQLOrdem .= "       m73_codmatestoqueitem,";
                $sSQLOrdem .= "       m52_codlanc,";
                $sSQLOrdem .= "       m72_codordem,";
                $sSQLOrdem .= "       m52_valor,";
                $sSQLOrdem .= "       m52_quant";
                $sSQLOrdem .= "  from matordem ";
                $sSQLOrdem .= "        inner join empnotaord   on m72_codordem    = m51_codordem";
                $sSQLOrdem .= "        inner join matordemitem on m51_codordem    = m52_codordem ";
                $sSQLOrdem .= "        left  join matestoqueitemoc on m52_codlanc = m73_codmatordemitem";
                $sSQLOrdem .= "                                   and m73_cancelado is false  ";
                $sSQLOrdem .= " where m72_codnota = {$objNota->e69_codnota}";
                $rOrdem = db_query($sSQLOrdem);
                if (pg_num_rows($rOrdem) > 0) {

                    if (!class_exists("cl_matordemanu")) {
                        require_once modification("classes/db_matordemanu_classe.php");
                    }
                    if (!class_exists("cl_matordemitemanu")) {
                        require_once modification("classes/db_matordemitemanu_classe.php");
                    }
                    if (!class_exists("cl_matordemanul")) {
                        require_once modification("classes/db_matordemanul_classe.php");
                    }
                    $clmatordemanu     = new cl_matordemanu();
                    $clmatordemanul    = new cl_matordemanul();
                    $clmatordemitemanu = new cl_matordemitemanu();
                    /*
             vamos verificar se essa nota possui algum item em estoque.
             se possui, nao podemos deixar extornar a liquidacao
           */

                    for ($j = 0; $j < pg_num_rows($rOrdem); $j++) {

                        $oMatordemItem = db_utils::fieldsMemory($rOrdem, $j);
                        if ($oMatordemItem->m73_codmatestoqueitem != null and $oMatordemItem->m51_tipo == 2) {

                            $this->lSqlErro = true;
                            $this->sMsgErro = "Nota ({$objNota->e69_numero}) possui Itens com entrada no estoque.";
                            $this->sMsgErro .= "\nNão podera ser estornada (anulada) a liquidação.";

                        }
                        if (!$this->lSqlErro and $oMatordemItem->m51_tipo == 2) {

                            $clmatordemanul->m37_hora    = db_hora();
                            $clmatordemanul->m37_data    = date("Y-m-d", db_getsession("DB_datausu"));
                            $clmatordemanul->m37_usuario = db_getsession("DB_id_usuario");
                            $clmatordemanul->m37_motivo  = "Cancelamento por anulação de liquidação";
                            $clmatordemanul->m37_empanul = "0";
                            $clmatordemanul->m37_tipo    = 1;//anulacao parcial;
                            $clmatordemanul->incluir(null);
                            if ($clmatordemanul->erro_status == 0) {

                                $this->lSqlErro = true;
                                $this->sMsgErro = "Erro (3) anulacao do item nao incluso.";
                                $this->sMsgErro .= "\n{$clmatordemanul->erro_msg}";
                            }
                        }
                        if (!$this->lSqlErro and $oMatordemItem->m51_tipo == 2) {

                            $clmatordemitemanu->m36_matordemitem = $oMatordemItem->m52_codlanc;
                            $clmatordemitemanu->m36_matordemanul = $clmatordemanul->m37_sequencial;
                            $clmatordemitemanu->m36_vrlanu       = $oMatordemItem->m52_valor;
                            $clmatordemitemanu->m36_qtd          = $oMatordemItem->m52_quant;
                            $clmatordemitemanu->m36_vrlanu       = $oMatordemItem->m52_valor;

                            $clmatordemitemanu->incluir(null);
                            if ($clmatordemitemanu->erro_status == 0) {

                                $this->lSqlErro = true;
                                $this->sMsgErro = "Erro (3) anulacao do item nao incluso.";
                                $this->sMsgErro .= "\n{$clmatordemitemanu->erro_msg}";
                            }
                        }
                        if (!$this->lSqlErro and $oMatordemItem->m51_tipo == 2) {
                            if (!class_exists("cl_empnotaele")) {
                                require_once modification("classes/db_empnotaele_classe.php");
                            }
                            $clempnotaele              = new cl_empnotaele();
                            $clempnotaele->e70_vlranu  = $objNota->e70_valor;
                            $clempnotaele->e70_codnota = $objNota->e69_codnota;
                            $clempnotaele->alterar($objNota->e69_codnota);
                            if ($clempnotaele->erro_status == 0) {

                                $this->lSqlErro = true;
                                $this->sMsgErro = "Erro (5) anulacao do valor da nota nao incluso.";
                                $this->sMsgErro .= "\\n{$clempnotaele->erro_msg}";

                            }
                        }
                    }

                }


                if (!$this->lSqlErro) {
                    //pegamos dados das notas e tentamos fazer os lançamentos contábeis par ao estorno.
                    $this->estornaLiq($iEmpenho,
                      $objEmpElem->e64_codele,
                      $objNota->e69_codnota,
                      $objNota->e70_valor,
                      $sHistorico);
                    if ($this->erro_status == "0") {

                        $this->lSqlErro = true;
                        $this->sMsgErro = $this->erro_msg;
                    }
                }
                //anulando o  op para a nota
                if (!$this->lSqlErro) {

                    $this->estornaOP($iEmpenho,
                      $objEmpElem->e64_codele,
                      $objNota->e69_codnota,
                      $objNota->e70_valor,
                      null,
                      $sHistorico);
                    if ($this->erro_status == "0") {

                        $this->lSqlErro = true;
                        $this->sMsgErro = $this->erro_msg;
                    }
                }
                if (!$this->lSqlErro) {
                    $totalLiquidado += $objNota->e70_valor;
                }
            }//end for
        }

        if ($lTransacao) {
            db_fim_transacao($this->lSqlErro);
        }
        $objJson = new services_JSON();
        if ($this->lSqlErro) {
            if ($lTransacao) {
                $this->sMsgErro = urlencode($this->sMsgErro);
            }
            $retorno = array("erro" => 2, "mensagem" => $this->sMsgErro);
        } else {
            $total = 0;
            if ($totalLiquidado == $this->dadosEmpenho->e60_vlremp) {

                $total = 1;
            }
            $retorno = array("erro" => 1, "mensagem" => "OK", "total" => $total);
        }

        return $objJson->encode($retorno);
    }

    function setEmpenho($iEmpenho) {

        $this->numemp = $iEmpenho;
    }

    function getEmpenho() {

        return $this->numemp;
    }

    /**
     *  funcao para para retornar itens do empenho (com saldo) ;
     * @return recordset;
     */

    function getItensSaldo() {

        $this->clempempitem = new cl_empempitem();
        $sqlItensEmpenho    = "select rsdescr as pc01_descrmater, ";
        $sqlItensEmpenho .= "       rnquantini as e62_quant, ";
        $sqlItensEmpenho .= "       pc01_servico,";
        $sqlItensEmpenho .= "       pc01_fraciona,";
        $sqlItensEmpenho .= "       riseqitem as e62_sequen,";
        $sqlItensEmpenho .= "       ricoditem as e62_sequencial,";
        $sqlItensEmpenho .= "       rnsaldoitem as saldo, ";
        $sqlItensEmpenho .= "       rnvalorini as e62_vltot, ";
        $sqlItensEmpenho .= "       rnsaldovalor as saldovalor, ";
        $sqlItensEmpenho .= "       rnvaloruni as e62_vlrun, ";
        $sqlItensEmpenho .= "       rnsaldoentradaempenho as saldocentavos, ";
        $sqlItensEmpenho .= "       rlcontrolaquantidade as servicoquantidade ";
        $sqlItensEmpenho .= "  From fc_saldoitensempenho({$this->numemp}) ";
        $sqlItensEmpenho .= "       inner join pcmater on riCodmater = pc01_codmater ";
        $sqlItensEmpenho .= " order by e62_sequen ";
        $rsItems = $this->clempempitem->sql_record($sqlItensEmpenho);
        if ($rsItems) {
            $this->iNumRowsItens = pg_num_rows($rsItems);

            return $rsItems;
        } else {
            echo pg_last_error();

            return false;
        }
    }

    /**
     *  funcao para para gerar OC's ,
     *
     * @param integer $iNumNota numero da nota, float $nTotali valor total da nota,mixed $aItens [,boolean
     *                          $lLiquidar,date $dDataNota]
     *
     * @return recordset;
     */
    function gerarOrdemCompra($iNumNota,
      $nTotal,
      $aItens,
      $lLiquidar = false,
      $dDataNota = null,
      $sHistorico = null,
      $lIniciaTransacao = true,
      $oInfoNota = null) {

        $this->lSqlErro  = false;
        $this->sErroMsg  = '';
        $this->iPagOrdem = '';
        if ($dDataNota == null) {
            $e69_dtnota = date("Y-m-d", db_getsession("DB_datausu"));
        } else {
            $dtaux = explode("/", $dDataNota);
            if (count($dtaux) != 3) {
                $this->lSqlErro = true;
                $this->sMsgErro = "Argumento [dDataNota] não é uma data válida.";

                return false;
            } else {
                $e69_dtnota = $dtaux[2] . "-" . $dtaux[1] . "-" . $dtaux[0];
            }
        }

        // Valida se a data da nota é inferior a data do empenho
        // caso seja inferior então retorna erro

        $clEmpEmpenho = db_utils::getDao('empempenho');

        $sSqlValidaEmp   = $clEmpEmpenho->sql_query_file($this->numemp, "e60_emiss");
        $rsValidaDataEmp = $clEmpEmpenho->sql_record($sSqlValidaEmp);

        if (pg_num_rows($rsValidaDataEmp) > 0) {
            $oDataEmpenho = db_utils::fieldsMemory($rsValidaDataEmp, 0);

            if ($e69_dtnota < $oDataEmpenho->e60_emiss) {
                $this->lSqlErro = true;
                $this->sMsgErro = "Data da nota inferior a data do empenho!";

                return false;
            }
        }

        if (!class_exists("cl_matordem")) {
            require_once modification("classes/db_matordem_classe.php");
        }
        $objMatOrdem = new cl_matordem();
        if (!class_exists("cl_matordemitem")) {
            require_once modification("classes/db_matordemitem_classe.php");
        }
        $objMatOrdemItem = new cl_matordemitem();
        if (!class_exists("cl_empnotaord")) {
            require_once modification("classes/db_empnotaord_classe.php");
        }
        $objMatOrdemItem = new cl_matordemitem();
        if (!is_array($aItens)) {
            $this->lSqlErro = true;
            $this->sMsgErro = "Argumento [ 1 ] não é um array.";

            return false;
        }
        if (trim($iNumNota) == '') {

            $this->lSqlErro = true;
            $this->sMsgErro = "Número da nota nao pode ser vazio.";

            return false;

        }

        /**
         * Validamos a regra dos Custos
         * caso o custo esteje sendo utilizado, e já existe uma planilha encerrada para o mes/ano, nao podemos
         * permitir a liquidacao do empenho
         */

        require_once(modification("std/db_stdClass.php"));
        $aParamKeys          = array(
          db_getsession("DB_anousu")
        );
        $aParametrosCustos   = db_stdClass::getParametro("parcustos", $aParamKeys);
        $iTipoControleCustos = 0;
        if (count($aParametrosCustos) > 0) {
            $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
        }
        if ($iTipoControleCustos > 1) {

            $aData = explode("-", $this->datausu);
            require_once(modification('model/custoPlanilha.model.php'));
            $oPlanilha = new custoPlanilha($aData[1], $aData[0]);
            if ($oPlanilha->getSituacao() == 2) {

                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (0) - não Foi possível gerar ordem de compra.\nPlanilha de custos já processada ";
                $this->sMsgErro .= "para competência {$aData[1]}/{$aData[0]}";
            }
        }
        //incluimos a ordem.
        if ($lIniciaTransacao) {
            db_inicio_transacao();
        }
        if (!$this->lSqlErro) {

            $this->getDados($this->numemp);
            $objMatOrdem->m51_data       = $this->datausu;
            $objMatOrdem->m51_depto      = db_getsession("DB_coddepto");
            $objMatOrdem->m51_numcgm     = $this->dadosEmpenho->e60_numcgm;
            $objMatOrdem->m51_obs        = "Ordem de Compra Automatica";
            $objMatOrdem->m51_valortotal = $nTotal;
            $objMatOrdem->m51_prazoent   = "0";
            $objMatOrdem->m51_tipo       = 2;
            $objMatOrdem->Incluir(null);
            if ($objMatOrdem->erro_status == 0) {

                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (1) - não Foi possível gerar ordem de compra .\\nerro:{$objMatOrdem->erro_msg}";
            }
        }

        if (!$this->lSqlErro) {
            //incluimos os items da ordem de compra
            for ($i = 0; $i < count($aItens); $i++) {


                $objMatOrdemItem->m52_codordem = $objMatOrdem->m51_codordem;
                $objMatOrdemItem->m52_numemp   = $this->numemp;
                $objMatOrdemItem->m52_sequen   = $aItens[$i]->sequen;
                $objMatOrdemItem->m52_quant    = $aItens[$i]->quantidade;
                $objMatOrdemItem->m52_valor    = $aItens[$i]->vlrtot;
                $objMatOrdemItem->m52_vlruni   = $aItens[$i]->vlruni;
                $objMatOrdemItem->incluir(null);
                if ($objMatOrdemItem->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sMsgErro = "Erro (1) - não Foi possível gerar itens da ordem de compra .\\nerro:{$objMatOrdemItem->erro_msg}";
                }

                /**
                 * Caso foi informado um centro de custo,
                 * vinculamoso item da ordem de compra ao criterio,
                 * esse criterio sera usado como sugestao na saida de material
                 */
                if (isset($aItens[$i]->iCodigoCriterioCusto) && $aItens[$i]->iCodigoCriterioCusto != "") {

                    $oDaoCriterioOrdem                           = db_utils::getDao("matordemitemcustocriterio");
                    $oDaoCriterioOrdem->cc11_custocriteriorateio = $aItens[$i]->iCodigoCriterioCusto;
                    $oDaoCriterioOrdem->cc11_matordemitem        = $objMatOrdemItem->m52_codlanc;
                    $oDaoCriterioOrdem->incluir(null);
                    if ($oDaoCriterioOrdem->erro_status == 0) {

                        $this->lSqlErro = true;
                        $this->sMsgErro = "Erro (1) - não Foi possível aproriar custos.";
                        $this->sMsgErro .= "\\nerro:{$oDaoCriterioOrdem->erro_msg}";

                    }
                }
            }
        }
        if (!$this->lSqlErro) {
            //incluimos a nota com os valores da ordem de compra
            if (!class_exists("cl_empnota")) {
                require_once modification("classes/db_empnota_classe.php");
            }

            if (!isset($oInfoNota->iTipoDocumentoFiscal)) {
                $iTipoDocumentoFiscal = 4;
            } else {
                $iTipoDocumentoFiscal = $oInfoNota->iTipoDocumentoFiscal;
            }
            $objEmpNota                           = new cl_empnota();
            $objEmpNota->e69_numero               = $iNumNota;
            $objEmpNota->e69_numemp               = $this->numemp;
            $objEmpNota->e69_id_usuario           = db_getsession("DB_id_usuario");
            $objEmpNota->e69_dtnota               = $e69_dtnota;
            $objEmpNota->e69_dtrecebe             = $this->datausu;
            $objEmpNota->e69_tipodocumentosfiscal = $iTipoDocumentoFiscal;
            $objEmpNota->e69_anousu               = db_getsession("DB_anousu");
            $objEmpNota->e69_dtservidor           = date('Y-m-d');
            $objEmpNota->e69_dtinclusao           = date('Y-m-d', db_getsession("DB_datausu"));
            if ($this->oDadosNota && isset($this->oDadosNota->e69_dtrecebe)) {
                $objEmpNota->e69_dtrecebe = $this->oDadosNota->e69_dtrecebe;
            }
            if ($this->oDadosNota && isset($this->oDadosNota->e69_dtvencimento)) {
                $objEmpNota->e69_dtvencimento = $this->oDadosNota->e69_dtvencimento;
            }
            if ($this->oDadosNota && isset($this->oDadosNota->e69_localrecebimento)) {
                $objEmpNota->e69_localrecebimento = $this->oDadosNota->e69_localrecebimento;
            }
            $objEmpNota->incluir(null);

            if ($objEmpNota->erro_status == 0) {

                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (1) - não Foi possível gerar nota do Empenho .\\nerro:{$objEmpNota->erro_msg}";
            }
        }
        /**
         * Verificamos se a instituição controla o pit. Caso controla, incluimos os dados do pit
         */
        if (!$this->lSqlErro) {

            require_once(modification("std/db_stdClass.php"));
            $iControlaPit   = 2;
            $aParamKeys     = array(
              db_getsession("DB_instit")
            );
            $aParametrosPit = db_stdClass::getParametro("matparaminstit", $aParamKeys);
            if (count($aParametrosPit) > 0) {
                $iControlaPit = $aParametrosPit[0]->m10_controlapit;
            }
            if ($iControlaPit == 1 && $iTipoDocumentoFiscal == 50) {

                if ($oInfoNota == null && $iTipoDocumentoFiscal == 50) {

                    $this->lSqlErro = true;
                    $this->sMsgErro = "Informações da nota não foram informados.\nProcedimento Cancelado";

                }
                /**
                 * Verificamos o tipo da nota informada.
                 * caso seje tipo 50, é obrigatório informa a cfop;
                 */
                if ($iTipoDocumentoFiscal == 50) {

                    if ($oInfoNota->iCfop == "") {

                        $this->lSqlErro = true;
                        $this->sMsgErro = "CFOP não Informado!\nProcedimento Cancelado";
                    } else {

                        if (!$this->lSqlErro) {

                            $oDaoEmpnotaDadosPit                                = db_utils::getDao("empnotadadospit");
                            $oDaoEmpnotaDadosPit->e11_cfop                      = $oInfoNota->iCfop;
                            $oDaoEmpnotaDadosPit->e11_seriefiscal               = $oInfoNota->sSerieFiscal;
                            $oDaoEmpnotaDadosPit->e11_inscricaosubstitutofiscal = $oInfoNota->iInscrSubstituto;
                            $oDaoEmpnotaDadosPit->e11_basecalculoicms           = "$oInfoNota->nBaseCalculoICMS";
                            $oDaoEmpnotaDadosPit->e11_valoricms                 = "$oInfoNota->nValorICMS";
                            $oDaoEmpnotaDadosPit->e11_basecalculosubstitutotrib = "$oInfoNota->nBaseCalculoSubst";
                            $oDaoEmpnotaDadosPit->e11_valoricmssubstitutotrib   = "$oInfoNota->nValorICMSSubst";
                            $oDaoEmpnotaDadosPit->incluir(null);
                            if ($oDaoEmpnotaDadosPit->erro_status == 0) {

                                $this->lSqlErro = true;
                                $this->sMsgErro = "Não foi possível Salvar informações da nota Fiscal.\n";
                                $this->sMsgErro .= "[Erro Técnico] - {$oDaoEmpnotaDadosPit->erro_msg} - {$oDaoEmpnotaDadosPit->erro_campo}";

                            }
                        }

                        if (!$this->lSqlErro) {
                            /**
                             * Vinculamos as notas ao empnotadadospit
                             */
                            if ($oInfoNota->iTipoDocumentoFiscal == 50) {

                                $oDaoEmpnotaDadosPitNota                      = db_utils::getDao("empnotadadospitnotas");
                                $oDaoEmpnotaDadosPitNota->e13_empnota         = $objEmpNota->e69_codnota;
                                $oDaoEmpnotaDadosPitNota->e13_empnotadadospit = $oDaoEmpnotaDadosPit->e11_sequencial;
                                $oDaoEmpnotaDadosPitNota->incluir(null);
                                if ($oDaoEmpnotaDadosPitNota->erro_status == 0) {

                                    $this->lSqlErro = true;
                                    $this->sMsgErro = "Não foi possível incluir itens da nota.\n";
                                    $this->sMsgErro .= "[Erro Técnico] - {$oDaoEmpnotaDadosPitNota->erro_msg}";
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!$this->lSqlErro) {
            //incluimos os items na nota
            if (!class_exists("cl_empnotaitem")) {
                require_once modification("classes/db_empnotaitem_classe.php");
            }
            for ($i = 0; $i < count($aItens); $i++) {

                $sSQL = "select e62_sequencial";
                $sSQL .= "   from empempitem ";
                $sSQL .= "  where e62_numemp = {$this->numemp} ";
                $sSQL .= "    and e62_sequen  = {$aItens[$i]->sequen}";
                $rsItem                         = db_query($sSQL);
                $oItem                          = db_utils::fieldsMemory($rsItem, 0);
                $this->iCodNota                 = $objEmpNota->e69_codnota;
                $objEmpNotaItem                 = new cl_empnotaitem();
                $objEmpNotaItem->e72_codnota    = $objEmpNota->e69_codnota;
                $objEmpNotaItem->e72_empempitem = $oItem->e62_sequencial;
                $objEmpNotaItem->e72_qtd        = $aItens[$i]->quantidade;
                $objEmpNotaItem->e72_valor      = $aItens[$i]->vlrtot;
                $objEmpNotaItem->e72_vlrliq     = $aItens[$i]->vlrtot;
                $objEmpNotaItem->incluir(null);
                if ($objEmpNotaItem->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sMsgErro = "Erro (1) - não Foi possível gerar itens da ordem de compra .\nerro:{$objEmpNotaItem->erro_msg}";
                }
            }
        }

        if (!$this->lSqlErro) {
            //geramos elemento da nota.
            $rsEle = $this->clempelemento->sql_record($this->clempelemento->sql_query($this->numemp));
            if ($this->clempelemento->numrows > 0) {
                $objEmpElem                     = db_utils::fieldsMemory($rsEle, 0);
                $this->dadosEmpenho->e64_codele = $objEmpElem->e64_codele;
                if (!class_exists("cl_empnotaele")) {
                    require_once modification("classes/db_empnotaele_classe.php");
                }
                $objEmpNotaEle              = new cl_empnotaele();
                $objEmpNotaEle->e70_codnota = $objEmpNota->e69_codnota;
                $objEmpNotaEle->e70_codele  = $this->dadosEmpenho->e64_codele;
                $objEmpNotaEle->e70_valor   = round($nTotal, 2);
                $objEmpNotaEle->e70_vlranu  = "0";
                $objEmpNotaEle->e70_vlrliq  = "0";
                $objEmpNotaEle->incluir($objEmpNota->e69_codnota, $this->dadosEmpenho->e64_codele);
                if ($objEmpNotaEle->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sMsgErro = "Erro (1) -  Elemento da nota nao incluido.";
                    $this->sMsgErro .= "\\nerro:{$objEmpNotaEle->erro_msg}";
                }
            } else {

                $this->lSqlErro = true;
                $this->sMsgErro = "Erro (2) -  Empenho ({$this->dadosEmpenho->e60_codemp}) sem elemento. operação cancelada.";
                $this->sMsgErro .= "\\nerro:{$objEmpNota->erro_msg}";

            }
        }

        if (!$this->lSqlErro) {

            //incluimos empnotaord
            if (!class_exists("cl_empnotaord")) {
                require_once(modification("classes/db_empnotaord_classe.php"));
            }
            $objNotaOrd               = new cl_empnotaord();
            $objNotaOrd->m72_codordem = $objMatOrdem->m51_codordem;
            $objNotaOrd->m72_codnota  = $objEmpNota->e69_codnota;
            $objNotaOrd->incluir($objEmpNota->e69_codnota, $objMatOrdem->m51_codordem);
        }
        if ($lLiquidar && !$this->lSqlErro) {

            $this->liquidar($this->numemp, $objEmpElem->e64_codele, $objEmpNota->e69_codnota, $nTotal, $sHistorico);
            if ($this->erro_status == "0") {

                $this->lSqlErro = true;
                $this->sMsgErro = $this->erro_msg;
            }
            //lancando op para a nota
            if (!$this->lSqlErro) {

                //        $this->lancaOP($this->numemp, $objEmpElem->e64_codele, $objEmpNota->e69_codnota, $nTotal, null, $sHistorico);
                //        if ($this->erro_status == "0"){
                //
                //          $this->lSqlErro = true;
                //          $this->sMsgErro = $this->erro_msg;
                //        }
            }
        }
        if ($lIniciaTransacao) {
            db_fim_transacao($this->lSqlErro);
        }
        $objJson = new services_JSON();
        if ($this->lSqlErro) {
            $retorno = array("erro" => 2, "mensagem" => urlencode($this->sMsgErro), "e50_codord" => null);
        } else {
            $retorno = array(
              "erro" => 1, "mensagem" => "OK", "e50_codord" => $this->iPagOrdem, "iCodMov" => $this->getCodigoMovimento(),
              "iCodNota" => $objEmpNota->e69_codnota
            );
        }

        return $objJson->encode($retorno);
    }

    function getSolicitacoesAnulacoes() {

        if (!class_exists('cl_empsolicitaanulitem')) {
            require_once(modification("classes/db_empsolicitaanulitem_classe.php"));
        }
        $clempsolicitaanulitem = new cl_empsolicitaanulitem();
        $sSQLAnulados          = "select e36_sequencial, ";
        $sSQLAnulados .= "       e36_empempitem, ";
        $sSQLAnulados .= "       e36_vrlanu,     ";
        $sSQLAnulados .= "       e62_sequen,     ";
        $sSQLAnulados .= "       e36_qtdanu,     ";
        $sSQLAnulados .= "       e36_empsolicitaanul,";
        $sSQLAnulados .= "       pc01_descrmater ";
        $sSQLAnulados .= "  from empsolicitaanulitem";
        $sSQLAnulados .= "       inner join empsolicitaanul on e36_empsolicitaanul = e35_sequencial  ";
        $sSQLAnulados .= "       inner join empempitem      on e36_empempitem      = e62_sequencial ";
        $sSQLAnulados .= "       inner join pcmater         on e62_item            = pc01_codmater  ";
        $sSQLAnulados .= " where e35_numemp   = {$this->numemp} ";
        $sSQLAnulados .= "   and e35_situacao = 1";
        $sSQLAnulados .= " order by e62_sequen";
        $rsAnulados           = $clempsolicitaanulitem->sql_record($sSQLAnulados);
        $this->aItensAnulados = array();
        if ($clempsolicitaanulitem->numrows > 0) {

            for ($i = 0; $i < $clempsolicitaanulitem->numrows; $i++) {

                $oAnulados              = db_utils::fieldsMemory($rsAnulados, $i, false, false, true);
                $this->aItensAnulados[] = $oAnulados;
            }
        }
    }

    /**
     * @description Metodo para anular(itens) o empenho Itens do Empenho.
     *
     * @param   $aItens       array de itens que devem ser anulados - {[CodItemOrdem, CodItemEmp, Qtdem ,Valor]}
     * @param   $nValorAnular valor total a ser anulado;
     *
     * @returns   void;
     */
    function anularEmpenho($aItens,
      $nValorAnular = 0,
      $sMotivo = null,
      $aSolicitacoes = null,
      $iTipoAnulacao,
      $lTransacao = true) {

        if (!is_array($aItens)) {

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro [1]: Parametro aItens não e um array valido!\nContate Suporte";

            return false;
        }
        $lControlePacto       = false;
        $aParametrosOrcamento = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
        if (count($aParametrosOrcamento) > 0) {

            if (isset($aParametrosOrcamento[0]->o50_utilizapacto)) {
                $lControlePacto = $aParametrosOrcamento[0]->o50_utilizapacto == "t" ? true : false;
            }
        }
        $this->lSqlErro = false;
        $this->sErroMsg = null;
        $this->getDados($this->numemp);
        $nValorAnular = round($nValorAnular, 2);
        /*
       vericamos se existe saldo a anular;
     */
        (float) $nSaldoEmpenho = round($this->dadosEmpenho->e60_vlremp - $this->dadosEmpenho->e60_vlrliq
          - $this->dadosEmpenho->e60_vlranu,
          2);
        if ($nSaldoEmpenho < round($nValorAnular, 2)) {

            $this->lSqlErro = true;

            $this->sErroMsg = "Erro [2]: Não Existe saldo a anular no empenho!\nSaldo disponivel: R$ "
              . trim(db_formatar($nSaldoEmpenho, 'f'));
            $this->sErroMsg .= "\nValor Solicitado para anulação: R$ " . trim(db_formatar($nValorAnular, 'f'));

            return false;
        }
        $clempelemento  = $this->usarDao("empelemento", true);
        $rsEmpElemento  = $clempelemento->sql_record($clempelemento->sql_query($this->numemp,
          null,
          "e64_vlranu,e64_vlremp,e64_codele"));
        $oElemento      = db_utils::fieldsMemory($rsEmpElemento, 0);
        $nTotalElemento = $oElemento->e64_vlranu + $nValorAnular;
        if (bccomp($nTotalElemento, $oElemento->e64_vlremp) > 0) { // if $tot > $e64_vlremp

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro[12](Sem saldo no elemento para anular\nNão pode anular o valor digitado para o elemento $elemento do empenho. Verifique!";

            return false;
        }

        //classes utilizadas pelo metodo;
        require_once(modification("libs/db_libcontabilidade.php"));
        $clempparametro = $this->usarDao("empparametro", true);
        $clpcparam      = $this->usarDao("pcparam", true);
        $rsParametros   = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),
          "e30_verificarmatordem"));
        if ($clempparametro->numrows > 0) {
            $oParametros = db_utils::fieldsMemory($rsParametros, 0);
        }

        //pegamos os saldos da dotacao so empenho.
        $rsDotacaoSaldo = db_dotacaosaldo(8,
          2,
          2,
          "true",
          "o58_coddot={$this->dadosEmpenho->e60_coddot}",
          db_getsession("DB_anousu"));
        $oDotacaoSaldo  = db_utils::fieldsMemory($rsDotacaoSaldo, 0);

        /*
       testamos se existe saldo contabil disponivel para realizar a anulacao do empenho
     */
        $iCodigoDocumento = 2; //anulaçao de empenho.
        if ($this->dadosEmpenho->e60_anousu < db_getsession("DB_anousu")) {
            $iCodigoDocumento = "32";//anulação de restos a pagar.
        }

        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($this->numemp);

        if (USE_PCASP) {

            $lIsPassivo = $oEmpenhoFinanceiro->isEmpenhoPassivo();
            if ($lIsPassivo) {
                $iCodigoDocumento = 83;
            }

            /**
             * Verificamos se o empenho eh uma prestacao de contas
             */
            $isPrestacaoContas = $oEmpenhoFinanceiro->isPrestacaoContas();
            if ($isPrestacaoContas) {
                $iCodigoDocumento = 411;
            }

            $isProvisaoFerias         = $oEmpenhoFinanceiro->isProvisaoFerias();
            $isProvisaoDecimoTerceiro = $oEmpenhoFinanceiro->isProvisaoDecimoTerceiro();
            $isAmortizacaoDivida      = $oEmpenhoFinanceiro->isAmortizacaoDivida();
            $isPrecatoria             = $oEmpenhoFinanceiro->isPrecatoria();

            /**
             * Verificamos se o empenho eh uma provisao de ferias
             */
            if ($isProvisaoFerias) {
                $iCodigoDocumento = 305; // ESTORNO DE EMPENHO DA PROVISÃO DE FÉRIAS
            }

            /**
             *  Verificamos se o empenho eh uma provisao de 13o
             */
            if ($isProvisaoDecimoTerceiro) {
                $iCodigoDocumento = 309; // ESTORNO DE EMPENHO DA PROVISÃO DE 13º SALÁRIO
            }

            if ($isPrecatoria) {
                $iCodigoDocumento = 501;
            }

            if ($isAmortizacaoDivida) {
                $iCodigoDocumento = 505;  //  ESTORNO EMPENHO AMORT. DIVIDA
            }
        }

        db_inicio_transacao();


        $sSqlVerificacao = "select fc_verifica_lancamento({$this->numemp},'";
        $sSqlVerificacao .= date("Y-m-d", db_getsession("DB_datausu")) . "',{$iCodigoDocumento}," . round($nValorAnular, 2)
          . ") as verificacao";
        $oVerificacao = db_utils::fieldsMemory($this->clempempenho->sql_record($sSqlVerificacao), 0);
        if (substr($oVerificacao->verificacao, 0, 2) > 0) {

            $this->sErroMsg = substr($oVerificacao->verificacao, 3);
            $this->lSqlErro = true;

            return false;
        }

        /*
       controle de andamento do empenho
     */
        $rsPcParam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
        $oPcParam  = db_utils::fieldsMemory($rsPcParam, 0);

        if (isset ($oPcParam->pc30_contrandsol) && $oPcParam->pc30_contrandsol == 't') {

            $clempautitem        = $this->usarDao("empautitem", true);
            $rsTransfItens       = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,
              null,
              " distinct pc11_codigo as cod_item",
              null,
              "e54_anulad is null and e61_numemp = {$this->numemp}"));
            $iNumRowsItensTransf = $clempautitem->numrows;
            if ($clempautitem->numrows > 0) {

                $oTransfItens        = db_utils::fieldsMemory($rsTransfItens, 0);
                $clsolandam          = $this->usarDao("solandam", true);
                $clsolandpadraodepto = $this->usarDao("solandpadraodepto", true);
                //local atual do empenho
                $rsLocal   = $clsolandam->sql_record($clsolandam->sql_query_andpad(null,
                  "*",
                  null,
                  "pc43_solicitem = {$oTransfItens->cod_item} and pc47_pctipoandam = 6"));
                $sWhereSol = "";
                if ($clsolandam->numrows > 0) {
                    $sWhereSol = " pc47_pctipoandam = 5 ";
                } else {
                    $sWhereSol = " pc47_pctipoandam = 3 ";
                }
                $rsDestino = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,
                  "*",
                  null,
                  "pc47_solicitem={$oTransfItens->cod_item} and {$sWhereSol}"));

                if ($clsolandpadraodepto->numrows > 0) {

                    $clproctransfer                  = $this->usarDao("proctransfer", true);
                    $oDestino                        = db_utils::fieldsMemory($rsDestino, 0);
                    $clproctransfer->p62_hora        = db_hora();
                    $clproctransfer->p62_dttran      = date("Y-m-d", db_getsession("DB_datausu"));
                    $clproctransfer->p62_id_usuario  = db_getsession("DB_id_usuario");
                    $clproctransfer->p62_coddepto    = db_getsession("DB_coddepto");
                    $clproctransfer->p62_coddeptorec = $oDestino->pc48_depto;
                    $clproctransfer->p62_id_usorec   = '0';
                    $clproctransfer->incluir(null);
                    $iCodTransf = $clproctransfer->p62_codtran;
                    if ($clproctransfer->erro_status == 0) {

                        $this->lSqlErro = true;
                        $this->sErroMsg = "Erro [3] Não foi possível anular empenho\nErro ao incluir andamento\nErro:{$clproctransfer->erro_msg}";

                        return false;
                    }
                    if (!$this->lSqlErro) {

                        $clsolicitemprot = $this->usarDao("solicitemprot", true);
                        if (isset($iCodTransf) && $iCodTransf != "") {

                            for ($w = 0; $w < $iNumRowsItensTransf; $w++) {

                                $oTransfItens = db_utils::fieldsMemory($rsTransfItens, $w);
                                if (!$this->lSqlErro) {

                                    $rsSolicProt = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($oTransfItens->cod_item));
                                    if ($clsolicitemprot->numrows > 0) {

                                        $oSolicProt         = db_utils::fieldsMemory($rsSolicProt, 0);
                                        $clproctransferproc = $this->usarDao("proctransferproc", true);
                                        $clproctransferproc->incluir($iCodTransf, $oSolicProt->pc49_protprocesso);
                                        // db_msgbox("proctransferproc");
                                        if ($clproctransferproc->erro_status == 0) {

                                            $this->lSqlErro = true;
                                            $this->sErroMsg = $clproctransferproc->erro_msg;

                                            return false;
                                        }
                                        if (!$this->lSqlErro) {

                                            $clprotprocesso               = $this->usarDao("protprocesso", true);
                                            $clprotprocesso->p58_codproc  = $oSolicProt->pc49_protprocesso;
                                            $clprotprocesso->p58_despacho = "Empenho Anulado!!";
                                            $clprotprocesso->alterar($oSolicProt->pc49_protprocesso);
                                            //    db_msgbox("protprocesso");
                                            if ($clprotprocesso->erro_status == 0) {

                                                $this->lSqlErro = true;
                                                $this->sErroMsg = $clprotprocesso->erro_msg;

                                                return false;
                                            }
                                        }
                                    }
                                    if ($this->lSqlErro == false) {

                                        $clsolordemtransf                 = $this->usarDao("solordemtransf", true);
                                        $clsolordemtransf->pc41_solicitem = $oTransfItens->cod_item;
                                        $clsolordemtransf->pc41_codtran   = $iCodTransf;
                                        $clsolordemtransf->pc41_ordem     = $oDestino->pc47_ordem;
                                        $clsolordemtransf->incluir(null);
                                        //db_msgbox("solordemtransf");
                                        if ($clsolordemtransf->erro_status == 0) {

                                            $this->lSsqlErro = true;
                                            $erro_msg        = $clsolordemtransf->erro_msg;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }// fim  do controle de andamento de protocolo.

        /**
         * Iniciamos as rotinas de inclusão nas tabelas de controle da anulação do empenho
         */
        if (!$this->lSqlErro) {

            $this->clempempenho->e60_vlranu = $this->dadosEmpenho->e60_vlranu + round($nValorAnular, 2);
            $this->clempempenho->e60_numemp = $this->numemp;
            $this->clempempenho->alterar($this->numemp);

            if ($this->clempempenho->erro_status == 0) {

                $this->lSqlErro = true;
                $this->sErroMsg = $this->clempempenho->erro_msg;

                return false;
            }
        }

        /**
         * incluimos o dados do empenho na empanulado.
         */
        if (!$this->lSqlErro) {

            $clempanulado                     = $this->usarDao("empanulado", true);
            $clempanulado->e94_numemp         = $this->numemp;
            $clempanulado->e94_valor          = $nValorAnular;
            $clempanulado->e94_saldoant       = $nValorAnular; // carlos atualizado
            $clempanulado->e94_motivo         = $sMotivo;
            $clempanulado->e94_empanuladotipo = $iTipoAnulacao;
            $clempanulado->e94_data           = date("Y-m-d", db_getsession("DB_datausu"));
            $clempanulado->incluir(null);
            $iCodAnu = $clempanulado->e94_codanu;
            if ($clempanulado->erro_status == 0) {

                $this->lSqlErro = true;
                $this->sErroMsg = "Erro[10]\nNão Foi possível anular empenho.Erro ao cadastrar Empenho como anulado.";
                $this->sErroMsg .= "\nErro:{$clempanulado->erro_msg}";

                return false;
            }
        }

        /*
     ** Alteramos o elemento do empenho, e em seguida incluimos na empanuladoele
     */
        if (!$this->lSqlErro) {

            $clempelemento->e64_numemp = $this->numemp;
            $clempelemento->e64_codele = $oElemento->e64_codele;
            $clempelemento->e64_vlranu = $nTotalElemento;
            $clempelemento->alterar($this->numemp, $oElemento->e64_codele);
            $erro_msg = $clempelemento->erro_msg;
            if ($clempelemento->erro_status == 0) {
                $this->lSqlErro = true;
                $this->sErroMsg = "Erro[13]\nNão Foi possível anular empenho.Erro ao lançar valores do elemento.";
                $this->sErroMsg .= "\nErro:{$clempelemento->erro_msg}";

                return false;

            }
        }
        if (!$this->lSqlErro) {

            $clempanuladoele             = $this->usarDao("empanuladoele", true);
            $clempanuladoele->e95_codanu = $iCodAnu;
            $clempanuladoele->e95_codele = $oElemento->e64_codele;
            $clempanuladoele->e95_valor  = $nValorAnular;
            $clempanuladoele->incluir($iCodAnu);
            if ($clempanuladoele->erro_status == 0) {

                $lSqlErro       = true;
                $this->sErroMsg = "Erro[14]\nNão Foi possível anular empenho.Erro ao incluir elemento anulado.";
                $this->sErroMsg .= "\nErro:{$clempelemento->erro_msg}";

                return false;
            }
        }

        /**[Extensao OrdenadorDespesa] inclusao_ordenador*/
        /*
     ** incluimos na empanuladoitem, e marcamos como realizada (2) a solicitação de anulacao,
     ** caso todos os itens da solicitação foram anulados.
     */
        if (!$this->lSqlErro) {

            $clempanuladoitem = $this->usarDao("empanuladoitem", true);
            for ($iInd = 0; $iInd < count($aItens); $iInd++) {

                $clempanuladoitem->e37_empempitem = $aItens[$iInd]->e62_sequencial;
                $clempanuladoitem->e37_empanulado = $iCodAnu;
                $clempanuladoitem->e37_vlranu     = $aItens[$iInd]->vlrtot;
                $clempanuladoitem->e37_qtd        = $aItens[$iInd]->quantidade;
                $clempanuladoitem->incluir(null);
                if ($clempanuladoitem->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro[15]\nNão Foi possível anular empenho.Erro ao incluir Item como anulado.";
                    $this->sErroMsg .= "\nErro:{$clempanuladoitem->erro_msg}";

                    return false;
                }
                /**
                 * Verificamos se o item possui vinculo com algum item de pacto lancamos o o valor na pactoitemvalormov
                 */
                if (!$this->lSqlErro && $lControlePacto) {

                    $oDaoEmpempitem  = db_utils::getDao("empempitem");
                    $sSqlVerificacao = $oDaoEmpempitem->sql_query_item_pacto(null,
                      null,
                      "o88_pactovalor",
                      null,
                      "o105_empempitem = {$aItens[$iInd]->e62_sequencial}");
                    $rsVerificacao   = $oDaoEmpempitem->sql_record($sSqlVerificacao);
                    if ($oDaoEmpempitem->numrows > 0) {

                        $oItemPacto = db_utils::fieldsMemory($rsVerificacao, 0);
                        try {
                            $this->baixarSaldoPacto($aItens[$iInd]->e62_sequencial,
                              $oItemPacto->o88_pactovalor,
                              $aItens[$iInd]->quantidade * -1,
                              $aItens[$iInd]->vlrtot * -1);
                        } catch (Exception $eEmpenho) {

                            $this->lSqlErro = true;
                            $this->sErroMsg = "Erro [" . $eEmpenho->getCode() . "] - " . str_replace("\\n",
                                "\n",
                                $eEmpenho->getMessage());

                            return false;

                        }
                    }
                }
            }
        }
        /*
     ** Atualizamos as solicitações marcadas como atendidas....
     */
        if (!$this->lSqlErro && is_array($aSolicitacoes)) {

            $clempsolicitaanul = $this->usarDao("empsolicitaanul", true);
            for ($iInd = 0; $iInd < count($aSolicitacoes); $iInd++) {

                $clempsolicitaanul->e35_situacao   = 2;
                $clempsolicitaanul->e35_sequencial = $aSolicitacoes[$iInd]->e35_sequencial;
                $clempsolicitaanul->alterar($aSolicitacoes[$iInd]->e35_sequencial);
                if ($clempsolicitaanul->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro[16]\nNão Foi possível anular empenho.Erro ao Atualizar situação da solicitaçao de anulação.";
                    $this->sErroMsg .= "\nErro:{$clempsolicitaanul->erro_msg}";

                    return false;
                }
            }
        }

        if (empty($sMotivo)) {
            $sMotivo = 'Anulação de empenho';
        }

        $sComplemento = $sMotivo;
        $iAnoUsu      = db_getsession("DB_anousu");

        /*
     ** Iniciamos os lancamentos contabeis para anulação do empenho.
     */
        $dDataUsu = date("Y-m-d", db_getsession("DB_datausu"));
        for ($iEle = 0; $iEle < count($clempelemento->numrows); $iEle++) {

            $oElemento = db_utils::fieldsMemory($rsEmpElemento, $iEle);
            if (!$this->lSqlErro) {

                $iAnoUsu = db_getsession("DB_anousu");

                /*
         ** Atualização do orçamento.
         */
                if (!$this->lSqlErro && $this->dadosEmpenho->e60_anousu == $iAnoUsu) {

                    $rsFcLancam = db_query("select fc_lancam_dotacao({$this->dadosEmpenho->e60_coddot},
                                                           '{$dDataUsu}',
                                                           {$iCodigoDocumento},
                                                           {$nValorAnular}) as dotacao");
                    $oFcLancam  = db_utils::fieldsMemory($rsFcLancam, 0);
                    if (substr($oFcLancam->dotacao, 0, 1)
                      == 0
                    ) { //quando o primeiro caractere for igual a zero eh porque deu erro

                        $this->lSqlErro = true;
                        $this->sErroMsg = "Erro [16]:Erro na atualização do orçamento \\n " . substr($dotacao, 1);
                    }
                }

                try {


                    /**
                     * Valida parametro de integracao da contabilidade com contratos
                     */
                    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                    $oContaCorrenteDetalhe->setCredor($oEmpenhoFinanceiro->getCgm());
                    $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
                    $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
                    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);

                    $oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
                    $oEventoContabil     = new EventoContabil($iCodigoDocumento, $iAnoUsu);

                    $oLancamentoAuxiliar->setCaracteristicaPeculiar($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
                    $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());
                    $oLancamentoAuxiliar->setNumeroEmpenho($this->numemp);
                    $oLancamentoAuxiliar->setValorTotal(round($nValorAnular, 2));
                    $oLancamentoAuxiliar->setEmpenhoFinanceiro($oEmpenhoFinanceiro);
                    $oLancamentoAuxiliar->setObservacaoHistorico($sComplemento);
                    $oLancamentoAuxiliar->setCodigoElemento($oElemento->e64_codele);
                    $oLancamentoAuxiliar->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
                    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);
                    $oEventoContabil->executaLancamento($oLancamentoAuxiliar);


                } catch (Exception $eErro) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = $eErro->getMessage();
                }

            }

        }

        /**
         * Lancamentos do contrato
         * - busca contrato do empenho, caso encontre, efetua lançamento
         */
        $oDataImplantacao = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
        $oInstituicao     = new Instituicao(db_getsession('DB_instit'));

        if (USE_PCASP && ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataImplantacao, $oInstituicao)) {

            $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
            $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_file(null,
              "e100_acordo",
              null,
              "e100_numemp = {$this->numemp}");

            $rsContrato = $oDaoEmpenhoContrato->sql_record($sSqlContrato);
            if (!$this->lSqlErro && $oDaoEmpenhoContrato->numrows > 0) {

                try {

                    $oAcordo                   = new Acordo(db_utils::fieldsMemory($rsContrato, 0)->e100_acordo);
                    $oEventoContabilAcordo     = new EventoContabil(903, $iAnoUsu);
                    $oLancamentoAuxiliarAcordo = new LancamentoAuxiliarAcordo();
                    $oLancamentoAuxiliarAcordo->setEmpenho($oEmpenhoFinanceiro);
                    $oLancamentoAuxiliarAcordo->setAcordo($oAcordo);
                    $oLancamentoAuxiliarAcordo->setValorTotal(round($nValorAnular, 2));

                    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
                    $oContaCorrenteDetalhe->setAcordo($oAcordo);
                    $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
                    $oLancamentoAuxiliarAcordo->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

                    $oEventoContabilAcordo->executaLancamento($oLancamentoAuxiliarAcordo);

                } catch (Exception $eErro) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "({$eErro->getMessage()})";
                }
            }
        }

        /*
     ** fim dos lancamentos Contabeis e verificamos se o usuario solicitou a recriacao do saldo...
     */
        if (!$this->lSqlErro) {

            $iAutori         = 0;
            $clempautitem    = $this->usarDao("empempaut", true);
            $rsItemSolic     = $clempautitem->sql_record($clempautitem->sql_query_file($this->numemp,
              "distinct e61_autori as autori"));
            $iNumRowsItemSol = $clempautitem->numrows;
            if ($iNumRowsItemSol > 0) {
                $oItensSolic = db_utils::fieldsMemory($rsItemSolic, 0);
                $iAutori     = $oItensSolic->autori;
            }
            // Se nao for RP e valor anulado nao for parcial e tiver solicitacao de compras
            if ($this->dadosEmpenho->e60_anousu >= db_getsession("DB_anousu")
              && (round($this->dadosEmpenho->e60_vlremp, 2) - round(($nValorAnular + $this->dadosEmpenho->e60_vlranu), 2)
                == 0)
              && $iAutori > 0
            ) {

                $rsDotacao     = db_dotacaosaldo(8,
                  2,
                  2,
                  "true",
                  "o58_coddot={$this->dadosEmpenho->e60_coddot}",
                  db_getsession("DB_anousu"));
                $oDotacaoSaldo = db_utils::fieldsMemory($rsDotacao, 0);

                $saldo                                     = (0 + $oDotacaoSaldo->atual_menos_reservado);
                $saldo                                     = trim(str_replace(".", "", db_formatar($saldo, "f")));
                $aDotacao[$this->dadosEmpenho->e60_coddot] = str_replace(",", ".", $saldo);
                $clpcprocitem                              = db_utils::getDao("pcprocitem");
                $clempautoriza                             = db_utils::getDao("empautoriza");
                $clorcreservaaut                           = db_utils::getDao("orcreservaaut");
                $clorcreserva                              = db_utils::getDao("orcreserva");
                $clorcreserva                              = db_utils::getDao("orcreservasol");
                $clempautitem                              = $this->usarDao("empautitem");
                try {
                    $clempautoriza->anulaAutorizacao($iAutori, $this->getRecriarSaldo());
                } catch (Exception $eErro) {
                    $this->lSqlErro = true;
                    $this->sErroMsg = $eErro->getMessage();
                }

            }

        }

        /**
         * Caso o empenho seje da folha, devemso excluir a vinculaçao do empenho
         */
        require_once(modification("classes/db_rhempenhofolhaempenho_classe.php"));
        $oDaoEmpenhoFolhaEmpenho = new cl_rhempenhofolhaempenho();
        $sSqlEmpenhoFolha        = $oDaoEmpenhoFolhaEmpenho->sql_query_file(null,
          "*",
          null,
          "rh76_numemp = {$this->numemp}");
        $rsEmpenhoFolha          = $oDaoEmpenhoFolhaEmpenho->sql_record($sSqlEmpenhoFolha);
        if ($oDaoEmpenhoFolhaEmpenho->numrows > 0) {

            $oDaoEmpenhoFolhaEmpenho->excluir(null, "rh76_numemp = {$this->numemp}");
            if ($oDaoEmpenhoFolhaEmpenho->erro_status == 0) {

                $this->lSqlErro = true;
                $this->sErroMsg = "Erro[27]:Empenho não anulado.\n";
                $this->sErroMsg .= "({$oDaoEmpenhoFolhaEmpenho->erro_msg})";

            }
        }

        if ($lTransacao) {

            $this->sMsgErro = urlencode($this->sMsgErro);
            db_fim_transacao($this->lSqlErro);
        }

        if (!$this->lSqlErro) {
            $this->sErroMsg = "Anulação efetuada com sucesso.";
        }

    }//end function anularEmpenho;

    /**
     * Retorna os dados do empenho, caso ele seje RP
     *
     * @param integer $iTipo define o tipo de rp 1 - Nao Processao 2 processado
     */

    function getDadosRP($iTipo) {

        $this->getDados($this->numemp);
        $oEmpResto      = $this->usarDao("empresto", true);
        $this->lSqlErro = false;
        $this->sErroMsg = '';
        $rsEmpResto     = $oEmpResto->sql_record($oEmpResto->sql_query_empenho($this->anousu, $this->numemp));
        if ($oEmpResto->numrows == 0) {

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro [15] - Empenho não cadastrado com restos a pagar em {$this->anousu}!";

            return false;

        } else {

            if ($iTipo == 2) {

                $rsNotas              = $this->getNotas($this->numemp, "e50_anousu < {$this->anousu}");
                $nValorProcessado     = 0;
                $nValorProcessadoNota = 0;
                $aNotasProcessadas    = array();
                if ($this->iNumRowsNotas > 0) {

                    for ($iInd = 0; $iInd < $this->iNumRowsNotas; $iInd++) {

                        $oEmpNota             = db_utils::fieldsMemory($rsNotas, $iInd, false, false, $this->getEncode());
                        $nValorProcessadoNota = $oEmpNota->e70_vlrliq - $oEmpNota->e53_vlrpag;
                        $nValorProcessado += $nValorProcessadoNota;
                        $aNotasProcessadas[] = $oEmpNota;

                    }
                    if (!$this->lSqlErro) {

                        $this->dadosEmpenho->aNotasRP         = $aNotasProcessadas;
                        $this->dadosEmpenho->nValorProcessado = $nValorProcessado;

                        return true;

                    }
                } else {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro [16] - Empenho nao possui liquidações";

                    return false;

                }
            } else if ($iTipo == 1) {

                $sWhereNotas             = "e69_anousu < {$this->anousu} and (e70_vlrliq is null or e70_vlrliq = 0)";
                $rsNotas                 = $this->getNotas($this->numemp, $sWhereNotas);
                $nValorNaoProcessado     = $this->dadosEmpenho->e60_vlremp - $this->dadosEmpenho->e60_vlrliq
                  - $this->dadosEmpenho->e60_vlranu;
                $nValorNaoProcessadoNota = 0;
                $aNotasNaoProcessadas    = array();
                $aItensNota              = array();
                if ($this->iNumRowsNotas > 0) {

                    for ($iInd = 0; $iInd < $this->iNumRowsNotas; $iInd++) {

                        $oEmpNota               = db_utils::fieldsMemory($rsNotas, $iInd, false, false, $this->getEncode());
                        $aItensNota             = $this->getItensNota($oEmpNota->e69_codnota);
                        $aNotasNaoProcessadas[] = $oEmpNota;

                    }
                    if (!$this->lSqlErro) {
                        $this->dadosEmpenho->aNotasRP = $aNotasNaoProcessadas;
                    }
                }
                $this->dadosEmpenho->nValorProcessado = $nValorNaoProcessado;
                $rsItens                              = $this->getItensSaldo();
                $aItens                               = array();
                //print_r($aItensNota);
                if ($rsItens) {

                    for ($iInd = 0; $iInd < $this->iNumRowsItens; $iInd++) {

                        $oEmpItem = db_utils::fieldsMemory($rsItens, $iInd, false, false, $this->getEncode());
                        for ($iItens = 0; $iItens < count($aItensNota); $iItens++) {

                            if ($oEmpItem->e62_sequencial == $aItensNota[$iItens]->e72_empempitem) {

                                //                $oEmpItem->saldo      -= $aItensNota[$iItens]->e72_qtd;
                                //                $oEmpItem->saldovalor -= $aItensNota[$iItens]->e72_valor;

                            }
                        }
                        $oEmpItem->saldo      = $oEmpItem->saldo < 0 ? 0 : $oEmpItem->saldo;
                        $oEmpItem->saldovalor = $oEmpItem->saldovalor < 0 ? 0 : $oEmpItem->saldovalor;
                        $aItens[]             = $oEmpItem;
                    }
                }
                $this->dadosEmpenho->aItens = $aItens;

                return true;
            }
        }
    }

    function estornarRP($iTipo,
      $aNotas = null,
      $nValorEstornado,
      $sMotivo = '',
      $aItens = null,
      $iTipoAnulacao = null) {

        if (!db_utils::inTransaction()) {
            throw new exception("Não foi possível iniciar Procedimento.Nao foi possível achar uma transacao valida");
        }
        $nValorLiquidado = 0;
        $nValorAnulado   = 0;
        $iQtdeItens      = 0;
        $this->getDados($this->numemp);
        $oEmpResto      = $this->usarDao("empresto", true);
        $this->lSqlErro = false;
        $rsEmpResto     = $oEmpResto->sql_record($oEmpResto->sql_query_empenho($this->anousu, $this->numemp));
        if (is_array($aItens)) {
            $iQtdeItens = count($aItens);
        }
        if ($oEmpResto->numrows == 0) {

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro [15] - Empenho não cadastrado com restos a pagar em {$this->anousu}!";
            throw new exception($this->sErroMsg);

            return false;

        } else {

            /*
       * estorna Liquidacao RP Processado;
       */
            if ($iTipo == 2) {

                /*
         * Para Anular um RP Processado, é necessário ter ao menos
         * uma nota selecionado pelo usuario
         */
                if (is_array($aNotas) && count($aNotas) == 0) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "[Erro 19] - Deve existir uma nota para ser extornada";
                    throw new exception($this->sErroMsg);

                    return false;
                }

                /*
         * Verificamos se o empenho possui saldo para anular;
         * saldo solicitado deve ser menor que o saldo do empenho
         */

                $iCodDoc         = 31;
                $nSaldoAEstornar = $this->dadosEmpenho->e60_vlrliq
                  - $this->dadosEmpenho->e60_vlrpag;// - $this->dadosEmpenho->e60_vlranu ;

                if (round($nSaldoAEstornar, 2) < round($nValorEstornado, 2)) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro [17] - Sem saldo a estornar {$nSaldoAEstornar} < {$nValorEstornado}";
                    throw new exception($this->sErroMsg);

                    return false;

                }

            } else if ($iTipo == 1) {
                $iCodDoc = 32;
            }

            /*
       * Fazemos as verificações de saldo da funcao fc_lancamento.
       */

            $sqlFcLancamento = "select fc_verifica_lancamento({$this->numemp},'";
            $sqlFcLancamento .= date("Y-m-d", db_getsession("DB_datausu")) . "',";
            $sqlFcLancamento .= "{$iCodDoc},{$nValorEstornado})";
            $rsFcLancamento    = db_query($sqlFcLancamento);
            $sErroFclancamento = pg_result($rsFcLancamento, 0, 0);
            if (substr($sErroFclancamento, 0, 2) > 0) {

                $this->sErroMsg = substr($sErroFclancamento, 3);
                $this->lSqlErro = true;
                throw new exception($this->sErroMsg);

            }

            /*
       * -- empenho de RP processado, devemos diminuir o valor estornado da liquidação,
       * e lancar como anulado (empempenho e empelemento)
       * -- Empenho RP nao processado apenas lancamos o valor anulado;
       */
            if (!$this->lSqlErro) {

                if ($iTipo == 2) {

                    $nValorLiquidado = $this->dadosEmpenho->e60_vlrliq - $nValorEstornado;
                    $nValorAnulado   = $nValorEstornado;

                } else if ($iTipo == 1) {

                    $nValorLiquidado = $this->dadosEmpenho->e60_vlrliq;
                    $nValorAnulado   = $nValorEstornado;
                }
                /*
         * Atualizamos a empempenho e empelemento;
         */

                $this->clempempenho->e60_numemp = $this->numemp;
                $this->clempempenho->e60_vlrliq = "$nValorLiquidado";
                $this->clempempenho->e60_vlranu = $nValorAnulado + $this->dadosEmpenho->e60_vlranu;
                $this->clempempenho->alterar($this->numemp);
                if ($this->clempempenho->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro [18] - Erro ao atualizar valores do empenho.";
                    throw new exception($this->sErroMsg);
                }

                if (!$this->lSqlErro) {

                    $rsEmpEle = $this->clempelemento->sql_record($this->clempelemento->sql_query($this->numemp));
                    if ($this->clempelemento->numrows == 0) {

                        $this->lSqlErro = false;
                        $this->sErroMsg = "Erro [20] Empenho sem elemento cadastrado";
                        throw new exception($this->sErroMsg);

                        return false;

                    }

                    $oElemento                       = db_utils::fieldsMemory($rsEmpEle, 0);
                    $this->clempelemento->e64_numemp = $this->numemp;
                    $this->clempelemento->e64_codele = $oElemento->e64_codele;
                    $this->clempelemento->e64_vlrliq = "$nValorLiquidado";
                    $this->clempelemento->e64_vlranu = $nValorAnulado + $oElemento->e64_vlranu;
                    $this->clempelemento->alterar($this->numemp);

                    if ($this->clempelemento->erro_status == 0) {

                        $this->lSqlErro = true;
                        $this->sErroMsg = "Erro [19] - Erro ao atualizar valores do elemento do empenho.";
                        $this->sErroMsg .= "\n[Técnico] -{$this->clempelemento->erro_msg}";
                        throw new exception($this->sErroMsg);

                    }
                }
            }
            /*
       * Atualizamos empnota e empnotaele, caso existam notas para essa anulação.
       */
            if (!$this->lSqlErro) {

                if (count($aNotas) > 0) {

                    $oEmpNota     = $this->usarDao("empnota", true);
                    $oEmpNotaEle  = $this->usarDao("empnotaele", true);
                    $oEmpNotaItem = $this->usarDao("empnotaitem", true);
                    for ($iNotas = 0; $iNotas < count($aNotas); $iNotas++) {

                        $sSqlDadosElementoNota = $oEmpNotaEle->sql_query_file($aNotas[$iNotas]->iCodNota);
                        $rsDadosElementoNota   = $oEmpNotaEle->sql_record($sSqlDadosElementoNota);
                        if ($oEmpNotaEle->numrows != 1) {

                            $this->sErroMsg = "Nota {$aNotas[$iNotas]->iCodNota} não possui elementos lançados.\n";
                            $this->sErroMsg .= "Não será possível estornar a nota.";
                            throw new Exception($this->sMsgErro);
                        }

                        $oDadosElementoNota = db_utils::fieldsMemory($rsDadosElementoNota, 0);
                        /*
             * as notas no a anulaçao de um RP processado deve ter do seu valor liquidado descontado o valor que foi
             * anulado,  e acrescentar no seu valor anulado o valor que foi solicitado para a anulação.
             */
                        $nNovoValorLiquidado      = $oDadosElementoNota->e70_vlrliq - $aNotas[$iNotas]->sValorEstornado;
                        $oEmpNotaEle->e70_vlranu  = $oDadosElementoNota->e70_vlranu + $aNotas[$iNotas]->sValorEstornado;
                        $oEmpNotaEle->e70_vlrliq  = "{$nNovoValorLiquidado}";
                        $oEmpNotaEle->e70_codnota = $aNotas[$iNotas]->iCodNota;
                        $oEmpNotaEle->alterar($aNotas[$iNotas]->iCodNota);
                        if ($oEmpNotaEle->erro_status == 0) {

                            $this->sErroMsg = "Erro[20]  Não foi possivel Alterar nota\n";
                            $this->sErroMsg .= "[Técnico] {$oEmpnotaEle->erro_msg}";
                            throw new exception($this->sErroMsg);

                            return false;

                        } else {
                            //Anulamos os itens da nota
                            $sSqlItensNota = $oEmpNotaItem->sql_query_file(null,
                              "e72_sequencial,e72_valor,e72_empempitem,e72_qtd,e72_vlranu",
                              null,
                              "e72_codnota = {$aNotas[$iNotas]->iCodNota}");
                            $rsItens       = $oEmpNotaItem->sql_record($sSqlItensNota);
                            $iNumRowsItens = $oEmpNotaItem->numrows;
                            for ($iItens = 0; $iItens < $iNumRowsItens; $iItens++) {

                                $oItens                       = db_utils::fieldsMemory($rsItens, $iItens);
                                $oEmpNotaItem->e72_vlranu     = $oItens->e72_valor;
                                $oEmpNotaItem->e72_sequencial = $oItens->e72_sequencial;
                                $oEmpNotaItem->alterar($oItens->e72_sequencial);
                                $iIndice                     = $iQtdeItens;
                                $aItens[$iIndice]->iCodItem  = $oItens->e72_empempitem;
                                $aItens[$iIndice]->nVlrTotal = $oItens->e72_valor - $oItens->e72_vlranu;
                                $aItens[$iIndice]->nQtde     = $oItens->e72_qtd;
                                $iQtdeItens++;
                                if ($oEmpNotaItem->erro_status == 0) {

                                    $this->sErroMsg = "Erro[21]  Não foi possível alterar nota.";
                                    $this->sErroMsg .= "[Técnico] {$oEmpNotaItem->erro_msg}.";
                                    throw new exception($this->sErroMsg);

                                    return false;
                                }
                            }
                        }
                        if ($iTipo == 2) {

                            $oPagOrdemNota  = $this->usarDao("pagordemnota", true);
                            $oPagOrdemEle   = $this->usarDao("pagordemele", true);
                            $sWhere         = "e71_codnota  = {$aNotas[$iNotas]->iCodNota} and e71_anulado is false";
                            $sSqlDadosOrdem = $oPagOrdemNota->sql_query_valorordem(null,
                              null,
                              "pagordemnota.*,
                                                                      pagordemele.*",
                              null,
                              $sWhere);
                            $res            = $oPagOrdemNota->sql_record($sSqlDadosOrdem);
                            if ($oPagOrdemNota->numrows > 0) {

                                /**
                                 * pesquisa o valor da ordem de pagamento
                                 */
                                $oPagOrdem                = db_utils::fieldsmemory($res, 0);
                                $oPagOrdemEle->e53_codord = $oPagOrdem->e71_codord;
                                $oPagOrdemEle->e53_codele = $oElemento->e64_codele;
                                $oPagOrdemEle->e53_vlranu = +$oPagOrdem->e53_vlranu + $aNotas[$iNotas]->sValorEstornado;
                                $oPagOrdemEle->alterar($oPagOrdemEle->e53_codord, $oElemento->e64_codele);
                                if ($oPagOrdemEle->erro_status == 0) {

                                    $this->lSqlErro = true;
                                    $this->sErroMsg = "Pagordemele:" . $oPagOrdemEle->erro_msg;
                                    throw new exception($this->sErroMsg);
                                }

                                $iCodigoNota  = $aNotas[$iNotas]->iCodNota;
                                $iCodigoOrdem = $oPagOrdemNota->e71_codord;
                                /**
                                 * a ordem é marcada como anulada apenas se ela estiver com o valor totalmente utilizada,
                                 * isto é o valor pago mais o valor anulado é igual ao valor da ordem.
                                 */
                                $nValorPagoMaisAnulado = round($oPagOrdemEle->e53_vlranu, 2) + round($oPagOrdemEle->e53_vlrpag, 2);
                                if (round($oPagOrdem->e53_valor, 2) == $nValorPagoMaisAnulado) {

                                    $oPagOrdemNota->e71_codord  = $oPagOrdemEle->e53_codord;
                                    $oPagOrdemNota->e71_codnota = $aNotas[$iNotas]->iCodNota;
                                    $oPagOrdemNota->e71_anulado = 'true';
                                    $oPagOrdemNota->alterar($oPagOrdemNota->e71_codord, $oPagOrdemNota->e71_codnota);
                                    if ($oPagOrdemNota->erro_status == 0) {

                                        $this->lSqlErro = true;
                                        $this->sErroMsg = "Pagordemnota:" . $clpagordenota->erro_msg;
                                        throw new exception($this->sErroMsg);

                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }

                /*
         *incluimos Empanulado e (empanuladoitem (somente RP nao proc));
         */
                $oEmpAnulado                     = $this->usarDao("empanulado", true);
                $oEmpAnulado->e94_numemp         = $this->numemp;
                $oEmpAnulado->e94_valor          = $nValorEstornado;
                $oEmpAnulado->e94_saldoant       = $nValorEstornado; // carlos atualizado
                $oEmpAnulado->e94_motivo         = $sMotivo;
                $oEmpAnulado->e94_empanuladotipo = $iTipoAnulacao;
                $oEmpAnulado->e94_data           = date("Y-m-d", db_getsession("DB_datausu"));
                $oEmpAnulado->incluir(null);
                $iCodAnu = $oEmpAnulado->e94_codanu;
                if ($oEmpAnulado->erro_status == 0) {

                    $this->lSqlErro = true;
                    $this->sErroMsg = "Erro[22]\nNão Foi possível estornar RP.Erro ao cadastrar Empenho como anulado.";
                    $this->sErroMsg .= "\nErro:{$oEmpAnulado->erro_msg}";
                    throw new exception($this->sErroMsg);

                    return false;
                }
                /*
         * Incluimos os itens anulados;
         */
                if (is_array($aItens) && count($aItens) > 0) {

                    $oEmpAnuladoItem = $this->usarDao("empanuladoitem", true);
                    for ($iInd = 0; $iInd < count($aItens); $iInd++) {

                        $oEmpAnuladoItem->e37_empempitem = $aItens[$iInd]->iCodItem;
                        $oEmpAnuladoItem->e37_empanulado = $iCodAnu;
                        $oEmpAnuladoItem->e37_vlranu     = $aItens[$iInd]->nVlrTotal;
                        $oEmpAnuladoItem->e37_qtd        = $aItens[$iInd]->nQtde;
                        $oEmpAnuladoItem->incluir(null);
                        if ($oEmpAnuladoItem->erro_status == 0) {

                            $this->lSqlErro = true;
                            $this->sErroMsg = "Erro[24]\nNão Foi possível estornar RP.Erro incluir item como anulado.";
                            $this->sErroMsg .= "\nErro:{$oEmpAnuladoItem->erro_msg}";
                            throw new exception($this->sErroMsg);

                            return false;
                        }
                    }
                }
                /*
         * informações do elemento anulado
         */
                $oEmpAnuladoEle             = $this->usarDao("empanuladoele", true);
                $oEmpAnuladoEle->e95_codanu = $iCodAnu;
                $oEmpAnuladoEle->e95_codele = $oElemento->e64_codele;
                $oEmpAnuladoEle->e95_valor  = $nValorEstornado;
                $oEmpAnuladoEle->incluir($iCodAnu);
                if ($oEmpAnuladoEle->erro_status == 0) {

                    $lSqlErro       = true;
                    $this->sErroMsg = "Erro[25]\nNão Foi possível anular empenho.Erro ao incluir elemento anulado.";
                    $this->sErroMsg .= "\nErro:{$oEmpAnuladoEle->erro_msg}";
                    throw new exception($this->sErroMsg);

                    return false;

                }
                /*
         * Lançamentos contabeis
         */
                $oLancam = new LancamentoContabil($iCodDoc,
                  $this->anousu,
                  date("Y-m-d", db_getsession("DB_datausu")),
                  $nValorEstornado);
                $oLancam->setCgm($this->dadosEmpenho->e60_numcgm);
                $oLancam->setEmpenho($this->numemp, $this->dadosEmpenho->e60_anousu, $this->dadosEmpenho->e60_codcom);
                $oLancam->setElemento($oElemento->e64_codele);
                $oLancam->setComplemento($sMotivo);
                if ($iTipo == 2) {

                    if ($iCodigoNota != "") {
                        $oLancam->setNota($iCodigoNota);
                    }
                    if ($iCodigoOrdem != "") {
                        $oLancam->setOrdemPagamento($iCodigoOrdem);
                    }
                }
                $oLancam->salvar();
            }
        }
    }

    /**
     * Carrega a classe $sClasse
     *
     * @param string  $sClasse   nome da tabela
     * @param boolean $rInstance se deve retornar a instancia da classe
     *
     * @returns Object
     */

    function usarDao($sClasse, $rInstance = false) {

        if (!class_exists("cl_{$sClasse}")) {
            require_once modification("classes/db_{$sClasse}_classe.php");
        }
        if ($rInstance) {

            eval ("\$objRet = new cl_{$sClasse};");

            return $objRet;

        }
    }

    /**
     * Retorna a informação da ordem agendada.
     *
     * @param integer $iCodNota Código da nota
     *
     * @return string
     */
    function getInfoAgenda($iCodNota) {

        $clpagordemnota = db_utils::getDao("pagordemnota");
        $res            = $clpagordemnota->sql_record($clpagordemnota->sql_query(null,
          null,
          "*",
          null,
          "e71_codnota = {$iCodNota} and e71_anulado is false"));
        if ($clpagordemnota->numrows > 0) {

            $oNota      = db_utils::fieldsMemory($res, 0);
            $sSqlAgenda = "select e80_codage,";
            $sSqlAgenda .= "       to_char(e80_data,'dd/mm/YYYY') as e80_data";
            $sSqlAgenda .= "  from empord ";
            $sSqlAgenda .= "        inner join empagemov on e82_codmov = e81_codmov";
            $sSqlAgenda .= "        inner join empage    on e81_codage = e80_codage";
            $sSqlAgenda .= "  where e82_codord = {$oNota->e71_codord}";
            $sSqlAgenda .= "    and e81_cancelado is null";
            $rsAgenda = $clpagordemnota->sql_record($sSqlAgenda);
            if ($clpagordemnota->numrows > 0) {

                $sVir       = "";
                $sMsgAgenda = "";
                for ($i = 0; $i < $clpagordemnota->numrows; $i++) {

                    $oAgenda = db_utils::fieldsMemory($rsAgenda, $i);
                    $sMsgAgenda .= "{$sVir} {$oAgenda->e80_codage} ({$oAgenda->e80_data})";
                    $sVir = ",";

                }

                return $sMsgAgenda;
            }
        }
    }

    function getCodigoMovimento() {

        return $this->iCodigoMovimento;
    }

    function baixarSaldoPacto($iEmpItem, $iItemPacto, $nQuantidade, $nValor) {

        require_once(modification("model/itempacto.model.php"));
        $oItemPacto = new itemPacto($iItemPacto);
        $oItemPacto->baixarSaldoEmpenho($nQuantidade, $nValor, $iEmpItem);

    }

    /**
     * Funcao para pesquisar os empenhos para a liberação
     *
     * @param object $oFiltro
     *
     * @return array $aItens
     */

    public function getEmpenhosLiberados($oFiltro) {


        $sWhere         = "";
        $sAnd           = "";
        $iAnoUso        = db_getsession("DB_anousu");
        $iInstit        = db_getsession("DB_instit");
        $oDaoEmpempenho = db_utils::getDao("empempenho");
        $aItens         = array();

        $sCampos = "e60_numemp, e60_codemp, e60_anousu, e60_vlremp, e60_vlrliq, e60_vlranu, z01_cgccpf, e22_sequencial, ";
        $sCampos .= "z01_nome, e60_emiss, (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) as saldo,        ";
        $sCampos .= "exists (select 1                                                                                    ";
        $sCampos .= "          from matordemitem                                                                         ";
        $sCampos .= "               left join matordemanu on m53_codordem = m52_codordem                                 ";
        $sCampos .= "         where m52_numemp = e60_numemp                                                              ";
        $sCampos .= "           and m53_codordem is null) as temordemdecompra,                                           ";
        $sCampos .= "       (select ridepto||'-'||descrdepto                                                             ";
        $sCampos .= "          from fc_origem_empenho(e60_numemp)                                                        ";
        $sCampos .= "               inner join db_depart on ridepto = coddepto limit 1) as origem                        ";

        if (isset($oFiltro->codempini) && !empty($oFiltro->codempini)) {

            $codempIni = split("/", $oFiltro->codempini);
            if (isset($oFiltro->codempfim) && !empty($oFiltro->codempfim)) {

                $codempFim = split("/", $oFiltro->codempfim);
                $str       = "  ( ( e60_codemp::integer >= " . $codempIni[0]
                  . " and e60_anousu = {$iAnoUso} )                          ";
                $str .= " and ( e60_codemp::integer <= " . $codempFim[0]
                  . " and e60_anousu = {$iAnoUso} ) )                      ";

            } else {

                $codemp = split("/", $oFiltro->codempini);
                if (count($codemp) > 1) {
                    $str = " e60_codemp = '" . $codemp[0] . "' and e60_anousu = " . $codemp[1] . " ";
                } else {
                    $str = " e60_codemp = '" . $oFiltro->codempini . "' and e60_anousu = {$iAnoUso} ";
                }
            }

            $sWhere .= "{$sAnd}{$str}";
            $sAnd = " and ";
        }

        if (isset($oFiltro->numcgm) && !empty($oFiltro->numcgm)) {

            $sWhere .= "{$sAnd} e60_numcgm = {$oFiltro->numcgm}";
            $sAnd = " and ";
        }

        if (isset($oFiltro->dtemissini) && isset($oFiltro->dtemissfim)) {

            if (!empty($oFiltro->dtemissini)) {
                $dtDataIni = split("/", $oFiltro->dtemissini);
                $dtDataIni = $dtDataIni[2] . "-" . $dtDataIni[1] . "-" . $dtDataIni[0];
            }

            if (!empty($oFiltro->dtemissini)) {
                $dtDataFim = split("/", $oFiltro->dtemissfim);
                $dtDataFim = $dtDataFim[2] . "-" . $dtDataFim[1] . "-" . $dtDataFim[0];
            }

            if (!empty($dtDataIni) && !empty($dtDataFim)) {

                $sWhere .= "{$sAnd} e60_emiss between '{$dtDataIni}' and '{$dtDataFim}'";
                $sAnd = " and ";
            } else if (!empty($dtDataIni)) {

                $sWhere .= "{$sAnd} e60_emiss = '{$dtDataIni}'";
                $sAnd = " and ";
            } else if (!empty($dtDataFim)) {

                $sWhere .= "{$sAnd} e60_emiss <= '{$dtDataFim}'";
                $sAnd = " and ";
            }
        }

        $sWhere .= " {$sAnd} e60_instit = {$iInstit}                                                                      ";
        $sWhere .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0                                ";
        $sWhere .= " and not exists (select 1                                                                             ";
        $sWhere .= "                   from matordemitem                                                                  ";
        $sWhere .= "                        left join matordemanu on m53_codordem = m52_codordem                          ";
        $sWhere .= "                  where m52_numemp = e60_numemp                                                       ";
        $sWhere .= "                    and m53_codordem is null)                                                         ";

        $sSqlEmpenhos    = $oDaoEmpempenho->sql_query_liberarempenho(null, $sCampos, "e60_numemp", $sWhere);
        $rsSqlEmpEmpenho = $oDaoEmpempenho->sql_record($sSqlEmpenhos);
        $aItens          = db_utils::getCollectionByRecord($rsSqlEmpEmpenho, true, false, true);

        return $aItens;
    }

    /**
     * Funcao para liberar empenhos
     *
     * @param array $aEmpenhos
     *
     * @return empempenholiberado
     */

    public function liberarEmpenho($aEmpenhos) {

        $oDaoEmpempenhoLiberado = db_utils::getDao("empempenholiberado");

        if (!db_utils::inTransaction()) {
            throw new Exception('Nao existe transação com o banco de dados ativa.');
        }

        foreach ($aEmpenhos as $oEmpenho) {

            $sCampos = "empempenholiberado.*";
            $sWhere  = "e22_numemp = {$oEmpenho->iNumemp}";

            $sSqlEmpEmpenhoLiberado  = $oDaoEmpempenhoLiberado->sql_query(null, $sCampos, null, $sWhere);
            $rsSqlEmpEmpenhoLiberado = $oDaoEmpempenhoLiberado->sql_record($sSqlEmpEmpenhoLiberado);
            if ($oEmpenho->lLiberar) {

                if ($oDaoEmpempenhoLiberado->numrows == 0) {

                    $oDaoEmpempenhoLiberado->e22_numemp     = $oEmpenho->iNumemp;
                    $oDaoEmpempenhoLiberado->e22_id_usuario = db_getsession('DB_id_usuario');
                    $oDaoEmpempenhoLiberado->e22_data       = date("Y-m-d", db_getsession("DB_datausu"));
                    $oDaoEmpempenhoLiberado->e22_hora       = db_hora();
                    $oDaoEmpempenhoLiberado->incluir(null);
                    if ($oDaoEmpempenhoLiberado->erro_status == 0) {
                        throw new Exception($oDaoEmpempenhoLiberado->erro_msg);
                    }

                }

            } else {
                if ($oDaoEmpempenhoLiberado->numrows > 0) {

                    $oEmpenhosLiberados                     = db_utils::fieldsMemory($rsSqlEmpEmpenhoLiberado, 0);
                    $oDaoEmpempenhoLiberado->e22_sequencial = $oEmpenhosLiberados->e22_sequencial;
                    $oDaoEmpempenhoLiberado->excluir($oDaoEmpempenhoLiberado->e22_sequencial);
                    if ($oDaoEmpempenhoLiberado->erro_status == 0) {
                        throw new Exception($oDaoEmpempenhoLiberado->erro_msg);
                    }
                }

            }

        }

        return $this;

    }

    function getLancamentosContabeis($sWhere = '') {

        if (trim($sWhere) != "") {
            $sWhere = " and " . $sWhere;
        }
        $sSqlLancamentos = "select c70_codlan  as codigo,";
        $sSqlLancamentos .= "       c70_data    as data,";
        $sSqlLancamentos .= "       c53_descr   as descricaotipo,";
        $sSqlLancamentos .= "       c70_valor   as valor,";
        $sSqlLancamentos .= "       c71_coddoc  as tipo,";
        $sSqlLancamentos .= "       c53_tipo    as tipodocumento,";
        $sSqlLancamentos .= "       c72_complem as observacao,";
        $sSqlLancamentos .= "       e33_pagordemdesconto as desconto,";
        $sSqlLancamentos .= "       c80_codord as ordempagamento,";
        $sSqlLancamentos .= "       exists(select true";
        $sSqlLancamentos .= "          from corgrupocorrente ret";
        $sSqlLancamentos .= "               inner join corgrupo on ret.k105_corgrupo = k104_sequencial";
        $sSqlLancamentos .= "         where ret.k105_corgrupo = corgrupocorrente.k105_corgrupo";
        $sSqlLancamentos .= "          and  k105_corgrupotipo in(2,5)) as temretencao,";
        $sSqlLancamentos .= "       exists(select true";
        $sSqlLancamentos .= "                from pagordemnota inner join retencaopagordem on e71_codord = e20_pagordem";
        $sSqlLancamentos .= "                                                             and e71_anulado is false";
        $sSqlLancamentos .= "                     inner join retencaoreceitas on e23_retencaopagordem = e20_sequencial";
        $sSqlLancamentos .= "               where e71_codnota = c66_codnota ";
        $sSqlLancamentos .= "                 and e23_ativo is true) as temretencaonota,";
        $sSqlLancamentos .= "        k105_corgrupotipo as tipo";
        $sSqlLancamentos .= "  from conlancamemp";
        $sSqlLancamentos .= "       inner join conlancam      on c70_codlan            = c75_codlan ";
        $sSqlLancamentos .= "       inner join conlancamdoc   on c71_codlan            = c70_codlan";
        $sSqlLancamentos .= "       inner join conhistdoc     on c53_coddoc            = c71_coddoc";
        $sSqlLancamentos .= "       left  join conlancamcompl on c72_codlan            = c70_codlan";
        $sSqlLancamentos .= "       left  join conlancamnota   on c70_codlan           =  c66_codlan ";
        $sSqlLancamentos .= "       left  join conlancamord    on c70_codlan           =  c80_codlan ";
        $sSqlLancamentos .= "       left  join pagordemdescontolanc on c70_codlan      =  e33_conlancam ";
        $sSqlLancamentos .= "       left  join conlancamcorgrupocorrente on c70_codlan =  c23_conlancam ";
        $sSqlLancamentos .= "       left  join corgrupocorrente on c23_corgrupocorrente = k105_sequencial ";
        $sSqlLancamentos .= " where c75_numemp = {$this->numemp} {$sWhere}";
        $sSqlLancamentos .= " order by c75_data,";
        $sSqlLancamentos .= "          c75_codlan ";
        $rsLancamentos = db_query($sSqlLancamentos);
        $aLancamentos  = db_utils::getCollectionByRecord($rsLancamentos, false, false, true);

        return $aLancamentos;

    }

    function alterarDataLancamento($iCodigoLancamento, $dtNovaData) {

        if (!db_utils::inTransaction()) {
            throw new Exception("Não há transação aberta.\nProcedimento Cancelado");
        }

        $dtValidar = implode("-", array_reverse(explode("/", $dtNovaData)));
        /**
         * Verificamos se o lancamento informado é realmente do empenho
         */
        require_once(modification("classes/lancamentoContabil.model.php"));
        $oDadosLancamento = lancamentoContabil::getInfoLancamento($iCodigoLancamento, false);
        if ($oDadosLancamento->empenho != $this->numemp) {
            throw new Exception("o Lançamento ({$iCodigoLancamento}) informado não pertence ao empenho ({$this->numemp})");
        }

        /**
         * Verificamos se a data é menor que a data de encerramento da contabilidade
         */
        $sSqlDataEncerramento = "select c99_data ";
        $sSqlDataEncerramento .= "  from condataconf";
        $sSqlDataEncerramento .= " where c99_anousu = " . db_getsession("DB_anousu");
        $sSqlDataEncerramento .= "   and c99_instit = " . db_getsession("DB_instit");
        $rsDataEncerramento = db_query($sSqlDataEncerramento);
        if (pg_num_rows($rsDataEncerramento) > 0) {

            $dtDataEncerramento = db_utils::fieldsMemory($rsDataEncerramento, 0)->c99_data;
            if (db_strtotime($dtValidar) <= db_strtotime($dtDataEncerramento)) {

                $sMessage = "Data do lançamento não pode ser menor que a data do encerramento contábil ";
                $sMessage .= "(" . db_formatar($dtDataEncerramento, "d") . ")!\nOperação cancelada.";
                throw new Exception($sMessage);
            }
        }

        /**
         * Alteramos e validados o  lancamento pelo seu documento
         */
        switch ($oDadosLancamento->tipoevento) {


            case 10:

                /**
                 * Validamos  o ano do empenho.
                 * Caso o Ano modificado seje diferente do ano do lancamento, cancelamos a operacao;
                 */
                $aDataValidar    = explode("-", $dtValidar);
                $iAnoNovaData    = $aDataValidar[0];
                $aDataLancamento = explode("-", $oDadosLancamento->data);
                $iAnoLancamento  = $aDataLancamento[0];
                if ($iAnoNovaData != $iAnoLancamento) {

                    $sErroMensagem = "Não é permitido mudar o ano do lançamento.\n";
                    $sErroMensagem .= "Operação cancelada.";
                    throw new Exception($sErroMensagem);
                }
                /**
                 * Validamos a liquidacao de Empenho.
                 */
                $sWhereEmpenho       = "c71_coddoc in(3, 23, 33)";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtValidar) > db_strtotime($oLancamentoEmpenho->data)) {

                        $sErroMensagem = "Data do empenho  deve ser MENOR ou IGUAL a menor data de liquidacao.\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }

                /**
                 * Validamos o estornodoe Empenho.
                 */
                $sWhereEmpenho       = "c71_coddoc in(2, 31, 32)";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtValidar) > db_strtotime($oLancamentoEmpenho->data)) {

                        $sErroMensagem = "Data do empenho  deve ser MENOR ou IGUAL a menor data de estorno do empenho.\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }
                /**
                 * Verificar se existe OC para o empenho.
                 * caso exista, devemos verificar se a data do lancamento é menor que a data da OC
                 */
                $sSqlOrdemCompra = "select distinct m51_codordem, m51_data ";
                $sSqlOrdemCompra .= "  from matordem ";
                $sSqlOrdemCompra .= "       inner join matordemitem on m51_codordem = m52_codordem";
                $sSqlOrdemCompra .= "  where m52_numemp = {$this->numemp}";
                $sSqlOrdemCompra .= "  order by m51_data";
                $rsOrdemCompra = db_query($sSqlOrdemCompra);
                if (pg_num_rows($rsOrdemCompra) > 0) {

                    $oOrdemCompra = db_utils::fieldsMemory($rsOrdemCompra, 0);
                    if (db_strtotime($dtValidar) > db_strtotime($oOrdemCompra->m51_data)) {

                        $sOrdens  = "";
                        $sVirgula = "";
                        $aOrdens  = db_utils::getCollectionByRecord($rsOrdemCompra);
                        foreach ($aOrdens as $oOrdemCompra) {

                            $sOrdens .= "{$sVirgula}" . $oOrdemCompra->m51_codordem;
                            $sVirgula .= ", ";

                        }
                        $sErroMensagem = "Data do empenho  deve ser MENOR ou IGUAL a menor data das Ordens de Compra lançadas ";
                        $sErroMensagem .= "para o empenho.\nOrdens lançadas:\n{$sOrdens}\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }
                if (db_strtotime($dtValidar) != db_strtotime($oLancamentoEmpenho->data)) {

                    lancamentoContabil::alterarDataLancamento($iCodigoLancamento, $dtValidar);

                    /**
                     * Alteramos a data do empenho
                     */
                    $oDaoEmpEmpenho             = db_utils::getDao("empempenho");
                    $oDaoEmpEmpenho->e60_numemp = $this->numemp;
                    $oDaoEmpEmpenho->e60_emiss  = $dtValidar;
                    $oDaoEmpEmpenho->e60_vencim = $dtValidar;
                    $oDaoEmpEmpenho->alterar($this->numemp);
                    if ($oDaoEmpEmpenho->erro_status == 0) {

                        $sErroMensagem = "Erro ao alterar data do empenho!";
                        throw new Exception($sErroMensagem);
                    }
                }
                break;

            case 11:

                /**
                 * Validamos  o ano do empenho.
                 * Caso o Ano modificado seje diferente do ano do lancamento, cancelamos a operacao;
                 */
                $aDataValidar    = explode("-", $dtValidar);
                $iAnoNovaData    = $aDataValidar[0];
                $aDataLancamento = explode("-", $oDadosLancamento->data);
                $iAnoLancamento  = $aDataLancamento[0];
                if ($iAnoNovaData != $iAnoLancamento) {

                    $sErroMensagem = "Não é permitido mudar o ano do lançamento.\n";
                    $sErroMensagem .= "Operação cancelada.";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Validamos o estorno do  Empenho.
                 */
                $sWhereEmpenho       = "c53_tipo in(10)";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtValidar) < db_strtotime($oLancamentoEmpenho->data)) {

                        $sErroMensagem = "Data do estorno do empenho  deve ser MAIOR ou IGUAL a menor data de estorno do empenho.\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }


                lancamentoContabil::alterarDataLancamento($iCodigoLancamento, $dtValidar);

                /**
                 * Alteramos a data do log de anulação do empenho.
                 */
                $sWhereAnulado = "e94_valor       = {$oDadosLancamento->valor} and e94_data = '{$oDadosLancamento->data}'";
                $sWhereAnulado .= " and e94_numemp = {$this->numemp}";

                $oDaoEmpAnulado    = db_utils::getDao("empanulado");
                $sSqlDadosAnulacao = $oDaoEmpAnulado->sql_query(null, "*", null, $sWhereAnulado);
                $rsDadosAnulacao   = $oDaoEmpAnulado->sql_record($sSqlDadosAnulacao);
                $aAnulacoes        = db_utils::getCollectionByRecord($rsDadosAnulacao);

                foreach ($aAnulacoes as $oAnulacao) {

                    $oDaoEmpAnulado->e94_codanu = $oAnulacao->e94_codanu;
                    $oDaoEmpAnulado->e94_data   = $dtValidar;
                    $oDaoEmpAnulado->alterar($oAnulacao->e94_codanu);
                    if ($oDaoEmpAnulado->erro_status == 0) {

                        $sErroMensagem = "Erro ao alterar data da anulação empenho!";
                        throw new Exception($sErroMensagem);
                    }
                }

                break;
            case 20:

                /**
                 * Validamos o lancamento de Empenho.
                 */
                $sWhereEmpenho       = "c71_coddoc = 1";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 1 || count($aLancamentosEmpenho) == 0) {
                    throw new Exception("Lançamento de empenhos invalido.\n");
                }
                $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                if (db_strtotime($dtValidar) < db_strtotime($oLancamentoEmpenho->data)) {

                    throw new Exception("Data da liquidação deve ser MAIOR ou IGUAL a data de empenho.\nOperação cancelada.");
                }

                /**
                 * Validamos o lancamento de Estorno da nota.
                 */
                $sWhereLiquidacao    = "c71_coddoc = 4 and c66_codnota = {$oDadosLancamento->codigonotafiscal}";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtValidar) > db_strtotime($oLancamentoEmpenho->data)) {
                        throw new Exception("Data da liquidação deve ser MENOR ou IGUAL a data de estorno da nota.\nOperação cancelada.");
                    }
                }

                /**
                 * Consultamos o codigo da ordem de pagamento da nota
                 */
                $oNota = db_utils::fieldsMemory($this->getNotas($this->numemp,
                  "e69_codnota = $oDadosLancamento->codigonotafiscal"),
                  0);
                if ($oNota->e50_codord != "") {

                    /**
                     * Validamos o lancamento de pagamento da nota.
                     */
                    $sWhereLiquidacao    = "c71_coddoc  in(5,35,37) and c80_codord = {$oNota->e50_codord}";
                    $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                    if (count($aLancamentosEmpenho) > 0) {

                        $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                        if (db_strtotime($dtValidar) > db_strtotime($oLancamentoEmpenho->data)) {
                            throw new Exception("Data da liquidação deve ser MENOR ou IGUAL a data de pagamento da nota.\nOperação cancelada.");
                        }
                    }
                }

                /**
                 * Verificamos se a op da nota está configurada para cheque, ou arquivo
                 */
                $sSqlMov = "select e97_codforma,";
                $sSqlMov .= "       e96_descr,    ";
                $sSqlMov .= "       e90_codgera, ";
                $sSqlMov .= "       e81_codmov, ";
                $sSqlMov .= "       e91_cheque, ";
                $sSqlMov .= "       e86_data, ";
                $sSqlMov .= "       e91_ativo ";
                $sSqlMov .= "  from empord";
                $sSqlMov .= "       inner join empagemov      on e82_codmov   = e81_codmov";
                $sSqlMov .= "       inner join empageconf     on e86_codmov   = e81_codmov";
                $sSqlMov .= "       left join empagemovforma on e81_codmov   = e97_codmov";
                $sSqlMov .= "       left join empageforma    on e97_codforma = e96_codigo";
                $sSqlMov .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
                $sSqlMov .= "       left  join empageconfgera on e81_codmov   = e90_codmov";
                $sSqlMov .= " where e82_codord = {$oNota->e50_codord}";
                $sSqlMov .= "   and e81_cancelado is null";
                $rsMov = db_query($sSqlMov);
                if ($rsMov && pg_num_rows($rsMov) > 0) {

                    $aMovimentos = db_utils::getCollectionByRecord($rsMov);
                    foreach ($aMovimentos as $oMovimento) {

                        if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque != "") {

                            /**
                             * ordem de pagamento possui cheque emitido. devemos cancelar
                             */
                            if (db_strtotime($dtValidar) > db_strtotime($oMovimento->e86_data)) {

                                $sMsgErro = "O Lançamento '{$iCodigoLancamento}', possui a ordem de pagamento '{$oNota->e50_codord}' ";
                                $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' com cheque  número ";
                                $sMsgErro .= "'{$oMovimento->e91_cheque}' Emitido, ";
                                $sMsgErro .= "no dia " . db_formatar($oMovimento->e86_data, "d") . ".";
                                $sMsgErro .= "\n Antes de alterar esse lançamento, cancele o cheque.";
                                throw new Exception($sMsgErro);
                            }

                        } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera != "") {

                            /**
                             * ordem de pagamento está em transmissão para pagamento eletronico. devemos cancelar
                             */
                            if (db_strtotime($dtValidar) > db_strtotime($oMovimento->e86_data)) {

                                $sMsgErro = "O Lançamento '{$iCodigoLancamento}', possui a ordem de pagamento '{$oNota->e50_codord}' ";
                                $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' no arquivo '{$oMovimento->e90_codgera}', ";
                                $sMsgErro .= "no dia " . db_formatar($oMovimento->e86_data, "d") . ".";
                                $sMsgErro .= "\n Antes de alterar esse lançamento, retire o movimento informado do arquivo.";
                                throw new Exception($sMsgErro);
                            }
                        }
                    }
                }
                lancamentoContabil::alterarDataLancamento($iCodigoLancamento, $dtValidar);
                /**
                 * Alteramos a data do OP;
                 */
                if ($oNota->e50_codord != "") {

                    $oDaoPagOrdem             = db_utils::getDao("pagordem");
                    $oDaoPagOrdem->e50_codord = $oNota->e50_codord;
                    $oDaoPagOrdem->e50_data   = $dtValidar;
                    $oDaoPagOrdem->alterar($oNota->e50_codord);
                    if ($oDaoPagOrdem->erro_status == 0) {
                        throw new Exception("Erro ao alterar data da ordem de pagamento ($oNota->e50_codord).");
                    }
                }
                break;

            case 21:

                /**
                 * Validamos a liquidacao.
                 */
                $sWhereEmpenho = "c71_coddoc in(3, 23, 33) and c66_codnota = {$oDadosLancamento->codigonotafiscal}";
                $sWhereEmpenho .= " and c70_data <= '{$oDadosLancamento->data}'";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 1 || count($aLancamentosEmpenho) == 0) {
                    throw new Exception("Lançamento de empenhos invalido.\n");
                }
                $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                if (db_strtotime($dtValidar) < db_strtotime($oLancamentoEmpenho->data)) {
                    throw new Exception("Data do Estorno da liquidação deve ser MAIOR ou IGUAL a data de liquidação.\nOperação cancelada.");
                }

                /**
                 * Validamos o lancamento de Estorno da nota.
                 */
                $sWhereLiquidacao    = "c53_tipo = 20 and c66_codnota = {$oDadosLancamento->codigonotafiscal}";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtValidar) < db_strtotime($oLancamentoEmpenho->data)) {
                        throw new Exception("Data do estorno da liquidação deve ser MENOR ou IGUAL a data de liquidacao da nota.\nOperação cancelada.");
                    }
                }

                /**
                 * Consultamos o codigo da ordem de pagamento da nota
                 */
                $oNota = db_utils::fieldsMemory($this->getNotas($this->numemp,
                  "e69_codnota = $oDadosLancamento->codigonotafiscal"),
                  0);
                if ($oNota->e50_codord != "") {

                    /**
                     * Validamos o lancamento de pagamento da nota.
                     */
                    $sWhereLiquidacao    = "c71_coddoc  in(5,35,37) and c80_codord = {$oNota->e50_codord}";
                    $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                    if (count($aLancamentosEmpenho) > 0) {

                        $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                        if (db_strtotime($dtValidar) > db_strtotime($oLancamentoEmpenho->data)) {
                            throw new Exception("Data da liquidação deve ser MENOR ou IGUAL a data de pagamento da nota.\nOperação cancelada.");
                        }
                    }
                }

                lancamentoContabil::alterarDataLancamento($iCodigoLancamento, $dtValidar);
                break;

            case 30:

                $this->manutencaoLancamentosPagamento($oDadosLancamento, $dtValidar, 1);
                break;

            case 31:

                $this->manutencaoLancamentosPagamento($oDadosLancamento, $dtValidar, 2);
                break;

            case 200:
            case 90:
                lancamentoContabil::alterarDataLancamento($iCodigoLancamento, $dtValidar);
                break;

            default :
                throw new Exception('Tipo de evento não tratado: ' . $oDadosLancamento->tipoevento);
                break;
        }
    }

    public function alterarDataDesconto($iCodigoDesconto, $dtDataDesconto) {

        if (!db_utils::inTransaction()) {
            throw new Exception("Não há transação aberta.\nProcedimento Cancelado");
        }

        /**
         * buscamos todos os lancamentos contábeis do desconto
         */
        $sWhere               = " e33_pagordemdesconto = {$iCodigoDesconto}";
        $aLancamentosDesconto = $this->getLancamentosContabeis($sWhere);
        $dtValidar            = implode("-", array_reverse(explode("/", $dtDataDesconto)));
        /**
         * Verificamos se o lancamento informado é realmente do empenho
         */
        $dtLancamento = '';
        require_once(modification("classes/lancamentoContabil.model.php"));
        if (count($aLancamentosDesconto) != 2) {

            $sMessageDesconto = "Não existem lançamentos de desconto para esse desconto.\nVerifique o lançamento, ";
            $sMessageDesconto .= "ou contate  suporte.";
            throw new Exception($sMessageDesconto);
        }

        $oOrdemAlterar           = new stdClass();
        $oOrdemAlterar->primeiro = null;
        $oOrdemAlterar->segundo  = null;
        foreach ($aLancamentosDesconto as $oLancamentoDesconto) {

            $oDadosLancamento = lancamentoContabil::getInfoLancamento($oLancamentoDesconto->codigo, false);
            if ($oDadosLancamento->empenho != $this->numemp) {
                throw new Exception("o Lançamento ({$oLancamentoDesconto->codigo}) informado não pertence ao empenho ({$this->numemp})");
            }
            $dtLancamento = $oLancamentoDesconto->data;
            if (db_strtotime($dtValidar) <= db_strtotime($dtLancamento)) {

                if ($oLancamentoDesconto->tipodocumento == 21) {
                    $oOrdemAlterar->primeiro = $oLancamentoDesconto;
                } else {
                    $oOrdemAlterar->segundo = $oLancamentoDesconto;
                }
            } else {

                if ($oLancamentoDesconto->tipodocumento == 11) {
                    $oOrdemAlterar->primeiro = $oLancamentoDesconto;
                } else {
                    $oOrdemAlterar->segundo = $oLancamentoDesconto;
                }
            }
        }
        if ($dtLancamento == "") {

            $sMessageDesconto = "Data dos lancamentos contabeis de desconto estão inválidas,\nVerifique o Lançamento, ";
            $sMessageDesconto .= "ou contate  suporte.";
            throw new Exception($sMessageDesconto);
        }

        //print_r($oOrdemAlterar);
        $this->alterarDataLancamento($oOrdemAlterar->primeiro->codigo, $dtDataDesconto);
        $this->alterarDataLancamento($oOrdemAlterar->segundo->codigo, $dtDataDesconto);
        /**
         * Alteramos a data do desconto na tabela pagordemdesconto
         */
        $oDaoPagordemDesconto                 = db_utils::getDao("pagordemdesconto");
        $oDaoPagordemDesconto->e34_sequencial = $iCodigoDesconto;
        $oDaoPagordemDesconto->e34_data       = $dtValidar;
        $oDaoPagordemDesconto->alterar($iCodigoDesconto);
        if ($oDaoPagordemDesconto->erro_status == 0) {
            throw new Exception("Houve um erro ao alterar a data do desconto.\n[ET]{$oDaoPagordemDesconto->erro_msg}");
        }
    }

    function excluirLancamento($iCodigoLancamento) {

        if (!db_utils::inTransaction()) {
            throw new Exception("Não há transação aberta.\nProcedimento Cancelado");
        }

        /**
         * Verificamos se o lancamento informado é realmente do empenho
         */
        require_once(modification("classes/lancamentoContabil.model.php"));
        $oDadosLancamento = lancamentoContabil::getInfoLancamento($iCodigoLancamento, false);
        if ($oDadosLancamento->empenho != $this->numemp) {
            throw new Exception("o Lançamento ({$iCodigoLancamento}) informado não pertence ao empenho ({$this->numemp})");
        }

        /**
         * Verificamos se a data é menor que a data de encerramento da contabilidade
         */
        $sSqlDataEncerramento = "select c99_data ";
        $sSqlDataEncerramento .= "  from condataconf";
        $sSqlDataEncerramento .= " where c99_anousu = " . db_getsession("DB_anousu");
        $sSqlDataEncerramento .= "   and c99_instit = " . db_getsession("DB_instit");
        $rsDataEncerramento = db_query($sSqlDataEncerramento);
        if (pg_num_rows($rsDataEncerramento) > 0) {

            $dtDataEncerramento = db_utils::fieldsMemory($rsDataEncerramento, 0)->c99_data;
            if (db_strtotime($oDadosLancamento->data) <= db_strtotime($dtDataEncerramento)) {

                $sMessage = "Data do lançamento não pode ser menor que a data do encerramento contábil ";
                $sMessage .= "(" . db_formatar($dtDataEncerramento, "d") . ")!\nOperação cancelada.";
                throw new Exception($sMessage);
            }
        }

        switch ($oDadosLancamento->tipoevento) {


            case 10:

                /**
                 * Verificamos se o epenho é o ultimo cadastrado
                 */
                $sWhere         = " e60_numemp > $this->numemp and e60_anousu = " . db_getsession("DB_anousu");
                $oDaoEmpempenho = new cl_empempenho;
                $sSqlEmpenho    = $oDaoEmpempenho->sql_query_file(null, "e60_numemp", "e60_numemp limit 1", $sWhere);
                $rsEmpenho      = $oDaoEmpempenho->sql_record($sSqlEmpenho);
                if ($oDaoEmpempenho->numrows > 0) {

                    $sErroMensagem = "Você não pode excluir este empenho porque já existem outros emitidos posteriormente. ";
                    $sErroMensagem .= "Caso este empenho esteja incorreto ou você deseja descartá-lo por qualquer outro motivo, ";
                    $sErroMensagem .= "utilize a rotina de anulação de empenhos.";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Validamos a liquidacao de Empenho.
                 */
                $sWhereEmpenho       = "c71_coddoc in(3, 23, 33)";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $sErroMensagem = "Exclusão do lançamento do empenho nao poderá ser executada.\n";
                    $sErroMensagem .= "existem liquidações realizadas para esse empenho.\nOperação Cancelada";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Validamos o estorno do Empenho.
                 */
                $sWhereEmpenho       = "c71_coddoc in(2, 31, 32)";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $sErroMensagem = "Exclusão do lançamento do empenho nao poderá ser executada.\n";
                    $sErroMensagem .= "existem anulações de empenho realizadas para esse empenho.\nOperação Cancelada";
                    throw new Exception($sErroMensagem);
                }

                lancamentoContabil::excluirLancamento($iCodigoLancamento);

                /**
                 * excluimos os vinculos do empenho, e reservamos o saldo da autorizacao novamente
                 */


                /**
                 * exclui empelemento
                 */
                $oDaoEmpElemento             = db_utils::getDao("empelemento");
                $oDaoEmpElemento->e64_numemp = $this->numemp;
                $oDaoEmpElemento->excluir($this->numemp);
                if ($oDaoEmpElemento->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir elemento do empenho\n[ET] - {$oDaoEmpElemento->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * excluimos todos os itens do empenho e seus vinculos com o pacto
                 */
                $oDaoEmpEmpitem = new cl_empempitem;//db_utils::getDao("empempitem");
                $sSqlItens      = $oDaoEmpEmpitem->sql_query_left_item_pacto($this->numemp,
                  null,
                  "e62_sequencial,
                                                                     o105_sequencial,
                                                                     o88_sequencial");
                $rsItens        = $oDaoEmpEmpitem->sql_record($sSqlItens);
                $aItens         = db_utils::getCollectionByRecord($rsItens);
                foreach ($aItens as $oItem) {

                    if ($oItem->o105_sequencial != "") {

                        $oDaoPactoValorMovEmpenho = db_utils::getDao("pactovalormovempempitem");
                        $oDaoPactoValorMovEmpenho->excluir($oItem->o15_sequencial);
                        if ($oDaoPactoValorMovEmpenho->erro_status == 0) {

                            $sErroMensagem = "Erro ao excluir vinculacao com pacto\n[ET] - {$oDaoPactoValorMovEmpenho->erro_msg}";
                            throw new Exception($sErroMensagem);
                        }
                    }
                    if ($oItem->o88_sequencial != "") {

                        $oDaoPactoValorMov = db_utils::getDao("pactovalormov");
                        $oDaoPactoValorMov->excluir($oItem->o88_sequencial);
                        if ($oDaoPactoValorMov->erro_status == 0) {

                            $sErroMensagem = "Erro ao excluir vinculacao com pacto\n[ET] - {$oDaoPactoValorMov->erro_msg}";
                            throw new Exception($sErroMensagem);
                        }
                    }

                    /**
                     * excluimos o item
                     */
                    //$oDaoEmpEmpitem->excluir();
                    $oDaoEmpEmpitem->e62_sequencial = $oItem->e62_sequencial;
                    $oDaoEmpEmpitem->excluir(null, null, "e62_sequencial = {$oItem->e62_sequencial}");
                    if ($oDaoEmpEmpitem->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir item do empenho\n[ET] - {$oDaoEmpEmpitem->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                }

                /**
                 * Excluimos a vinculacao com a prestação de contas do empenho
                 */
                $oDaoEmpPresta = db_utils::getDao("emppresta");
                $oDaoEmpPresta->excluir(null, "e45_numemp = {$this->numemp}");
                if ($oDaoEmpPresta->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir prestações de contas do empenho\n[ET] - {$oDaoEmpPresta->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Excluimos o vínculo com a conta corrente
                 */
                $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
                $oDaoContaCorrenteDetalhe->excluir(null, "c19_numemp = {$this->numemp}");
                if ($oDaoContaCorrenteDetalhe->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir detalhes de conta corrente do empenho\n[ET] - {$oDaoContaCorrenteDetalhe->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * excluimos o o historico no empenho(emphist);
                 */
                $oDaoEmpHist = db_utils::getDao("empemphist");
                $oDaoEmpHist->excluir($this->numemp);
                if ($oDaoEmpHist->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir prestações de contas do empenho\n[ET] - {$oDaoEmpHist->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Devolvemos o saldo para a autorizacao
                 */
                $this->getDados($this->numemp);
                $clempautitem    = $this->usarDao("empempaut", true);
                $rsItemSolic     = $clempautitem->sql_record($clempautitem->sql_query_file($this->numemp,
                  "distinct e61_autori as autori"));
                $iNumRowsItemSol = $clempautitem->numrows;
                if ($iNumRowsItemSol > 0) {

                    $oItensSolic = db_utils::fieldsMemory($rsItemSolic, 0);
                    $iAutori     = $oItensSolic->autori;
                }
                $rsDotacao     = db_dotacaosaldo(8,
                  2,
                  2,
                  "true",
                  "o58_coddot={$this->dadosEmpenho->e60_coddot}",
                  db_getsession("DB_anousu"));
                $oDotacaoSaldo = db_utils::fieldsMemory($rsDotacao, 0);

                $saldo                                     = (0 + $oDotacaoSaldo->atual_menos_reservado);
                $saldo                                     = trim(str_replace(".", "", db_formatar($saldo, "f")));
                $aDotacao[$this->dadosEmpenho->e60_coddot] = str_replace(",", ".", $saldo);
                $oDaoEmpAutoriza                           = db_utils::getDao("empautoriza");
                $oDaoOrcreservaaut                         = db_utils::getDao("orcreservaaut");
                $oDaoOrcReserva                            = db_utils::getDao("orcreserva");
                /**
                 * Verificamos o valor total da autorizacao, e incluimos a reserva novamente;
                 */
                $sSqlValorAutorizacao = $oDaoEmpAutoriza->sql_query_depto($iAutori, "e56_coddot, e54_valor");
                $rsValorAutorizacao   = $oDaoEmpAutoriza->sql_record($sSqlValorAutorizacao);
                if ($oDaoEmpAutoriza->numrows == 0) {
                    throw new Exception("Empenho {$this->numemp} sem autorizacao!");
                }
                $oAutorizacao               = db_utils::fieldsMemory($rsValorAutorizacao, 0);
                $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
                $oDaoOrcReserva->o80_coddot = $oAutorizacao->e56_coddot;
                $oDaoOrcReserva->o80_dtfim  = db_getsession("DB_anousu") . "-12-31";;
                $oDaoOrcReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));;
                $oDaoOrcReserva->o80_valor = "{$oAutorizacao->e54_valor}";
                $oDaoOrcReserva->o80_descr = "Reserva autorização";
                $oDaoOrcReserva->incluir(null);
                if ($oDaoOrcReserva->erro_status == 0) {

                    $sErroMensagem = "Erro ao recriar reserva de saldo\n{$oDaoOrcReserva->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                $oDaoOrcreservaaut->o83_codres = $oDaoOrcReserva->o80_codres;
                $oDaoOrcreservaaut->o83_autori = $iAutori;
                $oDaoOrcreservaaut->incluir($oDaoOrcReserva->o80_codres);
                if ($oDaoOrcreservaaut->erro_status == 0) {

                    $sErroMensagem = "Erro ao recriar reserva de saldo\n{$oDaoOrcreservaaut->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * excluimos a vinculacao do empenho com a autorizacao
                 */
                $oDaoEmpempAut = new cl_empempaut();
                $oDaoEmpempAut->excluir($this->numemp);
                if ($oDaoEmpempAut->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir vinculação com a autorizacao do empenho\n[ET] - {$oDaoEmpempAut->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                /**
                 * Excluimos a vinculação com a nota de liquidacao
                 */
                $oDaoEmpempenhoNL = db_utils::getDao("empempenhonl");
                $oDaoEmpempenhoNL->excluir(null, "e68_numemp = {$this->numemp}");
                if ($oDaoEmpempenhoNL->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir empenho\n[ET] - {$oDaoEmpempenhoNL->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                /*
         * excluimos o empenho
         */
                $oDaoEmpempenho->excluir($this->numemp);
                if ($oDaoEmpempenho->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir empenho\n[ET] - {$oDaoEmpempenho->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Alteramos o codemp da empparametro para o ultimo codigo do empenho
                 *
                 */
                $oDaoEmpParametro             = db_utils::getDao("empparametro");
                $oDaoEmpParametro->e30_codemp = $this->dadosEmpenho->e60_codemp - 1;
                $oDaoEmpParametro->e39_anousu = db_getsession("DB_anousu");
                $oDaoEmpParametro->alterar(db_getsession("DB_anousu"));
                if ($oDaoEmpempenho->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar numeração do  empenho\n[ET] - {$oDaoEmpParametro->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                break;

            case 11: //Estorno

                $sErroMensagem = "";
                lancamentoContabil::excluirLancamento($iCodigoLancamento);
                /**
                 * Devemos exclui os itens dessa anulacao e a anulacao
                 */
                $oDaoEmpanulado     = db_utils::getDao("empanulado");
                $oDaoEmpanuladoItem = db_utils::getDao("empanuladoitem");
                $sSqlCodigoAnulacao = $oDaoEmpanulado->sql_query_file(null, "e94_codanu", null, "e94_numemp={$this->numemp}");
                $rsCodigoAnulacao   = $oDaoEmpanulado->sql_record($sSqlCodigoAnulacao);
                if ($oDaoEmpanulado->numrows == 0) {

                    $sErroMensagem = "Lançamento contábil de anulação de empenho sem informação da anulacao!";
                    $sErroMensagem .= "\nOperação cancelada.";
                    throw new Exception($sErroMensagem);
                }

                $iCodigoAnulacao = db_utils::fieldsMemory($rsCodigoAnulacao, 0)->e94_codanu;
                /**
                 * excluimos todos os itens anulados
                 */
                $oDaoEmpanuladoItem->excluir(null, "e37_empanulado = {$iCodigoAnulacao}");
                if ($oDaoEmpanuladoItem->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir informações dos itens anulados.\n";
                    $sErroMensagem .= "Operação cancelada.\n[ET]\n - {$oDaoEmpanuladoItem->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Excluimos da tabela empanuladoele
                 */
                $oDaoEmpanuladoEle = db_utils::getDao("empanuladoele");
                @$oDaoEmpanuladoEle->excluir($iCodigoAnulacao, null);
                if ($oDaoEmpanuladoEle->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir informações da anulação do empenho.\n";
                    $sErroMensagem .= "Operação cancelada.\n[ET]\n - {$oDaoEmpanuladoEle->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Excluimos da tabela empanulado
                 */

                $oDaoEmpanulado->excluir($iCodigoAnulacao);
                if ($oDaoEmpanulado->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir informações da anulação do empenho.\n";
                    $sErroMensagem .= "Operação cancelada.\n[ET]\n - {$oDaoEmpanulado->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * Modificamos o valor estornado nas tabelas empenho, e empelemento
                 */
                $this->getDados($this->numemp);
                $oDaoEmpempenho             = db_utils::getDao("empempenho");
                $oDaoEmpempenho->e60_numemp = $this->numemp;
                //$sErroMensagem .=  "Empenho:{$this->dadosEmpenho->e60_vlranu} - {$oDadosLancamento->valor}\n";
                $oDaoEmpempenho->e60_vlranu = "" . ($this->dadosEmpenho->e60_vlranu - $oDadosLancamento->valor) . "";
                $oDaoEmpempenho->alterar($this->numemp);
                if ($oDaoEmpempenho->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar valores do empenho.\n";
                    $sErroMensagem .= "Operação cancelada.\n[ET]\n - {$oDaoEmpempenho->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                $oDaoEmpElemento             = db_utils::getDao("empelemento");
                $oDaoEmpElemento->e64_numemp = $this->numemp;
                $oDaoEmpElemento->e64_vlranu = "" . ($this->dadosEmpenho->e60_vlranu - $oDadosLancamento->valor) . "";
                //$sErroMensagem .=  "Elemento:{$this->dadosEmpenho->e60_vlranu} - {$oDadosLancamento->valor}\n";
                $oDaoEmpElemento->alterar($this->numemp, null);
                if ($oDaoEmpElemento->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar valores do empenho.\n";
                    $sErroMensagem .= "Operação cancelada.\n[ET]\n - {$oDaoEmpElemento->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                //throw new Exception($sErroMensagem);
                break;

            case 20: //Liquidacoes

                /**
                 * Não pode haver pagamentos, nem liquidações na nota com data maior ou igual a data do lancamento contabil
                 */
                $sWhereLiquidacao = "c53_tipo = 21 and c66_codnota = {$oDadosLancamento->codigonotafiscal}";
                $sWhereLiquidacao .= "and c70_data  >= '{$oDadosLancamento->data}'";
                if ($oDadosLancamento->ordempagamento != "") {
                    $sWhereLiquidacao .= "and c80_codord  = '{$oDadosLancamento->ordempagamento}'";
                }
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                if (count($aLancamentosEmpenho) > 0) {
                    throw new Exception("Existem estornos de liquidação realizados para a nota.\nOperação cancelada.");
                }

                /**
                 * Consultamos o codigo da ordem de pagamento da nota
                 */
                $oNota = db_utils::fieldsMemory($this->getNotas($this->numemp,
                  "e69_codnota = $oDadosLancamento->codigonotafiscal",
                  false),
                  0);
                if ($oNota->e50_codord != "") {

                    /**
                     * Validamos o lancamento de pagamento da nota.
                     */
                    $sWhereLiquidacao    = "c71_coddoc  in(5,35,37) and c80_codord = {$oNota->e50_codord}";
                    $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereLiquidacao);
                    if (count($aLancamentosEmpenho) > 0) {
                        throw new Exception("Existem pagamentos Realizados para a nota.\nOperação cancelada.");
                    }
                }
                /**
                 * Verificamos se a op da nota está configurada para cheque, ou arquivo
                 */
                $sSqlMov = "select e97_codforma,";
                $sSqlMov .= "       e96_descr,    ";
                $sSqlMov .= "       e90_codgera, ";
                $sSqlMov .= "       e81_codmov, ";
                $sSqlMov .= "       e91_cheque, ";
                $sSqlMov .= "       e91_ativo ";
                $sSqlMov .= "  from empord";
                $sSqlMov .= "       inner join empagemov      on e82_codmov   = e81_codmov";
                $sSqlMov .= "       left join empagemovforma on e81_codmov   = e97_codmov";
                $sSqlMov .= "       left join empageforma    on e97_codforma = e96_codigo";
                $sSqlMov .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
                $sSqlMov .= "       left  join empageconfgera on e81_codmov   = e90_codmov";
                $sSqlMov .= " where e82_codord = {$oNota->e50_codord}";
                $sSqlMov .= "   and e81_cancelado is null";
                $rsMov = db_query($sSqlMov);
                if ($rsMov && pg_num_rows($rsMov) > 0) {

                    $aMovimentos = db_utils::getCollectionByRecord($rsMov);
                    foreach ($aMovimentos as $oMovimento) {

                        if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque != "") {

                            /**
                             * ordem de pagamento possui cheque emitido. devemos cancelar
                             */
                            $sMsgErro = "O Lançamento '{$iCodigoLancamento}', possui a ordem de pagamento '{$oNota->e50_codord}' ";
                            $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' com cheque  número ";
                            $sMsgErro .= "'{$oMovimento->e91_cheque}' Emitido.\n Antes de excluir esse lançamento, cancele o cheque.";
                            throw new Exception($sMsgErro);

                        } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera != "") {

                            /**
                             * ordem de pagamento está em transmissão para pagamento eletronico. devemos cancelar
                             */
                            $sMsgErro = "O Lançamento '{$iCodigoLancamento}', possui a ordem de pagamento '{$oNota->e50_codord}' ";
                            $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' no arquivo '{$oMovimento->e90_codgera}'.";
                            $sMsgErro .= "\n Antes de excluir esse lançamento, retire o movimento informado do arquivo.";
                            throw new Exception($sMsgErro);
                        }
                    }
                }

                /**
                 * Verificamos se a nota de liquidação foi liquidada sem ordem de compra.
                 * caso existe uma liquidacao sem Ordem de compra, devemos verificar se o usuário nao deu entrada no estoque
                 * dessa nota.
                 */

                //verificamos o tipo da ordem , se for virtual devemos anular a ordem de compra e seus itens.
                $sSQLOrdem = "select m51_tipo,";
                $sSQLOrdem .= "       m73_codmatestoqueitem,";
                $sSQLOrdem .= "       m52_codlanc,";
                $sSQLOrdem .= "       m72_codordem,";
                $sSQLOrdem .= "       m52_valor,";
                $sSQLOrdem .= "       m52_quant";
                $sSQLOrdem .= "  from matordem ";
                $sSQLOrdem .= "        inner join empnotaord   on m72_codordem    = m51_codordem";
                $sSQLOrdem .= "        inner join matordemitem on m51_codordem    = m52_codordem ";
                $sSQLOrdem .= "        left join matestoqueitemoc on m52_codlanc = m73_codmatordemitem";
                $sSQLOrdem .= " where m72_codnota = {$oDadosLancamento->codigonotafiscal}";
                $sSQLOrdem .= "   and m51_tipo = 2";
                $rOrdem        = db_query($sSQLOrdem);
                $aCodigosOrdem = array();
                if (pg_num_rows($rOrdem) > 0) {

                    if (!class_exists("cl_matordemanu")) {
                        require_once modification("classes/db_matordemanu_classe.php");
                    }
                    if (!class_exists("cl_matordemitemanu")) {
                        require_once modification("classes/db_matordemitemanu_classe.php");
                    }
                    if (!class_exists("cl_matordemanul")) {
                        require_once modification("classes/db_matordemanul_classe.php");
                    }
                    $clmatordemanu     = new cl_matordemanu();
                    $clmatordemanul    = new cl_matordemanul();
                    $clmatordemitemanu = new cl_matordemitemanu();
                    /*
             vamos verificar se essa nota possui algum item em estoque.
             se possui, nao podemos deixar extornar a liquidacao
           */
                    for ($j = 0; $j < pg_num_rows($rOrdem); $j++) {

                        $oMatordemItem = db_utils::fieldsMemory($rOrdem, $j);
                        if (!in_array($oMatordemItem->m72_codordem, $aCodigosOrdem)) {
                            $aCodigosOrdem[] = $oMatordemItem->m72_codordem;
                        }
                        if ($oMatordemItem->m73_codmatestoqueitem != null) {

                            $sMsgErro = "O Lançamento '{$iCodigoLancamento}', possui a ";
                            $sMsgErro .= " nota ({$oNota->e69_numero}), que possui Itens que foram movimentados no estoque.";
                            $sMsgErro .= "\nLançamento não poderá ser excluido.";
                            throw  new Exception($sMsgErro);

                        }
                    }
                }
                /**
                 * excluimos os lancamentos contábeis
                 */
                lancamentoContabil::excluirLancamento($iCodigoLancamento);

                /**
                 * excluimos a ordem de compra
                 */
                $oDaoEmpnotaOrd   = db_utils::getDao("empnotaord");
                $oDaoMatordemItem = db_utils::getDao("matordemitem");
                $oDaoMatordem     = db_utils::getDao("matordem");
                foreach ($aCodigosOrdem as $iCodigoOrdem) {

                    $oDaoEmpnotaOrd->excluir($oNota->e69_codnota, $iCodigoOrdem);
                    if ($oDaoEmpnotaOrd->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir ordem de compra {$iCodigoOrdem}\n[ET] - {$oDaoEmpnotaOrd->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoMatordemItem->excluir(null, "m52_codordem = {$iCodigoOrdem}");
                    if ($oDaoMatordemItem->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir ordem de compra {$iCodigoOrdem}\n[ET] - {$oDaoMatordemItem->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoMatordem->excluir($iCodigoOrdem);
                    if ($oDaoMatordem->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir ordem de compra {$iCodigoOrdem}\n[ET] - {$oDaoMatordem->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                }

                /**
                 * excluimos os movimentos de agenda
                 */
                $oDaoRetencaoEmpagemov    = db_utils::getDao("retencaoempagemov");
                $oDaoEmpageNotasOrdem     = db_utils::getDao("empagenotasordem");
                $oDaoEmpageMovForma       = db_utils::getDao("empagemovforma");
                $oDaoEmpageConf           = db_utils::getDao("empageconf");
                $oDaoEmpageConfCanc       = db_utils::getDao("empageconfcanc");
                $oDaoEmpOrd               = db_utils::getDao("empord");
                $oDaoEmpageConfGera       = db_utils::getDao("empageconfgera");
                $oDaoEmpageConfche        = db_utils::getDao("empageconfche");
                $oDaoEmpageConfcheCanc    = db_utils::getDao("empageconfchecanc");
                $oDaoEmpagemov            = db_utils::getDao("empagemov");
                $oDaoEmpagePag            = db_utils::getDao("empagepag");
                $oDaoEmpagemovConta       = db_utils::getDao("empagemovconta");
                $oDaoEmpageConCarPeculiar = db_utils::getDao("empageconcarpeculiar");
                foreach ($aMovimentos as $oMovimento) {


                    $oDaoRetencaoEmpagemov->excluir(null, "e27_empagemov = {$oMovimento->e81_codmov}");
                    if ($oDaoRetencaoEmpagemov->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir retenções vinculadas ao lançamento.\n[ET]-{$oDaoRetencaoEmpagemov->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    /**
                     * Excluimos o vinculo com a ordem de pagamento auxilar
                     */
                    $oDaoEmpageNotasOrdem->excluir(null, "e43_empagemov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageNotasOrdem->erro_status == 0) {

                        $sErroMensagem = "[1] Erro ao ordem de pagamento auxiliar.\n[ET]-{$oDaoEmpageNotasOrdem->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    /**
                     * Excluimos o vinculo com a caracteristica peculiar
                     */
                    $oDaoEmpageConCarPeculiar->excluir(null, "e79_empagemov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConCarPeculiar->erro_status == "0") {

                        $sErroMensagem = "[2] Erro ao excluir vínculo entre a movimentação da agenda com a característica peculiar\n";
                        $sErroMensagem .= "[ET] - {$oDaoEmpageConCarPeculiar->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpageMovForma->excluir(null, "e97_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageMovForma->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir forma de pagamento vinculado ao lançamento.\n[ET]-{$oDaoEmpageMovForma->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpageConf->excluir(null, "e86_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConf->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir movimento vinculado ao lançamento.\n[ET]-{$oDaoEmpageConf->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpageConfCanc->excluir(null, "e88_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConfCanc->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir movimento vinculado ao lançamento.\n[ET]-{$oDaoEmpageConfCanc->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpageConfche->excluir(null, "e91_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConfche->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir cheque vinculado ao lançamento.\n[ET]-{$oDaoEmpageConfche->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpageConfGera->excluir(null, null, "e90_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConfGera->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir movimento vinculado ao lançamento.\n[ET]-{$oDaoEmpageConfGera->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpOrd->excluir(null, null, "e82_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpOrd->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir movimento vinculado ao lançamento.\n[ET]-{$oDaoEmpOrd->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                    $oDaoEmpageConfcheCanc->excluir(null, "e93_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpageConfcheCanc->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir cheque vinculado ao lançamento.\n[ET]-{$oDaoEmpageConfcheCanc->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoEmpagemovConta->excluir(null, "e98_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpagemovConta->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir conta vinculado ao lançamento.\n[ET]-{$oDaoEmpagemovconta->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                    $oDaoEmpagePag->excluir(null, null, "e85_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpagePag->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir forma de pagamento vinculado ao lançamento.\n[ET]-{$oDaoEmpagePag->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                    $oDaoEmpagemov->excluir(null, "e81_codmov = {$oMovimento->e81_codmov}");
                    if ($oDaoEmpagemov->erro_status == 0) {

                        $sErroMensagem = "Erro ao excluir forma de pagamento vinculado ao lançamento.\n[ET]-{$oDaoEmpagemov->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                }

                /**
                 * Excluimos as retencoes
                 */
                $oDaoRetencaoReceitas = db_utils::getDao("retencaoreceitas");
                $oDaoRetencaoPagOrdem = db_utils::getDao("retencaopagordem");
                $sSqlRetencaoOrdem    = $oDaoRetencaoReceitas->sql_query(null,
                  "e23_sequencial,
                                                                    e23_retencaopagordem",
                  null,
                  "e20_pagordem = {$oNota->e50_codord}");
                $rsRetencoes          = $oDaoRetencaoReceitas->sql_record($sSqlRetencaoOrdem);
                $aRetencoes           = db_utils::getCollectionByRecord($rsRetencoes);
                foreach ($aRetencoes as $oRetencao) {

                    $oDaoRetencaoReceitas->excluir($oRetencao->e23_sequencial);
                    if ($oDaoRetencaoReceitas->erro_status == 0) {

                        $sErroMensagem = "[9] Erro ao excluir retenções vinculadas ao lançamento.\n[ET]-{$oDaoRetencaoReceitas->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }

                    $oDaoRetencaoPagOrdem->excluir($oRetencao->e23_retencaopagordem);
                    if ($oDaoRetencaoPagOrdem->erro_status == 0) {

                        $sErroMensagem = "[10] Erro ao excluir retenções vinculadas ao lançamento.\n[ET]-{$oDaoRetencaoPagOrdem->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                }
                /**
                 * excluimos a ordem de pagamento
                 */
                $oDaoPagOrdemEle   = db_utils::getDao("pagordemele");
                $oDaoPagOrdem      = db_utils::getDao("pagordem");
                $oDaoPagOrdemNota  = db_utils::getDao("pagordemnota");
                $oDaoPagOrdemconta = db_utils::getDao("pagordemconta");

                $oDaoPagOrdemEle->excluir($oNota->e50_codord);
                if ($oDaoPagOrdemEle->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir Elementos da OP vinculadas ao lançamento.\n[ET]-{$oDaoPagOrdemEle->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                $oDaoPagOrdemconta->excluir($oNota->e50_codord);
                if ($oDaoPagOrdemconta->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir OP vinculada ao lançamento.\n[ET]-{$oDaoPagOrdemconta->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                $oDaoPagOrdemNota->excluir($oNota->e50_codord);
                if ($oDaoPagOrdemNota->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir OP vinculada ao lançamento.\n[ET]-{$oDaoPagOrdemNota->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                $oDaoPagOrdem->excluir($oNota->e50_codord);
                if ($oDaoPagOrdem->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir OP vinculada ao lançamento.\n[ET]-{$oDaoPagOrdem->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                /**
                 * atualizams os valores liquidados da nota, e empenho
                 */
                $this->getDados($this->numemp);

                /**
                 * alteramos o valor liquidado da nota
                 */
                $oDaoEmpnotaEle              = db_utils::getDao("empnotaele");
                $oDaoEmpnotaEle->e70_codnota = $oNota->e69_codnota;
                $nValorLiquidado             = $oNota->e70_vlrliq - $oDadosLancamento->valor;
                $oDaoEmpnotaEle->e70_vlrliq  = "{$nValorLiquidado}";
                $oDaoEmpnotaEle->alterar($oNota->e69_codnota);
                if ($oDaoEmpnotaEle->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar valores liquidados da nota.\n[ET]-{$oDaoEmpnotaEle->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                /**
                 * Alteramos o valor liquidado do empenho
                 */
                $oDaoEmpempenho             = db_utils::getDao("empempenho");
                $oDaoEmpempenho->e60_numemp = $this->numemp;
                $nValorLiquidado            = $this->dadosEmpenho->e60_vlrliq - $oDadosLancamento->valor;
                $oDaoEmpempenho->e60_vlrliq = "{$nValorLiquidado}";
                $oDaoEmpempenho->alterar($this->numemp);
                if ($oDaoEmpempenho->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpempenho->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /*
         * Alteramos os valores liquidados o elemento do empenho
         */
                $oDaoEmpElemento             = db_utils::getDao("empelemento");
                $oDaoEmpElemento->e64_numemp = $this->numemp;
                $nValorLiquidado             = $this->dadosEmpenho->e60_vlrliq - $oDadosLancamento->valor;
                $oDaoEmpElemento->e64_vlrliq = "{$nValorLiquidado}";
                @$oDaoEmpElemento->alterar($this->numemp);
                if ($oDaoEmpempenho->erro_status == 0) {

                    $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpElemento->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
                break;

            case 21:

                $this->excluirLancamentoEstornoLiquidacao($oDadosLancamento);
                break;

            case 30:

                $this->manutencaoLancamentosPagamento($oDadosLancamento, null, 3);
                break;

            case 31:

                $this->manutencaoLancamentosPagamento($oDadosLancamento, null, 4);
                break;
            case 200:
            case 201:
                /**
                 * excluimos os lancamentos contábeis
                 */
                lancamentoContabil::excluirLancamento($oDadosLancamento->codigo);
                break;

            case 90:
                lancamentoContabil::excluirLancamento($oDadosLancamento->codigo);
                break;

            case 414:
            case 415:
                lancamentoContabil::excluirLancamento($oDadosLancamento->codigo);
                break;
        }
    }

    protected function excluirLancamentoEstornoLiquidacao($oDadosLancamento) {


        /*
     * Alteramos os valores nas tabelas empnotaele, empempenho, empelemento
     */
        $this->getDados($this->numemp);
        $oNota = db_utils::fieldsMemory($this->getNotas($this->numemp,
          "e69_codnota = $oDadosLancamento->codigonotafiscal"),
          0);

        /**
         * buscamos as informações da ordem, e verificamos se existe alguma ordem pagamento ativa.
         * caso exista, ainda existe alguma liquidação ativa, e o nao podemos excluir o lançamento
         */

        $oDaoPagOrdemNota = db_utils::getDao("pagordemnota");
        $sSqlOrdens       = $oDaoPagOrdemNota->sql_query_valorordem(null,
          $oDadosLancamento->codigonotafiscal,
          "e69_numero,e50_codord,e71_anulado,pagordemele.*");
        $rsOrdens         = $oDaoPagOrdemNota->sql_record($sSqlOrdens);
        $aOrdemPagamentos = db_utils::getCollectionByRecord($rsOrdens);
        $oOrdem           = null;
        foreach ($aOrdemPagamentos as $oOrdemPagamento) {

            if ($oOrdemPagamento->e71_anulado == 'f') {

                $sErroMsg = "Existe uma liquidação para a nota {$oOrdemPagamento->e69_numero}.\n";
                $sErroMsg .= "Não será possivel excluir o lançamento!";
                throw new Exception($sErroMsg);
            }

            if ($oOrdemPagamento->e50_codord == $oDadosLancamento->ordempagamento) {
                $oOrdem = $oOrdemPagamento;
            }
        }

        /**
         * Verificamos se a nota possui Ordens de compra automaticas,
         * caso tenha, a rotina de storno de liquidação anula essas OC's.
         * devemso excluir essa anulação;
         */
        $sSQLOrdem = "select m51_tipo,";
        $sSQLOrdem .= "       m52_codlanc,";
        $sSQLOrdem .= "       m72_codordem,";
        $sSQLOrdem .= "       m52_valor,";
        $sSQLOrdem .= "       m52_quant";
        $sSQLOrdem .= "  from matordem ";
        $sSQLOrdem .= "        inner join empnotaord   on m72_codordem    = m51_codordem";
        $sSQLOrdem .= "        inner join matordemitem on m51_codordem    = m52_codordem ";
        $sSQLOrdem .= " where m72_codnota = {$oDadosLancamento->codigonotafiscal}";
        $sSQLOrdem .= "   and m51_tipo = 2";
        $rsOrdem = db_query($sSQLOrdem);
        if (pg_num_rows($rsOrdem) > 0) {

            $aOrdemCompras       = db_utils::getCollectionByRecord($rsOrdem);
            $oDaoMatOrdemItemanu = db_utils::getDao("matordemitemanu");
            foreach ($aOrdemCompras as $oOrdemCompra) {

                /**
                 * Exclui matordemitemanu
                 */
                $oDaoMatOrdemItemanu->excluir(null, "m36_matordemitem={$oOrdemCompra->m52_codlanc}");
                if ($oDaoMatOrdemItemanu->erro_status == 0) {
                    throw new Exception("Erro ao excluir itens anulados da Ordem de Compra.");
                }
            }
        }

        /**
         * alteramos o valor anulado da OP
         */
        $nValorAnuladoOrdem          = $oOrdem->e53_vlranu - $oDadosLancamento->valor;
        $oDaoPagOrdemele             = db_utils::getDao("pagordemele");
        $oDaoPagOrdemele->e53_codord = $oDadosLancamento->ordempagamento;
        $oDaoPagOrdemele->e53_vlranu = "{$nValorAnuladoOrdem}";
        $oDaoPagOrdemele->alterar($oDadosLancamento->ordempagamento);
        if ($oDaoPagOrdemele->erro_status == 0) {

            throw new Exception("Erro ao cancelar valores anulados da ordem de pagamento.");
        }

        /**
         * Alteramos a ligacao da nota e ordem de pagamento para como anulado = false;
         */
        $oDaoPagOrdemNota->e71_codord  = $oDadosLancamento->ordempagamento;
        $oDaoPagOrdemNota->e71_anulado = "false";
        $oDaoPagOrdemNota->alterar($oDadosLancamento->ordempagamento);
        if ($oDaoPagOrdemNota->erro_status == 0) {
            throw new Exception("Erro ao cancelar valores anulados da ordem de pagamento.");
        }
        /**
         * excluimos os lancamentos contábeis
         */
        lancamentoContabil::excluirLancamento($oDadosLancamento->codigo);
        /**
         * alteramos o valor liquidado da nota
         */
        $oDaoEmpnotaEle              = db_utils::getDao("empnotaele");
        $oDaoEmpnotaEle->e70_codnota = $oNota->e69_codnota;
        $nValorLiquidado             = $oNota->e70_vlrliq + $oDadosLancamento->valor;
        $oDaoEmpnotaEle->e70_vlrliq  = "{$nValorLiquidado}";
        $oDaoEmpnotaEle->alterar($oNota->e69_codnota);
        if ($oDaoEmpnotaEle->erro_status == 0) {

            $sErroMensagem = "Erro ao alterar valores liquidados da nota.\n[ET]-{$oDaoEmpnotaEle->erro_msg}";
            throw new Exception($sErroMensagem);
        }

        /**
         * Alteramos o valor liquidado do empenho
         */
        $oDaoEmpempenho             = db_utils::getDao("empempenho");
        $oDaoEmpempenho->e60_numemp = $this->numemp;
        $nValorLiquidado            = $this->dadosEmpenho->e60_vlrliq + $oDadosLancamento->valor;
        $oDaoEmpempenho->e60_vlrliq = "{$nValorLiquidado}";
        $oDaoEmpempenho->alterar($this->numemp);
        if ($oDaoEmpempenho->erro_status == 0) {

            $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpempenho->erro_msg}";
            throw new Exception($sErroMensagem);
        }

        $oDaoEmpElemento             = db_utils::getDao("empelemento");
        $oDaoEmpElemento->e64_numemp = $this->numemp;
        $nValorLiquidado             = $this->dadosEmpenho->e60_vlrliq + $oDadosLancamento->valor;
        $oDaoEmpElemento->e64_vlrliq = "{$nValorLiquidado}";
        @$oDaoEmpElemento->alterar($this->numemp);
        if ($oDaoEmpempenho->erro_status == 0) {

            $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpElemento->erro_msg}";
            throw new Exception($sErroMensagem);
        }
    }

    public function excluirLancamentoDesconto($iCodigoDesconto) {


        if (!db_utils::inTransaction()) {
            throw new Exception("Não há transação aberta.\nProcedimento Cancelado");
        }

        /**
         * buscamos todos os lancamentos contábeis do desconto
         */
        $sWhere               = " e33_pagordemdesconto = {$iCodigoDesconto}";
        $aLancamentosDesconto = $this->getLancamentosContabeis($sWhere);
        /**
         * Verificamos se o lancamento informado é realmente do empenho
         */
        $dtLancamento = '';
        require_once(modification("classes/lancamentoContabil.model.php"));
        if (count($aLancamentosDesconto) != 2) {

            $sMessageDesconto = "Não existem lançamentos de desconto para esse desconto.\nVerifique o lançamento, ";
            $sMessageDesconto .= "ou contate  suporte.";
            throw new Exception($sMessageDesconto);
        }

        $oOrdemAlterar           = new stdClass();
        $oOrdemAlterar->primeiro = null;
        $oOrdemAlterar->segundo  = null;
        foreach ($aLancamentosDesconto as $oLancamentoDesconto) {

            $oDadosLancamento = lancamentoContabil::getInfoLancamento($oLancamentoDesconto->codigo, false);
            if ($oDadosLancamento->empenho != $this->numemp) {
                throw new Exception("o Lançamento ({$oLancamentoDesconto->codigo}) informado não pertence ao empenho ({$this->numemp})");
            }
            $dtLancamento = $oLancamentoDesconto->data;
            if ($oLancamentoDesconto->tipodocumento == 21) {
                $oOrdemAlterar->primeiro = $oLancamentoDesconto;
            } else {
                $oOrdemAlterar->segundo = $oLancamentoDesconto;
            }
        }
        if ($dtLancamento == "") {

            $sMessageDesconto = "Data dos lancamentos contabeis de desconto estão inválidas,\nVerifique o Lançamento, ";
            $sMessageDesconto .= "ou contate  suporte.";
            throw new Exception($sMessageDesconto);
        }


        /**
         * excuimos o desconto  data do desconto na tabela pagordemdesconto
         */
        $oDaoPagordemDescontoLanc = db_utils::getDao("pagordemdescontolanc");
        $oDaoPagordemDescontoLanc->excluir(null, "e33_pagordemdesconto={$iCodigoDesconto}");
        if ($oDaoPagordemDescontoLanc->erro_status == 0) {
            throw new Exception("Houve um erro ao excluir o desconto.\n[ET]{$oDaoPagordemDescontoLanc->erro_msg}");
        }
        $oDaoPagordemDesconto                 = db_utils::getDao("pagordemdesconto");
        $oDaoPagordemDesconto->e34_sequencial = $iCodigoDesconto;
        $oDaoPagordemDesconto->excluir($iCodigoDesconto);
        if ($oDaoPagordemDesconto->erro_status == 0) {
            throw new Exception("Houve um erro ao excluir a data do desconto.\n[ET]{$oDaoPagordemDesconto->erro_msg}");
        }
        $this->excluirLancamento($oOrdemAlterar->primeiro->codigo);
        $this->excluirLancamento($oOrdemAlterar->segundo->codigo);
    }

    private function manutencaoLancamentosPagamento($oDadosLancamento, $dtLancamento, $iTipo) {

        /**
         * Se houver retenção, não pode alterar a data do lançamento de pagamento ou estorno.
         */
        if (in_array($iTipo, array(1, 2))) {

            if (ManutencaoRetencao::temRetencao($oDadosLancamento->codigo)) {

                $sMsgErro = "O lançamento '{$oDadosLancamento->codigo}' não pode ser alterado pois possui lançamentos de ";
                $sMsgErro .= "retenção.";
                throw new BusinessException($sMsgErro);
            }
        }

        $this->getDados($this->numemp);
        if (($iTipo == 1 || $iTipo == 2) && $dtLancamento == $oDadosLancamento->data) {
            return true;
        }
        if ($iTipo == 1 || $iTipo == 2) {

            $sMsgTipo = "";
            switch ($iTipo) {

                case 1:

                    $sMsgTipo = "pagamento";
                    break;
                case 2:

                    $sMsgErro = "estorno do pagamento";
                    break;
            }
            /**
             * Validamos as liquidacao de Empenho.
             */
            $sWhereEmpenho       = "c53_tipo in(20) and c80_codord = {$oDadosLancamento->ordempagamento}";
            $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
            if (count($aLancamentosEmpenho) > 0) {

                $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                if (db_strtotime($dtLancamento) < db_strtotime($oLancamentoEmpenho->data)) {

                    $sErroMensagem = "Data do {$sMsgTipo} da ordem de pagamento deve ser MAIOR ou IGUAL a data de liquidacao.\n";
                    $sErroMensagem .= "Operação cancelada.";
                    throw new Exception($sErroMensagem);
                }
            } else {

                $sErroMensagem = "Não foi encontrado lançamento de liquidação vinculado a ordem de pagamento.\n";
                $sErroMensagem .= "Operação cancelada.";
                throw new Exception($sErroMensagem);
            }

            /**
             * Validamos o lancmento de estorno de pagamento
             */
            if ($iTipo == 1) {

                $sWhereEmpenho       = "c53_tipo in(31) and c80_codord = {$oDadosLancamento->ordempagamento}";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtLancamento) > db_strtotime($oLancamentoEmpenho->data)) {

                        $sErroMensagem = "Data do pagamento da ordem de pagamento deve ser MENOR ou IGUAL a data de estorno ";
                        $sErroMensagem .= "do pagamento da Ordem.\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }
            } else if ($iTipo == 2) {

                $sWhereEmpenho       = "c53_tipo in(30) and c80_codord = {$oDadosLancamento->ordempagamento}";
                $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
                if (count($aLancamentosEmpenho) > 0) {

                    $oLancamentoEmpenho = $aLancamentosEmpenho[0];
                    if (db_strtotime($dtLancamento) < db_strtotime($oLancamentoEmpenho->data)) {

                        $sErroMensagem = "Data do estorno do pagamento da ordem de pagamento deve ser MAIOR ou IGUAL a data de pagamento ";
                        $sErroMensagem .= "do pagamento da Ordem.\n";
                        $sErroMensagem .= "Operação cancelada.";
                        throw new Exception($sErroMensagem);
                    }
                }
            }
            /**
             * Verificamos se a op da nota está configurada para cheque, ou arquivo
             */
            $sSqlMov = "select e97_codforma,";
            $sSqlMov .= "       e96_descr,    ";
            $sSqlMov .= "       e90_codgera, ";
            $sSqlMov .= "       e81_codmov, ";
            $sSqlMov .= "       e91_cheque, ";
            $sSqlMov .= "       e91_ativo, ";
            $sSqlMov .= "       e86_data, ";
            $sSqlMov .= "       e87_data ";
            $sSqlMov .= "  from empord";
            $sSqlMov .= "       inner join empagemov      on e82_codmov   = e81_codmov";
            $sSqlMov .= "       left join empagemovforma on e81_codmov    = e97_codmov";
            $sSqlMov .= "       left join empageforma    on e97_codforma  = e96_codigo";
            $sSqlMov .= "       left  join empageconfche  on e81_codmov   = e91_codmov and e91_ativo is true";
            $sSqlMov .= "       left  join empageconf     on e81_codmov   = e86_codmov ";
            $sSqlMov .= "       left  join empageconfgera on e81_codmov   = e90_codmov";
            $sSqlMov .= "       left  join empagegera     on e90_codgera  = e87_codgera";
            $sSqlMov .= " where e82_codord = {$oDadosLancamento->ordempagamento}";
            $sSqlMov .= "   and e81_cancelado is null";
            $rsMov = db_query($sSqlMov);
            if ($rsMov && pg_num_rows($rsMov) > 0) {

                $aMovimentos = db_utils::getCollectionByRecord($rsMov);
                foreach ($aMovimentos as $oMovimento) {

                    if ($oMovimento->e97_codforma == 2 && $oMovimento->e91_cheque != "") {

                        /**
                         * ordem de pagamento possui cheque emitido. devemos validar a data
                         * a data da ordem nao pode ser menor que a data do cheque
                         */
                        if (db_strtotime($dtLancamento) < db_strtotime($oMovimento->e86_data)) {

                            $sMsgErro = "O Lançamento '{$oDadosLancamento->codigo}', possui a ordem de pagamento ";
                            $sMsgErro .= "'{$oDadosLancamento->ordempagamento}' ";
                            $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' com cheque  número ";
                            $sMsgErro .= "'{$oMovimento->e91_cheque}' Emitido com a data de " . db_formatar($oMovimento->e86_data,
                                "d") . ".\n";
                            $sMsgErro .= "a Data do {$sMsgTipo} não pode ser menor que a data do cheque.";
                            throw new Exception($sMsgErro);
                        }
                    } else if ($oMovimento->e97_codforma == 3 && $oMovimento->e90_codgera != "") {

                        /**
                         * ordem de pagamento está em transmissão para pagamento eletronico. devemos cancelar
                         */
                        if (db_strtotime($dtLancamento) < db_strtotime($oMovimento->e87_data)) {

                            $sMsgErro = "O Lançamento '{$oDadosLancamento->codigo}', possui a ordem de pagamento ";
                            $sMsgErro .= "'{$oDadosLancamento->ordempagamento}' ";
                            $sMsgErro .= "com o movimento '{$oMovimento->e81_codmov}' no arquivo{$oMovimento->e90_codgera} ";
                            $sMsgErro .= "Emitido com a data de " . db_formatar($oMovimento->e87_data, "d") . ".\n";
                            $sMsgErro .= "a Data do {$sMsgTipo} não pode ser menor que a data do arquivo.";
                            throw new Exception($sMsgErro);
                        }
                    }
                }
            }
        }

        if ($iTipo == 3) {

            /**
             * Validamos os estornos de Empenho.
             */
            $sWhereEmpenho       = "c53_tipo = 31 and c80_codord = {$oDadosLancamento->ordempagamento}";
            $aLancamentosEmpenho = $this->getLancamentosContabeis($sWhereEmpenho);
            if (count($aLancamentosEmpenho) > 0) {

                $sErroMensagem = "existem estornos de pagamento para a ordem de pagamento {$oDadosLancamento->ordempagamento}.\n";
                $sErroMensagem .= "Operação cancelada.";
                throw new Exception($sErroMensagem);
            }
        }
        /**
         * Verificamos se nao existe um boletim liberado para a data original do lancamento e
         * a data modificada pelo usuário
         */
        if ($iTipo == 1 || $iTipo == 2) {

            $sSqlBoletim = "select *       ";
            $sSqlBoletim .= "  from boletim ";
            $sSqlBoletim .= " where k11_data   = '{$oDadosLancamento->data}' ";
            $sSqlBoletim .= "   and k11_instit = " . db_getsession("DB_instit");
            $rsBoletim = db_query($sSqlBoletim);
            $oBoletim  = db_utils::fieldsMemory($rsBoletim, 0);

            if ($oBoletim->k11_lanca == "t") {

                $dtDiaBoletim = db_formatar($oDadosLancamento->data, "d");
                $sMsg         = "Não é permitido alterar lançamentos com boletim processado.\nBoletim do caixa para o dia {$dtDiaBoletim}.";
                $sMsg .= "já processado.\nOperação cancelada";
                throw new exception ($sMsg);

            }
            if ($oBoletim->k11_libera == "t") {

                $dtDiaBoletim = db_formatar($oDadosLancamento->data, "d");
                $sMsg         = "Não é permitido alterar lançamentos com boletim liberado.\nBoletim do caixa para o dia {$dtDiaBoletim}.";
                $sMsg .= "já liberado para a contabilidade.\nOperação cancelada";
                throw new exception ($sMsg);

            }

            $sSqlBoletim = "select *       ";
            $sSqlBoletim .= "  from boletim ";
            $sSqlBoletim .= " where k11_data   = '{$dtLancamento}' ";
            $sSqlBoletim .= "   and k11_instit = " . db_getsession("DB_instit");
            $rsBoletim = db_query($sSqlBoletim);
            $oBoletim  = db_utils::fieldsMemory($rsBoletim, 0);
            if ($oBoletim->k11_lanca == "t") {

                $dtDiaBoletim = db_formatar($dtLancamento, "d");
                $sMsg         = "Não é permitido alterar lançamentos com boletim processado.\nBoletim do caixa para o dia {$dtDiaBoletim}.";
                $sMsg .= "já processado.\nOperação cancelada";
                throw new exception ($sMsg);

            }
            if ($oBoletim->k11_libera == "t") {

                $dtDiaBoletim = db_formatar($dtLancamento, "d");
                $sMsg         = "Não é permitido alterar lançamentos com boletim liberado.\nBoletim do caixa para o dia {$dtDiaBoletim}.";
                $sMsg .= "já liberado para a contabilidade.\nOperação cancelada";
                throw new exception ($sMsg);

            }

        }
        /**
         * alteramos a data nas tabelas de autenticacao
         */
        $sSqlCorrente = "SELECT  c23_sequencial, ";
        $sSqlCorrente .= "        corgrupocorrente.*, ";
        $sSqlCorrente .= "        corrente.*, ";
        $sSqlCorrente .= "        coremp.k12_empen, ";
        $sSqlCorrente .= "        coremp.k12_codord, ";
        $sSqlCorrente .= "        coremp.k12_cheque, ";
        $sSqlCorrente .= "        corconf.k12_ativo as chequeativo,";
        $sSqlCorrente .= "        corconf.k12_codmov as codcheque, ";
        $sSqlCorrente .= "        corempagemov.k12_codmov as codigomovimento, ";
        $sSqlCorrente .= "        corempagemov.k12_sequencial as sequencial, ";
        $sSqlCorrente .= "        corlanc.k12_conta as corlancconta, ";
        $sSqlCorrente .= "        corlanc.k12_codigo as corlanccodigo ";
        $sSqlCorrente .= "  from conlancamcorgrupocorrente ";
        $sSqlCorrente .= "       inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial ";
        $sSqlCorrente .= "       inner join corrente         on  k105_id     = corrente.k12_id ";
        $sSqlCorrente .= "                                  and  k105_data   = corrente.k12_data ";
        $sSqlCorrente .= "                                  and  k105_autent = corrente.k12_autent ";
        $sSqlCorrente .= "       left  join coremp           on  corrente.k12_id     = coremp.k12_id ";
        $sSqlCorrente .= "                                  and  corrente.k12_data   = coremp.k12_data ";
        $sSqlCorrente .= "                                  and  corrente.k12_autent = coremp.k12_autent ";
        $sSqlCorrente .= "       left  join corconf          on  corrente.k12_id     = corconf.k12_id ";
        $sSqlCorrente .= "                                  and  corrente.k12_data   = corconf.k12_data ";
        $sSqlCorrente .= "                                  and  corrente.k12_autent = corconf.k12_autent ";
        $sSqlCorrente .= "       left  join corempagemov     on  corrente.k12_id     = corempagemov.k12_id ";
        $sSqlCorrente .= "                                  and  corrente.k12_data   = corempagemov.k12_data ";
        $sSqlCorrente .= "                                  and  corrente.k12_autent = corempagemov.k12_autent ";
        $sSqlCorrente .= "       left  join corlanc          on  corrente.k12_id     = corlanc.k12_id ";
        $sSqlCorrente .= "                                  and  corrente.k12_data   = corlanc.k12_data ";
        $sSqlCorrente .= "                                  and  corrente.k12_autent = corlanc.k12_autent ";
        $sSqlCorrente .= " where c23_conlancam = {$oDadosLancamento->codigo}";
        $rsCorrente = db_query($sSqlCorrente);
        if (pg_num_rows($rsCorrente) == 0) {

            $sMsg = "Não foram encontrados informação sobre o lançamento contábil na tesouraria.\n";
            $sMsg .= "Operação cancelada.";
            throw new exception ($sMsg);
        }
        $oDadosCorrente = db_utils::fieldsMemory($rsCorrente, 0);


        /**
         * Verifica se o lançamento possui retenção vinculada e realiza a exclusão.
         */
        if ($iTipo == 3) {
            ManutencaoRetencao::excluirRetencao($oDadosLancamento->codigo);
        }

        /**
         * Verifica se existe, e exclui, lançamentos de apropriação de retenção.
         */
        if ($iTipo == 4) {
            lancamentoContabil::excluirLancamentosApropriacao($oDadosLancamento->codigo);
        }

        /**
         * incluimos uma nova autenticacao(corrente), e excluimos as tabelas vinculadas.
         *
         */
        $oDaoCorrente = db_utils::getDao("corrente");
        if ($iTipo == 1 || $iTipo == 2) {

            /**
             * Pesquisamos a proxima autenticacao da Corrente ,no dia da alteração para o caixa da autenticação original
             */
            $sSqlAutenticacao = "select coalesce(max(k12_autent), 0) as idautenticacao";
            $sSqlAutenticacao .= "  from corrente ";
            $sSqlAutenticacao .= " where k12_id     = {$oDadosCorrente->k105_id}";
            $sSqlAutenticacao .= "   and k12_data   = '{$dtLancamento}'";
            $sSqlAutenticacao .= "   and k12_instit = " . db_getsession("DB_instit");
            $iAutenticacao            = db_utils::fieldsMemory(db_query($sSqlAutenticacao), 0)->idautenticacao + 1;
            $oDaoCorrente->k12_data   = $dtLancamento;
            $oDaoCorrente->k12_id     = $oDadosCorrente->k12_id;
            $oDaoCorrente->k12_autent = $iAutenticacao;
            $oDaoCorrente->k12_estorn = "false";
            $oDaoCorrente->k12_hora   = $oDadosCorrente->k12_hora;
            $oDaoCorrente->k12_instit = db_getsession("DB_instit");
            $oDaoCorrente->k12_conta  = $oDadosCorrente->k12_conta;
            $oDaoCorrente->k12_valor  = $oDadosCorrente->k12_valor;
            $oDaoCorrente->incluir($oDadosCorrente->k12_id, $dtLancamento, $iAutenticacao);
            if ($oDaoCorrente->erro_status == 0) {

                $sErroMensagem = "Erro ao inserir nova autenticação.\n";
                $sErroMensagem .= "[ET] - {$oDaoCorrente->erro_msg}";
                throw new Exception($sErroMensagem);
            }
        }
        if ($oDadosCorrente->k12_empen != "") {

            $oDaoCoremp             = db_utils::getDao("coremp");
            $oDaoCoremp->k12_id     = $oDadosCorrente->k12_id;
            $oDaoCoremp->k12_data   = $oDadosCorrente->k12_data;
            $oDaoCoremp->k12_autent = $oDadosCorrente->k12_autent;
            $oDaoCoremp->excluir($oDadosCorrente->k12_id, $oDadosCorrente->k12_data, $oDadosCorrente->k12_autent);
            if ($oDaoCoremp->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações do empenho na autenticação.\n";
                $sErroMensagem .= "[ET] - {$oDaoCoremp->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            if ($oDadosCorrente->corlancconta != "") {


                $oDaoCorlanc             = db_utils::getDao("corlanc");
                $oDaoCorlanc->k12_id     = $oDadosCorrente->k12_id;
                $oDaoCorlanc->k12_data   = $oDadosCorrente->k12_data;
                $oDaoCorlanc->k12_autent = $oDadosCorrente->k12_autent;
                $oDaoCorlanc->excluir($oDadosCorrente->k12_id, $oDadosCorrente->k12_data, $oDadosCorrente->k12_autent);
                if ($oDaoCorlanc->erro_status == 0) {

                    $sErroMensagem = "Erro ao excluir informações do empenho na corlanc.\n";
                    $sErroMensagem .= "[ET] - {$oDaoCorlanc->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
            }

            /**
             * incluimos a nova autenticacao, case seje alteração  de data (iTipo = 1)
             */
            if ($iTipo == 1 || $iTipo == 2) {

                $oDaoCoremp->k12_cheque = $oDadosCorrente->k12_cheque;
                $oDaoCoremp->k12_codord = $oDadosCorrente->k12_codord;
                $oDaoCoremp->k12_empen  = $oDadosCorrente->k12_empen;
                $oDaoCoremp->k12_data   = $dtLancamento;
                $oDaoCoremp->k12_id     = $oDadosCorrente->k12_id;
                $oDaoCoremp->k12_autent = $iAutenticacao;
                $oDaoCoremp->incluir($oDadosCorrente->k12_id, $dtLancamento, $iAutenticacao);
                if ($oDaoCoremp->erro_status == 0) {

                    $sErroMensagem = "Erro ao incluir informações do empenho na autenticação.\n";
                    $sErroMensagem .= "[ET] - {$oDaoCoremp->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                if ($oDadosCorrente->corlancconta != "") {

                    $oDaoCorlanc->k12_id     = $oDadosCorrente->k12_id;
                    $oDaoCorlanc->k12_data   = $dtLancamento;
                    $oDaoCorlanc->k12_autent = $iAutenticacao;
                    $oDaoCorlanc->k12_conta  = $oDadosCorrente->corlancconta;
                    $oDaoCorlanc->k12_codigo = $oDadosCorrente->corlanccodigo;
                    $oDaoCorlanc->incluir($oDadosCorrente->k12_id, $dtLancamento, $iAutenticacao);
                    if ($oDaoCorlanc->erro_status == 0) {

                        $sErroMensagem = "Erro ao incluir informações do empenho na corlanc.\n";
                        $sErroMensagem .= "[ET] - {$oDaoCorlanc->erro_msg}";
                        throw new Exception($sErroMensagem);
                    }
                }

            }
        }

        if ($oDadosCorrente->codcheque != "") {

            $oDaoCorconf             = db_utils::getDao("corconf");
            $oDaoCorconf->k12_id     = $oDadosCorrente->k12_id;
            $oDaoCorconf->k12_data   = $oDadosCorrente->k12_data;
            $oDaoCorconf->k12_autent = $oDadosCorrente->k12_autent;
            $sDelete                 = "delete from corconf where k12_id = {$oDadosCorrente->k12_id} ";
            $sDelete .= "  and k12_data = '{$oDadosCorrente->k12_data}' and k12_autent = $oDadosCorrente->k12_autent";
            $rsExclusaoCorconf = db_query($sDelete);
            if (!$rsExclusaoCorconf) {

                $sErroMensagem = "Erro ao excluir informações do cheque na autenticação.\n";
                $sErroMensagem .= "[ET] - " . pg_last_error();
                throw new Exception($sErroMensagem);

            }

            if ($iTipo == 1 || $iTipo == 2) {

                $oDaoCorconf->k12_id     = $oDadosCorrente->k12_id;
                $oDaoCorconf->k12_data   = $dtLancamento;
                $oDaoCorconf->k12_autent = $iAutenticacao;
                $oDaoCorconf->k12_codmov = $oDadosCorrente->codcheque;
                $sAtivo                  = $oDadosCorrente->chequeativo == "t" ? "true" : "false";
                $oDaoCorconf->k12_ativo  = $sAtivo;
                $oDaoCorconf->incluir($oDadosCorrente->k12_id, $dtLancamento, $iAutenticacao);
                if ($oDaoCorconf->erro_status == 0) {

                    $sErroMensagem = "Erro ao incluir informações do cheque na autenticação.\n";
                    $sErroMensagem .= "[ET] - {$oDaoCorconf->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
            }
        }

        if ($oDadosCorrente->sequencial != "") {

            $oDaoCorEmpagemov                 = db_utils::getDao("corempagemov");
            $oDaoCorEmpagemov->k12_sequencial = $oDadosCorrente->sequencial;
            $oDaoCorEmpagemov->excluir($oDadosCorrente->sequencial);
            if ($oDaoCorEmpagemov->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações do cheque na autenticação.\n";
                $sErroMensagem .= "[ET] - {$oDaoCorEmpagemov->erro_msg}";
                throw new Exception($sErroMensagem);
            }
            if ($iTipo == 1 || $iTipo == 2) {

                $oDaoCorEmpagemov->k12_id     = $oDadosCorrente->k12_id;
                $oDaoCorEmpagemov->k12_data   = $dtLancamento;
                $oDaoCorEmpagemov->k12_autent = $iAutenticacao;
                $oDaoCorEmpagemov->k12_codmov = $oDadosCorrente->codigomovimento;
                $oDaoCorEmpagemov->incluir(null);
                if ($oDaoCorEmpagemov->erro_status == 0) {

                    $sErroMensagem = "Erro ao incluir informações da agenda de pagamentos na autenticação.\n";
                    $sErroMensagem .= "[ET] - {$oDaoCorEmpagemov->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
            }
        }

        if ($oDadosCorrente->k105_sequencial != "") {

            /**
             * excluimos da conlancamcorgrupocorrente
             */
            $oDaoConlancamCorgrupocorrente                 = db_utils::getDao("conlancamcorgrupocorrente");
            $oDaoConlancamCorgrupocorrente->c23_sequencial = $oDadosCorrente->c23_sequencial;
            $oDaoConlancamCorgrupocorrente->excluir($oDadosCorrente->c23_sequencial);
            if ($oDaoConlancamCorgrupocorrente->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações da vinculação do lançamento contábil com a autenticação.\n";
                $sErroMensagem .= "[ET] - {$oDaoConlancamCorgrupocorrente->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            /**
             * excluimos corgrupocorrente
             */
            $oDaoCorGrupocorrente                  = db_utils::getDao("corgrupocorrente");
            $oDaoCorGrupocorrente->k105_sequencial = $oDadosCorrente->k105_sequencial;
            $oDaoCorGrupocorrente->excluir($oDadosCorrente->k105_sequencial);
            if ($oDaoCorGrupocorrente->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações do grupo de autenticações.\n";
                $sErroMensagem .= "[ET] - {$oDaoCorGrupocorrente->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            if ($iTipo == 1 || $iTipo == 2) {

                $oDaoCorGrupocorrente->k105_id       = $oDadosCorrente->k12_id;
                $oDaoCorGrupocorrente->k105_data     = $dtLancamento;
                $oDaoCorGrupocorrente->k105_autent   = $iAutenticacao;
                $oDaoCorGrupocorrente->k105_corgrupo = $oDadosCorrente->k105_corgrupo;
                $oDaoCorGrupocorrente->incluir(null);
                if ($oDaoCorGrupocorrente->erro_status == 0) {

                    $sErroMensagem = "Erro ao incluir informações do grupo de autenticações.\n";
                    $sErroMensagem .= "[ET] - {$oDaoCorGrupocorrente->erro_msg}";
                    throw new Exception($sErroMensagem);
                }

                /**
                 * vinculamos o lançamento ao grupo da autenticação
                 */
                $oDaoConlancamCorgrupocorrente->c23_conlancam        = $oDadosLancamento->codigo;
                $oDaoConlancamCorgrupocorrente->c23_corgrupocorrente = $oDaoCorGrupocorrente->k105_sequencial;
                $oDaoConlancamCorgrupocorrente->incluir(null);
                if ($oDaoConlancamCorgrupocorrente->erro_status == 0) {

                    $sErroMensagem = "Erro ao incluir informações da vinculação do lançamento contábil com a autenticação.\n";
                    $sErroMensagem .= "[ET] - {$oDaoConlancamCorgrupocorrente->erro_msg}";
                    throw new Exception($sErroMensagem);
                }
            }
        }

        /**
         * Exclui da corautent
         */
        $oDaoCorautent             = db_utils::getDao('corautent');
        $oDaoCorautent->k12_id     = $oDadosCorrente->k12_id;
        $oDaoCorautent->k12_data   = $oDadosCorrente->k12_data;
        $oDaoCorautent->k12_autent = $oDadosCorrente->k12_autent;
        $oDaoCorautent->excluir($oDadosCorrente->k12_id, $oDadosCorrente->k12_data, $oDadosCorrente->k12_autent);

        /**
         * excluimos a corrente original
         */
        $oDaoCorrente->k12_id     = $oDadosCorrente->k12_id;
        $oDaoCorrente->k12_data   = $oDadosCorrente->k12_data;
        $oDaoCorrente->k12_autent = $oDadosCorrente->k12_autent;
        $oDaoCorrente->excluir($oDadosCorrente->k12_id, $oDadosCorrente->k12_data, $oDadosCorrente->k12_autent);

        /**
         * Exclui configurações do movimento na agenda.
         */
        if ($iTipo == 3 && !empty($oDadosCorrente->codigomovimento)) {

            $oDaoEmpAgeConf = new cl_empageconf();
            $oDaoEmpAgeConf->excluir($oDadosCorrente->codigomovimento);
            if ($oDaoEmpAgeConf->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações da configuração do movimento da agenda.\n";
                $sErroMensagem .= "[ET] - {$oDaoEmpAgeConf->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            $oDaoEmpAgeMovForma = new cl_empagemovforma();
            $oDaoEmpAgeMovForma->excluir($oDadosCorrente->codigomovimento);
            if ($oDaoEmpAgeMovForma->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações da configuração do movimento da agenda.\n";
                $sErroMensagem .= "[ET] - {$oDaoEmpAgeMovForma->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            $oDaoEmpAgeConfGera = new cl_empageconfgera();
            $oDaoEmpAgeConfGera->excluir(null, null, "e90_codmov = {$oDadosCorrente->codigomovimento}");
            if ($oDaoEmpAgeConfGera->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações da configuração do movimento da agenda.\n";
                $sErroMensagem .= "[ET] - {$oDaoEmpAgeConfGera->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            $oDaoEmpAgeConfChe = new cl_empageconfche();
            $oDaoEmpAgeConfChe->excluir(null, "e91_codmov = {$oDadosCorrente->codigomovimento}");
            if ($oDaoEmpAgeConfChe->erro_status == 0) {

                $sErroMensagem = "Erro ao excluir informações da configuração do movimento da agenda.\n";
                $sErroMensagem .= "[ET] - {$oDaoEmpAgeConfChe->erro_msg}";
                throw new Exception($sErroMensagem);
            }
        }

        if ($oDaoCorrente->erro_status == 0) {

            $sErroMensagem = "Erro ao excluir autenticação.\n";
            $sErroMensagem .= "[ET] - {$oDaoCorrente->erro_msg}";
            throw new Exception($sErroMensagem);
        }
        if ($iTipo == 1 || $iTipo == 2) {
            lancamentoContabil::alterarDataLancamento($oDadosLancamento->codigo, $dtLancamento);
        } else {
            lancamentoContabil::excluirLancamento($oDadosLancamento->codigo);
        }

        /**
         * Alteramos a empage vinculada ao movimento na alteração de data de pagamento.
         */
        if ($iTipo == 1 && !empty($oDadosCorrente->codigomovimento)) {

            $oDaoEmpAgeConf = new cl_empageconf();
            $oDaoEmpAgeConf->e86_codmov = $oDadosCorrente->codigomovimento;
            $oDaoEmpAgeConf->e86_data = $dtLancamento;
            if (!$oDaoEmpAgeConf->alterar($oDadosCorrente->codigomovimento)) {
                throw new DBException("Houve um erro ao atualizar o movimento da agenda.");
            }

            /**
             * Primeiramente busca se já existe um empage para o dia e instituição.
             */
            $iInstituicao  = db_getsession('DB_instit');
            $sCamposEmpAge = "e80_codage";
            $sWhereEmpAge  = "e80_instit = {$iInstituicao} and e80_cancelado is null and e80_data = '{$dtLancamento}'";

            $oDaoEmpAge = new cl_empage();
            $sSqlEmpAge = $oDaoEmpAge->sql_query_file(null, $sCamposEmpAge, null, $sWhereEmpAge);
            $rsEmpAge   = db_query($sSqlEmpAge);
            if (!$rsEmpAge) {
                throw new DBException("Houve um erro ao buscar as informações da agenda de pagamento.");
            }

            $iEmpAge = null;
            if (pg_num_rows($rsEmpAge) == 1) {
                $iEmpAge = db_utils::fieldsMemory($rsEmpAge, 0)->e80_codage;
            } else {

                /**
                 * Se não houver empage para a data e instituição, cria uma nova.
                 */
                $oDaoEmpAge->e80_data   = $dtLancamento;
                $oDaoEmpAge->e80_instit = $iInstituicao;
                if (!$oDaoEmpAge->incluir(null)) {
                    throw new DBException("Houve um erro ao criar a agenda de pagamento para a data informada.");
                }
                $iEmpAge = $oDaoEmpAge->e80_codage;
            }

            $oDaoEmpAgeMov = new cl_empagemov();
            $oDaoEmpAgeMov->e81_codmov = $oDadosCorrente->codigomovimento;
            $oDaoEmpAgeMov->e81_codage = $iEmpAge;
            if (!$oDaoEmpAgeMov->alterar($oDadosCorrente->codigomovimento)) {
                throw new DBException("Houve um erro ao atualizar o movimento da agenda.");
            }
        }

        if ($iTipo == 3 || $iTipo == 4) {

            /**
             * Alteramos o valor liquidado do empenho
             */
            if ($iTipo == 3) {
                $nValorPago = $this->dadosEmpenho->e60_vlrpag - $oDadosLancamento->valor;
            } else {
                $nValorPago = $this->dadosEmpenho->e60_vlrpag + $oDadosLancamento->valor;
            }
            $oDaoEmpempenho             = db_utils::getDao("empempenho");
            $oDaoEmpempenho->e60_numemp = $this->numemp;
            $oDaoEmpempenho->e60_vlrpag = "{$nValorPago}";
            $oDaoEmpempenho->alterar($this->numemp);
            if ($oDaoEmpempenho->erro_status == 0) {

                $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpempenho->erro_msg}";
                throw new Exception($sErroMensagem);
            }

            $oDaoEmpElemento             = db_utils::getDao("empelemento");
            $oDaoEmpElemento->e64_numemp = $this->numemp;
            $oDaoEmpElemento->e64_vlrpag = "{$nValorPago}";
            @$oDaoEmpElemento->alterar($this->numemp);
            if ($oDaoEmpempenho->erro_status == 0) {

                $sErroMensagem = "Erro ao alterar valores liquidados do empenho.\n[ET]-{$oDaoEmpElemento->erro_msg}";
                throw new Exception($sErroMensagem);
            }
            /**
             * Alteramos a ordem de pagamento
             */
            $oDaoPagOrdemEle = db_utils::getDao("pagordemele");
            $sSqlOrdem       = $oDaoPagOrdemEle->sql_query_file($oDadosLancamento->ordempagamento);
            $rsOrdem         = $oDaoPagOrdemEle->sql_record($sSqlOrdem);
            if ($oDaoPagOrdemEle->numrows == 0) {
                throw new Exception("Ordem de pagamento sem Elementos vinculados");
            }

            $oDadosOrdemPagamento = db_utils::fieldsMemory($rsOrdem, 0);
            if ($iTipo == 3) {
                $nValorOrdem = $oDadosOrdemPagamento->e53_vlrpag - $oDadosLancamento->valor;
            } else {
                $nValorOrdem = $oDadosOrdemPagamento->e53_vlrpag + $oDadosLancamento->valor;
            }

            $oDaoPagOrdemEle->e53_codord = $oDadosLancamento->ordempagamento;
            $oDaoPagOrdemEle->e53_vlrpag = "{$nValorOrdem}";
            $oDaoPagOrdemEle->alterar($oDadosLancamento->ordempagamento);
            if ($oDaoPagOrdemEle->erro_status == 0) {
                throw new Exception("não foi possível alterar data de pagamento");
            }
        }
    }

    /**
     * @param       $iNumeroEmpenho
     * @param       $iAno
     * @param array $aDocumentos
     *
     * @return bool
     */
    public static function possuiLancamentoDeControle($iNumeroEmpenho, $iAno = null, array $aDocumentos) {

        $sDocumentos = implode(',', $aDocumentos);
        $aWhere      = array(
          "c75_numemp  = {$iNumeroEmpenho}",
          "c71_coddoc in ({$sDocumentos})"
        );
        if (!empty($iAno)) {
            $aWhere[] = "c70_anousu = {$iAno}";
        }

        $sWhere = implode(" and ", $aWhere);
        $sOrdem = " c70_codlan desc limit 1 ";

        $oDaoConlancam      = new cl_conlancamemp();
        $sSqlBuscaDocumento = $oDaoConlancam->sql_query_documentos(null, "conhistdoc.*", $sOrdem, $sWhere);
        $rsBuscaDocumento   = $oDaoConlancam->sql_record($sSqlBuscaDocumento);
        if ($oDaoConlancam->numrows == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $iSequencialEmpenho
     * @param $iAno
     *
     * @return $iCodigoDocumento
     * @throws Exception
     */
    public static function buscaUltimoDocumentoExecutado($iSequencialEmpenho, $iAno) {

        $aWhere = array(
          "c75_numemp = {$iSequencialEmpenho}", "c70_anousu = {$iAno}"
        );

        $sOrdem             = " c70_codlan desc limit 1 ";
        $sWhere             = implode(" and ", $aWhere);
        $oDaoConlancam      = new cl_conlancamemp();
        $sSqlBuscaDocumento = $oDaoConlancam->sql_query_documentos(null, "conhistdoc.*", $sOrdem, $sWhere);
        $rsBuscaDocumento   = $oDaoConlancam->sql_record($sSqlBuscaDocumento);
        if ($oDaoConlancam->erro_status == "0") {
            throw new Exception("Não foi possível localizar os lançamentos contábeis para o empenho {$iSequencialEmpenho}.");
        }

        return db_utils::fieldsMemory($rsBuscaDocumento, 0)->c53_coddoc;
    }

    /**
     * Retorna se a nota gerada foi da origem de ordem de compra manual.
     * @param $iCodigoNota
     * @return bool
     * @throws Exception
     */
    public static function ordemDeCompraManual($iCodigoNota) {

        $oDaoOrdem = new cl_empnotaord();
        $sSqlBuscaTipoOrdem = $oDaoOrdem->sql_matordem(null, null, "m51_tipo", null, "m72_codnota = {$iCodigoNota}");
        $rsBuscaTipo = db_query($sSqlBuscaTipoOrdem);
        if (!$rsBuscaTipo) {
            throw new Exception("Não foi localizada a ordem de compra para a nota {$iCodigoNota}.");
        }
        return db_utils::fieldsMemory($rsBuscaTipo, 0)->m51_tipo == 1;
    }

    /* [Extensão] ContratosPADRS: Atributos e Persistência do Tipo Instrumento Contratual */

    /*[Extensao] - Controle Interno Parte 2*/
}
