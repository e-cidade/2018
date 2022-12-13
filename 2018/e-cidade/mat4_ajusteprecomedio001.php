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
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>
<style>

 .field {
   border : 0px;
   border-top: 2px groove white; 
 }
  
 fieldset.field table tr td:FIRST-CHILD {
   width: 150px;
 	 white-space: nowrap;
 }
   
 .link_botao {
   color: blue;
   cursor: pointer;
   text-decoration: underline;
 }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad=" a=1" bgcolor="#cccccc">
<center>

<div id='ficha' style="position: absolute; float:left;background-color:#ccc; width: 100%; height: 100%; display: none; padding-top: 10px;">
</div>

<form name="form1" method="post" action="">
  <fieldset id='fldForm' style="margin-top:50px; text-align: left;">
    <legend><strong>Ajuste Preço Médio</strong></legend>
    <table id='content' border="0">
      <tr>
        <td>
          <b> 
            <? db_ancora('Material :',"js_pesquisaMaterial(true);",1); ?> 
          </b>
        </td>
        <td>
          <?
             db_input('m60_codmater',6,false,'','text',3," onchange='js_pesquisaMaterial(false);'");
             db_input('m60_descr',45,'text',3,'');
         ?>             
        </td>        
      </tr>
    
      <tr>
        <td>
          <b> Data do Reajuste : </b>
        </td>
        <td>
           <? db_inputdata('m85_data','','','',true,'text',1,""); ?>
        </td>        
      </tr>
      
      <tr>
        <td>
           <b> Preço Médio Atual :</b>
        </td>
        <td>
           <? db_input('precomedioatual',15,'text',3,''); ?>
        </td>        
      </tr>
    
      <tr>
        <td>
           <b> Novo Preço Médio : </b>
        </td>
        <td>
          <?  db_input('m85_precomedio',15,false,'','text',3,"onKeyUp='return js_verValor(this.id);'"); ?>
        </td>        
      </tr>      
  
      
      <tr>
        <td colspan="2">
        
          <fieldset style="width: 500px;"> 
          <legend><strong>Motivo</strong></legend> 
            <table cellpadding="3" border="0">
              <tr>
                <td>
                  <textarea rows="5" cols="70" name="obs_precomedio" id='obs_precomedio'></textarea>
                </td>
              </tr>            
            </table>
          </fieldset>  
          
        </td>
      </tr>
        
    </table>
  </fieldset> 
  <input type="button" id='processar' value='Processar' onclick="js_ajustaPrecoMedio();" style="margin-top: 10px;">
</form>   



</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  var sUrlRPC     = 'mat4_ajusteprecomedio.RPC.php';  
  var oParametros = new Object();  
  
//***********   Função que processara o novo valor ********************

function js_ajustaPrecoMedio() {

  var sDtAjuste        = $F('m85_data'); 
  var nValorPrecoMedio = $F('m85_precomedio');
  var sMotivo          = $F('obs_precomedio');
  var iCodMaterial     = $F('m60_codmater');
  var msgDiv           = "Processando novo valor \n Aguarde ...";

  if (iCodMaterial == null || iCodMaterial == '') {
  
    alert('Selecione um material para alteração !');
    return false;
  }     
  if (sDtAjuste == null || sDtAjuste == '') {
  
    alert('Selecione uma data !');
    return false;
  }
  if (nValorPrecoMedio == null || nValorPrecoMedio == '') {
  
    alert('Digite o novo preço médio !');
    return false;
  }  
  if (sMotivo == null || sMotivo == '') {
  
    alert('Digite o motivo da alteração !');
    return false;
  } 

  
    
  oParametros.exec             = 'Ajusta';  
  oParametros.sDtAjuste        = sDtAjuste;
  oParametros.nValorPrecoMedio = nValorPrecoMedio;
  oParametros.sMotivo          = sMotivo;
  oParametros.iCodMaterial     = iCodMaterial;
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoAjustaPrecoMedio
                                             });   

} 

function js_retornoAjustaPrecoMedio(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      
      alert(oRetorno.message.urlDecode());
      $('m60_codmater').value    = '';
      $('m60_descr').value       = '';
      $('m85_data').value        = '';
      $('precomedioatual').value = '';
      $('m85_precomedio').value  = '';
      $('obs_precomedio').value  = '';
       
    } else {
      
      alert(oRetorno.message.urlDecode());
      return false;
    }
}  
  
//***********   funcao para atualizar o preço medio atual *************
  
function js_getPrecoMedioAtual(iCodMaterial) {

  if (iCodMaterial == '' || iCodMaterial == null) {
    
    $('precomedioatual').value = '';
    return false;
  } 
  oParametros.exec         = 'PrecoAtual';  
  oParametros.iCodMaterial = iCodMaterial;   
  
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoPrecoAtual
                                             });  
}

function js_retornoPrecoAtual(oAjax) {
    
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      
      if ( oRetorno.dados.length == 0 ) {
        return false;
      } 
      $("precomedioatual").value = oRetorno.dados;
    }
}

//******************           LOKUP MATERIAL ********************
function js_pesquisaMaterial(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_material','func_matmater.php?funcao_js=parent.js_mostraMaterial1|m60_codmater|m60_descr','Pesquisa',true);
  } else {
  
     if(document.form1.m60_codmater.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_material','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostraMaterial','Pesquisa',false);
     }else{
       document.form1.m60_codmater.value = ''; 
     }
  }
}
function js_mostraMaterial(chave,erro,chave2){


  document.form1.m60_codmater.value = chave2; 
  document.form1.m60_descr.value = chave;
  if (erro == true) {
   
    document.form1.m60_codmater.focus(); 
    document.form1.m60_codmater.value = ''; 
  }
  js_getPrecoMedioAtual(chave2);
}
function js_mostraMaterial1(chave1,chave2){
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  js_getPrecoMedioAtual(chave1);
  db_iframe_material.hide();
}

//**************************  Funcao para validar a entrada de valor *******
function js_verValor(id) {

  var elem = document.getElementById(id);
  
  if (isNaN(elem.value)) {
      alert('Apenas Numeros ou \".\"!');
      elem.value="";
      return false;
    }
  if (elem.value.indexOf(".") > 0) {
      var strArr = elem.value.split(".");
      if (strArr.length > 2) {
        alert('Mais que um \".\" não permitido!');
        elem.value="";
        return false;
      }
     /* else if (String(strArr[1]).length > 2) {
        alert('Apenas duas casas decimais sao permitidas!') ;
        elem.value="";
        return false;
      }*/
    }
  }

var iWidth = $('content').scrollWidth;
$('fldForm').style.width = iWidth+((10 * iWidth)/100);

</script>