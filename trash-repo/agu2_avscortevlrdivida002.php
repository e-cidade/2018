<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label("x40_codcorte");
$clrotulo->label("x44_vlrhis");
$clrotulo->label("x44_vlrcor");
$clrotulo->label("k02_descr");

$oGet = db_utils::postMemory($_GET);

$oPdf = new PDF();

if(isset($oGet->corte) && $oGet->corte != '') {
  
  
  if(isset($oGet->ordem)) {
    
    if($oGet->ordem == 'corte') {
      $sOrderBy = 'x40_codcorte';
    }else if($oGet->ordem == 'historico') {
      $sOrderBy = 'x44_vlrhis';  
    }else if($oGet->ordem == 'corrigido') {
      $sOrderBy = 'x44_vlrcor';        
    }else if($oGet->ordem == 'tipo') {
      $sOrderBy = 'k02_descr';
    }
    
  }
  
  $sSqlAguaCorte = "select x40_codcorte, k02_descr, ROUND(SUM(x44_vlrhis),2) as x44_vlrhis, ROUND(SUM(x44_vlrcor),2 ) as x44_vlrcor
                      from aguacorte
                     inner join aguacortemat       on x41_codcorte    = x40_codcorte
                     inner join aguacortematnumpre on x44_codcortemat = x41_codcortemat
                     inner join tabrec             on x44_receit      = k02_codigo
                     where x40_codcorte = $oGet->corte
                     group by X40_codcorte, k02_descr 
                     order by $sOrderBy"; 
  
  $rSqlAguaCorte = pg_query($sSqlAguaCorte);
  
  
	$head2 = "Lista de Corte Nro: $oGet->corte";
  
  if(pg_num_rows($rSqlAguaCorte) == 0) {
    
    db_redireciona('db_erros?fechar=true&db_erro=Nenhum resultado encontrado.');
    
  }
  
  $oPdf->Open();
  $oPdf->AliasNBPages();
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial','b',8);
  $oPdf->DefOrientation = "L";

  $troca = 1;
  $alt   = 4;
  $total = 0;
  
  for($i = 0; $i < pg_num_rows($rSqlAguaCorte); $i++) {
    
    $oAguaCorte = db_utils::fieldsMemory($rSqlAguaCorte, $i);
    
    if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
	    $oPdf->addpage();
	    $oPdf->setfont('arial','b',8);
	    $oPdf->cell(50 , $alt, $RLx40_codcorte, 1, 0, "C", 1);
	    $oPdf->cell(60 , $alt, $RLx44_vlrhis  , 1, 0, "C", 1); 
	    $oPdf->cell(60 , $alt, $RLx44_vlrcor  , 1, 0, "C", 1);
	    $oPdf->cell(110, $alt, $RLk02_descr   , 1, 1, "C", 1); 
	    $troca = 0;
	    $p=0;
    }
  
    $oPdf->setfont('arial','',7);
    
    $oPdf->cell(50, $alt, $oAguaCorte->x40_codcorte, 0, 0, "C", $p);
	  $oPdf->cell(60, $alt, $oAguaCorte->x44_vlrhis  , 0, 0, "C", $p);
	  $oPdf->cell(60, $alt, $oAguaCorte->x44_vlrcor  , 0, 0, "C", $p);
	  $oPdf->cell(110, $alt, $oAguaCorte->k02_descr   , 0, 1, "L", $p);
	   
    if($p == 0) 
      $p = 1;
    else 
      $p = 0;
   
    $total++;
            
  }
  
  $oPdf->setfont('arial','b',8);
  $oPdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
  $oPdf->Output();  
  
}