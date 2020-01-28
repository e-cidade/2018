<?php
namespace ECidade\Package\Desktop\Controller;

use \ECidade\V3\Extension\Controller;
use \ECidade\V3\Extension\Exceptions\ResponseException;
use \ECidade\V3\Extension\Registry;
use \ECidade\V3\Window\Session;
use \ECidade\Package\Desktop\Model\Window as ModelWindow;
use \ECidade\Package\Desktop\Model\Menu as ModelMenu;
use \Exception;

class Window extends Controller {

  public function index() {

    $get = $this->request->get();
    $session = $this->request->session();

    if ($get->has('iInstitId')) {
      $session->set('DB_instit', $get->get('iInstitId'));
    }

    if ($get->has('iAreaId')) {
      $session->set('DB_Area', $get->get('iAreaId'));
    }

    if ($get->has('iModuloId')) {
      $session->set('DB_modulo', $get->get('iModuloId'));
    }

    $session->set('DB_uol_hora', time());

    if (!$session->has('DB_datausu')) {
      $session->set('DB_datausu', $session->get('DB_uol_hora'));
    }

    if (!$session->has('DB_anousu')) {
      $session->set('DB_anousu', date("Y", $session->get('DB_uol_hora')));
    }

    if ($get->has('iModuloId')) {

      $session->set('DB_modulo', $get->get('iModuloId'));
      $session->set('DB_nome_modulo', $get->get('iModuloId'));
    }

    if (!$session->has('DB_instit')) {
      throw new \Exception('Instituição não definida.');
    }

    $model = new ModelWindow();
    $menuModel = new ModelMenu();

    $departamentos = $model->getDepartamentos(
      $session->get('DB_id_usuario'), $session->get('DB_instit'), date("Y-m-d", $session->get('DB_datausu')), 1
    );

    if (empty($departamentos)) {
      throw new ResponseException("Usuário sem departamento para acesso cadastrado!");
    }

    $codigoDepartamento = $departamentos[0]['coddepto'];
    $nomeDepartamento = $codigoDepartamento . ' - ' . $departamentos[0]['descrdepto'];

    if ($session->has('DB_coddepto')) {

      $departamento = $model->getDepartamento(
        $session->get('DB_coddepto'),
        $session->get('DB_id_usuario'),
        $session->get('DB_instit'),
        date("Y-m-d", $session->get('DB_datausu'))
      );

      if ($departamento) {
        $codigoDepartamento = $departamento->coddepto;
        $nomeDepartamento = $codigoDepartamento . ' - ' . $departamento->descrdepto;
      }
    }

    $session->set('DB_coddepto', $codigoDepartamento);
    $session->set('DB_nomedepto', $nomeDepartamento);

    $this->view->dateUser = false;
    $dateUser = $model->getDataUsuario($session->get("DB_id_usuario"));

    if ($dateUser && $dateUser != date("Y-m-d", $session->get("DB_datausu")) && $menuModel->getPermissaoAlterarData($session->get("DB_anousu"))) {
      $this->view->dateUser = true;
    }

    $this->view->dateSystemDiffServer = false;
    if (date('Y-m-d') != date('Y-m-d', $session->get('DB_datausu')) || date('Y') != $session->get('DB_anousu')) {
      $this->view->dateSystemDiffServer = true;
    }

    $window = Registry::get('app.window');

    $this->view->window = $window;
    $this->view->pathBody = ECIDADE_REQUEST_PATH . 'w/'. $window->id() .'/'. $get->get('action');
    $this->view->pathStatus = ECIDADE_REQUEST_PATH . 'w/'. $window->id() .'/extension/desktop/window/bottom';

    if ($session->get('DB_DEBUG', false)) {
      return $this->render();
    }

    // valida se usuario tem permissao para acessar rotina pelo anousu

    $idMenu = $menuModel->getMenuArquivo($this->request, $get->get('action'));

    // 404?
    if (empty($idMenu)) {
      throw new ResponseException('Permissão para rotina não encontrada.');
    }

    $permissao = $menuModel->getPermissaoMenu(
      $this->request->session()->get('DB_anousu'), $this->request->session()->get('DB_modulo'), $idMenu
    );

    if (!$permissao) {
      throw new ResponseException('Sem permissão para acessar está rotina.');
    }

    $this->render();
  }

  public function bottom() {

    $model = new ModelWindow();

    $this->view->data = date('d/m/Y', $this->request->session()->get('DB_datausu'));
    $this->view->exercicio = $this->request->session()->get('DB_anousu');
    $this->view->departamento = $this->request->session()->get('DB_nomedepto');

    $instituicao = $this->request->session()->get('DB_instit');
    $this->view->instituicao = $instituicao . ' - ' . $model->getNomeInstituicao($instituicao);

    $this->render();
  }

