<?php
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

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoRepository as CobrancaRegistradaRetornoRepository;
use \ECidade\Tributario\Arrecadacao\BaixaDeBanco\TarifaBancaria\Repository as TarifaRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

db_app::import('exceptions.*');

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch ($oParam->exec) {

  case "Processar":

    $aRetorno = array();
    $aRetorno["status"]  = 1;
    $aRetorno["message"] = urlencode("Processamento efetuado com sucesso");

    try {

      db_inicio_transacao();

      /*
       * Tipos de Processamento
       * iTipoProcessamento == 1
       *   Exclusão do Arquivo, Autenticação, Classificação e Abatimentos
       *
       * iTipoProcessamento == 2
       *   Exclusão da Classificação, Abatimentos e Autenticação
       *
       * iTipoProcessamento == 3
       *   Exclusão apenas da Autenticação
       */
      switch ($oParam->iTipoProcessamento) {
        case 1: // exclui arquivo de retorno

          $oDaoDisCla         = db_utils::getDao("discla");
          $sSqlDiscla         = $oDaoDisCla->sql_query_file(null, "codcla", null, "codret = {$oParam->codret}");
          $rsDiscla           = $oDaoDisCla->sql_record($sSqlDiscla);
          $iQtdClassificacoes = $oDaoDisCla->numrows;
          if($iQtdClassificacoes > 0) {

            for($iClassificacao = 0; $iClassificacao < $iQtdClassificacoes; $iClassificacao++) {
              $iCodCla = db_utils::fieldsMemory($rsDiscla, $iClassificacao)->codcla;

              cancelaAutenticacao($iCodCla);
              cancelaClassificacao($iCodCla);

            }

          }

          excluiArquivoBaixaBanco($oParam->codret);

        break;
        case 2: // cancela classificação

          cancelaAutenticacao($oParam->codcla);
          cancelaClassificacao($oParam->codcla);

        break;
        case 3: // exclui autenticação

          cancelaAutenticacao($oParam->codcla);

        break;
      }

      db_fim_transacao(false);

    } catch (DBException $eErro) {          // DB Exception

      db_fim_transacao(true);
      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($eErro->getMessage());

    } catch (BusinessException $eErro) {     // Business Exception

      db_fim_transacao(true);
      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($eErro->getMessage());

    } catch (ParameterException $eErro) {     // Parameter Exception

      db_fim_transacao(true);
      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($eErro->getMessage());

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $aRetorno["status"]  = 2;
      $aRetorno["message"] = urlencode($eErro->getMessage());
    }

    echo $oJson->encode($aRetorno);

  break;

  case "validaProcessamento":

    /**
     * Excluir arquivo de retorno
     */
    $sWhere       = " disbanco.codret = {$oParam->codret} or discla.codret = {$oParam->codret}";
    $sWhereDisCla = " discla.codret = {$oParam->codret}";

    /**
     * Cancelar Classificação
     */
    if($oParam->iTipoProcessamento == 2 || $oParam->iTipoProcessamento == 3) {

      $sWhere       = " discla.codcla = {$oParam->codcla}";
      $sWhereDisCla = " discla.codcla = {$oParam->codcla}";
    }

    $sSqlValida  = "select ( select dtaute                                                                            \n";
    $sSqlValida .= "           from discla                                                                            \n";
    $sSqlValida .= "          where {$sWhereDisCla} limit 1) as arqautent,                                            \n";

    $sSqlValida .= "         ( select q44_issarqsimplesreg                                                            \n";
    $sSqlValida .= "             from issarqsimplesregdisbanco                                                        \n";
    $sSqlValida .= "                  inner join disbanco on q44_disbanco    = disbanco.idret                         \n";
    $sSqlValida .= "                  left join discla   on disbanco.codret = discla.codret                           \n";
    $sSqlValida .= "            where {$sWhere} limit 1) as arqsimples,                                               \n";

    $sSqlValida .= "         ( select c77_databol                                                                     \n";
    $sSqlValida .= "             from conlancambol                                                                    \n";
    $sSqlValida .= "            where c77_instit  = ".db_getsession("DB_instit")."                                    \n";
    $sSqlValida .= "              and c77_databol = (select k12_data                                                  \n";
    $sSqlValida .= "                                   from corcla                                                    \n";
    $sSqlValida .= "                                        inner join discla on corcla.k12_codcla = discla.codcla    \n";
    $sSqlValida .= "                                  where {$sWhereDisCla} limit 1) limit 1) as boletim_processado,  \n";

    $sSqlValida .= "         ( select k11_data                                                                        \n";
    $sSqlValida .= "             from boletim                                                                         \n";
    $sSqlValida .= "            where k11_instit = ".db_getsession("DB_instit")."                                     \n";
    $sSqlValida .= "              and k11_data = ( select k12_data                                                    \n";
    $sSqlValida .= "                                 from corcla                                                      \n";
    $sSqlValida .= "                                      inner join discla on corcla.k12_codcla = discla.codcla      \n";
    $sSqlValida .= "                                      where {$sWhereDisCla} limit 1)                              \n";
    $sSqlValida .= "              and k11_libera is true limit 1 ) as boletim_liberado,                               \n";

    $sSqlValida .= "         ( select q145_issarquivoretencao                                                         \n";
    $sSqlValida .= "             from issarquivoretencaodisarq                                                        \n";
    $sSqlValida .= "                  inner join disbanco on q145_disarq     = disbanco.codret                        \n";
    $sSqlValida .= "                  left join discla    on disbanco.codret = discla.codret                          \n";
    $sSqlValida .= "            where {$sWhere} limit 1 ) as arquivo_retencao                                         \n";

    $rsValida = db_query($sSqlValida);

    $oRetorno = new stdClass;
    $oRetorno = db_utils::fieldsMemory($rsValida, 0, false, false, true);

    echo $oJson->encode($oRetorno);

  break;
}

