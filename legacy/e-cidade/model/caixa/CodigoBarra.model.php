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
class CodigoBarra {

  /**
   * Quantidade de dígitos para código de barras e linhas digitáveis para fatura e convênios.
   */
  const QUANTIDADE_DIGITOS_CODIGO_BARRA             = 44;
  const QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_FATURA   = 47;
  const QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_CONVENIO = 48;

  /**
   * Tipos de códigos de barra.
   */
  const TIPO_BARRA_FATURA   = 1;
  const TIPO_BARRA_CONVENIO = 2;

  /**
   * Valor do Código de barra.
   * @var string
   */
  private $sCodigoBarra;

  /**
   * Valor da linha digitável.
   * @var string
   */
  private $sLinhaDigitavel;

  /**
   * Código do banco, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iCodigoBanco;

  /**
   * Código do segmento, no caso de boleto do tipo convênio.
   * @var int
   */
  private $iCodigoSegmento;

  /**
   * Código da moeda do valor, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iCodigoMoeda;

  /**
   * Dígito verificador do código de barras.
   * @var int
   */
  private $iDigitoVerificador;

  /**
   * Fator para cálculo do vencimento, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iFatorVencimento;

  /**
   * Valor do pagamento do código de barras.
   * @var float
   */
  private $nValor;

  /**
   * Campo livre para utilização conforme padrão de cada banco.
   * @var string
   */
  private $sCampoLivre;

  /**
   * Código da identificação do tipo de valor, para boleto do tipo convênio.
   * @var int
   */
  private $iIdentificadorValor;


  /**
   * CodigoBarra constructor.
   *
   * @param string $sCodigoBarra Código de Barra ou Linha Digitável
   *
   * @throws ParameterException
   */
  public function __construct($sCodigoBarra) {

    $sCodigoBarra = preg_replace('/[^0-9]/', '', $sCodigoBarra);
    if ($this->codigoBarra($sCodigoBarra)) {

      $this->sCodigoBarra    = $sCodigoBarra;
      $this->sLinhaDigitavel = $this->geraLinhaDigitavel();
    }

    if ($this->linhaDigitavel($sCodigoBarra)) {

      $this->sCodigoBarra    = $this->gerarCodigoBarra($sCodigoBarra);
      $this->sLinhaDigitavel = $sCodigoBarra;
    }

    if (empty($this->sCodigoBarra) || empty($this->sLinhaDigitavel)) {
      throw new ParameterException("O valor informado não é um código de barras ou linha digitável válido.{$sCodigoBarra}");
    }

    $this->processaCodigoBarras();
  }

  /**
   * Verifica se o codigo passado e um codigo de barras.
   * @param string $sCodigoBarra
   *
   * @return bool
   */
  private function codigoBarra($sCodigoBarra) {
    return strlen($sCodigoBarra) == self::QUANTIDADE_DIGITOS_CODIGO_BARRA;
  }

  /**
   * Verifica se o codigo passado e uma linha digitavel.
   * @param string $sLinhaDigitavel
   *
   * @return bool
   */
  private function linhaDigitavel($sLinhaDigitavel) {

    return in_array(strlen($sLinhaDigitavel),
                    array(self::QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_CONVENIO,
                          self::QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_FATURA));
  }

  /**
   * @return string
   */
  public function getCodigoBarras() {
    return $this->sCodigoBarra;
  }

  /**
   * @return string
   */
  public function getLinhaDigitavel() {
    return $this->sLinhaDigitavel;
  }

  /**
   * Retorna o código do banco, caso o boleto seja fatura.
   * @return int
   */
  public function getCodigoBanco() {
    return $this->iCodigoBanco;
  }

  /**
   * Retorna o fator de vencimento encontrado, caso o boleto seja fatura.
   * @return int
   */
  public function getFatorVencimento() {
    return $this->iFatorVencimento;
  }

  /**
   * Retorna o digito verificador do codigo de barras.
   * @return int
   */
  public function getDigitoCodigoBarras() {
    return $this->iDigitoVerificador;
  }

  /**
   * Retorna o código da moeda utilizado, caso o boleto seja fatura.
   * @return int
   */
  public function getCodigoMoeda() {
    return $this->iCodigoMoeda;
  }

  /**
   * Retorna o valor a ser pago.
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @return string
   */
  public function getCampoLivre() {
    return $this->sCampoLivre;
  }

  /**
   * Processa as informações do código de barras, transformando-as em atributos.
   */
  private function processaCodigoBarras() {

    $this->iCodigoBanco        = $this->processaCodigoBanco();
    $this->iCodigoSegmento     = $this->processaCodigoSegmento();
    $this->iCodigoMoeda        = $this->processaCodigoMoeda();
    $this->iDigitoVerificador  = $this->processaDigitoVerificador();
    $this->iFatorVencimento    = $this->processaFatorVencimento();
    $this->iIdentificadorValor = $this->processaIdentificadorValor();
    $this->nValor              = $this->processaValor();
    $this->sCampoLivre         = $this->processaCampoLivre();
  }

