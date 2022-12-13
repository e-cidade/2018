<?php
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('descrdepto');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$instit         = db_getsession("DB_instit");
$result_ultdata = db_query("select k22_data from debitos where k22_instit = $instit order by k22_data desc limit 1");
if ( pg_numrows($result_ultdata) == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros na tabela debitos.');
}
db_fieldsmemory($result_ultdata,0);

$where = " where k22_data = '$k22_data' ";
$and   = " and  ";

if($ordem == "s") {

  $desc_ordem = "Setor/Quadra/Lote";
  $order_by   = "order by lote.j34_setor,
	                        lote.j34_quadra,
							            lote.j34_lote,
							 	  				iptubase.j01_matric,
							 	  				debitos.k22_tipo,
							 	  				debitos.k22_exerc,
							 	  				debitos.k22_numpar";
}else if($ordem == "m") {

  $desc_ordem = "Matrícula";
  $order_by   = "order by iptubase.j01_matric,
							  					debitos.k22_tipo,
							  					debitos.k22_exerc,
							  					debitos.k22_numpar";
}else if($ordem == "r") {

  $desc_ordem = "Rua";
  $order_by   = "order by proprietario.nomepri,
							  					iptubase.j01_matric,
								  				debitos.k22_tipo,
								  				debitos.k22_exerc,
								  				debitos.k22_numpar";
}

if(isset($setor) && $setor != ""){

  $arr_setor = split(",",$setor);
  $vir       = "";
  $set       = "";
  for($i = 0; $i < count($arr_setor); $i++){

    $set .= $vir."'".$arr_setor[$i]."'";
    $vir  = ",";
  }
	$where .= " $and lote.j34_setor in ($set)";
  $and    = " and ";
}

if(isset($quadra) && $quadra != ""){

  $arr_quadra = split(",",$quadra);
  $vir        = "";
  $qua        = "";
  for($i = 0; $i < count($arr_quadra); $i++){

    $qua .= $vir."'".$arr_quadra[$i]."'";
    $vir  = ",";
  }

	$where .= " $and proprietario.j34_quadra in ($qua)";
  $and    = " and ";
}

if (isset($ruas) && $ruas!=""){

	$where .= " $and proprietario.codpri in ($ruas)";
  $and    = " and ";
}

if ($lista != "") {

	if (isset ($ver) && $ver == "com") {
		$where .= " $and iptubase.j01_numcgm in  ($lista)";
	} else {
		$where .= " $and iptubase.j01_numcgm not in  ($lista)";
	}
  $and = " and ";
}

$head3 = "POSIÇÃO EM ".db_formatar($k22_data,"d");
$head5 = "ORDENADO POR  $desc_ordem";

$sql = "select iptubase.j01_matric,
               proprietario.z01_nome,
							 lote.j34_setor,
							 lote.j34_quadra,
							 lote.j34_lote,
							 proprietario.codpri,
							 proprietario.nomepri,
               proprietario.j39_numero,
							 proprietario.j39_compl,
							 lote.j34_bairro,
							 bairro.j13_descr,
							 debitos.k22_tipo,
							 arretipo.k00_descr,
							 k22_numpar ,
						   sum(k22_vlrhis)   as k22_vlrhis  ,
						   sum(k22_vlrcor)   as k22_vlrcor  ,
						   sum(k22_juros)    as k22_juros   ,
						   sum(k22_multa)    as k22_multa   ,
						   sum(k22_desconto) as k22_desconto,
							 k22_exerc
				  from proprietario
				       inner join iptubase   on proprietario.j01_matric = iptubase.j01_matric
				       inner join arrematric on arrematric.k00_matric   = iptubase.j01_matric
				  		 inner join debitos    on debitos.k22_numpre      = arrematric.k00_numpre
				  		                      and k22_instit              = $instit
				  		 inner join arretipo   on arretipo.k00_tipo       = debitos.k22_tipo
				  		 inner join lote       on iptubase.j01_idbql      = lote.j34_idbql
				  		 inner join bairro     on lote.j34_bairro         = bairro.j13_codi
				  		 $where
      group by iptubase.j01_matric,
		           proprietario.z01_nome,
		  				 lote.j34_setor,
               lote.j34_quadra,
		  				 lote.j34_lote,
		  				 proprietario.codpri,
		  				 proprietario.nomepri,
               proprietario.j39_numero,
		  				 proprietario.j39_compl,
		  				 lote.j34_bairro,
		  				 bairro.j13_descr,
		  				 debitos.k22_tipo,
		  				 arretipo.k00_descr,
		  				 k22_numpar,
		  				 k22_exerc
		  				 $order_by";

