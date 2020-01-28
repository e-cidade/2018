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
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$instit = db_getsession("DB_instit");
$head3 = "RELATÓRIO DE TAXAS PAGAS";
if ( $codsubrec == ''){
   $head4 = 'TODAS AS TAXAS';
   $ordem = ' order by g.codsubrec ';
   $where = ' f.k00_codsubrec is not null and ';
}else{
   $sql = "select upper(k07_descr) as k07_descr from tabdesc where codsubrec = $codsubrec and k07_instit = $instit";
   $result = pg_exec($sql);

   $head4 = $codsubrec.' - '.pg_result($result,0,'k07_descr');
   $ordem = ' order by g.codsubrec, a.k00_dtpaga, e.z01_nome ';
   $where = ' f.k00_codsubrec = '.$codsubrec.' and ';
}
$head6 = "Período: ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
if ($agrupar == 't'){
    $sql ="
            select  g.codsubrec,
                    g.k07_descr,
                    sum(a.k00_valor) as valor,
		                count(*) as count
            from recibo f
            left outer join tabdesc g on g.codsubrec  = f.k00_codsubrec 
                                     and g.k07_instit = $instit
		        inner join arrepaga a		  on a.k00_numpre = f.k00_numpre
            where $where k00_dtpaga between '$datai' and '$dataf'
            group by g.codsubrec,
                     g.k07_descr
            $ordem
	   ";
}else{
 $sql ="
	select	g.codsubrec,
		g.k07_descr,
		a.k00_numpre,
		e.z01_nome,
 		a.k00_dtpaga,
		coalesce(b.k00_matric,0) as k00_matric,
		coalesce(c.k00_inscr,0)  as k00_inscr,
		d.k00_histtxt,
		sum(a.k00_valor) as valor
	from recibo f
		left outer join tabdesc g	on g.codsubrec  = f.k00_codsubrec 
                             and g.k07_instit = $instit
		inner join arrepaga a		on a.k00_numpre   = f.k00_numpre
		inner join cgm e                on a.k00_numcgm = e.z01_numcgm
		left outer join arrematric b    on a.k00_numpre = b.k00_numpre
                left outer join arreinscr  c    on a.k00_numpre = c.k00_numpre
                left outer join arrehist   d    on a.k00_numpre = d.k00_numpre
        where $where k00_dtpaga between '$datai' and '$dataf'
	group by g.codsubrec,
		g.k07_descr,
		a.k00_numpre,
		e.z01_nome,
		k00_dtpaga,
		k00_matric,
		k00_inscr,
		d.k00_histtxt
	$ordem
      ";
}
$sql = "select * from ($sql) as x where codsubrec is not null";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos para a taxa '.$codsubrec.' no período de '.db_formatar($datai,'d').' a '.db_formatar($dataf,'d'));
}
$linha = 0;
$pre = 0;
$total_taxa = 0;
$total_geral = 0;
$count_geral = 0;
$pagina = 0;
if ($agrupar == 't'){
   $pdf->ln(2);
   $pdf->AddPage(); 
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(220);
   $pdf->SetFont('Arial','B',9);
   $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
   $pdf->Cell(80,6,"TAXA",1,0,"C",1);
   $pdf->Cell(25,6,"QUANTIDADE",1,0,"C",1);
   $pdf->Cell(25,6,"VALOR",1,1,"C",1);
   $pdf->SetFont('Arial','B',9);
   for ($i=0;$i<$xxnum;$i++){
     db_fieldsmemory($result,$i);
     if ($pdf->gety() > $pdf->h - 30 ){
        $pdf->addpage();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
        $pdf->Cell(80,6,"TAXA",1,0,"C",1);
        $pdf->Cell(25,6,"QUANTIDADE",1,0,"C",1);
        $pdf->Cell(25,6,"VALOR",1,1,"C",1);
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,4,$codsubrec,1,0,"C",$pre);
     $pdf->cell(80,4,strtoupper($k07_descr),1,0,"D",$pre);
     $pdf->cell(25,4,db_formatar($count,'s'),1,0,"R",$pre);
     $pdf->cell(25,4,db_formatar($valor,'f'),1,1,"R",$pre);
     $total_geral +=$valor;
     $count_geral +=$count;
   }
   $pdf->cell(100,4,"TOTAL GERAL",1,0,"C",0);
   $pdf->cell(25,4,db_formatar($count_geral,'s'),1,0,"R",0);
   $pdf->cell(25,4,db_formatar($total_geral,'f'),1,1,"R",0);

}else{
   $pdf->ln(2);
   $pdf->AddPage('L'); 
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(220);
   $pdf->SetFont('Arial','B',11);
   $pdf->SetFont('Arial','B',7);
   $receita = pg_result($result,0,'codsubrec').' - '.strtoupper(pg_result($result,0,'k07_descr'));
   $pdf->multicell(0,6,$receita,0,"L",0);
   $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
   $pdf->Cell(60,6,"NOME",1,0,"C",1);
   $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
   $pdf->Cell(12,6,"MAT/INSC",1,0,"C",1);
   $pdf->Cell(150,6,"HISTORICO",1,0,"C",1);
   $pdf->Cell(20,6,"VALOR",1,1,"C",1);
   $pdf->SetFont('Arial','B',9);
   
   $matins = 0;
   
   for($i=0;$i<$xxnum;$i++) {
         db_fieldsmemory($result,$i);
      if ($pdf->gety() > $pdf->h - 30 ){
	 $pdf->addpage('L');
         $pdf->SetFont('Arial','B',7);
         $pdf->multicell(0,6,$codsubrec.' - '.strtoupper($k07_descr),0,"L",0);
         $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
         $pdf->Cell(60,6,"NOME",1,0,"C",1);
         $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
         $pdf->Cell(12,6,"MAT/INSC",1,0,"C",1);
         $pdf->Cell(150,6,"HISTORICO",1,0,"C",1);
         $pdf->Cell(20,6,"VALOR",1,1,"C",1);
      }
      if ( $receita != $codsubrec.' - '.strtoupper($k07_descr) ){;
   //      $pdf->AddPage('L');
         $pdf->SetFont('Arial','B',7);
         $pdf->Cell(249,6,"TOTAL DA TAXA : ",1,0,"L",0);
         $pdf->Cell(20,6,db_formatar($total_taxa,'f'),1,1,"R",0);
         $total_taxa = 0;
         $pdf->SetFont('Arial','B',7);
         $pdf->ln(3);
         $pdf->multicell(0,6,$codsubrec.' - '.strtoupper($k07_descr),0,"L",0);
         $pdf->Cell(12,6,"NUMPRE",1,0,"C",1);
         $pdf->Cell(60,6,"NOME",1,0,"C",1);
         $pdf->Cell(15,6,"PAGTO",1,0,"C",1);
         $pdf->Cell(12,6,"MAT/INSC",1,0,"C",1);
         $pdf->Cell(150,6,"HISTORICO",1,0,"C",1);
         $pdf->Cell(20,6,"VALOR",1,1,"C",1);
      }
   
      if (!empty($k00_matric)){
         $matins = 'M-'.$k00_matric;
      }elseif (!empty($k00_inscr)){
         $matins = 'I-'.$k00_inscr;
      }else{
         $matins = 0;
      }
      $pdf->SetFont('Arial','',7);
      $pdf->cell(12,4,$k00_numpre,1,0,"R",$pre);
      $pdf->cell(60,4,substr($z01_nome,0,35),1,0,"L",$pre);
      $pdf->Cell(15,4,db_formatar($k00_dtpaga,'d'),1,0,"C",$pre);
      $pdf->cell(12,4,$matins,1,0,"L",$pre);
      $pdf->cell(150,4,substr(strtoupper($k00_histtxt),0,100),1,0,"L",$pre);
      $pdf->cell(20,4,db_formatar($valor,'f'),1,1,"R",$pre);
      $total_geral += $valor;
      $total_taxa += $valor;
      $receita = $codsubrec.' - '.strtoupper($k07_descr);
   }
   //$pdf->Ln(5);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(249,6,"TOTAL DA TAXA : ",1,0,"L",0);
   $pdf->Cell(20,6,db_formatar($total_taxa,'f'),1,1,"R",0);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(249,6,"TOTAL GERAL : ",1,0,"L",0);
   $pdf->Cell(20,6,db_formatar($total_geral,'f'),1,1,"R",0);
}   
$pdf->Output();
   
?>