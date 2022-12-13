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
include ("fpdf151/assinatura.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");


$classinatura = new cl_assinatura;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$anousu  = db_getsession("DB_anousu");
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }
        $xvirg = ', ';
}

$head2  = "DEMONSTRATIVO DA DÍVIDA FUNDADA INTERNA";
$head3  = "EXERCÍCIO ".$anousu;

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,150);
     }
}

$head5  = "INSTITUIÇÕES : ".$descr_inst;
$head6  = "ANEXO 16";

$sql    = "select * from conreltitulos where c44_anousu = $anousu and c44_instit in(".str_replace('-',', ',$db_selinstit).") ";
$result = pg_exec($sql);
//db_criatabela($result);exit;

if(pg_numrows($result) == 0) {
//	db_msgbox("Não existem informações para este exercício " . $anousu);
//	echo "<script>window.close();</script>";
}

$total_c44_valemiss   = 0;
$total_c44_saldo      = 0;
$total_c44_movemiss   = 0;
$total_c44_movresgate = 0;
$total_c44_saldoqtd   = 0;
$total_c44_saldovalor = 0;

$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','',9);
$alt    = 4;
$pagina = 1;

if($pdf->gety() > $pdf->h -30 || $pagina == 1){
     $pagina = 0;
     $pdf->addpage();
     $pdf->setfont('arial','b',8);
     $pdf->cell(140,$alt,"AUTORIZAÇÕES","TBR",0,"C",0);
     $pdf->cell(30,$alt,"SALDO ANTERIOR","T",0,"C",0);
     $pdf->cell(60,$alt,"MOVIMENTO NO EXERCÍCIO R$","TBL",0,"C",0);
     $pdf->cell(50,$alt,"SALDO P/ O EXERC SEGUINTE","TBL",1,"C",0);
     $pdf->cell(70,$alt,"LEIS","TBR",0,"C",0);
     $pdf->cell(40,$alt,"QTDE (Nº E DATA)","TBR",0,"C",0);
     $pdf->cell(30,$alt,"VLR DE EMISSÃO R$","TBR",0,"C",0);
     $pdf->cell(30,$alt,"EM CIRCULAÇÃO","BR",0,"C",0);
     $pdf->cell(30,$alt,"CORR. MONETÁRIA","LBR",0,"C",0);
     $pdf->cell(30,$alt,"RESGATE/BAIXA","LBR",0,"C",0);
     $pdf->cell(10,$alt,"QTDE","LBR",0,"C",0);
     $pdf->cell(40,$alt,"VALOR","LTB",1,"C",0);
}

for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   if($pdf->gety() > $pdf->h -30 || $pagina == 1){
     $pagina = 0;
     $pdf->addpage();
     $pdf->setfont('arial','b',8);
     $pdf->cell(140,$alt,"AUTORIZAÇÕES","TBR",0,"C",0);
     $pdf->cell(30,$alt,"SALDO ANTERIOR","T",0,"C",0);
     $pdf->cell(60,$alt,"MOVIMENTO NO EXERCÍCIO R$","TBL",0,"C",0);
     $pdf->cell(50,$alt,"SALDO P/ O EXERC SEGUINTE","TBL",1,"C",0);
     $pdf->cell(70,$alt,"LEIS","TBR",0,"C",0);
     $pdf->cell(40,$alt,"QTDE (Nº E DATA)","TBR",0,"C",0);
     $pdf->cell(30,$alt,"VLR DE EMISSÃO R$","TBR",0,"C",0);
     $pdf->cell(30,$alt,"EM CIRCULAÇÃO","BR",0,"C",0);
     $pdf->cell(30,$alt,"CORR. MONETÁRIA","LBR",0,"C",0);
     $pdf->cell(30,$alt,"RESGATE/BAIXA","LBR",0,"C",0);
     $pdf->cell(10,$alt,"QTDE","LBR",0,"C",0);
     $pdf->cell(40,$alt,"VALOR","LTB",1,"C",0);
   }
   $pdf->setfont('arial','',8);
   $pdf->cell(70,$alt,$c44_lei,"BR",0,"L",0);
   $pdf->setfont('arial','',9);
   $pdf->cell(40,$alt,$c44_quantidade,"BR",0,"L",0);
   $pdf->cell(30,$alt,db_formatar($c44_valemiss,'f'),"BR",0,"R",0);
   $pdf->cell(30,$alt,db_formatar($c44_saldo,'f'),"BLR",0,"R",0);
   $pdf->cell(30,$alt,db_formatar($c44_movemiss,'f'),"BR",0,"R",0);
   $pdf->cell(30,$alt,db_formatar($c44_movresgate,'f'),"BR",0,"R",0);
   $pdf->cell(10,$alt,$c44_saldoqtd,"BR",0,"R",0);
   $pdf->cell(40,$alt,db_formatar($c44_saldovalor,'f'),"B",1,"R",0);
   
   $total_c44_valemiss   += $c44_valemiss;
   $total_c44_saldo      += $c44_saldo;
   $total_c44_movemiss   += $c44_movemiss;
   $total_c44_movresgate += $c44_movresgate;
   $total_c44_saldoqtd   += $c44_saldoqtd;
   $total_c44_saldovalor += $c44_saldovalor;
}
$pdf->setfont('arial','',9);
$pdf->cell(70,$alt,"","TBR",0,"L",0);
$pdf->cell(40,$alt,"","TBR",0,"L",0);
$pdf->cell(30,$alt,"","TBR",0,"R",0);
$pdf->cell(30,$alt,"","TBR",0,"R",0);
$pdf->cell(30,$alt,"","TBR",0,"R",0);
$pdf->cell(30,$alt,"","TBR",0,"R",0);
$pdf->cell(10,$alt,"","TBR",0,"R",0);
$pdf->cell(40,$alt,"","TB",1,"R",0);

$pdf->cell(70,$alt,"","TBR",0,"L",0);
$pdf->cell(40,$alt,"TOTAIS:","TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_c44_valemiss,'f'),"TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_c44_saldo,'f'),"TBLR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_c44_movemiss,'f'),"TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($total_c44_movresgate,'f'),"TBR",0,"R",0);
$pdf->cell(10,$alt,$total_c44_saldoqtd,"TBR",0,"R",0);
$pdf->cell(40,$alt,db_formatar($total_c44_saldovalor,'f'),"TB",1,"R",0);


$pdf->ln(14);

assinaturas(&$pdf,&$classinatura,'BG');



$pdf->Output();
?>