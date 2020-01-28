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
include("classes/db_obrashabite_classe.php");

$clobrashabite = new cl_obrashabite;

$clobrashabite->rotulo->label();
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
$clrotulo->label("ob08_codconstr");
$clrotulo->label("ob08_ocupacao");
$clrotulo->label("ob08_tipoconstr");
$clrotulo->label("ob08_tipolanc");
$clrotulo->label(" ob08_area ");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$txt_where="1=1";

$infodt="Periodo Total";

if (($dt!="--")&&($dt1!="--")) {
  $txt_where = $txt_where." and ob09_data  between '$dt' and '$dt1'  ";
  $dt=db_formatar($dt,'d');
  $dt1=db_formatar($dt1,'d');
  $infodt="Periodo de $dt ate $dt1";
} else if ($dt!="--"){
  $txt_where = $txt_where." and ob09_data >= '$dt'  ";
  $dt=db_formatar($dt,'d');
  $infodt="Periodo apartir de  $dt";
} else if ($dt1!="--"){
  $txt_where = $txt_where."and ob09_data <= '$dt1'   ";  
  $dt1=db_formatar($dt1,'d');
  $infodt="Periodo ate  $dt1";
}

if (($areaini!="")&&($areafin!="")) {
    $txt_where .=" and  ob09_area between $areaini and $areafin  ";
      }else if ($areaini!=""){
	$txt_where .=" and  ob09_area >= $areaini  ";
	}else if ($areafin!=""){
	   $txt_where .=" and  ob09_area <= $areafin   ";
	   }

if  ($ordem=='t'){
  $txt_where.="";
  $info="TODAS";
}else if ($ordem=='p'){
  $txt_where.="and ob09_parcial='t'";
  $info="PARCIAL";
}else {
  $txt_where.="and ob09_parcial='f'";
   $info="TOTAL";
}
$head3 = "RELATÓRIO DE CONSTRUÇÕES E HABITE-SE ";
$head4 = "$infodt";
$head5 = "$info";

$campos=" ob01_codobra	,
 ob01_nomeobra	,
 ob03_numcgm	,
 z01_nome	,
 ob08_codconstr	,
 a.j31_descr as ocupacao,	
 b.j31_descr as constr,	
 c.j31_descr as lanc,	
 ob08_area,
 ob09_habite,	
 ob09_parcial,	
 ob09_data,	
 ob09_area ";	

//echo $clobrashabite->sql_query_obco(null,"$campos","",$txt_where);exit;
$result=$clobrashabite->sql_record($clobrashabite->sql_query_obco(null,"$campos","",$txt_where));

if ($clobrashabite->numrows == 0){
  
  $sMsg = _M('tributario.projetos.pro2_relhabite002.nao_existem_registros');
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
for($x = 0; $x < $clobrashabite->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Cod. Obra",1,0,"C",1);
      $pdf->cell(70,$alt,$RLob01_nomeobra,1,0,"C",1);
      $pdf->cell(30,$alt,$RLob03_numcgm,1,0,"C",1);
      $pdf->cell(70,$alt,'Proprietário',1,1,"C",1); 
      
      $pdf->cell(20,$alt,'Cod. Constr.',1,0,"C",1);
      $pdf->cell(50,$alt,$RLob08_ocupacao,1,0,"C",1);
      $pdf->cell(50,$alt,$RLob08_tipoconstr,1,0,"C",1);
      $pdf->cell(50,$alt,$RLob08_tipolanc,1,0,"C",1);
      $pdf->cell(20,$alt,$RLob08_area,1,1,"C",1);
      
      $pdf->cell(70,$alt,'',0,0,"C",0);
      $pdf->cell(30,$alt,$RLob09_habite,1,0,"C",1);
      $pdf->cell(30,$alt,$RLob09_parcial,1,0,"C",1);
      $pdf->cell(30,$alt,$RLob09_data,1,0,"C",1);
      $pdf->cell(30,$alt,$RLob09_area,1,1,"C",1);
      $troca = 0;
   }
   if ($codant!=$ob01_codobra){
   if ($p==0){
     $p=1;
   }else $p=0;

   $pdf->setfont('arial','b',8);
   $pdf->cell(20,$alt,  $ob01_codobra,0,0,"C",$p);
   $pdf->cell(70,$alt, $ob01_nomeobra,0,0,"L",$p);
   $pdf->cell(30,$alt,  $ob03_numcgm,0,0,"C",$p);
   $pdf->cell(70,$alt, $z01_nome,0,1,"L",$p);
   }
   $pdf->setfont('arial','',8);
   $pdf->cell(20,$alt,  $ob08_codconstr,0,0,"C",$p);
   $pdf->cell(50,$alt,  $ocupacao,0,0,"L",$p);
   $pdf->cell(50,$alt,  $constr,0,0,"L",$p);
   $pdf->cell(50,$alt,  $lanc,0,0,"L",$p);
   $pdf->cell(20,$alt,  $ob08_area,0,1,"R",$p);
   
   if ($ob09_parcial=='f'){
    $partot='Total';
   
   }else $partot='Parcial';
   
   $pdf->setfont('arial','',7);
   $pdf->cell(70,$alt,'',0,0,"C",$p);
   $pdf->cell(30,$alt,$ob09_habite	,0,0,"C",$p);
   $pdf->cell(30,$alt,$partot	,0,0,"C",$p);
   $pdf->cell(30,$alt,db_formatar($ob09_data,'d')	,0,0,"C",$p);
   $pdf->cell(30,$alt,$ob09_area	,0,1,"R",$p);
   $codant=$ob01_codobra;
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>