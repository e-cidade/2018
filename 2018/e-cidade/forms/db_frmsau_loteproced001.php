<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: saude


$clprontproced->rotulo->label ();
$clsau_lote->rotulo->label();
$clsau_lotepront->rotulo->label ();
$clrotulo = new rotulocampo ( );

//Login
$clrotulo->label ( "login" );
$clrotulo->label ( "nome" );
//Especmedico
$clrotulo->label ( "sd27_i_codigo" );
//CGS
$clrotulo->label ( "z01_v_nome" );
$clrotulo->label ( "z01_i_cgsund" );
$clrotulo->label ( "z01_t_obs" );
//Prontuarios
$clrotulo->label ( "sd24_i_codigo" );
$clrotulo->label ( "sd24_i_unidade" );
$clrotulo->label ( "sd24_v_motivo" );
$clrotulo->label ( "sd24_v_pressao" );
$clrotulo->label ( "sd24_f_peso" );
$clrotulo->label ( "sd24_f_temperatura" );
$clrotulo->label ( "sd24_i_profissional" );
$clrotulo->label ( "sd24_c_digitada" );
//Médico
$clrotulo->label ( "sd03_i_codigo" );
$clrotulo->label ( "z01_nome" );
//CBO
$clrotulo->label ( "sd04_i_cbo" );
$clrotulo->label ( "rh70_sequencial" );
$clrotulo->label ( "rh70_estrutural" );
$clrotulo->label ( "rh70_descr" );
//Procedimento
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
//Cid
$clrotulo->label ( "sd70_i_codigo" );
$clrotulo->label ( "sd70_c_cid" );
$clrotulo->label ( "sd70_c_nome" );

?>


<form name="form1" method="post" action="">
<center>
<table border="0" style="width: 650px;">
	<tr>
		<td align="center">
		<fieldset><legend><b>Consulta Médica</b></legend>
		<table border="0">
			<!-- Lote / login -->
			<tr>
				<td nowrap title="<?=@$Tsd58_i_codigo?>" width="86">
				<?=@$Lsd58_i_codigo?>
				</td>
				<td>
				<?
				db_input ( 'sd58_i_codigo', 10, $Isd58_i_codigo, true, 'text', 3, "" );
				db_input ( 'sd59_i_codigo', 10, $Isd59_i_codigo, true, 'hidden', 3, "" );
				?>
				</td>
				<td colspan="2" align="right" title="<?=@$Tsd58_i_login?>">
				<?=@$Lsd58_i_login?>
				<?db_input ( 'sd58_i_login', 10, $Isd58_i_login, true, 'hidden', $db_opcao, "" );?>
				<?db_input ( 'sd24_i_unidade', 10, $Isd24_i_unidade, true, 'hidden', $db_opcao, "" );?>
				<?db_input ( 'login', 10, $Ilogin, true, 'text', 3, '' );?>
				</td>
			</tr>
				<!-- Segunda parte do formulário -->
				<?
					unset($intQuant);
					include 'forms/db_frmsau_loteproced002.php';
				?>

		</table>
		</fieldset>
		<center>
		<p>
		<!--
		<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
			type="submit"
			id="db_opcao"
			value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?> Procedimento"
			<?=($db_processar == true ? "disabled" : "")?>
			onFocus='js_foco(this, "done");'
			onFocus='js_foco(this, "btnGravar" );'
		>
		-->
		<input name="btnGravar"
			type="button"
			id="btnGravar"
			value="Gravar"
			onFocus='js_foco(this, "btnCancelar" );'
			onclick="js_gravar()"
		>
		<input name="cancelar"
			type="button"
			id="btnCancelar"
			value="Cancelar"
			onFocus='js_foco(this, "btnProcessar" );'
			onclick="js_limpadados()"
		>
		<input name="btnProcessar"
			type="button"
			id="btnProcessar"
			value="Processar"
			onFocus='js_foco(this, "btnVoltar");'
			onclick="js_processar()"
		>
		<input name="voltar"
			type="button"
			id="btnVoltar"
			value="Voltar"
			onFocus='js_foco(this, "<?=$idarq==1?'btnNovoLote':'btnFAALote'?>");'
			onclick="js_voltar()"
			<?=($db_botao1 == true ? "disabled" : "")?>
		>
		<?
		if ($idarq == 1) {
			?>
			<input name="novolote"
				type="button"
				id="btnNovoLote"
				onFocus='js_foco(this, "btnFAALote");'
				onclick="booNovoLote = true; js_voltar();"
				value="F2-Novo Lote"
			>
				<!-- onclick="js_novolote()" -->
			<?
		}
		?>
		<input name="faalote"
			type="button"
			id="btnFAALote"
			value="FAA Lote"
			<?=($db_botao1 == true ? "disabled" : "")?>
			onclick="js_pesquisafaalote()"
			onFocus='js_foco(this, focoInclusao.name );'
			onblur="focoInclusao.focus()"
		>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset><legend><b>Procedimentos</b></legend>
			<div id="gridProcedimento"></div>
			</fieldset>
		</td>
	</tr>
