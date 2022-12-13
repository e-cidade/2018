<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 
require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'dbforms/db_funcoes.php';

$oRotulo  = new rotulocampo;
$db_opcao = 2;

$oDaoAgrupamentorubrica        = db_utils::getDao('agrupamentorubrica');
$oDaoAgrupamentorubricarubrica = db_utils::getDao('agrupamentorubricarubrica');

$oDaoAgrupamentorubrica->rotulo->label();
$oDaoAgrupamentorubricarubrica->rotulo->label();

$oRotulo->label("rh27_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<br /> <br />

<fieldset style="width:600px;margin: 0 auto;">

  <legend><strong>Agrupamento de rubrica</strong></legend>

  <table border="0" width="600">

    <tr>
      <td nowrap title="<?php echo $Trh113_codigo; ?>">
         <?php echo $Lrh113_codigo; ?>
      </td>
      <td> 
        <?php db_input('rh113_codigo', 10, $Irh113_codigo, true, 'text', 3); ?>
      </td>
    </tr>

    <tr>
      <td nowrap title="<?php echo $Trh113_descricao; ?>">
         <?php echo $Lrh113_descricao; ?>
      </td>
      <td> 
        <?php db_input('rh113_sequencial', 10, null, true, 'hidden', 3); ?>
        <?php db_input('rh113_descricao', 79, $Irh113_descricao, true, 'text', 3); ?>
      </td>
    </tr>

    <tr>
      <td nowrap title="<?php echo $Trh113_tipo; ?>">
         <?php echo $Lrh113_tipo; ?>
      </td>
      <td> 
        <select id="rh113_tipo" disabled>
          <option value="1">Provento</option>
          <option value="2">Desconto</option>
        </select>
      </td>
    </tr>

  </table>

  <fieldset>

    <legend><strong>Rubricas</strong></legend>

    <fieldset style="margin:0 0 10px 0;">

      <legend><strong>Lançar rubrica:</strong></legend>

      <table border="0" width="600">
        <tr>

          <td nowrap title="<?php echo $Trh114_rubrica; ?>">
            <?php db_ancora($Lrh114_rubrica, "js_buscarRubrica(true);", $db_opcao); ?>
          </td>

          <td>
            <?php db_input('rh114_rubrica', 4, $Irh114_rubrica, true, 'text', $db_opcao, "onchange='js_buscarRubrica(false);'"); ?>
            <?php db_input('rh27_descr', 56, $Irh27_descr, true, 'text', 3, ''); ?>
          </td>

          <td>
            <input type="button" onClick="js_lancarRubrica()" value="Lançar" />
          </td>

        </tr>
      </table>

    </fieldset>

    <div id="ctnGridRubricas"></div>

  </fieldset>

  <br />

  <center>
    <input type="button" id="processar" onClick="js_processar();" value="Processar" />
    <input type="button" id="pesquisar" onClick="js_pesquisar();" value="Pesquisar" />
  </center>

</fieldset>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

<script type="text/javascript">

var sUrlRPC  = 'pes4_agrupamentorubrica.RPC.php';  

js_pesquisar();
js_montaGrid();

function js_pesquisar() {

  var sFuncaoPesquisa = 'func_agrupamentorubrica.php?funcao_js=parent.js_retornoPesquisaAgrupamento|rh113_sequencial|rh113_descricao';

  js_OpenJanelaIframe('', 'db_iframe_agrupamentorubrica', sFuncaoPesquisa, 'Pesquisa', true);
}

function js_retornoPesquisaAgrupamento(iAgrupamentoRubrica, sDescricaoAgrupamento) {

  $('rh113_sequencial').value = iAgrupamentoRubrica;
  $('rh113_descricao').value  = sDescricaoAgrupamento; 

  db_iframe_agrupamentorubrica.hide();
  js_getDadosAgrupamentoRubrica();
}

function js_getDadosAgrupamentoRubrica() {

  js_divCarregando('Carregando dados... aguarde', 'divCarregando');

  Rubricas.removerTodasRubricas();

  var oParam = new Object();

  oParam.exec                = 'getDadosAgrupamentoRubrica';
  oParam.iAgrupamentoRubrica = $F('rh113_sequencial');

  var oAjax = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParam),
      onComplete : js_retornoGetDadosAgrupamentoRubrica
    }
  );
}

