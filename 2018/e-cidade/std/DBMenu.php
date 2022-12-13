<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

/**
 * Classe que realiza o
 */
class DBMenu {

  /**
   * Módulo atual a ser gerado o menu
   * @var integer
   */
  private $iModulo;

  /**
   * Id do usuário para gerar as permissões
   * @var integer
   */
  private $iIdUsuario;

  /**
   * Ano atual da geração
   * @var integer
   */
  private $iAnoUsu;

  /**
   * Instituição a ser gerado o menu
   * @var integer
   */
  private $iInstituicao;

  /**
   * Ano DataUsu
   * @var String
   */
  private $iDataUsu;

  /**
   * Campo pelo qual o menu deve ser ordenado
   * @var String
   */
  private $sOrdenacao = 'menusequencia';

  /**
   * DAO db_menu
   */
  private $oDaoMenu = null;

  /**
   * Estrutura do ultimo menu gerado
   * @var String
   */
  private $sHtmlMenu = "";

  /**
   * Caso o usuário seja administrador
   * @var Boolean
   */
  private $lAdministrador = false;

  /**
   * Vai sabe pra que é usado isso
   * @var Boolean
   */
  private $lDBSeller = false;

  /**
   * Fonte onde o menu será renderizado
   * @var string
   */
  private $sFuncao;

  /**
   * Id da função para o Helper
   * @var integer
   */
  private $iIdFuncaoHelp = '';

  /**
   * Descrição da função para o Helper
   * @var integer
   */
  private $sDescricaoHelp = '';

  /**
   * Descrição do item de menu atual
   * @var String
   */
  private $sDescricaoFuncao = '';

  /**
   * Se deve exibir a busca rápida de menus
   * @var Boolean
   */
  private $lExibeBuscaMenus = false;

  /**
   * Mantém o cache para busca dos menus
   * @var array
   */
  private $aCacheMenus = array();

  function __construct($iModulo, $iIdUsuario, $iAnoUsu, $iInstituicao) {

    $this->iModulo      = $iModulo;
    $this->iIdUsuario   = $iIdUsuario;
    $this->iAnoUsu      = $iAnoUsu;
    $this->iInstituicao = $iInstituicao;

    $this->iDataUsu = date("Y");
    $this->oDaoMenu = db_utils::getDao("db_menu", true);
  }

  /**
   * Retorna a estrutura do ultimo menu gerado
   * @return String
   */
  public function getHtmlMenu() {
    return $this->sHtmlMenu;
  }

  /**
   * @param String $sOrdenacao
   */
  public function setOrdenacao($sOrdenacao) {
    $this->sOrdenacao = $sOrdenacao;
  }

  /**
   * @param Boolean $lAdministrador
   */
  public function setAdministrador($lAdministrador) {
    $this->lAdministrador = $lAdministrador;
  }

  /**
   * @param Boolean $lDBSeller
   */
  public function setDBSeller($lDBSeller) {
    $this->lDBSeller = $lDBSeller;
  }

  /**
   * @param integer $sDataUsu
   */
  public function setDataUsu($iDataUsu) {
    $this->iDataUsu = $iDataUsu;
  }

  /**
   * @param Boolean $lExibeBuscaMenus
   */
  public function setExibeBuscaMenus($lExibeBuscaMenus) {
    $this->lExibeBuscaMenus = $lExibeBuscaMenus;
  }

  /**
   * @param Boolean $sFuncao
   */
  public function setFuncao($sFuncao){
    $this->sFuncao = $sFuncao;
  }

  public function getDescricaoFuncao() {
    return $this->sDescricaoFuncao;
  }

  /**
   * Remove os arquivos de cache do usuario
   * @param  Integer  $iIdUsuario
   * @param  Integer  $iModulo
   * @return null
   */
  public static function limpaCache($iIdUsuario = '', $iInstituicao = '', $iModulo = '', $iAno = '') {

    $iIdUsuario   = (empty($iIdUsuario)   ? '(\d+)' : $iIdUsuario   );
    $iInstituicao = (empty($iInstituicao) ? '(\d+)' : $iInstituicao );
    $iModulo      = (empty($iModulo)      ? '(\d+)' : $iModulo      );
    $iAno         = (empty($iAno)         ? '(\d+)' : $iAno         );

    $sPattern = "/{$iAno}_{$iModulo}_{$iInstituicao}_{$iIdUsuario}_(\w+|\W+)/";

    DBCache::remove( DBCache::scanCache("menus/", $sPattern) );
  }

