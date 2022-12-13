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




$clrotulo = new rotulocampo;
$clrotulo->label('matricula');
$clrotulo->label('proprietario1');
$clrotulo->label('proprietario2');
$clrotulo->label('endereco');
$clrotulo->label('numero');
$clrotulo->label('complem');
$clrotulo->label('municipio');
$clrotulo->label('cep');
$clrotulo->label('uf');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

  
  $desc_ordem = "Por Matricula";
 


$head3 = "CARNES DE IPTU ";
$head5 = "ORDEM $desc_ordem";

$result = pg_query("select * from iptucarnes order by matricula "); 

if (pg_numrows($result) == 0){
     db_redireciona('db_erros.php?fechar=true&db_erro=Não existem carnes cadastrados.');
}
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca = 1;
  $alt = 4;$total = 0;
  $prenc = 0;
  for($x = 0; $x < pg_numrows($result);$x++){
       db_fieldsmemory($result,$x);
       if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
         $pdf->addpage('L');
         $pdf->setfont('arial','b',7);
         $pdf->cell(15,$alt,'Matricula',1,0,"C",1);
         $pdf->cell(60,$alt,'Proprietario1',1,0,"C",1);
         $pdf->cell(60,$alt,'Proprietario2',1,0,"C",1);
         $pdf->cell(45,$alt,'Endereço',1,0,"C",1);
         $pdf->cell(15,$alt,'Numero',1,0,"C",1);
         $pdf->cell(30,$alt,'Complem.',1,0,"C",1);
         $pdf->cell(30,$alt,'Municipio',1,0,"C",1);
         $pdf->cell(20,$alt,'Cep',1,0,"C",1);
         $pdf->cell(5,$alt,'UF',1,1,"C",1);
         
         
         
         
         
         
         
	 
	 

         $troca = 0;
         }
       $pdf->setfont('arial','',6);
       $pdf->cell(15,$alt,$matricula,0,0,"C",$prenc);
       $pdf->cell(60,$alt,$proprietario1,0,0,"L",$prenc);
       $pdf->cell(60,$alt,$proprietario2,0,0,"L",$prenc);
       $pdf->cell(45,$alt,$endereco,0,0,"L",$prenc);
       $pdf->cell(15,$alt,$numero,0,0,"L",$prenc);
       $pdf->cell(30,$alt,$complem,0,0,"L",$prenc);
       $pdf->cell(30,$alt,$municipio,0,0,"L",$prenc);
       $pdf->cell(20,$alt,$cep,0,0,"L",$prenc);
       $pdf->cell(5,$alt,$uf,0,1,"C",$prenc);
       $total++;
  if ($prenc == 0){
  $prenc = 1;
  }else $prenc = 0;
  
  }

  $pdf->cell(280,$alt,"TOTAL DE REGISTROS  :".$total,"T",0,"L",0);
  $pdf->Output();

  ?>