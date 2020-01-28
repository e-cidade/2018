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


require_once ('model/configuracao/DBEstruturaValor.model.php');

/**
 * Model para controle do grupo do material do estoque
 * Class MaterialGrupo
 */
class MaterialGrupo extends DBEstruturaValor  {

  /**
   * Código sequencial do grupo do material
   * @var integer
   */
  protected $iCodigoGrupo;

  /**
   * Código sequencial da conta contábil. Normalmente uma conta de ATIVO na contabilidade
   * @var integer
   */
  protected $iConta;

  /**
   * Ano da conta contábil / VPD
   * @var integer
   */
  protected $iAnoUsu;

  /**
   * Variável de controle para sabermos se o grupo está ativo
   * @var boolean
   */
  protected $lAtivo;

  /**
   * Descrição do Tipo de Estrutural
   * @var string
   */
  protected $sTipoEstrutural = 'Grupo/Subgrupo';

  /**
   * Descrição da conta contábil
   * @var string
   */
  protected $sDescricaoConta;

  /**
   * Código da conta referente a Variação Patrimonial Diminutiva
   * @var integer
   */
  protected $iCodigoContaVPD;

  /**
   * Conta do Plano de Contas referente ao VPD
   * @var ContaPlanoPCASP
   */
  protected $oPlanoContaVPD;

  /**
   * Conta do plano de contas referente à conta do grupo ATIVO
   * @var ContaPlanoPCASP
   */
  protected $oPlanoContaAtivo;

  /**
   * Código sequencial do grupo do material
   * @param $iCodigoGrupo
   */
  public function __construct($iCodigoGrupo = null) {

    if (!empty($iCodigoGrupo)) {

      $oDaoGrupoMaterial = db_utils::getDao("materialestoquegrupo");
      
      $iAnoUsu        = db_getsession('DB_anousu');
      $sSqlDadosGrupo = $oDaoGrupoMaterial->sql_query_conta_ano($iCodigoGrupo, $iAnoUsu, "*");
      
      $rsDadosGrupo = $oDaoGrupoMaterial->sql_record($sSqlDadosGrupo);
      if ($oDaoGrupoMaterial->numrows > 0) {

        $oDadosGrupo = db_utils::fieldsMemory($rsDadosGrupo, 0);
        $this->iCodigoGrupo    = $iCodigoGrupo;
        $this->lAtivo          = $oDadosGrupo->m65_ativo=='t'?true:false;
        $this->iConta          = $oDadosGrupo->m66_codcon;
        $this->sDescricaoConta = $oDadosGrupo->c60_descr;
        $this->iCodigoContaVPD = $oDadosGrupo->m66_codconvpd;
        $this->iAnoUsu         = $oDadosGrupo->m66_anousu;
        parent::__construct($oDadosGrupo->m65_db_estruturavalor);
        unset($oDadosGrupo);
      }
    }
    $this->tipo = __CLASS__;
  }

  /**
   * persiste os dados do grupo
   *
   * @return MaterialGrupo
   */
  public function salvar() {

    parent::salvar();
    $oDaoGrupoMaterial                        = db_utils::getDao("materialestoquegrupo");
    $oDaoGrupoMaterial->m65_ativo             = $this->lAtivo?"true":"false";
    $oDaoGrupoMaterial->m65_db_estruturavalor = $this->iCodigo;
    if (empty($this->iCodigoGrupo)) {

      $oDaoGrupoMaterial->incluir(null);
      $this->iCodigoGrupo = $oDaoGrupoMaterial->m65_sequencial;
    } else {

      $oDaoGrupoMaterial->m65_sequencial = $this->getCodigo();
      $oDaoGrupoMaterial->alterar($this->getCodigo());
    }
    if ($oDaoGrupoMaterial->erro_status == 0) {
      throw new Exception($oDaoGrupoMaterial->erro_msg);
    }

    /**
     * realiza o controle da conta.
     */
    $sWhere                 = "m66_materialestoquegrupo  = {$this->getCodigo()}";
    $oDaoGrupoMaterialConta = db_utils::getDao("materialestoquegrupoconta");
    $oDaoGrupoMaterialConta->excluir(null, $sWhere);
    if (!empty($this->iConta)) {

      $sSqlDadosConta = $oDaoGrupoMaterialConta->sql_query_file(null,"*", null, $sWhere);
      $rsDadosConta   = $oDaoGrupoMaterialConta->sql_record($sSqlDadosConta);
      $oDaoGrupoMaterialConta->m66_anousu               = db_getsession("DB_anousu");
      $oDaoGrupoMaterialConta->m66_codcon               = $this->getConta();
      $oDaoGrupoMaterialConta->m66_materialestoquegrupo = $this->getCodigo();
      $oDaoGrupoMaterialConta->m66_codconvpd            = $this->iCodigoContaVPD;

      if ($oDaoGrupoMaterialConta->numrows == 0) {
        $oDaoGrupoMaterialConta->incluir(null);
      } else {

        $iCodigoConta = db_utils::fieldsMemory($rsDadosConta, 0)->m66_sequencial;
        $oDaoGrupoMaterialConta->m66_sequencial = $iCodigoConta;
        $oDaoGrupoMaterialConta->alterar($iCodigoConta);
      }
    }

    if ($oDaoGrupoMaterialConta->erro_status == 0) {
      throw new Exception($oDaoGrupoMaterialConta->erro_msg);
    }
    return $this;
  }

