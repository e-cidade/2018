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


require_once('libs/db_utils.php');
require_once('libs/exceptions/ParameterException.php');
require_once('libs/exceptions/DBException.php');
require_once('std/DBDate.php');
require_once('std/db_stdClass.php');
require_once('libs/db_app.utils.php');
require_once('libs/smtp.class.php');

db_app::import("configuracao.notificacao.*");

/**
 * Classe para Requisição de AIDOF( Autorização de Impressão de DOcumento Fiscal )
 *
 * @package Fiscal
 * @author  Gilton Guma <gilton@dbseller.com.br> 
 *          Everton Heckler <everton.heckler@dbseller.com.br>
 */
class RequisicaoAidof {

  /**
   * Codigo do Aidof
   * 
   * @var integer
   * @access protected
   */
  protected $iId;
  
  /**
   * Quantidade solicitada
   * 
   * @var integer
   * @access protected
   */
  protected $iQuantidadeSolicitada;

  /**
   * Quantidade liberada
   * 
   * @var integer
   * @access protected
   */  
  protected $iQuantidadeLiberada;

  /**
   * Observacao
   *
   * @var string
   * @access protected
   */
  protected $sObservacao;
  
  /**
   * Status
   * 
   * @var string
   * @access protected
   */  
  protected $sStatus;
  
  /**
   * Usuario
   * 
   * @var UsuarioSistema
   * @access protected
   */
  protected $oUsuario;  
  
  /**
   * Nota Fiscal do Aidof
   *
   * @var NotaFiscalISSQN
   * @access protected
   */
  protected $oNota;
  
  /**
   * Data lançamento da requisicao
   *
   * @var Object Datetime
   * @access protected
   */
  protected $oDataLancamento;

  /**
   * Empresa do Aidof (Inscrição Municipal)
   * 
   * @var Empresa
   * @access protected
   */
  protected $oEmpresa;
  
  /**
   * Gráfica
   * 
   * @var Grafica
   * @access protected
   */
  protected $oGrafica;
  
  public function __construct($iCodigoRequisicao = null) {
    
    if (empty($iCodigoRequisicao)) {
      return;
    }
    
    $oDaoRequisicaoAidof = db_utils::getDao('requisicaoaidof');
    $sSqlRequisicaoAidof = $oDaoRequisicaoAidof->sql_query_file($iCodigoRequisicao);
    
    $rsRequisicaoAidof   = db_query($sSqlRequisicaoAidof);
    
    if (!$rsRequisicaoAidof) {
      throw new DBException('Erro ao buscar Requisição Aidof: ' . pg_last_error());
    }
    
    if (pg_num_rows($rsRequisicaoAidof) == 0) {
      throw new DBException('Nenhuma Requisição Aidof encontrada: ' . $iCodigoRequisicao);
    }
    
    $oRequisicaoAidof            = db_utils::fieldsMemory($rsRequisicaoAidof, 0);
    $this->iId                   = $oRequisicaoAidof->y116_id;
    $this->oNota                 = new NotaFiscalISSQN($oRequisicaoAidof->y116_tipodocumento);
    $this->oGrafica              = new Grafica($oRequisicaoAidof->y116_codigografica);
    $this->oEmpresa              = new Empresa($oRequisicaoAidof->y116_inscricaomunicipal);
    $this->oUsuario              = new UsuarioSistema($oRequisicaoAidof->y116_idusuario);
    $this->sObservacao           = $oRequisicaoAidof->y116_observacao;
    $this->iQuantidadeSolicitada = $oRequisicaoAidof->y116_quantidadesolicitada;
    $this->iQuantidadeLiberada   = $oRequisicaoAidof->y116_quantidadeliberada;
    $this->sStatus               = $oRequisicaoAidof->y116_status;
  }
  
  public function getId() {
    return $this->iId;
  }
  
  public function setId($iId) {
    $this->iId = $iId;
  }
  
