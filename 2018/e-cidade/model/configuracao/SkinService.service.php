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

Class SkinService {

  const SKINS_BASE    = 'skins/';
  const COOKIE_NOME   = 'ecidade_skin';

  /**
   * Skin default
   * @var string
   */
  private $sSkinDefault = '';

  public function __construct() {

    $oPreferenciaCliente = new PreferenciaCliente();
    $this->sSkinDefault = $oPreferenciaCliente->getSkinDefault();
  }

  /**
   * Retorna todos as skins disponíveis
   *
   * @return Array
   */
  public function getSkins() {

    $aSkins = scandir(self::SKINS_BASE);

    $aRetornoSkins = array();
    foreach ($aSkins as $sSkin) {

      $sPathPlugin = self::SKINS_BASE . $sSkin;

      if (!in_array($sSkin, array('.', '..')) && is_dir($sPathPlugin) && file_exists("{$sPathPlugin}/config.json")) {
        $oJson = json_decode( file_get_contents("{$sPathPlugin}/config.json") );

        $aRetornoSkins[$sSkin] = utf8_decode($oJson->nome);
      }
    }

    return $aRetornoSkins;
  }

  /**
   * Retorna a skin que esta ativa para o usuário
   *
   * @return String
   */
  public function getActiveSkin() {

    if( !isset($_SESSION['DB_preferencias_usuario']) ){
      return $this->sSkinDefault;
    }

    $oPreferencias = unserialize(base64_decode($_SESSION['DB_preferencias_usuario']));

    return $oPreferencias->getSkin();
  }

  /**
   * Retorna o path do arquivo passado por parametro -- Caso não encontre na skin ativa pega da skin padrão --
   *
   * @param string $sArquivo Arquivo a ser carregado
   * @param string $sActiveSkin a Skin ativa atualmente (Caso não seja passado irá pegar da sessão)
   * @return string
   */
  public function getPathFile($sArquivo, $sActiveSkin = "") {

    $sPath = self::SKINS_BASE . (!empty($sActiveSkin) ? $sActiveSkin : $this->getActiveSkin()) . "/{$sArquivo}";

    if (file_exists($sPath)) {
      return $sPath;
    }

    $sPath = self::SKINS_BASE . $this->sSkinDefault . "/{$sArquivo}";

    if (file_exists($sPath)) {
      return $sPath;
    }

    $oPreferenciaEcidade = new PreferenciaEcidade();
    return self::SKINS_BASE . $oPreferenciaEcidade->getSkinDefault() . "/{$sArquivo}";
  }

  /**
   * Retorna o cookie salvo na sessão com a skin ativa
   * @return mixed string|null
   */
  public function getCookie() {

    if ( !empty($_COOKIE[self::COOKIE_NOME]) ) {
      return $_COOKIE[self::COOKIE_NOME];
    }

    return null;
  }

  /**
   * Seta na sessão a skin ativa
   */
  public function setCookie() {
    setcookie( self::COOKIE_NOME, $this->getActiveSkin(), 0, '/' );
  }

}