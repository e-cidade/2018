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

/**
 * Classe utilizada Internamente em Métodos da classe db_utils, como se fosse um stdClass
 * @author $Author: dbrafael.nery $
 * @version $Revision: 1.37 $
 */
class _db_fields {}

/**
 * Classe com Utilitários comuns para Uso no Projeto
 * @abstract
 */
class db_utils {

  const ITERATION_CONTINUE = '$__DB_UTILS_::_ITERATION__CONTINUE__$';
  const ITERATION_BREAK    = '$__DB_UTILS_::_ITERATION__BREAK__$';
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
   * Transforma um Vetor em Objeto {stdClass}
   * @param      $aVetor
   * @param bool $mostra
   * @return stdClass
   */
  static function postMemory( $aVetor, $mostra = false ) {

    $oFields = new stdClass();
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
        require_once modification("classes/db_{$sClasse}_classe.php");
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
   * @return stdClass[]
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
   *
   * @deprecated - metodo depreciado - getCollectionByRecord
   * @param resource     $rsRecordset
   * @param bool $lFormata
   * @param bool $lMostra
   * @param bool $lEncode
   *
   * @return \stdClass[]
   */
  static function getColectionByRecord( $rsRecordset, $lFormata=false, $lMostra=false, $lEncode=false ) {
    return self::getCollectionByRecord( $rsRecordset, $lFormata, $lMostra, $lEncode );
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

  /**
   * Retorna uma objeto stdClass com os dados de  uma linha da dao selecionada
   * @param $oDao
   * @param $aKeys
   * @return null|stdClass
   */
  static function getRowFromDao($oDao, $aKeys) {

    $sSqlRow = call_user_func_array(array($oDao, "sql_query_file"), $aKeys);

    $rsRow   = $oDao->sql_record($sSqlRow);
    if ($oDao->numrows == 0) {
      return null;
    }
    return db_utils::fieldsMemory($rsRow, 0);
  }

  /**
   * Cria a representação da Linha de Query no formato especificado na Closure
   *
   * @param RecordSet  $rsRecord - Recordset do Resultado da query
   * @param Closure    $fRetorno - Função que descreverá o retorno
   * @param Integer    $iIndice  - Indice da linha do resultado, caso não seja informada pegará a próxima(fetch)
   *
   * @return mixed - Retorno informado na Closure
   */
  public static function makeFromRecord($rsRecord, Closure $fRetorno, $iIndice = null) {
    return $fRetorno(pg_fetch_object($rsRecord, $iIndice));
  }


  /**
   * Cria a representação da Coleção de Resultados de Query no formato especificado na Closure
   *
   * @param RecordSet  $rsRecord - Recordset do Resultado da query
   * @param Closure    $fRetorno - Função que descreverá o retorno
   *
   * @return array - Coleção criada
   */
  public static function makeCollectionFromRecord($rsRecord, Closure $fRetorno) {

    $aRetorno     = array();
    $iTotalLinhas = pg_num_rows($rsRecord);

    for ( $iIndice = 0; $iIndice < $iTotalLinhas; $iIndice++ ) {

      $mRetorno   = self::makeFromRecord($rsRecord, $fRetorno);

      if ($mRetorno === null || $mRetorno === self::ITERATION_CONTINUE) {
        continue;
      }

      if ($mRetorno === self::ITERATION_BREAK) {
        break;
      }

      $aRetorno[] = $mRetorno;
    }
    return $aRetorno;
  }

  /**
   * Verifica os dados postados e verificar se o conteudo enviado não ultrapassou o max_post_size,
   * onde o PHP não dispara nenhum alerta quando esse valor é ultrapassado, 
   * gerando apenas erros onde a variável POST/GET/REQUEST não for definida
   */
  public static function checkContentSize() {

    $maxPostSize = self::getBytesFromINIFormat(ini_get('post_max_size'));
    $contentSize = $_SERVER['CONTENT_LENGTH'];

    if ($contentSize > $maxPostSize) {
        
      $maxPostSize = DBString::formatSizeUnits($maxPostSize);
      $contentSize = DBString::formatSizeUnits($contentSize);
      throw new ParameterException(
        "Tamanho do conteúdo enviado({$contentSize}), ultrapassa o valor máximo permitido({$maxPostSize})."
      );
    }
    return true;
  }
    
  /**
   * Retorna a quantidade de bytes no formato do INI file
   */
  private static function getBytesFromINIFormat($valor) {

    $letra = '';

    if ($valor != '') {
      $letra = strtolower(
        $valor[strlen($valor) - 1]
      );
    }

    switch ($letra) {

      case 'g':
        $valor *= 1024;
      case 'm':
        $valor *= 1024;
      case 'k':
        $valor *= 1024;
    }

    return $valor;
  }
}
