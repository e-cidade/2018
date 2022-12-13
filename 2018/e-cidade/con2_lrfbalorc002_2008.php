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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("libs/db_libtxt.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelvalor_classe.php");

function imprime_cabec_rec($alt,$pdf){
   $pdf->setfont('arial','',6);
   $pdf->cell(60,($alt*2),"RECEITAS",'TBR',0,"C",0);
   $pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
   $pdf->cell(20,$alt,"PREVISÃO",'TR',0,"C",0);
   $pdf->cell(70,$alt,"RECEITAS REALIZADAS",'TBR',0,"C",0);
   $pdf->cell(20,$alt,"SALDO A",'T',1,"C",0);
   //BR
   $pdf->setX(70);
   $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
   $pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);
   $pdf->cell(25,$alt,"No Bimestre (b)",'BR',0,"C",0);
   $pdf->cell(10,$alt,"% (b/a)",'BR',0,"C",0);
   $pdf->cell(25,$alt,"Até Bimestre (c)",'BR',0,"C",0);
   $pdf->cell(10,$alt,"% (c/a)",'BR',0,"C",0);
   $pdf->cell(20,$alt,"REALIZAR (a-c)",'B',0,"R",0);
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
     $pdf->cell(14,$alt,"SALDO A",'T',1,"C",0);
     //BR
     $pdf->setX(65);
     $pdf->cell(20,$alt,"INICIAL (d)",'BR',0,"C",0);
     $pdf->cell(20,$alt,"ADICIONAIS (e)",'BR',0,"C",0);
     $pdf->cell(20,$alt,"ATUALIZADA(f)=(d+e)",'BR',0,"C",0);
     $pdf->cell(14,$alt,"No Bimestre",'BR',0,"C",0);
     $pdf->cell(14,$alt,"Até Bimestre",'BR',0,"C",0);
     $pdf->cell(14,$alt,"No Bimestre",'BR',0,"C",0);
     $pdf->cell(14,$alt,"Até Bimestre(g)",'BR',0,"C",0);
     $pdf->cell(10,$alt,"% (g/f)",'BR',0,"C",0);
     $pdf->cell(14,$alt,"LIQUIDAR (f-g)",'B',0,"C",0);
     $pdf->ln(4);
     
   } else {
     
     $pdf->setfont('arial','',5);
     $pdf->cell(55,($alt*4),"DESPESAS",'TRB',0,"C",0);
     $pdf->cell(15,$alt,"DOTAÇÂO",'TR',0,"C",0);
     $pdf->cell(15,$alt,"CREDITOS",'TR',0,"C",0);
     $pdf->cell(15,$alt,"DOTAÇÂO",'TR',0,"C",0);
     $pdf->cell(26,$alt,"DESPESAS",'TR',0,"C",0);
     $pdf->cell(51,$alt,"DESPESAS EXECUTADAS",'TBR',0,"C",0);
     $pdf->cell(14,$alt,"SALDO A",'T',1,"C",0);
     $pdf->setX(65);
     
     $pdf->cell(15,$alt,"INICIAL (d)",'R',0,"C",0);
     $pdf->cell(15,$alt,"ADICIONAIS (e)",'R',0,"C",0);
     $pdf->cell(15,$alt,"ATUALIZADA",'R',0,"C",0);
     $pdf->cell(26,$alt,"EMPENHADAS",'BR',0,"C",0);
     $pdf->cell(23,$alt,"LIQUIDADAS",'TBR',0,"C",0);
     $pdf->cell(18,$alt,"INSCRITAS EM ",'TR',0,"C",0);
     $pdf->cell(10,$alt,"%",'TR',0,"C",0);
     $pdf->cell(14,$alt,"LIQUIDAR ",'',1,"C",0);
     //BR
     $pdf->setX(65);
     $pdf->cell(15,$alt,"",'R',0,"C",0);
     $pdf->cell(15,$alt,"",'R',0,"C",0);
     $pdf->cell(15,$alt,"(f)=(d+e)",'R',0,"C",0);
     $pdf->cell(13,$alt,"No Bim",'R',0,"C",0);
     $pdf->cell(13,$alt,"Até Bim",'R',0,"C",0);
     $pdf->cell(11,$alt,"No Bim",'R',0,"C",0);
     $pdf->cell(12,$alt,"Até Bim(g)",'R',0,"C",0);
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
$iCodigoPeriodo = '';
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$classinatura    = new cl_assinatura;
$orcparamrel     = new cl_orcparamrel;
$clconrelvalor   = new cl_conrelvalor;
$oDaoPeriodo     = db_utils::getDao("periodo");
if ($anousu >= 2010 ) {
  $sSqlPeriodo     = $oDaoPeriodo->sql_query($bimestre); 
  $iCodigoPeriodo  = $bimestre;
  $sSiglaPeriodo   = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla; 
  $dt              = data_periodo($anousu,$sSiglaPeriodo);
  $bimestre        = $sSiglaPeriodo;
}
$dt = datas_bimestre($bimestre,$anousu);
// no dbforms/db_funcoes.php
$dt_ini= $dt[0];
// data inicial do período
$dt_fin= $dt[1];
// data final do período

// seleciona matriz com estruturais selecionados pelo usuario
// variareis
$n1 = 5;
$n2= 10;

$instituicao  = str_replace("-",",",$db_selinstit);

$m_impostos   = $orcparamrel->sql_parametro('22','1',"f",$instituicao,$anousu);
$m_taxas          = $orcparamrel->sql_parametro('22','2',"f",$instituicao,$anousu);
$m_melhorias      = $orcparamrel->sql_parametro('22','3',"f",$instituicao,$anousu);
$m_sociais        = $orcparamrel->sql_parametro('22','4',"f",$instituicao,$anousu);

$m_economicas = $orcparamrel->sql_parametro('22','5',"f",$instituicao,$anousu);
$m_imobiliarias = $orcparamrel->sql_parametro('22','6',"f",$instituicao,$anousu);
$m_valmobiliarias = $orcparamrel->sql_parametro('22','7',"f",$instituicao,$anousu);

$m_permissoes = $orcparamrel->sql_parametro('22','8',"f",$instituicao,$anousu);
$m_patrimoniais = $orcparamrel->sql_parametro('22','9',"f",$instituicao,$anousu);

$m_vegetal  = $orcparamrel->sql_parametro('22','10',"f",$instituicao,$anousu);
$m_animal = $orcparamrel->sql_parametro('22','11',"f",$instituicao,$anousu);
$m_agropecuarias = $orcparamrel->sql_parametro('22','12',"f",$instituicao,$anousu);

$m_transformacao = $orcparamrel->sql_parametro('22','13',"f",$instituicao,$anousu);
$m_construcao    = $orcparamrel->sql_parametro('22','14',"f",$instituicao,$anousu);
$m_industriais   = $orcparamrel->sql_parametro('22','15',"f",$instituicao,$anousu);

$m_servicos   = $orcparamrel->sql_parametro('22','16',"f",$instituicao,$anousu);

$m_intergovernamental = $orcparamrel->sql_parametro('22','17',"f",$instituicao,$anousu);
$m_privadas           = $orcparamrel->sql_parametro('22','18',"f",$instituicao,$anousu);
$m_transf_exterior    = $orcparamrel->sql_parametro('22','19',"f",$instituicao,$anousu);
$m_transf_pessoas     = $orcparamrel->sql_parametro('22','20',"f",$instituicao,$anousu);
$m_transf_convenios   = $orcparamrel->sql_parametro('22','21',"f",$instituicao,$anousu);
$m_transf_fome        = $orcparamrel->sql_parametro('22','22',"f",$instituicao,$anousu);

$m_multas             = $orcparamrel->sql_parametro('22','23',"f",$instituicao,$anousu);
$m_indenizacao        = $orcparamrel->sql_parametro('22','24',"f",$instituicao,$anousu);
$m_divida_ativa       = $orcparamrel->sql_parametro('22','25',"f",$instituicao,$anousu);
$m_correntes_diversas = $orcparamrel->sql_parametro('22','26',"f",$instituicao,$anousu);

$m_oper_internas = $orcparamrel->sql_parametro('22','27',"f",$instituicao,$anousu);
$m_oper_externas = $orcparamrel->sql_parametro('22','28',"f",$instituicao,$anousu);

$m_bens_moveis  = $orcparamrel->sql_parametro('22','29',"f",$instituicao,$anousu);
$m_bens_imoveis = $orcparamrel->sql_parametro('22','30',"f",$instituicao,$anousu);
$m_emprestimos  = $orcparamrel->sql_parametro('22','31',"f",$instituicao,$anousu);

$m_transf_capital_intergovernamentais = $orcparamrel->sql_parametro('22','32',"f",$instituicao,$anousu);
$m_transf_capital_privadas            = $orcparamrel->sql_parametro('22','33',"f",$instituicao,$anousu);
$m_transf_capital_exterior            = $orcparamrel->sql_parametro('22','34',"f",$instituicao,$anousu);
$m_transf_capital_pessoas             = $orcparamrel->sql_parametro('22','35',"f",$instituicao,$anousu);
$m_transf_capital_outras              = $orcparamrel->sql_parametro('22','36',"f",$instituicao,$anousu);
$m_transf_capital_convenios           = $orcparamrel->sql_parametro('22','37',"f",$instituicao,$anousu);
$m_transf_capital_fome                = $orcparamrel->sql_parametro('22','38',"f",$instituicao,$anousu);

$m_outras_social           = $orcparamrel->sql_parametro('22','39',"f",$instituicao,$anousu);
$m_outras_disponibilidades = $orcparamrel->sql_parametro('22','40',"f",$instituicao,$anousu);
$m_outras_restituicoes     = $orcparamrel->sql_parametro('22','41',"f",$instituicao,$anousu);
$m_outras_diversas         = $orcparamrel->sql_parametro('22','42',"f",$instituicao,$anousu);

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
//db_criatabela($result_desp);
//exit;
/*
$result_bal  = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit).')');
for ($i=0; $i<pg_numrows($result_bal); $i++) {
  db_fieldsmemory($result_bal,$i);
  if (in_array($estrutural,$m_saldo_anterior['estrut'])) {
    $m_saldo_anterior['valor'] += $saldo_final ;
  }
}
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$xinstit    = split("-",$db_selinstit);
$resultinst = pg_exec("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
db_fieldsmemory($resultinst,0);

$head2 = "MUNICÍPIO DE ".$munic;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
$head4 = "BALANÇO ORÇAMENTÁRIO";
$head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$aDt_ini  = split("-",$dt_ini);
$txt = strtoupper(db_mes($aDt_ini[1]));
$dt  = split("-",$dt_fin);
$txt.= " À ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";
;
$dt  = split("-",$dt_ini);
$txt.= strtoupper(db_mes($dt[1]))."-";
$dt  = split("-",$dt_fin);
$txt.= strtoupper(db_mes($dt[1]));
$head6 = "$txt";
////////////////////////// ///////////////////

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->addpage();

$troca = 1;
$alt = 4;
$dataini = $dt_ini;
$datafin = $dt_fin;
$pagina = 1;
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

/*
db_criatabela($result_rec);
exit;
*/

for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_impostos)) {
    // despesas com ensino fundamental
    if (substr($estrutural,1,1) == "7") {
      $m_impostos_intra["inicial"]    += $saldo_inicial;
      $m_impostos_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_impostos_intra["nobim"]      += $saldo_arrecadado;
      $m_impostos_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_impostos_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      // ;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_taxas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_taxas_intra["inicial"]    += $saldo_inicial;
      $m_taxas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_taxas_intra["nobim"]      += $saldo_arrecadado;
      $m_taxas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_taxas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
     $tot_inicial    += $saldo_inicial ;
     $tot_atualizada += $saldo_inicial_prevadic;
    // ;
     $tot_nobim     += $saldo_arrecadado;
     $tot_atebim   += $saldo_arrecadado_acumulado;
     $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_melhorias)) {
    if (substr($estrutural,1,1) == "7"){
      $m_melhorias_intra["inicial"]    += $saldo_inicial;
      $m_melhorias_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_melhorias_intra["nobim"]      += $saldo_arrecadado;
      $m_melhorias_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_melhorias_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
     $tot_inicial    += $saldo_inicial ;
     $tot_atualizada += $saldo_inicial_prevadic;
     $tot_nobim     += $saldo_arrecadado;
     $tot_atebim   += $saldo_arrecadado_acumulado;
     $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_sociais)) {
    if (substr($estrutural,1,1) == "7"){
      $m_sociais_intra["inicial"]    += $saldo_inicial;
      $m_sociais_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_sociais_intra["nobim"]      += $saldo_arrecadado;
      $m_sociais_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_sociais_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_economicas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_economicas_intra["inicial"]    += $saldo_inicial;
      $m_economicas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_economicas_intra["nobim"]      += $saldo_arrecadado;
      $m_economicas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_economicas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$pdf->cell(60,$alt,espaco($n2)."Contribuições Econômicas",'R',0,"L",0);
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_imobiliarias)) {
    if (substr($estrutural,1,1) == "7"){
      $m_imobiliarias_intra["inicial"]    += $saldo_inicial;
      $m_imobiliarias_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_imobiliarias_intra["nobim"]      += $saldo_arrecadado;
      $m_imobiliarias_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_imobiliarias_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim     += $saldo_arrecadado;
      $tot_atebim   += $saldo_arrecadado_acumulado;
      $tot_realizar  += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_valmobiliarias)) {
    if (substr($estrutural,1,1) == "7"){
      $m_valmobiliarias_intra["inicial"]    += $saldo_inicial;
      $m_valmobiliarias_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_valmobiliarias_intra["nobim"]      += $saldo_arrecadado;
      $m_valmobiliarias_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_valmobiliarias_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

$v_inicial    += $tot_inicial;
$v_atualizada += $tot_atualizada;
// ;
$v_nobim      += $tot_nobim;
$v_atebim     += $tot_atebim;
$v_realizar   += $tot_realizar;


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_permissoes)) {
    if (substr($estrutural,1,1) == "7"){
      $m_permissoes_intra["inicial"]    += $saldo_inicial;
      $m_permissoes_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_permissoes_intra["nobim"]      += $saldo_arrecadado;
      $m_permissoes_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_permissoes_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
    // despesas com ensino fundamental
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;

for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_patrimoniais)) {
    if (substr($estrutural,1,1) == "7"){
      $m_patrimoniais_intra["inicial"]    += $saldo_inicial;
      $m_patrimoniais_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_patrimoniais_intra["nobim"]      += $saldo_arrecadado;
      $m_patrimoniais_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_patrimoniais_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_vegetal)) {
    if (substr($estrutural,1,1) == "7"){
      $m_vegetal_intra["inicial"]    += $saldo_inicial;
      $m_vegetal_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_vegetal_intra["nobim"]      += $saldo_arrecadado;
      $m_vegetal_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_vegetal_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_animal)) {
    if (substr($estrutural,1,1) == "7"){
      $m_animal_intra["inicial"]    += $saldo_inicial;
      $m_animal_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_animal_intra["nobim"]      += $saldo_arrecadado;
      $m_animal_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_animal_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_agropecuarias)) {
    if (substr($estrutural,1,1) == "7"){
      $m_agropecuarias_intra["inicial"]    += $saldo_inicial;
      $m_agropecuarias_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_agropecuarias_intra["nobim"]      += $saldo_arrecadado;
      $m_agropecuarias_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_agropecuarias_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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

//--------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transformacao)) {
    if (substr($estrutural,1,1) == "7"){
      $m_transformacao_intra["inicial"]    += $saldo_inicial;
      $m_transformacao_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transformacao_intra["nobim"]      += $saldo_arrecadado;
      $m_transformacao_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transformacao_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$pdf->cell(60,$alt,espaco($n2)."Receitas da Indústria de Transformação",'R',0,"L",0);
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_construcao)) {
    if (substr($estrutural,1,1) == "7"){
      $m_construcao_intra["inicial"]    += $saldo_inicial;
      $m_construcao_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_construcao_intra["nobim"]      += $saldo_arrecadado;
      $m_construcao_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_construcao_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$pdf->cell(60,$alt,espaco($n2)."Receitas da Indústria de Construção",'R',0,"L",0);
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_industriais)) {
    if (substr($estrutural,1,1) == "7"){
      $m_industriais_intra["inicial"]    += $saldo_inicial;
      $m_industriais_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_industriais_intra["nobim"]      += $saldo_arrecadado;
      $m_industriais_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_industriais_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
// ;
$somador_I_nobim      += $tot_nobim;
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_servicos)) {
    if (substr($estrutural,1,1) == "7"){
      $m_servicos_intra["inicial"]    += $saldo_inicial;
      $m_servicos_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_servicos_intra["nobim"]      += $saldo_arrecadado;
      $m_servicos_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_servicos_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_intergovernamental)) {
    if (substr($estrutural,1,1) == "7"){
      $m_intergovernamental_intra["inicial"]    += $saldo_inicial;
      $m_intergovernamental_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_intergovernamental_intra["nobim"]      += $saldo_arrecadado;
      $m_intergovernamental_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_intergovernamental_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_privadas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_privadas_intra["inicial"]    += $saldo_inicial;
      $m_privadas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_privadas_intra["nobim"]      += $saldo_arrecadado;
      $m_privadas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_privadas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_exterior)) {
    if (substr($estrutural,1,1) == "7"){
      $m_transf_exterior_intra["inicial"]    += $saldo_inicial;
      $m_transf_exterior_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_exterior_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_exterior_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_exterior_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_pessoas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_transf_pessoas_intra["inicial"]    += $saldo_inicial;
      $m_transf_pessoas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_pessoas_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_pessoas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_pessoas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_convenios)) {
    if (substr($estrutural,1,1) == "7"){
      $m_transf_convenios_intra["inicial"]    += $saldo_inicial;
      $m_transf_convenios_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_convenios_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_convenios_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_convenios_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_fome)) {
    if (substr($estrutural,1,1) == "7"){
      $m_transf_fome_intra["inicial"]    += $saldo_inicial;
      $m_transf_fome_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_fome_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_fome_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_fome_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_multas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_multas_intra["inicial"]    += $saldo_inicial;
      $m_multas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_multas_intra["nobim"]      += $saldo_arrecadado;
      $m_multas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_multas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_indenizacao)) {
    if (substr($estrutural,1,1) == "7"){
      $m_indenizacao_intra["inicial"]    += $saldo_inicial;
      $m_indenizacao_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_indenizacao_intra["nobim"]      += $saldo_arrecadado;
      $m_indenizacao_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_indenizacao_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_divida_ativa)) {
    if (substr($estrutural,1,1) == "7"){
      $m_divida_ativa_intra["inicial"]    += $saldo_inicial;
      $m_divida_ativa_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_divida_ativa_intra["nobim"]      += $saldo_arrecadado;
      $m_divida_ativa_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_divida_ativa_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_correntes_diversas)) {
    if (substr($estrutural,1,1) == "7"){
      $m_correntes_diversas_intra["inicial"]    += $saldo_inicial;
      $m_correntes_diversas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_correntes_diversas_intra["nobim"]      += $saldo_arrecadado;
      $m_correntes_diversas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_correntes_diversas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_internas)) {
    if (substr($estrutural,1,1) == "8"){
      $m_oper_internas_intra["inicial"]    += $saldo_inicial;
      $m_oper_internas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_oper_internas_intra["nobim"]      += $saldo_arrecadado;
      $m_oper_internas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_oper_internas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_oper_externas)) {
    if (substr($estrutural,1,1) == "8"){
      $m_oper_externas_intra["inicial"]    += $saldo_inicial;
      $m_oper_externas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_oper_externas_intra["nobim"]      += $saldo_arrecadado;
      $m_oper_externas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_oper_externas_intra["realizar"]   += $saldo_a_arrecadar;
    } else { 
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_bens_moveis)) {
    if (substr($estrutural,1,1) == "8"){
      $m_bens_moveis_intra["inicial"]    += $saldo_inicial;
      $m_bens_moveis_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_bens_moveis_intra["nobim"]      += $saldo_arrecadado;
      $m_bens_moveis_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_bens_moveis_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_bens_imoveis)) {
    if (substr($estrutural,1,1) == "8"){
      $m_bens_imoveis_intra["inicial"]    += $saldo_inicial;
      $m_bens_imoveis_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_bens_imoveis_intra["nobim"]      += $saldo_arrecadado;
      $m_bens_imoveis_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_bens_imoveis_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$somador_I_atebim   += $tot_atebim;
$somador_I_realizar += $tot_realizar;
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_emprestimos)) {
    if (substr($estrutural,1,1) == "8"){
      $m_emprestimos_intra["inicial"]    += $saldo_inicial;
      $m_emprestimos_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_emprestimos_intra["nobim"]      += $saldo_arrecadado;
      $m_emprestimos_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_emprestimos_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_intergovernamentais)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_intergovernamentais_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_intergovernamentais_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_intergovernamentais_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_intergovernamentais_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_intergovernamentais_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_privadas)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_privadas_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_privadas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_privadas_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_privadas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_privadas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_exterior)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_exterior_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_exterior_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_exterior_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_exterior_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_exterior_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_pessoas)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_pessoas_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_pessoas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_pessoas_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_pessoas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_pessoas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_outras)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_outras_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_outras_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_outras_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_outras_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_outras_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_convenios)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_convenios_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_convenios_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_convenios_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_convenios_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_convenios_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_transf_capital_fome)) {
    if (substr($estrutural,1,1) == "8"){
      $m_transf_capital_fome_intra["inicial"]    += $saldo_inicial;
      $m_transf_capital_fome_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_transf_capital_fome_intra["nobim"]      += $saldo_arrecadado;
      $m_transf_capital_fome_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_transf_capital_fome_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_social)) {
    if (substr($estrutural,1,1) == "8"){
      $m_outras_social_intra["inicial"]    += $saldo_inicial;
      $m_outras_social_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_outras_social_intra["nobim"]      += $saldo_arrecadado;
      $m_outras_social_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_outras_social_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_disponibilidades)) {
    if (substr($estrutural,1,1) == "8"){
      $m_outras_disponibilidades_intra["inicial"]    += $saldo_inicial;
      $m_outras_disponibilidades_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_outras_disponibilidades_intra["nobim"]      += $saldo_arrecadado;
      $m_outras_disponibilidades_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_outras_disponibilidades_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_restituicoes)) {
    if (substr($estrutural,1,1) == "8"){
      $m_outras_restituicoes_intra["inicial"]    += $saldo_inicial;
      $m_outras_restituicoes_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_outras_restituicoes_intra["nobim"]      += $saldo_arrecadado;
      $m_outras_restituicoes_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_outras_restituicoes_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

$rec_cap_II_inicial_intra           += $m_outras_restituicoes_intra["inicial"];
$rec_cap_II_atualizada_intra        += $m_outras_restituicoes_intra["atualizada"];
$rec_cap_II_nobim_intra             += $m_outras_restituicoes_intra["nobim"];
$rec_cap_II_atebim_intra            += $m_outras_restituicoes_intra["atebim"];
$rec_cap_II_realizar_intra          += $m_outras_restituicoes_intra["realizar"];

$rec_outras_cap_II_inicial_intra    += $m_outras_restituicoes_intra["inicial"];
$rec_outras_cap_II_atualizada_intra += $m_outras_restituicoes_intra["atualizada"];
$rec_outras_cap_II_nobim_intra      += $m_outras_restituicoes_intra["nobim"];
$rec_outras_cap_II_atebim_intra     += $m_outras_restituicoes_intra["atebim"];
$rec_outras_cap_II_realizar_intra   += $m_outras_restituicoes_intra["realizar"];

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,espaco($n2)."Restituições",'R',0,"L",0);
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
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;
for ($i=0; $i<pg_numrows($result_rec); $i++) {
  db_fieldsmemory($result_rec,$i);
  $estrutural = $o57_fonte;
  if (in_array($estrutural,$m_outras_diversas)) {
    if (substr($estrutural,1,1) == "8"){
      $m_outras_diversas_intra["inicial"]    += $saldo_inicial;
      $m_outras_diversas_intra["atualizada"] += $saldo_inicial_prevadic;
      $m_outras_diversas_intra["nobim"]      += $saldo_arrecadado;
      $m_outras_diversas_intra["atebim"]     += $saldo_arrecadado_acumulado;
      $m_outras_diversas_intra["realizar"]   += $saldo_a_arrecadar;
    } else {
      $tot_inicial    += $saldo_inicial ;
      $tot_atualizada += $saldo_inicial_prevadic;
      $tot_nobim      += $saldo_arrecadado;
      $tot_atebim     += $saldo_arrecadado_acumulado;
      $tot_realizar   += $saldo_a_arrecadar;
    }
  }
}

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
$rec_cor_I_inicial    = $somador_I_inicial-$rec_cor_I_inicial ;
$rec_cor_I_atualizada = $somador_I_atualizada-$rec_cor_I_atualizada ;
// ;
$rec_cor_I_nobim      = $somador_I_nobim-$rec_cor_I_nobim   ;
$rec_cor_I_atebim     = $somador_I_atebim-$rec_cor_I_atebim ;
$rec_cor_I_realizar   = $somador_I_realizar-$rec_cor_I_realizar ;

$pos_atu = $pdf->y;
// posição atual
// sobe, escreve e desce
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

$pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
$pdf->AddPage();
$pdf->cell(190,$alt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0); 

imprime_cabec_rec($alt,&$pdf);

$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"RECEITAS (INTRA-ORÇAMENTÁRIAS) (II)",'R',0,"L",0);


