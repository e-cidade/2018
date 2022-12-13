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
class ConfiguracaoConsignado {
  
  private $iCodigo;

  /**
   * @var Banco
   */
  private $oBanco;

  /**
   * @var DBLayoutTXT
   */
  private $oLayout;


  /**
   * @var Rubrica
   */
  private $oRubrica;

  /**
   * ConfiguracaoConsignado constructor.
   */
  public function __construct($iCodigo = null) {


    if (empty($iCodigo)) {
       return;
    }
    $oDaoConfiguracaoConsignado = new cl_rhconsignacaobancolayout();
    $oDadosConfiguracao         = db_utils::getRowFromDao($oDaoConfiguracaoConsignado, array($iCodigo));
    if (empty($oDadosConfiguracao)) {
      return;
    }
    $this->setCodigo($iCodigo);
    $this->setBanco(new Banco($oDadosConfiguracao->rh178_db_banco));
    $this->setRubrica(RubricaRepository::getInstanciaByCodigo($oDadosConfiguracao->rh178_rubrica));
    $this->setLayout(new DBLayoutTXT($oDadosConfiguracao->rh178_layout));
  }

  /**
   * @return mixed
  */
  public function getCodigo() {

    return $this->iCodigo;
  }

 /**
  * @param mixed $iCodigo
  */
  public function setCodigo($iCodigo) {

    $this->iCodigo =  $iCodigo;
  }

  /**
   * @return Banco
   */
  public function getBanco() {

    return $this->oBanco;
  }

  /**
   * @param Banco $oBanco
   */
  public function setBanco(Banco $oBanco) {

    $this->oBanco = $oBanco;
  }

  /**
   * @return DBLayoutTXT
   */
  public function getLayout() {

    return $this->oLayout;
  }
  /**
   * @param DBLayoutTXT $oLayout
   */
  public function setLayout(DBLayoutTXT $oLayout) {

    $this->oLayout = $oLayout;
  }

  /**
   * @return Rubrica
   */
  public function getRubrica() {

    return $this->oRubrica;
  }

  /**
   * @param Rubrica $oRubrica
   */
  public function setRubrica(Rubrica $oRubrica) {

    $this->oRubrica = $oRubrica;
  }

  /**
   * Persiste dos dados da configuração
   * @throws \BusinessException
   */
  public function salvar() {

    $oDaoConfiguracaoConsignado = new cl_rhconsignacaobancolayout();

    /**
     * Verificamos se já não existe configurações salvas 
     * para o Banco informado, quando for inserção.
     */
    $sSqlConsignadoBancoLayout = $oDaoConfiguracaoConsignado->sql_query_file(null, 'rh178_db_banco', null, "rh178_db_banco = '{$this->getBanco()->getCodigo()}' and rh178_instit = {$this->getRubrica()->getInstituicao()}");
    $rsConsignadoBancoLayout   = db_query($sSqlConsignadoBancoLayout);

    if (!$rsConsignadoBancoLayout) {
      throw new DBException("Ocorreu um erro ao verificar as configurações salvas.");
    }

    if (pg_num_rows($rsConsignadoBancoLayout) > 0 && !$this->getCodigo()) {
      throw new BusinessException("Banco já possuí configuração cadastrada.");
    }

    $oDaoConfiguracaoConsignado->rh178_db_banco   = $this->getBanco()->getCodigo();
    $oDaoConfiguracaoConsignado->rh178_instit     = $this->getRubrica()->getInstituicao();
    $oDaoConfiguracaoConsignado->rh178_layout     = $this->getLayout()->getCodigo();
    $oDaoConfiguracaoConsignado->rh178_rubrica    = $this->getRubrica()->getCodigo();
    $oDaoConfiguracaoConsignado->rh178_sequencial = $this->getCodigo();

    if (empty($this->iCodigo)) {

      $oDaoConfiguracaoConsignado->incluir(null);
      $this->iCodigo = $oDaoConfiguracaoConsignado->rh178_sequencial;
    } else {
      $oDaoConfiguracaoConsignado->alterar($this->iCodigo);
    }

    if ($oDaoConfiguracaoConsignado->erro_status == 0) {
      throw new BusinessException("Erro ao persistir dos dados da configuração da consignação");
    }
  }

  public function remover() {

    if (empty($this->iCodigo)) {
      return false;
    }

    $oDaoConfiguracaoConsignado = new cl_rhconsignacaobancolayout();
    $oDaoConfiguracaoConsignado->excluir($this->getCodigo());

    if ($oDaoConfiguracaoConsignado->erro_status == 0) {
      throw new BusinessException("Erro ao remover dos dados da configuração da Rubrica");
    }
  }
}