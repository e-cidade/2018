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

/**
 * Model para lançamentos contabeis
 * @package contabilidade
 * @author Iuri Guntchnigg Revisão$Author: dbricardo.lopes $
 * @version $Revision: 1.72 $
 */
class lancamentoContabil {

  public $iCodLanc            = null;
  private $dDataLanc           = null;
  private $iAnousu             = null;
  private $iCodDoc             = null;
  private $nValorLancando      = null;
  private $iAnoEmpenho         = null;
  private $iCodCom             = null;
  private $oTransaction        = null;
  private $lInverterLancamento = false;
  private $aLancamentos        = array();

  /**
   * metodo Construtor
   * @param integer $iCodDoc       Código do documento a set executado
   * @param integer $iAnoUsu       Ano do Lancamento
   * @param date    $dDataLanc     Data do lancamento.
   * @param float   $nValorLancado valor do lancamento;.
   */
  function __construct($iCodDoc, $iAnousu, $dDataLanc, $nValorLancado){

    $this->dDataLanc     = $dDataLanc;
    $this->iAnoUsu       = $iAnousu;
    $this->nValorLancado = $nValorLancado;
    $this->iCodDoc       = $iCodDoc;
    $this->lSqlErro      = false;
    $this->sErroMsg      = '';
    $this->aLancamentos = array(
      "lancamCgm"   => array("set" => 0),
      "lancamCompl" => array("set" => 0),
      "lancamDig"   => array("set" => 0),
      "lancamDot"   => array("set" => 0),
      "lancamEle"   => array("set" => 0),
      "lancamEmp"   => array("set" => 0),
      "lancamNota"  => array("set" => 0),
      "lancamOrd"   => array("set" => 0),
      "lancamPag"   => array("set" => 0),
      "lancamRec"   => array("set" => 0),
      "lancamRetif" => array("set" => 0),
      "lancamSup"   => array("set" => 0)
    );

    if (!db_utils::inTransaction()){
      throw new exception("{$iCodDoc} Nao foi possível iniciar lancamento.Nao foi possível achar uma transacao valida;");
    }
  }

  /**
   * Seta o cgm do lancamento
   * @param integer $iNumCgm Codigo do CGM
   * @return void
   */
  function setCgm($iNumCgm){

    $this->aLancamentos["lancamCgm"]["set"]        = 1;
    $this->aLancamentos["lancamCgm"]["c76_numcgm"] = $iNumCgm;
    $this->aLancamentos["lancamCgm"]["c76_data"]   = $this->dDataLanc;
    $this->oLancamCgm                              = db_utils::getDao("conlancamcgm");

  }

  /**
   * Seta o complemento do lancamento
   * @param string $sComplemento complemento do lancamento
   * @return void
   */

  function setComplemento($sComplemento){

    $this->aLancamentos["lancamCompl"]["set"]         = 1;
    $this->aLancamentos["lancamCompl"]["c72_complem"] = $sComplemento;
    $this->oLancamCompl                               = db_utils::getDao("conlancamcompl");

  }

  /**
   * Seta o grupo de Lancamentos (tabela conlancamdig);
   * @param string $sChave chave identificadora do Grupo
   * @return void
   */

  function setDigito($sChave) {

    $this->aLancamentos["lancamDig"]["set"]       = 1;
    $this->aLancamentos["lancamDig"]["c78_chave"] = $sChave;
    $this->aLancamentos["lancamDig"]["c78_data"]  = $this->dDataLanc;

  }

  /**
   * Seta o empenho
   * @param integer $iChave código do empenho
   * @param integer $iAnoEmpenho seta o ano do empenho (para doc 32,31,33)
   * @param integer $iCodCom tipo da compra
   */
  function setEmpenho($iChave, $iAnoEmpenho = null, $iCodCom = null){

    $this->aLancamentos["lancamEmp"]["set"]        = 1;
    $this->aLancamentos["lancamEmp"]["c75_numemp"] = $iChave;
    $this->aLancamentos["lancamEmp"]["c75_data"]   = $this->dDataLanc;
    $this->iAnoEmpenho                             = $iAnoEmpenho;
    $this->iCodCom                                 = $iCodCom;

  }
  /**
   * Seta o elemento
   * @param integer $iChave código do elemento
   */
  function setElemento($iChave){

    $this->aLancamentos["lancamEle"]["set"]        = 1;
    $this->aLancamentos["lancamEle"]["c67_codele"] = $iChave;

  }

  /**
   * Seta a dotação
   * @param integer $iChave código da dotação
   */
  function setDotacao($iChave){

    $this->aLancamentos["lancamDot"]["set"]        = 1;
    $this->aLancamentos["lancamDot"]["c73_coddot"] = $iChave;
    $this->aLancamentos["lancamDot"]["c73_anousu"] = $this->iAnoUsu;
    $this->aLancamentos["lancamDot"]["c73_data"]   = $this->dDataLanc;

  }

  /**
   * Seta a ordem de pagamento
   * @param integer $iChave código da orde de pagamento
   */
  function setOrdemPagamento($iChave){

    $this->aLancamentos["lancamOrd"]["set"]        = 1;
    $this->aLancamentos["lancamOrd"]["c80_codord"] = $iChave;
    $this->aLancamentos["lancamOrd"]["c80_data"]   = $this->dDataLanc;

  }

  /**
   * Seta a nota de empenho
   * @param integer $iChave código da nota
   */
  function setNota($iChave){

    $this->aLancamentos["lancamNota"]["set"]        = 1;
    $this->aLancamentos["lancamNota"]["c66_conota"]  = $iChave;

  }

  /**
   * seta o código reduzido do lancamento.
   * @param integer $iChave código do Reduzido.
   */

  function setReduz($iChave){

    $this->aLancamentos["lancamPag"]["set"]        = 1;
    $this->aLancamentos["lancamPag"]["c82_reduz"]  = $iChave;
    $this->aLancamentos["lancamPag"]["c82_anousu"] = $this->iAnoUsu;

  }
  /**
   * retorna o código do lançamento gerado .
   * @return integer
   */
  function getCodigoLancamento() {
    return $this->iCodLanc;
  }

  /**
   * seto código da suplementação;
   *
   * @param integer $iSuplementacao código da suplementação
   */
  function setCodigoSuplementacao($iSuplementacao) {

    $this->aLancamentos["lancamSup"]["set"]        = 1;
    $this->aLancamentos["lancamSup"]["c79_codsup"] = $iSuplementacao;
    $this->aLancamentos["lancamSup"]["c79_data"]   = $this->dDataLanc;
  }

  function setReceita($iReceita) {

    $this->aLancamentos["lancamRec"]["set"]        = 1;
    $this->aLancamentos["lancamRec"]["c74_codrec"] = $iReceita;
    $this->aLancamentos["lancamRec"]["c74_data"]   = $this->dDataLanc;
    $this->aLancamentos["lancamRec"]["c74_anousu"] = $this->iAnoUsu;
  }
  /**
   * salva os Lancamentos no banco;
   *
   */

