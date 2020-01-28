<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class FormatterFactory
{

    public static function get($tipo)
    {
        $path = ECIDADE_PATH . DS . 'src' . DS . 'RecursosHumanos' . DS . 'ESocial' . DS . 'Integracao' . DS . 'Formatter'. DS . 'Templates';

        $formatter = new Formatter\Formatter();
        switch ($tipo) {
            case Tipo::S1000:
                $formatter = new Formatter\EmpregadorFormatter();
                $formatter->setDePara(require($path . DS . 'templateEmpregador.php'));
                break;
            case Tipo::S1005:
                $formatter->setDePara(require($path . DS . 'templateEstabelecimentoObras.php'));
                break;
            case Tipo::RUBRICA:
                $formatter->setDePara(require($path . DS . 'templateRubrica.php'));
                break;
            case Tipo::SERVIDOR:
                $formatter->setDePara(require($path . DS . 'templateServidor.php'));
                break;
            default:
                throw new \Exception('Tipo de fomulário não encontrado.');
        }

        return $formatter;
    }
}
