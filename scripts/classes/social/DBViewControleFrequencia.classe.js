require_once('estilos/grid.style.css');
require_once('scripts/datagrid.widget.js');
require_once('scripts/widgets/DBGridMultiCabecalho.widget.js');
require_once('scripts/widgets/datagrid/plugins/DBRealcarLinhas.plugin.js');
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');

/**
 * Controle de frequencia para os Cursos Sociais
 * Funcionamento:
 * Básicamente a View é definida pelo combobox de "mês". 
 * É ele que chama os dias de aula do curso que posteiormente chama os alunos do curso.
 * Ao alterar o estado da Grade as informações são salvas em um array
 */
DBViewControleFrequencia = function (iCurso, sCurso, sMinistrante, oNode) {
  
  /**
   * Código do curso
   * @var {interger} iCurso
   */
  this.iCurso = iCurso;
  this.sCurso = sCurso;
  
  /**
   * Nome do Ministrante do curso
   * @var {string} sMinistrante
   */
  this.sMinistrante = sMinistrante;
  
  this.iTamanhoJanela = document.body.getWidth() - 20;
  
  /**
   * Variável para receber a intancia da grid 
   * @var {Object}
   */
  this.oGridFrequencia = {};
  
  /**
   * Valida se lançou falta no mês
   * @var {Bolean}
   */
  this.lFaltaLancada = false;
  
  /**
   * Array com as faltas lançadas
   * @var {Array}
   */
  this.aFaltaLancadas = [];
  
  /**
   * Select com os meses de abrangência do curso
   */
  this.oCboMeses = document.createElement('select');
  this.oCboMeses.id = "mesAbrangente";
  this.oCboMeses.style.width        = '250px';
  
  this.oBtnSalvar          = document.createElement('input');
  this.oBtnSalvar.type     = 'button';
  this.oBtnSalvar.value    = 'Salvar';
  this.oBtnSalvar.name     = 'salvar';
  this.oBtnSalvar.id       = 'btnSalvar';
  this.oBtnSalvar.disabled = true;
  
  
  /**
   * Onde será renderizada a view. 
   * Se vaziu, cria uma janela
   */
  this.view = '';
  if (oNode != null) {
    this.view = oNode;
  }
  
  this.sUrlRPC = 'soc4_cursosocial.RPC.php';
  
};

/**
 * Cria a estrutura da interface
 * Renderiza a Window, montando a estrutura básica (HTML)
 */
DBViewControleFrequencia.prototype.criarWindow = function() {
 
  var oSelf = this;
  
  this.oWindowFreequencia = new windowAux('wndFreequencia','Controle de Frequência', this.iTamanhoJanela);

  this.oWindowFreequencia.setShutDownFunction(function() {
    
    var sMsgAviso = "A grade de presença foi alterada, se alterar o mês sem salvar as informações serão perdidas";
    if (oSelf.lFaltaLancada && !confirm(sMsgAviso)) {
      return false;
    }
    oSelf.oWindowFreequencia.destroy();
  });
  
  
  var sConteudo  = "<div id='ctnControleFrequencia' style='width:98%'> ";
      sConteudo += "  <div id='opcoes'>                                ";
      sConteudo += "    <fieldset id='ctnFiltro' style='width:100%'>   ";
      sConteudo += "      <legend><b>Filtros</b></legend>              ";
      sConteudo += "      <table>                                      ";
      sConteudo += "        <tr>                                       ";
      sConteudo += "          <td class='bold'>Mês: </td>              ";
      sConteudo += "          <td id='filtrosView'></td>               ";
      sConteudo += "        </tr>                                      ";
      sConteudo += "      </table>                                     ";
      sConteudo += "    </fieldset>                                    ";
      sConteudo += "  </div>                                           ";
      sConteudo += "  <div id='gradeAproveitamento'>                   ";
      sConteudo += "    <fieldset style='width:100%'>                  ";
      sConteudo += "      <legend><b>Alunos do Curso</b></legend>      ";
      sConteudo += "      <div id='ctnDiarioClasse'></div>             ";
      sConteudo += "    </fieldset>                                    ";
      sConteudo += "    <center id='ctnSalvar'></center>               ";
      sConteudo += "  </div>                                           ";
      sConteudo += "</div>                                             ";  
  
  this.oWindowFreequencia.setContent(sConteudo);
  
  
  var sMsg  = 'Ministrante: ' + this.sMinistrante + ' Curso: ' +this.iCurso + " - " + this.sCurso;
  
  var sHelpMsgBox  = 'Desmarque o quadro de presença para inserir falta para o Aluno em um dia. ';
    
  if (this.view == "") {
    this.oWindowFreequencia.setContent(sConteudo);
  } else {
    oNode.innerHTML = sConteudo;
  }
  
  this.oMessageBoard = new DBMessageBoard('msgBoardControleFrequencia', 
                                          sMsg,
                                          sHelpMsgBox,
                                          this.oWindowFreequencia.getContentContainer()
                                         );
  if (this.view == "") {
    this.oWindowFreequencia.show();
  } else {
    this.oWindowFreequencia.show(this.oNode);
  }
};


