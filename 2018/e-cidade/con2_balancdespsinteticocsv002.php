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

header('Content-type: text/html; charset=ISO-8859-9');
require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_orcelemento_classe.php");
echo "<PRE>";
db_postmemory($_POST);
//Abre o arquivo
function nivel($nivel){
	$espaco = "'";
	for($i=0; $i < $nivel +1; $i++){
		 $espaco .= "   ";
	}
	return $espaco;
}
//echo nivel(15);
$sArquivo     = "tmp/con2_balancdespsinteticocsv002.csv";
$fArquivo     = fopen($sArquivo, "w");
$sNivel       = "";
$iContaNivel  = count(split(',',$vernivel));

//CABECALHO
  $aTextoSaida[] = trim("  DOTAÇÃO
                          #SALDO INICIAL
                          #SUPLEMENTAÇÕES   
                          #REDUÇÕES         
                          #TOTAL CRÉDITOS 
                          #SALDO DISPONÍVEL DOTAÇÃO 
                          #EMPENHADO NO MÊS 
                          #ANULADO NO MÊS   
                          #LIQUIDADO NO MÊS 
                          #PAGO NO MÊS    
                          #A LIQUIDAR
                          #EMPENHADO NO ANO 
                          #ANULADO NO ANO   
                          #LIQUIDADO NO ANO 
                          #PAGO NO ANO    
                          #A PAGAR LIQUIDADO");




$classinatura  = new cl_assinatura;
$clorcelemento = new cl_orcelemento;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//db_postmemory($HTTP_POST_VARS,2); exit;

$anousu = db_getsession("DB_anousu");

$head1 = "BALANCETE DA DESPESA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

$dataini = $DBtxt21_ano.'-'.$DBtxt21_mes.'-'.$DBtxt21_dia;
$datafin = $DBtxt22_ano.'-'.$DBtxt22_mes.'-'.$DBtxt22_dia;

$dt1 = $DBtxt21_dia.'/'.$DBtxt21_mes.'/'.$DBtxt21_ano;
$dt2 = $DBtxt22_dia.'/'.$DBtxt22_mes.'/'.$DBtxt22_ano;
$head4 = "Período: $dt1 à $dt2 ";

if (!isset($db_selinstit)) {
  $db_selinstit = db_getsession("DB_instit");
}

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0) {
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }
  
  $xvirg = ', ';
}

if ($flag_abrev == false) {
  if (strlen($descr_inst) > 50) {
    $descr_inst = substr($descr_inst,0,100);
  }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;

$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa);
// passa os parametros vindos da func_selorcdotacao_abas.php
$sele_work = $clselorcdotacao->getDados();

$clselorcdotacao->instit = "(".db_getsession("DB_instit").")";

$xinstit    = split("-",$db_selinstit);

$arr_niveis = split(",",$vernivel);

//$nivela = substr($nivel,0,1);
$sele_work = $sele_work.' and w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';

$dataini = $DBtxt21_ano.'-'.$DBtxt21_mes.'-'.$DBtxt21_dia;
$datafin = $DBtxt22_ano.'-'.$DBtxt22_mes.'-'.$DBtxt22_dia;



// TOTAIS GERAIS
$totgeraldot_ini                   = 0;
$totgeralsuplementado_acumulado    = 0;
$totgeralreduzido_acumulado        = 0;
$totgeralatual                     = 0;
$totgeralempenhado                 = 0;
$totgeralanulado                   = 0;
$totgeralliquidado                 = 0;
$totgeralpago                      = 0;
$totgeralatual_a_pagar             = 0;
$totgeralempenhado_acumulado       = 0;
$totgeralanulado_acumulado         = 0;
$totgeralliquidado_acumulado       = 0;
$totgeralpago_acumulado            = 0;
$totgeralatual_a_pagar_liquidado   = 0;

// TOTAIS DE ORGAO
$totorgao_dot_ini                  = 0;
$totorgao_suplementado_acumulado   = 0;
$totorgao_reduzido_acumulado       = 0;
$totorgao_atual                    = 0;
$totorgao_empenhado                = 0;
$totorgao_anulado                  = 0;
$totorgao_liquidado                = 0;
$totorgao_pago                     = 0;
$totorgao_atual_a_pagar            = 0;
$totorgao_empenhado_acumulado      = 0;
$totorgao_anulado_acumulado        = 0;
$totorgao_liquidado_acumulado      = 0;
$totorgao_pago_acumulado           = 0;
$totorgao_atual_a_pagar_liquidado  = 0;

// TOTAIS DE UNIDADE
$totunidade_dot_ini                 = 0;
$totunidade_suplementado_acumulado  = 0;
$totunidade_reduzido_acumulado      = 0;
$totunidade_atual                   = 0;
$totunidade_empenhado               = 0;
$totunidade_anulado                 = 0;
$totunidade_liquidado               = 0;
$totunidade_pago                    = 0;
$totunidade_atual_a_pagar           = 0;
$totunidade_empenhado_acumulado     = 0;
$totunidade_anulado_acumulado       = 0;
$totunidade_liquidado_acumulado     = 0;
$totunidade_pago_acumulado          = 0;
$totunidade_atual_a_pagar_liquidado = 0;

// TOTAIS DE FUNCAO
$totfuncao_dot_ini                 = 0;
$totfuncao_suplementado_acumulado  = 0;
$totfuncao_reduzido_acumulado      = 0;
$totfuncao_atual                   = 0;
$totfuncao_empenhado               = 0;
$totfuncao_anulado                 = 0;
$totfuncao_liquidado               = 0;
$totfuncao_pago                    = 0;
$totfuncao_atual_a_pagar           = 0;
$totfuncao_empenhado_acumulado     = 0;
$totfuncao_anulado_acumulado       = 0;
$totfuncao_liquidado_acumulado     = 0;
$totfuncao_pago_acumulado          = 0;
$totfuncao_atual_a_pagar_liquidado = 0;

// TOTAIS DE SUBFUNCAO
$totsubfuncao_dot_ini                 = 0;
$totsubfuncao_suplementado_acumulado  = 0;
$totsubfuncao_reduzido_acumulado      = 0;
$totsubfuncao_atual                   = 0;
$totsubfuncao_empenhado               = 0;
$totsubfuncao_anulado                 = 0;
$totsubfuncao_liquidado               = 0;
$totsubfuncao_pago                    = 0;
$totsubfuncao_atual_a_pagar           = 0;
$totsubfuncao_empenhado_acumulado     = 0;
$totsubfuncao_anulado_acumulado       = 0;
$totsubfuncao_liquidado_acumulado     = 0;
$totsubfuncao_pago_acumulado          = 0;
$totsubfuncao_atual_a_pagar_liquidado = 0;

// TOTAIS DE PROGRAMA
$totprograma_dot_ini                 = 0;
$totprograma_suplementado_acumulado  = 0;
$totprograma_reduzido_acumulado      = 0;
$totprograma_atual                   = 0;
$totprograma_empenhado               = 0;
$totprograma_anulado                 = 0;
$totprograma_liquidado               = 0;
$totprograma_pago                    = 0;
$totprograma_atual_a_pagar           = 0;
$totprograma_empenhado_acumulado     = 0;
$totprograma_anulado_acumulado       = 0;
$totprograma_liquidado_acumulado     = 0;
$totprograma_pago_acumulado          = 0;
$totprograma_atual_a_pagar_liquidado = 0;

// TOTAIS DE PROJ./ATIV.
$totprojativ_dot_ini                 = 0;
$totprojativ_suplementado_acumulado  = 0;
$totprojativ_reduzido_acumulado      = 0;
$totprojativ_atual                   = 0;
$totprojativ_empenhado               = 0;
$totprojativ_anulado                 = 0;
$totprojativ_liquidado               = 0;
$totprojativ_pago                    = 0;
$totprojativ_atual_a_pagar           = 0;
$totprojativ_empenhado_acumulado     = 0;
$totprojativ_anulado_acumulado       = 0;
$totprojativ_liquidado_acumulado     = 0;
$totprojativ_pago_acumulado          = 0;
$totprojativ_atual_a_pagar_liquidado = 0;

// TOTAIS DE ELEMENTO
$totelemento_dot_ini                 = 0;
$totelemento_suplementado_acumulado  = 0;
$totelemento_reduzido_acumulado      = 0;
$totelemento_atual                   = 0;
$totelemento_empenhado               = 0;
$totelemento_anulado                 = 0;
$totelemento_liquidado               = 0;
$totelemento_pago                    = 0;
$totelemento_atual_a_pagar           = 0;
$totelemento_empenhado_acumulado     = 0;
$totelemento_anulado_acumulado       = 0;
$totelemento_liquidado_acumulado     = 0;
$totelemento_pago_acumulado          = 0;
$totelemento_atual_a_pagar_liquidado = 0;

// TOTAIS DE RECURSO
$totrecurso_dot_ini                 = 0;
$totrecurso_suplementado_acumulado  = 0;
$totrecurso_reduzido_acumulado      = 0;
$totrecurso_atual                   = 0;
$totrecurso_empenhado               = 0;
$totrecurso_anulado                 = 0;
$totrecurso_liquidado               = 0;
$totrecurso_pago                    = 0;
$totrecurso_atual_a_pagar           = 0;
$totrecurso_empenhado_acumulado     = 0;
$totrecurso_anulado_acumulado       = 0;
$totrecurso_liquidado_acumulado     = 0;
$totrecurso_pago_acumulado          = 0;
$totrecurso_atual_a_pagar_liquidado = 0;

$codele = "";

$ultimo = count($arr_niveis)-1;
$nivelb = substr($arr_niveis[$ultimo],0,1);

$flag_grupo = false;
for ($i = 0; $i < count($arr_niveis); $i++) {
  if (substr($arr_niveis[$i],0,1) == "9") {
    $flag_grupo = true;
    break;
  }
}

if ($flag_grupo == false) {
  $nivela = 0;
} else {
  $nivela = 9;
}

//echo $nivela; exit;

$sql_dotacaosaldo = db_dotacaosaldo($nivelb,2,2,true,$sele_work,$anousu,$dataini,$datafin,8,0,true);

