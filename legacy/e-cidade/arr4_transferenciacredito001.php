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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("model/arrecadacao/RegraCompensacao.model.php");

$clrotulo = new rotulocampo;

$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('p58_codproc');
$clrotulo->label('k160_abatimento');
$clrotulo->label('k160_data');
$clrotulo->label('k160_nometitular');
$clrotulo->label('k160_numeroprocesso');
$clrotulo->label('k160_sequencial');
$clrotulo->label('k156_observacao');

$lDesabilitado = false;

if ( count(RegraCompensacao::getRegrasCompensacaoPorTipo(RegraCompensacao::TRANSFERENCIA)) == 0 ) {
  $lDesabilitado = true;
}

?>

<html>
<head>
<?php 
db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, dbtextField.widget.js");
?>
</head>

<body bgcolor="#CCCCCC">

	<form name="form1" id="form1" method="post" action="">

		<fieldset style="margin: 25px auto 0 auto; width: 600px;">
			<legend>
				<strong>CGM de origem do crédito</strong>
			</legend>

			<table align="center">
				<tr>
					<td title="<?php echo 'CGM Origem'; ?>"><?php
					db_ancora($Lz01_nome, 'js_pesquisaNomeCgmOrigem(true)', 1);
					?>
					</td>
					<td><?php
					db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 1, 'onchange="js_pesquisaNomeCgmOrigem(false)"', 'z01_numcgmorigem');

					db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, "", 'z01_nomeorigem');
					?>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="button"
						value="Listar Créditos" onclick="js_listaCreditos()" /></td>
				</tr>
			</table>

			<fieldset id="ctnCreditos">
				<legend>
					<strong>Créditos do CGM</strong>
				</legend>
				<div id="ctnGridCreditos"></div>
			</fieldset>

		</fieldset>

		<fieldset style="margin: auto; width: 600px;">
			<legend>
				<strong>CGM de destino do crédito</strong>
			</legend>
			<table align="center">
				<tr>
					<td align="center" title="<?php echo 'CGM Destino'; ?>"><?php
					db_ancora($Lz01_nome, 'js_pesquisaNomeCgmDestino(true)', 1);
					?>
					</td>
					<td><?php
					db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 1, 'onchange="js_pesquisaNomeCgmDestino(false)"', 'z01_numcgmdestino');

					db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3,'', 'z01_nomedestino');
					?>
					</td>
				</tr>

				<tr>
					<td title="Processo registrado no protocolo do sistema"><strong>Processo
							do Sistema</strong>
					</td>
					<td
						title="Sim = Processo registrado no protocolo, Não = Processo externo">
						<?php
						db_select('lProcessoSistema', array('' => 'SELECIONE', 'S' => 'SIM', 'N' => 'NÃO'), true, 1, 'onchange="js_processoSistema(this.value)" style="width: 93px;"')
						?>
					</td>
				</tr>

				<tr>

					<td colspan="2">

						<div id="processoSistemaInterno">

							<fieldset>
								<legend>
									<strong>Dados do Processo</strong>
								</legend>

								<table align="center">
									<tr>
										<td title="<?=@$Tp58_codproc?>"><?
										db_ancora($Lp58_codproc, 'js_pesquisaProcesso(true)', 1)
										?>
										</td>
										<td><?
		   
										db_input('p58_codproc', 10, $Ip58_codproc, true, 'text', 1, "onchange='js_pesquisaProcesso(false)'");
										db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, "", 'z01_nomeprocesso');
											
										?></td>
									</tr>
								</table>

							</fieldset>

						</div>

					</td>
				</tr>

				<tr>
					<td colspan="2">

						<div id="processoSistemaExterno">

							<fieldset>
								<legend>
									<strong>Dados do Processo</strong>
								</legend>

								<table align="center">
									<tr>
										<td title="<?=@$Tk160_numeroprocesso?>"><?php
										echo $Lk160_numeroprocesso;
										?>
										</td>
										<td><?
										db_input('k160_numeroprocesso', 40, $Ik160_numeroprocesso, true, 'text', 1);
										?>
										</td>
									</tr>

									<tr>
										<td title="<?=@$Tk160_nometitular?>"><?php
										echo $Lk160_nometitular;
										?>
										</td>
										<td><?
										db_input('k160_nometitular', 40, $Ik160_nometitular, true, 'text', 1);
										?>
										</td>
									</tr>

									<tr>
										<td title="<?=@$Tk160_data?>"><?php
										echo $Lk160_data;
										?>
										</td>
										<td><?php
										db_inputdata('k160_data', @$k160_data, @$k160_mes, @$k160_ano, 'text', true, 1);

										?>
										</td>
									</tr>

								</table>

							</fieldset>

						</div>

					</td>
				</tr>

			</table>
		</fieldset>

		<fieldset style="margin: auto; width: 600px;">
			<legend>
				<strong><?php echo $Lk156_observacao?></strong>
			</legend>
			<?php
			  db_textarea('k156_observacao', 5, 80, $Ik156_observacao, true, 'text', 1)
			?>
		</fieldset>

		<center>
		  <br/>
			<input type="button" value="Processar" onclick="js_processar()" <?php echo $lDesabilitado ? 'disabled="disabled"' : ''?> />
		</center>

    <?php 
    
      if ($lDesabilitado) {
        db_msgbox('Nenhuma regra de compensação para o tipo transferência foi configurada. Verifique.');
      }
    
    ?>			
	</form>

