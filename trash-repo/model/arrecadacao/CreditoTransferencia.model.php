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
 * Model Crédito Transferência
 *
 * Classe responsavel por realizar uma transferencia de créditos no sistema
 *
 * @author everton.heckler <everton.heckler@dbseller.com.br>
 * @package Arrecadacao
 *
 * @version $
 *
 */

class CreditoTransferencia {
  
  /**
   * Instância do objeto cgm com o contribuinte de destino do crédito
   * @var CgmBase
   */
  private $oCgmDestino;
  
  /**
   * Observação referente ao lançamento do crédito
   * @var string
   */
  private $sObservacao;
  
  /**
   * Valor do crédito para transferência
   * @var number
   */
  private $nValor;
  
  /**
   * Instância do objeto DBDate com a data da transferência
   * @var DBDate
   */
  private $oDataTransferencia;
  
  /**
   * Hora da transferência
   * @var string
   */
  private $sHoraTransferencia;
  
  /**
   * Usuário que efetuou a transferência
   * @var integer
   */
  private $iUsuario;
  
  /**
   * Instituição do crédito
   * @var integer
   */
  private $iInstituicao;
  
  /**
   * Informa se o processo anexado ao crédito é do sistema
   * @var boolean
   */
  private $lProcessoSistema;
  
  /**
   * Código sequencial do processo externo
   * @var integer
   */
  private $iCodigoProcessoExterno;
  
  /**
   * Número do processo externo 
   * @var string
   */
  private $sNumeroProcessoExterno;
  
  /**
   * Nome do titular do processo externo
   * @var string
   */
  private $sNomeTitularProcessoExterno;
  
  /**
   * Instância do objeto DBDate contendo a data do processo externo
   * @var DBDate
   */
  private $oDataProcessoExterno;
  
  /**
   * Instância do objeto processoProtocolo contendo dados do processo do sistema vinculado ao crédito
   * @var processoProtocolo
   */
  private $oProcessoProtocolo;
  
  /**
   * Contruct da classe
   */
  public function __construct() {
    
  }
  
  /**
   * Rotina de processamento da transferência de crédito
   * @throws Exception Instância do crédito a ser transferido
   * @throws Exception Instância do cgm do destino do crédito
   * @throws Exception Valor a ser transferido
   */
  public function salvar() {
    
    if (!$this->getCredito() instanceof CreditoManual || $this->getCredito()->getCodigoCredito() == '') {
    
      throw  new Exception("Crédito não informado para transferência.");
    }
    
    if ($this->getCredito()->getCgm()->getCodigo() == $this->getCgmDestino()->getCodigo()) {
    	
    	throw new Exception ('CGM de origem é igual ao cgm de destino.');
    	
    }
    
    if (!$this->getCgmDestino() instanceof CgmBase || $this->getCgmDestino()->getCodigo() == '') {
    
      throw  new Exception("CGM não informado ou inválido para a inclusão do crédito");
      
    }
    
    $oDaoAbatimento = db_utils::getDao('abatimento');
    $sSqlAbatimento = $oDaoAbatimento->sql_query_file($this->getCredito()->getCodigoCredito());
    $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);
    
    if ($oDaoAbatimento->numrows == 0) {
      throw new Exception("Crédito {$this->getCredito()->getCodigoCredito()} não encontrado.");
    }
    
    /**
     * Abate o valor transferido do crédito original 
     */
    $oAbatimento                          = db_utils::fieldsMemory($rsAbatimento, 0);
    
    $nNovoValorDisponivel                 = $oAbatimento->k125_valordisponivel - $this->getValor();
    
    $oDaoAbatimento->k125_sequencial      = $oAbatimento->k125_sequencial;
    $oDaoAbatimento->k125_valordisponivel = "$nNovoValorDisponivel";
    
    $oDaoAbatimento->alterar($oDaoAbatimento->k125_sequencial);
    if ($oDaoAbatimento->erro_status == "0") {
      throw new Exception("Erro ao atualizar dados do crédito {$oAbatimento->k125_sequencial}. Erro: (abatimento): {$oDaoAbatimento->erro_msg}");
    }
    
    /**
     * Gerar novo crédito 
     */
    
    $oCreditoManual = new CreditoManual();
    
    if (count(RegraCompensacao::getRegrasCompensacaoPorTipo(RegraCompensacao::TRANSFERENCIA)) == 0) {
      throw new Exception ('Nenhuma regra de compensação para o tipo transferência foi configurada. Verifique');
    }
    
    foreach (RegraCompensacao::getRegrasCompensacaoPorTipo(RegraCompensacao::TRANSFERENCIA) as $oRegraCompensacao) {
      $oCreditoManual->adicionarRegra($oRegraCompensacao);
    }
    
