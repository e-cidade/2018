<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

class AvaliacaoQuestionarioInternoMenu {
  
  protected $iCodigoQuestionarioInternoMenu;  
  protected $iQuestionario;
  protected $iMenu;
  protected $iModulo;
  
  function __construct($iCodigoQuestionarioInternoMenu=null){
    
    if (!empty($iCodigoQuestionarioInternoMenu) ){
      
      $oDaoQuestionarioInternoMenu       = db_utils::getDao("avaliacaoquestionariointernomenu");
      $sSqlDadosQuestionarioInternoMenu  = $oDaoQuestionarioInternoMenu->sql_query($iCodigoQuestionarioInternoMenu);
      $rsDadosQuestionarioInternoMenu    = $oDaoQuestionarioInternoMenu->sql_record($sSqlDadosQuestionarioInternoMenu);

      if(!$rsDadosQuestionarioInternoMenu){

        throw new DBException("Erro ao buscar itens de menu do questionario");
      }

      if ($oDaoQuestionarioInternoMenu->numrows > 0){
        
        $oDadosQuestionarioInternoMenu        = db_utils::fieldsMemory($rsDadosQuestionarioInternoMenu, 0);
        $this->iCodigoQuestionarioInternoMenu = $iCodigoQuestionarioInternoMenu;
        $this->iQuestionario                  = $oDadosQuestionarioInterno->db171_questionario;        
        $this->iMenu                          = $oDadosQuestionarioInterno->db171_menu;
        $this->iModulo                        = $oDadosQuestionarioInterno->db171_modulo;
        unset($oDadosQuestionarioInternoMenu);
      }
    }

    $this->tipo = __CLASS__;
  }

  /**
   * Retorna o código do questionário
   * @return int iQuestionario
   */
  public function getQuestionario(){
    return $this->iQuestionario;
  }

  /**
   * Seta o código do Questionario
   * @param int $iQuestionario retorna Object $this
   */
  public function setQuestionario($iQuestionario){

    $this->iQuestionario = $iQuestionario;
    return $this;
  }

  /**
   * Retorna o código do menu
   * @return int iMenu
   */
  public function getMenu(){
    return $this->iMenu;
  }

  /**
   * Seta o código do Menu
   * @param int $iMenu retorna Object $this
   */
  public function setMenu($iMenu){

    $this->iMenu = $iMenu;
    return $this;
  }

  /**
   * Retorna o código do modulo
   * @return int iModulo
   */
  public function getModulo(){
    return $this->iModulo;
  }

  /**
   * Seta o código do Módulo
   * @param int $iModulo retorna Object $this
   */
  public function setModulo($iModulo){

    $this->iModulo = $iModulo;
    return $this;
  }

  /**
   * persiste os dados do Menu do Questionario Interno
   *
   * @return Menu do Questionario Interno 
   */
  function salvar(){
  
    $oDaoQuestionarioInternoMenu                      = db_utils::getDao("avaliacaoquestionariointernomenu");
    $oDaoQuestionarioInternoMenu->db171_questionario  = $this->iQuestionario;
    $oDaoQuestionarioInternoMenu->db171_menu          = $this->iMenu; 
    $oDaoQuestionarioInternoMenu->db171_modulo        = $this->iModulo; 

    $oDaoQuestionarioInternoMenu->incluir(null);

    if ($oDaoQuestionarioInternoMenu->erro_status == 0) {
      throw new Exception($oDaoQuestionarioInternoMenu->erro_msg);
    }
    return $this;
  }

  function __destruct(){
  }
}
?>