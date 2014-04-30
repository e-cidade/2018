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
	parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
	db_postmemory($HTTP_POST_VARS);

if(isset($incluir)) {

	pg_exec("insert into db_sysclasses values($codarq,
		'$nomclasse',
		'$descrclasse',
		'$codigoclass')") or die("Erro(17) inserindo em db_sysclasses");
	db_msgbox("classe incluída com sucesso.");
	db_redireciona();

} else if(isset($alterar)) {

	pg_exec("update db_sysclasses set descrclasse = '$descrclasse',
		codigoclass = '$codigoclass'
		where codarq = $codarq and nomclasse = '$nomclasse'") or die("Erro(32) alterando db_sysclasses");
	db_msgbox("classe alterada com sucesso.");
	db_redireciona();

} else if(isset($excluir)) {

	pg_exec("delete from db_sysclasses where codarq = $codarq and nomclasse = '$nomclasse'") or die("Erro(36) excluindo db_sysclasses");
	db_msgbox("classe excluída com sucesso.");
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
Botao = "";
function js_submeter(obj) {
	if(Botao == 'incluir' || Botao == 'alterar') {
		if(obj.nomclasse.value == "") {
			alert("Campo nome da metodo obrigatório!");
			obj.nomclasse.focus();
			return false;
		}
		if(obj.codigoclass.value == "") {
			alert("Campo texto da metodo é obrigatório!");
			obj.codigoclass.focus();
			return false;
		}
	}
	return true;
}

function js_abreJanelaTabelas(){
	document.form1.submit();
}
</script>
<style type="text/css">
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" align='center' height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
<?
if(isset($procurar)) {

	$sql = "
		SELECT  c.nomclasse,s.nomearq,c.codarq as db_codarq,c.codarq,  c.descrclasse, c.codigoclass
		FROM db_sysclasses c
		inner join db_sysarquivo s on s.codarq = c.codarq
		where c.nomclasse like '$nomclasse%'
	";
	if ($codarq != ""){
		$sql .= "and c.codarq = $codarq";
	}
        db_lovrot($sql,15,"()","","js_vai|codarq|nomclasse");
	echo "
	<script>
	function js_vai(a,b){
	  location.href='sys4_cadclasse001.php?retorno='+b+'&codarq='+a;
	}
	</script>
	
	";
	

} else if((!isset($pesquisar))&&(isset($nomearq))){
 

	$sql = "
		select codarq as db_codarq,codarq, nomearq
		from db_sysarquivo
		where nomearq like '$nomearq%'
	";
	db_lovrot($sql,15,"sys4_cadclasse001.php",$nomearq);

}else {
   if (isset($retorno)){
	$sql = "
		SELECT c.codarq, s.nomearq, c.nomclasse, c.descrclasse, c.codigoclass
		FROM db_sysclasses c
		inner join db_sysarquivo s on s.codarq = c.codarq
		where c.nomclasse like '$retorno%' ".(isset($codarq)&&$codarq!=''?"and c.codarq=$codarq":"");
	$result = pg_exec($sql);
	if (pg_numrows($result) != 0){
		db_fieldsmemory($result,0);
		$desabilitaBotaoIncluir = true;
	}else {
		$sql = "
			select codarq , nomearq
			from db_sysarquivo
			where codarq = $retorno
		";
		$result = pg_exec($sql);
		if (pg_numrows($result) != 0){
			db_fieldsmemory($result,0);
		}
	}
}

?>
<br><br>
<form name="form1" method="post" onSubmit="return js_submeter(this)">
	<input type="hidden" name="codarq" value="<?=@$codarq?>">
<table width="80%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  align="right" onclick="javascript: js_abreJanelaTabelas();">
		<strong>Nome da tabela:&nbsp;</strong>
		</td>
		<td>
			<input name="codarq" id="codarq" type="hidden" value="<?=@$codarq?>">
			<input name="nomearq" type="text" id="nomearq" value="<?=@$nomearq?>" size="20" maxlength="20" onblur="javascript: js_abreJanelaTabelas();" >
		</td>
	</tr>
	<tr>
		<td align="right" >
			<strong>Nome da Método:&nbsp;</strong></td>
		<td>
		<input name="nomclasse" type="text" id="nomclasse" value="<?=@$nomclasse?>" size="50" maxlength="50" >
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong>Descri&ccedil;&atilde;o:<br>
			<textarea name="descrclasse" cols="110" wrap="VIRTUAL" id="descrclasse"><?=@$descrclasse?></textarea></strong></td>
	</tr>
	<tr>
		<td colspan="2"><strong>Código da método:<br>
		<textarea name="codigoclass" cols="110" rows="17" id="codigoclass"><?=@$codigoclass?></textarea>
		</strong></td>
	</tr>
	<tr>
            <td colspan="2"> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)&&isset($desabilitaBotaoIncluir)?"disabled":"" ?>>
              &nbsp; <input name="alterar" onClick="Botao = 'alterar'" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)&&!isset($desabilitaBotaoIncluir)?"disabled":"" ?>>
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="Botao = 'excluir';return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)&&!isset($desabilitaBotaoIncluir)?"disabled":"" ?>>
              &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar">
              </td>
          </tr>
        </table>
      </form>
    <?
	} // fim do else do       if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
    ?>
    </td>

  </tr>
</table>
   <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>


</body>
</html>