/**
 *
 *@fileoverview  DB Toogle
 *  Componente para criação de toogles automaticos
 *
 *  @author Matheus Felini - matheus.felini@dbseller.com.br
 *  @version  $Revision: 1.8 $
 */


/**
 *  Transforma um elemento fieldset em um toogle
 *  @class DBToogle
 *  @constructor
 *  @param {string} sNome    - ID do Objeto
 *  @param {boolean} lMostra -  exibir o Toogle expandido caso true e recolhido caso false
 *  @example
 *  var oToogle = new DBToogle('fieldsetInclusao', true);
 */
DBToogle = function (sNome, lMostra) {

  /**
   * Seta as propriedades do objeto
   * @private
   */
  var me            = this;
      me.id         = sNome;
      me.aNodes     = new Array();
      me.imgIcon    = '';
      me.oLegend    = '';
      me.lDisplayed = true;

      if (lMostra != null) {
        me.lDisplayed = lMostra;
      }

  var aObjChild = null;
  if ( typeof me.id == "object" ) {

    aObjChild = me.id.childElements();
    me.id = "oDBToogle_0";
  } else {
    aObjChild = $(me.id).childElements();
  }


  /**
   *  Percorre os elementos contidos no Toogle, caso ele encontre uma tag legend e esta não seja
   *  vazia, configura esta mesma legend incrementando uma imagem e deixando o texto em negrito.
   *
   *  Os demais 'nós' são armazenados dentro do array me.aNodes
   */
  aObjChild.each(
    function (oElement, id) {
      if (oElement.nodeName.toLowerCase() == 'legend' && oElement.innerHTML != '') {

        var sIdLegenda = 'legend_'+me.id;

        var oImgSeta     = document.createElement('img');
            oImgSeta.id  = 'img_'+me.id;
            oImgSeta.src = 'imagens/setabaixo.gif';

        var oSpan = document.createElement('span');
            oSpan.appendChild(oImgSeta);

        oElement.appendChild(oSpan);
        me.imgIcon = oImgSeta;
        me.oLegend = oElement;
        me.oLegend.style.fontWeight = 'bold';
        me.oLegend.style.cursor     = 'default';
    } else {

      // Incrementa no array
      me.aNodes.push(oElement);
    }
  });


  /**
   * Mostra os elementos contidos no Toogle
   * @param {boolean} lShow Caso seja false, será escondido, do contrário,
   * @return void
   */
  this.show = function(lShow) {

    var sDisplay   = '';
    me.imgIcon.src = 'imagens/setabaixo.gif';
    if ($(me.id)) {
      $(me.id).classList.remove("separator");
    }

    if (!lShow) {

      sDisplay       = 'none';
      me.imgIcon.src = 'imagens/seta.gif';
      if ($(me.id)) {
        $(me.id).classList.add("separator");
      }

    }
    for ( var iEle = 0; iEle < me.aNodes.length; iEle++ ) {
      me.aNodes[iEle].style.display = sDisplay;
    }

    me.lDisplayed = lShow;
    return true;
  };

  /**
   * AfterClick
   * FuncAO que sera executada depois do click
   * @example
   * oToogle1.afterClick = function() {
   *   oToogle3.show(false);
   *   oToogle4.show(false);
   * }
   */
  this.afterClick = function() {
    return true;
  };

  /**
   *  Observa o click na Legenda.
   */
  if (me.oLegend != '') {
    me.oLegend.observe('click', function(event) {

      var lShow = false;
      if (!me.lDisplayed) {
        lShow = true;
      }

      me.show(lShow);
      me.afterClick();
    });
  };

  /**
   *  Retorna o estado do toogle - true para expandido, false para recolhido
   *  @return boolean - Retorna o estado do toogle - true para expandido, false para recolhido
   */
  this.isDisplayed = function() {
    return me.lDisplayed;
  };

  me.show(me.lDisplayed);
};
