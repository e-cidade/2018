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
 * Classe para controle do local de atendimento de uma familia
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package social
 */
class LocalAtendimentoFamilia {
  
  /**
   * Codigo de localatendimentofamilia
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Instancia de LocalAtendimentoSocial
   * @var LocalAtendimentoSocial
   */
  private $oLocalAtendimentoSocial;
  
  /**
   * Instancia de Familia
   * @var Familia
   */
  private $oFamilia;
  
  /**
   * Instancia de DBDate com a data do vinculo
   * @var DBDate
   */
  private $oDataVinculo;
  
  /**
   * Instancia de DBDate com a data do fim do atendimento
   * @var DBDate
   */
  private $oFimAtendimento;
  
  /**
   * Observacao em relacao ao vinculo da familia com o local de atendimento
   * @var string
   */
  private $sObservacao;
  
  /**
   * Controle se o vinculo esta ativo
   * @var boolean
   */
  private $lAtivo = true;
  
  /**
   * Instancia de UsuarioSistema
   * @var UsuarioSistema
   */
  private $oUsuario;
  
  /**
   * Construtor da classe. Recebe um codigo como parametro e caso nao esteja vazio, busca e seta as demais propriedades
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoLocalAtendimentoFamilia = new cl_localatendimentofamilia();
      $sSqlLocalAtendimentoFamilia = $oDaoLocalAtendimentoFamilia->sql_query_file($iCodigo);
      $rsLocalAtendimentoFamilia   = $oDaoLocalAtendimentoFamilia->sql_record($sSqlLocalAtendimentoFamilia);
      
      if ($oDaoLocalAtendimentoFamilia->numrows > 0) {
        
        $oDadosLocalAtendimentoFamilia = db_utils::fieldsMemory($rsLocalAtendimentoFamilia, 0);
        $this->iCodigo                 = $oDadosLocalAtendimentoFamilia->as23_sequencial;
        $this->oLocalAtendimentoSocial = new LocalAtendimentoSocial($oDadosLocalAtendimentoFamilia->as23_localatendimentosocial);
        $this->oFamilia                = new Familia($oDadosLocalAtendimentoFamilia->as23_cidadaofamilia);
        $this->oDataVinculo            = new DBDate($oDadosLocalAtendimentoFamilia->as23_datavinculo);
        
        $oFimAtendimento = null;
        if (!empty($oDadosLocalAtendimentoFamilia->as23_fimatendimento)) {
          $oFimAtendimento = new DBDate($oDadosLocalAtendimentoFamilia->as23_fimatendimento);
        }
        
        $this->oFimAtendimento = $oFimAtendimento;
        $this->sObservacao     = $oDadosLocalAtendimentoFamilia->as23_observacao;
        $this->lAtivo          = $oDadosLocalAtendimentoFamilia->as23_ativo == 't' ? true : false;
        $this->oUsuario        = new UsuarioSistema($oDadosLocalAtendimentoFamilia->as23_db_usuario);
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
   * Retorna uma instancia de LocalAtendimentoSocial
   * @return LocalAtendimentoSocial
   */
  public function getLocalAtendimentoSocial() {
    return $this->oLocalAtendimentoSocial;
  }
  
  /**
   * Seta uma instancia de LocalAtendimentoSocial
   * @param LocalAtendimentoSocial $oLocalAtendimentoSocial
   */
  public function setLocalAtendimentoSocial(LocalAtendimentoSocial $oLocalAtendimentoSocial) {
    $this->oLocalAtendimentoSocial = $oLocalAtendimentoSocial;
  }
  
  /**
   * Retorna uma instancia de Familia
   * @return Familia
   */
  public function getFamilia() {
    return $this->oFamilia;
  }
  
  /**
   * Seta uma instancia de Familia
   * @param Familia $oFamilia
   */
  public function setFamilia(Familia $oFamilia) {
    $this->oFamilia = $oFamilia;
  }
  
  /**
   * Retorna uma instancia de DBDate com a data do vinculo
   * @return DBDate
   */
  public function getDataVinculo() {
    return $this->oDataVinculo;
  }
  
