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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_stdlibwebseller.php"));

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oErro               = new stdClass();
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define("MENSAGEM_EXPORTACAOHORUSRPC", "saude.farmacia.far4_exportacaohorus_RPC.");

$oData = new DBDate( date("Y-m-d") );
$iMes  = $oData->getMes() - 1;
$iAno  = $oData->getAno();

if( $iMes == 0 ) {

  $iMes = 12;
  $iAno = $iAno - 1;
}

try {

  $oDBCompetencia        = new DBCompetencia($iAno, $iMes);
  $oUnidadeProntoSocorro = new UnidadeProntoSocorro( db_getsession( 'DB_coddepto' ) );
  $oUsuarioSistema       = new UsuarioSistema(db_getsession('DB_id_usuario'));

  db_inicio_transacao();

  switch( $oParam->sExecucao ) {

    /**
     * Responsável por gerar os arquivos XML's afim de exportar os dados para o Horus
     */
    case 'exportarArquivos':

      if ( !isset( $oParam->aArquivos ) || count($oParam->aArquivos) == 0 ) {
        throw new ParameterException( _M( MENSAGEM_EXPORTACAOHORUSRPC . 'informe_arquivos' ) );
      }

      $oHorus = new IntegracaoHorus();

      foreach ( $oParam->aArquivos as $iArquivo ) {

        switch ( $iArquivo ) {

          case HorusArquivoBase::ARQUIVO_ENTRADA:

            $oArquivo = new HorusEntradaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
            break;

          case HorusArquivoBase::ARQUIVO_SAIDA:

            $oArquivo = new HorusSaidaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
            break;

          case HorusArquivoBase::ARQUIVO_DISPENSACAO:

            $oArquivo = new HorusDispensacaoMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
            break;

          default:

            throw new BusinessException( _M( MENSAGEM_EXPORTACAOHORUSRPC . 'tipo_arquivo_nao_encontrado' ) );
            break;
        }

        $oHorus->adicionarArquivo($oArquivo);
      }

      $aArquivosEnviados   = $oHorus->enviar();
      $oRetorno->aArquivos = $aArquivosEnviados;
      $oRetorno->sMensagem = urlencode( _M( MENSAGEM_EXPORTACAOHORUSRPC . 'arquivos_exportados_sucesso' ) );

      break;

    /**
     * Verifica a competência a ser exportada e valida a situação de cada um dos arquivos
     */
    case 'verificarCompetencia':

      $oUnidadeProntoSocorro  = new UnidadeProntoSocorro( db_getsession('DB_coddepto') );
      $oRetorno->sCompetencia = $iMes < 10 ? "0{$iMes}/{$iAno}" : "{$iMes}/{$iAno}";

      $oRetorno->aArquivos = array();

      $oEntrada     = new HorusEntradaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
      $oSaida       = new HorusSaidaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
      $oDispensacao = new HorusDispensacaoMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );

      $oRetorno->aArquivos[] = retornaObjeto( $oEntrada, HorusArquivoBase::ARQUIVO_ENTRADA, HorusEntradaMedicamento::TIPO );
      $oRetorno->aArquivos[] = retornaObjeto( $oSaida, HorusArquivoBase::ARQUIVO_SAIDA, HorusSaidaMedicamento::TIPO );
      $oRetorno->aArquivos[] = retornaObjeto( $oEntrada, HorusArquivoBase::ARQUIVO_DISPENSACAO, HorusDispensacaoMedicamento::TIPO );
      break;

    /**
     * Realiza o pré-processamento dos arquivos, verificando se existe inconsistências para envio, tanto no ecidade
     * quanto no Hórus( para os casos do arquivo já ter sido enviado )
     */
    case 'validarArquivos':

      $oHorusEntradaMedicamento     = new HorusEntradaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
      $oHorusSaidaMedicamento       = new HorusSaidaMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );
      $oHorusDispensacaoMedicamento = new HorusDispensacaoMedicamento( $oDBCompetencia, $oUnidadeProntoSocorro, $oUsuarioSistema );

      $oHorusEntradaMedicamento->preProcessar();
      $oHorusSaidaMedicamento->preProcessar();
      $oHorusDispensacaoMedicamento->preProcessar();

      $oIntegracaoHorus = new IntegracaoHorus();
      $oIntegracaoHorus->adicionarArquivo($oHorusEntradaMedicamento);
      $oIntegracaoHorus->adicionarArquivo($oHorusSaidaMedicamento);
      $oIntegracaoHorus->adicionarArquivo($oHorusDispensacaoMedicamento);

      $oIntegracaoHorus->preProcessar();

      $oRetorno->lTemInconsistenciasEntradaMedicamento     = false;
      $oRetorno->lTemInconsistenciasSaidaMedicamento       = false;
      $oRetorno->lTemInconsistenciasDispensacaoMedicamento = false;

      if( $oHorusEntradaMedicamento->temInconsistencia() ) {
        $oRetorno->lTemInconsistenciasEntradaMedicamento = true;
      }

      if( $oHorusSaidaMedicamento->temInconsistencia() ) {
        $oRetorno->lTemInconsistenciasSaidaMedicamento = true;
      }

      if( $oHorusDispensacaoMedicamento->temInconsistencia() ) {
        $oRetorno->lTemInconsistenciasDispensacaoMedicamento = true;
      }

      break;
  }

  db_fim_transacao();

} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

function retornaObjeto( $oDadosArquivo, $iTipoArquivo, $sNomeTipoArquivo ) {

  $aCoresSituacaos = array(
                            HorusArquivoBase::SEM_DADOS            => "#989898",
                            HorusArquivoBase::AGUARDANDO_ENVIO     => "#1165a0",
                            HorusArquivoBase::PARCIALMENTE_ENVIADO => "#1165a0",
                            HorusArquivoBase::AGUARDANDO_HORUS     => "#1165a0",
                            HorusArquivoBase::INCONSISTENTE        => "#1165a0",
                            HorusArquivoBase::CONCLUIDO            => "#51a011",
                          );

  $oRetornaDadosArquivo = new stdClass();
  $oRetornaDadosArquivo->iTipo         = $iTipoArquivo;
  $oRetornaDadosArquivo->iSituacao     = $oDadosArquivo->situacaoArquivoCompetencia( $iTipoArquivo );
  $oRetornaDadosArquivo->sSituacao     = urlencode( $oDadosArquivo->getSituacaoArquivo($oRetornaDadosArquivo->iSituacao) );
  $oRetornaDadosArquivo->sCorSituacao  = urlencode($aCoresSituacaos[ $oRetornaDadosArquivo->iSituacao ]);
  $oRetornaDadosArquivo->lPermiteEnvio = $oDadosArquivo->permiteEnvio( $iTipoArquivo );
  $oRetornaDadosArquivo->sTipoArquivo  = urlencode($sNomeTipoArquivo);
  return $oRetornaDadosArquivo;
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);