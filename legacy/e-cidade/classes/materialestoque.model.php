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

require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("std/db_stdClass.php"));
/**
 * Modelo para controle de itens do estoque
 * @author Iuri Guntchnigg $Author: dbmatheus.felini $
 * @version  $Revision: 1.59 $
 */
class materialEstoque {

  /**
   * Codigo do material
   *
   * @var integer
   */
  private $iCodigoMater = null;

  /**
   * Objeto Dao da classe matmater.
   *
   * @var object
   */
  private $oDaoMater = null;

  /**
   * Dados do material
   * @var stdClass
   */
  private $oDados = false;

  /**
   * Criterio de Rateio
   * @var integer
   */
  private $iCriterioRateio = null;

  /**
   * Departamento do material
   */
  private $iCodDepto = null;

  /**
   * Grupo do Material
   * @var MaterialGrupo
   */
  protected $oMaterialGrupo;


  /**
   * Desdobramento do material;
   * é uma instancia da Plano
   * @var ContaOrcamento
   */
  protected $oDesdoramento;
  /**
   * Construtor
   *
   * @param integer [$iCodMater Codigo do material] caso preenchido, retorna os dados do item. senao, inclui um novo
   *
   */
  function __construct($iCodMater = null) {

    $this->oDaoMater = db_utils::getDao ( "matmater" );
    if ($iCodMater != null) {

      $this->iCodigoMater = $iCodMater;
      $this->getDados ();
    }
    if ( empty($this->iCodDepto) ) {
      $this->iCodDepto = db_getsession ( "DB_coddepto" );
    }
  }
  /**
   * @return unknown
   */
  public function getCodDepto() {
    return $this->iCodDepto;
  }

  /**
   * @param unknown_type $iCodDepto
   */
  public function setCodDepto($iCodDepto) {
    $this->iCodDepto = $iCodDepto;
  }

  /**
   * Seta o criterio de rateio
   *
   * @param intger $iCriterio
   */
  public function setCriterioRateioCusto($iCriterio) {
    $this->iCriterioRateio = $iCriterio;

  }

  public function getCriterioRateio() {
    return $this->iCriterioRateio;
  }
  /**
   * Retorna o codigo do material
   *
   * @access public
   * @return integer
   */
  public function getcodMater() {
    return $this->iCodigoMater;
  }

  /**
   * retorna o codigo do movimento gerado
   *
   * @return string
   */
  function getICodMovimento() {
    if (isset ( $this->iCodigoMovimento )) {
      return $this->iCodigoMovimento;
    } else {
      return false;
    }
  }
  /**
   * Retorna as informações do material
   * @access public
   * @return Object
   */
  function getDados() {

    if ($this->iCodigoMater != null && empty($this->oDados)) {

      $rsMatMater = $this->oDaoMater->sql_record ($this->oDaoMater->sql_query_com_pcmater($this->getcodMater()));
      $iNumRows   = $this->oDaoMater->numrows;
      if ($this->oDaoMater->numrows >= 1) {

        $this->oDados = db_utils::fieldsMemory ( $rsMatMater, 0 );
      } else {
        return false;
      }
    }
    return $this->oDados;
  }

  /**
   * retorna o saldo do item no estoque
   *
   * @param integer [$iCodDepto codigo do departamento]
   * @param integer [$iCodEstoque codigo do estoque]
   * @param integer [$icodLote codigo do lote]
   * @param boolean [$lGroup  Traz o material agrupado]
   *
   * @return array
   */

  function getSaldoItens($iCodDepto = null, $iCodEstoque = null, $icodLote = null, $lGroup = false) {

    if ($this->getcodMater () == null) {

      throw new Exception ( "Código do material não informado" );
      return false;

    }
    $aItens = array ();

    $oItem = $this->getDados ();
    /**
     * Ordem do itens, caso o item tenha controle de validade,
     * ordenamos pela ordem da validade.
     */
    $sOrder = "m71_codlanc";
    if (@$oItem->m60_controlavalidade == 1 || @$oItem->m60_controlavalidade == 2) {
      $sOrder = "m77_dtvalidade, m77_lote, m71_codlanc";
    }
    if (! $lGroup) {

      $sCampos  = " distinct m71_codlanc, round(m71_quant::numeric, 5) as m71_quant , m71_valor,";
      $sCampos .= " m71_codmatestoque,m60_descr,m70_codmatmater,m61_descr,";
      $sCampos .= " m77_lote,m77_dtvalidade,round(m71_quantatend::numeric, 5) as m71_quantatend,m77_sequencial,";
      $sCampos .= " coalesce(round((m71_quant - m71_quantatend)::numeric, 5), 0) as saldo,m70_quant,m70_valor";

    } else {

      $sCampos  = " distinct m70_quant , m70_valor, m60_descr,m70_codmatmater,m61_descr,";
      $sCampos .= " m60_controlavalidade";
      $sOrder   = null;

    }
    $sWhere = "m70_codmatmater = " . $this->getcodMater () . " and round(m71_quantatend::numeric, 5) < round(m71_quant::numeric, 5) ";

    if (! empty ( $iCodDepto )) {
      $sWhere .= " and m92_depto = {$iCodDepto}";
    }
    if (! empty ( $iCodEstoque )) {
      $sWhere .= " and m70_coddepto = {$iCodEstoque}";
    }
    if (! empty ( $iCodLote )) {
      $sWhere .= " and m77_sequencial = {$iCodLote}";
    }

    $sSqlSaldoItem = $this->oDaoMater->sqlQuerySaldo ( null, $sCampos, $sOrder, $sWhere );

    $rsSaldoItem = $this->oDaoMater->sql_record ( $sSqlSaldoItem );
    if ($this->oDaoMater->numrows > 0) {

      /**
       * Criamos  a colecao de itens
       */

      for($iInd = 0; $iInd < $this->oDaoMater->numrows; $iInd ++) {
        $aItens[] = db_utils::fieldsMemory ( $rsSaldoItem, $iInd );
      }
      return $aItens;
    } else {

      throw new Exception ( "Nao existe estoque para esse item" );
      return false;

    }
  }

  /**
   * Realiza o rateio automatico do item nos lotes do item
   *
   * @param numeric nsValor
   * @param integer $iCodDepto
   * @param integer $iCodEstoque
   * @return array
   */

