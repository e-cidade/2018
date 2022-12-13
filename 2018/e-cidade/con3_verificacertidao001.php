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

include(modification("libs/db_stdlib.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_conecta.php"));
if(isset($verifica) && @$verifica != ""){
  include(modification("classes/db_db_certidaoweb_classe.php"));
  $t1 = $verifica;
  $t = strrev($t1);
  $cod = substr($t,0,7);
  $clcertidao = new cl_db_certidaoweb;
  $result = $clcertidao->sql_record($clcertidao->sql_query("","*","","codcert = $cod"));
  db_fieldsmemory($result,0);
  if(pg_numrows($result) == 0){
    echo "<script>alert('codigo de autenticidade inválido');</script>";
  }else{
    echo "<script>window.open('gerador.php?cod=$cod','','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height=500,width=700');</script>";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Prefeitura On - Line</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_verificacertidao() {
var teste = new String(document.form1.verifica.value);
  if (document.form1.verifica.value == "" || isNaN(document.form1.verifica.value) ||  (teste.length != 42)){
    alert("Código Inválido.");
    document.form1.verifica.focus();
    document.form1.verifica.select();
    return false;
  }
}
function testa() {
  var numero = new Number(document.form1.verifica.value);
  if(isNaN(numero)){
    alert ("este campo deve ser preenchido somente com números");
    document.form1.verifica.focus();
  }
}
</script>
<style type="text/css">
.bordas {
	border: 1px solid white;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #666666;
	background-color: #00436E;
	cursor: hand;	
}
.linksmenu {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
.links {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
a.links:hover {
	font-size: 12px;
	font-weight: bold;
	color: #CCCCCC;
	text-decoration: underline;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;	
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
	background-color: #FFFFFF;
	height: 16px;
	border: 1px solid #00436E;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr align="center">
    <td>
      <form name="form1" method="post" onSubmit="return js_verificacertidao();">
        <table width="100%" border="0">
          <tr><br><br> 
            <td width="42%" align="right"><strong>Linha Digitável:&nbsp;</strong>
            </td>
            <td width="58%" align="left"><input name="verifica" type="text" value=""  size="50" maxlength="50" onBlur="testa();"></td>
          </tr>
          <tr>
            <td align="right">
            </td>              
          </tr>
          <tr> 
            <td align="center">&nbsp; </td>
            <td align="left"><input type="submit" name="Submit" value="Emitir certid&atilde;o"></td>
          </tr>
        </table>
      </form>
    </td>      
  </tr>   
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
