<?php
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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_libtxt.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcparamrel_classe.php");
require_once("classes/db_conrelvalor_classe.php");
require_once("model/relatorioContabil.model.php");
require_once("model/linhaRelatorioContabil.model.php");

function imprime_cabec_rec($alt,$pdf) {

  $pdf->setfont('arial','',6);
  $pdf->cell(60,($alt*2),"RECEITAS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
  $pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
  $pdf->cell(70,$alt,"RECEITAS REALIZADAS",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"SALDO",'T',1,"C",0);
  //BR
  $pdf->setX(70);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);
  $pdf->cell(25,$alt,"No Bimestre (b)",'BR',0,"C",0);
  $pdf->cell(10,$alt,"% (b/a)",'BR',0,"C",0);
  $pdf->cell(25,$alt,"Até o Bimestre (c)",'BR',0,"C",0);
  $pdf->cell(10,$alt,"% (c/a)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"(a-c)",'B',0,"C",0);
  //BR
  $pdf->ln(4);
}

function imprime_cabec_desp($alt,$pdf, $bimestre) {

  if ($bimestre != 6) {

    $pdf->setfont('arial','',5);
    $pdf->cell(55,($alt*2),"DESPESAS",'TBR',0,"C",0);
    $pdf->cell(20,$alt,"DOTAÇÂO",'TR',0,"C",0);
    $pdf->cell(20,$alt,"CREDITOS",'TR',0,"C",0);
    $pdf->cell(20,$alt,"DOTAÇÂO",'TR',0,"C",0);
    $pdf->cell(28,$alt,"DESPESAS EMPENHADAS",'TBR',0,"C",0);
    $pdf->cell(38,$alt,"DESPESAS LIQUIDADAS",'TBR',0,"C",0);
    $pdf->cell(14,$alt,"SALDO",'T',1,"C",0);
    //BR
    $pdf->setX(65);
    $pdf->cell(20,$alt,"INICIAL (d)",'BR',0,"C",0);
    $pdf->cell(20,$alt,"ADICIONAIS (e)",'BR',0,"C",0);
    $pdf->cell(20,$alt,"ATUALIZADA(f)=(d+e)",'BR',0,"C",0);
    $pdf->cell(14,$alt,"No Bimestre",'BR',0,"C",0);
    $pdf->cell(14,$alt,"Até o Bimestre",'BR',0,"C",0);
    $pdf->cell(14,$alt,"No Bimestre",'BR',0,"C",0);
    $pdf->cell(14,$alt,"Até o Bimestre(g)",'BR',0,"C",0);
    $pdf->cell(10,$alt,"% (g/f)",'BR',0,"C",0);
    $pdf->cell(14,$alt,"(f-g)",'B',0,"C",0);
    $pdf->ln(4);

  } else {

    $pdf->setfont('arial','',5);
    $pdf->cell(55,($alt*4),"DESPESAS",'TRB',0,"C",0);
    $pdf->cell(15,$alt,"DOTAÇÂO",'TR',0,"C",0);
    $pdf->cell(15,$alt,"CREDITOS",'TR',0,"C",0);
    $pdf->cell(15,$alt,"DOTAÇÂO",'TR',0,"C",0);
    $pdf->cell(26,$alt,"DESPESAS",'TR',0,"C",0);
    $pdf->cell(51,$alt,"DESPESAS EXECUTADAS",'TBR',0,"C",0);
    $pdf->cell(14,$alt,"SALDO",'T',1,"C",0);
    $pdf->setX(65);

    $pdf->cell(15,$alt,"INICIAL (d)",'R',0,"C",0);
    $pdf->cell(15,$alt,"ADICIONAIS (e)",'R',0,"C",0);
    $pdf->cell(15,$alt,"ATUALIZADA",'R',0,"C",0);
    $pdf->cell(26,$alt,"EMPENHADAS",'BR',0,"C",0);
    $pdf->cell(23,$alt,"LIQUIDADAS",'TBR',0,"C",0);
    $pdf->cell(18,$alt,"INSCRITAS EM ",'TR',0,"C",0);
    $pdf->cell(10,$alt,"%",'TR',0,"C",0);
    $pdf->cell(14,$alt,"",'',1,"C",0);
    //BR
    $pdf->setX(65);
    $pdf->cell(15,$alt,"",'R',0,"C",0);
    $pdf->cell(15,$alt,"",'R',0,"C",0);
    $pdf->cell(15,$alt,"(f)=(d+e)",'R',0,"C",0);
    $pdf->cell(13,$alt,"No Bim",'R',0,"C",0);
    $pdf->cell(13,$alt,"Até o Bim",'R',0,"C",0);
    $pdf->cell(11,$alt,"No Bim",'R',0,"C",0);
    $pdf->cell(12,$alt,"Até o Bim(g)",'R',0,"C",0);
    $pdf->cell(18,$alt,"RP NÃO",'R',0,"C",0);
    $pdf->cell(10,$alt,"((g+h)/f)",'R',0,"C",0);
    $pdf->cell(14,$alt,"(f-(h+g))",'L',1,"C",0);
    $pdf->setX(65);
    $pdf->cell(15,$alt,"",'BR',0,"C",0);
    $pdf->cell(15,$alt,"",'BR',0,"C",0);
    $pdf->cell(15,$alt,"",'BR',0,"C",0);
    $pdf->cell(13,$alt,"",'BR',0,"C",0);
    $pdf->cell(13,$alt,"",'BR',0,"C",0);
    $pdf->cell(11,$alt,"",'BR',0,"C",0);
    $pdf->cell(12,$alt,"",'BR',0,"C",0);
    $pdf->cell(18,$alt,"PROCESSADOS(h)",'BR',0,"C",0);
    $pdf->cell(10,$alt,"",'BR',0,"C",0);
    $pdf->cell(14,$alt,"",'B',0,"C",0);
    $pdf->ln(4);
  }
}

$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$classinatura    = new cl_assinatura;
$orcparamrel     = new cl_orcparamrel;
$clconrelvalor   = new cl_conrelvalor;
$oDaoPeriodo     = db_utils::getDao("periodo");
$sSqlPeriodo     = $oDaoPeriodo->sql_query($bimestre);
$iCodigoPeriodo  = $bimestre;
$sSiglaPeriodo   = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
$dt              = data_periodo($anousu,$sSiglaPeriodo);
$bimestre        = $sSiglaPeriodo;
$dt              = datas_bimestre($bimestre,$anousu);
// no dbforms/db_funcoes.php
$dt_ini = $dt[0];
// data inicial do período
$dt_fin = $dt[1];
// data final do período

// seleciona matriz com estruturais selecionados pelo usuario
// variareis
$n1               = 5;
$n2               = 10;
$iCodigoRelatorio = 79;

$oRelatorio  = new relatorioContabil($iCodigoRelatorio,false);
$instituicao = str_replace("-",",",$db_selinstit);

$m_impostos["parametros"]                           = new linhaRelatorioContabil($iCodigoRelatorio, 1);
$m_taxas["parametros"]                              = new linhaRelatorioContabil($iCodigoRelatorio, 2);
$m_melhorias["parametros"]                          = new linhaRelatorioContabil($iCodigoRelatorio, 3);
$m_sociais["parametros"]                            = new linhaRelatorioContabil($iCodigoRelatorio, 4);
$m_iluminacao_publica["parametros"]                 = new linhaRelatorioContabil($iCodigoRelatorio, 61);

$m_economicas["parametros"]                         = new linhaRelatorioContabil($iCodigoRelatorio, 5);
$m_imobiliarias["parametros"]                       = new linhaRelatorioContabil($iCodigoRelatorio, 6);
$m_valmobiliarias["parametros"]                     = new linhaRelatorioContabil($iCodigoRelatorio, 7);

$m_permissoes["parametros"]                         = new linhaRelatorioContabil($iCodigoRelatorio, 8);
$m_comp_financeiras["parametros"]                   = new linhaRelatorioContabil($iCodigoRelatorio, 9);
$m_receita_decorrente_direito["parametros"]         = new linhaRelatorioContabil($iCodigoRelatorio, 62);
$m_receita_cessao_direitos["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 63);
$m_patrimoniais["parametros"]                       = new linhaRelatorioContabil($iCodigoRelatorio, 10);

$m_vegetal["parametros"]                            = new linhaRelatorioContabil($iCodigoRelatorio, 11);
$m_animal["parametros"]                             = new linhaRelatorioContabil($iCodigoRelatorio, 12);
$m_agropecuarias["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 13);

$m_industria_extrativa_mineral["parametros"]        = new linhaRelatorioContabil($iCodigoRelatorio, 64);
$m_transformacao["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 14);
$m_construcao["parametros"]                         = new linhaRelatorioContabil($iCodigoRelatorio, 15);
$m_industriais["parametros"]                        = new linhaRelatorioContabil($iCodigoRelatorio, 16);

$m_servicos["parametros"]                           = new linhaRelatorioContabil($iCodigoRelatorio, 17);

$m_intergovernamental["parametros"]                 = new linhaRelatorioContabil($iCodigoRelatorio, 18);
$m_privadas["parametros"]                           = new linhaRelatorioContabil($iCodigoRelatorio, 19);
$m_transf_exterior["parametros"]                    = new linhaRelatorioContabil($iCodigoRelatorio, 20);
$m_transf_pessoas["parametros"]                     = new linhaRelatorioContabil($iCodigoRelatorio, 21);
$m_transf_convenios["parametros"]                   = new linhaRelatorioContabil($iCodigoRelatorio, 22);
$m_transf_fome["parametros"]                        = new linhaRelatorioContabil($iCodigoRelatorio, 39);

$m_multas["parametros"]                             = new linhaRelatorioContabil($iCodigoRelatorio, 24);
$m_indenizacao["parametros"]                        = new linhaRelatorioContabil($iCodigoRelatorio, 25);
$m_divida_ativa["parametros"]                       = new linhaRelatorioContabil($iCodigoRelatorio, 26);
$m_receita_decorrente_aportes["parametros"]         = new linhaRelatorioContabil($iCodigoRelatorio, 65);
$m_correntes_diversas["parametros"]                 = new linhaRelatorioContabil($iCodigoRelatorio, 27);

$m_oper_internas["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 28);
$m_oper_externas["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 29);

$m_bens_moveis["parametros"]                        = new linhaRelatorioContabil($iCodigoRelatorio, 30);
$m_bens_imoveis["parametros"]                       = new linhaRelatorioContabil($iCodigoRelatorio, 31);
$m_emprestimos["parametros"]                        = new linhaRelatorioContabil($iCodigoRelatorio, 32);

$m_transf_capital_intergovernamentais["parametros"] = new linhaRelatorioContabil($iCodigoRelatorio, 33);
$m_transf_capital_privadas["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 34);
$m_transf_capital_exterior["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 35);
$m_transf_capital_pessoas["parametros"]             = new linhaRelatorioContabil($iCodigoRelatorio, 36);
$m_transf_capital_outras["parametros"]              = new linhaRelatorioContabil($iCodigoRelatorio, 37);
$m_transf_capital_convenios["parametros"]           = new linhaRelatorioContabil($iCodigoRelatorio, 38);
$m_transf_capital_fome["parametros"]                = new linhaRelatorioContabil($iCodigoRelatorio, 23);

$m_outras_social["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 40);
$m_outras_disponibilidades["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 41);
$m_outras_diversas["parametros"]                    = new linhaRelatorioContabil($iCodigoRelatorio, 42);

$m_oper_credito_internas_mobiliaria["parametros"]   = new linhaRelatorioContabil($iCodigoRelatorio, 43);
$m_oper_credito_internas_contratual["parametros"]   = new linhaRelatorioContabil($iCodigoRelatorio, 44);
$m_oper_credito_externas_mobiliaria["parametros"]   = new linhaRelatorioContabil($iCodigoRelatorio, 45);
$m_oper_credito_externas_contratual["parametros"]   = new linhaRelatorioContabil($iCodigoRelatorio, 46);

$m_superavit_financeiro["parametros"]               = new linhaRelatorioContabil($iCodigoRelatorio, 47);
$m_reab_credito_adicionais["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 48);

$m_pessoal_enc_sociais["parametros"]                = new linhaRelatorioContabil($iCodigoRelatorio, 49);
$m_juros_enc_divida["parametros"]                   = new linhaRelatorioContabil($iCodigoRelatorio, 50);
$m_outras_despesas_correntes["parametros"]          = new linhaRelatorioContabil($iCodigoRelatorio, 51);
$m_investimentos["parametros"]                      = new linhaRelatorioContabil($iCodigoRelatorio, 52);
$m_inversoes_financeiras["parametros"]              = new linhaRelatorioContabil($iCodigoRelatorio, 53);
$m_amortizacao_divida["parametros"]                 = new linhaRelatorioContabil($iCodigoRelatorio, 54);
$m_reserva_contigencia["parametros"]                = new linhaRelatorioContabil($iCodigoRelatorio, 55);
$m_reserva_rpps["parametros"]                       = new linhaRelatorioContabil($iCodigoRelatorio, 56);

$m_amort_divida_int_mobiliario["parametros"]        = new linhaRelatorioContabil($iCodigoRelatorio, 57);
$m_amort_divida_int_outras["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 58);
$m_amort_divida_ext_mobiliario["parametros"]        = new linhaRelatorioContabil($iCodigoRelatorio, 59);
$m_amort_divida_ext_outras["parametros"]            = new linhaRelatorioContabil($iCodigoRelatorio, 60);

// Receitas Correntes Intra-Orcamentaria
$rec_cor_II_inicial_intra        = 0;
$rec_cor_II_atualizada_intra     = 0;
$rec_cor_II_nobim_intra          = 0;
$rec_cor_II_atebim_intra         = 0;
$rec_cor_II_realizar_intra       = 0;

// Receitas de Capital Intra-Orcamentaria
$rec_cap_II_inicial_intra        = 0;
$rec_cap_II_atualizada_intra     = 0;
$rec_cap_II_nobim_intra          = 0;
$rec_cap_II_atebim_intra         = 0;
$rec_cap_II_realizar_intra       = 0;
$rec_cap_II_realizar_intra       = 0;

// Receitas Tributarias Intra-Orcamentaria
$rec_trib_II_inicial_intra       = 0;
$rec_trib_II_atualizada_intra    = 0;
$rec_trib_II_nobim_intra         = 0;
$rec_trib_II_atebim_intra        = 0;
$rec_trib_II_realizar_intra      = 0;

// Receitas de Contribuicao Intra-Orcamentaria
$rec_contr_II_inicial_intra      = 0;
$rec_contr_II_atualizada_intra   = 0;
$rec_contr_II_nobim_intra        = 0;
$rec_contr_II_atebim_intra       = 0;
$rec_contr_II_realizar_intra     = 0;

// Receita Patrimonial Intra-Orcamentaria
$rec_patr_II_inicial_intra       = 0;
$rec_patr_II_atualizada_intra    = 0;
$rec_patr_II_nobim_intra         = 0;
$rec_patr_II_atebim_intra        = 0;
$rec_patr_II_realizar_intra      = 0;

// Receita Agropecuaria Intra-Orcamentaria
$rec_agro_II_inicial_intra       = 0;
$rec_agro_II_atualizada_intra    = 0;
$rec_agro_II_nobim_intra         = 0;
$rec_agro_II_atebim_intra        = 0;
$rec_agro_II_realizar_intra      = 0;

// Receita Industrial Intra-Orcamentaria
$rec_ind_II_inicial_intra       = 0;
$rec_ind_II_atualizada_intra    = 0;
$rec_ind_II_nobim_intra         = 0;
$rec_ind_II_atebim_intra        = 0;
$rec_ind_II_realizar_intra      = 0;

// Transferencias Correntes Intra-Orcamentaria
$rec_transf_cor_II_inicial_intra    = 0;
$rec_transf_cor_II_atualizada_intra = 0;
$rec_transf_cor_II_nobim_intra      = 0;
$rec_transf_cor_II_atebim_intra     = 0;
$rec_transf_cor_II_realizar_intra   = 0;

// Outras Receitas Correntes Intra-Orcamentaria
$rec_outras_cor_II_inicial_intra    = 0;
$rec_outras_cor_II_atualizada_intra = 0;
$rec_outras_cor_II_nobim_intra      = 0;
$rec_outras_cor_II_atebim_intra     = 0;
$rec_outras_cor_II_realizar_intra   = 0;

// Operacoes de Credito Capital Intra-Orcamentaria
$rec_oper_cred_cap_II_inicial_intra    = 0;
$rec_oper_cred_cap_II_atualizada_intra = 0;
$rec_oper_cred_cap_II_nobim_intra      = 0;
$rec_oper_cred_cap_II_atebim_intra     = 0;
$rec_oper_cred_cap_II_realizar_intra   = 0;

// Alienacao de Bens Capital Intra-Orcamentaria
$rec_alien_bens_cap_II_inicial_intra    = 0;
$rec_alien_bens_cap_II_atualizada_intra = 0;
$rec_alien_bens_cap_II_nobim_intra      = 0;
$rec_alien_bens_cap_II_atebim_intra     = 0;
$rec_alien_bens_cap_II_realizar_intra   = 0;

// Transferencias de Capital Intra-Orcamentaria
$rec_transf_cap_II_inicial_intra    = 0;
$rec_transf_cap_II_atualizada_intra = 0;
$rec_transf_cap_II_nobim_intra      = 0;
$rec_transf_cap_II_atebim_intra     = 0;
$rec_transf_cap_II_realizar_intra   = 0;

// Outras Receitas de Capital Intra-Orcamentaria
$rec_outras_cap_II_inicial_intra    = 0;
$rec_outras_cap_II_atualizada_intra = 0;
$rec_outras_cap_II_nobim_intra      = 0;
$rec_outras_cap_II_atebim_intra     = 0;
$rec_outras_cap_II_realizar_intra   = 0;

// Impostos Intra-Orcamentario
$m_impostos_intra["inicial"]    = 0;
$m_impostos_intra["atualizada"] = 0;
$m_impostos_intra["nobim"]      = 0;
$m_impostos_intra["atebim"]     = 0;
$m_impostos_intra["realizar"]   = 0;

// Taxas Intra-Orcamentaria
$m_taxas_intra["inicial"]       = 0;
$m_taxas_intra["atualizada"]    = 0;
$m_taxas_intra["nobim"]         = 0;
$m_taxas_intra["atebim"]        = 0;
$m_taxas_intra["realizar"]      = 0;

// Contribuicao de Melhoria Intra-Orcamentaria
$m_melhorias_intra["inicial"]    = 0;
$m_melhorias_intra["atualizada"] = 0;
$m_melhorias_intra["nobim"]      = 0;
$m_melhorias_intra["atebim"]     = 0;
$m_melhorias_intra["realizar"]   = 0;

// Contribuicoes Sociais Intra-Orcamentaria
$m_sociais_intra["inicial"]      = 0;
$m_sociais_intra["atualizada"]   = 0;
$m_sociais_intra["nobim"]        = 0;
$m_sociais_intra["atebim"]       = 0;
$m_sociais_intra["realizar"]     = 0;

// Contribuicoes Economicas Intra-Orcamentaria
$m_economicas_intra["inicial"]    = 0;
$m_economicas_intra["atualizada"] = 0;
$m_economicas_intra["nobim"]      = 0;
$m_economicas_intra["atebim"]     = 0;
$m_economicas_intra["realizar"]   = 0;

// Contribuicoes Iluminacao Publica
$m_iluminacao_publica_intra["inicial"]    = 0;
$m_iluminacao_publica_intra["atualizada"] = 0;
$m_iluminacao_publica_intra["nobim"]      = 0;
$m_iluminacao_publica_intra["atebim"]     = 0;
$m_iluminacao_publica_intra["realizar"]   = 0;

// Receitas Imobiliarias Intra-Orcamentaria
$m_imobiliarias_intra["inicial"]    = 0;
$m_imobiliarias_intra["atualizada"] = 0;
$m_imobiliarias_intra["nobim"]      = 0;
$m_imobiliarias_intra["atebim"]     = 0;
$m_imobiliarias_intra["realizar"]   = 0;

// Receita de Valores Mobiliarios Intra-Orcamentario
$m_valmobiliarias_intra["inicial"]    = 0;
$m_valmobiliarias_intra["atualizada"] = 0;
$m_valmobiliarias_intra["nobim"]      = 0;
$m_valmobiliarias_intra["atebim"]     = 0;
$m_valmobiliarias_intra["realizar"]   = 0;

// Receita de Concessoes e Permissoes Intra-Orcamentario
$m_permissoes_intra["inicial"]    = 0;
$m_permissoes_intra["atualizada"] = 0;
$m_permissoes_intra["nobim"]      = 0;
$m_permissoes_intra["atebim"]     = 0;
$m_permissoes_intra["realizar"]   = 0;

// Compensações Financeiras Intra-Orcamentario
$m_comp_financeiras_intra["inicial"]    = 0;
$m_comp_financeiras_intra["atualizada"] = 0;
$m_comp_financeiras_intra["nobim"]      = 0;
$m_comp_financeiras_intra["atebim"]     = 0;
$m_comp_financeiras_intra["realizar"]   = 0;

// Receita Decorrente do Direito de Exploração de Bens Públicos em Áreas de Domínio Público
$m_receita_decorrente_direito_intra["inicial"]    = 0;
$m_receita_decorrente_direito_intra["atualizada"] = 0;
$m_receita_decorrente_direito_intra["nobim"]      = 0;
$m_receita_decorrente_direito_intra["atebim"]     = 0;
$m_receita_decorrente_direito_intra["realizar"]   = 0;

// Receita da Cessão de Direitos
$m_receita_cessao_direitos_intra["inicial"]    = 0;
$m_receita_cessao_direitos_intra["atualizada"] = 0;
$m_receita_cessao_direitos_intra["nobim"]      = 0;
$m_receita_cessao_direitos_intra["atebim"]     = 0;
$m_receita_cessao_direitos_intra["realizar"]   = 0;

// Outras Receitas Patrimoniais Intra-Orcamentario
$m_patrimoniais_intra["inicial"]    = 0;
$m_patrimoniais_intra["atualizada"] = 0;
$m_patrimoniais_intra["nobim"]      = 0;
$m_patrimoniais_intra["atebim"]     = 0;
$m_patrimoniais_intra["realizar"]   = 0;

// Receita da Producao Vegetal Intra-Orcamentaria
$m_vegetal_intra["inicial"]    = 0;
$m_vegetal_intra["atualizada"] = 0;
$m_vegetal_intra["nobim"]      = 0;
$m_vegetal_intra["atebim"]     = 0;
$m_vegetal_intra["realizar"]   = 0;

// Receita da Producao Vegetal Intra-Orcamentaria
$m_animal_intra["inicial"]    = 0;
$m_animal_intra["atualizada"] = 0;
$m_animal_intra["nobim"]      = 0;
$m_animal_intra["atebim"]     = 0;
$m_animal_intra["realizar"]   = 0;

// Outras Receitas Agropecuarias Intra-Orcamentaria
$m_agropecuarias_intra["inicial"]    = 0;
$m_agropecuarias_intra["atualizada"] = 0;
$m_agropecuarias_intra["nobim"]      = 0;
$m_agropecuarias_intra["atebim"]     = 0;
$m_agropecuarias_intra["realizar"]   = 0;

// Receita da Industria de Extrativa mineral
$m_industria_extrativa_mineral_intra["inicial"]    = 0;
$m_industria_extrativa_mineral_intra["atualizada"] = 0;
$m_industria_extrativa_mineral_intra["nobim"]      = 0;
$m_industria_extrativa_mineral_intra["atebim"]     = 0;
$m_industria_extrativa_mineral_intra["realizar"]   = 0;

// Receitas da Industria de Transformacao Intra-Orcamentaria
$m_transformacao_intra["inicial"]    = 0;
$m_transformacao_intra["atualizada"] = 0;
$m_transformacao_intra["nobim"]      = 0;
$m_transformacao_intra["atebim"]     = 0;
$m_transformacao_intra["realizar"]   = 0;


// Receita da Industria de Construcao Intra-Orcamentaria
$m_construcao_intra["inicial"]    = 0;
$m_construcao_intra["atualizada"] = 0;
$m_construcao_intra["nobim"]      = 0;
$m_construcao_intra["atebim"]     = 0;
$m_construcao_intra["realizar"]   = 0;

// Receita da Industria de Construcao Intra-Orcamentaria
$m_industriais_intra["inicial"]    = 0;
$m_industriais_intra["atualizada"] = 0;
$m_industriais_intra["nobim"]      = 0;
$m_industriais_intra["atebim"]     = 0;
$m_industriais_intra["realizar"]   = 0;

// Receita de Servicos Intra-Orcamentaria
$m_servicos_intra["inicial"]    = 0;
$m_servicos_intra["atualizada"] = 0;
$m_servicos_intra["nobim"]      = 0;
$m_servicos_intra["atebim"]     = 0;
$m_servicos_intra["realizar"]   = 0;

// Transferencias Intergovernamentais Intra-Orcamentaria
$m_intergovernamental_intra["inicial"]    = 0;
$m_intergovernamental_intra["atualizada"] = 0;
$m_intergovernamental_intra["nobim"]      = 0;
$m_intergovernamental_intra["atebim"]     = 0;
$m_intergovernamental_intra["realizar"]   = 0;

// Transferencias de Instituicoes Privadas Intra-Orcamentaria
$m_privadas_intra["inicial"]    = 0;
$m_privadas_intra["atualizada"] = 0;
$m_privadas_intra["nobim"]      = 0;
$m_privadas_intra["atebim"]     = 0;
$m_privadas_intra["realizar"]   = 0;

// Transferencias do Exterior Intra-Orcamentaria
$m_transf_exterior_intra["inicial"]    = 0;
$m_transf_exterior_intra["atualizada"] = 0;
$m_transf_exterior_intra["nobim"]      = 0;
$m_transf_exterior_intra["atebim"]     = 0;
$m_transf_exterior_intra["realizar"]   = 0;

// Transferencias de Pessoas Intra-Orcamentaria
$m_transf_pessoas_intra["inicial"]    = 0;
$m_transf_pessoas_intra["atualizada"] = 0;
$m_transf_pessoas_intra["nobim"]      = 0;
$m_transf_pessoas_intra["atebim"]     = 0;
$m_transf_pessoas_intra["realizar"]   = 0;

// Transferencias de Convenios Intra-Orcamentaria
$m_transf_convenios_intra["inicial"]    = 0;
$m_transf_convenios_intra["atualizada"] = 0;
$m_transf_convenios_intra["nobim"]      = 0;
$m_transf_convenios_intra["atebim"]     = 0;
$m_transf_convenios_intra["realizar"]   = 0;

// Transferencias para o Combate a Fome Intra-Orcamentaria
$m_transf_fome_intra["inicial"]    = 0;
$m_transf_fome_intra["atualizada"] = 0;
$m_transf_fome_intra["nobim"]      = 0;
$m_transf_fome_intra["atebim"]     = 0;
$m_transf_fome_intra["realizar"]   = 0;

// Multas e Juros de Mora Intra-Orcamentaria
$m_multas_intra["inicial"]    = 0;
$m_multas_intra["atualizada"] = 0;
$m_multas_intra["nobim"]      = 0;
$m_multas_intra["atebim"]     = 0;
$m_multas_intra["realizar"]   = 0;

// Indenizacoes e Restituicoes Intra-Orcamentaria
$m_indenizacao_intra["inicial"]    = 0;
$m_indenizacao_intra["atualizada"] = 0;
$m_indenizacao_intra["nobim"]      = 0;
$m_indenizacao_intra["atebim"]     = 0;
$m_indenizacao_intra["realizar"]   = 0;

// Receita da Divida Ativa Intra-Orcamentaria
$m_divida_ativa_intra["inicial"]    = 0;
$m_divida_ativa_intra["atualizada"] = 0;
$m_divida_ativa_intra["nobim"]      = 0;
$m_divida_ativa_intra["atebim"]     = 0;
$m_divida_ativa_intra["realizar"]   = 0;

// Receitas Decorrentes aportes periodicos
$m_receita_decorrente_aportes_intra["inicial"]    = 0;
$m_receita_decorrente_aportes_intra["atualizada"] = 0;
$m_receita_decorrente_aportes_intra["nobim"]      = 0;
$m_receita_decorrente_aportes_intra["atebim"]     = 0;
$m_receita_decorrente_aportes_intra["realizar"]   = 0;

// Receitas Correntes Diversas Intra-Orcamentaria
$m_correntes_diversas_intra["inicial"]    = 0;
$m_correntes_diversas_intra["atualizada"] = 0;
$m_correntes_diversas_intra["nobim"]      = 0;
$m_correntes_diversas_intra["atebim"]     = 0;
$m_correntes_diversas_intra["realizar"]   = 0;

// Operacoes de Credito Internas Intra-Orcamentaria
$m_oper_internas_intra["inicial"]    = 0;
$m_oper_internas_intra["atualizada"] = 0;
$m_oper_internas_intra["nobim"]      = 0;
$m_oper_internas_intra["atebim"]     = 0;
$m_oper_internas_intra["realizar"]   = 0;

// Operacoes de Credito Externas Intra-Orcamentaria
$m_oper_externas_intra["inicial"]    = 0;
$m_oper_externas_intra["atualizada"] = 0;
$m_oper_externas_intra["nobim"]      = 0;
$m_oper_externas_intra["atebim"]     = 0;
$m_oper_externas_intra["realizar"]   = 0;

// Alienacao de Bens Moveis Intra-Orcamentario
$m_bens_moveis_intra["inicial"]    = 0;
$m_bens_moveis_intra["atualizada"] = 0;
$m_bens_moveis_intra["nobim"]      = 0;
$m_bens_moveis_intra["atebim"]     = 0;
$m_bens_moveis_intra["realizar"]   = 0;

// Alienacao de Bens Imoveis Intra-Orcamentario
$m_bens_imoveis_intra["inicial"]    = 0;
$m_bens_imoveis_intra["atualizada"] = 0;
$m_bens_imoveis_intra["nobim"]      = 0;
$m_bens_imoveis_intra["atebim"]     = 0;
$m_bens_imoveis_intra["realizar"]   = 0;

// Alienacao de Emprestimos Intra-Orcamentario
$m_emprestimos_intra["inicial"]    = 0;
$m_emprestimos_intra["atualizada"] = 0;
$m_emprestimos_intra["nobim"]      = 0;
$m_emprestimos_intra["atebim"]     = 0;
$m_emprestimos_intra["realizar"]   = 0;

// Transferencias Intergovernamentais Intra-Orcamentario
$m_transf_capital_intergovernamentais_intra["inicial"]    = 0;
$m_transf_capital_intergovernamentais_intra["atualizada"] = 0;
$m_transf_capital_intergovernamentais_intra["nobim"]      = 0;
$m_transf_capital_intergovernamentais_intra["atebim"]     = 0;
$m_transf_capital_intergovernamentais_intra["realizar"]   = 0;

// Transferencias de Insituicoes Privadas Intra-Orcamentario
$m_transf_capital_privadas_intra["inicial"]    = 0;
$m_transf_capital_privadas_intra["atualizada"] = 0;
$m_transf_capital_privadas_intra["nobim"]      = 0;
$m_transf_capital_privadas_intra["atebim"]     = 0;
$m_transf_capital_privadas_intra["realizar"]   = 0;

// Transferencias do Exterior Intra-Orcamentario
$m_transf_capital_exterior_intra["inicial"]    = 0;
$m_transf_capital_exterior_intra["atualizada"] = 0;
$m_transf_capital_exterior_intra["nobim"]      = 0;
$m_transf_capital_exterior_intra["atebim"]     = 0;
$m_transf_capital_exterior_intra["realizar"]   = 0;

// Transferencias de Pessoas Intra-Orcamentario
$m_transf_capital_pessoas_intra["inicial"]    = 0;
$m_transf_capital_pessoas_intra["atualizada"] = 0;
$m_transf_capital_pessoas_intra["nobim"]      = 0;
$m_transf_capital_pessoas_intra["atebim"]     = 0;
$m_transf_capital_pessoas_intra["realizar"]   = 0;

// Transferencias de Outras Instituicoes Publicas Intra-Orcamentario
$m_transf_capital_outras_intra["inicial"]    = 0;
$m_transf_capital_outras_intra["atualizada"] = 0;
$m_transf_capital_outras_intra["nobim"]      = 0;
$m_transf_capital_outras_intra["atebim"]     = 0;
$m_transf_capital_outras_intra["realizar"]   = 0;

// Transferencias de Convenios Intra-Orcamentario
$m_transf_capital_convenios_intra["inicial"]    = 0;
$m_transf_capital_convenios_intra["atualizada"] = 0;
$m_transf_capital_convenios_intra["nobim"]      = 0;
$m_transf_capital_convenios_intra["atebim"]     = 0;
$m_transf_capital_convenios_intra["realizar"]   = 0;

// Transferencias para o Combate a fome Intra-Orcamentario
$m_transf_capital_fome_intra["inicial"]    = 0;
$m_transf_capital_fome_intra["atualizada"] = 0;
$m_transf_capital_fome_intra["nobim"]      = 0;
$m_transf_capital_fome_intra["atebim"]     = 0;
$m_transf_capital_fome_intra["realizar"]   = 0;

// Integralizacao do Capital Social Intra-Orcamentario
$m_outras_social_intra["inicial"]    = 0;
$m_outras_social_intra["atualizada"] = 0;
$m_outras_social_intra["nobim"]      = 0;
$m_outras_social_intra["atebim"]     = 0;
$m_outras_social_intra["realizar"]   = 0;

// Div. Atv. Prov. da Amortiz. de Emp. e Financ. Intra-Orcamentario
$m_outras_disponibilidades_intra["inicial"]    = 0;
$m_outras_disponibilidades_intra["atualizada"] = 0;
$m_outras_disponibilidades_intra["nobim"]      = 0;
$m_outras_disponibilidades_intra["atebim"]     = 0;
$m_outras_disponibilidades_intra["realizar"]   = 0;

// Restituicoes Intra-Orcamentario
$m_outras_restituicoes_intra["inicial"]    = 0;
$m_outras_restituicoes_intra["atualizada"] = 0;
$m_outras_restituicoes_intra["nobim"]      = 0;
$m_outras_restituicoes_intra["atebim"]     = 0;
$m_outras_restituicoes_intra["realizar"]   = 0;

// Receitas de Capital Diversas Intra-Orcamentario
$m_outras_diversas_intra["inicial"]    = 0;
$m_outras_diversas_intra["atualizada"] = 0;
$m_outras_diversas_intra["nobim"]      = 0;
$m_outras_diversas_intra["atebim"]     = 0;
$m_outras_diversas_intra["realizar"]   = 0;

// Operções de Crédito Internas Mobiliária Intra-Orcamentario
$m_oper_credito_internas_mobiliaria_intra["inicial"]    = 0;
$m_oper_credito_internas_mobiliaria_intra["atualizada"] = 0;
$m_oper_credito_internas_mobiliaria_intra["nobim"]      = 0;
$m_oper_credito_internas_mobiliaria_intra["atebim"]     = 0;
$m_oper_credito_internas_mobiliaria_intra["realizar"]   = 0;

// Operções de Crédito Internas Contratual Intra-Orcamentario
$m_oper_credito_internas_contratual_intra["inicial"]    = 0;
$m_oper_credito_internas_contratual_intra["atualizada"] = 0;
$m_oper_credito_internas_contratual_intra["nobim"]      = 0;
$m_oper_credito_internas_contratual_intra["atebim"]     = 0;
$m_oper_credito_internas_contratual_intra["realizar"]   = 0;

// Operções de Crédito Externas Mobiliária Intra-Orcamentario
$m_oper_credito_externas_mobiliaria_intra["inicial"]    = 0;
$m_oper_credito_externas_mobiliaria_intra["atualizada"] = 0;
$m_oper_credito_externas_mobiliaria_intra["nobim"]      = 0;
$m_oper_credito_externas_mobiliaria_intra["atebim"]     = 0;
$m_oper_credito_externas_mobiliaria_intra["realizar"]   = 0;

// Operções de Crédito Externas Contratual Intra-Orcamentario
$m_oper_credito_externas_contratual_intra["inicial"]    = 0;
$m_oper_credito_externas_contratual_intra["atualizada"] = 0;
$m_oper_credito_externas_contratual_intra["nobim"]      = 0;
$m_oper_credito_externas_contratual_intra["atebim"]     = 0;
$m_oper_credito_externas_contratual_intra["realizar"]   = 0;

// Superávit Financeiro Intra-Orcamentario
$m_superavit_financeiro_intra["inicial"]    = 0;
$m_superavit_financeiro_intra["atualizada"] = 0;
$m_superavit_financeiro_intra["nobim"]      = 0;
$m_superavit_financeiro_intra["atebim"]     = 0;
$m_superavit_financeiro_intra["realizar"]   = 0;

// Reabertura de Crédito Adicionais Intra-Orcamentario
$m_reab_credito_adicionais_intra["inicial"]    = 0;
$m_reab_credito_adicionais_intra["atualizada"] = 0;
$m_reab_credito_adicionais_intra["nobim"]      = 0;
$m_reab_credito_adicionais_intra["atebim"]     = 0;
$m_reab_credito_adicionais_intra["realizar"]   = 0;

// Pessoal e Encargos Sociais Intra-Orcamentario
$m_pessoal_enc_sociais_intra["inicial"]    = 0;
$m_pessoal_enc_sociais_intra["atualizada"] = 0;
$m_pessoal_enc_sociais_intra["nobim"]      = 0;
$m_pessoal_enc_sociais_intra["atebim"]     = 0;
$m_pessoal_enc_sociais_intra["realizar"]   = 0;

// Juros e Encargos da Divida Intra-Orcamentario
$m_juros_enc_divida_intra["inicial"]    = 0;
$m_juros_enc_divida_intra["atualizada"] = 0;
$m_juros_enc_divida_intra["nobim"]      = 0;
$m_juros_enc_divida_intra["atebim"]     = 0;
$m_juros_enc_divida_intra["realizar"]   = 0;

// Outras Despesas Correntes Intra-Orcamentario
$m_outras_despesas_correntes_intra["inicial"]    = 0;
$m_outras_despesas_correntes_intra["atualizada"] = 0;
$m_outras_despesas_correntes_intra["nobim"]      = 0;
$m_outras_despesas_correntes_intra["atebim"]     = 0;
$m_outras_despesas_correntes_intra["realizar"]   = 0;

// Investimentos Intra-Orcamentario
$m_investimentos_intra["inicial"]    = 0;
$m_investimentos_intra["atualizada"] = 0;
$m_investimentos_intra["nobim"]      = 0;
$m_investimentos_intra["atebim"]     = 0;
$m_investimentos_intra["realizar"]   = 0;

// Amortização da Dívida Intra-Orcamentario
$m_amortizacao_divida_intra["inicial"]    = 0;
$m_amortizacao_divida_intra["atualizada"] = 0;
$m_amortizacao_divida_intra["nobim"]      = 0;
$m_amortizacao_divida_intra["atebim"]     = 0;
$m_amortizacao_divida_intra["realizar"]   = 0;

// Reserva de Contingência Intra-Orcamentario
$m_reserva_contigencia_intra["inicial"]    = 0;
$m_reserva_contigencia_intra["atualizada"] = 0;
$m_reserva_contigencia_intra["nobim"]      = 0;
$m_reserva_contigencia_intra["atebim"]     = 0;
$m_reserva_contigencia_intra["realizar"]   = 0;

// Reserva do RPPS Intra-Orcamentario
$m_reserva_rpps_intra["inicial"]    = 0;
$m_reserva_rpps_intra["atualizada"] = 0;
$m_reserva_rpps_intra["nobim"]      = 0;
$m_reserva_rpps_intra["atebim"]     = 0;
$m_reserva_rpps_intra["realizar"]   = 0;

// Amortização da dívida interna Mobiliário
$m_amort_divida_int_mobiliario_intra["inicial"]    = 0;
$m_amort_divida_int_mobiliario_intra["atualizada"] = 0;
$m_amort_divida_int_mobiliario_intra["nobim"]      = 0;
$m_amort_divida_int_mobiliario_intra["atebim"]     = 0;
$m_amort_divida_int_mobiliario_intra["realizar"]   = 0;

// Amortização da dívida interna Outras
$m_amort_divida_int_outras_intra["inicial"]    = 0;
$m_amort_divida_int_outras_intra["atualizada"] = 0;
$m_amort_divida_int_outras_intra["nobim"]      = 0;
$m_amort_divida_int_outras_intra["atebim"]     = 0;
$m_amort_divida_int_outras_intra["realizar"]   = 0;

// Reabertura de Crédito Adicionais Intra-Orcamentario
$m_amort_divida_ext_mobiliario_intra["inicial"]    = 0;
$m_amort_divida_ext_mobiliario_intra["atualizada"] = 0;
$m_amort_divida_ext_mobiliario_intra["nobim"]      = 0;
$m_amort_divida_ext_mobiliario_intra["atebim"]     = 0;
$m_amort_divida_ext_mobiliario_intra["realizar"]   = 0;

// Reabertura de Crédito Adicionais Intra-Orcamentario
$m_amort_divida_ext_outras_intra["inicial"]    = 0;
$m_amort_divida_ext_outras_intra["atualizada"] = 0;
$m_amort_divida_ext_outras_intra["nobim"]      = 0;
$m_amort_divida_ext_outras_intra["atebim"]     = 0;
$m_amort_divida_ext_outras_intra["realizar"]   = 0;

// Reabertura de Crédito Adicionais Intra-Orcamentario
$m_inversoes_financeiras_intra["inicial"]    = 0;
$m_inversoes_financeiras_intra["atualizada"] = 0;
$m_inversoes_financeiras_intra["nobim"]      = 0;
$m_inversoes_financeiras_intra["atebim"]     = 0;
$m_inversoes_financeiras_intra["realizar"]   = 0;


$somador_I_inicial      = 0;
$somador_I_atualizada   = 0;
$somador_I_nobim        = 0;
$somador_I_atebim       = 0;
$somador_I_realizar     = 0;

$somador_II_inicial     = 0;
$somador_II_atualizada  = 0;
$somador_II_nobim       = 0;
$somador_II_atebim      = 0;
$somador_II_realizar    = 0;

$somador_III_inicial    = 0;
$somador_III_atualizada = 0;
$somador_III_nobim      = 0;
$somador_III_atebim     = 0;
$somador_III_realizar   = 0;

$somador_IV_inicial     = 0;
$somador_IV_atualizada  = 0;
$somador_IV_nobim       = 0;
$somador_IV_atebim      = 0;
$somador_IV_realizar    = 0;

$somador_V_inicial      = 0;
$somador_V_atualizada   = 0;
$somador_V_nobim        = 0;
$somador_V_atebim       = 0;
$somador_V_realizar     = 0;

// despesas
$somador_VI_inicial     = 0;
$somador_VI_adicional   = 0;
$somador_VI_emp_nobim   = 0;
$somador_VI_emp_atebim  = 0;
$somador_VI_liq_nobim   = 0;
$somador_VI_liqatebim   = 0;
$somador_VI_inscritos   = 0;

$somador_VII_inicial    = 0;
$somador_VII_adicional  = 0;
$somador_VII_emp_nobim  = 0;
$somador_VII_emp_atebim = 0;
$somador_VII_liq_nobim  = 0;
$somador_VII_liqatebim  = 0;
$somador_VII_inscritos  = 0;
$somador_IX_inicial     = 0;
$somador_IX_adicional   = 0;
$somador_IX_emp_nobim   = 0;
$somador_IX_emp_atebim  = 0;
$somador_IX_liq_nobim   = 0;
$somador_IX_liqatebim   = 0;
$somador_IX_inscritos   = 0;
// ----------------------- // -------------------------- // -----------------------

$db_filtro   = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_rec  = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);

$sele_work   = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result_desp = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$xinstit    = split("-",$db_selinstit);
$resultinst = db_query("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
db_fieldsmemory($resultinst,0);

$head2 = "MUNICÍPIO DE ".$munic;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "BALANÇO ORÇAMENTÁRIO";
$head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$txt   = strtoupper(db_mes('01'));
$dt    = split("-",$dt_fin);
$txt  .= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";
$dt    = split("-",$dt_ini);
$txt  .= strtoupper(db_mes($dt[1]))."-";
$dt    = split("-",$dt_fin);
$txt  .= strtoupper(db_mes($dt[1]));
$head6 = "$txt";
////////////////////////// ///////////////////

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setAutoPageBreak(false);
$pdf->addpage();

$total    = 0;
$troca    = 1;
$alt      = 4;
$dataini  = $dt_ini;
$datafin  = $dt_fin;
$pagina   = 1;
$tottotal = 0;

$pdf->setfont('arial','',6);
$pdf->cell(170,$alt,"RREO - Anexo I (LRF, Art. 52, inciso I, alíneas \"a\" e \"b\" do inciso II e §1º)",'0',0,"L",0);
$pdf->cell(20,$alt,"R$ 1,00",'0',1,"R",0);

imprime_cabec_rec($alt,&$pdf);

//--------------------------------
$pos_rec_intra = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS (EXCETO INTRA-ORÇAMENTÁRIAS) (I)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------


//--------------------------------
$pos_rec_correntes = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA TRIBUTÁRIA",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;

atualizaTotaisReceita($m_impostos["parametros"] ,&$m_impostos_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_impostos_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_impostos_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_impostos_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_impostos_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_impostos_intra["realizar"];

$rec_trib_II_inicial_intra    += $m_impostos_intra["inicial"];
$rec_trib_II_atualizada_intra += $m_impostos_intra["atualizada"];
$rec_trib_II_nobim_intra      += $m_impostos_intra["nobim"];
$rec_trib_II_atebim_intra     += $m_impostos_intra["atebim"];
$rec_trib_II_realizar_intra   += $m_impostos_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Impostos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;

// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_taxas["parametros"] ,&$m_taxas_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_taxas_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_taxas_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_taxas_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_taxas_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_taxas_intra["realizar"];

$rec_trib_II_inicial_intra    += $m_taxas_intra["inicial"];
$rec_trib_II_atualizada_intra += $m_taxas_intra["atualizada"];
$rec_trib_II_nobim_intra      += $m_taxas_intra["nobim"];
$rec_trib_II_atebim_intra     += $m_taxas_intra["atebim"];
$rec_trib_II_realizar_intra   += $m_taxas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Taxas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_melhorias["parametros"] ,&$m_melhorias_intra, $result_rec);

$rec_cor_II_inicial_intra    += $m_melhorias_intra["inicial"];
$rec_cor_II_atualizada_intra += $m_melhorias_intra["atualizada"];
$rec_cor_II_nobim_intra      += $m_melhorias_intra["nobim"];
$rec_cor_II_atebim_intra     += $m_melhorias_intra["atebim"];
$rec_cor_II_realizar_intra   += $m_melhorias_intra["realizar"];

$rec_trib_II_inicial_intra    += $m_melhorias_intra["inicial"];
$rec_trib_II_atualizada_intra += $m_melhorias_intra["atualizada"];
$rec_trib_II_nobim_intra      += $m_melhorias_intra["nobim"];
$rec_trib_II_atebim_intra     += $m_melhorias_intra["atebim"];
$rec_trib_II_realizar_intra   += $m_melhorias_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Contribuição de Melhoria",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
// --------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA DE CONTRIBUIÇÕES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_sociais["parametros"] ,&$m_sociais_intra, $result_rec);

$rec_cor_II_inicial_intra      += $m_sociais_intra["inicial"];
$rec_cor_II_atualizada_intra   += $m_sociais_intra["atualizada"];
$rec_cor_II_nobim_intra        += $m_sociais_intra["nobim"];
$rec_cor_II_atebim_intra       += $m_sociais_intra["atebim"];
$rec_cor_II_realizar_intra     += $m_sociais_intra["realizar"];

$rec_contr_II_inicial_intra    += $m_sociais_intra["inicial"];
$rec_contr_II_atualizada_intra += $m_sociais_intra["atualizada"];
$rec_contr_II_nobim_intra      += $m_sociais_intra["nobim"];
$rec_contr_II_atebim_intra     += $m_sociais_intra["atebim"];
$rec_contr_II_realizar_intra   += $m_sociais_intra["realizar"];

$pdf->setfont('arial','',6);

// @todo - ajusta output

$pdf->cell(60,$alt,espaco($n2)."Contribuições Sociais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
// ;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_economicas["parametros"] ,&$m_economicas_intra, $result_rec);

$rec_cor_II_inicial_intra      += $m_economicas_intra["inicial"];
$rec_cor_II_atualizada_intra   += $m_economicas_intra["atualizada"];
$rec_cor_II_nobim_intra        += $m_economicas_intra["nobim"];
$rec_cor_II_atebim_intra       += $m_economicas_intra["atebim"];
$rec_cor_II_realizar_intra     += $m_economicas_intra["realizar"];

$rec_contr_II_inicial_intra    += $m_economicas_intra["inicial"];
$rec_contr_II_atualizada_intra += $m_economicas_intra["atualizada"];
$rec_contr_II_nobim_intra      += $m_economicas_intra["nobim"];
$rec_contr_II_atebim_intra     += $m_economicas_intra["atebim"];
$rec_contr_II_realizar_intra   += $m_economicas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Contribuições de Intervenção no Domínio Econômico",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_iluminacao_publica["parametros"] ,&$m_iluminacao_publica_intra, $result_rec);

$rec_cor_II_inicial_intra      += $m_iluminacao_publica_intra["inicial"];
$rec_cor_II_atualizada_intra   += $m_iluminacao_publica_intra["atualizada"];
$rec_cor_II_nobim_intra        += $m_iluminacao_publica_intra["nobim"];
$rec_cor_II_atebim_intra       += $m_iluminacao_publica_intra["atebim"];
$rec_cor_II_realizar_intra     += $m_iluminacao_publica_intra["realizar"];

$rec_contr_II_inicial_intra    += $m_iluminacao_publica_intra["inicial"];
$rec_contr_II_atualizada_intra += $m_iluminacao_publica_intra["atualizada"];
$rec_contr_II_nobim_intra      += $m_iluminacao_publica_intra["nobim"];
$rec_contr_II_atebim_intra     += $m_iluminacao_publica_intra["atebim"];
$rec_contr_II_realizar_intra   += $m_iluminacao_publica_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Contribuições de Iluminação Pública",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//-------------------------------------------------------------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki

// --------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA PATRIMONIAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_imobiliarias["parametros"] ,&$m_imobiliarias_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_imobiliarias_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_imobiliarias_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_imobiliarias_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_imobiliarias_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_imobiliarias_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_imobiliarias_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_imobiliarias_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_imobiliarias_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_imobiliarias_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_imobiliarias_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas Imobiliárias",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_valmobiliarias["parametros"] ,&$m_valmobiliarias_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_valmobiliarias_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_valmobiliarias_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_valmobiliarias_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_valmobiliarias_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_valmobiliarias_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_valmobiliarias_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_valmobiliarias_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_valmobiliarias_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_valmobiliarias_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_valmobiliarias_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita de Valores Mobiliários",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_permissoes["parametros"] ,&$m_permissoes_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_permissoes_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_permissoes_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_permissoes_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_permissoes_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_permissoes_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_permissoes_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_permissoes_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_permissoes_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_permissoes_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_permissoes_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita de Concessões e Permissões",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_comp_financeiras["parametros"] ,&$m_comp_financeiras_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_comp_financeiras_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_comp_financeiras_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_comp_financeiras_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_comp_financeiras_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_comp_financeiras_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_comp_financeiras_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_comp_financeiras_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_comp_financeiras_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_comp_financeiras_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_comp_financeiras_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Compensações Financeiras",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_receita_decorrente_direito["parametros"] ,&$m_receita_decorrente_direito_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_receita_decorrente_direito_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_receita_decorrente_direito_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_receita_decorrente_direito_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_receita_decorrente_direito_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_receita_decorrente_direito_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_receita_decorrente_direito_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_receita_decorrente_direito_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_receita_decorrente_direito_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_receita_decorrente_direito_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_receita_decorrente_direito_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Rec. Dec. Direito Expl. Bens Pub. Áreas Dom. Público",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


// --------------------------------*****

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_receita_cessao_direitos["parametros"] ,&$m_receita_cessao_direitos_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_receita_cessao_direitos_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_receita_cessao_direitos_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_receita_cessao_direitos_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_receita_cessao_direitos_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_receita_cessao_direitos_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_receita_cessao_direitos_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_receita_cessao_direitos_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_receita_cessao_direitos_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_receita_cessao_direitos_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_receita_cessao_direitos_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita De Cessão De Direitos",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------


$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_patrimoniais["parametros"] ,&$m_patrimoniais_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_patrimoniais_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_patrimoniais_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_patrimoniais_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_patrimoniais_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_patrimoniais_intra["realizar"];

$rec_patr_II_inicial_intra    += $m_patrimoniais_intra["inicial"];
$rec_patr_II_atualizada_intra += $m_patrimoniais_intra["atualizada"];
$rec_patr_II_nobim_intra      += $m_patrimoniais_intra["nobim"];
$rec_patr_II_atebim_intra     += $m_patrimoniais_intra["atebim"];
$rec_patr_II_realizar_intra   += $m_patrimoniais_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Outras Receitas Patrimoniais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki


// --------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA AGROPECUÁRIA",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_vegetal["parametros"] ,&$m_vegetal_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_vegetal_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_vegetal_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_vegetal_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_vegetal_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_vegetal_intra["realizar"];

$rec_agro_II_inicial_intra    += $m_vegetal_intra["inicial"];
$rec_agro_II_atualizada_intra += $m_vegetal_intra["atualizada"];
$rec_agro_II_nobim_intra      += $m_vegetal_intra["nobim"];
$rec_agro_II_atebim_intra     += $m_vegetal_intra["atebim"];
$rec_agro_II_realizar_intra   += $m_vegetal_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Produção Vegetal",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_animal["parametros"] ,&$m_animal_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_animal_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_animal_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_animal_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_animal_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_animal_intra["realizar"];

$rec_agro_II_inicial_intra    += $m_animal_intra["inicial"];
$rec_agro_II_atualizada_intra += $m_animal_intra["atualizada"];
$rec_agro_II_nobim_intra      += $m_animal_intra["nobim"];
$rec_agro_II_atebim_intra     += $m_animal_intra["atebim"];
$rec_agro_II_realizar_intra   += $m_animal_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Produção Animal e Derivados",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_agropecuarias["parametros"] ,&$m_agropecuarias_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_agropecuarias_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_agropecuarias_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_agropecuarias_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_agropecuarias_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_agropecuarias_intra["realizar"];

$rec_agro_II_inicial_intra    += $m_agropecuarias_intra["inicial"];
$rec_agro_II_atualizada_intra += $m_agropecuarias_intra["atualizada"];
$rec_agro_II_nobim_intra      += $m_agropecuarias_intra["nobim"];
$rec_agro_II_atebim_intra     += $m_agropecuarias_intra["atebim"];
$rec_agro_II_realizar_intra   += $m_agropecuarias_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Outras Receitas Agropecuárias",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA INDUSTRIAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);

//-------------------------------

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_industria_extrativa_mineral["parametros"] ,&$m_industria_extrativa_mineral_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_industria_extrativa_mineral_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_industria_extrativa_mineral_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_industria_extrativa_mineral_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_industria_extrativa_mineral_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_industria_extrativa_mineral_intra["realizar"];

$rec_ind_II_inicial_intra     += $m_industria_extrativa_mineral_intra["inicial"];
$rec_ind_II_atualizada_intra  += $m_industria_extrativa_mineral_intra["atualizada"];
$rec_ind_II_nobim_intra       += $m_industria_extrativa_mineral_intra["nobim"];
$rec_ind_II_atebim_intra      += $m_industria_extrativa_mineral_intra["atebim"];
$rec_ind_II_realizar_intra    += $m_industria_extrativa_mineral_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Indústria Extrativa Mineral",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
//--------------------------------

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transformacao["parametros"] ,&$m_transformacao_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_transformacao_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_transformacao_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_transformacao_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_transformacao_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_transformacao_intra["realizar"];

$rec_ind_II_inicial_intra     += $m_transformacao_intra["inicial"];
$rec_ind_II_atualizada_intra  += $m_transformacao_intra["atualizada"];
$rec_ind_II_nobim_intra       += $m_transformacao_intra["nobim"];
$rec_ind_II_atebim_intra      += $m_transformacao_intra["atebim"];
$rec_ind_II_realizar_intra    += $m_transformacao_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Indústria de Transformação",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;


//--------------------------------

$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_construcao["parametros"] ,&$m_construcao_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_construcao_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_construcao_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_construcao_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_construcao_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_construcao_intra["realizar"];

$rec_ind_II_inicial_intra     += $m_construcao_intra["inicial"];
$rec_ind_II_atualizada_intra  += $m_construcao_intra["atualizada"];
$rec_ind_II_nobim_intra       += $m_construcao_intra["nobim"];
$rec_ind_II_atebim_intra      += $m_construcao_intra["atebim"];
$rec_ind_II_realizar_intra    += $m_construcao_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Indústria de Construção",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_industriais["parametros"] ,&$m_industriais_intra, $result_rec);

$rec_cor_II_inicial_intra     += $m_industriais_intra["inicial"];
$rec_cor_II_atualizada_intra  += $m_industriais_intra["atualizada"];
$rec_cor_II_nobim_intra       += $m_industriais_intra["nobim"];
$rec_cor_II_atebim_intra      += $m_industriais_intra["atebim"];
$rec_cor_II_realizar_intra    += $m_industriais_intra["realizar"];

$rec_ind_II_inicial_intra     += $m_industriais_intra["inicial"];
$rec_ind_II_atualizada_intra  += $m_industriais_intra["atualizada"];
$rec_ind_II_nobim_intra       += $m_industriais_intra["nobim"];
$rec_ind_II_atebim_intra      += $m_industriais_intra["atebim"];
$rec_ind_II_realizar_intra    += $m_industriais_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Outras Receitas Industriais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."RECEITA DE SERVIÇOS",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_servicos["parametros"] ,&$m_servicos_intra, $result_rec);

$rec_cor_II_inicial_intra    += $m_servicos_intra["inicial"];
$rec_cor_II_atualizada_intra += $m_servicos_intra["atualizada"];
$rec_cor_II_nobim_intra      += $m_servicos_intra["nobim"];
$rec_cor_II_atebim_intra     += $m_servicos_intra["atebim"];
$rec_cor_II_realizar_intra   += $m_servicos_intra["realizar"];

$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_intergovernamental["parametros"] ,&$m_intergovernamental_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_intergovernamental_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_intergovernamental_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_intergovernamental_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_intergovernamental_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_intergovernamental_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_intergovernamental_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_intergovernamental_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_intergovernamental_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_intergovernamental_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_intergovernamental_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências Intergovernamentais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_privadas["parametros"] ,&$m_privadas_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_privadas_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_privadas_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_privadas_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_privadas_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_privadas_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_privadas_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_privadas_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_privadas_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_privadas_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_privadas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;

atualizaTotaisReceita($m_transf_exterior["parametros"] ,&$m_transf_exterior_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_transf_exterior_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_transf_exterior_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_transf_exterior_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_transf_exterior_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_transf_exterior_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_transf_exterior_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_transf_exterior_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_transf_exterior_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_transf_exterior_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_transf_exterior_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_pessoas["parametros"] ,&$m_transf_pessoas_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_transf_pessoas_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_transf_pessoas_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_transf_pessoas_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_transf_pessoas_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_transf_pessoas_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_transf_pessoas_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_transf_pessoas_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_transf_pessoas_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_transf_pessoas_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_transf_pessoas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_convenios["parametros"] ,&$m_transf_convenios_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_transf_convenios_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_transf_convenios_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_transf_convenios_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_transf_convenios_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_transf_convenios_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_transf_convenios_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_transf_convenios_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_transf_convenios_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_transf_convenios_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_transf_convenios_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Convenios",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_fome["parametros"] ,&$m_transf_fome_intra, $result_rec);

$oLinha = $m_transf_fome["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $tot_inicial    += $oColunas->colunas[1]->o117_valor;
  $tot_atualizada += $oColunas->colunas[2]->o117_valor;
  $tot_nobim      += $oColunas->colunas[3]->o117_valor;
  $tot_atebim     += $oColunas->colunas[4]->o117_valor;
  //$tot_realizar   += $oColunas->colunas[5]->o117_valor;

  if ($bimestre == 6) {

    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp     += $oColunas->colunas[7]->o117_valor;
    }
  }

}

$rec_cor_II_inicial_intra           += $m_transf_fome_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_transf_fome_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_transf_fome_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_transf_fome_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_transf_fome_intra["realizar"];

$rec_transf_cor_II_inicial_intra    += $m_transf_fome_intra["inicial"];
$rec_transf_cor_II_atualizada_intra += $m_transf_fome_intra["atualizada"];
$rec_transf_cor_II_nobim_intra      += $m_transf_fome_intra["nobim"];
$rec_transf_cor_II_atebim_intra     += $m_transf_fome_intra["atebim"];
$rec_transf_cor_II_realizar_intra   += $m_transf_fome_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências para o Combate à Fome",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS CORRENTES",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_multas["parametros"] ,&$m_multas_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_multas_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_multas_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_multas_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_multas_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_multas_intra["realizar"];

$rec_outras_cor_II_inicial_intra    += $m_multas_intra["inicial"];
$rec_outras_cor_II_atualizada_intra += $m_multas_intra["atualizada"];
$rec_outras_cor_II_nobim_intra      += $m_multas_intra["nobim"];
$rec_outras_cor_II_atebim_intra     += $m_multas_intra["atebim"];
$rec_outras_cor_II_realizar_intra   += $m_multas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Multas e Juros de Mora",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
// ;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;


//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_indenizacao["parametros"] ,&$m_indenizacao_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_indenizacao_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_indenizacao_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_indenizacao_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_indenizacao_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_indenizacao_intra["realizar"];

$rec_outras_cor_II_inicial_intra    += $m_indenizacao_intra["inicial"];
$rec_outras_cor_II_atualizada_intra += $m_indenizacao_intra["atualizada"];
$rec_outras_cor_II_nobim_intra      += $m_indenizacao_intra["nobim"];
$rec_outras_cor_II_atebim_intra     += $m_indenizacao_intra["atebim"];
$rec_outras_cor_II_realizar_intra   += $m_indenizacao_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Indenizações e Restituições",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_divida_ativa["parametros"] ,&$m_divida_ativa_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_divida_ativa_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_divida_ativa_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_divida_ativa_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_divida_ativa_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_divida_ativa_intra["realizar"];

$rec_outras_cor_II_inicial_intra    += $m_divida_ativa_intra["inicial"];
$rec_outras_cor_II_atualizada_intra += $m_divida_ativa_intra["atualizada"];
$rec_outras_cor_II_nobim_intra      += $m_divida_ativa_intra["nobim"];
$rec_outras_cor_II_atebim_intra     += $m_divida_ativa_intra["atebim"];
$rec_outras_cor_II_realizar_intra   += $m_divida_ativa_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receita da Dívida Ativa",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------


//***

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_receita_decorrente_aportes["parametros"] ,&$m_receita_decorrente_aportes_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_receita_decorrente_aportes_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_receita_decorrente_aportes_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_receita_decorrente_aportes_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_receita_decorrente_aportes_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_receita_decorrente_aportes_intra["realizar"];

$rec_outras_cor_II_inicial_intra    += $m_receita_decorrente_aportes_intra["inicial"];
$rec_outras_cor_II_atualizada_intra += $m_receita_decorrente_aportes_intra["atualizada"];
$rec_outras_cor_II_nobim_intra      += $m_receita_decorrente_aportes_intra["nobim"];
$rec_outras_cor_II_atebim_intra     += $m_receita_decorrente_aportes_intra["atebim"];
$rec_outras_cor_II_realizar_intra   += $m_receita_decorrente_aportes_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Rec. Dec. Aportes Per. Amort. Def. Atuarial RPPS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------

















$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_correntes_diversas["parametros"] ,&$m_correntes_diversas_intra, $result_rec);

$rec_cor_II_inicial_intra           += $m_correntes_diversas_intra["inicial"];
$rec_cor_II_atualizada_intra        += $m_correntes_diversas_intra["atualizada"];
$rec_cor_II_nobim_intra             += $m_correntes_diversas_intra["nobim"];
$rec_cor_II_atebim_intra            += $m_correntes_diversas_intra["atebim"];
$rec_cor_II_realizar_intra          += $m_correntes_diversas_intra["realizar"];

$rec_outras_cor_II_inicial_intra    += $m_correntes_diversas_intra["inicial"];
$rec_outras_cor_II_atualizada_intra += $m_correntes_diversas_intra["atualizada"];
$rec_outras_cor_II_nobim_intra      += $m_correntes_diversas_intra["nobim"];
$rec_outras_cor_II_atebim_intra     += $m_correntes_diversas_intra["atebim"];
$rec_outras_cor_II_realizar_intra   += $m_correntes_diversas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas Correntes Diversas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//-------------------------------- * --------------------------------------------------
// guarda o total das receitas correntes
$rec_cor_I_inicial    = $somador_I_inicial ;
$rec_cor_I_atualizada = $somador_I_atualizada ;
// ;
$rec_cor_I_nobim      = $somador_I_nobim  ;
$rec_cor_I_atebim     = $somador_I_atebim ;
$rec_cor_I_realizar   = $somador_I_realizar ;

$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_rec_correntes);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_cor_I_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($rec_cor_I_nobim*100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_cor_I_atebim ,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($rec_cor_I_atebim *100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//-------------------------------- * --------------------------------------------------
//--------------------------------
$pos_rec_capital = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OPERAÇÕES DE CRÉDITO",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_internas["parametros"] ,&$m_oper_internas_intra, $result_rec);

$rec_cap_II_inicial_intra              += $m_oper_internas_intra["inicial"];
$rec_cap_II_atualizada_intra           += $m_oper_internas_intra["atualizada"];
$rec_cap_II_nobim_intra                += $m_oper_internas_intra["nobim"];
$rec_cap_II_atebim_intra               += $m_oper_internas_intra["atebim"];
$rec_cap_II_realizar_intra             += $m_oper_internas_intra["realizar"];

$rec_oper_cred_cap_II_inicial_intra    += $m_oper_internas_intra["inicial"];
$rec_oper_cred_cap_II_atualizada_intra += $m_oper_internas_intra["atualizada"];
$rec_oper_cred_cap_II_nobim_intra      += $m_oper_internas_intra["nobim"];
$rec_oper_cred_cap_II_atebim_intra     += $m_oper_internas_intra["atebim"];
$rec_oper_cred_cap_II_realizar_intra   += $m_oper_internas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Internas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;



//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_externas["parametros"] ,&$m_oper_externas_intra, $result_rec);

$rec_cap_II_inicial_intra              += $m_oper_externas_intra["inicial"];
$rec_cap_II_atualizada_intra           += $m_oper_externas_intra["atualizada"];
$rec_cap_II_nobim_intra                += $m_oper_externas_intra["nobim"];
$rec_cap_II_atebim_intra               += $m_oper_externas_intra["atebim"];
$rec_cap_II_realizar_intra             += $m_oper_externas_intra["realizar"];

$rec_oper_cred_cap_II_inicial_intra    += $m_oper_externas_intra["inicial"];
$rec_oper_cred_cap_II_atualizada_intra += $m_oper_externas_intra["atualizada"];
$rec_oper_cred_cap_II_nobim_intra      += $m_oper_externas_intra["nobim"];
$rec_oper_cred_cap_II_atebim_intra     += $m_oper_externas_intra["atebim"];
$rec_oper_cred_cap_II_realizar_intra   += $m_oper_externas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Externas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."ALIENAÇÃO DE BENS",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;
atualizaTotaisReceita($m_bens_moveis["parametros"] ,&$m_bens_moveis_intra, $result_rec);

$rec_cap_II_inicial_intra               += $m_bens_moveis_intra["inicial"];
$rec_cap_II_atualizada_intra            += $m_bens_moveis_intra["atualizada"];
$rec_cap_II_nobim_intra                 += $m_bens_moveis_intra["nobim"];
$rec_cap_II_atebim_intra                += $m_bens_moveis_intra["atebim"];
$rec_cap_II_realizar_intra              += $m_bens_moveis_intra["realizar"];

$rec_alien_bens_cap_II_inicial_intra    += $m_bens_moveis_intra["inicial"];
$rec_alien_bens_cap_II_atualizada_intra += $m_bens_moveis_intra["atualizada"];
$rec_alien_bens_cap_II_nobim_intra      += $m_bens_moveis_intra["nobim"];
$rec_alien_bens_cap_II_atebim_intra     += $m_bens_moveis_intra["atebim"];
$rec_alien_bens_cap_II_realizar_intra   += $m_bens_moveis_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Móveis",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
// ;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_bens_imoveis["parametros"] ,&$m_bens_imoveis_intra, $result_rec);

$rec_cap_II_inicial_intra               += $m_bens_imoveis_intra["inicial"];
$rec_cap_II_atualizada_intra            += $m_bens_imoveis_intra["atualizada"];
$rec_cap_II_nobim_intra                 += $m_bens_imoveis_intra["nobim"];
$rec_cap_II_atebim_intra                += $m_bens_imoveis_intra["atebim"];
$rec_cap_II_realizar_intra              += $m_bens_imoveis_intra["realizar"];

$rec_alien_bens_cap_II_inicial_intra    += $m_bens_imoveis_intra["inicial"];
$rec_alien_bens_cap_II_atualizada_intra += $m_bens_imoveis_intra["atualizada"];
$rec_alien_bens_cap_II_nobim_intra      += $m_bens_imoveis_intra["nobim"];
$rec_alien_bens_cap_II_atebim_intra     += $m_bens_imoveis_intra["atebim"];
$rec_alien_bens_cap_II_realizar_intra   += $m_bens_imoveis_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Imóveis",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

$pos_atu = $pdf->y;
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_emprestimos["parametros"] ,&$m_emprestimos_intra, $result_rec);

$rec_cap_II_inicial_intra               += $m_emprestimos_intra["inicial"];
$rec_cap_II_atualizada_intra            += $m_emprestimos_intra["atualizada"];
$rec_cap_II_nobim_intra                 += $m_emprestimos_intra["nobim"];
$rec_cap_II_atebim_intra                += $m_emprestimos_intra["atebim"];
$rec_cap_II_realizar_intra              += $m_emprestimos_intra["realizar"];

$rec_alien_bens_cap_II_inicial_intra    += $m_emprestimos_intra["inicial"];
$rec_alien_bens_cap_II_atualizada_intra += $m_emprestimos_intra["atualizada"];
$rec_alien_bens_cap_II_nobim_intra      += $m_emprestimos_intra["nobim"];
$rec_alien_bens_cap_II_atebim_intra     += $m_emprestimos_intra["atebim"];
$rec_alien_bens_cap_II_realizar_intra   += $m_emprestimos_intra["realizar"];

// --------------------------------
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."AMORTIZAÇÕES DE EMPRÉSTIMOS",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),'0',1,"R",0);

// --------------------------------

$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

// --------------------------------
// posição atual
// sobe, escreve e desce
// desce novamente até aki

//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_intergovernamentais["parametros"] ,&$m_transf_capital_intergovernamentais_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_intergovernamentais_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_intergovernamentais_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_intergovernamentais_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_intergovernamentais_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_intergovernamentais_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_intergovernamentais_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_intergovernamentais_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_intergovernamentais_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_intergovernamentais_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_intergovernamentais_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências Intergovernamentais",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
// ;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_privadas["parametros"] ,&$m_transf_capital_privadas_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_privadas_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_privadas_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_privadas_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_privadas_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_privadas_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_privadas_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_privadas_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_privadas_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_privadas_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_privadas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_exterior["parametros"] ,&$m_transf_capital_exterior_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_exterior_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_exterior_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_exterior_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_exterior_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_exterior_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_exterior_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_exterior_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_exterior_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_exterior_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_exterior_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_pessoas["parametros"] ,&$m_transf_capital_pessoas_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_pessoas_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_pessoas_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_pessoas_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_pessoas_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_pessoas_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_pessoas_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_pessoas_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_pessoas_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_pessoas_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_pessoas_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_outras["parametros"] ,&$m_transf_capital_outras_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_outras_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_outras_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_outras_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_outras_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_outras_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_outras_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_outras_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_outras_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_outras_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_outras_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Outras Instituições Públicas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_convenios["parametros"] ,&$m_transf_capital_convenios_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_transf_capital_convenios_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_convenios_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_convenios_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_convenios_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_convenios_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_convenios_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_convenios_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_convenios_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_convenios_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_convenios_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências de Convênios",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_transf_capital_fome["parametros"] ,&$m_transf_capital_fome_intra, $result_rec);

$oLinha = $m_transf_capital_fome["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $tot_inicial    += $oColunas->colunas[1]->o117_valor;
  $tot_atualizada += $oColunas->colunas[2]->o117_valor;
  $tot_nobim      += $oColunas->colunas[3]->o117_valor;
  $tot_atebim     += $oColunas->colunas[4]->o117_valor;

}


$rec_cap_II_inicial_intra           += $m_transf_capital_fome_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_transf_capital_fome_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_transf_capital_fome_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_transf_capital_fome_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_transf_capital_fome_intra["realizar"];

$rec_transf_cap_II_inicial_intra    += $m_transf_capital_fome_intra["inicial"];
$rec_transf_cap_II_atualizada_intra += $m_transf_capital_fome_intra["atualizada"];
$rec_transf_cap_II_nobim_intra      += $m_transf_capital_fome_intra["nobim"];
$rec_transf_cap_II_atebim_intra     += $m_transf_capital_fome_intra["atebim"];
$rec_transf_cap_II_realizar_intra   += $m_transf_capital_fome_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Transferências para o Combate à Fome",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS DE CAPITAL",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_outras_social["parametros"] ,&$m_outras_social_intra, $result_rec);

$oLinha = $m_outras_social["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $tot_inicial    += $oColunas->colunas[1]->o117_valor;
  $tot_atualizada += $oColunas->colunas[2]->o117_valor;
  $tot_nobim      += $oColunas->colunas[3]->o117_valor;
  $tot_atebim     += $oColunas->colunas[4]->o117_valor;

}

$rec_cap_II_inicial_intra           += $m_outras_social_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_outras_social_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_outras_social_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_outras_social_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_outras_social_intra["realizar"];

$rec_outras_cap_II_inicial_intra    += $m_outras_social_intra["inicial"];
$rec_outras_cap_II_atualizada_intra += $m_outras_social_intra["atualizada"];
$rec_outras_cap_II_nobim_intra      += $m_outras_social_intra["nobim"];
$rec_outras_cap_II_atebim_intra     += $m_outras_social_intra["atebim"];
$rec_outras_cap_II_realizar_intra   += $m_outras_social_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Integralização do Capital Social",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
// ;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_outras_disponibilidades["parametros"] ,&$m_outras_disponibilidades_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_outras_disponibilidades_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_outras_disponibilidades_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_outras_disponibilidades_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_outras_disponibilidades_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_outras_disponibilidades_intra["realizar"];

$rec_outras_cap_II_inicial_intra    += $m_outras_disponibilidades_intra["inicial"];
$rec_outras_cap_II_atualizada_intra += $m_outras_disponibilidades_intra["atualizada"];
$rec_outras_cap_II_nobim_intra      += $m_outras_disponibilidades_intra["nobim"];
$rec_outras_cap_II_atebim_intra     += $m_outras_disponibilidades_intra["atebim"];
$rec_outras_cap_II_realizar_intra   += $m_outras_disponibilidades_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Div. Atv. Prov. da Amortiz. de Emp. e Financ.",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);
$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_outras_diversas["parametros"] ,&$m_outras_diversas_intra, $result_rec);

$rec_cap_II_inicial_intra           += $m_outras_diversas_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_outras_diversas_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_outras_diversas_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_outras_diversas_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_outras_diversas_intra["realizar"];

$rec_outras_cap_II_inicial_intra    += $m_outras_diversas_intra["inicial"];
$rec_outras_cap_II_atualizada_intra += $m_outras_diversas_intra["atualizada"];
$rec_outras_cap_II_nobim_intra      += $m_outras_diversas_intra["nobim"];
$rec_outras_cap_II_atebim_intra     += $m_outras_diversas_intra["atebim"];
$rec_outras_cap_II_realizar_intra   += $m_outras_diversas_intra["realizar"];

$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim     += $tot_atebim;
$somador_I_realizar   += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//-------------------------------- * --------------------------------------------------
// guarda o total das receitas correntes
$rec_cor_I_inicial    = $somador_I_inicial-$rec_cor_I_inicial ;
$rec_cor_I_atualizada = $somador_I_atualizada-$rec_cor_I_atualizada ;
// ;
$rec_cor_I_nobim      = $somador_I_nobim-$rec_cor_I_nobim   ;
$rec_cor_I_atebim     = $somador_I_atebim-$rec_cor_I_atebim ;
$rec_cor_I_realizar   = $somador_I_realizar-$rec_cor_I_realizar ;

$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
// ESCREVE O TOTAL NA LINHA DE RECEITAS DE CAPITAL
$pdf->setY($pos_rec_capital);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_cor_I_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($rec_cor_I_nobim*100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($rec_cor_I_atebim ,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($rec_cor_I_atebim *100)/$rec_cor_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($rec_cor_I_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
// grava valores da nao intra
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
// ESCREVE O TOTAL NA LINHA DE RECEITAS DE CAPITAL
$pdf->setY($pos_rec_intra);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($somador_I_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_I_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_nobim*100)/$somador_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_I_atebim ,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_atebim *100)/$somador_I_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
//-------------------------------- * --------------------------------------------------
//
$pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","T",1,"R",0);
$pdf->AddPage();
$pdf->cell(190,$alt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0);

imprime_cabec_rec($alt,&$pdf);

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Receitas de Capital Diversas",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS (INTRA-ORÇAMENTÁRIAS) (II)",'R',0,"L",0);

// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

// Impostos Intra-Orcamentario
$tot_inicial    += $m_impostos_intra["inicial"];
$tot_atualizada += $m_impostos_intra["atualizada"];
$tot_nobim      += $m_impostos_intra["nobim"];
$tot_atebim     += $m_impostos_intra["atebim"];
$tot_realizar   += $m_impostos_intra["realizar"];
;
// Taxas Intra-Orcamentaria
$tot_inicial    += $m_taxas_intra["inicial"];
$tot_atualizada += $m_taxas_intra["atualizada"];
$tot_nobim      += $m_taxas_intra["nobim"];
$tot_atebim     += $m_taxas_intra["atebim"];
$tot_realizar   += $m_taxas_intra["realizar"];
;
// Contribuicao de Melhorias Intra-Orcamentaria
$tot_inicial    += $m_melhorias_intra["inicial"];
$tot_atualizada += $m_melhorias_intra["atualizada"];
$tot_nobim      += $m_melhorias_intra["nobim"];
$tot_atebim     += $m_melhorias_intra["atebim"];
$tot_realizar   += $m_melhorias_intra["realizar"];
;
// Contribuicoes Sociais Intra-Orcamentaria
$tot_inicial    += $m_sociais_intra["inicial"];
$tot_atualizada += $m_sociais_intra["atualizada"];
$tot_nobim      += $m_sociais_intra["nobim"];
$tot_atebim     += $m_sociais_intra["atebim"];
$tot_realizar   += $m_sociais_intra["realizar"];
;
// Contribuicoes Economicas Intra-Orcamentaria
$tot_inicial    += $m_economicas_intra["inicial"];
$tot_atualizada += $m_economicas_intra["atualizada"];
$tot_nobim      += $m_economicas_intra["nobim"];
$tot_atebim     += $m_economicas_intra["atebim"];
$tot_realizar   += $m_economicas_intra["realizar"];
;
// Receitas Imobiliarias Intra-Orcamentaria
$tot_inicial    += $m_imobiliarias_intra["inicial"];
$tot_atualizada += $m_imobiliarias_intra["atualizada"];
$tot_nobim      += $m_imobiliarias_intra["nobim"];
$tot_atebim     += $m_imobiliarias_intra["atebim"];
$tot_realizar   += $m_imobiliarias_intra["realizar"];
;
// Receita de Valores Mobiliarios Intra-Orcamentario
$tot_inicial    += $m_valmobiliarias_intra["inicial"];
$tot_atualizada += $m_valmobiliarias_intra["atualizada"];
$tot_nobim      += $m_valmobiliarias_intra["nobim"];
$tot_atebim     += $m_valmobiliarias_intra["atebim"];
$tot_realizar   += $m_valmobiliarias_intra["realizar"];
;
// Receita de Concessoes e Permissoes Intra-Orcamentario
$tot_inicial    += $m_permissoes_intra["inicial"];
$tot_atualizada += $m_permissoes_intra["atualizada"];
$tot_nobim      += $m_permissoes_intra["nobim"];
$tot_atebim     += $m_permissoes_intra["atebim"];
$tot_realizar   += $m_permissoes_intra["realizar"];
;
// Receita de Concessoes e Permissoes Intra-Orcamentario
$tot_inicial    += $m_comp_financeiras_intra["inicial"];
$tot_atualizada += $m_comp_financeiras_intra["atualizada"];
$tot_nobim      += $m_comp_financeiras_intra["nobim"];
$tot_atebim     += $m_comp_financeiras_intra["atebim"];
$tot_realizar   += $m_comp_financeiras_intra["realizar"];
;

// Receita Decorrente Direito
$tot_inicial    += $m_receita_decorrente_direito_intra["inicial"];
$tot_atualizada += $m_receita_decorrente_direito_intra["atualizada"];
$tot_nobim      += $m_receita_decorrente_direito_intra["nobim"];
$tot_atebim     += $m_receita_decorrente_direito_intra["atebim"];
$tot_realizar   += $m_receita_decorrente_direito_intra["realizar"];
;

// Outras Receitas Patrimoniais Intra-Orcamentario
$tot_inicial    += $m_patrimoniais_intra["inicial"];
$tot_atualizada += $m_patrimoniais_intra["atualizada"];
$tot_nobim      += $m_patrimoniais_intra["nobim"];
$tot_atebim     += $m_patrimoniais_intra["atebim"];
$tot_realizar   += $m_patrimoniais_intra["realizar"];
;
// Receita da Producao Vegetal Intra-Orcamentaria
$tot_inicial    += $m_vegetal_intra["inicial"];
$tot_atualizada += $m_vegetal_intra["atualizada"];
$tot_nobim      += $m_vegetal_intra["nobim"];
$tot_atebim     += $m_vegetal_intra["atebim"];
$tot_realizar   += $m_vegetal_intra["realizar"];
;
// Receita da Producao Animal e Derviados Intra-Orcamentaria
$tot_inicial    += $m_animal_intra["inicial"];
$tot_atualizada += $m_animal_intra["atualizada"];
$tot_nobim      += $m_animal_intra["nobim"];
$tot_atebim     += $m_animal_intra["atebim"];
$tot_realizar   += $m_animal_intra["realizar"];
;
// Outras Receitas Agropecuarias Intra-Orcamentaria
$tot_inicial    += $m_agropecuarias_intra["inicial"];
$tot_atualizada += $m_agropecuarias_intra["atualizada"];
$tot_nobim      += $m_agropecuarias_intra["nobim"];
$tot_atebim     += $m_agropecuarias_intra["atebim"];
$tot_realizar   += $m_agropecuarias_intra["realizar"];
;
// Receita da Industria de Transformacao Intra-Orcamentaria
$tot_inicial    += $m_transformacao_intra["inicial"];
$tot_atualizada += $m_transformacao_intra["atualizada"];
$tot_nobim      += $m_transformacao_intra["nobim"];
$tot_atebim     += $m_transformacao_intra["atebim"];
$tot_realizar   += $m_transformacao_intra["realizar"];
;
// Receita da Industria de Construcao Intra-Orcamentaria
$tot_inicial    += $m_construcao_intra["inicial"];
$tot_atualizada += $m_construcao_intra["atualizada"];
$tot_nobim      += $m_construcao_intra["nobim"];
$tot_atebim     += $m_construcao_intra["atebim"];
$tot_realizar   += $m_construcao_intra["realizar"];
;
// Outras Receitas Industriais Intra-Orcamentaria
$tot_inicial    += $m_industriais_intra["inicial"];
$tot_atualizada += $m_industriais_intra["atualizada"];
$tot_nobim      += $m_industriais_intra["nobim"];
$tot_atebim     += $m_industriais_intra["atebim"];
$tot_realizar   += $m_industriais_intra["realizar"];
;
// Receita de Servicos Intra-Orcamentaria
$tot_inicial    += $m_servicos_intra["inicial"];
$tot_atualizada += $m_servicos_intra["atualizada"];
$tot_nobim      += $m_servicos_intra["nobim"];
$tot_atebim     += $m_servicos_intra["atebim"];
$tot_realizar   += $m_servicos_intra["realizar"];
;
// Transferencias Intergovernamentais Intra-Orcamentaria
$tot_inicial    += $m_intergovernamental_intra["inicial"];
$tot_atualizada += $m_intergovernamental_intra["atualizada"];
$tot_nobim      += $m_intergovernamental_intra["nobim"];
$tot_atebim     += $m_intergovernamental_intra["atebim"];
$tot_realizar   += $m_intergovernamental_intra["realizar"];
;
// Transferencias de Instituicoes Privadas Intra-Orcamentaria
$tot_inicial    += $m_privadas_intra["inicial"];
$tot_atualizada += $m_privadas_intra["atualizada"];
$tot_nobim      += $m_privadas_intra["nobim"];
$tot_atebim     += $m_privadas_intra["atebim"];
$tot_realizar   += $m_privadas_intra["realizar"];
;
// Transferencias do Exterior Intra-Orcamentaria
$tot_inicial    += $m_transf_exterior_intra["inicial"];
$tot_atualizada += $m_transf_exterior_intra["atualizada"];
$tot_nobim      += $m_transf_exterior_intra["nobim"];
$tot_atebim     += $m_transf_exterior_intra["atebim"];
$tot_realizar   += $m_transf_exterior_intra["realizar"];
;
// Transferencias de Pessoas Intra-Orcamentaria
$tot_inicial    += $m_transf_pessoas_intra["inicial"];
$tot_atualizada += $m_transf_pessoas_intra["atualizada"];
$tot_nobim      += $m_transf_pessoas_intra["nobim"];
$tot_atebim     += $m_transf_pessoas_intra["atebim"];
$tot_realizar   += $m_transf_pessoas_intra["realizar"];
;
// Transferencias de Convenios Intra-Orcamentaria
$tot_inicial    += $m_transf_convenios_intra["inicial"];
$tot_atualizada += $m_transf_convenios_intra["atualizada"];
$tot_nobim      += $m_transf_convenios_intra["nobim"];
$tot_atebim     += $m_transf_convenios_intra["atebim"];
$tot_realizar   += $m_transf_convenios_intra["realizar"];
;
// Transferencias para o Combate a Fome Intra-Orcamentaria
$tot_inicial    += $m_transf_fome_intra["inicial"];
$tot_atualizada += $m_transf_fome_intra["atualizada"];
$tot_nobim      += $m_transf_fome_intra["nobim"];
$tot_atebim     += $m_transf_fome_intra["atebim"];
$tot_realizar   += $m_transf_fome_intra["realizar"];
;
// Multas e Juros de Mora Intra-Orcamentaria
$tot_inicial    += $m_multas_intra["inicial"];
$tot_atualizada += $m_multas_intra["atualizada"];
$tot_nobim      += $m_multas_intra["nobim"];
$tot_atebim     += $m_multas_intra["atebim"];
$tot_realizar   += $m_multas_intra["realizar"];
;
// Indenizacoes e Restituicoes Intra-Orcamentaria
$tot_inicial    += $m_indenizacao_intra["inicial"];
$tot_atualizada += $m_indenizacao_intra["atualizada"];
$tot_nobim      += $m_indenizacao_intra["nobim"];
$tot_atebim     += $m_indenizacao_intra["atebim"];
$tot_realizar   += $m_indenizacao_intra["realizar"];
;
// Receita da Divida Ativa Intra-Orcamentaria
$tot_inicial    += $m_divida_ativa_intra["inicial"];
$tot_atualizada += $m_divida_ativa_intra["atualizada"];
$tot_nobim      += $m_divida_ativa_intra["nobim"];
$tot_atebim     += $m_divida_ativa_intra["atebim"];
$tot_realizar   += $m_divida_ativa_intra["realizar"];
;
// Receitas Correntes Diversas Intra-Orcamentaria
$tot_inicial    += $m_correntes_diversas_intra["inicial"];
$tot_atualizada += $m_correntes_diversas_intra["atualizada"];
$tot_nobim      += $m_correntes_diversas_intra["nobim"];
$tot_atebim     += $m_correntes_diversas_intra["atebim"];
$tot_realizar   += $m_correntes_diversas_intra["realizar"];
;
// Operacoes de Credito Internas Capital Intra-Orcamentaria
$tot_inicial    += $m_oper_internas_intra["inicial"];
$tot_atualizada += $m_oper_internas_intra["atualizada"];
$tot_nobim      += $m_oper_internas_intra["nobim"];
$tot_atebim     += $m_oper_internas_intra["atebim"];
$tot_realizar   += $m_oper_internas_intra["realizar"];
;
// Operacoes de Credito Externas Capital Intra-Orcamentaria
$tot_inicial    += $m_oper_externas_intra["inicial"];
$tot_atualizada += $m_oper_externas_intra["atualizada"];
$tot_nobim      += $m_oper_externas_intra["nobim"];
$tot_atebim     += $m_oper_externas_intra["atebim"];
$tot_realizar   += $m_oper_externas_intra["realizar"];
;
// Alienacao de Bens Moveis Capital Intra-Orcamentaria
$tot_inicial    += $m_bens_moveis_intra["inicial"];
$tot_atualizada += $m_bens_moveis_intra["atualizada"];
$tot_nobim      += $m_bens_moveis_intra["nobim"];
$tot_atebim     += $m_bens_moveis_intra["atebim"];
$tot_realizar   += $m_bens_moveis_intra["realizar"];
;
// Alienacao de Bens Imoveis Capital Intra-Orcamentaria
$tot_inicial    += $m_bens_imoveis_intra["inicial"];
$tot_atualizada += $m_bens_imoveis_intra["atualizada"];
$tot_nobim      += $m_bens_imoveis_intra["nobim"];
$tot_atebim     += $m_bens_imoveis_intra["atebim"];
$tot_realizar   += $m_bens_imoveis_intra["realizar"];
;
// Amortizacoes de Emprestimos Capital Intra-Orcamentaria
$tot_inicial    += $m_emprestimos_intra["inicial"];
$tot_atualizada += $m_emprestimos_intra["atualizada"];
$tot_nobim      += $m_emprestimos_intra["nobim"];
$tot_atebim     += $m_emprestimos_intra["atebim"];
$tot_realizar   += $m_emprestimos_intra["realizar"];
;
// Transferencias Intergovernamentais Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_intergovernamentais_intra["inicial"];
$tot_atualizada += $m_transf_capital_intergovernamentais_intra["atualizada"];
$tot_nobim      += $m_transf_capital_intergovernamentais_intra["nobim"];
$tot_atebim     += $m_transf_capital_intergovernamentais_intra["atebim"];
$tot_realizar   += $m_transf_capital_intergovernamentais_intra["realizar"];
;
// Transferencias de Instituicoes Privadas Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_privadas_intra["inicial"];
$tot_atualizada += $m_transf_capital_privadas_intra["atualizada"];
$tot_nobim      += $m_transf_capital_privadas_intra["nobim"];
$tot_atebim     += $m_transf_capital_privadas_intra["atebim"];
$tot_realizar   += $m_transf_capital_privadas_intra["realizar"];
;
// Transferencias do Exterior Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_exterior_intra["inicial"];
$tot_atualizada += $m_transf_capital_exterior_intra["atualizada"];
$tot_nobim      += $m_transf_capital_exterior_intra["nobim"];
$tot_atebim     += $m_transf_capital_exterior_intra["atebim"];
$tot_realizar   += $m_transf_capital_exterior_intra["realizar"];
;
// Transferencias do Exterior Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_pessoas_intra["inicial"];
$tot_atualizada += $m_transf_capital_pessoas_intra["atualizada"];
$tot_nobim      += $m_transf_capital_pessoas_intra["nobim"];
$tot_atebim     += $m_transf_capital_pessoas_intra["atebim"];
$tot_realizar   += $m_transf_capital_pessoas_intra["realizar"];
;
// Transferencias de Outras Instituicoes Publicas Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_outras_intra["inicial"];
$tot_atualizada += $m_transf_capital_outras_intra["atualizada"];
$tot_nobim      += $m_transf_capital_outras_intra["nobim"];
$tot_atebim     += $m_transf_capital_outras_intra["atebim"];
$tot_realizar   += $m_transf_capital_outras_intra["realizar"];

// Transferencias de Convenios Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_convenios_intra["inicial"];
$tot_atualizada += $m_transf_capital_convenios_intra["atualizada"];
$tot_nobim      += $m_transf_capital_convenios_intra["nobim"];
$tot_atebim     += $m_transf_capital_convenios_intra["atebim"];
$tot_realizar   += $m_transf_capital_convenios_intra["realizar"];

// Transferencias para o Combate a fome Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_fome_intra["inicial"];
$tot_atualizada += $m_transf_capital_fome_intra["atualizada"];
$tot_nobim      += $m_transf_capital_fome_intra["nobim"];
$tot_atebim     += $m_transf_capital_fome_intra["atebim"];
$tot_realizar   += $m_transf_capital_fome_intra["realizar"];

// Integralizacao do Capital Social Intra-Orcamentaria
$tot_inicial    += $m_outras_social_intra["inicial"];
$tot_atualizada += $m_outras_social_intra["atualizada"];
$tot_nobim      += $m_outras_social_intra["nobim"];
$tot_atebim     += $m_outras_social_intra["atebim"];
$tot_realizar   += $m_outras_social_intra["realizar"];

// Dív. Atv. Prov. da Amortiz. de Emp. e Financ. Intra-Orcamentaria
$tot_inicial    += $m_outras_disponibilidades_intra["inicial"];
$tot_atualizada += $m_outras_disponibilidades_intra["atualizada"];
$tot_nobim      += $m_outras_disponibilidades_intra["nobim"];
$tot_atebim     += $m_outras_disponibilidades_intra["atebim"];
$tot_realizar   += $m_outras_disponibilidades_intra["realizar"];

// Restituicoes Intra-Orcamentaria
$tot_inicial    += $m_outras_restituicoes_intra["inicial"];
$tot_atualizada += $m_outras_restituicoes_intra["atualizada"];
$tot_nobim      += $m_outras_restituicoes_intra["nobim"];
$tot_atebim     += $m_outras_restituicoes_intra["atebim"];
$tot_realizar   += $m_outras_restituicoes_intra["realizar"];

// Receitas de Capital Diversas Intra-Orcamentaria
$tot_inicial    += $m_outras_diversas_intra["inicial"];
$tot_atualizada += $m_outras_diversas_intra["atualizada"];
$tot_nobim      += $m_outras_diversas_intra["nobim"];
$tot_atebim     += $m_outras_diversas_intra["atebim"];
$tot_realizar   += $m_outras_diversas_intra["realizar"];


if ($tot_inicial > 0) {

  $iTotInicial  = db_formatar($tot_inicial,'f');
  $AlinhaIni    = "R";
} else {

  $iTotInicial  = " - ";
  $AlinhaIni    = "C";
}

if ($tot_atualizada > 0) {

  $nTotAtualizada  = db_formatar($tot_atualizada,'f');
  $AlinhaAtu       = "R";

} else {

  $nTotAtualizada  = " - ";
  $AlinhaAtu       = "C";
}

if ($tot_nobim > 0) {

  $nTotNobimestre  = db_formatar($tot_nobim,'f');
  $AlinhaBim       = "R";

} else {

  $nTotNobimestre  = " - ";
  $AlinhaBim       = "C";

}
@$nTotBimAtu = ($tot_nobim*100)/$tot_atualizada;
if ($nTotBimAtu > 0) {

  $nTotBimAtu = db_formatar($nTotBimAtu,'f');
  $AlinhaBate = "R";

} else {

  $nTotBimAtu = " - ";
  $AlinhaBate = "C";

}

if ($tot_atebim > 0) {

  $nTotAteBimestre = db_formatar($tot_atebim,'f');
  $AlinhaAte       = "R";

} else {

  $nTotAteBimestre = " - ";
  $AlinhaAte       = "C";

}

if ($tot_atebim > 0) {

  $nTotAteBimestre = db_formatar($tot_atebim,'f');
  $AlinhaAte       = "R";

} else {

  $nTotAteBimestre = " - ";
  $AlinhaAte       = "C";

}
@$nTotAteBimAtual = ($tot_atebim*100)/$tot_atualizada;
if ($nTotAteBimAtual > 0) {

  $nTotAteBimAtual = db_formatar($nTotAteBimAtual,'f');
  $AlinhaBimAtual  = "R";
} else {

  $nTotAteBimAtual = "-";
  $AlinhaBimAtual  = "C";

}

if ($tot_realizar > 0) {

  $nTotRealizar = db_formatar($tot_realizar,'f');
  $AlinhaReal   = "R";

} else {

  $nTotRealizar  = " - ";
  $AlinhaReal    = "C";

}
$pdf->cell(20,$alt,$iTotInicial,'R',0, $AlinhaIni,0);
$pdf->cell(20,$alt,$nTotAtualizada,'R',0,$AlinhaAtu,0);
$pdf->cell(25,$alt, $nTotNobimestre,'R',0,$AlinhaBim,0);
@$pdf->cell(10,$alt,$nTotBimAtu,'R',0, $AlinhaBate,0);
// % (b/a)
$pdf->cell(25,$alt,$nTotAteBimestre,'R',0,$AlinhaAte,0);
@$pdf->cell(10,$alt,$nTotAteBimAtual,'R',0,$AlinhaBimAtual,0);
// % (b/a)
$pdf->cell(20,$alt,$nTotRealizar,0,1,$AlinhaReal,0);

if ($rec_cor_II_inicial_intra  > 0 || $rec_cor_II_atualizada_intra > 0 ||
  $rec_cor_II_nobim_intra    > 0 || $rec_cor_II_atebim_intra     > 0 ||
  $rec_cor_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,"RECEITAS CORRENTES",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_cor_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_cor_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_cor_II_nobim_intra*100)/$rec_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_cor_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_cor_II_atebim_intra*100)/$rec_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_cor_II_realizar_intra,'f'),0,1,"R",0);
  }

if ($rec_trib_II_inicial_intra  > 0 || $rec_trib_II_atualizada_intra > 0 ||
  $rec_trib_II_nobim_intra    > 0 || $rec_trib_II_atebim_intra     > 0 ||
  $rec_trib_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA TRIBUTÁRIA",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_trib_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_trib_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_trib_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_trib_II_nobim_intra*100)/$rec_trib_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_trib_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_trib_II_atebim_intra*100)/$rec_trib_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_trib_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'111 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Imposto Intra-Orcamentario
if ($m_impostos_intra["inicial"]  > 0 || $m_impostos_intra["atualizada"] > 0 ||
  $m_impostos_intra["nobim"]    > 0 || $m_impostos_intra["atebim"]    > 0  ||
  $m_impostos_intra["realizar"] > 0) {

    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Impostos",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_impostos_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_impostos_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_impostos_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_impostos_intra["nobim"]*100)/$m_impostos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_impostos_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_impostos_intra["atebim"]*100)/$m_impostos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_impostos_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'222 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Taxas Intra-Orcamentaria
if ($m_taxas_intra["inicial"]  > 0 || $m_taxas_intra["atualizada"] > 0 ||
  $m_taxas_intra["nobim"]    > 0 || $m_taxas_intra["atebim"]     > 0 ||
  $m_taxas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Taxas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_taxas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_taxas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_taxas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_taxas_intra["nobim"]*100)/$m_taxas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_taxas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_taxas_intra["atebim"]*100)/$m_taxas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_taxas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'333 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Contribuicao de Melhorias Intra-Orcamentaria
if ($m_melhorias_intra["inicial"]  > 0 || $m_melhorias_intra["atualizada"] > 0 ||
  $m_melhorias_intra["nobim"]    > 0 || $m_melhorias_intra["atebim"]     > 0 ||
  $m_melhorias_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Contribuição de melhoria",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_melhorias_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_melhorias_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_melhorias_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_melhorias_intra["nobim"]*100)/$m_melhorias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_melhorias_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_melhorias_intra["atebim"]*100)/$m_melhorias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_melhorias_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'444 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_contr_II_inicial_intra  > 0 || $rec_contr_II_atualizada_intra > 0 ||
  $rec_contr_II_nobim_intra    > 0 || $rec_contr_II_atebim_intra     > 0 ||
  $rec_contr_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA DE CONTRIBUIÇÕES",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_contr_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_contr_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_contr_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_contr_II_nobim_intra*100)/$rec_contr_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_contr_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_contr_II_atebim_intra*100)/$rec_contr_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_contr_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'555 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Contribuicoes Sociais Intra-Orcamentaria
if ($m_sociais_intra["inicial"]  > 0 || $m_sociais_intra["atualizada"] > 0 ||
  $m_sociais_intra["nobim"]    > 0 || $m_sociais_intra["atebim"]     > 0 ||
  $m_sociais_intra["realizar"] > 0){

    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Contribuições Sociais",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_sociais_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_sociais_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_sociais_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_sociais_intra["nobim"]*100)/$m_sociais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_sociais_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_sociais_intra["atebim"]*100)/$m_sociais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_sociais_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'66 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Contribuicoes Economicas Intra-Orcamentaria
if ($m_economicas_intra["inicial"]  > 0 || $m_economicas_intra["atualizada"] > 0 ||
  $m_economicas_intra["nobim"]    > 0 || $m_economicas_intra["atebim"]     > 0 ||
  $m_economicas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Contribuições de Intervenção no Domínio Econômico",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_economicas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_economicas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_economicas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_economicas_intra["nobim"]*100)/$m_economicas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_economicas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_economicas_intra["atebim"]*100)/$m_economicas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_economicas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'777 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }


//Contribuições de Iluminação Pública
if ($m_iluminacao_publica_intra["inicial"]  > 0 || $m_iluminacao_publica_intra["atualizada"] > 0 ||
  $m_iluminacao_publica_intra["nobim"]    > 0 || $m_iluminacao_publica_intra["atebim"]     > 0 ||
  $m_iluminacao_publica_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Contribuições de Iluminação Pública",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_iluminacao_publica_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_iluminacao_publica_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_iluminacao_publica_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_iluminacao_publica_intra["nobim"]*100)/$m_iluminacao_publica_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_iluminacao_publica_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_iluminacao_publica_intra["atebim"]*100)/$m_iluminacao_publica_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_iluminacao_publica_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }


if ($rec_patr_II_inicial_intra  > 0 || $rec_patr_II_atualizada_intra > 0 ||
  $rec_patr_II_nobim_intra    > 0 || $rec_patr_II_atebim_intra     > 0 ||
  $rec_patr_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA PATRIMONIAL",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_patr_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_patr_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_patr_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_patr_II_nobim_intra*100)/$rec_patr_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_patr_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_patr_II_atebim_intra*100)/$rec_patr_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_patr_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'888 Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receitas Imobiliarias Intra-Orcamentaria
if ($m_imobiliarias_intra["inicial"]  > 0 || $m_imobiliarias_intra["atualizada"] > 0 ||
  $m_imobiliarias_intra["nobim"]    > 0 || $m_imobiliarias_intra["atebim"]     > 0 ||
  $m_imobiliarias_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receitas Imobiliárias",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_imobiliarias_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_imobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_imobiliarias_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_imobiliarias_intra["nobim"]*100)/$m_imobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_imobiliarias_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_imobiliarias_intra["atebim"]*100)/$m_imobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_imobiliarias_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita de Valores Mobiliarios Intra-Orcamentario
if ($m_valmobiliarias_intra["inicial"]  > 0 || $m_valmobiliarias_intra["atualizada"] > 0 ||
  $m_valmobiliarias_intra["nobim"]    > 0 || $m_valmobiliarias_intra["atebim"]     > 0 ||
  $m_valmobiliarias_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita de Valores Mobiliários",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_valmobiliarias_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_valmobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_valmobiliarias_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_valmobiliarias_intra["nobim"]*100)/$m_valmobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_valmobiliarias_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_valmobiliarias_intra["atebim"]*100)/$m_valmobiliarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_valmobiliarias_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita de Concessoes e Permissoes Intra-Orcamentario
if ($m_permissoes_intra["inicial"]  > 0 || $m_permissoes_intra["atualizada"] > 0 ||
  $m_permissoes_intra["nobim"]    > 0 || $m_permissoes_intra["atebim"]     > 0 ||
  $m_permissoes_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita de Concessões e Permissões",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_permissoes_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_permissoes_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_permissoes_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_permissoes_intra["nobim"]*100)/$m_permissoes_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_permissoes_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_permissoes_intra["atebim"]*100)/$m_permissoes_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_permissoes_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Outras Receitas Patrimoniais Intra-Orcamentaria
if ($m_patrimoniais_intra["inicial"]  > 0 || $m_patrimoniais_intra["atualizada"] > 0 ||
  $m_patrimoniais_intra["nobim"]    > 0 || $m_patrimoniais_intra["atebim"]     > 0 ||
  $m_patrimoniais_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Outras Receitas Patrimoniais",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_patrimoniais_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_patrimoniais_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_patrimoniais_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_patrimoniais_intra["nobim"]*100)/$m_patrimoniais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_patrimoniais_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_patrimoniais_intra["atebim"]*100)/$m_patrimoniais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_patrimoniais_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_agro_II_inicial_intra  > 0 || $rec_agro_II_atualizada_intra > 0 ||
  $rec_agro_II_nobim_intra    > 0 || $rec_agro_II_atebim_intra     > 0 ||
  $rec_agro_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA AGROPECUÁRIA",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_agro_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_agro_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_agro_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_agro_II_nobim_intra*100)/$rec_agro_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_agro_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_agro_II_atebim_intra*100)/$rec_agro_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_agro_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita da Producao Vegetal Intra-Orcamentaria
if ($m_vegetal_intra["inicial"]  > 0 || $m_vegetal_intra["atualizada"] > 0 ||
  $m_vegetal_intra["nobim"]    > 0 || $m_vegetal_intra["atebim"]     > 0 ||
  $m_vegetal_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita da Produção Vegetal",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_vegetal_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_vegetal_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_vegetal_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_vegetal_intra["nobim"]*100)/$m_vegetal_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_vegetal_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_vegetal_intra["atebim"]*100)/$m_vegetal_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_vegetal_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita da Producao Animal e Derivados Intra-Orcamentaria
if ($m_animal_intra["inicial"]  > 0 || $m_animal_intra["atualizada"] > 0 ||
  $m_animal_intra["nobim"]    > 0 || $m_animal_intra["atebim"]     > 0 ||
  $m_animal_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita da Produção Animal e Derivados",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_animal_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_animal_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_animal_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_animal_intra["nobim"]*100)/$m_animal_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_animal_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_animal_intra["atebim"]*100)/$m_animal_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_animal_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Outras Receitas Agropecuarias Intra-Orcamentaria
if ($m_agropecuarias_intra["inicial"]  > 0 || $m_agropecuarias_intra["atualizada"] > 0 ||
  $m_agropecuarias_intra["nobim"]    > 0 || $m_agropecuarias_intra["atebim"]     > 0 ||
  $m_agropecuarias_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Outras Receitas Agropecuárias",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_agropecuarias_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_agropecuarias_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_agropecuarias_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_agropecuarias_intra["nobim"]*100)/$m_agropecuarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_agropecuarias_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_agropecuarias_intra["atebim"]*100)/$m_agropecuarias_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_agropecuarias_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_ind_II_inicial_intra  > 0 || $rec_ind_II_atualizada_intra > 0 ||
  $rec_ind_II_nobim_intra    > 0 || $rec_ind_II_atebim_intra     > 0 ||
  $rec_ind_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA INDUSTRIAL",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_ind_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_ind_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_ind_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_ind_II_nobim_intra*100)/$rec_ind_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_ind_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_ind_II_atebim_intra*100)/$rec_ind_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_ind_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receitas da Industria de Transformacao Intra-Orcamentaria
if ($m_transformacao_intra["inicial"]  > 0 || $m_transformacao_intra["atualizada"] > 0 ||
  $m_transformacao_intra["nobim"]    > 0 || $m_transformacao_intra["atebim"]     > 0 ||
  $m_transformacao_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita da Indústria de Transformação",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transformacao_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transformacao_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transformacao_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transformacao_intra["nobim"]*100)/$m_transformacao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transformacao_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transformacao_intra["atebim"]*100)/$m_transformacao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transformacao_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receitas da Industria de Construcao Intra-Orcamentaria
if ($m_construcao_intra["inicial"]  > 0 || $m_construcao_intra["atualizada"] > 0 ||
  $m_construcao_intra["nobim"]    > 0 || $m_construcao_intra["atebim"]     > 0 ||
  $m_construcao_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita da Indústria de Construção",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_construcao_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_construcao_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_construcao_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_construcao_intra["nobim"]*100)/$m_construcao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_construcao_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_construcao_intra["atebim"]*100)/$m_construcao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_construcao_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Outras Receitas Industriais Intra-Orcamentaria
if ($m_industriais_intra["inicial"]  > 0 || $m_industriais_intra["atualizada"] > 0 ||
  $m_industriais_intra["nobim"]    > 0 || $m_industriais_intra["atebim"]     > 0 ||
  $m_industriais_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Outras Receitas Industriais",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_industriais_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_industriais_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_industriais_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_industriais_intra["nobim"]*100)/$m_industriais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_industriais_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_industriais_intra["atebim"]*100)/$m_industriais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_industriais_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita de Servicos Intra-Orcamentario
if ($m_servicos_intra["inicial"]  > 0 || $m_servicos_intra["atualizada"] > 0 ||
  $m_servicos_intra["nobim"]    > 0 || $m_servicos_intra["atebim"]     > 0 ||
  $m_servicos_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."RECEITA DE SERVIÇOS",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_servicos_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_servicos_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_servicos_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_servicos_intra["nobim"]*100)/$m_servicos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_servicos_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_servicos_intra["atebim"]*100)/$m_servicos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_servicos_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_transf_cor_II_inicial_intra  > 0 || $rec_transf_cor_II_atualizada_intra > 0 ||
  $rec_transf_cor_II_nobim_intra    > 0 || $rec_transf_cor_II_atebim_intra     > 0 ||
  $rec_transf_cor_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS CORRENTES",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_transf_cor_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_transf_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_transf_cor_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_transf_cor_II_nobim_intra*100)/$rec_transf_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_transf_cor_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_transf_cor_II_atebim_intra*100)/$rec_contr_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_transf_cor_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias Intergovernamentais
if ($m_intergovernamental_intra["inicial"]  > 0 || $m_intergovernamental_intra["atualizada"] > 0 ||
  $m_intergovernamental_intra["nobim"]    > 0 || $m_intergovernamental_intra["atebim"]     > 0 ||
  $m_intergovernamental_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências Intergovernamentais",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_intergovernamental_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_intergovernamental_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_intergovernamental_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_intergovernamental_intra["nobim"]*100)/$m_intergovernamental_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_intergovernamental_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_intergovernamental_intra["atebim"]*100)/$m_intergovernamental_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_intergovernamental_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Instituicoes Privadas
if ($m_privadas_intra["inicial"]  > 0 || $m_privadas_intra["atualizada"] > 0 ||
  $m_privadas_intra["nobim"]    > 0 || $m_privadas_intra["atebim"]     > 0 ||
  $m_privadas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_privadas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_privadas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_privadas_intra["nobim"]*100)/$m_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_privadas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_privadas_intra["atebim"]*100)/$m_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_privadas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias do Exterior
if ($m_transf_exterior_intra["inicial"]  > 0 || $m_transf_exterior_intra["atualizada"] > 0 ||
  $m_transf_exterior_intra["nobim"]    > 0 || $m_transf_exterior_intra["atebim"]     > 0 ||
  $m_transf_exterior_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_exterior_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_exterior_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_exterior_intra["nobim"]*100)/$m_transf_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_exterior_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_exterior_intra["atebim"]*100)/$m_transf_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_exterior_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Pessoas
if ($m_transf_pessoas_intra["inicial"]  > 0 || $m_transf_pessoas_intra["atualizada"] > 0 ||
  $m_transf_pessoas_intra["nobim"]    > 0 || $m_transf_pessoas_intra["atebim"]     > 0 ||
  $m_transf_pessoas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_pessoas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_pessoas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_pessoas_intra["nobim"]*100)/$m_transf_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_pessoas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_pessoas_intra["atebim"]*100)/$m_transf_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_pessoas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Convenios
if ($m_transf_convenios_intra["inicial"]  > 0 || $m_transf_convenios_intra["atualizada"] > 0 ||
  $m_transf_convenios_intra["nobim"]    > 0 || $m_transf_convenios_intra["atebim"]     > 0 ||
  $m_transf_convenios_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Convênios",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_convenios_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_convenios_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_convenios_intra["nobim"]*100)/$m_transf_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_convenios_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_convenios_intra["atebim"]*100)/$m_transf_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_convenios_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias para o Combate a Fome
if ($m_transf_fome_intra["inicial"]  > 0 || $m_transf_fome_intra["atualizada"] > 0 ||
  $m_transf_fome_intra["nobim"]    > 0 || $m_transf_fome_intra["atebim"]     > 0 ||
  $m_transf_fome_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências para o Combate à Fome",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_fome_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_fome_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_fome_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_fome_intra["nobim"]*100)/$m_transf_fome_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_fome_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_fome_intra["atebim"]*100)/$m_transf_fome_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_fome_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_outras_cor_II_inicial_intra  > 0 || $rec_outras_cor_II_atualizada_intra > 0 ||
  $rec_outras_cor_II_nobim_intra    > 0 || $rec_outras_cor_II_atebim_intra     > 0 ||
  $rec_outras_cor_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS CORRENTES",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_outras_cor_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_outras_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_outras_cor_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_outras_cor_II_nobim_intra*100)/$rec_outras_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_outras_cor_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_outras_cor_II_atebim_intra*100)/$rec_outras_cor_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_outras_cor_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Multas e Juros de Mora Intra-Orcamentario
if ($m_multas_intra["inicial"]  > 0 || $m_multas_intra["atualizada"] > 0 ||
  $m_multas_intra["nobim"]    > 0 || $m_multas_intra["atebim"]     > 0 ||
  $m_multas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Multas e Juros de Mora",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_multas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_multas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_multas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_multas_intra["nobim"]*100)/$m_multas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_multas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_multas_intra["atebim"]*100)/$m_multas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_multas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Indenizacoes e Restituicoes Intra-Orcamentario
if ($m_indenizacao_intra["inicial"]  > 0 || $m_indenizacao_intra["atualizada"] > 0 ||
  $m_indenizacao_intra["nobim"]    > 0 || $m_indenizacao_intra["atebim"]     > 0 ||
  $m_indenizacao_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Indenizações e Restituições",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_indenizacao_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_indenizacao_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_indenizacao_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_indenizacao_intra["nobim"]*100)/$m_indenizacao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_indenizacao_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_indenizacao_intra["atebim"]*100)/$m_indenizacao_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_indenizacao_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receita da Divida Ativa Intra-Orcamentario
if ($m_divida_ativa_intra["inicial"]  > 0 || $m_divida_ativa_intra["atualizada"] > 0 ||
  $m_divida_ativa_intra["nobim"]    > 0 || $m_divida_ativa_intra["atebim"]     > 0 ||
  $m_divida_ativa_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receita da Divida Ativa",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_divida_ativa_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_divida_ativa_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_divida_ativa_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_divida_ativa_intra["nobim"]*100)/$m_divida_ativa_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_divida_ativa_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_divida_ativa_intra["atebim"]*100)/$m_divida_ativa_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_divida_ativa_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receitas Correntes Diversas Intra-Orcamentaria
if ($m_correntes_diversas_intra["inicial"]  > 0 || $m_correntes_diversas_intra["atualizada"] > 0 ||
  $m_correntes_diversas_intra["nobim"]    > 0 || $m_correntes_diversas_intra["atebim"]     > 0 ||
  $m_correntes_diversas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receitas Correntes Diversas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_correntes_diversas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_correntes_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_correntes_diversas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_correntes_diversas_intra["nobim"]*100)/$m_correntes_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_correntes_diversas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_correntes_diversas_intra["atebim"]*100)/$m_correntes_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_correntes_diversas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

if ($rec_cap_II_inicial_intra  > 0 || $rec_cap_II_atualizada_intra > 0 ||
  $rec_cap_II_nobim_intra    > 0 || $rec_cap_II_atebim_intra     > 0 ||
  $rec_cap_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,"RECEITAS DE CAPITAL",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_cap_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_cap_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_cap_II_nobim_intra*100)/$rec_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_cap_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_cap_II_atebim_intra*100)/$rec_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_cap_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Operacoes de Credito Capital Intra-Orcamentaria
if ($rec_oper_cred_cap_II_inicial_intra  > 0 || $rec_oper_cred_cap_II_atualizada_intra > 0 ||
  $rec_oper_cred_cap_II_nobim_intra    > 0 || $rec_oper_cred_cap_II_atebim_intra     > 0 ||
  $rec_oper_cred_cap_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."OPERAÇÕES DE CRÉDITO",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_oper_cred_cap_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_oper_cred_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_oper_cred_cap_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_oper_cred_cap_II_nobim_intra*100)/$rec_oper_cred_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_oper_cred_cap_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_oper_cred_cap_II_atebim_intra*100)/$rec_oper_cred_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_oper_cred_cap_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Operacoes de Credito Internas Capital Intra-Orcamentaria
if ($m_oper_internas_intra["inicial"]  > 0 || $m_oper_internas_intra["atualizada"] > 0 ||
  $m_oper_internas_intra["nobim"]    > 0 || $m_oper_internas_intra["atebim"]     > 0 ||
  $m_oper_internas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Internas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_oper_internas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_oper_internas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_oper_internas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_oper_internas_intra["nobim"]*100)/$m_oper_internas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_oper_internas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_oper_internas_intra["atebim"]*100)/$m_oper_internas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_oper_internas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Operacoes de Credito Externas Capital Intra-Orcamentaria
if ($m_oper_externas_intra["inicial"]  > 0 || $m_oper_externas_intra["atualizada"] > 0 ||
  $m_oper_externas_intra["nobim"]    > 0 || $m_oper_externas_intra["atebim"]     > 0 ||
  $m_oper_externas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Operações de Crédito Externas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_oper_externas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_oper_externas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_oper_externas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_oper_externas_intra["nobim"]*100)/$m_oper_externas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_oper_externas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_oper_externas_intra["atebim"]*100)/$m_oper_externas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_oper_externas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Alienacao de Bens Capital Intra-Orcamentaria
if ($rec_alien_bens_cap_II_inicial_intra  > 0 || $rec_alien_bens_cap_II_atualizada_intra > 0 ||
  $rec_alien_bens_cap_II_nobim_intra    > 0 || $rec_alien_bens_cap_II_atebim_intra     > 0 ||
  $rec_alien_bens_cap_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."ALIENAÇÃO DE BENS",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_alien_bens_cap_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_alien_bens_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_alien_bens_cap_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_alien_bens_cap_II_nobim_intra*100)/$rec_alien_bens_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_alien_bens_cap_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_alien_bens_cap_II_atebim_intra*100)/$rec_alien_bens_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_alien_bens_cap_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Alienacao de Bens Moveis Capital Intra-Orcamentaria
if ($m_bens_moveis_intra["inicial"]  > 0 || $m_bens_moveis_intra["atualizada"] > 0 ||
  $m_bens_moveis_intra["nobim"]    > 0 || $m_bens_moveis_intra["atebim"]     > 0 ||
  $m_bens_moveis_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Móveis",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_bens_moveis_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_bens_moveis_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_bens_moveis_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_bens_moveis_intra["nobim"]*100)/$m_bens_moveis_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_bens_moveis_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_bens_moveis_intra["atebim"]*100)/$m_bens_moveis_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_bens_moveis_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Alienacao de Bens Imoveis Capital Intra-Orcamentaria
if ($m_bens_imoveis_intra["inicial"]  > 0 || $m_bens_imoveis_intra["atualizada"] > 0 ||
  $m_bens_imoveis_intra["nobim"]    > 0 || $m_bens_imoveis_intra["atebim"]     > 0 ||
  $m_bens_imoveis_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Alienação de Bens Imóveis",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_bens_imoveis_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_bens_imoveis_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_bens_imoveis_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_bens_imoveis_intra["nobim"]*100)/$m_bens_imoveis_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_bens_imoveis_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_bens_imoveis_intra["atebim"]*100)/$m_bens_imoveis_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_bens_imoveis_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Amorizacoes de Emprestimos Capital Intra-Orcamentaria
if ($m_emprestimos_intra["inicial"]  > 0 || $m_emprestimos_intra["atualizada"] > 0 ||
  $m_emprestimos_intra["nobim"]    > 0 || $m_emprestimos_intra["atebim"]     > 0 ||
  $m_emprestimos_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."AMORTIZAÇÕES DE EMPRÉSTIMOS",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_emprestimos_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_emprestimos_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_emprestimos_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_emprestimos_intra["nobim"]*100)/$m_emprestimos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_emprestimos_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_emprestimos_intra["atebim"]*100)/$m_emprestimos_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_emprestimos_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Capital Intra-Orcamentaria
if ($rec_transf_cap_II_inicial_intra  > 0 || $rec_transf_cap_II_atualizada_intra > 0 ||
  $rec_transf_cap_II_nobim_intra    > 0 || $rec_transf_cap_II_atebim_intra     > 0 ||
  $rec_transf_cap_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."TRANSFERÊNCIAS DE CAPITAL",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_transf_cap_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_transf_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_transf_cap_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_transf_cap_II_nobim_intra*100)/$rec_transf_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_transf_cap_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_transf_cap_II_atebim_intra*100)/$rec_transf_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_transf_cap_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias Intergovernamentais Capital Intra-Orcamentaria
if ($m_transf_capital_intergovernamentais_intra["inicial"]  > 0 || $m_transf_capital_intergovernamentais_intra["atualizada"] > 0 ||
  $m_transf_capital_intergovernamentais_intra["nobim"]    > 0 || $m_transf_capital_intergovernamentais_intra["atebim"]     > 0 ||
  $m_transf_capital_intergovernamentais_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências Intergovernamentais",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_intergovernamentais_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_intergovernamentais_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_intergovernamentais_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_intergovernamentais_intra["nobim"]*100)/$m_transf_capital_intergovernamentais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_intergovernamentais_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_intergovernamentais_intra["atebim"]*100)/$m_transf_capital_intergovernamentais_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_intergovernamentais_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Instituicoes Privadas Capital Intra-Orcamentaria
if ($m_transf_capital_privadas_intra["inicial"]  > 0 || $m_transf_capital_privadas_intra["atualizada"] > 0 ||
  $m_transf_capital_privadas_intra["nobim"]    > 0 || $m_transf_capital_privadas_intra["atebim"]     > 0 ||
  $m_transf_capital_privadas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Instituições Privadas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_privadas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_privadas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_privadas_intra["nobim"]*100)/$m_transf_capital_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_privadas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_privadas_intra["atebim"]*100)/$m_transf_capital_privadas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_privadas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias do Exterior Capital Intra-Orcamentaria
if ($m_transf_capital_exterior_intra["inicial"]  > 0 || $m_transf_capital_exterior_intra["atualizada"] > 0 ||
  $m_transf_capital_exterior_intra["nobim"]    > 0 || $m_transf_capital_exterior_intra["atebim"]     > 0 ||
  $m_transf_capital_exterior_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências do Exterior",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_exterior_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_exterior_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_exterior_intra["nobim"]*100)/$m_transf_capital_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_exterior_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_exterior_intra["atebim"]*100)/$m_transf_capital_exterior_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_exterior_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Pessoas Capital Intra-Orcamentaria
if ($m_transf_capital_pessoas_intra["inicial"]  > 0 || $m_transf_capital_pessoas_intra["atualizada"] > 0 ||
  $m_transf_capital_pessoas_intra["nobim"]    > 0 || $m_transf_capital_pessoas_intra["atebim"]     > 0 ||
  $m_transf_capital_pessoas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Pessoas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_pessoas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_pessoas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_pessoas_intra["nobim"]*100)/$m_transf_capital_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_pessoas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_pessoas_intra["atebim"]*100)/$m_transf_capital_pessoas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_pessoas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Outras Instituicoes Publicas Capital Intra-Orcamentaria
if ($m_transf_capital_outras_intra["inicial"]  > 0 || $m_transf_capital_outras_intra["atualizada"] > 0 ||
  $m_transf_capital_outras_intra["nobim"]    > 0 || $m_transf_capital_outras_intra["atebim"]     > 0 ||
  $m_transf_capital_outras_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Outras Instituições Públicas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_outras_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_outras_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_outras_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_outras_intra["nobim"]*100)/$m_transf_capital_outras_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_outras_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_outras_intra["atebim"]*100)/$m_transf_capital_outras_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_outras_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias de Convenios Capital Intra-Orcamentaria
if ($m_transf_capital_convenios_intra["inicial"]  > 0 || $m_transf_capital_convenios_intra["atualizada"] > 0 ||
  $m_transf_capital_convenios_intra["nobim"]    > 0 || $m_transf_capital_convenios_intra["atebim"]     > 0 ||
  $m_transf_capital_convenios_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências de Convênios",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_convenios_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_convenios_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_convenios_intra["nobim"]*100)/$m_transf_capital_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_convenios_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_convenios_intra["atebim"]*100)/$m_transf_capital_convenios_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_convenios_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Transferencias para o Combate a Fome Capital Intra-Orcamentaria
if ($m_transf_capital_fome_intra["inicial"]  > 0 || $m_transf_capital_fome_intra["atualizada"] > 0 ||
  $m_transf_capital_fome_intra["nobim"]    > 0 || $m_transf_capital_fome_intra["atebim"]     > 0 ||
  $m_transf_capital_fome_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Transferências para o Combate à Fome",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_fome_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_fome_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_fome_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_fome_intra["nobim"]*100)/$m_transf_capital_fome_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_transf_capital_fome_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_transf_capital_fome_intra["atebim"]*100)/$m_transf_capital_fome_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_transf_capital_fome_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Outras Receitas de Capital Intra-Orcamentaria
if ($rec_outras_cap_II_inicial_intra  > 0 || $rec_outras_cap_II_atualizada_intra > 0 ||
  $rec_outras_cap_II_nobim_intra    > 0 || $rec_outras_cap_II_atebim_intra     > 0 ||
  $rec_outras_cap_II_realizar_intra > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n1)."OUTRAS RECEITAS DE CAPITAL",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($rec_outras_cap_II_inicial_intra,'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($rec_outras_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($rec_outras_cap_II_nobim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_outras_cap_II_nobim_intra*100)/$rec_outras_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($rec_outras_cap_II_atebim_intra,'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($rec_outras_cap_II_atebim_intra*100)/$rec_outras_cap_II_atualizada_intra,'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($rec_outras_cap_II_realizar_intra,'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-30){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Integralizacao do Capital Social Intra-Orcamentaria
if ($m_outras_social_intra["inicial"]  > 0 || $m_outras_social_intra["atualizada"] > 0 ||
  $m_outras_social_intra["nobim"]    > 0 || $m_outras_social_intra["atebim"]     > 0 ||
  $m_outras_social_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Integralização do Capital Social",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_social_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_social_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_outras_social_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_social_intra["nobim"]*100)/$m_outras_social_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_outras_social_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_social_intra["atebim"]*100)/$m_outras_social_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_outras_social_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Dív. Atv. Prov. da Amortiz. de Emp. e Financ. Intra-Orcamentaria
if ($m_outras_disponibilidades_intra["inicial"]  > 0 || $m_outras_disponibilidades_intra["atualizada"] > 0 ||
  $m_outras_disponibilidades_intra["nobim"]    > 0 || $m_outras_disponibilidades_intra["atebim"]     > 0 ||
  $m_outras_disponibilidades_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Dív. Atv. Prov. da Amortiz. de Emp. e Financ.",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_disponibilidades_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_disponibilidades_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_outras_disponibilidades_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_disponibilidades_intra["nobim"]*100)/$m_outras_disponibilidades_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_outras_disponibilidades_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_disponibilidades_intra["atebim"]*100)/$m_outras_disponibilidades_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_outras_disponibilidades_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Restituicoes Intra-Orcamentaria
if ($m_outras_restituicoes_intra["inicial"]  > 0 || $m_outras_restituicoes_intra["atualizada"] > 0 ||
  $m_outras_restituicoes_intra["nobim"]    > 0 || $m_outras_restituicoes_intra["atebim"]     > 0 ||
  $m_outras_restituicoes_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Restituições",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_restituicoes_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_restituicoes_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_outras_restituicoes_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_restituicoes_intra["nobim"]*100)/$m_outras_restituicoes_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_outras_restituicoes_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_restituicoes_intra["atebim"]*100)/$m_outras_restituicoes_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_outras_restituicoes_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-40){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }

// Receitas de Capital Diversas Intra-Orcamentaria

if ($m_outras_diversas_intra["inicial"]  > 0 || $m_outras_diversas_intra["atualizada"] > 0 ||
  $m_outras_diversas_intra["nobim"]    > 0 || $m_outras_diversas_intra["atebim"]     > 0 ||
  $m_outras_diversas_intra["realizar"] > 0){
    $pdf->setfont('arial','',6);
    $pdf->cell(60,$alt,espaco($n2)."Receitas de Capital Diversas",'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_diversas_intra["inicial"],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_outras_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    $pdf->cell(25,$alt,db_formatar($m_outras_diversas_intra["nobim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_diversas_intra["nobim"]*100)/$m_outras_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(25,$alt,db_formatar($m_outras_diversas_intra["atebim"],'f'),'R',0,"R",0);
    @$pdf->cell(10,$alt,db_formatar(($m_outras_diversas_intra["atebim"]*100)/$m_outras_diversas_intra["atualizada"],'f'),'R',0,"R",0);
    // % (b/a)
    $pdf->cell(20,$alt,db_formatar($m_outras_diversas_intra["realizar"],'f'),0,1,"R",0);

    if ($pdf->gety() > $pdf->h-30){
      $pdf->addpage();
      $pdf->setfont('arial','',5);
      $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
      $pdf->Ln(4);

      $flag_troca = true;
    }
  } else {
    $flag_troca = false;
  }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$somador_I_inicial    += $tot_inicial;
$somador_I_atualizada += $tot_atualizada;
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
// // // // // // // SUBTOTAL DAS RECEITAS  // // // // // // //
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SUBTOTAL DAS RECEITAS(III) = (I+II)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_I_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_I_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_I_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_nobim*100)/$somador_I_atualizada,'f'),'TBR',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($somador_I_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_I_atebim*100)/$somador_I_atualizada,'f'),'TBR',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($somador_I_realizar,'f'),'TB',1,"R",0);
//--------------------------------

/*
$pdf->setfont('arial','',5);
$pdf->Ln(1);
$pdf->cell(60,$alt,"Continua na página 2",'0',1,"L",0);
 */

  if ($pdf->gety() > $pdf->h-40){
    $pdf->addpage();
    $pdf->setfont('arial','',5);
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
    $pdf->Ln(4);

    imprime_cabec_rec($alt,&$pdf);
  }

$pos_refi = $pdf->getY();
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"OPERAÇÕES DE CRÉDITO / REFINANCIAMENTO (IV)",'TR',0,"L",0);
$pdf->cell(20,$alt,'','TR',0,"R",0);
$pdf->cell(20,$alt,'','TR',0,"R",0);
$pdf->cell(25,$alt,'','TR',0,"R",0);
$pdf->cell(10,$alt,'','TR',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','TR',0,"R",0);
$pdf->cell(10,$alt,'','TR',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'','T',1,"R",0);
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."Operações de Crédito Internas",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_credito_internas_mobiliaria["parametros"],$m_oper_credito_internas_mobiliaria_intra,$result_rec);

$oLinha = new linhaRelatorioContabil($iCodigoRelatorio, 43);
$oLinha->setPeriodo($iCodigoPeriodo);
$aColunas  = $oLinha->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
foreach ($aColunas as $oLinhaColuna) {

  if (isset($oLinhaColuna->colunas[1]->o117_valor)) {
    $tot_inicial    += @$oLinhaColuna->colunas[1]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[2]->o117_valor)) {
    $tot_atualizada += @$oLinhaColuna->colunas[2]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[3]->o117_valor)) {
    $tot_nobim      += @$oLinhaColuna->colunas[3]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[4]->o117_valor)) {
    $tot_atebim     += @$oLinhaColuna->colunas[4]->o117_valor;
  }
}
$tot_realizar = $tot_atualizada - $tot_atebim;
$pdf->cell(60,$alt,espaco($n2)."Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$somador_II_inicial    += $tot_inicial;
$somador_II_atualizada += $tot_atualizada;
$somador_II_nobim      += $tot_nobim;
$somador_II_atebim     += $tot_atebim;
$somador_II_realizar   += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_credito_internas_contratual["parametros"],$m_oper_credito_internas_contratual_intra,$result_rec);
$oLinha = new linhaRelatorioContabil($iCodigoRelatorio, 44);
$oLinha->setPeriodo($iCodigoPeriodo);
$aColunas  = $oLinha->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
foreach ($aColunas as $oLinhaColuna) {

  if (isset($oLinhaColuna->colunas[1]->o117_valor)) {
    $tot_inicial    += @$oLinhaColuna->colunas[1]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[2]->o117_valor)) {
    $tot_atualizada += @$oLinhaColuna->colunas[2]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[3]->o117_valor)) {
    $tot_nobim      += @$oLinhaColuna->colunas[3]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[4]->o117_valor)) {
    $tot_atebim     += @$oLinhaColuna->colunas[4]->o117_valor;
  }
}
$tot_realizar = $tot_atualizada - $tot_atebim;
$pdf->cell(60,$alt,espaco($n2)."Contratual",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$somador_II_inicial    += $tot_inicial;
$somador_II_atualizada += $tot_atualizada;
$somador_II_nobim      += $tot_nobim;
$somador_II_atebim     += $tot_atebim;
$somador_II_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki

//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n1)."Operações de Crédito Externas",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_credito_externas_mobiliaria["parametros"],$m_oper_credito_externas_mobiliaria_intra,$result_rec);
$oLinha = new linhaRelatorioContabil($iCodigoRelatorio, 45);
$oLinha->setPeriodo($iCodigoPeriodo);
$aColunas  = $oLinha->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
foreach ($aColunas as $oLinhaColuna) {

  if (isset($oLinhaColuna->colunas[1]->o117_valor)) {
    $tot_inicial    += @$oLinhaColuna->colunas[1]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[2]->o117_valor)) {
    $tot_atualizada += @$oLinhaColuna->colunas[2]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[3]->o117_valor)) {
    $tot_nobim      += @$oLinhaColuna->colunas[3]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[4]->o117_valor)) {
    $tot_atebim     += @$oLinhaColuna->colunas[4]->o117_valor;
  }
}
$tot_realizar = $tot_atualizada - $tot_atebim;
$pdf->cell(60,$alt,espaco($n2)."Mobiliária",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$somador_II_inicial    += $tot_inicial;
$somador_II_atualizada += $tot_atualizada;
$somador_II_nobim      += $tot_nobim;
$somador_II_atebim     += $tot_atebim;
$somador_II_realizar   += $tot_realizar;

$v_inicial    = $tot_inicial;
$v_atualizada = $tot_atualizada;
$v_nobim      = $tot_nobim;
$v_atebim     = $tot_atebim;
$v_realizar   = $tot_realizar;

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim      = 0;
$tot_atebim     = 0;
$tot_realizar   = 0;

atualizaTotaisReceita($m_oper_credito_externas_contratual["parametros"],$m_oper_credito_externas_contratual_intra,$result_rec);
$oLinha = new linhaRelatorioContabil($iCodigoRelatorio, 46);
$oLinha->setPeriodo($iCodigoPeriodo);
$aColunas  = $oLinha->getValoresSomadosColunas(str_replace('-',', ',$db_selinstit), $anousu);
foreach ($aColunas as $oLinhaColuna) {

  if (isset($oLinhaColuna->colunas[1]->o117_valor)) {
    $tot_inicial    += @$oLinhaColuna->colunas[1]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[2]->o117_valor)) {
    $tot_atualizada += @$oLinhaColuna->colunas[2]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[3]->o117_valor)) {
    $tot_nobim      += @$oLinhaColuna->colunas[3]->o117_valor;
  }
  if (isset($oLinhaColuna->colunas[4]->o117_valor)) {
    $tot_atebim     += @$oLinhaColuna->colunas[4]->o117_valor;
  }
}
$tot_realizar = $tot_atualizada - $tot_atebim;
$pdf->cell(60,$alt,espaco($n2)."Contratual",'R',0,"L",0);
$pdf->cell(20,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell(20,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,db_formatar($tot_nobim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_nobim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(25,$alt,db_formatar($tot_atebim,'f'),'R',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($tot_atebim*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($tot_realizar,'f'),0,1,"R",0);

$somador_II_inicial    += $tot_inicial;
$somador_II_atualizada += $tot_atualizada;
$somador_II_nobim      += $tot_nobim;
$somador_II_atebim     += $tot_atebim;
$somador_II_realizar   += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;

//--------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($v_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_nobim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($v_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($v_atebim*100)/$v_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($v_realizar,'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki
// sobe, escreve e desce
// escreve o operações de crédito/refinamento
$pdf->setY($pos_refi);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($somador_II_inicial,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_II_nobim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_II_nobim*100)/$somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_II_atebim,'f'),'0',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_II_atebim*100)/$somador_II_atualizada,'f'),'0',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_II_realizar,'f'),'0',0,"R",0);

$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// // // // // // // SUBTOTAL COM REFINANCIAMENTO  // // // // // // //

$somador_III_inicial    = $somador_I_inicial    + $somador_II_inicial;
$somador_III_atualizada = $somador_I_atualizada + $somador_II_atualizada;
$somador_III_nobim      = $somador_I_nobim      + $somador_II_nobim;
$somador_III_atebim     = $somador_I_atebim     + $somador_II_atebim;
$somador_III_realizar   = $somador_I_realizar   + $somador_II_realizar;

$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SUBTOTAL COM REFINANCIAMENTO(V) = (III+IV)",'TBR',0,"L",0);
$pdf->cell(20,$alt,db_formatar($somador_III_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_III_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_III_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_III_nobim*100)/$somador_III_atualizada,'f'),'TBR',0,"R",0);
// %
$pdf->cell(25,$alt,db_formatar($somador_III_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_III_atebim*100)/$somador_III_atualizada,'f'),'TBR',0,"R",0);
// %
$pdf->cell(20,$alt,db_formatar($somador_III_realizar,'f'),'TB',1,"R",0);
//--------------------------------
$tot_despesas   = 0;
$tot_liq_atebim = 0;
$tot_deficit    = 0;
for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento."00";

  if (substr($estrutural,0,1) == "3") {
    $tot_liq_atebim += $liquidado_acumulado;
  }
}

$tot_despesas = $tot_liq_atebim;
$tot_deficit  = $somador_III_atebim - $tot_despesas;

if ($tot_deficit > 0){
  $deficit = "";
} else {
  //$deficit = db_formatar($tot_deficit,"f");
  $deficit = "-";
}
$pos_deficit = $pdf->getY();
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"DÉFICIT (VI)",'TBR',0,"L",0);
$pdf->cell(20,$alt,'-','TBR',0,"C",0);
$pdf->cell(20,$alt,'-','TBR',0,"C",0);
$pdf->cell(25,$alt,'-','TBR',0,"C",0);
$pdf->cell(10,$alt,'-','TBR',0,"C",0);
$pdf->cell(25,$alt,$deficit,'TBR',0,"C",0);
$pdf->cell(10,$alt,'-','TBR',0,"C",0);
// % (b/a)
$pdf->cell(20,$alt,'-','TB',1,"C",0);
//--------------------------------
$pos_total_rec = $pdf->getY();
$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"TOTAL (VII) = (V+VI)",'TBR',0,"L",0);
$pdf->cell(20,$alt,' ','TBR',0,"C",0);
$pdf->cell(20,$alt,' ','TBR',0,"C",0);
$pdf->cell(25,$alt,' ','TBR',0,"C",0);
$pdf->cell(10,$alt,' ','TBR',0,"C",0);
// %
$pdf->cell(25,$alt,'','TBR',0,"C",0);
$pdf->cell(10,$alt,' ','TBR',0,"C",0);
// % (b/a)
$pdf->cell(20,$alt,' ','TB',1,"C",0);

//--------------------------------

$nSuperavitFinancPrevAtu = "";
$nSuperavitFinancAteBim  = "";
$nReaberturaCredPrevAtu  = "";
$nReaberturaCredAteBim   = "";
$dData = $anousu."-01-01";
$sSqlSuperavit = " SELECT sum(o47_valor) as soma
  from orcsuplem
  inner join orcsuplemval on o47_codsup = o46_codsup
  inner join orcsuplemlan on o49_codsup = o47_codsup
  where o46_tiposup in(1008, 1003)
  and o49_data between '{$dData}'
  and '$dt_fin'
  and o46_instit in ($instituicao);";

$rsSqlSuperavit = db_query($sSqlSuperavit);
if(pg_num_rows($rsSqlSuperavit) > 0){
  $oSuperavit = db_utils::fieldsMemory($rsSqlSuperavit,0);
  $nSuperavitFinancPrevAtu += $oSuperavit->soma;
}

$m_superavit_financeiro["parametros"]->setPeriodo($iCodigoPeriodo);
$oLinha = $m_superavit_financeiro["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $nSuperavitFinancPrevAtu += $oColunas->colunas[2]->o117_valor;
  $nSuperavitFinancAteBim  += $oColunas->colunas[4]->o117_valor;

}

$sSqlReabertura = " SELECT sum(o47_valor) as soma
  from orcsuplem
  inner join orcsuplemval on o47_codsup = o46_codsup
  inner join orcsuplemlan on o49_codsup = o47_codsup
  where o46_tiposup in(1012, 1013)
  and o49_data between '$dataini'
  and '$dt_fin'
  and o46_instit in ($instituicao);";

$rsSqlReabertura = db_query($sSqlReabertura);
if(pg_num_rows($rsSqlReabertura) > 0){
  $oReabertura = db_utils::fieldsMemory($rsSqlReabertura,0);
  $nReaberturaCredPrevAtu += $oReabertura->soma;
}

$oLinha = $m_reab_credito_adicionais["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $nReaberturaCredPrevAtu += $oColunas->colunas[2]->o117_valor;
  $nReaberturaCredAteBim  += $oColunas->colunas[4]->o117_valor;

}


$tot_saldo_anterior              = $nSuperavitFinancPrevAtu + $nReaberturaCredPrevAtu;

$tot_saldo_anteriormenosprevisao = $nSuperavitFinancAteBim  + $nReaberturaCredAteBim;

$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SALDO DE EXERCÍCIOS ANTERIORES",'TR',0,"L",0);
$pdf->cell(20,$alt,'-','TR',0,"C",0);
$pdf->cell(20,$alt,db_formatar($tot_saldo_anterior ,'f'),'TR',0,"R",0);
$pdf->cell(25,$alt,'-','TR',0,"C",0);
$pdf->cell(10,$alt,'-','TR',0,"C",0);
$pdf->cell(25,$alt,db_formatar($tot_saldo_anteriormenosprevisao,'f'),'TR',0,"R",0);
$pdf->cell(10,$alt,'-','TR',0,"C",0);
// % (b/a)
$pdf->cell(20,$alt,'-','T',1,"C",0);

$pdf->cell(60,$alt,"(UTILIZADOS PARA CRÉDITOS ADICIONAIS)",'R',0,"L",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(20,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
$pdf->cell(25,$alt,'','R',0,"R",0);
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'',0,1,"R",0);
//--------------------------------
// quebra a pagina,

$pdf->setfont('arial','',6);

$pdf->cell(60,$alt,espaco($n1)."Superávit Financeiro"       ,'R',0,"L",0);
$pdf->cell(20,$alt,'-'                                      ,'R',0,"C",0);
$pdf->cell(20,$alt,db_formatar($nSuperavitFinancPrevAtu,'f'),'R',0,"R",0);
$pdf->cell(25,$alt,'-'                                      ,'R',0,"C",0);
$pdf->cell(10,$alt,'-'                                      ,'R',0,"C",0);
$pdf->cell(25,$alt,db_formatar($nSuperavitFinancAteBim ,'f'),'R',0,"R",0);
$pdf->cell(10,$alt,'-'                                      ,'R',0,"C",0);
$pdf->cell(20,$alt,'-'                                      ,0  ,1,"C",0);

$pdf->cell(60,$alt,espaco($n1)."Reabertura de Créditos Adicionais",'BR',0,"L",0);
$pdf->cell(20,$alt,'-'                                            ,'BR',0,"C",0);
$pdf->cell(20,$alt,db_formatar($nReaberturaCredPrevAtu,'f')       ,'BR',0,"R",0);
$pdf->cell(25,$alt,'-'                                            ,'BR',0,"C",0);
$pdf->cell(10,$alt,'-'                                            ,'BR',0,"C",0);
$pdf->cell(25,$alt,db_formatar($nReaberturaCredAteBim ,'f')       ,'BR',0,"R",0);
$pdf->cell(10,$alt,'-'                                            ,'BR',0,"C",0);
$pdf->cell(20,$alt,'-'                                            ,'B' ,1,"C",0);

$pdf->Ln(2);

imprime_cabec_desp($alt,&$pdf, $bimestre);

/*
 * #quadrodespesa#
 * Inicio do quadro da despesa.
 */
  /*
   * quando for o ultimo bimestre ($bimestre = 6) ,
   * devemos acrescentar a informação de restos a pagar (usamos o campo a_liquidar da dotacao saldo)
 */
  $iTamDotInical     = 20;
$iTamCreditos      = 20;
$iTamDotAtualizada = 20;
$iTamEmpNoBim      = 14;
$iTamEmpAteBim     = 14;
$iTamLiqNoBim      = 14;
$iTamLiqAteBim     = 14;
$iTamRPInscritos   = 14;
$iTampercentualGF  = 10;
$iTamanhoFonte     = 5;
if ($bimestre == 6) {

  $iTamDotInical     = 15;
  $iTamCreditos      = 15;
  $iTamDotAtualizada = 15;
  $iTamEmpNoBim      = 13;
  $iTamEmpAteBim     = 13;
  $iTamLiqNoBim      = 11;
  $iTamLiqAteBim     = 12;
  $iTamRPInscritos   = 18;
  $iTampercentualGF  = 10;
  $iTamanhoFonte     = 4;

}
//--------------------------------
$pos_desp_VII = $pdf->getY();
// guarda posição corrente
$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"DESPESAS (EXCETO INTRA-ORÇAMENTÁRIAS) (VIII)",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
// % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);

$pos_corrente = $pdf->getY();
$pdf->setfont('arial','b',$iTamanhoFonte);
$pdf->cell(55,$alt,"DESPESAS CORRENTES",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
// % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_pessoal_enc_sociais["parametros"], $result_desp, false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','',$iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."PESSOAL E ENCARGOS SOCIAIS",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar($tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;
//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_juros_enc_divida["parametros"], $result_desp, false);
$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."JUROS E ENCARGOS DA DÍVIDA",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_outras_despesas_correntes["parametros"],$result_desp,false);

$oLinha = $m_outras_despesas_correntes["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $tot_inicial    += $oColunas->colunas[1]->o117_valor;
  $tot_adicional  += $oColunas->colunas[2]->o117_valor;
  $tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  $tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  $tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  $tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {

    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }

}

$tot_atualizada = $tot_inicial+$tot_adicional;

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."OUTRAS DESPESAS CORRENTES",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;

// ----------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_corrente);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($somador_VI_inicial,'f'),'0',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($somador_VI_adicional,'f'),'0',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($somador_VI_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($somador_VI_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($somador_VI_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($somador_VI_liqatebim,'f'),'0',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($somador_VI_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($somador_VI_liqatebim+$somador_VI_inscritos)*100)/($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional)-($somador_VI_inscritos+$somador_VI_liqatebim),'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki

//--------------------------------
$pos_capital = $pdf->getY();
//pega posição e guarda
$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"DESPESAS DE CAPITAL",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
// % (b/a)
$pdf->cell(10,$alt,'','R',0,"R",0);
// % (b/a)
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_investimentos["parametros"],$result_desp,false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."INVESTIMENTOS",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;

$v_VI_inicial    = $tot_inicial ;
$v_VI_adicional  = $tot_adicional;
// ;
$v_VI_emp_nobim  = $tot_emp_nobim;
$v_VI_emp_atebim = $tot_emp_atebim;
$v_VI_liq_nobim  = $tot_liq_nobim;
$v_VI_liqatebim  = $tot_liq_atebim;
$v_VI_inscritos  = $tot_inscritos_rp;
//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_inversoes_financeiras["parametros"],$result_desp,false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."INVERSÕES FINANCEIRAS",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;
$v_VI_inicial    += $tot_inicial ;
$v_VI_adicional  += $tot_adicional;
// ;
$v_VI_emp_nobim  += $tot_emp_nobim;
$v_VI_emp_atebim += $tot_emp_atebim;
$v_VI_liq_nobim  += $tot_liq_nobim;
$v_VI_liqatebim  += $tot_liq_atebim;
$v_VI_inscritos  += $tot_inscritos_rp;


//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;
atualizaTotaisDespesa($m_amortizacao_divida["parametros"],$result_desp,false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."AMORTIZAÇÃO DA DÍVIDA",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;
$v_VI_inicial    += $tot_inicial ;
$v_VI_adicional  += $tot_adicional;
// ;
$v_VI_emp_nobim  += $tot_emp_nobim;
$v_VI_emp_atebim += $tot_emp_atebim;
$v_VI_liq_nobim  += $tot_liq_nobim;
$v_VI_liqatebim  += $tot_liq_atebim;
$v_VI_inscritos +=  $tot_inscritos_rp;

// ----------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_capital);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($v_VI_inicial,'f'),'0',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($v_VI_adicional,'f'),'0',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($v_VI_inicial+$v_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($v_VI_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($v_VI_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($v_VI_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($v_VI_liqatebim,'f'),'0',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($v_VI_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar(($v_VI_liqatebim+$v_VI_inscritos)*100/($v_VI_inicial+$v_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($v_VI_inicial+$v_VI_adicional)-($v_VI_inscritos+$v_VI_liqatebim),'f'),'0',0,"R",0);

$pdf->setY($pos_atu);
// desce novamente até aki

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim   = 0;
$tot_emp_atebim  = 0;
$tot_liq_nobim   = 0;
$tot_liq_atebim  = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_reserva_contigencia["parametros"],$result_desp,false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"RESERVA DE CONTINGÊNCIA",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;


// // // // // // //  RPPS
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_reserva_rpps["parametros"],$result_desp,false);

$tot_atualizada = $tot_inicial + $tot_adicional;
$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"RESERVA DO RPPS",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)

$somador_VI_inicial    += $tot_inicial ;
$somador_VI_adicional  += $tot_adicional;
// ;
$somador_VI_emp_nobim  += $tot_emp_nobim;
$somador_VI_emp_atebim += $tot_emp_atebim;
$somador_VI_liq_nobim  += $tot_liq_nobim;
$somador_VI_liqatebim  += $tot_liq_atebim;
$somador_VI_inscritos  += $tot_inscritos_rp;

$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_desp_VII);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($somador_VI_inicial,'f'),'0',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($somador_VI_adicional,'f'),'0',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($somador_VI_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($somador_VI_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($somador_VI_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($somador_VI_liqatebim,'f'),'0',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($somador_VI_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($somador_VI_liqatebim+$somador_VI_inscritos)*100)/($somador_VI_inicial+$somador_VI_adicional),'f'),'0',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($somador_VI_inicial+$somador_VI_adicional)-($somador_VI_inscritos+$somador_VI_liqatebim),'f'),'0',0,"R",0);
$pdf->setY($pos_atu);
// desce novamente até aki

// // // // // // //  INTERFERENCIAS ORÇAMENTARIAS- DESPESAS(INTRA-ORÇAMENTÁRIAS) (IX)
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;
// atualizaTotaisDespesa($m_in["parametros"],$result_desp,false);

for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);
  $estrutural = $o58_elemento."00";
  if (substr($estrutural,3,2)=='91' ) {
    $tot_inicial    += $dot_ini;
    $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
    // adicional;
    $tot_emp_nobim  += $empenhado  - $anulado;
    $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
    $tot_liq_nobim  += $liquidado;
    $tot_liq_atebim += $liquidado_acumulado;
    if($bimestre == 6) {
      $tot_inscritos_rp += $empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado;
    }

  }
}