// --------------------------------
$tot_inicial    = 0;
$tot_atualizada = 0;
$tot_nobim     = 0;
$tot_atebim   = 0;
$tot_realizar  = 0;

// Impostos Intra-Orcamentario
$tot_inicial    += $m_impostos_intra["inicial"];
$tot_atualizada += $m_impostos_intra["atualizada"];
$tot_nobim      += $m_impostos_intra["nobim"];
$tot_atebim     += $m_impostos_intra["atebim"];
$tot_realizar   += $m_impostos_intra["realizar"];

// Taxas Intra-Orcamentaria
$tot_inicial    += $m_taxas_intra["inicial"];
$tot_atualizada += $m_taxas_intra["atualizada"];
$tot_nobim      += $m_taxas_intra["nobim"];
$tot_atebim     += $m_taxas_intra["atebim"];
$tot_realizar   += $m_taxas_intra["realizar"];

// Contribuicao de Melhorias Intra-Orcamentaria
$tot_inicial    += $m_melhorias_intra["inicial"];
$tot_atualizada += $m_melhorias_intra["atualizada"];
$tot_nobim      += $m_melhorias_intra["nobim"];
$tot_atebim     += $m_melhorias_intra["atebim"];
$tot_realizar   += $m_melhorias_intra["realizar"];

