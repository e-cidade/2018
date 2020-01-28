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
 * Classe para dados de ocorrencias de alunos
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.2 $
 * @package ocorrencia 
 */
require_once ('model/educacao/ocorrencia/Ocorrencia.model.php');

final class OcorrenciaFalta extends Ocorrencia {
 
  
  /**
   * Colecao de faltas da ocorrencia
   * @var array
   */
  private $aFaltas;

  /**
   * Tipo da ocorrencia;
   * @var integer
   */
  protected $iTipo = 1;
  
  /**
   * Adiciona uma falta a ocorrencia
   * @param Falta $oFalta
   * 
   */
  public function adicionarFalta(Falta $oFalta) {

    $this->aFaltas[] = $oFalta;
  }
  
  /**
   * Retorna as faltas da ocorrencia
   * @return array
   */
  public function getFaltas() {
    
    return $this->aFaltas;
  }
  
  /**
   * salva os dados da ocorrencia
   */
  public function salvar() {
    
    parent::salvar();
    $oDaoOcorrenciaFalta = db_utils::getDao("ocorrenciafalta");
    $oDaoOcorrenciaFalta->excluir(null, "ed104_ocorrencia = {$this->getCodigo()}");
    if ($oDaoOcorrenciaFalta->erro_status == 0) {
    
      $sMensagemErro   = "Erro ao salvar faltas da ocorrência.\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoOcorrenciaFalta->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
    
    /**
     * salvamos as faltas vinculadas a ocorrencia
     */
    foreach ($this->aFaltas as $oFalta) {
      
      $oDaoOcorrenciaFalta->ed104_diarioclassealunofalta = $oFalta->getCodigo();
      $oDaoOcorrenciaFalta->ed104_ocorrencia             = $this->getCodigo();
      $oDaoOcorrenciaFalta->incluir(null);
    }
    if ($oDaoOcorrenciaFalta->erro_status == 0) {
    
      $sMensagemErro   = "Erro ao salvar faltas da ocorrência.\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoOcorrenciaFalta->erro_msg}";
      throw new BusinessException($sMensagemErro);
    }
  }
}

?>