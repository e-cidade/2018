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
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");

define("MENSAGENS", "tributario.meioambiente.amb4_emissaodelicenca.");

$oJson                     = new services_json();
$oParametros               = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno                  = new stdClass();
$oRetorno->erro            = false;
$oRetorno->sMensagem       = '';
$oDaoLicencaEmpreendimento = db_utils::getDao("licencaempreendimento");

define("TIPO_LICENCA_PREVIA",     1);
define("TIPO_LICENCA_INSTALACAO", 2);
define("TIPO_LICENCA_OPERACAO",   3);

define("TIPO_EMISSAO_NOVA",        1);
define("TIPO_EMISSAO_PRORROGACAO", 2);
define("TIPO_EMISSAO_RENOVACAO",   3);

try {

  switch ($oParametros->sExecucao) {

    case "emitirLicenca":

      if (empty($oParametros->iCodigoParecerTecnico)) {
        throw new Exception(_M( MENSAGENS . 'parecertecnico_obrigatorio'));
      }

      $sArquivoPdfLicenca        = "tmp/licenca_".date("YmdHmi").".pdf";
      $sSqlLicencaEmpreendimento = $oDaoLicencaEmpreendimento->sql_query_arquivo( $oParametros->iCodigoParecerTecnico );
      $rsLicencaEmpreendimento   = $oDaoLicencaEmpreendimento->sql_record( $sSqlLicencaEmpreendimento );

      if( !$rsLicencaEmpreendimento ){
        throw new Exception(_M( MENSAGENS . 'erro_emissao_arquivo'));
      }

      $iOidArquivo    = db_utils::fieldsMemory( $rsLicencaEmpreendimento, 0 )->am13_arquivo;

      db_query($conn, 'begin;');
      $lEmitirArquivo = DBLargeObject::leitura( $iOidArquivo, $sArquivoPdfLicenca );
      db_query($conn, 'commit;');

      if( !$lEmitirArquivo ){
        throw new Exception(_M( MENSAGENS . 'erro_emissao_arquivo'));
      }

      if( !file_exists( $sArquivoPdfLicenca ) ){
        throw new Exception(_M( MENSAGENS . 'erro_emissao_arquivo'));
      }

      $oRetorno->sArquivoRetorno = $sArquivoPdfLicenca;
      $oRetorno->sMensagem       = urlencode( _M( MENSAGENS . 'emissao_sucesso' ) );

    break;

    case 'getLicencaValida':

      if (empty($oParametros->iCodigoEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'empreendimento_obrigatorio'));
      }

      $oEmpreendimento = new Empreendimento($oParametros->iCodigoEmpreendimento);
      $oLicencaValida  = $oEmpreendimento->getLicencaValida();

      if (!$oLicencaValida) {
        throw new Exception(_M( MENSAGENS . 'licenca_inexistente'));
      }

      if ($oLicencaValida->am08_favoravel == 'f') {
        throw new Exception(_M( MENSAGENS . 'licenca_invalida'));
      }

      $oTipoLicenca    = new TipoLicenca($oLicencaValida->am08_tipolicenca);
      $oDataVencimento = new DateTime($oLicencaValida->am08_datavencimento);
      $oDataEmissao    = new DateTime($oLicencaValida->am08_dataemissao);

      $iCodigoLicencaEmpreendimento = $oLicencaValida->am13_sequencial;
      if ($oLicencaValida->am08_tipolicenca != 3) {

        $oParecerTecnico = new ParecerTecnico($oLicencaValida->am08_sequencial);
        $iCodigoLicencaEmpreendimento = $oParecerTecnico->getCodigoLicencaAnterior();
        if (is_null($iCodigoLicencaEmpreendimento)) {
          $iCodigoLicencaEmpreendimento = $oLicencaValida->am13_sequencial;
        }
      }

      $oParecerTecnico = new ParecerTecnico();
      $aTipoEmissao    = $oParecerTecnico->getTipoEmissao($oParametros->iCodigoEmpreendimento, $oLicencaValida->am08_tipolicenca, $oLicencaValida->am08_sequencial);

      $oRetorno->codigoProcesso       = $oLicencaValida->am08_protprocesso;
      $oRetorno->tipoEmissao          = array_pop($aTipoEmissao);
      $oRetorno->tipoLicenca          = utf8_encode($oTipoLicenca->getDescricao());
      $oRetorno->codigoLicenca        = $iCodigoLicencaEmpreendimento;
      $oRetorno->codigoParecerTecnico = $oLicencaValida->am08_sequencial;
      $oRetorno->dataVencimento       = $oDataVencimento->format('d/m/Y');
      $oRetorno->dataEmissao          = $oDataEmissao->format('d/m/Y');

    break;
  }

} catch (Exception $oErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);