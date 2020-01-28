<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("ArquivoSiprevEscritor.model.php");
class ArquivoSiprevEscritorXML  extends ArquivoSiprevEscritor {
  
  /**
   * 
   */
  function __construct() {

  }
  
  /**
   * Transforma os dados passados para XML 
   * @param $oArquivo
   * @return caminho do Arquivo 
   */
  public function criarArquivo(ArquivoSiprevBase $oArquivo) {
    
  	  //echo "<pre>";
      //print_r($oArquivo);
      //echo "</pre>";
      //echo $oArquivo->getCnpj();
      //die();
  	
    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0','ISO-8859-1',"yes");
    //$oXmlWriter->startDocument('1.0','UTF-8');
    $oXmlWriter->startElementNs('ns2', 'siprev',"http://www.dataprev.gov.br/siprev");
    $oXmlWriter->endDtd();
    
    //$oXmlWriter->startElement($oArquivo->getNomeArquivo());
      $oXmlWriter->startElement('ente');
      $oXmlWriter->writeAttribute("siafi",$oArquivo->getSiafi());
      $oXmlWriter->writeAttribute("cnpj",$oArquivo->getCnpj());
      $oXmlWriter->endElement();
      
    foreach ($oArquivo->getDados() as $oLinha) {
    	$oXmlWriter->startElement($oArquivo->getNomeArquivo());
    	$oXmlWriter->writeAttribute("operacao", "I");
    	
      foreach ($oArquivo->getElementos() as $aElemento) {
        $this->escreveElemento($oLinha, $oXmlWriter, $aElemento);
      }
     $oXmlWriter->endElement();
      
    }
    
   // $oXmlWriter->endElement();
    $oXmlWriter->endElement();
    $sNomeArquivo = "tmp/{$oArquivo->getNomeArquivo()}.xml";
    $rsArquivoXML = fopen($sNomeArquivo, "w");
    fputs($rsArquivoXML, $oXmlWriter->outputMemory());
    fclose($rsArquivoXML);
    unset($oXmlWriter);
    return $sNomeArquivo;
  }
  
  public function escreveElemento($oLinha, $oXmlWriter, $oElemento, $sNome = '') {
   
    if (empty($oLinha->$oElemento["nome"])) {
      return false;
    }

    $oXmlWriter->startElement($oElemento["nome"]);
    foreach ($oElemento["propriedades"] as $sPropriedade) {
    	
      if (!is_array($sPropriedade)) {        
        $sValor  = '';
        if (isset($oLinha->$oElemento["nome"]->$sPropriedade)) {
          $sValor = $oLinha->$oElemento["nome"]->$sPropriedade;
        }
        $oXmlWriter->writeAttribute($sPropriedade, utf8_encode($sValor));
      } else {
        $this->escreveElemento($oLinha->$oElemento["nome"], $oXmlWriter, $sPropriedade);
      }
      
      
    }
    $oXmlWriter->endElement();    
  }
}

?>