$selecao = "";
$agrupar = "";
$ordem   = "";
for ($i = 0; $i < count($arr_niveis); $i++) {
  $nivel = substr($arr_niveis[$i],0,1);
  
  if ($nivel == 1) {
    // Orgao
   
    if (trim($selecao)!="") {
      $selecao .= ",o58_orgao,o40_descr";
    } else {
      $selecao  = "select o58_orgao,o40_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_orgao,o40_descr";
    } else {
      $agrupar  = "group by o58_orgao,o40_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_orgao,o40_descr";
    } else {
      $ordem  = "order by o58_orgao,o40_descr";
    }
  } else if ($nivel == 2) {
    // Unidade
    if (trim($selecao)!="") {
      $selecao .= ",o58_unidade,o41_descr";
    } else {
      $selecao  = "select o58_unidade,o41_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_unidade,o41_descr";
    } else {
      $agrupar  = "group by o58_unidade,o41_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_unidade,o41_descr";
    } else {
      $ordem  = "order by o58_unidade,o41_descr";
    }
  } else if ($nivel == 3) {
    // Funcao
    if (trim($selecao)!="") {
      $selecao .= ",o58_funcao,o52_descr";
    } else {
      $selecao  = "select o58_funcao,o52_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_funcao,o52_descr";
    } else {
      $agrupar  = "group by o58_funcao,o52_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_funcao,o52_descr";
    } else {
      $ordem  = "order by o58_funcao,o52_descr";
    }
  } else if ($nivel == 4) {
    // SubFuncao
    if (trim($selecao)!="") {
      $selecao .= ",o58_subfuncao,o53_descr";
    } else {
      $selecao  = "select o58_subfuncao,o53_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_subfuncao,o53_descr";
    } else {
      $agrupar  = "group by o58_subfuncao,o53_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_subfuncao,o53_descr";
    } else {
      $ordem  = "order by o58_subfuncao,o53_descr";
    }
  } else if ($nivel == 5) {
    // Programa
    if (trim($selecao)!="") {
      $selecao .= ",o58_programa,o54_descr";
    } else {
      $selecao  = "select o58_programa,o54_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_programa,o54_descr";
    } else {
      $agrupar  = "group by o58_programa,o54_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_programa,o54_descr";
    } else {
      $ordem  = "order by o58_programa,o54_descr";
    }
  } else if ($nivel == 6) {
    // Projeto/Atividade
    if (trim($selecao)!="") {
      $selecao .= ",o58_projativ,o55_descr";
    } else {
      $selecao  = "select o58_projativ,o55_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_projativ,o55_descr";
    } else {
      $agrupar  = "group by o58_projativ,o55_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_projativ,o55_descr";
    } else {
      $ordem  = "order by o58_projativ,o55_descr";
    }
  } else if ($nivel == 7) {
    // Elemento
    if (trim($selecao)!="") {
      $selecao .= ",o58_elemento,o56_descr";
    } else {
      $selecao  = "select o58_elemento,o56_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_elemento,o56_descr";
    } else {
      $agrupar  = "group by o58_elemento,o56_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_elemento,o56_descr";
    } else {
      $ordem  = "order by o58_elemento,o56_descr";
    }
  } else if ($nivel == 8) {
    // Recurso
    if (trim($selecao)!="") {
      $selecao .= ",o58_codigo,o15_descr";
    } else {
      $selecao  = "select o58_codigo,o15_descr";
    }
    
    if (trim($agrupar)!="") {
      $agrupar .= ",o58_codigo,o15_descr";
    } else {
      $agrupar  = "group by o58_codigo,o15_descr";
    }
    
    if (trim($ordem)!="") {
      $ordem .= ",o58_codigo,o15_descr";
    } else {
      $ordem  = "order by o58_codigo,o15_descr";
    }
  }
}

$sql_result = $selecao.",
sum(dot_ini)                 as dot_ini,
sum(saldo_anterior)          as saldo_anterior,
sum(empenhado)               as empenhado,
sum(anulado)                 as anulado,
sum(liquidado)               as liquidado,
sum(pago)                    as pago,
sum(suplementado)            as suplementado,
sum(reduzido)                as reduzido,
sum(atual)                   as atual,
sum(reservado)               as reservado,
sum(atual_menos_reservado)   as atual_menos_reservado,
sum(atual_a_pagar)           as atual_a_pagar,
sum(atual_a_pagar_liquidado) as atual_a_pagar_liquidado,
sum(empenhado_acumulado)     as empenhado_acumulado,
sum(anulado_acumulado)       as anulado_acumulado,
sum(liquidado_acumulado)     as liquidado_acumulado,
sum(pago_acumulado)          as pago_acumulado,
sum(suplementado_acumulado)  as suplementado_acumulado,
sum(reduzido_acumulado)      as reduzido_acumulado
from ($sql_dotacaosaldo) as rr ";

$sql_result .= $agrupar." ";
$sql_result .= $ordem;

//echo $sql_result; exit;

if ($selecao != "") {
  $result = pg_exec($sql_result);
}

$orgao     = "";
$unidade   = "";
$funcao    = "";
$subfuncao = "";
$programa  = "";
$projativ  = "";
$elemento  = "";
$codigo    = "";

