<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/JSON.php");
require_once modification("dbforms/db_funcoes.php");

$oJson                = JSON::create();
$oParam               = JSON::parse(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMensagem = '';

try {

  switch ($oParam->exec) {

    case 'salvarEvento':

      if (empty($oParam->sRubrica)) {
        throw new BusinessException("Rubrica não informada.");
      }

      if (empty($oParam->iSelecao)) {
        throw new BusinessException("Seleção não informado.");
      }

      if (empty($oParam->iMes) || $oParam->iMes > 12) {
        throw new BusinessException("Mês não informado.");
      }

      if (empty($oParam->sDescricao)) {
        throw new BusinessException("Descrição não informada.");
      }

      $oRubrica     = RubricaRepository::getInstanciaByCodigo($oParam->sRubrica);
      $oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
      $oSelecao     = new Selecao($oParam->iSelecao);

      $oConfiguracaoEventoFinanceiroAutomatico = new ConfiguracaoEventoFinanceiroAutomatico();

      if (!empty($oParam->iCodigo)) {
        $oConfiguracaoEventoFinanceiroAutomatico->setCodigo($oParam->iCodigo);
      }

      $oConfiguracaoEventoFinanceiroAutomatico->setDescricao($oParam->sDescricao);
      $oConfiguracaoEventoFinanceiroAutomatico->setRubrica($oRubrica);
      $oConfiguracaoEventoFinanceiroAutomatico->setMes($oParam->iMes);
      $oConfiguracaoEventoFinanceiroAutomatico->setSelecao($oSelecao);
      $oConfiguracaoEventoFinanceiroAutomatico->setInstituicao($oInstituicao);

      $oConfiguracao = ConfiguracaoEventoFinanceiroAutomaticoRepository::persist($oConfiguracaoEventoFinanceiroAutomatico);

      $oRetorno->iCodigoConfiguracao = $oConfiguracao->getCodigo();
      $oRetorno->sMensagem = "Evento configurado com sucesso.";
      break;

    case 'removerEvento':

      if (empty($oParam->iCodigo)) {
        throw new BusinessException("Código do evento não informado.");
      }

      $oConfiguracaoEventoFinanceiroAutomatico = new ConfiguracaoEventoFinanceiroAutomatico();
      $oConfiguracaoEventoFinanceiroAutomatico->setCodigo($oParam->iCodigo);

      ConfiguracaoEventoFinanceiroAutomaticoRepository::remover($oConfiguracaoEventoFinanceiroAutomatico);
      $oRetorno->sMensagem = "Configuração removida com sucesso.";
      break;

    case 'getEventos':

      $aConfiguracoesCadastradas = ConfiguracaoEventoFinanceiroAutomaticoRepository::getConfiguracoesPorMesInstituicao();
      $oRetorno->eventos         = array(); 
      foreach ($aConfiguracoesCadastradas as $oConfiguracao) {

        $oConfiguracoes = new stdClass();
        $oConfiguracoes->iCodigo        = $oConfiguracao->getCodigo();
        $oConfiguracoes->sDescricao     = $oConfiguracao->getDescricao();
        $oConfiguracoes->sCodigoRubrica = $oConfiguracao->getRubrica()->getCodigo();
        $oConfiguracoes->sNomeRubrica   = $oConfiguracao->getRubrica()->getDescricao();
        $oConfiguracoes->iCodigoSelecao = $oConfiguracao->getSelecao()->getCodigo();
        $oConfiguracoes->sNomeSelecao   = $oConfiguracao->getSelecao()->getDescricao();
        $oConfiguracoes->iMes            = $oConfiguracao->getMes();
        $oRetorno->eventos[]            = $oConfiguracoes;
      }
      
      break;
  }
} catch(Exception $e) {

  if(db_utils::inTransaction()) {
    db_fim_transacao(true);
  }

  $oRetorno->erro = true;
  $oRetorno->sMensagem = $e->getMessage();
}

echo JSON::stringify($oRetorno);