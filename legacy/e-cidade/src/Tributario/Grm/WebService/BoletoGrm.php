<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Grm\WebService;


use Dompdf\Exception;
use ECidade\Tributario\Grm\EmissaoDocumento;
use ECidade\Tributario\Grm\Recibo;
use ECidade\Tributario\Grm\Repository\TipoRecolhimento as TipoRecolhimentoRepository;
use ECidade\Tributario\Grm\Repository\UnidadeGestora;
use Ecidade\Tributario\Grm\TipoRecolhimento;

class BoletoGrm {

  /**
   * @var integer
   */
  protected $cgm;

  /**
   * @var string
   */
  protected $nome;

  /**
   * @var string
   */
  protected $cpfcnpj;
  /**
   * @var integer
   */
  protected $unidadeGestora;

  /**
   * @var integer
   */
  protected $tipoRecolhimento;

  /**
   * Numpre do Recibo Gerado
   * @var integer
   */
  protected $numpre;

  /**
   * @var \DBCompetencia
   */
  protected $competencia;

  /**
   * @var \DBDate
   */
  protected $dataVencimento;

  /**
   * número de Referência
   * @var string
   */
  protected $numeroReferencia;

  /**
   * @var float;
   */
  protected $valor = 0;

  /**
   * @var float;
   */
  protected $valorDesconto = 0;

  /**
   * @var float;
   */
  protected $valorMulta = 0;

  /**
   * @var float;
   */
  protected $valorJuros = 0;

  /**
   * @var float;
   */
  protected $valorOutrosAcrescimos = 0;

  /**
   * Outras deduções
   * @var int
   */
  protected $valorOutrasDeducoes = 0;

  /**
   * @var float
   */
  protected $valorTotal = 0;

  protected $atributos = array();

  /**
   *
   * Tipo de Pessoa Emissora do Documento
   * 1 = Juridica 2 = Fisica
   * @var int
   */
  protected $tipoPessoa = 1;

  public function __construct() {

  }

  /**
   * @return integer
   */
  public function getCgm() {
    return $this->cgm;
  }

  /**
   * @param integer
   */
  public function setCgm($cgm) {
    $this->cgm = $cgm;
  }

  /**
   * @return string
   */
  public function getNome() {

    return $this->nome;
  }

  /**
   * @param string $nome
   */
  public function setNome($nome) {

    $this->nome = $nome;
  }

  /**
   * @return string
   */
  public function getCpfcnpj() {
    return $this->cpfcnpj;
  }

  /**
   * @param string $cpfcnpj
   */
  public function setCpfcnpj($cpfcnpj) {
    $this->cpfcnpj = str_replace(array(',', ".", "/"), '', $cpfcnpj);
    if (strlen($this->cpfcnpj) == 14) {
      $this->tipoPessoa = 2;
    }
    if (strlen($this->cpfcnpj) == 11) {
      $this->tipoPessoa = 1;
    }
  }



  /**
   * @return integer
   */
  public function getUnidadeGestora() {
    return $this->unidadeGestora;
  }

  /**
   * @param integer
   */
  public function setUnidadeGestora($unidadeGestora) {
    $this->unidadeGestora = $unidadeGestora;
  }

  /**
   * @return integer
   */
  public function getTipoRecolhimento() {
    return $this->tipoRecolhimento;
  }

  /**
   * @param integer
   */
  public function setTipoRecolhimento($tipoRecolhimento) {
    $this->tipoRecolhimento = $tipoRecolhimento;
  }

  /**
   * @return int
   */
  public function getNumpre() {

    return $this->numpre;
  }

  /**
   * @param int $numpre
   */
  public function setNumpre($numpre) {

    $this->numpre = $numpre;
  }

  /**
   * @return \DBCompetencia
   */
  public function getCompetencia() {

    return $this->competencia;
  }

  /**
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia($competencia) {

    $this->competencia = $competencia;
  }

  /**
   * @return string
   */
  public function getDataVencimento() {

    return $this->dataVencimento;
  }

  /**
   * @param \DBDate $dataVencimento
   */
  public function setDataVencimento($dataVencimento) {

    $this->dataVencimento = $dataVencimento;
  }

  /**
   * @return string
   */
  public function getNumeroReferencia() {

    return $this->numeroReferencia;
  }

  /**
   * @param string $numeroReferencia
   */
  public function setNumeroReferencia($numeroReferencia) {

    $this->numeroReferencia = $numeroReferencia;
  }

