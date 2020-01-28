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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");

$oRotuloGED = new rotulo("configuracaoged");
$oRotuloGED->label();
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?PHP
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js");
    db_app::load("estilos.css");
  ?>
</head>
<body style="background-color: #CCCCCC; margin-top:25px;" >
<center>
  <fieldset style="width: 400px">
    <legend><strong>Configurações GED</strong></legend>
    <table style="width: 100%">
      <tr style="display:none;">
        <td><b>Código:</b></td>
        <td>
          <?php
            db_input("db141_sequencial", 10, $Idb141_sequencial, true, "text", 3);
          ?>
        </td>
      </tr>
      <tr>
        <td><b>Ativo:</b></td>
        <td>
          <select id="db141_ativo">
            <option value="f">Não</option>
            <option value="t">Sim</option>
          </select>
        </td>
      </tr>
      <tr id="trWebServiceURI">
        <td><b>WebService URI:</b></td>
        <td>
          <?php
            db_input("db141_webserviceuri", 50, $Idb141_webserviceuri, true, "text", 1);
          ?>
        </td>
      </tr>
      <tr id="trWebServiceLocation">
        <td nowrap="nowrap"><b>WebService Location:</b></td>
        <td>
          <?php
            db_input("db141_webservicelocation", 50, $Idb141_webservicelocation, true, "text", 1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input type="button" id="btnSalvar" value="Salvar" />
  </p>
</center>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
$('db141_ativo').style.width = "100px";


$('btnSalvar').observe('click', function() {

  if ($F('db141_ativo') == "t" && ($F('db141_webserviceuri') == "" || $F('db141_webservicelocation') == "")) {

    alert("Você está ativando o gerenciador eletrônico de documentos. É preciso informar a URI e o Location para o webservice.");
    return false;
  }

  js_divCarregando("Aguarde, salvando configuração...", "msgBox");

  var oParam                      = new Object();
  oParam.exec                     = "salvarConfiguracao";
  oParam.db141_sequencial         = $F('db141_sequencial');
  oParam.db141_ativo              = $F('db141_ativo');
  oParam.db141_webserviceuri      = $F('db141_webserviceuri');
  oParam.db141_webservicelocation = $F('db141_webservicelocation');


  new Ajax.Request("con4_configuracaoged004.php",
                   {method: 'post',
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: js_concluirConfiguracao});
});

function js_concluirConfiguracao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
}




function js_carregarConfiguracao() {

  js_divCarregando("Aguarde, buscando configuração...", "msgBox");

  var oParam  = new Object();
  oParam.exec = "getConfiguracao";

  new Ajax.Request("con4_configuracaoged004.php",
                   {method: 'post',
                    parameters: 'json='+Object.toJSON(oParam),
                    onComplete: js_preencheFormulario});
}

function js_preencheFormulario(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    $('db141_sequencial').value         = "";
    $('db141_ativo').value              = "";
    $('db141_webserviceuri').value      = "";
    $('db141_webservicelocation').value = "";
    return false;
  }

  $('db141_sequencial').value         = oRetorno.db141_sequencial;
  $('db141_ativo').value              = oRetorno.db141_ativo;
  $('db141_webserviceuri').value      = oRetorno.db141_webserviceuri.urlDecode();
  $('db141_webservicelocation').value = oRetorno.db141_webservicelocation.urlDecode();
}

/**
 * Função que altera a cor dos inputs para branco (obrigatório)
 */
function js_verificarSituacaoConfiguracao() {

  if ($F('db141_ativo') == "t") {

    $('db141_webserviceuri').style.backgroundColor      = "#FFFFFF";
    $('db141_webservicelocation').style.backgroundColor = "#FFFFFF";

  } else {

    $('db141_webserviceuri').style.backgroundColor      = "#E6E4F1";
    $('db141_webservicelocation').style.backgroundColor = "#E6E4F1";
  }
}

$('db141_ativo').observe("change", js_verificarSituacaoConfiguracao);

js_carregarConfiguracao();
</script>