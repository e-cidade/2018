<?
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

require(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbcomboBox.widget.js,datagrid.widget.js, prototype.maskedinput.js,
               DBTreeView.widget.js,arrays.js, datagrid.widget.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style='margin-top: 25px' >
  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
   <center>
   <div style="display: table;">
   <fieldset><legend><b>Verificar informações do Arquivo - CENSO ESCOLAR</b></legend>
   <table border="0" align="left">
    <tr>
     <td colspan="2">
      <b>Arquivo de exportação gerado pelo sistema:</b>
      <input name="flArquivo" id='flArquivo' type="file" size='50'>
      <input name="nomearquivoservidor" id='nomearquivoservidor' type="hidden" size='10'>
     </td>
    </tr>
   </table>
   </fieldset>
   </div>
   <input name="processar" type="button" id="arquivo" value="Processar" onclick="js_processar()">

   </center>
</form>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:500px;
            text-align: left;
            padding:3px;
            z-index:1000000;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<div id='uploadIframeBox' style="display: none;"></div>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
$('flArquivo').observe('change', function() {
   js_criarIframeBox('flArquivo', 'nomearquivoservidor');
});
function retornoUploadArquivo(sArquivo) {
  $('nomearquivoservidor').value = sArquivo;
}
function js_criarIframeBox(sIdCampo, sCampoRetorno) {

  js_divCarregando('Aguarde... carregando arquivo...', 'msgbox');

  var iFrame      = document.createElement("iframe");
  var sParametros = "clone=form1&idcampo="+sIdCampo+"&function=retornoUploadArquivo&camporetorno="+sCampoRetorno;
  iFrame.src      = "func_iframeupload.php?"+sParametros;
  iFrame.id       = 'uploadIframe';
  iFrame.width    = '100%';

  $('uploadIframeBox').appendChild(iFrame);
}
function js_endloading() {

  js_removeObj('msgbox');
  $('uploadIframeBox').removeChild($('uploadIframe'));
  js_processar();
}
windowGrupo = '';
function js_processar() {

    oOlderNode = '';
    var iTamWidth          = screen.availWidth  - 50;

    /**
     *  Configurações do componente WindowAux
     */
    windowGrupo = new windowAux("winVerificarArquivo", "Validação dos Dados do Arquivo", iTamWidth);
    var sContent  = "<div id='divEscolheGrupo' style=''>";
        sContent += "  <div id='divListaGrupo' style='padding: 2px;'>";
        sContent += "    <fieldset id='' style='height:80%'>";
        sContent += "       <div id='ctnlistaDados' style='height:100%; width:48%;float:right;'>";
        sContent += "        <div id='ctnGrid' style='width:99%'></div> ";
        sContent += "       </div>";
        sContent += "       <div id='ctnTreeView' style='width:50%'>";
        sContent += "       </div>";
        sContent += "    </fieldset>";
        sContent += "    </center>";
        sContent += "  </div>";
        sContent += "</div>";

    windowGrupo.setContent(sContent);
    windowGrupo.setShutDownFunction(function() {
      windowGrupo.destroy();
    });

    oTreeViewArquivos = new DBTreeView('treeViewArquivos');
    oTreeViewArquivos.show($('ctnTreeView'));

    oNoPrincipal = oTreeViewArquivos.addNode("0", "Arquivo");
    js_divCarregando('Aguarde... processando...', 'msgbox');
    var oParametro         = new Object();
    oParametro.exec        = 'validarDadosArquivo';
    oParametro.nomearquivo = $F('nomearquivoservidor');

  var oAjax = new Ajax.Request('edu4_censoescolar.RPC.php',
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoProcessamentoArquivo
                               }
                              );


    oTreeViewArquivos.allowFind(true);
    oTreeViewArquivos.setFindOptions('matchedonly');

    /**
     *  Configurações do componente Message Board
     */
    var sIdMsgBoard    = "helpWindowGrupo";
    var sTitleMsgBoard = "Verificação do Arquivo do Censo";
    var sHelpMsgBoard  = "Verificação dos dados gerados pelo sistema, Clique nos itens do ao lado esquerdo ";
    sHelpMsgBoard     += "para verificar os dados do registro. ";
    ajudaWindowArquivo = new DBMessageBoard(sIdMsgBoard, sTitleMsgBoard, sHelpMsgBoard,
                                               windowGrupo.getContentContainer());


}

