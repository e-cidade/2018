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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php
	  db_app::load("scripts.js");
	  db_app::load("strings.js");
	  db_app::load("prototype.js");
	  db_app::load("arrays.js");
	  db_app::load("datagrid.widget.js");
	  db_app::load("grid.style.css");
	  db_app::load("estilos.css");
	  db_app::load("dbcomboBox.widget.js");
	?>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<?php
	
	$oGet = db_utils::postMemory( $_GET );
	$sDataValidadeInicial = db_formatar($oGet->sDataValidadeInicial, 'd');
	$sDataValidadeFinal   = db_formatar($oGet->sDataValidadeFinal, 'd');
	$sNomeTipoAlvara 		 = $oGet->sNomeTipoAlvara;

  /**
   * Retorna os tipos de Alvaras disponiveis
   */
	$aTiposAlvara         = array();
	$aTiposAlvaraVariavel = array();
  $aTiposAlvaraFixo     = array();
	$oIssTipoAlvara       = db_utils::getDao('isstipoalvara');
	$sWhereTipoAlvara     = "q98_instit = " . db_getsession('DB_instit') . " and q98_sequencial <>" . $oGet->iTipoAlvara;
  $sCamposAlvara        = 'q98_sequencial, q98_descricao, q98_tipovalidade, q98_quantvalidade';
	$sSqlTipoAlvara       = $oIssTipoAlvara->sql_query_file(null, $sCamposAlvara, 'q98_sequencial', $sWhereTipoAlvara);
	$rsTipoAlvara         = $oIssTipoAlvara->sql_record($sSqlTipoAlvara);

	if ( $oIssTipoAlvara->numrows > 0 ) {

    $aTiposAlvara[] = 'SELECIONE...';

	  foreach ( db_utils::getCollectionByRecord($rsTipoAlvara) as $oTipoAlvara ) {

	  	if ($oTipoAlvara->q98_tipovalidade == 2) {
	  		$aTiposAlvaraVariavel[] = $oTipoAlvara->q98_sequencial;
	  	} 

	  	if ($oTipoAlvara->q98_tipovalidade == 1) {
	  		$aTiposAlvaraFixo[ $oTipoAlvara->q98_sequencial ] = $oTipoAlvara->q98_quantvalidade;
	  	} 

	    $aTiposAlvara[ $oTipoAlvara->q98_sequencial ] = $oTipoAlvara->q98_descricao;
	  }

	}

	$oJSON = new services_json();

	$sTiposAlvaraVariavel = $oJSON->encode($aTiposAlvaraVariavel);
	$sTiposAlvaraFixo     = $oJSON->encode($aTiposAlvaraFixo);

	$sDataInicial = "";
	$sDataFinal 	= "";

	if ( !empty($oGet->sDataValidadeInicial) ) {
  	$sDataInicial = $oGet->sDataValidadeInicial;
  }

  if ( !empty($oGet->sDataValidadeFinal) ) {
  	$sDataFinal = $oGet->sDataValidadeFinal;
  }
  
  
