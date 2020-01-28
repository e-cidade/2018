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
include("libs/db_libcontabilidade.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$xinstit = db_getsession("DB_instit");
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in ($xinstit) ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinst ;
        $xvirg = ', ';
}

$perini='2005-01-04';
$perfin='2005-01-04';


$head2 = "BALANCETE DE VERIFICAÇÃO";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head4 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d');

if ( $movimento == "S" )
  $xmov = "Somente com Movimento";
else
  $xmov = "Todas";

if ( $tipo == "S" )
  $head5 = "SINTÉTICO - ".$xmov;
else
  $head5 = "ANALÍTICO - ".$xmov;

$head6 = "INSTITUIÇÕES : ".$descr_inst;
  


$where = " c61_instit in ($xinstit) and c60_codsis in (5,6) ";
  
$result = db_planocontassaldo(db_getsession("DB_anousu"),$perini,$perfin,false,$where);

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
   
   if( ( $tipo == "S" ) && ( $c61_reduz != 0 ) )
     continue;
   
   if( substr($estrutural,0,1) != "1"  )
     continue;
     

   if( ( $movimento == "S" ) && ( ( $saldo_anterior + $saldo_anterior_debito + $saldo_anterior_credito) == 0 ) )
     continue;
     
   if(($pdf->gety() > $pdf->h - 32) || $pagina == 1){
     $pagina = 0;
     $pdf->addpage('L');
     $pdf->setfont('arial','b',7);
     $pdf->cell(25,$alt,'ESTRUTURAL',"B",0,"L",0);
     $pdf->cell(15,$alt,'REDUZIDO',"B",0,"R",0);
     $pdf->cell(130,$alt,'DESCRIÇÃO DA CONTA',"B",0,"C",0);
     $pdf->cell(20,$alt,'RECURSO',"B",0,"C",0);
     $pdf->cell(20,$alt,'SALDO ANTERIOR',"B",0,"R",0);
     $pdf->cell(2,$alt,'',"B",0,"R",0);
     $pdf->cell(20,$alt,'DÉBITOS',"B",0,"R",0);
     $pdf->cell(20,$alt,'CRÉDITOS',"B",0,"R",0);
     $pdf->cell(20,$alt,'SALDO',"B",0,"R",0);
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
     $pdf->setfont('arial','b',7);
   }else{
     $pdf->setfont('arial','',7);
   }
   $resconta = pg_query("select * from conplanoconta where c63_anousu = ".db_getsession("DB_anousu")." and  c63_codcon = $c61_codcon");
   if(pg_numrows($resconta) > 0)
     db_fieldsmemory($resconta,0);
   
   $pdf->cell(25,$alt,db_formatar($estrutural,'receita_int'),0,0,"L",0,0); 
   $pdf->cell(15,$alt,($c61_reduz == 0?'':$c61_reduz),0,0,"C",0,0); 

   $sql = "select fc_saltessaldo($c61_reduz,'$perini','$perfin',null)";
   $resultc = pg_exec($sql);
   $saldo = pg_result($resultc,0,0);


   $pdf->cell(130,$alt,$saldo,0,0,"L",0,0,'.'); 
   
   //$pdf->cell(130,$alt,(pg_numrows($resconta) == 0?$espaco.$c60_descr:$espaco.$c60_descr.'   ( Bco: '.$c63_banco.'  Ag: '.$c63_agencia.'  Cta: '.$c63_conta),0,0,"L",0,0,'.'); 
   
   $pdf->cell(20,$alt,($c61_reduz == 0?'':$c61_codigo),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($saldo_anterior,'f'),0,0,"R",0);
   $pdf->cell(2,$alt,$sinal_anterior,0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($saldo_anterior_debito,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($saldo_anterior_credito,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($saldo_final,'f'),0,0,"R",0);
   $pdf->cell(2,$alt,$sinal_anterior,0,1,"R",0);
}


$pdf->Output();
   
?>