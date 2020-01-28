/**
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

/**
 * @example -
 * var oFiltro = new FiltroQuestionario('filtroQuestionario', true, 1);
 *     oFiltro.show();
 */
var sNomeTreeView = 'treeViewGrupo-';

var FiltroQuestionario = function(sNome, lSelecionados, iAvaliacao) {

  /**
   * carrega os grupos/subgrupos
   */
  var loadData = function() {

    lDadosCarregados  = true;
    var iNivelEditMax = 0;
    iNivelEdit        = 0;
    /**
     * Carrega os niveis
     */
    new AjaxRequest(
      "con4_avaliacaoquestionario.RPC.php",
      {
        exec      : "getTreeView",
        iAvaliacao: iAvaliacao
      }, 
      function(oResponse, lError) {

        if (lError) {

          alert(oResponse.message.urlDecode());
        }

        var oTable = document.getElementById(sNome);
        var oLine  = document.createElement('tr');
        // Primeira coluna da linha da tabela
        var oTd    = document.createElement('th');
        oTd.appendChild(document.createTextNode('Área'));
        oLine.appendChild(oTd);
        oTd        = document.createElement('th');
        oTd.appendChild(document.createTextNode('Módulo'));
        oLine.appendChild(oTd);
        oTable.appendChild(oLine);

        oResponse.aAreas.forEach(function(oArea){

          oArea.codarea = parseInt(oArea.codarea);
          oArea.area    = oArea.area.urlDecode();

          // Cada linha da tabela
          oLine  = document.createElement('tr');
          // Primeira coluna da linha da tabela
          oTd    = document.createElement('td');
          // Checkbox da Area
          oCheck = document.createElement("INPUT");

          oCheck.setAttribute("type", "checkbox");          
          oCheck.setAttribute("id", "area-" + oArea.codarea);          
          oCheck.setAttribute("data-area", oArea.codarea); 

          if(oArea.selecionado){

            oCheck.setAttribute("checked", oArea.selecionado);     
          }    
          oCheck.setAttribute("onclick", "seleciona(this)");     

          oTd.appendChild(oCheck);
          oTd.appendChild(document.createTextNode(oArea.area));
          oTd.setAttribute("class", "line");
          oLine.appendChild(oTd);

          var oElement2 = document.createElement('td');
          var oTable2   = document.createElement('table');
          var oLine2    = document.createElement('tr');
          var count     = 0; 
          
          oElement2.setAttribute("class", "line")
                    
          oArea.filhos.forEach(function(oModulo){
            
            if(count == 0){

              oLine2 = document.createElement('tr');
            }
            count += 1;

            var oTd2    = document.createElement('td');
            var oCheck2 = document.createElement("input");
            var oDiv2   = document.createElement('div');
            
            oCheck2.setAttribute("type", "checkbox");
            oCheck2.setAttribute("id", "modulo-" + oModulo.codmodulo);
            oDiv2.setAttribute('id', "modulo-div-"+oModulo.codmodulo);
            oCheck2.setAttribute("data-area", oArea.codarea);          
            oCheck2.setAttribute("data-modulo", oModulo.codmodulo);
            oCheck2.setAttribute("class", "area-" + oArea.codarea + " modulo");                      
            oCheck2.setAttribute("onclick", "seleciona(this)");

            if(oModulo.selecionado){

              oCheck2.setAttribute("checked", oModulo.selecionado);     
            }    
            oTd2.appendChild(oCheck2);
            oModulo.codmodulo = parseInt(oModulo.codmodulo);

            var oSpan     = document.createElement('span');
            var oDiv      = document.createElement('div');
            var oDivPlus  = document.createElement('div');
            var oDivMinus = document.createElement('div');

            oDiv.setAttribute("class", 'icon2');

            oSpan.setAttribute("onclick", 'getMenuItem('+oModulo.codmodulo+')');
            oSpan.setAttribute("style", 'display:inline-block;');
            oSpan.setAttribute("onMouseOver", "this.style.cursor='hand'")
            oSpan.setAttribute("onMouseOut", "this.style.cursor='pointer'")

            oSpan.appendChild(oDiv);
            oSpan.appendChild(document.createTextNode(" +"));          
            
            oTd2.setAttribute("id", "td-" + oModulo.codmodulo);
            oTd2.appendChild(oSpan);
            oTd2.appendChild(document.createTextNode(" "+oModulo.modulo.urlDecode()));
            oTd2.appendChild(oDiv2);
            oLine2.appendChild(oTd2);
      
            if(count == 5){
      
              count = 0;
              oTable2.appendChild(oLine2);              
            }

          });
          if(count != 0){

            oTable2.appendChild(oLine2);              
          }

          oLine.appendChild(oTable2);
          oElement2.appendChild(oTable2);
          oLine.appendChild(oElement2);
          oTable.appendChild(oLine);
        });

      }).setMessage("Carregando Áreas/Módulos.")
        .asynchronous(false)
        .execute();
  }

  this.save = function(){

    var aList      = new Array();
    var aItensMenu = new Array();
    var aListEl    = document.getElementsByClassName("modulo");
    
    js_divCarregando('Aguarde, salvando informações da Lista', 'msgBox');

    for (var i = 0; i < aListEl.length; i++) {

      if(aListEl[i].checked){

        oItemMenu = document.getElementById(sNomeTreeView+aListEl[i].getAttribute('data-modulo'));

        if(oItemMenu){

          aItemSelecionado = oItemMenu.getElementsByClassName('marker-checked');

          if(aItemSelecionado){         

            for (var j = 0; j < aItemSelecionado.length; j++) {

              var oItem = new Object();

              oItem.item   = aItemSelecionado[j].getAttribute('value');
              oItem.modulo = aListEl[i].getAttribute('data-modulo');
              aItensMenu.push(oItem);
            }
          }
        }else{

          aList.push(aListEl[i].getAttribute('data-modulo'));
        }
      }
    }

    if(aList || aItensMenu){
      
      var iQuestionario  = getQuestionario(iAvaliacao);
      var oParam         = new Object();

      oParam.Modulos     = aList;
      oParam.ItensMenu   = aItensMenu;
      oParam.exec        = "saveList";
      oParam.iAvaliacao  = iAvaliacao;

      if(iQuestionario){

        oParam.iCodigoQuestionarioInterno = iQuestionario;
      } else {
        
        oParam.iCodigoQuestionarioInterno = '';
      }
      
      var oAjax  = new Ajax.Request(
        "con4_avaliacaoquestionario.RPC.php",
        {
          method:     'post',
          parameters: 'json='+Object.toJSON(oParam),
          asynchronous: false,
          onComplete: function(oAjax) {

            js_removeObj('msgBox');
            var oRetorno = eval("("+oAjax.responseText+")");

            if (oRetorno.status == "2") {

              alert(oRetorno.message.urlDecode());
            } else {
              alert('Configuração salva com sucesso!');
            }
          }
        }
      );
    }
    js_removeObj('msgBox');
  }

  if (lSelecionados) {

    loadData();
  }  
}

