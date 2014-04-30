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
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once ("classes/db_emparquivopit_classe.php");
$clemparquivopit = new cl_emparquivopit();
$clemparquivopit->rotulo->label();
$clrotulo = new rotulocampo;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor="#CCCCCC" style='margin:1em;' onload="js_main()">
  <table width="790" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="360" height="25">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form>
    <table>
       <tr>
         <td>
           <fieldset>
             <legend>
               <b>Reemitir Arquivo</b>
             </legend>
             <table>
              <tr>
						      <td nowrap title="<?=@$Te14_sequencial?>">
						       <?
						        db_ancora("<b>Arquivo:</b>","js_pesquisae14_sequencial(true);",1);
						       ?>
						      </td>
						      <td colspan="3"> 
										<?
										  db_input('e14_sequencial',10,$Ie14_sequencial,true,'text',1," onchange='js_pesquisae14_sequencial(false);'")
										?>
									  <?
									   db_input('e14_nomearquivo',40,$Ie14_nomearquivo,true,'text',3,'')
									  ?>
  					     </td>
	       		   </tr>
               <tr>
                 <td>
                   <b>Data Inicial:</b>
                 </td> 
                 <td>
                   <?
                    $dtDataInicio = date("Y-m-d", db_getsession("DB_datausu"));
                    $aPartesDataInicio = explode("-", $dtDataInicio); 
                    db_inputdata('datainicial', '01', $aPartesDataInicio[1], $aPartesDataInicio[0], true, "text", 1);
                   ?>
                 </td>
                 <td>
                   <b>Data Final:</b>
                 </td> 
                 <td align="left">
                   <?
                    $dtDataFinal      = date("Y-m-d", db_getsession("DB_datausu"));
                    $aPartesDataFinal = explode("-", $dtDataInicio);
                    $iUltimoDia       = cal_days_in_month(CAL_GREGORIAN, $aPartesDataFinal[1], $aPartesDataFinal[0]); 
                    db_inputdata('datafinal', $iUltimoDia, $aPartesDataFinal[1], $aPartesDataFinal[0], true, "text", 1);
                   ?>
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr>
       <tr>
         <td style="text-align: center">
           <input type='button' id='pesquisar'       name='pesquisar'       value='Pesquisar'
                  onclick='js_getArquivosNoPeriodo()'>
           
         </td>
       </tr>
    </table>
    <table style="width: 70%">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Arquivos Gerados</b>
            </legend>
            <div id='containergridArquivos' style="width: 100%">
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
urlRPC            = 'mat4_arquivosPIT.RPC.php';
var dtDataInicial = '';
var dtDataFinal   = '';

function js_main() { 

  gridArquivos = new DBGrid("gridArquivos");
  gridArquivos.nameInstance = "gridArquivos";
  gridArquivos.setCellAlign(new Array("right","center", "left", "center"));
  gridArquivos.setHeader(new Array("Código", "Data", "Arquivo","Reemitir"));
  gridArquivos.show($('containergridArquivos'));
  
}


function js_getArquivosNoPeriodo() {

  var dtDataInicial  = $F('datainicial');
  var dtDataFinal    = $F('datafinal');
  var e14_sequencial = $F('e14_sequencial');
  
  
  /*
  if (dtDataFinal == "") {
  
    alert('informe a data final');
    $('dtafinal').focus();
    
  }
  
  if (dtDataInicial == "") {
  
    alert('informe a data inicial');
    $('dtainicial').focus();
    
  }
  */
  
  var oParamPit = new Object();
  oParamPit.exec          = "getArqReemitir";
  oParamPit.datainicial   = dtDataInicial;  
  oParamPit.datafinal     = dtDataFinal;  
  oParamPit.tipodocumento = 50;
  oParamPit.e14_sequencial = e14_sequencial == '' ? 0 : e14_sequencial;
  js_divCarregando("Aguarde, pesquisando arquivos para o(s) filtro(s) indicado.","msgBox");  
  var oAjax    = new Ajax.Request(
                          urlRPC, 
                          {
                           method    : 'post', 
                           parameters: 'json='+Object.toJSON(oParamPit), 
                           onComplete: js_retornoGetArquivos
                          }
                        ); 
}

function js_retornoGetArquivos(oResponse) {
  
  js_removeObj('msgBox');
  gridArquivos.clearAll(true);
  var oRetorno = eval("("+oResponse.responseText+")");
  lDisabled    = false;
  if (oRetorno.status == 1) {
    
    if (oRetorno.itens.length  > 0) {
      
      for (var i = 0; i < oRetorno.itens.length; i++) {
        
          
        with(oRetorno.itens[i]) {
          
          aLinha    = new Array();
          aLinha[0] = e14_sequencial; 
          aLinha[1] = js_formatar(e14_dtarquivo,'d');  
          aLinha[2] = e14_nomearquivo.urlDecode();
          var sButton = "<input type='button' onclick='js_reemitirArquivo("+e14_sequencial+")' id='reemite'"+i+" value='Reemitir'>" 
          aLinha[3] = sButton;  
          gridArquivos.addRow(aLinha);
          gridArquivos.aRows[i].sStyle += ";padding:1px";
          
                    
        } 
      }
      
      gridArquivos.renderRows();
      
    } 
  }  
}

function js_reemitirArquivo(e14_sequencial) {

  
  var sUrl  = 'mat4_reprocessararquivopit002.php?';
  sUrl     += 'e14_sequencial='+e14_sequencial;

  js_OpenJanelaIframe('', 'db_iframe_pit',sUrl,"Reemitir arquivos Pit", true);
  
} 
 
function js_validaSomenteNumeros(sValor) {
  var sReg    = /[^0-9]/;
  var lRetorno = sReg.test(sValor);
  if (lRetorno) {
     lRetorno = false;
  } else {
    lRetorno = true;
  }
  return lRetorno;
}

function js_pesquisae14_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emparquivopit','func_emparquivopit.php?funcao_js=parent.js_mostrae14_sequencial1|e14_sequencial|e14_nomearquivo','Pesquisa',true);
  }else{
     if($('e14_sequencial').value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emparquivopit','func_emparquivopit.php?pesquisa_chave='+$('e14_sequencial').value+'&funcao_js=parent.js_mostrae14_sequencial','Pesquisa',false);
     }else{
       $('e14_nomearquivo').value = ''; 
     }
  }
}
function js_mostrae14_sequencial(chave,erro){
  $('e14_nomearquivo').value = chave; 
  if(erro==true){ 
    $('e14_sequencial').focus(); 
    $('e14_sequencial').value = ''; 
  }else{
    js_getArquivosNoPeriodo()
  }
}
function js_mostrae14_sequencial1(chave1,chave2){
  $('e14_sequencial').value  = chave1;
  $('e14_nomearquivo').value = chave2;
  db_iframe_emparquivopit.hide();
  js_getArquivosNoPeriodo();
}

</script>