  /**
   * Busca os itens de menu a partir do conteudo informado.
   *
   * @param  String $sConteudo Conteudo da busca.
   * @return Array  $aRetorno  Resultado da busca
   */
  public function buscaMenu($sConteudo) {

    $aMenus = DBCache::read("menus/{$this->iAnoUsu}_{$this->iModulo}_{$this->iInstituicao}_{$this->iIdUsuario}_pesquisa");

    if ($aMenus === false) {
      return array();
    }

    $aRetorno = array();

    foreach ($aMenus as $oMenu) {

      if (stripos($oMenu->caminho, $sConteudo) !== false) {

        $oItem = new stdClass();
        $oItem->label = utf8_encode($oMenu->caminho);
        $oItem->cod   = utf8_encode($oMenu->funcao);
        $aRetorno[]   = $oItem;
      }
    }

    return $aRetorno;
  }

  /**
   * Gera o menu
   * @return String
   */
  public function montaMenu() {

    $oUsuarioSistema     = new UsuarioSistema( $this->iIdUsuario );
    $oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario();

    /**
     * Verifica se os menus estão em cache e se deve utilizar o cache
     */
    if ( $oPreferenciaUsuario->getHabilitaCacheMenu()
         && ($sHtmlCache = DBCache::read("menus/{$this->iAnoUsu}_{$this->iModulo}_{$this->iInstituicao}_{$this->iIdUsuario}_estrutura")) ) {

      return base64_decode($sHtmlCache);
    }

    $this->sHtmlMenu   = "";
    $this->aCacheMenus = array();

    $rsMenusPrincipais = $this->getMenuItens($this->iModulo);

    if ($this->oDaoMenu->numrows > 0) {

      $iTotalMenus      = $this->oDaoMenu->numrows;
      $this->sHtmlMenu .= "<div id=\"db-menu\">\n";

      for ($iMenu = 0; $iMenu < $iTotalMenus; $iMenu++) {

        $oMenu    = db_utils::fieldsMemory($rsMenusPrincipais, $iMenu);
        $rsFilhos = $this->getMenuItens($oMenu->id_item_filho);

        $this->sHtmlMenu .= "<ul>\n";
        $this->sHtmlMenu .= "<li class=\"sub-menu\" onclick=\"Menu_toggle(this, event)\" onmouseover=\"Menu_parentOver(this, event)\">\n";
        $this->sHtmlMenu .= "<a href=\"javascript:;\">{$oMenu->descricao}</a>\n";

        $this->montaFilhos($rsFilhos, $oMenu->descricao);

        $this->sHtmlMenu .= "</li>\n";
        $this->sHtmlMenu .= "</ul>\n";
      }

      $this->montaModulos();
      $this->montaHelp();

      /**
       * Exibe a busca de menus
       */
      if ($this->lExibeBuscaMenus) {
        $this->sHtmlMenu .= "<div id=\"autoComplete\">";
        $this->sHtmlMenu .= "<label>Busca rápida de menus: </label>";
        $this->sHtmlMenu .= "<input type=\"text\" id=\"autoCompleteMenus\" name=\"autoCompleteMenus\" autoComplete=\"off\" />";
        $this->sHtmlMenu .= "</div>";
      }

      $this->sHtmlMenu .= "</div>";
    }

    /**
     * Grava os arquivo de cache do menu
     * Somente salva o arquivo da estrutura dos menus se for utilizar o cache
     */
    if ($oPreferenciaUsuario->getHabilitaCacheMenu()) {
      DBCache::write("menus/{$this->iAnoUsu}_{$this->iModulo}_{$this->iInstituicao}_{$this->iIdUsuario}_estrutura", base64_encode($this->sHtmlMenu));
    }

    DBCache::write("menus/{$this->iAnoUsu}_{$this->iModulo}_{$this->iInstituicao}_{$this->iIdUsuario}_pesquisa", $this->aCacheMenus);

    return $this->sHtmlMenu;
  }

