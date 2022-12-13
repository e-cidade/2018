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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_solicita_classe.php");

$oDaoSolicita = new cl_solicita;
$oDaoPcProc   = db_utils::getDao("pcproc");
db_postmemory($HTTP_POST_VARS);

$oDaoPcProc->rotulo->label();
$oDaoSolicita->rotulo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("widgets/windowAux.widget.js");
      db_app::load("datagrid.widget.js");
      db_app::load("strings.js");
      db_app::load("grid.style.css");
      db_app::load("estilos.css");
      db_app::load("widgets/dbmessageBoard.widget.js");  
      db_app::load("dbcomboBox.widget.js");   
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px">
    <center>
      <form name="form1" id='frmLiberacaoProcessoCompras' method="post" action="">
        <div style="display: table;">
          <fieldset>
             <legend style="font-weight: bold;">Dados do Processo de Compras</legend>
             <table>
               <tr>
                 <td>
                   <?
                    db_ancora("<b>Código:</b>" , 'js_abrePesquisaProcesso()', 1);
                   ?>
                 </td>
                 <td>
                   <?
                     db_input('pc80_codproc', 10, $Ipc80_codproc, true, 'text', 3);
                   ?>
                 </td>
                 <td style="text-align: right">
                   <span style="font-weight: bold;">Data Emissão:</span>
                 </td>
                 <td style="text-align: right">
                   <?
                     db_input('pc80_data', 10, $Ipc80_data, true, 'text', 3);
                   ?>
                 </td>
               </tr>
               <tr>
                 <td>
                   <b>Departamento:</b>
                 </td>
                 <td colspan="4">
                 
                    <?
                     db_input('pc80_depto', 45, 0, true, 'text', 3);
                   ?>
                 </td>
               </tr>
               <tr>
                 <td>
                   <b>
                     Situação:
                   </b>
                 </td>
                 <td  colspan="4">
                 <?
                   $aSituacoes = array(
                                       2 => "Autorizado", 
                                       1 => "Análise",
                                       3 => "Não Autorizado",
                                       );
                   db_select("pc80_situacao", $aSituacoes, true, 1);                     
                 ?>
               </tr>
             </table>
          </fieldset>
          <center>
             <input type='button' value="Confirmar" id='btnConfirmar'>
             <input type='button' value="Pesquisar" id='btnPesquisar'>
          </center>
        </div>
      </form>
    </center>
  </body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var sUrlRPC = 'com4_processocompras.RPC.php';
function js_pesquisarProcessoCompras() {

  js_OpenJanelaIframe('',
                      'db_iframe_pcproc',
                      'func_pcproc.php?situacao=1&funcao_js=parent.js_getDadosProcesso|pc80_codproc',
                      'Pesquisar Processar de Compras para Liberação', 
                      true);
}

function js_getDadosProcesso(iProcessoCompras) {
  
  var oParam             = new Object();
  oParam.exec            = 'getDadosProcessoCompras';
  oParam.iCodigoProcesso = iProcessoCompras;
  js_divCarregando('Aguarde, carregando dados do Processo de Compras', 'msgBox');
  var oAjax              = new Ajax.Request(sUrlRPC, 
                                            {method:'post',
                                             parameters:'json='+Object.toJSON(oParam),
                                             onComplete:js_preencheDadosProcesso
                                            });
}

function js_preencheDadosProcesso(oAjax) {

  js_removeObj('msgBox');
  db_iframe_pcproc.hide();
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode());
    js_pesquisarProcessoCompras();
  } else {
  
    $('pc80_codproc').value   = oRetorno.iCodigoProcesso;    
    $('pc80_data').value      = oRetorno.dtEmissaoProcesso;    
    $('pc80_depto').value     = oRetorno.iDepartamento+" - "+oRetorno.sDescricaoDepartamento.urlDecode();    
    $('pc80_situacao').value  = oRetorno.iSituacao;    
  }
}

/**
 * Altera a situação do processo de compras
 */
function js_liberarProcessoDeCompras() {
  
  
  if ($F('pc80_codproc') == '') {
  
    alert('Processo de Compras não informado.');
    return false;
  }
  var sTipoProcessamento = ''
  switch($F('pc80_situacao')) {  
    case '1':
        
      sTipoProcessamento  = 'Análise.\nO Processo de Compras não estará apto para o vínculo ';
      sTipoProcessamento += 'de licitaçoes ou para a Geração de Autorizações de Empenho';
      break;
      
    case '2':
      
      sTipoProcessamento  = 'Autorizado.\nO Processo de Compras estará autorizado para o vínculo ';
      sTipoProcessamento += 'com licitações e a Geração de Autorizações.';
      break;  
      
    case '3':
      
      sTipoProcessamento = 'Não Autorizado.\nO Processo de Compras não estará apto para o vínculo ';
      sTipoProcessamento += 'de licitaçoes ou para a Geração de Autorizações de Empenho';
      break;  
  }
  var sMsgPRocessamento  = 'o Processo de Compras '+$F('pc80_codproc')+ ' ficara com a seguinte situação:\n'; 
      sMsgPRocessamento += sTipoProcessamento; 
  if (!confirm(sMsgPRocessamento)) {
    return false;
  }
  var oParam             = new Object();
  oParam.exec            = 'liberarProcessoCompras';
  oParam.iCodigoProcesso = $F('pc80_codproc');
  oParam.iSituacao       = $F('pc80_situacao');
  js_divCarregando('Aguarde, Realizando procedimento', 'msgBox');
  var oAjax              = new Ajax.Request(sUrlRPC, 
                                            {method:'post',
                                             parameters:'json='+Object.toJSON(oParam),
                                             onComplete:js_retornoLiberarProcessoDeCompra
                                            });
}

function js_retornoLiberarProcessoDeCompra(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode());
  } else {
    
    var sTipoProcessamento = '';
    switch($F('pc80_situacao')) {
      
      case '1':
        
        sTipoProcessamento = ' foi colocado em análise.';
        break;
        
      case '2':
        
        sTipoProcessamento = ' está autorizado para os proximos procedimentos.';
        break;  
        
      case '3':
        
        sTipoProcessamento = ' foi colocado como não autorizado. Esse processo de compras não poderá ser mais usado.';
        break;  
    }
    if (confirm('Processo de Compras'+sTipoProcessamento+ '\nDeseja emitir o documento '+$F('pc80_codproc')+'?')){
      jan = window.open('com2_autorizacaoprocessocompras002.php?iCodigoProcesso='+$F('pc80_codproc')+'&iModeloImpressao=10',
                         '',
                         'width='+(screen.availWidth - 5), 
                         'height='+(screen.availHeight-40)+',scrollbars=1, location=0'
                        );
      jan.moveTo(0, 0);
    }
    
  }
}

function js_abrePesquisaProcesso() {
  
  if ($F("pc80_codproc") != "") {
  
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_pesquisa_processo',
                        'com3_pesquisaprocessocompras003.php?pc80_codproc='+$F("pc80_codproc"),
                        'Consulta Processo de Compras',
                        true
                       );
                       
                         
  }
}
$('btnPesquisar').observe('click', js_pesquisarProcessoCompras);
$('btnConfirmar').observe('click', js_liberarProcessoDeCompras);
$('pc80_situacao').style.width = '100%';
$('pc80_codproc').style.width  = '100%';
js_pesquisarProcessoCompras();
</script>