<script>

			
js_processoSistema($F('lProcessoSistema'));
function js_processoSistema(lProcessoSistema) {

  if(lProcessoSistema == 'S') {

    $('processoSistemaInterno').style.display = '';
    $('processoSistemaExterno').style.display = 'none';


  } else if (lProcessoSistema == 'N'){

    $('processoSistemaInterno').style.display = 'none';
    $('processoSistemaExterno').style.display = '';
    
  } else {

    $('processoSistemaInterno').style.display = 'none';
    $('processoSistemaExterno').style.display = 'none';
    
  }

} 

//==============================================BUSCAR CRÉDITOS========================================================
sUrlRPC = 'arr4_transferenciacredito.RPC.php';

function js_listaCreditos() {

  if ($F('z01_numcgmorigem').trim() == '') {
    alert('CGM de origem não informado para pesquisa dos créditos.');
    return false;
  }

  var oParametros     = new Object();
  var msgDiv          = "Buscando créditos. \n Aguarde ...";

  oParametros.sExec        		 = 'listaCreditos';  
  oParametros.iCodigoCgmOrigem = $F('z01_numcgmorigem');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxCreditos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoCreditos
    }
  );   
}

function js_retornoCreditos(oAjax) {

  js_removeObj('msgBox');
  
  var oRetorno  = eval("("+oAjax.responseText+")");

  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n'); 

  oGridCreditos.clearAll          (true);
  
  if (oRetorno.iStatus > 1) {
    
    alert(sMensagem);
    return;
    
  } 
  
	var fFuncaoPadrao = oGridCreditos.selectSingle;
	
  oGridCreditos.selectSingle = function (oCheckbox, iIdLinhaGrid, oTableRow) {

	  fFuncaoPadrao(oCheckbox, iIdLinhaGrid, oTableRow);

	  /**
	   * Instancia do primeiro item filho da célula
	   */
	  var oInput        = $(oTableRow.aCells[5].sId).children.item(0);

    if ( oCheckbox.checked ) {
      oInput.disabled = false;
      oInput.style.backgroundColor = '#FFFFFF'; 
    } else {
      oInput.disabled = true;
      oInput.style.backgroundColor = '#DEB887';
      oInput.value                 = $(oTableRow.aCells[4]).content;
    }
	  
	}
	
  for ( var iLinha = 0; iLinha < oRetorno.aCreditos.length; iLinha++ ) {

		var aLinha = new Array();
		var oDados = oRetorno.aCreditos[iLinha];
		
		aLinha[0]  = oDados.iCodigoCredito;
		aLinha[1]  = oDados.iNumpre;
		aLinha[2]  = oDados.sOrigem;
		aLinha[3]  = oDados.nValorDisponivel;
		aLinha[4]  = eval("credito_"+oDados.iCodigoCredito+" = new DBTextField('credito_"+oDados.iCodigoCredito+"','credito_"+oDados.iCodigoCredito+"','"+oDados.nValorDisponivel+"'); credito_"+oDados.iCodigoCredito+"");
		aLinha[4].setReadOnly(true);
		aLinha[4].addEvent('onChange', ';js_validaValores(this, "'+oDados.nValorDisponivel+'");');
		aLinha[4].addEvent('onKeyUp', 'js_ValidaCampos(this, 4, "", "f", "f", event);');
		oGridCreditos.addRow(aLinha);
  }
  oGridCreditos.renderRows();
}

function js_validaValores(oInputValorInformado, nValorDisponivel) {

  if (parseFloat(oInputValorInformado.value) > parseFloat(nValorDisponivel)) {

    alert('Valor informado para transferência maior que o valor disponível do crédito.');

    oInputValorInformado.value = '';

    return false;

  } 
  
}