  /**
   * Monta a estrutura de menu dos submenus
   * @param  resource $rsFilhos
   * @param  string $sCaminho Caminho para ser exibido na busca de menus
   * @return null
   */
  private function montaFilhos($rsFilhos, $sCaminho = '') {

    if ($this->oDaoMenu->numrows == 0) {
      return;
    }

    $iTotalFilhos     = $this->oDaoMenu->numrows;
    $this->sHtmlMenu .= "<ul>\n";

    for ($iMenu = 0; $iMenu < $iTotalFilhos; $iMenu++) {

      $oMenu     = db_utils::fieldsMemory($rsFilhos, $iMenu);
      $rsSubItens = $this->getMenuItens($oMenu->id_item_filho);

      if ($this->oDaoMenu->numrows > 0) {

        $this->sHtmlMenu .= "<li class=\"sub-menu\" onmouseover=\"Menu_mouseOver(this, event)\">\n";
        $this->sHtmlMenu .= "<a href=\"javascript:;\">{$oMenu->descricao}</a>\n";
      } else {

        $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
        $this->sHtmlMenu .= "<a id=\"DBmenu_{$oMenu->id_item_filho}\" href=\"{$oMenu->funcao}\">{$oMenu->descricao}</a>\n";

        /**
         * Cache dos itens de menu
         */
        $oCacheMenu = new stdclass();
        $oCacheMenu->caminho = "{$sCaminho} > {$oMenu->descricao}";
        $oCacheMenu->funcao  = $oMenu->funcao;

        $this->aCacheMenus[] = $oCacheMenu;


        if ($oMenu->funcao == $this->sFuncao && empty($this->iIdFuncaoHelp)) {

          $this->iIdFuncaoHelp    = $oMenu->id_item_filho;
          $this->sDescricaoHelp   = $oMenu->desctec;
          $this->sDescricaoFuncao = $oMenu->help;
        }
      }

      $this->montaFilhos($rsSubItens, "{$sCaminho} > {$oMenu->descricao}");

      $this->sHtmlMenu .= "</li>\n";
    }

    $this->sHtmlMenu .= "</ul>\n";
  }

  /**
   * Monta o menu das areas e módulos
   */
  private function montaModulos() {
    $rsModulos = $this->getModulos();

    /**
     * Monta o menu dos módulos
     */
    if (pg_numrows($rsModulos) == 0) {
      return;
    }

    $this->sHtmlMenu .= "<ul>\n";
    $this->sHtmlMenu .= "<li class=\"sub-menu\" onclick=\"Menu_toggle(this, event)\" onmouseover=\"Menu_parentOver(this, event)\">\n";
    $this->sHtmlMenu .= "<a href=\"javascript:;\">Módulos</a>\n";
    $this->sHtmlMenu .= "<ul>\n";

    $sModuloAtual      = "";
    $iTotalModulosArea = 0;
    $iMaxModulos       = 22;

    for ($iModulo = 0; $iModulo < pg_numrows($rsModulos); $iModulo++) {
      $oModulo = db_utils::fieldsMemory($rsModulos, $iModulo);

      if ($oModulo->at25_descr != $sModuloAtual) {

        if ($iTotalModulosArea > $iMaxModulos) {

          $this->sHtmlMenu .= "</ul>\n";
          $this->sHtmlMenu .= "</li>\n";
        }

        if (!empty($sModuloAtual)) {

          $this->sHtmlMenu .= "</ul>\n";
          $this->sHtmlMenu .= "</li>\n";
        }

        $this->sHtmlMenu .= "<li class=\"sub-menu\" onmouseover=\"Menu_mouseOver(this, event)\">\n";
        $this->sHtmlMenu .= "<a href=\"javascript:;\">{$oModulo->at25_descr}</a>\n";

        $this->sHtmlMenu .= "<ul>\n";
        $iTotalModulosArea = 0;
      }

      if ($iTotalModulosArea == $iMaxModulos) {

        $this->sHtmlMenu .= "<li class=\"sub-menu\" onmouseover=\"Menu_mouseOver(this, event)\">\n";
        $this->sHtmlMenu .= "<a href=\"javascript:;\">Outros ...</a>\n";
        $this->sHtmlMenu .= "<ul>";
      }

      $sHref = base64_encode("anousu={$oModulo->anousu}&modulo={$oModulo->id_item}&nomemod={$oModulo->nome_modulo}");

      $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
      $this->sHtmlMenu .= "<a href=\"modulos.php?{$sHref}\">{$oModulo->nome_modulo}</a>\n";
      $this->sHtmlMenu .= "</li>\n";

      $sModuloAtual = $oModulo->at25_descr;
      $iTotalModulosArea++;
    }

    if ($iTotalModulosArea > $iMaxModulos) {

      $this->sHtmlMenu .= "</ul>\n";
      $this->sHtmlMenu .= "</li>\n";
    }

    /**
     * Fecha o ultimo item das areas
     */
    $this->sHtmlMenu .= "</li>\n";
    $this->sHtmlMenu .= "</ul>\n";

    $this->sHtmlMenu .= "</ul>\n";
    $this->sHtmlMenu .= "</li>\n";

    $this->sHtmlMenu .= "</ul>\n";
  }

