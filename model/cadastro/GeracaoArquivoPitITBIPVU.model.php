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
 * Classe responsável pela geração do arquivo PIT do ITBI-PVU
 * @author  Tales Baz <tales.baz@dbseller.com.br>
 * @package Cadastro
 */
class GeracaoArquivoPitITBIPVU {
    
	/**
	 * Caminho do JSON com as mensagens
	 */
	const MENSAGENS = 'arrecadacao.cadastro.GeracaoArquivoPitITBIPVU.';
	
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
   * Função construtora para geração de arquivo ITBI-PVU
   * @param integer $iAno      Ano que deve ser usado para a geração de Arquivo
   * @param integer $iSemestre Semestre que deve ser usado para a geração do Arquivo
   */
  function __construct( $iAno, $iSemestre ){
    
    $this->iAno         = $iAno;
    $this->iSemestre    = $iSemestre;
    $this->iInstit      = db_getsession('DB_instit');
    
    $this->oDomDocument = new DOMDocument('1.0', 'iso-8859-1');
    $this->oDomDocument->xmlStandalone = true;
  }

  /**
   * Função para geração de arquivo XML ITBI-PVU
   * @return string $sCaminhoArquivo
   */
  public function geraArquivo(){

    $oInformacao = $this->oDomDocument->createElement("INFORMACAO");
    $oInformacao->setAttribute('tipo', 'ITBI-PVU');
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
        
    $sCaminhoArquivo = "tmp/ITBIPVU_{$oMunicipio->codigo}_{$this->iSemestre}_{$this->iAno}.xml";
    
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
   * Função que retorna a faixa de números da rua 
   * @param integer $iCodigoRua
   * @throws DBException
   * @return object
   */
  private function getFaixaNumeros( $iCodigoRua ){
  
  	$sSql  = " select coalesce ( min(j39_numero), 0 ) as nro_inicial,  "; 
  	$sSql .= "				coalesce ( max(j39_numero), 0 ) as nro_final    ";
  	$sSql .= "   from iptuconstr											              "; 
  	$sSql .= "	where j39_codigo = $iCodigoRua                      ";
  	 
  	$rsFaixaNumeros = pg_query( $sSql );
  	
  	if ( !$rsFaixaNumeros ){
  		throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_faixanumero' ) );
  	}
  	
  	if( pg_num_rows( $rsFaixaNumeros ) == 0 ){
  	  return null;  	
  	}
  	
 		return db_utils::fieldsMemory( $rsFaixaNumeros, 0 );
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
    $oLocal->setAttribute('tipo_logradouro', $oLocalidades->tipo_logradouro );
    $oLocal->setAttribute('logradouro',      utf8_encode( $oLocalidades->logradouro ) );
    
    $oFaixaNumero = $this->getFaixaNumeros( $oLocalidades->codigo_rua );
    $iNroInicial  = 0;
    $iNroFinal    = 0;
    if( !empty( $oFaixaNumero ) ){
 
    	$iNroInicial = $oFaixaNumero->nro_inicial;
    	$iNroFinal   = $oFaixaNumero->nro_final;
    }
    
    $oLocal->setAttribute('nro_inicial',     $iNroInicial );
    $oLocal->setAttribute('nro_final', 			 $iNroFinal );
    $oLocal->setAttribute('vila', 					 '' );
    $oLocal->setAttribute('quadra', 				 utf8_encode( $oLocalidades->quadra ) );
    $oLocal->setAttribute('bairro', 				 utf8_encode( $oLocalidades->bairro ) );
    $oLocal->setAttribute('valor_m2',        number_format( $oLocalidades->valor_m2, 2, ',', '') );
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
  	
  	$sSql  = "select distinct                                                                                         ";
  	$sSql .= "       j88_descricao 					as tipo_logradouro,                                                       ";
  	$sSql .= "       j14_nome               as logradouro,                                                            ";
  	$sSql .= "       j14_codigo             as codigo_rua,                                                             ";
  	$sSql .= "       ''                     as vila,                                                                  ";
  	$sSql .= "       j34_quadra             as quadra,                                                                ";
  	$sSql .= "       j13_descr              as bairro,                                                                ";
  	$sSql .= "       j23_m2terr             as valor_m2                                                               ";
  	$sSql .= "                                                                                                        ";
  	$sSql .= "  from itbimatric                                                                                       ";
  	$sSql .= "       inner join itbi            on itbi.it01_guia            = itbimatric.it06_guia                   ";
  	$sSql .= "       inner join itburbano       on it05_guia                 = it01_guia                              ";
  	$sSql .= "       inner join itbidadosimovel on itbidadosimovel.it22_itbi = itbimatric.it06_guia                   ";
  	$sSql .= "       inner join iptubase        on iptubase.j01_matric       = itbimatric.it06_matric                 ";
  	$sSql .= "       inner join iptucalc        on iptucalc.j23_matric       = iptubase.j01_matric                    ";
  	$sSql .= "                                 and iptucalc.j23_anousu       = '{$this->iAno}'                        ";
  	$sSql .= "		   inner join lote            on lote.j34_idbql            = iptubase.j01_idbql                     ";
  	$sSql .= "		   inner join testpri         on testpri.j49_idbql         = lote.j34_idbql                         ";
  	$sSql .= "		   inner join ruas            on ruas.j14_codigo           = testpri.j49_codigo                     ";
  	$sSql .= "		   inner join ruastipo        on ruastipo.j88_codigo       = ruas.j14_tipo                          ";
  	$sSql .= "		   inner join bairro          on j34_bairro                = j13_codi                               ";
  	$sSql .= "		   inner join testada         on ruas.j14_codigo           = j36_codigo                             ";
  	$sSql .= "		   inner join itbiavalia      on it01_guia                 = it14_guia                              ";
  	$sSql .= "		                             and it14_dtliber BETWEEN '$sDataInicial'::date and '$sDataFinal'::date ";
    $sSql .= "        left join itbicancela       on it16_guia               = itbi.it01_guia                         ";
    $sSql .= " where it16_guia is null                                                                                ";
  	
    $rsLocalidades = pg_query( $sSql );

    if ( !$rsLocalidades ){
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_localidade' ) );
    }
    
    if( pg_num_rows($rsLocalidades) == 0 ){
    	$this->registraErros("ERRO SQL: pg_numrows = 0 SQL: getLocalidade()");
    }

    $oLocalidades  = db_utils::getCollectionByRecord( $rsLocalidades );

    return $oLocalidades;
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
  		$this->registraErros("ERRO SQL: pg_numrows = 0 SQL: getMunicipio()");
  	}
  	
  	$oMunicipio  = db_utils::fieldsMemory( $rsMunicipio, 0 );
  	
  	return $oMunicipio;
  }
  
}