  function salvar() {

    $oConLancam             = db_utils::getDao("conlancam");
    $oconLancamVal          = db_utils::getDao("conlancamval");
    $oConLancam->c70_anousu = $this->iAnoUsu;
    $oConLancam->c70_data   = $this->dDataLanc;
    $oConLancam->c70_valor  = $this->nValorLancado;
    $res = $oConLancam->incluir(null);
    //$oConlancam->erro_status = 0;
    if ($oConLancam->erro_status == 0) {

      $this->lSqlErro = true;
      $this->sErroMsg = "Não foi Possível incluir lançamento\nErro Técnico:{$oConLancam->erro_msg}";
      throw new exception($this->sErroMsg);
    }

    if ( !EventoContabil::vincularLancamentoNaInstituicao($oConLancam->c70_codlan, db_getsession('DB_instit')) ) {
      throw new Exception('Não foi possível vincular o lançamento a instituição.');
    }

    if ( !EventoContabil::vincularOrdem($oConLancam->c70_codlan) ) {
      throw new Exception('Não foi possível setar a ordem para o lançamento.');
    }

    $this->iCodLanc            = $oConLancam->c70_codlan;
    $oConLancamDoc             = db_utils::getDao("conlancamdoc");
    $oConLancamDoc->c71_data   = $this->dDataLanc;
    $oConLancamDoc->c71_coddoc = $this->iCodDoc;
    $oConLancamDoc->c71_codlan = $oConLancam->c70_codlan;
    $oConLancamDoc->incluir($oConLancam->c70_codlan);
    if ($oConLancamDoc->erro_status == 0) {
      $this->lSqlErro  = true;
      $this->sErroMsg  = "Não foi possível iniciar lançamentos Contábeis\n";
      $this->sErroMsg .= "({$oConLancamDoc->erro_msg})";
      throw new exception($this->sErroMsg);
    }

    /*
     * buscamos a transacao cadastrada para o documento selecionado,
     * e fazemos os lancamentos necessários;
     */
    if ($this->oTransaction == null) {
      try {
        $this->getTransacao();
      } catch (Exception $eErro) {
        throw new exception($eErro->getMessage());
      }
    }
    if (count($this->oTransaction->arr_debito) == 0 || count($this->oTransaction->arr_credito) == 0) {
      throw new exception("Verifique o cadastro do Documento {$this->iCodDoc}.\nEncontrado Inconsistências");
    }
    $aCredito    = $this->oTransaction->arr_credito;
    $aDebito     = $this->oTransaction->arr_debito;
    //se a flag InverterLancamento estiver true, invertemos  as contas de credito e debito;
    if ($this->lInverterLancamento){

      $aCredito    = $this->oTransaction->arr_debito;
      $aDebito     = $this->oTransaction->arr_credito;
    }

    $aHistori    = $this->oTransaction->arr_histori;
    $aSeqtranslr = $this->oTransaction->arr_seqtranslr;
    $oPlanoReduz = db_utils::getDao("conplanoreduz");
    $oDocumentoEvento = DocumentoEventoContabilRepository::getPorCodigo($this->iCodDoc);
    for ($iTran = 0; $iTran < count($aCredito); $iTran++) {

      $oPlanoReduz->sql_record($oPlanoReduz->sql_query_file(null, null, 'c61_codcon', '',
                                                            "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$aDebito[$iTran]));
      if ($oPlanoReduz->numrows == 0) {

        $this->lSqlErro = true;
        $sErroMsg = "(D) Conta {$aDebito[$iTran]} não disponível para o exercício!";
        throw new exception($sErroMsg);
        break;
      }
      $oPlanoReduz->sql_record($oPlanoReduz->sql_query_file(null, null,
                                                            'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")."
                                                              and c61_reduz ={$aCredito[$iTran]}"));
      if ($oPlanoReduz->numrows == 0 &&  $this->lSqlErro == false) {

        $this->lSqlErro = true;
        $sErroMsg = "(C) Conta {$aCredito[$iTran]} não disponível para o exercício!";
        throw new exception($sErroMsg);
        break;
      }
      unset($oConLancamVal);
      $oConLancamVal              = new cl_conlancamval;
      $oConLancamVal->c69_codlan  = $oConLancam->c70_codlan;
      $oConLancamVal->c69_credito = $aCredito[$iTran];
      $oConLancamVal->c69_debito  = $aDebito[$iTran];
      $oConLancamVal->c69_codhist = $aHistori[$iTran];
      $oConLancamVal->c69_valor   = $this->nValorLancado;
      $oConLancamVal->c69_data    = $this->dDataLanc;
      $oConLancamVal->c69_anousu  = $this->iAnoUsu;
      $oConLancamVal->incluir(null);
      if ($oConLancamVal->erro_status == 0) {

        $this->sErroMsg  = "Conlancamval:".$oConLancamVal->erro_msg;
        throw new exception($this->sErroMsg);
      }

      if (USE_PCASP) {

        /**
         * Salvamos os dados referente a conta corrente
         */
        if ($this->aLancamentos["lancamEmp"]["set"] == 1) {

          require_once modification("libs/db_app.utils.php");

          db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
          db_app::import("Acordo");
          db_app::import("AcordoComissao");
          db_app::import("CgmFactory");
          db_app::import("financeiro.*");
          db_app::import("contabilidade.*");
          db_app::import("contabilidade.lancamento.*");
          db_app::import("Dotacao");

          require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
          require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
          require_once(modification("model/financeiro/ContaBancaria.model.php"));
          require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
          require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
          require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
          require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
          require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
          require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));

          db_app::import("contabilidade.contacorrente.*");

          $oContaCorrenteDetalheCredito = new ContaCorrenteDetalhe();
          $oContaCorrenteDetalheDebito  = new ContaCorrenteDetalhe();
          $oEmpenhoFinanceiro           = new EmpenhoFinanceiro($this->aLancamentos["lancamEmp"]["c75_numemp"]);
          $oLancamentoAuxiliar          = new LancamentoAuxiliarEmpenho();

          $oLancamentoAuxiliar->setCaracteristicaPeculiar($oEmpenhoFinanceiro->getCaracteristicaPeculiar());
          $oLancamentoAuxiliar->setFavorecido($oEmpenhoFinanceiro->getCgm()->getCodigo());

          $iCodigoContrato = $oEmpenhoFinanceiro->getCodigoContrato();

          if (!empty($iCodigoContrato)) {
            $oLancamentoAuxiliar->setAcordo($iCodigoContrato);
          }

          if ($this->aLancamentos["lancamDot"]["set"] == 1) {

            $oDotacao = new Dotacao($this->aLancamentos["lancamDot"]["c73_coddot"], $this->iAnoUsu);
            $oLancamentoAuxiliar->setCodigoRecurso($oDotacao->getRecurso());
          }

          $oDotacaoEmpenho = $oEmpenhoFinanceiro->getDotacao();

          $oContaCorrenteDetalheCredito->setDotacao($oDotacaoEmpenho);
          $oContaCorrenteDetalheDebito->setDotacao($oDotacaoEmpenho);
          $oContaCorrenteDetalheCredito->setRecurso($oDotacaoEmpenho->getDadosRecurso());
          $oContaCorrenteDetalheDebito->setRecurso($oDotacaoEmpenho->getDadosRecurso());

          if (!empty($this->aLancamentos["lancamPag"]["c82_reduz"]) &&
            $oDocumentoEvento->getTipo() == DocumentoEventoContabil::TIPO_PAGAMENTO_EMPENHO) {

            $iReduzidoContaPagadora = $this->aLancamentos["lancamPag"]["c82_reduz"];
            $oPlanoConta = new ContaPlanoPCASP(null, $this->iAnoUsu, $iReduzidoContaPagadora);
            $oContaCorrenteDetalheCredito->setRecurso(new Recurso($oPlanoConta->getRecurso()));
          }

          if (!empty($this->aLancamentos["lancamPag"]["c82_reduz"]) &&
            $oDocumentoEvento->getTipo() == DocumentoEventoContabil::TIPO_ESTORNO_PAGAMENTO_EMPENHO) {

            $iReduzidoContaPagadora = $this->aLancamentos["lancamPag"]["c82_reduz"];
            $oPlanoConta = new ContaPlanoPCASP(null, $this->iAnoUsu, $iReduzidoContaPagadora, db_getsession('DB_instit'));
            $oContaCorrenteDetalheDebito->setRecurso(new Recurso($oPlanoConta->getRecurso()));
          }

          $oLancamentoAuxiliar->setNumeroEmpenho($this->aLancamentos["lancamEmp"]["c75_numemp"]);
          $oContaCorrenteDetalheCredito->setEmpenho($oEmpenhoFinanceiro);
          $oContaCorrenteDetalheDebito->setEmpenho($oEmpenhoFinanceiro);

          $oConplanoPCASP = new ContaPlanoPCASP( null,
                                                 $this->iAnoUsu,
                                                 $oConLancamVal->c69_credito,
                                                 db_getsession('DB_instit') );

          if ($oConplanoPCASP->getContaBancaria()) {
            $oContaCorrenteDetalheCredito->setContaBancaria($oConplanoPCASP->getContaBancaria());
          }

          $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalheCredito);

          $oContaCorrenteCredito = ContaCorrenteFactory::getInstance( $oConLancamVal->c69_sequen,
                                                                      $oConLancamVal->c69_credito,
                                                                      $oLancamentoAuxiliar );


          if ($oContaCorrenteCredito) {
            $oContaCorrenteCredito->salvar();
          }

          $oConplanoPCASP = new ContaPlanoPCASP( null,
                                                 $this->iAnoUsu,
                                                 $oConLancamVal->c69_debito,
                                                 db_getsession('DB_instit') );

          if ($oConplanoPCASP->getContaBancaria()) {
            $oContaCorrenteDetalheDebito->setContaBancaria($oConplanoPCASP->getContaBancaria());
          }

          $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalheDebito);

          $oContaCorrenteDebito  = ContaCorrenteFactory::getInstance( $oConLancamVal->c69_sequen,
                                                                      $oConLancamVal->c69_debito,
                                                                      $oLancamentoAuxiliar );

          if ($oContaCorrenteDebito) {
            $oContaCorrenteDebito->salvar();
          }

          unset($oEmpenhoFinanceiro);
          unset($oContaCorrenteCredito);
          unset($oContaCorrenteDebito);

        } else if ( $this->aLancamentos["lancamDot"]["set"] == 1 && $this->aLancamentos["lancamEmp"]["set"] == 0) {

          $oDotacao = new Dotacao($this->aLancamentos["lancamDot"]["c73_coddot"], $this->aLancamentos["lancamDot"]["c73_anousu"]);

          $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
          $oContaCorrenteDetalhe->setDotacao($oDotacao);
          $oContaCorrenteDetalhe->setRecurso($oDotacao->getDadosRecurso());

          $oLancamentoAuxiliar = new LancamentoAuxiliarSuplementacao();
          $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

          $oContaCorrenteCredito = ContaCorrenteFactory::getInstance($oConLancamVal->c69_sequen,
                                                                     $oConLancamVal->c69_credito,
                                                                     $oLancamentoAuxiliar);

          $oContaCorrenteDebito  = ContaCorrenteFactory::getInstance($oConLancamVal->c69_sequen,
                                                                     $oConLancamVal->c69_debito,
                                                                     $oLancamentoAuxiliar);
          if ($oContaCorrenteCredito) {
            $oContaCorrenteCredito->salvar();
          }
          if ($oContaCorrenteDebito) {
            $oContaCorrenteDebito->salvar();
          }
          unset($oDotacao);
          unset($oContaCorrenteCredito);
          unset($oContaCorrenteDebito);
        }
      }

    }


    /*
     * Percorremos os lancamentos configurados pelo usuario.
     */
    foreach($this->aLancamentos  as $obj => $lancam) {

      if ($lancam["set"] == 1) {

        $sObjNome = "con".strtolower($obj);
        $oObjeto  = db_utils::getDao($sObjNome);
        //Percorremos as propriedades do lancamento para lancar .
        foreach ($lancam as $sPropriedade => $sValor){

          $oObjeto->$sPropriedade = $sValor;
        }
        //algumas das conlancam possuem mais de um parametro. tratamos aqui.
        switch ($sObjNome){

          case "conlancamnota" :

            $oObjeto->incluir($oConLancam->c70_codlan,$this->aLancamentos["lancamNota"]["c66_conota"]);
            break;

          default:

            $oObjeto->incluir($oConLancam->c70_codlan);
            break;
        }
        if ($oObjeto->erro_status == 0){

          $this->sErroMsg  = "{$sObjNome}: ".$oObjeto->erro_msg;
          throw new exception($this->sErroMsg);
          return false;
        }
      }
    }
  }


