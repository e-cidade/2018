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

use ECidade\Configuracao\Formulario\Arquivo\ParserInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Realiza parse de um arquivo yaml
 *
 * @author Andrio Costa <andrio.ac@gmail.com>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 *
 * @example $parser = new Parser('/caminho_arquivo/arquivo.yml');
 *          $data = $parser->parse(); // Instancia de uma \Avaliacao
 */
class Parser implements ParserInterface
{
    /**
     * @var \Avaliacao
     */
    private $evaluation;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @throws \ParseException
     * @return \Avaliacao
     */
    public function parse()
    {
        $data = Yaml::parse(file_get_contents($this->path));
        $data = \DBString::utf8_decode_all($data);

        $this->createEvaluation($data);

        return $this->evaluation;
    }

    /**
     * Valida a existencia do index no arquivo retornando o valor se existir.
     * Se não retorna um valor default conforme
     * o tipo de dado do campo
     * @param  Array   $data  array com os dados
     * @param  string  $index
     * @param  mixed   $default valor default do campo de acordo com o tipo de dados
     * @return mixed
     */
    private function getValue(array $data, $index, $default = null)
    {

        if (isset($data[$index])) {
            return $data[$index];
        }

        return $default;
    }

    /**
     * Cria instância do Formuário / Avaliação
     * @param  array|null  $data
     * @return \Avaliacao|boolean
     */
    private function createEvaluation(array & $data = null)
    {
        if (empty($data)) {
            return false;
        }

        $this->evaluation = new \Avaliacao();
        $this->evaluation->setCodigo($this->getValue($data, 'id', null));
        $this->evaluation->setTipoAvaliacao($this->getValue($data, 'tipo', null));
        $this->evaluation->setDescricao($this->getValue($data, 'descricao', ''));
        $this->evaluation->setIdentificador($this->getValue($data, 'identificador', ''));
        $this->evaluation->setObservacao($this->getValue($data, 'observacao', ''));
        $this->evaluation->setPermiteEdicao($this->getValue($data, 'permite_edicao', true));
        $this->evaluation->setAtivo($this->getValue($data, 'ativo', true));
        $this->evaluation->setSqlCargaDados($this->getValue($data, 'carga', ''));

        $this->createGroups($data['grupos']);

        return $this->evaluation;
    }

    /**
     * Cria instância dos grupos do Formuário / Avaliação
     * @param  array|null  $groups array com os grupos
     * @return boolean
     */
    private function createGroups(array & $groups = null)
    {
        if (empty($groups)) {
            return false;
        }

        foreach ($groups as $dataGroup) {
            $group = new \AvaliacaoGrupo();
            $group->setCodigo($this->getValue($dataGroup, 'id', null));
            $group->setDescricao($this->getValue($dataGroup, 'descricao', ''));
            $group->setIdentificador($this->getValue($dataGroup, 'identificador', ''));
            $group->setIdentificadorCampo($this->getValue($dataGroup, 'identificador_campo', null));

            if (!$this->evaluation->getCodigo()) {
                $group->setCodigo(null);
            }

            $this->createQuestion($dataGroup['perguntas'], $group);

            $this->evaluation->addGrupo($group);
        }

        return true;
    }

    /**
     * Cria instâncias das perguntas de cada grupo
     * @param  array|null      $questions perguntas
     * @param  \AvaliacaoGrupo $group     instância do grupo das perguntas
     * @return boolean
     */
    private function createQuestion(array & $questions = null, \AvaliacaoGrupo $group)
    {
        if (empty($questions)) {
            return false;
        }

        foreach ($questions as $key => $dataQuestion) {
            $question = new \AvaliacaoPergunta();

            $question->setCodigo($this->getValue($dataQuestion, 'id', null));
            $question->setIdentificador($this->getValue($dataQuestion, 'identificador', ''));
            $question->setDescricao($this->getValue($dataQuestion, 'descricao', ''));
            $question->setTipo($this->getValue($dataQuestion, 'tipo_resposta', null));
            $question->setObrigatoria($this->getValue($dataQuestion, 'obrigatoria', true));
            $question->setAtivo($this->getValue($dataQuestion, 'ativo', true));
            $question->setCodigoFormula($this->getValue($dataQuestion, 'formula', null));
            $question->setCampoCarga($this->getValue($dataQuestion, 'campo_carga', ''));
            $question->setPerguntaIdentificadora($this->getValue($dataQuestion, 'pergunta_identificadora_formulario', false));
            $question->setTipoComponente($this->getValue($dataQuestion, 'tipo', 1));
            $question->setMascara($this->getValue($dataQuestion, 'mascara', ''));
            $question->setIdentificadorCampo($this->getValue($dataQuestion, 'identificador_campo', null));

            // ordena pelo indice, ignorando campo ordem informado no arquivo,
            $question->setOrdem($key + 1);

            if (!$group->getCodigo()) {
                $question->setCodigo(null);
            }

            $this->createOptionAnswer($dataQuestion['respostas'], $question);

            $group->addPergunta($question);
        }

        return true;
    }

    /**
     * Cria opcoes de resposta
     * @param  array             $options
     * @param  \AvaliacaoPergunta $question
     * @return boolean
     */
    private function createOptionAnswer(array & $options = null, \AvaliacaoPergunta $question)
    {
        if (empty($options)) {
            return false;
        }

        foreach ($options as $dataOption) {
            $option = new \AvaliacaoPerguntaOpcao();
            $option->setCodigo($this->getValue($dataOption, 'id', null));
            $option->setIdentificador($this->getValue($dataOption, 'identificador', ''));
            $option->setDescricao($this->getValue($dataOption, 'descricao', ''));
            $option->setValorResposta($this->getValue($dataOption, 'valor_resposta', ''));
            $option->setAceitaTexto($this->getValue($dataOption, 'aceita_texto', false));
            $option->setPeso($this->getValue($dataOption, 'peso', null));
            $option->setIdentificadorCampo($this->getValue($dataOption, 'identificador_campo', null));

            if (!$question->getCodigo()) {
                $option->setCodigo(null);
            }
            $question->addOpcao($option);
        }

        return true;
    }
}
