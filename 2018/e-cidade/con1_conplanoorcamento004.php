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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_conparametro_classe.php");

$oEstruturaSistema = new cl_estrutura_sistema();
$iOpcao = 1;

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js"); 
  db_app::load("strings.js, grid.style.css, datagrid.widget.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top:25px;">

<form id='form1' name='form1'>
  <center>
    <fieldset style="width: 600px">
      <legend><b>Reduzidos</b></legend>
      <table width="100%">
        <tr>
          <td><b>Código Conta:</b></td>
          <td>
            <?php 
              db_input("iCodigoConta", 10, null, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Reduzido:</b></td>
          <td>
            <?php 
              db_input("iCodigoReduzido", 10, null, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php 
              db_ancora("<b>Instituição:</b>", "js_pesquisaInstituicao(true)", 1, "", "ancoraInstituicao");
            ?>
             <span id='ancoraDesabilitada' style='display:none'><b>Instituição:</b></span>
          </td>
          <td>
            <?php 
              db_input("iCodigoInstituicao", 10, null, false, "text", 1, "onchange='js_pesquisaInstituicao(false);'");
              db_input("sDescricaoInstituicao", 50, null, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php 
              db_ancora("<b>Recurso:</b>", "js_pesquisaRecurso(true)", 1);
            ?>
          </td>
          <td>
            <?php 
              db_input("iCodigoRecurso", 10, null, false, "text", 1, "onchange='js_pesquisaRecurso(false);'");
              db_input("sDescricaoRecurso", 50, null, true, "text", 3);
            ?>
          </td>
        </tr>
      </table>      
    </fieldset>
    <p><input type="button" name="btnIncluirReduzido" id="btnIncluirReduzido" value="Incluir"></p>
    <fieldset>
      <legend><b>Reduzidos Cadastrados</b></legend>
      <div id="divGridReduzidos">
      </div>
    </fieldset>
  </center>
</form>
</body>
</html>


<script type="text/javascript">

var sCaminhoMensagem = "financeiro.contabilidade.con1_conplanoorcamento.";
var oUrl             = js_urlToObject();
            
var oGridReduzido              = new DBGrid('oGridRecibo');
    oGridReduzido.nameInstance = 'oGridReduzido';
    oGridReduzido.sName        = 'oGridReduzido';
    oGridReduzido.setCellAlign = (new Array("center","center", "center", "left", "left", "center"));
    aHeaders                   = new Array("Código Conta", "Reduzido", "Instituição", "Recurso", "Ação", "Instit", "Recurso");
    oGridReduzido.aWidths      = new Array(10, 10, 30, 30, 5);
    oGridReduzido.setHeader(aHeaders);
    oGridReduzido.aHeaders[5].lDisplayed = false;
    oGridReduzido.aHeaders[6].lDisplayed = false;
    oGridReduzido.show($('divGridReduzidos'));

function js_carregaReduzidos() {

  js_divCarregando("Aguarde, carregando reduzidos...", "msgBox");
  
  var oParam          = new Object();
  oParam.exec         = "getReduzidos";
  oParam.iCodigoConta = document.getElementById('iCodigoConta').value;

  var oAjax = new Ajax.Request("con1_conplanoorcamento.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheGridReduzidos
                                }
                               );
  
}

function js_preencheGridReduzidos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  oGridReduzido.clearAll(true);
  if (oRetorno.aContasReduzidas.length > 0) {
    oRetorno.aContasReduzidas.each(function (oReduz, iLinha) {

      var aLinha = new Array();
      aLinha[0]  = oReduz.c61_codcon; 
      aLinha[1]  = oReduz.c61_reduz;
      aLinha[2]  = oReduz.codigo +" - "+ oReduz.nomeinst;
      aLinha[3]  = oReduz.o15_codigo +" - "+ oReduz.o15_descr;
      aLinha[4]  = '<input type="button" id="btnReduzAlt_'+iLinha+'"';
      aLinha[4] += '       value="A" title="Alterar Registro" onclick="js_alterarReduzido('+iLinha+');">&nbsp;';
      aLinha[4] += '<input type="button" id="btnReduzExc_'+iLinha+'" value="E"';
      aLinha[4] += '       title="Excluir Registro" onclick="js_excluirReduzido('+oReduz.c61_reduz+', '+oReduz.codigo+')">';
      aLinha[5]  = oReduz.codigo; //instituicao
      aLinha[6]  = oReduz.o15_codigo; //recurso

      oGridReduzido.addRow(aLinha);
    });
    oGridReduzido.renderRows();
  }
}

function js_alterarReduzido(iLinha, iReduzido) {

  var oRowGrid = oGridReduzido.aRows[iLinha];
  
  $('iCodigoReduzido').value    = oRowGrid.aCells[1].getValue();
  $('iCodigoInstituicao').value = oRowGrid.aCells[5].getValue();
  $('iCodigoRecurso').value     = oRowGrid.aCells[6].getValue();

  js_pesquisaRecurso(false);
  js_pesquisaInstituicao(false);
  
  $('iCodigoInstituicao').disabled         = true;
  $('iCodigoInstituicao').style.background = "#DEB887";

  $('ancoraInstituicao').style.display     = "none";
  $('ancoraDesabilitada').style.display    = "";
  
  $('btnIncluirReduzido').value = "Alterar";
}

function js_excluirReduzido(iReduzido, iInstituicao) {

  if (!confirm("Confirma a exclusão do reduzido "+iReduzido+", instituição "+iInstituicao+"?")) {
    return false;
  }
  
  var oParam               = new Object();
  oParam.exec              = "excluirReduzido";
  oParam.iCodigoReduzido   = iReduzido;
  oParam.iInstituicao      = iInstituicao;
  oParam.iCodigoPlanoConta = document.getElementById('iCodigoConta').value;

  var oAjax = new Ajax.Request("con1_conplanoorcamento.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                   var oRetorno = eval("("+oAjax.responseText+")");
                                   alert(oRetorno.message.urlDecode());
                                 }
                                }
                               );
                               
  js_carregaReduzidos();
}



