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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/JSON.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("std/db_stdClass.php");

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno                 = new stdClass();
$oRetorno->erro           = false;
$oRetorno->sMensagem      = '';
$oRetorno->iDepartamento  = null;
$oRetorno->lTemImportacao = false;

try {

  switch ($oParametros->sExecucao) {

    /**
     * Faz a validação inicial do arquivo e verifica se já existe importação para o departamento
     */
    case "verificaTemImportacao":

      if (empty($_FILES['arquivo'])) {
        throw new Exception("Nenhum arquivo selecionado.");
      }

//      $oFile = new File($_FILES['arquivo']['name']);
//      if ($oFile->getExtension() !== 'txt') {
//        throw new Exception("Arquivo com formato inválido! Selecione um arquivo com formato TXT.");
//      }

      $oArquivo      = new SplFileObject($_FILES['arquivo']['tmp_name']);
      $iDepartamento = (int) $oArquivo->current();

      $sWhere           = "p13_departamento = {$iDepartamento}";
      $oDaoLevantamento = new cl_levantamentopatrimonial;
      $sSqlLevantamento = $oDaoLevantamento->sql_query(null, 'p13_sequencial', null, $sWhere);
      $rsLevantamento   = $oDaoLevantamento->sql_record($sSqlLevantamento);

      if ($oDaoLevantamento->numrows > 0) {

        $iCodigoLevantamento  = db_utils::fieldsMemory($rsLevantamento, 0)->p13_sequencial;
        $oDaoLevantamentoBens = new cl_levantamentopatrimonialbens;
        $sWhere   = "p14_levantamentopatrimonial = {$iCodigoLevantamento}";
        $sSqlBens = $oDaoLevantamentoBens->sql_query(null, 'count(*) as contagem', null, $sWhere);
        $rsBens   = $oDaoLevantamentoBens->sql_record($sSqlBens);
        $iBens    = db_utils::fieldsMemory($rsBens, 0)->contagem;

        $oRetorno->lTemImportacao = true;
        $oRetorno->sMensagem      = "Já existe uma importação com {$iBens} bens para o departamento {$iDepartamento}. Deseja continuar?";
      }

      break;

    /**
     * Importa o arquivo selecionado
     */
    case "processarArquivo":

      /**
       * Data da sessão
       * @var DBDate
       */
      $oData = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
      /**
       * Objeto utilizado para ler o arquivo
       * @var SplFileObject
       */
      $oArquivo = new SplFileObject($_FILES['arquivo']['tmp_name']);
      /**
       * A primeira linha do arquivo é o departamento
       * @var integer
       */
      $iDepartamento = (int) $oArquivo->current();
      /**
       * Códigos dos bens
       * @var string[]
       */
      $aBens = array();

      if (empty($iDepartamento)) {
        throw new Exception("Não foi encontrado o departamento no arquivo.");
      }

      while (!$oArquivo->eof()) {

        $sBem = ltrim(db_stdClass::normalizeStringJsonEscapeString(trim($oArquivo->fgets())), '0');
        if (strlen($sBem) > 50) {
          throw new Exception("Não é possível importar arquivo onde exista(m) placa(s) que ultrapassa(m) o tamanho máximo de caracteres (50).");
        }
        if (!empty($sBem) && !in_array($sBem, $aBens)) {
          $aBens[] = $sBem;
        }
      }

      if (empty($aBens)) {
        throw new Exception("Nenhum item encontrado no arquivo.");
      }

      $oDaoLevantamento     = new cl_levantamentopatrimonial;
      $oDaoLevantamentoBens = new cl_levantamentopatrimonialbens;

      try {
        (new DBDepartamento($iDepartamento));
      } catch (Exception $e) {
        throw new Exception("O departamento de código {$iDepartamento} não foi encontrado no sistema. Verifique o arquivo.");
      }

      db_inicio_transacao();

      /**
       * Busca levantamento existente para o departamento no arquivo
       */
      $sWhereLevantamento = "p13_departamento = {$iDepartamento}";
      $sSqlLevantamento   = $oDaoLevantamento->sql_query(null, 'p13_sequencial', null, $sWhereLevantamento);
      $rsLevantamento     = $oDaoLevantamento->sql_record($sSqlLevantamento);

      /**
       * Se existir algum levantamento para o departamento, apaga os dados
       * antes de fazer a importação
       */
      if ($oDaoLevantamento->numrows > 0) {

        $iCodigoLevantamento = db_utils::fieldsMemory($rsLevantamento, 0)->p13_sequencial;
        $oDaoLevantamentoBens->excluir(null, "p14_levantamentopatrimonial = {$iCodigoLevantamento}");
        $oDaoLevantamento->excluir($iCodigoLevantamento);
      }

      $oDaoLevantamento->p13_departamento = $iDepartamento;
      $oDaoLevantamento->p13_data         = $oData->getDate();
      $oDaoLevantamento->incluir(null);

      if ($oDaoLevantamento->erro_status == '0') {
        throw new Exception('Falha ao importar o arquivo.');
      }

      foreach ($aBens as $sBem) {

        $oDaoLevantamentoBens->p14_levantamentopatrimonial = $oDaoLevantamento->p13_sequencial;
        $oDaoLevantamentoBens->p14_placa                   = $sBem;
        $oDaoLevantamentoBens->incluir(null);

        if ($oDaoLevantamentoBens->erro_status == '0') {
          throw new Exception('Falha ao importar o arquivo.');
        }
      }

      db_fim_transacao();

      $oRetorno->sMensagem     = 'Arquivo importado com sucesso. Deseja emitir o relatório?';
      $oRetorno->iDepartamento = $iDepartamento;

      break;

    default:

      throw new Exception('Opção "' . $oParametros->sExecucao . '" inválida.');

      break;
  }

} catch (Exception $oException) {

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $oException->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);
