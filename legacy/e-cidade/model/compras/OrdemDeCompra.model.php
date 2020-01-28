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
 * model para controle da ordem de compra
 * @author Rafael Lopes
 * @version $Revision: 1.15 $
 * @package compras
 */
class OrdemDeCompra {

  const TIPO_NORMAL     = 1;
  const TIPO_AUTOMATICA = 2;

  /**
   * codigo da ordem de compra
   * @var integer
   */
  public  $iCodigoOrdem;

  /**
   * data de emissao da ordem
   * @var DBDate
   */
  private $oEmissao;

  /**
   * data de anulação da ordem
   * @var DBDate
   */
  private $oAnulacao;

  /**
   * departamento da ordem de compra
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * fornecedor da ordem de compra
   * @var CgmFisico|CgmJuridico
   */
  private $oFornecedor;

  /**
   * observação da ordem de compra
   * @var string
   */
  private $sObservacao;

  /**
   * tipo da compra
   * @var integer
   */
  private $iTipoCompra;

  /**
   * valor total da ordem
   * @var float
   */
  private $nTotalOrdem;

  /**
   * valor lancado
   * @var float
   */
  private $nValorLancado;

  /**
   * valor a lancar
   * @var float
   */
  private $nValorLancar;

  /**
   * @type float
   */
  private $nValorAnulado;

  /**
   * Armazena os itens da ordem de compra
   * @var ItemOrdemDeCompra[]
   */
  private $aItens = array();

  /**
   * Código do Departamento
   * @var integer
   */
  private $iCodigoDepartamento;

  /**
   * Código do Fornecedor
   * @var integer
   */
  private $iCodigoFornecedor;

  /**
   * @var EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * Constante com o caminho das mensagens utilizadas pelo model
   * @const URL_MENSAGEM
   */
  const URL_MENSAGEM = "patrimonial.compras.OrdemDeCompra.";


  /**
   * Carrega os dados da ordem de compra
   * @param integer $iCodigoOrdem
   */
  public function __construct( $iCodigoOrdem = null ) {

    $this->iCodigoOrdem = $iCodigoOrdem;
    if (!empty($this->iCodigoOrdem)) {

      $oDaoMatOrdem         = new cl_matordem();
      $oDaoMatestoqueitemoc = new cl_matestoqueitemoc();
      $oDaoItemAnulado      = new cl_matordemitemanu();
      $sSqlValorAnulado     = $oDaoItemAnulado->sql_query_file_anulado("sum(m36_vrlanu)", "m51_codordem = {$this->iCodigoOrdem}");
      $sSqlMatestoqueitemoc = $oDaoMatestoqueitemoc->sql_query(null,null,"sum(m71_valor) ", null,"m52_codordem = {$this->iCodigoOrdem} and m73_cancelado is false");

      $sCamposMatOrdem  = "m51_codordem   , ";
      $sCamposMatOrdem .= "m51_data       , ";
      $sCamposMatOrdem .= "m51_depto      , ";
      $sCamposMatOrdem .= "m51_numcgm     , ";
      $sCamposMatOrdem .= "m51_obs        , ";
      $sCamposMatOrdem .= "m51_tipo       , ";
      $sCamposMatOrdem .= "m51_valortotal , ";
      $sCamposMatOrdem .= "m53_data       , ";
      $sCamposMatOrdem .= "({$sSqlValorAnulado}) as valoranulado, ";
      $sCamposMatOrdem .= "($sSqlMatestoqueitemoc) as valorlancado ";

      $sSqlMatOrdem = $oDaoMatOrdem->sql_query_tot( null,$sCamposMatOrdem , null, "m51_codordem = {$iCodigoOrdem}");
      $rsMatOrdem   = $oDaoMatOrdem->sql_record($sSqlMatOrdem);

      if ($oDaoMatOrdem->erro_status == "0") {
        throw new BusinessException(_M(self::URL_MENSAGEM."ordem_de_compra_nao_encontrada"));
      }

      $oDadosMatOrdem = db_utils::fieldsMemory($rsMatOrdem, 0);
      $nLancar        = $oDadosMatOrdem->m51_valortotal - $oDadosMatOrdem->valorlancado;
      $this->iCodigoFornecedor = $oDadosMatOrdem->m51_numcgm;
      $this->iCodigoDepartamento = $oDadosMatOrdem->m51_depto;
      $this->setEmissao(new DBDate($oDadosMatOrdem->m51_data));
      $this->setObservacao($oDadosMatOrdem->m51_obs);
      $this->setTipoCompra($oDadosMatOrdem->m51_tipo);
      $this->setTotalOrdem($oDadosMatOrdem->m51_valortotal);
      $this->setValorLancado($oDadosMatOrdem->valorlancado);
      $this->setValorLancar($nLancar);
      $this->nValorAnulado = $oDadosMatOrdem->valoranulado;


      /*
       * verificamos se a ordem esta anulada, caso venha valor no campo m53_data
       * instanciamos e setamos o objeto anulacao
       */
      if ( $oDadosMatOrdem->m53_data != '' ) {
        $this->setAnulacao(new DBDate($oDadosMatOrdem->m53_data));
      }
    }
  }

