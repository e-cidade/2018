<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

class BemHistoricoMovimentacao {
  
  /**
   * Codigo do HistBem
   * @var integer
   */
  protected $iHistBem;
  
  /**
   * Data
   * @var date
   */
  protected $dtData;
  
  /**
   * C�digo Departamento
   * @var integer
   */
  protected $iDepartamento;
  
  /**
   * C�digo Situa��o
   * @var integer
   */
  protected $iCodigoSituacao;
  
  /**
   * Descri��o da Situa��o
   * @var string
   */
  protected $sDescricaoSituacao;
  
  /**
   * Hist�rico do Bem
   * @var string
   */
  protected $sHistorico;
  
  
  /**
   * M�todo Construtor
   * Seta valores nas propriedades do model da acordo com o c�digo do HistBem passado
   * @param integer $iHistBem
   */
  public function __construct($iHistBem = '') {
    
    if (!empty($iHistBem)) {

      $oDaoHistBem   = db_utils::getDao("histbem");
      $sCampoHistBem = "histbem.*, situabens.t70_descr";
      $sSqlHistBem   = $oDaoHistBem->sql_query($iHistBem, $sCampoHistBem);
      $rsHistBem     = $oDaoHistBem->sql_record($sSqlHistBem);
      
      if ($oDaoHistBem->numrows > 0) {
        
        $oDadoHistBem             = db_utils::fieldsMemory($rsHistBem, 0);
        $this->iHistBem           = $oDadoHistBem->t56_histbem;
        $this->dtData             = $oDadoHistBem->t56_data;
        $this->iDepartamento      = $oDadoHistBem->t56_depart;
        $this->iCodigoSituacao    = $oDadoHistBem->t56_situac;
        $this->sDescricaoSituacao = $oDadoHistBem->t70_descr;
        $this->sHistorico         = $oDadoHistBem->t56_histor;
      }
    }
  }
  
  /**
   * SetCodigo
   * Seta valor na propriedade iHistBem
   * @param integer $iCodHistBem
   */
  protected function setCodigo($iCodHistBem) {
    $this->iHistBem = $iCodHistBem;
  }
  
  /**
   * SetData
   * Seta valor na propriedade dtData
   * @param date $dtData
   */
  public function setData($dtData) {
    $this->dtData = $dtData;
  }
  
  /**
   * SetDepartamento
   * Seta valor na propriedade iDepartamento
   * @param integer $iDepartamento
   */
  public function setDepartamento($iDepartamento) {
    $this->iDepartamento = $iDepartamento;
  }
  
  /**
   * SetCodigoSituacao
   * Seta valor na propriedade iCodSituacao
   * @param integer $iCodigoSituacao
   */
  public function setCodigoSituacao($iCodigoSituacao) {
    $this->iCodigoSituacao = $iCodigoSituacao;
  }
  
  /**
   * SetDescricaoSituacao
   * Seta valor na propriedade sDescSituacao
   * @param string $sDescricaoSituacao
   */
  public function setDescricaoSituacao($sDescricaoSituacao) {
    $this->sDescricaoSituacao = $sDescricaoSituacao;
  }
  
  /**
   * SetHistorico
   * Seta valor na propriedade sHistorico
   * @param string $sHistorico
   */
  public function setHistorico($sHistorico) {
    $this->sHistorico = $sHistorico;
  }
  
  
  /**
   * GetCodigo
   * @return integer Codigo HistBem
   */
  public function getCodigo() {
    return $this->iHistBem;
  }
  
  /**
   * GetData
   * @return date 
   */
  public function getData() {
    return $this->dtData;
  }
  
  /**
   * GetDepartamento
   * @return integer C�digo do Departamento
   */
  public function getDepartamento() {
    return $this->iDepartamento;
  }
  
  /**
   * GetCodigoSituacao
   * @return integer C�digo da Situa��o
   */
  public function getCodigoSituacao() {
    return $this->iCodigoSituacao;
  }
  
  /**
   * GetDescricaoSituacao
   * @return string Descri��o da Situa��o
   */
  public function getDescricaoSituacao() {
    return $this->sDescricaoSituacao;
  }
  
  /**
   * GetHistorico
   * @return string Hist�rico do HistBem
   */
  public function getHistorico() {
    return $this->sHistorico;
  }
  
  /**
   * Persiste o Historico na base de dados
   */
  public function salvar($iBem) {
    
    
    $oDaoHistBem             = db_utils::getDao("histbem");
    $oDaoHistBem->t56_data   = $this->getData();
    $oDaoHistBem->t56_depart = $this->getDepartamento();
    $oDaoHistBem->t56_histor = $this->getHistorico();
    $oDaoHistBem->t56_situac = $this->getCodigoSituacao();
    
    if (!empty($this->iHistBem)) {
      
      $oDaoHistBem->t56_histbem = $this->iHistBem;
      $oDaoHistBem->alterar($this->iHistBem);
    } else {
      
      $oDaoHistBem->t56_codbem = $iBem;
      $oDaoHistBem->incluir(null);
    }
    
    if ($oDaoHistBem->erro_status == 0) {
      
      throw new Exception('Erro ao salvar hist�rico do bem.');
    }
  }
}
?>