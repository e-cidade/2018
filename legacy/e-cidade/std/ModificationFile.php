<?php

class ModificationFile {

  /**
   * @var string
   */
  private $path;

  /**
   * @var string
   */
  private $content;

  /**
   * @var string
   */
  private $key;

  /**
   * @var Array
   */
  private $modifications = array();

  /**
   * @var StdClass[]
   */
  private $operations = array();

  /**
   * @var ModificationFile[]
   */
  private static $instances = array();

  /**
   * @param string $path
   */
  public function __construct($path) {

    if (!is_readable($path)) {
      throw new Exception("Sem permissão de leitura no arquivo: $path");
    }
    $this->key = ModificationFile::createKey($path);
    $this->path = $path;
  }

  /**
   * @return string
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * @return string
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * @return string
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * @param StdClass $operation
   * @return ModificationFile
   */
  public function addOperation($operation) {

    $this->operations[] = $operation;
    return $this;
  }

  /**
   * @param string $xmlPath
   * @return ModificationFile
   */
  public function addModification($xmlPath) {

    $this->modifications[] = $xmlPath;
    return $this;
  }

  public function getModifications() {
    return $this->modifications;
  }

  public function load() {

    if (!file_exists($this->path)) {
      throw new Exception('Arquivo não existe: ' . $this->path);
    }
    $this->content = file_get_contents($this->path);
    return $this;
  }

  public function unload() {

    $this->content = null;
    return $this;
  }

  /**
   * @return ModificationFile
   */
  public function parse() {

    foreach($this->operations as $operation) {

      $search = $operation->search->content;
      $limit = $operation->search->limit;
      $offset = $operation->search->offset;
      $flag = $operation->search->flag;

      $add = $operation->add->content;
      $position = $operation->add->position;
      $replace = null;

      switch ($position) {

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

        // final do arquivo
        case 'bottom':

          $this->content = $this->content . $add;
          continue;
        break;

        // inicio do arquivo
        case 'top':

          $this->content = $add . $this->content;
          continue;
        break;
      }

      if ($operation->search->regex) {

        if (!$limit) {
          $limit = -1;
        }

        $this->content = preg_replace("/$search/$flag", $replace, $this->content, $limit);
        continue;
      }

      $pos = -1;
      $currentMatch = 0;
      $match = array();
      $searchLength = mb_strlen($search);
      $replaceLength = mb_strlen($replace);

      // Busca conteudo da tag <search> e guarda posicao
      while (($pos = strpos($this->content, $search, $pos + 1)) !== false) {
        $match[$currentMatch++] = $pos;
      }

      // Offset
      if (!$offset) {
        $offset = 0;
      }

      // Limit
      if (!$limit) {
        $limit = count($match);
      } else {
        $limit = $offset + $limit;
      }

      // Percorre as ocorrencias encontradas, entre offset e limit
      for ($iOffset = $offset; $iOffset < $limit; $iOffset++) {

        if (!isset($match[$iOffset])) {
          continue;
        }

        // Altera arquivo
        $this->content = substr_replace($this->content, $replace, $match[$iOffset], $searchLength);

        // Corrige posicao das proximas ocorrencias
        $posFix = $searchLength - $replaceLength;
        for ($iFix = $iOffset; $iFix < $limit; $iFix++) {
          $match[$iFix] -= $posFix;
        }
      }

    }

    return $this;
  }

  /**
   * @return bool
   */
  public function hasCache() {

    $fileCache = ECIDADE_MODIFICATION_CACHE_PATH . $this->getKey();
    if (file_exists($fileCache) && !is_dir($fileCache)) {
      return true;
    }

    return false;
  }

  /**
   * @return ModificationFile
   */
  public function clearCache() {

    if (!unlink(ECIDADE_MODIFICATION_CACHE_PATH . $this->getKey())) {
      throw new Exception("Não foi possivle remover cache: " . ECIDADE_MODIFICATION_CACHE_PATH . $this->getKey());
    }

    return $this;
  }

  /**
   * @throws Exception
   * @return ModificationFile
   */
  public function save() {

    if (!file_put_contents(ECIDADE_MODIFICATION_CACHE_PATH . $this->getKey(), $this->getContent())) {
      throw new Exception("Erro ao salvar arquivo de cache: " . $this->getKey());
    }

    return $this;
  }

  /**
   * Cria uma chave pelo caminho do arquivo
   * - usado para criar arquivo de cache
   * @param string $file
   * @return string
   */
  public static function createKey($file) {
    return str_replace('/', '-', str_replace(ECIDADE_PATH, '', $file));
  }

  /**
   * Cria instancia pelo path do arquivo
   * @param string $file
   * @return ModificationFile
   */
  public static function getInstance($file) {

    if (empty(self::$instances[$file])) {
      self::$instances[$file] = new ModificationFile($file);
    }

    return self::$instances[$file];
  }

}
