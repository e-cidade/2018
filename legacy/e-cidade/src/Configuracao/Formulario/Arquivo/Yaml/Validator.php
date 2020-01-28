<?php

namespace ECidade\Configuracao\Formulario\Arquivo\Yaml;

use ECidade\Configuracao\Formulario\Arquivo\ValidatorInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Realiza as validações no arquivo yaml
 * @author Andrio Costa <andrio.ac@gmail.com>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 *
 *
 * @example $parser = new Parser('/caminho_arquivo/arquivo.yml');
 *          $validator = new Validator($parser);
 *          if (! $validator->validate() ) {
 *            $errors_message = $validator->getErrors();
 *          }
 */
class Validator implements ValidatorInterface
{
  /**
   * Parser
   *
   * @var \ECidade\Configuracao\Formulario\Importacao\Yaml\Parser
   */
  private $parser;

  /**
   * Conteudo do arquivo parseado
   *
   * @var array
   */
  private $yamlData = array();

  /**
   * Erros do arquivo
   *
   * @var string[]
   */
  private $errors = array();

  /**
   * Campos obrigatorios
   *
   * @var array
   */
  private $requiredFields = array(
    'formulario' => array(
      'tipo',
      'descricao',
      'observacao'
    ),
    'grupos' => array(
      'descricao'
    ),
    'perguntas' => array(
      'descricao',
      'tipo_resposta',
    ),
    'respostas' => array(
      'descricao',
    )
  );

  /**
   * @param string $parser
   */
  public function __construct($path)
  {
    $this->path = $path;
  }

  /**
   * Retorna mensagens de erros
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->formatMessageErrors();
  }

  /**
   * Realiza a validação
   *
   * @return void
   */
  public function validate()
  {
    try {

      $this->yamlData = Yaml::parse(file_get_contents($this->path));
      $this->structValidation();

    } catch (ParseException $e ) {

      $message = $e->getMessage();

      if ($e->getParsedLine() != -1) {

        $message  = "Arquivo possui erro em sua estrutura. ";
        $message .= "Confira manual de criação e manutenção de arquivos yaml.\n";
        $message .= "O erro no arquivo esta na linha ". $e->getParsedLine();
        $message .= " perto de ". $e->getSnippet();
      }

      $this->errors[] = $message;
    }

    return empty($this->errors);
  }

  /**
   * Formata as mensagens de erros
   *
   * @return array
   */
  private function formatMessageErrors()
  {
    $messages = array();
    $labels = array(
      'formulario' => 'Formulário',
      'grupos' => 'Grupo(s)',
      'perguntas' => 'Pergunta(s)',
      'respostas' => 'Resposta(s)',
    );

    foreach( $this->errors as $key => $fields) {

      // Erro no parse do arquivo
      if ( !is_array($fields) ) {
        return $this->errors;
      }
      foreach($fields as $field => $times) {
        $messages[] = sprintf("Não foi informado o campo '%s' em %s %s.", $field, count($times), $labels[$key]);
      }
    }
    return $messages;
  }

  /**
   * Valida se os campos obrigatórios estão presente e preenchidos
   *
   * @param string $key        chave do grupo que esta sendo validado
   * @param array  $yamlFields campos do yaml no arquivo
   * @return array
   */
  private function structValidationField($key, $yamlFields)
  {
    foreach ($this->requiredFields[$key] as $field ) {
      if ( !isset($yamlFields[$field]) ) {
        $this->errors[$key][$field][] = true;
      }
    }
  }

  /**
   * Valida a estrutura do yaml
   *
   * @return boolean
   */
  private function structValidation()
  {
    $this->structValidationField('formulario', $this->yamlData);
    return $this->structValidationGroups($this->yamlData['grupos']);
  }

  /**
   * @param array $groups
   * @return boolean
   */
  private function structValidationGroups(Array & $groups = null)
  {
    if (empty($groups)) {
      return false;
    }

    foreach ($groups as $group) {
      $this->structValidationField('grupos', $group);
      $this->structValidationQuestions($group['perguntas']);
    }

    return true;
  }

  /**
   * @param array $questions
   * @return boolean
   */
  private function structValidationQuestions(Array & $questions = null)
  {
    if (empty($questions)) {
      return false;
    }

    foreach($questions as $question) {

      $this->structValidationField('perguntas', $question);
      $this->structValidationAnswers($question['respostas']);
    }

    return true;
  }

  /**
   * @param array $answers
   * @return boolean
   */
  private function structValidationAnswers(Array & $answers = null)
  {
    if ( empty($answers) ) {
      return false;
    }

    foreach ($answers as $answer) {
      $this->structValidationField('respostas', $answer);
    }

    return true;
  }

}
