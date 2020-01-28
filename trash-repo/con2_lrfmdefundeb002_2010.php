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
  require_once ("libs/db_utils.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_liborcamento.php");
  include("libs/db_libcontabilidade.php");
  include("libs/db_libtxt.php");
  include("dbforms/db_funcoes.php");
  include_once("model/linhaRelatorioContabil.model.php");
  include_once("model/relatorioContabil.model.php");
  include("classes/db_periodo_classe.php");
  include("classes/db_conrelinfo_classe.php");
  include("classes/db_conrelvalor_classe.php");
  include("classes/db_orcparamrel_classe.php");
  include("classes/db_empresto_classe.php");
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $classinatura    = new cl_assinatura;
  $orcparamrel     = new cl_orcparamrel;
  $clconrelinfo    = new cl_conrelinfo;
  $clconrelvalor   = new cl_conrelvalor;
  $clempresto      = new cl_empresto;
  $oDaoPeriodo     = new cl_periodo;
  $iCodigoPeriodo  = $periodo;
  $anousu = db_getsession("DB_anousu");
  $sSqlPeriodo   = $oDaoPeriodo->sql_query($periodo); 
  $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla; 
  $dt     = data_periodo($anousu,$sSiglaPeriodo);
  $periodo_selecionado = $sSiglaPeriodo;

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
$lUltimoPeriodo = false;
if ($sSiglaPeriodo == "6B" or $sSiglaPeriodo == "2S") {
  $lUltimoPeriodo = true;
}
$sSqlInstituicao  = "select codigo from db_config where prefeitura is true";
$rsPrefeitura     = db_query($sSqlInstituicao);
$iPrefeitura      = db_utils::fieldsMemory($rsPrefeitura, 0)->codigo;
$somador30_valor = 0;
$somador31_valor = 0;
$iCodigoRelatorio = 86;
// PARAMETROS - RECEITAS
for ($linha = 1; $linha <= 50; $linha++) {

  $oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, $linha);
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $aValoresReceita[$linha]["parametro"] = $oLinhaRelatorio->getParametros($anousu);
  $aValoresColunasLinhas = $oLinhaRelatorio->getValoresSomadosColunas($iPrefeitura, $anousu);
  // Previsao Inicial
  $aValoresReceita[$linha]["inicial"]    = 0;
  // Previsao Atualizada
  $aValoresReceita[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $aValoresReceita[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $aValoresReceita[$linha]["exercicio"]  = 0;
  /**
   * somamos os valores das variaveis ao valor inicial da linha
   */
  
  foreach($aValoresColunasLinhas as $oValor) {

    $aValoresReceita[$linha]["inicial"]    += @$oValor->colunas[1]->o117_valor;
    $aValoresReceita[$linha]["atualizada"] += @$oValor->colunas[2]->o117_valor;
    $aValoresReceita[$linha]["bimestre"]   += @$oValor->colunas[3]->o117_valor;
    $aValoresReceita[$linha]["exercicio"]  += @$oValor->colunas[4]->o117_valor;
  }
  //echo "<pre>";
  //print_r($aValoresColunasLinhas);
  //echo "</pre>";
  
}


// PARAMETROS - DESPESAS
$aValoresDespesas[76]["saldo"]     = 0;
$aValoresDespesas[76]["cancelado"] = 0;
for ($linha = 51; $linha <= 76; $linha++) {
   //DOTACAO INICIAL
  if (($linha >= 55 && $linha <= 59) || ($linha >= 68 && $linha <= 71)) {
    
    $aValoresDespesas[$linha]["valor"] = 0;
  } else {
    
    $aValoresDespesas[$linha]["inicial"]       = 0;
    // DOTACAO ATUALIZADA
    $aValoresDespesas[$linha]["atualizada"]    = 0;
    // NO BIMESTRE/SEMESTRE
    $aValoresDespesas[$linha]["bimestre"]      = 0;
    // ATE O BIMESTRE/SEMESTRE
    $aValoresDespesas[$linha]["exercicio"]     = 0;
    //inscritas em RP
    $aValoresDespesas[$linha]["inscritas"]     = 0;
  }
  /**
   * Somamos os valores de edi��o manual
   */
  $oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, $linha);
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $aValoresDespesas[$linha]["descr"] = $oLinhaRelatorio->getDescricaoLinha();
  $aValoresDespesas[$linha]["parametro"] = $oLinhaRelatorio->getParametros($anousu);
  $aValoresColunasLinhas = $oLinhaRelatorio->getValoresSomadosColunas($iPrefeitura, $anousu);
  foreach($aValoresColunasLinhas as $oValor) {
    
    if (($linha >= 55 && $linha <= 59) || ($linha >= 68 && $linha <= 71)) {
      $aValoresDespesas[$linha]["valor"] = $oValor->colunas[1]->o117_valor;
    } else if ($linha == 76) {
       
      $aValoresDespesas[$linha]["saldo"]     = $oValor->colunas[1]->o117_valor;
      $aValoresDespesas[$linha]["cancelado"] = $oValor->colunas[2]->o117_valor;
      
    } else {
      
      $aValoresDespesas[$linha]["inicial"]       = $oValor->colunas[1]->o117_valor;
      $aValoresDespesas[$linha]["atualizada"]    = $oValor->colunas[2]->o117_valor;
      $aValoresDespesas[$linha]["bimestre"]      = $oValor->colunas[3]->o117_valor;
      $aValoresDespesas[$linha]["exercicio"]     = $oValor->colunas[4]->o117_valor;
      /**
       * restos a pagar
       */
      if (isset($oValor->colunas[5])) {
        $aValoresDespesas[$linha]["inscritas"]     = $oValor->colunas[5]->o117_valor;
      }
    }
  }
}


// RESTOS A PAGAR COM ENSINO

$oLinhaRelatorio   = new linhaRelatorioContabil($iCodigoRelatorio, 76);
$oParametroLinha67 = $oLinhaRelatorio->getParametros($anousu);
foreach ($oParametroLinha67->contas as $oConta) {
  $m_restos_mde["estrut"][] = $oConta->estrutural;
}
$m_restos_mde["funcao"]    = $oParametroLinha67->orcamento->funcao->valor;
$m_restos_mde["subfunc"]   = $oParametroLinha67->orcamento->subfuncao->valor;
$m_restos_mde["recurso"]   = $oParametroLinha67->orcamento->recurso->valor;
$m_restos_mde["saldo"]     = $aValoresDespesas[76]["saldo"];
$m_restos_mde["cancelado"] = $aValoresDespesas[76]["cancelado"];

// FLUXO FINANCEIRO - FUNDEB
$oLinhaRelatorio   = new linhaRelatorioContabil($iCodigoRelatorio, 78);
$oParametroLinha69 = $oLinhaRelatorio->getParametros($anousu);
foreach ($oParametroLinha69->contas as $oConta) {
  $m_fluxo_fundeb["estrut"][] = $oConta->estrutural;
}
$m_fluxo_fundeb["valor_inscricao"]        = 0;
$m_fluxo_fundeb["valor_atual"]            = 0;
$m_fluxo_fundeb["valor_credito"]          = 0;
$m_fluxo_fundeb["valor_debito"]           = 0;
$m_fluxo_fundeb["valor_inscricao_fundef"] = 0;
$m_fluxo_fundeb["valor_atual_fundef"]     = 0;
$m_fluxo_fundeb["valor_credito_fundef"]   = 0;
$m_fluxo_fundeb["valor_debito_fundef"]    = 0;
// APLICACAO FINANCEIRA - FUNDEB
$oLinhaRelatorio80 = new linhaRelatorioContabil($iCodigoRelatorio, 80);
$oParametroLinha80 = $oLinhaRelatorio80->getParametros($anousu);
$m_aplicacao_fundeb["fundeb"]["valor"]  = 0;
$m_aplicacao_fundeb["fundef"]["valor"]  = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tela do relatorio

$aReceitas = array();
$aReceitas[1]["label"]  = "1 - RECEITAS DE IMPOSTOS";
/*
 * Dados para informa��o de IPTU
 */
$aReceitas[2]["label"]  = "    1.1 - Receita Resultante do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU";
$aReceitas[3]["label"]  = "          1.1.1-IPTU";
$aReceitas[4]["label"]  = "          1.1.2-Multas, Juros de Mora e Outros Encargos do IPTU";
$aReceitas[5]["label"]  = "          1.1.3-D�vida Ativa do IPTU";
$aReceitas[6]["label"]  = "          1.1.4-Multas, Juros de Mora, Atual. Monet�ria e Outros Enc. da D�vida Ativa do IPTU";
$aReceitas[7]["label"]  = "          1.1.5-(-) Dedu��es da Receita de IPTU";

/*
 * Dados ITBI
 */
$aReceitas[8]["label"]  = "    1.2 - Receita Resultante do Imposto Sobre Transmiss�o Inter Vivos-ITBI ";
$aReceitas[9]["label"]  = "          1.2.1-ITBI";
$aReceitas[10]["label"] = "          1.2.2-Multas, Juros de Mora e Outros Encargos do ITBI";
$aReceitas[11]["label"] = "          1.2.3-D�vida Ativa do ITBI";
$aReceitas[12]["label"] = "          1.2.4-Multas, Juros de Mora, Atual. Monet�ria e Outros Enc. da D�vida Ativa do ITBI";
$aReceitas[13]["label"] = "          1.2.5-(-) Dedu��es da Receita de ITBI";

/*
 *Dados ISS 
 */
$aReceitas[14]["label"] = "    1.3 - Receita Resultante do Imposto sobre Servi�os de Qualquer Natureza - ISS";
$aReceitas[15]["label"] = "          1.3.1-ISS";
$aReceitas[16]["label"] = "          1.3.2-Multas, Juros de Mora e Outros Encargos do ISS";
$aReceitas[17]["label"] = "          1.3.3-D�vida Ativa do ISS";
$aReceitas[18]["label"] = "          1.3.4-Multas, Juros de Mora, Atual. Monet�ria e Outros Enc. da D�vida Ativa do ISS";
$aReceitas[19]["label"] = "          1.3.5-(-) Dedu��es da Receita de ISS";

/**
 * Receitas de IRRF
 */
$aReceitas[20]["label"] = "    1.4 - Receita Resultante do Imposto de Renda Retido na Fonte - IRRF";
$aReceitas[21]["label"] = "          1.4.1-IRRF";
$aReceitas[22]["label"] = "          1.4.2-Multas, Juros de Mora e Outros Encargos do IRRF";
$aReceitas[23]["label"] = "          1.4.3-D�vida Ativa do IRRF";
$aReceitas[24]["label"] = "          1.4.4-Multas, Juros de Mora, Atual. Monet�ria e Outros Enc. da D�vida Ativa do IRRF";
$aReceitas[25]["label"] = "          1.4.5-(-) Dedu��es da Receita de IRRF";

/*
 * ITR
 */

