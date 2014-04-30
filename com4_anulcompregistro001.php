<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_app.utils.php");
require ("std/db_stdClass.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc67_motivo");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  </table>
  <center>
    <table style="margin-top: 20px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Anulação Compilação RP</b>
            </legend>
            <table>
            <tr>
              <td nowrap title="Abertura" width="70">
                 <? 
                  db_ancora("<b>Compilação:</b>","js_pesquisar();",1);
                 ?>
              </td>
              <td>  
                <?
                db_input('pc10_numero',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
              <fieldset>
                <legend><b>Motivo</b></legend>
                <?
                $pc67_motivo = "";
                db_textarea("pc67_motivo",10,50,"",true,"text",1,"","","",""); 
                ?>
              </fieldset>  
              </td>
            </tr>
          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <input type='button' value='Confirmar' onclick="js_confirma();">
        </td>
      </tr>
    </table>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';

function js_confirma() {

   if ($F('pc10_numero').trim() == "") {
      alert("usuário:\n\nCompilação a ser anulada não selecionada !\n\n");
      return false;
   }
   if ($F('pc67_motivo').trim() == "") {
     $('pc67_motivo').focus();
     alert("usuário:\n\nMotivo não informado !\n\n");
     return false;
   }
  
   var msgDiv = "Aguarde anulando compilação RP ...";
   js_divCarregando(msgDiv,'msgBox'); 

   var oParam                = new Object();
   oParam.exec               = "anularCompilacaoRegistroPreco";
   oParam.iCodigoSolicitacao = $F('pc10_numero');
   oParam.sMotivo            = tagString($F('pc67_motivo'));
   
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoAnula
                                         });
}

function js_retornoAnula(oAjax) {

  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
  
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));  
  } else {
    
    alert ("usuário:\n\n Anulação Compilação RP efetuada com sucesso !\n\n");
    js_limpar();
  }
  
}

function js_limpar() {

  $('pc10_numero').value = '';
  $('pc67_motivo').value = '';
  
}


function js_pesquisar() {

  js_OpenJanelaIframe('',
                      'db_iframe_solicita',
                      'func_solicitacompilacao.php?funcao_js=parent.js_mostraPesquisa|pc10_numero&departamento=true&'+
                      'anuladas=1&comcompilacaoprocessada=1',
                      'Estimativa de Registro de Preço',
                      true
                      );
}


function js_mostraPesquisa(chave1,chave2){
  
   $('pc10_numero').value = chave1;
   db_iframe_solicita.hide();
}

function js_grvDetalhes() {
  alert("aqui");
  /*
  oGrvDetalhes = new DBGrid('detalhes');
  oGrvDetalhes.nameInstance = 'oGrvDetalhes';
  oGrvDetalhes.setCellWidth(new Array('10%','10%',
                                          '20%',
                                          '30%',
                                          '30%',
                                          '30%', 
                                          '30%',
                                          '30%',
                                          '30%',
                                          '15%'))
  oGrvDetalhes.setHeader(new Array('Cep','Número',
                                        'Complemento',
                                        'Loteamento',
                                        'Condomínio',
                                        'SeqLocal', 
                                        'SeqEnder',
                                        'PontoRef','SeqRuasTipo','RuaTipo'));
  oGrvDetalhes.setHeight(200);
  oGrvDetalhes.show($('GridRua'+me.sId));
  oGrvDetalhes.showColumn(false,5);
  oGrvDetalhes.showColumn(false,6);
  oGrvDetalhes.showColumn(false,7);
  oGrvDetalhes.showColumn(false,8);
  oGrvDetalhes.showColumn(false,9);
    var iNumDados = aDados.length;
        
  oGrvDetalhes.clearAll(true);
  oGrvDetalhes.renderRows();
  */
}
</script>