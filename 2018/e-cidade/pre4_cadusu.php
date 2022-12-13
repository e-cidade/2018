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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

db_msgbox("Utilize a rotina de cadstro de usuário externo do módulo confiugração.");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>


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

	     $sql = "SELECT id_usuario as código,nome,login,CASE WHEN usuarioativo = '1' THEN 'Ativo'::text ELSE 'Inativo'::text END as \"Usuário Ativo\"
              FROM db_usuarios
			  where login like '".$HTTP_POST_VARS["login"]."%'
			  and usuext = 1
              ORDER BY nome";
		db_lov($sql,15,"pre4_cadusu.php");
	  } else {
        ?>
        <form name="form1" method="post" onSubmit="return js_submeter(this)">
          <input type="hidden" name="id_usuario" value="<?=@$id_usuario?>">
          <table width="452" border="0" cellspacing="0" cellpadding="0">
            <tr>
	      <td height="25" nowrap><strong>
              <?
	        db_ancora('Login',"js_cgmlogin();",'1');
	      ?>
              </strong></td>
              <td height="25" nowrap>
	      <input name="login" style="background-color:#DEB887" readonly  type="text" onBlur="js_ValidaCamposText(this,3)" id="login2" value="<?=@$login?>" size="10" maxlength="10"  onChange="js_cgmlogin1()">
	      <input readonly style="background-color:#DEB887" name="nomelogin" type="text" readonly id="nomelogin" value="<?=@$nome?>" size="30" maxlength="20">
	      </td>
            </tr>
            <tr>
              <td height="25" nowrap><strong>Email:</strong></td>
              <td height="25" nowrap><input name="email" type="text" onBlur="js_ValidaCamposText(this,3)" id="email" value="<?=@$email?>" size="50" maxlength="50" onChange="js_testaemail(this.value)"></td>
            </tr>
            <tr>
              <td height="25" nowrap><strong>Criar senha aleatória:</strong></td>
              <td height="25" valign="center" nowrap><input name="senhalouca" type="checkbox" value="1" onChange="(this.form.email.value==''?alert('campo e-mail deve ser preenchido'):'');(this.form.email.value==''?this.checked=false:'');js_apagasenha();" >
              <input name="enviaemail" type="hidden" value="" size="50" maxlength="50">
            </tr>
            <tr>
              <td height="25" nowrap><strong>Senha:</strong></td>
              <td height="25" nowrap><input name="senha" type="password" id="senha" size="30" maxlength="20">
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
              <td height="25" nowrap>&nbsp;</td>
              <td height="25" nowrap><br> <input name="incluir" accesskey="i" type="submit" id="incluir2" value="Incluir" disabled >
                &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar2" value="Alterar" disabled >
                &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" disabled >
            </tr>
          </table>
	  <script>
	  function js_testaemail(obj){
	    pesqemail.location.href = 'pre4_email.php?email='+obj;
	  }
	  </script>
          <iframe name="pesqemail" src="pre4_email.php" width="0" height="0" style="visibility:hidden"></iframe>
        </form>
        <?
		}
		?>
      </center>
    </td>
  </tr>
</table>
<iframe name="gerasenha" src="" style="visibility:hidden">
</iframe>
<script>

function js_cgmlogin(){
  frame.jan.location.href = 'func_nome.php?campos=cgm.z01_numcgm,z01_nome,z01_email,trim(z01_cgccpf) as z01_cgccpf&funcao_js=parent.js_preenchecgm|z01_numcgm|z01_nome|z01_email|z01_cgccpf';
  frame.show();
}
function js_preenchecgm(cgm,nomecgm,email,cgccpf){
  if(cgccpf == ""){
    alert('Este CGM não tem CNPJ/CPF preenchido\npor favor atualize antes de prosseguir!');
  }else{
    document.form1.login.value = cgm;
    document.form1.nomelogin.value = nomecgm;
    document.form1.email.value = email.toLowerCase();
    frame.hide();
  }
}
</script>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
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