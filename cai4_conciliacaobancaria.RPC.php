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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("libs/exceptions/ParameterException.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';
$sCaminhoMensagens = 'financeiro.caixa.cai4_conciliacaobancaria';
try {

  switch($oParam->exec) {

    case 'GetDadosExtrato':

      $oRetorno->aLinhasExtrato = array();

      $aWhere   = array();
      $aWhere[] = " not exists(select 1 from conciliaextrato where k87_extratolinha = k86_sequencial) ";
      $aWhere[] = " not exists(select 1 from conciliapendextrato where k88_extratolinha = k86_sequencial) ";

      if (!empty($oParam->iCodigoExtrato)) {
        $aWhere[] = "k86_extrato = {$oParam->iCodigoExtrato} ";
      }

      if (!empty($oParam->iCodigoContaBancaria)) {
        $aWhere[] = "k86_contabancaria = {$oParam->iCodigoContaBancaria} ";
      }

      if (!empty($oParam->dtProcessamentoInicial)) {

        $oDataProcessamentoInicial = new DBDate($oParam->dtProcessamentoInicial);
        $aWhere[] = "k85_dtproc >= '{$oDataProcessamentoInicial->getDate()}'";
      }

      if (!empty($oParam->dtProcessamentoFinal)) {

        $oDataProcessamentoFinal = new DBDate($oParam->dtProcessamentoFinal);
        $aWhere[] = "k85_dtproc <= '{$oDataProcessamentoFinal->getDate()}'";
      }

      if (!empty($oParam->dtArquivoInicial)) {

        $oDataArquivoInicial = new DBDate($oParam->dtArquivoInicial);
        $aWhere[] = "k85_dtarq >= '{$oDataArquivoInicial->getDate()}'";
      }

      if (!empty($oParam->dtArquivoFinal)) {

        $oDataArquivoFinal = new DBDate($oParam->dtArquivoFinal);
        $aWhere[] = "k85_dtarq <= '{$oDataArquivoFinal->getDate()}'";
      }

      $oDaoExtratoLinha = new cl_extratolinha();
      $sListaCampos     = "k86_sequencial as codigo_linha,";
      $sListaCampos    .= "k86_extrato as codigo_extrato,";
      $sListaCampos    .= "k86_contabancaria as conta_bancaria,";
      $sListaCampos    .= "k86_data as data,";
      $sListaCampos    .= "k86_valor as valor,";
      $sListaCampos    .= "k86_tipo as tipo,";
      $sListaCampos    .= "k86_historico as historico";

      $sWhereLinhasExtrato = implode(" and ", $aWhere);
      $sSqlLinhasExtrato   = $oDaoExtratoLinha->sql_query(null,
                                                          $sListaCampos,
                                                          "k86_sequencial",
                                                          $sWhereLinhasExtrato
                                                         );

      $rsLinhasExtrato = $oDaoExtratoLinha->sql_record($sSqlLinhasExtrato);
      if (!$rsLinhasExtrato) {
        throw new BusinessException(_M("{$sCaminhoMensagens}.sem_linhas_para_exclusao"));
      }
      $iTotalLinhasExtrato = $oDaoExtratoLinha->numrows;
      $aContasBancarias    = array();
      for ($iLinha = 0; $iLinha < $iTotalLinhasExtrato; $iLinha++) {

        $oDadosLinha = db_utils::fieldsMemory($rsLinhasExtrato, $iLinha, false, false, true);

        if (!isset($aContasBancarias[$oDadosLinha->conta_bancaria])) {
          $aContasBancarias[$oDadosLinha->conta_bancaria] = new ContaBancaria($oDadosLinha->conta_bancaria);
        }

        $oContaBancaria = $aContasBancarias[$oDadosLinha->conta_bancaria];
        $oDadosLinha->descricao_conta_bancaria = urlencode($oContaBancaria->getDadosConta());
        $oRetorno->aLinhasExtrato[]            = $oDadosLinha;
      }
      break;

    case 'Processar':

      if (!is_array($oParam->aLinhasExtrato)) {
        throw new ParameterException(_M("{$sCaminhoMensagens}.parametro_linhas_invalido"));
      }

      db_inicio_transacao();
      $oDaoExtratoLinha        = new cl_extratolinha();
      $aLinhasAgrupadasPorData = array();

      /**
       * Excluimos as linhas selecionadas pelo usuario.
       * após a exclusão das mesmas, é recalculado o saldo das contas bancarias envolvidas na exclusão.
       */
      foreach ($oParam->aLinhasExtrato as $iLinhaExtrato) {

        $sSqlDadosLinha  = $oDaoExtratoLinha->sql_query_file($iLinhaExtrato);
        $rsLinhasExtrato = $oDaoExtratoLinha->sql_record($sSqlDadosLinha);
        if ($oDaoExtratoLinha->numrows == 0) {

          $oParametroErro = (object) array("codigo_linha" => $iLinhaExtrato);
          throw new BusinessException(_M("{$sCaminhoMensagens}.linha_nao_encontrada", $oParametroErro));
        }

        $oDadosLinha = db_utils::fieldsMemory($rsLinhasExtrato, 0);
        if (!isset($aLinhasAgrupadasPorData[$oDadosLinha->k86_contabancaria])) {
          $aLinhasAgrupadasPorData[$oDadosLinha->k86_contabancaria] = array();
        }

        if (!in_array($oDadosLinha->k86_data, $aLinhasAgrupadasPorData[$oDadosLinha->k86_contabancaria])) {
          $aLinhasAgrupadasPorData[$oDadosLinha->k86_contabancaria][] = $oDadosLinha->k86_data;
        }

        $oDaoExtratoLinha->excluir($iLinhaExtrato);
        if ($oDaoExtratoLinha->erro_status == 0) {

          $oParametroErro = new stdClass();
          $oParametroErro->erro_tecnico   = $oDaoExtratoLinha->erro_banco;
          $oParametroErro->codigo_linha   = $iLinhaExtrato;
          $oParametroErro->codigo_extrato = $oDadosLinha->k86_extrato;
          $oParametroErro->valor_linha    = $oDadosLinha->k86_valor;
          throw new BusinessException(_M("{$sCaminhoMensagens}.erro_ao_excluir_linha", $oParametroErro));
        }

      }

      $oDaoExtrato = new cl_extratosaldo();
      /**
       * Ordenamos as datas de cada conta
       */
      $oDaoExtratoSaldo = new cl_extratosaldo();
      $sSqlSaldo        = "select * From extratosaldo where k97_extrato=1813";
      $rsSaldo          = db_query($sSqlSaldo);
      foreach ($aLinhasAgrupadasPorData as $iCodigoConta => &$aDatasConta) {
        usort($aLinhasAgrupadasPorData[$iCodigoConta], "ordernarDatasContas");
      }

      foreach ($aLinhasAgrupadasPorData as $iCodigoConta => $aDatasConta) {

        foreach ($aDatasConta as $sData) {
          $oDaoExtrato->recriarSaldo($iCodigoConta, $sData);
        }
      }
      $oDaoExtratoSaldo = new cl_extratosaldo();
      $sSqlSaldo        = "select * From extratosaldo where k97_extrato=1813";
      $rsSaldo          = db_query($sSqlSaldo);
      db_fim_transacao(false);
      break;
  }

} catch (ParameterException $oErro) {

    db_fim_transacao(true);
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

    db_fim_transacao(true);
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);

function ordernarDatasContas($aContaAtual, $aProximaConta) {

  $oDataAtual   = new DBDate($aContaAtual);
  $oProximaData = new DBDate($aProximaConta);
  return $oDataAtual->getTimeStamp() > $oProximaData->getTimeStamp() ? 1 : -1;
}