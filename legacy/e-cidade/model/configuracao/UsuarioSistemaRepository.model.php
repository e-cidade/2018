<?php
/**
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

/**
 * Class UsuarioSistemaRepository
 */
class UsuarioSistemaRepository {

  /**
   * @var array
   */
  private $aItens = array();

  /**
   * @var UsuarioSistemaRepository
   */
  private static $oInstancia;

  /**
   * @param $iCodigo
   * @return UsuarioSistema
   */
  public static function getPorCodigo($iCodigo) {

    if ( !array_key_exists($iCodigo, UsuarioSistemaRepository::getInstancia()->aItens)) {
      UsuarioSistemaRepository::getInstancia()->aItens[$iCodigo] = new UsuarioSistema($iCodigo);
    }
    return UsuarioSistemaRepository::getInstancia()->aItens[$iCodigo];
  }

  /**
   * @return UsuarioSistemaRepository
   */
  private static function getInstancia() {

    if (self::$oInstancia == null) {
      self::$oInstancia = new UsuarioSistemaRepository();
    }
    return self::$oInstancia;
  }

  /**
   * Retorna as Lotações que o Usuário do sistema ãinda não possuí vínculo.
   * 
   * @param  UsuarioSistema   $oUsuarioSistema 
   * @param  Instituicao|null $oInstituicao    
   * @return Array Toddas as Lotações ainda disponíveis para o usuário.
   */
  public static function getLotacoesPermitidas(UsuarioSistema $oUsuarioSistema, Instituicao $oInstituicao = null) {

    if (is_null($oInstituicao)) {
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    $aLotacoesIntituicao      = LotacaoRepository::getLotacoesByInstituicao($oInstituicao, true);
    $aLotacoesUsuario         = LotacaoRepository::getLotacoesByUsuario($oUsuarioSistema, $oInstituicao);
    $iTotalLotacaoInstituicao = count($aLotacoesIntituicao);

    for ($iLotacaoInstituicao = 0; $iLotacaoInstituicao < $iTotalLotacaoInstituicao; $iLotacaoInstituicao++) {

      $oLotacaoInstituicao = $aLotacoesIntituicao[$iLotacaoInstituicao];
      for ($iLotacoesUsuario = 0; $iLotacoesUsuario < count($aLotacoesUsuario); $iLotacoesUsuario++) {

        $oLotacaoUsuario = $aLotacoesUsuario[$iLotacoesUsuario];

        if ($oLotacaoInstituicao->getCodigoLotacao() == $oLotacaoUsuario->getCodigoLotacao()) {
          unset($aLotacoesIntituicao[$iLotacaoInstituicao]);
        }
      }
    }
    sort($aLotacoesIntituicao);

    return $aLotacoesIntituicao;
  }

  /**
   * Impossibilita instancia
   */
  private function __construct() {}
  private function __clone() {}
}