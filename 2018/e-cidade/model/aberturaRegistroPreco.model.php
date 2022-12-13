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

require_once(modification('classes/solicitacaocompras.model.php'));
require_once(modification("model/itemSolicitacao.model.php"));

/**
 * Cria um nova Abertura para um registro de Preço
 * @package Compras
 */
class aberturaRegistroPreco extends solicitacaoCompra {


  protected $aItens = array();

  protected $iCodigoAbertura;

  protected $iCodigoSolicitacao;

  protected $dtDataInicio;

  protected $dtDataTermino;

  protected $dtDataSolicitacao;

  protected $sResumo;

  private $iTipoSolicitacao = 3;

  protected $lLiberado  = false;

  protected $iCodigoDepartamento;

  protected $sDescricaoDepartamento;

  protected $sDataAnulacao;

  protected $oParametroRegistroPreco;

  protected $aEstimativas = array();

  protected $lItensAlterados = false;

  const CONTROLA_QUANTIDADE = 1;
  const CONTROLA_VALOR = 2;

  protected $iFormaControle = self::CONTROLA_QUANTIDADE;


  /**
   *
   *@param integer $iSolicitacao
   */
  public function __construct($iRegistroCompras = '') {

    if (!empty ($iRegistroCompras)) {

      parent::__construct($iRegistroCompras);
      $oDaoRegistroPreco = db_utils::getDao("solicitaregistropreco");
      $sSqlDadosRegistro = $oDaoRegistroPreco->sql_query_solicitaanulada(null,
                                                                         "*",
                                                                         null,
                                                                         "pc10_numero={$iRegistroCompras}");

      $rsDadosRegistro   = $oDaoRegistroPreco->sql_record($sSqlDadosRegistro);

      if ($oDaoRegistroPreco->numrows) {

        $oDadosRegistro               = db_utils::fieldsMemory($rsDadosRegistro, 0);
        $this->iCodigoAbertura        = $oDadosRegistro->pc54_sequencial;
        $this->iCodigoSolicitacao     = $oDadosRegistro->pc54_solicita;
        $this->sResumo                = $oDadosRegistro->pc10_resumo;
        $this->dtDataInicio           = $oDadosRegistro->pc54_datainicio;
        $this->dtDataTermino          = $oDadosRegistro->pc54_datatermino;
        $this->dtDataSolicitacao      = $oDadosRegistro->pc10_data;
        $this->lLiberado              = $oDadosRegistro->pc54_liberado == 't'?true:false;
        $this->iCodigoDepartamento    = $oDadosRegistro->coddepto;
        $this->sDescricaoDepartamento = $oDadosRegistro->descrdepto;
        $this->sDataAnulacao          = $oDadosRegistro->pc67_data;
        $this->iFormaControle         = $oDadosRegistro->pc54_formacontrole;
      }
    }

    if (DBRegistry::get("parametroRegistroPreco") == '') {

      $aParametro = db_stdClass::getParametro("registroprecoparam",  array(db_getsession("DB_instit")));
      if (count($aParametro) > 0) {
      DBRegistry::add("parametroRegistroPreco",
                      $aParametro[0]
                     );
      }
    }
  }

  /**
   * Retorna o Código do departamento
   * @return integer
   */
  public function getCodigoDepartamento() {

    return $this->iCodigoDepartamento;
  }
  /**
   * Retorna o Código do departamento
   * @return string
   */
  public function getDescricaoDepartamento() {

    return $this->sDescricaoDepartamento;
  }
  /**
   * Retorna o Código do departamento
   * @return string
   */
  public function getDataAnulacao() {

    return $this->sDataAnulacao;
  }