    $oCreditoManual->setDataLancamento  (new DBDate($this->getDataTransferencia()->getDate()));
    $oCreditoManual->setHora            ($this->getHoraTransferencia());
    $oCreditoManual->setUsuario         ($this->getUsuario());
    $oCreditoManual->setInstituicao     ($this->getInstituicao());
    $oCreditoManual->setValor           ($this->getValor());
    $oCreditoManual->setObservacao      ($this->getObservacao());
    $oCreditoManual->setPercentual      (100);
    $oCreditoManual->setCgm             ($this->getCgmDestino());
    
    if (!empty($oParam->dDataExpiracao)) {
      $oCreditoManual->setDataExpiracao (new DBDate($oParam->dDataExpiracao));
    }

    $oCreditoManual->setProcessoSistema($this->isProcessoSistema());
    
    if ($this->isProcessoSistema()) {
      $oCreditoManual->setProcessoProtocolo($this->getProcessoProtocolo());
    }
    $oCreditoManual->setNumeroProcessoExterno     ($this->getNumeroProcessoExterno());
    $oCreditoManual->setNomeTitularProcessoExterno($this->getNomeTitularProcessoExterno());
    
    if ($this->getDataProcessoExterno() != '') {
      $oCreditoManual->setDataProcessoExterno     ($this->getDataProcessoExterno());
    }

    $oCreditoManual->salvar();
    
    $oDaoAbatimentoUtilizacao                      = db_utils::getDao('abatimentoutilizacao');
    $oDaoAbatimentoUtilizacao->k157_tipoutilizacao = 1;
    $oDaoAbatimentoUtilizacao->k157_data           = $this->getDataTransferencia()->getDate();
    $oDaoAbatimentoUtilizacao->k157_valor          = $this->getValor();
    $oDaoAbatimentoUtilizacao->k157_hora           = $this->getHoraTransferencia();
    $oDaoAbatimentoUtilizacao->k157_usuario        = $this->getUsuario();
    $oDaoAbatimentoUtilizacao->k157_abatimento     = $this->getCredito()->getCodigoCredito();
    $oDaoAbatimentoUtilizacao->incluir(null);
    
    if ($oDaoAbatimentoUtilizacao->erro_status == '0') {
      throw new Exception('Erro ao incluir registro de utilização do crédito. ERRO: (abatimentoutilizacao)' . $oDaoAbatimentoUtilizacao->erro_msg);
    }
    
    $oDaoAbatimentoTransferencia = db_utils::getDao('abatimentotransferencia');
    $oDaoAbatimentoTransferencia->k158_abatimentoutilizacao = $oDaoAbatimentoUtilizacao->k157_sequencial;
    $oDaoAbatimentoTransferencia->k158_abatimentoorigem     = $this->getCredito()->getCodigoCredito();
    $oDaoAbatimentoTransferencia->k158_abatimentodestino    = $oCreditoManual->getCodigoCredito();
    $oDaoAbatimentoTransferencia->incluir(null);
    
    if ($oDaoAbatimentoTransferencia->erro_status == '0') {
      throw new Exception('Erro ao incluir registro de transferência do crédito. ERRO: (abatimentotransferencia)' . $oDaoAbatimentoTransferencia->erro_msg);
    }
    
    
  }
  
  /**
   * Define a instituição do crédito
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }
  
  /**
   * Retorna a instituição do crédtio
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }
  
  /**
   * Define o usuario que efetuou a transferência
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Retorna o código do usuário que efetuou a tranferência
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }
  
  /**
   * Define a data da transferência do crédito
   * @param DBDate $oDataTransferencia
   */
  public function setDataTransferencia(DBDate $oDataTransferencia) {
    $this->oDataTransferencia = $oDataTransferencia;
  }
  
  /**
   * Retorna uma instância do objeto DBData com a data do sistema
   * @return DBDate
   */
  public function getDataTransferencia() {
    return $this->oDataTransferencia;
  }
  
  /**
   * Define a hora da transferência do crédito
   * @param string $sHoraTransferencia
   */
  public function setHoraTransferencia($sHoraTransferencia) {
    $this->sHoraTransferencia = $sHoraTransferencia;
  }
  
  /**
   * Retorna a hora da transferência do crédito
   * @return string
   */
  public function getHoraTransferencia() {
    return $this->sHoraTransferencia;
  }
  
  /**
   * Define o cgm de destino do crédito
   * @param CgmBase $oCgmDestino
   */
  public function setCgmDestino(CgmBase $oCgmDestino) {
    $this->oCgmDestino = $oCgmDestino;
  }
  
  /**
   * Retorna uma instância de CGM
   * @return CgmBase
   */
  public function getCgmDestino() {
    return $this->oCgmDestino;
  }
  
  /**
   * Seta o crédito a ser transferido
   * @param CreditoManual $oCredito
   */
  public function setCredito(CreditoManual $oCredito) {
    $this->oCredito = $oCredito;  
  }
    
  /**
   * Retorna o crédito a ser transferido
   * @return CreditoManual
   */
  public function getCredito() {
    return $this->oCredito;
  }

  /**
   * Retorna a observação referente a transferência
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observação referente a transferencia
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o valor a ser transferido
   * @return number
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Define o valor a ser transferido
   * @param number $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
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
  
}