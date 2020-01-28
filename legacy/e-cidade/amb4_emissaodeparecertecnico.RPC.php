<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("std/DBLargeObject.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

$oDaoParecerTecnico                 = db_utils::getDao("parecertecnico");
$oDaoLicencaEmpreendimento          = db_utils::getDao("licencaempreendimento");
$oDaoEmpreendimentoAtividadeImpacto = db_utils::getDao("empreendimentoatividadeimpacto");
$oDaoDocumentoTemplate              = db_utils::getDao("db_documentotemplate");
$oParecerTecnico                    = new ParecerTecnico();
$oLicencaEmpreendimento             = new LicencaEmpreendimento();

define("MENSAGENS", "tributario.meioambiente.amb4_emissaodeparecertecnico.");

define("TIPO_LICENCA_PREVIA",     1);
define("TIPO_LICENCA_INSTALACAO", 2);
define("TIPO_LICENCA_OPERACAO",   3);

define("TIPO_EMISSAO_NOVA",        1);
define("TIPO_EMISSAO_PRORROGACAO", 2);
define("TIPO_EMISSAO_RENOVACAO",   3);

try {

  switch ($oParametros->sExecucao) {

    case "emitirParecer":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'empreendimento_obrigatorio'));
      }

      if (empty($oParametros->iCodigoProtocolo)) {
        throw new Exception(_M( MENSAGENS . 'processo_obrigatorio'));
      }

      /**
       * Quando Parecer é favorável
       */
      if ( $oParametros->lFavoravel == 'true' ) {

        if (empty($oParametros->iTipoLicenca)) {
          throw new Exception(_M( MENSAGENS . 'tipo_licenca_obrigatorio'));
        }

        if (empty($oParametros->iTipoEmissao)) {
          throw new Exception(_M( MENSAGENS . 'tipo_emissao_obrigatorio'));
        }

        if (empty($oParametros->sDataEmissao)) {
          throw new Exception(_M( MENSAGENS . 'data_emissao_obrigatorio'));
        }

        if (empty($oParametros->sDataVencimento)) {
          throw new Exception(_M( MENSAGENS . 'data_vencimento_obrigatorio'));
        }

        $aDataEmissao    = explode("/", $oParametros->sDataEmissao);
        $aDataVencimento = explode("/", $oParametros->sDataVencimento);

        if ($aDataEmissao[2] > $aDataVencimento[2]) {
          throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
        } else if ($aDataEmissao[2] == $aDataVencimento[2]) {

          if($aDataEmissao[1] > $aDataVencimento[1]) {
            throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
          } else if ($aDataEmissao[1] == $aDataVencimento[1]) {

            if ($aDataEmissao[0] > $aDataVencimento[0]) {
              throw new Exception( _M( MENSAGENS . 'datas_invalidas' ) );
            }
          }
        }
      }

      $sWhere            = " am06_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sWhere           .= " and am06_principal  = true";
      $sSql              = $oDaoEmpreendimentoAtividadeImpacto->sql_query_file(null, "*", null, $sWhere);
      $rsRecordAtividade = $oDaoEmpreendimentoAtividadeImpacto->sql_record($sSql);
      $oAtividade        = db_utils::getCollectionByRecord($rsRecordAtividade);

      if (empty($oAtividade)) {
        throw new Exception(_M( MENSAGENS . 'erro_atividade_obrigatorio' ));
      }

      $oParecerTecnico->setEmpreendimento( new Empreendimento( $oParametros->iCodigoEmpreendimento ) );
      $oParecerTecnico->setProtProcesso( $oParametros->iCodigoProtocolo );
      $oParecerTecnico->setFavoravel( $oParametros->lFavoravel );
      $sObservacao = '';
      if( !empty($oParametros->sObservacao) ){
        $sObservacao =  addslashes( db_stdClass::normalizeStringJsonEscapeString( $oParametros->sObservacao ) );
      }
      $oParecerTecnico->setObservacao( $sObservacao );

      /**
       * Quando parecer é favorável
       */
      if ( $oParametros->lFavoravel == 'true' ) {

        $oParecerTecnico->setTipoLicenca( new TipoLicenca( $oParametros->iTipoLicenca ) );
        $oParecerTecnico->setDataEmissao( $oParametros->sDataEmissao );
        $oParecerTecnico->setDataVencimento( $oParametros->sDataVencimento );
        $oParecerTecnico->setDataGeracao( date('Y-m-d') );
        $oParecerTecnico->verificaParecerAnterior( $oParametros->iTipoEmissao );
      }

      db_inicio_transacao();

      $oParecerTecnico->incluir();
      $iCodigoParecerTecnico = $oParecerTecnico->getSequencial();

      /**
       * Incluimos a licença para o Parecer
       */
      if ( $oParametros->lFavoravel == 'true' ) {

        $oLicencaEmpreendimento->setParecerTecnico($oParecerTecnico);
        $oLicencaEmpreendimento->incluir();
      }

      db_fim_transacao();

      /**
       * Gera arquivo do parecer tecnico
       */
      $sArquivoAgt        = "meioambiente/parecer_tecnico.agt";

      $sArquivoSxwSaida   = "tmp/parecertecnico_".date("YmdHmi").".sxw";
      $sArquivoPdfParecer = "tmp/parecertecnico_".date("YmdHmi").".pdf";

      ini_set("error_reporting","E_ERROR");

      $oAgata     = new cl_dbagata( $sArquivoAgt );
      $oApiAgata  = $oAgata->api;

      /**
       * Passamos as descrições das condicionantes e criamos uma
       * única string a partir delas
       */
      $sCondicionantesVinculadas = "";

      if( !empty($oParametros->aCondicionantes) ){
        foreach ($oParametros->aCondicionantes as $iIndice => $oCondicionante) {
          $sCondicionantesVinculadas .= $iIndice + 1 . " - " . addslashes( db_stdClass::normalizeStringJsonEscapeString( $oCondicionante->sDescricao ) ) . "\n\n";
        }
      }

      /**
       * Verificamos se o Parecer tem emissão anteriores do mesmo tipo, para que seja buscado o id
       * da licenca vinculado a estes. Caso não tenha, usamos o sequencial da Licença atual
       */
      $iCodigoLicencaEmpreendimento = $oParecerTecnico->getCodigoLicencaAnterior();
      if (is_null($iCodigoLicencaEmpreendimento)) {
        $iCodigoLicencaEmpreendimento = $oLicencaEmpreendimento->getSequencial();
      }

      $aParametrosDocumento = array( 'iCodigoParecerTecnico'      => $iCodigoParecerTecnico,
                                     '$sCondicionantesVinculadas' => "{$sCondicionantesVinculadas}",
                                     'numero_licenca_ambiental'   => $iCodigoLicencaEmpreendimento,
                                     'iCodigoLicenciamento'       => $iCodigoLicencaEmpreendimento
                              );

      /**
       * Verficamos o Parecer para setarmos o template a ser gerado
       *
       *   Favoravel:    TemplateTipo -> 52 TemplatePadrao -> 55
       *   Desfavoravel: TemplateTipo -> 53 TemplatePadrao -> 56
       */
      $iCodigoTemplateTipo   = 52;
      if( $oParecerTecnico->getFavoravel() == 'false' ){
        $iCodigoTemplateTipo   = 53;
      }

      /**
       * Definimos o Path de saida do template e os parametros para o agata
       */
      $oApiAgata->setOutputPath( $sArquivoSxwSaida );
      foreach ($aParametrosDocumento as $sParametro => $sParametroValor ) {
        $oApiAgata->setParameter( $sParametro, $sParametroValor );
      }

      /**
       * Instancia Template
       * @var documentoTemplate
       */
      $oDocumentoTemplate = new documentoTemplate( $iCodigoTemplateTipo );

      /**
       * Geramos o documento utilizando as variaveis informadas
       */
      $lGeracaoArquivo = $oApiAgata->parseOpenOffice( $oDocumentoTemplate->getArquivoTemplate() );
      if ( !$lGeracaoArquivo ) {
        throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
      }

      /**
       * Convertemos o sxw em pdf para armazenarmos
       */
      $lConversao = db_stdClass::ex_oo2pdf( $sArquivoSxwSaida, $sArquivoPdfParecer );
      if ( !$lConversao ) {
        throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
      }

      if ( !file_exists($sArquivoPdfParecer) ) {
        throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
      }

      /**
       * Geramos um Blob vazio e gravamos o arquivo no banco
       */
      db_query($conn, "begin;");
      $iOid          = DBLargeObject::criaOID( true );
      $lSalvaArquivo = DBLargeObject::escrita( $sArquivoPdfParecer, $iOid );

      /**
       * Gravamos o arquivo no banco e atualizamos o oid na tabela de parecer
       */
      $oDaoParecerTecnico->am08_sequencial = $iCodigoParecerTecnico;
      $oDaoParecerTecnico->am08_arquivo    = $iOid;
      $oDaoParecerTecnico->alterar( $iCodigoParecerTecnico );

      if ( $oDaoParecerTecnico->erro_status == 0 && !$lSalvaArquivo ) {
        throw new FileException( _M( MENSAGENS . 'erro_gravar_parecer' ) );
      }

      /**
       * Incluimos as condicionantes juntamente com a atualização o oid
       */
      if( $oParecerTecnico->getFavoravel() == 'true' ){
        $oParecerTecnico->setCondicionantes( $oParametros->aCondicionantes );
      }
      db_query($conn, "commit;");

      if( file_exists($sArquivoSxwSaida) ){
        unlink($sArquivoSxwSaida);
      }

      /**
       * Geramos a licença caso o parecer seja favoravel
       */
      if( $oParecerTecnico->getFavoravel() == 'true' ){

        /**
         * Fechamos a conexão do agata
         * @todo  rever esta logica
         */
        pg_close();

        /**
         * Gera arquivo da Licença Ambiental
         */
        $sArquivoAgtLicenca = "meioambiente/licencas_meio_ambiente.agt";

        $sArquivoSxwSaidaLicenca = "tmp/licenca_".date("YmdHmi").".sxw";
        $sArquivoPdfLicenca      = "tmp/licenca_".date("YmdHmi").".pdf";

        $oAgata     = new cl_dbagata( $sArquivoAgtLicenca );
        $oApiAgata  = $oAgata->api;

        /**
         * Definimos qual o template a utilizar para emissao
         */
        $iCodigoTemplateTipo = 54;

        /**
         * Definimos o Path de saida do template e os parametros para o agata
         */
        $oApiAgata->setOutputPath( $sArquivoSxwSaidaLicenca );
        foreach ($aParametrosDocumento as $sParametro => $sParametroValor ) {
          $oApiAgata->setParameter( $sParametro, $sParametroValor );
        }

        /**
         * Abrimos a conexão com o e-cidade novamente
         */
        include('libs/db_conecta.php');

        /**
         * Instancia Template
         * @var documentoTemplate
         */
        $oDocumentoTemplate = new documentoTemplate( $iCodigoTemplateTipo );

        /**
         * Geramos o documento utilizando as variaveis informadas
         */
        $lGeracaoArquivo = $oApiAgata->parseOpenOffice( $oDocumentoTemplate->getArquivoTemplate() );
        if ( !$lGeracaoArquivo ) {
          throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
        }

        /**
         * Convertemos o sxw em pdf para armazenarmos
         */
        $lConversao = db_stdClass::ex_oo2pdf( $sArquivoSxwSaidaLicenca, $sArquivoPdfLicenca );
        if ( !$lConversao ) {
          throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
        }

        if ( !file_exists($sArquivoPdfLicenca) ) {
          throw new FileException( _M( MENSAGENS . 'erro_gerararquivo') );
        }

        /**
         * Geramos um Blob vazio e gravamos o arquivo no banco
         */
        db_query($conn, "begin;");
        $iOidLicenca   = DBLargeObject::criaOID( true );
        $lSalvaArquivo = DBLargeObject::escrita( $sArquivoPdfLicenca, $iOidLicenca );

        /**
         * Gravamos o arquivo no banco e atualizamos o oid na tabela de licencaempreendimento
         */
        $oDaoLicencaEmpreendimento->am13_sequencial = $oLicencaEmpreendimento->getSequencial();
        $oDaoLicencaEmpreendimento->am13_arquivo    = $iOidLicenca;
        $oDaoLicencaEmpreendimento->alterar( $oLicencaEmpreendimento->getSequencial() );

        if ( $oDaoLicencaEmpreendimento->erro_status == 0 && !$lSalvaArquivo ) {
          throw new FileException( _M( MENSAGENS . 'erro_gravar_parecer' ) );
        }

        db_query($conn, "commit;");

        if( file_exists($sArquivoSxwSaidaLicenca) ){
          unlink($sArquivoSxwSaidaLicenca);
        }
      }

      /**
       * Retornamos o arquivo pdf gerado do parecer
       */
      $oRetorno->sArquivoRetorno = $sArquivoPdfParecer;
      $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'emissao_sucesso' ) );

    break;

    case "getTiposLicenca":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      $sWhere    = "am08_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sSql      = $oDaoParecerTecnico->sql_query_file(null, "*", null, $sWhere);
      $rsRecord  = $oDaoParecerTecnico->sql_record($sSql);
      $aLicencas = db_utils::getCollectionByRecord($rsRecord);

      $aTiposLicenca = array(
          TIPO_LICENCA_PREVIA     => utf8_encode("Prévia"),
          TIPO_LICENCA_INSTALACAO => utf8_encode("Instalação"),
          TIPO_LICENCA_OPERACAO   => utf8_encode("Operação")
      );

      foreach ($aLicencas as $oLicenca) {

        if ($oLicenca->am08_tipolicenca == TIPO_LICENCA_OPERACAO) {

          unset($aTiposLicenca[TIPO_LICENCA_PREVIA]);
          unset($aTiposLicenca[TIPO_LICENCA_INSTALACAO]);
          break;
        }

        if ($oLicenca->am08_tipolicenca == TIPO_LICENCA_INSTALACAO) {
          unset($aTiposLicenca[TIPO_LICENCA_PREVIA]);
        }
      }

      $oRetorno->aTiposLicenca = $aTiposLicenca;
    break;

    case "getTiposEmissao":

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      if ($oParametros->iTipoLicenca == "") {
        throw new Exception(_M( MENSAGENS . 'tipo_licenca_obrigatorio'));
      }

      $aTiposEmissao = array(
          TIPO_EMISSAO_NOVA        => utf8_encode("Nova"),
          TIPO_EMISSAO_PRORROGACAO => utf8_encode("Prorrogação"),
          TIPO_EMISSAO_RENOVACAO   => utf8_encode("Renovação")
        );

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_PREVIA || $oParametros->iTipoLicenca == TIPO_LICENCA_INSTALACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
      }

      if ($oParametros->iTipoLicenca == TIPO_LICENCA_OPERACAO) {
        unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
      }

      $sWhere    = "am08_empreendimento = {$oParametros->iCodigoEmpreendimento}";
      $sWhere   .= " and am08_tipolicenca = {$oParametros->iTipoLicenca}";

      $sSql      = $oDaoParecerTecnico->sql_query_file(null, "*", null, $sWhere);
      $rsRecord  = $oDaoParecerTecnico->sql_record($sSql);
      $aLicencas = db_utils::getCollectionByRecord($rsRecord);

      if ( empty($aLicencas) ) {

        if (isset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_PRORROGACAO]);
        }

        if (isset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO])) {
          unset($aTiposEmissao[TIPO_EMISSAO_RENOVACAO]);
        }
      } else {
        unset($aTiposEmissao[TIPO_EMISSAO_NOVA]);
      }

      $oRetorno->aTiposEmissao = $aTiposEmissao;
      break;

    /**
     * Buscamos as condicionantes vinculadas as atividades do empreendimento selecionado
     * e ao Tipo de Licença
     */
    case "getCondicionantesEmpreendimento":

      $oEmpreendimento = new Empreendimento( $oParametros->iCodigoEmpreendimento );
      $aCondicionantes = $oEmpreendimento->getCondicionantes( $oParametros->iTipoLicenca );
      $oRetorno->aCondicionantes = $aCondicionantes;
      break;

    case "getDadosEmpreendimento":

      $oEmpreendimento = new Empreendimento( $oParametros->iEmpreendimento );
      $iEmpreendimento = $oEmpreendimento->getSequencial();

      if ( empty( $iEmpreendimento ) ) {
        break;
      }

      $oRetorno->sNomeEmpreendedor = utf8_encode( $oEmpreendimento->getCgm()->getNome() );
      $oRetorno->sNomeFantasia     = utf8_encode( $oEmpreendimento->getNomeFantasia() );
      $oRetorno->iCNPJ             = $oEmpreendimento->getCnpj();
      break;
  }

} catch (FileException $oFileErro) {

  /**
   * Removemos o parecer caso não tenha conseguido gerar
   * o arquivo
   * @todo  mover lógica para o lugar certo
   * @see AgataClass
   */
  if ( $oParecerTecnico->getFavoravel() == 'true' ) {
    $oLicencaEmpreendimento->excluir( $iCodigoParecerTecnico );
  }

  $oParecerTecnico->excluir( $iCodigoParecerTecnico );

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oFileErro->getMessage());

} catch (Exception $eErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);