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
 *
 * @author Iuri Guntchnigg
 * @revision $Author: dbmatheus.felini $
 * @version $Revision: 1.25 $
 */

class slip {

  /**
   * Código do Slip
   *
   * @var integer
   */
  protected $iSlip;

  /**
   * Arrecadacoes vinculadas ao slip (quanto receita extra)
   *
   * @var array
   */
  protected $aArrecadacoes = array();

  /**
   * Valor do recurso
   *
   * @var float
   */
  protected $nValor;

  /**
   * Conta debito do slip
   *
   * @var integer
   */
  protected $iContaDebito;

  /**
   * Conta credito do slip
   *
   * @var integer
   */
  protected $iContaCredito;

  /**
   * Observações do Historico
   *
   * @var string
   */
  protected $sObservacoes;

  /**
   * Data de emissão do slip
   *
   * @var string
   */
  protected $dtData;

  /**
   * Situacao do slip 1 - Emitido  2 - Autenticado - 3 - Estornado  4 - Anulado
   *
   * @var integer
   */
  protected $iSituacao;

  /**
   * Tipos de pagamento do slip - 0 Nenhum (Contas Orçamentarias) 1 - saldo Inicial 2- Arrecacadações 3 -> sem pag
   * (extra- orçamentarias)
   *
   * @var unknown_type
   */
  protected $iTipoPagamento;

  /**
   * Autenticacoes Realizadas pelo slip (coleção de objetos)
   *
   * @var array
   */
  protected $aPagamentos;

  /**
   * Lista de Recursos no slip (colecao de objetos com codigo do slip, descricao, valor)
   *
   * @var array
   */
  protected $aRecursos;

  /**
   * Numero do cgm
   *
   * @var integer
   */
  protected $iNumCgm;


  /**
   * Código do slip
   *
   * @var integer
   */
  protected $iHistorico;

  /**
   * Codigo do Movimento da agenda
   *
   * @var integer
   */
  protected $iMovimento;


  /**
   * Motivo do Estorno
   * @var string
   */
  protected $sMotivoEstornoAnulacao;


  /**
   * Data Autenticacao
   * @var date
   */
  protected $dtDataAutenticacao;


  /**
   * Tipo Autenticacao
   * @var integer
   */
  protected $iTipoAutenticacao;

  /**
   * Instituicao que criou o slip
   * @var integer
   */
  protected $iInstituicao;


  /**
   * Codigo da Caracteristica Peculiar Credito
   * @var string
   */
  protected $sCodigoCaracteristicaPeculiarCredito;

  protected $sCodigoCaracteristicaPeculiarDebito;

