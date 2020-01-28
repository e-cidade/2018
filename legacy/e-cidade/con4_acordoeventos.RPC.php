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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam            = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass();
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'getTiposDeEventos' :

      $oRetorno->aTipos = array_map(function($sKeys, $sItem) {

        $oTipo            = new \stdClass();
        $oTipo->codigo    = $sKeys;
        $oTipo->descricao = urlencode($sItem);
        return $oTipo;
      }, array_keys(TipoEventoAcordo::getTipos()), TipoEventoAcordo::getTipos());


      break;

    case "getTiposDocumentos":

      $oRetorno->aTipos = array_map(function($sKey, $sItem) {

        $oTipo = new \stdClass();
        $oTipo->codigo    = $sKey;
        $oTipo->descricao = urlencode($sItem);

        return $oTipo;
      }, array_keys(LicitaConTipoDocumentoAcordo::getTipos()), LicitaConTipoDocumentoAcordo::getTipos());

      break;

    case 'getEventosDoAcordo':

      if (empty($oParam->iCodigoAcordo)) {
        throw new ParameterException('Parametro $iCodigoAcordo não informado');
      }
      $oAcordo = AcordoRepository::getByCodigo((int)$oParam->iCodigoAcordo);
      $oRetorno->eventos = array_map(function(AcordoEvento $oEventoAcordo){

        $oEvento                           = new \stdClass();
        $oEvento->codigo                   = $oEventoAcordo->getCodigo();
        $oEvento->tipo                     = $oEventoAcordo->getTipoEvento();
        $oEvento->data                     = $oEventoAcordo->getData()->getDate(DBDate::DATA_PTBR);
        $oEvento->processo                 = $oEventoAcordo->getProcesso()."/".$oEventoAcordo->getAnoProcesso();
        $oEvento->tipo_veiculo_comunicacao = $oEventoAcordo->getVeiculoComunicacao();
        $oEvento->descricao_veiculo        = urlencode($oEventoAcordo->getDescricaoVeiculo());
        return $oEvento;
      },$oAcordo->getEventos());
      break;

    case 'salvar':

      if (empty($oParam->iCodigoAcordo)) {
        throw new ParameterException('Acordo não foi informado.');
      }
      $oAcordo = AcordoRepository::getByCodigo($oParam->iCodigoAcordo);
      $oEventoAcordo = new AcordoEvento();
      $oEventoAcordo->setAnoProcesso($oParam->iAnoProcesso);
      $oEventoAcordo->setProcesso($oParam->iNumeroProcesso);
      $oEventoAcordo->setAcordo($oAcordo);
      $oEventoAcordo->setDescricaoVeiculo(db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricaoVeiculo));
      $oEventoAcordo->setData(new DBDate($oParam->sData));
      $oEventoAcordo->setVeiculoComunicacao($oParam->iTipoVeiculo);
      $oEventoAcordo->setTipoEvento($oParam->iTipoEvento);
      $oEventoAcordo->salvar();
      $oRetorno->iCodigoEvento = $oEventoAcordo->getCodigo();
      break;

    case 'remover':

      if (empty($oParam->iCodigoEvento)) {
        throw new ParameterException('Evento não foi informado.');
      }
      $oEventoAcordo = new AcordoEvento($oParam->iCodigoEvento);
      $oEventoAcordo->remover();
      break;

    case 'getDocumentos':

      if (empty($oParam->iCodigoEvento)) {
        throw new ParameterException('Evento não foi informado.');
      }
      $oEventoAcordo = new AcordoEvento((int)$oParam->iCodigoEvento);
      $oRetorno->documentos = array_map(function(DocumentoEventoAcordo $oEventoDocumento){

        $oDocumento                           = new \stdClass();
        $oDocumento->codigo                   = $oEventoDocumento->getCodigo();
        $oDocumento->tipo                     = $oEventoDocumento->getTipoDocumento();
        $oDocumento->nome                     = urlencode($oEventoDocumento->getAcordoDocumento()->getNomeArquivo());
        return $oDocumento;
      }, $oEventoAcordo->getDocumentos());

      break;

    case 'adicionarDocumento':

      if (!isset($_FILES['arquivo'])) {
        throw new ParameterException('Nenhum arquivo informado.');
      }

      if ($_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
        throw new FileException('Ocorreu um erro ao fazer envio do arquivo.');
      }

      if (empty($oParam->iCodigoEvento)) {
        throw new ParameterException('Evento não foi informado.');
      }

      $aExtensoesProibidas = array('exe', 'php', 'sh', 'bat', 'py');
//      $oSplFile = new File($_FILES['arquivo']['name']);
//      if (in_array($oSplFile->getExtension(), $aExtensoesProibidas)) {
//        throw new FileException('A extensão utilizada não é permitida');
//      }

      $oEvento = new AcordoEvento((int)$oParam->iCodigoEvento);
      $oAcordo = $oEvento->getAcordo();

      $oDocumento = new AcordoDocumento();
      $oDocumento->setArquivo($_FILES['arquivo']['tmp_name']);
      $oDocumento->setDescricao(utf8_decode($_FILES['arquivo']['name']));
      $oDocumento->setNomeArquivo(db_removeAcentuacao(utf8_decode($_FILES['arquivo']['name'])));
      $oDocumento->setCodigoAcordo($oAcordo->getCodigo());
      $oDocumento->salvar();

      $oDocumentoEvento = new DocumentoEventoAcordo();
      $oDocumentoEvento->setAcordoDocumento($oDocumento);
      $oDocumentoEvento->setEvento($oEvento);
      $oDocumentoEvento->setTipoDocumento($oParam->iTipoDocumento);
      $oDocumentoEvento->salvar();

      $oRetorno->iCodigoDocumento = $oDocumentoEvento->getCodigo();
      $oRetorno->sNomeDocumento = urlencode($oDocumento->getNomeArquivo());

      break;

    case 'removerDocumento':

      $oDocumentoEvento = new DocumentoEventoAcordo($oParam->iCodigoDocumento);
      $oDocumentoEvento->remover();
      break;
  }
  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($e->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);