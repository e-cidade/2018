<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_empage_classe.php"));
include(modification("classes/db_empagedadosret_classe.php"));
include(modification("classes/db_empagedadosretmov_classe.php"));
include(modification("classes/db_errobanco_classe.php"));
$clempage = new cl_empage;
$clempagedadosret = new cl_empagedadosret;
$clempagedadosretmov = new cl_empagedadosretmov;
$clerrobanco= new cl_errobanco;
$clrotulo = new rotulocampo;
$clempage->rotulo->label();
$clempagedadosret->rotulo->label();
$clempagedadosretmov->rotulo->label();
$clerrobanco->rotulo->label();
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e82_codord");
$clrotulo->label("e60_codemp");
$clrotulo->label("e87_codgera");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e92_processa = 't' and e92_sequencia <> 35 and e60_instit = ".db_getsession("DB_instit");
$valorin = "";
if(trim($selecionadas) != ""){
  $virgula = "";
  $arr_selecionadas = split(",",$selecionadas);
  for($i=0; $i<count($arr_selecionadas); $i++){
    $valorin .= $virgula.$arr_selecionadas[$i];
    $virgula = ",";
  }

  if(trim($valorin) != ""){
  	$dbwhere.= " and e83_codtipo ";
    $head6   = "Com contas selecionadas";
  	$wopcoes = " in ";
    if($opcoes == "N"){
      $wopcoes = " not in ";
      $head6   = "Sem contas selecionadas";
    }
    $dbwhere.= $wopcoes." ($valorin) ";
    $and = " and ";
  }
}else{
  $head6   = "Sem seleção de contas";
}

if($datai_dia != "" && $datai_mes != "" && $datai_ano != ""){
  $datai = $datai_ano.'-'.$datai_mes.'-'.$datai_dia;
}

if($dataf_dia != "" && $dataf_mes != "" && $dataf_ano != ""){
  $dataf = $dataf_ano.'-'.$dataf_mes.'-'.$dataf_dia;
}

if(isset($datai) && isset($dataf)){
  $dbwhere.= " and e87_dataproc between '".$datai."' and '".$dataf."' ";
  $head5   = "Período entre ".db_formatar($datai,"d")." e ".db_formatar($dataf,"d");
}else if(isset($datai)){
  $dbwhere.= " and e87_dataproc >= '".$datai."' ";
  $head5   = "Período posterior a ".db_formatar($datai,"d");
}else if(isset($dataf)){
  $dbwhere.= " and e87_dataproc <= '".$dataf."' ";
  $head5   = "Período anterior a ".db_formatar($dataf,"d");
}else{
  $head5   = "Período de data não informado";
}

$dbwhere .= " and e75_ativo is true "; 

$sSqlEmpAge = $clempage->sql_query_pagam(null,"e53_valor,
                                               e53_vlranu,
                                               e53_vlrpag,
                                               e87_codgera,
                                               e87_descgera,
                                               e87_data,
                                               e87_hora,
                                               e83_descr,
                                               e83_conta,
                                               pc63_conta,
                                               pc63_dataconf,
                                               pc63_conta_dig,
                                               pc63_agencia,
                                               pc63_agencia_dig,
                                               e75_arquivoret,
                                               e76_lote,
                                               e76_movlote,
                                               e76_dataefet,
                                               e76_valorefet,
                                               e81_codmov,
                                               e60_codemp,
                                               e82_codord,
                                               e86_codmov,
                                               case when coalesce(a.z01_numcgm,0) = 0 
                                                    then cgm.z01_numcgm 
                                                    else a.z01_numcgm 
                                               end as z01_numcgm,
                                               case when a.z01_nome='' or a.z01_nome is null 
                                                    then cgm.z01_nome 
                                                    else a.z01_nome 
                                               end as z01_nome,
                                               e81_valor,
                                               e83_codtipo,
                                               e83_descr",
                                               "e83_codtipo,
                                               e87_codgera,
                                               z01_nome",
                                               $dbwhere);

$result_valores = $clempage->sql_record($sSqlEmpAge);
$numrows_valores = $clempage->numrows;
if($numrows_valores == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum arquivo encontrado com os dados informados.");
}

$head3 = "RELATÓRIO ARQUIVOS ENVIADOS";
$head8 = "** - Contas conferidas";
  
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$p = 1;
$alt = 4;
$pagadora = "";
$arquivos = "";

$arr_valconta = Array();
$arr_valmovis = Array();
$arr_valarqui = Array();
$arr_valarquis = Array();

