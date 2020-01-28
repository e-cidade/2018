require_once("scripts/classes/DBViewLancamentoAvaliacao/PeriodoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/AlunoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/DisciplinaTurma.classe.js");

DBViewLancamentoParecerDisciplina = function (sNameInstancia, iTurma, iRegencia, oWindowAuxAvaliacao, sRPCAlterado) {

	this.sInstancia      = sNameInstancia;
	this.iMatriculaAluno = '';
  this.iTurma          = iTurma;
  this.iRegencia       = iRegencia;
  this.iCodigoPeriodo  = '';
  this.sRPC            = "edu4_lancamentoavaliacao.RPC.php";
  if (sRPCAlterado && sRPCAlterado != '') {
    this.sRPC = sRPCAlterado;   
  }
  
  this.metodoSalvarRpc         = 'salvarParecer';                             
  this.getParecerRpc           = 'getParecer';                             
  this.iCodigoEtapa            = '';  
  this.lMostrarPesquisaAluno   = false;  
  this.lMostrarPesquisaPeriodo = false;
  this.oAlunoTurma             = null;
  this.oPeriodoTurma           = null;
  this.lProfessorLogado        = false;
  
  var me = this;
	
  /**
	 * Cria a grid de pareceres da disciplina
	 */
  me.oGridParecerDisciplina               = new DBGrid('gridParecerDisciplina');
	me.oGridParecerDisciplina.nameInstance  = this.sInstancia+'.oGridParecerDisciplina';
	me.oGridParecerDisciplina.setCheckbox(0);
	me.oGridParecerDisciplina.setCellWidth(new Array("5%","85%", "10%"));
	me.oGridParecerDisciplina.setCellAlign(new Array("center", "left", "left"));
	me.oGridParecerDisciplina.setHeader(new Array("C�digo", "Parecer", "Legenda"));
  
	me.iWidth  = document.body.getWidth() / 1.3;
	me.iHeight = document.body.getHeight() / 1.2;
  
	
	
	/**
	 * .show da grid
	 */
	this.criarDataGridParecer = function () {
		
		me.oGridParecerDisciplina.show($('gridParecerDisciplina'));
	};
	
	/**
	 * Buscamos os pareceres vinculados a disciplina
	 */
	this.buscarParecesDisciplina = function () {
		
		var oParametro               = new Object();
		oParametro.exec              = 'getParecerDisciplina';
		oParametro.iMatriculaAluno   = me.iMatriculaAluno;
		oParametro.iRegencia         = me.iRegencia;
		oParametro.iPeriodoAvaliacao = me.iCodigoPeriodo;
		
		js_divCarregando('Aguarde, carregando as legendas.', 'msgBox');
		new Ajax.Request(me.sRPC,
		                 {
		                   method: 'post',
		                   parameters: 'json='+Object.toJSON(oParametro),
		                   onComplete: me.retornaBuscarPareceresDisciplina
		                 }
		                );
	};
	
	/**
	 * Retornamos os pareceres vinculados a disciplina
	 */
	this.retornaBuscarPareceresDisciplina = function (oResponse) {
		
		js_removeObj("msgBox");
		var oRetorno = eval('('+oResponse.responseText+')');
		var aOption  = new Array();

		oRetorno.aLegendas.each(function(oLegenda, iSeq) {
			
			aOption[iSeq] = "<option id='"+oLegenda.iCodigoLegenda+"'>"+oLegenda.sSigla.urlDecode()+"</option>";
		});
		
		me.oGridParecerDisciplina.clearAll(true);
		oRetorno.dados.each(function(oParecer, iSeq) {
			
			var aLinha = new Array();
			aLinha[0]  = oParecer.iCodigoParecer;
			aLinha[1]  = oParecer.sDescricao.urlDecode();
			aLinha[2]  = "<select id='parecer"+oParecer.iCodigoParecer+"'>"+aOption+"</select>";
			
			me.oGridParecerDisciplina.addRow(aLinha);
		});
		me.oGridParecerDisciplina.renderRows();
	}
	
	/**
	 * Salvamos os pareceres e legenda selecionados para uma disciplina
	 */
	this.salvarPareceres = function () {
		
		var oParametro                 = new Object();
		oParametro.exec                = me.metodoSalvarRpc;
		oParametro.sParecer            = encodeURIComponent(tagString($F('parecer')));
		oParametro.iMatricula          = me.iMatriculaAluno;
		oParametro.iRegencia           = me.iRegencia;
		oParametro.iPeriodo            = me.iCodigoPeriodo;
    oParametro.sParecerPadronizado = '';
		var sAgrupador                 = '';
		
		if (me.metodoSalvarRpc == 'salvarParecerComplementar') {
		  
	    if (js_empty(me.iMatriculaAluno)) {
	      
	      alert('Antes de salvar o parecer, escolha um aluno.');
	      return false;
	    }
	    
	    if (js_empty(me.iCodigoPeriodo)) {
	      
	      alert('Antes de salvar o parecer, escolha um per�odo de avalia��o.');
	      return false;	      
	    }
	    
	    delete oParametro.iRegencia;
	    oParametro.aRegencia = [me.iRegencia];
	    if (this.oDisciplinasTurma != null) {
	     
	      var aDisciplinasSelecionadas = this.oDisciplinasTurma.getSelecionados();
	      if (aDisciplinasSelecionadas.length > 0) {
	        oParametro.aRegencia = oParametro.aRegencia.concat(aDisciplinasSelecionadas);
	      }
	    }    
	  }
		var aLinhasSelecionadas = new Array();
		aLinhasSelecionadas     = me.oGridParecerDisciplina.getSelection("object");
		
		/**
		 * Agrupamos o codigo do parecer, descricao e a legenda selecionada
		 */
		aLinhasSelecionadas.each(function(oParecer, iSeq) {
			
			oParametro.sParecerPadronizado += sAgrupador+oParecer.aCells[0].getValue()+" - "+oParecer.aCells[2].getValue();
			if (oParecer.aCells[3].getValue() != null) {
			  oParametro.sParecerPadronizado += " => "+oParecer.aCells[3].getValue();
			}
			sAgrupador = " ** ";
		});
		
		new Ajax.Request(me.sRPC,
		                 {
		                   method:     'post',
		                   parameters: 'json='+Object.toJSON(oParametro),
		                   onComplete: me.retornaSalvarParecer
		                 }
		                );
	};
	
	/**
	 * Retorno de salvarParecer
	 */
	this.retornaSalvarParecer = function (oResponse) {
		
		var oRetorno = eval('('+oResponse.responseText+')');
		if (oRetorno.status == 1) {
      me.oCallback(me);
    }
	};
	
	
	this.oCallback = function () {
    return true;
  };
	
	me.setCallback  = function (oFunction) {
    me.oCallback  = oFunction;
  };
	
};

