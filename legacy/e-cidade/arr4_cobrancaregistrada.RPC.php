<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Factory;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use \cl_remessacobrancaregistrada as RemessaCobrancaRegistrada;
use \cl_conveniocobranca as ConvenioCobranca;
use \db_utils as DbUtils;
use \Exception as Exception;
use \DBDate as DBDate;

$oJson    = \JSON::create();
$oRetorno = new stdClass();

$oRetorno->erro = false;
$oRetorno->sMensagem = '';

$oParametros = $oJson->parse(str_replace("\\", "", $_POST["json"]));

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "getRemessasGeradas":

      if (empty($oParametros->sDataEmissaoInicio) or empty($oParametros->sDataEmissaoFim)) {
        throw new Exception("Campo Data de Emissão é de preenchimento obrigatório.");
      }

      $oDataEmissaoInicio = new DBDate($oParametros->sDataEmissaoInicio);
      $sDataEmissaoInicio = $oDataEmissaoInicio->getDate(DBDate::DATA_PTBR);

      $oDataEmissaoFim = new DBDate($oParametros->sDataEmissaoFim);
      $sDataEmissaoFim = $oDataEmissaoFim->getDate(DBDate::DATA_PTBR);

      $sWhere  = "k147_instit = ".db_getsession("DB_instit");
      $sWhere .= " and k147_dataemissao between '{$sDataEmissaoInicio}' and '{$sDataEmissaoFim}' ";

      if (!empty($oParametros->iConvenio)) {
        $sWhere .= " and k147_convenio = {$oParametros->iConvenio} ";
      }

      $oRemessaCobrancaRegistrada = new RemessaCobrancaRegistrada();
      $sSqlRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_query(
        null,
        "remessacobrancaregistrada.*, cadconvenio.ar11_nome",
        "k147_sequencialremessa desc",
        $sWhere
      );

      $rsRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_record($sSqlRemessaCobrancaRegistrada);

      if (!empty($oRemessaCobrancaRegistrada->erro_banco)) {
        throw new Exception("Erro ao buscar remessas.");
      }

      if ($oRemessaCobrancaRegistrada->numrows == 0) {
        throw new Exception("Nenhum registro encontrado para os filtros selecionados!");
      }

      $oRetorno->aRemessasGeradas = DbUtils::makeCollectionFromRecord($rsRemessaCobrancaRegistrada, function($oItem) {

        $oDataEmissao = new DBDate($oItem->k147_dataemissao);

        return (object) array(
          "codigo"          => $oItem->k147_sequencial,
          "sequencial"      => $oItem->k147_sequencialremessa,
          "codigo_convenio" => $oItem->k147_convenio,
          "nome_convenio"   => $oItem->ar11_nome,
          "data"            => $oDataEmissao->getDate(DBDate::DATA_PTBR),
          "hora"            => $oItem->k147_horaemissao
        );
      });

      break;

    case "getRemessaGeradaBaixar":

      $iSequencial = $oParametros->iSequencial;

      if (empty($iSequencial)) {
        throw new Exception("Campo sequencial da remessa é obrigatório.");
      }

      $oRemessaCobrancaRegistrada = new RemessaCobrancaRegistrada();
      $sSqlRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_query($iSequencial);
      $rsRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_record($sSqlRemessaCobrancaRegistrada);

      if (!empty($oRemessaCobrancaRegistrada->erro_banco)) {
        throw new Exception("Erro ao buscar remessas.");
      }

      if ($oRemessaCobrancaRegistrada->numrows == 0) {
        throw new Exception("Nenhum registro encontrado para o sequencial da remessa!");
      }

      $oRCR = db_utils::fieldsMemory($rsRemessaCobrancaRegistrada, 0);

      $sDataEmissao = str_replace("-", "", $oRCR->k147_dataemissao);
      $sHoraEmissao = str_replace(":", "", $oRCR->k147_horaemissao);

      $sArquivoNome = "Remessa{$oRCR->k147_sequencialremessa}{$sDataEmissao}{$sHoraEmissao}.zip";
      $sArquivo = "tmp/{$sArquivoNome}";

      $lReemitiuArquivo = DBLargeObject::leitura($oRCR->k147_arquivoremessa, $sArquivo);

      if (!$lReemitiuArquivo) {
        throw new BusinessException("Erro ao buscar arquivo de remessa!");
      }

      $oRetorno->sArquivo = $sArquivo;
      $oRetorno->sArquivoNome = $sArquivoNome;

      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMensagem = $oErro->getMessage();
}

echo $oJson->stringify($oRetorno);
