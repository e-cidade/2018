<?php
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

if (!isset($arqinclude)) {

  include(modification("fpdf151/pdf.php"));
  include(modification("fpdf151/assinatura.php"));
  include(modification("libs/db_sql.php"));
  include(modification("libs/db_utils.php"));
  include(modification("libs/db_libcontabilidade.php"));
  include(modification("libs/db_liborcamento.php"));
  include(modification("classes/db_orcparamrel_classe.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("classes/db_orcparamrelopcre_classe.php"));
  include(modification("classes/db_db_config_classe.php"));

  $classinatura = new cl_assinatura();
  $orcparamrel  = new cl_orcparamrel();
  $cldb_config  = new cl_db_config();
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

}

include_once(modification("classes/db_conrelinfo_classe.php"));
include_once(modification("classes/db_conrelvalor_classe.php"));
include_once(modification("classes/db_orcparamrelopcre_classe.php"));
include_once(modification("classes/db_orcparamelemento_classe.php"));
include_once(modification("libs/db_utils.php"));
include_once(modification("std/db_stdClass.php"));
include_once(modification("model/linhaRelatorioContabil.model.php"));
include_once(modification("model/relatorioContabil.model.php"));

$oGet               = db_utils::postMemory($_GET);
$oInstitPref        = db_stdClass::getDadosInstit();
$iAnoUsu            = db_getsession("DB_anousu") + 1;
$clorcparamelemento = new cl_orcparamelemento();
$iAnoUsu1           = $iAnoUsu - 4;
$iAnoUsu2           = $iAnoUsu - 3;
$iAnoUsu3           = $iAnoUsu - 2;
$iCodigoRelatorio   = 69;
$oRelatorio         = new relatorioContabil($iCodigoRelatorio);
$iInstit            = db_getsession("DB_instit");
/*
 * Calculamos a receita para o ano de 2006;
 */
$rsRppsInstit  = $cldb_config->sql_record($cldb_config->sql_query_file(null,
                                                                       "codigo",
                                                                       null,
                                                                       "db21_tipoinstit in (5,6)"));
$iLinhasInstit = $cldb_config->numrows;

if ($iLinhasInstit > 0) {

  $aListaInstit = array();

  for ($iInd = 0; $iInd < $iLinhasInstit; $iInd++) {
    $oInstit        = db_utils::fieldsMemory($rsRppsInstit, $iInd);
    $aListaInstit[] = $oInstit->codigo;
  }

  $sListaInstit = implode(",", $aListaInstit);

} else {
  $sListaInstit = $iInstit;
}
db_inicio_transacao();
for ($iAno = $iAnoUsu1; $iAno <= $iAnoUsu3; $iAno++) {
  // Exclui elementos referente ao exercício anterior;
  $clorcparamelemento->o44_anousu    = $iAno;
  $clorcparamelemento->o44_codparrel = $iCodigoRelatorio;
  $clorcparamelemento->excluir($iAno, $iCodigoRelatorio);

  // Inclui elemento no exercício anterior com base no atual;
  $sSqlWhere      = " o44_codparrel = {$iCodigoRelatorio} ";
  $sSqlDuplicaEle = " select fc_duplica_exercicio('orcparamelemento', 'o44_anousu', " . db_getsession('DB_anousu')
                    . ",{$iAno},'{$sSqlWhere}');";
  $rsDuplicaEle   = db_query($sSqlDuplicaEle);
}
db_fim_transacao();
/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
}
$head2 = "MUNICÍPIO de {$oInstitPref->munic}";
$head3 = $sModelo;
$head4 = "ANEXO DE METAS FISCAIS";
$head5 = $iAnoUsu;
$head6 = "RECEITAS E DESPESAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES";
// fechado ate a linha 360
$aLinhasRelatorio              = array();
$aLinhasRelatorio[0]["label"]  = "RECEITAS PREVIDENCIÁRIAS - RPPS (EXCETO INTRA-ORÇAMENTÁRIAS)(I)";
$aLinhasRelatorio[1]["label"]  = "	 RECEITAS CORRENTES";
$aLinhasRelatorio[2]["label"]  = "    Receita de Contribuíções dos Segurados";
$aLinhasRelatorio[3]["label"]  = "      Pessoa Cívil";
$aLinhasRelatorio[4]["label"]  = "      Pessoa Militar";
$aLinhasRelatorio[5]["label"]  = "    Outras Receitas de Contribuíções";
$aLinhasRelatorio[6]["label"]  = "    Receita Patrimonial";
$aLinhasRelatorio[7]["label"]  = "    Receita de Serviços";
$aLinhasRelatorio[8]["label"]  = "    Outras Receitas Correntes";
$aLinhasRelatorio[9]["label"]  = "      Compensação Previdenciária do RGPS para o RPPS";
$aLinhasRelatorio[10]["label"] = "     Outras Receitas Correntes";
$aLinhasRelatorio[11]["label"] = "  RECEITAS DE CAPITAL";
$aLinhasRelatorio[12]["label"] = "    Alienação de Bens, Direitos e Ativos";
$aLinhasRelatorio[13]["label"] = "    Amortização de Empréstimos";
$aLinhasRelatorio[14]["label"] = "    Outras Receitas de Capital";
$aLinhasRelatorio[15]["label"] = "  (-)DEDUÇÕES DA RECEITA";
$aLinhasRelatorio[16]["label"] = "RECEITAS PREVIDENCIÁRIAS - RPPS(INTRA-ORÇAMENTÁRIAS)(II)";
$aLinhasRelatorio[17]["label"] = "  RECEITAS CORRENTES";
$aLinhasRelatorio[18]["label"] = "    Receita de Contribuíções";
$aLinhasRelatorio[19]["label"] = "      Patronal";
$aLinhasRelatorio[20]["label"] = "        Pessoa Civil";
$aLinhasRelatorio[21]["label"] = "        Pessoa Militar";
$aLinhasRelatorio[22]["label"] = "      Cobertura de Déficit Atuarial";
$aLinhasRelatorio[23]["label"] = "      Regime de Débitos e Parcelamentos";
$aLinhasRelatorio[24]["label"] = "    Receita Patrimonial";
$aLinhasRelatorio[25]["label"] = "    Receita de Serviços";
$aLinhasRelatorio[26]["label"] = "    Outras Receitas Correntes";
$aLinhasRelatorio[27]["label"] = "  RECEITAS DE CAPITAL";
$aLinhasRelatorio[28]["label"] = "  (-) DEDUÇÕES DA RECEITA";
$aLinhasRelatorio[29]["label"] = "DESPESAS PREVIDENCIÁRIAS - RPPS(EXCETO INTRA-ORÇAMENTÁRIAS)(IV)";
$aLinhasRelatorio[30]["label"] = "  ADMINISTRAÇÃO";
$aLinhasRelatorio[31]["label"] = "    Despesas Correntes";
$aLinhasRelatorio[32]["label"] = "    Despesas de Capital";
$aLinhasRelatorio[33]["label"] = "  PREVIDÊNCIA";
$aLinhasRelatorio[34]["label"] = "    Pessoal Civil";
$aLinhasRelatorio[35]["label"] = "    Pessoal Militar";
$aLinhasRelatorio[36]["label"] = "    Outras Despesas Previdenciárias";
$aLinhasRelatorio[37]["label"] = "      Compensação Previdenciária do RPPS para o RGPS";
$aLinhasRelatorio[38]["label"] = "      Demais Despesas Previdenciárias";
$aLinhasRelatorio[39]["label"] = "DESPESAS PREVIDENCIÁRIAS - RPPS(INTRA-ORÇAMENTÁRIAS)(V)";
$aLinhasRelatorio[40]["label"] = "  ADMINISTRAÇÃO";
$aLinhasRelatorio[41]["label"] = "    Despesas Correntes";
$aLinhasRelatorio[42]["label"] = "    Despesas de Capital";
$aLinhasRelatorio[43]["label"] = "TOTAL DOS APORTES PARA O RPPS";
$aLinhasRelatorio[44]["label"] = "  Plano Financeiro";
$aLinhasRelatorio[45]["label"] = "    Recursos para Cobertura de Insuficiências Financeiras";
$aLinhasRelatorio[46]["label"] = "    Recursos para Formação de Reserva";
$aLinhasRelatorio[47]["label"] = "    Outros Aportes para o RPPS";
$aLinhasRelatorio[48]["label"] = "  Plano Previdênciário";
$aLinhasRelatorio[49]["label"] = "    Recursos para Cobertura de Déficit Financeiro";
$aLinhasRelatorio[50]["label"] = "    Recursos para Cobertura de Déficit Autuarial";
$aLinhasRelatorio[51]["label"] = "    Outros Aportes para o RPPS";
$aLinhasRelatorio[52]["label"] = "RESERVA ORÇAMENTÁRIA DO RPPS";
$aLinhasRelatorio[53]["label"] = "BENS E DIREITOS DO RPPS";

for ($i = 0; $i < count($aLinhasRelatorio); $i++) {

  $aLinhasRelatorio[$i]["ano2"] = 0;
  $aLinhasRelatorio[$i]["ano3"] = 0;
  $aLinhasRelatorio[$i]["ano4"] = 0;

}
for ($linha = 1; $linha <= 20; $linha++) {

  $m_receita[$linha]['estrut1'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu1);
  $m_receita[$linha]['estrut2'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu2);
  $m_receita[$linha]['estrut3'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu3);
  $m_receita[$linha]['ano2']    = 0;
  $m_receita[$linha]['ano3']    = 0;
  $m_receita[$linha]['ano4']    = 0;
  $oLinhaRelatorio              = new linhaRelatorioContabil($iCodigoRelatorio, $linha);

  if (!empty($oLinhaRelatorio)) {

    $aValores = $oLinhaRelatorio->getValoresColunas();
    foreach ($aValores as $oValorLinha) {

      $m_receita[$linha]['ano4'] += $oValorLinha->colunas[0]->o117_valor;
      $m_receita[$linha]['ano3'] += $oValorLinha->colunas[1]->o117_valor;
      $m_receita[$linha]['ano2'] += $oValorLinha->colunas[2]->o117_valor;

    }
  }
}
//Parametros de Despesas
for ($linha = 21; $linha <= 42; $linha++) {

  $m_despesa[$linha]['estrut1'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu1);
  $m_despesa[$linha]['nivel1']  = $orcparamrel->sql_nivel($iCodigoRelatorio, $linha);
  $m_despesa[$linha]["funcao1"] = $orcparamrel->sql_funcao($iCodigoRelatorio, $linha);
  $m_despesa[$linha]['estrut2'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu2);
  $m_despesa[$linha]['nivel2']  = $orcparamrel->sql_nivel($iCodigoRelatorio, $linha);
  $m_despesa[$linha]["funcao2"] = $orcparamrel->sql_funcao($iCodigoRelatorio, $linha);
  $m_despesa[$linha]['estrut3'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu3);
  $m_despesa[$linha]['nivel3']  = $orcparamrel->sql_nivel($iCodigoRelatorio, $linha);
  $m_despesa[$linha]["funcao3"] = $orcparamrel->sql_funcao($iCodigoRelatorio, $linha);
  $m_despesa[$linha]['ano2']    = 0;
  $m_despesa[$linha]['ano3']    = 0;
  $m_despesa[$linha]['ano4']    = 0;
  $oLinhaRelatorio              = new linhaRelatorioContabil($iCodigoRelatorio, $linha);

  if (!empty($oLinhaRelatorio)) {

    $aValores = $oLinhaRelatorio->getValoresColunas();
    foreach ($aValores as $oValorLinha) {

      $m_despesa[$linha]['ano4'] += $oValorLinha->colunas[0]->o117_valor;
      $m_despesa[$linha]['ano3'] += $oValorLinha->colunas[1]->o117_valor;
      $m_despesa[$linha]['ano2'] += $oValorLinha->colunas[2]->o117_valor;
    }
  }
}

for ($linha = 29; $linha <= 34; $linha++) {

  $m_receita[$linha]['estrut1'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu1);
  $m_receita[$linha]['estrut2'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu2);
  $m_receita[$linha]['estrut3'] = $orcparamrel->sql_parametro($iCodigoRelatorio, $linha, "f", $sListaInstit, $iAnoUsu3);
  $m_receita[$linha]['ano2']    = 0;
  $m_receita[$linha]['ano3']    = 0;
  $m_receita[$linha]['ano4']    = 0;
  $oLinhaRelatorio              = new linhaRelatorioContabil($iCodigoRelatorio, $linha);

  if (!empty($oLinhaRelatorio)) {

    $aValores = $oLinhaRelatorio->getValoresColunas();
    foreach ($aValores as $oValorLinha) {

      $m_receita[$linha]['ano4'] += $oValorLinha->colunas[0]->o117_valor;
      $m_receita[$linha]['ano3'] += $oValorLinha->colunas[1]->o117_valor;
      $m_receita[$linha]['ano2'] += $oValorLinha->colunas[2]->o117_valor;

    }
  }
}
$oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, 35);

