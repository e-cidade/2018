<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                       = new services_json();
$oParametros                 = $oJson->decode( str_replace("\\", "", $_POST["json"] ) );
$oRetorno                    = new stdClass();
$oRetorno->erro              = false;
$oRetorno->sMensagem         = '';
$oRetorno->aInconsistencias  = array();
$oRetorno->aCertidoesValidas = array();

$oDataMovimentacao = new DBDate( $oParametros->dDataMovimentacao );
$sDataMovimentacao = $oDataMovimentacao->getDate();

define("MENSAGENS", "tributario.divida.div4_movimentacaocda.");

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "validarMovimentacao":

      if ($oParametros->iCertidaoInicial > $oParametros->iCertidaoFinal) {

        $iCertidaoAuxiliar             = $oParametros->iCertidaoFinal;
        $oParametros->iCertidaoFinal   = $oParametros->iCertidaoInicial;
        $oParametros->iCertidaoInicial = $iCertidaoAuxiliar;
      }

      for ($iCodigo = $oParametros->iCertidaoInicial; $iCodigo <= $oParametros->iCertidaoFinal; $iCodigo++) {

        $oCertidao = new Certidao($iCodigo);

        if ( is_null( $oCertidao->getSequencial() ) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $iCodigo,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "certidao_inexistente" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $aCertidaoArrecad = $oCertidao->getArrecad("certid.v13_certid");
        if ( empty($aCertidaoArrecad) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $iCodigo,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "certidao_fechada" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $oCertidaoCartorio = new CertidCartorio( null, $oCertidao->getSequencial() );
        $iCodigoCertidao   = $oCertidaoCartorio->getCertidao();
        if ( empty($iCodigoCertidao) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $iCodigo,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "certidao_nao_cobrada" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        if ( !$oCertidao->validaDataMovimentacao( $oDataMovimentacao ) ) {

          $sDataUltimaMovimentacao                    = $oCertidao->getDataUltimaMovimentacao();
          $oStdMensagemErro                           = new stdClass();
          $oStdMensagemErro->data_ultima_movimentacao = $sDataUltimaMovimentacao;

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $iCodigo,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "data_movimentacao_invalida", $oStdMensagemErro ) ),
            "lIsErro"         => true
          );

          continue;
        }

        if ( !$oCertidao->validaTipoMovimentacao( $oParametros->iTipoMovimentacao ) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $iCodigo,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "tipo_movimentacao_invalida" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $oRetorno->aCertidoesValidas[] = $iCodigo;
      }

      break;

    case "processaMovimentacao":

      foreach ($oParametros->aCertidoes as $iCertidao) {

        try {

          $oCertidaoCartorio   = new CertidCartorio( null, $iCertidao );
          $oCertidMovimentacao = new CertidMovimentacao();

          $oCertidMovimentacao->setCertidCartorio( $oCertidaoCartorio );
          $oCertidMovimentacao->setDataMovimentacao( $oDataMovimentacao );
          $oCertidMovimentacao->setTipo( $oParametros->iTipo );
          $oCertidMovimentacao->incluir();

          $oRetorno->sMensagem = urlencode( _M( MENSAGENS. "sucesso_movimentacao" ) );
        } catch ( Exception $oErro ) {
          throw new Exception( _M( MENSAGENS . "erro_inclusao_movimentacao" ) );
        }
      }
      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode( $eErro->getMessage() );
}
echo $oJson->encode($oRetorno);