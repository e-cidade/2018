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
include ("libs/db_liborcamento.php");
include ("classes/db_empresto_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_orcprojativ_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempresto    = new cl_empresto;
$clrotulo      = new rotulocampo;
$clorcelemento = new cl_orcelemento;
$clorcprojativ = new cl_orcprojativ;

//função para retornar desdobramento
function retorna_desdob($elemento,$e64_codele,$clorcelemento){
  
 return  pg_query($clorcelemento->sql_query_file(null,null,"o56_elemento as estrutural,o56_descr as descr",null,"o56_codele = $e64_codele and o56_elemento like '$elemento%'")); 

  }


$troca = 1;

function cabecalho(&$pdf,&$troca){

if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

      $tam  = "10";
      $tam2 = "5";
      $pdf->addpage("L");
      $pdf->SetFont('Arial', 'B',8);
      $pdf->Cell(100, $tam, "Dados cadastrais dos empenhos", 1, 0, "C", 1);
      $pdf->Cell(40, $tam, "Saldos a pagar anteriores", 1, 0, "C", 1);
      $alturacabecalho=$pdf->gety();
      $distanciacabecalho=$pdf->getx();
      $pdf->Cell(80, $tam2, "Movimentação dos restos a pagar no período", 1, 1, "C", 1);
      $pdf->setxy($distanciacabecalho, $alturacabecalho+5);
      $pdf->Cell(40, $tam2, "Anulação", 1, 0 , "C", 1);
      $pdf->Cell(40, $tam2, "", 1, 0 , "C", 1);
      $pdf->setxy($distanciacabecalho+80,$alturacabecalho);
      $pdf->Cell(60, $tam, "Saldo a pagar finais", 1, 1, "C", 1);

      $pdf->Cell(15, $tam, "Empenho", 1, 0, "C", 1);
      $pdf->Cell(15, $tam, "Emissão", 1, 0, "C", 1);
      $pdf->Cell(70, $tam, "Credor",  1, 0, "C", 1);

      $pdf->Cell(20, $tam, "RP não proc", 1, 0, "C", 1);
      $pdf->Cell(20, $tam, "RP proc",     1, 0, "C", 1);

      $pdf->Cell(20, $tam, "RP não proc", 1, 0, "C", 1);
      $pdf->Cell(20, $tam, "RP proc",     1, 0, "C", 1);
      $pdf->Cell(20, $tam, "Liquidação",  1, 0, "C", 1);
      $pdf->Cell(20, $tam, "Pagamento",   1, 0, "C", 1);

      $pdf->Cell(20, $tam, "A liquidar ", 1, 0, "C", 1);
      $pdf->Cell(20, $tam, "Liquidados ",  1, 0, "C", 1);
      $pdf->Cell(20, $tam, "Geral ",      1, 1, "C", 1);

      $pdf->SetFont('Arial', '',8);
      $troca = 0;
      $iYlinha = $pdf->getY();

}


}


$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}

$sele_work = ' e60_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
$sele_work1 = '';//tipo de recurso
$anoatual=db_getsession("DB_anousu");
if ($tipo=="or"){
  $tipofiltro="Órgão";
}

if ($tipo=="un"){
    $tipofiltro="Unidade";
} 

if ($tipo=="fu"){
    $tipofiltro="Função";
} 

if ($tipo=="su"){
    $tipofiltro="Subfunção";
} 
if ($tipo=="pr"){
    $tipofiltro="Programa";
} 

if ($tipo=="pa"){
    $tipofiltro="Projeto Atividade";
} 

if ($tipo=="el"){
    $tipofiltro="Elemento";
} 

if ($tipo=="de"){
    $tipofiltro="Desdobramento";
} 
if ($tipo=="re"){
    $tipofiltro="Recurso";
} 

if ($tipo=="tr"){
    $tipofiltro="Tipo de Resto";
} 

if ($tipo=="cr"){
    $tipofiltro="Credor";
} 

