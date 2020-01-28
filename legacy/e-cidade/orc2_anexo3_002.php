<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");

$classinatura = new cl_assinatura;

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head2 = "RELATÓRIO DAS FONTES DA RECEITA - ANEXO 3";
$head3 = "SEGUNDO A CATEGORIA ECONÔMICA";
$head4 = "ANEXO (3) EXERCÍCIO: ".db_getsession("DB_anousu");

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
     $descr_inst .= $xvirg.$nomeinstabrev;
     $xvirg = ', ';
}
$head5 = "INSTITUIÇÕES : ".$descr_inst;
//exit;

$anousu  = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu")."-01-01";
$datafin = db_getsession("DB_anousu")."-01-01";
$xinstit = str_replace('-',', ',$db_selinstit);
/*
$sql = "
        select * 
	from orcreceita
	     inner join orcfontes on o70_codfon = o57_codfon
	                          and o70_anousu = $anousu
				  and o70_instit in ($xinstit)
	order by o57_fonte
       ";
//echo $sql ; exit;
$result = db_query($sql);
*/
$instit = ' o70_instit in ('.$xinstit.')';

$result = db_receitasaldo(11,1,3,true,$instit,$anousu,$dataini,$datafin);


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
   $sQuery 	= "select o57_finali from orcfontes where o57_fonte = '$o57_fonte' and o57_anousu = ".db_getsession("DB_anousu");
   $rsQuery = db_query($sQuery);
   if(pg_num_rows($rsQuery) > 0){
   	  db_fieldsmemory($rsQuery,0);
   }
   $aux = $o57_fonte;
   if (substr($aux,1,14) == "00000000000000")
      continue;
   elseif (substr($aux,2,12) == "000000000000")
      $col = 1;
   elseif (substr($aux,3,12) == "000000000000")
      $col = 2;
   elseif (substr($aux,4,11) == "00000000000")
      $col = 3;
   elseif (substr($aux,5,10) == "0000000000")
      $col = 4;
   elseif (substr($aux,7,8)  == "00000000")
      $col = 5;
   elseif (substr($aux,9,6)  == "000000")
      $col = 6;
   elseif (substr($aux,11,4) == "0000")
      $col = 7;
   elseif (substr($aux,13,2) == "00")
      $col = 8;
   else
      $col = 9;
   
   $pdf->setfont('arial','',7);
   $pdf->cell(9-$col,$alt,db_formatar($aux,'receita'),0,0,"L",0);
   $pdf->cell($col+18,$alt,'',0,0,"L",0);
   $pdf->cell(90,$alt,str_repeat(" ",$col*2).$o57_descr,0,0,"L",0);
   //$pdf->cell(20+(9-$col)+$col,$alt,(8-$col),1,0,"L",0);
   $pdf->cell(20,$alt,substr($o57_finali,0,40),0,1,"L",0);
}


$pdf->ln(14);

// assinaturas(&$pdf,&$classinatura,'BG');


$pdf->Output();