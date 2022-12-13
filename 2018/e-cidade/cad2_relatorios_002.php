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

  include("fpdf151/pdf.php");
  db_postmemory($_SESSION);

  if ($opcaoRelatorio == "ruasAvenidas"){

    $sql = "
	select j14_codigo, j14_nome, j14_tipo
	from ruas
	";
	if ($opcaoOrdem == "alfabetica") {

	  $head5 = "RELATÓRIO DE RUAS / AVENIDAS EM ORDEM ALFABÉTICA";
	  $sql .= "
	  order by j14_nome
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $ttotal = 0;
      $pdf->Cell(80,4,"RUA / AVENICA","LRBT",0,"C",0);
      $pdf->Cell(15,4,"CÓDIGO","LRBT",0,"C",0);
      $pdf->Cell(30,4,"TIPO","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->Cell(80,4,trim(pg_result($result,$i,"j14_nome")),"B",0,"L",0);
        $pdf->Cell(15,4,pg_result($result,$i,"j14_codigo"),"B",0,"C",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j14_tipo"),"B",1,"C",0);
        $ttotal += 1;
	  }
        $pdf->cell(125,4,"Total : ".$ttotal." Registros",1,1,"L",0);
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "RELATÓRIO DE RUAS / AVENIDAS EM ORDEM NUMÉRICA";
	  $sql .= "
	  order by j14_codigo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $ttotal = 0;
      $pdf->Cell(15,4,"CÓDIGO","LRBT",0,"C",0);
      $pdf->Cell(80,4,"RUA / Avenida","LRBT",0,"C",0);
      $pdf->Cell(30,4,"TIPO","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->Cell(15,4,pg_result($result,$i,"j14_codigo"),"B",0,"C",0);
        $pdf->Cell(80,4,trim(pg_result($result,$i,"j14_nome")),"B",0,"L",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j14_tipo"),"B",1,"C",0);
        $ttotal += 1;
	  }
        $pdf->cell(125,4,"Total : ".$ttotal." Registros",1,1,"L",0);
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "bairros"){
    $sql = "
	select j13_codi, j13_descr, j13_codant
	from bairro
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de bairros em ordem alfabética";
	  $sql .= "
	  order by j13_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Nome do bairro","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo Anterior","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j13_descr")),"B",0,"L",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codi"),"B",0,"C",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codant"),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head6 = "Relatório de bairros em ordem numérica";
	  $sql .= "
	  order by j13_codi
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(30,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Nome do bairro","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo Anterior","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codi"),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j13_descr")),"B",0,"L",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codant"),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "setor"){
    $sql = "
	select j30_codi, j30_descr, j30_alipre, j30_aliter
	from setor
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de setor em ordem alfabética";
	  $sql .= "
	  order by j30_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Setor","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_alipre","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_aliter","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j30_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_codi"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_alipre"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_aliter"),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de setor em ordem numérica";
	  $sql .= "
	  order by j30_codi
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Setor","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_alipre","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_aliter","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_codi"),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j30_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_alipre"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_aliter"),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "grupoCaracteristica"){
    $sql = "
	select j32_grupo, j32_descr, j32_tipo
	from cargrup
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de Grupo Característica em ordem alfabética";
	  $sql .= "
	  order by j32_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Tipo","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j32_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_tipo")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de Grupo Característica em ordem numérica";
	  $sql .= "
	  order by j32_grupo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Tipo","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_grupo")),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j32_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_tipo")),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "caracteristica"){
    $sql = "
	select j31_codigo, j31_descr, j31_grupo, j31_pontos
	from caracter
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de Característica em ordem alfabética";
	  $sql .= "
	  order by j31_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de Característica em ordem numérica";
	  $sql .= "
	  order by j31_codigo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "grupo") {
	  $head5 = "Relatório de Característica em ordem de grupo";
	  $sql .= "
	  order by j31_grupo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = db_query($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	}
  }
  $pdf->Output();