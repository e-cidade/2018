require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/widgets/DBLookUp.widget.js');

Justificativas = function(oItemCollection) {

  this.oItem    = oItemCollection;
  this.sRpc     = 'rec4_pontoeletronico.RPC.php';
  this.iLargura = document.body.getWidth() / 3.3;
  this.iAltura  = document.body.clientHeight / 2.2;
  this.oWindow  = new windowAux('oWindowJustificativas', 'Lançar Justificativa', this.iLargura, this.iAltura);
  this.oCallBackCloseWindow = function() {
    return true;
  };

  this.aJustificativasLancadas = [];

  var oReg   = new RegExp('_', 'g');
  this.sData = this.oItem.data.replace(oReg, '/');
};

Justificativas.prototype.montaWindow = function() {

  var oSelf = this;

  this.oWindow.setShutDownFunction(function() {

    oSelf.oCallBackCloseWindow();
    oSelf.oWindow.destroy();
  });

  var sConteudo  = "<div>";
      sConteudo += "  <form>";
      sConteudo += "    <fieldset>";
      sConteudo += "      <legend>Dados para ajuste</legend>";
      sConteudo += "      <table class='form-container'>";
      sConteudo += "        <tr>";
      sConteudo += "          <td><label for='tipoAjuste'>Tipo:</label></td>";
      sConteudo += "          <td colspan='2'>";
      sConteudo += "            <select id='tipoAjuste'>";
      sConteudo += "              <option value=''>Selecione...</option>";
      sConteudo += "              <option value='T'>Total</option>";
      sConteudo += "              <option value='P'>Parcial</option>";
      sConteudo += "            </select>";
      sConteudo += "          </td>";
      sConteudo += "        </tr>";
      sConteudo += "        <tr>";
      sConteudo += "          <td>";
      sConteudo += "            <label>";
      sConteudo += "              <a id='ancoraJustificativas' href='#' func-arquivo='func_pontoeletronicojustificativa.php' func-objeto='db_iframe_pontoeletronicojustificativa'>Justificativa:</a>";
      sConteudo += "            </label>";
      sConteudo += "          </td>";
      sConteudo += "          <td><input id='rh194_sequencial' type='text' value='' class='field-size2' /></td>";
      sConteudo += "          <td><input id='rh194_descricao' type='text' value='' class='field-size9 readonly' disabled='disabled' /></td>";
      sConteudo += "        </tr>";
      sConteudo += "        <tr id='linhaRegistros' style='display: none;'>";
      sConteudo += "          <td colspan='3'>";
      sConteudo += "            <fieldset class='separator'>";
      sConteudo += "              <legend>Registros a serem abonados</legend>";
      sConteudo += "              <div id='registrosHora'></div>";
      sConteudo += "            </fieldset>";
      sConteudo += "          </td>";
      sConteudo += "        </tr>";
      sConteudo += "      </table>";
      sConteudo += "    </fieldset>";
      sConteudo += "    <div class='container'>";
      sConteudo += "      <input id='salvarJustificativa' type='button' value='Salvar' />";
      sConteudo += "      <input id='removerJustificativas' type='button' value='Remover' disabled='disabled' />";
      sConteudo += "    </div>";
      sConteudo += "  </form>";
      sConteudo += "</div>";

  this.oWindow.setContent(sConteudo);

  new DBMessageBoard(
    'msgJustificativas',
    'Ajustes do ponto',
    'Data: ' + this.sData,
    this.oWindow.getContentContainer()
  );

  this.oWindow.show();

  this.oLookupJustificativas = new DBLookUp(
    $('ancoraJustificativas'),
    $('rh194_sequencial'),
    $('rh194_descricao'),
    {
      'sLabel' : 'Pesquisar Justificativas',
      'zIndex' : 100000
    }
  );

  $('tipoAjuste').observe('change', this.validaTipo);
  $('salvarJustificativa').observe('click', function() {
    oSelf.salvarJustificativa();
  });

  $('removerJustificativas').observe('click', function() {
    oSelf.removerJustificativas();
  });

  this.registrosHora();
};

