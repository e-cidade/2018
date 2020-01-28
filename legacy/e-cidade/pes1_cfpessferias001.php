<?php

/**
 *          E-cidade Software Publico para Gestao Municipal
 *        Copyright (C) 2014 DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
 * 
 * Este programa e software livre; voce pode redistribui-lo e/ou
 * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a versao 2 da
 * Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 * Este programa e distribuido na expectativa de ser util, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia implicita de
 * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 * detalhes.
 * 
 * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 * junto com este programa; se nao, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *  
 * Copia da licenca no diretorio licenca/licenca_en.txt
 *                               licenca/licenca_pt.txt
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'classes/db_cfpess_classe.php';
require_once 'classes/db_inssirf_classe.php';
require_once 'dbforms/db_funcoes.php';

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clcfpess  = new cl_cfpess;
$clinssirf = new cl_inssirf;
$db_opcao  = 2;
$db_botao  = true;
$iAnoCompetencia = DBPessoal::getAnoFolha();
$iMesCompetencia = DBPessoal::getMesFolha();

if (isset($alterar)) {
  include_once 'pes1_cfpess002.php';
}

$sSql = $clcfpess->sql_query_rubr(
  $iAnoCompetencia,
  $iMesCompetencia,
  'r11_recalc,
   r11_fersal,
   r11_pagaab,
   r11_propae,
   r11_propac,
   r11_13ferias,
   r11_pagarferias,
   r11_compararferias,
   r11_baseferias,
   r11_basesalario'
);

$rsResult = db_query($sSql);
db_fieldsmemory($rsResult, 0);

/**
 * Buscar descrição das bases;
 */
$iInstituicao = db_getsession('DB_instit');

$sCampo       = "(select r08_descr from bases where r08_codigo = r11_baseferias                       ";
$sCampo      .= "                               and r08_anousu = r11_anousu                           ";
$sCampo      .= "                               and r08_mesusu = r11_mesusu                           ";
$sCampo      .= "                               and r08_instit = r11_instit) as baseferias_descricao, ";
$sCampo      .= "(select r08_descr from bases where r08_codigo = r11_basesalario                      ";
$sCampo      .= "                               and r08_anousu = r11_anousu                           ";
$sCampo      .= "                               and r08_mesusu = r11_mesusu                           ";
$sCampo      .= "                               and r08_instit = r11_instit) as basesalario_descricao ";
$sSqlBases    = $clcfpess->sql_query_file($iAnoCompetencia, $iMesCompetencia, $iInstituicao, $sCampo);

$rsResultBases = db_query($sSqlBases);
db_fieldsmemory($rsResultBases, 0);

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <link rel="stylesheet" href="estilos.css">
</head>
<body onload="a=1" ondragstart="return false;" ondrop="return false;">
  <?php include_once 'forms/db_frmcfpessferias.php'; ?>
  <script src="scripts/scripts.js"></script>
  <?php db_menu(); ?>
</body>
</html>
<?php
if (isset($alterar)) {

  if ($clcfpess->erro_status == "0") {

    $clcfpess->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clcfpess->erro_campo != "") {

      echo "<script> document.form1.".$clcfpess->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfpess->erro_campo.".focus();</script>";
    }
  } else {
    $clcfpess->erro(true,true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>