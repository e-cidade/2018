<?php

namespace ECidade\RecursosHumanos\ESocial;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Preenchimentos;
use ECidade\RecursosHumanos\ESocial\Formatter\DadosPreenchimento as DadosPreenchimentoFormatter;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;

/**
 * Constrói uma coleção com os dados para o envio do eSocial
 *
 * @package ECidade\RecursosHumanos\ESocial
 */
class DadosESocial
{
    private $tipo;

    private $dados;

    /**
     * Responsável pelo preenchimento do formulário
     *
     * @var mixed
     */
    private $responsavelPreenchimento;

    /**
     * Informa o responsável pelo preenchimento. Se não indormado, busca de todos
     *
     * @param mixed $responsavel
     */
    public function setReponsavelPeloPreenchimento($responsavel)
    {
        $this->responsavelPreenchimento = $responsavel;
    }

    /**
     * Retorna todos os preenchimentos e suas respostas para o tipo informado
     *
     * @param integer $tipo
     * @return ECidade\RecursosHumanos\ESocial\Model\Formulario\DadosPreenchimento[]
     */
    public function getPorTipo($tipo)
    {
        $this->tipo = $tipo;
        $preenchimentos = $this->buscaPreenchimentos();

        $this->buscaRespostas($preenchimentos);

        /**
         * @todo Quando for o empregador, temos que buscar os dados da escala do servidor do e-cidade.
         *       Não é possível representar a escala do servidor no formulário.
         *       Talvez outras informações de outros cadastros também serão buscadas do e-cidade
         */
        if ($tipo == Tipo::EMPREGADOR) {

        }

        return  $this->dados;
    }

    /**
     * Busca os preenchimentos conforme o tipo de formulário informado
     *
     * @throws \Exception
     * @return \stdClass[]
     */
    private function buscaPreenchimentos()
    {
        $configuracao = new Configuracao();
        $formularioId = $configuracao->getFormulario($this->tipo);
        $preenchimento = new Preenchimentos();
        $preenchimento->setReponsavelPeloPreenchimento($this->responsavelPreenchimento);
        switch ($this->tipo) {
            case Tipo::SERVIDOR:
                return $preenchimento->buscarUltimoPreenchimentoServidor($formularioId);
            case Tipo::EMPREGADOR:
                return $preenchimento->buscarUltimoPreenchimentoEmpregador($formularioId);
            case Tipo::RUBRICA:
                return $preenchimento->buscarUltimoPreenchimento($formularioId);
            default:
                throw new Exception('Tipo não encontrado.');
        }
    }

    /**
     * Busca as respostas de um preenchimento do formulário
     *
     * @param integer $preenchimentos
     */
    private function buscaRespostas($preenchimentos)
    {
        $dadosPreechimento = new DadosPreenchimentoFormatter();
        foreach ($preenchimentos as $preenchimento) {
            $this->dados[] = $dadosPreechimento->formatar(
                $this->tipo,
                $this->identificaResponsavel($preenchimento),
                $preenchimento->inscricao_empregador,
                Preenchimentos::buscaRespostas($preenchimento->preenchimento)
            );
        }
    }


    /**
     * Identifica o responsável pelo preenchimento
     * O responsável é a figura "dona" das respostas/ que preencheu o formulário
     *
     * @param \stdClass $preenchimento
     * @throws \Exception
     * @return integer
     */
    private function identificaResponsavel(\stdClass $preenchimento)
    {
        switch ($this->tipo) {
            case Tipo::SERVIDOR:
                return $preenchimento->matricula;
            case Tipo::EMPREGADOR:
                return $preenchimento->cgm;
            case Tipo::RUBRICA:
                return $preenchimento->pk;
            default:
                throw new Exception('Tipo não encontrado.');
        }
    }
}
