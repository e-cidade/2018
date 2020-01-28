<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_processosapensados_classe.php");
require_once("dbforms/db_funcoes.php");

$db_opcao  = 1;
$clrotulo  = new rotulocampo;
$oPost     = db_utils::postMemory($_POST,0);
$oGet      = db_utils::postMemory($_GET,0);
$clrotulo->label("p58_codproc");
$clrotulo->label("p30_procapensado");
$clrotulo->label("z01_nome");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>
  <div style="margin-top: 25px; width: 400px;">
    <form name="form1" method="post" action="">
    <fieldset>
      <legend style="font-weight: bolder;">Apensar Processo</legend>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td nowrap title="<?=@$Tp30_procapensado?>" >
            <?
              db_ancora('<b>Principal: </b>',"js_pesquisaProcesso(true, false);","");
            ?>
          </td>
          <td nowrap="nowrap"> 
            <?
              db_input('p58_codproc',12,$Ip30_procapensado,true,'text',$db_opcao," onchange='js_pesquisaProcesso(false, false);'");
              db_input('z01_nome_principal',40,$Iz01_nome,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tp30_procapensado?>" align="right">
            <?
              db_ancora('<b>Apensar: </b>',"js_pesquisaProcesso(true, true);","");
            ?>
          </td>
          <td nowrap="nowrap"> 
            <?
              db_input('p30_procapensado',12,$Ip30_procapensado,true,'text',$db_opcao," onchange='js_pesquisaProcesso(false, true);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <br>
    <input type="button" name='apensar' value='Apensar' onclick="js_apensar();" >
  </form>
    <fieldset style="width: 470px; ">
      <legend style="font-weight: bolder;">Processo Apensados</legend>      
      <div id = 'gridContainer' > 
      </div>
    </fieldset>
  </div>
</center>
</body>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>  
<script type="text/javascript">

var lApensado = false; 
var sUrlRpc   = "prot4_apensarprocesso.RPC.php";

/**
 * Função de pesquisa
 */
function js_pesquisaProcesso(mostra, apensado) {
  
  var p58_codproc      = document.form1.p58_codproc.value;
  var p30_procapensado = document.form1.p30_procapensado.value;
    
  if (mostra) {
    
    var sUrl = 'func_protprocesso.php?grupo=1'
    if (apensado) {   

      lApensado = apensado;
      sUrl     += '&apensado='+p58_codproc;
    }
    sUrl += '&funcao_js=parent.js_mostratipoproc1|0|3';
    js_OpenJanelaIframe("top.corpo",'db_iframe_processo',sUrl,'Pesquisa',true);
  } else {

    var sUrl = 'func_protprocesso.php?grupo=1';
    
    if (apensado) {   

      lApensado = apensado;
      sUrl     += '&apensado='+p58_codproc;
      sUrl     += '&pesquisa_chave='+p30_procapensado;
    } else {
      sUrl     += '&pesquisa_chave='+p58_codproc;
    }
    
    sUrl += '&funcao_js=parent.js_mostratipoproc';    
    
    js_OpenJanelaIframe("top.corpo",'db_iframe_processo',sUrl,'Pesquisa',false);
  }
}
function js_mostratipoproc(chave1,chave2, erro) {

  if (lApensado) {
     
    $('p30_procapensado').value = chave1;
    $('z01_nome').value         = chave2;
  } else {

    $('p58_codproc').value        = chave1;
    $('z01_nome_principal').value = chave2; 
    buscaProcessosApensados(chave1);
  }
  if (erro) {

    if (lApensado) {  
       
      $('p30_procapensado').focus(); 
      $('p30_procapensado').value = '';
    } else {

      $('p58_codproc').focus();
      $('p58_codproc').value = "";
    }
  }
}

function js_mostratipoproc1(chave1,chave2) {

  if (lApensado) {
     
    $('p30_procapensado').value = chave1;
    $('z01_nome').value         = chave2;
    
  } else {
    
    $('p58_codproc').value        = chave1;
    $('z01_nome_principal').value = chave2;
    buscaProcessosApensados(chave1);
  }
  db_iframe_processo.hide();
}

/**
 * Busca todos processos apensados ao processo selecionado
 */
function buscaProcessosApensados(processo) {

  var oObject         = new Object();
  oObject.exec        = "buscaProcessosApensado";
  oObject.processo    = processo;

  js_divCarregando('Verificando processos apensados...','msgBox');
  var objAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoBuscaProcessoApensado
                                        }
                                   );
}

