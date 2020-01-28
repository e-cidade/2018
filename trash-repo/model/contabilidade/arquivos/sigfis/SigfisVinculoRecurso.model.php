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
 * Classe Singleton para verificaчуo de vinculo de Recursos do e-cidade para o Sigfis
 * @author iuri@dbseller.combr
 * @package Recursobilidade
 * @subpackage sigfis
 *
 */
class SigfisVinculoRecurso {

  static $oInstance;
   
  protected $aListaRecursos = array();
  
  /**
   * mщtodo construtor
   */
  protected function __construct() {

    $oDomXml  = new DOMDocument();
    $oDomXml->load('config/sigfis/vinculorecursos.xml');
    $aRecursos = $oDomXml->getElementsByTagName("recurso");
    foreach ($aRecursos as $oRecurso) {
      
      $oRecursoRetorno                 = new stdClass();
      $oRecursoRetorno->recursotce     = $oRecurso->getAttribute("recursotce");
      $oRecursoRetorno->recursoecidade = $oRecurso->getAttribute("recursoecidade");
      $this->aListaRecursos[]          = $oRecursoRetorno;   
    }
  }
  /**
   * Retorna a instancia da classe
   * @return SigfisVinculoRecurso
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new SigfisVinculoRecurso();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se o Recurso passada no paramento possui vinculo com o sigfis
   * @param integer c$iCodigoRecurso codigo da Recurso do e-cidade (orctiporec.o15_codigo)
   * @return retornaobjeto do vinculo , ou false em caso de nao existir o vinculo.
   */
  public function getVinculoRecurso($iCodigoRecurso) {
   
    $aRecursos  = self::getInstance()->aListaRecursos;
    $mRetorno = false;
    foreach ($aRecursos as $oRecurso) {
      
      if ($oRecurso->recursoecidade == $iCodigoRecurso) {
        
        $mRetorno = $oRecurso;
        break;
      }
    }
    return $mRetorno;
  }
}
?>