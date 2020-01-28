<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Servico;

use Agenda;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\NotificacaoMovimentacaoProcesso;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\NotificacaoMovimentacaoProcessoRepositorio;
use Job;
use stdClass;

/**
 * Class NotificacaoMovimentacaoProcessoServico
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Servico
 */
class NotificacaoMovimentacaoProcessoServico
{
    /**
     * @var NotificacaoMovimentacaoProcessoRepositorio
     */
    private $notificacaoMovimentacaoProcessoRepositorio;

    /**
     * NotificacaoMovimentacaoProcessoServico constructor.
     * @param NotificacaoMovimentacaoProcessoRepositorio $notificacaoMovimentacaoProcessoRepositorio
     */
    public function __construct(NotificacaoMovimentacaoProcessoRepositorio $notificacaoMovimentacaoProcessoRepositorio)
    {
        $this->notificacaoMovimentacaoProcessoRepositorio = $notificacaoMovimentacaoProcessoRepositorio;
    }

    /**
     * @param stdClass $atributos
     * @throws \DBException
     */
    public function salvar(stdClass $atributos)
    {
        $notificacaoMovimentacaoProcesso = new NotificacaoMovimentacaoProcesso();
        $notificacaoMovimentacaoProcesso->setSequencial($atributos->sequencial);
        $notificacaoMovimentacaoProcesso->setAssunto($atributos->assunto);
        $notificacaoMovimentacaoProcesso->setMensagem($atributos->mensagem);
        $notificacaoMovimentacaoProcesso->setDiasPrazoMovimentacao($atributos->diasPrazoMovimentacao);
        $notificacaoMovimentacaoProcesso->setNotificarDataVencimento($atributos->notificarDataVencimento);
        $notificacaoMovimentacaoProcesso->setNotificarReceberProcesso($atributos->notificarReceberProcesso);

        $this->notificacaoMovimentacaoProcessoRepositorio->alterar($notificacaoMovimentacaoProcesso);

        if (!file_exists('jobs/configuracoes/taskManager/fila/NotificacaoMovimentacaoProcesso.task.xml')) {
            $job = new Job();
            $job->setNome('NotificacaoMovimentacaoProcesso');
            $job->setCodigoUsuario(1);
            $job->setDescricao('Notificação de Movimentação do Processo');
            $job->setNomeClasse('NotificacaoMovimentacaoProcessoTask');
            $job->setTipoPeriodicidade(Agenda::PERIODICIDADE_DIARIA);
            $job->adicionarPeriodicidade('0600');
            $job->setCaminhoPrograma('model/contrato/mensageria/NotificacaoMovimentacaoProcessoTask.model.php');
            $job->salvar();
        }
    }

    /**
     * @return \_db_fields|stdClass
     * @throws \DBException
     */
    public function buscar()
    {
        return $this->notificacaoMovimentacaoProcessoRepositorio->buscar();
    }
}
