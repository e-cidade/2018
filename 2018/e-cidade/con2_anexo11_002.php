<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("dbforms/db_funcoes.php"));


$classinatura = new cl_assinatura;

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//db_postmemory($HTTP_POST_VARS,2);exit;

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else{
         $descr_inst .= $xvirg.$nomeinst;
    }
        $xvirg = ', ';
}

if($tipo_emp == "E")
   $head1 = "COMPARATIVO DA DESPESA ORÇADA COM A REALIZADA - EMPENHADO";
elseif($tipo_emp == "L")
   $head1 = "COMPARATIVO DA DESPESA ORÇADA COM A REALIZADA - LIQUIDADO";
else
   $head1 = "COMPARATIVO DA DESPESA ORÇADA COM A REALIZADA - PAGO";
  
$head2 = "EXERCÍCIO: ".db_getsession("DB_anousu");

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42) {
          $descr_inst = substr($descr_inst,0,190);
     }
}

$head3 = "INSTITUIÇÕES : ".$descr_inst;
$head4 = "ANEXO 11 - ".strtoupper(db_mes($mes)) ;

$nivela = substr($vernivel,0,1);
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
if($nivela >= 1){
  $sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao) ";
}
if($nivela >= 2){
  $sele_work .= " and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
}
  
db_query("begin");
db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");
  
$xcampos = split("-",$orgaos);

for($i=0;$i < sizeof($xcampos);$i++){
   $where = '';
   $virgula = ''; 
   $xxcampos = split("_",$xcampos[$i]);
   for($ii=0;$ii<sizeof($xxcampos);$ii++){
      if($ii > 0){
        $where .= $virgula.$xxcampos[$ii];
        $virgula = ', ';
      }
   }
   if($nivela == 1)
     $where .= ",0,0,0,0,0,0,0";
   if($nivela == 2)
     $where .= ",0,0,0,0,0,0";
   db_query("insert into t values($where)");
}

//db_criatabela(db_query("select * from t"));

$anousu = db_getsession("DB_anousu");

//$dataini = db_getsession("DB_anousu").'-'.$mes.'-'.'01';
$dataini = db_getsession("DB_anousu").'-01-01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));
//echo $dataini."<br>";
//echo $datafin."<br>";
$result = db_elementosaldo($tipo_agrupa,4,$sele_work,$anousu,$dataini,$datafin);
					     
// db_criatabela($result);exit;


$fonte = 8;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);

$alt          = 4;
$aux          = 0;
$col          = 0;
$pagina       = 1;
$qualo        = 0;
$qualu        = 0;
$empliq       = 0;

$tot1_orgao   = 0;
$tot2_orgao   = 0;
$tot3_orgao   = 0;
$tot4_orgao   = 0;
$tot5_orgao   = 0;
$tot1_unidade = 0;
$tot2_unidade = 0;
$tot3_unidade = 0;
$tot4_unidade = 0;
$tot5_unidade = 0;
$tot1_geral   = 0;
$tot2_geral   = 0;
$tot3_geral   = 0;
$tot4_geral   = 0;
$tot5_geral   = 0;


if($tipo_agrupa > 0){
  db_fieldsmemory($result,0);
  $qualo = $o58_orgao;
  if($tipo_agrupa == 2)
    $qualu = $o58_orgao.$o58_unidade;
}


