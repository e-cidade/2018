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


require_once(modification("classes/materialestoque.model.php"));

/**
 * controle de  Ordem de compra
 * @author Iuri Guntchnigg
 * @version $Revision: 1.110 $
 * @package material
 */
class ordemCompra {

  /**
   * Codigo da ordem de compra
   *
   * @var integer
   */
  public $iCodOrdem = null;

  /**
   * Dao da Ordem de compra (cl_matordem_classe.php)
   *
   * @var object
   */
  private $daoOrdemCompra = null;

  /**
   * Erro no procedimento
   *
   * @var boolean
   */
  public $lSqlErro = false;

  /**
   * Mensagem de erro
   *
   * @var string
   */
  public $sErroMsg = null;

  /**
   * notas fiscas envolvidas
   *
   * @var array
   */
  private $aNotas = array ();

  /**
   * Codifica strings com urlencode
   *
   * @var boolean
   */
  private $lEncode = false;

  /**
   * define se empenhjo da OC é resto a pagar
   *
   * @var booelan
   */
  private $isRestoPagar = false;

  /**
   * @type EmpenhoFinanceiro
   */
  private $oEmpenhoFinanceiro;

  /**
   * construtor
   *
   * @param  integer $iCodOrdem Codigo da ordem de compra
   * @return void
   */
  function __construct($iCodOrdem) {

    $this->setOrdem((int) $iCodOrdem);
    //instanciamos as classes do db_portal referentes a ordem de compra
    $this->usarDao("matordem");
    $this->daoOrdemCompra = new cl_matordem();
  }

  /**
   * Define o codigo da ordem de compra
   *
   * @param integer $iCodOrdem codigo da ordem de compta (matordem.m51_codord)
   */
  function setOrdem($iCodOrdem) {

    (int) $this->iCodOrdem = (int) $iCodOrdem;
  }

  /**
   * Retorna o codigo da ordem de compra
   *
   * @return integer
   */
  function getOrdem() {

    return $this->iCodOrdem;
  }

  /**
   * habilita a codificação de strings
   *
   */
  function setEncodeON() {

    $this->lEncode = true;
  }
  /**
   * Cancela a codificação de strings
   *
   */
  function setEncodeOff() {

    $this->lEncode = false;
  }

  /**
   * Verifica o tipo do retorno da sstrings
   *
   * @return unknown
   */
  function getEncode() {

    return $this->lEncode;
  }

  /**
   * @desc  método para retornar os dados da ordem. (retorna um objeto db_utils)
   * @return Object db_utils
   *
   */

  function getDados() {

    $sSQLOrdem  = "select * ";
    $sSQLOrdem .= "  from matordem  ";
    $sSQLOrdem .= "      inner join cgm         on  cgm.z01_numcgm          = matordem.m51_numcgm";
    $sSQLOrdem .= "      inner join db_depart   on db_depart.coddepto       = matordem.m51_depto";
    $sSQLOrdem .= "      left  join matordemanu on matordemanu.m53_codordem = matordem.m51_codordem";
    $sSQLOrdem .= " where m51_codordem = " . $this->getOrdem();

    $rsOrdemCompra = $this->daoOrdemCompra->sql_record($sSQLOrdem);
    if ($this->daoOrdemCompra->numrows != 1) {

      $this->sErroMsg = "Não Foi possível consultar dados da Ordem ({$this->iCodOrdem}). ";
      $this->lSqlErro = true;
      return false;

    } else {
      $this->dadosOrdem = db_utils::fieldsMemory($rsOrdemCompra, 0, false, false, $this->getEncode());
      $this->verificarEmpenho();
      $this->dadosOrdem->isRestoPagar = $this->isRestoPagar;
      return true;
    }
  }

  /**
   * verifica a situacao do empenho no ano (restos a pagar)
   * @return boolean;
   */
  function verificarEmpenho() {

    $sSqlEmpenho  = "select e91_anousu,";
    $sSqlEmpenho .= "       e62_numemp ";
    $sSqlEmpenho .= "  from matordemitem ";
    $sSqlEmpenho .= "        inner join empempitem on e62_numemp = m52_numemp ";
    $sSqlEmpenho .= "                             and e62_sequen = m52_sequen ";
    $sSqlEmpenho .= "        inner join empresto   on e62_numemp = e91_numemp ";
    $sSqlEmpenho .= "                             and e91_anousu = " . db_getsession("DB_anousu");
    $sSqlEmpenho .= "  where m52_codordem = {$this->iCodOrdem}";
    $rsEmpenho    = $this->daoOrdemCompra->sql_record($sSqlEmpenho);
    if ($this->daoOrdemCompra->numrows > 0) {
      $this->isRestoPagar = true;
    }
    return $this->isRestoPagar;
  }

  /**
   * @desc Metodo para retornar as notas da ordem;
   * @param integer [$iCodNota  Código da nota]
   * @return mixed
   */

  function getNotasOrdem($iCodNota = null) {

    $this->usarDao("empnota");
    $sWhere = null;
    if ($iCodNota != null) {
      $sWhere = " and e69_codnota = {$iCodNota}";
    }
    /*
     * Verificamos se o empenho é um RP.
     * caso for, o usario podera anular a entrada dessa nota.
     */
    $this->daoEmpNota = new cl_empnota();

    $sCamposNota  = "e69_codnota,e69_anousu,e69_numero,e69_dtnota,e69_dtrecebe,coalesce(e70_valor,0) as e70_valor, ";
    $sCamposNota .= " coalesce(e70_vlrliq,0) as e70_vlrliq ,coalesce(e70_vlranu,0) as e70_vlranu,e60_numemp,";
    $sCamposNota .= "m72_codordem,id_usuario,nome,coalesce(e53_vlrpag,0) as e53_vlrpag";

    $sSqlDadosNota = $this->daoEmpNota->sql_query_nota(null,
                                                       "$sCamposNota",
                                                       "e69_codnota",
                                                       "m72_codordem = {$this->iCodOrdem} {$sWhere}"
                                                      );
    $rsNotasOrdem  = $this->daoEmpNota->sql_record($sSqlDadosNota);

    $this->verificarEmpenho();
    /*
         As notas pode ter as seguintes situacoes (apenas usadas nesse metodo):
         1 = Liquidada - tem algum valor liquidado;
         2 = Anulada   - tem algum valor anulado;
         3 = paga      - tem alguma parte do valro pago;
         4 = normal    - nao possui nenhum valor pago, ou anulado.
         */
    if ($this->daoEmpNota->numrows > 0) {

      for($iNota = 0; $iNota < $this->daoEmpNota->numrows; $iNota ++) {

        $objNotas = db_utils::fieldsMemory($rsNotasOrdem, $iNota, false, false, $this->getEncode());
        for($iFlds = 0; $iFlds < pg_num_fields($rsNotasOrdem); $iFlds ++) {

          $fieldName                                          = pg_field_name($rsNotasOrdem, $iFlds);
          $this->aNotas [$objNotas->e69_codnota] [$fieldName] = $objNotas->$fieldName;

        }
        (int) $iSituacaoNota = 4;
        //verificamos os valores da nota e decidimos a situacao
        if ($objNotas->e53_vlrpag != 0) {
          $iSituacaoNota = 3; //Ha algum valor pago.
        } else if ($objNotas->e70_vlrliq != 0) {
          $iSituacaoNota = 1; // ha algum valor liquidado
        } else if ($objNotas->e70_vlranu != 0) {
          $iSituacaoNota = 2; // ha algum valor anulado
        }
        $this->aNotas [$objNotas->e69_codnota] ["resto"] = $this->isRestoPagar;
        $this->aNotas [$objNotas->e69_codnota] ["situacao"] = $iSituacaoNota;
      }
      return true;
    } else {
      $this->lSqlErro = true;
      $this->sErroMsg = pg_last_error();
      return false;
    }
  }

  /**
   * @desc Metodo para retornar os itens da ordem;
   * @param integer $iCodNota Codigo da nota;
   * @return mixed
   */

  function getItensOrdemEmEstoque($iCodNota) {

    $this->usarDao("matestoqueitemnota");
    $daoItensEstoque = new cl_matestoqueitemnota();

    $sCampos  = "pc01_descrmater,pc01_servico,pc01_fraciona, e60_anousu,e60_codemp,e60_numemp,m52_codlanc,";
    $sCampos .= "m52_vlruni,m71_valor, m71_codlanc,";
    $sCampos .= "e69_codnota,m71_quant, m60_codmater, m60_descr, m75_quant,m71_quantatend,";
    $sCampos .= "m52_valor,m75_quantmult,m75_codmatunid, m71_codmatestoque,m70_quant,m70_valor";

    $sSqlItens = $daoItensEstoque->sql_query_itensunid(null,
                                                       null,
                                                       $sCampos,
                                                       "m71_codlanc",
                                                       "m74_codempnota={$iCodNota}"
                                                       );

    $rsItensEstoque = $daoItensEstoque->sql_record($sSqlItens);
    if ($daoItensEstoque->numrows > 0) {

      for($iItens = 0; $iItens < $daoItensEstoque->numrows; $iItens ++) {

        $oItens = db_utils::fieldsMemory($rsItensEstoque, $iItens, false, false, $this->getEncode());
        if ($oItens->pc01_servico == "t") {
          $oItens->m70_quant = $oItens->m71_quant;
        }
        $this->aItensNota [] = array (

                        "pc01_descrmater" => $oItens->pc01_descrmater,
                        "pc01_fraciona" => $oItens->pc01_fraciona,
                        "pc01_servico" => $oItens->pc01_servico,
                        "e60_numemp" => $oItens->e60_numemp,
                        "e60_codemp" => $oItens->e60_codemp,
                        "e60_anousu" => $oItens->e60_anousu,
                        "m52_vlruni" => $oItens->m52_vlruni,
                        "m60_codmater" => $oItens->m60_codmater,
                        "m60_descr" => $oItens->m60_descr,
                        "m52_valor" => $oItens->m52_valor,
                        "m52_codlanc" => $oItens->m52_codlanc,
                        "m75_quant" => $oItens->m75_quant,
                        "m71_quant" => $oItens->m71_quant,
                        "m71_valor" => $oItens->m71_valor,
                        "m70_quant" => $oItens->m70_quant,
                        "m70_valor" => $oItens->m70_valor,
                        "m75_quantmult" => $oItens->m75_quantmult,
                        "m71_quantatend" => $oItens->m71_quantatend,
                        "m71_codlanc"    => $oItens->m71_codlanc,
                        "m75_codmatunid" => $oItens->m75_codmatunid,
                        "m71_codmatestoque" => $oItens->m71_codmatestoque
        );
      }
      return true;
    }
  }

