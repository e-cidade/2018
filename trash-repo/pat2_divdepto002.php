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
include("classes/db_departdiv_classe.php");
$cldepartdiv = new cl_departdiv;
$cldepartdiv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');

//echo $HTTP_SERVER_VARS["QUERY_STRING"];

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where="db_depart.instit = ".db_getsession("DB_instit");
if ($listadepart != "") {
	if (isset ($verdepart) and $verdepart == "com") {
		$where .= " and t30_depto in  ($listadepart) ";
	} else {
		$where .= " and t30_depto not in  ($listadepart) ";
	}
}

$where2 = "";
$head3  = "LISTAR DEPARTAMENTOS: ";
if (isset($listar_depart) && trim($listar_depart) != ""){
     if (trim($listar_depart) == "T"){
          $head3 .= "TODOS";
     }

     if (trim($listar_depart) == "false"){
          $head3 .= "INATIVOS";
          $where .= " and (db_depart.limite is not null and db_depart.limite < '".date("Y-m-d",db_getsession("DB_datausu"))."')";
          $where2 = " and (db_depart.limite is not null and db_depart.limite < '".date("Y-m-d",db_getsession("DB_datausu"))."')";
     }
     
     if (trim($listar_depart) == "true"){
          $head3 .= "ATIVOS";
          $where .= " and (db_depart.limite is null or db_depart.limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')";
          $where2 = " and (db_depart.limite is null or db_depart.limite >= '".date("Y-m-d",db_getsession("DB_datausu"))."')";
     }
}

$head4 = "LISTAR DVISOES: ";
if (isset($listar_divisao) && trim($listar_divisao) != ""){
     if (trim($listar_divisao) == "T"){
          $head4 .= "TODAS";
     }

     if (trim($listar_divisao) == "false"){
          $head4  .= "INATIVAS";
          $where  .= " and departdiv.t30_ativo is false";
          $where2 .= " and departdiv.t30_ativo is false";
     }
     
     if (trim($listar_divisao) == "true"){
          $head4  .= "ATIVAS";
          $where  .= " and departdiv.t30_ativo is true";
          $where2 .= " and departdiv.t30_ativo is true";
     }
}

$result_depto=$cldepartdiv->sql_record($cldepartdiv->sql_query(null,"distinct t30_depto as coddepto,descrdepto,limite","descrdepto",$where));
//echo($cldepartdiv->sql_query(null,"distinct t30_depto as coddepto,descrdepto,limite","descrdepto",$where)); exit;
$numrows_depto=$cldepartdiv->numrows;
if ($numrows_depto==0) {
  
  $sMsg = _M('patrimonial.patrimonio.pat2_divdepto002.registros_nao_encontrados');
	db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}

//db_criatabela($result_depto); exit;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$erro = 0;
for($x = 0; $x < $numrows_depto;$x++){
   db_fieldsmemory($result_depto,$x);
   $mostrar = "";
   if (isset($limite) && trim(@$limite) == ""){
        $mostrar = " - Ativo: Sim";
   }
   
   if (isset($limite) && trim(@$limite) != ""){
        if ($limite < date("Y-m-d",db_getsession("DB_datausu"))){
             $mostrar = " - Ativo: Não";
        } else {
             $mostrar = " - Ativo: Sim";
        }
   }

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $troca = 0;
   }   
   $total = 0;   
   $result_div=$cldepartdiv->sql_record($cldepartdiv->sql_query(null,"distinct t30_codigo,t30_descr,t30_ativo,t30_numcgm,z01_nome",null,"t30_depto=$coddepto $where2"));
   $numrows_div=$cldepartdiv->numrows;
   if($numrows_div>0){
   	$pdf->setfont('arial','b',9);
   	$pdf->cell(0,$alt,"Departamento:".$coddepto." - ".$descrdepto.$mostrar,0,1,"L",0);
   	$pdf->setfont('arial','b',8);
   	$pdf->cell(20,$alt,"Cod. Divisão",1,0,"C",1);
   	$pdf->cell(70,$alt,$RLt30_descr,1,0,"C",1);
   	$pdf->cell(15,$alt,$RLt30_ativo,1,0,"C",1);
   	$pdf->cell(20,$alt,$RLt30_numcgm,1,0,"C",1);
   	$pdf->cell(70,$alt,$RLz01_nome,1,1,"C",1);
   	$p=0;
   	for($w=0;$w<$numrows_div;$w++){
   		db_fieldsmemory($result_div,$w);
   		if ($pdf->gety() > $pdf->h - 30){
	      	$pdf->addpage();
	      	$pdf->setfont('arial','b',8);
	   		$pdf->cell(20,$alt,"Cod. Divisão",1,0,"C",1);
	   		$pdf->cell(70,$alt,$RLt30_descr,1,0,"C",1);
	   		$pdf->cell(15,$alt,$RLt30_ativo,1,0,"C",1);
	   		$pdf->cell(20,$alt,$RLt30_numcgm,1,0,"C",1);
	   		$pdf->cell(70,$alt,$RLz01_nome,1,1,"C",1);
	   		$p=0;
   		}     
   		$pdf->setfont('arial','',7);
   		$pdf->cell(20,$alt,$t30_codigo,0,0,"C",$p);
   		$pdf->cell(70,$alt,$t30_descr,0,0,"L",$p);   	
   		if ($t30_ativo=='f'){
   			$ativo="Não";
   		}else{
   			$ativo="Sim";
   		}
   		$pdf->cell(15,$alt,$ativo,0,0,"C",$p);
   		$pdf->cell(20,$alt,$t30_numcgm,0,0,"C",$p);
   		$pdf->cell(70,$alt,$z01_nome,0,1,"L",$p);
   		if ($p==0){
   			$p=1;
   		}else{
   			$p=0;
   		}
   		$total++;
   	}  
   	$pdf->cell(195,$alt,"TOTAL DE DIVISÕES:".$total,"T",1,"R",0); 	
   }else{
   	$erro++;
   	continue;
   }   
}
$pdf->Output();
?>