/**
 * Chama as funções responsáveis para renderizar a view
 */
DBViewControleFrequencia.prototype.show = function() {
  
  var oSelf = this;
  this.criarWindow();
  
  $('filtrosView').appendChild(this.oCboMeses);
  $('ctnSalvar').appendChild(this.oBtnSalvar);
  
  $('btnSalvar').onclick = function () {
    oSelf.salvar();
  }

  this.buscaMeses();
};

/**
 * Busca os mese de abangência do curso
 */
DBViewControleFrequencia.prototype.buscaMeses = function () {
  
  var oSelf = this;
  
  var oParametro    = new Object();
  oParametro.exec   = 'getMesesDeAbrangencia';
  oParametro.iCurso = this.iCurso;
  
  new Ajax.Request(this.sUrlRPC,
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: function(oAjax) {
         oSelf.retornoMesesDeAbrangencia(oAjax);
       }
      }
     );
};

/**
 * Monta o combobox "Mês de abrangência"
 */
DBViewControleFrequencia.prototype.retornoMesesDeAbrangencia = function (oAjax) {
  
  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');
  this.aMeses  = oRetorno.aMeses;
  
  this.oCboMeses.options.length = 0;

  for ( var iAno in this.aMeses) {

    if (typeof this.aMeses[iAno] == "function") {
      continue;
    }
    
    for (var iMes in this.aMeses[iAno]) {
      
      var oOption      = document.createElement('option');
      oOption.value    = iMes;
      oOption.setAttribute("ano", iAno);
      
      oOption.innerHTML= iAno + ' -' + this.aMeses[iAno][iMes].urlDecode();
      this.oCboMeses.appendChild(oOption);
      
    }
  }
  

  if (this.oCboMeses.options.length > 0) {
    
    var iAno = $("mesAbrangente").options[$("mesAbrangente").selectedIndex].getAttribute('ano');
    this.buscaDiasMes(iAno, $F("mesAbrangente")); 
  }
  
  this.oCboMeses.onchange = function () {
    
    var iAno = $("mesAbrangente").options[$("mesAbrangente").selectedIndex].getAttribute('ano');
    oSelf.buscaDiasMes(iAno,  $("mesAbrangente").value);
  }
  
};

/**
 * Busca os dias de aula referente ao mês selecionado
 */
DBViewControleFrequencia.prototype.buscaDiasMes = function (iAno, iMes) {
  
  var oSelf = this;
  
  var oParametro    = new Object();
  oParametro.exec   = 'getDiasAulaPorMes';
  oParametro.iCurso = this.iCurso;
  oParametro.iMes   = iMes;
  oParametro.iAno   = iAno;
  
  js_divCarregando("Aguarde, buscando os dias do mês selecionado.", "msgBox");
  new Ajax.Request(this.sUrlRPC,
                   {method:'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: function(oAjax) {
                      oSelf.retornoDiasMes(oAjax);
                    }
                   }
                  );
};

/**
 * Monta/Renderia a grid com os dias do mês selecionado
 */