if ($commov=="0"){
  $commovfiltro= "Todos";
}

if ($commov=="1"){
  $commovfiltro= "Com movimento até a data";
}

if ($commov=="2"){
  $commovfiltro= "Com saldo a pagar";
}

if ($commov=="3"){
  $commovfiltro= "Liquidados";
}
if ($commov=="4"){
  $commovfiltro= "Anulados";
}
if ($commov=="5"){
  $commovfiltro= "Pagos";
}

if ($commov=="6"){
    $commovfiltro= "Não liquidados";
}




$head5 = "INSTITUIÇÕE(S): ".$descr_inst. "\nPosição até: ".$dtfim. "\nAgrupado por: ".$tipofiltro. "\nRestos a pagar:".$commovfiltro;

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$tam="10";
$tam2="5";
            
//filtro por posição
$dtini = db_getsession("DB_anousu").'-01-01';
$dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;



//filtro por agrupamento
$sql_order = "";
if ($tipo=="or"){// órgão - tabela orcdotacao
$sql_order = " order by o58_orgao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="un"){// unidade - tabela orcdotacao
$sql_order = " order by  o58_orgao,o58_unidade,e60_anousu,e60_codemp::integer ";
}

if ($tipo=="fu"){//função  - tabela orcdotacao
$sql_order = " order by o58_funcao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="su"){//subfunção - tabela orcdotacao
$sql_order = " order by o58_subfuncao,e60_anousu,e60_codemp::integer";
}

if ($tipo=="pr"){//programa - tabela orcdotacao
$sql_order = " order by o58_programa,e60_anousu,e60_codemp::integer";
}

if ($tipo=="pa"){//projeto atividade - tabela orcdotacao
$sql_order = " order by o58_projativ,e60_anousu,e60_codemp::integer";
}

if ($tipo=="el"){//elemento - tabela orcdotacao
$sql_order = " order by o58_codele,e60_anousu,e60_codemp::integer";
}


if ($tipo=="de"){//desdobramento-tabela empelemento
$sql_order = " order by e64_codele,e60_anousu,e60_codemp::integer";
}

if ($tipo=="re"){//recurso - tabela orcdotacao
$sql_order = " order by o58_codigo,e60_anousu,e60_codemp::integer";
}


if ($tipo=="tr"){//resto - tabela empresto 
$sql_order = "order by e91_codtipo,e60_anousu,e60_codemp::integer";
}


if ($tipo=="cr"){//credor - tabela cgm
$sql_order = " order by z01_nome,e60_anousu,e60_codemp::integer ";
}


//filtro por restos a pagar
 $sql_where_externo = " ";
if ($commov=="0"){//geral
 $sql_where_externo .= "  ";
}

if ($commov=="1"){//com movimento até a data
$sql_where_externo = "and (round(vlranu,2) + round(vlrliq,2) + round(vlrpag,2)) > 0 and $sele_work";


}

if ($commov=="2"){//com saldo a pagar ok
$sql_where_externo .="and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - (round(e91_vlrpag, 2) + round(vlrpag, 2))) > 0)";

}

if ($commov=="3"){//liquidados
 $sql_where_externo .= "and (round(vlrliq,2)) > 0 ";
}

if ($commov=="4"){//anulados
 $sql_where_externo .= " and (round(vlranu,2)) > 0";

}


if ($commov=="5"){//pagos
 $sql_where_externo .= "and (round(vlrpag,2)) > 0";

}

if ($commov=="6"){//não liquidados
 $sql_where_externo .= "and (((round(round(e91_vlremp, 2) - (round(e91_vlranu, 2) + round(vlranu, 2)), 2)) - (round(e91_vlrliq, 2) + round(vlrliq, 2))) > 0) ";

}

//filtro por exercicio
if ($exercicio!=0){
    $sql_where_externo.=' and e60_anousu = '.$exercicio;
}



$sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");
//echo "$sqlempresto";exit;

