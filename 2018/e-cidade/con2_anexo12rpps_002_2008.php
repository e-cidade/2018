<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orcparamrel_classe.php"));
include(modification("classes/db_empresto_classe.php"));
include(modification("classes/db_empempenho_classe.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;

$iUsuInstit  = db_getsession('DB_instit');
$rsUsuInstit = db_query(" select codigo from db_config where ( db21_tipoinstit in (5,6) or prefeitura is true ) and codigo = ".$iUsuInstit);
$iNumRowsUsuInstit = pg_num_rows($rsUsuInstit);

if($iNumRowsUsuInstit == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=O usuário deve ser da instituição RPPS ou Prefeitura para visualizar o relatório');
}

$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Instituição RPPS.');
}else{
	$oInstit  = db_utils::fieldsMemory($rsInstit,0);
}

$head2 =  $oInstit->nomeinst;
$head3 = "BALANÇO ORÇAMENTÁRIO DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($mes == 1){
$head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{
$head4 = "JANEIRO A ".strtoupper(db_mes($mes))." DE ".db_getsession("DB_anousu");
}
$anousu	 = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.'01'.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

$somatorio_receita_ini			= 0;
$somatorio_receita_exec			= 0;


//--- BALANCETE DE RECEITA

$sSqlFiltro = ' o70_instit = '.$oInstit->codigo;
$rsReceitaSaldo = db_receitasaldo(11,1,3,true,$sSqlFiltro,$anousu,$dataini,$datafin);
//db_criatabela($rsReceitaSaldo);
//echo "mes $mes, sqlfiltro $sSqlFiltro, dataini: $dataini, datafim: $datafin ";exit;


//db_criatabela($rsReceitaSaldo);exit;

//variaveis da previsão da receita
$receita_contribuicoes[0]    = 0;
$receita_patrimonial[0]      = 0; 
$outras_receitas_correntes[0]= 0;
$alienacao_bens[0]           = 0;
$amortizacao_emprestimos[0]  = 0;
$outras_receitas_capital[0]  = 0; 
$aOperCorrContrib[0]	       = 0;
$aOperCorrPatrim[0]	         = 0;
$aOperCorrOutras[0]	         = 0;  
$aOperCapAlienacao[0]        = 0;
$aOperCapEmprestimo[0]       = 0;
$aOperCapOutras[0]           = 0;

// variaveis da arrecadação
$receita_contribuicoes[1]    = 0;
$receita_patrimonial[1]      = 0; 
$outras_receitas_correntes[1]= 0;
$alienacao_bens[1]           = 0;
$amortizacao_emprestimos[1]  = 0;
$outras_receitas_capital[1]  = 0; 
$aOperCorrContrib[1]	       = 0;
$aOperCorrPatrim[1]	         = 0;
$aOperCorrOutras[1]	         = 0;  
$aOperCapAlienacao[1]        = 0;
$aOperCapEmprestimo[1]       = 0;
$aOperCapOutras[1]           = 0;

$iTotRecCorrPrev  = 0;
$iTotRecCorrExe   = 0;
$iTotRecCapPrev   = 0;
$iTotRecCapExe    = 0;
$iTotOperCorrPrev = 0;
$iTotOperCorrExe  = 0;
$iTotOperCapPrev  = 0; 
$iTotOperCapExe   = 0;


for ($i=0;$i<pg_numrows($rsReceitaSaldo);$i++){
 $oReceitaSaldo = db_utils::fieldsMemory($rsReceitaSaldo,$i);   

//------------------    RECEITAS DE CORRENTE    -------------------//
		
		if ((substr($oReceitaSaldo->o57_fonte,0,2)=='41' || substr($oReceitaSaldo->o57_fonte,0,2)=='91' ) && $oReceitaSaldo->o70_codigo == 0){      
			if ($oReceitaSaldo->o57_fonte == '412000000000000' && $oReceitaSaldo->o70_codigo == 0){      
				 $receita_contribuicoes[0]+= $oReceitaSaldo->saldo_inicial;   
				 $receita_contribuicoes[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;   
			} else if (($oReceitaSaldo->o57_fonte =='413000000000000' || $oReceitaSaldo->o57_fonte =='913000000000000' ) && $oReceitaSaldo->o70_codigo == 0){
				 $receita_patrimonial[0]+= $oReceitaSaldo->saldo_inicial;
				 $receita_patrimonial[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if (($oReceitaSaldo->o57_fonte == '416000000000000')
			            || ($oReceitaSaldo->o57_fonte == '419000000000000')) {
				 $outras_receitas_correntes[0]+= $oReceitaSaldo->saldo_inicial;
				 $outras_receitas_correntes[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			}     
			if (($oReceitaSaldo->o57_fonte =='410000000000000' || $oReceitaSaldo->o57_fonte =='910000000000000' ) && $oReceitaSaldo->o70_codigo == 0){	
				$iTotRecCorrPrev += $oReceitaSaldo->saldo_inicial;
				$iTotRecCorrExe  += $oReceitaSaldo->saldo_arrecadado_acumulado;
			}
		}

//------------------    RECEITAS DE CAPITAL    -------------------//
    
		if (substr($oReceitaSaldo->o57_fonte,0,2)=='42' && $oReceitaSaldo->o70_codigo == 0){
			if ($oReceitaSaldo->o57_fonte=='422000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $alienacao_bens[0]+= $oReceitaSaldo->saldo_inicial;
				 $alienacao_bens[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte =='423000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $amortizacao_emprestimos[0]+= $oReceitaSaldo->saldo_inicial;
				 $amortizacao_emprestimos[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte !='420000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $outras_receitas_capital[0]+= $oReceitaSaldo->saldo_inicial;
				 $outras_receitas_capital[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			}     
			if ($oReceitaSaldo->o57_fonte=='420000000000000' && $oReceitaSaldo->o70_codigo == 0){	
				$iTotRecCapPrev += $oReceitaSaldo->saldo_inicial;
				$iTotRecCapExe  += $oReceitaSaldo->saldo_arrecadado_acumulado;
		  }
		}
//-----------    OPERAÇÕES INTRA-ORÇAMENTÁRIAS CORRENTES    ------------//
		
		if (substr($oReceitaSaldo->o57_fonte,0,2)=='47' && $oReceitaSaldo->o70_codigo == 0){
			if ($oReceitaSaldo->o57_fonte=='472000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $aOperCorrContrib[0]+= $oReceitaSaldo->saldo_inicial;
				 $aOperCorrContrib[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte=='473000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $aOperCorrPatrim[0]+= $oReceitaSaldo->saldo_inicial;
				 $aOperCorrPatrim[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte  == '479000000000000'){
				 $aOperCorrOutras[0]+= $oReceitaSaldo->saldo_inicial;
				 $aOperCorrOutras[1]+= $oReceitaSaldo->saldo_arrecadado_acumulado;
			}     
			if ($oReceitaSaldo->o57_fonte =='470000000000000' && $oReceitaSaldo->o70_codigo == 0){	
				$iTotOperCorrPrev += $oReceitaSaldo->saldo_inicial;
				$iTotOperCorrExe  += $oReceitaSaldo->saldo_arrecadado_acumulado;
			}
		}

//-----------   OPERAÇÕES INTRA-ORÇAMENTÁRIAS DE CAPITAL   ------------//
		
		if (substr($oReceitaSaldo->o57_fonte,0,2) == '48' && $oReceitaSaldo->o70_codigo == 0){
			if ($oReceitaSaldo->o57_fonte == '482000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $aOperCapAlienacao[0]	 += $oReceitaSaldo->saldo_inicial;
				 $aOperCapAlienacao[1]	 += $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte == '483000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $aOperCapEmprestimo[0]  += $oReceitaSaldo->saldo_inicial;
				 $aOperCapEmprestimo[1]  += $oReceitaSaldo->saldo_arrecadado_acumulado;
			} else if ($oReceitaSaldo->o57_fonte!='480000000000000' && $oReceitaSaldo->o70_codigo == 0){
				 $aOperCapOutras[0]		   += $oReceitaSaldo->saldo_inicial;
				 $aOperCapOutras[1]			 += $oReceitaSaldo->saldo_arrecadado_acumulado;
			}     
			if ($oReceitaSaldo->o57_fonte =='480000000000000' && $oReceitaSaldo->o70_codigo == 0){
				$iTotOperCapPrev += $oReceitaSaldo->saldo_inicial;
				$iTotOperCapExe  += $oReceitaSaldo->saldo_arrecadado_acumulado;
			}
		}
}

$somatorio_receita_ini  = $iTotRecCorrPrev + $iTotRecCapPrev + $iTotOperCorrPrev + $iTotOperCapPrev;
$somatorio_receita_exec = $iTotRecCorrExe  + $iTotRecCapExe  + $iTotOperCorrExe  + $iTotOperCapExe;


$sele_work = ' w.o58_instit = '.$oInstit->codigo;
$rsDotacaoSaldo = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dataini,$datafin,8,0);
//db_criatabela($rsDotacaoSaldo); exit;

// variaveis da previsão da despesa

$aCredOrcPessoalEncargosAplicDir[0]			 = 0;
$aCredOrcPessoalEncargosAplicDirIntra[0] = 0;
$aCredOrcOutrasTransf[0]				         = 0;
$aCredOrcOutrasAplicDir[0]			         = 0;
$aCredOrcOutrasAplicDirIntra[0]          = 0;
$aCredOrcInvestAplicDir[0]			         = 0;
$aCredOrcInversAplicDir[0]			         = 0;
$aCredOrcInversAplicDirIntra[0]          = 0;
$aCredEspPessoalEncargosAplicDir[0]			 = 0;
$aCredEspPessoalEncargosAplicDirIntra[0] = 0;
$aCredEspOutrasTransf[0]				         = 0;
$aCredEspOutrasAplicDir[0]			         = 0;
$aCredEspOutrasAplicDirIntra[0]          = 0;
$aCredEspInvestAplicDir[0]			         = 0;
$aCredEspInversAplicDir[0]			         = 0;
$aCredEspInversAplicDirIntra[0]          = 0;



// variaveis da fixação da despesa

$aCredOrcPessoalEncargosAplicDir[1]			 = 0;
$aCredOrcPessoalEncargosAplicDirIntra[1] = 0;
$aCredOrcOutrasTransf[1]								 = 0;
$aCredOrcOutrasAplicDir[1]							 = 0;
$aCredOrcOutrasAplicDirIntra[1]					 = 0;
$aCredOrcInvestAplicDir[1]							 = 0;
$aCredOrcInversAplicDir[1]							 = 0;
$aCredOrcInversAplicDirIntra[1]          = 0;
$aCredEspPessoalEncargosAplicDir[1]      = 0;
$aCredEspPessoalEncargosAplicDirIntra[1] = 0;
$aCredEspOutrasTransf[1]                 = 0;
$aCredEspOutrasAplicDir[1]		           = 0;
$aCredEspOutrasAplicDirIntra[1]          = 0;
$aCredEspInvestAplicDir[1]		           = 0;
$aCredEspInversAplicDir[1]		           = 0;
$aCredEspInversAplicDirIntra[1]          = 0;


for($i=0;$i<pg_num_rows($rsDotacaoSaldo);$i++){
   
	$oDotacaoSaldo = db_utils::fieldsMemory($rsDotacaoSaldo,$i);   

  if($oDotacaoSaldo->dot_ini > 0){
 	
   
  // 1 -------- CRÉDITOS ORÇAMENTÁRIOS DE SUPLEMENTARES

	// 1.1 ------ DESPESAS CORRENTES
	
	// 1.1.1 ---- PESSOAL E ENCARGO SOCIAIS

	// 1.1.1.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33190'){
       $aCredOrcPessoalEncargosAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcPessoalEncargosAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
		}     

	// 1.1.1.2 --	Aplicações Diretas Op. Intra 
	
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33191'){
       $aCredOrcPessoalEncargosAplicDirIntra[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcPessoalEncargosAplicDirIntra[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 1.1.2 ---- OUTRAS
	
	// 1.1.2.1 --	Transf. a União
	
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33320'){
       $aCredOrcOutrasTransf[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcOutrasTransf[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 1.1.2.2 --	Aplicações Diretas 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33390'){
       //echo "ele: ".$oDotacaoSaldo->o58_elemento." dot_ini: ".$oDotacaoSaldo->dot_ini." suple: ".$oDotacaoSaldo->suplementado_acumulado."  reduz: ".$oDotacaoSaldo->reduzido_acumulado." <br>";
       $aCredOrcOutrasAplicDir[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcOutrasAplicDir[1]+= $oDotacaoSaldo->liquidado_acumulado;
       //echo "soma: ".$aCredEspOutrasAplicDir[0]."<br><br>";
    }     

	// 1.1.2.3 --	Aplicações Diretas Op. Intra 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33391'){
       $aCredOrcOutrasAplicDirIntra[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcOutrasAplicDirIntra[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	
	
	// 1.2 ------ DESPESAS DE CAPITAL
	
	// 1.2.1 ---- INVESTIMENTOS

	// 1.2.1.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34490'){
       $aCredOrcInvestAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcInvestAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     

	// 1.2.2 ---- INVERSÕES FINANCEIRAS

	// 1.2.2.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34590'){
       $aCredOrcInversAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcInversAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 1.2.2.2 --	Aplicações Diretas Op. Intra 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34591'){
       $aCredOrcInversAplicDirIntra[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredOrcInversAplicDirIntra[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     

	
	}else{

		
  // 2 -------- CRÉDITOS ESPECIAIS

	// 2.1 ------ DESPESAS CORRENTES
	
	// 2.1.1 ---- PESSOAL E ENCARGO SOCIAIS

	// 2.1.1.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33190'){
       $aCredEspPessoalEncargosAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspPessoalEncargosAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     

	// 2.1.1.2 --	Aplicações Diretas Op. Intra 
	
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33191'){
       $aCredEspPessoalEncargosAplicDirIntra[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspPessoalEncargosAplicDirIntra[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 2.1.2 ---- OUTRAS
	
	// 2.1.2.1 --	Transf. a União
	
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33320'){
       $aCredEspOutrasTransf[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspOutrasTransf[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 2.1.2.2 --	Aplicações Diretas 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33390'){
      // echo "ele: ".$oDotacaoSaldo->o58_elemento." dot_ini: ".$oDotacaoSaldo->dot_ini." suple: ".$oDotacaoSaldo->suplementado_acumulado."  reduz: ".$oDotacaoSaldo->reduzido_acumulado." <br>";
			 $aCredEspOutrasAplicDir[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspOutrasAplicDir[1]+= $oDotacaoSaldo->liquidado_acumulado;
      // echo "soma: ".$aCredEspOutrasAplicDir[0]."<br><br>";
		}     

	// 2.1.2.3 --	Aplicações Diretas Op. Intra 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '33391'){
       $aCredEspOutrasAplicDirIntra[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspOutrasAplicDirIntra[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	
	
	// 2.2 ------ DESPESAS DE CAPITAL
	
	// 2.2.1 ---- INVESTIMENTOS

	// 2.2.1.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34490'){
       $aCredEspInvestAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspInvestAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     

	// 2.2.2 ---- INVERSÕES FINANCEIRAS

	// 2.2.2.1 --	Aplicações Diretas 
		
		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34590'){
       $aCredEspInversAplicDir[0] += $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspInversAplicDir[1] += $oDotacaoSaldo->liquidado_acumulado;
    }     
	
	// 2.2.2.2 --	Aplicações Diretas Op. Intra 

		if (substr($oDotacaoSaldo->o58_elemento,0,5) == '34591'){
       $aCredEspInversAplicDirIntra[0]+= $oDotacaoSaldo->dot_ini + $oDotacaoSaldo->suplementado_acumulado - $oDotacaoSaldo->reduzido_acumulado;
       $aCredEspInversAplicDirIntra[1]+= $oDotacaoSaldo->liquidado_acumulado;
    }     

	}
	
}

$iTotCredOrcPesEncFix	= $aCredOrcPessoalEncargosAplicDir[0] + $aCredOrcPessoalEncargosAplicDirIntra[0];
$iTotCredOrcPesEncExe = $aCredOrcPessoalEncargosAplicDir[1] + $aCredOrcPessoalEncargosAplicDirIntra[1];
$iTotCredOrcOutrasFix = $aCredOrcOutrasTransf[0] + $aCredOrcOutrasAplicDir[0] + $aCredOrcOutrasAplicDirIntra[0]; 
$iTotCredOrcOutrasExe = $aCredOrcOutrasTransf[1] + $aCredOrcOutrasAplicDir[1] + $aCredOrcOutrasAplicDirIntra[1]; 
$iTotCredOrcInvestFix = $aCredOrcInvestAplicDir[0];
$iTotCredOrcInvestExe = $aCredOrcInvestAplicDir[1];
$iTotCredOrcInversFix = $aCredOrcInversAplicDir[0] + $aCredOrcInversAplicDirIntra[0];
$iTotCredOrcInversExe = $aCredOrcInversAplicDir[1] + $aCredOrcInversAplicDirIntra[1];

$iTotCredEspPesEncFix = $aCredEspPessoalEncargosAplicDir[0] + $aCredEspPessoalEncargosAplicDirIntra[0];
$iTotCredEspPesEncExe = $aCredEspPessoalEncargosAplicDir[1] + $aCredEspPessoalEncargosAplicDirIntra[1];
$iTotCredEspOutrasFix = $aCredEspOutrasTransf[0] + $aCredEspOutrasAplicDir[0] + $aCredEspOutrasAplicDirIntra[0]; 
$iTotCredEspOutrasExe = $aCredEspOutrasTransf[1] + $aCredEspOutrasAplicDir[1] + $aCredEspOutrasAplicDirIntra[1]; 
$iTotCredEspInvestFix = $aCredEspInvestAplicDir[0];
$iTotCredEspInvestExe = $aCredEspInvestAplicDir[1];
$iTotCredEspInversFix = $aCredEspInversAplicDir[0] + $aCredEspInversAplicDirIntra[0];
$iTotCredEspInversExe = $aCredEspInversAplicDir[1] + $aCredEspInversAplicDirIntra[1];

$iTotCredOrcDespCorrFix = $iTotCredOrcPesEncFix + $iTotCredOrcOutrasFix;
$iTotCredOrcDespCorrExe = $iTotCredOrcPesEncExe + $iTotCredOrcOutrasExe;
$iTotCredOrcDespCapFix  = $iTotCredOrcInvestFix + $iTotCredOrcInversFix;
$iTotCredOrcDespCapExe  = $iTotCredOrcInvestExe + $iTotCredOrcInversExe;

$iTotCredEspDespCorrFix = $iTotCredEspPesEncFix + $iTotCredEspOutrasFix;
$iTotCredEspDespCorrExe = $iTotCredEspPesEncExe + $iTotCredEspOutrasExe;
$iTotCredEspDespCapFix  = $iTotCredEspInvestFix + $iTotCredEspInversFix;
$iTotCredEspDespCapExe  = $iTotCredEspInvestExe + $iTotCredEspInversExe;

$iTotCredOrcFix = $iTotCredOrcDespCorrFix + $iTotCredOrcDespCapFix;
$iTotCredOrcExe = $iTotCredOrcDespCorrExe + $iTotCredOrcDespCapExe;
$iTotCredEspFix = $iTotCredEspDespCorrFix + $iTotCredEspDespCapFix;
$iTotCredEspExe = $iTotCredEspDespCorrExe + $iTotCredEspDespCapExe;

$somatorio_despesa_ini	= $iTotCredOrcFix + $iTotCredEspFix;
$somatorio_despesa_exec = $iTotCredOrcExe + $iTotCredEspExe;

//------------------//-------------------//---------------------

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$alt            = 4;
$pagina         = 1;
$maislinha      = 0;

$pdf->addpage("L");
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,"ART. 102 DA LEI 4.320/1964.",'T',1,"L",0);
$pdf->setfont('arial','b',8);
$pdf->cell(135,$alt,"RECEITAS","T",0,"C",1);
$pdf->cell(143,$alt,"DESPESAS","T",1,"C",1);

// RECEITA E DESPERA ORÇAMENTARIA
$pdf->cell(63,$alt,"TÍTULO"		,"TB",0,"C",1);
$pdf->cell(25,$alt,"PREVISÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"EXECUÇÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"DIFERENÇA","TB",0,"C",1);


$pdf->cell(65,$alt,"TÍTULO"	  ,"TB",0,"C",1);
$pdf->cell(25,$alt,"FIXAÇÃO"  ,"TB",0,"C",1);
$pdf->cell(25,$alt,"EXECUÇÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"DIFERENÇA","TB",0,"C",1);

$pdf->line(73  ,$pdf->getY(),73  ,190);
$pdf->line(98  ,$pdf->getY(),98  ,190);
$pdf->line(123 ,$pdf->getY(),123 ,190);
$pdf->line(148 ,($pdf->getY()- 4),148 ,190);
$pdf->line(213 ,$pdf->getY(),213 ,190);
$pdf->line(238 ,$pdf->getY(),238 ,190);
$pdf->line(263 ,$pdf->getY(),263 ,190);
$pdf->line(10,190,287,190);
$pdf->ln();

$pdf->setfont('arial','b',8);
$pdf->ln(2);
$pdf->cell(63,$alt,"RECEITAS CORRENTES"																,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCorrPrev,'f')									,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCorrExe,'f')									  ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCorrPrev - $iTotRecCorrExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);

$pdf->ln();

$pdf->cell(5,$alt,""																																	 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Contribuições"																										 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[0],'f')												   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[1],'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_contribuicoes[0]-$receita_contribuicoes[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->cell(5,$alt,""																															 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Patrimonial"																									 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[0],'f')												 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[1],'f')												 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($receita_patrimonial[0]-$receita_patrimonial[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->cell(5,$alt,""																																					 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Outras"																																		 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[0],'f')															 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[1],'f')															 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_correntes[0]-$outras_receitas_correntes[1],'f'),"0",0,"R",0);
$pdf->ln();

$pdf->ln();
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"RECEITAS DE CAPITAL"						                 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCapPrev,'f')									 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCapExe ,'f') 								 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotRecCapPrev - $iTotRecCapExe,'f') ,"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->ln();
$pdf->cell(5,$alt,""																										 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Alienação de Bens"																	 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[0],'f')								   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[1],'f')									 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($alienacao_bens[0]-$alienacao_bens[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,""																																			 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Amortização de Empréstimos"																						 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[0],'f')														 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[1],'f')													   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($amortizacao_emprestimos[0]-$amortizacao_emprestimos[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,""																																			 ,"0",0,"L",0);
$pdf->cell(58,$alt,"Outras Receitas de Capital"																						 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[0],'f')														 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[1],'f')														 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($outras_receitas_capital[0]-$outras_receitas_capital[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->Ln();
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"OPER. INTRA-ORÇAMENTÁRIAS"          								 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCorrPrev,'f')									   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCorrExe,'f')										 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCorrPrev - $iTotOperCorrExe,'f') ,"0",0,"R",0);
$pdf->Ln();
$pdf->cell(63,$alt,"CORRENTES"																					 ,"0",0,"L",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Contribuições ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrContrib[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrContrib[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrContrib[0]-$aOperCorrContrib[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Patrimonial ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrPatrim[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrPatrim[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrPatrim[0]-$aOperCorrPatrim[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Outras ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrOutras[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrOutras[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCorrOutras[0]-$aOperCorrOutras[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->Ln();
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"OPER. INTRA-ORÇAMENTÁRIAS"		  									 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCapPrev,'f')									 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCapExe,'f')										 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotOperCapPrev - $iTotOperCapExe,'f') ,"0",0,"R",0);
$pdf->Ln();
$pdf->cell(63,$alt,"DE CAPITAL"																				 ,"0",0,"L",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Alienação de Bens ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCapAlienacao[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapAlienacao[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapAlienacao[0]-$aOperCapAlienacao[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Amortização de Empréstimos","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCapEmprestimo[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapEmprestimo[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapEmprestimo[0]-$aOperCapEmprestimo[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->cell(5,$alt,"","0",0,"L",0);
$pdf->cell(58,$alt,"Outras ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aOperCapOutras[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapOutras[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aOperCapOutras[0]-$aOperCapOutras[1],'f'),"0",0,"R",0);
$pdf->Ln();

$iGetX = $pdf->getX();
$iGetY = $pdf->getY();
$xDesp = 150;

$pdf->setY(49);
$pdf->setX($xDesp);
$pdf->setfont('arial','iu',8);
$pdf->cell(63,$alt,"CREDITOS ORÇAMENTARIOS","0",0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcFix - $iTotCredOrcExe,'f'),"0",1,"R",0);
$pdf->setX($xDesp);
$pdf->setfont('arial','iu',8);
$pdf->cell(63,$alt," E SUPLEMENTARES","0",1,"L",0);
$pdf->Ln();
$pdf->setX($xDesp);
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"DESPESAS CORRENTES","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCorrFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCorrExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCorrFix - $iTotCredOrcDespCorrExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->setfont('arial','b',8);
$pdf->cell(58,$alt,"PESSOAL E ENCARGOS SOCIAIS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcPesEncFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcPesEncExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcPesEncFix - $iTotCredOrcPesEncExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDir[0]-$aCredOrcPessoalEncargosAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDirIntra[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcPessoalEncargosAplicDirIntra[0]-$aCredOrcPessoalEncargosAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->setfont('arial','b',8);
$pdf->cell(58,$alt,"OUTRAS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcOutrasFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcOutrasExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcOutrasFix - $iTotCredOrcOutrasExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Transf. a União","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasTransf[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasTransf[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasTransf[0]-$aCredOrcOutrasTransf[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDir[0]-$aCredOrcOutrasAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra "																									 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDirIntra[0],'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDirIntra[1],'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcOutrasAplicDirIntra[0]-$aCredOrcOutrasAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp);
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"DESPESAS DE CAPITAL"																								 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCapFix,'f')														   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCapExe,'f')														   ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcDespCapFix - $iTotCredOrcDespCapExe,'f')			 ,"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->cell(58,$alt,"INVESTIMENTOS "																											 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInvestFix,'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInvestExe,'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInvestFix - $iTotCredOrcInvestExe,'f')			   ,"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas"																											 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInvestAplicDir[0],'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInvestAplicDir[1],'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInvestAplicDir[0]-$aCredOrcInvestAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->setfont('arial','b',8);
$pdf->cell(58,$alt,"INVERSÕES FINANCEIRAS "																							 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInversFix,'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInversExe,'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredOrcInversFix - $iTotCredOrcInversExe,'f')				 ,"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas"																											 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDir[0],'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDir[1],'f')											     ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDir[0]-$aCredOrcInversAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra "																									 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDirIntra[0],'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDirIntra[1],'f')																 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredOrcInversAplicDirIntra[0]-$aCredOrcInversAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp);
$pdf->setfont('arial','iu',8);
$pdf->cell(63,$alt,"CRÉDITOS ESPECIAIS"																,"0",0,"L",0);
$pdf->setfont('arial','',8);
$pdf->cell(25,$alt,db_formatar($iTotCredEspFix,'f')										,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspExe,'f')									  ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspFix - $iTotCredEspExe,'f') ,"0",1,"R",0);
$pdf->Ln();
$pdf->setX($xDesp);
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"DESPESAS CORRENTES"																							 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCorrFix,'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCorrExe,'f')													 ,"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCorrFix - $iTotCredEspDespCorrExe,'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->cell(58,$alt,"PESSOAL E ENCARGOS SOCIAIS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspPesEncFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspPesEncExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspPesEncFix - $iTotCredEspPesEncExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDir[0]-$aCredEspPessoalEncargosAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDirIntra[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspPessoalEncargosAplicDirIntra[0]-$aCredEspPessoalEncargosAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->setfont('arial','b',8);
$pdf->cell(58,$alt,"OUTRAS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspOutrasFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspOutrasExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspOutrasFix - $iTotCredEspOutrasExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Transf. a União","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasTransf[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasTransf[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasTransf[0]-$aCredEspOutrasTransf[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDir[0]-$aCredEspOutrasAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDirIntra[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspOutrasAplicDirIntra[0]-$aCredEspOutrasAplicDirIntra[1],'f'),"0",0,"R",0);

//$pdf->setXY($iGetX,$iGetY);

$pdf->addpage("L");
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,"ART. 102 DA LEI 4.320/1964.",'T',1,"L",0);
$pdf->setfont('arial','b',8);
$pdf->cell(135,$alt,"RECEITAS","T",0,"C",1);
$pdf->cell(143,$alt,"DESPESAS","T",1,"C",1);

$pdf->cell(63,$alt,"TÍTULO"		,"TB",0,"C",1);
$pdf->cell(25,$alt,"PREVISÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"EXECUÇÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"DIFERENÇA","TB",0,"C",1);

$pdf->cell(65,$alt,"TÍTULO"	  ,"TB",0,"C",1);
$pdf->cell(25,$alt,"FIXAÇÃO"  ,"TB",0,"C",1);
$pdf->cell(25,$alt,"EXECUÇÃO" ,"TB",0,"C",1);
$pdf->cell(25,$alt,"DIFERENÇA","TB",0,"C",1);


$pdf->line(73  ,$pdf->getY(),73  ,85);
$pdf->line(98  ,$pdf->getY(),98  ,85);
$pdf->line(123 ,$pdf->getY(),123 ,85);
$pdf->line(148 ,($pdf->getY()- 4),148 ,85);
$pdf->line(213 ,$pdf->getY(),213 ,85);
$pdf->line(238 ,$pdf->getY(),238 ,85);
$pdf->line(263 ,$pdf->getY(),263 ,85);
$pdf->Ln();


$pdf->Ln();
$pdf->setX($xDesp);
$pdf->cell(63,$alt,"DESPESAS DE CAPITAL","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCapFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCapExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspDespCapFix - $iTotCredEspDespCapExe,'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->cell(58,$alt,"INVESTIMENTOS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInvestFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInvestExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInvestFix - $iTotCredEspInvestExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInvestAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInvestAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInvestAplicDir[0]-$aCredEspInvestAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->Ln();
$pdf->setX($xDesp+5);
$pdf->setfont('arial','b',8);
$pdf->cell(58,$alt,"INVERSÕES FINANCEIRAS ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInversFix,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInversExe,'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($iTotCredEspInversFix - $iTotCredEspInversExe,'f'),"0",0,"R",0);
$pdf->setfont('arial','',8);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDir[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDir[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDir[0]-$aCredEspInversAplicDir[1],'f'),"0",0,"R",0);
$pdf->Ln();
$pdf->setX($xDesp+10);
$pdf->cell(53,$alt,"Aplic. Diretas - Op. Intra ","0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDirIntra[0],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->cell(25,$alt,db_formatar($aCredEspInversAplicDirIntra[0]-$aCredEspInversAplicDirIntra[1],'f'),"0",0,"R",0);
$pdf->Ln();

$pdf->ln();
$pdf->setfont('arial','b',8);
$pdf->cell(63,$alt,"SOMA" 																												,"TB",0,"L",1);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini,'f')												,1	 ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_exec,'f')												,1	 ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini-$somatorio_receita_exec,'f'),1	 ,0,"R",1);
$pdf->cell(65,$alt,"SOMA"     																									  ,1	 ,0,"L",1);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini,'f')												,1	 ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_exec,'f')												,1	 ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini-$somatorio_despesa_exec,'f'),"TBL",0,"R",1);
$pdf->ln();

// calculo das diferenças
$soma_rec =$somatorio_receita_ini-$somatorio_receita_exec;
$soma_desp=$somatorio_despesa_ini-$somatorio_despesa_exec;

$pdf->cell(63,$alt,"DEFICITS"																																																					 ,"TB",0,"L",1);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_ini<$somatorio_despesa_ini?($somatorio_despesa_ini-$somatorio_receita_ini):0,'f')		 ,1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_receita_exec<$somatorio_despesa_exec?($somatorio_despesa_exec-$somatorio_receita_exec):0,'f'),1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($soma_rec<$soma_desp?($soma_desp-$soma_rec):0,'f')																											 ,1	  ,0,"R",1);
$pdf->cell(65,$alt,"SUPERAVITS"																																																				 ,1	  ,0,"L",1);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_ini<$somatorio_receita_ini?($somatorio_receita_ini-$somatorio_despesa_ini):0,'f')		 ,1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($somatorio_despesa_exec<$somatorio_receita_exec?($somatorio_receita_exec-$somatorio_despesa_exec):0,'f'),1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($soma_desp<$soma_rec?($soma_rec-$soma_desp):0,'f')																											 ,"TBL",0,"R",1);
$pdf->ln();

$tot_rec_ini = $somatorio_receita_ini+($somatorio_receita_ini<$somatorio_despesa_ini?($somatorio_despesa_ini-$somatorio_receita_ini):0);
$tot_rec_exe = $somatorio_receita_exec+($somatorio_receita_exec<$somatorio_despesa_exec?($somatorio_despesa_exec-$somatorio_receita_exec):0);
$tot_rec_dif = ($somatorio_receita_ini-$somatorio_receita_exec)+($soma_rec<$soma_desp?($soma_desp-$soma_rec):0);

$tot_desp_ini = $somatorio_despesa_ini+($somatorio_despesa_ini<$somatorio_receita_ini?($somatorio_receita_ini-$somatorio_despesa_ini):0);
$tot_desp_exe = $somatorio_despesa_exec+($somatorio_despesa_exec<$somatorio_receita_exec?($somatorio_receita_exec-$somatorio_despesa_exec):0);
$tot_desp_dif = ($somatorio_despesa_ini-$somatorio_despesa_exec)+($soma_desp<$soma_rec?($soma_rec-$soma_desp):0);

$pdf->cell(63,$alt,"TOTAL"											 ,"TB",0,"L",1);
$pdf->cell(25,$alt,db_formatar($tot_rec_ini,'f') ,1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($tot_rec_exe,'f') ,1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($tot_rec_dif,'f') ,1	  ,0,"R",1);
$pdf->cell(65,$alt,"TOTAL"											 ,1	  ,0,"L",1);
$pdf->cell(25,$alt,db_formatar($tot_desp_ini,'f'),1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($tot_desp_exe,'f'),1	  ,0,"R",1);
$pdf->cell(25,$alt,db_formatar($tot_desp_dif,'f'),"TBL",0,"R",1);
$pdf->ln();

$pdf->Ln();
$pdf->setfont('arial','',5);

notasExplicativas($pdf,53,($mes>9?$mes:"0".$mes),190);

$pdf->Ln(14);
$pdf->setfont('arial','',8);

$sAssPref     = $classinatura->assinatura(1000,"","0");
$sAssPrefFunc = $classinatura->assinatura(1000,"","1");
$sAssPrefCPF  = $classinatura->assinatura(1000,"","2");

$sAssCont     = $classinatura->assinatura(1005,"","0");
$sAssContFunc = $classinatura->assinatura(1005,"","1");
$sAssContCPF  = $classinatura->assinatura(1005,"","2");

$sAssSecretFazenda	= $classinatura->assinatura(1002,"","0");
$sAssSecretFazendaFunc  = $classinatura->assinatura(1002,"","1");
$sAssSecretFazendaCPF   = $classinatura->assinatura(1002,"","2");

$pdf->cell(100,$alt,$sAssPref		 ,0,0,"C",0);
$pdf->cell(100,$alt,$sAssCont		 ,0,0,"C",0);
$pdf->cell(100,$alt,$sAssSecretFazenda   ,0,1,"C",0);

$pdf->cell(100,$alt,$sAssPrefFunc,0,0,"C",0);
$pdf->cell(100,$alt,$sAssContFunc,0,0,"C",0);
$pdf->cell(100,$alt,$sAssSecretFazendaFunc,0,1,"C",0);

$pdf->cell(100,$alt,$sAssPrefCPF,0,0,"C",0);
$pdf->cell(100,$alt,$sAssContCPF,0,0,"C",0);
$pdf->cell(100,$alt,$sAssSecretFazendaCPF,0,1,"C",0);

$pdf->Output();
   
?>
