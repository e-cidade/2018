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
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/pessoal/std/DBPessoal.model.php"));

$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno            = new stdClass();
$oRetorno->erro      = false;
$oRetorno->sMensagem = '';

try {

  db_inicio_transacao();
  switch ($oParametros->exec) {

    case 'getPeriodoEmAbertoDoServidor' :

      $oServidor  = new Servidor($oParametros->matricula);
      if (FeriasConfiguracao::isUltimoPeriodoAquisitivo()) {
        $aPeriodos = PeriodoAquisitivoFeriasRepository::getPeriodosDisponiveisDoServidor($oServidor);
      } else {
        $aPeriodos = array(PeriodoAquisitivoFerias::getDisponivel( $oServidor));
      }
      $periodosRetorno = array();

      foreach ($aPeriodos as $oPeriodo) {

        if (!empty($oParametros->codigo_periodo)) {
          if ($oParametros->codigo_periodo != $oPeriodo->getCodigo()) {
            continue;
          }
        }
        $oStdPeriodo               = new stdClass();
        $oStdPeriodo->inicio       = $oPeriodo->getDataInicial()->getDate(DBDate::DATA_PTBR);
        $oStdPeriodo->fim          = $oPeriodo->getDataFinal()->getDate(DBDate::DATA_PTBR);
        $oStdPeriodo->saldo        = $oPeriodo->getSaldoDiasDireito();
        $oStdPeriodo->dias_direito = $oPeriodo->getSaldoDiasDireito();
        $oStdPeriodo->codigo       = $oPeriodo->getCodigo();
        $oRetorno->periodos[]      = $oStdPeriodo;
      }
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);