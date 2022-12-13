<?php

class AcordoReativacao {
  
  
  const CAMINHO_MENSAGEM = "patrimonial.contratos.AcordoReativacao.";
  /**
   * 
   * Ultima paralizacao do acordo 
   * @var AcordoParalisacao
   */
  private $oParalizacao;
  
  /**
   * Lista de periodos que ficaram paralizados;
   * @var array;
   */
  private $aPeriodos = array();
  
  /**
   * Data de reativa��o do contrato
   * @var DBDate
   */
  private $oDataReativacao;
  
  /**
   * periodos novos incluidos a partir de periodos paralisados
   * @var array
   */
  private $aPeriodosComplementares = array();
  
  /**
   * Observacao da reativacao;
   * @var string
   */
  private $sObservacao = ''; 
 
 /**
  * Realiza a reativa��o de um contrato que esta paralizado
  */
  public function __construct(AcordoParalisacao $oParalizacao) {
    
    $this->oParalizacao = $oParalizacao; 
  }
  
  public function setObservacao( $sObservacao ){
    $this->sObservacao = $sObservacao;
  }
  
  public function setPeriodosParalizados($aPeriodos) {
    $this->aPeriodos = $aPeriodos;
  }
  
  public function setDataReativacao(DBDate $oData) {
    $this->oDataReativacao = $oData;
  }
  
  public function setUltimaPosicao ( DBDate $oUltimaPosicao ) {
    
    $this->oUltimaPosicao = $oUltimaPosicao;
  }
  
  
  public function getPeriodosParalisados(){
    return $this->aPeriodos;
  }
  
  /**
   * metodo ir� incluir novos periodos a partir dos periodos paralisados
   * 
   */
  private function adicionarPeriodosParalisados() {
    
    $aPeriodosParalisados = $this->getPeriodosParalisados();
    foreach ($this->getPeriodosParalisados() as $iPeriodo) {
      
      $this->oParalizacao->adicionarPeriodos($iPeriodo, AcordoParalisacao::PERIODO_PARALIZADO);
    }    
  }
  
