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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/db_orcparamrelnota_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet	 = db_utils::postMemory($_GET);

$orcparamrel       = new cl_orcparamrel;
$cldb_config       = new cl_db_config;
$clorcparamrelnota = new cl_orcparamrelnota;
$classinatura      = new cl_assinatura;




$iAnousu 	  = db_getsession("DB_anousu");
$iInstit 	  = db_getsession("DB_instit");

$sListaInstit = str_replace("-",",",$oGet->db_selinstit);

$sDataIni 	  = "$iAnousu-01-01";
$sDataFin	  = "$iAnousu-12-31";



// Busca valores dos parâmetros configurados

$aRecOperCredito 				= $orcparamrel->sql_parametro('25','1',"f",$sListaInstit,$iAnousu);

$aDespCapital['estrut']     	= $orcparamrel->sql_parametro('25','2',"f",$sListaInstit,$iAnousu);
$aDespCapital['nivel']     	    = $orcparamrel->sql_nivel('25','2');

$aIncentivoFiscal['estrut']     = $orcparamrel->sql_parametro('25','3',"f",$sListaInstit,$iAnousu);
$aIncentivoFiscal['nivel']      = $orcparamrel->sql_nivel('25','3');

$aIncentivoFiscalInst['estrut'] = $orcparamrel->sql_parametro('25','4',"f",$sListaInstit,$iAnousu);
$aIncentivoFiscalInst['nivel']  = $orcparamrel->sql_nivel('25','4');




// Receita

$sWhereReceita 	   = " o70_instit in ({$sListaInstit})";
$rsReceita  	   = db_receitasaldo(11,1,3,true,$sWhereReceita,$iAnousu,$sDataIni,$sDataFin);
$iNroLinhasReceita = pg_num_rows($rsReceita);



// Despesa

$sWhereDespesa 	   = " w.o58_instit in ({$sListaInstit}) ";
$rsDespesa    	   = db_dotacaosaldo(8,2,3,true,$sWhereDespesa,$iAnousu,$sDataIni,$sDataFin);
$iNroLinhasDespesa = pg_num_rows($rsDespesa);




$aRecOperCreditoVal["PrevAtualizada"]			= 0;
$aRecOperCreditoVal["ReceitaAteBim"] 			= 0;
$aRecOperCreditoVal["SaldoRealizar"] 			= 0;

$aDespCapitalVal['DotacAtu'] 					= 0;
$aDespCapitalVal['DespLiqAteBim'] 				= 0;
$aDespCapitalVal['InscrRestPagNaoProc'] 		= 0;
$aDespCapitalVal['SaldoExecutar']	    		= 0;

$aIncentivoFiscalVal['DotacAtu'] 				= 0;
$aIncentivoFiscalVal['DespLiqAteBim'] 			= 0;
$aIncentivoFiscalVal['InscrRestPagNaoProc'] 	= 0;
$aIncentivoFiscalVal['SaldoExecutar']       	= 0;

$aIncentivoFiscalInstVal['DotacAtu'] 			= 0;
$aIncentivoFiscalInstVal['DespLiqAteBim'] 		= 0;
$aIncentivoFiscalInstVal['InscrRestPagNaoProc'] = 0;
$aIncentivoFiscalInstVal['SaldoExecutar']       = 0;


//*************************************** RECEITAS ********************************************//



for ($i=0; $i < $iNroLinhasReceita; $i++) {

  $oReceita    = db_utils::fieldsMemory($rsReceita,$i);

  $sEstrutural = $oReceita->o57_fonte;

  // Calcula Receita de Operações de Crédito

  if (in_array($sEstrutural,$aRecOperCredito)) {

    $aRecOperCreditoVal["PrevAtualizada"] += $oReceita->saldo_inicial_prevadic;
    $aRecOperCreditoVal["ReceitaAteBim"]  += $oReceita->saldo_arrecadado_acumulado;
    $aRecOperCreditoVal["SaldoRealizar"]  += $oReceita->saldo_a_arrecadar;

  }

}


//*************************************** DESPESAS ********************************************//




