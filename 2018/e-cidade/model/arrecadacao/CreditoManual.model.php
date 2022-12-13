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
 * Model Crédito Manual
 *
 * Classe que manipula os créditos lançados manualmente no sistema
 *
 * @author alberto <alberto@dbseller.com.br>
 * @author robson.silva <robson.silva@dbseller.com.br>
 * @package Arrecadacao
 *
 * @version $
 *
 */
class CreditoManual extends Credito {

  /**
   * Instancia da classe CgmBase com os dados do contribuinte
   * @var CgmBase
   */
  private $oCgm;

  /**
   * Array com regras de compensacao para o crédito
   * @var array
   */
  public $aRegraCompensacao = array();

  /**
   * Observações do lançamento de crédito
   * @var string
   */
  private $sObservacao;

  /**
   * Numpre do recibo gerado para o crédito
   * @var integer
   */
  private $iNumpre;

  /**
   * Numpar do recibo gerado para o crédito
   * @var integer
   */
  private $iNumpar;

  /**
   * Codigo da receita do recibo gerado para o crédito
   * @var integer
   */
  private $iCodigoReceita;

  /**
   * Codigo do historico do credito
   * @var integer
   */
  private $iCodigoHistorico;

  /**
   * Historico do credito
   * @var string
   */
  private $sHistorico;

  /**
   * Código do tipo de débito de origem do crédito
   * @var integer
   */
  private $iCodigoTipoOrigem;

  /**
   * Descrição do tipo de débito de origem do crédito
   * @var string
   */
  private $sDescricaoTipoOrigem;

  /**
   * Código do tipo de débito de destino do crédito
   * @var integer
   */
  private $iCodigoTipoDestino;

  /**
   * Descrição do tipo de débito de destino do crédito
   * @var string
   */
  private $sDescricaoTipoDestino;

  /**
   * Instância do objeto recibo
   * @var recibo
   */
  private $oRecibo;

  /**
   * Código do Registro ref ao processo externo
   * @var integer
   */
  private $iCodigoProcessoExterno;

  /**
   * Numero do processo Externo vinculado ao crédito
   * @var string
   */
  private $sNumeroProcessoExterno;

  /**
   * Nome do titular do processo Externo vinculado ao crédito
   * @var string
   */
  private $sNomeTitularProcessoExterno;

  /**
   * Data do processo Externo vinculado ao crédito
   * @var date
   */
  private $oDataProcessoExterno;

  /**
   * Instancia do objeto processoProtocolo
   * @var processoProtocolo
   */
  private $oProcessoProtocolo;

  /**
   * Tipo de processo. True = Processo do Sistema
   * False = Processo Externo
   * @var bool
   */
  private $lProcessoSistema;

  /**
   * @var integer
   */
  private $iCodigoMatricula;

  /**
   * @var integer
   */
  private $iCodigoInscricao;

