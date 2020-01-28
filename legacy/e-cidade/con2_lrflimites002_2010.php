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

set_time_limit(0);
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("classes/db_empresto_classe.php");
include_once("classes/db_db_config_classe.php");
include_once("classes/db_orcparamelemento_classe.php");
include_once("classes/db_conrelinfo_classe.php");
include_once("classes/db_conrelvalor_classe.php");
require_once("libs/db_app.utils.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("model/relatorioContabil.model.php");

/*
 * Classes para calculo do restos a pagar
 */
db_app::import("contabilidade.relatorios.AnexoIRGF");
db_app::import("contabilidade.relatorios.AnexoVIRGF");
db_app::import("contabilidade.relatorios.AnexoVRGF");

$clconrelvalor      = new cl_conrelvalor;
$clconrelinfo       = new cl_conrelinfo;
$classinatura       = new cl_assinatura;
$orcparamrel        = new cl_orcparamrel;
$cldb_config        = new cl_db_config;
$clorcparamelemento = new cl_orcparamelemento;
$clempresto         = new cl_empresto();

$pessoal         = false;
$receita_rcl     = false;
$divida          = false;
$garantias       = false;
$operacoes       = false;
$restosapagar    = false;

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

$xinstit         = split("-",$db_selinstit);
$aListaSelInstit = $xinstit;

$sWhere         = "where codigo in (".str_replace('-',', ',$db_selinstit).")";
$sSqlResultInst = "select munic, db21_tipoinstit from db_config {$sWhere}";
$rsResultInst   = db_query($sSqlResultInst);

for ($xins = 0; $xins < pg_numrows($rsResultInst); $xins++) {
	
  db_fieldsmemory($rsResultInst,$xins);
  
  if ($db21_tipoinstit == 1) {
    $temprefa=true;
  } elseif ($db21_tipoinstit == 2) {
    $temcamara=true;
  } elseif ($db21_tipoinstit == 5 or $db21_tipoinstit == 7) {
    $temadmind=true;
  }
}

$oDaoPeriodo    = db_utils::getDao("periodo");
$iCodigoPeriodo = $periodo;
$sSqlPeriodo    = $oDaoPeriodo->sql_query($periodo); 
$sSiglaPeriodo  = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla; 
$dt             = data_periodo($anousu,$sSiglaPeriodo);

$dt_ini = split("-",$dt[0]);
$dt_fin = split("-",$dt[1]);

$descr_periodo = "PERIODO: ".$dt["texto"];
$arqinclude = true;

$dtini = "";
$dtfin = "";



if ($periodo == 12) {   // primeiro semestre
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-06-30'; 
  $dt_ini_ant = ($anousu-1).'-05-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($periodo == 13) {    // segundo semestre
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-12-31'; 
  $dt_ini_ant = ($anousu-1).'-05-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($periodo == 14) {   // primeiro quadrimestre
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-04-30'; 
  $dt_ini_ant = ($anousu-1).'-05-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($periodo == 15) {   // segundo quadrimestre
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-08-31';
  $dt_ini_ant = ($anousu-1).'-09-01';
  $dt_fin_ant = ($anousu-1).'-12-31';
} elseif ($periodo == 16) {   // terceiro quadrimestre
  
  $dt_ini = $anousu.'-01-01';
  $dt_fin = $anousu.'-12-31';
  $dt_ini_ant = ($anousu-1).'-01-01';
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

/*
 *Calcula a RCL uma unica vez 
 */
$sTodasInstit  = '';
$rsResultInst  = db_query("select codigo, munic, db21_tipoinstit from db_config");
$numrowsinstit = pg_num_rows($rsResultInst);
$virgula       = "";
for ($x = 0; $x < $numrowsinstit; $x ++) {
	
  db_fieldsmemory($rsResultInst, $x);
  $sTodasInstit .= $virgula . $codigo;
  $virgula      = ",";
}

duplicaReceitaaCorrenteLiquida(2010, 81);
$aMesesRCLAnterior = calcula_rcl2($anousu_ant, "{$anousu_ant}-01-01", "{$anousu_ant}-12-31", $sTodasInstit, true, 81);
$aMesesRCLAtual    = calcula_rcl2($anousu, "{$anousu}-01-01", "{$anousu}-12-31", $sTodasInstit, true, 81);
if ($pessoal == 'true') {
  
  /*
   * Inst�ncia da classe do relat�rio 89 para c�lculos da despesa pessoal.
   */
  $oAnexoIRGF         = new AnexoIRGF($iAnoUsu, 89, $iCodigoPeriodo);
  $oAnexoIRGF->setInstituicoes(str_replace("-", ",", $db_selinstit));

  $oDadosSimplificado = $oAnexoIRGF->getDadosSimplificado();
  
  /**
   * Busca os dados simplificados dos totalizadores do AnexoIRGF.
   */
  $total_despesa_pessoal_limites = $oDadosSimplificado->despesatotalpessoal->valorapurado;
  //$total_rcl_limites             = $oDadosSimplificado->receitacorrenteliquida->valorapurado;
  $total_rcl_limites             = $oDadosSimplificado->despesatotalpessoal->valorapurado;
  $total_despesa_pessoal_limites = $oDadosSimplificado->despesatotalpessoalsobreRCL->valorapurado;
  $limite_maximo                 = $oDadosSimplificado->limitemaximo->percentuallimite;
  $limite_maximo_valor           = $oDadosSimplificado->limitemaximo->valorapurado;
  $limite_prudencial             = $oDadosSimplificado->limiteprudencial->percentuallimite;
  $limite_prudencial_valor       = $oDadosSimplificado->limiteprudencial->valorapurado;
}

if ($divida == 'true') {
  
  $periodo  = $iCodigoPeriodo;
  $executar = "con2_lrfdivida002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($garantias == 'true') {
  
  unset($aTotalizadores);
  $periodo  = $iCodigoPeriodo;
  $executar = "con2_lrfgarantias002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);
}

if ($operacoes == 'true') {
  
  $periodo  = $iCodigoPeriodo;
  $executar = "con2_opercredito002";
  $executar = validaarquivo($executar,$anousu);
  include($executar);

  $total_operacoes_credito 				= $aLinhasRelatorio[28]->nobimestre;
  $perc_total_operacoes_credito		=	$aLinhasRelatorio[28]->atebimestre;
  $total_antecipacao_receita			=	$aLinhasRelatorio[30]->nobimestre;
  $perc_antecipacao_receita				= $aLinhasRelatorio[30]->atebimestre;
  $limite_senado_int_ext					=	$aLinhasRelatorio[29]->nobimestre;
  $perc_limite_senado_int_ext			=	$aLinhasRelatorio[29]->atebimestre;
  $limite_senado_antecipacao			=	$aLinhasRelatorio[31]->nobimestre;
  $perc_limite_senado_antecipacao	=	$aLinhasRelatorio[31]->atebimestre;  
}

if ($restosapagar == 'true') {
	/*
	 * Instancia das classes do relatorio 108 e 109
	 * para calculos do resto a pagar
	 */
  $oAnexoV   = new AnexoVRGF($iAnoUsu, 108, $iCodigoPeriodo);
  $oAnexoV->setInstituicoes(str_replace("-", ",", $db_selinstit));
  
  $oAnexoVI = new AnexoVIRGF($iAnoUsu, 109, $iCodigoPeriodo);
  $oAnexoVI->setInstituicoes(str_replace("-", ",", $db_selinstit));
  $oAnexoVI->setDadosAnexoV($oAnexoV);
  $oDados = $oAnexoVI->getDadosSimplificado();
  
  $lGeraPDF = false;
  
  //include($executar);
  
  $nTotalInscricaoRpNaoProcessados            = $oDados->restoapagarexericioanterior + $oDados->restoapagarexericio;
  $nSuficienciaAntesInscricaoRpNaoProcessados = $oDados->disponibilidadedecaixa;
  
}
if ($temcamara == true && ($temprefa == true || $temadmind == true)){
  $head2 = "MUNIC�PIO DE ".strtoupper($munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head2 = "MUNIC�PIO DE ".strtoupper($munic)." - PODER LEGISLATIVO";
}

if ($temprefa == true && $temcamara == false && $temadmind == false){
  $head2 = "MUNIC�PIO DE ".strtoupper($munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($temcamara == true && $temprefa == false && $temadmind == false){
  $head3 = $descr_inst;
  $head4 = "RELAT�RIO DE GEST�O FISCAL";
  $head5 = "DEMONSTRATIVO SIMPLIFICADO DO RELAT�RIO DE GEST�O FISCAL";
 // $head5 = "DEMONSTRATIVO DOS LIMITES";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
} else {
  $head3 = "RELAT�RIO DE GEST�O FISCAL";
  $head4 = "DEMONSTRATIVO SIMPLIFICADO DO RELAT�RIO DE GEST�O FISCAL";
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

if ($pessoal == 'true'){

  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt,"DESPESA COM PESSOAL",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  $pdf->setfont('arial','',7);
  
  $pdf->cell(110,$alt,"Despesa Total com Pessoal - DTP",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar(abs($total_rcl_limites),'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar(abs($total_despesa_pessoal_limites),'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite M�ximo (incisos I, II e III, art. 20 da LRF) - $limite_maximo%",'R',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo_valor,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_maximo,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Prudencial (par�grafo �nico, art. 22 da LRF) - $limite_prudencial%",'BR',0,"L",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial_valor,'f'),'BR',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($limite_prudencial,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($divida == 'true') {
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt,"D�VIDA CONSOLIDADA",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  $pdf->setfont('arial','',7);
  
  $pdf->cell(110,$alt,"D�vida Consolidada L�quida",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($nTotalDividaII,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($nPercentualRCL,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolu��o do Senado Federal",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($nLimiteSenadoAnexoII, 'f'),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar(120,'f'),'B',1,"R",0);
  
  $pdf->Ln();
  
}

if ($garantias == 'true'){
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt,"GARANTIAS DE VALORES",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  $pdf->setfont('arial','',7);
  
  $pdf->cell(110,$alt,"Total das Garantias Concedidas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($nTotalGarantias,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($nPercentualRCLIII,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido por Resolu��o do Senado Federal",'RB',0,'L',0);

  $pdf->cell(40,$alt,db_formatar($nValorSenadoIII,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado,"f"),'B',1,"R",0);
  
  $pdf->Ln();
	
}

if ($operacoes == 'true') {
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(110,$alt,"OPERA��ES DE CR�DITO",'TBR',0,'C',0);
  $pdf->cell(40,$alt,"VALOR",'TBR',0,"C",0);
  $pdf->cell(40,$alt,"% SOBRE A RCL",'TB',1,"C",0);
  $pdf->setfont('arial','',7);
  
  $pdf->cell(110,$alt,"Opera��es de Cr�dito Externas e Internas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_operacoes_credito,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_total_operacoes_credito,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Opera��es de Cr�dito por Antecipa��o de Receita",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($total_antecipacao_receita,'f'),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_antecipacao_receita,'f'),0,1,"R",0);
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Opera��es de Credito Externas e Internas",'R',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_int_ext,"f"),'R',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_int_ext,"f"),0,1,"R",0);
  
  
  $pdf->cell(110,$alt,"Limite Definido pelo Senado Federal para Opera��es de Credito por Antecipa��o da Receita",'RB',0,'L',0);
  $pdf->cell(40,$alt,db_formatar($limite_senado_antecipacao,"f"),'RB',0,"R",0);
  $pdf->cell(40,$alt,db_formatar($perc_limite_senado_antecipacao,"f"),'B',1,"R",0);
  
  
}

if ($restosapagar =="true"){
  
  $pdf->Ln();
  $pdf->setfont('arial','b',7);
  
  
  $pdf->cell(80,$alt,"",                                       'TR',0,'C',0);
  $pdf->cell(60,$alt,"",                                       'TR',0,"C",0);
  $pdf->cell(50,$alt,"DISPONIBILIDADE DE CAIXA",               'T',1,"C",0);

  $pdf->cell(80,$alt," ",                                      'R',0,'C',0);
  $pdf->cell(60,$alt,"INSCRI��O EM",                           'R',0,"C",0);
  $pdf->cell(50,$alt,"LIQUIDA (ANTES DA",                      '',1,"C",0);

  $pdf->cell(80,$alt,"",                                       'R',0,'C',0);
  $pdf->cell(60,$alt,"RESTOS A PAGAR N�O",                     'R',0,"C",0);
  $pdf->cell(50,$alt,"INSCRI��O EM",                           '',1,"C",0);
  
  $pdf->cell(80,$alt,"RESTOS A PAGAR",                         'R',0,'C',0);
  $pdf->cell(60,$alt,"PROCESSADOS DO",                         'R',0,"C",0);
  $pdf->cell(50,$alt,"RESTOS A PAGAR N�O",                     '',1,"C",0);

  $pdf->cell(80,$alt,"",                                       'R',0,'C',0);
  $pdf->cell(60,$alt,"EXERC�CIO",                              'R',0,"C",0);
  $pdf->cell(50,$alt,"PROCESSADOS DO",                         '',1,"C",0);

  $pdf->cell(80,$alt,"",                                       'BR',0,'C',0);
  $pdf->cell(60,$alt,"",                                       'BR',0,"C",0);
  $pdf->cell(50,$alt,"EXERC�CIO)",                             'B',1,"C",0);  
  
  
  $pdf->setfont('arial','',7);
// Variaveis do relatorio

// Fim das Variaveis

  $pdf->cell(80,$alt,"Valor Total",                                                'RB',0,'L',0);
  $pdf->cell(60,$alt,db_formatar($nTotalInscricaoRpNaoProcessados,'f'),            'RB',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($nSuficienciaAntesInscricaoRpNaoProcessados,'f'), "B",1,"R",0);
  
}
$oRelatorio = new relatorioContabil(93, false);
$oRelatorio->getNotaExplicativa(&$pdf,$iCodigoPeriodo);
$pdf->Ln(15);

$pdf->setfont('arial','',6);

// assinaturas

assinaturas(&$pdf,&$classinatura,'GF');

$pdf->Output();
?>