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

set_time_limit(0);
include("libs/db_sql.php");
require("fpdf151/pdf.php");
include("classes/db_tabativ_classe.php");
$cltabativ = new cl_tabativ;
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);exit;

$info       = "Total";
$ativs      = "";
$atividades = "";
$where      = "";
if(isset($chaves) && $chaves != ""){
  $at = split("#",$chaves);
  $or = "";
  for($i=0;$i<sizeof($at);$i++){
    $ativs .= $or." q03_ativ = ".$at[$i]." ";
    $or = " or ";
  }
  $atividades = "and (".$ativs.")";
}

if(isset($q02_inscr) && $q02_inscr != ""){
  $where .= " and issbase.q02_inscr = $q02_inscr ";  
}
if(isset($j14_codigo) && $j14_codigo != ""){
  $where .= " and issruas.j14_codigo = $j14_codigo ";
}

if ($tipo == "a") {
  $where .= " and issbase.q02_dtbaix is null";
} elseif ($tipo == "b") {
  $where .= " and issbase.q02_dtbaix is not null";
}
//die($atividades);

//=======================================================================================================================================

$pdf = new pdf();
$pdf->SetFillColor(255);
$pdf->Open();
$pdf->AliasNbPages();

$sql = "
select  
issbase.q02_inscr, 
issbase.q02_dtbaix,
cgm.z01_nome,	
cgm.z01_nomefanta,
issruas.j14_codigo, 
j14_nome, 
q03_ativ, 
ativid.q03_descr,
empresa.z01_numero,
empresa.z01_compl
from tabativ
inner join issbase     on issbase.q02_inscr  = tabativ.q07_inscr
inner join issruas     on issruas.q02_inscr  = tabativ.q07_inscr
inner join ruas        on issruas.j14_codigo = ruas.j14_codigo
inner join ativid      on ativid.q03_ativ    = tabativ.q07_ativ
inner join cgm         on cgm.z01_numcgm     = issbase.q02_numcgm
inner join empresa     on empresa.q02_inscr  = issbase.q02_inscr
where 1=1
$where
$atividades
group by 
issbase.q02_inscr,
issbase.q02_dtbaix,
cgm.z01_nomefanta, 
cgm.z01_nome,
issruas.j14_codigo,
j14_nome,
q03_ativ,
ativid.q03_descr,
empresa.z01_numero,
empresa.z01_compl
order by issbase.q02_inscr,
issruas.j14_codigo ";

//die($sql);

