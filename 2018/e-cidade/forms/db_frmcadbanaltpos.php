<?php
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
 
/**
 * MODULO: caixa
 * Labels
 */	
$clcadban->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" id="formularioManutencaoPosicoes" method="post" action="">

	<fieldset style="width:750px;">
		<legend><strong>Manutenção de posições:</strong></legend>

		<fieldset>

			<legend><strong>Dados do banco:</strong></legend>

			<table width="100%" border="0">

				<tr>
					<td width="110" nowrap title="<?php echo $Tk15_codigo; ?>">
						<?php echo $Lk15_codigo; ?>
					</td>
					<td colspan="3"> 
						<?php db_input('k15_codigo', 10, $Ik15_codigo, true, 'text', 3, ""); ?>
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Tk15_numcgm; ?>">
						<?php db_ancora($Lk15_numcgm, "", 3); ?>
					</td>
					<td colspan="3"> 
						<?php 
							db_input('k15_numcgm', 10, $Ik15_numcgm, true, 'text', 3, "");
							db_input('z01_nome', 70, $Iz01_nome, true, 'text', 3, '');
						 ?>
					</td>
				</tr>

				<tr>
					<td nowrap title="<?php echo $Tk15_codbco; ?>">
						<?php echo $Lk15_codbco; ?>
					</td>
					<td width="90"> 
						<?php db_input('k15_codbco', 10, $Ik15_codbco, true, 'text', 3, ""); ?>
					</td>

					<td width="90" nowrap title="<?php echo $Tk15_codage; ?>">
						<?php echo $Lk15_codage; ?>
					</td>
					<td> 
						<?php db_input('k15_codage', 10, $Ik15_codage, true, 'text', 3, ""); ?>
					</td>
				</tr>

			</table>

		</fieldset>


		<fieldset>
			<legend><strong>Dados do layout:</strong></legend>

			<table border="0" width="100%">

				<tr>
					<td width="110" nowrap title="<?php echo $Tk15_taman; ?>">
						<?php echo $Lk15_taman; ?>
					</td>
					<td> 
						<?php db_input('k15_taman', 10, $Ik15_taman, true, 'text', $db_opcao, ""); ?>
					</td>
				</tr>

			</table>

			<div id="ctnGridDadosLayout"></div>

		</fieldset>

	</fieldset>

	<br />

	<input onclick="js_alterarLayout();" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>

<script>

/**
 * ---------------------------------------------------------------------
 * Monta GRID dos dados do layout
 * ---------
 * INICIO
 */	 

var sUrlRPC             = 'cai1_manutencaoposicoes.RPC.php';  
var aHeaderLayout       = new Array();
var aWidthLayout        = new Array();
var aAlinhamentosLayout = new Array();

/**
 * Array com os headers da grid
 */
aHeaderLayout[0] = 'Campo';
aHeaderLayout[1] = 'Posição inicial';
aHeaderLayout[2] = 'Tamanho';

/**
 * Array com os tamanhos das colunas
 */
aWidthLayout[0]  = '40%';
aWidthLayout[1]  = '30%';
aWidthLayout[2]  = '30%';

/**
 * Array com os alinhamentos do conteudo das colunas
 */
aAlinhamentosLayout[0]  = 'left';
aAlinhamentosLayout[1]  = 'left';
aAlinhamentosLayout[2]  = 'left';

/**
 * Monta html da grid dos dados da baixa
 */
oGridLayout              = new DBGrid('datagridLayout');
oGridLayout.sName        = 'datagridLayout';
oGridLayout.nameInstance = 'oGridLayout';
//oGridLayout.setCellWidth( aWidthLayout );
oGridLayout.setCellAlign( aAlinhamentosLayout );
oGridLayout.setHeader( aHeaderLayout );
oGridLayout.show( $('ctnGridDadosLayout') );
oGridLayout.clearAll(true);

/**
 * Busca os dados do layout no RPC 
 * Cria função e já executa
 */
