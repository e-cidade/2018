require_once("scripts/widgets/Collection.widget.js");
require_once("scripts/widgets/DatagridCollection.widget.js");
require_once("scripts/widgets/Input/DBInput.widget.js");
require_once("scripts/widgets/Input/DBInputDate.widget.js");

DBViewRenovacao = function(oConfiguracao,  aItensRenovar ) {

  this.oConfiguracao = oConfiguracao;
  this.aItensRenovar = aItensRenovar;

  this.oEmprestimosCollection = new Collection().setId('emprestimoacervo');
  this.oEmprestimosCollection.add(this.aItensRenovar);

};

DBViewRenovacao.prototype.montaView = function () {

  var sContainer = '';
  sContainer +=  "<div class='container'>";
  sContainer +=  "  <form name='form1' id='form1' >";
  sContainer +=  "    <fieldset>";
  sContainer +=  "      <legend>Renovação de Empréstimo</legend>";
  sContainer +=  "      <table class='form-container'>";
  sContainer +=  "        <tr>";
  sContainer +=  "          <td>";
  sContainer +=  "            <label for='sLeitor'>Leitor:</label>";
  sContainer +=  "          </td>";
  sContainer +=  "          <td>";
  sContainer +=  "            <input type='text' name='sLeitor' id='sLeitor' class=' readonly field-size7' disabled='disabled' />";
  sContainer +=  "          </td>";
  sContainer +=  "        </tr>";
  sContainer +=  "        <tr>";
  sContainer +=  "          <td>";
  sContainer +=  "            <label for='iTempoEmprestimo'>Tempo de Empréstimo (em dias):</label>";
  sContainer +=  "          </td>";
  sContainer +=  "          <td>";
  sContainer +=  "            <input type='text' name='iTempoEmprestimo' id='iTempoEmprestimo' class=' readonly field-size2' disabled='disabled' />";
  sContainer +=  "          </td>";
  sContainer +=  "        </tr>";
  sContainer +=  "        <tr>";
  sContainer +=  "          <td>";
  sContainer +=  "            <label for='dtRetirada'>Retirada:</label>";
  sContainer +=  "          </td>";
  sContainer +=  "          <td>";
  sContainer +=  "            <input type='text' name='dtRetirada' id='dtRetirada' class=' readonly field-size2' disabled='disabled' />";
  sContainer +=  "          </td>";
  sContainer +=  "        </tr>";
  sContainer +=  "        <tr>";
  sContainer +=  "          <td>";
  sContainer +=  "            <label for='bi18_devolucao'>Devolução:</label>";
  sContainer +=  "          </td>";
  sContainer +=  "          <td>";
  sContainer +=  "            <input type='text'   name='bi18_devolucao' id='bi18_devolucao' class='field-size2' />";
  sContainer +=  "            <input type='hidden' name='bi18_devolucao_ano' id='bi18_devolucao_ano' />";
  sContainer +=  "            <input type='hidden' name='bi18_devolucao_mes' id='bi18_devolucao_mes' />";
  sContainer +=  "            <input type='hidden' name='bi18_devolucao_dia' id='bi18_devolucao_dia' />";
  sContainer +=  "            <input type='text'   name='diasemana' id='diasemana' class=' readonly field-size2' disabled='disabled' />";
  sContainer +=  "          </td>";
  sContainer +=  "        </tr>";
  sContainer +=  "      </table>";
  sContainer +=  "      <fieldset>";
  sContainer +=  "        <legend>Empréstimo(s) selecionado(s) para renovação</legend>";
  sContainer +=  "        <div id='gridEmprestimos'></div>";
  sContainer +=  "      </fieldset>";
  sContainer +=  "    </fieldset>";
  sContainer +=  "    <input type='button' name='btnConfirmarRenovacao' id='btnConfirmarRenovacao' value='Confirmar Renovação' />";
  sContainer +=  "    <input type='checkbox' name='chkComprovanteRenovacao' id='chkComprovanteRenovacao'/>";
  sContainer +=  "    <label for='chkComprovanteRenovacao'> Emitir Comprovante </label>";
  sContainer +=  "    <iframe src='' name='iframe_verificadata' id='iframe_verificadata' width='0' height='0' frameborder='0'></iframe>";
  sContainer +=  "  </form>";
  sContainer +=  "</div>";

  var oSelf    = this;
  this.oWindow = new windowAux('wndRenovacao', 'Renovação de Empréstimo', 700, 480);
  this.oWindow.setContent(sContainer);
  this.oWindow.setShutDownFunction(function() {

    buscarEmprestimos();
    oSelf.oWindow.destroy();
  });
  oSelf.oWindow.show();

  var oDataDevolucao  = new DBInputDate( $('bi18_devolucao') );
  var oDadosRenovacao =  this.aItensRenovar[0];

  $('sLeitor').value = oDadosRenovacao.leitor;

  var oDtRetirada             = new Date();
  $('dtRetirada').value       = oDtRetirada.getFormatedDate(DATA_PTBR);
  $('iTempoEmprestimo').value = oDadosRenovacao.dias_emprestimo;

  var oDtDevolucao = somaDataDiaMesAno(oDtRetirada, oDadosRenovacao.dias_emprestimo, 0, 0 );
  oSelf.calculaDevolucao( oDtDevolucao );

  $('bi18_devolucao').observe('blur', function() {

    var aParseData     = $F('bi18_devolucao').split('/');
    var oDtDevolucao = new Date ( aParseData[2], (aParseData[1] - 1), aParseData[0]);
    oSelf.calculaDevolucao(oDtDevolucao);
  });

  var oGridEmprestimos = DatagridCollection.create(this.oEmprestimosCollection).configure({"order": false, "height": "110"});

  oGridEmprestimos.addColumn("codigo_barras",   {label : "Cód. Barras", 'width':'25%'});
  oGridEmprestimos.addColumn("titulo", {label : "Título", 'width':'75%'});
  oGridEmprestimos.show( $('gridEmprestimos') );

  $('btnConfirmarRenovacao').observe('click', function() {
    oSelf.validaRenovacao();
  });
};

