<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_liclicitaata_classe.php");
require_once('dbagata/classes/core/AgataAPI.class');
require_once("model/documentoTemplate.model.php");

ini_set("error_reporting","E_ALL & ~NOTICE");

$oGet = db_utils::postMemory($_GET);

if ( isset($oGet->lPosicaoInicial) && $oGet->lPosicaoInicial == "t" ) {
  
  $clliclicitaata    = new cl_liclicitaata;

  $sMsg  = "Licitaзгo {$oGet->iLicitacao} julgada sem vinculo com modelo de ata, "; 
  $sMsg .= "para gerar ata escolher a opзгo posiзгo ATUALIZADA.                  ";
  
  $sWhere            = "l39_liclicita = {$oGet->iLicitacao} and l39_posicaoinicial is true";
  $sSqlLicLicitaAta  = $clliclicitaata->sql_query_file(null, "*", null, $sWhere);
  $rsSqlLicLicitaAta = $clliclicitaata->sql_record($sSqlLicLicitaAta);
  if ($clliclicitaata->numrows > 0) {
  	
    $oLicLicitaAta = db_utils::fieldsMemory($rsSqlLicLicitaAta, 0);
    
	  db_inicio_transacao();
	   
	  $sCaminhoSalvoSxw = "tmp/salvo_julgamento_{$oLicLicitaAta->l39_arqnome}";
	  $oOpenFile        = pg_lo_open($conn, $oLicLicitaAta->l39_arquivo, "r");
	  if ($oOpenFile) {
	    $oDadosOid = pg_lo_read($oOpenFile, 999999);
	  } else {
	    
	    db_fim_transacao(true);
	    
	    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
	  }
	    
	  $oFile = fopen($sCaminhoSalvoSxw, "w+");
	    
	  fwrite($oFile, $oDadosOid);
	  fclose($oFile);
	   
	  db_fim_transacao();
	  db_redireciona($sCaminhoSalvoSxw);
  } else {
   	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  }
} else {

	$clagata = new cl_dbagata("licitacao/atas.agt");
	$api     = $clagata->api;
	
	$iCasasDecimais     = 2;
	$aParametrosEmpenho = db_stdClass::getParametro('empparametro',array(db_getsession('DB_anousu')));
	
	if (count($aParametrosEmpenho) > 0) {	
		$iCasasDecimais = $aParametrosEmpenho[0]->e30_numdec;
	}
	
	$sCaminhoSalvoSxw = "tmp/ata_licitacao_{$oGet->iLicitacao}.sxw";
	$api->setOutputPath($sCaminhoSalvoSxw);
	$api->setParameter('$licitacao',$oGet->iLicitacao);
	
	try {
		$oDocumentoTemplate = new documentoTemplate(5,$oGet->iCodDocumento); 
	} catch (Exception $eException){
		$sErroMsg  = $eException->getMessage();
	  db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");
	}
	
	$lProcessado = $api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate());
	
	if ( $lProcessado ) {
		db_redireciona($sCaminhoSalvoSxw);
	} else {
		db_redireciona("db_erros.php?fechar=true&db_erro=Falha ao gera relatуrio!");
	}
}
?>