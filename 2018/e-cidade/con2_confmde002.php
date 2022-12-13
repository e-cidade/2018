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
include("fpdf151/assinatura.php");
include("classes/db_orcparamrel_classe.php");
include("libs/db_libcontabilidade.php");
include("dbforms/db_funcoes.php");

$anousu = db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$classinatura = new cl_assinatura;
$orcparamrel = new cl_orcparamrel;

$anousu = db_getsession("DB_anousu");
$dt = datas_bimestre($bimestre,$anousu); // no dbforms/db_funcoes.php
$dt_ini= $dt[0]; // data inicial do período
$dt_fin= $dt[1]; // data final do período


$interferencias = $orcparamrel->sql_parametro('19','0');

//-----------------------------------------
$r_orgao = pg_exec("select distinct(o41_orgao) from orcunidade where o41_ident=03 and o41_anousu=$anousu");
if (pg_numrows($r_orgao) == 0 ){
   db_redireciona('db_erros.php?fechar=true&db_erro=No cadastro das unidades não foi definido o identificador do tribunal!');   
   exit;
}   
db_fieldsmemory($r_orgao,0);
$orgao_educacao = $o41_orgao;

$recurso_mde  = 20; // recurso fixo (?)
//-----------------------------------------

$anousu  = db_getsession("DB_anousu");
$total_saldo_inicial             =0;
$total_saldo_prevadic_acum       =0;
$total_saldo_arrecadado          =0;
$total_saldo_arrecadado_acumulado=0;
$total_saldo_a_arrecadar        = 0;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
      $descr_inst .= $xvirg.$nomeinst ;
        $xvirg = ', ';
}
$head2 = "DEMONSTRATIVO DAS RECEITAS E DESPESAS COM MDE";
$head6 = "PERÍODO :   $bimestre º  BIMESTRE";

$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu")."  ";
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$db_filtro .= " and o70_codigo = $recurso_mde and fc_conplano_grupo($anousu,substr(o57_fonte,1,2)||'%',9000) is false ";
//$db_filtro .= " and o70_codigo = $recurso_mde and substr(o57_fonte,1,2)<>'49' ";
$result = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);

//echo $db_filtro;
//echo $result; exit;

$sele_work = " o58_codigo = $recurso_mde and o58_orgao=$orgao_educacao";
$result_desp = db_dotacaosaldo(1,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);
// db_criatabela($result_desp);
// exit;

$result_balancete = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dt_ini,$dt_fin,false);
//db_criatabela($result_balancete);
//exit;



$pagina = 1;
$tottotal = 0;
$total_saldo_inicial              = 0;
$total_saldo_prevadic_acum        = 0;
$total_saldo_arrecadado           = 0;
$total_saldo_arrecadado_acumulado = 0;
$total_saldo_a_arrecadar          = 0;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $elemento = $o57_fonte;
  $descr    = $o57_descr;
     
//  if($o57_fonte == '400000000000000' || $o57_fonte == "900000000000000"){
  if (db_conplano_grupo($anousu,$o57_fonte,9004) == true) {
       $total_saldo_inicial              += $saldo_inicial;
       $total_saldo_prevadic_acum        += $saldo_prevadic_acum;
       $total_saldo_arrecadado           += $saldo_arrecadado;
       $total_saldo_arrecadado_acumulado += $saldo_arrecadado_acumulado;
       $total_saldo_a_arrecadar          += $saldo_a_arrecadar;
    continue;
  }
	
  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',6);
    $pdf->cell(145,$alt,"FONTES DE RECURSO PARA MDE",0,0,"L",0);
    $pdf->cell(17,$alt,"PREVISTO",0,0,"R",0);
    $pdf->cell(17,$alt,"ARREC. ANO",0,1,"R",0);
    $pdf->ln(3);
  
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(145,$alt,$descr,0,0,"L",0,'','.');
  $pdf->cell(17,$alt,db_formatar($saldo_inicial,'f'),0,0,"R",0);
  $pdf->cell(17,$alt,db_formatar($saldo_arrecadado_acumulado,'f'),0,1,"R",0);
}
$pdf->setfont('arial','B',6);
$pdf->cell(145,$alt,'TOTAL ',0,0,"L",0);
$pdf->cell(17,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
$pdf->cell(17,$alt,db_formatar($total_saldo_arrecadado_acumulado,'f'),0,1,"R",0);


/*  imprime despesa do MDE  */
$total_despesas = 0 ;
$pdf->Ln(3);
$pdf->setfont('arial','b',7);
$pdf->cell(145,$alt,"RELAÇÃO DAS DESPESAS ",0,0,"L",0);
$pdf->cell(17,$alt,"",0,0,"R",0);
$pdf->cell(17,$alt,"Valor",0,1,"R",0);
for($i=0;$i<pg_numrows($result_desp);$i++){
  db_fieldsmemory($result_desp,$i);
  $pdf->setfont('arial','',6);
  $pdf->cell(145,$alt,$o40_descr,0,0,"L",0,'','.');
  $pdf->cell(17,$alt,'liquidado',0,0,"C",0);
  $pdf->cell(17,$alt,db_formatar($liquidado,'f'),0,1,"R",0);
  $total_despesas += $liquidado;
} 
// lista interferencias
for($i=0;$i<pg_numrows($result_balancete);$i++){
  db_fieldsmemory($result_balancete,$i);
  $estrutural = $estrutural;
  if (in_array($estrutural,$interferencias)){
      $pdf->setfont('arial','',6);
      $pdf->cell(145,$alt,$c60_descr,0,0,"L",0,'','.');
      $pdf->cell(17,$alt,'',0,0,"R",0);
      $pdf->cell(17,$alt,db_formatar($saldo_final,'f'),0,1,"R",0);
      $total_despesas += $saldo_final;
  }    
} 
$pdf->setfont('arial','B',6);
$pdf->cell(145,$alt,'TOTAL ',0,0,"L",0);
$pdf->cell(17,$alt,'',0,0,"R",0);
$pdf->cell(17,$alt,db_formatar($total_despesas,'f'),0,1,"R",0);


$pdf->Ln(3);
$pdf->setfont('arial','b',7);
$pdf->cell(145,$alt," % DAS DESPESAS SOBRE AS FONTES DE RECURSO ",0,0,"L",0);
$total = @(($total_despesas*25)/$total_saldo_arrecadado_acumulado);
$pdf->cell(17,$alt,"%",0,0,"R",0);
$pdf->cell(17,$alt,db_formatar($total,'f'),0,1,"R",0);


$tes =  "______________________________"."\n"."Tesoureiro";
$sec =  "______________________________"."\n"."Secretaria da Fazenda";
$cont =  "______________________________"."\n"."Contador";
$pref =  "______________________________"."\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000,$pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec  = $classinatura->assinatura(1002,$sec);
$ass_tes  = $classinatura->assinatura(1004,$tes);
$ass_cont = $classinatura->assinatura(1005,$cont);

$pdf->setfont('arial','',7);

if( $pdf->gety() > ( $pdf->h - 30 ) )
  $pdf->addpage();

$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();
$pdf->multicell($largura,2,$ass_pref,0,"C",0,0);
$pdf->setxy($largura,$pos);
$pdf->multicell($largura,2,$ass_cont,0,"C",0,0);



$pdf->Output();

pg_exec("commit");

?>