<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("std/db_stdClass.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_acordodocumento_classe.php");
require_once("classes/db_acordo_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet              = db_utils::postMemory($_GET);
$clAcordoDocumento = new cl_acordodocumento;
$clAcordo          = new cl_acordo;
$db_opcao          = 22;
$db_botao          = false;
$clRotulo          = new rotulocampo;

$clAcordoDocumento->rotulo->label();
$clRotulo->label('ac40_acordo');
$clRotulo->label('ac16_resumoobjeto');
$clRotulo->label('ac16_sequencial');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js");
      db_app::load("widgets/dbtextField.widget.js, dbViewCadEndereco.classe.js");
      db_app::load("dbmessageBoard.widget.js, dbautocomplete.widget.js,dbcomboBox.widget.js, datagrid.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  
    <div style="margin-top: 30px;"></div>
    <form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
      <center>
        <div style="width: 600px;">
          <fieldset>
            <legend><b>Adicionar Documento:</b></legend>
            <table>
              <tr>
                <td title="<?php echo $Tac16_sequencial; ?>">
                  <?php db_ancora($Lac16_sequencial, 'js_pesquisaContrato(true);', 1)?>
                </td>
                <td nowrap="nowrap">
                  <?php
                    db_input('iCodigoContrato', 9, @$Iac16_sequencial, true, 'text', 1, " onchange='js_pesquisaContrato(false);' ");
                    db_input('sDescricaoContrato', 42, @$Iac16_resumoobjeto, true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              <tr>
                <td valign="top">
                  <b>Documento: </b>
                </td>
                <td valign='top' style="height: 25px;">
                  <?php
                    db_input("uploadfile", 30, 0, true, "file", 1, "disabled style='background-color:#DEB887;'");
                    db_input("namefile", 30, 0, true, "hidden", 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $Lac40_descricao;?>
                </td>
                <td>
                  <?php
                    db_input("sDescricaoDocumento", 55, 0, true, 'text', 1, "disabled style='background-color:#DEB887;'");
                    db_input("ac40_acordo", 30, 0, true, 'hidden', 3);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </div>
        <input type='button' id='btnSalvar' Value='Salvar' disabled/>
        <div style="width: 600px;">
          <fieldset>
            <legend><b>Documentos Cadastrados</b></legend>
            <div id='ctnDbGridDocumentos'>
            </div>
          </fieldset>
        </div>
      </center>
    </form>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?> 
  </body>
  <div id='teste' style='display:none'></div>
</html>

<script type="text/javascript">

var iAcordo = null;

/**
 * Funções para busca de departamentos
 */
function js_pesquisaContrato(lMostra) {

  var sFuncao = 'func_acordoinstit.php?funcao_js=parent.js_mostraContrato|ac16_sequencial|ac16_resumoobjeto';

  if (lMostra == false) {
  
    var iContrato = $F('iCodigoContrato');
    sFuncao = 'func_acordoinstit.php?pesquisa_chave='+iContrato+'&funcao_js=parent.js_completaContrato'+
              '&descricao=true';
  } 

  js_OpenJanelaIframe('', 'db_iframe_contrato', sFuncao,'Pesquisar Contrato', lMostra, '25');
}

function js_completaContrato(iContrato, sDescricao, lErro) {

  $('sDescricaoContrato').value = sDescricao;

  if (lErro) {

    $('iCodigoContrato').focus();
    $('iCodigoContrato').value = '';
    iAcordo = null;
    js_controlaCampos(true);
    oGridDocumento.clearAll(true);
    return false;
  }

  js_controlaCampos(false);
  iAcordo = $F("iCodigoContrato");
  js_getDocumento();
}

function js_mostraContrato (iCodigo, sDescricao) {

  $('iCodigoContrato').value = iCodigo;
  $('sDescricaoContrato').value = sDescricao;
  db_iframe_contrato.hide();
  js_controlaCampos(false);
  iAcordo = iCodigo;
  js_getDocumento();
}

function js_controlaCampos(lBloqueia) {
  
  if (lBloqueia) {

    $("uploadfile").setAttribute("disabled", true);
    $("uploadfile").style.backgroundColor = "#DEB887";

    $("sDescricaoDocumento").setAttribute("disabled", true);
    $("sDescricaoDocumento").style.backgroundColor = "#DEB887";

    $("btnSalvar").setAttribute("disabled", true);

  } else {

    $("uploadfile").removeAttribute("disabled");
    $("uploadfile").style.backgroundColor = "#FFFFFF";

    $("sDescricaoDocumento").style.backgroundColor = "#FFFFFF";  
    $("sDescricaoDocumento").removeAttribute("disabled");

    $("btnSalvar").removeAttribute("disabled");
  }
}

var sUrlRpc = "con4_contratos.RPC.php";

oGridDocumento     = new DBGrid('gridDocumento');
oGridDocumento.nameInstance = "oGridDocumento";
oGridDocumento.setHeight(200);
oGridDocumento.setCellAlign(new Array("right","right","left","center", "center"));
oGridDocumento.setHeader(new Array("Codigo","Acordo","Descricao","Download", "Ação"));
oGridDocumento.show($('ctnDbGridDocumentos'));


/**
 * Cria um listener para subir a imagem, e criar um preview da mesma
 */
$("uploadfile").observe('change', function() {

  startLoading();  
  var iFrame = document.createElement("iframe");
  iFrame.src = 'func_uploadfiledocumento.php?clone=form1';
  iFrame.id  = 'uploadIframe';
  $('teste').appendChild(iFrame);

});

function startLoading() {
  js_divCarregando('Aguarde... Carregando Documento','msgbox');
}

function endLoading() {
  js_removeObj('msgbox');
}

function js_salvarDocumento() {

  if ($F('namefile') == '') {

     alert('Escolha um Documento!');
     return false;
  }

  if ($F('sDescricaoDocumento') == '') {

    alert('Informe uma descrição para o documento!');
    return false;
  }

  var oParam       = new Object();
  oParam.exec      = 'adicionarDocumento';
  oParam.acordo    = $F('iCodigoContrato');
  oParam.descricao = encodeURIComponent(($F('sDescricaoDocumento').replace(/\\/g,  "<contrabarra>")));
  oParam.arquivo   = $F('namefile');
  js_divCarregando('Aguarde... Salvando Documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               asynchronous:false,
                               onComplete : js_retornoSalvarFoto
                             });    
}

function js_retornoSalvarFoto(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval('('+oAjax.responseText+")");
  if (oRetorno.status == 1) {

     $('uploadfile').value     = '';
     $("sDescricaoDocumento").value = "";
     js_getDocumento();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_getDocumento() {

  if (iAcordo == null) {
    return false;
  }

  var oParam       = new Object();
  oParam.exec      = 'getDocumento';
  oParam.acordo   = iAcordo;
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { parameters: 'json='+Object.toJSON(oParam),
                               asynchronous:false,
                               method: 'post',
                               onComplete : js_retornoGetDocumento
                             });   
}

function js_retornoGetDocumento(oAjax) {
	
  var oRetorno = eval('('+oAjax.responseText+")");
  oGridDocumento.clearAll(true);

  if (oRetorno.dados.length == 0) {
    return false;
  }

  oRetorno.dados.each(function (oDocumento, iSeq) {

    var aLinha = new Array();
    aLinha[0]  = oDocumento.iCodigo;
    aLinha[1]  = oDocumento.iAcordo;
    aLinha[2]  = decodeURIComponent(oDocumento.sDescricao.replace(/\+/g,  " "));
    aLinha[3]  = '<input type="button" value="Dowload" onclick="js_documentoDownload('+oDocumento.iCodigo+')">';
    aLinha[4]  = '<input type="button" value="E" onclick="js_excluirDocumento('+oDocumento.iCodigo+')">';
    oGridDocumento.addRow(aLinha);  
  });

  oGridDocumento.renderRows();

}
$('btnSalvar').observe("click",js_salvarDocumento);
js_getDocumento(); 

function js_excluirDocumento(iCodigoDocumento) {

  if (!confirm('Confirma a Exclusão do Documento?')) {
    return false;
  }  
  var oParam             = new Object();
  oParam.exec            = 'excluirDocumento';
  oParam.acordo          = iAcordo;
  oParam.codigoDocumento = iCodigoDocumento;
  js_divCarregando('Aguarde... excluindo documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { asynchronous:false,
                               parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               onComplete : js_retornoExcluiDocumento
                             });   

}

function js_retornoExcluiDocumento(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval('('+oAjax.responseText+")");
  if (oRetorno.status == 2) {

     alert("Não foi possivel excluir o documento:\n "+ oRetorno.message);
  }

  js_getDocumento();

}

function js_documentoDownload(iCodigoDocumento) {

  if (!confirm('Deseja realizar o Download do Documento?')) {
    return false;
  }  
  var oParam              = new Object();
  oParam.exec             = 'downloadDocumento';
  oParam.acordo           = iAcordo;
  oParam.iCodigoDocumento = iCodigoDocumento;
  js_divCarregando('Aguarde... realizando Download do documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRpc,
                             { asynchronous:false,
                               parameters: 'json='+Object.toJSON(oParam),
                               method: 'post',
                               onComplete : js_downloadDocumento
                             });   
} 

function js_downloadDocumento(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval('('+oAjax.responseText+")");
  if (oRetorno.status == 2) {
     alert("Não foi possivel carregar o documento:\n "+ oRetorno.message);
  }
  window.open("db_download.php?arquivo="+oRetorno.nomearquivo);
} 

js_pesquisaContrato(true);

</script>
