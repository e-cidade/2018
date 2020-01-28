<?php
/**
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
 * Class GeradorArquivoPagFor
 */
class GeradorArquivoPagFor extends ArquivoTransmissao {

  /**
   * Caminho do arquivo onde ficam as mensagens de aviso ao usuário
   * @type string
   */
  const CAMINHO_MENSAGEM = 'financeiro.caixa.GeradorArquivoPagFor.';

  /**
   * Código do Layout cadastrado no e-cidade
   * @type integer
   */
  const ARQUIVO_PAGFOR = 256;

  /**
   * Código do Banco Bradesco FEBRABAN
   * @type integer
   */
  const CODIGO_BANCO_BRADESCO = 237;

  /**
   * @type string
   */
  protected $sNomeArquivo = '';

  /**
   * @type integer
   */
  protected $iAno;

  /**
   * Movimentos que serão emitidos no arquivo
   * @type stdClass[]
   */
  protected $aMovimentosPagFor = array();

  /**
   * @type db_layouttxt
   */
  protected $oDbLayoutTxt;

  /**
   * @type integer
   */
  protected $iNumeroLinhas;

  /**
   * @type integer
   */
  protected $iNumeroArquivo;

  /**
   * GeradorArquivoPagFor constructor
   * @param integer $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    parent::__construct($iCodigo);

    if (empty($this->iCodigoRemessa)) {
      return;
    }

    $oDaoNumeracao      = new cl_pagfornumeracao();
    $sSqlBuscaNumeracao = $oDaoNumeracao->sql_query_file(null, 'o152_numero', null, 'o152_empagegera = '.$this->iCodigoRemessa);
    $rsBuscaNumeracao   = db_query($sSqlBuscaNumeracao);
    if (!$rsBuscaNumeracao) {

      $oDadoMensagem = (object)array('codigo_arquivo' => $this->iCodigoRemessa);
      throw new DBException(_M(self::CAMINHO_MENSAGEM . 'erro_consulta_numero', $oDadoMensagem));
    }
    $this->iNumeroArquivo = db_utils::fieldsMemory($rsBuscaNumeracao, 0)->o152_numero;
  }

  /**
   * Coloca o objeto na linha controlando o númeroi/totalizador
   * @param stdClass $oDados
   * @param integer  $iTipoLinha
   */
  protected function adicionaLinha(stdClass $oDados, $iTipoLinha) {

    $oDados->sequencial_registro = str_pad(++$this->iNumeroLinhas, 6, '0', STR_PAD_LEFT);
    $this->oDbLayoutTxt->setByLineOfDBUtils($oDados, $iTipoLinha);
  }

  /**
   * @param $sString
   * @param $iTamanho
   * @return string
   */
  private static function padZeroLeft($sString, $iTamanho) {
    return str_pad($sString, $iTamanho, '0', STR_PAD_LEFT);
  }

  /**
   * @param array $aMovimentosPagFor
   *
   * @return File
   * @throws BusinessException
   * @throws Exception
   */
  public function emitir(array $aMovimentosPagFor) {

    $this->salvar();
    $this->vincularNumeracao();
    $this->aMovimentosPagFor = $aMovimentosPagFor;
    foreach ($this->aMovimentosPagFor as $iCodigoMovimento) {
      $this->vinculaMovimento($iCodigoMovimento);
    }
    return $this->emitirTxt();
  }

  /**
   * @param DBDate $oDataGeracao
   * @param DBDate $oDataPagamento
   * @param string $sHoraGeracao
   *
   * @return File
   * @throws ParameterException
   */
  public function reemitir(DBDate $oDataGeracao, DBDate $oDataPagamento, $sHoraGeracao) {

    $iCodigoRemessa = $this->getCodigoRemessa();
    if (empty($iCodigoRemessa)) {
      throw new ParameterException(_M(self::CAMINHO_MENSAGEM . 'parametro_codigo_remessa'));
    }
    $this->setDataGeracaoArquivo($oDataGeracao);
    $this->setDataAutorizacaoPagamento($oDataPagamento);
    $this->setHoraGeracaoArquivo($sHoraGeracao);

    return $this->emitirTxt();
  }

