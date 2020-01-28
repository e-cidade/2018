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
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("dbforms/db_funcoes.php");

$classinatura = new cl_assinatura;

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELATÓRIO DOS ELEMENTOS DA DESPESA (ANEXO 4)";
$head4 = "SEGUNDO A CATEGORIA ECONÔMICA";
$head5 = "ANEXO (4) EXERCÍCIO: ".db_getsession("DB_anousu");

$anousu = db_getsession("DB_anousu");
/*
$sql = "
        select * 
	from orcelemento 
	order by o56_elemento
       ";
//echo $sql ; exit;
*/
// $result = pg_exec($sql);

$result = db_elementosaldo(0,2,'',db_getsession("DB_anousu"),null,null,false);
//db_criatabela($result);exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$aux = 0;
$col = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);  
   $aux = $elemento;
   if (substr($aux,1,12) == "000000000000")
      continue;
   elseif (substr($aux,1,11) == "00000000000")
      $col = 1;
   elseif (substr($aux,2,10) == "0000000000")
      $col = 2;
   elseif (substr($aux,3,9) == "000000000")
      $col = 3;
   elseif (substr($aux,4,8) == "00000000")
      $col = 4;
   elseif (substr($aux,6,6) == "000000")
      $col = 5;
   elseif (substr($aux,8,4) == "0000")
      $col = 6;
   elseif (substr($aux,10,2) == "00")
      $col = 7;
   else
      $col = 8;
   
   $pdf->setfont('arial','',7);
   $pdf->cell($col,$alt,db_formatar($aux,'elemento'),0,0,"L",0);
   $pdf->cell($col+20,$alt,'',0,0,"L",0);
   $pdf->cell($col,$alt,$descr,0,1,"L",0);
}

$pdf->ln(14);

// assinaturas(&$pdf,&$classinatura,'BG');


$pdf->Output();
   
?>