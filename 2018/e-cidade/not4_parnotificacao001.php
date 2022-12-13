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
include("libs/db_usuariosonline.php");
include("classes/db_parnotificacao_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

$oPost = db_utils::postMemory($_POST);

$clparnotificacao = new cl_parnotificacao;
$db_botao = true;
$lSqlErro = false;
$db_opcao = 2;

if(isset($oPost->incluir)){

  db_inicio_transacao();
  
  $clparnotificacao->k102_tipoemissao  = $oPost->k102_tipoemissao;
  $clparnotificacao->k102_anousu 	   = $oPost->k102_anousu;
  $clparnotificacao->k102_docnotpadrao = $oPost->k102_docnotpadrao;
  $clparnotificacao->k102_instit	   = $oPost->k102_instit;
  $clparnotificacao->incluir($oPost->k102_anousu);
  
  if ($clparnotificacao->erro_status == "0") {
  	$lSqlErro = true;
  }
  
	$sMsgErro = $clparnotificacao->erro_msg;
  
  db_fim_transacao($lSqlErro);

} else if(isset($oPost->alterar)){
	
  db_inicio_transacao();
  $clparnotificacao->k102_tipoemissao  = $oPost->k102_tipoemissao;
  $clparnotificacao->k102_anousu 	   = $oPost->k102_anousu;
  $clparnotificacao->k102_docnotpadrao = $oPost->k102_docnotpadrao;
  $clparnotificacao->k102_instit	   = $oPost->k102_instit;
  $clparnotificacao->alterar($oPost->k102_anousu);
  	
  if ($clparnotificacao->erro_status == "0") {
  	$lSqlErro = true;
  }
  
	$sMsgErro = $clparnotificacao->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
} else {

  $rsConsultaPar = $clparnotificacao->sql_record($clparnotificacao->sql_query(null,"*",null,"k102_anousu = ".db_getsession("DB_anousu")." and k102_instit =".db_getsession("DB_instit")));
  
  if ($clparnotificacao->numrows > 0 ){
	$oParam = db_utils::fieldsMemory($rsConsultaPar,0);
	$k102_tipoemissao  = $oParam->k102_tipoemissao;
  	$k102_anousu       = $oParam->k102_anousu;
  	$k102_docnotpadrao = $oParam->k102_docnotpadrao;
  	$db03_descr		   = $oParam->db03_descr;
  	$k102_instit	   = $oParam->k102_instit;
  } else { 
  	$k102_anousu       = db_getsession("DB_anousu");
  	$k102_instit	   = db_getsession("DB_instit");
  	$db_opcao = 1;
  }

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
		  include("forms/db_frmparnotificacao.php");
	    ?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($oPost->incluir) || isset($oPost->alterar)){
  
  if($clparnotificacao->erro_status=="0"){
	  db_msgbox($sMsgErro);
  } else {
  	$clparnotificacao->erro(true,true);
  }
}

?>
<script>
js_tabulacaoforms("form1","k102_docnotpadrao",true,1,"k102_docnotpadrao",true);
</script>