//====================================================PROCESSAR========================================================
function js_processar() {

	var oParametros                         = new Object();

	oParametros.sExec                       = 'processarTransferencia';
	oParametros.iCodigoCgmDestino           = $F('z01_numcgmdestino');
	oParametros.sObservacao                 = encodeURIComponent(tagString($F('k156_observacao')));
	
	oParametros.lProcessoSistema            = $F('lProcessoSistema');

  oParametros.iCodigoProcessoSistema      = $F('p58_codproc');
  
  oParametros.sNumeroProcessoExterno      = $F('k160_numeroprocesso');
  oParametros.dDataProcessoExterno        = $F('k160_data');
  oParametros.sNomeTitularProcessoExterno = encodeURIComponent(tagString($F('k160_nometitular')));

  oParametros.aSelecionados               = new Array();
  
  for (var iIndice = 0; iIndice < oGridCreditos.getSelection().length; iIndice++) {

    oCredito = new Object();

    oCredito.iCodigoCredito     = oGridCreditos.getSelection()[iIndice][0];
    oCredito.nValorTransferido  = oGridCreditos.getSelection()[iIndice][5];

    oParametros.aSelecionados[iIndice] = oCredito;
      
  }  
    
  
	js_divCarregando('Pesquisando, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrlRPC,
			                        {
	                             method    : 'POST',
                               parameters: 'json='+Object.toJSON(oParametros), 
                               onComplete: js_confirma
                              });
	
}            

function js_confirma(oAjax){

  js_removeObj('msgbox');
  
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 1) {
    
    sMensagem = "Crédito transferido com sucesso para o cgm : " + $F('z01_numcgmdestino');

    alert(sMensagem);
    
    window.location = 'arr4_transferenciacredito001.php';
    
  } else { 

    alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n'));

    return false;
    
  }
  
}


//=================================PESQUISA CGM ORIGEM==================================================================

function js_pesquisaNomeCgmOrigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_mostraNomeCgmOrigem|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgmorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgmorigem.value+'&funcao_js=parent.js_mostraNomeCgmOrigemHide','Pesquisa',false);
     }else{
       document.form1.z01_nomeorigem.value = ''; 
     }
  }
  oGridCreditos.clearAll(true);  
}

function js_mostraNomeCgmOrigemHide(erro, chave){
  
  document.form1.z01_nomeorigem.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgmorigem.focus(); 
    document.form1.z01_numcgmorigem.value = ''; 
  }
  
}

function js_mostraNomeCgmOrigem(chave1,chave2){

  document.form1.z01_numcgmorigem.value = chave1;
  document.form1.z01_nomeorigem.value   = chave2;
  db_iframe_nomes.hide();
  
}

//=================================PESQUISA CGM DESTINO=================================================================

function js_pesquisaNomeCgmDestino(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_mostraNomeCgmDestino|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgmdestino.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgmdestino.value+'&funcao_js=parent.js_mostraNomeCgmDestinoHide2','Pesquisa',false);
     }else{
       document.form1.z01_nomedestino.value = ''; 
     }
  }
}

function js_mostraNomeCgmDestinoHide2(erro, chave){
  
  document.form1.z01_nomedestino.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgmdestino.focus(); 
    document.form1.z01_numcgmdestino.value = ''; 
  }
  
}

function js_mostraNomeCgmDestino(chave1,chave2){
  
  document.form1.z01_numcgmdestino.value  = chave1;
  document.form1.z01_nomedestino.value = chave2;
  db_iframe_nomes.hide();
  
}

//=================================PESQUISA PROCESSO===================================================================
	
	function js_pesquisaProcesso(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
	  }else{
	     if(document.form1.p58_codproc.value != ''){ 
	        js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraProcessoHide','Pesquisa',false);
	     }else{
	       document.form1.z01_nomeprocesso.value = ''; 
	     }
	  }
	}

	function js_mostraProcessoHide(erro, chave){
	  
	  document.form1.z01_nomeprocesso.value = chave; 
	  if(erro==true){ 
	    document.form1.p58_codproc.focus(); 
	    document.form1.p58_codproc.value = ''; 
	  }
	  
	}

	function js_mostraProcesso(chave1,chave2){
	  
	  document.form1.p58_codproc.value 			= chave1;
	  document.form1.z01_nomeprocesso.value = chave2;
	  db_iframe_nomes.hide();
	  
	}

//=========================== GRID CRÉDITOS=============================================================================
  
js_gridCreditos();
  
function js_gridCreditos() {

  oGridCreditos                   = new DBGrid('datagridCreditos');
  oGridCreditos.sName             = 'datagridCreditos';
  oGridCreditos.nameInstance      = 'oGridCreditos';
  oGridCreditos.setCheckbox       (0);
  oGridCreditos.allowSelectColumns(true);

  oGridCreditos.setCellWidth      ( new Array('15%', '25%', '20%', '20%', '20%') );
  oGridCreditos.setCellAlign      ( new Array('center', 'left', 'left', 'left', 'left') );
  oGridCreditos.setHeader         ( new Array('Código', 'Numpre', 'Origem', 'Valor', 'Tranferência') );
  
  oGridCreditos.show              ( $('ctnGridCreditos') );
  oGridCreditos.clearAll          (true);
  
}

//=====================================================================================================================

</script>



	<?php 
	db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
	?>
</body>
</html>