/**
 * Buscamos os pareceres dados na disciplina
 */
DBViewLancamentoParecerDisciplina.prototype.getParecer  = function() {
  
  var me = this;
  
  var oParametro        = new Object();
  oParametro.exec       = me.getParecerRpc;
  oParametro.iMatricula = me.iMatriculaAluno;
  oParametro.iRegencia  = me.iRegencia;
  oParametro.iPeriodo   = me.iCodigoPeriodo;
  js_divCarregando('Aguarde, buscando dados dos pareceres', 'msgBox');
  var oJson        = new Object();
  oJson.method     = 'post';
  oJson.parameters = 'json='+Object.toJSON(oParametro);
  oJson.onComplete = function (oResponse) {
  
    js_removeObj('msgBox');
    var oRetorno       = eval("("+oResponse.responseText+")");
    $('parecer').value = oRetorno.sParecer.urlDecode();
    var aPartesParecerPadronizado = oRetorno.sParecerPadronizado.urlDecode().split("**");
    
    aPartesParecerPadronizado.each(function(sPartePadronizada, iSeq) {
    
       aParteParecer        = sPartePadronizada.split("-");
       iTotalParteParecer   = aParteParecer.length;
       
       var oParecer         = new Object();
           oParecer.iCodigo = aParteParecer[0].trim();
       
       var sDescricao = aParteParecer[1];
       
       if (iTotalParteParecer > 2) {
         
         for (var iContador = 2; iContador < iTotalParteParecer; iContador++) {
           sDescricao += "-"+aParteParecer[iContador];
         }
       }

       oParecer.sDescricao = sDescricao;
       
       if (oParecer.sDescricao != "") {
         
         aParteDescricaoParecerLegenda = oParecer.sDescricao.split("=>");
         if (aParteDescricaoParecerLegenda.length > 0 && aParteDescricaoParecerLegenda[1]) {
           
           oParecer.sLegenda                   = aParteDescricaoParecerLegenda[1].trim();
           $('parecer'+oParecer.iCodigo).value = oParecer.sLegenda;
         }
         $('chk'+oParecer.iCodigo).click();
       }
    });
  };
  new Ajax.Request(me.sRPC, oJson);
};

