<?php

/**
 * v3
 */
if (!function_exists('modification')) {
  function modification($file) {
    return Modification::getFile($file);
  }
}

require_once (ECIDADE_PATH . 'std/ModificationFile.php');

/**
 * Modificacoes
 */
class Modification {

  /**
   * Arquivos xml com as modificacoes
   * @var array
   */
  private $xmlFiles = array();

  /**
   * Arquivos modificados
   * @var array
   */
  private $files = array();

  /**
   * Limpa arquivos de caches gerados
   * @return bool
   */
  public function clear() {

    $errors = 0;

    foreach(glob(ECIDADE_MODIFICATION_CACHE_PATH . '*') as $cacheFile) {
      if (!is_dir($cacheFile) && !unlink($cacheFile)) {
        $errors++;
      }
    }

    if ($errors > 0) {
      throw new Exception("Nao foi possivel remover ". $errors . " arquivo(s) de cache");
    }

    return true;
  }

  /**
   * Carrega os arquivos xml
   * @throws Exception
   * @return Modification
   */
  public function load($glob = '{*.xml}') {

    if (!is_dir(ECIDADE_MODIFICATION_PATH)) {
      throw new Exception("Diretorio 'modification' nao criado: " . ECIDADE_MODIFICATION_PATH);
    }

    $clearCache = false;
    $lastParseTime = 0;
    $xmlFiles = glob(ECIDADE_MODIFICATION_XML_PATH . $glob, GLOB_BRACE);
    $lastCacheTime = filemtime(ECIDADE_MODIFICATION_CACHE_PATH);
    $lastXmlTime = filemtime(ECIDADE_MODIFICATION_XML_PATH);

    if (file_exists(ECIDADE_MODIFICATION_CACHE_PATH . '.last-parse')) {
      $lastParseTime = filemtime(ECIDADE_MODIFICATION_CACHE_PATH . '.last-parse');
    }

    // Pasta dos xml modificada, remove os cache
    if ($lastXmlTime > $lastCacheTime) {
      $clearCache = true;
    }

    foreach ($xmlFiles as $xmlFile) {

      // verifica se deve limpar diretorio de cache
      if (filemtime($xmlFile) > $lastParseTime) {
        $clearCache = true;
      }
      $this->xmlFiles[] = $xmlFile;
    }

    if ($clearCache) {
      $this->clear();
    }

    return $this;
  }

  /**
   * Abre os arquivos xml e modifica os arquivos nele declarados
   * @throws Exception
   * @return Modification
   */
  public function parse() {

    foreach($this->xmlFiles as $xmlFile) {

      $dom = new DOMDocument('1.0');
      $dom->preserveWhiteSpace = false;

      if (!(@$dom->load($xmlFile))) {

        static::log("Documento XML inválido: $xmlFile.");
        continue;
      }

      $nodeModification = $dom->getElementsByTagName('modification')->item(0);
      $this->loadFiles($nodeModification->getElementsByTagName('file'), $xmlFile);
    }

    return $this;
  }

  /**
   * @return Modification
   */
  public function save() {

    foreach($this->files as $file) {

      $parse = true;

      // existe arquivo de cache verifica se deve fazer parse
      if ($file->hasCache()) {

        $parse = false;

        foreach ($file->getModifications() as $xmlPath) {

          $xmlFileTime = filemtime($xmlPath);
          $timeFileCache = filemtime(ECIDADE_MODIFICATION_CACHE_PATH . $file->getKey());
          $timeFile = filemtime($file->getPath());

          if ($timeFile > $timeFileCache || $xmlFileTime > $timeFileCache) {
            $parse = true;
          }
        }
      }

      if (!$parse) {
        continue;
      }

      try {

        $file->load();
        $file->parse();
        $file->save();
        $file->unload();

      } catch (Exception $error) {
        static::log('Modification:save() - ' . $file->getPath() . ': '. $error->getMessage());
      }
    }

    return $this;
  }

