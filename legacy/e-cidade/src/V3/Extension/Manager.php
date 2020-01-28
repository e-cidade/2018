<?php
namespace ECidade\V3\Extension;

use Exception, PharData;
use \ECidade\V3\Config\Data as ConfigData;
use \ECidade\V3\Extension\AbstractManager;
use \ECidade\V3\Extension\Data as ExtensionData;
use \ECidade\V3\Extension\Parse\Manifest as ManifestParse;
use \ECidade\V3\Modification\Manager as ModificationManager;

/**
 * @package extension
 */
class Manager extends AbstractManager {

  /**
   * @param string $id
   * @param string $user
   * @return boolean
   */
  public static function isEnabled($id, $user = null) {
    return ExtensionData::restore($id)->isEnabled($user);
  }

  /**
   * @param string $file
   * @param boolean $foce
   * @return ExtensionData
   */
  public function unpack($file, $force = false) {

    if (!file_exists($file)) {
      throw new Exception("Arquivo não encontrado: $file");
    }

    if (pathinfo($file, PATHINFO_EXTENSION) != "gz") {
      throw new Exception("Arquivo com extensão inválida, esperado tar.gz");
    }

    $pharData = new PharData($file);
    $id = $pharData->getFileName();
    $extensionData = ExtensionData::restore($id);

    if ($extensionData->exists() && !$force) {
      throw new Exception("Extensão já descompactada.");
    }

    if (!isset($pharData[$id . '/Manifest.xml'])) {
      throw new Exception("Arquivo manifest não encontrado no package.");
    }

    if (!$pharData->extractTo(ECIDADE_EXTENSION_PACKAGE_PATH, null, true)) {
      throw new Exception("Erro ao descompactar arquivo: $file");
    }

    $parse = $this->parse($id);

    if (!$extensionData->exists()) {
      $extensionData->setStatus(ExtensionData::STATUS_DISABLED);
      $extensionData->setId($parse->getId());
      $extensionData->setVersion($parse->getVersion());
      $extensionData->setType($parse->getType());
      $extensionData->setModifications($parse->getModifications());
      $extensionData->setEvents($parse->getEvents());
      $extensionData->setManager($parse->getManager());
      $extensionData->save();
    }

    if ($extensionData->hasManager()) {

      $extensionManager = $extensionData->getManager();
      $extensionManager = new $extensionManager($this->container);
      $extensionManager->unpack();
    }

    return $extensionData;
  }

  /**
   * @param string $id
   * @return \ECidade\Extension\Parse\Manifest
   */
  public function parse($id) {

    $path = ECIDADE_EXTENSION_PACKAGE_PATH . $id . '/Manifest.xml';

    // parse no xml
    $parse = new ManifestParse($path);
    return $parse->load()->parse();
  }

  /**
   * @param integer $id
   * @param string $user
   * @return boolean
   */
  public function install($id, $user = null) {

    $this->container->get('logger')->debug('Instalando '. $id . (!empty($user) ? ' user '. $user : null));
    $this->container->get('logger')->debug('install('. $id .')');

    $extensionData = ExtensionData::restore($id);
    $configData = ConfigData::restore('config');

    if (!$extensionData->exists()) {
      throw new Exception("Extensão não descompactada: '$id'");
    }

    if ($extensionData->isUserType() && empty($user)) {
      throw new Exception("Usuário da extensão '$id' não informado");
    }

    if ($extensionData->isEnabled($user)) {
      throw new Exception("Extensão já instalada: '$id'" . ($extensionData->isUserType() ? " usuário '$user'" : null));
    }

    if ($extensionData->hasModifications()) {

      $modificationManager = new ModificationManager($this->container);
      $modificationManager->install($extensionData->getModifications(), $user);
    }

    if ($extensionData->hasEvents()) {
      foreach ($extensionData->getEvents() as $trigger => $events) {
        foreach ($events as $event) {
          $configData->addEvent($event, $trigger);
        }
      }
    }

    $extensionData->setStatus(ExtensionData::STATUS_ENABLED, $user);

    if ($extensionData->hasManager()) {

      $extensionManager = $extensionData->getManager();
      $extensionManager = new $extensionManager($this->container);
      $extensionManager->install($extensionData, $user);
    }

    $extensionData->save();
    $configData->save();

    return $extensionData->getStatus($user) === ExtensionData::STATUS_ENABLED;
  }

  /**
   * @param string $id
   * @param string $user
   * @return boolean
   */
  public function uninstall($id, $user = null) {

    $this->container->get('logger')->debug('Desinstalando '. $id . (!empty($user) ? ' user '. $user : null));

    $configData = ConfigData::restore('config');
    $extensionData = Data::restore($id);

    if (false === $extensionData->exists()) {
      throw new Exception("Extensão não instalada: $id");
    }

    if ($extensionData->isUserType() && empty($user)) {
      throw new Exception("Usuário da extensão '$id' não informado");
    }

    if (false === $extensionData->isEnabled($user)) {
      throw new Exception("Extensão desativada: $id");
    }

    if ($extensionData->hasModifications()) {

      $modificationManager = new ModificationManager($this->container);
      $modificationManager->uninstall($extensionData->getModifications(), $user);
    }

    if ($extensionData->hasEvents()) {
      foreach ($extensionData->getEvents() as $trigger => $events) {
        foreach ($events as $event) {
          $configData->removeEvent($event);
        }
      }
    }

    $extensionData->setStatus(ExtensionData::STATUS_DISABLED, $user);

    if ($extensionData->hasManager()) {

      $extensionManager = $extensionData->getManager();
      $extensionManager = new $extensionManager($this->container);
      $extensionManager->uninstall($extensionData, $user);
    }

    $extensionData->save();
    $configData->save();

    return true;
  }

}
