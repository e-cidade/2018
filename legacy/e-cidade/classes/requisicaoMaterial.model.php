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

require_once("libs/db_app.utils.php");
db_app::import("estoque.MaterialGrupo");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.*");
db_app::import("exceptions.*");
/**
 * Modelo para controle das requisições de saida de material
 * @author Iuri Guntchnigg $Author: dbigor.cemim $
 * @version  $Revision: 1.50 $
 */
class RequisicaoMaterial {

  /**
   * Codigo da requisição
   *
   * @var unknown_type
   */


  private $icodReq = null;


  /**
   *
   * Coleção dos itens da requisição
   *
   * @var object
   */
  private $oDadosRequisacao = null;

  /**
   * Objeto com as propriedades da requisicao
   *
   * @var object
   */
  public $oItensRequisicao = null;

  /**
   * Se e aplicado urlencode nas strings
   *
   * @var boolean
   */
  private $lEncode = false;
  /**
   * Metodo Construtor
   */
  function __construct($iCodReq) {

    $this->icodReq = $iCodReq;

  }
  /**
   * @return integer
   */
  public function getIcodReq() {

    return $this->icodReq;
  }

  function setEncode($lEncode) {

    $this->lEncode = $lEncode;
  }

  function getEncode() {

    return $this->lEncode;
  }
  /**
   * seta a propriedade oDadosRequisicao os dados da requisição
   *
   * @return object false em caso de erro
   */
  function getDados() {

    $oDaoMatRequisicao = db_utils::getDao("matrequi");
    $sSqlMatRequi      = $oDaoMatRequisicao->sql_query_almox($this->icodReq);
    $rsMatRequi        = $oDaoMatRequisicao->sql_record($sSqlMatRequi);
    if ($oDaoMatRequisicao->numrows == 1) {
      $this->oDadosRequisacao = db_utils::fieldsMemory($rsMatRequi, 0, false, false, $this->getEncode());
      return true;
    } else {
      return false;
    }
  }
  /**
   * Retorna os items da requisição
   *
   * @param integer [$iCodItem]Código do item na requisição se passado, busca apenas o item.
   * @return array|object
   */
  public function   getItens($iCodItem = null, $iCodigoMaterial = '') {

    $sWhere = '';
    if ($iCodItem != '') {
      $sWhere = " and m41_codigo = {$iCodItem}";
    }
    if ($iCodigoMaterial != '') {
      $sWhere = " and m41_codmatmater = {$iCodigoMaterial}";
    }
    $oDaoRequisicaoItens = new cl_matrequiitem();

    $sCampos  = " m41_codigo,m41_codmatmater,m60_descr,unidade_saida.m61_descr,m41_quant,m91_depto, ";
    $sCampos .= " (select coalesce(sum(m43_quantatend),0)";
    $sCampos .= "  from atendrequiitem ";
    $sCampos .= "   left join matestoquedevitem on atendrequiitem.m43_codigo =  m46_codatendrequiitem ";
    $sCampos .= "  where m43_codmatrequiitem = m41_codigo";
    $sCampos .= " ) as totalAtendido,";
    $sCampos .= "  coalesce((select sum(m103_quantanulada) ";
    $sCampos .= "              from matanulitem ";
    $sCampos .= "            left join matanulitemrequi on matanulitemrequi.m102_matanulitem = matanulitem.m103_codigo";
    $sCampos .= "            where  m102_matrequiitem = m41_codigo)";
    $sCampos .= "          ,0) as quantanulada, ";
    $sCampos .= "m60_controlavalidade,cc08_sequencial, cc08_descricao,";
    $sCampos .= "(select sum(m70_quant) as total  ";
    $sCampos .= "  from matestoque saldo inner join db_almox on saldo.m70_coddepto = m91_depto ";
    $sCampos .= " where saldo.m70_codmatmater = m41_codmatmater and m91_codigo = m40_almox) as qtdeEstoque";

    $sGroupBy  = " group by m41_codigo, m41_codmatmater,m60_descr,m61_descr,m41_quant,m91_depto,";
    $sGroupBy .= " m60_controlavalidade,cc08_sequencial, cc08_descricao";
    $sGroupBy  = "";

    $sSqlreqItens = $oDaoRequisicaoItens->sql_query_estoque_atend_requi(null,
                                                            $sCampos,
                                                            'm60_descr',
                                                            "m41_codmatrequi = ".$this->getIcodReq().
                                                            " {$sWhere} $sGroupBy");

    $rsReqItem = $oDaoRequisicaoItens->sql_record($sSqlreqItens);

    $aItensRequisicao = array ( );
    if ($oDaoRequisicaoItens->numrows > 0) {
      for($iInd = 0; $iInd < $oDaoRequisicaoItens->numrows; $iInd ++) {

        $oItem                     = db_utils::fieldsMemory($rsReqItem, $iInd, false, false, $this->getEncode());
        $oMaterialAlmoxarifado     = new MaterialAlmoxarifado($oItem->m41_codmatmater);
        $oAlmoxarifado             = new Almoxarifado($oItem->m91_depto);
        $oItem->pontopedido        = $oMaterialAlmoxarifado->getPontoDePedidoNoAlmoxarifado($oAlmoxarifado);
        $oItem->avisarpontopedido  = ControleEstoque::itemEstaNoPontoPedido($oMaterialAlmoxarifado, $oAlmoxarifado);
        $aItensRequisicao[] = $oItem;
      }
      if ($iCodItem != '' || $iCodigoMaterial != "") {

        return $aItensRequisicao [0];

      } else {
        return $aItensRequisicao;
      }
    } else {
      return false;
    }

  }

