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

if(isset($HTTP_POST_VARS["enviar"])) {
  pg_exec("update atendmedexa set resultado = '".$HTTP_POST_VARS["resultado"]."'
           where codate = ".db_getsession("COD_atendimento")."
		   and id_exame = ".$HTTP_POST_VARS["id_exame"]) or die("Erro atualizando atendmedexa");
  echo "<script>  parent.document.getElementById('Iresultado').style.visibility = 'hidden'; parent.location.href = 'ipa4_atenmed0045.php';  </script>\n";
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$result = pg_exec("select resultado 
                   from atendmedexa 
				   where codate = ".db_getsession("COD_atendimento")."
				   and id_exame = $id_exame");
if(pg_numrows($result) > 0)
  $resultado = pg_result($result,0,0);
else
  $resultado = "";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_fechar() {
  parent.document.getElementById('Iresultado').style.visibility = 'hidden';  
}
</script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" bgcolor="#cccccc" marginwidth="0" marginheight="0" onLoad="document.form1.resultado.focus()">
<form name="form1" method="post">
  <input type="hidden" name="id_exame" value="<?=$id_exame?>">
  <textarea name="resultado" cols="81" rows="10" id="resultado"><?=$resultado?></textarea>
  <br>
  <input name="enviar" type="submit" id="enviar" value="Enviar">
  &nbsp; 
  <input type="button" name="Submit2" value="Fechar" onClick="js_fechar()">
</form>
</body>
</html>