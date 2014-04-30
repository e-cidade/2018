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
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

//$datai = '2005-01-01';
//$dataf = '2005-01-31';


$head3 = "RELATÓRIO DE RECEITAS PAGAS POR FORNECEDOR";

$head5 = "Período : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');

$sql ="
select	
    e.z01_numcgm,
	e.z01_nome,
	e.z01_ender,
	e.z01_cgccpf,
	to_char(k00_dtpaga,'MM') as mes,
	sum(f.k00_valor) as valor
from arrepaga f
	inner join tabrec g		on g.k02_codigo  = f.k00_receit
	inner join cgm e                on f.k00_numcgm = e.z01_numcgm
			left outer join (select distinct on (k00_numpre) k00_numpre, matinscr 
					 from
								  (select k00_numpre,'M-'||k00_matric as matinscr
								   from arrematric
								   union
								   select k00_numpre,'I-'||k00_inscr as marinscr
								   from arreinscr) 
				   as x) 
					 as c on c.k00_numpre = f.k00_numpre
			left outer join (select distinct on (k00_numpre) k00_numpre, k00_histtxt from arrehist) as d on d.k00_numpre = f.k00_numpre
	where k00_dtpaga between '$datai' and '$dataf' and
	      k02_codigo = $k02_codigo
    group by 
	e.z01_numcgm,
	e.z01_nome,
	e.z01_ender,
	e.z01_cgccpf,
	to_char(k00_dtpaga,'MM') 
    order by e.z01_nome 
  ";
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos para a receita '.$codrec.' no período de '.db_formatar($datai,'d').' a '.db_formatar($dataf,'d'));

}
$linha = 0;
$pre = 0;
$total_taxa = 0;
$total_geral = 0;
$pagina = 0;
$numcgm = 0;
for($i=0;$i<$xxnum;$i++) {
  db_fieldsmemory($result,$i);

  if ($pdf->gety() > $pdf->h - 30 || $i ==0 ){
	 $pdf->AddPage('L');
	 $pdf->SetFont('Arial','B',7);
	 $pdf->Cell(60,6,"NOME",1,0,"C",0);
	 $pdf->Cell(100,6,"ENDERECO",1,0,"C",0);
	 $pdf->Cell(50,6,"CNPJ/CPF",1,0,"C",0);
	 $pdf->Cell(20,6,"MÊS",1,0,"C",0);
	 $pdf->Cell(20,6,"VALOR",1,1,"C",0);
  }
  $pdf->SetFont('Arial','',7);
  if($numcgm != $z01_numcgm){
    if($numcgm !=0>0){
	    $pdf->cell(210,4,"",0,0,"L",$pre);
        $pdf->cell(20,4,"Total.",0,0,"L",$pre);
        $pdf->cell(20,4,db_formatar($total_taxa,'f'),0,1,"R",$pre);
        $total_taxa = 0;
		$pdf->ln(1);
	  } 
      $numcgm = $z01_numcgm;
      $pdf->cell(60,4,substr($z01_nome,0,35),0,0,"L",$pre);
      $pdf->cell(100,4,$z01_ender,0,0,"L",$pre);
      $pdf->cell(50,4,$z01_cgccpf,0,0,"L",$pre);
      $pdf->cell(20,4,$mes,0,0,"L",$pre);
      $pdf->cell(20,4,db_formatar($valor,'f'),0,1,"R",$pre);
  }else{
      $pdf->cell(210,4,"",0,0,"L",$pre);
      $pdf->cell(20,4,$mes,0,0,"L",$pre);
      $pdf->cell(20,4,db_formatar($valor,'f'),0,1,"R",$pre);
 
  }
  $total_geral += $valor;
  $total_taxa += $valor;
}
$pdf->SetFont('Arial','B',7);
$pdf->Cell(230,6,"TOTAL GERAL : ",1,0,"L",0);
$pdf->Cell(20,6,db_formatar($total_geral,'f'),1,1,"R",0);

$pdf->Output();

?>