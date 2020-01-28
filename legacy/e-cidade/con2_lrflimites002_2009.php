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

set_time_limit(0);
include("libs/db_utils.php");
include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
require_once("classes/db_empresto_classe.php");
include_once("classes/db_db_config_classe.php");
include_once("classes/db_orcparamelemento_classe.php");
include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");


$clconrelvalor      = new cl_conrelvalor;
$clconrelinfo       = new cl_conrelinfo;
$classinatura       = new cl_assinatura;
$orcparamrel        = new cl_orcparamrel;
$cldb_config        = new cl_db_config;
$clorcparamelemento = new cl_orcparamelemento;
$clempresto         = new cl_empresto();

$v_despesa_pessoal = "s";
$v_receita_rcl     = "s";
$v_divida          = "s";
$v_garantias       = "s";
$v_operacoes       = "s";
$v_restos          = "s";

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$anousu     = db_getsession("DB_anousu");
$anousu_ant = (db_getsession("DB_anousu")-1);
$iAnoUsu    = $anousu;

$orcparamrel = new cl_orcparamrel;

$temprefa   = false;
$temcamara  = false;
$temadmind  = false;
$flag_abrev = false;

$xinstit            = split("-",$db_selinstit);
$aListaSelInstit    = $xinstit;

$resultinst = pg_exec("select munic,db21_tipoinstit from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");

for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  
  if ($db21_tipoinstit == 1) {
    $temprefa=true;
  } elseif ($db21_tipoinstit == 2) {
    $temcamara=true;
  } elseif ($db21_tipoinstit == 5 or $db21_tipoinstit == 7) {
    $temadmind=true;
  }
}

$dt     = data_periodo($anousu,$periodo);
$dt_ini = split("-",$dt[0]);
$dt_fin = split("-",$dt[1]);

$descr_periodo = "PERIODO: ".$dt["texto"];

$arqinclude = true;
$quadrimestre = substr($periodo,0,1);
$bimestre     = $quadrimestre;
$dtini = "";
$dtfin = "";

if ($quadrimestre == 1) {
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-04-30'; 
  $dt_ini_ant = ($anousu-1).'-05-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($quadrimestre == 2) {
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-08-31';
  $dt_ini_ant = ($anousu-1).'-09-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($quadrimestre == 3) {
  
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-12-31';
  $dt_ini_ant = $anousu.'-01-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
  
}

//////////////////////////////////////////////////////////////////////////
//
function validaarquivo($sArquivo,$ano){
	$iAnousu = $ano;	
	$bControle = true;
	while ($bControle){
		if	($iAnousu < 2000) {
			$sFile = $sArquivo.".php";
			$bControle = false;
		} else 	if (file_exists($sArquivo."_".$iAnousu.".php")) {
			$sFile = $sArquivo."_".$iAnousu.".php";
			$bControle = false;
		} else {
			$iAnousu--;
		}	
	}
	return $sFile;
}
/////////////////////////////////////////////////////////////////////////

// data apresentada na tela 
$dtd1    = split('-',$dt_ini);
$dtd2    = split('-',$dt_fin);
$textodt = strtoupper(db_mes($dtd1[1]))." A ".strtoupper(db_mes($dtd2[1]))." DE ";

if ($v_receita_rcl=="s") {
  $executar = "con2_lrfreceitacorrente002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($v_despesa_pessoal=="s") {
  $executar = "con2_lrfdesppessoal002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($v_divida=="s"){
  $executar = "con2_lrfdivida002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($v_garantias=="s"){
  $executar = "con2_lrfgarantias002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($v_operacoes=="s"){
  $executar = "con2_opercredito002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);

  $total_operacoes_credito 				= $aLinhasRelatorio[26]["nobim"];
  $perc_total_operacoes_credito		=	$aLinhasRelatorio[26]["atebim"];
  $total_antecipacao_receita			=	$aLinhasRelatorio[28]["nobim"];
  $perc_antecipacao_receita				= $aLinhasRelatorio[28]["atebim"];
  $limite_senado_int_ext					=	$aLinhasRelatorio[27]["nobim"];
  $perc_limite_senado_int_ext			=	$aLinhasRelatorio[27]["atebim"];
  $limite_senado_antecipacao			=	$aLinhasRelatorio[29]["nobim"];
  $perc_limite_senado_antecipacao	=	$aLinhasRelatorio[29]["atebim"];  
}

if ($v_restos == "s"){
  $executar = "con2_lrfdemrestosapagar002";
  $executar = validaarquivo($executar,$anousu);

  $lGeraPDF = false;
  
  include($executar);
  
  $total_inscricao_rp_nao_processados = $nTotalEmpNaoLiq;
  $suficiencia_antes_incricao_rp_nao_processados = $nTotSufAntInscr;
  
}
if ($temcamara == true && ($temprefa == true || $temadmind == true)){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER LEGISLATIVO";
}

