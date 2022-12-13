<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_rhpromocao_classe.php");
require_once("classes/db_rhavaliacao_classe.php");
require_once("classes/db_rhparam_classe.php");

require_once("dbforms/db_funcoes.php");

$oDaoRHPromocao     = new cl_rhpromocao;
$iCodigoInstituicao = db_getsession('DB_instit');
$oDaoRHParam        = new cl_rhparam;

$sSqlRHParam        = $oDaoRHParam->sql_query_file($iCodigoInstituicao, 'h36_pontuacaominpromocao');
$rsRHParam          = $oDaoRHParam->sql_record($sSqlRHParam);

if( $oDaoRHParam->numrows > 0 ) {

  $oRHParam         = db_utils::fieldsMemory($rsRHParam,0);
  $pontuacaoMinimia = $oRHParam->h36_pontuacaominpromocao;
}

$clrotulo      = new rotulocampo;
$clrotulo->label("z01_nome");

$oDaoRHPromocao->rotulo->label();
$oDaoRHPromocao->rotulo->tlabel();

$h72_dtfinal_dia = date('d', db_getsession("DB_datausu"));
$h72_dtfinal_mes = date('m', db_getsession("DB_datausu")); 
$h72_dtfinal_ano = date('Y', db_getsession("DB_datausu"));

$oGet	  = db_utils::postmemory($HTTP_GET_VARS);

if( isset($oGet->iCodigoMatricula) ) {

  $sCampos  = 'h72_regist,    ';
  $sCampos .= 'z01_nome,      ';
  $sCampos .= 'h72_dtinicial, ';
  $sCampos .= 'h72_sequencial' ;
	$sSqlPromocao = $oDaoRHPromocao->sql_query( null, $sCampos, null, "h72_regist = {$oGet->iCodigoMatricula} and h72_ativo is true" );
	
	$rsPromocao   = $oDaoRHPromocao->sql_record($sSqlPromocao);
	$oPromocao    = db_utils::fieldsMemory($rsPromocao, 0);
	
  $iCodigoPromocao   = $oPromocao->h72_sequencial;
  $h72_sequencial    = $iCodigoPromocao;
	$z01_nome          = $oPromocao->z01_nome;
	$h72_regist        = $oPromocao->h72_regist;
  $h72_dtinicial     = $oPromocao->h72_dtinicial;
  $h72_dtinicial_dia = date('d', strtotime($h72_dtinicial));
  $h72_dtinicial_mes = date('m', strtotime($h72_dtinicial));
  $h72_dtinicial_ano = date('Y', strtotime($h72_dtinicial));
}

db_app::load('scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js, dbtextField.widget.js, datagrid.widget.js');
db_app::load('estilos.css, grid.style.css');
?>
<script src="scripts/classes/DBViewCursos.classe.js"></script>
<style>
fieldset.form {
  margin: 10px auto 5px auto;
	width: 700px;
}
</style>
</head>