$result  = db_query($sql);
$numrows = pg_numrows($result);
if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$total    = 0;
$troca    = 1;
$alt      = 4;
$totalreg = 0;
$totalval = 0;
$arr_tipo =  array();
$p = 0;
$matric_ant=0;
$totalmat1=0;
$totalmat2=0;
$totalmat3=0;
$totalmat4=0;
$totalmat5=0;
$totalmat6=0;
$tipo_ant=0;
$totaldeb1=0;
$totaldeb2=0;
$totaldeb3=0;
$totaldeb4=0;
$totaldeb5=0;
$totaldeb6=0;
for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
	 if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
			$pdf->addpage("L");
			$pdf->setfont('arial','b',8);
			$pdf->cell(30,$alt,"Matrícula",1,0,"C",1);
			$pdf->cell(100,$alt,"Contribuinte",1,0,"C",1);
			$pdf->cell(50,$alt,"Setor/Quadra/Lote",1,0,"C",1);
			$pdf->cell(100,$alt,"Endereço",1,1,"C",1);

			$pdf->cell(15,$alt,"Tipo",1,0,"C",1);
			$pdf->cell(70,$alt,"Descr. Tipo",1,0,"C",1);
			$pdf->cell(20,$alt,"Exerc.",1,0,"C",1);
			$pdf->cell(20,$alt,"N° Parc.",1,0,"C",1);
			$pdf->cell(25,$alt,"Vlr Histórico",1,0,"C",1);
			$pdf->cell(25,$alt,"Vlr Corrigido",1,0,"C",1);
			$pdf->cell(25,$alt,"Multa",1,0,"C",1);
			$pdf->cell(25,$alt,"Juros",1,0,"C",1);
			$pdf->cell(25,$alt,"Desconto",1,0,"C",1);
			$pdf->cell(30,$alt,"Total",1,1,"C",1);
			$troca = 0;
	 }
   $passo_tipo = false;

   if ($x!=0&&$tipo_ant!=$k22_tipo){

	     $pdf->setfont('arial','b',7);
	     $pdf->cell(125,$alt,"Total Tipo de Débito: $tipo_ant","T",0,"L",$p);
	     $pdf->cell(25,$alt,db_formatar($totaldeb1,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totaldeb2,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totaldeb3,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totaldeb4,'f'),"T",0,"R",$p);
   	   $pdf->cell(25,$alt,db_formatar($totaldeb5,'f'),"T",0,"R",$p);
    	 $pdf->cell(30,$alt,db_formatar($totaldeb6,'f') ,"T",1,"R",$p);
			 $totaldeb1=0;
			 $totaldeb2=0;
			 $totaldeb3=0;
			 $totaldeb4=0;
			 $totaldeb5=0;
			 $totaldeb6=0;
			 $tipo_ant=$k22_tipo;
			 $passo_tipo = true;
	 }else{
		 $tipo_ant=$k22_tipo;
	 }

	 if ($matric_ant!=$j01_matric){

     if ($x!=0){

			 if ($passo_tipo == false){

				 $pdf->setfont('arial','b',7);
				 $pdf->cell(125,$alt,"Total Tipo de Débito: $tipo_ant","T",0,"L",$p);
				 $pdf->cell(25,$alt,db_formatar($totaldeb1,'f') ,"T",0,"R",$p);
				 $pdf->cell(25,$alt,db_formatar($totaldeb2,'f') ,"T",0,"R",$p);
				 $pdf->cell(25,$alt,db_formatar($totaldeb3,'f') ,"T",0,"R",$p);
				 $pdf->cell(25,$alt,db_formatar($totaldeb4,'f'),"T",0,"R",$p);
				 $pdf->cell(25,$alt,db_formatar($totaldeb5,'f'),"T",0,"R",$p);
				 $pdf->cell(30,$alt,db_formatar($totaldeb6,'f') ,"T",1,"R",$p);
				 $totaldeb1 = 0;
				 $totaldeb2 = 0;
				 $totaldeb3 = 0;
				 $totaldeb4 = 0;
				 $totaldeb5 = 0;
				 $totaldeb6 = 0;
				 $tipo_ant  = $k22_tipo;
			 }

		   $pdf->setfont('arial','b',9);
	     $pdf->cell(125,$alt,"Total Matrícula: $matric_ant","T",0,"L",$p);
	     $pdf->cell(25,$alt,db_formatar($totalmat1,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totalmat2,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totalmat3,'f') ,"T",0,"R",$p);
    	 $pdf->cell(25,$alt,db_formatar($totalmat4,'f'),"T",0,"R",$p);
   	   $pdf->cell(25,$alt,db_formatar($totalmat5,'f'),"T",0,"R",$p);
    	 $pdf->cell(30,$alt,db_formatar($totalmat6,'f') ,"T",1,"R",$p);
			 $totalmat1 = 0;
			 $totalmat2 = 0;
			 $totalmat3 = 0;
			 $totalmat4 = 0;
			 $totalmat5 = 0;
			 $totalmat6 = 0;
		 }

		 if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

				$pdf->addpage("L");
				$pdf->setfont('arial','b',8);
				$pdf->cell(30,$alt,"Matrícula",1,0,"C",1);
				$pdf->cell(100,$alt,"Contribuinte",1,0,"C",1);
				$pdf->cell(50,$alt,"Setor/Quadra/Lote",1,0,"C",1);
				$pdf->cell(100,$alt,"Endereço",1,1,"C",1);
				$pdf->cell(15,$alt,"Tipo",1,0,"C",1);
				$pdf->cell(70,$alt,"Descr. Tipo",1,0,"C",1);
				$pdf->cell(20,$alt,"Exerc.",1,0,"C",1);
				$pdf->cell(20,$alt,"N° Parc.",1,0,"C",1);
				$pdf->cell(25,$alt,"Vlr Histórico",1,0,"C",1);
				$pdf->cell(25,$alt,"Vlr Corrigido",1,0,"C",1);
				$pdf->cell(25,$alt,"Multa",1,0,"C",1);
				$pdf->cell(25,$alt,"Juros",1,0,"C",1);
				$pdf->cell(25,$alt,"Desconto",1,0,"C",1);
				$pdf->cell(30,$alt,"Total",1,1,"C",1);
				$troca = 0;
		 }

		 $pdf->cell(180,$alt,"",0,1,"C",$p);
     $p = 1;
		 $pdf->cell(30,$alt,$j01_matric,0,0,"C",$p);
		 $pdf->cell(100,$alt,substr($z01_nome,0,90),0,0,"L",$p);
		 $pdf->cell(50,$alt,$j34_setor."/".$j34_quadra."/"."$j34_lote",0,0,"C",$p);
		 $pdf->cell(100,$alt,substr($nomepri.", ".$j39_numero.". ".$j39_compl.". ".$j13_descr,0,90),0,1,"L",$p);
     $p = 0;
		 $matric_ant=$j01_matric;
	 }

	 $pdf->setfont('arial','',7);
	 $total = $k22_vlrcor + $k22_juros + $k22_multa - $k22_desconto;
   $pdf->cell(15,$alt,$k22_tipo,0,0,"C",$p);
	 $pdf->cell(70,$alt,substr($k00_descr,0,55),0,0,"L",$p);
	 $pdf->cell(20,$alt,$k22_exerc,0,0,"C",$p);
	 $pdf->cell(20,$alt,$k22_numpar ,0,0,"C",$p);
	 $pdf->cell(25,$alt,db_formatar($k22_vlrhis,'f')     ,0,0,"R",$p);
	 $pdf->cell(25,$alt,db_formatar($k22_vlrcor ,'f')     ,0,0,"R",$p);
	 $pdf->cell(25,$alt,db_formatar($k22_juros ,'f')   ,0,0,"R",$p);
	 $pdf->cell(25,$alt,db_formatar($k22_multa  ,'f')  ,0,0,"R",$p);
	 $pdf->cell(25,$alt,db_formatar($k22_desconto,'f') ,0,0,"R",$p);
	 $pdf->cell(30,$alt,db_formatar($total,'f') ,0,1,"R",$p);

	 if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

			$pdf->addpage("L");
			$pdf->setfont('arial','b',8);
			$pdf->cell(30,$alt,"Matrícula",1,0,"C",1);
			$pdf->cell(100,$alt,"Contribuinte",1,0,"C",1);
			$pdf->cell(50,$alt,"Setor/Quadra/Lote",1,0,"C",1);
			$pdf->cell(100,$alt,"Endereço",1,1,"C",1);

			$pdf->cell(15,$alt,"Tipo",1,0,"C",1);
			$pdf->cell(70,$alt,"Descr. Tipo",1,0,"C",1);
			$pdf->cell(20,$alt,"Exerc.",1,0,"C",1);
			$pdf->cell(20,$alt,"N° Parc.",1,0,"C",1);
			$pdf->cell(25,$alt,"Vlr Histórico",1,0,"C",1);
			$pdf->cell(25,$alt,"Vlr Corrigido",1,0,"C",1);
			$pdf->cell(25,$alt,"Multa",1,0,"C",1);
			$pdf->cell(25,$alt,"Juros",1,0,"C",1);
			$pdf->cell(25,$alt,"Desconto",1,0,"C",1);
			$pdf->cell(30,$alt,"Total",1,1,"C",1);
			$troca = 0;
	 }

	 if (array_key_exists($k22_tipo."-".$k00_descr,$arr_tipo)){
	   $arr_tipo[$k22_tipo."-".$k00_descr ] += $total;
	 }else{
	   $arr_tipo[$k22_tipo."-".$k00_descr ] = $total;
	 }

	 $totalmat1+=$k22_vlrhis ;
	 $totalmat2+=$k22_vlrcor ;
	 $totalmat3+=$k22_juros ;
	 $totalmat4+=$k22_multa ;
	 $totalmat5+=$k22_desconto ;
	 $totalmat6+=$total ;
	 $totaldeb1+=$k22_vlrhis ;
	 $totaldeb2+=$k22_vlrcor ;
	 $totaldeb3+=$k22_juros ;
	 $totaldeb4+=$k22_multa ;
	 $totaldeb5+=$k22_desconto ;
	 $totaldeb6+=$total ;

	 $totalval += $total;
	 $totalreg++;
}