for ($i=0; $i < pg_numrows($result); $i++) {
  if ($selecao == "") {
    break;
  }
  
  db_fieldsmemory($result,$i);
  
  $flag_imp   = false;
  $flag_nivel = false;
  
  $col        = 5;
  $mult_nivel = 1;
  
 
  $pagina = 0;
   /* 
  $pdf->addpage();
  $pdf->setfont('arial','b',7);
   
  $pdf->ln(2);*/

  
  
 // $pdf->cell(0,$alt,'',"T",1,"C",0);
 
  
  //
  // Exemplo: Niveis escolhidos - funcao(3), programa(5) e grupo de natureza(9)
  //
  //   Funcao
  //      Totais Funcao
  //      Programa
  //      Totais Programa
  //         Grupo
  //             Valores
  //
  //
  
  if (trim(@$o58_orgao) != "") {
    if ($nivelb == 1) {
      // Ultimo nivel que deve ser impresso
      $flag_imp = true;
    }
    
    if ($codigo != "" && $codigo != $o58_codigo &&  $nivelb != 8  && $i > 0) {
  
      
      
      $aTextoSaida[] =  nivel(8)."TOTAL DO RECURSO#".
                        db_formatar($totrecurso_dot_ini,'f')."#".
									      db_formatar($totrecurso_suplementado_acumulado,'f')."#".
									      db_formatar($totrecurso_reduzido_acumulado,'f')."#".
									      db_formatar((($totrecurso_dot_ini + $totrecurso_suplementado_acumulado) - $totrecurso_reduzido_acumulado),'f')."#".
									      db_formatar($totrecurso_atual,'f')."#".
									      db_formatar($totrecurso_empenhado,'f')."#".
									      db_formatar($totrecurso_anulado,'f')."#".
									      db_formatar($totrecurso_liquidado,'f')."#".
									      db_formatar($totrecurso_pago,'f')."#".
									      db_formatar(($totrecurso_empenhado_acumulado-$totrecurso_anulado_acumulado)-$totrecurso_liquidado_acumulado ,'f')."#".
									      db_formatar($totrecurso_empenhado_acumulado,'f')."#".
									      db_formatar($totrecurso_anulado_acumulado,'f')."#".
									      db_formatar($totrecurso_liquidado_acumulado,'f')."#".
									      db_formatar($totrecurso_pago_acumulado,'f')."#".
									      db_formatar($totrecurso_liquidado_acumulado-$totrecurso_pago_acumulado,'f');
      
          
      $totrecurso_dot_ini                 = 0;
      $totrecurso_suplementado_acumulado  = 0;
      $totrecurso_reduzido_acumulado      = 0;
      $totrecurso_atual                   = 0;
      $totrecurso_empenhado               = 0;
      $totrecurso_anulado                 = 0;
      $totrecurso_liquidado               = 0;
      $totrecurso_pago                    = 0;
      $totrecurso_atual_a_pagar           = 0;
      $totrecurso_empenhado_acumulado     = 0;
      $totrecurso_anulado_acumulado       = 0;
      $totrecurso_liquidado_acumulado     = 0;
      $totrecurso_pago_acumulado          = 0;
      $totrecurso_atual_a_pagar_liquidado = 0;
      
      
    }
    
    if ($elemento != "" && $elemento != $o58_elemento && $nivelb   != 7  && $nivela   == 0 && $i > 0 ) {
      // Imprime total do elemento
      
      $descr = "ELEMENTO";
      
      /*
      if ($nivela == 0 && $nivelb == 7) {
        $descr = "ELEMENTO";
      }
      
      if (isset($nivela) && $nivela == 9) {
        $descr = "GRUPO NAT. DESP.";
      }
      
      if ($descr != "ELEMENTO" &&
      $descr != "GRUPO NAT. DESP.") {
        $descr = "ELEMENTO";
      }
      */
      
      
      $aTextoSaida[] = 
                        nivel(7)."TOTAL DO ELEMENTO"."#".
                        db_formatar($totelemento_dot_ini,'f')."#".
                        db_formatar($totelemento_suplementado_acumulado,'f')."#".
                        db_formatar($totelemento_reduzido_acumulado,'f')."#".
                        db_formatar((($totelemento_dot_ini + $totelemento_suplementado_acumulado) - $totelemento_reduzido_acumulado),'f')."#".
                        db_formatar($totelemento_atual,'f')."#".
                        db_formatar($totelemento_empenhado,'f')."#".
                        db_formatar($totelemento_anulado,'f')."#".
                        db_formatar($totelemento_liquidado,'f')."#".
                        db_formatar($totelemento_pago,'f')."#".
                        db_formatar(($totelemento_empenhado_acumulado-$totelemento_anulado_acumulado)-$totelemento_liquidado_acumulado ,'f')."#".
                        db_formatar($totelemento_empenhado_acumulado,'f')."#".
                        db_formatar($totelemento_anulado_acumulado,'f')."#".
                        db_formatar($totelemento_liquidado_acumulado,'f')."#".
                        db_formatar($totelemento_pago_acumulado,'f')."#".
                        db_formatar($totelemento_liquidado_acumulado-$totelemento_pago_acumulado,'f');
      
      
      
      $totelemento_dot_ini                 = 0;
      $totelemento_suplementado_acumulado  = 0;
      $totelemento_reduzido_acumulado      = 0;
      $totelemento_atual                   = 0;
      $totelemento_empenhado               = 0;
      $totelemento_anulado                 = 0;
      $totelemento_liquidado               = 0;
      $totelemento_pago                    = 0;
      $totelemento_atual_a_pagar           = 0;
      $totelemento_empenhado_acumulado     = 0;
      $totelemento_anulado_acumulado       = 0;
      $totelemento_liquidado_acumulado     = 0;
      $totelemento_pago_acumulado          = 0;
      $totelemento_atual_a_pagar_liquidado = 0;
      
     // 
    }
    
    if ($projativ != "" && $projativ != $o58_projativ && $nivelb   != 6  && $i > 0) {
      // Imprime total do projativ
      
      
      
      $aTextoSaida[] =  
                        nivel(6)."TOTAL DO PROJ./ATIV."."#".
                        db_formatar($totprojativ_dot_ini,'f')."#".
                        db_formatar($totprojativ_suplementado_acumulado,'f')."#".
                        db_formatar($totprojativ_reduzido_acumulado,'f')."#".
                        db_formatar((($totprojativ_dot_ini + $totprojativ_suplementado_acumulado) - $totprojativ_reduzido_acumulado),'f')."#".
                        db_formatar($totprojativ_atual,'f')."#".
                        db_formatar($totprojativ_empenhado,'f')."#".
                        db_formatar($totprojativ_anulado,'f')."#".
                        db_formatar($totprojativ_liquidado,'f')."#".
                        db_formatar($totprojativ_pago,'f')."#".
                        db_formatar(($totprojativ_empenhado_acumulado-$totprojativ_anulado_acumulado)-$totprojativ_liquidado_acumulado ,'f')."#".
                        db_formatar($totprojativ_empenhado_acumulado,'f')."#".
                        db_formatar($totprojativ_anulado_acumulado,'f')."#".
                        db_formatar($totprojativ_liquidado_acumulado,'f')."#".
                        db_formatar($totprojativ_pago_acumulado,'f')."#".
                        db_formatar($totprojativ_liquidado_acumulado-$totprojativ_pago_acumulado,'f');
      
      
      
      $totprojativ_dot_ini                 = 0;
      $totprojativ_suplementado_acumulado  = 0;
      $totprojativ_reduzido_acumulado      = 0;
      $totprojativ_atual                   = 0;
      $totprojativ_empenhado               = 0;
      $totprojativ_anulado                 = 0;
      $totprojativ_liquidado               = 0;
      $totprojativ_pago                    = 0;
      $totprojativ_atual_a_pagar           = 0;
      $totprojativ_empenhado_acumulado     = 0;
      $totprojativ_anulado_acumulado       = 0;
      $totprojativ_liquidado_acumulado     = 0;
      $totprojativ_pago_acumulado          = 0;
      $totprojativ_atual_a_pagar_liquidado = 0;
      
      
    }
    
    if ($programa != "" && $programa != $o58_programa && $nivelb   != 5  && $i > 0) {
      // Imprime total do programa
      
      
      
      $aTextoSaida[] = 
                        nivel(5)."TOTAL DO PROGRAMA"."#".
                        db_formatar($totprograma_dot_ini,'f')."#".
                        db_formatar($totprograma_suplementado_acumulado,'f')."#".
                        db_formatar($totprograma_reduzido_acumulado,'f')."#".
                        db_formatar((($totprograma_dot_ini + $totprograma_suplementado_acumulado) - $totprograma_reduzido_acumulado),'f')."#".
                        db_formatar($totprograma_atual,'f')."#".
                        db_formatar($totprograma_empenhado,'f')."#".
                        db_formatar($totprograma_anulado,'f')."#".
                        db_formatar($totprograma_liquidado,'f')."#".
                        db_formatar($totprograma_pago,'f')."#".
                        db_formatar(($totprograma_empenhado_acumulado-$totprograma_anulado_acumulado)-$totprograma_liquidado_acumulado ,'f')."#".
                        db_formatar($totprograma_empenhado_acumulado,'f')."#".
                        db_formatar($totprograma_anulado_acumulado,'f')."#".
                        db_formatar($totprograma_liquidado_acumulado,'f')."#".
                        db_formatar($totprograma_pago_acumulado,'f')."#".
                        db_formatar($totprograma_liquidado_acumulado-$totprograma_pago_acumulado,'f');
      
      
      
      $totprograma_dot_ini                 = 0;
      $totprograma_suplementado_acumulado  = 0;
      $totprograma_reduzido_acumulado      = 0;
      $totprograma_atual                   = 0;
      $totprograma_empenhado               = 0;
      $totprograma_anulado                 = 0;
      $totprograma_liquidado               = 0;
      $totprograma_pago                    = 0;
      $totprograma_atual_a_pagar           = 0;
      $totprograma_empenhado_acumulado     = 0;
      $totprograma_anulado_acumulado       = 0;
      $totprograma_liquidado_acumulado     = 0;
      $totprograma_pago_acumulado          = 0;
      $totprograma_atual_a_pagar_liquidado = 0;
      
      
    }
    
    if ($subfuncao != "" && $subfuncao != $o58_subfuncao && $nivelb    != 4  && $i > 0) {
      // Imprime total da subfuncao
      
      
      
      $aTextoSaida[] =  
                        nivel(4)."TOTAL DA SUBFUNCAO"."#".
                        db_formatar($totsubfuncao_dot_ini,'f')."#".
                        db_formatar($totsubfuncao_suplementado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_reduzido_acumulado,'f')."#".
                        db_formatar((($totsubfuncao_dot_ini + $totsubfuncao_suplementado_acumulado) - $totsubfuncao_reduzido_acumulado),'f')."#".
                        db_formatar($totsubfuncao_atual,'f')."#".
                        db_formatar($totsubfuncao_empenhado,'f')."#".
                        db_formatar($totsubfuncao_anulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado,'f')."#".
                        db_formatar($totsubfuncao_pago,'f')."#".
                        db_formatar(($totsubfuncao_empenhado_acumulado-$totsubfuncao_anulado_acumulado)-$totsubfuncao_liquidado_acumulado ,'f')."#".
                        db_formatar($totsubfuncao_empenhado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_anulado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_pago_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado-$totsubfuncao_pago_acumulado,'f');
      
      
      
      $totsubfuncao_dot_ini                 = 0;
      $totsubfuncao_suplementado_acumulado  = 0;
      $totsubfuncao_reduzido_acumulado      = 0;
      $totsubfuncao_atual                   = 0;
      $totsubfuncao_empenhado               = 0;
      $totsubfuncao_anulado                 = 0;
      $totsubfuncao_liquidado               = 0;
      $totsubfuncao_pago                    = 0;
      $totsubfuncao_atual_a_pagar           = 0;
      $totsubfuncao_empenhado_acumulado     = 0;
      $totsubfuncao_anulado_acumulado       = 0;
      $totsubfuncao_liquidado_acumulado     = 0;
      $totsubfuncao_pago_acumulado          = 0;
      $totsubfuncao_atual_a_pagar_liquidado = 0;

      
    }
    
    if ($funcao != "" && $funcao != $o58_funcao && $nivelb != 3  && $i > 0) {
      // Imprime total da funcao
      
      
      
      $aTextoSaida[] =  
                        nivel(3)."TOTAL DA FUNCAO"."#".
                        db_formatar($totfuncao_dot_ini,'f')."#".
                        db_formatar($totfuncao_suplementado_acumulado,'f')."#".
                        db_formatar($totfuncao_reduzido_acumulado,'f')."#".
                        db_formatar((($totfuncao_dot_ini + $totfuncao_suplementado_acumulado) - $totfuncao_reduzido_acumulado),'f')."#".
                        db_formatar($totfuncao_atual,'f')."#".
                        db_formatar($totfuncao_empenhado,'f')."#".
                        db_formatar($totfuncao_anulado,'f')."#".
                        db_formatar($totfuncao_liquidado,'f')."#".
                        db_formatar($totfuncao_pago,'f')."#".
                        db_formatar(($totfuncao_empenhado_acumulado-$totfuncao_anulado_acumulado)-$totfuncao_liquidado_acumulado ,'f')."#".
                        db_formatar($totfuncao_empenhado_acumulado,'f')."#".
                        db_formatar($totfuncao_anulado_acumulado,'f')."#".
                        db_formatar($totfuncao_liquidado_acumulado,'f')."#".
                        db_formatar($totfuncao_pago_acumulado,'f')."#".
                        db_formatar($totfuncao_liquidado_acumulado-$totfuncao_pago_acumulado,'f');
      
      
      $totfuncao_dot_ini                 = 0;
      $totfuncao_suplementado_acumulado  = 0;
      $totfuncao_reduzido_acumulado      = 0;
      $totfuncao_atual                   = 0;
      $totfuncao_empenhado               = 0;
      $totfuncao_anulado                 = 0;
      $totfuncao_liquidado               = 0;
      $totfuncao_pago                    = 0;
      $totfuncao_atual_a_pagar           = 0;
      $totfuncao_empenhado_acumulado     = 0;
      $totfuncao_anulado_acumulado       = 0;
      $totfuncao_liquidado_acumulado     = 0;
      $totfuncao_pago_acumulado          = 0;
      $totfuncao_atual_a_pagar_liquidado = 0;
      
      
    }
    
    if ($unidade != "" && $orgao != $o58_orgao && $nivelb  != 2  && $i > 0) {
      // Imprime total da unidade
      
      
      
      $aTextoSaida[] = 
                        nivel(2)."TOTAL DA UNIDADE"."#".
                        db_formatar($totunidade_dot_ini,'f')."#".
                        db_formatar($totunidade_suplementado_acumulado,'f')."#".
                        db_formatar($totunidade_reduzido_acumulado,'f')."#".
                        db_formatar((($totunidade_dot_ini + $totunidade_suplementado_acumulado) - $totunidade_reduzido_acumulado),'f')."#".
                        db_formatar($totunidade_atual,'f')."#".
                        db_formatar($totunidade_empenhado,'f')."#".
                        db_formatar($totunidade_anulado,'f')."#".
                        db_formatar($totunidade_liquidado,'f')."#".
                        db_formatar($totunidade_pago,'f')."#".
                        db_formatar(($totunidade_empenhado_acumulado-$totunidade_anulado_acumulado)-$totunidade_liquidado_acumulado ,'f')."#".
                        db_formatar($totunidade_empenhado_acumulado,'f')."#".
                        db_formatar($totunidade_anulado_acumulado,'f')."#".
                        db_formatar($totunidade_liquidado_acumulado,'f')."#".
                        db_formatar($totunidade_pago_acumulado,'f')."#".
                        db_formatar($totunidade_liquidado_acumulado-$totunidade_pago_acumulado,'f');
      
      
      
      $totunidade_dot_ini                 = 0;
      $totunidade_suplementado_acumulado  = 0;
      $totunidade_reduzido_acumulado      = 0;
      $totunidade_atual                   = 0;
      $totunidade_empenhado               = 0;
      $totunidade_anulado                 = 0;
      $totunidade_liquidado               = 0;
      $totunidade_pago                    = 0;
      $totunidade_atual_a_pagar           = 0;
      $totunidade_empenhado_acumulado     = 0;
      $totunidade_anulado_acumulado       = 0;
      $totunidade_liquidado_acumulado     = 0;
      $totunidade_pago_acumulado          = 0;
      $totunidade_atual_a_pagar_liquidado = 0;
      
      $flag_imp = true;
      
      
    }
    
    if ($orgao  != $o58_orgao && $nivelb != 1 && $i > 0) {
      // Trocou de orgao e nao eh nivel de orgao somente - imprime totais
      
      
      
      $aTextoSaida[] =  
                        nivel(1).'TOTAL DO ORGAO'."#".
                        db_formatar($totorgao_dot_ini,'f')."#".
                        db_formatar($totorgao_suplementado_acumulado,'f')."#".
                        db_formatar($totorgao_reduzido_acumulado,'f')."#".
                        db_formatar((($totorgao_dot_ini + $totorgao_suplementado_acumulado) - $totorgao_reduzido_acumulado),'f')."#".
                        db_formatar($totorgao_atual,'f')."#".
                        db_formatar($totorgao_empenhado,'f')."#".
                        db_formatar($totorgao_anulado,'f')."#".
                        db_formatar($totorgao_liquidado,'f')."#".
                        db_formatar($totorgao_pago,'f')."#".
                        db_formatar(($totorgao_empenhado_acumulado-$totorgao_anulado_acumulado)-$totorgao_liquidado_acumulado ,'f')."#".
                        db_formatar($totorgao_empenhado_acumulado,'f')."#".
                        db_formatar($totorgao_anulado_acumulado,'f')."#".
                        db_formatar($totorgao_liquidado_acumulado,'f')."#".
                        db_formatar($totorgao_pago_acumulado,'f')."#".
                        db_formatar($totorgao_liquidado_acumulado-$totorgao_pago_acumulado,'f');
      
      
      $totorgao_dot_ini                 = 0;
      $totorgao_suplementado_acumulado  = 0;
      $totorgao_reduzido_acumulado      = 0;
      $totorgao_atual                   = 0;
      $totorgao_empenhado               = 0;
      $totorgao_anulado                 = 0;
      $totorgao_liquidado               = 0;
      $totorgao_pago                    = 0;
      $totorgao_atual_a_pagar           = 0;
      $totorgao_empenhado_acumulado     = 0;
      $totorgao_anulado_acumulado       = 0;
      $totorgao_liquidado_acumulado     = 0;
      $totorgao_pago_acumulado          = 0;
      $totorgao_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "ORGAO: ";
    if ($orgao != $o58_orgao) {
    	$sRotulo = $descricao.db_formatar($o58_orgao,"orgao").'  -  '.$o40_descr;
       if($iContaNivel > 1) $aTextoSaida[] = nivel(1).$sRotulo;
      $orgao = $o58_orgao;
    } else {
      if ($flag_imp == true) {
      	$sRotulo = $descricao.db_formatar($o58_orgao,"orgao").' - '.$o40_descr;
        $aTextoSaida[] = $sRotulo;
      }
    }
    
    $totorgao_dot_ini                 += $dot_ini;
    $totorgao_suplementado_acumulado  += $suplementado_acumulado;
    $totorgao_reduzido_acumulado      += $reduzido_acumulado;
    $totorgao_atual                   += $atual;
    $totorgao_empenhado               += $empenhado;
    $totorgao_anulado                 += $anulado;
    $totorgao_liquidado               += $liquidado;
    $totorgao_pago                    += $pago;
    $totorgao_atual_a_pagar           += $atual_a_pagar;
    $totorgao_empenhado_acumulado     += $empenhado_acumulado;
    $totorgao_anulado_acumulado       += $anulado_acumulado;
    $totorgao_liquidado_acumulado     += $liquidado_acumulado;
    $totorgao_pago_acumulado          += $pago_acumulado;
    $totorgao_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_unidade) != "") {
    if ($nivelb == 2) {
      $flag_imp = true;
    }
    
    if ($unidade != "" && $unidade != $o58_unidade &&
    $nivelb  != 2  && $i > 0 &&
    ($totunidade_dot_ini               != 0 ||
    $totunidade_suplementado_acumulado != 0 ||
    $totunidade_reduzido_acumulado     != 0 ||
    $totunidade_atual                  != 0 ||
    $totunidade_empenhado              != 0 ||
    $totunidade_anulado                != 0 ||
    $totunidade_liquidado              != 0 ||
    $totunidade_pago                   != 0 ||
    $totunidade_empenhado_acumulado    != 0 ||
    $totunidade_anulado_acumulado      != 0 ||
    $totunidade_liquidado_acumulado    != 0 ||
    $totunidade_pago_acumulado         != 0)) {
      // Imprime total da unidade
      
      
      
      $aTextoSaida[] =  
                        nivel(1)."TOTAL DA UNIDADE"."#".
                        db_formatar($totunidade_dot_ini,'f')."#".
                        db_formatar($totunidade_suplementado_acumulado,'f')."#".
                        db_formatar($totunidade_reduzido_acumulado,'f')."#".
                        db_formatar((($totunidade_dot_ini + $totunidade_suplementado_acumulado) - $totunidade_reduzido_acumulado),'f')."#".
                        db_formatar($totunidade_atual,'f')."#".
                        db_formatar($totunidade_empenhado,'f')."#".
                        db_formatar($totunidade_anulado,'f')."#".
                        db_formatar($totunidade_liquidado,'f')."#".
                        db_formatar($totunidade_pago,'f')."#".
                        db_formatar(($totunidade_empenhado_acumulado-$totunidade_anulado_acumulado)-$totunidade_liquidado_acumulado ,'f')."#".
                        db_formatar($totunidade_empenhado_acumulado,'f')."#".
                        db_formatar($totunidade_anulado_acumulado,'f')."#".
                        db_formatar($totunidade_liquidado_acumulado,'f')."#".
                        db_formatar($totunidade_pago_acumulado,'f')."#".
                        db_formatar($totunidade_liquidado_acumulado-$totunidade_pago_acumulado,'f');
      
      
      
      $totunidade_dot_ini                 = 0;
      $totunidade_suplementado_acumulado  = 0;
      $totunidade_reduzido_acumulado      = 0;
      $totunidade_atual                   = 0;
      $totunidade_empenhado               = 0;
      $totunidade_anulado                 = 0;
      $totunidade_liquidado               = 0;
      $totunidade_pago                    = 0;
      $totunidade_atual_a_pagar           = 0;
      $totunidade_empenhado_acumulado     = 0;
      $totunidade_anulado_acumulado       = 0;
      $totunidade_liquidado_acumulado     = 0;
      $totunidade_pago_acumulado          = 0;
      $totunidade_atual_a_pagar_liquidado = 0;
      
      $flag_imp = true;
      
      
    }
   
    $descricao = "UNIDADE: ";
    if ($unidade != $o58_unidade) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
        $sEspaco = nivel(2);
      }
      
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_unidade,"unidade").'  -  '.$o41_descr;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;
      
      $unidade = $o58_unidade;
    } else {
      if ($flag_imp == true) {
        $sEspaco = "";
      	if ($flag_nivel == true) {
          $sEspaco = nivel(2);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_unidade,"unidade").'  -  '.$o41_descr;
        $aTextoSaida[] =$sRotulo;
      }
    }
    
    $totunidade_dot_ini                 += $dot_ini;
    $totunidade_suplementado_acumulado  += $suplementado_acumulado;
    $totunidade_reduzido_acumulado      += $reduzido_acumulado;
    $totunidade_atual                   += $atual;
    $totunidade_empenhado               += $empenhado;
    $totunidade_anulado                 += $anulado;
    $totunidade_liquidado               += $liquidado;
    $totunidade_pago                    += $pago;
    $totunidade_atual_a_pagar           += $atual_a_pagar;
    $totunidade_empenhado_acumulado     += $empenhado_acumulado;
    $totunidade_anulado_acumulado       += $anulado_acumulado;
    $totunidade_liquidado_acumulado     += $liquidado_acumulado;
    $totunidade_pago_acumulado          += $pago_acumulado;
    $totunidade_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_funcao) != "") {
    if ($nivelb == 3) {
      $flag_imp = true;
    }
    
    if ($subfuncao != "" && $subfuncao != $o58_subfuncao &&
    $nivelb    != 4  && $i > 0                       &&
    ($totsubfuncao_dot_ini               != 0 ||
    $totsubfuncao_suplementado_acumulado != 0 ||
    $totsubfuncao_reduzido_acumulado     != 0 ||
    $totsubfuncao_atual                  != 0 ||
    $totsubfuncao_empenhado              != 0 ||
    $totsubfuncao_anulado                != 0 ||
    $totsubfuncao_liquidado              != 0 ||
    $totsubfuncao_pago                   != 0 ||
    $totsubfuncao_empenhado_acumulado    != 0 ||
    $totsubfuncao_anulado_acumulado      != 0 ||
    $totsubfuncao_liquidado_acumulado    != 0 ||
    $totsubfuncao_pago_acumulado         != 0 )) {
      // Imprime total da subfuncao
      
      
      
      $aTextoSaida[] =  
                        nivel(4).'TOTAL DA SUBFUNCAO'."#".
                        db_formatar($totsubfuncao_dot_ini,'f')."#".
                        db_formatar($totsubfuncao_suplementado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_reduzido_acumulado,'f')."#".
                        db_formatar((($totsubfuncao_dot_ini + $totsubfuncao_suplementado_acumulado) - $totsubfuncao_reduzido_acumulado),'f')."#".
                        db_formatar($totsubfuncao_atual,'f')."#".
                        db_formatar($totsubfuncao_empenhado,'f')."#".
                        db_formatar($totsubfuncao_anulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado,'f')."#".
                        db_formatar($totsubfuncao_pago,'f')."#".
                        db_formatar(($totsubfuncao_empenhado_acumulado-$totsubfuncao_anulado_acumulado)-$totsubfuncao_liquidado_acumulado ,'f')."#".
                        db_formatar($totsubfuncao_empenhado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_anulado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_pago_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado-$totsubfuncao_pago_acumulado,'f');
      
      
      
      $totsubfuncao_dot_ini                 = 0;
      $totsubfuncao_suplementado_acumulado  = 0;
      $totsubfuncao_reduzido_acumulado      = 0;
      $totsubfuncao_atual                   = 0;
      $totsubfuncao_empenhado               = 0;
      $totsubfuncao_anulado                 = 0;
      $totsubfuncao_liquidado               = 0;
      $totsubfuncao_pago                    = 0;
      $totsubfuncao_atual_a_pagar           = 0;
      $totsubfuncao_empenhado_acumulado     = 0;
      $totsubfuncao_anulado_acumulado       = 0;
      $totsubfuncao_liquidado_acumulado     = 0;
      $totsubfuncao_pago_acumulado          = 0;
      $totsubfuncao_atual_a_pagar_liquidado = 0;
      
      
    }
    
    if ($funcao != ""      && $funcao != $o58_funcao &&
    $flag_imp == false && $i > 0                 &&
    ($totfuncao_dot_ini                != 0 ||
    $totfuncao_suplementado_acumulado != 0 ||
    $totfuncao_reduzido_acumulado     != 0 ||
    $totfuncao_atual                  != 0 ||
    $totfuncao_empenhado              != 0 ||
    $totfuncao_anulado                != 0 ||
    $totfuncao_liquidado              != 0 ||
    $totfuncao_pago                   != 0 ||
    $totfuncao_empenhado_acumulado    != 0 ||
    $totfuncao_anulado_acumulado      != 0 ||
    $totfuncao_liquidado_acumulado    != 0 ||
    $totfuncao_pago_acumulado         != 0)) {
      // Imprime total da funcao
      
      
      
      $aTextoSaida[] =  
                        nivel(3).'TOTAL DA FUNCAO '."#".
                        db_formatar($totfuncao_dot_ini,'f')."#".
                        db_formatar($totfuncao_suplementado_acumulado,'f')."#".
                        db_formatar($totfuncao_reduzido_acumulado,'f')."#".
                        db_formatar((($totfuncao_dot_ini + $totfuncao_suplementado_acumulado) - $totfuncao_reduzido_acumulado),'f')."#".
                        db_formatar($totfuncao_atual,'f')."#".
                        db_formatar($totfuncao_empenhado,'f')."#".
                        db_formatar($totfuncao_anulado,'f')."#".
                        db_formatar($totfuncao_liquidado,'f')."#".
                        db_formatar($totfuncao_pago,'f')."#".
                        db_formatar(($totfuncao_empenhado_acumulado-$totfuncao_anulado_acumulado)-$totfuncao_liquidado_acumulado ,'f')."#".
                        db_formatar($totfuncao_empenhado_acumulado,'f')."#".
                        db_formatar($totfuncao_anulado_acumulado,'f')."#".
                        db_formatar($totfuncao_liquidado_acumulado,'f')."#".
                        db_formatar($totfuncao_pago_acumulado,'f')."#".
                        db_formatar($totfuncao_liquidado_acumulado-$totfuncao_pago_acumulado,'f');
      
      
      
      $totfuncao_dot_ini                 = 0;
      $totfuncao_suplementado_acumulado  = 0;
      $totfuncao_reduzido_acumulado      = 0;
      $totfuncao_atual                   = 0;
      $totfuncao_empenhado               = 0;
      $totfuncao_anulado                 = 0;
      $totfuncao_liquidado               = 0;
      $totfuncao_pago                    = 0;
      $totfuncao_atual_a_pagar           = 0;
      $totfuncao_empenhado_acumulado     = 0;
      $totfuncao_anulado_acumulado       = 0;
      $totfuncao_liquidado_acumulado     = 0;
      $totfuncao_pago_acumulado          = 0;
      $totfuncao_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "FUNCAO: ";
    if ($funcao != $o58_funcao) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
          $sEspaco= nivel(3);
        
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_funcao,"funcao").'  -  '.$o52_descr;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;
      $funcao = $o58_funcao;
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
          $sEspaco= nivel(3);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_funcao,"funcao").'  -  '.$o52_descr;
        $aTextoSaida[] =$sRotulo;
      }
    }
    
    //

    $totfuncao_dot_ini                 += $dot_ini;
    $totfuncao_suplementado_acumulado  += $suplementado_acumulado;
    $totfuncao_reduzido_acumulado      += $reduzido_acumulado;
    $totfuncao_atual                   += $atual;
    $totfuncao_empenhado               += $empenhado;
    $totfuncao_anulado                 += $anulado;
    $totfuncao_liquidado               += $liquidado;
    $totfuncao_pago                    += $pago;
    $totfuncao_atual_a_pagar           += $atual_a_pagar;
    $totfuncao_empenhado_acumulado     += $empenhado_acumulado;
    $totfuncao_anulado_acumulado       += $anulado_acumulado;
    $totfuncao_liquidado_acumulado     += $liquidado_acumulado;
    $totfuncao_pago_acumulado          += $pago_acumulado;
    $totfuncao_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_subfuncao) != "") {
    if ($nivelb == 4) {
      $flag_imp = true;
    }
    
    if ($subfuncao != "" && $subfuncao != $o58_subfuncao &&
    $nivelb    != 4  && $i > 0                       &&
    ($totsubfuncao_dot_ini               != 0 ||
    $totsubfuncao_suplementado_acumulado != 0 ||
    $totsubfuncao_reduzido_acumulado     != 0 ||
    $totsubfuncao_atual                  != 0 ||
    $totsubfuncao_empenhado              != 0 ||
    $totsubfuncao_anulado                != 0 ||
    $totsubfuncao_liquidado              != 0 ||
    $totsubfuncao_pago                   != 0 ||
    $totsubfuncao_empenhado_acumulado    != 0 ||
    $totsubfuncao_anulado_acumulado      != 0 ||
    $totsubfuncao_liquidado_acumulado    != 0 ||
    $totsubfuncao_pago_acumulado         != 0)) {
      // Imprime total da subfuncao
      
      
      $aTextoSaida[] =  nivel(4)."TOTAL DA SUBFUNCAO"."#".
                        db_formatar($totsubfuncao_dot_ini,'f')."#".
                        db_formatar($totsubfuncao_suplementado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_reduzido_acumulado,'f')."#".
                        db_formatar((($totsubfuncao_dot_ini + $totsubfuncao_suplementado_acumulado) - $totsubfuncao_reduzido_acumulado),'f')."#".
                        db_formatar($totsubfuncao_atual,'f')."#".
                        db_formatar($totsubfuncao_empenhado,'f')."#".
                        db_formatar($totsubfuncao_anulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado,'f')."#".
                        db_formatar($totsubfuncao_pago,'f')."#".
                        db_formatar(($totsubfuncao_empenhado_acumulado-$totsubfuncao_anulado_acumulado)-$totsubfuncao_liquidado_acumulado ,'f')."#".
                        db_formatar($totsubfuncao_empenhado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_anulado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado,'f')."#".
                        db_formatar($totsubfuncao_pago_acumulado,'f')."#".
                        db_formatar($totsubfuncao_liquidado_acumulado-$totsubfuncao_pago_acumulado,'f');
      
      
      
      $totsubfuncao_dot_ini                 = 0;
      $totsubfuncao_suplementado_acumulado  = 0;
      $totsubfuncao_reduzido_acumulado      = 0;
      $totsubfuncao_atual                   = 0;
      $totsubfuncao_empenhado               = 0;
      $totsubfuncao_anulado                 = 0;
      $totsubfuncao_liquidado               = 0;
      $totsubfuncao_pago                    = 0;
      $totsubfuncao_atual_a_pagar           = 0;
      $totsubfuncao_empenhado_acumulado     = 0;
      $totsubfuncao_anulado_acumulado       = 0;
      $totsubfuncao_liquidado_acumulado     = 0;
      $totsubfuncao_pago_acumulado          = 0;
      $totsubfuncao_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "SUBFUNCAO: ";
    if ($subfuncao != $o58_subfuncao) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
        $sEspaco= nivel(4);
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_subfuncao,"subfuncao").'  -  '.$o53_descr;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;
      $subfuncao = $o58_subfuncao;
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
          $sEspaco= nivel(4);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_subfuncao,"subfuncao").'  -  '.$o53_descr;
        $aTextoSaida[] =$sRotulo;
      }
    }
    
    $totsubfuncao_dot_ini                 += $dot_ini;
    $totsubfuncao_suplementado_acumulado  += $suplementado_acumulado;
    $totsubfuncao_reduzido_acumulado      += $reduzido_acumulado;
    $totsubfuncao_atual                   += $atual;
    $totsubfuncao_empenhado               += $empenhado;
    $totsubfuncao_anulado                 += $anulado;
    $totsubfuncao_liquidado               += $liquidado;
    $totsubfuncao_pago                    += $pago;
    $totsubfuncao_atual_a_pagar           += $atual_a_pagar;
    $totsubfuncao_empenhado_acumulado     += $empenhado_acumulado;
    $totsubfuncao_anulado_acumulado       += $anulado_acumulado;
    $totsubfuncao_liquidado_acumulado     += $liquidado_acumulado;
    $totsubfuncao_pago_acumulado          += $pago_acumulado;
    $totsubfuncao_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_programa) != "") {
    if ($nivelb == 5) {
      $flag_imp = true;
    }
    
    if ($programa != "" && $programa != $o58_programa &&
    $nivelb   != 5  && $i > 0                     &&
    ($totprograma_dot_ini               != 0 ||
    $totprograma_suplementado_acumulado != 0 ||
    $totprograma_reduzido_acumulado     != 0 ||
    $totprograma_atual                  != 0 ||
    $totprograma_empenhado              != 0 ||
    $totprograma_anulado                != 0 ||
    $totprograma_liquidado              != 0 ||
    $totprograma_pago                   != 0 ||
    $totprograma_empenhado_acumulado    != 0 ||
    $totprograma_anulado_acumulado      != 0 ||
    $totprograma_liquidado_acumulado    != 0 ||
    $totprograma_pago_acumulado         != 0)) {
      // Imprime total do programa
      
      
      $aTextoSaida[] =  
                        nivel(5).'TOTAL DO PROGRAMA'."#".
                        db_formatar($totprograma_dot_ini,'f')."#".
                        db_formatar($totprograma_suplementado_acumulado,'f')."#".
                        db_formatar($totprograma_reduzido_acumulado,'f')."#".
                        db_formatar((($totprograma_dot_ini + $totprograma_suplementado_acumulado) - $totprograma_reduzido_acumulado),'f')."#".
                        db_formatar($totprograma_atual,'f')."#".
                        db_formatar($totprograma_empenhado,'f')."#".
                        db_formatar($totprograma_anulado,'f')."#".
                        db_formatar($totprograma_liquidado,'f')."#".
                        db_formatar($totprograma_pago,'f')."#".
                        db_formatar(($totprograma_empenhado_acumulado-$totprograma_anulado_acumulado)-$totprograma_liquidado_acumulado ,'f')."#".
                        db_formatar($totprograma_empenhado_acumulado,'f')."#".
                        db_formatar($totprograma_anulado_acumulado,'f')."#".
                        db_formatar($totprograma_liquidado_acumulado,'f')."#".
                        db_formatar($totprograma_pago_acumulado,'f')."#".
                        db_formatar($totprograma_liquidado_acumulado-$totprograma_pago_acumulado,'f');
      
      
      
      $totprograma_dot_ini                 = 0;
      $totprograma_suplementado_acumulado  = 0;
      $totprograma_reduzido_acumulado      = 0;
      $totprograma_atual                   = 0;
      $totprograma_empenhado               = 0;
      $totprograma_anulado                 = 0;
      $totprograma_liquidado               = 0;
      $totprograma_pago                    = 0;
      $totprograma_atual_a_pagar           = 0;
      $totprograma_empenhado_acumulado     = 0;
      $totprograma_anulado_acumulado       = 0;
      $totprograma_liquidado_acumulado     = 0;
      $totprograma_pago_acumulado          = 0;
      $totprograma_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "PROGRAMA: ";
    if ($programa != $o58_programa) {
      $sEspaco = "";
    	if ($flag_nivel == true) {
         $sEspaco=  nivel(5);
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_programa,"programa").'  -  '.$o54_descr;;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;
      $programa = $o58_programa;
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
           $sEspaco=  nivel(5);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_programa,"programa").'  -  '.$o54_descr;;
        $aTextoSaida[] =$sRotulo;      
      }
    }
    
    $totprograma_dot_ini                 += $dot_ini;
    $totprograma_suplementado_acumulado  += $suplementado_acumulado;
    $totprograma_reduzido_acumulado      += $reduzido_acumulado;
    $totprograma_atual                   += $atual;
    $totprograma_empenhado               += $empenhado;
    $totprograma_anulado                 += $anulado;
    $totprograma_liquidado               += $liquidado;
    $totprograma_pago                    += $pago;
    $totprograma_atual_a_pagar           += $atual_a_pagar;
    $totprograma_empenhado_acumulado     += $empenhado_acumulado;
    $totprograma_anulado_acumulado       += $anulado_acumulado;
    $totprograma_liquidado_acumulado     += $liquidado_acumulado;
    $totprograma_pago_acumulado          += $pago_acumulado;
    $totprograma_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_projativ) != "") {
    if ($nivelb == 6) {
      $flag_imp = true;
    }
    
    if ($projativ != "" && $projativ != $o58_projativ &&    $nivelb   != 5  && $i > 0                     &&
    ($totprojativ_dot_ini               != 0 ||
    $totprojativ_suplementado_acumulado != 0 ||
    $totprojativ_reduzido_acumulado     != 0 ||
    $totprojativ_atual                  != 0 ||
    $totprojativ_empenhado              != 0 ||
    $totprojativ_anulado                != 0 ||
    $totprojativ_liquidado              != 0 ||
    $totprojativ_pago                   != 0 ||
    $totprojativ_empenhado_acumulado    != 0 ||
    $totprojativ_anulado_acumulado      != 0 ||
    $totprojativ_liquidado_acumulado    != 0 ||
    $totprojativ_pago_acumulado         != 0)) {
      // Imprime total do projativ
      
      
     if($iContaNivel > 1) $aTextoSaida[] =  nivel(6).'TOTAL DO PROJ./ATIV.'."#".
                        db_formatar($totprojativ_dot_ini,'f')."#".
                        db_formatar($totprojativ_suplementado_acumulado,'f')."#".
                        db_formatar($totprojativ_reduzido_acumulado,'f')."#".
                        db_formatar((($totprojativ_dot_ini + $totprojativ_suplementado_acumulado) - $totprojativ_reduzido_acumulado),'f')."#".
                        db_formatar($totprojativ_atual,'f')."#".
                        db_formatar($totprojativ_empenhado,'f')."#".
                        db_formatar($totprojativ_anulado,'f')."#".
                        db_formatar($totprojativ_liquidado,'f')."#".
                        db_formatar($totprojativ_pago,'f')."#".
                        db_formatar(($totprojativ_empenhado_acumulado-$totprojativ_anulado_acumulado)-$totprojativ_liquidado_acumulado ,'f')."#".
                        db_formatar($totprojativ_empenhado_acumulado,'f')."#".
                        db_formatar($totprojativ_anulado_acumulado,'f')."#".
                        db_formatar($totprojativ_liquidado_acumulado,'f')."#".
                        db_formatar($totprojativ_pago_acumulado,'f')."#".
                        db_formatar($totprojativ_liquidado_acumulado-$totprojativ_pago_acumulado,'f');
      
      
      
      $totprojativ_dot_ini                 = 0;
      $totprojativ_suplementado_acumulado  = 0;
      $totprojativ_reduzido_acumulado      = 0;
      $totprojativ_atual                   = 0;
      $totprojativ_empenhado               = 0;
      $totprojativ_anulado                 = 0;
      $totprojativ_liquidado               = 0;
      $totprojativ_pago                    = 0;
      $totprojativ_atual_a_pagar           = 0;
      $totprojativ_empenhado_acumulado     = 0;
      $totprojativ_anulado_acumulado       = 0;
      $totprojativ_liquidado_acumulado     = 0;
      $totprojativ_pago_acumulado          = 0;
      $totprojativ_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "PROJ./ATIV.: ";
    if ($projativ != $o58_projativ) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
        $sEspaco= nivel(6);
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_projativ,"projativ").'  -  '.$o55_descr;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;      
      $projativ = $o58_projativ;
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
          $sEspaco= nivel(6);
        }
        $sRotulo = $descricao.db_formatar($o58_projativ,"projativ").'  -  '.$o55_descr;
        $aTextoSaida[] =$sRotulo;
      }
    }
    
    $totprojativ_dot_ini                 += $dot_ini;
    $totprojativ_suplementado_acumulado  += $suplementado_acumulado;
    $totprojativ_reduzido_acumulado      += $reduzido_acumulado;
    $totprojativ_atual                   += $atual;
    $totprojativ_empenhado               += $empenhado;
    $totprojativ_anulado                 += $anulado;
    $totprojativ_liquidado               += $liquidado;
    $totprojativ_pago                    += $pago;
    $totprojativ_atual_a_pagar           += $atual_a_pagar;
    $totprojativ_empenhado_acumulado     += $empenhado_acumulado;
    $totprojativ_anulado_acumulado       += $anulado_acumulado;
    $totprojativ_liquidado_acumulado     += $liquidado_acumulado;
    $totprojativ_pago_acumulado          += $pago_acumulado;
    $totprojativ_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_elemento) != "") {
    if ($nivelb == 7 && $nivela == 0) {
      $flag_imp = true;
    }
    
    /*
    if ($nivela == 0 && $nivelb == 7) {
      $descricao = "ELEMENTO";
    }
    
    if ($nivela == 9) {
      $descricao = "GRUPO NAT. DESP.";
    }
    
    if ($descricao != "ELEMENTO" &&
    $descricao != "GRUPO NAT. DESP.") {
      $descricao = "ELEMENTO";
    }
    */
    
    if ($elemento != "" && $elemento != $o58_elemento &&
    $nivelb   != 7  && $nivela   == 0             && $i > 0 &&
    ($totelemento_dot_ini               != 0 ||
    $totelemento_suplementado_acumulado != 0 ||
    $totelemento_reduzido_acumulado     != 0 ||
    $totelemento_atual                  != 0 ||
    $totelemento_empenhado              != 0 ||
    $totelemento_anulado                != 0 ||
    $totelemento_liquidado              != 0 ||
    $totelemento_pago                   != 0 ||
    $totelemento_empenhado_acumulado    != 0 ||
    $totelemento_anulado_acumulado      != 0 ||
    $totelemento_liquidado_acumulado    != 0 ||
    $totelemento_pago_acumulado         != 0)) {
      // Imprime total do elemento
      
      
      $aTextoSaida[] =  nivel(7).'TOTAL DO ELEMENTO'."#".
                        db_formatar($totelemento_dot_ini,'f')."#".
                        db_formatar($totelemento_suplementado_acumulado,'f')."#".
                        db_formatar($totelemento_reduzido_acumulado,'f')."#".
                        db_formatar((($totelemento_dot_ini + $totelemento_suplementado_acumulado) - $totelemento_reduzido_acumulado),'f')."#".
                        db_formatar($totelemento_atual,'f')."#".
                        db_formatar($totelemento_empenhado,'f')."#".
                        db_formatar($totelemento_anulado,'f')."#".
                        db_formatar($totelemento_liquidado,'f')."#".
                        db_formatar($totelemento_pago,'f')."#".
                        db_formatar(($totelemento_empenhado_acumulado-$totelemento_anulado_acumulado)-$totelemento_liquidado_acumulado ,'f')."#".
                        db_formatar($totelemento_empenhado_acumulado,'f')."#".
                        db_formatar($totelemento_anulado_acumulado,'f')."#".
                        db_formatar($totelemento_liquidado_acumulado,'f')."#".
                        db_formatar($totelemento_pago_acumulado,'f')."#".
                        db_formatar($totelemento_liquidado_acumulado-$totelemento_pago_acumulado,'f');
      
      
      
      $totelemento_dot_ini                 = 0;
      $totelemento_suplementado_acumulado  = 0;
      $totelemento_reduzido_acumulado      = 0;
      $totelemento_atual                   = 0;
      $totelemento_empenhado               = 0;
      $totelemento_anulado                 = 0;
      $totelemento_liquidado               = 0;
      $totelemento_pago                    = 0;
      $totelemento_atual_a_pagar           = 0;
      $totelemento_empenhado_acumulado     = 0;
      $totelemento_anulado_acumulado       = 0;
      $totelemento_liquidado_acumulado     = 0;
      $totelemento_pago_acumulado          = 0;
      $totelemento_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "ELEMENTO: ";
    if ($elemento != $o58_elemento) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
        $sEspaco= nivel(7);
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_elemento,"elemento").'  -  '.$o56_descr;
      if($iContaNivel > 1)  $aTextoSaida[] =$sRotulo;
      $elemento = $o58_elemento;
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
          $sEspaco= nivel(7);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_elemento,"elemento").'  -  '.$o56_descr;
        $aTextoSaida[] =$sRotulo;
      }
    }
    
    $totelemento_dot_ini                 += $dot_ini;
    $totelemento_suplementado_acumulado  += $suplementado_acumulado;
    $totelemento_reduzido_acumulado      += $reduzido_acumulado;
    $totelemento_atual                   += $atual;
    $totelemento_empenhado               += $empenhado;
    $totelemento_anulado                 += $anulado;
    $totelemento_liquidado               += $liquidado;
    $totelemento_pago                    += $pago;
    $totelemento_atual_a_pagar           += $atual_a_pagar;
    $totelemento_empenhado_acumulado     += $empenhado_acumulado;
    $totelemento_anulado_acumulado       += $anulado_acumulado;
    $totelemento_liquidado_acumulado     += $liquidado_acumulado;
    $totelemento_pago_acumulado          += $pago_acumulado;
    $totelemento_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if (trim(@$o58_codigo) != "") {
    if ($nivelb == 8) {
      $flag_imp = true;
    }
    
    if ($codigo != "" && $codigo != $o58_codigo &&
    $nivelb != 8  && $i > 0                 &&
    ($totrecurso_dot_ini               != 0 ||
    $totrecurso_suplementado_acumulado != 0 ||
    $totrecurso_reduzido_acumulado     != 0 ||
    $totrecurso_atual                  != 0 ||
    $totrecurso_empenhado              != 0 ||
    $totrecurso_anulado                != 0 ||
    $totrecurso_liquidado              != 0 ||
    $totrecurso_pago                   != 0 ||
    $totrecurso_empenhado_acumulado    != 0 ||
    $totrecurso_anulado_acumulado      != 0 ||
    $totrecurso_liquidado_acumulado    != 0 ||
    $totrecurso_pago_acumulado         != 0)) {
      // Imprime total do recurso
      
      
      
      $aTextoSaida[] =  
                        nivel(8).'TOTAL DO RECURSO'."#".
                        db_formatar($totrecurso_dot_ini,'f')."#".
                        db_formatar($totrecurso_suplementado_acumulado,'f')."#".
                        db_formatar($totrecurso_reduzido_acumulado,'f')."#".
                        db_formatar((($totrecurso_dot_ini + $totrecurso_suplementado_acumulado) - $totrecurso_reduzido_acumulado),'f')."#".
                        db_formatar($totrecurso_atual,'f')."#".
                        db_formatar($totrecurso_empenhado,'f')."#".
                        db_formatar($totrecurso_anulado,'f')."#".
                        db_formatar($totrecurso_liquidado,'f')."#".
                        db_formatar($totrecurso_pago,'f')."#".
                        db_formatar(($totrecurso_empenhado_acumulado-$totrecurso_anulado_acumulado)-$totrecurso_liquidado_acumulado ,'f')."#".
                        db_formatar($totrecurso_empenhado_acumulado,'f')."#".
                        db_formatar($totrecurso_anulado_acumulado,'f')."#".
                        db_formatar($totrecurso_liquidado_acumulado,'f')."#".
                        db_formatar($totrecurso_pago_acumulado,'f')."#".
                        db_formatar($totrecurso_liquidado_acumulado-$totrecurso_pago_acumulado,'f');
      
      
      
      $totrecurso_dot_ini                 = 0;
      $totrecurso_suplementado_acumulado  = 0;
      $totrecurso_reduzido_acumulado      = 0;
      $totrecurso_atual                   = 0;
      $totrecurso_empenhado               = 0;
      $totrecurso_anulado                 = 0;
      $totrecurso_liquidado               = 0;
      $totrecurso_pago                    = 0;
      $totrecurso_atual_a_pagar           = 0;
      $totrecurso_empenhado_acumulado     = 0;
      $totrecurso_anulado_acumulado       = 0;
      $totrecurso_liquidado_acumulado     = 0;
      $totrecurso_pago_acumulado          = 0;
      $totrecurso_atual_a_pagar_liquidado = 0;
      
      
    }
    
    $descricao = "RECURSO: ";
    
    if ($codigo != $o58_codigo) {
    	$sEspaco = "";
      if ($flag_nivel == true) {
       $sEspaco= nivel(8);
      }
      $sRotulo = $sEspaco.$descricao.db_formatar($o58_codigo,"recurso").'  -  '.$o15_descr;
     //  $aTextoSaida[] =$sRotulo;
      $codigo  = $o58_codigo;
      if ($flag_imp == false) {
        $flag_lib8 = true;
      }
    } else {
      if ($flag_imp == true) {
      	$sEspaco = "";
        if ($flag_nivel == true) {
          $sEspaco= nivel(8);
        }
        $sRotulo = $sEspaco.$descricao.db_formatar($o58_codigo,"recurso").'  -  '.$o15_descr;
       // $aTextoSaida[] =$sRotulo;
      }
    }
    
    $totrecurso_dot_ini                 += $dot_ini;
    $totrecurso_suplementado_acumulado  += $suplementado_acumulado;
    $totrecurso_reduzido_acumulado      += $reduzido_acumulado;
    $totrecurso_atual                   += $atual;
    $totrecurso_empenhado               += $empenhado;
    $totrecurso_anulado                 += $anulado;
    $totrecurso_liquidado               += $liquidado;
    $totrecurso_pago                    += $pago;
    $totrecurso_atual_a_pagar           += $atual_a_pagar;
    $totrecurso_empenhado_acumulado     += $empenhado_acumulado;
    $totrecurso_anulado_acumulado       += $anulado_acumulado;
    $totrecurso_liquidado_acumulado     += $liquidado_acumulado;
    $totrecurso_pago_acumulado          += $pago_acumulado;
    $totrecurso_atual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
    
    $flag_nivel = true;
    $mult_nivel++;
  }
  
  if ($flag_imp == true) {
    
    
    $aTextoSaida[] =  $sRotulo."#".
                      db_formatar($dot_ini,'f')               ."#".
                      db_formatar($suplementado_acumulado,'f')."#".
                      db_formatar($reduzido_acumulado,'f')    ."#".
                      db_formatar((($dot_ini + $suplementado_acumulado) - $reduzido_acumulado),'f')."#".
                      db_formatar($atual,'f')                 ."#".
                      db_formatar($empenhado,'f')             ."#".
                      db_formatar($anulado,'f')               ."#".
                      db_formatar($liquidado,'f')             ."#".
                      db_formatar($pago,'f')                  ."#".
                      db_formatar(($empenhado_acumulado-$anulado_acumulado)-$liquidado_acumulado ,'f')."#".
                      db_formatar($empenhado_acumulado,'f')   ."#".
                      db_formatar($anulado_acumulado,'f')     ."#".
                      db_formatar($liquidado_acumulado,'f')   ."#".
                      db_formatar($pago_acumulado,'f')        ."#".
                      db_formatar($liquidado_acumulado-$pago_acumulado,'f');
    
  }
  
  $totgeraldot_ini                 += $dot_ini;
  $totgeralsuplementado_acumulado  += $suplementado_acumulado;
  $totgeralreduzido_acumulado      += $reduzido_acumulado;
  $totgeralatual                   += $atual;
  $totgeralempenhado               += $empenhado;
  $totgeralanulado                 += $anulado;
  $totgeralliquidado               += $liquidado;
  $totgeralpago                    += $pago;
  $totgeralatual_a_pagar           += $atual_a_pagar;
  $totgeralempenhado_acumulado     += $empenhado_acumulado;
  $totgeralanulado_acumulado       += $anulado_acumulado;
  $totgeralliquidado_acumulado     += $liquidado_acumulado;
  $totgeralpago_acumulado          += $pago_acumulado;
  $totgeralatual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
}