// Contribuicoes Sociais Intra-Orcamentaria
$tot_inicial    += $m_sociais_intra["inicial"];
$tot_atualizada += $m_sociais_intra["atualizada"];
$tot_nobim      += $m_sociais_intra["nobim"];
$tot_atebim     += $m_sociais_intra["atebim"];
$tot_realizar   += $m_sociais_intra["realizar"];

// Contribuicoes Economicas Intra-Orcamentaria
$tot_inicial    += $m_economicas_intra["inicial"];
$tot_atualizada += $m_economicas_intra["atualizada"];
$tot_nobim      += $m_economicas_intra["nobim"];
$tot_atebim     += $m_economicas_intra["atebim"];
$tot_realizar   += $m_economicas_intra["realizar"];

// Receitas Imobiliarias Intra-Orcamentaria
$tot_inicial    += $m_imobiliarias_intra["inicial"];
$tot_atualizada += $m_imobiliarias_intra["atualizada"];
$tot_nobim      += $m_imobiliarias_intra["nobim"];
$tot_atebim     += $m_imobiliarias_intra["atebim"];
$tot_realizar   += $m_imobiliarias_intra["realizar"];

// Receita de Valores Mobiliarios Intra-Orcamentario
$tot_inicial    += $m_valmobiliarias_intra["inicial"];
$tot_atualizada += $m_valmobiliarias_intra["atualizada"];
$tot_nobim      += $m_valmobiliarias_intra["nobim"];
$tot_atebim     += $m_valmobiliarias_intra["atebim"];
$tot_realizar   += $m_valmobiliarias_intra["realizar"];

