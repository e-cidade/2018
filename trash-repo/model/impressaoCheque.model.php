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

require_once("model/impressao.model.php");
require_once("libs/db_strings.php");

class impressaoCheque extends impressao {

  private $dtDataImpressao;
  private $nValor;
  private $sMunicipio;
  private $sCredor;
  private $sCodBanco;
  public  $sStringImpressao;
  private $iTipoImpressora;
  private $nomePrefeito;
  private $nomeTesoureiro;
  /**
   * 
   */
  function __construct($iTiPoImpressora) {
    $this->iTipoImpressora = $iTiPoImpressora;
  }
  /**
   * @return unknown
   */
  public function getdtDataImpressao() {
     
    return $this->dtDataImpressao;
  }
  
  /**
   * @param unknown_type $dtDataImpressao
   */
  public function setdtDataImpressao($dtDataImpressao) {
  	
    $dataImpressao         = explode("/", $dtDataImpressao);
    $this->dtDataImpressao = $dataImpressao[0]."-".$dataImpressao[1]."-".substr($dataImpressao[2],2,2); 
    
  }
  
  /**
   * @return unknown
   */
  public function getnValor() {
    $sValor = trim(db_formatar(round($this->nValor,2), 'p'));
    return $sValor;
  }
  
  /**
   * @param unknown_type $nValor
   */
  public function setnValor($nValor) {

    $this->nValor = $nValor;
  }
  
  /**
   * 
   * @return unknown
   */
  public function getsCodBanco() {

    return $this->sCodBanco;
  }
  
  /**
   * @param unknown_type $sCodBanco
   */
  public function setsCodBanco($sCodBanco) {

    $this->sCodBanco = $sCodBanco;
  }
  
  /**
   * @return unknown
   */
  public function getsCredor() {

    return $this->sCredor;
  }
  
  /**
   * @param unknown_type $sCredor
   */
  public function setSCredor($sCredor) {

    $this->sCredor = $sCredor;
  }
  
  /**
   * @return unknown
   */
  public function getSMunicipio() {

    return $this->sMunicipio;
  }
  
  /**
   * @param unknown_type $sMunicipio
   */
  public function setSMunicipio($sMunicipio) {

    $this->sMunicipio = $sMunicipio;
  }
  /**
   * @return unknown
   */
  public function getNomePrefeito() {

    return $this->nomePrefeito;
  }
  
  /**
   * @return unknown
   */
  public function getNomeTesoureiro() {

    return $this->nomeTesoureiro;
  }
  
  /**
   * @param unknown_type $nomePrefeito
   */
  public function setNomePrefeito($nomePrefeito) {
    $this->nomePrefeito = $nomePrefeito;
  }
  