/**
 * Renderizamos a window
 */
DBViewLancamentoParecerDisciplina.prototype.renderizaWindowParecer = function () {
  
  var me = this;
  me.oWindowAuxParecer = new windowAux("wndParecer",
                                       "Lan�amento de Pareceres",
                                       me.iWidth,
                                       me.iHeight
                                      );
  
  me.oWindowAuxParecer.allowCloseWithEsc(false);
  
  var sConteudo  = "<div>";
  sConteudo     += "  <div>";
  sConteudo     += "  <fieldset id='ctnFieldSetAlunos' style='display:none; width:98%'>";
  sConteudo     += "    <Legend><b>Alunos</b></legend>";
  sConteudo     += "    <table>";
  sConteudo     += "      <tr id='ctnListaAlunos' style='display:none'>";
  sConteudo     += "        <td>";
  sConteudo     += "          <b>Aluno:</b>";
  sConteudo     += "        </td>";
  sConteudo     += "        <td id='tdAluno'>";
  sConteudo     += "        </td>";
  sConteudo     += "      </tr>";
  sConteudo     += "      <tr id='ctnListaPeriodos' style='display:none'>";
  sConteudo     += "        <td>";
  sConteudo     += "          <b>Per�odo:</b>";
  sConteudo     += "        </td> ";
  sConteudo     += "        <td id='tdPeriodo'>";
  sConteudo     += "        </td>";
  sConteudo     += "      </tr>";
  sConteudo     += "    </table>";
  sConteudo     += "  </fieldset>";  
  sConteudo     += "  <fieldset style='width:98%'>";
  sConteudo     += "    <legend><b>Parecer Padronizado</b></legend>";
  sConteudo     += "    <div id='gridParecerDisciplina'>";
  sConteudo     += "    </div>";
  sConteudo     += "  </fieldset>";
  sConteudo     += "  </div>";
  sConteudo     += "  <div>";
  sConteudo     += "    <fieldset style='width:98%'>";
  sConteudo     += "      <legend><b>Parecer Descritivo</b></legend>";
  sConteudo     += "      <form id='frmParecerDescritivo' name='frmParecerDescritivo' action=''>";
  sConteudo     += "        <textarea id='parecer' rows='5' cols='195'></textarea>";
  sConteudo     += "      </form>";
  sConteudo     += "    </fieldset>";  
  sConteudo     += "  <fieldset id='ctnDisciplinasTurmas' style='display:none; width:98%'>";
  sConteudo     += "    <Legend id='legendDisciplinasTurmas'><b>Outras Disciplinas para o parecer</b></legend>";
  sConteudo     += "  </fieldset>";  
  sConteudo     += "  </div>";
  sConteudo     += "  <center>";
  sConteudo     += "    <input type='button' name='btnSalvarPareceres' value='Salvar Pareceres' onclick='"+me.sInstancia+".salvarPareceres();' />";
  sConteudo     += "  </center>";
  sConteudo     += "</div>";

  me.oWindowAuxParecer.setShutDownFunction(function () {
    me.oWindowAuxParecer.destroy();
  });
   /*if (oWindowAuxAvaliacao != null) {
     me.oWindowAuxParecer.setChildOf(oWindowAuxAvaliacao);
   }*/
  me.oWindowAuxParecer.setContent(sConteudo);
  new DBMessageBoard('msgBoardAvaliacao',
                     'Lan�amento de Pareceres',
                     'Informe a legenda',
                      me.oWindowAuxParecer.getContentContainer()
                     );
  
  me.oWindowAuxParecer.show();
};

/**
 * Carregamos as funcoes que mostram o conteudo
 */
DBViewLancamentoParecerDisciplina.prototype.show = function ( oElementoDestino ) {
  
  var me = this;
  me.renderizaWindowParecer();
  me.criarDataGridParecer();  
  me.criarPesquisaAluno();
  
  if (!this.lMostrarPesquisaAluno) {
    
    me.buscarParecesDisciplina();
    me.getParecer();
  }
};

/**
 * Retorna a janela do container
 * @returns windowAux
 */
DBViewLancamentoParecerDisciplina.prototype.getWindow = function() {
  
  return this.oWindowAuxParecer;
};

