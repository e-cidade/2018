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
include("classes/db_empnota_classe.php");

$clempnota = new cl_empnota;

$clrotulo = new rotulocampo;
$clrotulo->label('e69_codnota');
$clrotulo->label('e69_numero');
$clrotulo->label('e60_codemp');
$clrotulo->label('e70_valor');
$clrotulo->label('e70_vlrliq');
$clrotulo->label('e70_vlranu');
$clrotulo->label('e69_dtnota');
$clrotulo->label('e69_dtrecebe');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
// db_postmemory($HTTP_SERVER_VARS,2);exit;


$where = "";
if ($codigos!=""){
    $where = " where e60_numcgm  in ($codigos)";
}
if (strlen($data_ini) > 5 && strlen($data_fin)>5){
   if ($where !="")
      $where .=" and e60_emiss between '$data_ini' and '$data_fin'";
   else
      $where .=" where e60_emiss between '$data_ini' and '$data_fin'";
}  


$sql ="
       select  e60_numemp,
               e60_codemp,
	       e60_anousu,
	       e60_emiss,
	       e60_numcgm,
	       z01_nome,
	       e69_numero,
	       e69_dtnota,
	       e70_valor,
	       e70_vlranu, 
	       e70_vlrliq,
	       e71_codord,
	       e53_valor,
	       e53_vlranu,
	       e53_vlrpag
       from empempenho
 	     inner join cgm on z01_numcgm  = e60_numcgm
             inner join empnota on e69_numemp=e60_numemp
	     inner join empnotaele on e70_codnota = e69_codnota 
             left  join pagordemnota on e71_codnota = e70_codnota
	     left  join pagordem on e50_codord=e71_codord and e50_numemp=e60_numemp
             left  join pagordemele on e53_codord= e50_codord
 
        $where

        /* alterar a ordem abaixo pode duplicar dados */
	order by e60_numemp,e69_codnota,e71_codord

	limit $limite
 
      ";

$result =  pg_query($sql);
// db_criatabela($result); exit;

// --------------------------------------------------------------

$head3 = "RELATÓRIO DE NOTAS";
$head5 = "ORDEM EMPENHO,NOTA,OP";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);

$pdf->setfont('arial','',8);

$troca   = 0;

$prenc   = 0;
$alt     = 4;
$total   = 0;
$cod_cgm = 0;
$cod_op  = 0;

$tnota_vlr =  0;
$tnota_anu =  0;
$tnota_liq =  0;
$tpag_vlr  =  0;
$tpag_anu  =  0;
$tpag_pag  =  0;


