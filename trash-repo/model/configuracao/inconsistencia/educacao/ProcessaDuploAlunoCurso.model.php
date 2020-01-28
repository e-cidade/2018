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
        
        echo "deu erro\n\n";
      
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoAlunoCurso->erro_sql);
        return false;
      }
    }
    
    return true;
  }
  
}