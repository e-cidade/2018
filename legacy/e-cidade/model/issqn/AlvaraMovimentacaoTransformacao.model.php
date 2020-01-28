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

require_once 'model/issqn/AlvaraMovimentacao.model.php';

/**
 * @deprecated
 * @see model/issqn/alvara/TransformacaoAlvara.model.php
 */
class AlvaraMovimentacaoTransformacao extends AlvaraMovimentacao {

  /**
   * Id da entidade issgrupotipoalvara 
   *
   * @var Integer
   */
  protected $iGrupoTipoAlvara;
  /**
   * Id da entidade isstipoalvara 
   *
   * @var Integer
   */  
  protected $iTipoAlvara;
  /**
   * Id da entidade issbase 
   *
   * @var Integer
   */  
  protected $iInscricao;
  
  /**
   * Construtor da Classe.
   * Deve ser passado o Cуdigo do Alvarб por parвmetro
   *
   * @param Integer $iCodAlvara
   */
  public function __construct($iCodAlvara) {
    parent::__construct($iCodAlvara);
  }
  
  /**
   * Este mйtodo insere uma nova movimentaзгo na entidade issmovalvara e altera o cуdigo do tipo do alvara na 
   * entidade issalvara
   *
   */
  public function transformar() {
    
    try {
      db_inicio_transacao();
      try {
        parent::salvar();
      } catch (Exception $oException) {
        throw new Exception($oException->getMessage());      
      }
      
      $oDaoIssAlvara = db_utils::getDAO("issalvara",true);

      $oDaoIssAlvara->q123_isstipoalvara = $this->iTipoAlvara;
      $oDaoIssAlvara->q123_sequencial    = $this->getCodigoAlvara();      
      
      $oDaoIssAlvara->alterar($this->getCodigoAlvara());
      
      if($oDaoIssAlvara->erro_status == "0") {
        throw new Exception($oDaoIssAlvara->erro_msg);
      }
      db_inicio_transacao(false);
    } catch (Exception $oException) {
      echo $oException->getMessage(); 
      db_inicio_transacao(true);
    }
  }
  /**
   * Seta valor da Variavel $iGrupoTipoAlvara
   *
   * @param Integer $iGrupo
   */
  public function setGrupoTipo($iGrupo) {
    $this->iGrupoTipoAlvara = $iGrupo;
  }
    
  /**
   * Seta valor da Variavel $iTipoAlvara
   *
   * @param Integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipoAlvara = $iTipo ;
  }
  
  /**
   * Seta valor da Variavel $iInscricao
   *
   * @param Integer $iInscricao
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao ;
  }
  
  /**
  * Retorna valor da variбvel $iInscricao
  * @return integer $iInscricao
  */
  protected function getInscricao() {
    return $this->iInscricao;
  }
  
  /**
  * Retorna valor da variбvel $iGrupoTipoAlvara
  * @return integer $iGrupoTipoAlvara
  */
  protected function getGrupoTipo() {
    return $this->iGrupoTipoAlvara;
  }
  
  /**
  * Retorna valor da variбvel $iTipoAlvara
  * @return integer $iTipoAlvara
  */
  protected function getTipo() {
    return $this->iTipoAlvara;
  }
  
}


?>