for ($i=0; $i < $iNroLinhasDespesa; $i++) {

  $oDespesas = db_utils::fieldsMemory($rsDespesa,$i);


  $sEstrutural = $oDespesas->o58_elemento."00";


  // Calcula Despesas de Capital

  $iNivel       	 = $aDespCapital['nivel'];
  $sEstruturalNivel  = substr($sEstrutural,0,$iNivel);
  $sEstruturalNivel  = str_pad($sEstruturalNivel, 15, "0", STR_PAD_RIGHT);

  if (in_array($sEstruturalNivel,$aDespCapital['estrut'])) {

	$aDespCapitalVal['DotacAtu'] 			+= $oDespesas->dot_ini + ($oDespesas->suplementado_acumulado - $oDespesas->reduzido_acumulado);
	$aDespCapitalVal['DespLiqAteBim'] 		+= $oDespesas->liquidado_acumulado;
	$aDespCapitalVal['InscrRestPagNaoProc'] += $oDespesas->empenhado_acumulado - $oDespesas->anulado_acumulado - $oDespesas->liquidado_acumulado;

  }


  // Calcula  Incentivos Fiscais a Contribuinte

  $iNivel 	         = $aIncentivoFiscal['nivel'];
  $sEstruturalNivel  = substr($sEstrutural,0,$iNivel);
  $sEstruturalNivel  = str_pad($sEstruturalNivel, 15, "0", STR_PAD_RIGHT);

  if (in_array($sEstruturalNivel,$aIncentivoFiscal['estrut'])) {

	$aIncentivoFiscalVal['DotacAtu'] 			+= $oDespesas->dot_ini + ($oDespesas->suplementado_acumulado - $oDespesas->reduzido_acumulado);
	$aIncentivoFiscalVal['DespLiqAteBim'] 		+= $oDespesas->liquidado_acumulado;
	$aIncentivoFiscalVal['InscrRestPagNaoProc'] += $oDespesas->empenhado_acumulado - $oDespesas->anulado_acumulado - $oDespesas->liquidado_acumulado;

  }


  // Calcula  Incentivos Fiscais a Contribuinte por Instit. Financeiras

  $iNivel       	= $aIncentivoFiscalInst['nivel'];
  $sEstruturalNivel = substr($sEstrutural,0,$iNivel);
  $sEstruturalNivel = str_pad($sEstruturalNivel, 15, "0", STR_PAD_RIGHT);

  if (in_array($sEstruturalNivel,$aIncentivoFiscalInst['estrut'])) {

	$aIncentivoFiscalInstVal['DotacAtu'] 			+= $oDespesas->dot_ini + ($oDespesas->suplementado_acumulado - $oDespesas->reduzido_acumulado);
	$aIncentivoFiscalInstVal['DespLiqAteBim'] 		+= $oDespesas->liquidado_acumulado;
	$aIncentivoFiscalInstVal['InscrRestPagNaoProc'] += $oDespesas->empenhado_acumulado - $oDespesas->anulado_acumulado - $oDespesas->liquidado_acumulado;

  }


}

// Calcula Saldo a Executar das Despesas de Capital, Incentivos Fiscais a Contribuintes e Incentivos Fiscais a Contribuinte por Instit.

$aDespCapitalVal['SaldoExecutar']	      	  = $aDespCapitalVal['DotacAtu'] 		 - ($aDespCapitalVal['DespLiqAteBim'] + $aDespCapitalVal['InscrRestPagNaoProc']);
$aIncentivoFiscalVal['SaldoExecutar']     	  = $aIncentivoFiscalVal['DotacAtu']     - ($aIncentivoFiscalVal['DespLiqAteBim'] + $aIncentivoFiscalVal['InscrRestPagNaoProc']);
$aIncentivoFiscalInstVal['SaldoExecutar']     = $aIncentivoFiscalInstVal['DotacAtu'] - ($aIncentivoFiscalInstVal['DespLiqAteBim'] + $aIncentivoFiscalInstVal['InscrRestPagNaoProc']);



// Calcula Despesas de Capital Líquida (II)

