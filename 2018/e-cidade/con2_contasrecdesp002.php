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

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$numero_instit = pg_numrows($resultinst);
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}


$head2 = "CONFERENCIA DE DESPESA/RECEITA";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head4 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d');

//-- PARAMETROS
$movimento ="S";
$tipo ="A";
// $encerramento -> com encerramento de exercicio ou nao

if ( $movimento == "S" )
  $xmov = "Somente com Movimento";
else
  $xmov = "Todas";
if ( $tipo == "S" )
  $head5 = "SINTÉTICO - ".$xmov;
else
  $head5 = "ANALÍTICO - ".$xmov;

$head6 = "INSTITUIÇÕES : ".$descr_inst;
$where = " r.c61_instit in (".str_replace('-',', ',$db_selinstit).") ";

if ($encerramento=='s')
  $encerramento='true';
else
  $encerramento='false';

$result = db_planocontassaldo_desp_rec(db_getsession("DB_anousu"),$perini,$perfin,false,$where,'',$encerramento);

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

/*
   if( ( $tipo == "S" ) && ( $c61_reduz != 0 ) )
     continue;
     
   if(substr($estrutural,0,1) == '3' )  
     continue;
     
   if(substr($estrutural,0,1) == '4' )  
     continue;

   if(substr($estrutural,0,1) == '34' && $estrutural <> '340000000000000')  
     continue;
     
   if(substr($estrutural,0,1) == '41' && $estrutural <> '410000000000000')  
     continue;
     
   if(substr($estrutural,0,1) == '42' && $estrutural <> '420000000000000')  
     continue;
*/
   if( ( $movimento == "S" ) && ( ( $saldo_anterior + $saldo_anterior_debito + $saldo_anterior_credito) == 0 ) )
     continue;
     
   if(($pdf->gety() > $pdf->h - 32) || $pagina == 1){
     $pagina = 0;
     $pdf->addpage('L');
     $pdf->setfont('arial','b',7);
     $pdf->cell(28,$alt,'ESTRUTURAL',"B",0,"C",0);
     $pdf->cell(10,$alt,'REDUZIDO',"B",0,"C",0);
     $pdf->cell(125,$alt,'DESCRIÇÃO DA CONTA',"B",0,"C",0);
     $pdf->cell(20,$alt,'RECURSO',"B",0,"C",0);
     $pdf->cell(8,$alt,'SIS',"B",0,"C",0);
     $pdf->cell(22,$alt,'SALDO ANTERIOR',"B",0,"R",0);
     $pdf->cell(2,$alt,'',"B",0,"R",0);
     $pdf->cell(22,$alt,'DÉBITOS',"B",0,"R",0);
     $pdf->cell(22,$alt,'CRÉDITOS',"B",0,"R",0);
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
   }
   $resconta = pg_query("select conplanoconta.* 
                         from conplanoconta 
			 where c63_codcon = $c61_codcon
			 and c63_anousu = ".db_getsession("DB_anousu"));
   if(pg_numrows($resconta) > 0)
     db_fieldsmemory($resconta,0);
   if($c61_reduz != 0){
     $pdf->setfont('arial','',7);
   }else{
     $pdf->setfont('arial','B',7);
   }
   $pdf->cell(28,$alt,db_formatar($estrutural,'receita'),0,0,"L",0,0); 
   if($numero_instit>1){
     $pdf->cell(10,$alt,"",0,0,"C",0,0); 
   }else{
     $pdf->cell(10,$alt,($c61_reduz == 0?'':$c61_reduz),0,0,"C",0,0); 
   }
   /*if($conta == 'S'){
     $pdf->cell(125,$alt,(pg_numrows($resconta) == 0?$espaco.$c60_descr:$espaco.$c60_descr.'   ( Bco: '.$c63_banco.'  Ag: '.$c63_agencia.'  Cta: '.$c63_conta.')'),0,0,"L",0,0,'.'); 
   }else{
     $pdf->cell(125,$alt,$espaco.$c60_descr,0,0,"L",0,0,'.'); 
   }*/
   $pdf->cell(125,$alt,$espaco.$c60_descr,0,0,"L",0,0,'.'); 

   $pdf->cell(20,$alt,($c61_reduz == 0?'':$c61_codigo),0,0,"C",0);

   if($c61_reduz != 0){
     $resconta = pg_query("select c52_descrred
                         from conplano 
			      inner join consistema on c52_codsis = c60_codsis
			 where c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut = '$estrutural'");
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

if ( $pdf->gety() > ( $pdf->h - 40 ) )
    $pdf->addpage("L");

$pdf->Output();
   
?>