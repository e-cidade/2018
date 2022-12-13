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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam            = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->erro    = false;
$oRetorno->message = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Pesquisa as posicoes do acordo
     */
    case "getItensAditar":

      $oContrato  = AcordoRepository::getByCodigo($oParam->iAcordo);

      $oPosicao                = $oContrato->getUltimaPosicao();
      $oRetorno->tipocontrato  = $oContrato->getOrigem();
      $oRetorno->datainicial   = $oContrato->getDataInicial();
      $oRetorno->datafinal     = $oContrato->getDataFinal();
      $oRetorno->valores       = $oContrato->getValoresItens();
      $oRetorno->origem_manual = ($oContrato->getOrigem() == Acordo::ORIGEM_MANUAL);

      $aItens = array();
      foreach ($oPosicao->getItens() as $oItemPosicao) {

        $oItem                 = new stdClass();

        $oItem->codigo         = $oItemPosicao->getCodigo();
        $oItem->codigoitem     = $oItemPosicao->getMaterial()->getMaterial();
        $oItem->elemento       = $oItemPosicao->getDesdobramento();
        $oItem->descricaoitem  = $oItemPosicao->getMaterial()->getDescricao();
        $oItem->valorunitario  = $oItemPosicao->getValorUnitario();
        $oItem->quantidade     = $oItemPosicao->getQuantidadeAtualizadaRenovacao();
        $oItem->valor          = $oItemPosicao->getValorAtualizadoRenovacao();
        $oItem->servico        = $oItemPosicao->getMaterial()->isServico() && ($oItemPosicao->getControlaQuantidade() == 'f' || $oItemPosicao->getControlaQuantidade() == null);
        $oItem->dotacoes       = array();

        foreach($oItemPosicao->getDotacoes() as $oDotacao) {
          $oItem->dotacoes[] = (object) array(
              'dotacao' => $oDotacao->dotacao,
              'quantidade' => $oDotacao->quantidade,
              'valor' => $oDotacao->valor,
              'valororiginal' => $oDotacao->valor
            );
        }

        $aItens[] = $oItem;
      }

      $oRetorno->itens = $aItens;
      break;

    case "processarAditamento":

      $oContrato = AcordoRepository::getByCodigo($oParam->iAcordo);
      $oContrato->aditar(
        $oParam->aItens,
        $oParam->tipoaditamento,
        $oParam->datainicial,
        $oParam->datafinal,
        $oParam->sNumeroAditamento,
        db_stdClass::db_stripTagsJson($oParam->sJustificativa),
        $oParam->iTipoOperacao
      );
      break;

    case "getUnidades":

      $oDaoMatUnid  = db_utils::getDao("matunid");
      $sSqlUnidades = $oDaoMatUnid->sql_query_file( null,
                                                    "m61_codmatunid,substr(m61_descr,1,20) as m61_descr",
                                                    "m61_descr" );
      $rsUnidades      = $oDaoMatUnid->sql_record($sSqlUnidades);
      $iNumRowsUnidade = $oDaoMatUnid->numrows;
      for ($i = 0; $i < $iNumRowsUnidade; $i++) {

        $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
        $oUnidade->m61_descr = urlencode($oUnidade->m61_descr);

        $aUnidades[] = $oUnidade;
      }
      $oRetorno->itens = $aUnidades;
      break;

  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao (true);
  $oRetorno->erro  = true;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo JSON::create()->stringify($oRetorno);