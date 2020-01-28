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

require_once ("model/configuracao/DBDepartamento.model.php");

/**
 * Classe para controle de almoxarifado
 */
class Almoxarifado extends DBDepartamento {

  /**
   * Código do Almoxarifado
   * @var integer
   */
  protected $iAlmoxarifado;

  /**
   * Constrói o objeto Almoxarifado
   * @return Almoxarifado
   */
  public function __construct($iCodigoDepartamento) {

    parent::__construct($iCodigoDepartamento);
    
    $oDaoDBAlmox           = db_utils::getDao('db_almox');
    $sSqlBuscaAlmoxarifado = $oDaoDBAlmox->sql_query_file(null, "m91_codigo", null, "m91_depto = {$this->iCodigoDepartamento}");
    $rsBuscaAlmoxarifado   = $oDaoDBAlmox->sql_record($sSqlBuscaAlmoxarifado);
    
    if ($oDaoDBAlmox->erro_status == "0") {

      $sMensagemErro  = "Não foi possível localizar o código do almoxarifado para o departamento ";
      $sMensagemErro .= "{$this->iCodigoDepartamento} - {$this->sNomeDepartamento}";
      throw new Exception($sMensagemErro);
    }
    $this->iAlmoxarifado = db_utils::fieldsMemory($rsBuscaAlmoxarifado, 0)->m91_codigo;
  }

