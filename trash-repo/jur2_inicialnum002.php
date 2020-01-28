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

$sql  = " select v57_numcgm,																												                                           ";
$sql .= "				 z01_nome,																													                                           ";
$sql .= "				 v50_inicial,																												                                           ";
$sql .= "				 v50_data,																													                                           ";
$sql .= "				 v70_codforo,																												                                           ";
$sql .= "				 v70_vara,    																											                                           ";
$sql .= "				 v53_descr as vara,																									                                           ";
$sql .= "				 v56_data,																													                                           ";
$sql .= "				 v52_codsit,																												                                           ";
$sql .= "				 v52_descr as situacao,																							                                           ";
$sql .= "				 v50_codlocal,																											                                           ";
$sql .= "				 v54_descr as local,																								                                           ";
$sql .= "				 v50_id_login,																											                                           ";
$sql .= "				 nome																																                                           ";
$sql .= "	  from inicial																														                                           ";
$sql .= "			   inner join advog				     	  on v57_numcgm		                      =	v50_advog									             ";
$sql .= "			   inner join cgm						      on z01_numcgm		                      = v57_numcgm								             ";
$sql .= "			   inner join db_usuarios		      on id_usuario		                      = inicial.v50_id_login			             ";
$sql .= "			   left  join processoforoinicial on processoforoinicial.v71_inicial	  = inicial.v50_inicial								     ";
$sql .= "                                      and processoforoinicial.v71_anulado is false                                    ";
$sql .= "        left  join processoforo        on processoforo.v70_sequencial        = processoforoinicial.v71_processoforo   ";
$sql .= "			   left  join vara						    on v53_codvara	                      = processoforo.v70_vara                  ";
$sql .= "			   left  join inicialmov			    on v56_codmov		                      = inicial.v50_codmov				             ";
$sql .= "			   left  join situacao				    on v52_codsit		                      = v56_codsit								             ";
$sql .= "			   left  join localiza				    on v54_codlocal                       = inicial.v50_codlocal			             ";

$sql .= "where v50_instit = ".db_getsession('DB_instit') ;

if (($inicial01!="")&&($inicial02=="" )){ // somente inicial 1 preenchida
  $sql .="and v50_inicial = $inicial01 ";
} else if ($inicial01 !="" && $inicial02!=""){
  $sql .="and v50_inicial >= $inicial01 and v50_inicial <= $inicial02";
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
  $sql.=" and inicialmov.v56_data >='$data1' and inicialmov.v56_data <='$data2' ";
	$inf = "Tipo : Todos";
} else if ($tipo=="foro"){
  $sql.=" and inicialmov.v56_data >='$data1' and inicialmov.v56_data <='$data2' ";
  $sql.=" and v70_codforo is not null ";
	$inf = "Tipo : Processos do Foro";
} else if ($tipo =="semforo"){
  $sql.=" and inicialmov.v56_data >='$data1' and inicialmov.v56_data <='$data2' ";
  $sql.=" and v70_codforo is null ";
	$inf = "Tipo : Processos sem Foro";
}  

$sql .= " order by v50_inicial ";


$res = $auxiliar->sql_record($sql);

if ($auxiliar ->numrows ==0) {
  $sMsg = _M('tributario.juridico.jur2_inicialnum002.nao_existem_dados');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");  
}

$head2 = "Relatorio de Iniciais";
$head3 = "Período :".db_formatar($data1,'d')." à ".db_formatar($data2,'d');
$head4 = $inf;
$head5 = $cabAtiv;

$pdf = new PDF(); 		// abre a classe
$pdf->Open(); 				// abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('P'); 	// adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','',7);
$tam=4;
$tottotal = 0;
  
	if (true){  
    $inicial="";
    $imprime_header=false;
    for ($x=0; $x <$auxiliar->numrows;$x++){
      db_fieldsmemory($res,$x,true);
      // testa novapagina 
      if ($pdf->gety() > $pdf->h - 40){
        $pdf->addpage("P"); 
        $imprime_header=true;
      }
      if (($imprime_header==true)||($inicial!=$v50_inicial))  {
        $imprime_header=false;
        $inicial = $v50_inicial;
        $pdf->SetFont('Arial','B',8);
        $pdf->Ln();
        $pdf->setX(10);
        $pdf->Cell(20,$tam,"$v50_inicial",0,0,"R",0);
        $pdf->Cell(80,$tam,"$v50_data"   ,0,1,"L",0); // <br>
        $pdf->SetFont('Arial','B',7);
        $pdf->setX(10);
        $pdf->Cell(65,$tam,'ADVOGADO',1,0,"C",1); 
        $pdf->Cell(20,$tam,'FORO',    1,0,"C",1); 
        $pdf->Cell(40,$tam,'VARA',    1,0,"C",1); 
        $pdf->Cell(20,$tam,'ULT.AND', 1,0,"C",1); 
        $pdf->Cell(45,$tam,'SITUACAO',1,1,"C",1); // <br>     
      }  
      $pdf->SetFont('Arial','',7);
			$pdf->setX(10);
      $pdf->Cell(65,$tam,"$z01_nome"   ,0,0,"L",0); 
      $pdf->Cell(20,$tam,"$v70_codforo",0,0,"C",0); 
      $pdf->Cell(40,$tam,"$vara"			 ,0,0,"L",0); 
      $pdf->Cell(20,$tam,"$v56_data"	 ,0,0,"C",0); 
      $pdf->Cell(45,$tam,"$situacao"	 ,0,1,"L",0); // <br>
      $tottotal++;
    }// end for
    
  }
  
  $pdf->Cell(40,$tam,"",0,1,"R",0); // <br>
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell($pdf->w-40,$tam,"Total de Registros - $tottotal ",0,1,"R",0); // <br>
  //include("fpdf151/geraarquivo.php");
  $pdf->output();
  
  ?>