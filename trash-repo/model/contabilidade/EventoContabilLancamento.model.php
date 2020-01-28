<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model para controle dos Lancamentos de uma transa��o
 * @author  matheus.felini@dbseller.com.br
 * @version $Revision: 1.21 $
 * @package contabilidade
 */

require_once ("contacorrente/ContaCorrenteFactory.model.php");

class EventoContabilLancamento {

  /**
   * Sequencial do Lancamento
   * @var integer
   */
  protected $iSequencialLancamento;

  /**
   * Sequencial da Transa��o
   * @var integer
   */
  protected $iSequencialTransacao;

  /**
   * C�digo do Hist�rico
   * @var integer
   */
  protected $iHistorico;

  /**
   * Observa��o do Lan�amento
   * @var string
   */
  protected $sObservacao;

  /**
   * Valor do Lan�amento
   * @var float
   */
  protected $nValor;

  /**
   * Obrigatorio
   * @var boolean
   */
  protected $lObrigatorio;

  /**
   * C�digo do Evento
   * @var integer
   */
  protected $iEvento;

  /**
   * Descri��o do Lan�amento
   * @var string
   */
  protected $sDescricao;

  /**
   * Ordem do Lan�amento
   * @var integer
   */
  protected $iOrdem;

  /**
   * Regras localizadas para o Lan�amento
   * @var array
   */
  protected $aRegrasLancamento = array();

  static $iTotalInstancias = 0;
  
  const CAMINHO_MENSAGEM = 'financeiro.contabilidade.EventoContabilLancamento.';

  /**
   * Seta valor para o objeto em quest�o e cria um vetor com todas as regras deste objeto
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
        throw new Exception("Dados do lan�amento {$iCodigoLancamento} n�o localizado.\n\n{$oDaoLancamento->erro_msg}");
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
   * Salva os dados de um lan�amento cont�bil
   * @throws Exception
   */
  public function salvar() {

    /*
     * Inserimos os dados do Lan�amento em contranslan
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
     * Definimos se vamos incluir um novo lan�amento ou se alteramos um existente
    */
    if ($this->getSequencialLancamento() == "") {

      $oDaoContransLan->c46_ordem = $this->getProximaOrdemLancamentoTransacao();
      $this->setOrdem($oDaoContransLan->c46_ordem);
      $oDaoContransLan->incluir(null);
      if ($oDaoContransLan->erro_status == 0) {
        throw new Exception("N�o foi poss�vel salvar os dados do lan�amento.\n\n{$oDaoContransLan->erro_msg}");
      }
      $this->setSequencialLancamento($oDaoContransLan->c46_seqtranslan);
    } else {
      $oDaoContransLan->alterar($this->getSequencialLancamento());
    }

    if ($oDaoContransLan->erro_status == 0) {
      throw new Exception("N�o foi poss�vel salvar os dados do lan�amento.\n\n{$oDaoContransLan->erro_msg}");
    }
    return true;
  }

  /**
   * Exclui as regras de um lan�amento e o pr�prio lan�amento
   * @throws Exception
   */
  public function excluir() {

    /*
     * Verifico se existem regras para o lan�amento instanciado. Caso exita, percorremos a cole��o de objetos
     * chamando o m�todo excluir o objeto RegraLancamentoContabil
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
      throw new Exception("N�o foi poss�vel excluir o lan�amento cont�bil.\n\n{$oDaoContransLan->erro_msg}");
    }
  }

  /**
   * M�todo que retorna a ordem do pr�ximo lan�amento de uma transa��o
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
   * Seta se � obrigatorio
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
   * Retorna o sequencial do lan�amento
   * @return integer
   */
  public function getSequencialLancamento() {
    return $this->iSequencialLancamento;
  }

  /**
   * Retorna o sequencial da transa��o
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
   * @return array
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

  public function getRegraLancamento ($iDocumento, $iSequencialLancamento, $oLancamentoAuxiliar, DBDate $dtDataUsu) {
    /**
     * Busca as Contas para efetuar lan�amento
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
     * Altera��o na regra dos lancamentos para validarmos a op��o lObrigatorio da regra
     * - caso a regra retorne false e o lan�amento seja obrigat�rio $this->lObrigatorio, lan�ar excess�o
     */
    if ($oContaContabil === false) {
    	
      if ($this->lObrigatorio === true) {
    	  throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "sem_regra", $oStdMensagem));
      }
      
      /**
       * Caso a regra retorne false e o lan�amento n�o seja obrigat�rio, apenas return true;
       */
      return true;
    }
    
    /**
     * Valida se as contas est�o disponiveis para lan�amento no ano atual
     */
    $oDaoComplanoReduz = db_utils::getDao("conplanoreduz");
    $sSqlCreditoReduz  = $oDaoComplanoReduz->sql_query_file($oContaContabil->getContaCredito(), $iAnoUsu, 'c61_codcon');
    $rsCreditoReduz    = $oDaoComplanoReduz->sql_record($sSqlCreditoReduz);

    /**
     * Conta cr�dito n�o encontrada
     */
    if ($oDaoComplanoReduz->numrows == 0) {

      $oStdMensagem->sTipoConta = 'cr�dito';
      $oStdMensagem->iConta     = $oContaContabil->getContaCredito();
      throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "conta_nao_encontrada_exercicio", $oStdMensagem));
    }

    $sSqlDebitoReduz = $oDaoComplanoReduz->sql_query_file($oContaContabil->getContaDebito(), $iAnoUsu, 'c61_codcon');
    $rsDebitoReduz   = $oDaoComplanoReduz->sql_record($sSqlDebitoReduz);

    /**
     * Conta debito n�o encontrada
     */
    if ($oDaoComplanoReduz->numrows == 0) {
      
      $oStdMensagem->sTipoConta = 'debito';
      $oStdMensagem->iConta     = $oContaContabil->getContaDebito();
      throw new BusinessException(_M( EventoContabilLancamento::CAMINHO_MENSAGEM . "conta_nao_encontrada_exercicio", $oStdMensagem));
    }

    /**
     * Inclui os valores do lan�amento cont�bil para a conta d�bito/cr�dito do lan�amento
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

      $sErroMsg  = "N�o foi poss�vel incluir os lan�amentos do evento contabil.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoValorLancamento->erro_msg}";
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