  function ratearLotes($nValor, $iCodDepto = null, $iCodEstoque = null) {

    $aItens = $this->getSaldoItens ( $iCodDepto, $iCodEstoque );

    $nSaldoItem = $nValor;

    if (count ( $aItens ) > 0) {

      $iTotalItens = count ( $aItens );
      for($iInd = 0; $iInd < $iTotalItens; $iInd ++) {

        if (isset ( $_SESSION ["mat{$this->iCodigoMater}"] [$aItens [$iInd]->m71_codlanc] )) {

          $aItens [$iInd]->rateio = $_SESSION ["mat{$this->iCodigoMater}"] [$aItens [$iInd]->m71_codlanc];
          $nSaldoItem             = $nSaldoItem - $_SESSION ["mat{$this->iCodigoMater}"] [$aItens [$iInd]->m71_codlanc];

        } else if ($aItens [$iInd]->saldo >= $nSaldoItem) {

          $aItens [$iInd]->rateio = $nSaldoItem;
          $nSaldoItem             = 0;

        } else if ($aItens [$iInd]->saldo > 0) {

          $aItens [$iInd]->rateio = $aItens [$iInd]->saldo;
          $nSaldoItem             = $nSaldoItem - $aItens [$iInd]->saldo;

        }
      }

    }

    return $aItens;
  }
  /**
   * grava o rateio dos Lotes na sessao
   *
   * @param array $aItens
   * @return boolean
   */

  public function saveLoteSession($aItens) {

    if (isset ( $_SESSION ["mat{$this->iCodigoMater}"] )) {
      unset ( $_SESSION ["mat{$this->iCodigoMater}"] );
    }
    foreach ( $aItens as $iCodLanc ) {
      $_SESSION ["mat{$this->iCodigoMater}"] [$iCodLanc->iCodItem] = $iCodLanc->qtde;
    }
    return true;
  }
  /**
   * Cancela as informações do material guardadas em sessão
   * o metodo pode ser chamado estaticamente.
   * @param integer [$iMaterial] Código do material
   * @return boolean;
   */
  function cancelarLoteSession($iMaterial = null) {

    if ($iMaterial == null) {

      if (isset ( $_SESSION ["mat{$this->iCodigoMater}"] )) {
        unset ( $_SESSION ["mat{$this->iCodigoMater}"] );
      }
    } else {

      if (isset ( $_SESSION ["mat{$iMaterial}"] )) {
        unset ( $_SESSION ["mat{$iMaterial}"] );
      }
    }
    return true;
  }

  /**
   * realiza a transferencia do material para outros departamentos complementa merenda.
   *
   * @param float $nQuantidade quantidade a ser transferida
   * @param integer $iCodDeptoOrigem departamento de origem da transferencia
   * @param integer $iCodDeptoDestino deparamento de destino da transferencia
   * @param string  [$sObservacao Observação da transferencia;
   * @param integer  [$iCodMatPedidoItem codigo da tabela matpedidoitem;
   * @return boolean
   */
  function transferirMaterial($nQuantidade,$iCodDeptoOrigem,$iCodDeptoDestino,$iCodMovimento,$sObservacao = "",
                              $iCodMatPedidoItem = null) {

    /*
     * Verificamos se os parametros foram passados, e se existe transaçaõ ativa
     */
    if (empty($iCodDeptoDestino)) {
      throw new Exception("Departamento de destino deve ser informado.");
    }

    if (empty($iCodDeptoOrigem)) {
      throw new Exception("Departamento de origem deve ser informado.");
    }

    if ($iCodDeptoOrigem == $iCodDeptoDestino) {
      throw new Exception("Departamento de destino não pode ser o mesmo de origem.");
    }

    if (empty($nQuantidade) || $nQuantidade <= 0) {
      throw new Exception("Quantidade deve ser maior que 0 (zero).");
    }

    if (! db_utils::inTransaction()) {
      throw new Exception("Não existe transação com o banco de dados ativa.\n Operação cancelada");
    }

    materialEstoque::bloqueioMovimentacaoItem($this->iCodigoMater, $iCodDeptoOrigem);
    /*
     * Buscamos o saldo do item, já fazendo o rateio do mesmo, conforme regra esclhida pelo usuário.
     */
    $aItens = $this->ratearLotes($nQuantidade, null, $iCodDeptoOrigem);

    if (count($aItens > 0)) {

      $iTotItens      = count($aItens);
      $iCodEstoqueIni = $iCodMovimento;

      //incluimos movimentação inicial do estoque, caso nao iniciamos uma transferencia
      if ($iCodMovimento == "") {

        $oMatEstoqueIni               = db_utils::getDao("matestoqueini");
        $oMatEstoqueIni->m80_coddepto = $iCodDeptoOrigem;
        $oMatEstoqueIni->m80_codtipo  = 7; //Transferencia de Material
        $oMatEstoqueIni->m80_data     = date("Y-m-d", db_getsession("DB_datausu"));
        $oMatEstoqueIni->m80_login    = db_getsession("DB_id_usuario");
        $oMatEstoqueIni->m80_hora     = db_hora();
        $oMatEstoqueIni->m80_obs      = $sObservacao;
        $oMatEstoqueIni->incluir(null);
        $iCodEstoqueIni = $oMatEstoqueIni->m80_codigo;

        if ($oMatEstoqueIni->erro_status == 0) {

          $sErroMsg  = "Erro [1] - Não foi possível iniciar movimentação no estoque.";
          $sErroMsg .= "\nErro Tecnico:{$oMatEstoqueIni->erro_msg}";
          throw new Exception($sErroMsg);
        }

        /*
         * Incluimos a transferencia para o depto de destino
         * matestoquetransf
         */
        $oDaoMatTrans                    = db_utils::getDao ("matestoquetransf");
        $oDaoMatTrans->m83_coddepto      = $iCodDeptoDestino;
        $oDaoMatTrans->m83_matestoqueini = $iCodEstoqueIni;
        $oDaoMatTrans->incluir($iCodEstoqueIni);

        if ($oDaoMatTrans->erro_status == 0) {

          $sErroMsg  = "Erro [2] - Não foi possível iniciar movimentação no estoque.\n";
          $sErroMsg .= "Erro Técnico: {$oDaoMatTrans->erro_msg} ";
          throw new Exception($sErroMsg);
        }

        if ($iCodMatPedidoItem != null) {

          $oDaoMatPedidoTrans                     = db_utils::getDao("matpedidotransf");
          $oDaoMatPedidoTrans->m100_matpedidoitem = $iCodMatPedidoItem;
          $oDaoMatPedidoTrans->m100_matestoqueini = $iCodEstoqueIni;
          $oDaoMatPedidoTrans->incluir(null);

          if ($oDaoMatPedidoTrans->erro_status == 0) {

            $sErroMsg  = "Erro [2] - Não foi possível iniciar movimentação na matpedidotransf.\n";
            $sErroMsg .= "Erro Técnico: {$oDaoMatPedidoTrans->erro_msg} ";
            throw new Exception($sErroMsg);

          }
        }
      }

      $this->iCodigoMovimento = $iCodEstoqueIni;
      foreach ($aItens as $oMaterial) {

        if ($oMaterial->rateio > 0) {

          $oDaoMatestoqueItem                 = db_utils::getDao("matestoqueitem");
          $nQuantidade                        = $oMaterial->m71_quantatend + $oMaterial->rateio;
          $oDaoMatestoqueItem->m71_quantatend = "$nQuantidade";
          $oDaoMatestoqueItem->m71_codlanc    = $oMaterial->m71_codlanc;
          $oDaoMatestoqueItem->alterar($oMaterial->m71_codlanc);

          if ($oDaoMatestoqueItem->erro_status == 0) {

            $sMsgErro  = "Erro[3] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).\n";
            $sMsgErro .= "Erro Técnico: \n{$oDaoMatestoqueItem->erro_msg}";
            throw new Exception($sMsgErro);
          }


          $oDaoMatEstoqueIniMei                     = db_utils::getDao("matestoqueinimei");
          $oDaoMatEstoqueIniMei->m82_matestoqueitem = $oMaterial->m71_codlanc;
          $oDaoMatEstoqueIniMei->m82_matestoqueini  = $iCodEstoqueIni;
          $oDaoMatEstoqueIniMei->m82_quant          = $oMaterial->rateio;
          $oDaoMatEstoqueIniMei->incluir(null);

          if ($oDaoMatEstoqueIniMei->erro_status == 0) {

            $sMsgErro  = "Erro[4] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).";
            $sMsgErro .= "\nErro Técnico: \n{$oDaoMatEstoqueIniMei->erro_msg}";
            throw new Exception($sMsgErro);
          }

          if ($iCodMatPedidoItem != null) {

            $oDaoMatEstoqueIniMeiMatPedidoItem                       = db_utils::getDao("matestoqueinimeimatpedidoitem");
            $oDaoMatEstoqueIniMeiMatPedidoItem->m99_matpedidoitem    = $iCodMatPedidoItem;
            $oDaoMatEstoqueIniMeiMatPedidoItem->m99_matestoqueinimei = $oDaoMatEstoqueIniMei->m82_codigo;
            $oDaoMatEstoqueIniMeiMatPedidoItem->incluir(null);

            if ($oDaoMatEstoqueIniMeiMatPedidoItem->erro_status == 0) {

              $sMsgErro  = "Erro[4] - Não Foi possível atualizar matestoqueinimeipedidoitem({$this->$iCodMatPedidoItem}).";
              $sMsgErro .= "\nErro Técnico: \n{$oDaoMatEstoqueIniMeiMatPedidoItem->erro_msg}";
              throw new Exception($sMsgErro);
            }
          }

          /**
           * Adicionamos a quantidade solictada na tabela matestoquetransferencia para que
           * seja "reservada" de forma que nos relatórios consigamos chegar a quantidade real em estoque
           */
          $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
          $oDaoMatEstoqueTransferencia->m84_sequencial     = null;
          $oDaoMatEstoqueTransferencia->m84_matestoqueitem = $oMaterial->m71_codlanc;
          $oDaoMatEstoqueTransferencia->m84_matestoqueini  = $iCodEstoqueIni;
          $oDaoMatEstoqueTransferencia->m84_coddepto       = $iCodDeptoDestino;
          $oDaoMatEstoqueTransferencia->m84_valortotal     = ($oMaterial->rateio * $this->getPrecoMedio());
          $oDaoMatEstoqueTransferencia->m84_quantidade     = $oMaterial->rateio;
          $oDaoMatEstoqueTransferencia->m84_transferido    = "false";
          $oDaoMatEstoqueTransferencia->m84_ativo          = "true";

          $oDaoMatEstoqueTransferencia->incluir(null);
          if ($oDaoMatEstoqueTransferencia->erro_status == "0") {

            $sMsgErro  = "Não foi possível efetuar a reserva para o material ";
            $sMsgErro .= "{$this->oDados->m60_codmater} - {$this->oDados->m60_descr}";
            $sMsgErro .= "\n -> {$oDaoMatEstoqueTransferencia->erro_msg}";
            throw new Exception($sMsgErro);
          }
        }
      }
    }
    return $iCodEstoqueIni;
  }