DBViewControleFrequencia.prototype.retornoDiasMes = function (oAjax) {
  
  js_removeObj('msgBox');
  var oSelf = this;
  
  var oRetorno = eval('('+oAjax.responseText+')');
  this.aDias   = oRetorno.aDias;

  var iNumeroDias = this.aDias.length;
  /**
   * Calcula porcentagem das outras colunas
   */
  var iLarguraColuna = (60/iNumeroDias).toFixed(2);
  
  var aGrupos           = new Array();
  var oGrupoAluno       = new Object();
  oGrupoAluno.descricao = "Dados do Aluno";
  oGrupoAluno.aColunas  = new Array(0);
  aGrupos.push(oGrupoAluno);

  var oGrupoDias        = new Object();
  oGrupoDias.descricao  = "Dias de Aula";
  oGrupoDias.aColunas  = new Array();

  /**
   * Neste laço definimos o quantas colunas de dias vai abranger a coluna "Dias de Aula" 
   */
  this.aDias.each(function (oDia, iInd) {
    oGrupoDias.aColunas.push(iInd+1)
  });
  aGrupos.push(oGrupoDias);
  
  /**
   * Cria cabeçalho da grid
   */
  var aHeader = new Array("Alunos");
  var aWidth  = new Array("40%");
  var aAlign  = new Array("left");
  
  this.aDias.each(function (oDia) {
    
    aHeader.push(oDia.iDia);
    aWidth.push(iLarguraColuna+"%");
    aAlign.push("center");
  });

  delete this.oGridFrequencia;
  $('ctnDiarioClasse').innerHTML = '';
  
  this.oGridFrequencia = new DBGridMultiCabecalho("oGridFrequencia");
  this.oGridFrequencia.setCellWidth(aWidth);
  this.oGridFrequencia.setCellAlign(aAlign);
  this.oGridFrequencia.setHeader(aHeader);
  
  /**
   * Adiciona os grupos de cabeçalho
   */
  aGrupos.each(function(oGrupo, iSeq) {
    oSelf.oGridFrequencia.adicionarGrupo(oGrupo.descricao, oGrupo.aColunas, '0');
  });
  this.oGridFrequencia.setHeight(300);
  
  this.oGridFrequencia.show($('ctnDiarioClasse'));
 
  this.buscaAlunos($F("mesAbrangente"));
  
};

/**
 * Busca os alunos do curso
 */
DBViewControleFrequencia.prototype.buscaAlunos = function (iMes) {
 
  var oSelf = this;
  
  var oParametro    = new Object();
  oParametro.exec   = 'getAlunosCurso';
  oParametro.iCurso = this.iCurso;
  
  js_divCarregando("Aguarde, buscando os Alunos.", "msgBox2");
  new Ajax.Request(this.sUrlRPC,
                   {method:'post',
                    parameters: 'json='+Object.toJSON(oParametro),
                    onComplete: function(oAjax) {
                      oSelf.retornoAlunos(oAjax);
                    }
                   }
                  );
};

/**
 * Carrega os alunos do curso na grid
 * Valida se o aluno tem falta em um dia do mês selecionado e marca na grade
 */
DBViewControleFrequencia.prototype.retornoAlunos = function (oAjax) {
  
  js_removeObj('msgBox2');
  var oRetorno = eval('('+oAjax.responseText+')');
  
  var oSelf = this;
  
  this.oGridFrequencia.clearAll(true);
  
  oRetorno.aAlunos.each( function (oAluno) {
    
    var aLinha = new Array();
    aLinha.push(oAluno.sNome.urlDecode());
    
    oSelf.aDias.each( function (oDia) {
      
      var oCheckBox  = document.createElement('input');
      oCheckBox.type = "checkbox";
      oCheckBox.addClassName("frequenciaDia");
      oCheckBox.setAttribute("matricula", oAluno.iMatricula);
      oCheckBox.setAttribute("cidadao", oAluno.iCidadao);
      oCheckBox.setAttribute("value", oDia.iCursoAula);
      oCheckBox.setAttribute("checked", true);
      
      /**
       * Validamos se o Aluno possui falta no Dia
       */
      if (oAluno.aAusencias.length != 0) {
        
        oAluno.aAusencias.each( function (oAusencia) {
          
          if (oAusencia.iCursoAula == oDia.iCursoAula) {
            
            oCheckBox.removeAttribute("checked");
            return;
          }
        });
      }
      
      aLinha.push(oCheckBox.outerHTML);  
    }); 
    
    oSelf.oGridFrequencia.addRow(aLinha);
  });
  oSelf.oGridFrequencia.renderRows();

  /**
   * Adiciona as funções nos checkbox
   */
  $$('.frequenciaDia').each( function (oElemento) {
    
    oElemento.onchange = function () {
      oSelf.adicionaFalta(this);
    };
  });
  
  oSelf.oGridFrequencia.realcarLinhas();
  
  /**
   * No carregar inicial da VIEW inicializamos o {this.aFaltaLancadas} 
   * Se o array já tem valores, siguinifica que a grade já foi alterada e ou alterado o mês, neste caso 
   * consistenciamos as informações da grid (tela) com a do usuário 
   */
  if (this.aFaltaLancadas.length == 0) {
    this.criaArrayDeFaltasPorCidadao(oRetorno.aAlunos);
  } else {
    this.marcaGridConformeEdicao();
  }
};

/**
 * Monta o Array de faltas para os cidadãos que já possuem faltas lançadas
 * Essa função só inicializa o array de faltas quando o curso já possui alunos com ausencias lançadas
 * @param Array
 */
