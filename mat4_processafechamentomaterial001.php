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
  require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <div id="div_container" style="width: 300px; margin: auto;">
      <fieldset>
        <legend style="font-weight: bold;">Processar Fechamento do Estoque</legend>

        <table>
          <tr>
            <td style="font-weight: bold;">
              Último Processamento:
            </td>
            <td>
              <?php db_input("dtUltimoProcessamento", '10', null, true, 'text', 3) ?>
            </td>
          </tr>
          <tr>
            <td style="font-weight: bold;">
              Data do Processamento:
            </td>
            <td>
              <?php db_inputdata("dtProcessamento", null, null, null, true, 'text', 1) ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <p align="center">
        <input type="button" id="btnProcessa" value="Processar">
      </p>
    </div>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));


  ?>

  </body>
</html>

<script>
  
  var sUrlRPC             = "mat4_processafechamentomaterial004.RPC.php";
  var sUrlMensagemSistema = "patrimonial.material.mat4_processafechamentomaterial004.";

  $("btnProcessa").observe("click", function() {

    if ($F("dtProcessamento") == "") {

      alert(_M(sUrlMensagemSistema+"data_processamento_invalida"));
      return false;
    }

    if (!confirm(_M(sUrlMensagemSistema+"confirmar_processamento_fechamento_material"))) {
      return false;
    }

    js_divCarregando("Aguarde, processando fechamento do estoque.", "msgBox");


    var oParam             = new Object();
    oParam.exec            = "processarFechamentoMaterial";
    oParam.dtProcessamento = $F("dtProcessamento");

    new Ajax.Request( sUrlRPC, {
                      method: 'post', 
                      async: false,
                      parameters: 'json='+Object.toJSON(oParam), 
                      onComplete: js_concluirProcessamento});
  });

  function js_concluirProcessamento(oAjax) {

    js_removeObj("msgBox");

    var oRetorno = eval("(" + oAjax.responseText + ")");
    alert(oRetorno.sMessage.urlDecode());
  }

  function getDataUltimoProcessamento() {

    js_divCarregando("Aguarde, carregando último processamento.", "msgBox");

    var oParam = new Object();
    oParam.exec = "getDataUltimoProcessamento";

    new Ajax.Request( sUrlRPC, {
                      method: 'post', 
                      async: false,
                      parameters: 'json='+Object.toJSON(oParam), 
                      onComplete: function (oAjax) {
                        
                        js_removeObj("msgBox");
                        var oRetorno = eval("(" + oAjax.responseText + ")");
                        $("dtUltimoProcessamento").value = oRetorno.dtUltimoProcessamento;
                      }
                    });
  

  }

  getDataUltimoProcessamento();
</script>