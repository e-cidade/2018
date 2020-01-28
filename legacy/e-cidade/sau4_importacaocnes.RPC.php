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
define( 'MENSAGENS_SAU4_IMPORTACAOCNES_RPC', 'saude.ambulatorial.sau4_importacaocnes_RPC.' );

require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro      = false;

$iInstituicao = db_getsession( 'DB_instit' );

try {

  switch( $oParam->sExecuta ) {

    /**
     * Processa as informações de um arquivo XML, informando seu caminho
     * Com base nas tags do XML, verifica se os estabelecimentos do arquivo estão cadastrados como UPS, através do CNES,
     * atualizando as informações.
     * Caso não tenha sido encontrado o CNES, retorna um array com estes não encontrados e os departamentos do e-cidade
     * que não estão cadastrados como UPS ou não possuem CNES
     */
    case 'processar':

      if( !isset( $oParam->sCaminhoArquivo ) || empty( $oParam->sCaminhoArquivo ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'caminho_nao_informado' ) );
      }

      if( strtolower( pathinfo( $oParam->sCaminhoArquivo, PATHINFO_EXTENSION ) ) != 'xml' ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'arquivo_invalido' ) );
      }

      db_inicio_transacao();

      $oRetorno->aCNES    = array();
      $oLogInconsistencia = new DBLog( 'JSON', 'tmp/log_inconsistencia_cnes' );
      $oImportacaoCNES    = new ImportacaoCNES( $oParam->sCaminhoArquivo, $oLogInconsistencia );
      $oImportacaoCNES->processar();

      $oRetorno->sMensagem          = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'importacao_sucesso' ) );
      $oRetorno->lTemInconsistencia = $oImportacaoCNES->temInconsistencia();

      if( count( $oImportacaoCNES->getUnidadesSemVinculo() ) == 0 && $oImportacaoCNES->temInconsistencia() ) {
        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'importacao_sucesso_inconsistencia' ) );
      }

      db_fim_transacao();

      if( count( $oImportacaoCNES->getUnidadesSemVinculo() ) == 0 ) {
        break;
      }

      $aCNESSemVinculo = array();

      foreach( $oImportacaoCNES->getUnidadesSemVinculo() as $iCNES => $oCNES ) {

        $oDadosCNES             = new stdClass();
        $oDadosCNES->iCodigo    = $iCNES;
        $oDadosCNES->sDescricao = urlencode( $oCNES->sNomeDepartamento );
        $oRetorno->aCNES[]      = $oDadosCNES;
      }

      $aCNESSemVinculo          = $oImportacaoCNES->getCNESVinculados();
      $oRetorno->aDepartamentos = array();
      $oDaoDBDepart             = new cl_db_depart();
      $sCamposDBDepart          = "coddepto, descrdepto, sd02_i_codigo, sd02_v_cnes";
      $sWhereDBDepart           = "     instit = {$iInstituicao}";
      $sWhereDBDepart          .= " AND coddepto <> 0";
      $sSqlDBDepart             = $oDaoDBDepart->sql_query_unidades( null, $sCamposDBDepart, 'descrdepto', $sWhereDBDepart );
      $rsDBDepart               = db_query( $sSqlDBDepart );

      if( !$rsDBDepart ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'erro_buscar_departamentos', $oErro ) );
      }

      if( pg_num_rows( $rsDBDepart ) == 0 ) {

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'nenhum_departamento_encontrado' ) );
        break;
      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'cnes_nao_encontrados' ) );

      $iTotalLinhas = pg_num_rows( $rsDBDepart );
      for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

        $oDadosDepartamentos = db_utils::fieldsMemory( $rsDBDepart, $iContador );

        if( trim( $oDadosDepartamentos->sd02_v_cnes ) != '' && in_array( $oDadosDepartamentos->sd02_v_cnes, $aCNESSemVinculo ) ) {
          continue;
        }

        $oDepartamentoRetorno             = new stdClass();
        $oDepartamentoRetorno->iCodigo    = $oDadosDepartamentos->coddepto;
        $oDepartamentoRetorno->sDescricao = urlencode( $oDadosDepartamentos->descrdepto );

        $oRetorno->aDepartamentos[] = $oDepartamentoRetorno;
      }

      break;

    /**
     * Processa os novos vínculos CNES/Departamento, conforme selecionado pelo usuário
     */
    case 'processarNovosVinculos':

      if( !isset( $oParam->sCaminhoArquivo ) || empty( $oParam->sCaminhoArquivo ) ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'caminho_nao_informado' ) );
      }

      if( strtolower( pathinfo( $oParam->sCaminhoArquivo, PATHINFO_EXTENSION ) ) != 'xml' ) {
        throw new ParameterException( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'arquivo_invalido' ) );
      }

      db_inicio_transacao();

      $oRetorno->aCNES    = array();
      $oLogInconsistencia = new DBLog( 'JSON', 'tmp/log_inconsistencia_cnes_novos' );
      $oImportacaoCNES    = new ImportacaoCNES( $oParam->sCaminhoArquivo, $oLogInconsistencia );
      $oImportacaoCNES->processarNovosVinculos( $oParam->aVinculos );

      $oRetorno->sMensagem          = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'novos_vinculos_realizados' ) );
      $oRetorno->lTemInconsistencia = false;

      if( $oImportacaoCNES->temInconsistencia() ) {

        $oRetorno->sMensagem          = urlencode( _M( MENSAGENS_SAU4_IMPORTACAOCNES_RPC . 'novos_vinculos_realizados_inconsistencia' ) );
        $oRetorno->lTemInconsistencia = true;
      }

      db_fim_transacao();

      break;
  }
} catch ( Exception $oErro ) {

	db_fim_transacao(true);
	$oRetorno->iStatus   = 2;
	$oRetorno->sMensagem = urlencode( $oErro->getMessage() );
  $oRetorno->erro      = true;
}

echo $oJson->encode($oRetorno);