// Receita de Concessoes e Permissoes Intra-Orcamentario
$tot_inicial    += $m_permissoes_intra["inicial"];
$tot_atualizada += $m_permissoes_intra["atualizada"];
$tot_nobim      += $m_permissoes_intra["nobim"];
$tot_atebim     += $m_permissoes_intra["atebim"];
$tot_realizar   += $m_permissoes_intra["realizar"];

// Outras Receitas Patrimoniais Intra-Orcamentario
$tot_inicial    += $m_patrimoniais_intra["inicial"];
$tot_atualizada += $m_patrimoniais_intra["atualizada"];
$tot_nobim      += $m_patrimoniais_intra["nobim"];
$tot_atebim     += $m_patrimoniais_intra["atebim"];
$tot_realizar   += $m_patrimoniais_intra["realizar"];

// Receita da Producao Vegetal Intra-Orcamentaria
$tot_inicial    += $m_vegetal_intra["inicial"];
$tot_atualizada += $m_vegetal_intra["atualizada"];
$tot_nobim      += $m_vegetal_intra["nobim"];
$tot_atebim     += $m_vegetal_intra["atebim"];
$tot_realizar   += $m_vegetal_intra["realizar"];

// Receita da Producao Animal e Derviados Intra-Orcamentaria
$tot_inicial    += $m_animal_intra["inicial"];
$tot_atualizada += $m_animal_intra["atualizada"];
$tot_nobim      += $m_animal_intra["nobim"];
$tot_atebim     += $m_animal_intra["atebim"];
$tot_realizar   += $m_animal_intra["realizar"];

