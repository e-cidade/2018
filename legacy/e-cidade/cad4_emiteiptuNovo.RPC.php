<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once (modification("libs/JSON.php"));

use ECidade\Tributario\Arrecadacao\EmissaoGeral\Repository;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;

$oJson                  = new \services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new \stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

try {

  \db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "consultarEmissao":

      if ( empty($oParametros->iTipoEmissao) ) {
        throw new ParameterException("o Tipo de Emissão Geral não foi informado.");
      }

      $oEmissaoRepository = new Repository();
      $aEmissao           = $oEmissaoRepository->getEmissoesPorTipo($oParametros->iTipoEmissao);

      foreach ($aEmissao as $iIndice => $oEmissao) {

        $oDados       = new \stdClass;
        $oDados->id   = $oEmissao->getCodigo();
        $oDados->data = $oEmissao->getData()->getDate(DBDate::DATA_PTBR);
        $oDados->hora = $oEmissao->getHora();

        $aOcorrencias = $oEmissaoRepository->getEmissaoOcorrencias($oEmissao);
        $sOcorrencia  = 0;

        if ( empty($aOcorrencias) ) {
          $sOcorrencia = "Retorno Pendente";
        }

        foreach ($aOcorrencias as $iIndice => $oOcorrencia) {

          if ( EmissaoGeral::MOVIMENTACAO_RETORNO_REJEITADO ==  $oOcorrencia->movimentacao ) {
            $sOcorrencia++;
          }
        }

        $oDados->ocorrencias = $sOcorrencia;
        $oRetorno->aDados[]  = $oDados;
      }

      break;
  }

  \db_fim_transacao(false);


} catch (\Exception $oErro){

  \db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);