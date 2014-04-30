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
include("classes/db_auto_classe.php");

$clauto = new cl_auto;
$clauto2 = new cl_auto;
$clrotulo = new rotulocampo;
$clrotulo->label('y50_codauto');
$clrotulo->label('y50_data');
$clrotulo->label('y50_prazorec');
$clrotulo->label('y50_dtvenc');
$clrotulo->label('y27_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$whereSetor = " and y50_instit = ".db_getsession('DB_instit') ;
if ($setorfiscal != 0) {
  $whereSetor .= " AND y50_setor = $setorfiscal";
}

if (($dt_prazo != "--")){
	$result = $clauto->sql_record($clauto->sql_query(null,"*",null,"y50_data between '$dt_ini' and '$dt_fin' and y50_prazorec <= '$dt_prazo'"."$whereSetor"));
} else {
	$result = $clauto->sql_record($clauto->sql_query(null,"*",null,"y50_data between '$dt_ini' and '$dt_fin'"."$whereSetor"));
}

if ($clauto->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrado registros correspondentes.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;

/*Definindo cabeçalhos*/
$head3 = "Relatório de Autos de Infração por Prazo";
if ($dt_ini != "--") $head7 = "Período = ".db_formatar($dt_ini,'d')." à ".db_formatar($dt_fin,'d')."";
if ($dt_prazo != "--") $head5 = "Prazo Recurso = ".db_formatar($dt_prazo,'d')."";

$pdf->setfillcolor(235);
$pdf->setfont('arial','b',6);
$troca = 1;
$alt = 4;

$numRowsResult = $clauto->numrows;

for($x = 0; $x < $numRowsResult;$x++){

   db_fieldsmemory($result,$x);
   $result2 = $clauto->sql_record($clauto->sql_query_busca2($y50_codauto,"dl_Auto = $y50_codauto and y50_instit = ".db_getsession('DB_instit')));
   $numRowsResult2 = $clauto->numrows;

   for ($j = 0; $j < $numRowsResult2;$j++){
      db_fieldsmemory($result2,$j);
   }

   /*Definindo cabeçalho 1*/
   if ($setorfiscal == 0) $head1 = "RELATÓRIO GERAL";

   /*Atribuinto valor do auto caso calculado*/
   $resultValor = db_query("SELECT  k00_valor
                                    FROM arrecad inner join autonumpre
				    ON y17_numpre = k00_numpre and y17_codauto = $y50_codauto");

   $valorAuto = pg_fetch_array($resultValor);

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',6);
      $pdf->cell(15,$alt,"AUTO INF.",1,0,"C",1);
      $pdf->cell(25,$alt,"IDENTIFICAÇÃO",1,0,"C",1);
      $pdf->cell(15,$alt,"CÓDIGO",1,0,"C",1);
      $pdf->cell(60,$alt,"CONTRIBUINTE",1,0,"C",1);
      $pdf->cell(20,$alt,"DT AUTO",1,0,"C",1);
      $pdf->cell(20,$alt,"DT RECURSO",1,0,"C",1);
      $pdf->cell(20,$alt,"DT VENCTO",1,0,"C",1);
      $pdf->cell(50,$alt," FISCAL RESPONSÁVEL",1,0,"C",1);
      $pdf->cell(40,$alt,$RLy27_descr,1,0,"C",1);
      $pdf->cell(15,$alt,"VALOR",1,1,"C",1);
      $troca = 0;
   }

   if (!empty($valorAuto[0])) $valorAuto[0] = number_format("$valorAuto[0]",2,",",".");

   $pdf->setfont('arial','',6);
   $pdf->cell(15,$alt,@$y50_codauto,0,0,"C",0);
   $pdf->cell(25,$alt,@$dl_identifica,0,0,"C",0);
   $pdf->cell(15,$alt,@$dl_codigo,0,0,"C",0);
   $pdf->cell(60,$alt,@$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($y50_data,'d'),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($y50_prazorec,'d'),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($y50_dtvenc,'d'),0,0,"C",0);
   $pdf->cell(50,$alt,$nomeresponsavel,0,0,"L",0);
   $pdf->cell(40,$alt,$y27_descr,0,0,"L",0);
   $pdf->cell(15,$alt,@$valorAuto[0],0,1,"R",0);
 }

$pdf->Output();

?>