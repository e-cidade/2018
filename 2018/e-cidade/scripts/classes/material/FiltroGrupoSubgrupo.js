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
 * var oFiltro = new FiltroGrupoSubgrupo('filtroGrupoSubgrupo', true);
 *     oFiltro.show();
 */
var FiltroGrupoSubgrupo = function(sNome, lSelecionados) {

  if (sNome == null) {
    sNome = "filtroGrupoSubgrupo";
  }

  if (lSelecionados == null) {
    lSelecionados = false;
  }

  var oWindow = null;
  var oTreeView = null;
  var lDadosCarregados = false;

  /**
   * Configura o conteúdo da treeView
   */
  var makeTreeView = function(oElemento) {

    oTreeView = new DBTreeView(sNome + "treeViewGrupo");
    oTreeView.show(oElemento);

    oTreeView.addNode("0", "Grupos / Subgrupos");
    oTreeView.allowFind(true);
    oTreeView.setFindOptions('matchedonly');
  }

  /**
   * Cria a janela
   */
  var makeWindow = function() {

    var iTamWidth  = screen.availWidth / 2;
    var iTamHeight = screen.availHeight / 2;

    oWindow = new windowAux(sNome + "-escolheGrupo", "Escolha de Grupos/Subgrupos", iTamWidth, iTamHeight);

    /**
     * Define o conteúdo da window
     */
    var oDivContainer = document.createElement("div"),
        oDivGroup = document.createElement("div"),
        oDivTreeView = document.createElement("div"),
        oFieldset = document.createElement("fieldset"),
        oDivInput = document.createElement("div"),
        oInput = document.createElement("input");

    oDivGroup.setAttribute("rel", "ignore-css");
    oDivGroup.classList.add("container");

    oDivInput.classList.add("text-center");

    oInput.setAttribute("type", "button");
    oInput.setAttribute("name", sNome + "-btnSalvarGrupo");
    oInput.setAttribute("value", "Salvar");

    oInput.onclick = function() {
      oWindow.hide();
    }

    oFieldset.style.height = (iTamHeight - 140);
    oFieldset.appendChild(oDivTreeView);
    oDivInput.appendChild(oInput);
    oDivGroup.appendChild(oFieldset);
    oDivGroup.appendChild(oDivInput);
    oDivContainer.appendChild(oDivGroup);

    oWindow.setContent(oDivContainer);

    /**
     * Configura o messageBoard
     */
    var oMessageBoard = new DBMessageBoard( sNome + "helpWindowGrupo",
                                            "Escolha de Grupos / Subgrupos",
                                            "Escolha os grupos/subgrupos para adicionar ao filtro.",
                                            oDivContainer );

    makeTreeView(oDivTreeView);
  }

  /**
   * carrega os grupos/subgrupos
   */
  var loadData = function() {

    lDadosCarregados = true;

    /**
     * Carrega os grupos
     */
    new AjaxRequest("mat4_materialgrupo.RPC.php", { exec : "getGrupos" }, function(oResponse, lError) {

        if (lError) {
          alert(oResponse.message.urlDecode());
        }

        var oCheckFunction = function (oNode, event) {

          if (oNode.checkbox.checked) {
            oNode.checkAll(event);
          } else {
            oNode.uncheckAll(event);
          }
        }

        oResponse.aGrupos.forEach(function(oGrupo) {

            oTreeView.addNode( oGrupo.codigogrupo,
                               oGrupo.estrutural + " - " + oGrupo.descricaogrupo.urlDecode(),
                               oGrupo.conta_pai,
                               null,
                               null,
                               {
                                 checked : lSelecionados,
                                 onClick : oCheckFunction
                               });
          });

        oTreeView.aNodes[0].expand();

      }).setMessage("Carregando Grupos/Subgrupos.")
        .asynchronous(false)
        .execute();
  }

  makeWindow();

  if (lSelecionados) {
    loadData();
  }

  /**
   * Abre o filtro
   */
  this.show = function() {

    if (!lDadosCarregados) {
      loadData();
    }

    oWindow.show();
  }

  /**
   * Retorna os grupos/subgrupos selecionados
   * @return []
   */
  this.getSelecionados = function() {

    var aSelecionados = oTreeView.getNodesChecked();
    var aGrupos = new Array();

    aSelecionados.each(function(oItem) {
      aGrupos.push(oItem.value);
    })

    return aGrupos;
  }
}