for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);

   // codigo não repete o cgm
   if ($cod_cgm != $e60_numemp  ){

      $cod_cgm =  $e60_numemp;

      if ($troca==0){
 	 $pdf->addpage("L");
 	 $troca = 1;     

         $pdf->cell(20,$alt+2,"EMPENHO",'T',0,"L",1);   
         $pdf->cell(215,$alt+2,"CREDOR (CGM-NOME)",'T',1,"L",1);
	
      } else {
	  $pdf->setX(85);	  
	  $pdf->cell(20,$alt,"SUBTOTAL",'T',0,"R",0); 
          $pdf->cell(20,$alt,db_formatar($tnota_vlr,'f'),'T',0,"R",0); 
	  $pdf->cell(20,$alt,db_formatar($tnota_anu,'f'),'T',0,"R",0); 
	  $pdf->cell(20,$alt,db_formatar($tnota_liq,'f'),'T',0,"R",0); 
      	  $pdf->cell(20,$alt," - ",'T',0,"C",0); 
	  $pdf->cell(20,$alt,db_formatar($tpag_vlr,'f'),'T',0,"R",0); 
	  $pdf->cell(20,$alt,db_formatar($tpag_anu,'f'),'T',0,"R",0); 
	  $pdf->cell(20,$alt,db_formatar($tpag_pag,'f'),'T',1,"R",0);   	 

	  $tnota_vlr =  0;
	  $tnota_anu =  0;
	  $tnota_liq =  0;
	  $tpag_vlr  =  0;
	  $tpag_anu  =  0;
	  $tpag_pag  =  0;


          $pdf->Ln(2);
      }	
      

      $pdf->cell(20,$alt,$e60_codemp."/".$e60_anousu,'T',0,"L",0);   
      $pdf->cell(215,$alt,$e60_numcgm."- ".$z01_nome,'T',1,"L",0);

          
      $pdf->setX(40);  
      $pdf->cell(25,$alt,"DATA",'B',0,"R",0); 
      $pdf->cell(40,$alt,"NOTA",'B',0,"L",0); 
      $pdf->cell(20,$alt,"VALOR",'B',0,"R",0); 
      $pdf->cell(20,$alt,"ANULADO",'B',0,"R",0); 
      $pdf->cell(20,$alt,"LIQUIDADO",'B',0,"R",0); 
      
      $pdf->cell(20,$alt,"OP",'B',0,"C",0); 
      $pdf->cell(20,$alt,"VALOR",'B',0,"R",0); 
      $pdf->cell(20,$alt,"ANULADO",'B',0,"R",0); 
      $pdf->cell(20,$alt,"PAGO",'B',0,"R",0);   

      $pdf->Ln(6);   
      
   }
   
   
   $pdf->setX(40);  
   $pdf->cell(25,$alt,$e69_dtnota,0,0,"R",0); 
   $pdf->cell(40,$alt,$e69_numero,0,0,"L",0); 
   $pdf->cell(20,$alt,db_formatar($e70_valor,"f"),0,0,"R",0); 
   $pdf->cell(20,$alt,db_formatar($e70_vlranu,"f"),0,0,"R",0); 
   $pdf->cell(20,$alt,db_formatar($e70_vlrliq,"f"),0,0,"R",0);

   if ($cod_op == $e71_codord) { // imprime op somente uma vez 
       $pdf->cell(20,$alt,$e71_codord,0,0,"C",0); 
       $pdf->cell(20,$alt," - ",0,0,"R",0); 
       $pdf->cell(20,$alt," - ",0,0,"R",0); 
       $pdf->cell(20,$alt," - ",0,0,"R",0);

   } else {  
       $pdf->cell(20,$alt,$e71_codord,0,0,"C",0); 
       $pdf->cell(20,$alt,db_formatar($e53_valor,"f"),0,0,"R",0); 
       $pdf->cell(20,$alt,db_formatar($e53_vlranu,"f"),0,0,"R",0); 
       $pdf->cell(20,$alt,db_formatar($e53_vlrpag,"f"),0,0,"R",0);
       // atualiza o numero da op na variavel
       $cod_op = $e71_codord; 
       
       // somador dos pagamentos         
       $tpag_vlr  += $e53_valor;
       $tpag_anu  += $e53_vlranu;
       $tpag_pag  += $e53_vlrpag;

   }

   // somador das notas
   $tnota_vlr += $e70_valor;
   $tnota_anu += $e70_vlranu;
   $tnota_liq += $e70_vlrliq;
   
   
   $pdf->Ln();
   $total++;

}

 $pdf->setX(85);
 $pdf->cell(20,$alt,"SUBTOTAL",'TB',0,"R",0); 
 $pdf->cell(20,$alt,db_formatar($tnota_vlr,'f'),'TB',0,"R",0); 
 $pdf->cell(20,$alt,db_formatar($tnota_anu,'f'),'TB',0,"R",0); 
 $pdf->cell(20,$alt,db_formatar($tnota_liq,'f'),'TB',0,"R",0); 
 $pdf->cell(20,$alt," - ",'TB',0,"C",0); 
 $pdf->cell(20,$alt,db_formatar($tpag_vlr,'f'),'TB',0,"R",0); 
 $pdf->cell(20,$alt,db_formatar($tpag_anu,'f'),'TB',0,"R",0); 
 $pdf->cell(20,$alt,db_formatar($tpag_pag,'f'),'TB',1,"R",0);   	 

$pdf->cell(185,$alt,'TOTAL DE REGISTROS:  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>