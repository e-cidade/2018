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
 * Repositorio para os docentes
 * @package   Educacao
 * @author    Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version   $Revision: 1.5 $
 */
class DocenteRepository {

  /**
   * Array com as instancias de Docente
   * @var array
   */
  private $aDocente = array();

  private static $oInstance;

  private function __construct() {

  }

  private function __clone() {

  }

  /**
   * Retorna a instancia do Repositorio
   * @return DocenteRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new DocenteRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se o docente possui instancia. Caso não, cria e retorna a instancia de Docente
   * @param integer $iCodigoDocente
   * @return Docente
   */
  public static function getDocenteByCodigo($iCodigoDocente) {

    if (!array_key_exists($iCodigoDocente, DocenteRepository::getInstance()->aDocente)) {


      DocenteRepository::getInstance()->aDocente[$iCodigoDocente] = new Docente($iCodigoDocente);
    }
    return DocenteRepository::getInstance()->aDocente[$iCodigoDocente];
  }

  /**
   * Retorna uma instancia do docente pelo Código do recurso humano
   * @return Docente
   */
  public static function getDocenteByCodigoRecursosHumano($iCodigoRecursoHumano) {

    $iCodigoCgm    = '';
    $oDaoRecHumano = db_utils::getDao("rechumano");
    $sWhere        = " ed20_i_codigo = {$iCodigoRecursoHumano} ";
    $sSqlCodigoCgm = $oDaoRecHumano->sql_query_escola(null,
                                                      "distinct (case when rh01_numcgm is null
                                                                 then  ed285_i_cgm else rh01_numcgm end) as cgm",
                                                        null,
                                                       $sWhere
                                                     );
    $rsCodigoCgm  = $oDaoRecHumano->sql_record($sSqlCodigoCgm);
    if ($oDaoRecHumano->numrows > 0) {
      $iCodigoCgm = db_utils::fieldsMemory($rsCodigoCgm, 0)->cgm;
    }
    return DocenteRepository::getDocenteByCodigo($iCodigoCgm);
  }

  /**
   * Retorna uma instancia de Docente, caso o usuario logado seja um docente
   * @param integer $iCodigoUsuario - Codigo do usuario logado
   * @return Docente
   */
  public static function getDocenteLogado($iCodigoUsuario) {

    $oDocente         = null;
    $oDaoDBUsusario   = new cl_db_usuacgm;
    $sSqlDadosDocente = $oDaoDBUsusario->sql_query($iCodigoUsuario, "z01_numcgm");
    $rsDadosDocente   = $oDaoDBUsusario->sql_record($sSqlDadosDocente);

    if ($oDaoDBUsusario->numrows > 0) {

      $oDaoRecHumano = new cl_rechumano();

      $iCodigoCgm  = db_utils::fieldsMemory($rsDadosDocente, 0)->z01_numcgm;
      $sWhere      = " (rh01_numcgm = {$iCodigoCgm} or ed285_i_cgm = {$iCodigoCgm})";
      $sSqlDocente = $oDaoRecHumano->sql_query_escola(null,
                                                    "distinct ed20_i_codigo",
                                                     null,
                                                     $sWhere
      );
      $rsDocente = $oDaoRecHumano->sql_record($sSqlDocente);
      if ($rsDocente && $oDaoRecHumano->numrows > 0) {
        $oDocente = DocenteRepository::getDocenteByCodigo($iCodigoCgm);
      }
    }
    return $oDocente;
  }
}
?>