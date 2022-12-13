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

session_start();

include_once("functions/func_sys.php");
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
mens_help();
db_mensagem("corpoprincipal","mensagemsenha");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
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
<?db_estilosite()?>
</style>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<div class="bold4" align="center">
 <br><br><br><br>
 <?=$DB_mens1?>
</div><br><br><br>
<?db_rodape();?>
</body>
</html>
