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


include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;

$tipo_agrupa = substr($nivel,0,1);
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinstabrev ;
        $xvirg = ', ';
}

$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($tipo_balanco==2){
    $xtipo .= "-EMPENHADO";
  }else if($tipo_balanco==3){
    $xtipo .= "-LIQUIDADO";
  }else{
    $xtipo .= "-PAGO";
  }  
 
  if($opcao == 3)
    $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}

$head2 = "RESUMO DA DESPESA";
$head3 = "UNIDADE / CATEGORIA ECONÔMICA ";
$head4 = "ANEXO (2) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

pg_exec("begin");


$sql = "create temp table work as 
        select distinct o58_orgao as orgao, o58_unidade as unidade, substr(o56_elemento,1,3) as elemento,0::float8 as valor 
        from orcdotacao d
	     inner join orcelemento e on d.o58_codele = e.o56_codele  and d.o58_anousu = e.o56_anousu
        where o58_anousu = ".db_getsession("DB_anousu");


$result = pg_exec($sql);

//$result = pg_exec("select * from work");
//db_criatabela($result);exit;

$xcampos = split("-",$orgaos);


if(substr($nivel,0,1) == '1'){
  $xwhere  = " trim(to_char(orgao,'99')) in (";
  $xwhere1 = " trim(to_char(o58_orgao,'99')) in (";
}elseif(substr($nivel,0,1) == '2'){
  $xwhere = " trim(to_char(orgao,'99'))||'.'||trim(to_char(unidade,'99')) in (";
  $xwhere1 = " trim(to_char(o58_orgao,'99'))||'.'||trim(to_char(o58_unidade,'99')) in (";
}elseif(substr($nivel,0,1) == '7'){
  $xwhere = " to_char(elemento,'9999999999999') in (";
  $xwhere1 = " to_char(o58_elemento,'9999999999999') in (";
}
$virgula1 = ' ';
for($i=0;$i < sizeof($xcampos);$i++){
   $xxcampos = split("_",$xcampos[$i]);
   $virgula = '';
   $where  = "'";
   $where1 = "'";
   for($ii=0;$ii<sizeof($xxcampos);$ii++){
      if($ii > 0){
        $where  .= $virgula.$xxcampos[$ii];
        $where1 .= $virgula.$xxcampos[$ii];
        $virgula = '.';
      }
   }
   $xwhere  .= $virgula1.$where."'";
   $xwhere1 .= $virgula1.$where1."'";
   $virgula1 = ', ';
   
}

$xwhere .= ')';

$xwhere1 .= ") and o58_instit in (".str_replace('-',', ',$db_selinstit).")";



$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;
$result_rec = db_dotacaosaldo(7,2,3,true,$xwhere1,$anousu,$dataini,$datafin,null,null,null,$tipo_balanco);
//echo $tipo_balanco;
//db_criatabela($result_rec);exit;
$valor = 0;
if($origem == "O"){
  $tipo_balanco=1;
}
for($i=0;$i<pg_numrows($result_rec);$i++){
  db_fieldsmemory($result_rec,$i);
  if($tipo_balanco==1){
    $valor = $dot_ini;
  }else if($tipo_balanco == 2){
    $valor = $empenhado-$anulado;
  }else if($tipo_balanco == 3){
    $valor = $liquidado;
  }else {
    $valor = $pago;
  }
 
  $o56_elemento = substr($o58_elemento,0,3);  
  $sql = "update work set valor = valor+$valor where work.elemento = '$o56_elemento' and orgao = ".$o58_orgao. " and unidade = ".$o58_unidade;
  $result = pg_exec($sql);

}
// pesquisa as dotacoes

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$sql = "select ".($tipo_agrupa == 1?"orgao,o40_descr,":"orgao,o40_descr, unidade,o41_descr, ")."
               sum(case when elemento = '331' then valor else 0 end) as valor1 ,
               sum(case when elemento = '332' then valor else 0 end) as valor2 ,
               sum(case when elemento = '333' then valor else 0 end) as valor3 
              
        from work  
	     inner join orcorgao o on work.orgao = o.o40_orgao and o.o40_anousu = ".db_getsession("DB_anousu")."
	     inner join orcunidade u on u.o41_orgao = work.orgao and  work.unidade = u.o41_unidade and u.o41_anousu = ".db_getsession("DB_anousu")."
	where substr(elemento,1,1) = '3' and $xwhere
	group by ".($tipo_agrupa == 1?"orgao,o40_descr ":"orgao,o40_descr, unidade,o41_descr ")."  
	order by  ".($tipo_agrupa == 1?"orgao":"orgao, unidade");
$result = pg_exec($sql);


//db_criatabela($result);exit;