if (!empty($oLinhaRelatorio)) {

  $aValores = $oLinhaRelatorio->getValoresColunas();
  foreach ($aValores as $oValorLinha) {

    $aLinhasRelatorio[52]['ano4'] += $oValorLinha->colunas[0]->o117_valor;
    $aLinhasRelatorio[52]['ano3'] += $oValorLinha->colunas[1]->o117_valor;
    $aLinhasRelatorio[52]['ano2'] += $oValorLinha->colunas[2]->o117_valor;
  }
}

$oLinhaRelatorio = new linhaRelatorioContabil($iCodigoRelatorio, 36);
if (!empty($oLinhaRelatorio)) {

  $aValores = $oLinhaRelatorio->getValoresColunas();
  foreach ($aValores as $oValorLinha) {

    $aLinhasRelatorio[53]['ano4'] += $oValorLinha->colunas[0]->o117_valor;
    $aLinhasRelatorio[53]['ano3'] += $oValorLinha->colunas[1]->o117_valor;
    $aLinhasRelatorio[53]['ano2'] += $oValorLinha->colunas[2]->o117_valor;
  }
}
$db_filtro          = " o70_instit in ({$sListaInstit})";
$rsRecExeAno4       = db_receitasaldo(11, 1, 3, true, $db_filtro, $iAnoUsu1, "{$iAnoUsu1}-01-01", "{$iAnoUsu1}-12-31");
$iLinhasRecExeAtual = pg_num_rows($rsRecExeAno4);
@db_query("drop table work_receita");

