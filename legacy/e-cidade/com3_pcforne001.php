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
require("libs/db_app.utils.php");
include("classes/db_pcforne_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcforne = new cl_pcforne;
$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("pc60_numcgm");
$clrotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_consulta(){
	if (document.form1.pc60_numcgm.value!=""){
		js_OpenJanelaIframe('top.corpo','db_iframe','com3_pcforne002.php?pc60_numcgm='+document.form1.pc60_numcgm.value,'Consulta Fornecedor',true);
	}else{
	 	alert('Informe um Fornecedor!!');
	 	document.form1.pc60_numcgm.focus();
	}
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.pc60_numcgm.focus();" >
<br /><br />
  <center>
    <form name='form1'>
      <fieldset style="width: 600px; height: 40px;">
      <legend style="font-weight: bold; font-size: 13px;">&nbsp;Consulta Fornecedores&nbsp;</legend>
        <table border="0" />
          <tr>
            <td nowrap title="<?=@$Tpc60_numcgm?>" align="right">
              <?
                db_ancora(@$Lpc60_numcgm,"js_pesquisa(true);",1);
              ?>
            </td>
            <td>
              <?
                db_input('pc60_numcgm',8,$Ipc60_numcgm,true,'text',1," onchange='js_pesquisa(false);'");
                echo "&nbsp;";
                db_input('z01_nome',50,@$Iz01_nome,true,'text',1,'');
              ?>        
            </td>
          </tr>  
        </table>
      </fieldset>
      <br />
      <input type='button' name='consultar' value='Consultar' onclick='js_consulta();'>
    </form>
  </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
oAutoComplete = new dbAutoComplete($('z01_nome'),'com4_pesquisafornecedor.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('pc60_numcgm'));
oAutoComplete.show();



function js_pesquisa(mostra){
	if (mostra==true){
  		js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','func_pcfornealt.php?funcao_js=parent.js_mostra1|pc60_numcgm|z01_nome','Pesquisa',true);
	}else{
		js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','func_pcfornealt.php?pesquisa_chave='+document.form1.pc60_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }
    if(document.form1.pc60_numcgm.value==""){
    	document.form1.z01_nome.value="";
    }
}
function js_mostra(nome,erro){
	document.form1.z01_nome.value=nome;
	if (erro==true){
		document.form1.pc60_numcgm.value="";
		document.form1.pc60_numcgm.focus();
	}	
}
function js_mostra1(numcgm,nome){
	document.form1.pc60_numcgm.value=numcgm;
	document.form1.z01_nome.value=nome;
	db_iframe_pcforne.hide();
}
</script>