</table>

</center>
</form>


<style>
 .classAlterar{background-color: #BEBEBE;}
 .classExcluir{background-color: #FF0000;}
 .classNull{background-color: #FFFFFF;}
</style>

<script>

/**
 * seta documento para atualizar o enter
 * set url do arquivo RPC
 */
strURL         = 'sau1_sau_loteprocedRPC.php';
booNovoLote    = false;
focoInclusao   = $('z01_nome');
objAtualFocado = focoInclusao;
booValidaCID   = false;

//Evento onkeydow do documento
document.onkeydown = function(evt) {
 	var evt = (evt) ? evt : (window.event) ? window.event : "";
	var array_types = new Array('button','submit','reset');
	var valor_types = js_search_in_array(array_types,evt.target.type);
	if (evt.keyCode == 13) {
		if (nextfield == 'done' || valor_types ) {
			return true;
		} else {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		}
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}else if( evt.keyCode == 113  ){ //F2
		booNovoLote = true;
		js_voltar();
	}
}

//Evento onclick do documento
document.onclick = function(evt){
	var objTarget = evt.target;

	if( objTarget.type != 'select-one' )
		if( objTarget.readOnly == undefined || objTarget.readOnly == true )
			objAtualFocado.focus();
}
/**
 * Função para atualizar foco atual e próximo foco
 * objAtual - foco no objeto atual
 * strField - string com nome do próximo foco
 */
function js_foco(objAtual, strField ){
	objAtualFocado = objAtual;
	nextfield=strField;
}


/**
 * Campos para validação;
 * array[0] - campo para foco;
 * array[1] - menssage;
 */
objValidaCampo = new Object();
objValidaCampo.sd29_i_profissional = new Array('sd03_i_codigo', 'Profissional não informado.' );
objValidaCampo.sd29_i_procedimento = new Array('sd63_c_procedimento', 'Procedimento não informado.' );
objValidaCampo.sd29_d_data         = new Array('sd29_d_data', 'Data não informada.', 'js_validadata' );

objProfissional = new Object();

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno ){
	var objAjax = new Ajax.Request(
                         strURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                          				js_divCarregando( strCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
}
/**
 * Inicializa GRID
 */
function js_init() {

	var arrHeader = new Array ( "Registro",
			    				'<?=str_replace(":","",$Lsd29_i_codigo)?>',
				    			'<?=str_replace(":","",$Lsd29_d_data)?>',
					    		'<?=str_replace(":","",$Lsd29_c_hora)?>',
						    	'<?=str_replace(":","",$Lsd63_c_procedimento)?>',
						    	'<?=str_replace(":","",$Lsd63_c_nome)?>',
						    	"Opções"
						      );

	objGridProcedimento              = new DBGrid('objGridProcedimento');
	objGridProcedimento.nameInstance = 'objGridProcedimento';
	objGridProcedimento.setHeight(140);
	objGridProcedimento.setCellWidth (["9%", "10%", "12%", "8%", "15%","27%", "18%"]);
	objGridProcedimento.setHeader( arrHeader );
	objGridProcedimento.setCellAlign (["left","center","center", "center","center","center", "center"]);
	objGridProcedimento.show($('gridProcedimento'));

	//Pesquisa Procedimentos
	var objParam             = new Object();
	objParam.exec            = "getGridProcedimentos";
	objParam.sd58_i_codigo   = $F('sd58_i_codigo');

	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoGridProcedimentos' );
}

/**
 * Botão incluir
 */
function js_gravar(){
	var objParam             = new Object();
	var arrInputs = $$('input, select, textarea');

	if( js_validaForm() ){
		if( $F('tmp_i_registro') == '' ){
			objParam.exec = "Incluir";
		}else{
			objParam.exec = "Alterar";
			objParam.intIterator = $F('tmp_i_registro')-1;
		}
		arrInputs.each(function(input, intIterator) {
			if (input.type == 'text' || input.type == 'hidden' || input.type == 'textarea' || input.type == 'select-one') {
				var evlTmp = 'objParam.'+input.name+'='+'\''+input.value+'\'; \n';
				eval( evlTmp );
			}
		});

		js_ajax( objParam, 'Aguarde, incluindo....', 'js_retornoGridProcedimentos' );
	}

}
/**
 * Retorno Grid Procedimentos
 */
function js_retornoGridProcedimentos( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

  	objGridProcedimento.clearAll(true);

  	if (objRetorno.status == 1) {

     	if (objRetorno.itens != undefined && objRetorno.itens.length > 0) {

     		objRetorno.itens.each(function (objProcedimentos, intIterator) {
	     		var arrLinha = new Array();
	          	arrLinha[0]  = intIterator+1;
	          	arrLinha[1]  = objProcedimentos.sd29_i_codigo;
	          	arrLinha[2]  = objProcedimentos.sd29_d_data;
	          	arrLinha[3]  = objProcedimentos.sd29_c_hora.urlDecode();
	          	arrLinha[4]  = objProcedimentos.sd63_c_procedimento.urlDecode();
	          	arrLinha[5]  = objProcedimentos.sd63_c_nome.urlDecode();
	          	arrLinha[6]  =  "<input type='button' value='Alterar' onclick='js_opcoesProcedimentoAlterar(\""+objProcedimentos.sd29_i_codigo+"\", "+intIterator+", \"alterar\")'>";
	          	arrLinha[6] +=  "<input type='button' value='Excluir' onclick='js_opcoesProcedimentoExcluir(\""+objProcedimentos.sd29_i_codigo+"\", \""+objProcedimentos.sd29_i_procedimento+"\", "+intIterator+", \"excluir\")'>";

	          	objGridProcedimento.addRow(arrLinha);
	          	booNovoLote = false;
         	});
         	objGridProcedimento.renderRows();
     	}

   		js_limpadados();

  	} else {
  		message_ajax( objRetorno.message.urlDecode() );
  	}

	//Preenche com informações do Profissiona do Agendamento
   	if(objRetorno.profissional != undefined && objRetorno.profissional.length > 0 ){
     	objRetorno.profissional.each(function (objProfissional, intIterator) {
	   		$('sd03_i_codigo').value       = objProfissional.sd03_i_codigo;
	   		$('z01_nome').value            = objProfissional.z01_nome.urlDecode();
	   		$('sd29_i_profissional').value = objProfissional.sd27_i_codigo;
	   		$('rh70_sequencial').value     = objProfissional.rh70_sequencial;
	   		$('rh70_estrutural').value     = objProfissional.rh70_estrutural.urlDecode();
	   		$('rh70_descr').value          = objProfissional.rh70_descr.urlDecode();
	   		$('sd29_d_data').value         = objProfissional.sd23_d_consulta;
	   		$('sd29_c_hora').value         = objProfissional.sd23_c_hora.urlDecode();
   		});
   		$('sd63_c_procedimento').focus();
   	}

}

/**
 * Função Opções Procedimento
 */
function js_opcoesProcedimento( sd29_i_codigo, intIterator, opcao ){
	var strURL = '<?=basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] )?>';
	strURL += '?opcao='+opcao;
	strURL += '&sd29_i_codigo='+sd29_i_codigo;
	strURL += '&sd58_i_codigo='+$F('sd58_i_codigo');
	strURL += '&idarq=<?=@$idarq?>';

	//location = strURL;
}

/**
 * Botão Excluir Grid Procedimentos
 */
function js_opcoesProcedimentoExcluir( sd29_i_codigo, sd29_i_procedimento, intIterator, opcao ){
	var objParam            = new Object();

	//gridobjGridProcedimento.rows[(intIterator+1)].style.backgroundColor = 'gray';
    $('objGridProcedimentorowobjGridProcedimento' + intIterator).className = 'classExcluir';
	if( confirm('Confirma exclusão do registro?' ) ){
		objParam.exec                = "Excluir";
		objParam.sd29_i_codigo       = sd29_i_codigo;
		objParam.sd58_i_codigo       = $F('sd58_i_codigo');
		objParam.sd29_i_procedimento = sd29_i_procedimento;
		objParam.intIterator   = intIterator;

		js_ajax( objParam, 'Aguarde, excluindo....', 'js_retornoGridProcedimentos' );
	}
	//gridobjGridProcedimento.rows[(intIterator+1)].style.backgroundColor = '';
    $('objGridProcedimentorowobjGridProcedimento' + intIterator).className	 = '';

}
/**
 * Botão Altear Grid Procedimentos
 */
function js_opcoesProcedimentoAlterar( sd29_i_codigo, intIterator, opcao ){
	var objParam            = new Object();

	objParam.exec         = "getAlterar";
	objParam.intIterator   = intIterator;

	$('objGridProcedimentorowobjGridProcedimento' + intIterator).className = 'classAlterar';
	js_ajax( objParam, 'Aguarde....', 'js_retornoProcedimentoAlterar' );
}

/**
 * Retorno Procedimento Alterar
 */
function js_retornoProcedimentoAlterar( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

	if (objRetorno.status == 1) {
		if (objRetorno.itens.length > 0) {
			//Atribui valor dos objetos de retorno ao formulário
			var arrInput = $$('input, textarea');
			for( var i=0; i < arrInput.length; i++ ){
				var elmX = arrInput[i];
				var evlTmp = eval( 'objRetorno.itens[0]["'+elmX.name+'"]' );
				if( evlTmp != undefined ){
					//elmX.value = eval( 'objRetorno.itens[0].'+elmX.name+'.urlDecode()' );
					elmX.value = evlTmp;
					var campo = new Number(evlTmp);
					if( isNaN(campo)){
						elmX.value = eval( 'objRetorno.itens[0].'+elmX.name+'.urlDecode()' );
					}
				}
			}
			$('sd03_i_codigo').focus();
 		}
	}
}
/**
 * Função para limpar dados
 */
function js_limpadados(){
	//var arrInput = $$('input, textarea');
	var arrInput = $('divProcedimento').getElementsByTagName('input');
	for( var i=0; i < arrInput.length; i++ ){
		var elmX = arrInput[i];
		if( elmX.type == 'text' || elmX.type == 'hidden' || elmX.type == 'textarea' ){
			elmX.value = '';
		}
	}

	//$('sd29_d_data_dia').value   = '<?=date("d",db_getsession("DB_datausu"));?>';
	//$('sd29_d_data_mes').value   = '<?=date("m",db_getsession("DB_datausu"));?>';
	//$('sd29_d_data_ano').value   = '<?=date("Y",db_getsession("DB_datausu"));?>';
	//$('sd29_d_data').value       = '<?=date("d/m/Y",db_getsession("DB_datausu"));?>';
	$('sd29_c_hora').value       =  '' //'<?=date("H").":".date("m");?>';
	$('sd29_t_tratamento').value = '';

   	$('z01_nome').focus();
}

/**
 * Pesquisa Profissional pelo código
 */
function js_pesquisasd03_i_codigo(mostra){
	var strParam = '';
	strParam += 'func_medicos.php';
	strParam += '?chave_sd06_i_unidade='+$F('sd24_i_unidade');

	if(mostra==true){
		strParam += '&campoFoco=sd03_i_codigo';
		strParam += '&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome';
		js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa',true);
	}else{
		if(document.form1.sd03_i_codigo.value != ''){
			strParam += '&campoFoco=z01_nome';
			strParam += '&pesquisa_chave='+$F('sd03_i_codigo');
			strParam += '&funcao_js=parent.js_mostramedicos';
			js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa',false);
		}else{
			$('z01_nome').value = '';
		}
		$('z01_nome').focus();
	}
}
/**
 * Pesquisa Profissional pelo nome
 */
function js_pesquisaz01_nome(mostra){
	if($F('z01_nome') != ''){
		/**
		var objParam            = new Object();
		objParam.exec           = "getEspecialidade";
		objParam.sd24_i_unidade = $F('sd24_i_unidade');
		objParam.z01_nome       = $F('z01_nome');

		js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoEspecialidade' );
		*/
		var strParam = '';
		strParam += 'func_medicos.php';
		strParam += '?chave_sd06_i_unidade='+$F('sd24_i_unidade');
		strParam += '&campoFoco=z01_nome';
		strParam += '&chave_z01_nome='+URLEncode( $F('z01_nome') );
		strParam += '&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome';
		js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa',true);
	}
}
function js_mostramedicos(chave,erro){
	document.form1.z01_nome.value = chave;
	if(erro==true){
		document.form1.sd03_i_codigo.focus();
		document.form1.sd03_i_codigo.value = '';
		document.form1.sd29_i_profissional.value = '';
		document.form1.rh70_estrutural.value = '';
		document.form1.rh70_descr.value = '';
	}else{
		//js_pesquisasd04_i_cbo(true);
		var objParam            = new Object();
		objParam.exec           = "getEspecialidade";
		objParam.sd24_i_unidade = $F('sd24_i_unidade');
		objParam.sd03_i_codigo  = $F('sd03_i_codigo');

		js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoEspecialidade' );
	}
}
function js_mostramedicos1(chave1,chave2){
	document.form1.sd03_i_codigo.value = chave1;
	document.form1.z01_nome.value = chave2;
	db_iframe_medicos.hide();

	//js_pesquisasd04_i_cbo(true);
	var objParam            = new Object();
	objParam.exec           = "getEspecialidade";
	objParam.sd24_i_unidade = $F('sd24_i_unidade');
	objParam.sd03_i_codigo  = $F('sd03_i_codigo');

	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoEspecialidade' );
}
/**
 * Retorno pesquisa especialidade do profissional
 */
function js_retornoEspecialidade( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

	if (objRetorno.status == 1) {
		if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objEspecialidade, iIteracao) {
     			//Prenche Especialidade
     			js_mostrarhcbo1(objEspecialidade.sd03_i_codigo,
     							objEspecialidade.z01_nome.urlDecode(),
     							objEspecialidade.sd27_i_codigo,
     							objEspecialidade.rh70_estrutural.urlDecode(),
     							objEspecialidade.rh70_descr.urlDecode(),
     							objEspecialidade.sd27_i_rhcbo);
         	});
         	$('sd63_c_procedimento').focus();
 		}
	} else {
		js_pesquisasd04_i_cbo(true);
	}
}


/*
 * Pesquisa Especialidade
 */
function js_pesquisasd04_i_cbo(mostra){
	var strParam = '';
	strParam += 'func_especmedico.php';
	strParam += '?funcao_js=parent.js_mostrarhcbo1|sd03_i_codigo|z01_nome|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo';
	strParam += '&chave_sd04_i_unidade='+$F('sd24_i_unidade');
	strParam += '&chave_sd04_i_medico='+$F('sd03_i_codigo');
	strParam += '&campoFoco=rh70_estrutural';

	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_especmedico',strParam,'Pesquisa Especialidade',true);
	}else{
		if(document.form1.rh70_estrutural.value != ''){
			var objParam             = new Object();
			objParam.exec            = "getEspecialidade";
			objParam.sd24_i_unidade  = $F('sd24_i_unidade');
			objParam.sd03_i_codigo   = $F('sd03_i_codigo');
			objParam.rh70_estrutural = $F('rh70_estrutural');
			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoEspecialidade' );
		}else{
			document.form1.rh70_estrutural.value = '';
		}
	}
}
function js_mostrarhcbo1(sd03_i_codigo, z01_nome, chave1,chave2,chave3,chave4){
	document.form1.sd03_i_codigo.value       = sd03_i_codigo;
	document.form1.z01_nome.value            = z01_nome;
	document.form1.sd29_i_profissional.value = chave1;
	document.form1.rh70_estrutural.value     = chave2;
	document.form1.rh70_descr.value          = chave3;
	document.form1.rh70_sequencial.value     = chave4;

	document.form1.sd29_i_procedimento.value = '';
	document.form1.sd63_c_procedimento.value = '';
	document.form1.sd63_c_nome.value = '';

	document.form1.sd63_c_procedimento.focus();

	if(chave2=''){
		document.form1.rh70_estrutural.focus();
		document.form1.rh70_estrutural.value = '';
	}

	db_iframe_especmedico.hide();
}