$arr_valtconta = 0;
$arr_valtmovis = 0;

for($i=0;$i<$numrows_valores;$i++){
  db_fieldsmemory($result_valores,$i);

  if(!isset($arr_valmovis[$e83_codtipo])){
    $arr_valmovis[$e83_codtipo] = 0;    
  }
  if(!isset($arr_valconta[$e83_codtipo])){
    $arr_valconta[$e83_codtipo] = 0;
  }
  if(!isset($arr_valarqui[$e83_codtipo."-".$e87_codgera])){
    $arr_valarqui[$e83_codtipo."-".$e87_codgera] = 0;
  }
  if(!isset($arr_valarquis[$e83_codtipo."-".$e87_codgera])){
    $arr_valarquis[$e83_codtipo."-".$e87_codgera] = 0;
  }

  $arr_valmovis[$e83_codtipo] += $e81_valor;
  $arr_valconta[$e83_codtipo] += $e76_valorefet;
  $arr_valarqui[$e83_codtipo."-".$e87_codgera] += $e81_valor;
  $arr_valarquis[$e83_codtipo."-".$e87_codgera] += $e76_valorefet;

  $arr_valtmovis += $e81_valor;
  $arr_valtconta += $e76_valorefet;

}

for($i=0;$i<$numrows_valores;$i++){
  db_fieldsmemory($result_valores,$i);  

  $true_ou_false = false;
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(15,$alt,"Núm Emp",1,0,"C",1);
    $pdf->cell(15,$alt,$RLe82_codord,1,0,"C",1);
    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
    $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(15,$alt,"Data mov.",1,0,"C",1);
    $pdf->cell(15,$alt,"Data ret.",1,0,"C",1);
    $pdf->cell(25,$alt,"Valor mov.",1,0,"C",1);	
    $pdf->cell(25,$alt,"Valor ret.",1,1,"C",1);
    $troca = 0;
    $true_ou_false = true;
  }  
  if($pagadora!=$e83_codtipo || $true_ou_false == true){
  	if(trim($pagadora) != "" && $pagadora!=$e83_codtipo){
      $pdf->ln(3);
  	}
    $pagadora = $e83_codtipo;
    $pdf->setfont('arial','b',8);
    $pdf->cell(145,$alt,$e83_codtipo .' - '. $e83_descr." - CONTA: $e83_conta",1,0,"L",1);
    $pdf->cell(25,$alt,db_formatar($arr_valmovis[$e83_codtipo],"f"),"LTB",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($arr_valconta[$e83_codtipo],"f"),"TBR",1,"R",1);
  }

  $asteriscos = "";
  if($pc63_dataconf!=""){
    $asteriscos = "** ";
  }
  if($arquivos != $e87_codgera || $true_ou_false == true){
  	$arquivos = $e87_codgera;
  	$pdf->ln(1);
    $pdf->setfont('arial','b',6);
    $pdf->cell(145,$alt,$e87_codgera." - ".$e87_descgera,1,0,"L",0);
    $pdf->cell(25,$alt,db_formatar($arr_valarqui[$e83_codtipo."-".$e87_codgera],"f"),"LTB",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($arr_valarquis[$e83_codtipo."-".$e87_codgera],"f"),"TBR",1,"R",0);
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(15,$alt,$e60_codemp,"T",0,"C",0);
  $pdf->cell(15,$alt,$e82_codord,"T",0,"C",0);
  $pdf->cell(15,$alt,$z01_numcgm,"T",0,"C",0);
  $pdf->cell(70,$alt,$asteriscos.$z01_nome,"T",0,"L",0);
  $pdf->cell(15,$alt,db_formatar($e87_data,"d"),"T",0,"C",0);
  $pdf->cell(15,$alt,db_formatar($e76_dataefet,"d"),"T",0,"C",0);
  $pdf->cell(25,$alt,db_formatar($e81_valor,"f"),"T",0,"R",0);    
  $pdf->cell(25,$alt,db_formatar($e76_valorefet,"f"),"T",1,"R",0);
  $total++;
}
$pdf->ln(3);
$pdf->setfont('arial','b',8);
$pdf->cell(145,$alt,"Total geral ",1,0,"R",1);
$pdf->cell(25,$alt,db_formatar($arr_valtmovis,"f"),"TB",0,"R",1);
$pdf->cell(25,$alt,db_formatar($arr_valtconta,"f"),"TBR",1,"R",1);

$pdf->Output();
?>