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
class MovimentoArquivoTransmissao {

  /**
   * Código de Barra
   * @var String
   */
  private $sCodigoBarra;

  /* [Inicio plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte1] */
  /* [Fim plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte1] */

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

  /**
   * Código do Slip vinculado ao movimento
   * @var integer
   */
  private $iCodigoSlip;

  /**
   * Códdigo da ordem vinculada ao movimento
   * @var integer
   */
  private $iCodigoOrdem;

  /**
   * Código da finalidade de pagamento quando o recurso é FUNDEB
   * @var integer
   */
  private $iCodigoFinalidadePagamentoFundeb;

  /**
   * Número do empenho
   * @var String
   */
  private $sNumeroEmpenho;

  /**
   * Se o Movimento já foi processado em algum arquivo de retorno
   * @var boolean
   */
  private $lProcessado;

  /**
   * @type string
   */
  private $sLinhaDigitavel;

  /* [Inicio plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte2] */
  /* [Fim plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte2] */

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

  public function getCodigoSlip() {
    return $this->iCodigoSlip;
  }

  public function setCodigoSlip($iCodigoSlip) {
    $this->iCodigoSlip = $iCodigoSlip;
  }

  public function setCodigoOrdem($iCodigoOrdem) {
    $this->iCodigoOrdem = $iCodigoOrdem;
  }

  public function getCodigoOrdem() {
    return $this->iCodigoOrdem;
  }

  public function getNumeroEmpenho() {
    return $this->sNumeroEmpenho;
  }

  public function setNumeroEmpenho($sNumeroEmpenho) {
    $this->sNumeroEmpenho = $sNumeroEmpenho;
  }

  /**
   * Retorna o código sequencial da finalidade de pagamento do FUNDEB
   * @return integer
   */
  public function getFinalidadePagamentoFundeb() {
    return $this->iCodigoFinalidadePagamentoFundeb;
  }

  /**
   * Seta a finalidade de pagamento do recurso FUNDEB
   * @param integer
   */
  public function setFinalidadePagamentoFundeb($iCodigoFinalidadePagamentoFundeb) {
    $this->iCodigoFinalidadePagamentoFundeb = $iCodigoFinalidadePagamentoFundeb;
  }

  /**
   * @return boolean
   */
  public function getProcessado() {
    return $this->lProcessado;
  }

  /**
   * @param boolean $lProcessado
   * @return self
   */
  public function setProcessado($lProcessado) {

    $this->lProcessado = $lProcessado;
    return $this;
  }

  /**
   * @param string $sLinha
   */
  public function setLinhaDigitavel($sLinha) {
    $this->sLinhaDigitavel = $sLinha;
  }

  /**
   * @return string
   */
  public function getLinhaDigitavel() {
    return $this->sLinhaDigitavel;
  }

  /**
   * Constrói um objeto do tipo MovimentoArquivoTransmissao de acordo com codigo e ano
   *
   * @param    integer $iCodigoMovimento
   * @param    integer $iAno
   * @param    integer $iInstituicao
   *
   * @return MovimentoArquivoTransmissao
   * @throws BusinessException
   */
  public static function getInstance($iCodigoMovimento, $iAno, $iInstituicao) {

    $sSqlMovimentacao  = self::getSqlDadosMovimentacao(null, $iInstituicao, $iAno, $iCodigoMovimento);
    $rsMovimentacao    = db_query($sSqlMovimentacao);

    if (!$rsMovimentacao) {
      throw new BusinessException("Movimentação não encontrada");
    }
    $oStdDadosMovimentacao = db_utils::fieldsMemory($rsMovimentacao, 0);
    return self::montaObjetoLinha($oStdDadosMovimentacao);
  }