$tot_atualizada = $tot_inicial + $tot_adicional;
if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){

    $somador_IX_inicial    = 0;
    $somador_IX_adicional  = 0;
    $somador_IX_emp_nobim  = 0;
    $somador_IX_emp_atebim = 0;
    $somador_IX_liq_nobim  = 0;
    $somador_IX_liqatebim  = 0;
    $somador_IX_inscritos  = 0;

    $v_desp_cor_intra_inicial    = 0;
    $v_desp_cor_intra_adicional  = 0;
    $v_desp_cor_intra_emp_nobim  = 0;
    $v_desp_cor_intra_emp_atebim = 0;
    $v_desp_cor_intra_liq_nobim  = 0;
    $v_desp_cor_intra_liqatebim  = 0;
    $v_desp_cor_intra_inscritos  = 0;

    $v_desp_cap_intra_inicial    = 0;
    $v_desp_cap_intra_adicional  = 0;
    $v_desp_cap_intra_emp_nobim  = 0;
    $v_desp_cap_intra_emp_atebim = 0;
    $v_desp_cap_intra_liq_nobim  = 0;
    $v_desp_cap_intra_liqatebim  = 0;
    $v_desp_cap_intra_inscritos  = 0;

    $pdf->setfont('arial','b', $iTamanhoFonte);
    $pdf->cell(55,$alt,"DESPESAS (INTRA-ORÇAMENTÁRIAS) (IX)",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)

    $somador_IX_inicial    += $tot_inicial;
    $somador_IX_adicional  += $tot_adicional;
    $somador_IX_emp_nobim  += $tot_emp_nobim;
    $somador_IX_emp_atebim += $tot_emp_atebim;
    $somador_IX_liq_nobim  += $tot_liq_nobim;
    $somador_IX_liqatebim  += $tot_liq_atebim;
    $somador_IX_inscritos  += $tot_inscritos_rp;
    $pos_corrente_intra = $pdf->y;
    $pdf->setfont('arial','b',$iTamanhoFonte);
    $pdf->cell(55,$alt,"DESPESAS CORRENTES",'R',0,"L",0);
    $pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
    $pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
    $pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
    // % (b/a)
    $pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
    // % (b/a)
    $pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
    // % (b/a)
    $pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
    }
    // % (b/a)
    $pdf->cell(10,$alt,'','R',0,"R",0);
    // % (b/a)
    $pdf->cell(14,$alt,'',0,1,"R",0);
  }

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim  = 0;
$tot_emp_atebim = 0;
$tot_liq_nobim  = 0;
$tot_liq_atebim = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_pessoal_enc_sociais["parametros"], $result_desp, true);

