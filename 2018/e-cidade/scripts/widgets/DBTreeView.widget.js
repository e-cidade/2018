
/**
 * @fileoverview Cria um Objeto do tipo Treeview
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.18 $
 */

/**
 * Cria uma treeview
 * @class DBTreeview
 * @constructor
 * @param {string} sName  id do Objeto
 */
DBTreeView = function (sName) {

  /**
   *cria escopo para  a instancia da classe internament
   *@class DBTreeview
   */
  var me          = this;
  me.id           = sName;
  me.aNodes       = new Array();

  /**
   * Opções para a pesquisa
   * opções disponiveis:
   * hint : marca o texto.
   * matchedonly: Esconde os Nos nao Visiveis
   */
  me.optionsFind         = new Object();
  me.optionsFind.display = 'hint';
  me.optionsFind.length  = 0;
  me.optionsFind.match   = 'all';
  /**
   * Inicia o Container da Tree.
   */
  me.container                       = document.createElement("div");
  me.container.style.backgroundColor = "#FFF";
  me.container.className             = "DBTreeView";
  me.container.style.border          = "1px solid #999999";
  me.container.id                    = me.id;

  me.container.style.overflow        = 'hidden';
  me.container.style.height          = '100%';
  me.container.style.width           = '100%';


  /**
   *Input para pesquisa dentro da Tree
   */
  me.containerFind                = document.createElement("div");
  me.containerFind.style.display  = 'none';
  me.containerFind.style.position = 'relative';
  me.containerFind.style.width    ='100%';

  me.inputFind               = document.createElement("input");
  me.inputFind.type          = 'text';
  me.inputFind.title         = 'Informe a expressão de pesquisa';
  me.inputFind.style.marginLeft = '16px';
  me.inputFind.style.border     = '0px';
  me.inputFind.style.borderBottom  = '1px solid #999999';
  me.inputFind.id            = "dbTree"+me.id+"_inputFind";

  me.iconFind                = document.createElement("img");
  me.iconFind.src            = 'imagens/icon_find_black.png';
  me.iconFind.style.float    = 'right';
  me.iconFind.style.position = 'absolute';
  me.iconFind.style.top      = '0px';

  //me.iconFind.style.right    = '0px';
  me.iconFind.style.backgroundColor = '#EFEFEF';
  me.iconFind.style.padding      = '1px';
  me.iconFind.style.borderTop    ='2px outset #999999';
  me.iconFind.style.borderRight  = '2px outset #999999';
  me.iconFind.style.borderBottom = '2px outset #999999';
  me.iconFind.style.clear = 'all';
  me.containerFind.appendChild(me.iconFind);
  me.containerFind.appendChild(me.inputFind);

  me.containerLists                       = document.createElement("div");
  me.containerLists.style.backgroundColor = "#FFF";
  me.containerLists.style.border          = "0px";

  me.containerLists.style.overflow        = 'auto';
  me.containerLists.style.height          = '95%';
  me.containerLists.style.width           = '100%';

  /**
   * Nó principal da Grid
   */
  me.rootNode                = document.createElement("ul");
  me.rootNode.id             ='noderoot'+sName;
  me.rootNode.className      ='noderoot';
  me.rootNode.style.overflow ='auto';
  me.rootNode.style.height   ='100%';

  me.container.appendChild(me.containerFind);
  me.container.appendChild(me.containerLists);
  me.containerLists.appendChild(me.rootNode);
  this.show   = function (oContainer) {

    oContainer.appendChild(me.container);
    me.inputFind.style.width   = '100%'
    if (me.container.parentNode.scrollWidth == 0) {
      me.inputFind.style.width = '98%';
    }
  };

  /**
    * cria os nos da treeview atraves de uma string json
    * @return void
    * @see DBNodeTree
    */
  this.addFromJson = function(sJson) {

    oItens = eval("("+sJson+")");
    aItens.each(function(oNode, iLinha) {

      me.addNode(oNode.value,
                 oNode.label,
                 oNode.parentnode,
                 oNode.expanded,
                 oNode.type,
                 oNode.checkbox,
                 oNode.action
              );
    });
  };

  /**
   * cria os nos da treeview atraves de um array com objetos do tipo no. os dados que
   * devem ser setados o objeto sao:
   *<pre>
   *   oNode.value, = Valor do no
                 oNode.label label do no ,
                 oNode.parentnode valor do no pai,
                 oNode.expanded  - nao implementado,
                 oNode.type      = null,
                 oNode.checkbox  = Objeto , com as propriedadas checked, e onClick = Onde e setado uma
                                 funcao callback,
                 oNode.action funcao disparada no click do no.
   *</pre>
   * @return void
   *@see DBNodeTree
   */
  this.addFromArray = function(aNodes) {

    aNodes.each(function(oNode, iLinha) {

      me.addNode(oNode.value,
                 oNode.label,
                 oNode.parentnode,
                 oNode.expanded,
                 oNode.type,
                 oNode.checkbox,
                 oNode.action
              );
    });
  };

  /**
   * adiciona um  no na  treeview .todos os nos serao convertidos em um Objeto DBNodeTree.
   * @param {string} sValue Valor do no
   * @param {string} sLabel label do no ,
   * @param {string} sParentNode valor do no pai,
   * @param {boolean} Expanded    - nao implementado,
   * @param {boolean}type     null,
   * @param {Object} checkbox Objeto , com as propriedadas checked, e onClick = Onde e setado uma
                                 funcao callback,
   * @param {callback} action funcao disparada no click do no.
   * @see DBNodeTree
   * @return DBNodeTree
   */
  this.addNode = function (sValue, sLabel, sParentNode, lExpanded, iType, checkbox, action, aParams) {


    /**
     * Objeto node
     *
     */
    var oNode          = new DBNodeTree();
    oNode.value        = sValue;
    oNode.label        = sLabel;
    oNode.type         = iType;
    oNode.checkbox     = checkbox;
    oNode.action       = action;
    oNode.children     = new Array();
    oNode.lastnode     = true;
    oNode.marker       = '';
    me.aNodes[sValue]  = oNode;
    oNode.asParentNode = false;
    oNode.parentNode   = '';
    if (aParams != null) {
      for (sIndice in aParams) {

        if (typeof(aParams[sIndice]) != 'function') {
           oNode[sIndice] = aParams[sIndice];
        }
      }
    }

    var oNodeElement   = document.createElement("li");
    oNodeElement.id    = "dbTree"+me.id+'node_'+sValue;
    oNodeElement.setAttribute("value" , sValue);
    oNodeConector           = document.createElement('span');
    oNodeConector.className = 'conector';
    oNodeConector.id        = "dbTree"+me.id+'conector_'+oNodeElement.id;
    oNode.element           = oNodeElement;
    oNodeElement.appendChild(oNodeConector);
    /**
     * Cria o elemento do marcador
     */
    oNodeMarker           = document.createElement('span');
    oNodeMarker.id        = "dbTree"+me.id+'checkbox_'+oNodeElement.id;
    oNodeMarker.setAttribute("value" ,sValue);
    if (checkbox instanceof Object) {

      oNodeMarker.className = 'marker';
      if (checkbox.disabled) {
       oNodeMarker.className = 'marker-disabled';
      }
      if (checkbox.checked) {

        oNodeMarker.className = 'marker-checked';
        oNodeElement.className = ' selected ';
        if (checkbox.disabled) {
          oNodeMarker.className = 'marker-checked-disabled';
        }
      }
      if (!checkbox.disabled) {
        oNodeMarker.observe("click", function(event){
          me.setChecked(event, event.target, checkbox.onClick);
        });
      }
      oNode.marker = oNodeMarker;
    }

    oNodeElement.appendChild(oNodeMarker);
    oNodeLabel    = document.createElement('span');
    oNodeLabel.id = "dbTree"+me.id+'value_'+oNodeElement.id;
    oNodeLabel.innerHTML   = sLabel;

    if (typeof(action) == "function") {

        oNodeLabel.observe('click', function(event) {
           action(oNode, event);
      });
    }

    oNodeLabel.observe("click", function (event) {
      me.showChildren(event, $(oNodeElement.id));
    });
    oNodeConector.observe("click", function (event) {
      me.showChildren(event, $(oNodeElement.id));
    });
    oNodeLabel.className   = 'nodeValue';
    oNodeElement.appendChild(oNodeLabel);
    if (sParentNode == null || !$("dbTree"+me.id+'node_'+sParentNode)) {

      oNodeConector.className = 'last-conector';
      me.rootNode.appendChild(oNodeElement);
      oNodeElement.className += ' lastnode';
    } else {

      if ($("dbTree"+me.id+'node_'+sParentNode)) {

        oLista = me.createList();
        oLista.style.display = 'none';
        oLista.appendChild(oNodeElement);
        $("dbTree"+me.id+'node_'+sParentNode).childNodes[0].className='conector-close';
        oNode.asParentNode     = true;
        oNode.parentNode       = me.aNodes[$("dbTree"+me.id+'node_'+sParentNode).getAttribute('value')];
        oNode.parentNode.children.each(function (oNodeChild, index) {

          oNodeChild.element.className = '';
          if (oNodeChild.checkbox && oNodeChild.checkbox.checked) {
            oNodeChild.element.className += ' selected';
          }
          oNodeChild.lastnode          = false;

        });

        oNodeElement.className += 'lastnode';
        $("dbTree"+me.id+'node_'+sParentNode).appendChild(oLista);
        me.aNodes[$("dbTree"+me.id+'node_'+sParentNode).getAttribute('value')].children.push(oNode);
      }
    }

    /*
     *Sobrescrevemos a funcao checkall do node
     */
    oNode.checkAll = function(event) {

      for (var j = 0; j < oNode.children.length; j++) {

        if (oNode.children[j].checkbox && !oNode.children[j].checkbox.disabled) {
          me.setChecked(event,
                        $("dbTree"+me.id+'checkbox_dbTree'+me.id+'node_'+oNode.children[j].value),
                        oNode.children[j].onClick,
                        true);
        }
        oNode.children[j].checkAll(event);
      }
    };

    /*
     * sobrescrevendo o método da classe DBNodeTree
     */
    oNode.uncheckAll = function(event) {


      for (var i=0; i < oNode.children.length; i++) {

        if (oNode.children[i].checkbox && !oNode.children[i].checkbox.disabled) {

          me.setChecked(event,
                        $("dbTree"+me.id+'checkbox_dbTree'+me.id+'node_'+oNode.children[i].value),
                        oNode.children[i].onClick,
                        false
                        );
        }
        oNode.children[i].uncheckAll(event);
      }
    };

    oNode.expand = function (event, top) {

      me.showChildren(event, oNode.element, 1);
      if (top) {
        if (oNode.asParentNode) {
          oNode.parentNode.expand(event, true);
        }
      }
    };

    oNode.collapse = function(event) {
      me.showChildren(event, oNode.element, 2);
    };

    oNode.display =  function(show, top) {

	    var sDisplay = '';
	    if (!show) {
	      sDisplay = 'none';
	    }
	    oNode.element.style.display = sDisplay;
	    if (oNode.asParentNode) {
	      oNode.parentNode.display(show, true);
	    }
	  };

	  /**
	   * Remove o nó e seus filhos da arvore
	   */
	  oNode.remove = function() {

	    if (oNode.children.length > 0) {

	      oNode.children.each(function(oChildrenNode) {
	        oChildrenNode.remove();
	      });
	    };
	    var oParent = oNode.element.parentNode;
	    oParent.removeChild(oNode.element);

	    for (oNodeArvore in me.aNodes) {

	      if (typeof(me.aNodes[oNodeArvore]) == 'function') {
	        continue;
	      }

	      if (oNode.value == me.aNodes[oNodeArvore].value) {
	        delete me.aNodes[oNodeArvore];
	      }
	    }
	  };

	  return oNode;
  };

  /**
   * Cria um elemento do tipo UL
   * @private
   */
  this.createList =function() {

    var oList = document.createElement('ul');
    return oList;
  };

  /**
   * expande os nos filhos. funcao apenas utilizada internamente na classe
   *@private
   */
  this.showChildren = function(event, obj, iType) {

    var iTamanho = (obj.childNodes.length);
    for (var i = 0; i < iTamanho; i++) {

      with (obj.childNodes[i]) {

        if (nodeName =='UL') {

          if (iType == null) {
            if (style.display == 'none') {
              iType = 1;
	          } else {
	            iType = 2;
	          }
          }
          if (iType == 1) {
            style.display = '';
            $("dbTree"+me.id+'conector_'+obj.id).className='conector-open';
          } else {
            style.display = 'none';
            $("dbTree"+me.id+'conector_'+obj.id).className='conector-close';
          }
        }
      }
    }
    return false;
  };

  /**
   * marca o checbox da linham caso exista. Funcao apenas utilizada internamente na classe
   * @private
   */
  this.setChecked = function (event, oCheckBox, oCallBack, lForceMode) {


    /**
     * Verifica se o não está visivel. caso não esteja.
     * nao realiza nenhuma ação com ele.
     */
    if (me.aNodes[oCheckBox.getAttribute('value')].element.style.display == 'none') {
      return false;
    }
    if (lForceMode != null) {

      if (lForceMode) {

        oCheckBox.className = 'marker-checked';
        me.aNodes[oCheckBox.getAttribute('value')].checkbox.checked = true;
        me.aNodes[oCheckBox.getAttribute('value')].select(true);
      } else {

        oCheckBox.className = 'marker';
        me.aNodes[oCheckBox.getAttribute('value')].checkbox.checked = false;
        me.aNodes[oCheckBox.getAttribute('value')].select(false);
      }
    } else {

	    if (oCheckBox.className == 'marker') {

	     oCheckBox.className = 'marker-checked';
	     me.aNodes[oCheckBox.getAttribute('value')].checkbox.checked = true;
	     me.aNodes[oCheckBox.getAttribute('value')].select(true);
	    } else {

	      oCheckBox.className = 'marker';
	      me.aNodes[oCheckBox.getAttribute('value')].checkbox.checked = false;
	      me.aNodes[oCheckBox.getAttribute('value')].select(false);
	    }
    }
    if (me.aNodes[oCheckBox.getAttribute('value')].checkbox.disabled) {
       oCheckBox.className += '-disabled';
    }
    if (oCallBack != null) {

      oCallBack(me.aNodes[oCheckBox.getAttribute('value')], event);
    }
    if (event != null) {

      event.stopPropagation();
      event.preventDefault();
    }
    return false;
  };

 /**
  * Retorna os nos que estao selecionados
  * @return Array
  */

  this.getNodesChecked = function() {

    var aRetorno = new Array();
    for (sValue in me.aNodes) {

       if (typeof(me.aNodes[sValue]) == "function") {
         continue;
       }
       with (me.aNodes[sValue]) {
         if (checkbox) {
           if (checkbox.checked) {
            aRetorno.push(me.aNodes[sValue]);
          }
        }
      }
    }
    return aRetorno;
  };

  /**
   * Realiza uma busca na arvore, conforme o valor digitidado no campo de pesquisa,
   * mostrando os dados conforme o estilo de pesquisa definido em  setFindOptions
   * @see DBTreeView.setFindOptions
   */
  this.find = function(event) {

    var sExpressao = $F("dbTree"+me.id+'_inputFind').toLowerCase();
    /**
     * percorre todos os nos da Arvore
     */
    for (sValue in me.aNodes) {

       /**
        * a biblioteca prototype adiciona algumas funcoes a Classe array
        * Devemos ignorar esses indices
        */
      if (typeof(me.aNodes[sValue]) == "function") {
        continue;
      }

      with (me.aNodes[sValue]) {

        var iPosicao = label.toLowerCase().indexOf(sExpressao);

        /**
         * Opção de pesquisa = "hint" apenas marca o Texto.
         */
        if (me.optionsFind.display == 'hint') {

          if (sExpressao.trim().length > me.optionsFind.length && iPosicao >= 0) {

            /**
             * para o hint, criamos uma span com cor diferente
             */
	          var sTag = "<span class='findResult' style='color:blue'>"+label.substr(iPosicao, sExpressao.length)+"</span>";
	          var sStringMatched = label.substrReplace(sTag, iPosicao, iPosicao+sExpressao.length);
	          $("dbTree"+me.id+'value_dbTree'+me.id+'node_'+value).innerHTML  = sStringMatched;

	          /**
	           * expande o no pai.
	           */
	          if (me.aNodes[sValue].asParentNode) {
	            me.aNodes[sValue].parentNode.expand(null, true);
	          }
	        } else {
	           /**
	            * nao econtrando nenhum no na pesquisa, ou a string de pesquisa é inválida,
	            * desmarcamos o no.
	            */
	           $("dbTree"+me.id+'value_dbTree'+me.id+'node_'+value).innerHTML  = label;
	        }
	      } else if (me.optionsFind.display == 'matchedonly') {


	         me.aNodes[sValue].element.style.display = '';
	        /**
	         * mostra apenas os nos que batem com a pesquisa, e seus nos pais.
	         */
	        if (sExpressao.trim().length > me.optionsFind.length && iPosicao >= 0) {

	         /**
	          * Cria o hint.
	          */
	         var sTag = "<span class='findResult' style='color:blue'>"+label.substr(iPosicao, sExpressao.length)+"</span>";
           var sStringMatched = label.substrReplace(sTag, iPosicao, iPosicao+sExpressao.length);
           $("dbTree"+me.id+'value_dbTree'+me.id+'node_'+value).innerHTML  = sStringMatched;
	         if (me.aNodes[sValue].asParentNode) {

	            /**
	             * expande o no pai, e mostra o no pai também.
	             */
              me.aNodes[sValue].parentNode.expand(null, true);
              me.aNodes[sValue].parentNode.display(true, true);
            }
           } else if (sExpressao.trim() == '') {

             /**
              * expressão inválida, mostra todo os nos
              */
             me.aNodes[sValue].element.style.display = '';
             $("dbTree"+me.id+'value_dbTree'+me.id+'node_'+value).innerHTML  = label;
	        } else {

	          /**
	           * no que nao bate com a expressão de pesquisa, é escondido.
	           */
	           var lDisplay = 'none';
	          if (me.aNodes[sValue].parentNode) {
	            if (me.aNodes[sValue].parentNode.label.toLowerCase().indexOf(sExpressao) >= 0) {
	             lDisplay = '';
	            }
	          }
	          me.aNodes[sValue].element.style.display                        = lDisplay;
	          $("dbTree"+me.id+'value_dbTree'+me.id+'node_'+value).innerHTML = label;
	        }
	      }
      }
    }
  };

  me.inputFind.observe("keypress", function (event){
   if (event.which == 13) {
     me.find();
   }
  });

  me.inputFind.observe("change", function (event){
     me.find();
  });

  /**
   * define as opcoes para a pesquisa na arvore
   * @param {string} display define como sera mostrada o resultado da pesquisa os valores aceitos sao:
     hint: apenas destaca o no .
     matchedonly  destaca e deixa visivel apenas o no encontradado, assim com o seus pais
   * @param length define o tamanho minino de caracteres para a busca
   */
  this.setFindOptions = function(display, length) {

   me.optionsFind.display = display   != null?display:'hint';
   me.optionsFind.length  = length    != null?length:0;

  };

  /**
   *Define se a tree aceita pesquisas
   *@param {boolean}  lAllow
   */
  this.allowFind = function(lAllow) {

    var sDisplay = 'none';
    if (lAllow) {
      sDisplay = '';
    }
    me.containerFind.style.display = sDisplay;
  };

};
/**
 * Define um no para a DBTreeView
 * @constructor;
 * @class DBNodeTree

 */