  public function getQuantidadeSolicitada() {
    return $this->iQuantidadeSolicitada;
  }
  
  public function setQuantidadeSolicitada($iQuantidadeSolicitada) {
    $this->iQuantidadeSolicitada = $iQuantidadeSolicitada;
  }  

  public function getQuantidadeLiberada() {
    return $this->iQuantidadeLiberada;
  }
  
  public function setQuantidadeLiberada($iQuantidadeLiberada) {
    $this->iQuantidadeLiberada = $iQuantidadeLiberada;
  }
  
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  public function getStatus() {
    return $this->sStatus;
  }
  
  public function setStatus($sStatus) {
    $this->sStatus = $sStatus;
  }

  public function getUsuario(){
    return $this->oUsuario;
  }
  
  public function setUsuario($iIdUsuario) {
    $this->oUsuario = new UsuarioSistema($iIdUsuario);
  }
  
  public function getNota() {
    return $this->oNota;
  }
  
  public function setNota($oNota) {
    $this->oNota = $oNota;
  }
  
  public function getDataLancamento() {
    return $this->oDataLancamento;
  }
  
  public function setDataLancamento($oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;
  }
  
  public function getEmpresa() {
    return $this->oEmpresa;
  }
  
  public function setEmpresa($oEmpresa) {
    
    if (!$oEmpresa->isAtiva()) {
      throw new BusinessException('Empresa baixada.');
    }

    $this->oEmpresa = $oEmpresa;
  }

  public function getGrafica() {
    return $this->oGrafica;
  }
  
  public function setGrafica($oGrafica) {
    $this->oGrafica = $oGrafica;
  }
  
  public function salvar() {
    
    $oRetorno = new stdClass();
    
    try {
      
      $oDaoRequisicaoAidof = new cl_requisicaoaidof();
      
      if (!is_null($this->iQuantidadeLiberada)) {
        $oDaoRequisicaoAidof->y116_quantidadeLiberada = $this->iQuantidadeLiberada;
      }
      
      if (is_object($this->oUsuario) && ($this->oUsuario->getIdUsuario())) {
        $oDaoRequisicaoAidof->y116_idusuario = $this->oUsuario->getIdUsuario();
      }
      
      if (is_object($this->oNota) && ($this->oNota->getCodigo())) {
        $oDaoRequisicaoAidof->y116_tipodocumento = $this->oNota->getCodigo();
      }
      
      if (is_object($this->oGrafica) && ($this->oGrafica->getCodigo())) {
        $oDaoRequisicaoAidof->y116_codigografica = $this->oGrafica->getCodigo();
      }
      
      if (is_object($this->oDataLancamento) && $this->oDataLancamento->getDate()) {
        $oDaoRequisicaoAidof->y116_datalancamento = $this->oDataLancamento->getDate();
      } else {
        $oDaoRequisicaoAidof->y116_datalancamento = date('Y-m-d');
      }
      
      if (!is_null($this->iQuantidadeSolicitada)) {
        $oDaoRequisicaoAidof->y116_quantidadesolicitada = $this->iQuantidadeSolicitada;
      }
      
      if ($this->sStatus) {
        $oDaoRequisicaoAidof->y116_status = strtoupper($this->sStatus);
      } else {
        $oDaoRequisicaoAidof->y116_status = 'P';
      }
      
      if (!is_null($this->sObservacao)) {
        $oDaoRequisicaoAidof->y116_observacao = $this->sObservacao;
      }
      
      if ($this->oEmpresa->getInscricao()) {
        $oDaoRequisicaoAidof->y116_inscricaomunicipal = $this->oEmpresa->getInscricao();
      }
      
      /**
       * Inclui ou altera AIDOF
       */
      if (empty($this->iId)) {
        
        if ($oDaoRequisicaoAidof->incluir(null)) {
          $oRetorno->sMensagem = 'Requisicao de Aidof Gerada.';
        } else {
          $oRetorno->sMensagem = $oDaoRequisicaoAidof->erro_sql;
        }
      } else {
        
        $oDaoRequisicaoAidof->y116_id = $this->iId;
        $oDaoRequisicaoAidof->alterar($oDaoRequisicaoAidof->y116_id);
        
        $oRetorno->sMensagem = 'Requisicao de Aidof Alterada.';
      }
            
      $oRetorno->bStatus   = true;
    } catch (Exception $eException){
    
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = $eException->getMessage();
    }
    
    return $oRetorno;
  }
  
