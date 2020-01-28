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
include("classes/db_iptuender_classe.php");
include("classes/db_iptuconstr_classe.php");
$cliptuender = new cl_iptuender;
$cliptuconstr = new cl_iptuconstr;
$cliptuender->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
$clrotulo->label('nome');
$clrotulo->label('descrdepto');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$txt_where="";
$and="";
if ($listacgm != "") {
	if (isset ($vercgm) and $vercgm == "com") {
		$txt_where .= $and." j01_numcgm in  ($listacgm)";
		$and = " and ";
	} else {
		$txt_where .= $and." j01_numcgm not in  ($listacgm)";
		$and = " and ";
	}
}
if ($listaset != "") {
	$vir = "";
	$dados_list = split(",",$listaset);
	$listaset = "";
	for($w=0;$w<count($dados_list);$w++){
		$listaset .= $vir."'".$dados_list[$w]."'";
		$vir = ",";
	}
	if (isset ($verset) and $verset == "com") {
		$txt_where .= $and." j34_setor in  ($listaset)";
		$and = " and ";
	} else {
		$txt_where .= $and." j34_setor not in  ($listaset)";
		$and = " and ";
	}
}
$ordem = "";
if(isset($tipo_ordem) && $tipo_ordem == "m"){
    $ordem = "j43_matric,j43_dest,j43_ender,j43_cep,j34_setor";
}else if(isset($tipo_ordem) && $tipo_ordem == "d"){
	 $ordem = "j43_dest,j43_matric,j43_ender,j43_cep,j34_setor";
}else if(isset($tipo_ordem) && $tipo_ordem == "e"){
    $ordem = "j43_ender,j43_matric,j43_dest,j43_cep,j34_setor";
}else if (isset($tipo_ordem) && $tipo_ordem == "c"){
    $ordem = "j43_cep,j43_matric,j43_dest,j43_ender,j34_setor";
}else if (isset($tipo_ordem) && $tipo_ordem == "c"){
	$ordem = "j34_setor,j43_matric,j43_dest,j43_ender,j43_cep";
}else if (isset($tipo_ordem) && $tipo_ordem == "p"){
	$ordem = "j43_munic,j43_matric,j43_dest,j43_ender,j43_cep,j34_setor";
}
$head3 = "ENDEREÇO DE ENTREGA ";

$result = $cliptuender->sql_record($cliptuender->sql_query(null,"*",$ordem,$txt_where));
if ($cliptuender->numrows == 0){
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
$total_t = 0;
$total_p = 0; 
$p=0;
for($x = 0; $x < $cliptuender->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLj43_matric	,1,0,"C",1);
      $pdf->cell(56,$alt,$RLj43_dest	,1,0,"C",1);
      $pdf->cell(50,$alt,$RLj43_ender	,1,0,"C",1);
      $pdf->cell(10,$alt,"Nº"			,1,0,"C",1);//$RLj43_numimo = "Nº do Imovel"
      $pdf->cell(30,$alt,$RLj43_comple	,1,0,"C",1);
      $pdf->cell(34,$alt,$RLj43_bairro	,1,0,"C",1);
      $pdf->cell(30,$alt,$RLj43_munic	,1,0,"C",1);
      $pdf->cell(10,$alt,$RLj43_uf		,1,0,"C",1);
      $pdf->cell(15,$alt,$RLj43_cep		,1,0,"C",1);
      $pdf->cell(20,$alt,$RLj43_cxpost	,1,1,"C",1);       
      $troca = 0;
      $p=0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$j43_matric,0,0,"C",$p);  

   if(strlen($j43_dest) < 33){
      $pdf->cell(56,$alt,$j43_dest ,0,0,"L",$p);   
   }else{
      $pdf->cell(56,$alt,substr($j43_dest,0,35)."..."  ,0,0,"L",$p);   
   }
     
   $pdf->cell(50,$alt,$j43_ender ,0,0,"L",$p);   
   $pdf->cell(10,$alt,$j43_numimo,0,0,"C",$p);   
   $pdf->cell(30,$alt,$j43_comple,0,0,"L",$p);   
   $pdf->cell(34,$alt,$j43_bairro,0,0,"L",$p);   
   $pdf->cell(30,$alt,$j43_munic ,0,0,"L",$p);   
   $pdf->cell(10,$alt,$j43_uf    ,0,0,"C",$p);   
   $pdf->cell(15,$alt,$j43_cep   ,0,0,"C",$p);   
   $pdf->cell(20,$alt,$j43_cxpost,0,1,"C",$p);   
   if ($p==0){
   		$p=1;
   }else{
		$p=0;	
   }
   $result_constr = $cliptuconstr->sql_record($cliptuconstr->sql_query_file(null,null,"*",null,"j39_matric = $j43_matric and j39_dtdemo is null"));
   if ($cliptuconstr->numrows>0){
   	$total_p++;
   }else{
   	$total_t++;
   }
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(270,$alt,'MATRICULAS TERRITORIAIS : '.$total_t.'     MATRICULAS PREDIAIS : '.$total_p.'     TOTAL DE REGISTROS : '.$total,"T",0,"R",0);
$pdf->Output();
?>