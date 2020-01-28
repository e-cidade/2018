<?php

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\NotificacaoMovimentacaoProcessoRepositorio;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Servico\NotificacaoMovimentacaoProcessoServico;

$post = (object)filter_input_array(INPUT_POST);
$status = 200;
$mensagem = '';
$dados = array();

$notificacaoMovimentacaoProcessoRepositorio = new NotificacaoMovimentacaoProcessoRepositorio();
$notificacaoMovimentacaoProcessoServico = new NotificacaoMovimentacaoProcessoServico($notificacaoMovimentacaoProcessoRepositorio);

try {
    switch ($post->acao) {
        case 'salvar':
            $mensageria = JSON::create()->parse($post->mensageria);
            $notificacaoMovimentacaoProcessoServico->salvar($mensageria);
            $mensagem = 'Configuração da notificação salva com sucesso!';
            break;
        case 'buscar':
            $dados = $notificacaoMovimentacaoProcessoServico->buscar();
            break;
    }
} catch (Exception $exception) {
    $mensagem = $exception->getMessage();
    $status = 400;
}

echo JSON::create()->stringify(array(
    'mensagem' => $mensagem,
    'dados' => $dados
));

exit($status);


