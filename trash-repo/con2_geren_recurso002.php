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
include("libs/db_libcontabilidade.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_selinstit = "1";
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
   db_fieldsmemory($resultinst,$xins);
   $descr_inst .= $xvirg.$nomeinst ;
   $xvirg = ', ';
}


$head2 = "SALDO CONTÁBIL";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head4 = "POSIÇÃO : ".db_formatar(date("Y-m-d",db_getsession("DB_datausu")),'d');

$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).")  and c61_codigo = 1010";

$result = db_planocontassaldo(db_getsession("DB_anousu"),db_getsession("DB_anousu")."-01-01",date("Y-m-d",db_getsession("DB_datausu")),false,$where);

db_criatabela($result);exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pdf->ln(2);

//db_fieldsmemory($


$pdf->cell(25,"TESTE",'',0,0,"L",0,0,'.'); 




$pdf->Output();
   
?>