<?php

require_once modification('model/configuracao/Task.model.php');
require_once modification('interfaces/iTarefa.interface.php');
require_once modification('integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php');
require_once modification('libs/db_stdlib.php');

use DBseller\Mensageria\Library\Cliente;
use ECidade\Patrimonial\Protocolo\Modelo\Processo;
use ECidade\Patrimonial\Protocolo\Repositorio\ProcessoRepositorio;

class NotificacaoMovimentacaoProcessoTask extends \Task implements \iTarefa
{

    public function iniciar()
    {
        parent::iniciar();

        try {
            $rsMensageriaProcesso = db_query("SELECT * FROM mensageriaprocesso LIMIT 1");

            if (!$rsMensageriaProcesso) {
                throw new DBException('Não foi possível buscar os dados na notificação padrão.');
            }

            $oNotificacao = db_utils::fieldsMemory($rsMensageriaProcesso, 0);

            if ($oNotificacao->p101_notificardatavencimento == 'f') {
                return;
            }

            $aProcessosVencidos = ProcessoRepositorio::vencidos();

            $oInstituicao = new Instituicao();
            $oPrefeitura = $oInstituicao->getDadosPrefeitura();
            $sSistema = 'e-cidade.' . strtolower($oPrefeitura->getMunicipio());

            array_map(function (Processo $oProcesso) use ($oNotificacao, $sSistema) {
                $oDataUltimaMovimentacao = $oProcesso->getData();
                $iNumeroProcesso = $oProcesso->getNumero();
                $iAno = $oProcesso->getAno();
                $sDataUltimaMovimentacao = $oDataUltimaMovimentacao->getDate(DBDate::DATA_PTBR);
                $sDataLimiteParaMovimentacao = $oDataUltimaMovimentacao->adiantarPeriodo($oNotificacao->p101_diasprazo,
                    'd')->getDate(DBDate::DATA_PTBR);

                $sConteudo = str_replace(
                    array('[numero]', '[ano]', '[data_final]', '[data_inicial]'),
                    array($iNumeroProcesso, $iAno, $sDataLimiteParaMovimentacao, $sDataUltimaMovimentacao),
                    $oNotificacao->p101_mensagem
                );

                $aMensagem = array(
                    'iTipo' => Cliente::TIPO_NOTIFICACAO,
                    'sAssunto' => $oNotificacao->p101_assunto,
                    'sConteudo' => $sConteudo,
                    'aDestinatarios' => array(array('sLogin' => $oProcesso->getLogin(), 'sSistema' => $sSistema))
                );

                Cliente::enviar(db_getsession('DB_login'), $sSistema, $aMensagem);
            }, $aProcessosVencidos);
        } catch (Exception $oException) {
            $this->log('Erro: ' . $oException->getMessage());
            return;
        }

        parent::terminar();
    }

    public function cancelar()
    {
    }
}
