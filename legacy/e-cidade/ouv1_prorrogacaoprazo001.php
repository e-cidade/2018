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
include("classes/db_procandam_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clprocandam = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clprotprocesso = new cl_protprocesso;
$clproctransand = new cl_proctransand;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_requer");
$rotulo->label("p58_numcgm");
$rotulo->label("p58_id_usuario");
$rotulo->label("p58_coddepto");
$rotulo->label("z01_nome");
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
function js_pesquisar(){

	 var processo = document.getElementById('p58_codproc').value;

	 if(processo == ''){
	 	alert('\n\nUsuário:\n\nCódigo do processo não informado !\n\nAdministrador:\n\n');
	 	document.form1.p58_codproc.focus();
	 	return false;
	 }
	  
   js_OpenJanelaIframe('','db_iframe_pesquisa','ouv1_prorrogacaoprazo002.php?codproc='+processo,'Pesquisa',true);
}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form method="post" action="" name="form1">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center"> 
    <td valign="top" bgcolor="#CCCCCC">
    <center>
    <fieldset style="width: 600px;">
    	<legend><b>Consulta Processo:</b></legend>
    	
	    <table cellspacing = 0>
			  <tr>
			    <td nowrap title="<?=@$Tp58_codproc?>">
			       <?
			       db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",$db_opcao);
			       ?>
			    </td>
			    <td> 
						<?
						db_input('p58_codproc',10,$Ip58_codproc,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'")
						?>
			      <?
						db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')
			      ?>
			    </td>
			 	</tr>   
	    </table>
	   
	   </fieldset>
	   </center> 
   </td>
  </tr>
  <tr align="center">
  	<td height="40" valign="middle">
  		<input type="button" name="pesquisar" value="Pesquisar" onclick="js_pesquisar();">
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
function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocessoouvidoria.php?arq=false&funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome&grupo=2','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocessoouvidoria.php?arq=false&pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso&grupo=2','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1; 
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2,erro){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_proc.hide();
}
document.form1.p58_codproc.focus();
</script>