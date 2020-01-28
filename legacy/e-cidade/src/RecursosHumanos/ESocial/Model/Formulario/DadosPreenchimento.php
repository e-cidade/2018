<?php
namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Essa classe é VO para armazenas os dados do preenchimento de um formulário
 *
 * @package  ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class DadosPreenchimento
{
    /**
     * Tipo do formulário
     * @see Cidade\RecursosHumanos\ESocial\Model\Formulario\Tipo
     *
     * @var integer
     */
    public $tipo;

    /**
     * Identifica quem respondeu o formulário
     *
     * @var integer
     */
    public $responsavel;

    /**
     * Array com as respostas
     *
     * @var \stdClass
     */
    public $respostas = array();
}
