<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

define("URL_MENSAGEM_PROT4PROCESSODOCUMENTO", "patrimonial.protocolo.prot4_processodocumento.");

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
try {

  /*
   * Início Transação
   */
  db_inicio_transacao();

  switch ($oParam->exec) {

    case "carregarDocumentos":

      $oProcessoProtocolo    = new processoProtocolo($oParam->iCodigoProcesso);
      $aDocumentosVinculados = $oProcessoProtocolo->getDocumentos();
      $aDocumentosRetorno    = array();
      foreach ($aDocumentosVinculados as $oProcessoDocumento) {

        $oStdDocumento = new stdClass();
        $oStdDocumento->iCodigoDocumento    = $oProcessoDocumento->getCodigo();
        $oStdDocumento->sDescricaoDocumento = urlencode($oProcessoDocumento->getDescricao());
        $aDocumentosRetorno[] = $oStdDocumento;
      }

      $oRetorno->aDocumentosVinculados = $aDocumentosRetorno;

    break;


    case "salvarDocumento":

      $oProcessoProtocolo = new processoProtocolo($oParam->iCodigoProcesso);
      $oDepartamentoAtual = $oProcessoProtocolo->getDepartamentoAtual();

      if ($oDepartamentoAtual->getCodigo() != db_getsession("DB_coddepto")) {

        $oStdErro = (object)array("sDepartamento" => "{$oDepartamentoAtual->getCodigo()} - {$oDepartamentoAtual->getNomeDepartamento()}");
        throw new BusinessException(_M(URL_MENSAGEM_PROT4PROCESSODOCUMENTO."departamento_diferente_vinculo_documento", $oStdErro));
      }

      $oProcessoDocumento = new ProcessoDocumento($oParam->iCodigoDocumento);
      $oProcessoDocumento->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricaoDocumento));
      $oProcessoDocumento->setProcessoProtocolo($oProcessoProtocolo);

      if ( !empty($oParam->sCaminhoArquivo) ) {
        $oProcessoDocumento->setCaminhoArquivo($oParam->sCaminhoArquivo);
      } 

      $oRetorno->sMensagem = urlencode($oProcessoDocumento->salvar());

    break;

    case "excluirDocumento":

      $oProcessoProtocolo = new processoProtocolo($oParam->iCodigoProcesso);
      $oDepartamentoAtual = $oProcessoProtocolo->getDepartamentoAtual();

      if ($oDepartamentoAtual->getCodigo() != db_getsession("DB_coddepto")) {

        $oStdErro = (object)array("sDepartamento" => "{$oDepartamentoAtual->getCodigo()} - {$oDepartamentoAtual->getNomeDepartamento()}");
        throw new BusinessException(_M(URL_MENSAGEM_PROT4PROCESSODOCUMENTO."departamento_diferente_vinculo_documento", $oStdErro));
      }

      foreach ($oParam->aDocumentosExclusao as $iCodigoDocumento) {

        $oProcessoDocumento = new ProcessoDocumento($iCodigoDocumento);
        $oProcessoDocumento->excluir();
      }
      $oRetorno->sMensagem = urlencode(_M(URL_MENSAGEM_PROT4PROCESSODOCUMENTO."documento_excluido"));

    break;

    case "download":

      $oProcessoDocumento                = new ProcessoDocumento($oParam->iCodigoDocumento);
      $oRetorno->sCaminhoDownloadArquivo = $oProcessoDocumento->download();
      $oRetorno->sTituloArquivo          = urlencode($oProcessoDocumento->getNomeDocumento());

    break;
  }

  /**
   * Fim Transação
   */
  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);