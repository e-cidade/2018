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
 * Classe de manipulação de String
 *
 * @package std
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo <renan@dbseller.com.br>
 */
abstract class DBString {

  /**
   * Verica se o tamanho total do nome é no minimo de 2 caracteres
   */
  const NOME_REGRA_1 = 1;
  /**
   * Verifica se a string de nome é composta por nome e sobrenome e cada um deles tem no minimo 2 caracteres
   */
  const NOME_REGRA_2 = 2;
  /**
   * Verifica se a string nome é composta por nome e sobrenome
   * @var unknown_type
   */
  const NOME_REGRA_3 = 3;
  /**
   * Verifica se a string nome contem quatro letras repetidas em sequencia
   */
  const NOME_REGRA_4 = 4;
  /**
   * Verifica se nome esta ascentuado, possui números ou caractéres especiais
   */
  const NOME_REGRA_5 = 5;

  /**
   * Quebra uma string passando por parâmetro o número máximo de caracteres retornando um array
   *
   * @param string  $sString
   * @param integer $iNroMaxCaract
   * @param string  $sValorCompl
   * @return array
   */
  public static function quebrarLinha( $sString, $iNroMaxCaract, $sValorCompl=' ') {

    $aPalavras      = explode(' ',$sString);
    $iTamanhoTotal  = 0;
    $iIndLinha      = 0;
    $aRetornoString = array();

    foreach ( $aPalavras as $iInd => $sPalavra ) {

      $iTamanhoPalavra = strlen($sPalavra);

      if ( isset($aRetornoString[$iIndLinha]) ) {
        $iTamanhoPalavra += 1;
      }

      $iTamanhoTotal  += $iTamanhoPalavra;

      if ( $iTamanhoTotal <= $iNroMaxCaract  ) {
        if ( isset($aRetornoString[$iIndLinha]) ) {
          $aRetornoString[$iIndLinha].= ' '.$sPalavra;
        } else {
          $aRetornoString[$iIndLinha] = $sPalavra;
        }
      } else {
        $aRetornoString[$iIndLinha]   .= str_repeat($sValorCompl,$iNroMaxCaract-(strlen($aRetornoString[$iIndLinha])));
        $aRetornoString[++$iIndLinha]  = $sPalavra;
        $iTamanhoTotal                 = ($iTamanhoPalavra-1);
      }

    }

    $aRetornoString[$iIndLinha] .= str_repeat($sValorCompl,$iNroMaxCaract-(strlen($aRetornoString[$iIndLinha])));

    return $aRetornoString;
  }

  /**
   * Recebe a String e valida seu tamanho com o tamanho informado
   * @param string $sString
   * @param integer $iTamanho
   * @return boolean
   */
  public static function validarTamanho($sString, $iTamanho) {
    return (strlen($sString) == $iTamanho);
  }