<body bgcolor="#CCCCCC" onload="js_getTotalPontos();">
<br />
<div id="ctnFechamentoPromocao">
<center>
	<form name="form1" id="form1">
		<fieldset class="form">
			<legend>
				<strong>Dados da Promoção:</strong>
			</legend>
			
			<table align="center">
        <tr>
          <td>
            <strong>Código da promoção: </strong>
          </td>
          <td>
            <?php db_input('h72_sequencial', 10, $Ih72_sequencial, true, 'text', 3); ?>
          </td>
        </tr>
				<tr>
					<td width="180" title="<?php echo $Th72_regist; ?>">
					  <?php echo $Lh72_regist; ?>
					</td>
					
					<td>
						<?php 
						  db_input('h72_regist', 10, $Ih72_regist, true, 'text', 3);
						  db_input('z01_nome'  , 55, $Iz01_nome   , true, 'text', 3);
						?>
					</td>
				</tr>
				
				<tr>
					<td title="<?php echo $Th72_dtinicial; ?>">
						<?php echo $Lh72_dtinicial; ?>
					</td>
					<td title="<?php echo $Th72_dtinicial; ?>">
						<?	
							db_inputdata('h72_dtinicial', $h72_dtinicial_dia, $h72_dtinicial_mes, $h72_dtinicial_ano, true, 'text', 3);
						?>
					</td>
				</tr>

				<tr>
					<td title="<?php echo $Th72_dtfinal; ?>">
						<?php echo $Lh72_dtfinal; ?>
					</td>
					<td title="<?php echo $Th72_dtfinal; ?>">
						<?	
							db_inputdata('h72_dtfinal', $h72_dtfinal_dia, $h72_dtfinal_mes, $h72_dtfinal_ano, true, 'text', 1);
						?>
					</td>
				</tr>

			</table>

		</fieldset>
		
		<fieldset class="form">
			<legend><strong>Pontuação:</strong></legend>
			
			<table>
        <tr>
          <td colspan="4">
            <?php db_ancora("<b>Consulta Perdas</b>","js_consultaPerdas();",1); ?>
          </td>
        </tr>

			  <tr>
			  	<td colspan="4">
				  	<fieldset>
				  		<legend><strong>Requisitos de avaliação:</strong></legend>
              <div id="ctnTipoAvaliacao"></div>
            </fieldset>
          </td>
			  </tr>
			  
			  <tr>
			  	<td colspan="4" align="center">
				  	<fieldset>
				  		<legend><strong>Observações:</strong></legend>
				  	<?php 
				  		db_textarea('h72_observacao', 5, 90, $Ih72_observacao, true, 'text', 1);
				  	?> 
				  	</fieldset>
					</td>
			  </tr>

			  <tr>
			  	<td colspan="4" align="center">
              <div id="ctnCursos" onClick="js_getTotalPontos();"></div>
					</td>
			  </tr>

        <tr>
          <td width="130">
            <strong>Pontuação minima:</strong>
          </td>
          <td>
            <?php db_input('pontuacaoMinimia', 10, null, true, 'text', 3); ?>
          </td>
        </tr>

        <tr>
          <td width="130">
            <strong>Pontuação atingida:</strong>
          </td>
          <td>
            <?php db_input('pontuacaoAtingida', 10, null, true, 'text', 3); ?>
          </td>
        </tr>

        <input type="hidden" id="totalAvaliacoes" value="" />

			</table>
		</fieldset>
	
		<input type="button" name="btnProcessar" id="btProcessar" value="Processar" onclick="js_salvar();" /> 
	</form>
</center>

</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var iPontuacaoMinima = <?php echo $pontuacaoMinimia; ?>;
var oGet = js_urlToObject(window.location.search);

/*------------------ WindowsAux--------------------*/

var sConteudo       = "<div id='headerPerdas'></div>";
    sConteudo      += "<br /><fieldset style='width:650px;margin:0 auto;'><legend><b>Lista de Perdas do Servidor: </b></legend><div id='ctnPerdas'></div></fieldset>";

if (typeof(oConsultaPerdas) != 'undefined') {
  oConsultaPerdas.destroy();
}

oConsultaPerdas = new windowAux("oConsultaPerdas", "Consulta Perdas", 730, 400); 
oConsultaPerdas.setContent(sConteudo);

/**
 * Abre WindowsAux das perdas quando clicado no ancora "consulta perdas"
 */
function js_consultaPerdas() {

  oConsultaPerdas.show(45);
  js_buscaDadosPerdas(); 
}

/*------------------ GRIDS-------------------------*/

/**
 * Grid dos cursos
 */
var oCursos = new DBViewCursos("oCursos", false);
    oCursos.setCodigoPromocao($F('h72_sequencial'));
    oCursos.setAlturaGrid(110);
    oCursos.show($('ctnCursos'));

/**
 * Grid das perdas
 */   
