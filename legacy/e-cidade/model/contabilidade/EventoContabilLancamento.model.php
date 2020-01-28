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
 * EventoContabilLancamento
 * Model para controle dos Lancamentos de uma transação
 * @author  matheus.felini@dbseller.com.br
 * @version $Revision: 1.26 $
 * @package contabilidade
 */

require_once ("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");

class EventoContabilLancamento {

  /**
   * Sequencial do Lancamento
   * @var integer
   */
  protected $iSequencialLancamento;

  /**
   * Sequencial da Transação
   * @var integer
   */
  protected $iSequencialTransacao;

  /**
   * Código do Histórico
   * @var integer
   */
  protected $iHistorico;

  /**
   * Observação do Lançamento
   * @var string
   */
  protected $sObservacao;

  /**
   * Valor do Lançamento
   * @var float
   */
  protected $nValor;

  /**
   * Obrigatorio
   * @var boolean
   */
  protected $lObrigatorio;

  /**
   * Código do Evento
   * @var integer
   */
  protected $iEvento;

  /**
   * Descrição do Lançamento
   * @var string
   */
  protected $sDescricao;

  /**
   * Ordem do Lançamento
   * @var integer
   */
  protected $iOrdem;

  /**
   * Regras localizadas para o Lançamento
   * @var array
   */
  protected $aRegrasLancamento = array();

  static $iTotalInstancias = 0;
  
  const CAMINHO_MENSAGEM = 'financeiro.contabilidade.EventoContabilLancamento.';

  /**
   * Seta valor para o objeto em questão e cria um vetor com todas as regras deste objeto
   * @param  integer $iCodigoLancamento
   * @throws Exception
   */
  public function __construct($iCodigoLancamento = null) {

    EventoContabilLancamento::$iTotalInstancias++;
    if (!empty($iCodigoLancamento)) {

      $oDaoLancamento      = db_utils::getDao('contranslan');
      $sSqlBuscaLancamento = $oDaoLancamento->sql_query($iCodigoLancamento);
      $rsBuscaLancamento   = $oDaoLancamento->sql_record($sSqlBuscaLancamento);

      if ($oDaoLancamento->numrows == 0) {
        throw new Exception("Dados do lançamento {$iCodigoLancamento} não localizado.\n\n{$oDaoLancamento->erro_msg}");
      }

      $oDadoLancamento = db_utils::fieldsMemory($rsBuscaLancamento, 0);
      $this->setDescricao($oDadoLancamento->c46_descricao);
      $this->setEvento($oDadoLancamento->c46_evento);
      $this->setHistorico($oDadoLancamento->c46_codhist);
      $this->setObrigatorio($oDadoLancamento->c46_obrigatorio == 't' ? true : false );
      $this->setObservacao($oDadoLancamento->c46_obs);
      $this->setOrdem($oDadoLancamento->c46_ordem);
      $this->setSequencialLancamento($oDadoLancamento->c46_seqtranslan);
      $this->setValor($oDadoLancamento->c46_valor);
      $this->setSequencialTransacao($oDadoLancamento->c46_seqtrans);
      unset($oDadoLancamento);
    }
    return true;
  }

