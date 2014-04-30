<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("model/Dotacao.model.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js, widgets/dbtextField.widget.js");
db_app::load("DBViewAcordoPrevisao.classe.js,widgets/dbtextFieldData.widget.js, classes/DBViewAcordoExecucao.classe.js, DBHint.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <br>
  <br>
  <center>
    <table width="70%">
      <tr>
        <td>
         <fieldset>
          <legend><B>Informe o Acordo</B></legend>
          <table width="100%">
              <tr>
                <td>
                  <?
                   db_ancora("<b>Acordo:</b>","js_pesquisaac16_sequencial(true);", 1);
                 ?>
                </td>
                <td>
                   <span id='ctnTxtCodigoAcordo'></span>
                   <span id='ctnTxtDescricaoAcordo'></span>
                </td>
              </tr>
              <tr>
                <td>
                  origem
                </td>
                <td>
                  <input type ='text' id='contratoOrigem' />
                </td>
              </tr>              
              
           </table>
           </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='3'>
          <fieldset>
            <legend><b>Movimentações</b></legend>
            <div id='ctnGridPosicoes'>
            </div>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>
</body>
</html>
<script>

var sUrlRpc = 'con4_contratosmovimentacoesfinanceiras.RPC.php';
/**
 * Pesquisa acordos
 */
function js_pesquisaac16_sequencial(lMostrar) {
   
  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {
  
    if (oTxtCodigoAcordo.getValue() != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+oTxtCodigoAcordo.getValue()+
                 '&funcao_js=parent.js_mostraacordo&iTipoFiltro=4';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       oTxtCodigoAcordo.setValue('');
     }
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {
 
  if (erro == true) {
   
    oTxtCodigoAcordo.setValue('');
    oTxtDescricaoAcordo.setValue('');
    $('oTxtDescricaoAcordo').focus(); 
  } else {
  
    oTxtCodigoAcordo.setValue(chave1);
    oTxtDescricaoAcordo.setValue(chave2);
    js_pesquisarPosicoesContrato();
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  oTxtCodigoAcordo.setValue(chave1);
  oTxtDescricaoAcordo.setValue(chave2);
  db_iframe_acordo.hide();
  js_pesquisarPosicoesContrato();
}
function js_main() {

   oTxtCodigoAcordo = new DBTextField('oTxtCodigoAcordo', 'oTxtCodigoAcordo','', 10);
   oTxtCodigoAcordo.addEvent("onChange",";js_pesquisaac16_sequencial(false);");
   oTxtCodigoAcordo.show($('ctnTxtCodigoAcordo'));
   
   oTxtDescricaoAcordo = new DBTextField('oTxtDescricaoAcordo', 'oTxtDescricaoAcordo','', 80);
   oTxtDescricaoAcordo.show($('ctnTxtDescricaoAcordo'));
   oTxtDescricaoAcordo.setReadOnly(true);
   
   oGridPosicoes = new DBGrid('oGridPosicoes');
   oGridPosicoes.setHeader(new Array('Cod', 'Aditamento', 'Tipo', "data", "Emergencial"));
   oGridPosicoes.setHeight(100);
   oGridPosicoes.aHeaders[0].lDisplayed = false;
   oGridPosicoes.show($('ctnGridPosicoes'));
   
   //$('btnPesquisarPosicoes').onclick = js_pesquisarPosicoesContrato;
}

function js_pesquisarPosicoesContrato() {

  if (oTxtCodigoAcordo.getValue() == "") {
    
    alert('Informe um acordo!');
    return false;
  } 
  var oParam     = new Object();
  oParam.exec    = 'getPosicoesAcordo';
  oParam.iAcordo = oTxtCodigoAcordo.getValue();
  var oAjax      = new Ajax.Request(sUrlRpc,
                                    {method:'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: js_retornoGetPosicoesAcordo
                                    } 
                                   )
}

function js_retornoGetPosicoesAcordo(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  oGridPosicoes.clearAll(true);
  if (oRetorno.status == 1) {
    $('contratoOrigem').value = oRetorno.tipocontrato;
    oRetorno.posicoes.each(function (oPosicao, iLinha) {
    
      var aLinha = new Array();
      aLinha[0]  = oPosicao.codigo;
      //aLinha[1]  = oPosicao.numerocontrato;
      aLinha[1]  = oPosicao.numero;
      aLinha[2]  = oPosicao.descricaotipo.urlDecode();
      aLinha[3]  = oPosicao.data;
      aLinha[4]  = oPosicao.emergencial.urlDecode();

      oGridPosicoes.addRow(aLinha);
      oGridPosicoes.aRows[iLinha].sEvents='ondblclick="js_openPrevisao('+oPosicao.codigo+')"';
    });
      
    oGridPosicoes.renderRows();
    if (oRetorno.posicoes.length == 1) {
      js_openPrevisao(oRetorno.posicoes[0].codigo);
    }
  }
}

function js_openPrevisao(iPosicao) { 
  
  oPrevisao    = new DBViewAcordoPrevisao(iPosicao, 'oPrevisao', 'Execução do Contrato', true, true);
  oPrevisao.onPeriodoClick = function (iPeriodo, iItem) {
    oExecucao = new DBViewAcordoExecucao(oPrevisao.aItens[iItem], iPeriodo, 'oExecucao', oPrevisao.wndAcordoPrevisao);
    oExecucao.show();
    oExecucao.onAfterSave = function(oPeriodo) {

       if ($('quantidade'+oPeriodo.codigo)) {

         if (oPrevisao.lMostrarLegenda) {
           
           if (oPeriodo.saldo == 0) {
           
             $('quantidade'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorExecutado;
             $('valortotal'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorExecutado;
           } else if (oPeriodo.quantidade == oPeriodo.saldo) {
           
             $('quantidade'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorNaoExecutado;
             $('valortotal'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorNaoExecutado;
           } else {
           
             $('quantidade'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorExecutadoParcialmente;
             $('valortotal'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorExecutadoParcialmente;
           }
         } else {
         
           $('quantidade'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorNaoExecutado;
           $('valortotal'+oPeriodo.codigo).style.backgroundColor = oPrevisao.sColorNaoExecutado;
         }
       }
       oPrevisao.wndAcordoPrevisao.destroy();
       js_openPrevisao(iPosicao);
    }
  }
  
  oPrevisao.show();
  oPrevisao.setAjuda('Clique no período que Deseja realizar a execução');
}
js_main();
</script>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>