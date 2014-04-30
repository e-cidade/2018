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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_empempenho_classe.php");

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($_GET);

$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;

$iUsuInstit  = db_getsession('DB_instit');
$rsUsuInstit = db_query(" select codigo from db_config where ( db21_tipoinstit in (5,6) or prefeitura is true ) and codigo = ".$iUsuInstit);
$iNumRowsUsuInstit = pg_num_rows($rsUsuInstit);

if($iNumRowsUsuInstit == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=O usuário deve ser da instituição RPPS ou Prefeitura para visualizar o relatório');
}

$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");
$oInstit  = db_utils::fieldsMemory($rsInstit,0);

$head2 =  $oInstit->nomeinst;
$head3 = "DEMONSTRAÇÃO DAS VARIAÇÕES PATRIMONIAIS DO  REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";

//BALANÇO ORÇAMENTÁRIO DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($mes == 1){
  $head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{
  $head4 = "JANEIRO A ".strtoupper(db_mes($mes))." DE ".db_getsession("DB_anousu");
}
$anousu	 = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.'01'.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

//-------------------------------------------------------------------------------------------------------------------------------------------//
$aVariacoesPatrimoniais = array();
$aVariacoesPatrimoniais = db_varPatrimoniaisRpps($anousu,$dataini,$datafin,$oInstit->codigo);

// echo "<pre>"; 
// print_r($aVariacoesPatrimoniais);
// echo "</pre>";
// exit;

//------------------------------------------------------------------------------------------------------------------------------------------//

//
// Layout do relatorio
//
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

$alt = 4;

$pdf->addpage("P");
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,"ART. 104 DA LEI 4.320/1964.",'T',1,"L",0);
$pdf->setfont('arial','b',8);

$pdf->cell(70,$alt,"VARIAÇÕES ATIVAS"    ,"BT",0,"C",1);
$pdf->cell(25,$alt,"R$"                  ,"1" ,0,"C",1);
$pdf->cell(70,$alt,"VARIAÇÕES PASSIVAS"  ,"1" ,0,"C",1);
$pdf->cell(25,$alt,"R$"                  ,"BTL",1,"C",1);

$pdf->line(80, $pdf->getY(),80  ,185);
$pdf->line(105,$pdf->getY(),105 ,185);
$pdf->line(175,$pdf->getY(),175 ,185);

$pdf->setfont('arial','b',8);
$pdf->ln();
$pdf->cell(70,$alt,"ORÇAMENTÁRIA"    	,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['TotalOrcamentariaAtiva'],'f')	,"0",0,"R",0);

$pdf->cell(70,$alt,"ORÇAMENTÁRIA"    	,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['TotalOrcamentariaPassiva'],'f')	,"0",1,"R",0);
$pdf->ln();

$pdf->setfont('arial','',8);

$pdf->cell(5,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(65,$alt,"RECEITAS"                                                                ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['Receitas'],'f')             ,"0",0,"R",0);

$pdf->cell(5,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(65,$alt,"DESPESAS"                                                                ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['Despesas'],'f')           ,"0",1,"R",0);
$pdf->ln();

