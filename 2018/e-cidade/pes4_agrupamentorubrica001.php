<?php
/*
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
 
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('dbforms/db_funcoes.php'));

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
<body bgcolor="#cccccc" >

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
        <?php db_input('rh113_descricao', 60, $Irh113_descricao, true, 'text', 3); ?>
      </td>
    </tr>

    <tr>
      <td nowrap title="<?php echo $Trh113_tipo; ?>">
         <?php echo $Lrh113_tipo; ?>
      </td>
      <td> 
        <select id="rh113_tipo" disabled style="width:95px">
          <option value="1">Provento</option>
          <option value="2">Desconto</option>
        </select>
      </td>
    </tr>

  </table>

  <fieldset>

    <legend><strong>Rubricas</strong></legend>

    <div id="ctnGridRubricas"></div>

  </fieldset>

  <br />

  

</fieldset>

<center>
    <input type="button" id="processar" onClick="js_processar();" value="Processar" />
    <input type="button" id="pesquisar" onClick="js_pesquisar();" value="Pesquisar" />
</center>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

<script type="text/javascript">

var sUrlRPC  = 'pes4_agrupamentorubrica.RPC.php';  

js_pesquisar();

function js_pesquisar() {

  var sFuncaoPesquisa = 'func_agrupamentorubrica.php?funcao_js=parent.js_retornoPesquisaAgrupamento|rh113_sequencial|rh113_descricao|rh113_codigo';

  js_OpenJanelaIframe('', 'db_iframe_agrupamentorubrica', sFuncaoPesquisa, 'Pesquisa', true);
}

function js_retornoPesquisaAgrupamento(iAgrupamentoRubrica, sDescricaoAgrupamento, iCodigo) {

  $('rh113_sequencial').value = iAgrupamentoRubrica;
  $('rh113_descricao').value  = sDescricaoAgrupamento;
  $('rh113_codigo').value  = iCodigo; 

  db_iframe_agrupamentorubrica.hide();
  js_getDadosAgrupamentoRubrica();
}

function js_getDadosAgrupamentoRubrica() {

  js_divCarregando('Carregando dados... aguarde', 'divCarregando');

  Rubricas.removerTodasRubricas();

  var oParam = new Object();

  oParam.exec                = 'getRubricas';
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

    oRubrica.sRubrica   				= aRubricas[iIndice].sRubrica;
    oRubrica.sDescricao 				= aRubricas[iIndice].sDescricao.urlDecode();
    oRubrica.iCodigoAgrupamento = aRubricas[iIndice].iCodigoAgrupamento;

    Rubricas.adicionarRubrica(oRubrica);
  }

  Rubricas.recarregarGrid();
}

function js_processar() {

  js_divCarregando('Processando... aguarde', 'divCarregando');

  var oParam    = new Object();

  oParam.exec                = 'alterar';
	oParam.iAgrupamentoRubrica = $F('rh113_sequencial');

	oParam.aRubricas = new Array();
	
	oGridRubricas.getSelection().each(function(aRubricaSelecionada){

		oParam.aRubricas.push(aRubricaSelecionada[0]);
		
  });
  
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
    
  } else  {

	  alert(sMensagem);
	  
	  window.location.href='pes4_agrupamentorubrica001.php';
	  
  }

}




var aAlinhamentos = new Array();
var aHeader       = new Array();
var aWidth        = new Array();

/**
 * Array com headers
 */
aHeader[0] = 'Código';
aHeader[1] = 'Descrição';

/**
 * Tamanho das colunas
 */
aWidth[0] = '10%';  
aWidth[1] = '90%';  

/**
 * Alinhamento das colunas
 */
aAlinhamentos[0] = 'center';
aAlinhamentos[1] = 'left';

/**
 * Monta html da grid 
 */
oGridRubricas 						 = new DBGrid('datagridRubricas');
oGridRubricas.sName 			 = 'oGridRubricas';
oGridRubricas.nameInstance = 'oGridRubricas';
oGridRubricas.setCheckbox(0);
oGridRubricas.setCellWidth( aWidth );
oGridRubricas.setCellAlign( aAlinhamentos );
oGridRubricas.setHeader( aHeader );
oGridRubricas.setHeight(270);
oGridRubricas.show( $('ctnGridRubricas') );
oGridRubricas.clearAll(true);

String.prototype.urlEncode = function() {

  var sString = this;
  encodeURIComponent( tagString( sString ) );
  return sString;
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

			if (oRubrica.iCodigoAgrupamento != '') {				
				lChecked = true;
			} else {
				lChecked = false;
			}

      oGridRubricas.addRow(aLinha, false, null, lChecked);
    }

    oGridRubricas.renderRows(); 
  }

}
</script>

</body>
</html>
