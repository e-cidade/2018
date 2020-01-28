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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_selecaopontorubricas_classe.php");
include("classes/db_selecaopontorubricastipo_classe.php");
include("classes/db_selecaoponto_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clselecaoponto             = new cl_selecaoponto();
$clselecaopontorubricas     = new cl_selecaopontorubricas();
$clselecaopontorubricastipo = new cl_selecaopontorubricastipo();

$db_opcao = 22;
$db_botao = false;

if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
	
	$lSqlErro = false;

	$clselecaopontorubricas->r73_sequencial   = $oPost->r73_sequencial;
	$clselecaopontorubricas->r73_selecaoponto = $oPost->r73_selecaoponto;
	$clselecaopontorubricas->r73_rubric       = $oPost->r73_rubric;
	$clselecaopontorubricas->r73_instit       = db_getsession('DB_instit');
	$clselecaopontorubricas->r73_tipo         = $oPost->r73_tipo;
	
	if ( isset($oPost->r73_valor)) {
	  $clselecaopontorubricas->r73_valor = $oPost->r73_valor;
	} else {
		$clselecaopontorubricas->r73_valor = '';
	}
  	
  db_inicio_transacao();

  if ( isset($oPost->incluir) ) {
    $clselecaopontorubricas->incluir($oPost->r73_sequencial);
  } else if ( isset($oPost->alterar) ) {
  	$clselecaopontorubricas->alterar($oPost->r73_sequencial);
  } else if ( isset($oPost->excluir) ) { 
    $clselecaopontorubricas->excluir($oPost->r73_sequencial);
  } 
    
  if($clselecaopontorubricas->erro_status==0){
    $lSqlErro = true;
  }
    
  $sMsgErro         = $clselecaopontorubricas->erro_msg;
  $r73_selecaoponto = $oPost->r73_selecaoponto;  
  
  db_fim_transacao($lSqlErro);

} else if ( isset($oPost->opcao) )  {
	
   $rsPonroRubricas = $clselecaopontorubricas->sql_record($clselecaopontorubricas->sql_query($oPost->r73_sequencial));
   
   if( $clselecaopontorubricas->numrows > 0 ){
     db_fieldsmemory($rsPonroRubricas,0);
   }
   
} else if ( isset($oGet->chavepesquisa) ) {
  $r73_selecaoponto = $oGet->chavepesquisa;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" style="padding-top:15px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
			<?
			 include("forms/db_frmselecaopontorubricas.php");
			?>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
    
  db_msgbox($sMsgErro);
  
	if ( !$lSqlErro ) {
	  if($clselecaopontorubricas->erro_campo!=""){
	    echo "<script> document.form1.".$clselecaopontorubricas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	    echo "<script> document.form1.".$clselecaopontorubricas->erro_campo.".focus();</script>";
	  }
	}
  
}

?>