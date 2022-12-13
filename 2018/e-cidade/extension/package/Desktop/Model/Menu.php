<?php
namespace ECidade\Package\Desktop\Model;

use \ECidade\V3\Extension\Model;
use \ECidade\V3\Extension\Request;

class Menu extends Model {

  private $sOrdenacaoMenu = null;

  public function getInstituicoes() {

    $sDataSistema = date('Y-m-d', !empty($_SESSION["DB_datausu"]) ? $_SESSION["DB_datausu"] : time());

    $sSqlInstituicoes  = " select c.codigo as id, c.nomeinst as nome, db21_tipoinstit as tipo_instit ";
    $sSqlInstituicoes .= "   from db_config c                                                        ";
    $sSqlInstituicoes .= "        inner join db_userinst u on u.id_instit = c.codigo                 ";
    $sSqlInstituicoes .= "  where u.id_usuario = " . $_SESSION["DB_id_usuario"];
    $sSqlInstituicoes .= "    and c.db21_ativo = 1                                                   ";
    $sSqlInstituicoes .= "   and (c.db21_datalimite is null or c.db21_datalimite > '$sDataSistema')  ";
    $sSqlInstituicoes .= "  order by c.prefeitura desc, id                                           ";

    if ($_SESSION["DB_id_usuario"] == "1" || $_SESSION['DB_administrador'] == "1") {

      $sSqlInstituicoes  = "select codigo as id, nomeinst as nome, db21_tipoinstit as tipo_instit                    ";
      $sSqlInstituicoes .= "  from db_config                                                                         ";
      $sSqlInstituicoes .= " where db21_ativo = 1 and (db21_datalimite is null or db21_datalimite < '$sDataSistema') ";
      $sSqlInstituicoes .= " order by prefeitura desc, id                                                            ";
    }

    $rsInstituicoes = $this->db->execute($sSqlInstituicoes);

    return $this->db->getCollectionByRecord($rsInstituicoes);
  }