  /**
   * Realiza a emissão do arquivo a partir das informações do objeto.
   * @return File
   */
  private function emitirTxt() {

    $iMes = self::padZeroLeft($this->getDataGeracaoArquivo()->getMes(), 2);
    $iDia = self::padZeroLeft($this->getDataGeracaoArquivo()->getDia(), 2);

    $this->sNomeArquivo  = "PG{$iDia}{$iMes}".self::padZeroLeft($this->getNumeroPorDia(), 2).".REM";
    $this->oDbLayoutTxt  = new db_layouttxt(self::ARQUIVO_PAGFOR, "tmp/{$this->sNomeArquivo}");
    $this->iNumeroLinhas = 0;
    $this->gerarArquivoTXT();

    return new File("tmp/{$this->sNomeArquivo}");
  }

  /**
   * Método que define o numero diário da geração do arquivo baseado na Data De Geração
   * @return int
   */
  private function getNumeroPorDia() {

    $sData = $this->getDataGeracaoArquivo()->getDate(DBDate::DATA_EN);
    $sCaminhoArquivo = "tmp/pagfor_numero_{$sData}.txt";
    $iNumero = 1;
    if ( file_exists($sCaminhoArquivo) ) {
      $iNumero = ((int)file_get_contents($sCaminhoArquivo) + 1);
    }

    $hOpenFile = fopen($sCaminhoArquivo, "w+");
    fwrite($hOpenFile, $iNumero);
    fclose($hOpenFile);
    return $iNumero;
  }

