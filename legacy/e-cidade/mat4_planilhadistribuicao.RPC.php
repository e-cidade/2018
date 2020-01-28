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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));


$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');


try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'salvar':

      $oParam->descricao = trim($oParam->descricao);
      if (empty($oParam->descricao)) {
        throw new Exception("O campo Descrição é de preenchimento obrigatório.");
      }
      if (count($oParam->materiais) == 0) {
        throw new Exception("Nenhum material foi informado.");
      }
      if (count($oParam->departamentos) == 0) {
        throw new Exception("Nenhum departamento foi informado.");
      }

      $oPlanilha = new PlanilhaDistribuicao($oParam->codigo);
      $oPlanilha->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->descricao));
      foreach ($oParam->materiais as $oStdMaterial) {
        $oPlanilha->adicionarMaterial(new MaterialAlmoxarifado($oStdMaterial->sCodigo));
      }

      foreach ($oParam->departamentos as $oStdDepartamento) {
        $oPlanilha->adicionarDepartamento(DBDepartamentoRepository::getDBDepartamentoByCodigo($oStdDepartamento->sCodigo));
      }

      $oPlanilha->salvar();
      $oRetorno->codigo   = $oPlanilha->getCodigo();
      $oRetorno->mensagem = "Planilha salva com sucesso.";
      break;

    case 'gerarPlanilha':

      $oPlanilha    = new PlanilhaDistribuicao($oParam->codigo);
      $sNomeArquivo = $oPlanilha->gerar();
      $oRetorno->nome_arquivo = urlencode($sNomeArquivo);

      break;

    case 'getPlanilha':

      $oPlanilha = new PlanilhaDistribuicao($oParam->codigo);
      $aDepartamentosAtendidos = $oPlanilha->getDepartamentosAtendidos();
      $oRetorno->codigo    = $oPlanilha->getCodigo();
      $oRetorno->descricao = urlencode($oPlanilha->getDescricao());
      $oRetorno->materiais = array();
      foreach ($oPlanilha->getMateriais() as $oMaterial) {

        $oStdMaterial = new stdClass();
        $oStdMaterial->codigo    = $oMaterial->getCodigo();
        $oStdMaterial->descricao = urlencode($oMaterial->getDescricao());
        $oStdMaterial->ativo     = $oMaterial->ativo();
        $oRetorno->materiais[]   = $oStdMaterial;
      }

      $oRetorno->departamentos = array();
      foreach ($oPlanilha->getDepartamentos() as $oDepartamento) {

        $oStdDepartamento = new stdClass();
        $oStdDepartamento->codigo    = $oDepartamento->getCodigo();
        $oStdDepartamento->descricao = urlencode($oDepartamento->getNomeDepartamento());
        $oStdDepartamento->atendido  = in_array($oDepartamento->getCodigo(), $aDepartamentosAtendidos);
        $oRetorno->departamentos[]   = $oStdDepartamento;
      }
      break;

    case "importarPlanilha":

      if (empty($_FILES['arquivo'])) {
        throw new Exception('Nenhum arquivo selecionado.');
      }

      if ($_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Ocorreu um erro ao fazer o upload do arquivo.');
      }

//      $oFile = new File($_FILES['arquivo']['name']);
//      if ($oFile->getExtension() !== 'csv') {
//        throw new Exception("Arquivo com formato inválido. Selecione um arquivo no formato CSV.");
//      }

      $oPlanilha           = new PlanilhaDistribuicao;
      $sNomeArquivo        = $_FILES['arquivo']['tmp_name'];
      $sNomeArquivoRetorno = $oPlanilha->importar($sNomeArquivo, (int) $oParam->almoxarifado);

      $oRetorno->mensagem     = "Planilha importada com sucesso.";
      $oRetorno->nome_arquivo = urlencode($sNomeArquivoRetorno);

      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro = true;
  $oRetorno->mensagem = $e->getMessage();
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
