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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once('dbforms/db_funcoes.php');

$oRotuloSaltes = new rotulo("saltes");
$oRotuloSaltes->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("estilos.css");
  db_app::load("prototype.maskedinput.js");
  db_app::load("windowAux.widget.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
  select {width: 98%;}
  textarea {width: 100%;}
  input#c90_estrutcontabil:disabled{background-color: #DEB887;
                                    color:black}
</style>
</head>
<body bgcolor="#CCCCCC" style="margin-top:30px;" onLoad="a=1" >
<center>
  <form name='form1'>

    <fieldset style="width: 600px">
      <legend><b>Manutenção de Contas da Baixa de Banco</b></legend>

      <table style="width: 100%">
        <tr>
          <td width="120px"><b>Classificação:</b></td>
          <td>
            <select id="iCodigoRetorno">
              <option value="0">Selecione...</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              db_ancora("<b>Conta da Tesouraria:</b>", "js_pesquisaContaTesouraria(true)", 1);
            ?>
          </td>
          <td>
            <?php
              db_input("k13_conta", 10, $Ik13_conta, true, "text", 1,"onchange='js_pesquisaContaTesouraria(false)'");
              db_input("k13_descr", 50, false, 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <br />
    <input type="button" name="btnBuscarContas" id="btnBuscarContas" value="Processar" />
  </form>
</center>
<?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>




<script>

  var sUrlRPC = "cai4_manutencaocontasbaixabanco.RPC.php";

  $('btnBuscarContas').observe('click', function() {

    var iCodigoRetorno = $('iCodigoRetorno').value;
    var iCodigoConta   = $('k13_conta').value;
    if (iCodigoRetorno == 0) {

      alert("Selecione um código de retorno.");
      return false;
    }

    if (iCodigoConta == "" || iCodigoConta == 0) {

      alert("Informe a conta da tesouraria.");
      return false;
    }

    if (!confirm("Confirma a alteração da conta da tesouraria na classificação selecionada?")) {
      return false;
    }

    js_divCarregando("Aguarde, processando alterações...", "msgBox");

    var oParam              = new Object();
    oParam.exec             = "processarAlteracaoConta";
    oParam.iContaTesouraria = iCodigoConta;
    oParam.iCodigoRetorno   = iCodigoRetorno;

    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method: 'POST',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_concluirAlteracao});

  });

  function js_concluirAlteracao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    alert(oRetorno.message.urlDecode());

    if (oRetorno.status == 1) {

      $('k13_conta').value = "";
      $('k13_descr').value = "";
      $('iCodigoRetorno').value = "0";
      $('iCodigoRetorno').options.length = 0;
      var oOption = new Option("Selecione...", "0");
      $('iCodigoRetorno').appendChild(oOption);
      js_buscaContasClassificacao();
    }
  }

  /**
   * Busca as contas disponiveis para alteração
   */
  function js_buscaContasClassificacao () {

    js_divCarregando("Aguarde, carregando contas...", "msgBox");

    var oParam  = new Object();
    oParam.exec = "getContasClassificacao";

    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method: 'POST',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_preencheComboBoxContas});

  }

  /**
   * Preenche o COMBOBOX com as classificações disponíveis para alteração
   */
  function js_preencheComboBoxContas (oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.aContas.length == 0) {

       alert("Não existe classificação para ser autenticada.");
       return false;
    }

    oRetorno.aContas.each(function (oClassificacao, iIndice) {

      var sDescricao  = oClassificacao.codcla     +" - ";
      sDescricao     += oClassificacao.k15_codbco +" - ";
      sDescricao     += oClassificacao.k15_codage +" - ";
      sDescricao     += oClassificacao.k00_conta  +" - ";
      sDescricao     += oClassificacao.k13_descr;

      var oOption = new Option(sDescricao, oClassificacao.codret);
      $('iCodigoRetorno').appendChild(oOption);
    });
  }

  js_buscaContasClassificacao();


  /**
   * Funções de Pesquisa
   */
  function js_pesquisaContaTesouraria(lMostra) {

    var sUrlSaltes = "func_saltesmovimentacaobaixabanco.php";
    if (lMostra) {
      sUrlSaltes += "?funcao_js=parent.js_preencheContaSaltes|k13_conta|k13_descr";
    } else {

      sUrlSaltes += "?pesquisa_chave="+$F('k13_conta')+"&funcao_js=parent.js_completaContaSaltes";
      if ($F('k13_conta') == "") {

        $('k13_conta').value = "";
        $('k13_descr').value = "";
      }
    }

    js_OpenJanelaIframe('', "db_iframe_saltes", sUrlSaltes, "Pesquisa Conta da Tesouraria", lMostra);
  }

  function js_preencheContaSaltes(iCodigoSaltes, sDescricao) {

    $('k13_conta').value = iCodigoSaltes;
    $('k13_descr').value = sDescricao;
    db_iframe_saltes.hide();
  }

  function js_completaContaSaltes(sDescricao, lErro) {

    $('k13_descr').value = sDescricao;
    if (lErro) {
      $('k13_conta').value = "";
    }
  }
</script>