var oPerdas              = new DBGrid("oPerdas"); 
    oPerdas.nameInstance = "oPerdas";
    oPerdas.sName        = "oPerdas";
    oPerdas.setHeight(200);
    oPerdas.setCellAlign(new Array("center","left", "right", "right"));
    oPerdas.setCellWidth(new Array("16%","50%", "17%", "17%"));
    oPerdas.setHeader(new Array('Código', 'Tipo de Perda', 'Valor', 'Máximo Permitido'));
    oPerdas.show( $('ctnPerdas') );
    oPerdas.clearAll(true);

js_buscaDadosPerdas(); 

var sMsg  = "<b>Servidor: </b>" + $('z01_nome').value + " <BR>";
    sMsg += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Matrícula: </b> " + $('h72_regist').value;
oMessage  = new DBMessageBoard('msgboard', 
                               'Dados das Perdas de Direito a Promoção',
                               sMsg,
                               $('headerPerdas'));
oMessage.show();

var oGridTipoAvaliacao                 = new DBGrid('TipoAvaliacao');
    oGridTipoAvaliacao.nameInstance = "TipoAvaliacao";
    oGridTipoAvaliacao.sName        = "TipoAvaliacao";
    oGridTipoAvaliacao.setHeight(110);
    oGridTipoAvaliacao.setCellAlign(new Array("center", "left","right"));
    oGridTipoAvaliacao.setCellWidth(new Array("20%", "60%","20%"));
    oGridTipoAvaliacao.setHeader(new Array('Código', 'Tipo de Avaliação','Pontos'));
    oGridTipoAvaliacao.show( $('ctnTipoAvaliacao') );
    oGridTipoAvaliacao.clearAll(true);

js_listaTipoAvaliacao();

/*------------------ RPC --------------------*/

/**
 * Busca os dados da perda por RPC e retorna pra funcao js_retornoDadosPerdas
 */   
function js_buscaDadosPerdas () {

  js_divCarregando('Pesquisando Perdas.', 'msgAjax');
  
  var oParam             = new Object();
  var oAjax              = new Object();
  
  oParam.sExec           = "getPerdas";
  oParam.iCodigoPromocao = $F('h72_sequencial');
  oParam.iMatricula      = $F('h72_regist');
  oParam.dtInicial       = js_formatar( $F('h72_dtinicial'), 'd' );
  oParam.dtFinal         = js_formatar( $F('h72_dtfinal'), 'd' );
  
  oAjax.method           = 'POST';
  oAjax.asynchronous     = false;
  oAjax.parameters       = 'json=' + Object.toJSON(oParam);
  oAjax.onComplete       = js_retornoDadosPerdas;
                         
  oPerdas.clearAll(true);
  var oRequest           = new Ajax.Request("rec4_promocao.RPC.php", oAjax);
  
}

/**
 * Pega o retorno do RPC e adiciona na grid
 */   
function js_retornoDadosPerdas(oAjax) {
  
  js_removeObj('msgAjax');

  var oRetorno = eval("(" + oAjax.responseText +")");

  if (oRetorno.iStatus == 2) { 
    alert(oRetorno.sMessage.urlDecode().replace(/\\\\n/g,'\n') );
    return;
  }

  for (var iIndice = 0; iIndice < oRetorno.aDadosRetorno.length; iIndice++) {

     with (oRetorno.aDadosRetorno[iIndice]) {
        var aCelulas = new Array(h70_sequencial, h70_descricao.urlDecode(), h16_quant, h70_dias);
     }
     oPerdas.addRow(aCelulas, false);  
  }
  
  oPerdas.renderRows();
}

/**
 * Quando clicar em processar, encerra promoção e salva os dados por RPC
 */   
