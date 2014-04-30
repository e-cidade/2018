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

$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('descrdepto');
$clrotulo->label('k00_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('k00_receit');
$clrotulo->label('k00_dtoper');
$clrotulo->label('k00_dtvenc');
$clrotulo->label('k00_valor');
$clrotulo->label('k00_dtpaga');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$where = " 1=1 ";
if (($dataini != "--") && ($datafim != "--")) {
	$where = $where." and recibo.k00_dtoper  between '$dataini' and '$datafim'  ";
	$dataini = db_formatar($dataini, "d");
	$datafim = db_formatar($datafim, "d");
	$info = "De $dataini até $datafim.";
} else	if ($dataini != "--") {
		$where = $where." and  recibo.k00_dtoper  >= '$dataini'  ";
		$dataini = db_formatar($dataini, "d");
		$info = "Apartir de $dataini.";
} else	if ($datafim != "--") {
			$where = $where."and  recibo.k00_dtoper <= '$datafim'   ";
			$datafim = db_formatar($datafim, "d");
			$info = "Até $datafim.";
}

if (isset($k02_codigo)&&$k02_codigo!=""){
  $where.=" and  recibo.k00_receit=$k02_codigo ";
}
$order_by="";
if($ordem == "n") {
	$desc_ordem = "Ordenado por Nome";
	$order_by = "order by cgm.z01_nome";
}else if($ordem == "d") {
	$desc_ordem = "Ordenado por Data de operação";
	$order_by = "order by recibo.k00_dtoper";
}else if($ordem == "e") {
	$desc_ordem = "Ordenado por Data de pagamento";
	$order_by = "order by arrepaga.k00_dtpaga";
}

if($busca == "p") {
	$busca_inf = "Situação - PAGOS";
	$where .= " and arrepaga.k00_dtpaga is not null ";
}else if($busca == "n") {
	$busca_inf = "Situação - NÃO PAGOS";
	$where .= " and arrepaga.k00_dtpaga is null ";
}

$head3 = "Relatório de Recibos da Receita";
$head4 = @$busca_inf;
$head5 = @$info;

$sql= "select recibo.k00_numcgm ,
                 cgm.z01_nome	,
              recibo.k00_receit	,
	      recibo.k00_dtoper	,
	      recibo.k00_dtvenc	,
	      recibo.k00_valor	,
	    arrepaga.k00_dtpaga
       from recibo														              
            inner join cgm on recibo.k00_numcgm=z01_numcgm
	    left join arrepaga on recibo.k00_numpre = arrepaga.k00_numpre
	                      and recibo.k00_numpar = arrepaga.k00_numpar
	                      and recibo.k00_receit = arrepaga.k00_receit
       where $where $order_by";																			
       //die($sql);
$result=pg_exec($sql);																			
if (pg_numrows($result) == 0){
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
$id_usuarioaux = ""; 
$p=0;
$totalval=0; 
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLk00_numcgm  ,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome    ,1,0,"C",1); 
      $pdf->cell(15,$alt,$RLk00_receit  ,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLk00_dtoper  ,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLk00_dtvenc  ,1,0,"C",1); 
      $pdf->cell(20,$alt,/*$RLk00_dtpaga*/"Dt. Pgto",1,0,"C",1); 
      $pdf->cell(20,$alt,$RLk00_valor   ,1,1,"C",1); 
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$k00_numcgm                   ,0,0,"C",$p);
   $pdf->cell(70,$alt,$z01_nome                     ,0,0,"L",$p);
   $pdf->cell(15,$alt,$k00_receit                   ,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($k00_dtoper,'d')  ,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($k00_dtvenc,'d')  ,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($k00_dtpaga,'d')  ,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($k00_valor,'f')   ,0,1,"R",$p);
   if ($p==1){     
     $p=0;
   }else $p=1;
   $total++;
   $totalval+=$k00_valor; 
}
$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL DE REGISTROS:  '.$total,"T",0,"L",0);
$pdf->cell(85,$alt,'VALOR TOTAL:  '.db_formatar($totalval,'f'),"T",0,"R",0);
$pdf->Output();
?>