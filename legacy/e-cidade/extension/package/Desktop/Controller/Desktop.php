<?php

namespace ECidade\Package\Desktop\Controller;

use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Window\Session;
use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Datasource\Database;
use \ECidade\V3\Modification\Data\Modification as ModificationDataModification;
use \ECidade\V3\Extension\Manager as ExtensionManager;

class Desktop extends Controller {

  public function beforeAction() {

    if (!$this->request->session()->has('DB_login')) {
      $this->response->redirect(ECIDADE_REQUEST_PATH . 'login.php');
    }
  }

  public function index() {

    // limpa dados da dessao global
    $this->request->session()->remove('DB_coddepto');
    $this->request->session()->remove('DB_nomedepto');
    $this->request->session()->remove('DB_anousu');
    $this->request->session()->remove('DB_datausu');

    // Destroe sessoes ja criadas
    Session::destroyAll();

    $this->view->document->setTitle('DBSeller Informática Ltda - e-cidade - 3.0');
    $this->view->document->setCharset($this->response->getCharset());

    $styles = array(
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/topbar.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/desktop.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/taskbar.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/menu.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/menu-list-icons.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/css/fm.scrollator.jquery.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/window/default.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/window/ecidade.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/alertify/themes/alertify.core.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/alertify/themes/alertify.bootstrap.css",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/jquery.menu-search/jquery.menu-search.css",
    );

    $scripts = array(
      ECIDADE_REQUEST_PATH . "scripts/jquery-2.1.1.min.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/fm.scrollator.jquery.min.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/jquery.ba-outside-events.min.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/jquery.dropdown.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/alertify/alertify.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/jquery.menu-search/jquery.menu-search.js",
      ECIDADE_REQUEST_PATH . "scripts/prototype.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/vendors/window/window.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/desktop.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/menu.js",
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . "assets/js/bootstrap.js",
    );

    foreach ($styles as $href) {
      $this->view->document->addLink($href, array('type' => 'text/css', 'rel' => 'stylesheet', 'media' => 'screen'));
    }

    // favicon
    $this->view->document->addLink(
      ECIDADE_CURRENT_EXTENSION_REQUEST_PATH . 'assets/img/favicon.png', array('type' => 'image/png', 'rel' => 'icon')
    );

    foreach ($scripts as $src) {
      $this->view->document->addScript($src, array('type' => 'text/javascript'));
    }

    // dispara o evento do desktop
    Registry::get('app.eventManager')->trigger('extension.desktop.bootstrap', $this, array($this));

    /**
     * Conecta no banco
     */
    Database::init();

    $usuarioSistema = $this->getUsuarioSistema();
    $this->view->usuarioSistema = $usuarioSistema;

    $this->view->caminhoFoto = "imagens/none1.jpeg";

    try {

      $oidFoto = $usuarioSistema->getCGM()->getFotoPrincipal();

      if ($oidFoto) {
        $this->view->caminhoFoto = $this->getCaminhoFotoUsuario($oidFoto);
      }

    } catch (\Exception $error) {
      $this->view->caminhoFoto = "imagens/none1.jpeg";
    }

    try {
      $modificationDesktopData = ModificationDataModification::restore('dbportal-v3-desktop');

      $isDBSeller = $this->request->session()->get('DB_login') == 'dbseller';
      $isDBug = $this->request->session()->get('DB_DEBUG', false);

      $this->view->showFallbackButton = ($modificationDesktopData->isUserType() && !$isDBSeller) || $isDBug;
    } catch (\Exception $error) {
      $this->view->showFallbackButton = false;
    }

    $this->view->version = $this->version();

    $this->render();
  }

  /**
   * @todo - validation? whitelist?
   */
  public function session() {
    foreach ($this->request->post() as $name => $value) {
      $this->request->session()->set($name, utf8_decode($value));
    }
  }

  /**
   * @return string
   */
  public function version() {

    Database::init();

    if (file_exists(ECIDADE_PATH . 'libs/db_acessa.php')) {

      require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
      require(modification(ECIDADE_PATH . 'libs/db_acessa.php'));
    }

    return '2.' . $db_fonte_codversao . '.' . $db_fonte_codrelease;
  }

  /**
   * @return \UsuarioSistema
   */
  private function getUsuarioSistema() {

    require_once(modification(ECIDADE_PATH . 'libs/db_autoload.php'));
    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
    $usuarioSistema = new \UsuarioSistema($this->request->session()->get('DB_id_usuario'));

    return $usuarioSistema;
  }

  /**
   * @param integer
   * @return string
   */
  private function getCaminhoFotoUsuario($oidFoto) {

    $sCaminhoFoto = 'tmp/' . $oidFoto . ".jpg";
    \db_query('begin');
    pg_lo_export($oidFoto, $sCaminhoFoto);
    \db_query('commit');
    return $sCaminhoFoto;
  }

  /**
   * @return string
   */
  public function fallback() {

    $login = $this->request->session()->get('DB_login');

    if (empty($login)) {
      throw new \Exception('Usuário não informado para retornar versão.');
    }

    $manager = new ExtensionManager();
    $manager->uninstall('Desktop', $login);

    return $login;
  }

  /**
   * @param integer $instit
   * @return array
   */
  public function getBases($instit) {

    $_SESSION['DB_instit'] = $instit;

    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
    Database::init();

    $ano = $this->request->session()->get("DB_anousu", date("Y"));
    $acesso = db_permissaomenu($ano, 1, 5333) == 'true';

    $result = db_query("select datname from pg_database where substr(datname,1,6) != 'templa' order by datname");

    if (!$result) {
      return array();
    }

    $total = pg_num_rows($result);
    $bases = array();
    for ($index = 0; $index < $total; $index++) {
      $bases[] = pg_fetch_result($result, $index, 'datname');
    }

    return (object) array(
      'atual' => $this->request->session()->get('DB_NBASE'),
      'acesso' => $acesso,
      'bases' => $bases
    );
  }

}
