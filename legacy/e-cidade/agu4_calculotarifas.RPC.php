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

use \ECidade\Tributario\Agua\Calculo\Calculo;

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  'mensagem' => null,
  'erro'     => false,
);

try {

  switch ($oParam->exec) {

    /**
     * Calculo Parcial de Tarifas
     */
    case 'calculoParcial':

      db_inicio_transacao();

      if (empty($oParam->iContrato)) {
        throw new ParameterException('Código de contrato não inforamdo.');
      }

      if (empty($oParam->iAno)) {
        throw new ParameterException('Ano não informado.');
      }

      if (empty($oParam->iMesInicial) || empty($oParam->iMesFinal)) {
        throw new ParameterException('Mês Inicial/Final não informado.');
      }

      if ($oParam->iMesFinal < $oParam->iMesInicial) {
        throw new ParameterException('Mês Inicial não pode ser maior que Mês Final.');
      }

      $oInstituicao = new Instituicao(db_getsession('DB_instit'));
      if (!$oInstituicao->getUsaSisagua()) {
        throw new BusinessException('O cálculo deve ser executado na instituição configurada para o módulo Água.');
      }

      $oDadosExportacao = new clExpDadosColetores;
      $iTipoDebito      = $oDadosExportacao->getArretipo($oParam->iAno);

      $iAno = (integer) $oParam->iAno;
      if (!$iTipoDebito) {
        throw new BusinessException("Tipo de Débito não encontrado para o ano de {$iAno}");
      }

      $sArquivoLog = "tmp/agua_calculo_" . time();

      $oLog = new DBLog("TXT", $sArquivoLog);
      $oLog->escreverLog("Cálculo iniciado.");

      try {

        $oContrato = new AguaContrato((integer) $oParam->iContrato);
        $oCalculoTarifas = new Calculo();
        $oCalculoTarifas->setContrato($oContrato);
        $oCalculoTarifas->setAno($iAno);
        $oCalculoTarifas->setCodigoUsuario(db_getsession('DB_id_usuario'));
        $oCalculoTarifas->setTipoDebito($iTipoDebito);
        $oCalculoTarifas->setMesInicial((integer) $oParam->iMesInicial);
        $oCalculoTarifas->setMesFinal((integer) $oParam->iMesFinal);
        $oCalculoTarifas->setLogger($oLog);
        $oCalculoTarifas->processar();
      } catch (Exception  $oErro) {

        db_fim_transacao($lErro = true);
        $oLog->escreverLog("Contrato: {$oContrato->getCodigo()} - {$oErro->getMessage()}");
      }

      $oLog->escreverLog("Cálculo finalizado.");

      $oRetorno->arquivo = $oLog->getArquivo();
      $oRetorno->mensagem = 'Cálculo concluído.';

      db_fim_transacao($lErro = false);

      break;

    default:
      throw new Exception('Opção é inválida.');
  }

} catch (Exception $exception) {

  db_fim_transacao($lErro = true);

  $oRetorno->mensagem = $exception->getMessage();
  $oRetorno->erro     = $lErro;
}

echo JSON::create()->stringify($oRetorno);
