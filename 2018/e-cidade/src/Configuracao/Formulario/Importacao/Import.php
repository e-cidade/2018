<?php

namespace ECidade\Configuracao\Formulario\Importacao;

use ECidade\Configuracao\Formulario\Arquivo\ParserInterface;

/**
 * Realiza as validações no arquivo yaml
 * @author Andrio Costa <andrio.ac@gmail.com>
 * @author Jeferson Belmiro <jeferson.belmiro@gmail.com>
 */
class Import
{
  /**
   * @var ECidade\Configuracao\Formulario\Arquivo\ParserInterface
   */
  private $parser;

  /**
   * @param ParserInterface $parser
   */
  public function __construct(ParserInterface $parser)
  {
    $this->parser = $parser;
  }

  /**
   * @return boolean
   */
  public function import()
  {
    $evaluationFile = $this->parser->parse();

    if ($evaluationFile->getCodigo() === null) {
      return $this->create($evaluationFile);
    }

    // avaliacao atual, salva do banco
    $evaluationCurrent = new \Avaliacao($evaluationFile->getCodigo());

    // Atualiza os dados de acordo com o aquivo
    return $this->update($evaluationFile, $evaluationCurrent);
  }

  /**
   * Cria a avaliacao
   * @param  \Avaliacao $evaluationFile
   * @return boolean
   */
  private function create(\Avaliacao $evaluationFile)
  {
    return $evaluationFile->salvar();
  }

  /**
   * atualiza uma avaliacao
   * @param  \Avaliacao $evaluationFile
   * @return boolean
   */
  private function update(\Avaliacao $evaluationFile, \Avaliacao $evaluationCurrent)
  {
    // remove os grupos da avaliacao
    $groupsFile = $evaluationFile->getGrupos();
    $groupsCurrent = $evaluationCurrent->getGrupos();
    $this->removeGroups($groupsFile, $groupsCurrent);

    // remove as perguntas da avaliacao
    $questionsFile = $this->extractQuestions($groupsFile);
    $questionsCurrent = $this->extractQuestions($groupsCurrent);
    $this->removeQuestions($questionsFile, $questionsCurrent);

    // remove as opcoes de resposta da avaliacao
    $optionsFile = $this->extractOptionsAnwser($questionsFile);
    $optionsCurrent = $this->extractOptionsAnwser($questionsCurrent);
    $this->removeOptionsAnwser($optionsFile, $optionsCurrent);

    // Atualiza os dados de acordo com o aquivo
    $evaluationFile->salvar();

    return true;
  }

  /**
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function removeGroups(Array $file, Array & $current)
  {
    try {
      $this->validateData($file, $current);
    } catch (\Exception $error) {
      throw new \Exception(
        "Os seguintes grupos não pertencem a avalição informada: " . $error->getMessage()
      );
    }

    // Remove da base de dados que foram removidas do arquivo e suas dependências
    return $this->removeData($file, $current);
  }

  /**
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function removeQuestions(Array $file, Array & $current)
  {
    try {
      $this->validateData($file, $current);
    } catch (\Exception $error) {
      throw new \Exception(
        "As seguintes perguntas não pertencem a avalição informada: " . $error->getMessage()
      );
    }

    // Remove da base de dados que foram removidas do arquivo e suas dependências
    return $this->removeData($file, $current);
  }

  /**
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function removeOptionsAnwser(Array $file, Array & $current)
  {
    try {
      $this->validateData($file, $current);
    } catch (\Exception $error) {
      throw new \Exception(
        "As seguintes opções de resposta não pertencem a avalição informada: " . $error->getMessage()
      );
    }

    // Remove da base de dados que foram removidas do arquivo e suas dependências
    return $this->removeData($file, $current);
  }

  /**
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function validateData(Array $file, Array & $current)
  {
    // valida ids
    // nao permitindo editar dados de outras avaliacoes
    $invalid = $this->invalidData($file, $current);
    if (!empty($invalid)) {
      throw new \Exception(implode(", ", $invalid));
    }
  }

  /**
   * Retorna ids que nao estao no banco, invalidos
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function invalidData(Array $file, Array $current)
  {
    return array_diff(
      $this->extractId( $file ),
      $this->extractId( $current )
    );
  }

  /**
   * @param  Array $file
   * @param  Array $current
   * @return boolean
   */
  private function removeData(Array $file, Array & $current)
  {
    $toRemove = array_diff(
      $this->extractId( $current ),
      $this->extractId( $file )
    );

    if( empty($toRemove) ) {
      return false;
    }

    // remove os grupos removido no arquivo
    foreach ($current as $key => $data) {

      if (in_array($data->getCodigo(), $toRemove)) {
        $data->excluir();
        unset($current[$key]);
      }
    }

    return true;
  }

  /**
   * @param  Array $groups
   * @return Array
   */
  private function extractQuestions(Array $groups)
  {
    $questions = array();

    foreach ($groups as $group) {
      $questions = array_merge($questions, $group->getPerguntas());
    }

    return $questions;
  }

  /**
   * @param  Array  $questions
   * @return Array
   */
  private function extractOptionsAnwser(Array $questions)
  {
    $options = array();

    foreach ($questions as $question) {
      $options = array_merge($options, $question->getOpcoes());
    }

    return $options;
  }

  /**
   * Extrai todos os id de um array data
   * @param  Array $array
   * @return Array
   */
  private function extractId(Array $array)
  {
    $result = array();
    foreach ($array as $object) {
      if ( !!$object->getCodigo() ) {
        $result[] = $object->getCodigo();
      }
    }
    return $result;
  }

}