  public function consultar($iInscricaoMunicipal, $iTipoDocumento = null) {
    
    if (empty($iInscricaoMunicipal)) {
      return array();
    }
      
    $this->setEmpresa($iInscricaoMunicipal);
    
    $oDaoRequisicaoAidof = new cl_requisicaoaidof();
    $sSqlWhere           = "y116_inscricaomunicipal = {$this->oEmpresa->getInscricao()}"; 
    
    if (!empty($iTipoDocumento)) {
      $sSqlWhere .= " AND y116_tipodocumento = {$iTipoDocumento}";
    }
    
    $sSql                = $oDaoRequisicaoAidof->sql_query(null, '*', 'y116_id DESC', $sSqlWhere);
    $rsRequisicaoAidof   = $oDaoRequisicaoAidof->sql_record($sSql);
    $iNumLinhas          = $oDaoRequisicaoAidof->numrows;
    $aRetorno            = array();
    
    for ($iLinha = 0; $iLinha < $iNumLinhas; $iLinha++) {
      
      $rsRequisicao                     = db_utils::fieldsMemory($rsRequisicaoAidof, $iLinha);
      $oRetorno                         = new stdClass();
      $oRetorno->id                     = $rsRequisicao->y116_id;                  
      $oRetorno->tipodocumento          = $rsRequisicao->y116_tipodocumento;       
      $oRetorno->datalancamento         = $rsRequisicao->y116_datalancamento;      
      $oRetorno->quantidadesolicitada   = $rsRequisicao->y116_quantidadesolicitada;
      $oRetorno->quantidadeliberada     = $rsRequisicao->y116_quantidadeliberada;
      $oRetorno->status                 = $rsRequisicao->y116_status;              
      $oRetorno->observacao             = $rsRequisicao->y116_observacao;          
      $oRetorno->inscricaomunicipal     = $rsRequisicao->y116_inscricaomunicipal;  
      $oRetorno->idusuario              = $rsRequisicao->y116_idusuario;           
      $oRetorno->tipodocumentodescricao = $rsRequisicao->q09_descr;                
      $aRetorno[]                       = $oRetorno; 
    }
    
    return $aRetorno;
  }
  
  /**
   * Altera status da requisição
   * 
   * @param string $sStatus
   *   [C] Cancelada
   *   [L] Liberada
   *   [P] Pendente
   *   [R] Recusada
   * @throws Exception
   * @return boolean
   */
  public function alterarStatus() {
    
    try {
    
      if (empty($this->iId)) {
        throw new ParameterException('Informe o codigo da requisicao');
      }
      
      $sStatus = strtoupper($this->getStatus());
      
      if (empty($sStatus) || !in_array($sStatus, array('C', 'L', 'P', 'R'))) {
        
        throw new ParameterException('Informe um status valido');
      }
      
      $oDaoRequisicaoAidof              = new cl_requisicaoaidof();
      $oDaoRequisicaoAidof->y116_id     = $this->iId;
      $oDaoRequisicaoAidof->y116_status = $sStatus;
      
      switch ($sStatus) {
        case 'R' :
          
          if ($this->getObservacao()) {
            
            throw new ParameterException('Informe uma observacao');
          } else {
            
            $oDaoRequisicaoAidof->y116_observacao = $this->getObservacao();
            $oDaoRequisicaoAidof->y116_idusuario  = db_getsession('DB_id_usuario');
          }
          
          break;
          
        case 'L' :
          
          $oDaoRequisicaoAidof->y116_idusuario    = db_getsession('DB_id_usuario');
          
          break;
          
        case 'C': 
          
          $oDaoRequisicaoAidof->y116_observacao   = 'Cancelado pelo usuario (NFSE)';
          
          break;
          
      }
     
      $oDaoRequisicaoAidof->alterar($oDaoRequisicaoAidof->y116_id);
      
      $oRetorno->bStatus   = true;
      $oRetorno->sMensagem = "status da requisicao alterada";
      
      return $oRetorno;
      
    } catch (Exception $eException){
      
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = $eException->getMessage();
    
      return $oRetorno;
    }
  }
  
