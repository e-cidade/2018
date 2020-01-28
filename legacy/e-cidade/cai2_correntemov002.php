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

$head1 = "RELATÓRIO DAS MOVIMENTAÇÕES ";
$head2 = "DIA: ".db_formatar($data,'d');
if ($caixa > 0 ){
   $sql = "select * from cfautent where k11_id = $caixa and k11_instit = " . db_getsession("DB_instit");
   $result = pg_exec($sql);
//   db_criatabela($result);exit;
   db_fieldsmemory($result,0);
   $xcaixa  = ' and c.k12_id = '.$caixa ;
   $xcaixa1 = ' and k12_idautent = '.$caixa ;
   $head4 = 'Caixa :  '.$k11_id.' - '.$k11_local;
}else{
   $xcaixa  = '';
   $xcaixa1 = '';
   $head4 = 'Todos os Caixas';
}
/*
    $sql="
          select k13_conta,k13_descr,
	         sum(k12_valor) as totcaixa 
          from corrente 
	       inner join saltes on k13_conta = k12_conta 
	  where k12_data = '$data' 
	    and k12_instit = " . db_getsession("DB_instit") . " 
	    $xcaixa1 
	  group by k13_conta,k13_descr
         ";
//echo $sql;exit;
$result1 = pg_exec($sql);

echo $sql = "
 select correntemov.k12_idautent, 
        correntemov.k12_tipomov,
        case correntemov.k12_tipomov 
	     when '0' then 'Débito' 
	     when '1' then 'Crédito' 
	end as tipo,
	correntemov.k12_horamov,
	correntemov.k12_valormov,
	correntemov.k12_obsmov
from correntemov
	inner join corrente on 	    corrente.k12_id 	= correntemov.k12_idmov
				and corrente.k12_autent = correntemov.k12_idautent
				and corrente.k12_data   = correntemov.k12_dtmov
where k12_dtmov = '$data' and corrente.k12_instit = " . db_getsession("DB_instit") . "
      $xcaixa1;
";
*/

$sql2 = "
  select k12_idautent,
         k12_tipomov,
         case k12_tipomov
              when '0' then 'Débito'
              when '1' then 'Crédito'
         end as tipo,
         k12_horamov,
         k12_valormov,
         k12_obsmov
  from correntemov c
  where k12_dtmov = '$data'
        $xcaixa1;
  ";


   $sql="
select k13_conta,
       k13_descr,
       c60_codsis,
       sum(soma) as totcaixa
from
      (select k12_empen,
              k13_conta,
              k13_descr,
              case when k12_empen is null then k12_valor else 0 end as soma,
              case when k12_empen is not null then k12_valor else 0 end as menos
       from corrente c
            inner join saltes  on k13_conta = c.k12_conta
            left join coremp p on p.k12_id=c.k12_id
                              and p.k12_data = c.k12_data
                              and p.k12_autent = c.k12_autent
       where c.k12_data = '$data' $xcaixa ) as x
           inner join conplanoreduz on c61_reduz = k13_conta and c61_anousu=".db_getsession("DB_anousu")."
           inner join conplano      on c60_codcon = c61_codcon and c60_anousu=c61_anousu		   
      group by k13_conta,k13_descr,c60_codsis
	 ";
   $sql1="
      select k12_empen,
              k13_conta,
	          k12_hora,
              k13_descr,
              k12_valor as valor,
              z01_nome,
	          e60_codemp,
	          c60_codsis,
	          k12_codord
       from corrente c
            inner join saltes  on k13_conta = c.k12_conta
            inner join coremp p on p.k12_id=c.k12_id
                              and p.k12_data = c.k12_data
                              and p.k12_autent = c.k12_autent
	        inner join empempenho on e60_numemp = k12_empen
	        inner join cgm on z01_numcgm = e60_numcgm
            inner join conplanoreduz on c61_reduz = k13_conta and c61_anousu=".db_getsession("DB_anousu")."
            inner join conplano      on c60_codcon = c61_codcon and c60_anousu=c61_anousu
       where c.k12_data = '$data' $xcaixa  and k12_empen is not null and c60_codsis = 5
	  
	 ";
//echo $sql1;exit;										      
    $result1 = pg_exec($sql);
    $result2 = pg_exec($sql1);
    $result = pg_exec($sql2);
