<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_autor_classe.php"));
$clautor = new cl_autor;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<?php MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>

<form name="form1" method="post">

   <center>

     <fieldset style="width:500px;margin-top:25px">
       <legend><b>Selecione o autor:</b></legend>
       <table border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" align="center">
        <tr>
         <td>
           <label for="bi01_codigo"><?php db_ancora("<b>Autor:</b>","js_pesquisabi01_codigo(true);",1); ?></label>
           <?php db_input('bi01_codigo',10,@$Ibi01_codigo,true,'text',1," onchange='js_pesquisabi01_codigo(false);'"); ?>
           <?php db_input('bi01_nome',40,@$Ibi01_nome,true,'text',3,''); ?>
         </td>
        </tr>
       </table>
     </fieldset>
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

     <fieldset style="width:95%"><legend><b>Consulta de Acervos por Autor:</b></legend>
      <table width="100%">
       <tr height="350">
        <td align="center" colspan="3">
         <iframe src="bib3_autoracervo002.php" name="framemarca" id="framemarca" width="100%" height="90%" frameborder="0"></iframe>
        </td>
       </tr>
      </table>
     </fieldset>

   </center>

</form>

<script>
function js_pesquisa(){
 if(document.form1.bi01_codigo.value==""){
  alert("Selecione um autor para pesquisa");
  document.form1.bi01_codigo.style.backgroundColor="#99A9AE";
  document.form1.bi01_codigo.focus();
 }else{
  framemarca.location.href="bib3_autoracervo002.php?todos=false&valor="+document.form1.bi01_codigo.value;
 }
}
function js_pesquisabi01_codigo(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autor','func_autor.php?funcao_js=parent.js_mostraautor1|bi01_codigo|bi01_nome','Pesquisa de Autores',true);
 }else{
  if(document.form1.bi01_codigo.value != ''){
   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autor','func_autor.php?pesquisa_chave='+document.form1.bi01_codigo.value+'&funcao_js=parent.js_mostraautor','Pesquisa',false);
  }else{
   document.form1.bi01_nome.value = '';
  }
 }
}
function js_mostraautor(chave,erro){
 document.form1.bi01_nome.value = chave;
 if(erro==true){
  document.form1.bi01_codigo.focus();
  document.form1.bi01_codigo.value = '';
  return false;
 }
 document.form1.pesquisar.onclick();
}
function js_mostraautor1(chave1,chave2){
 document.form1.bi01_codigo.value = chave1;
 document.form1.bi01_nome.value = chave2;
 document.form1.pesquisar.onclick();
 db_iframe_autor.hide();
}
</script>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>