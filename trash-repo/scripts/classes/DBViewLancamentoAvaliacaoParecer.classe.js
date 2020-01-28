require_once("scripts/classes/DBViewLancamentoAvaliacao/PeriodoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/AlunoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/DisciplinaTurma.classe.js");

DBViewLancamentoAvaliacaoParecer = function(sInstancia, iTurma, iCodigoRegencia, oWindowParent, sRPCAlterado) {


   this.sInstancia = sInstancia;
   this.iMatricula = '';
   this.iTurma     = iTurma;
   this.sRPC       = "edu4_lancamentoavaliacao.RPC.php";
   if (sRPCAlterado && sRPCAlterado != '') {
     this.sRPC = sRPCAlterado;   
   }
   
   this.metodoSalvarRpc = 'salvarParecer';
   
   this.getParecerRpc = 'getParecer';
   
   this.iCodigoPeriodo = ''; 
   this.iCodigoEtapa   = '';
   
   this.lMostrarPesquisaAluno = false;
   
   this.lMostrarPesquisaPeriodo = false;
   
   this.oAlunoTurma   = null;
   this.oPeriodoTurma = null;
   
   this.iCodigoRegencia = iCodigoRegencia;
   
   this.lSalvar          = false;
   this.lProfessorLogado = false;

   this.oCallback = function () {
     return true;
   }; 
   /**
    * Lista de disciplinas da turma
    */
   this.oDisciplinasTurma = null;
   /**
    * Array com os parecers padronizados
    */
   this.aParecer = new Array();
   var me = this;
    
    var iWidth  = document.body.getWidth()/1.3;
    var iHeight = document.body.getHeight()/1.2;
    if (oWindowParent != null) {
      
      iWidth  = oWindowParent.getWidth()/1.2;
      iHeight = oWindowParent.getHeight()/1.2;
    }
    this.oWindowParecer = new windowAux("wndParecer", "Lan�amento de Pareceres", iWidth, iHeight);
    this.oWindowParecer.setShutDownFunction(function () {
      me.oWindowParecer.destroy();
    });
    var sConteudo  = "<div id='ctnparecer'>";
    sConteudo     += "  <fieldset id='ctnFieldSetAlunos' style='display:none'>";
    sConteudo     += "    <Legend><b>Alunos</b></legend>";
    sConteudo     += "    <table>";
    sConteudo     += "     <tr id='ctnListaAlunos' style='display:none'>";
    sConteudo     += "      <td>";
    sConteudo     += "       <b>Aluno:</b>";
    sConteudo     += "      </td>";
    sConteudo     += "      <td id='tdAluno'>";
    sConteudo     += "      </td>";
    sConteudo     += "     </tr>";
    sConteudo     += "     <tr id='ctnListaPeriodos' style='display:none'>";
    sConteudo     += "      <td>";
    sConteudo     += "       <b>Per�odo:</b>";
    sConteudo     += "      </td> ";
    sConteudo     += "      <td id='tdPeriodo'>";
    sConteudo     += "      </td>";
    sConteudo     += "     </tr>";
    sConteudo     += "    </table>";
    sConteudo     += "  </fieldset>";
    sConteudo     += "  <fieldset>";
    sConteudo     += "    <legend><b>Parecer Padronizado</b></legend>";
    sConteudo     += "      <div style='width:100%'>";
    sConteudo     += "        <table> ";
    sConteudo     += "          <tr> ";
    sConteudo     += "            <td> ";
    sConteudo     += "              <a href='#' onclick='"+me.sInstancia+".consultarParecer(true);return false;'>";
    sConteudo     += "                <b>Parecer:</b></a>";
    sConteudo     += "            </td> ";
    sConteudo     += "            <td>  ";
    sConteudo     += "              <span id='ctnTxtCodigoParecer'></span>";
    sConteudo     += "              <span id='ctnTxtDescricaoParecer'></span>";
    sConteudo     += "            </td> ";
    sConteudo     += "          </tr> ";
    sConteudo     += "          <tr> ";
    sConteudo     += "            <td> ";
    sConteudo     += "              <b>Legenda:</b> ";
    sConteudo     += "            </td> ";
    sConteudo     += "            <td id='ctnComboLegenda'>";
    sConteudo     += "            </td>";
    sConteudo     += "          </tr> ";
    sConteudo     += "          <tr> ";
    sConteudo     += "            <td>&nbsp;</td> ";
    sConteudo     += "           <td> ";
    sConteudo     += "              <input type='button' id='btnSalvarParecerPadronizado' value='Adicionar Parecer'>";
    sConteudo     += "            </td>";
    sConteudo     += "          </tr>";
    sConteudo     += "        </table> ";
    sConteudo     += "        <fieldset style='border:0px;border-top:2px groove white'>";
    sConteudo     += "          <legend><b>Pareceres Lan�ados</b></legend>";
    sConteudo     += "           <div style='width:100%' id='ctnGridParecer'>";
    sConteudo     += "           </div>";
    sConteudo     += "        </fieldset>";
    sConteudo     += "      </div>";
    sConteudo     += "  </fieldset>";
    sConteudo     += "  <fieldset>";
    sConteudo     += "    <legend>";
    sConteudo     += "      <b>Parecer Descritivo</b>";
    sConteudo     += "    </legend>";
    sConteudo     += "    <textarea style='width:100%;' id='parecer' rows='4'></textarea>";
    sConteudo     += "  </fieldset>";
    
    sConteudo     += "  <fieldset id='ctnDisciplinasTurmas' style='display:none'>";
    sConteudo     += "    <Legend id='legendDisciplinasTurmas'><b>Outras Disciplinas para o parecer</b></legend>";
    sConteudo     += "  </fieldset>";
    
    
    sConteudo     += "  <center>";
    sConteudo     += "    <input type='button' id='btnSalvarPareceres' value='Salvar Parecer' />";
    sConteudo     += "  </center>";
    sConteudo     += "</div>"; 
    
    this.oWindowParecer.setContent(sConteudo);
    this.oMessageBoard = new DBMessageBoard('msgBoardLancamentoParecer', 
                                          'Lan�amento de Avalia��o por Parecer', 
                                          '',
                                           this.oWindowParecer.getContentContainer()
                                          );    
    if (oWindowParent != null) {
      this.oWindowParecer.setChildOf(oWindowParent);
    }
    
    this.oTxtCodigoParecer = new DBTextField('oTxtCodigoParecer', this.sInstancia+'.oTxtCodigoParecer','', 10);
    this.oTxtCodigoParecer.addEvent("onChange",";"+this.sInstancia+".consultarParecer(false);");
    this.oTxtCodigoParecer.show($('ctnTxtCodigoParecer'));
    this.oTxtCodigoParecer.setReadOnly(false);
    
    
    this.oTxtDescricaoParecer = new DBTextField('oTxtDescricaoAcordo', this.sInstancia+'.oTxtDescricaoParecer','', 50);
    this.oTxtDescricaoParecer.show($('ctnTxtDescricaoParecer'));
    this.oTxtDescricaoParecer.setReadOnly(true);
    
    this.oCboLegendas  = new DBComboBox('oCboLegendas', this.sInstancia+'.oCboLegendas', null, '100%');
    this.oCboLegendas.show($('ctnComboLegenda')); 
    this.oCboLegendas.addItem('', ' ');
    
    $('oTxtCodigoParecer').focus();
    $('oTxtDescricaoAcordo').disabled    = true;
    $('oTxtDescricaoAcordo').style.color = '#000';
    
    this.oGridParecer               = new DBGrid('gridParecer');
    this.oGridParecer.sNameInstance = this.sInstancia+'.oGridParecer';
    this.oGridParecer.setHeight(80);
    this.oGridParecer.setHeader(new Array('C�digo', 'Parecer', 'A��o'));
    this.oGridParecer.setCellWidth(new Array('5%', '85%', '10%'));
    
    $('btnSalvarParecerPadronizado').observe('click',  function() {
        me.adicionarParecer();
    });
    me.getLegendas();    
    
    $('btnSalvarPareceres').observe('click', function() {
      
      me.salvarParecer();
    });
    
};

