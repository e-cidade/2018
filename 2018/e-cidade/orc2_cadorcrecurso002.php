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
include("classes/db_orctiporec_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oGet     = db_utils::postMemory($_GET,0);
$sRecurso = "";
$dbwhere  = "";

$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label();

if (isset($oGet->recurso) && $oGet->recurso != "") {
	 if ($oGet->recurso == 't') {
	 	  $sRecurso = "RECURSO: Todos";
	 } else if ($oGet->recurso == 'sa') {
	 	  $sRecurso = "RECURSO: Somente Ativos";
	 	  $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
	 } else if ($oGet->recurso == 'si') {
	 	  $sRecurso = "RECURSO: Somente Inativos";
	 	  $dbwhere = " o15_datalimite is not null or o15_datalimite < '".date('Y-m-d',db_getsession('DB_datausu'))."'";
	 }
}

$result  = $clorctiporec->sql_record($clorctiporec->sql_query(null,"*","o15_codigo",$dbwhere));

$head3 = "RELATÓRIO DE RECURSOS VINCULADOS";
$head5 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head7 = $sRecurso;

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem recursos cadastradas.');
   exit;
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLo15_codigo,1,0,"C",1);
      $pdf->cell(64,$alt,$RLo15_descr,1,0,"C",1);
      $pdf->cell(20,$alt,"Validade",1,0,"C",1);
      $pdf->cell(90,$alt,$RLo15_finali,1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,db_formatar($o15_codigo,'recurso'),0,0,"C",0);
   $pdf->cell(64,$alt,substr($o15_descr,0,42),0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($o15_datalimite,'d'),0,0,"C",0);
   $pdf->multicell(90,$alt,$o15_finali);
}
$pdf->Output();
?>