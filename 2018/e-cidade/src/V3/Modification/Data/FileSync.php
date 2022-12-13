<?php
namespace ECidade\V3\Modification\Data;

use \ECidade\V3\Extension\Storage;
use \ECidade\V3\Extension\Logger;
use \ECidade\V3\Modification\Manager as ModificationManager;
use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Modification\Data\File;

/**
 * @package modification
 */
class FileSync extends Storage {

  /**
   * @var \ECidade\V3\Modification\Data\File
   */
  private $fileData;

  /**
   * @param string $path
   */
  public function __construct(File $fileData) {

    $this->fileData = $fileData;
    $path = $fileData->getPrefix() . $fileData->getOriginalPath();
    parent::__construct(ECIDADE_MODIFICATION_DATA_PATH . "file/sync/" . $path);
    $this->setSerialize(false);
  }

  /**
   * @return boolean
   */
  public function updated() {

    $originalPath = ECIDADE_PATH . $this->fileData->getOriginalPath();

    // arquivo nao sincronizando ou cache nao existe
    if (!$this->exists() || !$this->fileData->exists()) {
      return false;
    }

    return filemtime($this->path) > filemtime($originalPath);
  }

  /**
   * Mantem arquivo de cache atualizado
   *
   * @param string $path
   * @param string $user
   * @return \ECidade\V3\Modification\Data\File | false
   */
  public static function update($path, $user = null) {

    // verifica cache por usuario
    $fileData = new File($path, $user);
    $fileSync = new static($fileData);

    // nao existe cache por usuario, busca global
    // @FIXME - remover validacao "!$fileData->exists()"
    // validacao por compatibilidade
    // caches ja instalados nao tem arquivo de sincronizacao criado
    if (!$fileSync->exists() && !$fileData->exists()) {

      $user = null;
      $fileData = new File($path);
      $fileSync = new static($fileData);
    }

    // arquivo nao tem modificação
    // @FIXME - remover validacao "!$fileData->exists()"
    // validacao por compatibilidade
    // caches ja instalados nao tem arquivo de sincronizacao criado
    if (!$fileSync->exists() && !$fileData->exists()) {
      return false;
    }

    // arquivo de cache atualizado
    if ($fileSync->updated()) {
      return $fileData;
    }

    $manager = new ModificationManager();
    $config = Registry::get('app.config');

    // verifica se tem configurado arquivo para log
    if ($config->has('app.modifications.log.path')) {

      $manager->getLogger()->setFile($config->get('app.modifications.log.path'));
      $manager->getLogger()->setVerbosity($config->get('app.log.verbosity', Logger::QUIET));
      $manager->getLogger()->debug("\n\nmodification() - update file: $path");
    }

    // atualiza arquivo
    $manager->updateFile($path, $user);

    // arquivo com modificacao de usuario nao gerou cache
    // verifica se gerou o global
    if (!$fileData->exists() && !empty($user)) {
      $fileData = new File($path);
    }

    // arquivo nao gerou cache
    // ocorreu algum erro no parse
    if (!$fileData->exists()) {
      return false;
    }

    return $fileData;
  }

}
