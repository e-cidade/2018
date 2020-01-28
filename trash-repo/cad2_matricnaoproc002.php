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
include("classes/db_iptubase_classe.php");
include("classes/db_iptucalc_classe.php");

$cliptubase = new cl_iptubase;
$cliptucalc = new cl_iptucalc;


$clrotulo = new rotulocampo;
$clrotulo->label('j34_setor');
$clrotulo->label('j34_lote');
$clrotulo->label('j34_quadra');
$clrotulo->label('z01_nome');
$cliptubase->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$sql= "select * from
          (select j01_matric, 
	          j23_matric,
		  j46_matric,
		  j01_numcgm,
		  z01_nome,
		  j34_setor,
		  j34_quadra,
		  j34_lote,
		  j01_baixa
	 from  iptubase 
	          inner join cgm on j01_numcgm= z01_numcgm
		  inner join lote on j01_idbql= j34_idbql
		  left join iptucalc on j01_matric = j23_matric and j23_anousu = $ano and j01_baixa is null
		  left join iptuisen on j01_matric = j46_matric
		  left join isenexe on j46_codigo = j47_codigo and j47_anousu = $ano) 
	  as x where j23_matric is null and j46_matric is null and j01_baixa is null";


//die($sql);





$result = pg_exec($sql); 

if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');

}
$head3 = "CADASTRO DE MATRÍCULAS NÃO PROCESSADAS ";
$head5 = "ORDEM POR COD. MATRÍCULA";
$head6 = "REFERENTE AO ANO DE $ano";
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;$total = 0;
$p=0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLj01_matric,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj01_numcgm,1,0,"C",1);
      $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj34_setor,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj34_quadra,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj34_lote,1,1,"C",1); 
   
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$j01_matric,0,0,"C",$p);
   $pdf->cell(20,$alt,$j01_numcgm,0,0,"C",$p);
   $pdf->cell(80,$alt,$z01_nome,0,0,"L",$p);
   $pdf->cell(20,$alt,$j34_setor,0,0,"C",$p);
   $pdf->cell(20,$alt,$j34_quadra,0,0,"C",$p);
   $pdf->cell(20,$alt,$j34_lote,0,1,"C",$p); 
  
   if ($p==0){
     $p=1;
   }else $p=0;
   
   $total++
   ;
}

$pdf->setfont('arial','b',8);
$pdf->cell(180,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>