if ($subfuncao != "" && $nivelb != 4) {
 
  
  $aTextoSaida[] =  nivel(4)."TOTAL DA SUBFUNCAO"."#".
                    db_formatar($totsubfuncao_dot_ini,'f')."#".
                    db_formatar($totsubfuncao_suplementado_acumulado,'f')."#".
                    db_formatar($totsubfuncao_reduzido_acumulado,'f')."#".
                    db_formatar((($totsubfuncao_dot_ini + $totsubfuncao_suplementado_acumulado) - $totsubfuncao_reduzido_acumulado),'f')."#".
                    db_formatar($totsubfuncao_atual,'f')."#".
                    db_formatar($totsubfuncao_empenhado,'f')."#".
                    db_formatar($totsubfuncao_anulado,'f')."#".
                    db_formatar($totsubfuncao_liquidado,'f')."#".
                    db_formatar($totsubfuncao_pago,'f')."#".
                    db_formatar(($totsubfuncao_empenhado_acumulado-$totsubfuncao_anulado_acumulado)-$totsubfuncao_liquidado_acumulado ,'f')."#".
                    db_formatar($totsubfuncao_empenhado_acumulado,'f')."#".
                    db_formatar($totsubfuncao_anulado_acumulado,'f')."#".
                    db_formatar($totsubfuncao_liquidado_acumulado,'f')."#".
                    db_formatar($totsubfuncao_pago_acumulado,'f')."#".
                    db_formatar($totsubfuncao_liquidado_acumulado-$totsubfuncao_pago_acumulado,'f');
  
 
}

