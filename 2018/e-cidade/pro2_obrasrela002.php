<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_obras_classe.php");

$clobras = new cl_obras;

$clobras->rotulo->label();
$clrotulo = new rotulocampo;
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$txt_where="1=1 ";


if ($listapropri!=""){
   if (isset($verpropri) and $verpropri=="com"){
       $txt_where= $txt_where." and ob03_numcgm in  ($listapropri)";
   } else {
       $txt_where= $txt_where." and ob03_numcgm not in  ($listapropri)";
   }	 
}  
if ($listaresp!=""){
   if (isset($verres) and $verres=="com"){
       $txt_where= $txt_where." and ob10_numcgm in  ($listaresp)";
   } else {
       $txt_where= $txt_where." and ob10_numcgm not in  ($listaresp)";
   }	 
}  
if ($listatec!=""){
   if (isset($vertec) and $vertec=="com"){
       $txt_where= $txt_where." and ob01_tecnico in  ($listatec)";
   } else {
       $txt_where= $txt_where." and ob01_tecnico not in  ($listatec)";
   }	 
}  
if ($listatipo!=""){
   if (isset($vertipo) and $vertipo=="com"){
       $txt_where= $txt_where." and ob01_tiporesp in  ($listatipo)";
   } else {
       $txt_where= $txt_where." and ob01_tiporesp not in  ($listatipo)";
   }	 
}  

if  ($tipo=='t'){
  $where="";
  $info="TODAS";
}else if ($tipo=='a'){
  $where="and ob01_regular='t'";
  $info="REGULAR";
}else{ 
  $where="and ob01_regular='f'";
  $info="IRREGULAR";
}

if  ($alvara=='t'){
  $where.="";
  $infoalva="";
}else if ($alvara=='c'){
  $where.="and ob04_alvara is not null";
  $infoalva="Obras com Alvará";
}else {
  $where.="and ob04_alvara is null";
  $infoalva="Obras sem Alvará";
  }


$infodataal="";

if (isset($data1)||isset($data2)){

if ( $data1 != "--" && isset($data1) && $data2 != "--" && isset($data2) ) {
  $where = $where." and ob04_data  between '$data1' and '$data2'  ";
  $data1=db_formatar($data1,'d');
  $data2=db_formatar($data2,'d');
  $infodataal="Data do Alvará de: $data1 ate $data2 ";
} else if ($data1!="--"&&isset($data1)){
  $where = $where." and ob04_data >= '$data1'  ";
  $data1=db_formatar($data1,'d');
  $infodataal="Data do Alvará apartir de: $data1  ";
} else if ($data2!="--"&&isset($data2)){
  $where = $where."and ob04_data <= '$data2'   ";  
  $data2=db_formatar($data2,'d');
  $infodataal="Data do Alvará ate: $data2 ";
}

}
if ( $dtobra1 != "--" && $dtobra2 != "--"  ) {
  $where = $where." and ob07_inicio >= '$dtobra1' and ob07_fim <= '$dtobra2'  ";
  $dtobra1=db_formatar($dtobra1,'d');
  $dtobra2=db_formatar($dtobra2,'d');
  $infodt="Período de: $dtobra1 ate $dtobra2 ";
} else if ($dtobra1!="--"){
  $where = $where." and ob07_inicio >= '$dtobra1'  ";
  $dtobra1=db_formatar($dtobra1,'d');
  $infodt="Apartir de: $dtobra1  ";
} else if ($dtobra2!="--"){
  $where = $where."and ob07_fim <= '$dtobra2'   ";  
  $dtobra2=db_formatar($dtobra2,'d');
  $infodt="Ate: $dtobra2 ";
}

$head2 = "RELATÓRIO DE OBRAS ";
$head3 = @$infodt;
$head5 = "$infoalva";
$head4 = "$info";
$head6 = "$infodataal";