$aReceitas[26]["label"] = "     1.5 - Receita Resultante do Imposto Territorial Rural - ITR (CF, art. 153, �4�, inciso III)";
$aReceitas[27]["label"] = "          1.5.1-ITR";
$aReceitas[28]["label"] = "          1.5.2-Multas, Juros de Mora e Outros Encargos do ITR ";
$aReceitas[29]["label"] = "          1.5.3- D�vida Ativa do ITR";
$aReceitas[30]["label"] = "          1.5.4- Multas, Juros de Mora, Atual. Monet�ria e Outros Enc. da D�vida Ativa do ITR";
$aReceitas[31]["label"] = "          1.5.5- (-) Dedu��es da Receita do ITR";

/*
 * Receitas de Transferencia
 */

$aReceitas[32]["label"] = "2 - RECEITAS DE TRANSFER�NCIAS CONSTITUCIONAIS E LEGAIS";
$aReceitas[33]["label"] = "    2.1-Cota-Parte FPM";
$aReceitas[34]["label"] = "        2.1.1-Parcela referente � CF, art. 159, I, al�nea  b";
$aReceitas[35]["label"] = "        2.1.2-Parcela referente � CF, art. 159, I, al�nea  d";
$aReceitas[36]["label"] = "    2.2-Cota-Parte ICMS";
$aReceitas[37]["label"] = "    2.3-ICMS-Desonera��o - L.C. n�87/1996";
$aReceitas[38]["label"] = "    2.4-Cota-Parte IPI-Exporta��o";
$aReceitas[39]["label"] = "    2.5-Cota-Parte ITR";
$aReceitas[40]["label"] = "    2.6-Cota-Parte IPVA";
$aReceitas[41]["label"] = "    2.7-Cota-Parte IOF-Ouro";
/*
 * Totalizador 3 (Receitas oriundas de impostos)
 */
$aReceitas[42]["label"] = "3- TOTAL DAS RECEITAS DE IMPOSTOS (1+2)";

/*
 * Receita da Aplica��o
 */

$aReceitas[43]["label"] = "4 - RECEITA DA APLICA��O FINANCEIRA DE OUTROS RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO";
/*
 * Transferencias
 */
$aReceitas[44]["label"] = "5 - RECEITA DE TRANSFER�NCIAS DO FNDE";
$aReceitas[45]["label"] = "    5.1-Transfer�ncias do Sal�rio-Educa��o";
$aReceitas[46]["label"] = "    5.2-Outras Transfer�ncias do FNDE";
$aReceitas[47]["label"] = "    5.3-Aplica��o Financeira dos Recursos do FNDE";

$aReceitas[48]["label"] = "6 - RECEITA DE TRANSFER�NCIAS DE CONV�NIOS";
$aReceitas[49]["label"] = "    6.1-Transfer�ncias de Conv�nios";
$aReceitas[50]["label"] = "    6.2- Aplica�ao Financeira dos Recursos de Conv�nios";

$aReceitas[51]["label"] = "7 - RECEITA DE OPERA��ES DE CR�DITO";

$aReceitas[52]["label"] = "8 - OUTRAS RECEITAS PARA FINANCIAMENTO DO ENSINO";
$aReceitas[53]["label"] = "9 - TOTAL DAS RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO (4+5+6+7+8)";

$aReceitas[54]["label"] = "10 - RECEITAS DESTINADAS AO FUNDEB";
$aReceitas[55]["label"] = "     10.1-Cota-Parte FPM Destinada ao FUNDEB -(20% de 2.1.1)";
$aReceitas[56]["label"] = "     10.2-Cota-Parte ICMS Destinada ao FUNDEB - (20% de 2.2)";
$aReceitas[57]["label"] = "     10.3-ICMS-Desonera��o Destinada ao FUNDEB - (20% de 2.3)";
$aReceitas[58]["label"] = "     10.4-Cota-Parte IPI-Exporta��o Destinada ao FUNDEB - (20% de 2.4)";
$aReceitas[59]["label"] = "     10.5-Cota-Parte ITR ou ITR Arrec. Dest. ao FUNDEB - (20% de (1.5 + 2.5))";
$aReceitas[60]["label"] = "     10.6- Cota-Parte IPVA Destinada ao FUNDEB - (20% de 2.6)";

$aReceitas[61]["label"] = "11 - RECEITAS RECEBIDAS DO FUNDEB";
$aReceitas[62]["label"] = "     11.1-Transfer�ncias de Recursos do FUNDEB";
$aReceitas[63]["label"] = "     11.2-Complementa��o da Uni�o ao FUNDEB";
$aReceitas[64]["label"] = "     11.3-Receita de Aplica��o Financeira dos Recursos do FUNDEB";
$aReceitas[65]["label"] = "12 - RESULTADO L�QUIDO DAS TRANSFER�NCIAS DO FUNDEB (11.1 - 10)";