  /**
   * retorna o codigo da ordem de compra
   * @return integer
   */
  public function getCodigoOrdem(){
    return $this->iCodigoOrdem;
  }

  /**
   * define o codigo da ordem de compra
   * @param integer $iCodigoOrdem
   */
  private function setCodigoOrdem($iCodigoOrdem){
    $this->iCodigoOrdem = $iCodigoOrdem;
  }

  /**
   * retorna a data de emissao
   * @return DBDate
   */
  public function getEmissao(){
    return $this->oEmissao;
  }

  /**
   * define a data de emissao
   * @param DBDate $oDaTaEmissao
   */
  public function setEmissao(DBDate $oDaTaEmissao){
    $this->oEmissao = $oDaTaEmissao;
  }

  /**
   * retorna a data de anulacao da ordem de compra
   * @return DBDate
   */
  public function getAnulacao(){
    return $this->oAnulacao;
  }

  /**
   * definimos a data de anulação da ordem de compra
   * @param DBDate $oDataAnulacao
   */
  public function setAnulacao(DBDate $oDataAnulacao){
    $this->oAnulacao  = $oDataAnulacao;
  }

  /**
   * retorna o departamento da ordem de compra
   * @return DBDepartamento
   */
  public function getDepartamento(){

    if (!empty($this->iCodigoDepartamento)) {
      $this->oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo($this->iCodigoDepartamento);
    }
    return $this->oDepartamento;
  }

  /**
   * definimos o departamento da ordem de compra
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento){
    $this->oDepartamento = $oDepartamento;
  }

  /**
   * retorna o fornecedor da ordem de compra
   * @return CgmFisico|CgmJuridico
   */
  public function getFornecedor(){

    if (!empty($this->iCodigoFornecedor) && empty($this->oFornecedor)) {
      $this->oFornecedor = CgmFactory::getInstanceByCgm($this->iCodigoFornecedor);
    }
    return $this->oFornecedor;
  }

  /**
   * Fornecedor
   * @param CgmJuridico|CgmFisico|CgmBase $oFornecedor
   */
  public function setFornecedor(CgmBase $oFornecedor){
    $this->oFornecedor = $oFornecedor;
  }

  /**
   * retorna a observacao de uma ordem de compra
   * @return string
   */
  public function getObservacao(){
    return $this->sObservacao;
  }

  /**
   * define a observao para ordem de compra
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao){
    $this->sObservacao = $sObservacao;
  }

  /**
   * retorna tipo da compra
   * 1 - normal
   * 2 - virtual
   * @return integer
   */
  public function getTipoCompra(){
    return $this->iTipoCompra;
  }

  /**
   * define tipo da compra
   * 1 - normal
   * 2 - virtual
   * @param integer $sTipoCompra
   */
  public function setTipoCompra($iTipoCompra){
    $this->iTipoCompra = $iTipoCompra;
  }

  /**
   * retorna valor lancado
   * @return float
   */
  public function getValorLancado(){
    return $this->nValorLancado;
  }

  /**
   * define valor lancado
   * @param float $nValorLancado
   */
  public function setValorLancado($nValorLancado){
    $this->nValorLancado = $nValorLancado;
  }

  /**
   * retorna total da ordem
   * @return float
   */
  public function getTotalOrdem(){
    return $this->nTotalOrdem;
  }

