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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

$oParametros                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno                    = new stdClass();
$oRetorno->erro              = false;
$oRetorno->caminho_relatorio = '';
$oRetorno->mensagem          = '';

$aInstituicoes    = array();
$sErroInstituicao = "O campo Instituição é de preenchimento obrigatório.";

try {

  switch ($oParametros->exec) {
    case 'gerarRelatorio':

      if (!isset($oParametros->iCodigoRelatorio) || empty($oParametros->iCodigoRelatorio)) {
        throw new ParameterException("O código do relatório não foi informado.");
      }
      $iCodigoRelatorio = (int) $oParametros->iCodigoRelatorio;

      if (!isset($oParametros->sInstituicao) || empty($oParametros->sInstituicao)) {
        throw new ParameterException($sErroInstituicao);
      }

      $aInstituicoes = explode(",", $oParametros->sInstituicao);
      if (empty($aInstituicoes)) {
        throw new ParameterException($sErroInstituicao);
      }

      foreach ($aInstituicoes as $iInstituicao) {

        if (!is_numeric($iInstituicao)) {
          throw new ParameterException("Instituição informada é inválida.");
        }
      }

      $sInstituicao = implode(",", $aInstituicoes);

      $iAnoUsu = db_getsession("DB_anousu");

      $oRelatorio = AnexoSICONFIFactory::getAnexoSICONFI($iAnoUsu, $iCodigoRelatorio);
      $oRelatorio->setInstituicoes($sInstituicao);
      $oRetorno->nome_relatorio    = $oRelatorio::NOME_RELATORIO;
      $oRetorno->caminho_relatorio = $oRelatorio->gerar($oParametros->sFormato);
      break;

    default:
      throw new Exception('Método não encontrado.');
      break;
  }

} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
