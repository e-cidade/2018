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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_libpostgres.php"));

$oPost = db_utils::postMemory($_POST);
$sJoins = "";
db_sel_instit(null, "db21_regracgmiptu");
switch ($oPost->tipo_origem) {
    case 'numcgm':
      $sCampos = "arrenumcgm.k00_numcgm as id, cgm.z01_nome as nome, ";
      $sJoins .= " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
      $sJoins .= " inner join cgm on cgm.z01_numcgm = arrenumcgm.k00_numcgm ";
    break;

    case 'inscr':
      $sCampos = "arreinscr.k00_inscr as id, (select rvnome  from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'I', arreinscr.k00_inscr) limit 1) as nome, ";
      $sJoins .= " inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
      $sJoins .= " inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr ";
      $sJoins .= " inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm ";
    break;

    case 'matric':
      $sCampos  = "arrematric.k00_matric as id, (select rvnome  from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'M', arrematric.k00_matric) limit 1) as nome, ";
      $sJoins  .= " inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
      $sJoins  .= " inner join iptubase on arrematric.k00_matric = iptubase.j01_matric ";
      $sJoins  .= " inner join cgm on cgm.z01_numcgm = iptubase.j01_numcgm ";
    break;

    default:
      throw new Exception ("Opção de origem invalida.");
    break;
}

$sCampos .= " arretipo.k00_descr as origem, sum(abatimentoarreckey.k128_valorabatido) as valor";

$sSql = "select {$sCampos} 
      from abatimentorecibo
      inner join abatimento           on abatimento.k125_sequencial           = abatimentorecibo.k127_abatimento 
      inner join abatimentoarreckey   on abatimentoarreckey.k128_abatimento   = abatimento.k125_sequencial
      inner join arreckey             on arreckey.k00_sequencial              = abatimentoarreckey.k128_arreckey
      inner join arretipo             on arretipo.k00_tipo                    = arreckey.k00_tipo";
$sSql .= $sJoins;
$sSql .= " where abatimento.k125_tipoabatimento = 3";
$sSql .= " group by id, nome, origem ";
$sSql .= " order by {$oPost->ordenador} {$oPost->ordenacao} ";

$rsCreditos = db_query($sSql);
$iQtd = pg_num_rows($rsCreditos);

$oPdf = null;
if ($rsCreditos && $iQtd > 0) {
        $oPdf = new PDFTable();
        $oPdf->setLineHeigth(5);
        $oPdf->addHeaderDescription("Relatório de Créditos");
        $oPdf->setPercentWidth(true);
        $oPdf->setColumnsWidth(array("10","50","30","10"));
        $oPdf->setHeaders(array( ucfirst($oPost->tipo_origem),"Nome","Receita","Valor"));
        $oPdf->setColumnsAlign(array(PDFDocument::ALIGN_RIGHT, PDFDocument::ALIGN_LEFT, PDFDocument::ALIGN_LEFT, PDFDocument::ALIGN_RIGHT));

  for ($i = 0; $i < $iQtd; $i++) {

    $oCredito = db_utils::fieldsMemory($rsCreditos, $i);

    $oPdf->addLineInformation(array(
        $oCredito->id,
        substr($oCredito->nome,0,36),
        str_replace("PARCELAMENTO", "PARCEL.", $oCredito->origem),
        $oCredito->valor
    ));

  }
}

$oPdf->printOut();

