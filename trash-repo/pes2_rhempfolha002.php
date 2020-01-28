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
include("classes/db_rhempfolha_classe.php");
include("classes/db_rhrubelementoprinc_classe.php");
include("classes/db_rhlotaexe_classe.php");
include("classes/db_rhlotavinc_classe.php");
include("classes/db_rhlotavincele_classe.php");
include("classes/db_rhlotavincativ_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcprojativ_classe.php");
include("classes/db_orctiporec_classe.php");
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clrhlotaexe = new cl_rhlotaexe;
$clrhempfolha = new cl_rhempfolha;
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavincele = new cl_rhlotavincele;
$clrhlotavincativ = new cl_rhlotavincativ;
$clorcdotacao = new cl_orcdotacao;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcprojativ = new cl_orcprojativ;
$clorctiporec = new cl_orctiporec;
db_postmemory($HTTP_POST_VARS);

$passa = false;

if($ponto == 's'){
  $descrarq = "Salário";
  $arquivo = 'gerfsal';
  $sigla   = 'r14_';
  $siglaarq= 'r14';
}elseif($ponto == 'c'){
  $descrarq = "Complementar";
  $arquivo = 'gerfcom';
  $sigla   = 'r48_';
  $siglaarq= 'r48';
}elseif($ponto == 'a'){
  $descrarq = "Adiantamento";
  $arquivo = 'gerfadi';
  $sigla   = 'r22_';
  $siglaarq= 'r22';
}elseif($ponto == 'r'){
  $descrarq = "Rescisão";
  $arquivo = 'gerfres';
  $sigla   = 'r20_';
  $siglaarq= 'r20';
}elseif($ponto == 'f'){
  $descrarq = "Férias";
  $arquivo = 'gerffer';
  $sigla   = 'r31_';
  $siglaarq= 'r31';
}elseif($ponto == 'd'){
  $descrarq = "13o. Salário";
  $arquivo = 'gerfs13';
  $sigla   = 'r35_';
  $siglaarq= 'r35';
}

if($tipo=="n"){
  $descrtipo = "Salário";
}else if($tipo=="p"){
  $descrtipo = "Previdência";
}else{
  $descrtipo = "FGTS";
}

$head2 = "MAPA DA FOLHA DE PAGAMENTO";
$head4 = "ANO / MÊS: $ano / $mes";
$head5 = "ARQUIVO: $descrarq";
$head6 = "TIPO EMPENHO: $descrtipo";
if($mostra=="a"){
  $head8 = "Analítico";
  $groupby = " rh40_orgao, rh40_unidade, rh40_projativ, rh40_codele, rh40_recurso ";
  $campos  = " rh40_orgao, rh40_unidade, rh40_projativ, rh40_codele, rh40_recurso, sum(rh40_provento) as rh40_provento, sum(rh40_desconto) as rh40_desconto ";
}else{
  $head8 = "Sintético";
  $groupby = " rh40_orgao, rh40_unidade, rh40_projativ, rh40_codele, rh40_recurso, rh40_coddot ";
  $campos  = " rh40_orgao, rh40_unidade, rh40_projativ, rh40_codele, rh40_recurso, rh40_coddot, sum(rh40_provento) as rh40_provento, sum(rh40_desconto) as rh40_desconto";
}

$where = ""; 

if (isset($rh40_sequencia)) {
  
  if ($rh40_sequencia != "") {
    
    $where .= " and rh40_sequencia = $rh40_sequencia ";
    
  } 
  
  $where .= " and rh40_sequencia <> 0 ";
  
}

