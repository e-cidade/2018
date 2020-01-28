require_once('scripts/widgets/windowAux.widget.js');

/**
 * DBView responsável por realizar o lançamento de um assentamento funcional
 * a partir de um assentamento por efetividade.
 */
var DBViewManutencaoAssentamentoFuncional = function (){

  this.iCodigoAssentamentoEfetividade;
  this.iMatricula;

  this.setCodigoAssentamentoEfetividade = function(iCodigoAssentamentoEfetividade) {
    this.iCodigoAssentamentoEfetividade  = iCodigoAssentamentoEfetividade;
  }

  this.setMatricula = function(iMatricula) {
    this.iMatricula  = iMatricula;
  }
}

/**
 * Exibe a windowAux com a lista de assentamentos funcionais a partir
 * do assentamento por efetividade
 *
 * @return  void
 */
DBViewManutencaoAssentamentoFuncional.prototype.show = function() {

  var sTitulo     = 'Processamento Assentamento Funcional';
  var sFormulario = 'forms/db_frmassentamentofuncional.php';
  var oSelf       = this;

  this.oWindow = new windowAux('AssentamentoFuncional', sTitulo, 715, 360);
  this.oWindow.zIndex = 2;
  this.oWindow.setContent('');
  this.oWindow.setShutDownFunction(function(){window.js_carregarAssentamentosEfetividade(); oSelf.oWindow.destroy();});
  this.oWindow.show();

  this.oWindow.getContentContainer().load(
    sFormulario,
    function() {

      oSelf.loadGrid();

      $('novo_assentamento').observe('click', function(){
        oSelf.criarNovoAssentamento();
      });

      $('fechar').observe('click', function() {
        window.js_carregarAssentamentosEfetividade();
        oSelf.oWindow.destroy();
      });
    }
  );
}

/**
 * Realiza a montagem da grid com os assentamentos funcionais.
 *
 * @return  void
 */
DBViewManutencaoAssentamentoFuncional.prototype.loadGrid = function(){

  var aHeader = ["Codigo", "Assentamento", "Ação"];

  this.oGridAssentamentosFuncional = new DBGrid("AssentamentoFuncional");
  this.oGridAssentamentosFuncional.nameInstance = 'AssentamentoFuncional';
  this.oGridAssentamentosFuncional.hasCheckbox  = false;
  this.oGridAssentamentosFuncional.setHeader(["Código", "Servidor", "Tipo", "Data Início", "Data Término"]);
  this.oGridAssentamentosFuncional.setCellWidth(["50px", "350px", "50px", "70px", "90px"]);
  this.oGridAssentamentosFuncional.setCellAlign(["center", "left", "center", "center", "center"]);
  this.oGridAssentamentosFuncional.show($('grid_assentamentos'));
  this.loadAssentamentosFuncionais();
};

/**
 * Realiza a chamada do metodo getAssentamentosFuncionais para o RPC,
 * para buscar os assentamentos funcionais
 *
 * @return void
 */
DBViewManutencaoAssentamentoFuncional.prototype.loadAssentamentosFuncionais = function() {

  this.oRequisicao = new AjaxRequest('rec4_assentamentosefetividade.RPC.php');
  this.oRequisicao.setParameters({
    "exec"              : "getAssentamentosFuncionais",
    "iCodigoEfetividade": this.iCodigoAssentamentoEfetividade,
  });
  this.oRequisicao.setCallBack(DBViewManutencaoAssentamentoFuncional.prototype.carregarDadosGrid.bind(this));
  this.oRequisicao.setMessage("Buscando Registros...");
  this.oRequisicao.execute();
}

/**
 * Realiza o carregamento dos dados dos assentamentos funcionais para a grid.
 *
 * @param  Object   oResponse
 * @param  boolean  lErro
 * @return  void
 */
DBViewManutencaoAssentamentoFuncional.prototype.carregarDadosGrid = function(oResponse, lErro) {

  var aAssentamentosEfetividade = oResponse.aAssentamentosEfetividade;

  this.oGridAssentamentosFuncional.clearAll(true);

  for (var iIndAssentaEfetiv = 0; iIndAssentaEfetiv < aAssentamentosEfetividade.length; iIndAssentaEfetiv++) {

    var oAssentamento = aAssentamentosEfetividade[iIndAssentaEfetiv];
    var aDadosAssentamento = [
      oAssentamento.iCodigo,
      oAssentamento.sNome,
      oAssentamento.sTipo,
      oAssentamento.sDataInicio,
      oAssentamento.sDataFim
    ];

    this.oGridAssentamentosFuncional.addRow(aDadosAssentamento);
  };

  this.oGridAssentamentosFuncional.renderRows();
}

/**
 * Realiza a abertura de uma nova janela para realizar a criação de um novo assentamento.
 *
 * @return  void
 */
DBViewManutencaoAssentamentoFuncional.prototype.criarNovoAssentamento = function() {

  var sQueryString  = "?lAssentamentoFuncional=true";
  sQueryString += "&iCodigoEfetividade="+this.iCodigoAssentamentoEfetividade;
  sQueryString += "&h16_regist="+this.iMatricula;

  var oJanela = null;

  if(oJanela == null) {
    oJanela = js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'assentamentofuncional',
      'rec1_assenta001.php'+sQueryString,
      'Novo Assentamento Funcional',
      true,
      0, 0, 850, 550
    );
  }

  var iPosicaoX     = (CurrentWindow.corpo.window.innerWidth/2) - 355;
  var iPosicaoY     = (CurrentWindow.corpo.window.innerHeight/2) - 322;

  oJanela.setPosX(iPosicaoX);
  oJanela.setPosY(iPosicaoY);

  var oSelf = this;

  oJanela.hide = function() {

    oSelf.loadGrid();
    $('Janassentamentofuncional').style.display = 'none';
  };

  oJanela.show = function() {
    $('Janassentamentofuncional').style.display = '';
  }
};