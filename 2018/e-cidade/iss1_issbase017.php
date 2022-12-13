<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcgm          = new cl_cgm;
$clissbase      = new cl_issbase;
//$db_opcao = 1;
$db_botao = true;
$erro = "";

if(isset($postback) && $q02_inscr != 0){
	$opcao = $postback;
	$sqlerro=false;
  db_inicio_transacao();
  //$clissbase->q02_inscr=$q02_inscr;
  $q02_memo = pg_escape_string($q02_memo);
  $q02_obs  = pg_escape_string($q02_obs);
  $sQueryUpdate  = "update issbase set q02_memo = '$q02_memo',q02_obs = '$q02_obs' ";
  $sQueryUpdate .= " where q02_inscr = $q02_inscr and q02_numcgm = $q02_numcgm";   

  $result = pg_query($sQueryUpdate);
  
  if(pg_affected_rows($result) == 0){
  	$erro_banco = pg_last_error();
  	if($opcao == 1){
  		$erro  = "Inclusão ($q02_inscr) nao Incluído. Inclusao Abortada.";
      $erro .= "Valores : ".$q02_inscr."\\n\\n";
      $erro .= "Usuário: \\n\\n ";
      $erro .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$erro_banco." \\n"));
  	}else if($opcao == 2){
  		$erro  = "Alteração ($q02_inscr) nao Incluído. Alteração Abortada.";
      $erro .= "Valores : ".$q02_inscr."\\n\\n";
      $erro .= "Usuário: \\n\\n ";
      $erro .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$erro_banco." \\n"));
  	}
    $sqlerro = true;
  }else{
  	if($opcao == 1){
  	  $erro_banco = "";
  	  $erro  = "Inclusao efetuada com Sucesso\\n";
      $erro .= "Valores : ".$q02_inscr."\\n\\n";
      $erro .= "Usuário: \\n\\n ";
      $erro .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$erro_banco." \\n"));
  	}else if ($opcao == 2){
  		$erro_banco = "";
  	  $erro  = "Alteração efetuada com Sucesso\\n";
      $erro .= "Valores : ".$q02_inscr."\\n\\n";
      $erro .= "Usuário: \\n\\n ";
      $erro .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$erro_banco." \\n"));
  	}
  	
  	$sqlerro = false;
  }
  
  db_fim_transacao($sqlerro);
}

if($opcao == 1){
	$db_opcao = 1;
}else if ($opcao == 2){
	$db_opcao = 2;
	
}else if ($opcao == 3){
	$db_opcao = 3;
	$db_botao = false;
}
if(isset($chavepesquisa)){
	$q02_inscr = $chavepesquisa;
}else if (isset($q02_inscr)){
	$q02_inscr = $q02_inscr;
}
if(isset($sqlerro) && $sqlerro !== false){
	$result = $clissbase->empresa_record($clissbase->sql_query($q02_inscr,"z01_nome,q02_inscr,q02_numcgm,q02_obs, q02_memo"));

	if($clissbase->numrows > 0){
		db_fieldsmemory($result,0);
	}else{
		
	}
}else if(!isset($sqlerro)){
	$result = $clissbase->empresa_record($clissbase->sql_query($q02_inscr,"z01_nome,q02_inscr,q02_numcgm,q02_obs, q02_memo"));

	if($clissbase->numrows > 0){
		db_fieldsmemory($result,0);
	}else{
		
	}
		
}

//echo $q02_inscr."<br>";
//echo $chavepesquisa;
//echo "<br>$opcao";
//die('aqui');
//verifica o parametro na tabela parissqn para gerar sanitario automaticamente apartir do ISSQN

?>

<html>
	<head>
	  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?
				include("forms/db_frmissbasealtobs.php");
		  ?>
    </center>
    </td>
  </tr>
</table>
</center>
</body>

</html>
<?
  
if(isset($postback)){
  db_msgbox($erro);
  echo "<script>
					parent.mo_camada('atividades');					
				</script>";
  
}else {
	if ($erro != ""){
		echo "<script>
						alert($erro);		
					</script>";
		parent.mo_camada('atividades');
		db_redireciona("iss1_issbase017.php?nomenu=nops&chavepesquisa=$q02_inscr&opcao=$opcao");
	}
}
?>