<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once('libs/db_utils.php');
require_once('libs/db_app.utils.php');
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label("sd63_i_codigo");
$oRotulo->label("sd63_c_procedimento");
$oRotulo->label("sd63_c_nome");
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title> 
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load(" prototype.js, strings.js, webseller.js, scripts.js,  datagrid.widget.js ");
    db_app::load(" estilos.css,  grid.style.css ");
    ?>
  </head>
  <body class="body-default">
    <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Procedimento</legend>
        <table>
          <tr>
            <td title="<?=@$Tsd63_i_codigo?>">
              <?php
              db_ancora( "<b>Procedimento Triagem:</b>", "js_pesquisasd63_i_codigo(true);", 1 );
              ?>
            </td>
            <td>
              <?php
              db_input( 'sd63_i_codigo',       10, $Isd63_i_codigo,       true, 'hidden', 3 );
              db_input( 'sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text',   3 );
              db_input( 'sd63_c_nome',         80, $Isd63_c_nome,         true, 'text',   3 );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id='salvar' name='salvar' value='Salvar' onclick="js_salvar();">
      <fieldset>
        <legend>Procedimentos Configurados</legend>
        <div id='ctnGrigProcedimentos'></div>
      </fieldset>
      <input type="button" id='excluir' name='excluir' value='Excluir Selecionados' onclick="js_excluir();">
    </div>
  </body>
</html>
<script type="text/javascript">

function js_pesquisasd63_i_codigo(mostra) {

  var sUrl  = 'func_sau_procedimento.php?funcao_js=parent.js_mostrasau_procedimento1';
      sUrl += '|sd63_i_codigo|sd63_c_procedimento|sd63_c_nome';
  js_OpenJanelaIframe( 'top.corpo.iframe_a5', 'db_iframe_sau_procedimento', sUrl, 'Pesquisa Procedimentos', true );
}

function js_mostrasau_procedimento1( chave1, chave2, chave3 ) {

  document.form1.sd63_i_codigo.value       = chave1;
  document.form1.sd63_c_procedimento.value = chave2;
  document.form1.sd63_c_nome.value         = chave3;
  db_iframe_sau_procedimento.hide();
}

var oGridProcedimento          = new DBGrid('gridProcedimento');
oGridProcedimento.nameInstance = 'oGridProcedimento';
oGridProcedimento.setCheckbox(0);

oGridProcedimento.setCellWidth(new Array('0%','20%', '80%'));
oGridProcedimento.setCellAlign(new Array("left","left"));
oGridProcedimento.setHeader(new Array('sequencial','Código', 'Procedimento'));
oGridProcedimento.aHeaders[1].lDisplayed = false;
oGridProcedimento.setHeight(200);
oGridProcedimento.show($('ctnGrigProcedimentos'));

/**
 * Busca os procedimentos configurados
 */
function js_buscarDados() {

  var oObject  = new Object;
  oObject.exec = 'getProcedimentosConfigurados';
  
  js_divCarregando('Buscando procedimentos configurados ...','msgBox');
  var objAjax   = new Ajax.Request ('sau4_triagem.RPC.php',
                                     {method:'post',
                                      parameters:'json='+Object.toJSON(oObject),
                                      asynchronous:false,
                                      onComplete:js_retornoDados
                                     }
                                   );
}

/**
 * Trata o retorno dos dados
 */
function js_retornoDados(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  oGridProcedimento.clearAll(true);
  oRetorno.aProcedimentos.each(function (oProcedimento, iSeq) {

    var aLinha = new Array();
    aLinha[0] = oProcedimento.iCodigo;
    aLinha[1] = oProcedimento.iProcedimento;
    aLinha[2] = oProcedimento.sDescricao.urlDecode();
    oGridProcedimento.addRow(aLinha);
  });

  oGridProcedimento.renderRows();
}

function js_salvar() {

  if($F('sd63_i_codigo') == '') {
    
    alert('Selecione um procedimento.');
    return false;
  }

  var oObject           = new Object;
  oObject.exec          = 'salvarProcedimentos';
  oObject.iProcedimento = $F('sd63_i_codigo');

  js_divCarregando('Salvando procedimento...','msgBox');
  new Ajax.Request ('sau4_triagem.RPC.php',
                     {method:'post',
                      parameters:'json='+Object.toJSON(oObject),
                      asynchronous:false,
                      onComplete: js_retornoSalvar
                     }
                   );
}

function js_retornoSalvar(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  alert(oRetorno.message.urlDecode());

  if(oRetorno.status == 1) {
    js_buscarDados();
  }

  js_limpaCampos();
}

function js_excluir() {

  var aSelecionados = oGridProcedimento.getSelection();
  if (aSelecionados.length == 0) {

    alert('Selecione um "Procedimentos Configurados" para excluir.');
    return false;
  }
  
  var aCodigoSelecionados = new Array(); 
  aSelecionados.each( function (aProcedimento, iSeq) {
    aCodigoSelecionados.push(aProcedimento[0]);
  });

  var oObject            = new Object;
  oObject.exec           = 'excluirProcedimentos';
  oObject.aProcedimentos = aCodigoSelecionados;
  
  js_divCarregando('Excluindo procedimento(s)...','msgBox');
  new Ajax.Request ('sau4_triagem.RPC.php',
                     {method:'post',
                      parameters:'json='+Object.toJSON(oObject),
                      asynchronous:false,
                      onComplete: js_retornoExcluir
                     }
                   );
}

function js_retornoExcluir(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  alert(oRetorno.message.urlDecode());

  if(oRetorno.status == 1) {
    js_buscarDados();
  }
}

js_buscarDados();

function js_limpaCampos() {

  $('sd63_c_procedimento').value = '';
  $('sd63_i_codigo').value       = '';
  $('sd63_c_nome').value         = '';
}
</script>