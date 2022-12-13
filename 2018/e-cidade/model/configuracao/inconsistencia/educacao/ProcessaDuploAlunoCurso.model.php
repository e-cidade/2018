<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Processa as exceções encontradas quando tentamos remover os duplos do alunocurso
 * @package configuracao
 * @subpackage inconsistencia 
 * @subpackage educacao 
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploAlunoCurso implements IExcecaoProcessamentoDependencias {
  
  private $sMsgErro;
  
  /**
   * A tabela alunocurso não pode ter 2 registros do mesmo aluno, portanto 
   * devemos remover os registro de alunopossib e alunocurso do aluno iformado como incorreto
   *  
   * @param integer $iChaveCorreta código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoAlunoCurso = new cl_alunocurso;
    $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query_file(null, "*", null, "ed56_i_aluno = {$iChaveIncorreta}");
    $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
    $iAlunosCursos  = $oDaoAlunoCurso->numrows;
    
    if ($iAlunosCursos > 0) {
      
      return $this->removeRegistros(db_utils::getCollectionByRecord($rsAlunoCurso));
    }
    return true;
  }
  
  /**
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMsgErro;
  }
  
  
  /**
   * Remove os vinculos da alunocurso e suas tabelas filhas
   * @param array $aRemover
   * @return boolean
   */
  private function removeRegistros($aRemover) {
    
    $oDaoAlunoCurso  = new cl_alunocurso;
    $oDaoAlunoPossib = new cl_alunopossib;
    
    foreach ($aRemover as $oRemover) {
      
      $oDaoAlunoPossib->excluir(null, "ed79_i_alunocurso = {$oRemover->ed56_i_codigo}");
      if ($oDaoAlunoPossib->erro_status == 0) {
        
        $this->sMsgErro  = str_replace("\\n", "\n", $oDaoAlunoPossib->erro_sql);
        return false;
      }
      
      $oDaoAlunoCurso->excluir($oRemover->ed56_i_codigo);
      if ($oDaoAlunoCurso->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoAlunoCurso->erro_sql);
        return false;
      }
    }
    
    return true;
  }
  
}