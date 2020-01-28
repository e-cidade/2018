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
  $sql = "select * from db_sysfuncoes where codfuncao = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}
//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select nextval('db_sysfuncoes_codfuncao_seq')");
  $codfuncao = pg_result($result,0,0);
  $codfuncao = $codfuncao==""?"1":$codfuncao;
  pg_exec("insert into db_sysfuncoes values($codfuncao,
                                            '$nomefuncao',
                                            '$obsfuncao',
                                            '$corpofuncao',
                                            '$triggerfuncao')") or die("Erro(23) inserindo em db_sysfuncoes");
  db_redireciona('sys1_funcoes002.php?'.base64_encode("gerar=$nomefuncao"));											
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("update db_sysfuncoes set nomefuncao = '$nomefuncao',
                                    obsfuncao = '$obsfuncao',
                                    corpofuncao = '$corpofuncao',
                                    triggerfuncao = '$triggerfuncao'
			where codfuncao = $codfuncao") or die("Erro(32) alterando db_sysfuncoes");
  db_redireciona('sys1_funcoes002.php?'.base64_encode("gerar=$nomefuncao"));
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from db_sysfuncoes where codfuncao = ".$HTTP_POST_VARS["codfuncao"]) or die("Erro(36) excluindo db_sysfuncao");			
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
    if(obj.nomefuncao.value == "") {
	  alert("Campo nome da função obrigatório!");
	  obj.nomefuncao.focus();
	  return false;
	}
    if(obj.corpofuncao.value == "") {
	  alert("Campo corpo é obrigatório!");
	  obj.corpofuncao.focus();
	  return false;
	}
  }  
  return true;
}
function js_iniciar() {
if(document.form1)
  document.form1.nomefuncao.focus();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()" >
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
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"> 
		<?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
		 $sql = "SELECT codfuncao as \"Código\",nomefuncao as \"Nome\",obsfuncao as observações
                 FROM db_sysfuncoes
			     WHERE nomefuncao like '".$HTTP_POST_VARS["nomefuncao"]."%'
                 ORDER BY nomefuncao";
		db_lov($sql,15,"sys1_funcoes001.php"); 
	  } else {
	 ?>
	  <form name="form1" method="post" onSubmit="return js_submeter(this)">
	  <input type="hidden" name="codfuncao" value="<?=@$codfuncao?>">
        <table width="80%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
			  <strong>Nome:&nbsp;</strong>
              <input name="nomefuncao" type="text" id="nomefuncao" value="<?=@$nomefuncao?>" size="40" maxlength="100">
              <strong>Trigger: </strong>
              <input name="triggerfuncao" type="radio" id="triggerfuncao" value="1" <? echo @$triggerfuncao=="1"?"checked":"" ?>>
              <strong>Fun&ccedil;&atilde;o:</strong>
              <input name="triggerfuncao" type="radio" id="triggerfuncao" value="0" <? echo @$triggerfuncao=="0"?"checked":"" ?>>
              <strong>View:</strong>
              <input name="triggerfuncao" type="radio" id="triggerfuncao" value="2" <? echo @$triggerfuncao=="2"?"checked":"" ?>>
		    </td>
          </tr>
          <tr>
            <td><strong>Observa&ccedil;&otilde;es:<br>
              <textarea name="obsfuncao" cols="140" wrap="VIRTUAL" id="obsfuncao"><?=@$obsfuncao?></textarea>
              </strong></td>
          </tr>
          <tr>
            <td><strong>Corpo:<br>
              <textarea name="corpofuncao" cols="140" rows="17" id="corpofuncao"><?=@$corpofuncao?></textarea>
              </strong></td>
          </tr>
          <tr>
            <td> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="alterar" onClick="Botao = 'alterar'" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="Botao = 'excluir';return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
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