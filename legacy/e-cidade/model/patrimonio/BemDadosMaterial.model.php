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
 * Classe de controle dos dados de compra do bem 
 */
final class BemDadosMaterial {
  
  protected $iBem;
  
  protected $sNotaFiscal;
  
  protected $iEmpenho;
  
  protected $sOrdemCompra;
  
  protected $dtGarantia;
  
  protected $sNomeCredor;
  
  protected $isEmpenhoSistema;
  
  /**
   * 
   */
  function __construct($iBem = null) {
    
    if (!empty($iBem)) {
      
      $oDaoBensMaterial = new cl_bensmater();
      $sSqlDados        = $oDaoBensMaterial->sql_query_bensmater($iBem);
      $rsDados          = $oDaoBensMaterial->sql_record($sSqlDados);
      if ($oDaoBensMaterial->numrows > 0) {
        
        $oDadosMaterial = db_utils::fieldsMemory($rsDados, 0);
        $this->setDataGarantia(db_formatar($oDadosMaterial->t53_garant, "d"));
        $this->setEmpenho($oDadosMaterial->t53_empen);
        if ($oDadosMaterial->e60_numemp != '') {
          
          $this->setEmpenho($oDadosMaterial->e60_numemp);
          $this->setEmpenhoSistema(true); 
          $this->sNomeCredor = $oDadosMaterial->z01_nome;
        }
        
        $this->setNotaFiscal($oDadosMaterial->t53_ntfisc);
        $this->setOrdemCompra($oDadosMaterial->t53_ordem);
        $this->setBem($oDadosMaterial->t53_codbem);
        unset($oDadosMaterial);
      }
    }
  }
  /**
   * @return unknown
   */
  public function getDataGarantia() {

    return $this->dtGarantia;
  }
  
  /**
   * @return unknown
   */
  public function getBem() {
    return $this->iBem;
  }
  
  /**
   * @return unknown
   */
  public function getEmpenho() {
    return $this->iEmpenho;
  }
  
  /**
   * @return unknown
   */
  public function isEmpenhoSistema() {
    return $this->isEmpenhoSistema;
  }
  
  /**
   * Retorna a nota fiscal do bem 
   * @return string
   */
  public function getNotaFiscal() {
    return $this->sNotaFiscal;
  }
  
  /**
   * Retorna a Ordem de compra do bem 
   * @return string
   */
  public function getOrdemCompra() {
    return $this->sOrdemCompra;
  }
  
  /**
   * Define a data da Garantia
   * @param  string $dtGarantia formato DD/MM/YYYY
   */
  public function setDataGarantia($dtGarantia) {
    $this->dtGarantia = $dtGarantia;
  }
  
  /**
   * Define o Codigo do Bem
   * @param integer $iBem
   */
  public function setBem($iBem) {
    $this->iBem = $iBem;
  }
  
  /**
   * Define o Numero do Empenho
   * @param integer $iEmpenho
   */
  public function setEmpenho($iEmpenho) {
    $this->iEmpenho = $iEmpenho;
  }
  
  /**
   * Define se o Empenho é do sistema
   * @param boolean $isEmpenhoSistema
   */
  public function setEmpenhoSistema($isEmpenhoSistema) {
    $this->isEmpenhoSistema = $isEmpenhoSistema;
  }
  
  /**
   * Define o Numero da Nota Fiscal
   * @param string $sNotaFiscal
   */
  public function setNotaFiscal($sNotaFiscal) {
    $this->sNotaFiscal = $sNotaFiscal;
  }
  
  /**
   * Define a a ordem  de Compra
   * @param string $sOrdemCompra
   */
  public function setOrdemCompra($sOrdemCompra) {
    $this->sOrdemCompra = $sOrdemCompra;
  }

  /**
   * Persiste os dados na base de dados
   */
  public function salvar() {
    
    $oDaoBensMaterialEmpenho = new cl_bensmaterialempempenho();
    $oDaoBensMaterial        = new cl_bensmater();
    if (!empty($this->iBem)) {
      
      $oDaoBensMaterialEmpenho->excluir(null, "t11_bensmaterial={$this->iBem}");
      if ($oDaoBensMaterialEmpenho->erro_status == 0) {
        throw new Exception("Erro ao salvar dados o material do bem.\n{$oDaoBensMaterialEmpenho->erro_msg}");
      }
      
      $oDaoBensMaterial->excluir($this->getBem());
      if ($oDaoBensMaterial->erro_status == 0) {
        throw new Exception("Erro ao salvar dados o material do bem.\n{$oDaoBensMaterial->erro_msg}");
      }
    }
    

    /**
     * salva os dados dos bens
     */
    $oDaoBensMaterial->t53_codbem = $this->getBem(); 
    $oDaoBensMaterial->t53_empen  = $this->getEmpenho(); 
    $oDaoBensMaterial->t53_garant = $this->getDataGarantia(); 
    $oDaoBensMaterial->t53_ntfisc = $this->getNotaFiscal(); 
    $oDaoBensMaterial->t53_ordem  = $this->getOrdemCompra();
    $oDaoBensMaterial->incluir($this->getBem()); 
    if ($oDaoBensMaterial->erro_status == 0) {
      throw new Exception("Erro ao salvar dados o material do bem.\n{$oDaoBensMaterial->erro_msg}");
    }
    
    if ($this->isEmpenhoSistema()) {
      
      $oDaoBensMaterialEmpenho->t11_bensmaterial = $this->getBem();
      $oDaoBensMaterialEmpenho->t11_empempenho   = $this->getEmpenho();
      $oDaoBensMaterialEmpenho->incluir(null);
      if ($oDaoBensMaterialEmpenho->erro_status == 0) {
        throw new Exception("Erro ao salvar dados o material do bem.\n{$oDaoBensMaterialEmpenho->erro_msg}");
      }
    }
  }
  /**
   * Retorna o nome do credor
   *
   * @return string
   */
  public function getCredor() {
  	return $this->sNomeCredor;
  }
}
?>