/**
 * Pesquisa Procedimento
 */
function js_pesquisasd29_i_procedimento(mostra){
	var strParam = '';
	strParam += 'func_sau_proccbo.php';
	strParam += '?chave_rh70_sequencial='+$F('rh70_sequencial');
	strParam += '&intUnidade='+$F('sd24_i_unidade');
	strParam += '&funcao_js=parent.js_mostraprocedimentos1|db_sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
	strParam += '&campoFoco=sd63_c_procedimento';

	$('sd70_i_codigo').value = '';
	$('sd70_c_cid').value    = '';
	$('sd70_c_nome').value   = '';
	booValidaCID             = false;

	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
	}else{
		//if(document.form1.sd29_i_procedimento.value != ''){
		//	strParam += '&chave_sd96_i_procedimento='+$F('sd29_i_procedimento');
		//	js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
		//}else
		if(document.form1.sd63_c_procedimento.value != ''){
			//strParam += '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento');
			//js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
			var objParam                 = new Object();
			objParam.exec                = "getProcedimento";
			objParam.rh70_sequencial     = $F('rh70_sequencial');
			objParam.sd63_c_procedimento = $F('sd63_c_procedimento');
			objParam.rh70_descr          = $F('rh70_descr');
			objParam.sd24_i_unidade      = $F('sd24_i_unidade');

			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );
		}else{
			document.form1.sd63_c_nome.value = '';
		}
	}
	document.form1.sd63_c_procedimento.focus();
}
function js_mostraprocedimentos1(chave1,chave2,chave3){

	if(chave1==''){
		alert('CBO não tem ligação com procedimento');
	}
	//$('sd29_i_procedimento').value = chave1;
	//$('sd63_c_procedimento').value = chave2;
	//$('sd63_c_nome').value         = chave3;
	//$('sd70_c_cid').focus();
	var objParam                 = new Object();
	objParam.exec                = "getProcedimento";
	objParam.rh70_sequencial     = $F('rh70_sequencial');
	objParam.sd63_c_procedimento = chave2;
	objParam.rh70_descr          = $F('rh70_descr');
	objParam.sd24_i_unidade      = $F('sd24_i_unidade');

	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );
	db_iframe_sau_proccbo.hide();

}
/**
 * Retorno Pesquisa Procedimento
 */