//db_criatabela($result);
$num = pg_numrows($result);
$linha = 60;
$pre = 0;
$total = 0;
$pagina = 0;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage(); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,4,"Caixa",1,0,"C",1);
$pdf->Cell(10,4,"Tipo",1,0,"C",1);
$pdf->Cell(10,4,"Hora",1,0,"C",1);
$pdf->Cell(20,4,"Valor",1,0,"C",1);
$pdf->Cell(140,4,"Observações",1,1,"C",1);
$preenc = 1;
for($i=0;$i<$num;$i++) {
   if($pdf->gety() > $pdf->h - 30 ){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(10,4,"Caixa",1,0,"C",1);
      $pdf->Cell(10,4,"Tipo",1,0,"C",1);
      $pdf->Cell(10,4,"Hora",1,0,"C",1);
      $pdf->Cell(20,4,"Valor",1,0,"C",1);
      $pdf->Cell(140,4,"Observações",1,1,"C",1);
      $pagina = $pdf->PageNo();
      $pdf->Ln(3);
   }
   if($preenc == 1){
     $preenc = 0;
   }else {
     $preenc = 1;
   }
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(10,4,$k12_idautent,0,0,"C",$preenc);
   $pdf->Cell(10,4,$tipo,0,0,"C",$preenc);
   $pdf->Cell(10,4,$k12_horamov,0,0,"C",$preenc);
   $pdf->Cell(20,4,db_formatar($k12_valormov,'f'),0,0,"R",$preenc);
   $pdf->multiCell(140,4,$k12_obsmov,0,"L",$preenc);
   if ($k12_tipomov == '0'){
      $total -= $k12_valormov;
   }else{
      $total += $k12_valormov;
   }
}
$pdf->Ln(3);
$pdf->Cell(30,4,"Movimentações :","T",0,"R",0);
$pdf->Cell(20,4,db_formatar($total,'f'),"T",0,"R",0);
$pdf->Cell(140,4,'',"T",1,"R",0);
$totalcontas = 0;
for ($i = 0;$i < pg_numrows($result1);$i++){
    db_fieldsmemory($result1,$i);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(30,4,$k13_descr." : ",0,0,"R",0);
    $pdf->Cell(20,4,db_formatar($totcaixa,'f'),0,0,"R",0);
    $pdf->Cell(140,4,'',0,1,"R",0);
    $totalcontas += $totcaixa;
    if($k13_conta == 1){
       $pdf->SetFont('Arial','B',7);
       $pdf->Cell(30,4,'Saldo do Caixa : ',0,0,"R",0);
       $pdf->Cell(20,4,db_formatar($totalcontas+$total,'f'),"T",0,"R",0);
       $pdf->Cell(140,4,'',0,1,"R",0);
    }
    
//    $pdf->Cell(30,6,$k13_descr." : ","T",0,"R",0);
//    $pdf->Cell(20,6,db_formatar($totcaixa,'f'),"T",0,"R",0);
//    $pdf->Cell(140,6,'',"T",1,"R",0);

}
$pdf->ln(5);
$pdf->SetFont('Arial','B',9);
$pdf->multicell(0,4,'EMPENHOS',0,"C");
$pdf->Cell(10,4,"Empenho",1,0,"C",1);
$pdf->Cell(10,4,"Ordem",1,0,"C",1);
$pdf->Cell(10,4,"Hora",1,0,"C",1);
$pdf->Cell(20,4,"Valor",1,0,"C",1);
$pdf->Cell(140,4,"Credor",1,1,"C",1);
$vlremp = 0;
for($xx=0;$xx < pg_numrows($result2);$xx++){
  db_fieldsmemory($result2,$xx);
   if($pdf->gety() > $pdf->h - 30 ){
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(10,4,"Empenho",1,0,"C",1);
      $pdf->Cell(10,4,"Ordem",1,0,"C",1);
      $pdf->Cell(10,4,"Hora",1,0,"C",1);
      $pdf->Cell(20,4,"Valor",1,0,"C",1);
      $pdf->Cell(140,4,"Credor",1,1,"C",1);
      $pagina = $pdf->PageNo();
      $pdf->Ln(3);
   }
   if($preenc == 1){
     $preenc = 0;
   }else {
     $preenc = 1;
   }
   $pdf->SetFont('Arial','',8);
   $pdf->Cell(10,4,$e60_codemp,0,0,"L",$preenc);
   $pdf->Cell(10,4,$k12_codord,0,0,"L",$preenc);
   $pdf->Cell(10,4,$k12_hora,0,0,"C",$preenc);
   $pdf->Cell(20,4,db_formatar($valor,'f'),0,0,"R",$preenc);
   $pdf->Cell(140,4,$z01_nome,0,1,"L",$preenc);
   $vlremp += $valor;
}
$pdf->Ln(3);
$pdf->Cell(30,4,"Empenhos :","T",0,"R",0);
$pdf->Cell(20,4,db_formatar($vlremp,'f'),"T",0,"R",0);
$pdf->Cell(140,4,'',"T",1,"R",0);
$pdf->Ln(3);
$pdf->Cell(30,4,"Saldo :","T",0,"R",0);
$pdf->Cell(20,4,db_formatar($totalcontas+$total-$vlremp,'f'),"T",0,"R",0);
$pdf->Cell(140,4,'',"T",1,"R",0);
$pdf->Output();

?>