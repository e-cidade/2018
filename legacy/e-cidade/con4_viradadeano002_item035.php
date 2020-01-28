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
 * Programa responsável pela virada anual das etapas do censo, e os vínculos das etapas do e-cidade
 */

$oDaoCensoEtapa      = new cl_censoetapa();
$oDaoSerieCensoEtapa = new cl_seriecensoetapa();
$oDaoMediacao        = new cl_censoetapamediacaodidaticopedagogica();

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);
  try {

    $sWhere         = " ed266_ano = {$iAnoOrigem} ";
    $sSqlCensoEtapa = $oDaoCensoEtapa->sql_query_file(null, null, "*", null, $sWhere);
    $rsCensoEtapa   = db_query($sSqlCensoEtapa);

    if (!$rsCensoEtapa || pg_num_rows($rsCensoEtapa) == 0)  {
      throw new Exception("Não existe etapas do censo para o ano de: {$iAnoOrigem} ", 2);
    }

    /**
     * Vira as etapas do censo
     */
    $iLinhasCensoEtapa = pg_num_rows($rsCensoEtapa);
    for ($i = 0; $i < $iLinhasCensoEtapa; $i++) {

      $oDadosEtapaCenso = db_utils::fieldsMemory( $rsCensoEtapa, $i);
      /*
       * Valida se para o ano de destino, já não existe a etapa do censo
       */
      $sWhereValidar         = "     ed266_ano      = {$iAnoDestino} ";
      $sWhereValidar        .= " and ed266_i_codigo = {$oDadosEtapaCenso->ed266_i_codigo} ";
      $sSqlValidarCensoEtapa = $oDaoCensoEtapa->sql_query_file(null, null, "*", null, $sWhereValidar);
      $rsValidarCensoEtapa   = db_query($sSqlValidarCensoEtapa);
      if ( !$rsValidarCensoEtapa ) {
        throw new Exception("Erro ao validar censoetapa.\n" . pg_last_error(), 1);
      }
      if ( pg_num_rows($rsValidarCensoEtapa) > 0) {
        continue;
      }

      $oDaoCensoEtapa->ed266_i_codigo   = $oDadosEtapaCenso->ed266_i_codigo;
      $oDaoCensoEtapa->ed266_c_descr    = $oDadosEtapaCenso->ed266_c_descr;
      $oDaoCensoEtapa->ed266_c_regular  = $oDadosEtapaCenso->ed266_c_regular;
      $oDaoCensoEtapa->ed266_c_especial = $oDadosEtapaCenso->ed266_c_especial;
      $oDaoCensoEtapa->ed266_c_eja      = $oDadosEtapaCenso->ed266_c_eja;
      $oDaoCensoEtapa->ed266_ano        = $iAnoDestino;
      $oDaoCensoEtapa->incluir($oDadosEtapaCenso->ed266_i_codigo, $iAnoDestino);

      if ( $oDaoCensoEtapa->erro_status == 0 ) {
        throw new Exception($oDaoCensoEtapa->erro_msg, 1);
      }
    }


    /**
     * Migra as mediações didático pedagógica
     */
    $sWhereMediacao = " ed131_ano = {$iAnoOrigem} ";
    $sSqlMediacao   = $oDaoMediacao->sql_query_file(null, "*", null, $sWhereMediacao);
    $rsMediacao     = db_query($sSqlMediacao);

    if ( !$rsMediacao ) {
      throw new Exception("Erro ao buscar mediações didático pedagógica.\n" . pg_last_error(), 1);
    }

    $iLinhasMediacao = pg_num_rows($rsMediacao);
    for ($i = 0; $i < $iLinhasMediacao; $i++) {

      $oDadosMediacao = db_utils::fieldsMemory($rsMediacao, $i);

      $sWhereValidar  = "     ed131_censoetapa                 = {$oDadosMediacao->ed131_censoetapa} ";
      $sWhereValidar .= " and ed131_mediacaodidaticopedagogica = {$oDadosMediacao->ed131_mediacaodidaticopedagogica} ";
      $sWhereValidar .= " and ed131_ano                        = {$iAnoDestino} ";

      $sSqlValidarMediacao = $oDaoMediacao->sql_query_file(null, "*", null, $sWhereValidar);
      $rsValidarMediacao   = db_query($sSqlValidarMediacao);
      if ($rsValidarMediacao && pg_num_rows($rsValidarMediacao) > 0) {
        continue;
      }

      $oDaoMediacao->ed131_codigo                     = null;
      $oDaoMediacao->ed131_mediacaodidaticopedagogica = $oDadosMediacao->ed131_mediacaodidaticopedagogica;
      $oDaoMediacao->ed131_censoetapa                 = $oDadosMediacao->ed131_censoetapa;
      $oDaoMediacao->ed131_ano                        = $iAnoDestino;
      $oDaoMediacao->ed131_regular                    = "$oDadosMediacao->ed131_regular";
      $oDaoMediacao->ed131_especial                   = "$oDadosMediacao->ed131_especial";
      $oDaoMediacao->ed131_eja                        = "$oDadosMediacao->ed131_eja";
      $oDaoMediacao->ed131_profissional               = "$oDadosMediacao->ed131_profissional";
      $oDaoMediacao->incluir(null);

      if ( $oDaoMediacao->erro_status == 0 ) {
        throw new Exception($oDaoMediacao->erro_msg, 1);
      }
    }

    $sWhereSerie    = " ed133_ano = {$iAnoOrigem} ";
    $sSqlSerieCenso = $oDaoSerieCensoEtapa->sql_query_file(null, " * ", null, $sWhereSerie);
    $rsSerieCenso   = db_query($sSqlSerieCenso);

    if ( !$rsSerieCenso ) {

      $sMsgErro = "Erro ao buscar vínculos das etapas do e-cidade as etapas do censo. \n".pg_last_error();
      throw new Exception($sMsgErro , 1);
    }

    /**
     * Vira os vínculos das etapas do e-cidade com as etapas do censo
     */
    $iLinhasSerieCenso = pg_num_rows($rsSerieCenso);
    for( $i = 0; $i < $iLinhasSerieCenso; $i++ ) {

      $oDadosSerieCenso = db_utils::fieldsMemory($rsSerieCenso, $i);

      /*
       * Valida se etapa já foi vínculada a uma etapa do censo
       */
      $sWhereValidar  = "     ed133_serie      = {$oDadosSerieCenso->ed133_serie} ";
      $sWhereValidar .= " and ed133_censoetapa = {$oDadosSerieCenso->ed133_censoetapa} ";
      $sWhereValidar .= " and ed133_ano        = {$iAnoDestino} ";

      $sSqlValidarSerieCenso = $oDaoSerieCensoEtapa->sql_query_file(null, " * ", null, $sWhereValidar);
      $rsValidarSerieCenso   = db_query($sSqlValidarSerieCenso);
      if ( !$rsValidarSerieCenso ) {
        throw new Exception("Erro ao validar seriecensoetapa.\n" . pg_last_error(), 1);
      }

      if ( pg_num_rows($rsValidarSerieCenso) > 0) {
        continue;
      }

      $oDaoSerieCensoEtapa->ed133_codigo     = null;
      $oDaoSerieCensoEtapa->ed133_serie      = $oDadosSerieCenso->ed133_serie;
      $oDaoSerieCensoEtapa->ed133_censoetapa = $oDadosSerieCenso->ed133_censoetapa;
      $oDaoSerieCensoEtapa->ed133_ano        = $iAnoDestino;

      $oDaoSerieCensoEtapa->incluir(null);

      if ( $oDaoSerieCensoEtapa->erro_status == 0 ) {
        throw new Exception($oDaoSerieCensoEtapa->erro_msg, 1);
      }
    }


  } catch( Exception $oErro) {

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

