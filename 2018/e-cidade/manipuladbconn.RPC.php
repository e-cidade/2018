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

require_once ('e-cidade/std/db_stdClass.php');
require_once ('libs/JSON.php');

/**
 * Constante do caminho onde se encontram os arquivos
 */
const CAMINHO_ARQUIVO = "libs/";

$oJson               = new services_json();
$oParam              = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno            = new stdClass();
$oRetorno->sMensagem = "";
$oRetorno->iStatus   = 1;

try {
  
  switch( $oParam->sExecuta ) {
  
    /**
     * ****************************************
     * Altera o conteúdo do arquivo db_conn.php
     * ****************************************
     */
  	case 'alterar':
  	  
  	  /**
  	   * Valida se já existe um arquivo de backup com conteúdo original do db_conn.php.
  	   * Não existindo, gera este backup
  	   */
  	  if ( !file_exists( CAMINHO_ARQUIVO."db_conn.php.bkp" ) ) {
  	    
  	    if ( !copy( CAMINHO_ARQUIVO."db_conn.php", CAMINHO_ARQUIVO."db_conn.php.bkp" ) ) {
  	      throw new Exception( "Erro ao gerar a cópia do arquivo db_conn.php" );
  	    }
  	  }
  	  
  	  /**
  	   * Abre o arquivo db_conn.php e limpa todo o conteúdo, para em seguida escrever o que foi passado como parâmetro
  	   */
  	  $oArquivo = fopen( CAMINHO_ARQUIVO."db_conn.php", "w+" );
  	  fwrite( $oArquivo, urldecode( db_stdClass::db_stripTagsJson( $oParam->sConteudo ) ) );
  	  fclose( $oArquivo );
  
  	  $oRetorno->sMensagem = urlencode( "Alterações realizadas com sucesso." );
  	  
  	  break;
  	  
  	/**
  	 * ********************************************************
  	 * Restaura o arquivo db_conn original, caso o mesmo exista
  	 * ********************************************************
  	 */
  	case 'restaurarArquivoOriginal':
  	  
  	  /**
  	   * Verifica se existe o arquivo com as configurações originais, gerando exceção caso não exista
  	   */
  	  if ( !file_exists( CAMINHO_ARQUIVO."db_conn.php.bkp" ) ) {
  	    throw new Exception( "Arquivo original de backup inexistente" );
  	  }
  	  
  	  /**
  	   * Valida se o arquivo alterado foi excluído
  	   */
  	  if ( !unlink( CAMINHO_ARQUIVO."db_conn.php" ) ) {
  	    throw new Exception( "Arquivo db_conn.php não excluído" );
  	  }
  	  
  	  /**
  	   * Valida se o arquivo das configurações originais foi renomeado corretamente
  	   */
  	  if ( !copy( CAMINHO_ARQUIVO."db_conn.php.bkp", CAMINHO_ARQUIVO."db_conn.php" ) ) {
  	    throw new Exception( "Erro ao copiar o arquivo das configurações originais." );
  	  }
  	  
  	  break;
  }
} catch ( Exception $oErro ) {
  
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
  $oRetorno->iStatus   = 2;
}

echo $oJson->encode( $oRetorno );
?>