  /**
   * define se o grupo está ativo
   *
   * @param  boolean $lAtivo ativo/inativo
   * @return MaterialGrupo
   */
  public function setAtivo($lAtivo) {

    $this->lAtivo = $lAtivo;
    return $this;
  }

  /**
   * verifica se ogrupo está ativo
   *
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Retorna o código do grupo de acordo com o código informado via parâmetro
   * @param integer $iCodigoEstrutura
   * @return integer
   */
  static public function getCodigoByEstrutura($iCodigoEstrutura) {

    $iCodigoGrupo       = null;
    $oDaoGrupoMaterial  = db_utils::getDao("materialestoquegrupo");
    $sSqlCodigo         = $oDaoGrupoMaterial->sql_query_file(null,
                                                            'm65_sequencial',
                                                             null,
                                                             "m65_db_estruturavalor={$iCodigoEstrutura}"
                                                             );

    $rsCodigo  = $oDaoGrupoMaterial->sql_record($sSqlCodigo);
    if ($oDaoGrupoMaterial->numrows > 0) {
      $iCodigoGrupo = db_utils::fieldsMemory($rsCodigo, 0)->m65_sequencial;
    }
    return $iCodigoGrupo;
  }

  /**
   * Define a conta contabil
   * @param $iConta
   * @return MaterialGrupo
   */
  public function setConta($iConta) {

    $this->iConta = $iConta;
    return $this;
  }

  /**
   * retorna a conta contábil do grupo
   *@return integer
   */
  public function getConta() {
    return $this->iConta;
  }

  /**
   * Retorna o codigo do Grupo
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoGrupo;
  }
/**
   * Retorna o codigo do Grupo
   *
   * @return integer
   */
  public function getCodigoEstrutural() {
    return $this->iCodigo;
  }

  /**
   * Retorna a Descrição da Conta Contábil
   * @return string
   */
  public function getDescricaoConta() {
    return $this->sDescricaoConta;
  }

  /**
   * Retorna o código da conta VPD (Variação Patrimonial Diminutiva)
   * @return integer
   */
  public function getCodigoContaVPD() {
  	return $this->iCodigoContaVPD;
  }

  /**
   * Retorna o código da conta VPD (Variação Patrimonial Diminutiva)
   * @return integer
   */
  public function setCodigoContaVPD($iCodigoContaVPD) {
    $this->iCodigoContaVPD = $iCodigoContaVPD;
    return $this;
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP
   * @return ContaPlanoPCASP
   */
  public function getContaVPD() {

    if (!empty($this->iCodigoContaVPD)) {
      $this->oPlanoContaVPD = new ContaPlanoPCASP($this->iCodigoContaVPD, $this->iAnoUsu);
    }
    return $this->oPlanoContaVPD;
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP para a Conta Contábil
   * @return ContaPlanoPCASP
   */
  public function getContaAtivo() {

    if (!empty($this->iConta)) {
      $this->oPlanoContaAtivo = new ContaPlanoPCASP($this->iConta, $this->iAnoUsu);
    }
    return $this->oPlanoContaAtivo;
  }
}