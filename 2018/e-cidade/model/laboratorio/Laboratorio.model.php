<?php

/**
 * Class Laboratorio
 * @packge laboratorio
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class Laboratorio {

  private $iCodigo;

  private $sLaboratorio;

  public function __construct( $iCodigo = null ) {

    if ( !empty( $iCodigo ) ) {

      $oDaoLaboratorio = new cl_lab_laboratorio();
      $sSqlLaboratorio = $oDaoLaboratorio->sql_query_file( $iCodigo );
      $rsLaboratorio   = db_query( $sSqlLaboratorio );

      if ( $rsLaboratorio && pg_num_rows( $rsLaboratorio ) == 0 ) {
        return false;
      }
      $oDadosLaboratorio  = db_utils::fieldsMemory( $rsLaboratorio, 0 );
      $this->iCodigo      = $oDadosLaboratorio->la02_i_codigo;
      $this->sLaboratorio = $oDadosLaboratorio->la02_c_descr;
    }
    return true;
  }

  /**
   * Valida se o departamento informado esta vinculádo a um laboratório
   *
   * @param DBDepartamento $oDepartamento
   * @return bool
   */
  static public function departamentoIsLaboratorio(DBDepartamento $oDepartamento) {

    $sWhere        = " la03_i_departamento = {$oDepartamento->getCodigo()} ";
    $oDaoLabDepart = new cl_lab_labdepart();
    $sSqlDepart    = $oDaoLabDepart->sql_query_file(null, "1", null, $sWhere);
    $rsDepart      = db_query( $sSqlDepart ) ;

    if ($rsDepart && pg_num_rows( $rsDepart ) == 0 ) {
      return false;
    }
    return true;
  }

  /**
   * Validamos se o Usuário informado, esta vinculado ao laboratório
   * @param DBDepartamento $oDepartamento
   * @param UsuarioSistema $oUsuario
   * @return bool
   */
  static public function usuarioIsTecnicoLaboratorio(DBDepartamento $oDepartamento, UsuarioSistema $oUsuario) {

    $sWhere      = " id_usuario = {$oUsuario->getIdUsuario()} and la03_i_departamento = {$oDepartamento->getCodigo()} ";
    $oDaoLabResp = new cl_lab_labresp();
    $sSqlLabResp = $oDaoLabResp->sql_query_responsavel(null, "1", null, $sWhere);
    $rsLabResp   = db_query($sSqlLabResp);

    if ($rsLabResp && pg_num_rows( $rsLabResp ) == 0 ) {
      return false;
    }
    return true;
  }

  static function getLaboratorioByDepartamento(DBDepartamento $oDepartamento) {

    $sWhere        = " la03_i_departamento = {$oDepartamento->getCodigo()} ";
    $oDaoLabDepart = new cl_lab_labdepart();
    $sSqlDepart    = $oDaoLabDepart->sql_query_file(null, "la03_i_laboratorio", null, $sWhere);
    $rsDepart      = db_query( $sSqlDepart ) ;

    if ($rsDepart && pg_num_rows( $rsDepart ) == 0 ) {
      return false;
    }

    return new Laboratorio(db_utils::fieldsMemory($rsDepart, 0)->la03_i_laboratorio);

  }

  /**
   * retorna o código do laboratório
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * define o nome do Laboratório
   * @param string $sLaboratorio
   */
  public function setDescricao($sLaboratorio) {
    $this->sLaboratorio = $sLaboratorio;
  }

  /**
   * Retorna o nome do laboratório
   * @return string
   */
  public function getDescricao() {
    return $this->sLaboratorio;
  }
}