  /**
   * Adiciona os periodos complementares do contrato
   */
  private function adicionarPeriodosComplementares() {
    
    $iCodigoAcordo         = $this->oParalizacao->getAcordo()->getCodigoAcordo();
    $oInicioParalisacao    = $this->oParalizacao->getDataInicio();
    $oDataReativacao       = $this->oDataReativacao;
    $iTotalDiasParalisados = DBDate::calculaIntervaloEntreDatas($oDataReativacao, $oInicioParalisacao,  'd') ;

    $oUltimaPosicao   = $this->oParalizacao->getAcordo()->getUltimaPosicao(); 
    $oDtUltimaPosicao = new DBDate($oUltimaPosicao->getVigenciaFinal());
    
    $oDtUltimaPosicao ->modificarIntervalo("+ 1 days");
    $oNovaDataFinalAcordo = clone $oDtUltimaPosicao;
    
    
    
    $oNovaDataFinalAcordo->modificarIntervalo("+ {$iTotalDiasParalisados} days");
    
    $lPeriodoComercial       = $this->oParalizacao->getAcordo()->getPeriodoComercial();
    $aPeriodosComplementares = array();
    if (!$lPeriodoComercial) {
      
      $aPeriodosComplementares = AcordoPosicao::calcularPeriodosMensais($oDtUltimaPosicao->getDate("d/m/Y"), 
                                                                        $oNovaDataFinalAcordo->getDate("d/m/Y"),
                                                                        $iCodigoAcordo
                                                                       );
    } else {
      
      $aPeriodosComplementares = AcordoPosicao::calculaPeriodosComerciais($oDtUltimaPosicao->getDate("d/m/Y"),
                                                                          $oNovaDataFinalAcordo->getDate("d/m/Y")
                                                                         );
    }

    $oDaoAcordoVigencia = new cl_acordovigencia();
    
    $sWhereVigencia  = "     ac18_acordoposicao = {$oUltimaPosicao->getCodigo()} ";
    $sWhereVigencia .= " and ac18_ativo is true ";
    
    $sSqlDadosVigencia  = $oDaoAcordoVigencia->sql_query_file(null, "*", null, $sWhereVigencia);
    $rsVigencia         = $oDaoAcordoVigencia->sql_record($sSqlDadosVigencia);
    if (!$rsVigencia || $oDaoAcordoVigencia->numrows == 0) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM."vigencia_nao_encontrada"));
    }
    
    $oDadosVigencia = db_utils::fieldsMemory($rsVigencia, 0); 
    
    $oDaoAcordoVigencia->ac18_sequencial = $oDadosVigencia->ac18_sequencial;
    $oDaoAcordoVigencia->ac18_datafim    = $oNovaDataFinalAcordo->getDate();
    
    $oDaoAcordoVigencia->alterar($oDadosVigencia->ac18_sequencial);
    if ($oDaoAcordoVigencia->erro_status == 0) {
      
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoVigencia->erro_msg;
      throw new DBException( _M( self::CAMINHO_MENSAGEM . ("erro_atualizar_vigencia"), $oErro ) );
    }
    
    
    $oPeriodo         = end($oUltimaPosicao->getPosicaoPeriodo());
    $iUltimaNumeracao = 0;
    if (!empty($oPeriodo)) {
      $iUltimaNumeracao = $oPeriodo->periodo;
    }
    
    $oDaoAcordoPosicaoPeriodo = new cl_acordoposicaoperiodo();
    foreach ($aPeriodosComplementares as $oPeriodo) {
      
      $oDaoAcordoPosicaoPeriodo->ac36_acordoposicao = $oUltimaPosicao->getCodigo();
      $oDaoAcordoPosicaoPeriodo->ac36_datainicial   = $oPeriodo->dtIni;
      $oDaoAcordoPosicaoPeriodo->ac36_datafinal     = $oPeriodo->dtFin;
      $oDaoAcordoPosicaoPeriodo->ac36_descricao     = $oPeriodo->descrPer;
      $oDaoAcordoPosicaoPeriodo->ac36_numero        = ++$iUltimaNumeracao;
      $oDaoAcordoPosicaoPeriodo->incluir(null);
      if ( $oDaoAcordoPosicaoPeriodo->erro_status == 0 ) {
        
        $oErro = new stdClass();
        $oErro->erro = $oDaoAcordoPosicaoPeriodo->erro_msg;
        throw new DBException( _M( self::CAMINHO_MENSAGEM . "erro_incluir_novo_periodo" ), $oErro );
      }
      
      $this->oParalizacao->adicionarPeriodos($oDaoAcordoPosicaoPeriodo->ac36_sequencial, AcordoParalisacao::PERIODO_COMPLEMENTAR);
      $oPeriodo->codigo = $oDaoAcordoPosicaoPeriodo->ac36_sequencial;
      
      $this->aPeriodosComplementares[] = $oPeriodo;
    }
    $oQuadro = $oUltimaPosicao->getQuadroPrevisao();
    
    foreach ($oQuadro->aItens as $oItem) {
      
      $aItensPrevisao = array();
      foreach ($oItem->previsoes as $oPrevisao) {
        
        if (in_array($oPrevisao->codigovigencia, $this->aPeriodos)) {
          $aItensPrevisao[] = $oPrevisao;
        }
      }
      $this->adicionarNovoPeriodoItem($oItem, $aItensPrevisao);
    }
  }
  
  /**
   * Move as Previsoes dos Itens para os novos periodos criados apos o retorno da paraliza��o
   * @param stdClass $oItem
   * @param array $aItemPrevisoes
   */
  private function adicionarNovoPeriodoItem($oItem, $aItemPrevisoes) {
    
    foreach ($aItemPrevisoes as $iPeriodoPrevisao => $oPrevisao) {

      if (!isset($this->aPeriodosComplementares[$iPeriodoPrevisao])) {
        
        if ($this->oParalizacao->getAcordo()->getPeriodoComercial()) {
          
          continue;
        } else {
          
          throw new BusinessException(_M(self::CAMINHO_MENSAGEM."periodo_complementar_nao_encontrado"));
        }
      }
      $nValorPrevisao = round($oPrevisao->valor - $oPrevisao->valorexecutado, 2);
      
      if ( $nValorPrevisao <= 0 ) {
        continue;
      }
      
      $oPeriodoComplementar                            = $this->aPeriodosComplementares[$iPeriodoPrevisao]; 
      $oDaoAcordoitemPrevisao                          = new cl_acordoitemprevisao();
      $oDaoAcordoitemPrevisao->ac37_acordoitem         = $oItem->codigo;
      $oDaoAcordoitemPrevisao->ac37_quantidade         = $oPrevisao->saldo;
      $oDaoAcordoitemPrevisao->ac37_valor              = $nValorPrevisao;
      $oDaoAcordoitemPrevisao->ac37_quantidadeprevista = $oPrevisao->saldo;
      $oDaoAcordoitemPrevisao->ac37_acordoperiodo      = $oPeriodoComplementar->codigo;
      $oDaoAcordoitemPrevisao->ac37_datainicial        = $oPeriodoComplementar->dtIni;
      $oDaoAcordoitemPrevisao->ac37_datafinal          = $oPeriodoComplementar->dtFin;
      $oDaoAcordoitemPrevisao->ac37_valorunitario      = $oPrevisao->valorunitario;
      $oDaoAcordoitemPrevisao->incluir(null);
      if ($oDaoAcordoitemPrevisao->erro_status == '0') {

        $oErro = new stdClass();
        $oErro->erro = $oDaoAcordoitemPrevisao->erro_msg;
        throw new BusinessException(_M(self::CAMINHO_MENSAGEM."erro_inclusao_item_periodo_complementar",  $oErro));
      } 
    }
  }
  
  /**
   * Persite os dados da reativa��o do acordo
   */
  public function salvar() {
    
    
    $this->oParalizacao->setDataTermino($this->oDataReativacao);
    $this->adicionarPeriodosParalisados();
    $this->adicionarPeriodosComplementares();
    $this->oParalizacao->salvar();
    
    $oAcordoMovimentacaoReativacao = new AcordoMovimentacaoReativacao();
    $oAcordoMovimentacaoReativacao->setAcordo($this->oParalizacao->getAcordo()->getCodigoAcordo());
    $oAcordoMovimentacaoReativacao->setObservacao($this->sObservacao);
    $oAcordoMovimentacaoReativacao->save();
    
  }
  
  
  public function cancelar(){
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::CAMINHO_MENSAGEM . "sem_transacao_ativa" ) );
    }
    
    $aPeriodos = $this->aPeriodos;
    
    $sPeriodos = implode(", ", $aPeriodos);
    
    $iCodigoParalisacao = $this->oParalizacao->getCodigo();
    
    $oDaoAcordoParalisacaoPeriodo = new cl_acordoparalisacaoperiodo();
    $oDaoAcordoPosicaoPeriodo     = new cl_acordoposicaoperiodo();
    $oDaoAcordoItemPrevisao       = new cl_acordoitemprevisao();
    $oDaoAcordoParalisacao        = new cl_acordoparalisacao();
    $oDaoAcordoVigencia           = new cl_acordovigencia();
    
    $oDaoAcordoItemPrevisao->excluir(null, "ac37_acordoperiodo in ({$sPeriodos})");
    if ($oDaoAcordoItemPrevisao->erro_status == 0) {
    
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoItemPrevisao->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }    
    
    $oDaoAcordoParalisacaoPeriodo->excluir(null, "ac49_acordoparalisacao = {$iCodigoParalisacao}");
    if ($oDaoAcordoParalisacaoPeriodo->erro_status == 0) {
      
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacaoPeriodo->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }
    
    $oDaoAcordoPosicaoPeriodo->excluir(null, " ac36_sequencial in ({$sPeriodos}) " );
    if ($oDaoAcordoPosicaoPeriodo->erro_status == 0) {
    
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoPosicaoPeriodo->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }    
    
    /*
     * pegamos os dados do ultimo periodo, desconciderando os periodos que foram gerados na reativa��o
     * esse sera a data final da vigencia (original)
     */
    $iAcordoPosicao  = $this->oParalizacao->getAcordo()->getUltimaPosicao()->getCodigo();
    $sWhereVigencia  = "     ac36_acordoposicao = {$iAcordoPosicao} " ;
    $sWhereVigencia .= " and ac36_sequencial not in ({$sPeriodos}) ";
    $sOrder          = " ac36_sequencial desc limit 1 ";
    
    /*
     * a vigencia, nao se altera pelo where, precisamos do id para atualisar a nova data
     */
    $sSqlVigencia    = $oDaoAcordoPosicaoPeriodo->sql_query_file(null, "ac36_datafinal", $sOrder, $sWhereVigencia);
    $rsVigencia      = $oDaoAcordoPosicaoPeriodo->sql_record($sSqlVigencia);
    $oVigencia       = db_utils::fieldsMemory($rsVigencia, 0);
    $dtVigencia      = $oVigencia->ac36_datafinal;
    
    $sWhereVigencia  = "     ac18_acordoposicao = {$iAcordoPosicao} ";
    $sWhereVigencia .= " and ac18_ativo is true ";
    
    $sSqlDadosVigencia  = $oDaoAcordoVigencia->sql_query_file(null, "*", null, $sWhereVigencia);
    $rsVigencia         = $oDaoAcordoVigencia->sql_record($sSqlDadosVigencia);
    
    if (!$rsVigencia || $oDaoAcordoVigencia->numrows == 0) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM."vigencia_nao_encontrada"));
    }
    
    $oDadosVigencia = db_utils::fieldsMemory($rsVigencia, 0);
    
    $oDaoAcordoVigencia->ac18_sequencial = $oDadosVigencia->ac18_sequencial;
    $oDaoAcordoVigencia->ac18_datafim    = $dtVigencia;
    $oDaoAcordoVigencia->alterar($oDaoAcordoVigencia->ac18_sequencial);
    if ($oDaoAcordoVigencia->erro_status == 0) {
    
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoVigencia->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }

    $oDaoAcordoParalisacao->ac47_sequencial = $iCodigoParalisacao;
    $oDaoAcordoParalisacao->ac47_datafim    = "null";
    $oDaoAcordoParalisacao->alterar($oDaoAcordoParalisacao->ac47_sequencial);
    if ($oDaoAcordoParalisacao->erro_status == 0) {

      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacao->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }
    
    $oDaoAcordoParalisacao->ac47_sequencial = $iCodigoParalisacao;
    $oDaoAcordoParalisacao->ac47_datafim    = "null";
    $oDaoAcordoParalisacao->alterar($oDaoAcordoParalisacao->ac47_sequencial);
    if ($oDaoAcordoParalisacao->erro_status == 0) {
    
      $oErro = new stdClass();
      $oErro->erro = $oDaoAcordoParalisacao->erro_msg;
      throw new DBException( _M(self::CAMINHO_MENSAGEM . "erro_excluir_reativacao", $oErro));
    }    
    
    $oAcordoMovimentacaoCancelamento = new AcordoMovimentacaoReativacao();
    $oAcordoMovimentacaoCancelamento->setAcordo($this->oParalizacao->getAcordo()->getCodigoAcordo());
    $oAcordoMovimentacaoCancelamento->setTipo(19);
    $oAcordoMovimentacaoCancelamento->setObservacao($this->sObservacao);
    $oAcordoMovimentacaoCancelamento->save();
        
    return true;
  }

}