function cancelaAutenticacao($iCodCla) {

  if ( !db_utils::inTransaction() ) {
  	throw new Exception("Nenhuma transação com o banco de dados encontrada!");
  }

  if (empty($iCodCla)) {
     throw new ParameterException("Código da Classificação não declarada!");
  }

  $oDaoDiscla                  = new cl_discla();
  $oDaoCorcla                  = new cl_corcla();
  $oDaoCorrenteId              = new cl_correnteid();
  $oDaoCorNump                 = new cl_cornump();
  $oDaoCorNumpDesconto         = new cl_cornumpdesconto();
  $oDaoCorAutent               = new cl_corautent();
  $oDaoCorrente                = new cl_corrente();
  $oDaoConciliaCor             = new cl_conciliacor();
  $oDaoConLancamCgm            = new cl_conlancamcgm();
  $oDaoConLancamRec            = new cl_conlancamrec();
  $oDaoConLancamCompl          = new cl_conlancamcompl();
  $oDaoConLancamDoc            = new cl_conlancamdoc();
  $oDaoConLancamCorrente       = new cl_conlancamcorrente();
  $oDaoConLancamPag            = new cl_conlancampag();
  $oDaoConLancamVal            = new cl_conlancamval();
  $oDaoConLancamConCarpeculiar = new cl_conlancamconcarpeculiar();
  $oDaoConLancamInstit         = new cl_conlancaminstit();
  $oDaoConLancamOrdem          = new cl_conlancamordem();
  $oDaoConLancam               = new cl_conlancam();
  $oDaoCorHist                 = new cl_corhist();
  $oDaoCorAutent               = new cl_corautent();
  $oDaoConDataConf             = new cl_condataconf();
  $oDaoContaCorrenteDetalhe    = new cl_contacorrentedetalheconlancamval();

  $sSqlAutenticacao = $oDaoCorcla->sql_query_file(null, null, null, "*", null, "k12_codcla = {$iCodCla}");
  $rsAutenticacao   = $oDaoCorcla->sql_record($sSqlAutenticacao);
  $iQtdRegistros    = $oDaoCorcla->numrows;

  if ($iQtdRegistros > 0) {

    for ($iAutenticacao = 0; $iAutenticacao < $iQtdRegistros; $iAutenticacao++) {

      $oAutenticacao = db_utils::fieldsMemory($rsAutenticacao, $iAutenticacao);

      $sWhere = "    c99_data   >= '{$oAutenticacao->k12_data}'::date
                 and c99_instit  = " . db_getsession('DB_instit');
      $sSqlValidaFechamentoContabilidade = $oDaoConDataConf->sql_query(null, null, '*', null, $sWhere);
      $rsValidaFechamentoContabilidade   = $oDaoConDataConf->sql_record($sSqlValidaFechamentoContabilidade);
      if ($oDaoConDataConf->numrows > 0) {

          $sMsg  = "Operação não permitida!\n";
          $sMsg .= "A data de encerramento da contabilidade é posterior a data de autenticação.\n";
          throw new Exception($sMsg);
      }

      /**
       * Excluimos os lancamentos contabeis vinculados a classificacao
       */
      $sWhere  = "    c86_id     = {$oAutenticacao->k12_id}     ";
      $sWhere .= "and c86_autent = {$oAutenticacao->k12_autent} ";
      $sWhere .= "and c86_data   = '{$oAutenticacao->k12_data}' ";
      $sSqlLancamentos = $oDaoConLancamCorrente->sql_query(null, 'c86_conlancam' , null, $sWhere);
      $rsLancamentos   = $oDaoConLancamCorrente->sql_record( $sSqlLancamentos );
      $oLancamentosVinculados = db_utils::getCollectionByRecord( $rsLancamentos );

      foreach ($oLancamentosVinculados as $iIndice => $oLancamento) {

        $oDaoConLancamCgm->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancamCgm->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamCgm gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamCgm->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamRec->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancamRec->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamRec gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamRec->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamCompl->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancamCompl->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamCompl gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamCompl->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamDoc->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancamDoc->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamDoc gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamDoc->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $sWhere  = "    c86_id     = {$oAutenticacao->k12_id}     ";
        $sWhere .= "and c86_autent = {$oAutenticacao->k12_autent} ";
        $sWhere .= "and c86_data   = '{$oAutenticacao->k12_data}' ";
        $oDaoConLancamCorrente->excluir(null, $sWhere);
        if ($oDaoConLancamCorrente->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamCorrente gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamDoc->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamPag->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancamPag->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamPag gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamPag->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $sWhereContaCorrenteDetalhe = " c28_conlancamval in (select c69_sequen from conlancamval where c69_codlan = {$oLancamento->c86_conlancam}) ";
        $oDaoContaCorrenteDetalhe->excluir(null, $sWhereContaCorrenteDetalhe);
        if ($oDaoContaCorrenteDetalhe->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela contacorrentedetalheconlancamval gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoContaCorrenteDetalhe->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }


        $oDaoConLancamVal->excluir(null, " c69_codlan = {$oLancamento->c86_conlancam} ");
        if ($oDaoConLancamVal->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamVal gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamVal->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamConCarpeculiar->excluir(null, " c08_codlan = {$oLancamento->c86_conlancam} ");
        if ($oDaoConLancamConCarpeculiar->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamConCarpeculiar gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamConCarpeculiar->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamInstit->excluir(null, " c02_codlan = {$oLancamento->c86_conlancam} ");
        if ($oDaoConLancamInstit->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamInstit gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamInstit->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancamOrdem->excluir(null, " c03_codlan = {$oLancamento->c86_conlancam} ");
        if ($oDaoConLancamOrdem->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancamOrdem gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancamOrdem->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoConLancam->excluir($oLancamento->c86_conlancam);
        if ($oDaoConLancam->erro_status == "0") {

          $sMsg  = "Erro ao excluir dados da tabela ConLancam gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
          $sMsg .= "Erro: {$oDaoConLancam->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

      }

      $oDaoCorHist->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorHist->erro_status == "0") {

        $sMsg  = "Erro ao excluir dados da tabela CorHist gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorHist->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $oDaoCorAutent->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorAutent->erro_status == "0") {

        $sMsg  = "Erro ao excluir dados da tabela CorAutent gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorAutent->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $sWhere  = "    k84_id     = {$oAutenticacao->k12_id}     ";
      $sWhere .= "and k84_autent = {$oAutenticacao->k12_autent} ";
      $sWhere .= "and k84_data   = '{$oAutenticacao->k12_data}' ";
      $oDaoConciliaCor->sql_record($oDaoConciliaCor->sql_query_file(null, "k84_sequencial", null, $sWhere));
      if ($oDaoConciliaCor->numrows > 0) {
          $sMsg  = "[ 0 ] - Procedimento não poderá ser realizado!\n";
          $sMsg .= "Foram encontrados registros de conciliação bancária para a autenticação.\n";
          throw new Exception($sMsg);
      }

      $oDaoCorcla->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorcla->erro_status == "0") {

        $sMsg  = "[ 1 ] - Erro ao excluir dados da tabela Corcla gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorcla->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $sWhere  = "k56_id = {$oAutenticacao->k12_id}";
      $sWhere .= "and k56_autent = {$oAutenticacao->k12_autent}";
      $sWhere .= "and k56_data = '{$oAutenticacao->k12_data}'";
      $oDaoCorrenteId->excluir(null, $sWhere);
      if ($oDaoCorrenteId->erro_status == "0") {

        $sMsg  = "[ 2 ] - Erro ao excluir dados da tabela CorrenteId gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorrenteId->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $oDaoCorNumpDesconto->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorNumpDesconto->erro_status == "0") {

        $sMsg  = "[ 3 ] - Erro ao excluir dados da tabela CorNumpDesconto gerados pela baixa do arquivo na classificação {$iCodcla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorNumpDesconto->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $oDaoCorNump->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorNump->erro_status == "0") {

        $sMsg  = "[ 4 ] - Erro ao excluir dados da tabela Cornump gerados pela baixa do arquivo na classificação {$iCodcla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorNump->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

      $oDaoCorAutent->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorAutent->erro_status == "0") {

        $sMsg  = "[ 5 ] - Erro ao excluir dados da tabela CorAutent gerados pela baixa do arquivo na classificação {$iCodcla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorAutent->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);

      }

      /**
       * Verifica se existe registro na conciliapendcorrente, se existir não pode ser deletado da Corrente
       */
      $sWhere = "k89_id = {$oAutenticacao->k12_id} and k89_data = '{$oAutenticacao->k12_data}' and k89_autent = {$oAutenticacao->k12_autent}";
      $oDaoConciliaPendCorrente = db_utils::getDao('conciliapendcorrente');
      $sSqlConciliaPendCorrente = $oDaoConciliaPendCorrente->sql_query_file(null, "*", null, $sWhere);

      $rsConciliaPendCorrente   = db_query($sSqlConciliaPendCorrente);

      if (pg_num_rows($rsConciliaPendCorrente) > 0) {

        $sMsg  = "[ 6 ] - Procedimento não poderá ser realizado!\n";
        $sMsg .= "Foram encontrados registros de conciliação bancária Pendentes.\n";
        throw new Exception($sMsg);
      }

      $oDaoCorrente->excluir($oAutenticacao->k12_id, $oAutenticacao->k12_data, $oAutenticacao->k12_autent);
      if ($oDaoCorrente->erro_status == "0") {

        $sMsg  = "[ 7 ] - Erro ao excluir dados da tabela Corrente gerados pela baixa do arquivo na classificação {$iCodCla}.\n\n";
        $sMsg .= "Erro: {$oDaoCorrente->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

    }

  }

  $oDaoDiscla->dtaute = "null";
  $oDaoDiscla->codcla = $iCodCla;
  $oDaoDiscla->alterar($iCodCla);
  if ($oDaoDiscla->erro_status == "0") {

    $sMsg  = "[ 8 ] - Erro ao alterar a data da autenticação da discla.\n\n";
    $sMsg .= "Erro: {$oDaoDiscla->erro_msg} - ".str_replace('"',"\'",pg_last_error());
    throw new Exception($sMsg);
  }

  return true;

}

function cancelaClassificacao($iCodCla) {

  if ( !db_utils::inTransaction() ) {
  	throw new Exception("Nenhuma transação com o banco de dados encontrada!");
  }

  if(empty($iCodCla)) {
    throw new ParameterException("Código da classificação não declarado!");
  }

  $oDaoDisCla     = new cl_discla();
  $oDaoDisBanco   = new cl_disbanco();
  $oDaoArrecant   = new cl_arrecant();
  $oDaoArrecad    = new cl_arrecad();
  $oDaoArrePaga   = new cl_arrepaga();
  $oDaoArreIdRet  = new cl_arreidret();
  $oDaoDisRec     = new cl_disrec();
  $oDaoReciboPaga = new cl_recibopaga();

  $sSqlDados  = " select distinct                                                                       \n";
  $sSqlDados .= "        disrec.idret,                                                                  \n";
  $sSqlDados .= "        disbanco.k00_numpre  as disbanco_numpre,                                       \n";
  $sSqlDados .= "        disbanco.k00_numpar  as disbanco_numpar,                                       \n";
  $sSqlDados .= "        arreidret.k00_numpre as numpre,                                                \n";
  $sSqlDados .= "        arreidret.k00_numpar as numpar,                                                \n";
  $sSqlDados .= "        k132_abatimento      as abatimento,                                            \n";
  $sSqlDados .= "        corcla.k12_codcla    as autenticado,                                           \n";
  $sSqlDados .= "        (select true                                                                   \n";
  $sSqlDados .= "           from recibo                                                                 \n";
  $sSqlDados .= "          where recibo.k00_numpre = arreidret.k00_numpre                               \n";
  $sSqlDados .= "             or recibo.k00_numpre = disbanco.k00_numpre limit 1)   as recibo_avulso    \n";
  $sSqlDados .= "   from discla                                                                         \n";
  $sSqlDados .= "        inner join disbanco           on disbanco.codret               = discla.codret \n";
  $sSqlDados .= "        inner join disrec             on disrec.codcla                 = discla.codcla \n";
  $sSqlDados .= "                                     and disrec.idret                  = disbanco.idret\n";
  $sSqlDados .= "         left join arreidret          on arreidret.idret               = disrec.idret  \n";
  $sSqlDados .= "         left join abatimentodisbanco on abatimentodisbanco.k132_idret = disrec.idret  \n";
  $sSqlDados .= "         left join corcla             on corcla.k12_codcla             = discla.codcla \n";
  $sSqlDados .= "  where discla.codcla = {$iCodCla}                                                       ";
  $rsDados    = $oDaoDisCla->sql_record($sSqlDados);

  $iQtdRegistros = $oDaoDisCla->numrows;
  if ($iQtdRegistros > 0) {

    for ($iIdRet = 0; $iIdRet < $iQtdRegistros; $iIdRet++) {

      $oRegistros = db_utils::fieldsMemory($rsDados, $iIdRet);

      if (empty($oRegistros->numpre) ) {
        $sMsg  = "[ 1 ] - Procedimento não poderá ser realizado!\n";
        $sMsg .= "Não foram encontrados registros dos numpres de origem para cancelar a classificação (arreidret).\n";
        throw new BusinessException($sMsg);
      }

      /**
       * Realizamos o tratamento do numpre e parcela para exclusão do débito na arrepaga
       *
       * Caso o numpre e parcela não forem nulos, utilizamos esses dados para exclusão do débito do arrepaga
       * Caso contrário o numpre se trata de um recibo e deve ser utilizado somente o numpre para exclusão
       *
       */
       if ($oRegistros->numpre != "" && $oRegistros->numpar != "") {
         $sWhere = "k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}";
       } else if (  $oRegistros->disbanco_numpre != "" ) {
         $sWhere = "k00_numpre = {$oRegistros->disbanco_numpre}";
       }
       $oDaoArrePaga->excluir(null, $sWhere);
       if ($oDaoArrePaga->erro_status == "0") {

         $sMsg  = "[ 2 ] - Erro ao excluir os pagamentos realizados pela baixa do arquivo na classificação {$iCodCla} (Arrepaga).\n\n";
         $sMsg .= "Erro: {$oDaoArrePaga->erro_msg} - ".str_replace('"',"\'",pg_last_error());
         throw new DBException($sMsg);

       }

       /*
        * Caso o registro se trate de um recibo avulso, este não terá arrecant gerado, somenta arrepaga
        */
       if ($oRegistros->recibo_avulso != 't') {

         $sWhere = "k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}";
         $sOrder = "k00_numpre, k00_numpar, k00_receit";
         $sSqlArrecant          = $oDaoArrecant->sql_query_file(null, "*", $sOrder, $sWhere);
         $rsArrecant            = $oDaoArrecant->sql_record($sSqlArrecant);
         $iQtdRegistrosArrecant = $oDaoArrecant->numrows;
         if ($oDaoArrecant->numrows == 0) {
           $sMsg  = "Procedimento Abortado!\n";
           $sMsg .= "Nenhum registro encontrado na tabela arrecant para o numpre {$oRegistros->numpre} parcela {$oRegistros->numpar} Idret: {$oRegistros->idret}\n";
           $sMsg .= "Sem essa informação não é possível retornar o débito";
           throw new DBException($sMsg);
         }

         for($iArrecant = 0; $iArrecant < $iQtdRegistrosArrecant; $iArrecant++) {
            $oDadosArrecant = db_utils::fieldsMemory($rsArrecant, $iArrecant);

            $oDaoArrecad->k00_numpre = $oDadosArrecant->k00_numpre;
            $oDaoArrecad->k00_numpar = $oDadosArrecant->k00_numpar;
            $oDaoArrecad->k00_numcgm = $oDadosArrecant->k00_numcgm;
            $oDaoArrecad->k00_dtoper = $oDadosArrecant->k00_dtoper;
            $oDaoArrecad->k00_receit = $oDadosArrecant->k00_receit;
            $oDaoArrecad->k00_hist   = $oDadosArrecant->k00_hist  ;
            $oDaoArrecad->k00_valor  = $oDadosArrecant->k00_valor ;
            $oDaoArrecad->k00_dtvenc = $oDadosArrecant->k00_dtvenc;
            $oDaoArrecad->k00_numtot = $oDadosArrecant->k00_numtot;
            $oDaoArrecad->k00_numdig = "".($oDadosArrecant->k00_numdig+0)."";
            $oDaoArrecad->k00_tipo   = $oDadosArrecant->k00_tipo  ;
            $oDaoArrecad->k00_tipojm = $oDadosArrecant->k00_tipojm;
            $oDaoArrecad->incluir();
            if ($oDaoArrecad->erro_status == "0") {

               $sMsg  = "[ 3 ] - Erro ao retornar os pagamentos realizados pela baixa do arquivo na classificação {$iCodCla}.\\n\\n";
               $sMsg .= "Erro: {$oDaoArrecad->erro_msg}";
               throw new DBException($sMsg);

            }
          }

          $oDaoArrecant->excluir(null,"k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}");
          if ($oDaoArrecant->erro_status == "0") {

             $sMsg  = "[ 4 ] - Erro ao retornar os pagamentos realizados pela baixa do arquivo na classificação {$iCodCla}.\\n\\n";
             $sMsg .= "Erro: {$oDaoArrecant->erro_msg} - ".str_replace('"',"\'",pg_last_error());
             throw new DBException($sMsg);

          }
        }

        $oDaoArreIdRet->excluir(null, null, "idret = {$oRegistros->idret}");
        if ($oDaoArreIdRet->erro_status == "0") {

          $sMsg  = "[ 5 ] - Erro ao excluir dados da tabela Arreidret gerados pela baixa do arquivo na classificação {$iCodCla} (Arreidret).\n\n";
          $sMsg .= "Erro: {$oDaoArreIdRet->erro_msg} - ".str_replace('"',"\'",str_replace('"',"\'",pg_last_error()));
          throw new DBException($sMsg);

        }

        /*
         * Excluímos os registros da disrec
         */
        $oDaoDisRec->excluir(null, "codcla = {$iCodCla}");
        if ($oDaoDisRec->erro_status == "0") {

          $sMsg  = "[ 6 ] - Erro ao excluir dados da tabela Disrec gerados pela baixa do arquivo na classificação {$iCodCla} (Disrec).\n\n";
          $sMsg .= "Erro: {$oDaoDisRec->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new DBException($sMsg);

        }

        /*
         * Excluímos os registros da discla
         */
        $oDaoDisCla->excluir($iCodCla);
        if ($oDaoDisCla->erro_status == "0") {

          $sMsg  = "[ 7 ] - Erro ao excluir dados da tabela Discla gerados pela baixa do arquivo na classificação {$iCodCla} (Discla).\n\n";
          $sMsg .= "Erro: $oDaoDisCla->{erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new DBException($sMsg);

        }

        /*
         * Alteramos a conta e a data de pagamento dos recibos da baixa como se não tivessem sido classificados
         */
        if ( $oRegistros->disbanco_numpre != "") {
          $oDaoReciboPaga->k00_conta  = "0";
          $oDaoReciboPaga->alterar(null, "k00_numnov = {$oRegistros->disbanco_numpre}");
          if ($oDaoReciboPaga->erro_status == "0") {

            $sMsg  = "[ 8 ] - Erro ao alterar dados da tabela Recibopaga {$iCodCla}.\n\n";
            $sMsg .= "Erro: {$oDaoReciboPaga->erro_msg} -".str_replace('"',"\'",pg_last_error());
            throw new DBException($sMsg);

          }
        }

        $oDaoDisBanco->idret      = $oRegistros->idret;
        $oDaoDisBanco->classi     = "false";
        $oDaoDisBanco->alterar($oRegistros->idret);
        if ($oDaoDisBanco->erro_status == "0") {

             $sMsg  = "[ 9 ] - Erro ao excluir dados da tabela Disbanco gerados pela baixa do arquivo na classificação {$iCodCla} (Disbanco).\n\n";
             $sMsg .= "Erro: {$oDaoDisBanco->erro_msg} - ".str_replace('"',"\'",pg_last_error());
             throw new DBException($sMsg);

        }

      if(!empty($oRegistros->abatimento)) {
        excluiAbatimento($oRegistros->abatimento);
      }

    }

  }

  return true;
}

function excluiAbatimento($iAbatimento) {

  if ( !db_utils::inTransaction() ) {
  	throw new Exception("Nenhuma transação com o banco de dados encontrada!");
  }

  if (empty($iAbatimento)) {
    throw new ParameterException("Código do Abatimento não declarado");
  }

  $oDaoAbatimento                         = new cl_abatimento();
  $oDaoArreCantPgtoParcial                = new cl_arrecantpgtoparcial();
  $oDaoArreCad                            = new cl_arrecad();
  $oDaoArrePaga                           = new cl_arrepaga();
  $oDaoRecibo                             = new cl_recibo();
  $oDaoArreHist                           = new cl_arrehist();
  $oDaoArreNumcgm                         = new cl_arrenumcgm();
  $oDaoArreMatric                         = new cl_arrematric();
  $oDaoArreInscr                          = new cl_arreinscr();
  $oDaoAbatimentoRecibo                   = new cl_abatimentorecibo();
  $oDaoAbatimentoArrecKeyArrecadCompos    = new cl_abatimentoarreckeyarrecadcompos();
  $oDaoAbatimentoArrecKey                 = new cl_abatimentoarreckey();
  $oDaoArreCadCompos                      = new cl_arrecadcompos();
  $oDaoArrecKey                           = new cl_arreckey();
  $oDaoAbatimentoDisbanco                 = new cl_abatimentodisbanco();
  $oDaoDisBanco                           = new cl_disbanco();

  $sSqlDadosAbatimento  = " select k132_idret                           as idret,                                                          \n";
  $sSqlDadosAbatimento .= "        k132_abatimento                      as abatimento,                                                     \n";
  $sSqlDadosAbatimento .= "        k128_sequencial,                                                                                        \n";
  $sSqlDadosAbatimento .= "        k128_arreckey                        as arreckey,                                                       \n";
  $sSqlDadosAbatimento .= "        arreckey.k00_numpre                  as arreckey_numpre,                                                \n";
  $sSqlDadosAbatimento .= "        arreckey.k00_numpar                  as arreckey_numpar,                                                \n";
  $sSqlDadosAbatimento .= "        arreckey.k00_receit                  as arreckey_receita,                                               \n";
  $sSqlDadosAbatimento .= "        coalesce(abatimentoarreckey.k128_valorabatido, 0) as vlrabatido,                                        \n";
  $sSqlDadosAbatimento .= "        arrecantpgtoparcial.k00_numpre       as arrecantpgtoparcial_numpre,                                     \n";
  $sSqlDadosAbatimento .= "        arrecantpgtoparcial.k00_numpar       as arrecantpgtoparcial_numpar,                                     \n";
  $sSqlDadosAbatimento .= "        abatimento.k125_tipoabatimento       as tipo_abatimento,                                                \n";
  $sSqlDadosAbatimento .= "        abatimentorecibo.k127_sequencial,                                                                       \n";
  $sSqlDadosAbatimento .= "        abatimentorecibo.k127_numprerecibo,                                                                     \n";
  $sSqlDadosAbatimento .= "        abatimentorecibo.k127_numpreoriginal,                                                                   \n";
  $sSqlDadosAbatimento .= "        (select array_accum(x.idret)                                                                            \n";
  $sSqlDadosAbatimento .= "           from abatimentorecibo                                                                                \n";
  $sSqlDadosAbatimento .= "                inner join disbanco as x on x.k00_numpre = abatimentorecibo.k127_numprerecibo                   \n";
  $sSqlDadosAbatimento .= "          where abatimentorecibo.k127_numprerecibo       = disbanco.k00_numpre) as abatimento_idret             \n";
  $sSqlDadosAbatimento .= "   from abatimento                                                                                              \n";
  $sSqlDadosAbatimento .= "        inner join abatimentodisbanco  on abatimentodisbanco.k132_abatimento = abatimento.k125_sequencial       \n";
  $sSqlDadosAbatimento .= "        inner join disbanco            on disbanco.idret                     = abatimentodisbanco.k132_idret    \n";
  $sSqlDadosAbatimento .= "         left join abatimentoarreckey  on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial       \n";
  $sSqlDadosAbatimento .= "         left join arreckey            on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey \n";
  $sSqlDadosAbatimento .= "         left join arrecantpgtoparcial on arrecantpgtoparcial.k00_abatimento = abatimento.k125_sequencial       \n";
  $sSqlDadosAbatimento .= "         left join abatimentorecibo    on abatimentorecibo.k127_abatimento   = abatimento.k125_sequencial       \n";
  $sSqlDadosAbatimento .= "   where abatimento.k125_sequencial = {$iAbatimento}                                                            \n";

  $rsAbatimento        = $oDaoAbatimento->sql_record($sSqlDadosAbatimento);
  $iQtdRegistros       = $oDaoAbatimento->numrows;
  if ($iQtdRegistros > 0) {

    for($iInd = 0; $iInd < $iQtdRegistros; $iInd++) {

      $oRegistros = db_utils::fieldsMemory($rsAbatimento, $iInd);

      if (!empty($oRegistros->arrecantpgtoparcial_numpre)) {

        $oDaoArreCantPgtoParcial->excluir_arrecantpgtoparcial($oRegistros->arrecantpgtoparcial_numpre, $oRegistros->arrecantpgtoparcial_numpar, 0, true);
        if ($oDaoArreCantPgtoParcial->erro_status == "0") {

          $sMsg  = "[  4  ] - Erro ao retornar os registros de abatimento gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro: {$oDaoArreCantPgtoParcial->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      /**
       * Caso o abatimento for do tipo 1, pagamento parcial
       *   Somamos o valor abatido no arrecad
       */
      if ($oRegistros->tipo_abatimento == 1){

        $oDaoArreCad->k00_valor = " (k00_valor + {$oRegistros->vlrabatido}) ";
        $oDaoArreCad->alterar(null, " k00_numpre = {$oRegistros->arreckey_numpre} and k00_numpar = {$oRegistros->arreckey_numpar} and k00_receit = {$oRegistros->arreckey_receita}");
        if ($oDaoArreCad->erro_status == "0") {

          $sMsg  = "[ 4.1 ] - Erro ao alterar o valor do arrecad com os valores dos abatimentos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro: {$oDaoArreCad->erro_msg} - ".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      if (!empty($oRegistros->k127_sequencial)) {

        $oDaoArrePaga->excluir(null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoArrePaga->erro_status == "0") {

          $sMsg  = "[ 4.2 ] - Erro ao excluir os pagamentos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoRecibo->excluir(null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoRecibo->erro_status == "0") {

          $sMsg  = "[ 4.3 ] - Erro ao excluir os recibos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoArreHist->excluir(null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoArreHist->erro_status == "0") {

          $sMsg  = "[ 4.4 ] - Erro ao excluir os históricos dos débitos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoArreNumcgm->excluir(null, null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoArreNumcgm->erro_status == "0" ) {

          $sMsg  = "[ 4.5 ] - Erro ao excluir os vinculos com cgm dos débitos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoArreMatric->excluir(null, null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoArreMatric->erro_status == "0" ) {

          $sMsg  = "[ 4.6 ] - Erro ao excluir os vinculos com matriculas dos débitos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoArreInscr->excluir(null, null, "k00_numpre = {$oRegistros->k127_numprerecibo}");
        if ($oDaoArreInscr->erro_status == "0" ) {

          $sMsg  = "[ 4.7 ] - Erro ao excluir os vinculos com inscrições dos débitos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

        $oDaoAbatimentoRecibo->excluir($oRegistros->k127_sequencial);
        if ($oDaoAbatimentoRecibo->erro_status == "0") {

          $sMsg  = "[ 4.8 ] - Erro ao excluir os vinculos com recibos dos débitos gerados pelo Pagamento Parcial.\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }

      }

      if( !empty($oRegistros->k128_sequencial) ){

        $oDaoAbatimentoArrecKeyArrecadCompos->excluir(null,"k129_abatimentoarreckey = {$oRegistros->k128_sequencial}");
        if ($oDaoAbatimentoArrecKeyArrecadCompos->erro_status == "0") {

          $sMsg  = "[ 4.9 ] - Erro ao excluir os registros gerados pelo Pagamento Parcial (abatimentoarreckeyarrecadcompos).\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      if( !empty($oRegistros->arreckey) ){

        $oDaoAbatimentoArrecKey->excluir(""," k128_arreckey = {$oRegistros->arreckey}");
        if ($oDaoAbatimentoArrecKey->erro_status == "0") {

          $sMsg  = "[ 4.10 ] - Erro ao excluir os registros gerados pelo Pagamento Parcial (abatimentoarreckey).\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      if( !empty($oRegistros->arreckey)){

        $oDaoArreCadCompos->excluir(null, "k00_arreckey = {$oRegistros->arreckey}");
        if ($oDaoArreCadCompos->erro_status == "0") {

          $sMsg  = "[ 4.11 ] - Erro ao excluir os registros gerados pelo Pagamento Parcial (abatimentoarreckey).\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      if( !empty($oRegistros->arreckey) ){

        $oDaoArrecKey->excluir($oRegistros->arreckey);
        if ($oDaoArrecKey->erro_status == '0') {

          $sMsg  = "[ 4.12 ] - Erro ao excluir os registros gerados pelo Pagamento Parcial (abatimentoarreckey).\n\n";
          $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
          throw new Exception($sMsg);
        }
      }

      $oDaoAbatimentoDisbanco->excluir(null, "k132_abatimento = {$oRegistros->abatimento}");
      if ($oDaoAbatimentoDisbanco->erro_status == "0" ) {

        $sMsg  = "[ 4.13 ] - Erro ao excluir abatimentos gerados pelo Pagamento Parcial.\n\n";
        $sMsg .= "Erro do Banco de Dados:".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);
      }

     /*
      * Alteramos os registros da disbanco para não classificados
      */
      $oDaoDisBanco->k00_numpre = $oRegistros->k127_numpreoriginal;
      $oDaoDisBanco->k00_numpar = $oRegistros->arreckey_numpar;
      if ($oRegistros->tipo_abatimento == 3) {
        $oDaoDisBanco->vlrpago    = " vlrpago + {$oRegistros->vlrabatido} ";
        $oDaoDisBanco->vlrtot     = " vlrtot  + {$oRegistros->vlrabatido} ";
      }
      $oDaoDisBanco->idret  = $oRegistros->idret;
      $oDaoDisBanco->alterar($oRegistros->idret);
      if ($oDaoDisBanco->erro_status == "0") {

        $sMsg  = "[1.9] - Erro ao excluir dados da tabela Disbanco gerados pela baixa do arquivo na classificação {$iCodCla} (Disbanco).\n\n";
        $sMsg .= "Erro: {$oDaoDisBanco->erro_msg} - ".str_replace('"',"\'",pg_last_error());
        throw new Exception($sMsg);

      }

    }
  }

  return true;

}

function retornaDebitoPagoLancamentoManual($iCodRet) {

  if ( !db_utils::inTransaction() ) {
  	throw new Exception("Nenhuma transação com o banco de dados encontrada!");
  }

  if (empty($iCodRet)) {
    throw new ParameterException("CodRet não declarado!");
  }

  $oDaoDisbanco  = new cl_disbanco();
  $oDaoArrePaga  = new cl_arrepaga();
  $oDaoArrecant  = new cl_arrecant();
  $oDaoArrecad   = new cl_arrecad();
  $oDaoArreIdRet = new cl_arreidret();

  /*
   * Caso o arquivo seja proveniente de uma inclusão manual que não é permitida a autenticação, quando o campo autent = t
   * Verificamos se o numpre da disbanco está diretamente no arrepaga ou em um recibopaga e de acordo com as informações
   * encontradas setamos os novos valores para as variáveis $oRegistros->numpre e $oRegistros->numpar
   */
   $sSqlDadosDisBanco  = "select distinct                                                                       \n";
   $sSqlDadosDisBanco .= "       idret,                                                                         \n";
   $sSqlDadosDisBanco .= "       case                                                                           \n";
   $sSqlDadosDisBanco .= "         when arrepaga.k00_numpre is null                                             \n";
   $sSqlDadosDisBanco .= "           then recibopaga.k00_numpre                                                 \n";
   $sSqlDadosDisBanco .= "         else arrepaga.k00_numpre                                                     \n";
   $sSqlDadosDisBanco .= "       end as numpre,                                                                 \n";
   $sSqlDadosDisBanco .= "       case                                                                           \n";
   $sSqlDadosDisBanco .= "         when arrepaga.k00_numpar is null                                             \n";
   $sSqlDadosDisBanco .= "           then recibopaga.k00_numpar                                                 \n";
   $sSqlDadosDisBanco .= "         else arrepaga.k00_numpar                                                     \n";
   $sSqlDadosDisBanco .= "       end as numpar,                                                                 \n";
   $sSqlDadosDisBanco .= "       case                                                                           \n";
   $sSqlDadosDisBanco .= "         when recibo.k00_numpre is not null                                           \n";
   $sSqlDadosDisBanco .= "           then true                                                                  \n";
   $sSqlDadosDisBanco .= "         else false                                                                   \n";
   $sSqlDadosDisBanco .= "       end as recibo_avulso                                                           \n";
   $sSqlDadosDisBanco .= "  from disbanco                                                                       \n";
   $sSqlDadosDisBanco .= "       left join arrepaga   on arrepaga.k00_numpre   = disbanco.k00_numpre            \n";
   $sSqlDadosDisBanco .= "                           and ( case                                                 \n";
   $sSqlDadosDisBanco .= "                                   when disbanco.k00_numpar <> 0                      \n";
   $sSqlDadosDisBanco .= "                                    then arrepaga.k00_numpar   = disbanco.k00_numpar  \n";
   $sSqlDadosDisBanco .= "                                   else true                                          \n";
   $sSqlDadosDisBanco .= "                                 end )                                                \n";
   $sSqlDadosDisBanco .= "       left join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre            \n";
   $sSqlDadosDisBanco .= "                           and disbanco.k00_numpar   = 0                              \n";
   $sSqlDadosDisBanco .= "       left join recibo     on recibo.k00_numpre     = disbanco.k00_numpre            \n";
   $sSqlDadosDisBanco .= "                           and disbanco.k00_numpar   = 0                              \n";
   $sSqlDadosDisBanco .= " where disbanco.codret = {$iCodRet}                                                   \n";
   $rsDadosDisbanco   = $oDaoDisbanco->sql_record($sSqlDadosDisBanco);
   $iQtdRegistros     = $oDaoDisbanco->numrows;
   if ($iQtdRegistros > 0) {

     for ($iRegistro = 0; $iRegistro < $iQtdRegistros; $iRegistro++) {

       $oRegistros = db_utils::fieldsMemory($rsDadosDisbanco, $iRegistro);

       if (empty($oRegistros->numpre)) {
         continue;
       }

       $oDaoArrePaga->excluir(null, "k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}");
       if ($oDaoArrePaga->erro_status == "0") {

         $sMsg  = "[ 2 ] - Erro ao excluir os pagamentos realizados pela baixa do arquivo {$iCodRet} (Arrepaga).\n\n";
         $sMsg .= "Erro: {$oDaoArrePaga->erro_msg} - ".str_replace('"',"\'",pg_last_error());
         throw new DBException($sMsg);

       }

       if ($oRegistros->recibo_avulso == "f") {

         $sWhere = "k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}";
         $sOrder = "k00_numpre, k00_numpar, k00_receit";
         $sSqlArrecant          = $oDaoArrecant->sql_query_file(null, "*", $sOrder, $sWhere);
         $rsArrecant            = $oDaoArrecant->sql_record($sSqlArrecant);
         $iQtdRegistrosArrecant = $oDaoArrecant->numrows;
         if ($oDaoArrecant->numrows == 0) {
           $sMsg  = "Procedimento Abortado!\n";
           $sMsg .= "Nenhum registro encontrado na tabela arrecant para o numpre {$oRegistros->numpre} parcela {$oRegistros->numpar}\n";
           $sMsg .= "Sem essa informação não é possível retornar o débito";
           throw new DBException($sMsg);
         }

         for($iArrecant = 0; $iArrecant < $iQtdRegistrosArrecant; $iArrecant++) {
            $oDadosArrecant = db_utils::fieldsMemory($rsArrecant, $iArrecant);

            $oDaoArrecad->k00_numpre = $oDadosArrecant->k00_numpre;
            $oDaoArrecad->k00_numpar = $oDadosArrecant->k00_numpar;
            $oDaoArrecad->k00_numcgm = $oDadosArrecant->k00_numcgm;
            $oDaoArrecad->k00_dtoper = $oDadosArrecant->k00_dtoper;
            $oDaoArrecad->k00_receit = $oDadosArrecant->k00_receit;
            $oDaoArrecad->k00_hist   = $oDadosArrecant->k00_hist  ;
            $oDaoArrecad->k00_valor  = $oDadosArrecant->k00_valor ;
            $oDaoArrecad->k00_dtvenc = $oDadosArrecant->k00_dtvenc;
            $oDaoArrecad->k00_numtot = $oDadosArrecant->k00_numtot;
            $oDaoArrecad->k00_numdig = "".($oDadosArrecant->k00_numdig+0)."";
            $oDaoArrecad->k00_tipo   = $oDadosArrecant->k00_tipo  ;
            $oDaoArrecad->k00_tipojm = $oDadosArrecant->k00_tipojm;
            $oDaoArrecad->incluir();
            if ($oDaoArrecad->erro_status == "0") {

               $sMsg  = "[ 3 ] - Erro ao retornar os pagamentos realizados pela baixa do arquivo {$iCodRet}.\\n\\n";
               $sMsg .= "Erro: ".str_replace('"',"\'",pg_last_error());
               throw new DBException($sMsg);

            }
          }

          $oDaoArrecant->excluir(null,"k00_numpre = {$oRegistros->numpre} and k00_numpar = {$oRegistros->numpar}");
          if ($oDaoArrecant->erro_status == "0") {

             $sMsg  = "[ 4 ] - Erro ao retornar os pagamentos realizados pela baixa do arquivo {$iCodRet}.\\n\\n";
             $sMsg .= "Erro: {$oDaoArrecant->erro_msg} - ".str_replace('"',"\'",pg_last_error());
             throw new DBException($sMsg);

          }

        }

        $oDaoArreIdRet->excluir(null, null, "idret = {$oRegistros->idret}");
        if ($oDaoArreIdRet->erro_status == "0") {

          $sMsg  = "[ 5 ] - Erro ao excluir dados da tabela Arreidret gerados pela baixa do arquivo {$iCodret} (Arreidret).\n\n";
          $sMsg .= "Erro: {$oDaoArreIdRet->erro_msg} - ".str_replace('"',"\'",str_replace('"',"\'",pg_last_error()));
          throw new DBException($sMsg);

        }

     }

   }

}

function excluiArquivoBaixaBanco($iCodRet) {

  if ( !db_utils::inTransaction() ) {
  	throw new Exception("Nenhuma transação com o banco de dados encontrada!");
  }

  if (empty($iCodRet)) {
    throw BusinessException("CodRet não declarado!");
  }

  $oDaoAbatimentoDisbanco       = db_utils::getDao("abatimentodisbanco");

  $oDaoIssArqSimplesRegDisbanco = new cl_issarqsimplesregdisbanco();
  $oDaoIssArqSimplesRegIssVar   = new cl_issarqsimplesregissvar();
  $oDaoIssArqSimplesRegErro     = new cl_issarqsimplesregerro();
  $oDaoIssArqSimplesRegIssBase  = new cl_issarqsimplesregissbase();
  $oDaoIssArqSimplesReg         = new cl_issarqsimplesreg();
  $oDaoIssArqSimplesDisArq      = new cl_issarqsimplesdisarq();
  $oDaoIssArqSimples            = new cl_issarqsimples();
  $oDaoDisbancoTxtReg           = new cl_disbancotxtreg();
  $oDaoDisbancotxt              = new cl_disbancotxt();
  $oDaoArreIdRet                = new cl_arreidret();
  $oDaoDisBancoProtProcesso     = new cl_disbancoprotprocesso();
  $oDaoDisBancoProcesso         = new cl_disbancoprocesso();
  $oDaoDisBanco                 = new cl_disbanco();
  $oDaoDisCla                   = new cl_discla();
  $oDaoDisArq                   = new cl_disarq();
  $oDaoArrecad                  = new cl_arrecad();
  $oDaoIssVar                   = new cl_issvar();

  /*
   * Arquivo classificado
   */
  $sSqlClassificacoes = $oDaoDisCla->sql_query_file(null, "array_to_string(array_accum(codcla),',') as classificacoes",null, "codret = {$iCodRet}");
  $rsClassificacoes   = $oDaoDisCla->sql_record($sSqlClassificacoes);
  if ($oDaoDisCla->numrows > 0 ) {
    $sClassificacoes = db_utils::fieldsMemory($rsClassificacoes,0)->classificacoes;
    if (!empty($sClassificacoes)) {
      $sMsg  = "Procedimento abortado!\n";
      $sMsg .= "Encontradas as classificações {$sClassificacoes} para o arquivo";
      throw new BusinessException($sMsg);
    }
  }

  $sSqlDisArq = $oDaoDisArq->sql_query_file($iCodRet, "autent");
  $rsDisArq   = $oDaoDisArq->sql_record($sSqlDisArq);
  if ($oDaoDisArq->numrows > 0) {
    $lAutentica = db_utils::fieldsMemory($rsDisArq, 0)->autent;
    if ($lAutentica == "t") {

    	retornaDebitoPagoLancamentoManual($iCodRet);

    	$sWhere          = "k132_idret in (select idret from disbanco where codret = {$iCodRet})";
    	$sSqlAbatimentos = $oDaoAbatimentoDisbanco->sql_query(null, "k132_abatimento", null, $sWhere);
    	$rsAbatimentos   = $oDaoAbatimentoDisbanco->sql_record($sSqlAbatimentos);
    	for($iIndAbatimentos = 0; $iIndAbatimentos < $oDaoAbatimentoDisbanco->numrows; $iIndAbatimentos++) {
    		excluiAbatimento(db_utils::fieldsMemory($rsAbatimentos, $iIndAbatimentos)->k132_abatimento);
    	}

    }
  } else {
  	$sMsg  = "Procedimento abortado!\n";
  	$sMsg .= "Arquivo de retorno {$iCodRet} não encontrado";
  	throw new BusinessException($sMsg);
  }


  /***************************************************************************************************************
  *
  * INICIO DO PROCESSAMENTO DA EXCLUSÃO DOS DADOS GERADOS PELO ARQUIVO SER DO SIMPLES NACIONAL
  *
  * Caso seja solicitado a exclusão do arquivo e  este for referente ao simples nacional.
  *
  * Excluimos os registros das tabelas: issarqsimplesregissvar, issarqsimplesregdisbanco, issarqsimplesregerro,
  * issarqsimplesreg, issarqsimplesdisarq e issarqsimples
  *
  ***************************************************************************************************************/
  $sCamposArqSimples = " distinct
                         q23_issarqsimples as issarq,
                         q23_sequencial as issarqreg ";
  $sWhereArqSimples  = " disbanco.codret = {$iCodRet} ";
  $sSqlArqSimples    = $oDaoIssArqSimplesRegDisbanco->sql_query(null, $sCamposArqSimples, null, $sWhereArqSimples);
  $rsArqSimples      = $oDaoIssArqSimplesRegDisbanco->sql_record($sSqlArqSimples);
  $iQtdRegistros     = $oDaoIssArqSimplesRegDisbanco->numrows;
  if ( $iQtdRegistros > 0 ) {

    for ($x=0; $x< $iQtdRegistros; $x++) {
      $oArqSimples = db_utils::fieldsMemory($rsArqSimples,$x);

      $sSqlDadosIssVar = $oDaoIssArqSimplesRegIssVar->sql_query(null,
      		                                                      "q05_codigo, q05_numpre, q05_numpar",
      		                                                      null,
      		                                                      "q68_issarqsimplesreg = {$oArqSimples->issarqreg}" );
      $rsDadosIssVar   = $oDaoIssArqSimplesRegIssVar->sql_record($sSqlDadosIssVar);
      $iLinhasIssVar   = $oDaoIssArqSimplesRegIssVar->numrows;

      $oDaoIssArqSimplesRegIssVar->excluir(null, "q68_issarqsimplesreg = {$oArqSimples->issarqreg}");
      if ($oDaoIssArqSimplesRegIssVar->erro_status == "0") {

      	$sMsg  = "[ 5.1 ] - Erro ao excluir os dados da tabela issarqsimplesregissvar.\n\n";
      	$sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
      	throw new DBException($sMsg);

      }

      if ($iLinhasIssVar > 0) {

      	for($iRegIssVar = 0; $iRegIssVar < $iLinhasIssVar; $iRegIssVar++){

      		$oDadosIssVar = db_utils::fieldsMemory($rsDadosIssVar,$iRegIssVar);

          $sWhereArrecad  = "     k00_numpre = {$oDadosIssVar->q05_numpre} ";
          $sWhereArrecad .= " and k00_numpar = {$oDadosIssVar->q05_numpar} ";
          $sWhereArrecad .= " and k00_numtot = 1                           ";

          $oDaoArrecad->excluir(null, $sWhereArrecad);
          if ($oDaoArrecad->erro_status == "0") {

          	$sMsg  = "[ 4.9 ] - Erro ao excluir os dados gerados pelo processamento do arquivo do SIMPLES da tabela arrecad.\n\n";
          	$sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
          	throw new DBException($sMsg);
          }

          // verifica se é numpre e numpar de calculo de issqn
          $sSqlIssVar = $oDaoIssVar->sql_isscalc_numpre_numpar($oDadosIssVar->q05_numpre, $oDadosIssVar->q05_numpar);
          $rDaoIssVar = db_query($sSqlIssVar);
          if ($rDaoIssVar == true) {

            // se for numpre e numpar de calculo de issqn zera o valor do issvar
            $sSqlIssVarUpdate = $oDaoIssVar->sql_update_vlrinf($oDadosIssVar->q05_numpre, $oDadosIssVar->q05_numpar, 0);
            $rDaoIssVarUpdate = db_query($sSqlIssVarUpdate);
            if ($rDaoIssVarUpdate == false) {

              $sMsg  = "[ 4.9.1 ] - Erro ao atualizar vlrinf do numpre {$oDadosIssVar->q05_numpre} e numpar {$oDadosIssVar->q05_numpar} na tabela issvar.\n\n";
              $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
              throw new DBException($sMsg);
            }
          } else {

            // se não for numpre e numpar de calculo de issqn deleta issvar
            $oDaoIssVar->excluir($oDadosIssVar->q05_codigo);
            if ($oDaoIssVar->erro_status == "0") {

              $sMsg  = "[ 4.9.2 ] - Erro ao excluir issvar do numpre {$oDadosIssVar->q05_numpre} e numpar {$oDadosIssVar->q05_numpar}.\n\n";
              $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
              throw new DBException($sMsg);
            }
          }

        }

      }

      $oDaoIssArqSimplesRegDisbanco->excluir(null, "q44_issarqsimplesreg = {$oArqSimples->issarqreg}" );
      if($oDaoIssArqSimplesRegDisbanco->erro_status == "0") {

        $sMsg  = "[ 5.2 ] - Erro ao excluir os dados da tabela issarqsimplesregdisbanco.\n\n";
        $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
        throw new DBException($sMsg);

      }

      $oDaoIssArqSimplesRegErro->excluir($oArqSimples->issarqreg);
      if ($oDaoIssArqSimplesRegErro->erro_status == "0") {

        $sMsg  = "[ 5.3 ] - Erro ao excluir os dados da tabela issarqsimplesregerro.\n\n";
        $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
        throw new DBException($sMsg);

      }

      $oDaoIssArqSimplesRegIssBase->excluir(null, "q134_issarqsimplesreg = {$oArqSimples->issarqreg}");
      if ($oDaoIssArqSimplesRegIssBase->erro_status == "0") {

        $sMsg  = "[ 5.4 ] - Erro ao excluir os dados da tabela issarqsimplesregissbase.\n\n";
        $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
        throw new DBException($sMsg);

      }

      $oDaoIssArqSimplesReg->excluir($oArqSimples->issarqreg);
      if ($oDaoIssArqSimplesReg->erro_status == "0") {

        $sMsg  = "[ 5.5 ] - Erro ao excluir os dados da tabela issarqsimplesreg.\n\n";
        $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
        throw new DBException($sMsg);

      }

      $oDaoIssArqSimplesDisArq->excluir(null," q43_issarqsimples = {$oArqSimples->issarq}");
      if ($oDaoIssArqSimplesDisArq->erro_status == "0") {

        $sMsg  = "[ 5.6 ] - Erro ao excluir os dados da tabela issarqsimplesdisarq.\n\n";
        $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
        throw new DBException($sMsg);

      }
    }

    $oDaoIssArqSimples->excluir($oArqSimples->issarq);
    if ($oDaoIssArqSimples->erro_status == "0") {

      $sMsg  = "[ 5.7 ] - Erro ao excluir os dados da tabela issarqsimples.\n\n";
      $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
      throw new DBException($sMsg);

    }

  }

  /****************************************************************************************************************
  *
  * FIM DO PROCESSAMENTO DA EXCLUSÃO DOS REGISTROS DE PROCESSAMENTO DE ARQUIVO DO SIMPLES NACIONAL
  *
  ****************************************************************************************************************/

  $sWhereDisbancoTxtReg  = "k35_disbancotxt in (select k34_sequencial from disbancotxt where k34_codret = {$iCodRet} ) ";
  $oDaoDisbancoTxtReg->excluir(null, $sWhereDisbancoTxtReg);
  if ($oDaoDisbancoTxtReg->erro_status == "0") {

    $sMsg  = "[ 5.7 ] - Erro ao excluir dados da tabela DisbancoTxtReg gerados para o arquivo {$iCodRet}.\n\n";
    $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $oDaoDisbancotxt->excluir(null, "k34_codret = {$iCodRet}");
  if ($oDaoDisbancotxt->erro_status == "0") {

    $sMsg  = "[ 5.8 ] - Erro ao excluir dados da tabela DisbancoTxt gerados para o arquivo {$iCodRet}.\n\n";
    $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $oDaoArreIdRet->excluir(null, null, "idret in (select idret from disbanco where codret = {$iCodRet} )");
  if ($oDaoArreIdRet->erro_status == "0") {

    $sMsg = "[ 5.8.1 ] - Erro ao excluir dados da tabela Arreidret gerados pela baixa do arquivo {$iCodRet}.\n\n";
    $sMsg = "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $sWhere  = " k141_disbancoprocesso in (select k142_sequencial                            ";
  $sWhere .= "                             from disbancoprocesso                           ";
  $sWhere .= "                            where k142_idret in (select idret                ";
  $sWhere .= "                                                   from disbanco             ";
  $sWhere .= "                                                  where codret = {$iCodRet}))";
  $oDaoDisBancoProtProcesso->excluir(null, $sWhere);
  if ($oDaoDisBancoProtProcesso->erro_status == "0") {

    $sMsg = "[ 5.8.1 ] - Erro ao excluir dados da tabela disbancoprotprocesso gerados pela baixa do arquivo {$iCodRet}.\n\n";
    $sMsg = "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $sWhere  = "k142_idret in ( select idret               ";
  $sWhere .= "                  from disbanco            ";
  $sWhere .= "                 where codret = {$iCodRet})";
  $oDaoDisBancoProcesso->excluir(null, $sWhere);
  if ($oDaoDisBancoProcesso->erro_status == "0") {

    $sMsg = "[ 5.8.1 ] - Erro ao excluir dados da tabela disbancoprocesso gerados pela baixa do arquivo {$iCodRet}.\n\n";
    $sMsg = "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $oCobrancaRegistradaRetornoRepository = new CobrancaRegistradaRetornoRepository;
  $oCobrancaRegistradaRetornoRepository->excluirRetornoArquivo($iCodRet);

  $oTarifaRepository = new TarifaRepository();
  $oTarifaRepository->excluir(null, $iCodRet);

  $oDaoDisBanco->excluir(null, "codret = {$iCodRet}");
  if ($oDaoDisBanco->erro_status == "0") {

    $sMsg  = "[ 5.9 ] - Erro ao excluir dados da tabela Disbanco gerados pela baixa do arquivo {$iCodRet}.\n\n";
    $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

  $oDaoDisArq->excluir($iCodRet);
  if ($oDaoDisArq->erro_status == "0") {

    $sMsg  = "[ 5.10 ] - Erro ao excluir dados da tabela DisArq gerados pela baixa do arquivo {$iCodRet}.\n\n";
    $sMsg .= "Erro:".str_replace('"',"\'",pg_last_error());
    throw new DBException($sMsg);

  }

}