$tvalor1=0;
$tvalor2=0;
$tvalor3=0;
$ttotal4=0;
$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2);
    $pdf->cell(60,$alt,"UNIDADES","TLR",0,"L",0);
    $pdf->cell(105,$alt,"DESPESAS CORRENTES",1,0,"C",0);
    $pdf->cell(25,$alt,"TOTAL","TLR",1,"R",0);
    
    $pdf->cell(60,$alt,"ORÇAMENTÁRIAS","LBR",0,"L",0);
    $pdf->cell(35,$alt,"PESSOAL / ENC SOCIAIS",1,0,"R",0);
    $pdf->cell(35,$alt,"JUROS / ENCARG. DÍVIDA",1,0,"R",0);
    $pdf->cell(35,$alt,"OUTROS",1,0,"R",0);
    $pdf->cell(25,$alt,"","BLR",1,"R",0);
  }
  $pdf->setfont('arial','',6);
  if ($tipo_agrupa==1){
     $pdf->cell(60,$alt,db_formatar($orgao,'orgao')."-".substr($o40_descr,0,40),0,0,"L",0);
  } else {
    $pdf->cell(60,$alt,db_formatar($orgao,'orgao').db_formatar($unidade,'orgao')."-".substr($o41_descr,0,40),0,0,"L",0);
  } 
  $pdf->cell(35,$alt,db_formatar($valor1,'f'),0,0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor2,'f'),0,0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor3,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($valor1+$valor2+$valor3,'f'),0,1,"R",0);
 
  $tvalor1+=$valor1;
  $tvalor2+=$valor2;
  $tvalor3+=$valor3;
  $ttotal4+=$valor1+$valor2+$valor3;
  
  
}
$pdf->cell(60,$alt,"Total","T",0,"L",0);
$pdf->cell(35,$alt,db_formatar($tvalor1,'f'),"T",0,"R",0);
$pdf->cell(35,$alt,db_formatar($tvalor2,'f'),"T",0,"R",0);
$pdf->cell(35,$alt,db_formatar($tvalor3,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($ttotal4,'f'),"T",1,"R",0);

$sql = "select ".($tipo_agrupa == 1?"orgao,o40_descr,":"orgao,o40_descr, unidade,o41_descr, ")."
       sum(case when elemento = '344' then valor else 0 end) as valor1 ,
       sum(case when elemento = '345' then valor else 0 end) as valor2 ,
       sum(case when elemento = '346' then valor else 0 end) as valor3 ,
       sum(case when elemento = '399' or elemento = '377' then valor else 0 end) as valor4
  from work inner join orcorgao o on work.orgao = o.o40_orgao and o40_anousu = ".db_getsession("DB_anousu")."
       inner join orcunidade  on o41_orgao = work.orgao and work.unidade = o41_unidade  and o40_anousu = o41_anousu
  where substr(elemento,1,1) = '3' and $xwhere
  group by ".($tipo_agrupa == 1?"orgao,o40_descr ":"orgao,o40_descr, unidade,o41_descr ")."  
  order by  ".($tipo_agrupa == 1?"orgao":"orgao, unidade");
//$sql = "select orgao,unidade,
//               sum(case when elemento = '344' then valor else 0 end) as valor1 ,
//               sum(case when elemento = '345' then valor else 0 end) as valor2 ,
//               sum(case when elemento = '346' then valor else 0 end) as valor3 ,
//               sum(case when elemento = '399' or elemento = '377' then valor else 0 end) as valor4 ,
//               o40_descr,o41_descr 
//        from work  
//	     inner join orcorgao o on work.orgao = o.o40_orgao
//	     inner join orcunidade u on u.o41_orgao = work.orgao and  work.unidade = u.o41_unidade
//	where substr(elemento,1,1) = '3' and $xwhere
//	group by orgao,unidade,o40_descr,o41_descr
//	order by orgao,unidade";
$result = pg_exec($sql);
//db_criatabela($result);exit;


$tvalor1=0;
$tvalor2=0;
$tvalor3=0;
$tvalor4=0;
$ttotal4=0;
$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if($valor==-1){
    continue;
  }

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2); 
    $pdf->cell(60,$alt,"UNIDADES","TLR",0,"L",0);
    $pdf->cell(105,$alt,"DESPESAS DE CAPITAL",1,0,"C",0);
    $pdf->cell(25,$alt,"TOTAL","TLR",1,"R",0);
    
    $pdf->cell(60,$alt,"ORÇAMENTÁRIAS","LBR",0,"L",0);
    $pdf->cell(35,$alt,"INVESTIMENTOS",1,0,"R",0);
    $pdf->cell(35,$alt,"INVERSÕES",1,0,"R",0);
    $pdf->cell(35,$alt,"AMOTIZ DA DÍVIDA",1,0,"R",0);
    $pdf->cell(25,$alt,"","BLR",1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
   
  }
  
  $pdf->setfont('arial','',6);
  if ($tipo_agrupa==1) {
     $pdf->cell(60,$alt,db_formatar(substr($orgao,0,40),'orgao')."-".substr($o40_descr,0,40),0,0,"L",0);
  } else {
    $pdf->cell(60,$alt,db_formatar(substr($orgao,0,40),'orgao').db_formatar($unidade,'orgao')."-".substr($o41_descr,0,40),0,0,"L",0);
  }
  $pdf->cell(35,$alt,db_formatar($valor1,'f'),0,0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor2,'f'),0,0,"R",0);
  $pdf->cell(35,$alt,db_formatar($valor3,'f'),0,0,"R",0);
  $pdf->cell(25,$alt,db_formatar($valor1+$valor2+$valor3+$valor4,'f'),0,1,"R",0);
 
  $tvalor1+=$valor1;
  $tvalor2+=$valor2;
  $tvalor3+=$valor3;
  $ttotal4+=$valor1+$valor2+$valor3+$valor4;
  
  
}

