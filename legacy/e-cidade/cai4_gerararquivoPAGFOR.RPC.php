<?php
/**
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));

$oParam             = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "gerarArquivoTXT":

      $dtGeracaoArquivo       = new DBDate($oParam->dtGeracao);
      $dtAutorizacaoPagamento = new DBDate($oParam->dtPagamento);
      $oGeradorArquivoPagFor  = new GeradorArquivoPagFor();
      $oGeradorArquivoPagFor->setDescricaoGeracao($oParam->sDescricao);
      $oGeradorArquivoPagFor->setDataGeracaoArquivo($dtGeracaoArquivo);
      $oGeradorArquivoPagFor->setDataAutorizacaoPagamento($dtAutorizacaoPagamento);
      $oGeradorArquivoPagFor->setHoraGeracaoArquivo(db_hora());
      $oGeradorArquivoPagFor->setInstituicao(new Instituicao(db_getsession("DB_instit")));
      $oGeradorArquivoPagFor->setAno(db_getsession("DB_anousu"));
      $oGeradorArquivoPagFor->setCodigoRemessa(null);

      $oFile              = $oGeradorArquivoPagFor->emitir(explode(',', $oParam->sMovimentos));
      $oRetorno->sArquivo = $oFile->getFilePath();
      break;

    case "regerarArquivo":

      if (empty($oParam->iCodGera)) {
        throw new ParameterException("O campo Código é de preenchimento obrigatório");
      }

      if (empty($oParam->dtGeracao)) {
        throw new ParameterException("O campo Data de Geração é de preenchimento obrigatório");
      }

      if (empty($oParam->dtAutoriza)) {
        throw new ParameterException("O campo Data de Autorização é de preenchimento obrigatório");
      }

      $oGeracaoArquivo       = new DBDate($oParam->dtGeracao);
      $oAutorizacaoPagamento = new DBDate($oParam->dtAutoriza);

      $oGeradorArquivoPagFor = new GeradorArquivoPagFor($oParam->iCodGera);
      $oGeradorArquivoPagFor->setInstituicao(new Instituicao(db_getsession("DB_instit")));
      $oGeradorArquivoPagFor->setAno(db_getsession("DB_anousu"));

      $oFile              = $oGeradorArquivoPagFor->reemitir($oGeracaoArquivo, $oAutorizacaoPagamento, db_hora());
      $oRetorno->sArquivo = $oFile->getFilePath();
      break;
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();;
}

echo JSON::create()->stringify($oRetorno);