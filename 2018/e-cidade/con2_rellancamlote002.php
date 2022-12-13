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
include("fpdf151/assinatura.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

///////////////////////////////////////////////////////////////////////////////

$anousu  = db_getsession("DB_anousu");
$instit  = db_getsession("DB_instit");
$dbwhere = "";
if(isset($c78_chave)&&$c78_chave!="") {
   $dbwhere=" c78_chave='$c78_chave'  ";
   if(isset($data_ini)&&$data_ini!="") {
       $dbwhere.=" and c78_data >= '$data_ini' and c78_data <='$data_fim'  ";
   } 
   else {
       if(isset($data_ini)&&$data_ini!="") {
           $dbwhere.="c78_data >= '$data_ini' and c78_data <='$data_fim'  ";
	}  
   }	
}
else {
   if(isset($data_ini)&&$data_ini!="") {
       $dbwhere.="c78_data >= '$data_ini' and c78_data <='$data_fim'  ";
   }  
}

if(strlen($dbwhere)>0) {
    $sql="select c69_data,
                 c69_codlan,
                 c69_sequen,
                 c69_valor,
                 c69_debito,
                 c1.c60_descr as debito_descr,
                 c69_credito,
                 c2.c60_descr as credito_descr 
          from conlancamdig
               inner join conlancamval on c69_codlan  = c78_codlan       				   
               inner join conplanoreduz r1 on r1.c61_reduz = c69_debito and r1.c61_anousu= $anousu and r1.c61_instit = $instit
               inner join conplano c1 on c1.c60_codcon =r1.c61_codcon and c1.c60_anousu=r1.c61_anousu
               inner join conplanoreduz r2 on r2.c61_reduz = c69_credito and r2.c61_anousu= $anousu and  r2.c61_instit = $instit
               inner join conplano c2 on c2.c60_codcon =r2.c61_codcon and c2.c60_anousu=r2.c61_anousu 
          where $dbwhere   
	  order by c69_sequen, c69_data";
    $resultado = @pg_exec($sql);
//    db_criatabela($resultado); exit;
}

if(@pg_numrows($resultado)==0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=<center>Não existem Lançamento de lotes.</center>');
    exit;
}

$numrows = pg_numrows($resultado);

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");

$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $descr_inst .= $xvirg.$nomeinst ;
    $xvirg = ', ';
}

$head3   = "RELATÓRIO DE LANÇAMENTO POR LOTE";
$head4   = "EXERCÍCIO    : ".$anousu;
$head5   = "INSTITUIÇÕES : ".$descr_inst;

if(isset($data_ini)&&$data_ini!="") {
  $head6 = "PERÍODO : ".db_formatar($data_ini,"d")." a ".db_formatar($data_fim,"d");
}


$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setleftmargin(5);
$pdf->setfillcolor(235);
$alt         = 4;
$pagina      = 1;
$fonte       = 8;
$total_geral = 0;
$pdf->addpage();
$pdf->setfont('arial','b',$fonte);
$pdf->cell(18,$alt,"DATA",1,0,"C",1);
$pdf->cell(25,$alt,"CODIGO LANC.",1,0,"R",1);
$pdf->cell(21,$alt,"SEQUÊNCIA",1,0,"R",1);
$pdf->cell(27,$alt,"CONTA DEBITO",1,0,"R",1);
$pdf->cell(80,$alt,"DESCRIÇÃO DEBITO",1,0,"L",1);
$pdf->cell(28,$alt,"CONTA CRÉDITO",1,0,"R",1);
$pdf->cell(60,$alt,"DESCRIÇÃO CRÉDITO",1,0,"L",1);
$pdf->cell(30,$alt,"VALOR",1,1,"R",1);

for($i=0; $i < $numrows; $i++) {
   db_fieldsmemory($resultado,$i);

   $pdf->cell(18,$alt,db_formatar($c69_data,"d"),0,0,"C",0);
   $pdf->cell(25,$alt,$c69_codlan,0,0,"R",0);
   $pdf->cell(21,$alt,$c69_sequen,0,0,"R",0);
   $pdf->cell(27,$alt,$c69_debito,0,0,"R",0);
   $pdf->cell(80,$alt,$debito_descr,0,0,"L",0);
   $pdf->cell(28,$alt,$c69_credito,0,0,"R",0);
   $pdf->cell(60,$alt,$credito_descr,0,0,"L",0);
   $pdf->cell(30,$alt,db_formatar($c69_valor,"f"),0,1,"R",0);
 
   $total_geral += $c69_valor;
   
   if($pdf->gety() > $pdf->h - 20){
       $pagina = 0;
       $pdf->addpage();
       $pdf->ln(5);
       $pdf->setfont('arial','B',$fonte);
       $pdf->cell(18,$alt,"DATA",1,0,"C",1);
       $pdf->cell(25,$alt,"CODIGO LANC.",1,0,"R",1);
       $pdf->cell(21,$alt,"SEQUÊNCIA",1,0,"R",1);
       $pdf->cell(27,$alt,"CONTA DEBITO",1,0,"R",1);
       $pdf->cell(80,$alt,"DESCRIÇÃO DEBITO",1,0,"L",1);
       $pdf->cell(28,$alt,"CONTA CRÉDITO",1,0,"R",1);
       $pdf->cell(60,$alt,"DESCRIÇÃO CRÉDITO",1,0,"L",1);
       $pdf->cell(30,$alt,"VALOR",1,1,"R",1);
   }
}

$pdf->ln(3);
$pdf->setfont('arial','b',$fonte+2);
$pdf->cell(289,$alt+2,"TOTAL: ".db_formatar($total_geral,"f"),"TB",1,"R",0);

$pdf->Output();

?>