<?php

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$oUsuario      = new UsuarioSistema( db_getsession("DB_id_usuario") );
$oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo( db_getsession("DB_coddepto") );
try {

  switch ( $oParam->exec ) {

    case 'departamentoIsLaboratorio':

      if ( !empty($oParam->iDepartamentoLogado) ) {
        $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo( $oParam->iDepartamentoLogado );
      }
      $oRetorno->lIsLaboratorio = Laboratorio::departamentoIsLaboratorio( $oDepartamento );
      break;

    case 'usuarioIsTecnicoLaboratorio' :

      if ( !empty($oParam->iDepartamentoLogado) ) {
        $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo( $oParam->iDepartamentoLogado );
      }
      if ( !empty($oParam->iUsuario) ) {
        $oUsuario = new UsuarioSistema( $oParam->iUsuario );
      }
      $oRetorno->lIsVinculado  = Laboratorio::usuarioIsTecnicoLaboratorio( $oDepartamento, $oUsuario );
      break;

    case 'getLaboratorioByDepartamento' :

      if ( !empty($oParam->iDepartamentoLogado) ) {
        $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo( $oParam->iDepartamentoLogado );
      }

      $oLaboratorio = Laboratorio::getLaboratorioByDepartamento( $oDepartamento) ;

      if ( !$oLaboratorio instanceof Laboratorio) {
        throw new BusinessException ("Departamento informado não é um laboratório.");
      }

      $oRetorno->iLaboratorio = $oLaboratorio->getCodigo();
      $oRetorno->sLaboratorio = $oLaboratorio->getDescricao();
      break;
  }
} catch ( Exception $oError ) {

}

echo $oJson->encode($oRetorno);