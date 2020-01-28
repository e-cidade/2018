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
 * Model para encapsulamento de um SLIP
 * @author Matheus Felini / Bruno Silva
 * @package caixa
 * @subpackage slip
 * @version $Revision: 1.21 $
 */
abstract class Transferencia {

  /**
   * Objeto Slip
   * @var slip
   */
  protected $oSlip;

  /**
   * Tipo de operaзгo da tansferencia
   * @var integer
   */
  protected $iTipoOperacao;

  /**
   * Cуdigo do Lanзamento contбbil executado pela transferencia
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * Codigo do terminal da autenticacao
   * @var integer
   */
  private $iIDTerminal;

  /**
   * numero sequencial da autenticao
   * @var integer
   */
  private $iNumeroAutenticacao;

  /**
   * data da autenticao
   * @var string
   */
  
  
  /**
   * string para autenticaзгo
   * @var string
   */
  private $sStringAutenticacao;
  
  private $dtDataAutenticacao;
  /**
   * Construtor da classe
   */
  public function __construct($iCodigoSlip = null) {
    $this->oSlip = new slip($iCodigoSlip);
  }


  
  
  /**
   * Retorna o cуdigo do lanзamento
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Salva os dados do slip
   */
  public function salvar() {
    $this->oSlip->save();
  }