  /**
   * @return float
   */
  public function getValor() {

    return $this->valor;
  }

  /**
   * @param float $valor
   */
  public function setValor($valor) {
    $this->valor = $valor;
  }

  /**
   * @return float
   */
  public function getValorDesconto() {
    return $this->valorDesconto;
  }

  /**
   * @param float $valorDesconto
   */
  public function setValorDesconto($valorDesconto) {
    $this->valorDesconto = $valorDesconto;
  }

  /**
   * @return float
   */
  public function getValorMulta() {
    return $this->valorMulta;
  }

  /**
   * @param float $valorMulta
   */
  public function setValorMulta($valorMulta) {
    $this->valorMulta = $valorMulta;
  }

  /**
   * @return float
   */
  public function getValorJuros() {
    return $this->valorJuros;
  }

  /**
   * @param float $valorJuros
   */
  public function setValorJuros($valorJuros) {
    $this->valorJuros = $valorJuros;
  }

  /**
   * @return float
   */
  public function getValorOutrosAcrescimos() {
    return $this->valorOutrosAcrescimos;
  }

  /**
   * @param float $valorOutrosAcrescimos
   */
  public function setValorOutrosAcrescimos($valorOutrosAcrescimos) {
    $this->valorOutrosAcrescimos = $valorOutrosAcrescimos;
  }

  /**
   * @return float
   */
  public function getValorTotal() {

    return $this->valorTotal;
  }

  /**
   * @param float $valorTotal
   */
  public function setValorTotal($valorTotal) {

    $this->valorTotal = $valorTotal;
  }

  public function calcularValorTotal() {

    $valorTotal = round($this->getValor() - $this->getValorDesconto() - $this->getValorOutrasDeducoes()
                        + $this->getValorMulta() + $this->getValorJuros() + $this->getValorOutrosAcrescimos(), 2);
    return $valorTotal;
  }

  /**
   * @return int
   */
  public function getValorOutrasDeducoes() {

    return $this->valorOutrasDeducoes;
  }

  /**
   * @param int $valorOutrasDeducoes
   */
  public function setValorOutrasDeducoes($valorOutrasDeducoes) {
    $this->valorOutrasDeducoes = $valorOutrasDeducoes;
  }

  /**
   * @return array
   */
  public function getAtributos() {
    return $this->atributos;
  }

  /**
   * @param array $atributos
   */
  public function setAtributos($atributos) {
    $this->atributos = $atributos;
  }


