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
 * Class OrcamentoLicitacao
 * Classe responsável por consultar os valores referentes aà uma licitação.
 */
class OrcamentoLicitacao {

  /**
   * @var licitacao
   */
  private $oLicitacao;

  /**
   * @var int
   */
  private $iCodigoItem;

  /**
   * @var string
   */
  private $sDescricaoLote;

  /**
   * @var integer
   */
  private $iCodigoAcordo;

  /**
   * OrcamentoLicitacao constructor.
   *
   * @param licitacao $oLicitacao Licitação para os quais os valores serão buscados.
   */
  public function __construct(licitacao $oLicitacao) {
    $this->oLicitacao = $oLicitacao;
  }

  /**
   * @param int $iCodigoItem
   */
  public function setCodigoItem($iCodigoItem) {
    $this->iCodigoItem = $iCodigoItem;
  }

  /**
   * @param string $sDescricaoLote
   */
  public function setDescricaoLote($sDescricaoLote) {
    $this->sDescricaoLote = $sDescricaoLote;
  }

  public function setCodigoAcordo($iCodigoAcordo) {
    $this->iCodigoAcordo = $iCodigoAcordo;
  }

  /**
   * Executa, valida e retorna o resultado da consulta.
   * @param $sSqlBuscaValor
   *
   * @return float
   * @throws DBException
   */
  private function executaConsulta($sSqlBuscaValor) {

    $rsBuscaValor = db_query($sSqlBuscaValor);

    if ($rsBuscaValor === false) {
      throw new DBException("Houve um erro ao buscar os valores para a licitação.");
    }


    if (pg_num_rows($rsBuscaValor) == 0) {
      return 0.0;
    }
    return (float) db_utils::fieldsMemory($rsBuscaValor, 0)->total;
  }

  /**
   * Monta o where de acordo com os atributos.
   * @return string
   */
  private function montaWhere() {

    $aWhereLicitacao   = array();
    $aWhereLicitacao[] = " l21_codliclicita = {$this->oLicitacao->getCodigo()} ";

    if ($this->iCodigoItem != null) {
      $aWhereLicitacao[] = " l21_codigo = {$this->iCodigoItem} ";
    }

    if ($this->sDescricaoLote != null) {
      $aWhereLicitacao[] = " l04_descricao = '{$this->sDescricaoLote}' ";
    }

    if ($this->iCodigoAcordo != null) {
      $aWhereLicitacao[] = " ac16_sequencial = {$this->iCodigoAcordo} ";
    }

    return implode(" and ", $aWhereLicitacao);
  }

  /**
   * Monta where para busca do valor estimado.
   * @return string
   */
  private function montaWhereEstimado() {

    $sWhere = $this->montaWhere();
    return " {$sWhere} and (pcorcamitemproc.pc31_orcamitem is null or pcorcamjulg.pc24_pontuacao = 1) ";
  }

  /**
   * Monta where para busca do valor homologado.
   * @return string
   */
  private function montaWhereHomologacao() {

    $sWhere = $this->montaWhere();
    return " {$sWhere} and pc24_pontuacao = 1 ";
  }

  /**
   * Busca o valor total estimado.
   * @return float
   * @throws DBException
   */
  public function getValorTotalEstimado() {

    $oDaoLicLicitem  = new cl_liclicitem();

    /**
     * Busca o valor estimado por item com a seguinte prescedência:
     * - Orçamento de Processo de Compras (Quando houver), se estiver julgado irá trazer o valor do vencedor, caso contrário irá trazer o valor zerado
     * - Solicitação de Compras
     */
    $sCampos        = " sum(case when pcorcamjulg.pc24_orcamitem is null then pc11_quant * solicitem.pc11_vlrun else pc23_valor end) as total";
    $sWhere         = $this->montaWhereEstimado();
    $sSqlBuscaValor = $oDaoLicLicitem->sql_query_valor_estimado($sCampos, $sWhere);

    return $this->executaConsulta($sSqlBuscaValor);
  }

  public function getValorUnitarioEstimado() {

    if (empty($this->iCodigoItem)) {
      throw new ParameterException("Código do Item da Licitação não informado.");
    }

    $oDaoLicLicitem  = new cl_liclicitem();

    /**
     * Busca o valor estimado por item com a seguinte prescedência:
     * - Orçamento de Processo de Compras (Quando houver), se estiver julgado irá trazer o valor do vencedor, caso contrário irá trazer o valor zerado
     * - Solicitação de Compras
     */
    $sCampos        = " sum(case when pcorcamjulg.pc24_orcamitem is null then solicitem.pc11_vlrun else pc23_vlrun end) as total";
    $sWhere         = $this->montaWhereEstimado();
    $sSqlBuscaValor = $oDaoLicLicitem->sql_query_valor_estimado($sCampos, $sWhere);

    return $this->executaConsulta($sSqlBuscaValor);
  }

