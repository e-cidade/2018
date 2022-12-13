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

if(isset($HTTP_POST_VARS["enviar"])) {
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

  $result = pg_exec("SELECT max(id) FROM db_contatos");
  $id = pg_result($result,0,0)==""?"1":((integer)pg_result($result,0,0) + 1);
  pg_exec("BEGIN");
  $result = pg_exec("INSERT INTO db_contatos VALUES($id,
	                                             '$organizacao',
                                                 '$nome',
                                                 '$rua',
                                                 '$bairro',
                                                 '$cidade',
                                                 '$uf',
                                                 '$cep',
                                                 '$telefone',
                                                 '$fax',
                                                 '$celular',
                                                 '$obs',
                                                 '$email',
                                                 '$pagina')") or die("Erro(49) inserindo em db_contatos.");
  pg_exec("COMMIT");
  unset($id,$organizacao,$nome,$rua,$bairro,$cidade,$uf,$cep,$telefone,$fax,$celular,$obs,$email,$pagina);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.organizacao.focus()" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><br><br>
	<?
    include("forms/db_frmagenda.php");   
    ?>
	</td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>