DBViewLancamentoAvaliacaoParecer.prototype.setCallback  = function (oFunction) {
  this.oCallback  = oFunction;
};

DBViewLancamentoAvaliacaoParecer.prototype.getWindow = function() {
  return this.oWindowParecer;
};

/**
 * Define o matr�cula que ser� lan�ada o parecer 
 * @param iMatricula C�digo da matricula
 */
DBViewLancamentoAvaliacaoParecer.prototype.setMatricula = function (iMatricula) {
  this.iMatricula = iMatricula;
};

/**
 * Define o codigo do periodo em que o parecer ser� lan�ado.
 * @param iCodigoPeriodo
 */
DBViewLancamentoAvaliacaoParecer.prototype.setCodigoPeriodo = function (iCodigoPeriodo) {
  this.iCodigoPeriodo = iCodigoPeriodo;
};

/**
 * Renderiza os pareceres padronizados.
 */
DBViewLancamentoAvaliacaoParecer.prototype.prencherParecerPadronizados = function() {
  
  var me = this; 
  me.oGridParecer.clearAll(true);
  me.aParecer.each(function(oParecer, iSeq) {
      
    var aLinha = new Array();
    aLinha[0]  = oParecer.iCodigo;
    aLinha[1]  = oParecer.sDescricao;
    aLinha[2]  = "<input type='button' value='Excluir' onclick='"+me.sInstancia+".excluirParecer("+iSeq+")'>";
    me.oGridParecer.addRow(aLinha); 
  });
  me.oGridParecer.renderRows();
};

