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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/JSON.php";

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->erro    = false;
$oRetorno->message = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getDadosPerspectiva":

      if (empty($oParam->iPerspectiva)) {
        throw new Exception("Perspectiva não informada.");
      }

      $oDaoPerspectiva    = new cl_cronogramaperspectiva();
      $oDaoAcompanhamento = new cl_cronogramaperspectivaacompanhamento();
      $oDaoReceita        = new cl_cronogramaperspectivareceita();
      $oDaoDespesa        = new cl_cronogramaperspectivadespesa();
      $sSqlAcompanhamento = $oDaoAcompanhamento->sql_query_file(null, "*", null, "o151_cronogramaperspectivaorigem = {$oParam->iPerspectiva}");
      $sSqlReceita        = $oDaoReceita->sql_query_file(null, "*", null, "o126_cronogramaperspectiva = {$oParam->iPerspectiva}");
      $sSqlDespesa        = $oDaoDespesa->sql_query_file(null, "*", null, "o130_cronogramaperspectiva = {$oParam->iPerspectiva}");
      $sSqlPerspectiva    = $oDaoPerspectiva->sql_query_file( $oParam->iPerspectiva,
                                                              "o124_situacao, exists({$sSqlAcompanhamento}) as acompanhamento" .
                                                              ", exists($sSqlReceita) as receita, exists($sSqlDespesa) as despesa" );
      $rsPerspectiva      = $oDaoPerspectiva->sql_record($sSqlPerspectiva);

      if ($oDaoPerspectiva->numrows < 0) {
        throw new Exception("Perspectiva não encontrada.");
      }

      $oDadosPerspectiva        = db_utils::fieldsMemory($rsPerspectiva, 0);
      $oRetorno->homologado     = $oDadosPerspectiva->o124_situacao;
      $oRetorno->acompanhamento = $oDadosPerspectiva->acompanhamento == 't';
      $oRetorno->receita        = $oDadosPerspectiva->receita == 't';
      $oRetorno->despesa        = $oDadosPerspectiva->despesa == 't';

      break;

    case "alterarSitucacao":

      if (!in_array($oParam->iHomologado, array(cronogramaFinanceiro::SITUACAO_ABERTO, cronogramaFinanceiro::SITUACAO_HOMOLOGADO))) {
        throw new Exception("A Situação informada é invalida.");
      }

      $oDaoPerspectiva = new cl_cronogramaperspectiva();

      if ($oParam->iHomologado == cronogramaFinanceiro::SITUACAO_HOMOLOGADO) {

        $sSqlAno       = $oDaoPerspectiva->sql_query_file($oParam->iPerspectiva, "o124_ano");
        $sSqlValidacao = $oDaoPerspectiva->sql_query_file(null, "*", null, "o124_ano = ({$sSqlAno}) and o124_situacao = " . cronogramaFinanceiro::SITUACAO_HOMOLOGADO);
        $rsValidacao   = $oDaoPerspectiva->sql_record($sSqlValidacao);

        if ($oDaoPerspectiva->numrows > 0) {

          $iAno = db_utils::fieldsMemory($rsValidacao, 0)->o124_ano;
          throw new Exception("Já existe uma perspectiva homologada para o ano de {$iAno}.");
        }
      }

      $oDaoPerspectiva->o124_sequencial = $oParam->iPerspectiva;
      $oDaoPerspectiva->o124_situacao   = $oParam->iHomologado;

      $oDaoPerspectiva->alterar($oParam->iPerspectiva);

      if ($oDaoPerspectiva->erro_status == 0) {
        throw new Exception("Erro ao alterar a situação do Cronograma de Desembolso.");
      }

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao (true);
  $oRetorno->erro  = true;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);