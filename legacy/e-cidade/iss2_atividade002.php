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

    $sql = " select *
               from ativid
                    left join clasativ on q03_ativ = q82_ativ
          	        left join ativtipo on q03_ativ = q80_ativ
          	        left join classe   on q82_classe = q12_classe
          	        left join tipcalc  on q80_tipcal = q81_codigo";

    $head4 = "RELATÓRIO DAS ATIVIDADES";

  	if ($opcaoOrdem == "alfabetica") {

  	  $head5 = "Ordem alfabética";
  	  $sql .= " order by q03_descr" ;
  	} else if ($opcaoOrdem == "numerica") {

  	  $head5 = "Ordem Numérica";
  	  $sql .= " order by q03_ativ" ;
    }
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(235);

    $result = db_query($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',8);
    $linha = 60;
    $TotPag = 0;
    for($i=0;$i<$num;$i++) {

      if($linha++>65){

         $linha = 0;
         $pdf->AddPage("L");
         $pdf->SetFont('Arial','B',8);
         $pdf->Cell(12,4,"Código",1,0,"C",1);
         $pdf->Cell(80,4,"Descrição",1,0,"C",1);
	       $pdf->Cell(60,4,"Classe",1,0,"C",1);
	       $pdf->Cell(120,4,"Tipo de Cálculo Configurado",1,1,"C",1);
         $pdf->SetFont('Courier','B',8);
         $pdf->SetTextColor(0,0,0);
      }
      $pdf->Cell(12,4,pg_result($result,$i,"q03_ativ"),"B",0,"C",0);
      $pdf->Cell(80,4,pg_result($result,$i,"q03_descr"),"B",0,"L",0);
      $pdf->Cell(60,4,pg_result($result,$i,"q12_descr"),"B",0,"L",0);
      $pdf->Cell(120,4,pg_result($result,$i,"q81_descr"),"B",1,"L",0);
      $TotPag += 1;
    }

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(25,10,"",0,1,"C",0);
    $pdf->Cell(25,4,"Total de Registros",0,0,"C",0);
    $pdf->Cell(25,4,$TotPag,0,1,"C",0);

 $pdf->Output();