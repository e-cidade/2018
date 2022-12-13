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

set_time_limit(0);
include("libs/db_sql.php");
require("fpdf151/pdf.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);
//exit;
if(isset($data)){
  if(!checkdate(substr($data,5,2),substr($data,8,2),substr($data,0,4))){
     db_redireciona('db_erros.php?fechar=true&db_erro=Data Inválida ( '.$data.' ). Verifique!');
  }
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=Data Inválida ( '.$data.' ). Verifique!');
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "SECRETARIA DA FAZENDA";
$head3 = "TOTAL DOS DÉBITOS POR CONTRIBUINTE";
//$head4 = "JUROS E MULTA SIMULADOS";
$linha = 60;
$TPagina = 40;
if ($ordemtipo == 'asc'){
   $ascend = 'Ascendente';
 } else {
   $ascend = 'Descendente';
}
if ($numerolista != ''){
     $limite = ' limit '.$numerolista;
	 $head6 = 'Total de Listados : '.$numerolista.'  em ordem '.$ascend;
}else {
     $limite = '';
	 $head6 = 'Total de Listados : Todos em ordem '.$ascend;
}

$sql = "select data,numcgm as um ,z01_nome as dois , 
                round(sum(valor),2) as valor  
          from devedores 
               inner join cgm on z01_numcgm = numcgm 
          where valor between $valorminimo and $valormaximo
            and data = '$data'
          group by data,numcgm,z01_nome order by $ordem $ordemtipo $limite";
//echo $sql;exit;
$head7 = 'Valores entre :  '.trim(db_formatar($valorminimo,'f')).'   e   '.trim(db_formatar($valormaximo,'f'));
$head8 = 'Posição em : '.db_formatar($data,'d');
$result1 = pg_exec($sql);

$ttvlrhis=0;
$ttvlrcor=0;
$ttvlrjuros=0;
$ttvlrmulta=0;
$ttvlrdesconto=0;
$tttotal=0;
$preenc = 0;
$xborda = 0;
$resultnrows= pg_numrows($result1);
$pdf->SetFillColor(220);
$col  = array();
$cor = 240;
//$data = array();
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(220);
$pdf->Cell(15,05,'Numcgm',"TB",0,"C",1);
$pdf->Cell(70,05,'Nome',"TB",0,"C",1);
$pdf->Cell(30,05,"Valor","TB",1,"R",1);
for($yy=0;$yy<$resultnrows;$yy++){
  db_fieldsmemory($result1,$yy);
//  if ($yy % 2 == 0){
//      $preenc = 1;
//  }else {
//      $preenc = 0;
//  }
  if ($pdf->h - 30 < $pdf->gety()){
     $linha = 0;
     $pdf->AddPage();
     $pdf->SetFont('Arial','B',8);
     $pdf->SetFillColor(220);
     $pdf->Cell(15,05,'Numcgm',"TB",0,"C",1);
     $pdf->Cell(70,05,'Nome',"TB",0,"C",1);
     $pdf->Cell(30,05,"Valor","TB",1,"R",1);
  }
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(15,04,$um,$xborda,0,"R",0);
  $pdf->Cell(70,04,$dois,$xborda,0,"L",$preenc);
  $pdf->Cell(30,04,db_formatar($valor,'f'),$xborda,1,"R",$preenc);
  $pdf->SetFont('Arial','',6);
  $z01_numcgm='';
  $sql2 = "

           select sum(valor) as val1,
                  case when a.k00_matric is not null
                       then 'M-'||k00_matric end as matric,
                  case when b.k00_inscr  is not null
                       then 'I-'||k00_inscr end as inscr,
                  z01_numcgm,
                  z01_nome
           from devedores
                left outer join (select distinct on (k00_numpre) k00_numpre,k00_matric from arrematric where k00_numpre = devedores.numpre and devedores.numcgm=$um and devedores.data='$data') as a on a.k00_numpre=numpre
                left outer join promitente on j41_matric = k00_matric and j41_tipopro = 't'
                left outer join cgm on z01_numcgm = j41_numcgm 
                left outer join arreinscr b on b.k00_numpre = numpre
           where data='$data'
             and numcgm = $um
             and tipo not in (1,2,3,27,33)
           group by matric,inscr,z01_numcgm,z01_nome
           order by val1 desc
          ";
//echo $sql2;exit;
  $result2 = pg_exec($sql2);
  for($ii = 0;$ii < pg_numrows($result2);$ii++){
     db_fieldsmemory($result2,$ii);
     if ($pdf->h - 30 < $pdf->gety()){
        $linha = 0;
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',8);
        $pdf->SetFillColor(220);
        $pdf->Cell(15,05,'Numcgm',"TB",0,"C",1);
        $pdf->Cell(70,05,'Nome',"TB",0,"C",1);
        $pdf->Cell(30,05,"Valor","TB",1,"R",1);
        $pdf->SetFont('Arial','',6);
     }
     $pdf->cell(10,04,'',0,0,"L",0);
     if ($matric != ''){
        $pdf->cell(15,04,$matric,0,0,"L",0);
     }elseif($inscr != ''){
        $pdf->cell(15,04,$inscr,0,0,"L",0);
     }else{
        $pdf->cell(15,04,'',0,0,"L",0);
     }
     if ($z01_numcgm != ''){
         $pdf->cell(15,04,db_formatar($val1,'f'),0,0,"R",0);
         $pdf->multicell(0,04,'Promitente : '.$z01_numcgm.' - '.$z01_nome,0,"L",0);
     }else{
     $pdf->cell(15,04,db_formatar($val1,'f'),0,1,"R",0);

     }
  } 
  $pdf->multicell(0,06,'',"T","C",0); 
  $tttotal       += $valor;
}
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(15,05,'',"TB",0,"R",1);
  $pdf->Cell(70,05,'TOTAL',"TB",0,"L",1);
  $pdf->Cell(30,05,db_formatar($tttotal,'f'),"TB",1,"R",1);

$pdf->Output();
?>