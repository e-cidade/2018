<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$sql = " select q86_numcgm
               ,z01_nome
	       ,z01_ender
	       ,z01_numero
	       ,z01_compl
	       ,z01_bairro
	       ,z01_munic
          from cadescrito
         inner join cgm on q86_numcgm = z01_numcgm" ;

    $head4 = "RELATÓRIO DOS ESCRITÓRIOS CONTÁBEIS";
  	if ($opcaoOrdem == "alfabetica") {

  	  $head5 = "Ordem alfabética";
  	  $sql .= " order by z01_nome " ;
  	} else if ($opcaoOrdem == "numerica") {

  	  $head5 = "Ordem Numérica";
  	  $sql .= " order by q86_numcgm " ;
    }
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(235);
    $pdf->SetLeftMargin(10);

    $result = db_query($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',8);
    $linha = 60;
    $TotPag = 0;
    $p=0;
    for($i=0;$i<$num;$i++) {

      if($linha++>57){

         $linha = 0;
         $pdf->AddPage("L");
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(12,4,"Cgm",1,0,"C",1);
         $pdf->Cell(80,4,"Descrição",1,0,"C",1);
         $pdf->Cell(90,4,"Endereço",1,0,"C",1);
         $pdf->Cell(20,4,"Número",1,0,"C",1);
         $pdf->Cell(45,4,"Bairro",1,0,"C",1);
         $pdf->Cell(30,4,"Município",1,1,"C",1);
         $pdf->SetFont('Courier','B',8);
         $pdf->SetTextColor(0,0,0);
      }

      $pdf->Cell(12,4,pg_result($result,$i,"q86_numcgm"),0,0,"C",$p);
      $pdf->Cell(80,4,pg_result($result,$i,"z01_nome"),0,0,"L",$p);
      $pdf->Cell(90,4,pg_result($result,$i,"z01_ender"),0,0,"L",$p);
      $pdf->Cell(20,4,pg_result($result,$i,"z01_numero"),0,0,"L",$p);
      $pdf->Cell(45,4,pg_result($result,$i,"z01_bairro"),0,0,"L",$p);
      $pdf->Cell(30,4,pg_result($result,$i,"z01_munic"),0,1,"L",$p);
      $TotPag += 1;

      if($p == 0){
       $p = 1;
      }else{
       $p = 0;
      }

    }

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(25,10,"",0,1,"C",0);
    $pdf->Cell(25,4,"Total de Registros",0,0,"C",1);
    $pdf->Cell(25,4,$TotPag,0,1,"C",1);

 $pdf->Output();