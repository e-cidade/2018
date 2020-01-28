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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
include("fpdf151/pdf.php");
$db_anousu= db_getsession("DB_anousu");
$sql="select j01_matric,
             z01_nome,
			 j34_setor,j34_lote,j34_quadra
	  from iptubase
	       inner join iptucalc on j01_matric = j23_matric and j23_anousu = $db_anousu
           inner join lote on j01_idbql = j34_idbql
           inner join cgm on z01_numcgm = j01_numcgm
	  order by $ordem
";
$result=pg_query($sql);
if(pg_numrows($result)==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem matrículas Calculadas: Exercício:'.$db_anousu);
}else{


$head4 = "RELATÓRIO CÁLCULO DO IPTU ";
$head5 = "EXERCÍCIO DE ".$db_anousu;
$borda = 1; 
$bordat = 1;
$preenc = 1;
$TPagina = 57;

$pdf = new PDF("L"); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Courier','B',9);
$preenc = "0";
$linha = 0;
$pdf->Cell(15,4,"Matr",$bordat,0,"L",$preenc);
$pdf->Cell(60,4,"Nome",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"Set.",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"Quad",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"Lote",$bordat,0,"L",$preenc);
$pdf->Cell(30,4,"Valor Iptu",$bordat,0,"R",$preenc);
$pdf->Cell(30,4,"Taxa Bombeiro",$bordat,0,"R",$preenc);
$pdf->Cell(30,4,"Taxa Limpeza",$bordat,0,"R",$preenc);
$pdf->Cell(30,4,"A Pagar",$bordat,1,"R",$preenc);
$bordat = 0;
$totvlriptu=0;
$totvlrbombeiro=0;
$totvlrlimpeza=0;
$totvlrgeral=0;

for($i = 0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  //echo $j01_matric;
  if($linha > 55 ){
    $pdf->AddPage(); // adiciona uma pagina
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(235);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;
    $bordat = 1;
	$pdf->Cell(15,4,"Matr",$bordat,0,"L",$preenc);
    $pdf->Cell(60,4,"Nome",$bordat,0,"L",$preenc);
    $pdf->Cell(10,4,"Set.",$bordat,0,"L",$preenc);
    $pdf->Cell(10,4,"Quad",$bordat,0,"L",$preenc);
    $pdf->Cell(10,4,"Lote",$bordat,0,"L",$preenc);
    $pdf->Cell(30,4,"Valor Iptu",$bordat,0,"R",$preenc);
    $pdf->Cell(30,4,"Taxa Bombeiro",$bordat,0,"R",$preenc);
    $pdf->Cell(30,4,"Taxa Limpeza",$bordat,0,"R",$preenc);
    $pdf->Cell(30,4,"A Pagar",$bordat,1,"R",$preenc);
    $bordat = 0;
  }
  $linha ++;
  $sql = "select j21_receit,round(sum(j21_valor),2) as j21_valor
                  from iptucalv 
				  where j21_matric = $j01_matric and j21_anousu = $db_anousu
				  group by j21_receit";
  $resultv = pg_query($sql);
  $vlriptu = 0;
  $vlrlimpeza = 0;
  $vlrbombeiro = 0;
  $vlrgeral = 0;  
  for($x=0;$x<pg_numrows($resultv);$x++){
    db_fieldsmemory($resultv,$x);
	if($j21_receit==7){
	  $vlriptu += $j21_valor;
	  $totvlriptu += $j21_valor;
	}else if ($j21_receit == 39 ){
	  $vlrbombeiro += $j21_valor;
	  $totvlrbombeiro += $j21_valor;
	}else{
	  $vlrlimpeza += $j21_valor;
	  $totvlrlimpeza += $j21_valor;
	}
	$vlrgeral += $j21_valor;
	$totvlrgeral += $j21_valor;
  }

  $pdf->Cell(15,4,$j01_matric,$bordat,0,"L",$preenc);
  $pdf->SetFont('Courier','B',7);
  $pdf->Cell(60,4,$z01_nome,$bordat,0,"L",$preenc);
  $pdf->SetFont('Courier','B',9);
  $pdf->Cell(10,4,$j34_setor,$bordat,0,"L",$preenc);
  $pdf->Cell(10,4,$j34_quadra,$bordat,0,"L",$preenc);
  $pdf->Cell(10,4,$j34_lote,$bordat,0,"L",$preenc);
  $pdf->Cell(30,4,db_formatar($vlriptu,"f"),$bordat,0,"R",$preenc);
  $pdf->Cell(30,4,db_formatar($vlrbombeiro,"f"),$bordat,0,"R",$preenc);
  $pdf->Cell(30,4,db_formatar($vlrlimpeza,"f"),$bordat,0,"R",$preenc);
  $pdf->Cell(30,4,db_formatar($vlrgeral,"f"),$bordat,1,"R",$preenc);
}
$bordat= 'T';
$pdf->Cell(15,4,"Totais",$bordat,0,"L",$preenc);
$pdf->Cell(60,4,"",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"",$bordat,0,"L",$preenc);
$pdf->Cell(10,4,"",$bordat,0,"L",$preenc);
$pdf->Cell(30,4,db_formatar($totvlriptu,"f"),$bordat,0,"R",$preenc);
$pdf->Cell(30,4,db_formatar($totvlrbombeiro,"f"),$bordat,0,"R",$preenc);
$pdf->Cell(30,4,db_formatar($totvlrlimpeza,"f"),$bordat,0,"R",$preenc);
$pdf->Cell(30,4,db_formatar($totvlrgeral,"f"),$bordat,1,"R",$preenc);
$pdf->Output();

}
?>