/**
 * Retorna os pareceres dos alunos.
 */
DBViewLancamentoAvaliacaoParecer.prototype.getParecer =  function () {  
  
  var me = this;
  
  me.oGridParecer.clearAll(true);
  me.aParecer = new Array();
  
  var oParametro        = new Object();
  oParametro.exec       = this.getParecerRpc;
  oParametro.iMatricula = me.iMatricula;
  oParametro.iRegencia  = me.iCodigoRegencia;
  oParametro.iPeriodo   = me.iCodigoPeriodo;
  js_divCarregando('Aguarde, buscando dados dos pareceres', 'msgBox');
  var oJson        = new Object();
  oJson.method     = 'post';
  oJson.parameters = 'json='+Object.toJSON(oParametro);
  oJson.onComplete = function (oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno       = eval("("+oResponse.responseText+")");
    $('parecer').value = oRetorno.sParecer.urlDecode();
    
    if (oRetorno.sParecerPadronizado.urlDecode() != '') {
      
      var aPartesParecerPadronizado = oRetorno.sParecerPadronizado.urlDecode().split("**");
      
      aPartesParecerPadronizado.each(function(sPartePadronizada, iSeq) {
        
        aParteParecer       = sPartePadronizada.split("-");
        iTotalParteParecer  = aParteParecer.length;
        
        var oParecer            = new Object();
            oParecer.iCodigo    = aParteParecer[0].trim();

        var sDescricao = aParteParecer[1];
        
        if (iTotalParteParecer > 2) {
          
          for (iContador = 2; iContador < iTotalParteParecer; iContador++) {
            sDescricao += "-"+aParteParecer[iContador];
          }
        }
        
        oParecer.sDescricao = sDescricao;
        me.aParecer.push(oParecer); 
      });
      me.prencherParecerPadronizados(); 
    }
  };
  new Ajax.Request(me.sRPC, oJson);
};

/**
 * Abre a lookup de pesquisa dos pareceres.
 * @param lShow
 */
DBViewLancamentoAvaliacaoParecer.prototype.consultarParecer = function (lShow) {

  if (lShow) {
     
    js_OpenJanelaIframe('',
                        'db_iframe_parecer001', 
                        'func_parecerdiario.php?funcao_js=parent.'+this.sInstancia+'.preencherParecer|'+
                        'ed92_i_sequencial|ed92_c_descr&turma='+this.iTurma, 
                        'Pesquisar Pareceres Padronizados',
                        true
                        );
                        
    $('Jandb_iframe_parecer001').style.zIndex=10000;                    
  } else {
   
    if (this.oTxtCodigoParecer.getValue() != '') {
       
      js_OpenJanelaIframe('', 
                          'db_iframe_parecer001',
                          'func_parecerdiario.php?pesquisa_chave='+this.oTxtCodigoParecer.getValue()+
                          '&funcao_js=parent.'+this.sInstancia+'.preencherParecer&turma='+this.iTurma,
                          'Pesquisar Pareceres Padronizados',
                          false
                          );
    } else {
      oTxtDescricaoParecer.setValue('');
    }
  }
};

/**
 * Retorna as legendas cadastradas para as escolas.
 */
DBViewLancamentoAvaliacaoParecer.prototype.getLegendas = function() {
  
  var me = this;
  
  var oParametros        = new Object();
  oParametros.exec       = 'getLegendasParecer';
  
  var oJson        = new Object();
  oJson.method     = 'post';
  oJson.parameters = 'json='+Object.toJSON(oParametros);
  oJson.onComplete = function (oResponse) {
  
    var oRetorno = eval("("+oResponse.responseText+")");
    oRetorno.aLegendas.each(function(oLegenda, iSeq) {
      me.oCboLegendas.addItem(oLegenda.codigo, oLegenda.descricao.urlDecode());
    });
    
    if (oRetorno.aLegendas.length == 0) {
      
      me.oCboLegendas.setDisable();
      
    }
  };
  oJson.asynchronous = false;
  new Ajax.Request(me.sRPC, oJson);
};