$v_desp_cor_intra_inicial    = $tot_inicial;
$v_desp_cor_intra_adicional  = $tot_adicional;
$v_desp_cor_intra_emp_nobim  = $tot_emp_nobim;
$v_desp_cor_intra_emp_atebim = $tot_emp_atebim;
$v_desp_cor_intra_liq_nobim  = $tot_liq_nobim;
$v_desp_cor_intra_liqatebim  = $tot_liq_atebim;
$v_desp_cor_intra_inscritos  = $tot_inscritos_rp;
$tot_atualizada = $tot_inicial + $tot_adicional;
if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','', $iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."PESSOAL E ENCARGOS SOCIAIS",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim  = 0;
$tot_emp_atebim = 0;
$tot_liq_nobim  = 0;
$tot_liq_atebim = 0;
$tot_inscritos_rp = 0;
atualizaTotaisDespesa($m_juros_enc_divida["parametros"], $result_desp, true);

$v_desp_cor_intra_inicial    += $tot_inicial;
$v_desp_cor_intra_adicional  += $tot_adicional;
$v_desp_cor_intra_emp_nobim  += $tot_emp_nobim;
$v_desp_cor_intra_emp_atebim += $tot_emp_atebim;
$v_desp_cor_intra_liq_nobim  += $tot_liq_nobim;
$v_desp_cor_intra_liqatebim  += $tot_liq_atebim;
$v_desp_cor_intra_inscritos  += $tot_inscritos_rp;
$tot_atualizada = $tot_inicial + $tot_adicional;