  /**
   * @param                              $nQuantidade
   * @param null                         $sObservacao
   * @param bool|false                   $lServico
   * @param TipoMovimentacaoEstoque|null $oMovimentacao
   *
   * @return bool
   * @throws BusinessException
   * @throws Exception
   */
  function saidaMaterial($nQuantidade, $sObservacao = null, $lServico = false, TipoMovimentacaoEstoque $oMovimentacao = null) {

    if (empty($nQuantidade) || $nQuantidade <= 0) {
      throw new Exception("Parametro nQuantidade inválido");
    }

    if (! db_utils::inTransaction()) {
      throw new Exception("Não existe transação com o banco de dados ativa.\n Operação cancelada");
    }
    $aItens = $this->ratearLotes($nQuantidade, null, $this->getCodDepto());
    $nQuantidadeAtendida = $nQuantidade;

    $iCodigoTipoMovimentacao = $lServico ? 20 : 5;
    if (!empty($oMovimentacao) && $oMovimentacao) {

      $iCodigoTipoMovimentacao = $oMovimentacao->getCodigo();
      $sObservacao = "Saída automática de material permanente.";
    }

    if (count($aItens) > 0) {

      foreach ($aItens as $oMaterial) {

        if ($oMaterial->rateio == 0) {
          continue;
        }

        if (empty($sObservacao) || trim($sObservacao) == "") {
          $sObservacao = "Saida automatica de servico";
        }

        $oMatEstoqueIni               = db_utils::getDao("matestoqueini");
        $oMatEstoqueIni->m80_coddepto = $this->getCodDepto();
        $oMatEstoqueIni->m80_codtipo  = $iCodigoTipoMovimentacao;
        $oMatEstoqueIni->m80_data     = date("Y-m-d", db_getsession("DB_datausu"));
        $oMatEstoqueIni->m80_login    = db_getsession("DB_id_usuario");
        $oMatEstoqueIni->m80_hora     = date('H:i:s');
        $oMatEstoqueIni->m80_obs      = $sObservacao;

        $oMatEstoqueIni->incluir(null);
        $iCodEstoqueIni         = $oMatEstoqueIni->m80_codigo;
        $this->iCodigoMovimento = $iCodEstoqueIni;

        if ($oMatEstoqueIni->erro_status == 0) {

          $sErroMsg  = "Erro [1] - Não foi possível iniciar movimentação no estoque.";
          $sErroMsg .= "\nErro Tecnico:{$oMatEstoqueIni->erro_msg}";
          throw new Exception ( $sErroMsg );

        }

        $oDaoMatestoqueItem                 = db_utils::getDao("matestoqueitem");
        $nQuantidade                        = $oMaterial->m71_quantatend + $oMaterial->rateio;
        $oDaoMatestoqueItem->m71_quantatend = "$nQuantidade";
        $oDaoMatestoqueItem->m71_codlanc    = $oMaterial->m71_codlanc;
        if ($lServico) {
          $oDaoMatestoqueItem->m71_servico  = "true";
        }
        $oDaoMatestoqueItem->alterar($oMaterial->m71_codlanc);

        if ($oDaoMatestoqueItem->erro_status == 0) {

          $sMsgErro  = "Erro[3] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).\n";
          $sMsgErro .= "Erro Técnico: \n{$oDaoMatestoqueItem->erro_msg}";
          throw new Exception($sMsgErro);
        }

        $oDaoMatEstoqueIniMei                     = db_utils::getDao("matestoqueinimei");
        $oDaoMatEstoqueIniMei->m82_matestoqueitem = $oMaterial->m71_codlanc;
        $oDaoMatEstoqueIniMei->m82_matestoqueini  = $iCodEstoqueIni;
        $oDaoMatEstoqueIniMei->m82_quant          = "$oMaterial->rateio";
        $oDaoMatEstoqueIniMei->incluir(null);

        if ($oDaoMatEstoqueIniMei->erro_status == 0) {

          $sMsgErro  = "Erro[4] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).";
          $sMsgErro .= str_replace("\\n", "\n","\nErro Técnico: \n{$oDaoMatEstoqueIniMei->erro_msg}");
          throw new Exception($sMsgErro);
        }
        /**
         * Caso exista no material um centro de custo definido ,
         * incluimos a na tabele cuscustoapropria
         */

        if ($this->getCriterioRateio() != "") {
          $nValorSaida                                 = round ((($oMaterial->m70_valor * $oMaterial->rateio) /
            $oMaterial->m70_quant), 2 );
          $oDaoCustoApropria                           = db_utils::getDao("custoapropria");
          $oDaoCustoApropria->cc12_custocriteriorateio = $this->getCriterioRateio();
          $oDaoCustoApropria->cc12_matestoqueinimei    = $oDaoMatEstoqueIniMei->m82_codigo;
          $oDaoCustoApropria->cc12_qtd                 = "$oMaterial->rateio";
          $oDaoCustoApropria->cc12_valor               = "{$nValorSaida}";
          $oDaoCustoApropria->incluir(null);

          if ($oDaoCustoApropria->erro_status == 0) {

            $sMsgErro = "Erro[5] - Não Foi possível apropriar custos do material({$this->iCodigoMater}).";
            throw new Exception($sMsgErro);

          }
        }

        $oDataImplantacao      = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
        $oInstituicao          = new Instituicao(db_getsession('DB_instit'));
        $lIntegracaoFinanceiro = ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao);

        /**
         * Realizamos o lancamento Contabil
         */
        if ( !$lServico && USE_PCASP && $lIntegracaoFinanceiro && $iCodigoTipoMovimentacao != 24) {

          $nValorLancamento = round($oMaterial->rateio * $this->getPrecoMedio(), 2);
          $this->processarLancamento($oDaoMatEstoqueIniMei->m82_codigo, $nValorLancamento, $sObservacao);
        }
      }
    }
    $this->cancelarLoteSession();
    return true;
  }

  /**
   * Cancela uma retiada do estoque.
   *
   * @param  float $nQuantidade Quantidade a serdevolvida
   * @param  integer $iCodIniMei codigo do movimento da retirada.
   * @param  string $sObservacao string
   * @return boolean
   */
  function cancelarSaidaMaterial($nQuantidade, $iCodIniMei, $sObservacao = null) {
    if (empty($nQuantidade) || $nQuantidade <= 0) {

      throw new Exception("Parametro nQuantidade inválido");
      return false;

    }

    if (empty($iCodIniMei)) {

      throw new Exception("Parametro iCodEstoqueIni inválido");
      return false;

    }

    if (!db_utils::inTransaction()) {

      throw new Exception("Não existe transação com o banco de dados ativa.\n Operação cancelada");
      return false;

    }
    $oMatEstoqueIni   = db_utils::getDao("matestoqueini");
    $rsMatEstoqueItem = $oMatEstoqueIni->sql_record(
      $oMatEstoqueIni->sql_query_mater(null,
                                       "m71_codlanc,
                                                                                      m71_quant,
                                                                                      m71_quantatend,
                                                                                      m71_valor,
                                                                                      m82_quant,
                                                    		                          matestoqueini.m80_data,
                                                                                      matestoqueini.m80_codigo", "",
                                       "m82_codigo={$iCodIniMei}
                                                                                      and  (matestoqueini.m80_codtipo=5
                                                                                      or matestoqueini.m80_codtipo=20)
                                                                                      and (b.m80_codtipo<>6
                                                                                       or b.m80_codigo is null) "));
    $iNumRows         = $oMatEstoqueIni->numrows;
    if ($iNumRows > 0) {

      $oMaterial = db_utils::fieldsMemory($rsMatEstoqueItem, 0);

      if (db_strtotime(date("Y-m-d",db_getsession("DB_datausu"))) < db_strtotime($oMaterial->m80_data)) {

        $sErroMsg  = "Data da operação ".date("d/m/Y",db_getsession("DB_datausu"));
        $sErroMsg .= " anterior a data da movimentação ".db_formatar($oMaterial->m80_data,"d")."!";
        throw new Exception($sErroMsg);
      }

      /*
       * exlcuimos a apropriacao do item
       */
      $oDaoCustoApropria = db_utils::getDao("custoapropria");
      $oDaoCustoApropria->excluir(null,"cc12_matestoqueinimei =  {$iCodIniMei} ");

      if ($oDaoCustoApropria->erro_status == 0) {

        $sErroMsg = $oDaoCustoApropria->erro_msg;
        throw new Exception($sErroMsg);

      }

      //guarda o movimento de origem do lancamento.
      $oDaoMatestoquenil                    = db_utils::getDao("matestoqueinil");
      $oDaoMatestoquenil->m86_matestoqueini = $oMaterial->m80_codigo;
      $oDaoMatestoquenil->incluir(null);
      $iCodEstoqueinil = $oDaoMatestoquenil->m86_codigo;

      if ($oDaoMatestoquenil->erro_status == 0) {

        $sErroMsg = $oDaoMatestoquenil->erro_msg;
        throw new Exception($sErroMsg);

      }
      //iniciamos a movimentacao no estoque


      $oMatEstoqueIni->m80_coddepto = $this->getCodDepto();
      $oMatEstoqueIni->m80_codtipo  = 6; //saida Manual
      $oMatEstoqueIni->m80_data     = date("Y-m-d",db_getsession("DB_datausu"));
      $oMatEstoqueIni->m80_login    = db_getsession("DB_id_usuario");
      $oMatEstoqueIni->m80_hora     = db_hora();
      $oMatEstoqueIni->m80_obs      = $sObservacao;
      $oMatEstoqueIni->incluir(null);
      $iCodEstoqueIni = $oMatEstoqueIni->m80_codigo;

      if ($oMatEstoqueIni->erro_status == 0) {

        $sErroMsg  = "Erro [1] - Não foi possível iniciar movimentação no estoque.";
        $sErroMsg .= "\nErro Tecnico:{$oMatEstoqueIni->erro_msg}";
        throw new Exception($sErroMsg);

      }

      $this->iCodigoMovimento                  = $iCodEstoqueIni;
      $oDaoMatEstoqueinill                     = db_utils::getDao("matestoqueinill");
      $oDaoMatEstoqueinill->m87_matestoqueini  = $iCodEstoqueIni;
      $oDaoMatEstoqueinill->m87_matestoqueinil = $iCodEstoqueinil;
      $oDaoMatEstoqueinill->incluir($iCodEstoqueinil);

      if ($oDaoMatEstoqueinill->erro_status == 0) {

        $sErro  = "erro[2] - Não foi possível iniciar movimento do estoque.\n";
        $sErro .= "Erro Técnico: {$oDaoMatEstoqueinill->erro_msg}";
        throw new Exception($sErroMsg);

      }
      //incluimos o matestoqueinill
      $oDaoMatestoqueItem                 = db_utils::getDao("matestoqueitem");
      $nSaldoEstoque                      = $oMaterial->m71_quantatend - $nQuantidade;
      $oDaoMatestoqueItem->m71_quantatend = "$nSaldoEstoque";
      $oDaoMatestoqueItem->m71_codlanc    = $oMaterial->m71_codlanc;
      $oDaoMatestoqueItem->alterar($oMaterial->m71_codlanc);

      if ($oDaoMatestoqueItem->erro_status == 0) {

        $sMsgErro  = "Erro[3] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).\n";
        $sMsgErro .= "Erro Técnico: \n{$oDaoMatestoqueItem->erro_msg}";
        throw new Exception($sMsgErro);
        return false;

      }

      $oDaoMatEstoqueIniMei                     = db_utils::getDao("matestoqueinimei");
      $oDaoMatEstoqueIniMei->m82_matestoqueitem = $oMaterial->m71_codlanc;
      $oDaoMatEstoqueIniMei->m82_matestoqueini  = $iCodEstoqueIni;
      $oDaoMatEstoqueIniMei->m82_quant          = $nQuantidade;
      $oDaoMatEstoqueIniMei->incluir(null);

      if ($oDaoMatEstoqueIniMei->erro_status == 0) {

        $sMsgErro  = "Erro[4] - Não Foi possível atualizar saldo do estoque do material({$this->iCodigoMater}).";
        $sMsgErro .= "\nErro Técnico: \n{$oDaoMatEstoqueIniMei->erro_msg}";
        throw new Exception($sMsgErro);
        return false;

      }
    }
  }

  /**
   * anula um item do pedido ou todo o pedido de transferencia .
   *
   * @param  float $nQuantidade Quantidade a serdevolvida
   * @param  integer $iCoMater codigo do material.
   * @param  string $sObservacao string
   * @return boolean
   */
  function anularPedido($nQuantidade, $sMotivo = null, $iCodMater = null, $iMatPedidoItem = null, $iCodSol = null) {

    if(($nQuantidade == null) || ($nQuantidade == "")){

      throw new Exception("Parametro Quantidade não pode ser vazio!");
      return false;

    }
    if($nQuantidade <= 0){

      throw new Exception("Parametro Quantidade não pode ser menor ou igual a zero!");
      return false;

    }
    if(!db_utils::inTransaction()){

      throw new Exception("Não existe transação com o banco de dados ativa.\n Operação cancelada");
      return false;

    }
    $lSqlErro         = false;
    $oMatPedidoItem   = db_utils::getDao("matpedidoitem");
    $rsoMatPedidoItem = $oMatPedidoItem->sql_record($oMatPedidoItem->sql_query(null,
                                                                               "*",
                                                                               "",
                                                                               "m98_sequencial={$iMatPedidoItem}"
    ));
    $iNumRows         = $oMatPedidoItem->numrows;
    $oMaterial        = db_utils::fieldsMemory($rsoMatPedidoItem,0);
    $MatPedidoItem    = $oMaterial->m98_sequencial;
    $unid             = $oMaterial->m98_matunid;
    $matpedido        = $oMaterial->m98_matpedido;
    if($lSqlErro == false){

      $oDaoMatAnulItem                    = db_utils::getDao("matanulitem");
      $oDaoMatAnulItem->m103_id_usuario   = db_getsession("DB_id_usuario");
      $oDaoMatAnulItem->m103_data         = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoMatAnulItem->m103_hora         = db_hora();
      $oDaoMatAnulItem->m103_motivo       = $sMotivo;
      $oDaoMatAnulItem->m103_quantanulada = $nQuantidade;
      $oDaoMatAnulItem->m103_tipoanu      = 9;
      $oDaoMatAnulItem->incluir(null);

      if($oDaoMatAnulItem->erro_status == 0){

        $sErroMsg = $oDaoMatAnulItem->erro_msg;
        throw new Exception("Erro durante inclusão na tabela matanulaitem [$sErroMsg]");
        $lSqlErro = true;

      }

    }
    if($lSqlErro == false){

      $oDaoMatAnulItemPedido                     = db_utils::getDao("matanulitempedido");
      $oDaoMatAnulItemPedido->m101_matanulitem   = $oDaoMatAnulItem->m103_codigo;
      $oDaoMatAnulItemPedido->m101_matpedidoitem = $iMatPedidoItem;
      $oDaoMatAnulItemPedido->incluir(null);
      if($oDaoMatAnulItemPedido->erro_status == 0){

        $sErroMsg = $oDaoMatAnulItemPedido->erro_msg;
        throw new Exception("Erro durante inclusão na tabela matanulitempedido [$sErroMsg]");
        $lSqlErro = true;

      }

    }
    return $lSqlErro;

  }

  function anularRequisicao($nQuantidade, $sMotivo = null, $iCodMater = null, $iCodItemReq = null) {

    require_once(modification("classes/requisicaoMaterial.model.php"));
    if (($nQuantidade == null) || ($nQuantidade == "")) {

      throw new Exception("Parametro Quantidade não pode ser vazio!");
      return false;

    }

    if($nQuantidade <= 0){

      throw new Exception("Parametro Quantidade não pode ser menor ou igual a zero!");
      return false;

    }

    if(!db_utils::inTransaction()){

      throw new Exception("Não existe transação com o banco de dados ativa.\n Operação cancelada");
      return false;

    }

    $lSqlErro          = false;
    $dData             = date("Y-m-d",db_getsession("DB_datausu"));
    $iCodDepto         = db_getsession("DB_coddepto");
    $tHora             = db_hora();
    $iUsuario          = db_getsession("DB_id_usuario");
    $oDaoMatrequiItem  = db_utils::getDao("matrequiitem");
    $rsDaoMatrequiItem = $oDaoMatrequiItem->sql_record($oDaoMatrequiItem->sql_query(null,
                                                                                    "*",
                                                                                    "",
                                                                                    "m41_codigo={$iCodItemReq}"
    ));

    $iNumRows     = $oDaoMatrequiItem->numrows;
    $oMaterial    = db_utils::fieldsMemory($rsDaoMatrequiItem, 0);
    $MatrequiItem = $oMaterial->m41_codigo;
    $unid         = $oMaterial->m41_codunid;
    $codmatrequi  = $oMaterial->m41_codmatrequi;

    $oRequisicao = new RequisicaoMaterial($codmatrequi);
    $oRequisicao->anularItemRequisicao($iCodItemReq, $nQuantidade, $sMotivo);

  }

  public function getPrecoMedio($sData='', $sHora='') {


    if ($sData == '') {

      $sData = date("Y-m-d", db_getsession("DB_datausu"));
      $sHora = date("H:i:s");
    }
    $nPrecoMedio     = 0;
    $sSqlPrecoMedio  = " select m85_precomedio                                                    ";
    $sSqlPrecoMedio .= "   from  matmaterprecomedio                                               ";
    $sSqlPrecoMedio .= " where m85_precomedio > 0 and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') < ";
    $sSqlPrecoMedio .= "   to_timestamp('{$sData}' || ' ' || '{$sHora}', 'YYYY-MM-DD HH24:MI:SS')   ";
    $sSqlPrecoMedio .= "   and m85_matmater = {$this->iCodigoMater}                               ";

    $sSqlPrecoMedio .= "   and m85_coddepto = ". $this->iCodDepto ;//.db_getsession("DB_coddepto");

    $sSqlPrecoMedio .= "  order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1";

    $rsPrecoMedio = db_query($sSqlPrecoMedio);
    if (pg_num_rows($rsPrecoMedio)  > 0) {
      $nPrecoMedio = db_utils::fieldsMemory($rsPrecoMedio, 0)->m85_precomedio;
    }
    return $nPrecoMedio;
  }

  /**
   * metodo criado para retornar preco medio do material, onde nao necessita o filtro por DEPTO
   * @param string $sData
   * @param string $sHora
   * @return number
   */
  public function getPrecoMedioMaterial($sData='', $sHora='') {


    if ($sData == '') {

      $sData = date("Y-m-d", db_getsession("DB_datausu"));
      $sHora = date("H:i:s");
    }
    $nPrecoMedio     = 0;
    $sSqlPrecoMedio  = " select m85_precomedio                                                    ";
    $sSqlPrecoMedio .= "   from  matmaterprecomedio                                               ";
    $sSqlPrecoMedio .= " where m85_precomedio > 0 and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') < ";
    $sSqlPrecoMedio .= "   to_timestamp('{$sData}' || ' ' || '{$sHora}', 'YYYY-MM-DD HH24:MI:SS')   ";
    $sSqlPrecoMedio .= "   and m85_matmater = {$this->iCodigoMater}                               ";
    //$sSqlPrecoMedio .= "   and m85_coddepto = ".db_getsession("DB_coddepto");
    $sSqlPrecoMedio .= "  order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1";

    $rsPrecoMedio = db_query($sSqlPrecoMedio);
    if (pg_num_rows($rsPrecoMedio)  > 0) {
      $nPrecoMedio = db_utils::fieldsMemory($rsPrecoMedio, 0)->m85_precomedio;
    }
    return $nPrecoMedio;
  }



  /**
   * método responsavel pelo ajuste de preços medios
   *
   * @param string $sDtAjuste        = data do ajuste
   * @param time   $tHoraAjuste      = hora do ajuste
   * @param float  $nValorPrecoMedio = novo valor
   * @param string $sMotivo          = motivo da alteração
   */
  public function ajustaPrecoMedio($sDtAjuste, $tHoraAjuste, $nValorPrecoMedio, $sMotivo ) {

    $iCodMaterial              = $this->iCodigoMater;
    $oDaoMatmaterprecomedio    = db_utils::getDao("matmaterprecomedio");
    $oDaoMatestoqueini         = db_utils::getDao("matestoqueini");
    $oDaoMatmaterprecomedioini = db_utils::getDao("matmaterprecomedioini");

    $iInstit = db_getsession('DB_instit');

    if ($sDtAjuste == null) {

      throw new Exception("Data da Alteração nao informada");
    }

    if ($nValorPrecoMedio == null) {
      throw new Exception("Novo Preço Médio nao informado");
    }
    if ($sMotivo == null) {
      throw new Exception("Motivo da Alteração nao informado");
    }
    // inserção na tabela matmaterprecomedio

    $oDaoMatmaterprecomedio->m85_matmater   =  $iCodMaterial;
    $oDaoMatmaterprecomedio->m85_instit     =  $iInstit;
    $oDaoMatmaterprecomedio->m85_hora       =  '00:00:01';
    $oDaoMatmaterprecomedio->m85_data       =  $sDtAjuste;
    $oDaoMatmaterprecomedio->m85_precomedio =  $nValorPrecoMedio;
    $oDaoMatmaterprecomedio->incluir(null);

    $iCodMatmaterprecomedio = $oDaoMatmaterprecomedio->m85_sequencial; // retorna o ultimo sequencial inserido

    if ($oDaoMatmaterprecomedio->erro_status == 0) {

      $sErroMsg = $oDaoMatmaterprecomedio->erro_msg;
      throw new Exception("Erro durante inclusão na tabela matmaterprecomedio [$sErroMsg]");
    }
    // inserção na tabela matestoqueini
    $oDaoMatestoqueini->m80_login    =  db_getsession('DB_id_usuario');
    $oDaoMatestoqueini->m80_data     =  "$sDtAjuste";
    $oDaoMatestoqueini->m80_obs      =  "$sMotivo";
    $oDaoMatestoqueini->m80_codtipo  =  22;
    $oDaoMatestoqueini->m80_coddepto =  db_getsession('DB_coddepto');
    $oDaoMatestoqueini->m80_hora     =  $tHoraAjuste;
    $oDaoMatestoqueini->incluir(null);

    $iCodMatestoqueini = $oDaoMatestoqueini->m80_codigo; // retorna o ultimo sequencial inserido
    if ($oDaoMatestoqueini->erro_status == 0) {

      $sErroMsg = $oDaoMatestoqueini->erro_msg;
      throw new Exception("Erro durante inclusão na tabela matestoqueini [$sErroMsg]");
    }
    //ligação entre os registros inseridos para a Matmaterprecomedioini
    $oDaoMatmaterprecomedioini->m88_matestoqueini      = $iCodMatestoqueini;
    $oDaoMatmaterprecomedioini->m88_matmaterprecomedio = $iCodMatmaterprecomedio;
    $oDaoMatmaterprecomedioini->incluir(null);
    if ($oDaoMatmaterprecomedioini->erro_status == 0) {

      $sErroMsg = $oDaoMatmaterprecomedioini->erro_msg;
      throw new Exception("Erro durante inclusão na tabela Matmaterprecomedioini [$sErroMsg]");
    }

    /**
     * ajustes posteriores do preço medio do item
     */
    $sSqlPosterior  = "SELECT m70_codmatmater, min(m82_codigo) as codigo_atualizar                                    ";
    $sSqlPosterior .= " from matestoqueinimei                                                                         ";
    $sSqlPosterior .= "       inner join  matestoqueitem on m71_codlanc       = m82_matestoqueitem                    ";
    $sSqlPosterior .= "       inner join  matestoque     on m71_codmatestoque = m70_codigo                            ";
    $sSqlPosterior .= "       inner join  matestoqueini  on m82_matestoqueini = m80_codigo                            ";
    $sSqlPosterior .= " where m70_codmatmater = {$iCodMaterial} and to_timestamp(m80_data || ' '                      ";
    $sSqlPosterior .= "                                                       || m80_hora,   'YYYY-MM-DD HH:MI:SS') > ";
    $sSqlPosterior .= "                    to_timestamp('{$sDtAjuste}' || ' ' || '00:00:01', 'YYYY-MM-DD HH:MI:SS')   ";
    $sSqlPosterior .= " group by 1 order by 1                                                                         ";

    $rsDadosPosterior     = db_query($sSqlPosterior);
    $iTotaLinhasPosterior = pg_num_rows($rsDadosPosterior);
    for ($iPosterior = 0; $iPosterior < $iTotaLinhasPosterior; $iPosterior++) {

      $oValorPosterior     = db_utils::fieldsMemory($rsDadosPosterior, $iPosterior);
      $sSqlUpdatePosterior = "UPDATE matestoqueinimei SET m82_quant = m82_quant where m82_codigo =
                                                                               {$oValorPosterior->codigo_atualizar}; ";
      if (!db_query($sSqlUpdatePosterior)) {
        throw new Exception("ERRO na Atualização posterior do item {$oValorPosterior->codigo_atualizar}");
      }
    }
  }

  /**
   * Retorna a conta do grupo do material
   * @return MaterialGrupo
   */
  public function getGrupo() {

    if (empty($this->oMaterialGrupo) && !empty($this->iCodigoMater)) {
      if (!empty($this->oDados->m68_materialestoquegrupo)) {
        $this->oMaterialGrupo = new MaterialGrupo($this->oDados->m68_materialestoquegrupo);
      }
    }
    return $this->oMaterialGrupo;
  }

  /**
   * retorna a conta de desdobramento vinculado ao material
   * @return ContaOrcamento
   */
  public function getDesdobramento() {

    if (!empty($this->iCodigoMater) && empty($this->oDesdoramento)) {

      $oDaoMatmater = db_Utils::getDao("matmater");
      $sWhere       = "m60_codmater    = {$this->iCodigoMater} ";
      $sWhere      .= " and c61_instit = ".db_getsession("DB_instit");
      $sSqlConta    = $oDaoMatmater->sql_query_material_desdobramento(null,
                                                                      "pc07_codele,
                                                                       c61_codcon,
                                                                       c61_reduz ",
                                                                      "pc07_codele limit 1",
                                                                      $sWhere
      );
      $rsConta = $oDaoMatmater->sql_record($sSqlConta);
      if ($oDaoMatmater->numrows > 0) {

        $oDadosConta     = db_utils::fieldsMemory($rsConta, 0);
        $iAno            = db_getsession("DB_anousu");
        $iCodigoConta    = $oDadosConta->c61_codcon;
        $iCodigoReduzido = $oDadosConta->c61_reduz;

        $this->oDesdoramento = new ContaOrcamento($iCodigoConta, $iAno, $iCodigoReduzido, db_getsession("DB_instit"));
      }
    }
    return $this->oDesdoramento;
  }
  /**
   * Realiza os lancamentos contabeis de estoque
   * @param integer $iCodigoTipoDocumento Código do Tipo do Documento que vai ser executado
   * @param LancamentoAuxiliarMovimentacaoEstoque $oLancamentoAuxiliar Lancamento auxiliar com os dados para la
   */
  protected function executarLancamentosContabeis ($iCodigoTipoDocumento,
                                                   LancamentoAuxiliarMovimentacaoEstoque $oLancamentoAuxiliar,
                                                   $dtLancamento) {

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoTipoDocumento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
    $oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dtLancamento);
  }

  /**
   * Processa os lancamentos contabeis do atendimento da requisicao.
   * @param MaterialEstoque $oMaterial Material do estoque
   * @param $iCodigoMovimentacao  Codigo do movimento do estoque
   * @param float $nValorLancamento valor total do atendimento
   */
  public function processarLancamento($iCodigoMovimentacao, $nValorLancamento, $sObservacao, $dtLancamento = null) {

    if (empty($dtLancamento)) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $oEventoContabil = new EventoContabil(404, db_getsession("DB_anousu"));
    $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
    if (count($aLancamentos) == 0 ) {

      $sMensagem = "Não existe lançamentos para o evento 404 - {$oEventoContabil->getDescricaoDocumento()}";
      throw new BusinessException($sMensagem);
    }
    $iCodigoHistorico           = $aLancamentos[0]->getHistorico();
    $oLancamentoAuxiliarEstoque = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliarEstoque->setCodigoMovimentacaoEstoque($iCodigoMovimentacao);
    $oLancamentoAuxiliarEstoque->setValorTotal($nValorLancamento);
    $oLancamentoAuxiliarEstoque->setObservacaoHistorico($sObservacao);
    $oLancamentoAuxiliarEstoque->setHistorico($iCodigoHistorico);
    $oLancamentoAuxiliarEstoque->setMaterial($this);
    $oLancamentoAuxiliarEstoque->setSaida(true);
    if ($this->getGrupo() == null) {
      throw new BusinessException("Material sem grupo informado.");
    }
    $oLancamentoAuxiliarEstoque->setContaPcasp($this->getGrupo()->getConta());
    $this->executarLancamentosContabeis(403, $oLancamentoAuxiliarEstoque, $dtLancamento);
  }

  /**
   * Retorna o saldo que se encontra em transferência para outro departamento
   * @param boolean $lDepartamento - Define se é para buscar as transferências atravez do departamento do objeto
   * @return integer
   */
  public function getSaldoTransferencia($lDepartamento = false) {

    $sWhereSaldo  = "     matestoque.m70_codmatmater = {$this->iCodigoMater}";
    if ($lDepartamento) {
      $sWhereSaldo .= " and matestoque.m70_coddepto = {$this->iCodDepto}";
    }
    $sWhereSaldo .= " and matestoquetransferencia.m84_ativo is true";
    $sWhereSaldo .= " and matestoquetransferencia.m84_transferido is false";
    $sCamposSaldo = " sum(m84_quantidade) as m84_quantidade";
    $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
    $sSqlBuscaSaldo              = $oDaoMatEstoqueTransferencia->sql_query_transferencia(null,
                                                                                         $sCamposSaldo,
                                                                                         null,
                                                                                         $sWhereSaldo);
    $rsBuscaSaldoTransferencia = $oDaoMatEstoqueTransferencia->sql_record($sSqlBuscaSaldo);
    $iQuantidade = 0;
    if ($oDaoMatEstoqueTransferencia->numrows > 0) {
      $iQuantidade = db_utils::fieldsMemory($rsBuscaSaldoTransferencia, 0)->m84_quantidade;
    }
    return $iQuantidade;
  }

  /**
   * Realiza o bloqueio das linhas da tabela matestoque, matestoqueitem
   * O método irá realizar o bloqueio das linhas das linhas do item $iCodigoItem e do Departamento $iCodigoDepartamento
   * As linhas ficaram bloqueadas até o termino da transacao (com commit, ou rollback)
   * <code>
   * db_inicio_transacao();
   * materialEstoque::bloqueioMovimentacaoItem(10, 1);
   * $oMaterial = new Material(10);
   * $oMaterial->saidaMaterial(5,'Ajuste no estoque')
   * db_fim_transacao(false);
   * </code>
   *
   * @param integer $iCodigoItem Codigo do Item do estoque
   * @param integer $iCodigoDepartamento codigo do departamento
   * @throws Exception Quando não existe transação aberta
   * * @throws Exception Quando não foi possível realizar o bloqueio das linhas
   */
  public static function bloqueioMovimentacaoItem($iCodigoItem, $iCodigoDepartamento) {

    if (!db_utils::inTransaction()) {

      $sMensagem  ='Para utilizar o método MaterialEstoque::bloqueioMovimentacaoItem, o bloco de código ';
      $sMensagem .= 'deve estar em transação';
      throw new Exception($sMensagem);
    }
    $oDaoMatestoqueitem  = new cl_matestoqueitem();

    $sWhereBloqueio  = "m70_codmatmater = {$iCodigoItem}";
    $sWhereBloqueio .= " and m70_coddepto    = {$iCodigoDepartamento}";

    $sSqlBloqueio  = $oDaoMatestoqueitem->sql_query(null, "matestoqueitem.*", null, $sWhereBloqueio);
    $sSqlBloqueio .= " for update";

    $rsBloqueio = $oDaoMatestoqueitem->sql_record($sSqlBloqueio);
    if (!$rsBloqueio) {
      throw new Exception('Erro ao selecionar itens para bloqueio');
    }
  }

  /**
   * metodo para retornar o minimo que um material deve ter em estoque em um departamento (almox)
   * @return integer
   */
  public function getEstoqueMinimo(){

    $iEstoqueMinimo     = 0;
    $oDaoMatmaterEstoque = new cl_matmaterestoque();
    $sWhereMinimo        = "    coddepto = {$this->iCodDepto}        ";
    $sWhereMinimo       .= "and m60_codmater = {$this->iCodigoMater} ";
    $sSqlMinimo          = $oDaoMatmaterEstoque->sql_queryAlmoxarifado(null,"m64_estoqueminimo", null, $sWhereMinimo);
    $rsMinimo            = $oDaoMatmaterEstoque->sql_record($sSqlMinimo);
    if ($oDaoMatmaterEstoque->numrows > 0) {
      $iEstoqueMinimo = db_utils::fieldsMemory($rsMinimo, 0)->m64_estoqueminimo;
    }

    return $iEstoqueMinimo;
  }
  /**
   * metodo para retornar o maximo que um material deve ter em estoque em um departamento (almox)
   * @return integer
   */
  public function getEstoqueMaximo(){

    $iEstoqueMaximo     = 0;
    $oDaoMatmaterEstoque = new cl_matmaterestoque();
    $sWhereMaximo        = "    coddepto = {$this->iCodDepto}        ";
    $sWhereMaximo       .= "and m60_codmater = {$this->iCodigoMater} ";
    $sSqlMaximo          = $oDaoMatmaterEstoque->sql_queryAlmoxarifado(null,"m64_estoquemaximo", null, $sWhereMaximo);
    $rsMaximo            = $oDaoMatmaterEstoque->sql_record($sSqlMaximo);
    if ($oDaoMatmaterEstoque->numrows > 0) {
      $iEstoqueMaximo = db_utils::fieldsMemory($rsMaximo, 0)->m64_estoquemaximo;
    }

    return $iEstoqueMaximo;
  }

  /**
   * metodo para retornar o ponto de pedido que um material deve ter em estoque em um departamento (almox)
   * @return integer
   */
  public function getPontoPedido(){

    $iPontoPedido        = 0;
    $oDaoMatmaterEstoque = new cl_matmaterestoque();
    $sWherePedido        = "    coddepto = {$this->iCodDepto}        ";
    $sWherePedido       .= "and m60_codmater = {$this->iCodigoMater} ";
    $sSqlPedido          = $oDaoMatmaterEstoque->sql_queryAlmoxarifado(null,"m64_pontopedido", null, $sWherePedido);
    $rsPedido            = $oDaoMatmaterEstoque->sql_record($sSqlPedido);
    if ($oDaoMatmaterEstoque->numrows > 0) {
      $iPontoPedido = db_utils::fieldsMemory($rsPedido, 0)->m64_pontopedido;
    }
    return $iPontoPedido;
  }
}
?>