<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("libs/db_conn.php");

session_start();
if(!isset($login)){
  if(!isset($DB_LOGADO)){
     session_destroy();
  }else{
    session_register("DB_acesso");  
  }
}else{
  session_register("DB_acesso");  
}
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
mens_help();
db_mensagem("corpoprincipal","mensagemsenha");
db_fieldsmemory($result,0);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
if(isset($login)){
  $result = db_query($conn,"select id_usuario,senha,cgmlogin from db_usuarios where login = '".$DB_login."'");
  if(pg_numrows($result) == 0) {
    $erroscripts = "1";
  }elseif($DB_senha != md5(~pg_result($result,0,"senha"))) {
    $erroscripts = "2";
  }else{
    session_register("DB_login");  
    $HTTP_SESSION_VARS["DB_login"] = pg_result($result,0,"cgmlogin");
    $DB_LOGADO = "";
    $sql = "select fc_permissaodbpref(".pg_result($result,0,"cgmlogin").",0,0)";
    
    $result = db_query($sql);

    if(pg_numrows($result)==0){
      
      db_redireciona("centro_pref.php?".base64_encode("erroscripts='4'"));

    }
    $HTTP_SESSION_VARS["DB_acesso"] = pg_result($result,0,0);
    
  }
}  
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
</script>
<script>
function js_alapucha(evt) {
    evt = (evt) ? evt : (window.event) ? window.event : "";
      if(evt.keyCode == 13)
	    js_submeter();
}
function js_submeter() {
  document.form1.DB_senha.value = calcMD5(document.form1.senha.value);
  document.form1.DB_login.value = document.form1.login.value;
  document.form1.senha.value = "";
  document.form1.login.value = "";
  wname = 'wname' + Math.floor(Math.random() * 10000);
  document.form1.submit();
}

</script>
<style type="text/css">
<?
db_estilosite()
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="<?=!isset($DB_LOGADO)?'js_foco()':''?>" >
<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" align="left" valign="top"><img src="imagens/cabecalho.jpg"></td>
</tr>
      </table></td>
  </tr>
  <tr>
    <td>
      <table class="bordas" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td nowrap width="90%">
            &nbsp;<a href="index.php" class="links">Principal &gt;</a>
          </td>
	  <td align="center" width="10%" >
	    <a href="#" class="links" onClick="MM_showHideLayers('<?=$nome_help?>','',(document.getElementById('<?=$nome_help?>').style.visibility == 'visible'?'hide':'show'));"><?=isset($DB_LOGADO)?"Ajuda":""?></a>
          </td>
       </tr>
     </table>  
   </td>
  </tr>
  <tr>
    <td align="left" valign="top">
      <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <?
            echo"<td width=\"140\" align=\"left\" valign=\"top\">"; 
	    db_montamenus(isset($login)?false:true);   
	    echo"<br>";
              echo "<form name=\"form1\" action=\"\" method=\"post\">";
	       echo"<table bgcolor=\"".$w01_corfundomenu."\" style=\"border:".$w01_bordamenu." ".$w01_estilomenu." ".$w01_corbordamenu."\"><tr> 
                     <td colspan=\"2\" align=\"center\">$DB_mens2:</td>
                   </tr>
                   <tr align=\"left\"> 
                     <td>Login: </td>
                     <td><input name=\"login\" type=\"text\" size=\"10\"> </td>
                   </tr>
                   <tr align=\"left\"> 
                     <td>Senha:</td>
                     <td><input name=\"senha\" onKeyUp=\"js_alapucha(event)\" type=\"password\" size=\"10\"> </td>
                   </tr>
                   <tr align=\"center\"> 
                     <td colspan=\"2\" ><input name=\"Submit\" type=\"button\" class=\"botao\" onClick=\"js_submeter()\" value=\"Acessar\"></td>
                   </tr>
		   </table>
	           <input type='hidden' name='DB_senha'>
                   <input type='hidden' name='DB_login'>
	           </form>
                   ";
    
	    echo"</td>";
	  ?>
          <td align="left" valign="top"> 
	    <table width="100%" height="100%" border="0">
            <?
            echo"<tr>
                 <td align=\"center\" valign=\"top\">$DB_mens1</td>";
            echo"<tr>";
            ?>
          </table>
       </td>
     </tr>
   </table>
</td>
</tr>
</table>
</center>
</body>
<!-- InstanceEnd --></html>

<?
if(isset($erroscripts) && !isset($DB_LOGADO)){
  if(@$erroscripts == 1)
    echo "<script>alert('Login Inválido');</script>\n";
  elseif(@$erroscripts == 2)
    echo "<script>alert('Senha Inválida');</script>\n";
  elseif(@$erroscripts == 3)
    echo "<script>alert('Acesso a rotina inválido.');</script>\n";
  elseif(@$erroscripts == 4)
    echo "<script>alert('Sem permissão de acesso, Contate a Prefeitura.');</script>\n";
}

?>
<script>
<?
if(!isset($DB_LOGADO)){
echo "
  function js_foco(){
    document.form1.login.focus();
  }
    ";
}
?>
</script>