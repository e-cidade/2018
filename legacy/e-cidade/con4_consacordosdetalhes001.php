<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));
require_once(modification("model/Acordo.model.php"));

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?php
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js,
             classes/infoLancamentoContabil.classe.js,messageboard.widget.js, widgets/DBHint.widget.js");
  db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_grvDetalhes();">
<center>
<table width="100%">
  <tr>
    <td>
    <fieldset>
      <div id="grvDetalhes">

      </div>
    </fieldset>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<script type="text/javascript">

var detalhe         = '<?=$oGet->exec; ?>';
var ac16_sequencial = '<?=$oGet->ac16_sequencial; ?>';

var sUrlRC = 'ac4_acordoconsulta.RPC.php';

function js_completaPesquisa(ac16_sequencial,detalhe) {

   var oParam             = new Object();
   oParam.exec            = detalhe+'Consulta';

   if (detalhe == "documentos") {

     oParam.exec = "getDocumento";
     sUrlRC      = "con4_contratos.RPC.php";
   }
   oParam.ac16_sequencial = ac16_sequencial;
   oParam.detalhe         = detalhe;
   var msgDiv = "Aguarde ...";
   js_divCarregando(msgDiv,'msgBox');

   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisa
                                         });
}

function js_retornoCompletaPesquisa(oAjax) {

  var aEventsIn  = ["onmouseover"];
  var aEventsOut = ["onmouseout"];
  aDadosHintGrid = new Array();
  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    oGrvDetalhes.clearAll(true);

    if (oRetorno.dados !== false ) {

      var iNumDados = oRetorno.dados.length;

      if (oRetorno.detalhe == "itens") {
        aItens = oRetorno.dados;
      }
      if (iNumDados > 0) {

        oRetorno.dados.each(
          function (oDado, iInd) {

            switch(oRetorno.detalhe) {

              case 'licitacoes':

                var aRow = new Array();

                aRow[0] = "<b><a href='#' onclick='parent.js_consultaLicitacao("+oDado.iCodigoLicitacao+")' title='Consultar Licitacao'>" + oDado.iCodigoLicitacao + "</a></b>";
                aRow[1] = decodeURIComponent(oDado.sObjetoLicitacao.replace(/\+/g, ""));
                aRow[2] = decodeURIComponent(oDado.sLocalLicitacao.replace(/\+/g, ""));
                aRow[3] = js_formatar(oDado.dtCriacaoLicitacao, "d");
                aRow[4] = oDado.iModalidadeLicitacao + " - " + decodeURIComponent(oDado.sModalidadeLicitacao.replace(/\+/g, ""));

                oGrvDetalhes.addRow(aRow);

                break;

              case 'empenhos':

                var aRow = new Array();

                aRow[0] = "<b><a href='#' onclick='parent.js_consultaEmpenho("+oDado.iNumeroEmpenho+")' title='Consultar Empenho'>" + oDado.iCodigoEmpenho + " / " + oDado.iAnoEmpenho + "</a></b>";
                aRow[1] = decodeURIComponent(oDado.sResumoEmpenho.replace(/\+/g, ""));
                aRow[2] = oDado.iCaracteristicaPeculiar;
                aRow[3] = js_formatar(oDado.iValorEmpenho, "f");
                aRow[4] = js_formatar(oDado.dtEmissaoEmpenho, "d");

                oGrvDetalhes.addRow(aRow);

                break;

              case "processodecompras":

                var aRow = new Array();

                aRow[0] = "<b><a href='#' onclick='parent.js_consultaProcessoCompras(" + oDado.iCodigoProcesso + ")' title='Consultar Empenho'>" + oDado.iCodigoProcesso + "</a></b>";
                aRow[1] = decodeURIComponent(oDado.sResumoProcesso.replace(/\+/g, ""));
                aRow[2] = oDado.dtEmissaoProcesso;
                aRow[3] = oDado.iCodigoDepartamento + decodeURIComponent(oDado.sDescricaoDepartamento.replace(/\+/g, ""));

                oGrvDetalhes.addRow(aRow);

                break;



               case 'itens':

                  var aRow = new Array();

                  aRow[0] = oDado.ordem;
                  aRow[1] = oDado.codigo;
                  aRow[2] = oDado.tipo == '' ? '1 - Inclusão' : oDado.tipo+' - '+oDado.aditamento.urlDecode();
                  aRow[3] = oDado.descricao.urlDecode();
                  aRow[4] = oDado.quantidade;
                  aRow[5] = oDado.unidade;
                  aRow[6] = oDado.vlrUnit;
                  aRow[7] = oDado.vlrTotal;
                  oGrvDetalhes.addRow(aRow);

                  var sTextEvent  = "<b>Unidade: </b>"+oDado.unidademed+"</br>";
                      sTextEvent += "<b>Elemento: </b>"+oDado.elemento+"</br>";

                  if (oDado.observacao !== '') {
                    sTextEvent += "<b>Resumo: </b>"+oDado.observacao.urlDecode();
                  } else {
                    sTextEvent += "<b>Resumo: </b> Sem observação";
                  }

                  var oDadosHint           = new Object();
                      oDadosHint.idLinha   = oGrvDetalhes.aRows[iInd].sId;
                      oDadosHint.sText     = sTextEvent;
                      aDadosHintGrid.push(oDadosHint);

               break;

               case 'empenhamentos':
                  var aRow = new Array();

                  aRow[0] = '<a href="#" onclick="parent.js_detalhesAutorizacao('+oDado.codigoAutorizacao+');">'
                            +oDado.codigoAutorizacao+'</a>';
                  aRow[1] = '<a href="#" onclick="parent.js_detalhesEmpenho(\''+oDado.codigoempenho+'\');">'
                            +oDado.empenho+'</a>';
                  aRow[2] = js_formatar(oDado.dataEmissao,'d');
                  aRow[3] = js_formatar(oDado.dataAnulacao,'d');
                  aRow[4] = oDado.valor;


                  oGrvDetalhes.addRow(aRow);
               break;

               case 'aditamentos':
                  var aRow = new Array();

                  aRow[0] = oDado.codigo;
                  aRow[1] = oDado.vigencia.urlDecode();
                  aRow[2] = oDado.numeroAditamento.urlDecode();
                  aRow[3] = oDado.situacao.urlDecode();
                  aRow[4] = oDado.data;
                  aRow[5] = oDado.emergencial == false ? 'Não' : 'Sim';

                  oGrvDetalhes.addRow(aRow);

               break;

               case 'rescisoes':
                  var aRow = new Array();

                  aRow[0] = js_formatar(oDado.data, 'd');
                  aRow[1] = oDado.hora;
                  aRow[2] = oDado.usuario.urlDecode();
                  aRow[3] = oDado.motivo.urlDecode();

                  oGrvDetalhes.addRow(aRow);
               break;

               case 'anulacoes':
                 var aRow = new Array();

                 aRow[0] = js_formatar(oDado.data, 'd');
                 aRow[1] = oDado.hora;
                 aRow[2] = oDado.usuario.urlDecode();
                 aRow[3] = oDado.motivo.urlDecode();

                 oGrvDetalhes.addRow(aRow);
               break;

              case "documentos":

                var aLinha = new Array();
                aLinha[0]  = oDado.iCodigo;
                aLinha[1]  = oDado.iAcordo;
                aLinha[2]  = decodeURIComponent(oDado.sDescricao.replace(/\+/g, ""));
                aLinha[3]  = '<input type="button" value="Dowload" onclick="js_documentoDownload('+oDado.iCodigo+')">';
                oGrvDetalhes.addRow(aLinha);
              break;

              case 'paralisacoes':

                var aLinha = new Array();
                aLinha[0]  = oDado.datainicial;
                aLinha[1]  = oDado.datafinal;
                aLinha[2]  = oDado.usuario.urlDecode();
                aLinha[3]  = oDado.observacao.urlDecode();
                oGrvDetalhes.addRow(aLinha);
                break;
            }

          }

        );

        oGrvDetalhes.renderRows();

        if (oRetorno.detalhe === 'itens') {

          aDadosHintGrid.each(function(oHint, id) {

            var oDBHint    = eval("oDBHint_"+id+" = new DBHint('oDBHint_"+id+"')");
            oDBHint.setText(oHint.sText);
            oDBHint.setShowEvents(aEventsIn);
            oDBHint.setHideEvents(aEventsOut);
            oDBHint.setPosition('B', 'L');
            oDBHint.setUseMouse(true);
            oDBHint.make($(oHint.idLinha));
          });
        }
      } else {

        oGrvDetalhes.setStatus('Nenhum Registro Retornado!');

      }
    } else {

      alert(oRetorno.message.urlDecode());
    }
  }
}

