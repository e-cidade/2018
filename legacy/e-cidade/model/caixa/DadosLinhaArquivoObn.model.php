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
 * Classe que representa os dados usados na construção uma linha do processamento de arquivo OBN
 * As propriedades presentes na classe são usadas nos layouts de linha do tipo 3  e 4 (pagamento Código de Barra) 
 * @author Bruno Silva bruno.silva@dbseller.com.br
 * @package caixa
 */
class DadosLinhaArquivoObn {

  /**
   * Código de Barra
   * @var String
   */
  private $sCodigoBarra;

  /**
   * Valor Nominal da movimentação
   * @var number
   */
  private $nValorNominal;

  /**
   * Data de Vencimento
   * @var date
   */
  private $dtDataVencimento;

  /**
   * Valor Juros
   * @var number
   */
  private $nValorJuros;       
  
  
  /**
   * Valor Desconton
   * @var number
   */
  private $nValorDesconto;    
  
  /**
   * Tipo da Fatura
   * @var integer
   */
  private $iTipoFatura;

  /**
   * Código do banco do fornecedor
   * @var number
   */
  private $iCodigoBancoFornecedor;

  /**
   * Código do arquivo no sistema
   * @var integer
   */
  private $iCodigoArquivoSistema;

  /**
   * Código do movimento
   * @var integer
   */
  private $iCodigoMovimento;   
  
  /**
   * Data da Geração do arquivo
   * @var date
   */
  private $dtDataGeracao;     
  
  /**
   * Data do processamento do arquivo
   * @var date
   */
  private $dtDataProcessamento; 
  
  /**
   * Código do banco do pagador
   * @var integer
   */
  private $iCodigoBancoPagador; 
  
  /**
   * Código da agência do pagador
   * @var integer
   */
  private $iCodigoAgenciaPagadora;

  /**
   * Digito verificador da agencia pagadora
   * @var string
   */
  private $sDigitoVerificadorAgenciaPagadora;
  
  /**
   * Código da Conta Pagadora
   * @var integer
   */
  private $iContaPagadora;

  /**
   * Digito Verificador da conta pagadora
   * @var string
   */
  private $sDigitoVerificadorContaPagadora; 
  
  
  /**
   * Código do banco do favorecido
   * @var integer
   */
  private $iCodigoBancoFavorecido;          

  /**
   * Código da agência do favorecido
   * @var integer
   */
  private $iCodigoAgenciaFavorecida;                 
  
  /**
   * Digito verificador da agencia do favorecido
   * @var string
   */
  private $sDigitoVerificadorAgenciaFavorecida;

  /**
   * Código da conta favorecida
   * @var integer
   */
  private $iContaFavorecida;

  /**
   * Digito verificador da conta favorecida
   * @var string
   */
  private $sDigitoVerificadorContaFavorecida;   

  /**
   * Código da operação do favorecido
   * @var integer
   */
  private $iCodigoOperacaoFavorecido;
  
  /**
   * Código da operação do favorecido
   * @var integer
   */
  private $iCodigoOperacaoPagador;
  
  /**
   * Tipo de conta do favorecido
   * @var integer
   */
  private $iTipoContaFavorecido;
  
  /**
   * Valor da movimentacao
   * @var number
   */
  private $nValor; 
  
  /**
   * Parte inteira do Valor da movimentacao
   * @var number
   */
  private $nValorInteiro;
  
  /**
   * Valor do lançamento
   * @var number
   */
  private $nLancamento;

  /**
   * Código do convenio
   * @var integer
   */
  private $iConvenio;

  /**
   * Código Cgm do favorecido
   * @var integer
   */
  private $iCGM;

  /**
   * Nome do favorecido
   * @var string
   */
  private $sNome;
  
  /**
   * Código Cnpj do favorecido
   * @var string
   */
  private $sCnpj;
  
  /**
   * Código endereço do favorecido
   * @var string
   */
  private $sEndereco;
   
  /**
   * Código número do endereço do favorecido
   * @var string
   */
  private $sNumeroEndereco;

  /**
   * Complemento do endereço do favorecido
   * @var string
   */
  private $sComplementoEndereco;

  /**
   * Bairro do favorecido
   * @var string
   */
  private $sBairro;

  /**
   * Municipio do favorecido
   * @var string
   */
  private $sMunicipio;