function js_retornoProcedimento( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

	if (objRetorno.status == 1) {
		if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objProcedimento, iIteracao) {
     			//Prenche Procedimento
				$('sd29_i_procedimento').value = objProcedimento.db_sd96_i_procedimento;
				$('sd63_c_procedimento').value = objProcedimento.sd63_c_procedimento.urlDecode();
				$('sd63_c_nome').value         = objProcedimento.sd63_c_nome.urlDecode();
			});
			booValidaCID = objRetorno.itens[0].intcid > 0;
			$('sd70_c_cid').focus();
 		}
	} else {
		message_ajax( objRetorno.message.urlDecode() );
		$('sd63_c_procedimento').focus();
		$('sd63_c_procedimento').select();
	}
}
/**
 * Botão Voltar
 */
function js_voltar(){
	var objParam                 = new Object();
	objParam.exec                = "getSair";
	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoSair' );
}
/**
 * Retorno Voltar/Sair
 */
function js_retornoSair( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
	if( objRetorno.status == 1 ){
    	parent.document.formaba.a1.disabled = false;
    	parent.document.formaba.a2.disabled = true;

    	if( booNovoLote ){
    		var strParam = 'sau1_sau_lote001.php';
    		strParam += '?idarq=<?=$idarq ?>';
    		strParam += '&sd24_i_unidade='+$F('sd24_i_unidade');
    		parent.iframe_a1.location.href = strParam ;
    	}


		parent.mo_camada('a1');

		//foca no último campo focado
		var campoFocado = parent.iframe_a1.document.form1.campoFocado.value;

		eval("parent.iframe_a1.document.getElementById('"+campoFocado+"').value = '  '; ");
		eval("parent.iframe_a1.document.getElementById('"+campoFocado+"').select(); ");
		eval("parent.iframe_a1.document.getElementById('"+campoFocado+"').focus(); ");
		eval("parent.iframe_a1.document.getElementById('"+campoFocado+"').value = ''; ");

	}else{
		message_ajax( objRetorno.message.urlDecode() );
		booNovoLote = false;
	}
}

