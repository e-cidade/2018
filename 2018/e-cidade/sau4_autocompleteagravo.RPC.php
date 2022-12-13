<?php
/* 
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 * 
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 * 
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 * 
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */


require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/JSON.php');
require_once('libs/db_utils.php');

$oJson = new services_json();
$sName = html_entity_decode(crossUrlDecode($_POST['string']));

$sCampos  = " distinct sd70_i_codigo as cod, trim(sd70_c_nome) as label ";
$aWhere   = array();
$aWhere[] = " trim(sd70_c_nome) like upper('%{$sName}%') ";

$oDaoSauCid = new cl_sau_cid();
$sSqlSauCid = $oDaoSauCid->sql_query_file(null, $sCampos, "label", implode(" and ", $aWhere));
$rsSauCid   = db_query($sSqlSauCid);
$iLinhas    = pg_num_rows($rsSauCid);

$aRetorno = array();
if ($iLinhas > 0) {
  $aRetorno = db_utils::getCollectionByRecord($rsSauCid, false, false, true);
}

echo $oJson->encode($aRetorno);


function crossUrlDecode($sSource) {

  // Troco os caracteres especiais por pelo coringa
  $aOrig   = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç',
    'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç'
  );
  return str_replace($aOrig, '_', mb_convert_encoding($sSource, "ISO-8859-1", "UTF-8"));
}