  public function getAreas($iInstitId) {

    $iIdUsuario = $_SESSION['DB_id_usuario'];
    $iAno       = isset($_SESSION['DB_datausu']) ? date('Y', $_SESSION['DB_datausu']) : date('Y');

    $sSqlAreas  = "select distinct at26_sequencial as id, at25_descr as nome            ";
    $sSqlAreas .= "  from atendcadarea                                                  ";
    $sSqlAreas .= "       inner join atendcadareamod on at26_sequencial = at26_codarea  ";
    $sSqlAreas .= " where at26_id_item in (                                             ";
    $sSqlAreas .= "                                                                     ";
    $sSqlAreas .= "       select id_item from (                                                                                       ";
    $sSqlAreas .= "              select distinct i.id_modulo as id_item, m.descr_modulo, it.help, it.funcao, m.imagem, m.nome_modulo, ";
    $sSqlAreas .= "                              case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end ";
    $sSqlAreas .= "                from (                                                                   ";
    $sSqlAreas .= "                     select distinct i.itemativo, p.id_modulo, p.id_usuario, p.id_instit ";
    $sSqlAreas .= "                       from db_permissao p                                               ";
    $sSqlAreas .= "                            inner join db_itensmenu i on p.id_item = i.id_item           ";
    $sSqlAreas .= "                      where i.itemativo  = 1                                             ";
    $sSqlAreas .= "                        and p.id_usuario = $iIdUsuario                                   ";
    $sSqlAreas .= "                        and p.id_instit  = $iInstitId                                    ";
    $sSqlAreas .= "                        and p.anousu     = $iAno                                         ";
    $sSqlAreas .= "                     ) as i                                                              ";
    $sSqlAreas .= "                     inner join db_modulos m     on m.id_item  = i.id_modulo                                 ";
    $sSqlAreas .= "                     inner join db_itensmenu it  on it.id_item = i.id_modulo                                 ";
    $sSqlAreas .= "                     left outer join db_usumod u on u.id_item  = i.id_modulo and u.id_usuario = i.id_usuario ";
    $sSqlAreas .= "               where i.id_usuario = $iIdUsuario                                                              ";
    $sSqlAreas .= "                 and i.id_instit = $iInstitId                                                                ";
    $sSqlAreas .= "                 and libcliente is true                                                                      ";
    $sSqlAreas .= "                    ";
    $sSqlAreas .= "              union ";
    $sSqlAreas .= "                    ";
    $sSqlAreas .= "              select distinct i.id_modulo as id_item, m.descr_modulo, it.help, it.funcao, m.imagem, m.nome_modulo, ";
    $sSqlAreas .= "                              case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end ";
    $sSqlAreas .= "                from (                                                                                       ";
    $sSqlAreas .= "                     select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit                        ";
    $sSqlAreas .= "                       from db_permissao p                                                                   ";
    $sSqlAreas .= "                            inner join db_permherda h on h.id_perfil  = p.id_usuario                         ";
    $sSqlAreas .= "                            inner join db_usuarios u  on u.id_usuario = h.id_perfil and u.usuarioativo = '1' ";
    $sSqlAreas .= "                            inner join db_itensmenu i on p.id_item    = i.id_item                            ";
    $sSqlAreas .= "                      where i.itemativo  = 1                                                                 ";
    $sSqlAreas .= "                        and h.id_usuario = $iIdUsuario                                                       ";
    $sSqlAreas .= "                        and p.id_instit  = $iInstitId                                                        ";
    $sSqlAreas .= "                        and p.anousu     = $iAno                                                             ";
    $sSqlAreas .= "                     ) as i                                                                                  ";
    $sSqlAreas .= "                     inner join db_modulos    m on m.id_item = i.id_modulo                                      ";
    $sSqlAreas .= "                     inner join db_itensmenu it on it.id_item = i.id_modulo                                  ";
    $sSqlAreas .= "                     left outer join db_usumod u on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario  ";
    $sSqlAreas .= "               where i.id_usuario = $iIdUsuario                                                              ";
    $sSqlAreas .= "                 and libcliente is true";
    $sSqlAreas .= "                 and i.id_instit = $iInstitId";
    $sSqlAreas .= "                           )  as yyy ";

    /** DOES NOT MAKE ANY SENSE **/
    if (isset($area_de_acesso)) {
      $sSqlAreas .= "inner join atendcadareamod on yyy.id_item = at26_id_item";
      $sSqlAreas .= "where at26_codarea = $area_de_acesso";
    }

    $sSqlAreas .= "        order by nome_modulo ";
    $sSqlAreas .= "                        ) order by at25_descr ";

    if ( $_SESSION['DB_id_usuario'] == "1" || $_SESSION['DB_administrador'] == 1 ) {
      $sSqlAreas  = "select distinct at26_sequencial as id, at25_descr as nome         ";
      $sSqlAreas .= "  from atendcadarea                                               ";
      $sSqlAreas .= "       inner join atendcadareamod on at26_sequencial=at26_codarea ";
      $sSqlAreas .= " order by at25_descr                                              ";
    }

    $rsAreas = $this->db->execute($sSqlAreas);
    return $this->db->getCollectionByRecord($rsAreas);
  }