var oDadosEscola = new Object();
function js_retornoProcessamentoArquivo (oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    windowGrupo.destroy();
    return false;
  }
  ajudaWindowArquivo.setTitle('Verificação do Arquivo do Censo - '+oRetorno.sNomeArquivo.urlDecode());
  windowGrupo.show();
  oDataGridCampos               = new DBGrid('gridCampos');
  oDataGridCampos.sNameInstance = 'oDataGridCampos';
  oDataGridCampos.setHeight($('ctnlistaDados').getHeight() -40);
  oDataGridCampos.setCellWidth(new Array("5%", "45%", "50%"));
  oDataGridCampos.setCellAlign(new Array("right"));
  oDataGridCampos.setHeader(new Array("Seq", "Campo", "Valor"));
  oDataGridCampos.show($('ctnGrid'));
  var aTiposRegistros   = new Array();
  oRegistroEscola       = new Object();
  oRegistroEscola.array = "aEscolas";
  oRegistroEscola.node  = "escola";
  aTiposRegistros.push(oRegistroEscola);

  oRegistroTurma = new Object();
  oRegistroTurma.array = "aTurmas";
  oRegistroTurma.node  = "turma";
  aTiposRegistros.push(oRegistroTurma);

  oRegistroDocente       = new Object();
  oRegistroDocente.array = "aDocentes";
  oRegistroDocente.node  = "docente";
  aTiposRegistros.push(oRegistroDocente);

  oRegistroAluno        = new Object();
  oRegistroAluno.array  = "aAlunos";
  oRegistroAluno.node   = "aluno";
  aTiposRegistros.push(oRegistroAluno);

  oDadosEscola = oRetorno.arquivo;
  oNoEscola    = oTreeViewArquivos.addNode("escola",   "Escolas", '0');
  oNoTurma     = oTreeViewArquivos.addNode("turma",    "Turmas",  '0');
  oNoTurma     = oTreeViewArquivos.addNode("docente",  "Docentes", '0');
  oNoTurma     = oTreeViewArquivos.addNode("aluno",  "Alunos", '0');

  aTiposRegistros.each(function(oRegistro, iSeq) {

    for (var i   = 0; i < oRetorno.arquivo[oRegistro.array].length; i++) {

      with (oRetorno.arquivo[oRegistro.array][i]) {

        var sRetLabel      = nome.urlDecode();
        var sRetParentNode = oRegistro.node;
        var iNivelNode     = oRegistro.node+i;
        oTreeViewArquivos.addNode(iNivelNode,
                                sRetLabel,
                                sRetParentNode
                                );

        for (sIndice in dados) {

          if (typeof(dados[sIndice]) != 'function') {

            var aParams        = new Array();
            aParams['linha']   = sIndice;
            aParams['indice']  = i;
            aParams['nivel']   = oRegistro.array;

            if ( sIndice > 80 ) {
              sIndice = 80;
            }
            var sRetLabel      = 'Registro '+sIndice;
            var sRetParentNode = iNivelNode;
            oTreeViewArquivos.addNode(i+(sRetLabel),
                                     sRetLabel,
                                     sRetParentNode,
                                     false,
                                     '',
                                     '',
                                     function(oNode, event) {

                                       js_showDados(oNode.nivel, oNode.indice,oNode.linha, oNode);
                                     },
                                     aParams

                                    );
          }
        }
      }
    }
  });
}
function js_showDados(sNivel, iIndice, iRegistro, oNode) {

   if (oOlderNode != "") {
    oOlderNode.element.removeClassName('selected');
   }
   oOlderNode = oNode;
   oDataGridCampos.clearAll(true);
   if (oDadosEscola[sNivel]) {
     if (oDadosEscola[sNivel][iIndice].dados[iRegistro]) {

       oDadosEscola[sNivel][iIndice].dados[iRegistro].each(function(aDados, iSeq) {

         var aLinha = new Array(iSeq+1, aDados[0].urlDecode(), aDados[1].urlDecode());
         oDataGridCampos.addRow(aLinha);
          oDataGridCampos.aRows[iSeq].aCells[0].sStyle =';background-color:#DED5CB;font-weight:bold';
         if (aDados[2]  && aDados[2] != "") {

          var sAjuda = aDados[2].urlDecode();
          oDataGridCampos.aRows[iSeq].aCells[1].sEvents  = "onmouseover=\'js_setAjuda(\""+sAjuda+"\",true)\'";
          oDataGridCampos.aRows[iSeq].aCells[1].sEvents += "onmouseOut='js_setAjuda(null,false)'";
         }
       });
     }
     oDataGridCampos.renderRows();
     oNode.element.className += " selected ";
   }
}

function js_setAjuda(sTexto,lShow) {

  if (lShow) {

    el =  $('gridgridCampos');
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y -($('ajudaItem').getHeight() - 10);
   $('ajudaItem').style.left    = x - 510;

  } else {
   $('ajudaItem').style.display = 'none';
  }
}
</script>