for ($iInd = 0; $iInd < $iLinhasRecExeAtual; $iInd++) {

  $oRecExeAtual = db_utils::fieldsMemory($rsRecExeAno4, $iInd);
  $sEstrut      = $oRecExeAtual->o57_fonte;

  for ($linha = 1; $linha <= 20; $linha++) {

    if (in_array($sEstrut, $m_receita[$linha]['estrut1'])) {
      $m_receita[$linha]['ano4'] += $oRecExeAtual->saldo_arrecadado_acumulado;

    }
  }
}
$db_filtro          = " o70_instit in ({$sListaInstit})";
$rsRecExeAno3       = db_receitasaldo(11, 1, 3, true, $db_filtro, $iAnoUsu2, "{$iAnoUsu2}-01-01", "{$iAnoUsu2}-12-31");
$iLinhasRecExeAtual = pg_num_rows($rsRecExeAno3);
@db_query("drop table work_receita");

for ($iInd = 0; $iInd < $iLinhasRecExeAtual; $iInd++) {

  $oRecExeAtual = db_utils::fieldsMemory($rsRecExeAno3, $iInd);
  $sEstrut      = $oRecExeAtual->o57_fonte;

  for ($linha = 1; $linha <= 20; $linha++) {

    if (in_array($sEstrut, $m_receita[$linha]['estrut2'])) {
      $m_receita[$linha]['ano3'] += $oRecExeAtual->saldo_arrecadado_acumulado;

    }
  }
}

