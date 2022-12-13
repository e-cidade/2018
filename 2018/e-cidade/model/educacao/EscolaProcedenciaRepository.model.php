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

define( 'MENSAGENS_ESCOLAPROCEDENCIA_REPOSITORY', 'educacao.escola.EscolaProcedenciaRepository.' );

/**
 * Classe repository para classes EscolaProcedencia
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package educacao
 */
class EscolaProcedenciaRepository {

  /**
   * Collection de EscolaProcedencia
   * @var array
   */
  private $aEscolas = array();

  /**
   * Instancia da classe
   * @var EscolaProcedenciaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia do EscolaProcedencia pelo Codigo
   * @param integer $iCodigo Codigo do EscolaProcedencia
   * @return EscolaProcedencia
   */
  public static function getEscolaByCodigo($iCodigoEscola) {

    if (!array_key_exists($iCodigoEscola, EscolaProcedenciaRepository::getInstance()->aEscolas)) {
      EscolaProcedenciaRepository::getInstance()->aEscolas[$iCodigoEscola] = new EscolaProcedencia($iCodigoEscola);
    }
    return EscolaProcedenciaRepository::getInstance()->aEscolas[$iCodigoEscola];
  }

  /**
   * Retorna a instancia da classe
   * @return EscolaProcedenciaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new EscolaProcedenciaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um EscolaProcedencia dao repositorio
   * @param EscolaProcedencia $oEscolaProcedencia Instancia do EscolaProcedencia
   * @return boolean
   */
  public static function adicionarEscolaProcedencia(EscolaProcedencia $oEscolaProcedencia) {

    if(!array_key_exists($oEscolaProcedencia->getCodigo(), EscolaProcedenciaRepository::getInstance()->aEscolas)) {
      EscolaProcedenciaRepository::getInstance()->aEscolas[$oEscolaProcedencia->getCodigo()] = $oEscolaProcedencia;
    }
    return true;
  }

  /**
   * Remove o EscolaProcedencia passado como parametro do repository
   * @param EscolaProcedencia $oEscolaProcedencia
   * @return boolean
   */
  public static function removerEscolaProcedencia(EscolaProcedencia $oEscolaProcedencia) {

    if (array_key_exists($oEscolaProcedencia->getCodigo(), EscolaProcedenciaRepository::getInstance()->aEscolas)) {
      unset(EscolaProcedenciaRepository::getInstance()->aEscolas[$oEscolaProcedencia->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna o total de escolas de procedencia
   * @return int
   */
  public static function getTotalEscolaProcedencia() {
    return count(EscolaProcedenciaRepository::getInstance()->aEscolas);
  }

  /**
   * @param bool $lSomenteAlunosForaRede
   * @return EscolaProcedencia[]
   * @throws DBException
   */
  public static function getTodasEscolasProcedencia( $lSomenteAlunosForaRede = false ) {

    $oDaoEscolaProcedencia   = new cl_escolaproc();
    $sWhereEscolaProcedencia = !$lSomenteAlunosForaRede ? "" : "ed76_i_codigo is not null and ed76_c_tipo = 'F'";
    $sSqlEscolaProcedencia   = $oDaoEscolaProcedencia->sql_query_escola_aluno_vinculado(
                                                                                         null,
                                                                                         'ed82_i_codigo',
                                                                                         'ed82_i_codigo',
                                                                                         $sWhereEscolaProcedencia
                                                                                       );
    $rsEscolaProcedencia   = db_query( $sSqlEscolaProcedencia );

    if( !$rsEscolaProcedencia ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_ESCOLAPROCEDENCIA_REPOSITORY . 'erro_buscar_escolas', $oErro ) );
    }

    if( pg_num_rows( $rsEscolaProcedencia ) > 0 ) {

      for( $iContador = 0; $iContador < pg_num_rows( $rsEscolaProcedencia ); $iContador++ ) {
        EscolaProcedenciaRepository::getEscolaByCodigo( db_utils::fieldsMemory( $rsEscolaProcedencia, $iContador )->ed82_i_codigo );
      }
    }

    return EscolaProcedenciaRepository::getInstance()->aEscolas;
  }
}