  /**
   * recebe uma transferencia de material
   *
   * @param  integer $iTransferencia código da transferencia (matestoqueini.m80_codigo)
   * @return Almoxarifado
   */
  public function receberTransferencia($iTransferencia) {

    /**
     * o sistema realiza dois movimentos, o primeiro, e a saida do estoque anterior. nesse ponto, nao movimentamos o
     * estoque, apenas vinculamos o movimento de tipo 21, aos mesmos itens utilizados na transferencia (tipo 7)
     */
    $oDaoMatestoqueIni      = new cl_matestoqueini;
    $sSqlDadosTransferencia = $oDaoMatestoqueIni->sql_query_transf($iTransferencia);
    $rsDadosTransferencia   = $oDaoMatestoqueIni->sql_record($sSqlDadosTransferencia);

    if ($oDaoMatestoqueIni->numrows == 0) {

      $sErroMsg  = "Erro ao pesquisar dados da transferencia.\n";
      $sErroMsg .= "Transferencia já realizada, ou cancelada.";
      throw new Exception($sErroMsg);
    }
    $oDadosTransferencia    = db_utils::fieldsMemory($rsDadosTransferencia, 0);
    if ($oDadosTransferencia->m86_codigo != "") {

      $sErroMsg  = "Erro ao pesquisar dados da transferencia.\n";
      $sErroMsg .= "Transferencia já realizada, ou cancelada.";
      throw new Exception($sErroMsg);
    }
    $sSqlItensTransferencia = $oDaoMatestoqueIni->sql_query_mater($iTransferencia,
                                                                  "distinct m70_codigo,
                                                                   m70_quant,
                                                                   m70_valor,
                                                                   m71_codlanc,
                                                                   m71_quant,
                                                                   (m71_valor/m71_quant) as valorunitarioitem,
                                                                   m71_quantatend,
                                                                   m82_quant,
                                                                   m77_lote,
                                                                   m77_dtvalidade,
                                                                   m77_sequencial,
                                                                   m60_codmater",
                                                                   "m71_codlanc"
                                                                );
    $rsItensTransferencia = $oDaoMatestoqueIni->sql_record($sSqlItensTransferencia);
    $iItensTransferencia  = $oDaoMatestoqueIni->numrows;

    /**
     * cria uma vinculação a transferencia, indicando que ela foi realizada
     */
    $oDaoMatestoqueInil = db_utils::getDao("matestoqueinil");
    $oDaoMatestoqueInil->m86_matestoqueini = $iTransferencia;
    $oDaoMatestoqueInil->incluir(null);
    $iCodigoLigacaoTransferencia = $oDaoMatestoqueInil->m86_codigo;
    if ($oDaoMatestoqueInil->erro_status == 0) {

      $sErroMsg = "Erro [1] - Erro ao vincular Transferencia!";
      throw new Exception($sErroMsg);
    }

    /**
     * incluimos um novo movimento, indicando que o material saiu do almoxarifado
     */
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d', db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = date("H:i:s");
    $oDaoMatestoqueIni->m80_obs      = '';
    $oDaoMatestoqueIni->m80_codtipo  = 21;
    $oDaoMatestoqueIni->m80_coddepto = $oDadosTransferencia->m80_coddepto;
    $oDaoMatestoqueIni->incluir(null);
    $ItransferenciaSaida = $oDaoMatestoqueIni->m80_codigo;
    if ($oDaoMatestoqueIni->erro_status == 0) {

      $sErroMsg = "Erro[2] - Erro ao iniciar transferencia de material. \n";
      $sErroMsg .= "Erro Técnico: {$oDaoMatestoqueIni->erro_msg}";
    }

    $oDaoMatestoqueInill = db_utils::getDao("matestoqueinill");
    $oDaoMatestoqueInill->m87_matestoqueini  = $ItransferenciaSaida;
    $oDaoMatestoqueInill->m87_matestoqueinil = $iCodigoLigacaoTransferencia;
    $oDaoMatestoqueInill->incluir($iCodigoLigacaoTransferencia);
    if ($oDaoMatestoqueInill->erro_status == 0) {

      $sErroMsg = "Erro [2] - Erro ao vincular Transferencia!";
      throw new Exception($sErroMsg);
    }

    /**
     * percorremos todos os itens da transferencia e vinculamos ao movimento 21.
     */
    $oDaoMatestoqueIniMei = db_utils::getDao("matestoqueinimei");
    for ($i = 0; $i < $iItensTransferencia; $i ++) {

      $oDadosItem  = db_utils::fieldsMemory($rsItensTransferencia, $i);
      $oDaoMatestoqueIniMei->m82_matestoqueitem = $oDadosItem->m71_codlanc;
      $oDaoMatestoqueIniMei->m82_matestoqueini  = $ItransferenciaSaida;
      $oDaoMatestoqueIniMei->m82_quant          = $oDadosItem->m82_quant;
      $oDaoMatestoqueIniMei->incluir(null);
      if ($oDaoMatestoqueIniMei->erro_status == 0) {

        $erro_msg = $oDaoMatestoqueIniMei->erro_msg;
        throw new Exception($erro_msg);
      }
      unset($oDadosItem);
    }
    /**
     * realizamos  a inclusão do movimento dpo tipo 8
     */
    /**
     * cria uma vinculação a saida da transferencia, indicando que ela foi realizada
     */
    $oDaoMatestoqueInil = db_utils::getDao("matestoqueinil");
    $oDaoMatestoqueInil->m86_matestoqueini = $ItransferenciaSaida;
    $oDaoMatestoqueInil->incluir(null);
    $iCodigoLigacaoTransferenciaSaida = $oDaoMatestoqueInil->m86_codigo;
    if ($oDaoMatestoqueInil->erro_status == 0) {

      $sErroMsg = "Erro [1] - Erro ao vincular Transferencia!";
      throw new Exception($sErroMsg);
    }

    /**
     * incluimos um novo movimento, indicando que o material saiu do almoxarifado
     */
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d', db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = date("H:i:s");
    $oDaoMatestoqueIni->m80_obs      = '';
    $oDaoMatestoqueIni->m80_codtipo  = 8;
    $oDaoMatestoqueIni->m80_coddepto = $this->iCodigoDepartamento;
    $oDaoMatestoqueIni->incluir(null);
    $ItransferenciaEntrada = $oDaoMatestoqueIni->m80_codigo;
    if ($oDaoMatestoqueIni->erro_status == 0) {

      $sErroMsg = "Erro[3] - Erro ao iniciar transferencia de material. \n";
      $sErroMsg .= "Erro Técnico: {$oDaoMatestoqueIni->erro_msg}";
    }

    $oDaoMatestoqueInill = db_utils::getDao("matestoqueinill");
    $oDaoMatestoqueInill->m87_matestoqueini  = $ItransferenciaEntrada;
    $oDaoMatestoqueInill->m87_matestoqueinil = $iCodigoLigacaoTransferenciaSaida;
    $oDaoMatestoqueInill->incluir($iCodigoLigacaoTransferenciaSaida);
    if ($oDaoMatestoqueInill->erro_status == 0) {

      $sErroMsg = "Erro [4] - Erro ao vincular Transferencia!";
      $sErroMsg = "Erro Técnico: {$oDaoMatestoqueInill->erro_msg}";
      throw new Exception($sErroMsg);
    }

    $oDaoMatEstoque         = db_utils::getDao("matestoque");
    $oDaoMatEstoqueItem     = db_utils::getDao("matestoqueitem");
    $oDaoMatEstoqueItemLote = db_utils::getDao("matestoqueitemlote");
    for ($i = 0; $i < $iItensTransferencia; $i ++) {

      $oDadosItem  = db_utils::fieldsmemory($rsItensTransferencia, $i);
      $sSqlEstoque = $oDaoMatEstoque->sql_query_file(null, "m70_codigo,m70_quant,m70_valor",
                                                     "",
                                                     "m70_codmatmater={$oDadosItem->m60_codmater}
                                                      and m70_coddepto={$this->iCodigoDepartamento}"
                                                     );
      $rsEstoque   = $oDaoMatEstoque->sql_record($sSqlEstoque);
      if ($oDaoMatEstoque->numrows > 0) {

        $oDadosEstoque  = db_utils::fieldsmemory($rsEstoque, 0);
        $iCodigoEstoque = $oDadosEstoque->m70_codigo;
        unset($oDadosEstoque);
      } else {

        $oDaoMatEstoque->m70_codmatmater = $oDadosItem->m60_codmater;
        $oDaoMatEstoque->m70_coddepto    = $this->iCodigoDepartamento;
        $oDaoMatEstoque->m70_valor       = $oDadosItem->m82_quant * $oDadosItem->valorunitarioitem;
        $oDaoMatEstoque->m70_quant       = $oDadosItem->m82_quant;
        $oDaoMatEstoque->incluir(null);
        $iCodigoEstoque = $oDaoMatEstoque->m70_codigo;
        if ($oDaoMatEstoque->erro_status == 0) {

          $erro_msg = $oDaoMatEstoque->erro_msg;
          throw new Exception($erro_msg);
        }
      }

      $oDaoMatEstoqueItem->m71_codmatestoque = $iCodigoEstoque;
      $oDaoMatEstoqueItem->m71_data          = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoMatEstoqueItem->m71_valor = $oDadosItem->valorunitarioitem * $oDadosItem->m82_quant;
      $oDaoMatEstoqueItem->m71_quant = $oDadosItem->m82_quant;
      $oDaoMatEstoqueItem->m71_quantatend = '0';
      $oDaoMatEstoqueItem->incluir(null);
      if ($oDaoMatEstoqueItem->erro_status == 0) {

        $erro_msg = $oDaoMatEstoqueItem->erro_msg;
        throw new Exception($erro_msg);
      }
      $iCodigoEstoqueItem = $oDaoMatEstoqueItem->m71_codlanc;
      if ($oDadosItem->m77_sequencial != '') {

        $oDaoMatEstoqueItemLote->m77_dtvalidade     = $oDadosItem->m77_dtvalidade;
        $oDaoMatEstoqueItemLote->m77_lote           = $oDadosItem->m77_lote;
        $oDaoMatEstoqueItemLote->m77_matestoqueitem = $iCodigoEstoqueItem;
        $oDaoMatEstoqueItemLote->incluir(null);
        if ($oDaoMatEstoqueItemLote->erro_status == 0) {

          $erro_msg = $clmatestoqueitemlote->erro_msg;
          throw new Exception($erro_msg);
        }
      }

      $oDaoMatestoqueIniMei->m82_matestoqueitem = $iCodigoEstoqueItem;
      $oDaoMatestoqueIniMei->m82_matestoqueini = $ItransferenciaEntrada;
      $oDaoMatestoqueIniMei->m82_quant = $oDadosItem->m82_quant;
      $oDaoMatestoqueIniMei->incluir(null);
      if ($oDaoMatestoqueIniMei->erro_status == 0) {

        $erro_msg = $clmatestoqueinimei->erro_msg;
        throw new Exception($erro_msg);
      }
    }

    $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
    $oDaoMatEstoqueTransferencia->m84_transferido = 'true';
    $oDaoMatEstoqueTransferencia->alterar(null, "m84_matestoqueini = {$iTransferencia}");
    if ($oDaoMatEstoqueTransferencia->erro_status == "0") {
      throw new Exception("Não foi possível alterar o status da transferência.");
    }
    return $this;
  }

