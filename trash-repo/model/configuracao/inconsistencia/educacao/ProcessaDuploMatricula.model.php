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

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Processa as exceções encontradas quando tentamos remover os duplos da matricula
 * @package configuracao
 * @subpackage inconsistencia 
 * @subpackage educacao
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploMatricula implements IExcecaoProcessamentoDependencias {
  
  private $sMsgErro;
  
  /**
   * Processa as dependencias do aluno em relação a sua matricula.
   * O que faz?
   *  -- Se tiver duas matriculas ativas no mesmo ano, joga no log e não processa. 
   *  -- Se não dar update na matricula pelo aluno correto 
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoMatricula = new cl_matricula();
    
    $sCampos  = " matricula.ed60_i_codigo, matricula.ed60_matricula, matricula.ed60_i_turma, "; 
    $sCampos .= " turma.ed57_c_descr, calendario.ed52_i_ano ";

    $sWhere   = " ed60_i_aluno        = {$iChaveIncorreta} ";
    $sWhere  .= " and ed60_c_situacao = 'MATRICULADO' ";
    $sWhere  .= " and exists(select 1 ";
    $sWhere  .= "              from matricula manter ";
    $sWhere  .= "                   inner join turma t      on ed57_i_codigo   = manter.ed60_i_turma ";
    $sWhere  .= "                   inner join calendario c on c.ed52_i_codigo = t.ed57_i_calendario ";
    $sWhere  .= "             where manter.ed60_i_aluno    = {$iChaveCorreta}      ";
    $sWhere  .= "               and manter.ed60_c_situacao = 'MATRICULADO' ";
    $sWhere  .= "               and c.ed52_i_ano           = calendario.ed52_i_ano ";
    $sWhere  .= "           );";

    $sSqlMatricula = $oDaoMatricula->sql_query_matriculaanual(null, $sCampos, null, $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iMatriculas   = $oDaoMatricula->numrows;
    
    if ($iMatriculas > 0) {
      
      for ($i = 0; $i < $iMatriculas; $i++) {
        
        $oDadoMatricula  = db_utils::fieldsMemory($rsMatricula, $i);
        $this->sMsgErro  = " Aluno {$iChaveIncorreta} possui uma matrícula ativa para o ano ";
        $this->sMsgErro .= "{$oDadoMatricula->ed52_i_ano}. \n";
        $this->sMsgErro .= " Antes de remover o aluno deve ser excluída a matrícula : {$oDadoMatricula->ed60_matricula} ";
        $this->sMsgErro .= " na Turma: {$oDadoMatricula->ed60_i_turma} - {$oDadoMatricula->ed57_c_descr} \n";
        return false;
      }
    }
    
    return $this->alteraMatricula($iChaveCorreta, $iChaveIncorreta);  
  }
  
  /**
   * Passa o matricula do aluno incorreta para o aluno correto
   * @param integer $iChaveCorreta código do aluno correto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @return boolean
   */
  private function alteraMatricula($iChaveCorreta, $iChaveIncorreta) {
  
    $sSqlMatricula  = " update matricula                          ";
    $sSqlMatricula .= "    set ed60_i_aluno  = {$iChaveCorreta}   ";
    $sSqlMatricula .= "  where ed60_i_aluno  = {$iChaveIncorreta} ";
    $rsMatricula    = db_query($sSqlMatricula);
  
    if (!$rsMatricula) {
  
      $this->sMsgErro = $sSqlMatricula;
      return false;
    }
  
    return true;
  }
  
  
  /**
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMsgErro;
  }
}