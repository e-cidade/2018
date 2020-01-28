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
$clrotulo = new rotulocampo;
$clrotulo->label('j46_matric');
$clrotulo->label('j47_anousu');
$clrotulo->label('j45_descr');
$clrotulo->label('j21_valor');
$clrotulo->label('j46_perc');
$clrotulo->label('j46_hist');

db_postmemory($HTTP_SERVER_VARS);
$head1 = "RELAT�RIO DE ISEN��ES";
$head2 = ($isencoes == "cad"?"SOMENTE CADASTRADAS":"SOMENTE CALCULADAS");
$head3 = "ISENTOS DO ANO ".$anoini." AT� O ANO ".$anofin;
$head4 = "ORDEM: " . ($order == "e"?"ENDERECO":($order == "z01_nome"?"NOME":"MATR�CULA"));


if ($datai != "--"){
		if($tipodata == "dtinc"){
		   $xdatas = " and j46_dtinc between '$datai' and '$dataf' "; 
		   $tipodata2 = "DATA DE INCLUS�O DA ISEN��O";
		}else if($tipodata == "dtini"){
		   $xdatas = " and j46_dtini between '$datai' and '$dataf' ";
		   $tipodata2 ="DATA DE IN�CIO DA ISEN��O";
		}else if($tipodata == "dtfim"){
		   $xdatas = " and j46_dtfim between '$datai' and '$dataf' ";
		   $tipodata2 ="DATA DE FIM DA ISEN��O";
		}
   $head5 = "$tipodata2";
   $head6 = "PER�ODO ".db_formatar($datai,'d')." A ".db_formatar($dataf,'d');
}else{
   $xdatas = "";
   $head5 = "SEM FILTRO PARA PERIODO DEFINIDO";
}
if($isencoes == "cad"){
  $calv = false;
}elseif($isencoes == "calc"){
  $calv = true;
}
$xtipo = '';
if (isset($campo)){
  $xtipo = " and j46_tipo in (".str_replace('-',', ',$campo).")";
}
if(isset($order)){
   if($order == "e"){
     $order = "nomepri";
   }elseif($order == "matricula"){
     $order = "j46_matric";
   }
}	

if($calv == false){
  $iptucalv = "and j21_matric is null";
}else{
  $iptucalv = "";
}

