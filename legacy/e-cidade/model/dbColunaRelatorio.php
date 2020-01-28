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

final class dbColunaRelatorio implements iGeradorRelatorio {

  public $iId = "";

  public $sNome = "";

  public $sAlias = "";

  public $iLargura = "";

  public $sAlinhamento = "";

  public $sAlinhamentoCab = "";

  public $sMascara = "";

  public $sTotalizar = "";

  public $lQuebra = "";

  public $oxml = "";
  
  /**
   * Método construtor da classe
   *
   * @param string  $iId
   * @param string  $sNome
   * @param string  $sAlias
   * @param string  $iLargura
   * @param string  $sAlinhamento
   * @param string  $sAlinhamentoCab
   * @param string  $sMascara
   * 
   */
  
  public function __construct($iId = "", $sNome = "", $sAlias = "", $iLargura = "", $sAlinhamento = "", $sAlinhamentoCab = "", $sMascara = "", $sTotalizar = "", $lQuebra = false) {

    $this->setId($iId);
    $this->setNome($sNome);
    $this->setAlias($sAlias);
    $this->setLargura($iLargura);
    $this->setAlinhamento($sAlinhamento);
    $this->setAlinhamentoCab($sAlinhamentoCab);
    $this->setMascara($sMascara);
    $this->setTotalizar($sTotalizar);
    $this->setQuebra($lQuebra);
  
  }
  
  /**
   * Retorna ID do campo 
   *
   * @return integer
   */
  public function getId() {

    return $this->iId;
  }
  
  /**
   * Retorna o nome do campo
   *
   * @return string
   */
  public function getNome() {
  
      if (db_utils::isUTF8($this->sNome)) {
        return utf8_decode($this->sNome);
      } else {
        return $this->sNome;
      }
  	
   // return $this->sNome;
  }
  
  /**
   * Retorna largura do campo
   *
   * @return integer
   */
  public function getLargura() {

    return $this->iLargura;
  }
  
  /**
   * Retorna alias do Campo
   *
   * @return string
   */
  public function getAlias() {

	    if (db_utils::isUTF8($this->sAlias)) {
	      return utf8_decode($this->sAlias);
	    } else {
	      return $this->sAlias;
	    }
    //return $this->sAlias;
  }
  
  /**
   * Retorna alinhamento do campo
   *
   * @return string
   */
  public function getAlinhamento() {

    return $this->sAlinhamento;
  }
  
  /**
   * Retorna alinhamento do cabeçalho referente ao campo no relatório
   *
   * @return string
   */
  public function getAlinhamentoCab() {

    return $this->sAlinhamentoCab;
  }
  
  /**
   * Retorna a máscara do campo
   *
   * @return string
   */
  public function getMascara() {

    return $this->sMascara;
  }
  
  /**
   * Retorna a totalizador do campo
   *
   * @return string
   */
  public function getTotalizar() {

    return $this->sTotalizar;
  }
  
  /**
   * @return unknown
   */
  public function getQuebra() {

    return $this->lQuebra;
  }
  
  /**
   * @param integer $iId
   */
  public function setId($iId) {

    $this->iId = $iId;
  }
  
  /**
   * @param integer $iLargura
   */
  public function setLargura($iLargura) {

    $this->iLargura = $iLargura;
  }
  
  /**
   * @param string $sAlias
   */
  public function setAlias($sAlias) {

    $this->sAlias = $sAlias;
  }
  
  /**
   * @param string $sAlinhamento
   */
  public function setAlinhamento($sAlinhamento) {

    $this->sAlinhamento = $sAlinhamento;
  }
  
  /**
   * @param string $sAlinhamentoCab
   */
  public function setAlinhamentoCab($sAlinhamentoCab) {

    $this->sAlinhamentoCab = $sAlinhamentoCab;
  }
  
  /**
   * @param string $sMascara
   */
  public function setMascara($sMascara) {

    $this->sMascara = $sMascara;
  }
  
  /**
   * @param string $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }
  
  /**
   * @param string $sTotalizar
   */
  public function setTotalizar($sTotalizar) {

    $this->sTotalizar = $sTotalizar;
  }
  
  /**
   *
   * @param unknown_type $lQuebra
   */
  public function setQuebra($lQuebra) {

    $this->lQuebra = $lQuebra;
  }
  
  /**
   * Retorna estrutura XML das propriedades da classe
   *
   * @return unknown
   */
  
  public function toXml(XMLWriter $oXmlWriter) {

    $oXmlWriter->startElement('Campo');
    
    $oXmlWriter->writeAttribute('id', utf8_encode($this->iId));
    $oXmlWriter->writeAttribute('nome', utf8_encode($this->sNome));
    $oXmlWriter->writeAttribute('alias', utf8_encode($this->sAlias));
    $oXmlWriter->writeAttribute('largura', utf8_encode($this->iLargura));
    $oXmlWriter->writeAttribute('alinhamento', utf8_encode($this->sAlinhamento));
    $oXmlWriter->writeAttribute('alinhamentocab', utf8_encode($this->sAlinhamentoCab));
    $oXmlWriter->writeAttribute('mascara', utf8_encode($this->sMascara));
    $oXmlWriter->writeAttribute('totalizar', utf8_encode($this->sTotalizar));
    $oXmlWriter->writeAttribute('quebra', utf8_encode($this->lQuebra));
    
    $oXmlWriter->endElement();
    
    return true;
  
  }
  
}

?>