DBViewRenovacao.prototype.calculaDevolucao = function( oData ) {

  var sMesDevolucao = oData.getMonth() + 1;
  var sDiaDevolucao = oData.getDate();
  sMesDevolucao     = new String(sMesDevolucao).lpad(0, 2);
  sDiaDevolucao     = new String(sDiaDevolucao).lpad(0, 2);

  iframe_verificadata.location = "bib1_emprestimo002.php?ano="+oData.getFullYear()+"&mes="+ sMesDevolucao +"&dia="+sDiaDevolucao;
};

DBViewRenovacao.prototype.validaRenovacao = function() {

  var oSelf    = this;
  var aAcervos = [];

  this.aItensRenovar.each(function (oDados) {
    aAcervos.push(oDados.acervo);
  });

  var oParamentros         = {'exec': 'temReserva', 'aAcervos': aAcervos, 'dtDevolucao' : $F('bi18_devolucao')};
  oParamentros.iBiblioteca = this.oConfiguracao.iBiblioteca;

  var oAjax = new AjaxRequest('bib4_emprestimo.RPC.php', oParamentros,
    function (oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.sMessage);
        return;
      }
      if ( oRetorno.lTemReserva ) {

        var sTitulos = oRetorno.aAcervosReservados.implode( ' , ' );
        alert( 'O(s) exemplar(es) ' + sTitulos + ' possui(em) reserva. Não é possível efetuar a renovação.' );
        return;
      }

      oSelf.renovarExemplar();

    }
  );

  oAjax.setMessage('Verificando se exemplares não estão reservados, aguarde...');
  oAjax.execute();
};

DBViewRenovacao.prototype.renovarExemplar = function( ) {

  var aDados = [];

  this.oEmprestimosCollection.get().each(function(oDadosCollection) {
    aDados.push(oDadosCollection.build());
  });

  var oParamentros         = {'exec': 'renovar', 'aItens': aDados, 'dtDevolucao' : $F('bi18_devolucao')};
  oParamentros.dtRetirada  = $F('dtRetirada');
  oParamentros.iBiblioteca = this.oConfiguracao.iBiblioteca;

  var oSelf = this;
  var oAjax = new AjaxRequest('bib4_emprestimo.RPC.php', oParamentros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro )  {
      return;
    }

    if ($('chkComprovanteRenovacao').checked) {
      oSelf.imprimirComprovanteRenovacao(oRetorno.iNovoEmprestimo);
    }
    oSelf.oWindow.oImagem.click();
  });

  oAjax.setMessage('Renovando exemplares, aguarde...');
  oAjax.execute();
};

DBViewRenovacao.prototype.show = function( ) {

  this.montaView();
};

DBViewRenovacao.prototype.imprimirComprovanteRenovacao = function(iEmprestimo) {

  var sUrl  = 'bib2_emprestimo002.php';
      sUrl += '?emp=' + iEmprestimo;
      sUrl += '&tipo=0';
  window.open(sUrl,'','scrollbars=1,location=0 ');
}