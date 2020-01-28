<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * garantias de contratos
 *@package contratos
 */
class AcordoGarantia  {
  
  /**
   * Código da Penalidade
   *
   * @var integer
   */
  private $iCodigo = null;
  
  /**
   * Descrição da Penalidade
   *
   * @var string
   */
  private $sDescricao = '';
  
  /**
   * Observação da Penalidade
   *
   * @var string
   */
  private $sObservacao = '';
  
  /**
   * Texto Padrão da Penalidade
   *
   * @var string
   */
  private $sTextoPadrao = '';
  
  /**
   * Data Limite da Penalidade
   *
   * @var string
   */
  private $dtDataLimite = '';
  
  private $aTiposContratos = array();
  /**
   * 
   * 
   * @param integer $iCodigo
   */
  function __construct($iCodigoGarantia = null) {
    
    if (!empty($iCodigoGarantia)) {
      
      $oDaoAcordoGarantia           = db_utils::getDao("acordogarantia");
      $oDaoAcordoGarantiaAcordoTipo = db_utils::getDao("acordogarantiaacordotipo");
      
      $sCampos                 = "acordogarantia.*";
      $sWhere                  = "ac11_sequencial = {$iCodigoGarantia}";
      $sSqlAcordoGarantia      = $oDaoAcordoGarantia->sql_query(null,$sCampos,null,$sWhere);
      $rsSqlAcordoGarantia     = $oDaoAcordoGarantia->sql_record($sSqlAcordoGarantia);
      $iNumRowsAcordoGarantia  = $oDaoAcordoGarantia->numrows;
      
      
      if ($iNumRowsAcordoGarantia == 0) {
        throw  new Exception("Nenhum Registro Encontrado para a Garantia {$iCodigoGarantia}!");
      }
      
      $oPenalidade = db_utils::fieldsMemory($rsSqlAcordoGarantia, 0);
          
      $this->setCodigo($oPenalidade->ac11_sequencial);
      $this->setDescricao($oPenalidade->ac11_descricao);
      $this->setObservacao($oPenalidade->ac11_obs);
      $this->setTextoPadrao($oPenalidade->ac11_textopadrao);
      $this->setDataLimite($oPenalidade->ac11_validade);
      
      $sCampos                          = "acordogarantiaacordotipo.*";
      $sWhere                           = "ac05_acordogarantia = {$iCodigoGarantia}";
      $sSqlAcordoGarantiaAcordoTipo     = $oDaoAcordoGarantiaAcordoTipo->sql_query(null,$sCampos,null,$sWhere);
      $rsSqlAcordoGarantiaAcordoTipo    = $oDaoAcordoGarantiaAcordoTipo->sql_record($sSqlAcordoGarantiaAcordoTipo);
      $iNumRowsAcordoGarantiaAcordoTipo = $oDaoAcordoGarantiaAcordoTipo->numrows;
      
      
      if ($iNumRowsAcordoGarantiaAcordoTipo == 0) {
        throw  new Exception("Nenhum Registro Encontrado para a Garantia {$iCodigoGarantia}!");
      }
      
      /*
       * For que insere os TIpos de acordos no objeto
       */
      for ($iInd = 0; $iInd < $iNumRowsAcordoGarantiaAcordoTipo; $iInd++) {

        $oAcordoGarantiaAcordoTipo = db_utils::fieldsMemory($rsSqlAcordoGarantiaAcordoTipo, $iInd);
        $this->addTipoContrato($oAcordoGarantiaAcordoTipo->ac05_acordotipo);
      }
    }
  }
  
  /**
   * Retorna Código Penalidade
   * 
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta Código Penalidade
   * 
   * @param integer $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna valor da Descrição da Penalidade
   * 
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta Descrição da Penalidade
   * 
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna Obeservação da Penalidade
   * 
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
   * Seta Observação da Penalidade
   * 
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Retorna Texto Padrão da Penalidade
   * 
   * @return string
   */
  public function getTextoPadrao() {
    return $this->sTextoPadrao;
  }
  
  /**
   * Seta Texto Padrão da Penalidade
   * 
   * @param string $sTextoPadrao
   */
  public function setTextoPadrao($sTextoPadrao) {
    $this->sTextoPadrao = $sTextoPadrao;
  }
  
  /**
   * Retorna Data Limite da Penalidade 
   * 
   * @return string
   */
  public function getDataLimite() {
    return $this->dtDataLimite;
  }
  
  /**
   * Seta Data Limite da Penalidade
   * 
   * @param string $dtDataLimite
   */
  public function setDataLimite($dtDataLimite) {
    $this->dtDataLimite = $dtDataLimite;
  }
  
