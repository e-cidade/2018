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
 * var oFiltro = new FiltroOrganograma('filtroOrganograma', true, 1);
 *     oFiltro.show();
 */
var FiltroOrganograma = function(sNome, lSelecionados, iDepartamento) {

  if (sNome == null) {
    sNome = "FiltroOrganograma";
  }

  if (lSelecionados == null) {
    lSelecionados = false;
  }

  var oWindow          = null;
  var oTreeView        = null;
  var lDadosCarregados = false;
  var oOrganograma     = new Object();
  var iCodEstrutura;
  var bEdit            = false; //Identifica se é para editar ou criar novo elemento do organograma
  var oEdit            = "";
  var sEstruturalBase  = "";
  var aFilhos          = new Array();
  var iNivelMaximo     = 0;//Nivel maximo da arvore
  var iNivelEdit       = 0;//Valida se o no origem pode ser movimentado para o no destino
  var aEstrutural      = new Array(); //Armazena os estruturais por nivel gerar o próx estrutural daquele nivel (caso disponivel)
  /**
   * Configura o conteúdo da treeView
   */
  var makeTreeView = function(oElemento) {

    oTreeView = new DBTreeView(sNome + "treeViewGrupo");
    oTreeView.show(oElemento);

    oTreeView.addNode("0", "Níveis/Subníveis");
    oTreeView.allowFind(true);
    oTreeView.setFindOptions('matchedonly');
  }

  /**
   * Cria a janela
   */
  var makeWindow = function() {

    var iTamWidth  = screen.availWidth / 2;
    var iTamHeight = screen.availHeight / 2;

    oWindow = new windowAux(sNome + "-escolheGrupo", "Escolha de Níveis/Subníveis", iTamWidth, iTamHeight);

    /**
     * Define o conteúdo da window
     */
    var oDivContainer   = document.createElement("div"),
        oDivGroup       = document.createElement("div"),
        oDivTreeView    = document.createElement("div"),
        oFieldset       = document.createElement("fieldset"),
        oDivInput       = document.createElement("div"),
        oInput          = document.createElement("input");

    var oDivInputForm    = document.createElement("div");
    var oInputDescricao  = document.createElement("input");
    var oInputAssociado  = document.createElement("input");
    var oInputEstrutural = document.createElement("input");
    var oLabelDescricao  = document.createTextNode('Descrição: ');
    var oLabelAssociado  = document.createTextNode(' Associado: ');

    oDivGroup.setAttribute("rel", "ignore-css");
    oDivGroup.classList.add("container");

    oDivInput.classList.add("text-center");

    oInput.setAttribute("type",  "submit");
    oInput.setAttribute("name",  "btnSalvarOrganograma");
    oInput.setAttribute("value", "Salvar");

    oInputDescricao.setAttribute("type", "text");
    oInputDescricao.setAttribute("name", "txtDescricao");
    oInputDescricao.setAttribute("id",   "sDescricao");
    oInputAssociado.setAttribute("type", "checkbox");
    oInputAssociado.setAttribute("name", "check");
    oInputAssociado.setAttribute("id",   "bAssociado");

    oInputEstrutural.setAttribute("name",     "sEstrutural");
    oInputEstrutural.setAttribute("id",       "sEstrutural");
    oInputEstrutural.setAttribute("type",     "text");
    oInputEstrutural.setAttribute("readonly", "true");
    oInputEstrutural.setAttribute("hidden", "hidden");

    oInput.onclick = function() {

      oWindow.hide();
      salvarOrganograma();

      oTreeView.aNodes.forEach(function(oItem){
        if(oItem.value == 0){
        } else {

          oItem.remove();
        }
      });
      loadData();
    }

    oFieldset.style.height = (iTamHeight - 160);

    oTable = document.createElement('table');
    oLine  = document.createElement('tr');

    oElement = document.createElement('td');
    oElement.appendChild(oLabelDescricao);
    oElement.appendChild(oInputDescricao);
    oLine.appendChild(oElement);

    oElement = document.createElement('td');
    oElement.appendChild(oLabelAssociado);
    oLine.appendChild(oElement);
    oElement = document.createElement('td');
    oElement.appendChild(oInputAssociado);
    oLine.appendChild(oElement);

    oElement = document.createElement('td');
    // oElement.appendChild(oLabelEstrutural);
    oElement.appendChild(oInputEstrutural);
    oLine.appendChild(oElement);

    oTable.appendChild(oLine);
    oDivInputForm.appendChild(oTable);

    oFieldset.appendChild(oDivTreeView);
    oDivInput.appendChild(oInput);
    oDivGroup.appendChild(oDivInputForm);
    oDivGroup.appendChild(oFieldset);
    oDivGroup.appendChild(oDivInput);
    oDivContainer.appendChild(oDivGroup);

    oWindow.setContent(oDivContainer);

    /**
     * Configura o messageBoard
     */
    var oMessageBoard = new DBMessageBoard( sNome + "helpWindowGrupo",
                                            "Escolha de Níveis/Subníveis",
                                            "Escolha os Níveis/subníveis para adicionar ao filtro.",
                                            oDivContainer );

    makeTreeView(oDivTreeView);
  }

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
      "con1_organograma.RPC.php",
      {
        exec : "getOrganogramasTreeView"
      }, function(oResponse, lError) {

        if (lError) {

          alert(oResponse.message.urlDecode());
        }

        var oCheckFunction = function (oNode, event) {

          uncheckAllOrganogramas(oTreeView, oNode);
          createEstrutural(oNode);
        }


        // Verifica se e edicao, caso seja
        // insere indices que nao seram alterados na treeview

        if(aFilhos.length > 0){

          aFilhos = new Array();
        }
        oResponse.aGrupos.forEach(function(oGrupo) {

          // Conversoes para melhor manipulacao dos dados
          oGrupo.estrutural     = oGrupo.estrutural.urlDecode();
          oGrupo.descricaogrupo = oGrupo.descricaogrupo.urlDecode();
          oGrupo.codigogrupo    = parseInt(oGrupo.codigogrupo);
          oGrupo.nivel          = parseInt(oGrupo.nivel);
          oGrupo.departamento   = parseInt(oGrupo.departamento);
          oGrupo.conta_pai      = parseInt(oGrupo.conta_pai);
          iNivelMaximo  = (oGrupo.estrutural.split('.').length);
          var sAssociado        = "";

          if(iDepartamento == oGrupo.departamento){

            document.getElementById("sEstrutural").value = oGrupo.estrutural;
            document.getElementById("sDescricao").value  = oGrupo.descricaogrupo;

            if(oGrupo.associado == 't'){
              document.getElementById("bAssociado").checked = 'checked';
              // oGrupo.descricaogrupo = "* " + oGrupo.descricaogrupo + " -> Associado";
            }

            if(!oOrganograma){

              oOrganograma = oGrupo;
            }
            oOrganograma.estrutural = oGrupo.estrutural;
            oEdit         = oGrupo;
            bEdit         = true;
            iNivelEditMax = oGrupo.nivel;
            iNivelEdit   += 1;
            // Desabilita a troca para dentro do no pai (ficar no mesmo local)
            // Indice pai é o indice 0 do array, nao sera alterado
            aFilhos.push(oGrupo.conta_pai);
            // Desabilita a troca para dentro dele mesmo(nao pode ser pai e filho ao mesmo tempo)
            // Indice 1 do array
            aFilhos.push(oGrupo.codigogrupo);

          } else {

            // adiciona os filhos do filho do estrutural de edicao(nao pode virar filho o no pai)
            if(bEdit){

              for (var i = aFilhos.length - 1; i >= 1; i--) {

                if(aFilhos[i] == oGrupo.conta_pai){

                  aFilhos.push(oGrupo.codigogrupo);

                  // Verifica se o Nivel Maximo aumenta ou nao
                  if(oGrupo.nivel > iNivelEditMax){

                    iNivelEditMax = oGrupo.nivel;
                    iNivelEdit   += 1;
                  }

                  break;
                }
              }
            }
          }
        });

        oResponse.aGrupos.forEach(function(oGrupo) {

          if(bEdit){

            if(iDepartamento == oGrupo.departamento){

              oTreeView.addNode(
                oGrupo.codigogrupo,
                oGrupo.descricaogrupo,
                oGrupo.conta_pai,
                null,
                null,
                {
                  checked : true,
                  disabled: true,
                  onClick : oCheckFunction
                },
                null,
                {
                  data : oGrupo
                }
              );
            } else {

              // Verifica se o no atual e filho do no que sera editado
              var bTemp = false;

              for (var i = aFilhos.length - 1; i >= 0; i--) {

                if(aFilhos[i] == oGrupo.codigogrupo){

                  bTemp = true;
                  break;
                }
              }
              // caso seja, o checkbox sera desabilitado
              if((iNivelMaximo < (oGrupo.nivel+iNivelEdit)) || bTemp){
                oTreeView.addNode(
                  oGrupo.codigogrupo,
                  oGrupo.descricaogrupo,
                  oGrupo.conta_pai,
                  null,
                  null,
                  {
                    checked : false,
                    disabled: true,
                    onClick : oCheckFunction
                  },
                  null,
                  {
                    data : oGrupo
                  }
                );
              } else {

                oTreeView.addNode(
                  oGrupo.codigogrupo,
                  oGrupo.descricaogrupo,
                  oGrupo.conta_pai,
                  null,
                  null,
                  {
                    checked : false,
                    onClick : oCheckFunction
                  },
                  null,
                  {
                    data : oGrupo
                  }
                );
              }
            }
          } else {
            if(iNivelMaximo == oGrupo.nivel){

              oTreeView.addNode(
                oGrupo.codigogrupo,
                oGrupo.descricaogrupo,
                oGrupo.conta_pai,
                null,
                null,
                {
                  checked : false,
                  disabled: true,
                  onClick : oCheckFunction
                },
                null,
                {
                  data : oGrupo
                }
              );
            } else {
              oTreeView.addNode(
                oGrupo.codigogrupo,
                oGrupo.descricaogrupo,
                oGrupo.conta_pai,
                null,
                null,
                {
                  checked : false,
                  onClick : oCheckFunction
                },
                null,
                {
                  data : oGrupo
                }
              );
            }
          }
        });

        oTreeView.aNodes[0].expand();

      }).setMessage("Carregando Níveis/Subníveis.")
        .asynchronous(false)
        .execute();
  }

  makeWindow();

  if (lSelecionados) {
    loadData();
  }

  /**
   * Cria o novo estrutural
   */
  var createEstrutural = function(oNode){

    // Busca o estrutural configurado pelo xml
    getEstrutura();
    // Valores do no pai
    var aTmp     = oNode.data.estrutural.split(".");
    var aRes     = oNode.data.estrutural.split(".");
    // Transforma a mascara em array
    var aMascara = sEstruturalBase.split(".");
    // Resultado final
    var sResult  = "";
    var sTmp     = "";

    for (var i = 0; i < oNode.data.nivel; i++) {

      if(sTmp != ""){

        sTmp += ".";
      }
      sTmp += aTmp[i];
    }
    var iNivel = getEstruturalDisponivel(sTmp, oNode.data.nivel);
    var sTmp     = "";

    for (var i = 0; i < aMascara.length; i++) {

      if(sResult != ""){

        sResult += ".";
        sTmp    += ".";
      }

      if(i == parseInt(oNode.data.nivel)){

        aTmp[i] = aMascara[i].substring(0, aMascara[i].length - (parseInt(oNode.data.filhos)).toString().length) + (parseInt(oNode.data.filhos)).toString() ;
        aRes[i] = aMascara[i].substring(0, aMascara[i].length - iNivel.toString().length) + iNivel.toString();
      } else {

        aTmp[i] = aMascara[i].substring(0, aMascara[i].length - aTmp[i].length) + aTmp[i];
        aRes[i] = aMascara[i].substring(0, aMascara[i].length - aTmp[i].length) + aTmp[i];
      }
      sTmp    += aTmp[i];
      sResult += aRes[i];
    }

    oOrganograma = oNode.data;

    if(bEdit){

      if(checkEstrutural(oEdit.estrutural, sResult)){

        if(oEdit.estrutural != sTmp){

          if(confirm('Deseja mover o departamento ' + oEdit.descricaogrupo + ' para dentro do departamento ' + oNode.data.descricaogrupo + '?')){

            document.getElementById("sEstrutural").value = sResult;
            oOrganograma.estrutural   = sResult;
            oOrganograma.nivelNovo    = oNode.data.nivel+1;
            oOrganograma.nivelAntigo  = oEdit.nivel;
          }
        } else {

        }
      } else {

        document.getElementById("sEstrutural").value = oEdit.estrutural;
      }
    } else {

      oOrganograma.estrutural = sResult;
      document.getElementById("sEstrutural").value = sResult;
    }
  }

  /**
   * Retorna o estrutural filho diponivel
   * sEstrutural Pai
   */
  var getEstruturalDisponivel = function(sEstrutural, iNivel){

    var sRPC    = 'con1_organograma.RPC.php';
    var oParam  = new Object();
    var iResult = 1;

    oParam.iNivel      = iNivel;
    oParam.sEstrutural = sEstrutural;
    oParam.exec = "getOrganogramaByEstruturalNivel";
    var oAjax  = new Ajax.Request(
      sRPC,
      {
        method:     'post',
        parameters: 'json='+Object.toJSON(oParam),
        asynchronous: false,
        onComplete: function(oAjax) {

          var oRetorno = eval("("+oAjax.responseText+")");
          if(oRetorno.aGrupos){

            oRetorno.aGrupos.forEach(function(oItem){

              var aEstrutura = oItem.estrutural.split(".");

              if(iResult == parseInt(aEstrutura[iNivel])){

                iResult += 1;
              }
            });
          }
        }
      }
    );
    return iResult;
  }

  /**
   * caso ja exista um estrutural para o departamento e ele seja movido
   * e necessario efetuar a seguinte validacao
   * 1- Ele nao pode ser movido para algum nodo filho, pois corrompe a estrutura
   * A funcao verifica se ele esta sendo movido para um nó filho do mesmo
   * caso esteja, nao e permitida a alteracao, caso contrario, o fluxo e seguido
   */
  var checkEstrutural = function(sEstrutural, sDestino){

    var aEstrutural = sEstrutural.split(".");
    var aDestino    = sDestino.split(".");

    for (var i = 0; i < aEstrutural.length; i++) {

      if(parseInt(aEstrutural[i]) != parseInt(aDestino[i])){

        if((parseInt(aEstrutural[i])) === 0 && (parseInt(aDestino[i]) > 0)){

          alert('Alteração inválida, tente outra estrutura.');
          return false;
        } else {

          return true;
        }
      }
    }
    return true;
  }

  /**
   * Desabilita todos os checkbox da treeview
   */
  var uncheckAllOrganogramas = function(oTreeView, oNode){

    var aSelect = document.getElementsByClassName("marker-checked");

    if(aSelect){

      for (var i = aSelect.length - 1; i >= 0; i--) {

        if(aSelect[i].getAttribute('value').toString() != oNode.data.codigogrupo){

          aSelect[i].className = aSelect[i].className.replace(/ *\bmarker-checked\b/g, "marker");
        }
      }
    }
  }

  /**
   * Busca estrutura base do estrutural
   */
  var getEstrutura = function(){

    var oParam  = new Object();
    var sRPC    = 'con1_organograma.RPC.php';
    oParam.exec = 'getEstrutural';
    oParam.sUrl = 'config/configuracao.xml';
    var oAjax   = new Ajax.Request(
      sRPC,
      {
        method:       'post',
        asynchronous: false,
        parameters:   'json='+Object.toJSON(oParam),
        onComplete:    function(oAjax) {

          var oRetorno = eval("("+oAjax.responseText+")");
          sEstruturalBase = oRetorno.estrutural;
        }
      }
    );
  }

  /**
   * Busca o código estrutural
   */
  var codEstrutural = function(){

    var oParam  = new Object();
    var sRPC    = 'con1_organograma.RPC.php';
    oParam.exec = 'getCodigoEstrutural';
    oParam.sUrl = 'config/configuracao.xml';
    var oAjax   = new Ajax.Request(
      sRPC,
      {
        method:       'post',
        asynchronous: false,
        parameters:   'json='+Object.toJSON(oParam),
        onComplete:   function(oAjax) {

          var oRetorno = eval("("+oAjax.responseText+")");
          iCodigoEstrutura = oRetorno.iCodigoEstrutura;
        }
      }
    );
  }

  /**
   * Abre o filtro
   */
  this.show = function(){

    if (!lDadosCarregados) {
      loadData();
    }

    oWindow.show();
  }

  /**
   * Salva o novo ou atualiza o organograma
   */
  var salvarOrganograma = function(){

    sDescricao  = document.getElementById("sDescricao").value;
    bAssociado = document.getElementById("bAssociado").checked;

    if(!sDescricao){

      alert('Campo descrição é obrigatório');
      oWindow.show();
      return false;
    }

    codEstrutural();

    if(!oOrganograma.estrutural){

      alert('Estrutural não informado');
      oWindow.show();
      return false
    }

    var oParam          = new Object();
    oParam.exec         = 'salvarOrganograma';
    oParam.oOrganograma = new Object();

    if(bAssociado){

      oParam.oOrganograma.sAssociado = 't';
    } else {

      oParam.oOrganograma.sAssociado = 'f';
    }

    oParam.iCodigoOrganograma        = '';

    if(bEdit){

      oParam.iCodigoOrganograma = parseInt(oEdit.codigoorganograma);
    }

    oParam.oOrganograma.iCodigoEstrutura = iCodigoEstrutura;
    oParam.oOrganograma.sDescricao       = encodeURIComponent(tagString(sDescricao));
    oParam.oOrganograma.sEstrutural      = encodeURIComponent(tagString(oOrganograma.estrutural));
    oParam.oOrganograma.iDepartamento    = iDepartamento;
    oParam.oOrganograma.iTipo            = 1;

    js_divCarregando('Aguarde, salvando informações do Organograma', 'msgBox');
    var sRPC   = 'con1_organograma.RPC.php';
    var oAjax  = new Ajax.Request(
      sRPC,
      {
        method:     'post',
        parameters: 'json='+Object.toJSON(oParam),
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj('msgBox');
          var oRetorno = eval("("+oAjax.responseText+")");
          if(bEdit){

            atualizaFilhos(oParam.oOrganograma.sEstrutural);
          } else {

            if (oRetorno.status== "2") {

              alert(oRetorno.message.urlDecode());
            } else {
            }
          }
        }
      }
    );
  }

  var atualizaFilhos = function(sEstrutural){

    if(aFilhos.length > 2){

      var oFilhos   = new Object();
      // Transforma a mascara em array
      var aMascara = sEstruturalBase.split(".");
      var aTmp     = sEstrutural.split(".");
      var iPos     = 0;

      oFilhos.nivel = new Array();

      if(oOrganograma.nivelNovo - oOrganograma.nivelAntigo !=0){

        iPos = (oOrganograma.nivelNovo - oOrganograma.nivelAntigo)*-1;
      }

      for (var i = aFilhos.length - 1; i >= 2; i--) {

        var oFilho = oTreeView.aNodes[aFilhos[i]].data;
        var iTemp = oTreeView.aNodes[aFilhos[i]].data.nivel;
        // Valores do no pai
        var aRes     = oFilho.estrutural.split(".");
        // Estrutural final
        var sResult  = "";

        for (var j = 0; j < aMascara.length; j++) {

          if(sResult != ""){

            sResult += ".";
          }

          if(j < oOrganograma.nivelNovo){

            sResult += aMascara[j].substring(0, aMascara[j].length - aTmp[j].length) + aTmp[j];
          } else {

            if((j+iPos) < aMascara.length){

              sResult += aMascara[j+iPos].substring(0, aMascara[j+iPos].length - aRes[j+iPos].length) + aRes[j+iPos];
            } else {
              if(iTemp == 0){
                iTemp = j;
              }
              sResult += aMascara[j];
            }
          }
        }

        if(iTemp == 0){

          iTemp =  aMascara.length;
        }
        oFilho.estrutural       = sResult;
        var oTmp = new Object();

        oFilho.nivel = oOrganograma.nivelNovo + iPos;
        oTmp.iCodigoOrganograma = parseInt(oFilho.codigoorganograma);
        oTmp.iCodigoEstrutura   = iCodigoEstrutura;

        oTmp.sDescricao         = encodeURIComponent(oFilho.descricaodepartamento);
        oTmp.sEstrutural        = encodeURIComponent(oFilho.estrutural);

        oTmp.iDepartamento      = oFilho.departamento;
        oTmp.iTipo              = 1;
        oTmp.sAssociado         = oFilho.associado;

        if(!oFilhos.nivel[iTemp]){

          oFilhos.nivel[iTemp] = new Array();
        }
        oFilhos.nivel[iTemp].push(oTmp);
      }
      salvarFilhos(oFilhos)
    }
  }

  var salvarFilhos = function(oFilhos){

    var oParam    = new Object();
    oParam.exec   = 'salvarOrganogramaFilhos';
    oParam.filhos = oFilhos;

    var sRPC      = 'con1_organograma.RPC.php';
    var oAjax     = new Ajax.Request(
      sRPC,
      {
        method:     'post',
        asynchronous: false,
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: function(oAjax) {
          var oRetorno = eval("("+oAjax.responseText+")");

          if (oRetorno.status== "2") {

            // console.log(oRetorno);
          } else {
          //   return true;
          }
        }
      }
    );
  }
}