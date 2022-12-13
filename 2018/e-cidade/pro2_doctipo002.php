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
include("classes/db_procdoctipo_classe.php");
$clprocdoctipo = new cl_procdoctipo;
$clrotulo = new rotulocampo;
$clprocdoctipo->rotulo->label();
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$msg="";
$param="";  
$param2="";  
$where="";
$passou=false;
//echo "lista = $lista ordem = $order"; 
if(isset($lista) && trim($lista)!=""){
  $passou=true;
  if($param_where=="S"){
    $msg=" com o(s) código(s) de tipo de andamento a seguir ($lista)";
    $param = " in ";
  }else if($param_where=="N"){
    $msg=" sem o(s) código(s) de tipo de andamento a seguir ($lista)";
    $param = " not in ";    
  }
  $where="where
                p57_codigo $param($lista)";
}
$sql="select
	    p51_codigo,
	    p51_descr,
	    p56_descr
      from
	    procdoctipo
	       inner join tipoproc on p51_codigo = p57_codigo 
	       inner join procdoc  on p56_coddoc = p57_coddoc
      $where
      order by $order
	    ";

//echo "<br>  $sql";exit;
$result = $clprocdoctipo->sql_record($sql);
$numrows=$clprocdoctipo->numrows;
if($numrows==0){  
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum documento encontrado$msg.");
}
$pdf = new PDF();
$pdf->Open();
$head5 = "DOCUMENTOS POR TIPO DE PROCESSO";
$pdf->addpage();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt = 4;
$c=1;
$total=0;
$recebendo="";
$quantprocesso = 0;
for($i = 0; $i<$numrows; $i++){
  if($c==1){
    $c=0;
  }else{
    $c=1;
  }
  db_fieldsmemory($result,$i);
 
  if($recebendo!=$p51_codigo || $i==0 || $pdf->gety() > $pdf->h - 30){
    if(trim($recebendo)!="" && $recebendo!=$p51_codigo){
      $pdf->setfont('arial','b',8);
      $pdf->cell(190,$alt,"TOTAL DE DOCUMENTOS  :  ".$total,"T",1,"L",0);
      $total=0;
    }
    if($pdf->gety() > $pdf->h - 30){
      $pdf->addpage();
    }
    $pdf->ln(5);
    $pdf->setfont('arial','b',8);
    $pdf->cell(190,$alt,"Tipo do processo: $p51_descr - $p51_codigo","RLT",1,"L",1);
    $pdf->cell(190,$alt,"Documentos:","RLB",1,"L",1);
    $c=0;
    $quantprocesso ++;
  }
  $recebendo = $p51_codigo;

  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$p56_descr,"RL",1,"L",$c);
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,"TOTAL DE DOCUMENTOS  :  ".$total,"T",1,"L",0);
$pdf->ln(6);
$pdf->cell(190,$alt,"TOTAL DE PROCESSOS  :  ".$quantprocesso,"TB",1,"L",0);

/*
  $pdf->ln(10);
  if((($param_where=="S") || ($param_where=="N")) && $passou==true){
    if($param_where=="S"){
      $pdf->cell(190,$alt,"TIPOS SELECIONADOS",  1,1,"L",1);
    }else if($param_where=="N"){
      $pdf->cell(190,$alt,"TIPOS NÃO SELECIONADOS",  1,1,"L",1);
    }
    $result_tipo2 = $cltipcalc->sql_record($cltipcalc->sql_query_file(null,"q81_codigo,q81_abrev","q81_codigo"," q81_codigo $param2 ($lista)"));
    $numrows_tipo=$cltipcalc->numrows;
    for($i=0;$i<$numrows_tipo;$i++){
      db_fieldsmemory($result_tipo2,$i);
      $pdf->setfont('arial','',7);
      $pdf->cell(30,$alt,"Código: $q81_codigo", "L",0,"L",0);
      $pdf->cell(160,$alt,"Descrição: $q81_abrev", "R",1,"L",0);
    }
    $pdf->cell(190,0.2,"", "T",1,"L",1);
  }else{
    $pdf->cell(190,$alt,"TODOS OS TIPOS SELECIONADOS",  0,1,"L",0);
  }
*/
$pdf->Output();
?>