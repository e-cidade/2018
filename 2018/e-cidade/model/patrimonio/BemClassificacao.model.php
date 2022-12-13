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
 * Que manipula a classificação de um bem
 * @author iuri@dbseller.com.br
 * @package patrimonio
 * @version $Revision: 1.15 $
 */
class BemClassificacao {

  /**
   * Código da Classificacao
   * @var integer;
   */
  protected $iCodigoClassificacao;

  /**
   * Descrição da classificação
   * @var string
   */
  protected $sDescricao;

  /**
   * Tipo do bem
   * 1 - MÓVEIS
   * 2 - IMÓVEIS
   * 3 - SEMOVENTES
   * @var integer
   */
  protected $iTipoBem;

  /**
   * Descrição do tipo de classificação
   * @var string
   */
  protected $sDescricaoTipo;

  /**
   * Classificação
   * @var string
   */
  protected $sClassificacao;

  /**
   * Utilizada para validar se a classificação é analitica ou sintética
   * @var boolean
   */
  protected $lAnalitica;

  /**
   * Tipo de depreciação associada a classificação.
   * @var integer
   */
  protected $iTipoDepreciacao;

  /**
   * Observações da classifcação do bem
   * @var string
   */
  protected $sObservacao;


  /**
   * Vida útil que será replicada para os bens da classificação.
   * @var integer
   */
  protected $iVidaUtil;

  
  /**
   * Instituicão da Classificação
   * @var integer
   */
  protected $iInstituicao;

  
  /**
   * Vinculo com as Contas (tabela clabensconplano)
   * @var Integer
   */
  protected $iVinculoContas;
  
  /**
   * A que plano de contas a classificação está associada
   * @var integer
   */
  protected $iPlanoConta;
  
  /**
   * Conta do plano de contas
   * @var ContaPlanoPCASP
   */
  protected $oContaPCASP;
  
  /**
   * A que plano de contas a depreciação da classificação está associada
   * @var integer
   */
  protected $iContaDepreciacao;
  
  /**
   * Conta Depreciacao do plano de contas
   * @var ContaPlanoPCASP
   */
  protected $oContaDepreciacao;
  
  /**
   * Ano da Classificação (para vinculo com contas)
   * @var integer
   */
  protected $iAno;
  
  
  /**
   * Constroi objeto referente a classificação
   * trazendo junto as contas vinculadas para o ano passado como parâmetro
   * 
   * @param integer $iCodigoClassificacao
   * @param integer $iAno
   */

  public function __construct($iCodigoClassificacao = null, $iAno = null) {

    if(!isset($iAno)) {
        $iAno = db_getsession("DB_anousu");
    }
    $this->iAno = $iAno;

    if ($iCodigoClassificacao != null) {

      $sCampos  = "t64_codcla, t64_class, t64_bemtipos, t24_descricao, t64_descr, t64_analitica, ";
			$sCampos .= "t64_benstipodepreciacao, t64_vidautil, t64_instit, t86_sequencial, t86_conplano, ";
			$sCampos .= "t86_conplanodepreciacao ";

      $oDaoClaBens          = new cl_clabens();
      $sWhere               = " clabens.t64_codcla = {$iCodigoClassificacao}";
      $sWhere              .= " and clabens.t64_instit = " . db_getsession("DB_instit");
      $sSqlClassificacao    = $oDaoClaBens->sql_query_contas(null, $sCampos, null, $sWhere);
      $rsDadosClassificacao = $oDaoClaBens->sql_record($sSqlClassificacao);

      if ($oDaoClaBens->numrows > 0) {

        $oDadosClassificacao        = db_utils::fieldsMemory($rsDadosClassificacao, 0);
        $this->iCodigoClassificacao = $oDadosClassificacao->t64_codcla;
        $this->sClassificacao       = $oDadosClassificacao->t64_class;
        $this->iTipoBem             = $oDadosClassificacao->t64_bemtipos;
        $this->sDescricaoTipo       = $oDadosClassificacao->t24_descricao;
        $this->sDescricao           = $oDadosClassificacao->t64_descr;
        $this->lAnalitica           = $oDadosClassificacao->t64_analitica=='t'?true:false;
        $this->iTipoDepreciacao     = $oDadosClassificacao->t64_benstipodepreciacao;
        $this->iVidaUtil            = $oDadosClassificacao->t64_vidautil;
        $this->iInstituicao         = $oDadosClassificacao->t64_instit;
        
        /**
         * Dados retirados da tabela clabensconplano 
         */
        $this->iVinculoContas       = $oDadosClassificacao->t86_sequencial;
        $this->iPlanoConta          = $oDadosClassificacao->t86_conplano;
        $this->iContaDepreciacao    = $oDadosClassificacao->t86_conplanodepreciacao;
        
        unset($oDadosClassificacao);
      }
    }
  }
  /**
   * Codigo da classificacao
   * @return integer;
   */
  public function getCodigo() {
    return $this->iCodigoClassificacao;
  }
  
