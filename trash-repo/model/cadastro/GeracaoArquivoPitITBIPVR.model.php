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
 * Classe responsável pela geração do arquivo PIT do ITBI-PVR
 * @author  Tales Baz <tales.baz@dbseller.com.br>
 * @package Cadastro
 */
class GeracaoArquivoPitITBIPVR {
    
	/**
	 * Caminho do JSON com as mensagens
	 */
	const MENSAGENS = 'arrecadacao.cadastro.GeracaoArquivoPitITBIPVR.';
	
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
  private $aErros = array();

  /**
   * Função construtora para geração de arquivo ITBI-PVR
   * @param integer $iAno      Ano que deve ser usado para a geração de Arquivo
   * @param integer $iSemestre Semestre que deve ser usado para a geração do Arquivo
   */
  function __construct( $iAno, $iSemestre ){
    
    $this->iAno         = $iAno;
    $this->iSemestre     = $iSemestre;
    $this->iInstit      = db_getsession('DB_instit');
    
    $this->oDomDocument = new DOMDocument('1.0', 'iso-8859-1');
    $this->oDomDocument->xmlStandalone = true;
  }

  /**
   * Função para geração de arquivo XML ITBI-PVR
   * @return string $sCaminhoArquivo
   */
  public function geraArquivo(){

    $oInformacao = $this->oDomDocument->createElement("INFORMACAO");
    $oInformacao->setAttribute('tipo', 'ITBI-PVR');
    $oInformacao->setAttribute('versao', $this->oDomDocument->xmlVersion);
    
    $this->oDomDocument->appendChild($oInformacao);
        
    /**
     * Busca dados Municipio
     */
    $oMunicipio = $this->getMunicipio();
    $this->escreveMunicipio( $oInformacao, $oMunicipio );
        
    /**
     * Percorre a lista de localidades
     * <local>
     */
    foreach ( $this->getLocalidades() as $oLocalidades ) {

      $oLocalidade = $this->escreveLocalidade($oLocalidades);
      $oInformacao->appendChild($oLocalidade);
    }
        
    $sCaminhoArquivo = "tmp/ITBIPVR_{$oMunicipio->codigo}_{$this->iSemestre}_{$this->iAno}.xml";
    
   /**
    * Verifica se variavel de erros é diferente de 0
    * pois caso seja não conseguiu encontrar o municipio ou localidades
    * e em ambos os casos o arquivo não deve ser gerado
    */
    if( count( $this->aErros ) > 0 ){
      return false;
    }

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
   * Função utilizada para realizar o registro dos 
   * erros no array $aErros.
   * @param  integer $sMensagem mensagem de erro
   */
  private function registraErros( $sMensagem ) {
    $this->aErros[] = $sMensagem;
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
   * Cria a tag localidade 
   * @param  Object $oLocalidades
   * @return Object
   */
  private function escreveLocalidade( $oLocalidades ){

    $oLocal = $this->oDomDocument->createElement('local');
    $oLocal->setAttribute('distrito',        $oLocalidades->distrito );
    $oLocal->setAttribute('localidade',      utf8_encode( $oLocalidades->localidade ) );
    $oLocal->setAttribute('valor_minimo_ha', number_format( $oLocalidades->valor_minimo_ha, 5, ',', '') );
    $oLocal->setAttribute('valor_maximo_ha', number_format( $oLocalidades->valor_maximo_ha, 5, ',', '') );
    $oLocal->setAttribute('Tipo_utilizacao', utf8_encode( $oLocalidades->tipo_utilizacao ) );
    $oLocal->setAttribute('topografia',      utf8_encode( $oLocalidades->topografia ) );
    return $oLocal;
  }
    
  /**
   * Retorna as localidades para geração do arquivo
   * @return Object
   */
  private function getLocalidades() {

    $sDataInicial = $this->iAno . '-01-01';
    $sDataFinal   = $this->iAno . '-06-30';
    
    if ( $this->iSemestre == 2 ) {
      $sDataInicial = $this->iAno . '-07-01';
      $sDataFinal   = $this->iAno . '-12-31';
    }

  	$sSql  = "select 1                                                                                      as distrito,        ";
  	$sSql .= "       j137_descricao                                                                         as localidade,      ";
  	$sSql .= "       j137_valorminimo                                                                       as valor_minimo_ha, ";
  	$sSql .= "       j137_valormaximo                                                                       as valor_maximo_ha, ";
  	$sSql .= "       ( select array_to_string( array_accum( distinct j31_descr ), ', ' ) as caracteristicas                     ";
  	$sSql .= "           from itbilocalidaderural                                                                               ";
  	$sSql .= "                inner join itbiruralcaract    on it33_guia                 = it19_guia                            ";
  	$sSql .= "                inner join caracter           on j31_codigo                = it19_codigo                          ";
  	$sSql .= "                inner join paritbi            on it24_grupoutilterrarural  = j31_grupo                            ";
    $sSql .= "                inner join itbiavalia         on it14_guia  = it19_guia                                           ";
    $sSql .= "                                             and it14_dtliber between '$sDataInicial' and '$sDataFinal'           ";
  	$sSql .= "          where it19_valor <> 0                                                                                   ";
  	$sSql .= "            and itbilocalidaderural.it33_localidaderural = localidaderural.j137_sequencial )  as tipo_utilizacao, ";
  	$sSql .= "       ( select array_to_string(array_accum( distinct j31_descr ), ', ') as caracteristicas                       ";
  	$sSql .= "           from itbilocalidaderural                                                                               ";
  	$sSql .= "                inner join itbiruralcaract    on it33_guia                 = it19_guia                            ";
  	$sSql .= "                inner join caracter           on j31_codigo                = it19_codigo                          ";
  	$sSql .= "                inner join paritbi            on it24_grupodistrterrarural = j31_grupo                            ";
    $sSql .= "                inner join itbiavalia         on it14_guia  = it19_guia                                           ";
    $sSql .= "                                             and it14_dtliber between '$sDataInicial' and '$sDataFinal'           ";
  	$sSql .= "          where it19_valor <> 0                                                                                   ";
  	$sSql .= "            and itbilocalidaderural.it33_localidaderural = localidaderural.j137_sequencial )  as topografia       ";
  	$sSql .= "  from localidaderural                                                                                            ";
    $sSql .= "       inner join itbilocalidaderural on it33_localidaderural  = j137_sequencial                                  ";
    $sSql .= "       inner join itbiavalia          on it14_guia             = it33_guia                                        ";
    $sSql .= "                                     and it14_dtliber between '$sDataInicial' and '$sDataFinal'                   ";
    $sSql .= "        left join itbicancela       on it16_guia   = itbilocalidaderural.it33_guia                                ";
    $sSql .= " where it16_guia is null                                                                                          ";
  	
    $rsLocalidades = pg_query( $sSql );

    if ( !$rsLocalidades ){
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_localidade' ) );
    }
    
    if( pg_num_rows($rsLocalidades) == 0 ){
    	$this->registraErros("ERRO SQL: pg_numrows = 0 SQL: getLocalidades()");
    }

    $oLocalidades  = db_utils::getCollectionByRecord( $rsLocalidades );

    return $oLocalidades;
  }

  /**
   * Retorna o Municipio para geração do arquivo
   * @return Object
   */
  private function getMunicipio() {
 	  	 
  	$sSql  = "select munic  										as nome, ";
  	$sSql .= "       db21_codigomunicipoestado  as codigo";
  	$sSql .= "  from db_config                           ";
  	$sSql .= " where codigo = {$this->iInstit}					 ";
  
  	$rsMunicipio = pg_query($sSql);
  
  	if ( !$rsMunicipio ){
  		throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_municipio' ) );
  	}
  
  	if( pg_num_rows($rsMunicipio) == 0 ){
  		$this->registraErros("ERRO SQL: pg_numrows = 0 SQL: getMunicipio()");
  	}
  	
  	$oMunicipio  = db_utils::fieldsMemory( $rsMunicipio, 0 );
  	
  	return $oMunicipio;
  }
  
}