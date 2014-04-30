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
include("classes/db_jazigos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cljazigos = new cl_jazigos;
$db_opcao = 3;
$db_botao = false;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center><br><Br>
	<?
     if(!isset($processa)){
    ?>
      <table>
      <form name="form1">
       <tr>
        <td>
         <?db_ancora("<b>Jazigo</b>","js_pesquisa_jazigo(true);",1);?>
        </td>
        <td>
         <?db_input('cm03_i_jazigo',10,$Icm03_i_jazigo,true,'text',1," onchange='js_pesquisa_jazigo(false);'")?>
         <?db_input('proprietario',40,$proprietario,true,'text',3,'')?>
        </td>
       </tr>
       <tr>
        <td><input type="submit" value="Processa" name="processa"</td>
       </tr>
      </form>
      </table>
    <?
     }else{
      $antigo="1";
      $result = $cljazigos->sql_record($cljazigos->sql_query($cm03_i_jazigo,"jazigos.*, cgm.z01_nome"));
      db_fieldsmemory($result,0);
   	  include("forms/db_frmjazigos.php");
   	 }
	?>
    </center>
	</td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_jazigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_jazigos','func_jazigos.php?funcao_js=parent.js_mostrajazigos1|cm03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm03_i_jazigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_jazigos','func_jazigos.php?pesquisa_chave='+document.form1.cm03_i_jazigo.value+'&funcao_js=parent.js_mostrajazigos','Pesquisa',false);
     }else{
       document.form1.proprietario.value = '';
     }
  }
}
function js_mostrajazigos(chave,erro){
  document.form1.proprietario.value = chave;
  if(erro==true){
    document.form1.cm03_i_jazigo.focus();
    document.form1.cm03_i_jazigo.value = '';
  }
}
function js_mostrajazigos1(chave1,chave2){
  document.form1.cm03_i_jazigo.value = chave1;
  document.form1.proprietario.value = chave2;
  db_iframe_jazigos.hide();
}
</script>
</body>
</html>