  public function getModulos($iInstitId, $iAreaId) {

    $iIdUsuario = $_SESSION['DB_id_usuario'];
    $iAno       = isset($_SESSION['DB_datausu']) ? date('Y', $_SESSION['DB_datausu']) : date('Y');


    $sSqlModulos = "";

    $sSqlModulos .= "select id_item as id, nome_modulo as nome from (                                                                                          ";
    $sSqlModulos .= "         select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo, ";
    $sSqlModulos .= "                case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end     ";
    $sSqlModulos .= "                from                                                                                     ";
    $sSqlModulos .= "                    (                                                                                    ";
    $sSqlModulos .= "                     select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit                    ";
    $sSqlModulos .= "                       from db_permissao p                                                               ";
    $sSqlModulos .= "                            inner join db_itensmenu i on p.id_item = i.id_item                              ";
    $sSqlModulos .= "                      where i.itemativo = 1                                                              ";
    $sSqlModulos .= "                        and p.id_usuario = $iIdUsuario                                                   ";
    $sSqlModulos .= "                        and p.id_instit = $iInstitId                                                     ";
    $sSqlModulos .= "                        and (p.anousu = $iAno or p.anousu = $iAno+1)                                     ";
    $sSqlModulos .= "                    ) as i                                                                               ";
    $sSqlModulos .= "                    inner join db_modulos m     on m.id_item    = i.id_modulo                            ";
    $sSqlModulos .= "                    inner join db_itensmenu it  on it.id_item   = i.id_modulo                            ";
    $sSqlModulos .= "                    left outer join db_usumod u on u.id_item    = i.id_modulo                            ";
    $sSqlModulos .= "                                               and u.id_usuario = i.id_usuario                           ";
    $sSqlModulos .= "              where i.id_usuario = $iIdUsuario                                                           ";
    $sSqlModulos .= "                and i.id_instit  = $iInstitId                                                            ";
    $sSqlModulos .= "                and libcliente is true                                                                   ";
    $sSqlModulos .= "         union                                                                                           ";
    $sSqlModulos .= "         select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo, ";
    $sSqlModulos .= "                case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end     ";
    $sSqlModulos .= "           from                                                                                          ";
    $sSqlModulos .= "               (                                                                                         ";
    $sSqlModulos .= "                select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit                         ";
    $sSqlModulos .= "                  from db_permissao p                                                                    ";
    $sSqlModulos .= "                       inner join db_permherda h on h.id_perfil  = p.id_usuario                          ";
    $sSqlModulos .= "                       inner join db_usuarios u  on u.id_usuario = h.id_perfil and u.usuarioativo = '1'  ";
    $sSqlModulos .= "                       inner join db_itensmenu i on p.id_item    = i.id_item                             ";
    $sSqlModulos .= "                 where i.itemativo = 1                                                                   ";
    $sSqlModulos .= "                   and h.id_usuario = $iIdUsuario                                                        ";
    $sSqlModulos .= "                   and p.id_instit = $iInstitId                                                          ";
    $sSqlModulos .= "                   and (p.anousu = $iAno or p.anousu = $iAno+1)                                          ";
    $sSqlModulos .= "               ) as i                                                                                    ";
    $sSqlModulos .= "               inner join db_modulos m     on m.id_item  = i.id_modulo                                   ";
    $sSqlModulos .= "               inner join db_itensmenu it  on it.id_item = i.id_modulo                                   ";
    $sSqlModulos .= "               left outer join db_usumod u on u.id_item  = i.id_modulo and u.id_usuario = i.id_usuario   ";
    $sSqlModulos .= "         where i.id_usuario = $iIdUsuario                                                                ";
    $sSqlModulos .= "           and libcliente is true                                                                        ";
    $sSqlModulos .= "           and i.id_instit = $iInstitId                                                                  ";
    $sSqlModulos .= "              )  as yyy                                                                                  ";
    $sSqlModulos .= "              inner join atendcadareamod on yyy.id_item = at26_id_item                                   ";
    $sSqlModulos .= "        where at26_codarea = $iAreaId                                                                    ";
    $sSqlModulos .= "        order by descr_modulo                                                                             ";

    if (  $_SESSION['DB_id_usuario'] == "1" || $_SESSION['DB_administrador'] == 1 ) {

      $sSqlModulos  = "select distinct  db_modulos.id_item as id,                                     ";
      $sSqlModulos .= "       db_modulos.descr_modulo as nome                                         ";
      $sSqlModulos .= "  from db_itensmenu                                                            ";
      $sSqlModulos .= "       inner join db_menu         on db_itensmenu.id_item = db_menu.id_item    ";
      $sSqlModulos .= "       inner join db_modulos      on db_itensmenu.id_item = db_modulos.id_item ";
      $sSqlModulos .= "       inner join atendcadareamod on db_modulos.id_item   = at26_id_item       ";
      $sSqlModulos .= "       inner join atendcadarea    on atendcadarea.at26_sequencial = atendcadareamod.at26_codarea ";
      $sSqlModulos .= " where libcliente is true and at26_codarea = $iAreaId                          ";
      $sSqlModulos .= " order by db_modulos.descr_modulo                                              ";

    }

    $rsModulos = $this->db->execute($sSqlModulos);

    return $this->db->getCollectionByRecord($rsModulos);
  }

