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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($HTTP_POST_VARS["procurar"])) {
  $campo = "varadescr";
  $campoaux = "vara";
}

if(!isset($arg)) {
  $str = split("\?",$HTTP_SERVER_VARS['QUERY_STRING']);
  $str1 = base64_decode($str[0]);
  $str2 = base64_decode($str[1]);
//  echo "$str1<br>$str2";
  parse_str($str1);
  parse_str($str2);  
}


if(isset($retorno)) {
  $ret = explode("##",$retorno);
   
  echo "
  <script>
    for(i = 0;i < opener.parent.corpo.document.form1.elements.length;i++) {
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('dbh_".$campo."') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('db_".$campo."') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	}
    opener.parent.corpo.document.form1.dbh_".$campo.".value = '".$ret[1]."';
    opener.parent.corpo.document.form1.db_".$campo.".value = '".$ret[0]."';
    opener.parent.corpo.document.form1.db_".$campoaux.".value=opener.parent.corpo.document.form1.dbh_".$campo.".value;";
//  if($campoaux!=""){
//    echo "opener.parent.corpo.document.form1.dbh_".$campoaux.".value = '".$ret[0]."';
//          opener.parent.corpo.document.form1.db_".$campoaux.".value = '".$ret[1]."';";
//  }
  echo "window.close();";
  echo "</script>
  ";
  exit;
}
//$arg = explode("==",$arg);
//$argaux = explode("==",$argaux);
if(empty($HTTP_POST_VARS["filtro"]))
  $HTTP_POST_VARS["filtro"] = $arg;
else
  $arg = $HTTP_POST_VARS["filtro"];
  
  if( $argaux !=""){
     $chave = $campoaux;
	 $chave_valor = $argaux;
  }else{
     $chave = $campo;
	 $chave_valor = $arg;
  }
  switch($chave) {
    case "vara":
      if($chave_valor!= "" && $lista == ""){
        $sql = "select (v53_codigo || '##' || v53_descr) as db_codigo,v53_codigo,v53_descr
	          from vara
			  where v53_codigo = ".$chave_valor."
		      order by v53_codigo";
        $result = db_query($sql);
	    if(pg_numrows($result)==1){
          $ret = explode("##",pg_result($result,0,0));
          echo "
          <script>
          window.blur();
          for(i = 0;i < opener.parent.corpo.document.form1.elements.length;i++) {
	        if(opener.parent.corpo.document.form1.elements[i].name.indexOf('dbh_".$campo."') != -1)
	           opener.parent.corpo.document.form1.elements[i].value = '';
	        if(opener.parent.corpo.document.form1.elements[i].name.indexOf('db_".$campo."') != -1)
	           opener.parent.corpo.document.form1.elements[i].value = '';
	      }
          opener.parent.corpo.document.form1.dbh_".$campo.".value = '".$ret[1]."';
          opener.parent.corpo.document.form1.db_".$campo.".value = '".$ret[0]."';
          opener.parent.corpo.document.form1.db_".$campoaux.".value=opener.parent.corpo.document.form1.dbh_".$campo.".value;";
          echo "window.close();";
          echo "</script>";
	    }
	  }
      $sql = "select (v53_codigo || '##' || v53_descr) as db_codigo,v53_codigo,v53_descr
	          from vara
		      where v53_codigo like '".$chave_valor."%'
		      order by v53_codigo";
      
	  break;
    case "varadescr":
      $sql = "select (v53_descr || '##' || v53_codigo) as db_codigo,v53_codigo,v53_descr
	          from vara
			  where upper(v53_descr) like upper('".$chave_valor."%')
		      order by v53_descr";
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
db_lov($sql,15,"db_vara.php?".base64_encode("campo=$campo&campoaux=$campoaux"),$HTTP_POST_VARS["filtro"]);
?>
</td>
</tr>
</table>
</center>
</body>
</html>