$db_filtro          = " o70_instit in ({$sListaInstit})";
$rsRecExeAno3       = db_receitasaldo(11, 1, 3, true, $db_filtro, $iAnoUsu3, "{$iAnoUsu3}-01-01", "{$iAnoUsu3}-12-31");
$iLinhasRecExeAtual = pg_num_rows($rsRecExeAno3);
@db_query("drop table work_receita");

for ($iInd = 0; $iInd < $iLinhasRecExeAtual; $iInd++) {

  $oRecExeAtual = db_utils::fieldsMemory($rsRecExeAno3, $iInd);
  $sEstrut      = $oRecExeAtual->o57_fonte;

  for ($linha = 1; $linha <= 20; $linha++) {

    if (in_array($sEstrut, $m_receita[$linha]['estrut3'])) {
      $m_receita[$linha]['ano2'] += $oRecExeAtual->saldo_arrecadado_acumulado;

    }
  }

}
/**
 * Inicio da despesa
 */
$db_filtro = "o58_instit in ({$sListaInstit})";

for ($linha = 21; $linha <= 28; $linha++) {

  $aListaFuncao = array();
  $sWhereFuncao = "";

  foreach ($m_despesa[$linha]["funcao1"] as $sRegistro) {
    $aListaFuncao[] = $sRegistro;
  }

  $sListaFuncao = implode(",", $aListaFuncao);

  if (trim($sListaFuncao) != "") {
    $sWhereFuncao = " and o58_funcao in ({$sListaFuncao}) ";
  }

  $rsDespFuncaoAtual     = db_dotacaosaldo(8,
                                           2,
                                           3,
                                           true,
                                           $db_filtro . $sWhereFuncao,
                                           $iAnoUsu1,
                                           "{$iAnoUsu1}-01-01",
                                           "{$iAnoUsu1}-12-31");
  $iLinhaDespFuncaoAtual = pg_num_rows($rsDespFuncaoAtual);

  for ($iInd = 0; $iInd < $iLinhaDespFuncaoAtual; $iInd++) {

    $oDespFuncaoAtual = db_utils::fieldsMemory($rsDespFuncaoAtual, $iInd);

    $sNivel      = $m_despesa[$linha]['nivel1'];
    $sEstrutural = $oDespFuncaoAtual->o58_elemento . '00';
    $sEstrutural = substr($sEstrutural, 0, $sNivel);
    $sEstrutural = str_pad($sEstrutural, 15, "0", STR_PAD_RIGHT);

    if (substr($oDespFuncaoAtual->o58_elemento, 3, 2) == "91") {
      continue;
    }

    if (in_array($sEstrutural, $m_despesa[$linha]['estrut1'])) {
      $m_despesa[$linha]['ano4'] += $oDespFuncaoAtual->empenhado_acumulado - $oDespFuncaoAtual->anulado_acumulado;
    }
  }

  $aListaFuncao = array();
  $sWhereFuncao = "";

  foreach ($m_despesa[$linha]["funcao2"] as $sRegistro) {
    $aListaFuncao[] = $sRegistro;
  }

  $sListaFuncao = implode(",", $aListaFuncao);

  if (trim($sListaFuncao) != "") {
    $sWhereFuncao = " and o58_funcao in ({$sListaFuncao}) ";
  }

  $rsDespFuncaoAtual     = db_dotacaosaldo(8,
                                           2,
                                           3,
                                           true,
                                           $db_filtro . $sWhereFuncao,
                                           $iAnoUsu2,
                                           "{$iAnoUsu2}-01-01",
                                           "{$iAnoUsu2}-12-31");
  $iLinhaDespFuncaoAtual = pg_num_rows($rsDespFuncaoAtual);

  for ($iInd = 0; $iInd < $iLinhaDespFuncaoAtual; $iInd++) {

    $oDespFuncaoAtual = db_utils::fieldsMemory($rsDespFuncaoAtual, $iInd);

    $sNivel      = $m_despesa[$linha]['nivel2'];
    $sEstrutural = $oDespFuncaoAtual->o58_elemento . '00';
    $sEstrutural = substr($sEstrutural, 0, $sNivel);
    $sEstrutural = str_pad($sEstrutural, 15, "0", STR_PAD_RIGHT);

    if (substr($oDespFuncaoAtual->o58_elemento, 3, 2) == "91") {
      continue;
    }

    if (in_array($sEstrutural, $m_despesa[$linha]['estrut2'])) {
      $m_despesa[$linha]['ano3'] += $oDespFuncaoAtual->empenhado_acumulado - $oDespFuncaoAtual->anulado_acumulado;
    }
  }

  $aListaFuncao = array();
  $sWhereFuncao = "";

  foreach ($m_despesa[$linha]["funcao3"] as $sRegistro) {
    $aListaFuncao[] = $sRegistro;
  }

  $sListaFuncao = implode(",", $aListaFuncao);

  if (trim($sListaFuncao) != "") {
    $sWhereFuncao = " and o58_funcao in ({$sListaFuncao}) ";
  }

  $rsDespFuncaoAtual     = db_dotacaosaldo(8,
                                           2,
                                           3,
                                           true,
                                           $db_filtro . $sWhereFuncao,
                                           $iAnoUsu3,
                                           "{$iAnoUsu3}-01-01",
                                           "{$iAnoUsu3}-12-31");
  $iLinhaDespFuncaoAtual = pg_num_rows($rsDespFuncaoAtual);

  for ($iInd = 0; $iInd < $iLinhaDespFuncaoAtual; $iInd++) {

    $oDespFuncaoAtual = db_utils::fieldsMemory($rsDespFuncaoAtual, $iInd);

    $sNivel      = $m_despesa[$linha]['nivel3'];
    $sEstrutural = $oDespFuncaoAtual->o58_elemento . '00';
    $sEstrutural = substr($sEstrutural, 0, $sNivel);
    $sEstrutural = str_pad($sEstrutural, 15, "0", STR_PAD_RIGHT);

    if (substr($oDespFuncaoAtual->o58_elemento, 3, 2) == "91") {
      continue;
    }

    if (in_array($sEstrutural, $m_despesa[$linha]['estrut3'])) {
      $m_despesa[$linha]['ano2'] += $oDespFuncaoAtual->empenhado_acumulado - $oDespFuncaoAtual->anulado_acumulado;
    }
  }
}