function getQuestionario(iAvaliacao){

  var sRPC    = 'con4_avaliacaoquestionario.RPC.php';
  var oParam  = new Object();
  var iResult = 0;

  oParam.iAvaliacao  = iAvaliacao;
  oParam.exec = "getCodigoQuestionario";
  var oAjax  = new Ajax.Request(
    sRPC,
    {
      method:     'post',
      parameters: 'json='+Object.toJSON(oParam),
      asynchronous: false,
      onComplete: function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.status== "2") {

          return false;
        } else {

          iResult = parseInt(oRetorno.iQuestionario);
        }
      }
    }
  );
  return iResult;
}

function seleciona(el){

  if((el).hasAttribute('data-modulo')){

    var oArea = document.getElementById("area-"+(el).getAttribute('data-area'));
    
    if(el.checked){

      oArea.checked = true;
    }

    iModulo = (el).getAttribute('data-modulo');
    selecionaModulo(iModulo, el.checked);

  } else {

    var aListArea = document.getElementsByClassName("area-"+(el).getAttribute('data-area'));
    var bChecked  = (el).checked;

    for (var i = 0; i < aListArea.length; i++) {
      
      aListArea[i].checked = bChecked;
      iModulo              = aListArea[i].getAttribute('data-modulo');
      selecionaModulo(iModulo, bChecked);
    }
  }
}

