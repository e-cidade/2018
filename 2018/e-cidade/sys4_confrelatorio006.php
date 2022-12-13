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
include("classes/db_db_gruporelatorio_classe.php");
include("classes/db_db_tiporelatorio_classe.php");

include("dbforms/db_funcoes.php");

$cldb_gruporelatorio = new cl_db_gruporelatorio();
$cldb_tiporelatorio  = new cl_db_tiporelatorio();

$cldb_gruporelatorio->rotulo->label();
$cldb_tiporelatorio->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; parent.lFinalizar = true;" bgcolor="#cccccc">
<form name="form1">
<center>
<table style="padding-top:20px;">
  <tr> 
    <td>
	  <fieldset>
	    <legend align="center">
	      <b>Finalizar Relatório</b>
	    </legend>
	    <table>
	      <tr>
	        <td>
	          <b>
			        <?
	          	  db_ancora("Grupo :","js_pesquisaGrupo(true)",1,"");
	            ?>
	          </b>
	        </td>
	        <td>
	          <?
							db_input("db13_sequencial",10,$Idb13_sequencial,true,"text",1,"onChange='js_pesquisaGrupo(false);'");
							db_input("db13_descricao" ,40,"",true,"text",3,"");
	          ?>
	        </td>
	      </tr>
	      <tr>
	        <td>
	          <b>
			      <?
	          	db_ancora("Tipo :","js_pesquisaTipo(true)",1,"");
	          ?>
	          </b>
	        </td>
	        <td>
	          <?
							db_input("db14_sequencial",10,$Idb14_sequencial,true,"text",1,"onChange='js_pesquisaTipo(false);'");
							db_input("db14_descricao" ,40,"",true,"text",3,"");
	          ?>
	        </td>
	      </tr>	      
	      
	    </table>
	  </fieldset>
	</td>
  </tr>
  <tr align="center">
    <td>
      <input type="button" name="visualizar" id="visualizar" value="Visualizar"  onClick="js_verificaAbas('visualizar');"/>
  	  <input type="button" name="salvar" 	   id="salvar"	   value="Salvar"		   onClick="js_verificaAbas('salvar');"	  />  		
  	  <input type="button" name="alterar"  	 id="alterar"	   value="Alterar"	   onClick="js_verificaAbas('alterar'); " style="display:none"/> 
  	  <input type="button" name="sair"	 	   id="sair"		   value="Sair"		     onClick="js_sair();"			  /> 	  
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<script>

var temporizador = null;
var lTestaOrdem  = false;

function js_verificaAbas(sAcao) {

  parent.iframe_ordem.js_enviaOrdem();

  if ( lTestaOrdem ) {    
    clearTimeout(temporizador);
    js_valida(sAcao);
  }else{
    temporizador  = setTimeout('js_validaAbas(\"'+sAcao+'\")',500);
  }
  
} 

function js_validaAbas(sAcao) {

  if ( lTestaOrdem ) {    
    clearTimeout(temporizador);
    js_valida(sAcao);
  }else{
    temporizador  = setTimeout('js_validaAbas(\"'+sAcao+'\")',500);
  }
  
}


function js_consultaTipoGrupo(objTipoGrupo){
	
  $('alterar').style.display 	= "";	
  $('salvar').style.display  	= "none";	
	
  document.form1.db13_sequencial.value = objTipoGrupo.gruporel;
  document.form1.db14_sequencial.value = objTipoGrupo.tiporel;
  
  js_pesquisaTipo(false);
  js_pesquisaGrupo(false);

}


function js_valida(sAcao){
  if (sAcao == 'visualizar'){ 
  	  parent.iframe_layout.js_incluirPropriedades();
  	  js_visualizar();
  } else {
    if ( document.form1.db13_sequencial.value == "") {
      alert("Favor escolha algum grupo.");
      return false;
    } else if (document.form1.db14_sequencial.value == "") {
      alert("Favor escolha algum tipo.");
      return false;
    } else {
  	  if (sAcao == 'alterar') {
  	    js_alterar();
  	  } else if (sAcao == 'salvar') {
  	    if ( parent.iframe_layout.document.form1.nomeRel.value == "" ){ 
  	      alert("Favor cadastre um nome para o relatório!");
  	      return false;
  	    } else {
   	      js_salvar();
   	    }
  	  }
    }
  }
}


