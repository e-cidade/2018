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
include("classes/db_inicial_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clinicial  = new cl_inicial;
$auxiliar  = new cl_inicial;

$data1="";  $data2="";
@$data1="$data1_ano-$data1_mes-$data1_dia"; 
@$data2="$data2_ano-$data2_mes-$data2_dia"; 
if (strlen($data1) < 7){
  $data1= db_getsession("DB_anousu")."-01-31";
}  
if (strlen($data2) < 7){
  $data2= db_getsession("DB_anousu")."-12-31";
}    
//---------
if (isset($lista)){
  $w="("; 
  $tamanho= sizeof($lista);
  for ($x=0;$x < sizeof($lista);$x++){
    $w = $w."$lista[$x]";
    if ($x < $tamanho-1) {
      $w= $w.",";
    }	
  }  
  $w = $w.")";
}
//-- se não tiver "lista", traz todos os advogados
// tipo = "todos", "foro" e "semforo"
$sql="select  
v57_numcgm,
z01_nome,
v50_inicial,
v50_data,
v70_codforo, 
v53_descr as vara,
v56_data,
v52_codsit,
v52_descr as situacao,
v50_codlocal,
v54_descr as local
from inicial
inner join advog on v57_numcgm=v50_advog
inner join cgm on z01_numcgm = v57_numcgm
left outer join processoforoinicial on processoforoinicial.v71_inicial = inicial.v50_inicial
                                   and processoforoinicial.v71_anulado is false
left outer join processoforo on v70_sequencial = processoforoinicial.v71_processoforo
left outer join vara on v53_codvara = processoforo.v70_vara
left outer join inicialmov on v56_codmov = inicial.v50_codmov
left outer join situacao on v52_codsit = v56_codsit
left outer join localiza on v54_codlocal = inicial.v50_codlocal ";

if (isset($lista)){
  if($ver=="com") 
  $sql.="where v50_instit = ".db_getsession('DB_instit')." and v50_codlocal in $w ";
  else 
  $sql.="where v50_instit = ".db_getsession('DB_instit')." and v50_codlocal not in $w ";      
} else {
  $sql.="where v50_instit = ".db_getsession('DB_instit')." and 1=1";
}  
//-----------
if ($tipo == "todos"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $sql.=" order by v50_codlocal, v50_inicial ";
} else if ($tipo=="foro"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $sql.=" and v70_codforo is not null ";
  $sql.=" order by v50_codlocal, v50_inicial ";  
} else if ($tipo =="semforo"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $sql.=" and v70_codforo is null ";
  $sql.=" order by v50_codlocal, v50_inicial ";
}  
/////////////////////////////
////////////////////////////////////

$res = $auxiliar->sql_record($sql);
// echo $sql;
// db_criatabela($res); 
// exit;
if ($auxiliar ->numrows ==0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
}

$head4 = "Iniciais por Localização";
$head5 = db_formatar($data1,'d')." à ".db_formatar($data2,'d');
//$head6 = "Tipo : $inf";
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','',7);
$tam=4;

//if ($tipo=="todos"){
  if (true){  
    $vlocal="";
    $imprime_header=false;
    for ($x=0; $x <$auxiliar->numrows;$x++){
      db_fieldsmemory($res,$x,true);
      // testa novapagina 
      if ($pdf->gety() > $pdf->h - 40){
        $pdf->addpage("L"); 
        $imprime_header=true;
      }
      if (($imprime_header==true)||($vlocal!=$v50_codlocal))  {
        $imprime_header=false;
        $vlocal = $v50_codlocal;
        $pdf->SetFont('Arial','B',8);
        $pdf->setX(5);
        $pdf->Cell(20,$tam,"$v50_codlocal",'B',0,"R",0);
        $pdf->Cell(80,$tam,"$local",  'B',1,"L",0); // <br>
        $pdf->SetFont('Arial','',7);
        $pdf->setX(5);
        $pdf->Cell(20,$tam,'INICIAL', 'B',0,"R",0);
        $pdf->Cell(18,$tam,'DATA',    'B',0,"C",0); 
        $pdf->Cell(60,$tam,'ADVOGADO','B',0,"L",0); 
        $pdf->Cell(20,$tam,'FORO',    'B',0,"R",0); 
        $pdf->Cell(40,$tam,'VARA',    'B',0,"L",0); 
        $pdf->Cell(20,$tam,'ULT.AND', 'B',0,"C",0); 
        $pdf->Cell(40,$tam,'SITUACAO','B',1,"L",0); // <br>     
      }  
      $pdf->setX(5);
      $pdf->Cell(20,$tam,"$v50_inicial",0,0,"R",0);
      $pdf->Cell(20,$tam,"$v50_data",   0,0,"C",0); 
      $pdf->Cell(60,$tam,"$z01_nome",   0,0,"L",0); 
      $pdf->Cell(20,$tam,"$v70_codforo",0,0,"R",0); 
      $pdf->Cell(40,$tam,"$vara",0,0,"L",0); 
      $pdf->Cell(20,$tam,"$v56_data",0,0,"C",0); 
      $pdf->Cell(40,$tam,"$situacao",0,1,"L",0); // <br>
      
    }// end for
    
  }
  
  //include("fpdf151/geraarquivo.php");
  $pdf->output();
  
  ?>