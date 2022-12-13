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

$sNomeArquivo = 'emissao' . date('YmdHis') . '.csv';
$sArquivo     = "tmp/$sNomeArquivo";
$fArquivo     = fopen($sArquivo, 'w');


/**
 * Array contendo as Colunas para cada grupo
 */
$aColunas = array();

/**
 * Array com as colunas do total geral
 */
$aTotalGeral = array();
$aTotalGeral[] = "TOTAL GERAL: ";

/**
 * Coluna contendo o grupo
 */
$aColunas[] = "Grupo";
/**
 * Verifica se deve ser exibido somente os 
 * totais ou os dados dos servidores
 */
if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {

  /**
   * Percorre todos os campos selecionados, 
   * criando suas respectivas colunas
   */
  foreach ($aCamposRelatorios as $oCampo) {
    $aColunas[]    = $oCampo->rotulorel;
  }
}
/**
 * Percorremos as Rubricas Montando os Cabecalhos
 */

foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {
  $tipoRubrica = $aTipoValorRubricas[$sCodigoRubrica] == 'V' ? ' (Val)' : ' (Quant)';
  $aColunas[] = $sCodigoRubrica . $tipoRubrica;
}

$aColunas[] = "TOTAL";

/**
 * Insere ao CSV as colunas necessárias para cada Grupo
 */
$lEscritaArquivo = fputcsv($fArquivo, $aColunas, ";");


$iTotalGeral = 0.0; 
ksort($aServidores);

foreach ( $aServidores as $sGrupo => $aDadosServidores ) {

  if (count($aDadosServidores) == 0) {
    continue;
  }

  $sNomeGrupo = $aQuebras[$sGrupo];
  if ($sNomeGrupo == '1 - 1') {
    $sNomeGrupo = '1 - Geral';
  }

  $aTotalRubricaGrupo  = array();
  $iTotalGrupoRubricas = 0;

  /**
   * Escreve o nome do grupoi quando for para exibir somente totais
   */
  if ($oParametros->sSomenteTotais == EXIBIR_SOMENTE_TOTAIS) {
    $aColunasServidores   = array();
    $aColunasServidores[] = $sNomeGrupo;
    $lEscritaArquivo      = fputcsv($fArquivo, $aColunasServidores, ";");
  }

  /**
   * Percorre cada servidor do grupo escrevendo seus dados
   */
  foreach ($aDadosServidores as $iMatricula => $oServidor) {

    /**
     * Recebe os dados dos servidores
     */
    $aColunasServidores = array();
    $sValorTotal   = $aValorRubricas[$iMatricula]["TOTAL"];
    $sNomeServidor = $oServidor->getCgm()->getNome();

    /**
     * Verifica se deve ser exibido somente os 
     * totais ou os dados dos servidores
     */
    if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {

      $aColunasServidores[] = $sNomeGrupo;
      /**
       * Percorre todos os campos selecionados, 
       * criando suas respectivas colunas
       */
      foreach ($aCamposRelatorios as $oCampo) {

        $oDadosServidor = $aDadosServidor[$iMatricula];
        $sCampo         = $oCampo->rh120_campo;
        $sValorCampo    = $oDadosServidor->$sCampo;

        /**
         *  Verifica se o campo é do tipo data, se for formada ele para o formato Brasileiro
         */
        if ( $oCampo->conteudo == 'date') {
          $sValorCampo = db_formatar($sValorCampo, 'd');
        }

        /**
         * Verifica se o valor é do tipo Float e adiciona a mascara de valor
         */
        if ( $oCampo->conteudo == 'float8') {
          $sValorCampo = db_formatar($sValorCampo, 'f');
        }
        $aColunasServidores[]   = $sValorCampo;
      }
    }
    /**
     * Percorre as Rubricas dos Servidores escrevendo os valores
     */
    foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

      $nValorRubrica = 0.00;
      /**
      * Define Valror se Houver Rubrica
      */
      if ( isset($aValorRubricas[$iMatricula][$sCodigoRubrica]) ) {
        $nValorRubrica   = $aValorRubricas[$iMatricula][$sCodigoRubrica];
      }

      $aTotalRubricaGrupo[$sCodigoRubrica] += $nValorRubrica;
      $aTotalRubrica[$sCodigoRubrica]      += $nValorRubrica;
      $sValorRubrica                        = $nValorRubrica;

      /**
       * Verifica se deve ser exibido somente os 
       * totais ou os dados dos servidores
       */
      if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) { 
        $aColunasServidores[]   = db_formatar($sValorRubrica, 'f');
      }
    }

    /**
     * Verifica se deve ser exibido somente os 
     * totais ou os dados dos servidores
     */
    if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {
      $aColunasServidores[]   = db_formatar($sValorTotal, 'f');;

      /**
       * Insere ao CSV as colunas com os dados de cada servidor
       */
      $lEscritaArquivo = fputcsv($fArquivo, $aColunasServidores, ";");
    }

    $iTotalGeral += $aValorRubricas[$iMatricula]["TOTAL"];
    $iTotalGrupoRubricas += $aValorRubricas[$iMatricula]["TOTAL"];
  }


  /**
   * Monta os totais para o Grupo
   */
  $aColunasTotalGrupos   = array();
  $aColunasTotalGrupos[] = "Total: ";

  /**
   * Monta uma coluna em branco para cada campo
   */
  if ($oParametros->sSomenteTotais == NAO_EXIBIR_SOMENTE_TOTAIS) {

    for ($iIndiceCampos = 1; $iIndiceCampos <= count($aCamposRelatorios); $iIndiceCampos++) {
      $aColunasTotalGrupos[] = ' ';
      $aTotalGeral        [] = ' ';
    }
  }

  /**
   * Monta uma coluna para cada rubrica com seus respectivos totais
   */
  foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {
    $aColunasTotalGrupos[] = db_formatar($aTotalRubricaGrupo[$sCodigoRubrica], 'f');
  }

  $aColunasTotalGrupos[] = db_formatar($iTotalGrupoRubricas, 'f');

  $lEscritaArquivo = fputcsv($fArquivo, $aColunasTotalGrupos, ";");
  $ColunaQuebra = array();
  $lEscritaArquivo = fputcsv($fArquivo, $ColunaQuebra, ";");
}

