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
include("dbforms/db_funcoes.php");
include("classes/db_selecaoponto_classe.php");

$oPost = db_utils::postMemory($_POST);

$clselecaoponto = new cl_selecaoponto;

$db_opcao = 1;
$db_botao = true;

if(isset($oPost->incluir)){
	
  $lSqlErro = false;
  
  db_inicio_transacao();
  
  $clselecaoponto->r72_selecao   = $oPost->r72_selecao;
  $clselecaoponto->r72_descricao = $oPost->r72_descricao;
  $clselecaoponto->r72_instit    = db_getsession('DB_instit');
  $clselecaoponto->incluir($oPost->r72_sequencial);
  
  if($clselecaoponto->erro_status==0){
    $lSqlErro = true;
  }
   
  $sErroMsg = $clselecaoponto->erro_msg;
   
  db_fim_transacao($lSqlErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" style="padding-top:15px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
			<?
			   include("forms/db_frmselecaoponto.php");
			?>
  	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($oPost->incluir)){
	
  if($lSqlErro){
  	
  	db_msgbox($sErroMsg);
  	
    if($clselecaoponto->erro_campo!=""){
      echo "<script> document.form1.".$clselecaoponto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clselecaoponto->erro_campo.".focus();</script>";
    }
    
  } else {
    db_msgbox($sErroMsg);
    db_redireciona("pes1_selecaoponto005.php?liberaaba=true&chavepesquisa={$clselecaoponto->r72_sequencial}");
  }
}
?>