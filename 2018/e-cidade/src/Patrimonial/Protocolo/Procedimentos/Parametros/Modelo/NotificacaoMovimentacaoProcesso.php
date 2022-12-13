<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo;

/**
 * Class NotificacaoMovimentacaoProcesso
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo
 */
class NotificacaoMovimentacaoProcesso
{
    /**
     * @var
     */
    private $sequencial;
    /**
     * @var int
     */
    private $notificarReceberProcesso;
    /**
     * @var int
     */
    private $notificarDataVencimento;
    /**
     * @var string
     */
    private $assunto;
    /**
     * @var string
     */
    private $mensagem;
    /**
     * @var int
     */
    private $diasPrazoMovimentacao;

    /**
     * NotificacaoMovimentacaoProcesso constructor.
     * @param int $notificarReceberProcesso
     * @param int $notificarDataVencimento
     * @param string $assunto
     * @param string $mensagem
     * @param int $diasPrazoMovimentacao
     */
    public function __construct(
        $notificarReceberProcesso = 0,
        $notificarDataVencimento = 0,
        $assunto = '',
        $mensagem = 'O prazo para movimentar o processo [numero] / [ano] venceu dia [data_final] e deve ter andamento.',
        $diasPrazoMovimentacao = 0
    ) {
        $this->notificarReceberProcesso = $notificarReceberProcesso;
        $this->notificarDataVencimento = $notificarDataVencimento;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
        $this->diasPrazoMovimentacao = $diasPrazoMovimentacao;
    }

    /**
     * @return int
     */
    public function getNotificarReceberProcesso()
    {
        return $this->notificarReceberProcesso;
    }

    /**
     * @param $notificarReceberProcesso
     */
    public function setNotificarReceberProcesso($notificarReceberProcesso)
    {
        $this->notificarReceberProcesso = $notificarReceberProcesso;
    }

    /**
     * @return int
     */
    public function getNotificarDataVencimento()
    {
        return $this->notificarDataVencimento;
    }

    /**
     * @param $notificarDataVencimento
     */
    public function setNotificarDataVencimento($notificarDataVencimento)
    {
        $this->notificarDataVencimento = $notificarDataVencimento;
    }

    /**
     * @return string
     */
    public function getAssunto()
    {
        return $this->assunto;
    }

    /**
     * @param $assunto
     */
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * @return int
     */
    public function getDiasPrazoMovimentacao()
    {
        return $this->diasPrazoMovimentacao;
    }

    /**
     * @param $diasPrazoMovimentacao
     */
    public function setDiasPrazoMovimentacao($diasPrazoMovimentacao)
    {
        $this->diasPrazoMovimentacao = $diasPrazoMovimentacao;
    }

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }
}