  public function setting() {

    $post = $this->request->post();

    // Atualiza sessao da janela atual
    if ($post->has('departamentos')) {

      if ($post->has('exercicios')) {
        $this->request->session()->set('DB_anousu', $post->get('exercicios'));
      }

      $model = new ModelWindow();

      if ($post->has('data')) {

        $data = implode('-', array_reverse(explode('/', $post->get('data'))));

        if (strtotime($data) > time()) {
          throw new \Exception('Data do Sistema não pode ser maior que data do servidor.');
        }

        $this->request->session()->set('DB_datausu', strtotime($data));
        $model->salvarDataUsuario($this->request->session()->get('DB_id_usuario'), $data);
      }

      $this->request->session()->set('DB_coddepto', $post->get('departamentos'));
      $this->request->session()->set(
        'DB_nomedepto',
        $post->get('departamentos') . ' - ' . $model->getNomeDepartamento($post->get('departamentos'))
      );

      // atualiza sesao global, usada como base para criar as proximas janelas
      Session::update(Session::MAIN_NAME, array(
        'DB_datausu' => $this->request->session()->get('DB_datausu'),
        'DB_anousu' => $this->request->session()->get('DB_anousu'),
        'DB_coddepto' => $this->request->session()->get('DB_coddepto'),
        'DB_nomedepto' => $this->request->session()->get('DB_nomedepto'),
      ));
      return true;
    }

    $model = new ModelWindow();
    $this->view->departamento = $this->request->session()->get('DB_coddepto');
    $this->view->departamentos = $model->getDepartamentos(
      $this->request->session()->get('DB_id_usuario'),
      $this->request->session()->get('DB_instit'),
      date("Y-m-d", $this->request->session()->get('DB_datausu'))
    );

    $this->view->exercicio = $this->request->session()->get('DB_anousu');
    $this->view->exercicios = $model->getExercicios($this->request->session()->get('DB_id_usuario'));
    $isDBSeller = $this->request->session()->get('DB_id_usuario') == 1;

    // Usuario dbseller - ano do servidor nao cadastrado na tabela db_permissao
    if ($isDBSeller && !in_array(date('Y'), $this->view->exercicios)) {

      $this->view->exercicios[] = date('Y');
      rsort($this->view->exercicios);
    }

    $menuModel = new ModelMenu();

    if ($menuModel->getPermissaoAlterarData($this->request->session()->get("DB_anousu"))) {

      $dataUsuario = strtotime($model->getDataUsuario($this->request->session()->get('DB_id_usuario')));
      $this->view->dataServidor = date('d/m/Y');
      $this->view->dataSistema = $dataUsuario ? date('d/m/Y', $dataUsuario) : date('d/m/Y');
      $this->view->dataSistemaDia = date('d', $dataUsuario ?: time());
      $this->view->dataSistemaMes = date('m', $dataUsuario ?: time());
      $this->view->dataSistemaAno = date('Y', $dataUsuario ?: time());

      $model->excluirDataUsuario($this->request->session()->get('DB_id_usuario'));
    }

    $window = \ECidade\V3\Extension\Registry::get('app.window');
    $this->view->document->setBase(ECIDADE_REQUEST_PATH . 'w/' . $window->id());

    $this->render();
  }

  public function block() {

    $this->request->session()->set('blocked', true);
    $this->render();
  }

  public function unblock() {

    if (!$this->request->session()->has('DB_login')) {
      return false;
    }

    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
    require_once(modification(ECIDADE_PATH . 'libs/db_conecta.php'));

    $oUsuarioSistema = new \UsuarioSistema($this->request->session()->get('DB_id_usuario'));

    $lConfere = \Encriptacao::hash($this->request->post()->get('senha')) === $oUsuarioSistema->getSenha();

    if (!$lConfere) {
      throw new ResponseException("Senha inválida.");
    }

    $this->request->session()->set('blocked', false);
    return $lConfere;
  }

  public function ping() {

    $result = 'alive';

    if ($this->request->session()->has('blocked') && $this->request->session()->blocked === true) {
      $result = 'blocked';
    }

    if (!$this->request->session()->has('DB_id_usuario')) {
      $result = 'dead';
    }

    return $result;
  }

  public function logout() {

    $this->request->session()->destroy();
    Session::destroyAll();

    return array('session' => 'dead');
  }

}
