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

if( isset( $HTTP_POST_VARS["alterar"] ) ) {

  $sSql   = "select id_usuario, senha from db_usuarios where login = '" . db_getsession("DB_login") . "'";
  $result = db_query( $sSql );

  if( pg_numrows($result) == 0 ) {

    echo "<script>alert('Login Inválido');top.location.href='index.php'</script>\n";
    exit;
  } else {

    if ( empty($HTTP_POST_VARS["novaSenha"]) ) {

      db_msgbox("Senha não pode ser nula!");
    } else if( Encriptacao::hash( $HTTP_POST_VARS["senha"] ) != pg_result($result,0,"senha") ) {

      echo "<script> alert('Senha Inválida');</script>\n";
    } else {

      $sSql = "update db_usuarios set senha = '" . Encriptacao::encriptaSenha($HTTP_POST_VARS["novaSenha"]) . "' where login =  '".db_getsession("DB_login")."'";
	    db_query( $sSql )  or die ("Erro atualizando senha");
	    db_msgbox("Senha alterada com sucesso");
	  }
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body class="body-default">

  <div class="container">

   <form name="form1" method="post" onSubmit="return js_submeter();">

    <fieldset>

      <legend>Alterar senha</legend>


      <table border="0"  class="form-container">

      <tr>
        <td width="170px"><strong>Senha atual:</strong></td>
        <td colspan="2" width="300px"><input name="senha" type="password" id="senha" size="30"></td>
      </tr>

      <tr>
        <td><strong>Nova senha:</strong></td>
        <td style="font-weight:bold"><input name="novaSenha" type="password" id="novaSenha" size="30" onkeyup="return js_forcaDaSenha();"/></td>
        <td style="text-align:left; padding-left:5px; width:300px;">Força da senha: <span id="forcaSenha"></span></td>
      </tr>

      <tr>
        <td><strong>Confirme nova senha:</strong></td>
        <td colspan="2"><input name="confirmaNovaSenha" type="password" id="confirmaNovaSenha" size="30"></td>
      </tr>

      </table>

    </fieldset>

    <input name="alterar" type="submit" id="alterar" value="Alterar Senha" />

   </form>

  </div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_submeter() {

  F = document.form1;

  if(F.novaSenha.value != F.confirmaNovaSenha.value) {

    alert("As senhas estão diferentes, verifique!");
	  F.novaSenha.select();
	  return false;
  }

  F.confirmaNovaSenha.disabled = true;
  F.senha.value                = calcMD5(F.senha.value);
  return true;
}

function js_iniciar() {

  if( document.form1 ){
    document.form1.senha.focus();
  }
}

/**
 * Verifica força de senha do campo senha
 * @return void
 */
function js_forcaDaSenha() {

  var oCampoForca       = document.getElementById("forcaSenha");
  var oCampoSenha       = document.getElementById("novaSenha");

  var oForte            = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
  var oMedio            = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
  var oPoucosCaracteres = new RegExp("(?=.{6,}).*", "g");

  if (false == oPoucosCaracteres.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:red;'>Poucos Caracteres</span>";
  } else if ( oForte.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:blue;'>Forte</span>";
  } else if ( oMedio.test( oCampoSenha.value ) ) {

    oCampoForca.innerHTML = "<span style='color:orange;'>Média</span>";
  } else {

    oCampoForca.innerHTML = "<span style='color:red;'>Fraca</span>";
  }
}
</script>

</body>
</html>