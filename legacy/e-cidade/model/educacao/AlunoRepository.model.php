<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Repositoy para os alunos
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.6 $
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
   * Verifica se o aluno possui instancia, se n�o instancia e retorna a instancia de Aluno
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
  
  /**
   * Retorna uma instancia de Aluno encontrado de acordo com os dados recebidos por par�metro
   * @param string $sNomeAluno
   * @param string $sNomeMae
   * @param DBDate $oDataNascimento
   * 
   * @return Aluno $oAluno
   */
  public static function getAlunoPorNomeDataNascimentoNomeMae( $sNomeAluno, $sNomeMae, DBDate $oDataNascimento ) {
    
    $oAluno       = null;
    $oDaoAluno    = new cl_aluno();
    $sWhereAluno  = "     ed47_v_nome = '{$sNomeAluno}' AND ed47_v_mae = '{$sNomeMae}'";
    $sWhereAluno .= " AND ed47_d_nasc = '{$oDataNascimento->getDate()}' ";
    $sSqlAluno    = $oDaoAluno->sql_query_file( null, "ed47_i_codigo", null, $sWhereAluno );
    $rsAluno      = db_query( $sSqlAluno );
    
    if ( pg_num_rows( $rsAluno ) > 0 ) {
      
      $iCodigoAluno = db_utils::fieldsMemory( $rsAluno, 0 )->ed47_i_codigo;
      $oAluno       = AlunoRepository::getAlunoByCodigo( $iCodigoAluno );
    }
    
    return $oAluno;
  }
}