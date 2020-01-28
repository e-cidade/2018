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
include("classes/db_divida_classe.php");

$cldivida = new cl_divida;

$clrotulo = new rotulocampo;
$clrotulo->label('v01_numcgm');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//echo $chaves.'\n' ;
$chaves = str_replace("X",",",$chaves);
$where  =" where ";

//echo $chaves; exit;
$desc_ordem = "Numérica";
$order_by = " v01_numcgm ";
if ($lista!=""){
  if ($ver=="com"){
    $where= " where v01_proced in ($lista) and";
  }else{
    $where= " where v01_proced not in ($lista) and";
  }
} 

$head3 = "DÍVIDAS POR NOME ";
$head5 = "ORDEM $desc_ordem";

$tp = 'txt';
if($tp == 'txt'){
 $arq = fopen("tmp/divida_por_nome.txt","w+");	
}

$sql  = "drop table if exists w_dados_relatorio_divida_nome;                   ";
$sql .= "create temp table w_dados_relatorio_divida_nome as                    ";
$sql .= " select *                                                             "; 
$sql .= "   from ( select v01_numcgm,                                          ";
$sql .= "                 z01_nome,                                            ";
$sql .= "		              k00_numpre,                                          ";
$sql .= "		              'N' as tipo                                          ";
$sql .= "            from divida                                               ";
$sql .= "                 inner join cgm     on v01_numcgm = z01_numcgm        ";
$sql .= "  		            inner join arrecad on v01_numpre = k00_numpre        ";
$sql .= "  		                              and v01_numpar = k00_numpar        ";
$sql .= "          $where v01_exerc in ($chaves)                               ";
$sql .= "  	          and v01_instit = ".db_getsession('DB_instit');
$sql .= "           union                                                      ";
$sql .= "          select v01_numcgm,                                          "; 
$sql .= "                 z01_nome,                                            ";
$sql .= "                 k00_numpre,                                          ";
$sql .= "	                'P' as tipo                                          ";
$sql .= "            from divida                                               ";
$sql .= "                 inner join cgm      on v01_numcgm       = z01_numcgm ";
$sql .= "           			inner join termodiv on v01_coddiv       = coddiv     ";
$sql .= "           			inner join termo    on termodiv.parcel  = v07_parcel ";
$sql .= "           			                   and v07_instit       = ".db_getsession('DB_instit');
$sql .= "           			inner join arrecad  on termo.v07_numpre = k00_numpre ";
$sql .= "          $where v01_exerc in ($chaves)                               ";
$sql .= "                 and v01_instit = ".db_getsession('DB_instit');
$sql .= "        ) as dados                                                    ";
$sql .= "  order by $order_by ";
$result=db_query($sql);
if (!$result) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Erro criando tabela temporaria com os dados do relatório');
}

$sql    = "select *                            "; 
$sql   .= "  from w_dados_relatorio_divida_nome";
$result = db_query($sql);
if (pg_numrows($result) == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dívidas cadastrados.');
}

$rsDropTable = db_query("drop table if exists w_dados_relatorio_divida_nome;");
 
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
//$pdf->AddPage(); // adiciona uma pagina
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$prenc = 0;
$alt = 4;
$totalre = 0;

db_fieldsmemory($result,0);
$xcgm  = $v01_numcgm;
$xnome = $z01_nome;
$xtotaln = 0;
$xtotalp = 0;


