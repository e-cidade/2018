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
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("fpdf151/pdf.php");

try{

	$oGet = new _db_fields();
	$oGet = db_utils::postMemory($HTTP_GET_VARS);
	
	db_app::import('diversos.DiversosRelatorio');
	db_app::import('exceptions.*');
	 
	$oRelatorio = new DiversosRelatorio(0);
	
	$oRelatorio->setCgm          ( $oGet->iCgm         );
	$oRelatorio->setMatricula    ( $oGet->iMatricula   );
	$oRelatorio->setInscricao    ( $oGet->iInscricao   );
	$oRelatorio->setNumpre       ( $oGet->iNumpre      );
	$oRelatorio->setDataInicial  ( $oGet->dDataInicial );
	$oRelatorio->setDataFinal    ( $oGet->dDataFinal   );
	$oRelatorio->setTipo         ( $oGet->sTipo        );
	$oRelatorio->setOrigem       ( $oGet->sOrigem      );
	$oRelatorio->setFormato      ( $oGet->sFormato     );

	$sNomeArquivo = $oRelatorio->gerarRelatorio();
	
	echo "
	
	<script>
	  window.opener.js_detectaarquivo('$sNomeArquivo');
	</script>";
	
}catch (Exception $oErro){
	
	 db_redireciona('db_erros.php?fechar=true&db_erro='.$oErro->getMessage());
   exit;
}

?>