  /**
   * Adiciona um item ao Registro de Compras
   *
   * @param itemSolicitacao $oItem
   * @return aberturaRegistroPreco
   */
  public function addItem(itemSolicitacao $oItem) {

    if (count($this->aItens) == 0) {
      $this->aItens = $this->getItens();
    }
    $oItem->setOrdem(count($this->aItens)+1);
    $this->aItens[] = $oItem;
    if (ParametroRegistroPreco::permiteAlterarAbertura()) {

      foreach ($this->getEstimativas() as $oEstimativa) {

        $oItemNovo = new ItemEstimativa(null, $oItem->getCodigoMaterial());
        $oItemNovo->setUnidade($oItem->getUnidade());
        $oItemNovo->setQuantidadeUnidade($oItem->getQuantidadeUnidade());
        $oItemNovo->setOrdem($oItem->getOrdem());
        $oItemNovo->setJustificativa($oItem->getJustificativa());
        $oItemNovo->setResumo($oItem->getResumo());
        $oItemNovo->setPrazos($oItem->getPrazos());
        $oItemNovo->setPagamento($oItem->getPagamento());
        $oItemNovo->setAutimatico(true);
        $oItemNovo->setQuantidade($oItem->getQuantidade());
        $oItemNovo->setVinculo($oItem);
        $oEstimativa->addItem($oItemNovo);
      }
    }
    $this->lItensAlterados = true;
    return $this;

  }
  /**
   * Retorna os itens cadastrados na solicitacao
   *
   * @return itemSolicitacao[]
   */
  public function getItens() {

    if ($this->iCodigoSolicitacao != "" && count($this->aItens) == 0) {

      $oDaoSolicitem = db_utils::getDao("solicitem");
      $sSqlItens     = $oDaoSolicitem->sql_query_mat(null,"*","pc11_seq", "pc11_numero={$this->iCodigoSolicitacao}");
      $rsItens       = $oDaoSolicitem->sql_record($sSqlItens);
      if ($oDaoSolicitem->numrows > 0) {

        for ($iItem = 0; $iItem < $oDaoSolicitem->numrows; $iItem++) {

          $oItem = db_utils::fieldsMemory($rsItens, $iItem, false, false, true);
          $oItemSolicitacao = new itemSolicitacao($oItem->pc11_codigo);
          $this->aItens[]   = $oItemSolicitacao;
          unset($oItem);

        }
      }
    }

    return $this->aItens;
  }

  /**
   * Anula
   *
   * @return
   */

  public function anular($sMotivo, $sProcessoAdministrativo = null) {

  	$lSolicitaAnulada = $this->isAnulada();

  	if (!$lSolicitaAnulada) {

  		$oDaoSolicitaAnulada                = db_utils::getDao("solicitaanulada");
  		$oDaoSolicitaAnulada->pc67_usuario  = db_getsession("DB_id_usuario");
  		$oDaoSolicitaAnulada->pc67_data     = date("Y-m-d",db_getsession("DB_datausu"));
  		$oDaoSolicitaAnulada->pc67_hora     = date("H:m",db_getsession("DB_datausu"));
  		$oDaoSolicitaAnulada->pc67_solicita = $this->getCodigoSolicitacao();
  		$oDaoSolicitaAnulada->pc67_motivo   = $sMotivo;
  		$oDaoSolicitaAnulada->incluir(null);

  		if ($oDaoSolicitaAnulada->erro_status == "0") {
  			throw new Exception("Erro ao anular Abertura de Registro de Preço!\n{$oDaoSolicitaAnulada->erro_msg}");
  	  }
  	}

  }

  /**
   * Verifica se a abertura está anulada
   *
   * @return boolean
   */

  public function isAnulada() {

    $oDaoSolicitaAnulada = db_utils::getDao("solicitaanulada");
    $sWhere   = "pc67_solicita = ".$this->getCodigoSolicitacao();
    $sCampos  = "*";

    $sSqlSolicitaAnulada  = $oDaoSolicitaAnulada->sql_query_file(null,$sCampos,null,$sWhere);
    $rsSqlSolicitaAnulada = $oDaoSolicitaAnulada->sql_record($sSqlSolicitaAnulada);

    if ($oDaoSolicitaAnulada->numrows > 0) {

    	return true;
    } else {

    	return false;
    }
  }

