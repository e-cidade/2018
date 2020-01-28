<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Repositoy para os alunos
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.5 $
 */
class AlunoRepository {

  private $aAluno = array();
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorna a instancia do Repositorio
   * @return AlunoRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new AlunoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se o aluno possui instancia, se não instancia e retorna a instancia de Aluno
   * @param integer $iCodigoAluno
   * @return Aluno
   */
  public static function getAlunoByCodigo($iCodigoAluno) {

    if (!array_key_exists($iCodigoAluno, AlunoRepository::getInstance()->aAluno)) {
      AlunoRepository::getInstance()->aAluno[$iCodigoAluno] = new Aluno($iCodigoAluno);
    }

    return AlunoRepository::getInstance()->aAluno[$iCodigoAluno];
  }

  /**
   * Busca o aluno pela matricula
   * @param integer $iMatricula
   * @return Aluno
   */
  public static function getAlunoByMatricula($iMatricula) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sSqlMatricula = $oDaoMatricula->sql_query_file($iMatricula, "ed60_i_aluno");
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    if ($oDaoMatricula->numrows > 0) {
      return AlunoRepository::getInstance()->getAlunoByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_aluno);
    }
    return false;
  }

  /**
   * Busca os Alunos de uma turma
   * @param integer $iTurma
   * @return array Aluno
   */
  public static function getAlunosByTurma(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = " ed60_i_turma         = {$oTurma->getCodigo()}";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula(null, "ed60_i_aluno, ed60_i_numaluno",
                                                    "ed60_i_numaluno, ed47_v_nome", $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalLinhas  = $oDaoMatricula->numrows;
    $aAlunosTurma  = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

       $iCodigoAluno   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_aluno;
       $aAlunosTurma[] = AlunoRepository::getInstance()->getAlunoByCodigo($iCodigoAluno);
      }
    }
    return $aAlunosTurma;
  }

  /**
   * Busca os Alunos de uma turma
   * @param integer $iTurma
   * @return array Aluno
   */
  public static function getAlunosByTurmaOrdemAlfabetica(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = " ed60_i_turma = {$oTurma->getCodigo()}";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula(null, "ed60_i_aluno, ed60_i_numaluno",
                                                              "ed47_v_nome, ed60_i_numaluno", $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalLinhas  = $oDaoMatricula->numrows;
    $aAlunosTurma  = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $iCodigoAluno   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_aluno;
        $aAlunosTurma[] = AlunoRepository::getInstance()->getAlunoByCodigo($iCodigoAluno);
      }
    }
    return $aAlunosTurma;
  }

  /**
   * Adiciona um Aluno ao repositorio
   * @param Aluno $oAluno
   * @return boolean
   */
  public static function adicionarAluno(Aluno $oAluno) {

    if(!array_key_exists($oAluno->getCodigoAluno(), AlunoRepository::getInstance()->aAluno)) {
      AlunoRepository::getInstance()->aAluno[$oAluno->getCodigoAluno()] = $oAluno;
    }
    return true;
  }

  /**
   * Remove um aluno do repository
   * @param Aluno $oAluno
   * @return boolean
   */
  public static function removerAluno(Aluno $oAluno) {

    if (array_key_exists($oAluno->getCodigoAluno(), AlunoRepository::getInstance()->aAluno)) {
      unset(AlunoRepository::getInstance()->aAluno[$oAluno->getCodigoAluno()]);
    }
    return true;
  }

}