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
	$sql  = "select v57_numcgm,						                                                                                     ";
	$sql .= "				z01_nome,							                                                                                     ";
	$sql .= "				v50_inicial,					                                                                                     ";
	$sql .= "				v50_data,							                                                                                     ";
	$sql .= "				v70_codforo,                                                                                               ";
	$sql .= "				v70_vara,   					                                                                                     ";
	$sql .= "				v53_descr as vara,		                                                                                     ";
	$sql .= "				v56_data,							                                                                                     ";
	$sql .= "				v52_codsit,						                                                                                     ";
	$sql .= "				v52_descr as situacao,                                                                                     ";
	$sql .= "				v50_codlocal,					                                                                                     ";
	$sql .= "				v54_descr as local,		                                                                                     ";
	$sql .= "				v50_id_login,					                                                                                     ";
	$sql .= "			  nome									 	 	 	 	 	 	 	 	 	 	 		                                                             ";
	$sql .= "	 from inicial								 	 	 	 	 	 	 	 	 	 	 		                                                             ";
	$sql .= "			  inner join advog			         on v57_numcgm                       = v50_advog			                       ";
	$sql .= "			  inner join cgm				         on z01_numcgm                       = v57_numcgm		                         ";
	$sql .= "			  inner join db_usuarios         on id_usuario                       = inicial.v50_id_login			             ";
	$sql .= "			  left  join processoforoinicial on processoforoinicial.v71_inicial  = inicial.v50_inicial	 				         ";
  $sql .= "                                     and processoforoinicial.v71_anulado is false                                 ";
	$sql .= "       left  join processoforo        on processoforo.v70_sequencial      = processoforoinicial.v71_processoforo  ";
	$sql .= "			  left  join vara						     on v53_codvara	                     = processoforo.v70_vara                 ";
	$sql .= "			  left  join inicialmov			     on v56_codmov		                   = inicial.v50_codmov				             ";
	$sql .= "			  left  join situacao				     on v52_codsit		                   = v56_codsit								             ";
	$sql .= "			  left  join localiza				     on v54_codlocal                     = inicial.v50_codlocal			             ";

if (isset($lista)){
  if($ver=="com") 
  $sql.="where v50_instit = ".db_getsession('DB_instit')." and v50_id_login in $w ";
  else 
  $sql.="where v50_instit = ".db_getsession('DB_instit')." and v50_id_login not in $w ";      
} else {
  $sql.="where v50_instit = ".db_getsession('DB_instit')." ";
}  

if ($selSituacao == "1"){
  $sql .= " and v50_situacao = 1 ";
  $cabAtiv = "Somente iniciais Ativas";
}else if( $selSituacao == 2){
  $sql .= " and v50_situacao = 2 ";
  $cabAtiv = "Somente iniciais Anuladas";
}else{
  $cabAtiv = "Iniciais Ativas e Anuladas";
}


if ($tipo == "todos"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $inf = "Tipo: Todos";
} else if ($tipo=="foro"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $sql.=" and v70_codforo is not null ";
  $inf = "Tipo: Processos do Foro";
} else if ($tipo =="semforo"){
  $sql.=" and inicial.v50_data >='$data1' and inicial.v50_data <='$data2' ";
  $sql.=" and v70_codforo is null ";
  $inf = "Tipo: Processos sem Foro";
}  

switch($selOrdem){
   case "i":
     $sql .= " order by v50_id_login, v50_inicial ";
     $cabOrdem = "Inicial";
   break;
   case "d":
     $sql .= " order by v50_id_login, v50_data";
     $cabOrdem = "Data";
   break;
   case "f":
     $sql .= " order by v50_id_login, v70_codforo";
     $cabOrdem = "Foro";
   break;
   case "s":
     $sql .= " order by v50_id_login, situacao";
     $cabOrdem = "Situação";
   break;
}

$res = $auxiliar->sql_record($sql);

if ($auxiliar ->numrows ==0) {
  $sMsg = _M('tributario.juridico.jur2_inicialusuario002.nao_existem_dados');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg} ");  
}