  /**
   *  @desc   Metodo para converter consulta de ordens para uma string json;
   *  @param  integer iCodNota   - codigo da nota .
   */
  function ordem2Json($iCodNota) {

    $oJson = new services_json();

    $lEmpenhoMaterialPermanente = false;
    $oGrupo = $this->getEmpenhoFinanceiro()->getContaOrcamento()->getGrupoConta();
      if (!empty($oGrupo) && $oGrupo->getCodigo() == 9) {
      $lEmpenhoMaterialPermanente = true;
    }
    $sJson ["lEmpenhoMaterialPermanente"] = $lEmpenhoMaterialPermanente;

    if ($this->getDados()) {

      $sJson ["m51_codordem"] = $this->dadosOrdem->m51_codordem;
      $sJson ["m51_numcgm"]   = $this->dadosOrdem->m51_numcgm;
      $sJson ["z01_nome"]     = $this->dadosOrdem->z01_nome;
      $sJson ["m51_tipo"]     = $this->dadosOrdem->m51_tipo;
      $sJson ["totalItens"]   = 0;
      $sJson ["itens"]        = array (

      );
      if ($this->getNotasOrdem($iCodNota)) {

        $sJson ["e69_codnota"]  = $this->aNotas [$iCodNota] ["e69_codnota"];
        $sJson ["e69_numero"]   = $this->aNotas [$iCodNota] ["e69_numero"];
        $sJson ["e69_dtnota"]   = db_formatar($this->aNotas [$iCodNota] ["e69_dtnota"], "d");
        $sJson ["e69_dtrecebe"] = db_formatar($this->aNotas [$iCodNota] ["e69_dtrecebe"], "d");
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["e70_valor"]    = $this->aNotas [$iCodNota] ["e70_valor"];
        $sJson ["id_usuario"]   = $this->aNotas [$iCodNota] ["id_usuario"];
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["nome"]         = $this->aNotas [$iCodNota] ["nome"];
        if ($this->getItensOrdemEmEstoque($iCodNota)) {

          $sJson ["totalItens"] = count($this->aItensNota);
          $sJson ["itens"]      = $this->aItensNota;
        }
      }
    }
    if (! $this->lSqlErro) {

      $sJson ["status"]   = 1;
      $sJson ["mensagem"] = null;
    } else {

      $sJson ["status"]   = 2;
      $sJson ["mensagem"] = "Erro: " . urlencode($this->sErroMsg);
    }
    $jsonEncoded = $oJson->encode($sJson);
    return $jsonEncoded;
  }

  /**
   * Método responsável por anular a entrada de uma ordem de compra no almoxarifado
   * @param integer  iCodNota - codigo da nota a ser anulada
   *
   */
  public function anularEntradaNota($iCodNota) {

    $this->sErroMsg = '';

    $clmatestoque         = new cl_matestoque();
    $clmatestoqueini      = new cl_matestoqueini();
    $clmatestoqueinil     = new cl_matestoqueinil();
    $clmatestoqueinill    = new cl_matestoqueinill();
    $clmatestoqueinimei   = new cl_matestoqueinimei();
    $clmatestoqueitem     = new cl_matestoqueitem();
    $clmatestoqueitemoc   = new cl_matestoqueitemoc();
    $clmatestoqueitemnota = new cl_matestoqueitemnota();
    $clmatestoqueitemunid = new cl_matestoqueitemunid();

    try {

      db_inicio_transacao();

      $this->getItensOrdemEmEstoque($iCodNota);
      $this->getDados();

      if ($this->getBensAtivoNota($iCodNota) != false) {
        throw new BusinessException("Há bens referentes à nota de empenho ativos no patrimônio. Favor verificar.");
      }

      $oDataAtual = new DBDate( date('Y-m-d', db_getsession("DB_datausu")) );
      $oTipoMovimentacao = new TipoMovimentacaoEstoque(TipoMovimentacaoEstoque::CODIGO_ANULACAO_ENTRADA_ORDEM_COMPRA);

      $oEstoqueMovimentoAnulacao = new MaterialEstoqueMovimentacao();
      $oEstoqueMovimentoAnulacao->setData( $oDataAtual );
      $oEstoqueMovimentoAnulacao->setHora( date('H:i:s') );
      $oEstoqueMovimentoAnulacao->setCodigoDepartamento( db_getsession("DB_coddepto") );
      $oEstoqueMovimentoAnulacao->setCodigoUsuario( db_getsession("DB_id_usuario") );
      $oEstoqueMovimentoAnulacao->setMovimento( $oTipoMovimentacao );
      $oEstoqueMovimentoAnulacao->setObservacao("Anulação da entrada de material.");
      $oEstoqueMovimentoAnulacao->salvar();

      $aItensVerificar   = array();
      $aEmpenhoGrupoItem = array();
      $aItensLancamento  = array();
      $nTotalNota = 0;

      for($iItens = 0; $iItens < count($this->aItensNota); $iItens ++) {

        $oItemAtivo = $this->aItensNota[$iItens];

        $oEstoqueItem = new MaterialEstoqueItem($oItemAtivo["m71_codlanc"]);
        $oEstoque     = $oEstoqueItem->getEstoque();
        $oMaterial    = $oEstoque->getMaterial();

        if ($oEstoqueItem->servico() && $oEstoqueItem->getQuantidade() == $oEstoqueItem->getQuantidadeAtendida()) {

          $oMaterialEstoque  = new materialEstoque($oMaterial->getCodigo());
          $sSqlMatestoqueini = $clmatestoqueini->sql_query_mater(null,
                                                                 "*",
                                                                 null,
                                                                 "    m82_matestoqueitem={$oEstoqueItem->getCodigo()}
                                                                  and matestoqueini.m80_codtipo=20
                                                                  and (b.m80_codtipo<>6
                                                                   or b.m80_codigo is null) ");

          $rsSaldoItens = $clmatestoqueinimei->sql_record($sSqlMatestoqueini);
          if ($clmatestoqueinimei->numrows  > 0) {

            $oSaidaMaterial = db_utils::fieldsMemory($rsSaldoItens, 0);
            try {

              $oMaterialEstoque->cancelarSaidaMaterial( $oEstoqueItem->getQuantidade(),
                                                        $oSaidaMaterial->m82_codigo,
                                                        'Anulação de saída de serviço automático' );
              $oEstoqueItem->setQuantidadeAtendida(0);

            } catch (Exception $eErro) {
              throw new Exception($eErro->getMessage());
            }
          }
        }

        $nTotalNota += $oEstoqueItem->getValor();

        /**
         * Anula o movimento de entrada de ordem de compra
         */
        $aMovimentacoes = $oEstoqueItem->getMovimentacoes();
        foreach ($aMovimentacoes as $oMovimentacao) {

          if ($oMovimentacao->getMovimento()->getCodigo() == TipoMovimentacaoEstoque::CODIGO_ENTRADA_ORDEM_COMPRA) {

            $oMovimentacao->anularMovimentacao($oEstoqueMovimentoAnulacao);
            break;
          }
        }

        /**
         * Agrupa os itens da nota por material caso tenha que fazer o ratio das quantidades em outras entradas
         */
        if (!isset($aItensVerificar[$oMaterial->getCodigo()])) {

          $aItensVerificar[$oMaterial->getCodigo()]->iCodigoItem       = $oMaterial->getCodigo();
          $aItensVerificar[$oMaterial->getCodigo()]->nSaldoItemEstoque = $oEstoqueItem->getQuantidade();
          $aItensVerificar[$oMaterial->getCodigo()]->nSaldoAtendido    = $oEstoqueItem->getQuantidadeAtendida();
          $aItensVerificar[$oMaterial->getCodigo()]->nSaldoABater      = 0;
          $aItensVerificar[$oMaterial->getCodigo()]->iNumemp           = $oItemAtivo["e60_numemp"];
          $aItensVerificar[$oMaterial->getCodigo()]->nValorTotal       = $oEstoque->getValor();
        } else {

          $aItensVerificar[$oMaterial->getCodigo()]->nSaldoItemEstoque += $oEstoqueItem->getQuantidade();
          $aItensVerificar[$oMaterial->getCodigo()]->nSaldoAtendido    += $oEstoqueItem->getQuantidadeAtendida();
        }

        $iQuantidadeAnular = $oEstoqueItem->getQuantidade();

        /**
         * Caso o item já tenha a quantidade total atendida, busca o próximo item mais antigo do material para lançar a anulação
         */
        if ($oEstoqueItem->getQuantidade() == $oEstoqueItem->getQuantidadeAtendida()) {
          $oEstoqueItem = $oEstoque->getItemComSaldo();

          if (empty($oEstoqueItem)) {
            throw new Exception("Nao foi possivel cancelar ordem.\nEstoque sem saldo.");
          }
        }

        $iSaldoItem   = $oEstoqueItem->getQuantidade() - $oEstoqueItem->getQuantidadeAtendida();
        $iValorAnular = ($iSaldoItem > $iQuantidadeAnular ? $iQuantidadeAnular : $iSaldoItem);
        $oEstoqueItem->setQuantidadeAtendida( $iValorAnular + $oEstoqueItem->getQuantidadeAtendida() );
        $oEstoqueItem->salvar();

        $iCodigoMatEstoqueIniMei = MaterialEstoqueItem::vincularMovimentacaoComItem($oEstoqueItem, $oEstoqueMovimentoAnulacao, $iValorAnular);

        $aItensVerificar[$oMaterial->getCodigo()]->nSaldoABater += $iValorAnular;

        /**
         * Anula o vinculo da entrada com o estoque
         */
        $sSqlOrdemCompra = $clmatestoqueitemoc->sql_query_OC_Nota( null,
                                                                   null,
                                                                   "m73_codmatestoqueitem,m73_codmatordemitem",
                                                                   null,
                                                                   "m52_codordem={$this->iCodOrdem} and m74_codempnota = {$iCodNota}" );
        $rsOc = $clmatestoqueitemoc->sql_record($sSqlOrdemCompra);

        $iNumRows = $clmatestoqueitemoc->numrows;
        for($iTot = 0; $iTot < $iNumRows; $iTot ++) {

          $oItemOC                                   = db_utils::fieldsMemory($rsOc, $iTot);
          $clmatestoqueitemoc->m73_codmatordemitem   = $oItemOC->m73_codmatordemitem;
          $clmatestoqueitemoc->m73_codmatestoqueitem = $oItemOC->m73_codmatestoqueitem;
          $clmatestoqueitemoc->m73_cancelado         = "true";
          $clmatestoqueitemoc->alterar($oItemOC->m73_codmatestoqueitem, $oItemOC->m73_codmatordemitem);
          if ($clmatestoqueitemoc->erro_status == 0) {
            throw new Exception($clmatestoqueitemoc->erro_msg);
          }
        }

        $clmatestoqueitemnota->excluir(null, null, "m74_codempnota=$iCodNota");

        if ($clmatestoqueitemnota->erro_status == 0) {
          throw new Exception($clmatestoqueitemnota->erro_msg);
        }

        if (USE_PCASP) {

          $oEmpenhoFinanceiro      = new EmpenhoFinanceiro($oItemAtivo['e60_numemp']);
          $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();
          $iCodigoDesdobramento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
          $oGrupoContaOrcamento    = GrupoContaOrcamento::getGrupoConta($iCodigoDesdobramento, db_getsession("DB_anousu"));
          $iGrupoContaOrcamento = "";
          if ($oGrupoContaOrcamento) {
            $iGrupoContaOrcamento  = $oGrupoContaOrcamento->getCodigo();
          }

          if (in_array($iGrupoContaOrcamento, array(7, 8, 10)) && !empty($iCodigoMatEstoqueIniMei)) {
            $aItensLancamento[$oMaterial->getCodigo()][] = $iCodigoMatEstoqueIniMei;
          }
        }
      }

      if ($this->dadosOrdem->m51_tipo == OrdemDeCompra::TIPO_NORMAL) {

        $clempnotaele = $this->usarDao("empnotaele", true);
        $clempnotaele->e70_codnota = $iCodNota;
        $clempnotaele->e70_vlranu = $nTotalNota;
        $clempnotaele->alterar($iCodNota);
        if ($clempnotaele->erro_status == 0) {
          throw new Exception($clempnotaele->erro_msg);
        }
      }

      /**
       * Acerta os saldos dos itens
       */
      foreach ($aItensVerificar as $oItemVerificar) {

        $nDiferenca = $oItemVerificar->nSaldoItemEstoque - $oItemVerificar->nSaldoABater;

        if ($nDiferenca > 0) {

          $sSql          = "select * ";
          $sSql         .= " from matestoqueitem ";
          $sSql         .= "      inner join matestoque on m71_codmatestoque = m70_codigo ";
          $sSql         .= " where m70_coddepto    = ".db_getsession("DB_coddepto");
          $sSql         .= "   and m70_codmatmater = {$oItemVerificar->iCodigoItem}";
          $sSql         .= "   and m71_quant > m71_quantatend";
          $rsSaldoItens  = db_query($sSql);

          $aItensSaldo   = db_utils::getCollectionByRecord($rsSaldoItens);
          $nValorRateio  = $nDiferenca;
          foreach ($aItensSaldo as $oItem) {

            if ($nValorRateio > 0) {

              $nSaldoItem = $oItem->m71_quant - $oItem->m71_quantatend;

              if ($nValorRateio > $nSaldoItem) {

                $nValorAbater  = $nSaldoItem;
                $nValorRateio -= $nSaldoItem;

              } else {

                $nValorAbater = $nValorRateio;
                $nValorRateio = 0;
              }

              $clmatestoqueitem->m71_quantatend = $oItem->m71_quantatend+$nValorAbater;
              $clmatestoqueitem->m71_codlanc    = $oItem->m71_codlanc;
              $clmatestoqueitem->alterar($oItem->m71_codlanc);
              if ($clmatestoqueitem->erro_status == 0) {
                 throw new Exception($clmatestoqueitem->erro_msg);
              }

              $clmatestoqueinimei->m82_matestoqueini  = $oEstoqueMovimentoAnulacao->getCodigo();
              $clmatestoqueinimei->m82_matestoqueitem = $oItem->m71_codlanc;
              $clmatestoqueinimei->m82_quant          = $nValorAbater;
              $clmatestoqueinimei->incluir(null);
              if ($clmatestoqueinimei->erro_status == 0) {
                throw new Exception($clmatestoqueinimei->erro_msg);
              }

              $aItensLancamento[$oItemVerificar->iCodigoItem][] = $clmatestoqueinimei->m82_codigo;
            }
          }

          if (round($nValorRateio,2) > 0) {
             throw new BusinessException("Nao foi possivel cancelar ordem.\nSem Saldo no estoque $nValorRateio");
          }

        }
      }

      foreach ($aItensLancamento as $iCodigoMaterial => $aMatestoqueinimei) {

        $sMatestoqueinimei    = implode(',', $aMatestoqueinimei);
        $oDaoMatestoqueinimei = new cl_matestoqueinimei();
        $sWhere               = "m82_codigo in($sMatestoqueinimei)";
        $sSql                 = $oDaoMatestoqueinimei->sql_query_matestoqueitem(null, 'sum(m71_valor) as valor', null, $sWhere);
        $rsMatestoqueinimei   = $oDaoMatestoqueinimei->sql_record($sSql);

        if ($oDaoMatestoqueinimei->numrows == 0) {
          throw new BusinessException('Não foi possivel encontrar o valor financeiro para o material.');
        }

        $nValor = db_utils::fieldsMemory($rsMatestoqueinimei, 0)->valor;
        $oMaterialEstoque = new materialEstoque($iCodigoMaterial);
        $oGrupoMaterial   = $oMaterialEstoque->getGrupo();

        $oItem = new stdClass;
        $oItem->iCodigoDaNota    = $iCodNota;
        $oItem->nValorLancamento = $nValor;

        $aEmpenhoGrupoItem[$this->aItensNota[0]['e60_numemp']][$oGrupoMaterial->getCodigo()][] = $oItem;
      }

      $dtAtual          = date("Y-m-d", db_getsession( 'DB_datausu' ));
      $oDataImplantacao = new DBDate($dtAtual);
      $oInstituicao     = new Instituicao(db_getsession('DB_instit'));

      if (USE_PCASP && count($aEmpenhoGrupoItem) > 0  && (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao)) ) {
        $this->processarLancamentosOrdemCompra($aEmpenhoGrupoItem, true);
      }

      db_fim_transacao(false);

    } catch (BusinessException $eErro) {

      db_fim_transacao(true);
      throw new BusinessException($eErro->getMessage());

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      throw new Exception($eErro->getMessage());

    }

  }