$res = $clempresto->sql_record($sqlempresto);

if ($clempresto->numrows == 0) {
   db_redireciona("db_erros.php?fechar=true&db_erro=Sem movimentação de restos a pagar.");
     exit;
}

$rows = $clempresto->numrows;

//variaveis agrupamentos
$vnumcgm=null;
$vorgao=null;
$vunidade=null;
$vfuncao=null;
$vsubfuncao=null;
$vprojativ=null;
$velemento=null;
$vdesdobramento=null;
$vrecurso=null;
$vprograma=null;
$vtiporesto=null;


//subtotal
$vorgaosub                  = 0;
$vunidadesub                = 0;
$vfuncaosub                 = 0;
$vsubfuncaosub              = 0;
$vprogramasub               = 0;
$vprojativsub               = 0;
$velementosub               = 0;
$vrecursosub                = 0;
$vtiporestosub              = 0;
$vnumcgmsub                 = 0;
$vdesdobramentosub          = 0;

$subtotal_rp_n_proc         = 0;
$subtotal_rp_proc           = 0;
$subtotal_anula_rp_n_proc   = 0;
$subtotal_anula_rp_proc     = 0;
$subtotal_mov_liquida       = 0;
$subtotal_mov_pagmento      = 0;
$subtotal_aliquidar_finais  = 0;
$subtotal_liquidados_finais = 0;
$subtotal_geral_finais      = 0;


//total
$total_rp_n_proc         =0;
$total_rp_proc           =0;

$total_anula_rp_n_proc   =0;
$total_anula_rp_proc     =0;


$total_mov_liquida       =0;
$total_mov_pagmento      =0;

$total_aliquidar_finais  =0;
$total_liquidados_finais =0;
$total_geral_finais      =0;
//


