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
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//db_postmemory($HTTP_POST_VARS,2);exit;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinst ;
        $xvirg = ', ';
}
if($tipo_emp == "O"){
   $head3 = "DESPESAS COM PESSOAL - ORÇAMENTO";
   $tipo_saldo = 1;
   $descr_mes = '';
}elseif($tipo_emp == "E"){
   $head3 = "DESPESAS COM PESSOAL - EMPENHADO";
   $tipo_saldo = 4;
   $descr_mes = ' - '.strtoupper(db_mes(1));
}elseif($tipo_emp == "L"){
   $head3 = "DESPESAS COM PESSOAL - LIQUIDADO";
   $tipo_saldo = 4;
   $descr_mes = ' - '.strtoupper(db_mes(1));
}else{
   $head3 = "DESPESAS COM PESSOAL - PAGO";
   $tipo_saldo = 4;
   $descr_mes = ' - '.strtoupper(db_mes(1));
}

$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu").$descr_mes;
$head5 = "INSTITUIÇÕES : ".$descr_inst;
//$head6 = "ANEXO 11 - ".strtoupper(db_mes($mes)) ;

$nivela = substr($vernivel,0,1);

$sele_ele = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$anousu = db_getsession("DB_anousu");

$dataini = db_getsession("DB_anousu").'-01-01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

$sqlele1 = db_elementosaldo(0,$tipo_saldo,$sele_ele,$anousu,$dataini,$datafin,true);
$sqlele  = "select * 
            from ($sqlele1) as ele
	    where elemento in (select distinct o56_elemento 
	                       from orcparamelemento 
			            inner join orcelemento on o44_codele = o56_codele and o56_anousu = $anousu ) 
           ";
$resultele = pg_query($sqlele);
//db_criatabela($resultele);

$sele_rec = ' d.o70_instit in ('.str_replace('-',', ',$db_selinstit).') ';

$sqlrec1 = db_receitasaldo(11,1,$tipo_saldo,true,$sele_rec,$anousu,$dataini,$datafin,true);
$sqlrec = "select * 
           from ($sqlrec1) as rec 
	   where o57_fonte in (select distinct o57_fonte 
	                       from orcparamfontes 
			            inner join orcfontes on o43_codfon = o57_codfon and o57_anousu = $anousu)";
$resultrec = pg_query($sqlrec);
//db_criatabela($resultrec);exit;

$pdf = new PDF(); 
$pdf->Open();
$pdf->addpage();
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','B',9);
$pdf->cell(50,6,'RECEITAS',0,1,"L",0);
$pdf->setfont('arial','',8);
$totalrec = 0;
for($x = 0; $x < pg_numrows($resultrec);$x++){
  db_fieldsmemory($resultrec,$x); 
  if ($tipo_saldo == 1){
     $valor = $saldo_inicial;
  }else{
     $valor = $saldo_arrecadado_acumulado;
  }
  $pdf->cell(70,5,$o57_descr,0,0,"L",0);
  $pdf->cell(25,5,db_formatar($valor,'f'),0,1,"R",0);
  $totalrec += $valor;
}
$pdf->cell(70,5,'TOTAL',0,0,"L",0,'','.');
$pdf->cell(25,5,db_formatar($totalrec,'f'),0,1,"R",0);
$pdf->ln(3);
$pdf->setfont('arial','B',9);
$pdf->cell(50,6,'DESPESAS',0,1,"L",0);
$pdf->setfont('arial','',8);
$totalele = 0;
for($x = 0; $x < pg_numrows($resultele);$x++){
  db_fieldsmemory($resultele,$x);  
  $valor = $dot_ini;
  $pdf->cell(70,5,$descr,0,0,"L",0);
  $pdf->cell(25,5,db_formatar($valor,'f'),0,1,"R",0);
  $totalele += $valor;
}
$pdf->cell(70,5,'TOTAL',0,0,"L",0,'','.');
$pdf->cell(25,5,db_formatar($totalele,'f'),0,1,"R",0);
if($totalrec > 0){
  $pdf->ln(3);
  $pdf->cell(70,5,'PERCENTUAL',0,0,"L",0,'','.');
  $pdf->cell(25,5,db_formatar($totalele*100/$totalrec,'f').'%',0,1,"R",0);
}

$pdf->Output();
   
?>