function js_showInfoItem(iLinha) {
  parent.js_showInfoItem(aItens[iLinha]);
}
function js_grvDetalhes() {

  switch (detalhe) {

    case 'itens':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(['5%', '5%', '20%', '30%', '10%', '10%', '10%', '10%']);
      oGrvDetalhes.setCellAlign(['left', 'right', 'left' , 'left', 'right', 'right', 'right', 'right']);
      oGrvDetalhes.setHeader(['Ordem','Código','Adit.','Descrição','Quantidade','Unidade','Valor Unitário','Valor Total']);
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.hasTotalizador = true;
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    case 'empenhamentos':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('20%', '20%', '20%', '20%', '20%'));
      oGrvDetalhes.setCellAlign(new Array('right', 'center', 'center', 'center', 'right'));
      oGrvDetalhes.setHeader(new Array('Código Autorização', 'Empenho', 'Data Emissão', 'Data Anulação', 'Valor'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    /**
     * Busca os empenhos vinculado ao contrato
     */
    case 'empenhos':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('20%', "40%", "20%", '20%', '20%'));
      oGrvDetalhes.setCellAlign(new Array('center', "left", "center", 'left', 'center'));
      oGrvDetalhes.setHeader(new Array('Empenho', "Resumo", "Característica Peculiar", 'Valor', 'Data Emissão'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();

      break;

    case "licitacoes":

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('20%', "40%", "20%", '20%', '20%'));
      oGrvDetalhes.setCellAlign(new Array('center', "left", "center", 'left', 'center'));
      oGrvDetalhes.setHeader(new Array('Licitação', "Objeto", "Local", 'Data de Criação', 'Modalidade'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();

      break;

    case "processodecompras":

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('20%', "40%", "20%", '20%'));
      oGrvDetalhes.setCellAlign(new Array('center', "left", "center", 'left'));
      oGrvDetalhes.setHeader(new Array('Processo de Compras', "Resumo", "Data Emissão", "Departamento"));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();

      break;

    case 'aditamentos':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('5%', '25%','10%', '30%', '15%', '15%'));
      oGrvDetalhes.setCellAlign(new Array('center', 'center', 'center', 'left', 'center', 'center'));
      oGrvDetalhes.setHeader(new Array('Código', 'Vigência', 'Número', 'Situação', 'Data', 'Emergencial'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    case 'rescisoes':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('10%', '10%', '20%', '60%'));
      oGrvDetalhes.setCellAlign(new Array('center', 'center', 'left', 'left'));
      oGrvDetalhes.setHeader(new Array('Data', 'Hora', 'Usuário', 'Motivo'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    case 'anulacoes':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('10%', '10%', '20%', '60%'));
      oGrvDetalhes.setCellAlign(new Array('center', 'center', 'left', 'left'));
      oGrvDetalhes.setHeader(new Array('Data', 'Hora', 'Usuário', 'Motivo'));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    case 'documentos':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('15%', '15%', '30%', '15%'));
      oGrvDetalhes.setCellAlign(new Array("right","right","left","center"));
      oGrvDetalhes.setHeader(new Array("Codigo","Acordo","Descricao","Download"));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      break;

    case 'paralisacoes':

      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('15%', '15%', '30%', '15%'));
      oGrvDetalhes.setCellAlign(new Array("center", "center", "left", "center"));
      oGrvDetalhes.setHeader(new Array("Data Inicial", "Data Final", "Descricao", "Observação"));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();

      break;
  }

  js_completaPesquisa(ac16_sequencial, detalhe);
}

function js_documentoDownload(iCodigoDocumento) {

  if (!confirm('Deseja realizar o Download do Documento?')) {
    return false;
  }

  var oParam              = new Object();
  oParam.exec             = 'downloadDocumento';
  oParam.acordo           = ac16_sequencial;
  oParam.iCodigoDocumento = iCodigoDocumento;
  js_divCarregando('Aguarde... realizando Download do documento','msgbox');
  var oAjax        = new Ajax.Request(
                              sUrlRC,
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
</script>