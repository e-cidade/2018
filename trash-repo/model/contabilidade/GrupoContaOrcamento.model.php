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
 * Model para controle do grupo de conta do plano or�ament�rio
 *
 * @author matheus felini
 * @package contabilidade
 * @version $Revision: 1.12 $
 */
class GrupoContaOrcamento {

  /**
   * Codigo do Grupo
   * @var integer
   */
  protected $iCodigo;

  /**
   * Descricao do Grupo
   * @var string
   */
  protected $sDescricao;

  /**
   * Tipo de Grupo
   * 1 - Interno
   * 2 - Externo
   * @var integer
   */
  protected $iTipoGrupo;

  /**
   * Armazena as contas do grupo
   * @var array
   */
  protected $aContas = array();

  /**
   * Constroi um objeto com os dados do grupo
   * @param integer $iCodigoGrupo
   * @return GrupoContaOrcamento
   */
  public function __construct($iCodigoGrupo = null) {

    $this->iCodigo = $iCodigoGrupo;
    if (!empty($iCodigoGrupo)) {

      $oDaoConGrupoOrcamento = db_utils::getDao('congrupo');
      $sSqlConGrupoOrcamento = $oDaoConGrupoOrcamento->sql_query_file(null,
                                                                      "*",
                                                                      null,
                                                                      "c20_sequencial = {$iCodigoGrupo}"
                                                                     );
      $rsConGrupoOrcamento   = $oDaoConGrupoOrcamento->sql_record($sSqlConGrupoOrcamento);
      if ($oDaoConGrupoOrcamento->numrows > 0) {

        $oConGrupoOrcamento = db_utils::fieldsMemory($rsConGrupoOrcamento, 0);

        $this->iCodigo    = $oConGrupoOrcamento->c20_sequencial;
        $this->sDescricao = $oConGrupoOrcamento->c20_descr;
        $this->iTipoGrupo = $oConGrupoOrcamento->c20_tipo;
      }
    }
    return true;
  }

  /**
   * Salva os dados do grupo e suas contas
   */
  public function salvar() {

    $oDaoGrupoOrcamento                 = db_utils::getDao('congrupo');
    $oDaoGrupoOrcamento->c20_sequencial = $this->getCodigo();
    $oDaoGrupoOrcamento->c20_descr      = $this->getDescricao();
    $oDaoGrupoOrcamento->c20_tipo       = $this->getTipoGrupo();

    if ($this->getCodigo() == "") {

      $oDaoGrupoOrcamento->incluir(null);
      $this->setCodigo($oDaoGrupoOrcamento->c20_sequencial);
    } else {
      $oDaoGrupoOrcamento->alterar($this->getCodigo());
    }
    return true;
  }

  /**
   * M�todo que exclui um grupo e as contas v�nculadas ao grupo do objeto atual
   * @throws Exception
   * @return boolean true
   */
  public function excluir() {

    $aContasVinculadas = $this->getContas();
    if (count($aContasVinculadas) > 0) {
      /* Percorremos o array de contas vinculadas ao grupo e excluimos as mesmas */
      foreach ($aContasVinculadas as $iIndice => $oContaOrcamento) {
        $this->excluirConta($oContaOrcamento->getCodigoConta());
      }
    }

    $oDaoConGrupoOrcamento = db_utils::getDao('congrupo');
    $oDaoConGrupoOrcamento->excluir($this->getCodigo());
    if ($oDaoConGrupoOrcamento->erro_status == "0") {
      throw new Exception("N�o foi poss�vel excluir o grupo {$this->getDescricao()}.");
    }
    return true;
  }

  /**
   * Vincula uma conta ao grupo de conta
   *
   * Sempre chamamos o metodo excluir para garantir que nao exista vinculo entre o
   * grupo e a conta que esta sendo vinculada
   * @param  integer $iCodigoConta
   * @throws BusinessException
   */
  public function vincularConta($iCodigoConta) {

    $this->excluirConta($iCodigoConta);

    $oDaoContaGrupo                 = db_utils::getDao('conplanoorcamentogrupo');
    $oDaoContaGrupo->c21_sequencial = null;
    $oDaoContaGrupo->c21_anousu     = db_getsession('DB_anousu');
    $oDaoContaGrupo->c21_codcon     = $iCodigoConta;
    $oDaoContaGrupo->c21_congrupo   = $this->getCodigo();
    $oDaoContaGrupo->c21_instit     = db_getsession('DB_instit');
    $oDaoContaGrupo->incluir(null);
    if ($oDaoContaGrupo->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel vincular a conta {$iCodigoConta} ao grupo {$this->getDescricao()}");
    }
    return true;
  }