$pdf->cell(10,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Receitas Correntes"                                                      ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['ReceitasCorrentes'],'f')	   ,"0",0,"R",0);

$pdf->cell(10,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Despesas Correntes"                                                      ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['DespesasCorrentes'],'f')	 ,"0",1,"R",0);

$pdf->cell(10,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Receitas de Capital"                                                     ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['ReceitasCapital'],'f')	     ,"0",0,"R",0);

$pdf->cell(10,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Despesas de Capital"                                                     ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['DespesasCapital'],'f')	   ,"0",1,"R",0);

$pdf->cell(10,$alt,""             			                                                     ,"0",0,"L",0);
$pdf->cell(60,$alt,"Operações Intra-Orçamentárias"                                           ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['IntraOrcamentarias'],'f')   ,"0",0,"R",0);

$pdf->cell(10,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Operações Intra-Orçamentárias"                                           ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['IntraOrcamentarias'],'f') ,"0",1,"R",0);

$pdf->cell(10,$alt,""                                                                        ,"0",0,"L",0);
$pdf->cell(60,$alt,"(-) Deduções da Receita"                                                 ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['DeducaoReceita'],'f')       ,"0",0,"R",0);

$pdf->cell(10,$alt,""                                                                        ,"0",0,"L",0);
$pdf->cell(60,$alt,""                                                                        ,"0",0,"L",0);
$pdf->cell(25,$alt,""                                                                        ,"0",1,"R",0);

$pdf->ln();

$pdf->cell(5,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(65,$alt,"INTERFERENCIAS ATIVAS"                                                   ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['InterferenciasAtivas'],'f') ,"0",0,"R",0);

$pdf->cell(5,$alt,""				                                                                 ,"0",0,"L",0);
$pdf->cell(65,$alt,"MUTAÇÕES PASSIVAS"                                                       ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['MutacoesPassivas'],'f')	 ,"0",1,"R",0);
$pdf->ln();

$pdf->cell(10,$alt,""             			                  ,"0",0,"L",0);
$pdf->cell(60,$alt,"Transferências Financeiras Recebidas" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['TransferenciasFinanceirasRecebidas'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""				                                                     ,"0",0,"L",0);
$pdf->cell(60,$alt,"Desincorporação de Ativos"                                   ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['DesincorporacaoAtivos'],'f')	 ,"0",1,"R",0);

$pdf->cell(10,$alt,"" ,"0",0,"L",0);
$pdf->cell(60,$alt,"" ,"0",0,"L",0);
$pdf->cell(25,$alt,"" ,"0",0,"R",0);

$pdf->cell(10,$alt,""				                                                   ,"0",0,"L",0);
$pdf->cell(60,$alt,"Incorporação de Passivos"                                  ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Passivo']['IncorporacaoPassivos'],'f') ,"0",1,"R",0);

$pdf->ln();
$pdf->cell(5,$alt,""				                                  ,"0",0,"L",0);
$pdf->cell(65,$alt,"MUTAÇÕES ATIVAS"                          ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['MutacoesAtivas'],'f') ,"0",0,"R",0);

$pdf->cell(5, $alt,""	,"0",0,"L",0);
$pdf->cell(65,$alt,"" ,"0",0,"L",0);
$pdf->cell(25,$alt,"" ,"0",1,"R",0);
$pdf->ln();

$pdf->cell(10,$alt,""   	                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Incorporação de Ativos"                                ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['IncorporacaoAtivos'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,"" ,"0",0,"L",0);
$pdf->cell(60,$alt,"" ,"0",0,"L",0);
$pdf->cell(25,$alt,"" ,"0",1,"R",0);

$pdf->cell(10,$alt,""             			                                        ,"0",0,"L",0);
$pdf->cell(60,$alt,"Desincorporação de Passivos"                                ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['Ativo']['DesincorporacaoPassivos'],'f')	,"0",0,"R",0);

$pdf->cell(10,$alt,""	,"0",0,"L",0);
$pdf->cell(60,$alt,"" ,"0",0,"L",0);
$pdf->cell(25,$alt,"" ,"0",1,"R",0);

$pdf->ln();
$pdf->line(10, $pdf->getY(),$pdf->w-10, $pdf->getY());
$pdf->ln();

$pdf->setfont('arial','b',8);
$pdf->cell(70,$alt,"RESULTADO EXTRA-ORÇAMENTÁRIO"          	,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['TotalAtivoExtra'] ,'f')	  ,"0",0,"R",0);

$pdf->cell(70,$alt,"RESULTADO EXTRA-ORÇAMENTÁRIO"          	,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['TotalPassivoExtra'],'f') ,"0",1,"R",0);
$pdf->ln();

$pdf->setfont('arial','',8);
$pdf->cell(5,$alt,""				               ,"0",0,"L",0);
$pdf->cell(65,$alt,"INTERFERÊNCIAS ATIVAS" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['InterferenciasAtivas'],'f')	  ,"0",0,"R",0);

$pdf->cell(5, $alt,""	,"0",0,"L",0);
$pdf->cell(65,$alt,"INTERFERÊNCIAS PASSIVAS" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['InterferenciasPassivas'],'f') ,"0",1,"R",0);
$pdf->ln();

$pdf->cell(10,$alt,""   	                               ,"0",0,"L",0);
$pdf->cell(60,$alt,"Transferências Financeiras Recebidas" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['TransferenciasFinanceirasRecebidas'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""                                      ,"0",0,"L",0);
$pdf->cell(60,$alt,"Transferências Financeiras Concedidas" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['TransferenciasFinanceirasConcedidas'],'f') ,"0",1,"R",0);

$pdf->cell(10,$alt,""             			           ,"0",0,"L",0);
$pdf->cell(60,$alt,"Movimento de Fundos a Débito"  ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['MovimentoFundosDebito'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""                              ,"0",0,"L",0);
$pdf->cell(60,$alt,"Movimento de Fundos a Crédito" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['MovimentoFundosCredito'],'f') ,"0",1,"R",0);

$pdf->ln();
$pdf->cell(5,$alt,""				                  ,"0",0,"L",0);
$pdf->cell(65,$alt,"ACRÉSCIMOS PATRIMONIAIS"  ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['AcrescimosPatrimoniais'],'f')	 ,"0",0,"R",0);

$pdf->cell(5, $alt,""	                                             ,"0",0,"L",0);
$pdf->cell(65,$alt,"DECRÉSCIMOS PATRIMONIAIS"                      ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['DecrescimosPatrimoniais'],'f') ,"0",1,"R",0);
$pdf->ln();

