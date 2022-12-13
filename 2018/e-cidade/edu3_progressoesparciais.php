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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");


$sWhere  = " ed109_tiposituacaoeducacao = 1 ";
$sCampos = " ed109_sequencial, ed109_descricao ";
$oDao    = new cl_situacaoeducacao();
$sSql    = $oDao->sql_query_file(null, $sCampos, "1", $sWhere);
$rs      = db_query($sSql);

$aSituacoes = array( 'TODOS');
if ( $rs && pg_num_rows($rs) > 0) {

  $iLinhas = pg_num_rows($rs);
  for ($i = 0; $i < $iLinhas; $i++) {

    $oDados       = db_utils::fieldsMemory($rs, $i);
    $aSituacoes[$oDados->ed109_sequencial] = $oDados->ed109_descricao;
  }
}
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
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js" ></script>
</head>
<body class='body-default'>
  <table>
    <tr>
      <td class="bold">Selecione:</td>
      <td>
        <select id='ctnStatusProgressao'>
          <?php
            foreach ($aSituacoes as $iCodigo => $sSituacao) {
              echo " <option codigo='{$iCodigo}' value='{$sSituacao}'>{$sSituacao}</option> ";
            }
          ?>
        </select>
      </td>
      <td>
        <input type="button" value='Imprimir' name="imprimir" id='imprimir' />
      </td>
    </tr>
  </table>

  <fieldset>
    <legend>Progressões do Aluno</legend>
    <div id='ctnGridProgressoes'></div>
  </fieldset>

</body>
</html>
<script type="text/javascript">

var oGet                    = js_urlToObject();
var RPC                     = 'edu4_progressaoparcial.RPC.php';
var MSG_PROGRESSOESPARCIAIS = 'educacao.escola.edu3_consultaaluno.';
var aProgressoesAluno       = [];

oGridProgressao    = new DBGrid('gridProgressoes');
var aHeadersGrid   = ['progressao', "Escola", "Ano", "Etapa", "Disciplina", "Situação", "Origem", "Detalhe"];
var aCellWidthGrid = ['0%', "30%", "8%", "10%", "25%", "10%", "9%", "8%"];
var aCellAlign     = ['left', "left", "center", "left", "left", "center", "center", "center"];

oGridProgressao.nameInstance = 'oGridProgressao';
oGridProgressao.setCellWidth(aCellWidthGrid);
oGridProgressao.setCellAlign(aCellAlign);
oGridProgressao.setHeader(aHeadersGrid);
oGridProgressao.setHeight(200);
oGridProgressao.aHeaders[0].lDisplayed = false;
oGridProgressao.show($('ctnGridProgressoes'));


function retornoProgressoes (oRetorno, lErro) {

  if ( lErro ) {

    alert( _M(MSG_PROGRESSOESPARCIAIS + "sem_progressao"));
    return;
  }

  aProgressoesAluno = oRetorno.aProgressoes;
  carregaDadosGrid();
}

function carregaDadosGrid() {

  oGridProgressao.clearAll(true);

  aProgressoesAluno.each( function (oProgressao) {

    if ( $F('ctnStatusProgressao') != 'TODOS' && $F('ctnStatusProgressao') != oProgressao.sSituacao.urlDecode() )  {
      return;
    }

    var sOrigem = oProgressao.lManual ? "Manual" : "Diário";
    var aLinha  = [];
    aLinha.push(oProgressao.iCodigo);
    aLinha.push(oProgressao.sEscola.urlDecode());
    aLinha.push(oProgressao.iAno);
    aLinha.push(oProgressao.sEtapa.urlDecode());
    aLinha.push(oProgressao.sDisciplina.urlDecode());
    aLinha.push(oProgressao.sSituacao.urlDecode());
    aLinha.push(sOrigem);

    var sDetalhe = "";
    if ( oProgressao.lTemMatricula ) {

      var sLinha            = aLinha.toSource();
      var oDetalhe          = new Element('img', {src:'imagens/icon_find_black.png'});
      oDetalhe.style.cursor = 'pointer';
      oDetalhe.setAttribute('onclick', "montaJanelaProgressaoParcial(" + sLinha + " );");
      sDetalhe = oDetalhe.outerHTML;
    }

    aLinha.push(sDetalhe);

    oGridProgressao.addRow(aLinha);
  });

  oGridProgressao.renderRows();
}

$('ctnStatusProgressao').observe( 'change', function (){
  carregaDadosGrid();
});