  /**
   * Retorna o objeto de transacao com as contas que devem ser lançadas;
   * valida se os lancamentos configurados pelo usuario estao corretos.
   * @return object;
   */

  private function getTransacao() {

    require_once(modification("libs/db_libcontabilidade.php"));
    // $oTransLan    = db_utils::getDao("translan");
    if (!class_exists("cl_translan")){
      throw new exception("Problema ao buscar transações.");
    }
    $oTransLan    = new cl_translan();
    switch ($this->iCodDoc){

      //lancamento de Empenho
      case 1 :
      case 304: // EMPENHO DA PROVISAO DE FERIAS
      case 308: // EMPENHO DA PROVISAO DE 13º SALARIO
      case 410: // EMPENHO SUPRIMENTO DE FUNDOS
      case 500: // EMPENHO DE PRECATORIOS
      case 504: // EMPENHO AMORT. DA DIVIDA

        //verificamos se o usuario setou  o codigo da conta
        if ($this->iCodCom == null){
          throw new exception("Não foi informado código da conta do lançamento.");
        }
        $oTransLan->db_trans_empenho($this->iCodCom, $this->iAnoUsu, $this->iCodDoc);
        $this->oTransaction = $oTransLan;
        break;

      //liquidacao de empenho
      case 3  :
      case 84 : // Liquidacao de Empenho Passivo
      case 306: // Liquidacao de Empenho provisao de ferias
      case 202: // LIQUIDAÇÃO DESPESA COM SERVIÇOS
      case 204: // LIQUIDAÇÃO DESPESA MATERIAL DE CONSUMO
      case 206: // LIQUIDAÇÃO AQUISIÇÃO MATERIAL PERMANENTE
      case 310: // LIQUIDACAO DA PROVISAO DE 13º SALARIO
      case 502: // LIQUIDAÇÃO DE PRECATÓRIOS
      case 506: // LIQUIDACAO AMORT. DIVIDA

        $iCodigoDocumento = $this->iCodDoc;
        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da conta;
         */

        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }
        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        $oTransLan->db_trans_liquida($this->iCodCom,
                                     $this->aLancamentos["lancamEle"]["c67_codele"],
                                     $this->iAnoUsu,
                                     $iCodigoDocumento);
        $this->oTransaction = $oTransLan;
        break;

      //restos a pagar.;
      case 4  :
      case 85 : // Estorno de Liquidacao Passivo
      case 307: // ESTORNO DA LIQUIDACAO DA PROVISAO DE FERIAS
      case 311: // ESTORNO DA LIQUIDACAO DA PROVISAO DE 13º SALARIO
      case 203: // ESTORNO DE LIQUIDAÇÃO DESPESA COM SERVIÇOS
      case 205: // ESTORNO DE LIQ. DESPESA MATERIAL DE CONSUM
      case 207: // ESTORNO DE LIQ. AQ. MATERIAL PERMANENTE
      case 507: // ESTORNO LIQUIDACAO AMORT. DIVIDA
      case 503: // ESTORNO DA LIQUIDACAO DE PRECATORIOS
        $iCodigoDocumento = $this->iCodDoc;
        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da contai, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        $oTransLan->db_trans_estorna_liquida($this->iCodCom,
                                             $this->aLancamentos["lancamEle"]["c67_codele"],
                                             $this->iAnoUsu,
                                             $iCodigoDocumento);
        $this->oTransaction = $oTransLan;
        break;

      //estorno de Liquidacao capital
      case 23 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da conta;
         */

        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }
        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        $oTransLan->db_trans_liquida_capital(
          $this->iCodCom,
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->iAnoUsu
        );
        $this->oTransaction = $oTransLan;
        break;
      case 24 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da contai, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        $oTransLan->db_trans_estorna_liquida_capital(
          $this->iCodCom,
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->iAnoUsu
        );
        $this->oTransaction = $oTransLan;
        break;

