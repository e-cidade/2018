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

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clauto->rotulo->label();
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
<script>
function js_testacamp(){
  var auto = document.form1.y50_codauto.value;
  if(auto == ""){
    alert("Informe um código para prosseguir!");   
    return false;  
  }
  document.form1.action = 'fis3_fandamauto00<?=$db_opcao?>.php?pri=true&abas=1&y50_codauto='+auto;
  document.form1.submit();
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
      <td nowrap title="<?=@$Ty50_codauto?>">
         <?
         db_ancora(@$Ly50_codauto,"js_auto(true);",1);
         ?>
      </td>
      <td> 
        <?
        db_input('y50_codauto',10,$Iy50_codauto,true,'text',$db_opcao," onChange='js_auto(false)'");
        db_input('y56_id_usuario',30,0,true,'text',3);
        ?>
      </td>
    </tr>
     <tr>
       <td colspan="2" align="center">
     <br>
	   <input type="button" name="vistoria" value="Confirma" onclick="return js_testacamp();" >
       </td>   	 
     </tr>	 
    </table> 	 
  </form>
  </td>
  </tr>
</table>
</body>
</html>
<script>
function js_auto(mostra){
  var auto=document.form1.y50_codauto.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_autoalt.php?funcao_js=parent.js_mostraauto|dl_auto|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_autoalt.php?pesquisa_chave='+auto+'&funcao_js=parent.js_mostraauto1','Pesquisa',false);
  }
}
function js_mostraauto(chave1,chave2){
  document.form1.y50_codauto.value = chave1;
  document.form1.y56_id_usuario.value = chave2;
  db_iframe.hide();
}
function js_mostraauto1(chave,erro){
  document.form1.y56_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y50_codauto.focus(); 
    document.form1.y50_codauto.value = ''; 
  }
}
</script>