$result = pg_query($sql) or die($sql);
//db_criatabela($result);exit;             
$numlinhas = pg_numrows($result);
if($numlinhas == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrado nenhum registro para o filtro selecionado.');
}
$head1 = 'Relátorio de contribuintes por atividade';
$inscrant = 0;
$primeiravolta = 't';
$troca = 1;
$tottotal = 0;
for($i=0;$i<$numlinhas;$i++){
  db_fieldsmemory($result,$i);
  $y = $pdf->gety();
  $xx = ($pdf->h-$y);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0){
    $pdf->addpage('L');
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFillColor(236);
    $pdf->setfont('arial','b',8);
    $pdf->Cell(15,5,"Inscrição",1,0,"C",1);
    $pdf->Cell(70,5,"Razão Social",1,0,"C",1);
    $pdf->Cell(75,5,"Nome fantasia",1,0,"C",1);
    $pdf->Cell(80,5,"Endereço",1,0,"C",1);
    $pdf->Cell(40,5,"Complemento",1,1,"C",1);
    
    $pdf->Cell(15,5,"",1,0,"C",1);
    $pdf->Cell(70,5,"Data da baixa",1,0,"L",1);
    $pdf->Cell(75,5,"Atividade(s)",1,0,"L",1);
    $pdf->Cell(105,5,"Exerc/ ",1,0,"R",1);
    $pdf->Cell(15,5,"Total",1,1,"R",1);
    $troca = 0;
    $pdf->SetFont('Arial','',8);
  }
  if($inscrant != $q02_inscr){
  	if($primeiravolta == 'f'){
  		
//      $sqltotal = "select extract(year from k00_dtoper), 
//      sum(k00_valor) 
//      from arreinscr 
//      inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre 
//      where k00_inscr = $inscrant
//      group by extract (year from k00_dtoper)";
//      $rsTotal =  pg_query($sqltotal);
//      $toti    =  pg_numrows($rsTotal);
      if($toti > 0 and $debitos != "s") {
        for($ii=0;$ii<$toti;$ii++){
          db_fieldsmemory($rsTotal,$ii);
          $pdf->SetFont('Arial','B',8);
          //str_pad(db_formatar($sum, 'f'), 10, " ", STR_PAD_LEFT);
//die('fdasfas');
          //$valkarina = str_pad(db_formatar($sum, 'f'), 10, " ", STR_PAD_LEFT);
          //($str, $tipo, $caracter = " ", $quantidade = 0, $TipoDePreenchimento = "e", $casasdecimais = 2)
         // $valkarina = db_formatar($sum, 'f',' ',10,'e');
          $pdf->Cell(265,4,"$date_part / ",0,0,"R",0);
          $pdf->Cell(15,4,db_formatar($sum, "f"),0,1,"R",0);
          //$pdf->Cell(280,4,"$date_part / ".$valkarina,0,1,"R",0);
          $pdf->SetFont('Arial','',8);
        }
      } elseif ($debitos != "d") {
        $pdf->Cell(280,4,"Sem debitos encontrados","0",1,"R",0);
      }
    }
    		
		$sqltotal = "select extract(year from k00_dtoper), 
		sum(k00_valor) 
		from arreinscr 
		inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre 
		where k00_inscr = $q02_inscr
		group by extract (year from k00_dtoper)";
		//die($sqltotal);
		$rsTotal =  pg_query($sqltotal);
		$toti    =  pg_numrows($rsTotal);
		$passa = true;	
		
		if ($debitos == "s" and $toti > 0) {
			$passa = false;
		} elseif ($debitos == "d" and $toti == 0) {
		  $passa = false;
		 // die("passa=$passa  deb= $debitos");
		}
		if(($numlinhas==1)&&($toti==0)){
			db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrado nenhum registro para o filtro selecionado.');
		}
		if ($passa == true) {
			$pdf->Cell($pdf->w-15,1,"","T",1,"R",0);
			$pdf->Cell(15,4,$q02_inscr,0,0,"C",1);
			$pdf->Cell(70,4,$z01_nome,0,0,"L",1);
			$pdf->Cell(75,4,@$z01_nomefanta,0,0,"L",1);
			$pdf->Cell(80,4,$j14_codigo."-".$j14_nome ." N".CHR(176)."". $z01_numero,0,0,"L",1);
			$pdf->Cell(40,4,$z01_compl,0,1,"C",1);
			$tottotal ++;
			$pdf->Cell(15,4,"",0,0,"C",0);
			$pdf->Cell(70,4,db_formatar($q02_dtbaix, "d"),0,0,"L",0);
			$pdf->Cell(75,4,$q03_ativ."-".$q03_descr,0,0,"L",0);
			$pdf->Cell(120,4,"",0,1,"L",0);
		}
		$inscrant = $q02_inscr;
		$primeiravolta = 'f';
		
  }else{
    $pdf->Cell(15,4,"",0,0,"C",0);
    $pdf->Cell(70,4,"",0,0,"L",0);
    $pdf->Cell(75,4,$q03_ativ."-".$q03_descr,0,0,"L",0);
    $pdf->Cell(120,4,"",0,1,"L",0);
  }
}

$pdf->SetFont('Arial','B',11);
$pdf->Cell(250,4,"Total de registros : ".$tottotal,0,1,"R",0);

$pdf->Output();

?>