  /**
   * Insere Tipo Acordo no Objeto
   *
   * @param Integer $iCodigoTipo
   */
  public function addTipoContrato($iCodigoTipo) {
    
    if (!in_array($iCodigoTipo, $this->aTiposContratos)) {
      $this->aTiposContratos[] = $iCodigoTipo;
    }
  }
  
/**
 * Remove um tipode contrato da garantia
 *
 * @param integer $iCodigo codigo do tipo do contrato
 * @return AcordoGarantia 
 */
  public function removeTipoContrato($iTiposContratos = null) {
    
    if (!empty($iTiposContratos)) {
      
      for ($i = 0; $i < count($this->aTiposContratos); $i++ ) {
        if ($this->aTiposContratos[$i] ==  $iTiposContratos) {
          unset($this->aTiposContratos[$i]);  
        }
      }
    } else {
      $this->aTiposContratos = array();
    }
    return $this;
  }
  
  /**
   * Retorna os Tipos de Contratos
   *
   * @return array $this->aTiposContratos 
   */
  public function getTiposContratos() {
    return $this->aTiposContratos;
  }
  
  /**
   * Exclui os dados das tabelas acordogarantia e acordogarantiaacordotipo
   */
  public function excluir() {
    
    $oDaoAcordoGarantia           = db_utils::getDao("acordogarantia");
    $oDaoAcordoGarantiaAcordoTipo = db_utils::getDao("acordogarantiaacordotipo");
    
    $sMsgErro = "Erro:\n\n Não foi possível excluir dados da garantia.\n\n";
    
    $iGetCodigo = $this->getCodigo();
    if (empty($iGetCodigo)) {
       throw new Exception("Erro:\n\n Código da garantia não informado. Exclusão cancelada!");
    }
    
    $oDaoAcordoGarantiaAcordoTipo->excluir(null, "ac05_acordogarantia = {$this->getCodigo()}");
    if ($oDaoAcordoGarantiaAcordoTipo->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoGarantiaAcordoTipo->erro_msg);
    }
    
    $oDaoAcordoGarantia->excluir(null,"ac11_sequencial = {$this->getCodigo()}");
    if ($oDaoAcordoGarantia->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoGarantia->erro_msg);
    }
    
  }
  
  /**
   * Persiste os dados da garantia da base de dados
   *
   * @return AcordoGarantia
   */
  public function save() {
        
    $oDaoAcordoGarantia           = db_utils::getDao("acordogarantia");
    $oDaoAcordoGarantiaAcordoTipo = db_utils::getDao("acordogarantiaacordotipo");
    
    $sMsgErro = "Erro:\n\n Não foi possível salvar dados da garantia.\n\n";
    
    $oDaoAcordoGarantia->ac11_descricao   = $this->getDescricao();
    $oDaoAcordoGarantia->ac11_obs         = $this->getObservacao();
    $oDaoAcordoGarantia->ac11_textopadrao = $this->getTextoPadrao();
    $oDaoAcordoGarantia->ac11_validade    = $this->getDataLimite();
    
    $iGetCodigo = $this->getCodigo();
    if (empty($iGetCodigo)) {
      
      $oDaoAcordoGarantia->incluir($this->getCodigo());
      $this->setCodigo($oDaoAcordoGarantia->ac11_sequencial);
    } else {
      
      $oDaoAcordoGarantia->ac11_sequencial = $this->getCodigo();
      $oDaoAcordoGarantia->alterar($this->getCodigo());
    }
    
    if ($oDaoAcordoGarantia->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoGarantia->erro_msg);
    }
    
    $oDaoAcordoGarantiaAcordoTipo->excluir(null, "ac05_acordogarantia = {$this->getCodigo()}");
    if ($oDaoAcordoGarantiaAcordoTipo->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoAcordoGarantiaAcordoTipo->erro_msg);
    }
    
    $aGetTipoContratos = $this->getTiposContratos();
    
    foreach ($aGetTipoContratos as $iTipo) {
      
      $oDaoAcordoGarantiaAcordoTipo->ac05_acordotipo       = $iTipo;
      $oDaoAcordoGarantiaAcordoTipo->ac05_acordogarantia = $this->getCodigo();
      $oDaoAcordoGarantiaAcordoTipo->incluir(null);
      
      if ($oDaoAcordoGarantiaAcordoTipo->erro_status == 0) {
        throw new Exception($sMsgErro.$oDaoAcordoGarantiaAcordoTipo->erro_msg);
      }
    }
    
    return $this;
  }
}
?>
