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
 * Class Check
 */
class Check {

  private function __construct() {}
  private function __clone() {}

  /**
   * Verfica se o valor está entre o valor mínimo e máximo
   * @param $iValorProcurado
   * @param $iValorMinimo
   * @param $iValorMaximo
   * @return bool
   */
  public static function between($iValorProcurado, $iValorMinimo, $iValorMaximo) {
    return ($iValorProcurado >= $iValorMinimo && $iValorProcurado <= $iValorMaximo);
  }

  /**
   * Verifica se o valor informado é inteiro ou compatível com inteiro.
   * @param $iValor
   *
   * @return bool
   */
  public static function isInt($iValor) {
    return filter_var($iValor, FILTER_VALIDATE_INT, array('flags' => FILTER_NULL_ON_FAILURE)) !== null;
  }

  /**
   * Verifica se o valor informadi é float ou compatível com float.
   * @param $nValor
   *
   * @return bool
   */
  public static function isFloat($nValor) {
    return filter_var($nValor, FILTER_VALIDATE_FLOAT, array('flags' => FILTER_NULL_ON_FAILURE)) !== null;
  }

  /**
   * Verifica se o valor informado é boolean ou compatível com boolean.
   * @param $nValor
   *
   * @return bool
   */
  public static function isBoolean($nValor) {

    if (is_bool($nValor)) {
      return true;
    }
    return filter_var($nValor, FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE)) !== null;
  }
}