  /**
   * Monta o menu do help
   */
  private function montaHelp(){

    $this->sHtmlMenu .= "<ul>\n";
    $this->sHtmlMenu .= "<li class=\"sub-menu\" onclick=\"Menu_toggle(this, event)\" onmouseover=\"Menu_parentOver(this, event)\">\n";
    $this->sHtmlMenu .= "<a href=\"javascript:;\">Central de Ajuda</a>\n";

    $this->sHtmlMenu .= "<ul>\n";
    $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
    // botao do Help
    $this->sHtmlMenu .= "<a href=\"javascript:;\" onclick=\"require_once('scripts/classes/configuracao/DBViewHelp.classe.js'); DBViewHelp.build(); \">Ajuda do Sistema</a>\n";
    $this->sHtmlMenu .= "</li>\n";

    // botao de FAQ
    $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
    $this->sHtmlMenu .= "<a href=\"javascript:;\" onclick=\"require_once('scripts/classes/configuracao/DBViewFaq.classe.js'); DBViewFaq.build(); \">Perguntas Frequentes</a>\n";
    $this->sHtmlMenu .= "</li>\n";

    $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
    $this->sHtmlMenu .= "<a href=\"javascript:;\"onclick=\"require_once('scripts/classes/configuracao/DBViewReleaseNote.classe.js'); DBViewReleaseNote.build(); \">Notas da Versão</a>\n";
    $this->sHtmlMenu .= "</li>\n";

    $this->sHtmlMenu .= "<li onmouseover=\"Menu_mouseOver(this, event)\">\n";
    $this->sHtmlMenu .= "<a href=\"javascript:;\"onclick=\"require_once('scripts/classes/configuracao/TutorialRepository.classe.js'); TutorialRepository.build(); \">Tutoriais</a>\n";
    $this->sHtmlMenu .= "</li>\n";

    $this->sHtmlMenu .= "</ul>\n";

    $this->sHtmlMenu .= "</li>\n";
    $this->sHtmlMenu .= "</ul>\n";
  }

  /**
   * Busca os itens de menu
   *
   * @param Integer $iIdItem - Item do menu pai
   * @return recordset
   */
  private function getMenuItens($iIdItem) {

    $sCampos = "m.id_item, m.id_item_filho, m.menusequencia, i.descricao, i.help, i.funcao, i.desctec ";
    $sWhere  = "m.modulo = {$this->iModulo} and i.itemativo = '1' and libcliente is true and m.id_item = {$iIdItem}";

    if ($this->iIdUsuario == 1 || $this->lAdministrador) {

      $sSql = $this->oDaoMenu->sqlItensMenuDBSeller( $sCampos,
                                                     $this->sOrdenacao,
                                                     $sWhere );
    } else {

      if ($this->lDBSeller) {
        $sWhere .= " and i.libcliente = true ";
      }

      $sSql = $this->oDaoMenu->sqlItensMenuUsuario( $sCampos,
                                                    $this->sOrdenacao,
                                                    $sWhere,
                                                    $this->iIdUsuario,
                                                    $this->iAnoUsu,
                                                    $this->iInstituicao,
                                                    $this->iModulo );
    }

    $rsFilhos = $this->oDaoMenu->sql_record($sSql);

    return $rsFilhos;
  }

