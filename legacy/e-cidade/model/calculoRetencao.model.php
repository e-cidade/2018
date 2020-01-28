<?php
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
 * realizada o calculo da retencao pelo tipo de calculo cadastrado na retencao
 *
 */
class calculoRetencao {
  
  private $iTipoCalculo = 0;
  private $iCpfCnpj     = null;
  
  /**
   * Objeto do calculo da retencao;
   *
   * @var object
   */
  private $oObjCalculo = null;
  
  /**
   * metodo construtor
   */
  function __construct($iTipoCalculo, $iCpfCnpj) {
    
    $this->iTipoCalculo = $iTipoCalculo;
    $this->iCpfCnpj     = $iCpfCnpj;
    $this->setObjetoCalculo();

  }
  /**
   * Define a classe de calculo que deve ser carrega,
   *
   */
  function setObjetoCalculo() {
    
    switch ($this->iTipoCalculo) {
      
      case 1:

        require_once("model/calculoRetencaoIrrf.model.php"); 
        $this->oObjCalculo = new calculoRetencaoIrrfFisica($this->iCpfCnpj,1);
        break;
        
      case 2:
        
        require_once("model/calculoRetencaoIrrfJuridica.model.php"); 
        $this->oObjCalculo = new calculoRetencaoIrrfJuridica($this->iCpfCnpj,2);
        break;
        
      case 3:
        
        require_once("model/calculoRetencaoInssFisica.model.php"); 
        $this->oObjCalculo = new calculoRetencaoInssFisica($this->iCpfCnpj,3);
        break; 

      case 4:
        
        require_once("model/calculoRetencaoInssJuridica.model.php"); 
        $this->oObjCalculo = new calculoRetencaoInssJuridica($this->iCpfCnpj,4);
        break;    

      case 5:
        
        require_once("model/calculoRetencaoIssqn.model.php"); 
        $this->oObjCalculo = new calculoRetencaoIssqn($this->iCpfCnpj,5);
        break;    
      
      case 6:
        
        require_once("model/calculoRetencaoOutros.model.php"); 
        $this->oObjCalculo = new calculoRetencaoOutros($this->iCpfCnpj,5);
        break;

      case 7:
        
        require_once("model/calculoRetencaoInssAutonomos.model.php"); 
        $this->oObjCalculo = new calculoRetencaoInssAutonomos($this->iCpfCnpj,7);
        break; 
         
      default:
        
        throw new Exception("tipo de cбlculo ({$this->iTipoCalculo}) invбlido.\nVerifique configuraзoes da Retenзгo.");
        break;
      
    }
    
  }
  
  function calcularRetencao() {
    return $this->oObjCalculo->calcularRetencao();
  }
  
  /**
   * Seta o valor a deduzir da base de calculo;
   *
   * @param float $nValorDeducao
   */
  function setDeducao ($nValorDeducao) {
    $this->oObjCalculo->setDeducao($nValorDeducao);
  }
  
  /**
   * Seta a aliquota;
   *
   * @param float $nValorAliquota
   */
  function setAliquota ($nValorAliquota) {
    $this->oObjCalculo->setAliquota($nValorAliquota);
  }
  
  function getAliquota() {
    return $this->oObjCalculo->getAliquota();
  }
  
  /**
   * Seta o valor da base de calculo
   *
   * @param float8 $nValorBaseCalculo valor da base de calculo
   */
  function setBaseCalculo($nValorBaseCalculo) {
    $this->oObjCalculo->setBaseCalculo($nValorBaseCalculo);
  }
  /**
   * Retorna o valor da base calculo 
   *
   * @return float;
   */
  function getValorBaseCalculo() {
    return $this->oObjCalculo->getValorBaseCalculo();
  }

  /**
   * Define a data base do calculo
   *
   * @param string $dtBaseCalculo data base para calculo formato dd/mm/YYY
   */
  function setDataBase($dtBaseCalculo) {
    $this->oObjCalculo->setDataBase($dtBaseCalculo);
  }
  /**
   * Define o valor da Nota a ser contabilizado na Retencao.
   *
   * @param unknown_type $nValorNota
   */
  function setValorNota($nValorNota) {
    $this->oObjCalculo->setValorNota($nValorNota);
  }
  /**
   * Define os Cуdigos  auxiliares do Movimento
   *
   * @param anteger $iCodigoMovimento
   */
  function setCodigoMovimentos($aCodigoMovimento) {
    $this->oObjCalculo->setCodigoMovimentos($aCodigoMovimento);
  }
}

?>