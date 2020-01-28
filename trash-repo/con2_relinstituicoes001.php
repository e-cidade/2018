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
require ("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;

$db_opcao = 1;
$db_botao = true;
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name="form1" method="post" action="">
      <center>
      <table border="0">
	<tr>
	  <td colspan="4">
	    <table>
	    <?
	    $aux = new cl_arquivo_auxiliar;
	    $aux->cabecalho  = "<strong>INSTITUIÇÕES SELECIONADAS</strong>";
	    $aux->codigo     = "codigo";
	    $aux->descr      = "nomeinst";
	    $aux->nomeobjeto = "instituicaosel";
	    $aux->funcao_js  = 'js_mostradb_config';
	    $aux->funcao_js_hide = 'js_mostradb_config1';
	    $aux->func_arquivo = "func_db_config.php";
	    $aux->nomeiframe = "db_iframe_db_config";
	    $aux->mostrar_botao_lancar = true;
	    $aux->db_opcao = 2;
	    $aux->tipo = 2;
	    $aux->top = 20;
	    $aux->linhas = 5;
	    $aux->vwidth = "420";
	    $aux->tamanho_campo_descricao = 30;
	    $aux->ordenar_itens = true;
	    $aux->funcao_gera_formulario();
	    ?>
	    </table>
	  </td>
	</tr>
	
  
	</table>
        <input name="relatorio" type="button" value="Relatório" onClick="js_relatorio();">
      </center>
      </form>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisainstit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostrainstit1|codigo|nomeinst','Pesquisa',true,'20');
  }else{
     if(document.form1.codigo.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostrainstit','Pesquisa',false);
     }else{
       document.form1.codigo.value = '';
       document.form1.nomeinst.value = "";
     }
  }
}
function js_mostrainstit(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.codigo.focus(); 
    document.form1.codigo.value = ''; 
  }
}
function js_mostrainstit1(chave1,chave2){
  document.form1.codigo.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisamodulo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_modulos.php?funcao_js=parent.js_mostramodulo1|id_item|nome_modulo','Pesquisa',true,'20');
  }else{
     if(document.form1.id_item.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe','func_db_modulos.php?pesquisa_chave='+document.form1.id_item.value+'&funcao_js=parent.js_mostramodulo','Pesquisa',false);
     }else{
       document.form1.id_item.value = '';
       document.form1.nome_modulo.value = "";
     }
  }
}
function js_mostramodulo(chave,erro){
  document.form1.nome_modulo.value = chave; 
  if(erro==true){ 
    document.form1.id_item.focus(); 
    document.form1.id_item.value = ''; 
  }
}
function js_mostramodulo1(chave1,chave2){
  document.form1.id_item.value = chave1;
  document.form1.nome_modulo.value = chave2;
  db_iframe.hide();
}
function js_relatorio(){
  
  var query        = "";
  
  if($('instituicaosel')){ 
      vir="";
      listainstituicoes="";
     
      for(x=0;x<document.form1.instituicaosel.length;x++){
        listainstituicoes+=vir+document.form1.instituicaosel.options[x].value;
        vir=",";
      } 
      if(listainstituicoes!=""){   
        query +='instituicoes=('+listainstituicoes+')';
      } else {
        query +='instituicoes=';
      }
      
  }
    
  jan = window.open('con2_relinstituicoes002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0); 

}
  
</script>