  /**
   * Método contrutor, carrega os dados de um slip
   * @param integer $iSlip
   * @throws Exception
   * @return slip
   */
  function __construct ($iSlip = null) {

    $this->iSlip = $iSlip;
    if (!empty($iSlip)) {

      /**
       * Caso o slip foi preenchido, pesquisamos seus dados e preenchemos
       * os dados
       */
      $oDaoSlip  = db_utils::getDao("slip");
      $sSqlSlip  = $oDaoSlip->sql_query_file($iSlip);
      $rsSlip    = $oDaoSlip->sql_record($sSqlSlip);
      if ($oDaoSlip->numrows > 0) {

         $oDadosSlip = db_utils::fieldsMemory($rsSlip, 0);
         $this->setTipoPagamento($oDadosSlip->k17_tipopagamento);
         $this->setContaCredito($oDadosSlip->k17_credito);
         $this->setContaDebito($oDadosSlip->k17_debito);
         $this->setData(db_formatar($oDadosSlip->k17_data, "d"));
         $this->setObservacoes($oDadosSlip->k17_texto);
         $this->setHistorico($oDadosSlip->k17_hist);
         $this->setSituacao($oDadosSlip->k17_situacao);
         $this->setValor($oDadosSlip->k17_valor);
         $this->dtDataAutenticacao = $oDadosSlip->k17_dtaut;
         $this->iTipoAutenticacao  = $oDadosSlip->k17_autent;
         $this->iSlip = $iSlip;
         $this->iInstituicao = $oDadosSlip->k17_instit;

         /**
          * Pesquisamos as informacoes do recurso
          */
         $oDaoSlipRecurso = db_utils::getDao('sliprecurso');
         $sSqlRecursos    = $oDaoSlipRecurso->sql_query(null,"*", "k29_recurso","k29_slip = {$this->iSlip}");
         $rsRecursos      = $oDaoSlipRecurso->sql_record($sSqlRecursos);
         $aRecursos       = db_utils::getCollectionByRecord($rsRecursos);
         foreach ($aRecursos as $oRecurso) {
           $this->addRecurso($oRecurso->k29_recurso, $oRecurso->k29_valor);
         }

      } else {
        throw new Exception("Slip {$iSlip} não encontrado!");
      }
      /**
       * Pesquisamos as arrecadacoes vinculadas ao slip
       */
      $sSqlArrecadacoes   = "select cornump.k12_numpre ";
      $sSqlArrecadacoes  .= "  from corrente  ";
      $sSqlArrecadacoes  .= "       inner join cornump on corrente.k12_data   = cornump.k12_data ";
      $sSqlArrecadacoes  .= "                         and corrente.k12_id     = cornump.k12_id ";
      $sSqlArrecadacoes  .= "                         and corrente.k12_autent = cornump.k12_autent ";
      $sSqlArrecadacoes  .= "       inner join reciborecurso on k00_numpre = cornump.k12_numpre  ";
      $sSqlArrecadacoes  .= "       inner join slipcorrente on corrente.k12_data   = k112_data ";
      $sSqlArrecadacoes  .= "                             and corrente.k12_id     = k112_id ";
      $sSqlArrecadacoes  .= "                             and corrente.k12_autent = k112_autent ";
      $sSqlArrecadacoes  .= " where k112_slip = {$this->iSlip} ";
      $sSqlArrecadacoes  .= "   and k112_ativo is true ";
      $rsRegistos         = db_query($sSqlArrecadacoes);
      $aArrecadacoes      = db_utils::getCollectionByRecord($sSqlArrecadacoes);
      foreach ($aArrecadacoes as $oArrecadacao) {
        $this->aArrecadacoes[] = $oArrecadacao->k12_numpre;
      }

      $oDaoSlipCaracteristica      = db_utils::getDao('slipconcarpeculiar');
      $sSqlBuscaCaracteristicaSlip = $oDaoSlipCaracteristica->sql_query_file(null, "*", "k131_tipo", "k131_slip = {$iSlip}");
      $rsBuscaCaracteristica       = $oDaoSlipCaracteristica->sql_record($sSqlBuscaCaracteristicaSlip);
      if ($oDaoSlipCaracteristica->numrows > 0) {

        for ($iRowCP = 0; $iRowCP < $oDaoSlipCaracteristica->numrows; $iRowCP++) {

          $oDadoCP = db_utils::fieldsMemory($rsBuscaCaracteristica, $iRowCP);
          if ($oDadoCP->k131_tipo == 1) {
            $this->setCaracteristicaPeculiarDebito($oDadoCP->k131_concarpeculiar);
          } else {
            $this->setCaracteristicaPeculiarCredito($oDadoCP->k131_concarpeculiar);
          }
          unset($oDadoCP);
        }
      }
      unset($oDaoSlipCaracteristica);

      $oDaoSlipNum  = db_utils::getDao('slipnum');
      $sSqlBuscaCGM = $oDaoSlipNum->sql_query_file($iSlip);
      $rsBuscaCGM   = $oDaoSlipNum->sql_record($sSqlBuscaCGM);
      if ($oDaoSlipNum->numrows > 0) {
        $this->iNumCgm = db_utils::fieldsMemory($rsBuscaCGM, 0)->k17_numcgm;
      }
    }
  }