  /**
   * @param integer $iInstitId
   * @param integer $iAreaId
   * @param integer $iModuloId
   * @param integer $iItemMenuId
   * @return array
   */
  public function getItensMenu($iInstitId, $iAreaId, $iModuloId, $iItemMenuId = null) {

    $iIdUsuario = $_SESSION['DB_id_usuario'];
    $iAno       = isset($_SESSION['DB_datausu']) ? date('Y', $_SESSION['DB_datausu']) : date('Y');

    if ( !$iItemMenuId ) {
        $iItemMenuId = $iModuloId;
    }

    $aItensMenu = array();

    $sCampos = "m.id_item as id_pai, m.id_item_filho as id, i.descricao as nome, i.funcao as action, menusequencia";    

    $sSqlItensMenu  = " select $sCampos";
    $sSqlItensMenu .= "  from db_menu m                                                         ";
    $sSqlItensMenu .= "       inner join db_permissao p on p.id_item = m.id_item_filho          ";
    $sSqlItensMenu .= "       inner join db_itensmenu i on i.id_item = m.id_item_filho          ";
    $sSqlItensMenu .= "                                  and p.permissaoativa = '1'             ";
    $sSqlItensMenu .= "                                  and p.anousu = $iAno                   ";
    $sSqlItensMenu .= "                                  and p.id_instit = $iInstitId           ";
    $sSqlItensMenu .= "                                  and p.id_modulo = $iModuloId           ";
    $sSqlItensMenu .= " where m.modulo    = $iModuloId                                          ";
    $sSqlItensMenu .= "   and i.itemativo = '1'                                                 ";
    $sSqlItensMenu .= "   and libcliente is true                                                ";
    $sSqlItensMenu .= "   and m.id_item = $iItemMenuId                                          ";
    $sSqlItensMenu .= "   and p.id_usuario = $iIdUsuario                                        ";
    $sSqlItensMenu .= " union                                                                   ";
    $sSqlItensMenu .= "  select $sCampos                                                        ";
    $sSqlItensMenu .= "    from db_menu m                                                       ";
    $sSqlItensMenu .= "         inner join db_permherda h on h.id_usuario     = $iIdUsuario     ";
    $sSqlItensMenu .= "         inner join db_usuarios  u on u.id_usuario     = h.id_perfil     ";
    $sSqlItensMenu .= "                                  and u.usuarioativo   = '1'             ";
    $sSqlItensMenu .= "         inner join db_permissao p on p.id_item        = m.id_item_filho ";
    $sSqlItensMenu .= "         inner join db_itensmenu i on i.id_item        = m.id_item_filho ";
    $sSqlItensMenu .= "                                  and p.permissaoativa = '1'             ";
    $sSqlItensMenu .= "                                  and p.anousu         = $iAno           ";
    $sSqlItensMenu .= "                                  and p.id_instit      = $iInstitId      ";
    $sSqlItensMenu .= " where m.modulo    = $iModuloId                                          ";
    $sSqlItensMenu .= "   and p.id_modulo = $iModuloId                                          ";
    $sSqlItensMenu .= "   and i.itemativo = '1'                                                 ";
    $sSqlItensMenu .= "   and libcliente is true                                                ";
    $sSqlItensMenu .= "   and m.id_item = $iItemMenuId                                          ";
    $sSqlItensMenu .= "   and p.id_usuario = h.id_perfil                                        ";
        
    if ($iIdUsuario == 1 || $_SESSION['DB_administrador'] == 1 ) {

      $sSqlItensMenu  = " select $sCampos";
      $sSqlItensMenu .= "  from db_menu m                                                ";
      $sSqlItensMenu .= "       inner join db_itensmenu i on i.id_item = m.id_item_filho ";
      $sSqlItensMenu .= " where m.modulo    = $iModuloId                                 ";
      $sSqlItensMenu .= "   and i.itemativo = '1'                                        ";
      $sSqlItensMenu .= "   and libcliente is true                                       ";
      $sSqlItensMenu .= "   and m.id_item = $iItemMenuId                                 ";
    }

    if ( !$this->sOrdenacaoMenu ) {

      require_once(modification(ECIDADE_PATH . 'model/configuracao/PreferenciaUsuario.model.php'));
      $oPreferencias = unserialize(base64_decode($_SESSION['DB_preferencias_usuario']));
      $this->sOrdenacaoMenu = ( $oPreferencias->getOrdenacao() == "alfabetico" ? "nome" : "menusequencia" );
    }

    $sSqlItensMenu .= "order by " . $this->sOrdenacaoMenu;
    $rsItensMenu = $this->db->execute($sSqlItensMenu);

    if (!$rsItensMenu || pg_num_rows($rsItensMenu) == 0) {
      return false;
    }

    $aItensMenu = $this->db->getCollectionByRecord($rsItensMenu);
    foreach ($aItensMenu as &$aItemMenu) {

        $aItemMenu['nome'] = $aItemMenu['nome'];
        $aItemMenu['filhos'] = $this->getItensMenu($iInstitId, $iAreaId, $iModuloId, $aItemMenu['id']);
    }

    return $aItensMenu;
  }

