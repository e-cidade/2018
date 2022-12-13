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

//include("libs/db_liborcamento.php");
include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");

//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);


/*
$xtipo = 0;
if($origem == "O"){
  $xtipo = "ORÇAMENTO";
}else{
  $xtipo = "BALANÇO";
  if($opcao == 3)
    $head6 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  else
    $head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}
$head1 = "DEMONSTRATIVO DA DESPESA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ; 
  $xvirg = ', ';
}
$head5 = "INSTITUIÇÕES : ".$descr_inst;
*/
 
 $anousu=2005;
 $dataini = '2005-01-01';
 $datafim = '2005-02-01';

 $sql = "select o58_funcao,
                o52_descr, 
                o58_subfuncao,
		o53_descr,
		sum(dot_ini) as dot_ini,
		sum(atual) as atual,
		(sum(empenhado)-sum(anulado)) as empenhado,
		sum(liquidado) as liquidado,
		(sum(empenhado_acumulado)-sum(anulado_acumulado))as empenhado_acumulado,
		sum(liquidado_acumulado) as liquidado_acumulado
	 from (
	".db_dotacaosaldo(8,1,4,true,'o58_anousu=2005',$anousu,$dataini,$datafim,'','',true)."
         ) as X
	 where o58_funcao > 0 and o58_subfuncao > 0 
         group by o58_funcao,o52_descr,o58_subfuncao,o53_descr
	";
 
 $result= pg_exec($sql);
 // db_criatabela($result);
 // exit;

pg_exec("commit");

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$alt = 4;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
  
$funcao="";
$subfuncao="";
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   
   if($funcao != $o58_funcao){   // mudou funcao
       $funcao = $o58_funcao;
       $subfunc="";

   }  
   if ($subfuncao!=$o58_subfuncao){
      $subfuncao = $o58_subfuncao;
      
   }  

   $pdf->cell(30,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($atual,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
   $pdf->cell(30,$alt,"total e ",0,0,"R",0);
   $pdf->cell(30,$alt,db_formatar(($liquidado_acumulado/$dot_ini*100),'f'),0,0,"R",0);
   $pdf->cell(30,$alt,"saldo ( a- e ",0,1,"R",0);



}



pg_free_result($result);
//include("fpdf151/geraarquivo.php");
$pdf->Output();
?>