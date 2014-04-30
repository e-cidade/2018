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
include("classes/db_arrevenc_classe.php");
include("classes/db_arrevenclog_classe.php");
include("classes/db_arreinstit_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($_GET);
db_postmemory($_POST);
$clarrevenc = new cl_arrevenc;
$clarreinstit = new cl_arreinstit;
$clarrevenclog = new cl_arrevenclog;
$db_botao = false;
$db_opcao = 33;
$botaoiframe = 3;
$db_opcaonumpre = 3;
if(isset($excluir)){
	$sqlerro = false;
  db_inicio_transacao();
 
  $clarreinstit->sql_record($clarreinstit->sql_query_file(null,"*",null,"k00_numpre = {$k00_numpre} and k00_instit = ".db_getsession('DB_instit') ) ); 
  if ($clarreinstit->numrows == 0) {
    db_msgbox("Numpre de outra instituição inclusão abortada");
    $sqlerro = true;
  }else{
  	$clarrevenc->k00_sequencial    = $k00_sequencial;
		$clarrevenc->excluir($k00_sequencial);
		if($clarrevenc->erro_status=="0"){
			$sqlerro = true;
			$msgerro = $clarrevenc->erro_msg;
		}
		
  }
  db_fim_transacao($sqlerro);
	if($sqlerro == false){
		$msgerro = "Exclusão efetuada com sucesso.";
	}
	
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
	 $botaoiframe = 3;
   $k75_sequencial =$chavepesquisa;
  
}
if(isset($k75_sequencial) and $k75_sequencial!=""){
 	  $sqlCarrega = "select k75_usuario,login, k75_data,k75_hora,k00_numpre 
	               from arrevenclog 
								 inner join arrevenc on k00_arrevenclog=k75_sequencial 
								  inner join db_usuarios on id_usuario      = k75_usuario
								 where k75_sequencial = {$k75_sequencial}";
   $rsCarrega = pg_query($sqlCarrega);
	 $linhasCarrega= pg_num_rows($rsCarrega);
	 if($linhasCarrega>0){
	  	db_fieldsmemory($rsCarrega,0);
	 }
	 $db_opcaonumpre=3;

	 
 }
 
if(isset($k00_sequencial) && $k00_sequencial!=""){	
   
   $sqlAlt = "select * from arrevenc where k00_sequencial =$k00_sequencial";
	 $rsAlt = pg_query($sqlAlt);
	 $linhasAlt = pg_num_rows($rsAlt);
	 if($linhasAlt > 0){
	 	 db_fieldsmemory($rsAlt,0);
		 $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	
	include("forms/db_frmarrevenc.php");
	
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
if($clarrevenc->erro_status=="0"){
  $clarrevenc->erro(true,false);
}else{
  $clarrevenc->erro(true,true);
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>