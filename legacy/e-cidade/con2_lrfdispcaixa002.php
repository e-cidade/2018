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
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");

$classinatura = new cl_assinatura;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$orcparamrel = new cl_orcparamrel;
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
//******************************************************************
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
$anousu = db_getsession("DB_anousu");

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head2 = "INSTITUIÇÕES : ".$descr_inst;
$head3 = "RELATÓRIO DE GESTÃO FISCAL";
$head4 = "DEMONSTRATIVO DE DISPONIBILIDADE DE CAIXA";
$head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$period = '';
if ($bimestre == 1){
  $period = '1º QUADRIMESTRE';
}elseif($bimestre == 2){  
  $period = '2º QUADRIMESTRE'; 
}elseif($bimestre == 3){  
  $period = '3º QUADRIMESTRE'; 
}  
$head6 = "PERIODO : $period DE ".$anousu;
//******************************************************************

if ($bimestre == 1) {
  $dataini = $anousu.'-01-01';
  $datafin = $anousu.'-04-30'; 
}elseif ($bimestre == 2){
  $dataini = $anousu.'-01-01';
  $datafin = $anousu.'-08-31';
}elseif ($bimestre == 3){
  $dataini = $anousu.'-01-01';
  $datafin = $anousu.'-12-31';
}

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";
$result1 = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
//db_criatabela($result1);

pg_exec("Drop Table work_pl");

// RPPS ///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
// seleciona instituição do RPPS
$sql        = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
$resultinst = pg_exec($sql);
$instit     = '';
$xvirg      = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $instit .= $xvirg.$codigo; // salva insituição
    $xvirg   = ', ';		  
}
$where       = " c61_instit in (".$instit.") "; 
$result_rpps = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where);
///////////////////////////////////////////////////////////////////////////////////////////////////////
//db_criatabela($result_rpps);exit;
$textoe[0] ='DISPONIBILIDADE FINANCEIRA';
$textoe[1] ='  Caixa';
$textoe[2] ='  Bancos';
$textoe[3] ='    Conta Movimento';
$textoe[4] ='    Conta Vinculada';
$textoe[5] ='  Aplicações Financeiras';
$textoe[6] ='  Outras Disponibilidades Financeiras';
$textoe[7] ='';
$textoe[8] ='';
$textoe[9] ='';
$textoe[10] ='';
$textoe[11] ='';
$textoe[12] ='DISPONIBILIDADE FINANCEIRA';
$textoe[13] ='  Caixa';
$textoe[14] ='  Bancos';
$textoe[15] ='    Conta Movimento';
$textoe[16] ='    Conta Vinculada';
$textoe[17] ='  Aplicações Financeiras';
$textoe[18] ='  Outras Disponibilidades Financeiras';
$textoe[19] ='';

$textod[0] ='OBRIGAÇÕES FINANCEIRAS';
$textod[1] ='  Depósitos';
$textod[2] ='  Restos a Pagar Processados';
$textod[3] ='    Do Exercício';
$textod[4] ='    De Exercício Anteriores';
$textod[5] ='  Outras Obrigações Financeiras';
$textod[6] ='';
$textod[7] ='';
$textod[7] ='';
$textod[8] ='';
$textod[9] ='';
$textod[10] ='';
$textod[11] ='';
$textod[12] ='OBRIGAÇÕES FINANCEIRAS';
$textod[13] ='  Depósitos';
$textod[14] ='  Restos a Pagar Processados';
$textod[15] ='    Do Exercício';
$textod[16] ='    De Exercício Anteriores';
$textod[17] ='  Outras Obrigações Financeiras';
$textod[18] ='';
$textod[19] ='';