if ($funcao != "" && $nivelb != 3) {
   
  $aTextoSaida[] =  
                    nivel(3)."TOTAL DA FUNCAO"."#".
                    db_formatar($totfuncao_dot_ini,'f')."#".
                    db_formatar($totfuncao_suplementado_acumulado,'f')."#".
                    db_formatar($totfuncao_reduzido_acumulado,'f')."#".
                    db_formatar((($totfuncao_dot_ini + $totfuncao_suplementado_acumulado) - $totfuncao_reduzido_acumulado),'f')."#".
                    db_formatar($totfuncao_atual,'f')."#".
                    db_formatar($totfuncao_empenhado,'f')."#".
                    db_formatar($totfuncao_anulado,'f')."#".
                    db_formatar($totfuncao_liquidado,'f')."#".
                    db_formatar($totfuncao_pago,'f')."#".
                    db_formatar(($totfuncao_empenhado_acumulado-$totfuncao_anulado_acumulado)-$totfuncao_liquidado_acumulado ,'f')."#".
                    db_formatar($totfuncao_empenhado_acumulado,'f')."#".
                    db_formatar($totfuncao_anulado_acumulado,'f')."#".
                    db_formatar($totfuncao_liquidado_acumulado,'f')."#".
                    db_formatar($totfuncao_pago_acumulado,'f')."#".
                    db_formatar($totfuncao_liquidado_acumulado-$totfuncao_pago_acumulado,'f');
  
 }

