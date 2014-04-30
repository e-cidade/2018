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


$head3 = "RELATÓRIO ANUAL VALE TRANSPORTE";
$head4 = "PERÍODO : ".$anoi." A ".$anof;

$sql_cfpess = "select * from cfpess where r11_anousu = fc_anofolha(".db_getsession("DB_instit").") and r11_mesusu = fc_mesfolha(".db_getsession("DB_instit").") and r11_instit = ".db_getsession("DB_instit");
$res_cfpess = pg_query($sql_cfpess);
db_fieldsmemory($res_cfpess,0);

if($r11_vtprop == "t"){
    $q_vale = " quantvale_afas(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,'$r11_vtfer',ndias(rh02_anousu, rh02_mesfolha),rh02_instit) ";
}else{
    $q_vale = " quantvale(r17_codigo,r17_regist, rh02_anousu, rh02_mesusu, 0, 'f', rh02_instit) ";
}




$xwhere = '';
$vales_selecionados = "";
if(trim($selecionados) != ''){
  $arr_vales = split(",",$selecionados);
  $sVirgula = ''; 
  for($i=0; $i<count($arr_vales); $i++){
    $vales_selecionados .= $sVirgula."'".$arr_vales[$i]."' ";
     $sVirgula = ',';
  }  

  $xwhere = " and r17_codigo in (".$vales_selecionados.") ";
}



$sql = " select rh02_regist, 
                z01_nome, 
                rh02_anousu, 
                r17_codigo,
                r16_descr,
                max(jan) as jan,
                max(fev) as fev,
                max(mar) as mar,
                max(abr) as abr,
                max(mai) as mai,
                max(jun) as jun,
                max(jul) as jul,
                max(ago) as ago,
                max(set) as set,
                max(out) as out,
                max(nov) as nov,
                max(dez) as dez
        from (
        select rh02_regist ,
         rh02_anousu,
         rh02_mesusu,
         r17_codigo,
         r16_descr,
	       z01_nome,
				 case when rh02_mesusu = 1  then $q_vale else 0 end as jan,
				 case when rh02_mesusu = 2  then $q_vale else 0 end as fev,
				 case when rh02_mesusu = 3  then $q_vale else 0 end as mar,
				 case when rh02_mesusu = 4  then $q_vale else 0 end as abr,
				 case when rh02_mesusu = 5  then $q_vale else 0 end as mai,
				 case when rh02_mesusu = 6  then $q_vale else 0 end as jun,
				 case when rh02_mesusu = 7  then $q_vale else 0 end as jul,
				 case when rh02_mesusu = 8  then $q_vale else 0 end as ago,
				 case when rh02_mesusu = 9  then $q_vale else 0 end as set,
				 case when rh02_mesusu = 10 then $q_vale else 0 end as out,
				 case when rh02_mesusu = 11 then $q_vale else 0 end as nov,
				 case when rh02_mesusu = 12 then $q_vale else 0 end as dez
	from vtffunc 
		inner join rhpessoalmov 
	     		  on rh02_regist = r17_regist
		       and rh02_anousu = r17_anousu
		       and rh02_mesusu = r17_mesusu
					 and rh02_instit = ".db_getsession("DB_instit")."
		inner join rhpessoal on rh01_regist = rh02_regist 
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
	where 	r17_anousu between {$anoi} and {$anof}
	    $xwhere ) as x
  group by rh02_regist, z01_nome, rh02_anousu, r17_codigo, r16_descr
  having (max(jan)+max(fev)+max(mar)+max(abr)+max(mai)+max(jun)+max(jul)+max(ago)+max(set)+max(out)+max(nov)+max(dez)) > 0     
  order by z01_nome, r17_codigo, rh02_anousu ";

