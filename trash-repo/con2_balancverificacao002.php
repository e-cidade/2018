<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("fpdf151/assinatura.php");
include("libs/db_libcontabilidade.php");

$classinatura = new cl_assinatura;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$agrupa_estrutural=($agrupa_estrutural=='1'?false:true);
$anousu = db_getsession("DB_anousu");

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
$numero_instit = pg_numrows($resultinst);
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg."($codigo)".$nomeinstabrev;
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg."($codigo)".$nomeinst;
  }

  $xvirg = ', ';
}

if ($encerramento=='s')
  $encerramento='true';
else
  $encerramento='false';


$head2 = "BALANCETE DE VERIFICA��O";
$head3 = "EXERC�CIO ".db_getsession("DB_anousu");
$head4 = "PER�ODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d');

if ( $movimento == "S" )
  $xmov = "Somente com Movimento";
else
  $xmov = "Todas";

if ( $tipo == "S" )
  $head5 = "SINT�TICO - ".$xmov;
else
  $head5 = "ANAL�TICO - ".$xmov;

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head6 = "INSTITUI��ES : ".$descr_inst;
  
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).")" ;

if($sistema_contas!="T")
  $where .= " and c52_descrred = '$sistema_contas'";

$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$perini,$perfin,false,$where,$estrut_inicial,$agrupa_estrutural,$encerramento);

//db_criatabela($result);exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;
$maislinha      = 0;
$total_anterior    = 0;
$total_debitos  = 0;
$total_creditos = 0;
$total_final    = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
     
   if( ( $tipo == "S" ) && ( $c61_reduz != 0 ) ) {
     continue;
   }
 
    if (USE_PCASP) {
    } else {
      if(substr($estrutural,0,1) == '3' ) { 
	if(substr($estrutural,2)+0 > 0 ) 
	  continue;
      }
      if(substr($estrutural,0,1) == '4' ) {
	if(substr($estrutural,2)+0 > 0 ) 
	  continue; 
      } 
    }