if ($temprefa == true && $temcamara == false && $temadmind == false){
  $head2 = "MUNICÍPIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head3 = $descr_inst;
  $head4 = "RELATÓRIO DE GESTÃO FISCAL";
  $head5 = "DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO DE GESTÃO FISCAL";
 // $head5 = "DEMONSTRATIVO DOS LIMITES";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
} else {
  $head3 = "RELATÓRIO DE GESTÃO FISCAL";
  $head4 = "DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO DE GESTÃO FISCAL";
//  $head4 = "DEMONSTRATIVO DOS LIMITES";
  $head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head7 = $textodt.$anousu;
} else {
  $head6 = $textodt.$anousu;
}

$where = " o58_instit in (".str_replace('-',', ',$db_selinstit).") ";

$where = "  c61_instit in (".str_replace('-',', ',$db_selinstit).") "; 

$total = 0;
$alt   = 4;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

$pdf->addpage();
$pdf->setfont('arial','',7);
$pdf->cell(110,$alt,'LRF, art. 48 - Anexo VII','B',0,"L",0);
$pdf->cell( 80,$alt,'R$ 1,00','B',1,"R",0);

if ($v_despesa_pessoal=="s"){
  
  $pdf->cell(110,$alt,"DESPESA COM PESSOAL",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Despesa Total com Pessoal - DTP",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar(abs($total_despesa_pessoal_limites),'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar(abs($total_rcl_limites),'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Máximo (incisos I, II e III, art. 20 da LRF - <$limite_maximo%>)",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo_valor,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Prudencial (§ único, art. 22 da LRF) - $limite_prudencial%",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial_valor,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($v_divida=="s"){
  
  $pdf->cell(110,$alt,"DIVIDA",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Dívida Consolidada Líquida",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_divida,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($percdclsobrercl,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolução do Senado Federal",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_divida, 'f'),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_senado,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($v_garantias=="s"){
  
  $pdf->cell(110,$alt,"GARANTIAS DE VALORES",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Total das Garantias",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($garantiascondedidas,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($garantiasrcl,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolução do Senado Federal",'RB',0,'L',0);

  if ($periodo == "1Q"){
    $limite_senado = $limite_senado1;
  } else if ($periodo == "2Q"){
    $limite_senado = $limite_senado2;
  } else if ($periodo == "3Q"){
    $limite_senado = $limite_senado3;
  } else if ($periodo == "1S"){  
    $limite_senado = $limite_senado1;
  } else if ($periodo == "2S"){
    $limite_senado = $limite_senado2;
  }

  $pdf->cell(40,$alt,db_formatar($limite_senado,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado,"f"),'B',1,"R",0);
  
  $pdf->Ln();
	
}

if ($v_operacoes=="s"){
  
  $pdf->cell(110,$alt,"OPERAÇÕES DE CRÉDITO",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  
  $pdf->cell(110,$alt,"Operações de Crédito Internas e Externas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_operacoes_credito,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_total_operacoes_credito,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Operações de Crédito por Antecipação de Receita",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_antecipacao_receita,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_antecipacao_receita,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Operações de Credito Internas e Externas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_int_ext,"f"),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_int_ext,"f"),0,1,"R",0);
  
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Operações de Credito por Antecipação da Receita",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_antecipacao,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_antecipacao,"f"),'B',1,"R",0);
  
  
}

if ($v_restos =="s"){
  
  $pdf->Ln();

  $pdf->cell(110,$alt,"",'TR',0,'C',0);
  $pdf->cell(40,$alt,"INSCRIÇÃO EM RESTOS",'TR',0,"C",0);
  $pdf->cell(40,$alt,"SUFICIÊNCIA ANTES DA",'T',1,"C",0);

  $pdf->cell(110,$alt,"RESTOS A PAGAR",'R',0,'C',0);
  $pdf->cell(40,$alt,"A PAGAR NÃO",'R',0,"C",0);
  $pdf->cell(40,$alt,"INSCRIÇÃO EM RESTOS A",'',1,"C",0);

  $pdf->cell(110,$alt,"",'BR',0,'C',0);
  $pdf->cell(40,$alt,"PROCESSADOS",'BR',0,"C",0);
  $pdf->cell(40,$alt,"A PAGAR NÃO PROCESSADOS",'B',1,"C",0);

// Variaveis do relatorio

// Fim das Variaveis

  $pdf->cell(110,$alt,"Valor apurado nos demonstrativos respectivos",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_inscricao_rp_nao_processados,'f'),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($suficiencia_antes_incricao_rp_nao_processados,'f'),"B",1,"R",0);
  
}

$pdf->Ln();
notasExplicativas(&$pdf, 99999, "{$periodo}",110);
$pdf->Ln(15);

$pdf->setfont('arial','',6);

// assinaturas

assinaturas(&$pdf,&$classinatura,'GF');

$pdf->Output();

?>