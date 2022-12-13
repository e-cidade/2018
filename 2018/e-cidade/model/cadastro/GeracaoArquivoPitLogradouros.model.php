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


/**
 * Classe responsável pela geração do arquivo PIT de Logradouros
 * @author  Tales Baz <tales.baz@dbseller.com.br>
 * @package Cadastro
 */
class GeracaoArquivoPitLogradouros {
    
	/**
	 * Caminho do JSON com as mensagens
	 */
	const MENSAGENS = 'arrecadacao.cadastro.GeracaoArquivoPitLogradouros.';
	
  /**
   * Ano que deve ser usado para a geração do arquivo.
   * @var Integer
   */
  private $iAno;

  /**
   * Semestre que deve ser usado para a geração do arquivo
   * @var Insteger
   */
  private $iSemestre;
  
  /**
   * Instituição que deve ser usado para a geração do arquivo
   * @var Insteger
   */
  private $iInstit;

  /**
   * Objeto do tipo DomDocument para geração do XML
   * @var DomDocument
   */
  private $DomDocument;

  /**
   * Array com as inconsistencias
   * @var array
   */
  private $aErros;

  /**
   * Função construtora para geração de arquivo ITBI-PVU
   * @param integer $iAno      Ano que deve ser usado para a geração de Arquivo
   * @param integer $iSemestre Semestre que deve ser usado para a geração do Arquivo
   */
  function __construct( $iAno, $iSemestre ){
    
    $this->iAno      = $iAno;
    $this->iSemestre = $iSemestre;
    $this->iInstit   = db_getsession('DB_instit');
    $this->aErros    = array();
    
    $this->oDomDocument = new DOMDocument('1.0', 'iso-8859-1');
    $this->oDomDocument->xmlStandalone = true;
  }

  /**
   * Função para geração de arquivo XML ITBI-PVU
   * @return string $sCaminhoArquivo
   */
  public function geraArquivo(){

    $oInformacao = $this->oDomDocument->createElement("INFORMACAO");
    $oInformacao->setAttribute('tipo', 'LOGRADOUROS');
    $oInformacao->setAttribute('versao', $this->oDomDocument->xmlVersion);
    
    $this->oDomDocument->appendChild($oInformacao);
        
    /**
     * Busca dados Municipio
     */
    $oMunicipio = $this->getMunicipio();
    $this->escreveMunicipio( $oInformacao, $oMunicipio );
        
    $oParentLogradouro = $this->oDomDocument->createElement("LOGRADOUROS");
    $oInformacao->appendChild( $oParentLogradouro );
    $oParentBairro     = $this->oDomDocument->createElement("BAIRROS");
    $oInformacao->appendChild( $oParentBairro );
    $oParentVila       = $this->oDomDocument->createElement("VILAS");
    $oInformacao->appendChild( $oParentVila );
    
    /**
     * Percorre a lista de logradouros
     */
    foreach ( $this->getLogradouros() as $oLogradouros ) {

    	/**
			 * Escreve tag <logradouro> dentro do Parent LOGRADOUROS
    	 */
    	$oChildLogradouro = $this->oDomDocument->createElement('logradouro');
    	$oChildLogradouro->setAttribute('tipo', $oLogradouros->tipo );
    	$oChildLogradouro->setAttribute('nome', utf8_encode( $oLogradouros->nome ) );
    	$oParentLogradouro->appendChild( $oChildLogradouro );

    	/**
    	 * Escreve tag <bairro> dentro do Parent BAIRROS
    	 */
    	$oChildBairro = $this->oDomDocument->createElement('bairro');
    	$oChildBairro->setAttribute('nome', utf8_encode( $oLogradouros->bairro ) );
    	$oParentBairro->appendChild( $oChildBairro );
    	
    	/**
    	 * Escreve tag <vila> dentro do Parent VILAS
    	 */
    	$oChildVila = $this->oDomDocument->createElement('vila');
    	$oChildVila->setAttribute('nome', '' );
    	$oParentVila->appendChild( $oChildVila );
    }
    
    /**
     * Escreve tags parent LOGRADOUROS, BAIRROS, VILAS
     */
    $oInformacao->appendChild( $oParentLogradouro );
    $oInformacao->appendChild( $oParentBairro     );
    $oInformacao->appendChild( $oParentVila       );
        
    $sCaminhoArquivo = "tmp/LOGR_{$oMunicipio->codigo}_{$this->iSemestre}_{$this->iAno}.xml";
    
    /**
     * Caso exista o arquivo deve ser removido
     */
    if( file_exists( $sCaminhoArquivo ) ){
      unlink( $sCaminhoArquivo );
    }
    
    /**
     * Retorna o arquivo gerado caso consiga criar
     */
    if ( $this->oDomDocument->save( $sCaminhoArquivo ) ){
    	return $sCaminhoArquivo;
    }
    
    return false;
  }