/*
   if(substr($estrutural,0,1) == '34' && $estrutural <> '340000000000000')  
     continue;
     
   if(substr($estrutural,0,1) == '41' && $estrutural <> '410000000000000')  
     continue;
     
   if(substr($estrutural,0,1) == '42' && $estrutural <> '420000000000000')  
     continue;
*/
   if( ( $movimento == "S" ) && ( ( $saldo_anterior + $saldo_anterior_debito + $saldo_anterior_credito) == 0 ) ) {
     continue;
   }
     
   if(($pdf->gety() > $pdf->h - 32) || $pagina == 1){
     $pagina = 0;
     $pdf->addpage('L');
     $pdf->setfont('arial','b',7);
     $pdf->cell(28,$alt,'ESTRUTURAL',"B",0,"C",0);
     if($numero_instit>1 && $agrupa_estrutural==true){
       $pdf->cell(10,$alt,'',"B",0,"C",0);
       $pdf->cell(10,$alt,'',"B",0,"C",0);
     }else{
       $pdf->cell(10,$alt,'REDUZ',"B",0,"C",0);
       $pdf->cell(10,$alt,'INST',"B",0,"C",0);
     }
     $pdf->cell(125,$alt,'DESCRI��O DA CONTA',"B",0,"C",0);
     $pdf->cell(10,$alt,'REC',"B",0,"C",0);
     $pdf->cell(10,$alt,'SIS',"B",0,"C",0);
     $pdf->cell(22,$alt,'SALDO ANTERIOR',"B",0,"R",0);
     $pdf->cell(2,$alt,'',"B",0,"R",0);
     $pdf->cell(22,$alt,'D�BITOS',"B",0,"R",0);
     $pdf->cell(22,$alt,'CR�DITOS',"B",0,"R",0);
     $pdf->cell(22,$alt,'SALDO',"B",0,"R",0);
     $pdf->cell(2,$alt,'',"B",1,"R",0);
     $pdf->ln(3);
   } 
   $espaco = '';
   $maislinha = 0;
   if(substr($estrutural,1,14)      == '00000000000000'){
   	$espaco="";
	$maislinha=1;
	if($sinal_anterior == "C")
           $total_anterior -= $saldo_anterior;
	else
           $total_anterior += $saldo_anterior;
	   
	if($sinal_final == "C")
           $total_final -= $saldo_final;
	else
           $total_final += $saldo_final;
	  
        $total_debitos  += $saldo_anterior_debito;
        $total_creditos += $saldo_anterior_credito;
   }elseif(substr($estrutural,2,13) == '0000000000000'){
   	$espaco="  ";
	$maislinha=1;
   }elseif(substr($estrutural,3,12) == '000000000000'){
   	$espaco="    ";
        $maislinha=1;
   }elseif(substr($estrutural,4,11) == '00000000000'){
   	$espaco="      ";
   }elseif(substr($estrutural,5,10) == '0000000000'){
   	$espaco="        ";
   }elseif(substr($estrutural,7,8)  == '00000000'){
   	$espaco="          ";
   }elseif(substr($estrutural,9,6)  == '000000'){
   	$espaco="            ";
   }elseif(substr($estrutural,11,4) == '0000'){
   	$espaco="              ";
   }
   if($maislinha == 1){
     $pdf->ln(1);
//     $pdf->setfont('arial','b',7);
//   }else{
//     $pdf->setfont('arial','',7);
   }
   $resconta = db_query("select conplanoconta.* 
                         from conplanoconta 
			 where c63_codcon = $c61_codcon and
			  c63_anousu = ".db_getsession("DB_anousu"));
   if(pg_numrows($resconta) > 0)
     db_fieldsmemory($resconta,0);
   if($c61_reduz != 0){
     $pdf->setfont('arial','',7);
   }else{
     $pdf->setfont('arial','B',7);
   }
   $pdf->cell(28,$alt,db_formatar($estrutural,'receita'),0,0,"L",0,0); 
   if($numero_instit>1 && $agrupa_estrutural==true){
     $pdf->cell(10,$alt,"",0,0,"C",0,0); 
     $pdf->cell(10,$alt,"",0,0,"C",0,0); 
   }else{
     $pdf->cell(10,$alt,($c61_reduz == 0?'':$c61_reduz),0,0,"C",0,0); 
     $pdf->cell(10,$alt,($c61_reduz == 0?'':$c61_instit),0,0,"C",0,0); 
   }
   if($conta == 'S'){
     $pdf->cell(125,$alt,(pg_numrows($resconta) == 0?$espaco.$c60_descr:$espaco.$c60_descr.'   ( Bco: '.$c63_banco.'  Ag: '.$c63_agencia.'  Cta: '.$c63_conta.')'),0,0,"L",0,0,'.'); 
   }else{
     $pdf->cell(125,$alt,$espaco.$c60_descr,0,0,"L",0,0,'.'); 
   }
   $pdf->cell(10,$alt,($c61_reduz == 0?'':$c61_codigo),0,0,"C",0);

   if($c61_reduz != 0){
     $resconta = db_query("select c52_descrred
                           from conplano 
			        inner join consistema on c52_codsis = c60_codsis
		           where c60_anousu=$anousu and c60_estrut = '$estrutural'");
     db_fieldsmemory($resconta,0);

     $pdf->cell(10,$alt,$c52_descrred,0,0,"C",0);
   }else{
     $pdf->cell(10,$alt,"",0,0,"C",0);
   }
   $pdf->cell(22,$alt,db_formatar($saldo_anterior,'f'),0,0,"R",0);
   $pdf->cell(2,$alt,$sinal_anterior,0,0,"R",0);
   $pdf->cell(22,$alt,db_formatar($saldo_anterior_debito,'f'),0,0,"R",0);
   $pdf->cell(22,$alt,db_formatar($saldo_anterior_credito,'f'),0,0,"R",0);
   $pdf->cell(22,$alt,db_formatar($saldo_final,'f'),0,0,"R",0);
   $pdf->cell(2,$alt,$sinal_final,0,1,"R",0);
}

//if ( $pdf->gety() > ( $pdf->h - 50 ) )
//    $pdf->addpage("L");

$pdf->setfont('arial','b',7);
$pdf->ln(2);
$pdf->cell(25,$alt,'',0,0,"L",0,0,'.'); 
$pdf->cell(10,$alt,'',0,0,"L",0,0,'.'); 
$pdf->cell(130,$alt,'T O T A L   G E R A L ',0,0,"L",0,0,'.'); 
$pdf->cell(26,$alt,'',0,0,"R",0);
//echo $total_anterior."<br>";
if($total_anterior<0){
  $sinal = "C";
  //$total_anterior *= -1;
}else{
  $sinal = "D";
}
$pdf->cell(22,$alt,db_formatar(($total_anterior * - 1),'f'),0,0,"R",0);
$pdf->cell(2,$alt,$sinal,0,0,"R",0);
$pdf->cell(24,$alt,db_formatar($total_debitos,'f'),0,0,"R",0);
$pdf->cell(22,$alt,db_formatar($total_creditos,'f'),0,0,"R",0);
$total_final = $total_anterior + $total_debitos - $total_creditos;
if($total_final<0){
  $sinal = "C";
  $total_final *= -1;
}else{
  $sinal = "D";
}
//echo "Total Final:$total_final - $sinal<br>";
//echo "Total Anterior:$total_anterior<br>";
//echo "Total Debitos:$total_debitos<br>";
//echo "Total Creditos: $total_creditos <br>";
$pdf->cell(22,$alt,db_formatar($total_final,'f'),0,0,"R",0);
$pdf->cell(2,$alt,$sinal,0,1,"R",0);


if ( $pdf->gety() > ( $pdf->h - 40 ) )
    $pdf->addpage("L");

$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);


//echo $ass_pref;
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);

$pdf->Output();
   
?>