if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','',$iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."JUROS E ENCARGOS DA DÍVIDA",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim  = 0;
$tot_emp_atebim = 0;
$tot_liq_nobim  = 0;
$tot_liq_atebim = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_outras_despesas_correntes["parametros"],$result_desp,true);

$oLinha = $m_outras_despesas_correntes["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  $tot_inicial    += $oColunas->colunas[1]->o117_valor;
  $tot_adicional  += $oColunas->colunas[2]->o117_valor;
  $tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  $tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  $tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  $tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {
    
    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }

}

$tot_atualizada = $tot_inicial+$tot_adicional;

$v_desp_cor_intra_inicial    += $tot_inicial;
$v_desp_cor_intra_adicional  += $tot_adicional;
$v_desp_cor_intra_emp_nobim  += $tot_emp_nobim;
$v_desp_cor_intra_emp_atebim += $tot_emp_atebim;
$v_desp_cor_intra_liq_nobim  += $tot_liq_nobim;
$v_desp_cor_intra_liqatebim  += $tot_liq_atebim;
$v_desp_cor_intra_inscritos  += $tot_inscritos_rp;

$tot_atualizada = $tot_inicial + $tot_adicional;

if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','', $iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."OUTRAS DESPESAS CORRENTES",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

if ($v_desp_cor_intra_inicial    > 0 || $v_desp_cor_intra_adicional > 0 || $v_desp_cor_intra_emp_nobim > 0 ||
  $v_desp_cor_intra_emp_atebim > 0 || $v_desp_cor_intra_liq_nobim > 0 || $v_desp_cor_intra_liqatebim > 0){
    $pos_atu = $pdf->y;
    // posição atual
    // sobe, escreve e desce
    $pdf->setY($pos_corrente_intra);
    $pdf->setX(65);
    $pdf->cell($iTamDotInical,$alt,db_formatar($v_desp_cor_intra_inicial,'f'),'0',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($v_desp_cor_intra_adicional,'f'),'0',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($v_desp_cor_intra_inicial+$v_desp_cor_intra_adicional),'f'),'0',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($v_desp_cor_intra_emp_nobim,'f'),'0',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($v_desp_cor_intra_emp_atebim,'f'),'0',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($v_desp_cor_intra_liq_nobim,'f'),'0',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($v_desp_cor_intra_liqatebim,'f'),'0',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($v_desp_cor_intra_inscritos,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($v_desp_cor_intra_liqatebim+$v_desp_cor_intra_inscritos)*100)/($v_desp_cor_intra_inicial+$v_desp_cor_intra_adicional),'f'),'0',0,"R",0);
    $pdf->cell(14,$alt,db_formatar(($v_desp_cor_intra_inicial+$v_desp_cor_intra_adicional)-($v_desp_cor_intra_inscritos+$v_desp_cor_intra_liqatebim),'f'),'0',0,"R",0);
    $pdf->setY($pos_atu);
    // desce novamente até aki
  }
