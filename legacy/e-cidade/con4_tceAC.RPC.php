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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/contabilidade/arquivos/tce/AC/ImportacaoArquivoTCEAC.model.php"));
require_once(modification("model/contabilidade/arquivos/tce/AC/ArquivoLancamento.model.php"));
require_once(modification("model/contabilidade/arquivos/tce/AC/ArquivoPartida.model.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "gerarArquivo":

      $oDataFinal   = new DBDate($oParam->dtFinal);
      $oDataInicial = new DBDate($oParam->dtInicial);

      $oArquivoLancamentos = new ArquivoLancamento($oDataInicial, $oDataFinal);
      $oArquivoPartida     = new ArquivoPartida($oDataInicial, $oDataFinal);

      file_put_contents("tmp/Lancamento.xml", $oArquivoLancamentos->getArquivo());
      file_put_contents("tmp/Partida.xml", $oArquivoPartida->getArquivo());

      $aArquivosComprimir = array();

      $oFile = new File('tmp/Lancamento.xml');
      $aArquivosComprimir[] = $oFile;
      $oFile = new File('tmp/Partida.xml');
      $aArquivosComprimir[] = $oFile;

      $oFileCompress = File::compressFiles($aArquivosComprimir, "ArquivoTCE_Acre");
      $oRetorno->sCaminhoArquivo = $oFileCompress->getFilePath();
      $oRetorno->sNomeArquivo    = $oFileCompress->getBaseName();
      $oRetorno->sMessage        = 'Arquivos gerados com sucesso!';

      break;

    case "importarArquivo":

      $oFiles = db_utils::postMemory($_FILES);
      if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
        throw new BusinessException("Arquivo importado com formato inválido! Arquivo deve ser do formato CSV.");
      }

      if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
        throw new BusinessException("Não é possível importar arquivo vazio.");
      }

      $oFile = new File($oFiles->arquivo['tmp_name']);
      $oImportacao = ImportacaoArquivoTCEAC::criarXML($oFile, $oParam->iTipo);
      $oRetorno->sMessage = "Importação efetuada com sucesso!";
      break;

    case "downloadArquivo":

      $oArquivo  = ImportacaoArquivoTCEAC::criarCSV($oParam->iTipo);
      $oRetorno->sNomeArquivo = urlencode($oArquivo->getFilePath());
      $oRetorno->sNome = urlencode(basename($oArquivo->getBaseName()));

      break;

    case "possuiArquivoImportado":

      $oRetorno->possuiArquivoImportado = ImportacaoArquivoTCEAC::possuiArquivoImportado($oParam->iTipo);

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);
