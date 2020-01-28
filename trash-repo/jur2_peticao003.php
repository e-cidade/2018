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

require_once('libs/db_stdlib.php'); 
require_once('libs/db_conecta.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('std/db_stdClass.php');
require_once('libs/db_libsys.php');
require_once('dbagata/classes/core/AgataAPI.class'); 
require_once('model/documentoTemplate.model.php');
require_once("classes/db_parjuridico_classe.php");
$oDaoParjuridico = new cl_parjuridico;
$oGet            = db_utils::postMemory($_GET);

if ($oGet->sTipoPeticao == "parcelamento") {
	
	$sArquivoAgt = "juridico/peticao_juridico_parcelamento.agt";
	$sCampo      = "v19_templateparcelamento";
	$iTipo       = 16;
} elseif ($oGet->sTipoPeticao == "inicialquitada") {
	
	$sArquivoAgt = "juridico/peticao_juridico_inicial.agt";
	$sCampo      = "v19_templateinicialquitada";
	$iTipo       = 17;
} else {
	db_redireciona('db_erros.php?fechar=true&db_erro=[1] - Tipo de Petição Incorreto.');
}

$sSqlModelo = $oDaoParjuridico->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"), $sCampo . " as modelo_impressao");
$rsModelo   = $oDaoParjuridico->sql_record($sSqlModelo);

if ($oDaoParjuridico->erro_msg == "0") {
	db_redireciona('db_erros.php?fechar=true&db_erro=[2] - Erro o buscar parametros do Jurídico.' . $oDaoParjuridico->erro_msg);
}

$iModeloImpressao = db_utils::fieldsMemory($rsModelo, 0)->modelo_impressao;

ini_set("error_reporting","E_ALL & ~NOTICE");

$oAgata           = new cl_dbagata($sArquivoAgt);
$oApiAgata        = $oAgata->api;
$sCaminhoSalvoSxw = "tmp/jur2_peticao003" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".sxw";

$oApiAgata->setOutputPath($sCaminhoSalvoSxw);
$oApiAgata->setParameter('$iCodigoPeticao', $oGet->iCodigoPeticao);

try {
	$oDocumentoTemplate = new documentoTemplate($iTipo, $iModeloImpressao);
	
} catch (Exception $eException){

	$sErroMsg  = $eException->getMessage();
	db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
}

if ( $oApiAgata->parseOpenOffice( $oDocumentoTemplate->getArquivoTemplate() ) ) {

	$sNomeRelatorio   = "tmp/jur2_peticao003" . date('YmdHis') . "_" . db_getsession("DB_id_usuario") . ".pdf";
	$lConversao       = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);

 	if (!$lConversao) {
 		db_redireciona("db_erros.php?fechar=true&db_erro=[3]Falha ao gerar PDF !!!");
 	} else {
 		db_redireciona($sNomeRelatorio);
 	}
}