$verifica=true;
$estrutura="";
$projativ="";
$o55anousu="";
$vprojativ="";
for ($x = 0; $x < $rows; $x ++) {
     db_fieldsmemory($res, $x);

cabecalho($pdf,$troca);
$troca=0;

//subtotal
if ($vorgaosub!=$o58_orgao and $tipo=="or"){
       if ($vorgaosub!=0){
                   
                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais) ,'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
 $vorgaosub = $o58_orgao;
}

if ($vunidadesub!=$o58_unidade and $tipo=="un"){
       if ($vunidadesub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais) ,'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
 $vunidadesub = $o58_unidade;
}

if ($vfuncaosub!=$o58_funcao and $tipo=="fu"){
       if ($vfuncaosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais) ,'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
 $vfuncaosub=$o58_funcao;
}



if ($vsubfuncaosub!=$o58_subfuncao and $tipo=="su"){
       if ($vsubfuncaosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vsubfuncaosub=$o58_subfuncao;
}


if ($vprogramasub!=$o58_programa and $tipo=="pr"){
       if ($vprogramasub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'),"TBL" , 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vprogramasub=$o58_programa;
}


if ($vprojativsub!=$o58_projativ and $tipo=="pa"){
       if ($vprojativsub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vprojativsub=$o58_projativ;
}



if ($velementosub!=$o56_elemento and $tipo=="el"){
       if ($velementosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais) ,'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$velementosub=$o56_elemento;
}


if ($vdesdobramentosub!=$e64_codele and $tipo=="de"){
       if ($vdesdobramentosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vdesdobramentosub=$e64_codele;
}

if ($vrecursosub!=$e91_recurso and $tipo=="re"){
       if ($vrecursosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vrecursosub=$e91_recurso;
}


if ($vtiporestosub!=$e91_codtipo and $tipo=="tr"){
       if ($vtiporestosub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vtiporestosub=$e91_codtipo;
}


if ($vnumcgmsub!=$z01_numcgm and $tipo=="cr"){
       if ($vnumcgmsub!=0){

                   $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
                   $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


                   $subtotal_rp_n_proc         = 0;
                   $subtotal_rp_proc           = 0;
                   $subtotal_anula_rp_n_proc   = 0;
                   $subtotal_anula_rp_proc     = 0;
                   $subtotal_mov_liquida       = 0;
                   $subtotal_mov_pagmento      = 0;
                   $subtotal_aliquidar_finais  = 0;
                   $subtotal_liquidados_finais = 0;
                   $subtotal_geral_finais      = 0;

}
$vnumcgmsub=$z01_numcgm;
}



//filtro por órgão
if ($tipo=="or" and $vorgao!=$o58_orgao){//orgão
 if (isset($quebradepagina) and $verifica==false){
   $troca=1;
   cabecalho($pdf,$troca);
    }

   $pdf->SetFont('Arial', 'B',8);
   $pdf->cell(0, 2,"", 0, 1, "", 0);
   $pdf->cell(0, 5,"Orgão: $o58_orgao $o40_descr ", 0, 1, "L", 0);
   $vorgao=$o58_orgao;
   $verifica=false;
}

if ($tipo=="un" and  $vunidade!=$o58_unidade){//unidade
    if (isset($quebradepagina) and $verifica==false){
        $troca=1;
        cabecalho($pdf,$troca);
        }
    
    $pdf->SetFont('Arial', 'B',8);
    $pdf->cell(0, 2,"", 0, 1, "", 0);
    $pdf->cell(0, 5,"Órgão:$o58_orgao $o40_descr  ", 0, 1, "L", 0);
    $pdf->cell(0, 5,"Unidade:$o58_unidade $o41_descr  ", 0, 1, "L", 0);
    $vunidade=$o58_unidade; 
    $verifica=false;
}

if ($tipo=="fu" and $vfuncao!=$o58_funcao){//função
      if (isset($quebradepagina) and $verifica==false){
          $troca=1;
          cabecalho($pdf,$troca);
          }

      $pdf->SetFont('Arial', 'B',8);
      $pdf->cell(0, 2,"", 0, 1, "", 0);
      $pdf->cell(0, 5,"Função:$o58_funcao $o52_descr", 0, 1, "L", 0);
      $vfuncao=$o58_funcao;
      $verifica=false;
 }

if ($tipo=="su" and  $vsubfuncao!=$o58_subfuncao){//subfuncao
       if (isset($quebradepagina) and $verifica==false){
           $troca=1;
           cabecalho($pdf,$troca);
           }
        $pdf->SetFont('Arial', 'B',8);
        $pdf->cell(0, 2,"", 0, 1, "", 0);
        $pdf->cell(0, 5,"Subfunção:$o58_subfuncao $o53_descr  ", 0, 1, "L", 0);
        $vsubfuncao=$o58_subfuncao;
        $verifica=false;
}

if ($tipo=="pr" and $vprograma!=$o58_programa){//programa
          if (isset($quebradepagina) and $verifica==false){
                   $troca=1;
                   cabecalho($pdf,$troca);
             }
          $pdf->SetFont('Arial', 'B',8);
          $pdf->cell(0, 2,"", 0, 1, "", 0);
          $pdf->cell(0,5,"Programa:$o58_programa $o54_descr ", 0, 1, "L", 0);
          $vprograma=$o58_programa;
          $verifica=false;
}

if ($tipo=="pa" and $vprojativ!=$o58_projativ ){//projetto atividade
   if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
      }
    if ($vprojativ!=$o58_projativ or $o55anousu!=$e60_anousu){
    
      
             $pdf->SetFont('Arial', 'B',8);
             $pdf->cell(0, 2,"", 0, 1, "", 0);
             $pdf->cell(0, 5,"Projeto/atividade:$o58_projativ $o55_descr", 0, 1, "L", 0);
             $projativ=$o58_projativ;
             $vprojativ=$o58_projativ;
             $o55anousu=$e60_anousu;
     }
          
   $verifica=false;
}
if ($tipo=="el"  and $velemento!=$o56_elemento){//elemento
   if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
      }
  $pdf->SetFont('Arial', 'B',8);
  $pdf->cell(0, 2,"", 0, 1, "", 0);
  $pdf->cell(0, 5,"Elemento:$o56_elemento  $o56_descr  ", 0, 1, "L", 0);
  $velemento=$o56_elemento;
  $verifica=false;
}

if ($tipo=="de" ){//desdobramento


$resdesdob = retorna_desdob(substr($o56_elemento,0,7),$e64_codele,$clorcelemento);
$numrows   = pg_numrows($resdesdob);

for ($i = 0; $i < $numrows; $i ++) {
    db_fieldsmemory($resdesdob,$i);
    if ($estrutural!=$estrutura){
        if (isset($quebradepagina) and $verifica==false){
             $troca=1;
             cabecalho($pdf,$troca);
            }

        $pdf->SetFont('Arial', 'B',8);
        $pdf->cell(0, 3,"", 0, 1, "L", 0);
        $pdf->cell(0, 5,"Desdobramento:" .$estrutural." ".$descr, 0, 1, "L", 0);
        $estrutura=$estrutural;
        $verifica=false;

       }

}

}
if ($tipo=="re" and $vrecurso!=$e91_recurso){//recurso
  if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
      }
  $pdf->SetFont('Arial', 'B',8);
  $pdf->cell(0, 2,"", 0, 1, "", 0);
  $pdf->cell(0, 5,"Recurso:$e91_recurso $o15_descr  ", 0, 1, "L", 0);
  $vrecurso=$e91_recurso;
  $verifica=false;
}

if ($tipo=="tr" and $vtiporesto!=$e91_codtipo ){//tipo resto
  if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
  }
   $pdf->SetFont('Arial', 'B',8);
   $pdf->cell(0, 2,"", 0, 1, "", 0);
   $pdf->cell(0, 5,"Tipo de resto: $e91_codtipo $e90_descr   ", 0, 1, "L", 0);
   $vtiporesto=$e91_codtipo;
   $verifica=false;

}


if ($tipo=="cr" and $vnumcgm!=$z01_numcgm){//credor
   if (isset($quebradepagina) and $verifica==false){
      $troca=1;
      cabecalho($pdf,$troca);
      }
   $pdf->SetFont('Arial', 'B',8);
   $pdf->cell(0, 2,"", 0, 1, "", 0);
   $pdf->cell(0, 5,"Credor:".$z01_numcgm." ".substr($z01_nome,0,100), 0, 1, "L", 0);
   $vnumcgm=$z01_numcgm;
   $verifica=false;
                     
                     }



//dados do relatório
$pdf->SetFont('Arial', '',8);
$tam="5";
//dados cadastrais dos empenhos
    $pdf->Cell(15, $tam, ($e60_codemp. "/" .$e60_anousu),"TBR", 0,"R", 0);//empenho
    $pdf->Cell(15,$tam, db_formatar($e60_emiss, 'd'), 1, 0, "C", 0);//emissao
    $pdf->Cell(70, $tam, substr($z01_nome, 0,38), 1, 0, "L", 0);//credor

//saldos a pagar anteriores
    $pdf->Cell(20, $tam, db_formatar(abs($e91_vlremp - $e91_vlranu - $e91_vlrliq), 'f'), 1, 0, "R", 0);// rp nao proc
    $total_rp_n_proc += ($e91_vlremp - $e91_vlranu - $e91_vlrliq);

    $pdf->Cell(20, $tam, db_formatar(abs($e91_vlrliq - $e91_vlrpag), 'f'), 1, 0, "R", 0);//rp proc
    $total_rp_proc += ($e91_vlrliq - $e91_vlrpag); 

//movimentação dos restos a pagar no período    
    $pdf->Cell(20, $tam, db_formatar(abs($vlranuliqnaoproc), 'f'), 1, 0, "R", 0);//anulacao -> rp nao proc
    $total_anula_rp_n_proc += $vlranuliqnaoproc;

    $pdf->Cell(20, $tam, db_formatar(abs($vlranuliq), 'f'), 1, 0, "R", 0);//anulacao -> rp proc
    $total_anula_rp_proc += $vlranuliq;
 
 if ($c70_anousu == $anoatual ){
    $pdf->Cell(20, $tam, db_formatar(abs($vlrliq), 'f'), 1, 0, "R", 0);//liquidado=rpproc
    $total_mov_liquida += ($vlrliq);
 } else  {
    $pdf->Cell(20, $tam, db_formatar("0", 'f'), 1, 0, "R", 0);//liquidado=rpproc
 }


    $pdf->Cell(20, $tam, db_formatar(abs($vlrpag), 'f'), 1, 0, "R", 0);//pagamento
    $total_mov_pagmento += $vlrpag;


//saldos a pagar finais  

    $liquidado_anterior = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
    $apagargeral=( $liquidado_anterior - $vlranu - $vlrpag);



    $aliquidargeral=$e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq));
    $liquidados=($apagargeral-$aliquidargeral);



    // a liquidar
    $pdf->Cell(20, $tam, db_formatar(abs($aliquidargeral), 'f'), 1, 0, "R", 0);
    $total_aliquidar_finais = $total_aliquidar_finais + $aliquidargeral;

    // liquidados
    $pdf->Cell(20, $tam, db_formatar(abs($liquidados), 'f'), 1, 0, "R", 0); 
    $total_liquidados_finais = $total_liquidados_finais + $liquidados;
 
    // a pagar
     $pdf->Cell(20, $tam, db_formatar(abs($apagargeral),'f'), "TBL", 1, "R", 0);
    $total_geral_finais = ($total_geral_finais + $apagargeral);


//subtotal
$subtotal_rp_n_proc         += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
$subtotal_rp_proc           += $e91_vlrliq - $e91_vlrpag; 
$subtotal_anula_rp_n_proc   += $vlranuliqnaoproc;
$subtotal_anula_rp_proc     += $vlranuliq;
$subtotal_mov_liquida       += $vlrliq;
$subtotal_mov_pagmento      += $vlrpag;
$subtotal_aliquidar_finais  += $aliquidargeral;
$subtotal_liquidados_finais += $liquidados;
$subtotal_geral_finais      += $apagargeral;


}