  /**
   * Cancela uma transferencia não confirmada
   *
   * @param integer $iTranferencia código da transferencia
   * @return Almoxarifado
   */
  public function cancelarTransferencia ($iTransferencia) {

    /**
     * cria uma vinculação a transferencia, indicando que ela foi realizada
     */
    $oDaoMatestoqueInil = db_utils::getDao("matestoqueinil");
    $oDaoMatestoqueInil->m86_matestoqueini = $iTransferencia;
    $oDaoMatestoqueInil->incluir(null);
    $iCodigoLigacaoTransferencia = $oDaoMatestoqueInil->m86_codigo;
    if ($oDaoMatestoqueInil->erro_status == 0) {

      $sErroMsg = "Erro [1] - Erro ao vincular Transferencia!";
      throw new Exception($sErroMsg);

    }

    /**
     * incluimos um novo movimento, indicando que o material saiu do almoxarifado
     */
    $oDaoMatestoqueIni  = db_utils::getDao("matestoqueini");
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d', db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = date("H:i:s");
    $oDaoMatestoqueIni->m80_obs      = 'Transferência Cancelada';
    $oDaoMatestoqueIni->m80_codtipo  = 9;
    $oDaoMatestoqueIni->m80_coddepto = $this->iCodigoDepartamento;
    $oDaoMatestoqueIni->incluir(null);
    $ItransferenciaSaida = $oDaoMatestoqueIni->m80_codigo;
    if ($oDaoMatestoqueIni->erro_status == 0) {

      $sErroMsg = "Erro[2] - Erro ao iniciar cancelamento de transferencia de material. \n";
      $sErroMsg .= "Erro Técnico: {$oDaoMatestoqueIni->erro_msg}";
    }

    $oDaoMatestoqueInill = db_utils::getDao("matestoqueinill");
    $oDaoMatestoqueInill->m87_matestoqueini  = $ItransferenciaSaida;
    $oDaoMatestoqueInill->m87_matestoqueinil = $iCodigoLigacaoTransferencia;
//       die("\n\nCHEGOU SEM ERRO 1\n\n");
    $oDaoMatestoqueInill->incluir($iCodigoLigacaoTransferencia);
    if ($oDaoMatestoqueInill->erro_status == 0) {

      $sErroMsg = "Erro [2] - Erro ao vincular Transferencia!";
      throw new Exception($sErroMsg);
    }
    $sSqlItensTransferencia = $oDaoMatestoqueIni->sql_query_mater($iTransferencia,
                                                                  "distinct m70_codigo,
                                                                   m70_quant,
                                                                   m70_valor,
                                                                   m71_codlanc,
                                                                   m71_quant,
                                                                   (m71_valor/m71_quant) as valorunitarioitem,
                                                                   m71_quantatend,
                                                                   m82_quant,
                                                                   m77_lote,
                                                                   m77_dtvalidade,
                                                                   m77_sequencial,
                                                                   m60_codmater",
                                                                   "m71_codlanc"
                                                                );
    $rsItensTransferencia  = $oDaoMatestoqueIni->sql_record($sSqlItensTransferencia);
    $iItensTransferencia   = $oDaoMatestoqueIni->numrows;
    $oDaoMatestoqueItem    = db_utils::getDao("matestoqueitem");
    $oDaoMatestoqueIiniMei = db_utils::getDao("matestoqueinimei");
    for ($i = 0; $i <  $iItensTransferencia; $i++) {

      $oDadosItem = db_utils::fieldsMemory($rsItensTransferencia, $i);
      $nSaldo     = $oDadosItem->m71_quantatend - $oDadosItem->m82_quant;
      $oDaoMatestoqueItem->m71_codlanc    = $oDadosItem->m71_codlanc;
      $oDaoMatestoqueItem->m71_quantatend = "{$nSaldo}";
      $oDaoMatestoqueItem->alterar($oDadosItem->m71_codlanc);
      if ($oDaoMatestoqueItem->erro_status == 0) {

        $erro_msg = $oDaoMatestoqueItem->erro_msg;
        throw new Exception($erro_msg);
      }
      $oDaoMatestoqueIiniMei->m82_matestoqueitem = $oDadosItem->m71_codlanc;
      $oDaoMatestoqueIiniMei->m82_matestoqueini  = $ItransferenciaSaida;
      $oDaoMatestoqueIiniMei->m82_quant = $oDadosItem->m82_quant;
      $oDaoMatestoqueIiniMei->incluir(null);
      if ($oDaoMatestoqueIiniMei->erro_status == 0) {

        $erro_msg = $oDaoMatestoqueIiniMei->erro_msg;
        throw new Exception($erro_msg);
      }
    }

    $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
    $sWhereTransferencia         = "m84_matestoqueini = {$iTransferencia}";
    $sSqlBuscaTransferencia      = $oDaoMatEstoqueTransferencia->sql_query_file(null, "*", null, $sWhereTransferencia);
    $rsBuscaTransferencia        = $oDaoMatEstoqueTransferencia->sql_record($sSqlBuscaTransferencia);
    if ($oDaoMatEstoqueTransferencia->numrows > 0) {

      $oDaoMatEstoqueTransferencia->m84_ativo = 'false';
      $oDaoMatEstoqueTransferencia->alterar(null, $sWhereTransferencia);
      if ($oDaoMatEstoqueTransferencia->erro_status == "0") {
        throw new Exception("Não foi possível cancelar a situação da transferência.");
      }
    }
    return $this;
  }