$aTotDespesaCapitalLiq['DotacAtu']			  = $aDespCapitalVal['DotacAtu']			- $aIncentivoFiscalVal['DotacAtu'] 			  - $aIncentivoFiscalInstVal['DotacAtu'];
$aTotDespesaCapitalLiq['DespLiqAteBim']		  = $aDespCapitalVal['DespLiqAteBim']		- $aIncentivoFiscalVal['DespLiqAteBim'] 	  - $aIncentivoFiscalInstVal['DespLiqAteBim'];
$aTotDespesaCapitalLiq['InscrRestPagNaoProc'] = $aDespCapitalVal['InscrRestPagNaoProc'] - $aIncentivoFiscalVal['InscrRestPagNaoProc'] - $aIncentivoFiscalInstVal['InscrRestPagNaoProc'];
$aTotDespesaCapitalLiq['SaldoExecutar']	  	  = $aDespCapitalVal['SaldoExecutar'] 		- $aIncentivoFiscalVal['SaldoExecutar']		  - $aIncentivoFiscalInstVal['SaldoExecutar'];



// Calcula Resultado para apuração da regra  de ouro

$aTotRegraOuro['Coluna1'] = $aRecOperCreditoVal['PrevAtualizada'] - $aTotDespesaCapitalLiq["DotacAtu"];
$aTotRegraOuro['Coluna2'] = $aRecOperCreditoVal['ReceitaAteBim']  - ( $aTotDespesaCapitalLiq["DespLiqAteBim"] + $aTotDespesaCapitalLiq["InscrRestPagNaoProc"] );
$aTotRegraOuro['Coluna3'] = $aRecOperCreditoVal['SaldoRealizar']  - $aTotDespesaCapitalLiq["SaldoExecutar"];




$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit')));
$oConfig  = db_utils::fieldsMemory($rsConfig,0);


$head2 = "MUNICÍPIO DE ".$oConfig->munic;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "DEMONSTRATIVO DAS RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL";
$head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head6 = "JANEIRO A DEZEMBRO DE ".db_getsession('DB_anousu');

