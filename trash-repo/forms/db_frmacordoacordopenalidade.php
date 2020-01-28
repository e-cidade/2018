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

//MODULO: Acordos
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clacordoacordopenalidade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac13_sequencial");
$db_opcao = 1;
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Vincular Penalidades</b>
        </legend>
        <table border="0">
          <tr style='display: none'>
            <td nowrap title="<?=@$Tac15_sequencial?>">
             <?=@$Lac15_sequencial?>
            </td>
            <td> 
            <?
            db_input('ac15_sequencial',10,$Iac15_sequencial,true,'text',3,"");
            db_input('ac15_acordo',10,$Iac15_acordo,true,'text',$db_opcao," onchange='js_pesquisaac15_acordo(false);'")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tac15_acordopenalidade?>">
               <?
               db_ancora(@$Lac15_acordopenalidade,"js_pesquisaac15_acordopenalidade(true);",$db_opcao);
               ?>
            </td>
            <td> 
            <?
            db_input('ac15_acordopenalidade', 10, $Iac15_acordopenalidade,true,'text',
                     $db_opcao," onchange='js_pesquisaac15_acordopenalidade(false);'");
            db_input('ac13_descricao',40,$Iac13_sequencial,true,'text',3,'')
            ?>
            </td>
           </tr>
           <tr>
            <td nowrap title="<?=@$Tac15_texto?>" colspan="3">
              <fieldset>
                <legend>
                  <b><?=str_replace(":","", @$Lac15_texto)?></b>
                </legend>
               <?
               db_textarea('ac15_texto',5, 0, $Iac15_texto,true,'text',$db_opcao,"style='width:100%'");
               ?>
               </fieldset>
            </td>
          </tr>
          </table>
       </fieldset>
     </td>
   </tr>
   <tr> 
     <td colspan="2" align="center">
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
              type="button" id="db_opcao" value="Incluir">
        <input name="Novo" style="display:none" onclick="js_limpar()"
              type="button" id="btnNovo" value="Novo">
      </td>
   </tr>
   <tr>
     <td>
       <fieldset>
         <legend><b>Penalidades Vinculadas</b></legend>
          <div id='ctnGridPenalidade'>
       </div>
       </fieldset>
     </td>
   </tr>
 </table>
  </center>
</form>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<script>
var sUrlRpc = 'ac4_acordopenalidadesgaratnias.RPC.php';
function js_pesquisaac15_acordopenalidade(mostra){

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo.iframe_acordopenalidade', 
                        'db_iframe_acordopenalidade',
                        'func_acordopenalidade.php?funcao_js=parent.js_mostraacordopenalidade1|'+
                        'ac13_sequencial|ac13_descricao|ac13_textopadrao', 
                        'Pesquisar Penalidades', 
                        true
                        );
  } else {
     
     if (document.form1.ac15_acordopenalidade.value != '') {
      
        js_OpenJanelaIframe('top.corpo.iframe_acordopenalidade',
                            'db_iframe_acordopenalidade',
                            'func_acordopenalidade.php?pesquisa_chave='+
                            document.form1.ac15_acordopenalidade.value+
                            '&funcao_js=parent.js_mostraacordopenalidade',
                            'Pesquisar Penalidades',
                            false
                            );
     }else{
       document.form1.ac13_sequencial.value = ''; 
     }
  }
}

function js_mostraacordopenalidade(chave,erro) {

  if (erro) {
   
    document.form1.ac15_acordopenalidade.focus(); 
    document.form1.ac15_acordopenalidade.value = '';
     
  } else {
    js_getDadosPenalidade($F('ac15_acordopenalidade'));
  }
}

function js_mostraacordopenalidade1(chave1, chave2, chave3) {

  document.form1.ac15_acordopenalidade.value = chave1;
  document.form1.ac13_descricao.value        = chave2;
  db_iframe_acordopenalidade.hide();
  js_getDadosPenalidade(chave1);
}

function js_main() {

  oGridPenalidades              = new DBGrid('oGridPenalidades');
  oGridPenalidades.nameInstance = "oGridPenalidades";
  oGridPenalidades.setCellWidth(new Array("5%", '85%','5%'));
  oGridPenalidades.setHeader(new Array("Código",  "Descrição","Ação"));
  oGridPenalidades.show($('ctnGridPenalidade'));  
  js_getPenalidades();
  $('db_opcao').onclick=js_salvarPenalidade;
}

