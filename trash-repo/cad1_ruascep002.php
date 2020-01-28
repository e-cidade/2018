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
include("dbforms/db_funcoes.php");
include("classes/db_levanta_classe.php");
db_postmemory($HTTP_POST_VARS);

$sql = "select 
              j14_codigo,
	      j14_nome,
	      j14_tipo 
	from ruas 
	      left join logradcep on j65_lograd=j14_codigo 
	where j65_lograd is null;";  

//die($sql);       
$result  = pg_query($sql);
$numrows = pg_num_rows($result);
if($numrows>0){
  db_fieldsmemory($result,0,true);
}
if($numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}

//db_criatabela($result);
//exit;
$head3 = "Relatório de Logradouros do Municipio";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$alt = 4;
$pri = true;
 

for ($i = 0;$i < $numrows;$i++){
 db_fieldsmemory($result,$i);

 if($i%2){
   $cor = 1;
 }else $cor = 0;  
  
  //cabeçalho
  if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
      $pdf->addpage("");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,4,"Codigo do Logradouro",1,0,"C",1);
      $pdf->cell(110,4,"Nome do Logradouro",1,0,"C",1);
      $pdf->cell(40,4,"Tipo",1,1,"C",1);
      $pri = false;
  }
      $pdf->setfont('arial','',7);
      $pdf->cell(40,4,"$j14_codigo",0,0,"C",$cor);
      $pdf->cell(110,4,"$j14_nome",0,0,"L",$cor);
      $pdf->cell(40,4,"$j14_tipo",0,1,"C",$cor);
   
}
$pdf->Output();

?>