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
db_postmemory($HTTP_SERVER_VARS);
$seleciona_conta = '';
$descr_conta = 'TODAS AS CONTAS';
$dataf = $datai;
if($conta != 0) {
  $seleciona_conta = ' and ( a.k12_conta = '.$conta.' or e.k12_conta = '.$conta.' )';
  $sql = 'select * 
              from saltes 
                    inner join conplanoexe on c62_reduz = k13_reduz and c62_anousu = ' . db_getsession('DB_anousu') . '
                    inner join conplanoreduz on c62_reduz = c61_reduz and c61_instit = ' . db_getsession('DB_instit') .  '
                                                         and c61_anousu=c62_anousu
              where k13_conta = '.$conta;
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
  $descr_conta = "CONTA : ".$conta.' - '.$k13_descr;
}

$selecao = 'TODOS OS CAIXAS';
$seleciona = '';
$ordem = ' order by a.k12_conta, a.k12_autent ';
if($caixa != 0){
  $seleciona = ' and a.k12_id = '.$caixa;
  $ordem = ' order by a.k12_data, a.k12_conta ';
  $sql = "select * from cfautent where k11_id = $caixa and k11_instit = " . db_getsession('DB_instit');
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
  $selecao = "CAIXA : ".$caixa.' - '.$k11_local;
}
if($datai == $dataf){
  $sql = "select k12_data 
          from boletim";
  $head1 = "BOLETIM DA TESOURARIA";
  $head3 = "BOLETIM NÚMERO: ".@$numbol;
  $head5 = "DATA : ".db_formatar(@$datai,"d");
}else{
  $head5 = "BOLETIM DE AUTENTICAÇÕES";
}
$head7 = $selecao;
$head9 = $descr_conta;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");

$QuebraPagina = 10;
$total     = 0;
$total_neg = 0;
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);

