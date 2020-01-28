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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
$aux = new cl_arquivo_auxiliar;


?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("estilos.css");
      db_app::load("datagrid.widget.js");
      db_app::load("strings.js");
      db_app::load("grid.style.css");
      db_app::load("estilos.css");
      db_app::load("DBLancador.widget.js, DBAncora.widget.js, dbtextField.widget.js, DBToogle.widget.js");
    ?>
  </head>
  <style>
   #fieldset_lista {
     float: left;
   }
  </style>

  <body bgcolor="#CCCCCC" style="margin-top: 30px;">
    <center>
   		<form name="form1" method="post">
      <fieldset style="width: 600px;">
        <legend>
          <strong>Razão por Conta Corrente</strong>
        </legend>

        <table border='0' style="width: 100%;">
          <tr>
            <td><strong>Período:</strong></td>
            <td>
              <?php
                db_inputdata("dtInicial", "", "", "", true, "text", 1, "");
                echo " a ";
                db_inputdata("dtFinal", "", "", "", true, "text", 1, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
                db_ancora("<b>Conta Corrente:<b>", "js_pesquisaContaCorrente(true);", 1);
              ?>
            </td>
            <td>
              <?php
                $funcaoJsInscricaoPassivo = "onchange = 'js_pesquisaContaCorrente(false);'";
                db_input("iContaCorrente", 10, 1, true, "text", 2, $funcaoJsInscricaoPassivo);
                db_input("sContaCorrente", 40, "", true, 'text', 3, "");
              ?>
          </td>
          </tr>
          <tr>
            <td id="tdCtnReduzidosContaCorrente" colspan="2" align="left">
            </td>
          </tr>
          <tr id='trCredores' style="display:none;">
            <td id="tdCtnCredoresContaCorrente" colspan="2" align="left">
            </td>
          </tr>
          <tr id="trPrestacaoContas" style="display:none" >
            <td>
              <b>Prestação de Contas:</b>
            </td>
            <td>
              <select id="comboboxPrestacaoContas" style="width: 100%">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
      <input name="lProcessar" id="lProcessar" onclick="js_validaFiltros();" type="button" style="margin-top: 10px;" value="Imprimir" />
    </center>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>

<script type="text/javascript">

  var aCamposReduzido      = new Array('c61_reduz', 'c60_descr');
  var aCamposCredor        = new Array('z01_numcgm', 'z01_nome');
  var aTipoPrestacaoContas = new Array();

  var oDBLancadorReduzido = new DBLancador('oDBLancadorReduzido');
  oDBLancadorReduzido.setNomeInstancia('oDBLancadorReduzido');
  oDBLancadorReduzido.setTextoFieldset("Adicionar Reduzidos");
  oDBLancadorReduzido.setLabelAncora('Reduzido:');
  oDBLancadorReduzido.setGridHeight(200);


  oDBLancadorReduzido.lancarRegistro = function() {

    var iCodigo = oDBLancadorReduzido.oElementos.oInputCodigo.value;

    if (iCodigo == '') {

      alert('Nenhum registro selecionado.');
      return false;
    }

	  var lAdicionaRegistro = oDBLancadorReduzido.adicionarRegistro(oDBLancadorReduzido.oElementos.oInputCodigo.value,
                                                                  oDBLancadorReduzido.oElementos.oInputDescricao.value);



    if (!lAdicionaRegistro) {
      alert("Registro já adicionado.");
    }
    js_criarLancadorCredores();
  };


  /**
   * Reescrito método que remove uma linha da grid
   */
  oDBLancadorReduzido.removerRegistro = function(iCodigo) {

    var aRegistrosGrid = oDBLancadorReduzido.getRegistros(true);
    delete(aRegistrosGrid["oDBLancadorReduzido"+iCodigo]);
    oDBLancadorReduzido.renderizarRegistros();
    js_criarLancadorCredores();
  };


  var oDBLancadorCredor   = new DBLancador('oDBLancadorCredor');
  oDBLancadorCredor.setTextoFieldset("Adicionar Credores");
  oDBLancadorCredor.setLabelAncora('Credores:');
  oDBLancadorCredor.setNomeInstancia('oDBLancadorCredor');
  oDBLancadorCredor.setGridHeight(200);

  /**
   * Cria o lançador para os reduzidos de contas correntes do sistema
   */
  function js_criarLancadorReduzidos() {

    oDBLancadorReduzido = new DBLancador('oDBLancadorReduzido');
    oDBLancadorReduzido.setNomeInstancia('oDBLancadorReduzido');
    oDBLancadorReduzido.setTextoFieldset("Adicionar Reduzidos");
    oDBLancadorReduzido.setLabelAncora('Reduzido:');
    oDBLancadorReduzido.setGridHeight(200);
    oDBLancadorReduzido.setParametrosPesquisa('func_conplanoRazaoContaCorrente.php',
                                               aCamposReduzido,
                                              'iConta='+$F('iContaCorrente'));
    oDBLancadorReduzido.show($('tdCtnReduzidosContaCorrente'));
  }

  function js_criarLancadorCredores() {

    var sReduzidos = "";
    var sVirgula   = "";
    var aReduzidos = oDBLancadorReduzido.getRegistros(false);
    aReduzidos.each(function (oDado, iIndice) {

      sReduzidos += sVirgula+oDado.sCodigo;
      sVirgula    = ", ";
    });

    /**
     * Setado um timeout, pois ao clicar na pesquisa da conta corrente, selecionando a conta 19, o campo iContaCorrente
     * nao tinha o campo preenchido a tempo de ser passado como filtro ao pesquisar os credores
     */
    setTimeout(timeOut, 200);
    function timeOut() {

      sStringAdicional = 'c19_contacorrente='+$F('iContaCorrente')+'&sReduzidos='+sReduzidos;
      oDBLancadorCredor.setParametrosPesquisa('func_contacorrentedetalhecgm.php',
                                              aCamposCredor,
                                              sStringAdicional);
      oDBLancadorCredor.show($('tdCtnCredoresContaCorrente'));
      
      var oDBToogle = new DBToogle(oDBLancadorCredor.getFieldset(), false);
  
      $('txtCodigooDBLancadorCredor').observe("change", function() {
        
        sStringAdicional += '&c17_sequencial='+$('txtCodigooDBLancadorCredor').value;
        oDBLancadorCredor.setParametrosPesquisa('func_contacorrentedetalhecgm.php',
                                                aCamposCredor,
                                                sStringAdicional);
      });
    }
  }


  /**
   * Libera caso o conta corrente selecionado seja o 19 - ADIANTAMENTOS - CONCESSÃO ou 3 - CC 3 - CREDOR/FORNECEDOR/DEVEDOR
   */
  $('iContaCorrente').observe('change', function() {

      $('trCredores').style.display = 'none';
      $('trPrestacaoContas').style.display = 'none';
	  
    switch ($F('iContaCorrente')) {

    case "19":

	    js_getTipoPrestacaoContas();
	    $('trCredores').style.display = '';
	    $('trPrestacaoContas').style.display = '';
	    js_criarLancadorCredores();
    break;

    case "3":
        
    	$('trCredores').style.display = '';
    	js_criarLancadorCredores();
    	
    break;

    default :
    
      $('trCredores').style.display = 'none';
      $('trPrestacaoContas').style.display = 'none';

    }

  });


  function js_getTipoPrestacaoContas() {

    js_divCarregando("Aguarde, buscando tipos de prestação de contas...", "msgBox");
    var oParam  = new Object();
    oParam.exec = "getTiposEventoEmpenho";
    var oAjax = new Ajax.Request("con4_regraeventocontabil.RPC.php",
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete:js_preencheArray
                                 });
  }

  function js_preencheArray(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    $('comboboxPrestacaoContas').options.length = 0;
    oRetorno.aDados.each(function (oTipoEvento, iIndice) {

      var oOption = new Option(oTipoEvento.e44_descr.urlDecode(), oTipoEvento.e44_tipo);
      $('comboboxPrestacaoContas').appendChild(oOption);
    });

  }

  function js_validaFiltros() {

    var dtInicial      = $("dtInicial").value;
    var dtFinal        = $("dtFinal").value;
    var iContaCorrente = $F('iContaCorrente');
    var sListaReduzido = "";
    var sListaCredor   = "";
    var sFonte         = "con2_razaocontacorrente002.php";
    var sQuery         = "";

    var sVirgulaReduzido = "";
    var aReduzidosSelecionados = oDBLancadorReduzido.getRegistros();
    if (aReduzidosSelecionados.length > 0) {

      aReduzidosSelecionados.each(function (oDado, iIndice) {

        sListaReduzido += sVirgulaReduzido+oDado.sCodigo;
        sVirgulaReduzido = ", ";
      });
    }

    var sVirgulaCredor   = "";
    var aCredoresSelecionados = oDBLancadorCredor.getRegistros();
    if (aCredoresSelecionados.length > 0) {

      aCredoresSelecionados.each(function (oDadoCredor, iIndice) {

        sListaCredor += sVirgulaCredor+oDadoCredor.sCodigo;
        sVirgulaCredor = ", ";
      });
    }

    if (dtInicial == "") {

      alert("Preencha a data inicial.");
      return false;
    }

    if (dtFinal == "") {

      alert("Preencha a data final.");
      return false;
    }

    if (js_comparadata(dtInicial, dtFinal, ">")) {

      alert("Data inicial superior a data final.");
      return false;
    }

    if (iContaCorrente == "") {

      alert('Selecione uma conta para emissão.');
      return false;
    }


    sQuery  = "?dtInicial="        + dtInicial;
    sQuery += "&dtFinal="          + dtFinal;
    sQuery += "&iContaCorrente="   + iContaCorrente;
    sQuery += "&sListaReduzido="   + sListaReduzido;
    sQuery += "&sListaCredor="     + sListaCredor;
    sQuery += "&iPrestacaoContas=" + $F('comboboxPrestacaoContas');
    jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  }

  function js_pesquisaContaCorrente(lMostra) {
    var sFunction = lMostra ? "js_preencheContaCorrente" : "js_preencheContaCorrenteDescricao";
    if(lMostra) {

      var sUrlLookUp = "func_contacorrente.php?funcao_js=parent." + sFunction + "|c17_sequencial|c17_contacorrente|c17_descricao";
      js_OpenJanelaIframe("", "db_iframe_contacorrente", sUrlLookUp, "Pesquisa Conta Corrente", true);
    } else {

      var sUrlLookUp = "func_contacorrente.php?pesquisa_chave=" + $F("iContaCorrente") + "&funcao_js=parent." + sFunction;
      js_OpenJanelaIframe("", "db_iframe_contacorrente", sUrlLookUp, "Pesquisa", false);
    }
  }

  function js_preencheContaCorrente(iContaCorrente, sContaCorrente, sDescricaoConta) {

	      $('trCredores').style.display = 'none';
	      $('trPrestacaoContas').style.display = 'none';
    switch (iContaCorrente) {
    
      case "19" :

      	$('trCredores').style.display = '';
    	  $('trPrestacaoContas').style.display = '';
    	  js_getTipoPrestacaoContas();
    	  js_criarLancadorCredores();
      break;
      
      case "3" :

    	  $('trCredores').style.display = '';
    	  js_criarLancadorCredores();
      break;

      default :
 	      $('trCredores').style.display = 'none';
	      $('trPrestacaoContas').style.display = 'none';
          

    }
    $("sContaCorrente").value = sContaCorrente + " - " + sDescricaoConta;
    $("iContaCorrente").value = iContaCorrente;
    js_criarLancadorReduzidos();
    db_iframe_contacorrente.hide();
  }

  function js_preencheContaCorrenteDescricao(sContaCorrente, lErro) {

    $("sContaCorrente").value = sContaCorrente;
    if(lErro) {

      $("iContaCorrente").focus();
      $("iContaCorrente").value = "";
    }
    js_criarLancadorReduzidos();
  }


  function js_bloqueiaCampos(){

    var iContaCorrente = $F('iContaCorrente');

    if (iContaCorrente == null || iContaCorrente == '') {

       $('c61_reduz').readOnly         = true;
       $('c61_reduz').style.background = "#DEB887";
    }
  }


  js_criarLancadorReduzidos();
</script>