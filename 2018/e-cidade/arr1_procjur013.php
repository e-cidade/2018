<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_procjur_classe.php");
include("classes/db_procjuradm_classe.php");
include("classes/db_procjurtipo_classe.php");
include("classes/db_procjurjudicial_classe.php");
include("classes/db_procjurjudicialadvog_classe.php");
include("classes/db_suspensao_classe.php");
include("dbforms/db_funcoes.php");

//echo "<pre>";
//var_dump($HTTP_SERVER_VARS);
//var_dump($HTTP_SESSION_VARS);
//echo "</pre>";exit;

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprocjur 	   			    = new cl_procjur();
$clprocjuradm  			    = new cl_procjuradm();
$clprocjurtipo 			    = new cl_procjurtipo();
$clprocjurjudicial 	    = new cl_procjurjudicial();
$clprocjurjudicialadvog = new cl_procjurjudicialadvog();
$clsuspensao		        = new cl_suspensao();
$cldb_usuarios		      = new cl_db_usuarios();

$db_opcao = 3;
$db_botao = true;
$lSqlErro = false;

if(isset($oPost->excluir)){
  	
  db_inicio_transacao();
  
  $rsVerificaSuspensao = $clsuspensao->sql_record($clsuspensao->sql_query_proc(null,"distinct ar18_sequencial",null," ar18_procjur = {$oPost->v62_sequencial} "));
  $iNroLinhasSuspensao = $clsuspensao->numrows;
  if ( $iNroLinhasSuspensao > 0 ) {
  	
  	$aSuspensao = array();
  	
  	for ( $i=0; $i < $iNroLinhasSuspensao; $i++) {
  	  $oSuspensao   = db_utils::fieldsMemory($rsVerificaSuspensao,$i);
  	  $aSuspensao[] = $oSuspensao->ar18_sequencial; 	
  	}
  	
  	$oParms = new stdClass();
  	$oParms->iSuspensao = implode(",",$aSuspensao);
  	db_msgbox(_M('tributario.juridico.db_frmprocjur.processo_suspensao', $oParms));
  	//db_msgbox("Processo envolvido com ".(count($aSuspensao)>1?"suspensões":"suspensão")." de nº: ".implode(",",$aSuspensao));
  	db_redireciona($_SERVER['REQUEST_URI']);	
  }
  
  if ($oPost->v66_procjurtiporegra == 1) {
  	
  	$rsBuscaProc = $clprocjurjudicial->sql_record($clprocjurjudicial->sql_query(null,"*",null," v63_procjur = {$oPost->v62_sequencial}"));
  	$oBuscaProc  = db_utils::fieldsMemory($rsBuscaProc,0);
  	
  	$clprocjurjudicialadvog->excluir(null," v65_procjurjudicial = {$oBuscaProc->v63_sequencial}");
  	
  	if ($clprocjurjudicialadvog->erro_status == 0) {
  	  $lSqlErro = true;	
  	}
  	
  	$sErroMsg = $clprocjurjudicialadvog->erro_msg;
  	
  	if (!$lSqlErro) {
  		
  	  $clprocjurjudicial->excluir(null," v63_procjur = {$oPost->v62_sequencial}  ");
  	  
  	  if ($clprocjurjudicial->erro_status == 0) {
  	    $lSqlErro = true;	
  	  }
  	  
  	  $sErroMsg = $clprocjurjudicial->erro_msg;  	  	
  		
  	}
  	
  } else {
  	
  	$clprocjuradm->excluir(null," v64_procjur = {$oPost->v62_sequencial} ");
  	
  	if ($clprocjuradm->erro_status == 0) {
  	   $lSqlErro = true;	
  	}
  	
  	$sErroMsg = $clprocjuradm->erro_msg;  	
  	
  }
  
  if (!$lSqlErro){
  	 $clprocjur->excluir($oPost->v62_sequencial);
  }

  if ($clprocjur->erro_status == 0) {
  	$lSqlErro = true;
  }
  $sErroMsg = $clprocjur->erro_msg;

  
  db_fim_transacao($lSqlErro);
  
} else if (isset($oGet->chavepesquisa)) {
	
  $rsBuscaProc = $clprocjur->sql_record($clprocjur->sql_query($oGet->chavepesquisa));
  db_fieldsmemory($rsBuscaProc,0);	
  
} else {

  $db_opcao = 33;
	
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
		<?
		  include("forms/db_frmprocjur.php");
		?>
</body>
</html>
<?
if(isset($oPost->excluir)){
	
  if($clprocjur->erro_status=="0"){
    $clprocjur->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocjur->erro_campo!=""){
      echo "<script> document.form1.".$clprocjur->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocjur->erro_campo.".focus();</script>";
    }
  }else{
    $clprocjur->erro(true,true);
  }
}
if( $db_opcao == 33 ){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","v62_procjurtipo",true,1,"v62_procjurtipo",true);
</script>