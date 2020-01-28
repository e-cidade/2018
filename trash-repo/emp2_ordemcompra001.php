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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("m51_codordem");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_emite(){
  if (document.form1.m51_codordem_ini==''&&document.form1.m51_codordem_fim==''){
    alert('Selecione alguma Ordem de Compra!!');
  }else{
    jan = window.open('emp2_ordemcompra002.php?m51_codordem_ini='+document.form1.m51_codordem_ini.value+'&m51_codordem_fim='+document.form1.m51_codordem_fim.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top: 25px; background-color: #CCCCCC;">
<center>
<fieldset style="width: 300px;">
<legend><strong>Emite Ordem de Compra</strong></legend>
<form name="form1" method="post" action="">
  <table  align="center">
    <tr>
  	<td nowrap><b>
  	<?db_ancora('Ordem de ',"js_pesquisa_matordem(true);",1);?>
  	</b>
  	</td>
  	<td>
      <? db_input('m51_codordem',8,$Im51_codordem,true,'text',4,"onchange='js_pesquisa_matordem(false);'","m51_codordem_ini")  ?>
      <strong> à </strong>
      <? db_input('m51_codordem',8,$Im51_codordem,true,'text',4,"","m51_codordem_fim")  ?>
      </td>
    </tr>
  </table>
</form>
</fieldset>
<p><input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" ></p>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

function js_pesquisa_matordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem.php?funcao_js=parent.js_mostramatordem1|m51_codordem|','Pesquisa',true);
  }else{
     if(document.form1.m51_codordem_ini.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem.php?pesquisa_chave='+document.form1.m51_codordem_ini.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.m51_codordem_ini.value = '';
       document.form1.m51_codordem_fim.value = '';
     }
  }
}
function js_mostramatordem(chave,erro){
  if(erro==true){
    alert('Ordem de Compra não existe!!');
    document.form1.m51_codordem_ini.value = '';
    document.form1.m51_codordem_fim.value = '';
    document.form1.m51_codordem_ini.focus();
  }else{
    document.form1.m51_codordem_fim.value =  document.form1.m51_codordem_ini.value ;
  }
}
function js_mostramatordem1(chave1){
   document.form1.m51_codordem_fim.value = chave1;
   document.form1.m51_codordem_ini.value = chave1;
   db_iframe_matordem.hide();
}
</script>
</body>
</html>