  /**
   * Executa os lancamentos contabeis do slip
   * @throws Exception
   * @return boolean true
   */
  public function executarLancamentoContabil($sDataLancamento = null, $lEstorno = false) {

    $iCodigoDocumento        = $this->getDocumentoPorTipoInclusao();

    if ($lEstorno) {

      switch ($iCodigoDocumento) {

        case 160:
          $iCodigoDocumento = 162;
          break;

        case 150:
          $iCodigoDocumento = 152;
          break;
      }
    }

    $sSqlBuscaContaCorrente  = "select corrente.k12_conta,   ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_id,      ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_data,    ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_autent   ";
    $sSqlBuscaContaCorrente .= "  from corrente                                                        ";
    $sSqlBuscaContaCorrente .= "       inner join corlanc  on corrente.k12_id     = corlanc.k12_id     ";
    $sSqlBuscaContaCorrente .= "                          and corrente.k12_data   = corlanc.k12_data   ";
    $sSqlBuscaContaCorrente .= "                          and corrente.k12_autent = corlanc.k12_autent ";
    $sSqlBuscaContaCorrente .= " where corlanc.k12_codigo = {$this->getCodigoSlip()} ";
    $sSqlBuscaContaCorrente .= " order by k12_autent desc limit 1";
    $rsBuscaCorrente         = db_query($sSqlBuscaContaCorrente);

    if (pg_num_rows($rsBuscaCorrente) == 0) {
    	throw new Exception("Conta nгo localizada na tabela corrente.");
    }

    $oDadosAutenticao        = db_utils::fieldsMemory($rsBuscaCorrente, 0);
    $iCodigoContaCorrente    = $oDadosAutenticao->k12_conta;
    $oLancamentoAuxiliarSlip = new LancamentoAuxiliarSlip();

    $oLancamentoAuxiliarSlip->setIDTerminal($oDadosAutenticao->k12_id);
    $oLancamentoAuxiliarSlip->setDataAutenticacao($oDadosAutenticao->k12_data);
    $oLancamentoAuxiliarSlip->setNumeroAutenticacao($oDadosAutenticao->k12_autent);

    $oLancamentoAuxiliarSlip->setHistorico($this->getHistorico());
    $oLancamentoAuxiliarSlip->setValorTotal($this->getValor());
    $oLancamentoAuxiliarSlip->setObservacaoHistorico($this->getObservacao());
    $oLancamentoAuxiliarSlip->setCodigoSlip($this->getCodigoSlip());
    $oLancamentoAuxiliarSlip->setCodigoReduzido($iCodigoContaCorrente);
    $oLancamentoAuxiliarSlip->setFavorecido($this->getCodigoCgm());
    $oLancamentoAuxiliarSlip->setEstorno(false);
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarCredito($this->getCaracteristicaPeculiarCredito());
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarDebito($this->getCaracteristicaPeculiarDebito());

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

    $oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    $aDocumentosEstorno       = array(121, 131, 141, 151, 152, 153, 162, 163);
    if (in_array($iCodigoDocumentoExecutar, $aDocumentosEstorno)) {
      $oLancamentoAuxiliarSlip->setEstorno(true);
    }
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarSlip, $sDataLancamento);
    $this->iCodigoLancamento =  $oEventoContabil->getCodigoLancamento();
    return true;
  }

  /**
   * Retorna o tipo de operacao que usuбrio incluiu de acordo com a tabela sliptipooperacao
   * @throws Exception
   * @return integer codigo do tipo da operacao (sliptipooperacao)
   */
  public function getTipoOperacaoPorInclusao() {

    if (empty($this->iTipoOperacao)) {

      /*
       * Busca o tipo de inclusгo para descobrirmos qual documento vamos executar
       */
      $oDaoSlipTipoOperacao  = db_utils::getDao('sliptipooperacaovinculo');
      $sSqlBuscaTipoOperacao = $oDaoSlipTipoOperacao->sql_query_file($this->getCodigoSlip());
      $rsBuscaTipoOperacao   = $oDaoSlipTipoOperacao->sql_record($sSqlBuscaTipoOperacao);
      if ($oDaoSlipTipoOperacao->numrows == 0) {
      	throw new Exception("Nгo foi possнvel localizar o tipo de operaзгo do slip {$this->getCodigoSlip()}.");
      }
      $iTipoOperacao       = db_utils::fieldsMemory($rsBuscaTipoOperacao, 0)->k153_slipoperacaotipo;
      $this->iTipoOperacao = $iTipoOperacao;
    }
    return $this->iTipoOperacao;
  }

  /**
   * Autentica um slip
   * @throws Exception
   * @return boolean
   */
  public function executaAutenticacao() {

    $iIp                = db_getsession("DB_ip");
    $oDaocfautent       = db_utils::getDao('cfautent');
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sSqlAutenticadora  = $oDaocfautent->sql_query_file(null,
                                                        "k11_id,
                                                         k11_tipautent",
                                                         '',
                                                         "k11_ipterm = '{$iIp}'
                                                         and k11_instit = {$iInstituicaoSessao}"
                                                        );
    $rsAutenticador    = $oDaocfautent->sql_record($sSqlAutenticadora);

    if ($oDaocfautent->numrows == '0') {
      throw new Exception("Cadastre o ip {$iIp} como um caixa.");
    }

    $iCodigoTerminal    = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
    $iCodigoSlip        = $this->getCodigoSlip();
    $iCodigoInstituicao = db_getsession("DB_instit");
    $dtSessao           = date("Y-m-d", db_getsession("DB_datausu"));

    $sSqlExecutaAutenticacao = "select fc_auttransf({$iCodigoSlip}, '{$dtSessao}', '{$iIp}', true, 0, {$iCodigoInstituicao}) as fc_autenticacao";
    $rsExecutaAutenticacao = db_query($sSqlExecutaAutenticacao);
    if (!$rsExecutaAutenticacao) {
      throw new Exception("Nгo foi possнvel realizar a autenticaзгo");
    }
    $sStringAutenticacao = db_utils::fieldsMemory($rsExecutaAutenticacao, 0)->fc_autenticacao;
    if (substr($sStringAutenticacao, 0, 1) != 1) {
      throw new Exception("Nгo foi possнvel executar a autenticaзгo.\n\n{$sStringAutenticacao}");
    }

    $this->setIDTerminal($iCodigoTerminal);
    $this->setDataAutenticacao($dtSessao);
    $this->setNumeroAutenticacao(substr($sStringAutenticacao, 1, 7));
    $this->setStringAutenticacao($sStringAutenticacao);

    return true;
  }




  /**
   * Retorna o documento por tipo de inclusгo
   * @throws Exception
   * @return integer - Codigo do documento que serб executado no lanзamento contбbil
   */
  public function getDocumentoPorTipoInclusao() {

    $iTipoOperacao    = $this->getTipoOperacaoPorInclusao();
    $iCodigoDocumento = 0;
    switch ($iTipoOperacao) {

    	/**
    	 * Transferencia Financeira
    	 */
    	case 1:
    		$iCodigoDocumento = 120;
    		break;
    	case 2:
    		$iCodigoDocumento = 121;
    		break;
    	case 3:
    		$iCodigoDocumento = 130;
    		break;
    	case 4:
    		$iCodigoDocumento = 131;
    		break;

    		/**
    		 * Transferencia Bancaria
    		 */
    	case 5:
    		$iCodigoDocumento = 140;
    		break;
    	case 6:
    		$iCodigoDocumento = 141;
    		break;

    		/**
    		 * Cauзгo
    		 */
    	case 7:
    		$iCodigoDocumento = 150;
    		break;
    	case 8:
    		$iCodigoDocumento = 152;
    		break;
    	case 9:
    		$iCodigoDocumento = 151;
    		break;
    	case 10:
    		$iCodigoDocumento = 153;
    		break;

    		/**
    		 * Depуsito de Diversas Origens
    		 */
    	case 11:
    		$iCodigoDocumento = 160;
    		break;
    	case 12:
    		$iCodigoDocumento = 162;
    		break;
    	case 13:
    		$iCodigoDocumento = 161;
    		break;
    	case 14:
    		$iCodigoDocumento = 163;
    		break;
    }
    return $iCodigoDocumento;
  }

  /**
   * Anula um slip
   * @param string $sMotivo
   */
  public function anular($sMotivo) {
    $this->oSlip->anular($sMotivo, true, $this);
  }

  public function setCodigoSlip($iCodigoSlip) {
    $this->oSlip->setSlip($iCodigoSlip);
  }

  public function getCodigoSlip() {
  	return $this->oSlip->getSlip();
  }

  /**
   * @return array
   */
  public function getArrecacoes() {
  	return $this->oSlip->getArrecacoes();
  }

  /**
   * @param integer $iArrecacoes
   */
  public function adicionarArrecadacao($iArrecadacoes) {
    $this->oSlip->addArrecadacao($iArrecadacoes);
  }

  /**
   * @return array
   */
  public function getPagamentos() {
  	return $this->oSlip->getPagamentos();
  }

  /**
   * @param array $aPagamentos
   */
  private function setPagamentos($aPagamentos) {
  	$this->oSlip->setPagamentos($aPagamentos);
  }

  /**
   * @return array
   */
  public function getRecursos() {
  	return $this->oSlip->getRecursos();
  }

  /**
   * Adiciona um Recurso ao Slip
   *
   * @param integer $iRecurso codigo do recurso
   * @param float   $nValor valor do Recurso
   */
  public function adicionarRecurso($iRecurso, $nValor = 0) {
    $this->oSlip->addRecurso($iRecurso, $nValor);
  }

  /**
   * @return string
   */

  public function getData() {
  	return $this->oSlip->getData();
  }

  /**
   * @param string $dtData
   */

  public function setData($dtData) {
  	 $this->oSlip->setData($dtData);
  }

  /**
   * @return integer
   */
  public function getContaCredito() {
  	return  $this->oSlip->getContaCredito();
  }

  /**
   * @param integer $iContaCredito
   */
  public function setContaCredito($iContaCredito) {
  	$this->oSlip->setContaCredito($iContaCredito);
  }

  /**
   * @return integer
   */
  public function getContaDebito() {
  	return $this->oSlip->getContaDebito();
  }

  /**
   * @param integer $iContaDebito
   */
  public function setContaDebito($iContaDebito) {
  	$this->oSlip->setContaDebito($iContaDebito);
  }

  /**
   * @return integer
   */
  public function getSituacao() {
  	return $this->oSlip->getSituacao();
  }

  /**
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->oSlip->setSituacao($iSituacao);
  }

  /**
   * @return unknown_type
   */
  public function getTipoPagamento() {
    return $this->oSlip->getTipoPagamento();
  }

  /**
   * @param unknown_type $iTipoPagamento
   */
  public function setTipoPagamento($iTipoPagamento) {
    $this->oSlip->setTipoPagamento($iTipoPagamento);
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->oSlip->getValor();
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->oSlip->setValor($nValor);
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->oSlip->getObservacoes();
  }

  /**
   * @param string $sObservacoes
   */
  public function setObservacao($sObservacoes) {
    $this->oSlip->setObservacoes($sObservacoes);
  }
  /**
   * @return integer
   */
  public function getCodigoCgm() {
    return $this->oSlip->getNumCgm();
  }

  /**
   * @param integer $iNumCgm
   */
  public function setCodigoCgm($iNumCgm) {
  	$this->oSlip->setNumCgm($iNumCgm);
  }
  /**
   * @return integer
   */
  public function getHistorico() {
  	return $this->oSlip->getHistorico();
  }

  /**
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->oSlip->setHistorico($iHistorico);
  }
  /**
   * @return integer
   */
  public function getMovimento() {
    return $this->oSlip->getMovimento();
  }

  /**
   * Retorna a instituiзгo que criou a transferencia
   * @return integer
   */
  public function getInstituicao() {
    return $this->oSlip->getInstituicao();
  }
  /**
   * Seta a instituiзгo que criou a transferencia
   * @param integer
   */
  public function setInstituicao($iInstituicao) {
  	$this->oSlip->setInstituicao($iInstituicao);
  }

  /**
   * Seta a caracteristica peculiar debito
   * @param string $sCodigoCaracteristica
   */
  public function setCaracteristicaPeculiarDebito($sCodigoCaracteristica) {
    $this->oSlip->setCaracteristicaPeculiarDebito($sCodigoCaracteristica);
  }

  /**
   * Retorna a caracteristica peculiar debito
   * @param string
   */
  public function getCaracteristicaPeculiarDebito() {
  	return $this->oSlip->getCaracteristicaPeculiarDebito();
  }

  /**
   * Seta a caracteristica peculiar credito
   * @param string $sCodigoCaracteristica
   */
  public function setCaracteristicaPeculiarCredito($sCodigoCaracteristica) {
  	$this->oSlip->setCaracteristicaPeculiarCredito($sCodigoCaracteristica);
  }

  /**
   * Retorna a caracteristica peculiar credito
   * @param string
   */
  public function getCaracteristicaPeculiarCredito() {
  	return $this->oSlip->getCaracteristicaPeculiarCredito();
  }

  /**
   * Seta o tipo de operaзгo
   * @param integer $iTipoOperacao
   */
  public function setTipoOperacao($iTipoOperacao) {
    $this->iTipoOperacao = $iTipoOperacao;
  }

  /**
   * Retorna o tipo de operaзгo de um slip
   * @return integer
   */
  public function getTipoOperacao() {
    return $this->iTipoOperacao;
  }

  /**
   * Define a data de autenticacao do SLIP
   * @param string $sDataAutenticacao data da autenticacao
   */
  public function setDataAutenticacao($sDataAutenticacao) {
    $this->dtDataAutenticacao = $sDataAutenticacao;
  }

  /**
   * retorna a data de autenticacao do SLIP
   * @return string data de autenticacao
   */
  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /**
   * Define o Numero autenticacao do SLIP
   * @param integer $iNumeroAutenticacao numero da autenticacao
   */
  public function setNumeroAutenticacao($iNumeroAutenticacao) {
    $this->iNumeroAutenticacao = $iNumeroAutenticacao;
  }

  /**
   * retorna  numero da autenticacao
   * @return integer numero da autenticacao
   */
  public function getNumeroAutenticacao() {
    return $this->iNumeroAutenticacao;
  }

  /**
   * Define o o id do terminal da autenticacao do SLIP
   * @param integer $iIDTerminal o id do terminal da autenticacao
   */
  public function setIDTerminal($iIDTerminal) {
    $this->iIDTerminal = $iIDTerminal;
  }

  /**
   * retorna o id do terminal da autenticacao
   * @return integer id do terminal autenticacao
   */
  public function getIDTerminal() {
    return $this->iIDTerminal;
  }

  /**
   * Define string para impressao
   * @param string
   */
  public function setStringAutenticacao($sStringAutenticacao) {
  	$this->setStringAutenticacao = $sStringAutenticacao;
  }
  
  /**
   * retorna string para impressao
   * @return string
   */
  public function getStringAutenticacao() {
  	return $this->setStringAutenticacao;
  }
  
  
  
  
  

}
?>