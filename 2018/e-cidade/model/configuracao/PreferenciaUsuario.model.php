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

require_once("libs/db_autoload.php");

/**
 * Classe responsável pela manipulação das preferências do usuário.
 * @package configuracao
 * @author Renan Melo <renan@dbseller.com.br>
 */
class PreferenciaUsuario {

  /**
   * Caminho onde o arquivo deve ser salvo.
   * @var String
   */
  const CAMINHO_ARQUIVO = 'cache/preferencias/';

  /**
   * Caminhos para o arquivo JSON contendo as mensagens utilizadas na função _M
   */
  const MENSAGENS = 'configuracao.configuracao.preferenciaUsuario.';

  /**
   * Nome do arquivo a ser salvo
   * @var String
   */
  private $sNomeArquivo;

  /**
   * Define a ordenação que deve ser utilizado nos menus
   * -Sequencial
   * -Alfabética
   * @var String
   */
  private $sOrdenacao;

  /**
   * Instância da classe UsuarioSistema
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Define se a busca por menus deve ser exibida ou não.
   * @var String
   */
  private $sExibeBusca;

  /**
   * Define o skin que será utilizado pelo usuário
   * @var String
   */
  private $sSkin;

  /**
   * Se deve fazer cache dos menus
   * @var boolean
   */
  private $lHabilitaCacheMenu;

  /**
   * Filtros personalizados das funções de pesquisa
   * @var Array
   */
  private $aFiltrosPersonalizados;

    /**
     * Função construtura, recebe como parametro uma instância de UsuarioSistema e
     * realiza o LazyLoad carregando as preferências do usuário
     * @param UsuarioSistema $oUsuarioSistema [description]
     */
    function __construct( UsuarioSistema $oUsuarioSistema ) {

      $oPreferenciaCliente = new PreferenciaCliente();

      $this->oUsuarioSistema    = $oUsuarioSistema;
      $this->sNomeArquivo       = $this->oUsuarioSistema->getLogin() . '.json';

      $this->sOrdencao          = 'sequencial';
      $this->sExibeBusca        = '0';
      $this->sSkin              = $oPreferenciaCliente->getSkinDefault();
      $this->lHabilitaCacheMenu = true;

      if (!file_exists(PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo)) {
        return false;
      }

      $sPreferencias = file_get_contents( PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo );
      $oPreferencias = json_decode($sPreferencias);

      $this->sOrdencao          = $oPreferencias->ordenacao;
      $this->sExibeBusca        = $oPreferencias->busca;
      
      if ( property_exists($oPreferencias, 'skin') ) {
        $this->sSkin = $oPreferencias->skin;
      }

      if ( property_exists($oPreferencias, 'lHabilitaCacheMenu') ) {
        $this->lHabilitaCacheMenu = $oPreferencias->lHabilitaCacheMenu;
      }
      
      if ( property_exists($oPreferencias, "oFiltrosPersonalizados") ) {
        $this->aFiltrosPersonalizados = (array) $oPreferencias->oFiltrosPersonalizados;
      }




      return true;
    }

  /**
   * Define a ordenação utilizada nos menus
   * @param String $sOrdencao
   */
  public function setOrdenacao($sOrdencao){
    $this->sOrdencao = $sOrdencao;
  }

  /**
   * Retorna a ordenação que deve ser utilizada nos menus
   * @return String
   */
  public function getOrdenacao(){
    return $this->sOrdencao;
  }

  /**
   * Define se deve exibir a busca de menus
   * @param string $sBusca
   */
  public function setExibeBusca($sBusca){
    $this->sExibeBusca = $sBusca;
  }

  /**
   * Retorna se irá exibir a busca de menus
   * @return string
   */
  public function getExibeBusca(){
    return $this->sExibeBusca;
  }

  /**
   * Define o skin a ser utilizado
   * @param string $sSkin
   */
  public function setSkin($sSkin) {
    $this->sSkin = $sSkin;
  }

  /**
   * Retorna o skin a ser utilizado
   * @return string
   */
  public function getSkin() {
    return $this->sSkin;
  }