for($linha = 1; $linha <= 65; $linha++){
  
  
  // Previsao Inicial
  $aReceitas[$linha]["inicial"]    = 0;
  // Previsao Atualizada
  $aReceitas[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $aReceitas[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $aReceitas[$linha]["exercicio"]  = 0;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Funcoes para receitas e despesas
// Receitas
$db_filtro = " o70_instit in ({$iPrefeitura})";
$result    = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
//db_criatabela($result); exit;

for ($i = 0; $i < pg_numrows($result); $i++){
  
  $oReceita   =  db_utils::fieldsmemory($result,$i);
  for ($linha = 1; $linha <= 50; $linha++) {
    
    $oParametro      = $aValoresReceita[$linha]["parametro"];
    foreach ($oParametro->contas as $oEstrutural) {
      
      $oVerificacao    = $oLinhaRelatorio->match($oEstrutural ,$oParametro->orcamento,$oReceita, 1);
      
      if ($oVerificacao->match) { 

        if ($linha < 49) {
          
          if ($oVerificacao->exclusao) {     

            $aValoresReceita[$linha]["inicial"]    -= ($oReceita->saldo_inicial);
            $aValoresReceita[$linha]["atualizada"] -= ($oReceita->saldo_inicial_prevadic);
            $aValoresReceita[$linha]["bimestre"]   -= ($oReceita->saldo_arrecadado);
            $aValoresReceita[$linha]["exercicio"]  -= ($oReceita->saldo_arrecadado_acumulado);
          } else {
            
            $aValoresReceita[$linha]["inicial"]    += ($oReceita->saldo_inicial);
            $aValoresReceita[$linha]["atualizada"] += ($oReceita->saldo_inicial_prevadic);
            $aValoresReceita[$linha]["bimestre"]   += ($oReceita->saldo_arrecadado);
            $aValoresReceita[$linha]["exercicio"]  += ($oReceita->saldo_arrecadado_acumulado);
          }
               
        } else {
              // echo "aqui $linha<br>";
          if ($oVerificacao->exclusao) {

            $aValoresReceita[$linha]["inicial"]    -= abs($oReceita->saldo_inicial);
            $aValoresReceita[$linha]["atualizada"] -= abs($oReceita->saldo_inicial_prevadic);
            $aValoresReceita[$linha]["bimestre"]   -= abs($oReceita->saldo_arrecadado);
            $aValoresReceita[$linha]["exercicio"]  -= abs($oReceita->saldo_arrecadado_acumulado);       
          } else {
            
            $aValoresReceita[$linha]["inicial"]    += abs($oReceita->saldo_inicial);
            $aValoresReceita[$linha]["atualizada"] += abs($oReceita->saldo_inicial_prevadic);
            $aValoresReceita[$linha]["bimestre"]   += abs($oReceita->saldo_arrecadado);
            $aValoresReceita[$linha]["exercicio"]  += abs($oReceita->saldo_arrecadado_acumulado);
          }
        }
      }
      unset($oVerificacao);
    }
    
    unset($oParametro);
    unset($oVerificacao);
    //exit;
  }
  foreach ($oParametroLinha80->contas as $oEstrutural) {
      
    $oVerificacao    = $oLinhaRelatorio80->match($oEstrutural ,$oParametroLinha80->orcamento,$oReceita, 1);
    if ($oVerificacao->match) {
        
      if ($oVerificacao->exclusao) {
         $oReceita->saldo_arrecadado_acumulado *= -1;  
      }
      if ($oReceita->o70_codigo == 31 || $oReceita->o70_codigo == 8031) {
        $m_aplicacao_fundeb["fundeb"]["valor"] += $oReceita->saldo_arrecadado_acumulado;
      } else if ($oReceita->o70_codigo == 30 || $oReceita->o70_codigo == 8030) {
        $m_aplicacao_fundeb["fundef"]["valor"] += $oReceita->saldo_arrecadado_acumulado;
      }
    }
  }
}

for ($col = 1; $col <= 4; $col++){
  $pcol =array(1=>"inicial",2=>"atualizada",3=>"bimestre",4=>"exercicio");

  /**
   * IPTU
   */
  // Imposto IPTU                             
  $aReceitas[3][$pcol[$col]] = $aValoresReceita[1][$pcol[$col]];
  // Outros encargos IPTU
  $aReceitas[4][$pcol[$col]] = $aValoresReceita[2][$pcol[$col]];
  // Divida Ativa IPTU
  $aReceitas[5][$pcol[$col]] = $aValoresReceita[3][$pcol[$col]];
  // Outros encargos Divida Ativa IPTU
  $aReceitas[6][$pcol[$col]] = $aValoresReceita[4][$pcol[$col]];
  //Dedu��es dae IPTU
  $aReceitas[7][$pcol[$col]] = $aValoresReceita[5][$pcol[$col]];
  // 1.1 - Receita Resultante de IPTU
  $aReceitas[2][$pcol[$col]] = $aReceitas[3][$pcol[$col]] + $aReceitas[4][$pcol[$col]]+
                               $aReceitas[5][$pcol[$col]] + $aReceitas[6][$pcol[$col]]+
                               $aReceitas[7][$pcol[$col]];

                              
   /*
    * ITBI
    */                            
  // Imposto ITBI
  $aReceitas[9][$pcol[$col]]  = $aValoresReceita[6][$pcol[$col]];
  // Outros encargos ITBI
  $aReceitas[10][$pcol[$col]] = $aValoresReceita[7][$pcol[$col]];
  // Divida Ativa ITBI
  $aReceitas[11][$pcol[$col]] = $aValoresReceita[8][$pcol[$col]];
  // Outros encargos Divida Ativa ITBI
  $aReceitas[12][$pcol[$col]] = $aValoresReceita[9][$pcol[$col]];
  //Deducoes 
  $aReceitas[13][$pcol[$col]] = $aValoresReceita[10][$pcol[$col]];
  // 1.2 - Receita Resultante de ITBI
  $aReceitas[8][$pcol[$col]] = $aReceitas[9] [$pcol[$col]] + $aReceitas[10][$pcol[$col]]+
                               $aReceitas[11][$pcol[$col]] + $aReceitas[12][$pcol[$col]]+
                               $aReceitas[13][$pcol[$col]];
  /*
   * ISS
   */
  // Imposto ISS  
  $aReceitas[15][$pcol[$col]] = $aValoresReceita[11][$pcol[$col]];
  // Outros encargos ISS
  $aReceitas[16][$pcol[$col]] = $aValoresReceita[12][$pcol[$col]];
  // Divida Ativa ISS
  $aReceitas[17][$pcol[$col]] = $aValoresReceita[13][$pcol[$col]];
  // Outros encargos Divida Ativa ISS
  $aReceitas[18][$pcol[$col]] = $aValoresReceita[14][$pcol[$col]];
  //Deducoes
  $aReceitas[19][$pcol[$col]] = $aValoresReceita[15][$pcol[$col]];
  
  // 1.3 - Receita Resultante de ISS
  $aReceitas[14][$pcol[$col]] = $aReceitas[15][$pcol[$col]] + $aReceitas[16][$pcol[$col]]+
                                $aReceitas[17][$pcol[$col]] + $aReceitas[18][$pcol[$col]]+
                                $aReceitas[19][$pcol[$col]];
 
  // Imposto IRRF
  $aReceitas[21][$pcol[$col]] = $aValoresReceita[16][$pcol[$col]];
  // Outros encargos IRRF
  $aReceitas[22][$pcol[$col]] = $aValoresReceita[17][$pcol[$col]];
  // Divida Ativa IRRF
  $aReceitas[23][$pcol[$col]] = $aValoresReceita[18][$pcol[$col]];
  // Outros encargos Divida Ativa IRRF
  $aReceitas[24][$pcol[$col]] = $aValoresReceita[19][$pcol[$col]];
  //Dedu��es
  $aReceitas[25][$pcol[$col]] = $aValoresReceita[20][$pcol[$col]];

  // 1.4 - Receita Resultante de IRRF
  $aReceitas[20][$pcol[$col]] = $aReceitas[21][$pcol[$col]] + $aReceitas[22][$pcol[$col]]+
                                $aReceitas[23][$pcol[$col]] + $aReceitas[24][$pcol[$col]]+
                                $aReceitas[25][$pcol[$col]];

// Imposto ITR
  $aReceitas[27][$pcol[$col]] = $aValoresReceita[21][$pcol[$col]];
  // Outros encargos ITR
  $aReceitas[28][$pcol[$col]] = $aValoresReceita[22][$pcol[$col]];
  // Divida Ativa ITR
  $aReceitas[29][$pcol[$col]] = $aValoresReceita[23][$pcol[$col]];
  // Outros encargos Divida Ativa ITR
  $aReceitas[30][$pcol[$col]] = $aValoresReceita[24][$pcol[$col]];
  //Dedu��es
  $aReceitas[31][$pcol[$col]] = $aValoresReceita[25][$pcol[$col]];

  // 1.4 - Receita Resultante de ITR
  $aReceitas[26][$pcol[$col]] = $aReceitas[27][$pcol[$col]] + $aReceitas[28][$pcol[$col]]+
                                $aReceitas[29][$pcol[$col]] + $aReceitas[30][$pcol[$col]]+
                                $aReceitas[31][$pcol[$col]];
                                
                               
  // 1 - RECEITAS DE IMPOSTOS
  $aReceitas[1][$pcol[$col]] = $aReceitas[2][$pcol[$col]]  + $aReceitas[8] [$pcol[$col]] +
                               $aReceitas[14][$pcol[$col]] + $aReceitas[20][$pcol[$col]] +
                               $aReceitas[26][$pcol[$col]];
                               

  // 2.1.1 FPM         

  $aReceitas[34][$pcol[$col]] = $aValoresReceita[26][$pcol[$col]];
  // 2.1.2 ICMS                            
  $aReceitas[35][$pcol[$col]] = $aValoresReceita[27][$pcol[$col]];
  //2.1
  $aReceitas[33][$pcol[$col]] = $aReceitas[34][$pcol[$col]]+$aReceitas[35][$pcol[$col]]; 
  // 2.3 Desoneracao ICMS                            
  $aReceitas[36][$pcol[$col]] = $aValoresReceita[28][$pcol[$col]];
  // 2.4 IPI                            
  $aReceitas[37][$pcol[$col]] = $aValoresReceita[29][$pcol[$col]];
  // 2.5 ITR                            
  $aReceitas[38][$pcol[$col]] = $aValoresReceita[30][$pcol[$col]];
  // 2.6 IPVA                           
  $aReceitas[39][$pcol[$col]] = $aValoresReceita[31][$pcol[$col]];
  // 2.7 IOF                            
  $aReceitas[40][$pcol[$col]] = $aValoresReceita[32][$pcol[$col]];
  $aReceitas[41][$pcol[$col]] = $aValoresReceita[33][$pcol[$col]];

  // 2 - RECEITA DE TRANSFERENCIAS
  $aReceitas[32][$pcol[$col]] = $aReceitas[33][$pcol[$col]] + $aReceitas[36][$pcol[$col]] + $aReceitas[37][$pcol[$col]] + 
                                $aReceitas[38][$pcol[$col]] + $aReceitas[39][$pcol[$col]] + $aReceitas[40][$pcol[$col]] +
                                $aReceitas[41][$pcol[$col]];
                                
  //total Dos Impostos
   $aReceitas[42][$pcol[$col]] = $aReceitas[1][$pcol[$col]]+$aReceitas[32][$pcol[$col]];
   
                 
  // OUTRAS RECEITAS DESTINADAS AO ENSINO
  $aReceitas[43][$pcol[$col]] = $aValoresReceita[34][$pcol[$col]];
  // 5.1 
  $aReceitas[45][$pcol[$col]] = $aValoresReceita[35][$pcol[$col]];
  
  // 5.2 
  $aReceitas[46][$pcol[$col]] = $aValoresReceita[36][$pcol[$col]];
  // 5.3 
  $aReceitas[47][$pcol[$col]] = $aValoresReceita[37][$pcol[$col]];
  /*
   * Totalizardor do Grupo 5
   */
  $aReceitas[44][$pcol[$col]] = $aReceitas[47][$pcol[$col]]+$aReceitas[45][$pcol[$col]]+$aReceitas[46][$pcol[$col]];
  
  /*
   * Grupo 6
   */
   //6.1 
   $aReceitas[49][$pcol[$col]] = $aValoresReceita[38][$pcol[$col]];
   // 6.2 
   $aReceitas[50][$pcol[$col]] = $aValoresReceita[39][$pcol[$col]];
  /*
   * Totalizardor do Grupo 6
   */
  $aReceitas[48][$pcol[$col]] = $aReceitas[49][$pcol[$col]]+$aReceitas[50][$pcol[$col]];
  
  // 7 - Opera��es de Credito
  $aReceitas[51][$pcol[$col]] = $aValoresReceita[40][$pcol[$col]];
  // 8 - Outras Receitas
  $aReceitas[52][$pcol[$col]] = $aValoresReceita[41][$pcol[$col]];
  // 9 - Totalizador
  $aReceitas[53][$pcol[$col]] = $aReceitas[43][$pcol[$col]]+$aReceitas[44][$pcol[$col]]+$aReceitas[48][$pcol[$col]]+
                                $aReceitas[51][$pcol[$col]]+$aReceitas[52][$pcol[$col]]; 
  
  // 11.2 - Receitas Destinadas ao FUNDEB
  $aReceitas[55][$pcol[$col]] = $aValoresReceita[42][$pcol[$col]];
  // 11.2
  $aReceitas[56][$pcol[$col]] = $aValoresReceita[43][$pcol[$col]];
  // 11.3
  $aReceitas[57][$pcol[$col]] = $aValoresReceita[44][$pcol[$col]];
  // 11.4
  $aReceitas[58][$pcol[$col]] = $aValoresReceita[45][$pcol[$col]];
  // 11.5
  $aReceitas[59][$pcol[$col]] = $aValoresReceita[46][$pcol[$col]];
  // 11.6
  $aReceitas[60][$pcol[$col]] = $aValoresReceita[47][$pcol[$col]];
  //Total
  $aReceitas[54][$pcol[$col]] = $aReceitas[55][$pcol[$col]]+$aReceitas[56][$pcol[$col]]+$aReceitas[57][$pcol[$col]]+
                                $aReceitas[58][$pcol[$col]]+$aReceitas[59][$pcol[$col]]+$aReceitas[60][$pcol[$col]]; 
                                
  // 11.2 - Receitas Destinadas ao FUNDEB
  // 11.1
  $aReceitas[62][$pcol[$col]] = $aValoresReceita[48][$pcol[$col]];
  // 11.2
  $aReceitas[63][$pcol[$col]] = $aValoresReceita[49][$pcol[$col]];
  // 11.3
  $aReceitas[64][$pcol[$col]] = $aValoresReceita[50][$pcol[$col]];
  $aReceitas[61][$pcol[$col]] = $aReceitas[62][$pcol[$col]]+$aReceitas[63][$pcol[$col]]+$aReceitas[64][$pcol[$col]];
  //Totalizador
  $aReceitas[65][$pcol[$col]] = $aReceitas[62][$pcol[$col]] - $aReceitas[54][$pcol[$col]];
}

// DESPESAS
$aDespesas = array();

$aDespesas[1]["label"]  = "13 - PAGAMENTO DOS PROFISSIONAIS DO MAGIST�RIO";
$aDespesas[2]["label"]  = "     13.1-Com Educa��o Infantil";
$aDespesas[3]["label"]  = "     13.2-Com Ensino Fundamental";
$aDespesas[4]["label"]  = "14 - OUTRAS DESPESAS";
$aDespesas[5]["label"]  = "     14.1-Com Educa��o Infantil";
$aDespesas[6]["label"]  = "     14.2-Com Ensino Fundamental";
$aDespesas[7]["label"]  = "23 - EDUCA��O INFANTIL";
$aDespesas[8]["label"]  = "     23.1 - Despesas Custeadas com Recursos do FUNDEB";
$aDespesas[9]["label"] = "     23.2 - Despesas Custeadas com Outros Recursos de Impostos";
$aDespesas[10]["label"] = "24 - ENSINO FUNDAMENTAL";
$aDespesas[11]["label"] = "     24.1 - Despesas Custeadas com Recursos do FUNDEB";
$aDespesas[12]["label"] = "     24.2 - Despesas Custeadas com Outros Recursos de Impostos";
$aDespesas[13]["label"] = "25 - ENSINO M�DIO";
$aDespesas[14]["label"] = "26 - ENSINO SUPERIOR";
$aDespesas[15]["label"] = "27 - ENSINO PROFISSIONAL N�O INTEGRADO AO ENSINO REGULAR";
$aDespesas[16]["label"] = "28 - OUTRAS";
$aDespesas[17]["label"] = "40 - DESP. CUST. COM A APLICA��O FINANC. DE OUTROS RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO";
$aDespesas[18]["label"] = "41 - DESPESAS CUSTEADAS COM A CONTRIBUI��O SOCIAL DO SAL�RIO-EDUCA��O";
$aDespesas[19]["label"] = "42 - DESPESAS CUSTEADAS COM OPERA��ES DE CR�DITO";
$aDespesas[20]["label"] = "43 - DESPESAS CUSTEADAS COM OUTRAS RECEITAS PARA FINANC. DO ENSINO";

for($linha = 1; $linha <= 20; $linha++){
  // Dotacao Inicial
  $aDespesas[$linha]["inicial"]    = 0;
  // Dotacao Atualizada
  $aDespesas[$linha]["atualizada"] = 0;
  // No Bimestre/Semestre
  $aDespesas[$linha]["bimestre"]   = 0;
  // Ate o Bimestre/Semestre
  $aDespesas[$linha]["exercicio"]  = 0;
  //inscritas
  $aDespesas[$linha]["inscritas"]  = 0;
}

$sele_work = "o58_instit in ({$iPrefeitura})";
$result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);

for ($i = 0; $i < pg_numrows($result_despesa); $i++) {
  
  $oDespesa = db_utils::fieldsmemory($result_despesa, $i);
  
  for ($linha = 51; $linha <= 76; $linha++) {
    
    if (($linha >= 55 && $linha <= 59) || ($linha >= 68 && $linha <= 71)) {
      continue;
    }
    $oParametro      = $aValoresDespesas[$linha]["parametro"];
    foreach ($oParametro->contas as $oConta) {
      
      $oVerificacao    = $oLinhaRelatorio->match($oConta,$oParametro->orcamento,$oDespesa, 2);
      if ($oVerificacao->match) {
        
        if ($oVerificacao->exclusao) {
          
          $aValoresDespesas[$linha]["inicial"]    -= $oDespesa->dot_ini;
          $aValoresDespesas[$linha]["atualizada"] -= $oDespesa->dot_ini + 
                                                     ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
          $aValoresDespesas[$linha]["bimestre"]   -= $oDespesa->liquidado;
          $aValoresDespesas[$linha]["exercicio"]  -= $oDespesa->liquidado_acumulado;
          $aValoresDespesas[$linha]["inscritas"]  -= $oDespesa->empenhado_acumulado-$oDespesa->anulado_acumulado-
                                                     $oDespesa->liquidado_acumulado;
        } else {
          
          $aValoresDespesas[$linha]["inicial"]    += $oDespesa->dot_ini;
          $aValoresDespesas[$linha]["atualizada"] += $oDespesa->dot_ini + 
                                                     ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
          $aValoresDespesas[$linha]["bimestre"]   += $oDespesa->liquidado;
          $aValoresDespesas[$linha]["exercicio"]  += $oDespesa->liquidado_acumulado;
          $aValoresDespesas[$linha]["inscritas"]  += $oDespesa->empenhado_acumulado-$oDespesa->anulado_acumulado-
                                                     $oDespesa->liquidado_acumulado;
          
        }
      }
    }
  }
}
//echo "<pre>";
//print_r($aValoresDespesas);
//echo "</pre>"; 
//exit;
for ($col = 1; $col <= 5; $col++){

  $pcol = array(1=>"inicial",2=>"atualizada",3=>"bimestre",4=>"exercicio",5=>"inscritas");
  
  // 12.1 PGTO PROFISSIONAIS - Educacao Infantil
  $aDespesas[2][$pcol[$col]] += $aValoresDespesas[51][$pcol[$col]];
  // 12.2 PGTO PROFISSIONAIS - Ensino Fundamental
  $aDespesas[3][$pcol[$col]] += $aValoresDespesas[52][$pcol[$col]];
  // 12 - PAGAMENTO PROFISSIONAIS DO MAGISTERIO
  $aDespesas[1][$pcol[$col]] = $aDespesas[2][$pcol[$col]] + $aDespesas[3][$pcol[$col]];

  // 13.1 OUTRAS DESPESAS - Educacao Infantil
  $aDespesas[5][$pcol[$col]] += $aValoresDespesas[53][$pcol[$col]];
  // 13.2 OUTRAS DESPESAS - Ensino Fundamental
  $aDespesas[6][$pcol[$col]] += $aValoresDespesas[54][$pcol[$col]];

  // 13 - OUTRAS DESPESAS
  $aDespesas[4][$pcol[$col]] = $aDespesas[5][$pcol[$col]] + $aDespesas[6][$pcol[$col]];

  // 17.1 Despesas com Recursos do FUNDEB
  $aDespesas[8][$pcol[$col]] += $aValoresDespesas[60][$pcol[$col]];
  // 17.2 Despesas com outros Recursos de Impostos
  $aDespesas[9][$pcol[$col]] += $aValoresDespesas[61][$pcol[$col]];

  // 17 EDUCACAO INFANTIL
  $aDespesas[7][$pcol[$col]] = $aDespesas[8][$pcol[$col]] + $aDespesas[9][$pcol[$col]];

  // 18.1 Despesas com Recursos do FUNDEB
  $aDespesas[11][$pcol[$col]] += $aValoresDespesas[62][$pcol[$col]];
  // 18.2 Despesas com outros Recursos de Impostos
  $aDespesas[12][$pcol[$col]] += $aValoresDespesas[63][$pcol[$col]];

  // 18 ENSINO FUNDAMENTAL
  $aDespesas[10][$pcol[$col]] = $aDespesas[11][$pcol[$col]] + $aDespesas[12][$pcol[$col]];
  
  // 19 ENSINO MEDIO
  $aDespesas[13][$pcol[$col]] += $aValoresDespesas[64][$pcol[$col]];
  // 20 ENSINO SUPERIOR
  $aDespesas[14][$pcol[$col]] += $aValoresDespesas[65][$pcol[$col]];
  // 21 ENSINO PROFISSIONAL
  $aDespesas[15][$pcol[$col]] += $aValoresDespesas[66][$pcol[$col]];
  // 22 OUTRAS
  $aDespesas[16][$pcol[$col]] += $aValoresDespesas[67][$pcol[$col]];

  // 32 CONTRIBUICAO SOCIAL SALARIO-EDUCACAO
  $aDespesas[17][$pcol[$col]] = $aValoresDespesas[72][$pcol[$col]];
  // 33 RECURSOS DE OPERACOES DE CREDITO
  $aDespesas[18][$pcol[$col]] = $aValoresDespesas[73][$pcol[$col]];
  // 34 OUTROS RECURSOS DESTINADOS A EDUCACAO
  $aDespesas[19][$pcol[$col]] = $aValoresDespesas[74][$pcol[$col]];
  $aDespesas[20][$pcol[$col]] = $aValoresDespesas[75][$pcol[$col]];
}
$v_funcao    = "0";
$v_subfuncao = "0";
$v_codigo    = "0";
$sp          = "";
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

$sele_work         = " e60_instit in ({$iPrefeitura})";
$sele_work1        = " and e91_recurso in ($v_codigo)";
$sql_where_externo = " where $sele_work ";
$sql_order         = " order by e91_recurso, e91_numemp ";

$dt_ini2           = $anousu."-01-01";
$sqlperiodo = $clempresto->sql_rp($anousu, $sele_work, $dt_ini2, $dt_fin, $sele_work1, $sql_where_externo, $sql_order);
$sqlperiodo = " select e91_recurso,o15_descr,e60_anousu,sum(vlranu) as vlranu, sum(vlrliq) as vlrliq, sum(vlrpag) as vlrpag,
                       sum( e91_vlremp) as e91_vlremp,sum(e91_vlranu) as e91_vlranu,sum(e91_vlrliq) as e91_vlrliq,sum(e91_vlrpag) as e91_vlrpag
                from ($sqlperiodo) as x
                group by e91_recurso,o15_descr,e60_anousu
	          		order by e91_recurso,e60_anousu";
$result_restos_mde1  = db_query($sqlperiodo);
$numrows_restos_mde1 = @pg_numrows($result_restos_mde1);

$cancelado = $m_restos_mde["cancelado"];
$saldo     = $m_restos_mde["saldo"];
for($i = 0; $i < pg_numrows($result_restos_mde1); $i++){
  db_fieldsmemory($result_restos_mde1,$i);

  $saldo += (($e91_vlremp-$e91_vlranu-$vlranu)-($e91_vlrpag+$vlrpag));
}

//echo $sqlperiodo; exit;                

$db_filtro = " in ({$iPrefeitura})";
$sWhere = "";
$sAnd   = "";
if ($v_codigo != "0") {
  $sWhere .= $sAnd." o58_codigo     in (".$v_codigo.") ";
}
if ($v_funcao != "0") {
  
  if ($sWhere != "") {
    $sAnd = " and ";
  }
  $sWhere .= $sAnd." o58_funcao    in (".$v_funcao.") ";
  $sAnd    = "";
}
if ($v_subfuncao != "0") {
  
  if ($sWhere != "") {
    $sAnd = " and ";
  }
  $sWhere .= $sAnd." o58_subfuncao    in (".$v_subfuncao.") ";
  $sAnd    = "";
}
$sWhereDoc = "";
if ($sWhere != "") {
   $sWhereDoc .= " and ";
}
$sWhereDoc .= " c53_coddoc = 32";
$result_restos_mde2 = db_rpsaldo($anousu,
                                 $db_filtro,
                                 $dt_ini2,
                                 $dt_fin,
                                 $sWhere,
                                 $sWhereDoc
                                 );

$sWhere = "";                                 
//db_criatabela($result_restos_mde); exit;
for($i = 0; $i < pg_numrows($result_restos_mde2); $i++){
  db_fieldsmemory($result_restos_mde2,$i);

  $cancelado += $vlranu;
}

$m_restos_mde["saldo"]     = $saldo;     
$m_restos_mde["cancelado"] = $cancelado;

//db_criatabela($result_restos_mde); exit;

// FIM RESTOS A PAGAR
// FLUXO FINANCEIRO

      
// FIM FLUXO FINANCEIRO

$fluxo = array();

$fluxo[1]["label"] = "47 - SALDO FINANCEIRO EM 31 DE DEZEMBRO DE ".($anousu-1);
$fluxo[2]["label"] = "48 - (+) INGRESSO DE RECURSOS AT� O ".strtoupper($periodo);
$fluxo[3]["label"] = "49 - (-) PAGAMENTOS EFETUADOS AT� O ".strtoupper($periodo);
$fluxo[4]["label"] = "50 - (+) RECEITA DE APLICA��O FINANCEIRA DOS RECURSOS AT� O ".strtoupper($periodo);
$fluxo[5]["label"] = "51 - (=) SALDO FINANCEIRO NO EXERC�CIO ATUAL";
 
$fluxo[1]["campo"] = "saldo_anterior"; 
$fluxo[2]["campo"] = "saldo_anterior_debito"; 
$fluxo[3]["campo"] = "saldo_anterior_credito"; 
$fluxo[5]["campo"] = "saldo_final"; 

for($linha = 1; $linha <= 5; $linha++){
  
  $fluxo[$linha]["valor"]        = 0;
  $fluxo[$linha]["valor_fundef"] = 0;
  $oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, ($linha+76));
  $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  $fluxo[$linha]["estrutural"] = $oLinhaRelatorio;
  $fluxo[$linha]["parametro"]  = $oLinhaRelatorio->getParametros($anousu);
  $aValoresColunasLinhas = $oLinhaRelatorio->getValoresSomadosColunas($iPrefeitura, $anousu);
  foreach ($aValoresColunasLinhas as $oValorColuna) {
  
    $fluxo[$linha]["valor"]        += $oValorColuna->colunas[1]->o117_valor;
    $fluxo[$linha]["valor_fundef"] += $oValorColuna->colunas[2]->o117_valor;
    
  }
}

$m_fluxo_fundeb["valor_inscricao"] = 0;
$m_fluxo_fundeb["valor_aplicacao"] = 0;
$dt_ini2        = $anousu."-01-01";
$db_filtro      = " c61_instit in ({$iPrefeitura}) ";
$result_bal_ver = db_planocontassaldo_matriz($anousu,$dt_ini2,$dt_fin,false,$db_filtro);
for ($i = 0; $i < pg_numrows($result_bal_ver); $i++) {
     
  $oResultado = db_utils::fieldsmemory($result_bal_ver,$i);
  for ($iLinha =1; $iLinha <= 5; $iLinha++) {

    $oParametroLinha = $fluxo[$iLinha]["parametro"];
    foreach ($oParametroLinha->contas as $oEstrutural) {
        
      $oVerificacao  = $fluxo[$iLinha]["estrutural"]->match($oEstrutural ,$oParametroLinha->orcamento,$oResultado, 3);
      if ($oVerificacao->match) {  
        
        if ($oVerificacao->exclusao) {
          
          $oResultado->saldo_anterior         *= -1;
          $oResultado->saldo_final            *= -1;
          $oResultado->saldo_anterior_credito *= -1;
          $oResultado->saldo_anterior_debito  *= -1;
        }
        if ($oResultado->c61_codigo == 31 || $oResultado->c61_codigo == 8031) {

          if (isset($fluxo[$iLinha]["campo"])) {
            $fluxo[$iLinha]["valor"] += $oResultado->{$fluxo[$iLinha]["campo"]};
          } 
        } else if ($oResultado->c61_codigo == 30 || $oResultado->c61_codigo == 8030) {
        
          if (isset($fluxo[$iLinha]["campo"])) {
            $fluxo[$iLinha]["valor_fundef"]  += $oResultado->{$fluxo[$iLinha]["campo"]};
          }
        }
      }
    }
  }
}
$fluxo[2]["valor"] -= $m_aplicacao_fundeb["fundeb"]["valor"];
$fluxo[4]["valor"] += $m_aplicacao_fundeb["fundeb"]["valor"];

$fluxo[2]["valor_fundef"] -= $m_aplicacao_fundeb["fundef"]["valor"];
$fluxo[4]["valor_fundef"] += $m_aplicacao_fundeb["fundef"]["valor"];
/**
 * Valores das variaveis do relatorio
 */

$nValorLinha16 = $aValoresDespesas[55]["valor"];
$nValorLinha17 = $aValoresDespesas[56]["valor"];
$nValorLinha20 = $aValoresDespesas[57]["valor"];
$nValorLinha21 = $aValoresDespesas[58]["valor"];

$nValorLinha31 = $aValoresDespesas[68]["valor"];
$nValorLinha32 = 0;
$nValorLinha33 = $aValoresDespesas[69]["valor"];
$nValorLinha34 = $aValoresDespesas[70]["valor"];
$nValorLinha35 = $aValoresDespesas[71]["valor"];

$nValorLinha37 = $aReceitas[65]["exercicio"]+ $nValorLinha31 + $nValorLinha32+$nValorLinha33+
                 $nValorLinha34             +$nValorLinha35  +$m_restos_mde["cancelado"]
                 +$fluxo[4]["valor"]; 

$nValorLinha38 = ($aDespesas[7]["exercicio"]+$aDespesas[10]["exercicio"])-$nValorLinha37;

if ($lUltimoPeriodo) {
  $nValorLinha38 += ( $aDespesas[7]["inscritas"] + $aDespesas[10]["inscritas"] );
}


@$nValorLinha39 = ($nValorLinha38/$aReceitas[42]["exercicio"])*100;

$nValorMinimo60Fundeb    = @(($aDespesas[1]["exercicio"]-($nValorLinha16+$nValorLinha17))/($aReceitas[61]["exercicio"])*100);
$nValorMinimo60Fundeb    += $aValoresDespesas[59]["valor"];

$nImpostosTransferencias      = @(($aReceitas[42]["exercicio"])*25)/100;
$nImpostosTransferenciasPrev  = @(($aReceitas[42]["inicial"])*25)/100;
$nImpostosTransferenciasAtual = @(($aReceitas[42]["atualizada"])*25)/100;
$nImpostosTransferenciasBim   = @(($aReceitas[42]["bimestre"])*25)/100;

$somador23_inicial     = $aDespesas[7]["inicial"]     + $aDespesas[10]["inicial"]    + $aDespesas[13]["inicial"]    +
                         $aDespesas[14]["inicial"]    + $aDespesas[15]["inicial"]    + $aDespesas[16]["inicial"];

$somador23_atualizada   = $aDespesas[7]["atualizada"]  + $aDespesas[10]["atualizada"] + $aDespesas[13]["atualizada"] +
                          $aDespesas[14]["atualizada"] + $aDespesas[15]["atualizada"] + $aDespesas[16]["atualizada"];

$somador23_nobimestre   = $aDespesas[7]["bimestre"]    + $aDespesas[10]["bimestre"]   + $aDespesas[13]["bimestre"]   +
                          $aDespesas[14]["bimestre"]   + $aDespesas[15]["bimestre"]   + $aDespesas[16]["bimestre"]; 

$somador23_atebimestre  = $aDespesas[7]["exercicio"]   + $aDespesas[10]["exercicio"]  + $aDespesas[13]["exercicio"]  +
                            $aDespesas[14]["exercicio"]  + $aDespesas[15]["exercicio"]  + $aDespesas[16]["exercicio"];

$somador23_inscritas    = $aDespesas[7]["inscritas"]   + $aDespesas[10]["inscritas"]  + $aDespesas[13]["inscritas"]  +
                          $aDespesas[14]["inscritas"]  + $aDespesas[15]["inscritas"]  + $aDespesas[16]["inscritas"];

$soma23_InscrBim        = $somador23_atebimestre+$somador23_inscritas;

$somador35_inicial     = $aDespesas[17]["inicial"] + $aDespesas[18]["inicial"]    +
                           $aDespesas[19]["inicial"] + $aDespesas[20]["inicial"];

$somador35_atualizada  = $aDespesas[17]["atualizada"] + $aDespesas[18]["atualizada"] +
                           $aDespesas[19]["atualizada"] + $aDespesas[20]["atualizada"];

$somador35_nobimestre  = $aDespesas[17]["bimestre"]   + $aDespesas[18]["bimestre"]   +
                           $aDespesas[19]["bimestre"]   + $aDespesas[20]["bimestre"];

$somador35_atebimestre = $aDespesas[17]["exercicio"]  + $aDespesas[18]["exercicio"]  +
                           $aDespesas[19]["exercicio"]  + $aDespesas[20]["exercicio"];


$nTotalDespesasMde["inicial"]    = $somador23_inicial+$somador35_inicial;
$nTotalDespesasMde["atualizada"] = $somador23_atualizada+$somador35_atualizada;
$nTotalDespesasMde["bimestre"]   = $somador23_nobimestre+$somador35_nobimestre;
$nTotalDespesasMde["exercicio"]  = $somador23_atebimestre+$somador35_atebimestre;
if ($lUltimoPeriodo){
  
  $somador35_inscritas   = $aDespesas[17]["inscritas"]  + $aDespesas[18]["inscritas"]  +
                           $aDespesas[19]["inscritas"]  + $aDespesas[20]["inscritas"];
  $soma35_InscrBim       = $somador35_atebimestre + $somador35_inscritas;
  $nTotalDespesasMde["inscrita"]   = $soma23_InscrBim+$soma35_InscrBim;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!isset($arqinclude)){
  $resultinst = db_query("select munic from db_config where codigo in ({$iPrefeitura})");
  db_fieldsmemory($resultinst,0);

  $descr_inst = strtoupper($munic);
    
  $head1 = "MUNIC�PIO DE ".$descr_inst;
  $head2 = "RELAT�RIO RESUMIDO DA EXECU��O OR�AMENT�RIA";
  $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS COM MANUTEN��O E DESENVOLVIMENTO DO ENSINO - MDE";
  $head4 = "OR�AMENTOS FISCAL E DA SEGURIDADE SOCIAL";

  $dados  = data_periodo($anousu,$periodo_selecionado);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);

  $txtper = strtoupper($dados["periodo"]);
  $mesini = db_mes($perini[1],1);
  $mesfin = db_mes($perfin[1],1);

  $head5 = "JANEIRO A ".$mesfin."/".$anousu." - ".$txtper." ".$mesini."-".$mesfin;

  $pdf   = new PDF();
  $pdf->Open();
  $pdf->setAutoPageBreak(false);
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
  $pdf->cell(90,($alt*2),"RECEITA RESULTANTES DE IMPOSTOS (caput do art. 212 da Constitui��o)",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
  //BR
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(b)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'B',0,"C",0);
  $pdf->ln();

  for ($linha=1; $linha <= 42; $linha++) {
    
    $borda = "";
    if ($linha == 42) {
      $borda = "T";
    }
    $pdf->cell(90,$alt,$aReceitas[$linha]['label'],"R{$borda}",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['inicial'],'f'),"R{$borda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['atualizada'],'f'),"R{$borda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['bimestre'],'f'),"R{$borda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['exercicio'],'f'),"R{$borda}",0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($aReceitas[$linha]['exercicio']*100)/$aReceitas[$linha]['atualizada'],'f'),"L{$borda}",0,"R",0);
    $pdf->Ln();
    
  }


  $pdf->cell(90,($alt*2),"RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(b)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'B',0,"C",0);
  $pdf->ln();

  for ($linha=43; $linha <= 53; $linha++) {
    
    if ($linha == 43) {
      $iAlturaAtual = $pdf->GetY();
      $pdf->cell(90,$alt,"4 - RECEITA DA APLICA��O FINANCEIRA DE OUTROS RECURSOS DE IMPOSTOS",'R',1,"L",0);
      $pdf->cell(90,$alt,"    VINCULADOS AO ENSINO",'R',1,"L",0);
      $iAlturaNova = $pdf->GetY();
      $pdf->setxy(100,$iAlturaAtual);
      $pdf->cell(20,$alt*2,db_formatar($aReceitas[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aReceitas[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aReceitas[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aReceitas[$linha]['exercicio'],'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt*2,db_formatar(($aReceitas[$linha]['exercicio']*100)/$aReceitas[$linha]['atualizada'],'f'),0,0,"R",0);
      //$pdf->SetY($iAlturaNova);
      
      
    } else {
      
      $sBorda  = "";
      if ($linha == 53) {
        $sBorda = "BT";
      }
      $pdf->cell(90,$alt,$aReceitas[$linha]['label'],"R{$sBorda}",0,"L",0);
      $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['inicial'],'f'),"R{$sBorda}",0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['atualizada'],'f'),"R{$sBorda}",0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['bimestre'],'f'),"R{$sBorda}",0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['exercicio'],'f'),"R{$sBorda}",0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(($aReceitas[$linha]['exercicio']*100)/$aReceitas[$linha]['atualizada'],'f'),$sBorda,0,"R",0);
      
    }
    $pdf->Ln();
    
  }
  
  $pdf->ln();
  $pdf->addpage();
  $pdf->cell(190,($alt*2),'Continua��o '.($pdf->PageNo()-1)."/{nb}",0,1,"R",0);
  $pdf->setfont('arial','B',6);

  $pdf->cell(190,($alt+2),"FUNDEB","T",1,"C",0);
  
  $pdf->setfont('arial','',6);

  $pdf->cell(90,($alt*2),"RECEITAS DO FUNDEB",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"PREVIS�O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(b)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (c) = (b/a)x100",'B',0,"C",0);
  $pdf->ln();

  for ($linha=54; $linha <= 65; $linha++) {

    
    $sBorda  = "";
    if ($linha == 65) {
      $sBorda = "BT";
    }
    $pdf->cell(90,$alt,$aReceitas[$linha]['label'],"R{$sBorda}",0,"L",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['inicial'],'f'),"R{$sBorda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['atualizada'],'f'),"R{$sBorda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['bimestre'],'f'),"R{$sBorda}",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aReceitas[$linha]['exercicio'],'f'),"R{$sBorda}",0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($aReceitas[$linha]['exercicio']*100)/$aReceitas[$linha]['atualizada'],'f'),$sBorda,0,"R",0);
    $pdf->Ln();
   
  }
  $pdf->cell(190,$alt,"[SE RESULTADO L�QUIDO DA TRANSFER�NCIA (12) > 0] = ACR�SCIMO RESULTANTE DAS TRANSFER�NCIAS DO FUNDEB","T",1,"L",0);
  $pdf->cell(190,$alt,"[SE RESULTADO L�QUIDO DA TRANSFER�NCIA (12) < 0] = DECR�SCIMO RESULTANTE DAS TRANSFER�NCIAS DO FUNDEB","B",1,"L",0);
