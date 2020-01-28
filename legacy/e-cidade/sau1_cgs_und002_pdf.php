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

include("fpdf151/pdf.php");

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

$alt = 5;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head2 = "CGS";
$head3 = "Data de emissão: ".date("d-m-Y",db_getsession("DB_datausu"));

$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',10);

$pdf->cell(40,$alt,'Código CGS:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt,$GLOBALS['z01_i_cgsund'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'Nome do paciente:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt,$GLOBALS['z01_v_nome'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'Nascimento:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt,$GLOBALS['z01_d_nasc_dia'] . '/' . $GLOBALS['z01_d_nasc_mes'] . '/' . $GLOBALS['z01_d_nasc_ano'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'Cartão SUS:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt,$GLOBALS['s115_c_cartaosus'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'Endereço:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt,$GLOBALS['z01_v_ender'] . ', ' . $GLOBALS['z01_i_numero'] . ' ' . $GLOBALS['z01_v_compl'] . ', ' . $GLOBALS['z01_v_bairro'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'Município:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt, $GLOBALS['z01_v_munic'] . ' - ' . $GLOBALS['z01_v_uf'],1,0,"L",1);
$pdf->ln();

$pdf->setfont('arial','b',10);
$pdf->cell(40,$alt,'CEP:',1,0,"R",1);
$pdf->setfont('arial','',10);
$pdf->cell(120,$alt, $GLOBALS['z01_v_cep'],1,0,"L",1);
$pdf->ln();

$pdf->Output();
