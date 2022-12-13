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
//include("classes/db_listainscrcab_classe.php");
//include("classes/db_listainscr_classe.php");

//$cllistainscrcab= new cl_listainscrcab;
//$cllistainscr = new cl_listainscr;

$clrotulo = new rotulocampo;
$clrotulo->label("p11_codigo    " );
$clrotulo->label("p11_numcgm    " );
$clrotulo->label("z01_nome      " );
$clrotulo->label("p11_data      " );
$clrotulo->label("p11_hora      " );
$clrotulo->label("p11_fechado   " );
$clrotulo->label("p11_processado" );
$clrotulo->label("p11_contato   " );
$clrotulo->label("p12_codigo    " );
$clrotulo->label("p12_inscr     " );
$clrotulo->label("p12_cnpj      " );
$clrotulo->label("p12_fone      " );

//$cllistainscrcab->rotulo->label();
//$cllistainscr->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$dt="";
$dt1="";
$dat="";
$p="";
$f="";
$where = "1=1";
if (($fechadas=="t")&&($processadas=="t")){
  $p="";
  $f="TODAS AS LISTAS";
}
if ($fechadas=="s"){
  $where = "p11_fechado = true";
  $f="LISTAS CONCLUIDAS SIM";
 }else if ($fechadas=="n"){
   $where = "p11_fechado = false";
   $f="LISTA CONCLUIDAS NÃO";
  }

if ($processadas=="s"){
  $where .= "and p11_processado = true";
  $p="LISTAS PROCESSADAS SIM";
 }else if ($processadas=="n"){
   $where .= "and p11_fprocessado = false";
   $p="LISTAS PROCESSADAS NÃO";
  }

if (($data!="--")&&($data1!="--")) {
  $where .="and  p11_data between '$data' and '$data1'";
  $dt=db_formatar($data,'d');
  $dt1=db_formatar($data1,'d');
  $dat="DE $dt ATE $dt1 ";
  }else if ($data!="--"){
     $where.="and p11_data >= '$data'";
     $dt=db_formatar($data,'d');
     $dat="APARTIR DE $dt";
     }else if ($data1!="--"){
        $where.="and p11_data <= '$data1'";
        $dt1=db_formatar($data1,'d');
	$dat="ATE $dt1";
        }

$sql = "select * from listainscrcab inner join cgm on p11_numcgm = z01_numcgm  where $where";


$result = pg_exec($sql);

if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');

}
      
$head3 = "LISTA DE INCRIÇÕES PELA WEB ";
$head4=  $f;
$head5=  $p; 
$head6=  $dat;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$qln = 0;
for($x = 0; $x < pg_numrows($result) ;$x++){
   db_fieldsmemory($result,$x,true);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Código"    ,1,0,"C",1);
      $pdf->cell(15,$alt,$RLp11_numcgm    ,1,0,"C",1); 
      $pdf->cell(65,$alt,$RLz01_nome      ,1,0,"C",1); 
      $pdf->cell(15,$alt,"Data"      ,1,0,"C",1); 
      $pdf->cell(15,$alt,"Hora"      ,1,0,"C",1); 
      $pdf->cell(18,$alt,"Concluidas"   ,1,0,"C",1); 
      $pdf->cell(20,$alt,"Processadas",1,0,"C",1); 
      $pdf->cell(55,$alt,$RLp11_contato   ,1,0,"C",1); 
      $pdf->cell(15,$alt,$RLp12_inscr     ,1,0,"C",1);
      $pdf->cell(25,$alt,$RLp12_cnpj      ,1,0,"C",1);
      $pdf->cell(15,$alt,$RLp12_fone      ,1,1,"C",1);
      $separa=1;
      $troca = 0;
   }
   if($p11_fechado=="t"){
     $fechado="Sim";
   }else $fechado="Não";
   if($p11_processado=="t"){
     $processado="Sim";
   }else $processado="Não";
   if ($separa!=1){
    $pdf->cell(271,$alt,"","T",1,"L",0);
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$p11_codigo    ,0,0,"C",0);
   $pdf->cell(15,$alt,$p11_numcgm    ,0,0,"C",0);
   $pdf->cell(65,$alt,$z01_nome      ,0,0,"L",0);
   $pdf->cell(15,$alt,$p11_data      ,0,0,"C",0);
   $pdf->cell(15,$alt,$p11_hora      ,0,0,"C",0);
   $pdf->cell(18,$alt,$fechado   ,0,0,"C",0);
   $pdf->cell(20,$alt,$processado,0,0,"C",0);
   $separa=0;
   $sql2 = "select * from listainscr  where p12_codigo = $p11_codigo "; 
   $result2 = pg_exec($sql2);  
   if (pg_numrows($result2)==0){
     $qln=1;
   }else $qln=0;
   $pdf->cell(55,$alt,$p11_contato   ,0,$qln,"L",0);
   $total++;
   $numrows=pg_numrows($result2) - 1;
   for($i = 0; $i < pg_numrows($result2) ;$i++){
     db_fieldsmemory($result2,$i);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
       $pdf->addpage("L");
       $pdf->setfont('arial','b',8);
       $pdf->cell(15,$alt,"Código"    ,1,0,"C",1);
       $pdf->cell(15,$alt,$RLp11_numcgm    ,1,0,"C",1); 
       $pdf->cell(65,$alt,$RLz01_nome      ,1,0,"C",1); 
       $pdf->cell(15,$alt,"Data"      ,1,0,"C",1); 
       $pdf->cell(15,$alt,"Hora"      ,1,0,"C",1); 
       $pdf->cell(18,$alt,"Concluidas"   ,1,0,"C",1); 
       $pdf->cell(20,$alt,"Processadas",1,0,"C",1); 
       $pdf->cell(55,$alt,$RLp11_contato   ,1,0,"C",1); 
       $pdf->cell(15,$alt,$RLp12_inscr     ,1,0,"C",1);
       $pdf->cell(25,$alt,$RLp12_cnpj      ,1,0,"C",1);
       $pdf->cell(15,$alt,$RLp12_fone      ,1,1,"C",1);
       $pdf->cell(218,$alt,"",0,0,"C",0);
       $troca = 0;
     }
     $pdf->setfont('arial','',7);
     
     $pdf->cell(15,$alt,$p12_inscr    ,0,0,"C",0);
     $pdf->cell(25,$alt,$p12_cnpj     ,0,0,"C",0);
     $pdf->cell(15,$alt,$p12_fone      ,0,1,"C",0);
     if ($i<$numrows){
       $pdf->cell(218,$alt,"",0,0,"C",0);
     }
   }
}

$pdf->setfont('arial','b',8);
$pdf->cell(271,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>