  /**
   * Gera Aidof
   * 
   * @throws Exception
   * @return boolean
   */
  public function gerarAidof() {
    
    $oRetorno = new stdClass();
    
    try {
      
      if (empty($this->iId)) {
        throw new ParameterException('Informe o código da requisição');
      }
      
      if (empty($this->iQuantidadeLiberada)) {
        throw new ParameterException('Informe a quantidade à liberar');
      }
      
      /**
       * Nova Aidof
       */
      $oAidof                  = new Aidof();
      $iNotaFinalAidofAnterior = Aidof::getNotaFinalAidof($this->oEmpresa,
                                                          $this->oNota);
      
      $oAidof->setNota($this->oNota);
      $oAidof->setEmpresa($this->oEmpresa);
      $oAidof->setNumeroInicial($iNotaFinalAidofAnterior + 1);
      $oAidof->setGrafica($this->oGrafica);
      $oAidof->setNumeroFinal($this->getQuantidadeLiberada() + $iNotaFinalAidofAnterior);
      $oAidof->setObservacoes("Convertido da requisição código: {$this->getId()}");
      $oAidof->setQuantidadeSolicitada($this->getQuantidadeSolicitada());
      $oAidof->setQuantidadeLiberada($this->getQuantidadeLiberada());
      $oAidof->setCancelado('false');
      $oAidof->setDataLancamento(date('Y-m-d', db_getsession('DB_datausu')));
      $oAidof->salvar();
      
      /**
       * Alterando Status da requisição
       */
      $oDaoRequisicaoAidof     = new cl_requisicaoaidof();
   
      $oDaoRequisicaoAidof->y116_status             = 'L';
      $oDaoRequisicaoAidof->y116_idusuario          = db_getsession('DB_id_usuario');
      $oDaoRequisicaoAidof->y116_quantidadeLiberada = $this->getQuantidadeLiberada();
      $oDaoRequisicaoAidof->y116_observacao         = $this->getObservacao();
      $oDaoRequisicaoAidof->y116_codigoaidof        = $oAidof->getCodigo();
      
      $oDaoRequisicaoAidof->alterar($this->iId);
      
      $sMensagemEnvio   = '';
      $sMensagemEmpresa = '';
      $sMensagemGrafica = '';
      $sVirgula         = '';
      
      if (($this->oEmpresa->getCgmEmpresa()->getEmailComercial()) || ($this->oGrafica->getEmailComercial())) {
        $sMensagemEnvio = 'Mensagem Enviada para: ';
      }
      
      if (($this->oEmpresa->getCgmEmpresa()->getEmailComercial()) && ($this->oGrafica->getEmailComercial())) {
        $sVirgula = ',';
      }
      
      if ($this->oEmpresa->getCgmEmpresa()->getEmailComercial() && 1==2) {
      
        $aMensagens = array();
        
        // envia mensagem para a empresa
        $oMensagemEmpresa   = new NotificacaoMensagem();
        
        $sMensagemEnvioEmpresa  = "\n Liberação de aidof. ";
        $sMensagemEnvioEmpresa .= "\n Código: " . $oAidof->getCodigo();
        $sMensagemEnvioEmpresa .= "\n Quantidade Liberada: " . $this->getQuantidadeLiberada();
        $oMensagemEmpresa->setMensagem($sMensagemEnvioEmpresa);
        
        $oMensagemEmpresa->setAssunto('Liberação de Aidof');
        $oMensagemEmpresa->setEmailDestino($this->oEmpresa->getCgmEmpresa()->getEmailComercial());
        $oMensagemEmpresa->setEmailOrigem(db_stdClass::getDadosInstit(db_getsession("DB_instit"))->email);
        NotificacaoBuilder::getNotificacoesPorMensagem($oMensagemEmpresa);
        
        $sMensagemEmpresa = 'Empresa';
      }

      if ($this->oGrafica->getEmailComercial() && 1==2) {
        
        // envia mensagem para a grafica
        $oMensagemGrafica   = new NotificacaoMensagem();
        
        $oMensagemEnvioGrafica  = "\n Liberação de aidof. ";
        $oMensagemEnvioGrafica .= "\n Código: " . $oAidof->getCodigo();
        $oMensagemEnvioGrafica .= "\n Empresa: " . $this->oEmpresa->getCgmEmpresa()->getNumeroComercial();
        $oMensagemEnvioGrafica .= "\n Inscrição Estadual: " . $this->oEmpresa->getInscricao();
        $oMensagemEnvioGrafica .= "\n Quantidade Liberada: " . $this->getQuantidadeLiberada();
        
        $oMensagemGrafica->setMensagem($oMensagemEnvioGrafica);
        $oMensagemGrafica->setAssunto("Liberação de Aidof");
        $oMensagemGrafica->setEmailDestino($this->oGrafica->getEmailComercial());
        $oMensagemGrafica->setEmailOrigem(db_stdClass::getDadosInstit(db_getsession("DB_instit"))->email);
        NotificacaoBuilder::getNotificacoesPorMensagem($oMensagemGrafica);

        $sMensagemGrafica = 'Gráfica';
      }

      $oRetorno->bStatus   = true;
      $oRetorno->sMensagem = "Aidof Código {$oAidof->getCodigo()}, gerada com sucesso.". $sMensagemEnvio   .
                                                                                         $sMensagemEmpresa .
                                                                                         $sVirgula         .
                                                                                         $sMensagemGrafica;
      return $oRetorno;
      
    } catch (Exception $eException){
    
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = $eException->getMessage();
      
      return $oRetorno;
    }
  }
  
