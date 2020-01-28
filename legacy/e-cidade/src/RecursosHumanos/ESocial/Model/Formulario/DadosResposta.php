<?php
namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Essa classe й VO para armazenar os dados do preenchimento da resposta de uma pergunta de um formulбrio
 *
 * @package  ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class DadosResposta
{
    /**
     * Nome do objeto que irб agrupar os dados
     *
     * @var string
     */
    public $grupo;

    /**
     * Nome do objeto que irб receber os dados da resposta.
     *
     * @var string
     */
    public $pergunta;

    /**
     * id da pergunta no formulбrio
     *
     * @var integer
     */
    public $idPergunta;

    /**
     * Valor da opзгo de uma resposta
     * Usado quando a pergunta й objetiva ou mъltipla escolha
     *
     * @var string
     */
    public $valorResposta;

    /**
     * Valor da reposta para perguntas dissertativas
     *
     * @var string
     */
    public $resposta;

    /**
     * Tipo da pergunta
     *
     * @var integer
     */
    public $tipoPergunta;

    /**
     * Se й obrigatуrio o preenchimento da pergunta
     *
     * @var se й obrigatуrio a resposta
     */
    public $obrigatoria;
}
