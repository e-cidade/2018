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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("model/orcamento/suplementacao/SuplementacaoArquivoTemplate.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;


switch ($oParam->exec) {
  
  case "buscaTemplates": 
    
    $oDaoSuplementacao     = db_utils::getDao('orcsuplem');
    
    $sWhere                = " o46_codlei = {$oParam->iCodigoProjeto}";
    $sSqlTipoSuplementacao = $oDaoSuplementacao->sql_query(null, "o46_tiposup", "o46_tiposup", $sWhere);
    
    $rsTipoSuplementacao   = $oDaoSuplementacao->sql_record($sSqlTipoSuplementacao);
    $mNomeArquivo          = false;
    $iTipoSuplementacao    = "";
    
    /**
     * Busca dados do template de acordo com o tipo da suplementação
     */
    if ($oDaoSuplementacao->numrows > 0) {
    
      $iTipoSuplementacao = db_utils::fieldsMemory($rsTipoSuplementacao, 0)->o46_tiposup;
      $mNomeArquivo       = SuplementacaoArquivoTemplate::getNomeArquivo($iTipoSuplementacao);
    }
    
    if ($mNomeArquivo == false) {
      
      $sMsgErro  = "Não foi encontrado um arquivo template para o projeto : {$oParam->iCodigoProjeto}\n";
      $sMsgErro .= "Tipo de suplementação: {$iTipoSuplementacao}\n";
      $sMsgErro .= "Entre em contato com o suporte.";
      
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($sMsgErro);
      break;
    }
    $oDaoDocumento  = db_utils::getDao('db_documentotemplate');
    $oDaoDocumento  = new cl_db_documentotemplate();
    $sCampos        = " db82_sequencial, db82_descricao, db82_templatetipo";
    $sTemplatesTipo = implode(", ", $mNomeArquivo['template']);
    
    $sWhereDocumentoTemplate = "db82_templatetipo in ({$sTemplatesTipo})";
    $sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, $sWhereDocumentoTemplate);
    
    $rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);
    
    for ($i = 0; $i < $oDaoDocumento->numrows; $i++) {
      
      $oDadosDocumentoTemplate        = db_utils::fieldsMemory($rsDocumentoTemplate, $i);
      $oDocumentoTemplate             = new stdClass();
      $oDocumentoTemplate->iCodigo    = $oDadosDocumentoTemplate->db82_sequencial;
      $oDocumentoTemplate->sDescricao = urlencode($oDadosDocumentoTemplate->db82_descricao);
      $oDocumentoTemplate->iTemplate  = $oDadosDocumentoTemplate->db82_templatetipo;
      $oRetorno->dados[]              = $oDocumentoTemplate;
    }
    break;
}

echo $oJson->encode($oRetorno);