// Outras Receitas Agropecuarias Intra-Orcamentaria
$tot_inicial    += $m_agropecuarias_intra["inicial"];
$tot_atualizada += $m_agropecuarias_intra["atualizada"];
$tot_nobim      += $m_agropecuarias_intra["nobim"];
$tot_atebim     += $m_agropecuarias_intra["atebim"];
$tot_realizar   += $m_agropecuarias_intra["realizar"];

// Receita da Industria de Transformacao Intra-Orcamentaria
$tot_inicial    += $m_transformacao_intra["inicial"];
$tot_atualizada += $m_transformacao_intra["atualizada"];
$tot_nobim      += $m_transformacao_intra["nobim"];
$tot_atebim     += $m_transformacao_intra["atebim"];
$tot_realizar   += $m_transformacao_intra["realizar"];

// Receita da Industria de Construcao Intra-Orcamentaria
$tot_inicial    += $m_construcao_intra["inicial"];
$tot_atualizada += $m_construcao_intra["atualizada"];
$tot_nobim      += $m_construcao_intra["nobim"];
$tot_atebim     += $m_construcao_intra["atebim"];
$tot_realizar   += $m_construcao_intra["realizar"];

// Outras Receitas Industriais Intra-Orcamentaria
$tot_inicial    += $m_industriais_intra["inicial"];
$tot_atualizada += $m_industriais_intra["atualizada"];
$tot_nobim      += $m_industriais_intra["nobim"];
$tot_atebim     += $m_industriais_intra["atebim"];
$tot_realizar   += $m_industriais_intra["realizar"];

// Receita de Servicos Intra-Orcamentaria
$tot_inicial    += $m_servicos_intra["inicial"];
$tot_atualizada += $m_servicos_intra["atualizada"];
$tot_nobim      += $m_servicos_intra["nobim"];
$tot_atebim     += $m_servicos_intra["atebim"];
$tot_realizar   += $m_servicos_intra["realizar"];

// Transferencias Intergovernamentais Intra-Orcamentaria
$tot_inicial    += $m_intergovernamental_intra["inicial"];
$tot_atualizada += $m_intergovernamental_intra["atualizada"];
$tot_nobim      += $m_intergovernamental_intra["nobim"];
$tot_atebim     += $m_intergovernamental_intra["atebim"];
$tot_realizar   += $m_intergovernamental_intra["realizar"];

// Transferencias de Instituicoes Privadas Intra-Orcamentaria
$tot_inicial    += $m_privadas_intra["inicial"];
$tot_atualizada += $m_privadas_intra["atualizada"];
$tot_nobim      += $m_privadas_intra["nobim"];
$tot_atebim     += $m_privadas_intra["atebim"];
$tot_realizar   += $m_privadas_intra["realizar"];

// Transferencias do Exterior Intra-Orcamentaria
$tot_inicial    += $m_transf_exterior_intra["inicial"];
$tot_atualizada += $m_transf_exterior_intra["atualizada"];
$tot_nobim      += $m_transf_exterior_intra["nobim"];
$tot_atebim     += $m_transf_exterior_intra["atebim"];
$tot_realizar   += $m_transf_exterior_intra["realizar"];

// Transferencias de Pessoas Intra-Orcamentaria
$tot_inicial    += $m_transf_pessoas_intra["inicial"];
$tot_atualizada += $m_transf_pessoas_intra["atualizada"];
$tot_nobim      += $m_transf_pessoas_intra["nobim"];
$tot_atebim     += $m_transf_pessoas_intra["atebim"];
$tot_realizar   += $m_transf_pessoas_intra["realizar"];

// Transferencias de Convenios Intra-Orcamentaria
$tot_inicial    += $m_transf_convenios_intra["inicial"];
$tot_atualizada += $m_transf_convenios_intra["atualizada"];
$tot_nobim      += $m_transf_convenios_intra["nobim"];
$tot_atebim     += $m_transf_convenios_intra["atebim"];
$tot_realizar   += $m_transf_convenios_intra["realizar"];

// Transferencias para o Combate a Fome Intra-Orcamentaria
$tot_inicial    += $m_transf_fome_intra["inicial"];
$tot_atualizada += $m_transf_fome_intra["atualizada"];
$tot_nobim      += $m_transf_fome_intra["nobim"];
$tot_atebim     += $m_transf_fome_intra["atebim"];
$tot_realizar   += $m_transf_fome_intra["realizar"];

// Multas e Juros de Mora Intra-Orcamentaria
$tot_inicial    += $m_multas_intra["inicial"];
$tot_atualizada += $m_multas_intra["atualizada"];
$tot_nobim      += $m_multas_intra["nobim"];
$tot_atebim     += $m_multas_intra["atebim"];
$tot_realizar   += $m_multas_intra["realizar"];

// Indenizacoes e Restituicoes Intra-Orcamentaria
$tot_inicial    += $m_indenizacao_intra["inicial"];
$tot_atualizada += $m_indenizacao_intra["atualizada"];
$tot_nobim      += $m_indenizacao_intra["nobim"];
$tot_atebim     += $m_indenizacao_intra["atebim"];
$tot_realizar   += $m_indenizacao_intra["realizar"];

// Receita da Divida Ativa Intra-Orcamentaria
$tot_inicial    += $m_divida_ativa_intra["inicial"];
$tot_atualizada += $m_divida_ativa_intra["atualizada"];
$tot_nobim      += $m_divida_ativa_intra["nobim"];
$tot_atebim     += $m_divida_ativa_intra["atebim"];
$tot_realizar   += $m_divida_ativa_intra["realizar"];

// Receitas Correntes Diversas Intra-Orcamentaria
$tot_inicial    += $m_correntes_diversas_intra["inicial"];
$tot_atualizada += $m_correntes_diversas_intra["atualizada"];
$tot_nobim      += $m_correntes_diversas_intra["nobim"];
$tot_atebim     += $m_correntes_diversas_intra["atebim"];
$tot_realizar   += $m_correntes_diversas_intra["realizar"];