  private function getModulos() {

    if ($this->iIdUsuario == 1 || $this->lAdministrador) {

      $sSql  = " select distinct atendcadarea.at25_descr,                                                         \n";
      $sSql .= "        db_itensmenu.id_item,                                                                     \n";
      $sSql .= "        db_modulos.nome_modulo,                                                                   \n";
      $sSql .= "        extract (year from current_date) as anousu                                                \n";
      $sSql .= "   from db_itensmenu                                                                              \n";
      $sSql .= "        inner join db_menu         on db_itensmenu.id_item         = db_menu.id_item              \n";
      $sSql .= "        inner join db_modulos      on db_modulos.id_item           = db_itensmenu.id_item         \n";
      $sSql .= "        inner join atendcadareamod on db_itensmenu.id_item         = atendcadareamod.at26_id_item \n";
      $sSql .= "        inner join atendcadarea    on atendcadarea.at26_sequencial = atendcadareamod.at26_codarea \n";
      $sSql .= "  where libcliente is true                                                                        \n";
      $sSql .= "  order by atendcadarea.at25_descr, nome_modulo                                                   \n";

    } else {

      $sSql  = " select atendcadarea.at25_descr,                                                                                      \n";
      $sSql .= "        x.id_item,                                                                                                    \n";
      $sSql .= "        nome_modulo,                                                                                                  \n";
      $sSql .= "        max(x.anousu) as anousu                                                                                       \n";
      $sSql .= "   from (                                                                                                             \n";
      $sSql .= "          select distinct i.id_modulo as id_item,                                                                     \n";
      $sSql .= "                 m.nome_modulo,                                                                                       \n";
      $sSql .= "                 case                                                                                                 \n";
      $sSql .= "                   when u.anousu is null then {$this->iAnoUsu}                                                        \n";
      $sSql .= "                   else u.anousu                                                                                      \n";
      $sSql .= "                 end                                                                                                  \n";
      $sSql .= "            from (                                                                                                    \n";
      $sSql .= "                  select distinct i.itemativo,                                                                        \n";
      $sSql .= "                         p.id_modulo,                                                                                 \n";
      $sSql .= "                         p.id_usuario,                                                                                \n";
      $sSql .= "                         p.id_instit                                                                                  \n";
      $sSql .= "                    from db_permissao p                                                                               \n";
      $sSql .= "                         inner join db_itensmenu i on p.id_item = i.id_item                                           \n";
      $sSql .= "                   where i.itemativo = '1'                                                                            \n";
      $sSql .= "                     and p.id_usuario = {$this->iIdUsuario}                                                           \n";
      $sSql .= "                     and p.id_instit = {$this->iInstituicao}                                                          \n";
      $sSql .= "                     and p.anousu = {$this->iDataUsu}                                                                 \n";
      $sSql .= "                     and libcliente is true                                                                           \n";
      $sSql .= "                  ) as i                                                                                              \n";
      $sSql .= "                  inner join db_modulos m  on m.id_item = i.id_modulo                                                 \n";
      $sSql .= "                  inner join db_itensmenu it on it.id_item = i.id_modulo                                              \n";
      $sSql .= "                   left outer join db_usumod u  on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario            \n";
      $sSql .= "            where i.id_usuario = {$this->iIdUsuario}                                                                  \n";
      $sSql .= "              and i.id_instit = {$this->iInstituicao}                                                                 \n";
      $sSql .= "              and it.libcliente is true                                                                               \n";
      $sSql .= "          union                                                                                                       \n";
      $sSql .= "          select distinct i.id_modulo as id_item,                                                                     \n";
      $sSql .= "                 m.nome_modulo,                                                                                       \n";
      $sSql .= "                 case                                                                                                 \n";
      $sSql .= "                   when u.anousu is null then {$this->iAnoUsu}                                                        \n";
      $sSql .= "                   else u.anousu                                                                                      \n";
      $sSql .= "                 end                                                                                                  \n";
      $sSql .= "            from (                                                                                                    \n";
      $sSql .= "                   select distinct i.itemativo,                                                                       \n";
      $sSql .= "                          p.id_modulo,                                                                                \n";
      $sSql .= "                          p.id_usuario,                                                                               \n";
      $sSql .= "                          p.id_instit                                                                                 \n";
      $sSql .= "                     from db_permissao p                                                                              \n";
      $sSql .= "                          inner join db_permherda h on h.id_perfil = p.id_usuario                                     \n";
      $sSql .= "                          inner join db_itensmenu i on p.id_item = i.id_item                                          \n";
      $sSql .= "                    where i.itemativo = '1'                                                                           \n";
      $sSql .= "                      and p.id_usuario in (select id_perfil from db_permherda where id_usuario = {$this->iIdUsuario}) \n";
      $sSql .= "                      and p.id_instit = {$this->iInstituicao}                                                         \n";
      $sSql .= "                      and p.anousu = {$this->iDataUsu}                                                                \n";
      $sSql .= "                      and libcliente is true                                                                          \n";
      $sSql .= "                  ) as i                                                                                              \n";
      $sSql .= "                  inner join db_modulos m  on m.id_item = i.id_modulo                                                 \n";
      $sSql .= "                  inner join db_itensmenu it on it.id_item = i.id_modulo                                              \n";
      $sSql .= "                  left outer join db_usumod u  on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario             \n";
      $sSql .= "            where i.id_instit = {$this->iInstituicao}                                                                 \n";
      $sSql .= "            and it.libcliente is true                                                                                 \n";
      $sSql .= "         order by nome_modulo                                                                                         \n";
      $sSql .= "        ) as x                                                                                                        \n";
      $sSql .= "         inner join atendcadareamod on id_item = atendcadareamod.at26_id_item                                         \n";
      $sSql .= "         inner join atendcadarea on atendcadarea.at26_sequencial =  atendcadareamod.at26_codarea                      \n";
      $sSql .= "         inner join db_usumod       on db_usumod.id_item = atendcadareamod.at26_id_item                               \n";
      $sSql .= "                                   and db_usumod.id_usuario = {$this->iIdUsuario}                                     \n";
      $sSql .= "   group by at25_descr, x.id_item, nome_modulo                                                                        \n";
      $sSql .= "   order by at25_descr,nome_modulo                                                                                    \n";
    }

    $rsModulos = db_query($sSql);

    return $rsModulos;
  }

