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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$oRotulo = new rotulo('farcomprovantetermicaconfig');
$oRotulo->label("fa57_coddepto");
$oRotulo->label("fa57_mensagem");
$oRotuloCampo = new rotulocampo("descrdepto");
?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  fieldset .fieldsetSeparator {
   
     border:0px;
     border-top:2px groove white;
     
   }
   fieldset .fieldsetSeparator select {
     width:100%;
     
   }
   fieldset .fieldsetSeparator table tr td:first-child {
      width: 250px;
      white-space: nowrap; 
   }
  </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin:0px; margin-top: 25px'>
    <center>
    <div style='display: table'>
      <fieldset>
        <legend><b>Configuração de Mensagem</b></legend>
        <table>
          <tr>
            <td>
              <?=@$Lfa57_coddepto?>             
            </td>
            <td>
               <?
                 $fa57_coddepto = db_getsession("DB_coddepto");
                 $descrdepto    = db_getsession("DB_nomedepto");
                 db_input("fa57_coddepto", 10, $Ifa57_coddepto, true, 'text');
                 db_input("descrdepto", 40, '', true, 'text');
               ?>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <fieldset>
                <legend>
                  <b><?=@str_replace(":","",$Lfa57_mensagem)?> 
                </legend>
                <?
                 db_textarea('fa57_mensagem', 5, 10, $Lfa57_mensagem, true, "text", 1); 
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
    </div>
    <input type="button" value='Salvar' id='btnSalvar'>
    </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), 
        db_getsession("DB_instit")
       );
?>
<script>
$('fa57_mensagem').style.width = '100%';
var sUrlRpc = 'far4_mensagemComprovante.RPC.php';
function js_init() {

  var oParameters           = new Object();
  oParameters.exec          = 'getMensagemDepartamento';
  oParameters.iDepartamento = $F('fa57_coddepto');
  
  var oAjax                 = new Ajax.Request(sUrlRpc,
                                              {
                                               method: 'post',
                                               parameters: 'json='+Object.toJSON(oParameters),
                                               onComplete: function (oResponse) {
                                                 
                                                 var oRetorno = eval("("+oResponse.responseText+")"); 
                                                 if (oRetorno.status == 1) {
                                                   $('fa57_mensagem').value = oRetorno.mensagemcomprovante.urlDecode();
                                                 }
                                               }
                                              }); 
}


function js_salvarMensagem() {

  var oParameters           = new Object();
  oParameters.exec          = 'salvarMensagemDepartamento';
  oParameters.iDepartamento = $F('fa57_coddepto');
  oParameters.sMensagem     = encodeURIComponent(tagString($F('fa57_mensagem')));
  var oAjax                 = new Ajax.Request(sUrlRpc,
                                              {
                                               method: 'post',
                                               parameters: 'json='+Object.toJSON(oParameters),
                                               onComplete: function (oResponse) {
                                                 
                                                 var oRetorno = eval("("+oResponse.responseText+")"); 
                                                 if (oRetorno.status == 1) {
                                                   
                                                   alert('Mensagem salva com sucesso');
                                                 } else {
                                                   alert('Erro ao salvar mensagem');
                                                 }
                                               }
                                              }); 
}
js_init();
$('btnSalvar').observe("click", js_salvarMensagem);
</script>