$parametroe[0] = $orcparamrel->sql_parametro('13','0','f',str_replace('-',', ',$db_selinstit));
$parametroe[1] = $orcparamrel->sql_parametro('13','1','f',str_replace('-',', ',$db_selinstit));
$parametroe[2] = $orcparamrel->sql_parametro('13','2','f',str_replace('-',', ',$db_selinstit));
$parametroe[3] = $orcparamrel->sql_parametro('13','3','f',str_replace('-',', ',$db_selinstit));
$parametroe[4] = $orcparamrel->sql_parametro('13','4','f',str_replace('-',', ',$db_selinstit));
$parametroe[5] = $orcparamrel->sql_parametro('13','5','f',str_replace('-',', ',$db_selinstit));
$parametroe[6] = $orcparamrel->sql_parametro('13','6','f',str_replace('-',', ',$db_selinstit));
$parametroe[7] = $orcparamrel->sql_parametro('13','7','f',str_replace('-',', ',$db_selinstit));
$parametroe[8] = $orcparamrel->sql_parametro('13','8','f',str_replace('-',', ',$db_selinstit));
$parametroe[9] = $orcparamrel->sql_parametro('13','9','f',str_replace('-',', ',$db_selinstit));
$parametroe[10] = $orcparamrel->sql_parametro('13','10','f',str_replace('-',', ',$db_selinstit));
$parametroe[11] = $orcparamrel->sql_parametro('13','11','f',str_replace('-',', ',$db_selinstit));
$parametroe[12] = $orcparamrel->sql_parametro('13','12','f',str_replace('-',', ',$db_selinstit));
$parametroe[13] = $orcparamrel->sql_parametro('13','13','f',str_replace('-',', ',$db_selinstit));

$parametrod[0] = $orcparamrel->sql_parametro('13','14','f',str_replace('-',', ',$db_selinstit));
$parametrod[1] = $orcparamrel->sql_parametro('13','15','f',str_replace('-',', ',$db_selinstit));
$parametrod[2] = $orcparamrel->sql_parametro('13','16','f',str_replace('-',', ',$db_selinstit));
$parametrod[3] = $orcparamrel->sql_parametro('13','17','f',str_replace('-',', ',$db_selinstit));
$parametrod[4] = $orcparamrel->sql_parametro('13','18','f',str_replace('-',', ',$db_selinstit));
$parametrod[5] = $orcparamrel->sql_parametro('13','19','f',str_replace('-',', ',$db_selinstit));
$parametrod[6] = $orcparamrel->sql_parametro('13','20','f',str_replace('-',', ',$db_selinstit));
$parametrod[7] = $orcparamrel->sql_parametro('13','21','f',str_replace('-',', ',$db_selinstit));
$parametrod[8] = $orcparamrel->sql_parametro('13','22','f',str_replace('-',', ',$db_selinstit));
$parametrod[9] = $orcparamrel->sql_parametro('13','23','f',str_replace('-',', ',$db_selinstit));

//$texto4 = array();
//$valor4 = array();
//$texto11 = array();
//$valor11 = array();
//$texto17 = array();
//$valor17 = array();
//$texto22 = array();
//$valor22 = array
//var_dump($parametroe); exit;
//db_criatabela($result1);exit;
for ($x=0;$x < 23;$x++){
 $valore[$x] = 0;
}
for ($x=0;$x < 23;$x++){
 $valord[$x] = 0;
}

$conte = 1;
$contd = 1;
for ($x=0;$x < 14;$x++){
 if ($conte == 2) {
   $conte = 3;
 }
 if ($conte == 7) {
    $conte = 8;
 }
 if ($conte == 9) {
    $conte = 10;
 }
  if ($conte == 11) {
    $conte = 13;
 }
 if ($conte == 14) {
    $conte = 15;
 }
 if ($conte == 20) {
    $conte = 21;
 }

 for($i=0;$i< pg_numrows($result1);$i++) {
  db_fieldsmemory($result1,$i);
  if(in_array($estrutural,$parametroe["$x"])) {
    $valore["$conte"] += $saldo_anterior;
   }
  }
$conte++;
}


for ($x=0;$x < 10;$x++){
 if ($contd == 2) {
   $contd = 3;
 }
  if ($contd == 6) {
   $contd = 7;
 }
 if ($contd == 8) {
   $contd = 13;
 }
 if ($contd ==14) {
   $contd = 15;
 }

 for($i=0;$i< pg_numrows($result1);$i++) {
  db_fieldsmemory($result1,$i);
  if (in_array($estrutural,$parametrod["$x"])){
    $valord["$contd"] += $saldo_anterior;
   }
  }
$contd++;
}

$valore["2"] = $valore["3"] + $valore["4"];
$valord["2"] = $valord["3"] + $valord["4"];

$valore["0"] = $valore["1"] + $valore["2"] + $valore["5"] + $valore["6"];
$valord["0"] = $valord["1"] + $valord["2"] + $valord["5"];