  /**
   * Salva os dados de um lançamento contábil
   * @throws Exception
   */
  public function salvar() {

    /*
     * Inserimos os dados do Lançamento em contranslan
     */
    $oDaoContransLan                  = db_utils::getDao('contranslan');
    $oDaoContransLan->c46_seqtranslan = $this->getSequencialLancamento();
    $oDaoContransLan->c46_seqtrans    = $this->getSequencialTransacao();
    $oDaoContransLan->c46_codhist     = $this->getHistorico();
    $oDaoContransLan->c46_obs         = $this->getObservacao();
    $oDaoContransLan->c46_valor       = $this->getValor();
    $oDaoContransLan->c46_obrigatorio = $this->getObrigatorio() == "t" ? "true" : "false";
    $oDaoContransLan->c46_evento      = $this->getEvento();
    $oDaoContransLan->c46_descricao   = $this->getDescricao();
    $oDaoContransLan->c46_ordem       = $this->getOrdem();

    /*
     * Definimos se vamos incluir um novo lançamento ou se alteramos um existente
    */
    if ($this->getSequencialLancamento() == "") {

      $oDaoContransLan->c46_ordem = $this->getProximaOrdemLancamentoTransacao();
      $this->setOrdem($oDaoContransLan->c46_ordem);
      $oDaoContransLan->incluir(null);
      if ($oDaoContransLan->erro_status == 0) {
        throw new Exception("Não foi possível salvar os dados do lançamento.\n\n{$oDaoContransLan->erro_msg}");
      }
      $this->setSequencialLancamento($oDaoContransLan->c46_seqtranslan);
    } else {
      $oDaoContransLan->alterar($this->getSequencialLancamento());
    }

    if ($oDaoContransLan->erro_status == 0) {
      throw new Exception("Não foi possível salvar os dados do lançamento.\n\n{$oDaoContransLan->erro_msg}");
    }
    return true;
  }

  /**
   * Exclui as regras de um lançamento e o próprio lançamento
   * @throws Exception
   */
  public function excluir() {

    /*
     * Verifico se existem regras para o lançamento instanciado. Caso exita, percorremos a coleção de objetos
     * chamando o método excluir o objeto RegraLancamentoContabil
     */
    $aRegrasLancamento = $this->getRegrasLancamento();
    if (count($aRegrasLancamento) > 0) {

      foreach ($aRegrasLancamento as $oRegra) {
        $oRegra->excluir();
      }
    }

    /*
     * Excluo os dados da tabela contranslan
    */
    $oDaoContransLan = db_utils::getDao("contranslan");
    $oDaoContransLan->excluir($this->getSequencialLancamento());
    if ($oDaoContransLan->erro_status == 0) {
      throw new Exception("Não foi possível excluir o lançamento contábil.\n\n{$oDaoContransLan->erro_msg}");
    }
  }

  /**
   * Método que retorna a ordem do próximo lançamento de uma transação
   * @return integer
   */
  public function getProximaOrdemLancamentoTransacao() {

    $oDaoTranslan        = db_utils::getDao("contranslan");
    $sWhereTranslan      = "c46_seqtrans = {$this->getSequencialTransacao()}";
    $sSqlOrdemLancamento = $oDaoTranslan->sql_query_file(null, "max(c46_ordem) as c46_ordem", null, $sWhereTranslan);
    $rsOrdemLancamento   = $oDaoTranslan->sql_record($sSqlOrdemLancamento);

    if ($oDaoTranslan->numrows == 0) {
      $iProximaOrdem = 1;
    } else {
      $iProximaOrdem = (db_utils::fieldsMemory($rsOrdemLancamento, 0)->c46_ordem + 1);
    }
    return $iProximaOrdem;
  }

  /**
    * Retorna o historico
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * Seta o historico
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * Retorna a observacao
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Seta a observacao
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o valor
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * Seta o valor
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * Retorna o valor da propriedade lObrigatorio
   * @return boolean
   */
  public function getObrigatorio() {
    return $this->lObrigatorio;
  }

  /**
   * Seta se é obrigatorio
   * @param boolean $lObrigatorio
   */
  public function setObrigatorio($lObrigatorio) {
    $this->lObrigatorio = $lObrigatorio;
  }

  /**
   * Retorna o codigo do evento
   * @return integer
   */
  public function getEvento() {
    return $this->iEvento;
  }

  /**
   * Seta o Evento
   * @param integer $iEvento
   */
  public function setEvento($iEvento) {
    $this->iEvento = $iEvento;
  }

  /**
   * Retorna a descricao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao do lancamento
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a ordem
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Seta a ordem do lancamento
   * @param integer $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Seta o sequencial do lancamento
   * @param integer $iSequencialLancamento
   */
  public function setSequencialLancamento($iSequencialLancamento) {
    $this->iSequencialLancamento = $iSequencialLancamento;
  }

