<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$oDaoagendamentos->rotulo->label();

$oDaorotulo = new rotulocampo;

//Médico
$oDaorotulo->label("sd03_i_codigo");
$oDaorotulo->label("z01_nome");
//Unidades
$oDaorotulo->label("sd02_i_codigo");
//Unidade / Medicos
$oDaorotulo->label("sd04_i_cbo");
//undmedhorario
$oDaoundmedhorario->rotulo->label();
//especmedico
$oDaorotulo->label("sd27_i_codigo");

//CBO
$oDaorotulo->label("rh70_sequencial");
$oDaorotulo->label("rh70_estrutural");
$oDaorotulo->label("rh70_descr");

?>

<form name="form1" method="post">
	<table width='100%'>
		<tr>
			<td>
				<fieldset><legend>Desanular Agendamento</legend>
				<table width='100%' align='center'>
					<tr>
						<td valign="top">
							<fieldset><legend>Dados do Agendamento</legend>
							<table>
								<!-- PROFISSIONAL -->
								<tr>
									<td nowrap title="<?=@$Tsd03_i_codigo?>" >
										<? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",$db_opcao); ?>
									</td>
									<td valing="top" align="top">
										<? 
											db_input('sd02_i_codigo',10,$Isd02_i_codigo,true,'hidden',$db_opcao,"");
											db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false); js_verificacoesMedico();' onFocus=\"nextfield='rh70_estrutural'\"");
										?>
									</td>
									<td colspan="2">
										<? db_input('z01_nome',30,$Iz01_nome,true,'text',3,''); ?>
									</td>
								</tr>
								<!-- CBO -->
								<tr>
									<td nowrap title="<?=@$Tsd04_i_cbo?>">
										<? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao); ?>
									</td>
									<td>
										<?
											db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcao,"");
											db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
											db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);js_verificacoesEspecialidade();' onFocus=\"nextfield='sd23_d_consulta'\"");
										?>
									</td>
									<td colspan="2">
										<? db_input('rh70_descr',30,$Irh70_descr,true,'text',3,''); ?>
									</td>
								</tr>
								<tr>
									<td nowrap title="<?=@$Tsd23_d_consulta?>"><?=@$Lsd23_d_consulta?></td>
									<td>
                      <? db_inputdatasaude( 'document.form1.sd27_i_codigo.value','sd23_d_consulta',@$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,true,'text',$db_opcao," onchange='js_diasem(true)' onFocus=\"nextfield='sd03_i_codigo2'\" ", "", "", "parent.js_diasem(); "); ?>
									</td>
									<td>
										<? 
											db_input('diasemana',30,@$diasemana,true,'text',3,''); 
											db_input('dia',10,@$dia,true,'hidden',3,''); 
										?>
									</td>
								</tr>
							</table>
							</fieldset>							
						</td>
					</tr>
					<tr>
						<td><br>
							<fieldset style="height: 100%;"><legend>Pacientes Agendados:</legend>
								<iframe id="frameagendados" name="frameagendados"  src="" width="100%" height="100%" scrolling="yes" frameborder="0"></iframe>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td align="center"><br>
							<input type="button" name="desanular" id="desanular" value="Desanular" onclick="js_desanular();" disabled>
              <input type="button" name="limpar" value="limpar" onclick="location.href='sau4_agendamentodesanula001.php' "><br>
							<input size="3"  type="hidden" name="lado_de" > <!-- variavel que vai conter codigo da undmedhorario -->
              <select multiple  name='select_agendamento[]' id='select_agendamento' style="display: none;">
						</td>
					</tr>
					<tr>
						<td align="center">
             	<fieldset id='fieldsetDesanulacao' style="display: none;"><legend>Informa&ccedil;&otilde;es da Desanula&ccedil;&atilde;o</legend>
                <table border="0">
                  <tr>
                    <td nowrap>
                      <b>Data Desanula&ccedil;&atilde;o:</b>
                    </td>
                    <td> 
                      <?
                      $s151_d_datadesanulamento_dia = date("d",db_getsession("DB_datausu"));
                      $s151_d_datadesanulamento_mes = date("m",db_getsession("DB_datausu"));
                      $s151_d_datadesanulamento_ano = date("Y",db_getsession("DB_datausu"));
                      db_inputdata('s151_d_datadesanulamento',@$s151_d_datadesanulamento_dia,@$s151_d_datadesanulamento_mes,@$s151_d_datadesanulamento_ano,true,'text',3,"");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap>
                      <b>Motivo:<b>
                    </td>
                    <td> 
                      <?
                      db_input('s151_c_motivodesanulamento',40,$Is151_c_motivodesanulamento,true,'text',1,"")
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td align='center' colspan='2'>
                      <br>
                      <input name="confirmar" type="submit" onclick="return js_desanular(true);" id="incluir" value="Confirmar">
                      <input name="nao_cancela" type="button" id="nao_cancela" value="Cancelar" onclick="js_cancelar();">
                    </td>
                  </tr>
                </table>
              </fieldset>
						</td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script>

