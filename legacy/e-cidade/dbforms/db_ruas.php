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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($arg)) {
  $str = split("\?",$HTTP_SERVER_VARS['QUERY_STRING']);
  $str1 = base64_decode($str[0]);
  $str2 = base64_decode($str[1]);
  echo "$str1<br>$str2";
  parse_str($str1);
  parse_str($str2);  
}
if(isset($retorno)) {
  $ret = explode("##",$retorno);
  echo "
  <script>
    for(i = 0;i < opener.parent.corpo.document.form1.elements.length;i++) {
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('dbh_') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('db_') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	}
    opener.parent.corpo.document.form1.dbh_".$campo.".value = '".$ret[0]."';
    opener.parent.corpo.document.form1.db_".$campo.".value = '".$ret[1]."';";	
	echo "window.close();
  </script>
  ";
  exit;
}
$arg = explode("==",$arg);
if(empty($HTTP_POST_VARS["filtro"]))
  $HTTP_POST_VARS["filtro"] = $arg[1];
else
  $arg[1] = $HTTP_POST_VARS["filtro"];
  
  switch($campo) {
    case "nomeruas":
      $sql = "select (j14_codigo || '##' || j14_nome ) as db_codigoruas, j14_codigo as C�digo,j14_nome as Nome
              from ruas
		      where upper(j14_nome) like upper('".$arg[1]."%')
		      order by j14_nome";
	  break;
    case "codigoruas":
      $sql = "select (j14_codigo || '##' || j14_nome ) as db_codigoruas, j14_codigo as C�digo, j14_nome as Nome
              from ruas
		      where j14_codigo like '".$arg[1]."%'
		      order by j14_codigo";
	  break;
  }
?>
<html>
<head>
<title>Lista de Valores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onFocus="document.form5.filtro.focus()">
<center>
<table border="0" cellspacing="5" cellpadding="0">
<tr>
<td align="center" nowrap>

<form name="form5" method="post">
  <input type="text" name="filtro" value="<?=@$HTTP_POST_VARS['filtro']?>" onBlur="window.focus();">
  <input type="hidden" name="arg" value="<?=@$HTTP_POST_VARS['arg']?>">
  <input type="submit" name="procurar" value="Procurar">
</form>
</td>
</tr>
<tr>
<td align="center">
<?
db_lov($sql,15,"db_ruas.php?".base64_encode("campo=$campo"),$HTTP_POST_VARS["filtro"]);
?>
</td>
</tr>
</table>
</center>
</body>
</html>