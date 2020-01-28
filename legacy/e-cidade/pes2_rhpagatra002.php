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
include("libs/db_libpessoal.php");
include("classes/db_rhpagatra_classe.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_rhpessoal_classe.php");
$clrhpagatra = new cl_rhpagatra;
$clrhpagocor = new cl_rhpagocor;
$clrhpessoal = new cl_rhpessoal;
$clrhpagatra->rotulo->label();
$clrhpagocor->rotulo->label();
$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('r70_estrut');
$clrotulo->label('r70_codigo');
$clrotulo->label('rh60_codigo');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "RELATÓRIO DE SALÁRIOS ATRASADOS";
if(isset($anousu) && trim($anousu) != "" && isset($mesusu) && trim($mesusu) != ""){
  $head5 = "COMPETÊNCIA : ".$anousu." / ".$mesusu;
}

db_sel_cfpess(db_anofolha(),db_mesfolha(),"r11_databaseatra");

$setaand = "";
$dbhaving = "";
if(isset($anousu) && trim($anousu) != ""){
  $dbhaving.= " rh57_ano = ".$anousu;
  $setaand  = " and ";
}
if(isset($mesusu) && trim($mesusu) != ""){
  $dbhaving.= $setaand." rh57_mes = ".$mesusu;
  $setaand  = " and ";
}
if(isset($regisi) && trim($regisi) != "" && isset($regisf) && trim($regisf) != ""){
  $dbhaving.= $setaand." rh57_regist between ".$regisi." and ".$regisf;
}else if(isset($regisi) && trim($regisi) != ""){
  $dbhaving.= $setaand." rh57_regist >= ".$regisi;
}else if(isset($regisf) && trim($regisf) != ""){
  $dbhaving.= $setaand." rh57_regist <= ".$regisf;
}else if(isset($selecion) && trim($selecion) != ""){
  $dbhaving.= $setaand." rh57_regist in (".$selecion.")";
}
$head6 = "Imprimir";
if($comsaldo == "t"){
  $head6 .= " todos";
}else{
  $head6 .= " somente com saldo";
  $dbhaving.= $setaand." rh57_saldo > 0 ";
  $setaand  = " and ";
}

$head7 = "Com funcionários na justiça";
if(!isset($impjus)){
	$dbhaving.= $setaand." rh61_regist is null ";
  $head7 = "Sem funcionários na justiça";
	$setaand  = " and ";
}

if($conta == 1){
  $head8 = "Com conta bancária";
	$dbhaving.= $setaand." rh44_seqpes is not null";
}else if($conta == 2){
  $head8 = "Sem conta bancária";
	$dbhaving.= $setaand." rh44_seqpes is null";
}

$dbgroupby = "rh57_seq, 
              rh57_ano, 
              rh57_mes,
              rh57_regist,
              z01_nome,
              rh57_valorini,
              r70_estrut,
              r70_descr,
              rh60_descr";
$sql_atrasados = $clrhpagatra->sql_query_relatorio(null,
                                                   "
                                                    distinct 
                                                    rh57_seq,
                                                    rh57_ano,
                                                    rh57_mes,
                                                    rh57_regist,
                                                    z01_nome, 
                                                    rh57_valorini,
                                                    rh57_saldo, 
                                                    r70_estrut,
                                                    r70_descr,
                                                    rh60_descr
                                                   ",
                                                   "rh57_regist, rh57_ano, rh57_mes",
                                                    $dbhaving,
																										null,
																										null,
																										db_anofolha(),
																										db_mesfolha()
                                                  );
$result_atrasados = $clrhpagatra->sql_record($sql_atrasados);
$numrows_atrasados = $clrhpagatra->numrows;