  /**
   * Retorna o código da Instituicao
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }
  
  /**
   * Seta o código da Instituicao
   * @return integer
   */
  public function setInstituicao($iInstituicao) {
    return $this->iInstituicao = $iInstituicao;
  }

  /**
   * Seta valor na propriedade iVidaUtil
   * @param float $iVidaUtil
   */
  public function setVidaUtil ($iVidaUtil) {
    $this->iVidaUtil = $iVidaUtil;
  }

  /**
  * Retorna a vida util
  * @return float;
  */
  public function getVidaUtil() {
    return $this->iVidaUtil;
  }

  /**
   * Seta valor na propriedade iCodigoClassificacao
   * @param integer $iCodigoClassificacao
   */
  public function setCodigo ($iCodigoClassificacao) {
    $this->iCodigoClassificacao = $iCodigoClassificacao;
  }

  /**
   * Seta valor na propriedade $iTipoBem
   * $param integer
   */
  public function setTipoBem($iTipoBem) {
    $this->iTipoBem = $iTipoBem;
  }

  /**
   * Retorna o Codigo do tipo do bem
   * @return integer
   */
  public function getTipoBem() {
    return $this->iTipoBem;
  }


  /**
   * Seta valor na propriedade lAnalitica
   * @param boolean $lAnalitica
   */
  public function setAnalitica ($lAnalitica) {
    $this->lAnalitica = $lAnalitica;
  }

  /**
   * retorna a classificacao do Bem
   * @return boolean
   */
  public function isAnalitica() {
    return $this->lAnalitica;
  }

  /**
  * Seta valor na propriedade sClassificacao
  * @param string $sClassificacao
  */
  public function setClassificacao ($sClassificacao) {
    $this->sClassificacao = $sClassificacao;
  }
  /**
   * estrutural da classificacao do bem
   * @return string
   */
  public function getClassificacao() {
    return $this->sClassificacao;
  }

  /**
   * Seta valor na propriedade sDescricao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  /**
   * @return string Descricao da classificacao
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Descrição do tipo do bem
   * @return string
   */
  public function getDescricaoTipoBem() {
    return $this->sDescricaoTipo;
  }

  /**
   * Seta valor na propriedade iTipoDepreciacao
   * @param integer $iTipoDepreciacao
   */
  public function setTipoDepreciacao($iTipoDepreciacao) {
    $this->iTipoDepreciacao = $iTipoDepreciacao;
  }

  /**
   * Retorna valor na propriedade iTipoDepreciacao
   * @return integer $iTipoDepreciacao
   */
  public function getTipoDepreciacao() {
    return $this->iTipoDepreciacao;
  }

  /**
  * Seta valor na propriedade sObservacao
  * @param string $sObservacao
  */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna valor na propriedade sObservacao
   * @return string $sObservacao
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Seta valor na propriedade iPlanoConta
   * @param integer $iPlanoConta
   */
  public function setPlanoConta($iPlanoConta) {
    $this->iPlanoConta = $iPlanoConta;
  }

  /**
   * Retorna valor na propriedade iPlanoConta
   * @return integer $iPlanoConta
   */
  public function getPlanoConta() {
    return $this->iPlanoConta;
  }
  
  