$valore["7"] = $valore["0"];
$valord["6"] = $valord["0"];

if ($valore["7"] > $valord["6"]) {
  $dif = $valore["7"] - $valord["6"];
  $valore["8"] = 0;
  $valord["7"] = $dif;
}else{ 
  $dif = $valord["6"] - $valore["7"];
  $valore["8"] = $dif;
  $valord["7"] = 0;
}  
$valore["9"] = $valore["7"] + $valore["8"];
$valord["8"] = $valord["6"] + $valord["7"];

if ($valore["7"] > $valord["6"]) {
  $valore["8"] = " - ";
}else{ 
  $valord["7"] = " - ";
}  

$valore["11"] = $valord["7"] - $valore["10"];

$valore["14"] = $valore["15"] + $valore["16"];
$valord["14"] = $valord["15"] + $valord["16"];

$valore["12"] = $valore["13"] + $valore["14"] + $valore["17"] + $valore["18"];
$valord["12"] = $valord["13"] + $valord["14"] + $valord["17"];

if ($valore["12"] > $valord["12"]) {
  $dif = $valore["12"] - $valord["12"];
  $valore["19"] = 0;
  $valord["18"] = $dif;
}else{ 
  $dif = $valord["12"] - $valore["12"];
  $valore["19"] = $dif;
  $valord["18"] = 0;
}  

$valore["20"] = $valore["19"] + $valore["12"];
$valord["19"] = $valord["18"] + $valord["12"];



//$valore["20"] = $valore["19"] + $valore["12"];
//$valord["19"] = $valord["18"] + $valord["12"];

$valore["22"] = $valord["18"] - $valore["21"];
//print_r($valord);exit;
$texto4[0] = '';
$valor4[0] = '';
$texto11[0] = '';
$valor11[0] = '';
$texto17[0] = '';
$valor17[0] = '';
$texto22[0] = '';
$valor22[0] = '';

$cont = 0;
for($i=0;$i< pg_numrows($result1);$i++) {
 db_fieldsmemory($result1,$i);
 if (in_array($estrutural,$parametroe[4])){
  $texto4[$cont]= $c60_descr; 
  $valor4[$cont]= $saldo_anterior;
  $cont++;
 }
}

$cont = 0;
for($i=0;$i< pg_numrows($result1);$i++) {
 db_fieldsmemory($result1,$i);
 if (in_array($estrutural,$parametroe[11])){
  $texto11[$cont]= $c60_descr; 
  $valor11[$cont]= $saldo_anterior;
  $cont++;
 }
}

$cont = 0;
for($i=0;$i< pg_numrows($result1);$i++) {
 db_fieldsmemory($result1,$i);
 if (in_array($estrutural,$parametrod[3])){
  $texto17[$cont]= $c60_descr; 
  $valor17[$cont]= $saldo_anterior;
  $cont++;
 }
}

$cont = 0;
for($i=0;$i< pg_numrows($result1);$i++) {
 db_fieldsmemory($result1,$i);
 if (in_array($estrutural,$parametrod[8])){
  $texto22[$cont]= $c60_descr; 
  $valor22[$cont]= $saldo_anterior;
  $cont++;
 }
}
if (count($texto4) > count($texto17)) {
  $for1 = count($texto4);
}else{
  $for1 = count($texto17);
}