  /**
   * @param integer $instit
   * @param array
   */
  public function buildMenu($instit) {

    require_once(modification(ECIDADE_PATH . 'std/DBCache.php'));

    if (empty($_SESSION['DB_NBASE'])) {
      //\Debug::log('EMPTY DB_NBASE', $_SERVER['REQUEST_URI'], session_name(), $_SESSION);
    }

    $usuario = $_SESSION['DB_id_usuario'];
    $base = !empty($_SESSION['DB_NBASE']) ? $_SESSION['DB_NBASE'] : $_SESSION['DB_base'];
    $ano = isset($_SESSION['DB_datausu']) ? date('Y', $_SESSION['DB_datausu']) : date('Y');
    $cachePath = "menus/{$ano}_{$instit}_{$usuario}_{$base}_ecidade3";
    $menus = \DBCache::read($cachePath);

    if (!empty($menus)) {
      return $menus;
    }

    $menus = array();

    foreach ($this->getAreas($instit) as $area) {

      foreach ($this->getModulos($instit, $area['id']) as $modulo) {

        $data = array(
          'breadcrumb' => $modulo['nome'],
          'area' => $area['id'],
          'modulo' => $modulo['id'],
          'action' => null,
        );

        foreach($this->getItensMenu($instit, $area['id'], $modulo['id']) as $menu) {
          $this->buildMenuChildren(array($menu), $data, $menus);
        }
      }

    }

    \DBCache::write($cachePath, $menus);
    return $menus;
  }

  /**
   * @param array $data
   * @param array $menu
   * @return array
   */
  public function buildMenuChildren(array $data, $menu, &$menus) {

    foreach ($data as $item) {

      $children = $menu;
      $children['breadcrumb'] .= ' > ' . $item['nome'];

      if (!empty($item['action'])) {

        $children['action'] = $item['action'];
        $children['id'] = $item['id'];
        $menus[] = $children;
      }

      if (!empty($item['filhos'])) {
        $this->buildMenuChildren($item['filhos'], $children, $menus);
      }
    }

    return $menu;
  }

  /**
   * @param \ECidade\Extension\Request $request
   * @param string $path
   * @return integer | boolean
   */
  public function getMenuArquivo(Request $request, $path) {

    $file = trim(addslashes(basename($path)));
    $where = null;

    if ($request->session()->has("DB_modulo")){
      $where =  "and modulo = ".$request->session()->get("DB_modulo");
    }

    $sql = "select db_itensmenu.id_item
                    from db_itensmenu
              inner join db_menu  on  db_menu.id_item_filho = db_itensmenu.id_item
                    where trim(funcao) = '".$file."'
              $where limit 1 ";

    $result = $this->db->execute($sql);

    if (!$result || pg_num_rows($result) == 0) {
      return false;
    }

    return $this->db->fetchRow($result, 0)->id_item;
  }

  /**
   * @param integer $id
   * @return string
   */
  public function getBreadcrumbMenu($id) {

    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
    return \DBMenu::getBreadcrumb($id);
  }

  /**
   * @param integer $ano
   * @param integer $modulo
   * @param integer $item
   * @return boolean
   */
  public function getPermissaoMenu($ano, $modulo, $item) {

    require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));
    return db_permissaomenu($ano, $modulo, $item) == 'true';
  }

  /**
   * @param integer $ano
   * @retuern boolean
   */
  public function getPermissaoAlterarData($ano) {
    return $this->getPermissaoMenu($ano, 1, 3896);
  }

}