  /**
   * Salva os dados da Solicitaçao na base de dados
   *
   * @return aberturaRegistroPreco
   */
  public function save() {

    $oDaoSolicitacao = db_utils::getDao("solicita");
    $oDaoSolicitacao->pc10_correto         = "true";
    $oDaoSolicitacao->pc10_data            = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoSolicitacao->pc10_resumo          = $this->getResumo();
    $oDaoSolicitacao->pc10_solicitacaotipo = $this->iTipoSolicitacao;
    if ($this->getCodigoSolicitacao() == null) {

      $oDaoSolicitacao->pc10_depto           = db_getsession("DB_coddepto");
      $oDaoSolicitacao->pc10_instit          = db_getsession("DB_instit");
      $oDaoSolicitacao->pc10_login           = db_getsession("DB_id_usuario");
      $oDaoSolicitacao->incluir(null);
      $this->iCodigoSolicitacao   = $oDaoSolicitacao->pc10_numero;

    } else {

      $oDaoSolicitacao->pc10_numero = $this->getCodigoSolicitacao();
      $oDaoSolicitacao->alterar($this->getCodigoSolicitacao());

    }
    if ($oDaoSolicitacao->erro_status == 0) {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoSolicitacao->erro_msg}");
    }
    /**
     * salvamos os dados da Abertura
     */
    $oDaoAberturaPreco = db_utils::getDao("solicitaregistropreco");
    $oDaoAberturaPreco->pc54_datainicio  = implode("-", array_reverse(explode("/", $this->getDataInicio())));
    $oDaoAberturaPreco->pc54_datatermino = implode("-", array_reverse(explode("/", $this->getDataTermino())));
    $oDaoAberturaPreco->pc54_liberado    = $this->isLiberado()?"true":"false";
    $oDaoAberturaPreco->pc54_formacontrole = $this->getFormaDeControle();
    if ($this->getCodigoAbertura() != null) {

      $oDaoAberturaPreco->pc54_sequencial = $this->getCodigoAbertura();
      $oDaoAberturaPreco->alterar($this->getCodigoAbertura());

    } else {

      $oDaoAberturaPreco->pc54_solicita   = $this->getCodigoSolicitacao();
      $oDaoAberturaPreco->incluir(null);
      $this->iCodigoAbertura = $oDaoAberturaPreco->pc54_sequencial;

    }
    if ($oDaoAberturaPreco->erro_status == "0") {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoAberturaPreco->erro_msg}");
    }

    unset($oDaoAberturaPreco);
    unset($oDaoSolicitacao);

    $iSeq = 1;
    if ($this->lItensAlterados) {

      foreach ($this->aItens as $oItem) {

        $oItem->setOrdem($iSeq);
        $oItem->save($this->iCodigoSolicitacao);
        $iSeq ++;
        /**
         * Atualiza os dados Complementares do item nas solicitacoes que o item é filho;
         */
        $sUpdate  = "update solicitem set pc11_just   = '".pg_escape_string(urldecode($oItem->getJustificativa()))."',";
        $sUpdate .= "                     pc11_prazo  = '".pg_escape_string(urldecode($oItem->getPrazos()))."',";
        $sUpdate .= "                     pc11_pgto   = '".pg_escape_string(urldecode($oItem->getPagamento()))."',";
        $sUpdate .= "                     pc11_resum  = '".pg_escape_string(urldecode($oItem->getResumo()))."'";
        $sUpdate .= "  from solicitemvinculo";
        $sUpdate .= " where pc55_solicitemfilho = pc11_codigo";
        $sUpdate .= "   and pc55_solicitempai   = {$oItem->getCodigoItemSolicitacao()} ";
        $rsUpdate = db_query($sUpdate);
        if (!$rsUpdate) {
          throw new Exception("Erro ao salvar dados do item da abertura\n".pg_last_error());
        }
        $oDaosolicitemUnid = db_utils::getDao("solicitemunid");
        $oDaosolicitemUnid->excluir($oItem->getCodigoItemSolicitacao());
        $oDaosolicitemUnid->pc17_codigo  = $oItem->getCodigoItemSolicitacao();
        $oDaosolicitemUnid->pc17_quant   = "1";
        $oDaosolicitemUnid->pc17_unid    = "{$oItem->getUnidade()}";
        $oDaosolicitemUnid->incluir($oItem->getCodigoItemSolicitacao());
        if ($oDaosolicitemUnid->erro_status == 0) {
          throw new Exception("Erro ao salvar item {$oItem->getCodigoMaterial()}!\nErro Retornado:{$oDaosolicitemUnid->erro_msg}");
        }

      }
      if (ParametroRegistroPreco::permiteAlterarAbertura()) {

        foreach ($this->getEstimativas() as $oEstimativa) {

          $oEstimativa->setAlterado(false);
          $oEstimativa->save();
        }
      }
    }
    $this->lItensAlterados = false;
    return $this;
  }
  /**
   * @return unknown
   */
  public function getDataInicio() {

    return $this->dtDataInicio;
  }

  /**
   * @param unknown_type $dtDataInicio
   */
  public function setDataInicio($dtDataInicio) {

    $this->dtDataInicio = $dtDataInicio;
  }

  /**
   * Retorna a data da inclusão da solicitação
   * @return string
   */
  public function getDataSolicitacao() {
    return $this->dtDataSolicitacao;
  }

  /**
   * Retorna a data de termino da vigencia da abertura do registo de preços
   * @return string
   */
  public function getDataTermino() {

    return $this->dtDataTermino;
  }

  /**
   * Define a data de termino da vigencia da abertura do registro de preco
   *
   * @param string $dtDataTermino string no formato "dd/mm/YYYY"
   * @return aberturaRegistroPreco
   */
  public function setDataTermino($dtDataTermino) {
    $this->dtDataTermino = $dtDataTermino;
  }

  /**
   * Retorna o Codigo da Abertura de Preço
   * @return  integer
   */
  public function getCodigoAbertura() {
    return $this->iCodigoAbertura;
  }

  /**
   * Retorna o codigo da solicitação de Compras Criadas para o registro de compra
   * @return integer
   */
  public function getCodigoSolicitacao() {
    return $this->iCodigoSolicitacao;
  }

  /**
   * retorno a resumo da Abertura
   * @return string
   */
  public function getResumo() {
    return $this->sResumo;
  }

  /**
   *
   * Define o resumo da Abertura
   * @param string $sResumo Resumo
   * @return aberturaRegistroPreco
   */
  public function setResumo($sResumo) {

    $this->sResumo = $sResumo;
    return $this;

  }

  /**
   * Retorna o tipo da solicitação Criada
   *
   * @return integer
   */
  public function getTipoSolicitacao() {
    return $this->iTipoSolicitacao;
  }

  /**
   *
   * Item verificado
   * @return boolean
   */
  public function isLiberado() {
    return  $this->lLiberado;
  }
  /**
   * Define se o item está liberado ou nao
   *
   * @param boolean $lLiberado
   */
  public function setLiberado($lLiberado) {
   $this->lLiberado = $lLiberado;
  }

  /**
   * Remove o Item informado da solicitacao;
   *
   * @param  integer $iSeq item a ser removido
   * @return aberturaRegistroPreco
   */
  public function removerItem($iSeq) {

    if ($iSeq >= 0) {

      $aItens = $this->getItens();
      if (isset($aItens[$iSeq])) {

        /**
         * caso o sistema permite a alteração do registro de preço, devemos
         * excluir os itens das estimativas do registro.
         */
        if (ParametroRegistroPreco::permiteAlterarAbertura()) {

          $this->removerItemVinculadoNoItemDaAbertura($aItens[$iSeq]->getCodigoItemSolicitacao());
          /**
          foreach ($this->getEstimativas() as $oEstimativa) {

            $oItem = $oEstimativa->getItemByCodigoOrigem($aItens[$iSeq]->getCodigoItemSolicitacao());
            if (!$oItem >= 0) {
              $oEstimativa->removerItem($oItem);
            }
          }
         */
        }
        $aItens[$iSeq]->remover();
        unset($this->aItens[$iSeq]);
        $this->lItensAlterados = true;
      }
    }
    return $this;
  }

  public function removerItemVinculadoNoItemDaAbertura($iCodigoItemAbertura) {

    $oDaoVinculo         = new cl_solicitemvinculo();
    $sSqlItensVinculados = $oDaoVinculo->sql_query_file(null,
                                                        "pc55_solicitemfilho",
                                                        null, "pc55_solicitempai = {$iCodigoItemAbertura}"
                                                       );
    $rsItensAbertura = $oDaoVinculo->sql_record($sSqlItensVinculados);
    $iTotalItens     = $oDaoVinculo->numrows;
    if ($iTotalItens > 0) {

      for ($iItens = 0; $iItens < $iTotalItens; $iItens++) {

        $oItem = new ItemEstimativa(db_utils::fieldsMemory($rsItensAbertura, $iItens)->pc55_solicitemfilho);
        $oItem->remover();
      }
    }
  }

  /**
   * retorna as Estimativas feitas para a abertura do registro de preço
   *
   * @return estimativaRegistroPreco[]
   */
  public function getEstimativas($iDepartamento = false) {

  	if (count($this->aEstimativas) == 0) {

  	  $oDaoSolicita = db_utils::getDao('solicitavinculo');
      $sWhere       = " pc53_solicitapai = ".$this->iCodigoSolicitacao."  and pc10_solicitacaotipo = 4 ";
      if ($iDepartamento) {
         $sWhere    .= " AND pc10_depto = {$iDepartamento}";
      }
      $sSql         = $oDaoSolicita->sql_query_filhas(null,"pc53_solicitafilho",null,$sWhere);

      $rsSql        = $oDaoSolicita->sql_record($sSql);
    	if ($oDaoSolicita->numrows > 0) {

        for($iInd = 0; $iInd < $oDaoSolicita->numrows; $iInd++) {

        	$oEstimativa   = new estimativaRegistroPreco(db_utils::fieldsMemory($rsSql,$iInd)->pc53_solicitafilho);
        	$this->aEstimativas[] = $oEstimativa;
        }
    	}
  	}
  	return $this->aEstimativas;
  }

  /**
   * retorna todas as compilações realizadas para a abertura
   *
   * @param null $lSolicitaCancelada
   * @return Collection CompilacaoRegistroPreco
   */
  public function getCompilacoes($lSolicitaCancelada=null) {

    $aCompilacoes = false;

    $oDaoSolicita = db_utils::getDao('solicitavinculo');
    $sWhere       = " pc10_solicitacaotipo = 6 and pc53_solicitapai = ".$this->iCodigoSolicitacao ;
    if(isset($lSolicitaCancelada)){
    	$sWhere      .= "  and pc67_data is null ";
    }
    $sSql         = $oDaoSolicita->sql_query_filhas(null,"pc53_solicitafilho",null,$sWhere);
    $rsSql        = $oDaoSolicita->sql_record($sSql);

    if ($oDaoSolicita->numrows > 0) {
      for($iInd = 0; $iInd < $oDaoSolicita->numrows; $iInd++) {
        $aCompilacoes[] = new compilacaoRegistroPreco(db_utils::fieldsMemory($rsSql,$iInd)->pc53_solicitafilho);
      }
    }

    return $aCompilacoes;
  }


  /**
   * Retorna a estimativa cadastrada para o Departamento
   * @param integer $iDepartamento Codigo do departamento;
   * @return estimativaRegistroPreco
   */
  public function getEstimativaPorDepartamento($iDepartamento) {

    $aEstimativas = $this->getEstimativas();
    foreach ($aEstimativas as $oEstimativa) {

      if ($oEstimativa->getCodigoDepartamento() == $iDepartamento) {
        return $oEstimativa;
      }
    }
    return false;
  }

  /**
   * Verifica se a abertura possui estimativas Vinculadas
   * @return bool
   */
  public function hasEstimativas() {

    $oDaoSolicita = db_utils::getDao('solicitavinculo');
    $sWhere       = " pc53_solicitapai = ".$this->iCodigoSolicitacao."  and pc10_solicitacaotipo = 4 ";
    $sSqlVinculos = $oDaoSolicita->sql_query_filhas(null,"pc53_solicitafilho",null,$sWhere);
    $oDaoSolicita->sql_record($sSqlVinculos);
    if ($oDaoSolicita->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se a abertura possui estimativa anulada
   * @return bool
   */
  public function possuiEstimativaValida() {

    $aWhere = array(
      "pc53_solicitapai = {$this->iCodigoSolicitacao}",
      "pc10_solicitacaotipo = 4",
      "pc67_solicita is null",
    );

    $oDaoSolicita = new cl_solicitavinculo();
    $sSqlVinculos = $oDaoSolicita->sql_query_filhas(null,"pc53_solicitafilho",null, implode(' and ', $aWhere));
    $oDaoSolicita->sql_record($sSqlVinculos);
    if ($oDaoSolicita->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * @param $iFormaControle
   */
  public function setFormaDeControle($iFormaControle) {
    $this->iFormaControle = $iFormaControle;
  }

  /**
   * @return int
   */
  public function getFormaDeControle() {
    return $this->iFormaControle;
  }
}