/**
 * Função que salva os reduzidos de uma conta.
 */

$("btnIncluirReduzido").observe("click", function() {

  var iCodigoPlanoConta  = $("iCodigoConta").value;
  var iCodigoInstituicao = $("iCodigoInstituicao").value;
  var iCodigoRecurso     = $("iCodigoRecurso").value;
  var iCodigoReduzido    = $("iCodigoReduzido").value;
  if (iCodigoInstituicao == "") {
    alert("Informe a instituição.");
    return false;
  }
  if (iCodigoRecurso == "") {
    alert("Informe o recurso.");
    return false;
  }

  js_divCarregando("Cadastrando reduzido, aguarde...", "msgBox");
  
  var oParam                    = new Object();
      oParam.exec               = "salvarReduzido";
      oParam.iCodigoPlanoConta  = iCodigoPlanoConta;
      oParam.iCodigoInstituicao = iCodigoInstituicao;
      oParam.iCodigoRecurso     = iCodigoRecurso;
      oParam.iCodigoReduzido    = iCodigoReduzido;

  var oAjax = new Ajax.Request("con1_conplanoorcamento.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoSalvarReduzidos
                                }
                               );
});


/**
 * Retorno do incluir de um novo reduzido
 */
function js_retornoSalvarReduzidos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  alert(oRetorno.message.urlDecode());

  $('iCodigoReduzido').value               = '';
  $('iCodigoInstituicao').value            = '';
  $('sDescricaoInstituicao').value         = '';
  $('iCodigoRecurso').value                = '';
  $('sDescricaoRecurso').value             = '';

  $('iCodigoInstituicao').disabled         = false;
  $('iCodigoInstituicao').style.background = "#FFFFFF";

  $('ancoraInstituicao').style.display     = "";
  $('ancoraDesabilitada').style.display    = "none";

  $('btnIncluirReduzido').value            = "Incluir";

  var iReduzido = oRetorno.iReduzido;

  /**
   * verificamos se existe vinculo de regras o estrutural do reduzido
   * se existir perguntamos se o usuario deseja criar a regra
   */
  if (oRetorno.lReduzidoVinculado == true) {

    var iTotalDocumentos = oRetorno.aEventoContabilVinculado.length;
    var aDocumentos      = new Array();
    var sDocumentos      = '';

    for (var iIndice = 0; iIndice < iTotalDocumentos; iIndice++ ) {

      var oEventoContabilVinculado = oRetorno.aEventoContabilVinculado[iIndice]; 
      var iDocumento = oEventoContabilVinculado.iDocumento;
      var sDescricaoDocumento = oEventoContabilVinculado.sDescricao.urlDecode();

      sDocumentos += iDocumento + " - " + sDescricaoDocumento + "\n";
      aDocumentos.push(iDocumento);
    }

    sMensagemPergunta = _M( sCaminhoMensagem + "vincular_regra", {'sDocumentos' : sDocumentos} );

    /**
     * perguntar se quer vincular
     */ 
    if (confirm(sMensagemPergunta)) { 
      
      js_divCarregando(_M( sCaminhoMensagem + "vinculandoReduzido"), "msgBox");
      
      var oParam = new Object();

      oParam.exec                  = "vincularReduzido";
      oParam.iCodigoReduzido       = iReduzido;
      oParam.aDocumentos           = aDocumentos;
      oParam.iCodConPcasp          = oUrl.iCodConPcasp;
      oParam.iCodigoContaOrcamento = oUrl.iCodigoConta;

      new Ajax.Request("con1_conplanoorcamento.RPC.php",
                       {method:'post',
                        parameters:'json=' + Object.toJSON(oParam),
                        onComplete: js_retornoVincularReduzidos
                       });
    }
  }

  js_carregaReduzidos();
}
 