$result = db_query($sql);
// echo $sql."<br>" ; db_criatabela($result); exit;
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
$troca     = 1;
$alt       = 4;
$matric    = '';
$tot_vale  = 0;
$lota      = 0;
$quant     = 0;
$matricula = 0;
$t_jan     = 0;
$t_fev     = 0;
$t_mar     = 0;
$t_abr     = 0;
$t_mai     = 0;
$t_jun     = 0;
$t_jul     = 0;
$t_ago     = 0;
$t_set     = 0;
$t_out     = 0;
$t_nov     = 0;
$t_dez     = 0;
$t_geral   = 0;
$arr_t     = array();
$pre       = 1;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   $total_ = $jan+$fev+$mar+$abr+$mai+$jun+$jul+$ago+$set+$out+$nov+$dez;
   $t_jan  += $jan;     
   $t_fev  += $fev;
   $t_mar  += $mar;
   $t_abr  += $abr;
   $t_mai  += $mai;
   $t_jun  += $jun;
   $t_jul  += $jul;
   $t_ago  += $ago;
   $t_set  += $set;
   $t_out  += $out;
   $t_nov  += $nov;
   $t_dez  += $dez;
   $t_geral+= $total_;
   if($imprime_serv == 't'){
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $matricula = 0;
        $pdf->addpage('L');
        $pdf->setfont('arial','b',8);
        $pdf->cell(13,4,'MATR.'    ,1,0,"C",1);
        $pdf->cell(60,4,'NOME'     ,1,0,"C",1);
        $pdf->cell(12,4,'ANO'      ,1,0,"C",1);
        $pdf->cell(12,4,'VALE'     ,1,0,"C",1);
        $pdf->cell(12,4,'JAN'      ,1,0,"C",1);
        $pdf->cell(12,4,'FEV'      ,1,0,"C",1);
        $pdf->cell(12,4,'MAR'      ,1,0,"C",1);
        $pdf->cell(12,4,'ABR'      ,1,0,"C",1);
        $pdf->cell(12,4,'MAI'      ,1,0,"C",1);
        $pdf->cell(12,4,'JUN'      ,1,0,"C",1);
        $pdf->cell(12,4,'JUL'      ,1,0,"C",1);
        $pdf->cell(12,4,'AGO'      ,1,0,"C",1);
        $pdf->cell(12,4,'SET'      ,1,0,"C",1);
        $pdf->cell(12,4,'OUT'      ,1,0,"C",1);
        $pdf->cell(12,4,'NOV'      ,1,0,"C",1);
        $pdf->cell(12,4,'DEZ'      ,1,0,"C",1);
        $pdf->cell(15,4,'TOTAL'    ,1,1,"C",1);
        $pre = 1;
     }
     
     
     $pdf->setfont('arial','b',7);
     if($matricula == $rh02_regist){
       $pdf->cell(13,$alt,'' ,0,0,"C",$pre);
       $pdf->cell(60,$alt,'' ,0,0,"L",$pre);
     }else{
       if($pre == 1){
         $pre = 0;
       }else{
         $pre = 1;
       }
       $pdf->cell(13,$alt,$rh02_regist,0,0,"C",$pre);
       $pdf->cell(60,$alt,$z01_nome   ,0,0,"L",$pre);
     }
     if($total_ > 0){
       $pdf->setfont('arial','',7);
       $pdf->cell(12,$alt,$rh02_anousu,0,0,"C",$pre);
       $pdf->cell(12,$alt,$r17_codigo ,0,0,"C",$pre);
       $pdf->cell(12,$alt,$jan        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$fev        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$mar        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$abr        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$mai        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$jun        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$jul        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$ago        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$set        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$out        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$nov        ,0,0,"R",$pre);
       $pdf->cell(12,$alt,$dez        ,0,0,"R",$pre);
       $pdf->cell(15,$alt,$total_     ,0,1,"R",$pre);
     }
     $matricula = $rh02_regist;
   }
   if(array_key_exists($r17_codigo.'-'.$r16_descr, $arr_t)){ 
      $arr_t[$r17_codigo.'-'.$r16_descr] [1] += $jan+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [2] += $fev+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [3] += $mar+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [4] += $abr+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [5] += $mai+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [6] += $jun+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [7] += $jul+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [8] += $ago+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [9] += $set+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [10]+= $out+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [11]+= $nov+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [12]+= $dez+0;         
   }else{ 
      $arr_t[$r17_codigo.'-'.$r16_descr] [1]  = $jan+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [2]  = $fev+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [3]  = $mar+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [4]  = $abr+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [5]  = $mai+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [6]  = $jun+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [7]  = $jul+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [8]  = $ago+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [9]  = $set+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [10] = $out+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [11] = $nov+0;         
      $arr_t[$r17_codigo.'-'.$r16_descr] [12] = $dez+0;         
   } 
   $troca = 0;

}


