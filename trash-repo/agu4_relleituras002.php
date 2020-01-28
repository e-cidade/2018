<?php
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
include("libs/db_utils.php");
include_once("libs/db_stdlib.php");
include("agu3_conscadastro_002_classe.php");

$oGet      = db_utils::postMemory($_GET);

$cClRotulo = new rotulocampo;
$cClRotulo->label('x21_exerc');
$cClRotulo->label('x21_mes');
$cClRotulo->label('x17_descr');
$cClRotulo->label('x21_dtleitura');
$cClRotulo->label('x21_dtinc');
$cClRotulo->label('x21_leitura');
$cClRotulo->label('x19_conspadrao');
$cClRotulo->label('x21_consumo');
$cClRotulo->label('x21_excesso');

$oPdf      = new PDF();

$oAguaBase = new ConsultaAguaBase($oGet->matric);

$sSql      = $oAguaBase->GetAguaLeituraSQL();

$rSql      = pg_query($sSql);

if(pg_num_rows($rSql) == 0) {
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
  
}

$head2 = "Histórico de Leituras";
$head4 = "Matrícula: ".$matric;

$troca = 1;
$alt   = 4;
$total = 0;

$oPdf->Open();
$oPdf->AliasNBPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);
//$oPdf->DefOrientation = "L";

for($i = 0; $i < pg_num_rows($rSql); $i++) {
  
  $oLeituras = db_utils::fieldsMemory($rSql, $i, true);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {
    
    $oPdf->addpage();
    $oPdf->setfont('arial','b',8);
    $oPdf->cell(10, $alt, $RLx21_exerc        , 1, 0, "C", 1);
    $oPdf->cell(20, $alt, $RLx21_mes          , 1, 0, "C", 1); 
    $oPdf->cell(47, $alt, $RLx17_descr        , 1, 0, "C", 1);
    $oPdf->cell(25, $alt, $RLx21_dtleitura    , 1, 0, "C", 1);
    $oPdf->cell(25, $alt, $RLx21_dtinc        , 1, 0, "C", 1);
    $oPdf->cell(20, $alt, $RLx21_leitura      , 1, 0, "C", 1);
    $oPdf->cell(15, $alt, $RLx19_conspadrao   , 1, 0, "C", 1);
    $oPdf->cell(15, $alt, $RLx21_consumo      , 1, 0, "C", 1); 
    $oPdf->cell(15, $alt, $RLx21_excesso      , 1, 1, "C", 1); 
    $troca = 0;
    $p     = 0;
    
  }
  
  $oPdf->setfont('arial','',7);
   
  $oPdf->cell(10, $alt, $oLeituras->x21_exerc         , 0, 0, "C", $p);
  $oPdf->cell(20, $alt, db_mes($oLeituras->x21_mes, 2), 0, 0, "L", $p);
  $oPdf->cell(47, $alt, $oLeituras->x17_descr         , 0, 0, "L", $p);
  $oPdf->cell(25, $alt, $oLeituras->x21_dtleitura     , 0, 0, "C", $p);
  $oPdf->cell(25, $alt, $oLeituras->x21_dtinc         , 0, 0, "C", $p);
  $oPdf->cell(20, $alt, $oLeituras->x21_leitura       , 0, 0, "C", $p);
  $oPdf->cell(15, $alt, $oLeituras->x19_conspadrao    , 0, 0, "C", $p);
  $oPdf->cell(15, $alt, $oLeituras->x21_consumo       , 0, 0, "C", $p);
  $oPdf->cell(15, $alt, $oLeituras->x21_excesso       , 0, 1, "C", $p);
  
   
  if($p == 0) 
    $p = 1;
  else 
    $p = 0;
   
  $total++;
  
}


$oPdf->setfont('arial','b',8);
$oPdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$oPdf->Output();      

?>