  /**
   * @param unknown_type $nomeTesoureiro
   */
  public function setNomeTesoureiro($nomeTesoureiro) {

    $this->nomeTesoureiro = $nomeTesoureiro;
  }

   
  function montaImpressao() {
    
    switch ($this->iTipoImpressora) {
        
      /*
       * Modelo 1 - Impressora Chronos
       */
      case 4:
        
        $this->sStringImpressao  = chr(27).chr(160)." ".$this->getsCredor()."\n";
        $this->sStringImpressao .= chr(27).chr(161)." ".$this->getsMunicipio()."\n";
        $this->sStringImpressao .= chr(27).chr(162)." ".$this->getsCodBanco()."\n";
        $this->sStringImpressao .= chr(27).chr(163)." ".$this->getnValor()."\n";
        $this->sStringImpressao .= chr(27).chr(164)." ".$this->getdtDataImpressao()."\n";
        $this->sStringImpressao .= chr(27).chr(176);
        break;
        
      /*
       * Impressora  BEMATECH (DP 20)
       * info: para essa impressora, o ano deve ser de dois Digitos. 
       */
      case 5:
        
        $sDataImpressao = str_replace("-", "/",$this->getdtDataImpressao());
        $this->sStringImpressao  = chr(27).chr(177);
        $this->sStringImpressao .= chr(27).chr(162).$this->getsCodBanco().chr(13);
        $this->sStringImpressao .= chr(27).chr(163).$this->getnValor().chr(13);
        $this->sStringImpressao .= chr(27).chr(160).$this->getsCredor().chr(13);
        $this->sStringImpressao .= chr(27).chr(161).$this->getSMunicipio().chr(13);
        $this->sStringImpressao .= chr(27).chr(164).$sDataImpressao.chr(13);
        $this->sStringImpressao .= chr(27).chr(176);
        break;
        
      case 6:
        
        $this->setdtDataImpressao(str_replace("-","",$this->getdtDataImpressao()));
        $this->nValor = str_replace(".","",$this->getnValor());
        $this->sStringImpressao  = chr(27).chr(66)." ".$this->getsCodBanco()."\n";
        $this->sStringImpressao .= chr(27).chr(70)." ".$this->getsCredor()."\n";
        $this->sStringImpressao .= chr(27).chr(67)." ".$this->getSMunicipio()."\n";
        $this->sStringImpressao .= chr(27).chr(68)." ".$this->getdtDataImpressao()."\n";
        $this->sStringImpressao .= chr(27).chr(86)." ".$this->nValor."\n";
        break;

      /*
       * Impressora Elgin/Imprecheq 
       */  
      case 8:
      	
        $this->nValor *= 100;
        $sDataImpressao = str_replace("-", "",$this->getdtDataImpressao());
        $this->sStringImpressao  = chr(27).chr(66)." ".$this->getsCodBanco();
        $this->sStringImpressao .= chr(27).chr(70)." ".$this->getsCredor().chr(36);
        $this->sStringImpressao .= chr(27).chr(67)." ".$this->getSMunicipio().chr(36);
        $this->sStringImpressao .= chr(27).chr(68)." ".$sDataImpressao;
        $this->sStringImpressao .= chr(27).chr(86)." ".str_pad($this->nValor,14,0,STR_PAD_LEFT);
        break;
        
      /*
       * Impressora Epson LX-300 
       */  
      case 9:
        

        unset($this->sStringImpressao);

        list($iAno,$iMes,$iDia)  = explode("-",$this->getdtDataImpressao());
        
        $sMunicipio     = $this->getSMunicipio();
        $sMes           = str_pad(strtoupper(db_mes($iMes)),14,' ',STR_PAD_RIGHT);
        $sDataImpressao = $iDia."   ".$sMes." ".$iAno;
        $sValor         = str_pad(trim(db_formatar($this->nValor,'f')),12,'*',STR_PAD_LEFT);
        $sValorExtenso  = strtoupper(db_extenso($this->nValor,true));
        $sCredor        = $this->getsCredor();
        $sMatricula     = "Matr.:".$this->sMatricula;
        $sFolha         = "Folha.:".$this->sFolha;
        $sCont          = "Cont.:".$this->sCont;
        $sEstrutural    = $this->sEstrutural;
        
        $sValorExtenso  = str_repeat(' ',8).$sValorExtenso;
        $aValorExtenso  = db_strings::quebraLinha($sValorExtenso,62,'/');
        
        $iInicioLinha   = 0;
        
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+59).$sValor."\n\n";
        
        foreach ( $aValorExtenso as $iIndExtenso => $sFrase ){
          $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+12).$sFrase."\n";
        }
        
        for ($iInd=$iIndExtenso; $iInd < 1; $iInd++) {
        	$this->sStringImpressao .= str_repeat(" ",$iInicioLinha+12).str_repeat('/',62)."\n";
        }
        $this->sStringImpressao .= "\n"; 
        
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+12).$sCredor."\n";
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+11).str_pad($sMunicipio,35,' ',STR_PAD_LEFT) ." ,  ".$sDataImpressao. "\n";
        
        $this->sStringImpressao .= "\n\n\n\n\n\n";
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+12).$sEstrutural;
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+5).$sMatricula;
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+5).$sFolha;
        $this->sStringImpressao .= str_repeat(" ",$iInicioLinha+5).$sCont;
        $this->sStringImpressao .= "\n\n\n\n\n";        
        
        
//        $this->sStringImpressao  = chr(15);                                                   
//        $this->sStringImpressao .= str_repeat(" ",120).$sValor. "\n\n";
//        $this->sStringImpressao .= str_repeat(" ",46).str_pad(trim($sValorExtenso),95,'/',STR_PAD_RIGHT).  "\n\n";
//        $this->sStringImpressao .= str_repeat(" ",46).str_repeat("/",95)."\n";
//        $this->sStringImpressao .= str_repeat(" ",32).$sCredor.        "\n";
//        $this->sStringImpressao .= str_repeat(" ",60).str_pad($sMunicipio,40,' ',STR_PAD_LEFT) ." ,  ".$sDataImpressao. "\n";
//        
//        $this->sStringImpressao .= "\n\n\n\n\n\n";
//        $this->sStringImpressao .= str_repeat(" ",32).$sEstrutural;
//        $this->sStringImpressao .= str_repeat(" ",15).$sMatricula;
//        $this->sStringImpressao .= str_repeat(" ",15).$sFolha;
//        $this->sStringImpressao .= str_repeat(" ",15).$sCont;
//        $this->sStringImpressao .= "\n\n\n\n\n";        
        
        break;

    }
  }
  
  function imprimir() {
    parent::imprimir();
  }
  
}


?>