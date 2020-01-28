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
include("classes/db_obrasconstr_classe.php");

$clobrasconstr = new cl_obrasconstr;

$clobrasconstr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('ob01_nomeobra');
$clrotulo->label('ob02_descr');
$clrotulo->label('z01_nome');
$clrotulo->label('j14_nome');
$clrotulo->label('j13_descr');
$clrotulo->label('ob07_compl');
$clrotulo->label('ob07_numero');
$clrotulo->label('ob06_quadra');
$clrotulo->label('ob06_setor');
$clrotulo->label('ob06_lote');
$clrotulo->label('j34_setor');
$clrotulo->label('j34_lote');
$clrotulo->label('j34_quadra');
$clrotulo->label('j34_idbql');
$clrotulo->label('ob04_alvara');
$clrotulo->label('ob04_data');
$clrotulo->label('ob03_numcgm');
$clrotulo->label('ob07_lograd');
$clrotulo->label('ob07_numero');
$clrotulo->label('ob07_compl');
$clrotulo->label('ob07_bairro');
$clrotulo->label('ob07_areaatual');
$clrotulo->label('ob07_unidades');
$clrotulo->label('ob07_pavimentos');
$clrotulo->label('ob07_inicio');
$clrotulo->label('ob07_fim');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$txt_where="1=1 ";


if ($listaocu!=""){
   if (isset($verocu) and $verocu=="com"){
       $txt_where= $txt_where." and ob08_ocupacao in  ($listaocu)";
   } else {
       $txt_where= $txt_where." and ob08_ocupacao not in  ($listaocu)";
   }	 
}  
if ($listaconstr!=""){
   if (isset($verconstr) and $verconstr=="com"){
       $txt_where= $txt_where." and ob08_tipoconstr in  ($listaconstr)";
   } else {
       $txt_where= $txt_where." and ob08_tipoconstr not in  ($listaconstr)";
   }	 
}  
if ($listalanc!=""){
   if (isset($verlanc) and $verlanc=="com"){
       $txt_where= $txt_where." and ob08_tipolanc in  ($listalanc)";
   } else {
       $txt_where= $txt_where." and ob08_tipolanc not in  ($listalanc)";
   }	 
}  
if ($listarua!=""){
   if (isset($verrua) and $verrua=="com"){
       $txt_where= $txt_where." and ob07_lograd in  ($listarua)";
   } else {
       $txt_where= $txt_where." and ob07_lograd not in  ($listarua)";
   }	 
}  

if ($listabairro!=""){
   if (isset($verbairro) and $verbairro=="com"){
       $txt_where= $txt_where." and ob07_bairro in  ($listabairro)";
   } else {
       $txt_where= $txt_where." and ob07_bairro not in  ($listabairro)";
   }	 
}  

$infodtini="Data Inicio :Total";
$infodtfin="Data Final :Total";

if (($dt!="--")&&($dt1!="--")) {
  $txt_where = $txt_where." and ob07_inicio  between '$dt' and '$dt1'  ";
  $dt=db_formatar($dt,'d');
  $dt1=db_formatar($dt1,'d');
  $infodtini="Data Inicio de $dt ate $dt1";
} else if ($dt!="--"){
  $txt_where = $txt_where." and ob07_inicio >= '$dt'  ";
  $dt=db_formatar($dt,'d');
  $infodtini="Data Inicio apartir de $dt";
} else if ($dt1!="--"){
  $txt_where = $txt_where."and ob07_inicio <= '$dt1'   ";  
  $dt1=db_formatar($dt1,'d');
  $infodtini="Data Inicio ate $dt";
}
if (($dt2!="--")&&($dt3!="--")) {
  $txt_where = $txt_where." and ob07_fim  between '$dt2' and '$dt3'  ";
  $dt2=db_formatar($dt2,'d');
  $dt3=db_formatar($dt3,'d');
  $infodtfin="Data Final de $dt2 ate $dt3";
} else if ($dt2!="--"){
  $txt_where = $txt_where." and ob07_fim >= '$dt2'  ";
  $dt2=db_formatar($dt2,'d');
  $infodtfin="Data Final apartir de $dt2";
} else if ($dt3!="--"){
  $txt_where = $txt_where."and ob07_fim <= '$dt3'   ";  
  $dt3=db_formatar($dt3,'d');
  $infodtfin="Data Final ate $dt3";
}


if (($uniini!="")&&($unifin!="")) {
    $txt_where .=" and  ob07_unidades between $uniini and $unifin  ";
      }else if ($uniini!=""){
	$txt_where .=" and  ob07_unidades >= $uniini  ";
	}else if ($unifin!=""){
	   $txt_where .=" and  ob07_unidades <= $unifin   ";
	   }
if (($pavini!="")&&($pavfin!="")) {
    $txt_where .=" and  ob07_pavimentos between $pavini and $pavfin  ";
      }else if ($pavini!=""){
	$txt_where .=" and  ob07_pavimentos >= $pavini  ";
	}else if ($pavfin!=""){
	   $txt_where .=" and  ob07_pavimetos <= $pavfin   ";
	   }

if (($areaini!="")&&($areafin!="")) {
    $txt_where .=" and  ob08_area between $areaini and $areafin  ";
      }else if ($areaini!=""){
	$txt_where .=" and  ob08_area >= $areaini  ";
	}else if ($areafin!=""){
	   $txt_where .=" and  ob08_area <= $areafin   ";
	   }