  /**
   * Constrói o objeto com as informações para o header do arquivo.
   * @return stdClass
   * @throws BusinessException
   */
  private function constroiHeader() {

    $oParametroCaixa = new ParametroCaixa();
    $iConvenioBanco  = $oParametroCaixa->getConvenioBanco();
    if (empty($iConvenioBanco)) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM . 'convenio_invalido'));
    }

    $oHeader = new stdClass();
    $oHeader->identificacao_registro     = 0;
    $oHeader->codigo_comunicacao         = self::padZeroLeft($iConvenioBanco, 8);
    $oHeader->tipo_inscricao             = 2;//CNPJ
    $oHeader->cnpj_cpf_base_empresa      = self::padZeroLeft($this->oInstituicao->getCNPJ(), 15);
    $oHeader->nome_empresa               = $this->oInstituicao->getDescricao();
    $oHeader->tipo_servico               = 20;//Pagamento Fornecedor
    $oHeader->codigo_origem_arquivo      = 1;//Origem do Cliente. 2 - Origem do Banco.
    $oHeader->numero_remessa             = self::padZeroLeft($this->iNumeroArquivo, 5);
    $oHeader->numero_retorno             = str_repeat('0', 5);
    $oHeader->data_gravacao_arquivo      = self::formatarData($this->getDataGeracaoArquivo());
    $oHeader->hora_gravacao_arquivo      = str_pad(str_replace(":", "", $this->getHoraGeracaoArquivo()), 6, "0", STR_PAD_RIGHT);
    $oHeader->densidade_gravacao         = str_repeat(' ', 5);
    $oHeader->unidade_densidade          = str_repeat(' ', 3);
    $oHeader->identificacao_modulo_micro = str_repeat(' ', 5);
    $oHeader->tipo_processamento         = str_repeat(' ', 1);
    $oHeader->reservado_empresa          = str_pad(GeradorArquivoPagFor::CODIGO_BANCO_BRADESCO, 74, ' ',STR_PAD_RIGHT);
    $oHeader->reservado_banco_um         = str_repeat(' ', 80);
    $oHeader->reservado_banco_dois       = str_repeat(' ', 217);
    $oHeader->numero_lista_debito        = str_repeat('0', 9);
    $oHeader->reservado_banco_tres       = str_repeat(' ', 8);
    $oHeader->sequencial_registro        = self::padZeroLeft("1", 6);

    return $oHeader;
  }

  /**
   * Constrói o objeto com as informações do registro de um movimento.
   * @param MovimentoArquivoTransmissao $oMovimento
   *
   * @return stdClass
   * @throws Exception
   */
  private function constroiRegistro(MovimentoArquivoTransmissao $oMovimento) {

    $oDaoContaBancaria = new cl_contabancaria();
    $sSqlBuscaContaBancaria = $oDaoContaBancaria->sql_query_conta_pagadora('db83_conta', "e81_codmov = {$oMovimento->getCodigoMovimento()}");
    $rsBuscaContaBancaria   = db_query($sSqlBuscaContaBancaria);
    if (!$rsBuscaContaBancaria || pg_num_rows($rsBuscaContaBancaria) == 0) {
      throw new Exception(_M(self::CAMINHO_MENSAGEM . 'conta_pagadora_invalida'));
    }

    $iCodigoContaPagadora = db_utils::fieldsMemory($rsBuscaContaBancaria, 0)->db83_conta;

    $oCgm = null;
    $sTipoDocumento = '3';
    $sNomeFornecedor = $oMovimento->getNome();
    $sDocumentoFornecedor = '0';
    $iCodigoCGM = $oMovimento->getCGM();

    $iCodigoBancoFornecedor         = $oMovimento->getCodigoBancoFavorecido();
    $iAgenciaFornecedor             = $oMovimento->getCodigoAgenciaFavorecida();
    $iDigitoAgenciaFornecedor       = $oMovimento->getDigitoVerificadorAgenciaFavorecida();
    $sContaCorrenteFornecedor       = $oMovimento->getContaFavorecida();
    $iDigitoContaCorrenteFornecedor = $oMovimento->getDigitoVerificadorContaFavorecida();

    if (!empty($iCodigoCGM)) {

      $oCgm = CgmFactory::getInstanceByCgm($iCodigoCGM);
      $sTipoDocumento = $oCgm->isFisico() ? '1' : '2';

      if ($oCgm->isFisico()) {
        $sDocumentoFornecedor = substr($oCgm->getCpf(), 0, 9)."0000".substr($oCgm->getCpf(), 9, 2);
      } else {
        $sDocumentoFornecedor = $oCgm->getCnpj();
      }
    }

    if (empty($iCodigoCGM)) {

      $aNumeroEmpenho = explode("/", $oMovimento->getNumeroEmpenho());
      if (empty($aNumeroEmpenho) || count($aNumeroEmpenho) < 2) {
        throw new BusinessException(_M(self::CAMINHO_MENSAGEM . 'empenho_invalido'));
      }
      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($aNumeroEmpenho[0],
                                                                                $aNumeroEmpenho[1],
                                                                                $this->getInstituicao());
      $oCgmEmpenho     = $oEmpenho->getCgm();
      $sNomeFornecedor = $oCgmEmpenho->getNome();
      if ($oCgmEmpenho->isJuridico()) {
          $sDocumentoFornecedor = $oCgmEmpenho->getCnpj();
      }
      if ($oCgmEmpenho->isFisico()) {
        $sDocumentoFornecedor = $oCgmEmpenho->getCpf();
      }
    }

    $sModalidadePagamento = '01';
    $sInformacoesComplementares = str_repeat(' ', 40);
    if ($oMovimento->getCodigoBancoFavorecido() != self::CODIGO_BANCO_BRADESCO) {

      $aComplementoTED = array(
        'C',
        str_repeat('0', 6),
        '07', /* 07 - Pagamento de Fornec/Honor. */
        '01',
        str_repeat('0', 18),
        str_repeat(' ', 25),
      );
      $sInformacoesComplementares = implode('', $aComplementoTED);
      $sModalidadePagamento = '08';
    }

    $sNossoNumero     = '0';
    $sFatorVencimento = '0';
    $sCodigoBarra     = $oMovimento->getCodigoBarra();
    $sCarteira        = '0';
    if (trim($sCodigoBarra) != '') {

      $oCodigoBarra = new CodigoBarra($sCodigoBarra);

      $iCodigoBancoFornecedor         = $oCodigoBarra->getCodigoBanco();
      $iAgenciaFornecedor             = '0';
      $iDigitoAgenciaFornecedor       = '';
      $sContaCorrenteFornecedor       = '0';
      $iDigitoContaCorrenteFornecedor = '';
      if ($oCodigoBarra->getCodigoBanco() == self::CODIGO_BANCO_BRADESCO) {

        $oCodigoBarraBradesco = new CodigoBarraBradesco($oCodigoBarra);
        $sNossoNumero         = $oCodigoBarraBradesco->getNossoNumero();
        $sCarteira            = $oCodigoBarraBradesco->getCarteira();

        $iAgenciaFornecedor             = $oCodigoBarraBradesco->getAgenciaCedente();
        $iDigitoAgenciaFornecedor       = $oCodigoBarraBradesco->getDigitoAgencia();
        $sContaCorrenteFornecedor       = $oCodigoBarraBradesco->getContaCedente();
        $iDigitoContaCorrenteFornecedor = $oCodigoBarraBradesco->getDigitoContaCorrente();
      }
      $sFatorVencimento     = $oCodigoBarra->getFatorVencimento();
      $sModalidadePagamento = '31';

      $aComplementoCodigoBarra = array(
        $oCodigoBarra->getCampoLivre(),
        $oCodigoBarra->getDigitoCodigoBarras(),
        $oCodigoBarra->getCodigoMoeda(),
        str_repeat(' ', 13)
      );

      $sInformacoesComplementares = implode('', $aComplementoCodigoBarra);
    }
    $sNossoNumero = self::padZeroLeft($sNossoNumero, 12);
    $sFatorVencimento = self::padZeroLeft($sFatorVencimento, 4);

    $oStdMovimento = new stdClass();
    $oStdMovimento->identificacao_registro           = 1;
    $oStdMovimento->tipo_inscricao_fornecedor        = $sTipoDocumento;
    $oStdMovimento->documento                        = self::padZeroLeft($sDocumentoFornecedor, 15);
    $oStdMovimento->nome_fornecedor                  = $sNomeFornecedor;
    $oStdMovimento->endereco_fornecedor              = "{$oMovimento->getMunicipio()}-{$oMovimento->getEndereco()}-Numero: {$oMovimento->getNumeroEndereco()}";
    $oStdMovimento->cep_fornecedor                   = $oMovimento->getCep();
    $oStdMovimento->codigo_banco_fornecedor          = self::padZeroLeft($iCodigoBancoFornecedor, 3);
    $oStdMovimento->codigo_agencia_fornecedor        = self::padZeroLeft($iAgenciaFornecedor, 5);
    $oStdMovimento->digito_agencia_fornecedor        = str_pad($iDigitoAgenciaFornecedor, 1, ' ', STR_PAD_LEFT);
    $oStdMovimento->conta_corrente_fornecedor        = self::padZeroLeft($sContaCorrenteFornecedor, 13);
    $oStdMovimento->digito_conta_corrente_fornecedor = str_pad($iDigitoContaCorrenteFornecedor, 2, ' ', STR_PAD_RIGHT);
    $oStdMovimento->numero_pagamento                 = self::padZeroLeft($oMovimento->getCodigoMovimento(), 16);
    $oStdMovimento->carteira                         = self::padZeroLeft($sCarteira, 3);
    $oStdMovimento->nosso_numero                     = $sNossoNumero;
    $oStdMovimento->seu_numero                       = str_repeat(' ', 15);
    $oStdMovimento->data_vencimento                  = self::formatarData($this->getDataGeracaoArquivo());
    $oStdMovimento->data_emissao_documento           = str_repeat('0', 8);
    $oStdMovimento->data_limite_desconto             = str_repeat('0', 8);
    $oStdMovimento->zeros                            = '0';
    $oStdMovimento->fator_vencimento                 = $sFatorVencimento;
    $oStdMovimento->valor_documento                  = self::padZeroLeft($oMovimento->getValor(), 10);
    $oStdMovimento->valor_pagamento                  = self::padZeroLeft($oMovimento->getValor(), 15);
    $oStdMovimento->valor_desconto                   = self::padZeroLeft("0", 15);
    $oStdMovimento->valor_acrescimo                  = self::padZeroLeft("0", 15);
    $oStdMovimento->tipo_documento                   = '05';
    $oStdMovimento->reservado                        = '0';
    $oStdMovimento->numero_nota_fiscal               = str_repeat('0', 9);
    $oStdMovimento->serie_documento                  = str_repeat(' ', 2);
    $oStdMovimento->modalidade_pagamento             = $sModalidadePagamento;
    $oStdMovimento->data_efetivacao_pagamento        = self::formatarData($this->getDataAutorizacaoPagamento());
    $oStdMovimento->moeda_cnab                       = str_repeat(' ', 3);
    $oStdMovimento->situacao_agendamento             = '01';
    $oStdMovimento->fixo_branco                      = str_repeat(' ', 10);
    $oStdMovimento->tipo_movimento                   = '0';
    $oStdMovimento->codigo_movimento                 = '00';
    $oStdMovimento->horario_consulta_saldo           = str_repeat(' ', 4);
    $oStdMovimento->saldo_disponivel_consulta        = str_repeat(' ', 15);
    $oStdMovimento->valor_taxa_pre_funding           = str_repeat(' ', 15);
    $oStdMovimento->reservado_um                     = str_repeat(' ', 6);
    $oStdMovimento->sacador_avalista                 = str_repeat(' ', 40);
    $oStdMovimento->reservado_dois                   = str_repeat(' ', 1);
    $oStdMovimento->nivel_informacao_retorno         = str_repeat(' ', 1);
    $oStdMovimento->informacoes_complementares       = $sInformacoesComplementares;
    $oStdMovimento->codigo_area_empresa              = '00';
    $oStdMovimento->uso_empresa                      = str_repeat(' ', 35);
    $oStdMovimento->reserva_tres                     = str_repeat(' ', 22);
    $oStdMovimento->codigo_lancamento                = str_repeat('0', 5);
    $oStdMovimento->reserva_quatro                   = ' ';
    $oStdMovimento->tipo_conta_fornecedor            = $oMovimento->getTipoContaFavorecido();
    $oStdMovimento->conta_complementar               = self::padZeroLeft($iCodigoContaPagadora, 7);
    $oStdMovimento->reserva_cinco                    = str_repeat(' ', 5);

    $iTipoContaFornecedor = $oMovimento->getTipoContaFavorecido();
    if (!empty($iTipoContaFornecedor) && !in_array($iTipoContaFornecedor, array(1, 2))) {

      $oDadosBancarios = (object)array('nome_fornecedor' => $oCgm->getNome());
      throw new Exception(_M(self::CAMINHO_MENSAGEM . 'tipo_conta_invalido', $oDadosBancarios));
    }

    return $oStdMovimento;
  }

  /**
   * Gera o arquivo TXT para os movimentos.
   * @throws DBException
   */
  private function gerarArquivoTXT() {

    $this->adicionaLinha($this->constroiHeader(), db_layouttxt::TIPO_LINHA_HEADER_ARQUIVO);
    $aMovimentos = $this->getMovimentos();

    $nValorTotalPagamento = 0;
    foreach ($aMovimentos as $oMovimento) {
      $nValorTotalPagamento += $oMovimento->getValor();
      $this->adicionaLinha($this->constroiRegistro($oMovimento), db_layouttxt::TIPO_LINHA_REGISTRO);
    }
    $this->adicionaLinha($this->constroiTrailler($nValorTotalPagamento), db_layouttxt::TIPO_LINHA_TRAILLER_ARQUIVO);

    $this->oDbLayoutTxt->fechaArquivo();
  }

  /**
   * @param $nValorTotalPagamento
   * @return stdClass
   */
  private function constroiTrailler($nValorTotalPagamento) {

    $iLinhaAtual = ($this->iNumeroLinhas + 1);
    $oStdDados = new stdClass();
    $oStdDados->identificacao_registro = 9;
    $oStdDados->quantidade_registro    = str_pad($iLinhaAtual, '6', '0', STR_PAD_LEFT);
    $oStdDados->total_valor_pagamentos = self::padZeroLeft($nValorTotalPagamento, 17);
    $oStdDados->reserva_um             = str_repeat(' ', 470);
    $oStdDados->sequencial_registro    = null;
    return $oStdDados;
  }

  /**
   * @param DBDate $oData
   * @return mixed
   */
  private static function formatarData(DBDate $oData) {
    return str_replace('-', '', $oData->getDate(DBDate::DATA_EN));
  }

  /**
   * @return bool
   * @throws Exception
   */
  protected function vincularNumeracao() {

    $sCampos = '(coalesce(max(o152_numero), 0) + 1) as proximo_numero';
    $oDaoNumeracao    = new cl_pagfornumeracao();
    $sSqlBuscaNumero  = $oDaoNumeracao->sql_query_file(null, $sCampos, null, "o152_instit = {$this->oInstituicao->getCodigo()}");
    $rsBuscaNumeracao = db_query($sSqlBuscaNumero);
    if (!$rsBuscaNumeracao) {
      throw new Exception(_M(self::CAMINHO_MENSAGEM . 'erro_buscar_proximo_numero'));
    }

    $iProximoNumero = db_utils::fieldsMemory($rsBuscaNumeracao, 0)->proximo_numero;
    $this->iNumeroArquivo = $iProximoNumero;
    $oDaoNumeracao->o152_sequencial = null;
    $oDaoNumeracao->o152_instit     = $this->oInstituicao->getCodigo();
    $oDaoNumeracao->o152_numero     = $this->iNumeroArquivo;
    $oDaoNumeracao->o152_empagegera = $this->getCodigoRemessa();
    $oDaoNumeracao->incluir(null);
    if ($oDaoNumeracao->erro_status == '0') {
      throw new Exception(_M(self::CAMINHO_MENSAGEM . 'erro_buscar_salvar_proximo_numero'));
    }
    return true;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }
}