$totalvalor = 0;
$tvalorn = 0;
$tvalorp = 0;
$passa = true;
for($x = 0; $x < pg_numrows($result);$x++){
  
  db_fieldsmemory($result,$x);

  $result_numpre = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"1");
  if ($result_numpre == false) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao processar numpre ' . $k00_numpre);
  }
  db_fieldsmemory($result_numpre,0);

  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,$RLv01_numcgm,1,0,"C",1);
    $pdf->cell(50,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(35,$alt,'Normal',1,0,"C",1); 
    $pdf->cell(35,$alt,'Parcelado',1,0,"C",1); 
    $pdf->cell(35,$alt,'Total',1,1,"C",1); 
    $troca = 0;
	
   if($tp == 'txt'){
    fwrite($arq,str_pad("|Numgm",10));
    fwrite($arq,str_pad("|Nome",40));   
    fwrite($arq,str_pad("|Normal",25));      
    fwrite($arq,str_pad("|Parcelado",25));      
    fwrite($arq,str_pad("|Total|",25));            
    fwrite($arq,"\n");               
   }
   
  }

  if ($xcgm == $v01_numcgm) {
    $passa = false;
    if ($tipo == "N"){
      $xtotaln += $total;
    }
    if ($tipo == "P"){
      $xtotalp += $total;
    }

  } else {
    
    $pdf->setfont('arial','',7);
    $pdf->cell(30,$alt,$xcgm,0,0,"C",$prenc);
    $pdf->cell(50,$alt,$xnome,0,0,"L",$prenc);
    $pdf->cell(35,$alt,db_formatar($xtotaln,'f'),0,0,"R",$prenc);
    $pdf->cell(35,$alt,db_formatar($xtotalp,'f'),0,0,"R",$prenc);
    $pdf->cell(35,$alt,db_formatar($xtotaln + $xtotalp,'f'),0,1,"R",$prenc);
    
   if($tp == 'txt'){
    fwrite($arq,str_pad("|".$xcgm,10));
    fwrite($arq,str_pad("|".$xnome,40));   
    fwrite($arq,str_pad("|".db_formatar($xtotaln,'f'),25));      
    fwrite($arq,str_pad("|".db_formatar($xtotalp,'f'),25));      
    fwrite($arq,str_pad("|".db_formatar($xtotaln + $xtotalp,'f')."|",25));            
    fwrite($arq,"\n");            	
   }
	
    $totalvalor += $xtotaln+$xtotalp;
    $tvalorn    += $xtotaln;
    $tvalorp    += $xtotalp;
    $xcgm       = $v01_numcgm;
    $xnome      = $z01_nome;
    $xtotaln    = 0;
    $xtotalp    = 0;
    
    if ($tipo == "N") {
      $xtotaln += $total;
    }
    if ($tipo == "P") {
      $xtotalp += $total;
    }
    if ($prenc == 0){
      $prenc = 1;
    }else{
      $prenc = 0;
    }
    
    $totalre++;
  }
  
}

//---------------------------------------------------------------------------------------------------------------//

if ( $xcgm != $v01_numcgm || $x == pg_numrows($result) ) {
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$xcgm,0,0,"C",$prenc);
  $pdf->cell(50,$alt,$xnome,0,0,"L",$prenc);
  $pdf->cell(35,$alt,db_formatar($xtotaln,'f'),0,0,"R",$prenc);
  $pdf->cell(35,$alt,db_formatar($xtotalp,'f'),0,0,"R",$prenc);
  $pdf->cell(35,$alt,db_formatar($xtotaln + $xtotalp,'f'),0,1,"R",$prenc);
  
  if($tp == 'txt'){
   fwrite($arq,str_pad("|".$xcgm,10));
   fwrite($arq,str_pad("|".$xnome,40));   
   fwrite($arq,str_pad("|".db_formatar($xtotaln,'f'),25));      
   fwrite($arq,str_pad("|".db_formatar($xtotalp,'f'),25));      
   fwrite($arq,str_pad("|".db_formatar($xtotaln + $xtotalp,'f')."|",25));            
   fwrite($arq,"\n");            	
  }
  
  $totalvalor += $xtotaln+$xtotalp;
  $tvalorn    += $xtotaln;
  $tvalorp    += $xtotalp;
  $xcgm       = $v01_numcgm;
  $xnome      = $z01_nome;
  $xtotaln    = 0;
  $xtotalp    = 0;
  
  if ($tipo == "N") {
    $xtotaln += $total;
  }
  if ($tipo == "P") {
    $xtotalp += $total;
  }
  if ($prenc == 0){
    $prenc = 1;
  }else{
    $prenc = 0;
  }
  $totalre++;
}
//---------------------------------------------------------------------------------------------------------------//


$pdf->setfont('arial','b',8);
$pdf->cell(80,$alt,'TOTAL DE REGISTROS E VALORES :  '.$totalre,"T",0,"L",0);
$pdf->cell(35,$alt,db_formatar($tvalorn,'f'),"T",0,"R",0);
$pdf->cell(35,$alt,db_formatar($tvalorp,'f'),"T",0,"R",0);
$pdf->cell(35,$alt,db_formatar($totalvalor,"f"),"T",0,"R",0);

 if($tp == 'txt'){
  fwrite($arq,str_pad("|TOTAL DE REGISTROS E VALORES ".$totalre,50));
  fwrite($arq,str_pad("|".db_formatar($tvalorn,'f'),25));      
  fwrite($arq,str_pad("|".db_formatar($tvalorp,'f'),25));      
  fwrite($arq,str_pad("|".db_formatar($totalvalor,'f')."|",25));            
  fwrite($arq,"\n");               
 }

$pdf->Output();

?>