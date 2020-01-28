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

if (!isset($arqinclude)){ // se este arquivo n�o esta incluido por outro

  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_liborcamento.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_libtxt.php");
  include("libs/db_utils.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_conrelinfo_classe.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_db_config_classe.php");

  $oGet = db_utils::postMemory($_GET);
  
  $classinatura = new cl_assinatura();
  $cldb_config  = new cl_db_config();
  $orcparamrel  = new cl_orcparamrel();

}
$iAnoUsu         = db_getsession("DB_anousu");
$anousu          = db_getsession("DB_anousu");
$iInstit = db_getsession("DB_instit");
$oDaoPeriodo     = db_utils::getDao("periodo");
if ($iAnoUsu >= 2010 ) {
  if (isset($iCodigoPeriodo)) {
    $sSqlPeriodo   = $oDaoPeriodo->sql_query($iCodigoPeriodo);  
  } else {
    
    $sSqlPeriodo   = $oDaoPeriodo->sql_query($periodo);
    $iCodigoPeriodo  = $periodo;
    
  }
   $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
} else {
   $sSiglaPeriodo = $periodo;
}


$ultimo_periodo = ($sSiglaPeriodo=="6B") || ( $sSiglaPeriodo=="2S");


// RECEITAS

// Parametros de Receitas Previd�nci�rias;
for ($linha=1; $linha<=17; $linha++){	
  $m_receita[$linha]['estrut']     = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu"));
  $m_receita[$linha]['inicial']    = 0;
  $m_receita[$linha]['atualizada'] = 0;
  $m_receita[$linha]['bimestre']   = 0;
  $m_receita[$linha]['exercicio']  = 0;  
  $m_receita[$linha]['anterior']   = 0;
}

// Paramentros de Receitas Intra-Or�amentarias
for ($linha = 39; $linha <= 54; $linha++){	
  $m_receita[$linha]['estrut']     = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu"));
  $m_receita[$linha]['inicial']    = 0;
  $m_receita[$linha]['atualizada'] = 0;
  $m_receita[$linha]['bimestre']   = 0;
  $m_receita[$linha]['exercicio']  = 0;  
  $m_receita[$linha]['anterior']   = 0;   
}	


// DESPESAS

//Parametros de Despesas
for ($linha=18; $linha<=27; $linha++){	
  $m_despesa[$linha]['estrut']     = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu"));
  $m_despesa[$linha]['nivel']      = $orcparamrel->sql_nivel('60',$linha);
  $m_despesa[$linha]["funcao"]     = $orcparamrel->sql_funcao("60",$linha);
  $m_despesa[$linha]['inicial']    = 0;
  $m_despesa[$linha]['atualizada'] = 0;
  $m_despesa[$linha]['bimestre']   = 0;
  $m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
  $m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
  $m_despesa[$linha]['rpnp_exe']   = 0;  // RP nao processado exercicio
  $m_despesa[$linha]['rpnp_ant']   = 0;  // RP nao processado exercicio anterior
}

//Parametros de Despesas Intra-Or�amentarias
for ($linha = 55; $linha <= 56; $linha++){	
  $m_despesa[$linha]['estrut']     = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu")); 
  $m_despesa[$linha]['nivel']      = $orcparamrel->sql_nivel('60',$linha);
  $m_despesa[$linha]["funcao"]     = $orcparamrel->sql_funcao("60",$linha);
  $m_despesa[$linha]['inicial']    = 0;
  $m_despesa[$linha]['atualizada'] = 0;
  $m_despesa[$linha]['bimestre']   = 0;
  $m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
  $m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
  $m_despesa[$linha]['rpnp_exe']   = 0;  // RP nao processado exercicio
  $m_despesa[$linha]['rpnp_ant']   = 0;  // RP nao processado exercicio anterior
}	

// Parametros Aportes

for ($linha=28; $linha<=33; $linha++){	
	
  $aParamAportes[$linha]['estrut']     = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu")); 
  $aParamAportes[$linha]['inicial']    = 0;
  $aParamAportes[$linha]['atualizada'] = 0;
  $aParamAportes[$linha]['bimestre']   = 0;
  $aParamAportes[$linha]['exercicio']  = 0;  // ate o bimestre
  $aParamAportes[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
  
}

$aParamReservaOrc[34]['estrut']     = $orcparamrel->sql_parametro('60',34,"f",$iInstit,db_getsession("DB_anousu"));
$aParamReservaOrc[34]['nivel']      = $orcparamrel->sql_nivel('60',34);
$aParamReservaOrc[34]['atualizada'] = 0;
$aParamReservaOrc[34]['inicial']    = 0;


//Paramentros de Disponibilidade

for($linha=35; $linha<=38; $linha++){
  $m_disponivel[$linha]['estrut']                 = $orcparamrel->sql_parametro('60',$linha,"f",$iInstit,db_getsession("DB_anousu"));
  $m_disponivel[$linha]['nivel']			      = $orcparamrel->sql_nivel('60',$linha);
  $m_disponivel[$linha]['saldo_inicial']          = 0;
  $m_disponivel[$linha]['saldo_periodo_atual']    = 0;       
  $m_disponivel[$linha]['saldo_periodo_anterior'] = 0;
}



// RECEITAS

$receita  = array();

$receita[0]['txt']  = "RECEITAS PREVIDENCI�RIAS - RPPS (EXCETO INTRA-OR�AMENT�RIAS)(I)";
$receita[1]['txt']  = "  RECEITAS CORRENTES ";
$receita[2]['txt']  = "    Receita de Contribui��es dos Segurados";
$receita[3]['txt']  = "      Pessoal Civil";
$receita[4]['txt']  = "        Ativo "; // linha 1
$receita[5]['txt']  = "        Inativo"; // linha 2
$receita[6]['txt']  = "        Pensionista"; // linha 3
$receita[7]['txt']  = "      Pessoal Militar";
$receita[8]['txt']  = "        Ativo"; // linha 4
$receita[9]['txt']  = "        Inativo"; // linha 5 
$receita[10]['txt'] = "        Pensionista"; // linha 6
$receita[11]['txt'] = "    Outras Receitas de Contribui�oes"; // linha 7
$receita[12]['txt'] = "    Receita Patrimonial"; 
$receita[13]['txt'] = "      Receitas Imobili�rias"; // linha 8
$receita[14]['txt'] = "   	 Receitas de Valores Mobili�rios"; // 9
$receita[15]['txt'] = "   	 Outras Receitas Patrimoniais"; // 10
$receita[16]['txt'] = "    Receita de Servi�os"; //11 
$receita[17]['txt'] = "    Outras Receitas Correntes"; // total outras receitas.
$receita[18]['txt'] = "   	 Compensa��o Previdenci�ria do RGPS para o RPPS";  //12
$receita[19]['txt'] = "  	 Demais Receitas Correntes"; // 13
$receita[20]['txt'] = "  RECEITAS DE CAPITAL"; // totalizador
$receita[21]['txt'] = "    Aliena��o de Bens,Direitos e Ativos"; // 14
$receita[22]['txt'] = "    Amortiza��o de Empr�stimos"; // 15
$receita[23]['txt'] = "    Outras Receitas de Capital"; // 16
$receita[24]['txt'] = "  (-) DEDU��ES DA RECEITA"; // 17
$receita[25]['txt'] = "RECEITAS PREVIDENCI�RIAS - RPPS (INTRA-OR�AMENT�RIAS) (II)"; //totalizar das intras 

// RECEITAS INTRA-OR�AMENT�RIAS

$receita[28]['txt'] = "RECEITAS CORRENTES (VIII)";
$receita[29]['txt'] = " Receita de Contribui��es"; // 39
$receita[30]['txt'] = "   Patronal";
$receita[31]['txt'] = "     Pessoal Civil";
$receita[32]['txt'] = "        Ativo"; // 40
$receita[33]['txt'] = "        Inativo "; // 41
$receita[34]['txt'] = "        Pensionista"; // 42
$receita[35]['txt'] = "     Pessoal Militar";
$receita[36]['txt'] = "        Ativo"; // 43
$receita[37]['txt'] = "        Inativo"; // 44
$receita[38]['txt'] = "        Pensionista"; // 45
$receita[39]['txt'] = "   Para Cobertura de D�ficit Atuarial"; // 46
$receita[40]['txt'] = "   Em Regime de D�bitos e Parcelamentos"; // 47

$receita[41]['txt'] = " Receita Patrimonial"; // 48
$receita[42]['txt'] = " Receita de Servi�os"; // 49
$receita[43]['txt'] = " Outras receitas Correntes";//50
$receita[44]['txt'] = "RECEITAS DE CAPITAL (IX)";
$receita[45]['txt'] = " Aliena��o de Bens";//51
$receita[46]['txt'] = " Amortiza��o de Empr�stimos";//52
$receita[47]['txt'] = " Outras Receitas de Capital";//53
$receita[48]['txt'] = "(-) DEDU��ES DA RECEITA (X)";//54

for ($linha=1;$linha<=54;$linha++){
  $receita[$linha]['inicial']    = 0;
  $receita[$linha]['atualizada'] = 0;
  $receita[$linha]['bimestre']   = 0;
  $receita[$linha]['exercicio']  = 0; // ate o bimestre
  $receita[$linha]['anterior']   = 0; // ate o bimestre exercicio anterior
}	




if (!isset($arqinclude)){ // se este arquivo n�o esta incluido por outro

  $anousu        = db_getsession("DB_anousu");
  $anousu_ant    = $anousu-1;
  $aDataPeriodo  = data_periodo($anousu,$sSiglaPeriodo); // no dbforms/db_funcoes.php
  $dtDataInicial = $aDataPeriodo[0]; // data inicial do per�odo
  $dtDataFinal   = $aDataPeriodo[1]; // data final do per�odo
  $sDescrPeriodo = $aDataPeriodo['periodo'];

}


// Caso o Periodo Seja 6B (Sexto Bimestre) ou 3Q (Terceiro Quadrimestre) ou 2S (Segundo Simestre)
// seta $ultimo_periodo como true, caso contrario false

$ultimo_periodo = ($oGet->periodo=="6B") || ($oGet->periodo=="2S");

$aDataInicial     = split("-",$dtDataInicial);
$periodo_mes      = strtoupper(db_mes($aDataInicial[1]));
$dtDataInicialAnt = $anousu_ant."-".$aDataInicial[1]."-".$aDataInicial[2];
$aDataFinal  	  = split("-",$dtDataFinal);

if ($aDataFinal[1] == 2){
  $aDataFinal[2]  = cal_days_in_month(CAL_GREGORIAN, $aDataFinal[1],$anousu_ant);
}

$dtDataFinalAnt   = $anousu_ant."-".$aDataFinal[1]."-".$aDataFinal[2];


// Seleciona institui��o do RPPS

$rsRppsInstit  = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo",null,"db21_tipoinstit in (5,6)"));
$iLinhasInstit = $cldb_config->numrows;

if ( $iLinhasInstit > 0 ){

  $aListaInstit = array();
  
  for ($iInd=0; $iInd < $iLinhasInstit; $iInd++) {	
    $oInstit = db_utils::fieldsMemory($rsRppsInstit,$iInd);	
    $aListaInstit[] = $oInstit->codigo;
  }   
  
  $sListaInstit = implode(",",$aListaInstit);
  
}else{
  $sListaInstit = $iInstit;
}



$db_filtro  = " o70_instit in ({$sListaInstit})";

// Exercicio Atual
$rsRecExeAtual         = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dtDataInicial,$dtDataFinal);
$iLinhasRecExeAtual    = pg_num_rows($rsRecExeAtual);
@pg_query("drop table work_receita");