function js_getDadosPenalidade(iPenalidade) {

   js_divCarregando('Aguarde, Pesquisando informações da penalidade.','msgBox');
   var oParam     = new Object();
   oParam.iTipo   = 1;
   oParam.exec    = 'getDadosPenalidadeGarantia';
   oParam.iCodigo = iPenalidade; 
   var oAjax      = new Ajax.Request(sUrlRpc,
                                    {method     : 'post',
                                     parameters :'json='+Object.toJSON(oParam),
                                     onComplete : js_retornoGetDadosPenalidade 
                                    }
                                   )
}



function js_retornoGetDadosPenalidade(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status  == 1) {
    
    $('ac13_descricao').value = oRetorno.descricao.urlDecode();
    $('ac15_texto').value     = oRetorno.texto.urlDecode();
  } 
}

function js_getPenalidades(iPenalidade) {

  if (iPenalidade == null) {
    iPenalidade   = '';
  } else {
  }
  $('ac15_acordopenalidade').value = '';
  $('ac13_descricao').value        = '';
  $('ac15_texto').value            = '';
  $('db_opcao').value              = 'Incluir';
  js_divCarregando('Aguarde, Pesquisando Penalidades','msgBox');
  var oParam          = new Object();
  oParam.exec         = 'getPenalidades';
  oParam.iPenalidade  = iPenalidade; 
  var oAjax           = new Ajax.Request(sUrlRpc,
                                     {method     : 'post',
                                      parameters :'json='+Object.toJSON(oParam),
                                      onComplete : js_retornoGetPenalidades 
                                      }
                                    )
}

function js_retornoGetPenalidades(oAjax) {


  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    if (!oRetorno.isUpdate) {
    
      oGridPenalidades.clearAll(true);
      oRetorno.itens.each(function (oRow, id) {
      
        aLinha     = new Array();
        aLinha[0]  = oRow.codigo;
        aLinha[1]  = oRow.descricao.urlDecode();
        aLinha[2]  = "<input type='button' value='A' onclick='js_getPenalidades("+oRow.codigo+")'>";
        aLinha[2] += "<input type='button' value='E' onclick='js_excluirPenalidade("+oRow.codigo+")'>";
        
        oGridPenalidades.addRow(aLinha);
        var sTexto  = oRow.texto.urlDecode().replace(/\"/g,'\\"');
        
        oGridPenalidades.aRows[id].sEvents  = "onmouseover=\'js_setAjuda(\""+sTexto+"\",true)'";
        
        oGridPenalidades.aRows[id].sEvents += "onmouseOut='js_setAjuda(null,false)'";
      });
      oGridPenalidades.renderRows();
    } else {
    
      $('db_opcao').value        = 'Alterar';
      $('btnNovo').style.display ='';
      $('ac15_acordopenalidade').value = oRetorno.itens[0].codigo;
      $('ac13_descricao').value = oRetorno.itens[0].descricao.urlDecode();
      $('ac15_texto').value = oRetorno.itens[0].texto.urlDecode();
      
    }
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
function js_salvarPenalidade() {

   js_divCarregando('Aguarde, salvando Penalidade.','msgBox');
   var oParam     = new Object();
   oParam.iTipo       = 1;
   oParam.exec        = 'salvarPenalidade';
   oParam.iPenalidade = $F('ac15_acordopenalidade');
   oParam.sTexto      = encodeURIComponent(tagString($F('ac15_texto'))); 
   var oAjax          = new Ajax.Request(sUrlRpc,
                                    {method     : 'post',
                                     parameters :'json='+Object.toJSON(oParam),
                                     onComplete : js_retornoSalvar 
                                    }
                                   )
}

function js_retornoSalvar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    js_getPenalidades();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_excluirPenalidade(iPenalidade) {
  
  if (!confirm('Confirma a Exclusao da Penalidade?')) {
    return false;
  } 
  js_divCarregando('Aguarde, Excluindo penalidade','msgBox');
  var oParam     = new Object();
   oParam.iTipo       = 1;
   oParam.exec        = 'excluirPenalidade';
   oParam.iPenalidade = iPenalidade;
   var oAjax          = new Ajax.Request(sUrlRpc,
                                    {method     : 'post',
                                     parameters :'json='+Object.toJSON(oParam),
                                     onComplete : js_retornoSalvar 
                                    }
                                   )
}

function js_limpar() {

  $('ac15_acordopenalidade').value = '';
  $('ac13_descricao').value        = '';
  $('ac15_texto').value            = '';
  $('db_opcao').value              = 'Incluir';
  $('btnNovo').style.display       = 'none';
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('gridoGridPenalidades'); 
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
js_main();
</script>