$sCampos  = "obras.ob01_codobra,                   ";
$sCampos .= "obras.ob01_regular,                   ";
$sCampos .= "obras.ob01_nomeobra,                  ";
$sCampos .= "obrastiporesp.ob02_descr,             ";
$sCampos .= "tecnico.z01_nome as tecnico,          ";
$sCampos .= "proprietario.z01_nome as proprietario,";
$sCampos .= "responsavel.z01_nome as responsavel,  ";
$sCampos .= "obrasender.ob07_compl,                ";
$sCampos .= "obrasender.ob07_numero,               ";
$sCampos .= "ruas.j14_nome,                        ";
$sCampos .= "bairro.j13_descr,                     ";
$sCampos .= "lote.j34_setor,                       ";
$sCampos .= "lote.j34_quadra,                      ";
$sCampos .= "lote.j34_lote,                        ";
$sCampos .= "obraslote.ob05_idbql,                 ";
$sCampos .= "obraslotei.ob06_lote,                 ";
$sCampos .= "obraslotei.ob06_quadra,               ";
$sCampos .= "obraslotei.ob06_setor,                ";
$sCampos .= "obrasalvara.ob04_alvara,              ";
$sCampos .= "obrasalvara.ob04_data,                ";              
$sCampos .= "obrasalvara.ob04_dataexpedicao        ";              

$sSqlObras = $clobras->sql_query_infob(null, $sCampos, "ob01_codobra", "$txt_where $where");

$result    = $clobras->sql_record($sSqlObras);

if ($clobras->numrows == 0){
  
  $sMsg = _M('tributario.projetos.pro2_obrasrela002.nao_existem_registros');
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
$p=0;
for($x = 0; $x < $clobras->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,"Cod. Obra",1,0,"C",1);
      $pdf->cell(60,$alt,$RLob01_nomeobra,1,0,"C",1);
      $pdf->cell(70,$alt,$RLob02_descr,1,0,"C",1);
      $pdf->cell(65,$alt,'Proprietário',1,0,"C",1); 
      $pdf->cell(65,$alt,'Responsável',1,1,"C",1); 
      $pdf->cell(73,$alt,"",0,0,"C",0);
      $pdf->cell(20,$alt,"Regular",1,0,"C",1);
      $pdf->cell(20,$alt,$RLob04_alvara,1,0,"C",1);
      $pdf->cell(22,$alt,$RLob04_data,1,0,"C",1);
      $pdf->cell(65,$alt,"Técnico",1,0,"C",1);
      $pdf->cell(20,$alt,$RLj34_idbql,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLj34_setor,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLj34_quadra,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLj34_lote,1,1,"C",1); 
      
      
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$ob01_codobra,0,0,"C",$p);
   $pdf->cell(60,$alt,$ob01_nomeobra,0,0,"L",$p);
   $pdf->cell(70,$alt,$ob02_descr,0,0,"L",$p);
   $pdf->cell(65,$alt,$proprietario,0,0,"L",$p);
   $pdf->cell(65,$alt,$responsavel,0,1,"L",$p);
   $pdf->cell(73,$alt,"",0,0,"C",$p);
   if ($ob01_regular=='f'){
     $regular='Não';
   }else $regular='Sim';
   $pdf->cell(20,$alt,$regular,0,0,"C",$p);
   $pdf->cell(20,$alt,$ob04_alvara,0,0,"C",$p);
   $pdf->cell(22,$alt,db_formatar($ob04_dataexpedicao,'d'),0,0,"C",$p);
   $pdf->cell(65,$alt,$tecnico,0,0,"L",$p);
   if ($ob01_regular=='f'){	
     $pdf->cell(20,$alt,"",0,0,"C",$p); 
     $pdf->cell(20,$alt,$ob06_setor,0,0,"C",$p); 
     $pdf->cell(20,$alt,$ob06_quadra,0,0,"C",$p); 
     $pdf->cell(20,$alt,$ob06_lote,0,1,"C",$p); 
   }else{
     $pdf->cell(20,$alt,$ob05_idbql,0,0,"C",$p); 
     $pdf->cell(20,$alt,$j34_setor,0,0,"C",$p); 
     $pdf->cell(20,$alt,$j34_quadra,0,0,"C",$p); 
     $pdf->cell(20,$alt,$j34_lote,0,1,"C",$p); 
   }
   if ($p==0){
     $p=1;
   }else $p=0;
   
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>