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

    case "getLicitacaoEvento" :

      $iCodigoEvento = (int) $oParam->codigo_evento;
      if (empty($iCodigoEvento)) {
        throw new ParameterException("Código do Evento é de preenchimento obrigatório.");
      }

      $oEvento         = new EventoLicitacao($iCodigoEvento);
      $oDataEvento     = $oEvento->getData();
      $oDataJulgamento = $oEvento->getDataJulgamento();
      $oAutor          = $oEvento->getAutor();

      $oStdEvento = new stdClass();
      $oStdEvento->codigo               = $oEvento->getCodigo();
      $oStdEvento->fase                 = $oEvento->getFase();
      $oStdEvento->evento               = $oEvento->getTipo();
      $oStdEvento->data                 = empty($oDataEvento)     ? "" : $oDataEvento->getDate(DBDate::DATA_PTBR);
      $oStdEvento->data_julgamento      = empty($oDataJulgamento) ? "" : $oDataJulgamento->getDate(DBDate::DATA_PTBR);
      $oStdEvento->autor                = empty($oAutor) ? "" : $oAutor->getCodigo();
      $oStdEvento->autor_nome           = empty($oAutor) ? "" : $oAutor->getNomeCompleto();
      $oStdEvento->resultado            = $oEvento->getTipoResultado();
      $oStdEvento->publicacao_tipo      = $oEvento->getTipoPublicacao();
      $oStdEvento->publicacao_descricao = ($oEvento->getDescricaoPublicacao());

      $oRetorno->oEvento = $oStdEvento;
      break;

    case "getLicitacaoEventos":

      $iLicitacao = (int) $oParam->codigo_licitacao;
      if (empty($iLicitacao)) {
        throw new ParameterException('Código da licitação não informado.');
      }

      $oLicitacao = new licitacao($iLicitacao);
      $aEventos   = $oLicitacao->getEventos();
      $oRetorno->aEventos = array();

      foreach ($aEventos as $oEvento) {

        $sTipo = LicitaConTipoEvento::$aDescricaoEvento[$oEvento->getTipo()];
        $sDescricaoFase = LicitaConTipoFase::$aFases[$oEvento->getFase()];
        $sData  = $oEvento->getData()->getDate(DBDate::DATA_PTBR);
        $oAutor = $oEvento->getAutor();

        $oRetorno->aEventos[] = array(
          'codigo' => $oEvento->getCodigo(),
          'fase'   => urlencode($sDescricaoFase),
          'evento' => urlencode($sTipo),
          'data'   => urlencode($sData),
          'autor'  => $oAutor ? urlencode($oAutor->getNome()) : '',
          'cpf_autor' => $oAutor && $oAutor->isFisico() ? db_formatar($oAutor->getCpf(), "cpf") : '',
          'cnpj_autor' => $oAutor && !$oAutor->isFisico() ? db_formatar($oAutor->getCnpj(), "cnpj") : ''
        );
      }

    break;

    case "getFaseLicitacao":

      $iLicitacao = (int) $oParam->codigo_licitacao;
      $oLicitacao = new licitacao($iLicitacao);
      $oRetorno->fase = $oLicitacao->getFase();

    break;

    case "getTipoEventos":

      $oDaoTipo = new cl_liclicitatipoevento();
      $sSql     = $oDaoTipo->sql_query();
      $oTipo    = db_query($sSql);

      if (!$oTipo) {
        throw new DBException('Não foi possível consultar os tipos de eventos.');
      }

      $oRetorno->aTipoEventos = db_utils::makeCollectionFromRecord($oTipo, function($oRegistro) {

        return (object) array(
          'codigo' => $oRegistro->l45_sequencial,
          'descricao' => urlencode($oRegistro->l45_descricao)
        );
      });

    break;

    case "getDocumentos":

      $iCodigo = (int) $oParam->codigo_evento;
      if (empty($iCodigo)) {
        throw new ParameterException('Código do evento não informado.');
      }

      $oRetorno->aDocumentos = array();
      $oEvento = new EventoLicitacao($iCodigo);
      $aDocumentos = $oEvento->getDocumentos();

      foreach ($aDocumentos as $oDocumento) {

        $sDescricaoTipoDocumento = LicitaConTipoDocumento::$aDescricaoTipoDocumento[$oDocumento->getTipoDocumento()];
        $oRetorno->aDocumentos[] = array(
          'codigo'  => $oDocumento->getCodigo(),
          'arquivo' => urlencode($oDocumento->getNomeArquivo()),
          'tipo'    => urlencode($sDescricaoTipoDocumento)
        );
      }

    break;

    case "salvarEvento":

      $oStdEvento    = $oParam->evento;
      $iCodigoEvento = isset($oStdEvento->codigo_evento) ? $oStdEvento->codigo_evento : null;
      $oData         = new DBDate(urldecode($oStdEvento->data));
      $oDataSessao   = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));

      if ($oData->getTimeStamp() > $oDataSessao->getTimeStamp()) {
        throw new BusinessException("A Data do Evento não pode ser maior que a data atual.");
      }

      $oEvento = new EventoLicitacao();
      if (!empty($iCodigoEvento)) {
        $oEvento->setCodigo($iCodigoEvento);
      }
      $oEvento->setCodigoLicitacao($oStdEvento->codigo_licitacao);
      $oEvento->setFase($oStdEvento->fase);
      $oEvento->setTipo($oStdEvento->evento);
      $oEvento->setData($oData);
      $oEvento->setTipoResultado($oStdEvento->resultado);
      $oEvento->setTipoPublicacao($oStdEvento->publicacao_tipo);
      $oEvento->setDescricaoPublicacao(db_stdClass::normalizeStringJsonEscapeString($oStdEvento->publicacao_descricao));

      $oEvento->setCodigoAutor(0);
      if (!empty($oStdEvento->autor)) {
        $oEvento->setAutor(CgmFactory::getInstanceByCgm($oStdEvento->autor));
      }

      if (!empty($oStdEvento->data_julgamento)) {

        $oDataJulgamento = new DBDate($oStdEvento->data_julgamento);
        if ($oDataJulgamento->getTimeStamp() > $oDataSessao->getTimeStamp()) {
          throw new BusinessException('A Data do Julgamento não pode maior que a data atual.');
        }
        $oEvento->setDataJulgamento($oDataJulgamento);
      }

      $oEvento->salvar();
      $oRetorno->message = "Evento foi salvo com sucesso.";

    break;

    case "excluirEvento":

      $iCodigo = (int) $oParam->codigo_evento;
      if (empty($iCodigo)) {
        throw new ParameterException('Código do evento não informado.');
      }

      $oEvento = new EventoLicitacao($iCodigo);
      $oEvento->excluir();
      $oRetorno->message = urlencode("Evento foi excluído com sucesso.");

    break;

    case "excluirDocumento":

      $iCodigo = (int) $oParam->codigoDocumento;
       if (empty($iCodigo)) {
        throw new ParameterException('Código do Documento não informado.');
      }

      $oDocumento = new DocumentoEventoLicitacao($iCodigo);
      $oDocumento->excluir();
      $oRetorno->message = urlencode("Documento excluído com sucesso.");

    break;

    case "salvarDocumento":

      if (!isset($_FILES['arquivo'])) {
        throw new ParameterException('Nenhum arquivo informado.');
      }

      if ($_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
        throw new FileException('Ocorreu um erro ao fazer envio do arquivo.');
      }

      $aExtensoesProibidas = array('exe', 'php', 'sh', 'bat', 'py');
//      $oFile = new File($_FILES['arquivo']['name']);
//      if (in_array($oFile->getExtension(), $aExtensoesProibidas)) {
//        throw new FileException('A extensão utilizada não é permitida');
//      }

      $oDocumento = new DocumentoEventoLicitacao();
      $oDocumento->setCodigoEvento((int) $oParam->codigo_evento);
      $oDocumento->setTipoDocumento((int) $oParam->tipo_documento);
      $oDocumento->setArquivoTemporario($_FILES['arquivo']['tmp_name']);
      $oDocumento->setNomeArquivo(db_removeAcentuacao(utf8_decode($_FILES['arquivo']['name'])));
      $oDocumento->salvar();

      $oRetorno->message = "Documento salvo com sucesso.";

      break;

    default:
      throw new Exception("Opção é inválida.");
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($e->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
