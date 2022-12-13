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
 * Classe para executar o mesmo cálculo executado no relatório de execução de restos à pagar.
 * Class ValorRestoAPagar
 */
class ValorRestoAPagar {

  private function __construct() {}

  /**
   * Valor referente a coluna Saldos a Pagar Anteriores (RP Proc)
   * @param stdClass $oStdRestos
   * @return float
   */
  public static function saldoAnteriorProcessado(stdClass $oStdRestos) {
    return (float)($oStdRestos->e91_vlrliq - $oStdRestos->e91_vlrpag);
  }

  /**
   * Valor referente a coluna Saldo a Pagar Finais (A Liquidar)
   * @param stdClass $oStdRestos
   * @return float
   */
  public static function saldoFinalALiquidar(stdClass $oStdRestos) {
    return (float)($oStdRestos->e91_vlremp - (($oStdRestos->e91_vlranu + $oStdRestos->vlranu) + ($oStdRestos->vlrliq + $oStdRestos->e91_vlrliq - $oStdRestos->vlranuliq)));
  }

  /**
   * Retorna o valor da coluna Saldo a Pagar Finais (Liquidados)
   * @param stdClass $oStdRestos
   * @return float
   */
  public static function saldoFinalLiquidado(stdClass $oStdRestos) {

    $nLiquidadoAnterior  = ($oStdRestos->e91_vlremp - $oStdRestos->e91_vlranu - $oStdRestos->e91_vlrliq) + ($oStdRestos->e91_vlrliq - $oStdRestos->e91_vlrpag);
    $nAPagarGeral        = ($nLiquidadoAnterior - $oStdRestos->vlranu - $oStdRestos->vlrpag - $oStdRestos->vlrpagnproc);
    return ($nAPagarGeral - self::saldoFinalALiquidar($oStdRestos));
  }

  /**
   * @param stdClass $oStdResto
   * @return float
   */
  public static function processado(stdClass $oStdResto) {
    return (float)($oStdResto->e91_vlrliq - $oStdResto->e91_vlrpag);
  }

  /**
   * @param stdClass $oStdResto
   * @return float
   */
  public static function naoProcessado(stdClass $oStdResto) {
    return (float)($oStdResto->e91_vlremp - $oStdResto->e91_vlranu - $oStdResto->e91_vlrliq);
  }

}