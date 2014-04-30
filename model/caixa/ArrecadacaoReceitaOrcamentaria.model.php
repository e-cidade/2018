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
 * Model responsável por arrecadar uma receita orçamentaria
 * @author bruno.silva@dbseller.com.br
 *
 */
class ArrecadacaoReceitaOrcamentaria {

  /**
   * Id da autenticação do dia
   * @var integer
   */
  protected $iId;

  /**
   * Data da autenticação
   * @var date
   */
  protected $dtAutenticacao;

  /**
   * sequencial da máquina autenticadora
   * @var integer
   */
  protected $iAutenticadora;

  /**
   * Receita Orcamentaria
   * @var ReceitaOrcamentaria
   */
  protected $oReceitaOrcamentaria;

  /**
   * Caracteristica Peculiar
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo do CGM
   * @var integer
   */
  protected $iCodigoCgm;

  /**
   * Valor arrecadado
   * @var float
   */
  protected $nValorArrecadado;


  /**
   * Conta da qual é debitado o valor da arrecadado
   * @var integer
   */
  protected $iContaDebito;

  /**
   * Observação do lançamento contabil
   * @var string
   */
  protected $sObservacaoHistorico;

  /**
   * Define se a arrecadação é de desconto
   * @var boolean
   */
  protected $lDesconto = false;

  /**
   * Seta a Receita Orçamentária que está sendo arrecadada
   * @param ReceitaOrcamentaria $oReceitaOrcamentaria
   */
  public function __construct(ReceitaOrcamentaria $oReceitaOrcamentaria, $dtAutenticacao, $iId, $iAutenticadora, $iContaDebito) {

    $this->dtAutenticacao       = $dtAutenticacao;
    $this->iId                  = $iId;
    $this->iAutenticadora       = $iAutenticadora;
    $this->oReceitaOrcamentaria = $oReceitaOrcamentaria;
    $this->iContaDebito         = $iContaDebito;
  }

  /**
   * Retorna a característica peculiar
   * @return string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }

  /**
   * Seta a característica peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna o código do CGM
   * @return integer
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * Seta o código do CGM
   * @param integer $iCodigoCgm
   */
  public function setCodigoCgm($iCodigoCgm) {
    $this->iCodigoCgm = $iCodigoCgm;
  }

  /**
   * Retorna o valor arrecadado
   * @return float
   */
  public function getValorArrecadado() {
    return $this->nValorArrecadado;
  }

  /**
   * Seta o valor que está sendo arrecadado
   * @param float $sValorArrecadado
   */
  public function setValorArrecadado($nValorArrecadado) {
    $this->nValorArrecadado = $nValorArrecadado;
  }