for($x = 0; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x,true);
  $aux = $elemento;
  
  if($tipo_emp == "E"){
    $empliq = $empenhado - $anulado;
  }elseif($tipo_emp == "L"){
    $empliq = $liquidado;
  }else{
    $empliq = $pago;
  }
  
  if (substr($aux,1,11) == "00000000000"){
     $col = 1;
  }elseif (substr($aux,2,10) == "0000000000"){
     $col = '';
     $tot1_orgao   += $dot_ini + $suplemen - $reduzido;
     $tot2_orgao   += $especial; //novo
     $tot3_orgao   += $dot_ini + $suplemen + $especial - $reduzido;
     $tot4_orgao   += $empliq;
     $tot5_orgao   += $dot_ini + $suplemen + $especial - $reduzido - $empliq;
     
     $tot1_unidade += $dot_ini + $suplemen - $reduzido;           
     $tot2_unidade += $especial; // novo
     $tot3_unidade += $dot_ini + $suplemen + $especial - $reduzido;           
     $tot4_unidade += $empliq;                                        
     $tot5_unidade += $dot_ini + $suplemen + $especial - $reduzido - $empliq; 

     $tot1_geral   += $dot_ini + $suplemen - $reduzido;           
     $tot2_geral   += $especial;    // novo                                         
     $tot3_geral   += $dot_ini + $suplemen + $especial - $reduzido;           
     $tot4_geral   += $empliq;                                        
     $tot5_geral   += $dot_ini + $suplemen + $especial - $reduzido - $empliq; 
  }elseif (substr($aux,3,9) == "000000000"){
     $col = '  ';
  }elseif (substr($aux,4,8) == "00000000"){
     $col = '    ';
  }elseif (substr($aux,7,6) == "000000"){
     $col = '      ';
  }elseif (substr($aux,9,4) == "0000"){
     $col = '        ';
  }else{
     $col = '          ';
  }   

  if($qualu != $o58_orgao.$o58_unidade && $tipo_agrupa == 2){
    $pagina = 2;
    $qualu = $o58_orgao.$o58_unidade;
    $pdf->setfont('arial','b',$fonte);
    $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0,'','.');
    $pdf->cell(22,$alt,db_formatar($tot1_unidade,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot2_unidade,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot3_unidade,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot4_unidade,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot5_unidade,'f'),0,1,"R",0);
    $tot1_unidade = 0;
    $tot2_unidade = 0;
    $tot3_unidade = 0;
    $tot4_unidade = 0;
    $tot5_unidade = 0;
  }
  
  if($qualo != $o58_orgao && $tipo_agrupa == 1){
    $pagina = 2;
    $qualo = $o58_orgao;
    $pdf->setfont('arial','b',$fonte);
    $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0,'','.');
    $pdf->cell(22,$alt,db_formatar($tot1_orgao,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot2_orgao,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot3_orgao,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot4_orgao,'f'),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($tot5_orgao,'f'),0,1,"R",0);
    $tot1_orgao   = 0;
    $tot2_orgao   = 0;
    $tot3_orgao   = 0;
    $tot4_orgao   = 0;
    $tot5_orgao   = 0;
  }
  
  if($pdf->gety()>$pdf->h-30 || $pagina > 0){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',$fonte);
    if($tipo_agrupa > 0){
       $pdf->cell(0,0.5,'',"TB",1,"C",0);
       $pdf->cell(10,$alt,"ÓRGÃO  -  ".db_formatar($o58_orgao,'orgao').'  -  '.$o40_descr,0,1,"L",0);
       if($tipo_agrupa == 1)
          $pdf->cell(0,0.5,'',"TB",1,"C",0);
    }
    if($tipo_agrupa == 2){
       $pdf->cell(10,$alt,"UNIDADE ORÇAMENTÁRIA  -  ".db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao')."  - ".$o41_descr,0,1,"L",0);
       $pdf->cell(0,0.5,'',"TB",1,"C",0);
    }
    $pdf->ln(2);

    $pdf->setfont('arial','b',$fonte-1);

    $pdf->cell(80,$alt,"","0",0,"L",0);
    $pdf->cell(25,$alt,"CRÉDITOS","0",0,"C",0);
    $pdf->cell(25,$alt,"CRÉDITOS","0",0,"C",0);
    $pdf->cell(20,$alt,"","0",0,"R",0);
    $pdf->cell(20,$alt,"","0",0,"R",0);
    $pdf->cell(20,$alt,"","0",1,"R",0);
    
    $pdf->cell(80,$alt,"","0",0,"L",0);
    $pdf->cell(25,$alt,"ORÇAMENTARIOS E","0",0,"C",0);
    $pdf->cell(25,$alt,"ESPECIAIS E","0",0,"C",0);
    $pdf->cell(20,$alt,"","0",0,"R",0);
    $pdf->cell(20,$alt,"","0",0,"R",0);
    $pdf->cell(20,$alt,"","0",1,"R",0);
    
    $pdf->cell(80,$alt,"CATEGORIA ECONÔMICA","B",0,"L",0);
    $pdf->cell(25,$alt,"SUPLEMENTARES","B",0,"C",0);
    $pdf->cell(25,$alt,"EXTRAORDINÁRIOS","B",0,"C",0);
    $pdf->cell(20,$alt,"T O T A L","B",0,"R",0);
    $pdf->cell(20,$alt,"REALIZADO","B",0,"R",0);
    $pdf->cell(20,$alt,"DIFERENÇA","B",1,"R",0);    
    $pdf->ln(3);
    
  
  }
   
   $pdf->setfont('arial','',$fonte);
   $pdf->cell(80,$alt,($col==1?" ":$col).substr($descr,0,40),0,0,"L",0);
   $pdf->cell(25,$alt,db_formatar($dot_ini + $suplemen - $reduzido,'f'),0,0,"R",0);
   $pdf->cell(25,$alt,db_formatar($especial,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($dot_ini + $suplemen + $especial - $reduzido,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($empliq,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($dot_ini + $suplemen + $especial - $reduzido - $empliq,'f'),0,1,"R",0);
}

$pdf->setfont('arial','b',$fonte);

if($tipo_agrupa == 2){
  $pdf->cell(80,$alt,'TOTAL DA UNIDADE',0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($tot1_unidade,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot2_unidade,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot3_unidade,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot4_unidade,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot5_unidade,'f'),0,1,"R",0);
}

if($tipo_agrupa == 1){
  $pdf->cell(80,$alt,'TOTAL DO ÓRGÃO',0,0,"L",0,'','.');
  $pdf->cell(25,$alt,db_formatar($tot1_orgao,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot2_orgao,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot3_orgao,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot4_orgao,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($tot5_orgao,'f'),0,1,"R",0);
}


$pdf->cell(80,$alt,'TOTAL GERAL',0,0,"L",0,'','.');
$pdf->cell(25,$alt,db_formatar($tot1_geral,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot2_geral,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot3_geral,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot4_geral,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot5_geral,'f'),0,1,"R",0);



$pdf->Ln(15);

assinaturas($pdf, $classinatura,'BG');




$pdf->Output();
   
?>