  /**
   * Cep do favorecido
   * @var string
   */
  private $sCep;

  /**
   * UF do favorecido
   * @var string
   */
  private $sUf;       
  
  public function getCodigoBarra() {
      return $this->sCodigoBarra;
  }

  public function setCodigoBarra($sCodigoBarra) {
      $this->sCodigoBarra = $sCodigoBarra;
  }

  public function getValorNominal() {
      return $this->nValorNominal;
  }

  public function setValorNominal($nValorNominal) {
      $this->nValorNominal = $nValorNominal;
  }

  public function getDataVencimento() {
      return $this->dtDataVencimento;
  }

  public function setDataVencimento($dtDataVencimento) {
      $this->dtDataVencimento = $dtDataVencimento;
  }

  public function getValorJuros() {
      return $this->nValorJuros;
  }

  public function setValorJuros($nValorJuros) {
      $this->nValorJuros = $nValorJuros;
  }

  public function getValorDesconto() {
      return $this->nValorDesconto;
  }

  public function setValorDesconto($nValorDesconto) {
      $this->nValorDesconto = $nValorDesconto;
  }

  public function getTipoFatura() {
      return $this->iTipoFatura;
  }

  public function setTipoFatura($iTipoFatura) {
      $this->iTipoFatura = $iTipoFatura;
  }

  public function getCodigoBancoFornecedor() {
      return $this->iCodigoBancoFornecedor;
  }

  public function setCodigoBancoFornecedor($iCodigoBancoFornecedor) {
      $this->iCodigoBancoFornecedor = $iCodigoBancoFornecedor;
  }

  public function getCodigoArquivoSistema() {
      return $this->iCodigoArquivoSistema;
  }

  public function setCodigoArquivoSistema($iCodigoArquivoSistema) {
      $this->iCodigoArquivoSistema = $iCodigoArquivoSistema;
  }

  public function getCodigoMovimento() {
      return $this->iCodigoMovimento;
  }

  public function setCodigoMovimento($iCodigoMovimento) {
      $this->iCodigoMovimento = $iCodigoMovimento;
  }

  public function getDataGeracao() {
      return $this->dtDataGeracao;
  }

  public function setDataGeracao($dtDataGeracao) {
      $this->dtDataGeracao = $dtDataGeracao;
  }

  public function getDataProcessamento() {
      return $this->dtDataProcessamento;
  }

  public function setDataProcessamento($dtDataProcessamento) {
      $this->dtDataProcessamento = $dtDataProcessamento;
  }

  public function getCodigoBancoPagador() {
      return $this->iCodigoBancoPagador;
  }

  public function setCodigoBancoPagador($iCodigoBancoPagador) {
      $this->iCodigoBancoPagador = $iCodigoBancoPagador;
  }

  public function getCodigoAgenciaPagadora() {
      return $this->iCodigoAgenciaPagadora;
  }

  public function setCodigoAgenciaPagadora($iCodigoAgenciaPagadora) {
      $this->iCodigoAgenciaPagadora = $iCodigoAgenciaPagadora;
  }

  public function getDigitoVerificadorAgenciaPagadora() {
      return $this->sDigitoVerificadorAgenciaPagadora;
  }

  public function setDigitoVerificadorAgenciaPagadora($sDigitoVerificadorAgenciaPagadora) {
      $this->sDigitoVerificadorAgenciaPagadora = $sDigitoVerificadorAgenciaPagadora;
  }

  public function getContaPagadora() {
      return $this->iContaPagadora;
  }

  public function setContaPagadora($iContaPagadora) {
      $this->iContaPagadora = $iContaPagadora;
  }

  public function getDigitoVerificadorContaPagadora() {
      return $this->sDigitoVerificadorContaPagadora;
  }

  public function setDigitoVerificadorContaPagadora($sDigitoVerificadorContaPagadora) {
      $this->sDigitoVerificadorContaPagadora = $sDigitoVerificadorContaPagadora;
  }

  public function getCodigoBancoFavorecido() {
      return $this->iCodigoBancoFavorecido;
  }

  public function setCodigoBancoFavorecido($iCodigoBancoFavorecido) {
      $this->iCodigoBancoFavorecido = $iCodigoBancoFavorecido;
  }

  public function getCodigoAgenciaFavorecida() {
      return $this->iCodigoAgenciaFavorecida;
  }