  /**
   * define total da ordem
   * @param float $nTotalOrdem
   */
  public function setTotalOrdem($nTotalOrdem){
    $this->nTotalOrdem = $nTotalOrdem;
  }

  /**
   * retorna valor a lancar
   * @return float
   */
  public function getValorLancar(){
    return $this->nValorLancar;
  }

  /**
   * define valor a lancar
   * @param float $nValorLancar
   */
  public function setValorLancar($nValorLancar){
    $this->nValorLancar = $nValorLancar;
  }

  /**
   * Retorna o valor anulado da Ordem de Compra
   * @return float
   */
  public function getValorAnulado() {
    return $this->nValorAnulado;
  }

  /**
   * Retorna array de todos itens de entrada
   * @return MovimentacaoItem[]
   */
  public function getEntradas(){

    $oDaoMatOrdemItem  = db_utils::getDao('matordemitem');
    $sWhereOrdemCompra = "m81_codtipo in (19, 12) and m52_codordem = {$this->iCodigoOrdem}";
    $sSqlMatOrdemItem  = $oDaoMatOrdemItem->sql_query_entradas( null, "*", null, $sWhereOrdemCompra);
    $rsMatOrdemItem    = $oDaoMatOrdemItem->sql_record($sSqlMatOrdemItem);

    $aItensEntrada     = array();
    if ($oDaoMatOrdemItem->numrows > 0) {

      $iTotalItens    = $oDaoMatOrdemItem->numrows;
      for ($iRowItem = 0; $iRowItem < $iTotalItens; $iRowItem++) {

        $oDadosMatOrdemItem  = db_utils::fieldsMemory($rsMatOrdemItem, $iRowItem);
        $oItem               = new Item($oDadosMatOrdemItem->m70_codmatmater);
        $oAlmoxarifado       = new Almoxarifado($oDadosMatOrdemItem->m80_coddepto);
        $oTipoMovimentacao   = TipoMovimentacaoEstoqueRepository::getTipoMovimentaoPorCodigo($oDadosMatOrdemItem->m81_codtipo);

        $oMovimentacaoItem   = new MovimentacaoItem($oItem);
        $oMovimentacaoItem->setQuantidade($oDadosMatOrdemItem->m52_quant);
        $oMovimentacaoItem->setQuantidadeEntrada($oDadosMatOrdemItem->m71_quant);
        $oMovimentacaoItem->setValor($oDadosMatOrdemItem->m71_valor);
        $oMovimentacaoItem->setAlmoxarifado($oAlmoxarifado);
        $oMovimentacaoItem->setTipoMovimentacao($oTipoMovimentacao);

        $aItensEntrada[]     = $oMovimentacaoItem;

      }
    }
    return $aItensEntrada;
  }

  /**
   * Retorna os itens da ordem de compra
   * @return ItemOrdemDeCompra[]
   * @throws BusinessException
   */
  public function getItens() {

    if (count($this->aItens) == 0) {

      $oDaoMatOrdemItem  = db_utils::getDao('matordemitem');
      $sSqlItens         = $oDaoMatOrdemItem->sql_query_ordcons(null, 'm52_codlanc', null, "m52_codordem = ".$this->getCodigoOrdem());
      $rsMatOrdemItem    = $oDaoMatOrdemItem->sql_record($sSqlItens);

      if ($oDaoMatOrdemItem->erro_status == "0") {
        throw new BusinessException(_M(self::URL_MENSAGEM."itens_nao_encontrados"));
      }

      for ($iRowItem = 0; $iRowItem < $oDaoMatOrdemItem->numrows; $iRowItem++){

        $iCodigoItemOrdemDeCompra = db_utils::fieldsMemory($rsMatOrdemItem, $iRowItem)->m52_codlanc;
        $this->aItens[]           = new ItemOrdemDeCompra($iCodigoItemOrdemDeCompra);
      }
    }
    return $this->aItens;
  }

  /**
   * @return EmpenhoFinanceiro
   */
  public function getEmpenhoFinanceiro() {

    $aItens = $this->getItens();
    $this->oEmpenhoFinanceiro = $aItens[0]->getItemEmpenho()->getEmpenhoFinanceiro();
    return $this->oEmpenhoFinanceiro;
  }
}