  /**
   * Caso seja informado o código do crédito, este é carregado em memória
   * @param string $iCodigoCredito
   * @throws Exception
   * @return boolean
   */
  public function __construct($iCodigoCredito = null) {

    if (!empty($iCodigoCredito)) {

      $oDaoAbatimento = db_utils::getDao('abatimento');

      $sWhere         = "k125_sequencial = {$iCodigoCredito}";

      $sCampos  = "abatimento.k125_sequencial,                        ";
      $sCampos .= "abatimento.k125_tipoabatimento,                    ";
      $sCampos .= "abatimento.k125_datalanc,                          ";
      $sCampos .= "abatimento.k125_hora,                              ";
      $sCampos .= "abatimento.k125_usuario,                           ";
      $sCampos .= "abatimento.k125_instit,                            ";
      $sCampos .= "abatimento.k125_valor,                             ";
      $sCampos .= "abatimento.k125_perc,                              ";
      $sCampos .= "abatimento.k125_valordisponivel,                   ";
      $sCampos .= "abatimentoprotprocesso.k159_protprocesso,          ";
      $sCampos .= "abatimentoprocessoexterno.k160_sequencial,         ";
      $sCampos .= "abatimentoprocessoexterno.k160_numeroprocesso,     ";
      $sCampos .= "abatimentoprocessoexterno.k160_nometitular,        ";
      $sCampos .= "abatimentoprocessoexterno.k160_data,               ";
      $sCampos .= "arrenumcgm.k00_numcgm                              ";

      $sSqlAbatimento = $oDaoAbatimento->sql_queryCreditoManual($sCampos, $sWhere);
      $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);

      if ($oDaoAbatimento->numrows == 0) {
        throw new Exception("Nenhum crédito encontrado com o código: {$iCodigoCredito}");
      }

      $oAbatimento = db_utils::fieldsMemory($rsAbatimento, 0);

      $this->setCodigoCredito              ($oAbatimento->k125_sequencial);
      $this->setTipoAbatimento             ($oAbatimento->k125_tipoabatimento);
      $this->setDataLancamento             (new DBDate($oAbatimento->k125_datalanc));
      $this->setHora                       ($oAbatimento->k125_hora);
      $this->setUsuario                    ($oAbatimento->k125_usuario);
      $this->setInstituicao                ($oAbatimento->k125_instit);
      $this->setValor                      ($oAbatimento->k125_valor);
      $this->setPercentual                 ($oAbatimento->k125_perc);
      $this->setCgm                        (CgmFactory::getInstanceByCgm($oAbatimento->k00_numcgm));

      $oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao');

      $sSqlAbatimentoRegraCompensacao = $oDaoAbatimentoRegraCompensacao->sql_query_file(null,
                                                                                        "k156_regracompensacao",
                                                                                        null,
                                                                                        "k156_abatimento = {$this->getCodigoCredito()}");

      $rsAbatimentoRegraCompensacao   = $oDaoAbatimentoRegraCompensacao->sql_record($sSqlAbatimentoRegraCompensacao);

      if ($oDaoAbatimentoRegraCompensacao->numrows > 0) {

        foreach (db_utils::getCollectionByRecord($rsAbatimentoRegraCompensacao) as $oAbatimentoRegraCompensacao) {

          $this->adicionarRegra(new RegraCompensacao($oAbatimentoRegraCompensacao->k156_regracompensacao));

        }

      }

      if ($oAbatimento->k159_protprocesso != '' || $oAbatimento->k160_sequencial != '') {

        if ($oAbatimento->k159_protprocesso != '') {

          $this->setProcessoSistema(true);
          $this->setProcessoProtocolo(new processoProtocolo($oAbatimento->k159_protprocesso));

        } else {

          $this->setCodigoProcessoExterno      ($oAbatimento->k160_sequencial);
          $this->setNumeroProcessoExterno      ($oAbatimento->k160_numeroprocesso);
          $this->setNomeTitularProcessoExterno ($oAbatimento->k160_nometitular);
          $this->setDataProcessoExterno        (new DBDate($oAbatimento->k160_data));

        }

      }

    }

  }

  /**
   * Lança o crédito para um cgm
   * @throws Exception Não foi instânciado o cgm do objeto
   * @throws Exception Não foi instânciado a regra de compensação do objeto
   * @throws Exception Não foi informado o valor do crédito
   * @throws Exception Erros na inclusão do crédito
   */
  public function salvar() {

    if ($this->getValor() == null || $this->getValor() <= 0) {
      throw  new Exception("Valor não informado ou inválido para a inclusão do crédito");
    }

    if (count($this->getRegrasCompensacao()) == 0) {
      throw new Exception("Nenhuma regra adicionada ao crédito");
    }

    $oDaoAbatimento                          = db_utils::getDao('abatimento');
    $oDaoAbatimento->k125_tipoabatimento     = $this->getTipoAbatimento();
    $oDaoAbatimento->k125_datalanc           = $this->getDataLancamento()->getDate();
    $oDaoAbatimento->k125_hora               = $this->getHora();
    $oDaoAbatimento->k125_usuario            = $this->getUsuario();
    $oDaoAbatimento->k125_instit             = $this->getInstituicao();
    $oDaoAbatimento->k125_valor              = $this->getValor();
    $oDaoAbatimento->k125_perc               = $this->getPercentual();
    $oDaoAbatimento->k125_abatimentosituacao = 1;

    if ( $this->getValorDisponivel() == null && $this->getValor() > 0 ) {
      $this->setValorDisponivel( $this->getValor() );
    }

    $oDaoAbatimento->k125_valordisponivel = $this->getValorDisponivel();

    $oDaoAbatimento->incluir(null);

    if ($oDaoAbatimento->erro_status == "0") {
      throw new Exception('Erro ao incluir crédito para o cgm. \nErro: (abatimento) ' . $oDaoAbatimento->erro_msg);
    }

    $this->setCodigoCredito($oDaoAbatimento->k125_sequencial);

    $oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao', false);

    foreach ($this->getRegrasCompensacao() as $oRegraCompensacao) {

      $oDaoAbatimentoRegraCompensacao = new cl_abatimentoregracompensacao();

      $oDaoAbatimentoRegraCompensacao->k156_abatimento       = $this->getCodigoCredito();
      $oDaoAbatimentoRegraCompensacao->k156_observacao       = $this->getObservacao();
      $oDaoAbatimentoRegraCompensacao->k156_regracompensacao = $oRegraCompensacao->getCodigoRegraCompensacao();
      $oDaoAbatimentoRegraCompensacao->incluir(null);

      if ($oDaoAbatimentoRegraCompensacao->erro_status == "0") {
        throw new Exception("Erro ao incluir regras para o crédito. \n Erro: (abatimentoregracompensacao) {$oDaoAbatimentoRegraCompensacao->erro_msg}");
      }

    }

    /**
     * Caso a receita do recibo não for informada, gera-se recibo com as configuraçãoes do tipo de débito de origem->receita de crédito
     */
    if ($this->getCodigoReceita() == '') {
      $this->setCodigoReceita($oRegraCompensacao->getCodigoReceitaRecibo());
    }

    $iNumpreRecibo  = $this->geraRecibo();

    $oDaoAbatimentoRecibo                      = db_utils::getDao('abatimentorecibo');
    $oDaoAbatimentoRecibo->k127_abatimento     = $this->getCodigoCredito();
    $oDaoAbatimentoRecibo->k127_numprerecibo   = $iNumpreRecibo;
    $oDaoAbatimentoRecibo->k127_numpreoriginal = '';

    $oDaoAbatimentoRecibo->incluir(null);

    if ($oDaoAbatimentoRecibo->erro_status == '0') {
      throw new Exception('Erro ao incluir crédito para o cgm. \nErro: (abatimentorecibo) ' . $oDaoAbatimentoRecibo->erro_msg);
    }

    if (!empty($this->oCgm) && $this->getCgm()->getCodigo() !== '') {

      $oDaoArrenumcgm = db_utils::getDao('arrenumcgm');
      $oDaoArrenumcgm->incluir($this->getCgm()->getCodigo(), $iNumpreRecibo);
      if ($oDaoArrenumcgm->erro_status == '0') {
        throw new Exception('Erro ao incluir crédito para o cgm. \nErro: (arrenumcgm) ' . $oDaoArrenumcgm->erro_msg);
      }
    }

    if (!empty($this->iCodigoMatricula)) {

      $daoArreMatric = new cl_arrematric();
      $daoArreMatric->k00_numpre = $iNumpreRecibo;
      $daoArreMatric->k00_matric = $this->iCodigoMatricula;
      $daoArreMatric->k00_perc   = 100;
      $daoArreMatric->incluir($daoArreMatric->k00_numpre, $daoArreMatric->k00_matric);
      if ($daoArreMatric->erro_status === '0') {
        throw new DBException("Ocorreu um erro ao salvar o vínculo do crédito com a matrícula {$this->iCodigoMatricula}.");
      }
    }

    if (!empty($this->iCodigoInscricao)) {

      $daoArreInscr = new cl_arreinscr();
      $daoArreInscr->k00_numpre = $iNumpreRecibo;
      $daoArreInscr->k00_inscr  = $this->iCodigoInscricao;
      $daoArreInscr->k00_perc   = 100;
      $daoArreInscr->incluir($daoArreInscr->k00_numpre, $daoArreInscr->k00_inscr);
      if ($daoArreInscr->erro_status === '0') {
        throw new DBException("Ocorreu um erro ao salvar o vínculo do crédito com a inscrição {$this->iCodigoMatricula}.");
      }
    }

    /**
     * processo do crédito
     * Caso não seja setado nenhum tipo de processo, o procedimento retornará
     */
    if ($this->isProcessoSistema()){

      $oDaoabatimentoprotprocesso = db_utils::getDao('abatimentoprotprocesso');

      $oDaoabatimentoprotprocesso->k159_abatimento   = $this->getCodigoCredito();
      $oDaoabatimentoprotprocesso->k159_protprocesso = $this->getProcessoProtocolo()->getCodProcesso();
      $oDaoabatimentoprotprocesso->incluir(null);

      if ($oDaoabatimentoprotprocesso->erro_status == "0") {
        throw new Exception('Erro ao incluir o processo para o crédito. \nErro: (abatimentoprotprocesso) ' . $oDaoabatimentoprotprocesso->erro_msg);
      }

    } else if ($this->isProcessoSistema() === false) {

      $oDaoAbatimentoProcessoExterno = db_utils::getDao('abatimentoprocessoexterno');
      $oDaoAbatimentoProcessoExterno->k160_abatimento     = $this->getCodigoCredito();
      $oDaoAbatimentoProcessoExterno->k160_numeroprocesso = $this->getNumeroProcessoExterno();
      $oDaoAbatimentoProcessoExterno->k160_nometitular    = $this->getNomeTitularProcessoExterno();

      if ($this->getDataProcessoExterno() != '') {
        $oDaoAbatimentoProcessoExterno->k160_data           = $this->getDataProcessoExterno()->getDate();
      }

      $oDaoAbatimentoProcessoExterno->incluir(null);

      if ($oDaoAbatimentoProcessoExterno->erro_status == "0") {
        throw new Exception('Erro ao incluir o processo externo para o crédito. \nErro: (abatimentoprotprocessoexterno) ' . $oDaoAbatimentoProcessoExterno->erro_msg);
      }

    }

    return true;
  }

  /**
   * Inclui recibo avulso
   * @return number
   */
  public function geraRecibo () {

    db_sel_instit(db_getsession("DB_instit"), "db21_regracgmiptu");
    if (empty($db21_regracgmiptu)) {
      $db21_regracgmiptu = 0;
    }

    $iCodigoCgm = null;
    if (!empty($this->iCodigoMatricula)) {

      $resBuscaCgm = db_query("select rinumcgm from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'M', $this->iCodigoMatricula)");
      $iCodigoCgm  = db_utils::fieldsMemory($resBuscaCgm, 0)->rinumcgm;
    }

    if (!empty($this->iCodigoInscricao)) {

      $resBuscaCgm = db_query("select rinumcgm from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'I', $this->iCodigoInscricao)");
      $iCodigoCgm  = db_utils::fieldsMemory($resBuscaCgm, 0)->rinumcgm;
    }

    if (!empty($this->oCgm) && $this->oCgm->getCodigo() !== '') {
      $iCodigoCgm = $this->oCgm->getCodigo();
    }




    $oRecibo = new recibo(1, $iCodigoCgm);

    $oRecibo->setDataRecibo($this->getDataLancamento()->getDate());
    $oRecibo->setDataVencimentoRecibo($this->getDataLancamento()->getDate());
    $oRecibo->adicionarReceita($this->getCodigoReceita(), $this->getValor());

    if ( empty($this->iCodigoHistorico) ) {
      $this->setCodigoHistorico(505);
    }

    if ( empty($this->sHistorico) ) {
      $this->setHistorico("Crédito gerado manualmente para o CGM.");
    }

    if ($this->getCodigoTipoDestino() != '') {
      $oRecibo->setCodigoTipo($this->getCodigoTipoDestino());
    }

    $oRecibo->setCodigoHistorico($this->getCodigoHistorico());
    $oRecibo->setHistorico($this->getHistorico());

    $oRecibo->emiteRecibo();

    return $oRecibo->getNumpreRecibo();

  }

  /**
   * Agregação com regras para o crédito
   * @param RegraCompensacao $oRegraCompensacao
   */
  public function adicionarRegra(RegraCompensacao $oRegraCompensacao) {
    $this->aRegraCompensacao[] = $oRegraCompensacao;
  }

  /**
   * Retorna array com regras de compensacao para o crédito
   * @return array
   */
  public function getRegrasCompensacao() {
    return $this->aRegraCompensacao;
  }

  /**
   * Define instância do objeto recibo
   * @param recibo $oRecibo
   */
  public function setRecibo(recibo $oRecibo) {
    $this->oRecibo = $oRecibo;
  }

  /**
   * Retorna instância do objeto recibo
   * @return recibo
   */
  public function getRecibo() {
    return $this->oRecibo;
  }

  /**
   * Define o codigo de historioco do recibo do credito
   *
   * @param integer $iCodigoHistorico
   * @access public
   * @return void
   */
  public function setCodigoHistorico($iCodigoHistorico) {
    $this->iCodigoHistorico = $iCodigoHistorico;
  }

  /**
   * Retorna o codigo do historico
   *
   * @access public
   * @return integer
   */
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Define descricao do historico
   *
   * @param string $sHistorico
   * @access public
   * @return void
   */
  public function setHistorico($sHistorico) {
    $this->sHistorico = $sHistorico;
  }

  /**
   * Retorna a descricao do historico
   *
   * @access public
   * @return string
   */
  public function getHistorico() {
    return $this->sHistorico;
  }

  /**
   * Retorna o cgm do crédito
   * @return object CgmFactory
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Define o cgm do crédito
   * @param CgmFactory $oCgm
   */
  public function setCgm(CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna a observação do crédito
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observação para o crédito
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Define o numpre para o recibo do crédito
   * @param integer $iNumpre
   */
  public function setNumpre($iNumpre){
    $this->iNumpre = $iNumpre;
  }

  /**
   * Retorna o numpre do recibo do crédito
   * @return integer
   */
  public function getNumpre(){
    return $this->iNumpre;
  }

  /**
   * Define o numpar do recibo do crédito
   * @param integer $iNumpar
   */
  public function setNumpar($iNumpar){
    $this->iNumpar = $iNumpar;
  }

  /**
   * Retorna o numpar do recibo do crédito
   * @return integer
   */
  public function getNumpar(){
    return $this->iNumpar;
  }

  /**
   * Define o código da receita do recibo do crédito
   * @param integer
   */
  public function setCodigoReceita($iCodigoReceita){
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * Retorna o código da receita do recibo do crédito
   * @return integer
   */
  public function getCodigoReceita(){
    return $this->iCodigoReceita;
  }

  /**
   * Retorna o código do tipo de débito de origem do crédito
   * @return integer
   */
  public function getCodigoTipoOrigem() {
    return $this->iCodigoTipoOrigem;
  }

  /**
   * Define o código do tipo de débito de origem do crédito
   * @param $iCodigoTipoOrigem
   */
  public function setCodigoTipoOrigem($iCodigoTipoOrigem) {
    $this->iCodigoTipoOrigem = $iCodigoTipoOrigem;
  }

  /**
   * Retorna a descrição do tipo de débito de origem
   * @return string
   */
  public function getDescricaoTipoOrigem() {
    return $this->sDescricaoTipoOrigem;
  }

  /**
   * Define a descrição do tipo de débito de origem
   * @param $sDescricaoTipoOrigem
   */
  public function setDescricaoTipoOrigem($sDescricaoTipoOrigem) {
    $this->sDescricaoTipoOrigem = $sDescricaoTipoOrigem;
  }

  /**
   * Retorna o código do tipo de débito de destino do crédito
   * @return $iCodigoTipoDestino
   */
  public function getCodigoTipoDestino() {
    return $this->iCodigoTipoDestino;
  }

  /**
   * Define o código do tipo de débito de destino do crédito
   * @param $iCodigoTipoOrigem
   */
  public function setCodigoTipoDestino($iCodigoTipoDestino) {
    $this->iCodigoTipoDestino = $iCodigoTipoDestino;
  }

  /**
   * Retorna a descrição do tipo de débito de destino
   * @return $sDescricaoTipoDestino
   */
  public function getDescricaoTipoDestino() {
    return $this->sDescricaoTipoDestino;
  }

  /**
   * Define a descrição do tipo de débito de destino
   * @param $sDescricaoTipoDestino
   */
  public function setDescricaoTipoDestino($sDescricaoTipoDestino) {
    $this->sDescricaoTipoDestino = $sDescricaoTipoDestino;
  }

  /**
   * Define o Codigo do processo Externo vinculado ao crédito
   * @param $iCodigoProcessoExterno
   */
  public function setCodigoProcessoExterno($iCodigoProcessoExterno) {
    $this->iCodigoProcessoExterno = $iCodigoProcessoExterno;
  }

  /**
   * Retorna o Codigo do processo Externo vinculado ao crédito
   * @return $iCodigoProcessoExterno
   */
  public function getCodigoProcessoExterno() {
    return $this->iCodigoProcessoExterno;
  }

  /**
   * Define o Numero do processo externo
   * @param $sNumeroProcessoExterno
   */
  public function setNumeroProcessoExterno($sNumeroProcessoExterno) {
    $this->sNumeroProcessoExterno = $sNumeroProcessoExterno;
  }

  /**
   * Retorna o Numero do processo externo
   * @return $sNumeroProcessoExterno
   */
  public function getNumeroProcessoExterno() {
    return $this->sNumeroProcessoExterno;
  }

  /**
   * Define o nome do titular do processo externo
   * @param $sNomeTitularProcessoExterno
   */
  public function setNomeTitularProcessoExterno($sNomeTitularProcessoExterno) {
    $this->sNomeTitularProcessoExterno = $sNomeTitularProcessoExterno;
  }

  /**
   * Retorna o nome do titular do processo externo
   * @return $sNomeTitularProcessoExterno
   */
  public function getNomeTitularProcessoExterno() {
    return $this->sNomeTitularProcessoExterno;
  }

  /**
   * Define data do processo externo
   * @param DBDate $oDataProcessoExterno
   */
  public function setDataProcessoExterno(DBDate $oDataProcessoExterno) {
    $this->oDataProcessoExterno = $oDataProcessoExterno;
  }

  /**
   * Retorna data do processo externo
   * @return DBDate $oDataProcessoExterno
   */
  public function getDataProcessoExterno() {
    return $this->oDataProcessoExterno;
  }

  /**
   * Valida se o processo é um processo do sistema
   */
  public function isProcessoSistema() {
    return $this->lProcessoSistema;
  }

  /**
   * Valida se o processo é um processo do sistema
   */
  public function setProcessoSistema($lProcessoSistema) {
    $this->lProcessoSistema = $lProcessoSistema;
  }

  /**
   * Define Objeto contendo os dados do processo no sistema
   * @param processoProtocolo $oProcessoSistema
   */
  public function setProcessoProtocolo(processoProtocolo $oProcessoProtocolo) {
    $this->oProcessoProtocolo = $oProcessoProtocolo;
  }

  /**
   * Retorna Objeto contendo os dados do processo no sistema
   * return object processoProtocolo
   */
  public function getProcessoProtocolo() {
    return $this->oProcessoProtocolo;
  }

  /**
   * @param integer $iCodigoMatricula
   */
  public function setCodigoMatricula($iCodigoMatricula) {
    $this->iCodigoMatricula = $iCodigoMatricula;
  }

  /**
   * @param integer $iCodigoInscricao
   */
  public function setCodigoInscricao($iCodigoInscricao) {
    $this->iCodigoInscricao = $iCodigoInscricao;
  }
}