  /**
   * Retorna o sequencial do lançamento
   * @return integer
   */
  public function getSequencialLancamento() {
    return $this->iSequencialLancamento;
  }

  /**
   * Retorna o sequencial da transação
   * @return integer
   */
  public function getSequencialTransacao() {
    return $this->iSequencialTransacao;
  }

  /**
   * Seta o sequencial da transacao
   * @param integer $iSequencialTransacao
   */
  public function setSequencialTransacao($iSequencialTransacao) {
    $this->iSequencialTransacao = $iSequencialTransacao;
  }

  /**
   * Adicionar ao array um objeto RegraLancamentoContabil
   * @param RegraLancamentoContabil $oRegra
   */
  public function adicionarRegra(RegraLancamentoContabil $oRegra) {
    $this->aRegrasLancamento[] = $oRegra;
  }

  /**
   * Retorna um array contendo RegraLancamentoContabil
   * @return RegraLancamentoContabil[]
   */
  public function getRegrasLancamento() {

    if (count($this->aRegrasLancamento) == 0) {

      $oDaoLancamento = db_utils::getDao("contranslan");
      $sWhereRegras   = "c47_seqtranslan = {$this->getSequencialLancamento()}";
      $sSqlRegras     = $oDaoLancamento->sql_query_lr(null, "c47_seqtranslr", null, $sWhereRegras);
      $rsRegras       = $oDaoLancamento->sql_record($sSqlRegras);

      if ($oDaoLancamento->numrows > 0) {

        for ($iRowRegra = 0; $iRowRegra < $oDaoLancamento->numrows; $iRowRegra++) {

          $iCodigoRegra             = db_utils::fieldsMemory($rsRegras, $iRowRegra)->c47_seqtranslr;
          $oRegraLancamentoContabil = new RegraLancamentoContabil($iCodigoRegra);
          $this->adicionarRegra($oRegraLancamentoContabil);
        }
      }
    }
    return $this->aRegrasLancamento;
  }

  /**
   * Busca a regra de lançamento utilizada para um determinado lançamento
   *
   * @param $iDocumento
   * @param $iSequencialLancamento
   * @param $oLancamentoAuxiliar
   * @param DBDate $dtDataUsu
   * @return RegraLancamentoContabil
   */
  public function getRegraLancamento ($iDocumento, $iSequencialLancamento, $oLancamentoAuxiliar, DBDate $dtDataUsu) {
    /**
     * Busca as Contas para efetuar lançamento
     */
    $oRegraLancamento = RegraLancamentoContabilFactory::getRegraLancamento( $iDocumento,
                                                                            $iSequencialLancamento,
                                                                            $oLancamentoAuxiliar,
                                                                            $dtDataUsu );

    return $oRegraLancamento;

  }

  /**
   *
   * @param integer $iDocumento
   * @param integer $iLancamento
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function executa ($iCodigoLancamento, $iDocumento, ILancamentoAuxiliar $oLancamentoAuxiliar,
                           $dtLancamento = null) {

    $iAnoUsu   = db_getsession("DB_anousu");
    $dtDataUsu = $dtLancamento;
    if (empty($dtLancamento)) {
      $dtDataUsu = date("Y-m-d", db_getsession('DB_datausu'));
    }

    $oContaContabil = $this->getRegraLancamento($iDocumento, $this->iSequencialLancamento, $oLancamentoAuxiliar, new DBDate($dtDataUsu) );

    $oEventoContabil = EventoContabil::getInstanciaPorCodigo($this->iSequencialTransacao);

    /**
     * StdClass para mensagens de erro
     */
    $oStdMensagem    = new stdClass();
    $oStdMensagem->iDocumento           = $oEventoContabil->getCodigoDocumento();
    $oStdMensagem->sDescricaoDocumento  = $oEventoContabil->getDescricaoDocumento();
    $oStdMensagem->sDescricaoLancamento = $this->getDescricao(); 
    
