<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Modification\Manager as ModificationManager;
use \ECidade\V3\Extension\Logger;
use \ECidade\V3\Extension\Container;
use \ECidade\V3\Modification\Data\Modification as ModificationData;
use \ECidade\V3\Extension\Encode;
use \ECidade\V3\Modification\Data\Group as ModificationDataGroup;
use \ECidade\V3\Modification\Data\Modification as ModificationDataModification;
use \ECidade\V3\Modification\Parse\Operation as ModificationOperation;

/**
 * Manipulador do model Plugin, utilizado para realizar comportamento do Plugin
 */
class PluginService {

  const MENSAGEM = 'configuracao.configuracao.pluginService.';
  const TMP_DIR  = "tmp/plugins/";

  private $oPluginDatabase;

  /**
   * @type \ECidade\V3\Extension\Container
   */
  private $oContainer;

  /**
   * Instala o plugin validado
   *
   * @param  string $sNomeArquivo - Nome do plugin (É retornado pela função validarPlugin())
   * @throws Exception
   * @return boolean
   */
  public function instalarPlugin( $sNomeArquivo ) {

    if (!file_exists(self::TMP_DIR . "{$sNomeArquivo}.tar.gz")) {
      throw new Exception("Plugin não encotrado.");
    }

    try {

      $this->instalar( $sNomeArquivo );
      unlink(self::TMP_DIR . "{$sNomeArquivo}.tar.gz");
    } catch(Exception $e) {

      unlink(self::TMP_DIR . "{$sNomeArquivo}.tar.gz");
      throw new Exception($e->getMessage());
    }

    return true;
  }

  /**
   * Valida o arquivo do plugin
   *
   * @param  string $sCaminhoArquivo - Caminho do arquivo importado
   * @throws Exception
   * @return string - Nome do plugin Validado
   */
  public function validarPlugin( $sCaminhoArquivo ) {

    $sExt = pathinfo($sCaminhoArquivo, PATHINFO_EXTENSION);

    if ($sExt != "gz") {
      throw new Exception("Formato de arquivo inválido.");
    }

    /**
     * Descompacta o Plugin no temp
     */
    $sCaminhoPlugin = self::TMP_DIR . $this->descompactar( $sCaminhoArquivo );

    $oDataManifest = $this->loadManifest( $sCaminhoPlugin . "/Manifest.xml" );
    $sNomePlugin   = $oDataManifest->plugin->attributes()->name;
    $sNomeArquivo  = $sNomePlugin . uniqid();

    $logger = $this->createContainer($sNomePlugin)->get('logger');

    /**
     * Renomeia o arquivo importado do plugin
     */
    rename($sCaminhoArquivo, self::TMP_DIR . "{$sNomeArquivo}.tar.gz");

    try {

      $this->validar( $sCaminhoPlugin . "/" );
      $this->recursiveRemove($sCaminhoPlugin);

    } catch (Exception $e) {

      if (is_dir($sCaminhoPlugin)) {
        $this->recursiveRemove($sCaminhoPlugin);
      }

      unlink(self::TMP_DIR . "{$sNomeArquivo}.tar.gz");

      throw new Exception( $e->getMessage() );
    }

    return $sNomeArquivo;
  }

  /**
   * Instala o plugin
   *
   * @param  string $sCaminhoArquivo - Caminho do arquivo que foi feito upload
   * @throws Exception
   * @return boolean                 - True se plugin instalado com sucesso
   */
  private function instalar( $sNomePlugin ) {

    $this->descompactar( self::TMP_DIR . "{$sNomePlugin}.tar.gz" );

    $sCaminhoPlugin = self::TMP_DIR . $sNomePlugin;
    $oDataManifest  = $this->loadManifest( "{$sCaminhoPlugin}/Manifest.xml" );
    $sNomePlugin    = $oDataManifest->plugin->attributes()->name;

    $logger = $this->createContainer($sNomePlugin)->get('logger');
    $logger->info('instalando plugin ' . $sNomePlugin);

    $sNomePluginAnterior = null;
    $lInstalarArquivos   = false;
    $oManifestAnterior   = null;

    $aModificacoesInstaladas = array();
    $aModificacoesDesinstaladas = array();

    /**
     * Verifica se o plugin já esta instalado para efetuar a atualização
     */
    if (is_dir("plugins/{$sNomePlugin}")) {

      $logger->debug("plugin já instalado");

      // desinstala as modificacoes atuais
      $aModificacoesDesinstaladas = $this->desinstalarModificacoes(
        $this->getModificacoes($sNomePlugin),
        $sNomePlugin,
        $logger
      );

      $sNomePluginAnterior = $sNomePlugin . uniqid();

      $logger->debug("Movendo plugins/{$sNomePlugin} para ". self::TMP_DIR . $sNomePluginAnterior);

      $oManifestAnterior = $this->loadManifest( "plugins/{$sNomePlugin}/Manifest.xml" );
      rename( "plugins/{$sNomePlugin}", self::TMP_DIR . $sNomePluginAnterior );

      $logger->debug(
        sprintf(
          "Atualizando versão de %s para %s",
          $oManifestAnterior->plugin['plugin-version'],
          $oDataManifest->plugin['plugin-version']
        )
      );

      $aFilesPluginAnterior = $this->getArquivosPlugin( self::TMP_DIR . "{$sNomePluginAnterior}/fontes.tar.gz" );

      /**
       * Verifica se os arquivos do plugin estão instalados no e-cidade e os remove para instalar os novos
       */
      if (!empty($aFilesPluginAnterior) && is_file(".{$aFilesPluginAnterior[0]}")) {

        $logger->debug("Removendo arquivos da versão anterior");

        $lInstalarArquivos = true;
        $this->removerArquivosInstalados($aFilesPluginAnterior);
      }
    }

    $oDatabase = $this->getPluginDatabase();
    $oDatabase->connect();
    $oDatabase->execute("begin");
    $this->restaurarConexaoPadrao();

    try {

      $lAtivo  = false;
      $oPlugin = new Plugin(null, $oDataManifest->plugin->attributes()->name);

      /**
       * Verifica se o plugin ja esta ativo e atualiza os menus e a estrutura do banco
       */
      if ($oPlugin->getCodigo()) {
        $lAtivo = $oPlugin->getSituacao();
      } else {

        $oPlugin->setNome($oDataManifest->plugin->attributes()->name);
        $oPlugin->setSituacao(false);
      }

      $oPlugin->setLabel($oDataManifest->plugin->attributes()->label);
      $oPlugin->salvar();

      if ($lInstalarArquivos || $lAtivo) {

        $logger->debug("Plugin ativo");
        $this->instalarArquivosCompactados("{$sCaminhoPlugin}/fontes.tar.gz");
      }

      // Renomeia o arquivo depois de descompactar
      // pois o sistema estava gerando cache do tar
      rename($sCaminhoPlugin, "plugins/{$sNomePlugin}");

      if ($lInstalarArquivos || $lAtivo) {

        // instala as novas modidifacoes
        $aModificacoesInstaladas = $this->instalarModificacoes(
          $this->getModificacoes($sNomePlugin),
          $sNomePlugin,
          $logger
        );

        // verifica se ocorreu algun ABORT
        $oErrors = $this->getErrosModificacoes($aModificacoesInstaladas);
        $iErrors = $oErrors->error;
        if ($iErrors > 0) {
          throw new Exception("Ocorreu $iErrors erro(s) ao instalar modificações.");
        }
      }

      if ($lAtivo) {

        $aModulos = $this->salvarEstruturaModulos($oPlugin, $oDataManifest);

        // move imagem do modulo
        if (!empty($aModulos)) {
          $this->instalarModulos($aModulos);
        }

        // instalar release_notes
        $aReleaseNotes = $this->getReleaseNotes($oDataManifest);
        if (!empty($aReleaseNotes)) {
          $this->instalarReleaseNotes($oPlugin, $aReleaseNotes);
        }

        // instalar helps
        $aHelps = $this->getHelps($oDataManifest);
        if (!empty($aHelps)) {
          $this->instalarHelps($oPlugin, $aHelps);
        }

        if ($oManifestAnterior) {
          $this->rodaEstrutura($oPlugin, "update", (string) $oManifestAnterior->plugin['plugin-version']);
        }
      }

      /**
       * Remove a pasta do plugin anterior
       */
      if ($sNomePluginAnterior) {

        /**
         * Faz o tratamento do arquivo de configuração
         */
        if (is_file(self::TMP_DIR . "{$sNomePluginAnterior}/config.ini") && is_file("plugins/{$sNomePlugin}/config.ini")) {

          $aConfiguracaoFinal = array();

          $aConfigAntigo = parse_ini_file(self::TMP_DIR . "{$sNomePluginAnterior}/config.ini");
          $aConfigNovo = $this->getPluginConfig($oPlugin);

          foreach($aConfigNovo as $sConfiguracao => $sValor) {

            if (isset($aConfigAntigo[$sConfiguracao])) {
              $aConfiguracaoFinal[$sConfiguracao] = $aConfigAntigo[$sConfiguracao];
            } else {
              $aConfiguracaoFinal[$sConfiguracao] = $sValor;
            }
          }

          $this->setPluginConfig($oPlugin, $aConfiguracaoFinal);
        }

        $this->recursiveRemove(self::TMP_DIR . $sNomePluginAnterior);
      }

      $oDatabase->execute('commit');

    } catch (Exception $e) {

      $logger->error($e->getMessage());

      $oDatabase->execute("rollback");

      // verifica se deve remover arquivos do plugin atual(atualizacao) e instalar os antigo(versao anterior)
      $lReinstalarArquivos = !empty($sNomePluginAnterior) && ($lInstalarArquivos || $lAtivo);

      // remove arquivos da versao nova(atualizacao)
      if ($lReinstalarArquivos) {

        $this->desinstalarModificacoes($aModificacoesInstaladas, $oPlugin->getNome(), $logger);
        $this->removerArquivosInstalados($this->getArquivosPlugin("{$sCaminhoPlugin}/fontes.tar.gz"));
      }

      if (is_dir("plugins/{$sNomePlugin}")) {

        $logger->debug("- renomeando plugins/{$sNomePlugin} para $sCaminhoPlugin");
        rename("plugins/{$sNomePlugin}", $sCaminhoPlugin);

        if (!empty($sNomePluginAnterior)) {

          $logger->debug("- renomeando $sNomePluginAnterior para plugins/{$sNomePlugin}");
          rename(self::TMP_DIR . $sNomePluginAnterior, "plugins/{$sNomePlugin}");
        }
      }

      // reinstala arquivos da versao anterior
      if ($lReinstalarArquivos) {
        $this->instalarArquivosCompactados("plugins/{$sNomePlugin}/fontes.tar.gz");
      }

      $this->recursiveRemove($sCaminhoPlugin);

      $this->instalarModificacoes($aModificacoesDesinstaladas, $oPlugin->getNome(), $logger);

      throw new Exception($e->getMessage());
    }

    return true;
  }

  /**
   * Remove os arquivos do plugin do diretório do eCidade
   *
   * @param array $aFiles - Arquivos a serem removidos
   * @throws Exception
   */
  private function removerArquivosInstalados($aFiles) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug('Removendo arquivos instalados: ' . count($aFiles));

