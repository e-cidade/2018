<?php
namespace ECidade\V3\Modification;

use ArrayObject, Exception;
use \ECidade\V3\Extension\Container;
use \ECidade\V3\Extension\AbstractManager;
use \ECidade\V3\Modification\Logger;
use \ECidade\V3\Modification\ManagerParseFiles;
use \ECidade\V3\Modification\Parse\Modification as ModificationParse;
use \ECidade\V3\Modification\Data\Modification as ModificationData;
use \ECidade\V3\Modification\Data\File as FileData;
use \ECidade\V3\Modification\Data\FileSync;
use \ECidade\V3\Modification\Data\FileTypeModification;

/**
 * @package Modification
 */
class Manager extends AbstractManager {

  /**
   * @param Container $container
   */
  public function __construct(Container $container = null) {

    // cria o container, caso nao for informado, e registra logger
    parent::__construct($container);

    if (!$this->container->has('group')) {
      $this->container->register('group', function($container) {
        return Data\Group::restore();
      });
    }

    // cache de \ECidade\V3\Modification\Data\Modification
    if (!$this->container->has('cacheDataModifications')) {

      $this->container->register('cacheDataModifications', function($container) {

        $cacheDataModifications = new ArrayObject();
        return function($id) use ($cacheDataModifications) {

          if (!isset($cacheDataModifications[$id])) {
            $cacheDataModifications[$id] = ModificationData::restore($id);
          }

          return $cacheDataModifications[$id];
        };
      });
    }

    // cache de \ECidade\V3\Modification\Logger
    if (!$this->container->has('cacheLoggerModifications')) {

      $this->container->register('cacheLoggerModifications', function($container) {

        $cacheLoggerModifications = new ArrayObject();
        return function($id) use ($cacheLoggerModifications) {

          if (!isset($cacheLoggerModifications[$id])) {
            $cacheLoggerModifications[$id] = new Logger($id);
          }

          return $cacheLoggerModifications[$id];
        };
      });
    }

  }

  /**
   * @param string $path
   * @param bool $force
   * @return \ECidade\V3\Modification\Data\Modification
   */
  public function unpack($path, $force = false) {

    // cria diretorios
    $this->setup();
    $parseModification = $this->parse($path);

    // cache das modificacoes
    $dataModification = ModificationData::restore($parseModification->getId());

    if ($force === false && $dataModification->exists()) {
      throw new Exception("Modificação já descompactada: ". $parseModification->getId());
    }

    $dataModification->setId($parseModification->getId());
    $dataModification->setLabel($parseModification->getLabel());
    $dataModification->setGroup($parseModification->getGroup());
    $dataModification->setOperations($parseModification->getOperations());
    $dataModification->setFilesOperations($parseModification->getFilesOperations());

    // nao altera status e type de modificacoes ja instaladas
    // @todo - buscar um metodo melhor
    if ($force === false || !$dataModification->exists()) {

      $dataModification->setStatus(ModificationData::STATUS_DISABLED);
      $dataModification->setType($parseModification->getType());
    }

    $dataModification->save();

    // @todo - verificar utilidade dessa copia, guardar .data com modificacoes instaladas
    copy($path, ECIDADE_MODIFICATION_XML_PATH . basename($path));

    return $dataModification;
  }