/**
 * Preenche parecer
 */
DBViewLancamentoAvaliacaoParecer.prototype.preencherParecer = function () {

  var me = this;
  
  if (typeof(arguments[1]) == "boolean") {
   
    if (arguments[1]) {
    
      me.oTxtDescricaoParecer.setValue( 'Chave('+me.oTxtCodigoParecer.getValue()+') n�o encontrada.'); 
      me.oTxtCodigoParecer.setValue(''); 
      $("oTxtCodigoParecer").focus();
    } else {
      me.oTxtDescricaoParecer.setValue(arguments[0]); 
    }
  } else {
  
    me.oTxtCodigoParecer.setValue(arguments[0]); 
    me.oTxtDescricaoParecer.setValue(arguments[1]); 
    db_iframe_parecer001.hide();  
  }
};

/**
 * Adiciona parecer
 * @returns {Boolean}
 */
DBViewLancamentoAvaliacaoParecer.prototype.adicionarParecer = function() {
  
  var me = this;
  if (me.oTxtCodigoParecer.getValue() == "") {
    return false;
  } 
  
  var lParecerJaAdicionado = false;
  me.aParecer.each(function(oParecer, iSeq) {
  
    if (oParecer.iCodigo == me.oTxtCodigoParecer.getValue()) {
      
      alert('Parecer '+oParecer.iCodigo+' - '+oParecer.sDescricao+' j� informado.');
      lParecerJaAdicionado = true;
      throw $break;
    }
  });
  
  if (!lParecerJaAdicionado) {
  
    var oParecer        = new Object();
    oParecer.iCodigo    = me.oTxtCodigoParecer.getValue();
    oParecer.sDescricao = me.oTxtDescricaoParecer.getValue();
    if (me.oCboLegendas.getValue() != "") {
     oParecer.sDescricao += " => "+me.oCboLegendas.getLabel();
    }
    me.aParecer.push(oParecer);
    this.lSalvar = true;
  }
  me.prencherParecerPadronizados();
  
  me.oTxtCodigoParecer.setValue('');
  me.oTxtDescricaoParecer.setValue('');
  me.oCboLegendas.setValue('');
  $('oTxtCodigoParecer').focus();
  
};

/**
 * Exclui o parecer
 */
DBViewLancamentoAvaliacaoParecer.prototype.excluirParecer = function(iParecer) {
  
  this.aParecer.splice(iParecer, 1);
  this.prencherParecerPadronizados();
  this.lSalvar = true;
};

/**
 * Salva o parecer
 */
DBViewLancamentoAvaliacaoParecer.prototype.salvarParecer = function() {
  
  var me = this;
  
  var oParametro        = new Object();
  oParametro.exec       = me.metodoSalvarRpc;
  oParametro.sParecer   = encodeURIComponent(tagString($F('parecer')));
  oParametro.iMatricula = me.iMatricula;
  oParametro.iRegencia  = me.iCodigoRegencia;
  if (me.metodoSalvarRpc == 'salvarParecerComplementar') {

    if (empty(me.iMatricula)) {
      
      alert('Antes de salvar o parecer, escolha um aluno.');
      return false;
    }
    
    if (empty(me.iCodigoPeriodo)) {
      
      alert('Antes de salvar o parecer, escolha um per�odo de avalia��o.');
      return false;
      
    }
    
    delete oParametro.iRegencia;
    oParametro.aRegencia = [me.iCodigoRegencia];
    if (this.oDisciplinasTurma != null) {
     
      var aDiscipliasSelecionadas = this.oDisciplinasTurma.getSelecionados();
      if (aDiscipliasSelecionadas.length > 0) {
        oParametro.aRegencia = oParametro.aRegencia.concat(aDiscipliasSelecionadas);
      }
    }    
  }
  oParametro.iPeriodo            = me.iCodigoPeriodo;
  oParametro.sParecerPadronizado = '';
  var sAgrupador                 = ''; 
  js_divCarregando('Aguarde, adicionando dados dos pareceres', 'msgBox');
  me.aParecer.each(function(oParecer, iSeq) {
     
     oParametro.sParecerPadronizado += sAgrupador +oParecer.iCodigo+" - "+oParecer.sDescricao;
     sAgrupador = " ** ";
  }); 
  oParametro.sParecerPadronizado = encodeURIComponent(tagString(oParametro.sParecerPadronizado));
  var oJson        = new Object();
  oJson.method     = 'post';
  oJson.parameters = 'json='+Object.toJSON(oParametro);
  oJson.onComplete = function (oResponse) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {
      me.oCallback(me);
    }
  };
  new Ajax.Request(me.sRPC, oJson);
};