function js_retornoGetDadosAgrupamentoRubrica(oAjax) {

  js_removeObj('divCarregando');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n');
  
  /**
   * Ocorreu um erro no rpc, exception
   */   
  if ( oRetorno.iStatus > 1 ) {
    
    alert(sMensagem);
    return false;
  }  

  $('rh113_codigo').value = oRetorno.nCodigo;

  /**
   * Tipo de agrupamento - provisao ou desconto
   */   
  $('rh113_tipo').value = oRetorno.iTipo;
	$('rh113_tipo').style.background = '#DEB887';
	$('rh113_tipo').style.color      = '#555';

  /**
   * Percorre array de rubricas e adiciona na grid
   */   
  var iQuantidadeRubricas = oRetorno.aRubricas.length;
  var aRubricas           = oRetorno.aRubricas;

  for ( iIndice = 0; iIndice < iQuantidadeRubricas; iIndice++ ) {
  
    var oRubrica = new Object();

    oRubrica.sRubrica   = aRubricas[iIndice].sRubrica;
    oRubrica.sDescricao = aRubricas[iIndice].sDescricao;

    Rubricas.adicionarRubrica(oRubrica);
  }

  Rubricas.recarregarGrid();
}

function js_processar() {

  if ( !js_validaFormulario() ) {
    return false;
  } 

  js_divCarregando('Processando... aguarde', 'divCarregando');

  var oParam    = new Object();
  var aRubricas = new Array();

  oParam.exec                = 'alterar';
	oParam.iAgrupamentoRubrica = $F('rh113_sequencial');
  oParam.aRubricas           = Rubricas.getCodigoRubricas();

  var oAjax = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters : 'json='+Object.toJSON(oParam),
      onComplete : js_retornoProcessar
    }
  );
}

function js_retornoProcessar(oAjax) {

  js_removeObj('divCarregando');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n');

  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }  

  alert(sMensagem);
  js_recarregar();
}

function js_validaFormulario() {

  if (oGridRubricas.aRows.length == 0) {

    alert('Escolha pelo menos uma rubrica');
    return false;
  }

  return true;
}

/**
 * Lancar rubricas 
 * 
 * @access public
 * @return void
 */
function js_lancarRubrica() {

  var sRubrica          = $F('rh114_rubrica');
  var sDescricaoRubrica = $F('rh27_descr');

  if ( sRubrica == '' ) {
    return false;
  }

  var iRubricasLancadas = 0;

  /**
   * Verifica se existe rubrica lancada e percorre elas para nao deixar lancar novamente
   */   
  if ( Rubricas.getQuantidadeRubricas() > 0 ) {

    iRubricasLancadas = Rubricas.getQuantidadeRubricas();

    for ( var iIndice = 0; iIndice < iRubricasLancadas; iIndice++) {

      if ( sRubrica == oGridRubricas.aRows[iIndice].aCells[0].getValue() ) {

        alert('Rubrica ' + sRubrica + ' já lançada');
        $('rh114_rubrica').value = '';
        $('rh27_descr').value    = '';
        return;
      }  
    }
  }

  var oRubrica = new Object();

  oRubrica.sRubrica   = $F('rh114_rubrica');
  oRubrica.sDescricao =  $F('rh27_descr');

  Rubricas.adicionarRubrica(oRubrica);
  Rubricas.recarregarGrid();

  $('rh114_rubrica').value = '';
  $('rh27_descr').value    = '';
}

function js_removerRubrica(iRubricaRemover) {

  Rubricas.removerRubrica(iRubricaRemover);
  Rubricas.recarregarGrid();
}

/**
 * Busca rubricas 
 * 
 * @param bool $lMostraJanela - true : Abre janela para escolher rubrica
 *                              false: Pesquisa e mostra resultado no campo descricao
 * @access public
 * @return void
 */
function js_buscarRubrica(lMostraJanela) {

  var sFuncaoPesquisa = 'func_rhrubricas.php?funcao_js=';

  if ( lMostraJanela ) {
    sFuncaoPesquisa += 'parent.js_retornoRubricaAncora|rh27_rubric|rh27_descr';
  } else {
  
    var sRubrica = $F('rh114_rubrica');

    if ( sRubrica == '' ) { 

      $('rh27_descr').value = '';
      return;
    }

    sFuncaoPesquisa += 'parent.js_retornoRubricaInput&pesquisa_chave=' + sRubrica;
  }

  js_OpenJanelaIframe('', 'db_iframe_rhrubricas', sFuncaoPesquisa, 'Pesquisa', lMostraJanela);
}

