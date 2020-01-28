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

	db_postmemory($HTTP_SERVER_VARS);
	db_postmemory($HTTP_POST_VARS);

if (isset($alterar)){
	if ((isset($instituicao))&&($conteudotexto!="")){
		pg_exec("begin");
		$sql = "
			update db_textos
			set id_instit = $instituicao,
			conteudotexto = '$conteudotexto'
			where descrtexto = '$descrtexto'
		";
		pg_exec($sql) or die ("Alteracao abortada. Erro (18).");
		pg_exec("end");
		db_msgbox("Alterado com sucesso.");
	}else{
		db_msgbox("Alteracao abortada. Erro (20).");
	}
}else if(isset($incluir)){
	if ((isset($instituicao))&&($conteudotexto!="")&&($descrtexto!="")){
		pg_exec("begin");
		$sql = "
			insert into db_textos
			values ($instituicao,'$descrtexto','$conteudotexto')
		";
		pg_exec($sql) or die ("Inclusão abortada. Erro (29).");
		pg_exec("end");
		db_msgbox("Inclusão com sucesso.");
	}else{
		db_msgbox("Inclusão abortada. Erro (32).");
	}
}
if (isset($db_opcao)) {
	$sql_db_opcao = ";
	select id_instit, descrtexto, conteudotexto
	from db_textos
	where descrtexto = '$db_opcao'
	";//
	$result_db_opcao = pg_exec($sql_db_opcao);
	$num_db_opcao = pg_numrows($result_db_opcao);
	if ($num_db_opcao!=0){
		$habilita_alteracao = true;
	}else{
		$habilita_alteracao = false;
	}
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
	function js_AlteraInstituicao(){
	  document.form1.submit();
	}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<table width="100%" cellspacing=0 border=1 cellpadding=0 align="center">
<?
if (isset($db_opcao)){// parametro db_opcao responsavel por localizar qual o texto a ser trabalhado
?>
<form action="" name="form1" method="POST">
  <tr>
    <td align="center"><b>&nbsp;Manuten&ccedil;&atilde;o de textos</b>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	</td>
  </tr>
  <tr>
    <td>&nbsp;Institui&ccedil;&atilde;o:&nbsp;
	<select name="instituicao" onchange="js_AlteraInstituicao()">
<?
	$sql = "
		select codigo, nomeinst
		from db_config
	";
	$result = pg_exec($sql);
	$num = pg_numrows($result);
	for ($i=0;$i<$num;$i++){
  if(($num_db_opcao!=0)&&(pg_result($result,$i,"codigo")==pg_result($result_db_opcao,0,"id_instit"))){
  $selected = true;
 }else{
  $selected = false;
 }
?>
		<option value="<?=@pg_result($result,$i,"codigo")?>" <?=$selected?"selected":""?> >&nbsp;<?=@pg_result($result,$i,"nomeinst")?>&nbsp;</option>
<?
	}
?>
	</select>
	</td>
  </tr>
  <tr>
    <td>&nbsp;Descri&ccedil;&atilde;o:&nbsp;
	<input type="hidden" name="descrtexto" value="<?=@$db_opcao?>" id="descrtexto">
	<input type="text" name="descrtexto2" id="descrtexto2" maxlength="20" size="22" value="<?=@$db_opcao?>" disabled>
	</td>
  </tr>
  <tr>
    <td>&nbsp;Texto:&nbsp;
	<textarea name="conteudotexto" cols="140" rows="16" id="conteudotexto"><?=@$codigoclass?><?=@pg_result($result_db_opcao,0,"conteudotexto")?></textarea>
	</td>
  </tr>
  <tr>
    <td>&nbsp;
	</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
<?
if($habilita_alteracao){
?>
	<input type="submit" name="alterar" value="Alterar" >&nbsp;
<?
}else if(!$habilita_alteracao){
?>
	<input type="submit" name="incluir" value="Incluir" >&nbsp;
<?
}
?>
	</td>
  </tr>
  </form>
 <?
 }else{ // se caiu aqui é porque o menu que chama esta pagina nao contem o parametro db_opcao
 ?>
  <tr>
    <td align="center"><B>&nbsp;Esta p&aacute;gina deve ser chamada atrav&eacute;s de um menu v&aacute;lido.</B>
	</td>
  </tr>
<?
}
?>
</table>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>