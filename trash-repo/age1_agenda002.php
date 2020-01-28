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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(isset($retorno)) {
  $result = pg_exec("select * from db_contatos where id = $retorno");
  db_fieldsmemory($result,0);
}

if(isset($HTTP_POST_VARS["enviar"])) {
  $id = $HTTP_POST_VARS["id"];
  $organizacao = $HTTP_POST_VARS["organizacao"];
  $nome = $HTTP_POST_VARS["nome"];
  $rua = $HTTP_POST_VARS["rua"];
  $bairro = $HTTP_POST_VARS["bairro"];
  $cidade = $HTTP_POST_VARS["cidade"];
  $uf = $HTTP_POST_VARS["uf"];
  $cep = $HTTP_POST_VARS["cep"];
  $telefone = $HTTP_POST_VARS["telefone"];
  $fax = $HTTP_POST_VARS["fax"];
  $celular = $HTTP_POST_VARS["celular"];
  $obs = $HTTP_POST_VARS["obs"];
  $email = $HTTP_POST_VARS["email"];
  $pagina = $HTTP_POST_VARS["pagina"];

  pg_exec("BEGIN");
  $result = pg_exec("UPDATE db_contatos SET  organizacao = '$organizacao',
                                          nome = '$nome',
                                          rua = '$rua',
                                          bairro = '$bairro',
                                          cidade = '$cidade',
                                          uf = '$uf',
                                          cep = '$cep',
                                          telefone = '$telefone',
                                          fax = '$fax',
                                          celular = '$celular',
                                          obs = '$obs',
                                          email = '$email',
                                          pagina = '$pagina'
				     WHERE id = $id") or die("Erro(36) alterando db_contatos");
  pg_exec("COMMIT");
  db_redireciona();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submeter() {
  var expr = /[^0-9]+/;
  var F =  document.form2;
  if(F.id.value.match(expr)) {
    alert("Campo Código só aceita numeros!");
	F.id.select();
	return false;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form2) document.form2.id.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	  <?
	   if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
	     db_postmemory($HTTP_POST_VARS);
         if(!empty($id)) {
           $result = pg_exec("select id from db_contatos where id = $id");
	       if(pg_numrows($result) > 0) {
 	         db_redireciona("age1_agenda002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	         exit;
	       } else {             
             $filtro = base64_encode("id like '".$id."%' order by id");
	       }
         } else {
		   if(!empty($nome)) {
             $filtro = base64_encode("upper(nome) like upper('".$HTTP_POST_VARS["nome"]."%') order by nome");
           } else
             $filtro = base64_encode("upper(organizacao) like upper('".$HTTP_POST_VARS["organizacao"]."%') order by organizacao");
	     }
         if(isset($HTTP_POST_VARS["filtro"]))
           $filtro = $HTTP_POST_VARS["filtro"];
         $sql = "select id as db_codigo,id as Código,nome,organizacao as Organização,email from db_contatos where ".base64_decode($filtro);
	     echo "<center>";
         db_lov($sql,15,"age1_agenda002.php",$filtro);
	     echo "</center>";	  
	   } else if(!isset($retorno)) {
	  ?>
	    <center>
		<form name="form2" method="post" onSubmit="return js_submeter()">
	    <table width="60%" border="0" cellspacing="0" cellpadding="0">
	      <Tr>
		    <td><strong>Código:</strong></td>
		    <td><input type="text" name="id"></td>
	  	  </Tr>
	      <Tr>
		    <td><strong>Nome:</strong></td>
		    <td><input type="text" name="nome"></td>
		  </Tr>
		  <Tr>
		    <td><strong>Organização:</strong></td>
		    <td><input type="text" name="organizacao"></td>
		  </Tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td><input type="submit" name="procurar" value="Procurar"></td>
		  </tr>
	    </table>
		</form>
		</center>
	  <?
	    } else {
          include("forms/db_frmagenda.php");
		}
		      
      ?>
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