  /**
   * Gera o Recibo e Realiza a Emissão do Documento
   */
  public function gerar() {

    $ip          = db_getsession("DB_ip");
    $dataEmissao = new \DBDate(date('Y-m-d'));

    if ($this->calcularValorTotal() != $this->getValorTotal()) {
      throw new \BusinessException("Valor total  {$this->calcularValorTotal()} está inconsistente do informado {$this->getValorTotal()}. Verifique!");
    }


    db_inicio_transacao();
    $oRecibo     = new Recibo();
    $anoSessao   = db_getsession("DB_anousu");
    $instituicao = \InstituicaoRepository::getInstituicaoPrefeitura();
    $this->cgm   = \CgmFactory::getInstanceByCnpjCpf($this->getCpfcnpj());
    $cadastrarCidadao = false;

    if (empty($this->cgm)) {
      $this->cgm = $instituicao->getCgm();
      $cadastrarCidadao = true;
    }
    $oRecibo->setCgm($this->cgm);

    $oUnidadeGestoraRepository = new UnidadeGestora();
    $oUnidadeGestora           = $oUnidadeGestoraRepository->getById($this->getUnidadeGestora());
    $recolhimentoRepository    = new TipoRecolhimentoRepository();
    $oRecolhimento             = $recolhimentoRepository->getTipoRecolhimento($this->getTipoRecolhimento());
    $oGrupoAtributo            = TipoRecolhimentoRepository::getAtributosDoRecolhimento($oRecolhimento);

    $aErroNosDados = $this->validarDadosPorTipoDeRecolhimento($oRecolhimento);
    if (count($aErroNosDados) > 0) {
      throw new \ParameterException(implode("\n", $aErroNosDados));
    }
    $sHistorico      = "\nUnidade Gestora: {$oUnidadeGestora->getNome()}\n";
    $sHistorico     .= "Recolhimento: {$oRecolhimento->getCodigoRecolhimento()} - {$oRecolhimento->getNome()}";
    if ($this->getNumeroReferencia() != '') {
      $sHistorico .= "\nNúmero de Referência: {$this->getNumeroReferencia()}";
    }
    $sHistorico .= "\nCompetência: {$this->getCompetencia()}";
    if (!empty($oGrupoAtributo)) {

      $atributos = $oGrupoAtributo->getAtributos();
      $this->validarAtributos($atributos);
      $sHistorico .= $this->getObservacaoAtributos($atributos);
    }

    if ($oRecolhimento->getInstrucoes() !='') {
      $sHistorico .= "\n".($oRecolhimento->getInstrucoes());
    }

    $oReceita = $oUnidadeGestora->getReceitaDoTipoDeRecolhimento($oRecolhimento);
    if (empty($oReceita)) {
      throw new \BusinessException("Receita não encontrada para o recolhimento {$oRecolhimento->getCodigoRecolhimento()}");
    }

    $dataVencimento  = new \DBDate($this->getDataVencimento());

    if ($dataVencimento->getTimeStamp() < $dataEmissao->getTimeStamp()) {
      throw new \BusinessException("Data de Vencimento deve ser maior ou igual a data atual.");
    }

    $reciboProtocolo = new \Recibo(1, $this->cgm->getCodigo());

    $reciboProtocolo->setDataVencimentoRecibo($dataVencimento->getDate());
    $reciboProtocolo->setVinculoCgm($this->cgm->getCodigo());
    $nValorReceita = $this->getValorTotal();

    if ($this->getValorMulta() > 0 && $oReceita->getReceitaMulta() != '') {

      $nValorReceita -= $this->getValorMulta();
      $reciboProtocolo->adicionarReceita($oReceita->getReceitaMulta()->getCodigo(), $this->getValorMulta(), 0);
    }

    if ($this->getValorJuros() > 0 && $oReceita->getReceitaJuros() != '') {

      $nValorReceita -= $this->getValorJuros();
      $reciboProtocolo->adicionarReceita($oReceita->getReceitaJuros()->getCodigo(), $this->getValorJuros(), 0);
    }
    $reciboProtocolo->adicionarReceita($oReceita->getCodigo(), $nValorReceita, 0);
    $reciboProtocolo->setHistorico($sHistorico);
    $reciboProtocolo->emiteRecibo();


    $regraEmissao      = new \regraEmissao(null, 26, $instituicao->getCodigo(), $dataEmissao->getDate(), $ip);
    $valorCodigoBarras = str_replace('.','',str_pad(number_format($reciboProtocolo->getTotalRecibo(),2,"","."),11,"0",STR_PAD_LEFT));

    $convenio     = new \convenio($regraEmissao->getConvenio(),
                                  $reciboProtocolo->getNumpreRecibo(),
                                  1,
                                  $reciboProtocolo->getTotalRecibo(),
                                  db_formatar($valorCodigoBarras, 's', '0', 11, 'e'),
                                  $reciboProtocolo->getDataVencimentoRecibo(),
                                  6);

    $nomeRecibo   = 'recibo_grm_'.$this->cgm->getCodigo().'_'.date('YmdHis').'.pdf';
    $localArquivo = "tmp/{$nomeRecibo}";

    $oRecibo->setValor($this->getValor());
    $oRecibo->setDataEmissao(new \DBDate($this->getDataVencimento()));
    $oRecibo->setDataVencimento(new \DBDate($this->getDataVencimento()));
    $oRecibo->setCgm($this->cgm);
    $oRecibo->setCompetencia($this->getCompetencia());
    $oRecibo->setTipoRecolhimento($oRecolhimento);
    $oRecibo->setNumpre($reciboProtocolo->getNumpreRecibo());
    $oRecibo->setNumeroReferencia($this->getNumeroReferencia());
    $oRecibo->setUnidadeGestora($oUnidadeGestora);
    $oRecibo->setValor($this->getValor());
    $oRecibo->setValorDesconto($this->getValorDesconto());
    $oRecibo->setValorJuros($this->getValorJuros());
    $oRecibo->setValorMulta($this->getValorMulta());
    $oRecibo->setValorOutrosAcrescimento($this->getValorOutrosAcrescimos());
    $oRecibo->setValorTotal($this->getValorTotal());
    $oRecibo->setValorOutrasDeducoes($this->getValorOutrasDeducoes());
    $oRecibo->setLinhaDigitavel($convenio->getLinhaDigitavel());
    $oRecibo->setCodigoBarras($convenio->getCodigoBarra());
    foreach ($this->getAtributos() as $atributo) {

      /**
       * todo mover para ValueObject
       */
      $atributoAdicionar = new \stdClass();
      $atributoAdicionar->codigo_atributo = $atributo->id;
      $atributoAdicionar->valor_plano     = $atributo->valor;
      $oRecibo->setAtributo($atributo->id, $atributoAdicionar);
    }

    $oReciboRepository = new \ECidade\Tributario\Grm\Repository\Recibo();
    $oReciboRepository->persist($oRecibo);


    if ($cadastrarCidadao) {

      /**
       * Este código busca o cidadão por Nome e Documento.
       *
       * Em caso de não encontrar o cidadão com mesmo nome e documento, ele buscará o cidadão somente pelo documento.
       * Se ele achar o cidadão por documento, cria um novo registro com um sequencial interno diferente do existente
       *
       * Do contrário, inclui novo cidadão.
       *
       */
      $nomeDecoded = utf8_decode($this->getNome());
      $oCidadao = \Cidadao::getPorDocumentoENome($nomeDecoded, $this->getCpfcnpj());
      if (!$oCidadao) {

        $oCidadao = new \Cidadao(null, null);
        $oCidadaoDocumento = \Cidadao::getPorDocumento($this->getCpfcnpj());
        if ($oCidadaoDocumento) {
          $oCidadao = clone $oCidadaoDocumento;
        }
        $oCidadao->setNome($nomeDecoded);
        $oCidadao->setCpfCnpj($this->getCpfcnpj());
        $oCidadao->setSituacaoCidadao(4);
        $oCidadao->setAtivo(true);
        $oCidadao->salvar();
      }

      $oRecibo->setCidadao($oCidadao);
      $daoGuiaCidadao = new \cl_guiarecolhimentocidadao();
      $daoGuiaCidadao->k177_sequencial       = null;
      $daoGuiaCidadao->k177_guiarecolhimento = $oRecibo->getCodigo();
      $daoGuiaCidadao->k177_cidadao          = $oCidadao->getCodigo();
      $daoGuiaCidadao->k177_cidadaoseq       = $oCidadao->getSequencialInterno();
      $daoGuiaCidadao->incluir(null);
      if ($daoGuiaCidadao->erro_status === "0") {
        throw new \DBException("Ocorreu um erro ao salvar o vínculo da Guia de Recolhimento com o cidadão.");
      }
    }

    $oEmissaoDocumentoGrm = new EmissaoDocumento($reciboProtocolo, $instituicao, $oRecibo);
    $oEmissaoDocumentoGrm->setRegraEmissao($regraEmissao);
    $oEmissaoDocumentoGrm->setConvenio($convenio);
    $oEmissaoDocumentoGrm->setAnousu($anoSessao);
    $oEmissaoDocumentoGrm->setHistorico($sHistorico);
    $arquivo = $oEmissaoDocumentoGrm->gerarPdfNoLocal($localArquivo);

    $retorno          = new \stdClass();
    $retorno->arquivo = base64_encode(file_get_contents($arquivo->getFilePath()));
    $retorno->numpre  = $reciboProtocolo->getNumpreRecibo();
    unlink($localArquivo);

    db_fim_transacao(false);
    return $retorno;
  }

