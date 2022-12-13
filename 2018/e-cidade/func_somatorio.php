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
include("classes/db_db_syscampo_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_syscampo = new cl_db_syscampo;
$clrotulo = new rotulocampo;
if(isset($campousar)){
  $result_campo = $cldb_syscampo->sql_record($cldb_syscampo->sql_query(null,"codcam, nomecam, conteudo, rotulo, descricao",""," codcam in (".$campousar.")"));
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_enviarvalor(){
  x = document.form1;
  parent.document.form1.campo_camporecb_somator.value = "";
  virgul = "";
	for(i=0; i<x.length; i++){
	  if(x.elements[i].type == 'checkbox'){
	    if(x.elements[i].checked == true){
	      parent.document.form1.campo_camporecb_somator.value+= virgul+x.elements[i].value;
	      virgul = ","
	    }
	  }
	}
	parent.db_iframe_somatorio.hide();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
  <tr>
    <td>
      <p>
        <fieldset>
	        <legend align='left'>
	          <b>Somatório dos campos</b>
			    </legend>
			    <table width='100%'>
   	      <?
   	      $arr = split(",",$sel);
		      $numrows = $cldb_syscampo->numrows;
		      for($i=0;$i<$numrows;$i++){
		      	db_fieldsmemory($result_campo, $i);
		      	$checkar = "";
		      	if(in_array($codcam,$arr)){
		      		$checkar = " checked ";
		      	}
		      	echo "
		              <tr>
						        <td id='td01' align='center' width='5%'>
						          <input type='checkbox' name='chk_".$codcam."' value='".$codcam."' ".$checkar." onclick='js_atualizacampos(this.name);'>
						        </td>
						        <td id='td02' align='left' width='45%'>
                      <b>".$rotulo."</b>
						        </td>
			            </tr>
            ";
			    }
			    ?>
			    </table>
        </fieldset>
      </p>
    </td>
  </tr>
  <tr>
    <td align='center'>
			<input name="Enviar" type="button" id="enviar" value="Enviar" onclick="js_enviarvalor();"> 
			<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_somatorio.hide();">
    </td>
  </tr>
  </form>
</table>
</center>
</body>
</html>
<script>
function js_atualizacampos(campo){
	x = document.form1;
	cont = 0;
	for(i=0; i<x.length; i++){
	  if(x.elements[i].type == 'checkbox'){
	    if(x.elements[i].checked == true){
	      cont++;
	    }
	  }
	}
	if(cont > 3){
		alert("Insira no máximo três (3) campos para somatório.");
		eval("x."+campo+".checked = false");
	}
}
</script>