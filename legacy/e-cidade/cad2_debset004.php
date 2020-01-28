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
include("libs/db_usuariosonline.php");
include("classes/db_face_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clface = new cl_face;
$cliframe_seleciona = new cl_iframe_seleciona;
$clface->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($j34_setor) && $j34_setor != ""){
  $setor = split(",",$j34_setor);
  $vir = "";
  $set = "";
  for($i=0;$i<count($setor);$i++){
    $set .= $vir."'".$setor[$i]."'";
    $vir = ",";
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
<script>
function js_nome(){
  j37_quadra = "";
  vir = "";
  x = 0;
  for(i=0;i<quadras.document.form1.length;i++){
   if(quadras.document.form1.elements[i].type == "checkbox"){
     if(quadras.document.form1.elements[i].checked == true){
       valor = quadras.document.form1.elements[i].value.split("_")
       j37_quadra += vir + valor[0];
       vir = ",";
       x += 1; 
     }
   }
  }
	/*
  if(x >= 1){
    parent.document.formaba.g1.disabled = false;
    parent.document.formaba.g3.disabled = false;
  }else{
    parent.document.formaba.g1.disabled = true;
    parent.document.formaba.g3.disabled = true;
  }
	*/
  parent.iframe_g3.location.href = "cad2_debset005.php?j37_quadra="+j37_quadra+"&j34_setor=<?=@$j34_setor?>";
	
}
function js_liberaaba(){
  j37_quadra = "";
	vir = "";
	x = 0;
	for(i=0;i<quadras.document.form1.length;i++){
    if(quadras.document.form1.elements[i].type == "checkbox"){
	    if(quadras.document.form1.elements[i].checked == true){
        x += 1;
      }
    }
  }
  if(x >= 1){
		parent.document.formaba.g3.disabled = false;
	}else{
	  parent.document.formaba.g3.disabled = true;
	}
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <form name="form1" method="post" action="" target="">
      <center>
      <table border="0">
        <tr>
          <td align="top" colspan="2">
      <?
	if(isset($j34_setor)&& $j34_setor!=""){
	  $cliframe_seleciona->campos  = "j37_setor,j37_quadra";
	  $cliframe_seleciona->legenda="QUADRAS";
	  $cliframe_seleciona->sql=$clface->sql_query("","distinct j37_quadra,j37_setor","j37_setor,j37_quadra","j37_setor in ($set)");
	  $cliframe_seleciona->textocabec ="darkblue";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#aacccc";
	  $cliframe_seleciona->fundocorpo ="#ccddcc";
	  $cliframe_seleciona->iframe_height ="250";
	  $cliframe_seleciona->iframe_width ="700";
	  $cliframe_seleciona->dbscript ="onClick='parent.js_liberaaba()'";
	  $cliframe_seleciona->js_marcador="parent.js_liberaaba()";
	  $cliframe_seleciona->iframe_nome ="quadras";
	  $cliframe_seleciona->chaves ="j37_quadra,j37_setor";
	  $cliframe_seleciona->iframe_seleciona(@$db_opcao);   
        }else{
	  echo "<br><strong>SELECIONE UM SETOR PARA ESCOLHER A(S) QUADRAS(S)</strong>";
          echo "<script>parent.document.formaba.g3.disabled = true;</script>";
        }
      ?>   
          </td>
        </tr>
      </table>
      </center>
      </form>
    </center>
    </td>
  </tr>
</table>
</body>
</html>