$sql = "select  distinct *,
	              (select min(j47_anousu) from iptuisen join isenexe on j47_codigo = j46_codigo and j46_matric = y.j46_matric) as min,
	              (select max(j47_anousu) from iptuisen join isenexe on j47_codigo = j46_codigo and j46_matric = y.j46_matric) as max
	      from (select	j46_matric,
											j46_codigo,
											j61_codproc,
											z01_nome,
											j47_anousu,
											j45_descr,
											j46_perc,
											j21_valor,
											min(j46_hist) as j46_hist,
											z01_compl,
											codpri,
											nomepri,
											j39_numero,
											tipo,
											min(j46_dtinc) as j46_dtinc
							from (
	            select  j46_matric,
											j46_codigo,
 										  j61_codproc,
		                  z01_nome,
		                  j47_anousu,
		                  j45_descr,
		                  j46_perc,                      
		                  abs(coalesce(j21_valor,0)) as j21_valor,
		                  j46_hist,
                      z01_compl,
                      codpri,   
                      nomepri,
                      proprietario.j39_numero,
		                  case 
		                    when j39_matric is not null then 'PREDIAL'
		                    when j39_matric is null or j39_dtdemo is not null then 'TERRITORIAL'
		                  end as tipo,
		                  j46_dtinc
	            from iptuisen 
	            inner join tipoisen 	  on j45_tipo = j46_tipo 
	            inner join isenexe 	    on j47_codigo = j46_codigo 
	            inner join proprietario on j01_matric = j46_matric
							inner join isenproc 	  on j61_codigo = j46_codigo
	            left join iptuconstr    on j01_matric = j39_matric
	            ".($calv == true?"inner":"left")." join iptucalv on j21_matric = j01_matric 
                                                   and j21_anousu = j47_anousu 
                                                   and j21_anousu = j47_anousu 
                                                   and j21_valor < 0 ";
$sql .= "     where j47_anousu between $anoini and $anofin $xdatas $xtipo $iptucalv) as x ";

$sql .= "     group by	j46_matric,
  											j46_codigo,
												j61_codproc,
												z01_nome,
												j47_anousu,
												j45_descr,
												j46_perc,
												j21_valor,
												z01_compl,
												codpri,
												nomepri,
												j39_numero,
												tipo";
$sql .= "      ) as y order by $order";

// die($sql);

$result = pg_exec($sql) or die($sql);
$num = pg_numrows($result);
if ( $num == 0 ) {
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o exitem isen��es cadastradas para os par�metros escolhidos');
   exit; 
}

$totalportipo = array();

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);

$exercicio = db_getsession("DB_anousu");

$total = 0;
$totvalor = 0;
$matricula = 0;

$pdf->SetFont('Arial','B',7);

// t�tulos das coluna no relat�rio
$pdf->Cell(15,5,"Matr�cula",1,0,"C",1);
$pdf->Cell(63,5,"Proprietario",1,0,"C",1);
$pdf->Cell(25,5,"Per�odo",1,0,"C",1);
$pdf->Cell(65,5,"Endere�o",1,0,"C",1);
$pdf->Cell(24,5,"Tipo",1,1,"C",1);

$pdf->Cell(15,5,$RLj47_anousu,1,0,"C",1);
$pdf->Cell(63,5,$RLj45_descr,1,0,"C",1);
$pdf->Cell(25,5,"Data de Inclus�o",1,0,"C",1);
$pdf->Cell(22,5,$RLj21_valor,1,0,"C",1);
$pdf->Cell(18,5,$RLj46_perc,1,0,"C",1);
$pdf->Cell(25,5,"Cod. Isen��o",1,0,"C",1);
$pdf->Cell(24,5,"Processo",1,0,"C",1);
$pdf->Setxy(10,45);
$pdf->cell(192,5,$RLj46_hist,1,1,"C",1);

$altura = 43;
// quebra linha
$pdf->Ln(5);	 

for($i=0;$i<$num;$i++) {
	/*
   if($pdf->gety() > $pdf->h - 30){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',7);
		  $pdf->Cell(15,5,"Matricula",1,0,"C",1);
		  $pdf->Cell(63,5,"Proprietario",1,0,"C",1);
		  $pdf->Cell(25,5,"Periodo",1,0,"C",1);
		  $pdf->Cell(65,5,"Endere�o",1,0,"C",1);
		  $pdf->Cell(20,5,"Tipo",1,1,"C",1);
			
      $pdf->Cell(15,5,$RLj47_anousu,0,0,"C",1);
      $pdf->Cell(40,5,$RLj45_descr,0,0,"C",1);
      $pdf->Cell(18,5,$RLj46_dtinc,0,0,"C",1);
      $pdf->Cell(15,5,$RLj21_valor,0,0,"C",1);
      $pdf->Cell(18,5,$RLj46_perc,0,0,"C",1);
      $pdf->Cell(85,5,$RLj46_hist,0,1,"L",1);
   }
	*/
   db_fieldsmemory($result,$i);
   if ($matricula != $j46_matric) {
      $pdf->SetFont('Arial','B',7);
      $pdf->cell(15,6,$j46_matric,"T",0,"C",0);
      $pdf->cell(63,6,$z01_nome,"T",0,"L",0);
			// ano inicio / ano fim
      $pdf->cell(25.5,6,$min.' / '.$max,"T",0,"C",0);
			// endere�o
      $pdf->cell(65,6,substr(($codpri!=""?$codpri:"").($nomepri!=""?" - ".$nomepri:"").($j39_numero!=""?" - ".$j39_numero:"").($z01_compl!=""?"/".$z01_compl:""),0,45),"T",0,"L",0);
      // tipo
			$pdf->cell(23.7,6,$tipo,"T",1,"C",0);
	
   }
   $pdf->SetFont('Arial','',7);
   // exercicio
   $pdf->cell(15,6,$j47_anousu,"T",0,"C",0);
	 // descri��o
   $pdf->cell(63,6,substr($j45_descr,0,20),"T",0,"L",0);
	 // data inclus�o
   $pdf->cell(25.5,6,db_formatar($j46_dtinc,'d'),"T",0,"C",0);
	 // valor
	 $pdf->cell(21.4,6,trim((isset($j21_valor)?db_formatar($j21_valor,'f'):"0,00")),"T",0,"C",0);
	 // percentual
	 $pdf->cell(18.3,6,trim(db_formatar($j46_perc,'f')),"T",0,"C",0);
	 // c�digo isen��o	 
	 $pdf->cell(25.4,6,$j46_codigo,"T",0,"C",0);
	 // c�digo do processo
	 $pdf->cell(23.6,6,$j61_codproc,"T",0,"C",0);
	 // quebra linha
   $pdf->Ln(5);	 
	 // hist�rico
   $pdf->MultiCell(192.3,6,$j46_hist,"T",1,"C",0);
	 // quebra linha
   $pdf->Ln(3);
	 
	 $total += 1;
   $totvalor += $j21_valor;
	 
	 if (!isset($totalportipo[$j45_descr][0])) {
	 	 $totalportipo[$j45_descr][0] = $j21_valor;
	 } else {
	 	 $totalportipo[$j45_descr][0] += $j21_valor;
	 }
	
}

$pdf->AddPage();

$pdf->cell(80,5,"TOTALIZA��O POR TIPO DE ISEN��O",1,1,"C",1);
$pdf->Ln(5);

$pdf->cell(60,5,"DESCRI��O",1,0,"L",1);
$pdf->cell(20,5,"VALOR",1,1,"C",1);

$total_quant=0;
foreach ($totalportipo as $k => $v) {
	$pdf->cell(60,5,$k,0,0,"L",0);
	$pdf->cell(20,5,db_formatar($v[0], 'f'),0,1,"R",0);
	$total_quant+=$v[0];
}
$pdf->cell(60,5,"TOTAL",1,0,"L",1);
$pdf->cell(20,5,db_formatar($total_quant, 'f'),1,1,"R",1);

$pdf->Ln(5);
$pdf->Cell(95,6,"Total de Registros: ".$total ,"T",0,"L",0);
$pdf->Cell(90,6,'',"T",1,"R",0);

$pdf->Output();

?>