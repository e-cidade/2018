<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where = "";
$and = "";
if ($ano != ""){
	$where .= $and." x21_exerc = $ano ";
	$and = " and ";
	$info1 = " Ano: $ano";
}
if ($mes != ""){
	$where .= $and." x21_mes = $mes ";
	$and = " and ";
	$info2 = " Mês: $mes";
}
if ($exc_ini != "" && $exc_fim != ""){
	$where .= $and." x21_excesso between $exc_ini and $exc_fim ";
	$and = " and ";
	$info3 = "Excesso: $exc_ini a $exc_fim ";
}else if ($exc_ini != ""){
	$where .= $and." x21_excesso >= $exc_ini ";
	$and = " and ";
	$info3 = "Excesso apartir de: $exc_ini ";
}else if ($exc_fim != ""){
	$where .= $and." x21_excesso <= $exc_fim ";
	$and = " and ";
	$info3 = "Excesso ate: $exc_fim ";
} 
if ($where != ""){
	$where = " where ".$where. 'and x21_status = 1';
}


$desc_ordem = "Ordem: Zona de Entrega/Logradouro/Numero/Letra";
$order_by = "order by x01_entrega, j14_nome, x01_numero, x11_complemento, x01_letra";

$head2 = "Relatório de Consumo/Excesso";
$head3 = @$info1; 
$head4 = @$info2;
$head5 = @$info3;
$head6 = @$desc_ordem;
$sql = "select  x04_matric,
	           x21_dtleitura,
	           x04_nrohidro,
	           fc_agua_leituraanterior(x04_matric, x21_codleitura) as x21_leitura_ant,
	           x21_leitura, 
	           x21_consumo,
	           x21_excesso,
		   x01_codrua, 
		   j14_nome, 
		   x01_numero, 
		   x11_complemento,
		   x01_letra
	   from agualeitura
	           
	           inner join aguahidromatric on x04_codhidrometro = x21_codhidrometro
	           inner join aguabase on aguahidromatric.x04_matric = aguabase.x01_matric
                   left join aguaconstr on aguaconstr.x11_matric = aguabase.x01_matric and x11_tipo = 'P'
                   inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro
                   inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua
       $where $order_by";
//die($sql);
$result = pg_exec($sql);
$numrows = pg_numrows($result);
//db_criatabela($result);exit;
if ($numrows==0){	           
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p = 0;
for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Matricula",1,0,"C",1);
      $pdf->cell(85,$alt,"Logradouro",1,0,"C",1);
      $pdf->cell(10,$alt,"Letra",1,0,"C",1);
      $pdf->cell(20,$alt,"Data Leitura",1,0,"C",1);      
      $pdf->cell(25,$alt,"Nro Hidrometro",1,0,"C",1);
      $pdf->cell(18,$alt,"L. Anterior",1,0,"C",1);
      $pdf->cell(18,$alt,"L. Atual",1,0,"C",1);
      $pdf->cell(15,$alt,"Consumo",1,0,"C",1);       
      $pdf->cell(15,$alt,"Excesso",1,0,"C",1);
      $pdf->cell(50,$alt,"Observações",1,1,"C",1); 
      $troca = 0;
      $p = 0;
   }
   $pdf->setfont('arial','',7);   
   $pdf->cell(20,$alt,$x04_matric,0,0,"C",$p);

   $pdf->cell(85,$alt,"$j14_nome - $x01_numero/$x11_complemento",0,0,"L",$p);
   $pdf->cell(10,$alt,$x01_letra,0,0,"L",$p);


   $pdf->cell(20,$alt,db_formatar($x21_dtleitura, 'd'),0,0,"C",$p);
   $pdf->cell(25,$alt,$x04_nrohidro,0,0,"C",$p);
   $pdf->cell(18,$alt,$x21_leitura_ant,0,0,"C",$p);   
   $pdf->cell(18,$alt,$x21_leitura,0,0,"C",$p);
   $pdf->cell(15,$alt,$x21_consumo,0,0,"C",$p);
   $pdf->cell(15,$alt,$x21_excesso,0,0,"C",$p);
   $pdf->cell(50,$alt,'',0,1,"C",$p);
   if ($p == 0) $p=1;
   else $p = 0;  
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(191,$alt,'TOTAL DE REGISTROS : '.$total,"T",0,"L",0);
$pdf->Output();
?>