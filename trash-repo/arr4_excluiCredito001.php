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
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" onload="js_pesquisaAbatimento()">
<br><br>
<?php
  if (db_getsession("DB_id_usuario") != 1) {
  	?>
  	 <div align="center">
  	 <fieldset style="width: 300px;">
  	   <b>Procedimento indisponível</b>
  	 </fieldset>
  	 </div>
  	<?
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  	exit;
  } 
?>
<div align="center">
<fieldset style="width: 300px;">
<legend> <b>Excluir Crédito</b> </legend>
<form name="form1" method="POST">
 <table align="center" border=0 width="100%">
  <tr>
  <tr>
   <td>
     <?
       db_ancora("Abatimento:","js_pesquisaAbatimento();",1);
     ?> 
   </td>
   <td>
     <?
       db_input("abatimento",10,"1",true,'text',3);
      ?>
   </td>
  </tr>
  <tr>
    <td colspan=2 style="visibility:hidden;" id ="MI">
      <?
       db_ancora('Consultar Origens Atuais do Abatimento',"js_consultaOrigemCredito()",1,'');
      ?>
    </td>
  </tr>  
 </table>
</fieldset>
<br>
 <input type="button" name="btnExcluir" id="btnExcluir" value="Excluir" disabled />
</form>
</div>

<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


  var sUrlRPC = "arr4_manutencaoAbatimento.RPC.php";

	$('btnExcluir').observe("click", function() {
		js_ExcluirCredito();
	});  

  function js_pesquisaAbatimento() {
	    js_OpenJanelaIframe('','db_iframe_abatimento','func_abatimento.php?tipo=3&funcao_js=parent.js_mostraAbatimento1|k125_sequencial|k125_valor','Pesquisa',true);
	}
	
	function js_mostraAbatimento1(chave1,chave2) {
		
	  $("abatimento").value    = chave1;
	  db_iframe_abatimento.hide();

	  $('btnExcluir').disabled  = false;
	  $("MI").style.visibility = 'visible';
	  js_buscaOrigensAbatimento(chave1);
	}

	function js_consultaOrigemCredito() {
		
		var sUrl = 'func_origemabatimento.php?iAbatimento='+$("abatimento").value;
	  js_OpenJanelaIframe('top.corpo','db_iframe_abatimento',sUrl,'Origem Crédito',true);
	  
	}	

	function js_ExcluirCredito() {

		if (confirm("Confirma a exclusão do crédito gerado pelo abatimento "+$("abatimento").value+"?")) {
			if (confirm("Este procedimento não poderá ser revertido!\nTem certeza que deseja confirmar a operação?")) {
		    if (confirm("Confirma a exclusão do crédito gerado pelo abatimento "+$("abatimento").value+" mesmo sabendo que a operação não poderá ser revertida?")) {
  		  	if (!confirm("Confirma exclusão do abatimento "+$("abatimento").value+"?")) {
  	  		  return false;	
  		  	}  	
	  	  } else {
		  	  return false; 
	  	  }  
			} else {
				return false;
			}	
		} else {
			return false; 
		}		  		
		var oParam  				   = new Object();
		    oParam.iAbatimento = $("abatimento").value;
		    oParam.exec 		   = "exluirCredito";

		  js_divCarregando("Aguarde, processando exclusão do abatimento...", "msgBox");

		  var oAjax = new Ajax.Request(sUrlRPC,
		                                {
		                                  method:'post',
		                                  parameters:'json='+Object.toJSON(oParam),
		                                  onComplete: js_retornoExcluirCredito
		                                }
		                              );		
		
	}

	function js_retornoExcluirCredito(oAjax){

		  js_removeObj("msgBox");
		  var oRetorno = eval("("+oAjax.responseText+")")
		  alert(oRetorno.message.urlDecode());

		  if (oRetorno.status == 1) {
		    js_limpaTela();
		  }
  }

	function js_limpaTela() {
		
		$("abatimento").value    = ""; 
		$("MI").style.visibility = 'hidden';
		$('btnExcluir').disabled  = true;
		js_pesquisaAbatimento();
		 
	}
		
</script>