  /**
   * Bloquear requisição de Aidof
   * @throws Exception
   * @throws DBException
   * @return boolean
   */
  public function bloquearRequisicao() {
  
    $oRetorno = new stdClass();
    
    try {
    
      if (empty($this->iId)) {
        throw new ParameterException('Informe o código da requisição');
      }
      
      if (empty($this->sObservacao)) {
        throw new ParameterException('Informe uma observação');
      }
      
      /**
       * Alterando Status da requisição
       */
      $oDaoRequisicaoAidof     = new cl_requisicaoaidof();
      
      $oDaoRequisicaoAidof->y116_status      = 'B';
      $oDaoRequisicaoAidof->y116_idusuario   = db_getsession('DB_id_usuario');
      $oDaoRequisicaoAidof->y116_observacao  = $this->getObservacao();
      $oDaoRequisicaoAidof->y116_quantidadeLiberada = null;
      
      $oDaoRequisicaoAidof->alterar($this->iId);
      
      $oRetorno->bStatus   = true;
      $oRetorno->sMensagem = "Requisição Código {$oDaoRequisicaoAidof->y116_id}, bloqueado com sucesso.";
      
      return $oRetorno;
    
    } catch (Exception $eException){
      
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = $eException->getMessage();
      
      return $oRetorno;
    }
    
    return true;
  }
  
}