if (count($texto11) > count($texto22)) {
  $for2 = count($texto11);
}else{
  $for2 = count($texto22);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pdf->addpage();
$alt            = 4;
$pagina         = 1;
$saldo_garantias = 0;
$saldo_contra    = 0;
$pdf->setfont('arial','b',7);
$pdf->cell(110,$alt,'LRF, Art. 55, Inciso III, Alínea "a" - Anexo V','B',0,"L",0);
$pdf->cell(75,$alt,'R$ Unidades','B',1,"R",0);

$pdf->cell(60,$alt,'ATIVO','RB',0,"C",0);
$pdf->cell(30,$alt,'VALOR','RB',0,"C",0);
$pdf->cell(63,$alt,'PASSIVO','RB',0,"C",0);
$pdf->cell(32,$alt,'VALOR','B',1,"C",0);
$pdf->setfont('arial','',7);
for($i=0;$i<7;$i++){
  $pdf->cell(60,$alt,$textoe[$i],'R',0,"L",0);
  if ($textoe[$i] == ''){;
    $pdf->cell(30,$alt,'','',0,"R",0);
  }else{
    $pdf->cell(30,$alt,db_formatar($valore[$i],'f'),'R',0,"R",0);
  }
  $pdf->cell(63,$alt,$textod[$i],'R',0,"L",0);
  if ($textod[$i] == ''){;
    $pdf->cell(32,$alt,'','',1,"R",0);
  }else{
    $pdf->cell(32,$alt,db_formatar($valord[$i],'f'),'',1,"R",0);
  }  
}
// Lista 
for($i=0;$i< $for1;$i++) {
 if (isset($texto4[$i])){
  $pdf->cell(60,$alt,'    '.substr($texto4[$i],0,35),'R',0,"L",0);
  $pdf->cell(30,$alt,db_formatar($valor4[$i],'f'),'R',0,"R",0);
 }else{
  $pdf->cell(60,$alt,'','R',0,"L",0);
  $pdf->cell(30,$alt,'','R',0,"R",0);
 }
 if (isset($texto17[$i])){
  $pdf->cell(63,$alt,'    '.substr($texto17[$i],0,37),'R',0,"L",0);
  $pdf->cell(32,$alt,db_formatar($valor17[$i],'f'),'',1,"R",0);
 }else{
  $pdf->cell(63,$alt,'','R',0,"L",0);
  $pdf->cell(32,$alt,'','',1,"R",0);
 }
}

$pdf->cell(60,$alt,'SUBTOTAL','TBR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valore[7],'f'),'TBR',0,"R",0);
$pdf->cell(63,$alt,'SUBTOTAL','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valord[6],'f'),'TB',1,"R",0);

$pdf->cell(60,$alt,'INSUFICIÊNCIA ANTES DA INSCRIÇÃO EM','TR',0,"L",0);
$pdf->cell(30,$alt,'','TR',0,"R",0);
$pdf->cell(63,$alt,'SUFICIÊNCIA ANTES DA INSCRIÇÃO EM','TR',0,"L",0);
$pdf->cell(32,$alt,'','T',1,"R",0);

$pdf->cell(60,$alt,'RESTOS A PAGAR NÃO PROCESSADOS(I)','BR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valore[8],'f'),'BR',0,"R",0);
$pdf->cell(63,$alt,'RESTOS A PAGAR NÃO PROCESSADOS(II)','BR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valord[7],'f'),'B',1,"R",0);
/*
$pdf->cell(60,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valore[9],'f'),'TBR',0,"R",0);
$pdf->cell(63,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valord[8],'f'),'TB',1,"R",0);

$pdf->cell(153,$alt,'INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS (III)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valore[10],'f'),'TB',1,"R",0);
$pdf->cell(153,$alt,'SUFICIÊNCIA APÓS A INSCRISÃO EM RESTOS APAGAR NÃO PROCESSADOS (IV) = (II - III)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valore[11],'f'),'TB',1,"R",0);


// RPPS ///////////////////////////////////////////////////////////////////////
for ($x=0;$x < 23;$x++){
 $valore[$x] = 0;
}
for ($x=0;$x < 23;$x++){
 $valord[$x] = 0;
}

$conte = 1;
$contd = 1;
for ($x=0;$x < 14;$x++){
 if ($conte == 2) {
   $conte = 3;
 }
 if ($conte == 7) {
    $conte = 8;
 }
 if ($conte == 9) {
    $conte = 10;
 }
  if ($conte == 11) {
    $conte = 13;
 }
 if ($conte == 14) {
    $conte = 15;
 }
 if ($conte == 20) {
    $conte = 21;
 }

 for($i=0;$i< pg_numrows($result_rpps);$i++) {
  db_fieldsmemory($result_rpps,$i);
  if(in_array($estrutural,$parametroe["$x"])) {
 //     if($c61_instit==$instit) {
	  $valore["$conte"] += $saldo_anterior;
 //     }
   }
  }
$conte++;
}

for ($x=0;$x < 10;$x++){
 if ($contd == 2) {
   $contd = 3;
 }
  if ($contd == 6) {
   $contd = 7;
 }
 if ($contd == 8) {
   $contd = 13;
 }
 if ($contd ==14) {
   $contd = 15;
 }

 for($i=0;$i< pg_numrows($result_rpps);$i++) {
  db_fieldsmemory($result_rpps,$i);
  if (in_array($estrutural,$parametrod["$x"])){
  //    if($c61_instit==$instit) {
          $valord["$contd"] += $saldo_anterior;
  //    }
   }
  }
$contd++;
}

$valore["2"] = $valore["3"] + $valore["4"];
$valord["2"] = $valord["3"] + $valord["4"];

$valore["0"] = $valore["1"] + $valore["2"] + $valore["5"] + $valore["6"];
$valord["0"] = $valord["1"] + $valord["2"] + $valord["5"];

$valore["7"] = $valore["0"];
$valord["6"] = $valord["0"];

if ($valore["7"] > $valord["6"]) {
  $dif = $valore["7"] - $valord["6"];
  $valore["8"] = 0;
  $valord["7"] = $dif;
}else{ 
  $dif = $valord["6"] - $valore["7"];
  $valore["8"] = $dif;
  $valord["7"] = 0;
}  
$valore["9"] = $valore["7"] + $valore["8"];
$valord["8"] = $valord["6"] + $valord["7"];

if ($valore["7"] > $valord["6"]) {
  $valore["8"] = " - ";
}else{ 
  $valord["7"] = " - ";
}  

$valore["11"] = $valord["7"] - $valore["10"];

$valore["14"] = $valore["15"] + $valore["16"];
$valord["14"] = $valord["15"] + $valord["16"];

$valore["12"] = $valore["13"] + $valore["14"] + $valore["17"] + $valore["18"];
$valord["12"] = $valord["13"] + $valord["14"] + $valord["17"];

if ($valore["12"] > $valord["12"]) {
  $dif = $valore["12"] - $valord["12"];
  $valore["19"] = 0;
  $valord["18"] = $dif;
}else{ 
  $dif = $valord["12"] - $valore["12"];
  $valore["19"] = $dif;
  $valord["18"] = 0;
}  

$valore["20"] = $valore["19"] + $valore["12"];
$valord["19"] = $valord["18"] + $valord["12"];

$valore["22"] = $valord["18"] - $valore["21"];
//print_r($valord);exit;
$texto4[0] = '';
$valor4[0] = '';
$texto11[0] = '';
$valor11[0] = '';
$texto17[0] = '';
$valor17[0] = '';
$texto22[0] = '';
$valor22[0] = '';

$cont = 0;
for($i=0;$i< pg_numrows($result_rpps);$i++) {
 db_fieldsmemory($result_rpps,$i);
 if (in_array($estrutural,$parametroe[4])){
    //  if($c61_instit==$instit) {
          $texto4[$cont]= $c60_descr; 
          $valor4[$cont]= $saldo_anterior;
          $cont++;
    //  } 
 }
}
$cont = 0;
for($i=0;$i< pg_numrows($result_rpps);$i++) {
 db_fieldsmemory($result_rpps,$i);
 if (in_array($estrutural,$parametroe[11])){
    //  if($c61_instit==$instit) {
          $texto11[$cont]= $c60_descr; 
          $valor11[$cont]= $saldo_anterior;
          $cont++;
    //  }
 }
}
$cont = 0;
for($i=0;$i< pg_numrows($result_rpps);$i++) {
 db_fieldsmemory($result_rpps,$i);
 if (in_array($estrutural,$parametrod[3])){
    //  if($c61_instit==$instit) {
          $texto17[$cont]= $c60_descr; 
          $valor17[$cont]= $saldo_anterior;
          $cont++;
    //  }
 }
}
$cont = 0;
for($i=0;$i< pg_numrows($result_rpps);$i++) {
 db_fieldsmemory($result_rpps,$i);
 if (in_array($estrutural,$parametrod[8])){
    //  if($c61_instit==$instit) {
          $texto22[$cont]= $c60_descr; 
          $valor22[$cont]= $saldo_anterior;
          $cont++;
    //  }
 }
}
if (count($texto4) > count($texto17)) {
  $for1 = count($texto4);
}else{
  $for1 = count($texto17);
}

if (count($texto11) > count($texto22)) {
  $for2 = count($texto11);
}else{
  $for2 = count($texto22);
}
///////////////////////////////////////////////////////////////////////////////

$pdf->setfont('arial','b',7);
$pdf->cell(185,$alt,'','',1,"C",0);
$pdf->cell(185,$alt,'REGIME PREVIDENCIA','TB',1,"C",0);
$pdf->cell(60,$alt,'ATIVO','RB',0,"C",0);
$pdf->cell(30,$alt,'VALOR','RB',0,"C",0);
$pdf->cell(63,$alt,'PASSIVO','RB',0,"C",0);
$pdf->cell(32,$alt,'VALOR','B',1,"C",0);
$pdf->setfont('arial','',7);
for($i=12;$i<19;$i++){
  $pdf->cell(60,$alt,$textoe[$i],'R',0,"L",0);
  if ($textoe[$i] == ''){;
   $pdf->cell(30,$alt,'','',0,"R",0);
  }else{
   $pdf->cell(30,$alt,db_formatar($valore[$i],'f'),'R',0,"R",0);
  } 
  $pdf->cell(63,$alt,$textod[$i],'R',0,"L",0);
  if ($textod[$i] == ''){;
    $pdf->cell(30,$alt,'','',1,"R",0);
  }else{
    $pdf->cell(32,$alt,db_formatar($valord[$i],'f'),'',1,"R",0);
  }  
}  
for($i=0;$i< $for2;$i++) {
 if (isset($texto11[$i])){
  $pdf->cell(60,$alt,'    '.substr($texto11[$i],0,35),'R',0,"L",0);
  $pdf->cell(30,$alt,db_formatar($valor11[$i],'f'),'R',0,"R",0);
 }else{
  $pdf->cell(60,$alt,'','R',0,"L",0);
  $pdf->cell(30,$alt,'','R',0,"R",0);
 }
 if (isset($texto22[$i])){
  $pdf->cell(63,$alt,'    '.substr($texto22[$i],0,37),'R',0,"L",0);
  $pdf->cell(32,$alt,db_formatar($valor22[$i],'f'),'',1,"R",0);
 }else{
  $pdf->cell(63,$alt,'','R',0,"L",0);
  $pdf->cell(32,$alt,'','',1,"R",0);
 }
}


$pdf->cell(60,$alt,'INSUFICIÊNCIA ANTES DA INSCRIÇÃO EM','TR',0,"L",0);
$pdf->cell(30,$alt,'','TR',0,"R",0);
$pdf->cell(63,$alt,'SUFICIÊNCIA ANTES DA INSCRIÇÃO EM','TR',0,"L",0);
$pdf->cell(32,$alt,'','T',1,"R",0);

$pdf->cell(60,$alt,'RESTOS A PAGAR NÃO PROCESSADOS(V)','BR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valore[19],'f'),'BR',0,"R",0);
$pdf->cell(63,$alt,'RESTOS A PAGAR NÃO PROCESSADOS(VI)','BR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valord[18],'f'),'B',1,"R",0);

$pdf->cell(60,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valore[20],'f'),'TBR',0,"R",0);
$pdf->cell(63,$alt,'TOTAL','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valord[19],'f'),'TB',1,"R",0);

$pdf->cell(153,$alt,'INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS (VII)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valore[21],'f'),'TB',1,"R",0);
$pdf->cell(153,$alt,'SUFICIÊNCIA APÓS A INSCRISÃO EM RESTOS APAGAR NÃO PROCESSADOS (VII) = (VI - VII)','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valore[22],'f'),'TB',1,"R",0);

$pdf->cell(60,$alt,'','',1,"L",0);
$ds1 = $valore["8"] + $valore["10"]+ $valore["19"] + $valore["21"];
$ds2 = $valord["7"] + $valord["18"];
if ($ds1 > $ds2) {
  $valordd = $ds1 - $ds2;
  $valorss = 0; 
}else{
  $valorss = $ds2 - $ds1;
  $valordd = 0; 
}

$pdf->cell(60,$alt,'DÉFICIT','TBR',0,"L",0);
$pdf->cell(30,$alt,db_formatar($valordd,'f'),'TBR',0,"R",0);
$pdf->cell(63,$alt,'SUPERÁVIT','TBR',0,"L",0);
$pdf->cell(32,$alt,db_formatar($valorss,'f'),'TB',1,"R",0);

$pdf->cell(190,$alt,'Fonte: Contabilidade',"",1,"L",0);

// assinaturas
$pdf->setfont('arial','',5);
$pdf->ln(20);

assinaturas(&$pdf,&$classinatura,'GF');

$pdf->Output();

?>