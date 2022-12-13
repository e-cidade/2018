<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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
     * Busca os exames de uma requisição que estão conferidos '7 - Conferido'
     * @param integer $oParam->iRequisicao - Código da requisição
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
     * Salva as informações da entrega de um resultado
     * @param array   $oParam->aExames - Código requiitem dos exames da requisição
     * @param integer $oParam->iTipoDocumento - Código do tipo de documento
     * @param string  $oParam->sDocumento - Informação do documento
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