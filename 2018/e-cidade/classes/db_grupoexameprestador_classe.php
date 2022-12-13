<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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


class cl_grupoexameprestador extends DAOBasica
{
  public function __construct()
  {
    parent::__construct("agendamento.grupoexameprestador");
  }

  /**
   * Criamos o SQL que insere os registros de dias da semana para a cota mensal
   *
   * @param  DBDate  $oDataInicial    Data Inicial
   * @param  DBDate  $oDataFinal      DataFinal
   * @param  integer $iPrestadorExame
   *
   * @return string
   */
  public function getQueryInsertDiasSemanaParaMes( DBDate  $oDataInicial, DBDate $oDataFinal, $iPrestadorExame )
  {
    $sInsertDiasSemana  = " insert into sau_prestadorhorarios ";
    $sInsertDiasSemana .= "      select nextval('sau_prestadorhorarios_codigo_seq'), ";
    $sInsertDiasSemana .= "             $iPrestadorExame, ";
    $sInsertDiasSemana .= "             dia_semana, ";
    $sInsertDiasSemana .= "             '00:00', ";
    $sInsertDiasSemana .= "             '23:59', ";
    $sInsertDiasSemana .= "             0, ";
    $sInsertDiasSemana .= "             0, ";
    $sInsertDiasSemana .= "             'M', ";
    $sInsertDiasSemana .= "             (select min(sd101_i_codigo) from sau_tipoficha), ";
    $sInsertDiasSemana .= "             '{$oDataInicial->getDate()}', ";
    $sInsertDiasSemana .= "             '{$oDataFinal->getDate()}' ";
    $sInsertDiasSemana .= "        from generate_series(1, 7) as dia_semana ";

    return $sInsertDiasSemana;
  }
}
