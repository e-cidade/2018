<?
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');

$clrotulo->label('o40_orgao');
$clrotulo->label('o40_descr');

$clrotulo->label('r70_estrut');
$clrotulo->label('r70_descr');

?>

<html>
<head>
<?php
	db_app::load("scripts.js, prototype.js, strings.js, estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" onload="js_escondeResumo();">

	<form name="form1" id="form1" method="post" action="" onsubmit="return js_verifica();">
		<fieldset align="center" style="margin: 25px auto 0 auto; width: 550px">
			<legend><b>Relatório de Cestas Básicas</b></legend>		
		  <table align="center" width="500">		      
		      <tr>
		        <td width="40%" align="left" nowrap title="Digite o Ano / Mes de competência" >
		        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
		        </td>
		        <td>
		          <?
		           $DBtxt23 = db_anofolha();
		           db_input('iAno', 4, $IDBtxt23, true, 'text', 1, '')
		          ?>
		          &nbsp;/&nbsp;
		          <?
		           $DBtxt25 = db_mesfolha();
		           db_input('iMes', 2, $IDBtxt25, true, 'text', 1, '')
		          ?>
		        </td>
		      </tr>

		      <tr>
		      	<td><strong>Tipo de resumo:</strong></td>
		      	<td>
		      		<?php
		      			$aTipoResumo = array('1'=>'Geral' , '2'=>'Lotação', '3'=>'Órgão');
		      			db_select('iTipoResumo', $aTipoResumo, true, 1, "style='width: 150px;' onchange=\"js_mostraResumo(this.value)\"");
		      		?>
		      	</td>
		      </tr>
		      		      
	      		<?php 
	      		
							$oComboOrgao = new cl_arquivo_auxiliar();
							$oComboOrgao->cabecalho                     = "<strong>Orgãos</strong>";
							$oComboOrgao->codigo                        = "o40_orgao"; //chave de retorno da func
							$oComboOrgao->descr                         = "o40_descr"; //chave de retorno
							$oComboOrgao->nomeobjeto                    = "oOrgao";
							$oComboOrgao->funcao_js                     = "js_mostraOrgao";
							$oComboOrgao->funcao_js_hide                = "js_mostraOrgaoHide";
							$oComboOrgao->func_arquivo                  = "func_orcorgao.php"; //func a executar
							$oComboOrgao->nomeiframe                    = "db_iframe_orcorgao";
							$oComboOrgao->db_opcao                      = 1;
							$oComboOrgao->tipo                          = 2;
							$oComboOrgao->top                           = 0;
							$oComboOrgao->linhas                        = 10;
							$oComboOrgao->vwidth                        = 420;
							$oComboOrgao->Labelancora                   = '<strong>Código</strong>';
							$oComboOrgao->passar_query_string_para_func = "&instit=" . db_getsession('DB_instit');
							$oComboOrgao->lFuncaoPersonalizada          = true;
							$oComboOrgao->funcao_gera_formulario ();
									      			
	      		?>
	      		<script>
	      		  $('tr_inicio_oOrgao').style.display = 'none';
		      	</script>	
	      		<?php 
	      		
							$oComboLotacao = new cl_arquivo_auxiliar();
							$oComboLotacao->cabecalho                     = "<strong>Lotações</strong>";
							$oComboLotacao->codigo                        = "r70_codigo";
							$oComboLotacao->descr                         = "r70_descr";
							$oComboLotacao->nomeobjeto                    = "oLotacao";
							$oComboLotacao->funcao_js                     = "js_mostraLotacao";
							$oComboLotacao->funcao_js_hide                = "js_mostraLotacaoHide";
							$oComboLotacao->func_arquivo                  = "func_rhlota.php"; //func a executar
							$oComboLotacao->nomeiframe                    = "db_iframe_lotacao";
							$oComboLotacao->db_opcao                      = 1;
							$oComboLotacao->tipo                          = 2;
							$oComboLotacao->top                           = 0;
							$oComboLotacao->linhas                        = 10;
							$oComboLotacao->vwidth                        = 420;
							$oComboLotacao->Labelancora                   = '<strong>Código</strong>';
							$oComboLotacao->nome_botao                    = 'btnLancarLotacao';
							$oComboLotacao->lFuncaoPersonalizada          = true;
							$oComboLotacao->passar_query_string_para_func = "&instit=" . db_getsession('DB_instit');							
							$oComboLotacao->funcao_gera_formulario ();
							
	      		?>
		      <script>
		      	$('tr_inicio_oLotacao').style.display = 'none';
		      </script>
		      <tr>
		      	<td><strong>Tipo de ordem:</strong></td>
		      	<td>
		      		<?php
		      			$aOrdem = array('1'=>'Alfabética', '2'=>'Numérica');
		      			db_select('iTipoOrdem', $aOrdem, true, 1, "style='width: 150px;'");
		      		?>
		      	</td>
		      </tr>
		      <tr>
		      	<td><strong>Vínculo:</strong></td>
		      	<td>
		      		<?php
		      			$aVinculo = array('1'=>'Todos', '2'=>'Ativo', '3'=>'Inativo', '4'=>'Pensionistas', '5'=>'Inativos/pensionistas');
		      			db_select('iVinculo', $aVinculo, true, 1, "style='width: 150px;'");
		      		?>
		      	</td>
		      </tr>
		      
		    </table>
			    		    
		</fieldset>
		<br>
		<center>
	    <input name="assinatura"  id="assinatura"  type="button" value="Assinatura"  onclick="js_relatorio('Assinatura');" >&nbsp;
			<input name="conferencia" id="conferencia" type="button" value="Conferência" onclick="js_relatorio('Conferencia');">
	  </center>
  </form>
	<?
	  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
<script>
	
function js_relatorio(relatorio){

	var iAno = document.form1.iAno.value;
	var iMes = document.form1.iMes.value;

	var iTipoResumo = document.form1.iTipoResumo.value;
	var iTipoOrdem  = document.form1.iTipoOrdem.value;
	var iVinculo    = document.form1.iVinculo.value;
	
	var aLotacoes = '';
	var aOrgaos   = '';

	if (iTipoResumo == 2) {
		aLotacoes = js_campo_recebe_valores_oLotacao();	
	} else if (iTipoResumo == 3) {
		aOrgaos = js_campo_recebe_valores_oOrgao();
	} else {
		aLotacoes = '';
		aOrgaos = '';
	}

	if (iTipoResumo == 2 && aLotacoes == '') {
		alert('Escolha uma ou mais lotações');
		return false;
	} else if (iTipoResumo == 3 && aOrgaos == '') {
		alert('Escolha um ou mais órgãos');
		return false;
	}

	var sVariaveisUrl = '?iAno='+iAno+'&iMes='+iMes+'&iTipoResumo='+iTipoResumo+'&iTipoOrdem='+iTipoOrdem+'&iVinculo='+iVinculo+'&aLotacoes='+aLotacoes+'&aOrgaos='+aOrgaos+'&iVinculo='+iVinculo+'&iTipoOrdem='+iTipoOrdem;

	if (relatorio == 'Conferencia') {
		
		jan = window.open('pes2_itacesta1002.php'+sVariaveisUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		jan.moveTo(0,0);
		
	} else if (relatorio == 'Assinatura') {
		
		jan = window.open('pes2_itacesta002.php'+sVariaveisUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		jan.moveTo(0,0);
		
	}


}

function js_escondeResumo(){

	/*$('tr_inicio_oLotacao').setAttribute('style','display:none');
	$('tr_inicio_oOrgao').setAttribute('style','display:none');*/
	
}

function js_mostraResumo(valor) {

	if (valor == '1') {
		
		$('tr_inicio_oLotacao').style.display = 'none';
		$('tr_inicio_oOrgao').style.display = 'none';
	} else if (valor == '2') {
		
		$('tr_inicio_oLotacao').style.display = '';
		$('tr_inicio_oOrgao').style.display = 'none';
	} else if (valor == '3') {
		
		$('tr_inicio_oOrgao').style.display = '';
		$('tr_inicio_oLotacao').style.display = 'none';
	}
	
}

</script>  
</body>
</html>