//--------------------------------
$pos_capital_intra = $pdf->getY();

$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim  = 0;
$tot_emp_atebim = 0;
$tot_liq_nobim  = 0;
$tot_liq_atebim = 0;

atualizaTotaisDespesa($m_investimentos["parametros"],$result_desp,true);

$v_desp_cap_intra_inicial    = $tot_inicial;
$v_desp_cap_intra_adicional  = $tot_adicional;
$v_desp_cap_intra_emp_nobim  = $tot_emp_nobim;
$v_desp_cap_intra_emp_atebim = $tot_emp_atebim;
$v_desp_cap_intra_liq_nobim  = $tot_liq_nobim;
$v_desp_cap_intra_liqatebim  = $tot_liq_atebim;
$v_desp_cap_intra_inscritos  = $tot_inscritos_rp;
$tot_atualizada = $tot_inicial + $tot_adicional;

if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','',$iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."INVESTIMENTOS",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_investimentos["parametros"],$result_desp,true);

$v_desp_cap_intra_inicial    += $tot_inicial;
$v_desp_cap_intra_adicional  += $tot_adicional;
$v_desp_cap_intra_emp_nobim  += $tot_emp_nobim;
$v_desp_cap_intra_emp_atebim += $tot_emp_atebim;
$v_desp_cap_intra_liq_nobim  += $tot_liq_nobim;
$v_desp_cap_intra_liqatebim  += $tot_liq_atebim;
$v_desp_cap_intra_inscritos  += $tot_inscritos_rp;
$tot_atualizada = $tot_inicial + $tot_adicional;

