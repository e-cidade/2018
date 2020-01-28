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
 * Repositoy para as turmas
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.5 $
 */
class TurmaRepository {

  /**
   * Array com instancias de Turma
   * @var array
   */
  private $aTurma = array();
  private static $oInstance;

  private function __construct() {

  }

  private function __clone(){

  }

  /**
   * Retorna a instancia do Repositorio
   * @return TurmaRepository
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new TurmaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a turma possui instancia, se não instancia e retorna a instancia de Turma
   * @param integer $iCodigoTurma
   * @return Turma
   */
  public static function getTurmaByCodigo($iCodigoTurma) {

    if (!array_key_exists($iCodigoTurma, TurmaRepository::getInstance()->aTurma)) {
      TurmaRepository::getInstance()->aTurma[$iCodigoTurma] = new Turma($iCodigoTurma);
    }

    return TurmaRepository::getInstance()->aTurma[$iCodigoTurma];

  }

  /**
   * Busca as turmas de uma escola
   * @param integer $iCodigoEscola
   */
  public static function getTurmaByEscola($iCodigoEscola) {

    $aTurmasEscola = array();
    $oDaoTurma     = db_utils::getDao('turma');
    $sWhere        = " ed57_i_escola = {$iCodigoEscola} ";
    $sSqlTurma     = $oDaoTurma->sql_query_turma(null, 'ed57_i_codigo, ed11_i_sequencia, ed57_c_descr',
                                                'ed52_i_ano,ed31_i_codigo, ed11_i_sequencia, ed57_c_descr', $sWhere);
    $rsTurma       = $oDaoTurma->sql_record($sSqlTurma);
    $iTotalLinhas  = $oDaoTurma->numrows;

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $oDadosTurma     = db_utils::fieldsMemory($rsTurma, $i);
        $aTurmasEscola[] = TurmaRepository::getInstance()->getTurmaByCodigo($oDadosTurma->ed57_i_codigo);
      }
    }
    return $aTurmasEscola;
  }

  /**
   * Adiciona uma Turma ao repositorio
   * @param Turma $oTurma
   */
  public static function adicionarTurma(Turma $oTurma) {

    TurmaRepository::getInstance()->aTurma[$oTurma->getCodigo()] = $oTurma;
    return true;
  }

  /**
   * Remove uma turma do repositorio
   * @param Turma $oTurma
   * @return boolean
   */
  public static function removerTurma(Turma $oTurma) {

    if (array_key_exists($oTurma->getCodigo(), TurmaRepository::getInstance()->aTurma)) {
      unset(TurmaRepository::getInstance()->aTurma[$oTurma->getCodigo()]);
    }
    return true;
  }

  /**
   * Busca as turmas de um docente
   * @param integer $iCodigoDocente
   * @return array
   */
  public static function getTurmaByDocente($iCodigoDocente) {

    $aTurmaDocente          = array();
    $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
    $sCamposRegenciaHorario = " ed58_i_codigo, ed57_i_codigo, ed57_c_descr ";
    $sWhereRegenciaHorario  = " ed58_i_rechumano = {$iCodigoDocente} ";
    $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null, $sCamposRegenciaHorario, null, $sWhereRegenciaHorario);
    $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
    $iTotalLinhas           = $oDaoRegenciaHorario->numrows;

    if ($iTotalLinhas > 0) {

      for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

        $oDadosTurma     = db_utils::fieldsMemory($rsRegenciaHorario, $iContador);
        $aTurmaDocente[] = TurmaRepository::getInstance()->getTurmaByCodigo($oDadosTurma->ed57_i_codigo);
      }
    }
    return $aTurmaDocente;
  }
  
  /**
   * 
   * @param integer $iTurmaSerieRegimeMat código do vinculo da turma com turmaserieregimemat 
   * @return Ambigous <Turma, null>
   */
  public static function getTurmaByCodigoTurmaSerieRegimeMat($iTurmaSerieRegimeMat) {
    
    $oDaoTurmaSerieRegimeMat = new cl_turmaserieregimemat();
    $sSqlTurmaSerieRegimeMat = $oDaoTurmaSerieRegimeMat->sql_query_file($iTurmaSerieRegimeMat, "ed220_i_turma");
    $rsTurmaSerieRegimeMat   = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieRegimeMat);
    
    if ($oDaoTurmaSerieRegimeMat->numrows == 1) {
      
      $iCodigoTurma = db_utils::fieldsMemory($rsTurmaSerieRegimeMat, 0)->ed220_i_turma;
      return TurmaRepository::getInstance()->getTurmaByCodigo($iCodigoTurma);
    }
    return null;
  }
}