/**
 * Botão Processar
 */
function js_processar(){
	var objParam                 = new Object();
	var arrInputs = $$('input, select, textarea');

	objParam.exec                = "Processar";
	arrInputs.each(function(input, intIterator) {
		if (input.type == 'text' || input.type == 'hidden' || input.type == 'textarea' || input.type == 'select-one') {
			var evlTmp = 'objParam.'+input.name+'='+'\''+input.value+'\'; \n';
			eval( evlTmp );
		}
	});

	js_ajax( objParam, 'Aguarde, Processando....', 'js_retornoProcessar' );

}
/**
 * Retorno Processar
 */
function js_retornoProcessar( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

  	message_ajax( objRetorno.message.urlDecode() );

	if( objRetorno.status == 1 ) {

		booNovoLote = true;
		js_init();
		parent.iframe_a1.js_reloadlote();
	}
}


/**
 * Valida Informações
 */
function js_validaForm(){
	var strMessage = '';
	var booRetorno = true;
	var arrInputs = $$('input, select, textarea');

	arrInputs.each(function(input, i) {
		if (input.type == 'text' || input.type == 'hidden' || input.type == 'textarea' || input.type == 'select-one') {
			var evlTmp = 'objValidaCampo.'+input.name+' instanceof Array';
			if( eval( evlTmp ) ){
				var evlFoco = eval( 'objValidaCampo.'+input.name+'[0]' );
				if( input.value == '' ){
					alert( eval( 'objValidaCampo.'+input.name+'[1]' ) );
					$( evlFoco ).focus();
					$( evlFoco ).select();
					booRetorno = false;
				}else{
					var jsValida = eval( 'objValidaCampo.'+input.name+'[2]' );
					if( jsValida != undefined ){
						var jsValidando = eval( jsValida+'('+evlFoco+')' );
						if( jsValidando == false ){
							$( evlFoco ).focus();
							$( evlFoco ).select();
							booRetorno = false;
						}
					}
				}
			}
		}
	});

	return booRetorno;
}

