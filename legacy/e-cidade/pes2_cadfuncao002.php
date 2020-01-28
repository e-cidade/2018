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
include("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r37_funcao');
$clrotulo->label('r37_descr');
$clrotulo->label('r37_vagas');
$clrotulo->label('r37_cbo');
$clrotulo->label('r37_lei');
$clrotulo->label('r37_class');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oGet = db_utils::postMemory($_GET,0);

$iInstit = db_getsession("DB_instit");
$head3 = "CADASTRO DE CARGOS";
$head4 = "PERÍODO : ".$oGet->mes." / ".$oGet->ano;

$sWhere = "rh37_instit = {$iInstit}";

if(isset($oGet->ordem) && $oGet->ordem == 'a'){
  $sOrder = " order by r37_descr ";
  $head6  = "Ordem: Alfabética";
} else {
  $sOrder = " order by r37_funcao ";
  $head6  = "Ordem: Numérica";
}

if (isset($oGet->ativo) && $oGet->ativo == 't') {
  $sWhere .= " and rh37_ativo is true";
  $head7   = "Ativo: Sim";
} else {
  $sWhere .= " and rh37_ativo is false";
  $head7   = "Ativo: Não";
}

$sSql  = "   select rh37_funcao as r37_funcao,                                                                        ";
$sSql .= "          rh37_descr  as r37_descr,                                                                         ";
$sSql .= "          rh37_vagas  as r37_vagas,                                                                         ";
$sSql .= "          rh37_cbo    as r37_cbo,                                                                           ";
$sSql .= "          rh37_lei    as r37_lei,                                                                           ";
$sSql .= "          rh37_class  as r37_class                                                                          "; 
$sSql .= "     from rhfuncao                                                                                          ";
$sSql .= "    where {$sWhere}                                                                                         "; 
$sSql .= " {$sOrder}                                                                                                  ";

$rsSql = db_query($sSql);
$iRows = pg_numrows($rsSql);

if ($iRows == 0){
  $sMsg = "Não existem Códigos cadastrados no período de $oGet->mes / $oGet->ano";
  db_redireciona("db_erros.php?fechar=true&db_erro=$sMsg");
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$total = 0;
$troca = 1;
$alt   = 4;

for ( $x = 0; $x < $iRows; $x++ ) {
	
   $oCargos  = db_utils::fieldsMemory($rsSql,$x);
   $iTamDesc = strlen($oCargos->r37_descr);
   $iTamLei  = strlen($oCargos->r37_lei);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,5,$RLr37_funcao                  ,1,0,"C",1);
      $pdf->cell(91,5,$RLr37_descr                   ,1,0,"C",1);
      $pdf->cell(20,5,$RLr37_vagas                   ,1,0,"C",1);
      $pdf->cell(30,5,$RLr37_cbo                     ,1,0,"C",1);
      $pdf->cell(30,5,$RLr37_class                   ,1,1,"C",1);

      if (isset($oGet->emitirlei) && $oGet->emitirlei == 't') {
        $pdf->cell(191,5,$RLr37_lei                  ,1,1,"C",1);
      }
      
      $troca = 0;
   }
   
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$oCargos->r37_funcao           ,0,0,"C",0);   
   $pdf->cell(91,$alt,$oCargos->r37_descr            ,0,0,"L",0);
   $pdf->cell(20,$alt,$oCargos->r37_vagas            ,0,0,"R",0);
   $pdf->cell(30,$alt,$oCargos->r37_cbo              ,0,0,"R",0);
   $pdf->cell(30,$alt,$oCargos->r37_class            ,0,1,"R",0);
   
   if (isset($oGet->emitirlei) && $oGet->emitirlei == 't') {
     $pdf->MultiCell(191,4,$oCargos->r37_lei         ,0,1,"R",0);   	
   }
   
   $total ++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(191,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"L",0);

$pdf->Output();
?>