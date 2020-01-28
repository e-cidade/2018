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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "RELATÓRIO DE FICHAS";
$head5 = "ORDEM ".strtoupper($ordem);;
if(!isset($desc)){
  $desc = "asc";
}
$sqlq = " select * from ficha order by $ordem $desc";
$rslt = pg_exec($sqlq);

$numrows = pg_numrows($rslt);

if($numrows==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(225);
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt   = 4;

$COR = 1;
for($x=0;$x<$numrows;$x++){
  db_fieldsmemory($rslt,$x);
  if($COR==1){
    $COR = 0;
  }else{
    $COR = 1;
  }
  if($pdf->gety()>$pdf->h-30||$troca!=0){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
     
    $pdf->cell(20,$alt,"Código"      ,1,0,"C",1);
    $pdf->cell(80,$alt,"Nome"        ,1,0,"C",1);
    $pdf->cell(25,$alt,"CPF"         ,1,0,"C",1);
    $pdf->cell(25,$alt,"Identidade"  ,1,0,"C",1);
    $pdf->cell(25,$alt,"Órgão/UF"       ,1,0,"C",1);
    $pdf->cell(20,$alt,"Dt. nasc."      ,1,0,"C",1);
    $pdf->cell(80,$alt,"Área"        ,1,1,"C",1);

    $pdf->cell(80,$alt,"Endereço"    ,1,0,"C",1);
    $pdf->cell(15,$alt,"APTO"         ,1,0,"C",1);
    $pdf->cell(20,$alt,"CEP"         ,1,0,"C",1);
    $pdf->cell(40,$alt,"Município/UF",1,0,"C",1);
    $pdf->cell(25,$alt,"Telefone"    ,1,0,"C",1);
    $pdf->cell(25,$alt,"Celular"     ,1,0,"C",1);
    $pdf->cell(70,$alt,"E-mail"       ,1,1,"C",1);
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$codigo    ,0,0,"C",$COR);
  $pdf->cell(80,$alt,$nome      ,0,0,"L",$COR);
  $pdf->cell(25,$alt,$cpf       ,0,0,"C",$COR);
  $pdf->cell(25,$alt,$identidade,0,0,"C",$COR);
  $pdf->cell(25,$alt,$orgao.'/'.$uf_orgao  ,0,0,"C",$COR);
  $pdf->cell(20,$alt,$dtnasc_dia."/".$dtnasc_mes."/".$dtnasc_ano,0,0,"C",$COR);
  $pdf->cell(80,$alt,$area      ,0,1,"L",$COR);

  $pdf->cell(80,$alt,$endereco.", ".$numero,0,0,"J",$COR);
  $pdf->cell(15,$alt,$apt       ,0,0,"C",$COR);
  $pdf->cell(20,$alt,$cep       ,0,0,"C",$COR);
  $pdf->cell(40,$alt,$municipio." / ".$uf,0,0,"J",$COR);
  $pdf->setfont('arial','',6);
  $pdf->cell(25,$alt,strtolower($telefone),0,0,"C",$COR);
  $pdf->cell(25,$alt,strtolower($celular) ,0,0,"C",$COR);
  $pdf->multicell(70,$alt,$email     ,0,"J",$COR);

  $pdf->cell(275,2,"","T",1,"C",($COR==1?"0":"1"));

  $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(275,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>