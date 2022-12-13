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
include("classes/db_cgm_classe.php");

$oGet  = db_utils::postMemory($_GET);
$clcgm = new cl_cgm();

   if(!isset($oGet->numcgm) && !isset($oGet->matric) && !isset($oGet->inscr) && !isset($oGet->numpre)) {
     db_redireciona("db_erros.php?fechar=true&db_erro=Sem parâmetros para impressão");
   }

  if(isset($oGet->numcgm)) {
   	
 	$querystring   = "numcgm=$oGet->numcgm";
   	$sSqlInnerSusp = "";
    $sSqlWhereSusp = " arresusp.k00_numcgm = ".$oGet->numcgm;
	  
  }else if(isset($oGet->matric)){
		
    $querystring   = "matric=$oGet->matric";
	$sSqlInnerSusp = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre"; 
	$sSqlWhereSusp = " k00_matric = ".$oGet->matric;
	  
  }else if(isset($oGet->inscr)){
		
    $querystring   = "inscr=$oGet->inscr";
	$sSqlInnerSusp = " inner join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre"; 
	$sSqlWhereSusp = " k00_inscr = ".$oGet->inscr;
	  
  } else {
		
    $querystring   = "numpre=$oGet->numpre";
    $sSqlInnerSusp = "";   
    $sSqlWhereSusp = " arresusp.k00_numpre = ".$oGet->numpre;
      
  }

  $sSqlSusp  = " select distinct 																			  ";
  $sSqlSusp .= "        arrenumcgm.k00_numcgm, 	   															  ";
  $sSqlSusp .= "        arresusp.*, 		    															  ";
  $sSqlSusp .= "	    suspensao.*,		    															  ";
  $sSqlSusp .= "		k02_drecei,																		  	  ";
  $sSqlSusp .= "		k01_descr,																		  	  ";
  $sSqlSusp .= "		arrehist.k00_histtxt,															 	  ";
  $sSqlSusp .= "		arrehist.k00_dtoper as dtlhist,													 	  ";
  $sSqlSusp .= "		arrehist.k00_hora   as hrlhist,														  ";
  $sSqlSusp .= "	  	db_usuarios.login																	  ";
  $sSqlSusp .= "   from arresusp 																			  ";
  $sSqlSusp .= "	    inner join arrenumcgm  on arrenumcgm.k00_numpre	    = arresusp.k00_numpre		      ";
  $sSqlSusp .= "	    inner join suspensao   on suspensao.ar18_sequencial = arresusp.k00_suspensao	      ";
  $sSqlSusp .= "	 	inner join db_usuarios on db_usuarios.id_usuario    = suspensao.ar18_usuario          ";
  $sSqlSusp .= "	 	inner join arreinstit  on arreinstit.k00_numpre     = arresusp.k00_numpre		      ";
  $sSqlSusp .= "	    				      and arreinstit.k00_instit     = ".db_getsession('DB_instit')."  ";
  $sSqlSusp .= "	    left  join arrehist    on arrehist.k00_numpre		= arresusp.k00_numpre			  "; 
  $sSqlSusp .= "                              and (    arresusp.k00_numpar  = arrehist.k00_numpar 			  ";
  $sSqlSusp .= "									or arrehist.k00_numpar  = 0)			  				  ";
  $sSqlSusp .= "		{$sSqlInnerSusp}																 	  "; 
  $sSqlSusp .= "		inner join tabrec      on arresusp.k00_receit       = k02_codigo				 	  ";
  $sSqlSusp .= "     	inner join tabrecjm    on tabrecjm.k02_codjm        = tabrec.k02_codjm			  	  ";
  $sSqlSusp .= "		inner join histcalc    on arresusp.k00_hist 	    = k01_codigo 					  ";
  $sSqlSusp .= "  where {$sSqlWhereSusp}																 	  ";
  $sSqlSusp .= "  order by arresusp.k00_numpre,															 	  ";
  $sSqlSusp .= "  	       arresusp.k00_numpar															 	  ";
  
  $rsDebitosSuspensos = pg_query($sSqlSusp);
  $iLinhasDebitosSusp = pg_num_rows($rsDebitosSuspensos);


  if($iLinhasDebitosSusp == 0){
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
  } 

  $oDebitosSusp = db_utils::fieldsMemory($rsDebitosSuspensos,0);
  $rsCgm	    = $clcgm->sql_record($clcgm->sql_query_file($oDebitosSusp->k00_numcgm,"z01_nome,z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident,z01_numero"));
  
  if($clcgm->numrows == 0){
  	
    db_redireciona("db_erros.php?fechar=true&db_erro=CGM não encontrado");
    
  }else{
  	
    $oCgm  = db_utils::fieldsMemory($rsCgm,0);
    $head2 = "SUSPENSÃO DE DÉBITOS";
  
  	if(isset($oGet->dataini)){
      $head3 = "Período entre ".db_formatar($oGet->dataini,'d')." e ".db_formatar($oGet->datafim,'d');
  	}
  	
    $head5 = $oDebitosSusp->k00_numcgm." - ".$oCgm->z01_nome;
    $head6 = $oCgm->z01_ender.", ".$oCgm->z01_numero;
    $head7 = $oCgm->z01_munic." / ".$oCgm->z01_uf;

  }


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(215);
  
