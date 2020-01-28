<?php

/**
 * Formata os valores dos atributos de exames
 * @author  Andrio Costa - andrio.costa@dbseller.com.br
 * @package laboratorio
 * @version $Revision: 1.1 $
 */
class MascaraValorAtributoExame {

  /**
   * Aplica virgula como separador decimal
   * @param  float $nValor    valor
   * @param  int   $iDecimais número de casas decimais
   * @return float
   */
  public static function formataComVirgula($nValor, $iDecimais) {

    return number_format($nValor, $iDecimais, ',', '');
  }

  /**
   * Aplica zero(s) a direita de um valor de acordo com o número de casas decimais informado
   * @param  int     $iCasasDecimais         Número de casas decimais para formatar
   * @param  floar   $nValor                 Valor a ser mascarado
   * @param  boolean $lSepararDecimalVirgula se aplica virgula como separador decimal
   * @return float                           valor formatado
   */
  public static function mascarar($iCasasDecimais, $nValor, $lSepararDecimalVirgula = true) {

    $aValor = explode(".", $nValor);
    if (is_null($iCasasDecimais) || $iCasasDecimais === 0) {

      if (isset($aValor[1]) &&  $lSepararDecimalVirgula) {
        $nValor = MascaraValorAtributoExame::formataComVirgula($nValor, strlen($aValor[1]));
      }
      return $nValor;
    }

    if (isset($aValor[1])) {
      $sCasasDecimaisFormatada = str_pad($aValor[1], $iCasasDecimais, "0", STR_PAD_RIGHT);
    } else {
      $sCasasDecimaisFormatada = str_pad(0, $iCasasDecimais, "0", STR_PAD_RIGHT);
    }

    $nValorMascarado = $aValor[0] .".". $sCasasDecimaisFormatada;

    if ($lSepararDecimalVirgula) {
      $nValorMascarado = MascaraValorAtributoExame::formataComVirgula($nValorMascarado, strlen($sCasasDecimaisFormatada));
    }
    return $nValorMascarado;
  }

}