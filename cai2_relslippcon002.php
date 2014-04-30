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

//$clslip = new cl_slip;

$clrotulo = new rotulocampo;
$clrotulo->label('k17_codigo');
$clrotulo->label('k17_data');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_dtaut');
$clrotulo->label('k17_autent');
$clrotulo->label('c60_descr');
$clrotulo->label('total_por_conta');
$clrotulo->label('total_geral');
$clrotulo->label('conta');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$where = "where 1=1  ";

$where3 = "";

if ($tipodata == "a") {
  if (($data!="--")&&($data1!="--")) {
      $where ="where k17_dtaut  between '$data' and '$data1'   ";
       }else if ($data!="--"){
	  $where="where k17_dtaut >= '$data'     ";
	  }else if ($data1!="--"){
	     $where="where k17_dtaut <= '$data1'      ";
	     }
} else {
  if (($data!="--")&&($data1!="--")) {
      $where ="where k17_data  between '$data' and '$data1'   ";
       }else if ($data!="--"){
	  $where="where k17_data >= '$data'     ";
	  }else if ($data1!="--"){
	     $where="where k17_data <= '$data1'      ";
	     }
}			    


if (($cod!="") && ($conta1=="b")){
   $where3 = "     and  r2.c61_codcon = $cod     ";
   }else if (($cod!="") && ($conta1=="a")){
      $where3 = "      and r1.c61_codcon = $cod    ";
   }else if ($cod!=""){
      $where3 = "   and r1.c61_codcon = $cod  or r2.c61_codcon = $cod    ";
}


if (isset($recurso) && $recurso!='0'){
    $where3 .= " and (r1.c61_codigo=$recurso  or r2.c61_codigo=$recurso ) ";
    $head4 = "RECURSO: $recurso";
}


$head2 = "PERIODO: " . db_formatar($data,"d") . " a " . db_formatar($data1,"d");
$head3 = "CADASTRO DE SLIP POR CONTA";

$ord = "";
if($totalizador == "c") {
   $ord .= "k17_credito";
}
elseif($totalizador == "d") {
   $ord .= "k17_debito";
}

if ($ordem == 1) {
  if(strlen($ord) > 0) {	
  	  $ord .= ",k17_dtaut";
  }
  else {
  	  $ord = "k17_dtaut";
  }
  $head5 = "ORDEM de data de autenticação";
} else {
  if(strlen($ord) > 0) {	
	  $ord .= ",k17_codigo";
  }
  else {
  	  $ord = "k17_codigo";
  }
  $head5 = "ORDEM de slip";
}

$sql = " select * from (
                 select  k17_codigo,
                         k17_data,
                         k17_debito,
	                     case when c1.c60_descr is null then 'E R R O' else c1.c60_descr end as debito_descr,
		                 k17_credito,
					     case when c2.c60_descr is null then 'E R R O' else c2.c60_descr end as credito_descr,
					     k17_valor,
					     k17_hist,
					     k17_texto,
					     k17_dtaut,
					     k17_autent
              from slip
	                     left join conplanoreduz r1 on r1.c61_reduz = k17_debito and r1.c61_anousu=".db_getsession("DB_anousu")." 
	                     left join conplano c1 on c1.c60_codcon = r1.c61_codcon and c1.c60_anousu = r1.c61_anousu
 
	                     left join conplanoreduz r2 on r2.c61_reduz = k17_credito and r2.c61_anousu=".db_getsession("DB_anousu")."
		             left join conplano c2 on c2.c60_codcon = r2.c61_codcon and c2.c60_anousu = r2.c61_anousu 
              $where $where3 and k17_dtaut is not null and k17_instit = " . db_getsession('DB_instit') . "
	      order by $ord ) as x $where ";

$result = pg_query($sql);
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');

}

      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$prenc = 0;
$total = 0;
$alt   = 4;

