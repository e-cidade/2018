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


if (!isset($arqinclude)){
  // se este arquivo no esta incluido por outro
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_liborcamento.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_libtxt.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_conrelinfo_classe.php");
  include("classes/db_conrelvalor_classe.php");
  include("classes/db_orcparamrel_classe.php");
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $classinatura  = new cl_assinatura;
  $orcparamrel   = new cl_orcparamrel;
  $clconrelinfo  = new cl_conrelinfo;
  $clconrelvalor = new cl_conrelvalor;
  
  $anousu = db_getsession("DB_anousu");
  $dt     = data_periodo($anousu,$periodo);

  $periodo_selecionado = $periodo;

  // no dbforms/db_funcoes.php
  $dt_ini = $dt[0];
  // data inicial do periodo
  $dt_fin = $dt[1];
  // data final do periodo
  $periodo = $dt["periodo"]; 
  $texto   = $dt["texto"];
} else {
  $periodo = "";
}

$somador30_valor = 0;
$somador31_valor = 0;

// PARAMETROS - RECEITAS
for ($linha = 1; $linha <= 37; $linha++){
  $m_receita[$linha]["estrut"]     = $orcparamrel->sql_parametro("31",$linha); 
  $m_receita[$linha]["nivel"]      = $orcparamrel->sql_nivel("31",$linha);
  $m_receita[$linha]["recurso"]    = $orcparamrel->sql_recurso("31",$linha);

  // Previsao Inicial
  $m_receita[$linha]["inicial"]    = 0;
  // Previsao Atualizada
  $m_receita[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $m_receita[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $m_receita[$linha]["exercicio"]  = 0;
}

// PARAMETROS - DESPESAS
for ($linha = 38; $linha <= 52; $linha++){
  $m_despesa[$linha]["estrut"]        = $orcparamrel->sql_parametro("31",$linha);
  $m_despesa[$linha]["nivel"]         = $orcparamrel->sql_nivel("31",$linha);
  $m_despesa[$linha]["nivelexclusao"] = $orcparamrel->sql_nivelexclusao("31",$linha);
  $m_despesa[$linha]["funcao"]        = $orcparamrel->sql_funcao("31",$linha);  
  $m_despesa[$linha]["subfunc"]       = $orcparamrel->sql_subfunc("31",$linha);
  $m_despesa[$linha]["recurso"]       = $orcparamrel->sql_recurso("31",$linha);

  // DOTACAO INICIAL
  $m_despesa[$linha]["inicial"]       = 0;
  // DOTACAO ATUALIZADA
  $m_despesa[$linha]["atualizada"]    = 0;
  // NO BIMESTRE/SEMESTRE
  $m_despesa[$linha]["bimestre"]      = 0;
  // ATE O BIMESTRE/SEMESTRE
  $m_despesa[$linha]["exercicio"]     = 0;
}

// RESTOS A PAGAR COM ENSINO
$m_restos_mde["estrut"]    = $orcparamrel->sql_parametro("31","53");
$m_restos_mde["funcao"]    = $orcparamrel->sql_funcao("31","53");
$m_restos_mde["subfunc"]   = $orcparamrel->sql_subfunc("31","53");
$m_restos_mde["recurso"]   = $orcparamrel->sql_recurso("31","53");
$m_restos_mde["saldo"]     = 0;
$m_restos_mde["cancelado"] = 0;

// FLUXO FINANCEIRO - FUNDEB
$m_fluxo_fundeb["estrut"]          = $orcparamrel->sql_parametro("31","54");
$m_fluxo_fundeb["valor_inscricao"] = 0;
$m_fluxo_fundeb["valor_atual"]     = 0;
$m_fluxo_fundeb["valor_credito"]   = 0;
$m_fluxo_fundeb["valor_debito"]    = 0;

// APLICACAO FINANCEIRA - FUNDEB
$m_aplicacao_fundeb["estrut"] = $orcparamrel->sql_parametro("31","55");
$m_aplicacao_fundeb["valor"]  = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tela do relatorio
$receita = array();

$receita[1]["txt"]  = "1 - RECEITAS DE IMPOSTOS";
$receita[2]["txt"]  = "    1.1 - Receita Resultante do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU";
$receita[3]["txt"]  = "          Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU";
$receita[4]["txt"]  = "          Multas, Juros de Mora e Outros Encargos do IPTU";
$receita[5]["txt"]  = "          Dívida Ativa do IPTU";
$receita[6]["txt"]  = "          Multas, Juros de Mora, Atual. Monetária e Outros Encargos da Dívida Ativa do IPTU";
$receita[7]["txt"]  = "    1.2 - Receita Resultante do Imposto sobre Transmissão Inter Vivos - ITBI";
$receita[8]["txt"]  = "          Imposto sobre Transmissão Inter Vivos - ITBI";
$receita[9]["txt"]  = "          Multas, Juros de Mora e Outros Encargos do ITBI";
$receita[10]["txt"] = "          Dívida Ativa do ITBI";
$receita[11]["txt"] = "          Multas, Juros de Mora, Atual. Monetária e Outros Encargos da Dívida Ativa do ITBI";
$receita[12]["txt"] = "    1.3 - Receita Resultante do Imposto sobre Serviços de Qualquer Natureza - ISS";
$receita[13]["txt"] = "          Imposto sobre Serviços de Qualquer Natureza - ISS";
$receita[14]["txt"] = "          Multas, Juros de Mora e Outros Encargos do ISS";
$receita[15]["txt"] = "          Dívida Ativa do ISS";
$receita[16]["txt"] = "          Multas, Juros de Mora, Atual. Monetária e Outros Encargos da Dívida Ativa do ISS";
$receita[17]["txt"] = "    1.4 - Receita Resultante do Imposto de Renda Retido na Fonte - IRRF";
$receita[18]["txt"] = "          Imposto de Renda Retido na Fonte - IRRF";
$receita[19]["txt"] = "          Multas, Juros de Mora e Outros Encargos do IRRF";
$receita[20]["txt"] = "          Dívida Ativa do IRRF";
$receita[21]["txt"] = "          Multas, Juros de Mora, Atual. Monetária e Outros Encargos da Dívida Ativa do IRRF";
$receita[22]["txt"] = "2 - RECEITAS DE TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS";
$receita[23]["txt"] = "    2.1 - Cota-Parte FPM";
$receita[24]["txt"] = "    2.2 - Cota-Parte ICMS";
$receita[25]["txt"] = "    2.3 - ICMS-Desoneração - L.C. nº87/1996";
$receita[26]["txt"] = "    2.4 - Cota-Parte IPI-Exportação";
$receita[27]["txt"] = "    2.5 - Cota-Parte ITR";
$receita[28]["txt"] = "    2.6 - Cota-Parte IPVA";
$receita[29]["txt"] = "    2.7 - Cota-Parte IOF-Ouro";
$receita[30]["txt"] = "4 - TRANSFERÊNCIAS DO FNDE";
$receita[31]["txt"] = "    4.1 - Transferências do Salário-Educação";
$receita[32]["txt"] = "    4.2 - Outras Transferências do FNDE";
$receita[33]["txt"] = "5 - TRANSFERÊNCIAS DE CONVÊNIOS DESTINADAS A PROGRAMAS DE EDUCAÇÃO";
$receita[34]["txt"] = "6 - RECEITA DE OPERAÇÕES DE CRÉDITO DESTINADA À EDUCAÇÃO";
$receita[35]["txt"] = "7 - OUTRAS RECEITAS DESTINADAS À EDUCAÇÃO";
$receita[36]["txt"] = "9 - RECEITAS DESTINADAS AO FUNDEB";
$receita[37]["txt"] = "    9.2 - Cota-Parte FPM Destinada ao FUNDEB - (16,66% de 2.1)";
$receita[38]["txt"] = "    9.2 - Cota-Parte ICMS Destinada ao FUNDEB - (16,66% de 2.2)";
$receita[39]["txt"] = "    9.3 - ICMS-Desoneração Destinada ao FUNDEB - (16,66% de 2.3)";
$receita[40]["txt"] = "    9.4 - Cota-Parte IPI-Exportação Destinada ao FUNDEB - (16,66% de 2.4)";
$receita[41]["txt"] = "    9.5 - Cota-Parte ITR Destinada ao FUNDEB - (6,66% de 2.5)";
$receita[42]["txt"] = "    9.6 - Cota-Parte IPVA Destinada ao FUNDEB - (6,66% de 2.6)";
$receita[43]["txt"] = "10 - RECEITAS RECEBIDAS DO FUNDEB";
$receita[44]["txt"] = "     10.1 - Transferências de Recursos do FUNDEB";
$receita[45]["txt"] = "     10.2 - Complementação da União ao FUNDEB";
$receita[46]["txt"] = "     10.3 - Receita de Aplicação Financeira dos Recursos do FUNDEB";

for($linha = 1; $linha <= 46; $linha++){
  // Previsao Inicial
  $receita[$linha]["inicial"]    = 0;
  // Previsao Atualizada
  $receita[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $receita[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $receita[$linha]["exercicio"]  = 0;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcoes para receitas e despesas
// Receitas
$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result    = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
//db_criatabela($result); exit;

for ($i = 0; $i < pg_numrows($result); $i++){
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  $v_recurso  = $o70_codigo;

  for ($linha = 1; $linha <= 37; $linha++){
    if (in_array($estrutural,$m_receita[$linha]["estrut"])){
      if (count($m_receita[$linha]["recurso"])==0 || in_array($v_recurso, $m_receita[$linha]["recurso"])){
        if ($linha < 27){
           $m_receita[$linha]["inicial"]    += ($saldo_inicial);
           $m_receita[$linha]["atualizada"] += ($saldo_inicial_prevadic);
           $m_receita[$linha]["bimestre"]   += ($saldo_arrecadado);
           $m_receita[$linha]["exercicio"]  += ($saldo_arrecadado_acumulado);
        }else{
          // echo "aqui $linha<br>";
           $m_receita[$linha]["inicial"]    += abs($saldo_inicial);
           $m_receita[$linha]["atualizada"] += abs($saldo_inicial_prevadic);
           $m_receita[$linha]["bimestre"]   += abs($saldo_arrecadado);
           $m_receita[$linha]["exercicio"]  += abs($saldo_arrecadado_acumulado);

        }
      }
    }
  }

  if (in_array($estrutural,$m_aplicacao_fundeb["estrut"])){
    $m_aplicacao_fundeb["valor"] += $saldo_arrecadado_acumulado;
  }
}

for ($col = 1; $col <= 4; $col++){
  $pcol =array(1=>"inicial",2=>"atualizada",3=>"bimestre",4=>"exercicio");

  // Imposto IPTU                             
  $receita[3][$pcol[$col]] = $m_receita[1][$pcol[$col]];
  // Outros encargos IPTU
  $receita[4][$pcol[$col]] = $m_receita[2][$pcol[$col]];
  // Divida Ativa IPTU
  $receita[5][$pcol[$col]] = $m_receita[3][$pcol[$col]];
  // Outros encargos Divida Ativa IPTU
  $receita[6][$pcol[$col]] = $m_receita[4][$pcol[$col]];

  // 1.1 - Receita Resultante de IPTU
  $receita[2][$pcol[$col]] = $receita[3][$pcol[$col]] + $receita[4][$pcol[$col]]+
                             $receita[5][$pcol[$col]] + $receita[6][$pcol[$col]];

  // Imposto ITBI
  $receita[8][$pcol[$col]]  = $m_receita[5][$pcol[$col]];
  // Outros encargos ITBI
  $receita[9][$pcol[$col]]  = $m_receita[6][$pcol[$col]];
  // Divida Ativa ITBI
  $receita[10][$pcol[$col]] = $m_receita[7][$pcol[$col]];
  // Outros encargos Divida Ativa ITBI
  $receita[11][$pcol[$col]] = $m_receita[8][$pcol[$col]];
  
  // 1.2 - Receita Resultante de ITBI
  $receita[7][$pcol[$col]] = $receita[8][$pcol[$col]]  + $receita[9][$pcol[$col]]+
                             $receita[10][$pcol[$col]] + $receita[11][$pcol[$col]];
  
  // Imposto ISS 
  $receita[13][$pcol[$col]] = $m_receita[9][$pcol[$col]];
  // Outros encargos ISS
  $receita[14][$pcol[$col]] = $m_receita[10][$pcol[$col]];
  // Divida Ativa ISS
  $receita[15][$pcol[$col]] = $m_receita[11][$pcol[$col]];
  // Outros encargos Divida Ativa ISS
  $receita[16][$pcol[$col]] = $m_receita[12][$pcol[$col]];
  
  // 1.3 - Receita Resultante de ISS
  $receita[12][$pcol[$col]] = $receita[13][$pcol[$col]] + $receita[14][$pcol[$col]]+
                              $receita[15][$pcol[$col]] + $receita[16][$pcol[$col]];

  // Imposto IRRF
  $receita[18][$pcol[$col]] = $m_receita[13][$pcol[$col]];
  // Outros encargos IRRF
  $receita[19][$pcol[$col]] = $m_receita[14][$pcol[$col]];
  // Divida Ativa IRRF
  $receita[20][$pcol[$col]] = $m_receita[15][$pcol[$col]];
  // Outros encargos Divida Ativa IRRF
  $receita[21][$pcol[$col]] = $m_receita[16][$pcol[$col]];

  // 1.4 - Receita Resultante de IRRF
  $receita[17][$pcol[$col]] = $receita[18][$pcol[$col]] + $receita[19][$pcol[$col]]+
                              $receita[20][$pcol[$col]] + $receita[21][$pcol[$col]];

  // 1 - RECEITAS DE IMPOSTOS
  $receita[1][$pcol[$col]] = $receita[2][$pcol[$col]]  + $receita[7][$pcol[$col]] +
                             $receita[12][$pcol[$col]] + $receita[17][$pcol[$col]];

  // 2.1 FPM                             
  $receita[23][$pcol[$col]] = $m_receita[17][$pcol[$col]];
  // 2.2 ICMS                            
  $receita[24][$pcol[$col]] = $m_receita[18][$pcol[$col]];
  // 2.3 Desoneracao ICMS                            
  $receita[25][$pcol[$col]] = $m_receita[19][$pcol[$col]];
  // 2.4 IPI                            
  $receita[26][$pcol[$col]] = $m_receita[20][$pcol[$col]];
  // 2.5 ITR                            
  $receita[27][$pcol[$col]] = $m_receita[21][$pcol[$col]];
  // 2.6 IPVA                           
  $receita[28][$pcol[$col]] = $m_receita[22][$pcol[$col]];
  // 2.7 IOF                            
  $receita[29][$pcol[$col]] = $m_receita[23][$pcol[$col]];

  // 2 - RECEITA DE TRANSFERENCIAS
  $receita[22][$pcol[$col]] = $receita[23][$pcol[$col]] + $receita[24][$pcol[$col]] + $receita[25][$pcol[$col]] + 
                              $receita[26][$pcol[$col]] + $receita[27][$pcol[$col]] + $receita[28][$pcol[$col]] +
                              $receita[29][$pcol[$col]];

  // OUTRAS RECEITAS DESTINADAS AO ENSINO
  // 4.1 Transferencias Salario-Educacao
  $receita[31][$pcol[$col]] = $m_receita[24][$pcol[$col]];
  // 4.2 Outras Transferencias do FNDE
  $receita[32][$pcol[$col]] = $m_receita[25][$pcol[$col]];

  // 4 - TRANSFERENCIA DO FNDE
  $receita[30][$pcol[$col]] = $receita[31][$pcol[$col]] + $receita[32][$pcol[$col]];

  // 5 - CONVENIOS DESTINADOS A PROGRAMAS DE EDUCACAO
  $receita[33][$pcol[$col]] = $m_receita[26][$pcol[$col]];
  // 6 - OPERACOES DE CREDITO 
  $receita[34][$pcol[$col]] = $m_receita[27][$pcol[$col]];
  // 7 - OUTRAS RECEITAS DESTINADAS A EDUCACAO       
  $receita[35][$pcol[$col]] = $m_receita[28][$pcol[$col]];
  
  // 9.2 Cota FPM       
  $receita[37][$pcol[$col]] = $m_receita[29][$pcol[$col]];
  // 9.2 Cota ICMS      
  $receita[38][$pcol[$col]] = $m_receita[30][$pcol[$col]];
  // 9.3 ICMS Desoneracao
  $receita[39][$pcol[$col]] = $m_receita[31][$pcol[$col]];
  // 9.4 Cota IPI
  $receita[40][$pcol[$col]] = $m_receita[32][$pcol[$col]];
  // 9.5 Cota ITR
  $receita[41][$pcol[$col]] = $m_receita[33][$pcol[$col]];
  // 9.6 Cota IPVA
  $receita[42][$pcol[$col]] = $m_receita[34][$pcol[$col]];
  
  // 9 - RECEITAS DO FUNDEB
  $receita[36][$pcol[$col]] = $receita[37][$pcol[$col]] + $receita[38][$pcol[$col]] + $receita[39][$pcol[$col]] +
                              $receita[40][$pcol[$col]] + $receita[41][$pcol[$col]] + $receita[42][$pcol[$col]];

  // 10.1 Transferencias FUNDEB
  $receita[44][$pcol[$col]] = $m_receita[35][$pcol[$col]];
  // 10.2 Complementacao FUNDEB
  $receita[45][$pcol[$col]] = $m_receita[36][$pcol[$col]];
  // 10.3 Receita aplic. financeira FUNDEB
  $receita[46][$pcol[$col]] = $m_receita[37][$pcol[$col]];

  // 10 - RECEITAS RECEBIDAS FUNDEB
  $receita[43][$pcol[$col]] = $receita[44][$pcol[$col]] + $receita[45][$pcol[$col]] + $receita[46][$pcol[$col]];
}

// DESPESAS
$despesa = array();

$despesa[1]["txt"]  = "12 - PAGAMENTO DOS PROFISSIONAIS DO MAGISTÉRIO";
$despesa[2]["txt"]  = "     12.1 - Com Educação Infantil";
$despesa[3]["txt"]  = "     12.2 - Com Ensino Fundamental";
$despesa[4]["txt"]  = "13 - OUTRAS DESPESAS";
$despesa[5]["txt"]  = "     13.1 - Com Educação Infantil";
$despesa[6]["txt"]  = "     13.2 - Com Ensino Fundamental";
$despesa[7]["txt"]  = "17 - EDUCAÇÃO INFANTIL";
$despesa[8]["txt"]  = "     17.1 - Despesas Custeadas com Recursos do FUNDEB";
$despesa[9]["txt"]  = "     17.2 - Despesas Custeadas com Outros Recursos de Impostos";
$despesa[10]["txt"] = "18 - ENSINO FUNDAMENTAL";
$despesa[11]["txt"] = "     18.1 - Despesas Custeadas com Recursos do FUNDEB";
$despesa[12]["txt"] = "     18.2 - Despesas Custeadas com Outros Recursos de Impostos";
$despesa[13]["txt"] = "19 - ENSINO MÉDIO";
$despesa[14]["txt"] = "20 - ENSINO SUPERIOR";
$despesa[15]["txt"] = "21 - ENSINO PROFISSIONAL NÃO INTEGRADO AO ENSINO REGULAR";
$despesa[16]["txt"] = "22 - OUTRAS";
$despesa[17]["txt"] = "32 - CONTRIBUIÇÃO SOCIAL DO SALÁRIO-EDUCAÇÃO";
$despesa[18]["txt"] = "33 - RECURSOS DE OPERAÇÕES DE CRÉDITO";
$despesa[19]["txt"] = "34 - OUTROS RECURSOS DESTINADOS À EDUCAÇÃO";

for($linha = 1; $linha <= 19; $linha++){
  // Dotacao Inicial
  $despesa[$linha]["inicial"]    = 0;
  // Dotacao Atualizada
  $despesa[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $despesa[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $despesa[$linha]["exercicio"]  = 0;
}

$sele_work = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
$result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

for ($i = 0; $i < pg_numrows($result_despesa); $i++) {
  db_fieldsmemory($result_despesa, $i);
  
  for ($linha = 38; $linha <= 52; $linha++) {
    $nivel        = $m_despesa[$linha]["nivel"];
    $estrutural   = $o58_elemento."00";
    $estrutural   = substr($estrutural,0,$nivel);
    $v_estrutural = str_pad($estrutural,15,"0",STR_PAD_RIGHT);
    $v_funcao     = $o58_funcao;
    $v_subfuncao  = $o58_subfuncao;
    $v_recurso    = $o58_codigo;
    
    if (in_array($v_estrutural, $m_despesa[$linha]["estrut"])) {
      if (count($m_despesa[$linha]["funcao"])      == 0 || in_array($v_funcao, $m_despesa[$linha]["funcao"])) {
        if (count($m_despesa[$linha]["subfunc"])   == 0 || in_array($v_subfuncao, $m_despesa[$linha]["subfunc"])) {
          if (count($m_despesa[$linha]["recurso"]) == 0 || in_array($v_recurso, $m_despesa[$linha]["recurso"])) {
            $m_despesa[$linha]["inicial"]    += $dot_ini;
            $m_despesa[$linha]["atualizada"] += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
            $m_despesa[$linha]["bimestre"]   += $liquidado;
            $m_despesa[$linha]["exercicio"]  += $liquidado_acumulado;
          }
        }
      }
    }
  }
}  

for ($col = 1; $col <= 4; $col++){
  $pcol = array(1=>"inicial",2=>"atualizada",3=>"bimestre",4=>"exercicio");
  
  // 12.1 PGTO PROFISSIONAIS - Educacao Infantil
  $despesa[2][$pcol[$col]] = $m_despesa[38][$pcol[$col]];
  // 12.2 PGTO PROFISSIONAIS - Ensino Fundamental
  $despesa[3][$pcol[$col]] = $m_despesa[39][$pcol[$col]];

  // 12 - PAGAMENTO PROFISSIONAIS DO MAGISTERIO
  $despesa[1][$pcol[$col]] = $despesa[2][$pcol[$col]] + $despesa[3][$pcol[$col]];

  // 13.1 OUTRAS DESPESAS - Educacao Infantil
  $despesa[5][$pcol[$col]] = $m_despesa[40][$pcol[$col]];
  // 13.2 OUTRAS DESPESAS - Ensino Fundamental
  $despesa[6][$pcol[$col]] = $m_despesa[41][$pcol[$col]];

  // 13 - OUTRAS DESPESAS
  $despesa[4][$pcol[$col]] = $despesa[5][$pcol[$col]] + $despesa[6][$pcol[$col]];

  // 17.1 Despesas com Recursos do FUNDEB
  $despesa[8][$pcol[$col]] = $m_despesa[42][$pcol[$col]];
  // 17.2 Despesas com outros Recursos de Impostos
  $despesa[9][$pcol[$col]] = $m_despesa[43][$pcol[$col]];

  // 17 EDUCACAO INFANTIL
  $despesa[7][$pcol[$col]] = $despesa[8][$pcol[$col]] + $despesa[9][$pcol[$col]];

  // 18.1 Despesas com Recursos do FUNDEB
  $despesa[11][$pcol[$col]] = $m_despesa[44][$pcol[$col]];
  // 18.2 Despesas com outros Recursos de Impostos
  $despesa[12][$pcol[$col]] = $m_despesa[45][$pcol[$col]];

  // 18 ENSINO FUNDAMENTAL
  $despesa[10][$pcol[$col]] = $despesa[11][$pcol[$col]] + $despesa[12][$pcol[$col]];
  
  // 19 ENSINO MEDIO
  $despesa[13][$pcol[$col]] = $m_despesa[46][$pcol[$col]];
  // 20 ENSINO SUPERIOR
  $despesa[14][$pcol[$col]] = $m_despesa[47][$pcol[$col]];
  // 21 ENSINO PROFISSIONAL
  $despesa[15][$pcol[$col]] = $m_despesa[48][$pcol[$col]];
  // 22 OUTRAS
  $despesa[16][$pcol[$col]] = $m_despesa[49][$pcol[$col]];

  // 32 CONTRIBUICAO SOCIAL SALARIO-EDUCACAO
  $despesa[17][$pcol[$col]] = $m_despesa[50][$pcol[$col]];
  // 33 RECURSOS DE OPERACOES DE CREDITO
  $despesa[18][$pcol[$col]] = $m_despesa[51][$pcol[$col]];
  // 34 OUTROS RECURSOS DESTINADOS A EDUCACAO
  $despesa[19][$pcol[$col]] = $m_despesa[52][$pcol[$col]];
}

// RESTOS A PAGAR
/*
$m_restos_mde["estrut"]    = $orcparamrel->sql_parametro("31","53");
$m_restos_mde["funcao"]    = $orcparamrel->sql_funcao("31","53");
$m_restos_mde["subfunc"]   = $orcparamrel->sql_subfunc("31","53");
$m_restos_mde["recurso"]   = $orcparamrel->sql_recurso("31","53");
$m_restos_mde["saldo"]     = 0;
$m_restos_mde["cancelado"] = 0;
*/

$v_funcao  = "0";
$v_subfunc = "0";
$v_codigo  = "0";
$sp        = "";

foreach($m_restos_mde["funcao"] as $registro) {
  $v_funcao .= $sp.$registro;
  $sp        = ",";
}

$sp = "";

foreach($m_restos_mde["subfunc"] as $registro) {
  $v_subfunc .= $sp.$registro;
  $sp         = ",";
}

$sp = "";

foreach($m_restos_mde["recurso"] as $registro) {
  $v_codigo .= $sp.$registro;
  $sp        = ",";
}

$dt_ini2   = $anousu."-01-01";
$db_filtro = " in (".str_replace("-",", ",$db_selinstit).")";
$result_restos_mde = db_rpsaldo($anousu,
                                $db_filtro,
                                $dt_ini2,
                                $dt_fin,
                                " o58_codigo    in (".$v_codigo.")  and 
                                  o58_funcao    in (".$v_funcao.")  and 
                                  o58_subfuncao in (".$v_subfunc.") ");

$cancelado = 0;
$saldo     = 0;

//db_criatabela($result_restos_mde); exit;
for($i = 0; $i < pg_numrows($result_restos_mde); $i++){
  db_fieldsmemory($result_restos_mde,$i);

  $cancelado += $vlranu;
  $saldo     += (($anterior_a_liquidar + $anterior_liquidado) - $vlranu) - $vlrpag;
}

$m_restos_mde["saldo"]     = $saldo;
$m_restos_mde["cancelado"] = $cancelado;

//db_criatabela($result_restos_mde); exit;

// FIM RESTOS A PAGAR
// FLUXO FINANCEIRO
/*
$m_fluxo_fundeb["estrut"]          = $orcparamrel->sql_parametro("31","54");
$m_fluxo_fundeb["valor_inscricao"] = 0;
$m_fluxo_fundeb["valor_inscricao"] = 0;
*/

$db_filtro      = " c61_instit in (".str_replace("-",", ",$db_selinstit).") ";
$result_bal_ver = db_planocontassaldo_matriz($anousu,$dt_ini2,$dt_fin,false,$db_filtro);

//db_criatabela($result_bal_ver); exit;
//print_r($m_fluxo_fundeb); exit;
for($i = 0; $i < pg_numrows($result_bal_ver); $i++){
  db_fieldsmemory($result_bal_ver,$i);

  if (in_array($estrutural,$m_fluxo_fundeb["estrut"])){
    $m_fluxo_fundeb["valor_inscricao"] += $saldo_anterior;
    $m_fluxo_fundeb["valor_atual"]     += $saldo_final; 
    $m_fluxo_fundeb["valor_credito"]   += $saldo_anterior_credito;
    $m_fluxo_fundeb["valor_debito"]    += $saldo_anterior_debito;
  }
}
// FIM FLUXO FINANCEIRO

$fluxo = array();

$fluxo[1]["txt"] = "38 - SALDO FINANCEIRO DO FUNDEB EM 31 DE DEZEMBRO DE ".($anousu-1);
$fluxo[2]["txt"] = "     38.1 - (+) INGRESSO DE RECURSOS DO FUNDEB ATÉ O ".strtoupper($periodo);
$fluxo[3]["txt"] = "     38.2 - (-) PAGAMENTOS EFETUADOS ATÉ O ".strtoupper($periodo);
$fluxo[4]["txt"] = "     38.3 - (+) RECEITA DE APLICAÇÃO FINANCEIRA DOS RECURSOS DO FUNDEB ATÉ O ".strtoupper($periodo);
$fluxo[5]["txt"] = "39 - (=) SALDO FINANCEIRO DO FUNDEB NO EXERCÍCIO ATUAL";   

for($linha = 1; $linha <= 5; $linha++){
  $fluxo[$linha]["valor"] = 0;
}

$fluxo[1]["valor"] = $m_fluxo_fundeb["valor_inscricao"];
$fluxo[2]["valor"] = $m_fluxo_fundeb["valor_debito"];
$fluxo[3]["valor"] = $m_fluxo_fundeb["valor_credito"];
$fluxo[5]["valor"] = $m_fluxo_fundeb["valor_atual"];

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($arqinclude)){
  $xinstit    = split("-",$db_selinstit);
  $resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
  $descr_inst = "";
  $xvirg      = "";
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
    if (strlen($descr_inst) > 42) {
      $descr_inst = substr($descr_inst,0,100);
    }
  }
    
  $head1 = $descr_inst;
  $head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS COM DESENVOLVIMENTO E MANUTENÇÃO DO ENSINO - MDE";
  $head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";


  $dt    = split("-",$dt_ini);
  $txt   = strtoupper(db_mes($dt[1]))."-";
  $dt    = split("-",$dt_fin);
  $txt  .= strtoupper(db_mes($dt[1]))." $anousu/".$texto;

  $head5 = "$txt";

  $pdf   = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);

  $total  = 0;
  $troca  = 1;
  $alt    = 4;
  $tottotal =  0;
  $pagina   =  1;
  $n1       =  5;
  $n2       = 10;

  $pdf->addpage();
  $pdf->setfont('arial','',6);
  $pdf->cell(90,$alt,"RREO - ANEXO X (Lei n".chr(176)." 9.394/1996, art. 72)",0,0,"L",0);
  $pdf->cell(100,$alt,"R$ 1,00",0,1,"R",0);
  $pdf->cell(190,($alt-2)," ","T",1,"L",0);
  $pdf->setfont('arial','B',6);
  $pdf->cell(190,($alt*2),"RECEITAS DO ENSINO",0,1,"C",0);
  
  $pdf->setfont('arial','',6);

// Cabecalho de receitas
  $pdf->cell(90,($alt*2),"RECEITAS BRUTA DE IMPOSTOS",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(b)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'TB',0,"C",0);
  $pdf->ln();

  for ($linha=1; $linha <= 29; $linha++) {
    $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($receita[$linha]['exercicio']*100)/$receita[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }

  $somador3_inicial     = $receita[1]["inicial"]    + $receita[22]["inicial"];
  $somador3_atualizada  = $receita[1]["atualizada"] + $receita[22]["atualizada"];
  $somador3_nobimestre  = $receita[1]["bimestre"]   + $receita[22]["bimestre"];
  $somador3_atebimestre = $receita[1]["exercicio"]  + $receita[22]["exercicio"];

  $pdf->cell(90,$alt,"3 - TOTAL DA RECEITA BRUTA DE IMPOSTOS(1 + 2)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador3_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador3_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador3_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador3_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador3_atebimestre*100)/$somador3_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $pdf->cell(90,($alt*2),"OUTRAS RECEITAS DESTINADAS AO ENSINO",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(b)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'TB',0,"C",0);
  $pdf->ln();

  for ($linha=30; $linha <= 35; $linha++) {
    $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($receita[$linha]['exercicio']*100)/$receita[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }
  
  $somador8_inicial     = $receita[30]["inicial"]    + $receita[33]["inicial"] +
                          $receita[34]["inicial"]    + $receita[35]["inicial"];
  $somador8_atualizada  = $receita[30]["atualizada"] + $receita[33]["atualizada"] +
                          $receita[34]["atualizada"] + $receita[35]["atualizada"];
  $somador8_nobimestre  = $receita[30]["bimestre"]   + $receita[33]["bimestre"] +
                          $receita[34]["bimestre"]   + $receita[35]["bimestre"];
  $somador8_atebimestre = $receita[30]["exercicio"]  + $receita[33]["exercicio"] +
                          $receita[34]["exercicio"]  + $receita[35]["exercicio"];

  $pdf->cell(90,$alt,"8 - TOTAL DAS OUTRAS RECEITAS DESTINADAS AO ENSINO(4 + 5 + 6 + 7)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador8_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador8_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador8_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador8_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador8_atebimestre*100)/$somador8_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $pdf->setfont('arial','B',6);
  $pdf->cell(190,($alt*2),"FUNDEB",0,1,"C",0);
  
  $pdf->setfont('arial','',6);

  $pdf->cell(90,($alt*2),"RECEITAS DO FUNDEB",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(b)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'TB',0,"C",0);
  $pdf->ln();
  
  for ($linha=36; $linha <= 46; $linha++) {
    if ($linha == 45){
      $pdf->cell(150,($alt*2),'Continuação da página 1',0,1,"L",0);
    }

    $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar(abs($receita[$linha]['inicial']),'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar(abs($receita[$linha]['atualizada']),'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar(abs($receita[$linha]['bimestre']),'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar(abs($receita[$linha]['exercicio']),'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar((abs($receita[$linha]['exercicio']*100)/$receita[$linha]['atualizada']),'f'),0,0,"R",0);
    $pdf->Ln();

    if ($linha == 44){
      $pdf->cell(150,($alt*2),'Continua na página 2',0,1,"L",0);
    }
  }

  $somador11_inicial     = $receita[44]["inicial"]    - $receita[36]["inicial"];
  $somador11_atualizada  = $receita[44]["atualizada"] - $receita[36]["atualizada"];
  $somador11_nobimestre  = $receita[44]["bimestre"]   - $receita[36]["bimestre"];
  $somador11_atebimestre = $receita[44]["exercicio"]  - $receita[36]["exercicio"];
  
  $pdf->cell(90,$alt,"11 - RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB(10.1 - 9)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar(abs($somador11_inicial),"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(abs($somador11_atualizada),"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(abs($somador11_nobimestre),"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(abs($somador11_atebimestre),"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(abs(($somador11_atebimestre*100)/$somador11_atualizada),'f'),'TB',0,"R",0);
  $pdf->ln();

  $pdf->cell(190,$alt,"[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (11) > 0] = ACRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB",0,1,"L",0);
  $pdf->cell(190,$alt,"[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (11) < 0] = DECRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB",0,1,"L",0);
// Fim das RECEITAS

// INICIO das despesas
  $pdf->cell(90,($alt*2),"DESPESAS DO FUNDEB",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(d)",1,0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(e)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'TB',0,"C",0);
  $pdf->ln();

  for ($linha=1; $linha <= 6; $linha++) {
    $pdf->cell(90,$alt,$despesa[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($despesa[$linha]['exercicio']*100)/$despesa[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }

  $somador14_inicial     = $despesa[1]["inicial"]    + $despesa[4]["inicial"];
  $somador14_atualizada  = $despesa[1]["atualizada"] + $despesa[4]["atualizada"];
  $somador14_nobimestre  = $despesa[1]["bimestre"]   + $despesa[4]["bimestre"];
  $somador14_atebimestre = $despesa[1]["exercicio"]  + $despesa[4]["exercicio"];

  $pdf->cell(90,$alt,"14 - TOTAL DAS DESPESAS DO FUNDEB(12 + 13)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador14_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador14_atebimestre*100)/$somador14_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $perc_12    = $despesa[1]["exercicio"];
  $perc_10    = $receita[43]["exercicio"];

  $percentual = @(($perc_12/$perc_10)*100);
                 
  $pdf->cell(170,$alt,"15 - MÍNIMO DAS DESPESAS DO FUNDEB(12/10) x 100%","TBR",0,"L",0);
  @$pdf->cell(20,$alt,db_formatar($percentual,'f'),'TB',0,"R",0);
  $pdf->ln();
  
  $pdf->setfont('arial','B',6);
  $pdf->cell(190,($alt*2),"CÁLCULO DO LIMITE MÍNIMO COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO",0,1,"C",0);
  
  $pdf->setfont('arial','',6);
  
  $pdf->cell(90,($alt*2),"RECEITAS COM AÇÕES TÍPICAS DE MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(b)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'TB',0,"C",0);
  $pdf->ln();
  
  $pdf->cell(90,$alt,"16 - IMPOSTOS E TRANSFERÊNCIAS DESTINADAS À MDE (25% de 3)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar(($somador3_inicial*25)/100,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(($somador3_atualizada*25)/100,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(($somador3_nobimestre*25)/100,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar(($somador3_atebimestre*25)/100,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(((($somador3_atebimestre*100)/$somador3_atualizada)*25)/100,'f'),'TB',0,"R",0);
  $pdf->ln();
  
  $pdf->cell(90,($alt*2),"DESPESAS COM AÇÕES TÍPICAS DE MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(d)",1,0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(e)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'TB',0,"C",0);
  $pdf->ln();

  for ($linha=7; $linha <= 16; $linha++) {
    $pdf->cell(90,$alt,$despesa[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($despesa[$linha]['exercicio']*100)/$despesa[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }
  
  $somador23_inicial     = $despesa[7]["inicial"]     + $despesa[10]["inicial"]    + $despesa[13]["inicial"]    + 
                           $despesa[14]["inicial"]    + $despesa[15]["inicial"]    + $despesa[16]["inicial"];  

  $somador23_atualizada  = $despesa[7]["atualizada"]  + $despesa[10]["atualizada"] + $despesa[13]["atualizada"] + 
                           $despesa[14]["atualizada"] + $despesa[15]["atualizada"] + $despesa[16]["atualizada"];  

  $somador23_nobimestre  = $despesa[7]["bimestre"]    + $despesa[10]["bimestre"]   + $despesa[13]["bimestre"]   + 
                           $despesa[14]["bimestre"]   + $despesa[15]["bimestre"]   + $despesa[16]["bimestre"];  

  $somador23_atebimestre = $despesa[7]["exercicio"]   + $despesa[10]["exercicio"]  + $despesa[13]["exercicio"]  + 
                           $despesa[14]["exercicio"]  + $despesa[15]["exercicio"]  + $despesa[16]["exercicio"];  

  $pdf->setfont('arial','',5);
  $pdf->cell(90,$alt,"23 - TOTAL DAS DESP. COM AÇÕES TÍPICAS DE MANUT. E DESENV. DO ENSINO(17+18+19+20+21+22)","TBR",0,"L",0);
  $pdf->setfont('arial','',6);

  $pdf->cell(20,$alt,db_formatar($somador23_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador23_atebimestre*100)/$somador23_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $pdf->cell(150,($alt*2),"DEDUÇÕES / ADIÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL","TBR",0,"C",0);
  $pdf->cell(40,($alt*2),"VALOR","TB",0,"C",0);
  $pdf->ln();

  $pdf->cell(150,$alt,"24 - RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB = (11)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador11_atebimestre,"f"),"TB",1,"R",0);

  $somador30_valor = $somador11_atebimestre;

// Variaveis do relatorio
  $res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo,c83_variavel","c83_codigo","c83_codrel = 31 and c83_anousu = ".db_getsession("DB_anousu")));
  if ($clconrelinfo->numrows > 0){
    $codigo = 25;
    for($i = 0; $i < $clconrelinfo->numrows; $i++){
      db_fieldsmemory($res_variaveis,$i);

      $pdf->cell(150,$alt,$codigo." - ".$c83_variavel,"TBR",0,"L",0);

      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$periodo_selecionado' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
      if ($clconrelvalor->numrows > 0){
        db_fieldsmemory($res_valor,0);
        $valor = $c83_informacao;  
      } else {
        $valor = 0;
      }

      $pdf->cell(40,$alt,db_formatar($valor,"f"),"TB",0,"R",0);
      $pdf->ln();

      $codigo++;
      $somador30_valor += $valor;
    }
  } else {
    $pdf->cell(150,$alt,"25 - DESPESAS CUSTEADAS COM A COMPLEMENTAÇÃO DO FUNDEB NO EXERCÍCIO","TBR",0,"L",0);
    $pdf->cell(40,$alt,"0,00","TB",1,"R",0);
    $pdf->cell(150,$alt,"26 - RESTOS A PAGAR INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO","TBR",0,"L",0);
    $pdf->cell(40,$alt,"0,00","TB",1,"R",0);
    $pdf->cell(150,$alt,"27 - DESPESAS CUSTEADAS AO SUPERÁVIT FINANCEIRO DO ACRÉSCIMO E DA COMPLEMENTAÇÃO DO FUNDEB DO EXERCÍCIO ANTERIOR","TBR",0,"L",0);
    $pdf->cell(40,$alt,"0,00","TB",1,"R",0);
  }
// Fim das Variaveis
// RESTOS A PAGAR
  $pdf->cell(150,$alt,"28 - CANCELAMENTO, NO EXERCÍCIO, DE RESTOS A PAGAR INSCRITOS COM DISP. FINANC. DE REC. DE IMP. VINC. AO ENSINO = (37)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($m_restos_mde["cancelado"],"f"),"TB",1,"R",0);
// Fim dos RESTOS A PAGAR

  $pdf->cell(150,$alt,"29 - RECEITA DE APLICAÇÃO FINANCEIRA DOS RECURSOS DO FUNDEB ATÉ O ".strtoupper($periodo)." = (38.3)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($m_aplicacao_fundeb["valor"],"f"),"TB",1,"R",0);

  $fluxo[4]["valor"] = $m_aplicacao_fundeb["valor"];

  $somador30_valor += $m_restos_mde["cancelado"] + $m_aplicacao_fundeb["valor"];

  $pdf->cell(150,$alt,"30 - TOTAL DAS DEDUÇÕES / ADIÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL(24+25+26+27+28+29)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador30_valor,"f"),"TB",1,"R",0);

  $somador31_valor = @((($despesa[7]["exercicio"] + $despesa[10]["exercicio"]) - $somador30_valor)/$somador3_atebimestre)*100;

  $pdf->cell(150,$alt,"31 - MÍNIMO DE 25% DAS RECEITAS RESULTANTES DE IMP. NA MANUT. E DESENV. DO ENSINO[(17+18)-(30)/(3)]x100%","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($somador31_valor,"f"),"TB",1,"R",0);
  
  $pdf->cell(90,($alt*2),"OUTRAS DESPESAS CUSTEADAS COM RECURSOS DESTINADOS À MDE",'TBR',0,"C",0);
  $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
  $pdf->cell(20,($alt*2),"ATUALIZADA(d)",1,0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",1,0,"C",0);
  $pdf->cell(20,$alt,"Até o $periodo(e)",1,0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'TB',0,"C",0);
  $pdf->ln();
  
  for ($linha = 17; $linha <= 19; $linha++) {
    $pdf->cell(90,$alt,$despesa[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($despesa[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($despesa[$linha]['exercicio']*100)/$despesa[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }
  
  $somador35_inicial     = $despesa[17]["inicial"]    + $despesa[18]["inicial"]    +
                           $despesa[19]["inicial"];
  $somador35_atualizada  = $despesa[17]["atualizada"] + $despesa[18]["atualizada"] +
                           $despesa[19]["atualizada"];
  $somador35_nobimestre  = $despesa[17]["bimestre"]   + $despesa[18]["bimestre"]   +
                           $despesa[19]["bimestre"];
  $somador35_atebimestre = $despesa[17]["exercicio"]  + $despesa[18]["exercicio"]  +
                           $despesa[19]["exercicio"];
  
  $pdf->setfont('arial','',5);
  $pdf->cell(90,$alt,"35 - TOTAL DAS OUTRAS DESPESAS CUSTEADAS COM REC. DESTINADOS À MDE(32 + 33 + 34)","TBR",0,"L",0);
  $pdf->setfont('arial','',6);

  $pdf->cell(20,$alt,db_formatar($somador35_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador35_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador35_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador35_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador35_atebimestre*100)/$somador35_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $somador36_inicial     = $somador23_inicial     + $somador35_inicial;
  $somador36_atualizada  = $somador23_atualizada  + $somador35_atualizada;
  $somador36_nobimestre  = $somador23_nobimestre  + $somador35_nobimestre;
  $somador36_atebimestre = $somador23_atebimestre + $somador35_atebimestre;

  $pdf->cell(90,$alt,"36 - TOTAL DAS DESPESAS COM ENSINO(23 + 35)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador36_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador36_atebimestre*100)/$somador36_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

  $pdf->setfont('arial','B',6);
  $pdf->cell(190,($alt*2),"OUTRAS INFORMAÇÕES PARA CONTROLE FINANCEIRO",0,1,"C",0);
  
  $pdf->setfont('arial','',6);

  $pdf->cell(90,($alt*2),"RESTOS A PAGAR INSCRITOS COM DISP. FINANC. DE REC. DE IMP. VINC. AO ENSINO","TBR",0,"C",0);
  $pdf->cell(40,($alt*2),"SALDO ATÉ O ".strtoupper($periodo),"TBR",0,"C",0);
  $pdf->cell(60,($alt*2),"CANCELAMENTO EM ".$anousu,"TB",0,"C",0);
  $pdf->ln();

  $pdf->cell(90,$alt,"37 - RESTOS A PAGAR DE DESPESAS COM MANUT. E DESENV. DO ENSINO","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($m_restos_mde["saldo"],"f"),"TBR",0,"R",0);
  $pdf->cell(60,$alt,db_formatar($m_restos_mde["cancelado"],"f"),"TB",0,"R",0);
  $pdf->ln();
 
  $pdf->cell(150,($alt*2),'Continua na página 3',0,1,"L",0);

//  $pdf->cell(190,($alt-2),"",0,1,"C",0);

  $pdf->cell(150,($alt*2),'Continuação da página 2',0,1,"L",0);

  $pdf->cell(150,$alt,"FLUXO FINANCEIRO DOS RECURSOS DO FUNDEB","TBR",0,"C",0);
  $pdf->cell(40,$alt,"VALOR","TB",1,"R",0);

  for($linha=1; $linha <= 5; $linha++){
    $pdf->cell(150,$alt,$fluxo[$linha]['txt'],'R',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($fluxo[$linha]["valor"],"f"),0,0,"R",0);
    $pdf->ln();
  }

// Rodape  
  $pdf->cell(190,$alt,"FONTE: Contabilidade","T",0,"L",0);
// Assinaturas
  $pdf->Ln(30);

  assinaturas(&$pdf,&$classinatura,'LRF');
    
  $pdf->Output();
}
?>