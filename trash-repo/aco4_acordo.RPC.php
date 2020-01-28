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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;


try {
  switch ($oParam->exec) {

    case "buscaDocumentoTemplate":
      
      $aOrigem[1] = 33; //'Processo de Compras'
      $aOrigem[2] = 34; //'Licitação'
      $aOrigem[3] = 35; //'Manual'
      $aOrigem[6] = 36; //'Empenho
      
      if (!isset($aOrigem[$oParam->iOrigem]) || empty($aOrigem[$oParam->iOrigem])) {
        throw BusinessException("Origem desconhecida");
      }
      
      $sWhere        = " db82_templatetipo = {$aOrigem[$oParam->iOrigem]}";
      $oDaoDocumento = new cl_db_documentotemplate();
      $sSqlDocumento = $oDaoDocumento->sql_query_file(null, "db82_sequencial, db82_descricao", "db82_descricao", $sWhere);
      $rsDocumento   = $oDaoDocumento->sql_record($sSqlDocumento);
      $iLinhas       = $oDaoDocumento->numrows;
      
      if ($iLinhas == 0) {
        throw new BusinessException("Nenhum documento cadastrado.");
      }
      
      $aDocumentoRetorno = array();
      for ($i = 0; $i < $iLinhas; $i++) {
        
        $oDadoDocumento = db_utils::fieldsMemory($rsDocumento, $i);
        $oDocumento     = new stdClass();
        
        $oDocumento->iCodigo        = $oDadoDocumento->db82_sequencial;
        $oDocumento->sDescricao     = urlencode($oDadoDocumento->db82_descricao);
        $aDocumentoRetorno[]        = $oDocumento;
      }
      $oRetorno->iTipoDocumento    = $aOrigem[$oParam->iOrigem];
      $oRetorno->aDocumentoRetorno = $aDocumentoRetorno;
      
      break;
  }
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (FileException $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);