  /**
   * Faz parse de um arquivo, tag <file>
   * @param DOMNodeList $nodeFile
   * @return boolean
   */
  private function loadFiles(DOMNodeList $nodeFile, $xmlFile) {

    foreach ($nodeFile as $node) {

      $path = $node->getAttribute('path');

      if (empty($path)) {
        throw new Exception('Tag <file>: Path do arquivo nao informado.');
      }

      $files = glob(ECIDADE_PATH . $path, GLOB_BRACE);

      foreach ($files as $file) {

        $modificationFile = ModificationFile::getInstance($file);

        if (!in_array($xmlFile, $modificationFile->getModifications())) {
          $modificationFile->addModification($xmlFile);
        }

        $operations = $node->getElementsByTagName('operation');

        if ($operations->length == 0) {
          throw new Exception("Nenhuma operacao para o arquivo, tag <operation>.");
        }

        foreach ($operations as $operation) {
          $modificationFile->addOperation($this->parseOperation($operation));
        }

        $this->files[$modificationFile->getKey()] = $modificationFile;
      }
    }

    return true;
  }

  /**
   * Parse na tag <operation>
   * @param DOMElement $operation
   * @return StdClass
   */
  private function parseOperation(DOMElement $operation) {

    $search = $operation->getElementsByTagName('search')->item(0);
    $add = $operation->getElementsByTagName('add')->item(0);

    $search = $this->parseOperationSearch($search);
    $add = $this->parseOperationAdd($add);

    return (object) array('search' => $search, 'add' => $add);
  }

  /**
   * Parse da tag <search>
   * @param DOMElement $nodeSearch
   * @return StdClass
   */
  private function parseOperationSearch($nodeSearch) {

    $search = new StdClass();
    $search->regex = false;
    $search->offset = 0;
    $search->limit = 0;
    $search->content = null;
    $search->flag = '';

    if (empty($nodeSearch)) {
      return $search;
    }

    $search->regex = $nodeSearch->getAttribute('regex') == 'true';
    $search->offset = $nodeSearch->getAttribute('offset');
    $search->limit = $nodeSearch->getAttribute('limit');
    $search->flag = $nodeSearch->getAttribute('flag');
    $search->content = $this->convertEncoding($nodeSearch->textContent);

    return $search;
  }

  /**
   * Parse da tag <add>
   * @param DOMElement $nodeAdd
   * @return StdClass
   */
  private function parseOperationAdd(DOMElement $nodeAdd) {

    $add = new StdClass();
    $add->position = $nodeAdd->getAttribute('position');
    $add->content = $this->convertEncoding($nodeAdd->textContent);

    return $add;
  }

  /**
   * Converte para latin1
   * @param string $text
   * @return string
   */
  private function convertEncoding($text) {
    return mb_convert_encoding(
      $text, "ISO-8859-1", mb_detect_encoding($text, "UTF-8, ISO-8859-1, ISO-8859-15", true)
    );
  }

  /**
   * Retorna o arquivo modificado, caso exista
   * @param string $file
   * @return string
   */
  public static function getFile($file) {

    // versao 3
    if (defined('ECIDADE_EXTENSION_PATH')) {
      return modification($file);
    }

    $path = ECIDADE_MODIFICATION_CACHE_PATH . ModificationFile::createKey($file);

    if (file_exists($path) && !is_dir($path)) {

      if (dirname($file) != '.') {
        set_include_path("./" . dirname($file) . PATH_SEPARATOR . get_include_path());
      }

      return $path;
    }

    return $file;
  }

  /**
   * @throws Exception
   * @return bool
   */
  public static function buildStructure() {

    $directories = array(
      ECIDADE_MODIFICATION_PATH,
      ECIDADE_MODIFICATION_LOG_PATH,
      ECIDADE_MODIFICATION_XML_PATH,
      ECIDADE_MODIFICATION_CACHE_PATH,
    );

    foreach ($directories as $path) {
      if (!is_dir($path) && !mkdir($path)) {
        throw new Exception("Nao foi possivel criar diretorio: " . $path);
      }
    }

    return true;
  }

  /**
   * @return void
   */
  public static function find() {

    // versao 3
    if (defined('ECIDADE_EXTENSION_PATH')) {
      return false;
    }

    try {

      static::buildStructure();

      $oModificacao = new Modification();
      $oModificacao->load()->parse()->save();

      // Cria/modifica arquivo com time da ultima instalacao
      touch(ECIDADE_MODIFICATION_CACHE_PATH . '.last-parse');

    } catch(Exception $oErro) {
      static::log($oErro->getMessage());
    }
  }

  /**
   * @param string $message
   * @return bool
   */
  public static function log($message) {
    return file_put_contents(ECIDADE_MODIFICATION_LOG_PATH . 'error.log', $message . PHP_EOL, FILE_APPEND);
  }

}
