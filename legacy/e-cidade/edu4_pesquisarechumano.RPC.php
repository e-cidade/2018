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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';

$oJson         = new services_json();
$oDaoRecHumano = new cl_rechumano();
$sName         = utf8_decode($_POST["string"]);

$iEscola = db_getsession('DB_coddepto');
if ( !empty($_GET['iEscola']) ) {
  $iEscola = $_GET['iEscola'];
}

$sCampos  = " distinct                        ";
$sCampos .= " ed20_i_codigo as cod,           ";
$sCampos .= " case                            ";
$sCampos .= "    when ed20_i_tiposervidor = 1 ";
$sCampos .= "      then trim(cgmrh.z01_nome)  ";
$sCampos .= "    else trim(cgmcgm.z01_nome)   ";
$sCampos .= " end as label                    ";

$sWhere  = " (cgmrh.z01_nome ilike '{$sName}%' or cgmcgm.z01_nome ilike '$sName%')";
$sWhere .= " and ed75_i_escola = {$iEscola} ";
$sWhere .= " and ed75_i_saidaescola is null ";

if ( isset($_GET['lFiltraAtividade']) && !empty($_GET['iAtividade']) ) {
  $sWhere .= " and ed01_funcaoatividade = {$_GET['iAtividade']}";
}

$sSql   = $oDaoRecHumano->sql_query_relatorio(null, $sCampos, " 2 ", $sWhere);
$rs     = db_query($sSql);
$aArray = db_utils::getCollectionByRecord($rs, false, false, true);
echo $oJson->encode($aArray);