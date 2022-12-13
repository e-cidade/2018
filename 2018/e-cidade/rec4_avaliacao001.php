<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_pesquisa() {

	var iMatricula = $F('rh01_regist');
  if ( iMatricula == ''){
  	alert('Informe numcgm ou a matricula do funcionário.');
  }else{
  	
    window.location = 'rec4_avaliacao002.php?iMatricula='+iMatricula;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >

<form name="form1" method="post">
<center>

<fieldset style="margin-top: 50px; width: 300px;">
	<legend>
		<strong>
		  Selecione a Matrícula do Servidor
		</strong>
	</legend>

	<table align="center" border="0" cellspacing="4" cellpadding="0" >
	  <tr> 
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td nowrap title="<?=@$Trh01_regist?>">
	      <?
	      db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",1);
	      ?>
	    </td>
	    <td nowrap>
	      <?
	      db_input('rh01_regist',6,$Irh01_regist,true,'text',1,"onchange='js_pesquisarh01_regist(false);'")
	      ?>
	      <?
	      db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
	      ?>
	    </td>
	  </tr>
	  <tr> 
	    <td align="center" colspan="2">
	    </td>
	  </tr>
	</table>

</fieldset>
	<br>
<input onClick="js_pesquisa();"  type="button" value="Pesquisar" name="pesquisar" onBlur='js_tabulacaoforms("form1","rh01_regist",true,0,"rh01_regist",true);'>
</center>
</form>


<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpromocao.php?lAtivo=1&sOrigem=avaliacoes&funcao_js=parent.js_mostrapessoal1|h72_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>', 'Pesquisa', true);
  }else{
    if(document.form1.rh01_regist.value != ''){ 
    //alert('dd');
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpromocao.php?lAtivo=1&sOrigem=avaliacoes&lPromocao=1&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa', false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostrapessoal(chave, erro){

//alert("chave " + chave + " erro" + erro);
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
}

js_tabulacaoforms("form1","rh01_regist",true,0,"rh01_regist",true);
</script>