// Exercicio Anterior
$rsRecExeAnterior      = db_receitasaldo(11,1,3,true,$db_filtro,$anousu_ant,$dtDataInicialAnt,$dtDataFinalAnt);
$iLinhasRecExeAnterior = pg_num_rows($rsRecExeAnterior);
@pg_query("drop table work_receita");



for ($iInd=0; $iInd < $iLinhasRecExeAtual; $iInd++) {
	
  $oRecExeAtual = db_utils::fieldsMemory($rsRecExeAtual,$iInd);
  $sEstrut      = $oRecExeAtual->o57_fonte;

  for ($linha=1; $linha <= 17; $linha++) {
    if (in_array($sEstrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['inicial']    += $oRecExeAtual->saldo_inicial;
      $m_receita[$linha]['atualizada'] += $oRecExeAtual->saldo_inicial_prevadic;
      $m_receita[$linha]['bimestre']   += $oRecExeAtual->saldo_arrecadado ;
      $m_receita[$linha]['exercicio']  += $oRecExeAtual->saldo_arrecadado_acumulado;
    }
  }

  for ($linha=39; $linha <= 54; $linha++) {
    if (in_array($sEstrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['inicial']    += $oRecExeAtual->saldo_inicial;
      $m_receita[$linha]['atualizada'] += $oRecExeAtual->saldo_inicial_prevadic;
      $m_receita[$linha]['bimestre']   += $oRecExeAtual->saldo_arrecadado ;
      $m_receita[$linha]['exercicio']  += $oRecExeAtual->saldo_arrecadado_acumulado;
    }
  }
}


for ($iInd=0; $iInd < $iLinhasRecExeAnterior; $iInd++) {
	
  $oRecExeAnterior = db_utils::fieldsMemory($rsRecExeAnterior,$iInd);
  $sEstrut		   = $oRecExeAnterior->o57_fonte;

  for ($linha=1; $linha <= 17; $linha++) {
    if (in_array($sEstrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['anterior'] += $oRecExeAnterior->saldo_arrecadado_acumulado;
    }
  }

  for ($linha=39; $linha <= 54; $linha++) {
    if (in_array($sEstrut,$m_receita[$linha]['estrut'])) {
      $m_receita[$linha]['anterior'] += $oRecExeAnterior->saldo_arrecadado_acumulado;
    }
  }

}

$pcol = array( 1 => 'inicial',
			   2 => 'atualizada',
			   3 => 'bimestre',
			   4 => 'exercicio',
			   5 => 'anterior');
			   
$ipcol = count($pcol);


for ($col = 1; $col <= $ipcol; $col++) {

  $receita[3] [$pcol[$col]] = $m_receita[1][$pcol[$col]]+$m_receita[2][$pcol[$col]]+$m_receita[3][$pcol[$col]];
  $receita[4] [$pcol[$col]] = $m_receita[1][$pcol[$col]];
  $receita[5] [$pcol[$col]] = $m_receita[2][$pcol[$col]];
  $receita[6] [$pcol[$col]] = $m_receita[3][$pcol[$col]];
  $receita[7] [$pcol[$col]] = $m_receita[4][$pcol[$col]]+$m_receita[5][$pcol[$col]]+$m_receita[6][$pcol[$col]]+$m_receita[7][$pcol[$col]];
  $receita[8] [$pcol[$col]] = $m_receita[4][$pcol[$col]];
  $receita[9] [$pcol[$col]] = $m_receita[5][$pcol[$col]];
  $receita[10][$pcol[$col]] = $m_receita[6][$pcol[$col]];
  $receita[11][$pcol[$col]] = $m_receita[7][$pcol[$col]];
  $receita[12][$pcol[$col]] = $m_receita[8][$pcol[$col]]+$m_receita[9][$pcol[$col]]+$m_receita[10][$pcol[$col]];  
  $receita[13][$pcol[$col]] = $m_receita[8][$pcol[$col]];
  $receita[14][$pcol[$col]] = $m_receita[9][$pcol[$col]];
  $receita[15][$pcol[$col]] = $m_receita[10][$pcol[$col]];
  $receita[16][$pcol[$col]] = $m_receita[11][$pcol[$col]];
  $receita[17][$pcol[$col]] = $m_receita[12][$pcol[$col]]+$m_receita[13][$pcol[$col]];
  $receita[18][$pcol[$col]] = $m_receita[12][$pcol[$col]];
  $receita[19][$pcol[$col]] = $m_receita[13][$pcol[$col]];
  $receita[20][$pcol[$col]] = $m_receita[14][$pcol[$col]]+$m_receita[15][$pcol[$col]]+$m_receita[16][$pcol[$col]];
  $receita[21][$pcol[$col]] = $m_receita[14][$pcol[$col]];
  $receita[22][$pcol[$col]] = $m_receita[15][$pcol[$col]];
  $receita[23][$pcol[$col]] = $m_receita[16][$pcol[$col]];
  $receita[24][$pcol[$col]] = $m_receita[17][$pcol[$col]];
  
  $receita[2] [$pcol[$col]] =  $receita[3][$pcol[$col]] + $receita[7] [$pcol[$col]];
  $receita[1] [$pcol[$col]] =  $receita[2][$pcol[$col]] + $receita[12][$pcol[$col]] + $receita[16][$pcol[$col]]+$receita[17][$pcol[$col]];
  $receita[0] [$pcol[$col]] = ( $receita[1][$pcol[$col]]+ $receita[20][$pcol[$col]])- $receita[24][$pcol[$col]];
}

//receitas intraorcamentarias

for ($col = 1; $col <= $ipcol; $col++) {
	
  // Pessoal Civil	
  $receita[31][$pcol[$col]] = $m_receita[40][$pcol[$col]]+$m_receita[41][$pcol[$col]]+$m_receita[42][$pcol[$col]];
  // Ativo
  $receita[32][$pcol[$col]] = $m_receita[40][$pcol[$col]];
  // Inativo
  $receita[33][$pcol[$col]] = $m_receita[41][$pcol[$col]];
  // Pensionista
  $receita[34][$pcol[$col]] = $m_receita[42][$pcol[$col]];
  
  // Pessoal Militar
  $receita[35][$pcol[$col]] = $m_receita[43][$pcol[$col]]+$m_receita[44][$pcol[$col]]+$m_receita[45][$pcol[$col]];
  // Patronal
  $receita[30][$pcol[$col]] = $receita[31][$pcol[$col]] + $receita[35][$pcol[$col]];
  // Ativo 
  $receita[36][$pcol[$col]] = $m_receita[43][$pcol[$col]];
  // Inativo
  $receita[37][$pcol[$col]] = $m_receita[44][$pcol[$col]];
  // Pensionista
  $receita[38][$pcol[$col]] = $m_receita[45][$pcol[$col]];
  // Parar Cobertura de D�ficit Atuarial  
  $receita[39][$pcol[$col]] = $m_receita[46][$pcol[$col]];
  // Em Regime de D�bitos e Parcelamentos
  $receita[40][$pcol[$col]] = $m_receita[47][$pcol[$col]];
  // Receita Patrimonial
  $receita[41][$pcol[$col]] = $m_receita[48][$pcol[$col]];
  // Receita de Servi�os
  $receita[42][$pcol[$col]] = $m_receita[49][$pcol[$col]];
  // Outras receitas correntes
  $receita[43][$pcol[$col]] = $m_receita[50][$pcol[$col]];
  // RECEITAS DE CAPITAL (IX)
  $receita[44][$pcol[$col]] = $m_receita[51][$pcol[$col]]+$m_receita[52][$pcol[$col]]+$m_receita[53][$pcol[$col]];
  // Aliena��o de Bens
  $receita[45][$pcol[$col]] = $m_receita[51][$pcol[$col]];
  // Amortiza��o de Empr�stimos
  $receita[46][$pcol[$col]] = $m_receita[52][$pcol[$col]];
  // Outras Receita de Capital
  $receita[47][$pcol[$col]] = $m_receita[53][$pcol[$col]];
  // (-) DEDU��ES DA RECEITAS (X)
  $receita[48][$pcol[$col]] = $m_receita[54][$pcol[$col]];
  // Receita de Contribui��es
  $receita[29][$pcol[$col]] = $receita[30][$pcol[$col]]+$receita[39][$pcol[$col]]+$receita[40][$pcol[$col]];
  // RECEITAS CORRENTES   
  $receita[28][$pcol[$col]] = $receita[29][$pcol[$col]]+$receita[41][$pcol[$col]]+$receita[42][$pcol[$col]]+$receita[43][$pcol[$col]];
  
  $receita[49][$pcol[$col]] = ($receita[28][$pcol[$col]]+$receita[44][$pcol[$col]])-$receita[48][$pcol[$col]];
  $receita[25][$pcol[$col]] = $receita[49][$pcol[$col]];

}


// TOTAL RECEITAS

$total_rec_inicial    = $receita[0]['inicial']    + $receita[25]['inicial'];
$total_rec_atualizada = $receita[0]['atualizada'] + $receita[25]['atualizada'];
$total_rec_bimestre   = $receita[0]['bimestre']   + $receita[25]['bimestre'];
$total_rec_exercicio  = $receita[0]['exercicio']  + $receita[25]['exercicio'];
$total_rec_anterior   = $receita[0]['anterior']   + $receita[25]['anterior'];

// DESPESAS

$despesa = array();

$despesa[0]['txt']  = "DESP. PREVID.-RPPS(EXCETO INTRA-OR�AMENT�RIAS)(IV)";
$despesa[1]['txt']  = "  ADMINISTRA��O";
$despesa[2]['txt']  = "    Despesas Correntes";//linha 18
$despesa[3]['txt']  = "    Despesas de Capital";//linha 19
$despesa[4]['txt']  = "  PREVID�NCIA";
$despesa[5]['txt']  = "    Pessoal Civil";
$despesa[6]['txt']  = "      Aposentadorias";//linha 20
$despesa[7]['txt']  = "      Pens�es";//linha 21
$despesa[8]['txt']  = "      Outros Benef�cios Previdenci�rios";//linha 22
$despesa[9]['txt']  = "    Pessoal Militar";
$despesa[10]['txt'] = "      Reformas";//linha 23
$despesa[11]['txt'] = "      Pens�es";//linha 24
$despesa[12]['txt'] = "      Outros Benef�cios Previdenci�rios";//linha 25
$despesa[13]['txt'] = "    Outras Despesas Previdenci�rias";
$despesa[14]['txt'] = "      Compensa��o Previdenci�ria de Aposentadorias do RPPS p/ o RGPS";//linha 26
$despesa[15]['txt'] = "      Demais Despesas Previdenci�rias";//linha 27
$despesa[16]['txt'] = "DESPESAS PREVIDENCIARIAS-RPPS(INTRA-OR�AMENTARIA)(V)";

for ($linha=1;$linha<=16;$linha++){
	
  $despesa[$linha]['inicial']    = 0;
  $despesa[$linha]['atualizada'] = 0;
  $despesa[$linha]['bimestre']   = 0;
  $despesa[$linha]['exercicio']  = 0; // ate o bimestre
  $despesa[$linha]['anterior']   = 0; // ate o bimestre exercicio anterior
  $despesa[$linha]['rpnp_exe']   = 0; // RP Nao Processado Exercicio
  $despesa[$linha]['rpnp_ant']   = 0; // RP Nao Processado Exercicio Anterior
}	

$db_filtro = "o58_instit in ({$sListaInstit})";

for ($linha = 18; $linha <= 27; $linha++){	
  
  $aListaFuncao = array();
  $sWhereFuncao = "";
	
  foreach($m_despesa[$linha]["funcao"] as $sRegistro){
    $aListaFuncao[] = $sRegistro;
  }

  $sListaFuncao = implode(",",$aListaFuncao);
  
  if (trim($sListaFuncao) != ""){
    $sWhereFuncao = " and o58_funcao in ({$sListaFuncao}) ";
  }

  $rsDespFuncaoAtual     = db_dotacaosaldo(8,2, 3, true, $db_filtro.$sWhereFuncao, $anousu, $dtDataInicial, $dtDataFinal);
  $iLinhaDespFuncaoAtual = pg_num_rows($rsDespFuncaoAtual); 
  
  for ($iInd = 0; $iInd < $iLinhaDespFuncaoAtual; $iInd++) {
    
  	$oDespFuncaoAtual = db_utils::fieldsMemory($rsDespFuncaoAtual,$iInd);

    $sNivel      = $m_despesa[$linha]['nivel'];
    $sEstrutural = $oDespFuncaoAtual->o58_elemento.'00';
    $sEstrutural = substr($sEstrutural,0,$sNivel);
    $sEstrutural = str_pad($sEstrutural,15,"0", STR_PAD_RIGHT);	

    if (substr($oDespFuncaoAtual->o58_elemento,3,2) == "91"){
      continue;
    }

    if (in_array($sEstrutural, $m_despesa[$linha]['estrut'])){
      $m_despesa[$linha]['inicial']    += $oDespFuncaoAtual->dot_ini; 
      $m_despesa[$linha]['atualizada'] += $oDespFuncaoAtual->dot_ini + ($oDespFuncaoAtual->suplementado_acumulado - $oDespFuncaoAtual->reduzido_acumulado);
      $m_despesa[$linha]['bimestre']   += $oDespFuncaoAtual->liquidado;  
      $m_despesa[$linha]['exercicio']  += $oDespFuncaoAtual->liquidado_acumulado;
      $m_despesa[$linha]['rpnp_exe']   += abs($oDespFuncaoAtual->empenhado_acumulado - $oDespFuncaoAtual->anulado_acumulado - $oDespFuncaoAtual->liquidado_acumulado);
    }
  }
  
  
  $rsDespFuncaoAnterior     = db_dotacaosaldo(8,2, 3, true, $db_filtro.$sWhereFuncao, $anousu_ant, $dtDataInicialAnt, $dtDataFinalAnt);
  $iLinhaDespFuncaoAnterior = pg_num_rows($rsDespFuncaoAnterior);
  
  for ($iInd=0; $iInd < $iLinhaDespFuncaoAnterior; $iInd++) {
    $oDespFuncaoAnterior = db_utils::fieldsMemory($rsDespFuncaoAnterior,$iInd);

    
    $sNivel      = $m_despesa[$linha]['nivel'];
    $sEstrutural = $oDespFuncaoAnterior->o58_elemento.'00';
    $sEstrutural = substr($sEstrutural,0,$sNivel);
    $sEstrutural = str_pad($sEstrutural,15,"0", STR_PAD_RIGHT);	

    if (substr($oDespFuncaoAnterior->o58_elemento,3,2) == "91"){
      continue;
    }

    if (in_array($sEstrutural, $m_despesa[$linha]['estrut'])){
      $m_despesa[$linha]['anterior'] += $oDespFuncaoAnterior->liquidado_acumulado;
      $m_despesa[$linha]['rpnp_ant'] += abs($oDespFuncaoAnterior->empenhado_acumulado - $oDespFuncaoAnterior->anulado_acumulado - $oDespFuncaoAnterior->liquidado_acumulado);
    }
  }  
  
}

$pcol = array( 1 => 'inicial',
		       2 => 'atualizada',
		       3 => 'bimestre',
		       4 => 'exercicio',
		       5 => 'anterior',
		       6 => 'rpnp_exe',
		       7 => 'rpnp_ant');

    
$ipcol = count($pcol);

for ($col = 1; $col <= $ipcol; $col++){ 

  // Despesas Correntes
  $despesa[2][$pcol[$col]]  = $m_despesa[18][$pcol[$col]];
  // Despesas de Capital     
  $despesa[3][$pcol[$col]]  = $m_despesa[19][$pcol[$col]];
  // total Administracao
  $despesa[1][$pcol[$col]]  = $despesa[2][$pcol[$col]]+$despesa[3][$pcol[$col]];
  // Aposentadorias Civil
  $despesa[6][$pcol[$col]]  = $m_despesa[20][$pcol[$col]];
  // Pensoes Civil
  $despesa[7][$pcol[$col]]  = $m_despesa[21][$pcol[$col]];
  // Outros Beneficios Prev. Civil 
  $despesa[8][$pcol[$col]]  = $m_despesa[22][$pcol[$col]];
  // Pessoal Civil
  $despesa[5][$pcol[$col]]  = $despesa[6][$pcol[$col]]+$despesa[7][$pcol[$col]]+$despesa[8][$pcol[$col]];
  // Reformas 
  $despesa[10][$pcol[$col]] = $m_despesa[23][$pcol[$col]];
  // Pensoes Militar
  $despesa[11][$pcol[$col]] = $m_despesa[24][$pcol[$col]];
  // Outros Beneficios Prev. Militar 
  $despesa[12][$pcol[$col]] = $m_despesa[25][$pcol[$col]];
  // Pessoal Militar
  $despesa[9][$pcol[$col]]  = $despesa[10][$pcol[$col]]+$despesa[11][$pcol[$col]]+$despesa[12][$pcol[$col]];
  // Compensacao Prev. de Aposentadorias
  $despesa[14][$pcol[$col]] = $m_despesa[26][$pcol[$col]];
  // Compensacao Prev. de Pensoes 
  $despesa[15][$pcol[$col]] = $m_despesa[27][$pcol[$col]];
  // Outras Despesas Prev. 
  $despesa[13][$pcol[$col]] = $despesa[14][$pcol[$col]]+$despesa[15][$pcol[$col]];
  // Prev. Social 
  $despesa[4][$pcol[$col]]  = $despesa[5][$pcol[$col]]+$despesa[9][$pcol[$col]]+$despesa[13][$pcol[$col]];
  //total despesas previdenciarias - rpps
  $despesa[0][$pcol[$col]]   = $despesa[1][$pcol[$col]]+$despesa[4][$pcol[$col]];
  
}



for ($linha=55;$linha<=56;$linha++){	

  $m_despesa[$linha]['inicial']    = 0;
  $m_despesa[$linha]['atualizada'] = 0;
  $m_despesa[$linha]['bimestre']   = 0;
  $m_despesa[$linha]['exercicio']  = 0;  // ate o bimestre
  $m_despesa[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
  $m_despesa[$linha]['rpnp_exe']   = 0;  // RP Nao Processados Exercicio
  $m_despesa[$linha]['rpnp_ant']   = 0;  // RP Nao Processados Exercicio Anterior
}


for ($linha = 55; $linha <= 56; $linha++){	
  $v_funcao = "";
  $sp       = "";
  foreach($m_despesa[$linha]["funcao"] as $registro){
    $v_funcao .= $sp.$registro;
    $sp        = ",";
  }

  if (trim($v_funcao) != ""){
    $v_funcao = " and o58_funcao in (".$v_funcao.") ";
  }

  $result_desp_funcao = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu, $dtDataInicial, $dtDataFinal);
  for ($i = 0; $i < pg_numrows($result_desp_funcao); $i ++) {
    db_fieldsmemory($result_desp_funcao, $i);

    $nivel        = $m_despesa[$linha]['nivel'];
    $estrutural   = $o58_elemento.'00';
    $estrutural   = substr($estrutural,0,$nivel);
    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

    if (substr($o58_elemento,3,2) != "91"){
      continue;
    }

    if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){

      $m_despesa[$linha]['inicial']    += $dot_ini; 
      $m_despesa[$linha]['atualizada'] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
      $m_despesa[$linha]['bimestre']   += $liquidado;  
      $m_despesa[$linha]['exercicio']  += $liquidado_acumulado;
      $m_despesa[$linha]['rpnp_exe']   += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
    }
  }
}

//Exercicio Anterior

for ($linha = 55; $linha <= 56; $linha++){	
  $v_funcao = "";
  $sp       = "";
  foreach($m_despesa[$linha]["funcao"] as $registro){
    $v_funcao .= $sp.$registro;
    $sp        = ",";
  }

  if (trim($v_funcao) != ""){
    $v_funcao = " and o58_funcao in (".$v_funcao.") ";
  }

  $result_desp_funcao_ant = db_dotacaosaldo(8,2, 3, true, $db_filtro.$v_funcao, $anousu_ant, $dtDataInicialAnt, $dtDataFinalAnt);
  
  for ($i = 0; $i < pg_numrows($result_desp_funcao_ant); $i ++) {
    db_fieldsmemory($result_desp_funcao_ant, $i);

    $nivel        = $m_despesa[$linha]['nivel'];
    $estrutural   = $o58_elemento.'00';
    $estrutural   = substr($estrutural,0,$nivel);
    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

    if (substr($o58_elemento,3,2) != "91"){
      continue;
    }

    if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
      $m_despesa[$linha]['anterior'] += $liquidado_acumulado;
      $m_despesa[$linha]['rpnp_ant'] += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
    }
  }
}

$despesa[48]["txt"] = "ADMINISTRA��O (XII)";
$despesa[49]["txt"] = " Despesas Correntes";//55
$despesa[50]["txt"] = " Despesas de Capital";//56


$pcol = array( 1 => 'inicial',
			   2 => 'atualizada',
			   3 => 'bimestre',
			   4 => 'exercicio',
			   5 => 'anterior',
			   6 => 'rpnp_exe',
			   7 => 'rpnp_ant');
    
$ipcol = count($pcol);

for($col = 1; $col <= $ipcol; $col++){ 

  // Despesas Correntes
  $despesa[49][$pcol[$col]]  = $m_despesa[55][$pcol[$col]];
  // Despesas de Capital     
  $despesa[50][$pcol[$col]]  = $m_despesa[56][$pcol[$col]];
  // Administracao     
  $despesa[48][$pcol[$col]]  = $m_despesa[55][$pcol[$col]] + $m_despesa[56][$pcol[$col]];

  $despesa[51][$pcol[$col]]  = $despesa[48][$pcol[$col]];
  
  $despesa[16][$pcol[$col]]  = $despesa[48][$pcol[$col]]; 
}


$total_desp_inicial    = $despesa[0]['inicial']    + $despesa[16]['inicial']; 
$total_desp_atualizada = $despesa[0]['atualizada'] + $despesa[16]['atualizada'];
$total_desp_bimestre   = $despesa[0]['bimestre']   + $despesa[16]['bimestre'];
$total_desp_exercicio  = $despesa[0]['exercicio']  + $despesa[16]['exercicio'];
$total_desp_anterior   = $despesa[0]['anterior']   + $despesa[16]['anterior'];
$total_desp_rpnp_exe   = $despesa[0]['rpnp_exe']   + $despesa[16]['rpnp_exe'];
$total_desp_rpnp_ant   = $despesa[0]['rpnp_ant']   + $despesa[16]['rpnp_ant'];



// aportes
$aAportes  = array();
$aAportes[1]['txt'] = "TOTAL DO APORTES PARA O RPPS"; 
$aAportes[2]['txt'] = "    Plano Financeiro";
$aAportes[3]['txt'] = "      Recursos para Cobertura de Insufici�ncias Financeiras";//linha 28
$aAportes[4]['txt'] = "      Recursos para Forma��o de Reserva";//linha 29
$aAportes[5]['txt'] = "      Outros Aportes para o RPPS";//linha 30
$aAportes[6]['txt'] = "    Plano Previdenci�rio";
$aAportes[7]['txt'] = "      Recursos para Cobertura de D�ficit Financeiro";//linha 31
$aAportes[8]['txt'] = "      Recursos para Cobertura de D�ficit Atuarial";//linha 32
$aAportes[9]['txt'] = "      Outros Aportes para o RPPS";//linha 33
  
for($linha=1;$linha<=9;$linha++){
  
  $aAportes[$linha]['inicial']    = 0;
  $aAportes[$linha]['atualizada'] = 0;
  $aAportes[$linha]['bimestre']   = 0;
  $aAportes[$linha]['exercicio']  = 0;  // ate o bimestre
  $aAportes[$linha]['anterior']   = 0;  // ate o bimestre exercicio anterior
  $aAportes[$linha]['rpnp_exe']   = 0;  // RP Nao Processados Exercicio
  $aAportes[$linha]['rpnp_ant']   = 0;  // RP Nao Processados Exercicio Anterior
  
}


$db_filtro_disponivel = "c61_instit in (".$sListaInstit.") ";

// Exercicio Atual
$rsDispAtual 	  = db_planocontassaldo_matriz($anousu,$dtDataInicial,$dtDataFinal,false,$db_filtro_disponivel);
$iLinhasDispAtual = pg_num_rows($rsDispAtual);
@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");


// Exercicio Anterior
$rsDispAnterior      = db_planocontassaldo_matriz($anousu_ant, $dtDataInicialAnt, $dtDataFinalAnt, false, $db_filtro_disponivel);
$iLinhasDispAnterior = pg_num_rows($rsDispAnterior);
@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");

for ($linha = 28; $linha <= 33; $linha++){	
  
  for ($iInd = 0; $iInd < $iLinhasDispAtual; $iInd++) {
    
  	$oDispAtual  = db_utils::fieldsMemory($rsDispAtual,$iInd);

    if (substr($oDispAtual->estrutural,3,2) == "91"){
      continue;
    }
	
    if (in_array($oDispAtual->estrutural, $aParamAportes[$linha]['estrut'])){
      $aParamAportes[$linha]['bimestre']  += $oDispAtual->saldo_final - $oDispAtual->saldo_anterior;
      $aParamAportes[$linha]['exercicio'] += $oDispAtual->saldo_final;
    }
  }
  
  for ($iInd=0; $iInd < $iLinhasDispAnterior; $iInd++) {
  	
    $oDispAnterior = db_utils::fieldsMemory($rsDispAnterior,$iInd);
    
    if (substr($oDispAnterior->estrutural,3,2) == "91"){
      continue;
    }

    if (in_array($oDispAnterior->estrutural, $aParamAportes[$linha]['estrut'])) {
      $aParamAportes[$linha]['anterior'] +=  $oDispAnterior->saldo_final;
    }
  }  
  
}

for ($iInd = 0; $iInd < $iLinhaDespFuncaoAtual; $iInd++) {
    
  $oDespFuncaoAtual = db_utils::fieldsMemory($rsDespFuncaoAtual,$iInd);
  $sEstrutural      = $oDespFuncaoAtual->o58_elemento.'00';
  
  if (substr($oDespFuncaoAtual->o58_elemento,3,2) == "91"){
    continue;
  }
  
  $sNivel      = $aParamReservaOrc[34]['nivel'];
  $sEstrutural = $oDespFuncaoAtual->o58_elemento.'00';
  $sEstrutural = substr($sEstrutural,0,$sNivel);
  $sEstrutural = str_pad($sEstrutural, 15, "0", STR_PAD_RIGHT);  
  
  if (in_array($sEstrutural, $aParamReservaOrc[34]['estrut'])){
     $aParamReservaOrc[34]['atualizada'] += $oDespFuncaoAtual->dot_ini + ($oDespFuncaoAtual->suplementado_acumulado - $oDespFuncaoAtual->reduzido_acumulado);
     $aParamReservaOrc[34]['inicial']    += $oDespFuncaoAtual->dot_ini;
  }
}



$pcol = array( 1 => 'inicial',
			   2 => 'atualizada',
			   3 => 'bimestre',
			   4 => 'exercicio',
			   5 => 'anterior');

			   
$ipcol = count($pcol);

for($col = 1; $col <= $ipcol; $col++){ 
  
  // Recursos para Cobertura de Insufici�ncias Financeiras	
  $aAportes[3][$pcol[$col]]  = $aParamAportes[28][$pcol[$col]];
  // Recursos para Forma��o de Reserva	
  $aAportes[4][$pcol[$col]]  = $aParamAportes[29][$pcol[$col]];
  // Outros Aportes para o RPPS
  $aAportes[5][$pcol[$col]]  = $aParamAportes[30][$pcol[$col]];
  
  
  // Recursos para Cobertura de D�ficit Financeiro
  $aAportes[7][$pcol[$col]]  = $aParamAportes[31][$pcol[$col]];
  // Recursos para Cobertura de D�ficit Atuarial	
  $aAportes[8][$pcol[$col]]  = $aParamAportes[32][$pcol[$col]];
  // Outros Aportes para o RPPS
  $aAportes[9][$pcol[$col]]  = $aParamAportes[33][$pcol[$col]];
  
  
  // Plano Financeiro
  $aAportes[2][$pcol[$col]]  = $aAportes[3][$pcol[$col]] + $aAportes[4][$pcol[$col]] + $aAportes[5][$pcol[$col]];
  // Plano Previdenci�rio	
  $aAportes[6][$pcol[$col]]  = $aAportes[7][$pcol[$col]] + $aAportes[8][$pcol[$col]] + $aAportes[9][$pcol[$col]];
  
  
  // TOTAL DO APORTES PARA O RPPS
  $aAportes[1][$pcol[$col]]  = $aAportes[2][$pcol[$col]] + $aAportes[6][$pcol[$col]];  
	
}
// reserva or�ament�ria
$reserva_orc  = array();
$reserva_orc[1]['txt'] = "VALOR";//linha 34

for ($linha=1;$linha<=1;$linha++){
  $reserva_orc[$linha]['atualizada'] = $aParamReservaOrc[34]['atualizada'];
  $reserva_orc[$linha]['inicial']    = $aParamReservaOrc[34]['inicial'];
}


// Disponibilidades e Investimentos Financeiros
$disponivel  = array();
$disponivel[1]['txt'] = "CAIXA";//linha 35
$disponivel[2]['txt'] = "BANCOS CONTA MOVIMENTO";//linha 36
$disponivel[3]['txt'] = "INVESTIMENTOS";//linha 37
$disponivel[4]['txt'] = "OUTROS BENS E DIREITOS";//linha 38

for ($linha=1;$linha<=4;$linha++){
	
  $disponivel[$linha]['saldo_inicial']          = 0;
  $disponivel[$linha]['saldo_periodo_atual']    = 0;
  $disponivel[$linha]['saldo_periodo_anterior'] = 0;
  
}
	
$db_filtro_disponivel = "c61_instit in (".$sListaInstit.") ";


// Exercicio Atual
$rsDispAtual 	  = db_planocontassaldo_matriz($anousu,$dtDataInicial,$dtDataFinal,false,$db_filtro_disponivel);
$iLinhasDispAtual = pg_num_rows($rsDispAtual);
@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");


// Exercicio Anterior
$rsDispAnterior      = db_planocontassaldo_matriz($anousu_ant,$dtDataInicialAnt,$dtDataFinalAnt,false,$db_filtro_disponivel);
$iLinhasDispAnterior = pg_num_rows($rsDispAnterior);
@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");

$aDataMes               = explode("-", $dtDataInicial);
$dtDataFinalMesAnterior = "{$aDataMes[0]}-{$aDataMes[1]}-".cal_days_in_month(CAL_GREGORIAN, $aDataMes[1], $aDataMes[2]);
$rsDispMesAnterior      = db_planocontassaldo_matriz($anousu, $dtDataInicial, $dtDataFinalMesAnterior, false, $db_filtro_disponivel);
$iLinhasMesAnterior     = pg_num_rows($rsDispMesAnterior);
@pg_query("drop table work_receita"); 
@pg_query("drop table work_pl");
@pg_query("drop table work_pl_estrut");
@pg_query("drop table work_pl_estrutmae");


for ($linha = 35; $linha <= 38; $linha++){

  for ($iInd = 0; $iInd < $iLinhasDispAtual; $iInd++) {
  	
  	$oDispAtual = db_utils::fieldsMemory($rsDispAtual,$iInd);

    if (substr($oDispAtual->estrutural,3,2) == "91"){
      continue;
    }

    if (in_array($oDispAtual->estrutural, $m_disponivel[$linha]['estrut'])){
      $m_disponivel[$linha]['saldo_periodo_atual'] += $oDispAtual->saldo_final;      
    }
  }

  for ($iInd = 0; $iInd < $iLinhasMesAnterior; $iInd++) {
  	
  	$oDispMesAnterior = db_utils::fieldsMemory($rsDispMesAnterior,$iInd);

    if (substr($oDispAtual->estrutural,3,2) == "91"){
      continue;
    }

    if (in_array($oDispMesAnterior->estrutural, $m_disponivel[$linha]['estrut'])){
      $m_disponivel[$linha]['saldo_inicial']   += $oDispMesAnterior->saldo_final;
    }
  }
	
  for ($iInd = 0; $iInd < $iLinhasDispAnterior; $iInd++) {
  	
  	$oDispAnterior = db_utils::fieldsMemory($rsDispAnterior,$iInd);

    if (substr($oDispAnterior->estrutural,3,2) == "91"){
      continue;
    }
    if (in_array($oDispAnterior->estrutural, $m_disponivel[$linha]['estrut'])){
      $m_disponivel[$linha]['saldo_periodo_anterior'] += $oDispAnterior->saldo_final;
      
    }
  }
  
}


for ($col=1;$col<=3;$col++){ 
  
  $pcol = array( 1=>'saldo_inicial',
                 2=>'saldo_periodo_atual',
                 3=>'saldo_periodo_anterior');

  $disponivel[1][$pcol[$col]]  = $m_disponivel[35][$pcol[$col]];
  $disponivel[2][$pcol[$col]]  = $m_disponivel[36][$pcol[$col]];
  $disponivel[3][$pcol[$col]]  = $m_disponivel[37][$pcol[$col]];
  $disponivel[4][$pcol[$col]]  = $m_disponivel[38][$pcol[$col]];
  
}

if (!isset($arqinclude)){ //
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Imprimindo Relatorio
  $perini = $dtDataInicial;
  $perfin = $dtDataFinal;

  $resultinst = pg_exec("select upper(munic) as munic, codigo,nomeinst,nomeinstabrev from db_config where db21_tipoinstit in (5,6)");
  $descr_inst = '';
  $xvirg = '';
  $flag_abrev = false;

  for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
      $descr_inst .= $xvirg.$nomeinstabrev;
      $flag_abrev  = true;

    }else{
      $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
  }

  if ($flag_abrev == false){
    if (strlen($descr_inst) > 42){
      $descr_inst = substr($descr_inst,0,100);
    }
  }

  $head1 = "MUNIC�PIO DE {$munic}";
  $head2 = "RELAT�RIO RESUMIDO DA EXECU��O OR�AMENT�RIA";
  $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS PREVIDENCI�RIAS DO REGIME PR�PRIO DOS SERVIDORES P�BLICOS";
  $head4 = "OR�AMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  $txt = strtoupper(db_mes('01'));
  $dt  = split("-",$dtDataFinal);
  $txt.= " � ".strtoupper(db_mes($dt[1]))."$anousu/{$sDescrPeriodo} ";;
  $head5 = "$txt";

  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $troca = 1;
  $alt = 4;

  $pagina   = 1;
  $tottotal = 0;
  $pagina   = 0;
  $n1 	    = 5;
  $n2	    = 10;

  $pdf->addpage();
  $pdf->setfont('arial','',6);
  $pdf->cell(90,$alt,"RREO - Anexo V (LRF, Art. 53, inciso II)",0,0,"L",0);
  $pdf->cell(100,$alt,"R$ 1,00"								   ,0,1,"R",0);

  $pdf->setfont('arial','',6);
  $pdf->cell(90,($alt*2),"RECEITAS"		  		  ,'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O"			 		  , "TR",0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O"			  		  , "TR",0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS"		  , 'TB',1,"C",0); 
  $pdf->setX(100); 
  $pdf->cell(20,$alt,"INICIAL"			  		  , "BR",0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA"		 		  , "BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(20,$alt,"No {$sDescrPeriodo}"		  		  ,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu	  ,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu_ant, 'TB',0,"C",0);
  $pdf->ln();

  $sBordas = '';
  
  
  for($linha=0;$linha<=25;$linha++){

    $pdf->cell(90,$alt,$receita[$linha]['txt']						  ,"R",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f')   ,"R",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),"R",0,"R",0);    
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f')  ,"R",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f') ,"R",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['anterior'],'f')  ,"0",0,"R",0);       
    $pdf->Ln();
    
    if ($linha == 24){
      $pdf->line(10,$pdf->getY(),200,$pdf->getY());
    }

  }
  
  $pdf->cell(90,$alt,"TOTAL DAS RECEITAS PREVIDENCI�RIAS-RPPS (III)=(I+II)" ,"TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_inicial,'f')				    ,"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_atualizada,'f')					,"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_bimestre,'f')					,"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_exercicio,'f')					,"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($total_rec_anterior,'f')					, "TB",0,"R",0);
  $pdf->ln();

  //
  // D E S P E S A S
  //
  
  $tam_lin  = ($ultimo_periodo)? 04: 02;
  $tam_col1 = ($ultimo_periodo)? 71: 90;
  $tam_col2 = ($ultimo_periodo)? 17: 18;
  $tam_col3 = ($ultimo_periodo)? 17: 18;
  $tam_col4 = ($ultimo_periodo)? 17: 18;
  $tam_col5 = ($ultimo_periodo)? 17: 18;
  $tam_col6 = ($ultimo_periodo)? 17: 18; // Inscr RP Nao Proc
  $tam_col7 = ($ultimo_periodo)? 17: 18; // Inscr RP Nao Proc Anterior
  $tam_col8 = ($ultimo_periodo)? 17: 18; // Inscr RP Nao Proc Anterior
  $tam_desp = ($ultimo_periodo)?190:190; // tamanho colunas 3+4+5 (e +6+7 qdo $ultimo_periodo==true)

  $pdf->Ln(3);
  
  if($ultimo_periodo) {
  	
    $pdf->cell($tam_col1,($alt*$tam_lin), "DESPESAS PREVIDENCI�RIAS","TBR",0,"C",0); //col1
    $pdf->cell($tam_col2, $alt,""									, "TR",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,""									, "TR",0,"C",0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6+$tam_col7+$tam_col8;
    
    $pdf->cell($tam,$alt,"DESPESAS EXECUTADAS"	 ,"TB",  0, "C", 0); //col4+col5+col6
    
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,"DOTA��O","R",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,"DOTA��O","R",0,"C",0); //col3

    $pdf->cell($tam_col4+$tam_col5+$tam_col7, $alt, "Em ".$anousu,     "RB",0,"C",0);
    $pdf->cell($tam_col6+$tam_col8,           $alt, "Em ".($anousu-1),  "B",0,"C",0);
    
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,"INICIAL"   ,"R",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,"ATUALIZADA","R",0,"C",0); //col3

    $posX = $pdf->getX();
    
    $pdf->cell($tam_col4+$tam_col5,$alt,"LIQUIDADAS","RB",0,"C",0);
    
    $posY = $pdf->getY()+$alt;

    $pdf->cell($tam_col7,($alt*2),"Inscritas RP NP" ,"RB",0,"C",0);
    $pdf->cell($tam_col6, $alt,"LIQUIDADAS"			, "R",0,"C",0);
    $pdf->cell($tam_col8,($alt*2), "Inscritas RP NP", "B",0,"C",0);
    
    $pdf->ln();

    $pdf->setY($posY);
    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "",                            "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3, $alt,     "",                            "BR",  0, "C", 0); //col3

    $pdf->cell($tam_col4, $alt,     "No {$sDescrPeriodo}",   	   "BR", 0, "C", 0); //col4
    $pdf->cell($tam_col5, $alt,     "At� o {$sDescrPeriodo}", 	   "BR", 0, "C", 0); //col5

    $pdf->setX($pdf->getX()+$tam_col7);
    $pdf->cell($tam_col6, $alt,     "At� o {$sDescrPeriodo}", 	   "BR",  0, "C", 0); //col6
    
    
  } else {
  	
  	
    $pdf->cell($tam_col1, ($alt*$tam_lin),"DESPESAS ","TBR",0,"C",0); //col1
    $pdf->cell($tam_col2, $alt,"DOTA��O"			 , "TR",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,"DOTA��O"			 , "TR",0,"C",0); //col3
    
    $tam = $tam_col4+$tam_col5+$tam_col6;
    
    $pdf->cell($tam,$alt,"DESPESAS LIQUIDADAS",    		     "TB",  1, "C", 0); //col4+col5+col6
    $pdf->setX(100);
    $pdf->cell($tam_col2,$alt,"INICIAL",                     "BR",  0, "C", 0); //col2
    $pdf->cell($tam_col3,$alt,"ATUALIZADA",                  "BR",  0, "C", 0); //col3
    $pdf->setX(140); 
    $pdf->cell($tam_col4,$alt,"No {$sDescrPeriodo}",                 "TBR", 0, "C", 0); //col4
    $pdf->cell($tam_col5,$alt,"At� o {$sDescrPeriodo}/".$anousu,     "TBR", 0, "C", 0); //col5
    $pdf->cell($tam_col6,$alt,"At� o {$sDescrPeriodo}/".$anousu_ant, "TB",  0, "C", 0); //col6
  }
  
  
  $pdf->ln();

  for ($linha=0; $linha<=16; $linha++) {

    $pdf->cell($tam_col1, $alt,             $despesa[$linha]['txt'],               'R',0,"L",0);
    $pdf->cell($tam_col2, $alt, db_formatar($despesa[$linha]['inicial'],    'f')  ,'R',0,"R",0);
    $pdf->cell($tam_col3, $alt, db_formatar($despesa[$linha]['atualizada'], 'f')  ,'R',0,"R",0);
    $pdf->cell($tam_col4, $alt, db_formatar($despesa[$linha]['bimestre'],   'f')  ,'R',0,"R",0);
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[$linha]['exercicio'],  'f')  ,'R',0,"R",0);
    
    if ($ultimo_periodo) {
    	
      $pdf->cell($tam_col7, $alt, db_formatar($despesa[$linha]['rpnp_exe'],   'f'),'R',0,"R",0);
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'],   'f'),'R',0,"R",0);
      $pdf->cell($tam_col8, $alt, db_formatar($despesa[$linha]['rpnp_ant'],   'f'),'0',0,"R",0);
      
    } else {
    	
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'],   'f'),'0',0,"R",0);
      
    }
    
    $pdf->Ln();
    
  }

  $pdf->cell($tam_col1, $alt, "TOTAL DAS DESPESAS PREVIDENCI�RIAS-RPPS (VI)=(IV+V)", "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($total_desp_inicial,    'f'),              "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($total_desp_atualizada, 'f'),              "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($total_desp_bimestre,   'f'),              "TBR", 0, "R", 0);
  
  if($ultimo_periodo) {
  	
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($total_desp_exercicio + $total_desp_rpnp_exe, 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($total_desp_anterior  + $total_desp_rpnp_ant, 'f'), "TB",  0, "R", 0);

  } else {
  	
    $pdf->cell($tam_col5, $alt, db_formatar($total_desp_exercicio,  'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($total_desp_anterior,   'f'), "TB",  0, "R", 0);
    
  }
  
  $pdf->ln();

  
  
  $pdf->cell($tam_col1, $alt, "RESULTADO PREVIDENCI�RIO(VII)=(III-VI)",                         "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($total_rec_inicial    - $total_desp_inicial,    'f'), "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($total_rec_atualizada - $total_desp_atualizada, 'f'), "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($total_rec_bimestre   - $total_desp_bimestre,   'f'), "TBR", 0, "R", 0);
  
  if($ultimo_periodo) {
  	
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($total_rec_exercicio - ($total_desp_exercicio + $total_desp_rpnp_exe), 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($total_rec_anterior  - ($total_desp_anterior  + $total_desp_rpnp_ant), 'f'), "TB",  0, "R", 0);

  } else {
  	
    $pdf->cell($tam_col5, $alt, db_formatar($total_rec_exercicio - $total_desp_exercicio, 'f'), "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($total_rec_anterior  - $total_desp_anterior,  'f'), "TB",  0, "R", 0);
    
  } 
  
  $pdf->ln();

  $pdf->cell(190,$alt,"Continua(2/{nb})",'TB',1,"R",0);
  $pdf->addpage();
  $pdf->cell(190,$alt,"Continua��o",'TB',1,"R",0);


  
  $pdf->cell(190,0,"","T");
  $pdf->ln(3);
  $pdf->setfont('arial','',6);
  $pdf->cell(90,($alt*2)," APORTES DE RECURSOS PARA O REGIME - RPPS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O "										, "TR",0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O "										, "TR",0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS"							, 'TB',1,"C",0); 
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL"										, "BR",0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA"									, "BR",0,"C",0);
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No {$sDescrPeriodo}"							,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu				,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu_ant			, "TB",0,"C",0);
  $pdf->ln();
  
  
  for($linha=1;$linha<=9;$linha++){
  	
    $pdf->cell(90,$alt,$aAportes[$linha]['txt']						   ,'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($aAportes[$linha]['inicial'],'f')   ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aAportes[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aAportes[$linha]['bimestre'],'f')  ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aAportes[$linha]['exercicio'],'f') ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aAportes[$linha]['anterior'],'f')  ,  0,0,"R",0);
    $pdf->Ln();

  }

  $pdf->cell(190,0,"","T");
  $pdf->Ln(3);

  //reserva orcamentaria
  $pdf->cell(130,($alt*2),"RESERVA OR�AMENT�RIA DO RPPS",'TBR',0,"C",0);
  $pdf->cell(60,$alt*2,   "PREVIS�O OR�AMENT�RIA"		, 'TB',0,"C",0); 

  $pdf->ln();
  
  for($linha=1;$linha<=1;$linha++){
  	
    $pdf->cell(130,$alt,$reserva_orc[$linha]['txt']						  ,'R',0,"L",0);
    $pdf->cell(60,$alt,db_formatar($reserva_orc[$linha]['inicial'],'f'), 0 ,0,"R",0);
    $pdf->Ln();

  }
  
  $pdf->cell(190,0,"","T");
  $pdf->Ln(3);

  

  $pdf->cell(90,($alt*2),"BENS E DIREITOS DO RPPS",'TBR',0,"C",0);
  $pdf->cell(40,$alt,$periodo_mes."/".$anousu     , "TR",0,"C",0);
  $pdf->cell(60,$alt,"PER�ODO DE REFER�NCIA"	  , 'TB',1,"C",0);
  $pdf->setX(100); 
  $pdf->cell(20,$alt,""							  ,  "B",0,"C",0);
  $pdf->cell(20,$alt,""							  , "BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(30,$alt,$anousu					  ,"TBR",0,"C",0);
  $pdf->cell(30,$alt,$anousu_ant				  , 'TB',0,"C",0);
  
  $pdf->ln();

  for($linha=1; $linha<=4; $linha++){
  	
    $pdf->cell(90,$alt,$disponivel[$linha]['txt']								  	 ,'R',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($disponivel[$linha]['saldo_inicial'],'f')	  	 ,"R",0,"R",0);
    $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_atual'],'f')   ,"R",0,"R",0);
    $pdf->cell(30,$alt,db_formatar($disponivel[$linha]['saldo_periodo_anterior'],'f'), 0 ,0,"R",0);    
    $pdf->Ln();
    	    
  }
  
  $pdf->cell(190,0,"","T");
  $pdf->ln(3);

  //receita intra-orcamentarias
  
  $pdf->setfont('arial','',6);
  $pdf->cell(90,($alt*2),"RECEITAS INTRA-OR�AMENT�RIAS - RPPS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O"								  , "TR",0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O"								  , "TR",0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS"					  , 'TB',1,"C",0);
  $pdf->setX(100); 
  $pdf->cell(20,$alt,"INICIAL"								  , "BR",0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA"							  , "BR",0,"C",0);
  $pdf->setX(140); 
  $pdf->cell(20,$alt,"No {$sDescrPeriodo}"					  ,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu		  ,"TBR",0,"C",0);
  $pdf->cell(20,$alt,"At� o {$sDescrPeriodo}/".$anousu_ant	  , 'TB',0,"C",0);
  $pdf->ln();

  for($linha=28;$linha <=48;$linha++){
  	
    $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f')   ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);    
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f')  ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f') ,'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['anterior'],'f')  , 0 ,0,"R",0);       
    $pdf->Ln();
    
  }

  $pdf->cell(90,$alt,"TOTAL DAS RECEITAS PREVIDENCI�RIAS INTRA-OR�AMENTARIAS (XI)=(VIII+IX-X)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($receita[49]['inicial'],'f')	,'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[49]['atualizada'],'f'),'TBR',0,"R",0);    
  $pdf->cell(20,$alt,db_formatar($receita[49]['bimestre'],'f')  ,'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[49]['exercicio'],'f') ,'TBR',0,"R",0);
  $pdf->cell(20,$alt,db_formatar($receita[49]['anterior'],'f')  , 'TB',0,"R",0);       
  $pdf->ln();


  // Despesas

  $pdf->Ln(3);
  
  if($ultimo_periodo) {
  	
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS PREVIDENCI�RIAS INTRA-OR�AMENTARIAS - RPPS","TBR",0,"C",0); //col1
    $pdf->cell($tam_col2,$alt,"","TR",0,"C",0); //col2
    $pdf->cell($tam_col3,$alt,"","TR",0,"C",0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6+$tam_col7+$tam_col8;
    
    $pdf->cell($tam,$alt,"DESPESAS EXECUTADAS","TB",0,"C",0); //col4+col5+col6
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,"DOTA��O","R",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,"DOTA��O","R",0,"C",0); //col3

    $pdf->cell($tam_col4+$tam_col5+$tam_col7, $alt, "Em ".$anousu,     "RB",0,"C",0);
    $pdf->cell($tam_col6+$tam_col8,           $alt, "Em ".($anousu-1),  "B",0,"C",0);
    $pdf->ln();

    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                   "R",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA"               , "R",0,"C",0); //col3

    $posX = $pdf->getX();
    $pdf->cell($tam_col4+$tam_col5, $alt,     "LIQUIDADAS"     ,"RB",0,"C",0);
    $posY = $pdf->getY()+$alt;

    $pdf->cell($tam_col7,           ($alt*2), "Inscritas RP NP","RB",0,"C",0);

    $pdf->cell($tam_col6,           $alt, "LIQUIDADAS"			,"R",0,"C",0);
    $pdf->cell($tam_col8,           ($alt*2), "Inscritas RP NP" ,"B",0,"C",0);
    $pdf->ln();

    $pdf->setY($posY);
    $pdf->setX(81); 
    $pdf->cell($tam_col2, $alt,     "","BR",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,     "","BR",0,"C",0); //col3

    $pdf->cell($tam_col4, $alt,     "No {$sDescrPeriodo}",   "BR",0,"C",0); //col4
    $pdf->cell($tam_col5, $alt,     "At� o {$sDescrPeriodo}","BR",0,"C",0); //col5

    $pdf->setX($pdf->getX()+$tam_col7);
    $pdf->cell($tam_col6, $alt,     "At� o {$sDescrPeriodo}","BR",0,"C",0); //col6
  } else {
    $pdf->cell($tam_col1, ($alt*$tam_lin), "DESPESAS INTRA-OR�AMENT�RIAS - RPPS","TBR",0,"C",0); //col1
    
    $pdf->cell($tam_col2, $alt,     "DOTA��O",                      "TR",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,     "DOTA��O",                      "TR",0,"C",0); //col3

    $tam = $tam_col4+$tam_col5+$tam_col6;
    $pdf->cell($tam,      $alt,     "DESPESAS LIQUIDADAS",          "TB",1,"C",0); //col4+col5+col6
    $pdf->setX(100); 
    $pdf->cell($tam_col2, $alt,     "INICIAL",                      "BR",0,"C",0); //col2
    $pdf->cell($tam_col3, $alt,     "ATUALIZADA",                   "BR",0,"C",0); //col3
    $pdf->setX(140); 
    $pdf->cell($tam_col4, $alt,     "No {$sDescrPeriodo}",                 "TBR",0,"C",0); //col4
    $pdf->cell($tam_col5, $alt,     "At� o {$sDescrPeriodo}/".$anousu,     "TBR",0,"C",0); //col5
    $pdf->cell($tam_col6, $alt,     "At� o {$sDescrPeriodo}/".$anousu_ant,  "TB",0,"C",0); //col6
  }
  $pdf->ln();



  for($linha=48;$linha<=50;$linha++){
    $pdf->cell($tam_col1, $alt,             $despesa[$linha]['txt'],              'R', 0, "L", 0);
    $pdf->cell($tam_col2, $alt, db_formatar($despesa[$linha]['inicial'],    'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col3, $alt, db_formatar($despesa[$linha]['atualizada'], 'f'), 'R', 0, "R", 0);    
    $pdf->cell($tam_col4, $alt, db_formatar($despesa[$linha]['bimestre'],   'f'), 'R', 0, "R", 0);
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[$linha]['exercicio'],  'f'), 'R', 0, "R", 0);
    if($ultimo_periodo) {
      $pdf->cell($tam_col7, $alt, db_formatar($despesa[$linha]['rpnp_exe'], 'f'), 'R', 0, "R", 0);       
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'], 'f'), 'R', 0, "R", 0);       
      $pdf->cell($tam_col8, $alt, db_formatar($despesa[$linha]['rpnp_ant'], 'f'), '0', 0, "R", 0);       
    } else {
      $pdf->cell($tam_col6, $alt, db_formatar($despesa[$linha]['anterior'], 'f'), '0', 0, "R", 0);       
    } 
    $pdf->Ln();	    
  }


  $pdf->cell($tam_col1, $alt, "TOTAL DAS DESP. PREVID. INTRA-OR�AMENTARIAS(XIII=XII)", "TBR", 0, "L", 0);
  $pdf->cell($tam_col2, $alt, db_formatar($despesa[51]['inicial'],    'f'),             "TBR", 0, "R", 0);
  $pdf->cell($tam_col3, $alt, db_formatar($despesa[51]['atualizada'], 'f'),             "TBR", 0, "R", 0);
  $pdf->cell($tam_col4, $alt, db_formatar($despesa[51]['bimestre'],   'f'),             "TBR", 0, "R", 0);
  if($ultimo_periodo) {
    
    $pdf->cell($tam_col5+$tam_col7, $alt, db_formatar($despesa[51]['exercicio'] + $despesa[51]['rpnp_exe'],  'f'),             "TBR", 0, "R", 0);
    $pdf->cell($tam_col6+$tam_col8, $alt, db_formatar($despesa[51]['anterior']  + $despesa[51]['rpnp_ant'],   'f'),             "TB",  0, "R", 0);
    
  } else {
    
    $pdf->cell($tam_col5, $alt, db_formatar($despesa[51]['exercicio'],  'f'),             "TBR", 0, "R", 0);
    $pdf->cell($tam_col6, $alt, db_formatar($despesa[51]['anterior'],   'f'),             "TB",  0, "R", 0);
    
  }
  $pdf->ln();
  $pdf->cell($tam_desp, $alt, "({nb}/{nb})", 'TB', 1, "R", 0);
  //assinatura
  notasExplicativas(&$pdf,23,$oGet->periodo,180);
  $pdf->ln(10);
  $pdf->setfont('arial','',6);

  assinaturas(&$pdf,&$classinatura,'LRF');
   
  $pdf->Output();

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

?>