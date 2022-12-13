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

/**
 * Classe que abstrai as Iniciativas de um programa
 * As iniciativas de um programa estão diretamente ligadas a uma Meta
 *
 * @author bruno.silva
 * @author acacio.schneider
 * @package orcamento
 * @version $Revision: 1.11 $
 */
class ProgramaIniciativa {

  /**
   * Sequencial da tabela orciniciativa
   * @var integer
   */
  private $iCodigoSequencial;

  /**
   * Descrição sucinta da iniciativa
   * @var string
   */
  private $sIniciativa;

  /**
   * Descrição completa da iniciativa
   * @var string
   */
  private $sDescricao;

  /**
   * Código da meta
   * @var integer
   */
  private $iCodigoMeta;

  /**
   * Meta a qual a iniciativa pertence
   * @var ProgramaMeta
   */
  protected $oProgramaMeta;
  
  /**
   * Projeto/Atividade a qual a iniciativa pertence
   * @var ProgramaProjetoAtividade
   */
  protected $oProjetoAtividade = null;

  /**
   * Ano inicial da iniciativa
   * 
   * @var integer
   * @access protected
   */
  protected $iAnoInicial;

  /**
   * Ano final da iniciativa
   * @var integer
   */
  protected $iAnoFinal;

  /**
   * Construtor da classe ProgramaIniciativa, recebe como parâmetro um sequencial da tabela orciniciativa
   * Caso contrário constrói um objeto vazio
   * @param  integer     $iCodigoSequencial
   * @throws DBException
   */
  public function __construct($iCodigoSequencial = null) {

    if (!empty($iCodigoSequencial)) {

      $oDAOOrciniciativa = db_utils::getDao("orciniciativa");
      $sSQL              = $oDAOOrciniciativa->sql_query_file(null, "*", null, "o147_sequencial ={$iCodigoSequencial}");
      $rsResultado       = $oDAOOrciniciativa->sql_record($sSQL);

      if ($oDAOOrciniciativa->erro_status == "0") {

        $sMensagemErro  = "Erro Técnico: erro ao carregar dados da Iniciativa {$iCodigoSequencial}.";
        $sMensagemErro .= $oDAOOrciniciativa->erro_msg;
        throw new DBException($sMensagemErro);
      }

      $oIniciativa             = db_utils::fieldsMemory($rsResultado, 0);
      $this->iCodigoSequencial = $oIniciativa->o147_sequencial;
      $this->sDescricao        = $oIniciativa->o147_descricao;
      $this->sIniciativa       = $oIniciativa->o147_iniciativa;
      $this->iCodigoMeta       = $oIniciativa->o147_orcmeta;
      $this->iAnoInicial       = $oIniciativa->o147_ano;
      $this->iAnoFinal         = $oIniciativa->o147_anofinal;
    }
  }

  /**
   * Recupera a propriedade do sequencial da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_sequencial (não nulo)
   * @return integer
   */
  public function getCodigoSequencial() {
    return $this->iCodigoSequencial;
  }

  /**
   * Atribui na propriedade do sequencial da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_sequencial (não nulo)
   * @return integer
   */
  public function setCodigoSequencial($iCodigoSequencial) {
    $this->iCodigoSequencial = $iCodigoSequencial;
  }

  /**
   * Recupera a propriedade da descrição da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_iniciativa(text)
   * @return string
   */
  public function getIniciativa() {
    return $this->sIniciativa;
  }

  /**
   * Atribui na propriedade da descrição da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_iniciativa(text)
   * @return string
   */
  public function setIniciativa($sIniciativa) {
    $this->sIniciativa = $sIniciativa;
  }

  /**
   * Recupera a propriedade da descrição sucinta da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_descricao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Atribui na propriedade da descrição sucinta da Iniciativa, presente na tabela orciniciativa
   * campo:   o147_descricao
   * @return string
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o código da meta
   * @return integer
   */
  public function getCodigoMeta() {
    return $this->iCodigoMeta;
  }

  /**
   * Seta o código da meta
   * @param integer $iCodigoMeta
   */
  public function setCodigoMeta($iCodigoMeta) {
    $this->iCodigoMeta = $iCodigoMeta;
  }

  /**
   * Retorna no ano inicial da iniciativa
   * 
   * @access public
   * @return integer
   */
  public function getAno() {
    return $this->iAnoInicial;
  }

