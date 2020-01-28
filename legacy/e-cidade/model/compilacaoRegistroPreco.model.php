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
require_once(modification("model/itemCompilacao.model.php"));

/**
 * Cria um nova Abertura para um registro de Preço
 * @package Compras
 */
class compilacaoRegistroPreco extends solicitacaoCompra {


  /**
   * @var itemCompilacao[]
   */
  protected $aItens = array();

  protected $iCodigoRegistro;

  protected $iCodigoAbertura;

  protected $iCodigoSolicitacao;

  protected $dtDataInicio;

  protected $dtDataTermino;

  protected $dtDataSolicitacao;

  protected $sResumo;

  private $iTipoSolicitacao = 6;

  protected $lLiberado  = false;

  protected $iCodigoDepartamento;

  protected $sDescricaoDepartamento;

  protected $sDataAnulacao;

  protected  $iFormaControle = aberturaRegistroPreco::CONTROLA_QUANTIDADE;

  /**
   *@param integer $iRegistroCompras
   */
  public function __construct($iRegistroCompras = '') {

    if (!empty ($iRegistroCompras)) {

      parent::__construct($iRegistroCompras);
      $oDaoRegistroPreco = db_utils::getDao("solicitaregistropreco");
      $sSqlDadosRegistro = $oDaoRegistroPreco->sql_query_origem(null, "*", null,"pc10_numero={$iRegistroCompras}");
      $rsDadosRegistro   = $oDaoRegistroPreco->sql_record($sSqlDadosRegistro);
      if ($oDaoRegistroPreco->numrows) {

        $oDadosRegistro               = db_utils::fieldsMemory($rsDadosRegistro, 0);
        $this->iCodigoAbertura        = $oDadosRegistro->pc53_solicitapai;
        $this->iCodigoSolicitacao     = $oDadosRegistro->pc54_solicita;
        $this->sResumo                = $oDadosRegistro->pc10_resumo;
        $this->dtDataInicio           = $oDadosRegistro->pc54_datainicio;
        $this->dtDataTermino          = $oDadosRegistro->pc54_datatermino;
        $this->dtDataSolicitacao      = $oDadosRegistro->pc10_data;
        $this->iCodigoRegistro        = $oDadosRegistro->pc54_sequencial;
        $this->iCodigoDepartamento    = $oDadosRegistro->coddepto;
        $this->sDescricaoDepartamento = $oDadosRegistro->descrdepto;
        $this->sDataAnulacao          = $oDadosRegistro->pc67_data;
        $this->iFormaControle         = $oDadosRegistro->pc54_formacontrole;
      }
    }
  }


  /**
   * Retorna o Código do departamento
   * @return interger
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
   * @return aberturaRegistroPreco
   */
  public function addItem(itemCompilacao  $oItem) {

    if (count($this->aItens) == 0) {
      $this->aItens = $this->getItens();
    }
    $oItem->setOrdem(count($this->aItens)+1);
    $this->aItens[] = $oItem;
    return $this;

  }

  /**
   * Retorna os itens cadastrados na solicitacao
   *
   * @return itemCompilacao[]
   */
  public function getItens() {

    if ($this->iCodigoSolicitacao != "" && count($this->aItens) == 0) {

      $oDaoSolicitem = db_utils::getDao("solicitem");
      $sSqlItens     = $oDaoSolicitem->sqlItensComValorLancado(null,"*,
                                                    exists(SELECT 1
                                                            from solicitemregistropreco b
                                                            inner join solicitemvinculo on pc55_solicitempai   = b.pc57_itemorigem
                                                            inner join solicitem      a on pc55_solicitempai = a.pc11_codigo
                                                            inner join solicita         on a.pc11_numero = pc10_numero
                                                            and pc10_solicitacaotipo = 3
                                                       where b.pc57_solicitem = solicitem.pc11_codigo
                                                         ) as item_abertura",
                                                      "pc11_seq",
                                                      "pc11_numero={$this->iCodigoSolicitacao}");
      $rsItens       = $oDaoSolicitem->sql_record($sSqlItens);
      if ($oDaoSolicitem->numrows > 0) {

        for ($iItem = 0; $iItem < $oDaoSolicitem->numrows; $iItem++) {

          $oItem            = db_utils::fieldsMemory($rsItens, $iItem, false, false, true);
          $oItemSolicitacao = new itemCompilacao($oItem->pc11_codigo);
          $oItemSolicitacao->setAutimatico($oItem->item_abertura=="t"?true:false);
          $this->aItens[]   = $oItemSolicitacao;
          unset($oItem);

        }
      }
    }

    return $this->aItens;
  }

  /**
   * Retorna a abertura do registro de Preco
   * @return aberturaRegistroPreco
   */
  public function getAberturaRegistroPreco() {
    return new aberturaRegistroPreco($this->iCodigoAbertura);
  }


