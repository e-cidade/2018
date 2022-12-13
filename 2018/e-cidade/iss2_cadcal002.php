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

    $sql = " select q85_codigo, q85_descr,q85_uniref,
                    q85_dtoper,q85_codven,case when q85_var is true then 'Sim' else 'Não' end as q85var,
		    case when q85_fixmes is true then 'Sim' else 'Não' end as q85fixmes, q85_forcal from cadcalc " ;
    $head4 = "RELATÓRIO DO CADASTRO DE CÁLCULOS";
	if ($opcaoOrdem == "alfabetica") {

	  $head5 = "Ordem alfabética";
	  $sql .= " order by q85_descr" ;
	} else if ($opcaoOrdem == "numerica") {

	  $head5 = "Ordem Numérica";
	  $sql .= " order by q85_codigo" ;
  }
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(24,135,18);

    $result = db_query($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',8);
    $linha = 60;
    $TotPag = 0;
    for($i=0;$i<$num;$i++) {

      if($linha++>57){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(12,4,"Codigo","LRT",0,"C",0);
         $pdf->Cell(80,4,"Descricao","LRT",0,"C",0);
         $pdf->Cell(18,4,"Unid.","LRT",0,"C",0);
         $pdf->Cell(20,4,"Data de","LRT",0,"C",0);
         $pdf->Cell(10,4,"Cod.","LRT",0,"C",0);
         $pdf->Cell( 6,4,"Var","LRT",0,"C",0);
         $pdf->Cell(10,4,"Gerar","LRT",0,"C",0);
         $pdf->Cell(10,4,"Forma","LRT",1,"C",0);

         $pdf->Cell(12,4,"","LRB",0,"C",0);
         $pdf->Cell(80,4,"","LRB",0,"C",0);
         $pdf->Cell(18,4,"Ref.","LRB",0,"C",0);
         $pdf->Cell(20,4,"Operação","LRB",0,"C",0);
         $pdf->Cell(10,4,"Vcto","LRB",0,"C",0);
         $pdf->Cell( 6,4,"","LRB",0,"C",0);
         $pdf->Cell(10,4,"Fixado","LRB",0,"C",0);
         $pdf->Cell(10,4,"Calculo","LRB",1,"C",0);
      }
      $pdf->Cell(12,4,pg_result($result,$i,"q85_codigo"),"0",0,"C",0);
      $pdf->Cell(80,4,pg_result($result,$i,"q85_descr"),"0",0,"L",0);
      $pdf->Cell(18,4,number_format(pg_result($result,$i,"q85_uniref"),2,",","."),"0",0,"R",0);
      $pdf->Cell(20,4,db_formatar(pg_result($result,$i,"q85_dtoper"),"d"),"0",0,"C",0);
      $pdf->Cell(10,4,number_format(pg_result($result,$i,"q85_codven"),0,",","."),"0",0,"C",0);
      $pdf->Cell( 6,4,pg_result($result,$i,"q85var"),"0",0,"C",0);
      $pdf->Cell(10,4,pg_result($result,$i,"q85fixmes"),"0",0,"C",0);
      $pdf->Cell(10,4,number_format(pg_result($result,$i,"q85_forcal"),0,",","."),"0",1,"C",0);
      $TotPag += 1;
    }

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(25,10,"",0,1,"C",0);
    $pdf->Cell(25,4,"Total de Registros",0,0,"C",0);
    $pdf->Cell(25,4,$TotPag,0,1,"C",0);

 $pdf->Output();