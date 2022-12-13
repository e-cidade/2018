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
 * Class MensageriaLicencaProcessamento
 */
class MensageriaLicencaProcessado {

  /**
   * @var MensageriaLicenca
   */
  private $oMensageriaLicenca;

  /**
   * Dias a vencer para avisar o usuário
   * @var array
   */
  private $aDiasAviso = array();

  /**
   * Usuarios do Sistema que devem ser avisados
   * @var UsuarioSistema[]
   */
  private $aUsuariosAviso;

  /**
   * Caminho das mensagens utilizadas pelo model
   */
  const CAMINHO_MENSAGEM = 'configuracao.configuracao.MensageriaAcordoUsuario.';

  /**
   * @param array $aDiasAviso
   */
  public function setDiasAviso(array $aDiasAviso) {
    $this->aDiasAviso = $aDiasAviso;
  }

  /**
   * @return array
   */
  public function getDiasAviso() {
    return $this->aDiasAviso;
  }

  /**
   * @param UsuarioSistema[] $aUsuariosAviso
   * @throws BusinessException
   */
  public function setUsuariosAviso($aUsuariosAviso) {

    foreach ($aUsuariosAviso as $oUsuario) {

      if (!$oUsuario instanceof UsuarioSistema) {
        throw new BusinessException('Coleção de usuários não são UsuarioSistema. Contate o suporte.');
      }
    }
    $this->aUsuariosAviso = $aUsuariosAviso;
  }

  /**
   * @return UsuarioSistema[]
   */
  public function getUsuariosAviso() {
    return $this->aUsuariosAviso;
  }

  /**
   * @param MensageriaLicenca $oMensageriaLicenca
   */
  public function setMensageriaLicenca(MensageriaLicenca $oMensageriaLicenca) {
    $this->oMensageriaLicenca = $oMensageriaLicenca;
  }

  /**
   * @return MensageriaLicenca
   */
  public function getMensageriaLicenca() {
    return $this->oMensageriaLicenca;
  }

  /**
   * @param UsuarioSistema $oUsuario
   */
  public function adicionarUsuario(UsuarioSistema $oUsuario) {
    $this->aUsuariosAviso[] = $oUsuario;
  }

  /**
   * @return bool
   */
  public function processarParametros() {

    $this->oMensageriaLicenca->salvar();
    $this->excluirDias();
    $this->excluirUsuarios();

    foreach ($this->aUsuariosAviso as $oUsuarioSistema) {

      foreach ($this->aDiasAviso as $iDia) {

        /**
         * @todo MensageriaLicencaUsuarioRepository
         */
        if ( ! MensageriaLicencaUsuarioRepository::getPorUsuarioDia($oUsuarioSistema, $iDia)) {

          $oMensageriaUsuario = new MensageriaLicencaUsuario(null);
          $oMensageriaUsuario->setUsuario($oUsuarioSistema);
          $oMensageriaUsuario->setDias($iDia);
          $oMensageriaUsuario->salvar();
        }
      }
    }
    return true;
  }

  /**
   * Exclui os usuários de acordo com os dias encontrados para remição.
   * @return bool
   * @throws BusinessException
   */
  private function excluirDias() {

    $oDaoMensageriaUsuario = db_utils::getDao('mensagerialicenca_db_usuarios');
    $sSqlBuscaDias         = $oDaoMensageriaUsuario->sql_query_file(null, "distinct am16_dias" , 'am16_dias');
    $rsBuscaDias           = $oDaoMensageriaUsuario->sql_record($sSqlBuscaDias);
    $aDiasRemover          = array();

    for ($iRowDia = 0; $iRowDia < $oDaoMensageriaUsuario->numrows; $iRowDia++) {

      $iStdDia = db_utils::fieldsMemory($rsBuscaDias, $iRowDia)->am16_dias;
      if ( ! in_array($iStdDia, $this->getDiasAviso()) ) {
        $aDiasRemover[] = $iStdDia;
      }
    }

    if (count($aDiasRemover) > 0) {

      $sDiasRemover = implode(",", $aDiasRemover);
      $sWhereDias   = "am16_dias in ($sDiasRemover)";
      $sSqlBuscaMensageriaUsuario = $oDaoMensageriaUsuario->sql_query_file(null, "am16_sequencial", null, $sWhereDias);
      $rsBuscaMensageriaUsuario   = $oDaoMensageriaUsuario->sql_record($sSqlBuscaMensageriaUsuario);
      if (!$rsBuscaMensageriaUsuario || $oDaoMensageriaUsuario->numrows == 0) {
        throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'destinatarios_dia_nao_encontrado'));
      }

      for ($iRowDia = 0; $iRowDia < $oDaoMensageriaUsuario->numrows; $iRowDia++) {

        $iCodigo = db_utils::fieldsMemory($rsBuscaMensageriaUsuario, $iRowDia)->am16_sequencial;
        $oMensageriaLicencaUsuario = MensageriaLicencaUsuarioRepository::getPorCodigo($iCodigo);
        $oMensageriaLicencaUsuario->remover();
      }
    }
    return true;
  }

  /**
   * Exclui os destinatários que foram excluidos pelo usuário do sistema
   * @return bool
   * @throws BusinessException
   */
  private function excluirUsuarios() {

    $aUsuariosSelecionados = array();
    foreach ($this->getUsuariosAviso() as $oUsuarioSistema) {
      $aUsuariosSelecionados[] = $oUsuarioSistema->getIdUsuario();
    }

    $oDaoMensageriaUsuario = db_utils::getDao('mensagerialicenca_db_usuarios');
    $sSqlBuscUsuario       = $oDaoMensageriaUsuario->sql_query_file(null, "distinct am16_usuario" , "am16_usuario");
    $rsBuscaUsuario        = $oDaoMensageriaUsuario->sql_record($sSqlBuscUsuario);
    $aUsuarioRemover       = array();
    for ($iRowUsuario = 0; $iRowUsuario < $oDaoMensageriaUsuario->numrows; $iRowUsuario++) {

      $iStdUsuario = db_utils::fieldsMemory($rsBuscaUsuario, $iRowUsuario)->am16_usuario;
      if ( ! in_array($iStdUsuario, $aUsuariosSelecionados) ) {
        $aUsuarioRemover[] = $iStdUsuario;
      }
    }

    if (count($aUsuarioRemover) > 0) {

      $sUsuarioRemover = implode(",", $aUsuarioRemover);
      $sWhereUsuario   = "am16_usuario in ($sUsuarioRemover)";
      $sSqlBuscaMensageriaUsuario = $oDaoMensageriaUsuario->sql_query_file(null, "am16_sequencial", null, $sWhereUsuario);
      $rsBuscaMensageriaUsuario   = $oDaoMensageriaUsuario->sql_record($sSqlBuscaMensageriaUsuario);
      if (!$rsBuscaMensageriaUsuario || $oDaoMensageriaUsuario->numrows == 0) {
        throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'destinatarios_dia_nao_encontrado'));
      }

      for ($iRowUsuario = 0; $iRowUsuario < $oDaoMensageriaUsuario->numrows; $iRowUsuario++) {

        $iCodigo = db_utils::fieldsMemory($rsBuscaMensageriaUsuario, $iRowUsuario)->am16_sequencial;
        $oMensageriaLicencaUsuario = MensageriaLicencaUsuarioRepository::getPorCodigo($iCodigo);
        $oMensageriaLicencaUsuario->remover();
      }
    }
    return true;
  }
}