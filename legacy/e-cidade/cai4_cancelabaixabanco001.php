<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, strings.js, md5.js, prototype.js, estilos.css, widgets/dbautocomplete.widget.js");
?>
</head>
<body class="body-default" onload="js_tipoprocessamento()">
 <div class="container">
<?php

$cldb_config = new cl_db_config;
$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"db21_codcli"));
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

$cldb_usuarios = new cl_db_usuarios;
$rsUsuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession('DB_id_usuario'),"administrador"));
$oUsuarios  = db_utils::fieldsMemory($rsUsuarios,0);

if (db_getsession("DB_id_usuario") == 1 || ( $oConfig->db21_codcli == 19985 && $oUsuarios->administrador == 1 ) ) {

?>
  <form name="form1" method="POST">
  <fieldset>
    <legend>Cancela Baixa Banco</legend>

       <table class="form-container">
         <tr>
           <td width="192"><label id="lbl_tipo" for="tipo">Tipo de cancelamento:</label></td>
           <td>
            <?php

           	 $aTipos = array("1" => "Excluir arquivo de retorno", "2" => "Cancelar classificação", "3" => "Excluir Autenticação");
             db_select("tipo", $aTipos, true, 1, "onchange=js_tipoprocessamento()");
            ?>
           </td>
         </tr>

         <tr id="div_codret">
           <td><label id="lbl_codret" for="codret">Código de Retorno (Codret):</label></td>
           <td> <?php db_input('codret',5,1,true,"text",1); ?> </td>
         </tr>

         <tr id="div_codcla">
           <td><label id="lbl_codcla" for="codcla">Código de Classificação (Codcla):</label></td>
           <td><?php db_input('codcla',5,1,true,"text",1); ?> </td>
         </tr>

       </table>

     </fieldset>

     <input type="button" name="processar" value="Processar" onclick="js_valida()"/>

   </form>
  </div>
<?php

 } else {
   db_msgbox("Procedimento não disponível!");
 }
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

 var sUrlRPC = "cai4_cancelabaixabancoRPC.php";
 $('div_codret').addClassName('hide');
 $('div_codcla').addClassName('hide');

 function js_tipoprocessamento(){

   if ( $F('tipo') == 1 ) {

     $('div_codcla').addClassName('hide');
     $('div_codret').removeClassName('hide');
     $('codcla').value = '';
   } else {

     $('div_codret').addClassName('hide');
     $('div_codcla').removeClassName('hide');
     $('codret').value = '';
   }
 }

 function js_valida() {

   if( $F('tipo') == 1 ) {

     sMsg = "Confirmar a exclusão do arquivo (codret) "+$F('codret')+" e todas as suas classificações?";
     if ( $F('codret') == ""){

      alert("Informe o código de retorno do Arquivo");
      return false;
     }

   } else if ( $F('tipo') == 2 ) {

     sMsg = "Confirmar a exclusão da classificação (codcla) "+$F('codcla')+"?";;
     if ( $F('codcla') == ""){

      alert("Informe o código de classificação do Arquivo");
      return false;
     }

   } else if ( $F('tipo') == 3 ) {

     sMsg = "Confirma a exclusão da autenticação da classificação (codcla) "+$F('codcla')+"?";;
     if ( $F('codcla') == "" ){

      alert("Informe o código de classificação do Arquivo");
      return false;
     }
   }

   if (confirm("Este procedimento não poderá ser revertido após processado!\nSe estiver ciente dos impactos da confirmação dessa operação e se os dados informados estão corretos clique em 'OK'!")) {
     if (confirm(sMsg)) {

        var oRequisicao                    = new Object();
            oRequisicao.exec               = "validaProcessamento";
            oRequisicao.iTipoProcessamento = $F('tipo');
            oRequisicao.codret             = $F('codret');
            oRequisicao.codcla             = $F('codcla');
            js_divCarregando("Verificando dados, aguarde ","msgBox");
            var sJson = js_objectToJson(oRequisicao);
            var oAjax = new Ajax.Request( sUrlRPC,
                                          {
                                            method    : 'post',
                                            parameters: 'json='+sJson,
                                            onComplete: js_retornoValida
                                          }
                                        );

     } else {
       return false;
     }
   } else {
     return false;
   }
 }

 function js_retornoValida(oAjax) {

	 js_removeObj("msgBox");

   var oRetorno = eval("("+oAjax.responseText+")");

   if (oRetorno.boletim_processado != "") {

 	   alert("Arquivo Autenticado e com boletim processado!\\nOperação não permitida!");
 	   return false;
   } else if(oRetorno.boletim_liberado != "") {

     alert("Arquivo Autenticado e com boletim liberado!\nPara prosseguir com a operação cancele a liberação do Boletim da Tesouraria.\nData da Liberação: "+js_formatar(oRetorno.boletim_liberado,'d'));
	   return false;
   }

   if (oRetorno.arqsimples != "") {

     if (!confirm("Arquivo de retorno do Simples Nacional!\nDeseja continuar o processamento da operação e cancelar o processamento do arquivo ?")){
       return false;
     }
   }

   if (oRetorno.arqautent != ""){

     if (!confirm("Arquivo Autenticado!\nDeseja continuar o processamento da operação e excluir as autenticações na tesouraria e lançamentos contábeis vinculados a este ?")){
       return false;
     }else{

       if (!confirm("Este procedimento não poderá ser revertido após processado!\nSe estiver ciente dos impactos da confirmação dessa operação e se os dados informados estão corretos clique em 'OK'!")){
         return false;
       }
     }
   }

   if (oRetorno.arquivo_retencao != "" && $F('tipo') == 1) {

     alert("Arquivo de retorno de Retenção!\nNão é possível realizar a operação!");
     return false;
   }

   js_processa();
 }

 function js_processa() {

   var oRequisicao                    = new Object();
       oRequisicao.exec               = "Processar";
       oRequisicao.iTipoProcessamento = $F('tipo');
       oRequisicao.codret             = $F('codret');
       oRequisicao.codcla             = $F('codcla');

   var sJson = js_objectToJson(oRequisicao);
   js_divCarregando("Processando, aguarde ","msgBox");
   var oAjax = new Ajax.Request( sUrlRPC,
                                          {
                                            method    : 'post',
                                            parameters: 'json='+sJson,
                                            onComplete: js_retornoProcessamento
                                          }
                                        );
 }

 function js_retornoProcessamento(oAjax) {

   js_removeObj("msgBox");
   var oRetorno = eval("("+oAjax.responseText+")");
   alert(oRetorno.message.urlDecode());
 }
</script>