  /**
   * Define o ano inicial da iniciativa
   * 
   * @param integer $iAnoInicial 
   * @access public
   * @return void
   */
  public function setAno($iAnoInicial) {
    $this->iAnoInicial = $iAnoInicial;
  }

  /**
   * Retorna no ano final da iniciativa
   * @access public
   * @return integer
   */
  public function getAnoFinal() {
    return $this->iAnoFinal;
  }

  /**
   * Define o ano final da iniciativa
   * @param integer $iAnoFinal
   * @access public
   * @return void
   */
  public function setAnoFinal($iAnoFinal) {
    $this->iAnoFinal = $iAnoFinal;
  }

  /**
   * Exclui o vínculo existente entre o projeto atividade e a iniciativa da tabela orciniciativa
   * @throws DBException
   */
  public function excluir() {

    $oProjetoAtividade = $this->getProjetoAtividade();
    
    if (!empty($oProjetoAtividade)) {
      $oProjetoAtividade->removerIniciativa($this);
    }
    
    $oDaoOrcIniciativa                  = db_utils::getDao("orciniciativa");
    $oDaoOrcIniciativa->o147_sequencial = $this->iCodigoSequencial;
    $oDaoOrcIniciativa->excluir($this->iCodigoSequencial);

    if ($oDaoOrcIniciativa->erro_status == "0") {

      $sMensagem = "Erro ao excluir iniciativa {$this->iCodigoSequencial}";
      throw new DBException($sMensagem);
    }
  }

  /**
   * Salva os dados da iniciativa, caso o sequêncial seja nulo
   * Do contrário, altera a iniciativa
   */
  public function salvar() {

    $oDAOOrciniciativa                  = db_utils::getDao("orciniciativa");
    $oDAOOrciniciativa->o147_descricao  = $this->sDescricao;
    $oDAOOrciniciativa->o147_iniciativa = $this->sIniciativa;
    $oDAOOrciniciativa->o147_orcmeta    = $this->iCodigoMeta;
    $oDAOOrciniciativa->o147_ano        = $this->iAnoInicial;
    $oDAOOrciniciativa->o147_anofinal   = $this->iAnoFinal;

    if (empty($this->iCodigoSequencial)) {
      $oDAOOrciniciativa->incluir(null);
    } else {

      $oDAOOrciniciativa->o147_sequencial = $this->iCodigoSequencial;
      $oDAOOrciniciativa->alterar($this->iCodigoSequencial);
    }

    if ($oDAOOrciniciativa->erro_status == 0) {
      $sMensagemErro  = "Erro Técnico: erro ao salvar dados da Iniciativa.";
      $sMensagemErro .= $oDAOOrciniciativa->erro_msg;
      throw new DBException($sMensagemErro);
    }

    $this->iCodigoSequencial = $oDAOOrciniciativa->o147_sequencial;
  }

  /**
   * Retorna a Meta a qual a iniciativa pertence
   * @return ProgramaMeta
   */
  public function getMeta() {

    if (!empty($this->iCodigoMeta)) {
      $this->oProgramaMeta = new ProgramaMeta($this->iCodigoMeta);
    }
    return $this->oProgramaMeta;
  }
  
  /**
   * Retorna uma instância de ProgramaProjetoAtividade á qual a Iniciativa pertence
   * @throws DBException
   * @return ProgramaProjetoAtividade
   */
  public function getProjetoAtividade() {
    
    if (empty($this->oProjetoAtividade)) {
      
      $oDaoVinculoProjetoAtividade = db_utils::getDao("orciniciativavinculoprojativ"); 
      $sWhere                      = "o149_iniciativa = {$this->getCodigoSequencial()}";
      $sSqlProjeto                 = $oDaoVinculoProjetoAtividade->sql_query_file(null, "*", null, $sWhere);
      $rsProjeto                   = db_query($sSqlProjeto);
      
      if (!$rsProjeto) {
        throw new DBException("Erro ao buscar dados do vinculo da Iniciativa com Projeto/Atividade");
      }
      
      if ($oDaoVinculoProjetoAtividade->numrows > 0) {
      
        $oProjetoAtividade = db_utils::fieldsMemory($rsProjeto, 0);
        $this->oProjetoAtividade = new ProgramaProjetoAtividade($oProjetoAtividade->o149_projativ, 
                                                                $oProjetoAtividade->o149_anousu);
      }
    }

    return $this->oProjetoAtividade;
  }
}