    /**
     * Alteração na regra dos lancamentos para validarmos a opção lObrigatorio da regra
     * - caso a regra retorne false e o lançamento seja obrigatório $this->lObrigatorio, lançar excessão
     */
    if ($oContaContabil === false) {
    	
      if ($this->lObrigatorio === true) {
    	  throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "sem_regra", $oStdMensagem));
      }
      
      /**
       * Caso a regra retorne false e o lançamento não seja obrigatório, apenas return true;
       */
      return true;
    }
    
    /**
     * Valida se as contas estão disponiveis para lançamento no ano atual
     */
    $oDaoComplanoReduz = db_utils::getDao("conplanoreduz");
    $sSqlCreditoReduz  = $oDaoComplanoReduz->sql_query_file($oContaContabil->getContaCredito(), $iAnoUsu, 'c61_codcon');
    $rsCreditoReduz    = $oDaoComplanoReduz->sql_record($sSqlCreditoReduz);

    /**
     * Conta crédito não encontrada
     */
    if ($oDaoComplanoReduz->numrows == 0) {

      $oStdMensagem->sTipoConta = 'crédito';
      $oStdMensagem->iConta     = $oContaContabil->getContaCredito();
      throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "conta_nao_encontrada_exercicio", $oStdMensagem));
    }

    $sSqlDebitoReduz = $oDaoComplanoReduz->sql_query_file($oContaContabil->getContaDebito(), $iAnoUsu, 'c61_codcon');
    $rsDebitoReduz   = $oDaoComplanoReduz->sql_record($sSqlDebitoReduz);

    /**
     * Conta debito não encontrada
     */
    if ($oDaoComplanoReduz->numrows == 0) {
      
      $oStdMensagem->sTipoConta = 'debito';
      $oStdMensagem->iConta     = $oContaContabil->getContaDebito();
      throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "conta_nao_encontrada_exercicio", $oStdMensagem));
    }

    /**
     * Inclui os valores do lançamento contábil para a conta débito/crédito do lançamento
     */
    $oDaoValorLancamento              = db_utils::getDao('conlancamval');
    $oDaoValorLancamento->c69_codlan  = $iCodigoLancamento;
    $oDaoValorLancamento->c69_credito = $oContaContabil->getContaCredito();
    $oDaoValorLancamento->c69_debito  = $oContaContabil->getContaDebito();

    $iHistoricoAuxiliar = $oLancamentoAuxiliar->getHistorico();
    $oDaoValorLancamento->c69_codhist = $oLancamentoAuxiliar->getHistorico();
    if (empty($iHistoricoAuxiliar)){
      $oDaoValorLancamento->c69_codhist = $this->getHistorico();
    }
    $oDaoValorLancamento->c69_valor   = $oLancamentoAuxiliar->getValorTotal();
    $oDaoValorLancamento->c69_data    = $dtDataUsu;
    $oDaoValorLancamento->c69_anousu  = $iAnoUsu;
    $oDaoValorLancamento->incluir(null);

    if ($oDaoValorLancamento->erro_status == 0) {

      $sErroMsg  = "Não foi possível incluir os lançamentos do evento contabil.\n\n";
      $sErroMsg .= "Erro Técnico: ".str_replace("\\n", "\n", $oDaoValorLancamento->erro_msg);
      throw new BusinessException($sErroMsg);
    }

    $oContaCredito = ContaCorrenteFactory::getInstance($oDaoValorLancamento->c69_sequen,
                                                       $oContaContabil->getContaCredito(),
                                                       $oLancamentoAuxiliar);
    if ($oContaCredito) {
      $oContaCredito->salvar();
    }

    $oContaDebito = ContaCorrenteFactory::getInstance($oDaoValorLancamento->c69_sequen,
                                                      $oContaContabil->getContaDebito(),
                                                      $oLancamentoAuxiliar
                                                     );
    if ($oContaDebito) {
      $oContaDebito->salvar();
    }

    return true;
  }

}