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
$segue = true;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$instit = db_getsession("DB_instit");
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
  $sql = "select * from tabdesc
            where     k07_codigo = ".$ret[2]." 
                  and k07_instit = $instit 
            order by k07_valorv desc";
  $result = pg_exec($sql);
  if($ret[2] != $ret[0]){
    if(pg_numrows($result)>0){
      $segue = false;
    }
  }
  if($segue == true){
    db_fieldsmemory($result,0);
    if($k07_quamin!=0 && $k07_quamin != ""){
       echo "<script>
            location.href='../cai4_recibo004.php?retorno=".$ret[2]."';
  	        </script>";
	   exit;
    }
    if(pg_numrows($result) == 0 || $ret[5] == 'sai'){
      echo "
      <script>
      for(i = 0;i < opener.parent.corpo.document.form1.elements.length;i++) {
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('dbh_') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	  if(opener.parent.corpo.document.form1.elements[i].name.indexOf('db_') != -1)
	    opener.parent.corpo.document.form1.elements[i].value = '';
	  }
      opener.parent.corpo.document.form1.dbh_".$campo.".value = '".$ret[1]."';
      opener.parent.corpo.document.form1.db_".$campo.".value = '".$ret[0]."';
      opener.parent.corpo.document.form1.db_".$campoaux.".value=opener.parent.corpo.document.form1.dbh_".$campo.".value;";
	  echo '
	    if(!opener.parent.corpo.document.form1.db_descr){ 
	      var inp = opener.parent.corpo.document.createElement("INPUT");
	      inp.setAttribute("type","hidden");
	      inp.setAttribute("name","db_descr"); 
	      inp.setAttribute("id","db_descr");
	      inp.setAttribute("value","'.$ret[3].'"); 		
	      opener.parent.corpo.document.form1.appendChild(inp);
	      var inp = opener.parent.corpo.document.createElement("INPUT");
	      inp.setAttribute("type","hidden");
	      inp.setAttribute("name","db_tipo"); 
	      inp.setAttribute("id","db_tipo");
	      inp.setAttribute("value","'.$ret[4].'"); 		
	      opener.parent.corpo.document.form1.appendChild(inp);
		}else{
	      opener.parent.corpo.document.form1.db_descr.value = "'.$ret[3].'";		
	      opener.parent.corpo.document.form1.db_tipo.value = "'.$ret[4].'";		
		}';
      echo "window.close();";
      echo "</script>
      ";
      exit;
    }
  }
}
//$arg = explode("==",$arg);
//$argaux = explode("==",$argaux);
if(empty($HTTP_POST_VARS["filtro"]) && $segue == true)
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
  if($segue == false){
    $chave_valor = $ret[2];
    $lista='';
  }
  switch($chave) {
    case "hist":
      if($chave_valor!= "" && $lista == ""){
        $sql = "select (k01_codigo || '##' || k01_descr) as db_codigo,k01_codigo,k01_descr
	            from histcalc
			    where k01_codigo = ".$chave_valor."
		        order by k01_codigo";
        $result = pg_exec($sql);
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
      $sql = "select (k01_codigo || '##' || k01_descr) as db_codigo,k01_codigo,k01_descr
	          from histcalc
		      where k01_codigo like '".$chave_valor."%'
		      order by k01_codigo";
	  break;
    case "descrhist":
      $sql = "select (k01_descr || '##' || k01_codigo) as db_codigo,k01_codigo,k01_descr
	          from histcalc
			  where upper(k01_descr) like upper('".$chave_valor."%')
		      order by k01_descr";
	  break;
    case "receita":
      if($chave_valor!= "" && $lista == ""){
        $sql = "select (k02_codigo || '##' || k02_drecei|| '##'  || k02_codigo ||'##' || k02_descr::varchar|| '##' || k02_tipo::varchar) as db_receita,k02_codigo as Codigo,k02_drecei as Descricao,k02_descr as Compl,case when k02_tipo = 'O' then 'Orçamentária' else 'Extra-Orçamentária' end
	            from tabrec
  			         inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
			    where k02_codigo = ".$chave_valor."
		        order by k02_codigo";
        $result = pg_exec($sql);
		$sqltem = true;
	    if(pg_numrows($result)==1){
          $ret = explode("##",pg_result($result,0,0));
          $sql = "select ( k02_codigo || '##' || k02_drecei|| '##'  || k02_codigo ||'##' || k02_descr::varchar|| '##' || k02_tipo::varchar || '##' || 'sai') as db_receita,tabdesc.*
                  from tabdesc, 
				               tabrec
   			           inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
                  where     k07_codigo = k02_codigo 
                        and k07_codigo = ".$ret[0]." 
                        and k07_instit = $instit 
                  order by k07_valorv desc";
          $result = pg_exec($sql);
          if(pg_numrows($result) == 0){
            echo "
            <script>
            window.blur();
            for(i = 0;i < opener.parent.corpo.document.form1.elements.length;i++) {
	          if(opener.parent.corpo.document.form1.elements[i].name.indexOf('dbh_') != -1)
	             opener.parent.corpo.document.form1.elements[i].value = '';
  	          if(opener.parent.corpo.document.form1.elements[i].name.indexOf('db_') != -1)
	             opener.parent.corpo.document.form1.elements[i].value = '';
	        }
            opener.parent.corpo.document.form1.dbh_".$campo.".value = '".$ret[1]."';
            opener.parent.corpo.document.form1.db_".$campo.".value = '".$ret[0]."';
            opener.parent.corpo.document.form1.db_".$campoaux.".value=opener.parent.corpo.document.form1.dbh_".$campo.".value;";
            echo '
	        if(!opener.parent.corpo.document.form1.db_descr){ 
	          var inp = opener.parent.corpo.document.createElement("INPUT");
	          inp.setAttribute("type","hidden");
	          inp.setAttribute("name","db_descr"); 
	          inp.setAttribute("id","db_descr");
	          inp.setAttribute("value","'.$ret[3].'"); 		
	          opener.parent.corpo.document.form1.appendChild(inp);
	          var inp = opener.parent.corpo.document.createElement("INPUT");
	          inp.setAttribute("type","hidden");
	          inp.setAttribute("name","db_tipo"); 
	          inp.setAttribute("id","db_tipo");
	          inp.setAttribute("value","'.$ret[4].'"); 		
	          opener.parent.corpo.document.form1.appendChild(inp);
		    }else{
	          opener.parent.corpo.document.form1.db_descr.value = "'.$ret[3].'";		
	          opener.parent.corpo.document.form1.db_tipo.value = "'.$ret[4].'";		
		    }';
            echo "window.close();";
            echo "</script>";
		  }else{
		     $sqltem = false;
		  }
		}
	    if($sqltem == true){
          $sql = "select (k02_codigo || '##' || k02_drecei|| '##'  || k02_codigo ||'##' || k02_descr::varchar|| '##' || k02_tipo::varchar) as db_receita,k02_codigo as Codigo,k02_drecei as Descricao,k02_descr as Compl,case when k02_tipo = 'O' then 'Orçamentária' else 'Extra-Orçamentária' end
	              from tabrec
				       inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
		          order by k02_codigo";
		}
	  }else{
        $sql = "select ( k02_codigo || '##' || k02_drecei|| '##'  || k02_codigo ||'##' || k02_descr::varchar || '##' || k02_tipo::varchar) as db_receita,k02_codigo as Codigo,k02_drecei as Descricao,k02_descr as Compl,case when k02_tipo = 'O' then 'Orçamentária' else 'Extra-Orçamentária' end
	            from tabrec
  			         inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
		        order by k02_codigo";
      }
	  break;
    case "descrreceita":
      $sql = "select ( k02_drecei || '##' || k02_codigo || '##' || k02_codigo || '##' || k02_descr::varchar || '##' || k02_tipo::varchar) as db_receita,k02_codigo as Codigo,k02_drecei as Descricao,k02_descr as Compl,case when k02_tipo = 'O' then 'Orçamentária' else 'Extra-Orçamentária' end
	          from tabrec
  			       inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
			  where k02_drecei like '".$chave_valor."%'
		      order by k02_drecei";
	  break;
   }
?>
<html>
<head>
<title>Lista de Valores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_verificavalor(){
    opener.parent.corpo.document.form1.dbh_receita.value = '<?=$rec[0]?>';
    opener.parent.corpo.document.form1.db_receita.value = '<?=$ret[0]?>';
    opener.parent.corpo.document.form1.db_descrreceita.value='<?=$ret[1]?>';
    opener.parent.corpo.document.form1.dbh_descrreceita.value='<?=$ret[0]?>';
    if(!opener.parent.corpo.document.form1.db_descr){ 
	      var inp = opener.parent.corpo.document.createElement("INPUT");
	      inp.setAttribute("type","hidden");
	      inp.setAttribute("name","db_descr"); 
	      inp.setAttribute("id","db_descr");
	      inp.setAttribute("value",'<?=$ret[3]?>'); 		
	      opener.parent.corpo.document.form1.appendChild(inp);
	      var inp = opener.parent.corpo.document.createElement("INPUT");
	      inp.setAttribute("type","hidden");
	      inp.setAttribute("name","db_tipo"); 
	      inp.setAttribute("id","db_tipo");
	      inp.setAttribute("value","<?=$ret[4]?>"); 		
	      opener.parent.corpo.document.form1.appendChild(inp);
		}else{
	      opener.parent.corpo.document.form1.db_descr.value = "<?=$k02_descr?>";		
	      opener.parent.corpo.document.form1.db_tipo.value = "<?=$k02_tipo?>";		
		}
    opener.parent.corpo.document.form1.gravar.focus();		
    window.close();
}
</script>
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
db_lov($sql,15,"db_caixa.php?".base64_encode("campo=$campo&campoaux=$campoaux"),$HTTP_POST_VARS["filtro"]);
if($sqltem == false){
  ?>
  <form>
  <input type="button" onclick="js_verificavalor()" name="Submit" value="Outro Valor"> 
  
  </form>
  <?
}
?>
      </td>
</tr>
</table>
</center>
</body>
</html>