  /**
   * retorna o valor do historico para o lançamento
   * @return string
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }

  /**
   * Seta o valor do historico para o lançamento
   * @param string $sValorArrecadado
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }

  /**
   * Função que arrecada a receita passada como parâmetro no construtor
   * @param boolean - $lEstorno
   * @return boolean - true
   */
  public function arrecadar($lEstorno = false) {

    /**
     * Verificamos se a receita instanciada possui Recurso 1 - LIVRE e possui desdobramentos
     */
    $aReceita = array();
    if ($this->oReceitaOrcamentaria->getTipoRecurso() == 1) {
      $aReceita = $this->oReceitaOrcamentaria->getDesdobramentos();
    }

    $nValorArrecadar      = $this->getValorArrecadado();
    $iTotalDesdobramentos = count($aReceita);

    if($iTotalDesdobramentos == 0) {

      $aReceita[0]                          = new stdClass();
      $aReceita[0]->nValorLancamento        = round($this->getValorArrecadado(), 2);
      $aReceita[0]->sCaracteristicaPeculiar = $this->getCaracteristicaPeculiar();
      $aReceita[0]->iReceita                = $this->oReceitaOrcamentaria->getCodigo();

    } else {

      /**
       * Percorremos os desdobramentos aplicando o percentual encontrado no valor total a ser arrecadado
       */
      $nValorAcumulado = 0;
      for($iDesdobramento = 0; $iDesdobramento < $iTotalDesdobramentos; $iDesdobramento++) {

        $oStdDesdobramento         = $aReceita[$iDesdobramento];


        

        $nValorPercentualAplicado  = db_formatar(round ( $nValorArrecadar * ( $oStdDesdobramento->nPercentual / 100), 2), 'p')+ 0;
        $nValorPercentual          = round($nValorPercentualAplicado, 2) + 0;
        $nValorAcumulado          += $nValorPercentual;

        if ($nValorPercentual == 0) {
          continue;
        }
        $oStdDesdobramento->nValorLancamento = $nValorPercentual;
      }

      /**
       * Quando o valor acumulado for menor que o total a ser arrecadado, somamos a diferença
       * na última receita
       */
      if ($nValorAcumulado < $nValorArrecadar ) {
        $aReceita[$iTotalDesdobramentos-1]->nValorLancamento += round($nValorArrecadar - $nValorAcumulado, 2);
      }
    }

    /**
     * Percorremos as receitas executando os lançamentos contábeis para cada uma delas
     */
    foreach ($aReceita as $oReceita) {


      $oDaoOrcReceita         = db_utils::getDao('orcreceita');
      $sCamposContaCredito    = "conplanoreduz.c61_reduz, ";
      $sCamposContaCredito   .= "conplanoorcamentogrupo.c21_codcon as grupoorcamento, conplanoorcamento.c60_codcon, ";
      $sCamposContaCredito   .= "conplanoconplanoorcamento.c72_sequencial";
      $sSqlBuscaContaCredito  = $oDaoOrcReceita->sql_query_validacao_receita($this->oReceitaOrcamentaria->getAno(),
                                                                             $oReceita->iReceita,
                                                                             $sCamposContaCredito, null, "conplanoorcamentogrupo.c21_instit = " . db_getsession("DB_instit"));
      $rsBuscaContaCredito   = $oDaoOrcReceita->sql_record($sSqlBuscaContaCredito);

      if ($oDaoOrcReceita->erro_status == "0") {
        throw new BusinessException("Não foi possível executar a instrução para buscar os dados da receita. Contate o Suporte.");
      }

      $oStdDadosCredito = db_utils::fieldsMemory($rsBuscaContaCredito, 0);

      if (empty($oStdDadosCredito->c72_sequencial)) {

        $sMensagem  = "A conta {$this->oReceitaOrcamentaria->getContaOrcamento()->getCodigoConta()} da receita {$oReceita->iReceita} ";
        $sMensagem .= "não está vinculada à uma conta do plano PCASP.";
        throw new BusinessException($sMensagem);
      }

      if (empty($oStdDadosCredito->grupoorcamento)) {

        $sMensagem  = "A conta {$this->oReceitaOrcamentaria->getContaOrcamento()->getCodigoConta()} da receita {$oReceita->iReceita} ";
        $sMensagem .= "não está vinculada a um grupo do orçamento.";
        throw new BusinessException($sMensagem);
      }

      if (empty($oStdDadosCredito->c61_reduz)) {

        $sMensagem  = "A conta {$this->oReceitaOrcamentaria->getContaOrcamento()->getCodigoConta()} da receita {$oReceita->iReceita} ";
        $sMensagem .= "não possui reduzido.";
        throw new BusinessException($sMensagem);
      }

      $oDaoCorgrupoCorrente = db_utils::getDao('corgrupocorrente');
      $sSqlCorrente  = $oDaoCorgrupoCorrente->sql_query_file(null,
                                                            "k105_sequencial",
                                                            null,
                                                            "   k105_id     = {$this->iId}
                                                            and k105_autent = {$this->iAutenticadora}
                                                            and k105_data   = '{$this->dtAutenticacao}'");

      $rsCorrenteGrupo      = $oDaoCorgrupoCorrente->sql_record($sSqlCorrente);
      $iCodigoGrupoCorrente = null;
      if ($oDaoCorgrupoCorrente->numrows > 0) {
        $iCodigoGrupoCorrente = db_utils::fieldsMemory($rsCorrenteGrupo,0)->k105_sequencial;
      }

      list($iAnoAutenticacao, $iMesAutenticacao, $iDiaAutenticacao) = explode("-", $this->dtAutenticacao);
      $oLancamentoAuxiliar  = new LancamentoAuxiliarArrecadacaoReceita();
      $oLancamentoAuxiliar->setCodigoCgm($this->iCodigoCgm);
      $oLancamentoAuxiliar->setCodigoContaCorrente($this->iContaDebito);
      $oLancamentoAuxiliar->setCodigoReceita($oReceita->iReceita);
      $oLancamentoAuxiliar->setHistorico(9100);
      $oLancamentoAuxiliar->setCodigoContaOrcamento($oStdDadosCredito->c60_codcon);
      $oLancamentoAuxiliar->setMesLancamento($iMesAutenticacao);
      $oLancamentoAuxiliar->setContaCredito($oStdDadosCredito->c61_reduz);
      $oLancamentoAuxiliar->setContaDebito($this->iContaDebito);
      $oLancamentoAuxiliar->setObservacaoHistorico($this->sObservacaoHistorico);
      $oLancamentoAuxiliar->setValorTotal(abs($oReceita->nValorLancamento));
      $oLancamentoAuxiliar->setCodigoGrupoCorrente($iCodigoGrupoCorrente);
      $oLancamentoAuxiliar->setCaracteristicaPeculiar($oReceita->sCaracteristicaPeculiar);

      $iCodigoTipoDocumento = 100;
      if ($lEstorno) {

        $iCodigoTipoDocumento = 101;
        $oLancamentoAuxiliar->setEstorno(true);
        $oLancamentoAuxiliar->setContaCredito($this->iContaDebito);
        $oLancamentoAuxiliar->setContaDebito($oStdDadosCredito->c61_reduz);
      }

      $oDocumentoContabil = SingletonRegraDocumentoContabil::getDocumento($iCodigoTipoDocumento);
      $oDocumentoContabil->setValorVariavel("[codigoreceita]", $oReceita->iReceita);
      
      $oDocumentoContabil->setValorVariavel("[instituicaogrupoconta]", db_getsession("DB_instit"));
      
      $oDocumentoContabil->setValorVariavel("[anousureceita]", $iAnoAutenticacao);
      $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
      $oLancamentoAuxiliar->setCodigoDocumento($iCodigoDocumentoExecutar);
      $oLancamentoContabil = new EventoContabil($iCodigoDocumentoExecutar, $iAnoAutenticacao);
      $oLancamentoContabil->executaLancamento($oLancamentoAuxiliar, $this->dtAutenticacao);

      /**
       * Verificamos se é um lançamento de desconto.
       * Caso seja executamos o lançamento de desconto nos documentos pré definidos
       */
      if ($this->lDesconto) {

        $oLancamentoAuxiliar->setContaCredito($this->iContaDebito);
        $oLancamentoAuxiliar->setContaDebito($oStdDadosCredito->c61_reduz);
        $iCodigoDocumentoExecutar = 418;
        if ($lEstorno) {

          $oLancamentoAuxiliar->setContaCredito($oStdDadosCredito->c61_reduz);
          $oLancamentoAuxiliar->setContaDebito($this->iContaDebito);
          $iCodigoDocumentoExecutar = 419;
        }
        $oLancamentoContabil = new EventoContabil($iCodigoDocumentoExecutar, $iAnoAutenticacao);
        $oLancamentoContabil->executaLancamento($oLancamentoAuxiliar);
      }

    }
    return true;
  }

  /**
   * Seta se o lançamento é de desconto
   * @param boolean $lDesconto
   */
  public function setDesconto($lDesconto) {
    $this->lDesconto = $lDesconto;
  }

  /**
   * Retorna o valo para lDesconto
   * @return boolean
   */
  public function getDesconto() {
    return $this->lDesconto;
  }

}