<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$instit     = db_getsession("DB_instit");
$valor_hist = '';

if (isset($campo)){
   $tipodebito = ' and k22_tipo in ('.str_replace('-',',',$campo).')';
}else{
   $tipodebito = '';
}

if(isset($data)){
  if(!checkdate(substr($data,5,2),substr($data,8,2),substr($data,0,4))){
     db_redireciona('db_erros.php?fechar=true&db_erro=Data Inválida ( '.$data.' ). Verifique!');
  }
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=Data Inválida ( '.$data.' ). Verifique!');
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$head1 = "SECRETARIA DA FAZENDA";
$head3 = "TOTAL DOS DÉBITOS POR CONTRIBUINTE";

$linha   = 60;
$TPagina = 40;

if ($ordemtipo == 'asc'){
   $ascend = 'Ascendente';
 } else {
   $ascend = 'Descendente';
}
if ($numerolista != ''){
     $limite = ' limit '.$numerolista;
	 $head6 = 'Total de Listados : '.$numerolista.'  em ordem '.$ascend;
}else {
     $limite = '';
	 $head6 = 'Total de Listados : Todos em ordem '.$ascend;
}

if ($quebrar == 't'){
   $quebra1 = ', k22_tipo';
}else{
   $quebra1 = '';
}
if ($origem == "vencidos") {
	$whereorigem = " k22_dtvenc < '" . date("Y-m-d",db_getsession("DB_datausu")) . "'";
} else {
	$whereorigem = " 1=1";
}

if ($grupo == 'nome'){

  if ($ordem == 'numerica' || $ordem == "tipo"){
    $ordem = 'k22_numcgm';
  }
  
  $sql  = " select *                                                   			             ";
  $sql .= "	  from (                                                           				 ";
  $sql .= " 					select k22_data,                                    	     ";
  $sql .= " 				   		   k22_numcgm as um,                                 	 ";
  $sql .= " 						   z01_nome as dois,                                     ";
  $sql .= "               round(sum(valor_hist),2) as valor_hist,                                                            ";
  $sql .= "						   round(sum(valor),2) as valor                          	 ";
  $sql .= "			   			   $quebra1                                            		 ";
  $sql .= "					 from (                                                    		 ";
  $sql .= "					  		select k22_data,                                     	 ";
  $sql .= "								   k22_numcgm,                                       "; 
  $sql .= "                        sum(k22_vlrhis) as valor_hist, " ;
  $sql .= "								   sum(k22_vlrcor + k22_juros + k22_multa) as valor  "; 
  $sql .= "								   $quebra1                                          ";
  $sql .= "							  from debitos                                           ";
  $sql .= "					   		 where k22_data   = '$data'                              ";
  $sql .= "                               and k22_instit = $instit             				 ";
  $sql .= "                               and $whereorigem                     				 ";
  $sql .= "							   $tipodebito                                           ";
  $sql .= "						  group by k22_data,                                     	 ";
  $sql .= "						  		   k22_numcgm                                        ";
  $sql .= "						  		    $quebra1                                         "; 
  $sql .= "						  ) as x                                                 	 ";
  $sql .= "					  	  inner join cgm on z01_numcgm = k22_numcgm            		 ";
  $sql .= "					where valor between $valorminimo and $valormaximo          		 "; 
  $sql .= "					  and valor > 0                                            		 ";
  $sql .= "				 group by k22_data,                                          		 ";
  $sql .= "				 		  k22_numcgm,                                            	 ";
  $sql .= "				 		  z01_nome                                               	 ";
  $sql .= "				 		  $quebra1                                               	 ";
  $sql .= "				 order by $ordem                                             		 ";
  $sql .= "				 		  $ordemtipo                                             	 ";
  $sql .= "				  ) as yyy $limite                                           		 ";

   $head5 = 'Ordem: alfabética - ' . ($origem == "vencidos"?"somente debitos vencidos":"debitos vencidos e a vencer");
   $cab1  = 'Numcgm';
   $cab2  = 'Nome';
}elseif ($grupo == 'inscr'){

  if ($ordem == 'numerica' || $ordem == "tipo"){
      $ordem = 'k22_inscr';
  }
 
  $sql  = "select *                                                                          				             ";
  $sql .= "  from (                                                                                       				 ";
  $sql .= "				 select k22_data,                                                                       		 ";
  $sql .= "								k22_numcgm,                                                                      ";
  $sql .= "								k22_inscr as um,                                                                 ";
  $sql .= "								z01_nome as dois,                                                                ";
  $sql .= "               round(sum(valor_hist),2) as valor_hist,                                                            ";
  $sql .= "								round(sum(valor),2) as valor                                                     ";
  $sql .= "								$quebra1                                                                         ";
  $sql .= "					 from ( select k22_data,                                                              		 ";
  $sql .= "									 			 k22_numcgm,                                                     ";
  $sql .= "												 k22_inscr,                                                      ";
  $sql .= "                        sum(k22_vlrhis) as valor_hist, " ;
  $sql .= "												 sum(k22_vlrcor + k22_juros + k22_multa) as valor $quebra1       ";
  $sql .= "								 	  from debitos                                                               ";
  $sql .= "							 		 where k22_data = '$data'                                                    ";
  $sql .= "                          and k22_instit = $instit                                             				 ";
  $sql .= "                          and $whereorigem                                                     				 ";
  $sql .= "                          and k22_inscr is not null                                            				 ";
  $sql .= "                          and k22_inscr <> 0                                                   				 ";
  $sql .= "												     $tipodebito                                                 ";
  $sql .= "								group by k22_data,                                                               ";
  $sql .= "                              k22_numcgm,                                                      				 ";
  $sql .= "                              k22_inscr $quebra1 ) as x                                        				 ";
  $sql .= "								inner join cgm on z01_numcgm = k22_numcgm                                        ";
  $sql .= "					where valor between $valorminimo and $valormaximo                                     		 ";
  $sql .= "						and valor > 0                                                                       	 ";
  $sql .= "					group by k22_data, k22_numcgm, k22_inscr,z01_nome $quebra1 order by $ordem $ordemtipo 		 ";
  $sql .= ") as yyy $limite                                                                               				 "; 

   $cab1  = 'Incrição';
   $cab2  = 'Nome';

}elseif ($grupo == 'matric'){

  if ($ordem == 'numerica' || $ordem == "tipo"){
      $ordem = 'k22_matric';
  }

  $sql  = " select *                                                                                                           "; 
  $sql .= "   from (                                                                                                           ";
  $sql .= "          select k22_data,                                                                                          ";
  $sql .= "                 k22_numcgm,                                                                                        ";
  $sql .= "                 k22_matric as um,                                                                                  ";
  $sql .= "                 z01_nome as dois,                                                                                  ";
  $sql .= "                 round(sum(valor_hist),2) as valor_hist,                                                            ";
  $sql .= "                 round(sum(valor),2) as valor                                                                       ";
  $sql .= "                 $quebra1                                                                                           ";
  $sql .= "          from ( select k22_data, k22_numcgm, k22_matric, sum(k22_vlrhis) as valor_hist,                           ";
  $sql .= "                                       sum(k22_vlrcor + k22_juros + k22_multa) as valor $quebra1                    ";
  $sql .= "                                       from debitos                                                                 ";
  $sql .= "                                       where k22_data = '$data' and $whereorigem  and k22_instit = $instit          ";
  $sql .= "                                         and k22_matric is not null and  k22_matric <> 0                            ";
  $sql .= "                                         $tipodebito                                                                ";
  $sql .= "                                       group by k22_data, k22_numcgm, k22_matric $quebra1 ) as x                    ";
  $sql .= "                inner join cgm on z01_numcgm = k22_numcgm                                                           ";
  $sql .= "          where valor between $valorminimo and $valormaximo                                                         ";
  $sql .= "            and valor > 0                                                                                           ";
  $sql .= "          group by k22_data, k22_numcgm, k22_matric,z01_nome $quebra1 order by $ordem $ordemtipo) as yyy $limite    ";

  $cab1  = 'Matrícula';
  $cab2  = 'Nome';

}else{
  
  if ($ordem == 'z01_nome'){
    $ordem = 'k00_descr';
  }

  if ($ordem == 'numerica' || $ordem == 'tipo'){
    $ordem = 'k22_tipo';
  }

  $sql = "select * from	(
 					 	  select k22_data, 
							 	 k22_tipo as um, 
							 	 k00_descr as dois,
								 round(sum(k22_vlrcor + k22_juros + k22_multa),2) as valor  
							from debitos
								 inner join arretipo on k00_tipo = k22_tipo 
						   where k22_data   = '$data' $tipodebito and $whereorigem 
						     and k22_instit = $instit
						group by k22_data, 
								 k22_tipo, 
								 k00_descr
							order by $ordem $ordemtipo
					 	 ) as yyy 
				  where valor between $valorminimo and $valormaximo 
			  	 $limite";
   
   $cab1  = 'Tipo';
   $cab2  = 'Descrição';
}
//echo $sql;exit;
$head7 = 'Valores entre :  '.trim(db_formatar($valorminimo,'f')).'   e   '.trim(db_formatar($valormaximo,'f'));
$head8 = 'Posição em : '.db_formatar($data,'d');
$result1 = pg_exec($sql);
$resultnrows = pg_numrows($result1);
if( $resultnrows == 0 ){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não há valores calculados na data ( '.$data.' ). Verifique!');
}