$pdf->setfont('arial','b',7);
$pdf->cell(125,$alt,"Total Tipo de Débito: $tipo_ant","T",0,"L",$p);
$pdf->cell(25,$alt,db_formatar($totaldeb1,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totaldeb2,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totaldeb3,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totaldeb4,'f'),"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totaldeb5,'f'),"T",0,"R",$p);
$pdf->cell(30,$alt,db_formatar($totaldeb6,'f') ,"T",1,"R",$p);

$pdf->setfont('arial','b',9);
$pdf->cell(125,$alt,"Total Matrícula: $matric_ant","T",0,"L",$p);
$pdf->cell(25,$alt,db_formatar($totalmat1,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totalmat2,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totalmat3,'f') ,"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totalmat4,'f'),"T",0,"R",$p);
$pdf->cell(25,$alt,db_formatar($totalmat5,'f'),"T",0,"R",$p);
$pdf->cell(30,$alt,db_formatar($totalmat6,'f') ,"T",1,"R",$p);

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'',"0",1,"R",0);
$pdf->cell(100,$alt,'TOTAL DE REGISTROS	: '.$totalreg,"T",0,"L",0);
$pdf->cell(0,$alt,'VALOR TOTAL : '.db_formatar($totalval,"f"),"T",1,"R",0);

$troca = 1;
foreach ($arr_tipo as $key => $value) {

  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

		$pdf->addpage("L");
		$pdf->setfont('arial','b',8);
		$pdf->cell(40,$alt,"Código Tipo de Débito",1,0,"C",1);
		$pdf->cell(70,$alt,"Descrição Tipo de Débito",1,0,"C",1);
		$pdf->cell(60,$alt,"Valor Total",1,1,"C",1);
		$troca = 0;
		$p = 0;
  }
	$arr_dados = split("-",$key);
  $pdf->cell(40,$alt,$arr_dados[0],0,0,"C",$p);
  $pdf->cell(70,$alt,$arr_dados[1],0,0,"L",$p);
  $pdf->cell(60,$alt,db_formatar($value,'f')     ,0,1,"R",$p);
	 if ($p==0){
		 $p=1;
	 }else{
		 $p=0;
	}
}
$pdf->cell(170,$alt,'',"T",1,"R",0);
$pdf->Output();