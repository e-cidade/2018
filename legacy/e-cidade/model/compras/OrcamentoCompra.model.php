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
 * Classe representa um orçamento do processo de compra 
 * @author $Author: dbiuri $
 * @version $Revision: 1.5 $
 */
class OrcamentoCompra {

  const ARQUIVO_MENSAGEM = 'patrimonial.compras.OrcamentoCompra.';
  
  /**
   * Estas constantes são os tipos de orçamento
   */
  const TIPO_ORCAMENTO_PROCESSO    = 1;
  const TIPO_ORCAMENTO_SOLICITACAO = 2;
  const TIPO_ORCAMENTO_LICITACAO   = 3;

  /**
   * Estas constantes são os tipos de processo de compras
   */
  const FORMA_JULGAMENTO_ITEM = 1;
  const FORMA_JULGAMENTO_LOTE = 2;

  /**
   * Sequencial do orçamento
   * 
   * @access private
   * @var Integer 
   */
  private $iCodigo;

  /**
   * Prazo limite para a entrega do orçamento 
   * 
   * @access private
   * @var DBDate 
   */
  private $oDataLimite;

  /**
   * Hora limite para a entrega do orçamento
   * 
   * @access private
   * @var String 
   */
  private $sHoraLimite;

  /**
   * Observação
   * 
   * @access private
   * @var String 
   */
  private $sObservacao;

  /**
   * Prazo de entrega dos itens
   * 
   * @access private
   * @var Integer
   */
  private $iPrazoEntrega;

  /**
   * Validade do orçamento
   * 
   * @access private
   * @var Integer
   */
  private $iValidadeOrcamento;

  /**
   * Cotação prévia
   * 
   * @access private
   * @var Boolean
   */
  private $lCotacaoPrevia;


  /**
   * Tipo do orcamento
   * @var integer
   */
  private $iTipoOrcamento;

  /**
   * Fornecedores participantes do orcamento
   * @var CgmJuridico[]|CgmFisico[]
   */
  private $aFornecedores = array();

  /**
   * Coleção de item no orçamento
   * @var ItemOrcamento[]
   */
  private $aItens = array();