/**
 * Define o codigo da etapa da turma
 * @param iCodigoEtapa Codigo da etapa
 */
DBViewLancamentoAvaliacaoParecer.prototype.setEtapa = function(iCodigoEtapa) {
  
  this.iCodigoEtapa = iCodigoEtapa;
};
/**
 * Renderiza os dados na tela.this.lProfessorLogado
 */
DBViewLancamentoAvaliacaoParecer.prototype.show = function () {
  
  this.oWindowParecer.show();
  this.oGridParecer.show($('ctnGridParecer'));
  
  if (!this.lMostrarPesquisaAluno) {    
    this.getParecer();
  }
  if (this.lMostrarPesquisaAluno || this.lMostrarPesquisaPeriodo) {
    
    $('ctnDisciplinasTurmas').style.display = '';
    this.oDisciplinasTurma = new DBViewAvaliacao.DisciplinaTurma(this.iTurma, this.iCodigoEtapa, this.lProfessorLogado);
    this.oDisciplinasTurma.naoListarAsRegencias([this.iCodigoRegencia]);
    this.oDisciplinasTurma.show($('ctnDisciplinasTurmas'));
  }
};

/**
 * Mostra os dados para pesquisar os alunos e per�odos para lan�ar pareceres.
 * @param lMostrarAluno
 */
DBViewLancamentoAvaliacaoParecer.prototype.mostrarPesquisaAluno = function (lMostrarAluno) {
  
  this.lMostrarPesquisaAluno = lMostrarAluno;
  var sDisplay               = 'none';  
  if (lMostrarAluno) {
    
    sDisplay             = '';
    this.metodoSalvarRpc = 'salvarParecerComplementar';
    this.getParecerRpc   = 'getParecerComplementar';
    
    if (this.oAlunoTurma == null) {
      
      this.oAlunoTurma   = new DBViewAvaliacao.AlunoTurma(this.iTurma, this.iCodigoEtapa, false);
      this.oAlunoTurma.show($('tdAluno'));
      this.oAlunoTurma.onChangeCallBack(function() {
        this.getParecerAluno();
      } , this); 
    }
  }
  $('ctnListaAlunos').style.display    = sDisplay;
  $('ctnFieldSetAlunos').style.display = sDisplay;
}

DBViewLancamentoAvaliacaoParecer.prototype.mostrarPesquisaPeriodo = function (lMostrarPeriodo) {
  
  this.lMostrarPesquisaPeriodo = lMostrarPeriodo;
  var sDisplay                 = 'none';  
  if (this.lMostrarPesquisaPeriodo) {
    
    sDisplay             = '';
    this.metodoSalvarRpc = 'salvarParecerComplementar';
    this.getParecerRpc   = 'getParecerComplementar';
    this.oPeriodoTurma   = new DBViewAvaliacao.PeriodoTurma(this.iTurma, this.iCodigoEtapa, false, false);
    this.oPeriodoTurma.show($('tdPeriodo'));
    this.oPeriodoTurma.onChangeCallBack(function() {
      this.getParecerAluno();
    } , this);
  }
  $('ctnListaPeriodos').style.display  = sDisplay;
  $('ctnFieldSetAlunos').style.display = sDisplay;
}
/**
 * Pesquisa o parecer do aluno no periodo
 */
DBViewLancamentoAvaliacaoParecer.prototype.getParecerAluno = function() {
  
  if (this.lMostrarPesquisaAluno) {
    this.iMatricula  = this.oAlunoTurma.getSelecionados()[0]; 
  }
  this.iCodigoPeriodo = this.oPeriodoTurma.getOrdemSelecionados()[0];
  
  if (!js_empty(this.iMatricula) != '' && !js_empty(this.iCodigoPeriodo)) {
    this.getParecer();
  }
  
};

/**
 * Seta se o usuario logado eh um professor ou nao
 * @param boolean lProfessorLogado
 */
DBViewLancamentoAvaliacaoParecer.prototype.setProfessorLogado = function(lProfessorLogado) {
  
  this.lProfessorLogado = lProfessorLogado;
};