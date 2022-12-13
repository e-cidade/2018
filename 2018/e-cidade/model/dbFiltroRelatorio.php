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


require_once 'interfaces/GeradorRelatorio.interface.php';

final class dbFiltroRelatorio implements iGeradorRelatorio {	
  
  public $sOperador = "";
  public $sCampo    = "";	
  public $sCondicao = "";
  public $sValor	= "";
  
  /**
   * Inclui atributos do filtro
   *
   * @param string $sCampo
   * @param string $sCondicao
   * @param string $sValor
   * @param string $sOperador
   * 
   */
  
  function __construct($sCampo="",$sCondicao="",$sValor="",$sOperador="") {
  	
	$this->setCampo($sCampo);
	$this->setCondicao($sCondicao);
	$this->setValor($sValor);
	$this->setOperador($sOperador);
	
  }
  
  /**
   * @return unknown
   */
  public function getCampo() {

    if (db_utils::isUTF8($this->sCampo)) {
      return utf8_decode($this->sCampo);
    } else {
      return $this->sCampo;
    }  	
    
  }
  
  /**
   * @return unknown
   */
  public function getCondicao() {

    return $this->sCondicao;
  }
  
  /**
   * @return unknown
   */
  public function getOperador() {

    return $this->sOperador;
  }
  
  /**
   * @return unknown
   */
  public function getValor() {

    if (db_utils::isUTF8($this->sValor)) {
      return utf8_decode($this->sValor);
    } else {
      return $this->sValor;
    }
      	
  }
  
  /**
   * @param unknown_type $sCampo
   */
  public function setCampo($sCampo) {

    $this->sCampo = $sCampo;
  }
  
  /**
   * @param unknown_type $sCondicao
   */
  public function setCondicao($sCondicao) {

    $this->sCondicao = $sCondicao;
  }
  
  /**
   * @param unknown_type $sOperador
   */
  public function setOperador($sOperador) {

    $this->sOperador = $sOperador;
  }
  
  /**
   * @param unknown_type $sValor
   */
  public function setValor($sValor) {

    $this->sValor = $sValor;
  }

  /**
   * Enter description here...
   *
   * @return unknown
   */
  
  public function toXml(XMLWriter $oXmlWriter) {
  		

  	$oXmlWriter->startElement('Filtro');
  	
  	$oXmlWriter->writeAttribute('operador',utf8_encode($this->sOperador));
  	$oXmlWriter->writeAttribute('campo'   ,utf8_encode($this->sCampo));
  	$oXmlWriter->writeAttribute('condicao',utf8_encode($this->sCondicao));
  	$oXmlWriter->writeAttribute('valor'	  ,utf8_encode($this->sValor));
  	
  	$oXmlWriter->endElement();
  	
  	return true;
  	
  }

  
}
?>