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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV as Relatorio;

/**
 * Class AnexoIV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory
 */
class AnexoIV {

  const MENSAGEM = 'financeiro.contabilidade.AnexoIV.';

  private function __construct() {}

  /**
   * @param integer  $iAno
   * @param \Periodo $oPeriodo
   *
   * @return \AnexoIVDemonstrativoRPPS|Relatorio
   * @throws \ParameterException
   */
  public static function getInstance($iAno, \Periodo $oPeriodo) {

    if (empty($iAno)) {
      throw new \ParameterException(_M(self::MENSAGEM . 'parametro_ano_nao_informado'));
    }

    if (empty($oPeriodo)) {
      throw new \ParameterException(_M(self::MENSAGEM . 'parametro_periodo_nao_informado'));
    }

    if ($iAno >= 2017) {
      $oAnexo = new Relatorio($iAno, $oPeriodo->getCodigo());
    } else {
      $oAnexo = new \AnexoIVDemonstrativoRPPS($iAno, \AnexoIVDemonstrativoRPPS::CODIGO_RELATORIO, $oPeriodo->getCodigo());
    }

    return $oAnexo;
  }
}