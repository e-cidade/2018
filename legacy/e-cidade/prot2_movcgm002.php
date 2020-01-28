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
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_cadast');
$clrotulo->label('z01_login');
$clrotulo->label('z01_hora');


db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$txt_where="1=1";
$info="Total";
$info1="";
$info2="";
$info3="";

if ($listausu!=""){
  if (isset($ver) and $ver=="com"){
      $txt_where.=" and login1 in  ($listausu)";
  } else {
      $txt_where.=" and login1 not in  ($listausu)";
  }	 
}  

if (($data!="--")&&($data1!="--")) {
   $txt_where = $txt_where." and data  between '$data' and '$data1'  ";
   $data=db_formatar($data,"d");
   $data1=db_formatar($data1,"d");
   $info="De $data até $data1.";
}else if ($data!="--"){
   $txt_where = $txt_where." and data >= '$data'  ";
   $data=db_formatar($data,"d");
   $info="Apartir de $data.";
}else if ($data1!="--"){
   $txt_where = $txt_where."and data <= '$data1'   ";  
   $data1=db_formatar($data1,"d");
   $info="Até $data1.";
}

$escolhe="";
$vir="";


if ($inc=='true'){
   $escolhe.="'I'";
   $info1.="Incluidos";
   $vir=",";
}
if ($alt=='true'){
  $escolhe.=$vir."'A'";
  $info2.="Alterados";
  $vir=",";
}
if ($exc=='true'){
  $escolhe.=$vir."'E'";
  $info3.="Excluidos ";
}


$txt_where.="and tipo in ($escolhe) ";


$sql = "select * from 
               (
	       select z05_numcgm as numcgm,z05_nome as nomecgm,z05_cadast as data,z05_hora as hora,z05_login as login1,z05_tipo_alt as tipo from cgmalt
                  union
               select z01_numcgm as numcgm,z01_nome as nomecgm,z01_cadast as data,z01_hora as hora,z01_login as login1,'I' as tipo from cgm 
	       )
	as x inner join db_usuarios on db_usuarios.id_usuario=x.login1 where $txt_where
        order by x.data,x.hora";

$result=pg_exec($sql);

$head2 = "Movimentações dos Cgm's";
$head3 = "Periodo $info";
$head4 = "$info1";
$head5 = "$info2";
$head6 = "$info3";

if (pg_numrows($result)==0){
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
$p=0;

for($x = 0; $x <pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(35,$alt,$RLz01_cadast,1,0,"C",1);
      $pdf->cell(35,$alt,$RLz01_hora,1,0,"C",1);
      $pdf->cell(25,$alt,$RLz01_login,1,0,"C",1);
      $pdf->cell(20,$alt,'Tipo',1,1,"C",1);
      $p=0;   
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$numcgm,0,0,"C",$p);
   $pdf->cell(60,$alt,$nomecgm,0,0,"L",$p);
   $pdf->cell(35,$alt,db_formatar($data,'d'),0,0,"C",$p);
   $pdf->cell(35,$alt,$hora,0,0,"C",$p);
   $pdf->cell(25,$alt,$login,0,0,"L",$p);
   if ($tipo=='I'){
     $tipocgm='Incluido';
   }else if ($tipo=='A'){
     $tipocgm='Alterado';
   }else if ($tipo=='E'){
     $tipocgm='Excluido';
   }   
   $pdf->cell(20,$alt,$tipocgm,0,1,"C",$p);
   if ($p==0){
     $p=1;
   }else $p=0;
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();

?>