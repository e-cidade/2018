<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_utils.php");
include ("libs/db_app.utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_bens_classe.php");
$clbens = new cl_bens;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC >
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Imprimir Etiquetas</legend>
    <table class="form-container">
      <tr> 
	      <td title="<?=$Tt52_bem?>"> <? db_ancora(@$Lt52_bem,"js_pesquisa_bem(true);",1);?>  </td>
	      <td>
	        <?
	          db_input("t52_bem",8,$It52_bem,true,"text",4,"onchange='js_pesquisa_bem(false);'"); 
	          db_input("t52_descr",40,$It52_descr,true,"text",3);  
	        ?>
	      </td>
	    </tr>
    </table>
  </fieldset>
      <input value='Imprimir' type='button' id='imprimir' onclick='js_imprimeEtiquetas();'>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_imprimeEtiquetas(){
	 
	 var t52_bem = $('t52_bem').value;
	 
	 if(t52_bem == ''){
	   alert(_M("patrimonial.patrimonio.pat4_imprimeetiqueta001.informe_bem"));
	   $('t52_bem').focus();
	   return false;
	 }
	 
	 var sQuery = "?t52_bem="+t52_bem;
	 
	 js_OpenJanelaIframe('top.corpo','db_iframe_imprime','pat4_imprimeetiquetas002.php'+sQuery,'Imprimindo Etiquetas',true);

} 
   
  
function js_pesquisa_bem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabem1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t52_bem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t52_bem.value+'&funcao_js=parent.js_mostrabem','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabem(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t52_bem.focus(); 
    document.form1.t52_bem.value = ''; 
  }
}
function js_mostrabem1(chave1,chave2){
  document.form1.t52_bem.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
  
</script>
<script>

$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");

</script>