/**
 * Aportes
 */
$db_filtro_disponivel = "c61_instit in (" . $sListaInstit . ") ";

// Exercicio Atual
$rsDispAtual      = db_planocontassaldo_matriz($iAnoUsu1,
                                               "{$iAnoUsu1}-01-01",
                                               "{$iAnoUsu1}-12-31",
                                               false,
                                               $db_filtro_disponivel);
$iLinhasDispAtual = pg_num_rows($rsDispAtual);
@db_query("drop table work_receita");
@db_query("drop table work_pl");
@db_query("drop table work_pl_estrut");
@db_query("drop table work_pl_estrutmae");
for ($iInd = 0; $iInd < $iLinhasDispAtual; $iInd++) {

  $oDispAtual = db_utils::fieldsMemory($rsDispAtual, $iInd);
  if (substr($oDispAtual->estrutural, 3, 2) == "91") {
    continue;
  }
  for ($linha = 29; $linha <= 34; $linha++) {

    if (in_array($oDispAtual->estrutural, $m_receita[$linha]['estrut1'])) {
      $m_receita[$linha]['ano4'] += $oDispAtual->saldo_final;
    }
  }
}

$rsDispAtual      = db_planocontassaldo_matriz($iAnoUsu2,
                                               "{$iAnoUsu2}-01-01",
                                               "{$iAnoUsu2}-12-31",
                                               false,
                                               $db_filtro_disponivel);
$iLinhasDispAtual = pg_num_rows($rsDispAtual);
@db_query("drop table work_receita");
@db_query("drop table work_pl");
@db_query("drop table work_pl_estrut");
@db_query("drop table work_pl_estrutmae");
for ($iInd = 0; $iInd < $iLinhasDispAtual; $iInd++) {

  $oDispAtual = db_utils::fieldsMemory($rsDispAtual, $iInd);
  if (substr($oDispAtual->estrutural, 3, 2) == "91") {
    continue;
  }
  for ($linha = 29; $linha <= 34; $linha++) {

    if (in_array($oDispAtual->estrutural, $m_receita[$linha]['estrut2'])) {
      $m_receita[$linha]['ano3'] += $oDispAtual->saldo_final;
    }
  }
}

$rsDispAtual      = db_planocontassaldo_matriz($iAnoUsu3,
                                               "{$iAnoUsu3}-01-01",
                                               "{$iAnoUsu3}-12-31",
                                               false,
                                               $db_filtro_disponivel);
