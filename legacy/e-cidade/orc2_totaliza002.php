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

include("libs/db_liborcamento.php");
include("classes/db_orcelemento_classe.php");

// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;

include("fpdf151/pdf.php");
include("libs/db_sql.php");

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ; 
  $xvirg = ', ';
}
$nivela = substr($nivel,0,1);
if($nivela == 1)
   $tipo = 'ÓRGÃO';
elseif($nivela == 2)
   $tipo = 'UNIDADE';
elseif($nivela == 3)
   $tipo = 'FUNÇÃO';
elseif($nivela == 4)
   $tipo = 'SUBFUNÇÃO';
elseif($nivela == 5)
   $tipo = 'PROGRAMA';
elseif($nivela == 6)
   $tipo = 'PROJ/ATIV';
elseif($nivela == 7)
   $tipo = 'ELEMENTO';
elseif($nivela == 8)
   $tipo = 'RECURSO';
   
$head2 = "TOTAL DO ORCAMENTO";
$head3 = "POR ".$tipo;
$head4 = "EXERCICIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($orgaos);
// passa os parametros vindos da func_selorcdotacao_abas.php
$sele_work  = $clselorcdotacao->getDados();
$sele_work .= ' and w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
//db_criatabela(pg_exec("select * from t"));
//$result = db_dotacaosaldo($nivela,1,2,true,$sele_work);
//db_criatabela($result);exit;

if (substr($nivel,1,1) == 'A'){
   $nivelb = 2;
}else{
   $nivelb = 3;
}

//echo $nivela."<br>";
//echo $sele_work;

$result = db_dotacaosaldo($nivela,$nivelb, 2, true, $sele_work);


//db_criatabela($result);
//exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;

$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',7);

    $pdf->cell(40,$alt,"CÓDIGO",0,0,"L",0);
    $pdf->cell(60,$alt,"E S P E C I F I C A Ç Ã O",0,0,"L",0);
    $pdf->cell(25,$alt,"VALOR ORÇADO",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
  }

  $o58_valor = $dot_ini;
  
  $codigo = '';
  if($o58_orgao != 0){
    $descr = $o40_descr;
    $codigo .= db_formatar($o58_orgao,'s','0',2,'e');
  }
  if($o58_unidade != 0){
    $descr = $o41_descr;
    if (substr($nivel,1,1) == 'A' )
       $codigo .= '.';
    $codigo .= db_formatar($o58_unidade,'s','0',2,'e');
  }
  if($o58_funcao != 0){
    $descr = $o52_descr;
    if (substr($nivel,1,1) == 'A' )
       $codigo .= '.';
    $codigo .= db_formatar($o58_funcao,'s','0',2,'e');
  }
  if($o58_subfuncao != 0){
    $descr = $o53_descr;
    if (substr($nivel,1,1) == 'A' )
       $codigo .= '.';
    $codigo .= db_formatar($o58_subfuncao,'s','0',4,'e');
  }
  if($o58_programa != 0){
    $descr = $o54_descr;
    if (substr($nivel,1,1) == 'A')
       $codigo .= '.';
    $codigo .= db_formatar($o58_programa,'s','0',2,'e');
  }
  if($o58_projativ != 0){
    $descr = $o55_descr;
    if (substr($nivel,1,1) == 'A')
       $codigo .= '.';
    $codigo .= db_formatar($o58_projativ,'s','0',4,'e');
  }
  if($o58_elemento != 0){
    $descr = $o56_descr;
    if (substr($nivel,1,1) == 'A')
       $codigo .= '.';
    $codigo .= $o58_elemento;
  }
  if($o58_codigo != 0 ){
    $descr = $o15_descr;
    if (substr($nivel,1,1) == 'A')
       $codigo .= '.';
    $codigo .= db_formatar($o58_codigo,'s','0',4,'e');
  }





 //$tipo_nivel = 7;
 // 1 = orgao
 // 2 = unidade
 // 3 = funcao
 // 4 = subfuncao
 // 5 = programa
 // 6 = projeto/atividade
 // 7 = elemento
 
  $pdf->setfont('arial','',6);

//  if($tipo_nivel == 7)
//     $pdf->cell(22,$alt,db_formatar($codigo,'elemento'),0,0,"L",0);
//  else
     $pdf->cell(50,$alt,$codigo,0,0,"R",0);
  
  $pdf->cell(60,$alt,$descr,0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($o58_valor,'f'),0,1,"R",0);
  $total += $o58_valor;
}
$pdf->setfont('arial','b',7);
$pdf->ln(3);
$pdf->cell(110,$alt,'T O T A L',0,0,"C",0);
$pdf->cell(25,$alt,db_formatar($total,'f'),0,1,"R",0);

$pdf->Output();
//include("fpdf151/geraarquivo.php");
db_query("commit");