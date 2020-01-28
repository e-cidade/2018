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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("libs/db_utils.php");
include_once("classes/db_db_config_classe.php");
include_once("classes/db_db_usuarios_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_tipoprocessamento()" bgcolor="#cccccc">
<?

$cldb_config = new cl_db_config; 
$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"db21_codcli")); 
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

$cldb_usuarios = new cl_db_usuarios; 
$rsUsuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession('DB_id_usuario'),"administrador")); 
$oUsuarios  = db_utils::fieldsMemory($rsUsuarios,0);

if (db_getsession("DB_id_usuario") == 1 or ( $oConfig->db21_codcli == 19985 and $oUsuarios->administrador == 1 ) ) {
  
?>

<table align="center" border=0 width="100%">
 <tr><td height="15"></td></tr>
 <tr>
  <td>
  <form name="form1" method="POST">
   <table align="center" border=0 width="600">
     <tr>
      <td height="50">
      </td>
     </tr>
     <tr>
      <td width=192> <strong>Tipo de cancelamento: </strong> </td>
      <td>
        <? 
         if (USE_PCASP) {
         	 $aTipos = array("1"=>"Excluir arquivo de retorno","2"=>"Cancelar classificação");
         } else {
         	 $aTipos = array("1"=>"Excluir arquivo de retorno","2"=>"Cancelar classificação","3"=>"Excluir Autenticação");
         }		
         db_select("tipo", $aTipos,true,1, "onchange=js_tipoprocessamento()");
        ?> 
      </td>
     </tr>
     
     <tr>
      <td colspan=2>
       
       
       <div style='position:fixed; visibility:hidden;' id='codret'>
        <table border=0>
         <tr>
          <td width="190"><strong>Codigo de Retorno (Codret): </strong> </td>
          <td> <? db_input('codret',5,1,true,"text",1) ?> </td>
         </tr> 
        </table> 
       </div>
                
       <div style='position:fixed; visibility:hidden;' id='codcla'>
        <table border=0>
         <tr>
          <td width="190"><strong>Codigo de Classificação (Codcla): </strong> </td> 
          <td><? db_input('codcla',5,1,true,"text",1) ?> </td>
         </tr> 
        </table>          
          
       </div>
       
      </td>
     </tr>  
     <tr>
      <td align="center" colspan=2 style='padding:30px'>
       <input type="button" name="processar" value="Processar" onclick="js_valida()"> 
      </td>
     </tr>
   </table>
   </form>
  </td>
 </tr>    
</table>

<?
 } else {
   db_msgbox("Procedimento não disponível!");
 }
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
 sUrlRPC = "cai4_cancelabaixabancoRPC.php";
 
 function js_tipoprocessamento(){
   var tipo = document.form1.tipo.value;
   if (tipo == 1) {
     document.getElementById('codcla').style.visibility = 'hidden';
     document.form1.codcla.value  = '';
     document.getElementById('codret').style.visibility = 'visible';
   } else {
     document.getElementById('codret').style.visibility = 'hidden';
     document.form1.codret.value = '';
     document.getElementById('codcla').style.visibility = 'visible';
   }
 }
 
 function js_valida() {
 
   if(document.form1.tipo.value == 1) {

     sMsg = "Confirmar a exclusão do arquivo (codret) "+document.form1.codret.value+" e todas as suas classificações?";
     if ( document.form1.codret.value == ""){
      alert("Informe o código de retorno do Arquivo");
      return false;
     }
     
   } else if (document.form1.tipo.value == 2) {

     sMsg = "Confirmar a exclusão da classificação (codcla) "+document.form1.codcla.value+"?";;
     if ( document.form1.codcla.value == ""){
      alert("Informe o código de classificação do Arquivo");
      return false;
     }
          
   } else if (document.form1.tipo.value == 3) {

     sMsg = "Confirma a exclusão da autenticação da classificação (codcla) "+document.form1.codcla.value+"?";;
     if ( document.form1.codcla.value == ""){
      alert("Informe o código de classificação do Arquivo");
      return false;
     }
   }
   
   if (confirm("Este procedimento não poderá ser revertido após processado!\nSe estiver ciente dos impactos da confirmação dessa operação e se os dados informados estão corretos clique em 'OK'!")) {
     if (confirm(sMsg)) {
        var oRequisicao                    = new Object();
            oRequisicao.exec               = "validaProcessamento";
            oRequisicao.iTipoProcessamento = document.form1.tipo.value; 
            oRequisicao.codret             = document.form1.codret.value;
            oRequisicao.codcla             = document.form1.codcla.value;
            js_divCarregando("Verificando dados, aguarde ","msgBox");
            var sJson = js_objectToJson(oRequisicao);
            var oAjax = new Ajax.Request( sUrlRPC, 
                                          { 
                                            method    : 'post', 
                                            parameters: 'json='+sJson, 
                                            onComplete: js_retornoValida
                                          }
                                        );
                        

     } else {
       return false;
     }
   } else {
     return false;
   }
 }
 
 function js_retornoValida(oAjax) {
	 js_removeObj("msgBox");
   var oRetorno = eval("("+oAjax.responseText+")");

   if (oRetorno.boletim_processado != "") {
	   <? if (!USE_PCASP) { ?> 
	        alert("Arquivo Autenticado e com boletim processado!\nPara prosseguir com a operação estorne o processamento do Boletim da Tesouraria.\nData do Boletim: "+js_formatar(oRetorno.boletim_processado,'d'));
	        return false;
	   <?  } else { ?>
	   	    alert("Arquivo Autenticado e com boletim processado!\\nOperação não permitida!");
	   	    return false;
     <?  } ?>  
	   
   } else if(oRetorno.boletim_liberado != "") {
     alert("Arquivo Autenticado e com boletim liberado!\nPara prosseguir com a operação cancele a liberação do Boletim da Tesouraria.\nData da Liberação: "+js_formatar(oRetorno.boletim_liberado,'d'));
	   return false;
   }    

   if (oRetorno.arqautent != ""){
	   <? if (!USE_PCASP) { ?> 
          if (!confirm("Arquivo Autenticado!\nDeseja continuar o processamento da operação e excluir a autenticação?")){
            return false;
          } 
	   <? } else { ?>
	      	alert("Arquivo Autenticado!\nNão é possível realizar a operação!"); 
	      	return false;
	   <? } ?>
   }
   
   if (oRetorno.arqsimples != "") {
     if (!confirm('Arquivo de retorno do Simples Nacional!\nDeseja continuar o processamento da operação e cancelar o processamento do arquivo?')){
       return false;               
     } 
   }
   
   js_processa();
   
 }
 
 function js_processa() {
   var oRequisicao                    = new Object();
       oRequisicao.exec               = "Processar";
       oRequisicao.iTipoProcessamento = document.form1.tipo.value; 
       oRequisicao.codret             = document.form1.codret.value;
       oRequisicao.codcla             = document.form1.codcla.value;
   var sJson = js_objectToJson(oRequisicao);
   js_divCarregando("Processando, aguarde ","msgBox");
   var oAjax = new Ajax.Request( sUrlRPC, 
                                          { 
                                            method    : 'post', 
                                            parameters: 'json='+sJson, 
                                            onComplete: js_retornoProcessamento
                                          }
                                        );
 }
 
 function js_retornoProcessamento(oAjax) {
   js_removeObj("msgBox");
   var oRetorno = eval("("+oAjax.responseText+")");
   alert(oRetorno.message.urlDecode());
 }
 
</script>