  /**
   * Recebe um PIS e válido.
   *
   * @example DBString::isPIS("20097809998");
   * @param   String  $sPis
   * @return  boolean
   */
  public static function isPIS($sPIS) {

    /**
     * Valida se foi informado o PIS
     */

    if (empty($sPIS)){
      return false;
    }

    /**
     * Valida se o pis possui 11 Digitos
     */
    if ( !DBString::validarTamanho( $sPIS, 11 ) ) {
      return false;
    }

    /**
     * Verifica se o pis possui valores válidos
     */
    if ($sPIS == "00000000000"){
      return false;
    }

    /**
     * Verifica se o valor informado é inteiro
     */
    if(!(DBNumber::isInteger($sPIS))){
      return false;
    }

    /**
     * Multiplica os 10 primeiros algarismos do PIS pelos respectivos pesos [3,2,9,8,7,6,5,4,3,2],
     * somando o produto obtido a partir da multiplicação na variavel $iSoma.
     */
    $iSoma  = $sPIS[0] * 3;
    $iSoma += $sPIS[1] * 2;
    $iSoma += $sPIS[2] * 9;
    $iSoma += $sPIS[3] * 8;
    $iSoma += $sPIS[4] * 7;
    $iSoma += $sPIS[5] * 6;
    $iSoma += $sPIS[6] * 5;
    $iSoma += $sPIS[7] * 4;
    $iSoma += $sPIS[8] * 3;
    $iSoma += $sPIS[9] * 2;

    /**
     * Verifica o resto da divisão da Soma a cima por 11.
     * Subtrai o Total do respo da divisão.
     */
    $iResto = $iSoma % 11;
    $iTotal = 11 - $iResto;


    /**
     * Se $iTotal for igual a 10 ou 11 o digito verificador será 11,
     * caso contratio o digito verificador será o valor de $iTotal
     */
    if ($iTotal == 10 || $iTotal == 11){
      $iDigito = 0;
    } else {
      $iDigito = $iTotal;
    }

    /**
     * Verifica se o digito verificador é válido.
     */
    if ($sPIS[10] == $iDigito) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Valida se a String passada é válida como CPF
   * @param String $sCpf
   * @return Boolean
   */
  public static function isCPF($sCpf) {

    $aBlackList = array(str_repeat("0",11),
      str_repeat("1",11),
      str_repeat("2",11),
      str_repeat("3",11),
      str_repeat("4",11),
      str_repeat("5",11),
      str_repeat("6",11),
      str_repeat("7",11),
      str_repeat("8",11),
      str_repeat("9",11));
    /**
     * Validamos se a String tem o tamanho correto
     */
    if ( !DBString::validarTamanho( $sCpf, 11 ) ) {
      return false;
    }

    /**
     * validamos se o $sCpf esta na BlackList
     */
    if ( in_array($sCpf, $aBlackList) ) {
      return false;
    }

    $sCPFBase            = substr($sCpf, 0, 9);
    $iPosicaoCPFBase     = 0;
    $nTotalCalculoDigito1= 0;

    for ( $iPosicaoCalculada = strlen($sCPFBase)+1; $iPosicaoCalculada > 1; $iPosicaoCalculada-- ) {

      $nTotalCalculoDigito1 += $iPosicaoCalculada * $sCPFBase[$iPosicaoCPFBase];
      $iPosicaoCPFBase++;
    }

    /**
     * A Base para O primeiro digito do CPF é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto1   = $nTotalCalculoDigito1 % 11;
    $iDigito1 = ($iResto1 < 2) ? 0 : (11 - $iResto1);

    /**
     * Concatenamos o digito encontrado ao fim do CPF
     */
    $sCPFBase           .= $iDigito1;
    $iPosicaoCPFBase     = 0;
    $nTotalCalculoDigito2= 0;

    for ( $iPosicaoCalculada = strlen($sCPFBase)+1; $iPosicaoCalculada > 1; $iPosicaoCalculada-- ) {

      $nTotalCalculoDigito2 += $iPosicaoCalculada * $sCPFBase[$iPosicaoCPFBase];
      $iPosicaoCPFBase++;
    }

    /**
     * A base para o segundo digito do cpf é formado pelo resto da divisao da soma efetuada a cima
     * se o resto for maior que2 o digito será 0, caso contrario o digito sera 11 menos o resto
     *
     */
    $iResto2  = $nTotalCalculoDigito2 % 11;
    $iDigito2 = ($iResto2 < 2) ? 0 : (11 - $iResto2);
    $sCPFBase.= $iDigito2;

    return ($sCPFBase == $sCpf);
  }

  /**
   * VErifica se a String passada é vallida como CNPJ
   * @param string $sCNPJ
   * @return boolean
   */
  public static function isCNPJ( $sCNPJ ) {

    $aBlackList = array(
      str_repeat("0",14),
      str_repeat("1",14),
      str_repeat("2",14),
      str_repeat("3",14),
      str_repeat("4",14),
      str_repeat("5",14),
      str_repeat("6",14),
      str_repeat("7",14),
      str_repeat("8",14),
      str_repeat("9",14)
    );
    /**
     * Validamos se a String tem o tamanho correto
     */
    if ( !DBString::validarTamanho($sCNPJ, 14) ) {
      return false;
    }

    /**
     * validamos se o $sCpf esta na BlackList
     */
    if ( in_array($sCNPJ, $aBlackList) ) {
      return false;
    }

    $sBaseCalculo1 = "543298765432";
    $sBaseCalculo2 = "6543298765432";
    $sBaseCNPJ     = substr($sCNPJ, 0, 12);
    $nTotalBase1   = 0;

    for ( $iPosicaoCNPJ = 0; $iPosicaoCNPJ < strlen($sBaseCNPJ); $iPosicaoCNPJ++ ) {
      $nTotalBase1 +=  $sBaseCalculo1[$iPosicaoCNPJ] * $sBaseCNPJ[$iPosicaoCNPJ];
    }

    /**
     * A Base para O primeiro digito do CNPJ é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto1  = $nTotalBase1 % 11;
    $iDigito1 = ($iResto1 < 2) ? 0 : (11 - $iResto1);

    /**
     * Concatenamos o digito encontrado ao fim do CNPJ
     */
    $sBaseCNPJ  .= $iDigito1;
    $nTotalBase2 = 0;

    for ( $iPosicaoCNPJ = 0; $iPosicaoCNPJ < strlen($sBaseCNPJ); $iPosicaoCNPJ++ ) {
      $nTotalBase2 +=  $sBaseCalculo2[$iPosicaoCNPJ] * $sBaseCNPJ[$iPosicaoCNPJ];
    }

    /**
     * A Base para o segundo digito do CNPJ é formado pelo resto da divisao soma efetuada a cima
     * Se o resto for maior que 2 o digito sera 0, caso contrario o digito será 11 menos o resto
     */
    $iResto2  = $nTotalBase2 % 11;
    $iDigito2 = ($iResto2 < 2) ? 0 : (11 - $iResto2);

    /**
     * Concatenamos o digito encontrado ao fim do CNPJ
     */
    $sBaseCNPJ .= $iDigito2;

    return ($sBaseCNPJ == $sCNPJ);
  }

  /**
   * Verifica se a string é válida como email
   * @param boolean $sEmail
   */
  public static function isEmail($sEmail){

    /**
     * verifica se possui cacteres validos para um email
     */
    $sEmail = strtolower($sEmail);
    $sRegex = '/^([0-9a-z\.\-_])+@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
    return preg_match($sRegex, $sEmail) ? true : false;
  }

  public static function isNomeValido($sNome, $iRegra){

    $aComposicaoNome = explode(' ', $sNome);

    switch ( $iRegra ) {
      /**
       * Verifica se o nome tem no minimo 2 caracteres
       */
    case DBString::NOME_REGRA_1:
      return DBString::validarTamanhoMinimo($sNome, 2);
      break;

      /**
       *  Verifica se o nome é composto pelo menos por 1 espaco, ou seja, nome e sobrenome
       */
    case DBString::NOME_REGRA_2:

      if ( !DBString::isNomeValido($sNome, DBString::NOME_REGRA_3) ) {
        return false;
      }
      if ( strlen($aComposicaoNome[0]) < 2 || strlen($aComposicaoNome[1]) < 1 ) {
        return false;
      }

      break;

      /**
       *  Verifica se o nome é composto(nome e sobrenome)
       */
    case DBString::NOME_REGRA_3:

      $aComposicaoNome = explode(' ', $sNome);

      if ( count($aComposicaoNome) < 2 ) {
        return false;
      }

      break;

      /**
       *  Verifica se o nome possui mais de 4 letras repetidas em sequencia
       */
    case DBString::NOME_REGRA_4:

      $sExpressao = '/([a-zA-ZÀ-ÿ\s])\1{3}/';
      return preg_match( $sExpressao, $sNome ) ? false : true;

      break;

      /**
       *  Verifica se nome esta ascentuado, possui números ou caractéres especiais
       */
    case DBString::NOME_REGRA_5:

      // $sExpressao = '/^([a-zA-Z\s]+)$/';
      $sExpressao = '/[À-ÿ0-9.]/';
      return preg_match($sExpressao, $sNome) ? false : true;

      break;

      /**
       * Verifica nome e sobrenome, se possui ao menos 2 caractéres em cada nome, se possui quatro letras repetidas
       * em sequencia e se possui caractéres especiais
       */
    case DBString::NOME_REGRA_6:


      break;

    default:
      throw new Exception("Regra Inválida");
      break;
    }

    return true;
  }

  /**
   * Verifica se a String informada possui o tamanho minimo solicitado
   * @param stringnknown_type $sSting
   * @param integer $iTamanhoMinimo
   * @return boolean
   */
  public static function validarTamanhoMinimo($sString, $iTamanhoMinimo) {

    return (strlen($sString) >= $iTamanhoMinimo);
  }

  /**
   * Verifica se a string não ultrapassa o tamanho máximo solicitado.
   *
   * @access public
   * @param String $sString
   * @param Integer $iTamanhoMaximo
   * @return Boolean
   */
  public static function validarTamanhoMaximo($sString, $iTamanhoMaximo) {

    return (strlen($sString) <= $iTamanhoMaximo);
  }

  /**
   * Verifica se a string possui somente números.
   *
   * @access public
   * @param String $sPalavra
   * @return Boolean
   */
  public static function isSomenteNumero($sPalavra) {

    $sRegex = '/^[0-9]+$/';
    return preg_match($sRegex, $sPalavra) ? true : false;
  }

  public static function isSomenteLetras($sPalavra, $lLetrasAcentuadas = false) {

    $sRegex = '/^[a-zA-Z\s]+$/';
    if ($lLetrasAcentuadas ) {
      $sRegex = '/^[a-zA-ZÀ-ÿ\s]+$/';
    }
    return preg_match($sRegex, $sPalavra) ? true : false;
  }

  /**
   * Valida se a string é somente alfanumerica
   * @param string $sPalavra
   * @param boolean $lAceitaBarra
   * @param boolean $lAceitaHifen
   * @param boolean $lAceitaPonto
   * @return boolean
   */
  public static function isSomenteAlfanumerico($sPalavra, $lAceitaBarra=true, $lAceitaHifen=false, $lAceitaPonto=false) {

    $sBarra = '';
    if($lAceitaBarra){
      $sBarra = '\/';
    }

    $sHifen = '';
    if($lAceitaHifen){
      $sHifen = '\-';
    }

    $sPonto = '';
    if($lAceitaPonto){
      $sPonto = '\.';
    }

    $sRegex = "/^[a-zA-Z0-9$sPonto$sHifen$sBarra\s]+$/";

    return preg_match($sRegex, $sPalavra) ? true : false;
  }

  /**
   * Remove acentuação na string passada
   *
   * @param String $sString - String acentuada
   * @static
   * @access public
   * @return String - String com os acentos removidos
   */
  public static function removerAcentuacao($sString) {

    $sString = preg_replace("/[ÁÀÂÃ]/", "A", $sString);
    $sString = preg_replace("/[áàâãª]/","a", $sString);

    $sString = preg_replace("/[ÉÈÊ]/",  "E", $sString);
    $sString = preg_replace("/[éèê]/",  "e", $sString);

    $sString = preg_replace("/[ÓÒÔÕ]/", "O", $sString);
    $sString = preg_replace("/[óòôõº]/","o", $sString);

    $sString = preg_replace("/[ÚÙÛ]/",  "U", $sString);
    $sString = preg_replace("/[úùû]/",  "u", $sString);

    $sString = preg_replace("/[ÍÌÎ]/",  "I", $sString);
    $sString = preg_replace("/[íìî]/",  "i", $sString);

    $sString = preg_replace("/Ç/", "C", $sString);
    $sString = preg_replace("/ç/", "c", $sString);

    return $sString;
  }

  /**
   * Remove os caracteres especiais da String
   *
   * @param String $sString
   * @static
   * @access public
   * @return String - Retorna a string sem caracteres especiais
   */
  public static function removerCaracteresEspeciais($sString) {
    preg_match_all("/[a-zA-ZÀ-ÿ0-9\s]/i", $sString, $aOcorrencias);
    return implode("", $aOcorrencias[0]);
  }

  /**
   * Recebe uma string e se ela for maior que o limite informado, corta o final
   * e substitui pelo sufixo informado
   * Se o limite for 0 ou menor que 0, retorna vazio
   * @param  string  $sString
   * @param  integer $iLimite
   * @param  string  $sSufixo
   * @return string
   */
  public static function retornaStringLimitada($sString, $iLimite = 0, $sSufixo = '...') {
    return ( $iLimite > 0 ? mb_strimwidth($sString, 0, $iLimite, $sSufixo) : '' );
  }

  /**
   * Retorna o Primeiro e Ultimo nome
   * Verifica se o ultimo nome possui prefixo da, de, dos, etc.
   * @param  string $sString
   * @param  string $sDelimitador
   * @return string
   */
  public static function retornaNomeReduzido($sString, $sDelimitador = ' ') {

    $aString        = array_values( explode($sDelimitador,$sString) );
    $sPrimeiroNome  = $aString[0];
    $sPref          = "";

    if ( $aString[count($aString)-2] != $sPrimeiroNome &&
      (strlen($aString[count($aString)-2]) == 2 ||
      strlen($aString[count($aString)-2]) == 3 )) {

        $sPref = $aString[count($aString)-2];
      }

    $sUltimoNome    = array_pop($aString);
    return "{$sPrimeiroNome} {$sPref} {$sUltimoNome}";
  }

  /**
   * Abrevia somente o(s) nome(s) do meio
   * @param  string  $sNome               Nome que será abreviado
   * @param  boolean $lRemoverPreposicoes Se deve remover as preposições
   * @return string                       Nome abreviado
   */
  public static function abreviaSobrenome($sNome, $lRemoverPreposicoes = true) {

    $aConectivos = array('do', 'da', 'de', 'dos', 'das', 'des');

    $aNome  = explode(" ", $sNome);
    $iNomes = count($aNome);

    if ($iNomes == 2 ) {
      return $sNome;
    }

    $aNovoNome   = array();
    $aNovoNome[] = array_shift($aNome);
    $sUltimoNome = array_pop($aNome);

    foreach ($aNome as $sValue) {

      if ( in_array(mb_strtolower($sValue), $aConectivos) && $lRemoverPreposicoes ) {
        continue;
      } else if (in_array(mb_strtolower($sValue), $aConectivos)) {
        $aNovoNome[] = $sValue;
      } else {
        $aNovoNome[] = substr($sValue, 0, 1). ".";
      }
    }

    $aNovoNome[] = $sUltimoNome;
    return implode(" ", $aNovoNome);
  }

  /**
   * Adiciona mascara de telefone em uma string numérica
   * @param  string $sNumero Número sem formatação
   * @return string          Número com mascara
   */
  public static function formatarTelefone($sNumero) {


    if ( strlen($sNumero) > 10 ) {
      return preg_replace('/(\d{2})(\d{5})(\d*)/', '($1) $2-$3', $sNumero);
    }

    return preg_replace('/(\d{2})(\d{4})(\d*)/', '($1) $2-$3', $sNumero);
  }


  /**
   * Alterada função de tracrever valores do agata para transcrever um número
   * @param  integer $iNumero    Número a ser transcrito
   * @param  boolean $lMaiusculo se deve retornar em maiusculo
   * @return string              número transcrito
   */
  static public function numeroPorExtenso($iNumero, $lMaiusculo = false) {

    $nValor = ereg_replace(",", "\.", $iNumero);

    $zeros = '000.000.000,00';
    $nValor = number_format($nValor,2);
    $nValor = substr($zeros,0,strlen($zeros)-strlen($nValor)) . $nValor;

    $sMilhao  = self::transcreveNumero(substr($nValor,0,3));
    $sMilhao .= ( (substr($nValor,0,3) > 1) ? 'milhões' : '' );
    $sMilhar  = self::transcreveNumero(substr($nValor,4,3));

    if (trim($sMilhar) == "um") {
      $sMilhar = 'mil';
    } else {
      $sMilhar .= ( (substr($nValor,4,3) > 0) ? 'mil' : '' );
    }

    $sUnidades  = self::transcreveNumero(substr($nValor,8,3));
    if (trim($sMilhar) == "mil" && (strlen(trim($sMilhar))<>0 && strlen(trim($sUnidades))<>0) ) {
      $sMilhar .=" e ";
    } elseif (strlen(trim($sMilhar)) <> 0 && strlen(trim($sUnidades)) <> 0) {

      /**
       * verifica se a unidade se trata de uma centena ou uma dezena
       */
      $sMilhar .=", ";
      if (strlen( (int) substr($nValor,8,3)) <= 2) {
        $sMilhar .=" e ";
      }
    } else {
      $sMilhar .="";
    }

    $sRetorno = $sMilhao . ((strlen(trim($sMilhao))<>0 && strlen(trim($sMilhar))<>0) ? ', ' : '') .
      $sMilhar .
      $sUnidades ;

    if ($iNumero === 0) {
      $sRetorno = "zero";
    }

    if ( $lMaiusculo ) {
      return mb_strtoupper( trim($sRetorno) );
    }
    return trim($sRetorno);
  }

  static private function transcreveNumero($nValor) {

    $aUnidade  = array('','um ','dois ','três ','quatro ','cinco ','seis ','sete ','oito ','nove ');
    $aDezenas  = array('',' ','vinte ','trinta ','quarenta ', 'cinquenta ', 'sessenta ', 'setenta ','oitenta ','noventa ');
    $aCentenas = array('','cento ','duzentos ','trezentos ','quatrocentos ','quinhentos ','seiscentos ','setecentos ','oitocentos ','novecentos ');
    $aExcessao = array('dez ', 'onze ', 'doze ', 'treze ', 'quatorze ', 'quinze ', 'desesseis ', 'desessete ', 'dezoito ', 'desenove ');

    $nPosicao1 = substr($nValor,0,1);
    $nPosicao2 = substr($nValor,1,1);
    $nPosicao3 = substr($nValor,2,1);

    $sCentena  = $aCentenas[($nPosicao1)];
    $sDezena   = $aDezenas[($nPosicao2)];
    $sUnidade  = $aUnidade[($nPosicao3)];

    if (substr($nValor,0,3) == '100')
    { $sCentena = 'cem '; }

    if (substr($nValor,1,1) == '1') {
      $sDezena = $aExcessao[$nPosicao3];
      $sUnidade = '';
    }

    $aResultado = array();
    if (!empty($sCentena)) {
      $aResultado[] = $sCentena;
    }
    if (!empty($sDezena)) {
      $aResultado[] = $sDezena;
    }
    if (!empty($sUnidade)) {
      $aResultado[] = $sUnidade;
    }

    $sResultado = implode(" e ", $aResultado);
    $sResultado = substr($sResultado,0, strlen($sResultado));
    return $sResultado;
  }

  public static function formatStringRecursive($entrada, \Closure $callback) {

    switch(getType($entrada)) {

      case "boolean":
      case "integer":
      case "double":
        return $entrada;
        break;

      case "string":
        $entrada = $callback($entrada);
        break;
      case "array":

        foreach ($entrada as $chave => $valor) {
          $entrada[$chave] = self::formatStringRecursive($valor, $callback);
        }
        break;

      case "object":

        foreach ($entrada as $chave => $valor) {
          $entrada->{$chave} = self::formatStringRecursive($valor, $callback);
        }
        break;

      case "NULL":
        return null;
      case "resource":
      case "unknown type":
        return "";
        break;
    }
    return $entrada;
  }

  /**
   * Codifica o objeto passado recursivamente(*se necessário)
   *
   * @param string mixed $entrada
   */
  public static function utf8_encode_all($entrada) {

    return \DBString::formatStringRecursive($entrada, function($string) {

      if (!db_utils::isUTF8($string)) {
       $string = utf8_encode($string);
      }
      return $string;
    });
  }

  /**
   * Codifica o objeto passado recursivamente(*se necessário)
   *
   * @param string mixed $entrada
   */
  public static function utf8_decode_all($entrada) {

    return \DBString::formatStringRecursive($entrada, function($string) {

      if (db_utils::isUTF8($string)) {
        $string = utf8_decode($string);
      }
      return $string;
    });
  }

  public static function urldecode_all($entrada) {

    return \DBString::formatStringRecursive($entrada, function($string) {

      return urldecode($string);
    });
  }

  public static function urlencode_all($entrada) {

    return \DBString::formatStringRecursive($entrada, function($string) {

      return urlencode($string);
    });
  }

  /**
   * Formata os bytes em um formato humanamente legível
   *
   * @param  integer $bytes Quantiade em Bytes inseridas para conversão
   * @return String  Valor Formatado.
   *
   */
  public static function formatSizeUnits($bytes) {

    if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1) {
      $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1) {
      $bytes = $bytes . ' byte';
    }
    else {
      $bytes = '0 bytes';
    }

    return $bytes;
  }

  /**
   * Slugify string
   *
   * @param string $string
   * @return string
   */
  public static function slugify($string) {

    $response = \DBString::removerAcentuacao($string);
    $response = \DBString::removerCaracteresEspeciais($response);
    $response = preg_replace("/\s+/",  "-", $response);
    $response = mb_strtolower($response);
    return $response;
  }
}