?>
<body bgcolor="#cccccc">

	<form class="container">

		<fieldset style="width: 700px">
			<legend>Seleção:</legend>
			<table width="700" class="form-container">
				<tr>
					<td width="155">
						<label><b>Tipo de Alvará Origem:</b></label>
					</td>
					<td>
						<?php db_input('sNomeTipoAlvara', null, null, true, 'text', 3, 'class="field-size-max"'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label><b>Data de validade inicial:</b></label>
					</td>
					<td>
						<?php db_input('sDataValidadeInicial', null, null, true, 'text', 3, 'class="field-size3"'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<label><b>Data de validade final:</b></label>
					</td>
					<td>
						<?php db_input('sDataValidadeFinal', null, null, true, 'text', 3, 'class="field-size3"'); ?>
					</td>
				</tr>
			</table>
		</fieldset>


		<!--Grid com os resultados-->
		<fieldset>
			<legend>Lista de Inscrições</legend>
			<table width="700">
				<tr>
					<td>
						<div id="containerResultados"></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<!--Bloco com as opções de destino-->
		<fieldset>
			<legend>Alvará Destino</legend>
			<table class="form-container">
				<tr>
					<td width="155"><label> Tipo de Alvará Destino:</label></td>
					<td>
							<?php 
								db_select('tipo_alvara', $aTiposAlvara, true, 4, "onChange='js_tipoAlvaras();'");
							?>
					</td>
				</tr>
				<tr>
					<td>
						<label>Dias para vencimento:</label>
					</td>
					<td>
						<?php
							db_input("diasVencimento", 20, 1, true, 'text', 1);
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset>
							<legend>Observação</legend>
							<textarea id="descricaoAlvara" name="descricaoAlvara"></textarea>
						</fieldset>
					</td>
				</tr>
			</table>
		</fieldset>
    <table class="form-container" width="100%">
      <tr>
        <td align="center" rel="ignore-css">
          <input name="processar" type="button" onClick="js_processarAlvara()" value="Processar">
          <input type="button" onClick="window.history.back(1)" value="Voltar" />
        </td>
      </tr>
    </table>
	</form>

	<?
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
</body>
<script type="text/javascript">

/**
* Verifica se o tipo de alvara selecionado é do tipo variavel, 
* se for habilita o campo data de validade caso contrario desabilita.
*
* @access public
* @return void
*/
function js_tipoAlvaras() {

  var aTiposAlvarasVariaveis = <?=$sTiposAlvaraVariavel?>;
  var oTiposAlvarasFixos     = <?=$sTiposAlvaraFixo?>;
  var iTipoAlvara 					 = $F('tipo_alvara');

  $('diasVencimento').value    = '';

  /**
   * Tipo de alvara fixo
   * - Altera o valor de dias para vencimento, usa o valor da tabela isstipoalvara 
   */
  for (  iTipoAlvaraFixo in oTiposAlvarasFixos ) {

    if ( iTipoAlvaraFixo == iTipoAlvara ) {
      $('diasVencimento').value = oTiposAlvarasFixos[ iTipoAlvaraFixo ];
    }

  }

  /**
   * Tipo de alvara variavel, habilita botao dias para vencimento 
   */
  if ( aTiposAlvarasVariaveis.in_array(iTipoAlvara) ) {

    $('diasVencimento').disabled = false;
    $('diasVencimento').style.background = "#FFF";

  } else {

    /**
     * Tipo de alvara não variavel
     * - desabilita botao dias para vencimento
     */
    $('diasVencimento').disabled = true;
    $('diasVencimento').style.background = "#DEB887";
    $('diasVencimento').style.color = "#444";
  }
}

/**
 * Cria uma instancia de DBGrid
 */
var oGridResultadoBusca = new DBGrid('resultadoBusca');

(function() {
  
  js_tipoAlvaras();

  /**
   * MOnta Grid com os resultados da busca
   */
   oGridResultadoBusca.nameInstance = 'oGridResultadoBusca';
   oGridResultadoBusca.setCheckbox(1);
   oGridResultadoBusca.setCellWidth(new Array( '0', '10%', '40%', '10%', '20%', '20%'));
   oGridResultadoBusca.setCellAlign(new Array('left', 'center', 'left', 'center', 'center', 'center'));
   oGridResultadoBusca.setHeader(new Array( 'M', 'Inscrição','Nome/Razão Social', 'Alvará', 'Data de liberação', 'Data de validade'));
   oGridResultadoBusca.show($('containerResultados'));
   oGridResultadoBusca.clearAll(true);

   /**
    * REaliza a busca pelas rubricas a partir dos parametros enviados por $_GET
    */ 
    var iTipoAlvara         = <?php echo $oGet->iTipoAlvara; ?>;
    var sDataInicial        = <?php echo "'".$sDataInicial."'"; ?>;
    var sDataFinal          = <?php echo "'".$sDataFinal  ."'"; ?>;
    var sUrl                = 'iss4_tranformacaogeralalvara.RPC.php';
    var oQuery              = {};
        oQuery.metodo       = 'consultaAlvaras';
        oQuery.iTipoAlvara  = iTipoAlvara;
        oQuery.sDataInicial = sDataInicial;
        oQuery.sDataFinal   = sDataFinal;

    var oAjax = new Ajax.Request( sUrl, {
                                          method: 'post',
                                          parameters: "json=" + JSON.stringify(oQuery),
                                          onComplete: js_retornoAlvaras
                                        }
                                )


})();

/**
 * Trata o Retorno dos alvaras
 *
 * @access public
 * @return void
 */
function js_retornoAlvaras( oAlvaras ) {

   var aRetorno = eval("("+oAlvaras.responseText+")");
   var aAlvaras = aRetorno.dados;

   oGridResultadoBusca.clearAll(true);

   for ( var iIndiceAlvara = 0; iIndiceAlvara < aAlvaras.length; iIndiceAlvara++ ) {

     var oAlvara = aAlvaras[iIndiceAlvara];

     oGridResultadoBusca.addRow(
       [
         oAlvara.q120_sequencial, 
         oAlvara.q123_inscr, 
         oAlvara.z01_nome,
         oAlvara.q120_issalvara,
         js_formatar(oAlvara.q120_dtmov,"d"),
         js_formatar(oAlvara.data_validade,"d")
       ],
       null,
       null,
       true
     );

   }

   oGridResultadoBusca.renderRows();
}

/**
 * Função responsável pela validação e tratamento
 * dos dados.
 *
 * @access public
 * @return void
 */
function js_processarAlvara() {

  var iTipoAlvara 		 = $F('tipo_alvara');
  var iDiasVencimento	 = $F('diasVencimento');
  var sDescricaoAlvara = $F('descricaoAlvara');

  var aAlvaras    = new Array();
  var aLinhasGrid = oGridResultadoBusca.getSelection();
  
  for ( var iLinhaGrid = 0; iLinhaGrid < aLinhasGrid.length; iLinhaGrid++ ) {

    var oDadosAlvara = new Object();

    oDadosAlvara.iAlvara       = aLinhasGrid[ iLinhaGrid ][4]; 
    oDadosAlvara.iMovimentacao = aLinhasGrid[ iLinhaGrid ][0]; 

    aAlvaras.push(oDadosAlvara);
  }

  /**
   * Valida se foi selecionado pelo menos um alvará
   */
   if (aAlvaras.length == 0) {

     alert('Por favor, selecione pelo menos 1 alvará.');
     return false;
   }

  /**
   * Valida se foi informada a data do alvara.
   */
   if ( iDiasVencimento == '') {

     if ( !$('diasVencimento').disabled ) {

       alert('Por Favor, informe a quantidade de dias para o vencimento.');
       return false;
     }

     iDiasVencimento = '0';
   }

   /**
    * Valida se não informado 0 dias para o vencimento, para o tipo de destino com vencimento variavel.
    */
    var aTiposAlvarasVariaveis = <?=$sTiposAlvaraVariavel?>;
    if( aTiposAlvarasVariaveis.in_array(iTipoAlvara) && iDiasVencimento == 0) {

      alert('Por Favor, informe a quantidade de dias para o vencimento.');
      return false;
    }

   /**
    * Valida de a descrição do alvara foi informada
    */
   if (sDescricaoAlvara == '') {

     alert('Por favor, informe uma observação.');
     return false;
   }

   /**
    * Valida se foir informado o tipo de alvara de origem
    */
   if( $('tipo_alvara').value == 0 ) {
 
      alert('Por favor, informe o Tipo de alvará destino.');
      return false;
   }


   var sUrl   = 'iss4_tranformacaogeralalvara.RPC.php';
   var oQuery = {};

   oQuery.metodo           = 'transformarAlvaras';
   oQuery.aAlvaras 				 = aAlvaras;
   oQuery.iTipoAlvara      = iTipoAlvara;
   oQuery.iDiasVencimento  = iDiasVencimento;
   oQuery.sDescricaoAlvara = sDescricaoAlvara.urlEncode();

   var oAjax = new Ajax.Request( 
     sUrl, 
     {
       method     : 'post',
       parameters : "json=" + JSON.stringify(oQuery),
       onComplete : js_retornoProcessarAlvara
     }
   );
};

/**
 * Retorno do processar alvar
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoProcessarAlvara(oAjax) {

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Erro no RPC 
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  alert(sMensagem);
  window.history.back(1);
}

</script>

</html>