/**
 * Botão Novo Lote
 */
function js_novolote(){
	var strParam = '';
	parent.document.formaba.a2.disabled = true;
	parent.document.formaba.a1.disabled = false;

	strParam += 'sau1_sau_lote001.php';
	strParam += '?idarq=<?=$idarq ?>';
	strParam += '&sd24_i_unidade='+$F('sd24_i_unidade');

	parent.iframe_a1.location.href = strParam ;
	parent.mo_camada("a1");
}



/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
	if(mostra==true){
		var strParam = ( booValidaCID == true )?'func_sau_proccid2.php':'func_sau_cid.php';
		strParam += '?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome';
		strParam += '&chave_sd72_i_procedimento='+$F('sd29_i_procedimento');
		strParam += '&campoFoco=sd70_c_cid';
		js_OpenJanelaIframe('','db_iframe_sau_cid',strParam,'Pesquisa CID',true);
	}else{
		if(document.form1.sd70_c_cid.value != ''){
			var objParam            = new Object();
			objParam.exec           = "getCID";
			objParam.sd70_c_cid     = $F('sd70_c_cid');
			objParam.sd29_i_procedimento = $F('sd29_i_procedimento');
			objParam.booValidaCID   = booValidaCID;

			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoCID' );
		}else{
			$('sd70_i_codigo').value = '';
			$('sd70_c_cid').value    = '';
			$('sd70_c_nome').value   = '';
		}
	}
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
	$('sd70_i_codigo').value = chave1;
	$('sd70_c_cid').value    = chave2;
	$('sd70_c_nome').value   = chave3;

	db_iframe_sau_cid.hide();

}