  /**
   * @desc Metodo para retornar o saldo dos itens da ordem de compra.
   * @return   mixed ;
   */
  function getItensSaldo() {

    $sSQlSaldo = "select riseqitem     as e62_sequen,";
    $sSQlSaldo .= "       rinumemp     as e62_numemp,";
    $sSQlSaldo .= "       ricodemp     as e60_codemp,";
    $sSQlSaldo .= "       ricodmater   as e62_item,";
    $sSQlSaldo .= "       ricoditem    as e62_sequencial,";
    $sSQlSaldo .= "       rnvaloruni   as e62_vlun,";
    $sSQlSaldo .= "       rsdescr      as pc01_descrmater,";
    $sSQlSaldo .= "       rsdescremp   as e62_descr,";
    $sSQlSaldo .= "       rnsaldoordem as saldoitens,";
    $sSQlSaldo .= "       rnvalorordem as saldovalor,";
    $sSQlSaldo .= "       rianoemp     as e60_anousu,";
    $sSQlSaldo .= "       m52_quant,";
    $sSQlSaldo .= "       pc01_servico,";
    $sSQlSaldo .= "       pc01_fraciona,";
    $sSQlSaldo .= "       m52_valor,";
    $sSQlSaldo .= "       m52_vlruni,";
    $sSQlSaldo .= "       m52_codlanc,";
    $sSQlSaldo .= "       rlcontrolaquantidade as lcontrolaquantidade";
    $sSQlSaldo .= "  from fc_saldoitensordem({$this->iCodOrdem})";
    $sSQlSaldo .= "       inner join  matordemitem on ricoditemordem = m52_codlanc";
    $sSQlSaldo .= "       inner join  pcmater on ricodmater          = pc01_codmater";
    $sSQlSaldo .= " where m52_codordem = {$this->iCodOrdem}";
    $sSQlSaldo .= " order by rinumemp, riseqitem";
    $rsSaldo = $this->daoOrdemCompra->sql_record($sSQlSaldo);
    if ($rsSaldo) {
      //criamos uma colection com os objetos dos itens da ordem de compra (saldos)
      if ($this->daoOrdemCompra->numrows > 0) {

        for($iLinha = 0; $iLinha < $this->daoOrdemCompra->numrows; $iLinha ++) {
          $this->aItensOrdem [] = db_utils::fieldsMemory($rsSaldo, $iLinha, false, false, $this->getEncode());
        }
      }
      $this->dadosOrdem->itens = $this->aItensOrdem;
      return true;
    } else {
      return false;
    }
  }

  /**
   * @desc Metodo para anular itens da ordem de compra.
   * @param  array $aItens array de itens que devem ser anulados - {[CodItemOrdem, CodItemEmp, Qtdem ,Valor]}
   * @param  integer $lSolicitaAnulEmpenho se deve gerar uma solicitacao de anulacao de empenho - 0 = nao solicita,
   *                                                                                            1 = Anulacao de Item
   *                                                                                            2 = Anulacao de valores
   * @return   void;
   */
  function anularOrdem($aItens, $sMotivo = '', $iSolicitaAnulEmpenho = 0) {

    if (! is_array($aItens)) {

      $this->lSqlErro = true;
      $this->sErroMsg = "Erro [1]: Parametro aItens não e um array valido!\nContate Suporte";
      return false;
    }
    //carregamos as daos necessarias
    $this->usarDao("matordemanul");
    $this->usarDao("matordemitemanu");
    $this->usarDao("empsolicitaanul");
    $this->usarDao("empsolicitaanulitem");
    $this->lSqlErro = false;
    $this->sErroMsg = null;
    $iNumEmpAnt = null;
    $clmatordemanul = new cl_matordemanul();
    $clmatordemitemanu = new cl_matordemitemanu();
    $clempsolicitaanul = new cl_empsolicitaanul();
    $clempsolicitaanulitem = new cl_empsolicitaanulitem();
    //incluimos a anulação da ordem na tablea matordemanul
    //percorremos os itens do array
    db_inicio_transacao();
    $clmatordemanul->m37_hora = db_hora();
    $clmatordemanul->m37_data = date("Y-m-d", db_getsession("DB_datausu"));
    $clmatordemanul->m37_usuario = db_getsession("DB_id_usuario");
    $clmatordemanul->m37_motivo = $sMotivo;
    $clmatordemanul->m37_empanul = "$iSolicitaAnulEmpenho";
    $clmatordemanul->m37_tipo = 2; //anulacao parcial;
    $clmatordemanul->incluir(null);
    if ($clmatordemanul->erro_status == 0) {

      $this->lSqlErro = true;
      $this->sErroMsg = "Erro[2]: \n{$clmatordemanul->erro_msg}";
    }

    //foi solicitado a anulacao do empenho.incluimos a requisicao na tabela empsolicitaanul.
    if (! $this->lSqlErro) {

      foreach ( $aItens as $itensAnulados ) {

        $clmatordemitemanu->m36_matordemanul = $clmatordemanul->m37_sequencial;
        $clmatordemitemanu->m36_matordemitem = $itensAnulados->iCodItemOrdem;
        $clmatordemitemanu->m36_vrlanu = $itensAnulados->nVlrAnu;
        $clmatordemitemanu->m36_qtd = $itensAnulados->nQtdeAnu;
        $clmatordemitemanu->incluir(null);
        if ($clmatordemitemanu->erro_status == 0) {

          $this->lSqlErro = true;
          $this->sErroMsg = "Erro [4]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
          $this->sErroMsg .= "\nErro Sistema:{$clmatordemitemanu->erro_msg}";
          return false;
        }
        //caso tenha foi solicitado a anulacao do empenho, lancamos mas seguintes tabelas
        if ($iSolicitaAnulEmpenho != 0 && ! $this->lSqlErro) {

          if ($iNumEmpAnt != $itensAnulados->iNumEmp) {

            $clempsolicitaanul->e35_numemp = $itensAnulados->iNumEmp;
            $clempsolicitaanul->e35_usuario = db_getsession("DB_id_usuario");
            $clempsolicitaanul->e35_hora = db_hora();
            $clempsolicitaanul->e35_data = date("Y-m-d", db_getSession("DB_datausu"));
            $clempsolicitaanul->e35_tipo = $iSolicitaAnulEmpenho;
            $clempsolicitaanul->e35_situacao = 1; //1-Solicitada 2 -Realizada 3 - Cancelada
            $clempsolicitaanul->incluir(null);
            if ($clempsolicitaanul->erro_status == 0) {

              $this->lSqlErro = true;
              $this->sErroMsg = "Erro [5]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
              $this->sErroMsg .= "\nErro Sistema:{$clempsolicitaanul->erro_msg}";
              return false;
            }
          }
          $clempsolicitaanulitem->e36_empempitem = $itensAnulados->iCodItem;
          $clempsolicitaanulitem->e36_empsolicitaanul = $clempsolicitaanul->e35_sequencial;
          $clempsolicitaanulitem->e36_vrlanu = $itensAnulados->nVlrAnu;
          $clempsolicitaanulitem->e36_qtdanu = $itensAnulados->nQtdeAnu;
          $clempsolicitaanulitem->incluir(null);
          if ($clempsolicitaanulitem->erro_status == 0) {

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro [6]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
            $this->sErroMsg .= "\nErro Sistema:{$clempsolicitaanulitem->erro_msg}";
            return false;
          }
        }
        $iNumEmpAnt = $itensAnulados->iNumEmp;
      }
    }
    db_fim_transacao($this->lSqlErro);
  }

