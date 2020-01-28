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
$clrotulo->label('e91_codcheque');
$clrotulo->label('e91_codmov');
$clrotulo->label('e60_codemp');
$clrotulo->label('e82_codord');
$clrotulo->label('z01_nome');
$clrotulo->label('e83_descr');
$clrotulo->label('e83_sequencia');
$clrotulo->label('e91_valor');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELATÓRIO PARA A CONFERÊNCIA";
$head4 = "DA BAIXA DOS EMPENHOS";
$head6 = "DATA :";
$dbwhere = ' 1=1 and e80_instit = ' . db_getsession("DB_instit");
if(isset($e50_codord) && $e50_codord != '' && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e82_codord >=$e50_codord and e82_codord <= $e50_codord02 ";
}else if(  (empty($e50_codord) || ( isset($e50_codord) && $e50_codord == '')   )  && isset($e50_codord02) && $e50_codord02 != '' ){
  $dbwhere .=" and e82_codord <= $e50_codord02 ";
}else if(isset($e50_codord) && $e50_codord != '' ){
  $dbwhere .=" and e82_codord=$e50_codord ";
}


if(isset($e60_codemp) && $e60_codemp != '' ){
  $dbwhere .=" and e60_codemp = $e60_codemp ";
}

if(isset($e60_numemp) && $e60_numemp != '' ){
  $dbwhere .=" and e60_numemp = $e60_numemp ";
}

if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
}

if(isset($dtfi) && $dtfi !=''){
 $dtfi =  str_replace("_","-",$dtfi);
 $dbwhere .= " and e86_data = '$dtfi'";
}

if(isset($e83_codtipo) && $e83_codtipo != ''){
  $dbwhere .= " and e85_codtipo = $e83_codtipo"; 
}

if(isset($cheque) && $cheque != ''){
  $dbwhere .= "and e91_cheque =  $cheque";
}


if(isset($e80_codage) && $e80_codage != ''){
  $dbwhere .= "and e81_codage =  $e80_codage";
}

$sql = "
select e91_codcheque,
       e91_codmov,
       e60_codemp,
       e82_codord,
       z01_nome,
       e83_descr,
       e83_sequencia,
       e91_valor 
from empageconfche 
     inner join empageconf on empageconf.e86_codmov = empageconfche.e91_codmov and e91_ativo is true 
     inner join empagemov on empagemov.e81_codmov = empageconfche.e91_codmov 
     inner join empage  on  empage.e80_codage = empagemov.e81_codage
     inner join empempenho on empempenho.e60_numemp = empagemov.e81_numemp 
     left join empord on empord.e82_codmov = empagemov.e81_codmov 
     left join cgm on cgm.z01_numcgm = empempenho.e60_numcgm 
     inner join empagepag on empagepag.e85_codmov = empagemov.e81_codmov 
     inner join empagetipo on empagetipo.e83_codtipo = empagepag.e85_codtipo 
     left join corconf on e91_codcheque = k12_codmov and corconf.k12_ativo is true
where $dbwhere
  and k12_codmov  is null
       ";

//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cheques para a opção escolhida. Verifique!');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$tot_reg = 0;
$tot_val = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$cor = 1;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(12,$alt,$RLe91_codcheque,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe91_codmov,1,0,"C",1);
      $pdf->cell(15,$alt,$RLe60_codemp,1,0,"C",1);
      $pdf->cell(10,$alt,$RLe82_codord,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(30,$alt,$RLe83_descr,1,0,"C",1);
      $pdf->cell(15,$alt,'Cheque',1,0,"C",1);
      $pdf->cell(20,$alt,$RLe91_valor,1,1,"C",1);
      $troca = 0;
      $cor = 1;
   }
   if($cor == 1)
     $cor = 0;
   else
     $cor = 1;
   $pdf->setfont('arial','',6);
   $pdf->cell(12,$alt,$e91_codcheque,0,0,"C",$cor);
   $pdf->cell(20,$alt,$e91_codmov,0,0,"C",$cor);
   $pdf->cell(15,$alt,$e60_codemp,0,0,"C",$cor);
   $pdf->cell(10,$alt,$e82_codord,0,0,"C",$cor);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$cor);
   $pdf->cell(30,$alt,$e83_descr,0,0,"L",$cor);
   $pdf->cell(15,$alt,$e83_sequencia,0,0,"C",$cor);
   $pdf->cell(20,$alt,db_formatar($e91_valor,'f'),0,1,"R",$cor); 
   $tot_reg ++;
   $tot_val += $e91_valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(172,$alt,'TOTAL  :  '.$tot_reg.' REGISTROS',"T",0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_val,'f'),"T",1,"R",0);

$pdf->Output();
   
?>