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
	echo '
	      if(!opener.parent.corpo.document.form1.DBF_numcgm){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_numcgm"); 
	        inp.setAttribute("id","DBF_numcgm");
	        inp.setAttribute("value","'.$ret[2].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
		  }else{
   	        opener.parent.corpo.document.form1.DBF_numcgm.value = "'.$ret[2].'";		
          }
	      if(!opener.parent.corpo.document.form1.DBF_nome){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_nome"); 
	        inp.setAttribute("id","DBF_nome");
	        inp.setAttribute("value","'.$ret[3].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
		  }else{
	        opener.parent.corpo.document.form1.DBF_nome.value = "'.$ret[3].'";		
          }
	      if(!opener.parent.corpo.document.form1.DBF_ender){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_ender"); 
	        inp.setAttribute("id","DBF_ender");
	        inp.setAttribute("value","'.$ret[4].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
		  }else{
	        opener.parent.corpo.document.form1.DBF_ender.value = "'.$ret[4].'";		
		  }
	      if(!opener.parent.corpo.document.form1.DBF_munic){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_munic"); 
	        inp.setAttribute("id","DBF_munic");
	        inp.setAttribute("value","'.$ret[5].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
          }else{
	        opener.parent.corpo.document.form1.DBF_munic.value = "'.$ret[5].'";		
		  }
	      if(!opener.parent.corpo.document.form1.DBF_cep){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_cep"); 
	        inp.setAttribute("id","DBF_cep");
	        inp.setAttribute("value","'.$ret[6].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
          }else{
	        opener.parent.corpo.document.form1.DBF_cep.value = "'.$ret[6].'";		
		  }
	      if(!opener.parent.corpo.document.form1.DBF_uf){ 
	        var inp = opener.parent.corpo.document.createElement("INPUT");
	        inp.setAttribute("type","hidden");
	        inp.setAttribute("name","DBF_uf"); 
	        inp.setAttribute("id","DBF_uf");
	        inp.setAttribute("value","'.$ret[7].'"); 		
	        opener.parent.corpo.document.form1.appendChild(inp);
		  }else{
	        opener.parent.corpo.document.form1.DBF_uf.value = "'.$ret[7].'";		
		  }';
	
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
    case "nome":
      $sql = "select (z01_numcgm || '##' || z01_nome || '##' || z01_numcgm || '##' || z01_nome|| '##' || z01_ender|| '##' || z01_munic|| '##' || z01_cep|| '##' || z01_uf) as db_codigo,z01_nome as Nome,z01_numcgm as Numcgm,z01_ender as Endereço,z01_munic as Municipio,z01_cep as CEP,z01_uf as UF
              from cgm
		      where upper(z01_nome) like upper('".$arg[1]."%')
		      order by z01_nome";
	  break;
    case "numcgm":
      $sql = "select (z01_numcgm || '##' || z01_numcgm || '##' || z01_numcgm || '##' || z01_nome|| '##' || z01_ender|| '##' || z01_munic|| '##' || z01_cep|| '##' || z01_uf) as db_codigo,z01_nome as Nome,z01_numcgm as Numcgm,z01_ender as Endereço,z01_munic as Municipio,z01_cep as CEP,z01_uf as UF
              from cgm
		      where z01_numcgm like '".$arg[1]."%'
		      order by z01_numcgm";
	  break;
	case "endereco":
      $sql = "select (z01_numcgm || '##' || z01_ender || '##' || z01_numcgm || '##' || z01_nome|| '##' || z01_ender|| '##' || z01_munic|| '##' || z01_cep|| '##' || z01_uf) as db_codigo,z01_nome as Nome,z01_numcgm as Numcgm,z01_ender as Endereço,z01_munic as Municipio,z01_cep as CEP,z01_uf as UF
              from cgm
		      where upper(z01_ender) like upper('".$arg[1]."%')
		      order by z01_ender";
	  break;
    case "cgccpf":
      $sql = "select (z01_numcgm || '##' || z01_cgccpf || '##' || z01_numcgm || '##' || z01_nome|| '##' || z01_ender|| '##' || z01_munic|| '##' || z01_cep|| '##' || z01_uf) as db_codigo,z01_nome as Nome,z01_numcgm as Numcgm,z01_ender as Endereço,z01_munic as Municipio,z01_cep as CEP,z01_uf as UF
              from cgm
		      where z01_cgccpf like '".$arg[1]."%'
		      order by z01_cgccpf";
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
db_lov($sql,15,"db_cgm.php?".base64_encode("campo=$campo"),$HTTP_POST_VARS["filtro"]);
?>
</td>
</tr>
</table>
</center>
</body>
</html>