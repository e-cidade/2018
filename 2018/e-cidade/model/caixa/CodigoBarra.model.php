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
   * Quantidade de d�gitos para c�digo de barras e linhas digit�veis para fatura e conv�nios.
   */
  const QUANTIDADE_DIGITOS_CODIGO_BARRA             = 44;
  const QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_FATURA   = 47;
  const QUANTIDADE_DIGITOS_LINHA_DIGITAVEL_CONVENIO = 48;

  /**
   * Tipos de c�digos de barra.
   */
  const TIPO_BARRA_FATURA   = 1;
  const TIPO_BARRA_CONVENIO = 2;

  /**
   * Valor do C�digo de barra.
   * @var string
   */
  private $sCodigoBarra;

  /**
   * Valor da linha digit�vel.
   * @var string
   */
  private $sLinhaDigitavel;

  /**
   * C�digo do banco, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iCodigoBanco;

  /**
   * C�digo do segmento, no caso de boleto do tipo conv�nio.
   * @var int
   */
  private $iCodigoSegmento;

  /**
   * C�digo da moeda do valor, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iCodigoMoeda;

  /**
   * D�gito verificador do c�digo de barras.
   * @var int
   */
  private $iDigitoVerificador;

  /**
   * Fator para c�lculo do vencimento, no caso de boleto do tipo fatura.
   * @var int
   */
  private $iFatorVencimento;

  /**
   * Valor do pagamento do c�digo de barras.
   * @var float
   */
  private $nValor;

  /**
   * Campo livre para utiliza��o conforme padr�o de cada banco.
   * @var string
   */
  private $sCampoLivre;

  /**
   * C�digo da identifica��o do tipo de valor, para boleto do tipo conv�nio.
   * @var int
   */
  private $iIdentificadorValor;


  /**
   * CodigoBarra constructor.
   *
   * @param string $sCodigoBarra C�digo de Barra ou Linha Digit�vel
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
      throw new ParameterException("O valor informado n�o � um c�digo de barras ou linha digit�vel v�lido.{$sCodigoBarra}");
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
   * Retorna o c�digo do banco, caso o boleto seja fatura.
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
   * Retorna o c�digo da moeda utilizado, caso o boleto seja fatura.
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
   * Processa as informa��es do c�digo de barras, transformando-as em atributos.
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
   * Pega o c�digo do banco do c�digo de barras.
   * @return int
   */
  private function processaCodigoBanco() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 0, 3);
    }
    return null;
  }

  /**
   * Pega o c�digo do segmento do c�digo de barras.
   * @return int
   */
  private function processaCodigoSegmento() {

    if ($this->convenio()) {
      return (int) substr($this->getCodigoBarras(), 1, 1);
    }
    return null;
  }

  /**
   * Pega o c�digo da moeda do c�digo de barras.
   * @return int
   */
  private function processaCodigoMoeda() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 3, 1);
    }
    return null;
  }

  /**
   * Pega o d�gito verificador do c�digo de barras.
   * @return int
   */
  private function processaDigitoVerificador() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 4, 1);
    }
    return (int) substr($this->getCodigoBarras(), 3, 1);
  }

  /**
   * Pega o fator de vencimento do c�digo de barras.
   * @return int
   */
  private function processaFatorVencimento() {

    if ($this->fatura()) {
      return (int) substr($this->getCodigoBarras(), 5, 4);
    }
    return null;
  }

  /**
   * Pega o identificador do valor real ou refer�ncia.
   * @return int
   */
  private function processaIdentificadorValor() {

    if ($this->convenio()) {
      return (int) substr($this->getCodigoBarras(), 2, 1);
    }
    return null;
  }

  /**
   * Pega o valor do c�digo de barras.
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
   * Pega o campo livre do c�digo de barras.
   * @return string
   */
  private function processaCampoLivre() {
    return substr($this->getCodigoBarras(), 19, 25);
  }

  /**
   * Verifica se o c�digo de barras � do tipo fatura.
   * @return bool
   */
  public function fatura() {
    return $this->getTipoBarra() == self::TIPO_BARRA_FATURA;
  }

  /**
   * Verifica se o c�digo de barras � do tipo conv�nio.
   * @return bool
   */
  public function convenio() {
    return $this->getTipoBarra() == self::TIPO_BARRA_CONVENIO;
  }

  /**
   * Verifica qual tipo de c�digo de barras deve gerar, de acordo com a linha digit�vel informada.
   * @param string $sCodigo Linha digit�vel ou c�digo de barras.
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
   * Gera um c�digo de barra de acordo com a linha digit�vel informada.
   * @param $sLinhaDigitavel string Valor da linha digit�vel.
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
   * Gera uma linha digit�vel de acordo com o c�digo de barras informado.
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
   * Gera o c�digo de barra para um conv�nio de acordo com a linha digit�vel.
   * @param string $sLinhaDigitavel Linha digit�vel
   *
   * @return string C�digo de barra.
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
   * Gera o c�digo de barras para a fatura de acordo com a linha digit�vel.
   * @param string $sLinhaDigitavel Linha digit�vel.
   *
   * @return string C�digo de barras.
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
   * Gera a linha digit�vel para uma fatura a partir do seu c�digo de barras.
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
   * Gera a linha digit�vel para o conv�nio de acordo com o c�digo de barras.
   *
   * @return string Linha digit�vel gerada.
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
   * Aplica o m�dulo de acordo com o c�digo de barras.
   * @param string $sDigitos Digitos para gera��o do c�digo validador
   *
   * @return int C�digo validador.
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
        throw new Exception("O c�digo informado � inv�lido.");
        break;
    }
  }

  /**
   * M�dulo 10 para valida��o e gera��o da linha digit�vel.
   * @param string $sNumero Parte do c�digo de barras
   *
   * @return int D�gito verifivador gerado.
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
   * M�dulo 11 para valida��o e gera��o da linha digit�vel.
   * @param string  $sNumero sNumero Parte do c�digo de barras
   *
   * @return int D�gito verifivador gerado.
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

    /* Utilizar o d�gito 1 sempre que o resultado do c�lculo padr�o for igual a 0, 1 ou 10. */
    if ($nDigito == 0) {
      $nDigito = 1;
    }
    return $nDigito;
  }
}