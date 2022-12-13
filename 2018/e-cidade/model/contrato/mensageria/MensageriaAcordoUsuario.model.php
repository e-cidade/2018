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
 * Class MensageriaAcordoUsuario
 */
class MensageriaAcordoUsuario {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * Código do Usuario
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * @var integer
   */
  private $iDias;

  /**
   * Caminho das mensagens utilizadas pelo programa
   */
  const CAMINHO_MENSAGEM = 'configuracao.configuracao.MensageriaAcordoUsuario.';

  /**
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (empty($iCodigo)) {
      return;
    }

    $oDaoMensageriaUsuario = new cl_mensageriaacordodb_usuario();
    $sSqlBuscaUsuario      = $oDaoMensageriaUsuario->sql_query_file($iCodigo);
    $rsBuscaUsuario        = $oDaoMensageriaUsuario->sql_record($sSqlBuscaUsuario);
    if (!$rsBuscaUsuario || $oDaoMensageriaUsuario->numrows == 0) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'destinatario_nao_encontrado'));
    }

    $oStdDadoUsuario      = db_utils::fieldsMemory($rsBuscaUsuario, 0);
    $this->iCodigoUsuario = $oStdDadoUsuario->ac52_db_usuarios;
    $this->iDias          = $oStdDadoUsuario->ac52_dias;
    unset($oStdDadoUsuario);
  }

  /**
   * @param int $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iDias
   */
  public function setDias($iDias) {
    $this->iDias = $iDias;
  }

  /**
   * @return int
   */
  public function getDias() {
    return $this->iDias;
  }

  /**
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (!empty($this->iCodigoUsuario) && empty($this->oUsuario)) {
      $this->oUsuario = new UsuarioSistema($this->iCodigoUsuario);
    }
    return $this->oUsuario;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'sem_transacao'));
    }

    if (!$this->getUsuario() instanceof UsuarioSistema) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM . 'destinatario_nao_informado'));
    }

    if (empty($this->iDias)) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM . 'dias_nao_informados'));
    }

    $oDaoMensageriaAcordoUsuario = new cl_mensageriaacordodb_usuario();
    $oDaoMensageriaAcordoUsuario->ac52_db_usuarios = $this->getUsuario()->getCodigo();
    $oDaoMensageriaAcordoUsuario->ac52_dias = $this->getDias();
    $oDaoMensageriaAcordoUsuario->incluir(null);

    if ($oDaoMensageriaAcordoUsuario->erro_status == 0) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM . 'erro_salvar'));
    }

    $this->iCodigo = $oDaoMensageriaAcordoUsuario->ac52_sequencial;
    return true;
  }

  /**
   *
   * metodo irá remover registros das tabelas
   *  - mensageriaacordoprocessados
   *  - mensageriaacordodb_usuario
   *
   * @throws BusinessException
   * @return mixed
   */
  public function remover() {

    if ( empty( $this->iCodigo) ) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'exclusao_abortada'));
    }

    if (!db_utils::inTransaction()) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'sem_transacao'));
    }

    $iCodigo                        = $this->getCodigo();
    $oDaoMensageriaUsuario          = new cl_mensageriaacordodb_usuario();
    $oDaoMensageriaAcordoProcessado = new cl_mensageriaacordoprocessados();
    $oErro                          = new stdClass();

    $oDaoMensageriaAcordoProcessado->excluir(null, "ac53_mensageriaacordodb_usuarios = {$iCodigo}");
    if ($oDaoMensageriaAcordoProcessado->erro_status == 0) {

      $oErro->mensagem = $oDaoMensageriaAcordoProcessado->erro_msg;
      throw new DBException(_M(self::CAMINHO_MENSAGEM.'erro_excluir_acordousuario', $oErro));
    }

    $oDaoMensageriaUsuario->excluir( $iCodigo );
    if ($oDaoMensageriaUsuario->erro_status == 0 ) {

      $oErro->mensagem = $oDaoMensageriaUsuario->erro_msg;
      throw new DBException(_M(self::CAMINHO_MENSAGEM.'erro_excluir_acordousuario', $oErro));
    }

    return true;
  }

}
