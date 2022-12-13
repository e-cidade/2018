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
$db_botao=true;
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<script>
function js_testacamp(){

  var matri = document.form1.j01_matric.value;
  var inscr = document.form1.q02_inscr.value;
  var numcgm = document.form1.z01_numcgm.value;
  var certid = document.form1.v13_certid.value;
  if(matri=="" && inscr=="" && numcgm=="" && certid==""){
    alert("Informe um campo para pesquisa!");   
    return false;  
    
  }
  
return true;  
}   
</script>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post" action=""  onSubmit="return js_verifica_campos_digitados();" >
   <table border="0" cellspacing="0" cellpadding="0">
   <br>
   <br>
     <tr>   
       <td>
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
      db_input('z01_nome',30,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>

     
     <tr>   
      <td>
      <?
       db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
     
     <tr>
       <td colspan="2" align="center">
     <br>
	   <input type="submit" name="pesquisar" value="Pesquisar" onclick="return js_testacamp()" >
       </td>   	 
     </tr>	 
    </table> 	 
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
function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1';
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>
<?
if(isset($invalido)){
    echo "<script>alert('Não foi encontrado nenhuma certidão pelos dados fornecidos!')</script>";
}
?>