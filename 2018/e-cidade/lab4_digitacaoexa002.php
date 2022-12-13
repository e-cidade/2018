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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
include_once("classes/db_lab_atributo_componente_classe.php");
include_once("classes/db_lab_requiitem_classe.php");
include_once("classes/db_lab_resultado_classe.php");
require_once("libs/db_app.utils.php");
$oAtributos = new cl_lab_atributo_componente;
$oRequiitem = new cl_lab_requiitem;
$oResultado = new cl_lab_resultado;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");


?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<?
   $db_opcao2=1;
   $la52_i_codigo="";
   if(isset($iRequiitem)){
   	   $sSql=$oRequiitem->sql_query_nova($iRequiitem,"la08_i_codigo,la42_i_atributo");
   	   $rResult=$oRequiitem->sql_record($sSql);
   	   if($oRequiitem->numrows>0){
   	   	  db_fieldsmemory($rResult,0);
   	   }
   }
   echo"<form name=\"form2\" method=\"post\" action=\"\" > <center>";
   if((isset($la08_i_codigo))&&(isset($la42_i_atributo))){
   	  if(($la08_i_codigo!="")&&($la42_i_atributo!="")){
   	  	  $sSql=$oResultado->sql_query("","la52_i_codigo,la52_t_motivo",""," la52_i_requiitem=$iRequiitem ");
   	  	  $rResult=$oResultado->sql_record($sSql);
   	  	  if($oResultado->numrows>0){
   	  	  	 db_fieldsmemory($rResult,0);
   	  	  	 $db_opcao2=2;
   	  	  }
          $oAtributos->Atributos($la08_i_codigo,$la42_i_atributo,$iRequiitem,$db_opcao2,1,0);
   	  }
   }?>
<input type="hidden" name="repositorios" id="repositorios" value="<?=$oAtributos->getInputs()?>" size="70">
 <?$iIndex=count($oAtributos->aRepositorios)+1;
   if($db_opcao2==2){
      echo"<table>
             <tr>
                <td>
                   <stronger><b>Motivo</b></stronger>";
      echo"      </td>
                 <td>";
                   db_textarea('la52_t_motivo',2,50,"",true,'text',1,"");
      echo"      </td>
              </tr>
           </table>";
      echo" <script> document.form2.la52_t_motivo.tabIndex = $iIndex; </script> ";
      $iIndex++;  
   }
echo"</center>";
?>

