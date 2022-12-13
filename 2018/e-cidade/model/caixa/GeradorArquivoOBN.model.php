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

require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("model/caixa/ConfiguracaoArquivoObn.model.php"));
require_once(modification("std/DBDate.php"));


/**
 * Classe que gera arquivo no Layout OBN
 * @author Matheus Felini matheus.felini@dbseller.com.br
 * @author Bruno Silva bruno.silva@dbseller.com.br
 * @package caixa
 */
class GeradorArquivoOBN {

  /**
   * Destino onde arquivo será gerado
   * @var String
   */
  private $sLocalizacaoArquivo;

  /**
   * Data
   * @var "yyyy-mm-dd"
   */
  private $dtGeracaoArquivo;

  /**
   * Hora
   * @var time
   */
  private $dtHoraGeracaoArquivo;

  /**
   * Data da autorização do pagamento
   * @var date
   */
  private $dtAutorizacaoPagamento;

  /**
   * Código sequencial do arquivo
   * @var integer
   */
  private $iSequencialArquivo;

  /**
   * Código da remessa do arquivo
   * @var integer
   */
  private $iCodigoRemessa;

  /**
   * Descrição do arquivo
   * @var string
   */
  private $sDescricaoArquivo;

  /**
   * Instituição
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Contador de registros no arquivo
   * @var Instituicao
   */
  private $iContadorRegistros;

  /**
   * Sequencial do registro dentro do arquivo
   * @var integer
   */
  private $iSequencialRegistro = 1;


  /**
   * Ano referente a geração do arquivo
   * @var integer
   */
  private $iAno;

  /**
   * Armazena os o somatório dos valores das ordens
   * @var float
   */
  private $nValorTotalDasMovimentacoes = 0;


  /**
   * Arquivo de transmissão
   * @var ArquivoTransmissao
   */
  private $oArquivoTransmissao;


  public function __construct() {
    $oArquivoTransmissao = new ArquivoTransmissao();
  }

  /**
   * Retorna o codigo da remessa
   * @return integer $iCodigoRemessa
   */
  public function getCodigoRemessa() {
  	return $this->iCodigoRemessa;
  }

  /**
   * seta o codigo da remessa
   * @param integer $iCodigoRemessa
   */
  public function setCodigoRemessa($iCodigoRemessa) {
  	$this->iCodigoRemessa = $iCodigoRemessa;
  }

  /**
   * Retorna o destino do arquivo OBN
   * @return String $sLocalizacaoArquivo
   */
  public function getLocalizacao() {
    return $this->sLocalizacaoArquivo;
  }

  /**
   * Seta o destino do arquivo OBN
   * @param String $sLocalizacaoArquivo
   */
  public function setLocalizacao($sLocalizacaoArquivo) {
    $this->sLocalizacaoArquivo = $sLocalizacaoArquivo;
  }

  /**
   * Seta a descrição do arquivo
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricaoArquivo = $sDescricao;
  }

  /**
   * Seta a data de geração do arquivo
   * @param date $dtGeracao
   */
  public function setDataGeracao($dtGeracao) {
    $this->dtGeracaoArquivo = $dtGeracao;
  }

  /**
   * Seta a hora da geração
   * @param time $dtHoraGeracaoArquivo
   */
  public function setHoraGeracao($dtHoraGeracaoArquivo) {
    $this->dtHoraGeracaoArquivo = $dtHoraGeracaoArquivo;
  }

  /**
   * Seta a data da autorização do pagamento
   * @param date $dtAutorizacaoPagamento
   */
  public function setDataAutorizacaoPagamento($dtAutorizacaoPagamento) {
    $this->dtAutorizacaoPagamento = $dtAutorizacaoPagamento;
  }

  /**
   * Seta a instituição vinculada ao arquivo
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna a instituição vinculada ao arquivo
   * @return Instituicao $oInstituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna o Ano do processamento do arquivo
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o Ano do processamento do arquivo
   * @param  integer $iAno
   * @return integer
   */
  public function setAno($iAno) {
    return $this->iAno = $iAno;
  }

  /**
   *  Vincula movimento com um arquivo de remessa (empagegera)
   *  e imprime arquivo com movimentos, de acordo com layout
   *
   * @param array $aMovimentosAgenda
   */
  public function construirRemessa(array $aMovimentosAgenda){

    $this->salvarGeracaoArquivo();
    $this->vincularMovimentosNaGeracao($aMovimentosAgenda);
    $this->geraArquivoEnvio($aMovimentosAgenda);
  	$this->vincularRemessaNumeracao();
  	$this->setCodigoSequencialArquivo();
  }


