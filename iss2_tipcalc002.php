<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where = "";

if(!empty($lista)) {
  $in = ($param_where=="S")?"in":"not in";

  $where = "where q81_cadcalc {$in} ({$lista})";
}


$sql = "select * from tipcalc left join cadcalc on q85_codigo = q81_cadcalc {$where} order by q81_codigo";
$head4 = "RELATÓRIO DOS TIPOS DE CÁLCULOS";
$head5 = "Ordem Numérica";

$pdf = new PDF('P');
// abre a classe
$pdf->Open();
// abre o relatorio
$pdf->AliasNbPages();
// gera alias para as paginas
//    $pdf->AddPage(); // adiciona uma pagina

//    $pdf->SetFont('Courier','B',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(24,135,18);

$result = pg_exec($sql);
$num = pg_numrows($result);
$pdf->SetFont('Courier','B',4);
$linha = 60;
$TotPag = 0;
for ($i=0; $i<$num; $i++) {
  
  if ($linha++>18) {
    $linha = 0;
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',6);
    
    $pdf->Cell(87,4,"",0,0,"C",0);
    $pdf->Cell(52,4,"-- Exercicio Anterior --",1,0,"C",0);
    $pdf->Cell(52,4,"-- Exercicio Atual --",1,1,"C",0);
    
    $pdf->Cell(04,4,"Cod","LRT",0,"C",0);
    $pdf->Cell(40,4,"Descricao","LRT",0,"C",0);
    $pdf->Cell(10,4,"Calculo ","LRT",0,"C",0);
    $pdf->Cell(06,4,"Int",1,0,"C",0);
    $pdf->Cell(04,4,"Tp",1,0,"C",0);
    $pdf->Cell(06,4,"Cod.","LRT",0,"C",0);
    $pdf->Cell(06,4,"Qtda","LRT",0,"C",0);
    $pdf->Cell(06,4,"Qtda","LRT",0,"C",0);
    $pdf->Cell(05,4,"Frm","LRT",0,"C",0);
    $pdf->Cell(06,4,"Rec","LRT",0,"C",0);
    $pdf->Cell(20,4,"Quantidade","LRT",0,"C",0);
    $pdf->Cell(26,4,"Valor ou","LRT",0,"C",0);
    $pdf->Cell(06,4,"Rec","LRT",0,"C",0);
    $pdf->Cell(20,4,"Quantidade","LRT",0,"C",0);
    $pdf->Cell(26,4,"Valor ou","LRT",1,"C",0);
    
    $pdf->Cell(04,4,"","LRB",0,"C",0);
    $pdf->Cell(40,4,"Abreviada e Completa","LRB",0,"C",0);
    $pdf->Cell(10,4,"Utilizado","LRB",0,"C",0);
    $pdf->Cell(06,4,"","LB",0,"C",0);
    $pdf->Cell(04,4,"","RB",0,"C",0);
    $pdf->Cell(06,4,"Vcto","LRB",0,"C",0);
    $pdf->Cell(06,4,"Ativ","LRB",0,"C",0);
    $pdf->Cell(06,4,"Cad.","LRB",0,"C",0);
    $pdf->Cell(05,4,"","LB",0,"C",0);
    $pdf->Cell(06,4,"","LRB",0,"C",0);
    $pdf->Cell(20,4,"Inicial / Final","LRB",0,"C",0);
    $pdf->Cell(26,4,"Aliquota","LRB",0,"C",0);
    $pdf->Cell(06,4,"","LRB",0,"C",0);
    $pdf->Cell(20,4,"Inicial / Final","LRB",0,"C",0);
    $pdf->Cell(26,4,"Aliquota","LRB",1,"C",0);
    
    $pdf->SetFont('Courier','B',7);
    $pdf->SetTextColor(0,0,0);
  }
  $pdf->Cell(04,4,pg_result($result,$i,"q81_codigo"),"0",0,"C",0);
  $pdf->Cell(40,4,pg_result($result,$i,"q81_abrev"),"0",0,"L",0);
  $pdf->Cell(10,4,(int)(pg_result($result,$i,"q81_cadcalc")),"0",0,"C",0);
  $pdf->Cell(06,4,db_formatar(pg_result($result,$i,"q81_integr"),"b"),"0",0,"C",0);
  $pdf->Cell(04,4,pg_result($result,$i,"q81_tippro"),"0",0,"C",0);
  $pdf->Cell(06,4,pg_result($result,$i,"q85_codven"),"0",0,"C",0);
  $pdf->Cell(06,4,db_formatar(pg_result($result,$i,"q81_uqtab"),"b"),"0",0,"C",0);
  $pdf->Cell(06,4,db_formatar(pg_result($result,$i,"q81_uqcad"),"b"),"0",0,"C",0);
  $pdf->Cell(05,4,pg_result($result,$i,"q81_gera"),"0",0,"C",0);
  $pdf->Cell(06,4,pg_result($result,$i,"q81_recexe"),"0",0,"R",0);
  $pdf->Cell(20,4,number_format(pg_result($result,$i,"q81_qiexe"  ),2,",","."),"0",0,"R",0);
  $pdf->Cell(26,4,number_format(pg_result($result,$i,"q81_valexe"),2,",","."),"0",0,"R",0);
  $pdf->Cell(06,4,pg_result($result,$i,"q81_recpro"),"0",0,"R",0);
  $pdf->Cell(20,4,number_format(pg_result($result,$i,"q81_qipro"),2,",","."),"0",0,"R",0);
  $pdf->Cell(26,4,number_format(pg_result($result,$i,"q81_valpro"),2,",","."),"0",1,"R",0);
  
  $pdf->Cell(04,4,"","B",0,"C",0);
  $pdf->Cell(40,4,pg_result($result,$i,"q81_descr"),"B",0,"C",0);
  $pdf->Cell(49,4,"","B",0,"C",0);
  $pdf->Cell(20,4,number_format(pg_result($result,$i,"q81_qfexe"),2,",","."),"B",0,"R",0);
  $pdf->Cell(32,4,"","B",0,"C",0);
  $pdf->Cell(20,4,number_format(pg_result($result,$i,"q81_qfpro"),2,",","."),"B",0,"R",0);
  $pdf->Cell(26,4,"","B",1,"R",0);
  $TotPag += 1;
}

$pdf->SetFont('Arial','B',4);
$pdf->Cell(25,10,"",0,1,"C",0);
$pdf->Cell(25,4,"Total de Registros",0,0,"C",0);
$pdf->Cell(25,4,$TotPag,0,1,"C",0);

$pdf->Output();

?>