$iLinhasDispAtual = pg_num_rows($rsDispAtual);
@db_query("drop table work_receita");
@db_query("drop table work_pl");
@db_query("drop table work_pl_estrut");
@db_query("drop table work_pl_estrutmae");
for ($iInd = 0; $iInd < $iLinhasDispAtual; $iInd++) {

  $oDispAtual = db_utils::fieldsMemory($rsDispAtual, $iInd);
  if (substr($oDispAtual->estrutural, 3, 2) == "91") {
    continue;
  }
  for ($linha = 29; $linha <= 34; $linha++) {

    if (in_array($oDispAtual->estrutural, $m_receita[$linha]['estrut3'])) {
      $m_receita[$linha]['ano2'] += $oDispAtual->saldo_final;
    }
  }
}

$pcol = array(
  1 => 'ano2', 2 => 'ano3', 3 => 'ano4'
);

$ipcol = count($pcol);


for ($col = 1; $col <= $ipcol; $col++) {

  $aLinhasRelatorio[3] [$pcol[$col]] = $m_receita[1][$pcol[$col]];//Civis
  $aLinhasRelatorio[4] [$pcol[$col]] = $m_receita[2][$pcol[$col]];//Militar
  $aLinhasRelatorio[5] [$pcol[$col]] = $m_receita[3][$pcol[$col]];//Outras Receitas de Contribuíções"
  $aLinhasRelatorio[6] [$pcol[$col]] = $m_receita[4][$pcol[$col]];//Receita Patrimonial
  $aLinhasRelatorio[7] [$pcol[$col]] = $m_receita[5][$pcol[$col]];//receitas de servicos
  $aLinhasRelatorio[9] [$pcol[$col]] = $m_receita[6][$pcol[$col]];
  $aLinhasRelatorio[10][$pcol[$col]] = $m_receita[7][$pcol[$col]];
  $aLinhasRelatorio[8] [$pcol[$col]] = $aLinhasRelatorio[9][$pcol[$col]] + $aLinhasRelatorio[10] [$pcol[$col]];

  $aLinhasRelatorio[12][$pcol[$col]] = $m_receita[8][$pcol[$col]];
  $aLinhasRelatorio[13][$pcol[$col]] = $m_receita[9][$pcol[$col]];

  $aLinhasRelatorio[14][$pcol[$col]] = $m_receita[10][$pcol[$col]];

  $aLinhasRelatorio[11][$pcol[$col]] = $aLinhasRelatorio[12][$pcol[$col]] + $aLinhasRelatorio[13][$pcol[$col]]
                                       + $aLinhasRelatorio[14][$pcol[$col]];

  $aLinhasRelatorio[15][$pcol[$col]] = $m_receita[11][$pcol[$col]];

  $aLinhasRelatorio[20][$pcol[$col]] = $m_receita[12][$pcol[$col]];
  $aLinhasRelatorio[21][$pcol[$col]] = $m_receita[13][$pcol[$col]];
  $aLinhasRelatorio[19][$pcol[$col]] = $aLinhasRelatorio[20][$pcol[$col]] + $aLinhasRelatorio[21] [$pcol[$col]];

  $aLinhasRelatorio[22][$pcol[$col]] = $m_receita[14][$pcol[$col]];
  $aLinhasRelatorio[23][$pcol[$col]] = $m_receita[15][$pcol[$col]];

  $aLinhasRelatorio[18][$pcol[$col]] = $aLinhasRelatorio[19][$pcol[$col]] + $aLinhasRelatorio[22][$pcol[$col]]
                                       + $aLinhasRelatorio[23][$pcol[$col]];

  $aLinhasRelatorio[24][$pcol[$col]] = $m_receita[16][$pcol[$col]];
  $aLinhasRelatorio[25][$pcol[$col]] = $m_receita[17][$pcol[$col]];
  $aLinhasRelatorio[26][$pcol[$col]] = $m_receita[18][$pcol[$col]];
  $aLinhasRelatorio[27][$pcol[$col]] = $m_receita[19][$pcol[$col]];
  $aLinhasRelatorio[28][$pcol[$col]] = $m_receita[20][$pcol[$col]];

  $aLinhasRelatorio[17][$pcol[$col]] = $aLinhasRelatorio[18][$pcol[$col]] + $aLinhasRelatorio[24][$pcol[$col]]
                                       + $aLinhasRelatorio[25][$pcol[$col]] + $aLinhasRelatorio[26][$pcol[$col]];

  $aLinhasRelatorio[16][$pcol[$col]] = $aLinhasRelatorio[17][$pcol[$col]] + $aLinhasRelatorio[27][$pcol[$col]]
                                       + $aLinhasRelatorio[28][$pcol[$col]];
  $aLinhasRelatorio[2] [$pcol[$col]] = $aLinhasRelatorio[3][$pcol[$col]] + $aLinhasRelatorio[4] [$pcol[$col]];
  $aLinhasRelatorio[1] [$pcol[$col]] = $aLinhasRelatorio[2][$pcol[$col]] + $aLinhasRelatorio[5][$pcol[$col]]
                                       + $aLinhasRelatorio[6][$pcol[$col]] + $aLinhasRelatorio[7][$pcol[$col]]
                                       + $aLinhasRelatorio[8][$pcol[$col]];
  $aLinhasRelatorio[0] [$pcol[$col]] = ($aLinhasRelatorio[1][$pcol[$col]] + $aLinhasRelatorio[11][$pcol[$col]]
                                        + $aLinhasRelatorio[15][$pcol[$col]]);


  //Outras Receitas

  $aLinhasRelatorio[45][$pcol[$col]] = $m_receita[29][$pcol[$col]];
  $aLinhasRelatorio[46][$pcol[$col]] = $m_receita[30][$pcol[$col]];
  $aLinhasRelatorio[47][$pcol[$col]] = $m_receita[31][$pcol[$col]];
  $aLinhasRelatorio[44][$pcol[$col]] = $aLinhasRelatorio[46][$pcol[$col]] + $aLinhasRelatorio[45][$pcol[$col]]
                                       + $aLinhasRelatorio[47][$pcol[$col]];

  $aLinhasRelatorio[49][$pcol[$col]] = $m_receita[32][$pcol[$col]];
  $aLinhasRelatorio[50][$pcol[$col]] = $m_receita[33][$pcol[$col]];
  $aLinhasRelatorio[51][$pcol[$col]] = $m_receita[34][$pcol[$col]];
  $aLinhasRelatorio[48][$pcol[$col]] = $aLinhasRelatorio[49][$pcol[$col]] + $aLinhasRelatorio[50][$pcol[$col]]
                                       + $aLinhasRelatorio[51][$pcol[$col]];

  $aLinhasRelatorio[43][$pcol[$col]] = $aLinhasRelatorio[48][$pcol[$col]] + $aLinhasRelatorio[44][$pcol[$col]];

  //Despesas
  $aLinhasRelatorio[31][$pcol[$col]] = $m_despesa[21][$pcol[$col]];
  $aLinhasRelatorio[32][$pcol[$col]] = $m_despesa[22][$pcol[$col]];
  $aLinhasRelatorio[30][$pcol[$col]] = $aLinhasRelatorio[32][$pcol[$col]] + $aLinhasRelatorio[31][$pcol[$col]];

  $aLinhasRelatorio[34][$pcol[$col]] = $m_despesa[23][$pcol[$col]];
  $aLinhasRelatorio[35][$pcol[$col]] = $m_despesa[24][$pcol[$col]];

  $aLinhasRelatorio[37][$pcol[$col]] = $m_despesa[25][$pcol[$col]];
  $aLinhasRelatorio[38][$pcol[$col]] = $m_despesa[26][$pcol[$col]];
  $aLinhasRelatorio[36][$pcol[$col]] = $aLinhasRelatorio[37][$pcol[$col]] + $aLinhasRelatorio[38][$pcol[$col]];

  $aLinhasRelatorio[33][$pcol[$col]] = $aLinhasRelatorio[35][$pcol[$col]] + $aLinhasRelatorio[34][$pcol[$col]]
                                       + $aLinhasRelatorio[36][$pcol[$col]];

  $aLinhasRelatorio[29][$pcol[$col]] = $aLinhasRelatorio[30][$pcol[$col]] + $aLinhasRelatorio[33][$pcol[$col]];

  $aLinhasRelatorio[41][$pcol[$col]] = $m_despesa[27][$pcol[$col]];
  $aLinhasRelatorio[42][$pcol[$col]] = $m_despesa[28][$pcol[$col]];
  $aLinhasRelatorio[40][$pcol[$col]] = $aLinhasRelatorio[42][$pcol[$col]] + $aLinhasRelatorio[41][$pcol[$col]];
  $aLinhasRelatorio[39][$pcol[$col]] = $aLinhasRelatorio[40][$pcol[$col]];

  $aLinhasRelatorio[36][$pcol[$col]] = $aLinhasRelatorio[37][$pcol[$col]] + $aLinhasRelatorio[38][$pcol[$col]];
}