var oGridDetalhes = null;
function montaJanelaProgressaoParcial( aLinha ) {

  if ($('wndProgressaoParcial')) {
    return false;
  }

  var iHeight = 400;
  var iWidth  = 950;
  windowProgressaoParcial = new windowAux('wndProgressaoParcial', 'Progressão:', iWidth, iHeight);

  var sContent  = "<div class='container'>";
  sContent     += "<fieldset style='width: 900px'>";
  sContent     += "  <div id='gridDetalhes'>";
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  sContent     += "<input type='button' id='fechar' value='Fechar' onclick='windowProgressaoParcial.destroy();'>";
  sContent     += "</div>";

  windowProgressaoParcial.setContent(sContent);
  oMessageBoard = new DBMessageBoard('msgboard1',
                                    'Progressão Parcial',
                                    'Escola: ' + aLinha[1] + ' - Etapa: ' + aLinha[3] + ' - Disciplina: ' + aLinha[4] ,
                                    $('windowwndProgressaoParcial_content')
                                    );

  windowProgressaoParcial.setShutDownFunction(function() {
    windowProgressaoParcial.destroy();
  });

  oMessageBoard.show();
  windowProgressaoParcial.show(10, 50);
  oGridDetalhes              = new DBGrid('gridDetalhes');
  oGridDetalhes.nameInstance = 'oGridDetalhes';
  oGridDetalhes.setHeader( ["Escola", "Ano", "Turma", "Aproveitamento", "RF"] );
  oGridDetalhes.setCellWidth( ['35%', '10%', '25%', '25%', '5%'] );
  oGridDetalhes.setCellAlign( ["left", "center", "left", "left", "left"] );
  oGridDetalhes.setHeight(iHeight/3);
  oGridDetalhes.show($('gridDetalhes'));
  oGridDetalhes.clearAll(true);

  buscaDadosProgressaoAlunoMatriculado( aLinha[0] );
}

/**
 * Busca os dados referente a progressão e os adiciona a grid
 * @param  {integer} iProgressao
 */
function buscaDadosProgressaoAlunoMatriculado( iProgressao ) {

  var oParametros         = {};
  oParametros.exec        = 'buscaDadosProgressaoAlunoMatriculado';
  oParametros.iProgressao = iProgressao;

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {

    js_removeObj('msgBoxA');
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if ( oRetorno.iStatus == 2 ) {
      return;
    }

    oGridDetalhes.clearAll(true);

    oRetorno.aDadosProgressao.each( function ( oDadosProgressao ) {

      var aLinha = [];
      aLinha.push(oDadosProgressao.sEscola.urlDecode());
      aLinha.push(oDadosProgressao.iAno);
      aLinha.push(oDadosProgressao.sTurma.urlDecode());
      aLinha.push(oDadosProgressao.sAproveitamento.urlDecode());
      aLinha.push(oDadosProgressao.sResultadoFinal.urlDecode());
      oGridDetalhes.addRow(aLinha);
    });

    oGridDetalhes.aRows.each( function( oRow ) {
      oRow.aCells[0].addClassName('elipse');
      oRow.aCells[2].addClassName('elipse');
    });

    oGridDetalhes.renderRows();
  };

  js_divCarregando ( _M( MSG_PROGRESSOESPARCIAIS + "buscando_dados_progressoes"), "msgBoxA" );
  new Ajax.Request("edu4_progressaoparcial.RPC.php", oRequest);
}

/**
 * Imprime as progressões do aluno
 * @return {void}
 */
$('imprimir').observe( 'click', function () {

  var sUrl  = "edu2_progressoesaluno002.php";
      sUrl += "?iAluno=" + oGet.iAluno;
      sUrl += "&iSituacao=" + $('ctnStatusProgressao').options[$('ctnStatusProgressao').selectedIndex].getAttribute('codigo');

  jan = window.open(sUrl,'');
  jan.moveTo(0,0);
});


(function(){

  var oParametros            = {exec: 'buscaDadosProgressaoAluno', iAluno : oGet.iAluno, lInativos : true, lBuscaTodas:true};
  var oAjaxRequest           = new AjaxRequest(RPC, oParametros, retornoProgressoes);
  oAjaxRequest.lAsynchronous = true;
  oAjaxRequest.setMessage( _M( MSG_PROGRESSOESPARCIAIS + "buscando_progressoes" ) );
  oAjaxRequest.execute();
})();


</script>