$nTotal = 0;
$troca  = 1;
$p 	    = 1;
$alt    = 4;

for($cont=0; $cont < $iLinhasDebitosSusp; $cont++){

  $oDebitosSusp = db_utils::fieldsMemory($rsDebitosSuspensos,$cont);
  
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
  	
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    
    $pdf->cell(15,$alt,"NUMPRE"	   ,1,0,"C",1);
    $pdf->cell(15,$alt,"PAR."	   ,1,0,"C",1);
    $pdf->cell(15,$alt,"TOT."	   ,1,0,"C",1);
    $pdf->cell(15,$alt,"VENC."	   ,1,0,"C",1);
    $pdf->cell(15,$alt,"HIST."	   ,1,0,"C",1);
    $pdf->cell(70,$alt,"DESCRIÇÃO" ,1,0,"C",1);
    $pdf->cell(15,$alt,"REC."	   ,1,0,"C",1);
    $pdf->cell(70,$alt,"DESCRIÇÃO" ,1,0,"C",1);	
    $pdf->cell(15,$alt,"VALOR"	   ,1,0,"C",1);
    $pdf->cell(35,$alt,"USUÁRIO"   ,1,1,"C",1);
    $pdf->cell(280,$alt,"HISTÓRICO",1,1,"C",1);
    $pdf->cell(280,1,""			   ,0,1,"C",0);
    
    $troca = 0;
    
  }
  
  
  $pdf->setfont('arial','',6);
	if($cont % 2 == 0){
    $corfundo = 236;
  }else{
    $corfundo = 245;
  }
  
  $pdf->SetFillColor($corfundo);

  $pdf->cell(15,$alt,$oDebitosSusp->k00_numpre,"0",0,"C",1);
  $pdf->cell(15,$alt,$oDebitosSusp->k00_numpar,"0",0,"C",1);
  $pdf->cell(15,$alt,$oDebitosSusp->k00_numtot,"0",0,"C",1);
  $pdf->cell(15,$alt,db_formatar($oDebitosSusp->k00_dtvenc,"d"),"0",0,"C",1);
  $pdf->cell(15,$alt,$oDebitosSusp->k00_hist  ,"0",0,"C",1);
  $pdf->cell(70,$alt,$oDebitosSusp->k01_descr ,"0",0,"L",1);
  $pdf->cell(15,$alt,$oDebitosSusp->k00_receit,"0",0,"C",1);
  $pdf->cell(70,$alt,$oDebitosSusp->k02_drecei,"0",0,"L",1);    
  $pdf->cell(15,$alt,db_formatar(($oDebitosSusp->k00_valor*-1),"f"),"0",0,"R",1);
  $pdf->cell(35,$alt,$oDebitosSusp->login,"0",1,"L",1);
  $pdf->multicell(280,$alt,db_formatar($oDebitosSusp->dtlhist,"d") . " (" .$oDebitosSusp->hrlhist. ") - " . $oDebitosSusp->k00_histtxt,0,"J",1);
  $nTotal += ($oDebitosSusp->k00_valor*-1);
}

$pdf->setfont('arial','b',8);
$pdf->cell(210,$alt,'VALOR TOTAL',"T",0,"L",0);
$pdf->cell(35,$alt,db_formatar($nTotal,"f"),"T",0,"R",0);
$pdf->cell(35,$alt,"","T",0,"L",0);
$pdf->Output();
?>