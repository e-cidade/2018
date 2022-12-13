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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_bensmodeloetiquetapadrao_classe.php");
require_once("classes/db_bensmodeloetiquetapadrao_classe.php");

$clbensmodeloetiquetapadrao = new cl_bensmodeloetiquetapadrao();
$clrotulo = new rotulocampo;
$clbensmodeloetiquetapadrao->rotulo->label();

db_postmemory($_POST,2);

if(isset($incluir)){
	echo "<br><br>";
	echo $_FILES['fileXml']['type'];
	
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table style="padding-top:25px;" width="100%">
  <tr align="center"> 
    <td>
    <!-- input type="button" value="Teste" onClick="js_janela()" -->
    <fieldset style="width: 600px;"><legend><b>Configurar Etiqueta Bens</b></legend>
      <table>
       <tr> 
		    <td  align="left" nowrap title="modelo etiqueta"><b>Modelo Etiqueta:</b></td>
		    <td align="left" nowrap>
		      <?
		         db_input("t72_sequencial",8,$It72_sequencial,true,"text",4,"onchange='js_pesquisa_modelo(false);'"); 
		         db_input("t72_descr",40,$It72_descr,true,"text",3);  
		      ?>
		    </td>
		   </tr>
		   <tr>
        <td><b>Descrição:</b></td>
        <td>
          <?
            db_input('fileDescr',51,0,true,'text',1);
          ?>
        </td>
       </tr>
		   <tr>
        <td><b>Arquivo:</b></td>
        <td>
          <? 
            db_input('fileXml',40,0,true,'file',1);
          ?>
        </td>
       </tr>
		   
      </table>
    </fieldset>
    </td>
  </tr>  
  <tr align="center">  
    <td>
      <input value='Incluir' type='submit' id='incluir' name='incluir' onclick="return js_incluir();">
      <input value='Importar' type='button' id='importar' name='importar' onclick="js_pesquisa_modelo(true);">
    </td>
  </tr>
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_incluir(){
  
  if($('fileXml').value != '' && $('fileDescr').value == ''){
    alert('descrição nao informada invalida! ');
    $('fileDescr').focus();
    return false;
  }

  //document.form1.submit();
}


function js_imprimeEtiquetas(){
	 
	 var t52_bem = $('t52_bem').value;
	 
	 if(t52_bem == ''){
	   alert('usuário:\n\n Bem não informado!');
	   $('t52_bem').focus();
	   return false;
	 }
	 
	 var sQuery = "?t52_bem="+t52_bem;
	 
	 js_OpenJanelaIframe('top.corpo','db_iframe_imprime','pat4_imprimeetiquetas002.php'+sQuery,'Imprimindo Etiquetas',true);

} 
   
  
function js_pesquisa_modelo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiquetapadrao','func_bensmodeloetiquetapadrao.php?funcao_js=parent.js_mostramodelo1|t72_sequencial|t72_descr','Pesquisa',true);
  }else{
     if(document.form1.t72_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiquetapadrao','func_bensmodeloetiquetapadrao.php?pesquisa_chave='+document.form1.t72_sequencial.value+'&funcao_js=parent.js_mostramodelo','Pesquisa',false);
     }else{
       document.form1.t72_sequencial.value = ''; 
     }
  }
}
function js_mostramodelo(chave,erro){
  document.form1.t72_descr.value = chave; 
  if(erro==true){ 
    document.form1.t72_sequencial.focus(); 
    document.form1.t72_sequencial.value = ''; 
  }
}
function js_mostramodelo1(chave1,chave2){
  document.form1.t72_sequencial.value = chave1;
  document.form1.t72_descr.value = chave2;
  db_iframe_bensmodeloetiquetapadrao.hide();
}
  
</script>