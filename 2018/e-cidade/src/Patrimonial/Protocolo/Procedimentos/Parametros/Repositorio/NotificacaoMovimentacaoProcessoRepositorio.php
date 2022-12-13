<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio;

require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));

use db_utils;
use DBException;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\NotificacaoMovimentacaoProcesso;

/**
 * Class NotificacaoMovimentacaoProcessoRepositorio
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio
 */
class NotificacaoMovimentacaoProcessoRepositorio
{
    /**
     * @var string
     */
    private $tabela = 'mensageriaprocesso';

    /**
     * @return \_db_fields|\stdClass
     * @throws DBException
     */
    public function buscar()
    {
        $query = "SELECT * FROM {$this->tabela} LIMIT 1";

        $resultado = db_query($query);

        if ($resultado === false) {
            throw new DBException('Não foi possível buscar a configuração das notificações.');
        }

        return db_utils::fieldsMemory($resultado, 0);
    }

    /**
     * @param NotificacaoMovimentacaoProcesso $notificacaoMovimentacaoProcesso
     * @throws DBException
     */
    public function alterar(NotificacaoMovimentacaoProcesso $notificacaoMovimentacaoProcesso)
    {
        db_inicio_transacao();

        $dias = $notificacaoMovimentacaoProcesso->getDiasPrazoMovimentacao() ?: 0;

        $query = "
            UPDATE {$this->tabela} SET
                p101_assunto = '{$notificacaoMovimentacaoProcesso->getAssunto()}',
                p101_mensagem = '{$notificacaoMovimentacaoProcesso->getMensagem()}',
                p101_diasprazo = {$dias},
                p101_notificarreceberprocesso = '{$notificacaoMovimentacaoProcesso->getNotificarReceberProcesso()}',
                p101_notificardatavencimento = '{$notificacaoMovimentacaoProcesso->getNotificarDataVencimento()}'
            WHERE p101_sequencial = {$notificacaoMovimentacaoProcesso->getSequencial()}
        ";

        $resultado = db_query($query);

        if (!$resultado) {
            db_fim_transacao(true);
            throw new DBException('Não foi possível salvar a configuração da notificação.');
        }

        db_fim_transacao();
    }
}