if ($unidade != "" && $nivelb != 2) {
 
  
  $aTextoSaida[] =  
                    nivel(2)."TOTAL DA UNIDADE"."#".
                    db_formatar($totunidade_dot_ini,'f')."#".
                    db_formatar($totunidade_suplementado_acumulado,'f')."#".
                    db_formatar($totunidade_reduzido_acumulado,'f')."#".
                    db_formatar((($totunidade_dot_ini + $totunidade_suplementado_acumulado) - $totunidade_reduzido_acumulado),'f')."#".
                    db_formatar($totunidade_atual,'f')."#".
                    db_formatar($totunidade_empenhado,'f')."#".
                    db_formatar($totunidade_anulado,'f')."#".
                    db_formatar($totunidade_liquidado,'f')."#".
                    db_formatar($totunidade_pago,'f')."#".
                    db_formatar(($totunidade_empenhado_acumulado-$totunidade_anulado_acumulado)-$totunidade_liquidado_acumulado ,'f')."#".
                    db_formatar($totunidade_empenhado_acumulado,'f')."#".
                    db_formatar($totunidade_anulado_acumulado,'f')."#".
                    db_formatar($totunidade_liquidado_acumulado,'f')."#".
                    db_formatar($totunidade_pago_acumulado,'f')."#".
                    db_formatar($totunidade_liquidado_acumulado-$totunidade_pago_acumulado,'f');
 
}

