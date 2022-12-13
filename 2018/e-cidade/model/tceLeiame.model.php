<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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