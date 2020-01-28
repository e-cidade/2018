<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once ('model/tceEstruturaBasica.php');

class tceLeiame extends tceEstruturaBasica {
	
	const NOME_ARQUIVO = 'LEIAME.TXT';
  const CODIGO_ARQUIVO = 48;
	
  public  $iInstit     = "";
  public  $sDataIni    = "";
  public  $sDataFim    = "";
  public  $sCodRemessa = "";
  
  private $oLeiaute   = null;
  
  function __construct($iInstit, $sCodRemessa, $sDataIni, $sDataFim, $oData, $oLeiaute=null) {

    try {
      parent::__construct(self::CODIGO_ARQUIVO, self::NOME_ARQUIVO);
    } catch ( Exception $e ) {
      throw $e->getMessage();
    }
    
    $this->iInstit      = $iInstit;
    $this->sDataIni     = $sDataIni;
    $this->sDataFim     = $sDataFim;
    $this->sCodRemessa  = $sCodRemessa;
    $this->oOutrosDados = $oData;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
  }
  
  function getNomeArquivo() {
    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    $this->oTxtLayout->setByLineOfDBUtils( $this->cabecalhoPadrao( $this->iInstit, $this->sDataIni, $this->sDataFim, $this->sCodRemessa ), 1 );
    
    $oContceArquivo = db_utils::getDao('contcearquivo');
    $sSql           = $oContceArquivo->sql_query_file($this->oOutrosDados->codigoremessa,"c11_infleiame as observacoes");
    $rsObservacoes  = $oContceArquivo->sql_record($sSql);
    $oObservacoes   = db_utils::fieldsMemory($rsObservacoes,0);
    $oObservacoes->observacoes = str_replace(array("\n", "\r"), " ", $oObservacoes->observacoes);
    $iInicioTextoObservacao = strpos($oObservacoes->observacoes, "#");
    $iFimTextoObservacao    = strpos($oObservacoes->observacoes, "#", $iInicioTextoObservacao + 1);

    $oObservacoes->observacoes = substr_replace($oObservacoes->observacoes, "",
                                                $iInicioTextoObservacao,
                                                ($iFimTextoObservacao) - $iInicioTextoObservacao +1
                                               );

    $this->oTxtLayout->setByLineOfDBUtils( $oObservacoes, 3 );
    
    $this->oTxtLayout->setByLineOfDBUtils( $this->rodapePadrao( 1 ), 5 );
  
  }
  
}

?>