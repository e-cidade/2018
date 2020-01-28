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
 * Classe Singleton para verificaчуo de vinculo de contas do e-cidade para o Sigfis
 * @author iuri@dbseller.combr
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisVinculoConta {

  static $oInstance;
   
  protected $aListaContas = array();
  
  /**
   * mщtodo construtor
   */
  protected function __construct() {

    $oDomXml  = new DOMDocument();
    $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
    $aContas = $oDomXml->getElementsByTagName("conta");
    foreach ($aContas as $oConta) {
      
      $oContaRetorno                = new stdClass();
      $oContaRetorno->contatce      = $oConta->getAttribute("contatce");
      $oContaRetorno->contaplano    = $oConta->getAttribute("contaplano");
      $oContaRetorno->naturezasaldo = $oConta->getAttribute("naturezasaldo");
      $this->aListaContas[]         = $oContaRetorno;   
    }
  }
  /**
   * Retorna a instancia da classe
   * @return SigfisVinculoConta
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new SigfisVinculoConta();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se a conta passada no paramento possui vinculo com o sigfis
   * @param integer c$iCodigoConta codigo da conta do e-cidade (conplano.c60_codcon)
   * @return retornaobjeto do vinculo , ou false em caso de nao existir o vinculo.
   */
  public function getVinculoConta($iCodigoConta) {
   
    $aContas  = self::getInstance()->aListaContas;
    $mReturno = false;
    foreach ($aContas as $oConta) {
      
      if ($oConta->contaplano == $iCodigoConta) {
        
        $mReturno = $oConta;
        break;
      }
    }
    return $mReturno;
  }
}
?>