if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','',$iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."INVERSÕES FINANCEIRAS",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesa($m_amortizacao_divida["parametros"],$result_desp,true);

$v_desp_cap_intra_inicial    += $tot_inicial;
$v_desp_cap_intra_adicional  += $tot_adicional;
$v_desp_cap_intra_emp_nobim  += $tot_emp_nobim;
$v_desp_cap_intra_emp_atebim += $tot_emp_atebim;
$v_desp_cap_intra_liq_nobim  += $tot_liq_nobim;
$v_desp_cap_intra_liqatebim  += $tot_liq_atebim;
$v_desp_cap_intra_inscritos  += $tot_inscritos_rp;
$tot_atualizada = $tot_inicial + $tot_adicional;

if ($tot_inicial    > 0 || $tot_adicional > 0 || $tot_emp_nobim  > 0 ||
  $tot_emp_atebim > 0 || $tot_liq_nobim > 0 || $tot_liq_atebim > 0){
    $pdf->setfont('arial','',$iTamanhoFonte);
    $pdf->cell(55,$alt,espaco($n2)."AMORTIZAÇÃO DA DÍVIDA",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
    // % (j/f)
    $pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
    // (f-j)
  }

if ($v_desp_cap_intra_inicial    > 0 || $v_desp_cap_intra_adicional > 0 || $v_desp_cap_intra_emp_nobim > 0 ||
  $v_desp_cap_intra_emp_atebim > 0 || $v_desp_cap_intra_liq_nobim > 0 || $v_desp_cap_intra_liqatebim > 0){
    //  $pos_atu = $pdf->y;
    // posição atual
    // sobe, escreve e desce
    //   $pdf->setY($pos_capital_intra);
    //  $pdf->setX(50);
    $pdf->setfont('arial','b',$iTamanhoFonte);
    $pdf->cell(55,$alt,"DESPESAS DE CAPITAL",'R',0,"L",0);
    $pdf->cell($iTamDotInical,$alt,db_formatar($v_desp_cap_intra_inicial,'f'),"R",0,"R",0);
    $pdf->cell($iTamCreditos,$alt,db_formatar($v_desp_cap_intra_adicional,'f'),"R",0,"R",0);
    $pdf->cell($iTamDotAtualizada,$alt,db_formatar(($v_desp_cap_intra_inicial+$v_desp_cap_intra_adicional),'f'),"R",0,"R",0);
    $pdf->cell($iTamEmpNoBim,$alt,db_formatar($v_desp_cap_intra_emp_nobim,'f'),"R",0,"R",0);
    $pdf->cell($iTamEmpAteBim,$alt,db_formatar($v_desp_cap_intra_emp_atebim,'f'),"R",0,"R",0);
    $pdf->cell($iTamLiqNoBim,$alt,db_formatar($v_desp_cap_intra_liq_nobim,'f'),"R",0,"R",0);
    $pdf->cell($iTamLiqAteBim,$alt,db_formatar($v_desp_cap_intra_liqatebim,'f'),"R",0,"R",0);
    if ($bimestre == 6) {
      $pdf->cell($iTamRPInscritos,$alt, db_formatar($v_desp_cap_intra_inscritos,'f'),'R',0,"R",0);
    }
    @$pdf->cell(10,$alt,db_formatar((($v_desp_cap_intra_liqatebim+$v_desp_cap_intra_inscritos)*100)/($v_desp_cap_intra_inicial+$v_desp_cap_intra_adicional),'f'),"R",0,"R",0);
    $pdf->cell(14,$alt,db_formatar(($v_desp_cap_intra_inicial+$v_desp_cap_intra_adicional)-($v_desp_cap_intra_inscritos+$v_desp_cap_intra_liqatebim),'f'),'0',1,"R",0);
    // $pdf->setY($pos_atu);
    // desce novamente até aki
  }
//--------------------------------

// // // // // // //  subtotal das despesas(VI)

$somador_VI_inicial    += $somador_IX_inicial;
$somador_VI_adicional  += $somador_IX_adicional;
$somador_VI_emp_nobim  += $somador_IX_emp_nobim;
$somador_VI_emp_atebim += $somador_IX_emp_atebim;
$somador_VI_liq_nobim  += $somador_IX_liq_nobim;
$somador_VI_liqatebim  += $somador_IX_liqatebim;
$somador_VI_inscritos  += $somador_IX_inscritos;

$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"SUBTOTAL DAS DESPESAS (X) = (VIII+IX)",'TBR',0,"L",0);
$pdf->cell($iTamDotInical,     $alt, db_formatar($somador_VI_inicial,'f'),'TBR',0,"R",0);
$pdf->cell($iTamCreditos,      $alt, db_formatar($somador_VI_adicional,'f'),'TBR',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt, db_formatar(($somador_VI_inicial+$somador_VI_adicional),'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpNoBim,      $alt, db_formatar($somador_VI_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpAteBim,     $alt, db_formatar($somador_VI_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamLiqNoBim,      $alt, db_formatar($somador_VI_liq_nobim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamLiqAteBim,     $alt, db_formatar($somador_VI_liqatebim,'f'),'TBR',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($somador_VI_inscritos,'f'),'TBR',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar(($somador_VI_liqatebim+$somador_VI_inscritos)*100/($somador_VI_inicial+$somador_VI_adicional),'f'),'TBR',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar((($somador_VI_inicial+$somador_VI_adicional)-($somador_VI_inscritos+$somador_VI_liqatebim)),'f'),'TB',1,"R",0);
// (f-j)
//--------------------------------
$pos_div_amort = $pdf->getY();
// guarda posição
$pdf->setfont('arial','b',$iTamanhoFonte);
$pdf->cell(55,$alt,"AMORTIZAÇÃO DA DÍVIDA/REFINANCIAMENTO (XI)",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
$pdf->cell(10,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'',0,1,"R",0);
//-------------------------------
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();
$pdf->setfont('arial','',$iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n1)."Amortização da Dívida Interna",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
$pdf->cell(10,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial    = 0;
$tot_adicional  = 0;
$tot_emp_nobim  = 0;
$tot_emp_atebim = 0;
$tot_liq_nobim  = 0;
$tot_liq_atebim = 0;
$tot_inscritos_rp = 0;
$tot_atualizada   = 0;

$oLinha = $m_amort_divida_int_mobiliario["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  @$tot_inicial    += $oColunas->colunas[1]->o117_valor;
  @$tot_adicional  += $oColunas->colunas[2]->o117_valor;
  @$tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  @$tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  @$tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  @$tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {
    
    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }
}

$tot_atualizada = $tot_inicial+$tot_adicional;

$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."Dívida Mobiliária",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);

$somador_VII_inicial    += $tot_inicial ;
$somador_VII_adicional  += $tot_adicional;
// ;
$somador_VII_emp_nobim  += $tot_emp_nobim;
$somador_VII_emp_atebim += $tot_emp_atebim;
$somador_VII_liq_nobim  += $tot_liq_nobim;
$somador_VII_liqatebim  += $tot_liq_atebim;
$somador_VII_inscritos  += $tot_inscritos_rp;
$v_inicial    = $tot_inicial ;
$v_adicional  = $tot_adicional;
// ;
$v_nobim      = $tot_emp_nobim;
$v_emp_atebim = $tot_emp_atebim;
$v_liq_nobim  = $tot_liq_nobim;
$v_liqatebim  = $tot_liq_atebim;
$v_inscritos  = $tot_inscritos_rp;

//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

atualizaTotaisDespesaDivida($m_amort_divida_int_outras["parametros"],$result_desp);

$oLinha = $m_amort_divida_int_outras["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  @$tot_inicial    += $oColunas->colunas[1]->o117_valor;
  @$tot_adicional  += $oColunas->colunas[2]->o117_valor;
  @$tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  @$tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  @$tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  @$tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {

    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }
}

$tot_atualizada = $tot_inicial+$tot_adicional;

$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."Outras Dívidas",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VII_inicial    += $tot_inicial ;
$somador_VII_adicional  += $tot_adicional;
// ;
$somador_VII_emp_nobim  += $tot_emp_nobim;
$somador_VII_emp_atebim += $tot_emp_atebim;
$somador_VII_liq_nobim  += $tot_liq_nobim;
$somador_VII_liqatebim  += $tot_liq_atebim;
$somador_VII_inscritos  += $tot_inscritos_rp;
$v_inicial    += $tot_inicial ;
$v_adicional  += $tot_adicional;
// ;
$v_nobim      += $tot_emp_nobim;
$v_emp_atebim += $tot_emp_atebim;
$v_liq_nobim  += $tot_liq_nobim;
$v_liqatebim  += $tot_liq_atebim;
$v_inscritos  += $tot_inscritos_rp;
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($v_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($v_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($v_inicial+$v_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($v_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($v_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($v_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($v_liqatebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($v_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($v_liq_atebim+$v_inscritos_rp)*100)/$v_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($v_atualizada - ($v_inscritos+$v_liqatebim)),'f'),'0',1,"R",0);

$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// pega altura e guarda
$pos_y = $pdf->getY();

$pdf->setfont('arial','',$iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n1)."Amortização da Dívida Externa",'R',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt,'','R',0,"R",0);
}
$pdf->cell(10,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

$oLinha = $m_amort_divida_ext_mobiliario["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas) {

  @$tot_inicial    += $oColunas->colunas[1]->o117_valor;
  @$tot_adicional  += $oColunas->colunas[2]->o117_valor;
  @$tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  @$tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  @$tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  @$tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {

    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }
}

$tot_atualizada = $tot_inicial+$tot_adicional;

$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."Dívida Mobiliária",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VII_inicial    += $tot_inicial ;
$somador_VII_adicional  += $tot_adicional;
// ;
$somador_VII_emp_nobim  += $tot_emp_nobim;
$somador_VII_emp_atebim += $tot_emp_atebim;
$somador_VII_liq_nobim  += $tot_liq_nobim;
$somador_VII_liqatebim  += $tot_liq_atebim;
$somador_VII_inscritos  += $tot_inscritos_rp;

$v_inicial    = $tot_inicial ;
$v_adicional  = $tot_adicional;
// ;
$v_nobim  = $tot_emp_nobim;
$v_emp_atebim = $tot_emp_atebim;
$v_liq_nobim  = $tot_liq_nobim;
$v_liqatebim  = $tot_liq_atebim;
$v_inscritos  = $tot_inscritos_rp;

//--------------------------------
$tot_inicial      = 0;
$tot_adicional    = 0;
$tot_emp_nobim    = 0;
$tot_emp_atebim   = 0;
$tot_liq_nobim    = 0;
$tot_liq_atebim   = 0;
$tot_inscritos_rp = 0;

$oLinha = $m_amort_divida_ext_outras["parametros"]->getValoresSomadosColunas($instituicao, $anousu);
foreach ($oLinha as $oColunas){

  @$tot_inicial    += $oColunas->colunas[1]->o117_valor;
  @$tot_adicional  += $oColunas->colunas[2]->o117_valor;
  @$tot_emp_nobim  += $oColunas->colunas[3]->o117_valor;
  @$tot_emp_atebim += $oColunas->colunas[4]->o117_valor;
  @$tot_liq_nobim  += $oColunas->colunas[5]->o117_valor;
  @$tot_liq_atebim += $oColunas->colunas[6]->o117_valor;

  if ($bimestre == 6) {

    if (isset($oColunas->colunas[7]->o117_valor)) {
      $tot_inscritos_rp += $oColunas->colunas[7]->o117_valor;
    }
  }
}

$tot_atualizada = $tot_inicial+$tot_adicional;
$pdf->setfont('arial','', $iTamanhoFonte);
$pdf->cell(55,$alt,espaco($n2)."Outras Dívidas",'R',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($tot_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($tot_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($tot_inicial+$tot_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($tot_emp_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($tot_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($tot_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($tot_liq_atebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($tot_inscritos_rp,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($tot_liq_atebim+$tot_inscritos_rp)*100)/$tot_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($tot_atualizada - ($tot_inscritos_rp+$tot_liq_atebim)),'f'),'0',1,"R",0);
// (f-j)
$somador_VII_inicial    += $tot_inicial ;
$somador_VII_adicional  += $tot_adicional;
// ;
$somador_VII_emp_nobim  += $tot_emp_nobim;
$somador_VII_emp_atebim += $tot_emp_atebim;
$somador_VII_liq_nobim  += $tot_liq_nobim;
$somador_VII_liqatebim  += $tot_liq_atebim;
$somador_VII_inscritos  += $tot_inscritos_rp;
$v_inicial    += $tot_inicial ;
$v_adicional  += $tot_adicional;
// ;
$v_nobim      += $tot_emp_nobim;
$v_emp_atebim += $tot_emp_atebim;
$v_liq_nobim  += $tot_liq_nobim;
$v_liqatebim  += $tot_liq_atebim;
$v_inscritos  += $tot_inscritos_rp;
// ------------------------------
// --------------------------------
$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
$pdf->setY($pos_y);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($v_inicial,'f'),'R',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($v_adicional,'f'),'R',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($v_inicial+$v_adicional),'f'),'R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($v_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($v_emp_atebim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($v_liq_nobim,'f'),'R',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($v_liqatebim,'f'),'R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($v_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar((($v_liq_atebim+$v_inscritos_rp)*100)/$v_atualizada,'f'),'R',0,"R",0);
$pdf->cell(14,$alt,db_formatar(($v_atualizada - ($v_inscritos+$v_liqatebim)),'f'),'0',1,"R",0);

$pdf->setY($pos_div_amort);
$pdf->setX(65);
$pdf->cell($iTamDotInical,$alt,db_formatar($somador_VII_inicial,'f'),'0',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($somador_VII_adicional,'f'),'0',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($somador_VII_inicial+$somador_VII_adicional),'f'),'0',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($somador_VII_emp_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($somador_VII_emp_atebim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($somador_VII_liq_nobim,'f'),'0',0,"R",0);
$pdf->cell($iTamLiqAteBim,$alt,db_formatar($somador_VII_liqatebim,'f'),'0',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamRPInscritos,$alt, db_formatar($somador_VII_inscritos,'f'),'R',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar(($somador_VII_liqatebim+$somador_VII_inscritos)*100/($somador_VII_inicial+$somador_VII_adicional),'f'),'R',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,db_formatar(($somador_VII_inicial+$somador_VII_adicional)-($somador_VII_inscritos+$somador_VII_liqatebim),'f'),'0',0,"R",0);

$pdf->setY($pos_atu);
// desce novamente até aki
//--------------------------------
// // // // // // //  subtotal com refinamento
$somador_VIII_inicial    =  $somador_VI_inicial   + $somador_VII_inicial;
$somador_VIII_adicional  =  $somador_VI_adicional + $somador_VII_adicional ;
// ;
$somador_VIII_emp_nobim  =  $somador_VI_emp_nobim + $somador_VII_emp_nobim ;
$somador_VIII_emp_atebim =  $somador_VI_emp_atebim+ $somador_VII_emp_atebim ;
$somador_VIII_liq_nobim  =  $somador_VI_liq_nobim + $somador_VII_liq_nobim ;
$somador_VIII_liqatebim  =  $somador_VI_liqatebim + $somador_VII_liqatebim ;
$somador_VIII_inscritos  =  $somador_VI_inscritos + $somador_VII_inscritos ;

$pdf->setfont('arial','b',$iTamanhoFonte);
$pdf->cell(55,$alt,"SUBTOTAL COM REFINANCIAMENTO (XII) = (X + XI)",'TBR',0,"L",0);
$pdf->cell($iTamDotInical,$alt,db_formatar($somador_VIII_inicial,'f'),'TBR',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($somador_VIII_adicional,'f'),'TBR',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($somador_VIII_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($somador_VIII_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($somador_VIII_liq_nobim,'f'),'TBR',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamLiqAteBim+$iTamRPInscritos,$alt,db_formatar($somador_VIII_liqatebim+$somador_VIII_inscritos,'f'),'TBR',0,"R",0);
} else {
  $pdf->cell($iTamLiqAteBim,$alt,db_formatar($somador_VIII_liqatebim,'f'),'TBR',0,"R",0);
}
@$pdf->cell(10,$alt,db_formatar(($somador_VIII_liqatebim+$somador_VIII_inscritos)*100/($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);

//$pdf->cell(14,$alt,'>>'.db_formatar((($somador_VIII_inicial+$somador_VIII_adicional) - ($somador_VII_inscritos+$somador_VIII_liqatebim)),'f'),'TB',1,"R",0);
$pdf->cell(14,$alt,db_formatar((($somador_VI_inicial  +$somador_VI_adicional  ) - ($somador_VI_inscritos +$somador_VI_liqatebim)),'f'),'TB',1,"R",0);

//--------------------------------

// // // // // // //  subtotal com refinamento
$pos_superavit = $pdf->getY();
$pdf->setfont('arial','b', $iTamanhoFonte);
$pdf->cell(55,$alt,"SUPERÁVIT (XIII)",'TBR',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'-','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'-','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'-','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'-','R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,'-','R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,'-','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamLiqAteBim+$iTamRPInscritos,$alt,'-','R',0,"R",0);
} else {
  $pdf->cell($iTamLiqAteBim,$alt,'-','R',0,"R",0);
}
$pdf->cell(10,$alt,'-','R',0,"R",0);
$pdf->cell(14,$alt,'-',0,1,"R",0);
// (f-j)
//--------------------------------

$pos_total_desp = $pdf->getY();
$pdf->setfont('arial','b',$iTamanhoFonte);
$pdf->cell(55,$alt,"TOTAL (XIV) = (XII + XIII)",'TBR',0,"L",0);
$pdf->cell($iTamDotInical, $alt,'','R',0,"R",0);
$pdf->cell($iTamCreditos, $alt,'','R',0,"R",0);
$pdf->cell($iTamDotAtualizada, $alt,'','R',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,'','R',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,'','R',0,"R",0);
if ($bimestre == 6) {
  $pdf->cell($iTamLiqAteBim+$iTamRPInscritos,$alt,'','R',0,"R",0);
} else {
  $pdf->cell($iTamLiqAteBim,$alt,'','R',0,"R",0);
}
$pdf->cell(10,$alt,'','R',0,"R",0);
$pdf->cell(14,$alt,'',0,1,"R",0);
//--------------------------------
// calculo do superávit ou déficit
// verifica se tem superávit
$TEM_SUPERAVIT = false;
if ($bimestre == 6) {
  $verifica = $somador_VIII_liqatebim + $somador_VIII_inscritos;
} else {
  $verifica = $somador_VIII_liqatebim;
}
$total_superavit = 0;
$total_deficit   = 0;
if ($somador_III_atebim > $verifica) {
  // receita realizada maior que despesa liquidada:superávit
  if ($bimestre == 6) {
    $somador_IX_liqatebim = $somador_III_atebim - ($somador_VIII_liqatebim+$somador_VIII_inscritos);
  } else {
    $somador_IX_liqatebim = $somador_III_atebim - $somador_VIII_liqatebim;
  }
  $pos = $pdf->getY();

  $pdf->setY($pos_superavit);
  if ($bimestre == 6) {
    $iPosicaoSuperAvit = 163;
  } else {
    $iPosicaoSuperAvit = 167;
  }
  $pdf->setX($iPosicaoSuperAvit);
  $pdf->cell(14,$alt, db_formatar(abs($somador_IX_liqatebim),'f'),'0',0,"R",0);
  // %
  $pdf->setY($pos_deficit);
  $pdf->setX(145);
  $pdf->cell(25,$alt, "-",'0',0,"R",0);
  $pdf->setY($pos);
  $TEM_SUPERAVIT = true;
  $total_superavit = abs($somador_IX_liqatebim);
  $total_deficit   = 0;

} else {

  if ($bimestre == 6) {
    $somador_IV_atebim = abs($somador_III_atebim - ($somador_VIII_liqatebim+$somador_VIII_inscritos)) ;
  } else {
    $somador_IV_atebim = abs($somador_III_atebim - $somador_VIII_liqatebim) ;
  }
  $pos = $pdf->getY();
  $pdf->setY($pos_deficit);
  $pdf->setfont('arial','b', 6);
  $pdf->setX(145);
  $pdf->cell(25,$alt, db_formatar(abs($somador_IV_atebim),'f'),'0',0,"R",0);
  // %
  $pdf->setY($pos);
  $pdf->setfont('arial','b',$iTamanhoFonte);

  $total_superavit = 0;
  $total_deficit   = $somador_IV_atebim;
}

$pos = $pdf->getY();
$pdf->setY($pos_total_rec);
$somador_V_inicial    = $somador_III_inicial    + $somador_IV_inicial   ;
$somador_V_atualizada = $somador_III_atualizada + $somador_IV_atualizada  ;
// ;
$somador_V_nobim      = $somador_III_nobim      + $somador_IV_nobim  ;
$somador_V_atebim     = $somador_III_atebim     + $somador_IV_atebim ;
$somador_V_realizar   = $somador_III_realizar   + $somador_IV_realizar;

$somador_VII_atebim   = $somador_III_atebim     + $total_deficit;

$pdf->setfont('arial','B',6);
$pdf->setX(70);
$pdf->cell(20,$alt,db_formatar($somador_V_inicial,'f'),'TBR',0,"R",0);
$pdf->cell(20,$alt,db_formatar($somador_V_atualizada,'f'),'TBR',0,"R",0);
$pdf->cell(25,$alt,db_formatar($somador_V_nobim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_V_nobim*100)/$somador_V_atualizada,'f'),'TBR',0,"R",0);
// %
$pdf->cell(25,$alt,db_formatar($somador_VII_atebim,'f'),'TBR',0,"R",0);
@$pdf->cell(10,$alt,db_formatar(($somador_VII_atebim*100)/$somador_V_atualizada,'f'),'TBR',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,db_formatar($somador_V_realizar,'f'),'TB',1,"R",0);

/*
 * Totalizadores da despesa
 */
$pdf->setY($pos_total_desp);
$pdf->setX(65);
$pdf->setfont('arial','b',$iTamanhoFonte);
$pdf->cell($iTamDotInical,$alt,db_formatar($somador_VIII_inicial,'f'),'TBR',0,"R",0);
$pdf->cell($iTamCreditos,$alt,db_formatar($somador_VIII_adicional,'f'),'TBR',0,"R",0);
$pdf->cell($iTamDotAtualizada,$alt,db_formatar(($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpNoBim,$alt,db_formatar($somador_VIII_emp_nobim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamEmpAteBim,$alt,db_formatar($somador_VIII_emp_atebim,'f'),'TBR',0,"R",0);
$pdf->cell($iTamLiqNoBim,$alt,db_formatar($somador_VIII_liq_nobim,'f'),'TBR',0,"R",0);
if ($bimestre == 6) {
  $iTamanhoCelulaSuperavit = $iTamLiqAteBim + $iTamRPInscritos;
} else {
  $iTamanhoCelulaSuperavit = $iTamLiqAteBim;
}

if ($bimestre == 6) {
  $somador_XIV_atebim = $somador_VIII_liqatebim + $total_superavit + $somador_VIII_inscritos;
} else {
  $somador_XIV_atebim = $somador_VIII_liqatebim + $total_superavit;
}
$pdf->cell($iTamanhoCelulaSuperavit,$alt,db_formatar($somador_XIV_atebim ,'f'),'TBR',0,"R",0);

// (g/f)*100

@$pdf->cell(10,$alt,"-",'TBR',0,"R",0);
// % (j/f)
$pdf->cell(14,$alt,'-','TB',1,"R",0);
// (f-j)

$pdf->setY($pos);
//--------------------------------

$pdf->ln(2);
$oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo, 190);

$pdf->ln(20);

// assinaturas
assinaturas($pdf,$classinatura,'LRF');

// saida
$pdf->Output();

function atualizaTotaisReceita($oLinha, &$aReceitaIntra, $recordset){

  global $tot_inicial,
    $tot_atualizada,
    $tot_nobim,
    $tot_atebim,
    $tot_realizar,
    $anousu;

  for ($i=0; $i<pg_num_rows($recordset); $i++) {

    $oResultado = db_utils::fieldsmemory($recordset,$i);
    $oParametro = $oLinha->getParametros($anousu);
    foreach ($oParametro->contas as $oEstrutural) {

      $oVerificacao = $oLinha->match($oEstrutural ,$oParametro->orcamento,$oResultado, 1);
      if ($oVerificacao->match) {

        if (substr($oResultado->o57_fonte,1,1) == "7") {

          $aReceitaIntra["inicial"]    += $oResultado->saldo_inicial;
          $aReceitaIntra["atualizada"] += $oResultado->saldo_inicial_prevadic;
          $aReceitaIntra["nobim"]      += $oResultado->saldo_arrecadado;
          $aReceitaIntra["atebim"]     += $oResultado->saldo_arrecadado_acumulado;
          $aReceitaIntra["realizar"]   += $oResultado->saldo_a_arrecadar;

        } else {

          $tot_inicial    += $oResultado->saldo_inicial ;
          $tot_atualizada += $oResultado->saldo_inicial_prevadic;
          $tot_nobim      += $oResultado->saldo_arrecadado;
          $tot_atebim     += $oResultado->saldo_arrecadado_acumulado;
          $tot_realizar   += $oResultado->saldo_a_arrecadar;
        }
      }
    }
  }
}

function atualizaTotaisDespesa($oLinha,$recordset, $lIntra){

  global $bimestre,$anousu,$tot_inicial,$tot_adicional,$tot_emp_nobim,$tot_emp_atebim,$tot_liq_nobim,$tot_liq_atebim,$tot_inscritos_rp;

  for ($i=0; $i<pg_num_rows($recordset); $i++) {

    $oResultado = db_utils::fieldsmemory($recordset,$i);
    $oParametro = $oLinha->getParametros($anousu);
    foreach ($oParametro->contas as $oEstrutural) {

      $oVerificacao = $oLinha->match($oEstrutural ,$oParametro->orcamento,$oResultado, 2);
      if ($oVerificacao->match) {

        if ($lIntra) {
          if (substr($oResultado->o58_elemento."00",3,2) != '91') {
            continue;
          }
        } else {
          if (substr($oResultado->o58_elemento."00",3,2) == '91') {
            continue;
          }
        }

        $tot_inicial    += $oResultado->dot_ini;
        $tot_adicional  += $oResultado->suplementado_acumulado - $oResultado->reduzido_acumulado;
        $tot_emp_nobim  += $oResultado->empenhado  - $oResultado->anulado;
        $tot_emp_atebim += $oResultado->empenhado_acumulado  - $oResultado->anulado_acumulado;
        $tot_liq_nobim  += $oResultado->liquidado;
        $tot_liq_atebim += $oResultado->liquidado_acumulado;
        if($bimestre == 6) {
          $tot_inscritos_rp += $oResultado->empenhado_acumulado-$oResultado->anulado_acumulado-$oResultado->liquidado_acumulado;
        }

      }
    }
  }
}

function atualizaTotaisDespesaDivida($oLinha,$recordset){

  global $bimestre,$anousu,$tot_inicial,$tot_adicional,$tot_emp_nobim,$tot_emp_atebim,$tot_liq_nobim,$tot_liq_atebim,$tot_inscritos_rp;

  for ($i=0; $i<pg_num_rows($recordset); $i++) {

    $oResultado = db_utils::fieldsmemory($recordset,$i);
    $oParametro = $oLinha->getParametros($anousu);
    foreach ($oParametro->contas as $oEstrutural) {

      $oVerificacao = $oLinha->match($oEstrutural ,$oParametro->orcamento,$oResultado, 2);
      if ($oVerificacao->match) {

        $tot_inicial    += $oResultado->dot_ini;
        $tot_adicional  += $oResultado->suplementado_acumulado - $oResultado->reduzido_acumulado;
        $tot_emp_nobim  += $oResultado->empenhado  - $oResultado->anulado;
        $tot_emp_atebim += $oResultado->empenhado_acumulado  - $oResultado->anulado_acumulado;
        $tot_liq_nobim  += $oResultado->liquidado;
        $tot_liq_atebim += $oResultado->liquidado_acumulado;
        if($bimestre == 6) {
          $tot_inscritos_rp += $oResultado->empenhado_acumulado-$oResultado->anulado_acumulado-$oResultado->liquidado_acumulado;
        }

      }
    }
  }
}

?>