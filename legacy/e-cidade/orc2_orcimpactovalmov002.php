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
include("classes/db_orcimpactovalmovmes_classe.php");
include("classes/db_orcimpactovalmov_classe.php");
include("classes/db_orcimpactomov_classe.php");
include("classes/db_orcimpactorecmov_classe.php");
include("classes/db_orcimpactorecmovmes_classe.php");
include("classes/db_orcimpactoger_classe.php");
include("classes/db_orcimpactoperiodo_classe.php");
include("classes/db_orctiporec_classe.php");

$clorcimpactovalmovmes = new cl_orcimpactovalmovmes;
$clorcimpactovalmov    = new cl_orcimpactovalmov;
$clorcimpactoperiodo   = new cl_orcimpactoperiodo;
$clorcimpactomov       = new cl_orcimpactomov;
$clorcimpactorecmov    = new cl_orcimpactorecmov;
$clorcimpactorecmovmes = new cl_orcimpactorecmovmes;
$clorcimpactoger       = new cl_orcimpactoger;
$clorctiporec          = new cl_orctiporec;

$clorcimpactovalmovmes->rotulo->label();
$clrotulo = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($recurso) && $recurso !='' && $recurso !=0){
  $result = $clorctiporec->sql_record($clorctiporec->sql_query_file($recurso));
  $numrows = $clorctiporec->numrows;
  if($numrows==0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Recuros inválido!');
  }else{
    $head3 = "Recurso:$recurso";
  }
}

$head2 = "IMPACTO ORÇAMENTÁRIO";
$head3 = "Número: $codimpger";

if(isset($recurso) && $recurso!=0 && $recurso!=''){
  $head4 = "Recurso: $recurso";
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$alt="5";
$troca = 1;

//---------------------------------------------------------------------------------
//pega o Orcimpactoger
//$result  = $clorcimpactoger->sql_record($clorcimpactoger->sql_query_file(null,"*","","o62_codimpger=$codimpger"));
$result  = $clorcimpactoger->sql_record($clorcimpactoger->sql_query_file(null,"sum(o62_ativo) as o62_ativo,sum(o62_passivo) as o62_passivo,o62_data","o62_data","o62_codimpger=$codimpger group by o62_data"));
$numrows = $clorcimpactoger->numrows;
if($numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
}else{
  db_fieldsmemory($result,0);
} 
//---------------------------------------------------------------------------------

//---------------------------------------------------------------------------------
//pega os periodos
$result=$clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file(null,"o96_anoini,o96_anofim",""));
$numrows = $clorcimpactoperiodo->numrows;
if($numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Cadastre o  período para Impacto Orçamentário!');
}else{
  db_fieldsmemory($result,0);
}
//---------------------------------------------------------------------------------