$pdf = new PDF("P", "mm", "A4");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$alt    = 4;
$pagina = 1;
$pdf->addpage();
$pdf->setfont('arial', '', 7);
$pdf->cell(165, $alt, 'AMF - Demonstrativo VI(LRF, art.4°,' . chr(167) . ' 2°, inciso IV,alíne "a")', 'B', 0, "L", 0);
$pdf->cell(25, $alt, 'R$ 1,00', 'B', 1, "R", 0);
$pdf->cell(100, $alt, "", 0, 0, "C", 0);
$pdf->cell(30, $alt, "", 'LR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'L', 1, "C", 0);
$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "RECEITAS", 'R', 0, "C", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, $iAnoUsu1, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu2, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu3, 'L', 1, "C", 0);
$pdf->cell(100, $alt, "", 'RB', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LB', 1, "C", 0);

for ($i = 0; $i < 29; $i++) {

  $pdf->cell(100, $alt, $aLinhasRelatorio[$i]["label"], 'R', 0, "L", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano4"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano3"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano2"], "f"), 'L', 1, "R", 0);
}

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "TOTAL DAS RECEITAS PREVIDENCIÁRIAS (III)=(I+II)", 'TR', 0, "L", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[0]["ano4"] + $aLinhasRelatorio[16]["ano4"], "f"), 'LTR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[0]["ano3"] + $aLinhasRelatorio[16]["ano3"], "f"), 'LTR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[0]["ano2"] + $aLinhasRelatorio[16]["ano2"], "f"), 'LT', 1, "R", 0);

