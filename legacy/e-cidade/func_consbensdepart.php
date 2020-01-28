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
include("classes/db_bens_classe.php");
include("classes/db_db_depart_classe.php");
$clbens = new cl_bens;
$cldb_depart = new cl_db_depart;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clrotulo->label("descrdepto");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(botao){
  if(document.form1.t52_depart.value == ""){
    document.form1.t52_depart.style.backgroundColor='#99A9AE';
    document.form1.t52_depart.focus();
    alert("Informe o código do departamento");
  }else{
    if(botao=="pesquisa"){
      js_OpenJanelaIframe('top.corpo','db_iframe_func_consbensdepart001','func_consbensdepart001.php?funcao_js=parent.js_mostrabens|t52_bem&t52_depart='+document.form1.t52_depart.value,'Pesquisa',true);
    }else if(botao=="relatorio"){
      jan = window.open('pat2_bensdepart002.php?t52_depart='+document.form1.t52_depart.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
    jan.moveTo(0,0);
    document.form1.t52_depart.style.backgroundColor='';
  }
}
function js_mostrabens(chave1){
  if(chave1 != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_func_consbensdepart001','func_consbens001.php?t52_bem='+chave1,'Pesquisa',true);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.t52_depart.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tt52_depart?>"> <? db_ancora(@$Lt52_depart,"js_pesquisa_depart(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("t52_depart",8,$It52_depart,true,"text",4,"onchange='js_pesquisa_depart(false);'"); 
         db_input("descrdepto",40,$Idescrdepto,true,"text",3);  
      ?>
    </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="pesquisa" type="button" onclick='js_abre(this.name);'  value="Pesquisa">
    <input name="relatorio" type="button" onclick='js_abre(this.name);'  value="Gerar relatório">
  </td>
  </tr>
  </table>
  </form>

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisa_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t52_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t52_depart.value+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
     }else{
       document.form1.t52_depart.value = ''; 
     }
  }
}
function js_mostradepart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t52_depart.focus(); 
    document.form1.t52_depart.value = ''; 
  }
}
function js_mostradepart1(chave1,chave2){
  document.form1.t52_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_depart.hide();
}
//--------------------------------
</script>
</body>
</html>