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
//include("libs/db_stdlib.php");
include("libs/db_utils.php");
include("classes/db_rhvisavalecad_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$oGet = db_utils::postMemory($_GET,0);

$clrhvisavalecad = new cl_rhvisavalecad;
$clrotulo = new rotulocampo;
$clrhvisavalecad->rotulo->label();
$clrotulo->label("z01_nome");

//$ano = 2006;
//$mes = 3;


$head2 = "CADASTRO DO VISA VALE";
$head4 = "PERÍODO : ".$mes." / ".$ano;
$head6 = ($qbr=="s"?"Com quebra de página":"Sem quebra de página");

$order = "";
$head5 = "TIPO DE RESUMO: ";
if($res == "g"){
  $head5.= "Geral";
}else if($res == "o"){
  $head5.= "Órgão";
  $pesquisa = " DESTE ÓRGÃO";
  $order = "rh26_orgao,";
}else if($res == "l"){
  $head5.= "Unidade";
  $pesquisa = " DESTA UNIDADE";
  $order = "rh26_orgao,rh26_unidade,";
}else if($res == "lc"){
  $head5.= "Unidade Completa";
  $pesquisa = " DESTA UNIDADE";
  $order = "r70_codigo,";
}


$dbwhere = " rh49_instit = ".db_getsession("DB_instit")." and rh49_anousu = $ano and rh49_mesusu = $mes ";
if($or1 != "" && $or2 != ""){
  $head7 = "Órgãos entre $or1 e $or2"; 
  $dbwhere.= " and rh26_orgao between ".$or1." and ".$or2;
}else if($or1 != ""){
  $head7 = "Órgãos maiores que $or1"; 
  $dbwhere.= " and rh26_orgao = ".$or1;
}else if($or2 != ""){
  $head7 = "Órgãos menores que $or2"; 
  $dbwhere.= " and rh26_orgao = ".$or2;
}

$sql = $clrhvisavalecad->sql_query_lotaexe(null," rhvisavalecad.*,z01_nome,o40_orgao,o40_codtri,o40_descr,o41_codtri,o41_unidade,o41_descr,r70_codigo,r70_descr,r70_estrut ",$order."z01_nome", $dbwhere);

//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Vales cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca        = 1;
$alt          = 4;
$tot_func     = 0;
$tot_val      = 0;
$tot_valmes   = 0;
$pre          = 0;

$organt       = "";
$lcant        = "";
$lotaant      = "";

$qtdregqbr    = 0;
$valregqbr    = 0;
$valmesregqbr = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
  
   $pdf->setfont('arial','b',8);
   if(($res=="o" && $organt != $o40_orgao) || ($res=="l" && $lcant != $o41_unidade) || ($res=="lc" && $lotaant != $r70_codigo)){
     $pdf->cell(149,$alt,'TOTAL DE FUNCIONARIOS'.$pesquisa.': '.$qtdregqbr,"T",0,"L",0);
     $pdf->cell(20,$alt,db_formatar($valregqbr,'f'),"T",0,"R",0);
     $pdf->cell(110,$alt,db_formatar($valmesregqbr,'f'),"T",1,"R",0);
     if($qbr == "s"){
       $troca = 1;
     }
     $qtdregqbr    = 0;
     $valregqbr    = 0;
     $valmesregqbr = 0;
   }

   if ($pdf->gety() > $pdf->h - 35 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->cell(30,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(30,$alt,'CGM',1,0,"C",1);
      $pdf->cell(79,$alt,'NOME',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR FIXO',1,0,"C",1);
      $pdf->cell(40,$alt,'DIAS AFASTADOS',1,0,"C",1);
      $pdf->cell(40,$alt,'PERC. DEPÓSITO',1,0,"C",1);
      $pdf->cell(30,$alt,'VALOR MÊS',1,1,"C",1);
      $total = 0;
      $troca = 0;
      $pre = 1;
      $organt = "";
      $lcant = "";
      $lotaant = "";
   }
   if($res=="o" && $organt != $o40_orgao){
      $pdf->ln(2);
      $pdf->cell(279,$alt,$o40_codtri." - ".$o40_descr,1,1,"L",1);
      $pre = 1;
   }
   if($res=="l" && $lcant != $o41_unidade){
      $pdf->ln(2);
      $bord = 1;
      if($organt != $o40_orgao || $qbr == "s"){
	      $bord = "LBR";
        $pdf->cell(279,$alt,$o40_codtri." - ".$o40_descr,"LTR",1,"L",1);
      }
      $pdf->cell(279,$alt,$o41_codtri." - ".$o41_descr,$bord,1,"L",1);
      $pre = 1;
   }
   if($res=="lc" && $lotaant != $r70_codigo){
      $pdf->ln(2);
      $pdf->cell(279,$alt,$r70_codigo." - ".$r70_estrut." - ".$r70_descr,1,1,"L",1);
      $pre = 1;
   }
   if($pre == 0){
     $pre = 1;
   }else{
     $pre = 0;
   }
   
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$rh49_regist,0,0,"C",$pre);
   $pdf->cell(30,$alt,$rh49_numcgm,0,0,"C",$pre);
   $pdf->cell(79,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(30,$alt,db_formatar($rh49_valor,'f'),0,0,"R",$pre);
   $pdf->cell(40,$alt,$rh49_diasafasta,0,0,"R",$pre);
   $pdf->cell(40,$alt,$rh49_percdep,0,0,"R",$pre);
   $pdf->cell(30,$alt,db_formatar($rh49_valormes,'f'),0,1,"R",$pre);
   $tot_func     += 1;
   $tot_val      += $rh49_valor;
   $tot_valmes   += $rh49_valormes;

   $qtdregqbr++;
   $valregqbr    += $rh49_valor;
   $valmesregqbr += $rh49_valormes;
   
   $organt = $o40_orgao;
   $lcant = $o41_unidade;
   $lotaant = $r70_codigo;
}
$pdf->setfont('arial','b',8);
if($res != "g"){
  $pdf->cell(149,$alt,'TOTAL DE FUNCIONARIOS DESTA '.$pesquisa.': '.$qtdregqbr,"T",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($valregqbr,'f'),"T",0,"R",0);
  $pdf->cell(110,$alt,db_formatar($valmesregqbr,'f'),"T",1,"R",0);
}

$pdf->ln(2);
$pdf->cell(149,$alt,'TOTAL GERAL DE FUNCIONARIOS : '.$tot_func,"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_val,'f'),"T",0,"R",0);
$pdf->cell(110,$alt,db_formatar($tot_valmes,'f'),"T",1,"R",0);

$pdf->Output();
?>