(function js_getDadosLayout() {

  var oParametros     = new Object();
  var msgDiv          = "Buscando dados do layout. \n Aguarde ...";

  oParametros.exec         = 'getDadosLayout';  
	oParametros.iCodigoBanco = $F('k15_codigo');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoDadosLayout
    }
  );   
})();

function js_retornoDadosLayout(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");

  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n'); 

  /**
   * Ocorreu erro no RPC  
   */
  if (oRetorno.iStatus > 1) {
    
    alert(sMensagem);
    return;
  } 

  /**
   * Verifica se existe dados da baixa
   */
  if ( !oRetorno.aDadosLayoutHeader ) {
    return false;
  }

	/**
	 * Tamanho dos input da grid, o "size"
	 */
	var iTamanhoInput = 4;

	/**
	 * css do texto do header e detalhes
	 */	 
	var sStyleHeaderColuna = "text-align:left;font-weight:bold; padding:2px 0;background:#f0f0f0;";

	var sMensagemHeader = 'Cabeçalho do arquivo';

	oGridLayout.addRow( [sMensagemHeader] );
	oGridLayout.aRows[0].aCells[0].setUseColspan(true, 3);
	oGridLayout.aRows[0].aCells[0].sStyle  = sStyleHeaderColuna;

	/**
	 * Monta objetos globais que serão enviados pro RPC
	 */	 
	oCampos              = new Object();
	aDadosLayoutHeader   = oRetorno.aDadosLayoutHeader;
	aDadosLayoutDetalhes = oRetorno.aDadosLayoutDetalhes;

  /**
   * Monta os dados do header  
   */
  for ( var iLinha = 0; iLinha < oRetorno.aDadosLayoutHeader.length; iLinha++ ) {

		var oDados = oRetorno.aDadosLayoutHeader[iLinha];

		/**
		 * POSIÇÃO INICIAL
		 * - Cria input para posicao inicial 
		 */
		var oInputPosicao  = "new DBTextField( '"+oDados.nomeCampo + "_posicao',";
	      oInputPosicao += "	'oCampos."+oDados.nomeCampo + "_posicao',        ";
	      oInputPosicao += "	oDados.posicaoInicial,                           ";
	      oInputPosicao += 	iTamanhoInput
	      oInputPosicao += ");                                                ";
				oCampos[oDados.nomeCampo + "_posicao"] = eval(oInputPosicao); 

		/**
		 * TAMANHO
		 * - Cria input para tamanho
		 */
		var oInputTamanho  = "new DBTextField( '"+oDados.nomeCampo + "_tamanho',";
	      oInputTamanho += "	'oCampos."+oDados.nomeCampo + "_tamanho',        ";
	      oInputTamanho += "	oDados.tamanho,                           ";
	      oInputTamanho += 	iTamanhoInput
	      oInputTamanho += ");                                                ";
				oCampos[oDados.nomeCampo + "_tamanho"] = eval(oInputTamanho); 


		/**
		 * Monta array para alimentar grid
		 */	 
		var aLinha = new Array();
		aLinha[0] = oDados.campo.urlDecode();
		aLinha[1] = oCampos[oDados.nomeCampo + "_posicao"].toInnerHtml();
		aLinha[2] = oCampos[oDados.nomeCampo + "_tamanho"].toInnerHtml();        

		oGridLayout.addRow(aLinha);
  }      

	var sMensagemDetalhes = 'Corpo do arquivo';
	oGridLayout.addRow( [sMensagemDetalhes] );
	oGridLayout.aRows[5].aCells[0].setUseColspan(true, 3);
	oGridLayout.aRows[5].aCells[0].sStyle  = sStyleHeaderColuna;

  /**
   * Monta os dados dos detalhes  
	 */
  for ( var iLinha = 0; iLinha < oRetorno.aDadosLayoutDetalhes.length; iLinha++ ) {

		var oDados = oRetorno.aDadosLayoutDetalhes[iLinha];

		/**
		 * POSIÇÃO INICIAL
		 * - Cria input para posicao inicial 
		 */
		var oInputPosicao  = "new DBTextField( '"+oDados.nomeCampo + "_posicao', ";
	      oInputPosicao += "	'oCampos."+oDados.nomeCampo + "_posicao',        ";
	      oInputPosicao += "	oDados.posicaoInicial,                           ";
	      oInputPosicao += 	iTamanhoInput
	      oInputPosicao += ");                                                 ";
				oCampos[oDados.nomeCampo + "_posicao"] = eval(oInputPosicao); 

		/**
		 * TAMANHO
		 * - Cria input para tamanho
		 */
		var oInputTamanho  = "new DBTextField( '"+oDados.nomeCampo + "_tamanho', ";
	      oInputTamanho += "	'oCampos."+oDados.nomeCampo + "_tamanho',        ";
	      oInputTamanho += "	oDados.tamanho,                                  ";
	      oInputTamanho += 	iTamanhoInput
	      oInputTamanho += ");                                                 ";
				oCampos[oDados.nomeCampo + "_tamanho"] = eval(oInputTamanho); 


		/**
		 * Monta array para alimentar grid
		 */	 
		var aLinha = new Array();
		aLinha[0] = oDados.campo.urlDecode();
		aLinha[1] = oCampos[oDados.nomeCampo + "_posicao"].toInnerHtml();
		aLinha[2] = oCampos[oDados.nomeCampo + "_tamanho"].toInnerHtml();        

		oGridLayout.addRow(aLinha);
  }                        
         
  oGridLayout.renderRows();
	return true;
}        