$where="";

if  ($habite=='t'){
  $where="";
  $info="";
}else if ($habite=='c'){
  $where.="and ob09_habite is not null";
  $info="Obras com Habite-se";
}else {
  $where.="and ob09_habite is null";
  $info="Obras sem Habite-se";
  }


$head3 = "RELATÓRIO DE OBRAS E CONSTRUÇÕES ";
$head4 = "$infodtini";
$head5 = "$infodtfin";
$head6 = "$info ";


$campos=" ob01_codobra	,
 ob01_nomeobra	,
 ob03_numcgm	,
 z01_nome	,
 ob08_codconstr	,
 a.j31_descr as ocupacao,	
 b.j31_descr as constr,	
 c.j31_descr as lanc,	
 ob08_area,	
 j14_nome,		
 ob07_numero,	
 ob07_compl,	
 j13_descr,	
 ob07_areaatual	,
 ob07_unidades	,
 ob07_pavimentos,	
 ob07_inicio	,
 ob07_fim,
 ob09_habite
 ";

$result=$clobrasconstr->sql_record($clobrasconstr->sql_query_ender(null,"$campos","","$txt_where $where"));


if ($clobrasconstr->numrows == 0){
  
  $sMsg = _M('tributario.projetos.pro2_constr002.nao_existem_registros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");

}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;$total = 0;
$p=1;
$codant=0;
for($x = 0; $x < $clobrasconstr->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > ($pdf->h - 40) || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,$alt,"Cod. Obra",1,0,"C",1);
      $pdf->cell(100,$alt,$RLob01_nomeobra,1,0,"C",1);
      $pdf->cell(40,$alt,$RLob03_numcgm,1,0,"C",1);
      $pdf->cell(100,$alt,'Proprietário',1,1,"C",1); 
      
      $pdf->cell(30,$alt,'Cod. Construção',1,0,"C",1);
      $pdf->cell(25,$alt,'Cod. Habite-se',1,0,"C",1);
      $pdf->cell(65,$alt,$RLob08_ocupacao,1,0,"C",1);
      $pdf->cell(65,$alt,$RLob08_tipoconstr,1,0,"C",1);
      $pdf->cell(65,$alt,$RLob08_tipolanc,1,0,"C",1);
      $pdf->cell(30,$alt,$RLob08_area,1,1,"C",1);
      
      $pdf->cell(60,$alt,"Rua/Avenida",1,0,"C",1);
      $pdf->cell(15,$alt,$RLob07_numero,1,0,"C",1);
      $pdf->cell(35,$alt,$RLob07_compl,1,0,"C",1);
      $pdf->cell(60,$alt,$RLob07_bairro,1,0,"C",1);
      $pdf->cell(20,$alt,$RLob07_areaatual,1,0,"C",1);
      $pdf->cell(20,$alt,$RLob07_unidades,1,0,"C",1);
      $pdf->cell(20,$alt,$RLob07_pavimentos,1,0,"C",1);
      $pdf->cell(25,$alt,$RLob07_inicio,1,0,"C",1);
      $pdf->cell(25,$alt,$RLob07_fim,1,1,"C",1);
      $troca = 0;
      $p=1;
   }
   if ($codant!=$ob01_codobra){
   if ($p==0){
     $p=1;
   }else $p=0;
   $pdf->setfont('arial','b',8);
   $pdf->cell(40,$alt,  $ob01_codobra,0,0,"C",$p);
   $pdf->cell(100,$alt, $ob01_nomeobra,0,0,"L",$p);
   $pdf->cell(40,$alt,  $ob03_numcgm,0,0,"C",$p);
   $pdf->cell(100,$alt, $z01_nome,0,1,"L",$p);
   }
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,  $ob08_codconstr,0,0,"C",$p);
   $pdf->cell(25,$alt,  $ob09_habite,0,0,"C",$p);
   $pdf->cell(65,$alt,  $ocupacao,0,0,"C",$p);
   $pdf->cell(65,$alt,  $constr,0,0,"C",$p);
   $pdf->cell(65,$alt,  $lanc,0,0,"C",$p);
   $pdf->cell(30,$alt,  $ob08_area,0,1,"R",$p);
   $pdf->setfont('arial','',7);
   $pdf->cell(60,$alt,$j14_nome	,0,0,"L",$p);
   $pdf->cell(15,$alt,$ob07_numero,0,0,"C",$p);
   $pdf->cell(35,$alt,$ob07_compl,0,0,"L",$p);
   $pdf->cell(60,$alt,$j13_descr,0,0,"L",$p);
   $pdf->cell(20,$alt,$ob07_areaatual,0,0,"C",$p);
   $pdf->cell(20,$alt,$ob07_unidades,0,0,"C",$p);
   $pdf->cell(20,$alt,$ob07_pavimentos,0,0,"C",$p);
   $pdf->cell(25,$alt,db_formatar($ob07_inicio,'d'),0,0,"C",$p);
   $pdf->cell(25,$alt,db_formatar($ob07_fim,'d'),0,1,"C",$p);
   
   $codant=$ob01_codobra;
   $total++;
   
}

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>