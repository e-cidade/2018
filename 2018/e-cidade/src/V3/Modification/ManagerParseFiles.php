<?php

namespace ECidade\V3\Modification;

use ArrayObject, Exception;
use \ECidade\V3\Extension\Container;
use \ECidade\V3\Modification\Manager;
use \ECidade\V3\Modification\Data\File as FileData;
use \ECidade\V3\Modification\Data\FileSync;
use \ECidade\V3\Modification\Data\Modification as ModificationData;
use \ECidade\V3\Modification\Parse\File as FileParse;
use \ECidade\V3\Modification\Parse\Operation;
use \ECidade\V3\Modification\Exception\Abort as AbortException;

/**
 * @package Modification
 */
class ManagerParseFiles {

  /**
   * @var \ECidade\Extension\Container
   */
  private $container;

  /**
   * usuario atual
   * @var string
   */
  private $user;

  /**
   * fila de operacoes por arquivo para processar
   *
   * @see ManagerParseFiles::generateOperationsQueue()
   * @example
   * ArrayObject(
   *   global => array(
   *     'modification1' => array(
   *         0 => array(
   *             'arquivo1' => $metadadoArquivo1.1
   *         )
   *     ),
   *     'modification2' => array(
   *         0 => array(
   *             'arquivo2' => $metadadoArquivo2
   *         )
   *     )
   *   ),
   *   user => array(
   *      'modification3' => array(
   *          0 => array(
   *              'arquivo1' => $metadadoArquivo1.2 (global->$metadadoArquivo1.1)
   *          )
   *      )
   *   )
   * )
   * @var ArrayObject
   */
  private $modificationOperationData;

  /**
   * usuarios que tem arquivos de cache especificos
   * @see ManagerParseFiles::abortModifications()
   * @var ArrayObject
   */
  private $users;

  /**
   * diretorio temporario para gerar caches
   * @param string
   */
  private $persistPath;

  /**
   * arquivos de caches marcados para remover
   * @var ArrayObject
   */
  private $dataToRemove;

  /**
   * arquivos de caches marcados para salvar
   * @var ArrayObject
   */
  private $dataToPersist;

  /**
   * modifciacoes marcadas para abortar
   * @var ArrayObject
   */
  private $abortModifications;

  /**
   * modifciacoes marcadas para salvar
   * @var ArrayObject
   */
  private $modificationToPersist;

  /**
   * Flag para saber se houveram erros durante o parse de um arquivo
   * @var boolean
   */
  private $hasErrorsOnParse;

  /**
   * @param Container $container
   * @param string $user
   */
  public function __construct(Container $container, $user = null) {

    $this->container = $container;
    $this->user = $user;
  }

  /**
   * @param ArrayObject $modificationsFiles
   * @return boolean
   */
  public function generateOperationsQueue(ArrayObject $modificationsFiles) {

    $logger = $this->container->get('logger');

    $this->modificationOperationData = new ArrayObject();
    $this->users = new ArrayObject();
    $this->dataToRemove = new ArrayObject();
    $this->dataToPersist = new ArrayObject();
    $this->abortModifications = new ArrayObject();
    $this->modificationToPersist = new ArrayObject();

    // diretorio temporario onde sera gerado arquivos de cache
    $this->persistPath = ECIDADE_MODIFICATION_CACHE_PATH . 'parsing-' . uniqid() . '/';

    $logger->debug('Gerando fila de operacoes');
    $logger->debug(' - Definindo diretorio temporario: ' . str_replace(ECIDADE_PATH, null, $this->persistPath));

    /**
     * @param string $a
     * @param string $b
     * @return integer
     */
    $globalSort = function($a, $b) {
      return ($a === $b ? 0 : ($a == 'global' ? -1 : 1));
    };

    // itera o conteudo do arquivo file-type-modification.data
    // (que contem informacoes de arquivos e suas modificacao globais/usuarios)
    $iterator = $modificationsFiles->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $path = $iterator->key();
      $types = $iterator->current();

      // se o arquivo original nao existir, erro
      if (!file_exists(ECIDADE_PATH . $path)) {
        $logger->error("Arquivo não existe: '$path'");
        continue;
      }

      // se o arquivo original nao tem permissao de leitura, erro
      if (!is_readable(ECIDADE_PATH . $path)) {
        $logger->error("Arquivo sem permissão de leitura: '$path'");
        continue;
      }

      // se o arquivo original estiver vazio, erro
      if (filesize(ECIDADE_PATH . $path) == 0) {
        $logger->error("Arquivo vazio: '$path'");
        continue;
      }

      // itera os modification globais e por usuario
      foreach ($types as $type => $modifications) {

        // verifica se a modificacao eh por usuario
        $_user = null;
        if (strpos($type, 'user:') === 0) {
          $_user = substr($type, 5);
          $this->users[$_user] = $_user;
        }

        // se for por usuario, a modificacao devera ser feita somente para o usuario passado por parametro
        // aqui verifica se o usuario passado por parametro eh o mesmo da modificacao atual, se nao for vai pra proxima
        if (!empty($this->user) && !empty($_user) && $this->user != $_user) {
          continue;
        }

        if (!isset($this->modificationOperationData[$type])) {
          $this->modificationOperationData[$type] = new ArrayObject();
        }

        foreach ($modifications as $modificationId) {

          if (!isset($this->modificationOperationData[$type][$modificationId])) {
            $this->modificationOperationData[$type][$modificationId] = new ArrayObject();
          }

          $this->modificationOperationData[$type][$modificationId][] = $path;
        }
      }
    }

