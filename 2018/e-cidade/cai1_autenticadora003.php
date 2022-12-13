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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("k11_id");
$clrotulo->label("k11_local");
$clrotulo->label("k11_ipterm");

$db_opcao = 33;
if(isset($exclusao) && trim($exclusao) == "S") {
  if (isset($k11_id) && $k11_id != ""){
       $sql = "select k11_id from cfautent where k11_id = $k11_id";
  }else if (isset($k11_ipterm) && $k11_ipterm != ""){
       $sql = "select k11_ipterm from cfautent where k11_ipterm = '$k11_ipterm'";
  }

  $sql .= " and k11_instit = " . db_getsession("DB_instit");
  $result = @pg_exec($sql);

  if(@pg_numrows($result) == 0)
      db_msgbox("Registro não encontrado");
  else {
      if (isset($k11_id) && $k11_id != ""){
           $sql_delete = "delete from cfautent where k11_id = $k11_id";
      }else if (isset($k11_ipterm) && $k11_ipterm != ""){
           $sql_delete = "delete from cfautent where k11_ipterm = '$k11_ipterm'";
      }

      $sql_delete .= " and k11_instit = " . db_getsession("DB_instit") or die("Erro(12) excluindo cfautent.");
      @pg_exec($sql_delete);
      db_redireciona();
  }
} else if (isset($chavepesquisa) && trim($chavepesquisa) != ""){
      if(isset($exclusao) && trim($exclusao) == "N") {
	  db_redireciona();
      } else {
          $db_opcao = 3;
          $sql      = "select * from cfautent where k11_id = $chavepesquisa and k11_instit = " . db_getsession("DB_instit");
          $result   = @pg_exec($sql);
          db_fieldsmemory($result,0);
      }	  
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">	
	<center>
	<form method="post" name="form1">
  	  <table border="0" cellspacing="2" cellpadding="2">
	    <tr><td height="30">&nbsp;</td></tr>
	    <tr>
		  <td><?=$Lk11_id?> 
		  <?
		    db_input("k11_id",10,"","text",$db_opcao)
		  ?>
		    <?
		      db_input("k11_local",40,"","text",$db_opcao);
		    ?>
		  </td>
		</tr>
	    <tr>
		  <td>
		    <?=$Lk11_ipterm?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    <?
		      db_input("k11_ipterm",20,"","text",$db_opcao);
		    ?>
		  </td>
		</tr>
	        <tr><td>&nbsp;</td></tr>
		<tr align="center">
		  <td><input type="submit" name="excluir" value="Excluir" onClick="return js_submeter();"></td>
		</tr>
	  </table>
	  <input type="hidden" name="exclusao" value="">
	</form>
	</center>
	<script>
<?	
	  if ($db_opcao == 33){
?>
               js_pesquisa();
<?
	  }
?>	    
          function js_pesquisa(){
             js_OpenJanelaIframe('top.corpo','db_iframe_cfautent','func_cfautent.php?funcao_js=parent.js_preenchepesquisa|k11_id','Pesquisa',true);
          }

          function js_preenchepesquisa(chave){
             db_iframe_cfautent.hide();
          <?
	     if ($db_opcao != 3){
                  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
             }
          ?>
          }

          function js_submeter(){
             var str = new String(document.form1.k11_ipterm.value);
             var expr1 = /\./g;
             var expr2 = /\d{1,3}\.\d{1,3}\.\d{1,3}\.[0-9]{1,3}/;  

             if (str.length > 0){
                  if(str.match(expr1) != ".,.,." || str.match(expr2) == null) {
                      alert("Endereço IP inválido!\n Formato xxx.xxx.xxx.xxx");
             	      document.form1.k11_ipterm.select();
        	      return false;
                  }
	     }	  

             if (confirm('Você deseja realmente excluir este registro?')){
	          document.form1.exclusao.value = "S";
	          return true;
	     } else {
	          document.form1.exclusao.value = "N";
	          return true;
	     }
          }
	</script>
	</td>
  </tr>
</table>
<? 	
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>