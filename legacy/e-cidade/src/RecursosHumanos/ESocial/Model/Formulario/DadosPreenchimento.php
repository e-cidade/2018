<?php
namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Essa classe � VO para armazenas os dados do preenchimento de um formul�rio
 *
 * @package  ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class DadosPreenchimento
{
    /**
     * Tipo do formul�rio
     * @see Cidade\RecursosHumanos\ESocial\Model\Formulario\Tipo
     *
     * @var integer
     */
    public $tipo;

    /**
     * Identifica quem respondeu o formul�rio
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
