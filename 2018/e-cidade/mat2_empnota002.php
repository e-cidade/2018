<?
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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_empnotaele_classe.php"));
include(modification("classes/db_empnota_classe.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clempnotaele = new cl_empnotaele;
$clempnota = new cl_empnota;

$clempnotaele->rotulo->label();
$clempnota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('e60_numemp');
$clrotulo->label('e60_codemp');
$clrotulo->label('nome');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$clrotulo->label('');

$erro=0;

$where='1=1';
$info="Periodo total";
$txt_where="1=1";
if ($listafor!=""){
  if (isset($verfor) and $verfor=="com"){
    $txt_where= $txt_where." and e60_numcgm in  ($listafor)";
  } else {
    $txt_where= $txt_where." and e60_numcgm not in  ($listafor)";
  }	 
}  
if ($listausu!=""){
  if (isset($verusu) and $verusu=="com"){
    $txt_where= $txt_where." and e69_id_usuario in  ($listausu)";
  } else {
    $txt_where= $txt_where." and e69_id_usuario not in  ($listausu)";
  }	 
}  
if ($listaele!=""){
  if (isset($verele) and $verele=="com"){
    $where= $where." and e70_codele in  ($listaele)";
  } else {
    $where= $where." and e70_codele not in  ($listaele)";
  }	 
}  

if (($data!="--")&&($data1!="--")) {
  $txt_where = $txt_where." and e69_dtnota  between '$data' and '$data1'  ";
  $data=db_formatar($data,"d");
  $data1=db_formatar($data1,"d");
  $info="De $data até $data1.";
} else if ($data!="--"){
  $txt_where = $txt_where." and e69_dtnota >= '$data'  ";
  $data=db_formatar($data,"d");
  $info="Apartir de $data.";
} else if ($data1!="--"){
  $txt_where = $txt_where."and e69_dtnota <= '$data1'   ";  
  $data1=db_formatar($data1,"d");
  $info="Até $data1.";
}
if (($data2!="--")&&($data3!="--")) {
  $txt_where = $txt_where." and e69_dtrecebe  between '$data2' and '$data3'  ";
} else if ($data2!="--"){
  $txt_where = $txt_where." and e69_dtrecebe >= '$data2'  ";
} else if ($data3!="--"){
  $txt_where = $txt_where."and e69_dtrecebe <= '$data3'   ";  
}

if ($opcao=='a'){
  $txt_where = $txt_where."";  
  $info2 = 'Opção de impressão: Todas';
}else if ($opcao=='b') {

  $txt_where = $txt_where." and e70_vlrliq > 0 ";

  $info2 = 'Opção de impressão: Liquidadas';
}else if ($opcao=='c'){
  $txt_where = $txt_where." and e70_vlrliq = 0";
  $info2='Opção de impressão: Não liquidadas';
}

$head3 = "Notas";
$head5 = "$info";
$head6 = "$info2";

$result=$clempnota->sql_record($clempnota->sql_query_emp(null,'*',null,"$txt_where "));


if ($clempnota->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
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

for($x = 0; $x < $clempnota->numrows;$x++){
   db_fieldsmemory($result,$x);
   $result_itens = $clempnotaele->sql_record($clempnotaele->sql_query(null,null,'*',null,"e70_codnota=$e69_codnota and  $where"));
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLe69_codnota,1,0,"C",1);
      $pdf->cell(25,$alt,$RLe69_numero,1,0,"C",1);
      $pdf->cell(15,$alt,$RLe60_codemp,1,0,"C",1);
      $pdf->cell(15,$alt,$RLe60_numemp,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe69_id_usuario,1,0,"C",1);
      $pdf->cell(60,$alt,$RLnome,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe69_dtnota,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe69_dtrecebe,1,1,"C",1);
      $pdf->cell(185,$alt,'',0,0,"C",0);
      $pdf->cell(20,$alt,'Cod. Elemen.',1,0,"C",1);
      $pdf->cell(25,$alt,$RLe70_valor,1,0,"C",1);
      $pdf->cell(25,$alt,$RLe70_vlranu,1,0,"C",1);
      $pdf->cell(25,$alt,$RLe70_vlrliq,1,1,"C",1);
      $troca = 0;
   }
   if ($clempnotaele->numrows==0){
     $erro++;
   }else{
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,$alt,$e69_codnota,0,0,"C",0);
   $pdf->cell(25,$alt,$e69_numero,0,0,"C",0);
   $pdf->cell(15,$alt,$e60_codemp,0,0,"C",0);
   $pdf->cell(15,$alt,$e69_numemp,0,0,"C",0);
   $pdf->cell(20,$alt,$z01_numcgm,0,0,"C",0);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(20,$alt,$e69_id_usuario,0,0,"C",0);
   $pdf->cell(60,$alt,$nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($e69_dtnota,'d'),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($e69_dtrecebe,'d'),0,1,"C",0);
   $total++;
   for($i = 0; $i < $clempnotaele->numrows;$i++){
     db_fieldsmemory($result_itens,$i);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
       $pdf->addpage('L');
       $pdf->setfont('arial','b',8);
       $pdf->cell(15,$alt,$RLe69_codnota,1,0,"C",1);
       $pdf->cell(25,$alt,$RLe69_numero,1,0,"C",1);
       $pdf->cell(15,$alt,$RLe60_codemp,1,0,"C",1);
       $pdf->cell(15,$alt,$RLe60_numemp,1,0,"C",1);
       $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
       $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
       $pdf->cell(20,$alt,$RLe69_id_usuario,1,0,"C",1);
       $pdf->cell(60,$alt,$RLnome,1,0,"C",1);
       $pdf->cell(20,$alt,$RLe69_dtnota,1,0,"C",1);
       $pdf->cell(20,$alt,$RLe69_dtrecebe,1,1,"C",1);
       $pdf->cell(185,$alt,'',0,0,"C",0);
       $pdf->cell(20,$alt,'Cod. Elemen.',1,0,"C",1);
       $pdf->cell(25,$alt,$RLe70_valor,1,0,"C",1);
       $pdf->cell(25,$alt,$RLe70_vlranu,1,0,"C",1);
       $pdf->cell(25,$alt,$RLe70_vlrliq,1,1,"C",1);

       
       $troca = 0;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(185,$alt,'',0,0,"C",0);
     $pdf->cell(20,$alt,$e70_codele,0,0,"C",0);
     $pdf->cell(25,$alt,db_formatar($e70_valor,'f'),0,0,"R",0);
     $pdf->cell(25,$alt,db_formatar($e70_vlranu,'f'),0,0,"R",0);
     $pdf->cell(25,$alt,db_formatar($e70_vlrliq,'f'),0,1,"R",0);
   }
 
   $num=$clempnota->numrows - 1;
   
   if ($x==$num){
   }else{
     $pdf->cell(280,$alt,"","T",1,"C",0);
   }
   }
}
if ($erro==$clempnota->numrows){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>