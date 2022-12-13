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
 *
 * Esta classe deve conter metodos para tratamento de Números.
 * @name DBNumber
 * @package std
 * @author dbseller
 * @version $Id: DBNumber.php,v 1.13 2016/09/29 13:26:34 dbandrio.costa Exp $
 */
class DBNumber {

  /**
   * Método responsável por arredondar um número float
   *
   * @param float $nNumber Numero a ser tratado
   * @param integar $iBase base de arredondamento
   * @return float
   */
  public static function round($nNumber=null,$iBase=null){

    /**
     * Metodo wrapper para correção de bug no metodo round em versões do php <= 5.2
     */
    if (floatval(phpversion()) <= 5.2) {
      return round(round($nNumber*pow(10, $iBase+1), 0), -1)/pow(10, $iBase+1);
    }
    return round($nNumber,$iBase);
  }

  /**
   * Retorna apenas a parte inteira do numero
   *
   * @param float $nNumero
   * @return integer parte inteira do numero informado
   */
  public static function truncate($nNumero, $iPrecisao = 0) {

    $aValuesPart  = explode(".", $nNumero);
    $iIntPart     = $aValuesPart[0];
    if (isset($aValuesPart[1]) && $iPrecisao > 0) {
      $iIntPart .= ".".substr($aValuesPart[1], 0, $iPrecisao);
    }
    return $iIntPart;
  }

  /**
   * Valida se um valor é do Tipo Float
   * @param numeric $nValor
   */
  public static function isFloat($nValor) {

    $sRegex = "/^-?(?:\d+|\d*\.\d+)$/";
    return preg_match($sRegex, $nValor) ? true : false;
  }

  /**
   * Valida se um numero é inteiro
   *
   * @param mixed $iNumero
   * @static
   * @access public
   * @return boolean
   */
  public static function isInteger( $iNumero ) {

    $sRegex = "/^-?([0-9])+$/";
    return preg_match( $sRegex, $iNumero ) ? true : false;
  }

  /**
   * Converte valor monetario para padrao americano
   *
   * @param  mixed $nNumero
   * @static
   * @access public
   * @return numeric
   */
  public static function toCurrency( $nNumero ) {

    $aPattern = array(
        '/\./',
        '/\,(?=\d*$)/'
      );

    $aReplacement = array(
        '',
        '.'
      );

    return preg_replace($aPattern, $aReplacement, $nNumero);
  }

  public static function overlaps($nValor, $nValorInicio, $nValorFim) {

    $nValor       = (float)$nValor ;
    $nValorInicio = (float)$nValorInicio;
    $nValorFim    = (float)$nValorFim;

    return ($nValor >= $nValorInicio && $nValor <=$nValorFim);
  }

  /**
   * Criado com o propósito de usar com o array_reduce.
   * Retorna o maior valor entre dois valores
   * @param  integer $iValor1
   * @param  integer $iValor2
   * @return integer
   */
  public static function maiorValor($iValor1, $iValor2) {

    $iAux = $iValor1;
    if ($iValor1 < $iValor2) {
      $iAux = $iValor2;
    }
    return $iAux;
  }
}
