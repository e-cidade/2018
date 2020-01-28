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
include("dbforms/db_funcoes.php");
include("classes/db_apolice_classe.php");
$clapolice = new cl_apolice;
$clrotulo = new rotulocampo;
$clapolice->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_abre(botao){
  if(document.form1.t81_codapo.value == ""){
    document.form1.t81_codapo.style.backgroundColor='#99A9AE';
    document.form1.t81_codapo.focus();
    alert(_M("patrimonial.patrimonio.pat2_apolices001.informe_codigo_bem"));
  }else{
    jan = window.open('pat2_apolices002.php?t81_codapo='+document.form1.t81_codapo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    document.form1.t81_codapo.style.backgroundColor='';
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC onLoad="document.form1.t81_codapo.focus();">
<form class="container" name="form1" method="post">
  <fieldset>
    <legend>Relatórios - Apólices</legend>
    <table class="form-container">
      <tr> 
        <td title="<?=$Tt81_codapo?>">
          <? db_ancora(@$Lt81_codapo,"js_pesquisa_apolice(true);",1);?>  
        </td>
        <td>
          <?
             db_input("t81_codapo",8,$It81_codapo,true,"text",4,"onchange='js_pesquisa_apolice(false);'"); 
             db_input("t81_apolice",40,$It81_apolice,true,"text",3);  
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="relatorio" type="button" onclick='js_abre();'  value="Gerar relatório">
</form>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisa_apolice(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_apolice','func_apolice.php?funcao_js=parent.js_mostraapolice1|t81_codapo|t81_apolice','Pesquisa',true);
  }else{
     if(document.form1.t81_codapo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_apolice','func_apolice.php?pesquisa_chave='+document.form1.t81_codapo.value+'&funcao_js=parent.js_mostraapolice','Pesquisa',false);
     }else{
       document.form1.t81_apolice.value = ''; 
     }
  }
}
function js_mostraapolice(chave,erro){
  document.form1.t81_apolice.value = chave; 
  if(erro==true){ 
    document.form1.t81_codapo.focus(); 
    document.form1.t81_codapo.value = ''; 
  }
}
function js_mostraapolice1(chave1,chave2){
  document.form1.t81_codapo.value = chave1;
  document.form1.t81_apolice.value = chave2;
  db_iframe_apolice.hide();
}
//--------------------------------
</script>
</body>
</html>
<script>

$("t81_codapo").addClassName("field-size2");
$("t81_apolice").addClassName("field-size7");

</script>