<?php
namespace ECidade\V3\Modification\Parse;

use DOMElement, StdClass;
use \ECidade\V3\Extension\Encode;
use \ECidade\V3\Modification\Parse\Operation\Ignore;

class Operation {

  private $search;
  private $add;
  private $ignore;
  private $error;
  private $encoding = 'ISO-8859-1';
  private $replaceCallbackLimitCounter = 0;
  private $replaceCallbackOffsetCounter = 0;

  const ERROR_SKIP = 1;
  const ERROR_ABORT = 3;

  public function __construct(DOMElement $operation, $index = null) {

    $search = $operation->getElementsByTagName('search')->item(0);
    $add = $operation->getElementsByTagName('add')->item(0);
    $ignore = $operation->getElementsByTagName('ignore')->item(0);
    $error = $operation->getAttribute('error');
    $label = $operation->getAttribute('label');

    $this->search = $this->createSearch($search);
    $this->add = $this->createAdd($add);
    $this->ignore = $this->createIgnore($ignore);
    $this->error = $this->createError($error);

    $index = isset($index) ? "#$index" : null;
    $this->label = $index && $label ? $index . ' - ' . $label : $index;
  }

  public function search($search = null) {

    if ($search === null) {
      return $this->search;
    }

    $this->search = $search;
    return $this;
  }

  public function add($add = null) {

    if ($add === null) {
      return $this->add;
    }

    $this->add = $add;
    return $this;
  }

  public function ignore($ignore = null) {

    if ($ignore === null) {
      return $this->ignore;
    }

    $this->ignore = $ignore;
    return $this;
  }

  public function error($error = null) {

    if ($error ===  null) {
      return $this->error;
    }

    $this->error = $error;
    return $this;
  }

  public function label($label = null) {

    if ($label === null) {
      return $this->label;
    }

    $this->label = $label;
    return $this;
  }

  /**
   * Parse da tag <search>
   * @param DOMElement|null $nodeSearch
   * @return StdClass
   */
  private function createSearch($node) {

    $search = new StdClass();
    $search->regex = false;
    $search->offset = 0;
    $search->limit = 0;
    $search->content = null;

    if (empty($node)) {
      return $search;
    }

    $search->regex = $node->getAttribute('regex') == 'true';
    $search->flag = $node->getAttribute('flag');
    $search->offset = $node->getAttribute('offset');
    $search->limit = $node->getAttribute('limit');
    $search->content = $this->convertEncoding($node->textContent);

    return $search;
  }

  /**
   * Parse da tag <add>
   * @param DOMElement $node
   * @return StdClass
   */
  private function createAdd(DOMElement $node) {

    $add = new StdClass();
    $add->position = $node->getAttribute('position');
    $add->content = $this->convertEncoding($node->textContent);

    return $add;
  }

  /**
   * Parse da tag <ignore>
   * @param DOMElement|null $nodeIgnore
   * @return StdClass
   */
  private function createIgnore($node) {

    if ($node === null) {
      return false;
    }

    $ignore = new Ignore();

    $ignore->regex(mb_strtolower($node->getAttribute('regex')) == 'true');
    $ignore->flag($node->getAttribute('flag'));
    $ignore->type($node->getAttribute('type'));
    $ignore->content($this->convertEncoding($node->textContent));

    return $ignore;
  }

  /**
   * @param string $error
   * @return integer
   */
  public function createError($error) {

    switch (strtolower($error)) {

      // case 'log' removido por redundancia
      default :
      case 'skip' :
        return Operation::ERROR_SKIP;
      break;
      case 'abort' :
        return Operation::ERROR_ABORT;
      break;
    }
  }

  /**
   * @param string $text
   * @return string
   */
  public function convertEncoding($text) {
    return mb_convert_encoding(
      $text,
      $this->encoding,
      mb_detect_encoding($text, "UTF-8, ISO-8859-1, ISO-8859-15", true)
    );
  }

  /**
   * @param string
   * @return string
   */
  public function execute($rawContent) {

    $content = $rawContent;

    // verifica se deve ignorar arquivo
    if ($this->ignore && $this->ignore->type() == 'global') {

      if ($this->ignore->match($content)) {
        return $content;
      }

    }

    $search = $this->search->content;
    $add = $this->add->content;
    $replace = null;

    switch ($this->add()->position) {

      default:
      case 'replace':
        $replace = $add;
      break;

      case 'before':
        $replace = $add . $search;
      break;

      case 'after':
        $replace = $search . $add;
      break;

      case 'bottom':
        return $content . $add;
      break;

      case 'top':
        return $add . $content;
      break;
    }


    // regex
    // quando for regex, nao eh aplicado a regra de bin2hex/hex2bin
    if ($this->search->regex) {

      $this->add->content = $replace;
      $flag = $this->search->flag;
      $this->replaceCallbackLimitCounter = 0;
      $this->replaceCallbackOffsetCounter = 0;
      return preg_replace_callback("/$search/$flag", array($this, 'replaceCallback'), $content);
    }

    // eh feita a conversao de todos texto para bin2hex para evitar erros de controle de posicoes com o php 5.6
    $search = Encode::bin2hex($search);
    $replace = Encode::bin2hex($replace);
    $content = Encode::bin2hex($content);

    $pos = -1;
    $currentMatch = 0;
    $matches = array();

    // Guarda ocorrencias da busca, tag <search>
    while (($pos = strpos($content, $search, $pos + 1)) !== false) {

      // ignore
      if ( $this->ignore && $this->ignore->match( Encode::hex2bin(substr($content, $pos, mb_strlen($search))) ) ) {
        continue;
      }

      $matches[$currentMatch++] = $pos;
    }

    $offset = $this->search->offset ?: 0;
    $limit = $this->search->limit ? $offset + $this->search->limit : count($matches);

    // Percorre as ocorrencias encontradas, entre offset e limit
    for ($iOffset = $offset; $iOffset < $limit; $iOffset++) {

      if (!isset($matches[$iOffset])) {
        continue;
      }

      // Altera arquivo
      $content = substr_replace($content, $replace, $matches[$iOffset], mb_strlen($search));

      // Corrige posicao das proximas ocorrencias
      $posFix = mb_strlen($search) - mb_strlen($replace);
      for ($iFix = $iOffset; $iFix < $limit; $iFix++) {
        $matches[$iFix] -= $posFix;
      }
    }

    return Encode::hex2bin($content);
  }

  /**
   * @param array $matches
   * @return string
   */
  private function replaceCallback($matches) {

    $replace = $this->add->content;
    $search = $this->search->content;
    $flag = $this->search->flag;

    // ignore
    if ($this->ignore && $this->ignore->match($matches[0])) {
      return $matches[0];
    }

    // OFFSET CONTROL
    $offset = $this->search->offset ?: 0;
    $this->replaceCallbackOffsetCounter++;

    if ($offset > 0 && $this->replaceCallbackOffsetCounter <= $offset) {
      return $matches[0];
    }

    // LIMIT CONTROL
    $limit = $this->search->limit ? $this->search->limit : 0;
    $this->replaceCallbackLimitCounter++;

    if ($limit > 0 && $this->replaceCallbackLimitCounter > $limit) {
      return $matches[0];
    }

    //@todo - preg_replace does not support named backreferences.
    return preg_replace("/$search/$flag", $replace, $matches[0]);
  }

}
