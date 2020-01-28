<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_portaria_classe.php");

$clportaria = new cl_portaria();
$clportaria->rotulo->label();

 


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1">
<table style="padding-top:30px;">
  <tr> 
    <td>
      <fieldset>
        <legend align="center">
          <b>Impressão da Portaria</b>
        </legend>
        <table>
          <tr>
            <td>
              <b>Ano</b>
            </td>
            <td>
              <?
				db_input("anousu",15,$Ih31_anousu,true,"text",1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>
              <?
				db_ancora("Portaria de:","js_pesquisaPortariaIncial();",1,"");
              ?>
              </b>
            </td>
            <td>
              <?
				db_input("porti",15,$Ih31_numero,true,"text",1,"");
              ?>
            </td>
            <td>
              <b>
              <?
				db_ancora("Até:","js_pesquisaPortariaFinal();",1,"");
              ?> 
              </b>           
            </td>
            <td>
              <?
				db_input("portf",15,$Ih31_numero,true,"text",1,"");
              ?>
            </td>                        
          </tr>          
        </table>
      </fieldset>
    </td>
  </tr>
  <tr align="center">
    <td>
      <input type="button" name="imprimir" id="imprimir" value="Imprimir" onClick="js_valida()">
    </td>
  </tr>
</table>
</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>


 function js_valida(){
 
 	var doc = document.form1;
 	
 	if ( doc.porti.value == "" || doc.portf.value == ""  ) {
	  alert("Você deve informar o ano ou as portarias iniciais e finais!");
	  return false; 	
 	} 
    js_envia();
 
 }

 function js_envia(){
   
  var sAcao   = "consultaPortarias";
  var sQuery  = "sAcao="+sAcao;
      sQuery += "&iPortariaInicial="+document.form1.porti.value;
      sQuery += "&iPortariaFinal="+document.form1.portf.value;
      sQuery += "&iAnoUsu="+document.form1.anousu.value;
  		
  var url     = "rec1_portariasRPC.php";
  var oAjax   = new Ajax.Request( url, {
                                         method: 'post', 
                                         parameters: sQuery,
                                         onComplete: js_retornoEmite
                                       }
                                );
 }
 
 function js_retornoEmite(oAjax){

   var aRetorno = eval("("+oAjax.responseText+")");
	
   if (aRetorno.erro == true) {
	 alert(aRetorno.msg.urlDecode());
	 return false;
   } else {
     js_imprimeRelatorio(aRetorno.iModIndividual,js_downloadArquivo,aRetorno.aParametros.toSource());
   }

 }

 function js_pesquisaPortariaIncial(){
    js_OpenJanelaIframe('','db_iframe_portariai','func_portaria.php?funcao_js=parent.js_mostraportariai|h31_numero','Pesquisa',true);
 }

 function js_mostraportariai(chave){
  document.form1.porti.value = chave;
  db_iframe_portariai.hide();
 }

 function js_pesquisaPortariaFinal(){
    js_OpenJanelaIframe('','db_iframe_portariaf','func_portaria.php?funcao_js=parent.js_mostraportariaf|h31_numero','Pesquisa',true);
 }

 function js_mostraportariaf(chave){
  document.form1.portf.value = chave;
  db_iframe_portariaf.hide();
 }



</script>
