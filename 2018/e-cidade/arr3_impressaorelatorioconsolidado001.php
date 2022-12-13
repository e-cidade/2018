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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_sql.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");

$iInstit               = db_getsession("DB_instit");

$oConsolidacaoDebitos  = db_utils::getDao('consolidacaodebitos');

$rsConsolidacaoDebitos = $oConsolidacaoDebitos->sql_record($oConsolidacaoDebitos->sql_query_file(null,
                                                                                                 "*",
                                                                                                 "k161_sequencial desc"));

$oHeader               = db_utils::fieldsMemory($rsConsolidacaoDebitos, 0);

$sDisabled             = 'disabled';

$aFiltros              = array(0 => '---', 
                               1 => '---', 
                               2 => '---');

$iQuantidade           = $oConsolidacaoDebitos->numrows;

if ($iQuantidade > 0) {
	
	$sDisabled = '';	
	$aFiltros = explode('|', $oHeader->k161_filtrosselecionados);
		
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php 
db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, dbtextField.widget.js");
?>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC">
<form name="form1" id="form1">

<fieldset style="margin: 25px auto 10px; width: 550px">
  <legend><strong>Consolidação de Dados:</strong></legend>

	<fieldset><legend><strong>Dados da Geração:</strong></legend>
	
		<table align="center">
			<tr>
				<td>
					<strong>Período inicial:</strong>
				</td>
				<td>
					<?php
						$dDataInicial = $aFiltros[0];
						db_input('dDataInicial', 10, null, true, 3);
					?>
				</td>		
				<td>
					<strong>Período final:</strong>
				</td>
				<td>
					<?php
						$dDataFinal = $aFiltros[1];
						db_input('dDataFinal', 10, null, true, 3);
					?>
				</td>
			</tr>		
			<tr>
				<td><strong>Data do cálculo:</strong></td>
				<td>
					<?php
					  
					  $dDataCalculo = implode('/',array_reverse(explode('-',$aFiltros[2])));
					  db_input('dDataCalculo', 10, null, true, 3);
					?>				
				</td>
			</tr>
		</table>
	
	</fieldset>

	<fieldset><legend><strong>Opções do Relatório:</strong></legend>
	
		<table align="left" cellspacing="5" cellpadding="2" style="width: 100%">
			<tr>
				<td style="width:25%"><strong>Quebra por Relatório:</strong></td>
				<td align="left">
					<?php
						$aQuebraPagina = array(1 => 'SIM', 0 => 'NÃO');
						db_select('lQuebraPagina', $aQuebraPagina, true, 1);
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">		
					<div style="width: 100%" id="ctnGridRelatorios"></div>
				</td>
			</tr>
		</table>
		
	</fieldset>
	
</fieldset>

<center>
	<input type="button" name="processar" id="processar" value="Processar" onclick="js_processar()" <?php echo $sDisabled; ?> />
</center>
</form>

<?php 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>

var aCamposGrid = new Array();

var iQuantidade = <?php echo $iQuantidade; ?>;

sUrl            = 'arr3_impressaorelatorioconsolidado.RPC.php';

js_criaGrid();

js_listaRelatorios();

function js_listaRelatorios() {

  if (iQuantidade == 0) {
    
    alert('Não existem relatórios gerados. Utilizar a rotina de geração em disco.');

    return false;
    
  }

  var oParam  = new Object();
  var msgDiv  = "Buscando relatórios. \n Aguarde ...";

  oParam.sExec            = 'listaRelatorios';
  oParam.iCodigoRelatorio = <?php echo $oHeader->k161_sequencial; ?>;
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxRelatorios  = new Ajax.Request(
		sUrl,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParam),
      onComplete : js_retornoRelatorios
    }
  );   
}

function js_retornoRelatorios(oAjax) {

  js_removeObj('msgBox');
  
  var oRetorno  = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 0) {
    
    alert(oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n'));
    return;
    
  } 

  var fFuncaoPadrao = oGridRelatorios.selectSingle;
  oGridRelatorios.selectSingle = function (oCheckbox, iIdLinhaGrid, oTableRow) {
  
    fFuncaoPadrao(oCheckbox, iIdLinhaGrid, oTableRow);	
  	  
  }

  var aLinha = new Array();
	  
  aLinha[1]  = ['1', 'Descontos concedidos por regras'];
  aLinha[2]  = ['2', 'Débitos cancelados'];
  aLinha[3]  = ['3', 'Prescrição de dívida'];
  aLinha[4]  = ['4', 'Inscrição em dívida'];
  aLinha[6]  = ['5', 'Pagamentos geral'];
  aLinha[7]  = ['6', 'Descontos concedidos (cota única)'];
  aLinha[8]  = ['7', 'Resumo geral da dívida'];

  oRetorno.aRelatorios.each( function (aRow) {
    oGridRelatorios.addRow(aLinha[aRow[0]]);
  });

	oGridRelatorios.renderRows();
	  
}

function js_criaGrid() {
	
	oGridRelatorios                   = new DBGrid('datagridRelatorios');
  oGridRelatorios.sName             = 'datagridRelatorios';
  oGridRelatorios.nameInstance      = 'oGridRelatorios';
  oGridRelatorios.setCheckbox       (0);
  
  oGridRelatorios.setCellWidth      ( new Array('0', '100%') );
  oGridRelatorios.setCellAlign      ( new Array('center','left') );
  oGridRelatorios.setHeader         ( new Array('Código','Relatório') );
    
  oGridRelatorios.show              ( $('ctnGridRelatorios') );
  oGridRelatorios.clearAll          (true);
  
}

function js_processar() {
	
	var oParam           = new Object();
  var sMsgDiv          = "Processando impressão dos relatórios. \n Aguarde ...";
  oParam.sExec         = 'processaRelatorios';
  oParam.aSelecionados = new Array();
  oParam.lQuebraPagina = $F('lQuebraPagina')
  
  aSelecionados = oGridRelatorios.getSelection();

  aSelecionados.each( function (aRow) {
    oParam.aSelecionados.push(aRow[0]);
  });
  
  js_divCarregando(sMsgDiv, 'msgBox');
   
  var oAjaxRelatorios  = new Ajax.Request(
		sUrl,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParam),
      onComplete : js_confirma
    }
  );
}

function js_confirma(oAjax){

  js_removeObj('msgBox');
  
  var sNomeArquivo = '';
  
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 1) {

    sNomeArquivo = oRetorno.sNomeArquivo.urlDecode();

    js_montarlista(sNomeArquivo + '#Download do relatório.','form1');
    
  } else { 

    alert(oRetorno.sMensagem.urlDecode());

    return false;
    
  }
  
}
</script>
</body>
</html>