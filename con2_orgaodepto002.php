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
include("classes/db_db_depart_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_depart = new cl_db_depart;

$cldb_depart->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('o40_descr');
$clrotulo->label('db01_orgao');
$clrotulo->label('');
$clrotulo->label('');
$clrotulo->label('');
$clrotulo->label('');

$erro=0;

$txt_where=" and 1=1";
if ($listaorg!=""){
  if (isset($verorg) and $verorg=="com"){
    $txt_where= $txt_where." and db01_orgao in  ($listaorg)";
  } else {
    $txt_where= $txt_where." and db01_orgao not in  ($listaorg)";
  }	 
}  
$sql = "select * 
          from ( select distinct
	                    db01_orgao, 
	                    db01_unidade,
                        o40_descr, 
	                    coddepto, 
	                    descrdepto, 
	                    nomeresponsavel, 
	                    emailresponsavel 
	               from db_depart 
                        inner join db_departorg on db01_coddepto = coddepto 
	                    inner join orcorgao     on o40_orgao = db01_orgao 
	                                           and o40_anousu = " . db_getsession("DB_anousu") . " 
	                                           and o40_instit = " . db_getsession("DB_instit") . "
                  where (limite is null or limite >= '" . date("y-m-d",db_getsession("DB_datausu")) . "') 
                    and db01_anousu = " . db_getsession("DB_anousu") . "$txt_where ) as x
         order by db01_orgao, db01_unidade";

$head3 = "Departamantos por Orgãos";

$result=pg_exec($sql);

if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$anterior="0";
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(100,$alt,$RLdb01_orgao,1,0,"C",1);
      $pdf->cell(160,$alt,$RLo40_descr,1,1,"C",1);
      $pdf->cell(20,$alt,$RLcoddepto,1,0,"C",1);
      $pdf->cell(80,$alt,$RLdescrdepto,1,0,"C",1);
      $pdf->cell(80,$alt,$RLnomeresponsavel,1,0,"C",1);
      $pdf->cell(80,$alt,$RLemailresponsavel,1,1,"C",1);
      $troca = 0;
   }
   if ($db01_orgao!=$anterior){
   $pdf->setfont('arial','b',8);
   $pdf->cell(100,$alt,$db01_orgao,0,0,"C",0);
   $pdf->cell(160,$alt,$o40_descr,0,1,"L",0);
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$coddepto,0,0,"C",0);
   $pdf->cell(80,$alt,$descrdepto,0,0,"L",0);
   $pdf->cell(80,$alt,$nomeresponsavel,0,0,"L",0);
   $pdf->cell(80,$alt,$emailresponsavel,0,1,"L",0);
   $anterior=$db01_orgao;
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(260,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>