$ttvlrhis=0;
$ttvlrcor=0;
$ttvlrjuros=0;
$ttvlrmulta=0;
$ttvlrdesconto=0;
$tttotal=0;
$totreg = 0;
$preenc = 0;
$xborda = 0;
$pdf->SetFillColor(220);
$col  = array();
$cor = 240;
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(220);
$pdf->Cell(15,05,$cab1,"TB",0,"C",1);
$pdf->Cell(70,05,$cab2,"TB",0,"C",1);
if ($quebrar == 't' && $grupo != 'tipo'){
    $pdf->Cell(30,05,"Valor","TB",0,"R",1);
    $pdf->cell(10,05,'Tipo',"TB",0,"C",1);
    $pdf->cell(40,05,'Descrição',"TB",1,"C",1);
}else{
   $pdf->Cell(30,05,"Valor Hist.","TB",0,"R",1);
   $pdf->Cell(30,05,"Valor","TB",1,"R",1);
}
//$data = array();
for($yy=0;$yy<$resultnrows;$yy++){
  db_fieldsmemory($result1,$yy);
//  if ($yy % 2 == 0){
//      $preenc = 1;
//  }else {
//      $preenc = 0;
//  }
  if ($pdf->gety() > $pdf->h -30 ){
     $linha = 0;
     $pdf->AddPage();
     $pdf->SetFont('Arial','B',8);
     $pdf->SetFillColor(220);
     $pdf->Cell(15,05,$cab1,"TB",0,"C",1);
     $pdf->Cell(70,05,$cab2,"TB",0,"C",1);
     if ($quebrar == 't' && $grupo != 'tipo'){
       $pdf->Cell(30,05,"Valor","TB",0,"R",1);
       $pdf->cell(10,05,'Tipo',"TB",0,"C",1);
       $pdf->cell(40,05,'Descrição',"TB",1,"C",1);
     }else{
       $pdf->Cell(30,05,"Valor Hist.","TB",0,"R",1);      
       $pdf->Cell(30,05,"Valor","TB",1,"R",1);
     }
  }
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(15,04,$um,$xborda,0,"R",0);
  $pdf->Cell(70,04,$dois,$xborda,0,"L",$preenc);
  if ($quebrar == 't' && $grupo != 'tipo'){
     $pdf->Cell(30,04,db_formatar($valor,'f'),$xborda,0,"R",$preenc);
     $sql = "select k00_descr from arretipo where k00_tipo = $k22_tipo";
//     echo pg_result(pg_exec($sql),0,'k02_descr');exit;
     $pdf->cell(10,05,$k22_tipo,$xborda,0,"C",$preenc);
     $pdf->cell(40,05,pg_result(pg_exec($sql),0,'k00_descr'),$xborda,1,"L",$preenc);
     
  }else{
     $pdf->Cell(30,04,db_formatar($valor_hist,'f'),$xborda,0,"R",$preenc);
     $pdf->Cell(30,04,db_formatar($valor,'f'),$xborda,1,"R",$preenc);
  }  
  $tttotal += $valor;
  $ttvlrhis += $valor_hist;
  $totreg  += 1;

  
  $dt["\"".$dois."\""] = $valor;
  $cor -= 15;
  if ( $cor < 80 )
     $cor = 248;
  $col[$yy] = array($cor,$cor,$cor);

}
  $pdf->SetFont('Arial','B',8);
