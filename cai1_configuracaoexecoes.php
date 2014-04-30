<?php
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php"); 
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
    db_app::load("DBLancador.widget.js");
    db_app::load("strings.js");
    db_app::load("grid.style.css");
    db_app::load("estilos.css");
  ?>
  </head>

<body bgcolor="#CCCCCC" style="margin-top: 50px" >
  <center>
    <form id="form1" name="form1">
      <fieldset style=" height:250;width: 600;">
        <legend><b>Exceções de Movimentos para Importação de Extrato</b></legend>
        <table border="0" >
          <tr>
            <td >
            <?php  
              db_ancora("<b>Banco:</b>", "js_pesquisaBanco(true)", 1); 
              db_input('iCodigoBanco', 10, "", true, 'text', 1, "onchange='js_pesquisaBanco(false);'onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' ");
              db_input('sDescricaoBanco', 60, "", true, 'text', 3, '');
            ?>
            </td>
          </tr>
          <tr>
            <td>
              <div id="ctnLancadorHistorico"></div>
            <td>
          </tr>
        </table>
      </fieldset>
    </form>
    <input type="button" value="Salvar" onclick="js_salvarDadosExcecoes();"/>
  </center>  
</body>
<?php 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>  
<script>


var sUrl = "cai1_configuracaoexecoesmovimentos.RPC.php";

function js_criaLancador() {

	oLancadorHistorico = new DBLancador("oLancadorHistorico");
	oLancadorHistorico.setNomeInstancia("oLancadorHistorico");
	oLancadorHistorico.setLabelAncora("Histórico: ");
	oLancadorHistorico.setTextoFieldset("Históricos Selecionados");	
  oLancadorHistorico.setParametrosPesquisa("func_bancoshistmov.php", ['k66_sequencial', 'k66_descricao'], "iCodigoBanco=" + $F("iCodigoBanco"));
	oLancadorHistorico.setGridHeight("400px");
	oLancadorHistorico.show($("ctnLancadorHistorico"));
}

function js_pesquisaBanco(lMostra) {

  var sUrlLookUp = "func_db_bancos.php?funcao_js=parent.js_mostraBanco|db90_codban|db90_descr";
  if (!lMostra) {
    sUrlLookUp = "func_db_bancos.php?pesquisa_chave=" + $F("iCodigoBanco") + "&funcao_js=parent.js_completaDescricaoBanco";
  }
  js_OpenJanelaIframe('', 'db_iframe_db_bancos', sUrlLookUp, 'Pesquisa Banco', lMostra); 
}

function js_mostraBanco(iCodigoBanco, sDescricaoBanco) {

  oLancadorHistorico.setParametrosPesquisa("func_bancoshistmov.php", ['k66_sequencial', 'k66_descricao'], "iCodigoBanco=" + iCodigoBanco);
  $("iCodigoBanco").value     = iCodigoBanco;
  $("sDescricaoBanco").value  = sDescricaoBanco;
  db_iframe_db_bancos.hide();
  js_carregaGridHistorico(iCodigoBanco);
}

function js_completaDescricaoBanco(sDescricaoBanco, lErro) {

  $("sDescricaoBanco").value = sDescricaoBanco;
  db_iframe_db_bancos.hide();

  if (lErro) {
    
    $("iCodigoBanco").value = "";
    oLancadorHistorico.clearAll();    
    return false;
  }

  js_carregaGridHistorico($F("iCodigoBanco"));
  oLancadorHistorico.setParametrosPesquisa("func_bancoshistmov.php", ['k66_sequencial', 'k66_descricao'], "iCodigoBanco=" + $F("iCodigoBanco"));
}

function js_carregaGridHistorico (iCodigoBanco) {
  
  oLancadorHistorico.clearAll();

  if (!iCodigoBanco) {
    return false;
  }

  js_divCarregando('Aguarde, Consultando Históricos...','msgBox');
  var oParam          = new Object();
  oParam.exec         = 'buscaHistoricosExcecoesBancos';
  oParam.iCodigoBanco = iCodigoBanco;
  
  var oAjax = new Ajax.Request( sUrl, 
                                {method     : 'post', 
                                 parameters : 'json='+Object.toJSON(oParam), 
                                 onComplete : js_carregaDadosGridHistorico });
}

/**
 * Função para carregar os históricos que são excecoes de movimentos, vinculados ao banco
 * nesta rotina.
 */
function js_carregaDadosGridHistorico(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if(oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
  oLancadorHistorico.carregarRegistros(oRetorno.aHistoricosExcecoes);
}

/**
 * Vincula as Exceções com o Banco selecionado
 */
function js_salvarDadosExcecoes() {

  var iCodigoBanco = $F("iCodigoBanco"); 
  if (!iCodigoBanco) {

    alert("Preencha o código do banco para associar as exceções de movimentos para importação de extrato");
    return false;
  }
  var oParam          = new Object();
  oParam.exec         = 'salvarDadosExcecoes';
  oParam.iCodigoBanco = iCodigoBanco;
  oParam.aHistoricos  = oLancadorHistorico.getRegistros();
  js_divCarregando('Aguarde, Salvando dados...','msgBox');

  var oAjax = new Ajax.Request( sUrl, 
                                {method     : 'post', 
                                 parameters : 'json='+Object.toJSON(oParam),
                                 onComplete : js_retornaSalvar }); 
}

function js_retornaSalvar (oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());

  if ( oRetorno.status == '1'  ) {
    document.location.href = 'cai1_configuracaoexecoes.php';
  }
}

js_criaLancador();

</script>