/**
 * Função responsavel por alterar os dados do layout, enviando os dados para o RPC 
 */
function js_alterarLayout() {

	var oDadosHeader   = new Object();
	var oDadosDetalhes = new Object();

	/**
	 * Header
	 * - Percore o array com os dados do heade juntando os campos posicao inicial e tamanho para passar para o RPC  
	 */
	for ( var iIndice = 0; iIndice < aDadosLayoutHeader.length; iIndice++ ) {
		
		var oDados = aDadosLayoutHeader[iIndice];

		var sPosicao = oCampos[oDados.nomeCampo + "_posicao"].getValue();
		var sTamanho = oCampos[oDados.nomeCampo + "_tamanho"].getValue();
		var sCampos  = "sPosicao + sTamanho";
	  eval('oDadosHeader.' + oDados.nomeCampo + ' = ' + sCampos);
	}

	/**
	 * Detalhes
	 *  - Percore o array com os dados do detalhes juntando os campos posicao inicial e tamanho para passar para o RPC  
	 */
	for ( var iIndice = 0; iIndice < aDadosLayoutDetalhes.length; iIndice++ ) {
		
		var oDados = aDadosLayoutDetalhes[iIndice];

		var sPosicao = oCampos[oDados.nomeCampo + "_posicao"].getValue();
		var sTamanho = oCampos[oDados.nomeCampo + "_tamanho"].getValue();
		var sCampos  = "sPosicao + sTamanho";
	  eval('oDadosDetalhes.' + oDados.nomeCampo + ' = ' + sCampos);
	}

  var oParametros     = new Object();
  var msgDiv          = "Alterando dados do layout. \n Aguarde ...";

  oParametros.exec             = 'alterarDadosLayout';  
	oParametros.iCodigoBanco     = $F('k15_codigo');
	oParametros.oDadosHeader     = oDadosHeader;
	oParametros.oDadosDetalhes   = oDadosDetalhes;
	oParametros.iTamanhoRegistro = $F('k15_taman');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoAlterarDados
    }
  );   

}

function js_retornoAlterarDados(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n'); 

  /**
   * Ocorreu erro no RPC  
   */
  if (oRetorno.iStatus > 1) {
    
    alert(sMensagem);
    return;
  } 

	/**
	 * Alterou com suceso, abre tela de pesquisa
	 */
	alert(sMensagem);

	js_pesquisa();
}

/**
 * FIM
 * --------                                                            
 * Monta GRID dos dados do layout                                      
 * ---------------------------------------------------------------------
 */	 


function js_pesquisa(){
	js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?funcao_js=parent.js_preenchepesquisa|k15_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave) {

	db_iframe_cadban.hide();
	<?php
		if ( $db_opcao != 1 ) {
			echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
		}
	?>
}
</script>