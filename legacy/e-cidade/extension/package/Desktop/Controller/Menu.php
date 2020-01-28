<?php
namespace ECidade\Package\Desktop\Controller;

use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Extension\Exceptions\ResponseException;
use \ECidade\Package\Desktop\Model\Menu as ModelMenu;
use \Exception;

class Menu extends Controller {

  public function beforeAction() {
    if (!$this->request->session()->has('DB_login')) {
      throw new ResponseException("Sessão não inicializada.");
    }
  }

  /**
   * @return array
   */
  public function getInstituicoes() {

    $oMenu = new ModelMenu();
    $aInstituicoes = \DBString::utf8_encode_all($oMenu->getInstituicoes());
    return $aInstituicoes;
  }

  /**
   * @return array
   */
  public function getAreas() {

    $iInstitId = $this->request->get()->get('iInstitId');
    $oMenu = new ModelMenu();
    $aAreas = \DBString::utf8_encode_all($oMenu->getAreas($iInstitId));

    return $aAreas;
  }

  /**
   * @return array
   */
  public function getModulos() {

    $iInstitId = $this->request->get()->get('iInstitId');
    $iAreaId = $this->request->get()->get('iAreaId');

    $oMenu = new ModelMenu();
    $aModulos = \DBString::utf8_encode_all($oMenu->getModulos($iInstitId, $iAreaId));

    return $aModulos;
  }

  /**
   * @return array
   */
  public function getItensMenu() {

    $iInstitId = $this->request->get()->get('iInstitId');
    $iAreaId = $this->request->get()->get('iAreaId');
    $iModuloId = $this->request->get()->get('iModuloId');

    $oMenu = new ModelMenu();
    $aItensMenu = \DBString::utf8_encode_all($oMenu->getItensMenu($iInstitId, $iAreaId, $iModuloId));

    return $aItensMenu;
  }

  /**
   * @param string $needle
   * @param integer $instit
   * @return arrray
   */
  public function search($needle = null, $instit = null) {

    ignore_user_abort(false);

    if (empty($instit)) {
      throw new ResponseException('Instituição não informada.');
    }

    $menus = $this->getEstruturaMenu($instit);
    $needle = urldecode(utf8_decode($needle));

    $search = new \Search\Match($menus);
    $search->haystackKey('breadcrumb');
    $search->highlight('<b>', '</b>');
    $search->limit(20);
    $search->threshold(1);
    $matches = array();

    foreach($search->execute($needle) as $match) {
      $matches[] = array(
        'highlight' => $match->highlight(),
        'score' => $match->score(),
        'context' => $match->context(),
      );
    }

    return $matches;
  }

  /**
   * @param $instit
   * @return array
   */
  public function getEstruturaMenu($instit = null) {

    if (empty($instit)) {
      throw new ResponseException('Instituição não informada.');
    }

    $this->request->session()->close();

    $menu = new ModelMenu();
    return \DBString::utf8_encode_all($menu->buildMenu($instit));
  }

  /**
   * @param string $file
   * @return Stdclass
   */
  public function getMenuArquivo($file = null) {

    if (empty($file)) {
      throw new ResponseException('Arquivo não informado.');
    }

    $data = (object) array('id' => null, 'breadcrumb' => null, 'permission' => true);
    $model = new ModelMenu();

    $file = $this->request->params()->get('file', $file);
    $data->id = $model->getMenuArquivo($this->request, $file);

    if (empty($data->id)) {
      return $data;
    }
    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));

    $data->breadcrumb = $model->getBreadcrumbMenu($data->id);
    $data->permission = $model->getPermissaoMenu(
      $this->request->session()->get('DB_anousu'), $this->request->session()->get('DB_modulo'), $data->id
    );

    return \DBString::utf8_encode_all($data);
  }

}