  /**
   * Construtor da classe
   *
   * @param Integer $iCodigo
   * @throws BusinessException
   * @return \OrcamentoCompra
   */
  function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }

    $oDaoOrcamentoCompra   = new cl_pcorcam();

    $sCampos  = "*, case";
    $sCampos .= "   when exists(select 1 from pcorcamitem inner join pcorcamitemproc on pc31_orcamitem = pc22_orcamitem  where pc22_codorc = pc20_codorc) then 1";
    $sCampos .= "   when exists(select 1 from pcorcamitem inner join pcorcamitemsol  on pc29_orcamitem = pc22_orcamitem  where pc22_codorc = pc20_codorc) then 2";
    $sCampos .= "   when exists(select 1 from pcorcamitem inner join pcorcamitemlic  on pc26_orcamitem = pc22_orcamitem  where pc22_codorc = pc20_codorc) then 3";
    $sCampos .= "   end as tipo_orcamento ";

    $sQueryOrcamentoCompra = $oDaoOrcamentoCompra->sql_query_file($iCodigo, $sCampos);
    $rsOrcamentoCompra     = $oDaoOrcamentoCompra->sql_record($sQueryOrcamentoCompra);

    if (!$rsOrcamentoCompra || $oDaoOrcamentoCompra->numrows == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "orcamento_compra_nao_encontrado"));
    }

    $oDadosOrcamentoCompra = db_utils::fieldsMemory($rsOrcamentoCompra, 0);
    
    $this->setCodigo($oDadosOrcamentoCompra->pc20_codorc);
    $this->setDataLimite(new DBDate($oDadosOrcamentoCompra->pc20_dtate));
    $this->setHoraLimite($oDadosOrcamentoCompra->pc20_hrate);
    $this->setObservacao($oDadosOrcamentoCompra->pc20_obs);
    $this->setPrazoEntrega($oDadosOrcamentoCompra->pc20_prazoentrega);
    $this->setValidadeOrcamento($oDadosOrcamentoCompra->pc20_validadeorcamento);
    $this->setCotacaoPrevia($oDadosOrcamentoCompra->pc20_cotacaoprevia == 2 ? false : true);
    $this->setTipoOrcamento($oDadosOrcamentoCompra->tipo_orcamento);
    
  }

  /**
   * Retorna o sequencial do orçamento
   * 
   * @access public
   * @return Integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a data limite do orçamento
   * 
   * @access public
   * @return DBDate
   */
  public function getDataLimite() {
    return $this->oDataLimite;
  }

  /**
   * Verifica se o Orcamneto possui alguma Cotacao;
   * @return bool
   */
  public function temCotacao() {

    $oDaoOrcamval   = new cl_pcorcamval();
    $sWhereCotacoes = "pc22_codorc = {$this->getCodigo()}";
    $sSqlCotacoes   = $oDaoOrcamval->sql_query(null, null, "count(*)", null, $sWhereCotacoes);
    $oDaoOrcamval->sql_record($sSqlCotacoes);
    return $oDaoOrcamval->numrows > 0;
  }

  /**
   * Retorna a hora limite do orçamento
   * 
   * @access public
   * @return String
   */
  public function getHoraLimite() {
    return $this->sHoraLimite;
  }

  /**
   * Retorna a observação
   * 
   * @access public
   * @return String
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Retorna o prazo de entrega
   * 
   * @access public
   * @return Integer
   */
  public function getPrazoEntrega() {
    return $this->iPrazoEntrega;
  }

  /**
   * Retorna a validade do orçamento
   * 
   * @access public
   * @return Integer
   */
  public function getValidadeOrcamento() {
    return $this->iValidadeOrcamento;
  }

  /**
   * Retorna a cotação prévia
   * 
   * @access public
   * @return Boolean
   */
  public function getCotacaoPrevia() {
    return $this->lCotacaoPrevia;
  }

  /**
   * Seta o sequencial do orçamento
   * 
   * @access private
   * @param Integer $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Seta a data limite do orçamento
   * 
   * @access public
   * @param DBDate $oDataLimite
   */
  public function setDataLimite($oDataLimite) {
    $this->oDataLimite = $oDataLimite;
  }

  /**
   * Seta a hora limite do orçamento
   * 
   * @access public
   * @param string $sHoraLimite
   */
  public function setHoraLimite($sHoraLimite) {
    $this->sHoraLimite = $sHoraLimite;
  }

  /**
   * Seta a observação
   * 
   * @access public
   * @param String $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Seta o prazo de entrega
   * 
   * @access public
   * @param Integer $iPrazoEntrega
   */
  public function setPrazoEntrega($iPrazoEntrega) {
    $this->iPrazoEntrega = $iPrazoEntrega;
  }

  /**
   * Seta a validade do orçamento
   * 
   * @access public
   * @param Integer $iValidadeOrcamento
   */
  public function setValidadeOrcamento($iValidadeOrcamento) {
    $this->iValidadeOrcamento = $iValidadeOrcamento;
  }

  /**
   * Seta a cotação prévia
   * 
   * @access public
   * @param Boolean $lCotacaoPrevia
   */
  public function setCotacaoPrevia($lCotacaoPrevia) {
    $this->lCotacaoPrevia = $lCotacaoPrevia;
  }

  /**
   * Define o tipo do orçamento
   * @param integer $iTipoOrcamento
   */
  private function setTipoOrcamento($iTipoOrcamento) {
    $this->iTipoOrcamento = $iTipoOrcamento;
  }

  /**
   * Retorna o tipo do orcamento
   * @return integer
   */
  public function getTipoOrcamento() {
    return $this->iTipoOrcamento;
  }

  public function julgar(iJulgamentoOrcamento $oFormaJulgamento) {
    $oFormaJulgamento->julgar($this);
  }

  /**
   * Retorna todos os fornecedores participantes no orçamento
   * @return CgmFisico[]|CgmJuridico[]
   */
  public function getFornecedores() {

    if (count($this->aFornecedores) > 0) {
      return $this->aFornecedores;
    }

    $oDaoOrcamentoCompraFornecedor = new cl_pcorcamforne();

    $sWhere                          = "pc21_codorc = {$this->getCodigo()}";
    $sQueryOrcamentoCompraFornecedor = $oDaoOrcamentoCompraFornecedor->sql_query(null, "pc21_numcgm" , "z01_nome", $sWhere);
    $rsOrcamentoCompraFornecedor     = $oDaoOrcamentoCompraFornecedor->sql_record($sQueryOrcamentoCompraFornecedor);

    if ($rsOrcamentoCompraFornecedor || $oDaoOrcamentoCompraFornecedor->numrows > 0) {

      for ($iFornecedor = 0; $iFornecedor < $oDaoOrcamentoCompraFornecedor->numrows; $iFornecedor++) {

        $iCodigoFornecedor     = db_utils::fieldsMemory($rsOrcamentoCompraFornecedor, $iFornecedor)->pc21_numcgm;
        $this->aFornecedores[] = CgmRepository::getByCodigo($iCodigoFornecedor);
      }
    }

    return $this->aFornecedores;
  }


  /**
   * @return ItemOrcamento[]
   */
  public function getItens () {

    if (count($this->aItens) > 0) {
      return $this->aItens;
    }

    $oDaoOrcamentoItem = new cl_pcorcamitem();

    $sCampos  = "pc22_orcamitem, ";
    switch ($this->getTipoOrcamento()) {

      case OrcamentoCompra::TIPO_ORCAMENTO_PROCESSO :

        $sCampos .= " (select pc31_pcprocitem from pcorcamitemproc where pc31_orcamitem = pc22_orcamitem) ";
        break;
      case OrcamentoCompra::TIPO_ORCAMENTO_SOLICITACAO :

        $sCampos .= " (select pc29_solicitem from pcorcamitemsol where pc29_orcamitem = pc22_orcamitem) ";
        break;

      case OrcamentoCompra::TIPO_ORCAMENTO_LICITACAO :

        $sCampos .= " (select pc26_liclicitem from pcorcamitemlic where pc26_orcamitem = pc22_orcamitem) ";
        break;
    }

    $sCampos .= "  as codigo_origem";

    $sWhere             = "pc22_codorc = {$this->getCodigo()}";
    $sSqlItensOrcamento = $oDaoOrcamentoItem->sql_query_itens(null, $sCampos, "pc22_orcamitem", $sWhere);
    $rsItensOrcamento   = $oDaoOrcamentoItem->sql_record($sSqlItensOrcamento);
    if ($rsItensOrcamento || $oDaoOrcamentoItem->numrows > 0)  {

      for ($iItem = 0; $iItem < $oDaoOrcamentoItem->numrows; $iItem++) {

        $oDadosItem = db_utils::fieldsMemory($rsItensOrcamento, $iItem);

        $iCodigoOrigem  = $oDadosItem->codigo_origem;
        $oItemOrcamento = new ItemOrcamento($oDadosItem->pc22_orcamitem);
        switch ($this->getTipoOrcamento()) {

          case OrcamentoCompra::TIPO_ORCAMENTO_PROCESSO:

            $oItemOrigem = ItemProcessoCompraRepository::getItemByCodigo($iCodigoOrigem);
            $oItemOrcamento->setItemOrigem($oItemOrigem);
            $oItemOrcamento->setItemSolicitacao($oItemOrigem->getItemSolicitacao());
            break;

          case OrcamentoCompra::TIPO_ORCAMENTO_SOLICITACAO:

            $oItemSolicitacao = new itemSolicitacao($iCodigoOrigem);
            $oItemOrcamento->setItemOrigem($oItemSolicitacao);
            $oItemOrcamento->setItemSolicitacao($oItemSolicitacao);
            break;

          case OrcamentoCompra::TIPO_ORCAMENTO_LICITACAO:

            $oItemLicitacao = new ItemLicitacao($iCodigoOrigem);
            $oItemOrcamento->setItemOrigem($oItemLicitacao);
            $oItemOrcamento->setItemSolicitacao($oItemLicitacao->getItemSolicitacao());
            break;
        }
        $this->aItens[] = $oItemOrcamento;
      }
    }
    return $this->aItens;
  }

  /**
   * Retorna o tipo julgamento
   *
   * @return Integer
   */
  public function getFormaJulgamento() {

    $iTipoJulgamento = self::FORMA_JULGAMENTO_ITEM;

    switch ($this->getTipoOrcamento()) {

      case OrcamentoCompra::TIPO_ORCAMENTO_PROCESSO :

        $oDaoItemOrcamentoProcesso = new cl_pcorcamitemproc();

        $sWhere               = "pc20_codorc = {$this->getCodigo()} limit 1";
        $sQueryTipoJulgamento = $oDaoItemOrcamentoProcesso->sql_query(null, null, "pc80_tipoprocesso", null, $sWhere);
        $rsTipoJulgamneto     = $oDaoItemOrcamentoProcesso->sql_record($sQueryTipoJulgamento);

        if ($rsTipoJulgamneto || $oDaoItemOrcamentoProcesso->numrows > 0) {

          $oDadosTipoJulgamento = db_utils::fieldsMemory($rsTipoJulgamneto, 0);
          $iTipoJulgamento      = $oDadosTipoJulgamento->pc80_tipoprocesso;
        }

        break;
    }

    return $iTipoJulgamento;
  }

  /**
   * Remove os dados do Orcamento
   */
  public function remover() {

    $oDaoOrcamItemJulg = new cl_pcorcamjulg;
    $oDaoOrcamVal      = new cl_pcorcamval();
    $oDaoOrcamDescla   = new cl_pcorcamdescla();
    $oDaoOrcamItem     = new cl_pcorcamitem();
    $oDaoOrcamento     = new cl_pcorcam();
    switch ($this->getTipoOrcamento()) {

      case OrcamentoCompra::TIPO_ORCAMENTO_LICITACAO:

        $oDaoItem = new cl_pcorcamitemlic();
        break;

      case OrcamentoCompra::TIPO_ORCAMENTO_SOLICITACAO:

        $oDaoItem = new cl_pcorcamitemsol();
        break;

      case OrcamentoCompra::TIPO_ORCAMENTO_PROCESSO:

        $oDaoItem = new cl_pcorcamitemproc();
        break;
    }

    foreach ($this->getItens() as $oItem) {

      $oDaoOrcamItemJulg->excluir($oItem->getCodigo());
      if ($oDaoOrcamItemJulg->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_itens_julgamento"));
      }

      $oDaoOrcamVal->excluir(null, $oItem->getCodigo());
      if ($oDaoOrcamVal->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_valor_fornecedores"));
      }

      $oDaoOrcamDescla->excluir($oItem->getCodigo());
      if ($oDaoOrcamDescla->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_fornecedores_desclassificados"));
      }

      $oDaoOrcamDescla->excluir($oItem->getCodigo());
      if ($oDaoOrcamDescla->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_fornecedores_desclassificados"));
      }

      $oDaoItem->excluir($oItem->getCodigo());
      if ($oDaoItem->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_item"));
      }

      $oDaoOrcamItem->excluir($oItem->getCodigo());
      if ($oDaoOrcamItem->erro_status == 0) {
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_item"));
      }

    }

    $oDaoOrcamForne   = new cl_pcorcamforne();
    $oDaoOrcamForne->excluir(null, "pc21_codorc = {$this->getCodigo()}");
    if ($oDaoOrcamForne->erro_status == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_fornecedores"));
    }

    $oDaoOrcamento->excluir($this->getCodigo());
    if ($oDaoOrcamento->erro_status == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM . "erro_exclusao_orcamento"));
    }
  }

  /**
   * Retorna os dados do ORcamento do Fornecedor
   * @param CgmBase $oFornecedor
   * @return OrcamentoFornecedor
   */
  public function getOrcamentoDoFornecedor(CgmBase $oFornecedor) {

    $oDaoFornecedores    = new cl_pcorcamforne();
    $sWhereOrcamento     = " pc21_numcgm={$oFornecedor->getCodigo()}";
    $sWhereOrcamento    .= " and pc21_codorc = {$this->getCodigo()}";
    $sSqlDadosFornecedor = $oDaoFornecedores->sql_query_file(null, "*", null, $sWhereOrcamento);
    $rsOrcamento         = $oDaoFornecedores->sql_record($sSqlDadosFornecedor);
    if (!$rsOrcamento || $oDaoFornecedores->numrows == 0) {
      return false;
    }

    $oDadosOrcamento = db_utils::fieldsMemory($rsOrcamento, 0);
    $oOrcamento      = new OrcamentoFornecedor();
    $oOrcamento->setCodigo($oDadosOrcamento->pc21_orcamforne);
    $oOrcamento->setFornecedor($oFornecedor);
    $oOrcamento->setOrcamento($this);
    if (!empty($oDadosOrcamento->pc21_prazoent)) {
      $oOrcamento->setPrazoEntrega(new DBDate($oDadosOrcamento->pc21_prazoent));
    }
    if (!empty($oDadosOrcamento->pc21_validadorc)) {
      $oOrcamento->setValidadeOrcamento(new DBDate($oDadosOrcamento->pc21_validadorc));
    }
    return $oOrcamento;
  }
}