/**
 * Define o matr�cula que ser� lan�ada o parecer 
 * @param iMatricula C�digo da matricula
 */
DBViewLancamentoParecerDisciplina.prototype.setMatricula = function (iMatricula) {
  this.iMatriculaAluno = iMatricula;
};

/**
 * Define o codigo do periodo em que o parecer ser� lan�ado.
 * @param iCodigoPeriodo
 */
DBViewLancamentoParecerDisciplina.prototype.setCodigoPeriodo = function (iCodigoPeriodo) {
  this.iCodigoPeriodo = iCodigoPeriodo;
};

/**
 * Define o codigo da etapa da turma
 * @param iCodigoEtapa Codigo da etapa
 */
DBViewLancamentoParecerDisciplina.prototype.setEtapa = function(iCodigoEtapa) {
  
  this.iCodigoEtapa = iCodigoEtapa;
};

/**
 * Mostra os dados para pesquisar os alunos e per�odos para lan�ar pareceres.
 * @param lMostrarAluno
 */
DBViewLancamentoParecerDisciplina.prototype.mostrarPesquisaAluno = function (lMostrarAluno) {
  this.lMostrarPesquisaAluno = lMostrarAluno;
};

/**
 * Mostra os dados para pesquisar os alunos e per�odos para lan�ar pareceres.
 * @param lMostrarAluno
 */
DBViewLancamentoParecerDisciplina.prototype.mostrarPesquisaPeriodo = function (lMostrarPeriodo) {
  this.lMostrarPesquisaPeriodo = lMostrarPeriodo;
};
/**
 * 
 */
DBViewLancamentoParecerDisciplina.prototype.criarPesquisaAluno = function () {
  
  var sDisplay = 'none'; 
  if (this.lMostrarPesquisaAluno || this.lMostrarPesquisaPeriodo) {
    
    sDisplay             = '';
    this.metodoSalvarRpc = 'salvarParecerComplementar';
    this.getParecerRpc   = 'getParecerComplementar';
    
    if (this.oAlunoTurma == null && this.lMostrarPesquisaAluno ) {

      this.oAlunoTurma   = new DBViewAvaliacao.AlunoTurma(this.iTurma, this.iCodigoEtapa, false);
      this.oAlunoTurma.show($('tdAluno'));
      this.oAlunoTurma.onChangeCallBack(function() {
        this.getParecerAluno();
      } , this);
      
      $('ctnListaAlunos').style.display  = '';
    }
    if (this.oPeriodoTurma == null && this.lMostrarPesquisaPeriodo) {
      
      this.oPeriodoTurma = new DBViewAvaliacao.PeriodoTurma(this.iTurma, this.iCodigoEtapa, false, false);
      this.oPeriodoTurma.show($('tdPeriodo'));
      this.oPeriodoTurma.onChangeCallBack(function() {
        this.getParecerAluno();        
      } , this);
      $('ctnListaPeriodos').style.display  = '';
      
    }
    
    if (this.lMostrarPesquisaAluno || this.lMostrarPesquisaPeriodo) {
      
      $('ctnDisciplinasTurmas').style.display = '';
      this.oDisciplinasTurma = new DBViewAvaliacao.DisciplinaTurma(this.iTurma, this.iCodigoEtapa, this.lProfessorLogado);
      this.oDisciplinasTurma.naoListarAsRegencias([this.iRegencia]);
      this.oDisciplinasTurma.show($('ctnDisciplinasTurmas'));
    }
  }
  
  $('ctnFieldSetAlunos').style.display = sDisplay;
};
/**
 * Pesquisa o parecer do aluno no periodo
 */
DBViewLancamentoParecerDisciplina.prototype.getParecerAluno = function() {
  
  if (this.lMostrarPesquisaAluno) {
    this.iMatriculaAluno = this.oAlunoTurma.getSelecionados()[0];
  }
  this.iCodigoPeriodo  = this.oPeriodoTurma.getOrdemSelecionados()[0];
  
  if (!empty(this.iMatriculaAluno) != '' && !empty(this.iCodigoPeriodo)) {
    
    this.buscarParecesDisciplina();
    this.getParecer();
  }
};

/**
 * Seta se o usuario logado eh um professor ou nao
 * @param boolean lProfessorLogado
 */
DBViewLancamentoParecerDisciplina.prototype.setProfessorLogado = function(lProfessorLogado) {
  
  this.lProfessorLogado = lProfessorLogado;
};