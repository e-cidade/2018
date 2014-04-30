function filtroOrcamento(id) {

 this.aFiltros      = ["orgao","unidade","funcao", "subfuncao","programa","projativ", "elemento", "recurso"];
 this.id            = id;
 var me             = this;
 this.data          = new Object();
 var iWidth         = document.width-20;
 var iHeight        = document.body.scrollHeight-30;
 this.sFiltroAtivo  = '';
 this.sFiltroPadrao = "orgao";
 this.window        = new windowAux("windowFiltros"+this.id,"Filtros Orcamento", iWidth, iHeight);
 
 var sConteudo  = "<table width='100%'>";
     sConteudo += "  <tr>";
     sConteudo += "    <td>";
     sConteudo += "      <fieldset><legend>Filtros</legend>";
     sConteudo += "        <table>";
     sConteudo += "          <tr>";
     sConteudo += "            <td>";     
     sConteudo += "            <input id='btnMarca' type='button' value='Marcar' onclick='js_marca_todos(true);return false' >";     
     sConteudo += "            <input id='btnDesmarca' type='button' value='Desmarcar' onclick='js_marca_todos(true);return false' >";     
     sConteudo += "            </td>";
     sConteudo += "          </tr>";
     sConteudo += "          <tr>";
     sConteudo += "            <td style='padding:0px;margin:0'>";     
     sConteudo += "            <input id='btnOrgao'     type='button' value='Orgao' style='display:none'>";     
     sConteudo += "            <input id='btnUnidade'   type='button' value='Unidade'  style='display:none'>";     
     sConteudo += "            <input id='btnFuncao'    type='button' value='Função' style='display:none'>";     
     sConteudo += "            <input id='btnSubfuncao' type='button' value='Subfunção' style='display:none'>";     
     sConteudo += "            <input id='btnPrograma'  type='button' value='Programa' style='display:none'>";     
     sConteudo += "            <input id='btnProjativ'  type='button' value='Projeto/Atividade' style='display:none'>";     
     sConteudo += "            <input id='btnElemento'  type='button' value='Elemento' style='display:none'>";     
     sConteudo += "            <input id='btnRecurso'   type='button' value='Recurso'  style='display:none'>";     
     sConteudo += "            </td>";
     sConteudo += "          </tr>";
     sConteudo += "        <table>";
     sConteudo += "      </fieldset>";
     sConteudo += "    </td>";
     sConteudo += "  <tr>";
     sConteudo += "</table>";
     sConteudo += "</fieldset>";
     sConteudo += "<fieldset>";
     sConteudo += "<div id='dadosorgao' class='filtro"+id+"' style='display:none;background-color:white'>";
     sConteudo += "</div>";
     sConteudo += "<div id='dadosunidade' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadosfuncao' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadossubfuncao' class='filtro"+id+"' style='display:none;width:99%background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadosprograma' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadosprojativ' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadoselemento' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "<div id='dadosrecurso' class='filtro"+id+"' style='display:none;width:99%;background-color:white'>&nbsp;";
     sConteudo += "</div>";
     sConteudo += "</fieldset>";
     sConteudo += "<center>";
     sConteudo += "<input type='button' id='btnSalvar' style='display:none' value='Salvar'>";
     sConteudo += "</center>";
     
  this.window.setContent(sConteudo);   
  /**
   *Pesquisa os filtros disponiveis
   */
  this.getFiltros = function () {
    
     var oParam  = new Object();
     oParam.exec = "getDadosOrcamento";
     var sUrl    = 'con4_filtroOrcamento.RPC.php';
     var oAjax   = new Ajax.Request(
                                   sUrl, 
                                   {
                                   method    : 'post', 
                                   parameters: 'json='+Object.toJSON(oParam), 
                                   onComplete: me.retornoGetFiltros
                                  }
                               );       
  }
  
  
  this.retornoGetFiltros = function (oAjax) {
    
    var oRetorno = eval("("+oAjax.responseText+")");
    for (var iFiltros = 0; iFiltros < me.aFiltros.length; iFiltros++ ) {
      
      if ($('btn'+me.aFiltros[iFiltros].ucFirst())) {
        $('btn'+me.aFiltros[iFiltros].ucFirst()).style.display  = '';
      }
      if (eval("oRetorno."+me.aFiltros[iFiltros]+".length > 0")) {

        with (eval("$('dados"+me.aFiltros[iFiltros]+"')")) {
        
          //style.display='';
          var sTabela  = '';
          sTabela += "<table cellspacing='0' style='border:2px inset white' cellpadding='0' width='100%'>";
          sTabela += "<tr>"; 
          sTabela += "<td colspan='10' class='table_header'>";
          sTabela += "  <select id='operador"+me.aFiltros[iFiltros]+"' style='width:100%'>"; 
          sTabela += "    <option value='in' >Contendo</option>"; 
          sTabela += "    <option value='notin'>não Contendo</option>"; 
          sTabela += "  </select>"; 
          sTabela += "</td>"; 
          sTabela += "</tr>"; 
          sTabela += "  <tr>";
          sTabela += "    <td class='table_header' colspan='5'><b>"+me.aFiltros[iFiltros].ucFirst()+"</b></td>";
          sTabela += "  </tr>";
          sTabela += "  <tr>";
          sTabela += "    <td class='table_header' width='17px'>";
          sTabela += "      &nbsp;";
          sTabela += "    </td>"
          if (me.aFiltros[iFiltros] == "unidade") {
          
            sTabela += "    <td width='5%' class='table_header'>";
            sTabela += "      <b>Orgao</b>";
            sTabela += "    </td>";
          }
          sTabela += "    <td width='5%' class='table_header'>";
          sTabela += "      <b>"+me.aFiltros[iFiltros].ucFirst()+"</b>";
          sTabela += "    </td>";
          sTabela += "    <td class='table_header'>";
          sTabela += "      <b>Descrição</b>";
          sTabela += "    </td>";
          sTabela += "    <td style='width:17px' class='table_header'>";
          sTabela += "      &nbsp;";
          sTabela += "    </td>";
          sTabela += "  </tr>";
          sTabela += "<tbody style='height:"+(iHeight/2.5)+"px;overflow: scroll;overflow-x:hidden;background-color: white;'>";
          var lTemValor = eval("typeof(me.data."+me.aFiltros[iFiltros]+") != 'undefined'");
          iTotal     = 0;
          if (lTemValor) {
            iTotal = eval("me.data."+me.aFiltros[iFiltros]+".valor.length");
          }
          for (var i = 0; i < eval("oRetorno."+me.aFiltros[iFiltros]+".length"); i++) {
                    
            with (eval("oRetorno."+me.aFiltros[iFiltros]+"[i]")) {
            
              sTabela += "  <tr style='height:1em'>";
              if (me.aFiltros[iFiltros] == "unidade") {
                var sIdLinha = orgao+"-"+eval(me.aFiltros[iFiltros]);
                var sSelected = '';
                if (lTemValor) {
	                if (js_search_in_array(eval("me.data."+me.aFiltros[iFiltros]+".valor"),orgao+"-"+eval(me.aFiltros[iFiltros]))) {
	                  sSelected = ' checked ';
	                }
                }
                
                sTabela += " <td class='linhagrid'><input class='checkbox"+me.aFiltros[iFiltros]+"' "+sSelected; 
                sTabela += "       onclick='filtroOrcamento.somaFiltros(\""+me.aFiltros[iFiltros]+"\")';";
                sTabela += "                  type='checkbox' id='"+sIdLinha+"'></td>"; 
                sTabela += "    <td class='linhagrid'>"+orgao+"</td>";
              } else { 
            
                var sSelected = '';
                if (lTemValor) {
	                if (js_search_in_array(eval("me.data."+me.aFiltros[iFiltros]+".valor"),eval(me.aFiltros[iFiltros]))) {
	                  sSelected = ' checked ';
	                }
                }
                sTabela += "<td class='linhagrid'><input class='checkbox"+me.aFiltros[iFiltros]+"' "+sSelected;
                sTabela += "       onclick='filtroOrcamento.somaFiltros(\""+me.aFiltros[iFiltros]+"\")';";
                sTabela += "                  type='checkbox' id='"+eval(me.aFiltros[iFiltros])+"'></td>"; 
                sTabela += "</td>";
              }
            
              sTabela += "    <td class='linhagrid'>"+eval(me.aFiltros[iFiltros])+"</td>";
              sTabela += "    <td style='text-align:left' class='linhagrid'>"+descricao.urlDecode()+"</td>";
              sTabela += "    <td style='width:17px'>&nbsp;</td>";
              sTabela += "  </tr>";
            }
          }
          sTabela += "  <tr style='height:auto'><td>&nbsp;</td></tr>";
          sTabela += "</tbody>";
          sTabela += "<tfooter>";  
          sTabela += " <tr><td  colspan='5' class='table_footer' style='text-align:left'>";
          sTabela += "   <span style='color:blue' id='total"+me.aFiltros[iFiltros]+"'>"+iTotal.valueOf()+"</span> ";
          sTabela +=     me.aFiltros[iFiltros]+"(s) selecionados.";
          sTabela += " </td></tr>";
          sTabela += "<t/footer>";  
          sTabela += "</table>";
          innerHTML = sTabela;
          var lTemValor = eval("typeof(me.data."+me.aFiltros[iFiltros]+") != 'undefined'");
          if (lTemValor) {
            $('operador'+me.aFiltros[iFiltros]).value = eval("me.data."+me.aFiltros[iFiltros]+".operador");
          }
        }
      }
      if ($('btn'+me.aFiltros[iFiltros].ucFirst())) {
        if (me.aFiltros[iFiltros] == me.sFiltroPadrao) {
          me.showFiltro(me.aFiltros[iFiltros]);
        }
      }
    }
  }
  
  $('windowwindowFiltros'+this.id+'_btnclose').onclick=function() {
     me.window.destroy();
  };
  /**
   * Mostra a janela de Filtros.
   */
  this.show = function () {
    
    this.getFiltros();
    me.setEvents();
    this.window.show(0,0);
    
  }
  /**
   * Mostra o botao salvar para o usuario
   * @para {bool} lShow mostra o botao na tela
   */
  this.showSaveButton = function(lShow) {
    
    if (lShow) {
      $('btnSalvar').style.display='';
    } else {
      $('btnSalvar').style.display='none';
    }
  }
  
  /**
   * Mostra o filtro selecionado na tela
   * @param {string} sFiltro Mostra o filtro na tela para selecção do usuario
   */
  this.showFiltro = function (sFiltro) {
 
   var aFiltros = $$('div.filtro'+me.id);
   aFiltros.each(function (oFiltro, id) {
     
     if (oFiltro.id+me.id == "dados"+sFiltro+me.id) {
       
       if (me.sFiltroAtivo != '') {
       $('btn'+me.sFiltroAtivo.ucFirst()).style.fontWeight="normal";
         }
       me.sFiltroAtivo     = sFiltro; 
       oFiltro.style.display = '';
       $('btn'+me.sFiltroAtivo.ucFirst()).style.fontWeight="bold";
     } else {
       oFiltro.style.display = 'none';
     }
   });
  }
  
  /**
   * Define a acao padrao para o botao Salvar
   * @param {Function} sFunction definicao da funcao que deve ser executada no click
   */
  this.setCallBackSave = function (sFunction) {
     $('btnSalvar').onclick=sFunction;
  }
  
  /**
   * Retorna os orgaos marcados no filtro
   */
  this.getOrgaos = function () {
     
     var aChecboxOrgao = $$("input.checkboxorgao");
     var oOrgaosSelecionados = new Object();
     oOrgaosSelecionados.aOrgaos  = new Array();
     oOrgaosSelecionados.operador = "in";
     if ($('operadororgao')) {
       oOrgaosSelecionados.operador = $F('operadororgao');
     }
     aChecboxOrgao.each(function(oCheckbox, id) {
     
       if (oCheckbox.checked) {
         
         oOrgaosSelecionados.aOrgaos.push(oCheckbox.id);
       }
     });
     return oOrgaosSelecionados;
  }
  
  /**
   * Retorna as Unidades marcados no filtro
   */
  this.getUnidades = function () {
     
     var aChecboxUnidade = $$("input.checkboxunidade");
     var oUnidadesSelecionados       = new Object();
     oUnidadesSelecionados.aUnidades = new Array();
     oUnidadesSelecionados.operador  = "in";
     if ($('operadorunidade')) {
       oUnidadesSelecionados.operador  = $F('operadorunidade');
     }
     aChecboxUnidade.each(function(oCheckbox, id) {
     
       if (oCheckbox.checked) {
         oUnidadesSelecionados.aUnidades.push(oCheckbox.id);
       }
     });
     return oUnidadesSelecionados;
  } 
  /**
   * Retorna as funcoes marcados no filtro
   */
  this.getFuncoes = function () {
     
     var aChecboxFuncao = $$("input.checkboxfuncao");
     var oFuncoesSelecionados = new Object();
     oFuncoesSelecionados.aFuncoes = new Array();
     oFuncoesSelecionados.operador = "in";
     if ($('operadorfuncao')) {
       oFuncoesSelecionados.operador = $F('operadorfuncao');
     }
     aChecboxFuncao.each(function(oCheckbox, id) {
     
       if (oCheckbox.checked) {
         oFuncoesSelecionados.aFuncoes.push(oCheckbox.id);
       }
     });
     return oFuncoesSelecionados;
  }
  
  /**
   * Retorna as Subfuncoes marcados no filtro
   */
  this.getSubFuncoes = function () {
     
     var aChecboxSubFuncao = $$("input.checkboxsubfuncao");
     var oSubFuncoesSelecionados = new Object();
     oSubFuncoesSelecionados.aSubFuncoes = new Array();
     oSubFuncoesSelecionados.operador = "in";
     if ($('operadorsubfuncao')) {
       oSubFuncoesSelecionados.operador = $F('operadorsubfuncao');
     }
     aChecboxSubFuncao.each(function(oCheckbox, id) {
     
       if (oCheckbox.checked) {
         oSubFuncoesSelecionados.aSubFuncoes.push(oCheckbox.id);
       }
     });
     return oSubFuncoesSelecionados;
  } 
  
  /**
   * Retorna os Programas marcados no filtro
   */
  this.getProgramas = function () {
     
     var aCheckboxPrograma = $$("input.checkboxprograma");
     var oProgramaSelecionados = new Object();
     oProgramaSelecionados.aProgramas = new Array();
     oProgramaSelecionados.operador   = "in";
     if ($('operadorprograma')) {
       oProgramaSelecionados.operador   = $F('operadorprograma');
     }
     aCheckboxPrograma.each(function(oCheckbox, id) {
     
       if (oCheckbox.checked) {
         oProgramaSelecionados.aProgramas.push(oCheckbox.id);
       }
     });
    return oProgramaSelecionados;
  }
  
  /**
   * Retorna os Projetos/Atividades marcados no filtro
   */
  this.getProjAtivs = function () {
     
    var aCheckboxProjAtiv = $$("input.checkboxprojativ");
    var oProjAtivSelecionados       = new Object();
    oProjAtivSelecionados.aProjAtiv = new Array();
    oProjAtivSelecionados.operador  = "in";
    if ($('operadorprojativ')) {
      oProjAtivSelecionados.operador  = $F('operadorprojativ');
    }
    aCheckboxProjAtiv.each(function(oCheckbox, id) {
     
      if (oCheckbox.checked) {
        oProjAtivSelecionados.aProjAtiv.push(oCheckbox.id);
      }
    });
    return oProjAtivSelecionados;
  }
  
  /**
   *Retorna um array com os recursos marcados no filtro.
   */
  this.getRecursos = function () {
     
    var aCheckboxRecurso = $$("input.checkboxrecurso");
    var oRecursosSelecionados = new Object();
    oRecursosSelecionados.aRecursos = new Array();
    oRecursosSelecionados.operador = "in";
    if ($('operadorrecurso')) {
      oRecursosSelecionados.operador = $F('operadorrecurso');
    }
    aCheckboxRecurso.each(function(oCheckbox, id) {
     
      if (oCheckbox.checked) {
        oRecursosSelecionados.aRecursos.push(oCheckbox.id);
      }
    });
    return oRecursosSelecionados;
  } 
  
  /**
   *Retorna um array com os recursos marcados no filtro.
   */
  this.getElementos = function () {
     
    var aCheckboxElemento = $$("input.checkboxelemento");
    var oElementosSelecionados        = new Object();
    oElementosSelecionados.aElementos = new Array();
    oElementosSelecionados.operador   = "in";
    if ($('operadorelemento')) {
      oElementosSelecionados.operador = $F('operadorelemento');
    }
    aCheckboxElemento.each(function(oCheckbox, id) {
     
      if (oCheckbox.checked) {
        oElementosSelecionados.aElementos.push(oCheckbox.id);
      }
    });
    return oElementosSelecionados;
  } 
   
  /**
   * Define os dados que ja devem vir marcado
   * @param {Object} oOrcamento objeto com as informacoes dos niveis do orcamento que devem vir marcados
   */
  this.setData = function(oOrcamento) {
    this.data = oOrcamento;
  }
  
  /**
   * Marca/Desmarca as opcoes do filtro que está Ativo
   * @param {bool} lMarca
   */
  this.marcaFiltroAtivo = function (lMarca) {
  
    var aCheckboxRecurso = $$("input.checkbox"+this.sFiltroAtivo);
    aCheckboxRecurso.each(function(oCheckbox, id) {
     
      if (lMarca) {
        oCheckbox.checked = true;
      } else {
        oCheckbox.checked = false;
      }
    });
    filtroOrcamento.somaFiltros(this.sFiltroAtivo);
  } 
  
  /**
   * Mostra a view dentro de um container
   * @param {htmlNODE} Elemento em que a view sera inclusa;
   */
  this.showInline = function (oObject) {
  
    oObject.innerHTML = sConteudo;
    me.setEvents();
    this.getFiltros();
    
    
  }
  
  /**
   * Seta os eventos dos botoes de navegacao
   * @return void
   */
  this.setEvents = function() {
  
    $('btnOrgao').onclick     = function(){me.showFiltro('orgao');this.blur()};
    $('btnUnidade').onclick   = function(){me.showFiltro('unidade');this.blur()};
    $('btnFuncao').onclick    = function(){me.showFiltro('funcao');this.blur()};
    $('btnSubfuncao').onclick = function(){me.showFiltro('subfuncao');this.blur()};
    $('btnPrograma').onclick  = function(){me.showFiltro('programa');this.blur()};
    $('btnProjativ').onclick  = function(){me.showFiltro('projativ');this.blur()};
    $('btnElemento').onclick  = function(){me.showFiltro('elemento');this.blur()};
    $('btnRecurso').onclick   = function(){me.showFiltro('recurso');this.blur()};
    $('btnMarca').onclick     = function(){me.marcaFiltroAtivo(true);this.blur()};
    $('btnDesmarca').onclick  = function(){me.marcaFiltroAtivo(false);this.blur()};
  }
  
  
  /**
   * Define quais os filtros serao mostrados para o usuário
   */
  this.setFiltros = function (aFiltros) {
    this.aFiltros = aFiltros;
  }
  
  this.setFiltroDefault = function(sFiltroPadrao) {
  
    this.sFiltroPadrao = sFiltroPadrao;
  }
}
filtroOrcamento.somaFiltros = function (iFiltro) {
  
     var aCheckbox = $$("input.checkbox"+iFiltro);
     
     var iTotal    = 0; 
     aCheckbox.each(function (chk, id) {
       
       if (chk.checked) {
         iTotal++;
       }
     })
     if (iTotal < 0) {
       iTotal = 0;
    }
    $('total'+iFiltro).innerHTML = iTotal;
  }
 
 