/**
 * retorno CID
 */
function js_retornoCID( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
  	var objForm    = document.form1;

	$('sd70_i_codigo').value = '';
	$('sd70_c_cid').value    = '';
	$('sd70_c_nome').value   = '';

  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objCID, iIteracao) {
     			$('sd70_i_codigo').value = objCID.sd70_i_codigo;
     			$('sd70_c_cid').value    = objCID.sd70_c_cid.urlDecode();
     			$('sd70_c_nome').value   = objCID.sd70_c_nome.urlDecode();
         	});
     	}
  	} else {
  		message_ajax( objRetorno.message.urlDecode() );
    	$('sd70_c_cid').focus();
  	}

}

/**
 * valida obrigatoriedade do cid
 */
function js_validacid( objCID ){
	if( booValidaCID == true && objCID.value == '' ){
		alert('CID obrigatório.');
		$('sd70_c_cid').focus();
	}
}


/**
 * valida data
 */
function js_validadata( sData ){

	datInfo  = $( sData ).value.split('/');

	dia   = '<?=date ( "d", db_getsession ( "DB_datausu" ) );?>';
	mes   = '<?=date ( "m", db_getsession ( "DB_datausu" ) );?>';
	ano   = '<?=date ( "Y", db_getsession ( "DB_datausu" ) );?>';


	dIni = new Date( datInfo[2],  datInfo[1],  datInfo[0] );
	dFim = new Date( ano, mes, dia );

    if( dIni > dFim) {

      alert("Data maior que data atual.");
      //$( sData ).value = '';
	  $( sData ).focus();
	  $( sData ).select();
      return false;
    }else{
       return true;
    }

}

