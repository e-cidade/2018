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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

include("classes/db_orcfontes_classe.php");

$clorcfontes = new cl_orcfontes;
$clorcfontes->rotulo->label();

$result = $clorcfontes->sql_record($clorcfontes->sql_query(null,db_getsession("DB_anousu"),"*",'o57_fonte'));

$head3 = "RELATÓRIO DE FONTES DA RECEITA";
$head5 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem itens cadastrados : fontes da receita.');
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
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLo57_codfon,1,0,"C",1);
      $pdf->cell(40,$alt,$RLo57_fonte,1,0,"L",1);
      $pdf->cell(100,$alt,$RLo57_descr,1,0,"L",1);
      $pdf->multicell(100,$alt,$RLo57_finali,1,"L",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$o57_codfon,0,0,"C",0);
   $pdf->cell(40,$alt,db_formatar($o57_fonte,'elemento_int').".00",0,0,"L",0);
   $pdf->cell(100,$alt,$o57_descr,0,0);
   $pdf->multicell(100,$alt,$o57_finali,0,'L');
}
$pdf->Output();
?>