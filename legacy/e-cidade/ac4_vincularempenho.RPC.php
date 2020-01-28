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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam            = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass();
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getEmpenhos":

      if (empty($oParam->iCodigoAcordo)) {
        throw new DBException("Código do acordo não foi informado.");
      }

      $iCodigoAcordo = $oParam->iCodigoAcordo;

      $oDaoAcordoEmpenho = new cl_acordoempempenho;
      $sCampos           = "e60_numemp, e60_codemp, e60_anousu, e60_emiss, e60_resumo, z01_nome, acordoempempenho.*";
      $sSql              = $oDaoAcordoEmpenho->sql_query(null, $sCampos, "e60_numemp", "ac54_acordo = {$iCodigoAcordo}");
      $rsEmpenhosVinculados = db_query($sSql);
      if ($rsEmpenhosVinculados === false) {
        throw new DBException("Não foi possivel encontrar empenhos vinculados a este acordo.");
      }

      $aEmpenhosVinculados = array();
      if (pg_num_rows($rsEmpenhosVinculados) > 0) {

        for ($iIndice = 0; $iIndice < pg_num_rows($rsEmpenhosVinculados); $iIndice++) {

          $oStdEmpenho = db_utils::fieldsMemory($rsEmpenhosVinculados, $iIndice);

          $oEmpenho = new stdClass;
          $oEmpenho->sCodigo          = urlencode($oStdEmpenho->e60_codemp."/".$oStdEmpenho->e60_anousu);
          $oEmpenho->sNomeCredor      = urlencode($oStdEmpenho->z01_nome);
          $oEmpenho->iCodigoAcordo    = $oStdEmpenho->ac54_acordo;
          $oEmpenho->sDataEmissao     = db_formatar($oStdEmpenho->e60_emiss, "d");
          $oEmpenho->iNumeroEmpenho   = $oStdEmpenho->ac54_empempenho;
          $oEmpenho->iNumeroLicitacao = $oStdEmpenho->ac54_numerolicitacao;

          if ($oStdEmpenho->ac54_numerolicitacao && $oStdEmpenho->ac54_ano) {
            $oEmpenho->iNumeroLicitacao = $oStdEmpenho->ac54_numerolicitacao . "/" . $oStdEmpenho->ac54_ano;
          }

          $aEmpenhosVinculados[] = $oEmpenho;
        }
      }

      $oRetorno->aEmpenhos = $aEmpenhosVinculados;

    break;

    case "salvar":

      if (empty($oParam->iCodigoAcordo)) {
        throw new ParameterException("O código do acordo não foi informado.");
      }

      if (empty($oParam->iNumeroEmpenho)) {
        throw new ParameterException("Nenhum empenho informado.");
      }

      if (!empty($oParam->iNumeroLicitacao) && !preg_match("/([0-9]+)\\/([0-9]{4})/", $oParam->iNumeroLicitacao)) {
        throw new ParameterException("O Número da Licitação é inválido. Informe Número/Ano da Licitacação.");
      }

      $iNumeroLicitacao = null;
      $iAnoLicitacao    = null;
      if (!empty($oParam->iNumeroLicitacao)) {

        $aNumeroLicitacao = explode('/', $oParam->iNumeroLicitacao);
        $iNumeroLicitacao = $aNumeroLicitacao[0];
        $iAnoLicitacao    = $aNumeroLicitacao[1];
      }

      $iCodigoAcordo  = (integer) $oParam->iCodigoAcordo;
      $aPartesEmpenho = explode("/", $oParam->iNumeroEmpenho);
      $iNumemp        = $aPartesEmpenho[0];
      $iAnoEmpenho    = db_getsession("DB_anousu");
      if (!empty($aPartesEmpenho[1])) {
        $iAnoEmpenho = $aPartesEmpenho[1];
      }
      $oInstituicao        = InstituicaoRepository::getInstituicaoSessao();
      $oEmpenhoFinanceiro  = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($iNumemp, $iAnoEmpenho, $oInstituicao);
      if (empty($oEmpenhoFinanceiro)) {
        throw new DBException("Empenho {$oParam->iNumeroEmpenho} não encontrado!");
      }
      $oDaoAcordoEmpenho = new cl_acordoempempenho;
      $oDaoAcordoEmpenho->ac54_acordo          = $iCodigoAcordo;
      $oDaoAcordoEmpenho->ac54_empempenho      = $oEmpenhoFinanceiro->getNumero();
      $oDaoAcordoEmpenho->ac54_numerolicitacao = $iNumeroLicitacao;
      $oDaoAcordoEmpenho->ac54_ano             = $iAnoLicitacao;
      $oDaoAcordoEmpenho->incluir(null);

      $oDaoEmpenhoAcordo = new cl_empempenhocontrato();
      $sSql = $oDaoEmpenhoAcordo->sql_query_file(null, '*', null, ' e100_numemp = ' . $oEmpenhoFinanceiro->getNumero() . ' AND e100_acordo = ' . $iCodigoAcordo);

      db_utils::makeFromRecord(db_query($sSql), function($linha) use ($oDaoEmpenhoAcordo, $oEmpenhoFinanceiro, $iCodigoAcordo) {
        if (empty($linha)) {
          $oDaoEmpenhoAcordo->e100_numemp = $oEmpenhoFinanceiro->getNumero();
          $oDaoEmpenhoAcordo->e100_acordo = $iCodigoAcordo;
          $oDaoEmpenhoAcordo->incluir(null);

          if ($oDaoEmpenhoAcordo->erro_status == "0") {
            throw new Exception('Não foi possível vincular o empenho ao contrato.');
          }
        }
      });

      if ($oDaoAcordoEmpenho->erro_status == "0") {
        throw new DBException("Não foi possível salvar vínculos do empenho com o acordo.");
      }
      $oRetorno->message = "Empenho Vinculado com sucesso!";

    break;
    case 'getLicitacaoDoEmpenho' :

      if (empty($oParam->iNumeroEmpenho)) {
        throw new DBException("Não foi informado o empenho!");
      }

      $aPartesEmpenho = explode("/", $oParam->iNumeroEmpenho);
      $iNumemp        = $aPartesEmpenho[0];
      $iAnoEmpenho    = db_getsession("DB_anousu");

      if (!empty($aPartesEmpenho[1])) {
        $iAnoEmpenho = $aPartesEmpenho[1];
      }

      $oInstituicao               = InstituicaoRepository::getInstituicaoSessao();
      $oEmpenhoFinanceiro         = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($iNumemp, $iAnoEmpenho,$oInstituicao);
      $oRetorno->iNumeroLicitacao = $oEmpenhoFinanceiro->getNumeroDaLicitacao();

      break;

    case 'excluirVinculo':

      if (empty($oParam->iCodigoAcordo)) {
        throw new ParameterException("O código do acordo não foi informado.");
      }

      if (empty($oParam->iNumeroEmpenho)) {
        throw new ParameterException("Nenhum empenho informado.");
      }

      $oDaoEmpenhoAcordo = new cl_empempenhocontrato();
      $oDaoEmpenhoAcordo->excluir(null, "e100_numemp={$oParam->iNumeroEmpenho} and e100_acordo = {$oParam->iCodigoAcordo}");

      if ($oDaoEmpenhoAcordo->erro_status == "0") {
        throw new Exception('Não foi possível remover o vínculo do empenho com contrato.');
      }

      $oDaoAcordoEmpenho = new cl_acordoempempenho;
      $iCodigoAcordo     = (int)$oParam->iCodigoAcordo;
      $iNumemp           = (int)$oParam->iNumeroEmpenho;
      $oDaoAcordoEmpenho->excluir(null, "ac54_acordo={$iCodigoAcordo} and ac54_empempenho = {$iNumemp}");
      if ($oDaoAcordoEmpenho->erro_status == "0") {
        throw new DBException("Não foi possível remover vínculos do empenho com o acordo.");
      }
      $oRetorno->message = urlencode("Vínculo do empenho removido com sucesso!");
      break;
    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($e->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