// Operacoes de Credito Internas Capital Intra-Orcamentaria
$tot_inicial    += $m_oper_internas_intra["inicial"];
$tot_atualizada += $m_oper_internas_intra["atualizada"];
$tot_nobim      += $m_oper_internas_intra["nobim"];
$tot_atebim     += $m_oper_internas_intra["atebim"];
$tot_realizar   += $m_oper_internas_intra["realizar"];

// Operacoes de Credito Externas Capital Intra-Orcamentaria
$tot_inicial    += $m_oper_externas_intra["inicial"];
$tot_atualizada += $m_oper_externas_intra["atualizada"];
$tot_nobim      += $m_oper_externas_intra["nobim"];
$tot_atebim     += $m_oper_externas_intra["atebim"];
$tot_realizar   += $m_oper_externas_intra["realizar"];

// Alienacao de Bens Moveis Capital Intra-Orcamentaria
$tot_inicial    += $m_bens_moveis_intra["inicial"];
$tot_atualizada += $m_bens_moveis_intra["atualizada"];
$tot_nobim      += $m_bens_moveis_intra["nobim"];
$tot_atebim     += $m_bens_moveis_intra["atebim"];
$tot_realizar   += $m_bens_moveis_intra["realizar"];

// Alienacao de Bens Imoveis Capital Intra-Orcamentaria
$tot_inicial    += $m_bens_imoveis_intra["inicial"];
$tot_atualizada += $m_bens_imoveis_intra["atualizada"];
$tot_nobim      += $m_bens_imoveis_intra["nobim"];
$tot_atebim     += $m_bens_imoveis_intra["atebim"];
$tot_realizar   += $m_bens_imoveis_intra["realizar"];

// Amortizacoes de Emprestimos Capital Intra-Orcamentaria
$tot_inicial    += $m_emprestimos_intra["inicial"];
$tot_atualizada += $m_emprestimos_intra["atualizada"];
$tot_nobim      += $m_emprestimos_intra["nobim"];
$tot_atebim     += $m_emprestimos_intra["atebim"];
$tot_realizar   += $m_emprestimos_intra["realizar"];

// Transferencias Intergovernamentais Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_intergovernamentais_intra["inicial"];
$tot_atualizada += $m_transf_capital_intergovernamentais_intra["atualizada"];
$tot_nobim      += $m_transf_capital_intergovernamentais_intra["nobim"];
$tot_atebim     += $m_transf_capital_intergovernamentais_intra["atebim"];
$tot_realizar   += $m_transf_capital_intergovernamentais_intra["realizar"];

// Transferencias de Instituicoes Privadas Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_privadas_intra["inicial"];
$tot_atualizada += $m_transf_capital_privadas_intra["atualizada"];
$tot_nobim      += $m_transf_capital_privadas_intra["nobim"];
$tot_atebim     += $m_transf_capital_privadas_intra["atebim"];
$tot_realizar   += $m_transf_capital_privadas_intra["realizar"];

// Transferencias do Exterior Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_exterior_intra["inicial"];
$tot_atualizada += $m_transf_capital_exterior_intra["atualizada"];
$tot_nobim      += $m_transf_capital_exterior_intra["nobim"];
$tot_atebim     += $m_transf_capital_exterior_intra["atebim"];
$tot_realizar   += $m_transf_capital_exterior_intra["realizar"];

// Transferencias do Exterior Capital Intra-Orcamentaria
$tot_inicial    += $m_transf_capital_pessoas_intra["inicial"];
$tot_atualizada += $m_transf_capital_pessoas_intra["atualizada"];
$tot_nobim      += $m_transf_capital_pessoas_intra["nobim"];
$tot_atebim     += $m_transf_capital_pessoas_intra["atebim"];
$tot_realizar   += $m_transf_capital_pessoas_intra["realizar"];

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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
    $pdf->Ln(4);

    $flag_troca = true;
  }
} else {
  $flag_troca = false;
}

