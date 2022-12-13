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
$clacordoacordogarantia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac12_sequencial");
$clrotulo->label("ac11_descricao");
$db_opcao = 1;
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Vincular garantias</b>
        </legend>
        <table border="0">
          <tr style='display: none'>
            <td nowrap title="<?=@$Tac12_sequencial?>">
             <?=@$Lac12_sequencial?>
            </td>
            <td> 
            <?
            db_input('ac12_sequencial',10,$Iac12_sequencial,true,'text',3,"");
            db_input('ac12_acordo',10,$Iac12_acordo,true,'text',$db_opcao," onchange='js_pesquisaac12_acordo(false);'")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tac12_acordogarantia?>">
               <?
               db_ancora(@$Lac12_acordogarantia,"js_pesquisaac12_acordogarantia(true);",$db_opcao);
               ?>
            </td>
            <td> 
            <?
            db_input('ac12_acordogarantia', 10, $Iac12_acordogarantia,true,'text',
                     $db_opcao," onchange='js_pesquisaac12_acordogarantia(false);'");
            db_input('ac11_descricao',40,$Iac11_descricao,true,'text',3,'')
            ?>
            </td>
           </tr>
           <tr>
            <td nowrap title="<?=@$Tac12_texto?>" colspan="3">
              <fieldset>
                <legend>
                  <b><?=str_replace(":","", @$Lac12_texto)?></b>
                </legend>
               <?
               db_textarea('ac12_texto',5, 0, $Iac12_texto,true,'text',$db_opcao,"style='width:100%'");
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
         <legend><b>garantias Vinculadas</b></legend>
          <div id='ctnGridgarantia'>
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
function js_pesquisaac12_acordogarantia(mostra){

  if (mostra) {
  
    js_OpenJanelaIframe('top.corpo.iframe_acordogarantia', 
                        'db_iframe_acordogarantia',
                        'func_acordogarantia.php?funcao_js=parent.js_mostraacordogarantia1|'+
                        'ac11_sequencial|ac11_descricao|ac11_textopadrao', 
                        'Pesquisar garantias', 
                        true
                        );
  } else {
     
     if (document.form1.ac12_acordogarantia.value != '') {
      
        js_OpenJanelaIframe('top.corpo.iframe_acordogarantia',
                            'db_iframe_acordogarantia',
                            'func_acordogarantia.php?pesquisa_chave='+
                            document.form1.ac12_acordogarantia.value+
                            '&funcao_js=parent.js_mostraacordogarantia',
                            'Pesquisar garantias',
                            false
                            );
     }else{
       document.form1.ac11_sequencial.value = ''; 
     }
  }
}

function js_mostraacordogarantia(chave,erro) {

  if (erro) {
   
    document.form1.ac12_acordogarantia.focus(); 
    document.form1.ac12_acordogarantia.value = '';
     
  } else {
    js_getDadosgarantia($F('ac12_acordogarantia'));
  }
}

function js_mostraacordogarantia1(chave1, chave2, chave3) {

  document.form1.ac12_acordogarantia.value = chave1;
  document.form1.ac11_descricao.value        = chave2;
  db_iframe_acordogarantia.hide();
  js_getDadosgarantia(chave1);
}

function js_main() {

  oGridGarantias              = new DBGrid('oGridGarantias');
  oGridGarantias.nameInstance = "oGridgarantias";
  oGridGarantias.setCellWidth(new Array("5%", '85%','5%'));
  oGridGarantias.setHeader(new Array("Código",  "Descrição","Ação"));
  oGridGarantias.show($('ctnGridgarantia'));  
  js_getGarantias();
  $('db_opcao').onclick=js_salvarGarantia;
}

function js_getDadosgarantia(iGarantia) {

   js_divCarregando('Aguarde, Pesquisando informações da garantia.','msgBox');
   var oParam     = new Object();
   oParam.iTipo   = 2;
   oParam.exec    = 'getDadosPenalidadeGarantia';
   oParam.iCodigo = iGarantia; 
   var oAjax      = new Ajax.Request(sUrlRpc,
                                    {method     : 'post',
                                     parameters :'json='+Object.toJSON(oParam),
                                     onComplete : js_retornoGetDadosgarantia 
                                    }
                                   )
}



