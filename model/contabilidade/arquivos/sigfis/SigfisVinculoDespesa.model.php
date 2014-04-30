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

/**
 * Classe Singleton para verificaчуo de vinculo de Despesa do e-cidade para o Sigfis
 * @author andrio.costa@dbseller.combr
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisVinculoDespesa {

  static $oInstance;
   
  protected $aListaDespesa = array();
  
  /**
   * mщtodo construtor
   */
  protected function __construct() {

    $oDomXml   = new DOMDocument();
    $aDespesas = array();
    if (file_exists('config/sigfis/vinculodespesa.xml')) {
      
      $oDomXml->load('config/sigfis/vinculodespesa.xml');
      $aDespesas = $oDomXml->getElementsByTagName("despesa");
    }
    foreach ($aDespesas as $oDespesa) {
      
      $oDespesaRetorno                 = new stdClass();
      $oDespesaRetorno->despesatce     = $oDespesa->getAttribute("despesatce");
      $oDespesaRetorno->despesaecidade = $oDespesa->getAttribute("despesaecidade");
      $this->aListaDespesa[]           = $oDespesaRetorno;   
    }
  }
  /**
   * Retorna a instancia da classe
   * @return SigfisVinculoDespesa
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new SigfisVinculoDespesa();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se a Despesa passada no paramento possui vinculo com o sigfis
   * @param integer $iCodigoDespesa codigo da Despesa do e-cidade (orcelemento.o56_codele)
   * @return retorna objeto do vinculo , ou false em caso de nao existir o vinculo.
   */
  public function getVinculoDespesa($iCodigoDespesa) {
   
    $aDespesas  = self::getInstance()->aListaDespesa;
    $mRetorno   = false;
    foreach ($aDespesas as $oDespesa) {
      
      if ($oDespesa->despesaecidade == $iCodigoDespesa) {
        
        $mRetorno = $oDespesa;
        break;
      }
    }
    return $mRetorno;
  }
  
  /**
   * 
   * Este mщtodo щ para garantir a integridade do Singleton
   */
  protected function __clone() {

  }
  
}
?>