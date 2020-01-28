<?php
namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Essa classe � VO para armazenar os dados do preenchimento da resposta de uma pergunta de um formul�rio
 *
 * @package  ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class DadosResposta
{
    /**
     * Nome do objeto que ir� agrupar os dados
     *
     * @var string
     */
    public $grupo;

    /**
     * Nome do objeto que ir� receber os dados da resposta.
     *
     * @var string
     */
    public $pergunta;

    /**
     * id da pergunta no formul�rio
     *
     * @var integer
     */
    public $idPergunta;

    /**
     * Valor da op��o de uma resposta
     * Usado quando a pergunta � objetiva ou m�ltipla escolha
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
     * Se � obrigat�rio o preenchimento da pergunta
     *
     * @var se � obrigat�rio a resposta
     */
    public $obrigatoria;
}