$pdf->cell(0,0.1,'',"T",1,"C",0);
$pdf->cell(60,$alt,"Total",0,0,"L",0);
$pdf->cell(35,$alt,db_formatar($tvalor1,'f'),0,0,"R",0);
$pdf->cell(35,$alt,db_formatar($tvalor2,'f'),0,0,"R",0);
$pdf->cell(35,$alt,db_formatar($tvalor3,'f'),0,0,"R",0);
$pdf->cell(25,$alt,db_formatar($ttotal4,'f'),0,1,"R",0);

$sql = "select ".($tipo_agrupa == 1?"orgao,o40_descr,":"orgao,o40_descr, unidade,o41_descr, ")."
               sum(case when substr(elemento,2,1) = '3' then valor else 0 end) as valor1 ,
               sum(case when substr(elemento,2,1) = '4' or substr(elemento,2,1) = '9' or substr(elemento,2,1) = '7' then valor else 0 end) as valor2 
        from work  
	     inner join orcorgao o on work.orgao = o.o40_orgao and o.o40_anousu = ".db_getsession("DB_anousu")."
	     inner join orcunidade on o41_orgao = work.orgao and  work.unidade = o41_unidade and o41_anousu = ".db_getsession("DB_anousu")."
	where substr(elemento,1,1) = '3' and $xwhere
	group by ".($tipo_agrupa == 1?"orgao,o40_descr ":"orgao,o40_descr, unidade,o41_descr ")."  
  order by  ".($tipo_agrupa == 1?"orgao":"orgao, unidade");
	
$result = pg_exec($sql);
//db_criatabela($result);exit;

$tvalor1=0;
$tvalor2=0;
$ttotal4=0;
$pagina = 1;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  if($valor==-1){
    continue;
  }

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    $pdf->ln(2); 
    $pdf->cell(60,$alt,"UNIDADES","TLR",0,"L",0);
    $pdf->cell(90,$alt,"TOTALIZAÇÃO",1,0,"C",0);
    $pdf->cell(40,$alt,"TOTAL","TRL",1,"R",0);
    
    $pdf->cell(60,$alt,"ORÇAMENTÁRIAS","LBR",0,"L",0);
    $pdf->cell(45,$alt,"DESPESAS CORRENTES",1,0,"R",0);
    $pdf->cell(45,$alt,"DESPESAS DE CAPITAL",1,0,"R",0);
    $pdf->cell(40,$alt,"","BLR",1,"R",0);
  }
  $pdf->setfont('arial','',6);
  if ($tipo_agrupa==1)
     $pdf->cell(50,$alt,db_formatar(substr($orgao,0,40),'orgao')."-".substr($o40_descr,0,40),0,0,"L",0);
  else
    $pdf->cell(60,$alt,db_formatar(substr($orgao,0,40),'orgao').db_formatar($unidade,'orgao')."-".substr($o41_descr,0,40),0,0,"L",0);
  $pdf->cell(45,$alt,db_formatar($valor1,'f'),0,0,"R",0);
  $pdf->cell(45,$alt,db_formatar($valor2,'f'),0,0,"R",0);
  $pdf->cell(40,$alt,db_formatar($valor1+$valor2,'f'),0,1,"R",0);
 
  $tvalor1+=$valor1;
  $tvalor2+=$valor2;
  $ttotal4+=$valor1+$valor2;
  
  
}
$pdf->cell(0,0.1,'',"T",1,"C",0);
$pdf->cell(60,$alt,"Total Geral",0,0,"L",0);
$pdf->cell(45,$alt,db_formatar($tvalor1,'f'),0,0,"R",0);
$pdf->cell(45,$alt,db_formatar($tvalor2,'f'),0,0,"R",0);
$pdf->cell(40,$alt,db_formatar($ttotal4,'f'),0,1,"R",0);





$pdf->ln(14);

if($origem != "O"){

   assinaturas($pdf, $classinatura,'BG');
}


 
//include(modification("fpdf151/geraarquivo.php"));

$pdf->Output();

pg_exec("commit");

?>
