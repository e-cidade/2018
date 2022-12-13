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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("as16_sequencial");
$oRotulo->label("as16_db_depart");
$oRotulo->label("as16_descricao");
$oRotulo->label("as16_identificadorunico");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("prototype.js, scripts.js, strings.js, prototype.maskedinput.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px;" >
    <center>
      <form method="post" name='form1'>
        <div style="display: table">
          <fieldset>
            <legend><b>Cadastro de Local de Atendimento</b></legend>
            <table>
              <tr style="display: none;">
                <td>
                  <label><b><?=$Las16_sequencial?></b></label>
                </td>
                <td>
                  <?php db_input('as16_sequencial', 5, $Ias16_sequencial, true, 'text', 3); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label><b>Departamento: </b></label>
                </td>
                <td>
                  <?php db_input('as16_db_depart', 5, $Ias16_db_depart, true, 'text', 3, "", "", "", "display: none;"); ?>
                  <?php db_input('descricaoDepartamento', 50, 'descricaoDepartamento', true, 'text', 3); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label><b>Local de Atendimento: </b></label>
                </td>
                <td>
                  <?php
                    $aLocal = array(1 => "1 - CRAS", 2 => "2 - CREAS"); 
                    db_select('localAtendimento', $aLocal, true, 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label><b>Descrição: </b></label>
                </td>
                <td>
                  <?php
                    db_input('as16_descricao', 50, $Ias16_descricao, true, 'text', 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label><b>Identificador Único: </b></label>
                </td>
                <td>
                  <?php
                    db_input('as16_identificadorunico', 50, $Ias16_identificadorunico, true, 'text', 1);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </div>
        <input type="button" id="btnSalvar" value="Salvar" onclick="js_salvar();" />
        <input type="button" id="btnExcluir" value="Excluir" onclick="js_excluir();" disabled="disabled" />
      </form>
    </center>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
var sRpc = 'soc4_localatendimentosocial.RPC.php';
$('localAtendimento').style.width = '100%';

/**
 * Busca os dados referente ao departamento, caso ja esteja cadastrado como local de atendimento
 */
function js_buscaDados() {

  var oParametro      = new Object();
      oParametro.exec = 'getDados';

  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = js_retornoBuscaDados;

  js_divCarregando("Aguarde, buscando os dados do local de atendimento.", "msgBox");
  new Ajax.Request(sRpc, oDadosRequest);
}

/**
 * Retorno da busca pelos dados do local
 */
function js_retornoBuscaDados(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  $('as16_db_depart').value        = oRetorno.iDepartamento;
  $('descricaoDepartamento').value = oRetorno.sDescricaoDepartamento.urlDecode();
  $('as16_descricao').value        = oRetorno.sDescricaoDepartamento.urlDecode();
  
  if (oRetorno.iCodigo != '') {

    $('as16_sequencial').value         = oRetorno.iCodigo;
    $('localAtendimento').value        = oRetorno.iTipo;
    $('as16_descricao').value          = oRetorno.sDescricao.urlDecode();
    $('as16_identificadorunico').value = oRetorno.sIdentificadorUnico.urlDecode();
    $('btnExcluir').disabled           = false;
    
    /**
     * Caso exista algum cidadao cadastrado com vinculo ao departamento, nao permitimos alterar o tipo de atendimento 
     * nem excluir o registro
     */
    if (oRetorno.lTemVinculo) {
      
      $('localAtendimento').disabled = true;
      $('btnExcluir').disabled       = true;
      alert(oRetorno.message.urlDecode());
    }
  }

}

/**
 * Persiste os dados do formulario
 */
function js_salvar() {

  if (js_verificaCampos()) {
    
    var oParametro                     = new Object();
        oParametro.exec                = 'salvarLocalAtendimento';
        oParametro.iCodigo             = $F('as16_sequencial');
        oParametro.sDescricao          = $F('as16_descricao');
        oParametro.sIdentificadorUnico = $F('as16_identificadorunico');
        oParametro.iTipo               = $F('localAtendimento');
  
    var oDadosRequest            = new Object();
        oDadosRequest.method     = 'post';
        oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequest.onComplete = js_retornoSalvar;

    js_divCarregando("Aguarde, salvando as informações do local de atendimento.", "msgBox");
    new Ajax.Request(sRpc, oDadosRequest);
  }
}

function js_retornoSalvar(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {
    js_buscaDados();
  }
}

/**
 * Validamos se os campos foram preenchidos
 */
function js_verificaCampos() {

  if ($F('as16_descricao') == '') {

    alert('Informe a descrição do local de atendimento.');
    return false;
  }

  if ($F('as16_identificadorunico') == '') {

    alert('Informe o identificador único do local de atendimento.');
    return false;
  }

  return true;
}

function js_excluir() {

  var oParametro     = new Object();
  oParametro.exec    = 'excluirLocalAtendimento';
  oParametro.iCodigo = $F('as16_sequencial');

  var oDadosRequest        = new Object();
  oDadosRequest.method     = 'post';
  oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
  oDadosRequest.onComplete = js_retornoExcluir;

  js_divCarregando("Aguarde, excluindo local de atendimento.", "msgBox");
  new Ajax.Request(sRpc, oDadosRequest);
}

function js_retornoExcluir (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    $('btnExcluir').disabled           = true;
    $('as16_identificadorunico').value = "";
    js_buscaDados();
  }
}

js_buscaDados();
</script>