  public function setCodigoAgenciaFavorecida($iCodigoAgenciaFavorecida) {
      $this->iCodigoAgenciaFavorecida = $iCodigoAgenciaFavorecida;
  }

  public function getDigitoVerificadorAgenciaFavorecida() {
      return $this->sDigitoVerificadorAgenciaFavorecida;
  }

  public function setDigitoVerificadorAgenciaFavorecida($sDigitoVerificadorAgenciaFavorecida) {
      $this->sDigitoVerificadorAgenciaFavorecida = $sDigitoVerificadorAgenciaFavorecida;
  }

  public function getContaFavorecida() {
      return $this->iContaFavorecida;
  }

  public function setContaFavorecida($iContaFavorecida) {
      $this->iContaFavorecida = $iContaFavorecida;
  }

  public function getDigitoVerificadorContaFavorecida() {
      return $this->sDigitoVerificadorContaFavorecida;
  }

  public function setDigitoVerificadorContaFavorecida($sDigitoVerificadorContaFavorecida) {
      $this->sDigitoVerificadorContaFavorecida = $sDigitoVerificadorContaFavorecida;
  }

  public function getCodigoOperacaoFavorecido() {
      return $this->iCodigoOperacaoFavorecido;
  }

  public function setCodigoOperacaoFavorecido($iCodigoOperacaoFavorecido) {
      $this->iCodigoOperacaoFavorecido = $iCodigoOperacaoFavorecido;
  }

  public function getCodigoOperacaoPagador() {
      return $this->iCodigoOperacaoPagador;
  }

  public function setCodigoOperacaoPagador($iCodigoOperacaoPagador) {
      $this->iCodigoOperacaoPagador = $iCodigoOperacaoPagador;
  }

  public function getTipoContaFavorecido() {
      return $this->iTipoContaFavorecido;
  }

  public function setTipoContaFavorecido($iTipoContaFavorecido) {
      $this->iTipoContaFavorecido = $iTipoContaFavorecido;
  }

  public function getValor() {
      return $this->nValor;
  }

  public function setValor($nValor) {
      $this->nValor = $nValor;
  }

  public function getValorInteiro() {
      return $this->nValorInteiro;
  }

  public function setValorInteiro($nValorInteiro) {
      $this->nValorInteiro = $nValorInteiro;
  }

  public function getLancamento() {
      return $this->nLancamento;
  }

  public function setLancamento($nLancamento) {
      $this->nLancamento = $nLancamento;
  }

  public function getConvenio() {
      return $this->iConvenio;
  }

  public function setConvenio($iConvenio) {
      $this->iConvenio = $iConvenio;
  }

  public function getCGM() {
      return $this->iCGM;
  }

  public function setCGM($iCGM) {
      $this->iCGM = $iCGM;
  }

  public function getNome() {
      return $this->sNome;
  }

  public function setNome($sNome) {
      $this->sNome = $sNome;
  }

  public function getCnpj() {
      return $this->sCnpj;
  }

  public function setCnpj($sCnpj) {
      $this->sCnpj = $sCnpj;
  }

  public function getEndereco() {
      return $this->sEndereco;
  }

  public function setEndereco($sEndereco) {
      $this->sEndereco = $sEndereco;
  }

  public function getNumeroEndereco() {
      return $this->sNumeroEndereco;
  }

  public function setNumeroEndereco($sNumeroEndereco) {
      $this->sNumeroEndereco = $sNumeroEndereco;
  }

  public function getComplementoEndereco() {
      return $this->sComplementoEndereco;
  }

  public function setComplementoEndereco($sComplementoEndereco) {
      $this->sComplementoEndereco = $sComplementoEndereco;
  }

  public function getBairro() {
      return $this->sBairro;
  }

  public function setBairro($sBairro) {
      $this->sBairro = $sBairro;
  }

  public function getMunicipio() {
      return $this->sMunicipio;
  }

  public function setMunicipio($sMunicipio) {
      $this->sMunicipio = $sMunicipio;
  }

  public function getCep() {
      return $this->sCep;
  }

  public function setCep($sCep) {
      $this->sCep = $sCep;
  }

  public function getUf() {
      return $this->sUf;
  }

  public function setUf($sUf) {
      $this->sUf = $sUf;
  }
}

?>