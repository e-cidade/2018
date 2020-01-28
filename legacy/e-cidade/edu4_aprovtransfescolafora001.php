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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Importar Aproveitamento de Aluno (Somente para alunos transferidos para fora da rede)</b></legend>
    <table border="0" width="100%">
     <tr>
      <td>
       <?db_ancora("<b>Aluno:</b>","js_pesquisatransf();",$db_opcao);?>
      </td>
      <td>
       <?db_input('matricula',15,@$Imatricula,true,'hidden',3,"")?>
       <?db_input('turmaorigem',15,@$Iturmaorigem,true,'hidden',3,"")?>
       <?db_input('turmadestino',15,@$Iturmadestino,true,'hidden',3,"")?>
       <?db_input('ed104_i_aluno',15,@$Ied104_i_aluno,true,'text',3,"")?>
       <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?><br>
      </td>
     </tr>
     <tr>
      <td>
       <b>Escola Origem:</b>
      </td>
      <td>  
       <?db_input('ed104_i_escolaorigem',15,@$Ied104_i_escolaorigem,true,'text',3,"")?>
       <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?><br>
      </td>
     </tr>
     <tr>
      <td>
       <b>Escola Destino:</b>
      </td>
      <td>  
       <?db_input('ed104_i_escoladestino',15,@$Ied104_i_escoladestino,true,'text',3,"")?>
       <?db_input('ed82_c_nome',50,@$Ied82_c_nome,true,'text',3,'')?><br>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <iframe id="iframe_trocaturma" name="iframe_trocaturma" src="" width="100%" height="800" frameborder="0"></iframe>
      </td>
     </tr>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
 function js_pesquisatransf(){
  js_OpenJanelaIframe('top.corpo','db_iframe_transfescolafora','func_transfescolafora.php?funcao_js=parent.js_mostraaluno|ed104_i_aluno|ed47_v_nome|ed104_i_escolaorigem|ed18_c_nome|ed104_i_escoladestino|ed82_c_nome|matricula|turmaorigem|turmadestino','Pesquisa de alunos transferidos para fora da rede atualmente matriculados nesta escola',true);
 }
 function js_mostraaluno(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9){
  document.form1.ed104_i_aluno.value = chave1;
  document.form1.ed47_v_nome.value = chave2;
  document.form1.ed104_i_escolaorigem.value = chave3;
  document.form1.ed18_c_nome.value = chave4;
  document.form1.ed104_i_escoladestino.value = chave5;
  document.form1.ed82_c_nome.value = chave6;
  document.form1.matricula.value = chave7;
  document.form1.turmaorigem.value = chave8;
  document.form1.turmadestino.value = chave9;
  iframe_trocaturma.location.href = 'edu4_aprovtransfescolafora002.php?matricula='+document.form1.matricula.value+'&turmaorigem='+document.form1.turmaorigem.value+'&turmadestino='+document.form1.turmadestino.value;
  db_iframe_transfescolafora.hide();  
 }
</script>