  /**
   * Exclui a conta de um grupo de conta
   * @param  integer $iCodigoConta
   * @throws BusinessException
   * @return boolean true
   */
  public function excluirConta($iCodigoConta) {

    $iAnoSessao     = db_getsession("DB_anousu");
    $oDaoContaGrupo = db_utils::getDao('conplanoorcamentogrupo');
    $iInstituicao   = db_getsession("DB_instit");
    $sWhereExcluir  = "c21_anousu = {$iAnoSessao} and c21_codcon = {$iCodigoConta} and c21_instit = {$iInstituicao}";
    $oDaoContaGrupo->excluir(null, $sWhereExcluir);
    if ($oDaoContaGrupo->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel excluir a conta {$iCodigoConta} do grupo {$this->getDescricao()}");
    }
    return true;
  }

  /**
   * Armazena em um array todas as contas do or�amento ligadas ao grupo do objeto
   * @return array
   */
  public function getContas() {

    if (count($this->aContas) == 0) {

      $iAnoSessao        = db_getsession("DB_anousu");
      $iInstituicao      = db_getsession("DB_instit");
      $oDaoContaGrupo    = db_utils::getDao('conplanoorcamentogrupo');
      $sCamposBuscaConta = "c21_codcon, c21_anousu";
      $sWhereBuscaConta  = "c21_congrupo = {$this->getCodigo()} and c21_anousu = {$iAnoSessao} and c21_instit = {$iInstituicao}";
      $sSqlBuscaContas   = $oDaoContaGrupo->sql_query(null, $sCamposBuscaConta, "c60_estrut", $sWhereBuscaConta);
      $rsBuscaContas     = $oDaoContaGrupo->sql_record($sSqlBuscaContas);
      $iTotalContas      = $oDaoContaGrupo->numrows;
      if ($iTotalContas > 0) {

        for ($iRowConta = 0; $iRowConta < $iTotalContas; $iRowConta++) {

          $oDadoConta      = db_utils::fieldsMemory($rsBuscaContas, $iRowConta);
          $oContaOrcamento = ContaOrcamentoRepository::getContaByCodigo($oDadoConta->c21_codcon,
                                                                        $oDadoConta->c21_anousu
                                                                       );
          $this->adicionarConta($oContaOrcamento);
        }
      }
    }
    return $this->aContas;
  }


  /**
   * Retorna o codigo do grupo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo sequencial do grupo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a descricao do grupo
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao do grupo
   * @param string $sDescricaoGrupo
   */
  public function setDescricao($sDescricaoGrupo) {
    $this->sDescricao = $sDescricaoGrupo;
  }

  /**
   * Retorna o tipo do grupo
   * @return integer
   */
  public function getTipoGrupo() {
    return $this->iTipoGrupo;
  }

  /**
   * Seta o tipo do grupo
   * 1 - interno / 2 - externo
   * @param integer $iTipoGrupo
   */
  public function setTipoGrupo($iTipoGrupo) {
    $this->iTipoGrupo = $iTipoGrupo;
  }

  /**
   * Adiciona ao array uma conta do orcamento (conplanorocamento)
   * @param ContaOrcamento $oContaOrcamento
   */
  public function adicionarConta(ContaOrcamento $oContaOrcamento) {
    $this->aContas[] = $oContaOrcamento;
  }


  /**
   * Busca atrav�s da conta o Grupo da Conta Orcamentaria
   * @param integer $iConta
   * @throws BusinessException
   * @return GrupoContaOrcamento
   *
   * @todo refatorar rotinas que utilizam este m�todo para passar a institui��o por par�metro
   *
   */
  static function getGrupoConta($iConta, $iAnoUsu, $iInstituicao = null) {

    if (empty($iInstituicao)) {
      $iInstituicao = db_getsession("DB_instit");
    }

    $oDaoGrupoOrcamento = db_utils::getDao("conplanoorcamentogrupo");
    $sWhere             = " conplanoorcamentogrupo.c21_codcon = {$iConta} and ";
    $sWhere            .= " conplanoorcamentogrupo.c21_anousu = {$iAnoUsu} and ";
    $sWhere            .= " conplanoorcamentogrupo.c21_instit = {$iInstituicao} and ";
    $sWhere            .= " conplanoorcamentogrupo.c21_congrupo != 0";
    $sSqlGrupoOrcamento = $oDaoGrupoOrcamento->sql_query_file(null, "c21_congrupo", null, $sWhere);
    $rsGrupoOrcamento   = $oDaoGrupoOrcamento->sql_record($sSqlGrupoOrcamento);

    if ($oDaoGrupoOrcamento->numrows == 1) {
      return new GrupoContaOrcamento(db_utils::fieldsMemory($rsGrupoOrcamento, 0)->c21_congrupo);
    }
    return false;
  }

}