$arr_dadosimprime = Array();
for($i=$o96_anoini;$i<=$o96_anofim;$i++){
  $arr_data = split("/",db_formatar($o62_data,"d"));
  $dataorcimpactoger = $arr_data[2];
  if($i==$dataorcimpactoger){
    $arr_dadosimprime["A"][$i] = $o62_ativo;
    $arr_dadosimprime["B"][$i] = $o62_passivo;
    $data_imprime = $i;
  }else{
    unset($data_imprime);
  }

  if(isset($data_imprime)){
    $arr_dadosimprime["C"][$i] = $arr_dadosimprime["A"][$data_imprime] - $arr_dadosimprime["B"][$data_imprime];
  }else{
    $arr_dadosimprime["C"][$i] = $arr_dadosimprime["F"][$i-1];
  }

  $dbwhere=" o69_codimpger <= $codimpger and o69_exercicio = $i ";
  if(isset($recurso) && $recurso !='' && $recurso !=0){
    $dbwhere .= " and  o69_codigo =$recurso ";
  }

  //-------------------------------------------------------------------------------
  //Pega os valores de receitas orcimpactorecmov
  $sql01     = $clorcimpactorecmov->sql_query(null,"sum(o69_valor) as o69_valor,o69_exercicio","o69_exercicio","$dbwhere  group by o69_exercicio");
  $result01  = $clorcimpactorecmov->sql_record($sql01);
  $numrows01 = $clorcimpactorecmov->numrows;
  //-------------------------------------------------------------------------------

  db_fieldsmemory($result01,0);
  $arr_dadosimprime["D"][$i] = $o69_valor;

  $dbwhere= " o63_codimpger <= $codimpger and  o64_exercicio = $i ";
  if(isset($recurso) && $recurso !=0 && $recurso !=''){
    $dbwhere .= " and  o67_codigo =$recurso ";
  }

  //-------------------------------------------------------------------------------
  //Pega os valores de despesas orcimpactovalmov
  $arr_cods =  array();
  $sql02= $clorcimpactovalmov->sql_query_soma(null,"sum(o64_valor) as o64_valor,o64_exercicio","o64_exercicio","$dbwhere group by o64_exercicio");
  $result02  = $clorcimpactovalmov->sql_record($sql02);
  $numrows02 = $clorcimpactovalmov->numrows; 
  //-------------------------------------------------------------------------------

  db_fieldsmemory($result02,0);
  
  $arr_dadosimprime["E"][$i] = $o64_valor;
  $arr_dadosimprime["F"][$i] = $arr_dadosimprime["C"][$i] + $arr_dadosimprime["D"][$i] - $arr_dadosimprime["E"][$i];
  $arr_dadosimprime["G"][$i] = $arr_dadosimprime["F"][$i] - $arr_dadosimprime["C"][$i];
}
  //   Valores usados no array $arr_dadosimprime[ANO]['Valores abaixo']
  // ______________________________________________ 
  //|           |                                  | 
  //|   Valor   |           Descrição              |
  //|___________|__________________________________| 
  //|           |                                  |
  //|    [A]    | Ativo Financeiro Inicial         |
  //|    [B]    | Passivo Financeiro Inicial       |
  //|    [C]    | Situação Financeira Inicial      |
  //|    [D]    | Receita de Interferência Ativas  |
  //|    [E]    | Despesas e Interferências        |
  //|    [F]    | Situação Financeira Projetada    |
  //|    [G]    | Situação Orçamentária Projetada  |
  //|___________|__________________________________|
  $arr_descricao["A"] = "Ativo Financeiro Inicial";
  $arr_descricao["B"] = "Passivo Financeiro Inicial";
  $arr_descricao["C"] = "Situação Financeira Inicial";
  $arr_descricao["D"] = "Receita de Interferência Ativas";
  $arr_descricao["E"] = "Despesas e Interferências";
  $arr_descricao["F"] = "Situação Financeira Projetada";
  $arr_descricao["G"] = "Situação Orçamentária Projetada";
  
  reset($arr_dadosimprime);  // vai pro inicio da variável 
  for($i=0;$i<count($arr_dadosimprime);$i++){
    $pdf->setfillcolor(235);
    $passa = false;
    $descrusu =key($arr_dadosimprime);
    $pdf->setfont('arial','b',10);
    if($troca!=0 || $pdf->gety()>$pdf->h-30){
      $pdf->cell(90,$alt,"Identificação",1,0,"C",1);
      reset($arr_dadosimprime["C"]);
      for($ii=0;$ii<count($arr_dadosimprime["C"]);$ii++){
	$impanousu = key($arr_dadosimprime["C"]);
	if($ii!=2){
          $pdf->cell(30,$alt,$impanousu,1,0,"C",1);
	}else{
          $pdf->cell(30,$alt,$impanousu,1,1,"C",1);
	}
        next($arr_dadosimprime["C"]);
      }
      $passa = true;
      $troca = 0;
    }

    $pdf->cell(90,$alt,$arr_descricao[$descrusu],1,0,"L",0);
    $pdf->setfont('arial','',10);
    reset($arr_dadosimprime[$descrusu]);
    for($ii=0;$ii<count($arr_dadosimprime[$descrusu]);$ii++){
      $anousu = key($arr_dadosimprime[$descrusu]);  // Recebe o ano
      if($ii!=2 && $descrusu != "A" && $descrusu != "B"){
        $valorimprime = db_formatar($arr_dadosimprime[$descrusu][$anousu],"f");
	if($valorimprime<0){
          $pdf->setfillcolor(215);
	}else{
          $pdf->setfillcolor(600);
	}
        $pdf->cell(30,$alt,$valorimprime,1,0,"R",1);
      }else{
	$valorimprime = db_formatar($arr_dadosimprime[$descrusu][$anousu],"f");
	if($valorimprime<0){
	  $pdf->setfillcolor(215);
	}else{
	  $pdf->setfillcolor(600);
	}
	if($descrusu=="A" || $descrusu=="B"){
          $pdf->cell(30,$alt,$valorimprime,1,0,"R",1);
	  $pdf->setfillcolor(235);
	  $pdf->cell(30,$alt,"",1,0,"R",1);
	  $pdf->cell(30,$alt,"",1,1,"R",1);
	}else{
          $pdf->cell(30,$alt,$valorimprime,1,1,"R",1);
	}
      }
      next($arr_dadosimprime[$descrusu]);
    }
    next($arr_dadosimprime);                    // vai pro próximo
  }

$pdf->Output();
?>