  /**
   * Busca o valor homologado.
   * @return float
   * @throws DBException
   */
  public function getValorTotalHomologado() {

    $oDaoPcOrcamJulg = new cl_pcorcamjulg();
    $sCampos         = " distinct pcorcamval.* ";
    $sWhere          = $this->montaWhereHomologacao();
    $sSqlBuscaValor  = $oDaoPcOrcamJulg->sql_query_orcamento_licitacao($sCampos, $sWhere);
    if ($this->iCodigoAcordo !== null) {
      $sSqlBuscaValor = $oDaoPcOrcamJulg->sql_query_orcamento_licitacao_contrato($sCampos, $sWhere);
    }

    $sSqlBuscaValor = "select sum(pc23_valor) as total from ({$sSqlBuscaValor}) as x;";
    return $this->executaConsulta($sSqlBuscaValor);
  }

  public function getValorUnitarioHomologado() {

    if (empty($this->iCodigoItem)) {
      throw new ParameterException("Código do Item da Licitação nãoi informado.");
    }

    $oDaoPcOrcamJulg = new cl_pcorcamjulg();
    $sCampos         = " sum(pc23_vlrun) as total ";
    $sWhere          = $this->montaWhereHomologacao();
    $sSqlBuscaValor  = $oDaoPcOrcamJulg->sql_query_orcamento_licitacao($sCampos, $sWhere);

    return $this->executaConsulta($sSqlBuscaValor);
  }

  /**
   * Busca o valor de acordo com a situação da licitação.
   * @return float
   * @throws DBException
   */
  public function getValorTotal() {

    if ($this->oLicitacao->getSituacao()->isJulgada()) {
      return $this->getValorTotalHomologado();
    }
    return $this->getValorTotalEstimado();
  }

  /**
   * Busca o valor unitário acordo com a situação da licitação.
   * @return float
   * @throws DBException
   */
  public function getValorUnitario() {

    if ($this->oLicitacao->getSituacao()->isJulgada()) {
      return $this->getValorUnitarioHomologado();
    }

    return $this->getValorUnitarioEstimado();
  }

  /**
   * Busca o fornecedor vencedor.
   * @return stdClass
   * @throws BusinessException
   * @throws DBException
   */
  public function getFornecedorVencedor() {

    $oStdFornecedor = new stdClass();
    $oStdFornecedor->tipoPessoa      = null;
    $oStdFornecedor->documento       = null;
    $oStdFornecedor->bdi             = null;
    $oStdFornecedor->encargosSociais = null;

    $oDaoPcOrcamJulg = new cl_pcorcamjulg();

    $sCamposVencedor = " z01_cgccpf, z01_numcgm, sum(pc23_bdi) as pc23_bdi, sum(pc23_encargossociais) as pc23_encargossociais";
    $sWhereVencedor  = $this->montaWhereHomologacao();

    $sSqlVencedor = $oDaoPcOrcamJulg->sql_query_orcamento_licitacao($sCamposVencedor, $sWhereVencedor);
    $rsVencedor   = db_query("{$sSqlVencedor} group by z01_cgccpf, z01_numcgm");

    if (!$rsVencedor) {
      throw new DBException("Houve um erro ao buscar o vencedor da licitação.");
    }

    if (pg_num_rows($rsVencedor) == 0) {
      return array($oStdFornecedor);
    }

    $iLicitacao = $this->oLicitacao->getCodigo();
    if (pg_num_rows($rsVencedor) > 1 && !empty($this->iCodigoItem)) {

      $sMensagem  = "Foi encontrado mais de um fornecedor vencedor para o item {$this->iCodigoItem} da licitação de código {$iLicitacao}.";
      throw new BusinessException($sMensagem);
    }

    /**
     * Quando é chamamento público / credenciamento pode haver mais de um fornecedor
     */
    if ( !$this->oLicitacao->isChamamentoPublicoComCredenciamento() && pg_num_rows($rsVencedor) > 1 && !empty($this->sDescricaoLote)) {

      $sMensagem  = "Foi encontrado mais de um fornecedor vencedor para o lote {$this->sDescricaoLote} da licitação de código {$iLicitacao}.";
      throw new BusinessException($sMensagem);
    }

    $iLinhas       = pg_num_rows($rsVencedor);
    $aFornecedores = array();
    for($i = 0; $i < $iLinhas; $i++) {

      $oStdVencedor                    = db_utils::fieldsMemory($rsVencedor, $i);
      $oStdFornecedor                  = new stdClass();
      $oStdFornecedor->tipoPessoa      = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdVencedor->z01_numcgm);
      $oStdFornecedor->documento       = LicitanteLicitaCon::getDocumentoPorCGM($oStdVencedor->z01_numcgm);
      $oStdFornecedor->bdi             = $oStdVencedor->pc23_bdi;
      $oStdFornecedor->encargosSociais = $oStdVencedor->pc23_encargossociais;

      $aFornecedores[] = $oStdFornecedor;
    }

    return $aFornecedores;
  }
}
