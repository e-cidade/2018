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
include("dbforms/db_funcoes.php");
include("classes/db_caitransf_classe.php");
include("classes/db_caitransfdest_classe.php");
include("classes/db_caitransflanc_classe.php");

db_postmemory($HTTP_POST_VARS);

$clcaitransf     = new cl_caitransf;
$clcaitransfdest = new cl_caitransfdest;
$clcaitransflanc = new cl_caitransflanc;

$db_opcao = 33;
$db_botao = true;

if(isset($excluir)){ 
  $sqlerro = false;	
  db_inicio_transacao();
  $db_opcao = 3;
  if($sqlerro==false) {
	  $clcaitransfdest->excluir($k91_transf);
	  if($clcaitransfdest->erro_status==0){
		  $erro_msg = $clcaitransfdest->erro_msg; 	
	   	  $sqlerro  = true;
          }
  }
  if($sqlerro==false) {
	  $clcaitransflanc->excluir(null,"k93_transf = $k91_transf");
	  if($clcaitransflanc->erro_status==0){
		  $erro_msg = $clcaitransflanc->erro_msg; 	
	   	  $sqlerro  = true;
          }
  }
  if ($sqlerro==false) {
          $clcaitransf->excluir($k91_transf);
          if($clcaitransf->erro_status==0){
	          $erro_msg = $clcaitransf->erro_msg; 	
                  $sqlerro  = true;
          }
  }
  
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
	$db_opcao = 3;
	$db_botao = true;
	
	$result   = $clcaitransf->sql_record(
	            $clcaitransf->sql_query_descr($chavepesquisa,
		                            "k91_transf,
					     k91_descr,
					     k91_debito,
					     k91_credito,
					     k91_finalidade,
					     k91_tipo,
					     cp1.c60_descr as debito_descr,
					     cp2.c60_descr as credito_descr
					     ",
					     null,""));
	if($clcaitransf->numrows > 0) {
		db_fieldsmemory($result,0);
	}	
	$result   = $clcaitransfdest->sql_record(
	            $clcaitransfdest->sql_query_descr($chavepesquisa,
		                                "k92_instit,
						 k92_debito,
						 k92_credito,
						 cp1.c60_descr as dest_debito_descr,
						 cp2.c60_descr as dest_credito_descr						 
						 ",
    	                                         null,""));
	if($clcaitransfdest->numrows > 0) {
		db_fieldsmemory($result,0);
	}
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcaitransf.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    $clcaitransf->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcaitransf->erro_campo!=""){
      echo "<script> document.form1.".$clcaitransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaitransf->erro_campo.".focus();</script>";
    };
  }else{
    $clcaitransf->erro(true,true);
  };
};
if ($db_opcao == 22 || $db_opcao == 33) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
?>