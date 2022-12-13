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

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('rh01_regist');
$clrotulo->label('r16_descr');
$clrotulo->label('r17_codigo');
$clrotulo->label('r17_quant');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$head3 = "CADASTRO VALE TRANSPORTE";
$head4 = "PERÍODO : ".$mesfolha." / ".$anofolha;

$sql_cfpess = "select * from cfpess where r11_anousu = $anofolha and r11_mesusu = $mesfolha and r11_instit = ".db_getsession("DB_instit");
$res_cfpess = pg_query($sql_cfpess);
db_fieldsmemory($res_cfpess,0);
if($r11_vtprop == "t"){
  $q_vale = " quantvale_afas(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,'$r11_vtfer',ndias($anofolha,$mesfolha),rh02_instit) as quantvale";
}else{
	$q_vale = " quantvale(r17_codigo,r17_regist,$anofolha,$mesfolha,0,'f',rh02_instit) as quantvale ";
}

if($tipo == 'l'){
  $head6  = "Lotação";
  if($ordem == 'a'){
    $xordem = " order by r70_estrut,z01_nome ";
    $head5  = "Ordem Alfabética";
  }else{
    $xordem = " order by r70_estrut,rh01_regist ";
    $head5  = "Ordem Numérica";
  }
}elseif($tipo == 't'){
  $head6  = "Local de Trabalho";
  if($ordem == 'a'){
    $xordem = " order by rh55_estrut ,z01_nome ";
    $head5  = "Ordem Alfabética";
  }else{
    $xordem = " order by rh55_estrut ,rh01_regist ";
    $head5  = "Ordem Numérica";
  }
}else{
  $head6  = "Geral";
  if($ordem == 'a'){
    $xordem = " order by z01_nome ";
    $head5  = "Ordem Alfabética";
  }else{
    $xordem = " order by rh02_regist ";
    $head5  = "Ordem Numérica";
  }
}
$xwhere = '';

if($vale == 'a'){
  $xwhere = " and r17_situac = 'A'";
}elseif($vale == 'i'){
  $xwhere = " and r17_situac = 'I'";
}
if(isset($lotaci) && trim($lotaci) != "" && isset($lotacf) && trim($lotacf) != ""){
  // Se for por intervalos e vier lotação inicial e final
  $xwhere .= " and r70_estrut between '".$lotaci."' and '".$lotacf."' ";
}else if(isset($lotaci) && trim($lotaci) != ""){
  // Se for por intervalos e vier somente lotação inicial
  $xwhere .= " and r70_estrut >= '".$lotaci."' ";
}else if(isset($lotacf) && trim($lotacf) != ""){
  // Se for por intervalos e vier somente lotação final
  $xwhere .= " and r70_estrut <= '".$lotacf."' ";
}else if(isset($sellotac) && $sellotac != ''){
	
	 $aSellotac = explode(',', $sellotac);
	 $sVirgula  = '';
	 $sLotacoes = '';
	 foreach ($aSellotac as $sLotac) {
	 	
	 	$sLotacoes .= "$sVirgula'$sLotac'";
	 	$sVirgula = ', ';
	 }
   $xwhere .= " and r70_estrut in ($sLotacoes) ";
}


if(isset($local1) && trim($local1) != "" && isset($local2) && trim($local2) != ""){
  // Se for por intervalos e vier lotação inicial e final
  $xwhere .= " and rh55_estrut between '".$local1."' and '".$local2."' ";
}else if(isset($local1) && trim($local1) != ""){
  // Se for por intervalos e vier somente lotação inicial
  $xwhere .= " and rh55_estrut >= '".$local1."' ";
}else if(isset($local2) && trim($local2) != ""){
  // Se for por intervalos e vier somente lotação final
  $xwhere .= " and rh55_estrut <= '".$local2."' ";
}else if(isset($sellocal) && $sellocal != ''){
   $sellocal = "'".str_replace(",","','",$sellocal)."'";
   $xwhere .= " and rh55_estrut in ($sellocal) ";
   //echo "<br><br>".$xwhere;exit;
}