Justificativas.prototype.registrosHora = function() {

  var diaPonto = this.oItem.diaPonto;
  diaPonto.getMarcacoes().each(function(oMarcacoes, iSeq) {

    if([0, 2, 4].indexOf(iSeq) > -1) {

      oMarcacaoEntrada = diaPonto.getMarcacao(iSeq+1);
      oMarcacaoSaida   = diaPonto.getMarcacao(iSeq+2);

      if(oMarcacaoEntrada.codigo != null && oMarcacaoSaida.codigo != null) {

        var sIdRadio = oMarcacaoEntrada.codigo + '_' + oMarcacaoSaida.codigo;
        var oRadio = new Element('input');
            oRadio.setAttribute('type', 'radio');
            oRadio.setAttribute('id', sIdRadio);
            oRadio.setAttribute('name', 'marcacoes');

        var iPosicao = iSeq + 1;

        if(iSeq > 0) {

          iPosicao = iSeq;
          
          if(iSeq == 4) {
            iPosicao = iSeq - 1;
          }
        }

        var sHoraEntrada = (oMarcacaoEntrada.hora !== null) ? oMarcacaoEntrada.hora : ' ';
        var sHoraSaida   = (oMarcacaoSaida.hora !== null) ? oMarcacaoSaida.hora : ' ';

        var sLinha   = ' Entrada' + iPosicao + '(' + sHoraEntrada + ')';
            sLinha  += ' - Saída' + iPosicao + '(' + sHoraSaida + ')';

        var oLinha = new Element('span');
            oLinha.innerHTML = "<label for='"+sIdRadio+"'>"+sLinha + '</label><br>';

        $('registrosHora').appendChild(oRadio);
        $('registrosHora').appendChild(oLinha);
      }
    }
  });
};

Justificativas.prototype.validaTipo = function() {

  $('linhaRegistros').setStyle({'display' : 'none'});

  if($F('tipoAjuste') == 'P') {
    $('linhaRegistros').setStyle({'display' : ''});
  }
};

Justificativas.prototype.salvarJustificativa = function() {

  if(empty($F('tipoAjuste'))) {

    alert('Selecione um tipo.');
    return false;
  }

  if(empty($F('rh194_sequencial'))) {

    alert('Selecione uma justificativa.');
    return false;
  }

  var aElementosRadio      = $$('input[type=radio]');
  var oElementoSelecionado = $$('input[type=radio]:checked');

  if($F('tipoAjuste') == 'P' && oElementoSelecionado.length == 0) {

    alert('Selecione o registro a ser ajustado.');
    return false;
  }

  var oParametros = {
    'exec'                 : 'salvarJustificativasRegistrosPonto',
    'iCodigoJustificativa' : $F('rh194_sequencial'),
    'sTipoJustificativa'   : $F('tipoAjuste'),
    'sCodigoData'          : this.oItem.diaPonto.codigo,
    'sData'                : this.sData,
    'iExercicio'           : this.oItem.exercicio,
    'iCompetencia'         : this.oItem.competencia,
    'iMatricula'           : this.oItem.diaPonto.matricula,
    'aMarcacoes'           : []
  };

  var aElementosEnvio = aElementosRadio;

  if($F('tipoAjuste') == 'P') {
    aElementosEnvio = oElementoSelecionado;
  }

  aElementosEnvio.each(function(oElemento) {

    var sId      = oElemento.getAttribute('id');
    var aCodigos = sId.split('_');
    var oMarcacao1 = {'codigo' : aCodigos[0]};
    var oMarcacao2 = {'codigo' : aCodigos[1]};

    oParametros.aMarcacoes.push(oMarcacao1);
    oParametros.aMarcacoes.push(oMarcacao2);
  });

  AjaxRequest.create(
    'rec4_pontoeletronico.RPC.php',
    oParametros,
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }
    }
  ).setMessage('Aguarde... Salvando a justificativa...').execute();
};

Justificativas.prototype.removerJustificativas = function() {

  if(!confirm('Confirma a exclusão das justificativas lançadas para o dia ' + this.sData)) {
    return false;
  }

  AjaxRequest.create(
    this.sRpc,
    {
      'exec' : 'removerJustificativasDia',
      'aJustificativas' : this.aJustificativasLancadas
    },
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }

      $('removerJustificativasDia').setAttribute('disabled', 'disabled');
    }
  ).setMessage('Aguarde... Removendo as justificativas...').execute();
};

Justificativas.prototype.buscaJustificativasLancadas = function() {

  var oSelf = this;
  var oReg  = new RegExp('_', 'g');

  AjaxRequest.create(
    this.sRpc,
    {
      'exec'      : 'buscarJustificativasDia',
      'data'      : this.sData,
      'matricula' : this.oItem.diaPonto.matricula
    },
    function(oRetorno, lErro) {

      if(lErro) {

        alert(oRetorno.mensagem);
        return false;
      }

      if(oRetorno.aJustificativas.length > 0) {

        oSelf.aJustificativasLancadas = oRetorno.aJustificativas;
        $('removerJustificativas').removeAttribute('disabled');
      }
    }
  ).setMessage('Aguarde... Buscando as justificativas lançadas....').execute();
};

Justificativas.prototype.setCallBackClose = function(fFunction) {
  this.oCallBackCloseWindow = fFunction;
};

Justificativas.prototype.show = function() {

  this.montaWindow();
  this.buscaJustificativasLancadas();
};