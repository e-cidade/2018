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

//setlocale(LC_ALL,"pt_BR");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");

include("fpdf151/pdf.php");
// pesquisa a conta mae da receita
$tipo_mesini = 1;
$tipo_mesfim = 1;

$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinst ;
        $xvirg = ', ';
}

$origem="a";
if($origem == "O"){
    $xtipo = "ORÇAMENTO";
}else{
    $xtipo = "BALANÇO";
    if($opcao == 3)
      $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
    else
      $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}

$head3 = "RELATORIO  DA RECEITA ";
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$sql = db_receitasaldo(11,1,$opcao,true,'',$anousu,$dataini,$datafin,true);
$sql1 = "select o70_codigo,
                o15_descr,
		sum(saldo_inicial)              as saldo_inicial, 
		sum(saldo_prevadic_acum)        as saldo_prevadic_acum, 
		sum(saldo_anterior)             as saldo_anterior,
		sum(saldo_arrecadado)           as saldo_arrecadado,
		sum(saldo_a_arrecadar)          as saldo_a_arrecadar,
		sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado
         from ( $sql ) as x
	 where o70_codigo > 0
	 group by o70_codigo,
	        o15_descr";

$result = pg_exec($sql1);

//db_criatabela($result);


$pagina = 1;
$tottotal = 0;
$total_saldo_inicial              = 0;
$total_saldo_prevadic_acum        = 0;
$total_saldo_anterior             = 0;
$total_saldo_arrecadado           = 0;
$total_saldo_a_arrecadar          = 0;  
$total_saldo_arrecadado_acumulado = 0;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
	
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',6);
    $pdf->cell(20,$alt,"RECURSO",0,0,"L",0);
    $pdf->cell(75,$alt,"DESCRIÇÃO",0,0,"L",0);
    if($origem == "O"){
      $pdf->cell(17,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell(17,$alt,"PREV.ADIC.",0,1,"R",0);
    }else{
      $pdf->cell(17,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell(17,$alt,"PREV.ADIC.",0,0,"R",0);
      $pdf->cell(17,$alt,"ARRECADADO",0,0,"R",0);
      $pdf->cell(17,$alt,"ARREC. ANO",0,0,"R",0);
      $pdf->cell(17,$alt,"DIFERENÇA",0,1,"R",0);
    }
    $pdf->ln(3);
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(20,$alt,db_formatar($o70_codigo,'recurso'),0,0,"L",0);
  $pdf->cell(75,$alt,$o15_descr,0,0,"L",0);
  if($origem == "O"){
    $pdf->cell(20,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($saldo_prevadic_acum,'f'),0,1,"R",0);
  }else{
    $pdf->cell(20,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($saldo_arrecadado,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($saldo_arrecadado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($saldo_a_arrecadar,'f'),0,1,"R",0);
  }
  $total_saldo_inicial              += $saldo_inicial             ;
  $total_saldo_prevadic_acum        += $saldo_prevadic_acum       ;
  $total_saldo_anterior             += $saldo_anterior            ;
  $total_saldo_arrecadado           += $saldo_arrecadado          ;
  $total_saldo_a_arrecadar          += $saldo_a_arrecadar         ;  
  $total_saldo_arrecadado_acumulado += $saldo_arrecadado_acumulado;



}
$pdf->setfont('arial','B',6);
$pdf->cell(20,$alt,'',0,0,"L",0);
$pdf->cell(75,$alt,'TOTAL ',0,0,"L",0);
if($origem == "O"){
  $pdf->cell(20,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,1,"R",0);
}else{
  $pdf->cell(20,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_saldo_arrecadado,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_saldo_arrecadado_acumulado,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_saldo_a_arrecadar,'f'),0,1,"R",0);
}
$pdf->Output();

pg_exec("commit");

?>