function js_pesquisaGrupo(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_grupo','func_db_gruporelatorio.php?funcao_js=parent.js_mostragrupo1|db13_sequencial|db13_descricao','Pesquisa',true);
  } else {
    if (document.form1.db13_sequencial.value != '') {
      js_OpenJanelaIframe('','db_iframe_grupo','func_db_gruporelatorio.php?pesquisa_chave='+document.form1.db13_sequencial.value+'&funcao_js=parent.js_mostragrupo','Pesquisa',false);
    } else {
      document.form1.db13_descricao.value = '';
    }
  }
}

function js_mostragrupo(chave,erro)
{
  document.form1.db13_descricao.value = chave;
  if (erro==true) {
    document.form1.db13_sequencial.focus();
    document.form1.db13_sequencial.value = '';
  }
}

function js_mostragrupo1(chave1,chave2){
  document.form1.db13_sequencial.value = chave1;
  document.form1.db13_descricao.value  = chave2;
  db_iframe_grupo.hide();
}

function js_pesquisaTipo(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_tipo','func_db_tiporelatorio.php?funcao_js=parent.js_mostratipo1|db14_sequencial|db14_descricao','Pesquisa',true);
  } else {
    if (document.form1.db14_sequencial.value != '') {
      js_OpenJanelaIframe('','db_iframe_tipo','func_db_tiporelatorio.php?pesquisa_chave='+document.form1.db14_sequencial.value+'&funcao_js=parent.js_mostratipo','Pesquisa',false);
    } else {
      document.form1.db14_descricao.value = '';
    }
  }
}

function js_mostratipo(chave,erro)
{
  document.form1.db14_descricao.value = chave;
  if (erro==true) {
    document.form1.db14_sequencial.focus();
    document.form1.db14_sequencial.value = '';
  }
}

function js_mostratipo1(chave1,chave2){
  document.form1.db14_sequencial.value = chave1;
  document.form1.db14_descricao.value  = chave2;
  db_iframe_tipo.hide();
}



function js_visualizar(){

    js_OpenJanelaIframe('',
                        'db_iframe_filtros',
                        'sys4_mostravariaveisdinamica.php',
                        'Filtros',true);

}

function js_salvar(){

	js_divCarregando('Aguarde, Salvando Relatório...','msgBoxRelatorio');
 	var ConsultaTipo  = 'salvarRelatorio';
 	var url           = 'sys4_consultaviewRPC.php';
 	var sQuery        = 'tipo='+ConsultaTipo;
			sQuery 	   	 += '&grupoRelatorio='+document.form1.db13_sequencial.value;
			sQuery 		   += '&tipoRelatorio='+document.form1.db14_sequencial.value;		
	var oAjax         = new Ajax.Request( url, {
                                               method: 'post', 
    	                                         parameters: sQuery, 
											                         onComplete: js_retornoAlteraInclui
                                             }
                                       );
}

function js_alterar(){


 	js_divCarregando('Aguarde, Salvando Relatório...','msgBoxRelatorio');
 	
 	var ConsultaTipo  = 'alterarRelatorio';
 	var url           = 'sys4_consultaviewRPC.php';
 	var sQuery        = 'tipo='+ConsultaTipo;
			sQuery 		   += '&grupoRelatorio='+document.form1.db13_sequencial.value;
			sQuery 	   	 += '&tipoRelatorio='+document.form1.db14_sequencial.value;		
 	var oAjax         = new Ajax.Request( url, {
                                               method: 'post', 
    	                                         parameters: sQuery, 
						                      					   onComplete: js_retornoAlteraInclui
                                             }
                                      );
}



function js_retornoAlteraInclui(oAjax){
 
 js_removeObj("msgBoxRelatorio");
 
 var aRetorno = eval("("+oAjax.responseText+")");

 alert(aRetorno.msg.urlDecode());
 
 if (aRetorno.erro == true){
 	 return false;
 } else {
   parent.document.location.href = "sys4_geradorrelatorio001.php";
 }
  	
}


function js_sair(){
   
  if(confirm("Deseja realmente sair?")){
    parent.js_bloqueiaMenus(false);
    js_retiraObjetoSessao(js_voltaTelaInicial);
  }

}


function js_retiraObjetoSessao(sCallBackFunction){

  js_divCarregando('Aguarde, Processando...','msgBoxLimpaSessao');
  var url   = "sys4_consultaviewRPC.php";
  var sTipo = "retiraObjetoSessao";
  var oAjax = new Ajax.Request( url,{
                                       method: 'post', 
                                       parameters:"tipo="+sTipo,
                                       onComplete:sCallBackFunction
                                    }
                               );
}

function js_voltaTelaInicial(){
  parent.document.location.href = "sys4_geradorrelatorio001.php";	
}


</script>
