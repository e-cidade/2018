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
include("classes/db_db_syscampodep_classe.php");
$cldb_syscampo = new cl_db_syscampo;
$cldb_syscampodep = new cl_db_syscampodep;


$clrotulo = new rotulocampo;
$clrotulo->label("nomecam");
$clrotulo->label("codcam");

$db_opcao = 1;
$db_botao = true;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


if(isset($pesquisar)){
  $cldb_syscampo->sql_record($cldb_syscampo->sql_query_file($codcam));
  if($cldb_syscampo->numrows==0){
     $erro_msg="Campo inválido!";
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
<script>
function js_selecionar(){
  bj = campos.document.form1;
  for(i=0; i<bj.pri.length; i++){
    if(bj.pri[i].checked==true){
      pri = bj.pri[i].value;
      break;
    }
  }
  obj=campos.document.getElementsByTagName("INPUT")
  var vir='';
  var secs="";
  var marcado=false;
  for(i=0; i<obj.length; i++){
    if(obj[i].type=='checkbox'){
      if(obj[i].checked==true){
	 secs += vir+obj[i].value;
	 marcado=true;
	 vir="XX";
      }
    }
  }
  if(marcado==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_camp','con3_db_syscampo003.php?segundo='+secs+'&principal='+pri,'Pesquisa',true);
  }else{
    alert('Selecione um campo!');
  }    
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name='form1'>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table border="0" align='left'> 
	<tr>
	  <td nowrap title="<?=@$Tcodcam?>" colspan='2' align='center'>
	     <?
	     db_ancora(@$Lnomecam,"js_pesquisacampo(true);",1);
	     ?>
      <?
      db_input('codcam',6,$Icodcam,true,'text',1," onchange='js_pesquisacampo(false);'");
      db_input('nomecam',40,$Inomecam,true,'text',3,'');
	     ?>
	  <td>
	</tr>
        <tr>
          <td colspan='2' align='center'>
             <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" >
          <td/>
        <tr/>
	<tr>
	  <td colspan='2'>
	    <table border="0">
	    <?
	        if(isset($codcam) && $codcam!=""){
	    ?>
	      <tr>
	        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <input name='selecionar' type='button' value='Selecionar' onclick='js_selecionar();'>
		</td>
	      </tr> 
	      <?}?>
	      <tr>
	        <td>
		     <iframe name="campos"   marginwidth="0" marginheight="0" frameborder="0" src="con3_db_syscampo002.php?codcam=<?=@$codcam?>" width="740" height="320">
		     </iframe>
                </td>
              </tr>
            </table> 
	  </td>
	</tr>
      </table>
    </center>
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisacampo(mostra){
  if(mostra==true){
        js_OpenJanelaIframe('top.corpo','db_iframe','func_db_syscampo.php?funcao_js=parent.js_mostracampo1|codcam|nomecam','Pesquisa',true);
  }else{
     if(document.form1.codcam.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe','func_db_syscampo.php?pesquisa_chave='+document.form1.codcam.value+'&funcao_js=parent.js_mostracampo','Pesquisa',false);
     }else{
       document.form1.nomecam.value = ''; 
     }
  }
}
function js_mostracampo(chave,erro){
  document.form1.nomecam.value = chave; 
  if(erro==true){ 
    document.form1.codcam.focus(); 
    document.form1.codcam.value = ''; 
  }
}
function js_mostracampo1(chave1,chave2){
  document.form1.codcam.value = chave1;
  document.form1.nomecam.value = chave2;
  db_iframe.hide();
}
</script>
<?
if(isset($erro_msg)){
  db_msgbox($erro_msg);
}
?>