$head2 = "Iniciais por Usuários";
$head3 = "Período : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
$head4 = $inf;
$head5 = "Ordenado por ".$cabOrdem;
$head6 = $cabAtiv;


$pdf = new PDF();			// abre a classe
$pdf->Open();					// abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage();		  // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','',7);
$tam=4;

  if (true){  
    
    $totalIni    = 0;
    $subTotalIni = 0;
		$id="";
    $imprime_header=false;
    for ($x=0; $x <$auxiliar->numrows;$x++){
      db_fieldsmemory($res,$x,true);
      // testa novapagina 
      if ($pdf->gety() > $pdf->h - 40){
        $pdf->addpage(); 
        $imprime_header=true;
      }
      
			if(($imprime_header==true)||($id!=$v50_id_login))  {
        if($x != 0 && $id!=$v50_id_login){
          $pdf->SetFont('Arial','B',6);
          $pdf->Cell(0,$tam,"","T",0,"R",0);
          $pdf->ln(2);
          $pdf->setX(175);
          $pdf->Cell(20,$tam,"TOTAL DE INICIAIS :" ,0,0,"R",0);
          $pdf->Cell(10,$tam,$subTotalIni          ,0,1,"L",0);
          $totalIni   += $subTotalIni;
          $subTotalIni = 0;
        }

				$imprime_header=false;
        $id = $v50_id_login;
        $pdf->SetFont('Arial','B',8);
        $pdf->setX(10);
        $pdf->Cell(20,$tam,"$v50_id_login",'B',0,"R",0);
        $pdf->Cell(80,$tam,"$nome",  'B',1,"L",0); // <br>
        $pdf->SetFont('Arial','',7);
        $pdf->setX(10);
        $pdf->Cell(20,$tam,'INICIAL', '1',0,"C",1);
        $pdf->Cell(20,$tam,'DATA',    '1',0,"C",1); 
        $pdf->Cell(50,$tam,'ADVOGADO','1',0,"C",1); 
        $pdf->Cell(20,$tam,'FORO',    '1',0,"C",1); 
        $pdf->Cell(30,$tam,'VARA',    '1',0,"C",1); 
        $pdf->Cell(20,$tam,'ULT.AND', '1',0,"C",1); 
        $pdf->Cell(30,$tam,'SITUACAO','1',1,"C",1); // <br>     
        $pre = 1;
      }
		  
			if($pre == 1){
        $pre = 0;
      }else{
        $pre = 1;
      }
      
			$pdf->setX(10);
      $pdf->Cell(20,$tam,"$v50_inicial",0,0,"C",$pre);
      $pdf->Cell(20,$tam,"$v50_data"   ,0,0,"C",$pre); 
      $pdf->Cell(50,$tam,"$z01_nome"   ,0,0,"L",$pre); 
      $pdf->Cell(20,$tam,"$v70_codforo",0,0,"C",$pre); 
      $pdf->Cell(30,$tam,"$vara"			 ,0,0,"L",$pre); 
      $pdf->Cell(20,$tam,"$v56_data"	 ,0,0,"C",$pre); 
      $pdf->Cell(30,$tam,"$situacao"	 ,0,1,"L",$pre); // <br>
      
    $subTotalIni++;
    }// end for
  
    $pdf->SetFont('Arial','B',6);
    $pdf->Cell(0,$tam,"","T",0,"R",0);
    $pdf->ln(2);
    $pdf->setX(175);
    $pdf->Cell(20,$tam,"TOTAL DE INICIAIS :",0,0,"R",0);
    $pdf->Cell(10,$tam,$subTotalIni         ,0,1,"L",0);
    $totalIni   += $subTotalIni;

    $pdf->SetFont('Arial','B',6);
    $pdf->ln(2);
    $pdf->setX(175);
    $pdf->Cell(20,$tam,"TOTAL DE GERAL :" ,0,0,"R",0);
    $pdf->Cell(10,$tam,$totalIni          ,0,1,"L",0);

	}
  
  $pdf->output();
  
  ?>