$sSqlEmpenhoFolha = $clrhempfolha->sql_query_file(null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  null,
                                                  "$campos",
                                                  $groupby,
                                                  "   rh40_anousu   = $ano 
                                                  and rh40_mesusu   = $mes 
                                                  and rh40_tipo     = '$tipo' 
                                                  and rh40_siglaarq = '$siglaarq' 
                                                  and rh40_instit   = ".db_getsession("DB_instit")." $where  group by $groupby ");

$result_confirma = $clrhempfolha->sql_record($sSqlEmpenhoFolha);


$numrows_confirma = $clrhempfolha->numrows;
if($clrhempfolha->numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Arquivo não encontrado.");
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();
$total = 0;
$troca = 1;
$alt = 4;

// Variáveis que testam código anterior impresso e se devem ou não imprimir
$iorgao = false;
$oorgao = "";
$iunida = false;
$ounida = "";
$iproja = false;
$oproja = "";
$ieleme = false; 
$oeleme = "";
$irecur = false; 
$orecur = "";

$totalorgao   = 0;
$totalunidade = 0;
$totalgeral   = 0;
$total_totrub = 0;
$tot_rub      = 0;

for($ii=0;$ii<$numrows_confirma;$ii++){
  db_fieldsmemory($result_confirma,$ii);  

  // Busca orgao
  $result_orgao = $clorcorgao->sql_record($clorcorgao->sql_query_file($ano,$rh40_orgao,"o40_codtri,o40_descr"));
  if($clorcorgao->numrows > 0){
    db_fieldsmemory($result_orgao,0);
  }
  ////////////////////////



  // Busca unidade
  $result_unida = $clorcunidade->sql_record($clorcunidade->sql_query_file($ano,$rh40_orgao,$rh40_unidade,"o41_codtri,o41_descr"));
  if($clorcunidade->numrows > 0){
    db_fieldsmemory($result_unida,0);
  }
  ////////////////////////



  // Busca proj/ativ
  $result_proja = $clorcprojativ->sql_record($clorcprojativ->sql_query_file($ano,$rh40_projativ,"o55_projativ,o55_descr"));
  if($clorcprojativ->numrows > 0){
    db_fieldsmemory($result_proja,0);
  }
  ////////////////////////



  // Busca elemento
  $result_eleme = $clorcelemento->sql_record($clorcelemento->sql_query_file($rh40_codele,$ano,"o56_elemento,o56_descr"));
  if($clorcelemento->numrows > 0){
    db_fieldsmemory($result_eleme,0);
  }
  ////////////////////////



  // Busca recurso
  $result_recur = $clorctiporec->sql_record($clorctiporec->sql_query_file($rh40_recurso,"o15_codtri,o15_descr"));
  if($clorctiporec->numrows > 0){
    db_fieldsmemory($result_recur,0);
  }
  ////////////////////////


  $pdf->setfont('arial','b',7);
  if($ounida!=$rh40_orgao.$rh40_unidade || $iunida==true){
    // Imprime unidade
    $linhaT = "";
    $parent = "";
    if($iunida==false){
      $linhaT = "T";
      $parent = "($o40_codtri - $o40_descr)";
    }
    
    if($ounida!=""){
      $pdf->cell(160,$alt,"Total Unidade","B",0,"R",1);
      $pdf->cell( 25,$alt,db_formatar($totalunidade,"f"),"B",1,"R",1);
      $pdf->cell(160, 0.1,"       ",0,1,"L",0);
      $pdf->ln(1);
      $totalunidade = 0;
    }
  }

  $pdf->setfont('arial','b',8);
  if($oorgao!=$rh40_orgao){ 
    // Imprime orgao
    if($oorgao!=""){
      $pdf->cell(160,$alt,"Total Orgao","B",0,"R",1);
      $pdf->cell( 25,$alt,db_formatar($totalorgao,"f"),"B",1,"R",1);
      $pdf->cell(160, 0.1,"       ",0,1,"L",0);
      $pdf->ln(1);
      $totalorgao = 0;
    }
    $pdf->cell( 10,$alt,$o40_codtri,"T",0,"L",1);
    $pdf->cell(175,$alt,$o40_descr ,"T",1,"L",1);
    $oorgao = $rh40_orgao;
    $iunida = true;
    ////////////////////////
  }

  $pdf->setfont('arial','b',7);
  if($ounida!=$rh40_orgao.$rh40_unidade || $iunida==true){
    // Imprime unidade
    $linhaT = "";
    $parent = "";
    if($iunida==false){
      $linhaT = "T";
      $parent = "($o40_codtri - $o40_descr)";
    }
    $pdf->cell(10,$alt,$o40_codtri.$o41_codtri,"B$linhaT",0,"L",1);
    $pdf->cell(90,$alt,$o41_descr             ,"B$linhaT",0,"L",1);
    $pdf->setfont('arial','b',4);
    $pdf->cell(85,$alt,$parent               ,"B$linhaT",1,"R",1);
    $ounida = $rh40_orgao.$rh40_unidade;
    $iunida = false;
    $iproja = true; 
    ////////////////////////
  }

  



  $pdf->setfont('arial','b',7);  
  if($oproja!=$rh40_projativ || $iproja==true){
    // Imprime proj/ativ
    $pdf->cell( 10,$alt,$o55_projativ,0,0,"L",0);
    $pdf->cell(150,$alt,$o55_descr   ,0,1,"L",0);
    $oproja = $rh40_projativ;
    $iproja = false;
    $ieleme = true;
    ////////////////////////
  } 



  $pdf->setfont('arial','b',6);
  if($oeleme!=$rh40_codele || $ieleme==true){
    // Imprime elemento
    $pdf->cell( 20,$alt,$o56_elemento,0,0,"L",0);
    $pdf->cell( 80,$alt,$o56_descr   ,0,0,"L",0);
    $impdotac = "";
    if(isset($rh40_coddot) && trim($rh40_coddot) != ""){
      $impdotac = "Dotação :  ".$rh40_coddot;
    }
    $pdf->cell( 35,$alt,$impdotac ,0,1,"L",0);
    $oeleme = $rh40_codele;
    $ieleme = false;
    $irecur = true;
    ////////////////////////
  }


  
  $pdf->setfont('arial','',6);
  if($orecur!=$rh40_recurso || $irecur==true){
    // Imprime recurso
    $tot_rub_ = $rh40_provento-$rh40_desconto;
    if(      $tot_rub < 0 ){
       $tot_rub = $tot_rub_+ $tot_rub;
    }else{
       $tot_rub = $tot_rub_;
    }
    $pdf->cell(  5,$alt,"                                              ",0,0,"C",0);
    $pdf->cell(120,$alt,$o15_codtri." - ".$o15_descr                    ,0,0,"L",0);
    $pdf->cell( 30,$alt,db_formatar(($tot_rub_),"f"),0,0,"R",0);
    $pdf->cell( 30,$alt,db_formatar(($tot_rub),"f"),0,1,"R",0);

    if($mostra == "a"){
      $valortotalrubricas = 0;
      $result_rubricas = $clrhempfolha->sql_record(
                                                   $clrhempfolha->sql_query_rubr(
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 null,
                                                                                 "
                                                                                  rh40_rubric as codrubrica, 
                                                                                  rh27_descr as desrubrica, 
                                                                                  (sum(rh40_provento) - sum(rh40_desconto)) as provmenosdesc 
                                                                                 ",
                                                                                 "
                                                                                  rh40_rubric
                                                                                 ",
                                                                                 "
                                                                                      rh40_anousu=".$ano." 
                                                                                  and rh40_mesusu=".$mes." 
                                                                                  and rh40_tipo='".$tipo."' 
                                                                                  and rh40_siglaarq='".$siglaarq."' 
                                                                                  and rh40_orgao = ".$rh40_orgao." 
                                                                                  and rh40_unidade = ".$rh40_unidade." 
                                                                                  and rh40_projativ = ".$rh40_projativ." 
                                                                                  and rh40_recurso = ".$rh40_recurso."
                                                                                  and rh40_codele = ". $rh40_codele." 
                                                                                  group by
										      rh40_rubric,
										      rh27_descr
                                                                                 "
                                                                                )
                                                  );
      for($ix = 0; $ix<$clrhempfolha->numrows; $ix++){
        db_fieldsmemory($result_rubricas, $ix);
	$bcabecrodap = "0";
        $pdf->setfont('arial','',6);
        $pdf->cell(  5,$alt,"                              ",$bcabecrodap,0,"C",0);
        $pdf->cell(120,$alt,"*  ".$codrubrica." - ".$desrubrica   ,$bcabecrodap,0,"L",0);
        $pdf->cell( 30,$alt,db_formatar($provmenosdesc,"f") ,$bcabecrodap,0,"R",0);
        $pdf->cell( 30,$alt,"                              ",$bcabecrodap,1,"R",0);
	$valortotalrubricas += $provmenosdesc;
      }
      if($clrhempfolha->numrows > 0){
        $pdf->setfont('arial','b',6);
        $pdf->cell(125,$alt,""                                  ,"T",0,"R",0);
        $pdf->cell( 30,$alt,db_formatar($valortotalrubricas,"f"),"T",0,"R",0);
        $pdf->cell( 30,$alt,""                                  ,"T",1,"R",0);
      }
    }

    $orecur = $rh40_recurso;
    $irecur = false;
    ////////////////////////
    $totalunidade += ($rh40_provento-$rh40_desconto);
    $totalorgao   += ($rh40_provento-$rh40_desconto);
    $totalgeral   += ($rh40_provento-$rh40_desconto);
    if($tot_rub > 0){
      $total_totrub += $tot_rub;
    }
  }
}
$pdf->setfont('arial','b',8);
$pdf->ln(3);
$pdf->cell(160,$alt,"Valor total","B",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($totalunidade,"f"),"B",1,"R",1);
$pdf->cell(160,0.2,"","T",1,"C",0);

$pdf->cell(160,$alt,"Valor total","B",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($totalorgao,"f"),"B",1,"R",1);
$pdf->cell(160,0.2,"","T",1,"C",0);

$pdf->ln(3);
$pdf->cell(135,$alt,"Valor Geral","B",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($totalgeral,"f"),"B",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($total_totrub,"f"),"B",1,"R",1);
$pdf->cell(160,0.2,"","T",1,"C",0);


$sql1 = ($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,null,"$campos",$groupby,"rh40_anousu=$ano and rh40_mesusu=$mes and rh40_tipo='$tipo' and rh40_siglaarq='$siglaarq' and rh40_instit = ".db_getsession("DB_instit")." $where group by $groupby "));

$sql2 = "select rh40_recurso, 
                o15_descr,
		sum( rh40_provento - rh40_desconto ) as total 
         from ($sql1) as x 
	      inner join orctiporec on o15_codigo = rh40_recurso
	 group by rh40_recurso,
	          o15_descr
	 order by rh40_recurso"; 

//echo $sql2; exit;

$result2 = pg_query($sql2);

$numrows2 = pg_numrows($result2);

$tot_rec = 0;

$alt = 5;

$pdf->setfont('arial','b',9);
$pdf->ln(5);
$pdf->cell(0,$alt,"TOTAL POR RECURSO",0,1,"L",0);

$pdf->setfont('arial','',9);

for($xi=0;$xi<$numrows2;$xi++){
  db_fieldsmemory($result2,$xi);  
  $pdf->cell(15,$alt,$rh40_recurso,0,0,"R",0);
  $pdf->cell(75,$alt,$o15_descr,0,0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total,"f"),0,1,"R",0);
  $tot_rec += $total;
}
$pdf->cell(15,$alt,'',"T",0,"R",0);
$pdf->cell(75,$alt,'',"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_rec,"f"),"T",1,"R",0);

$pdf->Output();
?>