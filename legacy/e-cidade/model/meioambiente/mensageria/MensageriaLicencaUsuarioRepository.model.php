<?php
/**
 * Class MensageriaLicencaUsuarioRepository
 */
class MensageriaLicencaUsuarioRepository {

  /**
   * Collection de MensageriaLicencaUsuario
   * @var array
   */
  private $aItens = array();

  /**
   * Instancia da classe
   * @var MensageriaLicencaUsuarioRepository
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
   * Retorna uma instancia do MensageriaLicencaUsuario pelo codigo
   * @param integer $iCodigo Codigo do MensageriaLicencaUsuario
   * @return MensageriaLicencaUsuario
   */
  public static function getPorCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, MensageriaLicencaUsuarioRepository::getInstancia()->aItens)) {
      MensageriaLicencaUsuarioRepository::getInstancia()->aItens[$iCodigo] = new MensageriaLicencaUsuario($iCodigo);
    }
    return MensageriaLicencaUsuarioRepository::getInstancia()->aItens[$iCodigo];
  }

  /**
   * Retorna um usuario/dia
   * @param UsuarioSistema $oUsuario
   * @param $iDias
   * @return MensageriaLicencaUsuario
   */
  public static function getPorUsuarioDia(UsuarioSistema $oUsuario, $iDias) {

    foreach (MensageriaLicencaUsuarioRepository::getInstancia()->aItens as $iCodigo => $oMensageriaLicencaUsuario){

      if ($oMensageriaLicencaUsuario->getUsuario()->getIdUsuario() == $oUsuario->getIdUsuario() &&
          $oMensageriaLicencaUsuario->getDias() == $iDias){
        return MensageriaLicencaUsuarioRepository::getInstancia()->aItens[$iCodigo];
      }
    }
    $sWhere  = "    am16_usuario = {$oUsuario->getIdUsuario()} ";
    $sWhere .= "and am16_dias    = {$iDias} ";
    $oDaoMensageriaLicencaUsuario = db_utils::getDao('mensagerialicenca_db_usuarios');
    $sSqlBuscaUsuario = $oDaoMensageriaLicencaUsuario->sql_query_file(null, 'am16_sequencial', null, $sWhere);
    $rsBuscaUsuario   = $oDaoMensageriaLicencaUsuario->sql_record($sSqlBuscaUsuario);
    if (!$rsBuscaUsuario || $oDaoMensageriaLicencaUsuario->numrows == "0") {
      return false;
    }
    $iCodigo = db_utils::fieldsMemory($rsBuscaUsuario, 0)->am16_sequencial;
    MensageriaLicencaUsuarioRepository::adicionarMensageriaLicencaUsuario(new MensageriaLicencaUsuario($iCodigo));
    return MensageriaLicencaUsuarioRepository::getPorCodigo($iCodigo);
  }

  /**
   * Retorna a instancia da classe
   * @return MensageriaLicencaUsuarioRepository
   */
  protected static function getInstancia() {

    if (self::$oInstance == null) {
      self::$oInstance = new MensageriaLicencaUsuarioRepository();
    }
    return self::$oInstance;
  }

  /**
   * @param MensageriaLicencaUsuario $oMensageriaLicencaUsuario
   */
  public static function adicionarMensageriaLicencaUsuario(MensageriaLicencaUsuario $oMensageriaLicencaUsuario) {

    if (!array_key_exists($oMensageriaLicencaUsuario->getCodigo(), MensageriaLicencaUsuarioRepository::getInstancia()->aItens)) {
      MensageriaLicencaUsuarioRepository::getInstancia()->aItens[$oMensageriaLicencaUsuario->getCodigo()] = $oMensageriaLicencaUsuario;
    }
    return;
  }

  /**
   * Retorna todos os registros vinculados para a mensagem
   * @return MensageriaLicencaUsuario[]
   * @throws BusinessException
   */
  public static function getColecaoMensageriaLicencaUsuario() {

    $oDaoMensageriaUsuario = db_utils::getDao('mensagerialicenca_db_usuarios');
    $sSqlBuscaUsuario      = $oDaoMensageriaUsuario->sql_query_file(null, "am16_sequencial", "am16_dias, am16_usuario");
    $rsBuscaUsuario        = $oDaoMensageriaUsuario->sql_record($sSqlBuscaUsuario);
    if (!$oDaoMensageriaUsuario || $oDaoMensageriaUsuario->numrows == 0) {
      throw new BusinessException("Impossível carregar os destinatários parametrizados.");
    }

    for ($iRowUsuario = 0; $iRowUsuario < $oDaoMensageriaUsuario->numrows; $iRowUsuario++) {

      $iCodigo = db_utils::fieldsMemory($rsBuscaUsuario, $iRowUsuario)->am16_sequencial;
      MensageriaLicencaUsuarioRepository::adicionarMensageriaLicencaUsuario(new MensageriaLicencaUsuario($iCodigo));
    }
    return MensageriaLicencaUsuarioRepository::getInstancia()->aItens;
  }
}