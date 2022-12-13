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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "libs/JSON.php";

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMessage     = '';

$sMensagens = "patrimonial.compras.com4_manifestarinteresseregistroprecoporvalor.";

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "buscarDadosEstimativa":

      if (empty($oParam->iEstimativa)) {
        throw new Exception( _M( $sMensagens . "estimativa_nao_informada") );
      }

      $oEstimativaRegistroPreco = new estimativaRegistroPreco($oParam->iEstimativa);
      $oRetorno->iAbertura = $oEstimativaRegistroPreco->getCodigoAbertura();

      break;

    case "buscarItens":

      if (empty($oParam->iEstimativa) && empty($oParam->iAbertura)) {
        throw new Exception( _M($sMensagens . "abertura_nao_informada") );
      }

      if (!empty($oParam->iAbertura)) {
        $oAberturaRegistroPreco = new aberturaRegistroPreco($oParam->iAbertura);

        foreach ($oAberturaRegistroPreco->getEstimativas() as $oEstimativa) {

          if ($oEstimativa->getCodigoDepartamento() == db_getsession("DB_coddepto") && !$oEstimativa->isAnulada()) {
            throw new Exception( _M($sMensagens . "abertura_possui_estimativa", (object) array( 'iAbertura' => $oParam->iAbertura,
                                                                                                'iEstimativa' => $oEstimativa->getCodigoSolicitacao() )) );
          }
        }
      } else {
        $oAberturaRegistroPreco = new estimativaRegistroPreco($oParam->iEstimativa);
      }

      $oRetorno->aItens = array();

      $aItens = $oAberturaRegistroPreco->getItens();
      if (count($aItens) == 0) {
        throw new BusinessException(_M($sMensagens.'abertura_sem_itens'));
      }

      foreach ($aItens as $oItem) {

        $oRetorno->aItens[] = array(
            'codigo'    => $oItem->getCodigoMaterial(),
            'descricao' => $oItem->getDescricaoMaterial(),
            'valor'     => $oItem->getValorTotal(),
            'resumo'    => $oItem->getResumo(),
            'marcado'   => ($oItem->getLiberado() && !empty($oParam->iEstimativa))
          );
      }

      break;

    /**
     * Quando for passado a Estimativa irá apenas alterar os itens
     * Quando não for passado a estimativa irá incluir uma com base na abertura
     */
    case "salvar":

      if (empty($oParam->iEstimativa) && empty($oParam->iAbertura)) {
        throw new Exception( _M($sMensagens . "abertura_nao_informada") );
      }

      if (!empty($oParam->iEstimativa)) {

        $oEstimativaRegistroPreco = new estimativaRegistroPreco($oParam->iEstimativa);

        foreach ($oEstimativaRegistroPreco->getItens() as $oItemeEstimativa) {

          $oItemeEstimativa->setLiberado(in_array($oItemeEstimativa->getCodigoMaterial(), $oParam->aItens));
          $oItemeEstimativa->save($oParam->iEstimativa);
        }
      } else {

        $oEstimativaRegistroPreco = new estimativaRegistroPreco();
        $oEstimativaRegistroPreco->setCodigoAbertura($oParam->iAbertura);

        $oAberturaRegistroPreco = new aberturaRegistroPreco($oParam->iAbertura);

        foreach ($oAberturaRegistroPreco->getItens() as $oItemAbertura) {

          $oItemEstimativa = new ItemEstimativa(null, $oItemAbertura->getCodigoMaterial());

          $oItemEstimativa->setQuantidade(1);
          $oItemEstimativa->setValorUnitario($oItemAbertura->getValorUnitario());
          $oItemEstimativa->setResumo($oItemAbertura->getResumo());
          $oItemEstimativa->setJustificativa($oItemAbertura->getJustificativa());
          $oItemEstimativa->setPagamento($oItemAbertura->getPagamento());
          $oItemEstimativa->setPrazos($oItemAbertura->getPrazos());
          $oItemEstimativa->setUnidade($oItemAbertura->getUnidade());
          $oItemEstimativa->setCodigoOrigem($oItemAbertura->getCodigoItemSolicitacao());
          $oItemEstimativa->setQuantidadeUnidade($oItemAbertura->getQuantidadeUnidade());
          $oItemEstimativa->setLiberado(in_array($oItemAbertura->getCodigoMaterial(), $oParam->aItens));

          $oEstimativaRegistroPreco->addItem($oItemEstimativa);
        }
      }

      $oEstimativaRegistroPreco->setAlterado(true);
      $oEstimativaRegistroPreco->save();

      $oRetorno->iCodigoEstimativa = $oEstimativaRegistroPreco->getCodigoSolicitacao();

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>