  /**
   * Retorna as inconsistências dos registros que não foram gerados no arquivo.
   *
   * @return array
   */
  public function getErros() {
    return $this->aErros;
  }
  
  /**
   * Cria a tag municipio e insere no XML dentro do parent
   * @param Object $oInformacao
   * @param Object $oMunicipios
   * @return void
   */
  private function escreveMunicipio( $oInformacao, $oMunicipios ) {
  	 
  	$oMunicipio = $this->oDomDocument->createElement('MUNICIPIO');
  	$oMunicipio->setAttribute('codigo',   $oMunicipios->codigo );
  	$oMunicipio->setAttribute('nome',     $oMunicipios->nome );
  	$oMunicipio->setAttribute('ano',      $this->iAno );
  	$oMunicipio->setAttribute('semestre', $this->iSemestre );
  
  	$oInformacao->appendChild($oMunicipio);
  }
      
  /**
   * Retorna as logradouros para geração do arquivo
   * @return Object
   */
  private function getLogradouros() {

  	$sSql  = "select distinct                                         ";
  	$sSql .= "       j88_descricao         as tipo,          					";
  	$sSql .= "       j14_nome              as nome,              			";
  	$sSql .= "       j13_descr             as bairro,                 ";
  	$sSql .= "       ''                    as vila                    ";
  	$sSql .= "  from ruas                                             ";
  	$sSql .= "       inner join ruastipo   on j88_codigo = j14_tipo   ";
  	$sSql .= "       left join  ruasbairro on j14_codigo = j16_lograd ";
  	$sSql .= "       left join  bairro     on j13_codi   = j16_bairro ";
  	  	
    $rsLogradouros= pg_query( $sSql );

    if ( !$rsLogradouros ){
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_logradouro' ) );
    }
    
    if( pg_num_rows($rsLogradouros) == 0 ){
    	throw new BusinessException( _M( self::MENSAGENS . 'nenhum_logradouro_encontrado' ) );
    }

    $oLogradouros  = db_utils::getCollectionByRecord( $rsLogradouros );

    return $oLogradouros;
  }

  /**
   * Retorna o Municipio para geração do arquivo
   * @return Object
   */
  private function getMunicipio() {
 	  	 
  	$sSql  = "select munic  										as nome,   ";
  	$sSql .= "       db21_codigomunicipoestado  as codigo  ";
  	$sSql .= "  from db_config                             ";
  	$sSql .= " where codigo = {$this->iInstit}						 ";
  
  	$rsMunicipio = pg_query($sSql);
  
  	if ( !$rsMunicipio ){
  		throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_municipio' ) );
  	}
  
  	if( pg_num_rows($rsMunicipio) == 0 ){
  		throw new BusinessException( _M( self::MENSAGENS . 'nenhum_municipio_encontrado' ) );
  	}
  	
  	$oMunicipio  = db_utils::fieldsMemory( $rsMunicipio, 0 );
  	
  	return $oMunicipio;
  }
  
}