function selecionaModulo(iModulo, bChecked){

  oModulo     = document.getElementById('modulo-div-'+iModulo);
  aListModulo = document.getElementById('modulo-div-'+iModulo).getElementsByTagName('li');
  aListCheck  = document.getElementById('modulo-div-'+iModulo).getElementsByTagName('span');

  if(bChecked) {

    for(var j = 0; j < aListModulo.length; j++){

      aListModulo[j].setAttribute("class", "selected");
    }

    for(var j = 0; j < aListCheck.length; j++){

      if(aListCheck[j].className == "marker"){
        aListCheck[j].setAttribute("class", "marker-checked");
      }
    }
  } else {

    for(var j = 0; j < aListModulo.length; j++){

      aListModulo[j].setAttribute("class", "");
    }

    for(var j = 0; j < aListCheck.length; j++){

      if(aListCheck[j].className == "marker-checked"){
        aListCheck[j].setAttribute("class", "marker");
      }
    }
  }
}

function getMenuItem(iModulo){

  var oParam          = new Object();
  var oModulo         = document.getElementById('modulo-div-'+iModulo);
  var oTreeView       = new DBTreeView(sNomeTreeView+iModulo);
  var oElemento       = document.getElementById('modulo-div-'+iModulo); 

  oElemento.innerHTML = "";
  oParam.iModulo      = iModulo;
  oParam.exec         = "getItensMenuByModulo";

  oTreeView.addNode("0", "Menu/Submenu");
  js_divCarregando('Aguarde, Buscando informações da Lista', 'msgBox');

  var oAjax  = new Ajax.Request(
    "con4_avaliacaoquestionario.RPC.php",
    {
      method:      'post',
      parameters:  'json='+Object.toJSON(oParam),
      asynchronous: true,
      onComplete: function(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");
        showItens(oRetorno.aMenu, oTreeView, iModulo);
        js_removeObj('msgBox');
      }
    }
   );

  oTreeView.show(oElemento);
}

function showItens(aItens, oTreeView, iModulo){

  aItens.forEach(function(oItem){

    fixObj(oItem, oTreeView, iModulo);
  });
}

var oCheckFunction = function (oNode, event){

  if (oNode.checkbox.checked) {

    var oInputModulo = document.getElementById('modulo-'+oNode.data);
    iArea            = oInputModulo.getAttribute('data-area');
    var oInputArea   = document.getElementById('area-'+iArea);

    oInputModulo.checked = true;
    oInputArea.checked = true;
    oNode.checkAll(event);

  } else {

    oNode.uncheckAll(event);
  }
}

function fixObj(oItem, oTreeView, iModulo){

  oItem.item        = parseInt(oItem.item);
  oItem.pai         = parseInt(oItem.pai);
  oItem.modulo      = parseInt(oItem.modulo);
  oItem.descricao   = oItem.descricao.urlDecode();
  oItem.funcao      = oItem.funcao.urlDecode();
  oItem.selecionado = parseInt(oItem.selecionado);
  
  if(oItem.selecionado){

    oModulo         = document.getElementById('modulo-'+iModulo);
    oModulo.checked = true;
    oArea           = document.getElementById('area-'+oModulo.getAttribute('data-area'));
    oArea.checked   = true;
  }
  oTreeView.addNode(
    oItem.item,
    oItem.descricao,
    oItem.pai,
    null,
    null,
    {
      checked : oItem.selecionado,
      onClick : oCheckFunction
    },
    null
    ,{
      data : oItem.modulo
    }
  );
  if(oItem.filhos){
  
    oItem.filhos.forEach(function(oItem2){
  
      oItem2 = fixObj(oItem2, oTreeView, iModulo);
    });
  }

  return oItem;
}