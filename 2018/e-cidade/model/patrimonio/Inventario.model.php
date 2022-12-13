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
 * Inventario de bens Patrimoniais para reavaliacao
 *
 * @author Raphael Lopes <dbrafael.lopes@dbseller.com.br>
 * @package patrimoinio
 * @version $Revision: 1.39 $
 */
class Inventario {

  /**
   * Codigo do inventario
   * @var integer
   */
  protected $iInventario;

  /**
   * data de bertura do inventario
   * @var date
   */
  protected $dDataAbertura;

  /**
   * data de periodo inicial do inventario
   * @var date
   */
  protected $dPeriodoInicial;

  /**
   * data do periodo final do inventário
   * @var date
   */
  protected $dPeriodoFinal;

  /**
   * exercicio do inventario
   * @var integer
   */
  protected $iExercicio;

  /**
   * processo vinculado ao inventario
   * @var integer
   */
  protected $iProcesso;

  /**
   * comissao responsavel pelo inventario
   * @var integer
   */
  protected $iAcordoComissao;

  /**
   * observção relacionada ao inventario
   * @var string
   */
  protected $sObservacao;

  /**
   * situação que se encontra o inventario
   * @var integer
   */
  protected $iSituacao;

  /**
   * departamento que pertence o inventario
   * @var integer
   */
  protected $iDb_depart;

  /**
   * motivo do cancelamento do invetario
   * @var string
   */
  protected $sMotivo;

  /**
   * Colecao de objeto da classe InventarioBem
   * @var array
   */
  protected $aInventarioBens = array();

  function __construct($iInventario = '') {

    if (!empty($iInventario)) {

      $oDaoInventario = db_utils::getDao("inventario");
      $sSqlInventario = $oDaoInventario->sql_query_file($iInventario);
      $rsInventario   = $oDaoInventario->sql_record($sSqlInventario);
      if ($oDaoInventario->numrows > 0) {

        $oDadosInventario = db_utils::fieldsMemory($rsInventario, 0);

        $this->setInventario     ($oDadosInventario->t75_sequencial);
        $this->setDataAbertura   ($oDadosInventario->t75_dataabertura);
        $this->setPeriodoInicial ($oDadosInventario->t75_periodoinicial);
        $this->setPeriodoFinal   ($oDadosInventario->t75_periodofinal);
        $this->setExercicio      ($oDadosInventario->t75_exercicio);
        $this->setAcordoComissao ($oDadosInventario->t75_acordocomissao);
        $this->setProcesso       ($oDadosInventario->t75_processo);
        $this->setObservacao     ($oDadosInventario->t75_observacao);
        $this->setSituacao       ($oDadosInventario->t75_situacao);
        $this->setDepartamento   ($oDadosInventario->t75_db_depart);

      } else {

        $oParms = new stdClass();
        $oParms->codigoInventario = $iInventario;
        throw new BusinessException(_M('patrimonial.patrimonio.Inventario.sequencial_nao_encotrado', $oParms));
        //throw new BusinessException("[ 0 ] - sequencial {$iInventario} não encontrado.");
      }

    }

  }

