<?
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
 * Classe utilizada Internamente em Métodos da classe db_utils, como se fosse um stdClass
 * @author $Author: dbrafael.nery $
 * @version $Revision: 1.22 $
 */
class _db_fields {}

/**
 * Classe com Utilitários comuns para Uso no Projeto
 * @abstract
 */
class db_utils {
  
  /**
   * Construtor da Classe
   */
  function db_utils() {}
  
  /**
   * Transforma uma linha do resultado SQL em Objeto 
   * @param resource (recordset) $rs
   * @param integer $idx     - Numero da Linha do Resultado SQL ( Inicia em 0(zero) )
   * @param boolean $formata - Testa se Deve Formatar o Valor Retornado
   * @param boolean $mostra  - Testa se Deve Mostrar o Resultado na Tela (Debug)
   * @param boolean $lEncode - Valida se Deve Codificar as strings de Saida como URL( urlencode() )
   * @return stdClass
   */
  static function fieldsMemory( $rs, $idx, $formata=false, $mostra = false, $lEncode=false ) {

    $oFields      = new stdClass();
    $numFields    = pg_num_fields($rs);
    $iTotalLinhas = pg_num_rows($rs); 
    for ($i = 0; $i < $numFields; $i++) {
      
      $sValor     = "";
      $sFieldName = @pg_field_name($rs, $i);
      $sFieldType = @pg_field_type($rs, $i);
      if ($iTotalLinhas > 0) {
         $sValor = trim(@pg_result($rs, $idx, $sFieldName));
      }
      if ($formata) {
        
        switch ($sFieldType) {
          
        case "date" :
          if ($sValor != null) {
            $sValor = implode('/',array_reverse(explode("-",$sValor)));
          }
        break;
        default :
          $sValor  = stripslashes($sValor);
         break;
        }
        
      }
      if ($mostra) {
        echo $sFieldName ." => ".$sValor." <br>";
      }
      if ($lEncode){

         switch ($sFieldType){

           case "bpchar":
              $sValor = urlencode($sValor);
           break;
           case "varchar":
              $sValor = urlencode($sValor);
           break;
           case "text":
              $sValor = urlencode($sValor);
           break;
           
         }
      }
      
      $oFields->$sFieldName = $sValor;
    }
    return $oFields;
  }
  
  /**
   * Transforma um Vetor em Objeto{_db_fields}
   * @param array  $aVetor
   * @param string $mostra
   * @return _db_fields
   */
  static function postMemory( $aVetor, $mostra = false ) {
    
    $oFields   = new _db_fields();
    
    for ($i = 0; $i < count($aVetor); $i++) {
      
      $sFieldName     = key($aVetor);
      $sValor         = current($aVetor);

      if ($mostra) {
        echo $sFieldName ." => ".$sValor." <br>\n";
      }
      $oFields->$sFieldName = $sValor;
      next($aVetor);
    }
    return $oFields;
  }

  /**
   * Metodo para carregar o arquivo de definição da classe requerida;
   * 
   * @param string  $sClasse   - Nome da Classe na Pasta Classes. 
   *                             Ex. db_arrecad_classe.php deve passar como parametro
   *                                 "arrecad"
   * @param boolean $rInstance - Testa se além de carregar arquivo deve também Instanciá-la
   * @return OBJECT|boolean - Objeto da Classe Instanciada ou Apenas confirmação do Carregamento
   */
  static function getDao( $sClasse, $lInstanciaClasse = true ){
  
     if (!class_exists("cl_{$sClasse}")){
        require_once "classes/db_{$sClasse}_classe.php";     
     }

     if ( $lInstanciaClasse ) {

       /**
     //  * @TODO modificar Eval por Chamada Dinamica 
     //  * $sNomeClasse = "cl_{$sClasse}";
     //  * $objRet      = new  {$sNomeClasse};
         */
        eval ("\$objRet = new cl_{$sClasse};");
        return $objRet;
     }
     return true;
  }

  /**
   * Retorna Coleção de Objetos de TODAS as Linhas do Result passado
   * 
   * @see db_utils::fieldsMemory()
   * @return _db_fields[]
   */
  static function getCollectionByRecord( $rsRecordset, $lFormata=false, $lMostra=false, $lEncode=false ) {

    $iINumRows = @pg_num_rows($rsRecordset);
    $aDButils  = array();
    
    if ( $iINumRows > 0 ) {

      for ($iIndice = 0; $iIndice < $iINumRows; $iIndice++ ) {
        $aDButils[] = self::fieldsMemory($rsRecordset,$iIndice,$lFormata,$lMostra,$lEncode);
      }
    } 
   return $aDButils;
  }
  
  /**
   * 
   * @deprecated - metodo depreciado - getCollectionByRecord 
   * 
   * @param unknown_type $rsRecordset
   * @param unknown_type $lFormata
   * @param unknown_type $lMostra
   * @param unknown_type $lEncode
   */
  static function getColectionByRecord( $rsRecordset, $lFormata=false, $lMostra=false, $lEncode=false ) {
    return self::getCollectionByRecord( $rsRecordset, $lFormata, $lMostra, $lEncode );
  }
  
  /**
   * Testa se a existe transacao ativa na conexao corrente;.
   * @param  resource - Conexao a Ser testada
   * @return boolean
   */
  static function inTransaction( $pConexao = null ) {
    
    if ( is_null($pConexao) ) {

      global $conn; 
      $pConexao = $conn;
    }
    
    $isIntransaction = false;
    $lStatus         = pg_transaction_status( $pConexao );
                                  
    switch( $lStatus ) {
      
      // sem transacao em  (0)
      case  PGSQL_TRANSACTION_IDLE:
        $isIntransaction = false;
      break;
         
      //em Transacao Ativa, comando sendo executado  (1)
      case PGSQL_TRANSACTION_ACTIVE:
        $isIntransaction = true;
      break;
        
      //transacao em andamento  (2)
      case PGSQL_TRANSACTION_INTRANS:
        $isIntransaction = true;
      break;
        
      //transacao com erro  (3)
      case  PGSQL_TRANSACTION_INERROR:
        $isIntransaction = false;
      break;   
        
      //falha na conexao; (4);
      case PGSQL_TRANSACTION_UNKNOWN:
        $isIntransaction = false;
      break;   
    }
    return $isIntransaction; 
  }

  /**
   * Valida se uma String está codificada como UTF-8
   * @param  string $string
   * @return boolean
   */
	static function isUTF8( $sString ) {
	  
	  if ( mb_detect_encoding($sString.'x', 'UTF-8, ISO-8859-1') == 'UTF-8'){
	  	return true; 
	  }
  	return false;
	}  

	/**
   * Valida se uma String está codificada como LATIN1(ISO-8859-1)
	 * @param  string $string
	 * @return boolean
	 */
  static function isLATIN1( $sString ) {
    
    if ( mb_detect_encoding($sString.'x', 'UTF-8, ISO-8859-1') == 'ISO-8859-1') {
      return true; 
    }     
    return false;
  }  	
}