if ($orgao != "" && $nivelb != 1) {
  
  $aTextoSaida[] = 
                    nivel(1)."TOTAL DO ORGAO"."#".
                    db_formatar($totorgao_dot_ini,'f')."#".
                    db_formatar($totorgao_suplementado_acumulado,'f')."#".
                    db_formatar($totorgao_reduzido_acumulado,'f')."#".
                    db_formatar((($totorgao_dot_ini + $totorgao_suplementado_acumulado) - $totorgao_reduzido_acumulado),'f')."#".
                    db_formatar($totorgao_atual,'f')."#".
                    db_formatar($totorgao_empenhado,'f')."#".
                    db_formatar($totorgao_anulado,'f')."#".
                    db_formatar($totorgao_liquidado,'f')."#".
                    db_formatar($totorgao_pago,'f')."#".
                    db_formatar(($totorgao_empenhado_acumulado-$totorgao_anulado_acumulado)-$totorgao_liquidado_acumulado ,'f')."#".
                    db_formatar($totorgao_empenhado_acumulado,'f')."#".
                    db_formatar($totorgao_anulado_acumulado,'f')."#".
                    db_formatar($totorgao_liquidado_acumulado,'f')."#".
                    db_formatar($totorgao_pago_acumulado,'f')."#".
                    db_formatar($totorgao_liquidado_acumulado-$totorgao_pago_acumulado,'f');

}
   $aTextoSaida[] =  " # # # # #";