DBNodeTree = function()  {

   var me = this;
  /*
   * funcao para marcar o checkbox do no;
   * @param {Event} Event
   * @return
   */
  this.checkAll = function () {

  };

  /**
   * funcao para desmarcar o checkbox do no;
   * @param {Event} Event
   * @return
   */
  this.uncheckAll = function(event) {

  };

  this.expand = function () {

  };
  /**
   * recolhe o
   * @param {Event} event evento
   */
  this.collapse = function(event) {

  };

  this.remove = function(){

  };
  /**
   * esconde ou mostra os nos
   * @param {boolean} show mostra o no
   * @param {boolean} define o se mostra o no pai.
   */
  this.display =  function(show, top) {

    var sDisplay = '';
    if (!show) {
      sDisplay = 'none';
    }
    me.element.style.display = sDisplay;
    if (me.asParentNode) {
      me.parentNode.display(show, true);
    }
  };
  /**
   * marca o no como selecionado
   * @param boolean lSelect true - seleciona false = não seleciona
   */
  this.select = function(lSelect) {

    var sClassName = '';
    if (me.lastnode) {
      sClassName += 'lastnode';
    }
    if (lSelect) {
      sClassName += ' selected';
    }
    me.element.className = sClassName;
  };

  this.setDisabled = function (lDisabled) {

    if (me.checkbox instanceof Object) {

      if (lDisabled) {

        me.marker.className += '-disabled';
        me.marker.stopObserving("click");
        me.checkbox.checked = false;
      } else {

        me.marker.observe("click", function(event) {
          me.setChecked(event, event.target, me.checkbox.onClick);
        });
      }
    }
  }
};

 var oEstiloDBTreeview= document.createElement("link");
     oEstiloDBTreeview.href = "estilos/dbtreeview.style.css";
     oEstiloDBTreeview.rel  = "stylesheet";
     oEstiloDBTreeview.type  = "text/css";
     document.getElementsByTagName("head")[0].appendChild(oEstiloDBTreeview);