if (!isset($lNaoGeraPDF)) {

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->addpage();

  $iAlt  = 4;
  $iFont = 6;


  $pdf->setfont('arial','',$iFont);

  $pdf->cell(170,$iAlt,"RREO - ANEXO XI (LRF, art.53, § 1º, inciso I)",'0',0,"L",0);
  $pdf->cell(20 ,$iAlt,"R$ 1,00"										,'0',1,"R",0);



  // Cabeçalho Receita

  $pdf->cell(60,$iAlt,""				      ,'TR',0,"C",0);
  $pdf->cell(35,$iAlt,"PREVISÃO"			  ,'TR',0,"C",0);
  $pdf->cell(60,$iAlt,"RECEITAS REALIZADAS" ,'TR',0,"C",0);
  $pdf->cell(35,$iAlt,"SALDO A REALIZAR"	  ,'T' ,1,"C",0);

  $pdf->cell(60,$iAlt,"RECEITAS"		  	  ,'BR',0,"C",0);
  $pdf->cell(35,$iAlt,"ATUALIZADA (a)"      ,'BR',0,"C",0);
  $pdf->cell(60,$iAlt,"Até o Bimestre (b)"  ,'BR',0,"C",0);
  $pdf->cell(35,$iAlt,"(c) = (a-b)"		  ,'B' ,1,"C",0);


  $pdf->cell(60,$iAlt,"RECEITAS DE OPERAÇÃO DE CRÉDITO (I)"				  ,'BR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aRecOperCreditoVal["PrevAtualizada"],"f"),'BR',0,"R",0);
  $pdf->cell(60,$iAlt,db_formatar($aRecOperCreditoVal["ReceitaAteBim"],"f") ,'BR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aRecOperCreditoVal["SaldoRealizar"],"f") ,'B' ,1,"R",0);






  // Cabeçalho Despesa

  $pdf->Ln();

  $pdf->MultiCell(60,($iAlt*6),"DESPESAS","TBR","C");

  $pdf->SetXY(70 ,($pdf->GetY()-($iAlt*6)));
  $pdf->MultiCell(35,($iAlt*6),"DOTAÇÃO ATUALIZADA (d)","TBR","C");

  $pdf->SetXY(105,($pdf->GetY()-($iAlt*6)));
  $pdf->MultiCell(60,($iAlt),"DESPESAS EXECUTADAS","TR","C");

  $pdf->SetX(105);
  $pdf->MultiCell(60,($iAlt),"Até o Bimestre","BR","C");

  $pdf->SetX(105,($pdf->GetY()-($iAlt*2)));
  $pdf->MultiCell(30,($iAlt*4),"LIQUIDADAS (e)","BR","C");

  $pdf->SetXY(135,($pdf->GetY()-($iAlt*4)));
  $pdf->MultiCell(30,($iAlt+1.3),"INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS (f)","LBR","C");

  $pdf->SetXY(165,($pdf->GetY()-($iAlt*6)));
  $pdf->MultiCell(35,($iAlt*3),"SALDO A EXECUTAR (g)=(d-(e+f))","TB","C");



  $pdf->cell(60,$iAlt,"DESPESAS DE CAPITAL"							  		 		,'BR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aDespCapitalVal["DotacAtu"],"f")			 		,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aDespCapitalVal["DespLiqAteBim"],"f")	  	 		,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aDespCapitalVal["InscrRestPagNaoProc"],"f") 		,'BR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aDespCapitalVal["SaldoExecutar"],"f") 		 		,'B' ,1,"R",0);

  $pdf->cell(60,$iAlt," (-)Incentivos Fiscais a Contribuinte"			  			 	,'BR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aIncentivoFiscalVal["DotacAtu"],"f")			 	,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aIncentivoFiscalVal["DespLiqAteBim"],"f")	  	 	,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aIncentivoFiscalVal["InscrRestPagNaoProc"],"f") 	,'BR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aIncentivoFiscalVal["SaldoExecutar"],"f") 		 	,'B' ,1,"R",0);

  $pdf->cell(60,$iAlt," (-)Incentivos Fiscais a Contribuinte por Instit. Financeiras" ,'BR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aIncentivoFiscalInstVal["DotacAtu"],"f")			,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aIncentivoFiscalInstVal["DespLiqAteBim"],"f")	  	,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aIncentivoFiscalInstVal["InscrRestPagNaoProc"],"f"),'BR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aIncentivoFiscalInstVal["SaldoExecutar"],"f") 		,'B' ,1,"R",0);


  $pdf->cell(60,$iAlt,"DESPESAS DE CAPITAL LÍQUIDA (II)" 							    ,'BR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aTotDespesaCapitalLiq["DotacAtu"],"f")			    ,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aTotDespesaCapitalLiq["DespLiqAteBim"],"f")	    ,'BR',0,"R",0);
  $pdf->cell(30,$iAlt,db_formatar($aTotDespesaCapitalLiq["InscrRestPagNaoProc"],"f")  ,'BR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aTotDespesaCapitalLiq["SaldoExecutar"],"f") 	    ,'B' ,1,"R",0);



  $pdf->Ln();

  $pdf->cell(60,$iAlt,"RESULTADO PARA APURAÇÃO DA REGRA DE OURO (I-II)" ,'TBR',0,"L",0);
  $pdf->cell(35,$iAlt,db_formatar($aTotRegraOuro["Coluna1"],"f")		  ,'TBR',0,"R",0);
  $pdf->cell(60,$iAlt,db_formatar($aTotRegraOuro["Coluna2"],"f") 		  ,'TBR',0,"R",0);
  $pdf->cell(35,$iAlt,db_formatar($aTotRegraOuro["Coluna3"],"f")  	  ,'TB' ,1,"R",0);



  // Verifica se foi incluido Notas explicativas em algum dos períodos: 6º Bimestre, 3º Quadrimestre, 2º Semestre

  $sWhereNota  = " orcparamrelnota.o42_codparrel = 25 ";
  $sWhereNota .= " and o42_anousu = {$iAnousu} 		";
  $sWhereNota .= " and o42_instit = {$iInstit}		";
  $sWhereNota .= " and o42_periodo in ('6B','3Q','2S')";

  $rsNotas 	   = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query_file(null,null,null,null,"o42_periodo",null,$sWhereNota));
  $iNroLinhaNota = $clorcparamrelnota->numrows;

  
  if ($iNroLinhaNota > 0) {
  	$oNota = db_utils::fieldsMemory($rsNotas,0);
  	notasExplicativas($pdf,25,$oNota->o42_periodo,190);
  } else {
  	notasExplicativas($pdf,25,'1B',190);
  }



  // Inlcui assinaturas

  $pdf->Ln($iAlt);

  assinaturas($pdf,$classinatura,'LRF');

  $pdf->Output();
}
?>
