<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_libsys.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbagata/classes/core/AgataAPI.class");
require_once ("model/documentoTemplate.model.php");
require_once ("model/orcamento/suplementacao/SuplementacaoArquivoTemplate.php");

/**
 * Deve receber duas variáveis por GET
 * O Numero do atendimento e o Ano
 */

$oGet               = db_utils::postMemory($_GET);
$oDaoOuvAtendimento = db_utils::getDao('ouvidoriaatendimento');
$oDaoOuvParametro   = db_utils::getDao('ouvidoriaparametro');

$ov06_instit	= db_getsession('DB_instit');
$ov06_anousu 	= db_getsession('DB_anousu');

/**
 * Busca o modelo do documento
 */
$sSqlOuvParametro = $oDaoOuvParametro->sql_query_file($ov06_instit,$ov06_anousu, 'ov06_db_documentotemplate');
$rsOuvParametro   = $oDaoOuvParametro->sql_record($sSqlOuvParametro);

if ($oDaoOuvParametro->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=[1] Não esta configurado o modelo da ficha de atendimento");
}
$iModeloDocumento = db_utils::fieldsMemory($rsOuvParametro, 0)->ov06_db_documentotemplate;


/**
 * Busca o Sequencial da ouvidoriaatendimento 
 */
$sWhereAtendimento  = "     ov01_numero = {$oGet->ov01_numero} ";
$sWhereAtendimento .= " and ov01_anousu = {$oGet->ov01_anousu} ";
$sSqlOuvAtendimento = $oDaoOuvAtendimento->sql_query_file(null, "ov01_sequencial", null, $sWhereAtendimento);

$rsOuvAtendimento   = $oDaoOuvAtendimento->sql_record($sSqlOuvAtendimento);

if ($oDaoOuvAtendimento->numrows == 0 ) {
  
  $sMsgErro = "[2] Não foi possivel localizar o atendimento {$oGet->ov01_numero}/{$oGet->ov01_anousu}";  
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");  
}
$iAtendimento = db_utils::fieldsMemory($rsOuvAtendimento, 0)->ov01_sequencial;

/**
 * Agata 
 */

ini_set("error_reporting","E_ALL & ~NOTICE");

$oAgata           = new cl_dbagata("ouvidoria/ficha_atendimento.agt");
$oApiAgata        = $oAgata->api;
$sCaminhoSalvoSxw = "tmp/__atendimento" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".sxw";

$oApiAgata->setOutputPath($sCaminhoSalvoSxw);
$oApiAgata->setParameter ('$iAtendimento', $iAtendimento);


try {
  $oDocumentoTemplate = new documentoTemplate(27, $iModeloDocumento);
} catch (Exception $eException) {

  $sErroMsg  = $eException->getMessage();
  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}

if ($oApiAgata->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate())) {

  $sNomeRelatorio   = "tmp/ouvidoria" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".pdf";
  $lConversao       = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);

 	if (!$lConversao) {
 	  db_redireciona("db_erros.php?fechar=true&db_erro=[3]Falha ao gerar PDF !!!");
 	} else {
 	  db_redireciona($sNomeRelatorio);
 	}
}