  /**
   * Pega o código do banco do código de barras.
   * @return int
   */
  private function processaCodigoBanco() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 0, 3);
    }
    return null;
  }

  /**
   * Pega o código do segmento do código de barras.
   * @return int
   */
  private function processaCodigoSegmento() {

    if ($this->convenio()) {
      return (int) substr($this->getCodigoBarras(), 1, 1);
    }
    return null;
  }

  /**
   * Pega o código da moeda do código de barras.
   * @return int
   */
  private function processaCodigoMoeda() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 3, 1);
    }
    return null;
  }

  /**
   * Pega o dígito verificador do código de barras.
   * @return int
   */
  private function processaDigitoVerificador() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 4, 1);
    }
    return (int) substr($this->getCodigoBarras(), 3, 1);
  }

  /**
   * Pega o fator de vencimento do código de barras.
   * @return int
   */
  private function processaFatorVencimento() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 5, 4);
    }
    return null;
  }

  /**
   * Pega o identificador do valor real ou referência.
   * @return int
   */
  private function processaIdentificadorValor() {

    if ($this->convenio()) {
      return (int) substr($this->getCodigoBarras(), 2, 1);
    }
    return null;
  }

  /**
   * Pega o valor do código de barras.
   * @return string
   */
  private function processaValor() {

    if ($this->fatura()) {
      return ((int) substr($this->getCodigoBarras(), 9, 10)) / 100;
    }

    if ($this->convenio()) {

      if (in_array($this->iIdentificadorValor, array("6", "8"))) {
        return ((int) substr($this->getCodigoBarras(), 4, 11)) / 100;
      }
    }
    return null;
  }

  /**
   * Pega o campo livre do código de barras.
   * @return string
   */
  private function processaCampoLivre() {
    return substr($this->getCodigoBarras(), 19, 25);
  }

  /**
   * Verifica se o código de barras é do tipo fatura.
   * @return bool
   */
  public function fatura() {
    return $this->getTipoBarra() == self::TIPO_BARRA_FATURA;
  }

  /**
   * Verifica se o código de barras é do tipo convênio.
   * @return bool
   */
  public function convenio() {
    return $this->getTipoBarra() == self::TIPO_BARRA_CONVENIO;
  }

  /**
   * Verifica qual tipo de código de barras deve gerar, de acordo com a linha digitável informada.
   * @param string $sCodigo Linha digitável ou código de barras.
   *
   * @return int
   */
  private function getTipoBarra($sCodigo = null) {

    if (empty($sCodigo)) {
      $sCodigo = $this->getCodigoBarras();
    }
    return substr($sCodigo, 0, 1) == '8' ? self::TIPO_BARRA_CONVENIO : self::TIPO_BARRA_FATURA;
  }

  /**
   * Gera um código de barra de acordo com a linha digitável informada.
   * @param $sLinhaDigitavel string Valor da linha digitável.
   *
   * @return string
   */
  private function gerarCodigoBarra($sLinhaDigitavel) {

    $sCodigoBarra = null;
    switch ($this->getTipoBarra($sLinhaDigitavel)) {
      
      case self::TIPO_BARRA_FATURA:
        $sCodigoBarra = $this->gerarCodigoBarraFatura($sLinhaDigitavel);
        break;

      case self::TIPO_BARRA_CONVENIO:
        $sCodigoBarra = $this->gerarCodigoBarraConvenio($sLinhaDigitavel);
        break;
    
    }
    return $sCodigoBarra;
  }

  /**
   * Gera uma linha digitável de acordo com o código de barras informado.
   *
   * @return string
   */
  private function geraLinhaDigitavel() {

    $sLinhaDigitavel = null;
    switch ($this->getTipoBarra()) {

      case self::TIPO_BARRA_FATURA:
        $sLinhaDigitavel = $this->geraLinhaDigitavelFatura();
        break;

      case self::TIPO_BARRA_CONVENIO:
        $sLinhaDigitavel = $this->geraLinhaDigitavelConvenio();
        break;
    }

    return $sLinhaDigitavel;
  }

  /**
   * Gera o código de barra para um convênio de acordo com a linha digitável.
   * @param string $sLinhaDigitavel Linha digitável
   *
   * @return string Código de barra.
   */
  private function gerarCodigoBarraConvenio($sLinhaDigitavel) {

    $aCodigoBarra = array();
    $aCodigoBarra[0] = substr($sLinhaDigitavel, 0, 11);
    $aCodigoBarra[1] = substr($sLinhaDigitavel, 12, 11);
    $aCodigoBarra[2] = substr($sLinhaDigitavel, 24, 11);
    $aCodigoBarra[3] = substr($sLinhaDigitavel, 36, 11);

    return implode('', $aCodigoBarra);
  }

  /**
   * Gera o código de barras para a fatura de acordo com a linha digitável.
   * @param string $sLinhaDigitavel Linha digitável.
   *
   * @return string Código de barras.
   */
  private function gerarCodigoBarraFatura($sLinhaDigitavel) {

    $aCodigoBarra = array();
    $aCodigoBarra[0] = substr($sLinhaDigitavel, 0, 4);
    $aCodigoBarra[1] = substr($sLinhaDigitavel, 32, 15);
    $aCodigoBarra[2] = substr($sLinhaDigitavel, 4, 5);
    $aCodigoBarra[3] = substr($sLinhaDigitavel, 10, 10);
    $aCodigoBarra[3] = substr($sLinhaDigitavel, 21, 10);

    return implode('', $aCodigoBarra);
  }

  /**
   * Gera a linha digitável para uma fatura a partir do seu código de barras.
   *
   * @return string
   */
  private function geraLinhaDigitavelFatura() {

    $sCodigoBarra = $this->getCodigoBarras();

    $sCampos1 = substr($sCodigoBarra, 0, 4)  . substr($sCodigoBarra, 19, 1) . '.' . substr($sCodigoBarra, 20, 4);
    $sCampos2 = substr($sCodigoBarra, 24, 5) . '.' . substr($sCodigoBarra, 24 + 5, 5);
    $sCampos3 = substr($sCodigoBarra, 34, 5) . '.' . substr($sCodigoBarra, 34 + 5, 5);
    $sCampos4 = substr($sCodigoBarra, 4, 1);
    $sCampos5 = substr($sCodigoBarra, 5, 14);

    if (empty($sCampos5)) {
      $sCampos5 = '000';
    }

    $sLinha = $sCampos1 . $this->modulo($sCampos1) . ' ' .
              $sCampos2 . $this->modulo($sCampos2) . ' ' .
              $sCampos3 . $this->modulo($sCampos3) . ' ' .
              $sCampos4 . ' ' . $sCampos5;

    return $sLinha;
  }

  /**
   * Gera a linha digitável para o convênio de acordo com o código de barras.
   *
   * @return string Linha digitável gerada.
   * @throws Exception
   */
  private function geraLinhaDigitavelConvenio() {

    $sCodigoBarra = $this->getCodigoBarras();
    $aBlocos      = array(
      substr($sCodigoBarra, 0,  11),
      substr($sCodigoBarra, 11, 11),
      substr($sCodigoBarra, 22, 11),
      substr($sCodigoBarra, 33, 11)
    );

    for ($i = 0; $i < count($aBlocos); $i++) {

      $sBloco      = substr($aBlocos[$i], 0, 6);
      $sBloco     .= ".";
      $sBloco     .= substr($aBlocos[$i], 6, 5);
      $sBloco     .= $this->modulo($aBlocos[$i]);
      $aBlocos[$i]  = $sBloco;
     }

     return implode($aBlocos, " ");
  }


  /**
   * Aplica o módulo de acordo com o código de barras.
   * @param string $sDigitos Digitos para geração do código validador
   *
   * @return int Código validador.
   * @throws Exception
   */
  private function modulo($sDigitos) {

    $sIdentificadorValor = substr($this->sCodigoBarra, 2, 1);

    if ($this->fatura()) {
      return self::modulo10($sDigitos);
    }

    switch ($sIdentificadorValor) {

      case '6':
      case '7':
        return self::modulo10($sDigitos);
        break;

      case '8':
      case '9':
        return self::modulo11($sDigitos);
        break;

      default:
        throw new Exception("O código informado é inválido.");
        break;
    }
  }

  /**
   * Módulo 10 para validação e geração da linha digitável.
   * @param string $sNumero Parte do código de barras
   *
   * @return int Dígito verifivador gerado.
   */
  public static function modulo10($sNumero) {

    $iSoma   = 0;
    $iPeso   = 2;

    for ($iContador = (strlen($sNumero) - 1); $iContador >= 0; $iContador--) {

      $iMultiplicacao = (int) (substr($sNumero, $iContador, 1) * $iPeso);
      if ($iMultiplicacao >= 10) {
        $iMultiplicacao = 1 + ($iMultiplicacao - 10);
      }
      $iSoma = $iSoma + $iMultiplicacao;

      if ($iPeso == 2) {
        $iPeso = 1;
      } else {
        $iPeso = 2;
      }
    }

    $iDigito = 10 - ($iSoma % 10);

    if ($iDigito == 10) {
      $iDigito = 0;
    }
   return $iDigito;
  }

  /**
   * Módulo 11 para validação e geração da linha digitável.
   * @param string  $sNumero sNumero Parte do código de barras
   *
   * @return int Dígito verifivador gerado.
   */
  public static function modulo11($sNumero) {

    $iSoma     = 0;
    $iPeso     = 2;
    $iBase     = 9;
    $iContador = strlen($sNumero) - 1;

    for ($iPosicao = $iContador; $iPosicao >= 0; $iPosicao--) {

      $iSoma = $iSoma + ((int) substr($sNumero, $iPosicao, 1) * $iPeso);
      if ($iPeso < $iBase) {
        $iPeso++;
      } else {
        $iPeso = 2;
      }
    }

    $nDigito = 11 - ($iSoma % 11);
    if ($nDigito > 9) {
     $nDigito = 0;
    }

    /* Utilizar o dígito 1 sempre que o resultado do cálculo padrão for igual a 0, 1 ou 10. */
    if ($nDigito == 0) {
      $nDigito = 1;
    }
    return $nDigito;
  }
}