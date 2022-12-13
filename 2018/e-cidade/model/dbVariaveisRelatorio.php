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

final class dbVariaveisRelatorio  implements iGeradorRelatorio {
  
  private $sNome     = "";	
  private $sLabel    = "";
  private $sTipoDado = "";
  private $sValor    = "";
  

  /**
   * Inclui todos atributos da variável
   *
   * @param string $sNome
   * 
   */
  
  function __construct($sNome="",$sLabel="",$sValor="",$sTipoDado="") {

  	$this->setNome ($sNome);
  	$this->setLabel($sLabel);
  	$this->setTipoDado($sTipoDado);
  	$this->setValor($sValor);
  	$this->setTipoDado($sTipoDado);
  	
  }
  
  /**
   * @return unknown
   */
  public function getNome() {

    return $this->sNome;
  }
  
  /**
   * @param unknown_type $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }
  
  /**
   * @return unknown
   */
  public function getLabel() {

   if (db_utils::isUTF8($this->sLabel)) {
     return utf8_decode($this->sLabel);
   } else {
     return $this->sLabel;
   }
     	
  }
  
  /**
   * @param unknown_type $sLabel
   */
  public function setLabel($sLabel) {

    $this->sLabel = $sLabel;
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
   * @param unknown_type $sValor
   */
  public function setValor($sValor) {

    $this->sValor = $sValor;
  }

  /**
   * @return unknown
   */
  public function getTipoDado() {

    return $this->sTipoDado;
  }
  
  /**
   * @param unknown_type 
   */
  public function setTipoDado($sTipoDado) {

    $this->sTipoDado = $sTipoDado;
  }
  
  /**
   * Enter description here...
   *
   * @return unknown
   */
  
  public function toXml(XMLWriter $oXmlWriter) {
  		
  	$oXmlWriter->startElement('Variavel');
  	
  	$oXmlWriter->writeAttribute('nome' ,    utf8_encode($this->sNome));
  	$oXmlWriter->writeAttribute('label',    utf8_encode($this->sLabel));
  	$oXmlWriter->writeAttribute('tipodado', utf8_encode($this->sTipoDado));
  	$oXmlWriter->writeAttribute('valor',    utf8_encode($this->sValor));

  	$oXmlWriter->endElement();
  	
  	return true;
  	
  }

}

?>