      //liquidacao de restos a pagar
      case 33 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da contai, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_liquida_resto($this->iCodCom,
                                           $this->aLancamentos["lancamEle"]["c67_codele"],
                                           $this->iAnoEmpenho,
                                           $this->aLancamentos["lancamEmp"]["c75_numemp"]
        );
        $this->oTransaction = $oTransLan;
        break;

      //estorno de liquidacao de empenho RP
      case 34 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da contai, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_estorna_liquida_resto(
          $this->iCodCom,
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->iAnoEmpenho,
          $this->aLancamentos["lancamEmp"]["c75_numemp"]
        );
        $this->oTransaction = $oTransLan;
        break;
      //estorno de restos a pagar processados
      case 31 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da conta, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_rp(31, $this->aLancamentos["lancamEmp"]["c75_numemp"]);
        if ($oTransLan->sqlerro){
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;
        break;
      //estorno de restos a pagar não processados

      case 32 :

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da conta, e o ano do Empenho;
         */
        $this->lInverterLancamento = false;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_rp(32, $this->aLancamentos["lancamEmp"]["c75_numemp"]);
        if ($oTransLan->sqlerro) {
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;
        break;

      case 5: //pagamento de Empenho

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o Reduzido do lançamento.");
        }

        if ($this->aLancamentos["lancamDot"]["set"] != 1) {
          throw new exception("Não foi informado a dotação do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        $oTransLan->db_trans_pagamento(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoUsu,
          $this->aLancamentos["lancamEmp"]["c75_numemp"]
        );
        $this->oTransaction = $oTransLan;
        $this->iContaEmp   = $oTransLan->conta_emp;
        break;

      case 35 : //pagamento de RPS

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o reduzido do lançamento.");
        }

        $oTransLan->db_trans_pagamento_resto(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoEmpenho,
          $this->aLancamentos["lancamEmp"]["c75_numemp"],
          $this->iCodDoc
        );
        $this->iContaEmp = $oTransLan->conta_emp;
        $this->oTransaction = $oTransLan;
        break;

      case 37 : //pagamento de RPS

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o reduzido do lançamento.");
        }

        $oTransLan->db_trans_pagamento_resto(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoEmpenho,
          $this->aLancamentos["lancamEmp"]["c75_numemp"],
          $this->iCodDoc
        );
        $this->iContaEmp = $oTransLan->conta_emp;
        $this->oTransaction = $oTransLan;
        break;

