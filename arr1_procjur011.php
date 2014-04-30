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
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$clprocjur 	   			= new cl_procjur();
$clprocjuradm  			= new cl_procjuradm();
$clprocjurtipo 			= new cl_procjurtipo();
$clprocjurjudicial 	    = new cl_procjurjudicial();
$clprocjurjudicialadvog = new cl_procjurjudicialadvog();
$cldb_usuarios		    = new cl_db_usuarios();

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;

if(isset($oPost->incluir)){
  	
  db_inicio_transacao();
  
  $clprocjur->v62_procjurtipo = $oPost->v62_procjurtipo;
  $clprocjur->v62_descricao	  =	$oPost->v62_descricao;
  $clprocjur->v62_data		  =	date("Y-m-d",db_getsession('DB_datausu'));
  $clprocjur->v62_hora		  =	db_hora();
  $clprocjur->v62_usuario	  =	$oPost->v62_usuario;
  $clprocjur->v62_dataini	  =	$oPost->v62_dataini_ano."-".$oPost->v62_dataini_mes."-".$oPost->v62_dataini_dia;
  if (trim($oPost->v62_datafim) != "") {
    $clprocjur->v62_datafim 	  =	$oPost->v62_datafim_ano."-".$oPost->v62_datafim_mes."-".$oPost->v62_datafim_dia;
  }
  $clprocjur->v62_situacao    =	$oPost->v62_situacao;
  $clprocjur->v62_obs		  =	$oPost->v62_obs;
  $clprocjur->incluir(null);

  if ($clprocjur->erro_status == 0) {
  	$lSqlErro = true;
  }
  $sErroMsg = $clprocjur->erro_msg;
  
  if (!$lSqlErro) {
  	 
	if ($oPost->v66_procjurtiporegra == 1) {
		
	  $clprocjurjudicial->v63_localiza	   = $oPost->v63_localiza;
	  $clprocjurjudicial->v63_procjur  	   = $clprocjur->v62_sequencial; 
	  $clprocjurjudicial->v63_processoforo = $oPost->v63_processoforo; 
	  $clprocjurjudicial->v63_vara		   = $oPost->v63_vara;
	  $clprocjurjudicial->incluir(null);

	  if ($clprocjurjudicial->erro_status == 0) {
  		$lSqlErro = true;
 	  }	  
  	  $sErroMsg = $clprocjurjudicial->erro_msg;
	  
	  
	} else if ($oPost->v66_procjurtiporegra == 2 && trim($oPost->v64_protprocesso) != "" ) {

	  $clprocjuradm->v64_protprocesso = $oPost->v64_protprocesso;
	  $clprocjuradm->v64_procjur	  = $clprocjur->v62_sequencial;
	  $clprocjuradm->incluir(null);
	  
	  if ($clprocjuradm->erro_status == 0) {
  		$lSqlErro = true;
 	  }	  
      $sErroMsg = $clprocjuradm->erro_msg;
	  		
	}
	
  }
  
  db_fim_transacao($lSqlErro);
  
} else {
	
  $rsUsuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession('DB_id_usuario'),"id_usuario as v62_usuario,nome"));
  db_fieldsmemory($rsUsuarios,0);
	
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
<script>
</script>
<?
if(isset($oPost->incluir)){
	
  if ($lSqlErro){
    $clprocjur->erro(true,false);
    $db_botao=true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if($clprocjur->erro_campo!=""){
      echo "<script> document.form1.".$clprocjur->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocjur->erro_campo.".focus();</script> ";
    } 
    
  }else{
  	
    db_msgbox($sErroMsg);
    
	if ($oPost->v66_procjurtiporegra == 1 ){
      echo " <script> 																							  		   ";
	  echo "   parent.iframe_advogados.location.href='arr1_procjur111.php?codProcjur={$clprocjurjudicial->v63_sequencial}';";
	  echo "   parent.document.formaba.advogados.disabled = false; 												  		   ";
	  echo "   parent.mo_camada('advogados');																	   		   ";
      echo "   parent.iframe_processo.location.href='arr1_procjur012.php?chavepesquisa={$clprocjur->v62_sequencial}'; 	   ";
	  echo " </script>																						  	   		   ";
	} else {
      echo " <script> 																							  		   ";
      echo "   parent.iframe_processo.location.href='arr1_procjur011.php'; 	 											   ";
	  echo " </script>																						  	   		   ";		
	}
	
	
  }
}
?>