// Fim das RECEITAS

// INICIO das despesas

if ($periodo_selecionado=='6B' || $periodo_selecionado=='2S'){
   $pdf->setfont('arial','',4);
  $pdf->cell(90,($alt+8),"DESPESAS DO FUNDEB",'TBR',0,"C",0);
  $pdf->cell(16,$alt+8,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(16,$alt+8,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(68,$alt,"DESPESAS EXECUTADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(16,$alt+4,"INICIAL",'BR',0,"C",0);
  $pdf->cell(16,$alt+4,"ATUALIZADA (d)",'BR',0,"C",0);

  $pdf->setX(132);
  $pdf->cell(32,$alt,"LIQUIDADAS",'BR',0,"C",0);
  $posY = $pdf->getY()+$alt;
  $iPosX = $pdf->getx();
  $pdf->multicell(18,2.65,"INSCRITAS EM \n RESTOS A PAGAR \nN�O PROCESSADOS(f)",'BR',"C",0);
  $pdf->setxy($iPosX+18, $posY-$alt); 
  $pdf->cell(18,$alt+4,"% (g) = ((e+f)/d)x100",'TB',0,"C",0);
  $pdf->ln();

 
  $pdf->setY($posY);
  $pdf->setX(132);
  $pdf->cell(16,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(16,$alt,"At� o $periodo(e)",'BR',0,"C",0);

  $pdf->ln();

  for ($linha=1; $linha <= 6; $linha++) {
    
    $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
    $pdf->cell(18,$alt,db_formatar($aDespesas[$linha]['inscritas'],'f'),'R',0,"R",0);
//  @$pdf->cell(16,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
    @$pdf->cell(18,$alt,db_formatar( ( ( ($aDespesas[$linha]['exercicio'] + $aDespesas[$linha]['inscritas']) ) /$aDespesas[$linha]['atualizada'] )*100,'f'),0,0,"R",0);
    $pdf->Ln();
    
  }

  $somador14_inicial     = $aDespesas[1]["inicial"]    + $aDespesas[4]["inicial"];
  $somador14_atualizada  = $aDespesas[1]["atualizada"] + $aDespesas[4]["atualizada"];
  $somador14_nobimestre  = $aDespesas[1]["bimestre"]   + $aDespesas[4]["bimestre"];
  $somador14_atebimestre = $aDespesas[1]["exercicio"]  + $aDespesas[4]["exercicio"];
  $somador14_inscritas   = $aDespesas[1]["inscritas"]  + $aDespesas[4]["inscritas"];
  $soma14_InscrBim       = $somador14_atebimestre+$somador14_inscritas;
//
  $pdf->cell(90,$alt,"15 - TOTAL DAS DESPESAS DO FUNDEB(13 + 14)","TBR",0,"L",0);
  $pdf->cell(16,$alt,db_formatar($somador14_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador14_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador14_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(34,$alt,db_formatar($soma14_InscrBim,"f"),"TBR",0,"R",0);
//  @$pdf->cell(18,$alt,db_formatar(($somador14_atebimestre*100)/$somador14_atualizada,'f'),'TB',0,"R",0);
  @$pdf->cell(18,$alt,db_formatar(($soma14_InscrBim/$somador14_atualizada)*100,'f'),'TB',0,"R",0);
  $pdf->ln();

  $perc_12    = $aDespesas[1]["exercicio"]  + $aDespesas[1]["inscritas"];
  $perc_10    = $aReceitas[43]["exercicio"] ;

  $percentual = @(($perc_12/$perc_10)*100);

  //$pdf->cell(172,$alt,"15 - M�NIMO DE 60% DO FUNDEB NA REMUNERA��O DO MAGIST�RIO COM EDUCA��O INFANTIL E ENSINO FUNDAMENTAL(12/10)x100%","TBR",0,"L",0);
  //@$pdf->cell(18,$alt,db_formatar($percentual,'f'),'TB',0,"R",0);
  $pdf->ln();

  }else{
 $pdf->cell(90,($alt*2),"DESPESAS DO FUNDEB",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (d)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(e)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'B',0,"C",0);
  $pdf->ln();

  for ($linha=1; $linha <= 6; $linha++) {
    
    $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
    
  }

  $somador14_inicial     = $aDespesas[1]["inicial"]    + $aDespesas[4]["inicial"];
  $somador14_atualizada  = $aDespesas[1]["atualizada"] + $aDespesas[4]["atualizada"];
// RESTOS A PAGAR
//
//$m_restos_mde["estrut"]    = $orcparamrel->sql_parametro("31","53");
//$m_restos_mde["funcao"]    = $orcparamrel->sql_funcao("31","53");
//$m_restos_mde["subfunc"]   = $orcparamrel->sql_subfunc("31","53");
//$m_restos_mde["recurso"]   = $orcparamrel->sql_recurso("31","53");
//$m_restos_mde["saldo"]     = 0;
//$m_restos_mde["cancelado"] = 0;

  $somador14_nobimestre  = $aDespesas[1]["bimestre"]   + $aDespesas[4]["bimestre"];
  $somador14_atebimestre = $aDespesas[1]["exercicio"]  + $aDespesas[4]["exercicio"];

  $pdf->cell(90,$alt,"15 - TOTAL DAS DESPESAS DO FUNDEB(13 + 14)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador14_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador14_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador14_atebimestre*100)/$somador14_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();

}
//Dedu��es(variaveis)
$pdf->setfont('arial','b',6); 
$pdf->cell(170,($alt),"DEDU��ES PARA FINS DE LIMITE DO FUNDEB PARA PAGAMENTO DOS PROFISSIONAIS DO MAGIST�RIO","TBR",0,"C",0);
$pdf->setfont('arial','',6);
$pdf->cell(20,$alt,"VALOR",'TB',1,"R",0);
$pdf->cell(170,$alt,"16- RESTOS A PAGAR INSCRITOS NO EXERC�CIO SEM DISPONIBILIDADE FINANCEIRA DE RECURSOS DO FUNDEB","R",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorLinha16,"f"),'TB',1,"R",0);
$pdf->cell(170,$alt,"17- DESPESAS CUSTEADAS COM O SUPER�VIT FINANCEIRO, DO EXERC�CIO ANTERIOR, DO FUNDEB ","TR",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorLinha17,"f"),'TB',1,"R",0);
$pdf->cell(170,$alt,"18- TOTAL DAS DEDU��ES CONSIDERADAS PARA FINS DE LIMITE DO FUNDEB (16 + 17) ","TR",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorLinha16+$nValorLinha17,"f"),'TB',1,"R",0);
$pdf->cell(170,$alt,"19- M�NIMO DE 60% DO FUNDEB NA REMUNERA��O DO MAGIST�RIO COM EDUCA��O INFANTIL E ENSINO FUNDAMENTAL1 ((13 -18) / (11) x 100) % ","TBR",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorMinimo60Fundeb,"f"),'TB',1,"R",0);

$pdf->setfont('arial','b',6); 
$pdf->cell(170,($alt),"CONTROLE DA UTILIZA��O DE RECURSOS NO EXERC�CIO SUBSEQ�ENTE","TBR",0,"C",0);
$pdf->setfont('arial','',6);
$pdf->cell(20,$alt,"VALOR",'TB',1,"R",0);
$pdf->cell(170,$alt,"20 - RECURSOS RECEBIDOS DO FUNDEB EM ".($anousu-1)." QUE N�O FORAM UTILIZADOS","R",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorLinha20,"f"),'TB',1,"R",0);
$pdf->cell(170,$alt,"21 - DESPESAS CUSTEADAS COM O SALDO DO ITEM 20 AT� O 1� TRIMESTRE DE {$anousu}�","TR",0,"L",0);
$pdf->cell(20,$alt, db_formatar($nValorLinha21,"f"),'TB',1,"R",0);

$pdf->setfont('arial','b',6);
$pdf->cell(190,$alt,"MANUTEN��O E DESENVOLVIMENTO DO ENSINO - DESPESAS CUSTEADAS COM A RECEITA RESULTANTE DE IMPOSTOS E RECURSOS DO FUNDEB","TB",1,"C",0);
$pdf->setfont('arial','',6);
$pdf->cell(90,$alt*2,"RECEITAS COM A��ES T�PICAS DE MDE","TR",0,"L",0);
$pdf->cell(20,$alt+4,"PREVIS�O",'TBR',0,"C",0);
$pdf->cell(20,$alt+4,"PREVIS�O",'TBR',0,"C",0);
$pdf->cell(60,$alt,"RECEITAS REALIZADAS",'TB',1,"C",0);
// br
$pdf->setX(100);
$pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
$pdf->cell(20,$alt,"ATUALIZADA (a)",'BR',0,"C",0);
$pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
$pdf->cell(20,$alt,"At� o $periodo(b)",'BR',0,"C",0);
$pdf->cell(20,$alt,"% (c) = ((b/a)*100)",'TB',1,"C",0);
$pdf->cell(90,$alt,"22 - IMPOSTOS E TRANSFER�NCIAS DESTINADAS � MDE (25% de 3)�","TR",0,"L",0);
$pdf->cell(20,$alt,db_formatar($nImpostosTransferenciasPrev,"f"),'BR',0,"C",0);
$pdf->cell(20,$alt,db_formatar($nImpostosTransferenciasAtual,"f"),'BR',0,"C",0);
$pdf->cell(20,$alt,db_formatar($nImpostosTransferenciasBim,"f"),'BR',0,"C",0);
$pdf->cell(20,$alt,db_formatar($nImpostosTransferencias,"f"),'BR',0,"C",0);
$pdf->cell(20,$alt,@db_formatar(($nImpostosTransferencias/$nImpostosTransferenciasAtual)*100,"f"),'TB',1,"C",0);

if ($periodo_selecionado=='6B' || $periodo_selecionado=='2S'){
  $pdf->setfont('arial','',4);
  $pdf->cell(90,($alt*3),"DESPESAS COM A��ES T�PICAS DE MDE",'TBR',0,"C",0);

  $pdf->cell(16,$alt+8,"DOTA��O",'TBR',0,"C",0);
  $pdf->cell(16,$alt+8,"DOTA��O",'TBR',0,"C",0);
  $pdf->cell(68,$alt,"DESPESAS EXECUTADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(16,$alt+4,"INICIAL",'BR',0,"C",0);
  $pdf->cell(16,$alt+4,"ATUALIZADA (d)",'BR',0,"C",0);
   
  $pdf->setX(132);
  $pdf->cell(32,$alt,"LIQUIDADAS",'BR',0,"C",0);
  $posY = $pdf->getY()+$alt;
  $iPosX = $pdf->getx();
  $pdf->multicell(18,2.65,"INSCRITAS EM \n RESTOS A PAGAR \nN�O PROCESSADOS(f)",'BR',"C",0);
  $pdf->setxy($iPosX+18, $posY-$alt);
  $pdf->cell(18,$alt+4,"% (g) = ((e+f)/d)x100",'TB',0,"C",0);
  $pdf->ln();


  $pdf->setY($posY);
  $pdf->setX(132);
  $pdf->cell(16,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(16,$alt,"At� o $periodo(e)",'BR',0,"C",0);

  $pdf->ln();

  for ($linha=7; $linha <= 16; $linha++) {
    
    $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
    $pdf->cell(18,$alt,db_formatar($aDespesas[$linha]['inscritas'],'f'),'R',0,"R",0);
    @$pdf->cell(18,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
    $pdf->Ln();
  }


   $somador23_inicial     = $aDespesas[7]["inicial"]     + $aDespesas[10]["inicial"]    + $aDespesas[13]["inicial"]    +
                            $aDespesas[14]["inicial"]    + $aDespesas[15]["inicial"]    + $aDespesas[16]["inicial"];

  $somador23_atualizada   = $aDespesas[7]["atualizada"]  + $aDespesas[10]["atualizada"] + $aDespesas[13]["atualizada"] +
                            $aDespesas[14]["atualizada"] + $aDespesas[15]["atualizada"] + $aDespesas[16]["atualizada"];

  $somador23_nobimestre   = $aDespesas[7]["bimestre"]    + $aDespesas[10]["bimestre"]   + $aDespesas[13]["bimestre"]   +
                            $aDespesas[14]["bimestre"]   + $aDespesas[15]["bimestre"]   + $aDespesas[16]["bimestre"]; 

  $somador23_atebimestre  = $aDespesas[7]["exercicio"]   + $aDespesas[10]["exercicio"]  + $aDespesas[13]["exercicio"]  +
                            $aDespesas[14]["exercicio"]  + $aDespesas[15]["exercicio"]  + $aDespesas[16]["exercicio"];

  $somador23_inscritas    = $aDespesas[7]["inscritas"]   + $aDespesas[10]["inscritas"]  + $aDespesas[13]["inscritas"]  +
                            $aDespesas[14]["inscritas"]  + $aDespesas[15]["inscritas"]  + $aDespesas[16]["inscritas"];

  $soma23_InscrBim        = $somador23_atebimestre+$somador23_inscritas;



  $pdf->setfont('arial','',4);
  $pdf->cell(90,$alt,"29- TOTAL DAS DESPESAS COM A��ES T�PICAS DE MDE (23 + 24 + 25 + 26 + 27 + 28)","TBR",0,"L",0);
  $pdf->setfont('arial','',4);
  $pdf->cell(16,$alt,db_formatar($somador23_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador23_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador23_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(34,$alt,db_formatar($soma23_InscrBim ,"f"),"TBR",0,"R",0);
 //$pdf->cell(18,$alt,db_formatar(($somador23_atebimestre*100)/$somador23_atualizada,'f'),'TB',0,"R",0);
  @$pdf->cell(18,$alt,db_formatar((($somador23_atebimestre+$somador23_inscritas)/$somador23_atualizada )*100,'f'),'TB',0,"R",0);
  $pdf->ln();

} else {
  
  $pdf->cell(90,($alt*2),"DESPESAS COM A��ES T�PICAS DE MDE",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (d)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(e)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'B',0,"C",0);
  $pdf->ln();

  for ($linha=7; $linha <= 16; $linha++) {
    
      
    $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
      
    $pdf->Ln();
    
  }

  $somador23_inicial     = $aDespesas[7]["inicial"]     + $aDespesas[10]["inicial"]    + $aDespesas[13]["inicial"]    +
                           $aDespesas[14]["inicial"]    + $aDespesas[15]["inicial"]    + $aDespesas[16]["inicial"];

  $somador23_atualizada  = $aDespesas[7]["atualizada"]  + $aDespesas[10]["atualizada"] + $aDespesas[13]["atualizada"] +
                           $aDespesas[14]["atualizada"] + $aDespesas[15]["atualizada"] + $aDespesas[16]["atualizada"];

  $somador23_nobimestre  = $aDespesas[7]["bimestre"]    + $aDespesas[10]["bimestre"]   + $aDespesas[13]["bimestre"]   +
                           $aDespesas[14]["bimestre"]   + $aDespesas[15]["bimestre"]   + $aDespesas[16]["bimestre"]; 

  $somador23_atebimestre = $aDespesas[7]["exercicio"]   + $aDespesas[10]["exercicio"]  + $aDespesas[13]["exercicio"]  +
                           $aDespesas[14]["exercicio"]  + $aDespesas[15]["exercicio"]  + $aDespesas[16]["exercicio"];

  $pdf->setfont('arial','',5);
  $pdf->cell(90,$alt,"29- TOTAL DAS DESPESAS COM A��ES T�PICAS DE MDE (23 + 24 + 25 + 26 + 27 + 28)","TBR",0,"L",0);
  $pdf->setfont('arial','',6);


 
  $pdf->cell(20,$alt,db_formatar($somador23_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador23_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador23_atebimestre*100)/$somador23_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();
}
  $pdf->cell(190,($alt*2),'Continua na p�gina '.($pdf->PageNo()+1)."/{nb}",0,1,"R",0);
  $pdf->addPage();
  $pdf->cell(190,($alt*2),'Continua��o '.($pdf->PageNo()-1)."/{nb}",0,1,"R",0);
  $pdf->setfont('arial','',6);
  $pdf->cell(150,($alt*2),"DEDU��ES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL","TBR",0,"C",0);
  $pdf->cell(40,($alt*2),"VALOR","TB",0,"C",0);
  $pdf->ln();

  $pdf->cell(150,$alt,"30- RESULTADO L�QUIDO DAS TRANSFER�NCIAS DO FUNDEB = (12)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($aReceitas[65]["exercicio"],"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"31- DESPESAS CUSTEADAS COM A COMPLEMENTA��O DO FUNDEB NO EXERC�CIO","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha31,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"32- RECEITA DE APLICA��O FINANCEIRA DOS RECURSOS DO FUNDEB AT� O BIMESTRE = (50 h)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($fluxo[4]["valor"],"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"33- DESPESAS CUSTEADAS COM O SUPER�VIT FINANCEIRO, DO EXERC�CIO ANTERIOR, DO FUNDEB","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha33,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"34- DESPESAS CUSTEADAS COM O SUPER�VIT FINANCEIRO, DO EXERC�CIO ANTERIOR, DE OUTROS RECURSOS DE IMPOSTOS","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha34,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"35- RESTOS A PAGAR INSCRITOS NO EXERC�CIO SEM DISPONIBILIDADE FINANCEIRA DE RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha35,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"36- CANCELAMENTO, NO EXERC�CIO, DE RP INSCRITOS COM DISP. FINANCEIRA DE RECURSOS DE IMPOSTOS VINC. AO ENSINO = (46 g)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($m_restos_mde["cancelado"], "f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"37- TOTAL DAS DEDU��ES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL(30 + 31 + 32 + 33 + 34 + 35 + 36)","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha37,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"38- TOTAL DAS DESPESAS PARA FINS DE LIMITE ((23 + 24) - (37))","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha38,"f"),"TB",1,"R",0);
  $pdf->cell(150,$alt,"39- M�NIMO DE 25% DAS RECEITAS RESULTANTES DE IMPOSTOS EM MDE5 ((38) / (3) x 100) %","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($nValorLinha39,"f"),"TB",1,"R",0);
  
if ($periodo_selecionado=='6B' || $periodo_selecionado=='2S'){
 
  $pdf->setfont('arial','',4);
  $pdf->cell(90,($alt*3),"OUTRAS DESP. CUSTEADAS COM REC. ADICIONAIS PARA FINANC. DO ENSINO",'TBR',0,"C",0);

  $pdf->cell(16,$alt+8,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(16,$alt+8,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(68,$alt,"DESPESAS EXECUTADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(16,$alt+4,"INICIAL",'BR',0,"C",0);
  $pdf->cell(16,$alt+4,"ATUALIZADA (d)",'BR',0,"C",0);

  $pdf->setX(132);
  $pdf->cell(32,$alt,"LIQUIDADAS",'1',0,"C",0);
  $posY = $pdf->getY()+$alt;
  $iPosX = $pdf->getx();
  $pdf->multicell(18,2.65,"INSCRITAS EM \n RESTOS A PAGAR \nN�O PROCESSADOS(f)",'BR',"C",0);
  $pdf->setxy($iPosX+18, $posY-$alt);
  $pdf->cell(18,$alt+4,"% (g) = ((e+f)/d)x100",'TB',0,"C",0);
  $pdf->ln();


  $pdf->setY($posY);
  $pdf->setX(132);
  $pdf->cell(16,$alt,"No $periodo",'1',0,"C",0);
  $pdf->cell(16,$alt,"At� o $periodo(e)",'1',0,"C",0);

  $pdf->ln();

  for ($linha=17; $linha <= 20; $linha++) {
    
    if ($linha == 17) {

      $iOriginal = $pdf->getY();
      $pdf->cell(90,$alt,"40- DESPESAS CUSTEADAS COM A APLICA��O FINANCEIRA DE OUTROS RECURSOS",'R',1,"L",0);
      $pdf->cell(90,$alt,"    DE IMPOSTOS VINCULADOS AO ENSINO",'R',1,"L",0);
      $pdf->setXY(100,$iOriginal);
      $pdf->cell(16,$alt*2,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt*2,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt*2,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt*2,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
      $pdf->cell(18,$alt*2,db_formatar($aDespesas[$linha]['inscritas'],'f'),'R',0,"R",0);
     @$pdf->cell(18,$alt*2,db_formatar((($aDespesas[$linha]['exercicio']+$aDespesas[$linha]['inscritas']) /$aDespesas[$linha]['atualizada'])*100,'f'),0,0,"R",0);
      
    } else {
      
      $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
      $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(16,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
      $pdf->cell(18,$alt,db_formatar($aDespesas[$linha]['inscritas'],'f'),'R',0,"R",0);
    //  @$pdf->cell(18,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
      @$pdf->cell(18,$alt,db_formatar((($aDespesas[$linha]['exercicio']+$aDespesas[$linha]['inscritas']) /$aDespesas[$linha]['atualizada'])*100,'f'),0,0,"R",0);
    }
    $pdf->Ln();
  }

  $somador35_inicial     = $aDespesas[17]["inicial"] + $aDespesas[18]["inicial"]    +
                           $aDespesas[19]["inicial"] + $aDespesas[20]["inicial"];

  $somador35_atualizada  = $aDespesas[17]["atualizada"] + $aDespesas[18]["atualizada"] +
                           $aDespesas[19]["atualizada"] + $aDespesas[20]["atualizada"];

  $somador35_nobimestre  = $aDespesas[17]["bimestre"]   + $aDespesas[18]["bimestre"]   +
                           $aDespesas[19]["bimestre"]   + $aDespesas[20]["bimestre"];

  $somador35_atebimestre = $aDespesas[17]["exercicio"]  + $aDespesas[18]["exercicio"]  +
                           $aDespesas[19]["exercicio"]  + $aDespesas[20]["exercicio"];

  $somador35_inscritas   = $aDespesas[17]["inscritas"]  + $aDespesas[18]["inscritas"]  +
                           $aDespesas[19]["inscritas"]  + $aDespesas[20]["inscritas"];
  $soma35_InscrBim       = $somador35_atebimestre + $somador35_inscritas;

  $pdf->setfont('arial','',4);
  $pdf->cell(90,$alt,"44- TOTAL DAS OUTRAS DESP. CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANC. DO ENSINO (40 + 41 + 42 + 43)","TBR",0,"L",0);
  $pdf->setfont('arial','',4);

  $pdf->cell(16,$alt,db_formatar($somador35_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador35_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador35_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(34,$alt,db_formatar($soma35_InscrBim,"f"),"TBR",0,"R",0);
 // @$pdf->cell(18,$alt,db_formatar(($somador35_atebimestre*100)/$somador35_atualizada,'f'),'TB',0,"R",0);
 @$pdf->cell(18,$alt,db_formatar((($somador35_atebimestre + $somador35_inscritas)/$somador35_atualizada)*100,'f'),'TB',0,"R",0);
  $pdf->ln();
  
  $somador36_inicial     = $somador23_inicial     + $somador35_inicial;
  $somador36_atualizada  = $somador23_atualizada  + $somador35_atualizada;
  $somador36_nobimestre  = $somador23_nobimestre  + $somador35_nobimestre;
  $somador36_atebimestre = $somador23_atebimestre + $somador35_atebimestre;
  $somador35_DespEns     = $soma23_InscrBim+$soma35_InscrBim;
  $pdf->cell(90,$alt,"45- TOTAL GERAL DAS DESPESAS COM MDE (29+44)","TBR",0,"L",0);
  $pdf->cell(16,$alt,db_formatar($somador36_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador36_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(16,$alt,db_formatar($somador36_nobimestre,"f"),"TBR",0,"R",0);
 // $pdf->cell(16,$alt,db_formatar($somador36_atebimestre,"f"),"TBR",0,"R",0);
 // $pdf->cell(18,$alt,db_formatar($somador36_inscritas,"f"),"TBR",0,"R",0);
  $pdf->cell(34,$alt,db_formatar($somador35_DespEns,"f"),"TBR",0,"R",0);
 // @$pdf->cell(18,$alt,db_formatar(($somador36_atebimestre*100)/$somador36_atualizada,'f'),'TB',0,"R",0);
  @$pdf->cell(18,$alt,db_formatar(($somador35_DespEns/$somador36_atualizada)*100,'f'),'TB',0,"R",0);
  $pdf->ln();


}else{
  
  $pdf->setfont('arial','B',6);
  $pdf->cell(190,($alt+2),"OUTRAS INFORMA��ES PARA CONTROLE","TB",1,"C",0);
  $pdf->setfont('arial','',6); 
  $pdf->cell(90,($alt*2),"OUTRAS DESP. CUSTEADAS COM REC. ADICIONAIS PARA FINANC. DO ENSINO",'TBR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(20,$alt,"DOTA��O",'TR',0,"C",0);
  $pdf->cell(60,$alt,"DESPESAS LIQUIDADAS",'TB',1,"C",0);
// br
  $pdf->setX(100);
  $pdf->cell(20,$alt,"INICIAL",'BR',0,"C",0);
  $pdf->cell(20,$alt,"ATUALIZADA (d)",'BR',0,"C",0);

  $pdf->setX(140);
  $pdf->cell(20,$alt,"No $periodo",'BR',0,"C",0);
  $pdf->cell(20,$alt,"At� o $periodo(e)",'BR',0,"C",0);
  $pdf->cell(20,$alt,"% (f) = (e/d)x100",'B',0,"C",0);
  $pdf->ln();
  for ($linha = 17; $linha <= 20; $linha++) {
    if ($linha == 17) {
      
      $iOriginal = $pdf->getY();
      $pdf->cell(90,$alt,"40- DESPESAS CUSTEADAS COM A APLICA��O FINANCEIRA DE OUTROS RECURSOS",'R',1,"L",0);
      $pdf->cell(90,$alt,"    DE IMPOSTOS VINCULADOS AO ENSINO",'R',1,"L",0);
      $pdf->setXY(100, $iOriginal);
      $pdf->cell(20,$alt*2,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt*2,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt*2,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
      
    } else {
      
      $pdf->cell(90,$alt,$aDespesas[$linha]['label'],'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($aDespesas[$linha]['exercicio'],'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(($aDespesas[$linha]['exercicio']*100)/$aDespesas[$linha]['atualizada'],'f'),0,0,"R",0);
      
    }
    $pdf->Ln();
  }
  
  $somador35_inicial     = $aDespesas[17]["inicial"]    + $aDespesas[18]["inicial"]    +
                           $aDespesas[19]["inicial"]+ $aDespesas[20]["inicial"];

  $somador35_atualizada  = $aDespesas[17]["atualizada"] + $aDespesas[18]["atualizada"] +
                           $aDespesas[19]["atualizada"]+ $aDespesas[20]["atualizada"];

  $somador35_nobimestre  = $aDespesas[17]["bimestre"]   + $aDespesas[18]["bimestre"]   +
                           $aDespesas[19]["bimestre"]+ $aDespesas[20]["bimestre"];

  $somador35_atebimestre = $aDespesas[17]["exercicio"]  + $aDespesas[18]["exercicio"]  +
                           $aDespesas[19]["exercicio"]  + $aDespesas[20]["exercicio"];
  
  $pdf->setfont('arial','',4.5);
  $pdf->cell(90,$alt,"44- TOTAL DAS OUTRAS DESP. CUSTEADAS COM REC. ADICIONAIS PARA FINANC. DO ENSINO (40+41+42+43)","TBR",0,"L",0);
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

  $pdf->cell(90,$alt,"45- TOTAL GERAL DAS DESPESAS COM MDE (29+44)","TBR",0,"L",0);
  $pdf->cell(20,$alt,db_formatar($somador36_inicial,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_atualizada,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_nobimestre,"f"),"TBR",0,"R",0);
  $pdf->cell(20,$alt,db_formatar($somador36_atebimestre,"f"),"TBR",0,"R",0);
  @$pdf->cell(20,$alt,db_formatar(($somador36_atebimestre*100)/$somador36_atualizada,'f'),'TB',0,"R",0);
  $pdf->ln();
}
  
  $pdf->setfont('arial','',6);

  $pdf->cell(90,($alt*2),"RESTOS A PAGAR INSCRITOS COM DISP. FINANC. DE REC. DE IMP. VINC. AO ENSINO","TBR",0,"C",0);
  $pdf->cell(40,($alt*2),"SALDO AT� O ".strtoupper($periodo),"TBR",0,"C",0);
  $pdf->cell(60,($alt*2),"CANCELADO EM {$anousu} (g)","TB",0,"C",0);
  $pdf->ln();

  $pdf->cell(90,$alt,"46 - RESTOS A PAGAR DE DESPESAS COM MDE","TBR",0,"L",0);
  $pdf->cell(40,$alt,db_formatar($m_restos_mde["saldo"],"f"),"TBR",0,"R",0);
  $pdf->cell(60,$alt,db_formatar($m_restos_mde["cancelado"],"f"),"TB",0,"R",0);
  $pdf->ln();
 // $pdf->cell(190,($alt*2),'Continua na p�gina '.($pdf->PageNo()+1)."/{nb}",0,1,"R",0);

//  $pdf->cell(190,($alt-2),"",0,1,"C",0);

//  $pdf->addpage();
 // $pdf->cell(190,($alt*2),'Continua��o '.($pdf->PageNo()-1)."/{nb}",0,1,"R",0);
  $pdf->cell(190,$alt,"","TB",1,"C",0);
  $pdf->cell(150,$alt*2,"FLUXO FINANCEIRO DOS RECURSOS","TBR",0,"C",0);
  $pdf->cell(40,$alt, "VALOR","TB",1, "C");
  $pdf->setx(160);
  $pdf->cell(20,$alt,"FUNDEB (h)","TBR",0,"R",0);
  $pdf->cell(20,$alt,"FUNDEF","TBL",1,"R",0);

  for($linha=1; $linha <= 5; $linha++){
    if ($linha == 5){
      
      $pdf->cell(150,$alt,$fluxo[$linha]['label'],'RB',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($fluxo[$linha]["valor"],"f"),"BR",0,"R",0);
      $pdf->cell(20,$alt,db_formatar($fluxo[$linha]["valor_fundef"],"f"),"B",0,"R",0);
      
    } else {
      
      $pdf->cell(150,$alt,$fluxo[$linha]['label'],'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($fluxo[$linha]["valor"],"f"),"R",0,"R",0);
      $pdf->cell(20,$alt,db_formatar($fluxo[$linha]["valor_fundef"],"f"),"L",0,"R",0);
      
    }

    $pdf->ln();
  }
// Rodape  
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa(&$pdf, $iCodigoPeriodo);
  
  $sNotaPadrao  = "";
  $sNotaPadrao .= "1 Limites m�nimos anuais a serem cumpridos no encerramento do exerc�cio.\n";
  $sNotaPadrao .= "2 Art. 21, � 2�, Lei 11.494/2007: \"At� 5% dos recursos recebidos � conta dos Fundos, ";
  $sNotaPadrao .= "inclusive relativos � complementa��o da Uni�o recebidos nos termos do �1� do art. 6� desta Lei, poder�o ser";
  $sNotaPadrao .= "utilizados no 1� trimestre do exerc�cio imediatamente subseq�ente, mediante abertura de cr�dito adicional\".\n";
  $sNotaPadrao .= "3 Caput do art. 212 da CF/1988\n";
  $sNotaPadrao .= "4 Os valores referentes � parcela dos Restos a Pagar inscritos sem disponibilidade financeira ";
  $sNotaPadrao .= "vinculada � educa��o dever�o ser informados somente no RREO do �ltimo bimestre do exerc�cio.\n";
  $sNotaPadrao .= "5 Limites m�nimos anuais a serem cumpridos no encerramento do exerc�cio, no �mbito de atua��o ";
  $sNotaPadrao .= "priorit�ria, conforme LDB, art. 11, V.";
  $pdf->MultiCell(190, 3, $sNotaPadrao, 0,"L");
  $pdf->Ln();

// Assinaturas
  $pdf->Ln(30);

  assinaturas(&$pdf,&$classinatura,'LRF');
    
  $pdf->Output();
}
?>