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
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_conlancamrec_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdig_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clconlancamval = new cl_conlancamval;
$clconlancamcgm = new cl_conlancamcgm;
$clconlancam  = new cl_conlancam;
$auxiliar     = new cl_conlancam;
$clorcsuplem = new cl_orcsuplem;
$clconlancamrec = new cl_conlancamrec;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdot  = new cl_conlancamdot;
$clconlancamdig  = new cl_conlancamdig;

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$clrotulo->label("c60_descr");
$clrotulo->label("c53_descr");
$clrotulo->label("c53_coddoc");

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$numero_instit = pg_numrows($resultinst);
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}
///////////////////////////////////////////////////////////////////////
 $data1="";
 $data2="";
 @$data1="$data1_ano-$data1_mes-$data1_dia"; 
 @$data2="$data2_ano-$data2_mes-$data2_dia"; 
 if (strlen($data1) < 7){
    $data1= db_getsession("DB_anousu")."-01-31";
 }  
 if (strlen($data2) < 7){
    $data2= db_getsession("DB_anousu")."-12-31";
 }  

//---------
$instits = "(".str_replace('-',', ',$db_selinstit).") ";

$xtipo = ' c53_coddoc in (';
$tem_outro = false;
if(isset($emp)){
  if($rp == 's')
     $xtipo .= '1,2,32';
  else
     $xtipo .= '1,2';
  $tem_outro = true;
}
if(isset($liq)){
  if($tem_outro == true)
    $xtipo .= ',';
  if($rp == 's')
    $xtipo .= '3,4,23,24,33,34';
  else
    $xtipo .= '3,4,23,24'; 
  $tem_outro = true;
}
if(isset($pag)){
  if($tem_outro == true)
    $xtipo .= ',';
  if($rp == 's')
    $xtipo .= '5,6,35,36';
  else
    $xtipo .= '5,6'; 
}
$xtipo .= ')';

if($credor == 's'){
  $yordem = ' order by e60_numcgm,e60_anousu,e60_codemp,c70_codlan';
}else{
  $yordem = ' order by e60_anousu,e60_codemp,c70_codlan';
}
$sql = "
select conlancam.*,
       e60_numcgm,
       z01_nome,
       e60_numemp,
       e60_anousu,
       lpad(e60_codemp,5,0) as e60_codemp,
       c71_coddoc,
       c53_descr,
       o56_elemento, 
       c80_codord,
       o56_descr  
from conlancam 
     inner join conlancamemp 	on c70_codlan = c75_codlan
     left  join conlancamord    on c70_codlan = c80_codlan 
     inner join conlancamdoc 	on c71_codlan = c70_codlan 
     inner join conhistdoc 	on c53_coddoc = c71_coddoc 
     inner join empempenho 	on e60_numemp = c75_numemp 
     inner join orcdotacao 	on o58_coddot = e60_coddot 
     				and e60_anousu = o58_anousu 
     inner join orcelemento 	on o58_codele = o56_codele and o56_anousu = o58_anousu
     inner join cgm 		on z01_numcgm = e60_numcgm
where $txt_where $xtipo
 and orcdotacao.o58_instit in $instits 
$yordem
";

//echo $sql ; exit;

$result = pg_exec($sql);

//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Movimentações para esses dados.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

 $dt1="$data1_dia-$data1_mes-$data1_ano"; 
 $dt2="$data2_dia-$data2_mes-$data2_ano"; 
 
$head2="MOVIMENTAÇÃO DA DESPESA";
$head4 ="Período : $dt1 à $dt2";
$head5 = "Instituições : $descr_inst"; 
$troca = 1;
$alt = 5;
$total_liq = 0;
$total_emp = 0;
$total_pago = 0;
$total_anuliq = 0;
$total_anupago = 0;
$cor = 0;
$emp = 0;
$cgm = 0;
   for($x=0;$x<pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);

   if($cgm != $e60_numcgm && $troca != 1 && $credor == 's'){
     $troca = 1;
   }
     
   if ($pdf->gety() > ($pdf->h - 30) || $troca ==1){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);

      if($credor == 's'){
        $pdf->multicell(0,4,'Credor : '.$e60_numcgm.' - '.$z01_nome,0,"L");
        $cgm = $e60_numcgm;
      }
      $pdf->ln(3);
      $pdf->cell(23,$alt,'EMPENHO',1,0,"C",1);
      $pdf->cell(23,$alt,'DATA',1,0,"C",1);
      $pdf->cell(23,$alt,'ORDEM',1,0,"C",1);
      $pdf->cell(50,$alt,'MOVIMENTAÇÃO',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   if($emp != $e60_codemp){
     $emp = $e60_codemp;
     if($cor == 0)
       $cor = 1;
     else
       $cor = 0;
   }
   $pdf->setfont('arial','',8);
//   $dots = $pdf->preenchimento($r13_descr,60);
   $pdf->cell(23,$alt,$e60_codemp.'/'.$e60_anousu.'   '.$c71_coddoc,0,0,"C",$cor);
   $pdf->cell(23,$alt,db_formatar($c70_data,'d'),0,0,"C",$cor);
   $pdf->cell(23,$alt,$c80_codord,0,0,"C",$cor);
   $pdf->cell(50,$alt,$c53_descr,0,0,"L",$cor);
   $pdf->cell(30,$alt,db_formatar($c70_valor,'f'),0,1,"R",$cor);
   if($c71_coddoc == '1' || $c71_coddoc == '2' || $c71_coddoc == '32'){
     if($c71_coddoc == '1'){
       $total_emp += $c70_valor;
     }else{
       $total_emp -= $c70_valor;
     }
   }elseif($c71_coddoc == '5' || $c71_coddoc == '6' || $c71_coddoc == '35' || $c71_coddoc == '36'){
     if($c71_coddoc == '5' || $c71_coddoc == '35'){
       $total_pago += $c70_valor;
     }else{
       $total_pago -= $c70_valor;
     }
   }else{
     if($c71_coddoc == '3' ||$c71_coddoc == '23'||$c71_coddoc == '33'){
       $total_liq += $c70_valor;
     }elseif($c71_coddoc == '4' ||$c71_coddoc == '24'||$c71_coddoc == '34'){
       $total_liq -= $c70_valor;
     }
   }
     
   }
   if($total_emp > 0){
     $pdf->cell(30,$alt,'Total Empenhado : ',0,0,"R",$cor);
     $pdf->cell(30,$alt,db_formatar($total_emp,'f'),0,1,"R",$cor);
   }
   if($total_liq > 0){
     $pdf->cell(30,$alt,'Total Liquidado : ',0,0,"R",$cor);
     $pdf->cell(30,$alt,db_formatar($total_liq,'f'),0,1,"R",$cor);
   }
   if($total_emp > 0){
     $pdf->cell(30,$alt,'Total Pago : ',0,0,"R",$cor);
     $pdf->cell(30,$alt,db_formatar($total_pago,'f'),0,1,"R",$cor);
   }


//include("fpdf151/geraarquivo.php");
$pdf->output();

?>