  /**
   * Retorna o campo da tabela que deve ser ordenado os menus
   * @return string
   */
  public static function getCampoOrdenacao() {

    $oPreferencias = unserialize(base64_decode(db_getsession('DB_preferencias_usuario')));

    switch ($oPreferencias->getOrdenacao()) {

      case 'sequencial':
        $sOrdenacao = 'menusequencia';
        break;

      case 'alfabetico':
        $sOrdenacao = 'descricao';
        break;

      default:
        $sOrdenacao = 'menusequencia';
    }

    return $sOrdenacao;
  }

  /**
   * @param integer $id
   * @param integer $idModulo
   * @return string
   */
  public static function getBreadcrumb($id, $idModulo = null, $sSeparator = ' > ') {

    if (empty($idModulo)) {
      return \db_stdClass::getCaminhoMenu((int) $id);
    }

    $aCaminho = array();
    $oDaoMenu = new cl_db_itensmenu();
    $iLimiteIteracoes = 100;

    $sCampos  = " case when db_menu.modulo = db_menu.id_item then true  else false end as is_raiz,\n";
    $sCampos .= " db_menu.id_item  as id_parent,\n";
    $sCampos .= " db_modulos.nome_modulo  as descricao_modulo,\n";
    $sCampos .= " db_itensmenu.descricao\n";

    while (--$iLimiteIteracoes > 0) {

      $sSql = $oDaoMenu->sql_queryArvoreMenus($sCampos, $id, $idModulo);
      $rsMenu = db_query($sSql);

      if (!$rsMenu || pg_num_rows($rsMenu) == 0) {
        break;
      }

      $oDados = db_utils::fieldsMemory($rsMenu, 0);
      $aCaminho[] = $oDados->descricao;
      $id = $oDados->id_parent;

      if ($oDados->is_raiz == 't') {

        $aCaminho[] = $oDados->descricao_modulo;
        break;
      }
    }

    return implode($sSeparator, array_reverse($aCaminho));
  }

}
