<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


$oDaoCargaHoraria = new cl_regracalculocargahoraria();

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);

  try {

    $sWhereOrigem    = " ed127_ano = {$iAnoOrigem}";
    $sSqlDadosOrigem = $oDaoCargaHoraria->sql_query_file( null, "*", null, $sWhereOrigem );
    $rsDadosOrigem   = db_query($sSqlDadosOrigem);

    if (!$rsDadosOrigem || pg_num_rows($rsDadosOrigem) == 0)  {
      throw new Exception("Não existe regra de calculo para carga horária no ano de: {$iAnoOrigem} ", 2);
    }

    $iLinha = pg_num_rows($rsDadosOrigem);
    for ($i = 0; $i < $iLinha; $i++) {

      $oDados = db_utils::fieldsMemory($rsDadosOrigem, $i);

      /**
       * Valida se já existe configuração para o ano migrado
       * @var string
       */
      $sWhereValida  = "     ed127_ano    = {$iAnoDestino}";
      $sWhereValida .= " and ed127_escola = {$oDados->ed127_escola}";
      $sSqlValida    = $oDaoCargaHoraria->sql_query_file(null, "1", null, $sWhereValida);
      $rsValida      = db_query($sSqlValida);
      if ($rsValida && pg_num_rows($rsValida) > 0) {
        continue;
      }

      $oDaoCargaHoraria->ed127_codigo                = null;
      $oDaoCargaHoraria->ed127_ano                   = $iAnoDestino;
      $oDaoCargaHoraria->ed127_calculaduracaoperiodo = $oDados->ed127_calculaduracaoperiodo == 't' ? 'true' : 'false' ;
      $oDaoCargaHoraria->ed127_escola                = $oDados->ed127_escola;
      $oDaoCargaHoraria->incluir(null);

      if ($oDaoCargaHoraria->erro_status == 0) {
        throw new Exception( $oDaoCargaHoraria->erro_msg, 1);
      }
    }

  } catch(Exception $oErro) {

    $sqlerro  = true;
    $erro_msg = $oErro->getMessage();

    if ( $oErro->getCode() == 2)  {

      $cldb_viradaitemlog->c35_log           = $oErro->getMessage();
      $cldb_viradaitemlog->c35_codarq        = 3781;
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);
      if ($cldb_viradaitemlog->erro_status == 0) {
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
      }
    }

  }

  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}
?>