$pdf->cell(190, $alt, "", 'TB', 1, "L", 0);

$pdf->cell(100, $alt, "", 'R', 0, "C", 0);
$pdf->cell(30, $alt, "", 'R', 0, "C", 0);
$pdf->cell(30, $alt, "", 'R', 0, "C", 0);
$pdf->cell(30, $alt, "", 0, 1, "C", 0);
$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "DESPESAS", 'R', 0, "C", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, $iAnoUsu1, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu2, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu3, 'L', 1, "C", 0);
$pdf->cell(100, $alt, "", 'RB', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LB', 1, "C", 0);

for ($i = 29; $i < 43; $i++) {

  $pdf->cell(100, $alt, $aLinhasRelatorio[$i]["label"], 'R', 0, "L", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano4"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano3"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano2"], "f"), 'L', 1, "R", 0);
}

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "TOTAL DAS DESPESAS PREVIDENCIÁRIAS (VI)=(IV+V)", 'TR', 0, "L", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[29]["ano4"] + $aLinhasRelatorio[39]["ano4"], "f"), 'LTR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[29]["ano3"] + $aLinhasRelatorio[39]["ano3"], "f"), 'LTR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[29]["ano2"] + $aLinhasRelatorio[39]["ano2"], "f"), 'LT', 1, "R", 0);

$pdf->cell(190, $alt, "", 'TB', 1, "L", 0);

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "RESULTADO PREVIDENCIÁRIO (VII)=(III-VI)", 'TR', 0, "L", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30,
           $alt,
           db_formatar(($aLinhasRelatorio[0]["ano4"] + $aLinhasRelatorio[16]["ano4"]) - ($aLinhasRelatorio[29]["ano4"]
                                                                                         + $aLinhasRelatorio[39]["ano4"]),
                       "f"),
           'LTR',
           0,
           "R",
           0);
$pdf->cell(30,
           $alt,
           db_formatar(($aLinhasRelatorio[0]["ano3"] + $aLinhasRelatorio[16]["ano3"]) - ($aLinhasRelatorio[29]["ano3"]
                                                                                         + $aLinhasRelatorio[39]["ano3"]),
                       "f"),
           'LTR',
           0,
           "R",
           0);
$pdf->cell(30,
           $alt,
           db_formatar(($aLinhasRelatorio[0]["ano2"] + $aLinhasRelatorio[16]["ano2"]) - ($aLinhasRelatorio[29]["ano2"]
                                                                                         + $aLinhasRelatorio[39]["ano2"]),
                       "f"),
           'LT',
           1,
           "R",
           0);

$pdf->cell(190, $alt, "", 'T', 1, "L", 0);
$pdf->addpage();

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "APORTES DE RECURSOS PARA O REGIME PRÓPRIO", 'TR', 0, "C", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, "", 'TR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'TR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'T', 1, "C", 0);
$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "DE PREVIDÊNCIA DO SERVIDOR", 'R', 0, "C", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, $iAnoUsu1, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu2, 'LR', 0, "C", 0);
$pdf->cell(30, $alt, $iAnoUsu3, 'L', 1, "C", 0);
$pdf->cell(100, $alt, "", 'RB', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LBR', 0, "C", 0);
$pdf->cell(30, $alt, "", 'LB', 1, "C", 0);

for ($i = 43; $i < 52; $i++) {

  $pdf->cell(100, $alt, $aLinhasRelatorio[$i]["label"], 'R', 0, "L", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano4"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano3"], "f"), 'LR', 0, "R", 0);
  $pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[$i]["ano2"], "f"), 'L', 1, "R", 0);

}
$pdf->cell(190, $alt, "", 'T', 1, "L", 0);

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "RESERVA ORÇAMENTÁRIA DO RPPS", 'TR', 0, "L", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[52]["ano4"], "f"), 'TLR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[52]["ano3"], "f"), 'TLR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[52]["ano2"], "f"), 'TL', 1, "R", 0);

$pdf->setfont('arial', 'b', 7);
$pdf->cell(100, $alt, "BENS E DIREITOS DO RPPS", 'TR', 0, "L", 0);
$pdf->setfont('arial', '', 7);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[53]["ano4"], "f"), 'TLR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[53]["ano3"], "f"), 'TLR', 0, "R", 0);
$pdf->cell(30, $alt, db_formatar($aLinhasRelatorio[53]["ano2"], "f"), 'TL', 1, "R", 0);
$pdf->cell(190, $alt, "", 'T', 1, "L", 0);
$oRelatorio->getNotaExplicativa($pdf, 1);
$pdf->Output();