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
    
    $this->oTxtLayout->setByLineOfDBUtils( $oObservacoes, 3 );
    
    $this->oTxtLayout->setByLineOfDBUtils( $this->rodapePadrao( 1 ), 5 );
  
  }
  
}

?>