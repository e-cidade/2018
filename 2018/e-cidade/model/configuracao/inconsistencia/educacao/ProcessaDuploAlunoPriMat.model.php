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
 * Processa as exceções encontradas quando tentamos remover os duplos da alunoprimat
 * @package configuracao
 * @subpackage inconsistencia
 * @subpackage educacao
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploAlunoPriMat implements IExcecaoProcessamentoDependencias {

  private $sMsgErro;

  /**
   * A tabela alunoprimat não pode ter 2 registros do mesmo aluno, portanto
   * devemos remover do aluno informado como incorréto
   *
   * @param integer $iChaveCorreta   código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoAlunoPriMat = new cl_alunoprimat();
    $oDaoAlunoPriMat->excluir(null, "ed76_i_aluno = {$iChaveIncorreta}");
    
    
    if ($oDaoAlunoPriMat->erro_status == 0) {

      $this->sMsgErro  = "Erro ao excluir registro da tabela alunoprimat. ";
      $this->sMsgErro .= "Registro incorréto: {$iChaveIncorreta} \n";
      
      $this->sMsgErro = utf8_encode($this->sMsgErro);
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