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

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  'message' => '',
  'erro'    => false
);

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Categoria de Consumo
     */
    case "carregarCategoriaConsumo":

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oCategoria = new AguaCategoriaConsumo((integer) $oParam->iCodigo);
      $oRetorno->oCategoriaConsumo = (object) array(
        'iCodigo'    => $oCategoria->getCodigo(),
        'sDescricao' => $oCategoria->getDescricao(),
        'iExercicio' => $oCategoria->getExercicio(),
      );

      break;

    case "listarTiposEstruturaTarifaria":

      $aTipos = array();
      foreach (AguaEstruturaTarifaria::getTiposEstrutura() as $iCodigo => $sTipo) {
        $aTipos[] = (object) array('iCodigo' => $iCodigo, 'sDescricao' => $sTipo);
      }

      $oRetorno->aTipos = $aTipos;

      break;

    case "salvarCategoriaConsumo":

      $oCategoria = new AguaCategoriaConsumo((integer) $oParam->iCodigo);
      $oCategoria->setExercicio((integer) $oParam->iExercicio);
      $oCategoria->setDescricao($oParam->sDescricao);
      $oCategoria->salvar();

      $oRetorno->iCodigo = $oCategoria->getCodigo();
      $oRetorno->message = 'Categoria de consumo salva com sucesso.';

      break;

    case "excluirCategoriaConsumo":

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oCategoria = new AguaCategoriaConsumo((integer) $oParam->iCodigo);
      $oCategoria->excluir();
      $oRetorno->message = 'Categoria de consumo excluída com sucesso.';

      break;

    /**
     * Estrutura Tarifária
     */
    case "listarEstruturasTarifarias":

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $aEstruturasTarifarias = array();
      $aTiposEstrutura = AguaEstruturaTarifaria::getTiposEstrutura();
      $oCategoria = new AguaCategoriaConsumo($oParam->iCodigo);
      if ($oCategoria->getEstruturas()) {

        foreach ($oCategoria->getEstruturas() as $oEstrutura) {

          $sTipoEstrutura = $aTiposEstrutura[$oEstrutura->getCodigoTipoEstrutura()];
          if ($oEstrutura->getCodigoTipoEstrutura() === AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {

            if ($oEstrutura->getValorFinal()) {
              $sTipoEstrutura .= " ({$oEstrutura->getValorInicial()} até {$oEstrutura->getValorFinal()} m³)";
            } else {
              $sTipoEstrutura .= " (A partir de {$oEstrutura->getValorInicial()} m³)";
            }
          }

          $aEstruturasTarifarias[] = (object) array(
            'iCodigo'          => $oEstrutura->getCodigo(),
            'iTipoConsumo'     => $oEstrutura->getCodigoTipoConsumo(),
            'sTipoConsumo'     => $oEstrutura->getTipoConsumo()->getDescricao(),
            'iTipoEstrutura'   => $oEstrutura->getCodigoTipoEstrutura(),
            'sTipoEstrutura'   => $sTipoEstrutura,
            'iFaixaConsumoDe'  => $oEstrutura->getValorInicial(),
            'iFaixaConsumoAte' => $oEstrutura->getValorFinal(),
            'nValor'           => $oEstrutura->getValor(),
            'iPercentual'      => $oEstrutura->getPercentual(),
          );
        }
      }
      $oRetorno->aEstruturasTarifarias = $aEstruturasTarifarias;

      break;

    case "carregarEstruturaTarifaria":

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oEstruturaTarifaria = new AguaEstruturaTarifaria((integer) $oParam->iCodigo);
      $aTiposEstrutura = AguaEstruturaTarifaria::getTiposEstrutura();
      $sTipoEstrutura = $aTiposEstrutura[$oEstruturaTarifaria->getCodigoTipoEstrutura()];

      $oRetorno->oEstruturaTarifaria = (object) array(
        'iCodigo'          => $oEstruturaTarifaria->getCodigo(),
        'iTipoConsumo'     => $oEstruturaTarifaria->getCodigoTipoConsumo(),
        'sTipoConsumo'     => $oEstruturaTarifaria->getTipoConsumo()->getDescricao(),
        'iTipoEstrutura'   => $oEstruturaTarifaria->getCodigoTipoEstrutura(),
        'sTipoEstrutura'   => $sTipoEstrutura,
        'iFaixaConsumoDe'  => $oEstruturaTarifaria->getValorInicial(),
        'iFaixaConsumoAte' => $oEstruturaTarifaria->getValorFinal(),
        'nValor'           => $oEstruturaTarifaria->getValor(),
        'iPercentual'      => $oEstruturaTarifaria->getPercentual(),
      );

      break;

    case "salvarEstruturaTarifaria":

      $oEstruturaTarifaria = new AguaEstruturaTarifaria((integer) $oParam->iCodigo);
      $oEstruturaTarifaria->setCodigoTipoConsumo((integer) $oParam->iTipoConsumo);
      $oEstruturaTarifaria->setCodigoCategoriaConsumo((integer) $oParam->iCodigoCategoria);
      $oEstruturaTarifaria->setCodigoTipoEstrutura((integer) $oParam->iTipoEstrutura);

      $oEstruturaTarifaria->setValorInicial(0);
      if ($oParam->iFaixaConsumoDe && $oParam->iTipoEstrutura == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
        $oEstruturaTarifaria->setValorInicial((integer) $oParam->iFaixaConsumoDe);
      }

      $oEstruturaTarifaria->setValorFinal(0);
      if ($oParam->iFaixaConsumoAte && $oParam->iTipoEstrutura == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
        $oEstruturaTarifaria->setValorFinal((integer) $oParam->iFaixaConsumoAte);
      }

      $oEstruturaTarifaria->setValor(0);
      if ($oParam->nValor && in_array($oParam->iTipoEstrutura, array(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO, AguaEstruturaTarifaria::TIPO_VALOR_FIXO))) {
        $oEstruturaTarifaria->setValor((float) $oParam->nValor);
      }

      $oEstruturaTarifaria->setPercentual(0);
      if ($oParam->iPercentual && $oParam->iTipoEstrutura == AguaEstruturaTarifaria::TIPO_PERCENTUAL) {
        $oEstruturaTarifaria->setPercentual((integer) $oParam->iPercentual);
      }

      $oEstruturaTarifaria->salvar();

      $oRetorno->message = "Estrutura Tarifária salva com sucesso.";

      break;

    case "excluirEstruturaTarifaria":

      if (empty($oParam->iCodigo)) {
        throw new ParameterException('Código não informado.');
      }

      $oEstruturaTarifaria = new AguaEstruturaTarifaria((integer) $oParam->iCodigo);
      $oEstruturaTarifaria->excluir();

      $oRetorno->message = "Estrutura Tarifária excluída com sucesso.";

      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao();
} catch (Exception $exception) {

  db_fim_transacao(true);

  $oRetorno->message = $exception->getMessage();
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
