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

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Processa as exce��es encontradas quando tentamos remover os duplos da alunoprimat
 * @package configuracao
 * @subpackage inconsistencia
 * @subpackage educacao
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploAlunoPriMat implements IExcecaoProcessamentoDependencias {

  private $sMsgErro;

  /**
   * A tabela alunoprimat n�o pode ter 2 registros do mesmo aluno, portanto
   * devemos remover do aluno informado como incorr�to
   *
   * @param integer $iChaveCorreta   c�digo do aluno corr�to
   * @param integer $iChaveIncorreta c�digo do aluno que deve ser substituido / removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoAlunoPriMat = new cl_alunoprimat();
    $oDaoAlunoPriMat->excluir(null, "ed76_i_aluno = {$iChaveIncorreta}");
    
    
    if ($oDaoAlunoPriMat->erro_status == 0) {

      $this->sMsgErro  = "Erro ao excluir registro da tabela alunoprimat. ";
      $this->sMsgErro .= "Registro incorr�to: {$iChaveIncorreta} \n";
      
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