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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_obrasalvara_classe.php");

$clObrasAlvara = new cl_obrasalvara;
$clRotulo 		 = new rotulocampo;

$clObrasAlvara->rotulo->label();
$clRotulo->label("ob01_nomeobra");
$clRotulo->label("p58_codproc");
$clRotulo->label("p58_requer");

$db_opcao = 1;
$db_botao = true;

db_app::load('scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js');
db_app::load('estilos.css');
?>
<style>

#window {
	border-collapse: collapse;
}

#window td {
	margin: 10px;
	padding: 2px;
	border: 1px solid #CCC;
}

#window .descricao {
	font-weight: bold;
	width: 25%;
}

#window .conteudo {
	background-color: #FFF;
	width: 75%;
}
</style>
</head>

<body bgcolor=#CCCCCC>

<form class="container" name="form1" id="form1">
	<fieldset>
		<legend>Alvarás</legend>
		<table class="form-container">
			<tr>
				<td nowrap title="<?=@$Tob04_codobra?>">
					<?
						db_ancora(@$Lob04_codobra,"js_pesquisaObra(true);",($db_opcao == 2?3:$db_opcao));
					?>
				</td>
				<td>
					<? 
						db_input('ob04_codobra',10,$Iob04_codobra,true,'text',($db_opcao == 2?3:$db_opcao)," onchange='js_pesquisaObra(false);'");
		  			db_input('ob01_nomeobra',40,$Iob01_nomeobra,true,'text',3,'');
		  		?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob04_alvara?>"><?=@$Lob04_alvara?></td>
				<td nowrap>
					<? 
						db_input('ob04_alvara',10,$Iob04_alvara,true,'text',1,"") ;
						if ($db_opcao==1){
							echo "(Se não preencher, codigo será gerado automaticamente)";
						}
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob04_data?>"><?=@$Lob04_data?></td>
				<td>
					<?
						if($db_opcao == 1){
							$ob04_data_dia = date("d",db_getsession("DB_datausu"));
							$ob04_data_mes = date("m",db_getsession("DB_datausu"));
							$ob04_data_ano = date("Y",db_getsession("DB_datausu"));
						}
						db_inputdata('ob04_data',@$ob04_data_dia,@$ob04_data_mes,@$ob04_data_ano,true,'text',$db_opcao,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tob04_dtvalidade?>">
					<?=@$Lob04_dtvalidade?>
				</td>
				<td>
					<?
						db_inputdata('ob04_dtvalidade',@$ob04_dtvalidade_dia,@$ob04_dtvalidade_mes,@$ob04_dtvalidade_ano,true,'text',$db_opcao,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="Processos registrado no sistema?">Processodo Sistema</td>
				<td nowrap>
					<?
					  $lProcessoSistema = true;
						db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, $db_opcao, "onchange='js_processoSistema(this.value)' style='width: 95px'")
					?>
				</td>
			</tr>
			<tr id="processoSistema">
				<td nowrap title="<?=@$Tp58_codproc?>">
					<?
						db_ancora($Lp58_codproc, 'js_pesquisaProcesso(true)', $db_opcao);
					?>
				</td>
				<td nowrap>
					<? 
					  db_input('p58_codproc', 10, $Ip58_codproc, true, 'text', $db_opcao, 'onchange="js_pesquisaProcesso(false)"') ;
					  db_input('p58_requer', 40, $Ip58_requer, true, 'text', 3);
					?>
				</td>
			</tr>
			<tr id="processoExterno1" style="display: none;">
				<td nowrap title="Número do processo externo">Processo</td>
				<td nowrap>
					<? 
					  db_input('ob04_processo', 10, $Iob04_processo, true, 'text', $db_opcao) ;
					?>
				</td>
			</tr>

			<tr id="processoExterno2" style="display: none;">
				<td nowrap title="Número do processo externo"><?=@$Lob04_titularprocesso?></td>
				<td nowrap>
				  <? 
				  	db_input('ob04_titularprocesso', 54, $Iob04_titularprocesso, true, 'text', $db_opcao) ;
				  ?>
				</td>
			</tr>
			<tr id="processoExterno3" style="display: none;">
				<td nowrap title="Número do processo externo"><?=@$Lob04_dtprocesso?></td>
				<td nowrap>
					<? 
					  db_inputdata('ob04_dtprocesso', @$ob04_dtprocesso_dia, @$ob04_dtprocesso_mes, @$ob04_dtprocesso_ano, true, 'text', $db_opcao);
					?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tob04_obsprocesso?>" colspan="2">
					<fieldset class="separator">
						<legend>
							<?=$Lob04_obsprocesso?>
						</legend>
						<?
  						db_textarea('ob04_obsprocesso', 10, 70, $Iob04_obsprocesso, true, 'text', $db_opcao);
  					?>
					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="button" name="btnSalvar"  id="btnSalvar"  value="Salvar"        onclick="js_salvarObrasAlvara()" /> 
	<input type="button" name="btnExcluir" id="btnExcluir" value="Excluir"       onclick="js_excluirObraAlvara()" disabled="disabled" /> 
	<input type="button" name="pesquisar"  id="pesquisar"  value="Pesquisar"     onclick="js_pesquisa();" /> 
	<input type="button" name="detalhes"   id="detalhes"   value="Detalhes Obra" onclick="js_pesquisaConstrucoes()" />
</form>

<script>
sUrl           = 'pro1_obrasalvara.RPC.php';
var dDataAtual = $F('ob04_data');

/**
 * SALVAR
 */

function js_salvarObrasAlvara() {

	var oParam   = new Object();
	
  oParam.iCodigoObra 		  = $F('ob04_codobra')        ;
  oParam.iCodigoAlvara 	  = $F('ob04_alvara')         ;
  oParam.dDtAlvara 		    = $F('ob04_data')           ;
  oParam.sProcesso 			  = encodeURIComponent($F('ob04_processo'));
  oParam.sTitularProcesso = encodeURIComponent($F('ob04_titularprocesso'));
  oParam.dDtProcesso      = $F('ob04_dtprocesso')     ;
  oParam.iCodigoProcesso  = $F('p58_codproc')         ;
  oParam.sRequerente 		  = encodeURIComponent($F('p58_requer')) ;
  oParam.sObservacao 		  = encodeURIComponent(tagString($F('ob04_obsprocesso'))) ;
  oParam.dDtValidade 		  = $F('ob04_dtvalidade')     ;
  oParam.lProcessoSistema = $F('lProcessoSistema')    ; 

	oParam.sExec       = 'salvaObraAlvara';

	
	js_divCarregando(_M('tributario.projetos.pro1_obrasalvara001.pesquisando_construcoes'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_retornoOperacao
                              });  

}

/*
 * EXCLUIR
 */

function js_excluirObraAlvara() {

	var oParam   = new Object();
	
  oParam.iCodigoObra 		  = $F('ob04_codobra')        ;
  oParam.iCodigoAlvara 	  = $F('ob04_alvara')         ;
  oParam.lProcessoSistema = $F('lProcessoSistema')    ; 

	oParam.sExec = 'excluirObraAlvara';

	js_divCarregando(_M('tributario.projetos.pro1_obrasalvara001.pesquisando_construcoes'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_retornoOperacao
                              });  

}

function js_retornoOperacao(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno = eval("("+oAjax.responseText+")");

	alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n') );

	if (oRetorno.iStatus == 1) {

		$('ob04_codobra').setValue('');
		$('ob01_nomeobra').setValue('');
	
		js_limpaCampos();
		
	}
	
}

function js_processoSistema(lProcessoSistema) {

	if (lProcessoSistema == 1) {
		document.getElementById('processoExterno1').style.display = 'none';
		document.getElementById('processoExterno2').style.display = 'none';
		document.getElementById('processoExterno3').style.display = 'none';
		document.getElementById('processoSistema').style.display  = '';
	}	else {
		document.getElementById('processoExterno1').style.display = '';
		document.getElementById('processoExterno2').style.display = '';
		document.getElementById('processoExterno3').style.display = '';
		document.getElementById('processoSistema').style.display  = 'none';
	}
		
}



function js_pesquisaConstrucoes() {

	var iCodigoObra = $F('ob04_codobra');
	var oParam      = new Object();

	if(iCodigoObra == '') {
		alert(_M('tributario.projetos.pro1_obrasalvara001.informe_codigo_obra'));
		return false;
	}
	
	oParam.sExec       = 'getConstrucoes';
	oParam.iCodigoObra = iCodigoObra;

	
	js_divCarregando(_M('tributario.projetos.pro1_obrasalvara001.pesquisando_construcoes'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_retornaConstrucoes
                              });
	
}

function js_retornaConstrucoes(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno        = eval("("+oAjax.responseText+")");

	if (oRetorno.iStatus == 1) {

		with (oRetorno.oConstrucao) {

			var sContent = '';
			
			sContent += "<div style='margin: 10px auto; text-align: center;'>                                                          ";
			sContent += "  <div id='msgtopo'></div>                                                                                    ";
			sContent += "  <div style='width:550px; margin:10px auto;'>                                                                ";
			sContent += "    <fieldset>                                                                                                ";
			sContent += "      <table id='window' width='100%'>                                                                        ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "					 <td class='descricao'>Obra</td>                                                           					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codobra+"</td>                                                          ";                 
			sContent += "			   </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "				   <td class='descricao'>Construção</td>                                                     					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codconstr+"</td>                                                        ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "				 <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Descrição Obra</td>                                                           ";
			sContent += "          <td class='conteudo'>"+ob01_nomeobra.urlDecode()+"</td>                                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Area</td>                                                                     ";
			sContent += "          <td class='conteudo'>"+ob08_area+"m2</td>                                                           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Ocupação</td>                                                                 ";
			sContent += "          <td class='conteudo'>"+ob08_ocupacao + " - " + ob08_descrocupacao.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo de Construção</td>                                                       ";
			sContent += "          <td class='conteudo'>"+ob08_tipoconstr + " - " + ob08_descrtipoconstr.urlDecode()+"</td>            ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo Lançamento</td>                                                          ";
			sContent += "          <td class='conteudo'>"+ob08_tipolanc + " - " + ob08_descrtipolanc.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Logradouro</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_lograd + " - " + j14_nome.urlDecode()+", "+ob07_numero+"</td>           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Complemento</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_compl.urlDecode()+"</td>                                                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Bairro</td>                                                                   ";
			sContent += "          <td class='conteudo'>"+ob07_bairro +" - "+ j13_descr.urlDecode()+"</td>                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Detalhes</td>                                                                 ";
			sContent += "          <td class='conteudo'>                                                                               ";     						 
			sContent += "            Área - " + ob07_areaatual + " | Unidades - " +ob07_unidades+" | Pavimentos - "+ob07_pavimentos+"  ";
			sContent += "          </td>                                                                                               ";
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Início</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_inicio+"</td>                                                           ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Final</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_fim+"</td>                                                              ";                 
			sContent += "        </tr>                                                                                                 ";
			sContent += "      </table>                                                                                                ";
			sContent += "    </fieldset>                                                                                               ";
			sContent += "    <div style='margin: 10px auto;'>                                                                          ";
			sContent += "      <input type='button' name='fechar' value='Fechar' onclick='js_fecharJanela()'/>                    ";
			sContent += "    </div>																																																		 ";		
			sContent += "  </div>                                                                                                      ";
			sContent += "</div>                                                                                                        ";
			
			js_montaGradeConstrucao(sContent);
		}
		
	} else {
		
		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n') );
		
	}

}

function js_montaGradeConstrucao(sHtml) {

	windowConstr = new windowAux('wndConstr', 'Construção da obra', 570, 440);
	windowConstr.setContent(sHtml);

  var w = ((screen.width - 582) / 2);
  var h = ((screen.height / 2) - 410);

  windowConstr.show(h, w);
	
	$('window'+windowConstr.idWindow+'_btnclose').observe("click",js_fecharJanela);

	oMessage  = new DBMessageBoard('msgboard', 
  										           'Detalhes da Construção',
  										           'Detalhes da Construção',
            										 $('msgtopo'));
	oMessage.show();

	
}

function js_fecharJanela(){
	
	windowConstr.destroy();
	  
} 

function js_detalhesObra() {

	var iCodigoObra = $F('ob04_codobra');

	var oParam      = new Object();

	if(iCodigoObra == '') {
		alert(_M('tributario.projetos.pro1_obrasalvara001.informe_codigo_obra'));
		return false;
	}
	
	oParam.sExec       = 'getObra';
	oParam.iCodigoObra = iCodigoObra;

	
	js_divCarregando(_M('tributario.projetos.pro1_obrasalvara001.pesquisando_construcoes'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_carregaDetalhesObra
                              });
}

function js_carregaDetalhesObra(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno        = eval("("+oAjax.responseText+")");

	if (oRetorno.iStatus == 1) {

		document.getElementById("ob04_alvara").readOnly = true;
		
		document.getElementById("btnExcluir") .disabled = false;
		
		with(oRetorno.oObra) {

			$('ob04_codobra')        .setValue(ob04_codobra);
			$('ob04_alvara')         .setValue(ob04_alvara);
			$('ob04_data')           .setValue(ob04_data);

			if (ob04_processosistema == 't') {
				
				$('ob04_processo')       .setValue('');
				$('ob04_titularprocesso').setValue('');
				$('ob04_dtprocesso')     .setValue('');
				$('p58_codproc')         .setValue(p58_codproc);
				$('p58_requer')          .setValue(p58_requer.urlDecode());
				
			} else {

				$('lProcessoSistema').value = '0';
				$('lProcessoSistema').onchange();
				
				$('ob04_processo')       .setValue(ob04_processo);
				$('ob04_titularprocesso').setValue(ob04_titularprocesso.urlDecode());
				$('ob04_dtprocesso')     .setValue(ob04_dtprocesso);
				$('p58_codproc')         .setValue('');
				$('p58_requer')          .setValue('');
			}
			
			$('ob04_obsprocesso')    .setValue(ob04_obsprocesso.urlDecode());
			$('ob04_dtvalidade')     .setValue(ob04_dtvalidade); 
			$('ob26_sequencial')     .setValue(ob26_sequencial);
			$('ob26_obrasalvara')    .setValue(ob26_obrasalvara);
			$('ob26_protprocesso')   .setValue(ob26_protprocesso);    
			
		}
		
	} else {

		document.getElementById("ob04_alvara").readOnly = false;
		document.getElementById("btnExcluir").disabled  = true;
		
		js_limpaCampos();
		
	}
	
}

function js_limpaCampos() {
	
	$('ob04_alvara')         .setValue('');
	$('ob04_data')           .setValue(dDataAtual);
  $('ob04_processo')       .setValue('');
  $('ob04_titularprocesso').setValue(''); 
  $('ob04_dtprocesso')     .setValue('');
  $('p58_codproc')         .setValue('');
  $('p58_requer')          .setValue('');
	$('ob04_obsprocesso')    .setValue('');
	$('ob04_dtvalidade')     .setValue(''); 
	$('ob26_sequencial')     .setValue('');
	$('ob26_obrasalvara')    .setValue('');
	$('ob26_protprocesso')   .setValue('');    
	$('ob04_processosistema').setValue('');

	return true;
	
}


/*
 * FUNCOES DE PESQUISA
 */

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}

function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.p58_codproc.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.p58_codproc.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }

}

function js_pesquisaObra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraObra|ob01_codobra|ob01_nomeobra&liberacao=true','Pesquisa',true);
  }else{
     if(document.form1.ob04_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob04_codobra.value+'&funcao_js=parent.js_mostraObraHidden','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}

function js_mostraObraHidden(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob04_codobra.focus(); 
    document.form1.ob04_codobra.value = ''; 
  } else {
		js_detalhesObra();
  }
}

function js_mostraObra(chave1,chave2){
  document.form1.ob04_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
  js_detalhesObra();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obrasalvara.php?funcao_js=parent.js_mostraObra|ob04_codobra|ob01_nomeobra','Pesquisa',true);
}

</script>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>