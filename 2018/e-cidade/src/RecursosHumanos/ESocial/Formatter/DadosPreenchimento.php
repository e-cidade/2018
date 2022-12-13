<?php

namespace ECidade\RecursosHumanos\ESocial\Formatter;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\DadosPreenchimento as ModelDadosPreenchimento;

/**
 * Reponsável por formatar os dados do preenchimento
 *
 * @package ECidade\RecursosHumanos\ESocial\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class DadosPreenchimento
{
    /**
     * Formata um preenchimento
     *
     * @param integer $tipo
     * @param integer $responsavel
     * @param DadosResposta[] $respostas
     * @return ModelDadosPreenchimento[]
     */
    public function formatar($tipo, $responsavel, $inscricaoEmpregador, array $respostas)
    {
        $dadosPreechimento = new ModelDadosPreenchimento();
        $dadosPreechimento->tipo = $tipo;
        $dadosPreechimento->responsavel = $responsavel;
        $dadosPreechimento->inscricao_empregador = $inscricaoEmpregador;
        $dadosPreechimento->tipo_inscricao = strlen($inscricaoEmpregador) == 11 ? 'cpf' : 'cnpj';
        $dadosPreechimento->respostas = $this->formataRespostas($respostas);
        return $dadosPreechimento;
    }

    /**
     * Organiza os dados das respostas de acordo com os grupos e perguntas
     *
     * @param array $respostas
     * @return array
     */
    private function formataRespostas($respostas)
    {
        $respostasFormatadas = array();
        foreach ($respostas as $resposta) {
            if (!\array_key_exists($resposta->grupo, $respostasFormatadas)) {
                $this->criaGrupo($resposta, $respostasFormatadas);
            }

            if (!\array_key_exists($resposta->pergunta, $respostasFormatadas[$resposta->grupo]->perguntas)) {
                $this->criaPergunta($resposta, $respostasFormatadas);
            }

            $dado = new \stdClass();
            $dado->idPergunta = $resposta->idPergunta;
            $dado->resposta = $resposta->resposta;
            if (in_array($resposta->tipoPergunta, array(1,3))) {
                $dado->resposta = $resposta->valorResposta;
            }

            $dado->tipoPergunta = $resposta->tipoPergunta;
            $dado->obrigatoria = $resposta->obrigatoria;
            $respostasFormatadas[$resposta->grupo]->perguntas[$resposta->pergunta]->resposta = $dado;
        }
        return $respostasFormatadas;
    }

    /**
     * Cria o objeto para o grupo
     *
     * @param array $resposta
     * @param array $respostasFormatadas
     */
    private function criaGrupo($resposta, &$respostasFormatadas)
    {
        $grupo = new \stdClass();
        $grupo->nome = $resposta->grupo;
        $grupo->perguntas = array();
        $respostasFormatadas[$resposta->grupo] = $grupo;
    }

    /**
     * Cria o objeto para resposta
     *
     * @param array $resposta
     * @param array $respostasFormatadas
     */
    private function criaPergunta($resposta, &$respostasFormatadas)
    {
        $pergunta = new \stdClass();
        $pergunta->nome = $resposta->pergunta;
        $respostasFormatadas[$resposta->grupo]->perguntas[$resposta->pergunta] = $pergunta;
    }
}
