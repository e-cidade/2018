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
include("classes/db_rhpessoal_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$clrhpessoal = new cl_rhpessoal;

$xtipo = "'x'";
if ($opcao == 'temsalario' || $opcao == 'salario') {
	$sigla = 'r10_';
	$arquivo = 'pontofs';
	$descrpon = "PONTO DE SALÁRIO";
}
elseif ($opcao == 'temferias' || $opcao == 'ferias') {
	$sigla = 'r29_';
	$arquivo = 'pontofe';
	$xtipo = ' r29_tpp ';
	$descrpon = "PONTO DE FÉRIAS";
}
elseif ($opcao == 'temrescisao' || $opcao == 'rescisao') {
	$sigla = 'r19_';
	$arquivo = 'pontofr';
	$xtipo = ' r19_tpp ';
	$descrpon = "PONTO DE RESCISÃO";
}
elseif ($opcao == 'temadiantamento' || $opcao == 'adiantamento') {
	$sigla = 'r21_';
	$arquivo = 'pontofa';
	$descrpon = "PONTO DE ADIANTAMENTO";
}
elseif ($opcao == 'tem13salario' || $opcao == '13salario') {
	$sigla = 'r34_';
	$arquivo = 'pontof13';
	$descrpon = "PONTO DE 13o. SALÁRIO";
}
elseif ($opcao == 'temcomplementar' || $opcao == 'complementar') {
	$sigla = 'r47_';
	$arquivo = 'pontocom';
	$descrpon = "PONTO COMPLEMENTAR";
}
elseif ($opcao == 'temfixo' || $opcao == 'fixo') {
	$sigla = 'r90_';
	$arquivo = 'pontofx';
	$descrpon = "PONTO FIXO";
}

if (trim($opcao) != '') {
	$sql = "
	          select distinct
                   rh27_rubric,
                   rh27_descr,
                   case 
                     when rh27_pd = 1 then 'PROVENTO' 
                     when rh27_pd = 2 then 'DESCONTO'
                     else 'BASE' 
                   end as pd,
                   ".$sigla."valor as valor,
                   ".$sigla."quant as quant 
	          from ".$arquivo." 
	               inner join rhrubricas on rhrubricas.rh27_rubric = ".$arquivo.".".$sigla."rubric
								                      and rhrubricas.rh27_instit = ".$arquivo.".".$sigla."instit 
	          where     ".$sigla."regist = $matricula 
	                and ".$sigla."anousu = $ano 
	                and ".$sigla."mesusu = $mes
									and ".$sigla."instit = ".db_getsession("DB_instit")." 
	  ";
//	  die($sql);
	$result = pg_exec($sql);
}
$result = pg_exec($sql);

$numrows = pg_numrows($result); 
if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}


$result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($matricula,"rh01_regist,z01_numcgm,z01_nome"));	  	
if($clrhpessoal->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Funcionário não encontrado");
}
db_fieldsmemory($result_registro,0);

$head3 = "PONTO POR REGISTRO";
$head5 = $descrpon;
$head6 = $rh01_regist." - ".$z01_nome;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$totalt = 0;
$valort = 0;
$quantt = 0;
$troca = 1;
$p = 1;
$alt = 4;
 
for($cont=0;$cont<$numrows;$cont++){
  db_fieldsmemory($result,$cont);
  if($p==1){
    $p=0;   
  }else{
    $p=1;
  }
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(20,$alt,"RUBRICA",1,0,"C",1);
    $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
    $pdf->cell(20,$alt,"Prov/Desc",1,0,"C",1);
    $pdf->cell(20,$alt,"Quantidade",1,0,"C",1);
    $pdf->cell(20,$alt,"Valor",1,1,"C",1);

    $troca = 0;
  }
  $pdf->setfont('arial','',6);
  
  $pdf->cell(20,$alt,$rh27_rubric,"T",0,"C",0);
  $pdf->cell(80,$alt,$rh27_descr,"T",0,"L",0);
  $pdf->cell(20,$alt,$pd,"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($quant,"f"),"T",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($valor,"f"),"T",1,"R",0);

  $totalt++;
  $valort+=$valor;
  $quantt+=$quant;
}
//$pdf->cell(278,1,"","T",1,"L",0);    
$pdf->setfont('arial','b',7);
$pdf->cell(100,$alt,"TOTAIS","T",0,"C",0);
$pdf->cell( 20,$alt,$totalt ,"T",0,"R",0);
$pdf->cell( 20,$alt,$quantt ,"T",0,"R",0);
$pdf->cell( 20,$alt,$valort ,"T",1,"R",0);
$pdf->Output();
?>