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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
include("fpdf151/pdf.php");

/////////////////////////////////
// tipos/elementos
/////////////////////////////////
$sql="  select 
               empresto.*,
               e90_descr
        from empresto
            left outer join emprestotipo  on e91_codtipo = e90_codigo
        order by e91_codtipo,e91_elemento
	
      ";

$result = pg_exec($sql);
if (pg_numrows($result) == 0 ){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
} 

//////////////////////////////////
//////////////////////////////////////////////////////////////

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$pdf->setfillcolor(235);
$pdf->setfont('arial','',9);
$alt = 4;


$codtipo="";
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i );
  	
   if($pdf->gety()>$pdf->h-30 ){
        $pdf->addpage();
        $pdf->setfont('arial','',9);
        $imprime=true; 

   } 

   if ($codtipo != $e91_codtipo){
      $codtipo=$e91_codtipo; 
      $pdf->setX(20);
      $pdf->cell(30,$alt,"$e91_codtipo",'B',0,"L",0);    
      $pdf->cell(50,$alt,"$e90_descr",'B',1,"L",0);    
       //
      $pdf->setX(40);
      $pdf->cell(30,$alt,"ELEMENTO",'B',0,"L",0);
      $pdf->cell(20,$alt,"NUM.EMP",'B',0,"R",0);
      $pdf->cell(20,$alt,"VLR.EMP",'B',0,"R",0);
      $pdf->cell(20,$alt,"VLR.ANU",'B',0,"R",0);
      $pdf->cell(20,$alt,"VLR.LIQ",'B',0,"R",0);
      $pdf->cell(20,$alt,"VLR.PAG",'B',0,"R",0);
      $pdf->cell(20,$alt,"RECURSO",'B',1,"R",0);
   }  
   $pdf->setX(40);
   $pdf->cell(30,$alt,"$e91_elemento",0,0,"L",0);
   $pdf->cell(20,$alt,"$e91_numemp",0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($e91_vlremp,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($e91_vlranu,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($e91_vlrliq,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($e91_vlrpag,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,"$e91_recurso",0,1,"R",0);

}




$pdf->Output();


?>