    foreach ($aFiles as $sFile) {

      if (!file_exists(".{$sFile}")) {
        continue;
      }

      if (!unlink(".{$sFile}")) {
        throw new Exception( _M( self::MENSAGEM . "erro_remover_arquivo", (object) array('sPath' => $sFile) ));
      }
    }
  }

  /**
   * Instala os fontes compactados no diretório do eCidade
   * @param string $sArquivo - Caminho do arquivo compactado dos fontes
   */
  private function instalarArquivosCompactados($sArquivo) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug('Instalando arquivos: ' . $sArquivo);

    $oArquivo = new PharData($sArquivo);
    $oArquivo->extractTo(".", null, true);
  }

  /**
   * Descompacta um arquivo tar.gz no diretório temporario do projeto
   *
   * @param  string $sCaminhoArquivo - Caminho do arquivo tar.gz
   * @param  string $sDestino - Caminho destino
   * @throws Exception
   * @return string - Nome do arquivo descompactado
   */
  private function descompactar($sCaminhoArquivo, $sDestino = '') {

    $oArquivo = new PharData($sCaminhoArquivo);
    $sDestino = empty($sDestino) ? basename($sCaminhoArquivo, ".tar.gz") : $sDestino;

    if (!$oArquivo->extractTo( self::TMP_DIR . $sDestino, null, true)) {
      throw new Exception( _M( self::MENSAGEM . "falha_descompactar" ) );
    }

    return $sDestino;
  }

  /**
   * Valida se o plugin esta instalado
   *
   * @param  string $sNomePlugin - Nome do plugin
   * @return boolean
   */
  private function instalado( $sNomePlugin ) {
    return (is_dir("plugins/{$sNomePlugin}") && file_exists("plugins/{$sNomePlugin}/Manifest.xml"));
  }

  /**
   * Verifica se o plugin já esta instalado
   *
   * @param string $sPathplugin Caminho do arquivo do plugin compactado
   * @return boolen
   */
  public function verificaAtualizacao( $sPathPlugin ) {

    if (!file_exists($sPathPlugin)) {
      return false;
    }

    if (!is_file("phar://{$sPathPlugin}/Manifest.xml")) {
      return false;
    }

    $oManifest = $this->loadManifest("phar://{$sPathPlugin}/Manifest.xml");

    return $this->instalado( $oManifest->plugin->attributes()->name );
  }

  /**
   * Descompacta os arquivos da estrutura e inclui o fonte dos callbacks
   * @param  string $sPathEstrutura
   * @param  string $sPathDestino
   */
  private function requireEstruturaCallback($sPathEstrutura, $sPathDestino) {

    $sPathEstruturaTmp = $this->descompactar($sPathEstrutura, $sPathDestino);

    require_once(modification("interfaces/iEstruturaPluginCallback.interface.php"));
    require_once(modification(self::TMP_DIR . "{$sPathEstruturaTmp}/EstruturaCallback.php"));
  }

  /**
   * Retorna array com os itens de menu
   * @param  object $oMenu
   * @return array  $aMenus
   */
  private function getMenus(SimpleXMLElement $oMenus = null) {

    $aRetorno = array();

    if ($oMenus === null) {
      return $aRetorno;
    }

    foreach ($oMenus as $oMenu) {
      $aRetorno = array_merge($aRetorno, $this->getItemMenu($oMenu));
    }

    return $aRetorno;
  }

  /**
   * @param SimpleXMLElement $oMenu
   * @return array
   */
  public function getItemMenu(SimpleXMLElement $oMenu) {

    foreach ($oMenu->item as $oItem) {

      $aMenu = (object) array(
        'type' => (string) $oMenu['type'],
        'parentId' => (string) $oMenu['parent-id'],
        'name' => (string) $oItem['name'],
        'file' => (string) $oItem['file'],
        'uid' => (string) $oItem['uid'],
        'liberadoCliente' => (string) $oItem['liberado-cliente'],
        'items' => array()
      );

      if (isset($oItem->item)) {
        $aMenu->items = $this->getItemMenu($oItem);
      }

      $aRetorno[] = $aMenu;
    }

    return $aRetorno;
  }

  private function getModulos(SimpleXMLElement $oManifest) {

    $idModulo = $oManifest->plugin['id-modulo'];
    $aModulos = array();

    if (!empty($idModulo)) {
      $aModulos[] = (object) array(
        'id' => $idModulo,
        'menus' => $this->getMenus($oManifest->plugin->menus->menu),
      );
    } else {

      foreach ($oManifest->plugin->modulos->modulo as $oModulo) {
        $aModulos[] = (object) array(
          'id' => (string) $oModulo['id'],
          'uid' => (string) $oModulo['uid'],
          'areaId' => (string) $oModulo['area-id'],
          'name' => (string) $oModulo['name'],
          'imagem' => (string) $oModulo['imagem'],
          'menus' => $this->getMenus($oModulo->menus->menu),
        );
      }

    }

    return $aModulos;
  }

  private function getReleaseNotes(SimpleXMLElement $oManifest) {

    $aReleaseNotes = array();

    if (empty($oManifest->plugin->{'release-notes'})) {
      return $aReleaseNotes;
    }

    foreach ($oManifest->plugin->{'release-notes'}->{'release-note'} as $oReleaseNote) {

      $aReleaseNotes[] = (object) array(
        'menuId' => (string) $oReleaseNote['menu-id'],
        'menuUid' => (string) $oReleaseNote['menu-uid'],
        'files' => $this->getFilesReleaseNote($oReleaseNote)
      );
    }

    return $aReleaseNotes;
  }

  private function getFilesReleaseNote(SimpleXMLElement $oReleaseNote) {

    $aFiles = array();

    foreach ($oReleaseNote->file as $oFile) {
      $aFiles[] = (object) array(
        'version' => (string) $oFile['version'],
        'name' => (string) $oFile['name']
      );
    }

    return $aFiles;
  }

  /**
   * Retorna um array com a arvore do Plugin.
   *
   * @param  string $sCaminho Caminho do diretorio
   * @param  string $sFolder  Diretorio a ser percorrido
   * @return array  $aRetorno caminho dos fontes.
   */
  private function getArquivosPlugin($sCaminho, $sFolder = '') {

    $sPathFontes     = 'phar://' . $sCaminho . $sFolder;
    $aRetorno        = array();
    $aArquivosPlugin = scandir($sPathFontes);

    foreach ($aArquivosPlugin as $sArquivo) {

      if ( is_dir($sPathFontes . '/' . $sArquivo ) ) {

       $aRetornoDiretorio = $this->getArquivosPlugin($sCaminho, $sFolder.'/'.$sArquivo);
       $aRetorno = array_merge($aRetorno, $aRetornoDiretorio);
      } else {
        $aRetorno[] = $sFolder . '/' . $sArquivo;
      }
    }

    return $aRetorno;
  }

  /**
   * Carrega o arquivo manifest.xml
   * @param  string $sCaminhosManifest
   * @return SimpleXml
   */
  public function loadManifest($sCaminhosManifest) {

    if (!file_exists($sCaminhosManifest)) {
      throw new Exception(_M(self::MENSAGEM . 'manifest_nao_existe'));
    }

    return simplexml_load_file($sCaminhosManifest);
  }

  /**
   * Desinstala um plugin do sistema
   *
   * @param  Plugin $oPlugin instância do plugin a ser desinstalado
   * @return boolean         True se desinstalado com sucesso
   */
  public function desinstalar(Plugin $oPlugin) {

    $logger = $this->createContainer($oPlugin->getNome())->get('logger');
    $logger->info('Desinstalando plugin');

    $sNomePlugin = $oPlugin->getNome();
    $lAtivo = $oPlugin->getSituacao();

    // faz parse dos modifications para busca arquivos de logs
    // antes do metodo desativar() remover eles
    $aLogsPaths = $this->getArquivosLog($sNomePlugin);

    if ($lAtivo) {
      $this->desativar($oPlugin);
    }

    if ($oPlugin->getCodigo()) {
      $oPlugin->excluir();
    }

    if (is_dir("plugins/{$sNomePlugin}")) {

      $aArquivosDesinstalar = $this->getArquivosPlugin( "plugins/{$sNomePlugin}/fontes.tar.gz" );

      // remove modificacoes: plugin desativado na base mas com fontes
      if ( !$lAtivo ) {
        $aModificacoesDesinstaladas = $this->desinstalarModificacoes(
          $this->getModificacoes($sNomePlugin),
          $oPlugin->getNome(),
          $logger
        );
      }

      if (!empty($aArquivosDesinstalar) && file_exists(".{$aArquivosDesinstalar[0]}")) {
        $this->removerArquivosInstalados($aArquivosDesinstalar);
      }

      $this->checkTempDir();

      if (!is_dir(self::TMP_DIR . date("YmdHis") . $sNomePlugin)) {
        rename("plugins/{$sNomePlugin}", self::TMP_DIR . date("YmdHis") . $sNomePlugin);
      }
    }

    // remove todos os logs
    foreach ($aLogsPaths as $path) {
      if (file_exists($path)) {
        unlink($path);
      }
    }

    return true;
  }

  /**
   * Verifica se existe o diretório temporario do plugin e cria o mesmo
   */
  public function checkTempDir() {

    if (!is_dir(self::TMP_DIR)) {
      mkdir(PluginService::TMP_DIR, 0777);
    }
  }

  /**
   * Ativa um plugin para uso
   * @param  Plugin $oPlugin instancia do Plugin que será ativado
   * @return boolean          Situação alterada
   */
  public function ativar(Plugin $oPlugin) {

    $oDataManifest = $this->loadManifest("plugins/{$oPlugin->getNome()}/Manifest.xml");
    $aDependenciasFaltando = $this->validarDependencias($oDataManifest->plugin);

    $logger = $this->createContainer($oPlugin->getNome())->get('logger');
    $logger->info('ativando plugin');

    // Se estiver faltando alguma dependência
    if (!empty($aDependenciasFaltando)) {

      $sListaPlugins = implode(', ', $aDependenciasFaltando);
      throw new BusinessException( _M( self::MENSAGEM . 'dependencias_faltando', (object) array('sListaPlugins' => $sListaPlugins)) );
    }

    // usado para saber as modificacoes que devem ser removidas caso ocorra algum erro
    $aModificacoesInstaladas = array();

    $oDatabase = $this->getPluginDatabase();
    $oDatabase->connect();
    $oDatabase->execute('begin');
    $this->restaurarConexaoPadrao();

    try {

      $aModulos = array();

      if (!$oPlugin->isAtivo()) {

        // Cria a estrutura do banco de dados
        $this->rodaEstrutura($oPlugin, "install");

        $aModulos = $this->salvarEstruturaModulos($oPlugin, $oDataManifest);
      }

      // Move os arquivos para o ecidade
      $this->instalarArquivosCompactados("plugins/{$oPlugin->getNome()}/fontes.tar.gz");

      // move imagem do modulo
      if (!empty($aModulos)) {
        $this->instalarModulos($aModulos);
      }

      // instalar release_notes
      $aReleaseNotes = $this->getReleaseNotes($oDataManifest);
      if (!empty($aReleaseNotes)) {
        $this->instalarReleaseNotes($oPlugin, $aReleaseNotes);
      }

      // instalar helps
      $aHelps = $this->getHelps($oDataManifest);
      if (!empty($aHelps)) {
        $this->instalarHelps($oPlugin, $aHelps);
      }

      // instala modificacoes e registra as que conseguiu instalar
      $aModificacoesInstaladas = $this->instalarModificacoes(
        $this->getModificacoes($oPlugin->getNome()),
        $oPlugin->getNome(),
        $logger
      );

      $oDatabase->execute('commit');

      // altera situacao do plugin e retorna true
      $oPlugin->setSituacao(true);
      return $oPlugin->alterarSituacao();

    } catch (Exception $oErro) {

      $logger->error($oErro->getMessage());
      $oDatabase->execute('rollback');

      // remove modificacoes
      $this->desinstalarModificacoes($aModificacoesInstaladas, $oPlugin->getNome(), $logger);

      // remove arquivos
      $aArquivosDesinstalar = $this->getArquivosPlugin( "plugins/". $oPlugin->getNome(). "/fontes.tar.gz" );
      $this->removerArquivosInstalados($aArquivosDesinstalar);

      throw $oErro;
    }

  }

  /**
   * Desativa um plugin para uso
   *
   * @param  Plugin $oPlugin instancia do Plugin que será desativado
   * @return boolean          Situação alterada
   */
  public function desativar(Plugin $oPlugin) {

    $logger = $this->createContainer($oPlugin->getNome())->get('logger');
    $logger->info('Desativando plugin');

    if (!$oPlugin->isAtivo()) {
      return false;
    }

    $oPlugin->setSituacao(false);

    $oDataManifest = $this->loadManifest("plugins/". $oPlugin->getNome()."/Manifest.xml");
    $oFiles = $oDataManifest->plugin->files;

    if ($oPlugin->getCodigo()) {

      $logger->info('Excluindo estrutura modulos');
      $oPlugin->alterarSituacao();
      $this->excluirEstruturaModulos($oPlugin, $oDataManifest);
    }

    /**
     * Verifica se algum outro plugin depende do plugin que será desativado
     */
    $aDependenciasReversas = $this->validarDependenciasReversas($oDataManifest);
    if (!empty($aDependenciasReversas)) {

      $sDependenciasReversas = implode(', ', $aDependenciasReversas);
      throw new BusinessException(_M( self::MENSAGEM . "dependencias_reversas", (object) array("sListaPlugins" => $sDependenciasReversas)));
    }

    $aArquivosDesinstalar = $this->getArquivosPlugin( "plugins/". $oPlugin->getNome(). "/fontes.tar.gz" );

    $aModificacoesDesinstaladas = array();

    $oDatabase = $this->getPluginDatabase();
    $oDatabase->connect();
    $oDatabase->execute('begin');
    $this->restaurarConexaoPadrao();

    try {

      // Remove a estrutura do banco de dados
      $this->rodaEstrutura($oPlugin, "uninstall");

      $aModificacoesDesinstaladas = $this->desinstalarModificacoes(
        $this->getModificacoes($oPlugin->getNome()),
        $oPlugin->getNome(),
        $logger
      );

      $this->removerArquivosInstalados($aArquivosDesinstalar);

      $this->desinstalarReleaseNotes($oPlugin);

      $oDatabase->execute('commit');

    } catch (Exception $oErro) {

      $logger->error($oErro->getMessage());
      $oDatabase->execute('rollback');

      $this->instalarArquivosCompactados("plugins/{$oPlugin->getNome()}/fontes.tar.gz");
      $this->instalarModificacoes($aModificacoesDesinstaladas, $oPlugin->getNome(), $logger);
      throw $oErro;
    }

    return true;
  }

  /**
   * Roda a estrutura do plugin na base de dados
   *
   * @param  Plugin $oPlugin
   * @param  string $sEstrutura - Tipo do arquivo de estrutura (install|uninstall|update)
   * @param  string $sOldVersion - Versão do plugin instalada para atualizar
   * @throws Exception
   * @return boolean
   */
  private function rodaEstrutura(Plugin $oPlugin, $sEstrutura, $sVersaoAnterior = null) {

    $lCallback = false;

    $sPathEstrutura = "plugins/{$oPlugin->getNome()}/estrutura.tar.gz";
    $oDataManifest  = $this->loadManifest("plugins/{$oPlugin->getNome()}/Manifest.xml");

    if (!property_exists($oDataManifest->plugin, 'estrutura')) {
      return false;
    }

    $aEstruturas   = array();
    $aAtualizacoes = (property_exists($oDataManifest->plugin->estrutura, 'estrutura') ? $oDataManifest->plugin->estrutura->estrutura : array());

    $logger = $this->getContainer()->get('logger');
    $logger->debug("Rodando estrutura $sEstrutura");

    /**
     * Verifica quais arquivos devem ser rodados
     */
    switch ($sEstrutura) {

      /**
       * Caso seja instalação roda todos os arquivos de estrutura
       */
      case "install":

        $aEstruturas[] = file_get_contents( "phar://{$sPathEstrutura}{$oDataManifest->plugin->estrutura[$sEstrutura]}" );

        foreach ($aAtualizacoes as $aAtualizacao) {
          $aEstruturas[] = file_get_contents( "phar://{$sPathEstrutura}{$aAtualizacao['file']}" );
        }
        break;

      /**
       * Caso seja desinstalação roda o arquivo de desinstalação
       */
      case "uninstall":

        $aEstruturas[] = file_get_contents( "phar://{$sPathEstrutura}{$oDataManifest->plugin->estrutura[$sEstrutura]}" );
        break;

      /**
       * Caso seja atualização roda somente os arquivos acima da versão já instalada
       */
      case "update":

        if (empty($sVersaoAnterior)) {
          return false;
        }

        foreach ($aAtualizacoes as $aAtualizacao) {

          if ($aAtualizacao['version'] > $sVersaoAnterior && $aAtualizacao['version'] <= (string) $oDataManifest->plugin['plugin-version']) {
            $aEstruturas[] = file_get_contents( "phar://{$sPathEstrutura}{$aAtualizacao['file']}" );
          }
        }
        break;

      default:
        return false;
    }

    if ( file_exists("phar://{$sPathEstrutura}/EstruturaCallback.php") ) {

      $this->requireEstruturaCallback($sPathEstrutura, $oPlugin->getNome() . "/estrutura");
      $lCallback = true;

      $oEstruturaCallback = new EstruturaCallback();
    }

    $oDatabase = $this->getPluginDatabase();
    $oDatabase->connect();

    try {

      $oDatabase->execute("select fc_startsession()");

      $rsSearchPath = $oDatabase->execute("show search_path");

      /**
       * Roda o callback antes da estrutura
       */
      if ($lCallback) {

        if ($sEstrutura == 'install') {
          $oEstruturaCallback->beforeInstall($oDatabase);
        } else if ($sEstrutura == 'uninstall') {
          $oEstruturaCallback->beforeUninstall($oDatabase);
        }
      }

      $oDatabase->execute("set search_path to plugins");

      foreach ($aEstruturas as $sEstrutura) {
        $oDatabase->execute($sEstrutura);
      }

      $oDatabase->execute("set search_path to " . Database::fetchRow($rsSearchPath, 0)->search_path);

      /**
       * Roda o callback depois da estrutura
       */
      if ($lCallback) {

        if ($sEstrutura == 'install') {
          $oEstruturaCallback->afterInstall($oDatabase);
        } else if ($sEstrutura == 'uninstall') {
          $oEstruturaCallback->afterUninstall($oDatabase);
        }
      }

      $this->restaurarConexaoPadrao();

    } catch (Exception $oException) {
      throw new Exception( "Estrutura:\n " . $oException->getMessage() );
    }
  }

  /**
   * Lê o arquivo de configuração "config/plugins.json" e retorna seu conteúdo
   * @throws Exception
   * @return JSON
   */
  public function getConfig() {

    $sPathConfigFile = "config/plugins.json";

    if (!file_exists($sPathConfigFile)) {
      throw new Exception( _M(self::MENSAGEM . "arquivo_config_nao_encontrado") );
    }

    $oConfiguracao = json_decode( file_get_contents($sPathConfigFile) );

    if (!property_exists($oConfiguracao, "AcessoBase")) {
      throw new Exception( _M(self::MENSAGEM . "acesso_base_nao_informado") );
    }

    if (!property_exists($oConfiguracao->AcessoBase, "usuario") || empty($oConfiguracao->AcessoBase->usuario)) {
      throw new Exception( _M(self::MENSAGEM . "usuario_base_nao_informado") );
    }

    if (!property_exists($oConfiguracao->AcessoBase, "senha") || empty($oConfiguracao->AcessoBase->senha)) {
      throw new Exception( _M(self::MENSAGEM . "senha_base_nao_informado") );
    }

    return $oConfiguracao;
  }

  /**
   * Cria os menus do plugin
   * @param  stdClass         $oItemMenu    Nó menu do xml Manifest
   * @param  integer          $iPlugin  Id do plugin
   * @param  integer          $iModulo  Id do módulo, especificado no xml Manifest
   * @param  integer          $iMenuPai Item de menu pai (utilizado para recursão da arvore)
   *                                    Caso não passe o pai, o método pega o pai de acordo com o xml Manifest e o módulo
   * @throws Exception
   * @return array Array com o id dos menus salvos
   */
  private function salvarMenus($oItemMenu, $iPlugin, $iModulo, $iMenuPai = null) {

    $aIdsRetorno = array();

    $oDaoDbitensmenu = new cl_db_itensmenu();
    $oDaoDbpermissao = new cl_db_permissao();
    $oDaoDbmenu      = new cl_db_menu();
    $oDaoPluginMenu  = new cl_db_pluginitensmenu();

    $idItemMenu = null;

    if (empty($iMenuPai)) {
      switch ($oItemMenu->type) {
        case 1:
          $sTipoDescricao = "Cadastros";
        break;
        case 2:
          $sTipoDescricao = "Consultas";
        break;
        case 3:
          $sTipoDescricao = "Relatórios";
        break;
        case 4:
          $sTipoDescricao = "Procedimentos";
        break;
        default:
          throw new Exception( _M( self::MENSAGEM . "tipo_menu_desconhecido", (object) array("sTipo" => $oItemMenu->type)));
      }

      $sSqlItenMenu = $oDaoDbitensmenu->sql_query_menus( null,
                                                         "i.id_item",
                                                         null,
                                                         "descricao = '{$sTipoDescricao}' and modulo = {$iModulo}" );

      $rsItenMenu = $oDaoDbitensmenu->sql_record($sSqlItenMenu);

      $oItemPai = db_utils::fieldsMemory($rsItenMenu, 0);

      $iMenuPai = $oItemPai->id_item;
    }

    $iIdItemMenu = null;
    $lIncluir = true;

    if (!empty($oItemMenu->uid)) {

      $sWhereMenuPluginInstalado = 'db146_uid = \'' . pg_escape_string($oItemMenu->uid) . '\' and db146_db_plugin = ' . $iPlugin;
      $sSqlMenuPluginInstalado = $oDaoPluginMenu->sql_query_file(null, 'db146_db_itensmenu', null, $sWhereMenuPluginInstalado);
      $rsMenuPluginInstalado = db_query($sSqlMenuPluginInstalado);

      if (!$rsMenuPluginInstalado) {
        throw new DBException('Erro ao buscar "uid" do item de menu.');
      }

      if (pg_num_rows($rsMenuPluginInstalado) > 0) {

        $iIdItemMenu = db_utils::fieldsMemory($rsMenuPluginInstalado, 0)->db146_db_itensmenu;
        $lIncluir = false;
      }
    }

    /**
     * Insere item de menu no sistema
     */
    $oDaoDbitensmenu->id_item    = $iIdItemMenu;
    $oDaoDbitensmenu->descricao  = utf8_decode($oItemMenu->name);
    $oDaoDbitensmenu->help       = utf8_decode($oItemMenu->name);
    $oDaoDbitensmenu->funcao     = $oItemMenu->file;
    $oDaoDbitensmenu->itemativo  = "1";
    $oDaoDbitensmenu->manutencao = "1";
    $oDaoDbitensmenu->desctec    = utf8_decode($oItemMenu->name);
    $oDaoDbitensmenu->libcliente = $oItemMenu->liberadoCliente;

    if ($lIncluir) {
      $oDaoDbitensmenu->incluir(null);
    } else {
      $oDaoDbitensmenu->alterar($iIdItemMenu);
    }

    $aIdsRetorno[] = $oDaoDbitensmenu->id_item;

    if ($oDaoDbitensmenu->erro_status == '0') {
      throw new DBException($oDaoDbitensmenu->erro_msg);
    }

    if ($lIncluir) {

      $oDaoPluginMenu->db146_sequencial   = null;
      $oDaoPluginMenu->db146_db_plugin    = $iPlugin;
      $oDaoPluginMenu->db146_db_itensmenu = $oDaoDbitensmenu->id_item;
      $oDaoPluginMenu->db146_uid = $oItemMenu->uid;
      $oDaoPluginMenu->incluir(null);

      if ($oDaoPluginMenu->erro_status == "0") {
        throw new DBException( _M( self::MENSAGEM . "falha_vinculacao_menu" ) );
      }

      /**
       * Busca o lugar certo na arvore de menus
       */
      $rsSequenciaMenu = $oDaoDbmenu->sql_record( $oDaoDbmenu->sql_query_file( null,
                                                                               "(max(menusequencia)+1) as menusequencia",
                                                                               null,
                                                                               "id_item = {$iMenuPai}") );

      if (!$rsSequenciaMenu) {
        throw new DBException( _M( self::MENSAGEM . "falha_organizar_menu", (object) array('sMenu' => $oItemMenu->name) ));
      }

      $oMenuSequencia = db_utils::fieldsMemory($rsSequenciaMenu,0);

      /**
       * Organizando o item de menu abaixo do item selecionado
       */
      $oDaoDbmenu->id_item        = $iMenuPai;
      $oDaoDbmenu->id_item_filho  = $oDaoDbitensmenu->id_item;
      $oDaoDbmenu->menusequencia  = $oMenuSequencia->menusequencia == NULL ? 1 : $oMenuSequencia->menusequencia;
      $oDaoDbmenu->modulo         = $iModulo;
      $oDaoDbmenu->incluir(null);

      if ($oDaoDbmenu->erro_status == '0') {
        throw new DBException($oDaoDbmenu->erro_msg);
      }

      /**
       * Liberando permissao de menu para o usuario que criou o relatorio
       */
      $oDaoDbpermissao->id_item        = $oDaoDbitensmenu->id_item;
      $oDaoDbpermissao->id_usuario     = db_getsession('DB_id_usuario');
      $oDaoDbpermissao->permissaoativa = '1';
      $oDaoDbpermissao->anousu         = db_getsession('DB_anousu');
      $oDaoDbpermissao->id_instit      = db_getsession('DB_instit');
      $oDaoDbpermissao->id_modulo      = $iModulo;

      $oDaoDbpermissao->incluir( db_getsession('DB_id_usuario'),
                                 $oDaoDbitensmenu->id_item,
                                 db_getsession('DB_anousu'),
                                 db_getsession('DB_instit'),
                                 $iModulo );

      if ($oDaoDbpermissao->erro_status == '0') {
        throw new DBException($oDaoDbpermissao->erro_msg);
      }

    }

    if ( !empty($oItemMenu->items) ) {
      foreach ($oItemMenu->items as $oItemFilho) {
        $aIdsRetorno = array_merge($aIdsRetorno, $this->salvarMenus($oItemFilho, $iPlugin, $iModulo, $oDaoDbitensmenu->id_item));
      }
    }

    return $aIdsRetorno;
  }

  private function salvarModulo($oModulo, $oPlugin) {

    $idNovoModulo = null;

    $sUid = $oModulo->uid;
    $lCriarModulo = true;

    // inserir modulo no sistema
    $oDaoDbModulo = new cl_db_modulos();
    $oDaoPluginModulo = new cl_db_pluginmodulos();
    $oDaoDbItensmenu = new cl_db_itensmenu();
    $oDaoDbMenu = new cl_db_menu();
    $oDaoAtendCadArea = new cl_atendcadareamod();

    // popular sIdNovoModulo com o id do modulo referente ao uid
    if (!empty($sUid)) {

      $sSqlPluginModulos = $oDaoPluginModulo->sql_query_file(null, '*', null, 'db152_uid = \'' . pg_escape_string($sUid) . '\'');
      $rsPluginModulos = db_query($sSqlPluginModulos);

      if (!$rsPluginModulos) {
        throw new DBException("Erro ao buscar 'uid' do módulo.");
      }

      if (pg_num_rows($rsPluginModulos) != 0) {
        $idNovoModulo = db_utils::fieldsMemory($rsPluginModulos, 0)->db152_db_modulo;
        $lCriarModulo = false;
      }
    }

    $oDaoDbItensmenu->id_item = $idNovoModulo;
    $oDaoDbItensmenu->descricao = utf8_decode($oModulo->name);
    $oDaoDbItensmenu->help = utf8_decode($oModulo->name);
    $oDaoDbItensmenu->funcao = null;
    $oDaoDbItensmenu->itemativo = "2";
    $oDaoDbItensmenu->manutencao = "1";
    $oDaoDbItensmenu->desctec = utf8_decode($oModulo->name);
    $oDaoDbItensmenu->libcliente = "true";

    if ( $lCriarModulo ) {
      $oDaoDbItensmenu->incluir(null);
    } else {
      $oDaoDbItensmenu->alterar($idNovoModulo);
    }

    if ($oDaoDbItensmenu->erro_status == '0') {
      throw new DBException($oDaoDbItensmenu->erro_msg);
    }

    $idNovoModulo = $oDaoDbItensmenu->id_item;

    // popula o objeto que veio por parametro
    $oModulo->id = $idNovoModulo;

    $oDaoDbModulo->id_item = $idNovoModulo;
    $oDaoDbModulo->nome_modulo = utf8_decode($oModulo->name);
    $oDaoDbModulo->descr_modulo = utf8_decode($oModulo->name);
    $oDaoDbModulo->imagem = ' ';
    $oDaoDbModulo->temexerc = 'false';
    $oDaoDbModulo->nome_manual = '';

    if ( $lCriarModulo ) {
      $oDaoDbModulo->incluir($idNovoModulo);
    } else {
      $oDaoDbModulo->alterar($idNovoModulo);
    }

    if ($oDaoDbModulo->erro_status == '0') {
      throw new DBException($oDaoDbModulo->erro_msg);
    }

    // caso seja somente alteração retorna aqui
    if ( !$lCriarModulo ) return;

    foreach(array('29', '30', '31', '32') as $index => $id) {

      $oDaoDbMenu->id_item = $idNovoModulo;
      $oDaoDbMenu->id_item_filho = $id;
      $oDaoDbMenu->menusequencia = $index + 1;
      $oDaoDbMenu->modulo = $idNovoModulo;
      $oDaoDbMenu->incluir(null);

      if ($oDaoDbMenu->erro_status == '0') {
        throw new DBException($oDaoDbMenu->erro_msg);
      }
    }

    // vincular modulo com a area: atendcadareamod

    $oDaoAtendCadArea->at26_sequencia = null;
    $oDaoAtendCadArea->at26_codarea = $oModulo->areaId;
    $oDaoAtendCadArea->at26_id_item = $idNovoModulo;
    $oDaoAtendCadArea->incluir(null);

    if ($oDaoAtendCadArea->erro_status == '0') {
      throw new DBException($oDaoAtendCadArea->erro_msg);
    }

    // vincular modulo com plugin na tabela dbpluginmodulos
    $oDaoPluginModulo = new cl_db_pluginmodulos();
    $oDaoPluginModulo->db152_sequencial = null;
    $oDaoPluginModulo->db152_db_plugin = $oPlugin->getCodigo();
    $oDaoPluginModulo->db152_db_modulo = $idNovoModulo;
    $oDaoPluginModulo->db152_uid = $sUid;
    $oDaoPluginModulo->incluir(null);

    if ($oDaoPluginModulo->erro_status == '0') {
      throw new DBException($oDaoPluginModulo->erro_msg);
    }

  }

  /**
   * @param  Array  $aModulos
   * @return bool
   */
  private function instalarModulos(Array $aModulos) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug("Instalando modulos: ". count($aModulos));

    foreach ($aModulos as $oModulo) {

      if (empty($oModulo->imagem)) {
        continue;
      }

      $sCaminhoArquivo = ECIDADE_PATH . 'skins/default/img/Modulos/' . $oModulo->id . '.png';

      if (file_exists($sCaminhoArquivo)) {
        unlink($sCaminhoArquivo);
      }

      if (!copy(ECIDADE_PATH . $oModulo->imagem, $sCaminhoArquivo)) {
        throw new Exception('Erro ao copiar imagem do módulo.');
      }
    }

    return true;
  }

  /**
   * Cria estrutura de modulos e menus do plugin
   */
  public function salvarEstruturaModulos(Plugin $oPlugin, SimpleXMLElement $oDataManifest) {

    $aModulos = $this->getModulos($oDataManifest);
    $aModulosRemover = $this->getModulosPlugin($oPlugin);

    $aItensMenuRemover = $this->getItensMenuPlugin($oPlugin);

    $logger = $this->getContainer()->get('logger');
    $logger->debug('Salvando estrutura modulos');

    foreach ($aModulos as $oModulo) {

      if (empty($oModulo->id)) {
        $this->salvarModulo($oModulo, $oPlugin);
      }

      $aModulosRemover = array_filter($aModulosRemover, function($oDadoModulo) use($oModulo) {
        return $oModulo->id != $oDadoModulo->db152_db_modulo;
      });

      foreach ($oModulo->menus as $oMenu) {

        $aMenusSalvos = $this->salvarMenus($oMenu, $oPlugin->getCodigo(), $oModulo->id, $oMenu->parentId);

        $aItensMenuRemover = array_filter($aItensMenuRemover, function($oItemMenu) use ($aMenusSalvos) {
          return !in_array($oItemMenu->db146_db_itensmenu, $aMenusSalvos);
        });
      }

      DBMenu::limpaCache('', '', $oModulo->id);
    }

    if (!empty($aItensMenuRemover)) {
      $this->excluirMenus($aItensMenuRemover);
    }

    if (!empty($aModulosRemover)) {
      $this->excluirModulos($aModulosRemover);
    }

    return $aModulos;
  }

  /**
   * @param  SimpleXMLElement $oDataManifest
   * @param  Plugin $oPlugin
   * @return bool
   */
  private function excluirEstruturaModulos(Plugin $oPlugin, SimpleXMLElement $oDataManifest) {

    $aItensMenu = $this->getItensMenuPlugin($oPlugin);
    $this->excluirMenus($aItensMenu);

    if (!empty($oDataManifest->plugin['id-modulo'])) {

      DBMenu::limpaCache('', '', $oDataManifest->plugin['id-modulo']);
      return true;
    }

    $aModulos = $this->getModulosPlugin($oPlugin);
    $this->excluirModulos($aModulos);

    foreach ($aModulos as $oModulo) {

      $sCaminhoArquivo = ECIDADE_PATH . 'skins/default/img/Modulos/' . $oModulo->db152_db_modulo . '.png';
      if (file_exists($sCaminhoArquivo) && !unlink($sCaminhoArquivo)) {
        throw new Exception('Erro ao remover imagem do módulo.');
      }
      DBMenu::limpaCache('', '', $oModulo->db152_db_modulo);
    }

    return true;
  }

  /**
   * @param  Plugin $oPlugin
   * @return array
   */
  private function getModulosPlugin(Plugin $oPlugin) {

    $oDaoPluginModulos  = new cl_db_pluginmodulos();
    $sSqlPluginModulos = $oDaoPluginModulos->sql_query_file(null, "db152_sequencial, db152_db_modulo", null,
                                                             "db152_db_plugin = " . $oPlugin->getCodigo());

    $rsPluginModulos = db_query($sSqlPluginModulos);

    if (!$rsPluginModulos) {
      throw new DBException('Erro ao buscar módulos do plugin.');
    }

    if (pg_num_rows($rsPluginModulos) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsPluginModulos);
  }

  public function getItensMenuPlugin(Plugin $oPlugin) {

    $oDaoPluginItensMenu  = new cl_db_pluginitensmenu();
    $sSqlPluginItensMenu = $oDaoPluginItensMenu->sql_query_file(null, "db146_sequencial, db146_db_itensmenu", null,
                                                             "db146_db_plugin = " . $oPlugin->getCodigo());

    $rsPluginItensMenu = db_query($sSqlPluginItensMenu);

    if (!$rsPluginItensMenu) {
      throw new DBException('Erro ao buscar os itens de menu do plugin.' . pg_last_error());
    }

    if (pg_num_rows($rsPluginItensMenu) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsPluginItensMenu);
  }

  /**
   * @param  Array  $aModulos
   * @return bool
   */
  private function excluirModulos(Array $aModulos) {

    $oDaoDbitensmenu = new cl_db_itensmenu();
    $oDaoDbpermissao = new cl_db_permissao();
    $oDaoDbmenu = new cl_db_menu();
    $oDaoDbusumod = new cl_db_usumod();
    $oDaoDbModulo = new cl_db_modulos();
    $oDaoPluginModulos = new cl_db_pluginmodulos();
    $oDaoAtendCadAreaMod = new cl_atendcadareamod();

    foreach ($aModulos as $oPluginModulo) {

      // remover db_itensmenu
      $oDaoDbitensmenu->excluir($oPluginModulo->db152_db_modulo);

      if ($oDaoDbitensmenu->erro_status == '0') {
        throw new DBException($oDaoDbitensmenu->erro_msg);
      }

      // remover db_permissao
      $oDaoDbpermissao->excluir(null, null, null, null, null, "id_modulo = " . $oPluginModulo->db152_db_modulo );

      if ($oDaoDbpermissao->erro_status == '0') {
        throw new DBException($oDaoDbpermissao->erro_msg);
      }

      // remover db_usumod
      $oDaoDbusumod->excluir(null, 'id_item = ' . $oPluginModulo->db152_db_modulo);

      if ($oDaoDbusumod->erro_status == '0') {
        throw new DBException($oDaoDbusumod->erro_msg);
      }

      // remover db_menu
      $oDaoDbmenu->excluir(null, "modulo = " . $oPluginModulo->db152_db_modulo);

      if ($oDaoDbmenu->erro_status == '0') {
        throw new DBException($oDaoDbmenu->erro_msg);
      }

      // remover atendcadarea
      $oDaoAtendCadAreaMod->excluir(null, 'at26_id_item = ' . $oPluginModulo->db152_db_modulo);

      if ($oDaoAtendCadAreaMod->erro_status == '0') {
        throw new DBException($oDaoAtendCadAreaMod->erro_msg);
      }

      // remover db_pluginmodulos
      $oDaoPluginModulos->excluir($oPluginModulo->db152_sequencial);

      if ($oDaoPluginModulos->erro_status == '0') {
        throw new DBException($oDaoPluginModulos->erro_msg);
      }

      // remover db_modulos
      $oDaoDbModulo->excluir($oPluginModulo->db152_db_modulo);

      if ($oDaoDbModulo->erro_status == '0') {
        throw new DBException($oDaoDbModulo->erro_msg);
      }
    }

    return true;
  }

  /**
   * Apaga os menus passados por parametro no formato db_utils::getCollectionByRedord
   * @param  Array $aItensMenu array de menus, exemplo: $this->getItensMenuPlugin()
   * @return bool
   */
  private function excluirMenus(Array $aItensMenu) {

    $oDaoDbitensmenu = new cl_db_itensmenu();
    $oDaoDbpermissao = new cl_db_permissao();
    $oDaoDbmenu      = new cl_db_menu();
    $oDaoPluginMenu  = new cl_db_pluginitensmenu();

    foreach ($aItensMenu as $oPluginMenu) {
      $oDaoDbpermissao->excluir(null, null, null, null, null, "id_item = " . $oPluginMenu->db146_db_itensmenu );
      $oDaoDbmenu->excluir(null, "id_item_filho = " . $oPluginMenu->db146_db_itensmenu);
      $oDaoPluginMenu->excluir($oPluginMenu->db146_sequencial);
      $oDaoDbitensmenu->excluir($oPluginMenu->db146_db_itensmenu);
    }

    return true;
  }

  /**
   * Retorna todos os plugins que estão no sistema
   * @return stdclass[] Coleção de plugins
   */
  public function getPlugins() {

    $aPlugins = array();

    foreach (scandir("plugins/") as $sFolder) {

      $sManifest = "plugins/{$sFolder}/Manifest.xml";

      if (!in_array($sFolder, array("..", '.')) && is_dir("plugins/{$sFolder}") && file_exists($sManifest)) {

        $oPluginSistema = new Plugin(null, $sFolder);

        $oDataManifest = $this->loadManifest($sManifest);

        if (!$oPluginSistema->getCodigo()) {

          $oPluginSistema->setNome((string) $oDataManifest->plugin->attributes()->name);
          $oPluginSistema->setLabel((string) $oDataManifest->plugin->attributes()->label);
          $oPluginSistema->setSituacao(false);
          $oPluginSistema->salvar();
        }

        $oPlugin = new stdClass();
        $oPlugin->iCodigo       = $oPluginSistema->getCodigo();
        $oPlugin->sNome         = $oPluginSistema->getNome();
        $oPlugin->sLabel        = $oPluginSistema->getLabel();
        $oPlugin->lConfiguracao = (boolean) $this->getPluginConfig($oPluginSistema);
        $oPlugin->nVersao       = (string) $oDataManifest->plugin['plugin-version'];
        $oPlugin->lSituacao     = $this->isAtivo($oPluginSistema);

        // plugin ativo, busca erros nos logs de modificacoes
        $oPlugin->oErrosModificacoes = $this->getErrosModificacoes(
          $oPlugin->lSituacao ?  $this->getModificacoes($oPluginSistema->getNome()) : array()
        );

        $aPlugins[] = $oPlugin;
      }
    }

    return $aPlugins;
  }

  /**
   * Retorna a configuração de um plugin, caso exista o arquivo de configuração
   *
   * @param  Plugin $oPlugin
   * @return mixed array|null
   */
  public static function getPluginConfig(Plugin $oPlugin) {

    $sPathConfig = "plugins/{$oPlugin->getNome()}/config.ini";

    if (!is_file($sPathConfig)) {
      return null;
    }

    $aConfiguracao = parse_ini_file($sPathConfig);
    return $aConfiguracao;
  }

  /**
   * Retorna a configuração de um plugin informada por parâmetro,
   * caso exista o arquivo de configuração
   *
   * @param  String  $sNamePlugin   Nome do plugin
   * @param  String  $sChaveConfig  Nome da chave a ser buscada
   *
   * @return  String Valor da chave;
   */
  public static function getPluginConfigByName($sNamePlugin, $sChaveConfig) {

    $sIndice       = "ConfiguracaoPlugin:{$sNamePlugin}";

    if ( DBRegistry::get($sIndice) ) {
      $aConfiguracao =  DBRegistry::get($sIndice);
    } else {
      $oPlugin       = new Plugin(null, $sNamePlugin);
      $aConfiguracao = PluginService::getPluginConfig($oPlugin);
      DBRegistry::add($sIndice, $aConfiguracao);
    }


    if (!isset($aConfiguracao[$sChaveConfig])) {
      throw new BusinessException(_M(self::MENSAGEM . 'configuracao_nao_encontrada'));
    }

    return $aConfiguracao[$sChaveConfig];
  }

  /**
   * Grava o arquivo de configuração do plugin
   *
   * @param Plugin $oPlugin
   * @param array $aConfig | array('nome da configuracao' => 'valor')
   * @return boolean
   */
  public static function setPluginConfig(Plugin $oPlugin, $aConfig) {

    $sPathConfig = "plugins/{$oPlugin->getNome()}/config.ini";

    if (!is_file($sPathConfig) || !is_writable($sPathConfig)) {
      return false;
    }

    $sContent = "";
    foreach ($aConfig as $sProperty => $sValue) {
      $sContent .= "{$sProperty}={$sValue}\n";
    }

    return (boolean) file_put_contents($sPathConfig, $sContent);
  }

  /**
   * Verifica se o Plugin esta ativo
   *
   * @param  Plugin  $oPlugin
   * @return boolean
   */
  public function isAtivo(Plugin $oPlugin) {

    $lAtivoPlugin  = $oPlugin->isAtivo();
    $oDataManifest = $this->loadManifest("plugins/{$oPlugin->getNome()}/Manifest.xml");

    $oFiles = $oDataManifest->plugin->files;

    foreach ($oFiles->file as $oFile) {

      $lAtivoArquivos = file_exists(".{$oFile['path']}" );
      break;
    }

    return $lAtivoPlugin && $lAtivoArquivos;
  }

  /**
   * Metodo responsavel por fazer a validação do Plugin.
   *
   *  -Todos os arquivos especificados no Manifest.XML devem existir no plugin.
   *  -Todos os arquivos que existem no plugin devem estar especificados no Manifest.XML.
   *  -Os arquivos especificados no Manifest.XML não podem existir no e-cidade.
   *  -A versão especificada no Manifest.XML deve ser <= que a versão atual do e-cidade.
   *  -Todas as dependências do plugin devem estar instaladas e ativadas
   *
   * @param  string $sPlugin caminho temporário do plugin.
   * @throws Exception
   * @return boolean
   */
  private function validar($sPlugin) {

    if (empty($sPlugin)) {
      throw new BusinessException(_M(self::MENSAGEM . 'manifest_nao_informado'));
    }

    /**
     * Carrega o Manifest.XML
     */
    $sCaminhosManifest = $sPlugin . "/Manifest.xml";
    $oPluginManifest   = $this->loadManifest($sCaminhosManifest);
    $oPlugin           = $oPluginManifest->plugin;
    $sNomePlugin       = $oPlugin->attributes()->name;

    /**
     * Verifica se o plugin já esta instalado
     */
    $lPluginInstalado  = $this->instalado( $sNomePlugin );

    /**
     * Array com todos os arquivos especificados no XML.
     */
    $aFilesXML = array();

    /**
     * Array com todos o arquivos fontes do pluguin.
     */
    $sPathFontes     = $sPlugin . "fontes.tar.gz";
    $aArquivosPlugin = $this->getArquivosPlugin($sPathFontes);

    /**
     * Verifica se todos os arquivos especificados no
     * XML existem no diretorio do plugin.
     */
    $oFiles = $oPlugin->files;

    foreach ($oFiles->file as $aFile) {

      $aFilesXML[] = $aFile['path'];

      if (!in_array($aFile['path'], $aArquivosPlugin)) {
        throw new BusinessException( _M(self::MENSAGEM . 'arquivo_nao_encontrado', (object) array('sPath' => $aFile['path'])) );
      }
    }

    /**
     * Verifica se todos os arquivos contidos no diretorio do
     * plugin estão especificados no arquivo XML
     */
    foreach ($aArquivosPlugin as $sArquivo) {

      if (!in_array($sArquivo, $aFilesXML)) {
        throw new BusinessException( _M(self::MENSAGEM . 'arquivo_nao_especificado', (object) array('sPath' => $sArquivo)) );
      }
    }

    /**
     * Verifica se o plugin já esta instalado para validar a atualização
     */
    if ($lPluginInstalado) {

      $oManifestInstalado  = $this->loadManifest( "plugins/{$sNomePlugin}/Manifest.xml" );
      $aArquivosInstalados = $this->getArquivosPlugin( "plugins/{$sNomePlugin}/fontes.tar.gz" );

      if (((string) $oPlugin['plugin-version']) < ((string) $oManifestInstalado->plugin['plugin-version'])) {
        throw new Exception( _M( self::MENSAGEM . 'versao_ja_instalada') );
      }
    }

    /**
     * Verifica se os arquivos informados no plugin já existem no e-cidade e se
     * não esta sendo incluido nos fontes o arquivo db_conecta.php
     */
    foreach ($aArquivosPlugin as $sArquivo) {

      /**
       * Verifica se os arquivos estão incluindo o "db_conecta.php"
       */
      if (file_exists("phar://{$sPlugin}fontes.tar.gz{$sArquivo}") && preg_match('/db_conecta\.php/', file_get_contents("phar://{$sPlugin}fontes.tar.gz{$sArquivo}"))) {
        throw new BusinessException( _M( self::MENSAGEM . 'db_conecta_incluido', (object) array('sPath' => $sArquivo)) );
      }

      /**
       * Verifica se o plugin já não esta instalado e se o arquivo já existe no eCidade
       */
      if (!$lPluginInstalado && file_exists("./$sArquivo")) {
        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_ja_existe', (object) array('sPath' => $sArquivo)) );
      }

      /**
       * Verifica se o plugin já esta instalado e se o arquivo informado é um arquivo novo desta versão do plugin e se já existe no eCidade
       */
      if ($lPluginInstalado && !in_array($sArquivo, $aArquivosInstalados) && file_exists("./{$sArquivo}")) {
        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_ja_existe', (object) array('sPath' => $sArquivo)) );
      }
    }

    /**
     * Verifica se a versão especificada no XML é menor ou igual a do e-cidade.
     */
    $iVersao  = $GLOBALS['db_fonte_codversao'];
    $iRelease = $GLOBALS['db_fonte_codrelease'];
    $sVersao  = "2.{$iVersao}.{$iRelease}";

    if ( $oPlugin['ecidade-version'] > $sVersao){
      throw new BusinessException(_M(self::MENSAGEM . 'versao_invalida'));
    }

    /**
     * Pega os módulos do manifest e seus respectivos menus para valida-los
     */
    $aModulos = $this->getModulos($oPluginManifest);
    $oMenus = $oPlugin->menus;
    $aUidsManifest = array();

    // Pega somente os uids dos menus.
    // usado para validar os release notes posteriormente
    $aUidsMenus = array();

    /**
     * Funcao recursiva para varredura e validacao dos menus
     */
    $fnValidarMenu = function($oMenu) use ($aArquivosPlugin, & $fnValidarMenu, & $aUidsManifest, & $aUidsMenus) {

      if (!empty($oMenu->items)) {
        array_walk($oMenu->items, $fnValidarMenu);
      }

      if (empty($oMenu->file)) {
        return;
      }

      if (!empty($oMenu->uid)) {

        if (in_array($oMenu->uid, $aUidsManifest)) {
          throw new BusinessException('UID informado no menu: ' . $oMenu->name  . ' já foi informado em outra tag.');
        }

        $aUidsManifest[] = $oMenu->uid;
        $aUidsMenus[] = $oMenu->uid;
      }

      $sArquivoMenu = '/' . ltrim($oMenu->file, '/');
      $sArquivoMenu = current(explode('?',  $sArquivoMenu));

      if (!in_array($sArquivoMenu, $aArquivosPlugin)) {
        throw new BusinessException( _M(PluginService::MENSAGEM . 'arquivo_nao_especificado_menu', (object) array('sPath' => $sArquivoMenu)) );
      }
    };

    /**
     * Percorre os modulos informados no manifest e valida se existe no banco de dados
     */
    foreach ($aModulos as $oModulo) {

      /**
       * Caso o modulo informado possua "id" ele é validado.
       * Se for um módulo novo (criado pelo plugin) não precisa validação pois não existe no banco ainda.
       */
      $this->validaModulo($oModulo);

      /**
       * Percorre os menus do modulo recursivamente pela funcao informada para validar os arquivos informados no menu
       */
      array_walk($oModulo->menus, $fnValidarMenu);

      if (!empty($oModulo->imagem) && !in_array('/' . $oModulo->imagem, $aArquivosPlugin)) {
        throw new BusinessException('Arquivo de imagem informado para o módulo "' . $oModulo->name . '" não foi informado em "<files/>".');
      }

      if (!empty($oModulo->uid)) {

        if (in_array($oModulo->uid, $aUidsManifest)) {
          throw new BusinessException('UID informado no módulo: ' . $oModulo->name  . ' já foi informado em outra tag.');
        }

        $aUidsManifest[] = $oModulo->uid;
      }

    }

    $aReleaseNotes = $this->getReleaseNotes($oPluginManifest);
    $sCaminhoReleaseNotes = 'phar://' . $sPlugin . 'release_notes.tar.gz/release_notes/';

    foreach ($aReleaseNotes as $oReleaseNote) {

      if (empty($oReleaseNote->menuId)) {

        if (empty($oReleaseNote->menuUid)) {
          throw new BusinessException("ID ou UID no menu não informados.");
        }

        // valida uid
        if (!in_array($oReleaseNote->menuUid, $aUidsMenus)) {
          throw new BusinessException(
            "Atributo 'menu-uid' informado no releases notes não encontrado nas tags de menu: " . $oReleaseNote->menuUid
          );
        }

      } else {

        // valida id do menu
        $oDaoDbitensmenu = new cl_db_itensmenu();
        $sSqlValidarMenu = $oDaoDbitensmenu->sql_query_file($oReleaseNote->menuId);
        $rsValidarMenu = db_query($sSqlValidarMenu);

        if (!$rsValidarMenu) {
          throw new DBException('Erro ao buscar item de menu: ' . $oReleaseNote->menuId);
        }

        if (pg_num_rows($rsValidarMenu) == 0) {
          throw new DBException('Item de menu para release notes não encontrado: ' . $oReleaseNote->menuId);
        }
      }

      foreach ($oReleaseNote->files as $oFile) {

        $sCaminhoReleaseNoteDestino = $sCaminhoReleaseNotes . $oFile->version . '/' . $oFile->name;

        if (!file_exists($sCaminhoReleaseNoteDestino)) {
          throw new Exception("Arquivo de origem do release notes não encontrado: " . $sCaminhoReleaseNoteDestino );
        }
      }

    }

    $aHelps = $this->getHelps($oPluginManifest);
    $sCaminhoHelps = 'phar://' . $sPlugin . 'helps.tar.gz/helps/';

    foreach ($aHelps as $oHelp) {

      if (empty($oHelp->menuId)) {

        if (empty($oHelp->menuUid)) {
          throw new BusinessException("ID ou UID no menu não informados.");
        }

        // valida uid
        if (!in_array($oHelp->menuUid, $aUidsMenus)) {
          throw new BusinessException(
            "Atributo 'menu-uid' informado no Help não encontrado nas tags de menu: " .$oHelp->menuUid
          );
        }

      } else {

        // valida id do menu
        $oDaoDbitensmenu = new cl_db_itensmenu();
        $sSqlValidarMenu = $oDaoDbitensmenu->sql_query_file($oHelp->menuId);
        $rsValidarMenu = db_query($sSqlValidarMenu);

        if (!$rsValidarMenu) {
          throw new DBException('Erro ao buscar item de menu: ' . $oHelp->menuId);
        }

        if (pg_num_rows($rsValidarMenu) == 0) {
          throw new DBException('Item de menu para Help não encontrado: ' . $oHelp->menuId);
        }
      }

      $sCaminhoArquivo = $sCaminhoHelps . ltrim($oHelp->file, '/');

      if (!file_exists($sCaminhoArquivo)) {
        throw new Exception("Arquivo de Help não encontrado: " . $oHelp->file);
      }

      $oJsonHelp = json_decode(file_get_contents($sCaminhoArquivo));

      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new BusinessException('Arquivo json inválido: ' . $oHelp->file);
      }
    }

    /**
     * Valida os arquivos que irão criar a estrutura no banco de dados
     */
    if (property_exists($oPlugin, "estrutura")) {

      /**
       * Caminho do arquivo de estrutura do plugin
       */
      $sPathEstrutura = $sPlugin . "estrutura.tar.gz";

      if (!file_exists($sPathEstrutura)) {
        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => 'estrutura.tar.gz')) );
      }

      /**
       * Array contendo todos os arquivos de estrutura compactador
       */
      $aArquivosEstrutura = $this->getArquivosPlugin($sPathEstrutura);

      if (!isset($oPlugin->estrutura['install'])) {
        throw new BusinessException( _M( self::MENSAGEM . 'estrutura_install_nao_informado') );
      }

      if (!isset($oPlugin->estrutura['uninstall'])) {
        throw new BusinessException( _M( self::MENSAGEM . 'estrutura_uninstall_nao_informado') );
      }

      if (!in_array($oPlugin->estrutura['install'], $aArquivosEstrutura)) {

        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => $oPlugin->estrutura['install'])) );
      }

      if (!in_array($oPlugin->estrutura['uninstall'], $aArquivosEstrutura)) {

        throw new BusinessException( _M( self::MENSAGEM . 'arquivo_nao_encontrado',
                                         (object) array('sPath' => $oPlugin->estrutura['uninstall'])) );
      }

      /**
       * Valida a estrutura das novas versões do plugin
       */
      if (property_exists($oPlugin->estrutura, "estrutura")) {

        foreach($oPlugin->estrutura->estrutura as $oEstrutura) {

          if ($oEstrutura["version"] > $oPlugin['plugin-version']) {
            throw new Exception( _M( self::MENSAGEM . 'versao_estrutura_superior' ) );
          }

          if (!in_array($oEstrutura["file"], $aArquivosEstrutura)) {
            throw new Exception( _M( self::MENSAGEM . 'arquivo_nao_encontrado', (object) array('sPath' => $oEstrutura["file"])) );
          }
        }
      }

      if (in_array("/EstruturaCallback.php", $aArquivosEstrutura)) {

        $this->requireEstruturaCallback( $sPathEstrutura, basename($sPlugin) . "/estrutura" );

        if (!class_exists("EstruturaCallback")) {
          throw new BusinessException( _M( self::MENSAGEM . 'classe_estrutura_nao_encontrada' ) );
        }

        if (!in_array("EstruturaPluginCallback", class_implements("EstruturaCallback"))) {
          throw new BusinessException( _M( self::MENSAGEM . 'classe_estrutura_sem_interface' ) );
        }
      }

    }

    $aDependenciasFaltando = $this->validarDependencias($oPlugin);

    /**
     * Se estiver faltando alguma dependência
     */
    if (!empty($aDependenciasFaltando)) {

      $sListaPlugins = implode(', ', $aDependenciasFaltando);
      throw new BusinessException( _M( self::MENSAGEM . 'dependencias_faltando', (object) array('sListaPlugins' => $sListaPlugins)) );
    }

    return true;
  }

  /**
   * Verifica se o módulo informado é valido
   * @param stdClass $oModulo
   */
  private function validaModulo($oModulo) {

    if (!empty($oModulo->id)) {

      $oDaoModulo = new cl_db_modulos();

      $sSqlModulo = $oDaoModulo->sql_query_file($oModulo->id);
      $rsModulo   = $oDaoModulo->sql_record( $sSqlModulo );

      if (!$rsModulo || !pg_num_rows($rsModulo)) {
        throw new BusinessException( _M(self::MENSAGEM . 'id_modulo_invalido') );
      }

      if (!empty($oModulo->imagem)) {
        throw new BusinessException("Imagem de módulo existente não pode ser substituida.");
      }

      return true;
    }

    // valida informacoes para poder criar modulo
    if (empty($oModulo->areaId)) {
      throw new BusinessException( 'Id da área não informado.' );
    }

    $oDaoAtendCadArea = new cl_atendcadarea();
    $sSqlArea = $oDaoAtendCadArea->sql_query_file($oModulo->areaId);
    $rsArea = $oDaoAtendCadArea->sql_record($sSqlArea);

    if (!$rsArea || !pg_num_rows($rsArea)) {
      throw new BusinessException( 'Área informada não existe: ' . $oModulo->areaId );
    }

    return true;
  }

  /**
   * Valida as dependências reversas de um plugin
   *
   * @return array Plugins dependentes
   */
  private function validarDependenciasReversas($oPlugin) {

    $aPlugins              = $this->getPlugins();
    $aDependenciasReversas = array();
    $sNomePlugin           = $oPlugin->plugin->attributes()->name;

    foreach ($aPlugins as $oPluginComparado) {

      $oPluginComparadoConfig = $this->loadManifest("plugins/{$oPluginComparado->sNome}/Manifest.xml");
      $sNomePluginComparado   = (string) $oPluginComparadoConfig->plugin['name'];
      $nVersaoPluginComparado = (string) $oPluginComparadoConfig->plugin['plugin-version'];

      if (property_exists($oPluginComparadoConfig->plugin, "dependencies")) {

        /**
         * Coloca o nome de todas as dependências em um array
         */
        $aDependenciasPlugin = array();
        foreach ($oPluginComparadoConfig->plugin->dependencies->plugin as $aDependencia) {
          $aDependenciasPlugin[] = (string) $aDependencia['name'];
        }

        /**
         * Verifica se o plugin validado está entre as dependências do plugin comparado
         */
        if (in_array($sNomePlugin, $aDependenciasPlugin) && $oPluginComparado->lSituacao) {
          /**
           * Adiciona o plugin comparado na lista de dependências reversas
           */
          $aDependenciasReversas[] = "{$sNomePluginComparado} {$nVersaoPluginComparado}";
        }

      }
    }

    return $aDependenciasReversas;
  }

  /**
   * Valida as dependências do plugin
   *
   * @return array Dependências faltando
   */
  private function validarDependencias($oPlugin) {

    $aDependenciasFaltando = array();

    if (property_exists($oPlugin, "dependencies")) {

      $aPlugins = $this->getPlugins();
      foreach ($oPlugin->dependencies->plugin as $aDependencia) {

        /**
         * Se a dependência não está ativa
         */
        if (!$this->validarDependenciaAtiva( $aDependencia, $aPlugins )) {
          /**
           * Adiciona na lista de dependências faltando
           */
          $aDependenciasFaltando[] = "{$aDependencia['name']} {$aDependencia['version']}";
        }

      }

    }

    return $aDependenciasFaltando;
  }

  /**
   * Verifica se a dependência informada está instalada e ativa
   *
   * @param array $aDependencia
   * @param array $aPlugins
   * @return boolean Verdadeiro se a dependência estiver instalada e ativa
   */
  private function validarDependenciaAtiva($aDependencia, $aPlugins) {

    foreach ($aPlugins as $oPlugin) {

      $oPluginConfig = $this->loadManifest("plugins/{$oPlugin->sNome}/Manifest.xml");
      $nVersao       = (float) $oPluginConfig->plugin['plugin-version'];

      if ($aDependencia['name'] == $oPlugin->sNome && $oPlugin->lSituacao && $nVersao >= $aDependencia['version']) {
        return true;
      }
    }

    return false;
  }

  /**
   * Remove um diretório recursivamente
   * @param string $sDir
   */
  private function recursiveRemove($sDir) {
    $oDirs = new DirectoryIterator($sDir);

    foreach ($oDirs as $oDir) {

      if ( $oDir->isDot() ) {
        continue;
      }

      if ( $oDir->isDir() ) {
        $this->recursiveRemove($oDir->getPathname());
      }

      if ($oDir->isFile()) {
        unlink($oDir->getPathname());
      }

    }

    rmdir($sDir);
  }

  /**
   * @return Database
   */
  public function getPluginDatabase() {

    if ($this->oPluginDatabase === null) {

      $oConfiguracao = $this->getConfig()->AcessoBase;
      $oDatabase = new Database();
      $oDatabase->setBase( pg_dbname() );
      $oDatabase->setServidor( pg_host() );
      $oDatabase->setPorta( pg_port() );
      $oDatabase->setUsuario( $oConfiguracao->usuario );
      $oDatabase->setSenha( $oConfiguracao->senha );
      $this->oPluginDatabase = $oDatabase;
    }

    return $this->oPluginDatabase;
  }

  /**
   * @return resource
   */
  public function restaurarConexaoPadrao() {

    return $GLOBALS['conn'] = pg_connect(
      "host="  . db_getsession("DB_servidor", false) .
      " dbname=" . (db_getsession("DB_NBASE", false) ?: db_getsession("DB_base", false )) .
      " port=" . db_getsession("DB_porta", false) .
      " user=" . db_getsession("DB_user", false) .
      " password=" . db_getsession("DB_senha", false)
    );
  }

  /**
   * @param string $sNomePlugin
   * @param string $sCaminhoBase
   * @return array
   */
  public function getModificacoes($sNomePlugin) {

    $aModificacoes = array();
    $sPath = "plugins/$sNomePlugin/fontes.tar.gz";

    if (!file_exists($sPath)) {
      return $aModificacoes;
    }

    $aArquivos = $this->getArquivosPlugin($sPath);

    foreach ($aArquivos as $sArquivo) {

      if (strpos($sArquivo, '/modification/xml/') === 0) {
        $aModificacoes[] = ltrim($sArquivo, '/');
      }
    }

    return $aModificacoes;
  }

  /**
   * @param array $aModificacoes
   * @param string $sNomePlugin
   * @param \ECidade\V3\Extension\Logger $logger
   * @return array
   */
  public function instalarModificacoes(Array $aModificacoes, $sNomePlugin, Logger $logger = null) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug("Instalando modificações: " . count($aModificacoes));

    try {

      $aInstaladas = array();
      $aModificacoesID = array();
      $container = null;

      if ($logger) {

        $container = new Container();
        $container->register('logger', $logger);
      }

      $oConfig = Registry::get('app.config');

      $oManager = new ModificationManager($container);
      $sLogin = db_getsession('DB_login');

      // mais de uma modificacao no plugin, agrupa para poder abortar se necessario
      $lAgrupar = count($aModificacoes) > 1;
      $sGrupoPlugin = 'ecidade-plugin-' . $sNomePlugin;

      $aModificationData = array();

      foreach ($aModificacoes as $sCaminhoModificacao) {

        $logger->debug(" - unpack modification: $sCaminhoModificacao");

        $parseModification = $oManager->unpack($sCaminhoModificacao, true);
        $oModification = ModificationData::restore($parseModification->getId());
        $aModificationData[] = $oModification;
        $lModificacaoAlterada = false;

        // modificacao por algum motivo desconhecido esta ativa
        if ($oModification->isEnabled($sLogin)) {
          $oModification->setStatus(ModificationData::STATUS_DISABLED);
          $lModificacaoAlterada = true;
        }

        if ($lAgrupar) {

          $sGrupo = $oModification->getGroup();
          // modification nao tem grupo, cria um com nome do plugin
          if (empty($sGrupo)) {
            $lModificacaoAlterada = true;
            $oModification->setGroup($sGrupoPlugin);
          }
        }

        $aModificacoesID[] = $oModification->getId();
        $aInstaladas[] = $sCaminhoModificacao;

        if ($lModificacaoAlterada) {
          $oModification->save();
        }
      }

      if (!empty($aModificacoesID)) {
        $oManager->install($aModificacoesID, $sLogin);
      }

      $oManager = null;

    } catch (Exception $oErro) {
      throw new Exception(Encode::toISO($oErro->getMessage()));
    }

    return $aInstaladas;
  }

  /**
   * @param string $aModificacoes
   * @param string $sNomePlugin
   * @param \ECidade\V3\Extension\Logger $logger
   * @return array
   */
  public function desinstalarModificacoes(Array $aModificacoes, $sNomePlugin, Logger $logger = null) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug("Desinstalando modificações: " . count($aModificacoes));

    try {

      $aRemovidas = array();
      $aModificacoesID = array();
      $container = null;

      if ($logger) {

        $container = new Container();
        $container->register('logger', $logger);
      }

      $oConfig = Registry::get('app.config', Registry::get('config'));
      $oManager = new ModificationManager($container);
      $sLogin = db_getsession('DB_login');
      $lAgrupar = false;
      $lGruposAlterado = false;

      $oGrupo = ModificationDataGroup::restore();
      $sGrupoPlugin = 'ecidade-plugin-' . $sNomePlugin;
      $lAgrupar = count($aModificacoes) > 1;

      foreach ($aModificacoes as $sCaminhoModificacao) {

        if (!file_exists($sCaminhoModificacao)) {
          $logger->debug(" - arquivo não existe: " . $sCaminhoModificacao);
          continue;
        }

        $logger->debug(" - parse modification: $sCaminhoModificacao");

        $oParse = $oManager->parse($sCaminhoModificacao);
        $oData = ModificationDataModification::restore($oParse->getId());

        // modificacao desinstalada ou abortada
        if (!$oData->isEnabled($sLogin)) {
          continue;
        }

        if ($lAgrupar && !$oData->hasGroup()) {

          $lGruposAlterado = true;
          $oGrupo->add($sGrupoPlugin, $oParse->getId());
          $oData->setGroup($sGrupoPlugin);
          $oData->save();
        }

        $aModificacoesID[] = $oParse->getId();
        $aRemovidas[] = $sCaminhoModificacao;
      }

      if ($lGruposAlterado) {
        $oGrupo->save();
      }

      if (!empty($aModificacoesID)) {
        $oManager->uninstall($aModificacoesID, $sLogin);
      }

      return $aRemovidas;

    } catch (Exception $oErro) {
      throw new Exception(Encode::toISO($oErro->getMessage()));
    }
  }

  /**
   * @param Plugin $oPlugin
   * @param Array $aReleaseNotes
   * @return boolean
   */
  private function instalarReleaseNotes(Plugin $oPlugin, $aReleaseNotes) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug('Instalando release notes: ' . count($aReleaseNotes));

    $sDiretorioReleaseNotes = ECIDADE_PATH . 'plugins/' . $oPlugin->getNome() . '/release_notes';

    // remove release notes ja instalados
    if (is_dir($sDiretorioReleaseNotes)) {
      $this->recursiveRemove($sDiretorioReleaseNotes);
    }

    $oDaoPluginItensMenu = new cl_db_pluginitensmenu();

    $aIndices = array();

    $lSalvarMenusPlugins = false;
    $aMenusPlugins = DBReleaseNoteModificacao::buscarMenusPlugins();

    foreach ($aReleaseNotes as $iIndiceReleaseNote => $oReleaseNote) {

      $iIdItemMenu = $oReleaseNote->menuId;

      if (!empty($oReleaseNote->menuId))  {

        if (!isset($aMenusPlugins[$oReleaseNote->menuId])) {
          $aMenusPlugins[$oReleaseNote->menuId] = array();
        }

        if (!in_array($oPlugin->getNome(), $aMenusPlugins[$oReleaseNote->menuId])) {
          $aMenusPlugins[$oReleaseNote->menuId][] = $oPlugin->getNome();
          $lSalvarMenusPlugins = true;
        }
      }

      // pegar id do item de menu pelo uid
      if (!empty($oReleaseNote->menuUid)) {

        $sSqlPluginItensMenu = $oDaoPluginItensMenu->sql_query_file(null, 'db146_db_itensmenu', null, 'db146_uid = \'' . $oReleaseNote->menuUid ."'");
        $rsPluginItensMenu = $oDaoPluginItensMenu->sql_record($sSqlPluginItensMenu);

        if (!$rsPluginItensMenu) {
          throw new BusinessException('Erro ao buscar os menus vinculados ao plugin na instalação dos release notes. UID: ' . $oReleaseNote->menuUid);
        }

        $iIdItemMenu = db_utils::fieldsMemory($rsPluginItensMenu, 0)->db146_db_itensmenu;
      }

      $aIndices[$iIndiceReleaseNote] = array();

      foreach ($oReleaseNote->files as $oFile) {

        $sVersao = $oFile->version;

        if (!isset($aIndices["$iIndiceReleaseNote"]["$sVersao"])) {
          $aIndices["$iIndiceReleaseNote"]["$sVersao"] = 0;
        } else {
          $aIndices["$iIndiceReleaseNote"]["$sVersao"]++;
        }

        $iIndice = $aIndices["$iIndiceReleaseNote"]["$sVersao"];

        $sCaminhoReleaseNoteOrigem = 'phar://' . $sDiretorioReleaseNotes . '.tar.gz/release_notes/' . $oFile->version . $oFile->name;
        $sCaminhoReleaseNoteDestino = $sDiretorioReleaseNotes . "/" . $oFile->version . '/' . $iIdItemMenu . '_' . str_pad( ( $iIndice + 1 ), 2, '0', STR_PAD_LEFT) . '.md';

        if (!file_exists($sCaminhoReleaseNoteOrigem)) {
          throw new Exception("Arquivo de origem do release notes não existe: " . $sCaminhoReleaseNoteOrigem );
        }

        if ( !is_dir(dirname($sCaminhoReleaseNoteDestino)) ) {
          mkdir(dirname($sCaminhoReleaseNoteDestino), 0775, true);
        }

        if (!copy($sCaminhoReleaseNoteOrigem, $sCaminhoReleaseNoteDestino)) {
          throw new Exception("Erro ao copiar arquivo '$sCaminhoReleaseNoteOrigem' para '$sCaminhoReleaseNoteDestino'.");
        }
      }

    }

    if ($lSalvarMenusPlugins) {
      DBReleaseNoteModificacao::salvarMenusPlugins($aMenusPlugins);
    }

    return true;
  }

  /**
   * @param Plugin $oPlugin
   * @return boolean
   */
  public function desinstalarReleaseNotes(Plugin $oPlugin) {

    // remove arquivos .md do releas notes
    $sCaminhoReleaseNotes = "plugins/" . $oPlugin->getNome() . "/release_notes/";
    if (is_dir($sCaminhoReleaseNotes))  {
      $this->recursiveRemove($sCaminhoReleaseNotes);
    }

    // remove metadados(release notes ja lidos)
    $sCaminhoDadoReleaseNote = "release_notes/plugins/{$oPlugin->getNome()}/";
    if (is_dir($sCaminhoDadoReleaseNote)) {
      $this->recursiveRemove($sCaminhoDadoReleaseNote);
    }

    // remove nome do plugin do arquivo com [id-menu][nome-plugin]
    DBReleaseNoteModificacao::removerPluginMenusPlugins($oPlugin);

    return true;
  }

  /**
   * @param Plugin $oPlugin
   * @param array $aHelps
   * @return boolean
   */
  public function instalarHelps(Plugin $oPlugin, $aHelps) {

    $logger = $this->getContainer()->get('logger');
    $logger->debug('Instalando helps: '. count($aHelps));

    $sDiretorioHelp = ECIDADE_PATH . 'plugins/' . $oPlugin->getNome() . '/helps';

    if (is_dir($sDiretorioHelp)) {
      $this->recursiveRemove($sDiretorioHelp);
    }

    $oDaoPluginItensMenu = new cl_db_pluginitensmenu();
    $aMenus = array();

    foreach ($aHelps as $oHelp) {

      $iIdItemMenu = $oHelp->menuId;

      // pegar id do item de menu pelo uid
      if (empty($oHelp->menuId) && !empty($oHelp->menuUid)) {

        $sSqlPluginItensMenu = $oDaoPluginItensMenu->sql_query_file(null, 'db146_db_itensmenu', null, 'db146_uid = \'' . $oHelp->menuUid ."'");
        $rsPluginItensMenu = $oDaoPluginItensMenu->sql_record($sSqlPluginItensMenu);

        if (!$rsPluginItensMenu) {
          throw new BusinessException('Erro ao buscar os menus vinculados ao plugin na instalação do Help. UID: ' . $oHelp->menuUid);
        }

        $iIdItemMenu = db_utils::fieldsMemory($rsPluginItensMenu, 0)->db146_db_itensmenu;
      }

      $sCaminhoOrigem = 'phar://' . $sDiretorioHelp . '.tar.gz/helps/' . $oHelp->file;
      $sCaminhoDestino = $sDiretorioHelp . "/" . $iIdItemMenu . '.json';

      if (!file_exists($sCaminhoOrigem)) {
        throw new Exception("Arquivo de origem do Help não existe: " . $sCaminhoOrigem);
      }

      if ( !is_dir(dirname($sCaminhoDestino)) ) {
        mkdir(dirname($sCaminhoDestino), 0775, true);
      }

      if (!copy($sCaminhoOrigem, $sCaminhoDestino)) {
        throw new Exception("Erro ao copiar arquivo '$sCaminhoOrigem' para '$sCaminhoDestino'.");
      }
    }

    return true;
  }

  private function getHelps(SimpleXMLElement $oManifest) {

    $aHelps = array();

    if (empty($oManifest->plugin->{'helps'})) {
      return $aHelps;
    }

    foreach ($oManifest->plugin->{'helps'}->{'help'} as $oHelp) {

      $aHelps[] = (object) array(
        'menuId' => (string) $oHelp['menu-id'],
        'menuUid' => (string) $oHelp['menu-uid'],
        'file' => (string) $oHelp['file'],
      );
    }

    return $aHelps;
  }

  /**
   * Retorna a instancia do plugin da requisio atual pelo item de menu acessado
   * @param  {Integer} $idItemMenu id do item de menu acessado
   * @return {Plugin|null}             Instancia de plugin caso exista
   */
  public static function getPluginAtual($idItemMenu = null) {

    $sChaveRegistry = 'PluginService::getPluginAtual::' . $idItemMenu;

    if ( DBRegistry::get($sChaveRegistry) ){
      return DBRegistry::get($sChaveRegistry);
    }

    if (empty($idItemMenu)) return null;

    $oDaoPluginMenu  = new cl_db_pluginitensmenu();
    $sqlPluginMenu = $oDaoPluginMenu->sql_query_file(null, '*', null, 'db146_db_itensmenu = ' . $idItemMenu);
    $rsPluginMenu = $oDaoPluginMenu->sql_record($sqlPluginMenu);

    if ( !$rsPluginMenu ) {
      return null;
    }

    $oPluginMenu = db_utils::fieldsMemory($rsPluginMenu, 0);
    $oPlugin = new Plugin($oPluginMenu->db146_db_plugin);

    DBRegistry::add($sChaveRegistry, $oPlugin);

    return $oPlugin;
  }

  /**
   * @param string $sNomePlugin
   * @return stdClass
   */
  public function getErrosModificacoes(Array $aModificacoes) {

    $aErros = array('error' => 0, 'warning' => 0);
    $oManager = new ModificationManager();

    foreach ($aModificacoes as $sCaminhoModificacao) {

      $parseModification = $oManager->parse($sCaminhoModificacao);
      $oModification = ModificationData::restore($parseModification->getId());

      foreach($oModification->getFilesErrors() as $file => $errors) {

        foreach($errors as $error) {

          if ($error['type'] === ModificationOperation::ERROR_ABORT) {
            $aErros['error']++;
          }
          if ($error['type'] === ModificationOperation::ERROR_SKIP) {
            $aErros['warning']++;
          }
        }
      }
    }

    return (object) $aErros;
  }

  /**
   * @param string $sNomePlugin
   * @return string
   */
  public static function getLogPath($sNomePlugin) {
    return sprintf("%stmp/%s.log", ECIDADE_PATH, $sNomePlugin);
  }

  /**
   * Cria container e registra arquivo de log em "tmp/$sNomePlugin"
   * @param String $sNomePlugin
   * @return \ECidade\V3\Extension\Container
   */
  public function createContainer($sNomePlugin) {

    if ($this->oContainer === null) {
      $this->oContainer = new Container();
      $this->oContainer->register('logger', new Logger($this->getLogPath($sNomePlugin), Logger::DEBUG));
    }

    return $this->oContainer;
  }

  /**
   * @return \ECidade\V3\Extension\Container
   */
  public function getContainer() {

    if ($this->oContainer === null) {
      throw new Exception("Container não iniciado.");
    }

    return $this->oContainer;
  }

  /**
   * busca arquivos de log do pluguin
   * @param string $sNomePlugin
   * @return array
   */
  private function getArquivosLog($sNomePlugin) {

    $aFiles = array(static::getLogPath($sNomePlugin));

    $logger = $this->getContainer()->get('logger');
    $logger->debug("Removendo logs de modificações");

    if (!file_exists("plugins/$sNomePlugin/fontes.tar.gz")) {

      $logger->debug("- arquivo não encontrado: plugins/$sNomePlugin/fontes.tar.gz");
      return $aFiles;
    }

    $aModificacoes = $this->getModificacoes($sNomePlugin);
    $oManager = new ModificationManager($this->getContainer());

    foreach ($aModificacoes as $sCaminhoModificacao) {

      if (!file_exists($sCaminhoModificacao)) {
        $logger->debug(" - arquivo não existe: " . $sCaminhoModificacao);
        continue;
      }

      $parseModification = $oManager->unpack($sCaminhoModificacao, true);
      $oModification = ModificationData::restore($parseModification->getId());
      $sArquivoLog = ECIDADE_MODIFICATION_LOG_PATH . $oModification->getId();

      if (!file_exists($sArquivoLog)) {
        $logger->debug(" - arquivo não existe: " . str_replace(ECIDADE_PATH, null, $sArquivoLog));
        continue;
      }

      $aFiles[] = $sArquivoLog;
    }

    return $aFiles;
  }

}
