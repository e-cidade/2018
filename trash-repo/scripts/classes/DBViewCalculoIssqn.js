/**
 * 
 * Componente para o formul�rio de c�lculo do ISSQN
 * 
 * @author Alberto Ferri Neto alberto@dbseller.com.br 
 * @package ISSQN
 * @revision $Author: dbanderson $
 * @version $Revision: 1.4 $
 *
 */
DBViewCalculoIssqn = function ( sInstancia, sNomeComponente ) {
  
  /**
   * Inst�ncia do Componente
   */
  var me                = this;
  var oElementoDestino  = null;
  var iInscricao        = null;
  var lUsaJanela        = false;
  this.sIntancia        = sInstancia;
  this.sNomeComponente  = sNomeComponente;
  this.oForm            = new Object();

  this.setInscricao = function (iCodigo) {
    iInscricao = iCodigo;
  }; 
  /**
   * Cria HTML B�SICO do Componente
   */
  this.renderizarHTML   = function() {
    
    //fieldset do formul�rio
    me.oForm.oFieldset                       = document.createElement('fieldset');
    me.oForm.oFieldset.style.margin          = '10px auto 0 auto ';
    me.oForm.oFieldset.style.width           = '750px';

    oElementoDestino.appendChild(me.oForm.oFieldset);
                                             
    me.oForm.oLegend                         = document.createElement('legend');
    me.oForm.oLegend.innerHTML               = '<strong>C�lculo de Alvar�</strong>';
    me.oForm.oFieldset.appendChild(me.oForm.oLegend);
    
                                    
    me.oForm.oTable                          = document.createElement('table');
    me.oForm.oTable.style.margin             = '0 auto';
    me.oForm.oFieldset.appendChild(me.oForm.oTable);
               
    /**
     * Criando linha para o tipo de Calculo
     */
    me.oForm.oLinhaTipo                      = document.createElement('tr');
    me.oForm.oTable.appendChild(me.oForm.oLinhaTipo);
    
    me.oForm.oCelulaLabelTipo                = document.createElement('td');
    me.oForm.oCelulaLabelTipo.style.width    = "130px";
    me.oForm.oCelulaLabelTipo.innerHTML      = '<strong>Tipo de C�lculo:</strong>';
    me.oForm.oCelulaCboTipo                  = document.createElement('td');
    me.oForm.oCelulaCboTipo.innerHTML        = '<div id="' + me.sNomeComponente + '_cboTipo"></div>';
    
    me.oForm.oLinhaTipo.appendChild(me.oForm.oCelulaLabelTipo);
    me.oForm.oLinhaTipo.appendChild(me.oForm.oCelulaCboTipo);
    
    /**
     * Linha para a Quantidade de Parcelas
     */
    me.oForm.oLinhaParcelas                  = document.createElement('tr');
    me.oForm.oLinhaParcelas.id               = sNomeComponente + "_linhaParcelas"; 
    me.oForm.oTable.appendChild(me.oForm.oLinhaParcelas);
    
    me.oForm.oCelulaLabelParcelas            = document.createElement('td');
    me.oForm.oCelulaLabelParcelas.innerHTML  = '<strong>N�mero de Parcelas Alvar�:</strong>';
    me.oForm.oCelulaNumeroParcelas           = document.createElement('td');
    me.oForm.oCelulaNumeroParcelas.innerHTML = '<div id="' + me.sNomeComponente + '_cboParcelas"></div>';
    
    me.oForm.oLinhaParcelas.appendChild(me.oForm.oCelulaLabelParcelas);
    me.oForm.oLinhaParcelas.appendChild(me.oForm.oCelulaNumeroParcelas);
    
    /**
     * Linha de exercicios para calculo exibida conforme par�metro
     */
    me.oForm.oLinhaExercicios                 = document.createElement('tr');
    me.oForm.oLinhaExercicios.id              = me.sNomeComponente + "_linhaExercicios";                
    me.oForm.oLinhaExercicios.style.display   = "none";
    me.oForm.oTable.appendChild(me.oForm.oLinhaExercicios);
    
    me.oForm.oCelulaLabelAnoCalculo           = document.createElement('td');
    me.oForm.oCelulaLabelAnoCalculo.innerHTML = '<strong>Ano C�lculo</strong>';
    me.oForm.oCelulaCboAno                    = document.createElement('td');
    var sConteudo                             = "<span id='" + me.sNomeComponente + "_anoInicial'></span>";
        sConteudo                            += "<strong> at� </strong>";
        sConteudo                            += "<span id='" + me.sNomeComponente + "_anoFinal'></span>";
    me.oForm.oCelulaCboAno.innerHTML          = sConteudo;
    
    me.oForm.oLinhaExercicios.appendChild(me.oForm.oCelulaLabelAnoCalculo);
    me.oForm.oLinhaExercicios.appendChild(me.oForm.oCelulaCboAno);
    

    /**
     * Grid atividades
     */
    me.oForm.oFieldsetGrid                    = document.createElement('fieldset');
    me.oForm.oFieldsetGrid.style.margin       = '10px auto 0 auto ';
    me.oForm.oFieldsetGrid.style.width        = '750px';
    me.oForm.oLegendGrid                      = document.createElement('legend');
    me.oForm.oLabelGrid                       = document.createElement('strong');
    me.oForm.oLabelGrid.innerHTML             = 'Atividades';
    me.oForm.oSpanGrid                        = document.createElement('div');
    me.oForm.oSpanGrid.id                     = me.sNomeComponente +'_gridAtividades';
    
    /**
     * Elementos grid atividades
     */
    me.oForm.oLegendGrid  .appendChild(me.oForm.oLabelGrid);
    me.oForm.oFieldsetGrid.appendChild(me.oForm.oSpanGrid);
    me.oForm.oFieldsetGrid.appendChild(me.oForm.oLegendGrid);
    
    /**
     * Adicionando o Componente ao Destino
     */
    oElementoDestino         .appendChild(me.oForm.oFieldsetGrid);
    if ( !lUsaJanela ) {
      var sConteudo  = "  <input type='button' value='Processar' onClick='" + sInstancia + ".processar();'>           ";
      oElementoDestino.innerHTML = oElementoDestino.innerHTML + sConteudo;
    }
  };
  
  /**
   * Cria componentes existentes na biblioteca 
   */
  this.criarComponentes = function() {
    
    me.oCboTipo   = new DBComboBox(me.sNomeComponente+'_oComboTipo' , sInstancia + '.oCboTipo' , new Array(), '92');
    me.oCboTipo.addItem(0, 'Todos');
    me.oCboTipo.addItem(1, 'ISSQN');
    me.oCboTipo.addItem(2, 'Alvar�');
    
    me.oCboTipo.addEvent('onChange', sInstancia + '.validarTipoSelecao();');
    me.oCboTipo.show($(me.sNomeComponente + '_cboTipo'));
    
    me.oCboParcelas = new DBComboBox(me.sNomeComponente+'_oCboParcelas', sInstancia + '.oCboParcelas', [], '92');
    me.oCboParcelas.show($(me.sNomeComponente + '_cboParcelas'));
    
    var oParametrosgetParametros = getParametros();
    
    $R(0, oParametrosgetParametros.iNumeroParcelas, true).each(function(iParcela){
      me.oCboParcelas.addItem(iParcela + 1, iParcela + 1);
    });
    
    
    
    if ( getAnoCalculo().length > 0 ) {
      me.cboAnoCalculoInicial = new DBComboBox(me.sNomeComponente+'_cboAnoInicial', sInstancia + '.cboAnoCalculoInicial', [], '92');
      me.cboAnoCalculoFinal   = new DBComboBox(me.sNomeComponente+'_cboAnoFinal'  , sInstancia + '.cboAnoCalculoFinal', [], '92');
     
      getAnoCalculo().each(function(iAno){
        me.cboAnoCalculoInicial.addItem(iAno, iAno);
        me.cboAnoCalculoFinal  .addItem(iAno, iAno);
      });
      me.cboAnoCalculoInicial.show($(me.sNomeComponente + '_anoInicial'));
      me.cboAnoCalculoFinal  .show($(me.sNomeComponente + '_anoFinal'));

      $(me.sNomeComponente + "_linhaExercicios").style.display  = "";
    }
    me.montaGridAtividades();
  };
  
  this.montaGridAtividades = function () {
    
    oGridAtividades = new DBGrid('gridAtividades');
    
    oGridAtividades.nameInstance = 'oGridAtividades';
    
    aAlinhamentoColunas     = new Array('center',
                                        'left',
                                        'center',
                                        'center',
                                        'center');
                               
    aTamanhoColunas         = new Array('10%',
                                        '60%',
                                        '10%',
                                        '10%',
                                        '10%');
    
    aCabecalhoColunas       = new Array('Sequencial'  ,
                                        'Descri��o'   ,
                                        'Data In�cio' ,
                                        'Permanente'  ,
                                        'Quantidade'  );
    
    oGridAtividades.setCheckbox(0);
    oGridAtividades.setCellWidth(aTamanhoColunas);
    oGridAtividades.setCellAlign(aAlinhamentoColunas);
    oGridAtividades.setHeader   (aCabecalhoColunas);
    oGridAtividades.show($(me.sNomeComponente+'_gridAtividades'));
    oGridAtividades.clearAll(true);
    oGridAtividades.setStatus('<span style="border:1px solid #000; margin-left:450px;padding-right: 15px; background-color: #d1f07c;"></span> Atividade Principal');
    
    
    if(this.getAtividades().length > 0) {
      
      this.getAtividades().each(function (oAtividade, iIndice) {
        
        var aRow = new Array();
        
        aRow[0]  = oAtividade.iSequencial;
        aRow[1]  = oAtividade.sDescricao.urlDecode();
        aRow[2]  = js_formatar(oAtividade.dDataInicial, 'd');
        aRow[3]  = oAtividade.lPermanente == 't' ? 'Sim' : 'N�o';
        aRow[4]  = oAtividade.iQuantidade;
        
        oGridAtividades.addRow(aRow, false, false, true);
        
        if (oAtividade.lPrincipal == 't') {
          oGridAtividades.aRows[iIndice].setClassName('destacado');
        }
        
      });
     oGridAtividades.renderRows();
    }
  };
  
  var getParametros = function () {
    
    var oParam       = new Object();
    oParam.sExec     = 'getParametros';
    
    oParametrosIssqn = new Object();
    var oAjax    = new Ajax.Request('iss4_isscalc.RPC.php',
                                   {
                                    method: 'POST',
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    asynchronous:false,
                                    onComplete: function (oAjax) {
                                      
                                      var oRetorno        = eval("("+oAjax.responseText+")");
                 
                                      if (oRetorno.status == 1) {
                                        
                                        oParametrosIssqn.iNumeroParcelas = oRetorno.iNumeroParcelas > 0 ? oRetorno.iNumeroParcelas : 1;  
                                        
                                      }
                                    }
                                   });
    
    return oParametrosIssqn;
    
    
  };
  
  this.getAtividades = function () {
    
    var oParam        = new Object();
    oParam.sExec      = 'getAtividades';
    oParam.iInscricao = iInscricao;
    
    aAtividades  = new Array();
    
    var oAjax    = new Ajax.Request('iss4_isscalc.RPC.php',
                                   {
                                    method: 'POST',
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    asynchronous:false,
                                    onComplete: function (oAjax) {
                                      
                                      var oRetorno        = eval("("+oAjax.responseText+")");
                 
                                      if (oRetorno.status == 1) {
                                        if (oRetorno.aAtividades.length > 0) {
                                          oRetorno.aAtividades.each(function (oAtividade){
                                            aAtividades.push(oAtividade);
                                          });
                                        } 
                                      }
                                    }
                                   });
    
    return aAtividades;
    
  };
  
  var getAnoCalculo = function () {
    
    var oParam = new Object();
    oParam.sExec = 'getParametrosFiscais';
    
    aAnoCalculo = new Array();
    
    var oAjax = new Ajax.Request('iss4_isscalc.RPC.php',
                                {
                                 method: 'POST',
                                 parameters: 'json='+Object.toJSON(oParam), 
                                 asynchronous:false,
                                 onComplete: function (oAjax) {

                                   var oRetorno        = eval("("+oAjax.responseText+")");

                                   if (oRetorno.status == 1) {
                                     if (oRetorno.aAnosCalculo.length > 0) {
                                       oRetorno.aAnosCalculo.each(function (oAnoCalculo){
                                         aAnoCalculo.push(oAnoCalculo['anocalculo']);
                                       });
                                     } 
                                     iNumeroParcelas = oAnoCalculo['iNumeroParcelas']; 
                                   }
                                 }
                                });
    
    return aAnoCalculo;
    
  };
  
  /**
   * Valida o tipo de c�lculo e libera o parcelamento
   */
  this.validarTipoSelecao = function() {
    
    if ( new Number(me.oCboTipo.getValue()) == 1 ) {
      $(sNomeComponente + "_linhaParcelas").style.display = 'none';
       $(sNomeComponente + "_oCboParcelas").setValue("1");
        return;
    } 
    $(sNomeComponente + "_linhaParcelas").style.display = '';
    return;
  };
  
  this.criarJanela = function() { 
    
    me.oForm.oInput         = document.createElement('input');
    me.oForm.oInput.type    = 'button';
    me.oForm.oInput.id      = 'processar';
    me.oForm.oInput.name    = 'processar';
    me.oForm.oInput.value   = 'Processar';
    me.oForm.oInput.style.margin   = '0 auto';
    me.oForm.oInput.style.clear = 'both';
    me.oForm.oInput.onClick = 'js_processar()';
    
    var sConteudo  = "<div id='" + me.sNomeComponente + "_divCalculoConteudo'></div>                                    ";
    sConteudo     += "<div id='" + me.sNomeComponente + "_divCalculoRodape' style=\"text-align:center\">                ";
    sConteudo     += "  <input type='button' value='Fechar' onClick='" + sInstancia + ".oWindowCalculoIssqn.destroy();'>";
    sConteudo     += "  <input type='button' value='Processar' onClick='" + sInstancia + ".processar();'>               ";
    sConteudo     += "</div>                                                                                            ";    
    me.oWindowCalculoIssqn = new windowAux(me.sNomeComponente + 'oWindowCalculoIssqn', 'C�lculo ISSQN', 800, 500 );
    me.oWindowCalculoIssqn.setContent( sConteudo );
    me.oWindowCalculoIssqn.show();
    return $( me.sNomeComponente + '_divCalculoConteudo' );
  };
  
  /**
   * Processa o C�lculo
   */
  this.processar = function() {
    
    if (oGridAtividades.getSelection().length == 0) {
      
      alert('Nenhuma atividade selecionada para o c�lculo.');
      return false;
      
    }
    
    var oParametros           = new Object();
    oParametros.sExec         = 'processarCalculo';
    oParametros.iInscricao    = iInscricao;
    oParametros.iTipoCalculo  = me.oCboTipo.getValue();
    oParametros.iParcelas     = me.oCboParcelas.getValue();
    oParametros.aSelecionados = new Array();
    
    oGridAtividades.getSelection().each( function( aDadosGrid ){
      oParametros.aSelecionados.push(aDadosGrid[0]);
    });
    
    
    oParametros.iAnoInicial  = "";
    oParametros.iAnoFinal    = "";
    
    if ( getAnoCalculo().length > 0 ) {
      
      oParametros.iAnoInicial  = me.cboAnoCalculoInicial.getValue();
      oParametros.iAnoFinal    = me.cboAnoCalculoFinal.getValue();
    }
    
    var oDadosRequisicao          = new Object();
    oDadosRequisicao.method       = "post";
    oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.onComplete   = function (oAjax) {
      
      var oRetorno        = eval("("+oAjax.responseText+")");
      alert(oRetorno.message.urlDecode().replace(/\\n/,"\n"));
      if (oRetorno.status == 1) {
        window.location.reload();// = window.location.href;
      }
      
    };
    
    oDadosRequisicao.method = "post";

    var oAjax = new Ajax.Request('iss4_isscalc.RPC.php', oDadosRequisicao);
    
  };
  
  /**
   * Cria HTML no Local especificado
   */
  this.show = function( oContainer ) {

    oElementoDestino = oContainer;
    if ( oContainer == null ) {
      oElementoDestino = me.criarJanela();
      lUsaJanela = true;
    }
    me.renderizarHTML();
    me.criarComponentes();
  };  

};
