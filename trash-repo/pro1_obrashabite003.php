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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_obrashabite_classe.php");
include("classes/db_obrashabiteprot_classe.php");
include("classes/db_obrashabiteprotoff_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clobrashabite     = new cl_obrashabite;
$clobrashabiteprot = new cl_obrashabiteprot;
$clobrashabiteprotoff = new cl_obrashabiteprotoff;
$db_botao = false;
$db_opcao = 33;
$sqlerro = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Excluir"){
  
	db_inicio_transacao();
  $db_opcao = 3;
  
	
	$rsProt		 = $clobrashabiteprot->sql_record($clobrashabiteprot->sql_query(null,"*",""," ob19_codhab = $chavepesquisa")); 
	if($clobrashabiteprot->numrows > 0){
	   db_fieldsmemory($rsProt,0);
		 $clobrashabiteprot->excluir("", " ob19_codhab = $ob19_codhab");
		 if($clobrashabiteprot->erro_status == 0){
					 $erro = $clobrashabiteprot->erro_msg;
					 db_msgbox("OBRASHABITEPROT - ".$erro);
    			 $sqlerro = true;
		 }
	}
  
	
	$rsProtoff = $clobrashabiteprotoff->sql_record($clobrashabiteprotoff->sql_query(null,"*",""," ob22_codhab = $chavepesquisa")); 
	if($clobrashabiteprotoff->numrows > 0){
	   db_fieldsmemory($rsProtoff,0);
		 $clobrashabiteprotoff->excluir("", " ob22_codhab = $ob22_codhab");
		 if($clobrashabiteprotoff->erro_status == 0){
					 $erro = $clobrashabiteprotoff->erro_msg;
					 db_msgbox("OBRASHABITEPROTOFF - ".$erro);
    			 $sqlerro = true;
		 }
	}
	
	$clobrashabite->excluir($ob09_codhab);
	
	if($clobrashabite->erro_status == 0){
			$erro = $clobrashabite->erro_msg;
		  db_msgbox("OBRASHABITE - ".$erro);
   		$sqlerro = true;
	}
  
	db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clobrashabite->sql_record($clobrashabite->sql_query($chavepesquisa)); 
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
				<?
					include("forms/db_frmobrashabitealtexc.php");
				?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Excluir"){
  if($clobrashabite->erro_status=="0"){
    $clobrashabite->erro(true,false);
  }else{
    $clobrashabite->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>