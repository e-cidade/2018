<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model Cr�dito Manual
 *
 * Classe que manipula os cr�ditos lan�ados manualmente no sistema
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
   * Array com regras de compensacao para o cr�dito
   * @var array
   */
  public $aRegraCompensacao = array();
   
  /**
   * Observa��es do lan�amento de cr�dito
   * @var string
   */
  private $sObservacao;
  
  /**
   * Numpre do recibo gerado para o cr�dito
   * @var integer
   */
  private $iNumpre;
  
  /**
   * Numpar do recibo gerado para o cr�dito
   * @var integer
   */
  private $iNumpar;
  
  /**
   * Codigo da receita do recibo gerado para o cr�dito
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
   * C�digo do tipo de d�bito de origem do cr�dito
   * @var integer
   */
  private $iCodigoTipoOrigem;
  
  /**
   * Descri��o do tipo de d�bito de origem do cr�dito
   * @var string
   */
  private $sDescricaoTipoOrigem;
  
  /**
   * C�digo do tipo de d�bito de destino do cr�dito
   * @var integer
   */
  private $iCodigoTipoDestino;
  
  /**
   * Descri��o do tipo de d�bito de destino do cr�dito
   * @var string
   */
  private $sDescricaoTipoDestino;

  /**
   * Inst�ncia do objeto recibo
   * @var recibo
   */
  private $oRecibo;
  
  /**
   * C�digo do Registro ref ao processo externo
   * @var integer
   */
  private $iCodigoProcessoExterno;
  
  /**
   * Numero do processo Externo vinculado ao cr�dito
   * @var string
   */
  private $sNumeroProcessoExterno;
  
  /**
   * Nome do titular do processo Externo vinculado ao cr�dito
   * @var string
   */
  private $sNomeTitularProcessoExterno;
  
  /**
   * Data do processo Externo vinculado ao cr�dito
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
   * Caso seja informado o c�digo do cr�dito, este � carregado em mem�ria
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
        throw new Exception("Nenhum cr�dito encontrado com o c�digo: {$iCodigoCredito}");
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
        
        foreach (db_utils::getColectionByRecord($rsAbatimentoRegraCompensacao) as $oAbatimentoRegraCompensacao) {
          
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
   * Lan�a o cr�dito para um cgm
   * @throws Exception N�o foi inst�nciado o cgm do objeto
   * @throws Exception N�o foi inst�nciado a regra de compensa��o do objeto
   * @throws Exception N�o foi informado o valor do cr�dito
   * @throws Exception Erros na inclus�o do cr�dito
   */
  public function salvar() {
    
    if (!$this->getCgm() instanceof CgmBase || $this->getCgm()->getCodigo() == '') {
      throw  new Exception("CGM n�o informado ou inv�lido para a inclus�o do cr�dito");
    }
    
    if ($this->getValor() == null || $this->getValor() <= 0) {
      throw  new Exception("Valor n�o informado ou inv�lido para a inclus�o do cr�dito");
    }
    
    if (count($this->getRegrasCompensacao()) == 0) {
      throw new Exception("Nenhuma regra adicionada ao cr�dito");
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
      throw new Exception('Erro ao incluir cr�dito para o cgm. \nErro: (abatimento) ' . $oDaoAbatimento->erro_msg);
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
        throw new Exception("Erro ao incluir regras para o cr�dito. \n Erro: (abatimentoregracompensacao) {$oDaoAbatimentoRegraCompensacao->erro_msg}");
      }
      
    }
    
    /**
     * Caso a receita do recibo n�o for informada, gera-se recibo com as configura��oes do tipo de d�bito de origem->receita de cr�dito
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
      throw new Exception('Erro ao incluir cr�dito para o cgm. \nErro: (abatimentorecibo) ' . $oDaoAbatimentoRecibo->erro_msg);
    } 
    
    $oDaoArrenumcgm = db_utils::getDao('arrenumcgm');
    $oDaoArrenumcgm->incluir($this->getCgm()->getCodigo(), $iNumpreRecibo);
    
    if ($oDaoArrenumcgm->erro_status == '0') {
      throw new Exception('Erro ao incluir cr�dito para o cgm. \nErro: (arrenumcgm) ' . $oDaoArrenumcgm->erro_msg);
    }
    
    /**
     * processo do cr�dito
     * Caso n�o seja setado nenhum tipo de processo, o procedimento retornar�
     */
    if ($this->isProcessoSistema()){
    
      $oDaoabatimentoprotprocesso = db_utils::getDao('abatimentoprotprocesso');
    
      $oDaoabatimentoprotprocesso->k159_abatimento   = $this->getCodigoCredito();
      $oDaoabatimentoprotprocesso->k159_protprocesso = $this->getProcessoProtocolo()->getCodProcesso();
      $oDaoabatimentoprotprocesso->incluir(null);
    
      if ($oDaoabatimentoprotprocesso->erro_status == "0") {
        throw new Exception('Erro ao incluir o processo para o cr�dito. \nErro: (abatimentoprotprocesso) ' . $oDaoabatimentoprotprocesso->erro_msg);
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
        throw new Exception('Erro ao incluir o processo externo para o cr�dito. \nErro: (abatimentoprotprocessoexterno) ' . $oDaoAbatimentoProcessoExterno->erro_msg);
      }
    
    }
    
    return true;
  }
  
  /**
   * Inclui recibo avulso
   * @return number
   */
  public function geraRecibo () {
    
    $oRecibo = new recibo(1, $this->getCgm()->getCodigo());
    
    $oRecibo->setDataRecibo($this->getDataLancamento()->getDate());
    $oRecibo->setDataVencimentoRecibo($this->getDataLancamento()->getDate());
    $oRecibo->adicionarReceita($this->getCodigoReceita(), $this->getValor());

    if ( empty($this->iCodigoHistorico) ) {
      $this->setCodigoHistorico(505);
    }

    if ( empty($this->sHistorico) ) {
      $this->setHistorico("Cr�dito gerado manualmente para o CGM {$this->getCgm()->getCodigo()}");
    }
    
    $oRecibo->setCodigoHistorico( $this->getCodigoHistorico() );
    $oRecibo->setHistorico( $this->getHistorico() );
    
    $oRecibo->emiteRecibo();
    
    return $oRecibo->getNumpreRecibo();

  }
  
  /**
   * Agrega��o com regras para o cr�dito
   * @param RegraCompensacao $oRegraCompensacao
   */
  public function adicionarRegra(RegraCompensacao $oRegraCompensacao) {
    $this->aRegraCompensacao[] = $oRegraCompensacao;
  }
  
  /**
   * Retorna array com regras de compensacao para o cr�dito
   * @return array
   */
  public function getRegrasCompensacao() {
    return $this->aRegraCompensacao;
  }
  
  /**
   * Define inst�ncia do objeto recibo 
   * @param recibo $oRecibo
   */
  public function setRecibo(recibo $oRecibo) {
    $this->oRecibo = $oRecibo;
  }
  
  /**
   * Retorna inst�ncia do objeto recibo
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
   * Retorna o cgm do cr�dito
   * @return object CgmFactory
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Define o cgm do cr�dito
   * @param CgmFactory $oCgm
   */
  public function setCgm(CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna a observa��o do cr�dito
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observa��o para o cr�dito
   * @param $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Define o numpre para o recibo do cr�dito
   * @param integer $iNumpre
   */
  public function setNumpre($iNumpre){
    $this->iNumpre = $iNumpre;
  }
  
  /**
   * Retorna o numpre do recibo do cr�dito
   * @return integer
   */
  public function getNumpre(){
    return $this->iNumpre;
  }
  
  /**
   * Define o numpar do recibo do cr�dito
   * @param integer $iNumpar
   */
  public function setNumpar($iNumpar){
    $this->iNumpar = $iNumpar;
  }
  
  /**
   * Retorna o numpar do recibo do cr�dito
   * @return integer
   */
  public function getNumpar(){
    return $this->iNumpar;
  }
  
  /**
   * Define o c�digo da receita do recibo do cr�dito
   * @param integer
   */
  public function setCodigoReceita($iCodigoReceita){
    $this->iCodigoReceita = $iCodigoReceita;
  }
  
  /**
   * Retorna o c�digo da receita do recibo do cr�dito
   * @return integer
   */
  public function getCodigoReceita(){
    return $this->iCodigoReceita;
  }
  
  /**
   * Retorna o c�digo do tipo de d�bito de origem do cr�dito
   * @return integer
   */
  public function getCodigoTipoOrigem() {
    return $this->iCodigoTipoOrigem;
  }

  /**
   * Define o c�digo do tipo de d�bito de origem do cr�dito
   * @param $iCodigoTipoOrigem
   */
  public function setCodigoTipoOrigem($iCodigoTipoOrigem) {
    $this->iCodigoTipoOrigem = $iCodigoTipoOrigem;
  }
  
  /**
   * Retorna a descri��o do tipo de d�bito de origem
   * @return string
   */
  public function getDescricaoTipoOrigem() {
    return $this->sDescricaoTipoOrigem;
  }

  /**
   * Define a descri��o do tipo de d�bito de origem
   * @param $sDescricaoTipoOrigem
   */
  public function setDescricaoTipoOrigem($sDescricaoTipoOrigem) {
    $this->sDescricaoTipoOrigem = $sDescricaoTipoOrigem;
  }
  
  /**
   * Retorna o c�digo do tipo de d�bito de destino do cr�dito
   * @return $iCodigoTipoDestino
   */
  public function getCodigoTipoDestino() {
    return $this->iCodigoTipoDestino;
  }

  /**
   * Define o c�digo do tipo de d�bito de destino do cr�dito
   * @param $iCodigoTipoOrigem
   */
  public function setCodigoTipoDestino($iCodigoTipoDestino) {
    $this->iCodigoTipoDestino = $iCodigoTipoDestino;
  }
  
  /**
   * Retorna a descri��o do tipo de d�bito de destino
   * @return $sDescricaoTipoDestino
   */
  public function getDescricaoTipoDestino() {
    return $this->sDescricaoTipoDestino;
  }

  /**
   * Define a descri��o do tipo de d�bito de destino
   * @param $sDescricaoTipoDestino
   */
  public function setDescricaoTipoDestino($sDescricaoTipoDestino) {
    $this->sDescricaoTipoDestino = $sDescricaoTipoDestino;
  }
  
  /**
   * Define o Codigo do processo Externo vinculado ao cr�dito
   * @param $iCodigoProcessoExterno
   */
  public function setCodigoProcessoExterno($iCodigoProcessoExterno) {
    $this->iCodigoProcessoExterno = $iCodigoProcessoExterno;
  }
  
  /**
   * Retorna o Codigo do processo Externo vinculado ao cr�dito
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
   * Valida se o processo � um processo do sistema
   */
  public function isProcessoSistema() {
    return $this->lProcessoSistema;
  }
  
  /**
   * Valida se o processo � um processo do sistema
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
}