$sql = "
        select rh02_regist as rh01_regist,
	       z01_nome,
         z01_ender,
         z01_numero,
         z01_compl,
         z01_bairro,
         z01_munic,
         z01_uf,
				 $q_vale,
	       fc_dias_vale(r17_regist,$anofolha,$mesfolha,rh02_instit),
	       r70_descr,
	       r70_estrut,
	       r16_codigo,
	       r16_descr,
	       r17_situac,
	       rh55_estrut,
	       rh55_descr
	from vtffunc 
		inner join rhpessoalmov 
	     		  on rh02_regist = r17_regist
		       and rh02_anousu = r17_anousu
		       and rh02_mesusu = r17_mesusu
					 and rh02_instit = ".db_getsession("DB_instit")."
		left join  rhpeslocaltrab 
		        on rh56_seqpes = rh02_seqpes
		       and rh56_princ  = 't'
    left join  rhlocaltrab
		        on rh55_codigo = rh56_localtrab
					 and rh55_instit = rh02_instit
		inner join rhpessoal on rh01_regist = rh02_regist 
    left  join rhpesrescisao on  rh05_seqpes = rh02_seqpes
    inner join rhlota 
		        on r70_codigo = rh02_lota
					 and r70_instit = rh02_instit	
		inner join cgm 
			      on rh01_numcgm = z01_numcgm 
		inner join vtfempr 
			      on r16_codigo = r17_codigo 
		       and r16_anousu = r17_anousu 
		       and r16_mesusu = r17_mesusu 
					 and r16_instit = rh02_instit
	where 	r17_anousu = $anofolha
      and r17_mesusu = $mesfolha 
	    and rh05_recis is null
	    $xwhere
    	$xordem
       ";

//
// tirei do where por causa do DAEB verificar se realmente eh necessario			 
//	    and fc_dias_vale(r17_regist,$anofolha,$mesfolha) > 0 


//echo $sql ; exit;