function js_retornoGetDadosgarantia(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status  == 1) {
    
    $('ac11_descricao').value = oRetorno.descricao.urlDecode();
    $('ac12_texto').value     = oRetorno.texto.urlDecode();
  } 
}

function js_getGarantias(iGarantia) {

  if (iGarantia == null) {
    iGarantia   = '';
  }
  $('ac12_acordogarantia').value = '';
  $('ac11_descricao').value        = '';
  $('ac12_texto').value            = '';
  $('db_opcao').value              = 'Incluir';
  js_divCarregando('Aguarde, Pesquisando garantias','msgBox');
  var oParam          = new Object();
  oParam.exec         = 'getGarantias';
  oParam.iGarantia    = iGarantia; 
  var oAjax           = new Ajax.Request(sUrlRpc,
                                     {method     : 'post',
                                      parameters :'json='+Object.toJSON(oParam),
                                      onComplete : js_retornoGetgarantias 
                                      }
                                    )
}

function js_retornoGetgarantias(oAjax) {


  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    if (!oRetorno.isUpdate) {
    
      oGridGarantias.clearAll(true);
      oRetorno.itens.each(function (oRow, id) {
      
        aLinha     = new Array();
        aLinha[0]  = oRow.codigo;
        aLinha[1]  = oRow.descricao.urlDecode();
        aLinha[2]  = "<input type='button' value='A' onclick='js_getGarantias("+oRow.codigo+")'>";
        aLinha[2] += "<input type='button' value='E' onclick='js_excluirGarantia("+oRow.codigo+")'>";
        
        oGridGarantias.addRow(aLinha);
        var sTexto  = oRow.texto.urlDecode().replace(/\"/g,'\\"');
        
        oGridGarantias.aRows[id].sEvents  = "onmouseover=\'js_setAjuda(\""+sTexto+"\",true)'";
        oGridGarantias.aRows[id].sEvents += "onmouseOut='js_setAjuda(null,false)'";
      });
      oGridGarantias.renderRows();
    } else {
    
      $('db_opcao').value        = 'Alterar';
      $('btnNovo').style.display ='';
      $('ac12_acordogarantia').value = oRetorno.itens[0].codigo;
      $('ac11_descricao').value = oRetorno.itens[0].descricao.urlDecode();
      $('ac12_texto').value = oRetorno.itens[0].texto.urlDecode();
      
    }
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
function js_salvarGarantia() {

   js_divCarregando('Aguarde, salvando garantia.','msgBox');
   var oParam     = new Object();
   oParam.iTipo       = 1;
   oParam.exec        = 'salvarGarantia';
   oParam.iGarantia   = $F('ac12_acordogarantia');
   oParam.sTexto      = encodeURIComponent(tagString($F('ac12_texto'))); 
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
    
    js_getGarantias();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_excluirGarantia(iGarantia) {
  
  if (!confirm('Confirma a Exclusao da garantia?')) {
    return false;
  } 
  js_divCarregando('Aguarde, Excluindo garantia','msgBox');
  var oParam         = new Object();
   oParam.iTipo       = 1;
   oParam.exec        = 'excluirGarantia';
   oParam.iGarantia   = iGarantia;
   var oAjax          = new Ajax.Request(sUrlRpc,
                                    {method     : 'post',
                                     parameters :'json='+Object.toJSON(oParam),
                                     onComplete : js_retornoSalvar 
                                    }
                                   )
}

function js_limpar() {

  $('ac12_acordogarantia').value = '';
  $('ac11_descricao').value        = '';
  $('ac12_texto').value            = '';
  $('db_opcao').value              = 'Incluir';
  $('btnNovo').style.display       = 'none';
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('gridoGridGarantias'); 
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