  /**
   * Define se deve fazer cache dos menus
   * @param boolean $lHabilitaCacheMenu
   */
  public function setHabilitaCacheMenu($lHabilitaCacheMenu) {
    $this->lHabilitaCacheMenu = $lHabilitaCacheMenu;
  }

  /**
   * Retorna se deve fazer cache dos menus
   * @return boolean
   */
  public function getHabilitaCacheMenu() {
    return $this->lHabilitaCacheMenu;
  }

  /**
   * Adiciona um filtro personalizado a rotina especificada
   *  
   * @param String $sRotina -Fução de pesquisa
   * @param String $sFiltro -Nome do filtro
   * @param boolean $lAtivo -Define se o filtro vai ser exibido ou não
   * @return Boolean
   */
  public function adicionarFiltroPersonalizado( $sRotina, $sFiltro ) {

    if ( !array_key_exists($sRotina, $this->aFiltrosPersonalizados) ) {
      $this->aFiltrosPersonalizados[$sRotina][] = $sFiltro;
      return true;
    }

    if ( !in_array( $sFiltro, $this->aFiltrosPersonalizados[$sRotina]) ) {
      $this->aFiltrosPersonalizados[$sRotina][] = $sFiltro;
      return true;
    }

    return false;
  }

  /**
   * Limpa todos os filtros
   * 
   * @return void
   */
  public function limparFiltrosPersonalizados() {
    $this->aFiltrosPersonalizados = array();
    return;
  }

  /**
   * Remove o filtro personalizado
   *
   * @param String $sRotina -Fução de pesquisa
   * @param String $sFiltro -Nome do filtro
   * @return Boolean
   */
  public function removerFiltroPersonalizado( $sRotina, $sFiltro ) {
     
    if ( !array_key_exists($sRotina, $this->aFiltrosPersonalizados) ) {
      return false;
    }

    $aFiltroInvertido = array_flip($this->aFiltrosPersonalizados[$sRotina]);

    if ( !array_key_exists($sFiltro, $aFiltroInvertido) ) {
      return false;
    }
    
    $iChaveExclusao = $aFiltroInvertido[$sFiltro];
    unset($this->aFiltrosPersonalizados[$sRotina][$iChaveExclusao]);
    sort($this->aFiltrosPersonalizados[$sRotina]);

    return true;
  }

  /**
   * Salva o arquivo [login_usuario].json contendo as preferências.
   * @return boolean
   */
  public function salvar(){

    $sPreferencias = $this->toJSON();

    if (!file_exists(PreferenciaUsuario::CAMINHO_ARQUIVO)) {
      mkdir(PreferenciaUsuario::CAMINHO_ARQUIVO, 0777, TRUE);
    }

    if (!is_writable(PreferenciaUsuario::CAMINHO_ARQUIVO)) {
      throw new Exception(_M(PreferenciaUsuario::MENSAGENS . 'erro_salvar'));
    }

    $oHandle = fopen(PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo, 'w');
    fwrite($oHandle, $sPreferencias);
    fclose($oHandle);

    /**
     * Limpa o cache dos menus do usuario
     */
    DBMenu::limpaCache($this->oUsuarioSistema->getIdUsuario());

    if (!$oHandle) {
      throw new Exception(_M(PreferenciaUsuario::MENSAGENS . 'erro_salvar'));
    }

    db_putsession("DB_preferencias_usuario", base64_encode(serialize($this)));

    /**
     * Salva o skin no cookie
     */
    $oSkin = new SkinService();
    $oSkin->setCookie();

    return true;
  }

  /**
   * Converte um objeto com as preferências do usuario
   * para uma String JSON
   * @return String
   */
  public function toJSON(){

    $oPreferencias = new stdClass();
    $oPreferencias->ordenacao              = $this->sOrdencao;
    $oPreferencias->busca                  = $this->sExibeBusca;
    $oPreferencias->skin                   = $this->sSkin;
    $oPreferencias->lHabilitaCacheMenu     = $this->lHabilitaCacheMenu;
    $oPreferencias->oFiltrosPersonalizados = (object)$this->aFiltrosPersonalizados;
    return  json_encode($oPreferencias);
  }
}
