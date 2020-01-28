<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson              = new services_json();
$oParametros        = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$iSequencial        = null;
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

define("MENSAGENS", "tributario.meioambiente.db_frmcondicionante.");

if (isset($oParametros->iSequencial)) {
  $iSequencial = $oParametros->iSequencial;
}

$oCondicionante = new Condicionante($iSequencial);

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "incluirCondicionante":

      $oCondicionante->setDescricao( db_stdClass::normalizeStringJsonEscapeString( $oParametros->sDescricao ) );
      $oCondicionante->setPadrao( $oParametros->lPadrao );
      $oCondicionante->setVinculaTodasAtividades( $oParametros->lTodasAtividades );
      $oCondicionante->incluir();

      /**
       * Incluimos  os Tipos de licença para condicionante
       */
      $iCodigoCondicionante = $oCondicionante->getSequencial();
      foreach ($oParametros->aTipoLicenca as $iCodigoTipoLicenca) {

        $oCondicionanteTipoLicenca = new CondicionanteTipoLicenca();
        $oCondicionanteTipoLicenca->setCondicionante( $oCondicionante );
        $oCondicionanteTipoLicenca->setTipoLicenca( new TipoLicenca( $iCodigoTipoLicenca ) );
        $oCondicionanteTipoLicenca->incluir();
      }

      /**
       * Incluimos as atividades para a condicionante
       */
      foreach ($oParametros->aAtividades as $oAtividade) {

        $oCondicionanteAtividadeImpacto = new CondicionanteAtividadeImpacto();
        $oCondicionanteAtividadeImpacto->setAtividadeImpacto( new AtividadeImpacto( $oAtividade->sCodigo ) );
        $oCondicionanteAtividadeImpacto->setCondicionante( $oCondicionante );
        $oCondicionanteAtividadeImpacto->incluir();
      }

      $oRetorno->sMessage = urlencode(_M( MENSAGENS . 'inclusao_sucesso' ));

      break;

    case "alterarCondicionante":

      $oCondicionante->setDescricao( db_stdClass::normalizeStringJsonEscapeString( $oParametros->sDescricao ) );
      $oCondicionante->setPadrao( $oParametros->lPadrao );
      $oCondicionante->setVinculaTodasAtividades( $oParametros->lTodasAtividades );
      $oCondicionante->alterar();

      /**
       * Excluimos as atividades vinculadas a condicionante e incluimos novamente
       */
      CondicionanteAtividadeImpacto::excluir( $oParametros->iSequencial );
      foreach ($oParametros->aAtividades as $oAtividade) {

        $oCondicionanteAtividadeImpacto = new CondicionanteAtividadeImpacto();
        $oCondicionanteAtividadeImpacto->setAtividadeImpacto(new AtividadeImpacto($oAtividade->sCodigo));
        $oCondicionanteAtividadeImpacto->setCondicionante($oCondicionante);
        $oCondicionanteAtividadeImpacto->incluir();
      }

      /**
       * Excluimos os Tipos de Licença vinculados a condicionante e incluimos novamente
       */
      CondicionanteTipoLicenca::excluirVinculoCondicionante( $oParametros->iSequencial );
      foreach ($oParametros->aTipoLicenca as $iCodigoTipoLicenca) {

        $oCondicionanteTipoLicenca = new CondicionanteTipoLicenca();
        $oCondicionanteTipoLicenca->setCondicionante( $oCondicionante );
        $oCondicionanteTipoLicenca->setTipoLicenca( new TipoLicenca( $iCodigoTipoLicenca ) );
        $oCondicionanteTipoLicenca->incluir();
      }

      $oRetorno->sMessage = urlencode(_M( MENSAGENS . 'alteracao_sucesso' ));
      break;

    case "excluirCondicionante":

      $oCondicionante->excluir();
      $oRetorno->sMessage = urlencode(_M( MENSAGENS . 'exclusao_sucesso' ));

      break;

    case "getAtividadesCondicionante":

      /**
       * Busca as atividades vinculadas a condicionante
       */
      $aAtividades    = $oCondicionante->getAtividades();

      $aAtividadesLancadas = array();
      foreach ($aAtividades as $oAtividade) {
        $aAtividadesLancadas[$oAtividade->am03_sequencial] = utf8_encode($oAtividade->am03_descricao);
      }

      $oRetorno->aAtividadesLancadas = $aAtividadesLancadas;
      break;

    case "getTiposLicencaCondicionante":

      $aTiposLicenca = $oCondicionante->getTipoLicenca();

      $aTiposLancados = array();
      foreach ($aTiposLicenca as $oTipoLicenca) {
        $aTiposLancados[] = $oTipoLicenca->am17_tipolicenca;
      }

      $oRetorno->aTiposLicenca = $aTiposLancados;

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);