function js_novaficha(){
parent.document.formaba.a1.disabled = true;
parent.document.formaba.a2.disabled = true;
parent.iframe_a1.location.href='sau4_consultamedica001.php';
parent.mo_camada('a1');
}

function js_pesquisasd29_i_usuario(mostra){
if(mostra==true){
js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
}else{
if(document.form1.sd29_i_usuario.value != ''){
js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd29_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
}else{
document.form1.nome.value = '';
}
}
}
function js_mostradb_usuarios(chave,erro){
document.form1.nome.value = chave;
if(erro==true){
document.form1.sd29_i_usuario.focus();
document.form1.sd29_i_usuario.value = '';
}
}
function js_mostradb_usuarios1(chave1,chave2){
document.form1.sd29_i_usuario.value = chave1;
document.form1.nome.value = chave2;
db_iframe_db_usuarios.hide();
}

function js_pesquisasd29_i_prontuario(mostra){
if(mostra==true){
js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_codigo|sd24_i_codigo','Pesquisa',true);
}else{
if(document.form1.sd29_i_prontuario.value != ''){
js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd29_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
}else{
document.form1.sd24_i_codigo.value = '';
}
}
}
function js_mostraprontuarios(chave,erro){
document.form1.sd24_i_codigo.value = chave;
if(erro==true){
document.form1.sd29_i_prontuario.focus();
document.form1.sd29_i_prontuario.value = '';
}
}
function js_mostraprontuarios1(chave1,chave2){
document.form1.sd29_i_prontuario.value = chave1;
document.form1.sd24_i_codigo.value = chave2;
db_iframe_prontuarios.hide();
}

function js_pesquisa(){
js_OpenJanelaIframe('','db_iframe_prontproced','func_prontproced.php?funcao_js=parent.js_preenchepesquisa|sd29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
db_iframe_prontproced.hide();
<?
if ($db_opcao != 1) {
	echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?chavepesquisa='+chave+'&idarq=" . $idarq . "'";
}
?>
}

function js_emitirfaa(chave_sd29_i_prontuario){
query = 'chave_sd29_i_prontuario='+chave_sd29_i_prontuario;
jan = window.open('sau2_emitirfaa002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
jan.moveTo(0,0);
}

function js_pesquisaprontuariomedico(){
if(document.form1.sd24_i_codigo.value==""){
alert("Paciente não informado!");
}else{
query= 'cgs='+document.form1.z01_i_cgsund.value;
jan = window.open('sau4_prontuariomedico003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
jan.moveTo(0,0);
}
}

function js_pesquisaprontuarios(){
js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios002.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo|z01_v_nome|sd24_i_numcgs','Pesquisa',true);
}

function js_preenchecgs(chave){
db_iframe_cgs_und.hide();

location.href ='<?=basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] )?>?chavepesquisacgs='+chave+'&triagem=<?=@$triagem?>&idarq=<?=$idarq?>';
}

function js_preenchepesquisa(chave1,chave2,chave3){
db_iframe_prontuarios.hide();
location.href ='<?=basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] )?>?chavepesquisaprontuario='+chave1+'&z01_v_nome='+chave2+'&z01_i_cgsund='+chave3+'&idarq=<?=$idarq?>';
}

function js_fatoresderisco(){
if(document.form1.sd24_i_codigo.value==""){
alert("FAA não informada!");
}else{
top = ( screen.availHeight-600 ) / 2;
left = ( screen.availWidth-600 ) / 2;
js_OpenJanelaIframe('','db_iframe_fatoresderisco','sau4_consultamedica006.php?chavepesquisacgs='+document.form1.z01_i_cgsund.value,'Fator de Risco',true, top, left, 600, 200);
}
}

function js_pesquisafaalote(){
parametro = '?funcao_js=parent.js_sairfaalote';
parametro += '&chave_sd59_i_lote='+document.form1.sd58_i_codigo.value;
if( document.form1.sd58_i_codigo.value != "" ){
js_OpenJanelaIframe('','db_iframe_sau_lotepront','func_sau_lotepront.php'+parametro,'Pesquisa',true);
}else{
alert('Lote não informado.');
}

}
function js_sairfaalote(){
db_iframe_sau_lotepront.hide();
}
</script>