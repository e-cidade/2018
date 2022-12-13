<?php

namespace ECidade\Api\V1\Controllers\Configuracao\Transformers;

use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\ParameterBag;

class FormularioTransformer extends TransformerAbstract
{

    private $fields;

    public function setFields(ParameterBag $fields)
    {
        $this->fields = $fields;
    }

    public function transform($formulario)
    {
        return $formulario;
    }

    private function filter($formulario)
    {
        return $formulario;
    }
}