//  $pdf->Cell(85,05,'',"TB",0,"R",1);
  $pdf->Cell(85,05,'TOTAL  '.$totreg." Registros","TB",0,"L",1);
  if ($quebrar == 't' && $grupo != 'tipo'){
    $pdf->Cell(30,05,db_formatar($tttotal,'f'),"TB",0,"R",1);
    $pdf->cell(10,05,'',"TB",0,"C",1);
    $pdf->cell(40,05,'',"TB",1,"C",1);
  }else{
    $pdf->Cell(30,05,db_formatar($ttvlrhis,'f'),"TB",0,"R",1);
    $pdf->Cell(30,05,db_formatar($tttotal,'f'),"TB",1,"R",1);
  }
////////////////// GRAFICOS /////////////////
if ($grupo == 'tipo'){

   $data = array();
   for($i=0;$i<pg_numrows($result1);$i++){
     $data[pg_result($result1,$i,'dois')] = pg_result($result1,$i,'valor');
   }
   //$data = array('Parcelamento do Foro' => 3229.78, 'Parcelamento de Diversos' => 4479.89, 'Alvara' => 495,'Parcelamentos de Melhorias'=> 29264.32);
   //Pie chart
   $pdf->AddPage();
   $pdf->SetFont('Arial', 'BIU', 10);
   $pdf->Cell(0, 5, '1 - Gráfico Comparativo', 0, 1);
   $pdf->Ln(8);

   $pdf->SetFont('Arial', '', 6);
   $valX = $pdf->GetX();
   $valY = $pdf->GetY();

   $pdf->SetXY(10, $valY+30);

   $col01=array(010,255,050);
   $col02=array(220,000,100);
   $col03=array(030,111,150);
   $col04=array(200,225,000);
   $col05=array(050,222,250);
   $col06=array(180,205,200);
   $col07=array(070,005,150);
   $col08=array(255,100,100);
   $col09=array(090,045,050);
   $col10=array(000,165,010);
   $col11=array(110,085,060);
   $col12=array(120,145,120);
   $col13=array(130,125,180);
   $col14=array(255,125,240);
   $col15=array(205,165,200);
   $col16=array(160,105,150);
   $col17=array(165,205,100);
   $col18=array(180,085,050);
   $col19=array(125,255,010);
   $col20=array(200,065,060);

//   $pdf->PieChart(180, 60, $dt, '%l - %v - (%p)', $col);
   $pdf->PieChart(180, 60, $dt, '%l - %v - (%p)', array($col01,$col02,$col03,$col04,$col05,$col06,$col07,$col08,$col09,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20));
   $pdf->SetXY($valX, $valY + 40);
   
   /*//Bar diagram
   $pdf->SetFont('Arial', 'BIU', 12);
   //$pdf->Cell(0, 5, '2 - Gráfico de Barras', 0, 1);
   $pdf->Ln(8);
   $valX = $pdf->GetX();
   $valY = $pdf->GetY();
   $pdf->SetXY($valX, $valY + 10);
   $pdf->BarDiagram(190, 70, $dt, '%l : %v (%p)', array(255,175,100));
   $pdf->SetXY($valX, $valY + 80);
   */
} else {   

  if (isset($campo)){

    $pdf->ln();

    $pdf->Cell(50,05,"TIPOS DE DEBITOS ESCOLHIDOS:","",1,"L",1);
 
    $tiposdeb = split("-",$campo);

    for ($x = 0; $x < count($tiposdeb); $x++) {
      $sql = "select k00_tipo, k00_descr from arretipo where k00_tipo = " . $tiposdeb[$x];
      $result = pg_exec($sql);
      db_fieldsmemory($result, 0);
      
      $pdf->Cell(50 + ($x*2), 05, "$k00_tipo - $k00_descr", "", 1, "L", 0);
    }
    
  } else {
    $pdf->Cell(85,05,"TODOS OS TIPOS DE DEBITOS ESCOLHIDOS","",0,"L",1);
  }

}


$pdf->Output();
?>