// Imposto Intra-Orcamentario
if ($m_impostos_intra["inicial"]  > 0 || $m_impostos_intra["atualizada"] > 0 ||
    $m_impostos_intra["nobim"]    > 0 || $m_impostos_intra["atebim"]    > 0  ||
    $m_impostos_intra["realizar"] > 0){
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0);
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
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
  $pdf->cell(60,$alt,espaco($n2)."Contribuições Econômicas",'R',0,"L",0);
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
    $pdf->cell(190,$alt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
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

$res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (301,305,309,313,317) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
if ($clconrelvalor->numrows > 0){
  $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
  $tot_atualizada = pg_result($res_valor,1,"c83_informacao");
  $tot_nobim      = pg_result($res_valor,2,"c83_informacao");
  $tot_atebim     = pg_result($res_valor,3,"c83_informacao");
  $tot_realizar   = pg_result($res_valor,4,"c83_informacao");
}

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

$res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (302,306,310,314,318) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
if ($clconrelvalor->numrows > 0){
  $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
  $tot_atualizada = pg_result($res_valor,1,"c83_informacao");
  $tot_nobim      = pg_result($res_valor,2,"c83_informacao");
  $tot_atebim     = pg_result($res_valor,3,"c83_informacao");
  $tot_realizar   = pg_result($res_valor,4,"c83_informacao");
}

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

$res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (303,307,311,315,319) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
if ($clconrelvalor->numrows > 0){
  $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
  $tot_atualizada = pg_result($res_valor,1,"c83_informacao");
  $tot_nobim      = pg_result($res_valor,2,"c83_informacao");
  $tot_atebim     = pg_result($res_valor,3,"c83_informacao");
  $tot_realizar   = pg_result($res_valor,4,"c83_informacao");
}

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

$res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (304,308,312,316,320) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
if ($clconrelvalor->numrows > 0){
  $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
  $tot_atualizada = pg_result($res_valor,1,"c83_informacao");
  $tot_nobim      = pg_result($res_valor,2,"c83_informacao");
  $tot_atebim     = pg_result($res_valor,3,"c83_informacao");
  $tot_realizar   = pg_result($res_valor,4,"c83_informacao");
}

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
$tot_saldo_anterior = "";
$tot_saldo_anteriormenosprevisao = "";
$res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in(321,476) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
if ($clconrelvalor->numrows > 0){
  $tot_saldo_anterior              = db_formatar(@pg_result($res_valor,0,"c83_informacao"),'f');
  $tot_saldo_anteriormenosprevisao = db_formatar(@pg_result($res_valor,1,"c83_informacao"),'f');
}
if ( $tot_saldo_anterior              == "") { $tot_saldo_anterior = "-";              }
if ( $tot_saldo_anteriormenosprevisao == "") { $tot_saldo_anteriormenosprevisao = "-"; } 

$pdf->setfont('arial','B',6);
$pdf->cell(60,$alt,"SALDO DE EXERCÍCIOS ANTERIORES",'TR',0,"L",0);
$pdf->cell(20,$alt,'-','TR',0,"C",0);
$pdf->cell(20,$alt,$tot_saldo_anteriormenosprevisao,'TR',0,"C",0);
$pdf->cell(25,$alt,'-','TR',0,"C",0);
$pdf->cell(10,$alt,'-','TR',0,"C",0);
$pdf->cell(25,$alt,$tot_saldo_anterior,'TR',0,"R",0);
$pdf->cell(10,$alt,'-','TR',0,"C",0);
// % (b/a)
$pdf->cell(10,$alt,'-','T',1,"C",0);

$pdf->cell(60,$alt,"(UTILIZADOS PARA CRÉDITOS ADICIONAIS)",'BR',0,"L",0);
$pdf->cell(20,$alt,'','BR',0,"R",0);
$pdf->cell(20,$alt,'','BR',0,"R",0);
$pdf->cell(25,$alt,'','BR',0,"R",0);
$pdf->cell(10,$alt,'','BR',0,"R",0);
$pdf->cell(25,$alt,'','BR',0,"R",0);
$pdf->cell(10,$alt,'','BR',0,"R",0);
// % (b/a)
$pdf->cell(20,$alt,'','B',1,"R",0);
//--------------------------------
// quebra a pagina,

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
for ($i=0; $i<pg_numrows($result_desp); $i++) {
  db_fieldsmemory($result_desp,$i);

  $estrutural = $o58_elemento."00";
  if (substr($estrutural,0,3)=='331' && substr($estrutural,3,2)!='91') {
    $tot_inicial      += $dot_ini;
    $tot_adicional    += $suplementado_acumulado - $reduzido_acumulado;
    // adicional;
    $tot_emp_nobim    += $empenhado  - $anulado;
    $tot_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
    $tot_liq_nobim    += $liquidado;
    $tot_liq_atebim   += $liquidado_acumulado;
    if($bimestre == 6) {
      $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
    }
  }
}

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
  for ($i=0; $i<pg_numrows($result_desp); $i++) {
    db_fieldsmemory($result_desp,$i);
    // $estrutural = $c60_estrut;
    $estrutural = $o58_elemento."00";
    
    if (substr($estrutural,0,3)=='332' && substr($estrutural,3,2)!='91') {
      // if (in_array($estrutural,$desp_juros)) {
        $tot_inicial    += $dot_ini;
        $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
        // adicional;
        $tot_emp_nobim    += $empenhado  - $anulado;
        $tot_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
        $tot_liq_nobim    += $liquidado;
        $tot_liq_atebim   += $liquidado_acumulado;
        if($bimestre == 6) {
          $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
        }
      }
    }
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
    for ($i=0; $i<pg_numrows($result_desp); $i++) {
      db_fieldsmemory($result_desp,$i);
      //  $estrutural = $c60_estrut;
      $estrutural = $o58_elemento."00";
      
      if (substr($estrutural,0,3)=='333' && substr($estrutural,3,2)!='91') {
        // if (in_array($estrutural,$desp_outras)) {
          $tot_inicial    += $dot_ini;
          $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
          // adicional;
          $tot_emp_nobim  += $empenhado  - $anulado;
          $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
          $tot_liq_nobim  += $liquidado;
          $tot_liq_atebim += $liquidado_acumulado;
          if($bimestre == 6) {
            $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
          }
        }
      }
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
      
      for ($i=0; $i<pg_numrows($result_desp); $i++) {
        db_fieldsmemory($result_desp,$i);
        //  $estrutural = $c60_estrut;
        $estrutural = $o58_elemento."00";
        
        if (substr($estrutural,0,3)=='344' && substr($estrutural,3,2)!='91') {
          //  if (in_array($estrutural,$desp_investimentos)) {
            $tot_inicial    += $dot_ini;
            $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
            // adicional;
            $tot_emp_nobim  += $empenhado  - $anulado;
            $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
            $tot_liq_nobim  += $liquidado;
            $tot_liq_atebim += $liquidado_acumulado;
            if($bimestre == 6) {
              $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
            }
            
          }
        }
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
        $tot_inicial    = 0;
        $tot_adicional  = 0;
        $tot_emp_nobim   = 0;
        $tot_emp_atebim  = 0;
        $tot_liq_nobim   = 0;
        $tot_liq_atebim  = 0;
        $tot_inscritos_rp = 0;
        for ($i=0; $i<pg_numrows($result_desp); $i++) {
          db_fieldsmemory($result_desp,$i);
          // $estrutural = $c60_estrut;
          $estrutural = $o58_elemento."00";
          
          if (substr($estrutural,0,3)=='345' && substr($estrutural,3,2)!='91') {
            //  if (in_array($estrutural,$desp_inversoes)) {
              $tot_inicial    += $dot_ini;
              $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
              // adicional;
              $tot_emp_nobim  += $empenhado  - $anulado;
              $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
              $tot_liq_nobim  += $liquidado;
              $tot_liq_atebim += $liquidado_acumulado;
              if($bimestre == 6) {
                $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
              }
              
            }
          }
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
          for ($i=0; $i<pg_numrows($result_desp); $i++) {
            db_fieldsmemory($result_desp,$i);
            //  $estrutural = $c60_estrut;
            $estrutural = $o58_elemento."00";
            
            if (substr($estrutural,0,3)=='346' && substr($estrutural,3,2)!='91') {
              // if (in_array($estrutural,$desp_amortizacao)) {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
                
              }
            }
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
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
              if (substr($estrutural,0,3)=='399' && substr($estrutural,3,2)!='91') {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
                
              }
            }
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
            $tot_inicial    = 0;
            $tot_adicional  = 0;
            $tot_emp_nobim   = 0;
            $tot_emp_atebim  = 0;
            $tot_liq_nobim   = 0;
            $tot_liq_atebim  = 0;
            $tot_inscritos_rp = 0;
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
              if (substr($estrutural,0,3)=='377' && substr($estrutural,3,2)!='91') {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
              }
            }
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
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
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
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
              if (substr($estrutural,0,3) == "331" && substr($estrutural,3,2) == "91") {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
                
              }
            }
            
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
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
    
              if (substr($estrutural,0,3) == "332" && substr($estrutural,3,2) == "91") {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
              }
            }

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
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
    
              if (substr($estrutural,0,3) == "333" && substr($estrutural,3,2) == "91") {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
              }
            }

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
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
               db_fieldsmemory($result_desp,$i);
               $estrutural = $o58_elemento."00";
        
               if (substr($estrutural,0,3) == "344" && substr($estrutural,3,2) == "91") {
                 $tot_inicial    += $dot_ini;
                 $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                 // adicional;
                 $tot_emp_nobim  += $empenhado  - $anulado;
                 $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                 $tot_liq_nobim  += $liquidado;
                 $tot_liq_atebim += $liquidado_acumulado;
                 $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                 
               }
            }
            
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

            $tot_inicial    = 0;
            $tot_adicional  = 0;
            $tot_emp_nobim  = 0;
            $tot_emp_atebim = 0;
            $tot_liq_nobim  = 0;
            $tot_liq_atebim = 0;
            $tot_inscritos_rp = 0;
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
        
              if (substr($estrutural,0,3) == "345" && substr($estrutural,3,2) == "91") {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
                
              }
            }
            
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
            
            $tot_inicial    = 0;
            $tot_adicional  = 0;
            $tot_emp_nobim  = 0;
            $tot_emp_atebim = 0;
            $tot_liq_nobim  = 0;
            $tot_liq_atebim = 0;
            $tot_inscritos_rp = 0;
            for ($i=0; $i<pg_numrows($result_desp); $i++) {
              db_fieldsmemory($result_desp,$i);
              $estrutural = $o58_elemento."00";
        
              if (substr($estrutural,0,3) == "346" && substr($estrutural,3,2) == "91") {
                $tot_inicial    += $dot_ini;
                $tot_adicional  += $suplementado_acumulado - $reduzido_acumulado;
                // adicional;
                $tot_emp_nobim  += $empenhado  - $anulado;
                $tot_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                $tot_liq_nobim  += $liquidado;
                $tot_liq_atebim += $liquidado_acumulado;
                if($bimestre == 6) {
                  $tot_inscritos_rp += $empenhado_acumulado-$anulado_acumulado-$liquidado_acumulado;
                }
                
              }
            }
            
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
            $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 322 and 328 and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
            if ($clconrelvalor->numrows > 0){
              
              $tot_inicial      = pg_result($res_valor,0,"c83_informacao");
              $tot_adicional    = pg_result($res_valor,1,"c83_informacao"); 
              $tot_atualizada   = pg_result($res_valor,2,"c83_informacao");
              $tot_emp_nobim    = pg_result($res_valor,3,"c83_informacao");
              $tot_emp_atebim   = pg_result($res_valor,4,"c83_informacao");
              $tot_liq_nobim    = pg_result($res_valor,5,"c83_informacao");
              $tot_liq_atebim   = pg_result($res_valor,6,"c83_informacao");
              if($bimestre == 6) {
                $tot_inscritos_rp = pg_result($res_valor,7,"c83_informacao");
              }
              
            }

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
            
            $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 329 and 335 and c83_codigo = 369 and  c83_codigo = 368 and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
            if ($clconrelvalor->numrows > 0){
              
              $tot_inicial      = pg_result($res_valor,0,"c83_informacao");
              $tot_adicional    = pg_result($res_valor,1,"c83_informacao"); 
              $tot_atualizada   = pg_result($res_valor,2,"c83_informacao");
              $tot_emp_nobim    = pg_result($res_valor,3,"c83_informacao");
              $tot_emp_atebim   = pg_result($res_valor,4,"c83_informacao");
              $tot_liq_nobim    = pg_result($res_valor,5,"c83_informacao");
              $tot_liq_atebim   = pg_result($res_valor,6,"c83_informacao");
              if($bimestre == 6) {
                $tot_inscritos_rp = pg_result($res_valor,7,"c83_informacao");
              }
              
            }

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
            $v_nobim  += $tot_emp_nobim;
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
            $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"(c83_codigo between 336 and 342 and c83_codigo= 370) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
            if ($clconrelvalor->numrows > 0) {
              
              $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
              $tot_adicional  = pg_result($res_valor,1,"c83_informacao"); 
              $tot_atualizada = pg_result($res_valor,2,"c83_informacao");
              $tot_emp_nobim  = pg_result($res_valor,3,"c83_informacao");
              $tot_emp_atebim = pg_result($res_valor,4,"c83_informacao");
              $tot_liq_nobim  = pg_result($res_valor,5,"c83_informacao");
              $tot_liq_atebim = pg_result($res_valor,6,"c83_informacao");
              $tot_liq_atebim = pg_result($res_valor,7,"c83_informacao");
              
            }

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
            $somador_VII_inscritos    += $tot_inscritos_rp;
            
            $v_inicial    = $tot_inicial ;
            $v_adicional  = $tot_adicional;
            // ;
            $v_nobim  = $tot_emp_nobim;
            $v_emp_atebim = $tot_emp_atebim;
            $v_liq_nobim  = $tot_liq_nobim;
            $v_liqatebim  = $tot_liq_atebim;
            $v_inscritos  = $tot_inscritos_rp;
            
            //--------------------------------
            $tot_inicial     = 0;
            $tot_adicional   = 0;
            $tot_emp_nobim   = 0;
            $tot_emp_atebim  = 0;
            $tot_liq_nobim   = 0;
            $tot_liq_atebim  = 0;
            $tot_inscritos_rp = 0;
            
            $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"(c83_codigo between 343 and 349 and c83_codigo =371) and c83_periodo = '".$bimestre."B"."' and c83_instit in (".$instituicao.")"));
            if ($clconrelvalor->numrows > 0){
              
              $tot_inicial    = pg_result($res_valor,0,"c83_informacao");
              $tot_adicional  = pg_result($res_valor,1,"c83_informacao"); 
              $tot_atualizada = pg_result($res_valor,2,"c83_informacao");
              $tot_emp_nobim  = pg_result($res_valor,3,"c83_informacao");
              $tot_emp_atebim = pg_result($res_valor,4,"c83_informacao");
              $tot_liq_nobim  = pg_result($res_valor,5,"c83_informacao");
              $tot_liq_atebim = pg_result($res_valor,6,"c83_informacao");
              if($bimestre == 6) {
                $tot_inscritos_rp = pg_result($res_valor,7,"c83_informacao");
              }
            }
            
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
            $v_nobim  += $tot_emp_nobim;
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
             if ($bimestre == 6){
                 $verifica= $somador_VIII_liqatebim+$somador_VIII_inscritos;                     
              }else{
                 $verifica= $somador_VIII_liqatebim;                                        
              }
            $total_superavit = 0;
            $total_deficit   = 0;
            if ($somador_III_atebim > $verifica) {
              // receita realizada maior que despesa liquidada:superávit
              if ($bimestre == 6){
                  $somador_IX_liqatebim = $somador_III_atebim - ($somador_VIII_liqatebim+$somador_VIII_inscritos);
              }else{
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
                 if ($bimestre == 6){
                     $somador_IV_atebim = abs($somador_III_atebim - ($somador_VIII_liqatebim+$somador_VIII_inscritos)) ;
                  }else{
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
              $iTamanhoCelulaSuperavit= $iTamLiqAteBim+$iTamRPInscritos;
            } else {
              $iTamanhoCelulaSuperavit= $iTamLiqAteBim;
            }

            if ($bimestre == 6) { 
              $somador_XIV_atebim = $somador_VIII_liqatebim + $total_superavit + $somador_VIII_inscritos;
            } else {
              $somador_XIV_atebim = $somador_VIII_liqatebim + $total_superavit;
            }
            $pdf->cell($iTamanhoCelulaSuperavit,$alt,db_formatar($somador_XIV_atebim ,'f'),'TBR',0,"R",0);
            
            @$pdf->cell(10,$alt,db_formatar($somador_VIII_liqatebim*100/($somador_VIII_inicial+$somador_VIII_adicional),'f'),'TBR',0,"R",0);
            // % (j/f)
            $pdf->cell(14,$alt,db_formatar((($somador_VI_inicial  +$somador_VI_adicional  ) - ($somador_VI_inscritos +$somador_VI_liqatebim)),'f'),'TB',1,"R",0);
            // (f-j)
            
            $pdf->setY($pos);
            //--------------------------------
            
            $pdf->ln(2);

            notasExplicativas(&$pdf,22,"{$bimestre}B",190);

            $pdf->ln(20);
            
            
            // assinaturas
            assinaturas(&$pdf,&$classinatura,'LRF');
            
            // saida
            $pdf->Output();
?>