    $modificationsFiles = null;

    // garante ordem correta, primeiro modificacoes globais
    $this->modificationOperationData->uksort($globalSort);

    return true;
  }

  /**
   * @return boolean
   */
  public function parse() {

    // itera todos os modification encontrados que precisam ser executados
    $iterator = $this->modificationOperationData->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $type = $iterator->key();
      $modifications = $iterator->current();

      $user = null;
      if (strpos($type, 'user:') === 0) {
        $user = substr($type, 5);
      }

      // itera os operations do modification seguindo a ordem correto que veio do xml
      foreach ($modifications as $modificationId => $files) {
        $this->parseModification($modificationId, $files, $user);
      }
    }

    return true;
  }

  /**
   * @return boolean
   */
  public function persist() {

    $logger = $this->container->get('logger');

    // salvar alteracoes nos metadados das modificacoes
    $this->persistModification();

    if (count($this->dataToPersist) == 0) {
      return false;
    }

    $logger->debug('Arquivos marcados para salvar: ' . count($this->dataToPersist));
    $saved = 0;

    $iterator = $this->dataToPersist->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $path = $iterator->key();
      $relativePath = str_replace($this->persistPath, null, $path);
      $finalPath = ECIDADE_MODIFICATION_CACHE_PATH . $relativePath;
      $finalPathDir = dirname($finalPath);

      // extrai usuario e caminho original do arquivo
      // usado para sincronizar cache com arquivo original
      $parts = explode("/", $relativePath);
      $type = array_shift($parts);
      $user = $type == 'user' ? array_shift($parts) : null;
      $originalPath = implode('/', $parts);

      $dataFile = new FileData($originalPath, $user);
      $fileSync = new FileSync($dataFile);

      if (!file_exists($path)) {
        $logger->error("Arquivo não encontrado: $path");
        continue;
      }

      if (!is_dir($finalPathDir) && !mkdir($finalPathDir, 0775, true)) {
        $logger->error("Erro ao criar diretorio: $finalPathDir");
        continue;
      }

      if (!rename($path, $finalPath)) {
        $logger->error("Erro ao mover arquivo: '$path' para ''$finalPath'");
        continue;
      }

      $fileSync->touch();

      $saved++;
    }

    $logger->debug(' - Arquivos salvos: ' . $saved);

    $finalPath = null;
    $saved = null;
    $this->dataToPersist = new ArrayObject();
    return true;
  }

  /**
   * @return boolean
   */
  private function persistModification() {

    if (count($this->modificationToPersist) == 0) {
      return false;
    }

    $iterator = $this->modificationToPersist->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $abortModificationID = $iterator->key();
      $dataModification = $iterator->current();
      $dataModification->save();
    }
    $this->modificationToPersist = new ArrayObject();
    return true;
  }

  /**
   * @param string $id
   * @param ArrayObject $files
   * @param string $user
   * @return boolean
   */
  private function parseModification($id, ArrayObject $files, $user = null) {

    $logger = $this->container->get('logger');
    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    $logModification = $cacheLoggerModifications($id);
    $dataModification = $cacheDataModifications($id);

    // modificacao esta em um grupo com modificacao abortada
    if ($this->inAbortGroup($id)) {

      $message = "Modificação em um grupo abortado, abortando processamento";
      $logger->error($message);
      $logModification->error($message);
      return false;
    }

    $iterator = $files->getIterator();

    // flag para saber se o modification possui erros antes do parse
    $hadErrors = false;

    // tipo de erros
    // 0 nenhum erro
    // 1 skip
    // 2 abort
    $error = 0;

    // erros ao executar parse
    $this->hasErrorsOnParse = false;

    // realiza parse de cada arquivo da modificacao
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $path = $iterator->current();

      if (!$hadErrors && count($dataModification->getFileErrors($path)) > 0) {
        $hadErrors = true;
      }

      // limpa os erros para o arquivo atual
      $dataModification->setFileError($path, array());

      $error = $this->parseFile($path, $id, $user);

      if ($error !== 0) {
        $this->hasErrorsOnParse = true;
      }

      // se houver algum erro abort no meio do parse dos operations
      // entao aborta processamento atual
      if ($error === Operation::ERROR_ABORT) {
        break;
      }
    }

    // se houveram erros no parse, salvamos os metadados para persistir o array de erros
    // ou
    // se anteriormente possuiam erros e agora não tem mais
    // isso ajudará depois a identificar possiveis erros nos modifications pelo sistema.
    // essa complexidade serve para evitar overhead na hora de salvar os metadados
    if ($this->hasErrorsOnParse || ($hadErrors && !$this->hasErrorsOnParse) ) {
      $this->modificationToPersist[$id] = $dataModification;
    }

    // nao ocorreu nenhum erro
    if ($error !== Operation::ERROR_ABORT) {
      return true;
    }

    // erro ao processar modificacao, aborta
    $this->abortModifications[$id] = $dataModification;

    // marca todos os arquivos da modificacao atual para ser removidos
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $_user = $dataModification->isUserType() ? $user : 'global';
      $path = sprintf("%s%s/%s", $this->persistPath, $_user, $iterator->current());

      // remove arquivo da lista para salvar
      if (isset($this->dataToPersist[$path])) {
        unset($this->dataToPersist[$path]);
      }

      // marca arquivo para se removido
      $this->dataToRemove[$path] = true;
    }

    return false;
  }

  /**
   * @param string $path
   * @param string $modificationId
   * @param string $user
   * @return integer | error code
   */
  private function parseFile($path, $modificationId, $user = null) {

    $logger = $this->container->get('logger');
    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    $logModification = $cacheLoggerModifications($modificationId);
    $dataModification = $cacheDataModifications($modificationId);

    $_user = $dataModification->isUserType() ? $user : null;
    $data = new FileData($path, $_user);
    $data->type = $dataModification->isUserType() ? $user : 'global';
    $data->setPersistPath($this->persistPath);
    $this->loadDataContent($data);

    $originalPath = $data->getOriginalPath();
    $operations = $dataModification->getOperationsFile($path);

    if (empty($operations)) {

      $logger->error('Nenhum operacao para o arquivo: ' . $path);
      return true;
    }

    $parseFile = new FileParse();
    $parseFile->setContent($data->getContent());
    $parseFile->setOperations($operations);

    $messageTemplate = "file: '%s' operation: '%s'";
    $code = 0;

    try {

      // total de operacoes
      $countOperations = count($operations);

      // tipo de operacao
      $type = $user ? "user: $user" : "global";

      $logger->debug("Realizando parse: $path | operacoes: ". $countOperations . " | $type");

      // aplica as operacoes no conteudo do arquivo
      $parseFile->parse();

      // total de operacoes que foram executadas com sucesso
      $countOperationsParse = $countOperations - count($parseFile->getFailOperations());

      $logModification->info(
        sprintf(
          "Realizado parse: %s | operacoes executadas: %s/%s | %s",
          $path,
          $countOperationsParse,
          $countOperations,
          $type
        )
      );

      // para cada operacao com falha, nos salvamos no log
      foreach($parseFile->getFailOperations() as $operation) {

        $code = Operation::ERROR_SKIP;
        $message = sprintf($messageTemplate, $originalPath, $operation->label());
        // log do modification
        $this->logFailOperations($message, $originalPath, $modificationId, $operation->error());
        // log da aplicacao
        $logger->warning($modificationId . ' - ' . $message);
      }

      // troca o conteudo do arquivo pelo conteudo parseado
      $data->setContent($parseFile->getContent());

      // persiste conteudo
      $this->persistData($data);

    } catch(AbortException $error) {

      $code = Operation::ERROR_SKIP;
      $message = "[ABORT] " . sprintf($messageTemplate, $originalPath, $error->getMessage());
      // log do modification
      $this->logFailOperations($message, $originalPath, $modificationId, Operation::ERROR_ABORT);
      // log da aplicacao
      $logger->error($modificationId . ' - ' . $message);
    }

    return $code;
  }

  /**
   * @param Data\File $data
   * @return boolean
   */
  private function loadDataContent(FileData $data) {

    if ($data->type != 'global') {
      $data->global = new FileData($data->getOriginalPath());
      $data->global->setPersistPath($this->persistPath);
    }

    // arquivo for do tipo global
    // ou arquivo for de usuario e nao tiver global
    if ($data->type == 'global' || !isset($data->global)) {

      if ($data->exists()) {
        $data->load();
      } else {
        $data->loadContent();
      }

      return true;
    }

    // se o metadado for do tipo user
    // e nenhum cache de usuario para esse arquivo salvo
    // entao seta o conteudo com o conteudo do metadado global
    if (isset($data->global) && !$data->exists()) {

      // carrega o conteudo do arquivo de cache do tipo global
      if ($data->global->exists()) {
        $data->global->load();
      } else {
        $data->global->loadContent();
      }

      // define conteudo do cache atual
      $data->setContent($data->global->getContent());

      // "limera" memoria
      $data->global->getStorage()->clear();
      return true;
    }

    return true;
  }

  /**
   * @return boolean
   */
  private function persistData(FileData $data) {

    $logger = $this->container->get('logger');

    $originalContent = file_get_contents(ECIDADE_PATH . $data->getOriginalPath());
    $diff = $data->getContent() != $originalContent;
    $originalContent = null;

    // se o arquivo nao sofreu modificacao, entao segue para o proximo
    if (!$diff) {

      $this->dataToRemove[$data->getPath()] = true;
      return false;
    }

    // arquivo foi modificado e nao podera ser removido no final
    if (isset($this->dataToRemove[$data->getPath()])) {
      unset($this->dataToRemove[$data->getPath()]);
    }

    // registra arquivo para ser salvo no diretorio correto posteriormente
    $this->dataToPersist[$data->getPath()] = true;

    // salvo o arquivo modificado no diretorio temporario
    $data->save();

    $logger->debug(' - cache temporario gerado');

    return true;
  }

  /**
   * @param string $message Conteudo do log a ser gravado
   * @param string $path Arquivo do modification no qual deu o erro
   * @param string $modificationId Id da modification ao qual ocorreu o erro
   * @param integer $errorType Tipo do erro: Operation::ERROR_SKIP ou Operation::ERROR_ABORT
   * @return boolean
   */
  private function logFailOperations($message, $path, $modificationId, $errorType) {

    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    // log do modification
    $logModification = $cacheLoggerModifications($modificationId);
    // metadado do modification
    $dataModification = $cacheDataModifications($modificationId);

    // adiciona mais um registro de erro para o arquivo
    $dataModification->addFileError($path, array(
      'message' => $message,
      'type' => $errorType
    ));

    // loga o error de acordo com o tipo
    switch ($errorType) {
      default:
      case Operation::ERROR_SKIP:
        $logModification->warning($message);
      break;
      case Operation::ERROR_ABORT:
        $logModification->error($message);
      break;
    }

    return true;
  }

  /**
   * Verifica se modificacao esta em um grupo com modificacao abortada
   * @param string $id - id da modificacao
   * @return boolean
   */
  private function inAbortGroup($id) {

    if (count($this->abortModifications) == 0) {
      return false;
    }

    $group = $this->container->get('group');

    $iterator = $this->abortModifications->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $abortModificationID = $iterator->key();
      $dataModification = $iterator->current();
      $siblings = array();

      if ($dataModification->hasGroup()) {
        $siblings = $group->get($dataModification->getGroup());
      } else {
        $siblings = $group->getSiblings($abortModificationID);
      }

      if (in_array($id, $siblings)) {
        return true;
      }
    }

    return false;
  }

  /**
   * sincroniza arquivos de uma modificacao
   * usado para verificar a necessidade de atualizar cache
   * Ao abortar uma modificacao ele dessincroniza arquivo, este metodo ressincroniza
   *
   * @param array $modifications
   * @param string $user
   * @return boolean
   */
  private function resyncModificationFiles(Array $modifications, $user = null) {

    $logger = $this->container->get('logger');
    $cacheDataModifications = $this->container->get('cacheDataModifications');

    foreach ($modifications as $id) {

      $dataModification = $cacheDataModifications($id);

      $logger->debug('Sincronizando arquivos da modificação: ' . $id .  '('. count($dataModification->getFiles()) . ')');

      foreach ($dataModification->getFiles() as $path) {

        $_user = $dataModification->isUserType() ? $user : null;
        $dataFile = new FileData($path, $_user);
        $fileSync = new FileSync($dataFile);
        $fileSync->touch();
      }
    }

    return true;
  }

  /**
   * @return array
   */
  public function getAbortModifications() {
    return $this->abortModifications;
  }

  /**
   * @return boolean
   */
  public function hasErrorsOnParse() {
    return $this->hasErrorsOnParse;
  }

  /**
   * Remover diretorio temporario usado para gerar os caches
   * @return boolean
   */
  public function removePersistDirectory() {

    if (!is_dir($this->persistPath)) {
      return false;
    }

    $logger = $this->container->get('logger');
    $logger->debug(' - removendo diretorio temporario: ' . str_replace(ECIDADE_PATH, null, $this->persistPath));

    $directoryIterator = new \RecursiveDirectoryIterator($this->persistPath, \RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $fileinfo) {
      $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
      $todo($fileinfo->getRealPath());
    }

    return rmdir($this->persistPath);
  }

  /**
   * remove caches nao utilizados
   * @return boolean
   */
  public function removeUselessDataFile() {

    if (count($this->dataToRemove) == 0) {
      return false;
    }

    $logger = $this->container->get('logger');
    $logger->debug("Arquivos marcados para remover: " . count($this->dataToRemove));

    $removed = 0;

    // itera os arquivos que devem ser removidos
    $iterator = $this->dataToRemove->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $path = $iterator->key();
      $finalPath = str_replace($this->persistPath, ECIDADE_MODIFICATION_CACHE_PATH, $path);

      if (file_exists($path) && !unlink($path)) {
        $logger->error('Erro ao remover arquivo: ' . $path);
      }

      if (file_exists($finalPath)) {
        if (!unlink($finalPath)) {
          $logger->error('Erro ao remover arquivo: ' . $finalPath);
        } else {
          $removed++;
        }
      }

      $finalPath = null;
    }

    $this->dataToRemove = new ArrayObject();
    $logger->debug(" - arquivos removidos: " . $removed);
    return $removed > 0;
  }

  /**
   * @param Manager $manager
   * @return boolean
   */
  public function abortModifications(Manager $manager = null) {

    if (count($this->abortModifications) == 0) {
      return false;
    }

    if ($manager === null) {
      $manager = new Manager($this->container);
    }

    // separa modificacoes globais e por usuario
    $abortModificationsGlobal = array();
    $abortModificationsUser = array();

    $abortModifications = $this->abortModifications;
    $logger = $this->container->get('logger');
    $group = $this->container->get('group');

    // get modifications group
    foreach ($abortModifications as $modificationId => $dataModification) {

      $modificationsGroup = $group->getSiblings($modificationId);

      foreach ($modificationsGroup as $_modificationId) {

        if (!isset($abortModifications[$_modificationId])) {
          $abortModifications[$_modificationId] = ModificationData::restore($_modificationId);
        }
      }
    }

    foreach ($abortModifications as $modificationId => $dataModification) {

      if ($dataModification->isUserType()) {

        foreach ($this->users as $_user) {
          if ($dataModification->isEnabled($_user)) {
            $abortModificationsUser[$_user][] = $modificationId;
          }
        }

        continue;
      }

      if ($dataModification->isEnabled()) {
        $abortModificationsGlobal[] = $modificationId;
      }
    }

    if (!empty($abortModificationsGlobal)) {

      $logger->error("Abortando modificações globais: " . implode(', ', $abortModificationsGlobal));

      try {
        $manager->uninstall($abortModificationsGlobal);
        $this->resyncModificationFiles($modificationsID);
      } catch (Exception $error) {
        $logger->error("erro ao abortar modificações glboais: " . $error->getMessage());
      }
    }

    if (!empty($abortModificationsUser)) {

      foreach ($abortModificationsUser as $_user => $modificationsID) {

        $logger->error("Abortando modificações para usuário: " . implode(', ', $modificationsID));

        try {
          $manager->uninstall($modificationsID, $_user);
          $this->resyncModificationFiles($modificationsID, $_user);
        } catch (Exception $error) {
          $logger->error("erro ao abortar modificações para usuário '$_user': " . $error->getMessage());
        }
      }
    }

    $this->abortModifications = new ArrayObject();
    return true;
  }

  /**
   * desabilita
   * @param integer $verbosity
   * @return boolean
   */
  public function setModificationLogVerbosity($verbosity) {

    $cacheLoggerModifications = $this->container->get('cacheLoggerModifications');
    $iterator = $this->modificationOperationData->getIterator();
    for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {

      $type = $iterator->key();
      $modifications = $iterator->current();

      foreach ($modifications as $modificationId => $files) {

        $logModification = $cacheLoggerModifications($modificationId);
        $logModification->setVerbosity($verbosity);
      }
    }

    return true;
  }

}