function js_getPosicaoElemento(elemID) {
 
  var offsetTrail = document.getElementById(elemID);
  var offsetLeft = 0;
  var offsetTop = 0;

  while (offsetTrail) {
   
    offsetLeft += offsetTrail.offsetLeft;
    offsetTop += offsetTrail.offsetTop;
    offsetTrail = offsetTrail.offsetParent;

  }

  if (navigator.userAgent.indexOf("Mac") != -1 &&  typeof document.body.leftMargin != "undefined") {

    offsetLeft += document.body.leftMargin;
    offsetTop += document.body.topMargin;

  }

  return {left:offsetLeft, top:offsetTop};

}

oButData = document.getElementsByName('dtjs_sd23_d_consulta');
oButData[0].onclick = function() {
                        show_calendarsaudeAnulados('sd23_d_consulta','parent.js_diasem();',document.form1.sd27_i_codigo.value);
                      }

function show_calendarsaudeAnulados(obj, shutdown_function, especmed) {
    
  oPosicoes = js_getPosicaoElemento('sd23_d_consulta');
  PosMouseY = oPosicoes.top + 15;
  PosMouseX = oPosicoes.left;

  js_OpenJanelaIframe('','iframe_data_'+obj,'func_calendariosaudeanulados.php?nome_objeto_data='+obj+'&shutdown_function='+shutdown_function+'&sd27_i_codigo='+especmed+'&fechar=true','Calend&aacute;rio',true,PosMouseY,PosMouseX,250,270);

}


function js_cancelar() {
  $('fieldsetDesanulacao').style.display = 'none';
}

function js_desanular(lVerificaMotivo) {

  if(lVerificaMotivo == undefined) {
    lVerificaMotivo = false;
  }

  oElementos = document.getElementById('frameagendados').contentDocument.getElementsByName('ckbox');    
  var oAgend = document.getElementById('select_agendamento');
  var j = 0;

  if($F('sd03_i_codigo') == '') {

    alert('Selecione um profissional.');
    return false;

  }

  if($F('rh70_estrutural') == '' || $F('rh70_sequencial') == '') {

    alert('Selecione uma especialidade.');
    return false;

  }

  if($F('sd23_d_consulta') == '') {

    alert('Selecione uma data!');
    return false;

  }
  
  js_esvaziaSelect();
  for(i = 0; i < oElementos.length; i++) {
     
    if(!oElementos[i].disabled && oElementos[i].checked) {

      oAgend.options[j] = new Option('agendamento', oElementos[i].value);
      oAgend.options[j].selected = true;
      j++;

    }

  }

  if(j < 1) { // verifica o numero de checkbox que foram marcadas

    alert('Selecione ao menos um agendamento para desanular.');
    return false;

  }

  if(lVerificaMotivo) {

    if($F('s151_c_motivodesanulamento') == '') {

      alert('Preencha o motivo.');
      return false;

    }

  }

  $('fieldsetDesanulacao').style.display = '';
  $('incluir').focus();
  $('s151_c_motivodesanulamento').focus();

  return true;

}

function js_esvaziaSelect() {

  oSel = $('select_agendamento');
  while(oSel.length > 0) {
    oSel.options[0] = null;
  }

}