/**
 * Retorno pela pesquisa do campo, disparado pelo onchange
 */   
function js_retornoRubricaInput(sDescricao, lErro){

  $('rh27_descr').value = sDescricao; 

  if ( lErro ) { 

    $('rh114_rubrica').focus(); 
    $('rh114_rubrica').value = ''; 
  }
}

/**
 * Retorno da pesquisa de rubrica disparado pelo ancora
 */   
function js_retornoRubricaAncora(sRubrica, sDescricao) {

  $('rh114_rubrica').value = sRubrica;
  $('rh27_descr').value    = sDescricao;

  db_iframe_rhrubricas.hide();
}

/**
 * Monta grid 
 */   
function js_montaGrid() {

  var aAlinhamentos = new Array();
  var aHeader       = new Array();
  var aWidth        = new Array();

  /**
   * Array com headers
   */
  aHeader[0] = 'Código';
  aHeader[1] = 'Descrição';
  aHeader[2] = 'Remover';

  /**
   * Tamanho das colunas
   */
  aWidth[0] = '10%';  
  aWidth[1] = '75%';  
  aWidth[2] = '15%';  

  /**
   * Alinhamento das colunas
   */
  aAlinhamentos[0] = 'left';
  aAlinhamentos[1] = 'left';
  aAlinhamentos[2] = 'center';

  /**
   * Monta html da grid 
   */
  oGridRubricas = new DBGrid('datagridRubricas');
  oGridRubricas.sName = 'datagridRubricas';
  oGridRubricas.nameInstance = 'oGridRubricas';
  oGridRubricas.setCellWidth( aWidth );
  oGridRubricas.setCellAlign( aAlinhamentos );
  oGridRubricas.setHeader( aHeader );
  oGridRubricas.allowSelectColumns(true);
  oGridRubricas.show( $('ctnGridRubricas') );
  oGridRubricas.clearAll(true);
}

String.prototype.urlEncode = function() {

  var sString = this;
  encodeURIComponent( tagString( sString ) );
  return sString;
}

function js_recarregar() {
  document.location.href = 'pes4_agrupamentorubrica.php';
}

Rubricas = {

  /**
   * Array com as rubricas 
   */   
  aRubricas : new Array(),
  
  /**
   * Adiciona uma rubrica a grid
   * @param Object oRubrica - objeto com codigo e descricao da rubrica
   */   
  adicionarRubrica : function(oRubrica) {
    Rubricas.aRubricas.push(oRubrica);
  },

  /**
   * Remove apenas uma rubrica da grid
   * @param integer iIndice
   */   
  removerRubrica : function( iIndice ) {
    Rubricas.aRubricas.splice(iIndice, 1);
  },

  /**
   * Remove todas as rubricas da grid
   */   
  removerTodasRubricas : function() {
    Rubricas.aRubricas = new Array();
  },

  /**
   * Retorna total de rubricas da grid
   */   
  getQuantidadeRubricas : function () {
    return Rubricas.aRubricas.length;
  },  

  /**
   * Recarrega grid
   */   
  recarregarGrid : function () {
  
    oGridRubricas.clearAll(true);

    for ( var iIndice = 0; iIndice < Rubricas.getQuantidadeRubricas(); iIndice++ ) {

      oRubrica = Rubricas.aRubricas[iIndice];

      var aLinha = new Array();

      aLinha[0] = oRubrica.sRubrica;
      aLinha[1] = oRubrica.sDescricao;

      sDisabled = '';

      aLinha[2] = '<input type="button" value="Remover" onclick="js_removerRubrica(' + iIndice + ')" ' + sDisabled + ' />';

      oGridRubricas.addRow(aLinha, null, null, true);
    }

    oGridRubricas.renderRows(); 
  },

  /**
   * Retorna um array somente com o codigo das rubricas
   */   
  getCodigoRubricas: function() {

    var aCodigoRubricas = new Array();

    for ( var iIndice = 0; iIndice < Rubricas.getQuantidadeRubricas(); iIndice++) {
      aCodigoRubricas[iIndice] = Rubricas.aRubricas[iIndice].sRubrica;
    }

    return aCodigoRubricas;
  }

}
</script>

</body>
</html>