<center>
<?if($db_opcao2==1){?>
  <input type="button" name="Salvar" id="Salvar" value="Salvar" tabindex="<?=$iIndex?>" onclick="js_incluir()" >
<?}else{?>  
  <input type="button" name="alterar" id="alterar" value="Alterar" tabindex="<?=$iIndex?>" onclick="js_alterar()" >
<?}?>
</center>
</form>
<script>
   F=document.form2
   sRPC = 'lab4_agendar.RPC.php';
   
   function js_valida(){
      aVet=F.repositorios.value.split(',');
      for(x=0;x<aVet.length;x++){
         aVet2=aVet[x].split('#');
         if(document.getElementById(aVet2[0]).value==''){
             alert('Digite todos os atributos!');
             return false
         }   
      }
      return true
   }

   function js_controla_tecla_enter(obj,evt){
		var evt = (evt) ? evt : (window.event) ? window.event : "";
		
		//13=enter, 40=seta cima, 38=seta baixo, 37=set esquerda, 39=seta direita
		if(evt.keyCode==13){
			if( evt.keyCode==13 || evt.keyCode==40 || evt.keyCode==39 ){
				var iTabindex = obj.tabIndex + 1;
			}else {
				var iTabindex = obj.tabIndex - 1;			
			}
				
			//Varre todos os campos que foram setados com tabindex
			var aTabindex = new Array();
			for(var iCount = 0; iCount <= document.form2.elements.length; iCount++){
				//verifica se tem tabindex
				if( document.form2.elements[iCount] != undefined && document.form2.elements[iCount].tabIndex > 0 ){
					aTabindex[ document.form2.elements[iCount].tabIndex ] = iCount;
				}
			}
			//varre todos os tabindex setado
			for(var iCount = 0; iCount <= aTabindex.length; iCount++){
				//verificar se o próximo tabindex é valido
				if( aTabindex[ iTabindex ] != undefined){
					document.form2.elements[ aTabindex[ iTabindex ] ].focus();
					break;
				}else{
					//se não for valido incrementa para o próximo tabindexs
					if( evt.keyCode==13 || evt.keyCode==40 || evt.keyCode==39 ){
						iTabindex++;
					}else {
						iTabindex--;
					}
				}
			}
			
			return false;
		}
	}
   
   function js_incluir(){
       if(js_valida()){
    	   aVet=F.repositorios.value.split(',');
           sValores='';
           sSep='';
           stipos = '';
           satributos = '';
 	       for(x=0;x<aVet.length;x++){

 	          aVet2=aVet[x].split('#');
 	          stipos+=sSep+aVet2[1];
 	          aVet3=aVet2[0].split('A');
 	          satributos+=sSep+aVet3[1];
 	          sValores += sSep+document.getElementById(aVet2[0]).value;
 	          sSep = '|';
 	       }
  	       var oParam      = new Object();
  	       oParam.exec     = 'digitacaoinc';
  	       oParam.iRequiitem  = <?=$iRequiitem?>;
    	   oParam.sAtributos = satributos;
    	   oParam.sValores = sValores;  
    	   oParam.sTipos = stipos;
    	   oParam.sMotivo = '';
  	       js_ajax( oParam, 'js_retornoIncluir' );
       }
   }
   function js_retornoIncluir(objAjax){
	   oAjax=eval("("+objAjax.responseText+")");
		if(oAjax.status==1){
			alert('Inclusão efetuada com sucesso!');
			parent.location.href="lab4_digitacaoexa001.php";
		}else{
			message_ajax(objAjax.message);
		}
   }
   function js_alterar(){
       if(js_valida()){
    	   aVet=F.repositorios.value.split(',');
           sValores='';
           sSep='';
           stipos = '';
           satributos = '';
 	       for(x=0;x<aVet.length;x++){

 	          aVet2=aVet[x].split('#');
 	          stipos+=sSep+aVet2[1];
 	          aVet3=aVet2[0].split('A');
 	          satributos+=sSep+aVet3[1];
 	          sValores += sSep+document.getElementById(aVet2[0]).value;
 	          sSep = '|';
 	       }
  	       var oParam      = new Object();
  	       oParam.exec     = 'digitacaoalt';
  	       oParam.iRequiitem  = <?=$iRequiitem?>;
    	   oParam.la52_i_codigo  = <?=($la52_i_codigo=="")?"''":$la52_i_codigo?>;
    	   oParam.sAtributos = satributos;
    	   oParam.sValores = sValores;  
    	   oParam.sTipos = stipos;
    	   oParam.sMotivo = F.la52_t_motivo.value;
  	       js_ajax( oParam, 'js_retornoAlterar' );
       }
   }
   function js_retornoAlterar(objAjax){
	   oAjax=eval("("+objAjax.responseText+")");
		if(oAjax.status==1){
			alert('Alteração efetuada com sucesso!');
			parent.location.href="lab4_digitacaoexa001.php";
		}else{
			message_ajax(objAjax.message);
		}
   }
   function js_ajax( objParam,jsRetorno ){
		  var objAjax = new Ajax.Request(
		                         sRPC, 
		                         {
		                          method    : 'post', 
		                          parameters: 'json='+Object.toJSON(objParam),
		                          onComplete: function(objAjax){
		                                  var evlJS = jsRetorno+'( objAjax );';
		                                  eval( evlJS );
		                                }
		                         }
		                        );
	}
</script>