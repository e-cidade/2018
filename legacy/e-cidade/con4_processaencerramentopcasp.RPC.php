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
require_once modification("libs/db_libcontabilidade.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/lancamentoContabil.model.php");

$oParam             = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

$sMensagens = "patrimonial.compras.com4_manifestarinteresseregistroprecoporvalor.";

$aTiposEncerramento = array(
    'rp' => EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR,
    'vp' => EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS,
    'no' => EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE,
    'is' => EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS
  );

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "encerramentosRealizados":

      $oEncerramentoExercicio = new EncerramentoExercicio( new Instituicao(db_getsession("DB_instit")),
                                                           db_getsession("DB_anousu") );

      $aEncerramentos = $oEncerramentoExercicio->getEncerramentosRealizados();

      $aTiposEncerramentoValor  = array_flip($aTiposEncerramento);
      $oRetorno->aEncerramentos = array(
          $aTiposEncerramentoValor[EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR] => in_array(EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR, $aEncerramentos),
          $aTiposEncerramentoValor[EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS] => in_array(EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS, $aEncerramentos),
          $aTiposEncerramentoValor[EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE] => in_array(EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE, $aEncerramentos),
          $aTiposEncerramentoValor[EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS] => in_array(EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS, $aEncerramentos)
        );

      break;

    case "processarEncerramento":

      $oEncerramentoExercicio = new EncerramentoExercicio( new Instituicao(db_getsession("DB_instit")),
                                                           db_getsession("DB_anousu") );

      if (empty($oParam->sTipo)) {
        throw new Exception("Tipo de encerramento n�o informado.");
      }

      if (!in_array($oParam->sTipo, array_keys($aTiposEncerramento))) {
        throw new Exception("O Tipo de encerramento informado � inv�lido.");
      }

      if (empty($oParam->sData)) {
        throw new Exception("Data dos lan�amentos n�o informada.");
      }

      $oDataLancamentos  = new DBDate($oParam->sData);
      $oDataEncerramento = new DBDate( date("d/m/Y", db_getsession("DB_datausu")) );

      $iTipoEncerramento = $aTiposEncerramento[$oParam->sTipo];
      $aEncerramentos    = $oEncerramentoExercicio->getEncerramentosRealizados();

      $oEncerramentoExercicio->setDataEncerramento($oDataEncerramento);
      $oEncerramentoExercicio->setDataLancamento($oDataLancamentos);

      $lEncerramentoSistemaOrcamentario   = in_array(EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE, $aEncerramentos);
      $lEncerramentoRestosPagar           = in_array(EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR, $aEncerramentos);
      $lEncerramentoVariacoesPatrimoniais = in_array(EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS, $aEncerramentos);
      $lEncerramentoImplantacaoSaldos     = in_array(EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS, $aEncerramentos);


      switch ($iTipoEncerramento) {

        case EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE:

          /**
           * Processamento j� foi realizado
           */
          if ($lEncerramentoSistemaOrcamentario && $lEncerramentoRestosPagar) {
            throw new BusinessException('Restos a Pagar / Natureza Or�ament�ria e Controle j� processado para o exerc�cio.');
          }

          /**
           * Tentativa de processar fora de ordem
           */
          if (!$lEncerramentoVariacoesPatrimoniais) {
            throw new BusinessException('O Encerramento das Varia��es Patrimoniais deve ser processado primeiro.');
          }

          $oEncerramentoExercicio->encerrar(EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE);
          $oEncerramentoExercicio->encerrar(EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR);

          break;

        case EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS:

          /**
           * Processamento j� foi realizado
           */
          if ($lEncerramentoVariacoesPatrimoniais) {
            throw new BusinessException('Encerramento das Varia��es Patrimoniais j� processado para o exerc�cio.');
          }

          $oEncerramentoExercicio->encerrar(EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS);

          break;

        case EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS:

          //Validando se ja foi processado.
          if ($lEncerramentoImplantacaoSaldos) {
            throw new BusinessException("Implanta��o de Saldos j� processado para o exerc�cio.");
          }

          //Validando ordem do processamento.
          if (!$lEncerramentoVariacoesPatrimoniais || !$lEncerramentoRestosPagar || !$lEncerramentoSistemaOrcamentario) {

            $sErro  = "A Implanta��o de Saldos deve ser processada ap�s os processamentos do Encerramento das Varia��es ";
            $sErro .= "Patrimoniais e Restos a Pagar / Natureza Or�ament�ria e Controle.";
            throw new BusinessException($sErro);
          }
          $oEncerramentoExercicio->encerrar(EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS);
          break;

        default:
          throw new ParameterException("Tipo de encerramento informado n�o � v�lido.");
      }


      break;

    case "desprocessarEncerramento":

      $oEncerramentoExercicio = new EncerramentoExercicio( new Instituicao(db_getsession("DB_instit")),
                                                           db_getsession("DB_anousu") );

      if (empty($oParam->sTipo)) {
        throw new Exception("Tipo de encerramento n�o informado.");
      }

      if (!in_array($oParam->sTipo, array_keys($aTiposEncerramento))) {
        throw new Exception("O Tipo de encerramento informado � inv�lido.");
      }

      $iTipoEncerramento = $aTiposEncerramento[$oParam->sTipo];
      $aEncerramentos    = $oEncerramentoExercicio->getEncerramentosRealizados();

      $lEncerramentoSistemaOrcamentario   = in_array(EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE, $aEncerramentos);
      $lEncerramentoRestosPagar           = in_array(EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR, $aEncerramentos);
      $lEncerramentoVariacoesPatrimoniais = in_array(EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS, $aEncerramentos);
      $lEncerramentoImplantacaoSaldos     = in_array(EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS, $aEncerramentos);

      if ($iTipoEncerramento == EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE) {

        /**
         * Cancelamento j� foi realizado ou o processamento n�o foi realizado
         */
        if (!$lEncerramentoSistemaOrcamentario && !$lEncerramentoRestosPagar) {
          throw new BusinessException('Restos a Pagar / Natureza Or�ament�ria e Controle n�o processado ou j� cancelado para o exerc�cio.');
        }

        $oEncerramentoExercicio->cancelar(EncerramentoExercicio::ENCERRAR_RESTOS_A_PAGAR);
        $oEncerramentoExercicio->cancelar(EncerramentoExercicio::ENCERRAR_SISTEMA_ORCAMENTARIO_CONTROLE);
      } else if ($iTipoEncerramento == EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS) {

        /**
         * Cancelamento j� foi realizado
         */
        if (!$lEncerramentoVariacoesPatrimoniais) {
          throw new BusinessException('Encerramento das Varia��es Patrimoniais n�o processado ou j� cancelado para o exerc�cio.');
        }

        /**
         * Tentativa de cancelar fora de ordem
         */
        if ($lEncerramentoSistemaOrcamentario && $lEncerramentoRestosPagar) {
          throw new BusinessException('Restos a Pagar / Natureza Or�ament�ria e Controle deve ser cancelado primeiro.');
        }
        $oEncerramentoExercicio->cancelar(EncerramentoExercicio::ENCERRAR_VARIACOES_PATRIMONIAIS);
      } else if($iTipoEncerramento == EncerramentoExercicio::ENCERRAR_IMPLANTACAO_SALDOS) {

        if (!$lEncerramentoImplantacaoSaldos) {
          throw new BusinessException("Implanta��o de Saldos n�o processado ou j� cancelado para o exerc�cio.");
        }
        $oEncerramentoExercicio->cancelarImplantacaoSaldos();
      }

      break;

    case "buscarRegras":

      $oEncerramentoExercicio = new EncerramentoExercicio( new Instituicao(db_getsession("DB_instit")),
                                                           db_getsession("DB_anousu") );

      $oRetorno->aRegras = $oEncerramentoExercicio->getRegrasNaturezaOrcamentaria();

      break;

    case "salvarRegra":

      if (empty($oParam->contadevedora)) {
        throw new Exception("Conta Devedora n�o informada.");
      }

      if (empty($oParam->contacredora)) {
        throw new Exception("Conta Credora n�o informada.");
      }

      $oDaoRegrasEncerramento = new cl_regraencerramentonaturezaorcamentaria();

      $oDaoRegrasEncerramento->c117_sequencial    = null;
      $oDaoRegrasEncerramento->c117_anousu        = db_getsession("DB_anousu");
      $oDaoRegrasEncerramento->c117_instit        = db_getsession("DB_instit");
      $oDaoRegrasEncerramento->c117_contadevedora = $oParam->contadevedora;
      $oDaoRegrasEncerramento->c117_contacredora  = $oParam->contacredora;

      $oDaoRegrasEncerramento->incluir(null);

      if ($oDaoRegrasEncerramento->erro_status == 0) {
        throw new Exception($oDaoRegrasEncerramento->erro_msg);
      }

      break;

    case "removerRegra":

      if (empty($oParam->iCodigoRegra)) {
        throw new Exception("C�digo da Regra n�o informado.");
      }

      $oDaoRegrasEncerramento = new cl_regraencerramentonaturezaorcamentaria();

      $oDaoRegrasEncerramento->excluir( null,
                                        "c117_sequencial = {$oParam->iCodigoRegra} "
                                        . "and c117_anousu = " . db_getsession("DB_anousu")
                                        . " and c117_instit = " . db_getsession("DB_instit") );

      if ($oDaoRegrasEncerramento->erro_status == 0) {
        throw new Exception($oDaoRegrasEncerramento->erro_msg);
      }

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo JSON::create()->stringify($oRetorno);