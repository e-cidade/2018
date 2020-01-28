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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clrotulo = new rotulocampo;
$clrotulo->label('');

$txt_where = " where " ;
$and = "";
$info = "";

if ($listarec != "") {
	if (isset ($verrec) and $verrec == "com") {
		$txt_where = $txt_where." $and arrepaga.k00_receit in  ($listarec)";
	} else {
		$txt_where = $txt_where." $and arrepaga.k00_receit not in  ($listarec)";
	}
  $and = " and ";
}
if (($data != "--") && ($data1 != "--")) {
	$txt_where .= " $and arrepaga.k00_dtpaga  between '$data' and '$data1'  ";
	$data = db_formatar($data, "d");
	$data1 = db_formatar($data1, "d");
	$info = "De $data até $data1.";
  $and = " and ";
} else if ($data != "--") {
	$txt_where .= " $and arrepaga.k00_dtpaga >= '$data'  ";
	$data = db_formatar($data, "d");
	$info = "Apartir de $data.";
  $and = " and ";
} else if ($data1 != "--") {
	$txt_where .= " $and arrepaga.k00_dtpaga <= '$data1'   ";
	$data1 = db_formatar($data1, "d");
	$info = "Até $data1.";
  $and = " and ";
}

$head3 = "Relatório Receitas Pagas por Período";
$head4 = @$info;

$sql = "
                    select arretipo.k00_tipo,
                           arretipo.k00_descr,
                           arrepaga.k00_receit,
                           tabrec.k02_descr,
                           round(sum(arrepaga.k00_valor),2) as k00_valor
                    from arrepaga
                         inner join arrecant on arrepaga.k00_numpre = arrecant.k00_numpre
                                            and arrepaga.k00_numpar = arrecant.k00_numpar
                                            and arrepaga.k00_receit = arrecant.k00_receit
                         inner join arretipo on arretipo.k00_tipo   = arrecant.k00_tipo
                         inner join tabrec   on k02_codigo = arrepaga.k00_receit

   left join taborc         on taborc.k02_codigo 		    = tabrec.k02_codigo 
                           and taborc.k02_anousu 			  = " . db_getsession("DB_anousu") . "
   left join orcreceita     on orcreceita.o70_anousu    = taborc.k02_anousu 
                           and orcreceita.o70_codrec    = taborc.k02_codrec 
   left join tabplan p      on p.k02_codigo 					  = tabrec.k02_codigo 
                           and p.k02_anousu             = " . db_getsession("DB_anousu") . "
   left join conplanoreduz  on conplanoreduz.c61_anousu = p.k02_anousu 
                           and conplanoreduz.c61_reduz  = p.k02_reduz 
  
                    $txt_where

   and case when o70_instit is null then conplanoreduz.c61_instit = ". db_getsession("DB_instit") . "
       else o70_instit =  " . db_getsession("DB_instit") . " end 


                    group by arretipo.k00_tipo,
                             arretipo.k00_descr,
                             arrepaga.k00_receit,
                             tabrec.k02_descr																																																																															
										order by arretipo.k00_tipo,
										         arretipo.k00_descr,
														 arrepaga.k00_receit,
														 tabrec.k02_descr
";
//die($sql);

$result = pg_query($sql);
if (pg_numrows($result) == 0) {
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
$p = 0;
$vlr_tot = 0;
$tipo_ant="";
$total_tipo=0;
$vlr_tipo =0 ;
$tipo="";
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Tipo",1,0,"C",1);
      $pdf->cell(65,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(15,$alt,"Receita",1,0,"C",1);
      $pdf->cell(65,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor Arrec.",1,1,"C",1);
      $troca = 0;
      $p = 0;
   }
	 if ($tipo!=$k00_tipo){
    if ($x!=0){
      $pdf->cell(105,$alt,"Total do Tipo:",'T',0,"R",0);
			$pdf->cell(25,$alt,$total_tipo,'T',0,"R",0);
			$pdf->cell(30,$alt,"Vlr. Total Tipo:",'T',0,"R",0);
			$pdf->cell(25,$alt,db_formatar($vlr_tipo,"f"),'T',1,"R",0);
			$total_tipo=0;
			$vlr_tipo =0 ;
		}
	  $tipo = $k00_tipo;
	 }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,@$k00_tipo,0,0,"C",$p);
   $pdf->cell(65,$alt,@$k00_descr,0,0,"L",$p);
   $pdf->cell(15,$alt,@$k00_receit,0,0,"C",$p);
   $pdf->cell(65,$alt,@$k02_descr,0,0,"L",$p);
   $pdf->cell(25,$alt,db_formatar(@$k00_valor,"f"),0,1,"R",$p);
   $total ++;
   $vlr_tot += $k00_valor;
	 $total_tipo++;
	 $vlr_tipo+=$k00_valor;
	 if ($p==1){
		 $p=0;
	 }else{
		 $p=1;
	 }

}
$pdf->setfont('arial','b',8);
$pdf->cell(105,$alt,"TOTAL DE REGISTROS:",'T',0,"R",0);
$pdf->cell(25,$alt,$total,'T',0,"R",0);
$pdf->cell(30,$alt,"VALOR TOTAL:",'T',0,"R",0);
$pdf->cell(25,$alt,db_formatar($vlr_tot,"f"),'T',1,"R",0);
$pdf->output();
exit;
?>