  /**
   * Realiza a consistencia dos dados enviados pela guia
   * @param \Ecidade\Tributario\Grm\TipoRecolhimento $tipoRecolhimento
   * @return array
   */
  public function validarDadosPorTipoDeRecolhimento(TipoRecolhimento $tipoRecolhimento) {

    $aErros = array();
    if ($tipoRecolhimento->obrigaNumeroReferencia() && empty($this->numeroReferencia)) {
      $aErros['numero_referencia'] = 'Número de Referência deve ser informado.';
    }

    switch ($tipoRecolhimento->getTipoPessoa()) {
      case TipoRecolhimento::TIPO_EMISSAO_FISICA:

        if (strlen($this->cpfcnpj) != 11) {
          $aErros['recolhedor'] = "Recolhimento {$tipoRecolhimento->getCodigoRecolhimento()} - {$tipoRecolhimento->getNome()} é permitido para pessoa física.";
        }
        break;

      case TipoRecolhimento::TIPO_EMISSAO_JURIDICA:

        if (strlen($this->cpfcnpj) != 14) {
          $aErros['recolhedor'] = "Recolhimento {$tipoRecolhimento->getCodigoRecolhimento()} - {$tipoRecolhimento->getNome()} é permitido para pessoas jurídica.";
        }
        break;
    }

    if (!empty($this->valorOutrosAcrescimos) && !$tipoRecolhimento->informaOutrosAcrescimos()) {
      $aErros['outros_acrescimos'] = 'Outros Acréscimos não devem ser informados.';
    }

    if (!empty($this->valorOutrasDeducoes) && !$tipoRecolhimento->informaOutrasDeducoes()) {
      $aErros['outras_deducoes'] = 'Outras Deduções não devem ser informadas.';
    }

    if (!empty($this->valorJuros) && !$tipoRecolhimento->informaJuros()) {
      $aErros['juros'] = 'Juros/Encargos não devem ser informados.';
    }

    if (!empty($this->valorMulta) && !$tipoRecolhimento->informaMulta()) {
      $aErros['multa'] = 'Mora/Multa não devem ser informada.';
    }

    if (!empty($this->valorDesconto) && !$tipoRecolhimento->informaDesconto()) {
      $aErros['desconto'] = 'Desconto/abatimento não deve ser informado.';
    }

    if ($this->tipoPessoa == 2 && !\DBString::isCNPJ($this->cpfcnpj)) {
      $aErros['cpnj'] = 'CNPJ informado não é válido!';
    }

    if ($this->tipoPessoa == 1 && !\DBString::isCPF($this->cpfcnpj)) {
      $aErros['cpf'] = 'CPF informado não é válido!';
    }
    return $aErros;
  }

