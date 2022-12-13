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
require_once("model/cadastro/CertidaoExistencia.model.php");
require_once("std/DBLargeObject.php");
require_once('std/db_stdClass.php');
require_once('dbagata/classes/core/AgataAPI.class');
$oGet  = db_utils::postMemory($_GET);
try {
	
	$oCertidao        = new CertidaoExistencia($oGet->iCodigoCertidao);
	
	$sArquivoAgt      = "cadastro/certidao_existencia_parte2.agt";	
	$sNomeArquivo     = "tmp/__certidaoExistencia_".db_getsession('DB_login')."_".date("YmdHmi").".sxw";
	$sArquivoSxwSaida = "tmp/certidaoExistencia_".db_getsession('DB_login')."_".date("YmdHmi").".sxw";
	$sNomeRelatorio   = $sArquivoSxwSaida.".pdf";
	
	db_inicio_transacao();
	
  $lGerouArquivo    = DBLargeObject::leitura($oCertidao->getOidArquivo(), $sNomeArquivo ); 
  if ( !$lGerouArquivo ) {
  	throw new Exception("erro ao Gerar arquivo");
  }	
  db_fim_transacao(); 
  	
  ini_set("error_reporting","E_ALL & ~NOTICE");
  $oAgata                         = new cl_dbagata($sArquivoAgt);
  $oApiAgata                      = $oAgata->api;
  	
  $oApiAgata->setOutputPath($sArquivoSxwSaida);
  $oApiAgata->setParameter ('$sUsuarioSessao',  db_getsession("DB_login"));
  $oApiAgata->setParameter ('$sBaseDados',      db_getsession("DB_NBASE"));
  $oApiAgata->setParameter ('$dDataSessao',     date("Y-m-d", db_getsession("DB_datausu")) );
  $oApiAgata->setParameter ('$sHoraSessao',     db_hora());
  
  $lGeracaoArquivo = $oApiAgata->parseOpenOffice( $sNomeArquivo ) ;
  
  if ( !$lGeracaoArquivo ) {
  	throw new Exception("Erro ao Gerar Arquivo.");
  } 
  
  $lConversao = db_stdClass::ex_oo2pdf($sArquivoSxwSaida, $sNomeRelatorio);
  if (!$lConversao) {
  	throw new Exception("Erro ao Converter arquivo.");
  } else {
  	db_redireciona($sNomeRelatorio);
  }
} catch (Exception $eErro) {
	db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage() );
}