function js_salvar() {

  if (new Number($F("pontuacaoAtingida")) < new Number(iPontuacaoMinima) ) {
    
	  if (!confirm ("Pontuação Mínima não atingida,\nFinalizar Promoção ? ")) {
	    return false;
	  }
    
  }

  js_divCarregando('Fechando Promoção', 'msgAjax');
  
  var oParam              = new Object();
  var oAjax               = new Object();

  oParam.sExec            = "fechamentoPromocao";
  oParam.iCodigoPromocao  = $F('h72_sequencial');
  oParam.iMatricula       = $F('h72_regist');
  oParam.dtInicial        = js_formatar( $F('h72_dtinicial'), 'd' );
  oParam.dtFinal          = js_formatar( $F('h72_dtfinal'), 'd' );
  oParam.observacao       = $F('h72_observacao');
  oParam.aCursos          = oCursos.getCursosSelecionados(false);  
  oParam.aTipoPerdas      = js_getTipoPerdas();
  oParam.totalPontos      = js_getTotalPontos();
  
  oAjax.method            = 'POST';
  oAjax.asynchronous      = false;
  oAjax.parameters        = 'json=' + Object.toJSON(oParam);
  oAjax.onComplete        = js_retornoFechamento;
                          
  var oRequest            = new Ajax.Request("rec4_promocao.RPC.php", oAjax);

}

function js_retornoFechamento(oAjax) {
  
  js_removeObj('msgAjax');

  var oRetorno = eval("(" + oAjax.responseText +")");

  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n') );
  if (oRetorno.iStatus == 1) { 
    window.location.href = "rec4_fechamentopromocao001.php";
	}
  return;
}

/**
 * Busca os tipos de avaliacao por RPC e retorna pra funcao js_retornoTipoAvaliacao
 */ 
function js_listaTipoAvaliacao() {

  var oAjax        = new Object();
  var oParam       = new Object();
                            
  var msgDiv                  = "Pesquisando Tipos de Avaliação \n Aguarde ...";
  oParam.exec                 = 'getTotalTiposAvaliacoes';
  oParam.iCodigoPromocao      = $F('h72_sequencial');

  js_divCarregando(msgDiv,'msgBox');

  oAjax.method      = 'POST';
  oAjax.asynchronous= false;
  oAjax.parameters  = 'json=' + Object.toJSON(oParam);
  oAjax.onComplete  = js_retornoTipoAvaliacao;

  var oAjaxLista    = new Ajax.Request("rec4_avaliacao.RPC.php", oAjax);
}

/**
 * funcao para montar a grid com os registros de tipo de avaliacao retornado do RPC
 */ 
function js_retornoTipoAvaliacao(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.iStatus == 1) {
      
      $('totalAvaliacoes').value =  oRetorno.iTotalPontosAvaliacoes;

      oGridTipoAvaliacao.clearAll(true);

      oRetorno.dados.each(function (oDado, iInd) {       

        var aRow    = new Array();  
            aRow[0] = oDado.h69_sequencial;
            aRow[1] = oDado.h69_descricao.urlDecode();
            aRow[2] = oDado.h76_pontos;
            oGridTipoAvaliacao.addRow(aRow);

      });

      oGridTipoAvaliacao.renderRows(); 
      
    } else {

      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}

/**
 * Cria array com as perdas
 * @return array
 */ 
function js_getTipoPerdas(){

  var aRows    = oPerdas.aRows;                                              
  var aRetorno = new Array(); 
  
  aRows.each(function(oLinha) {

    var oRetorno = new Object();
    oRetorno.iCodigoTipoPerda  = oLinha.aCells[0].getContent();
    oRetorno.sDescricaoPerda   = oLinha.aCells[1].getContent().urlDecode();
    oRetorno.iValorPerda       = oLinha.aCells[2].getContent();
    oRetorno.iMaximoPermitidos = oLinha.aCells[3].getContent();
    aRetorno.push( oRetorno );

  });    

  return aRetorno;
}

function js_getTotalPontos(){

  var iTotalAvaliacao = parseInt($F('totalAvaliacoes'));
  var iTotalCursos    = parseInt(oCursos.getTotalCargaHoraria(true));

  var iSomaTotal      = iTotalAvaliacao + iTotalCursos;

  $('pontuacaoAtingida').value = iSomaTotal;

  return iSomaTotal;
  
}

</script>