  /**
   * Salvamos os dados da Abertura
   */
  public function salvarDadosAbertura() {

    $oAbertura         = $this->getAberturaRegistroPreco();
    $oDaoAberturaPreco = new cl_solicitaregistropreco;
    $oDaoAberturaPreco->pc54_datainicio    = implode("-", array_reverse(explode("/", $this->getDataInicio())));
    $oDaoAberturaPreco->pc54_datatermino   = implode("-", array_reverse(explode("/", $this->getDataTermino())));
    $oDaoAberturaPreco->pc54_liberado      = $this->isLiberado()?"true":"false";
    $oDaoAberturaPreco->pc54_formacontrole = $oAbertura->getFormaDeControle();
    if ($this->iCodigoRegistro != null) {

      $oDaoAberturaPreco->pc54_sequencial = $this->iCodigoRegistro;
      $oDaoAberturaPreco->alterar($this->iCodigoRegistro);

    } else {

      $oDaoAberturaPreco->pc54_solicita   = $this->getCodigoSolicitacao();
      $oDaoAberturaPreco->incluir(null);
      $this->iCodigoRegistro = $oDaoAberturaPreco->pc54_sequencial;

    }
    if ($oDaoAberturaPreco->erro_status == 0) {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoAberturaPreco->erro_msg}");
    }
  }

  /**
   * Salva os dados da Solicitaçao na base de dados
   *
   * @throws Exception
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
      throw new Exception("Erro ao salvar Compilação do registro de preço!\n{$oDaoSolicitacao->erro_msg}");
    }
    $oAbertura = $this->getAberturaRegistroPreco();
    $this->setFormaDeControle($oAbertura->getFormaDeControle());

    $this->salvarDadosAbertura();

    /**
     * Salvamos o Vinculo com a abertura
     */
    $oDaoVinculo                     = db_utils::getDao("solicitavinculo");
    $oDaoVinculo->pc53_solicitapai   = $this->iCodigoAbertura;
    $oDaoVinculo->pc53_solicitafilho = $this->getCodigoSolicitacao();
    $sSqlVerificaVinculo  = $oDaoVinculo->sql_query_file(null,"*",
                                                               null,
                                                               "pc53_solicitafilho={$this->getCodigoSolicitacao()}"
                                                               );

    $rsVerificaVinculo  = $oDaoVinculo->sql_record($sSqlVerificaVinculo);

    if ($oDaoVinculo->numrows > 0) {

      $oVinculoSolicitacao = db_utils::fieldsMemory($rsVerificaVinculo, 0);
      $oDaoVinculo->pc53_sequencial = $oVinculoSolicitacao->pc53_sequencial;
      $oDaoVinculo->alterar($oVinculoSolicitacao->pc53_sequencial);

    } else {
      $oDaoVinculo->incluir(null);
    }
    if ($oDaoVinculo->erro_status == 0) {
      throw new Exception("Erro ao salvar Abertura de Registro de Preço!\n{$oDaoVinculo->erro_msg}");
    }
    unset($oDaoAberturaPreco);
    unset($oDaoSolicitacao);
    if (count($this->getItens()) == 0) {

      /**
       * Verificamos os parametros do registro de Preço
       */
      $iPercentualMaximo   = 0;
      $aParametrosRegistro = db_stdClass::getParametro("registroprecoparam",array(db_getsession("DB_instit")));
      if (count($aParametrosRegistro) > 0) {
        if ($aParametrosRegistro[0]->pc08_percentuquantmax > 0) {
          $iPercentualMaximo = $aParametrosRegistro[0]->pc08_percentuquantmax;
        }
      }

      /**
       * incluimos os itens da abertura na solicitacao
       */
      $sSqlItensRegistro = "SELECT codigo, ";
      $sSqlItensRegistro .= "      pc01_descrmater, ";
      $sSqlItensRegistro .= "      pc17_unid, ";
      $sSqlItensRegistro .= "      pc01_codmater, ";
      $sSqlItensRegistro .= "      pc17_quant, ";
      $sSqlItensRegistro .= "      pc11_resum, ";
      $sSqlItensRegistro .= "      pc11_just, ";
      $sSqlItensRegistro .= "      pc11_pgto, ";
      $sSqlItensRegistro .= "      pc11_prazo, ";
      $sSqlItensRegistro .= "      pc11_seq, ";
      $sSqlItensRegistro .= "      total, ";
      $sSqlItensRegistro .= "      tipoitem, ";
      /**
       * Quando a abertura for por valor, temos que pegar o valor unitario lancamento, ignoramos as quantidades
       */
      if ($this->getFormaDeControle() == aberturaRegistroPreco::CONTROLA_VALOR) {

        $sSqlItensRegistro .= "(select abertura.pc11_vlrun";
        $sSqlItensRegistro .= "   from solicitem abertura ";
        $sSqlItensRegistro .= "  where abertura.pc11_seq    = solicitem.pc11_seq";
        $sSqlItensRegistro .= "    and abertura.pc11_numero = {$this->iCodigoAbertura}";
        $sSqlItensRegistro .= ") as valor_item,";
      }
      $sSqlItensRegistro .= "      estimativas ";
      $sSqlItensRegistro .= " FROM (SELECT (case when itemabertura.pc11_codigo is null ";
      $sSqlItensRegistro .= "                    then itemestimativa.pc11_codigo ";
      $sSqlItensRegistro .= "               else itemabertura.pc11_codigo end) as codigo, ";
      $sSqlItensRegistro .= "              (case when itemabertura.pc11_codigo is null ";
      $sSqlItensRegistro .= "                    then 2 ";
      $sSqlItensRegistro .= "               else 1 end) as tipoitem, ";
      $sSqlItensRegistro .= "               sum(itemestimativa.pc11_quant) as total, ";
      $sSqlItensRegistro .= "               array_to_string(array_accum(itemestimativa.pc11_codigo),',') as estimativas ";
      $sSqlItensRegistro .= "          from solicitem itemestimativa ";
      $sSqlItensRegistro .= "               left join solicitemvinculo as vinculafilho on itemestimativa.pc11_codigo = ";
      $sSqlItensRegistro .= "                                             vinculafilho.pc55_solicitemfilho ";
      $sSqlItensRegistro .= "               left join solicitem itemabertura on itemabertura.pc11_codigo = ";
      $sSqlItensRegistro .= "                                                   vinculafilho.pc55_solicitempai ";
      $sSqlItensRegistro .= "               inner join solicita on itemestimativa.pc11_numero = pc10_numero ";
      $sSqlItensRegistro .= "               inner join solicitavinculo on pc53_solicitafilho  = pc10_numero";
      $sSqlItensRegistro .= "               left  join solicitaanulada on pc53_solicitafilho  = pc67_solicita";
      $sSqlItensRegistro .= "         where pc53_solicitapai = {$this->iCodigoAbertura}";
      $sSqlItensRegistro .= "           and pc67_sequencial is null";
      $sSqlItensRegistro .= "         group by 1,2 ";
      $sSqlItensRegistro .= "      ) as x ";
      $sSqlItensRegistro .= "       inner join solicitem        on codigo        = pc11_codigo ";
      $sSqlItensRegistro .= "       inner join solicitempcmater on pc11_codigo   = pc16_solicitem ";
      $sSqlItensRegistro .= "       inner join pcmater          on pc16_codmater = pc01_codmater ";
      $sSqlItensRegistro .= "       inner join solicitemunid    on pc17_codigo   = pc11_codigo ";
      $sSqlItensRegistro .= " order by pc11_seq,tipoitem";

      $rsItens = db_query($sSqlItensRegistro);
      $aItens  = db_utils::getCollectionByRecord($rsItens);
      $i       = 1;
      foreach ($aItens as $oItem) {

        if ($oItem->total == 0) {
          continue;
        }

        $oItemNovo = new itemCompilacao(null, $oItem->pc01_codmater);
        $oItemNovo->setCodigoItemOrigem($oItem->codigo);
        $oItemNovo->setUnidade($oItem->pc17_unid);
        $oItemNovo->setQuantidadeUnidade($oItem->pc17_quant);
        $oItemNovo->setQuantidadeMinima(1);
        $oItemNovo->setAutimatico($oItem->tipoitem == 1?true:false);
        $nQuantidadeExtendidaItem = round(($oItem->total*$iPercentualMaximo)/100);
        $oItemNovo->setQuantidadeMaxima($oItem->total+$nQuantidadeExtendidaItem);
        $oItemNovo->setAtivo(true);
        $oItemNovo->setOrdem($i);
        $oItemNovo->setJustificativa($oItem->pc11_just);
        $oItemNovo->setResumo($oItem->pc11_resum);
        $oItemNovo->setQuantidade($oItem->total);
        if ($this->getFormaDeControle() == aberturaRegistroPreco::CONTROLA_VALOR) {

          $oItemNovo->setValorUnitario($oItem->valor_item);
          $oItemNovo->setQuantidadeMaxima(1);
          $oItemNovo->setQuantidadeMinima(1);
          $oItemNovo->setQuantidade(1);
        }
        $oItemNovo->setItensEstimativas(explode(",", $oItem->estimativas));
        $oItemNovo->setPrazos($oItem->pc11_prazo);
        $oItemNovo->setPagamento($oItem->pc11_pgto);
        $this->addItem($oItemNovo);
        $i++;
      }
    }

    foreach ($this->aItens as $oItem) {
      $oItem->save($this->iCodigoSolicitacao);
    }
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

  public function setCodigoAbertura($iCodigoAbertura) {
    $this->iCodigoAbertura = $iCodigoAbertura;
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

        $aItens[$iSeq]->remover();
        unset($this->aItens[$iSeq]);
      }
    }
    return $this;
  }
  /**{
   *
   */
  public function __destruct() {

  }

  /**
   * Gera o processo de compras para a solicitacao, e libera para gerar licitação
   *
   * @return compilacaoRegistroPreco
   */
  public function toProcessoCompra() {

    $iNumeroProcesso = $this->getProcessodeCompras();
    if ($iNumeroProcesso != null) {
      throw new Exception("Compilação já processada!\nProcesso de Compras da Cmpilacao({$iNumeroProcesso}).");
    }
    $this->setLiberado(true);
    $this->save();
    $oDaoPcProc                = db_utils::getDao('pcproc');
    $oDaoPcProc->pc80_data     = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoPcProc->pc80_depto    = db_getsession("DB_coddepto");
    $oDaoPcProc->pc80_usuario  = db_getsession("DB_id_usuario");
    $oDaoPcProc->pc80_resumo   = urldecode($this->getResumo());
    $oDaoPcProc->pc80_situacao = 2;
    $oDaoPcProc->incluir(null);
    if ($oDaoPcProc->erro_status == 0) {
      throw new Exception("Erro ao incluir processo de compras para a compilação.\nErro retornado:{$oDaoPcProc->erro_msg}");
    }
    /**
     * Incluimos os itens do processo de compras
     */
    $aItens         = $this->getItens();
    $oDaopcProcItem = db_utils::getDao('pcprocitem');
    foreach ($aItens as $oItem) {

      if ($oItem->isAtivo()) {

        $oDaopcProcItem->pc81_codproc   = $oDaoPcProc->pc80_codproc;
        $oDaopcProcItem->pc81_solicitem = $oItem->getCodigoItemSolicitacao();
        $oDaopcProcItem->incluir(null);
        if ($oDaopcProcItem->erro_status == 0) {
          throw new Exception("Erro ao incluir processo de compras para a compilação.\nErro retornado:{$oDaopcProcItem->erro_msg}");
        }
      }
    }
    return $this;
  }

  /**
   * Verifica o numero do processo de compras da compilaçao
   *@return integer código do processo de compras
   */
  public function getProcessodeCompras() {

    $iNumeroProcesso = null;
    $oDaoPCprocitem  = db_utils::getDao('pcprocitem');
    $sSqlProcesso    = $oDaoPCprocitem->sql_query(null, "distinct pc81_codproc",
                                                  null,
                                                  "pc10_numero = {$this->getCodigoSolicitacao()}"
                                                 );
    $rsProcesso       = $oDaoPCprocitem->sql_record($sSqlProcesso);
    if ($oDaoPCprocitem->numrows > 0) {
      $iNumeroProcesso = db_utils::fieldsMemory($rsProcesso, 0)->pc81_codproc;
    }
    return $iNumeroProcesso;
  }

  /**
   * Cancela o processo de compras gerado pelo processamento da campilação;
   * @return compilacaoRegistroPreco
   */
  public function cancelarProcessoCompras() {


    /**
     * Verificamos se o processo de compras já no foi incluso em uma licitacao
     */
    $oDaoliclicitem   = db_utils::getDao('liclicitem');
    $sSqlVerificaLicitacao  = $oDaoliclicitem->sql_query_proc(null,"distinct l21_codliclicita",
                                                              null,
                                                              "pc10_numero = {$this->getCodigoSolicitacao()}"
                                                             );

    $rsVerificaLicitacao  = $oDaoliclicitem->sql_record($sSqlVerificaLicitacao);
    if ($oDaoliclicitem->numrows > 0) {

      $iLicitacao  = db_utils::fieldsMemory($rsVerificaLicitacao, 0)->l21_codliclicita;
      $sErroMsg    = "Há licitações ({$iLicitacao}) vinculadas ao Processo de compras da compilação.\n";
      $sErroMsg   .= "Não é possível cancelar o processamento da compilação";
      throw new Exception($sErroMsg);

    }
    /**
     * Excluimos os itens do processo de compras;
     */
    $iNumeroProcesso = $this->getProcessodeCompras();
    $oDaopcProcItem  = db_utils::getDao('pcprocitem');
    $oDaopcProcItem->excluir(null, "pc81_codproc = {$iNumeroProcesso}");
    if ($oDaopcProcItem->erro_status == 0) {

      $sErroMsg    = "Não foi possível anular processamento da compilação do registro de preços.\n";
      $sErroMsg   .= "Erro retornado : {$oDaopcProcItem->erro_msg}";
      throw new Exception($sErroMsg);
    }

    /**
     * Excluimos o processo de compras
     */
    $oDaoPcProc = db_utils::getDao('pcproc');
    $oDaoPcProc->excluir($iNumeroProcesso);
    if ($oDaoPcProc->erro_status == 0) {

      $sErroMsg    = "Não foi possível anular processamento da compilação do registro de preços.\n";
      $sErroMsg   .= "Erro retornado : {$oDaoPcProc->erro_msg}";
      throw new Exception($sErroMsg);
    }

    return $this;
  }

  /**
   * retorna os fornecedores que cotaram valores no item
   *
   * @param integer $iCodigoItemSolicitacao código do item da Solicitacao
   * @return array
   */
  function getFornecedoresPorItem($iCodigoItemSolicitacao) {

    $dtDia           = date("Y-m-d", db_getsession("DB_datausu"));
    $sSqlFornecedores  = "SELECT pc22_codorc, ";
    $sSqlFornecedores .= "       pc23_vlrun, ";
    $sSqlFornecedores .= "       pc23_obs, ";
    $sSqlFornecedores .= "       pc22_orcamitem as pc23_orcamitem, ";
    $sSqlFornecedores .= "       z01_nome,       ";
    $sSqlFornecedores .= "       pc21_orcamforne, ";
    $sSqlFornecedores .= "       (select to_char(min(pc66_datainicial),'dd/mm/YYYY')||'-'||to_char(max(pc66_datafinal),'dd/mm/YYYY')";
    $sSqlFornecedores .= "          from registroprecomovimentacaoitens ";
    $sSqlFornecedores .= "               inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
    $sSqlFornecedores .= "           where pc58_situacao    = 1 ";
    $sSqlFornecedores .= "             and pc58_tipo        = 2 ";
    $sSqlFornecedores .= "             and pc66_pcorcamitem = pc22_orcamitem";
    $sSqlFornecedores .= "             and pc66_orcamforne  = pc21_orcamforne";
    $sSqlFornecedores .= "            and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal) as bloqueio,";
    $sSqlFornecedores .= "       coalesce(pc24_pontuacao,0) as pontuacao ";
    $sSqlFornecedores .= "  from solicitaregistropreco ";
    $sSqlFornecedores .= "       inner join solicita       on pc54_solicita    = pc10_numero ";
    $sSqlFornecedores .= "       inner join solicitem      on pc10_numero      = pc11_numero ";
    $sSqlFornecedores .= "       inner join pcprocitem     on pc81_solicitem   = pc11_codigo ";
    $sSqlFornecedores .= "       inner join liclicitem     on pc81_codprocitem = l21_codpcprocitem ";
    $sSqlFornecedores .= "       inner join pcorcamitemlic on pc26_liclicitem  = l21_codigo ";
    $sSqlFornecedores .= "       inner join pcorcamitem    on pc26_orcamitem   = pc22_orcamitem ";
    $sSqlFornecedores .= "       inner join pcorcamforne   on pc21_codorc      = pc22_codorc    ";
    $sSqlFornecedores .= "       left  join pcorcamval     on pc23_orcamitem   = pc26_orcamitem ";
    $sSqlFornecedores .= "                                and pc23_orcamforne  = pc21_orcamforne ";
    $sSqlFornecedores .= "       inner join cgm            on z01_numcgm       = pc21_numcgm     ";
    $sSqlFornecedores .= "       left  join pcorcamjulg    on pc24_orcamforne = pc23_orcamforne  ";
    $sSqlFornecedores .= "                                and pc24_orcamitem  = pc23_orcamitem   ";
    $sSqlFornecedores .= " where pc11_codigo = {$iCodigoItemSolicitacao} ";
    $sSqlFornecedores .= " order by l21_codliclicita";
    $rsFornecedores    = db_query($sSqlFornecedores);
    $aFornecedores     = db_utils::getCollectionByRecord($rsFornecedores, false, false, true);
    return $aFornecedores;

  }

  /**
   * Salva os novos valores para os fornecedores
   *
   * @param  integer $iTipo tipo do movimento  1= Reequilibrio 2-Desistencia - 3
   * @param  Object $oDados objeto com os dados a serem alterados
   * @return unknown
   */
  public function setValoresFornecedores($iTipo, $aDados) {

    /**
     * Incluimos a movimentacao
     */
    $oDaoregistroMov                = db_utils::getDao("registroprecomovimentacao");
    $oDaoregistroMov->pc58_data     = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoregistroMov->pc58_situacao = 1;
    $oDaoregistroMov->pc58_tipo     = $iTipo;
    $oDaoregistroMov->pc58_usuario  = db_getsession("DB_id_usuario");
    $oDaoregistroMov->pc58_solicita = $this->getCodigoSolicitacao();
    $oDaoregistroMov->incluir(null);
    if ($oDaoregistroMov->erro_status == 0) {
       throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoregistroMov->erro_msg}");
    }
    /**
     * Procuramos os dados do valor do orcamento
     */
    $oDaoOrcamval     = db_utils::getDao("pcorcamval");
    $sSqlItemOrcamVal = $oDaoOrcamval->sql_query_file(null,$aDados[0]->iItemOrcamento);
    $rsItemOrcamVal   = $oDaoOrcamval->sql_record($sSqlItemOrcamVal);
    $nQuantidade      = db_utils::fieldsMemory($rsItemOrcamVal, 0)->pc23_quant;


    /**
     * excluimos o valores orçados do item pelo fornecedor
     */
    $sSqlItemOrcamVal = $oDaoOrcamval->excluir(null, $aDados[0]->iItemOrcamento);
    /**
     * Procuramos todos os itens orçados do registro de preco e o desativamos.
     */
    $iCodigoItemSolicitacao = $this->getCodigoItemSolicitacaoPorOrcamento($aDados[0]->iItemOrcamento);
    foreach ($aDados as $oDados) {

      $oDaoRegistroPrecoValor = db_utils::getDao("registroprecovalores");
      $sWhere   = "pc56_orcamitem = {$oDados->iItemOrcamento} and pc56_orcamforne = {$oDados->iItemFornecedor}";

      $sSqlItem = $oDaoRegistroPrecoValor->sql_query_file(null,"*", null,$sWhere);
      $rsItem   = $oDaoRegistroPrecoValor->sql_record($sSqlItem);
      if ($oDaoRegistroPrecoValor->numrows > 0) {

        $aItens = db_utils::getCollectionByRecord($rsItem);
        foreach ($aItens as $oItem) {

           $oDaoRegistroPrecoValor->pc56_sequencial = $oItem->pc56_sequencial;
           $oDaoRegistroPrecoValor->pc56_ativo      = "false";
           $oDaoRegistroPrecoValor->alterar($oItem->pc56_sequencial);
        }
      }


      /**
       * Incluimos os valores novos
       */
      $oDaoOrcamval->pc23_obs        = '';
      $oDaoOrcamval->pc23_orcamforne = $oDados->iItemFornecedor;
      $oDaoOrcamval->pc23_orcamitem  = $oDados->iItemOrcamento;
      $oDaoOrcamval->pc23_quant      = "$nQuantidade";
      $oDaoOrcamval->pc23_vlrun      = $oDados->nValor;
      $oDaoOrcamval->pc23_valor      = $oDados->nValor*$nQuantidade;
      $oDaoOrcamval->incluir($oDados->iItemFornecedor, $oDados->iItemOrcamento);
      if ($oDaoOrcamval->erro_status == 0) {
        throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoOrcamval->erro_msg}");
      }

      /**
       * Incluimos os valores orcados do registro de preco
       */
      $oDaoRegistroPrecoValor->pc56_ativo         = "true";
      $oDaoRegistroPrecoValor->pc56_orcamforne    = $oDados->iItemFornecedor;
      $oDaoRegistroPrecoValor->pc56_orcamitem     = $oDados->iItemOrcamento;
      $oDaoRegistroPrecoValor->pc56_valorunitario = $oDados->nValor;
      $oDaoRegistroPrecoValor->pc56_solicitem     = $iCodigoItemSolicitacao;
      $oDaoRegistroPrecoValor->incluir(null);
      if ($oDaoRegistroPrecoValor->erro_status == 0) {
        throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoRegistroPrecoValor->erro_msg}");
      }

      /**
       * Incluimos o itens movimentados
       */
      $oDaoRegistroMovItens = db_utils::getDao("registroprecomovimentacaoitens");
      $oDaoRegistroMovItens->pc66_datainicial               = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoRegistroMovItens->pc66_datafinal                 = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoRegistroMovItens->pc66_justificativa             = $oDados->sJustificativa;
      $oDaoRegistroMovItens->pc66_pcorcamitem               = $oDados->iItemOrcamento;
      $oDaoRegistroMovItens->pc66_tipomovimentacao          = $oDados->iTipoMovimento;
      $oDaoRegistroMovItens->pc66_orcamforne                = $oDados->iItemFornecedor;
      $oDaoRegistroMovItens->pc66_registroprecomovimentacao = $oDaoregistroMov->pc58_sequencial;
      $oDaoRegistroMovItens->pc66_solicitem                 = $iCodigoItemSolicitacao;
      $oDaoRegistroMovItens->incluir(null);
      if ($oDaoRegistroMovItens->erro_status == 0) {
        throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoRegistroMovItens->erro_msg}");
      }
    }

    return $this;
  }

  /**
   * Verifica os Vencedores do julgamento
   *
   * @param integer $iCodigoOrcamento codigo do orcamento do registro de precos(pcorcam.pc22_codorc)
   * @param null    $iItemJulgar
   * @throws Exception
   * @return compilacaoRegistroPreco
   */
  public function julgarOrcamentoRegistroPreco($iCodigoOrcamento, $iItemJulgar = null) {

    /**
     * O julgamento de uma licitacao de registro de preco, deve sempre ser realizada por itens.
     * nunca havera um registro de preco julgado por lote, ou global
     */
    $dtDia           = date("Y-m-d", db_getsession("DB_datausu"));
    $sSqlJulgamento  = "select distinct on(pcorcamval.pc23_orcamitem) pcorcamval.pc23_orcamitem,";
    $sSqlJulgamento .= "       pc23_orcamforne, ";
    $sSqlJulgamento .= "       pc23_vlrun, ";
    $sSqlJulgamento .= "       pc81_solicitem";
    $sSqlJulgamento .= " from  (SELECT pc23_orcamitem, ";
    $sSqlJulgamento .= "               pc81_solicitem, ";
    $sSqlJulgamento .= "               min(pc23_vlrun) as  minimo ";
    $sSqlJulgamento .= "          From pcorcamval ";
    $sSqlJulgamento .= "               inner join pcorcamitem    on pc22_orcamitem    = pc23_orcamitem ";
    $sSqlJulgamento .= "               inner join pcorcamitemlic on pc22_orcamitem    = pc26_orcamitem ";
    $sSqlJulgamento .= "               inner join liclicitem     on pc26_liclicitem   = l21_codigo";
    $sSqlJulgamento .= "               inner join pcprocitem     on l21_codpcprocitem = pc81_codprocitem ";
    $sSqlJulgamento .= "         where pc22_codorc = {$iCodigoOrcamento} ";
    if (!empty($iItemJulgar)) {
      $sSqlJulgamento .= "         and pc22_orcamitem = {$iItemJulgar} ";
    }
    $sSqlJulgamento .= "           and not exists(select 1 ";
    $sSqlJulgamento .= "                     from registroprecomovimentacaoitens ";
    $sSqlJulgamento .= "                          inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
    $sSqlJulgamento .= "                    where pc58_situacao    = 1 ";
    $sSqlJulgamento .= "                      and pc58_tipo        = 2 ";
    $sSqlJulgamento .= "                      and pc66_pcorcamitem =  pcorcamval.pc23_orcamitem ";
    $sSqlJulgamento .= "                      and pc66_orcamforne  = pcorcamval.pc23_orcamforne ";
    $sSqlJulgamento .= "                      and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal";
    $sSqlJulgamento .= "                     )";
    $sSqlJulgamento .= "         group by pc23_orcamitem, ";
    $sSqlJulgamento .= "                  pc81_solicitem ";
    $sSqlJulgamento .= "       ) as minimo ";
    $sSqlJulgamento .= "         inner join  pcorcamval on minimo.pc23_orcamitem = pcorcamval.pc23_orcamitem ";
    $sSqlJulgamento .= "                               and minimo.minimo = pc23_vlrun ";

    $rsJulgamento   = db_query($sSqlJulgamento);
    if (!$rsJulgamento) {
      throw new Exception("Nao foi possível iniciar julgamento.\nErro ao iniciar julgamento.\nInforme Suporte");
    }
    $iTotalItensJulgados = pg_num_rows($rsJulgamento);
    if ($iTotalItensJulgados == 0) {
      throw new Exception("Nao foi possível iniciar julgamento.\nNão foram encontrados itens para serem julgados.");
    }

    $aItensJulgados = db_utils::getCollectionByRecord($rsJulgamento);

    /**
     * desativamos o julgamento anterior do item do registro,  incluimos um novo julgamento.
     * os julgamentos antigos devem ficar como histórico
     */
    $oDaoRegistroJulgamento = db_utils::getDao("registroprecojulgamento");
    $oDaoOrcamJulgamento    = db_utils::getDao("pcorcamjulg");
    foreach ($aItensJulgados as $oItem) {

      $sSqlDesativaJulgamento = $oDaoRegistroJulgamento->sql_query_file(null,
                                                                        "*",
                                                                        null,
                                                                        "pc65_orcamitem = $oItem->pc23_orcamitem
                                                                        and pc65_ativo is true"
                                                                       );
      $rsDesativaJulgamento = $oDaoRegistroJulgamento->sql_record($sSqlDesativaJulgamento);
      $iTotalItensDesativar = $oDaoRegistroJulgamento->numrows;
      for ($iDisabilitar = 0; $iDisabilitar < $iTotalItensDesativar; $iDisabilitar++) {

        $oItemDesabilitar = db_utils::fieldsMemory($rsDesativaJulgamento, $iDisabilitar);
        /**
         * Desativamos o julgamento do registro de preco
         */
        $oDaoRegistroJulgamento->pc65_sequencial = $oItemDesabilitar->pc65_sequencial;
        $oDaoRegistroJulgamento->pc65_ativo      = "false";
        $oDaoRegistroJulgamento->alterar($oItemDesabilitar->pc65_sequencial);
        if ($oDaoRegistroJulgamento->erro_status == 0) {

          $sErroMsg = "Não foi possível realizar julgamento.\nErro Técnico:{$oDaoRegistroJulgamento->erro_msg}";
          throw new Exception($sErroMsg);
        }
      }
      /**
       * Excluimos o julgamento realizado para a licitacao e o orçamento.
       */
      $oDaoOrcamJulgamento->excluir($oItem->pc23_orcamitem);
      if ($oDaoOrcamJulgamento->erro_status == 0) {

        $sErroMsg = "Não foi possível realizar julgamento.\nErro Técnico:{$oDaoOrcamJulgamento->erro_msg}";
        throw new Exception($sErroMsg);
      }

      /**
       * Incluimos o novo julgamento para o registro de compras
       */
      $oDaoRegistroJulgamento->pc65_ativo         = "true";
      $oDaoRegistroJulgamento->pc65_orcamforne    = $oItem->pc23_orcamforne;
      $oDaoRegistroJulgamento->pc65_orcamitem     = $oItem->pc23_orcamitem;
      $oDaoRegistroJulgamento->pc65_pontuacao     = 1;
      $oDaoRegistroJulgamento->pc65_solicitem     = $oItem->pc81_solicitem;
      $oDaoRegistroJulgamento->pc65_valorunitario = $oItem->pc23_vlrun;
      $oDaoRegistroJulgamento->incluir(null);
      if ($oDaoRegistroJulgamento->erro_status == 0) {

        $sErroMsg = "Não foi possível realizar julgamento.\nErro Técnico:{$oDaoRegistroJulgamento->erro_msg}";
        throw new Exception($sErroMsg);
      }

      /**
       * incluimos o julgamento para a licitacao e o orçamento
       */
      $oDaoOrcamJulgamento->pc24_orcamforne = $oItem->pc23_orcamforne;
      $oDaoOrcamJulgamento->pc24_orcamitem  = $oItem->pc23_orcamitem;
      $oDaoOrcamJulgamento->pc24_pontuacao  = 1;
      $oDaoOrcamJulgamento->incluir($oItem->pc23_orcamitem, $oItem->pc23_orcamforne);
      if ($oDaoOrcamJulgamento->erro_status == 0) {

        $sErroMsg = "Não foi possível realizar julgamento.\nErro Técnico:{$oDaoOrcamJulgamento->erro_msg}";
        throw new Exception($sErroMsg);
      }
    }

    return $this;
  }

  /**
   * Retorna os vencedores do registro de preco
   *
   * @param integer $iOrcamento Código do orcamento
   */
  public function getVencedoresJulgamento($iOrcamento) {

    $sSqlVencedores  = "SELECT pc23_orcamitem as itemorcamento, ";
    $sSqlVencedores .= "       pc23_orcamforne as fornecedororcamento, ";
    $sSqlVencedores .= "       pc23_vlrun as valorunitario, ";
    $sSqlVencedores .= "       pc23_obs as obsorcamento, ";
    $sSqlVencedores .= "       z01_nome as vencedor, ";
    $sSqlVencedores .= "       z01_numcgm as codigocgm, ";
    $sSqlVencedores .= "       pc11_resum as complemento,";
    $sSqlVencedores .= "       pc01_codmater as codigomaterial, ";
    $sSqlVencedores .= "       pc01_descrmater as material";
    $sSqlVencedores .= "  from pcorcamjulg ";
    $sSqlVencedores .= "       inner join pcorcamitem      on pc22_orcamitem   = pc24_orcamitem ";
    $sSqlVencedores .= "       inner join pcorcamval       on pc24_orcamitem   = pc23_orcamitem ";
    $sSqlVencedores .= "                                  and pc24_orcamforne  = pc23_orcamforne ";
    $sSqlVencedores .= "       inner join pcorcamforne     on pc23_orcamforne  = pc21_orcamforne ";
    $sSqlVencedores .= "       inner join cgm              on z01_numcgm       = pc21_numcgm ";
    $sSqlVencedores .= "       inner join pcorcamitemlic   on pc23_orcamitem   = pc26_orcamitem ";
    $sSqlVencedores .= "       inner join liclicitem       on pc26_liclicitem  = l21_codigo ";
    $sSqlVencedores .= "       inner join pcprocitem       on pc81_codprocitem = l21_codpcprocitem ";
    $sSqlVencedores .= "       inner join solicitem        on pc81_solicitem   = pc11_codigo ";
    $sSqlVencedores .= "       inner join solicitempcmater on pc11_codigo      = pc16_solicitem ";
    $sSqlVencedores .= "       inner join pcmater          on pc01_codmater    = pc16_codmater ";
    $sSqlVencedores .= " where pc22_codorc = {$iOrcamento}";
    $sSqlVencedores .= "   and pc24_pontuacao = 1";
    $sSqlVencedores .= " order by pc23_orcamitem ";
    $rsVencedores    = db_query($sSqlVencedores);
    return db_utils::getCollectionByRecord($rsVencedores, false, false, true);

  }

  /**
   * retorna o fornecedor que tem o menor preco do item
   * retorna o um objeto com nome do fornecedor, numero do cgm, e valor unitario
   * @param $iCodigoMaterial
   * @param integer $iCodigoSolicitem
   * @return stdClass
   */
  public function getFornecedorItem($iCodigoMaterial, $iCodigoSolicitem = null) {

    $sSqlVencedores  = "SELECT pc23_vlrun as valorunitario, ";
    $sSqlVencedores .= "       z01_nome as vencedor, ";
    $sSqlVencedores .= "       z01_numcgm as codigocgm, ";
    $sSqlVencedores .= "       pc23_orcamforne as codigofornecedor, ";
    $sSqlVencedores .= "       pc23_obs as obsorcamento, ";
    $sSqlVencedores .= "       pc17_unid    as unidade, ";
    $sSqlVencedores .= "       pc17_quant   as quantidadeunidade,";
    $sSqlVencedores .= "       pc23_percentualdesconto as percentualdesconto";
    $sSqlVencedores .= "  from pcorcamjulg ";
    $sSqlVencedores .= "       inner join pcorcamitem      on pc22_orcamitem   = pc24_orcamitem ";
    $sSqlVencedores .= "       inner join pcorcamval       on pc24_orcamitem   = pc23_orcamitem ";
    $sSqlVencedores .= "                                  and pc24_orcamforne  = pc23_orcamforne ";
    $sSqlVencedores .= "       inner join pcorcamforne     on pc23_orcamforne  = pc21_orcamforne ";
    $sSqlVencedores .= "       inner join cgm              on z01_numcgm       = pc21_numcgm ";
    $sSqlVencedores .= "       inner join pcorcamitemlic   on pc23_orcamitem   = pc26_orcamitem ";
    $sSqlVencedores .= "       inner join liclicitem       on pc26_liclicitem  = l21_codigo ";
    $sSqlVencedores .= "       inner join pcprocitem       on pc81_codprocitem = l21_codpcprocitem ";
    $sSqlVencedores .= "       inner join solicitem        on pc81_solicitem   = pc11_codigo ";
    $sSqlVencedores .= "       inner join solicitemunid    on pc17_codigo      = pc11_codigo ";
    $sSqlVencedores .= "       inner join solicitempcmater on pc11_codigo      = pc16_solicitem ";
    $sSqlVencedores .= "       inner join pcmater          on pc01_codmater    = pc16_codmater ";
    $sSqlVencedores .= " where pc11_numero    = {$this->getCodigoSolicitacao()}";
    $sSqlVencedores .= "   and pc24_pontuacao = 1";
    $sSqlVencedores .= "   and pc01_codmater  = {$iCodigoMaterial}";
    if (!empty($iCodigoSolicitem)) {
      $sSqlVencedores .= "   and pc11_codigo = {$iCodigoSolicitem}";
    }
    $sSqlVencedores .= " order by pc23_orcamitem ";
    $rsVencedores    = db_query($sSqlVencedores);
    return db_utils::fieldsMemory($rsVencedores, 0);
  }

  /**
   * Retorna os fornecedores da Solicitacao
   * @param integer $iOrcamento código do Orcamentos
   * @return array
   */
  public function getFornecedoresPorOrcamento($iOrcamento) {

    $sSqlVencedores  = "SELECT distinct z01_nome as nome, ";
    $sSqlVencedores .= "       z01_numcgm as codigocgm, ";
    $sSqlVencedores .= "       pc21_orcamforne as codigofornecedor ";
    $sSqlVencedores .= "  from pcorcamforne ";
    $sSqlVencedores .= "       inner join cgm              on z01_numcgm       = pc21_numcgm ";
    $sSqlVencedores .= " where pc21_codorc   = {$iOrcamento}";
    $sSqlVencedores .= " order by z01_nome ";
    $rsVencedores    = db_query($sSqlVencedores);
    return db_utils::getCollectionByRecord($rsVencedores, false,false, true);

  }

  /**
   * Salva a Desistência dos Itens
   *
   * @param array   $aItens collection com os itens
   * @param string  $sJustificativa justificativa da desistencia
   * @param integer $iTipoMovimento tipo do movimento
   * @param string  $dtInicial data inicial da desistência formato data (dd/mm/YYYY)
   * @param string  $dtFinal   data final   da desistência formato data (dd/mm/YYYY)
   * @return compilacaoRegistroPreco
   */
  public function salvarDesistencia($aItens, $sJustificativa, $iTipoMovimento, $dtInicial, $dtFinal) {

    $iTotalItensMarcados = 0;
    /**
     * Validamos se existem itens marcados para o fornecedor
     */
    foreach ($aItens as $oFornecedor) {
      $iTotalItensMarcados += count($oFornecedor);
    }

    /**
     * Caso nao existam itens, nao podemos continuar na rotina, e lançamos uma excessão
     */
    if ($iTotalItensMarcados == 0) {
       throw new Exception("Não foram marcados nenhum item para a Desistência.\nProcessamento Cancelado");
    }
    if (empty($sJustificativa)) {
      throw new Exception("Justificativa não informada.");
    }

    if (empty($iTipoMovimento)) {
      throw new Exception("Tipo do movimento não informado.");
    }

    if (empty($dtFinal)) {
      throw new Exception("Data final não informada.");
    }

    if (empty($dtInicial)) {
      throw new Exception("data inicial informada.");
    }

    $sDataInicial = implode("-", array_reverse(explode("/", $dtInicial)));
    $sDataFinal   = implode("-", array_reverse(explode("/", $dtFinal)));

    /**
     * A data final deve ser maior ou igual a data inicial
     */
    if (db_strtotime($sDataInicial) > db_strtotime($sDataFinal)) {
      throw new Exception("data final menor que a data inicial.");
    }

    /**
     * Incluimos a movimentacao
     */
    $oDaoregistroMov                = db_utils::getDao("registroprecomovimentacao");
    $oDaoregistroMov->pc58_data     = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoregistroMov->pc58_situacao = 1;
    $oDaoregistroMov->pc58_tipo     = 2;//Desistencia
    $oDaoregistroMov->pc58_usuario  = db_getsession("DB_id_usuario");
    $oDaoregistroMov->pc58_solicita = $this->getCodigoSolicitacao();
    $oDaoregistroMov->incluir(null);
    if ($oDaoregistroMov->erro_status == 0) {
       throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoregistroMov->erro_msg}");
    }

    /*
     * Percorremos todos os itens e incluimos a desistencia
     */
    foreach ($aItens as $iFornecedor => $iItem) {

      foreach ($iItem as $iCodigoItem) {

        $iCodigoSolicitacao   = $this->getCodigoItemSolicitacaoporOrcamento($iCodigoItem);
        $oDaoRegistroMovItens = db_utils::getDao("registroprecomovimentacaoitens");
        $oDaoRegistroMovItens->pc66_datainicial               = $sDataInicial;
        $oDaoRegistroMovItens->pc66_datafinal                 = $sDataFinal;
        $oDaoRegistroMovItens->pc66_justificativa             = $sJustificativa;
        $oDaoRegistroMovItens->pc66_pcorcamitem               = $iCodigoItem;
        $oDaoRegistroMovItens->pc66_tipomovimentacao          = $iTipoMovimento;
        $oDaoRegistroMovItens->pc66_orcamforne                = $iFornecedor;
        $oDaoRegistroMovItens->pc66_registroprecomovimentacao = $oDaoregistroMov->pc58_sequencial;
        $oDaoRegistroMovItens->pc66_solicitem                 = $iCodigoSolicitacao;
        $oDaoRegistroMovItens->incluir(null);
        if ($oDaoRegistroMovItens->erro_status == 0) {
          throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoRegistroMovItens->erro_msg}");
        }
      }
    }
    return $this;
  }
  /**
   * Realiza o bloqueio dos itens passados por parametro
   *
   * @param array   $aItens collection com os itens
   * @param string  $sJustificativa justificativa da desistencia
   * @param integer $iTipoMovimento tipo do movimento
   * @param string  $dtInicial data inicial da desistência formato data (dd/mm/YYYY)
   * @param string  $dtFinal   data final   da desistência formato data (dd/mm/YYYY)
   * @return compilacaoRegistroPreco
   */
  public function bloquearItens($aItens, $sJustificativa, $iTipoMovimento, $dtInicial, $dtFinal ) {

    /**
     * Caso nao existam itens, nao podemos continuar na rotina, e lançamos uma excessão
     */
    if (count($aItens) == 0) {
       throw new Exception("Não foram marcados nenhum item para o bloqueio.\nProcessamento Cancelado");
    }
    if (empty($sJustificativa)) {
      throw new Exception("Justificativa não informada.");
    }

    if (empty($iTipoMovimento)) {
      throw new Exception("Tipo do movimento não informado.");
    }

    if (empty($dtFinal)) {
      throw new Exception("Data final não informada.");
    }

    if (empty($dtInicial)) {
      throw new Exception("data inicial informada.");
    }

    $sDataInicial = implode("-", array_reverse(explode("/", $dtInicial)));
    $sDataFinal   = implode("-", array_reverse(explode("/", $dtFinal)));

    /**
     * A data final deve ser maior ou igual a data inicial
     */
    if (db_strtotime($sDataInicial) > db_strtotime($sDataFinal)) {
      throw new Exception("data final menor que a data inicial.");
    }

    /**
     *percorremos os itens passados e incluimos a desistencia
     */
  /**
     * Incluimos a movimentacao
     */
    $oDaoregistroMov                = db_utils::getDao("registroprecomovimentacao");
    $oDaoregistroMov->pc58_data     = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoregistroMov->pc58_situacao = 1;
    $oDaoregistroMov->pc58_tipo     = 3;//Bloqueio
    $oDaoregistroMov->pc58_solicita = $this->getCodigoSolicitacao();
    $oDaoregistroMov->pc58_usuario  = db_getsession("DB_id_usuario");
    $oDaoregistroMov->incluir(null);
    if ($oDaoregistroMov->erro_status == 0) {
       throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoregistroMov->erro_msg}");
    }

    /*
     * Percorremos todos os itens e incluimos a desistencia
     */
    foreach ($aItens as $iItem) {

      foreach ($iItem as $iCodigoItem) {

        $iCodigoItemSolicitacao = $this->getCodigoItemSolicitacaoPorOrcamento($iCodigoItem);
        $oDaoRegistroMovItens = db_utils::getDao("registroprecomovimentacaoitens");
        $oDaoRegistroMovItens->pc66_datainicial      = $sDataInicial;
        $oDaoRegistroMovItens->pc66_datafinal        = $sDataFinal;
        $oDaoRegistroMovItens->pc66_justificativa    = $sJustificativa;
        $oDaoRegistroMovItens->pc66_pcorcamitem      = $iCodigoItem;
        $oDaoRegistroMovItens->pc66_tipomovimentacao = $iTipoMovimento;
        $oDaoRegistroMovItens->pc66_orcamforne       = "null";
        $oDaoRegistroMovItens->pc66_solicitem        = $iCodigoItemSolicitacao;
        $oDaoRegistroMovItens->pc66_registroprecomovimentacao = $oDaoregistroMov->pc58_sequencial;
        $oDaoRegistroMovItens->incluir(null);
        if ($oDaoRegistroMovItens->erro_status == 0) {
          throw new Exception("Erro ao salvar Valores!\nErro:{$oDaoRegistroMovItens->erro_msg}");
        }
      }
    }
    return $this;
  }

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
        throw new Exception("Erro ao anular Estimativa de Registro de Preço!\n\n{$oDaoSolicitaAnulada->erro_msg}");
      }
    }
  }

 /**
  * retorna a quantidade solicitada do item.
  *
  * @param unknown_type integer codigo do item
  * @return float codigo do item
  */
  public function getValorSolicitadoItem($iCodigoItem) {

    $sSqlValorItem  = "SELECT coalesce(sum(" . ($this->iFormaControle == aberturaRegistroPreco::CONTROLA_QUANTIDADE ? "pc11_quant" : "pc11_quant*pc11_vlrun") . "), 0) as valor";
    $sSqlValorItem .= " from solicitem ";
    $sSqlValorItem .= " inner join solicitemvinculo on pc11_codigo = pc55_solicitemfilho";
    $sSqlValorItem .= " inner join solicita on pc11_numero = pc10_numero";
    $sSqlValorItem .= " left join solicitaanulada on pc67_solicita = pc10_numero";
    $sSqlValorItem .= " where pc55_solicitempai = {$iCodigoItem} and pc67_solicita is null";
    $rsValorItem    = db_query($sSqlValorItem);

    return db_utils::fieldsMemory($rsValorItem, 0)->valor;
  }

  /**
  * retorna a quantidade empenhada do item.
  *
  * @param  integer codigo do item
  * @return float codigo do item
  */
  public function getValorEmpenhadoItem($iCodigoItem) {

    $sSqlValorItem  = "SELECT coalesce(sum(" . ($this->iFormaControle == aberturaRegistroPreco::CONTROLA_QUANTIDADE ? "e62_quant" : "e62_vltot") . "), 0) as valor";
    $sSqlValorItem .= "  from solicitem ";
    $sSqlValorItem .= "        inner join solicitemvinculo      on pc11_codigo      = pc55_solicitemfilho";
    $sSqlValorItem .= "        inner join pcprocitem            on pc11_codigo      = pc81_solicitem ";
    $sSqlValorItem .= "        inner join empautitempcprocitem  on pc81_codprocitem = e73_pcprocitem";
    $sSqlValorItem .= "        inner join empautitem            on e73_sequen       = e55_sequen";
    $sSqlValorItem .= "                                        and e73_autori       = e55_autori";
    $sSqlValorItem .= "        inner join empautoriza           on e55_autori       = e54_autori";
    $sSqlValorItem .= "        inner join empempaut             on e61_autori       = e54_autori";
    $sSqlValorItem .= "        inner join empempenho            on e61_numemp       = e60_numemp";
    $sSqlValorItem .= "        inner join empempitem            on e60_numemp       = e62_numemp";
    $sSqlValorItem .= "                                        and e62_sequen  = e55_sequen";
    $sSqlValorItem .= "  where pc55_solicitempai = {$iCodigoItem}";
    $sSqlValorItem .= "    and e54_anulad is null ";
    $rsValorItem    = db_query($sSqlValorItem);
    return db_utils::fieldsMemory($rsValorItem, 0)->valor;

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
   * Retorna os registros de Preços que foram gerados a partir da Compilação
   * @return Array com os Solicitações
   */
  public function getRegistrosdePreco() {

    $aRegistroPreco     = array();
    $sSqlRegistroPreco  = "select pc53_solicitafilho ";
    $sSqlRegistroPreco .= "  from solicitavinculo ";
    $sSqlRegistroPreco .= " where pc53_solicitapai = {$this->iCodigoSolicitacao} ";
    $rsRegistroPreco    = db_query($sSqlRegistroPreco);
    $aRegistros         = db_utils::getCollectionByRecord($rsRegistroPreco);

    foreach ($aRegistros as $oDadosRegistroPreco) {

      $oRegistroPreco   = new solicitacaoCompra($oDadosRegistroPreco->pc53_solicitafilho);
      $aRegistroPreco[] = $oRegistroPreco;
    }

    return $aRegistroPreco;
  }

 /* retorna o item da estimativa por codigo de inclusão
  *
  * @param integer $iCodigo Codigo do item
  * @return itemSolicitacao
  */
  public function getItemByCodigo($iCodigo) {

    if (count($this->getItens()) == 0) {
      $this->getItens();
    }
    $oItemRetorno = null;
    foreach ($this->aItens as $oItem) {


      if ($oItem->getCodigoItemSolicitacao() == $iCodigo) {

        $oItemRetorno = $oItem;
        break;
      }
    }
    return $oItemRetorno;
  }

  /**
   * Retorna o Codigo do item, atraves dos dados do orcamento
   *
   * @param integer $iItem código do item do orçamento
   * @return integer
   */
  private function getCodigoItemSolicitacaoPorOrcamento($iItem) {

    $iCodigoItem = null;
    $sSqlCodigo  = "SELECT pc11_codigo";
    $sSqlCodigo .= "  from pcorcamitem";
    $sSqlCodigo .= "       inner join pcorcamitemlic   on pc22_orcamitem   = pc26_orcamitem ";
    $sSqlCodigo .= "       inner join liclicitem       on pc26_liclicitem  = l21_codigo ";
    $sSqlCodigo .= "       inner join pcprocitem       on pc81_codprocitem = l21_codpcprocitem ";
    $sSqlCodigo .= "       inner join solicitem        on pc81_solicitem   = pc11_codigo ";
    $sSqlCodigo .= " where pc22_orcamitem  = {$iItem}";
    $rsCodigo  = db_query($sSqlCodigo);
    if (pg_num_rows($rsCodigo) > 0) {
      $iCodigoItem = db_utils::fieldsMemory($rsCodigo, 0)->pc11_codigo;
    }
    return $iCodigoItem;
  }

  /**
   * retorna os reequilibrios realizados para o registro de preco
   * @return array
   */
  public function getReequilibrios() {

    $aReequilibrios      = array();
    $oDaoRPMovimentacao  = db_utils::getDao('registroprecomovimentacao');
    $sWhereMovimentacao  = "pc58_solicita = {$this->getCodigoSolicitacao()} and pc58_tipo = 1 and pc58_situacao = 1";
    $sSqlRPMovimentacao  = $oDaoRPMovimentacao->sql_query(null, "login, pc58_data", null, $sWhereMovimentacao);
    $rsSqlRPMovimentacao = $oDaoRPMovimentacao->sql_record($sSqlRPMovimentacao);

    if ($oDaoRPMovimentacao->numrows  > 0) {

      for ($i = 0; $i < $oDaoRPMovimentacao->numrows; $i++) {

          $oDadosReequilibrio = db_utils::fieldsMemory($rsSqlRPMovimentacao, $i);
          $oReequilibrio          = new stdClass();
          $oReequilibrio->data    = db_formatar($oDadosReequilibrio->pc58_data, "d");
          $oReequilibrio->usuario = $oDadosReequilibrio->login;

          $aReequilibrios[]  = $oReequilibrio;
      }
    }
    return $aReequilibrios;
  }

  /**
   * Busca a licitação vinculada na compilação. Caso exista.
   * @return bool|licitacao
   */
  public function getLicitacao() {

    $oDaoLicLicitem = new cl_liclicitem();
    $sSqlLicitacao  = $oDaoLicLicitem->sql_query_licitacao_compilacao(
      null,
      "distinct l20_codigo",
      null,
      "solicita.pc10_numero = {$this->iCodigoSolicitacao}"
    );
    $rsBuscaLicitacao = $oDaoLicLicitem->sql_record($sSqlLicitacao);
    if ($oDaoLicLicitem->numrows == 0) {
      return false;
    }
    return new licitacao(db_utils::fieldsMemory($rsBuscaLicitacao, 0)->l20_codigo);
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