  /**
   * Método que constroi um objeto MovimentoArquivoTransmissao para que seja usado nas definições de layout de linha
   * @param  stdClass $oStdResultadoQuery
   * @return MovimentoArquivoTransmissao
   */
  public static function montaObjetoLinha($oStdResultadoQuery) {

    $oDadosLinha = new MovimentoArquivoTransmissao();
    $oDadosLinha->setCodigoBarra($oStdResultadoQuery->e74_codigodebarra);
    $oDadosLinha->setLinhaDigitavel($oStdResultadoQuery->e74_linhadigitavel);

    /* [Inicio plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte3] */
    /* [Fim plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte3] */

    $oDadosLinha->setValorNominal($oStdResultadoQuery->e74_valornominal);
    $oDadosLinha->setDataVencimento($oStdResultadoQuery->e74_datavencimento);
    $oDadosLinha->setValorJuros($oStdResultadoQuery->e74_valorjuros);
    $oDadosLinha->setValorDesconto(empty($oStdResultadoQuery->e74_valordesconto) ? '0' : $oStdResultadoQuery->e74_valordesconto);
    $oDadosLinha->setTipoFatura($oStdResultadoQuery->e74_tipofatura);
    $oDadosLinha->setCodigoBancoFornecedor($oStdResultadoQuery->banco_fornecedor);
    $oDadosLinha->setCodigoArquivoSistema($oStdResultadoQuery->e90_codgera);
    $oDadosLinha->setCodigoMovimento($oStdResultadoQuery->e81_codmov);
    $oDadosLinha->setDataGeracao($oStdResultadoQuery->e87_data);
    $oDadosLinha->setDataProcessamento($oStdResultadoQuery->e87_dataproc);

    $oDadosLinha->setCodigoBancoPagador($oStdResultadoQuery->c63_banco);
    $oDadosLinha->setCodigoAgenciaPagadora($oStdResultadoQuery->c63_agencia);
    $oDadosLinha->setDigitoVerificadorAgenciaPagadora($oStdResultadoQuery->c63_dvagencia);
    $oDadosLinha->setContaPagadora($oStdResultadoQuery->c63_conta);
    $oDadosLinha->setDigitoVerificadorContaPagadora($oStdResultadoQuery->c63_dvconta);

    $oDadosLinha->setCodigoBancoFavorecido($oStdResultadoQuery->pc63_banco);
    $oDadosLinha->setCodigoAgenciaFavorecida($oStdResultadoQuery->pc63_agencia);
    $oDadosLinha->setDigitoVerificadorAgenciaFavorecida($oStdResultadoQuery->pc63_agencia_dig);
    $oDadosLinha->setContaFavorecida($oStdResultadoQuery->pc63_conta);
    $oDadosLinha->setDigitoVerificadorContaFavorecida($oStdResultadoQuery->pc63_conta_dig);

    $oDadosLinha->setCodigoOperacaoFavorecido($oStdResultadoQuery->pc63_codigooperacao);
    $oDadosLinha->setCodigoOperacaoPagador($oStdResultadoQuery->c63_codigooperacao);
    $oDadosLinha->setTipoContaFavorecido($oStdResultadoQuery->pc63_tipoconta);

    $oDadosLinha->setValor($oStdResultadoQuery->valor);
    $oDadosLinha->setValorInteiro($oStdResultadoQuery->valorori);
    $oDadosLinha->setLancamento($oStdResultadoQuery->lanc);

    $oDadosLinha->setConvenio($oStdResultadoQuery->convenio);
    $oDadosLinha->setCGM($oStdResultadoQuery->numcgm);
    $oDadosLinha->setNome($oStdResultadoQuery->z01_nome);
    $oDadosLinha->setCnpj($oStdResultadoQuery->z01_cgccpf);

    $oDadosLinha->setEndereco($oStdResultadoQuery->z01_ender);
    $oDadosLinha->setNumeroEndereco($oStdResultadoQuery->z01_numero);
    $oDadosLinha->setComplementoEndereco($oStdResultadoQuery->z01_compl);
    $oDadosLinha->setBairro($oStdResultadoQuery->z01_bairro);
    $oDadosLinha->setMunicipio($oStdResultadoQuery->z01_munic);
    $oDadosLinha->setCep($oStdResultadoQuery->z01_cep);
    $oDadosLinha->setUf($oStdResultadoQuery->z01_uf);

    $oDadosLinha->setCodigoSlip($oStdResultadoQuery->slip);
    $oDadosLinha->setCodigoOrdem($oStdResultadoQuery->ordem);
    $oDadosLinha->setNumeroEmpenho($oStdResultadoQuery->empenho);
    $oDadosLinha->setFinalidadePagamentoFundeb($oStdResultadoQuery->finalidadepagamento);

    $oDadosLinha->setProcessado(($oStdResultadoQuery->processado == 't'));

    return $oDadosLinha;
  }