DBViewControleFrequencia.prototype.criaArrayDeFaltasPorCidadao = function (aAlunos) {
  
  var oSelf = this;
  aAlunos.each( function (oAluno) {
    
    var oAlunoFalta        = new Object();
    oAlunoFalta.iMatricula = oAluno.iMatricula;
    oAlunoFalta.aFaltas    = new Array();
    
    if (oAluno.aAusencias.length == 0) {
      return;
    }
    oAluno.aAusencias.each( function (oAusencia) {
      oAlunoFalta.aFaltas.push(oAusencia.iCursoAula);  
    }); 

    oSelf.aFaltaLancadas.push(oAlunoFalta);
  });
  
};


/**
 * Adiciona em array, as faltas lançadas na grade.
 * Se um Aluno/Cidadão já possui falta e for marcado como presença, a falta é removida do array
 * @param {Element} 
 */
DBViewControleFrequencia.prototype.adicionaFalta = function (oCheckBox) {
  
  var oSelf = this;
  
  if (this.aFaltaLancadas.length > 0) {
    
    var lAchoCidadao   = false;
    this.aFaltaLancadas.each( function (oAluno, iIndice) { 
      
      if (oAluno.iMatricula == oCheckBox.getAttribute("matricula")) {
        
        if (!oCheckBox.checked) {
          
          oAluno.aFaltas.push(oCheckBox.value);
        } else {
          
          var iRemoverFalta = null;
          oAluno.aFaltas.each( function (iValor, iKey) {
            
            if (iValor == oCheckBox.value) {
              iRemoverFalta = iKey;
            }
          }); 
          oAluno.aFaltas.splice(iRemoverFalta, 1);
        }
        lAchoCidadao = true;
      } 
      
    });
    
    if (!lAchoCidadao) {
      
      var oAlunoFalta        = new Object();
      oAlunoFalta.iMatricula = oCheckBox.getAttribute("matricula");
      oAlunoFalta.aFaltas    = new Array(oCheckBox.value);
      oSelf.aFaltaLancadas.push(oAlunoFalta);
    }
    
  } else {
    
    var oAlunoFalta        = new Object();
    oAlunoFalta.iMatricula = oCheckBox.getAttribute("matricula");
    oAlunoFalta.aFaltas    = new Array(oCheckBox.value);
    this.aFaltaLancadas.push(oAlunoFalta);
  }
  
  /**
   * Libera botão após edição
   */
  if (this.aFaltaLancadas.length > 0) {
    
    $('btnSalvar').removeAttribute("disabled");
    this.lFaltaLancada = true;
  } 
};

/**
 * salva as faltas lançadas
 */
DBViewControleFrequencia.prototype.salvar = function () {
  
  var oSelf                = this;
  var oParametro           = new Object();
  oParametro.exec          = 'salvarFaltas';
  oParametro.iCurso        = this.iCurso;
  oParametro.aAlunosFaltas = this.aFaltaLancadas;
  
  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         oSelf.retornoSalvarFaltas(oAjax);
                       }

  js_divCarregando("Aguarde, salvando Grade de Frequência.", "msgBox");
  new Ajax.Request(this.sUrlRPC, oObjeto);
};

/**
 * Retorno do salvar as frequencias
 */
DBViewControleFrequencia.prototype.retornoSalvarFaltas = function (oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')'); 
  
  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {
    
    this.lFaltaLancada  = false;
    this.aFaltaLancadas = new Array;
    $('btnSalvar').setAttribute("disabled", "disabled");
    this.buscaMeses();
  }
 
};

/**
 * Quando a grade já foi editada, e alteramos o mês selecionado, ao voltar no mês editado, a edição era 
 * perdida (somente visualmente) e a alteração se mantinha no array. 
 * Esta função percorre a grade, comparando o estado com o array. 
 */
DBViewControleFrequencia.prototype.marcaGridConformeEdicao = function () {
  
  var oSelf = this;
  $$('.frequenciaDia').each( function (oElemento) {
    
    oSelf.aFaltaLancadas.each( function (oAluno) {
   
      // Valida se a matricula do aluno é a mesma do elemento (checkbox)
      if (oAluno.iMatricula == oElemento.getAttribute("matricula")) {
        
        oElemento.setAttribute("checked", true);
        oAluno.aFaltas.each( function (iFalta) {
            
          if (oElemento.value == iFalta) {
            oElemento.removeAttribute("checked");
          }
          
        });
      }
    }); 
    
  }); 
};
