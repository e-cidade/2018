<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once'libs/db_liborcamento.php';
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style>
      label {font-weight: bold}
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #cccccc" >
   <div class="container">
     <form id="frmReprocessamento">
       <fieldset>
         <legend>
           Processamento Consistência Suprimento de Fundos
         </legend>
         <table>
           <tr>
             <td><label for="dtinicial">Data Inicial:</label></td>
             <td>
               <?php
               db_inputdata('dtinicial', null, null, null, true, 'text', 1);
               ?>
             </td>
           </tr>
           <tr>
             <td><label for="dtfinal">Data Final:</label></td>
             <td>
               <?php
               db_inputdata('dtfinal', null, null, null, true, 'text', 1);
               ?>
             </td>
           </tr>
           <tr>
             <td><label for="ano">Ano:</label></td>
             <td>
               <?php
               $ano = DB_getsession('DB_anousu');
               db_input('ano', 10,  1, true, 'text', 1);
               ?>
             </td>
           </tr>
         </table>
       </fieldset>
       <input type="button" value="Pesquisar" id="btnPesquisar">
       <input type="button" value="Processar" id="btnProcessar">
     </form>
     <fieldset style="width: 1000px">
       <legend>Empenhos de Suprimento de Fundos Inconsistentes</legend>
       <div id="ctnGridEmpenhos">
       </div>
     </fieldset>
   </div>
  </body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

  $('btnProcessar').onclick = function () {

    if (!confirm('Confirma o processamento dos Suprimentos de Fundos?')) {
      return;
    }

    var oParam = {

      exec       :'processar',
      datainicial: $F('dtinicial'),
      datafinal  : $F('dtfinal'),
      ano        : $F('ano')
    }

    if (empty(oParam.datainicial) || empty(oParam.datafinal) || empty(oParam.ano)) {
      alert('informe todos os parâmetros');
      return false;
    }
    new AjaxRequest('con4_processaconsitenciasuprimentofundos.RPC.php', oParam, function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.message.urlDecode());
        return false;
      }

      var sMessage = 'Processamento Realizado com sucesso.';
      if (oRetorno.erros.length > 0) {
        sMessage += "\nAlguns empenhos não foram Processados.";
      }
      alert(sMessage);
      $('btnPesquisar').click();
    }).setMessage('Aguarde, processando. <br>Este procedimetno pode demorar alguns minutos.').execute();
  }

  $('btnPesquisar').onclick = function () {
    var oParam = {

      exec       :'pesquisar',
      datainicial: $F('dtinicial'),
      datafinal  : $F('dtfinal'),
      ano        : $F('ano')
    }

    if (empty(oParam.datainicial) || empty(oParam.datafinal) || empty(oParam.ano)) {
      alert('informe todos os parâmetros');
      return false;
    }
    new AjaxRequest('con4_processaconsitenciasuprimentofundos.RPC.php', oParam, function(oRetorno, lErro) {

      oGridEmpenhos.clearAll(true);
      if (lErro) {

        alert(oRetorno.message.urlDecode());
        return false;
      }
      oRetorno.empenhos.each(function(oEmpenho){

        var aCampos = [
          oEmpenho.e60_codemp+"/"+oEmpenho.e60_anousu,
          js_formatar(oEmpenho.empenho_doc_1, 'f'),
          js_formatar(oEmpenho.empenho_doc_410, 'f'),
          js_formatar(oEmpenho.liquidacao_doc_3, 'f'),
          js_formatar(oEmpenho.liquidacao_doc_412, 'f'),
          js_formatar(oEmpenho.pagamento, 'f'),
          js_formatar(oEmpenho.suprimento_fundos, 'f'),
          js_formatar(oEmpenho.prestacao_contas, 'f')
        ];

        oGridEmpenhos.addRow(aCampos);
      });
      oGridEmpenhos.renderRows();
    }).setMessage('Aguarde, pesquisando').execute();
  }

  var oGridEmpenhos          = new DBGrid("gridEmpenhosSup");
  oGridEmpenhos.nameInstance = 'oGridEmpenhos';
  oGridEmpenhos.setCellAlign(['left', 'right',  'right', 'right', 'right', 'right', 'right', 'right', 'right', 'right']);
  oGridEmpenhos.setHeader(['Empenho',
                           'Empenho Doc 1',
                           'Empenho Doc 2',
                           'Empenho Doc 410',
                           'Empenho Doc 412',
                           'Pgto',
                           'Sup. Fundos',
                           'Prest. Contas']);
  oGridEmpenhos.show($('ctnGridEmpenhos'));
</script>