  /**
   * @desc Metodo para carregar o arquivo de definição da classe requerida;
   * @param  string sClasse - nome da classe a ser carregada
   */
  function usarDao($sClasse, $rInstance = false) {

    if (! class_exists("cl_{$sClasse}")) {
      require_once modification("classes/db_{$sClasse}_classe.php");
    }

    if ($rInstance) {

      eval("\$objRet = new cl_{$sClasse};");
      return $objRet;
    }
  }

  /**
   * Retorna informações da ordem de compra para dar entrada no estoque
   *
   * @return object
   */
  public function getInfoEntrada() {

    if ( ! $this->getDados()) {
      throw new Exception("Não foi possível Encontrar dados da ordem ({$this->iCodOrdem}).");
    }

      /*
       * trazemos items da ordem, com seus saldos,
       * e acrescentamos informações sobre o item, com suas
       * ligações com o item do almoxarifado.
       */
    if ( ! $this->getItensSaldo()) {
      throw new Exception("Não foi possível Encontrar itens da ordem ({$this->iCodOrdem}).");
    }

    if ($this->dadosOrdem->m51_tipo == 2) {

      $oDaoEmpNota = db_utils::getDao("empnota");
      $rsNota      = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null,
                                                                           "empnota.*,
                                                                            e70_valor",
                                                                            null,
                                                                            "m72_codordem = {$this->dadosOrdem->m51_codordem}"));
      $oEmpNota = db_utils::fieldsMemory($rsNota, 0,false,false, $this->getEncode());
      $this->dadosOrdem->e69_dtnota   = db_formatar($oEmpNota->e69_dtnota,"d");
      $this->dadosOrdem->e69_dtrecebe = db_formatar($oEmpNota->e69_dtrecebe,"d");
      $this->dadosOrdem->e69_numero   = $oEmpNota->e69_numero;
      $this->dadosOrdem->e70_valor    = $oEmpNota->e70_valor;

    }
    $oDaoSolicitem  = db_utils::getDao("solicitem");
    $oDaoTransMater = db_utils::getDao("transmater");
    $iTotItens = count($this->aItensOrdem);
    for ($iInd = 0; $iInd < $iTotItens; $iInd++) {

      $this->dadosOrdem->itens[$iInd]->unidade        = 1;
      $this->dadosOrdem->itens[$iInd]->quantunidade   = 1;
      $this->dadosOrdem->itens[$iInd]->iIndiceEntrada = 0;
      /*
       * Buscamos os dados da solicitação para ver se o item possui informações
       * de unidade cadastradas.
       */
      $rsSolicitem = $oDaoSolicitem->sql_record($oDaoSolicitem->sql_query_solunid(
                               null, "pc17_quant,pc17_unid", null, 'e62_sequencial = '.$this->dadosOrdem->itens[$iInd]->e62_sequencial));

      if ($oDaoSolicitem->numrows == 1) {

         $this->dadosOrdem->itens[$iInd]->unidade      = db_utils::fieldsMemory($rsSolicitem,0)->pc17_unid;
         $this->dadosOrdem->itens[$iInd]->quantunidade = db_utils::fieldsMemory($rsSolicitem,0)->pc17_quant;
      }

      /*
       * Buscamos todos os itens vinculados ao material do compras
       * no cadastro de materias do almoxarifados, para o usuário escolher um .
       */
      $this->dadosOrdem->itens[$iInd]->matmater = array();
      $this->dadosOrdem->itens[$iInd]->matmater[0] = new stdClass;
      $this->dadosOrdem->itens[$iInd]->matmater[0]->m63_codmatmater = "";
      $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr       = "";
      //echo $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr;

      $sSqlTransMater = $oDaoTransMater->sql_query(null,
                                                   "m63_codmatmater,m60_descr,m60_controlavalidade",
                                                    null,
                                                    "m60_ativo is true
                                                    and m63_codpcmater={$this->dadosOrdem->itens[$iInd]->e62_item}");
      $rsTransMater = $oDaoTransMater->sql_record($sSqlTransMater);
      if ($oDaoTransMater->numrows > 0) {

        unset($this->dadosOrdem->itens[$iInd]->matmater);
        for($iItens = 0; $iItens < $oDaoTransMater->numrows; $iItens++) {
          $this->dadosOrdem->itens[$iInd]->matmater[] = db_utils::fieldsMemory($rsTransMater, $iItens,false,false,$this->getEncode());
        }
        $oDaoTransMater->numrows = 0;
      }

      $oMaterial = new stdClass;
      $oMaterial->m63_codmatmater   = $this->dadosOrdem->itens[$iInd]->matmater[0]->m63_codmatmater;
      $oMaterial->m60_descr         = $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr;
      $oMaterial->pc01_descrmater   = $this->dadosOrdem->itens[$iInd]->pc01_descrmater;
      $oMaterial->pc01_codmater     = $this->dadosOrdem->itens[$iInd]->e62_item;
      $oMaterial->e62_descr         = $this->dadosOrdem->itens[$iInd]->e62_descr;
      $oMaterial->e62_vlun          = $this->dadosOrdem->itens[$iInd]->e62_vlun;
      $oMaterial->e62_sequencial    = $this->dadosOrdem->itens[$iInd]->e62_sequencial;
      $oMaterial->e60_codemp        = $this->dadosOrdem->itens[$iInd]->e60_codemp;
      $oMaterial->e60_numemp        = $this->dadosOrdem->itens[$iInd]->e62_numemp;
      $oMaterial->e60_anousu        = $this->dadosOrdem->itens[$iInd]->e60_anousu;
      $oMaterial->unidade           = $this->dadosOrdem->itens[$iInd]->unidade;
      $oMaterial->quantunidade      = $this->dadosOrdem->itens[$iInd]->quantunidade;
      $oMaterial->m52_quant         = $this->dadosOrdem->itens[$iInd]->saldoitens;
      $oMaterial->m52_valor         = $this->dadosOrdem->itens[$iInd]->saldovalor;
      $oMaterial->m52_vlruni        = $this->dadosOrdem->itens[$iInd]->m52_vlruni;
      $oMaterial->m52_codlanc       = $this->dadosOrdem->itens[$iInd]->m52_codlanc;
      $oMaterial->m77_lote          = "";
      $oMaterial->pc01_servico      = $this->dadosOrdem->itens[$iInd]->pc01_servico;
      $oMaterial->m77_dtvalidade    = "";
      $oMaterial->m78_matfabricante = "";
      $oMaterial->m76_nome          = "";
      $oMaterial->checked           = "checked";
      $oMaterial->saldoitens        = $this->dadosOrdem->itens[$iInd]->saldoitens;
      $oMaterial->saldovalor        = $this->dadosOrdem->itens[$iInd]->saldovalor;
      $oMaterial->fraciona          = false; //se o o item é fracionado.
      $oMaterial->iTotalFracionados = 0;//Total de itens Fracionados
      $oMaterial->iIndiceEntrada    = 0;
      $oMaterial->cc08_sequencial   = "";
      $oMaterial->cc08_descricao    = "";
      $this->saveMaterial($this->dadosOrdem->itens[$iInd]->m52_codlanc,$oMaterial);
      unset ($oMaterial);

    }
    return true;
  }
  /**
   * Inicializa a sessão para a ordem
   *
   */
  public function initSession() {

    if (!isset($_SESSION["matordem{$this->iCodOrdem}"])) {

      $_SESSION["matordem{$this->iCodOrdem}"]= array();
    }
    return $_SESSION["matordem{$this->iCodOrdem}"];
  }

  /**
   * Salva as modificações da entrada na sessao
   *
   * @param integer $iCodLanc codigo do item da ordem de compra
   * @param  object $oMaterial objeto com informações da entrada
   * @return unknown
   */
  public function saveMaterial($iCodLanc, $oMaterial) {

    $oOrdemSession = $this->initSession();
    if (!isset($oOrdemSession[$iCodLanc])) {

      $oOrdemSession[$iCodLanc] = array();
    }

    //verificamos se o material do estoque ja foi incluido.
    foreach ($oOrdemSession[$iCodLanc] as $oLancamento) {

      if ($oMaterial->iIndiceEntrada != $oLancamento->iIndiceEntrada) {
        if ($oLancamento->m63_codmatmater ==  $oMaterial->m63_codmatmater
           && $oLancamento->m77_lote == $oMaterial->m77_lote) {
          throw  new Exception("Material/lote já cadastrado.");
        }
      }
    }

    $oMaterial->pc01_descrmater = urlencode(urldecode($oMaterial->pc01_descrmater));
    $oMaterial->e62_descr       = urlencode(urldecode($oMaterial->e62_descr));
    $oMaterial->m76_nome        = urlencode(urldecode($oMaterial->m76_nome));
    $oMaterial->m60_descr       = urlencode(urldecode($oMaterial->m60_descr));
    if ($oMaterial->iIndiceEntrada != "") {


      $oOrdemSession[$iCodLanc][$oMaterial->iIndiceEntrada] = $oMaterial;

    } else {

      if ($oMaterial->fraciona) {

        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldoitens -= $oMaterial->quantidadeDebitar;
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldovalor -= $oMaterial->valorDebitar;
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->checked = " checked ";
        if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_quant >
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldoitens ) {
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_quant -= $oMaterial->quantidadeDebitar;
        }

        if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_valor >
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldovalor ) {
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_valor -= $oMaterial->valorDebitar;
        }
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->iTotalFracionados++;
        $oMaterial->iIndiceEntrada = $this->nextVal($iCodLanc);

      }

      $oMaterial->pc01_descrmater = urlencode(urldecode($oMaterial->pc01_descrmater));
      $oOrdemSession[$iCodLanc][] = $oMaterial;
    }
    $_SESSION["matordem{$this->iCodOrdem}"] = $oOrdemSession;
    return true;
  }

  /**
   * Destroi a sessao atual para a ordem de compra;
   *
   * @return boolean
   */
  public function destroySession() {

    if (isset($_SESSION["matordem{$this->iCodOrdem}"])) {
      unset ($_SESSION["matordem{$this->iCodOrdem}"]);
    }
    return true;
  }

  /**
   * retorna a lista dos itens incluidos no estoque. conforme rateio realizado pelo usuario;
   *
   * @return unknown
   */
  public function getDadosEntrada () {

    if (isset($_SESSION["matordem{$this->iCodOrdem}"])) {

      $aItensCadastrados = array();
      foreach ($_SESSION["matordem{$this->iCodOrdem}"] as $oItemOrdem) {

        foreach ($oItemOrdem as $iCodLanc => $oItemLancado) {
           $aItensCadastrados[] = $oItemLancado;
        }
      }
    }
    return $aItensCadastrados;

  }

  /**
   * funcao estatica para retornar se o item servico pode ser controlado
   * por quantidade
   *
   * @param integer $iSequencial (e62_sequencial)
   * @throws Exception
   * @return string
   */
  public static function getServicoQuantidade($iSequencial) {

    $oDaoEmpEmpItem = db_utils::getDao("empempitem");

    $sSqlEmpEmpItem = $oDaoEmpEmpItem->sql_query_file(null, null,
                                                     "e62_servicoquantidade",
                                                      null,
                                                      "e62_sequencial = {$iSequencial}"
                                                     );
    $rsServicoQuantidade = $oDaoEmpEmpItem->sql_record($sSqlEmpEmpItem);
    if ($oDaoEmpEmpItem->numrows <= 0) {

      throw new Exception("ERRO [ 1 ] - erro ao pesquisar se o item pode ser controlado por quantidade.");
    }

    $sServicoQuantidade = db_utils::fieldsMemory($rsServicoQuantidade, 0)->e62_servicoquantidade;

    return $sServicoQuantidade;

  }

  /**
   * Retorna as informações sobre item escolhido
   *
   * @param integer $iCodLanc Código do item da ordem de compra;
   * @param integer $iIndice indice do fracionamento do item. todos os itens tem ao minimo  fracionamento
   * @return object
   */
  function getInfoItem($iCodLanc,  $iIndice) {

    $oItemAtivo = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice];



    $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice]->checked = " checked ";
    $oDaoTransMater = db_utils::getDao("transmater");
    $aItensMaterial = array();
    $sSqlTransMater = $oDaoTransMater->sql_query(null,
                                                 "m63_codmatmater,m60_descr,m60_controlavalidade",
                                                  null,
                                                  "m60_ativo is true
                                                   and m63_codpcmater={$oItemAtivo->pc01_codmater}");
    $rsTransMater = $oDaoTransMater->sql_record($sSqlTransMater);
    if ($oDaoTransMater->numrows > 0) {

      for($iItens = 0; $iItens < $oDaoTransMater->numrows; $iItens++) {
         $aItensMaterial[] = db_utils::fieldsMemory($rsTransMater, $iItens,false,false,$this->getEncode());
      }
    }

    $iSequencialEmpEmpItem = $oItemAtivo->e62_sequencial;

    $oItemAtivo->aMateriaisEstoque = $aItensMaterial;
    $oItemAtivo->sServicoQuantidade = ordemCompra::getServicoQuantidade($iSequencialEmpEmpItem);
    return $oItemAtivo;
  }
  /**
   * Cancela o fracionamento do Item passado;
   *
   * @param integer $iCodLanc código do Lançamento do item
   * @param integer $iIndice indice do item
   * @return boolean
   */
  function cancelarFracionamento($iCodLanc, $iIndice) {

    $oItemAtivo = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice];
    $oItemPai   = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][0];
    $oItemPai->saldoitens += $oItemAtivo->m52_quant;
    $oItemPai->saldovalor += $oItemAtivo->m52_valor;
    $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][0] = $oItemPai;
    unset($oItemAtivo);
    unset($_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice]);
    return true;
  }

  function nextVal($iCodLanc) {

    $iIndiceNovo = 0;
    foreach ($_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc] as $iIndice => $aItens) {

      if ($iIndice > $iIndiceNovo) {
        $iIndiceNovo = $iIndice;
      }

    }
    return $iIndiceNovo+1;
  }

  function confirmaEntrada($iNumNota, $dtDataNota, $dtDataRecebe, $nValorNota, $aItens, $oInfoNota = null, $sObservacao, $sNumeroProcesso=null, $oDataVencimento, $sLocalRecebimento = null) {

    //Devemos estar dentro de uma transação.
    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe uma transação ativa.\nProcedimento Cancelado");
    }

    if (!is_array($aItens)) {
      throw new Exception("Parametro aItens deve ser um Array.\nProcedimento Cancelado");
    }

    $aElementosConfiguradosVerificacaoPatrimonio = array();
    if (!USE_PCASP) {

      $oDaoConfiguracaoDesdobramentoPatrimonio = db_utils::getDao('configuracaodesdobramentopatrimonio');

      $sWhere             = "o56_anousu = ".db_getsession("DB_anousu");
      $sSqlDesdobramentos = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_query(null, "o56_codele", null, $sWhere);
      $rsDesdobramentos   = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_record($sSqlDesdobramentos);
      if ($rsDesdobramentos && $oDaoConfiguracaoDesdobramentoPatrimonio->numrows > 0) {

        $aDesdobramentos = db_Utils::getCollectionByRecord($rsDesdobramentos);
        foreach ($aDesdobramentos as $oDesdobramento) {
          $aElementosConfiguradosVerificacaoPatrimonio[] = $oDesdobramento->o56_codele;
        }
      }
    }
    $aParamKeys = array(db_getsession("DB_anousu"));

    $aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
    $iTipoControleCustos = 0;

    if (count($aParametrosCustos) > 0) {
      $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
    }
    //primeiro, descobrimos a quantidade de empenhos que a ordem de compra possui.
    $aEmpenhos = array();
    $iTotItens = count($aItens);
    $aEntradas = $_SESSION["matordem{$this->iCodOrdem}"];
    /**
     * valor total da entrada.
     */
    $nTotalEntrada = 0;
    for ($iEmp = 0; $iEmp < $iTotItens; $iEmp++) {

      if ($aEntradas[$aItens[$iEmp]->iCodLanc][$aItens[$iEmp]->iIndiceEntrada] ) {

        //Pegamos o item ativo, pelo codigo do lançamento e do seu indice.
        $oItemAtivo = $aEntradas[$aItens[$iEmp]->iCodLanc][$aItens[$iEmp]->iIndiceEntrada];
        if ($oItemAtivo->iTotalFracionados > 0) {
          continue;
        }
        //Agrupamos o valor da entrada por empenho, e definos o codigo na nota fiscal.
        if (!isset($aEmpenhos[$oItemAtivo->e60_numemp])) {

          $aEmpenhos[$oItemAtivo->e60_numemp]["valor"]    = $oItemAtivo->m52_valor;
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = '';
        } else {

          $aEmpenhos[$oItemAtivo->e60_numemp]["valor"]    += $oItemAtivo->m52_valor;
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]  = '';
        }
        $nTotalEntrada += $oItemAtivo->m52_valor;
      }
    }

    if (round($nTotalEntrada,2) != round($nValorNota,2)) {
      throw new Exception("o Valor total da Entrada($nTotalEntrada) da nota diferente do valor da nota ($nValorNota).");
    }

    $this->getDados();


    /**
     * Verificamos se existe controloe do pit, e incluimos as informações extras das notas;
     */
    $iControlaPit = 2;
    $aParamKeys   = array(
                         db_getsession("DB_instit")
                        );

    $aParametrosPit = db_stdClass::getParametro("matparaminstit",$aParamKeys);
    if (count($aParametrosPit) > 0) {
      $iControlaPit = $aParametrosPit[0]->m10_controlapit;
    }

    if($this->dadosOrdem->z01_incest == '' && ($iControlaPit == 1 && $oInfoNota->iTipoDocumentoFiscal == 50) ) {
      $sMsg  = "O cgm (".urldecode($this->dadosOrdem->z01_numcgm)." - ".urldecode($this->dadosOrdem->z01_nome).") ";
      $sMsg .= "não possui inscrição estadual cadastrada. para continuar essa rotina, informe a ";
      $sMsg .= "inscrição estadual do fornecedor";
      throw new Exception($sMsg);
    }

    $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oItemAtivo->e60_numemp);
    $oGrupoConta        = $oEmpenhoFinanceiro->getContaOrcamento()->getGrupoConta();

    $iCodigoMovimentacao = 12;
    if (!empty($oGrupoConta) && $oGrupoConta->getCodigo() == 9) {
      $iCodigoMovimentacao = 23;
    }

    $iDepto                          = $this->dadosOrdem->m51_depto;
    $oDaoMatestoqueIni               = db_utils::getDao("matestoqueini");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d',db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = date('H:i:s');
    $oDaoMatestoqueIni->m80_coddepto = $iDepto;
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_codtipo  = $iCodigoMovimentacao;
    $oDaoMatestoqueIni->m80_obs      = $sObservacao;
    $oDaoMatestoqueIni->incluir(null);
    if ($oDaoMatestoqueIni->erro_status == 0) {

      $sErroMsg  = "Erro [1] - Não foi possivel Iniciar Movimento no Estoque.\n";
      $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueIni->erro_msg}";
      throw new Exception($sErroMsg);
    }
    $iCodMov = $oDaoMatestoqueIni->m80_codigo;

    $clEmpEmpenho = db_utils::getDao('empempenho');

    $aEmpenhoGrupoItem = array();
    for ($iItem = 0; $iItem < $iTotItens; $iItem++) {

      if ($aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada] ) {

        /**
         * Pegamos o item ativo, pelo codigo do lançamento e do seu indice.
         */
        $oItemAtivo = $aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada];


        /**
         * Verifica se a data da nota é inferior a do empenho
         * caso seja então retorna erro
         */
        $sSqlValidaEmp   = $clEmpEmpenho->sql_query_file($oItemAtivo->e60_numemp,"e60_emiss");
        $rsValidaDataEmp = $clEmpEmpenho->sql_record($sSqlValidaEmp);

        if ( pg_num_rows($rsValidaDataEmp) > 0 ) {
          $oDataEmpenho = db_utils::fieldsMemory($rsValidaDataEmp,0);
          if ( implode("-",array_reverse(explode("/",$dtDataNota))) < $oDataEmpenho->e60_emiss ) {
            throw new Exception("Data da nota inferior a data do empenho!");
          }
        }

        /**
         * Verificamos o tipo do controle do custos. caso seje obrigatorio parcustos.cc09_tipocontrole = 3
         * e o material for serviço, e nao foi informado o custo, devemos cancelar a entrada da ordem
         */
        if ($oItemAtivo->cc08_sequencial == "" && $iTipoControleCustos == 3 && $oItemAtivo->pc01_servico == "t") {

          $sErroMsg  = "Erro [5] - Item ({$oItemAtivo->pc01_descrmater}) sem centro de custo .\n";
          $sErroMsg .= "Operação Cancelada.";
          throw new Exception($sErroMsg);
        }
        /**
         * Verificamos se o usuário escolheu um item de entrada para o item.
         * caso nao, devemos incluir o ítem , com a descrição do módulo material.
         */
        if ($oItemAtivo->m63_codmatmater == "") {

          $oDaoMatMater                       = db_utils::getDao("matmater");
          $oDaoMatMater->m60_codmatunid       = 1;
          $oDaoMatMater->m60_quantent         = 1;
          $oDaoMatMater->m60_descr            = urldecode($oItemAtivo->pc01_descrmater);
          $oDaoMatMater->m60_controlavalidade = 3;
          $oDaoMatMater->m60_ativo            = "t";
          $oDaoMatMater->m60_codant           = "";
          $oDaoMatMater->incluir(null);
          if ($oDaoMatMater->erro_status == 0) {

            $sErroMsg  = "Erro [6] - Item ({$oItemAtivo->pc01_descrmater}) nao possui item de Entrada.\n";
            $sErroMsg .= "Operação Cancelada.";
            throw new Exception($sErroMsg);
          }
          $oItemAtivo->m63_codmatmater = $oDaoMatMater->m60_codmater;

          $oDaoMatUnid                  = db_utils::getDao("matmaterunisai");
          $oDaoMatUnid->m62_codmatmater = $oDaoMatMater->m60_codmater;
          $oDaoMatUnid->m62_codmatunid  = 1;
          $oDaoMatUnid->incluir($oDaoMatMater->m60_codmater, 1);
          if ($oDaoMatUnid->erro_status == 0) {

            $sErroMsg  = "Erro [7] - Item ({$oItemAtivo->pc01_descrmater}) unidade de saida.\n";
            $sErroMsg .= "Operação Cancelada.";
            throw new Exception($sErroMsg);
          }
          $oDaoTransMater = db_utils::getDao("transmater");
          $oDaoTransMater->m63_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoTransMater->m63_codpcmater  = $oItemAtivo->pc01_codmater;
          $oDaoTransMater->incluir();
          if ($oDaoTransMater->erro_status == 0) {

            $sErroMsg  = "Erro [13] - Item ({$oItemAtivo->pc01_descrmater}) Não foi possível incluir material.\n";
            $sErroMsg .= "Operação Cancelada.";
            throw new Exception($sErroMsg);
          }
        }

        /*
         * Caso o item for tiver quantidade fracionado maior que 0,
         * e o valor do mesmo for 0;
         * igonoramos o item na inclusao;
         */
         if ($oItemAtivo->iTotalFracionados > 0) {
           continue;
         } else if ($oItemAtivo->iTotalFracionados == 0 && $oItemAtivo->m52_valor == 0) {
           throw new Exception("Item ({$oItemAtivo->pc01_descrmater} com valores inválidos.\Verifique)");
         }
        /*
         * verificamos se a ordem não é uma ordem automatica, caso verdadeiro,
         * devemos criar uma nota para o empenho.
         * marcamos o elemento iCodNota do array aEmpenhos com o codigo da nota,
         * e passamos a  usar essa nota para todas as entradas desse empenho.
         */

        if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == ""
            && $this->dadosOrdem->m51_tipo == 1) {

          $oDaoEmpNota                           = new cl_empnota();
          $oDaoEmpNota->e69_anousu               = db_getsession("DB_anousu");
          $oDaoEmpNota->e69_dtnota               = implode("-", array_reverse(explode("/", $dtDataNota)));
          $oDaoEmpNota->e69_dtrecebe             = implode("-", array_reverse(explode("/", $dtDataRecebe)));
          $oDaoEmpNota->e69_dtvencimento         = $oDataVencimento ? $oDataVencimento->getDate() : null;
          $oDaoEmpNota->e69_localrecebimento     = $sLocalRecebimento;
          $oDaoEmpNota->e69_id_usuario           = db_getsession("DB_id_usuario");
          $oDaoEmpNota->e69_numemp               = $oItemAtivo->e60_numemp;
          $oDaoEmpNota->e69_numero               = $iNumNota;
          $oDaoEmpNota->e69_dtservidor           = date('Y-m-d');
          $oDaoEmpNota->e69_dtinclusao           = date('Y-m-d',db_getsession("DB_datausu"));
          $oDaoEmpNota->e69_tipodocumentosfiscal = $oInfoNota->iTipoDocumentoFiscal;
          $oDaoEmpNota->incluir(null);
          if ($oDaoEmpNota->erro_status == 0 ) {

            $sErroMsg  = "Erro [2] - Não foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoEmpNota->erro_msg}";
            throw new Exception($sErroMsg);
          }
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = $oDaoEmpNota->e69_codnota;

          /**
           * Vinculamos o processo informado na tela pelo usuário à nota de pagamento
           */
          if ( !empty($sNumeroProcesso) ) {

            $oDaoProcessoNota = new cl_empnotaprocesso();
            $oDaoProcessoNota->e04_sequencial     = null;
            $oDaoProcessoNota->e04_empnota        = $oDaoEmpNota->e69_codnota;
            $oDaoProcessoNota->e04_numeroprocesso = $sNumeroProcesso;
            $oDaoProcessoNota->incluir(null);
            if ($oDaoProcessoNota->erro_status == "0") {
              throw new Exception("Não foi possível vincular o número do processo a nota.");
            }
          }

          //incluimos o elemento da nota;
          $oDaoElemento = db_utils::getDao("empelemento");

          $sSqlElemento = $oDaoElemento->sql_query($oItemAtivo->e60_numemp);
          $rsElemento   = $oDaoElemento->sql_record($sSqlElemento);
          if ($oDaoElemento->numrows == 1) {
            $oElemento = db_utils::fieldsMemory($rsElemento, 0);
          } else {
            throw new Exception("Erro[3] - Empenho sem elementos, ou com mais de um elemento.Procedimento cancelado");
          }

          $aEmpenhos[$oItemAtivo->e60_numemp]['elemento']        =  $oElemento->o56_elemento;
          $aEmpenhos[$oItemAtivo->e60_numemp]['codigo_elemento'] =  $oElemento->o56_codele;

          $oDaoEmpNotaEle              = db_utils::getDao("empnotaele");
          $oDaoEmpNotaEle->e70_codele  = $oElemento->e64_codele;
          $oDaoEmpNotaEle->e70_codnota = $oDaoEmpNota->e69_codnota;
          $oDaoEmpNotaEle->e70_valor   = round($aEmpenhos[$oItemAtivo->e60_numemp]["valor"],2);
          $oDaoEmpNotaEle->e70_vlrliq  = "0";
          $oDaoEmpNotaEle->e70_vlranu  = "0";
          $oDaoEmpNotaEle->incluir($oDaoEmpNota->e69_codnota, $oElemento->e64_codele);
          if ($oDaoEmpNotaEle->erro_status == 0) {

            $sErroMsg  = "Erro [4] - Não foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoEmpNotaEle->erro_msg}";
            throw new Exception($sErroMsg);
          }

          $oDaoEmpNotaOrd = db_utils::getDao("empnotaord");
          $oDaoEmpNotaOrd->incluir($oDaoEmpNota->e69_codnota, $this->dadosOrdem->m51_codordem);
        } else if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == ""
                   && $this->dadosOrdem->m51_tipo == 2) {

          /**
           * a nota é virtual, entao apenas pegamos o número da nota gerada .
           */
          $oDaoEmpNota = db_utils::getDao("empnota");
          $rsNota      = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null,
                                                                               "e69_codnota, e70_codele",
                                                                                null,
                                                                                "m72_codordem = {$this->dadosOrdem->m51_codordem}"));
          if ($oDaoEmpNota->numrows == 1) {

            $oNotas   = db_utils::fieldsMemory($rsNota,0);
            $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]        = $oNotas->e69_codnota;
            $aEmpenhos[$oItemAtivo->e60_numemp]['codigo_elemento'] =  $oNotas->e70_codele;
          } else {
            throw new Exception("Erro[5] - Ordem de Compra automática sem Nota fiscal. Procedimento cancelado");
          }
        }


        /**
         * Validadamos o departamento da OC com o departamento que  usuário está logado.
         * Anteriormente caso o material fosse serviço, apenas davamos entrada no mesmo depto da OC.
         * Anteriormente caso fosse material normal, o depto que o usuario está logado(DB_coddepto )
         *               deveria ser igual ao depto da OC;
         *
         * Agora: se o departamento da ordem for diferente do departamento logado,
         *        jogamos uma exceção e cancelamos a entrada da OC
         */
        if (db_getsession("DB_coddepto") != $this->dadosOrdem->m51_depto) {
          throw new Exception("Erro [5] - Ordem de compra deve ser lançada no seu depósito de destino.\nOperação Cancelada");
        }

        /**
         * Verificamos se o item escolhido já possui estoque (matestoque)
         * cadastrado no departamento da ordem de compra.
         * caso nao exista, fazemos o cadastro
         */
        $oDaoMatestoque = db_utils::getDao("matestoque");
        $sSqlMaterialEstoque = $oDaoMatestoque->sql_query_file(null,
                                                               "*",
                                                               null,
                                                               "m70_codmatmater  = {$oItemAtivo->m63_codmatmater}
                                                                and m70_coddepto = {$iDepto}");

        $rsMatestoque   = $oDaoMatestoque->sql_record($sSqlMaterialEstoque);
        if ($oDaoMatestoque->numrows == 0) {

          $oDaoMatestoque->m70_coddepto    = $iDepto;
          $oDaoMatestoque->m70_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoMatestoque->m70_valor       = "0";
          $oDaoMatestoque->m70_quant       = "0";
          $oDaoMatestoque->incluir(null);
          $iCodEstoque = $oDaoMatestoque->m70_codigo;
          if ($oDaoMatestoque->erro_status == 0) {

            $sErroMsg  = "Erro [7] - Não foi possível iniciar estoque.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoque->erro_msg}";
            throw new Exception($sErroMsg);
          }
        } else {
          $iCodEstoque = db_utils::fieldsMemory($rsMatestoque, 0)->m70_codigo;
        }

        $iQuantUnidade = $oItemAtivo->quantunidade;
        if ($oItemAtivo->quantunidade <= 0) {
        	$iQuantUnidade = 1;
        }
        //incluimos na matestoqueitem

        if (!empty($oGrupoConta) && $oGrupoConta->getCodigo() == 9 && $oItemAtivo->pc01_servico == "t") {

          $sMensagem  = "O grupo do empenho está classificado como Despesa em Material Permanente (Grupo 9) e o material como serviço.\n\n";
          $sMensagem .= "Para realizar a entrada da O.C. é preciso configurar o grupo do empenho como Despesa com Serviços (Grupo 7).";
          throw new BusinessException($sMensagem);
        }

        $oDaoMatestoqueItem = db_utils::getDao("matestoqueitem");
        $oDaoMatestoqueItem->m71_codmatestoque = $iCodEstoque;
        $oDaoMatestoqueItem->m71_data          = implode("-", array_reverse(explode("/", $dtDataRecebe)));
        $oDaoMatestoqueItem->m71_quant         = $oItemAtivo->m52_quant * $iQuantUnidade;
        $oDaoMatestoqueItem->m71_quantatend    = !empty($oGrupoConta) && $oGrupoConta->getCodigo() == 9 ? $oDaoMatestoqueItem->m71_quant : "0";
        $oDaoMatestoqueItem->m71_valor         = $oItemAtivo->m52_valor;
        $oDaoMatestoqueItem->m71_servico       = $oItemAtivo->pc01_servico == "t" ? "true" : "false";
        $oDaoMatestoqueItem->incluir(null);
        if ($oDaoMatestoqueItem->erro_status == 0){

          $sErroMsg  = "Erro [8] - Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueItem->erro_msg}";
          throw new Exception($sErroMsg);
        }

        //incluimos matestoqueitemunid
        $oDaoMatUnid                        = db_utils::getDao("matestoqueitemunid");
        $oDaoMatUnid->m75_codmatestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
        $oDaoMatUnid->m75_codmatunid        = $oItemAtivo->unidade;
        $oDaoMatUnid->m75_quant             = $oItemAtivo->m52_quant * $iQuantUnidade;
        $oDaoMatUnid->m75_quantmult         = $iQuantUnidade;
        $oDaoMatUnid->incluir($oDaoMatestoqueItem->m71_codlanc);
        if ($oDaoMatUnid->erro_status==0){

          $sErroMsg  = "Erro [8]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatUnid->erro_msg}";
          throw new Exception($sErroMsg);
        }
        /**
         * incluimos a ligação da entrada da ordem de compra com o item do estoque
         */
        $oDaoMatItemOC = db_utils::getDao("matestoqueitemoc");
        $oDaoMatItemOC->incluir($oDaoMatestoqueItem->m71_codlanc, $oItemAtivo->m52_codlanc);
        if ($oDaoMatItemOC->erro_status == 0) {

          $sErroMsg  = "Erro [9]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatItemOC->erro_msg}";
          throw new Exception($sErroMsg);
        }
        /**
         * Verificamos se foi definido uma apropriacao para o item da OC.
         * Como foi realizado, devemos anular essa apropriação
         */
        $oDaoMatordemItemCustoCriterio = db_utils::getdao("matordemitemcustocriterio");
        $sSqlApropria  = $oDaoMatordemItemCustoCriterio->sql_query_file(null,"cc11_sequencial",
                                                                        "cc11_matordemitem = {$oItemAtivo->m52_codlanc}");
        $rsApropria    = $oDaoMatordemItemCustoCriterio->sql_record($sSqlApropria);

        /*
         * Caso o usuário informou o fabricante do material,
         * gravamos na matestoqueitemfabricante.
         */
        if (trim($oItemAtivo->m78_matfabricante) != '') {

          $oDaoMatFabricante = db_utils::getDao("matestoqueitemfabric");
          $oDaoMatFabricante->m78_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatFabricante->m78_matfabricante  = $oItemAtivo->m78_matfabricante;
          $oDaoMatFabricante->incluir(null);
          if ($oDaoMatFabricante->erro_status == 0) {

            $sErroMsg  = "Erro [17] - Não foi possível Salvar informações do fabricante.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatFabricante->erro_msg}";
            throw new Exception($sErroMsg);

          }
        }
        /*
         * Gravamos matestoqueinimei
         */
        $oDaoMatestoqueIniMei = db_utils::getDao("matestoqueinimei");
        $oDaoMatestoqueIniMei->m82_matestoqueini  = $iCodMov;
        $oDaoMatestoqueIniMei->m82_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
        $oDaoMatestoqueIniMei->m82_quant          = ($oItemAtivo->m52_quant * $iQuantUnidade);
        $oDaoMatestoqueIniMei->incluir(null);
        if ($oDaoMatestoqueIniMei->erro_status == 0) {

          $sErroMsg  = "Erro [11] - Não foi possível finalizar a inclusao da Ordem.\n";
          $sErroMsg .= "[Erro Técnico] - ".str_replace("\\n", "\n", $oDaoMatestoqueIniMei->erro_msg);
          throw new Exception($sErroMsg);
        }
        /**
         * Caso o material seje servico, já fizemos a saida automatica para esse material
         */
        if ($oItemAtivo->pc01_servico == "t") {

          $oMaterialEstoque = new materialEstoque($oItemAtivo->m63_codmatmater);
          if ($oItemAtivo->cc08_sequencial != "") {
            $oMaterialEstoque->setCriterioRateioCusto($oItemAtivo->cc08_sequencial);
          }
          $oMaterialEstoque->setCodDepto($this->dadosOrdem->m51_depto);
          $oMaterialEstoque->saidaMaterial($oItemAtivo->m52_quant*$iQuantUnidade, null, true);
        }

        /**
         * Executa uma saída automática quando for material permanente
         */
        if (!empty($oGrupoConta) && $oGrupoConta->getCodigo() == 9) {

          $oItemMovimentacao = new MaterialEstoqueMovimentacao(null);
          $oItemMovimentacao->setDepartamento(DBDepartamentoRepository::getDBDepartamentoByCodigo($iDepto));
          $oItemMovimentacao->setMovimento(new TipoMovimentacaoEstoque(24));
          $oItemMovimentacao->setUsuario(UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario')));
          $oItemMovimentacao->setData(new DBDate(date('Y-m-d',db_getsession("DB_datausu"))));
          $oItemMovimentacao->setHora(date('H:i:s'));
          $oItemMovimentacao->setObservacao("Saída automática de Material Permanente");
          $oItemMovimentacao->salvar();

          $oItemEstoque = new MaterialEstoqueItem($oDaoMatestoqueItem->m71_codlanc);
          MaterialEstoqueItem::vincularMovimentacaoComItem($oItemEstoque, $oItemMovimentacao, $oDaoMatestoqueIniMei->m82_quant);
        }


        /**
         * incluimos ligacao do itemn com a nota fiscal
         */
        $oDaoMatItemNota  = db_utils::getDao("matestoqueitemnota");
        $oDaoMatItemNota->incluir($oDaoMatestoqueItem->m71_codlanc, $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]);
        if ($oDaoMatItemNota->erro_status == 0) {

          $sErroMsg  = "Erro [10] - Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatItemNota->erro_msg}";
          throw new Exception($sErroMsg);

        }
        /**
         * caso o usuário deu informações sobre o lote, salvamos na tabela matestoqueitemlote
         */
        if (trim($oItemAtivo->m77_lote) != "") {

          $oDaoMatestoqueItemLote = db_utils::getDao("matestoqueitemlote");
          $oDaoMatestoqueItemLote->m77_dtvalidade     =  implode("-", array_reverse(explode("/", $oItemAtivo->m77_dtvalidade)));
          $oDaoMatestoqueItemLote->m77_lote           = $oItemAtivo->m77_lote;
          $oDaoMatestoqueItemLote->m77_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatestoqueItemLote->incluir(null);
          if ($oDaoMatestoqueItemLote->erro_status == 0) {

            $sErroMsg  = "Erro [13]- Não foi possível Salvar informações do lote.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueItemLote->erro_msg}";
            throw new Exception($sErroMsg);
          }
        }
      }


      if (USE_PCASP) {

        $oEmpenhoFinanceiro      = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oItemAtivo->e60_numemp);
        $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();

        $iCodigoContaElemento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
        $oGrupoContaOrcamento    = GrupoContaOrcamento::getGrupoConta($iCodigoContaElemento, db_getsession("DB_anousu"));

        if ($oGrupoContaOrcamento) {

          $iGrupoContaOrcamento = $oGrupoContaOrcamento->getCodigo();
          if (in_array($iGrupoContaOrcamento, array(7, 8, 10))) {

            $oMaterialEstoque             = new materialEstoque($oItemAtivo->m63_codmatmater);
            $oGrupoMaterial               = $oMaterialEstoque->getGrupo();
            $oItemAtivo->iCodigoDaNota    = $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"];
            $oItemAtivo->nValorLancamento = $oItemAtivo->m52_valor;

            /**
             *  Material sem grupo configurado
             */
            if (empty($oGrupoMaterial)) {
              throw new Exception(
                "Material {$oMaterialEstoque->getDados()->m63_codpcmater} - " .
                "{$oMaterialEstoque->getDados()->m60_descr} sem grupo."
              );
            }

             // adicionamos em um array agrupado por empenho e de acordo com o grupo a qual o material pertence
            $aEmpenhoGrupoItem[$oItemAtivo->e60_numemp][$oGrupoMaterial->getCodigo()][] = $oItemAtivo;
          }
        }

      }
    }

    $dtAtual      = date("Y-m-d", db_getsession( 'DB_datausu' ));
    $oDataAtual   = new DBDate($dtAtual);
    $oInstituicao = new Instituicao(db_getsession('DB_instit'));

    if (USE_PCASP && count($aEmpenhoGrupoItem) > 0 &&  (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataAtual, $oInstituicao)) ) {
      $this->processarLancamentosOrdemCompra($aEmpenhoGrupoItem);
    }

    /**
     * Verificamos se existe controloe do pit, e incluimos as informações extras das notas;
     */
     $iControlaPit = 2;
     $aParamKeys = array(
                         db_getsession("DB_instit")
                        );
     $aParametrosPit   = db_stdClass::getParametro("matparaminstit",$aParamKeys);
     if (count($aParametrosPit) > 0) {
       $iControlaPit = $aParametrosPit[0]->m10_controlapit;
     }
    /**
     * Incluimos os itens de cada nota, conforme a entrada dos mesmos no estoque
     */
    if ($iControlaPit == 1) {

      if ($oInfoNota->iTipoDocumentoFiscal == "") {

        $sErroMsg  = "Erro [14]- Tipo de documento fiscal não informado.\n";
        throw new Exception($sErroMsg);
        return false;

      }

      if ($oInfoNota->iTipoDocumentoFiscal == 50) {

        if ($oInfoNota->iCfop == "") {

          $sErroMsg  = "Erro [15] - CFOP não informada.\n";
          throw new Exception($sErroMsg);
        }

        $oDaoEmpnotaDadosPit                                = db_utils::getDao("empnotadadospit");
        $oDaoEmpnotaDadosPit->e11_cfop                      = $oInfoNota->iCfop;
        $oDaoEmpnotaDadosPit->e11_seriefiscal               = $oInfoNota->sSerieFiscal;
        $oDaoEmpnotaDadosPit->e11_inscricaosubstitutofiscal = $oInfoNota->iInscrSubstituto;
        $oDaoEmpnotaDadosPit->e11_basecalculoicms           = "$oInfoNota->nBaseCalculoICMS";
        $oDaoEmpnotaDadosPit->e11_valoricms                 = "$oInfoNota->nValorICMS";
        $oDaoEmpnotaDadosPit->e11_basecalculosubstitutotrib = "$oInfoNota->nBaseCalculoSubst";
        $oDaoEmpnotaDadosPit->e11_valoricmssubstitutotrib   = "$oInfoNota->nValorICMSSubst";
        $oDaoEmpnotaDadosPit->incluir(null);
        if ($oDaoEmpnotaDadosPit->erro_status == 0) {

           $sErroMsg  = "Erro [16] - Não foi possível Salvar informações da nota Fiscal.\n";
           $sErroMsg .= "[Erro Técnico] - {$oDaoEmpnotaDadosPit->erro_msg}";
           throw new Exception($sErroMsg);
        }
      }
    }

    if ($this->dadosOrdem->m51_tipo == 1) {

      foreach ($aEmpenhos as $iEmpenho => $oNota) {

        /**
         * A nota fiscal sempre deve ser incluida com os valores/quantidades da nota fiscal.
         * A quantidade que se dá no estoque pode ser diferente, pois podemos comprar em caixa, e o no material a
         * entrada pode ser feita por unidade
         */
        $iCodNota      = $oNota["iCodNota"];
        $clempnotaitem = db_utils::getDao("empnotaitem");
        $sSQlItens     = "SELECT sum(m71_quant / coalesce(m75_quantmult, 1)) as m71_quant,";
        $sSQlItens    .= "       sum(m71_valor) as m71_valor,";
        $sSQlItens    .= "       e62_sequencial, ";
        $sSQlItens    .= "       e62_codele, ";
        $sSQlItens    .= "       array_to_string(array_accum(m71_codlanc), '#') as m71_codlanc";
        $sSQlItens    .= "  from matestoqueitem ";
        $sSQlItens    .= "            inner join matestoqueitemnota on m71_codlanc = m74_codmatestoqueitem";
        $sSQlItens    .= "            inner join matestoqueitemoc   on m71_codlanc = m73_codmatestoqueitem";
        $sSQlItens    .= "            inner join matordemitem       on m52_codlanc = m73_codmatordemitem";
        $sSQlItens    .= "            left  join matestoqueitemunid on m75_codmatestoqueitem = m71_codlanc";
        $sSQlItens    .= "            inner join empempitem         on m52_numemp  = e62_numemp";
        $sSQlItens    .= "                                         and m52_sequen  = e62_sequen";
        $sSQlItens    .= "  where m74_codempnota = {$iCodNota}";
        $sSQlItens    .= "  group by  e62_sequencial,e62_codele";

        $rsItens       = db_query($sSQlItens);
        if (! $rsItens ) {

          $sErroMsg  = "Erro[14] - Erro ao buscar os itens da entrada no estoque. \n";
          $sErroMsg .= "[Erro Técnico] - ".pg_last_error();
          throw new Exception($sErroMsg);
        }

        for ($iInd = 0; $iInd < pg_num_rows($rsItens); $iInd++) {

          $oItens = db_utils::fieldsMemory($rsItens, $iInd);

          $clempnotaitem->e72_codnota    = $iCodNota;
          $clempnotaitem->e72_empempitem = $oItens->e62_sequencial;
          $clempnotaitem->e72_qtd        = $oItens->m71_quant;
          $clempnotaitem->e72_valor      = $oItens->m71_valor;
          $clempnotaitem->incluir(null);
          if ($clempnotaitem->erro_status == 0) {

            $sErroMsg  = "Erro[12] - Não foi possível incluir itens da nota.\n";
            $sErroMsg .= "[Erro Técnico] - {$clempnotaitem->erro_msg}";
            throw new Exception($sErroMsg);
            break;
          }
          $oGrupo = GrupoContaOrcamento::getGrupoConta($oNota['codigo_elemento'], db_getsession("DB_anousu"));

          if ((!USE_PCASP  && in_array($oNota['codigo_elemento'], $aElementosConfiguradosVerificacaoPatrimonio))
              || (USE_PCASP && $oGrupo instanceof GrupoContaOrcamento && $oGrupo->getCodigo() == 9)) {

            $aLancamentos = explode("#", $oItens->m71_codlanc);

            for ($i = 0; $i < count($aLancamentos); $i++) {

              $oDaoBensPendente                      = db_utils::getDao('empnotaitembenspendente');
              $oDaoBensPendente->e137_sequencial     = null;
              $oDaoBensPendente->e137_empnotaitem    = $clempnotaitem->e72_sequencial;
              $oDaoBensPendente->e137_matestoqueitem = $aLancamentos[$i];
              $oDaoBensPendente->incluir(null);
              if ($oDaoBensPendente->erro_status == 0) {

                $sErroMsg  = "Erro[13] - Não foi possível incluir vínculo do empenho com o patrimônio.\n";
                $sErroMsg .= "[Erro Técnico] - {$oDaoBensPendente->erro_msg}";
                throw new Exception($sErroMsg);
                break;
              }
            }
          }
        }
      }
      /**
       * Vinculamos as notas ao empnotadadospit
       */
      if ($oInfoNota->iTipoDocumentoFiscal == 50) {

        $oDaoEmpnotaDadosPitNota                      = db_utils::getDao("empnotadadospitnotas");
        $oDaoEmpnotaDadosPitNota->e13_empnota         = $oNota["iCodNota"];
        $oDaoEmpnotaDadosPitNota->e13_empnotadadospit = $oDaoEmpnotaDadosPit->e11_sequencial;
        $oDaoEmpnotaDadosPitNota->incluir(null);
        if ($oDaoEmpnotaDadosPitNota->erro_status == 0) {

          $sErroMsg  = "Erro[17] - Não foi possível incluir itens da nota.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoEmpnotaDadosPitNota->erro_msg}";
          throw new Exception($sErroMsg);
        }
      }
    }

    $this->destroySession();
    return true;
  }

  /**
   * Funcao para pesquisar os desdobramentos
   *
   * @param integer $iEstrutural
   * @return $aItens
   */

  public function getDesdobramentosLiberados($iEstrutural) {

    $iAnoUso         = db_getsession("DB_anousu");
    $oDaoOrcElemento = db_utils::getDao("orcelemento");
    $aItens          = array();

    $sCampos  = " desdobramentosliberadosordemcompra.pc33_sequencial,                                                ";
    $sCampos .= " orcelemento.o56_codele,                                                                            ";
    $sCampos .= " orcelemento.o56_elemento,                                                                          ";
    $sCampos .= " orcelemento.o56_descr                                                                              ";

    $sWhere  = " o56_anousu = {$iAnoUso}";
    if (!empty($iEstrutural)) {
      $sWhere .= " and o56_elemento like '{$iEstrutural}%'";
    }

    $sSqlOrcElemento  = $oDaoOrcElemento->sql_query_desdobramento_liberados(null, null,$sCampos,null,$sWhere);
    $rsSqlOrcElemento = $oDaoOrcElemento->sql_record($sSqlOrcElemento);
    $aItens           = db_utils::getCollectionByRecord($rsSqlOrcElemento, true, false, true);

    return $aItens;
  }

  /**
   * Funcao para liberar os desdobramentos
   *
   * @param  array $aDesdobramentos lista desdobramentos
   * @return ordemCompra
   */
  public function liberarDesdobramentos($aDesdobramentos) {

    $oDaoDesdobramentoLiberado = db_utils::getDao("desdobramentosliberadosordemcompra");
    $iAnoUsu                   = db_getsession('DB_anousu');
    if (!db_utils::inTransaction()) {
      throw new Exception('Nao existe transação com o banco de dados ativa.');
    }

    $oDaoDesdobramentoLiberado->excluir(null, "pc33_anousu = {$iAnoUsu}");
    if ( $oDaoDesdobramentoLiberado->erro_status == 0 ) {
    	throw new Exception($oDaoDesdobramentoLiberado->erro_msg);
    }

    foreach ($aDesdobramentos as $oDesdobramento) {

    	if ($oDesdobramento->lLiberar) {
        $oDaoDesdobramentoLiberado->pc33_codele = $oDesdobramento->iNumele;
        $oDaoDesdobramentoLiberado->pc33_anousu = $iAnoUsu;
        $oDaoDesdobramentoLiberado->incluir(null);
        if ( $oDaoDesdobramentoLiberado->erro_status == 0 ){
          throw new Exception($oDaoDesdobramentoLiberado->erro_msg);
        }

      }

    }

    return $this;
  }

  /**
   * Verifica se há bens ativos para a nota de empenho informada no parâmetro
   * @param integer $iCodigoNota Código da nota que deve ser usada na pesquisa de bens ativos por nota
   * @return mixed
   */
  public function getBensAtivoNota($iCodigoNota) {

    $oDaoBensEmpNotaItem     = db_utils::getDao('bensempnotaitem');
    $sCamposBuscaItensAtivos = "*";
    $sWhereBuscaItensAtivos  = "     empnotaitem.e72_codnota  = {$iCodigoNota} ";
    $sWhereBuscaItensAtivos .= " and bensbaix.t55_codbem is null ";
    $sSqlBuscaItensAtivos    = $oDaoBensEmpNotaItem->sql_query_bens_ativos(null, $sCamposBuscaItensAtivos,
                                                                           null, $sWhereBuscaItensAtivos);
    $rsBuscaItensAtivos      = $oDaoBensEmpNotaItem->sql_record($sSqlBuscaItensAtivos);
    $aItensAtivos            = db_utils::getCollectionByRecord($rsBuscaItensAtivos);
    $iLinhas                 = $oDaoBensEmpNotaItem->numrows;
    $mRetorno                = array();
    for($i = 0; $i < $iLinhas; $i++) {

      $oItemAtivo                     = db_utils::fieldsMemory($rsBuscaItensAtivos, $i);
      $oDadosItemAtivo                = new stdClass();
      $oDadosItemAtivo->iCodigoBem    = $oItemAtivo->t52_bem;
      $oDadosItemAtivo->sDescricaoBem = $oItemAtivo->t52_descr;
      $oDadosItemAtivo->sPlaca        = $oItemAtivo->t41_placa;
      $oDadosItemAtivo->iPlacaSeq     = $oItemAtivo->t41_placaseq;
      $oDadosItemAtivo->sEmpenho      = $oItemAtivo->e60_codemp;
      $oDadosItemAtivo->iAnoEmpenho   = $oItemAtivo->e60_anousu;
      $mRetorno[]                     = $oDadosItemAtivo;
    }
    if (count($mRetorno) == 0) {
      $mRetorno = false;
    }

    return $mRetorno;
  }

  /**
   * Verifica se houve dispensa de tombamento do bem no patrimonio
   * @param integer $iCodigoNota
   * @return boolean
   */
  public function houveDispensaTombamentoNoPatrimonio($iCodigoNota) {

    /* Verificamos se houve dispensa de tombamento para os itens da nota */
    $oDaoDispensaTombamento = db_utils::getDao("bensdispensatombamento");
    $sSqlBuscaDispensa      = $oDaoDispensaTombamento->sql_query(null, "1", null, "empnotaitem.e72_codnota = {$iCodigoNota}");
    $rsBuscaDispensa        = $oDaoDispensaTombamento->sql_record($sSqlBuscaDispensa);
    if ($oDaoDispensaTombamento->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Processamos o lançamento contábil de acordo com os itens da entrada percorrendo os grupos dos materiais
   * de cada empenho
   *
   * @param array $aEmpenhoGrupoItem
   */
  private function processarLancamentosOrdemCompra($aEmpenhoGrupoItem, $lEstorno = false) {

    $iAnoUsu = db_getsession("DB_anousu");

    foreach ($aEmpenhoGrupoItem as $iSequencialEmpenho => $aGrupo) {

      foreach ($aGrupo as $iCodigoGrupo => $aItens) {

        $nValorLancamentoGrupo = 0;
        foreach ($aItens as $oItem) {

          $nValorLancamentoGrupo += $oItem->nValorLancamento;
          $iCodigoNotaDoEmpenho   = $oItem->iCodigoDaNota;
        }

        $oEmpenhoFinanceiro      = new EmpenhoFinanceiro($iSequencialEmpenho);
        $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();
        $iCodigoContaElemento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
        $sObservacaoLancamento   = "Lançamento em liquidação da ordem de compra {$this->getOrdem()}";

        $oStdDadosLancamento = new stdClass();
        $oStdDadosLancamento->iCodigoDotacao        = $oEmpenhoFinanceiro->getDotacao()->getCodigo();
        $oStdDadosLancamento->iCodigoElemento       = $iCodigoContaElemento;
        $oStdDadosLancamento->iCodigoNotaLiquidacao = $iCodigoNotaDoEmpenho;
        $oStdDadosLancamento->iFavorecido           = $oEmpenhoFinanceiro->getCgm()->getCodigo();
        $oStdDadosLancamento->iNumeroEmpenho        = $oEmpenhoFinanceiro->getNumero();
        $oStdDadosLancamento->sObservacaoHistorico  = $sObservacaoLancamento;
        $oStdDadosLancamento->iCodigoGrupo          = $iCodigoGrupo;
        $oStdDadosLancamento->nValorTotal           = round($nValorLancamentoGrupo, 2);

        $iCodigoTipoDocumento = 200;

        if ($lEstorno) {
          $iCodigoTipoDocumento = 201;
        }

        if ($this->validaLancamentoMaterialPermanente($oStdDadosLancamento->iCodigoElemento, $iCodigoTipoDocumento)) {
          LancamentoEmpenhoEmLiquidacao::processar($oStdDadosLancamento, $lEstorno);
        }

      } // foreach

    } // foreach

    return true;
  }

  private function validaLancamentoMaterialPermanente($iCodigoContaElemento, $iTipoDocumento) {

    $oDocumentoContabil = SingletonRegraDocumentoContabil::getDocumento($iTipoDocumento);
    $oDocumentoContabil->setValorVariavel("[desdobramento]", $iCodigoContaElemento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

    if (in_array($iCodigoDocumentoExecutar, array(208, 209))) {
      return false;
    }

    return true;
  }

  /**
   * @return EmpenhoFinanceiro
   * @throws BusinessException
   */
  public function getEmpenhoFinanceiro() {

    if (empty($this->oEmpenhoFinanceiro)) {
      $oDaoOrdemItem = new cl_matordemitem();
      $sSqlBuscaItem = $oDaoOrdemItem->sql_query_file(null, "m52_numemp", null, "m52_codordem = {$this->iCodOrdem}");
      $rsBuscaOrdem  = $oDaoOrdemItem->sql_record($sSqlBuscaItem);
      if ($oDaoOrdemItem->erro_status == "0") {
        throw new BusinessException("Empenho não encontrado para a ordem de compra {$this->iCodOrdem}.");
      }
      $iSequencial = db_utils::fieldsMemory($rsBuscaOrdem, 0)->m52_numemp;
      $this->setEmpenhoFinanceiro(EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($iSequencial));
    }
    return $this->oEmpenhoFinanceiro;

  }

  /**
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenhoFinanceiro(EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenhoFinanceiro = $oEmpenho;
  }
}