  /**
   * @param \DBAttDinamicoAtributo[] $atributosDoRegime
   *
   * @throws \Exception
   */
  protected function validarAtributos(array $atributosDoRegime) {

    foreach ($this->getAtributos() as $atributo) {

      if (!isset($atributosDoRegime[$atributo->id])) {
        throw new \Exception("Atributo {$atributo->nome} não existe para o tipo de recolhimento.");
      }
      $dadosAtributo = $atributosDoRegime[$atributo->id];
      $nomeAtributo  = $dadosAtributo->getDescricao();
      if ($dadosAtributo->isObrigatorio() && $atributo->valor === '') {
        throw new \Exception("Atributo {$nomeAtributo} é de preenchimento obrigatório.");
      }
      $valorAtributo = $atributo->valor;
      switch ($dadosAtributo->getTipo()) {

        case \DBAttDinamicoAtributo::TIPO_BOOLEAN:

          if ( !is_bool($valorAtributo) ) {
            throw new \InvalidArgumentException("Campo {$nomeAtributo} deve ser informado como Boolean.");
          }
          break;

        case \DBAttDinamicoAtributo::TIPO_DATE:
          if ( !getdate($valorAtributo) && $valorAtributo != '' ) {
            throw new \InvalidArgumentException("Campo {$nomeAtributo} deve ser informado como Data.");
          }
          break;

        case \DBAttDinamicoAtributo::TIPO_NUMERIC:

          if ( !\DBNumber::isFloat($valorAtributo) && $valorAtributo != '' ) {
            throw new \InvalidArgumentException("Campo {$nomeAtributo} deve ser informado como numérico.");
          }
          break;
        case \DBAttDinamicoAtributo::TIPO_INTEGER:

          if ( !\DBNumber::isInteger($valorAtributo) && $valorAtributo != '' ) {
            throw new \InvalidArgumentException("Campo {$nomeAtributo} deve ser informado como inteiro.");
          }
          break;
      }
    }
  }


  /**
   * Retorna a string dos atributos formatada para salvar nas observações do boleto
   * @param array $atributosDoRegime
   *
   * @return string
   */
  protected function getObservacaoAtributos(array $atributosDoRegime) {

    $sStringAtributos = '';
    foreach ($this->atributos as $atributo) {

      $dadosAtributo = $atributosDoRegime[$atributo->id];
      $valor = \DBAttDinamicoAtributo::formatarValor($dadosAtributo, $atributo->valor);
      $sStringAtributos .= "{$dadosAtributo->getDescricao()}: $valor\n";
    }
    if (!empty($sStringAtributos)) {
      $sStringAtributos = "\n{$sStringAtributos}";
    }
    return $sStringAtributos;
  }

}
