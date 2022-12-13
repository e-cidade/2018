<?php

namespace ECidade\Patrimonial\Protocolo\Modelo;

/**
 * Class Processo
 * @package ECidade\Patrimonial\Protocolo\Modelo
 */
class Processo
{
    /**
     * @var int
     */
    private $codigo;
    /**
     * @var string
     */
    private $dataCriacao;
    /**
     * @var string
     */
    private $data;
    /**
     * @var string
     */
    private $hora;
    /**
     * @var string
     */
    private $departamento;
    /**
     * @var string
     */
    private $usuario;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $assunto;
    /**
     * @var int
     */
    private $codigoDepartamento;
    /**
     * @var int
     */
    private $codigoUsuario;
    /**
     * @var int
     */
    private $iNumero;
    /**
     * @var int
     */
    private $iAno;

    /**
     * Processo constructor.
     * @param $codigo
     * @param $dataCriacao
     * @param $data
     * @param $hora
     * @param $departamento
     * @param $usuario
     * @param $login
     * @param $assunto
     * @param $codigoDepartamento
     * @param $codigoUsuario
     * @param $iNumero
     * @param $iAno
     */
    public function __construct(
        $codigo,
        $dataCriacao,
        $data,
        $hora,
        $departamento,
        $usuario,
        $login,
        $assunto,
        $codigoDepartamento,
        $codigoUsuario,
        $iNumero,
        $iAno
    ) {
        $this->codigo = $codigo;
        $this->dataCriacao = $dataCriacao;
        $this->data = $data;
        $this->hora = $hora;
        $this->departamento = $departamento;
        $this->usuario = $usuario;
        $this->login = $login;
        $this->assunto = $assunto;
        $this->codigoDepartamento = $codigoDepartamento;
        $this->codigoUsuario = $codigoUsuario;
        $this->iNumero = $iNumero;
        $this->iAno = $iAno;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return \DBDate
     */
    public function getDataCriacao()
    {
        return new \DBDate($this->dataCriacao);
    }

    /**
     * @param $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * @return \DBDate
     */
    public function getData()
    {
        return new \DBDate($this->data);
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return string
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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
     * @return int
     */
    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    /**
     * @param $codigoDepartamento
     */
    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;
    }

    /**
     * @return int
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->iNumero;
    }

    /**
     * @param $iNumero
     */
    public function setNumero($iNumero)
    {
        $this->iNumero = $iNumero;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->iAno;
    }

    /**
     * @param $iAno
     */
    public function setAno($iAno)
    {
        $this->iAno = $iAno;
    }
}
