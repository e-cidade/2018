<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Formata os dados do preenchimento no padrão esperado pela API
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class Formatter
{
    protected $dePara = array();

    /**
     * Define o template do recurso a ser formatado
     *
     * @param array $dePara
     */
    public function setDePara($dePara)
    {
        $this->dePara = $dePara;
    }

    /**
     * Formata o dados do formulário de acordo com o template
     *
     * @param array $dados
     * @return void
     */
    public function formatar($dados)
    {
        $aDadosIntegracao = array();

        foreach ($dados as $dadosPreenchimento) {
            $aDadosIntegracao[] = $this->formataPreenchimento($dadosPreenchimento);
        }
        return $aDadosIntegracao;
    }

    /**
     * Cria o objeto a ser enviado para a API já formatado
     *
     * @param \stdClass $dadosPreenchimento
     * @return \stdClass
     */
    private function formataPreenchimento($dadosPreenchimento)
    {
        $preenchimento = new \stdClass();
        $preenchimento->inscricao_empregador = $dadosPreenchimento->inscricao_empregador;
        $preenchimento->referencia  = $dadosPreenchimento->responsavel;

        $preenchimento = $this->criarGrupo($preenchimento, $this->dePara, $dadosPreenchimento);
        return $preenchimento;
    }

    /**
     * Cria o nível do grupo de dados.
     * Esse metodo é recursivo, criando os subgrupos também
     *
     * @param \stdClass $preenchimento      objeto onde vai ser criado o grupo
     * @param array    $dePara             com os dados do depara do grupo criado
     * @param \stdClass $dadosPreenchimento dados do preenchimento do formulário no e-cidade
     * @return \stdClass
     */
    private function criarGrupo($preenchimento, $dePara, $dadosPreenchimento)
    {
        foreach ($dePara as $key => $dadosDePara) {
            // Valida se o grupo existe no array
            if (!isset($dadosPreenchimento->respostas[$key])) {
                continue;
            }
            // pega o que foi respondido no grupo
            $respostasPerguntasFormulario = $dadosPreenchimento->respostas[$key]->perguntas;

            $sNomeGrupo = $key;
            //Valida se o nome do grupo esta
            if (isset($dadosDePara['nome_api'])) {
                $sNomeGrupo = $dadosDePara['nome_api'];
            }

            // cria o objeto do grupo para envio na API
            $preenchimento->{$sNomeGrupo} = new \stdClass();
            // Quando o grupo é uma coleção de dados
            if (isset($dadosDePara['type']) && $dadosDePara['type'] == 'array') {
                $preenchimento->{$sNomeGrupo} = array();
            }
            if (isset($dadosDePara['items'])) {
                $this->criarItens(
                    $preenchimento->{$sNomeGrupo},
                    $dadosDePara['items'],
                    $respostasPerguntasFormulario
                );
            }

            // valida se existe array de propriedades
            if (isset($dadosDePara['properties'])) {
                $this->criaPropriedades(
                    $preenchimento->{$sNomeGrupo},
                    $dadosDePara['properties'],
                    $respostasPerguntasFormulario
                );
            }

            // Se o grupo atual tem subgrupo
            if (isset($dadosDePara['groups'])) {
                $preenchimento->{$sNomeGrupo} = $this->criarGrupo($preenchimento->{$sNomeGrupo}, $dadosDePara['groups'], $dadosPreenchimento);
            }
        }
        return $preenchimento;
    }

    /**
     * Cria os itens de uma coleção de dados
     * @todo não foi implementado com vários filhos pois no e-cidade no momento da criação não tinhamos o caso
     *
     * @param array $grupo atual
     * @param array $itens
     * @param array $respostasPerguntasFormulario contendo as respostas do grupo percorrido
     */
    private function criarItens(array &$grupo, $itens, $respostasPerguntasFormulario)
    {
        $data = new \stdClass();
        $this->criaPropriedades($data, $itens['properties'], $respostasPerguntasFormulario);
        $grupo[] = $data;
    }

    /**
     * Cria as propriedades do grupo recebido por parâmetro
     *
     * @param \stdClass $grupo
     * @param array $propriedades
     * @param array $respostasPerguntasFormulario
     */
    private function criaPropriedades($grupo, $propriedades, $respostasPerguntasFormulario)
    {
        foreach ($propriedades as $propriedade => $valor) {
            $nomeProrpriedade = is_int($propriedade) ? $valor : $propriedade;

            // valida se a propriedade do template existe nos dados recebidos
            if (!isset($respostasPerguntasFormulario[$nomeProrpriedade])) {
                continue;
            }

            // Se for um array
            if (is_array($valor)) {
                // Não aplica o cast caso valor seja vazio
                $valorCampo = $respostasPerguntasFormulario[$nomeProrpriedade]->resposta->resposta;
                if ($valorCampo !== '' && isset($valor['type'])) {
                    settype($valorCampo, $valor['type']);
                }

                if (isset($valor['type']) && in_array($valor['type'], array('int', 'float')) && $valorCampo === '') {
                    $valorCampo = null;
                }

                if (isset($valor['nome_api'])) {
                    $nomeProrpriedade = $valor['nome_api'];
                }

                $grupo->{$nomeProrpriedade} = $valorCampo;
            } else {
                $grupo->{$valor} = $respostasPerguntasFormulario[$nomeProrpriedade]->resposta->resposta;
            }
        }
        return $grupo;
    }
}
