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
include("dbforms/db_funcoes.php");

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $sql = "select u.login as loguinho
          from db_usuarios u
	       left outer join db_usuacgm on db_usuacgm.id_usuario = u.id_usuario";
  $result = pg_exec($sql);
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    if($loguinho == $nomelogin){
      echo "<script>alert('Usuário já cadastrado!')</script>";
      db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
      exit;		   
    }
  }
  $result = pg_exec("select max(id_usuario) + 1 from db_usuarios");
  $id_usuario = pg_result($result,0,0);
  $id_usuario = $id_usuario==""?"1":$id_usuario;
  $usuarioativo = (!isset($usuarioativo) || $usuarioativo==""?"0":$usuarioativo);
  
  pg_exec("BEGIN");
  for($i = 0;$i < sizeof($instit);$i++)
   pg_exec("insert into db_userinst values(".$instit[$i].",$id_usuario)") or die("Erro(21)($i) inserindo em db_userinst: ".pg_errormessage());
   pg_exec("insert into db_usuarios (
                  id_usuario,
		  nome,
		  login,
		  senha,
		  usuarioativo,
		  email,
		  usuext)
		  values(           
                    $id_usuario,
		   '$nome',
		   '$nomelogin',
		   '".~$senha."',
		   '$usuarioativo',
		   '$email',
		   0)")
                   or die("Erro(23) inserindo em db_usuarios: ".pg_errormessage());
  if($login!=""){
    pg_exec("insert into db_usuacgm (id_usuario,cgmlogin) values($id_usuario,$login)") or die("Erro(21)($i) inserindo em db_usuacgm: ".pg_errormessage());
  } 
  pg_exec("COMMIT");
  echo "<script>alert('Inclusão efetuada com sucesso!')</script>";
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;		   
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  if($senha == "" and 1 == 2) {
    $result = pg_exec("select senha from db_usuarios where id_usuario = $id_usuario");
	$senha = pg_result($result,0,0);
  }
  $usuarioativo = (!isset($usuarioativo) || $usuarioativo==""?"0":$usuarioativo);
  pg_exec("BEGIN");
  pg_exec("delete from db_userinst where id_usuario = $id_usuario") or die("Erro(43) deletando db_userinst: ".pg_errormessage());
  for($i = 0;$i < sizeof($instit);$i++) {     
    pg_exec("insert into db_userinst values(".$instit[$i].",$id_usuario)") or die("Erro(21)($i) inserindo em db_userinst: ".pg_errormessage());  
  }
  pg_exec("delete from db_usuacgm where id_usuario = $id_usuario") or die("Erro(43) deletando db_usuacgm: ".pg_errormessage());
  if($login!="") {     
    pg_exec("insert into db_usuacgm (id_usuario,cgmlogin) values($id_usuario,$login)") or die("Erro(21)($i) inserindo em db_usuacgm: ".pg_errormessage());
  }
  $sql = "select u.id_usuario as usuariocomp ,u.login as loginho
          from db_usuarios u
	       where u.id_usuario <> $id_usuario";
  $result = pg_exec($sql);
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    if($loginho == $nomelogin){
      echo "<script>alert('Usuário já cadastrado!')</script>";
      db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
      exit;		   
    }
  }
  if($senha == "") {
    pg_exec("update db_usuarios set
	       nome = '$nome',
		       login = '$nomelogin',
		       usuarioativo = '$usuarioativo',
		       email = '$email',
		       usuext = 0
		     where id_usuario = $id_usuario") or die("Erro(38) alterando db_usuarios: ".pg_errormessage());
  } else {
    pg_exec("update db_usuarios set
	       nome = '$nome',
		       login = '$nomelogin',
		       senha = '".~$senha."',
		       usuarioativo = '$usuarioativo',
		       email = '$email',
		       usuext = 0
		     where id_usuario = $id_usuario") or die("Erro(38) alterando db_usuarios: ".pg_errormessage());
  }
  pg_exec("COMMIT");
  echo "<script>alert('Alteração efetuada com sucesso!')</script>";
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
//  exit;		     
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("BEGIN");
  pg_exec("delete from db_userinst where id_usuario = ".$HTTP_POST_VARS["id_usuario"]) or die("Erro(58) excluindo db_userinst: ".pg_errormessage());
  pg_exec("delete from db_usuacgm  where id_usuario = ".$HTTP_POST_VARS["id_usuario"]) or die("Erro(58) excluindo db_usuacgm: ".pg_errormessage());
  pg_exec("delete from db_usuarios where id_usuario = ".$HTTP_POST_VARS["id_usuario"]) or die("Erro(43) excluindo db_usuarios: ".pg_errormessage());
  pg_exec("COMMIT");  
  echo "<script>alert('Exclusão efetuada com sucesso!')</script>";
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
//  exit;  
}

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $sql = "select u.id_usuario,db_usuacgm.cgmlogin,u.nome,u.login,u.usuarioativo,u.email,u.senha ,u.usuext
          from db_usuarios u
	       left outer join db_usuacgm on db_usuacgm.id_usuario = u.id_usuario
 	  where u.id_usuario = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<script language="JavaScript" type="text/javascript">
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao == 'incluir') {  
    if(obj.nome.value == "") {
      alert("Escolha um CGM antes de prosseguir.");
      obj.login.focus();
      return false;
    }
  }
  return true;
}
function js_iniciar() {
  if(document.form1)
    document.form1.login.focus()
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
        <?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
	  
	     $sql = "SELECT u.id_usuario as código,e.cgmlogin,nome,login,CASE WHEN usuarioativo = '1' THEN 'Ativo'::text ELSE 'Inativo'::text END as \"Usuário Ativo\", usuext
              FROM db_usuarios u
	           left join db_usuacgm e on e.id_usuario = u.id_usuario
			  WHERE upper(nome) like upper('".@$HTTP_POST_VARS["nome"]."%')
			  AND login like '".@$HTTP_POST_VARS["login"]."%'
			  AND usuext = 0
              ORDER BY login";
		db_lov($sql,15,"con1_cadusu001.php"); 
	  } else {
        ?>
        <form name="form1" method="post" onSubmit="return js_submeter(this)">
          <input type="hidden" name="id_usuario" value="<?=@$id_usuario?>">
          <table width="452" border="0" cellspacing="0" cellpadding="0">
            <tr> 
	      <td height="25" nowrap><strong> 
              <?
	        db_ancora('CGM',"js_cgmlogin();",'1');
	      ?>
              </strong></td>
              <td height="25" nowrap>
	      <input name="login" type="text" onBlur="js_ValidaCamposText(this,3)" id="login2" value="<?=@$cgmlogin?>" size="10" maxlength="20"  style="background-color:#DEB887" readonly onChange="js_cgmlogin1()">
	      <input name="nome" type="text" id="nome" value="<?=@$nome?>" size="50" style="background-color:#DEB887" readonly  maxlength="50">
	      </td>
            </tr>
            <tr> 
              <td height="25" nowrap><strong>Login:</strong></td>
              <td height="25" nowrap><input name="nomelogin" type="text" value="<?=@$login?>" id="nomelogin" size="30" maxlength="20"></td>
            </tr>
            <tr> 
              <td height="25" nowrap><strong>Senha:</strong></td>
              <td height="25" nowrap><input name="senha" type="password" id="senha" size="30" maxlength="20"> &nbsp;</td>
	    </tr>
            <tr> 
              <td height="25" nowrap><strong>Verifica senha:</strong></td>
              <td height="25" nowrap><input name="ver_senha" type="password" id="ver_senha" size="30" maxlength="20"></td>
	    </tr>
            <tr> 
              <td height="25" nowrap><strong>Usu&aacute;rio Ativo:</strong></td>
              <td height="25" nowrap><input checked name="usuarioativo" type="checkbox" id="usuarioativo2" value="1" <? echo (!isset($HTTP_POST_VARS["usuarioativo"])?(@$usuarioativo=="1"?"checked":""):"checked") ?>></td>
            </tr>
            <tr> 
              <td height="25" nowrap><strong>Email:</strong></td>
              <td height="25" nowrap><input name="email" type="text" onBlur="js_ValidaCamposText(this,3)" id="email" value="<?=@$email?>" size="50" maxlength="50"></td>
            </tr>
            <tr> 
              <td height="25" valign="top" nowrap><strong>Institui&ccedil;&atilde;o:</strong></td>
              <td height="25" nowrap> <select name="instit[]" size="5" multiple>
                  <?
		  if(isset($retorno)) {		  
	        $result = pg_exec("select c.codigo,c.nomeinst,u.id_instit
                               from db_config c
                               left outer join db_userinst u							   
                               on u.id_instit = c.codigo
							   and u.id_usuario = $retorno							   
							   ");
		  } else {
		    $result = pg_exec("select codigo,nomeinst from db_config");
		  }
		  for($i = 0;$i < pg_numrows($result);$i++) {
		    
		    echo "<option ".($i==0?'selected':'')." value=\"".pg_result($result,$i,"codigo")."\" ".(pg_result($result,$i,"id_instit")==pg_result($result,$i,"codigo")?"selected":"").">".pg_result($result,$i,"nomeinst")."</option>\n";
		  }
	      ?>
                </select> </td>
            </tr>
	    
            <tr> 
              <td height="25" nowrap>&nbsp;</td>
              <td height="25" nowrap> <input name="incluir" onclick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="alterar" accesskey="a" onclick="Botao = 'alterar' "type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="Botao='excluir';return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar">
                &nbsp; <input name="limpa" type="button" onclick="location.href='con1_cadusu001.php'" value="Limpa"></td>
            </tr>
          </table>
        </form>
        <?
		}
		?>
      </center>
    </td>
  </tr>


</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_cgmlogin(){
  frame.jan.location.href = 'func_nome.php?campos=cgm.z01_numcgm,z01_nome,z01_email,trim(z01_cgccpf) as z01_cgccpf&funcao_js=parent.js_preenchecgm|z01_numcgm|z01_nome|z01_email|z01_cgccpf';
  frame.show();
}
//function js_cgmlogin1(){
//  frame1.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenchecgm1&pesquisa_chave='+document.form1.login.value;
//}
function js_preenchecgm(cgm,nomecgm,email,cgccpf){
  if(cgccpf == ""){
    alert('Este CGM não tem CNPJ/CPF preenchido\npor favor atualize antes de prosseguir!');
  }else{
    document.form1.login.value = cgm;
    document.form1.nome.value = nomecgm;
    var nome = new String(nomecgm);
    nome1 = nome.replace(' ','#');
    var pos = nome1.indexOf('#',0);
    nome = nome.substr(0,pos);
    document.form1.nomelogin.value = nome.toLowerCase();
    document.form1.email.value = email.toLowerCase();
    frame.hide();
  }
}
function js_preenchecgm1(erro,nomecgm){
  document.form1.nome.value = nomecgm;
  if(erro == true){
    document.form1.login.value = '';
    document.form1.nomelogin.value = '';
    document.form1.login.focus();
  }else{
    var nome = new String(nomecgm);
    nome1 = nome.replace(' ','#');
    var pos = nome1.indexOf('#',0);
    nome = nome.substr(0,pos);
    document.form1.nomelogin.value = nome.toLowerCase();
  }
}
</script>
</body>
</html>
<?
$func_iframe = new janela('frame','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
<?
$func_iframe = new janela('frame1','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>