$result = db_query($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mesfolha.' / '.$anofolha);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 8;
$matric = '';
$tot_vale = 0;
$lota = 0;
$quant = 0;
$arr_v = array();
$arr_t = array();

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($lota <> $r70_estrut && $tipo == 'l'){
     $pdf->setfont('arial','b',8);
     $pdf->cell(100,4,'TOTAL DA LOTACAO : '.$quant,1,1,"L",1);
     $troca = 1;
     $lota = $r70_estrut;
     $quant = 0;
     ksort($arr_v);
     foreach ($arr_v as $key => $value) {
        $pdf->cell(73,4,'',0,0,"L",0);
        $pdf->cell(60,4,$key,0,0,"L",0);
        $pdf->cell(10,4,$value,0,1,"R",0);
     }
     $arr_v = array();
   }  
   if($lota <> $rh55_estrut && $tipo == 't'){
     $pdf->setfont('arial','b',8);
     $pdf->cell(100,4,'TOTAL DA LOCAL : '.$quant,1,1,"L",1);
     $troca = 1;
     $lota = $rh55_estrut;
     $quant = 0;
     ksort($arr_v);
     foreach ($arr_v as $key => $value) {
        $pdf->cell(73,4,'',0,0,"L",0);
        $pdf->cell(60,4,$key,0,0,"L",0);
        $pdf->cell(10,4,$value,0,1,"R",0);
     }
     $arr_v = array();
   }  
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(13,4,'Matrícula',1,0,"C",1);
      $pdf->cell(60,4,$RLz01_nome  ,1,0,"C",1);
      $pdf->cell(60,4,$RLr16_descr ,1,0,"C",1);
      $pdf->cell(10,4,'Quant'      ,1,0,"C",1);
      $pdf->cell(10,4,'Sit.'      ,1,0,"C",1);
      $pdf->cell(50,4,'Assinatura' ,1,1,"C",1);
      if( $tipo == 'l'){
        $pdf->cell(100,4,$r70_estrut.' - '.$r70_descr,0,0,"L",0);
        $pdf->ln(4);
      }
      if( $tipo == 't'){
        $pdf->cell(100,4,$rh55_estrut.' - '.$rh55_descr,0,0,"L",0);
        $pdf->ln(4);
      }
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   if($matric != $rh01_regist){
     $pdf->cell(13,$alt,$rh01_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome  ,0,0,"L",$pre);
     $matric = $rh01_regist;
     $total ++;
   }else{
     $pdf->cell(13,$alt,'',0,0,"C",$pre);
     $pdf->cell(60,$alt,'',0,0,"L",$pre);
   }
   $pdf->cell(60,$alt,$r16_codigo.'-'.$r16_descr ,0,0,"L",$pre);
   if(array_key_exists($r16_codigo.'-'.$r16_descr, $arr_v)){
        $arr_v[$r16_codigo.'-'.$r16_descr] += $quantvale; // //total;       
   }else{
        $arr_v[$r16_codigo.'-'.$r16_descr]  = $quantvale; // //total;       
   }
   if(array_key_exists($r16_codigo.'-'.$r16_descr, $arr_t)){
        $arr_t[$r16_codigo.'-'.$r16_descr] += $quantvale; // //total;       
   }else{
        $arr_t[$r16_codigo.'-'.$r16_descr]  = $quantvale; // //total;       
   }
   $pdf->cell(10,$alt,$quantvale ,0,0,"R",$pre);
   $pdf->cell(10,$alt,$r17_situac ,0,0,"C",$pre);
   $pdf->cell(50,$alt,str_repeat('.',50),0,1,"L",$pre);
   if($endereco == 's'){
     $pdf->cell(60,2,'End.: '.$z01_ender,0,0,"L",$pre);
     $pdf->cell(20,2,'Num.: '.$z01_numero,0,0,"L",$pre);
     $pdf->cell(30,2,'Compl.: '.$z01_compl,0,0,"L",$pre);
     $pdf->cell(40,2,'Bairro: '.$z01_bairro,0,0,"L",$pre);
     $pdf->cell(30,2,'Munic.: '.$z01_munic.'('.$z01_uf.')',0,1,"L",$pre);
   }
   $tot_vale += $quantvale;
   $quant    += $quantvale;
}
$pdf->setfont('arial','b',8);
if( $tipo == 'l'){
  $pdf->cell(100,4,'TOTAL DA LOTACAO : '.$quant,1,1,"L",1);
     ksort($arr_v);
     foreach ($arr_v as $key => $value) {
        $pdf->cell(73,4,'',0,0,"L",0);
        $pdf->cell(60,4,$key,0,0,"L",0);
        $pdf->cell(10,4,$value,0,1,"R",0);
     }
     $arr_v = array();
}elseif( $tipo == 't'){
  $pdf->cell(100,4,'TOTAL DA LOCAL : '.$quant,1,1,"L",1);
     ksort($arr_v);
     foreach ($arr_v as $key => $value) {
        $pdf->cell(73,4,'',0,0,"L",0);
        $pdf->cell(60,4,$key,0,0,"L",0);
        $pdf->cell(10,4,$value,0,1,"R",0);
     }
     $arr_v = array();
}
$pdf->cell(133,$alt,'TOTAL :    '.$total.'   Funcionários',"T",0,"L",0);
$pdf->cell(10,$alt,$tot_vale,"T",0,"R",0);
$pdf->cell(50,$alt,'Vales',"T",1,"L",0);
     ksort($arr_t);
     foreach ($arr_t as $key => $value) {
        $pdf->cell(73,4,'',0,0,"L",0);
        $pdf->cell(60,4,$key,0,0,"L",0);
        $pdf->cell(10,4,$value,0,1,"R",0);
     }

$pdf->Output();
   
?>