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

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head2 = "RELATÓRIO DE LOTEAMENTOS";
$head4 = "LOTEAMENTO : ".$descr;
$head8 = "COM DÉBITOS PENDENTES";

if($ordem == 'a'){
  $xordem = ' order by z01_nome ';
  $xxordem = 'ALFABÉTICA';
}else{
  $xordem = ' order by j01_matric ';
  $xxordem = 'NUMÉRICA';
}

$head6 = "ORDEM : ".$xxordem;
 
$sql  = " select j01_matric,																				   ";
$sql .= "        z01_nome,																					   ";
$sql .= "        sum(total_vencidos) as valor																   ";
$sql .= "   from ( select j01_matric,																		   ";
$sql .= "	              z01_nome,																			   ";
$sql .= "	              case                                                                                 ";
$sql .= "                   when trim(vencidos) <> ''                                                          ";
$sql .= "                     then  substr(vencidos,82,15)::float8                                             ";
$sql .= "                   else 0                                                                             ";
$sql .= "                 end as total_vencidos		                                                           ";
$sql .= "            from ( select j01_matric,																   ";
$sql .= "			               z01_nome,																   ";
$sql .= "                          c.k00_numpre,															   ";
$sql .= "                          fc_debitos_numpre(c.k00_numpre,0,current_date,2005,'t','t') as vencidos     ";
$sql .= "                     from loteam l																	   ";
$sql .= "                          inner join loteloteam a			 on l.j34_loteam = a.j34_loteam			   ";
$sql .= "                          inner join proprietario_nome on j01_idbql		 = a.j34_idbql			   ";
$sql .= "                          inner join arrematric m			 on m.k00_matric = j01_matric			   ";
$sql .= "                          inner join ( select distinct k00_numpre									   ";
$sql .= "                                         from arrecad									               ";
$sql .= "                                     ) c on c.k00_numpre = m.k00_numpre						       ";
$sql .= "                    where l.j34_loteam = $loteam													   ";
$sql .= "					   and j01_baixa is null limit 1000												   ";
$sql .= "                 ) as xx																			   ";
$sql .= "        ) as xxx																					   ";
$sql .= "  group by j01_matric,z01_nome																		   ";
$sql .= "$xordem																							   ";
$result = pg_query($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem matrículas cadastradas para o loteamento '.$descr);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total_valor = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   
	 if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
			$pdf->setX(45);
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,$RLj01_matric ,1,0,"C",1);
			
			if($selEmiteValor == "s"){ 
				$pdf->cell(80,$alt,$RLz01_nome	 ,1,0,"C",1);
				$pdf->cell(20,$alt,'Valor'			 ,1,1,"C",1);
      }else{
				$pdf->cell(100,$alt,$RLz01_nome	 ,1,1,"C",1);
			}
			$troca = 0;
	 }
   
   $pdf->setX(45);
	 $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$j01_matric							 ,0,0,"C",0);
	 
	 if($selEmiteValor == "s"){	   
		 $pdf->cell(80,$alt,$z01_nome							 ,0,0,"L",0);
		 $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
   }else{
		 $pdf->cell(100,$alt,$z01_nome						 ,0,1,"L",0);
	 }
	 
	 $total += 1;
   $total_valor += $valor;
}
$pdf->setX(45);
$pdf->setfont('arial','b',8);
$pdf->cell(30,$alt,'TOTAL DE MATRÍCULAS : '.$total,"T",0,"L",0);
$pdf->cell(80,$alt,'',"T",0,"C",0);
if($selEmiteValor == "s"){	   
	$pdf->cell(20,$alt,db_formatar($total_valor,'f')	,"T",1,"R",0);
}else{
	$pdf->cell(20,$alt,'',"T",1,"R",0);
}
$pdf->Output();
   
?>