if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
   $matricula = 0;
   $pdf->addpage('L');
   $pre = 1;
}

if($pre == 1){
  $pre = 0;
}else{
  $pre = 1;
}

if($imprime_serv == 't'){
  $pdf->setfont('arial','B',7);
  $pdf->cell(97,$alt,'TOTAIS'    ,1,0,"C",$pre);
  $pdf->cell(12,$alt,$t_jan      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_fev      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_mar      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_abr      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_mai      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_jun      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_jul      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_ago      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_set      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_out      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_nov      ,1,0,"R",$pre);
  $pdf->cell(12,$alt,$t_dez      ,1,0,"R",$pre);
  $pdf->cell(15,$alt,$t_geral    ,1,1,"R",$pre);
  $pdf->ln(5);
}else{
 $pdf->addpage('L');
}

$pdf->setfont('arial','b',9);

$pdf->cell(97,4,'TOTAL DOS VALES'     ,1,0,"C",1);
$pdf->cell(12,4,'JAN'      ,1,0,"C",1);
$pdf->cell(12,4,'FEV'      ,1,0,"C",1);
$pdf->cell(12,4,'MAR'      ,1,0,"C",1);
$pdf->cell(12,4,'ABR'      ,1,0,"C",1);
$pdf->cell(12,4,'MAI'      ,1,0,"C",1);
$pdf->cell(12,4,'JUN'      ,1,0,"C",1);
$pdf->cell(12,4,'JUL'      ,1,0,"C",1);
$pdf->cell(12,4,'AGO'      ,1,0,"C",1);
$pdf->cell(12,4,'SET'      ,1,0,"C",1);
$pdf->cell(12,4,'OUT'      ,1,0,"C",1);
$pdf->cell(12,4,'NOV'      ,1,0,"C",1);
$pdf->cell(12,4,'DEZ'      ,1,0,"C",1);
$pdf->cell(15,4,'TOTAIS'   ,1,1,"C",1);

ksort($arr_t);
$aTotais = $arr_t;
foreach ($aTotais as $sDescricaoVale => $aValoresMensais) {
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     $matricula = 0;
     $pdf->addpage('L');
     $pre = 1;
     $troca = 0;

    $pdf->cell(97,4,'TOTAL DOS VALES'     ,1,0,"C",1);
    $pdf->cell(12,4,'JAN'      ,1,0,"C",1);
    $pdf->cell(12,4,'FEV'      ,1,0,"C",1);
    $pdf->cell(12,4,'MAR'      ,1,0,"C",1);
    $pdf->cell(12,4,'ABR'      ,1,0,"C",1);
    $pdf->cell(12,4,'MAI'      ,1,0,"C",1);
    $pdf->cell(12,4,'JUN'      ,1,0,"C",1);
    $pdf->cell(12,4,'JUL'      ,1,0,"C",1);
    $pdf->cell(12,4,'AGO'      ,1,0,"C",1);
    $pdf->cell(12,4,'SET'      ,1,0,"C",1);
    $pdf->cell(12,4,'OUT'      ,1,0,"C",1);
    $pdf->cell(12,4,'NOV'      ,1,0,"C",1);
    $pdf->cell(12,4,'DEZ'      ,1,0,"C",1);
    $pdf->cell(15,4,'TOTAIS'   ,1,1,"C",1);
  }
  if($pre == 1){
    $pre = 0;
  }else{
    $pre = 1;
  }

   $pdf->cell(97,4,$sDescricaoVale,0,0,"L",$pre);
   $total_nValor = 0;
   foreach ($aValoresMensais as $nValor) {
     $pdf->cell(12,4,$nValor.'',0,0,"R",$pre);
     $total_nValor += $nValor;
   } 
   $pdf->cell(15,4,$total_nValor+0,0,0,"R",$pre);
   $pdf->ln();
//  $pdf->cell(73,4,'',0,0,"L",0);
}



$pdf->Output();
   
?>