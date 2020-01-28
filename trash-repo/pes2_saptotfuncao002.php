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
include("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_funcao');
$clrotulo->label('r37_descr');
$clrotulo->label('lei');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oGet = db_utils::postMemory($_GET,0);

$sWhere  = "";
$iInstit = db_getsession("DB_instit");

if(isset($oGet->selec) && $oGet->selec != ''){
  $sWhere = " and rh02_codreg in (".$oGet->selec.") ";
}

$head3 = "RELATÓRIO DE CARGOS";
$head5 = "PERÍODO : ".$oGet->mes." / ".$oGet->ano;

$sSql  = "   select rh02_regist as r01_regist,                                         ";
$sSql .= "          z01_nome,                                                          ";
$sSql .= "          rh37_descr  as r37_descr,                                          ";
$sSql .= "          rh30_descr,                                                        ";
$sSql .= "          rh37_vagas  as r37_vagas,                                          ";
$sSql .= "          rh37_lei    as lei,                                                ";
$sSql .= "          rh37_funcao 			                                                 ";
$sSql .= "     from rhpessoalmov                                                       ";
$sSql .= "          inner join rhpessoal     on rh01_regist = rh02_regist              ";
$sSql .= "          inner join rhfuncao      on rh02_funcao = rh37_funcao              ";
$sSql .= "                                  and rh02_instit = rh37_instit              ";
$sSql .= "          left  join rhpesrescisao on rh05_seqpes = rh02_seqpes              ";
$sSql .= "          inner join cgm           on rh01_numcgm = z01_numcgm               ";
$sSql .= "          left join rhregime       on rh30_codreg = rhpessoalmov.rh02_codreg ";
$sSql .= "                                  and rh30_instit = rhpessoalmov.rh02_instit ";    
$sSql .= "    where rh02_anousu = {$oGet->ano}                                         ";
$sSql .= "      and rh02_mesusu = {$oGet->mes}                                         ";
$sSql .= "      and rh02_instit = {$iInstit}                                           ";
$sSql .= "      and rh05_recis is null                                                 ";
$sSql .= " {$sWhere}                                                                   ";
$sSql .= " order by r37_descr,rh37_funcao,z01_nome                                     ";

$rsSql = db_query($sSql);
$iRows = pg_numrows($rsSql);
if ($iRows == 0) {
  $sMsg = "Não existem funcionários no período de {$oGet->mes} / {$oGet->ano}";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->setleftmargin(25);

$func                  = 0;
$func_c                = 0;
$tot_c                 = 0;
$total                 = 0;
$nVagasExistentes      = 0;
$nTotalVagasExistentes = 0;
$nTotalVagas           = 0;
$nTGeralVagas          = 0;
$troca                 = 1;
$alt                   = 4;
$lImpressoLei          = false;
$rh37_funcao_ant       = null;
$funcao  							 = null;

for ( $x = 0; $x < $iRows; $x++ ) {
	db_fieldsmemory($rsSql,$x);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     $pdf->addpage();
    
  	
    if ( $funcao != $r37_descr && $rh37_funcao != $rh37_funcao_ant && $troca != 1) {
     
       $lImpressoLei = false;
        if($funcao != ''){
      
          $pdf->ln(1);
          $nVagasExistentes = ($iVagas-$func_c);
          $pdf->cell(40,$alt,'Total de Vagas:  '.$iVagas,0,0,"L",0);
          $pdf->cell(30,$alt,'Ocupados:  '.$func_c,0,0,"L",0);
          $pdf->cell(40,$alt,'Vagas Existentes:  '.$nVagasExistentes,0,1,"L",0);
       
          $func_c = 0;
          $tot_c  = 0;
        }
     }
    
    $pdf->setfont('arial','b',8);  
    if ( $funcion == 't' ) {
      
      $pdf->cell(15,$alt,'MATRÍC.',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
    } else {
      $pdf->cell(100,$alt,'CARGOS',1,0,"C",1);
    }
    
    $pdf->cell(60,$alt,'VÍNCULO',1,1,"C",1);
    
    if (isset($oGet->emitirlei) && $oGet->emitirlei == 't') {
      $pdf->cell(135,$alt,'LEI',1,1,"C",1);
    }
    
    $funcao = '';
    $troca  = 0;
   }
    
   if ( $funcao != $r37_descr || $rh37_funcao != $rh37_funcao_ant ) {
     
     $lImpressoLei = false;
     if($funcao != ''){
      
       $pdf->ln(1);
       $nVagasExistentes = ($iVagas-$func_c);
       $pdf->cell(40,$alt,'Total de Vagas:  '.$iVagas,0,0,"L",0);
       $pdf->cell(30,$alt,'Ocupados:  '.$func_c,0,0,"L",0);
       $pdf->cell(40,$alt,'Vagas Existentes:  '.$nVagasExistentes,0,0,"L",0);
       
       $func_c = 0;
       $tot_c  = 0;
     }
     
     $pdf->setfont('arial','b',9);
     $pdf->ln(10);

     if ( $funcion != 't' ) {
      
       $pdf->cell(100,$alt,$r37_descr,0,0,"L",1);
       $pdf->cell(60,$alt,$rh30_descr,0,1,"L",1);
     }else{
       $pdf->cell(100,$alt,$r37_descr,0,1,"L",0);
     }
     
     $funcao = $r37_descr;
   }
   
   if (isset($oGet->emitirlei) && $oGet->emitirlei == 't' && !$lImpressoLei) {
     
   	 $pdf->setfont('arial','B',7);
     $pdf->MultiCell(191,$alt,$lei,0,1,"R",0);     
     $lImpressoLei = true;
     
   }
   
   if ( $funcion == 't' ) {
    
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
     $pdf->cell(60,$alt,$rh30_descr,0,1,"L",0);
   }
   
   $iVagas = $r37_vagas;
   $func++;
   $func_c++;
	 $rh37_funcao_ant = $rh37_funcao;
}

$pdf->ln(1);
$nVagasExistentes = ($r37_vagas-$func_c);
$pdf->cell(40,$alt,'Total de Vagas:  '.$r37_vagas,0,0,"L",0);
$pdf->cell(30,$alt,'Ocupados:  '.$func_c,0,0,"L",0);
$pdf->cell(40,$alt,'Vagas Existentes:  '.$nVagasExistentes,0,0,"L",0);

$pdf->ln(5);
$pdf->cell(115,$alt,'Total da Geral  :  '.$func.' Funcionários ',0,0,"L",0);

$pdf->Output(); 
?>