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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
if(isset($arquivofoto)){
  // Nome do novo arquivo
  $nomearq = $_FILES["arquivofoto"]["name"];
  
  // Nome do arquivo temporário gerado no /tmp
  $nometmp = $_FILES["arquivofoto"]["tmp_name"];

  // Seta o nome do arquivo destino do upload
  $arquivofoto = "tmp/$nomearq";
  
  // Faz um upload do arquivo para o local especificado
  if(copy($nometmp,$arquivofoto)){
    $href = "<a href='#' style='text-decoration:underline;' onclick='js_alterafoto();'><img src='".$arquivofoto."' border=0 width='95' height='120'></a>";
  }else{
  	db_msgbox("Erro ao enviar arquivo.");
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_enviar(){
	parent.document.form1.localrecebefoto.value = "<?=@$arquivofoto?>";
	parent.document.getElementById("fotofunc").innerHTML = "<?=@$href?>";
	parent.db_iframe_localfoto.hide();
}
function js_testacampo(){
  if(document.form1.arquivofoto.value != ""){
    document.form1.submit();
  }else{
    alert("Informe o arquivo.");
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
  <tr>
    <td nowrap title="Local foto">
      <b>Local foto:</b>
    </td>
    <td> 
			<?
   	  db_input("arquivofoto",30,0,true,"file",1);
			?>
    </td>
  </tr>
  </form>
</table>
<input name="Enviar" type="submit" id="enviar" value="Enviar" onclick="js_testacampo();"> 
<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_localfoto.hide();">
</center>
</body>
</html>
<script>
<?
if(isset($arquivofoto)){
	echo "js_enviar();";
}
?>
</script>