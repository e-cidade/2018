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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\Tributario\Agua\Calculo\Consumo as CalculoConsumo;
use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;

$oParametros = JSON::create()->parse(str_replace('\\', '', $_POST['json']));
$oRetorno = (object) array(
  'mensagem' => null,
  'erro' => false,
);

try {

  switch ($oParametros->exec) {

    case 'calcularConsumo':


      if (!$oParametros->iLeitura) {
        throw new ParameterException('Leitura não informada.');
      }

      if (!$oParametros->iMes) {
        throw new ParameterException('Mês não informado.');
      }

      if (!$oParametros->iAno) {
        throw new ParameterException('Ano não informado.');
      }

      /**
       * Contrato obrigatório a partir da implantação da tarifa.
       */
      if (!$oParametros->iContrato && $oParametros->iAno >= 2017 && $oParametros->iMes >= 7) {
        throw new ParameterException('Contrato não informado.');
      }

      if (!$oParametros->iHidrometro) {
        throw new ParameterException('Hidrômetro não informado.');
      }

      $iLeituraIgnorar = !empty($oParametros->iLeituraIgnorar) ? (int) $oParametros->iLeituraIgnorar : null;
      $iContrato = !empty($oParametros->iContrato) ? (int) $oParametros->iContrato : null;

      $oHidrometro = new AguaHidrometro($oParametros->iHidrometro);
      $oRepository = new LeituraRepository;
      $oLeituraAnterior = $oRepository->findUltimaMesAno(
        (int) $oParametros->iMatricula,
        (int) $oParametros->iMes,
        (int) $oParametros->iAno,
        $iContrato,
        $iLeituraIgnorar
      );

      if (!$oLeituraAnterior) {
        throw new BusinessException("Nenhuma leitura encontrada para o contrato.");
      }

      $iLeituraAnterior = $oLeituraAnterior->getLeitura();
      $iLeituraDigitada = $oParametros->iLeitura;

      $oCalculoConsumo = new CalculoConsumo();
      $oCalculoConsumo->setHidrometro($oHidrometro);
      $iConsumo = $oCalculoConsumo->calcular($iLeituraDigitada, $iLeituraAnterior);

      $oRetorno->lHidrometroVirou = $oCalculoConsumo->hidrometroVirou();
      $oRetorno->iConsumo = $iConsumo;

      break;

    default:

      throw new Exception('Opção é inválida.');
  }

} catch (Exception $oErro) {

  $oRetorno->mensagem = $oErro->getMessage();
  $oRetorno->erro     = true;
}

echo JSON::create()->stringify($oRetorno);