  /**
   * @param string|array $installModifcations
   * @param string $user
   * @return boolean
   */
  public function install($installModifcations, $user = null, $ignoreGlobal = false) {

    if (empty($installModifcations)) {
      throw new Exception("Nenhum ID informado.");
    }

    if (!is_array($installModifcations)) {
      $installModifcations = array($installModifcations);
    }

    $logger = $this->container->get('logger');
    $logger->debug(
      "Instalando modificações: ". implode(', ', $installModifcations) . (!empty($user) ? ' user '.$user : null)
    );

    // cache-data das modificacoes
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    // cache-data dos loggers de modficacoes
    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');

    // dados para reparse
    $filesReparse = new ArrayObject();

    // [file][type][modification]
    $dataFileTypeModification = new FileTypeModification();
    $fileTypeModification = array();

    if ($ignoreGlobal === false && $dataFileTypeModification->exists()) {
      $dataFileTypeModification->load();
      $fileTypeModification = $dataFileTypeModification->getData();
    }

    foreach ($installModifcations as $id) {

      $dataModification = $cacheDataModifications($id);
      $modificationUserType = $dataModification->getType() === ModificationData::TYPE_USER;
      $type = $modificationUserType ? 'user:' . $user : 'global';

      if (!$dataModification->exists()) {
        throw new Exception("Modificação sem cache: $id");
      }

      if ($modificationUserType && empty($user)) {
        throw new Exception("Usuário não definido para modificação: $id");
      }

      if ($dataModification->isEnabled($user)) {
        throw new Exception("Modificação já instalada: $id");
      }

      $logModification = $cacheLoggerModifications($id);
      $logModification->info('Instalando modificação');

      foreach ($dataModification->getFiles() as $path) {

        if (!isset($fileTypeModification[$path][$type])) {
          $fileTypeModification[$path][$type] = array();
        }

        if (!in_array($id, $fileTypeModification[$path][$type])) {
          $fileTypeModification[$path][$type][] = $id;
        }

        $filesReparse[$path] = $fileTypeModification[$path];
      }
    }

    $group = $this->container->get('group');
    $groupUpdated = false;

    try {

      foreach ($installModifcations as $id) {

        $data = $cacheDataModifications($id);

        if ($data->hasGroup()) {
          $groupUpdated = true;
          $group->add($data->getGroup(), $id);
        }

        $data->setStatus(ModificationData::STATUS_ENABLED, $user);
        $data->save();
      }

      if ($groupUpdated) {
        $group->save();
      }

      $this->parseFiles($filesReparse, $user);

    } catch (Exception $error) {

      foreach ($installModifcations as $id) {

        $data = $cacheDataModifications($id);

        if ($data->hasGroup()) {
          $group->removeItem($data->getGroup(), $id);
          $groupUpdated = true;
        }
        $data->setStatus(ModificationData::STATUS_DISABLED, $user);
        $data->save();
      }

      if ($groupUpdated) {
        $group->save();
      }

      throw $error;
    }

    // atualiza e salva cache dos arquivos
    if ($ignoreGlobal === false) {

      $dataFileTypeModification->setData($fileTypeModification);
      $dataFileTypeModification->save();
    }

    return true;
  }

  /**
   * @param string|array $uninstallModifications
   * @param string $user
   * @return boolean
   */
  public function uninstall($uninstallModifications, $user = null) {

    if (!is_array($uninstallModifications)) {
      $uninstallModifications = array($uninstallModifications);
    }

    if (empty($uninstallModifications)) {
      throw new Exception("Nenhum ID informado.");
    }

    // cache-data
    $cacheDataModifications = $this->container->get('cacheDataModifications');
    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');
    $logger = $this->container->get('logger');
    $group = $this->container->get('group');

    // dados para reparse
    $filesReparse = new ArrayObject();

    // [file][type][modification]
    $dataFileTypeModification = new FileTypeModification();
    $fileTypeModification = array();

    if ($dataFileTypeModification->exists()) {
      $dataFileTypeModification->load();
      $fileTypeModification = $dataFileTypeModification->getData();
    }

    $logger->debug(
      "Desinstalando modificações: ". implode(', ', $uninstallModifications) . (!empty($user) ? ' user '.$user : null)
    );

    foreach ($uninstallModifications as $id) {

      $dataModification = $cacheDataModifications($id);
      $logModification = $cacheLoggerModifications($id);

      $modificationUserType = $dataModification->getType() === ModificationData::TYPE_USER;
      $type = $modificationUserType ? 'user:' . $user : 'global';

      if (!$dataModification->exists()) {
        throw new Exception("Modificação sem cache: $id");
      }

      if ($modificationUserType && empty($user)) {
        throw new Exception("Usuário não definido para modificação: $id");
      }

      if (!$dataModification->isEnabled($user)) {
        throw new Exception("Modificação não instalada: $id");
      }

      $logModification->info('Desinstalando modificação');

      foreach ($dataModification->getFiles() as $path) {

        if (!isset($fileTypeModification[$path])) {
          continue;
        }

        // remove id da modificacao do cache global de arquivos
        if (!empty($fileTypeModification[$path][$type])) {

          $key = array_search($id, $fileTypeModification[$path][$type]);

          if ($key !== false) {
            unset($fileTypeModification[$path][$type][$key]);
          }

          // empty
          if (empty($fileTypeModification[$path][$type])) {
            unset($fileTypeModification[$path][$type]);
          }
        }

        // empty
        if (empty($fileTypeModification[$path])) {
          unset($fileTypeModification[$path]);
          continue;
        }

        if (!$modificationUserType && !empty($fileTypeModification[$path])) {
          $filesReparse[$path] = $fileTypeModification[$path];
        }
      }
    }

    $removeKeys = array();
    $iterator = $filesReparse->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
      if (!isset($fileTypeModification[$iterator->key()])) {
        $removeKeys[] = $iterator->key();
      }
    }
    foreach ($removeKeys as $key) {
      $filesReparse->offsetUnset($key);
    }
    $removeKeys = null;

