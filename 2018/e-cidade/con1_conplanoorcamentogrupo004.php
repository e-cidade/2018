<?
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
        <legend><b>Grupos</b></legend>
        <table width="100%">
          <tr>
            <td><b>Código Conta:</b></td>
            <td>
              <?
                db_input("iCodigoConta", 10, null, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td><?db_ancora('<b>Grupo:</b>', "js_pesquisaGrupos(true);", 1)?></td>
            <td>
              <?
                db_input("c20_sequencial", 10, @$Ic20_sequencial, true, 'text', 1, "onChange='js_pesquisaGrupos(false);'");
                db_input('c20_descr',50, @$Ic20_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="incluirGrupo" id="incluirGrupo" onclick="js_incluirGrupo();" value="Incluir">
      <fieldset>
      <legend><b>Grupos de Contas Cadastrados</b></legend>
      <div id="divGridGrupo">
      </div>
    </fieldset>
    </center>
  </form>
</body>
</html>

<script>

var iCodigoConta = <?=$oGet->iCodigoConta;?>;

var oGridGrupo          = new DBGrid('oGridGrupo');
oGridGrupo.nameInstance = 'oGridGrupo';
oGridGrupo.sName        = 'oGridGrupo';
oGridGrupo.setCellAlign = (new Array("right","left", "center"));
aHeaders                = new Array("Código Grupo", "Descricao", "Ação");
oGridGrupo.aWidths      = new Array(20, 60, 10);
oGridGrupo.setHeader(aHeaders);
oGridGrupo.show($('divGridGrupo'));

function js_carregaGrupos() {

  js_divCarregando("Aguarde, carregando Grupos...", "msgBox");
  
  var oParam          = new Object();
  oParam.exec         = "getGrupos";
  oParam.iCodigoConta = iCodigoConta;

  var oAjax = new Ajax.Request("con1_conplanoorcamento.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheGridGrupo
                                }
                               );
}

function js_preencheGridGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  oGridGrupo.clearAll(true);
  if (oRetorno.aGrupoContas.length > 0) {
    oRetorno.aGrupoContas.each(function (oGrupo, iLinha) {

      var aLinha = new Array();
      aLinha[0]  = oGrupo.c20_sequencial; 
      aLinha[1]  = oGrupo.c20_descr.urlDecode();
      aLinha[2]  = '<input type="button" id="btnReduzExc_'+iLinha+'" value="E"';
      aLinha[2] += '       title="Excluir Registro" onclick="js_excluirGrupo('+oGrupo.c20_sequencial+')">';

      oGridGrupo.addRow(aLinha);
    });
    oGridGrupo.renderRows();
  }
}

function js_excluirGrupo(iConGrupo) {


  if (!confirm("Deseja excluir o grupo: "+iConGrupo+"?")) {
    return false;
  }
  js_divCarregando("Excluindo grupo...", "msgBox");
  var oParam          = new Object();
  oParam.exec         = "excluirGrupo";
  oParam.iCodigoConta = iCodigoConta;
  oParam.iConGrupo    = iConGrupo;

  var oAjax = new Ajax.Request("con1_conplanoorcamento.RPC.php",
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_excluiGrupo
                                }
                               );
}

function js_excluiGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  js_carregaGrupos();
}

function js_pesquisaGrupos(mostra) {

  if (mostra === true) {
    js_OpenJanelaIframe("top.corpo.iframe_grupos",
                        "db_iframe_grupo",
                        "func_congrupo.php?funcao_js=parent.js_mostraGrupos|c20_sequencial|c20_descr",
                        "Pesquisa", true, '0');
  } else { 

    var sValorCampo = $F('c20_sequencial');
    if (sValorCampo !== '') {
      js_OpenJanelaIframe("top.corpo.iframe_grupos",
                          "db_iframe_grupo",
                          "func_congrupo.php?pesquisa_chave="+sValorCampo+"&funcao_js=parent.js_mostraGrupos",
                          "Pesquisa", false);
    } else {
      $('c20_descr').value = '';
    }
  }
}

function js_mostraGrupos() {

  if (arguments[1] === true) {js_carregaGrupos

    $('c20_sequencial').value = '';
    $('c20_descr').value = arguments[0];
    $('c20_sequencial').focus();
  } else if (arguments[1] === false) {
    $('c20_descr').value = arguments[0];
  } else {

    $('c20_sequencial').value = arguments[0];
    $('c20_descr').value = arguments[1];
  }
  db_iframe_grupo.hide();
}

function js_incluirGrupo() {

  js_divCarregando('Aguarde, incluindo conta', 'msgBox');
  
  var oParam = new Object();
      oParam.exec         = "incluirGrupo";
      oParam.iCodigoConta = iCodigoConta;
      oParam.iCodigoGrupo = $F('c20_sequencial');
      
  var oAjax = new Ajax.Request('con1_conplanoorcamento.RPC.php',
                               {method: 'POST',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornoInclusaoGrupo});
}

function js_retornoInclusaoGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status === 1) {
    
    alert('Grupo incluído com sucesso');
    $('c20_sequencial').value = '';
    $('c20_descr').value      = '';
    js_carregaGrupos();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
js_carregaGrupos();
</script>