  /**
   * Processa os dados do inventario, realiza as transferencias necessarias e ajustes do bens
   * @throws DBException
   * @throws BusinessException
   */
  public function
  processarReavaliacao() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M('patrimonial.patrimonio.Inventario.nenhum_transacao_encontrada'));
    }

    $oDaoBensDepreciacao         = db_utils::getDao("bensdepreciacao");
    $oDaoBensHistoricoCalculo    = db_utils::getDao("benshistoricocalculo");
    $oDaoBensHistoricoCalculoBem = db_utils::getDao("benshistoricocalculobem");

    $sObservacao     = "TRANSFERENCIA AUTOMATICA VIA REAVALIAÇÃO";
    $aInventarioBens = $this->getBens();

    /*
     * percorremos o array de itens vinculados
     * para realizar o processamento
     */
    foreach ($aInventarioBens as $oInventarioBem) {

      /*
       * instancia da classe transferencia de bem que ira realizar a
       * transferencia e o recebimento
       */
      $oTransferenciaBem = new TransferenciaBens();
      $oTransferenciaBem->setBem($oInventarioBem->getBem()->getCodigoBem());
      $oTransferenciaBem->setDepartamentoOrigem($oInventarioBem->getBem()->getDepartamento());
      $oTransferenciaBem->setDepartamentoDestino($oInventarioBem->getDepartamento()->getCodigo());
      $oTransferenciaBem->setDivisaoDestino($oInventarioBem->getDivisaoDepartamento()->getCodigo());
      $oTransferenciaBem->setUsuario(db_getsession('DB_id_usuario'));
      $oTransferenciaBem->setInstit(db_getsession('DB_instit'));
      $oTransferenciaBem->setClabens(0);
      $oTransferenciaBem->setData(date("Y-m-d", db_getsession("DB_datausu")));
      $oTransferenciaBem->setSituacao($oInventarioBem->getSituacao());
      $oTransferenciaBem->setHistorico($sObservacao);
      $oTransferenciaBem->setObservacao($sObservacao);

      $oTransferenciaBem->transferenciaAutomatica();

      /*
       * inclusao dos dados na bensdepreciacao
       */
      $iCodigoBem = $oInventarioBem->getBem()->getCodigoBem();

      $oDaoBensDepreciacao                      = db_utils::getDao("bensdepreciacao");
      $oDaoBensDepreciacao->t44_vidautil        = $oInventarioBem->getVidaUtil();
      $oDaoBensDepreciacao->t44_valoratual      = $oInventarioBem->getValorDepreciavel();
      $oDaoBensDepreciacao->t44_valorresidual   = $oInventarioBem->getValorResidual();
      $oDaoBensDepreciacao->t44_ultimaavaliacao = date("Y-m-d", db_getsession("DB_datausu"));

      /*
       * verificamos se o bem ja existe na bensdepreciacao, se existir, alteramos senão sera incluido
       */

      if ($oInventarioBem->getBem()->getCodigoBemDepreciacao() == null){
        throw new BusinessException(_M('patrimonial.patrimonio.Inventario.bem_sem_informacoes'));
      }

        $oDaoBensDepreciacao->t44_sequencial = $oInventarioBem->getBem()->getCodigoBemDepreciacao();
        $oDaoBensDepreciacao->alterar($oDaoBensDepreciacao->t44_sequencial);

      if ($oDaoBensDepreciacao->erro_status == "0"){
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_processar_reavaliacao_depreciacao', (object) array("sErro" => $oDaoBensDepreciacao->erro_msg)));
      }

      $oDaoBensHistoricoCalculo->t57_mes               = date("m"    , db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_ano               = date("Y"    , db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_datacalculo       = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_usuario           = db_getsession("DB_id_usuario");
      $oDaoBensHistoricoCalculo->t57_instituicao       = db_getsession("DB_instit");
      $oDaoBensHistoricoCalculo->t57_tipocalculo       = 2;
      $oDaoBensHistoricoCalculo->t57_processado        = "true";
      $oDaoBensHistoricoCalculo->t57_tipoprocessamento = 2;
      $oDaoBensHistoricoCalculo->t57_ativo             = "true";
      $oDaoBensHistoricoCalculo->incluir(null);

      if ($oDaoBensHistoricoCalculo->erro_status == "0") {
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_processar_reavaliacao_historico_calculo', (object) array("sErro" => $oDaoBensHistoricoCalculo->erro_msg)));
      }

      $oBemDepreciacao = BemDepreciacao::getInstance($oInventarioBem->getBem());

      $nValorCalculado = $oInventarioBem->getBem()->getValorAtual();
      $nValorAnterior  = ($oBemDepreciacao ? $oBemDepreciacao->getValorAtual() : $oInventarioBem->getBem()->getValorAtual());

      if ($nValorAnterior == null) {
        $nValorAnterior = $oInventarioBem->getBem()->getValorAquisicao();
      }

      $iVidaUtilAnterior = $oInventarioBem->getBem()->getVidaUtil();
      if ($oInventarioBem->getBem()->getValorAtual() != $oInventarioBem->getValorDepreciavel()) {
        $nValorCalculado = abs($oInventarioBem->getBem()->getValorAtual() - $oInventarioBem->getValorDepreciavel());
      }

      $oDaoBensHistoricoCalculoBem->t58_benstipodepreciacao   = 6;
      $oDaoBensHistoricoCalculoBem->t58_benshistoricocalculo  = $oDaoBensHistoricoCalculo->t57_sequencial;
      $oDaoBensHistoricoCalculoBem->t58_bens                  = $oInventarioBem->getBem()->getCodigoBem();
      $oDaoBensHistoricoCalculoBem->t58_valorresidual         = $oInventarioBem->getValorResidual();
      $oDaoBensHistoricoCalculoBem->t58_valoratual            = $oInventarioBem->getValorDepreciavel();
      $oDaoBensHistoricoCalculoBem->t58_valorcalculado        = $nValorCalculado;
      $oDaoBensHistoricoCalculoBem->t58_valoranterior         = $nValorAnterior;
      $oDaoBensHistoricoCalculoBem->t58_vidautilanterior      = $iVidaUtilAnterior;
      $oDaoBensHistoricoCalculoBem->t58_valorresidualanterior = $oInventarioBem->getBem()->getValorResidual();

      /**
       * Calcula percentual a ser depreciado de acordo com o valor atual, valor depreciavel e vida útil
       */
      $oCalculoBem = new CalculoBem();
      $oBemNovo    = new Bem($oInventarioBem->getBem()->getCodigoBem());
      $oBemNovo->setValorAtual($oInventarioBem->getValorDepreciavel());
      $oBemNovo->setValorResidual($oInventarioBem->getValorResidual());
      $oBemNovo->setVidaUtil($oInventarioBem->getVidaUtil());
      $oCalculoBem->setBem($oBemNovo);
      $oCalculoBem->calcular();
      $nPercentualDepreciavel                                = $oCalculoBem->getPercentualDepreciado();
      $oDaoBensHistoricoCalculoBem->t58_percentualdepreciado = "{$nPercentualDepreciavel}";

      $oDaoBensHistoricoCalculoBem->incluir(null);
      if ($oDaoBensHistoricoCalculoBem->erro_status == "0"){
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_processar_reavaliacao_historico_calculo_bem', (object) array("sErro" => $oDaoBensHistoricoCalculoBem->erro_msg)));
      }

    }

    /**
     * apos percorrer os itens atualizamos a situação do inventario para 3 - Processado
     */
    $oDaoInventario                 = db_utils::getDao("inventario");
    $oDaoInventario->t75_sequencial = $this->getInventario();
    $oDaoInventario->t75_situacao   = 3;
    $oDaoInventario->alterar($oDaoInventario->t75_sequencial);
    if ($oDaoInventario->erro_status == "0"){
      throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_processar_reavaliacao_inventario', (object) array("sErro" => $oDaoInventario->erro_msg)));
    }

  }


  /**
   * Método para desprocessar o inventário
   */
  public function desprocessar() {

    $sObservacao     = "TRANSFERENCIA AUTOMATICA VIA REAVALIAÇÃO";
    $aInventarioBens = $this->getBens();

    /**
     * Percorre o Array de InventarioBens, para realizar desprocessamento para cada item
     */
    foreach ($aInventarioBens as $iInventarioBens => $oInventarioBem) {

      $oDaoBensTransfOrigemDestino = db_utils::getDao("benstransforigemdestino");
      $sCampos                     = "max(t34_sequencial),       ";
      $sCampos                    .= "t34_divisaodestino,        ";
      $sCampos                    .= "t34_divisaoorigem,         ";
      $sCampos                    .= "t34_departamentoorigem,    ";
      $sCampos                    .= "t34_departamentodestino ,  ";
      $sCampos                    .= "t77_situabens              ";

      $sWhere  = "t34_bem = {$oInventarioBem->getBem()->getCodigoBem()} ";
      $sWhere .= "group by t34_divisaodestino, ";
      $sWhere .= "t34_divisaoorigem,           ";
      $sWhere .= "t34_departamentoorigem,      ";
      $sWhere .= "t34_departamentodestino ,    ";
      $sWhere .= "t77_situabens                ";

      $sSQLBensTransfOrigemDestino = $oDaoBensTransfOrigemDestino->sql_query_desprocessamento(null,
                                                                                              $sCampos,
                                                                                              null,
                                                                                              $sWhere
                                                                                             );
      $rsBensTransfOrigemDestino   = $oDaoBensTransfOrigemDestino->sql_record($sSQLBensTransfOrigemDestino);

      if ($oDaoBensTransfOrigemDestino->numrows != 0) {

        $oBensTransfOrigemDestino =  db_utils::fieldsMemory($rsBensTransfOrigemDestino, 0);

        /**
         * Cria uma transferência do bem, para seu antigo departamento
         * Cria uma transferência inversa a criada pelo processamento (departamento origem será destino)
         */
        $oTransferenciaBem = new TransferenciaBens();
        $oTransferenciaBem->setBem($oInventarioBem->getBem()->getCodigoBem());
        $oTransferenciaBem->setDepartamentoDestino($oBensTransfOrigemDestino->t34_departamentoorigem);
        $oTransferenciaBem->setDepartamentoOrigem($oBensTransfOrigemDestino->t34_departamentodestino);
        $oTransferenciaBem->setDivisaoDestino($oBensTransfOrigemDestino->t34_divisaoorigem);
        $oTransferenciaBem->setDivisaoOrigem($oBensTransfOrigemDestino->t34_divisaodestino);
        $oTransferenciaBem->setUsuario(db_getsession('DB_id_usuario'));
        $oTransferenciaBem->setInstit(db_getsession('DB_instit'));
        $oTransferenciaBem->setClabens(0);
        $oTransferenciaBem->setData(date("Y-m-d", db_getsession("DB_datausu")));
        $oTransferenciaBem->setSituacao($oBensTransfOrigemDestino->t77_situabens);
        $oTransferenciaBem->setHistorico($sObservacao);
        $oTransferenciaBem->setObservacao($sObservacao);
        $oTransferenciaBem->transferenciaAutomatica();
      }

      $oBem = $oInventarioBem->getBem();
      if ($oBem->getValorResidual() != $oInventarioBem->getValorResidual()    ||
      		$oBem->getValorDepreciavel() != $oInventarioBem->getValorDepreciavel()) {

      	/*$sMensagemErro  = "Você não pode desprocessar este inventário porque existem bens que sofreram movimentações\n";
      	$sMensagemErro .= "financeiras posteriores ao seu processamento.\n\n";
      	$sMensagemErro .= "Verifique o bem: {$oBem->getCodigoBem()} - {$oBem->getDescricao()}\n\n";
        $sMensagemErro .= "Se este desprocessamento for realmente necessário, ";
        $sMensagemErro .= "desfaça as movimentações e tente novamente.";*/
        $oParms = new stdClass();
        $oParms->iCodigoBem = $oBem->getCodigoBem();
        $oParms->sDescricao = $oBem->getDescricao();
      	throw new BusinessException(_M('patrimonial.patrimonio.Inventario.bens_sofreram_movimentacoes', $oParms));
      }

      /**
       * atualiza tabela bensdepreciacao de acordo com estado anterior
       * status encontrado na tabela benshistoricocalculobem
       */
      $oDaoBensHistoricoCalculoBem = db_utils::getDao("benshistoricocalculobem");
      $sWhere                      = "t58_bens = {$oInventarioBem->getBem()->getCodigoBem()}";
      $sWhere                     .= " and  t58_benstipodepreciacao = 6";
      $sOrder                      = "t58_sequencial desc";
      $sSqlBensHistoricoCalculoBem = $oDaoBensHistoricoCalculoBem->sql_query_file(null, "*", $sOrder, $sWhere);
      $rsBensHistoricoCalculoBem   = $oDaoBensHistoricoCalculoBem->sql_record($sSqlBensHistoricoCalculoBem);
      $iTotalBem                   = $oDaoBensHistoricoCalculoBem->numrows;
      if ($iTotalBem == 0) {
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_2', (object) array("sErro" => $oDaoBensHistoricoCalculoBem->erro_msg)));
      }

      $nValorAtualAnterior     = 0;
      $iCodigoBem              = 0;
      $nValorCalculado         = 0;
      $nPercentualAnterior     = 0;
      $nValorAnterior          = 0;
      $iTipoDepreciacao        = 0;
      $nValorResidualAnterior  = 0;

      for ($iRowBem = 0; $iRowBem < $iTotalBem; $iRowBem++) {

        $oStdBem = db_utils::fieldsMemory($rsBensHistoricoCalculoBem, $iRowBem);
        if ($oStdBem->t58_benstipodepreciacao == 6) {

          $iVidaUtilAterior = $oStdBem->t58_vidautilanterior;
          $nValorAtualAnterior     = $oStdBem->t58_valoratual;
          $iCodigoBem              = $oStdBem->t58_bens;
          $nValorCalculado         = $oStdBem->t58_valorcalculado;
          $nPercentualAnterior     = $oStdBem->t58_percentualdepreciado;
          $nValorAnterior          = $oStdBem->t58_valoranterior;
          $iTipoDepreciacao        = $oStdBem->t58_benstipodepreciacao;
          $nValorResidualAnterior  = $oStdBem->t58_valorresidualanterior;

          break;
        }
      }


      /**
       * Erro quando não há registros do estado anterior, antes do processamento
       */
      if ($iTotalBem == 0) {
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_3'));
      }

      /**
       * Vida útil anterior é guardada na tabela benshistoricocaculculobem, durante processamento
       */
      $iVidaUtilAterior         = db_utils::fieldsMemory($rsBensHistoricoCalculoBem, 0)->t58_vidautilanterior;

      /**
       * registro anterior ao registro inserido durante o processamento de inventário, é considerado o status
       */
      $oBensHistoricoCalculoBem = db_utils::fieldsMemory($rsBensHistoricoCalculoBem, 1);

      /**
       *  inclusao dos dados na bensdepreciacao
       */
      $oDaoBensDepreciacao                           = db_utils::getDao("bensdepreciacao");
      $oDaoBensDepreciacao->t44_vidautil             = $iVidaUtilAterior;
      $oDaoBensDepreciacao->t44_valoratual           = $nValorAtualAnterior;
      $oDaoBensDepreciacao->t44_valorresidual        = $nValorResidualAnterior;
      $oDaoBensDepreciacao->t44_ultimaavaliacao      = date("Y-m-d", db_getsession("DB_datausu"));

      if ($oInventarioBem->getBem()->getCodigoBemDepreciacao() == null){
        throw new BusinessException(_M('patrimonial.patrimonio.Inventario.bem_sem_informacoes'));
      }

      $oDaoBensDepreciacao->t44_sequencial = $oInventarioBem->getBem()->getCodigoBemDepreciacao();
      $oDaoBensDepreciacao->alterar($oDaoBensDepreciacao->t44_sequencial);

      /**
       * Erro de atualização no banco
       */
      if ($oDaoBensDepreciacao->erro_status == "0"){
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_4', (object) array("sErro" => $oDaoBensDepreciacao->erro_msg)));
      }

      /**
       *  inclusao dos dados na tabela benshistoricocalculo
       */
      $oDaoBensHistoricoCalculo                         = db_utils::getDao("benshistoricocalculo");
      $oDaoBensHistoricoCalculo->t57_mes               = date("m"    , db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_ano               = date("Y"    , db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_datacalculo        = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoBensHistoricoCalculo->t57_usuario            = db_getsession("DB_id_usuario");
      $oDaoBensHistoricoCalculo->t57_instituicao        = db_getsession("DB_instit");
      $oDaoBensHistoricoCalculo->t57_tipocalculo        = 2;
      $oDaoBensHistoricoCalculo->t57_processado         = "false";
      $oDaoBensHistoricoCalculo->t57_tipoprocessamento  = 2;
      $oDaoBensHistoricoCalculo->t57_ativo              = "true";
      $oDaoBensHistoricoCalculo->incluir(null);

      if ($oDaoBensHistoricoCalculo->erro_status == "0") {
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_5',(object) array("sErro" => $oDaoBensHistoricoCalculo->erro_msg)));
      }

      /**
       *  inclusao dos dados na tabela benshistoricocalculobem
       */
      $oDaoBensHistoricoCalculoBem->t58_benstipodepreciacao   = $iTipoDepreciacao;
      $oDaoBensHistoricoCalculoBem->t58_benshistoricocalculo  = $oDaoBensHistoricoCalculo->t57_sequencial;
      $oDaoBensHistoricoCalculoBem->t58_bens                  = $iCodigoBem;
      $oDaoBensHistoricoCalculoBem->t58_valorresidual         = $nValorResidualAnterior;
      $oDaoBensHistoricoCalculoBem->t58_valoratual            = $nValorAtualAnterior;
      $oDaoBensHistoricoCalculoBem->t58_valorcalculado        = $nValorCalculado;
      $oDaoBensHistoricoCalculoBem->t58_valoranterior         = $nValorAnterior;
      $oDaoBensHistoricoCalculoBem->t58_percentualdepreciado  = "{$nPercentualAnterior}";
      $oDaoBensHistoricoCalculoBem->t58_vidautilanterior      = $iVidaUtilAterior;
      $oDaoBensHistoricoCalculoBem->incluir(null);

      if ($oDaoBensHistoricoCalculoBem->erro_status == "0"){
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_6', (object) array("sErro" => $oDaoBensHistoricoCalculoBem->erro_msg)));
      }

      /**
       * apos percorrer os itens atualizamos a situação do inventario
       */
      $oDaoInventario = db_utils::getDao("inventario");
      $oDaoInventario->t75_sequencial = $this->getInventario();
      $oDaoInventario->t75_situacao   = 1;
      $oDaoInventario->alterar($oDaoInventario->t75_sequencial);

      if ($oDaoInventario->erro_status == "0"){
        throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico_7', (object) array("sErro" => $oDaoInventario->erro_msg)));
      }
    }
  }

  /**
   * metodo para desvincular um bem de um inventario
   */
  public function desvincularBens($iBem){

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M('patrimonial.patrimonio.Inventario.nenhum_transacao_encontrada'));
    }

    if (!isset($iBem)) {
      throw new ParameterException(_M('patrimonial.patrimonio.Inventario.informe_codigo_bem'));
    }

    $oInventarioBem = db_utils::getDao("inventariobem");

    $oInventarioBem->excluir(null, "t77_inventario = {$this->getInventario()} and t77_bens = {$iBem} ");
    if ($oInventarioBem->erro_status == "0") {

      throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_desvincular_bem', (object) array("sErro" => $oInventarioBem->erro_msg)));
    }

  }

  /**
   * Retorna os bens vinculados ao inventario
   * @return InventarioBem[]
   */
  public function getBens() {

    if (count($this->aInventarioBens) == 0) {

      $oDaoInventario          = db_utils::getDao('inventariobem');
      $sSqlBuscaBensInventario = $oDaoInventario->sql_query_file(null,
                                                                 "t77_sequencial",
                                                                 null,
                                                                 "t77_inventario = {$this->getInventario()}"
                                                                 );
      $rsBuscaInventarioBem    = $oDaoInventario->sql_record($sSqlBuscaBensInventario);
      if ($oDaoInventario->numrows > 0) {

        for ($iRow = 0; $iRow < $oDaoInventario->numrows; $iRow++) {

          $iCodigoInventarioBem = db_utils::fieldsMemory($rsBuscaInventarioBem, $iRow)->t77_sequencial;
          $this->adicionarInventarioBem(new InventarioBem($iCodigoInventarioBem));
        }
      }
    }
    return $this->aInventarioBens;
  }

  /**
   * metodo para anulação de um inventario
   * @throws DBException
   */
  public function anular() {

    $oDaoInventario        = db_utils::getDao("inventario");
    $oDaoInventarioAnulado = db_utils::getDao("inventarioanulado");

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M('patrimonial.patrimonio.Inventario.nenhum_transacao_encontrada'));
    }

    $oDaoInventario->t75_sequencial = $this->getInventario();
    $oDaoInventario->t75_situacao   = $this->getSituacao();
    $oDaoInventario->alterar($oDaoInventario->t75_sequencial);
    if ($oDaoInventario->erro_status == "0") {

      throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_inventario', $oDaoInventario->erro_msg));
    }
    $oDaoInventarioAnulado->t76_inventario   = $this->getInventario();
    $oDaoInventarioAnulado->t76_dataanulacao = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoInventarioAnulado->t76_horaanulacao = date("H:i");
    $oDaoInventarioAnulado->t76_usuario      = db_getsession("DB_id_usuario");
    $oDaoInventarioAnulado->t76_motivo       = $this->getMotivo();

    $oDaoInventarioAnulado->incluir(null);
    if ($oDaoInventarioAnulado->erro_status == "0") {

      throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_anular_inventario', (object) array("sErro" => $oDaoInventarioAnulado->erro_msg)));
    }


  }

  /**
   * metodo para persistir os dados de um inventario
   * @throws DBException
   * @throws ParameterException
   */
  public function salvar(){

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M('patrimonial.patrimonio.Inventario.nenhum_transacao_encontrada'));
    }

    $oDaoInventario = db_utils::getDao("inventario");
    $oDaoInventario->t75_dataabertura    = implode("-", array_reverse(explode("/",$this->getDataAbertura())));
    $oDaoInventario->t75_periodoinicial  = implode("-", array_reverse(explode("/",$this->getPeriodoInicial())));
    $oDaoInventario->t75_periodofinal    = implode("-", array_reverse(explode("/",$this->getPeriodoFinal())));
    $oDaoInventario->t75_exercicio       = $this->getExercicio();
    $oDaoInventario->t75_processo        = $this->getProcesso();
    $oDaoInventario->t75_acordocomissao  = $this->getAcordoComissao();
    $oDaoInventario->t75_observacao      = $this->getObservacao();
    $oDaoInventario->t75_situacao        = $this->getSituacao();
    $oDaoInventario->t75_db_depart       = $this->getDepartamento();

    $oDaoInventario->incluir(null);
    if ($oDaoInventario->erro_status == "0") {

      throw new ParameterException(_M('patrimonial.patrimonio.Inventario.erro_salvar', (object) array("sErro" => $oDaoInventario->erro_msg)));
    }

    $this->setInventario($oDaoInventario->t75_sequencial);
  }

  /**
   * defini o codigo de um inventario
   * @param integer $iInventario
   */
  private function setInventario($iInventario) {
  	$this->iInventario = $iInventario;
  }

  /**
   * retorna codigo inventario;
   * @return integer
   */
  public function getInventario() {
  	return $this->iInventario;
  }

  /**
   * defini data de abertura do inventario
   * @param date $dDataAbertura
   */
  public function setDataAbertura($dDataAbertura) {
    $this->dDataAbertura = $dDataAbertura;
  }

  /**
   * retorna data de abertura do inventario
   * @return date
   */
  public function getDataAbertura() {
    return $this->dDataAbertura;
  }

  /**
   * defini o periodo inicial do inventario
   * @param date $dPeriodoInicial
   */
  public function setPeriodoInicial($dPeriodoInicial) {
    $this->dPeriodoInicial = $dPeriodoInicial;
  }

  /**
   * retorna o periodo inicial de um inventario
   * @return date
   */
  public function getPeriodoInicial() {
    return $this->dPeriodoInicial;
  }

  /**
   * defini um periodo final para o inventario
   * @param date $dPeriodoFinal
   */
  public function setPeriodoFinal($dPeriodoFinal) {
    $this->dPeriodoFinal = $dPeriodoFinal;
  }

  /**
   * retorna o periodo final do inventario
   * @return date
   */
  public function getPeriodoFinal() {
    return $this->dPeriodoFinal;
  }

  /**
   * defini o exercicio do inventario
   * @param integer $iExercicio
   */
  public function setExercicio($iExercicio) {
    $this->iExercicio = $iExercicio;
  }

  /**
   * retorna o exercicio do inventario
   * @return integer
   */
  public function getExercicio() {
    return $this->iExercicio;
  }

  /**
   * defini o processo vinculado ao inventario
   * @param integer $iProcesso
   */
  public function setProcesso($iProcesso) {
    $this->iProcesso = $iProcesso;
  }

  /**
   * retorna o processo vinculado ao inventario
   * @return integer
   */
  public function getProcesso() {
    return $this->iProcesso;
  }

  /**
   * defini comissao responsavel pelo inventario
   * @param integer $iAcordoComissao
   */
  public function setAcordoComissao($iAcordoComissao) {
    $this->iAcordoComissao = $iAcordoComissao;
  }

  /**
   * retorna a comissao responsavel pelo inventario
   * @return integer;
   */
  public function getAcordoComissao() {
    return $this->iAcordoComissao;
  }

  /**
   * defini uma observação descritiva do inventario
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * retorna a observação do inventario
   * @return string;
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * defini a situação atual do inventario
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * retorna situação do inventario
   * @return integer;
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * defini o departamento do invetario
   * @param integer $iDb_depart
   */
  public function setDepartamento($iDb_depart) {
    $this->iDb_depart = $iDb_depart;
  }

  /**
   * retorna departamento do inventario
   * @return integer
   */
  public function getDepartamento() {
    return $this->iDb_depart;
  }

  /**
   * defini motivo do cancelamento do inventario
   * @param string $sMotivo
   */
  public function setMotivo($sMotivo) {
    $this->sMotivo = $sMotivo;
  }

  /**
   * retorna o motivo do cancelamento do inventario
   * @return string;
   */
  public function getMotivo() {
    return $this->sMotivo;
  }

  /**
   * retorna a string da situação
   * @return false;
   */
  public function getSituacaoString(){

    switch ($this->getSituacao()) {

      case "1":
        $sSituacao = "ATIVO";
        break;

      case "2":
        $sSituacao = "ANULADO";
        break;

      case "3":
        $sSituacao = "PROCESSADO";
        break;

    }
    return $sSituacao;
  }

  public function adicionarInventarioBem(InventarioBem $oInventarioBem) {
    $this->aInventarioBens[] = $oInventarioBem;
  }

  /**
   * Realizar o lancamento contabil de uma conta de reavaliacao
   */
  public function executarLancamentos($iCodigoDocumento, $oLancamentoAuxiliar) {

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
    $oEventoContabil = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar);
  }


  /**
   * Retorna uma tabela com os dado para a escrituracao contabil
   * @param string $lEstorno se a tabela devera retornar como estorno.
   * @throws BusinessException
   * @return array
   */
  public function getDadosEscrituracaoContabil($lEstorno = false) {

    $aDocumentosEstorno = array( "600" => "601",
                                 "602" => "603"
                               );

    $aBens                   = $this->getBens();
    $aDadosEscrituraContabil = array();

    foreach ($aBens as $oDadosBem) {

      $oClassificacao = $oDadosBem->getBem()->getClassificacao();
      $iCodigoConta   = $oClassificacao->getPlanoConta();

      if (!isset($aDadosEscrituraContabil[$iCodigoConta])) {

        $oValorEscriturar                       = new stdClass();
        $oValorEscriturar->iCodigoConta         = $oClassificacao->getPlanoConta();
        $oValorEscriturar->nValorAtual          = 0;
        $oValorEscriturar->nValorLancamento     = 0;
        $oValorEscriturar->nSaldoAnterior       = 0;
        $oValorEscriturar->nValorAnterior       = 0;
        $oValorEscriturar->aClassificacoes      = array();
        $aDadosEscrituraContabil[$iCodigoConta] = $oValorEscriturar;
      } else {
        $oValorEscriturar = $aDadosEscrituraContabil[$iCodigoConta];
      }
      $nValorBem = $oDadosBem->getBem()->getValorAquisicao();
      if ($oDadosBem->getBem()->getTotalDeReavaliacoes() > 1) {
        $nValorBem = $oDadosBem->getBem()->getValorUltimaReavaliacao();
      }
      $oValorEscriturar->nValorAtual      += $oDadosBem->getValorDepreciavel() + $oDadosBem->getValorResidual();
      $oValorEscriturar->nValorAnterior   += $nValorBem;
      $oValorEscriturar->aClassificacoes[] = $oClassificacao->getCodigo();
    }

    /**
     * iteramos sobre o agrupador de valores, para retornar os valores do saldo inicial, e o processamento
     * do tipo de documento que deve ser lancado.
     */
    foreach ($aDadosEscrituraContabil as $oDadoEscriturar) {

      $iAnoUsu = db_getsession("DB_anousu");
      $sWhere   = "c61_codcon = {$oDadoEscriturar->iCodigoConta} and ";
      $sWhere  .= "c61_anousu = {$iAnoUsu} and ";
      $sWhere  .= "c61_instit = ".db_getsession("DB_instit");

      $dtFinal  = date("Y-m-d", db_getsession("DB_datausu"));
      $rsConta  = db_planocontassaldo_matriz($iAnoUsu,
                                            "{$iAnoUsu}-01-01",
                                             $dtFinal,
                                             false,
                                             $sWhere
                                            );
      db_query("drop table work_pl");
      db_query("drop table if exists work_pl_estrut");
      db_query("drop table if exists work_pl_estrutmae");

      $aDadosBalancete = db_utils::getCollectionByRecord($rsConta);
      $oContaVerificar = null;

      foreach ($aDadosBalancete as $oContaBalancete) {

        if ($oContaBalancete->c61_codcon == $oDadoEscriturar->iCodigoConta) {

          $oContaVerificar = $oContaBalancete;
          break;
        }
      }

      if ($oContaVerificar == null) {

        $sClassificacoes = implode(", ", array_unique($oDadoEscriturar->aClassificacoes));

        $oParms = new stdClass();
        $oParms->iCodigoConta = $oDadoEscriturar->iCodigoConta;
        $oParms->sClassificacoes = $sClassificacoes;
        throw new BusinessException(_M('patrimonial.patrimonio.Inventario.conta_de_codigo_nao_existe', $oParms));
      }

      $oDadoEscriturar->nSaldoAnterior    = $oContaVerificar->saldo_final;
      $oDadoEscriturar->sEstrutural       = $oContaVerificar->estrutural;
      $oDadoEscriturar->sDescricao        = $oContaVerificar->c60_descr;
      $oDadoEscriturar->nValorReavaliacao = 0;
      $oDadoEscriturar->nValorReajuste    = 0;
      $oDadoEscriturar->nValorLancamento  = 0;
      $oDadoEscriturar->iCodigoConta      = $oContaVerificar->c61_reduz;

      /**
       * verificar qual o tipo do valor devemos ter, isto é, se é reajuste, ou reavaliacao
       */
      if ($oDadoEscriturar->nValorAtual >= $oDadoEscriturar->nValorAnterior ) {

        $oDadoEscriturar->nValorReavaliacao = $oDadoEscriturar->nValorAtual;
        $nValorVerificar                    = $oDadoEscriturar->nValorReavaliacao;
      } else {

        $oDadoEscriturar->nValorReajuste = $oDadoEscriturar->nValorAtual;
        $nValorVerificar                 = $oDadoEscriturar->nValorReajuste;
      }

      $oDadoEscriturar->nValorLancamento = abs($oDadoEscriturar->nValorAtual - $oDadoEscriturar->nValorAnterior);

      $iCodigoDocumento = 602;
      if ($nValorVerificar < $oDadoEscriturar->nSaldoAnterior) {
        $iCodigoDocumento = 600;
      }

      if ($lEstorno) {

        $sCampos                           = "c71_coddoc,c70_valor";
        $sWhere                            = "     c88_inventario = {$this->iInventario} ";
        $sWhere                           .= " and c85_reduz      = {$oDadoEscriturar->iCodigoConta} ";
        $sOrder                            = "c85_sequencial desc limit 1";
        $oEscrituraInventario              = db_utils::getDao("escriturainventario");
        $sSqlUltimoLancamento              = $oEscrituraInventario->sql_queryLancamentoAnterior( null,
                                                                                                 $sCampos,
                                                                                                 $sOrder,
                                                                                                 $sWhere );

        $rsDadosUltimoLancamento           = $oEscrituraInventario->sql_record($sSqlUltimoLancamento);

        if (!$rsDadosUltimoLancamento || $oEscrituraInventario->numrows == 0) {
          throw new BusinessException(_M('patrimonial.patrimonio.Inventario.erro_estornar_escrituracao'));
        }

        $oDadosUltimoLancamento            = db_utils::fieldsMemory($rsDadosUltimoLancamento, 0);
        if (in_array($oDadosUltimoLancamento->c71_coddoc, $aDocumentosEstorno)) {

          $oParms = new stdClass();
          $oParms->iInventario = $this->iInventario;
          throw new BusinessException(_M('patrimonial.patrimonio.Inventario.inventario_ja_estornado', $oParms));
          //throw new BusinessException("Inventário {$this->iInventario} já encontra-se estornado.");
        }
        $iCodigoDocumento                  = $aDocumentosEstorno[$oDadosUltimoLancamento->c71_coddoc];
        $oDadoEscriturar->nValorLancamento = $oDadosUltimoLancamento->c70_valor;
      }

      /**
       *Verificamos o codigo do documento a ser lancado
       */
      $oDocumentoContabil                 = new EventoContabil($iCodigoDocumento, $iAnoUsu);
      $oDadoEscriturar->sDocumento        = $oDocumentoContabil->getDescricaoDocumento();
      $oDadoEscriturar->iCodigoDocumento  = $iCodigoDocumento;
      unset($oDadoEscriturar->nValorAtual);
      unset($oDadoEscriturar->nValorAnterior);
    }

    return $aDadosEscrituraContabil;
  }


  /**
   * Processa os dados  de lancamentros contabeis do inventario, para atualizacao dos valores
   * na contabilidade
   * @param string $sObservacao observacoes para os lancamentos
   */
  public function processarLancamento($sObservacao) {
    $this->gerarLancamentoEscrituracao(false, $sObservacao);
  }

  /**
   * Desprocessamento dos lançamentos referentes ao processamento da reavaliação
   * @param string $sObservacao
   */
  public function desprocessarLancamento($sObservacao) {
    $this->gerarLancamentoEscrituracao(true, $sObservacao);
  }

  /**
   * Gera lancamento de escrituracao
   * @param bool $lEstornar
   * @param string $sObservacao
   */
  private function gerarLancamentoEscrituracao( $lEstornar, $sObservacao) {

    $sEstornar = $lEstornar ? 'true' : 'false';

    /**
     * Busca os dados da escrituracao, conta, documento e o total do lancamento
     * @var bool - true = desprocessamento | false = processamento
     */
    $aDadosEscrituracao 		 = $this->getDadosEscrituracaoContabil( $lEstornar );

    $oDaoEscrituraInventario = db_utils::getDao("escriturainventario");
    $sSqlEscrituraInventario = $oDaoEscrituraInventario->sql_query_file(null,"*",null,"c88_inventario = {$this->iInventario}");
    $rsEscrituraInventario   = $oDaoEscrituraInventario->sql_record($sSqlEscrituraInventario);

    /**
     * Verifica se ja foi feito processamento/desprocessamento e altera/incluir
     */
    if ($oDaoEscrituraInventario->numrows > 0 ) {

      $oEscrituraInventario = db_utils::fieldsMemory($rsEscrituraInventario, 0);

      $oDaoEscrituraInventario->c88_sequencial		= $oEscrituraInventario->c88_sequencial;
      $oDaoEscrituraInventario->c88_estornado 		= "{$sEstornar}";
      $oDaoEscrituraInventario->c88_data          =	date('Y-m-d', db_getsession("DB_datausu"));
      $oDaoEscrituraInventario->c88_usuario       =	db_getsession("DB_id_usuario");
      if ($oEscrituraInventario->c88_estornado == 'f' && !$lEstornar) {
        throw new BusinessException("O inventário {$this->iInventario} já se encontra escriturado.");
      }
      $oDaoEscrituraInventario->alterar($oEscrituraInventario->c88_sequencial);

    }	else {

      $oDaoEscrituraInventario->c88_estornado		  = "{$sEstornar}";
      $oDaoEscrituraInventario->c88_data          =	date('Y-m-d', db_getsession("DB_datausu"));
      $oDaoEscrituraInventario->c88_usuario       =	db_getsession("DB_id_usuario");
      $oDaoEscrituraInventario->c88_inventario 		= $this->iInventario;
      $oDaoEscrituraInventario->incluir(null);
    }

    /**
     * Erro ao incluir/alterar tabela escriturainventario
     */
    if ($oDaoEscrituraInventario->erro_status == "0"){
      throw new DBException(_M('patrimonial.patrimonio.Inventario.erro_tecnico', $oDaoEscrituraInventario->erro_msg) );
    }

    /**
     * Codigo da tabela escriturainventario
     * @var integer
     */
    $iEscrituraInventario = $oDaoEscrituraInventario->c88_sequencial;

    /**
     * Percorre os dados da escrituracao e executa lancamento
     */


    foreach ($aDadosEscrituracao as $iConta=>$oStdDadosEscrituracao) {

      $oEventoContabil               = new EventoContabil($oStdDadosEscrituracao->iCodigoDocumento, db_getsession("DB_anousu"));
      $aLancamentos                  = $oEventoContabil->getEventoContabilLancamento();
      $iCodigoHistorico              = $aLancamentos[0]->getHistorico();
      $oLancamentoAuxiliarInventario = new LancamentoAuxiliarInventario();
      $oLancamentoAuxiliarInventario->setObservacaoHistorico($sObservacao);
      $oLancamentoAuxiliarInventario->setCodigoInventario($this->iInventario);
      $oLancamentoAuxiliarInventario->setValorTotal($oStdDadosEscrituracao->nValorLancamento);
      $oLancamentoAuxiliarInventario->setHistorico($iCodigoHistorico);
      $oLancamentoAuxiliarInventario->setContaCredito($oStdDadosEscrituracao->iCodigoConta);
      $oLancamentoAuxiliarInventario->setCodigoEscrituraInventario($iEscrituraInventario);
      $oLancamentoAuxiliarInventario->setAnousu(db_getsession('DB_anousu'));
      $oLancamentoAuxiliarInventario->setReduzido($oStdDadosEscrituracao->iCodigoConta);
      $oLancamentoAuxiliarInventario->setContaDebito(null);

      $this->executarLancamentos($oStdDadosEscrituracao->iCodigoDocumento, $oLancamentoAuxiliarInventario);
    }
  }
}
?>