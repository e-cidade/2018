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
require_once("classes/db_ppaversao_classe.php");

$oRotuloPPAVersao = new rotulo("ppaversao");
$oRotuloPPAVersao->label();
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>

  <body bgcolor="#CCCCCC" style="margin-top: 50px" >
    <center>
      <form id="form1" name="form1">
        <div id="ctnFormularioRegra">
        <div  style="width: 300; float:center; top:100px">

        <fieldset style=" height:110;width:300px;">
          <legend><b>Alteração de Status de Ativação da Perspectiva </b></legend>
          <table border="0">

            <!-- Id da -->
            <tr>
              <td nowrap title="<?=@$To119_versao?>">
                <b> Atual:</b>
              </td>
              <td>
               <?
                db_input('o119_sequencial', 10, $Io119_versao, true, 'hidden', 3, "");
                db_input('o119_versao', 10, $Io119_versao, true, 'text', 3, "");
               ?>
              </td>
            </tr>

            <!-- Data Inicio-->
            <tr>
              <td nowrap title="<?=@$To119_datainicio?>">
                <?=@$Lo119_datainicio?>
              </td>
              <td>
                <?
                db_inputdata('o119_datainicio', null, null, null, true, 'text', 3, "");
                ?>
              </td>
            </tr>

            <!-- Data Fim-->
            <tr>
              <td nowrap title="<?=@$To119_datatermino?>">
                <?=@$Lo119_datatermino?>
              </td>
              <td>
                <?
                db_inputdata('o119_datatermino', null, null, null, true, 'text', 3, "");
                ?>
              </td>
            </tr>

            <!-- Ativo/ Inativo -->
            <tr>
              <td nowrap title="<?=@$To119_ativo?>">
                <?=@$Lo119_ativo?>
              </td>
              <td>
              <?
              db_inputdata('o119_ativo', null, null, null, true, 'text', 3, "");
              ?>
              </td>
            </tr>

          </table>
        </fieldset>

        <!-- Botão para salvar status da perspectiva -->
        <input
          type    = "button"
          value   = 'Ativar/Desativar'
          onclick = 'js_alteraStatus()'
        />

        <!-- Botão para pesquisar Versões -->
        <input
          type    ="button"
          value   ='Pesquisar '
          onclick ='js_pesquisaVersoes()'
        />

      </form>
    </center>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script language="javascript">

var sUrlRPC = "orc4_ppaRPC.php";

/**
 * Pesquisa a perspectiva
 */
function js_pesquisaVersoes() {

  js_OpenJanelaIframe('',
                      'db_iframe_ppaversao',
                      'func_ppaversao.php?funcao_js=parent.js_mostraperspectiva|o119_sequencial|o119_ppalei',
                      'Perspectivas do  PPA',
                       true);
}

js_pesquisaVersoes();
/**
 * Mostra a  e sua versão
 */
function js_mostraperspectiva(iSequencial,iLei) {

   var oParam           = new Object();
   oParam.iCodigoLei    = iLei;
   oParam.iCodigoVersao = iSequencial;
   oParam.iTipo         = 0;
   oParam.exec          = "getDadosVersao";
   var oAjax = new Ajax.Request (
                                 sUrlRPC,
                                 {
                                  method    : 'post',
                                  parameters: 'json='+js_objectToJson(oParam),
                                  onComplete: js_retornoMostraPerspectiva
                                 }
                                );
}


function js_retornoMostraPerspectiva(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    var aInput = $$('input[type=text]');

    aInput.each(function(oInput,id) {

       var sValor   = eval("oRetorno."+oInput.id);
       oInput.value = sValor;
     });
  }

  $('o119_ativo').value      = oRetorno.o119_ativo.urlDecode();
  $('o119_sequencial').value = oRetorno.o119_sequencial;
  db_iframe_ppaversao.hide();
}


function js_alteraStatus() {

  if (!confirm("Deseja alterar o status da Perspectiva?")) {
    return false;
  }

  var oParam           = new Object();
  oParam.iSequencial   = $F('o119_sequencial');
  oParam.exec          = "alterarStatusAtivacaoPerspectiva";

  var oAjax = new Ajax.Request (
                                sUrlRPC,
                                {
                                  method    : 'post',
                                  parameters: 'json='+js_objectToJson(oParam),
                                  onComplete: js_retornoMostraAlteracaoStatus
                                }
                              );
}


function js_retornoMostraAlteracaoStatus(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    $('o119_ativo').value = oRetorno.sAtivo.urlDecode();
    alert("Status de ativação modificado com sucesso.");
  }
}

</script>