  /**
   * Executa os Lancamentos contabeis para as movimentacoe do estoque
   * @param integer $iCodigoTipoDocumento codigo do tipo do documento
   */
  private function executarLancamentosContabeis($iCodigoTipoDocumento, $oLancamentoAuxiliar, $dtLancamento=null) {

    if (empty($dtLancamento)) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoTipoDocumento);

    /**
     * 403 é o tipo do documento de saida
     * Este tipo sempre ira retornar uma unica conta para por isso nao precisamos setar variavel de controle
     */
    if ($iCodigoTipoDocumento != 403) {
      $oDocumentoContabil->setValorVariavel("[codigomovimentacaoestoque]", $oLancamentoAuxiliar->getCodigoMovimentacaoEstoque());
    }

    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

    $oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dtLancamento);

  }

  /**
   * Efetua os lancamentos contabeis de entrada manual no estoque
   * $oDadosEntrada deve contar as seguintes propriedades:
   *    ->iMovimentoEstoque    = m80_codigo
   *    ->sObservacaoHistorico = m80_obs
   *    ->nValorLancamento     = m71_valor
   * @param stdClass $oDadosEntrada
   */
  public function entradaManual($oDadosEntrada, $dtLancamento=null) {

    if (empty($dtLancamento)) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $oEventoContabil = new EventoContabil(403, db_getsession("DB_anousu"));
    $aLancanmentos   = $oEventoContabil->getEventoContabilLancamento();
    $oMaterial       = new MaterialEstoque($oDadosEntrada->iCodigoMaterial);

    $oLancamentoAuxiliar = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliar->setCodigoMovimentacaoEstoque($oDadosEntrada->iMovimentoEstoque);
    $oLancamentoAuxiliar->setObservacaoHistorico($oDadosEntrada->sObservacaoHistorico);
    $oLancamentoAuxiliar->setValorTotal($oDadosEntrada->nValorLancamento);
    $oLancamentoAuxiliar->setContaPcasp($oDadosEntrada->iContaPCASP);
    $oLancamentoAuxiliar->setHistorico($aLancanmentos[0]->getHistorico());
    $oLancamentoAuxiliar->setMaterial($oMaterial);
    $this->executarLancamentosContabeis(402, $oLancamentoAuxiliar, $dtLancamento);
    return true;
  }

  /**
   * Efetua os lancamentos contabeis de implantacao do estoque
   * $oDadosEntrada deve contar as seguintes propriedades:
   *    ->iMovimentoEstoque    = m80_codigo
   *    ->sObservacaoHistorico = m80_obs
   *    ->nValorLancamento     = m71_valor
   * @param stdClass $oDadosEntrada
   */
  public function implantacaoEstoque($oDadosEntrada, $dtLancamento=null) {

    if (empty($dtLancamento)) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $oEventoContabil = new EventoContabil(403, db_getsession("DB_anousu"));
    $aLancanmentos   = $oEventoContabil->getEventoContabilLancamento();
    $oMaterial       = new MaterialEstoque($oDadosEntrada->iCodigoMaterial);

    $oLancamentoAuxiliar = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliar->setCodigoMovimentacaoEstoque($oDadosEntrada->iMovimentoEstoque);
    $oLancamentoAuxiliar->setObservacaoHistorico($oDadosEntrada->sObservacaoHistorico);
    $oLancamentoAuxiliar->setValorTotal($oDadosEntrada->nValorLancamento);
    $oLancamentoAuxiliar->setContaPcasp($oDadosEntrada->iContaPCASP);
    $oLancamentoAuxiliar->setHistorico($aLancanmentos[0]->getHistorico());
    $oLancamentoAuxiliar->setMaterial($oMaterial);

    $this->executarLancamentosContabeis(402, $oLancamentoAuxiliar, $dtLancamento);
    return true;
  }

  /**
   * Efetua os lancamentos contabeis de saida do estoque
   * $oDadosEntrada deve contar as seguintes propriedades:
   *    ->iMovimentoEstoque    = m80_codigo
   *    ->sObservacaoHistorico = m80_obs
   *    ->nValorLancamento     = m71_valor
   *    ->iContaPCASP          = m66_codcon
   * @param stdClass $oDadosEntrada
   */
  public function saidaManual($oDadosEntrada) {

    $oEventoContabil = new EventoContabil(404, db_getsession("DB_anousu"));
    $aLancanmentos   = $oEventoContabil->getEventoContabilLancamento();
    $oMaterial       = new MaterialEstoque($oDadosEntrada->iCodigoMaterial);

    $oLancamentoAuxiliar = new LancamentoAuxiliarMovimentacaoEstoque();
    $oLancamentoAuxiliar->setCodigoMovimentacaoEstoque($oDadosEntrada->iMovimentoEstoque);
    $oLancamentoAuxiliar->setObservacaoHistorico($oDadosEntrada->sObservacaoHistorico);
    $oLancamentoAuxiliar->setValorTotal($oDadosEntrada->nValorLancamento);
    $oLancamentoAuxiliar->setContaPcasp($oDadosEntrada->iContaPCASP);
    $oLancamentoAuxiliar->setHistorico($aLancanmentos[0]->getHistorico());
    $oLancamentoAuxiliar->setMaterial($oMaterial);
    $oLancamentoAuxiliar->setSaida(true);

    $this->executarLancamentosContabeis(403, $oLancamentoAuxiliar);
    return true;
  }

  /**
   * Retorna o código do almoxarifado
   * @return integer
   */
  public function getCodigoAlmoxarifado() {
    return $this->iAlmoxarifado;
  }
  
  /**
   * function para retornar todos departamentos que sao almoxarifados
   * @return ArrayObject
   */
  public static function getListaAlmoxarifados(){
    
    $aListaAlmoxarifados = array();
    $oDaoDb_depart       = new cl_db_almox();
    $iInstituicao        = db_getsession("DB_instit");
    $sWhereLista         = "m91_depto <> 0 and db_depart.instit = {$iInstituicao}";
    
    $sSqlLista = $oDaoDb_depart->sql_query(null,"m91_depto", "m91_depto", $sWhereLista);
    $rsLista   = $oDaoDb_depart->sql_record($sSqlLista);
    
    if ($oDaoDb_depart->numrows > 0) {
      
      for ($iAlmox = 0; $iAlmox < $oDaoDb_depart->numrows; $iAlmox++) {
        
        $iAlmoxarifado         = db_utils::fieldsMemory($rsLista, $iAlmox)->m91_depto;
        $oAlmoxarifado         = new Almoxarifado( $iAlmoxarifado );
        
        $aListaAlmoxarifados[] = $oAlmoxarifado;
      }
    }
    return $aListaAlmoxarifados;
  }
  
}