require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
require_once('scripts/widgets/dbcomboBox.widget.js');

/**
 * Constantes usadas nesta classe.
 */
DBViewFormularioFolha.ComboListaFolha = function(){

  this.TIPO_FOLHA_COMPLEMENTAR = 3;
  this.TIPO_FOLHA_SUPLEMENTAR  = 6;
  this.FOLHA_ABERTA            = true;
  this.FOLHA_FECHADA           = false;
};

/**
 * Cria um combobox com as quantidades de folha que existem na folha em questão.
 * @param  {integer} iTipoFolha Código da folha que será pesquisada
 * @param  {boolean} lStatus    Statatus da folha, True para as abertas, False para as fechadas.
 * @return DBComboBox
 */
DBViewFormularioFolha.ComboListaFolha.prototype.pesquisarFolhas = function (iTipoFolha,  iAno, iMes, lStatus) {

  lStatus  = lStatus || false;

  var oParam = {
    sExecucao  : 'BuscaFolhas',
    iTipoFolha : iTipoFolha,
    lStatus    : lStatus,
    iAno       : iAno,
    iMes       : iMes
  };
  
  var oDadosRequisicao = {
    method      : 'post', 
    parameters  : 'json='+Object.toJSON(oParam),
    asynchronous: false,
    onComplete  : function( oRespostaAjax ) {
      var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
       
      if (oRetorno.iStatus == 2) {
        throw oRetorno.sMensagem;
      }

      aDadosFolhas = oRetorno.aNumeroFolhas;
    }
  };
  
  new Ajax.Request('pes4_formularioFolha.RPC.php', oDadosRequisicao);
  
  var iTamanhoArray = aDadosFolhas.length;
  var oDBComboBox   = new DBComboBox('ListaFolhas', null, []);

  for (var iIndice = 0 ; iIndice < iTamanhoArray; iIndice++) {
    oDBComboBox.addItem(aDadosFolhas[iIndice]['rh141_codigo'], aDadosFolhas[iIndice]['rh141_codigo']);
  };

  return oDBComboBox;
};

