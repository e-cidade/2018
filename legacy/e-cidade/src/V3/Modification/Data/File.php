<?php

namespace ECidade\V3\Modification\Data;

use Exception;

use \ECidade\V3\Extension\AbstractMetadata;

/**
 * @package modification
 */
class File extends AbstractMetadata {

  /**
   * Caminho relativo do arquivo original
   * @var string
   */
  private $originalPath;

  /**
   * Diretorio onde sera salvo
   * @var string
   */
  private $persistPath;

  /**
   * baseado no tipo, usuario ou global
   * @var string
   */
  private $prefix;

  /**
   * @param string $path
   * @param string $user
   */
  public function __construct($path, $user = null) {

    $prefix = "global/";

    if (!empty($user)) {
      $prefix = "user/$user/";
    }

    $this->originalPath = str_replace(ECIDADE_PATH, '', $path);
    $this->persistPath = ECIDADE_MODIFICATION_CACHE_PATH;
    $this->prefix = $prefix;

    parent::__construct($this->persistPath . $this->prefix . $this->originalPath, null);
    $this->getStorage()->setSerialize(false);
  }

  /**
   * @param string $persistPath
   */
  public function setPersistPath($persistPath) {
    $this->persistPath = $persistPath;
    $this->getStorage()->setPath($this->persistPath . $this->prefix . $this->originalPath);
  }

  /**
   * @return string
   */
  public function getOriginalPath() {
    return $this->originalPath;
  }

  /**
   * @return string
   */
  public function getPrefix() {
    return $this->prefix;
  }

  /**
   * @param string $content
   */
  public function setContent($content) {
    $this->getStorage()->setData($content);
  }

  /**
   * @return string
   */
  public function getContent() {
    return $this->getStorage()->getData();
  }

  /**
   * @return boolean
   */
  public function save() {
    return $this->getStorage()->save();
  }

  /**
   * Carrega conteudo cacheado
   * @return boolean
   */
  public function load() {
    return $this->getStorage()->load();
  }

  /**
   * Carrega o conteudo original
   * @return boolean
   */
  public function loadContent() {

    $path = ECIDADE_PATH . $this->originalPath;
    if (!file_exists($path)) {
      throw new Exception('Arquivo não existe: ' . $path);
    }

    return $this->getStorage()->setData(file_get_contents($path));
  }

}
