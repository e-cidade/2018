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

$oParam             = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getItens":

      if (empty($oParam->iCodigoLicitacao)) {
        throw new ParameterException("Campo Código da Licitação é de preenchimento obrigatório.");
      }

      $oDaoReservaCotas = new cl_licitacaoreservacotas();

      $oLicitacao      = new licitacao($oParam->iCodigoLicitacao);
      $aItensLicitacao = $oLicitacao->getItens();
      $aItensAgrupados = array();

      foreach ($aItensLicitacao as $oItemLicitacao) {

        $oItemSolicitacao = $oItemLicitacao->getItemSolicitacao();
        $iCodigo = $oItemLicitacao->getCodigo();

        $sSqlReserva = $oDaoReservaCotas->sql_query_file(null, "*", null, "l19_liclicitemreserva = {$iCodigo}");
        $rsReserva   = $oDaoReservaCotas->sql_record($sSqlReserva);

        if ($rsReserva && $oDaoReservaCotas->numrows > 0) {

          $iCodigo = db_utils::fieldsMemory($rsReserva, 0)->l19_liclicitemorigem;
        }

        if (empty($aItensAgrupados[$iCodigo])) {

          $aItensAgrupados[$iCodigo] = new stdClass();
          $aItensAgrupados[$iCodigo]->ordem = $oItemLicitacao->getOrdem();
          $aItensAgrupados[$iCodigo]->codigo = $oItemLicitacao->getCodigo();
          $aItensAgrupados[$iCodigo]->descricao = $oItemSolicitacao->getDescricaoMaterial();
          $aItensAgrupados[$iCodigo]->quantidade = 0;
          $aItensAgrupados[$iCodigo]->reservado = 0;
          $aItensAgrupados[$iCodigo]->aQuantidades = array();
        }

        array_push($aItensAgrupados[$iCodigo]->aQuantidades, $oItemSolicitacao->getQuantidade());

        $iReservado = 0;
        if (count($aItensAgrupados[$iCodigo]->aQuantidades) > 1) {
          $iReservado = min($aItensAgrupados[$iCodigo]->aQuantidades);
        }

        $aItensAgrupados[$iCodigo]->quantidade += $oItemSolicitacao->getQuantidade();
        $aItensAgrupados[$iCodigo]->reservado   = $iReservado;
      }

      sort($aItensAgrupados);
      $oRetorno->aItens = $aItensAgrupados;

      break;

    case "salvar":

      if (empty($oParam->iCodigoLicitacao)) {
        throw new ParameterException("Campo Código da Licitação é de preenchimento obrigatório.");
      }

      $oTratamentoDiferenciado = new TratamentoDiferenciado(new licitacao($oParam->iCodigoLicitacao));
      foreach($oParam->aItens as $oStdItem) {

        if ($oStdItem->reservado > 0) {

          $nQuantidadeMaxima = floor($oStdItem->quantidade * 0.25);
          if ($oStdItem->reservado > $nQuantidadeMaxima) {
            throw new Exception("Quantidade reservada superior a 25% permitido.");
          }
        }

        $oItemTratamento = new ItemTratamentoDiferenciado();
        $oItemTratamento->setCodigoItemLicitacao($oStdItem->codigo);
        $oItemTratamento->setQuantidade($oStdItem->reservado);
        $oTratamentoDiferenciado->adicionarItem($oItemTratamento);
      }
      $oTratamentoDiferenciado->reservarQuantidades();

      $oRetorno->mensagem = "Itens reservados com sucesso.";

      break;

    default:
      throw new Exception("Opção inválida");
  }

  db_fim_transacao(false);

} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->mensagem = $oErro->getMessage();
  $oRetorno->erro     = true;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
