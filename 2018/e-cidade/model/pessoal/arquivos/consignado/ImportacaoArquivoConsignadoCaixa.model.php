<?php

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 03/05/16
 * Time: 11:22
 */
class ImportacaoArquivoConsignadoCaixa extends ImportacaoArquivoConsignado {

  /**
   * @var DBLayoutReader
   */
  private $oLayoutArquivo;

  /**
   * @var string
   */
  private $sCaminhoArquivo = '';

  /**
   * @var ArquivoConsignado
   */
  private $oArquivoConsignado;


  /**
   * ImportacaoArquivoConsignadoCaixa constructor.
   * @param                $sArquivo
   * @param \DBCompetencia $oCompetencia
   * @param \Instituicao   $oInstituicao
   */
  public function __construct($sArquivo, \DBCompetencia $oCompetencia, \Instituicao $oInstituicao) {

    $this->oCompetencia    = $oCompetencia;
    $this->oInstituicao    = $oInstituicao;
    $this->sCaminhoArquivo =  $sArquivo;

  }

  /**
   * Processar os dados do arquivo
   */
  function processar() {

    if ($this->temConsignacaoProcessadaNaCompetencia()) {

      $sCompetencia = $this->oCompetencia->getMes()."/".$this->oCompetencia->getAno();
      $sMensagem    = "As consignações para a competência {$sCompetencia} já foi processada.\n";
      $sMensagem   .= "Não é permitida a importação de novos arquivos.";
      throw new BusinessException($sMensagem);
    }

    $iCodigoLayout            = $this->oConfiguracao->getLayout()->getCodigo();
    $this->oLayoutArquivo     = new DBLayoutReader($iCodigoLayout, $this->sCaminhoArquivo);
    $this->oArquivoConsignado = new ArquivoConsignado();
    $sNomeArquivo = basename($this->sCaminhoArquivo);
    $this->oArquivoConsignado->setArquivo($sNomeArquivo);
    $this->oArquivoConsignado->setNome($sNomeArquivo);
    $iOIDArquivo = DBLargeObject::criaOID(true);
    DBLargeObject::escrita($this->sCaminhoArquivo, $iOIDArquivo);

    $this->processarArquivo();
    $this->oArquivoConsignado->setArquivo($iOIDArquivo);
    $this->oArquivoConsignado->setRelatorio('null');
    $this->oArquivoConsignado->setCompetencia($this->oCompetencia);
    $this->oArquivoConsignado->setInstituicao($this->oInstituicao);
    $this->oArquivoConsignado->setBanco($this->oConfiguracao->getBanco());
    ArquivoConsignadoRepository::persist($this->oArquivoConsignado);

  }

  function setConfiguracao(ConfiguracaoConsignado $oConfiguracao) {
    $this->oConfiguracao = $oConfiguracao;
  }

  /**
   * Processa os dados do arquivo, validado os dados do mesmo
   */
  private function processarArquivo() {

    foreach ($this->oLayoutArquivo->getLines() as $oLinha) {

      if (empty($oLinha)) {
        continue;
      }

      if ($oLinha->codigo_do_registro == 1) {
        $this->validarArquivoConsignadoCaixa($oLinha);
      }

      if ($oLinha->codigo_do_registro != 2) {
        continue;
      }

      $oRegistro = $this->getDadosFinanciamento($oLinha);
      $this->oArquivoConsignado->adicionarRegistro($oRegistro);
    }
  }

  /**
   * Valida se o arquivo importado é um arquivo valido para o consignado da caixa
   * @throws \BusinessException
   */
  private function validarArquivoConsignadoCaixa($oLinha) {

    if (!isset($oLinha->codigo_do_registro)) {
      throw new BusinessException("O Arquivo informado não é um arquivo consignado válido para a caixa");
    }

    if ($oLinha->identificacao_do_sistema != "SIAPX" && $oLinha->identificacao_operacao  != "CONSIGNACOES") {
      throw new BusinessException("O Arquivo informado não é um arquivo consignado válido para a caixa");
    }
  }

  /**
   * Reto
   * @param $oLinha
   * @return RegistroConsignado
   */
  private function getDadosFinanciamento($oLinha){

    $oRegistro = new RegistroConsignado();
    $oRegistro->setParcela($oLinha->n_prestacao);
    $oRegistro->setRubrica($this->oConfiguracao->getRubrica());
    $oRegistro->setMatricula(trim((int)$oLinha->n_matricula));
    $oRegistro->setNome($oLinha->nome_cliente);
    $oRegistro->setInstituicao($this->oInstituicao);
    $oRegistro->setTotalParcelas($oLinha->n_prestacao);
    $oRegistro->setValorDescontar((float)$oLinha->valor_prestacao / 100);
    $oMatricula = null;
    try {

      $oMatricula = ServidorRepository::getInstanciaByCodigo(trim((int)$oLinha->n_matricula));
      $oRegistro->setServidor($oMatricula);
    } catch (Exception $e) {
      $oRegistro->setMotivo(RegistroConsignado::MOTIVO_SERVIDOR_INVALIDO);
    }
    $this->consistirDadosRegistro($oRegistro);
    return $oRegistro;
  }

  /**
   * @param \RegistroConsignado $oRegistro
   * @return bool
   * @throws \DBException
   */
  private function consistirDadosRegistro(RegistroConsignado $oRegistro) {

    $oServidor = $oRegistro->getServidor();
    if (empty($oServidor)) {
      $oRegistro->setMotivo(RegistroConsignado::MOTIVO_SERVIDOR_INVALIDO);
      return false;
    }

    $this->validarAfastamento($oRegistro);
    $this->validarRescisao($oRegistro);
    $this->validaServidorFalecido($oRegistro);
  }

  /**
   * @return bool
   * @throws \DBException
   */
  private function temConsignacaoProcessadaNaCompetencia() {

    $oArquivo = ArquivoConsignadoRepository::getUltimoArquivoNaCompetenciaDoBanco($this->oInstituicao, $this->oCompetencia, $this->oConfiguracao->getBanco(), true);
    if (!empty($oArquivo)) {
      return true;
    }
    return false;
  }
}