/*
 * Monta os totais gerais.
 */
foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

  $nValorTotalRubrica = 0.00;

  if ( isset($aTotalRubrica[$sCodigoRubrica]) ) {
    $nValorTotalRubrica = $aTotalRubrica[$sCodigoRubrica];
  }

  $aTotalGeral[] =  db_formatar($nValorTotalRubrica, 'f');
}

$aTotalGeral[] = db_formatar($iTotalGeral, 'f');

/**
 * Insere ao CSV as colunas com o Total
 */
$lEscritaArquivo = fputcsv($fArquivo, $aTotalGeral, ";");

$aColunasLegenda = array();
$lEscritaArquivo = fputcsv($fArquivo, $aColunasLegenda, ";");

$aColunasLegenda = array();
$aColunasLegenda[] = 'LEGENDA';
$lEscritaArquivo = fputcsv($fArquivo, $aColunasLegenda, ";");
/**
 * Escrevendo a Legenda
 */
$aColunasLegenda = array();
$aColunasLegenda[] = 'Variável';
$aColunasLegenda[] = 'Rubrica';
$aColunasLegenda[] = 'Descrição da Rubrica';
$aColunasLegenda[] = 'Tipo';

$lEscritaArquivo = fputcsv($fArquivo, $aColunasLegenda, ";");

foreach ( $aOrdemFormula as $sDescricao => $sCodigoRubrica ) {

  $aColunasLegenda = array();  
  $oRubrica        = RubricaRepository::getInstanciaByCodigo($sCodigoRubrica);
  $sTipo           = $oRubrica->getTipo() == Rubrica::TIPO_PROVENTO ? "Provento" : "Desconto";

  $aColunasLegenda[] = $sDescricao;
  $aColunasLegenda[] = $sCodigoRubrica;
  $aColunasLegenda[] = $oRubrica->getDescricao();
  $aColunasLegenda[] = $sTipo;
  $lEscritaArquivo = fputcsv($fArquivo, $aColunasLegenda, ";");
}

if (!$lEscritaArquivo) {
  throw new Exception("Erro ao Escrever arquivo CSV");
}

fclose($fArquivo);