require_once("scripts/classes/DBViewArvoreTurma.classe.js");
DBViewFiltroLancamentoAvaliacao = function(sInstancia) {
	
  this.oDataGridAlunos     = null;  
  this.oViewArvoreTurma  = null;
  this.oWindowAuxAvaliacao = null;
  this.aMatriculas         = new Array();
  this.lProgressaoParcial  = false;
	var oInstancia           = this;
	this.iTurmaSelecionada   = '';
	this.iEtapaSelecionada   = '';

	/**
	 * Renderiza��o do HTML do componente
	 */
	var renderizarHTML = function () {
		
		var sConteudo    = '<div id="divPesquisaGrupo">                                                             \n';
        sConteudo   += '  <div id="divListaGrupo">                                                              \n';
        sConteudo   += '    <fieldset style="height:80%">                                                       \n';
        sConteudo   += '      <legend>                                                                          \n';
        sConteudo   += '        <b>Di�rio de Classe</b>                                                         \n';
        sConteudo   += '      </legend>                                                                         \n';
        if (!oInstancia.lProgressaoParcial) {
          
          sConteudo   += '      <div style="text-align:left">';
          sConteudo   += '         <fieldset style="border:0px; border-top:2px groove white">';
          sConteudo   += '           <legend><b>Opc�es</b></legend>';
          sConteudo   += '           <label><b>Mostrar Trocas de Turma:</b></label>';
          sConteudo   += '           <select id="trocaTurma" name="TrocaTurma">';
          sConteudo   += '             <option value="1" selected>N�o</option>';
          sConteudo   += '             <option value="2" >Sim</option>';
          sConteudo   += '           </select>';
          sConteudo   += '         </fielset>';        
          sConteudo   += '      </div>';
	      }
        sConteudo   += '      <div id="ctnTreeView" style="text-align:left;height:100%; width:30%;float:left;"> \n';
        sConteudo   += '      </div>                                                                            \n';
        sConteudo   += '      <div id="ctnListaConteudo" style="padding:0px;height:100%; width:70%;float:right;">           \n';
        sConteudo   += '          <div id="ctnGrid" style="height:90%; width:99%">                              \n';
        sConteudo   += '          </div>                                                                        \n';
        sConteudo   += '      </div>                                                                            \n';
        sConteudo   += '    </fieldset>                                                                         \n';
        sConteudo   += '  </div>                                                                                \n';
        sConteudo   += '</div>                                                                                  \n';
    return sConteudo;
	};
	
	/**
	 * Cria DataGrid
	 */
	var criarDataGridAlunos = function () {
		
		oInstancia.oDataGridAlunos               = new DBGridMultiCabecalho('gridAlunos');
		oInstancia.oDataGridAlunos.nameInstance  = sInstancia+'.oDataGridAlunos';
		oInstancia.oDataGridAlunos.setCellWidth(new Array('8%', '5%', '57%', '20%', '10%'));
		oInstancia.oDataGridAlunos.setHeight($('ctnListaConteudo').getHeight() - 100);
		oInstancia.oDataGridAlunos.setCellAlign(new Array('right', 'right', 'left', 'left', 'center'));
		oInstancia.oDataGridAlunos.setHeader(new Array('Matr�cula', 'Ordem', 'Aluno', 'Situa��o', 'Data Matr�cula'));
		oInstancia.oDataGridAlunos.show($('ctnGrid'));
		$('ctnTreeView').style.height = $('ctnListaConteudo').getHeight() - 60;
	};
	
	/**
	 * Cria a TreeView
	 */
	var criarTreeView = function () {
		
	  oInstancia.oViewArvoreTurma = new DBViewArvoreTurma('ArvoreFiltroLancamento');
	  oInstancia.oViewArvoreTurma.setCheckBox(false);
	  oInstancia.oViewArvoreTurma.setCallBackCliqueTurma(function(oTurma, iEtapa) {
       js_setCallBackVisualizaDados(oTurma, iEtapa);
	  });
    oInstancia.oViewArvoreTurma.lTurmasProgressaoParcial = oInstancia.lProgressaoParcial;
	  oInstancia.oViewArvoreTurma.show($('ctnTreeView'));
    
	};
	
	/**
	 * Busca os dados dos alunos matriculados na turma, para preenchimento da Grid
	 */
	var oTurmaAnterior = '';
	js_setCallBackVisualizaDados = function (oTurma, iEtapa) {
		
		var oParametro  = new Object();
		oParametro.exec = 'getAlunosMatriculados';
		if (oInstancia.lProgressaoParcial) {
		  oParametro.exec = 'getAlunosVinculados';
		};
		oParametro.iCodigoTurma = oTurma.iTurma;
		oParametro.iEtapa       = iEtapa;
		if (!oInstancia.lProgressaoParcial) {
		  oParametro.iMostrarTrocaTurma = $F('trocaTurma');
		}
		if (oTurmaAnterior != "") {
			oTurmaAnterior.select(false);
		}
		oInstancia.iTurmaSelecionada = oTurma.value;
		oInstancia.iEtapaSelecionada = iEtapa;
    oInstancia.iCodigoTurma      = oTurma.iTurma;

		oTurma.select(true);
		oTurmaAnterior = oTurma;
		js_divCarregando("Aguarde, carregando os alunos matriculados na turma.", "msgBox");
		new Ajax.Request('edu4_lancamentoavaliacao.RPC.php',
                     {
                       method:     'post',
                       parameters: 'json='+Object.toJSON(oParametro),
                       onComplete: js_montaGridAlunos
                     }
                    );
		
	}
	
	/**
	 * Monta a Grid com os dados retornados
	 */
	js_montaGridAlunos = function(oResponse) {
		
	  js_removeObj("msgBox");
		var oRetorno                 = eval('('+oResponse.responseText+')');
		var iTamanhoRetorno          = oRetorno.dados.length;
		var iCodigoMatriculaAnterior = '';
		var iCodigoMatriculaAtual    = '';
		var iCodigoMatriculaProxima  = '';
		oInstancia.aMatriculas.length = 0;
		
		oInstancia.oDataGridAlunos.clearAll(true);
		
		if (iTamanhoRetorno == 0) {
			
			alert('N�o h� alunos na turma solicitada');
			return false;
		}
		
		oRetorno.dados.each(function(oAluno, iSeq) {
			
		  iCodigoAluno = oAluno.iCodigo
			var aLinha = new Array();
			aLinha[0]  = oAluno.iMatricula;
			aLinha[1]  = oAluno.iOrdem;
			aLinha[2]  = oAluno.sNome.urlDecode();
			aLinha[3]  = oAluno.sSituacao.urlDecode();
			aLinha[4]  = oAluno.dtDataMatricula;
			
			if (oInstancia.lProgressaoParcial) {
			  
			  aLinha[3]    += oAluno.aDisciplina.implode(", ");
			  iCodigoAluno  = oAluno.iCodigo.implode(",");
			}
			oInstancia.oDataGridAlunos.addRow(aLinha);
			
			oInstancia.aMatriculas.push(oAluno.iCodigo);
			oInstancia.oDataGridAlunos.aRows[iSeq].sEvents += "onClick='js_setCallBackLancamentoAvaliacoes(\""+iCodigoAluno+"\");'";
		});
		oInstancia.oDataGridAlunos.renderRows();
	}
	
	/**
	 * Chama a Grid com as disciplinas do aluno selecionado
	 */
	js_setCallBackLancamentoAvaliacoes = function(iMatricula) {

	  if (oInstancia.lProgressaoParcial) {
	    
      oDisciplinas = new DBViewLancamentoProgressaoParcialAluno("oDisciplinas", 
	                                                              iMatricula, 
	                                                              oInstancia.aMatriculas ,
	                                                              oInstancia.iCodigoTurma,
	                                                              oInstancia.iEtapaSelecionada
	                                                             );
	  } else {
	    oDisciplinas = new DBViewLancamentoAvaliacaoAluno("oDisciplinas", iMatricula, oInstancia.aMatriculas);
	  }
		oDisciplinas.show();
	}
	
	/**
	 * Carrega as fun��es iniciais da tela
	 * renderizarHTML(), criarDataGridAlunos(), criarTreeView()
	 */
	this.show = function ( oElementoDestino ) {
		
		/**
		 * Renderizando HTML
		 */
		var sHtml = renderizarHTML();
		oElementoDestino.innerHTML = sHtml;
		criarDataGridAlunos();
		criarTreeView();
	};
	
	this.setProgressaoParcial = function(lProgressaoParcial) {
	  
	  oInstancia.lProgressaoParcial = lProgressaoParcial;
	} 
};