if(  $subtotal_rp_n_proc        !=0 || 
     $subtotal_rp_proc          !=0 || 
     $subtotal_anula_rp_n_proc  !=0 || 
     $subtotal_anula_rp_proc    !=0 ||
     $subtotal_mov_liquida      !=0 ||
     $subtotal_mov_pagmento     !=0 ||
     $subtotal_aliquidar_finais !=0 ||
     $subtotal_liquidados_finais!=0 ||
     $subtotal_geral_finais     !=0 ){
     
     $pdf->Cell(100, $tam, "Subtotal", "TBR", 0, "C", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_n_proc),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_rp_proc),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_n_proc),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_anula_rp_proc),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_liquida),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_mov_pagmento),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_aliquidar_finais),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_liquidados_finais),'f'), 1, 0, "R", 1);
     $pdf->Cell(20, $tam, db_formatar(abs($subtotal_geral_finais),'f'), "TBL", 1, "R", 1);


}

$pdf->ln(2);
$pdf->Cell(100, $tam, "Total", "TBR", 0 , "C", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_rp_n_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_rp_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_n_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_anula_rp_proc),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_mov_liquida),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_mov_pagmento),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_aliquidar_finais),'f'), 1, 0, "R",1);
$pdf->Cell(20, $tam, db_formatar(abs($total_liquidados_finais),'f'), 1, 0, "R", 1);
$pdf->Cell(20, $tam, db_formatar(abs($total_geral_finais),'f'), "TBL", 1, "R", 1);

$pdf->output();


?>