    $this->removeDataModificationFiles($filesReparse, $uninstallModifications, $user);

    if (count($filesReparse) > 0) {
      $this->parseFiles($filesReparse, $user);
    }

    $filesReparse = null;

    // atualiza e salva cache dos arquivos
    $dataFileTypeModification->setData($fileTypeModification);
    $dataFileTypeModification->save();

    foreach ($uninstallModifications as $id) {

      // set status como disable
      $data = $cacheDataModifications($id);

      // remove modificaton do grupo
      if ($data->hasGroup()) {
        $group->removeItem($data->getGroup(), $id);
      }

      $data->setStatus(ModificationData::STATUS_DISABLED, $user);
      $data->save();
    }

    $group->save();

    return true;
  }

  /**
   * @param string path
   * @param string $user
   * @return boolean
   */
  public function updateFile($path, $user = null) {

    if (!file_exists($path)) {
      throw new Exception('Arquivo não existe: ' . $path);
    }

    // clear absolute path
    $path = str_replace(ECIDADE_PATH, null, realpath($path));

    $dataFileTypeModification = new FileTypeModification();
    $fileTypeModification = array();

    if ($dataFileTypeModification->exists()) {
      $dataFileTypeModification->load();
      $fileTypeModification = $dataFileTypeModification->getData();
    }

    if (!isset($fileTypeModification[$path])) {
      throw new Exception('Arquivo sem modificacao: ' . $path);
    }

    $filesReparse = new ArrayObject(array($path => $fileTypeModification[$path]));
    return $this->parseFiles($filesReparse, $user);
  }

  /**
   * @param string path
   * @param string $user
   * @return boolean
   */
  public function updateFileTest($path, $user = null) {

    $logger = $this->container->get('logger');

    if (!file_exists($path)) {
      $logger->error('Arquivo nao existe: ' . $path);
      return false;
    }

    $dataFileTypeModification = new FileTypeModification();
    $fileTypeModification = array();

    // clear absolute path
    $path = str_replace(ECIDADE_PATH, null, realpath($path));

    if ($dataFileTypeModification->exists()) {
      $dataFileTypeModification->load();
      $fileTypeModification = $dataFileTypeModification->getData();
    }

    if (!isset($fileTypeModification[$path])) {
      $logger->debug('Arquivo sem modificacao: ' . $path);
      return true;
    }

    $filesReparse = new ArrayObject(array($path => $fileTypeModification[$path]));
    $managerParseFiles = new ManagerParseFiles($this->container, $user);

    // agrupa operacoes para executar parse na ordem correta
    $managerParseFiles->generateOperationsQueue($filesReparse);

    // desabilita log das modificacoes
    $managerParseFiles->setModificationLogVerbosity(Logger::QUIET);

    // executa o parse
    $managerParseFiles->parse();

    // remove arquivos temporarios
    $managerParseFiles->removePersistDirectory();

    return !$managerParseFiles->hasErrorsOnParse();
  }

  /**
   * @param ArrayObject $modificationsFiles
   * @param string $user
   * @return boolean
   */
  private function parseFiles(ArrayObject $modificationsFiles, $user = null) {

    $logger = $this->container->get('logger');
    $logger->debug('Total de arquivos para processar: ' . count($modificationsFiles));

    $managerParseFiles = new ManagerParseFiles($this->container, $user);

    // gera fila de operacoes para processar de acordo com modificacoes e usuarios passados
    $managerParseFiles->generateOperationsQueue($modificationsFiles);

    // realiza parse das modificacoes, gerando cache temporario
    $managerParseFiles->parse();

    // aborta modificacoes marcadas para remover
    $managerParseFiles->abortModifications($this);

    // salva os caches temporarios gerados pelo parse no diretorio final de caches
    $managerParseFiles->persist();

    // remove caches nao utilizados
    $managerParseFiles->removeUselessDataFile();

    // remove diretorio temporario
    $managerParseFiles->removePersistDirectory();

    return true;
  }

  /**
   * @param ArrayObject $filesReparse
   * @param array $modifications
   * @param string $user
   * @return boolean
   */
  private function removeDataModificationFiles(ArrayObject $filesReparse, Array $modifications, $user) {

    $logger = $this->container->get('logger');
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    foreach ($modifications as $id) {

      $dataModification = $cacheDataModifications($id);
      $dataRemoved = 0;

      $logger->debug('Removendo caches da modificação: ' . $id .  '('. count($dataModification->getFiles()) . ')');

      foreach ($dataModification->getFiles() as $path) {

        // arquivo marcado para reparse e contem modification global
        if (isset($filesReparse[$path]) && in_array('global', array_keys($filesReparse[$path]))) {
          continue;
        }

        $_user = $dataModification->isUserType() ? $user : null;
        $dataFile = new FileData($path, $_user);
        $fileSync = new FileSync($dataFile);

        // dessincroniza arquivo
        // usado para verificar a necessidade de atualizar cache
        $fileSync->remove();

        if (!$dataFile->exists()) {
          continue;
        }

        $dataRemoved++;
        $dataFile->remove();
      }

      $logger->debug('Arquivos removidos: ' . $dataRemoved);
    }

    return true;
  }

  /**
   * @param string $path
   */
  public function parse($path) {

    if (!file_exists($path)) {
      throw new Exception("Arquivo não existe: $path");
    }

    // parse no xml
    $parseModification = new ModificationParse($path);
    $parseModification->load();
    $parseModification->parse();

    return $parseModification;
  }

  /**
   * @throws Exception
   * @return bool
   */
  public function setup() {

    $mode = 0775;

    if (!is_dir(ECIDADE_MODIFICATION_PATH) && !mkdir(ECIDADE_MODIFICATION_PATH, $mode, true)) {
      throw new Exception("Nao foi possivel criar diretorio: " . ECIDADE_MODIFICATION_PATH);
    }

    if (!is_dir(ECIDADE_MODIFICATION_LOG_PATH) && !mkdir(ECIDADE_MODIFICATION_LOG_PATH, $mode, true)) {
      throw new Exception("Nao foi possivel criar diretorio: " . ECIDADE_MODIFICATION_LOG_PATH);
    }

    if (!is_dir(ECIDADE_MODIFICATION_DATA_PATH) && !mkdir(ECIDADE_MODIFICATION_DATA_PATH, $mode, true)) {
      throw new Exception("Nao foi possivel criar diretorio: " . ECIDADE_MODIFICATION_DATA_PATH);
    }

    if (!is_dir(ECIDADE_MODIFICATION_XML_PATH) && !mkdir(ECIDADE_MODIFICATION_XML_PATH, $mode, true)) {
      throw new Exception("Nao foi possivel criar diretorio: " . ECIDADE_MODIFICATION_XML_PATH);
    }

    return $this;
  }

}