  /**
   * Altera os dados do arquivo, como a data e hora de geração
   * reimprimindo o mesmo
   * @throws BusinessException
   */
  public function regerarArquivo() {

  	if (empty($this->oInstituicao)) {
  		throw new BusinessException("ERRO [ 0 ] - Gerando arquivo - Não foi encontrada instituição.");
  	}

  	if (empty($this->iAno)) {
  		throw new BusinessException("ERRO [ 1 ] - Gerando arquivo - Não foi encontrado o ano da seção.");
  	}

  	if (empty($this->iCodigoRemessa)) {
  		throw new BusinessException("ERRO [ 2 ] - Gerando arquivo - Não foi encontrado código da remessa.");
  	}
		$this->iSequencialArquivo = $this->buscaCodigoArquivoRemessa();
  	$this->salvarGeracaoArquivo();
  	$this->buscaCodigoArquivoRemessa();
  	$this->geraArquivoEnvio();
  }

  /**
   * Gera o Arquivo com Layout OBN, a partir dos movimentos
   */
  private function geraArquivoEnvio() {

    $dtNomeArquivo              = str_replace("-", "_", $this->dtGeracaoArquivo);
    $this->sLocalizacaoArquivo  = "tmp/arquivo_{$this->iCodigoRemessa}_{$dtNomeArquivo}.txt";

    $iInstituicao               = $this->oInstituicao->getSequencial();
    $iAno                       = $this->iAno;
    $iRemessa                   = $this->iCodigoRemessa;
    $sSqlGeracaoArquivo         = MovimentoArquivoTransmissao::getSqlDadosMovimentacao($iRemessa, $iInstituicao, $iAno);
    $rsBuscaDadosGeracaoArquivo = db_query($sSqlGeracaoArquivo);
    $iCodigoConvenio            = db_utils::fieldsMemory($rsBuscaDadosGeracaoArquivo, 0)->convenio;
    $iTotalMovimentos           = pg_num_rows($rsBuscaDadosGeracaoArquivo);
    $iContaPagadora             = 0;

    $aCodigoSequenciais = array();

    $oLayoutTXT = new db_layouttxt(211, $this->sLocalizacaoArquivo);
    $oHeader    = $this->constroiDadosHeader($iCodigoConvenio);

    $oLayoutTXT->setByLineOfDBUtils($oHeader, 1);
    $this->iContadorRegistros++;
    $aCodigoSequenciais[] = $this->iContadorRegistros;

    $oStdDadosBancoAnterior                = new stdClass();
    $oStdDadosBancoAnterior->banco         = 0;
    $oStdDadosBancoAnterior->agencia       = 0;
    $oStdDadosBancoAnterior->digitoAgencia = 0;
    $oStdDadosBancoAnterior->conta         = 0;
    $oStdDadosBancoAnterior->digitoConta   = 0;

    /**
     * @TODO fazer uso do model ArquivoTransmissao, usando getMovimentos para buscar objetos
     * do tipo MovimentoArquivoTransmissao, para não precisar refazer o SQL "MovimentoArquivoTransmissao::getSqlDadosMovimentacao"
     */
    for ($iDadoMovimento = 0; $iDadoMovimento < $iTotalMovimentos; $iDadoMovimento++) {

      $this->iSequencialRegistro++;
      $oStdMovimento   = db_utils::fieldsMemory($rsBuscaDadosGeracaoArquivo, $iDadoMovimento);
      $oDadosMovimento = MovimentoArquivoTransmissao::montaObjetoLinha($oStdMovimento);
      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */
      $oLinha = $this->constroiLinhaTipoDois($oDadosMovimento);
      $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 2);
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */

      $this->iContadorRegistros++;
      $aCodigoSequenciais[] = $this->iContadorRegistros;

      $this->nValorTotalDasMovimentacoes += $oDadosMovimento->getValor();

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte3] */
      $iTipoLinha = ConfiguracaoArquivoObn::verificaTipoLinha($oDadosMovimento->getCodigoBarra());
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte3] */
      switch($iTipoLinha) {

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte4] */
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte4] */
      case ConfiguracaoArquivoObn::LAYOUT4:

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte5] */
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte5] */
          $this->iSequencialRegistro++;
          $oLinha = $this->constroiLinhaTipoQuatro($oDadosMovimento);
          $oLayoutTXT->setByLineOfDBUtils($oLinha, 3, 4);

          $this->iContadorRegistros++;
          $aCodigoSequenciais[] = $this->iContadorRegistros;
          break;

      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte6] */
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte6] */

      }

    }

    $this->iContadorRegistros = array_sum($aCodigoSequenciais);
    $oLinha = $this->constroiLinhaTrailer();
    $oLayoutTXT->setByLineOfDBUtils($oLinha, 5);
  }

  /**
   * Método irá vincular o arquivo gerado na empagegera com a numeração OBN
   */

  private function vincularRemessaNumeracao() {

    /*
     * criamos vinculo do codgera com a numeração obn
     */
    $oDaoEmpAgeGeraObn = new cl_empagegeraobn();
    $oDaoEmpAgeGeraObn->e138_numeracaoobn = $this->iSequencialArquivo;
    $oDaoEmpAgeGeraObn->e138_empagegera   = $this->iCodigoRemessa;
    $oDaoEmpAgeGeraObn->incluir(null);
    if ($oDaoEmpAgeGeraObn->erro_status == 0 ) {
      throw new DBException("ERRO - [ 0 ] - criando vinculo obn - " .	$oDaoEmpAgeGeraObn->erro_msg);
    }
  }

  /**
   * Constrói os dados que serão impressos na linha do tipo 2.
   * @param MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoDois(MovimentoArquivoTransmissao $oDadosLinha) {

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    $iTipoPagamento           = ConfiguracaoArquivoObn::verificaTipoPagamento($iTipoOperacao);

    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte7] */
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, " ", STR_PAD_RIGHT);
      /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte7] */
    }

    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora() . $oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaConvenio      = $oDadosLinha->getContaFavorecida() . $oDadosLinha->getDigitoVerificadorContaFavorecida();

    $sAgenciaDigitoPagadora = $oDadosLinha->getCodigoAgenciaPagadora() . $oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $sContaDigitoPagadora   = $oDadosLinha->getContaPagadora() . $oDadosLinha->getDigitoVerificadorContaPagadora();

    $sCodigoFinalidadePagamento = $oDadosLinha->getFinalidadePagamentoFundeb();
    if (!empty($sCodigoFinalidadePagamento)) {

      $oFinalidadePagamento       = new FinalidadePagamentoFundeb($oDadosLinha->getFinalidadePagamentoFundeb());
      $sCodigoFinalidadePagamento = $oFinalidadePagamento->getCodigo();
    }

    /**
     * Verifica se existe configuração de envio para o movimento.
     */
    $daoDetalheTransmissao = new cl_empagemovdetalhetransmissao();
    $buscaDetalhes = $daoDetalheTransmissao->sql_query_file(null, 'e74_finalidade', null, 'e74_empagemov = '.$oDadosLinha->getCodigoMovimento());
    $resBuscaDetalhes = db_query($buscaDetalhes);
    if (!$resBuscaDetalhes) {
      throw new DBException("Ocorreu um erro ao consultar o código da finalidade de pagamento.");
    }
    if (pg_num_rows($resBuscaDetalhes) == 1) {

      $finalidade = db_utils::fieldsMemory($resBuscaDetalhes, 0)->e74_finalidade;
      if (!empty($finalidade)) {
        $sCodigoFinalidadePagamento = $finalidade;
      }
    }

    $sDigitoVerificadorAgenciaFavorecido = $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte21] */
    $sCodigoAgenciaFavorecido            = $oDadosLinha->getCodigoAgenciaFavorecida();
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte21] */
    $sCodigoBancoFavorecido              = $oDadosLinha->getCodigoBancoFavorecido();
    $sContaDigitoFavorecido              = str_pad($oDadosLinha->getContaFavorecida() . $oDadosLinha->getDigitoVerificadorContaFavorecida(), 10, "0", STR_PAD_LEFT);

    if ($oDadosLinha->getCodigoBarra() != "") {

      $sDigitoVerificadorAgenciaFavorecido = str_pad("0", 01, "0", STR_PAD_LEFT);
      $sCodigoAgenciaFavorecido            = str_pad("0", 04, "0", STR_PAD_LEFT);
      $sCodigoBancoFavorecido              = str_pad("0", 03, "0", STR_PAD_LEFT);
      $sContaDigitoFavorecido              = str_pad("0", 10, "0", STR_PAD_LEFT);

    }

    $oStdLinhaTipoDois = new stdClass();
    $oStdLinhaTipoDois->numero_sequencial_movimento = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte8] */
    $oStdLinhaTipoDois->codigo_retorno = str_repeat(" ", 2);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte8] */

    $oStdLinhaTipoDois->campo_branco_3 = str_repeat(" ", 4);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte9] */
    $oStdLinhaTipoDois->finalidade_pagamento = str_pad($sCodigoFinalidadePagamento, 3, "0", STR_PAD_LEFT);
    $oStdLinhaTipoDois->prefixo_conta_convenio = str_pad($sContaDigitoPagadora, 10, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte9] */

    $oStdLinhaTipoDois->prefixo_agencia_convenio    = $sAgenciaDigitoPagadora;
    $oStdLinhaTipoDois->cpf_cnpj_favorecido  	      = $sCPFCNPJ;
    $oStdLinhaTipoDois->tipo_favorecido  	          = $iTipoFavorecido;

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte10] */
    $oStdLinhaTipoDois->campo_um = "1";
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte10] */

    $oStdLinhaTipoDois->observacao  	            = str_repeat(" ", 40);
    $oStdLinhaTipoDois->estado_favorecido  	      = $oDadosLinha->getUf();
    $oStdLinhaTipoDois->cep_favorecido  	        = $oDadosLinha->getCep();
    $oStdLinhaTipoDois->campo_branco_2  	        = str_repeat(" ", 17);
    $oStdLinhaTipoDois->municipio_favorecido    	= $oDadosLinha->getMunicipio();
    $oStdLinhaTipoDois->endereco_favorecido  	    = $oDadosLinha->getEndereco();
    $oStdLinhaTipoDois->nome_favorecido  	        = $oDadosLinha->getNome();
    $oStdLinhaTipoDois->codigo_conta_favorecido  	= $sContaDigitoFavorecido;
    $oStdLinhaTipoDois->digito_agencia_favorecido = $sDigitoVerificadorAgenciaFavorecido;
    $oStdLinhaTipoDois->codigo_agencia_favorecido = $sCodigoAgenciaFavorecido;
    $oStdLinhaTipoDois->codigo_banco_favorecido  	= $sCodigoBancoFavorecido;
    $oStdLinhaTipoDois->valor_liquido  	          = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoDois->campo_zero_1  	          = str_repeat("0", 9);
    $oStdLinhaTipoDois->tipo_pagamento           	= "0";
    $oStdLinhaTipoDois->codigo_operacao           = $iTipoOperacao;
    $oStdLinhaTipoDois->campo_branco_1           	= str_repeat(" ", 4);
    $oStdLinhaTipoDois->data_geracao  	          = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoDois->codigo_ob  	              = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte11] */
    $oStdLinhaTipoDois->codigo_movimentacao = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - part11] */

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte12] */
    $oStdLinhaTipoDois->codigo_instituicao = ConfiguracaoArquivoObn::CODIGO_PADRAO_INSTITUICAO;
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte12] */

    $oStdLinhaTipoDois->codigo_agencia_dv  	= $sAgenciaInstituicao;
    $oStdLinhaTipoDois->identificador_campo = 2;
    return $oStdLinhaTipoDois;
  }

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte13] */
  /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte13] */

  /**
   * Configura os registros dos registros de movimentação de pagamento pessoal, pagamento no caixa,
   * crédito em conta BB ou crédito em outros bancos
   * @param  MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoTres(MovimentoArquivoTransmissao $oDadosLinha) {

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $iTipoFavorecido          = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao            = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    $iTipoPagamento           = ConfiguracaoArquivoObn::verificaTipoPagamento($iTipoOperacao);

    $sCPFCNPJ = $oDadosLinha->getCnpj();
    if ($iTipoFavorecido == ConfiguracaoArquivoObn::TIPO_CPF) {
      $sCPFCNPJ = str_pad($sCPFCNPJ, 14, "0", STR_PAD_RIGHT);
    }

    $oStdLinhaTipoTres = new stdClass();
    $oStdLinhaTipoTres->identificador_linha = 3;

    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora() . $oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoTres->codigo_agencia_bancaria_instituicao      = $sAgenciaInstituicao;
    $oStdLinhaTipoTres->codigo_instituicao                       = ConfiguracaoArquivoObn::CODIGO_PADRAO_INSTITUICAO;
    $oStdLinhaTipoTres->codigo_movimentacao                      = $oDadosLinha->getCodigoMovimento();
    $oStdLinhaTipoTres->codigo_ob                                = $oDadosLinha->getCodigoMovimento();
    $oStdLinhaTipoTres->data_movimentacao                        = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoTres->campo_branco_um                          = str_repeat(" ", 4);
    $oStdLinhaTipoTres->codigo_operacao                          = $iTipoOperacao;
    $oStdLinhaTipoTres->tipo_pagamento                           = 4;
    $oStdLinhaTipoTres->campo_zero_um                            = str_repeat("0", 6);
    $oStdLinhaTipoTres->campo_branco_dois                        = str_repeat(" ", 3);
    $oStdLinhaTipoTres->valor_liquido_movimentacao               = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoTres->codigo_banco_favorecido                  = $oDadosLinha->getCodigoBancoFavorecido();
    $oStdLinhaTipoTres->codigo_agencia_banco_favorecido          = $oDadosLinha->getCodigoAgenciaFavorecida();
    $oStdLinhaTipoTres->digito_verificador_agencia_favorecido    = $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    $oStdLinhaTipoTres->codigo_contacorrente_bancaria_favorecido = $oDadosLinha->getContaFavorecida();
    $oStdLinhaTipoTres->nome_favorecido                          = $oDadosLinha->getNome();
    $oStdLinhaTipoTres->endereco_favorecido                      = $oDadosLinha->getEndereco();
    $oStdLinhaTipoTres->municipio_favorecido                     = $oDadosLinha->getMunicipio();
    $oStdLinhaTipoTres->campo_branco_tres                        = str_repeat(" ", 17);
    $oStdLinhaTipoTres->cep_favorecido                           = $oDadosLinha->getCep();
    $oStdLinhaTipoTres->uf_favorecido                            = $oDadosLinha->getUf();
    $oStdLinhaTipoTres->observacao                               = str_repeat(" ", 40);
    $oStdLinhaTipoTres->campo_zero_tres                          = 0;
    $oStdLinhaTipoTres->tipo_favorecido                          = $iTipoFavorecido;
    $oStdLinhaTipoTres->codigo_favorecido                        = $sCPFCNPJ;
    $sPrefixoAgencia = $oDadosLinha->getCodigoAgenciaFavorecida() . $oDadosLinha->getDigitoVerificadorAgenciaFavorecida();
    $oStdLinhaTipoTres->prefixo_agencia                          = $sPrefixoAgencia;
    $sContaConvenio = $oDadosLinha->getContaFavorecida() . $oDadosLinha->getDigitoVerificadorContaFavorecida();
    $oStdLinhaTipoTres->numero_conta_convenio                    = str_pad($sContaConvenio, 10, "0", STR_PAD_LEFT);

    $oStdLinhaTipoTres->campo_branco_quatro                      = str_repeat(" ", 7);
    $oStdLinhaTipoTres->codigo_retorno_operacao                  = str_repeat(" ", 2);
    $oStdLinhaTipoTres->numero_sequencial_movimento              = str_pad($this->iSequencialArquivo, 7, "0", STR_PAD_LEFT);
    return $oStdLinhaTipoTres;
  }

  /**
   * Configura os registros do tipo pagamento com codigo de barras
   * @param  MovimentoArquivoTransmissao $oDadosLinha
   * @return stdClass
   */
  private function constroiLinhaTipoQuatro(MovimentoArquivoTransmissao $oDadosLinha) {

    $oStdLinhaTipoQuatro = new stdClass();
    $iTipoFavorecido     = ConfiguracaoArquivoObn::verificaTipoFavorecido(strlen($oDadosLinha->getCnpj()));
    $iTipoOperacao       = ConfiguracaoArquivoObn::verificarTipoOperacao($oDadosLinha);
    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    list($iAnoCodigoBarra, $iMesCodigoBarra, $iDiaCodigoBarra) = explode("-", $oDadosLinha->getDataVencimento());

    $oStdLinhaTipoQuatro->identificador_linha = 4;
    $sAgenciaInstituicao = $oDadosLinha->getCodigoAgenciaPagadora() . $oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoQuatro->codigo_agencia_banco_instituicao = $sAgenciaInstituicao;

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte14] */
    $oStdLinhaTipoQuatro->codigo_instituicao = ConfiguracaoArquivoObn::CODIGO_PADRAO_INSTITUICAO;
    $oStdLinhaTipoQuatro->codigo_movimento   = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte14] */

    $oStdLinhaTipoQuatro->codigo_ob            = str_pad($oDadosLinha->getCodigoMovimento(), 11, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->data_geracao_arquivo = "{$iDia}{$iMes}{$iAno}";
    $oStdLinhaTipoQuatro->campo_branco_um      = str_repeat(" ", 4);
    $oStdLinhaTipoQuatro->codigo_operacao      = $iTipoOperacao;
    $oStdLinhaTipoQuatro->campo_branco_dois    = str_repeat(" ", 1);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte15] */
    $oStdLinhaTipoQuatro->campo_zero_um = str_repeat("0", 6);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte15] */

    $oStdLinhaTipoQuatro->campo_branco_tres   = str_repeat(" ", 3);
    $oStdLinhaTipoQuatro->valor_liquido       = str_pad(str_replace(".", "", $oDadosLinha->getValor()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->campo_branco_quatro = str_repeat(" ", 15);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte16] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte16] */

    $oStdLinhaTipoQuatro->tipo_fatura                  = $oDadosLinha->getTipoFatura();
    $oStdLinhaTipoQuatro->codigo_barra                 = $oDadosLinha->getCodigoBarra();
    $oStdLinhaTipoQuatro->cb_data_vencimento           = "{$iDiaCodigoBarra}{$iMesCodigoBarra}{$iAnoCodigoBarra}";
    $oStdLinhaTipoQuatro->cb_valor_nominal             = str_pad(str_replace(".", "", $oDadosLinha->getValorNominal()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->cb_valor_desconto_abatimento = str_pad(str_replace(".", "",  $oDadosLinha->getValorDesconto()), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTipoQuatro->cb_valor_mora_juros          = str_pad(str_replace(".", "", $oDadosLinha->getValorJuros()), 17, "0", STR_PAD_LEFT);

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte17] */
    if ($oDadosLinha->getTipoFatura() == 2) {
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte17] */

      $oStdLinhaTipoQuatro->cb_data_vencimento           = str_repeat(" ", 20);
      $oStdLinhaTipoQuatro->cb_valor_nominal             = str_repeat(" ", 20);
      $oStdLinhaTipoQuatro->cb_valor_desconto_abatimento = str_repeat(" ", 10);
      $oStdLinhaTipoQuatro->cb_valor_mora_juros          = str_repeat(" ", 9);
    }

    $oStdLinhaTipoQuatro->campo_branco_cinco  = str_repeat(" ", 164);
    $oStdLinhaTipoQuatro->observacao_ob       = str_repeat(" ", 40);
    $oStdLinhaTipoQuatro->numero_autenticacao = str_repeat(" ", 16);

    $sConvenioAgencia = $oDadosLinha->getCodigoAgenciaPagadora() . $oDadosLinha->getDigitoVerificadorAgenciaPagadora();
    $oStdLinhaTipoQuatro->convenio_agencia_dv = $sConvenioAgencia;

    $sConvenioConta = $oDadosLinha->getContaPagadora() . $oDadosLinha->getDigitoVerificadorContaPagadora();
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte18] */
    $oStdLinhaTipoQuatro->convenio_conta_dv = str_pad($sConvenioConta, 10, "0", STR_PAD_LEFT);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte18] */

    $oStdLinhaTipoQuatro->campo_branco_seis            = str_repeat(" ", 7);
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte19] */
    $oStdLinhaTipoQuatro->retorno_operacao             = str_repeat(" ", 2);
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte19] */
    $oStdLinhaTipoQuatro->numero_sequencial_movimento  = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    return $oStdLinhaTipoQuatro;
  }

  /**
   * Constrói o trailer do arquivo
   * @return stdClass
   */
  private function constroiLinhaTrailer() {

    $oStdLinhaTrailer                                = new stdClass();
    $oStdLinhaTrailer->campo_nove                    = str_repeat("9", 35);
    $oStdLinhaTrailer->campo_branco                  = str_repeat(" ", 320);
    $oStdLinhaTrailer->somatorio_valores             = str_pad(str_replace(".", "", $this->nValorTotalDasMovimentacoes), 17, "0", STR_PAD_LEFT);
    $oStdLinhaTrailer->somatorio_sequencia_registros = str_pad($this->iContadorRegistros, 13, "0", STR_PAD_LEFT);
    return $oStdLinhaTrailer;
  }

  /**
   * Método que compara se houve alteração na conta bancária
   * @param stdClass $oStdContaAnterior
   * @param stdClass $oStdContaAtual
   * @return boolean
   */
  private function compararContaBancaria($oStdContaAnterior, $oStdContaAtual) {

    if ($oStdContaAnterior->banco         == $oStdContaAtual->c63_banco     &&
        $oStdContaAnterior->agencia       == $oStdContaAtual->c63_agencia   &&
        $oStdContaAnterior->digitoAgencia == $oStdContaAtual->c63_dvagencia &&
        $oStdContaAnterior->conta         == $oStdContaAtual->c63_conta     &&
        $oStdContaAnterior->digitoConta   == $oStdContaAtual->c63_dvconta) {
      return false;
    }
    return true;
  }

  /**
   * Salva os dados do cabeçalho da geração do arquivo
   * @throws BusinessException
   * @return boolean
   */
  private function salvarGeracaoArquivo() {

    $iCodigoRemessa      = $this->iCodigoRemessa;
    $oInstituicao        = $this->oInstituicao;
    $sHoraGeracaoArquivo = $this->dtHoraGeracaoArquivo;

    $this->oArquivoTransmissao = new ArquivoTransmissao();
    $this->oArquivoTransmissao->setCodigoRemessa($iCodigoRemessa);
    if (!empty($this->dtGeracaoArquivo)) {

      $dtDataGeracao = date('d/m/Y', strtotime($this->dtGeracaoArquivo));
      $this->oArquivoTransmissao->setDataAutorizacaoPagamento(new DBDate($dtDataGeracao));
    }
    if (!empty($this->dtAutorizacaoPagamento)) {

      $dtDataProcessamento = date('d/m/Y', strtotime($this->dtAutorizacaoPagamento));
      $this->oArquivoTransmissao->setDataGeracaoArquivo(new DBDate($dtDataProcessamento));
    }
    $this->oArquivoTransmissao->setHoraGeracaoArquivo($sHoraGeracaoArquivo);
    $this->oArquivoTransmissao->setInstituicao($oInstituicao);
    $this->oArquivoTransmissao->setDescricaoGeracao("Geração de Arquivo de Transmissão OBN");
    $this->oArquivoTransmissao->salvar();
    $this->iCodigoRemessa = $this->oArquivoTransmissao->getCodigoRemessa();
    return true;
  }

  /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte20] */
  /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte20] */

  /**
   * Vincula os movimentos ao cabeçalho da geração do arquivo
   * @param array $aMovimentosAgenda
   * @throws BusinessException
   * @return boolean
   */
  private function vincularMovimentosNaGeracao($aMovimentosAgenda) {

    $iAno         = $this->iAno;
    $iInstituicao = $this->oInstituicao->getSequencial();

    foreach ($aMovimentosAgenda as $iCodigoMovimento) {

//       $oMovimento = MovimentoArquivoTransmissao::getInstance($iCodigoMovimento, $iAno, $iInstituicao);
      $this->oArquivoTransmissao->vinculaMovimento($iCodigoMovimento);
    }
    return true;
  }

  /**
   * Busca os dados do header do arquivo retornando um objeto stdClass
   *
   * @param integer $iCodigoConvenio
   *
   * @return stdClass
   */
  private function constroiDadosHeader($iCodigoConvenio) {

  	if (empty($this->iSequencialArquivo)) {
    	$this->iSequencialArquivo = $this->getCodigoSequencialArquivo()->o150_proximonumero;
  	}

    list($iAno, $iMes, $iDia) = explode("-", $this->dtGeracaoArquivo);
    $oStdDadosHeader                                = new stdClass();
    $oStdDadosHeader->campo_zero                    = str_repeat("0", 35);
    $oStdDadosHeader->data_geracao_arquivo          = "{$iDia}{$iMes}{$iAno}";
    $oStdDadosHeader->hora_geracao_arquivo          = str_replace(":", "", $this->dtHoraGeracaoArquivo);
    $oStdDadosHeader->numero_remessa                = str_pad($this->iSequencialArquivo, 5, "0", STR_PAD_LEFT);
    $oStdDadosHeader->campo_exclusivo_header        = "10B001";
    $oStdDadosHeader->numero_contrato_banco_cliente = str_pad($iCodigoConvenio, 9, "0", STR_PAD_LEFT);
    $oStdDadosHeader->campo_branco                  = str_repeat(" ", 276);
    $oStdDadosHeader->numero_sequencial_arquivo     = str_pad($this->iSequencialRegistro, 7, "0", STR_PAD_LEFT);
    return $oStdDadosHeader;
  }

  /**
   * Função que controi um objeto do tipo MovimentoArquivoTransmissao, para que seja usado no gerador de arquivo obn
   * @param MovimentoArquivoTransmissao $oDadosMovimento
   * @return stdClass
   */
  private function constroiLinhaTipoUm(MovimentoArquivoTransmissao $oDadosMovimento) {

    $oInstituicao                  = $this->getInstituicao();
    $sAgenciaDigitoContaBancaria   = $oDadosMovimento->getCodigoAgenciaPagadora();
    $sAgenciaDigitoContaBancaria  .= $oDadosMovimento->getDigitoVerificadorAgenciaPagadora();
    $sContaBancaria                = $oDadosMovimento->getContaPagadora();
    $sContaBancaria               .= $oDadosMovimento->getDigitoVerificadorContaPagadora();
    $sContaBancaria                = str_pad($sContaBancaria, 10, "0", STR_PAD_LEFT);

    $oStdRegistroTipoUm                                              = new stdClass();
    $oStdRegistroTipoUm->campo_branco_ultimo                         = str_repeat(" ", 251);
    $oStdRegistroTipoUm->descricao_instituicao                       = substr($oInstituicao->getDescricao(), 0, 45);
    $oStdRegistroTipoUm->campo_branco                                = str_repeat(" ", 26);
    $oStdRegistroTipoUm->conta_instituicao                           = $sContaBancaria;
    $oStdRegistroTipoUm->codigo_instituicao_emitente_obs             = "";
    $oStdRegistroTipoUm->codigo_agenciabancaria_instituicao_emitente = $sAgenciaDigitoContaBancaria;
    $oStdRegistroTipoUm->identificador_linha                         = 1;
    return $oStdRegistroTipoUm;
  }

  private function buscaCodigoArquivoRemessa () {

  	if (isset($this->iCodigoRemessa)) {

  		$oDaoEmpAgeGeraObn = new cl_empagegeraobn();
  		$sSqlNumeracao     = $oDaoEmpAgeGeraObn->sql_query_file (null, "e138_numeracaoobn", null, "e138_empagegera = {$this->iCodigoRemessa}");
  		$rsNumeracao       = $oDaoEmpAgeGeraObn->sql_record($sSqlNumeracao);
  		if ($oDaoEmpAgeGeraObn->numrows == 0 ) {
  			throw new BusinessException("ERRO [ 0 ] - Regerando arquivo - Vinculo de remessa com numeração não encontrado.");
  		}
  	  return db_utils::fieldsMemory($rsNumeracao, 0)->e138_numeracaoobn;
  	}
  }

  /**
   * Retorna o sequencial do arquivo
   * @throws BusinessException
   * @return object
   */
  private function getCodigoSequencialArquivo() {

    $oDaoConfiguracaoOBN = new cl_obnnumeracao();
    $iInstituicao        = $this->getInstituicao()->getSequencial();
    $sWhere              = "o150_instit = {$iInstituicao}";
    $sSqlBuscaSequencial = $oDaoConfiguracaoOBN->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaSequencial   = $oDaoConfiguracaoOBN->sql_record($sSqlBuscaSequencial);

    if ($oDaoConfiguracaoOBN->erro_status == "0") {
      throw new BusinessException("Erro [ 0 ]: erro ao buscar sequencial do arquivo. {$oDaoConfiguracaoOBN->erro_msg}");
    }
    $oCodigoProximoNumero = db_utils::fieldsMemory($rsBuscaSequencial, 0);

    return $oCodigoProximoNumero;
  }

  /**
   * atualiza o sequencial do arquivo, apos a geração;
   */
  private function setCodigoSequencialArquivo() {

  	$oDaoConfiguracaoOBN = new cl_obnnumeracao();
  	$iInstituicao        = $this->getInstituicao()->getSequencial();
  	$iNumeroAtual        = $this->getCodigoSequencialArquivo()->o150_proximonumero;
  	$iSequencial         = $this->getCodigoSequencialArquivo()->o150_sequencial;
  	$iProximoNumero      = $iNumeroAtual + 1;

  	$oDaoConfiguracaoOBN->o150_sequencial    = $iSequencial;
  	$oDaoConfiguracaoOBN->o150_proximonumero = $iProximoNumero;
  	$oDaoConfiguracaoOBN->alterar($oDaoConfiguracaoOBN->o150_sequencial);
  	if ($oDaoConfiguracaoOBN->erro_status == '0') {
  		throw new DBException("ERRO [ 0 ] - atualizando código proximo arquivo - " . $oDaoConfiguracaoOBN->erro_msg );
  	}
  }
}