function js_retornoVincularReduzidos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode()); 
} 
 
/**
 * Funções de pesquisa das instituições cadastradas
 */
function js_pesquisaInstituicao(lMostraWindow) {

  if (lMostraWindow) {
  
    var sUrl = 'func_instit.php?funcao_js=parent.js_preencheInstituicao|codigo|nomeinst';
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_db_instit',sUrl,'Pesquisa',true,'0');
  } else {
    if($("iCodigoInstituicao").value != ''){
     
      var sUrl = 'func_instit.php?pesquisa_chave='+$("iCodigoInstituicao").value+'&funcao_js=parent.js_completaInstituicao';
      js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_db_instit',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoInstituicao").value = ''; 
    }
  }
}

function js_preencheInstituicao(iCodigoInstit, sNomeInstit) {

  $('iCodigoInstituicao').value    = iCodigoInstit;
  $('sDescricaoInstituicao').value = sNomeInstit;
  db_iframe_db_instit.hide();
}

function js_completaInstituicao(sNomeInstit, lErro) {

  if (!lErro) {
    $('sDescricaoInstituicao').value = sNomeInstit;
  } else {
  
    $('iCodigoInstituicao').value    = '';
    $('sDescricaoInstituicao').value = sNomeInstit;
  }
}

function js_pesquisaRecurso(lMostraWindow) {

  if (lMostraWindow) {
  
    var sUrl = 'func_orctiporec.php?funcao_js=parent.js_preencheRecurso|o15_codigo|o15_descr';
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_recurso',sUrl,'Pesquisa',true,'0');
  } else {
    if($("iCodigoRecurso").value != ''){
     
      var sUrl = 'func_orctiporec.php?pesquisa_chave='+$("iCodigoRecurso").value+'&funcao_js=parent.js_completaRecurso';
      js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_recurso',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoRecurso").value = ''; 
    }
  }
}

function js_preencheRecurso(iCodigoRecurso, sDescricaoRecurso) {

  $('iCodigoRecurso').value    = iCodigoRecurso;
  $('sDescricaoRecurso').value = sDescricaoRecurso;
  db_iframe_recurso.hide();
}

function js_completaRecurso(sDescricaoRecurso, lErro) {

  if (!lErro) {
    $('sDescricaoRecurso').value = sDescricaoRecurso;
  } else {
  
    $('iCodigoRecurso').value    = '';
    $('sDescricaoRecurso').value = sDescricaoRecurso;
  }
}

js_carregaReduzidos();
</script>