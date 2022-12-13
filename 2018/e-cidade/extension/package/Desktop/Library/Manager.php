<?php
namespace ECidade\Package\Desktop\Library;

use \ECidade\V3\Extension\AbstractManager;
use \ECidade\V3\Modification\Manager as ModificationManager;
use \ECidade\V3\Modification\Data\Modification as ModificationDataModification;
use \ECidade\V3\Extension\Data as ExtensionData;

/**
 * @package desktop
 */
class Manager extends AbstractManager {

  /**
   * Método responsavel por rodar o unpack de todas os modification, somente para atualizar o cache.
   * @return void
   */
  public function unpack() {

    $modificationManager = new ModificationManager();
    $modificationManager->setLogger($this->container->get('logger'));
    $this->unpackModifications($modificationManager, array('desktop.xml'));
  }

  /**
   * @param ExtensionData $extensionData
   * @return boolean
   */
  public function install($extensionData, $user = null) {

    $modificationManager = new ModificationManager();
    $modificationManager->setLogger($this->getLogger());

    $modificationDesktopData = ModificationDataModification::restore('dbportal-v3-desktop');

    if (!$modificationDesktopData->exists()) {
      $this->unpackModifications($modificationManager, array('desktop.xml'));
      $modificationDesktopData = ModificationDataModification::restore('dbportal-v3-desktop');
    }

    // global install
    if (empty($user)) {

      // carrega e altera modification para GLOBAL type
      $modificationDesktopData->setType(ModificationDataModification::TYPE_GLOBAL);
      $modificationDesktopData->save();

      $extensionData->setStatus(ExtensionData::STATUS_ENABLED);
      $extensionData->setType(ExtensionData::TYPE_GLOBAL);

      return $modificationManager->install('dbportal-v3-desktop');
    }

    // user install

    // extensao por usuario, desabilita global
    $extensionData->setStatus(ExtensionData::STATUS_DISABLED);
    $extensionData->setType(ExtensionData::TYPE_USER);

    // altera modification para USER type
    $modificationDesktopData->setType(ModificationDataModification::TYPE_USER);
    $modificationDesktopData->save();

    try {

      $result = $modificationManager->install('dbportal-v3-desktop', $user);
      $modificationDesktopData = ModificationDataModification::restore('dbportal-v3-desktop');
      $extensionData->setStatus(ExtensionData::STATUS_ENABLED, $user);

    } catch(\Exception $error) {

      $extensionData->setStatus(ExtensionData::STATUS_DISABLED, $user);
      $this->container->get('logger')->error($error->getMessage());
    }

    return $result;
  }

  /**
   * @param ExtensionData $extensionData
   * @return boolean
   */
  public function uninstall($extensionData, $user = null) {

    $modificationManager = new ModificationManager();
    $modificationManager->setLogger($this->getLogger());

    // global uninstall
    if (!$extensionData->isUserType() && !empty($user)) {
      throw new \Exception("Usuário informado para extesão do tipo global");
    }

    $result = $modificationManager->uninstall('dbportal-v3-desktop', $user);
    $extensionData->setStatus(ExtensionData::STATUS_DISABLED, $user);

    return $result;
  }

  /**
   * @param \ECidade\V3\Modification\Manager $modificationManager
   * @param array $files
   * @return array
   */
  private function unpackModifications($modificationManager, array $files) {

    $force = true;
    $modifications = array();
    $rootPath = ECIDADE_EXTENSION_PACKAGE_PATH . 'Desktop/modifications/';

    foreach ($files as $path) {

      try {

        $modifications[] = $modificationManager->unpack($rootPath . $path, $force)->getId();

      } catch (\Exception $error) {
        $this->container->get('logger')->error($error->getMessage());
      }
    }

    return $modifications;
  }

}
