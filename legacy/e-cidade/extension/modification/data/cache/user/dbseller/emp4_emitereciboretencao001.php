<?php
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("e82_codord");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()" >
    <table  border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
     </tr>
  </table>
  <center>
    <form name='form1'>
    <table>
      <tr>
        <td>
          <fieldset>
            <legend><b>Reemisao de recibo</legend>
            <table>
              <tr>
                <td nowrap title="<?=@$Te82_codord?>">
                 <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
                </td>
                <td nowrap> 
                 <? db_input('e50_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tz01_numcgm?>">
                 <? db_ancora(@$Lz01_numcgm,"js_pesquisaz01_numcgm(true);",$db_opcao);  ?>
                </td>
                <td nowrap> 
                 <? db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'")  ?>
                </td>
                <td nowrap> 
                 <? db_input('z01_nome',40,$Iz01_nome,true,'text', 3," onchange='js_pesquisaz01_numcgm(false);'")  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <input type='button' value='Pesquisar' id='pesquisar' onclick='js_pesquisar()'>
        </td>
      </tr>
    </table>
    <table style='width: 60%'>
      <tr>
        <td>
          <fieldset>
             <legend><b>Recibos Autenticados</b></legend>
             <div id='oGridRecibos' >
             
             </div>
          </fieldset>
        </td>
      </tr>
    </table>
    </form>
  </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

sUrlRPC = "emp4_retencaonotaRPC.php";
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }
}
function js_mostrapagordem1(chave1){
  document.form1.e50_codord.value = chave1;
  db_iframe_pagordem.hide();
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm1(iNumCgm, sNome) {

  $('z01_numcgm').value = iNumCgm;
  $('z01_nome').value   = sNome;
  func_nome.hide();
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}

function js_init() {

  oGridRecibos              = new DBGrid("oGridRecibos");
  oGridRecibos.nameInstance = "oGridRecibos";
  oGridRecibos.setCellAlign(new Array("right", "center", "right","right"));
  oGridRecibos.setHeader(new Array('Código Arrec.', 
                                   'Data Emissão',
                                   'OP',
                                   'Empenho', 
                                   'Valor',
                                   'Tipo',
                                   'Receita',
                                   'tipodeb',
                                   "CGM",
                                   "Credor"));
  oGridRecibos.aHeaders[6].lDisplayed = true;
  oGridRecibos.aHeaders[7].lDisplayed = false;
  oGridRecibos.show($('oGridRecibos'));
  
}

function js_pesquisar() {

  if ($F('e50_codord') == ""  && $F('z01_numcgm') == "") {
  
    alert('Preencha o Código da Ordem ou o CGM do Credor');
    return false;
    
  }
  
   var oRequisicao       = new Object();
   oRequisicao.exec      = "getRecibosRetencao";
   oRequisicao.iCodOrdem = $F('e50_codord'); 
   oRequisicao.iNumCgm   = $F('z01_numcgm');
   js_divCarregando("Aguarde, pesquisando Recibos.","msgBox");
   js_liberaBotoes(false); 
    
   var sJson = js_objectToJson(oRequisicao);
   var oAjax = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+sJson, 
                          onComplete: js_retornoPesquisar
                         }
                        );
  
}

function js_retornoPesquisar(oAjax) {

  js_removeObj("msgBox");
  js_liberaBotoes(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridRecibos.clearAll(true);
  for (var i = 0; i < oRetorno.aRecibos.length;i++) {
   
     with(oRetorno.aRecibos[i]) {
              
       var aLinha = new Array();
       aLinha[0]  = codarrecad;
       aLinha[1]  = js_formatar(k12_data,'d');
       aLinha[2]  = e20_pagordem;
       aLinha[3]  = empenho.urlDecode();
       aLinha[4]  = js_formatar(k12_valor,'f');
       aLinha[5]  = tiporecibo;
       aLinha[6]  = e21_receita+" - "+k02_descr.urlDecode();
       aLinha[7]  = k00_tipo;
       aLinha[8]  = numcgm;
       aLinha[9]  = nome.urlDecode();
       oGridRecibos.addRow(aLinha);
       oGridRecibos.aRows[i].sEvents = "ondblclick=emiteRecibo(oGridRecibos.aRows["+i+"])";
     }
  }
  oGridRecibos.renderRows();
}

function js_objectToJson(oObject) { return JSON.stringify(oObject); 
  
   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;
   
}

function emiteRecibo(oRowRecibo) {

   var iNumpre     = oRowRecibo.aCells[0].getValue();
   var iTipoDebito = oRowRecibo.aCells[7].getValue();
   var iNumCgm     = oRowRecibo.aCells[8].getValue();
   var iTipoRecibo = oRowRecibo.aCells[5].getValue();
   var  sUrl       = '';
   if (iTipoRecibo == 1) {
   
     sUrl   = "emp4_emitereciboretencao002.php?numpre="+iNumpre+"&tipo="+iTipoDebito+"&ver_inscr=";
     sUrl  += "&numcgm="+iNumCgm+"&emrec=t&CHECK10=&tipo_debito="+iTipoDebito; 
     sUrl  += "&k03_tipo="+iTipoDebito+"&k03_parcelamento=f&k03_perparc=f&ver_numcgm="+iNumCgm
     sUrl  += "&totregistros=1&reemite_recibo=1&k03_numpre="+iNumpre+"&k00_histtxt="; 
     if (confirm('Reeemitir Recibo?')) {
       window.open(sUrl,'','location=0');
     }
   } else {
     sUrl   = "cai4_recibo003.php?iNumpre="+iNumpre+"&tipo="+iTipoDebito+"&ver_inscr=";
     sUrl  += "&numcgm="+iNumCgm+"&emrec=t&CHECK10=&tipo_debito="+iTipoDebito+"&lReemissao=true"; 
     sUrl  += "&k03_tipo="+iTipoDebito+"&k03_parcelamento=f&k03_perparc=f&ver_numcgm="+iNumCgm;
     if (confirm('Reeemitir Recibo?')) {
       
       window.open(sUrl,'','location=0');
     }
   }
}

function js_liberaBotoes(lLiberar) {

  if (lLiberar) {
    //$('pesquisar').disabled = false;
  } else {
    //$('pesquisar').disabled = true;
  }
}

</script>