  public function getInfo() {

    return $this->oDadosRequisacao;
  }

  /**
   * Atende a requisicao, e faz as baixas no estoque
   * @method  atenderRequisicao;
   * @param integer $iCodtipo tipo da Requisição
   * @param array $aItensRequisicao array de objetos no formato {indice=>valor}(iCodMater iCodItemReq, nQtde ,iCodAlmox)
   * @param integer $iCodAlmox codigo do almoxarifado(depto)
   * @param integer $iCodAtend codigo do atendimento, caso houver
   * @return void
   */
  function atenderRequisicao($iCodtipo, $aItensRequisicao, $iCodAlmox, $iCodAtend = null) {
    /**
     * Devemos estar dentro de uma transaçao com o banco.
     */
    if (! db_utils::inTransaction()) {

      throw new Exception("Não existe transação ativa. Operação Cancelada");

    }
    if (empty($iCodtipo)) {

      throw new Exception("Parametro iCodtipo nulo.");

    }
    if (! is_array($aItensRequisicao)) {

      throw new Exception("Parametro aItensRequisicao deve ser um array.");

    }
    if (count($aItensRequisicao) == 0) {

      throw new Exception("Nenhum item para ser atendido.");

    }


    $dData     = date("Y-m-d", db_getsession("DB_datausu"));
    $iCodDepto = db_getsession("DB_coddepto");
    $tHora     = date("H:i");
    $iUsuario  = db_getsession("DB_id_usuario");

    /**
     * Bloqueamos todos os itens da Requisicao
     */
    if (! class_exists("materialEstoque")) {

    	require 'classes/materialestoque.model.php';

    }

    foreach ($aItensRequisicao as $oItem) {

      MaterialEstoque::bloqueioMovimentacaoItem($oItem->iCodMater, $iCodDepto);
      $oItemRequisicao = $this->getItens(null, $oItem->iCodMater);
      //throw new Exception("Quantidade na Requisição: {$oItemRequisicao->m41_quant}\n, Quantidade Atendida: {$oItemRequisicao->totalatendido}, Quantidade Solicitada: {$oItem->nQtde}");
      if ($oItemRequisicao != '') {
        if ($oItemRequisicao->totalatendido + $oItem->nQtde > $oItemRequisicao->m41_quant) {

          $sMensagem  = "Não Foi possível efetuar o atendimento. ";
          $sMensagem .= "Item {$oItemRequisicao->m60_descr} não possui mais saldo para atendimento.\n";
          $sMensagem .= "Quantidade na Requisição: {$oItemRequisicao->m41_quant}\n";
          $sMensagem .= "Quantidade Atendida: {$oItemRequisicao->totalatendido}\n";
          $sMensagem .= "Quantidade Solicitada: {$oItem->nQtde}\n";
          throw new Exception($sMensagem);
        }
      }
    }
    /**
     * validamos se os itens da Requisicao Possuem saldo nos itens para realizar o atendimento
     */
    $this->getDados();
    $dDataRequi = $this->oDadosRequisacao->m40_data;
    $tHoraRequi = $this->oDadosRequisacao->m40_hora;

    if ($dData < $dDataRequi) {

      throw new Exception("Não Foi possível efetuar o atendimento. A data atual é anterior a data da requisição");


    } else {

      if ($dData == $dDataRequi) {

        if ($tHora < $tHoraRequi) {

          throw new Exception("Não Foi possível efetuar o atendimento. A hora atual é anterior a hora da requisição");
        }
      }

    }

    /**
     * salvamos o atendimento, nas tabelas:atendrequi, e os itens atendidos na atendrequitem
     * 1ª tabela : atendRequi (atendimento de requisicao;)
     *
     */
    if ($iCodAtend == null) {

      $oDaoAtendrequi            = db_utils::getDao("atendrequi");
      $oDaoAtendrequi->m42_data  = $dData;
      $oDaoAtendrequi->m42_depto = $iCodDepto;
      $oDaoAtendrequi->m42_hora  = $tHora;
      $oDaoAtendrequi->m42_login = $iUsuario;

      $oDaoAtendrequi->incluir(null);
      $iCodigoAtendimento = $oDaoAtendrequi->m42_codigo;
      if($oDaoAtendrequi->erro_status == 0){

        $sErro = "Erro[1] - Não Foi possível efetuar o atendimento.\nErro Técnico: \n{$oDaoAtendrequi->erro_msg}";
        throw new Exception($sErro);
        return false;


      }
    }else{
       $iCodigoAtendimento = $iCodAtend;
    }

    /**
     * iniciamos o movimento no estoque, gravando na matestoqueini
     * com o codigo do tipo passado para o atendimento
     */

    $oDaoMatestoqueIni               = db_utils::getDao("matestoqueini");
    $oDaoMatestoqueIni->m80_login    = $iUsuario;
    $oDaoMatestoqueIni->m80_data     = $dData;
    $oDaoMatestoqueIni->m80_hora     = date('H:i:s');
    $oDaoMatestoqueIni->m80_obs      = "Atendimento de requisicao";
    $oDaoMatestoqueIni->m80_codtipo  = $iCodtipo;
    $oDaoMatestoqueIni->m80_coddepto = $iCodDepto;
    $oDaoMatestoqueIni->incluir(null);
    $iCodMatEstoqueIni = $oDaoMatestoqueIni->m80_codigo;
    if($oDaoMatestoqueIni->erro_status == 0){

      $sMsgErro  = "Erro[2] - Não Foi possível Iniciar a movimentação no estoque).";
      $sMsgErro .= "\nErro Técnico: \n{$oDaoMatestoqueIni->erro_msg}";
      throw new Exception($sMsgErro);
    }

    /**
     * Atualizamos o saldo no estoque, na tabela matestoqueitem,
     * aqui, instaciamos o modelo materialEstoque,  para realizar o rateio
     * dos itens conforme a regra do mesmo, e também obedecendo  o rateio feito pelo usuário
     *
     * 1º) Incluimos os itens atendidos , na tabela atendrequiitem
     */
    $iTotItens = count($aItensRequisicao);

    for($iInd = 0; $iInd < $iTotItens; $iInd ++) {

      /**
       * quantidade de itens no atendimento
       */
      $nTotalAtendido = 0;

      /**
       * Total De itens Salvos na matestoqueinimei.
       */
      $nTotalIniMei = 0;
      if ($aItensRequisicao [$iInd]->nQtde  < 0) {

         $sMsgErro  = "Erro[-1] - Não Foi possível efetuar o atendimento do ";
         $sMsgErro .= "material({$oItemAtual->m41_codmatmater}).\nItem possui quantidade Negativa!";
        throw new Exception($sMsgErro);
      }
      $oItemAtual                              = $this->getItens($aItensRequisicao [$iInd]->iCodItemReq);
      $oDaoAtendrequiItem                      = db_utils::getDao("atendrequiitem");
      $oDaoAtendrequiItem->m43_codatendrequi   = $iCodigoAtendimento;
      $oDaoAtendrequiItem->m43_codmatrequiitem = $aItensRequisicao[$iInd]->iCodItemReq;
      $oDaoAtendrequiItem->m43_quantatend      = $aItensRequisicao[$iInd]->nQtde;
      $nTotalAtendido                          = $aItensRequisicao [$iInd]->nQtde;
      $oDaoAtendrequiItem->incluir(null);

      if ($oDaoAtendrequiItem->erro_status == 0) {

        $sMsgErro  = "Erro[3] - Não Foi possível efetuar o atendimento do material({$oItemAtual->m41_codmatmater}).";
        $sMsgErro .= "\nErro Técnico: \n{$oDaoAtendrequiItem->erro_msg}";
        throw new Exception($sMsgErro);
      }
      /**
       * Atualizamos a matestoqueitem.
       * primeiro, temos que descobrir os lotes do item (se existir).
       * o metodo ratearLotes do modelo materialEstoque realiza esse trabalho,
       * onde ele ira retornar um array contendo as informações necessárias para
       * fazermos a baixa do estoque.
       */
      if (! class_exists("materialEstoque")) {
        require 'classes/materialestoque.model.php';
      }

      $oMaterialEstoque = new materialEstoque($oItemAtual->m41_codmatmater);
      $aItemsEstoque    = $oMaterialEstoque->ratearLotes($aItensRequisicao [$iInd]->nQtde, null, $iCodAlmox);

      /**
       * Percorremos os lotes ou itens existes no estoque, para o material escolhido,
       * para realizar a Baixa.
       */
      for($iIndEst = 0; $iIndEst < count($aItemsEstoque); $iIndEst ++) {


        if ($aItemsEstoque [$iIndEst]->rateio > 0) {

          $oDaoMatestoqueItem = db_utils::getDao("matestoqueitem");
          $nQuantidade        = $aItemsEstoque [$iIndEst]->m71_quantatend + $aItemsEstoque [$iIndEst]->rateio;

          $oDaoMatestoqueItem->m71_quantatend = "$nQuantidade";
          $oDaoMatestoqueItem->m71_codlanc    = $aItemsEstoque [$iIndEst]->m71_codlanc;
          $oDaoMatestoqueItem->alterar($aItemsEstoque [$iIndEst]->m71_codlanc);
          if ($oDaoMatestoqueItem->erro_status == 0) {

            $sMsgErro  = "Erro[4] - Não Foi possível atualizar saldo do estoque do ";
            $sMsgErro .= "material({$oItemAtual->m41_codmatmater}).\nErro Técnico: \n{$oDaoMatestoqueItem->erro_msg}";
            throw new Exception($sMsgErro);
          }

          /**
           * incluimos na tabela AtentRequiItemMEI (Ligacao AtendRequiItem e MatEstoqueItem)
           *
           */

          $oDaoAtendRequiItemMei                        = db_utils::getDao("atendrequiitemmei");
          $oDaoAtendRequiItemMei->m44_codatendreqitem   = $oDaoAtendrequiItem->m43_codigo;
          $oDaoAtendRequiItemMei->m44_codmatestoqueitem = $aItemsEstoque [$iIndEst]->m71_codlanc;

          $oDaoAtendRequiItemMei->m44_quant = $aItemsEstoque [$iIndEst]->rateio;
          $oDaoAtendRequiItemMei->incluir(null);
          if ($oDaoAtendRequiItemMei->erro_status == 0) {

            $sMsgErro  = "Erro[5] - Não Foi possível atualizar saldo do estoque ";
            $sMsgErro .= "do material({$oItemAtual->m41_codmatmater}).\n";
            $sMsgErro .= "Erro Técnico: \n{$oDaoAtendRequiItemMei->erro_msg}";
            throw new Exception($sMsgErro);
          }

          $oDaoMatEstoqueIniMei                     = db_utils::getDao("matestoqueinimei");
          $oDaoMatEstoqueIniMei->m82_matestoqueitem = $aItemsEstoque [$iIndEst]->m71_codlanc;
          $oDaoMatEstoqueIniMei->m82_matestoqueini  = $iCodMatEstoqueIni;
          $oDaoMatEstoqueIniMei->m82_quant          = $aItemsEstoque[$iIndEst]->rateio;

          $nTotalIniMei += $aItemsEstoque [$iIndEst]->rateio;
          $oDaoMatEstoqueIniMei->incluir(null);
          if ($oDaoMatEstoqueIniMei->erro_status == '0') {

            $sMsgErro  = "Erro[6] - Não Foi possível atualizar saldo do estoque do ";
            $sMsgErro .= "material({$oItemAtual->m41_codmatmater}).\nErro Técnico: \n{$oDaoMatEstoqueIniMei->erro_msg}";
            throw new Exception($sMsgErro);
          }
          /*
           * Caso exista no material um centro de custo definido ,
           * incluimos a na tabele cuscustoapropria
           */

          if (isset($aItensRequisicao[$iInd]->iCentroDeCusto) && $aItensRequisicao[$iInd]->iCentroDeCusto != "") {

            $nValorSaida = round((($aItemsEstoque[$iIndEst]->m70_valor *$aItemsEstoque[$iIndEst]->rateio)
                                 /$aItemsEstoque[$iIndEst]->m70_quant),2);

            $oDaoCustoApropria                           = db_utils::getDao("custoapropria");
            $oDaoCustoApropria->cc12_custocriteriorateio = $aItensRequisicao [$iInd]->iCentroDeCusto;
            $oDaoCustoApropria->cc12_matestoqueinimei    = $oDaoMatEstoqueIniMei->m82_codigo;
            $oDaoCustoApropria->cc12_qtd                 = "{$aItemsEstoque[$iIndEst]->rateio}";
            $oDaoCustoApropria->cc12_valor               = "{$nValorSaida}";
            $oDaoCustoApropria->incluir(null);
            if ($oDaoCustoApropria->erro_status == 0) {
              $sMsgErro = "Erro[5] - Não Foi possível apropriar custos do material({$this->iCodigoMater}).";
              throw new Exception($sMsgErro);

            }
          }
          $oMatEstoqueIniMeiARI                          = db_utils::getDao("matestoqueinimeiari");
          $oMatEstoqueIniMeiARI->m49_codatendrequiitem   = $oDaoAtendrequiItem->m43_codigo;
          $oMatEstoqueIniMeiARI->m49_codmatestoqueinimei = $oDaoMatEstoqueIniMei->m82_codigo;
          $oMatEstoqueIniMeiARI->incluir(null);
          if ($oMatEstoqueIniMeiARI->erro_status == 0) {

            $sMsgErro  = "Erro[7] - Não Foi possível atualizar saldo do estoque do ";
            $sMsgErro .= "material({$aItensRequisicao->iCodMater}).\nErro Técnico: \n{$oMatEstoqueIniMeiARI->erro_msg}";
            throw new Exception($sMsgErro);
          }

          $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
          $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
          $lPossuiIntegracao = ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao);

          /**
           * Realizamos os lancamentos contabeis da requisicao
           * Verifica se há integração da contabilidade com o material
           */
          if (USE_PCASP && $lPossuiIntegracao) {

            $nValorAtendimentoItem = round($oMaterialEstoque->getPrecoMedio() * $oDaoMatEstoqueIniMei->m82_quant, 2);

            /**
             * Não efetuar lançamento contabil quando o valor do item for menor que 0.01 centavos
             */
            if ($nValorAtendimentoItem >= 0.01) {
              $this->processarLancamento($oMaterialEstoque, $oDaoMatEstoqueIniMei->m82_codigo, $nValorAtendimentoItem);
            }
          }

          $oMaterialEstoque->cancelarLoteSession();
        }
      }

      if (round($nTotalAtendido, 2) != round($nTotalIniMei, 2)) {

         $sSqlMenu  = "select fc_montamenu(funcao) as menu ";
        $sSqlMenu .= "  From db_itensmenu where id_item = ".db_getsession("DB_itemmenu_acessado");
         $rsMenu    = db_query($sSqlMenu);

         $sDescricaoMenu = '';
         if (pg_num_rows($rsMenu) > 0) {

           $oMenu          = db_utils::fieldsMemory($rsMenu, 0);
           $sDescricaoMenu = $oMenu->menu;
         }

        $sMsgErro  = "Não existe saldo para atender o item da Requisição: {$aItensRequisicao[$iInd]->iCodItemReq}.\n";
        $sMsgErro .= "Saldo no Estoque: {$nTotalIniMei}.\n";
        $sMsgErro .= "Quantidade Solicitada: {$nTotalAtendido}.\n";
        $sMsgErro .= "Menu Acessado: {$sDescricaoMenu}.\n";
         throw new Exception($sMsgErro);
      }

    }
    return true;
  }

  /*
   * Função que traz os itens da sua respectiva atendimento da requisicao manual
   * Complementa a merenda escolar
  */
  function getDadosPedidoRequisicao() {

    $oDaoMatRequisicao = db_utils::getDao("matrequi");
    $sSqlMatRequi      = $oDaoMatRequisicao->sql_query_almox($this->icodReq);
    $rsMatRequi        = $oDaoMatRequisicao->sql_record($sSqlMatRequi);
    if($oDaoMatRequisicao->numrows == 1){

      $this->oDadosRequisacao = db_utils::fieldsMemory($rsMatRequi, 0, false, false, $this->getEncode());
      return true;

    } else {
      return false;
    }
  }

  /*
   * Função que traz os itens da sua respectiva atendimento da requisicao manual
   * Complementa a merenda escolar
  */
  public function getItensPedidoRequisicao($iCodItem = null) {

    $sWhere = '';
    if($iCodItem != ''){
      $sWhere = " and m41_codigo = {$iCodItem}";
    }
    $sSql                = "select m41_codigo from matrequiitem where m41_codmatrequi = ".$this->icodReq;
    $rsResult            = db_query($sSql);

    $oDaoRequisicaoItens = db_utils::getDao("matrequiitem");
    $sCampos             = " distinct m41_codigo,m41_codmatmater,m60_descr,m61_descr,m41_quant, ";
    $sCampos            .= " m70_quant as qtdeestoque, ";
    $sCampos            .= " (select coalesce(sum(m43_quantatend),0) ";
    $sCampos            .= "  from atendrequiitem ";
    $sCampos            .= "   left join matestoquedevitem on atendrequiitem.m43_codigo =  m46_codatendrequiitem ";
    $sCampos            .= "  where m43_codmatrequiitem = m41_codigo)as totalAtendido, ";
    $sCampos            .= " coalesce(m41_quant ";
    $sCampos            .= "          - ";
    $sCampos            .= "          ((select coalesce(sum(m43_quantatend),0) ";
    $sCampos            .= "            from atendrequiitem ";
    $sCampos            .= "            left join matestoquedevitem on atendrequiitem.m43_codigo = ";
    $sCampos            .= "            m46_codatendrequiitem";
    $sCampos            .= "            where m43_codmatrequiitem = m41_codigo)";
    $sCampos            .= "          +(select coalesce(sum(m103_quantanulada),0) ";
    $sCampos            .= "            from matanulitem ";
    $sCampos            .= "            left join matanulitemrequi on matanulitemrequi.m102_matanulitem = ";
    $sCampos            .= "             matanulitem.m103_codigo";
    $sCampos            .= "            where  m102_matrequiitem = m41_codigo)) ";
    $sCampos            .= "         ,0) as qtdpendente, ";
    $sCampos            .= " coalesce((select sum(m103_quantanulada) ";
    $sCampos            .= "           from matanulitem ";
    $sCampos            .= "            left join matanulitemrequi on matanulitemrequi.m102_matanulitem =
                                       matanulitem.m103_codigo";
    $sCampos            .= "           where  m102_matrequiitem = m41_codigo),0) as qtdanulada, ";
    $sCampos            .= " (select m103_motivo ";
    $sCampos            .= "           from matanulitem ";
    $sCampos            .= "            left join matanulitemrequi on matanulitemrequi.m102_matanulitem =
                                       matanulitem.m103_codigo";
    $sCampos            .= "           where  m102_matrequiitem = m41_codigo limit 1) as motivo ";



    $sGroupBy             = " group by m41_codigo, m41_codmatmater,m60_descr,m61_descr,m41_quant,m60_controlavalidade,";
    $sGroupBy            .= " m70_quant,cc08_sequencial, cc08_descricao";
    $sSqlreqItensanulacao = $oDaoRequisicaoItens->sql_query_estoque_anul(null,
                                                                    $sCampos,
                                                                    null,
                                                                    " m70_coddepto = m91_depto
                                                                   and m41_codmatrequi = " . $this->getIcodReq() . "
                                                                    {$sWhere} $sGroupBy");
    $rsReqItem            = $oDaoRequisicaoItens->sql_record($sSqlreqItensanulacao);
    $aItensRequisicao     = array();
    if ($oDaoRequisicaoItens->numrows > 0) {

      for($iInd = 0; $iInd < $oDaoRequisicaoItens->numrows; $iInd ++) {

        $aItensRequisicao [] = db_utils::fieldsMemory($rsReqItem, $iInd, false, false, $this->getEncode());

      }

      if($iCodItem!=''){

        return $aItensRequisicao[0];

      }else{

        return $aItensRequisicao;

      }

    } else {

      return false;

    }

  }

  /**
   * Realiza os lancamentos contabeis de estoque
   * @param integer $iCodigoDocumento Codigo do documento a ser lancado
   * @param LancamentoAuxiliarMovimentacaoEstoque $oLancamentoAuxiliar Lancamento auxiliar com os dados para la
   */
  protected function executarLancamentosContabeis ($iCodigoDocumento,
                                                   LancamentoAuxiliarMovimentacaoEstoque $oLancamentoAuxiliar,
                                                   $dtLancamento) {

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
    $oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    if($iCodigoDocumentoExecutar == 401){
      $oLancamentoAuxiliar->setSaida(false);
    }else{
      $oLancamentoAuxiliar->setSaida(true);
    }

    $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dtLancamento);
  }

  /**
   * Processa os lancamentos contabeis do atendimento da requisicao.
   * @param MaterialEstoque $oMaterial Material do estoque
   * @param $iCodigoMovimentacao  Codigo do movimento do estoque
   * @param float $nValorLancamento valor total do atendimento
   */
  public function processarLancamento(MaterialEstoque $oMaterial, $iCodigoMovimentacao, $nValorLancamento, $dtLancamento = null) {

  	if ( empty($nValorLancamento) || $nValorLancamento == 0 ){
  		throw new BusinessException("Valor do lancamento não informado ou igual a 0 !");
  	}

  	if (empty($dtLancamento)) {
  	  $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
  	}

    $oEventoContabil = new EventoContabil(400, db_getsession("DB_anousu"));
    $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
    if (count($aLancamentos) == 0 ) {

      $sMensagem = "Não existe lançamentos para o evento 400 - {$oEventoContabil->getDescricaoDocumento()}";
      throw new BusinessException($sMensagem);
    }

    $iCodigoHistorico           = $aLancamentos[0]->getHistorico();
    $oLancamentoAuxiliarEstoque = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliarEstoque->setCodigoMovimentacaoEstoque($iCodigoMovimentacao);
    $oLancamentoAuxiliarEstoque->setValorTotal($nValorLancamento);
    $sHistoricoLancamento = "Lançamento contábil referente a atendimento da requisição {$this->icodReq}";
    $oLancamentoAuxiliarEstoque->setObservacaoHistorico($sHistoricoLancamento);
    $oLancamentoAuxiliarEstoque->setHistorico($iCodigoHistorico);
    $oLancamentoAuxiliarEstoque->setMaterial($oMaterial);
    $oLancamentoAuxiliarEstoque->setSaida(true);
    if ($oMaterial->getGrupo() != null) {
      $oLancamentoAuxiliarEstoque->setContaPcasp($oMaterial->getGrupo()->getConta());
    }
    $this->executarLancamentosContabeis(400, $oLancamentoAuxiliarEstoque, $dtLancamento);
  }

  /**
   * Realiza o estorno dos lancamentos do estorno
   * @param MaterialEstoque $oMaterial Material do estoque
   * @param integer $iCodigoMovimentacao codigo da movimentacao do estoque
   * @param float $nValorLancamento valor do lancamento
   */
  public function estornarLancamento(MaterialEstoque $oMaterial, $iCodigoMovimentacao, $nValorLancamento, $dtLancamento=null) {

    if (empty($dtLancamento)) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }

    $oEventoContabil = new EventoContabil(400, db_getsession("DB_anousu"));
    $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
    if (count($aLancamentos) == 0 ) {

      $sMensagem = "Não existe lançamentos para o evento 400 - {$oEventoContabil->getDescricaoDocumento()}";
      throw new BusinessException($sMensagem);
    }
    $iCodigoHistorico           = $aLancamentos[0]->getHistorico();
    $oLancamentoAuxiliarEstoque = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliarEstoque->setCodigoMovimentacaoEstoque($iCodigoMovimentacao);
    $oLancamentoAuxiliarEstoque->setValorTotal($nValorLancamento);

    $sHistoricoLancamento  = "Lançamento contábil referente a cancelamento ";
    $sHistoricoLancamento .= "de atendimento de requisição {$this->icodReq}";
    $oLancamentoAuxiliarEstoque->setObservacaoHistorico($sHistoricoLancamento);
    $oLancamentoAuxiliarEstoque->setHistorico($iCodigoHistorico);
    $oLancamentoAuxiliarEstoque->setMaterial($oMaterial);
    if ($oMaterial->getGrupo() != null) {
      $oLancamentoAuxiliarEstoque->setContaPcasp($oMaterial->getGrupo()->getConta());
    }
    $this->executarLancamentosContabeis(401, $oLancamentoAuxiliarEstoque, $dtLancamento);
  }

  /**
   * Anulacao de item de Requisicao não atendidos.
   * @param integer $iCodigoItemRequisicao
   * @param float $nQuantidadeAnulada
   * @param string $sMotivo
   * @throws Exception
   * @return boolean
   */
  public function anularItemRequisicao($iCodigoItemRequisicao, $nQuantidadeAnulada, $sMotivo = null) {

    $oDaoMatAnulItem                    = db_utils::getDao("matanulitem");
    $oDaoMatAnulItem->m103_id_usuario   = db_getsession("DB_id_usuario");
    $oDaoMatAnulItem->m103_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoMatAnulItem->m103_hora         = db_hora();
    $oDaoMatAnulItem->m103_motivo       = $sMotivo;
    $oDaoMatAnulItem->m103_quantanulada = $nQuantidadeAnulada;
    $oDaoMatAnulItem->m103_tipoanu      = 9;
    $oDaoMatAnulItem->incluir(null);
    if($oDaoMatAnulItem->erro_status == 0){

      $sErroMsg = $oDaoMatAnulItem->erro_msg;
      throw new Exception("Erro durante inclusão na tabela matanulaitem [$sErroMsg]");
    }
    $oDaoMatAnulItemRequi                    = db_utils::getDao("matanulitemrequi");
    $oDaoMatAnulItemRequi->m102_matanulitem  = $oDaoMatAnulItem->m103_codigo;
    $oDaoMatAnulItemRequi->m102_matrequiitem = $iCodigoItemRequisicao;
    $oDaoMatAnulItemRequi->incluir(null);
    if($oDaoMatAnulItemRequi->erro_status == 0){

      $sErroMsg = $oDaoMatAnulItemRequi->erro_msg;
      throw new Exception("Erro durante inclusão na tabela matanulitemrequi [$sErroMsg]");
      $lSqlErro = true;

    }
    return true;
  }
}
?>