function js_agendados() {

	obj = document.form1;

	sd23_d_consulta = document.getElementById('sd23_d_consulta').value;
	sd27_i_codigo = obj.sd27_i_codigo.value;
 	
	if(sd23_d_consulta != "") {

    a =  sd23_d_consulta.substr(6,4);
	  m = (sd23_d_consulta.substr(3,2))-1;
	 	d =  sd23_d_consulta.substr(0,2);
	 	data = new Date(a,m,d);
	 	dia= data.getDay()+1;
 
 		x  = 'sau4_agendamentodesanula002.php';
  	x += '?sd27_i_codigo='+sd27_i_codigo;
  	x += '&chave_diasemana='+dia;
   	x += '&sd23_d_consulta='+sd23_d_consulta;
   	x += '&opcoes_no=true';
 
    iframe = document.getElementById('frameagendados');
  	iframe.src = x;

  }

}

function js_diasem(verifica) {

  if(verifica != true) {
    verifica = false;
  }

  if(verifica) {

    verifica = js_verificacoesData();
    if(!verifica) {
      return false;
    }

  }

	obj = document.form1;
	
  if(obj.sd23_d_consulta.value == '') {
    
    $('desanular').disabled = true;
    $('fieldsetDesanulacao').style.display = 'none';
    obj.diasemana.value = '';
    document.getElementById('frameagendados').src = '';

  } else {

    $('desanular').disabled = false;
    a =  obj.sd23_d_consulta_ano.value;
  	m = (obj.sd23_d_consulta_mes.value)-1;
  	d =  obj.sd23_d_consulta_dia.value;
    data = new Date(a,m,d);
  	dia= data.getDay();

	  semana = new Array(6);
  	semana[0] = 'Domingo';
	  semana[1] = 'Segunda-Feira';
  	semana[2] = 'Terca-Feira';
	  semana[3] = 'Quarta-Feira';
  	semana[4] = 'Quinta-Feira';
	  semana[5] = 'Sexta-Feira';
  	semana[6] = 'Sabado';

	  document.form1.diasemana.value = semana[dia];
  	document.form1.dia.value = (dia + 1);
	
	  js_agendados();

	}

}


/**** funcoes de verificacao de integridade das informacoes no formulario */
function js_verificacoesMedico() {

  if($F('sd03_i_codigo') == '') {
 
    $('rh70_estrutural').value = '';
    js_verificacoesEspecialidade();

  }

}

function js_verificacoesEspecialidade() {

  if($F('rh70_estrutural') == '') {
 
    $('rh70_descr').value = '';
    $('sd23_d_consulta').value = '';
    js_diasem();

  }

}

function js_verificacoesData() {

  if($F('sd03_i_codigo') == '') {

    alert('Selecione um profissional.');
    $('sd23_d_data').value = '';
    return false;

  }

  if($F('rh70_estrutural') == '') {

    alert('Selecione um profissional.');
    $('sd23_d_data').value = '';
    return false;

  }
  return true;

}
/*  final do bloco das funcoes de verificacao ****/


function js_pesquisasd04_i_cbo(mostra) {

	if(mostra==true) {
    		js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
 	} else {

    if(document.form1.rh70_estrutural.value != '') { 
	
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
	  document.form1.rh70_estrutural.value = '';
	  document.form1.rh70_descr.value = '';
	 
   } 

	}

}

function js_mostrarhcbo1(chave1,chave2,chave3,chave4) {

  document.form1.sd27_i_codigo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if(chave2==''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
  
  $('sd23_d_consulta').value = '';
  js_diasem();

  if($F('rh70_estrutural') != '') {
    show_calendarsaudeAnulados('sd23_d_consulta','parent.js_diasem();',document.form1.sd27_i_codigo.value);
  }

}

function js_pesquisasd03_i_codigo(mostra) {

	if(mostra==true) {
	  js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
	} else {

		if(document.form1.sd03_i_codigo.value != '') {
  		js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
		} else {
			document.form1.z01_nome.value = '';
		}

	}

}

function js_mostramedicos(chave,erro) {

  document.form1.z01_nome.value = chave;
  if(erro==true) {

    document.form1.sd03_i_codigo.focus();
    $('rh70_estrutural').value = '';
    js_verificacoesEspecialidade();

  } else {

    js_pesquisasd04_i_cbo(true);
    $('rh70_estrutural').value = '';
    js_verificacoesEspecialidade();

  }

}

function js_mostramedicos1(chave1,chave2) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
  $('rh70_estrutural').value = '';
  js_verificacoesEspecialidade();

}

</script>