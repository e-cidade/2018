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
 
include("fpdf151/pdfwebseller.php");
include("dbforms/db_funcoes.php");
include("classes/db_mer_cardapioaluno_classe.php");
$clmer_cardapioaluno = new cl_mer_cardapioaluno;
$escola              = db_getsession("DB_coddepto");

$campos  = " ed60_i_codigo, ";
$campos .= " ed47_v_nome, ";
$campos .= " me01_c_nome, ";
$campos .= " me01_f_versao, ";
$campos .= " me27_c_nome, ";
$campos .= " ed11_c_descr, ";
$campos .= " ed52_c_descr, ";
$campos .= " ed57_c_descr, ";
$campos .= " ed57_i_codigo, ";
$campos .= " ed60_i_numaluno, ";
$campos .= " ed60_i_aluno, ";
$campos .= " ed10_c_descr, ";
$campos .= " ed11_i_codigo, ";
$campos .= " ed10_i_codigo, ";
$campos .= " (select me40_i_repeticao from mer_cardapioalunorepet";
$campos .= "  where me40_i_cardapiodia = me12_i_codigo";
$campos .= "  and me40_i_turma = ed57_i_codigo) as repeticao,";
$campos .= " me12_d_data";
$condicao  = " 1=1";
if ($turma!="") {
  $condicao  .= " AND ed60_i_turma in ($turma)";	
}
if ($refeicao!="") {
  $condicao .= " AND me12_i_codigo = $refeicao";
} else {
  $condicao .= " AND me12_d_data BETWEEN '$datainicio' AND '$datafim'";	
}
$condicao .= " AND me01_i_tipocardapio = $cardapio";
$condicao .= " AND ed57_i_escola = $escola";

$result = $clmer_cardapioaluno->sql_record(
           $clmer_cardapioaluno->sql_query("",
                                           $campos,
                                           "ed10_i_codigo,ed11_i_sequencia,ed57_c_descr,to_ascii(ed47_v_nome)",
                                           "$condicao"
                                          ));
                                          echo pg_errormessage();
if ($clmer_cardapioaluno->numrows==0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
  
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO CONSUMO DE REFEIÇÃO POR ALUNOS";

$pdf->ln(5);
$pdf->addpage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->cell(15,4,"Matricula",1,0,"C",1);
$pdf->cell(15,4,"Código",1,0,"C",1);
$pdf->cell(5,4,"N°",1,0,"C",1);
$pdf->cell(80,4,"Nome do Aluno",1,0,"C",1);
$pdf->cell(30,4,"Turma",1,0,"C",1);
$pdf->cell(30,4,"Etapa",1,0,"C",1);
$pdf->cell(35,4,"Calendário",1,0,"C",1);
$pdf->cell(20,4,"Data",1,0,"C",1);
$pdf->cell(50,4,"Refeição",1,1,"C",1);
$total_qtde = 0;
$total_repet = 0;
$sub_qtde = 0;
$priensino = "";
$prietapa = "";
$priturma = pg_result($result,0,"ed57_i_codigo");
for ($c=0; $c<$clmer_cardapioaluno->numrows; $c++) {
	
  db_fieldsmemory($result,$c);
  if ($priturma!=$ed57_i_codigo) {
    
    $pdf->setfillcolor(255);
    $pdf->cell(280,4,"Turma ".trim(pg_result($result,$c-1,"ed57_c_descr"))." - Quantidade: $sub_qtde - Repeticões: ".pg_result($result,$c-1,"repeticao"),1,1,"L",1);
    $priturma = $ed57_i_codigo;
    $total_repet += pg_result($result,$c-1,"repeticao");
    $sub_qtde = 0; 
    
  }
  if ($priensino!=$ed10_i_codigo) {
  	
    $pdf->setfillcolor(235);
    $pdf->cell(280,4,$ed10_c_descr,1,1,"C",1);
    $priensino = $ed10_i_codigo;
    
  }
  if ($prietapa!=$ed11_i_codigo) {
    
    $pdf->setfillcolor(235);
  	$pdf->cell(280,4,$ed11_c_descr,1,1,"C",1);
    $prietapa = $ed11_i_codigo;
    
  }
  if ($pdf->gety() > $pdf->h - 30) {

    $pdf->addpage("L");
    $pdf->cell(15,4,"Matricula",1,0,"C",1);
    $pdf->cell(15,4,"Código",1,0,"C",1);
    $pdf->cell(5,4,"N°",1,0,"C",1);
    $pdf->cell(80,4,"Nome do Aluno",1,0,"C",1);
    $pdf->cell(30,4,"Turma",1,0,"C",1);
    $pdf->cell(30,4,"Etapa",1,0,"C",1);
    $pdf->cell(35,4,"Calendário",1,0,"C",1);
    $pdf->cell(20,4,"Data",1,0,"C",1);
    $pdf->cell(50,4,"Refeição",1,1,"C",1);
    
  }
  $pdf->setfillcolor(255);
  $pdf->setfont('arial','',8);
  $pdf->setfillcolor(255);
  $pdf->cell(15,4,$ed60_i_codigo,1,0,"C",1);
  $pdf->cell(15,4,$ed60_i_aluno,1,0,"C",1);
  $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",1);
  $pdf->cell(80,4,$ed47_v_nome,1,0,"L",1);
  $pdf->cell(30,4,$ed57_c_descr,1,0,"C",1);
  $pdf->cell(30,4,$ed11_c_descr,1,0,"C",1);
  $pdf->cell(35,4,$ed52_c_descr,1,0,"C",1);
  $pdf->cell(20,4,db_formatar($me12_d_data,"d"),1,0,"C",1);
  $pdf->cell(50,4,$me01_c_nome." - Versão: ".$me01_f_versao,1,1,"L",1);
  $sub_qtde++;  
  $total_qtde++;
  
}
$pdf->setfillcolor(255);
$pdf->cell(280,4,"Turma ".$ed57_c_descr." - Quantidade: $sub_qtde - Repeticões: ".$repeticao,1,1,"L",1);
$total_repet += $repeticao;
$pdf->cell(280,4,"",0,1,"L",1);
$pdf->cell(280,4,"Quantidade: $total_qtde - Repeticões: ".$total_repet." - TOTAL: ".($total_repet+$total_qtde),1,1,"L",1);


$pdf->Output();
?>