$res1 = pg_query("select c61_reduz 
         from conplano 
	      inner join conplanoreduz on c60_codcon = c61_codcon and c61_instit = " . db_getsession('DB_instit') . "
                                                    and c60_anousu=c61_anousu
	 where c60_codsis = 5 
            and c60_anousu=".db_getsession("DB_anousu")."
	 order by c60_estrut");
$virg = '';
$conta_caixa = ''; 
for($ii = 0;$ii < pg_numrows($res1); $ii++){
   db_fieldsmemory($res1,$ii);
   $conta_caixa .= $virg.$c61_reduz;   
   $virg = ' ,';
}


$CoL1 = 15;
$CoL2 = 25;
$CoL3 = 20;
$CoL4 = 20;
$CoL5 = 20;
$CoL6 = 35;
$CoL7 = 25;
$CoL8 = 25;

$StrPad1 = 20;
$StrPad2 = 26;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$exercicio = $GLOBALS["DB_anousu"];
$sql  = "
         select distinct k12_conta,k13_descr as conta_saltes
		from (
	          select a.k12_conta,w.k13_descr as conta_saltes
              from corrente a
          		   inner join saltes  w on a.k12_conta  = w.k13_conta
  	          where
	      	    a.k12_instit = " . db_getsession('DB_instit') . " and
	            a.k12_data between '$datai' and '$dataf' and k12_conta not in ($conta_caixa)
		    
         union all

         select l.k12_conta,w.k13_descr as conta_saltes
              from corlanc l
		   inner join saltes  w on l.k12_conta  = w.k13_conta
		   inner join conplanoexe on c62_reduz = w.k13_reduz and c62_anousu = " . db_getsession('DB_anousu') . "
		   inner join conplanoreduz on c61_reduz = c62_reduz and c61_instit = " . db_getsession('DB_instit') . "
                                                          and c61_anousu=c62_anousu
	      where
	            l.k12_data between '$datai' and '$dataf' and k12_conta not in ($conta_caixa)
	 ) as x
	 inner join saltes on k13_conta = k12_conta
	 order by k12_conta 
		    ";
//echo $sql;

$result = pg_exec($sql);
//db_criatabela($result);
//exit;
$num = pg_numrows($result);
//$num = 1;
$xconta = '';
for($conta=0;$conta<$num;$conta++){
  db_fieldsmemory($result,$conta);
  $seleciona_conta = ' and ( a.k12_conta = '.$k12_conta.' or e.k12_conta = '.$k12_conta.' )';
  
  if($xconta != $k12_conta){
    $descr_conta = "CONTA : ".$k12_conta.' - '.$conta_saltes;
    $xconta = $k12_conta;
  }

$sql  = "select x.*,
		case when cornump_numpre is not null then 'TRIBUTARIO' else 
		  case when coremp_empen is not null and corlanc_conta is not null then 'RP' else
		    case when coremp_empen is not null then 'EMPENHO' else
		      case when corlanc_conta is not null then 'SLIP' else
		        case when corcla_codcla is not null then 'CLASSIFICAÇÕES' else 
			  'ERRO' end
		      end
		    end
		  end
		end as tipo
		from (
	 select a.k12_id as corrente_id,
	        a.k12_data as corrente_data,
		a.k12_autent as corrente_autent,
                a.k12_hora as corrente_hora,
		a.k12_conta as corrente_conta,
		w.k13_descr as corrente_descr,
		round(a.k12_valor,2) as corrente_valor,
                b.k12_numpre as cornump_numpre,
		b.k12_numpar as cornump_numpar,
		b.k12_receit as cornump_receit,
		x.k02_descr as cornump_descr,
		b.k12_valor as cornump_valor,
		c.k12_empen as coremp_empen,
		c.k12_cheque as coremp_cheque,
		e60_codemp as coremp_codemp,
		e60_anousu as coremp_anousu,
		d.k12_codcla as corcla_codcla,
		e.k12_conta as corlanc_conta,
		p.c60_descr as corlanc_descr,
		e.k12_codigo as corlanc_slip,
		z01_nome
		
              from corrente a
		   left join saltes  w on a.k12_conta  = w.k13_conta
		   left join cornump b on b.k12_id     = a.k12_id
		 		      and b.k12_data   = a.k12_data
				      and b.k12_autent = a.k12_autent
	 	   left join tabrec  x on b.k12_receit = x.k02_codigo
		   left join coremp  c on c.k12_id     = a.k12_id
				      and c.k12_data   = a.k12_data
				      and c.k12_autent = a.k12_autent
                   left join empempenho y on y.e60_numemp = c.k12_empen
		   left join cgm on y.e60_numcgm = z01_numcgm
		   left join corcla  d on d.k12_id     = a.k12_id
				      and d.k12_data   = a.k12_data
				      and d.k12_autent = a.k12_autent
		   left join corlanc e on e.k12_id     = a.k12_id
				      and e.k12_data   = a.k12_data
				      and e.k12_autent = a.k12_autent
		   left join conplanoreduz z on e.k12_conta  = z.c61_reduz and z.c61_anousu=".db_getsession("DB_anousu")."
		   left join conplano p      on z.c61_codcon = p.c60_codcon and z.c61_anousu = p.c60_anousu 
	      where
	            a.k12_instit = " . db_getsession('DB_instit') . " and
	            a.k12_data between '$datai' and '$dataf' 
	            $seleciona 
		    $seleciona_conta 
		    $ordem) as x
		    order by corrente_data, tipo, corrente_conta";
//echo $sql;
$result1 = pg_exec($sql);
//db_criatabela($result);
$numrows = pg_numrows($result1);

$velho_nump_id     = ""; 
$velho_nump_data   = "";
$velho_nump_autent = "";
//echo $numrows;
$passa    = true;
$tipo_old = "";

for($i=0;$i<$numrows;$i++){
  $coremp  = false;
  $corlanc = false;
  $cornump = false;
  $corcla  = false;
  db_fieldsmemory($result1,$i);
  if(trim($coremp_empen)!="" || trim($coremp_cheque)!="" || trim($coremp_empen)!="" || trim($coremp_anousu)!=""){
    $coremp = true;
  }
  if(trim($corlanc_conta)!="" || trim($corlanc_descr)!="" || trim($corlanc_slip)!=""){
    $corlanc = true;
  }
  if(trim($cornump_numpre)!="" || trim($cornump_numpar)!="" || trim($cornump_receit)!="" || trim($cornump_descr)!="" || trim($cornump_valor)!=""){
    $cornump = true;
    $passa = true;
    if($velho_nump_id ==$corrente_id && $velho_nump_data == $corrente_data && $velho_nump_autent == $corrente_autent){
      $passa = false;
    }
  }
  if(trim($corcla_codcla)!=""){
    $corcla = true;
  }
  if (1 == 2) {
    if($coremp==true && $corlanc==true){
      $tipo = "RP";
    }else if($coremp==true && $corlanc==false){
      $tipo = "EMPENHOS";
    }else if($coremp==false && $corlanc==true){
      $tipo = "SLIP";
    }else if($cornump==true){
      $tipo = "TRIBUTÁRIO";
    }else if($corcla==true){
      $tipo = "CLASSIFICAÇÕES";
    }
  }
  if($pdf->gety() > $pdf->h - 32 || $i == 0){
    if($pdf->gety() > $pdf->h - 32){
      /*
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(255,0,0);
      $pdf->Cell($CoL1+$CoL2,5,"SUB-TOTAL",1,0,"C",0);
      $pdf->Cell($CoL3,5," ",1,0,"R",0);
      $pdf->Cell($CoL4,5," ",1,0,"R",0);
      $pdf->Cell($CoL5,5," ",1,0,"R",0);
      $pdf->Cell($CoL6,5,db_formatar($SubTotal2,'f'),1,1,"R",0);
      $pdf->SetTextColor(0,0,0); 
      */
      $pdf->AddPage("L");
    }
    $pdf->SetTextColor(0,100,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,$k12_conta.":  ".$corrente_descr,0,1,"L",0);
    $pdf->Cell($CoL1,5,"CAIXA","TBL",0,"C",1);
    $pdf->Cell($CoL2,5,"DATA","TB",0,"C",1);
    $pdf->Cell($CoL3,5,"AUTENT","TB",0,"C",1);
    $pdf->Cell($CoL4,5,"HORA","TB",0,"C",1);
    $pdf->Cell($CoL5,5,"CREDITO","TB",0,"C",1);
    $pdf->Cell(65,5,"DESCRIÇÃO","TB",0,"E",1);
    $pdf->Cell($CoL6,5,"VALOR","TB",0,"C",1);
    $pdf->Cell(30,5,"TIPO","RTB",1,"C",1);
  }
  if(($cornump==true  && $passa == true) || $cornump==false){
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,@$corrente_id,"T",0,"C",0);
    $pdf->Cell($CoL2,5,db_formatar(@$corrente_data,'d'),"T",0,"L",0);
    $pdf->Cell($CoL3,5,@$corrente_autent,"T",0,"C",0);
    $pdf->Cell($CoL4,5,@$corrente_hora,"T",0,"C",0);
    if($tipo=='TRIBUTARIO' || $tipo == 'CLASSIFICAÇÃO' )
      $tt='Deb';
    else
      $tt='Cre';
    $pdf->Cell($CoL5,5,"$tt-".@$corrente_conta,"T",0,"C",0);
 
    $pdf->Cell(65,5,@$corrente_descr,"T",0,"L",0);
    if ($corrente_valor < 0){$pdf->SetTextColor(255,0,0);}
    $pdf->Cell($CoL6,5,"R$".str_pad(number_format($corrente_valor,2,",","."),14," ",STR_PAD_LEFT),"T",0,"R",0);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,$tipo,"T",1,"C",0);
    if($corrente_valor > 0)
      $total     += $corrente_valor;
    else
      $total_neg += $corrente_valor;
  }
  $pdf->SetTextColor(0,100,255);
//  if($coremp==true && $corlanc==true && $tipo_old != "RP"){ 
  /*
  if($coremp==true && $corlanc==true){ 
    $pdf->Cell(20,5,"CÓD EMP",0,0,"C",0);
    $pdf->Cell(21,5,"EMP/ANO",0,0,"C",0);
    $pdf->Cell(21,5,"NUM CHEQUE",0,0,"C",0);
    $pdf->Cell(21,5,"NUM SLIP",0,0,"C",0);
    $pdf->Cell(21,5,"DEBITO",0,0,"C",0);
    $pdf->Cell(65,5,"DESCRIÇÃO",0,1,"E",0);
    $tipo_old = "RP";
//  }else if($coremp==true && $corlanc==false && $tipo_old != "emp"){
  }else if($coremp==true && $corlanc==false){
    $pdf->Cell(20,5,"CÓD EMP",0,0,"C",0);
    $pdf->Cell(20,5,"EMP/ANO",0,0,"C",0);
    $pdf->Cell(20,5,"NUM CHEQUE",0,1,"C",0);
    $tipo_old = "emp";
//  }else if($coremp==false && $corlanc==true && $tipo_old != "lanc"){
  }else if($coremp==false && $corlanc==true){
    $pdf->Cell(20,5,"NUM SLIP",0,0,"C",0);
    $pdf->Cell(20,5,"DEBITO",0,0,"C",0);
    $pdf->Cell(65,5,"DESCR",0,1,"C",0);
    $tipo_old = "emp";
//  }else if($cornump==true && $tipo_old != "nump"){
  }else if($cornump==true){
    $pdf->Cell(15,5,"NUMPRE",0,0,"C",0);
    $pdf->Cell(25,5,"RECEITA",0,0,"C",0);
    $pdf->Cell(40,5,"DESCR",0,0,"C",0);
    $pdf->Cell(20,5,"VALOR",0,1,"C",0);
    $passa = false;
    $tipo_old = "nump";
//  }else if($corcla==true && $tipo_old != "cla"){
  }else if($corcla==true){
    $pdf->Cell(20,5,"CODCLA",0,0,"C",0);
    $pdf->Cell(20,5,"RECEITA",0,0,"C",0);
    $pdf->Cell(40,5,"DESCR",0,0,"C",0);
    $pdf->Cell(20,5,"VALOR",0,1,"C",0);
    $tipo_old = "cla";
  }
  */
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFont('Arial','',8);
  if($coremp==true && $corlanc==true){    
    $pdf->Cell(21,5,"RP:".@$coremp_empen,0,0,"C",0);
    $pdf->Cell(21,5,@$coremp_codemp."/".@$coremp_anousu,0,0,"C",0);
    $pdf->Cell(21,5,"Che:".@$coremp_cheque,0,0,"C",0);
    $pdf->Cell(21,5,@$corlanc_slip,0,0,"C",0);
    $pdf->Cell(21,5,"Credor:".@$e60_numcgm,0,0,"C",0);
    $pdf->Cell(40,5,@$z01_nome,0,1,"L",0);
  }else if($coremp==true && $corlanc==false){
    $pdf->Cell(20,5,"Empenho:".@$coremp_empen,0,0,"C",0);
    $pdf->Cell(20,5,@$coremp_codemp."/".@$coremp_anousu,0,0,"C",0);
    $pdf->Cell(20,5,"Cheque:".@$coremp_cheque,0,0,"C",0);
    $pdf->Cell(21,5,"Credor:".@$e60_numcgm,0,0,"C",0);
    $pdf->Cell(40,5,@$z01_nome,0,1,"L",0);
  }else if($coremp==false && $corlanc==true){
    $pdf->Cell(20,5,"Slip:".@$corlanc_slip,0,0,"C",0);
    $pdf->Cell(20,5,@$corlanc_conta,0,0,"C",0);
    $pdf->Cell(65,5,@$corlanc_descr,0,1,"L",0);
  }else if($cornump==true){
    $pdf->Cell(20,5,"Numpre:".@$cornump_numpre,0,0,"C",0);
    $pdf->Cell(25,5,@$cornump_receit,0,0,"C",0);
    $pdf->Cell(40,5,@$cornump_descr,0,0,"L",0);
    $pdf->Cell(20,5,@$cornump_valor,0,1,"R",0);
    $velho_nump_id     = $corrente_id;
    $velho_nump_data   = $corrente_data;
    $velho_nump_autent = $corrente_autent;
  }else if($corcla==true){
    $pdf->Cell(20,5,"Slip:".@$corcla_codcla,0,0,"C",0);
    $pdf->Cell(20,5,@$cornump_receit,0,0,"C",0);
    $pdf->Cell(40,5,@$cornump_descr,0,0,"L",0);
    $pdf->Cell(20,5,@$cornump_valor,0,1,"R",0);
  }
}
//if ( $total != 0 ) {
    $pdf->ln(1);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell($CoL1+$CoL2,5,"TOTAL POSITIVO",0,0,"L",0);
    $pdf->Cell($CoL3,5," ",0,0,"R",0);
    $pdf->Cell($CoL4,5," ",0,0,"R",0);
    $pdf->Cell($CoL5,5," ",0,0,"R",0);
    $pdf->Cell(65,5," ",0,0,"C",0);
    $pdf->Cell($CoL6,5,number_format($total,2,",","."),0,1,"R",0);
    $pdf->ln(1);
    $pdf->Cell($CoL1+$CoL2,5,"TOTAL NEGATIVO",0,0,"L",0);
    $pdf->Cell($CoL3,5," ",0,0,"R",0);
    $pdf->Cell($CoL4,5," ",0,0,"R",0);
    $pdf->Cell($CoL5,5," ",0,0,"R",0);
    $pdf->Cell(65,5," ",0,0,"C",0);
    $pdf->Cell($CoL6,5,number_format($total_neg,2,",","."),0,1,"R",0);
    $pdf->SetTextColor(0,0,0);
    $total     = 0;
    $total_neg = 0;
    $pdf->SetFont('Arial','',8);
//}
$pdf->ln(3);
/*
echo "$index_cornum -
     $index_coremp  -
     $index_corcla  -
     $index_corlanc -
     $index_TPRP   ";
*/
}
$pdf->Output();
?>