  /**
   * Seta uma instancia de DBDate da data do vinculo
   * @param DBDate $oDataVinculo
   */
  public function setDataVinculo(DBDate $oDataVinculo) {
    $this->oDataVinculo = $oDataVinculo;
  }
  
  /**
   * Retorna uma instancia de DBDate do fim do atendimento
   * @return DBDate
   */
  public function getFimAtendimento() {
    return $this->oFimAtendimento;
  }
  
  /**
   * Seta uma instancia de DBDate com a data do fim do atendimento
   * @param DBDate $oFimAtendimento
   */
  public function setFimAtendimento(DBDate $oFimAtendimento) {
    $this->oFimAtendimento = $oFimAtendimento;
  }
  
  /**
   * Retorna a observacao em relao ao vinculo
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
   * Seta uma observacao em relacao ao vinculo
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * Retorna se o vinculo esta ativo ou nao
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }
  
  /**
   * Seta se o vinculo esta ativo ou nao
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }
  
  /**
   * Retorna uma instancia de UsuarioSistema
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }
  
  /**
   * Seta uma instancia de UsuarioSistema
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }
  
  /**
   * Salvamos um vinculo da familia com um local de atendimento. Primeiramente, valida se existe um vinculo ativo para a
   * familia. Caso exista, encerramos o atendimento e inserimos um novo
   * @throws DBException
   * @throws BusinessException
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Não existe transação com o banco de dados ativa");
    }

    if ($this->getFamilia() != '' && $this->getFamilia()->getLocalAtendimentoAtual() != '') {
      
      $oLocalAtendimentoAtual = $this->getFamilia()->getLocalAtendimentoAtual();
      $oLocalAtendimentoAtual->setFimAtendimento($this->getDataVinculo());
      $oLocalAtendimentoAtual->setObservacao($this->getObservacao());
      $oLocalAtendimentoAtual->encerraAtendimento();
    }
    
    $oDaoLocalAtendimentoFamiliaInclusao                              = new cl_localatendimentofamilia();
    $oDaoLocalAtendimentoFamiliaInclusao->as23_localatendimentosocial = $this->getLocalAtendimentoSocial()->getCodigo();
    $oDaoLocalAtendimentoFamiliaInclusao->as23_cidadaofamilia         = $this->getFamilia()->getCodigoSequencial();
    $oDaoLocalAtendimentoFamiliaInclusao->as23_datavinculo            = $this->getDataVinculo()->getDate(DBDate::DATA_EN);
    $oDaoLocalAtendimentoFamiliaInclusao->as23_ativo                  = $this->isAtivo() ? 't' : 'f';
    $oDaoLocalAtendimentoFamiliaInclusao->as23_db_usuario             = $this->getUsuario()->getIdUsuario();
    $oDaoLocalAtendimentoFamiliaInclusao->incluir(null);
     
    if ($oDaoLocalAtendimentoFamiliaInclusao->erro_status == 0) {
      throw new BusinessException(str_replace("\n", '\\n', $oDaoLocalAtendimentoFamiliaInclusao->erro_msg));
    }
  }
  
  /**
   * Encerra o atendimento da familia com um local de atendimento
   * @throws BusinessException
   */
  public function encerraAtendimento() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Não existe transação com o banco de dados ativa");
    }
    
    $oDaoLocalAtendimentoFamilia                      = new cl_localatendimentofamilia();
    $oDaoLocalAtendimentoFamilia->as23_ativo          = 'false';
    $oDaoLocalAtendimentoFamilia->as23_fimatendimento = $this->getFimAtendimento()->getDate(DBDate::DATA_EN);
    $oDaoLocalAtendimentoFamilia->as23_observacao     = $this->getObservacao();
    $oDaoLocalAtendimentoFamilia->as23_sequencial     = $this->getCodigo();
    $oDaoLocalAtendimentoFamilia->alterar($this->getCodigo());
    
    if ($oDaoLocalAtendimentoFamilia->erro_status == 0) {
      throw new BusinessException(str_replace("\n", '\\n', $oDaoLocalAtendimentoFamilia->erro_msg));
    }
  }
}