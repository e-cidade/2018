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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clbaseregimematdiv = new cl_baseregimematdiv;
$clserieregimemat   = new cl_serieregimemat;

$oPost = db_utils::postMemory( $_POST );

if( $oPost->sAction == 'PesquisaDivisao' ) {

  $sCampos = "ed219_i_codigo, ed219_c_nome";
  $sSql    = $clbaseregimematdiv->sql_query( "", $sCampos, "ed219_i_ordenacao", "ed224_i_base = {$oPost->base}" );
  $result  = $clbaseregimematdiv->sql_record( $sSql );
  $aResult = db_utils::getCollectionByRecord( $result, false, false, true );
  $oJson   = new services_json();
  echo $oJson->encode($aResult);
}

if( $oPost->sAction == 'PesquisaEtapaDivisao' ) {

  $iAnoConsulta = 2014;
  if ( !empty($oPost->iCalendario) ) {

    $oCalendario  = new Calendario( $oPost->iCalendario );
    $iAnoCenso    = DadosCenso::getUltimoAnoEtapaCenso();

    if ( $oCalendario->getAnoExecucao() > 2014 && $oCalendario->getAnoExecucao() == $iAnoCenso ) {
      $iAnoConsulta = $iAnoCenso;
    }
  }

  $sCampos  = " DISTINCT ed223_i_codigo, ed11_i_codigo, ed133_censoetapa as ed11_i_codcenso, ";
  $sCampos .= " ed11_c_descr, ed223_i_ordenacao, ed266_c_descr";
  $sWhere   = "     ed223_i_regimemat = {$oPost->codregime} AND ed11_i_ensino = {$oPost->codensino}";
  $sWhere  .= " and ed223_i_regimematdiv = {$oPost->coddivisao} ";
  $sWhere  .= " and ed266_ano = {$iAnoConsulta} ";
  $sWhere  .= " and ed266_ano = {$iAnoConsulta} ";
  if ( !empty($oPost->iBase) ) {
    $sWhere .= "AND ed31_i_codigo = {$oPost->iBase} ";
  }

  $sSql    =  $clserieregimemat->sql_query_censo_etapa( "", $sCampos, "ed223_i_ordenacao", $sWhere );
  $result  = $clserieregimemat->sql_record( $sSql );
  $aResult = db_utils::getCollectionByRecord( $result, false, false, true );
  $oJson   = new services_json();
  echo $oJson->encode($aResult);
}

if( $oPost->sAction == 'PesquisaEtapa' ) {

  $oCalendario  = new Calendario( $oPost->iCalendario );
  $iAnoCenso    = DadosCenso::getUltimoAnoEtapaCenso();
  $iAnoConsulta = 2014;

  if ( $oCalendario->getAnoExecucao() > 2014 && $oCalendario->getAnoExecucao() == $iAnoCenso ) {
    $iAnoConsulta = $iAnoCenso;
  }

  $sCamposEtapaCenso  = " DISTINCT ed223_i_codigo, ed11_i_codigo, ed133_censoetapa as ed11_i_codcenso, ed11_c_descr";
  $sCamposEtapaCenso .= ", ed223_i_ordenacao, ed266_c_descr ";
  $sWhereEtapaCenso   = "     ed223_i_regimemat = {$oPost->codregime} AND ed11_i_ensino = {$oPost->codensino}";
  $sWhereEtapaCenso  .= " AND ed31_i_codigo = {$oPost->codbase} AND ed266_ano = {$iAnoConsulta}";
  $sSqlEtapaCenso     = $clserieregimemat->sql_query_censo_etapa("", $sCamposEtapaCenso, "ed223_i_ordenacao", $sWhereEtapaCenso );
  $rsEtapaCenso       = db_query( $sSqlEtapaCenso );
  $aResult            = db_utils::getCollectionByRecord( $rsEtapaCenso, false, false, true );
  $oJson              = new services_json();
  echo $oJson->encode($aResult);
}

if($oPost->sAction == 'AtualizaAuto') {

  $sSql    = "UPDATE turmaserieregimemat ";
  $sSql   .= "   SET ed220_c_aprovauto = '{$oPost->valorauto}' ";
  $sSql   .= " WHERE ed220_i_codigo = {$oPost->codtsrmat}";
  $result  = db_query( $sSql );
}