<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio;

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\Gestor;

/**
 *
 */
class GestaoProcesso extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var GestaoProcesso
     */
    protected static $oInstance;

    /**
     * @param $codigo
     * @return ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\Gestor
     * @throws \BusinessException
     */
    public static function getById($codigo)
    {
        return self::getInstanciaPorCodigo($codigo);
    }


    /**
     * Constroi a instancia da classe
     * @param $codigo
     * @return ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\Gestor
     */
    protected function make($codigo)
    {
        if (empty($codigo)) {
            throw new \BusinessException('Não foi informado o código do gestor.');
        }

        $oGestor = new Gestor($codigo);
        return $oGestor;
    }
}
