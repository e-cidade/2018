<?php
/*
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/JSON.php'));

use cl_cadconvenio as ConvenioRepository;
use cl_arretipo as TipoDebitoRepository;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoRequestFilters;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoRepository;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno\RetornoReport;

$oParametros = JSON::create()->parse(str_replace('\\', '', $_POST['json']));

$oRetorno = new stdClass();

$oRetorno->erro = false;
$oRetorno->sMensagem = '';

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case 'relatorioRetornoCobrancaRegistrada':

      if (empty($oParametros->sDataEmissaoInicio) or empty($oParametros->sDataEmissaoFim)) {
        throw new Exception('O campo Data de Emissão é de preenchimento obrigatório.');
      }

      $oDataEmissaoInicio = new DBDate($oParametros->sDataEmissaoInicio);
      $oDataEmissaoFim = new DBDate($oParametros->sDataEmissaoFim);

      $oRetornoRequestFilters = new RetornoRequestFilters($oDataEmissaoInicio, $oDataEmissaoFim);

      $oRetornoRequestFilters->setCodigoArrecadacao($oParametros->iCodigoArrecadacao);
      $oRetornoRequestFilters->setCodigoOcorrencia($oParametros->iCodigoOcorrencia);

      if (!empty($oParametros->iCodigoConvenio)) {

        $oConvenioRepository = new ConvenioRepository;

        $sSql = $oConvenioRepository->sql_query($oParametros->iCodigoConvenio);

        $rsConvenio = $oConvenioRepository->sql_record($sSql);

        $oConvenio = db_utils::fieldsMemory($rsConvenio, 0);

        $oRetornoRequestFilters->setCodigoConvenio($oConvenio->ar11_sequencial);
        $oRetornoRequestFilters->setConvenioDescricao($oConvenio->ar11_nome);
      }


      if (!empty($oParametros->iCodigoTipoDebito)) {

        $oTipoDebitoRepository = new TipoDebitoRepository;

        $sSql = $oTipoDebitoRepository->sql_query($oParametros->iCodigoTipoDebito);

        $rsConvenio = $oTipoDebitoRepository->sql_record($sSql);

        $oTipoDebito = db_utils::fieldsMemory($rsConvenio, 0);

        $oRetornoRequestFilters->setCodigoTipoDebito($oTipoDebito->k00_tipo);
        $oRetornoRequestFilters->setTipoDebitoDescricao($oTipoDebito->k00_descr);
      }

      $oRetornoCobrancaRegistradaCollection = RetornoRepository::findAllByRequestFilters($oRetornoRequestFilters);

      if (!$oRetornoCobrancaRegistradaCollection->count()) {
        throw new Exception('Nenhum registro encontrado para o(s) filtro(s) selecionado(s).');
      }

      $sArquivo = RetornoReport::reportCobrancaRegistrada($oRetornoRequestFilters, $oRetornoCobrancaRegistradaCollection);

      $oRetorno->sArquivo = $sArquivo;

      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro) {

  $oRetorno->erro = true;
  $oRetorno->sMensagem = $oErro->getMessage();

  db_fim_transacao(true);
}

echo JSON::create()->stringify($oRetorno);