$pdf->cell(10,$alt,""   	                  ,"0",0,"L",0);
$pdf->cell(60,$alt,"Incorporação de Ativos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['IncorporacaoAtivos'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""                          ,"0",0,"L",0);
$pdf->cell(60,$alt,"Desincorporação de Ativos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['DesincorporacaoAtivos'],'f') ,"0",1,"R",0);

$pdf->cell(10,$alt,""             			                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Ajustes de Bens, Valores e Créditos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['AjustesBensValoresCreditos'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""                                    ,"0",0,"L",0);
$pdf->cell(60,$alt,"Ajustes de Bens, Valores e Créditos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['AjustesBensValoresCreditos'],'f') ,"0",1,"R",0);

$pdf->cell(10,$alt,""   	                       ,"0",0,"L",0);
$pdf->cell(60,$alt,"Desincorporação de Passivos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['DesincorporacaoPassivos'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,""                         ,"0",0,"L",0);
$pdf->cell(60,$alt,"Incorporação de Passivos" ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['PassivoExtra']['IncorporacaoPassivos'],'f') ,"0",1,"R",0);

$pdf->cell(10,$alt,""             			                                                 ,"0",0,"L",0);
$pdf->cell(60,$alt,"Ajustes de Exercícios Anteriores"                                    ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['AtivoExtra']['AjustesExerciciosAnteriores'],'f') ,"0",0,"R",0);

$pdf->cell(10,$alt,"","0",0,"L",0);
$pdf->cell(60,$alt,"","0",0,"L",0);
$pdf->cell(25,$alt,"","0",1,"R",0);

$pdf->Ln(0);
$pdf->line(10, $pdf->getY(),$pdf->w-10, $pdf->getY());

$pdf->cell(70,$alt,"SOMA"                       ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['TotaisAtivo']['Soma'],'f') ,"0",0,"R",0);
                                           
$pdf->cell(70,$alt,"SOMA"                         ,"0",0,"L",0);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['TotaisPassivo']['Soma'],'f') ,"0",1,"R",0);

$pdf->line(10, $pdf->getY(),$pdf->w-10, $pdf->getY());
$pdf->cell(70,$alt,"DÉFICIT PATRIMONIAL"   ,"0",0,"L",0);

if ($aVariacoesPatrimoniais['TotaisAtivo']['DeficitPatrimonial'] != "-") {
  $nDeficitPatrimonial = db_formatar($aVariacoesPatrimoniais['TotaisAtivo']['DeficitPatrimonial'],'f');
}else{
  $nDeficitPatrimonial = $aVariacoesPatrimoniais['TotaisAtivo']['DeficitPatrimonial'];  
}
$pdf->cell(25,$alt,$nDeficitPatrimonial    ,"0",0,"R",0);

if ($aVariacoesPatrimoniais['TotaisPassivo']['SuperavitPatrimonial'] != "-") {
  $nSuperavitPatrimonial = db_formatar($aVariacoesPatrimoniais['TotaisPassivo']['SuperavitPatrimonial'],'f');  
}else{
  $nSuperavitPatrimonial = $aVariacoesPatrimoniais['TotaisPassivo']['SuperavitPatrimonial'];
}
$pdf->cell(70,$alt,"SUPERAVÍT PATRIMONIAL" ,"0",0,"L",0);
$pdf->cell(25,$alt,$nSuperavitPatrimonial  ,"0",1,"R",0);

$pdf->line(10, $pdf->getY(),$pdf->w-10, $pdf->getY());
$pdf->cell(70,$alt,"TOTAL"            ,"BTR",0,"L",1);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['TotaisAtivo']['TotalAtivo'],'f') ,"1",0,"R",1);

$pdf->cell(70,$alt,"TOTAL"            ,"1",0,"L",1);
$pdf->cell(25,$alt,db_formatar($aVariacoesPatrimoniais['TotaisPassivo']['TotalPassivo'],'f')	,"BTL",1,"R",1);

notasExplicativas(&$pdf,54,($mes>9?$mes:"0".$mes),190);

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

$pdf->cell(65,$alt,$sAssPref		 ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssCont		 ,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazenda   ,0,1,"C",0);

$pdf->cell(65,$alt,$sAssPrefFunc,0,0,"C",0);
$pdf->cell(65,$alt,$sAssContFunc,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazendaFunc,0,1,"C",0);

$pdf->cell(65,$alt,$sAssPrefCPF,0,0,"C",0);
$pdf->cell(65,$alt,$sAssContCPF,0,0,"C",0);
$pdf->cell(65,$alt,$sAssSecretFazendaCPF,0,1,"C",0);

//exit;
$pdf->Output();

?>