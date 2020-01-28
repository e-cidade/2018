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
include("libs/db_liborcamento.php");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_abre(opcao){

  sel_instit  = new Number(document.form1.db_selinstit.value);
 if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
 }
 if (document.form1.vernivel.value != '' && document.form1.vernivel.value != document.form1.nivel.value){
    if(confirm('Voc� j� escolheu anteriormente dados do n�vel '+document.form1.vernivel.value+' , deseja altera-los?')==false) 
      return false
    else
      js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }else if(top.corpo.db_iframe_orgao != undefined){
   if(document.form1.nivel.value == document.form1.vernivel.value){
     db_iframe_orgao.show();
   }else{
     js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
   }
 }else{
   js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }
 
 
}


variavel = 1;
function js_emite(){
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
  }else{
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    setTimeout("document.form1.submit()",1000);
    return true;
  }
}
function js_limpa(){
  if(document.form1.orgaos.value != ''){
    alert('Os dados selecionados ser�o exclu�dos. Voc� dever� selecionar novamente.');
    document.form1.vernivel.value = '';
    document.form1.orgaos.value = '';
    document.form1.seleciona.click();
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="orc2_recursorecdesp002.php">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">
	<?
	db_selinstit('parent.js_limpa');
	?>
	</td>
      </tr>

      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>