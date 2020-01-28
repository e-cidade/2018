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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($pesquisar)){
  $sqlmunic = "select munic from db_config where codigo = 1";
  $result_munic = pg_exec($sqlmunic);
  db_fieldsmemory($result_munic,0);
  if ($munic == "ALEGRETE") {
    if($codbarras != "") {
      $k00_numpre = "05" . substr($codbarras,32,6);
      $k00_numpar = substr($codbarras,38,2);
      echo "<script>
	    parent.numeros.document.form1.codrec.value='".db_numpre_sp($k00_numpre,$k00_numpar)."'
		    parent.db_iframe.hide();
		    parent.numeros.document.form1.submit();
		    </script>";
      exit;
    }
  } else {
    $sql = "select *
	    from numpremigra
		    where k00_tipo = $coddiv and
			  k00_numpar = $parcela  and ";
    if($coddiv==41 || $coddiv == 43 || $coddiv == 61 || $coddiv == 8){
	  $sql .= " k00_matric = $matins";
    }else if($coddiv==42 || $coddiv == 62 ){
	  $sql .= " k00_inscr = $matins";
    }else{
      db_redireciona('db_erros.php?db_erro=Código de Divida ('.$coddiv.') Inválido.&pagina_retorno=cai4_arrecada008.php');
      exit;
    }		
    $result = pg_query($sql);
    if(pg_numrows($result)==0){
      db_redireciona('db_erros.php?db_erro=Código de Divida não Encontrado.&pagina_retorno=cai4_arrecada008.php');
      exit;     
    }
    db_fieldsmemory($result,0);
    echo "<script>
	  parent.numeros.document.form1.codrec.value='".db_numpre_sp($k00_numpre,$k00_numpar)."'
		  parent.db_iframe.hide();
		  parent.numeros.document.form1.submit();
		  </script>";
    exit;     
  }
}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.cancelapagto {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 15px;
	width: 100px;
	background-color: #AAAF96;
	border: none;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_verifica(){
  if(document.form1.coddiv.value = ""){
    parent.alert('Codigo da Dívida deverá ser Preenchido.');
    document.form1.coddiv.focus();
	return false;
  }
  if(document.form1.parcela.value = ""){
    parent.alert('Parcela deverá ser Preenchido.');
    document.form1.parcela.focus();
	return false;
  }
  if(document.form1.matins.value = ""){
    parent.alert('Inscrição/Matrícula deverá ser Preenchido.');
    document.form1.matins.focus();
	return false;
  }
  return true;
}

</script>
</head>
<body bgcolor="#AAAF96" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.codbarras.focus();">
<center>
<form name="form1" method="post" onsumit="return js_verifica();">
  <table width="49%" border="0" cellspacing="0">
    <tr> 
        <td width="50%" align="right"><strong>Código de barras:</strong></td>
      <td width="50%"><input name="codbarras" type="text" id="codbarras" size="20" maxlength="60"></td>
    </tr>
    <tr> 
        <td width="50%" align="right"><strong>Codigo D&iacute;vida:</strong></td>
      <td width="50%"><input name="coddiv" type="text" id="coddiv" size="3" maxlength="3"></td>
    </tr>
    <tr> 
        <td align="right"><strong>Parcela:</strong></td>
      <td><input name="parcela" type="text" id="parcela" size="3" maxlength="3"></td>
    </tr>
    <tr> 
        <td align="right"><strong>Inscri&ccedil;&atilde;o/Matr&iacute;cula:</strong></td>
      <td><input name="matins" type="text" id="matins" size="10" maxlength="10"></td>
    </tr>
    <tr align="center"> 
      <td colspan="2"><input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
          &nbsp;&nbsp;&nbsp; 
          <input name="retornar" type="button" id="retornar" value="Retornar" onclick="parent.db_iframe.hide()"></td>
    </tr>
  </table>
  </form>
</center>			
</body>
</html>