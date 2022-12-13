
const SITUACAO_CALCULO = {
  PARADO     :0,
  CALCULANDO :1,
  PAUSADO    :2,
  CONCLUIDO  :3
};

var ProcessamentoCalculo = function(aDados, oMock) {


  this.aDadosProcessar    = aDados;
  this.iSituacao          = SITUACAO_CALCULO.PARADO;
  this.aDadosErro         = [];
  this.oItemProcessamento = {};
  this.oMock              = oMock;
  this.oWorker            = new Worker("scripts/workers/pessoal/CalculoFolha.worker.js");
  this.oWorker.addEventListener( "message", this.respostaProcessamento.bind(this) );
  this.makeBehavior();
  this.init();
};

ProcessamentoCalculo.prototype = {
  makeBehavior: function()  {

    $('pausar').onclick = (function() {
      this.iSituacao = SITUACAO_CALCULO.PAUSADO;
      $('label_processamento').innerHTML = "Aguardando tÈrmino para pausar..."+ $('label_processamento').innerHTML ;
      $('continuar').style.display = '';
      $('continuar').disabled = true;
      $('pausar').style.display = 'none';
    }).bind(this);

    $('continuar').onclick = (function() {

      this.iSituacao = SITUACAO_CALCULO.CALCULANDO;
      this.executar();
      $('continuar').style.display = 'none';
      $('pausar').style.display = '';
    }).bind(this);

    $('procesar_erro').onclick = (function() {
      var oNovoCalculo = new ProcessamentoCalculo(this.aDadosErro, this.oMock);
      oNovoCalculo.executar();
    }).bind(this);

    $('fechar').onclick = (function() {
      parent.db_calculo.hide();
      window.location.href='';
    }).bind(this);

  $('pausar').style.display = '';
  $('continuar').style.display = "none";
  $('procesar_erro').style.display = "none";
  $('fechar').style.display = "none";

  },
  init: function() {

    $('barra_progresso').max = this.aDadosProcessar.length;

    window.oGridServidores               = new DBGrid("grid_servidores");
    window.oGridServidores.sNameInstance = "window.oGridServidores";
    window.oGridServidores.setHeader([ "CGM", "MatrÌculas Envolvidas","Nome", "Resultado"]);
    window.oGridServidores.setCellWidth(["80px",      "150px",   "350px",    "100px"]);
    window.oGridServidores.setCellAlign(["center",  "left",   "left",  "center"]);
    window.oGridServidores.setHeight("250");
    window.oGridServidores.show( $('gridResultados') );
    window.oGridServidores.clearAll(true);

    window.oGridErroProcessamento                = new DBGrid("grid_erro");
    window.oGridErroProcessamento.sNameInstance  = "window.oGridErroProcessamento";
    window.oGridErroProcessamento.setHeader([ "CGM", "MatrÌculas Envolvidas","Nome", "Resultado"]);
    window.oGridErroProcessamento.setCellWidth(["80px",      "150px",   "350px",    "100px"]);
    window.oGridErroProcessamento.setCellAlign(["center",  "left",   "left",  "center"]);
    window.oGridErroProcessamento.setHeight("250");
    window.oGridErroProcessamento.show( $('gridErros') );
    window.oGridErroProcessamento.clearAll(true);

  },

  adicionarProcesso: function(oDadosProcessamento) {

    $('label_processamento').innerHTML = '<strong>CGM:</strong> ' + oDadosProcessamento.iCodigo + ' - ' + oDadosProcessamento.sNome;
    $('label_andamento').innerHTML     = "<strong>CGMs Restantes:</strong> " + this.aDadosProcessar.length;
    var oMensagem = {
      "servidor"       : oDadosProcessamento,
      "mock_parametros": this.oMock
    };
    this.oWorker.postMessage(oMensagem);
  },

  respostaProcessamento: function(oWorkerResponse) {

    var oDados    = oWorkerResponse.data;
    var oResposta = oDados.resposta;
    var oServidor = oDados.servidor;

 // console.debug(oDados, oResposta.erro_tecnico ? atob(oResposta.erro_tecnico)|| '' );

    lSucesso  = oResposta.sucesso || false;//Fallback's Hack

    var sSituacao = "Calculado";

    if (!lSucesso) {

      var sMensagem    = "ALERTAS DURANTE O C√ÅLCULO:";
      var sErroTecnico = "";

      if ( !!oResposta.erro_tecnico ) {
        sErroTecnico = "\n\n\n----Erro TÈcnico----\n";
        sErroTecnico+= window.atob(oResposta.erro_tecnico);
      }

      sMensagem    += "\n----Mensagem----\n";
      sMensagem    += oResposta.alertas.join("\n----Mensagem----\n").replace(/(\n)+/g, "\n");
      sMensagem    += sErroTecnico;
      sSituacao     = '<abbr title="'+sMensagem+'"> Erro </abbr>';
    }

    aLinha = [
      oServidor.iCodigo,
      oServidor.aMatriculas ? oServidor.aMatriculas.join(', ') : '',
      oServidor.sNome,
      sSituacao
    ];

    $('barra_progresso').value        += 1;
    $('label_processamento').innerHTML = '';

    if ( lSucesso ) {
      window.oGridServidores.addRow(aLinha);
    } else {
      window.oGridErroProcessamento.addRow(aLinha);
      this.aDadosErro.push(oServidor);
    }

    window.oGridServidores.renderRows();
    window.oGridErroProcessamento.renderRows();

    if ( this.iSituacao == SITUACAO_CALCULO.PAUSADO ) {
      $('label_processamento').innerHTML = 'C·lculo em Pausa';
      $('continuar').disabled = false;
    } else {
      this.executar();
    }

  },

  executar: function() {

    this.iSituacao = SITUACAO_CALCULO.CALCULANDO;


    var oDados = this.aDadosProcessar.shift();
    /**
     * Fim do processamento
     */
    if ( !oDados ) {
      console.log("Fim", new Date());
      $('label_processamento').innerHTML = 'Fim do processamento';
      $('procesar_erro').style.display = "";
      $('fechar').style.display = "";
      $('pausar').style.display = "none";
      $('continuar').style.display = "none";

      this.iSituacao = SITUACAO_CALCULO.CONCLUIDO;

      return true;
    }

    this.adicionarProcesso(oDados);

  }
};
