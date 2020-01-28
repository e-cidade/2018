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
include("libs/db_app.utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clface = new cl_face;
$cliframe_seleciona = new cl_iframe_seleciona;
$clface->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if(isset($setores)){

	if (is_array($setores)){
		$set = "'".implode("','",$setores)."'";
	} else {
    $setores = explode(",",$setores);
    $set = "'".implode("','",$setores)."'";
	}
	
}else{
	$set = "''";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
  db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
  db_app::load('estilos.css,grid.style.css');
?>

<script>
setores = "<?=$set; ?>";
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
  
  var oDados = new Object();
  oDados.acao = 'putsession';
  oDados.dadosQuadra = tagString(j37_quadra);
  oDados.dadosSetor  = tagString(setores);
  
  var sDados = Object.toJSON(oDados);
  var msgDiv = 'Aguarde carregando ...';
  
  js_divCarregando(msgDiv,'msgBox');
  
  sUrl = 'lotes.RPC.php';
  var sQuery = 'dados='+sDados;
  var oAjax  = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoLotes
                                        }
                                  );      
  
}

function js_retornoLotes(oAjax){
  
  //alert(oAjax.responseText);
  
  js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
    
  //alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  
  if ( aRetorno.status == 1) {
    return false;
  } else if ( aRetorno.status == 0) {
    
    j37_quadra = '';
    j34_setor  = '';
    
    parent.iframe_g4.location.href = "cad2_lotpropri006.php?j37_quadra="+j37_quadra+"&j34_setor="+j34_setor;  
  }    
  
}

</script>
</head>
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
if(isset($setores)&& $setores!=""){
  $cliframe_seleciona->campos  = "j37_setor,j37_quadra";
  $cliframe_seleciona->legenda="QUADRAS";  
  $cliframe_seleciona->sql=$clface->sql_query("","distinct j37_quadra,j37_setor","j37_setor,j37_quadra","j37_setor in ($set)");
  $cliframe_seleciona->textocabec ="darkblue";
  $cliframe_seleciona->textocorpo ="black";
  $cliframe_seleciona->fundocabec ="#aacccc";
  $cliframe_seleciona->fundocorpo ="#ccddcc";
  $cliframe_seleciona->iframe_height ="250";
  $cliframe_seleciona->iframe_width ="700";
  $cliframe_seleciona->iframe_nome ="quadras";
  $cliframe_seleciona->chaves ="j37_quadra,j37_setor";
  $cliframe_seleciona->dbscript ="onClick='parent.js_nome()'";
  $cliframe_seleciona->js_marcador="parent.js_nome()";
  $cliframe_seleciona->iframe_seleciona(@$db_opcao);   
 ?>
 <script>
   //tempo = setInterval('quadras.js_marca();clearInterval(tempo)',500);
 </script>
 <?
}else{
?>
<br>
<strong>SELECIONE UM SETOR PARA ESCOLHER A(S) QUADRAS(S)</strong>
<script type="text/javascript">
	j37_quadra = '';
	parent.iframe_g4.location.href = "cad2_lotpropri006.php?j37_quadra="+j37_quadra+"&j34_setor=<?=@$setores?>";
</script>
<?  
}
?>   
   </td>
 </tr>
  </table>
  </center>
</form>
<script>
function js_test(){

}
</script>
    </center>
	</td>
  </tr>
</table>
</body>
</html>