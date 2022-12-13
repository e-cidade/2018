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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");

$oJson      = new Services_JSON();
$oParam     = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {

  case 'buscar':

    $oDaoSerie = db_utils::getDao('serie');

    $oRetorno->aVagas = array();

    $sCampos    = " ed11_i_codigo,
                    ed11_c_descr,
                    COALESCE(SUM(mo10_numvagas),0) AS total_vagas ";
    $sWhere     = " ed10_i_codigo = {$oParam->iEnsino} ";
    $sWhere    .= " AND ed85_i_escola = {$oParam->iEscola} ";
    $sWhere    .= " AND ed85_i_turno  = {$oParam->iTurno} ";
    $sWhere    .= " GROUP by ed11_i_codigo ";
    $sOrdem     = " ed11_i_codigo ";

    $sSqlVagas  = $oDaoSerie->sql_query_vagas_etapa( null, $sCampos, $sOrdem, $sWhere );
    $rsVagas    = db_query($sSqlVagas);

    if ( !$rsVagas ) {
      throw new DBException("Erro ao buscar vagas!");
    }

    if ( pg_num_rows($rsVagas) > 0 ) {

      for ($iContador = 0; $iContador < pg_num_rows($rsVagas); $iContador++) {

        $oDadosVagas    = db_utils::fieldsMemory($rsVagas, $iContador);
        $oVagasRetorno  = new stdClass();
        $oVagasRetorno->iSerie      = $oDadosVagas->ed11_i_codigo;
        $oVagasRetorno->sSerie      = urlencode($oDadosVagas->ed11_c_descr);
        $oVagasRetorno->iTotalVagas = $oDadosVagas->total_vagas;

        $oRetorno->aVagas[] = $oVagasRetorno;
      }
    }

  break;

}

echo $oJson->encode($oRetorno);