if($numrows_atrasados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique os dados informados, nenhum atraso encontrado.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$entrar = true;
$alt = 4;

$regist_ant = 0;
$anousu_ant = 0;
$mesusu_ant = 0;

function imprime_cabecalho(){
  global $pdf, $alt, $RLr70_codigo, $RLrh60_codigo, $RLrh57_valorini, $RLrh57_saldo;
  $pdf->addpage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(15,$alt,"Ano / Mês",1,0,"C",1);
  $pdf->cell(15,$alt,"Estrutural",1,0,"C",1);
  $pdf->cell(60,$alt,"Lotação",1,0,"C",1);
  $pdf->cell(60,$alt,"Tipo de atraso",1,0,"C",1);
  $pdf->cell(20,$alt,$RLrh57_valorini,1,0,"C",1);
  $pdf->cell(20,$alt,$RLrh57_saldo,1,1,"C",1);

  $pdf->cell(30,$alt,"Data","TLB",0,"C",0);
  $pdf->cell(60,$alt,"Tipo Ocorr.","TB",0,"C",0);
  $pdf->cell(80,$alt,"Descrição","TB",0,"C",0);
  $pdf->cell(20,$alt,"Valor","RTB",1,"C",0);
  return true;
}

function imprime_nome($regist, $nome){
  global $pdf, $alt;
  $pdf->ln(2);
  $pdf->setfont('arial','b',8);
  $pdf->cell(15,$alt,$regist,"LTB",0,"C",1);
  $pdf->cell(0,$alt,$nome,"RTB",1,"L",1);
  return true;
}

function imprime_atraso($ano, $mes, $estrut, $descrlota, $descrtipo, $valini, $valsaldo,$movimentacao=null){
  global $pdf, $alt;

  $tamanho_cell = 60;
  if($movimentacao != null && trim($movimentacao) != ""){
    $tamanho_cell = 45;
  }

  $pdf->ln(1);
  $pdf->setfont('arial','b',7);
  $pdf->cell(15,$alt,$ano." / ".$mes,0,0,"C",1);
  $pdf->cell(15,$alt,$estrut,0,0,"C",1);
  $pdf->cell(60,$alt,$descrlota,0,0,"L",1);
  $pdf->cell($tamanho_cell,$alt,$descrtipo,0,0,"L",1);
  if($movimentacao != null && trim($movimentacao) != ""){
    $pdf->setfont('arial','b',5);
    $pdf->cell(15,$alt,$movimentacao,0,0,"R",1);
    $pdf->setfont('arial','b',7);
  }
  $pdf->cell(20,$alt,db_formatar($valini,"f"),0,0,"R",1);
  $pdf->cell(20,$alt,db_formatar($valsaldo,"f"),0,1,"R",1);
}

$total_geral = 0;
$quant_geral = 0;
$total_funci = 0;
$quant_funci = 0;

for($x=0; $x<$numrows_atrasados; $x++){
  db_fieldsmemory($result_atrasados,$x);
  if($pdf->gety() > $pdf->h - 30 || $entrar == true){
    imprime_cabecalho();
    $entrar = false;
    $mregist = true;
  }

  if($regist_ant != $rh57_regist || $mregist == true){
    if($regist_ant != $rh57_regist && trim($regist_ant) != 0){
      $pdf->setfont('arial','',7);
      $pdf->cell(100,$alt,"Totalização por funcionário:","LTB",0,"L",1);
      $pdf->cell(70,$alt,$quant_funci." atrasos, saldo total de R$","TB",0,"R",1);
      $pdf->cell(20,$alt,db_formatar($total_funci,"f"),"RTB",1,"R",1);
      $pdf->ln(2);
      $total_funci = 0;
      $quant_funci = 0;
      if($quebra == 0){
         imprime_cabecalho();
      }
    }
    imprime_nome($rh57_regist, $z01_nome);
    $regist_ant = $rh57_regist;
    $mregist = false;
  }

  $dbwhere = " rh58_seq = ".$rh57_seq." and rh58_valor > 0";
  $sql_ocor = $clrhpagocor->sql_query(null," rh59_descr, case when rh59_tipo = 'S' then 'Somar' else 'Subtrair' end as rh59_tipo, rh58_valor, rh58_data ","rh58_data",$dbwhere);
  $result_ocor = $clrhpagocor->sql_record($sql_ocor);
  $numrows_ocor = $clrhpagocor->numrows;

  $sem_movimentacao = "";
  if($numrows_ocor == 0){
    $sem_movimentacao = " * Sem movimentação";
  }

  imprime_atraso($rh57_ano, $rh57_mes, $r70_estrut, $r70_descr, $rh60_descr, $rh57_valorini, $rh57_saldo, $sem_movimentacao);

  $total_geral+= $rh57_saldo;
  $total_funci+= $rh57_saldo;
  $quant_geral++;
  $quant_funci++;

  for($i=0; $i<$numrows_ocor; $i++){
    db_fieldsmemory($result_ocor, $i);
    if($pdf->gety() > $pdf->h - 30){
      imprime_cabecalho();
      imprime_nome($rh57_regist, $z01_nome);
      imprime_atraso($rh57_ano, $rh57_mes, $r70_estrut, $r70_descr, $rh60_descr, $rh57_valorini, $rh57_saldo);
    }

    $put_b = "";
    $tam_f = 7;
    if($rh58_data == $r11_databaseatra){
      $put_b = "b";
      $tam_f = 6;
    }

    $pdf->setfont('arial',$put_b,$tam_f);
    $pdf->cell(30,$alt,db_formatar($rh58_data,"d"),0,0,"C",0);
    $pdf->cell(60,$alt,$rh59_tipo,0,0,"L",0);
    $pdf->cell(80,$alt,$rh59_descr,0,0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rh58_valor,"f"),0,1,"R",0);
  }

}

$pdf->setfont('arial','',7);
$pdf->cell(100,$alt,"Totalização por funcionário:","LTB",0,"L",1);
$pdf->cell(70,$alt,$quant_funci." atrasos, saldo total de R$","TB",0,"R",1);
$pdf->cell(20,$alt,db_formatar($total_funci,"f"),"RTB",1,"R",1);
$pdf->ln(1);
$pdf->cell(100,$alt,"Totalização geral:","LTB",0,"L",1);
$pdf->cell(70,$alt,$quant_geral." atrasos, saldo total de R$","TB",0,"R",1);
$pdf->cell(20,$alt,db_formatar($total_geral,"f"),"RTB",1,"R",1);

$pdf->Output();
?>