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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("fpdf151/PDFDocument.php"));
require_once(modification("fpdf151/PDFTable.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("fpdf151/pdfCertidao.php"));

$oJson                      = new services_json();
$oParametros                = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno                   = new stdClass();
$oRetorno->erro             = false;
$oRetorno->aInconsistencias = array();
$oRetorno->aRecibosEmissao  = array();

$oDataVencimento            = new DBDate( $oParametros->dataVencimento );
$iAnousu                    = db_getsession("DB_anousu");

define("MENSAGENS", "tributario.divida.div4_emissaorecibocda.");

try {

  switch ($oParametros->sExecucao) {

    case "validarReciboCDA":

      db_inicio_transacao();

      $oCartorio = new Cartorio($oParametros->cartorio);
      $oDataUsu  = new DBDate( date("Y-m-d", db_getsession("DB_datausu") ) );

      $aCertidoesInexistentes = array();

      if ( $oParametros->certidaoInicial > $oParametros->certidaoFinal ) {

        $iCertidaoAuxiliar            = $oParametros->certidaoFinal;
        $oParametros->certidaoFinal   = $oParametros->certidaoInicial;
        $oParametros->certidaoInicial = $iCertidaoAuxiliar;
      }

      for ( $i = $oParametros->certidaoInicial; $i <= $oParametros->certidaoFinal; $i++ ) {

        $oCertidao = new Certidao($i);

        if ( is_null( $oCertidao->getSequencial() ) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $i,
            "sInconsistencia" => urlencode( _M( MENSAGENS . "certidao_inexistente" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $aCertidaoArrecad = $oCertidao->getArrecad("certid.v13_certid");

        if (empty($aCertidaoArrecad)) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $i,
            "sInconsistencia" => urlencode(_M( MENSAGENS . "certidao_fechada" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $iCertidaoInicial = $oCertidao->getInicial();

        if ( !empty($iCertidaoInicial) ) {

          $oRetorno->aInconsistencias[] = array(
            "iCertidao"       => $i,
            "sInconsistencia" => urlencode(_M( MENSAGENS . "certidao_com_inicial" ) ),
            "lIsErro"         => true
          );

          continue;
        }

        $oCertidaoCartorio = new CertidCartorio( null , $oCertidao->getSequencial() );

        $oCertidaoCartorioRecibo     = "";
        $iCertidaoCartorioSequencial = $oCertidaoCartorio->getSequencial();

        if ( !empty($iCertidaoCartorioSequencial) ) {
          $oCertidaoCartorioRecibo = $oCertidaoCartorio->buscaRecibo();
        }

        if ( !empty($oCertidaoCartorioRecibo) ) {

          if ( $oCartorio->getSequencial() != $oCertidaoCartorio->getCartorio()->getSequencial() ) {

            $oRetorno->aInconsistencias[] = array(
              "iCertidao"       => $i,
              "sInconsistencia" => urlencode( _M( MENSAGENS . "erro_cartorio_diferente"  ) ),
              "lIsErro"         => true
            );

            continue;
          }

          $aAbatimentosCertidao = $oCertidao->getAbatimento();

          if ( !empty($aAbatimentosCertidao) ) {

            $oRetorno->aInconsistencias[] = array(
              "iCertidao"       => $i,
              "sInconsistencia" => urlencode( _M( MENSAGENS . "pagamento_parcial"  ) ),
              "lIsErro"         => false
            );

            $oRetorno->aRecibosEmissao[] = (string) $i;
            continue;
          }

          $oDataRecibo = new DBDate( $oCertidaoCartorioRecibo->k00_dtpaga );

          if ( DBDate::calculaIntervaloEntreDatas( $oDataUsu, $oDataRecibo, 'd' ) < 0 ) {

            $oRetorno->aInconsistencias[] = array(
              "iCertidao"       => $i,
              "sInconsistencia" => urlencode( _M( MENSAGENS . "recibo_valido" ) ),
              "lIsErro"         => false
            );
          }
        }

        $oRetorno->aRecibosEmissao[] = (string) $i;
      }

      break;

    case "emiteReciboCDA":

      $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
      $oCartorio         = new Cartorio($oParametros->iCartorio);
      $oDataEmissao      = new DBDate( date("Y-m-d",db_getsession("DB_datausu")) );
      $aDadosRelatorio   = array();
      $aArquivosCda      = array();
      $aArquivosRecibo   = array();

      db_inicio_transacao();

      foreach ($oParametros->aCertidoes as $iCertidao) {

        try {

          $oCertidao              = new Certidao($iCertidao);

          /**
           * Emitimos o recibo para certidão
           */
          $oRetornoReciboCertidao = $oCertidao->gerarReciboCobrancaEmCartorio( $oInstituicao,
                                                                               $oDataVencimento,
                                                                               $oDataEmissao,
                                                                               $iAnousu );
          $aArquivosRecibo[]      = $oRetornoReciboCertidao->sNomeArquivo;
          $aDadosRelatorio[]      = $oRetornoReciboCertidao->aDadosRelatorio;

          /*
           * Gera CDA
           */
          try {

            $oGeradorCda        = new GeradorCDA();
            $oCda               = new cda($iCertidao);
            $sOrdenacao         = "v14_certid";

            $oInstituicao      = new Instituicao(db_getsession('DB_instit'));
            $oParametrosDivida = db_stdClass::getParametro("pardiv", array($oInstituicao->getSequencial()));

            if (isset($oParametrosDivida[0]->v04_cobrarjurosmultacda) && $oParametrosDivida[0]->v04_cobrarjurosmultacda == 't') {
              $oGeradorCda->setDataRecalculoJurosMulta($oDataVencimento);
            }

            $oGeradorCda->gerar($oCda->getTipo(), $iCertidao, $iCertidao, false, $sOrdenacao, true);

            $sNomeArquivoCda    =  "tmp/cda_{$iCertidao}_".time().".pdf";
            $sNomeArquivoCda    = $oGeradorCda->escreverArquivo($sNomeArquivoCda);
            $aArquivosCda[]     = $sNomeArquivoCda;
          } catch (Exception $oError) {
            throw new Exception(_M( MENSAGENS . "erro_pdf_cda"));
          }

          try{

            $oCertidaoCartorio = new CertidCartorio( null, $oCertidao->getSequencial(), $oCartorio->getSequencial() );

            if ( is_null($oCertidaoCartorio->getSequencial()) ) {

              $oCertidaoCartorio->setCartorio($oCartorio);
              $oCertidaoCartorio->setCertidao($oCertidao);
              $oCertidaoCartorio->incluir(null);
            }
          } catch (Exception $oErro) {
            throw new BusinessException( _M( MENSAGENS . "erro_incluir_certidcartorio" ) );
          }

          try {

            $oCertidCartorioReciboPaga = new CertidCartorioReciboPaga();
            $oCertidCartorioReciboPaga->setCertidCartorio($oCertidaoCartorio);
            $oCertidCartorioReciboPaga->setNumnov($oRetornoReciboCertidao->aDadosRelatorio['iNumnov']);
            $oCertidCartorioReciboPaga->incluir(null);
          } catch (Exception $oErro) {
            throw new BusinessException( _M( MENSAGENS . "erro_incluir_certidcartoriorecibopaga" ) );
          }

          try {

            $oCertidMovimentacao = new CertidMovimentacao();
            $oCertidMovimentacao->setCertidCartorio( $oCertidaoCartorio );
            $oCertidMovimentacao->setDataMovimentacao( $oDataEmissao );
            $oCertidMovimentacao->setTipo( CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO );
            $oCertidMovimentacao->incluir( null );
          } catch (Exception $oErro) {
            throw new BusinessException( _M( MENSAGENS . "erro_incluir_certidmovimentacao" ) );
          }
        } catch (BusinessException $oErro) {
          throw new Exception( $oErro->getMessage() );
        }
      }

      /**
       * Iniciamos geração do relatório
       */
      $oPdfRelatorio = new PDFTable();
      $oPdfRelatorio->setPercentWidth(true);
      $oPdfRelatorio->setHeaders( array( "Código da Certidão", "Identificação", "Código de Arrecadação", "Valor" ) );
      $oPdfRelatorio->setColumnsAlign( array( PDFDocument::ALIGN_CENTER,
                                              PDFDocument::ALIGN_LEFT,
                                              PDFDocument::ALIGN_CENTER,
                                              PDFDocument::ALIGN_RIGHT ) );
      $oPdfRelatorio->setColumnsWidth(array( "15", "55", "20", "10"));

      foreach ($aDadosRelatorio as $aDadoCdaRecibo) {

        $aLinha = array( $aDadoCdaRecibo['iCertidao'],
                         $aDadoCdaRecibo['sNome'],
                         $aDadoCdaRecibo['iArrecadacao'],
                         trim( $aDadoCdaRecibo['iValor'] ) );
        $oPdfRelatorio->addLineInformation( $aLinha );
      }

      $oPdfDocument = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
      $oPdfDocument->addHeaderDescription("Relatório de Recibos por CDA");
      $oPdfDocument->addHeaderDescription("");
      $oPdfDocument->addHeaderDescription("Cartório: {$oCartorio->getDescricao()}");
      $oPdfDocument->addHeaderDescription("Data de Vencimento dos Recibos: {$oDataVencimento->getDate( DBDate::DATA_PTBR )}");
      $oPdfDocument->SetFillColor(235);
      $oPdfDocument->SetFontSize(7);
      $oPdfDocument->open();

      $oPdfRelatorio->printOut($oPdfDocument,  false);

      $sNomeArquivoRelatorio = "relatorio_recibo_cda_" . time();
      $sNomeArquivoRelatorio = $oPdfDocument->savePDF($sNomeArquivoRelatorio);

      /**
       * Gera zip com todos os arquivos da cobranca extrajudicial manual
       */

      $oZip = new ZipArchive();
      $sZipFile =  "tmp/cobranca_extrajudicial_".time().".zip";

      if ($oZip->open($sZipFile, ZIPARCHIVE::CREATE) !== TRUE) {
        throw new Exception( _M( MENSAGENS . "erro_zip" ) );
      }

      foreach ($aArquivosRecibo as $sArquivo) {
        $oZip->addFile("{$sArquivo}", str_replace('tmp/', '', $sArquivo));
      }

      foreach ($aArquivosCda as $sArquivo) {
        $oZip->addFile("{$sArquivo}", str_replace('tmp/', '', $sArquivo));
      }

      $oZip->close();

      if (!file_exists($sZipFile)) {
        throw new Exception( _M( MENSAGENS . "erro_zip" ) );
      }

      /**
       * Retorna arquivos gerados
       */
      $oRetorno->sNomeArquivoZipCobranca = $sZipFile;
      $oRetorno->sNomeArquivoRelatorio   = $sNomeArquivoRelatorio;
      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'emissao_sucesso' ) );

      db_fim_transacao(false);

      break;
  }


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);