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

include("fpdf151/assinatura.php");
$classinatura = new cl_assinatura;

// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;


$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg      = '';
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

if($origem == "O"){
    $xtipo = "ORÇAMENTO";
}else{
    $xtipo = "BALANÇO";
    if($opcao == 3)
      $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
    else
      $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
if($recurso==0){
  $head3 = "BALANCETE DA RECEITA ";
}else{
  $head2 = "BALANCETE DA RECEITA ";
  $resrec = pg_exec("select o15_descr from orctiporec where o15_codigo = $recurso");
  $head3 = "Recurso: ".$recurso."-".substr(pg_result($resrec,0,0),0,30);
  $db_filtro .= " and o70_codigo = $recurso";
}
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages();	
$total = 0;
$pdf->setfillcolor(241);
$pdf->setfont('arial','b',8);
$pdf->setleftmargin(3);
$troca = 1;
$alt = 4;

$tm_fonte          =  6;
$tm_valor          = 17;
$tm_descr          = 65;
$tm_estrut         = 24;
$tm_reduz          = 10;
$fundo=0;
if ($impressao=='paisagem'){
	$pdf->setfont('arial','b',10);
	$tm_fonte =8;
    $tm_valor =24;
    $tm_descr = 90;
    $tm_estrut = 33;
    $tm_reduz = 15;    
} 	

//$sql = "select * from work order by elemento";
//$result = pg_exec($sql);
$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

$result = db_receitasaldo(11,1,$opcao,true,$db_filtro,$anousu,$dataini,$datafin);
 

//db_criatabela($result);exit;
$total_saldo_inicial              =0;
$total_saldo_prevadic_acum       =0;
$total_saldo_arrecadado          =0;
$total_saldo_arrecadado_acumulado=0;
$total_saldo_a_arrecadar        = 0;

for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
//  if($o57_fonte == '400000000000000'){
  if(db_conplano_grupo($anousu,$o57_fonte,9004) == true){
    $total_saldo_inicial              = $saldo_inicial;
    $total_saldo_prevadic_acum        = $saldo_prevadic_acum;
    $total_saldo_arrecadado           = $saldo_arrecadado;
    $total_saldo_arrecadado_acumulado = $saldo_arrecadado_acumulado;
    $total_saldo_a_arrecadar          = $saldo_a_arrecadar;
    break;
  }
}	


$pagina = 1;
$tottotal = 0;
$analitica=false;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;
     
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    if ($impressao=='paisagem'){
         $pdf->addpage('L');
    }else {
    	$pdf->addpage();
    } 	
    $pdf->setfont('arial','b',$tm_fonte);
    $pdf->cell($tm_estrut,$alt,"RECEITA",0,0,"L",0);
    $pdf->cell($tm_descr,$alt,"DESCRIÇÃO",0,0,"L",0);
    $pdf->cell($tm_reduz,$alt,"REDUZ",0,0,"L",0);
    $pdf->cell($tm_reduz,$alt,"REC",0,0,"L",0);
    if($origem == "O"){
      $pdf->cell($tm_valor,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"PREV.ADIC.",0,0,"R",0);
    }else{
      $pdf->cell($tm_valor,$alt,"PREVISTO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"PREV.ADIC.",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"ARRECADADO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"ARREC. ANO",0,0,"R",0);
      $pdf->cell($tm_valor,$alt,"DIFERENÇA",0,0,"R",0);
    }
    $pdf->cell(10,$alt,"Perc",0,1,"R",0);
    $pdf->ln(3);
  
  }
  /**
   *  contas analiticas possuem "o70_codrec" e serão apresentadas sombeadas
   */
  if ( $o70_codrec != 0 && $impressao=='paisagem'){
  	 $fundo=1;
  }else {
  	 $fundo=0;
  }		
  $pdf->setfont('arial','',$tm_fonte);
  $pdf->cell($tm_estrut,$alt,db_formatar($elemento,'receita'),0,0,"L",$fundo);
  $pdf->cell($tm_descr,$alt,$descr,0,0,"L",$fundo);

  if($o70_codrec != 0 ){
     $pdf->cell($tm_reduz,$alt,$o70_codrec,0,0,"C",$fundo);
  }else{
     $pdf->cell($tm_reduz,$alt,'',0,0,"C",$fundo);
  }
  if($o70_codigo != 0 ){
     $pdf->cell($tm_reduz,$alt,db_formatar($o70_codigo,'recurso'),0,0,"L",$fundo);
  }else{
     $pdf->cell($tm_reduz,$alt,'',0,0,"L",$fundo);
  }
  if($origem == "O"){
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",$fundo);
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",$fundo);
  }else{
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",$fundo);
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_prevadic_acum,'f'),0,0,"R",$fundo);
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_arrecadado,'f'),0,0,"R",$fundo);
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_arrecadado_acumulado,'f'),0,0,"R",$fundo);
    $pdf->cell($tm_valor,$alt,db_formatar($saldo_a_arrecadar,'f'),0,0,"R",$fundo);
  }

  if($saldo_inicial + $saldo_prevadic_acum >0)
    $pdf->cell($tm_reduz,$alt,db_formatar( $saldo_arrecadado_acumulado / ($saldo_inicial + $saldo_prevadic_acum ) *100,'f'),0,1,"R",$fundo);
  else
    $pdf->cell($tm_reduz,$alt,db_formatar(0,'f'),0,1,"R",$fundo);
     
}
$pdf->setfont('arial','B',$tm_fonte);
$pdf->cell($tm_estrut,$alt,'',0,0,"L",0);
$pdf->cell($tm_descr+($tm_reduz*2),$alt,'TOTAL ',0,0,"L",0);
//$pdf->cell(10,$alt,'',0,0,"L",0);
if($origem == "O"){
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,1,"R",0);
}else{
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_prevadic_acum,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_arrecadado,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_arrecadado_acumulado,'f'),0,0,"R",0);
  $pdf->cell($tm_valor,$alt,db_formatar($total_saldo_a_arrecadar,'f'),0,0,"R",0);
  if($total_saldo_inicial + $total_saldo_prevadic_acum >0)
    $pdf->cell($tm_reduz,$alt,db_formatar( $total_saldo_arrecadado_acumulado / ($total_saldo_inicial + $total_saldo_prevadic_acum ) *100,'f'),0,1,"R",0);
  else
    $pdf->cell($tm_reduz,$alt,db_formatar(0,'f'),0,1,"R",0);
 
}

$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);

//echo $ass_pref;
$pdf->setfont('arial','B',8);
if( $pdf->gety() > ( $pdf->h - 50 ) ){
    if ($impressao=='paisagem'){
         $pdf->addpage('L');
    }else {
    	$pdf->addpage();
    }
}
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,4,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,4,$ass_cont,0,"C",0,0);


$pdf->Output();

pg_exec("commit");

?>