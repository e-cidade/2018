<?php
/**
 * Class MensageriaAcordoUsuarioRepository
 */
class MensageriaAcordoUsuarioRepository {

  /**
   * Collection de MensageriaAcordoUsuario
   * @var array
   */
  private $aItens = array();

  /**
   * Instancia da classe
   * @var MensageriaAcordoUsuarioRepository
   */
  private static $oInstance;

  /**
   * Método privado para não podermos instanciar a classe
   */
  private function __construct() {}

  /**
   * Não permite clonar o objeto
   */
  private function __clone() {}

  /**
   * Retorna uma instancia do MensageriaAcordoUsuario pelo codigo
   * @param integer $iCodigo Codigo do MensageriaAcordoUsuario
   * @return MensageriaAcordoUsuario
   */
  public static function getPorCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, MensageriaAcordoUsuarioRepository::getInstancia()->aItens)) {
      MensageriaAcordoUsuarioRepository::getInstancia()->aItens[$iCodigo] = new MensageriaAcordoUsuario($iCodigo);
    }
    return MensageriaAcordoUsuarioRepository::getInstancia()->aItens[$iCodigo];
  }

  /**
   * Retorna um usuario/dia
   * @param UsuarioSistema $oUsuario
   * @param $iDias
   * @return MensageriaAcordoUsuario
   */
  public static function getPorUsuarioDia(UsuarioSistema $oUsuario, $iDias) {

    foreach (MensageriaAcordoUsuarioRepository::getInstancia()->aItens as $iCodigo => $oMensageriaAcordoUsuario){

      if ($oMensageriaAcordoUsuario->getUsuario()->getIdUsuario() == $oUsuario->getIdUsuario() &&
          $oMensageriaAcordoUsuario->getDias() == $iDias){
        return MensageriaAcordoUsuarioRepository::getInstancia()->aItens[$iCodigo];
      }
    }
    $sWhere  = "    ac52_db_usuarios = {$oUsuario->getIdUsuario()} ";
    $sWhere .= "and ac52_dias        = {$iDias} ";
    $oDaoMensageriaAcordoUsuario = new cl_mensageriaacordodb_usuario();
    $sSqlBuscaUsuario = $oDaoMensageriaAcordoUsuario->sql_query_file(null, 'ac52_sequencial', null, $sWhere);
    $rsBuscaUsuario   = $oDaoMensageriaAcordoUsuario->sql_record($sSqlBuscaUsuario);
    if (!$rsBuscaUsuario || $oDaoMensageriaAcordoUsuario->numrows == "0") {
      return false;
    }
    $iCodigo = db_utils::fieldsMemory($rsBuscaUsuario, 0)->ac52_sequencial;
    MensageriaAcordoUsuarioRepository::adicionarMensageriaAcordoUsuario(new MensageriaAcordoUsuario($iCodigo));
    return MensageriaAcordoUsuarioRepository::getPorCodigo($iCodigo);
  }

  /**
   * Retorna a instancia da classe
   * @return MensageriaAcordoUsuarioRepository
   */
  protected static function getInstancia() {

    if (self::$oInstance == null) {
      self::$oInstance = new MensageriaAcordoUsuarioRepository();
    }
    return self::$oInstance;
  }

  /**
   * @param MensageriaAcordoUsuario $oMensageriaAcordoUsuario
   */
  public static function adicionarMensageriaAcordoUsuario(MensageriaAcordoUsuario $oMensageriaAcordoUsuario) {

    if (!array_key_exists($oMensageriaAcordoUsuario->getCodigo(), MensageriaAcordoUsuarioRepository::getInstancia()->aItens)) {
      MensageriaAcordoUsuarioRepository::getInstancia()->aItens[$oMensageriaAcordoUsuario->getCodigo()] = $oMensageriaAcordoUsuario;
    }
    return;
  }

  /**
   * Retorna todos os registros vinculados para a mensagem
   * @return MensageriaAcordoUsuario[]
   * @throws BusinessException
   */
  public static function getColecaoMensageriaAcordoUsuario() {

    $oDaoMensageriaUsuario = new cl_mensageriaacordodb_usuario();
    $sSqlBuscaUsuario      = $oDaoMensageriaUsuario->sql_query_file(null, "ac52_sequencial", "ac52_dias, ac52_db_usuarios");
    $rsBuscaUsuario        = $oDaoMensageriaUsuario->sql_record($sSqlBuscaUsuario);
    if (!$oDaoMensageriaUsuario || $oDaoMensageriaUsuario->numrows == 0) {
      throw new BusinessException("Impossível carregar os destinatários parametrizados.");
    }

    for ($iRowUsuario = 0; $iRowUsuario < $oDaoMensageriaUsuario->numrows; $iRowUsuario++) {

      $iCodigo = db_utils::fieldsMemory($rsBuscaUsuario, $iRowUsuario)->ac52_sequencial;
      MensageriaAcordoUsuarioRepository::adicionarMensageriaAcordoUsuario(new MensageriaAcordoUsuario($iCodigo));
    }
    return MensageriaAcordoUsuarioRepository::getInstancia()->aItens;
  }
}