  /**
   * Seta valor da Conta de depreciacao
   * @param integer $iConta
   */
  public function setCodigoContaDepreciacao($iConta) {
    $this->iContaDepreciacao = $iConta;
  }
  
  /**
   * Retorna valor na propriedade iContaDepreciacao
   * @return integer $iContaDepreciacao
   */
  public function getCodigoContaDepreciacao() {
    return $this->iContaDepreciacao;
  }
  
  

  /**
   * Metodo que salva ou altera uma classificação de um bem.
   * @throws Exception
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transação com o banco de dados não encontrada.");
    }

    $oDaoClaBens                          = new cl_clabens();
    $oDaoClaBens->t64_codcla              = $this->getCodigo();
    $oDaoClaBens->t64_class               = str_replace(".", "", $this->getClassificacao());
    $oDaoClaBens->t64_descr               = $this->getDescricao();
    $oDaoClaBens->t64_obs                 = $this->getObservacao();
    $oDaoClaBens->t64_analitica           = $this->isAnalitica();
    $oDaoClaBens->t64_bemtipos            = $this->getTipoBem();
    $oDaoClaBens->t64_benstipodepreciacao = $this->getTipoDepreciacao();
    $oDaoClaBens->t64_vidautil            = $this->getVidaUtil();
    $oDaoClaBens->t64_instit              = db_getsession("DB_instit");

    if (empty($oDaoClaBens->t64_codcla)) {
      $oDaoClaBens->incluir(null);
    } else {
      $oDaoClaBens->alterar($oDaoClaBens->t64_codcla);
    }

    if ($oDaoClaBens->erro_status == 0) {

      $sMsgErro  = "Classificação não pode ser salva.\\n\\n";
      $sMsgErro .= "Erro Técnico 1: ".str_replace("\\n", "\n", $oDaoClaBens->erro_msg);
      throw new Exception($sMsgErro);
    }
    
    
    
    $this->iCodigoClassificacao                   = $oDaoClaBens->t64_codcla;
    
    $oDaoClaBensConplano                          = db_utils::getDao("clabensconplano");
    $oDaoClaBensConplano->t86_sequencial          = $this->iVinculoContas;
    $oDaoClaBensConplano->t86_clabens             = $this->iCodigoClassificacao;
    $oDaoClaBensConplano->t86_conplano            = $this->iPlanoConta;
    $oDaoClaBensConplano->t86_anousu              = $this->iAno;
    $oDaoClaBensConplano->t86_conplanodepreciacao = $this->iContaDepreciacao;
    $oDaoClaBensConplano->t86_anousudepreciacao   = $this->iAno;
    
    if ($oDaoClaBensConplano->t86_sequencial == null) {
      $oDaoClaBensConplano->incluir(null);
    } else {
      $oDaoClaBensConplano->alterar($oDaoClaBensConplano->t86_sequencial);
    }
    
    if ($oDaoClaBensConplano->erro_status == 0) {
    
      $sMsgErro  = "Classificação não pode ser salva.\\n\\n";
      $sMsgErro .= "Erro Técnico 2: ".str_replace("\\n", "\n", $oDaoClaBensConplano->erro_msg);
      throw new Exception($sMsgErro);
    }
    
    return true;
  }

  /**
   * Retorna a conta vinculada a classificacao
   * @return ContaPlanoPCASP
   */
  public function getContaContabil() {

    if (empty($this->oContaPCASP)) {
      $this->oContaPCASP = new ContaPlanoPCASP($this->iPlanoConta, $this->iAno, null);
    }
    return $this->oContaPCASP;
  }
  
  
  /**
   * Retorna a conta de devinculada a classificacao
   * @return ContaPlanoPCASP
   */
  public function getContaDepreciacao() {
  
    if (empty($this->oContaDepreciacao)) {
      $this->oContaDepreciacao = new ContaPlanoPCASP($this->iContaDepreciacao, $this->iAno, null);
    }
    return $this->oContaDepreciacao;
  }
  
}
?>