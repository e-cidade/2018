<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\Configuracao\Formulario\Arquivo\Yaml;

use ECidade\Configuracao\Formulario\Arquivo\DumperInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Cria um array apartir de uma Avaliacao
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 *
 * @example $dumper = new Dumper(new \Avaliacao(1));
 *          $data = $dumper->dump(); // array com os dados da avaliacao
 */
class Dumper implements DumperInterface
{
    /**
     * @var Avaliacao
     */
    private $evaluation;

    /**
     * @param Avaliacao $evaluation
     */
    public function __construct(\Avaliacao $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * Organiza a avaliação em um array
     * @return array
     */
    private function dumpEvaluation()
    {
        $data = array(
          'id' => $this->evaluation->getCodigo(),
          'tipo' => $this->evaluation->getTipoAvaliacao(),
          'descricao' => $this->evaluation->getDescricao(),
          'identificador' => $this->evaluation->getIdentificador(),
          'observacao' => $this->evaluation->getObservacao(),
          'permite_edicao' => $this->evaluation->getPermiteEdicao(),
          'ativo' => $this->evaluation->isAtivo(),
          'carga' => $this->evaluation->getSqlCargaDados(),
          'grupos' => $this->dumpGroup($this->evaluation->getGrupos()),
        );

        if (empty($data['grupos'])) {
            unset($data['grupos']);
        }

        return $data;
    }

    /**
     * Organiza os grupos em um array
     * @param  AvaliacaoGrupo[] $groups
     * @return array
     */
    private function dumpGroup($groups)
    {
        $data = array();

        foreach ($groups as $group) {
            $current = array(
              'id' => $group->getCodigo(),
              'descricao' => $group->getDescricao(),
              'identificador' => $group->getIdentificador(),
              'identificador_campo' => $group->getIdentificadorCampo(),
              'perguntas' => $this->dumpQuestions($group->getPerguntas()),
            );

            if (empty($current['perguntas'])) {
               unset($current['perguntas']);
            }

            $data[] = $current;
        }

      return $data;
    }

    /**
     * Organiza as perguntas em um array
     * @param  AvaliacaoPergunta[] $questions
     * @return array
     */
    private function dumpQuestions($questions)
    {
        $data = array();

        foreach ($questions as $question) {
            $current = array(
                'id' => $question->getCodigo(),
                'identificador' => $question->getIdentificador(),
                'descricao' => $question->getDescricao(),
                'tipo_resposta' => $question->getTipo(),
                'ordem' => $question->getOrdem(),
                'obrigatoria' => $question->isObrigatoria(),
                'ativo' => $question->isAtivo(),
                'formula' => $question->getCodigoFormula(),
                'campo_carga' => $question->getCampoCarga(),
                'pergunta_identificadora_formulario' => $question->getPerguntaIdentificadora(),
                'tipo' => $question->getTipoComponente(),
                'mascara' => $question->getMascara(),
                'identificador_campo' => $question->getIdentificadorCampo(),
                'respostas' => $this->dumpOption($question->getOpcoes()),
            );

            if (empty($current['respostas'])) {
                unset($current['respostas']);
            }

            $data[] = $current;
        }

        return $data;
    }

    /**
     * Organiza as opções de resposta em um array
     * @param  AvaliacaoPerguntaOpcao[] $options
     * @return array
     */
    private function dumpOption($options)
    {
        $data = array();

        foreach ($options as $option) {
            $data[] = array(
              'id' => $option->getCodigo(),
              'identificador' => $option->getIdentificador(),
              'descricao' => $option->getDescricao(),
              'valor_resposta' => $option->getValorResposta(),
              'aceita_texto' => $option->getAceitaTexto(),
              'peso' => $option->getPeso(),
              'identificador_campo' => $option->getIdentificadorCampo(),
            );
        }

        return $data;
    }

    /**
     * Converte a Avaliação (Objeto) em um arquivo yaml
     *
     * @return string no formato yaml
     */
    public function dump()
    {
        // array com os dados da avaliacao
        $data = $this->dumpEvaluation();

        // nivel para trocar para inline
        $inline = 7;

        // tamanho do espacamento
        $indent = 2;

        // transforma o array da avaliacao em yaml
        $content = Yaml::dump($data, $inline, $indent);

        // retorna o conteudo em utf8
        return \DBString::utf8_encode_all($content);
    }
}
