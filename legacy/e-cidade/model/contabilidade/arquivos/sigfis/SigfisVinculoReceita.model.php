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
 * Classe Singleton para verificaчуo de vinculo de Receitas do e-cidade para o Sigfis
 * @author andrio.costa@dbseller.combr
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisVinculoReceita {

  static $oInstance;
   
  protected $aListaReceita = array();
  
  /**
   * mщtodo construtor
   */
  protected function __construct() {

    $oDomXml   = new DOMDocument();
    $aReceitas = array();
    if (file_exists('config/sigfis/vinculoreceita.xml')) {
      
      $oDomXml->load('config/sigfis/vinculoreceita.xml');
      $aReceitas = $oDomXml->getElementsByTagName("receita");
    }
    foreach ($aReceitas as $oReceita) {
      
      $oReceitaRetorno                 = new stdClass();
      $oReceitaRetorno->receitatce     = $oReceita->getAttribute("receitatce");
      $oReceitaRetorno->receitaecidade = $oReceita->getAttribute("receitaecidade");
      $this->aListaReceita[]          = $oReceitaRetorno;   
    }
  }
  /**
   * Retorna a instancia da classe
   * @return SigfisVinculoReceita
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new SigfisVinculoReceita();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se a Receita passada no paramento possui vinculo com o sigfis
   * @param integer $iCodigoReceita codigo da Receita do e-cidade (orcfontes.o57_codfon)
   * @return retorna objeto do vinculo , ou false em caso de nao existir o vinculo.
   */
  public function getVinculoReceita($iCodigoReceita) {
   
    $aReceitas  = self::getInstance()->aListaReceita;
    $mRetorno = false;
    foreach ($aReceitas as $oReceita) {
      
      if ($oReceita->receitaecidade == $iCodigoReceita) {
        
        $mRetorno = $oReceita;
        break;
      }
    }
    return $mRetorno;
  }
}
?>