$aTextoSaida[] =  "TOTAL GERAL"."#".
										db_formatar($totgeraldot_ini,'f')."#".
										db_formatar($totgeralsuplementado_acumulado,'f')."#".
										db_formatar($totgeralreduzido_acumulado,'f')."#".
										db_formatar($totgeraldot_ini + $totgeralsuplementado_acumulado - $totgeralreduzido_acumulado,'f')."#".
										db_formatar($totgeralatual,'f')."#".
										db_formatar($totgeralempenhado,'f')."#".
										db_formatar($totgeralanulado,'f')."#".
										db_formatar($totgeralliquidado,'f')."#".
										db_formatar($totgeralpago,'f')."#".
										db_formatar($totgeralempenhado_acumulado - $totgeralanulado_acumulado - $totgeralliquidado_acumulado,'f')."#".
										db_formatar($totgeralempenhado_acumulado,'f')."#".
										db_formatar($totgeralanulado_acumulado,'f')."#".
										db_formatar($totgeralliquidado_acumulado,'f')."#".
										db_formatar($totgeralpago_acumulado,'f')."#".
										db_formatar($totgeralatual_a_pagar_liquidado,'f');


if ($nivela > 0) {
  
  $sql_dotacao = db_dotacaosaldo(7,2,2,true,$sele_work,$anousu,$dataini,$datafin,null,null,true);

//  echo $sql_dotacao; exit;
  
  $sql = "select o58_elemento,
  sum(dot_ini)                 as dot_ini,
  sum(saldo_anterior)          as saldo_anterior,
  sum(empenhado)               as empenhado,
  sum(anulado)                 as anulado,
  sum(liquidado)               as liquidado,
  sum(pago)                    as pago,
  sum(suplementado)            as suplementado,
  sum(reduzido)                as reduzido,
  sum(atual)                   as atual,
  sum(reservado)               as reservado,
  sum(atual_menos_reservado)   as atual_menos_reservado,
  sum(atual_a_pagar)           as atual_a_pagar,
  sum(atual_a_pagar_liquidado) as atual_a_pagar_liquidado,
  sum(empenhado_acumulado)     as empenhado_acumulado,
  sum(anulado_acumulado)       as anulado_acumulado,
  sum(liquidado_acumulado)     as liquidado_acumulado,
  sum(pago_acumulado)          as pago_acumulado,
  sum(suplementado_acumulado)  as suplementado_acumulado,
  sum(reduzido_acumulado)      as reduzido_acumulado
  from($sql_dotacao) as xx
      inner join orcelemento on orcelemento.o56_anousu = ".db_getsession("DB_anousu")."
  where substr(orcelemento.o56_elemento,4,10) = '0000000000' and
        substr(orcelemento.o56_elemento,2,1) != '' and
        substr(orcelemento.o56_elemento,3,1) != '0' 
  group by o58_elemento
  order by o58_elemento";
  
//  echo $sql;  exit;
  $result = pg_exec($sql);
//  db_criatabela($result);  exit;
  
  // TOTAIS GERAIS
  $totgeraldot_ini                   = 0;
  $totgeralsuplementado_acumulado    = 0;
  $totgeralreduzido_acumulado        = 0;
  $totgeralatual                     = 0;
  $totgeralempenhado                 = 0;
  $totgeralanulado                   = 0;
  $totgeralliquidado                 = 0;
  $totgeralpago                      = 0;
  $totgeralatual_a_pagar             = 0;
  $totgeralempenhado_acumulado       = 0;
  $totgeralanulado_acumulado         = 0;
  $totgeralliquidado_acumulado       = 0;
  $totgeralpago_acumulado            = 0;
  $totgeralatual_a_pagar_liquidado   = 0;

  // TOTAIS
  $totaldot_ini                      = 0;
  $totalsuplementado_acumulado       = 0;
  $totalreduzido_acumulado           = 0;
  $totalatual                        = 0;
  $totalempenhado                    = 0;
  $totalanulado                      = 0;
  $totalliquidado                    = 0;
  $totalpago                         = 0;
  $totalatual_a_pagar                = 0;
  $totalempenhado_acumulado          = 0;
  $totalanulado_acumulado            = 0;
  $totalliquidado_acumulado          = 0;
  $totalpago_acumulado               = 0;
  $totalatual_a_pagar_liquidado      = 0;
 
  if (pg_numrows($result) != 0){
    db_fieldsmemory($result,0);
    $aTextoSaida[] =  " # # # # # ";
  }

  $retorno = $o58_elemento; 
  for ($i=0; $i<pg_numrows($result); $i++) {
    db_fieldsmemory($result,$i);
    

    $totaldot_ini                 += $dot_ini;
    $totalsuplementado_acumulado  += $suplementado_acumulado;
    $totalreduzido_acumulado      += $reduzido_acumulado;
    $totalatual                   += $atual;
    $totalempenhado               += $empenhado;
    $totalanulado                 += $anulado;
    $totalliquidado               += $liquidado;
    $totalpago                    += $pago;
    $totalatual_a_pagar           += $atual_a_pagar;
    $totalempenhado_acumulado     += $empenhado_acumulado;
    $totalanulado_acumulado       += $anulado_acumulado;
    $totalliquidado_acumulado     += $liquidado_acumulado;
    $totalpago_acumulado          += $pago_acumulado;
    $totalatual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;

    if (substr($retorno,0,3) != substr($o58_elemento,0,3)){
      $res_elemento = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"o56_elemento,o56_descr",null,"o56_anousu = ".db_getsession("DB_anousu")." and o56_elemento = '".substr($retorno,0,3)."0000000000' limit 1"));
//      if ($clorcelemento->numrows > 0){
        db_fieldsmemory($res_elemento,0);

        $sSaida        =  db_formatar($o56_elemento,"elemento").'  -  '.$o56_descr.
        $aTextoSaida[] =  " # ";
    
        $aTextoSaida[] =  nivel(7)."TOTAL DO ELEMENTO"."#".
                          db_formatar($totaldot_ini,'f')."#".
                          db_formatar($totalsuplementado_acumulado,'f')."#".
                          db_formatar($totalreduzido_acumulado,'f')."#".
                          db_formatar((($totaldot_ini + $totalsuplementado_acumulado) - $totalreduzido_acumulado),'f')."#".
                          db_formatar($totalatual,'f');
      
        $aTextoSaida[] =  "#".
                          db_formatar($totalempenhado,'f')."#".
                          db_formatar($totalanulado,'f')."#".
                          db_formatar($totalliquidado,'f')."#".
                          db_formatar($totalpago,'f')."#".
                          db_formatar(($totalempenhado_acumulado-$totalanulado_acumulado)-$totalliquidado_acumulado ,'f');
    
        $aTextoSaida[] =  " "."#".
                          db_formatar($totalempenhado_acumulado,'f')."#".
                          db_formatar($totalanulado_acumulado,'f')."#".
                          db_formatar($totalliquidado_acumulado,'f')."#".
                          db_formatar($totalpago_acumulado,'f')."#".
                          db_formatar($totalatual_a_pagar_liquidado,'f');
        $aTextoSaida[] =  " # # # # # ";

        $totaldot_ini                 = 0;
        $totalsuplementado_acumulado  = 0;
        $totalreduzido_acumulado      = 0;
        $totalatual                   = 0;
        $totalempenhado               = 0;
        $totalanulado                 = 0;
        $totalliquidado               = 0;
        $totalpago                    = 0;
        $totalatual_a_pagar           = 0;
        $totalempenhado_acumulado     = 0;
        $totalanulado_acumulado       = 0;
        $totalliquidado_acumulado     = 0;
        $totalpago_acumulado          = 0;
        $totalatual_a_pagar_liquidado = 0;
//      }
      
      $retorno = $o58_elemento;
    }
    
    $totgeraldot_ini                 += $dot_ini;
    $totgeralsuplementado_acumulado  += $suplementado_acumulado;
    $totgeralreduzido_acumulado      += $reduzido_acumulado;
    $totgeralatual                   += $atual;
    $totgeralempenhado               += $empenhado;
    $totgeralanulado                 += $anulado;
    $totgeralliquidado               += $liquidado;
    $totgeralpago                    += $pago;
    $totgeralatual_a_pagar           += $atual_a_pagar;
    $totgeralempenhado_acumulado     += $empenhado_acumulado;
    $totgeralanulado_acumulado       += $anulado_acumulado;
    $totgeralliquidado_acumulado     += $liquidado_acumulado;
    $totgeralpago_acumulado          += $pago_acumulado;
    $totgeralatual_a_pagar_liquidado += $liquidado_acumulado-$pago_acumulado;
  }


  $aTextoSaida[] =  " # # # # # ";
  $aTextoSaida[] = 
                    'TOTAL GERAL'."#".
                    db_formatar($totgeraldot_ini,'f')."#".
                    db_formatar($totgeralsuplementado_acumulado,'f')."#".
                    db_formatar($totgeralreduzido_acumulado,'f')."#".
                    db_formatar($totgeraldot_ini + $totgeralsuplementado_acumulado - $totgeralreduzido_acumulado,'f')."#".
                    db_formatar($totgeralatual,'f')."#".
                    db_formatar($totgeralempenhado,'f')."#".
                    db_formatar($totgeralanulado,'f')."#".
                    db_formatar($totgeralliquidado,'f')."#".
                    db_formatar($totgeralpago,'f')."#".
                    db_formatar($totgeralempenhado_acumulado - $totgeralanulado_acumulado - $totgeralliquidado_acumulado,'f')."#".
                    db_formatar($totgeralempenhado_acumulado,'f')."#".
                    db_formatar($totgeralanulado_acumulado,'f')."#".
                    db_formatar($totgeralliquidado_acumulado,'f')."#".
                    db_formatar($totgeralpago_acumulado,'f')."#".
                    db_formatar($totgeralatual_a_pagar_liquidado,'f');
}



pg_free_result($result);

  foreach($aTextoSaida as $aLinhaSaida){
    fputcsv($fArquivo,split("#", $aLinhaSaida), ";");
  }
  fclose($fArquivo);
  db_redireciona($sArquivo);
  
?>