      case 6 : // estorno de pagamento de empenho (exercicio corrente);

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o Reduzido do lançamento.");
        }

        if ($this->aLancamentos["lancamDot"]["set"] != 1) {
          throw new exception("Não foi informado a dotação do lançamento.");
        }
        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        $oTransLan->db_trans_estorna_pagamento(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoUsu,
          $this->aLancamentos["lancamEmp"]["c75_numemp"]
        );

        if (count($oTransLan->arr_debito) == 0 || count($oTransLan->arr_credito) == 0) {
          throw new exception("Verifique o cadastro do Documento {$this->iCodDoc}.\nEncontrado Inconsistências");
        }
        $this->oTransaction = $oTransLan;
        $this->iContaEmp    = $oTransLan->conta_emp;
        break;

      case 91: // ESTORNO SUPRIMENTO DE FUNDOS
      case 92: // DEVOLUCAO DE ADIANTAMENTO

        $oTransLan = new cl_translan();
        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o Reduzido do lançamento.");
        }

        if ($this->aLancamentos["lancamDot"]["set"] != 1) {
          throw new exception("Não foi informado a dotação do lançamento.");
        }
        $oTransLan->db_trans_estorna_pagamento_prestacao_contas(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoUsu,
          $this->iCodDoc,
          $this->aLancamentos["lancamEmp"]["c75_numemp"]
        );

        if (count($oTransLan->arr_debito) == 0 || count($oTransLan->arr_credito) == 0) {
          throw new exception("Verifique o cadastro do Documento {$this->iCodDoc}.\nEncontrado Inconsistências");
        }

        $this->oTransaction = $oTransLan;
        $this->iContaEmp    = $oTransLan->conta_emp;
        break;

      case 36 :

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o reduzido do lançamento.");
        }

        $oTransLan->db_trans_estorna_pagamento_resto(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoEmpenho,
          $this->aLancamentos["lancamEmp"]["c75_numemp"],
          $this->iCodDoc
        );
        $this->iContaEmp    = $oTransLan->conta_emp;
        $this->oTransaction = $oTransLan;
        break;

      case 38:

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o reduzido do lançamento.");
        }

        $oTransLan->db_trans_estorna_pagamento_resto(
          $this->aLancamentos["lancamEle"]["c67_codele"],
          $this->aLancamentos["lancamPag"]["c82_reduz"],
          $this->iAnoEmpenho,
          $this->aLancamentos["lancamEmp"]["c75_numemp"],
          $this->iCodDoc
        );
        $this->iContaEmp    = $oTransLan->conta_emp;
        $this->oTransaction = $oTransLan;
        break;

      case 2 :  // ESTORNO DE EMPENHO
      case 83:  // estorno de empenho passivo
      case 305: // ESTORNO DE EMPENHO DA PROVISAO DE FERIAS
      case 309: // ESTORNO DE EMPENHO DA PROVISAO DE 13º SALÁRIO
      case 411: // ESTORNO DE EMPENHO SUPRIMENTO DE FUNDOS
      case 501: // ESTORNO DE EMPENHO DE PRECATORIOS
      case 505: // ESTORNO EMPENHO AMORT. DIVIDA

        $iCodigoDocumento = $this->iCodDoc;
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_estorna_empenho($this->iCodCom, $this->iAnoEmpenho, $iCodigoDocumento);
        if ($oTransLan->sqlerro) {
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;

        break;
      case 900:

        $iCodigoDocumento = $this->iCodDoc;
        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        break;
      case 903:

        $iCodigoDocumento = $this->iCodDoc;
        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        if ($this->aLancamentos["lancamCgm"]["set"] != 1) {
          throw new exception("Não foi informado o credor do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }
        $this->aLancamentos["lancamEle"]["set"] = 0;
        $oTransLan->db_trans_empenho_contrato($this->aLancamentos["lancamEmp"]["c75_numemp"],
                                              $this->iAnoEmpenho,
                                              903
        );
        if ($oTransLan->sqlerro) {
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;
        break;

      case 901:
      case 904:

        $iCodigoDocumento = $this->iCodDoc;
        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        if ($this->aLancamentos["lancamCgm"]["set"] != 1) {
          throw new exception("Não foi informado o credor do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }
        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }
        $this->aLancamentos["lancamEle"]["set"] = 0;
        $oTransLan->db_trans_liquidacao_contrato($this->aLancamentos["lancamEmp"]["c75_numemp"],
                                                 $this->iAnoEmpenho,
                                                 $this->iCodDoc
        );
        if ($oTransLan->sqlerro) {
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;
        break;
      case 412: // LIQUIDACAO SUPRIMENTO DE FUNDOS

        /*
         * Devemos verificar ser o usuario setou o elemento, e o codigo da conta;
         */
        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }
        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        $oTransLan->db_trans_liquida($this->iCodCom,
                                     $this->aLancamentos["lancamEle"]["c67_codele"],
                                     $this->iAnoUsu,
                                     $this->iCodDoc);
        $this->oTransaction = $oTransLan;
        break;

      case 413: // ESTORNO DE LIQUIDAÇÃO SUPRIMENTO DE FUNDOS

        if ($this->iCodCom == null) {
          throw new exception("Não foi informado o código da conta do lançamento.");
        }

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamEmp"]["set"] != 1) {
          throw new exception("Não foi informado o empenho do lançamento.");
        }

        if ($this->iAnoEmpenho == null){
          throw new exception("Não foi informado o ano do empenho.");
        }

        $oTransLan->db_trans_estorna_empenho($this->iCodCom, $this->iAnoEmpenho, $this->iCodDoc, $this->aLancamentos["lancamEmp"]["c75_numemp"]);
        if ($oTransLan->sqlerro) {
          throw new exception($oTransLan->erro_msg);
        }
        $this->oTransaction = $oTransLan;
        break;
      case 90: // SUPRIMENTO DE FUNDOS

        if ($this->aLancamentos["lancamEle"]["set"] != 1) {
          throw new exception("Não foi informado o elemento do lançamento.");
        }

        if ($this->aLancamentos["lancamPag"]["set"] != 1) {
          throw new exception("Não foi informado o Reduzido do lançamento.");
        }

        if ($this->aLancamentos["lancamDot"]["set"] != 1) {
          throw new exception("Não foi informado a dotação do lançamento.");
        }
        $oTransLan->db_trans_pagamento_prestacao_contas($this->aLancamentos["lancamEle"]["c67_codele"],
                                                        $this->aLancamentos["lancamPag"]["c82_reduz"],
                                                        $this->iAnoUsu,
                                                        $this->aLancamentos["lancamEmp"]["c75_numemp"]);
        $this->oTransaction = $oTransLan;
        $this->iContaEmp = $oTransLan->conta_emp;
        break;

      default:
        throw new exception(" Documento {$this->iCodDoc} inválido!");
        break;
    }


    return $this->oTransaction;
  }

  public static function getInfoLancamento($iCodigoLancamento, $lMostraContas=true) {

    $sSqlDadosLancamento  = "select c70_codlan  as codigo, ";
    $sSqlDadosLancamento .= "       c70_data    as data, ";
    $sSqlDadosLancamento .= "       c70_valor   as valor, ";
    $sSqlDadosLancamento .= "       c71_coddoc  as documento,";
    $sSqlDadosLancamento .= "       c53_descr   as descricaoevento,";
    $sSqlDadosLancamento .= "       c80_codord  as ordempagamento,";
    $sSqlDadosLancamento .= "       c75_numemp  as empenho, ";
    $sSqlDadosLancamento .= "       c76_numcgm  as cgm, ";
    $sSqlDadosLancamento .= "       z01_nome    as nome,  ";
    $sSqlDadosLancamento .= "       e69_numero  as notafiscal, ";
    $sSqlDadosLancamento .= "       e69_codnota as codigonotafiscal, ";
    $sSqlDadosLancamento .= "       c72_complem as complemento, ";
    $sSqlDadosLancamento .= "       c73_coddot  as dotacao,";
    $sSqlDadosLancamento .= "       c74_codrec  as receita,";
    $sSqlDadosLancamento .= "       c70_anousu  as anolancamento,";
    $sSqlDadosLancamento .= "       c53_tipo    as tipoevento,";
    $sSqlDadosLancamento .= "       c67_codele  as codigoelemento";
    $sSqlDadosLancamento .= "  from conlancam  ";
    $sSqlDadosLancamento .= "       inner join conlancamdoc on c71_codlan = c70_codlan ";
    $sSqlDadosLancamento .= "       inner join conhistdoc   on c71_coddoc = c53_coddoc ";
    $sSqlDadosLancamento .= "       left  join conlancamord on c70_codlan = c80_codlan ";
    $sSqlDadosLancamento .= "       left join conlancamemp on c75_codlan  = c70_codlan ";
    $sSqlDadosLancamento .= "       left join conlancamcgm on c70_codlan  = c76_codlan ";
    $sSqlDadosLancamento .= "       left join cgm on z01_numcgm = c76_numcgm ";
    $sSqlDadosLancamento .= "       left join conlancamnota on c70_codlan  =  c66_codlan ";
    $sSqlDadosLancamento .= "       left join empnota on c66_codnota       = e69_codnota ";
    $sSqlDadosLancamento .= "       left join conlancamcompl on c70_codlan = c72_codlan ";
    $sSqlDadosLancamento .= "       left join conlancamdot on c73_codlan   = c70_codlan ";
    $sSqlDadosLancamento .= "                             and c73_anousu   = c70_anousu ";
    $sSqlDadosLancamento .= "       left join conlancamele on c67_codlan   = c70_codlan ";
    $sSqlDadosLancamento .= "       left join conlancamrec on c74_codlan   = c70_codlan ";
    $sSqlDadosLancamento .= "                             and c74_anousu   = c70_anousu ";
    $sSqlDadosLancamento .= " where c70_codlan = {$iCodigoLancamento}";
    $rsDadosLancamento    = db_query($sSqlDadosLancamento);
    if (pg_num_rows($rsDadosLancamento) == 0) {
      throw new Exception("Lançamento {$iCodigoLancamento} não existe.");
    }
    $oDadosLancamento     = db_utils::fieldsMemory($rsDadosLancamento, 0, false, false, true);
    $oDadosLancamento->contas = array();
    if ($lMostraContas) {

      $sSqlContas = "select  c69_debito as contadebito, ";
      $sSqlContas .= "      c1.c60_descr as descricaodebito, ";
      $sSqlContas .= "      c1.c60_estrut as estruturaldebito, ";
      $sSqlContas .= "      c69_credito as contacredito, ";
      $sSqlContas .= "      c2.c60_descr as descricaocredito, ";
      $sSqlContas .= "      c2.c60_estrut as estruturalcredito, ";
      $sSqlContas .= "      c69_valor    as valor";
      $sSqlContas .= " from conlancam  ";
      $sSqlContas .= "      inner join conlancamval       on c70_codlan      = c69_codlan ";
      $sSqlContas .= "      inner join conplanoreduz red1 on red1.c61_reduz  = conlancamval.c69_debito  ";
      $sSqlContas .= "                                   and red1.c61_anousu = conlancamval.c69_anousu ";
      $sSqlContas .= "      inner join conplano c1        on c1.c60_codcon   = red1.c61_codcon ";
      $sSqlContas .= "                                   and c1.c60_anousu   = red1.c61_anousu ";
      $sSqlContas .= "      inner join conplanoreduz red2 on red2.c61_reduz  = conlancamval.c69_credito ";
      $sSqlContas .= "                                   and red2.c61_anousu = conlancamval.c69_anousu ";
      $sSqlContas .= "      inner join conplano c2        on c2.c60_codcon   = red2.c61_codcon ";
      $sSqlContas .= "                                   and c2.c60_anousu   = red2.c61_anousu ";
      $sSqlContas .= " where c70_codlan={$iCodigoLancamento}";
      $sSqlContas .= " order by c69_sequen ";

      $rsContas    = db_query($sSqlContas);
      $oDadosLancamento->contas = db_utils::getCollectionByRecord($rsContas,false,false,true);

    }
    return $oDadosLancamento;
  }


  public static function alterarDataLancamento($iCodigoLancamento, $dtData) {

    if (empty($iCodigoLancamento)) {
      throw new Exception("Informe o codigo do lançamento");
    }

    /**
     * alteramos a data da conlancam
     */
    $oConlancam = db_utils::getDao("conlancam");
    $oConlancam->c70_codlan = $iCodigoLancamento;
    $oConlancam->c70_data   = $dtData;
    $oConlancam->alterar($iCodigoLancamento);
    if ($oConlancam->erro_status == 0) {
      throw new Exception("Erro[1] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamval
     */
    $oDaoConlancamVal = db_utils::getDao("conlancamval");
    $sSqlLancamentos  = $oDaoConlancamVal->sql_query_file(null,"*", null, "c69_codlan = {$iCodigoLancamento}");
    $rsLancamentos    = $oDaoConlancamVal->sql_record($sSqlLancamentos);
    if ($oDaoConlancamVal->numrows > 0) {

      $aLancamentos = db_utils::getCollectionByRecord($rsLancamentos);
      foreach ($aLancamentos as $oLancamento) {

        /**
         * Tratamento para os dados da ContaCorrente
         */
        $oDaoContaCorrenteDetalheValor = db_utils::getDao("contacorrentedetalheconlancamval");
        $sWhereExcluirContaCorrenteDetalhe = "c28_conlancamval = {$oLancamento->c69_sequen}";
        $sSqlBuscaContaCorrente = $oDaoContaCorrenteDetalheValor->sql_query_file(null, "*", null, $sWhereExcluirContaCorrenteDetalhe);
        $rsBuscaContaCorrente   = db_query($sSqlBuscaContaCorrente);
        if ( !$rsBuscaContaCorrente) {
          throw new Exception("Não foi possível buscar os dados da conta corrente.");
        }
        $aDadosContaCorrente = array();
        if (pg_num_rows($rsBuscaContaCorrente) > 0) {
          $aDadosContaCorrente = db_utils::getCollectionByRecord($rsBuscaContaCorrente);
        }
        $oDaoContaCorrenteDetalheValor->excluir(null, $sWhereExcluirContaCorrenteDetalhe);
        if ($oDaoContaCorrenteDetalheValor->erro_status == 0) {
          throw new Exception("Não foi possível excluir os dados da conta corrente.");
        }


        $oDaoConlancamlr      = db_utils::getDao("conlancamlr");//db_utils::getDao("conlancamlr");
        $sSqlLancamentoConfig = $oDaoConlancamlr->sql_query_file($oLancamento->c69_sequen);
        $rsLancamentosConfig  = $oDaoConlancamlr->sql_record($sSqlLancamentoConfig);
        $aLancamentosConfig   = db_utils::getCollectionByRecord($rsLancamentosConfig);
        $oDaoConlancamlr->excluir($oLancamento->c69_sequen);
        if ($oDaoConlancamlr->erro_status == 0) {
          throw new Exception("Erro[2] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.");
        }

        $oDaoConlancamVal->excluir($oLancamento->c69_sequen);
        if ($oDaoConlancamVal->erro_status == 0) {

          $sErroMensagem  = "Erro[3] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.\n";
          $sErroMensagem .= $oDaoConlancamVal->erro_banco;
          throw new Exception($sErroMensagem);
        }

        /**
         * Incluimos a conlancam a data alterada
         */
        $oDaoConlancamVal->c69_anousu  = $oLancamento->c69_anousu;
        $oDaoConlancamVal->c69_codhist = $oLancamento->c69_codhist;
        $oDaoConlancamVal->c69_codlan  = $oLancamento->c69_codlan;
        $oDaoConlancamVal->c69_credito = $oLancamento->c69_credito;
        $oDaoConlancamVal->c69_data    = $dtData;
        $oDaoConlancamVal->c69_debito  = $oLancamento->c69_debito;
        $oDaoConlancamVal->c69_valor   = $oLancamento->c69_valor;
        $oDaoConlancamVal->incluir(null);
        if ($oDaoConlancamVal->erro_status == 0) {

          $sErroMsg  = "Erro[4] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.";
          $sErroMsg .= "\nErro Técnico:{$oDaoConlancamVal->erro_msg}";
          throw new Exception($sErroMsg);
        }

        /**
         * Configuramos a conta corrente novamente.
         */
        foreach ($aDadosContaCorrente as $oStdContaCorrente) {

          $oDaoContaCorrenteDetalheValor = db_utils::getDao("contacorrentedetalheconlancamval");
          $oDaoContaCorrenteDetalheValor->c28_sequencial           = null;
          $oDaoContaCorrenteDetalheValor->c28_contacorrentedetalhe = $oStdContaCorrente->c28_contacorrentedetalhe;
          $oDaoContaCorrenteDetalheValor->c28_conlancamval         = $oDaoConlancamVal->c69_sequen;
          $oDaoContaCorrenteDetalheValor->c28_tipo                 = $oStdContaCorrente->c28_tipo;
          $oDaoContaCorrenteDetalheValor->incluir(null);
          if ($oDaoContaCorrenteDetalheValor->erro_status == 0) {
            throw new Exception("Não foi possível inserir os dados para a conta corrente {$oStdContaCorrente->c28_contacorrentedetalhe}. Contate o Suporte.");
          }
          unset($oDaoContaCorrenteDetalheValor);
        }


        /**
         * incluimos na conlancamlr
         */
        foreach ($aLancamentosConfig as $oLancamentoConfig) {

          $oDaoConlancamlr->c81_sequen     = $oDaoConlancamVal->c69_sequen;
          $oDaoConlancamlr->c81_seqtranslr = $oLancamentoConfig->c81_seqtranslr;
          $oDaoConlancamlr->incluir($oDaoConlancamVal->c69_sequen, $oLancamentoConfig->c81_seqtranslr);
          if ($oDaoConlancamVal->erro_status == 0) {
            throw new Exception("Erro[5] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.");
          }
        }
      }
    }

    /**
     * Alteramos a tabela conlancamcgm
     */
    $oConlancamcgm = db_utils::getDao("conlancamcgm");
    $oConlancamcgm->c76_codlan = $iCodigoLancamento;
    $oConlancamcgm->c76_data   = $dtData;
    $oConlancamcgm->alterar($iCodigoLancamento);
    if ($oConlancamcgm->erro_status == 0) {
      throw new Exception("Erro[6] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamdig
     */
    $oConlancamdig = db_utils::getDao("conlancamdig");
    $oConlancamdig->c78_codlan = $iCodigoLancamento;
    $oConlancamdig->c78_data   = $dtData;
    $oConlancamdig->alterar($iCodigoLancamento);
    if ($oConlancamdig->erro_status == 0) {
      throw new Exception("Erro[7] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamdoc
     */
    $oConlancamdoc = db_utils::getDao("conlancamdoc");
    $oConlancamdoc->c71_codlan = $iCodigoLancamento;
    $oConlancamdoc->c71_data   = $dtData;
    $oConlancamdoc->alterar($iCodigoLancamento);
    if ($oConlancamdoc->erro_status == 0) {
      throw new Exception("Erro[8] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamdot
     */
    $oConlancamdot = db_utils::getDao("conlancamdot");
    $oConlancamdot->c73_codlan = $iCodigoLancamento;
    $oConlancamdot->c73_data   = $dtData;
    $oConlancamdot->alterar($iCodigoLancamento);
    if ($oConlancamdot->erro_status == 0) {
      throw new Exception("Erro[9] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamemp
     */
    $oConlancamemp = db_utils::getDao("conlancamemp");
    $oConlancamemp->c75_codlan = $iCodigoLancamento;
    $oConlancamemp->c75_data   = $dtData;
    $oConlancamemp->alterar($iCodigoLancamento);
    if ($oConlancamemp->erro_status == 0) {
      throw new Exception("Erro[10] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamord
     */
    $oConlancamord = db_utils::getDao("conlancamord");
    $oConlancamord->c80_codlan = $iCodigoLancamento;
    $oConlancamord->c80_data   = $dtData;
    $oConlancamord->alterar($iCodigoLancamento);
    if ($oConlancamord->erro_status == 0) {
      throw new Exception("Erro[11] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamrec
     */
    $oConlancamrec = db_utils::getDao("conlancamrec");
    $oConlancamrec->c74_codlan = $iCodigoLancamento;
    $oConlancamrec->c74_data   = $dtData;
    $oConlancamrec->alterar($iCodigoLancamento);
    if ($oConlancamrec->erro_status == 0) {
      throw new Exception("Erro[12] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamretif
     */
    $oConlancamretif = db_utils::getDao("conlancamretif");
    $oConlancamretif->c79_codlan = $iCodigoLancamento;
    $oConlancamretif->c79_data   = $dtData;
    $oConlancamretif->alterar($iCodigoLancamento);
    if ($oConlancamretif->erro_status == 0) {
      throw new Exception("Erro[13] - Erro ao alterar a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamsup
     */
    $oConlancamsup = db_utils::getDao("conlancamsup");
    $oConlancamsup->c79_codlan = $iCodigoLancamento;
    $oConlancamsup->c79_data   = $dtData;
    $oConlancamsup->alterar($iCodigoLancamento);
    if ($oConlancamsup->erro_status == 0) {
      throw new Exception("Erro[14] - Erro ao alterar a data do lançamento contábil.");
    }
  }

  public static function excluirLancamento($iCodigoLancamento) {

    if (empty($iCodigoLancamento)) {
      throw new Exception("Informe o codigo do lançamento");
    }

    /**
     * excluimos a tabela conlancamsup
     */
    $oConlancamsup = db_utils::getDao("conlancamsup");
    $oConlancamsup->c79_codlan = $iCodigoLancamento;
    $oConlancamsup->excluir($iCodigoLancamento);
    if ($oConlancamsup->erro_status == 0) {
      throw new Exception("Erro[14] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * excluimos a conlancamval
     */
    $oDaoConlancamVal = db_utils::getDao("conlancamval");
    $sSqlLancamentos  = $oDaoConlancamVal->sql_query_file(null,"*", null, "c69_codlan = {$iCodigoLancamento}");
    $rsLancamentos    = $oDaoConlancamVal->sql_record($sSqlLancamentos);
    if ($oDaoConlancamVal->numrows > 0) {

      $aLancamentos = db_utils::getCollectionByRecord($rsLancamentos);
      foreach ($aLancamentos as $oLancamento) {

        $oDaoConlancamlr      = db_utils::getDao("conlancamlr");//db_utils::getDao("conlancamlr");
        $sSqlLancamentoConfig = $oDaoConlancamlr->sql_query_file($oLancamento->c69_sequen);
        $rsLancamentosConfig  = $oDaoConlancamlr->sql_record($sSqlLancamentoConfig);
        $aLancamentosConfig   = db_utils::getCollectionByRecord($rsLancamentosConfig);
        $oDaoConlancamlr->excluir($oLancamento->c69_sequen);
        if ($oDaoConlancamlr->erro_status == 0) {
          throw new Exception("Erro[2] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.");
        }

        if (USE_PCASP) {

          if (!class_exists("ContaCorrenteBase")) {
            require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
          }

          /**
           * Atualizamos o saldo da conta corrente
           */
          ContaCorrenteBase::atualizarSaldoContaCorrenteReprocessamento($oLancamento);

          /**
           * Após atualizar o saldo da conta corrente podemos excluir o vinculo
           * do lançamento contábil com a conta corrente
           */
          $oDaoContaCorrenteDetalheConLancamVal = db_utils::getDao("contacorrentedetalheconlancamval");
          $sWhereExcluirVinculoContaCorrente    = "c28_conlancamval = {$oLancamento->c69_sequen}";
          $oDaoContaCorrenteDetalheConLancamVal->excluir(null, $sWhereExcluirVinculoContaCorrente);
          if ($oDaoContaCorrenteDetalheConLancamVal->erro_status == "0") {

            $sMensagemException  = "Erro[15] - Erro ao excluir vínculo do lançamento contábil com a conta corrente.\n";
            $sMensagemException .= $oDaoContaCorrenteDetalheConLancamVal->erro_banco;
            throw new Exception($sMensagemException);
          }
        }

        $oDaoConlancamVal->excluir($oLancamento->c69_sequen);
        if ($oDaoConlancamVal->erro_status == 0) {

          $sErroMensagem  = "Erro[3] - Erro ao alterar a data do lançamento contábil!\nErro ao reprocessar lançamentos.\n";
          $sErroMensagem .= $oDaoConlancamVal->erro_banco;
          throw new Exception($sErroMensagem);
        }
      }
    }
    /**
     * excluimos a tabela conlancamcgm
     */
    $oConlancamcgm = db_utils::getDao("conlancamcgm");
    $oConlancamcgm->c76_codlan = $iCodigoLancamento;
    $oConlancamcgm->excluir($iCodigoLancamento);
    if ($oConlancamcgm->erro_status == 0) {
      throw new Exception("Erro[6] - Erro ao excluir a data do lançamento contábil.");
    }
    /**
     * excluir a tabela conlancamdoc
     */
    $oConlancamdoc = db_utils::getDao("conlancamdoc");
    $oConlancamdoc->c71_codlan = $iCodigoLancamento;
    $oConlancamdoc->excluir($iCodigoLancamento);
    if ($oConlancamdoc->erro_status == 0) {
      throw new Exception("Erro[8] - Erro ao exlcuir a data do lançamento contábil.");
    }


    /**
     * Alteramos a tabela conlancamdig
     */
    $oConlancamdig = db_utils::getDao("conlancamdig");
    $oConlancamdig->c78_codlan = $iCodigoLancamento;
    $oConlancamdig->excluir($iCodigoLancamento);
    if ($oConlancamdig->erro_status == 0) {
      throw new Exception("Erro[7] - Erro ao excluir a data do lançamento contábil.");
    }


    /**
     * Alteramos a tabela conlancamdot
     */
    $oConlancamdot = db_utils::getDao("conlancamdot");
    $oConlancamdot->c73_codlan = $iCodigoLancamento;
    $oConlancamdot->excluir($iCodigoLancamento);
    if ($oConlancamdot->erro_status == 0) {
      throw new Exception("Erro[9] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamemp
     */
    $oConlancamemp = db_utils::getDao("conlancamemp");
    $oConlancamemp->c75_codlan = $iCodigoLancamento;
    $oConlancamemp->excluir($iCodigoLancamento);
    if ($oConlancamemp->erro_status == 0) {
      throw new Exception("Erro[10] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamemp
     */
    $oConlancampag = db_utils::getDao("conlancampag");
    $oConlancampag->c82_codlan = $iCodigoLancamento;
    $oConlancampag->excluir($iCodigoLancamento);
    if ($oConlancampag->erro_status == 0) {
      throw new Exception("Erro[10] - Erro ao excluir a data do lançamento contábil.");
    }
    /**
     * Alteramos a tabela conlancamord
     */
    $oConlancamord = db_utils::getDao("conlancamord");
    $oConlancamord->c80_codlan = $iCodigoLancamento;
    $oConlancamord->excluir($iCodigoLancamento);
    if ($oConlancamord->erro_status == 0) {
      throw new Exception("Erro[11] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamrec
     */
    $oConlancamrec = db_utils::getDao("conlancamrec");
    $oConlancamrec->c74_codlan = $iCodigoLancamento;
    $oConlancamrec->excluir($iCodigoLancamento);
    if ($oConlancamrec->erro_status == 0) {
      throw new Exception("Erro[12] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Alteramos a tabela conlancamrec
     */
    $oConlancamele = db_utils::getDao("conlancamele");
    $oConlancamele->c74_codlan = $iCodigoLancamento;
    $oConlancamele->excluir($iCodigoLancamento);
    if ($oConlancamrec->erro_status == 0) {
      throw new Exception("Erro[12] - Erro ao excluir a data do lançamento contábil.");
    }
    /**
     * Alteramos a tabela conlancamretif
     */
    $oConlancamretif = db_utils::getDao("conlancamretif");
    $oConlancamretif->c79_codlan = $iCodigoLancamento;
    $oConlancamretif->excluir($iCodigoLancamento);
    if ($oConlancamretif->erro_status == 0) {
      throw new Exception("Erro[13] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * excluimos  a tabela conlancamcompl
     */
    $oConlancamcompl = db_utils::getDao("conlancamcompl");
    $oConlancamcompl->c72_codlan = $iCodigoLancamento;
    $oConlancamcompl->excluir($iCodigoLancamento);
    if ($oConlancamcompl->erro_status == 0) {
      throw new Exception("Erro[13] - Erro ao excluir a data do lançamento contábil.");
    }

    $oConlancamnota = db_utils::getDao("conlancamnota");
    $oConlancamnota->c66_codlan = $iCodigoLancamento;
    $oConlancamnota->excluir($iCodigoLancamento);
    if ($oConlancamcompl->erro_status == 0) {
      throw new Exception("Erro[13] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Exclui a tabela conlancamconcarpeculiar
     */
    $oConlanconcarpeculiar = db_utils::getDao("conlancamconcarpeculiar");
    $oConlanconcarpeculiar->excluir(null, "c08_codlan = {$iCodigoLancamento}");

    if ($oConlanconcarpeculiar->erro_status == 0) {
      throw new Exception("Erro[20] - Erro ao excluir a data do lançamento contábil.");
    }

    /**
     * Exclui a tabela conlancaminstit
     */
    $oConlancaminstit = db_utils::getDao("conlancaminstit");
    $oConlancaminstit->excluir(null, "c02_codlan = {$iCodigoLancamento}");
    if ($oConlancaminstit->erro_status == 0) {
      throw new Exception("Erro[20] - Erro ao excluir a data do lançamento contábil.");
    }

    $oConlancamOrdem = db_utils::getDao("conlancamordem");
    $oConlancamOrdem->excluir(null, "c03_codlan = {$iCodigoLancamento}");
    if ($oConlancamOrdem->erro_status == "0") {
      throw new Exception("Erro[20] - Erro ao excluir a ordem do lançamento contábil.");
    }

    /*
      * alteramos a data da conlancam
      */
    $oConlancam = db_utils::getDao("conlancam");
    $oConlancam->c70_codlan = $iCodigoLancamento;
    $oConlancam->excluir($iCodigoLancamento);
    if ($oConlancam->erro_status == 0) {
      throw new Exception("Erro[1] - Erro ao excluir a data do lançamento contábil.\n{$oConlancam->erro_msg}");
    }
  }

	/**
	 * Exclui os lançamentos (e tesouraria) referente a apropriação de um lançamento de retenção.
	 * @param $iLancamento int Lançamento de retenção.
	 *
	 * @throws DBException
	 */
  public static function excluirLancamentosApropriacao($iLancamento) {

  	$sCamposGrupoCorrente = "k105_corgrupo";
		$sWhereGrupoCorrente  = "c23_conlancam = {$iLancamento} and k105_corgrupotipo in (2, 5)";

  	$sSqlGrupoCorrente = "
			select {$sCamposGrupoCorrente}
			from conlancamcorgrupocorrente
				inner join corgrupocorrente on c23_corgrupocorrente = k105_sequencial
			where {$sWhereGrupoCorrente}";

		$sCamposApropriacao = "c70_codlan as lancamento, k105_data as \"data\", k105_autent as autenticacao, k105_id as autenticadora";
		$sWhereApropriacao  = "k105_corgrupo in ({$sSqlGrupoCorrente}) and k105_corgrupotipo in (3, 6)  and c70_codlan <> {$iLancamento}";

		$sSqlApropriacao = "
				select {$sCamposApropriacao}
				from conlancam
					inner join conlancamcorgrupocorrente on c70_codlan = c23_conlancam
					inner join corgrupocorrente on c23_corgrupocorrente =  k105_sequencial
					inner join corgrupotipo on k105_corgrupotipo = k106_sequencial
				where {$sWhereApropriacao}
      ";

		$rsApropriacao = db_query($sSqlApropriacao);
		if (!$rsApropriacao) {
			throw new DBException("Houve um erro ao verificar os lançamentos de apropriação.");
		}

		$iLinhas = pg_num_rows($rsApropriacao);
		$aTesourariaExcluida = array();
		for ($i = 0; $i < $iLinhas; $i++) {

			$oStd = db_utils::fieldsMemory($rsApropriacao, $i);

			$sCorrente = $oStd->autenticadora.$oStd->data.$oStd->autenticacao;
			if (empty($aTesourariaExcluida) || !in_array($sCorrente, $aTesourariaExcluida)) {

				ManutencaoTesouraria::excluirTesouraria($oStd->autenticadora, $oStd->data, $oStd->autenticacao);
				$aTesourariaExcluida[] = $sCorrente;
			}
			lancamentoContabil::excluirLancamento($oStd->lancamento);
		}
	}

  /**
   * define qual o objeto que possui os lancamentos necessarios
   *
   * @param objeto $oDadosTransacao objeto cl_translan
   */
  public function setDadosTransacao($oDadosTransacao) {
    $this->oTransaction  = $oDadosTransacao;
  }
}