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

include("classes/db_orcreserprev_classe.php");
// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
 
$head2 = "PROGRAMAÇÃO DE BLOQUEIO DE DESPESA";
$head4 = "EXERCICIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".db_getsession("DB_instit");


$clorcreserprev = new cl_orcreserprev;

$result = $clorcreserprev->sql_reserva_prev(false,$atividade);
if($result==false || pg_numrows($result)==0){
  db_redireciona("db_erro.php?fechar=true&db_erro='Não há dados a serem impressos'");	
  exit;
}
//db_criatabela($result);exit;

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
    $pdf->setfont('arial','',7);

  }
    $pdf->cell(10,$alt,"Ativid.",1,0,"L",0);
    $pdf->cell(60,$alt,"Descrição",1,0,"L",0);
    $pdf->cell(15,$alt,"Recurso",1,0,"R",0);
    $pdf->cell(60,$alt,"Descrição",1,0,"L",0);
    $pdf->cell(20,$alt,"Orçado",1,0,"L",0);
    $pdf->cell(20,$alt,"Saldo",1,0,"L",0);
    $pdf->cell(1,$alt,"",0,1,"L",0);

    $pdf->cell(10,$alt,$o58_projativ,0,0,"L",0);
    $pdf->cell(60,$alt,$o55_descr,0,0,"L",0);
    $pdf->cell(15,$alt,$o58_codigo,0,0,"L",0);
    $pdf->cell(60,$alt,$o15_descr,0,0,"L",0);
    $pdf->cell(20,$alt,db_formatar($o58_valor,'f'),0,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
    $pdf->cell(1,$alt,"",0,1,"L",0);

    for($x=1;$x<7;$x++){

      $resultprev = $clorcreserprev->sql_record($clorcreserprev->sql_query(db_getsession("DB_anousu"),$o58_projativ,$o58_codigo,$x,'o33_perc'));
      if($clorcreserprev->numrows >0){
      	db_fieldsmemory($resultprev,0);
        $perc = $o33_perc; 	
        $valor = round($atual_menos_reservado * ($perc/100),2);
      }else{
      	$perc = 0;
        $valor = 0;
      }
   
      $pdf->cell(10,$alt,"",0,0,"R",0);
      $pdf->cell(20,$alt,db_mes($x,3),0,0,"R",0);
      $pdf->cell(10,$alt,$perc."%",0,0,"R",0);
      $pdf->cell(20,$alt,"Valor:",0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);

      $pdf->cell(10,$alt,"",0,0,"L",0);

      $resultprev = $clorcreserprev->sql_record($clorcreserprev->sql_query(db_getsession("DB_anousu"),$o58_projativ,$o58_codigo,$x+6,'o33_perc'));
      if($clorcreserprev->numrows >0){
      	db_fieldsmemory($resultprev,0);
        $perc = $o33_perc; 	
        $valor = round($atual_menos_reservado * ($perc/100),2);
      }else{
      	$perc = 0;
        $valor = 0;
      }
 
      $pdf->cell(10,$alt,"",0,0,"R",0);
      $pdf->cell(20,$alt,db_mes($x+6,3),0,0,"R",0);
      $pdf->cell(10,$alt,$perc."%",0,0,"R",0);
      $pdf->cell(20,$alt,"Valor:",0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);

      $pdf->cell(1,$alt,"",0,1,"L",0);
      
    }
     




}
$pdf->Output();

?>