  /**
   * Méotodo que constrói string para buscar dados de movimento associado a um arquivo
   *
   * @param string  $sCodigoGeracao
   * @param integer $iInstituicao
   * @param integer $iAno
   * @param integer $iCodigoMovimento
   *
   * @return string
   */
  public static function getSqlDadosMovimentacao($sCodigoGeracao, $iInstituicao, $iAno, $iCodigoMovimento = null) {

    /* [Inicio plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte4] */
    /* [Fim plugin GeracaoArquivoOBN  - processamento arquivo OBN - parte4] */

    $sWhere         = "1=1";
    $sInner         = "left";
    $sWhereArquivo  = " e80_instit      = {$iInstituicao}   ";
    $sWhereArquivo .= " and e90_codgera = {$sCodigoGeracao} ";
    $sWhereArquivo .= " and e90_cancelado = 'false'";

    $sWhereMovimento = " e81_codmov = {$iCodigoMovimento} ";

    if (!empty($sCodigoGeracao)) {
      $sWhere .= "and {$sWhereArquivo}";
      $sInner  = "inner" ;
    }

    if (!empty($iCodigoMovimento)) {
      $sWhere .= "and {$sWhereMovimento}";
    }


    $sqlOrdem = "select distinct
                        e60_codemp||'/'||e60_anousu as empenho,
                        null::integer as slip,
                        e82_codord as ordem,
                        pc63_banco as banco_fornecedor,
	                      e90_codgera,
	                      e81_codmov,
	                      e87_data,
	                      e87_dataproc,
	                      c63_banco,
	                      c63_agencia,
	                      coalesce(c63_dvagencia,'0') as c63_dvagencia,
	                      c63_conta,
	                      coalesce(c63_dvconta,'0') as c63_dvconta,
	                      pc63_agencia::varchar,
                        coalesce(pc63_agencia_dig,'0') as pc63_agencia_dig,
	                      pc63_conta::varchar,
                        coalesce(pc63_conta_dig,'0') as pc63_conta_dig,
                        pc63_codigooperacao::varchar,
                        conplanoconta.c63_codigooperacao::varchar,
                        pc63_tipoconta,
	                      translate(to_char(round(e81_valor- coalesce(fc_valorretencaomov(e81_codmov,false),0),2),'99999999999.99'),'.','') as valor,
	                      e81_valor- coalesce(fc_valorretencaomov(e81_codmov,false),0) as valorori,
	                      case when  pc63_banco = c63_banco then '01' else '03' end as  lanc,
	                      coalesce(pc63_banco,'000') as pc63_banco,
	                      e83_convenio as convenio,
	                      z01_numcgm as numcgm,
	                      substr(z01_nome,1,40) as z01_nome,
	                      case when trim(pc63_cnpjcpf) = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then length(trim(z01_cgccpf)) else length(trim(pc63_cnpjcpf)) end as tam,
	                      case when trim(pc63_cnpjcpf) = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then z01_cgccpf else pc63_cnpjcpf end as z01_cgccpf,
	                      e88_codmov as cancelado,
	                      z01_ender,
	                      z01_numero,
	                      z01_compl,
	                      z01_bairro,
	                      z01_munic,
	                      z01_cep,
	                      z01_uf,
	                      fc_validaretencoesmesanterior(e81_codmov,null) as validaretencao,
	                      e83_codigocompromisso,
                        empagemovdetalhetransmissao.*,
                        (select e152_finalidadepagamentofundeb from empempenhofinalidadepagamentofundeb where e152_numemp = empempenho.e60_numemp) as finalidadepagamento,
                        exists(select * 
                                 from empagedadosretmov 
                                      inner join empagedadosret on e76_codret = e75_codret 
                                                               and e75_ativo is true
                                where e76_codmov = e81_codmov) as processado
                   from empagemov
            	          {$sInner} join empageconfgera               on e90_codmov  = e81_codmov
            	          {$sInner} join empagegera                   on e90_codgera = e87_codgera
                        {$sInner} join empage                       on  empage.e80_codage = empagemov.e81_codage
            	          {$sInner} join empempenho                   on e60_numemp = e81_numemp
            	          {$sInner} join empagepag                    on e81_codmov = e85_codmov
            	          {$sInner} join empagetipo                   on e85_codtipo = e83_codtipo
              	        {$sInner} join empord                       on empord.e82_codmov         = empagemov.e81_codmov
            	          left      join empageslip                  on e81_codmov = e89_codmov
            	          left      join conplanoreduz               on e83_conta = c61_reduz and c61_anousu = {$iAno}
              	        left      join conplanoconta               on c63_codcon = c61_codcon and c63_anousu = c61_anousu
              	        left      join slip                        on slip.k17_codigo = e89_codigo
              	        left      join slipnum                     on slipnum.k17_codigo = slip.k17_codigo
              	        left      join empageconfcanc              on e88_codmov = e90_codmov
              	        left      join empagemovconta              on e90_codmov = e98_codmov
              	        left      join pcfornecon                  on pc63_contabanco = e98_contabanco
              	        left      join cgm                         on z01_numcgm = pc63_numcgm
              	        left      join empagemovtipotransmissao    on empagemovtipotransmissao.e25_empagemov = empagemov.e81_codmov
              	        left      join empagemovdetalhetransmissao on  empagemovdetalhetransmissao.e74_empagemov = empagemov.e81_codmov

      	          where
      	              {$sWhere} ";
      	              //"and empagemovtipotransmissao.e25_empagetipotransmissao = 2";

    $sqlSlip = "select
                      distinct
                      null::varchar as empenho,
                      slip.k17_codigo as slip,
                      null::integer as ordem,
                      pc63_banco as banco_fornecedor,
	                    e90_codgera,
	                    e81_codmov,
	                    e87_data,
	                    e87_dataproc,
	                    conplanoconta.c63_banco,
	                    conplanoconta.c63_agencia,
	                    coalesce(conplanoconta.c63_dvagencia,'0') as c63_dvagencia,
	                    conplanoconta.c63_conta,
	                    coalesce(conplanoconta.c63_dvconta,'0') as c63_dvconta,
                      (case
                          when pc63_agencia is null or k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
              	              then contadebito.c63_agencia
                          else pc63_agencia
                      end )::varchar as pc63_agencia,

                        coalesce((case when pc63_agencia_dig is null or k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
              	                 then contadebito.c63_dvagencia
                         else pc63_agencia_dig end ),'0')::varchar as pc63_agencia_dig,
                      (case when pc63_conta is null or slipnum.k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
              	                   then contadebito.c63_conta
                            else pc63_conta
                       end)::varchar as pc63_conta,
                        coalesce((case when pc63_conta_dig is null or k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
              	                         then contadebito.c63_dvconta
                         else pc63_conta_dig end ),'0')::varchar as pc63_conta_dig,
                      (case
                         when pc63_codigooperacao is null or k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
              	                             then contadebito.c63_codigooperacao
                         else pc63_codigooperacao
                       end )::varchar as pc63_codigooperacao,
                       conplanoconta.c63_codigooperacao::varchar,
                       (case
                         when pc63_tipoconta is null or k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
                           then contadebito.c63_tipoconta
                         else pc63_tipoconta
                       end ) as pc63_tipoconta,
	                    translate(to_char(round(e81_valor - coalesce(fc_valorretencaomov(e81_codmov,false),0),2),'99999999999.99'),'.','') as valor,
	                    e81_valor - coalesce(fc_valorretencaomov(e81_codmov,false),0) as valorori,
	                    case
                        when  ((case when pc63_banco is not null then pc63_banco else contadebito.c63_banco end ) = conplanoconta.c63_banco
                                or descrconta.c63_banco = conplanoconta.c63_banco )
                           then '01'
                        else '03'
                      end as  lanc,
                      ( case when pc63_banco is null and k17_numcgm = (select numcgm from db_config where codigo = {$iInstituicao})
                        then contadebito.c63_banco
                             else pc63_banco
                        end ) as pc63_banco,

	                    e83_convenio as convenio,
	                    case when cgm.z01_numcgm is null then cgmslip.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,
	                    substr(case when cgm.z01_nome is null then cgmslip.z01_nome else cgm.z01_nome end,1,40) as z01_nome,
	                    case when  trim(pc63_cnpjcpf) = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null
	                         then length(trim( case when cgm.z01_cgccpf is null then cgmslip.z01_cgccpf else cgm.z01_cgccpf end))
	                         else length(trim(pc63_cnpjcpf)) end as tam,
	                    case when  trim(pc63_cnpjcpf) = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is
	                         null then
	                           ( case when cgm.z01_cgccpf is null then cgmslip.z01_cgccpf else cgm.z01_cgccpf end)
	                         else pc63_cnpjcpf end as z01_cgccpf,
	                    e88_codmov as cancelado,
	                    case when cgm.z01_ender is null then cgmslip.z01_ender else cgm.z01_ender end as z01_ender,
	                    case when cgm.z01_numero is null then cgmslip.z01_numero else cgm.z01_numero end as z01_numero,
	                    case when cgm.z01_compl is null then cgmslip.z01_compl else cgm.z01_compl end as z01_compl,
	                    case when cgm.z01_bairro is null then cgmslip.z01_bairro else cgm.z01_bairro end as z01_bairro,
	                    case when cgm.z01_munic is null then cgmslip.z01_munic else cgm.z01_munic end as z01_munic,
	                    case when cgm.z01_cep is null then cgmslip.z01_cep else cgm.z01_cep end as z01_cep,
	                    case when cgm.z01_uf is null then cgmslip.z01_uf else cgm.z01_uf end as z01_uf,
	                    false as validaretencao,
	                    e83_codigocompromisso,
                      empagemovdetalhetransmissao.*,
                      (select e153_finalidadepagamentofundeb from slipfinalidadepagamentofundeb where e153_slip = slip.k17_codigo) as finalidadepagamento,
                      exists(select * 
                                 from empagedadosretmov 
                                      inner join empagedadosret on e76_codret = e75_codret 
                                                               and e75_ativo is true
                                where e76_codmov = e81_codmov) as processado
                  from empagemov
	                    {$sInner} join empageconfgera    on e90_codmov         = e81_codmov
	                    {$sInner} join empagegera        on e90_codgera        = e87_codgera
                      {$sInner} join empage            on  empage.e80_codage = empagemov.e81_codage
	                    {$sInner} join empagepag         on e81_codmov         = e85_codmov
	                    {$sInner} join empagetipo        on e85_codtipo        = e83_codtipo
	                    {$sInner} join empageslip        on e81_codmov         = e89_codmov
	                    {$sInner} join conplanoreduz     on e83_conta          = c61_reduz and c61_anousu = {$iAno}
                      {$sInner} join conplanoconta     on c63_codcon         = c61_codcon and c63_anousu = c61_anousu
	                    {$sInner} join slip              on slip.k17_codigo    = e89_codigo
	                    {$sInner} join slipnum           on slipnum.k17_codigo = slip.k17_codigo

	                    left join conplanoreduz reduzdebito on reduzdebito.c61_reduz   = k17_debito
                      left join conplano      planodebito on planodebito.c60_codcon  = reduzdebito.c61_codcon
                                                          and planodebito.c60_anousu = {$iAno}
                      left join conplanoconta contadebito on contadebito.c63_codcon  = reduzdebito.c61_codcon
                                                          and contadebito.c63_anousu = {$iAno}
                      left join saltes                    on saltes.k13_reduz        = reduzdebito.c61_reduz
                      left join empageconfcanc on e88_codmov   = e90_codmov
                      left join empagemovconta on e90_codmov   = e98_codmov
                      left join pcfornecon on pc63_contabanco  = e98_contabanco
                      left join cgm cgmslip on cgmslip.z01_numcgm = slipnum.k17_numcgm
                      left join cgm on cgm.z01_numcgm              = cgmslip.z01_numcgm
                      left join conplanoreduz cre on cre.c61_reduz   = k17_debito and cre.c61_anousu = {$iAno}
  	                  left join conplano concre on concre.c60_codcon = cre.c61_codcon and concre.c60_anousu = cre.c61_anousu
                      left join conplanoconta descrconta on concre.c60_codcon = descrconta.c63_codcon and concre.c60_anousu = descrconta.c63_anousu
                      left join empagemovtipotransmissao on empagemovtipotransmissao.e25_empagemov = empagemov.e81_codmov
                      left join empagemovdetalhetransmissao on  empagemovdetalhetransmissao.e74_empagemov = empagemov.e81_codmov
  	                  where
                        {$sWhere}".
  	                  " --order by c63_conta,lanc,e81_codmov";

    	  $sqlMov = $sqlOrdem . " union " . $sqlSlip;

    	  return $sqlMov;
      }
}