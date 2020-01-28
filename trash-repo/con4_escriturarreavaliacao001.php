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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oRotuloInventario = new rotulo("inventario");
$oRotuloInventario->label();
$oGet = db_utils::postMemory($_GET);
$sLegendReavaliacao = "Escriturar";
$sFuncaoPesquisaInventario = "js_pesquisaInventario";
if (isset($oGet->estornar) && $oGet->estornar == "true") {
  
  $sLegendReavaliacao        = "Estornar Escrituração";
  $sFuncaoPesquisaInventario = "js_pesquisaInventarioProcessado";
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?php 
      db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
      db_app::load("estilos.css, grid.style.css");
      
      db_app::load("widgets/windowAux.widget.js");
      db_app::load("widgets/dbmessageBoard.widget.js");
      db_app::load("classes/DBViewEscrituracaoInventario.classe.js");
    ?>
  </head>
  <body bgcolor="#cccccc" style='margin-top: 30px'>
    <form name='form1'>
      <center>
        <fieldset style="width: 300px;">
          <legend><b><?php echo $sLegendReavaliacao;?> Reavaliação</b></legend>
          <table>
            <tr>
              <td nowrap="nowrap">
                <?php 
                  db_ancora("<b>{$Lt75_sequencial}</b>", "{$sFuncaoPesquisaInventario}(true);", 1);
                ?>
              </td>
              <td>
                <?php 
                  db_input("t75_sequencial", 10, $It75_sequencial, true, 'text', 1, "onchange='{$sFuncaoPesquisaInventario}(false);'");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <br>
        <input type="button" name="btnBuscarDadosInventario" id="btnBuscarDadosInventario" value="Exibir"/>
      </center>
    </form>
    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
  </body>
</html>

<script type="text/javascript">

var oViewInventario  = null;
var oGet = js_urlToObject();

$('btnBuscarDadosInventario').observe("click", function () {

  if ($F("t75_sequencial") == "") {

    alert('Você deve selecionar um inventário.')
    return false;
  }
  oViewInventario =  new DBViewEscrituracaoInventario("oViewInventario", $F('t75_sequencial'), oGet.estornar);
  oViewInventario.show();
});

function js_pesquisaInventarioProcessado(lMostra) {

  var sUrlOpen = "func_escriturainventario.php?estornado=false&lPesquisaInventario=true&pesquisa_chave="+$F("t75_sequencial")+"&funcao_js=parent.js_completaInventario";
  if (lMostra) {
    sUrlOpen = "func_escriturainventario.php?estornado=false&funcao_js=parent.js_preencheInventario|c88_inventario";
  }
  js_OpenJanelaIframe('', 'db_iframe_escriturainventario', sUrlOpen, "Pesquisa Inventário Processado", lMostra);
}

function js_pesquisaInventario(lMostra) {

  var sUrlOpen = "func_inventario002.php?situacao=3&pesquisa_chave="+$F("t75_sequencial")+"&funcao_js=parent.js_completaInventario";
  if (lMostra) {
    sUrlOpen = "func_inventario002.php?situacao=3&funcao_js=parent.js_preencheInventario|t75_sequencial";
  }

  js_OpenJanelaIframe('', 'db_iframe_inventario', sUrlOpen, "Pesquisa Inventário", lMostra);
}

function js_completaInventario(iInventario, lErro) {

  if (lErro) {

    $("t75_sequencial").value = '';
    return false;
  }  
}

function js_preencheInventario(iInventario) {

  $("t75_sequencial").value = iInventario;
  
  if (oGet.estornar == 'false') {
    db_iframe_inventario.hide();
  } else {
    db_iframe_escriturainventario.hide();
  }

}

js_validarIntegracaoContabilidade();

function js_validarIntegracaoContabilidade() {

  var sRpc = "con4_contabilizacaoReavaliacao.RPC.php";
  var oParam = new Object();

  oParam.exec = "validarIntegracaoContabilidade";  
  js_divCarregando('Aguarde, validando integração com Contabilidade','msgBox');

  var oAjax  = new Ajax.Request (
    sRpc,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam), 
      onComplete: function(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("("+oAjax.responseText+")");
        
        /**  
         * Integração com contabilidade habilidade
         */
        if (oRetorno.iStatus == 1) {
          return false;
        }

        $('btnBuscarDadosInventario').disabled = true;        
        alert(oRetorno.sMessage.urlDecode());
      }
   });
     
}
</script>