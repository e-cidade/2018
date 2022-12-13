<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

define( "MENSAGENS_RESULTADOEXAME_RPC", "saude.laboratorio.lab4_resultadoexame_RPC." );

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$iUsuario            = db_getsession( "DB_id_usuario" );

try {

  db_inicio_transacao();

  switch( $oParam->sExecucao ) {

    /**
     * Busca os tipos de documentos cadastrados
     */
    case 'tiposDocumento':

      $oDaoTipoDocumento = new cl_lab_tipodocumento();
      $sSqlTipoDocumento = $oDaoTipoDocumento->sql_query_file();
      $rsTipoDocumento   = db_query( $sSqlTipoDocumento );

      if( !$rsTipoDocumento ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_RESULTADOEXAME_RPC . "erro_buscar_tipos_documentos", $oMensagem ) );
      }

      $iTotalTipoDocumento = pg_num_rows( $rsTipoDocumento );
      if( $iTotalTipoDocumento == 0 ) {
        throw new DBException( _M( MENSAGENS_RESULTADOEXAME_RPC . "tipos_documentos_nao_encontrados" ) );
      }

      $oRetorno->aTiposDocumento = db_utils::getCollectionByRecord( $rsTipoDocumento, false, false, true );

      break;

    /**
     * Busca os exames de uma requisi��o que est�o conferidos '7 - Conferido'
     * @param integer $oParam->iRequisicao - C�digo da requisi��o
     */
    case 'examesRequisicao':

      if( !isset( $oParam->iRequisicao ) || empty( $oParam->iRequisicao ) ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "requisicao_nao_informada" ) );
      }

      $oRetorno->aExames       = array();
      $oRequisicaoLaboratorial = new RequisicaoLaboratorial( $oParam->iRequisicao );

      foreach( $oRequisicaoLaboratorial->getRequisicoesDeExames() as $oRequisicaoExame ) {

        if( $oRequisicaoExame->getSituacao() != RequisicaoExame::CONFERIDO ) {
          continue;
        }

        $oDadosExame              = new stdClass();
        $oDadosExame->iCodigoItem = $oRequisicaoExame->getCodigo();
        $oDadosExame->iExame      = $oRequisicaoExame->getExame()->getCodigo();
        $oDadosExame->sExame      = urlencode( $oRequisicaoExame->getExame()->getNome() );
        $oRetorno->aExames[]      = $oDadosExame;
      }

      break;

    /**
     * Salva as informa��es da entrega de um resultado
     * @param array   $oParam->aExames - C�digo requiitem dos exames da requisi��o
     * @param integer $oParam->iTipoDocumento - C�digo do tipo de documento
     * @param string  $oParam->sDocumento - Informa��o do documento
     * @param string  $oParam->sRetirado - Nome de quem retirou o resultado
     */
    case 'salvarEntregaResultado':

      if( !isset( $oParam->aExames ) || count( $oParam->aExames ) == 0 ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "exames_nao_informados" ) );
      }

      if( !isset( $oParam->iCgs ) || empty( $oParam->iCgs ) ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "cgs_nao_informado" ) );
      }

      if( !isset( $oParam->iTipoDocumento ) || empty( $oParam->iTipoDocumento ) ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "tipo_documento_nao_informado" ) );
      }

      if( !isset( $oParam->sDocumento ) || empty( $oParam->sDocumento ) ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "documento_nao_informado" ) );
      }

      if( !isset( $oParam->sRetirado ) || empty( $oParam->sRetirado ) ) {
        throw new BusinessException( _M( MENSAGENS_RESULTADOEXAME_RPC . "retirado_nao_informado" ) );
      }

      foreach( $oParam->aExames as $iExame ) {

        $oRequisicaoExame       = new RequisicaoExame( $iExame );
        $oEntregaResultadoExame = new EntregaResultadoExame();
        $oEntregaResultadoExame->setRequisicaoExame( $oRequisicaoExame );
        $oEntregaResultadoExame->setTipoDocumento( $oParam->iTipoDocumento );
        $oEntregaResultadoExame->setDocumento( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDocumento ) );
        $oEntregaResultadoExame->setCgs( new Cgs( $oParam->iCgs ) );
        $oEntregaResultadoExame->setUsuarioSistema( UsuarioSistemaRepository::getPorCodigo( $iUsuario ) );
        $oEntregaResultadoExame->setData( new DBDate( date( "Y-m-d", db_getsession( "DB_datausu" ) ) ) );
        $oEntregaResultadoExame->setHora( date( "H:i" ) );
        $oEntregaResultadoExame->setRetirado( db_stdClass::normalizeStringJsonEscapeString( $oParam->sRetirado ) );
        $oEntregaResultadoExame->salvar();

        $oRequisicaoExame->setSituacao( RequisicaoExame::ENTREGUE );
        $oRequisicaoExame->salvar();
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_RESULTADOEXAME_RPC . "entrega_salva" ) );

      break;
  }

  db_fim_transacao();
} catch( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
}

echo $oJson->encode( $oRetorno );