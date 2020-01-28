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


include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_orcppa_classe.php");
include ("classes/db_orcppaval_classe.php");
include ("classes/db_orcppatiporec_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clorcppa = new cl_orcppa;
$clorcppaval = new cl_orcppaval;
$clorcppatiporec = new cl_orcppatiporec;
$clorcppa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');

if (!isset ($lei)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Lei não informada.');
}

$ano1 = $ano;
$ano = substr(str_replace('-', ',', $ano1), 1);
$ano_arr = split(',', $ano);
$anoini = $ano_arr[0];
$anofim = $ano_arr[count($ano_arr) - 1];
//print($anofim);exit;

$dbwhere = "";
$and = "";

$dbwhere = " o23_codleippa=$lei ";
if (isset ($orgao) && $orgao != '' && $orgao != 0) {
  $dbwhere .= " and o23_orgao =$orgao ";
  $head7 = "Orgao:$orgao";
}

$campos = "o21_descr,o21_anoini,o21_anofim,o23_codppa,o23_orgao,o40_descr,o23_unidade,o41_descr,o23_funcao,o52_descr,o23_subfuncao,o53_descr,o23_programa,o54_descr,o23_programatxt,o23_acao,o55_descr,o23_acaotxt,
o23_produto,o22_descrprod,o23_unimed ";

$sql = $clorcppa->sql_query_compl(null, $campos, "o23_orgao,o23_unidade,o23_funcao,o23_subfuncao,
o23_programa,o23_programatxt,o23_acao,o23_acaotxt,o23_produto", "$dbwhere");
$result01 = $clorcppa->sql_record($sql);
$numrows01 = $clorcppa->numrows;
if ($numrows01 == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

for ($s = $anoini; $s <= $anofim; $s ++) {
  $tottlorgao["$s"] = 0;
  $tottoorgao["$s"] = 0;
  $tottquanto["$s"] = 0;
  $arr_ano_livre[$s] = '0.00';
  $arr_ano_outro[$s] = '0.00';
  $arr_qua[$s] = 0;
}
//print_r($tottlorgao);exit;
//print($ano);exit;      

db_fieldsmemory($result01, 0);
$head2 = "LDO";

// sapiranga pediu pra tirar a linha abaixo
// $head4 = "Lei: $lei - ".$o21_descr;
$head5 = 'Ano : '.$ano;

$alt = "4";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 10);
//echo $numrows01;

$pdf->SetWidths(array (140, 140));
$pdf->SetAligns(array ('J', 'J'));

$tottlorgao = Array ();
$tottoorgao = Array ();
$tottquanto = Array ();

$orgao_antigo = "";
$unida_antigo = "";
$funca_antigo = "";
$subfu_antigo = "";
$prog_antigo = "";
$xtroca = 0;
$passar = true;

$texto_livre_contador=0;

      $total_orgao = array();
      $total_orgao_livre = array();
      $total_orgao_outro = array ();
      $total_orgao_quant = array();;
for ($x = 0; $x < $numrows01; $x ++) {
  db_fieldsmemory($result01, $x);
  //----------------------------------------------------------------------------------------------------------------------------------------------------------      
  //----------------------------------------------------------------------------------------------------------------------------------------------------------      
  //----------------------------------------------------------------------------------------------------------------------------------------------------------      
  
  $arr_ano_livre = array ();
  $arr_ano_outro = array ();
  $arr_qua = array ();
  for ($s = $anoini; $s <= $anofim; $s ++) {
    $arr_ano_livre[$s] = '0.00';
    $arr_ano_outro[$s] = '0.00';
    $arr_qua[$s] = 0;
  }
  
  $sql = $clorcppaval->sql_query_dad(null, "o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor", "", "o23_codppa=$o23_codppa and o24_exercicio in($ano) group by o24_exercicio,o24_quantmed,o26_codigo");
  
  $result04 = $clorcppaval->sql_record($sql);
  $numrows04 = $clorcppaval->numrows;
  for ($t = 0; $t < $numrows04; $t ++) {
    db_fieldsmemory($result04, $t);
    if ($o26_codigo == 1) {
      if (!isset ($arr_ano_livre[$o24_exercicio])) {
        $arr_ano_livre[$o24_exercicio] = 0;
      }
      $arr_ano_livre[$o24_exercicio] += $o24_valor;
    } else {
      if (!isset ($arr_ano_outro[$o24_exercicio])) {
        $arr_ano_outro[$o24_exercicio] = 0;
      }
      $arr_ano_outro[$o24_exercicio] += $o24_valor;
    }
    $arr_qua[$o24_exercicio] = $o24_quantmed;
  }
  
  $tot_livre = 0;
  $tot_outro = 0;
  $total_teste = 0;
  for ($s = $anoini; $s <= $anofim; $s ++) {
    $total_teste += $arr_ano_livre[$s] + $arr_ano_outro[$s];
  }
  if (isset($impzero)&&$impzero=='n'&&$total_teste==0){
    continue;
  }
  if ($xtroca == 4) {
    //    $pdf->addpage("L");
    $xtroca = 0;
  }
  if ($o23_orgao != $orgao_antigo) {
    if ($orgao_antigo != "") {
      
      db_fieldsmemory($result01, ($x -1));
      $pdf->ln(3);
      $pdf->setfont('arial', 'b', 10);
      
      $pdf->multicell(280, 6, "Totalização por órgão", 1, "L", 1);
      $pdf->cell(280, 7, "$RLo23_orgao:$o23_orgao - $o40_descr", 1, 1, "L", 1);
      
      $pdf->cell(110, $alt, '', 0, 0, "R", 0);
      $pdf->cell(25, $alt, "Ano", 1, 0, "C", 0);
      $pdf->cell(25, $alt, "Quant.Fisíca", 1, 0, "C", 0);
      $pdf->cell(40, $alt, "Próprios", 1, 0, "C", 0);
      $pdf->cell(40, $alt, "Outros", 1, 0, "C", 0);
      $pdf->cell(40, $alt, "Total", 1, 1, "C", 0);
      
      $arr_ano_livre = array ();
      $arr_ano_outro = array ();
      $arr_qua = array ();
      //$tottlorgao = array ();
      //$tottoorgao = array ();
      //$tottquanto = array ();
      for ($s = $anoini; $s <= $anofim; $s ++) {
        //$tottlorgao[$s] = 0;
        //$tottoorgao[$s] = 0;
        //$tottquanto[$s] = 0;
        $arr_ano_livre[$s] = 0;
        $arr_ano_outro[$s] = 0;
        $arr_qua[$s] = 0;
      }
      
      //$sql = $clorcppaval->sql_query_dad(null,"o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor",
      //				    "","o23_orgao=$orgao_antigo and o24_exercicio in($ano) group by o24_exercicio,o24_quantmed,o26_codigo");
      //echo($dbwhere);
      $sql = $clorcppaval->sql_query_dad(null, "o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor", "", " o24_exercicio in($ano)  and o23_orgao=$orgao_antigo and $dbwhere group by o24_exercicio,o24_quantmed,o26_codigo");
      $result04 = $clorcppaval->sql_record($sql);
      
      $numrows04 = $clorcppaval->numrows;
      for ($t = 0; $t < $numrows04; $t ++) {
        db_fieldsmemory($result04, $t);
        if ($o26_codigo == 1) {
          if (!isset ($arr_ano_livre[$o24_exercicio])) {
            $arr_ano_livre[$o24_exercicio] = 0;
          }
          $arr_ano_livre[$o24_exercicio] += $o24_valor;
        } else {
          if (!isset ($arr_ano_outro[$o24_exercicio])) {
            $arr_ano_outro[$o24_exercicio] = 0;
          }
          $arr_ano_outro[$o24_exercicio] += $o24_valor;
        }
        $arr_qua[$o24_exercicio] = $o24_quantmed;
      }
      //print_r($arr_ano_livre);exit;
      for ($s = $anoini; $s <= $anofim; $s ++) {
        $pdf->cell(110, $alt, '', 0, 0, "R", 0);
        $pdf->cell(25, $alt, $s, 1, 0, "C", 0);
        $pdf->cell(25, $alt, $total_orgao_quant[$s], 1, 0, "C", 0);
        $pdf->cell(40, $alt, db_formatar($total_orgao_livre[$s], 'f'), 1, 0, "R", 0);
        $pdf->cell(40, $alt, db_formatar($total_orgao_outro[$s], "f"), 1, 0, "R", 0);
        $pdf->cell(40, $alt, db_formatar($total_orgao[$s],"f" ), 1, 1, "R", 0);
        $tot_livre += $arr_ano_livre[$s];
        $tot_outro += $arr_ano_outro[$s];
      }
      $total_orgao = array();
      $total_orgao_livre = array();
      $total_orgao_outro = array ();
      $total_orgao_quant = array();;
      if ($xtroca > 2) {
        //        $pdf->addpage("L");
        $xtroca = 0;
      } else {
        $xtroca ++;
      }
      db_fieldsmemory($result01, $x);
      $pdf->ln(3);
    }
    $texto_livre_contador=0;
    $pdf->setfont('arial', 'b', 9);
    if (isset($texto_livre) && $texto_livre=='s'){
      if ($pdf->gety() > $pdf->h - 60){
        $pdf->addpage("L");
      }
    }
    $pdf->cell(280, 4, "$RLo23_orgao:$o23_orgao - $o40_descr  $RLo23_unidade:$o23_unidade - $o41_descr ", 1, 1, "L", 1);
    $orgao_antigo = $o23_orgao;
    $passar = true;

  } else {
    $pdf->ln(3);
  }
  $xtroca ++;
  if (($o23_funcao != $funca_antigo || $o23_subfuncao != $subfu_antigo) && $funca_antigo != "" || $passar == true) {
    if ($o23_funcao != $funca_antigo) {
      $funca_antigo = $o23_funcao;
      $subfu_antigo = $o23_subfuncao;
    } else {
      $subfu_antigo = $o23_subfuncao;
    }
    if (isset($texto_livre) && $texto_livre=='s'){
      if ($pdf->gety() > $pdf->h - 60){
        $pdf->addpage("L");
        $texto_livre_contador=0;
      }
    }
    $pdf->setfont('arial', 'b', 9);
    $pdf->cell(280, 4, "$RLo23_funcao:$o23_funcao - $o52_descr  $RLo23_subfuncao:$o23_subfuncao - $o53_descr  ", 1, 1, "L", 1);
  }
  
  $pdf->setfont('arial', '', 8);
  if (($o23_programa != $prog_antigo) && $prog_antigo != "" || $passar == true) {
    $passar = false;
    if ($o23_funcao != $prog_antigo) {
      $prog_antigo = $o23_programa;
    }
    if (isset($texto_livre) && $texto_livre=='s'){
      if ($pdf->gety() > $pdf->h - 60){
        $pdf->addpage("L");
        $texto_livre_contador=0;
      }
    }
    if ($o23_programa==10  ){
    }
    $pdf->cell(280, 4, "$RLo23_programa:$o23_programa - $o54_descr ", 1, 1, "L", 1);
    $pdf->multicell(280, $alt, $o23_programatxt, 1, "L", 1);
  }
  
  $pdf->ln(3);
  
  
  /* quando imprimir texto livre, pediram em guaiba, silvio pra por somente duas ações por páginas */
  if (isset($texto_livre) && $texto_livre=='s'){
    $texto_livre_contador++;
    
    
    if ($texto_livre_contador>2){ 
      $pdf->AddPage("L");
      $texto_livre_contador=1;
    }	   
  }
  
  
  $pdf->multicell(280, 4, "Ação: $o23_acao-$o55_descr  Descr: $o23_acaotxt   ", 1, "L", 1);
  
  
  // texto_livre
  if (isset($texto_livre) && $texto_livre=='s'){
    $pdf->multicell(280, 35, "", 1, "L", 0);
  }	
  
  // $pdf->cell(110, 6, "$RLo23_produto: $o22_descrprod  $RLo23_unidade: $o23_unidade ", 1, 0, "L", 0);
  // sapiranga pediu as modificações abaixo
  if (isset($texto_livre) && $texto_livre=='s'){
    if ($pdf->gety() > $pdf->h - 30){
      $texto_livre_contador=0;
    }
  }
  $pdf->cell(110, 6, "$RLo23_produto: $o22_descrprod  $RLo23_unidade: $o23_unimed ", 1, 0, "L", 0);
  
  $pdf->cell(50, 6, "Meta", 1, 0, "C", 0);
  $pdf->cell(120, 6, "Custos Previsto p/ o exercício", 1, 1, "C", 0);
  
  $pdf->cell(110, $alt, '', 0, 0, "R", 0);
  $pdf->cell(25, $alt, "Ano", 1, 0, "C", 0);
  $pdf->cell(25, $alt, "Quant.Fisíca", 1, 0, "C", 0);
  $pdf->cell(40, $alt, "Próprios", 1, 0, "C", 0);
  $pdf->cell(40, $alt, "Outros", 1, 0, "C", 0);
  $pdf->cell(40, $alt, "Total", 1, 1, "C", 0);
  $arr_ano_livre = array ();
  $arr_ano_outro = array ();
  $arr_qua = array ();
  for ($s = $anoini; $s <= $anofim; $s ++) {
    $arr_ano_livre[$s] = '0.00';
    $arr_ano_outro[$s] = '0.00';
    $arr_qua[$s] = 0;
  }
  
  $sql = $clorcppaval->sql_query_dad(null, "o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor", "", "o23_codppa=$o23_codppa and o24_exercicio in($ano) group by o24_exercicio,o24_quantmed,o26_codigo");
  
  $result04 = $clorcppaval->sql_record($sql);
  $numrows04 = $clorcppaval->numrows;
  for ($t = 0; $t < $numrows04; $t ++) {
    db_fieldsmemory($result04, $t);
    if ($o26_codigo == 1) {
      if (!isset ($arr_ano_livre[$o24_exercicio])) {
        $arr_ano_livre[$o24_exercicio] = 0;
      }
      $arr_ano_livre[$o24_exercicio] += $o24_valor;
    } else {
      if (!isset ($arr_ano_outro[$o24_exercicio])) {
        $arr_ano_outro[$o24_exercicio] = 0;
      }
      $arr_ano_outro[$o24_exercicio] += $o24_valor;
    }
    $arr_qua[$o24_exercicio] = $o24_quantmed;
  }
  
  $tot_livre = 0;
  $tot_outro = 0;
  $total_teste = 0;
  
  for ($s = $anoini; $s <= $anofim; $s ++) {
    if (isset($texto_livre) && $texto_livre=='s'){
      if ($pdf->gety() > $pdf->h - 30){
        $texto_livre_contador=0;
      }
    }
		if (($arr_ano_livre[$s] + $arr_ano_outro[$s]==0) && isset($impzero) && $impzero=='n'){
		}else{
    $pdf->cell(110, $alt, '', 0, 0, "R", 0);
    $pdf->cell(25, $alt, $s, 1, 0, "C", 0);
    $pdf->cell(25, $alt, $arr_qua[$s], 1, 0, "C", 0);
    $pdf->cell(40, $alt, db_formatar($arr_ano_livre[$s], 'f'), 1, 0, "R", 0);
    $pdf->cell(40, $alt, db_formatar($arr_ano_outro[$s], "f"), 1, 0, "R", 0);
    $pdf->cell(40, $alt, db_formatar($arr_ano_livre[$s] + $arr_ano_outro[$s], 'f'), 1, 1, "R", 0);
    }
    if (isset ($tottlorgao[$s])) {
      $tottlorgao[$s] += $arr_ano_livre[$s];
      $tottoorgao[$s] += $arr_ano_outro[$s];
      $tottquanto[$s] += $arr_qua[$s];
    } else {
      $tottlorgao[$s] = $arr_ano_livre[$s];
      $tottoorgao[$s] = $arr_ano_outro[$s];
      $tottquanto[$s] = $arr_qua[$s];
    }
    $tot_livre += $arr_ano_livre[$s];
    $tot_outro += $arr_ano_outro[$s];
		if (array_key_exists($s,$total_orgao)){
	 	  $total_orgao[$s] += $arr_ano_livre[$s] + $arr_ano_outro[$s];   
		  $total_orgao_livre[$s] += $arr_ano_livre[$s] ;
		  $total_orgao_outro[$s] += $arr_ano_outro[$s];
		  $total_orgao_quant[$s] += $arr_qua[$s];
		}else{   
			$total_orgao[$s] = $arr_ano_livre[$s] + $arr_ano_outro[$s];   
			$total_orgao_livre[$s] = $arr_ano_livre[$s] ;
			$total_orgao_outro[$s] = $arr_ano_outro[$s];
			$total_orgao_quant[$s] = $arr_qua[$s];
		}
  }
  $pdf->setfont('arial', '', 8);
  $pdf->ln(3);
}

if (isset ($orgao_antigo)) {
  if ($orgao_antigo != "") {
    
    if (isset($texto_livre) && $texto_livre=='s'){
      $pdf->AddPage("L");
    }
    
    $pdf->ln(6);
    $pdf->setfont('arial', 'b', 10);
    $pdf->multicell(280, 7, "Totalização por órgão", 1, "L", 1);
    $pdf->cell(280, 7, "$RLo23_orgao:$o23_orgao - $o40_descr", 1, 1, "L", 1);
    
    $pdf->cell(110, $alt, '', 0, 0, "R", 0);
    $pdf->cell(25, $alt, "Ano", 1, 0, "C", 0);
    $pdf->cell(25, $alt, "Quant.Fisíca", 1, 0, "C", 0);
    $pdf->cell(40, $alt, "Próprios", 1, 0, "C", 0);
    $pdf->cell(40, $alt, "Outros", 1, 0, "C", 0);
    $pdf->cell(40, $alt, "Total", 1, 1, "C", 0);
    
    $arr_ano_livre = array ();
    $arr_ano_outro = array ();
    $arr_qua = array ();
    for ($s = $anoini; $s <= $anofim; $s ++) {
      $arr_ano_livre[$s] = '0.00';
      $arr_ano_outro[$s] = '0.00';
      $arr_qua[$s] = 0;
    }
    
    $sql = $clorcppaval->sql_query_dad(null, "o24_exercicio,o24_quantmed,o26_codigo,sum(o24_valor) as o24_valor", "", "o23_orgao=$orgao_antigo and o24_exercicio in($ano) group by o24_exercicio,o24_quantmed,o26_codigo");
    
    $result04 = $clorcppaval->sql_record($sql);
    //echo($sql);
    $numrows04 = $clorcppaval->numrows;
    
    for ($t = 0; $t < $numrows04; $t ++) {
      db_fieldsmemory($result04, $t);
      if ($o26_codigo == 1) {
        if (!isset ($arr_ano_livre[$o24_exercicio])) {
          $arr_ano_livre[$o24_exercicio] = 0;
        }
        $arr_ano_livre[$o24_exercicio] += $o24_valor;
      } else {
        if (!isset ($arr_ano_outro[$o24_exercicio])) {
          $arr_ano_outro[$o24_exercicio] = 0;
        }
        $arr_ano_outro[$o24_exercicio] += $o24_valor;
      }
      $arr_qua[$o24_exercicio] = $o24_quantmed;
    }
    
    $tot_livre = 0;
    $tot_outro = 0;
    for ($s = $anoini; $s <= $anofim; $s ++) {
      $pdf->cell(110, $alt, '', 0, 0, "R", 0);
      $pdf->cell(25, $alt, $s, 1, 0, "C", 0);
      $pdf->cell(25, $alt, $arr_qua[$s], 1, 0, "C", 0);
      $pdf->cell(40, $alt, db_formatar($arr_ano_livre[$s], 'f'), 1, 0, "R", 0);
      $pdf->cell(40, $alt, db_formatar($arr_ano_outro[$s], "f"), 1, 0, "R", 0);
      $pdf->cell(40, $alt, db_formatar($arr_ano_livre[$s] + $arr_ano_outro[$s], 'f'), 1, 1, "R", 0);
      $tot_livre += $arr_ano_livre[$s];
      $tot_outro += $arr_ano_outro[$s];
      
    }
  }
}

$pdf->ln(6);
$pdf->setfont('arial', 'b', 10);
$pdf->multicell(280, 7, "Totalização geral (TODOS OS ÓRGÃOS)", 1, "L", 1);

$pdf->cell(110, $alt, '', 0, 0, "R", 0);
$pdf->cell(25, $alt, "Ano", 1, 0, "C", 0);
$pdf->cell(25, $alt, "Quant.Fisíca", 1, 0, "C", 0);
$pdf->cell(40, $alt, "Próprios", 1, 0, "C", 0);
$pdf->cell(40, $alt, "Outros", 1, 0, "C", 0);
$pdf->cell(40, $alt, "Total", 1, 1, "C", 0);

$tot_livre = 0;
$tot_outro = 0;
for ($s = $anoini; $s <= $anofim; $s ++) {
  $pdf->cell(110, $alt, '', 0, 0, "R", 0);
  $pdf->cell(25, $alt, $s, 1, 0, "C", 0);
  $pdf->cell(25, $alt, $tottquanto[$s], 1, 0, "C", 0);
  $pdf->cell(40, $alt, db_formatar($tottlorgao[$s], 'f'), 1, 0, "R", 0);
  $pdf->cell(40, $alt, db_formatar($tottoorgao[$s], "f"), 1, 0, "R", 0);
  $pdf->cell(40, $alt, db_formatar($tottlorgao[$s] + $tottoorgao[$s], 'f'), 1, 1, "R", 0);
  $tot_livre += $tottlorgao[$s];
  $tot_outro += $tottoorgao[$s];
}

$pdf->Output();
?>