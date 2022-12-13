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
include("classes/db_condominio_classe.php");
include("classes/db_iptubasecondominio_classe.php");
include("classes/db_condominiocgm_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcondominio = new cl_condominio;
$clcondominiocgm = new cl_condominiocgm();
$cliptubasecondominio = new cl_iptubasecondominio();
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
	$sqlerro = false;
  db_inicio_transacao();
  
  $sQueryCondPredio = "select condominio.* from condominio inner join predio on j107_sequencial = j111_condominio 
  																			where j107_sequencial = $j107_sequencial";
  																			
  $resQUeryCondPredio = db_query($sQueryCondPredio);
  if(pg_num_rows($resQUeryCondPredio)>0){
  	$sqlerro = true;
  	$msg = "Usuário:\\n\\nExclusão Abortada\\n\\nExistem Prédios vinculados a este condomínio !\\n\\nAdministrador:\\n\\n";
  }
  if($sqlerro==false){
	  $sQueryCondCgm = "select condominiocgm.* from condominio 
	               inner join condominiocgm on j107_sequencial = j106_condominio where j107_sequencial = $j107_sequencial";	
		$resQueryCondCgm = db_query($sQueryCondCgm);
	  if(pg_num_rows($resQueryCondCgm)>0){
	  	db_fieldsmemory($resQueryCondCgm,0);
	  	$clcondominiocgm->excluir($j106_sequencial);
	  	if($clcondominiocgm->erro_status==0){
	  		$sqlerro = true;
	  	  $msg = $clcondominiocgm->erro_msg;
	  	}	  	
	  }
  }
  
  if($sqlerro==false){
		$sQueryCondBase = "select condominio.* from condominio 
	               inner join iptubasecondominio on j107_sequencial = j108_condominio where j107_sequencial = $j107_sequencial";	
		$resQueryCondBase = db_query($sQueryCondBase);
	  if(pg_num_rows($resQueryCondBase)>0){
	  	$sqlerro = true;
	  	$msg = "Usuário:\\n\\nExclusão Abortada\\n\\nExiste Matrícula vinculada a este condomínio !\\n\\nAdministrador:\\n\\n";
	  }
  } 
               
  if($sqlerro==false){            
    //$clcondominiocgm->excluir(null,"j106_condominio = $j107_sequencial ");
  	//$cliptubasecondominio->excluir(null,"j108_condominio = $j107_sequencial ");
	  $clcondominio->excluir($j107_sequencial);
  }
  
  $db_opcao = 3;
  db_fim_transacao($sqlerro);
    
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $campos = "condominio.*,cgm.z01_nome,condominiocgm.j106_numcgm";
   $result = $clcondominio->sql_record($clcondominio->sql_query_condominio($chavepesquisa,$campos)); 
  // $result = $clcondominio->sql_record($clcondominio->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcondominio.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clcondominio->erro_status=="0"){
    $clcondominio->erro(true,false);
  }else{
    $clcondominio->erro(true,true);
  }
	if($sqlerro==true){
		db_msgbox($msg);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>