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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/JSON.php');
require_once('libs/db_utils.php');

$oJson   = new services_json();
$sName   = html_entity_decode(crossUrlDecode($_POST['string']));

$oDaoDisciplina = new cl_disciplina();

$sCampos = " distinct ed12_i_codigo as cod, trim(ed232_c_descr) as label ";
$sOrder  = " 2 ";

$sWhere  = "     ed29_i_codigo = {$_GET['iCurso']}";
$sWhere .= " and trim(ed232_c_descr) like upper('%{$sName}%') ";

/**
 * Filtros de exclusão
 */
if ( !empty($_GET['sFiltroExclusive']) ) {

  switch ($_GET['sFiltroExclusive']) {

    case 'base':
      $sWhere .= " and ed12_i_codigo not in (select ed34_i_disciplina from basemps where ed34_i_base = {$_GET['iBase']} and ed34_i_serie = {$_GET['iEtapa']})";
      break;
    case 'turma':
      $sWhere .= " and ed12_i_codigo not in (select ed59_i_disciplina from regencia where ed59_i_turma = {$_GET['iTurma']} and ed59_i_serie = {$_GET['iEtapa']})";
      break;
  }
}
$sSql    = $oDaoDisciplina->sql_query(null, $sCampos, $sOrder, $sWhere);
$rs      = $oDaoDisciplina->sql_record($sSql);
$iLinhas = $oDaoDisciplina->numrows;

$aRetorno = array();
if ($iLinhas > 0) {
  $aRetorno = db_utils::getCollectionByRecord($rs, false, false, true);
}

echo $oJson->encode($aRetorno);



function crossUrlDecode($sSource) {

  // Troco os caracteres especiais por pelo coringa
  $aOrig   = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç',
    'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç'
  );

  return str_replace($aOrig, '_', mb_convert_encoding($sSource, "ISO-8859-1", "UTF-8"));

}