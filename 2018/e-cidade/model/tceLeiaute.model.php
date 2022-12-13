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


require_once ('model/tceEstruturaBasica.php');

class tceLeiaute extends tceEstruturaBasica {
  
  const  NOME_ARQUIVO   = 'LEIAUTE.TXT';
  const  CODIGO_ARQUIVO = 40;
  
  public $iInstit       = "";
  public $sDataIni      = "";
  public $sDataFim      = "";
  public $sCodRemessa   = "";
  public $aLinhas       = array();
  public $aLinha        = array();
  public $oOutrosDados  = null;
    
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData) {
    
    try {
      parent::__construct(self::CODIGO_ARQUIVO,self::NOME_ARQUIVO);
    } catch (Exception $e) {
      throw $e->getMessage();     
    }
    
    $this->iInstit     = $iInstit;
    $this->sDataIni    = $sDataIni;
    $this->sDataFim    = $sDataFim;
    $this->sCodRemessa = $sCodRemessa;
    
  }
  
  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    // db_criatermometro('terTCE4960', 'Arquivo TCE4960...', 'blue', 1);
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);
    $iTotalRegistros = 0;
    foreach ($this->aLinhas as $obj) {
	    $this->oTxtLayout->setByLineOfDBUtils($obj, 3);
      $iTotalRegistros ++;
    }
      
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
  
  }
  
  private function getSequencial(){
  	return (count($this->aLinhas)+1);
  }
  
  public  function setNomeArquivo($sNome) {
    $this->aLinha['nomearqinfcomplemcomextensao'] = $sNome; 	
  }
  
  public  function setNomeArqTce($sNome){
  	$this->aLinha['nomearqinftcers'] = $sNome;
  }
  
  public  function setNomeCampo($sNome){
    $this->aLinha['nomecampoestruturabancodedados'] = $sNome;
  }
  
  public  function setNumCasasDecimais($iNumero){
    $this->aLinha['numcasasdecimaiscamponumerico'] = $iNumero;
  }
  
  public function setSequencial(){
    $this->aLinha['numerosequencialregistro'] = $this->getSequencial();
  }
  
  public function setVersaoLeiaute($sVersao){
    $this->aLinha['numeroversaoleiaute'] = $sVersao;
  }
  
  public function setObs($sObs){
    $this->aLinha['observacoes'] = $sObs;
  }
  
  
  public function setTamanho($iTamanho){
    $this->aLinha['tamanhocampo'] = $iTamanho;
  }
  
  public function setTipo($sTipo){
    $this->aLinha['tipocampo'] = $sTipo;
  }
  
  
  function addLinha() {
  	
  	$this->setSequencial();
  	$array = $this->aLinha;
  	$oObj  = new stdClass();
  	foreach ($array as $chave => $value) {
  		$oObj->$chave = $value;
  	}
    $this->aLinhas[] = $oObj;
      	
  }
  
}







?>