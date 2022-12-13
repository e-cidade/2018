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


class impressao {
	
  private $iTipoImpressora  = null;
  private $iModelo          = null;
  private $iPorta;
  private $iIp;
  private $aComandos        = array();
  
  public function __construct($iTiPoImpressora = null) {
    $this->iTipoImpressora = $iTiPoImpressora;
  }
  
  public function setPorta($iPorta) {
    $this->iPorta = $iPorta;
  }
  
  public function getPorta() {
    return $this->iPorta;
  }
  
  public function setIp($iIP) {
    $this->iIp = $iIP;
  }
  
  public function getIp() {
    return $this->iIp;
  }
  
  public function setModelo($iModelo) {
    $this->iModelo = $iModelo;
  }
  
  public function getModelo() {
    return $this->iModelo;
  }
  
  public function imprimir($sImprimir = '') {
    
    if (!empty($sImprimir)) {
      $this->sStringImpressao = $sImprimir;
    }
    $this->resetComandos();
    $this->addComando($this->sStringImpressao);
    $this->rodarComandos();
  }
  
  /**
   * Metodo para adicionar comandos na memoria da impressora
   *
   * @param   string   $sComando  
   */
  public function addComando($sComando = '') {
  	$this->aComandos[] = $sComando;
  }
  
  /**
   * Metodo para zerar comandos da memoria da impressora
   *
   */
  public function resetComandos() {
  	$this->aComandos = array();
  }
  
  public function rodarComandos($sFinalizadorComando="") {
  	  
    if (empty($this->iPorta)) {
      throw new Exception("Porta de Impressão nao configurada!");
    }
    
    if (empty($this->iIp)) {
      throw new Exception("Sem IP configurado!");
    }
    
    $rImpressora = @fsockopen($this->getIp(), $this->getPorta());
    //$rImpressora = fopen('/tmp/saida.log', "w+");
    if (!$rImpressora) {
      throw new Exception("Não foi possivel conectar com impressora (IP {$this->getIp()}:{$this->getPorta()}). Verifique!");
    }
    
    foreach ($this->aComandos as $sComando ) {
    	fputs($rImpressora, $sComando.$sFinalizadorComando);
    } 
    
    fclose($rImpressora);;
    
  } 
  
  /**
   * Metodo para converter uma string para ascii
   *
   * @param   string  $sStr
   * @return  string
   */
  public function strToAsc($sStr) {

    $sStrRetorno = "";
    $aCaracters = array('é' => '82', 
                        'É' => '90', 
                        'á' => 'A0', 
                        'Á' => '86', 
                        'í' => 'A1', 
                        'Í' => '8B', 
                        'ó' => 'A2', 
                        'Ó' => '9F', 
                        'ú' => 'A3', 
                        'Ú' => '96', 
                        'ç' => '87', 
                        'Ç' => '80', 
                        'ã' => '84', 
                        'Ã' => '8E', 
                        'õ' => '94', 
                        'Õ' => '99', 
                        'à' => '85', 
                        'À' => '91' 
                       );
    
    for ($i = 0; $i < strlen($sStr); $i ++) {
      
      $char = $sStr [$i];
      if (array_key_exists($char, $aCaracters)) {
        $sStrRetorno .= chr(hexdec($aCaracters [$char]));
      } else {
        $sStrRetorno .= $char;
      }
    }
    return $sStrRetorno;
  }
}

?>