  /**
   * salva o slip
   */
 public function save() {

    if (trim($this->getContaDebito()) == "") {
      $sMsgErro = "Conta a Debitar(Receber) não Informada";
    }

    if (trim($this->getContaCredito()) == "") {
      $sMsgErro = "Conta a Creditar(Pagar) não Informada";
    }

    if ($this->iSlip != "" ) {

      $numslip = $this->iSlip;
      $clsliprecurso = db_utils::getDao("sliprecurso");
      $clsliprecurso->excluir(null," k29_slip =".$this->iSlip);

      if ($clsliprecurso->erro_status == 0) {

        $sMsgErro = $clsliprecurso->erro_msg;
        throw new Exception($sMsgErro);
      }

      $clslipnum = db_utils::getDao("slipnum");
      $clslipnum->excluir($numslip);

      if ($clslipnum->erro_status == 0) {

        $sMsgErro = $clslipnum->erro_msg;
        throw new Exception($sMsgErro);
      }

      $clempageslip = db_utils::getDao("empageslip");
      $sSqlMov = $clempageslip->sql_query_file(null,$numslip);
      $rsMovSlip = $clempageslip->sql_record($sSqlMov);

      if ($clempageslip->numrows > 0) {

        $oMovimentoSlip = db_utils::fieldsMemory($rsMovSlip, 0);
        $clempageslip->excluir($oMovimentoSlip->e89_codmov);
        if ($clempageslip->erro_status == 0){

          $sMsgErro = $clempageslip->erro_msg;
          throw new Exception($sMsgErro);

        }

        $oDaoEmpPag = db_utils::getDao("empagepag");
        $oDaoEmpPag->excluir($oMovimentoSlip->e89_codmov);
        if ($oDaoEmpPag->erro_status == 0){

          $sMsgErro = $oDaoEmpPag->erro_msg;
          throw new Exception($sMsgErro);

        }
        $oDaoNotasOrdem = db_utils::getDao("empagenotasordem");
        $oDaoNotasOrdem->excluir(null,"e43_empagemov={$oMovimentoSlip->e89_codmov}");
        if ($oDaoNotasOrdem->erro_status == 0){

           $sMsgErro = $oDaoNotasOrdem->erro_msg;
           throw new Exception($sMsgErro);
        }


        /**
         * verificamos na empagemovforma, se possui movimentos atualizado para o slip
         * se houver, o slip deve antes ser reconfigurado na agenda, para NDA, e depois sim ser alterado
         *
         */
        $oDaoEmpAgeMovForma = db_utils::getDao("empagemovforma");
        $sSqlEmpAgeMovForma = $oDaoEmpAgeMovForma->sql_query_file($oMovimentoSlip->e89_codmov);
        $oDaoEmpAgeMovForma->sql_record($sSqlEmpAgeMovForma);

        if ( $oDaoEmpAgeMovForma->numrows > 0 ) {

          $sMensagemErroSlip  = "O Slip possui movimentação atualizada na agenda de pagamentos.";
          $sMensagemErroSlip .= "\nDeve ser alterado a forma de pagamento para NDA, para alteração do Slip";
          throw new Exception($sMensagemErroSlip);
        }

        $oDaoEmpAgeConfGera = new cl_empageconfgera();
        $oDaoEmpAgeConfGera->excluir(null,null, "e90_codmov = {$oMovimentoSlip->e89_codmov} ");
        if ($oDaoEmpAgeConfGera->erro_status == '0') {
          throw new Exception("ERRO[1] - ao desvincular cheques do movimento\n".$oDaoEmpAgeConfGera->erro_msg);
        }
        /**
           exclui os cheques que foram emitidos para o movimento
         */
        $oDaoEmpAgeConfChe = new cl_empageconfche();
        $oDaoEmpAgeConfChe->excluir(null, "e91_codmov = {$oMovimentoSlip->e89_codmov}");
        if ($oDaoEmpAgeConfChe->erro_status == '0') {
          throw new Exception("ERRO[2] - ao desvincular cheques do movimento\n".$oDaoEmpAgeConfChe->erro_msg);
        }

        $oDaoEmpageMov = db_utils::getDao("empagemov");
        $oDaoEmpageMov->excluir($oMovimentoSlip->e89_codmov);
        if ($oDaoEmpageMov->erro_status == 0){

          $sMsgErro = $oDaoEmpageMov->erro_msg;
          throw new Exception($sMsgErro);

        }
      }

//      $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
//      $oDaoSlipCorrente->excluir(null,"k112_slip = ".$this->iSlip);
//      if ($oDaoSlipCorrente->erro_status == 0) {
//
//        $sMsgErro = $oDaoSlipCorrente->erro_msg;
//        throw new Exception($sMsgErro);
//
//      }
    }

    $clslip                    = new cl_slip();
    $clslip->k17_data          = date("Y-m-d",db_getsession("DB_datausu"));
    $clslip->k17_debito        = $this->getContaDebito();
    $clslip->k17_credito       = "".$this->getContaCredito()."";
    $clslip->k17_valor         = "".$this->getValor()."";
    $clslip->k17_hist          = $this->getHistorico();
    $clslip->k17_texto         = $this->getObservacoes();
    $clslip->k17_instit        = db_getsession("DB_instit");
    $clslip->k17_dtanu         = "";
    $clslip->k17_tipopagamento = "".$this->getTipoPagamento()."";
    $clslip->k17_situacao      = $this->getSituacao();

    if ($this->iSlip == null) {
      $clslip->incluir(null);
    } else {

      $clslip->k17_codigo    = $this->iSlip;
      $clslip->alterar($this->iSlip);

    }
    $this->iSlip = $clslip->k17_codigo;
    if ($clslip->erro_status == 0) {

      $sMsgErro = $clslip->erro_msg;
      throw new Exception($sMsgErro);
    }

    /**
     * Inserimos a caracteristica peculiar para a conta debito, caso esta esteja setada
     */
    if ($this->getCaracteristicaPeculiarDebito() != "") {

      $oDaoConCarPeculiarDebito = db_utils::getDao('slipconcarpeculiar');
      $sWhereDebito = "k131_slip = {$this->iSlip} and k131_tipo = 1";
      $oDaoConCarPeculiarDebito->excluir(null, $sWhereDebito);


      $oDaoConCarPeculiarDebito->k131_sequencial     = null;
      $oDaoConCarPeculiarDebito->k131_slip           = $this->iSlip;
      $oDaoConCarPeculiarDebito->k131_tipo           = 1;
      $oDaoConCarPeculiarDebito->k131_concarpeculiar = $this->getCaracteristicaPeculiarDebito();
      $oDaoConCarPeculiarDebito->incluir(null);
      if ($oDaoConCarPeculiarDebito->erro_status == "0") {
        throw new Exception("Não foi possível incluir a característica peculiar para a conta débito");
      }
    }

    /**
     * Inserimos a caracteristica peculiar para a conta credito, caso esta esteja setada
     */
    if ($this->getCaracteristicaPeculiarCredito() != "") {

      $oDaoConCarPeculiarCredito = db_utils::getDao('slipconcarpeculiar');
      $sWhereCredito = "k131_slip = {$this->iSlip} and k131_tipo = 2";
      $oDaoConCarPeculiarCredito->excluir(null, $sWhereCredito);

      $oDaoConCarPeculiarCredito->k131_sequencial     = null;
      $oDaoConCarPeculiarCredito->k131_slip           = $this->iSlip;
      $oDaoConCarPeculiarCredito->k131_tipo           = 2;
      $oDaoConCarPeculiarCredito->k131_concarpeculiar = $this->getCaracteristicaPeculiarCredito();
      $oDaoConCarPeculiarCredito->incluir(null);
      if ($oDaoConCarPeculiarCredito->erro_status == "0") {
        throw new Exception("Não foi possível incluir a característica peculiar para a conta débito");
      }
    }


    /**
     * Agendamos o slip caso o parametro emparametro.e30_agendaautomatico = true.
     */
   require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
    $oInstit = db_stdClass::getDadosInstit();
    if ($this->getNumCgm() == "") {
      $this->setNumCgm($oInstit->numcgm);
    }

    $oAgendaPagamento = new agendaPagamento();
    $oSlipAgenda = new stdClass();
    $oSlipAgenda->iCodigoSlip = $clslip->k17_codigo;
    $oSlipAgenda->nValor      = "".$this->getValor()."";

    /**
      * Procuramos se a conta credito do slip é uma conta pagadora no caixa.
      * caso for. setamos essa conta como conta pagadora na agenda.
      */
    $oParametroAgenda = (db_stdClass::getParametro("empparametro",array(db_getsession('DB_anousu')),"e30_agendaautomatico"));
    if ($oParametroAgenda[0]->e30_agendaautomatico == "t" ) {

      if ($this->getContaCredito() != 0 ) {

        $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
        $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,
                                                          "e83_codtipo",
                                                           null,
                                                          "e83_conta = ".$this->getContaCredito());
        $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
        if ($oDaoEmpAgeTipo->numrows > 0 ) {
          $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
        }
      }
      $this->iMovimento =  $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);

    }

    if ($this->getNumCgm() != "") {

      $clslipnum = db_utils::getDao("slipnum");
      $clslipnum->k17_numcgm = $this->getNumCgm();
      $clslipnum->incluir($this->getSlip());
      if ($clslipnum->erro_status == 0) {

        $sMsgErro = $clslipnum->erro_msg;
        throw new Exception($sMsgErro);

      }
    }

    if (isset($this->aRecursos) && count($this->getRecursos()) > 0) {

      $clsliprecurso = db_utils::getDao("sliprecurso");
      /**
       * Incluimos os recursos
       */
      foreach ($this->getRecursos() as $iRecurso => $nValor) {

        $clsliprecurso->k29_slip     = $this->iSlip;
        $clsliprecurso->k29_recurso  = $iRecurso;
        $clsliprecurso->k29_valor    = "$nValor";
        $clsliprecurso->incluir(null);
        if ($clsliprecurso->erro_status == 0) {

          $sMsgErro = $clsliprecurso->erro_msg;
          throw new Exception($sMsgErro);
          break;

        }
      }
    }
    /**
     * vinculamos as arrecacoes com o slip
     */

    if ($this->getTipoPagamento() == 2) {

      foreach ($this->aArrecadacoes as $iArrecadacao) {

        $sSqlCornump       = "select cornump.k12_data,cornump.k12_id,cornump.k12_autent";
        $sSqlCornump      .= "  from cornump ";
        $sSqlCornump      .= "  inner join corrente on cornump.k12_data   = corrente.k12_data ";
        $sSqlCornump      .= "                     and cornump.k12_autent = corrente.k12_autent ";
        $sSqlCornump      .= "                     and cornump.k12_id     = corrente.k12_id ";
        $sSqlCornump      .= " where k12_numpre = {$iArrecadacao}";
        $sSqlCornump      .= "   and k12_estorn is false ";
        $sSqlCornump      .= "  order by corrente.k12_data desc, corrente.k12_id desc limit 1";
        $rsCorrente       = db_query($sSqlCornump);
        if (pg_num_rows($rsCorrente) == 1) {

          $oCorrente        = db_utils::fieldsMemory($rsCorrente, 0);
          $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
          $oDaoSlipCorrente->k112_ativo  = "true";
          $oDaoSlipCorrente->k112_data   = $oCorrente->k12_data;
          $oDaoSlipCorrente->k112_id     = $oCorrente->k12_id;
          $oDaoSlipCorrente->k112_autent = $oCorrente->k12_autent;
          $oDaoSlipCorrente->k112_slip   = $this->iSlip;
          $oDaoSlipCorrente->incluir(null);
          if ($oDaoSlipCorrente->erro_status == 0) {

            $sMsgErro  = "Não foi possível vincular slip com a arrecadação {$iArrecadacao}.\n";
            $sMsgErro .= "{$oDaoSlipCorrente->erro_msg}";
            throw new Exception($sMsgErro);

          }
        } else {
          $sMsgErro   = "Foi encontrado mais de uma autentição para a arrecadação {$iArrecadacao}.\n";
          $sMsgErro  .= "Processamento cancelado.\nVerifique Suporte.";
          throw new Exception($sMsgErro);
        }
      }
    }
    return true;
  }

  /**
   * Método privado que estorna um slip. Este método é chamado exclusivamente pelo método anular
   * @throws Exception
   * @return boolean true
   */
  public function estornar($lExcluirCheque = true, Transferencia $oTransferencia = null) {

    $iInstituicaoSessao = db_getsession("DB_instit");
    $dtSessao           = date("Y-m-d", db_getsession("DB_datausu"));
    $sIPSessao          = db_getsession("DB_ip");

    $oDaocfautent      = db_utils::getDao('cfautent');
    $sSqlAutenticadora  = $oDaocfautent->sql_query_file(null,
                                                       "k11_id,
                                                        k11_tipautent",
                                                        '',
                                                        "k11_ipterm = '{$sIPSessao}'
                                                        and k11_instit = {$iInstituicaoSessao}"
                                                      );
    $rsAutenticador    = $oDaocfautent->sql_record($sSqlAutenticadora);

    if ($oDaocfautent->numrows == '0') {
      throw new Exception("Cadastre o ip {$sIPSessao} como um caixa.");
    }
    /*
     * Verificamos existencia do slip na agenda de pagamento
     */
    $iCodigoMovimentoConfAgenda = "0";
    $iCodigoMovimentoAgenda     = "0";
    $sCamposBuscaSlip    = "e86_cheque, e91_codcheque as e86_codmov, e81_codmov";
    $sWhereBuscaSlip     = "e80_instit = {$iInstituicaoSessao} and empageslip.e89_codigo = {$this->getSlip()}";
    $oDaoEmpAgeSlip      = db_utils::getDao('empageslip');
    $sSqlBuscaSlipAgenda = $oDaoEmpAgeSlip->sql_query_configura(null,null, $sCamposBuscaSlip, null, $sWhereBuscaSlip);
   // echo $sSqlBuscaSlipAgenda."\n";

    $rsBuscaSlipAgenda   = $oDaoEmpAgeSlip->sql_record($sSqlBuscaSlipAgenda);
    if ($oDaoEmpAgeSlip->numrows > 0) {

      $oDadoAgenda = db_utils::fieldsMemory($rsBuscaSlipAgenda, 0);
      $iCodigoMovimentoConfAgenda = $oDadoAgenda->e86_codmov != "" ? $iCodigoMovimentoConfAgenda = $oDadoAgenda->e86_codmov : $iCodigoMovimentoConfAgenda = "0";
      $iCodigoMovimentoAgenda     = $oDadoAgenda->e81_codmov != "" ? $iCodigoMovimentoAgenda =  $oDadoAgenda->e81_codmov : $iCodigoMovimentoAgenda = "0";
    }

    /*
     * Validamos a situação do slip
     */
    if ($iCodigoMovimentoConfAgenda != "0" || $iCodigoMovimentoAgenda != "0" && $this->possuiAutenticacao()) {

      $sSqlValidaSlip     = "select fc_auttransf({$this->getSlip()},
                                                 '{$dtSessao}',
                                                 '{$sIPSessao}',
                                                 false,
                                                 '{$iCodigoMovimentoConfAgenda}',
                                                 {$iInstituicaoSessao}) as autentica_slip";

      $rsExecutaValidacao = db_query($sSqlValidaSlip);
      if (!$rsExecutaValidacao) {
        throw new Exception("Não foi possível validar a existência e autenticação do slip {$this->getSlip()}.");
      }

      $sDadoAutenticacaoSlip = db_utils::fieldsMemory($rsExecutaValidacao, 0)->autentica_slip;
      $iSubStringValidacao   = substr($sDadoAutenticacaoSlip, 0, 1);
      if ($iSubStringValidacao != 1) {
        throw new Exception($sDadoAutenticacaoSlip);
      }
      if (!empty($oTransferencia)) {

        $iCodigoTerminal = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
        $oTransferencia->setIDTerminal($iCodigoTerminal);
        $oTransferencia->setDataAutenticacao($dtSessao);
        $oTransferencia->setNumeroAutenticacao(substr($sDadoAutenticacaoSlip, 1, 7));
      }
    }

    /*
     * Caso o slip esteja autenticado, podemos estornar ele
     */
    $oDaoSlip                    = db_utils::getDao('slip');
    $oDaoSlip->k17_dtestorno     = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoSlip->k17_motivoestorno = $this->sMotivoEstornoAnulacao;
    $oDaoSlip->k17_codigo        = $this->getSlip();
    $oDaoSlip->alterar($this->getSlip());
    if ($oDaoSlip->erro_status == 0) {

      $sMensagemErro  = "Não foi possível estornar o slip {$this->getSlip()}.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoSlip->erro_msg}";
      throw new Exception($sMensagemErro);
    }

    /*
     * Cancelamos os dados inclusos no processamento da agenda e devolvemos para a agenda
     */
    if ($iCodigoMovimentoConfAgenda != "0" || $iCodigoMovimentoAgenda != "0") {

      $oAgendaPagamento = new agendaPagamento();

      /**
       * Verificamos se o o cheque realmente foi emitido.
       */

      $oDaoEmpageconfChe = db_utils::getDao("empageconfche");
      $sSqlCheque        = $oDaoEmpageconfChe->sql_query_file(null,"*", null,"e91_codmov = {$iCodigoMovimentoAgenda} and e91_ativo is true");
      $rsCheque          = $oDaoEmpageconfChe->sql_record($sSqlCheque);
      if ($oDaoEmpageconfChe->numrows > 0) {
        $oAgendaPagamento->cancelarCheque($iCodigoMovimentoAgenda);
      }

      $oDaoEmpAgeMov = db_utils::getDao("empagemov");
      $oDaoEmpAgeMov->e81_cancelado = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoEmpAgeMov->e81_codmov    = $iCodigoMovimentoAgenda;
      $oDaoEmpAgeMov->alterar($iCodigoMovimentoAgenda);
      if ($oDaoEmpAgeMov->erro_status == 0) {

        $sMensagemErro  = "Não foi possível cancelar o movimento na agenda.\n\n";
        $sMensagemErro .= "Erro Técnico: {$oDaoEmpAgeMov->erro_msg}";
        throw new Exception($sMensagemErro);
      }

      $oSlipAgenda              = new stdClass();
      $oSlipAgenda->iCodigoSlip = $this->getSlip();
      $oSlipAgenda->nValor      = "{$this->getValor()}";
      if ($this->getContaCredito() != 0 ) {

        $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
        $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null, "e83_codtipo", null, "e83_conta = {$this->getContaCredito()}");
        $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
        if ($oDaoEmpAgeTipo->numrows > 0 ) {
          $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
        }
      }
      $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);
    }
    return true;
  }

  /**
   * Anula um slip
   *
   * @param  string $sMotivo
   * @throws Exception
   */
  public function anular($sMotivo, $lExcluirCheque=true, Transferencia $oTransferencia = null) {

    if ($this->isAnulado()) {
      throw new Exception("A transferência {$this->iSlip} já está anulada. Procedimento abortado.");
    }

    $dtDataAtual = date("Y-m-d",db_getsession("DB_datausu"));

    if (!empty($this->dtDataAutenticacao) && ($this->dtDataAutenticacao > $dtDataAtual || $this->dtDataAutenticacao = '')) {

      $sMsgErro    = "Não foi possível anular o slip {$this->iSlip}!\n";
      $sMsgErro   .= "Data de Autenticação é um período posterior a data atual:\n";
      throw new Exception($sMsgErro);
    }

    $this->sMotivoEstornoAnulacao = $sMotivo;
    $this->estornar($lExcluirCheque, $oTransferencia);
    $oDaoEmpageslip = db_utils::getDao("empageslip");
    $sCamposBusca     = "e97_codforma,e96_descr,e90_codgera, e81_codmov,e91_cheque";
    $sWhere           = "e89_codigo = {$this->iSlip} and e81_cancelado is null";

    $oDaoPlaCaixaRecSlip = db_utils::getDao("placaixarecslip");
    $oDaoPlaCaixaRecSlip->excluir(null,"k110_slip = {$this->getSlip()}");

    if ( $oDaoPlaCaixaRecSlip->erro_status == 0 ) {

      $sMensagemUsuario  = "Erro 1 - Não foi possível anular o slip.\n\n Erro técnico: ";
      $sMensagemUsuario .= " {$oDaoPlaCaixaRecSlip->erro_msg}";
      throw new Exception($sMensagemUsuario);
    }


    $oDaorhSlipFolhaSlip = db_utils::getDao("rhslipfolhaslip");
    $oDaorhSlipFolhaSlip->excluir(null,"rh82_slip = {$this->getSlip()}");

    if ( $oDaorhSlipFolhaSlip->erro_status == 0 ) {

      $sMensagemUsuario  = "Erro 2 - Não foi possível anular o slip.\n\n Erro técnico: ";
      $sMensagemUsuario .= " {$oDaorhSlipFolhaSlip->erro_msg}";
      throw new Exception($sMensagemUsuario);
    }


    $oDaoSlipAnul             = db_utils::getDao("slipanul");
    $oDaoSlipAnul->k18_codigo = $this->getSlip();
    $oDaoSlipAnul->k18_motivo = $this->sMotivoEstornoAnulacao;
    $oDaoSlipAnul->incluir($this->getSlip());

    if ($oDaoSlipAnul->erro_status == 0) {

      $sMensagemUsuario  = "Não foi possível incluir a anulação.\n\n";
      $sMensagemUsuario .= "Erro Técnico: {$oDaoSlipAnul->erro_msg}";
      throw new Exception($sMensagemUsuario);
    }

    $oDaoSlip               = db_utils::getDao('slip');
    $oDaoSlip->k17_codigo   = $this->getSlip();
    $oDaoSlip->k17_situacao = 4;
    $oDaoSlip->k17_dtanu    = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoSlip->alterar($this->getSlip());

    if ($oDaoSlip->erro_status == 0) {

      $sMensagemUsuario  = "Não foi possível alterar a situação do slip.\n\n";
      $sMensagemUsuario .= "Erro Técnico: {$oDaoSlip->erro_msg}";
      throw new Exception($sMensagemUsuario);
    }

    $oDaoSlipMov = db_utils::getDao("slipempagemovslips");
    $oDaoSlipMov->excluir(null, "k108_slip = {$this->getSlip()}");

    if ($oDaoSlipMov->erro_status == 0) {

      $sMensagemUsuario  = "Erro 3 - Não foi possível anular o slip.\n\n";
      $sMensagemUsuario .= "Erro Técnico: {$oDaoSlipMov->erro_msg}";
      throw new Exception($sMensagemUsuario);
    }

    $oDaoSlipCorrente = db_utils::getDao("slipcorrente");
    $sSqlCorrente     = $oDaoSlipCorrente->sql_query_file(null,"*", null,"k112_slip= {$this->getSlip()}");
    $rsCorrente       = $oDaoSlipCorrente->sql_record($sSqlCorrente);

    if ($oDaoSlipCorrente->numrows > 0) {

      $iNumRows = $oDaoSlipCorrente->numrows;
      for ($iRowsSlipcorrente = 0; $iRowsSlipcorrente < $iNumRows; $iRowsSlipcorrente++) {

        $oCorrente  = db_utils::fieldsMemory($rsCorrente, $iRowsSlipcorrente);
        $oDaoSlipCorrente->k112_ativo       = "false";
        $oDaoSlipCorrente->k112_sequencial  = $oCorrente->k112_sequencial;
        $oDaoSlipCorrente->alterar($oCorrente->k112_sequencial);

        if ($oDaoSlipCorrente->erro_status == 0) {

           $sMensagemUsuario  = "Erro 4 - Não foi possível anular o slip.\n\n";
           $sMensagemUsuario .= "Erro Técnico: {$oDaoSlipCorrente->erro_msg}";
           throw new Exception($sMensagemUsuario);
        }
      }
    }
    return true;
  }

  /**
   * Verifica se o slip já está anulado.
   * @return boolean
   */
  public function isAnulado() {

    $oDaoSlipAnul     = db_utils::getDao('slipanul');
    $sSqlBuscaAnulado = $oDaoSlipAnul->sql_query_file($this->iSlip);
    $rsBuscaAnulado   = $oDaoSlipAnul->sql_record($sSqlBuscaAnulado);
    if ($oDaoSlipAnul->numrows > 0) {
      return true;
    }
    return false;
  }


  public function possuiAutenticacao() {

    if ($this->iTipoAutenticacao != 0 ){
      return true;
    }
    return false;
  }

  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /**
   * Seta a instituicao que criou o slip
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }
  /**
   * Retorna a instituicao que criou o slip
   * @return integer $iInstituicao
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Seta o codigo da caracteristica peculiar
   * @param string $sCaracteristica
   */
  public function setCaracteristicaPeculiarCredito($sCaracteristica) {
    $this->sCodigoCaracteristicaPeculiarCredito = $sCaracteristica;
  }

  /**
   * Retorna o código da caracteristica peculiar
   * @return string
   */
  public function getCaracteristicaPeculiarCredito() {
    return $this->sCodigoCaracteristicaPeculiarCredito;
  }

  /**
   * Seta o codigo da caracteristica peculiar debito
   * @param string $sCaracteristica
   */
  public function setCaracteristicaPeculiarDebito($sCaracteristica) {
    $this->sCodigoCaracteristicaPeculiarDebito = $sCaracteristica;
  }

  /**
   * Retorna o código da caracteristica peculiar debito
   * @return string
   */
  public function getCaracteristicaPeculiarDebito() {
    return $this->sCodigoCaracteristicaPeculiarDebito;
  }
  /**
   * Retorna o codigo sequencial do slip
   * @return integer
   */
  final public function getSlip() {
    return $this->iSlip;
  }

  /**
   * Seta o codigo sequencial do slip
   * @param integer $iCodigoSlip
   */
  final public function setSlip($iCodigoSlip) {
    $this->iSlip = $iCodigoSlip;
  }

  /**
   * Retorna as arrecadações do slip
   * @return array
   */
  final public function getArrecacoes() {
    return $this->aArrecacoes;
  }

  /**
   * @param integer $iArrecacoes
   */
  final public function addArrecadacao($iArrecadacoes) {
    if (!in_array($iArrecadacoes, $this->aArrecadacoes)) {
      $this->aArrecadacoes[] = $iArrecadacoes;
    }
  }

  /**
   * @return array
   */
  final public function getPagamentos() {
    return $this->aPagamentos;
  }

  /**
   * @param array $aPagamentos
   */
  final private function setPagamentos($aPagamentos) {
    $this->aPagamentos = $aPagamentos;
  }

  /**
   * @return array
   */
  final public function getRecursos() {
    return $this->aRecursos;
  }

  /**
   * Adiciona um Recurso ao Slip
   *
   * @param integer $iRecurso codigo do recurso
   * @param float   $nValor valor do Recurso
   */
  final public function addRecurso($iRecurso, $nValor = 0) {
    if ($this->aRecursos[$iRecurso]) {
      $this->aRecursos[$iRecurso] += $nValor;
    } else {
      $this->aRecursos[$iRecurso] = $nValor;
    }
  }

  /**
   * @return string
   */

  final public function getData() {
    return $this->dtData;
  }

  /**
   * @param string $dtData
   */

  final public function setData($dtData) {
    $this->dtData = $dtData;
  }

  /**
   * @return integer
   */
  final public function getContaCredito() {
    return $this->iContaCredito;
  }

  /**
   * @param integer $iContaCredito
   */
  final public function setContaCredito($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * @return integer
   */
  final public function getContaDebito() {
    return $this->iContaDebito;
  }

  /**
   * @param integer $iContaDebito
   */
  final public function setContaDebito($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }

  /**
   * @return integer
   */
  final public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * @param integer $iSituacao
   */
  final public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * @return unknown_type
   */
  final public function getTipoPagamento() {
    return $this->iTipoPagamento;
  }

  /**
   * @param unknown_type $iTipoPagamento
   */
  final public function setTipoPagamento($iTipoPagamento) {
    $this->iTipoPagamento = $iTipoPagamento;
  }

  /**
   * @return float
   */
  final public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $nValor
   */
  final public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return string
   */
  final public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * @param string $sObservacoes
   */
  final public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }
  /**
   * @return integer
   */
  public function getNumCgm() {
    return $this->iNumCgm;
  }

  /**
   * @param integer $iNumCgm
   */
  public function setNumCgm($iNumCgm) {
    $this->iNumCgm = $iNumCgm;
  }
  /**
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * Seta o código do historico
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * Retorna o codigo do movimento
   * @return integer
   */
  public function getMovimento() {
    return $this->iMovimento;
  }

  /**
   * Seta o codigo de um movimento
   * @param integer $iCodigoMovimento
   */
  public function setMovimento($iCodigoMovimento) {
    $this->iMovimento = $iCodigoMovimento;
  }
}
?>