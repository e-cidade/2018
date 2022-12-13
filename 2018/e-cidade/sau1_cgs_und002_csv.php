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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");

$clcgs           = new cl_cgs;
$clcgs_und       = new cl_cgs_und_ext;
$clcgs_cartaosus = new cl_cgs_cartaosus;

$db_opcao  = 2;
$db_opcao1 = 3;
$sWhere    = '';

if ( isset($sCartaoSus) && !empty($sCartaoSus) ) {
  $sWhere = "s115_c_cartaosus = '{$sCartaoSus}'";
}

$sql = $clcgs_und->sql_query_ext($chavepesquisa, "*", null, $sWhere);

$result    = $clcgs_und->sql_record($sql);
db_fieldsmemory($result,0);

$export = array(
  array('Código CGS', 'Nome do paciente', 'Nascimento', 'Cartão SUS', 'Endereço', 'Município', 'CEP'),
  array(
    $GLOBALS['z01_i_cgsund'],
    utf8_encode($GLOBALS['z01_v_nome']),
    $GLOBALS['z01_d_nasc_dia'] . '/' . $GLOBALS['z01_d_nasc_mes'] . '/' . $GLOBALS['z01_d_nasc_ano'],
    $GLOBALS['s115_c_cartaosus'],
    utf8_encode($GLOBALS['z01_v_ender'] . ', ' . $GLOBALS['z01_i_numero'] . ' ' . $GLOBALS['z01_v_compl'] . ', ' . $GLOBALS['z01_v_bairro']),
    utf8_encode($GLOBALS['z01_v_munic'] . ' - ' . $GLOBALS['z01_v_uf']),
    utf8_encode($GLOBALS['z01_v_cep'])
  )
);



function array2csv(array &$array)
{
  if (count($array) == 0) {
    return null;
  }
  ob_start();
  $df = fopen("php://output", 'w');
  //fputcsv($df, array_keys(reset($array)));
  foreach ($array as $row) {
    fputcsv($df, $row);
  }
  fclose($df);
  return ob_get_clean();
}

function download_send_headers($filename) {
  // disable caching
  $now = gmdate("D, d M Y H:i:s");
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
  header("Last-Modified: {$now} GMT");

  // force download
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");

  // disposition / encoding on response body
  header("Content-Disposition: attachment;filename={$filename}");
  header("Content-Transfer-Encoding: binary");
  header('Content-Encoding: UTF-8');
}

download_send_headers('cgs.csv');
echo array2csv($export);
die();