$total_por_conta = 0;
$total_geral     = 0;
$conta           = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
  
   if ($total_por_conta == 0){
     if ($totalizador == "c"){
        $conta =$k17_credito;
     } else {
        $conta =$k17_debito;
     }        
   }

  
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLk17_codigo,1,0,"C",1);
      $pdf->cell(20,$alt,$RLk17_data,1,0,"C",1); 
      $pdf->cell(40,$alt,$RLk17_valor,1,0,"C",1); 
      $pdf->cell(20,$alt,'Data Aut.',1,0,"C",1); 
      $pdf->cell(140,$alt,$RLk17_texto,1,1,"C",1); 
      $pdf->cell(30,$alt,$RLk17_debito,1,0,"C",1); 
      $pdf->cell(90,$alt,$RLc60_descr,1,0,"C",1); 
      $pdf->cell(30,$alt,$RLk17_credito,1,0,"C",1); 
      $pdf->cell(90,$alt,$RLc60_descr,1,1,"C",1); 

      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$k17_codigo,0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($k17_data,'d'),0,0,"C",$prenc); 
   $pdf->cell(40,$alt,db_formatar($k17_valor,'f'),0,0,"R",$prenc); 
   $pdf->cell(20,$alt,db_formatar($k17_dtaut,'d'),0,0,"C",$prenc); 
   $pdf->multicell(140,$alt,$k17_texto,0,"L",$prenc); 
   
   if ($prenc == 0){
       $prenc = 1;
   } else { 
       $prenc = 0; 
   }

   $pdf->cell(30,$alt,$k17_debito,0,0,"C",$prenc); 
   $pdf->cell(90,$alt,$debito_descr,0,0,"L",$prenc); 
   $pdf->cell(30,$alt,$k17_credito,0,0,"C",$prenc); 
   $pdf->cell(90,$alt,$credito_descr,0,1,"L",$prenc); 


   if($totalizador == "c") {
       if($conta != $k17_credito) {
	   $total_por_conta += $k17_valor;	
	   $pdf->cell(0,$alt,'TOTAL DE CREDITO :  '.db_formatar($total_por_conta,'f'),"TB",1,"L",0);
	   $pdf->ln();
	   $conta           = $k17_credito;
	   $total_por_conta = 0;
       } else {
           $total_por_conta += $k17_valor;	
       }	 
   }elseif($totalizador == "d") {
       if($conta != $k17_debito) {
	   $total_por_conta += $k17_valor;	
	   $pdf->cell(0,$alt,'TOTAL DE DEBITO :  '.db_formatar($total_por_conta,'f'),"TB",1,"L",0);
	   $pdf->ln();
	   $conta           = $k17_debito;
	   $total_por_conta = 0;
       } else {
           $total_por_conta += $k17_valor;	
       }	 
   }
   




   if($movim == 's'){
     $sql1 = "select r.k12_conta as corr, c.k12_conta as corl, r.k12_valor, r.k12_estorn 
              from corrente r 
	           inner join corlanc c on r.k12_id = c.k12_id 
		                       and r.k12_data = c.k12_data 
				       and r.k12_autent = c.k12_autent 
	      where k12_codigo = $k17_codigo ";
     $res1 = pg_query($sql1);
     $pdf->setfont('arial','b',8);
     $pdf->cell(50,$alt,'LANÇAMENTOS NO CAIXA',0,1,"L",0);
     $pdf->setfont('arial','',7);
     for($xx=0;$xx<pg_numrows($res1);$xx++){
        db_fieldsmemory($res1,$xx);
        $pdf->cell(30,$alt,'Débito  : '.($k12_estorn == 'f'?$corl:$corr),0,0,"L",$prenc); 
        $pdf->cell(30,$alt,'Crédito : '.($k12_estorn == 'f'?$corr:$corl),0,0,"L",$prenc); 
        $pdf->cell(30,$alt,'Valor   : '.db_formatar(($k12_estorn == 't'?$k12_valor*(-1):$k12_valor),'f'),0,0,"C",$prenc); 
        $pdf->cell(30,$alt,'Estorno : '.($k12_estorn == 'f'?'SIM':'NÃO'),0,1,"C",$prenc); 
     }

   }
  
   
     if ($prenc == 0){
        $prenc = 1;
       }else $prenc = 0;
   $total++;
   $total_geral += $k17_valor;
}
/////////
if($totalizador == "c") {
   $pdf->cell(0,$alt,'TOTAL DE CREDITO :  '.db_formatar($total_por_conta,'f'),"TB",1,"L",0);
   $pdf->ln();
}elseif($totalizador == "d") {
   $pdf->cell(0,$alt,'TOTAL DE DEBITO :  '.db_formatar($total_por_conta,'f'),"TB",1,"L",0);
   $pdf->ln();
}




$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE GERAL      :  '.db_formatar($total_geral,'f'),"T",1,"L",0);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS  :  '.$total,       0 ,0,"L",0);

$pdf->Output();
   
?>