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
  $result = pg_exec("select s_codigo as codigo,s_localid as localid,s_servico as servico,s_nome as nome,s_endereco as endereco,s_fone as fone,s_email as email from db_guia where s_codigo = $retorno");
  db_fieldsmemory($result,0);
}
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(s_codigo) from db_guia");
  $codigo = pg_result($result,0,0)==""?1:(integer)pg_result($result,0,0) + 1;
  pg_exec("insert into db_guia values($codigo,'$localid','".ucfirst(trim($servico))."','$nome','$endereco','$fone','$email')");
  //db_redireciona();
  echo "<script>location.href='sit1_servicos001.php'</script>\n";
  exit;
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("UPDATE db_guia SET
             s_localid = '$localid',
			 s_nome = '$nome',
			 s_servico = '".ucfirst(trim($servico))."',
			 s_endereco = '$endereco',
			 s_fone = '$fone',
			 s_email = '$email'
		   WHERE s_codigo = $codigo");		   
  db_redireciona();
  exit;
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from db_guia where s_codigo = ".$HTTP_POST_VARS["codigo"]);
  db_redireciona();
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_iniciar() {
  if(document.form1)
    document.form1.nome.focus();
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" onLoad="js_iniciar()">
<? if(!isset($HTTP_POST_VARS["consultar"]) && !isset($HTTP_POST_VARS["priNoMe"]) && !isset($HTTP_POST_VARS["antNoMe"]) && !isset($HTTP_POST_VARS["proxNoMe"]) && !isset($HTTP_POST_VARS["ultNoMe"])) { ?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
    <table width="50%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
            <td width="23%" height="30"><strong>Nome:</strong></td>
            <td width="77%" height="30"> 
              <input name="nome" type="text" value="<?=@$nome?>" size="50"></td>
      </tr>
      <tr> 
        <td height="30"><strong>Servi&ccedil;o:</strong></td>
        <td height="30"><input name="servico" type="text" value="<?=@$servico?>" size="50"></td>
      </tr>
      <tr>
            <td height="30"><strong>Localidade:</strong></td>
            <td height="30"><input name="localid" type="text" value="<?=@$localid?>" size="50"></td>
      </tr>
      <tr> 
        <td height="30"><strong>Endere&ccedil;o:</strong></td>
        <td height="30"> <input name="endereco" type="text" value="<?=@$endereco?>" size="50"> 
          <input name="codigo" type="hidden" value="<?=@$codigo?>"> </td>
      </tr>
      <tr> 
        <td height="30"><strong>Fone:</strong></td>
        <td height="30"><input name="fone" type="text" value="<?=@$fone?>" size="50"></td>
      </tr>
      <tr> 
        <td height="30"><strong>Email:</strong></td>
        <td height="30"><input name="email" type="text" value="<?=@$email?>" size="50"></td>
      </tr>
      <tr> 
        <td height="30">&nbsp;</td>
        <td height="30"><input name="incluir" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
          <input name="alterar" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
          <input name="excluir" type="submit" id="excluir" onClick="return confirm('Voce quer realmente excluir este serviço?')" value="Excluir" <? echo !isset($retorno)?"disabled":"" ?>>
              <input name="consultar" type="submit" id="consultar" onClick="this.form.target = 'consulta'" value="Procurar"></td>
      </tr>
    </table>
  </form>
    <iframe name="consulta" src="" width="750" height="190"></iframe>
  </td>
</tr>
</table>	
	</td>
  </tr>
</table>
<? } else { ?>
<?
  db_postmemory($HTTP_POST_VARS);
  if(!empty($nome))
    $filtro = "and upper(s_nome) like upper('$nome%')";
  if(isset($HTTP_POST_VARS["filtro"]))
     $filtro = base64_decode($HTTP_POST_VARS["filtro"]);
	 
  $sql = "select s_codigo as db_codigo,s_localid as localidade,s_servico,s_nome,s_endereco as endereço 
          from db_guia 
		  where 2 > 1
		  ".@$filtro."
		  order by s_localid,s_servico,s_nome";
		  
  echo "<center>\n";
  db_lov($sql,100,"sit1_servicos001.php",base64_encode(@$filtro),"corpo");
  //db_lov($query,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe") { 
  echo "</center>\n";
?>
<? } ?>
	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>