function js_retornoBuscaProcessoApensado(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status != 2) {

    js_preencheGrid(oRetorno.dados);
  } 
}

/**
 * Apensa um processo ao processo principal
 */
function js_apensar() {

  var oObject         = new Object();
  oObject.exec        = "apensandoProcessos";
  oObject.principal   = $F('p58_codproc');
  oObject.apesado     = $F('p30_procapensado');

  js_divCarregando('Apensando processos...','msgBox');
  var objAjax   = new Ajax.Request (sUrlRpc,{
                                             method:'post',
                                             parameters:'json='+Object.toJSON(oObject), 
                                             onComplete: js_retornoApensar
                                            }
                                    );
  
}

function js_retornoApensar(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status != 2) {

    js_preencheGrid(oRetorno.dados);
  } 
}

/**
 * Apensa um processo ao processo principal
 */
function js_desvinculaProcessoApensado(iProcesso, iApensado) {

  var oObject         = new Object();
  oObject.exec        = "desvinculaApensado";
  oObject.principal   = iProcesso;
  oObject.apesado     = iApensado;

  js_divCarregando('Desvinculando processo apensado...','msgBox');
  var objAjax   = new Ajax.Request (sUrlRpc,{
                                             method:'post',
                                             parameters:'json='+Object.toJSON(oObject), 
                                             onComplete: js_retornoDesvilculaApensado
                                            }
                                    );
  
}

function js_retornoDesvilculaApensado(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  alert(oRetorno.message.urlDecode());
  js_preencheGrid(oRetorno.dados);
}


/**
 * Preenche os dados na grid
 */
function js_preencheGrid(aDados) {

  dbGrid.clearAll(true);
  for (var i = 0; i < aDados.length; i++) { 

    with (aDados[i]) {

      var sExcluiProcesso   = "js_desvinculaProcessoApensado("+p30_procprincipal+", "+p30_procapensado+")";
      var aRowProcesso      = new Array();
			    aRowProcesso[0]   = p30_procprincipal;
  				aRowProcesso[1]   = p30_procapensado;
  				aRowProcesso[2]   = p58_requer.urlDecode();
  				aRowProcesso[3]   = "<input type='button' value='Excluir' onclick='"+sExcluiProcesso+"'>";

	    dbGrid.addRow(aRowProcesso);
    }
  }
  dbGrid.renderRows();
}

$('z01_nome_principal').value = "";
$('z01_nome').value           = "";

/**
 * Cria a grid no escopo global
 */
dbGrid = new DBGrid('gridContainer');
dbGrid.nameInstance = 'dbGrid';
dbGrid.hasTotalizador = false;
dbGrid.setHeight(150);
dbGrid.allowSelectColumns(false);

var aHeader = new Array();
    aHeader[0] = 'Principal';
    aHeader[1] = 'Apensado';
    aHeader[2] = 'Requerente';
    aHeader[3] = 'Ação';
var aAligns = new Array();
    aAligns[0] = 'center';
    aAligns[1] = 'center';
    aAligns[2] = 'center';
    aAligns[3] = 'center';

dbGrid.setCellAlign(aAligns);
dbGrid.setHeader(aHeader);
dbGrid.show($('gridContainer'));
    

//  p30_procprincipal, p30_procapensado, p58_requer

</script>