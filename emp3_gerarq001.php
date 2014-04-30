<?php
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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("classes/db_empagegera_classe.php");
include_once("classes/db_empagetipo_classe.php");
$clempagegera = new cl_empagegera;
$clempagetipo = new cl_empagetipo;
$clrotulo     = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e87_codgera.focus();" bgcolor="#cccccc">

  
<center>

<form name="form1" method="post">


<fieldset style="margin-top: 50px; width: 600px;">
<table border='0'>

<tr> 
    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
    <td align="left" nowrap>
  <?
   db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'"); 
   db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);  
  ?>
    </td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Te83_codtipo?>"> <? db_ancora(@$Le83_codtipo,"",3);?>  </td>
    <td align="left" nowrap>
  <?
   //die($clempagetipo->sql_query_file(null,"distinct e83_codtipo,e83_descr"));
   $result_empagetipo = $clempagetipo->sql_record($clempagetipo->sql_query(null,"distinct e83_codtipo,e83_descr"));
   $db_passapar = "true";
   if($clempagetipo->numrows == 0){
     $db_passapar = "false";
   }

   db_selectrecord("e83_codtipo",$result_empagetipo,true,1,"","","","0");
  ?>
    </td>
  </tr>

</table>
</fieldset>

<div style="margin-top: 10px;">

</div>

 <input name="pes" type="button" onclick='js_OpenJanelaIframe("top.corpo","db_iframe_empagegera","func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera","Pesquisa",true);'  value="Pesquisar arquivos">      
 <input name="cons" type="button" <?=("onclick='js_abrecons($db_passapar);'")?>  value="Consultar">
 <input name="rel" type="button" <?=("onclick='js_gerarel($db_passapar);'")?>  value="Gerar relatório">

</form>




</center>



<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_abrecons(x){
  if(x==true){
    obj  = document.form1.e83_codtipodescr;
    e83_codtipodescr = "";
    for(i=0; i<obj.options.length; i++){
      if(obj.options[i].selected == true){
	e83_codtipodescr = obj.options[i].text;
      }
    }
  }
  
  if(document.form1.e87_codgera.value!="" || document.form1.e83_codtipo.value!=0){
    js_OpenJanelaIframe('top.corpo','db_iframe','emp3_gerarq002.php?lCancelado=0&e87_codgera='+document.form1.e87_codgera.value+'&e87_descgera='+document.form1.e87_descgera.value+'&e83_codtipo='+document.form1.e83_codtipo.value+'&e83_codtipodescr='+e83_codtipodescr,'Pesquisa',true);
//    jan = window.open('                          emp2_gerarq002.php?e87_codgera='+document.form1.e87_codgera.value+'&e87_descgera='+document.form1.e87_descgera.value+'&e83_codtipo='+document.form1.e83_codtipo.value+'&e83_codtipodescr='+e83_codtipodescr,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else{
    alert("Informe o código do arquivo ou selecione o tipo para gerar o relatório.");
  }
}
function js_gerarel(x){
  if(x==true){
    obj  = document.form1.e83_codtipodescr;
    e83_codtipodescr = "";
    for(i=0; i<obj.options.length; i++){
      if(obj.options[i].selected == true){
	e83_codtipodescr = obj.options[i].text;
      }
    }
  }
  
  if(document.form1.e87_codgera.value!="" || document.form1.e83_codtipo.value!=0){
    jan = window.open('emp2_gerarq002.php?lCancelado=0&e87_codgera='+document.form1.e87_codgera.value+'&e87_descgera='+document.form1.e87_descgera.value+'&e83_codtipo='+document.form1.e83_codtipo.value+'&e83_codtipodescr='+e83_codtipodescr,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else{
    alert("Informe o código do arquivo ou selecione o tipo para gerar o relatório.");
  }
  
}
function js_pesquisa_gera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  }else{
     if(document.form1.e87_codgera.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
     }else{
       document.form1.e87_descgera.value = ''; 
     }
  }
}
function js_mostragera(chave,erro){
  document.form1.e87_descgera.value = chave; 
  if(erro==true){ 
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  }
}
function js_mostragera1(chave1,chave2){
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
}
//--------------------------------
</script>
</body>
</html>