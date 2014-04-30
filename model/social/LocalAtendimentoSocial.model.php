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

/**
 * Classe para controle das informacoes referentes a um local de atendimento CRAS / CREAS do Social
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package social
 */
class LocalAtendimentoSocial {
  
  /**
   * Codigo de LocalAtendimentoSocial
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Tipo do atendimento
   * 1 - CRAS
   * 2 - CREAS
   * @var integer
   */
  private $iTipo;
  
  /**
   * Descricao do local de atendimento
   * @var string
   */
  private $sDescricao;
  
  /**
   * Identificador unico do local de atendimento
   * @var string
   */
  private $sIdentificadorUnico;
  
  /**
   * Instancia do departamento
   * @var DBDepartamento
   */
  private $oDbDepart;
  
  /**
   * Controla se existe algum cidadao com vinculo
   * @var boolean
   */
  private $lTemVinculo = false;

  /**
   * Constantes referentes ao tipo
   * @var integer
   */
  CONST CRAS = 1;
  CONST CREAS = 2;
  
  /**
   * Construtor da classe. Recebe o codigo de localatendimentosocial como parametro. Caso seja diferente de vazio, 
   * setamos as outras propriedades
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoLocalAtendimentoSocial = new cl_localatendimentosocial();
      $sSqlLocalAtendimentoSocial = $oDaoLocalAtendimentoSocial->sql_query_file($iCodigo);
      $rsLocalAtendimentoSocial   = $oDaoLocalAtendimentoSocial->sql_record($sSqlLocalAtendimentoSocial);
      
      if ($oDaoLocalAtendimentoSocial->numrows > 0) {
        
        $oDadosLocalAtendimentoSocial = db_utils::fieldsMemory($rsLocalAtendimentoSocial, 0);
        $this->iCodigo                = $oDadosLocalAtendimentoSocial->as16_sequencial;
        $this->sDescricao             = $oDadosLocalAtendimentoSocial->as16_descricao;
        $this->sIdentificadorUnico    = $oDadosLocalAtendimentoSocial->as16_identificadorunico;
        $this->oDbDepart              = new DBDepartamento($oDadosLocalAtendimentoSocial->as16_db_depart);
        $this->iTipo                  = LocalAtendimentoSocial::CRAS;
        
        if ($oDadosLocalAtendimentoSocial->as16_tipo == 2) {
          $this->iTipo = LocalAtendimentoSocial::CREAS;
        }
      }
    }
  }

  /**
   * Retorna o codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o tipo
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Seta o tipo de atendimento do local
   * 1 - CRAS
   * 2 - CREAS
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    
    $this->iTipo = LocalAtendimentoSocial::CRAS;
        
    if ($iTipo == 2) {
      $this->iTipo = LocalAtendimentoSocial::CREAS;
    }
  }

  /**
   * Retorna a descricao do local de atendimento
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao do local de atendimento
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o identificador unico do local de atendimento
   * @return string
   */
  public function getIdentificadorUnico() {
    return $this->sIdentificadorUnico;
  }

  /**
   * Seta o identificador unico do local de atendimento
   * @param string $sIdentificadorUnico
   */
  public function setIdentificadorUnico($sIdentificadorUnico) {
    $this->sIdentificadorUnico = $sIdentificadorUnico;
  }

  /**
   * Retorna uma instancia do departamento
   * @return DBDepartamento
   */
  public function getDbDepart() {
    return $this->oDbDepart;
  }

  /**
   * Seta uma instancia do departamento
   * @param DBDepartamento $oDbDepart
   */
  public function setDbDepart(DBDepartamento $oDbDepart) {
    $this->oDbDepart = $oDbDepart;
  }
  
  /**
   * Retorna se existe alguma familia vinculada e ativa com o local de atendimento
   * @return boolean
   */
  public function temVinculo() {
    
    $oDaoLocalAtendimentoFamilia   = new cl_localatendimentofamilia();
    $sWhereLocalAtendimentoFamilia = "as23_localatendimentosocial = {$this->iCodigo} and as23_ativo is true";
    $sSqlLocalAtendimentoFamilia   = $oDaoLocalAtendimentoFamilia->sql_query_file(
                                                                                   null,
                                                                                   "as23_sequencial",
                                                                                   null,
                                                                                   $sWhereLocalAtendimentoFamilia
                                                                                 );
    $rsLocalAtendimentoFamilia     = $oDaoLocalAtendimentoFamilia->sql_record($sSqlLocalAtendimentoFamilia);
    
    if ($oDaoLocalAtendimentoFamilia->numrows > 0) {
      $this->lTemVinculo = true;
    }
    
    return $this->lTemVinculo;
  }
  
  /**
   * Persistimos os dados de localatendimentosocial
   * @throws BusinessException
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }
    
    $oDaoLocalAtendimentoSocial                          = new cl_localatendimentosocial();
    $oDaoLocalAtendimentoSocial->as16_tipo               = $this->getTipo();
    $oDaoLocalAtendimentoSocial->as16_descricao          = $this->getDescricao();
    $oDaoLocalAtendimentoSocial->as16_identificadorunico = $this->getIdentificadorUnico();
    $oDaoLocalAtendimentoSocial->as16_db_depart          = $this->getDbDepart()->getCodigo();
    
    if (!empty($this->iCodigo)) {
      
      $oDaoLocalAtendimentoSocial->as16_sequencial = $this->iCodigo;
      $oDaoLocalAtendimentoSocial->alterar($this->iCodigo);
    } else {
      
      $oDaoLocalAtendimentoSocial->incluir(null);
      $this->iCodigo = $oDaoLocalAtendimentoSocial->as16_sequencial;
    }
    
    if ($oDaoLocalAtendimentoSocial->erro_status == 0) {
      
      $sMsgErro  = "Erro ao salvar o local de atendimento.";
      $sMsgErro .= str_replace("\\n", "\n",$oDaoLocalAtendimentoSocial->erro_msg);
      throw new BusinessException($sMsgErro);
    }
  }
  
  public function removerLocalAtendimento() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }
    
    $oDaoLocalAtendimentoSocial                  = new cl_localatendimentosocial();
    $oDaoLocalAtendimentoSocial->as16_sequencial = $this->iCodigo;
    $oDaoLocalAtendimentoSocial->excluir($this->iCodigo);
    
    if ($oDaoLocalAtendimentoSocial->erro_status == 0) {
    
      $sMsgErro  = "Erro ao excluir o local de atendimento.";
      $sMsgErro .= "\n\nErro técnico: ";
      $sMsgErro .= str_replace("\\n", "\n",$oDaoLocalAtendimentoSocial->erro_msg);
      throw new BusinessException($sMsgErro);
    }
  }
}