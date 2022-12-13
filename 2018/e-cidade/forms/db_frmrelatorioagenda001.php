<?php
/**
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

//MODULO: saude
$clagendamentos->rotulo->label();

$clrotulo = new rotulocampo;

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");
//Unidades
$clrotulo->label("sd02_i_codigo");
//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//undmedhorario
$clundmedhorario->rotulo->label();
//especmedico
$clrotulo->label("sd27_i_codigo");

//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
?>
<form class="container" name="form1" method="post">
	<table class="form-container">
		<tr>
			<td>
			  <fieldset>
  	   	  <legend>Relatório de Agendamento</legend>
  				<table class="subtable">
  					<tr>
  						<td valign="top" colspan="3">
  							<fieldset class="separator">
    							<legend>Dados do Profissional</legend>
    							<table>
    								<!-- PROFISSIONAL -->
    								<tr>
    									<td nowrap title="<?=$Tsd03_i_codigo?>" >
                        <label for="sd03_i_codigo">
                          <?php
                          db_ancora($Lsd03_i_codigo, "js_pesquisasd03_i_codigo(true,1);", $db_opcao);
                          ?>
                        </label>
    									</td>
    									<td>
    										<?php
                        $sScript = " onchange='js_pesquisasd03_i_codigo(false,1);' onFocus=\"nextfield='rh70_estrutural'\"";
                        db_input('sd02_i_codigo', 10, $Isd02_i_codigo, true, 'hidden', $db_opcao, "");
    										db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text',   $db_opcao, $sScript);
                        ?>
    									</td>
    									<td colspan="2">
    										<?php
                        db_input('z01_nome', 49, $Iz01_nome, true, 'text', 3, '');
                        ?>
    									</td>
    								</tr>
    								<!-- CBO -->
    								<tr>
    									<td nowrap title="<?=$Tsd04_i_cbo?>">
                        <label for="rh70_estrutural">
                          <?php
                          db_ancora($Lsd04_i_cbo, "js_pesquisasd04_i_cbo(true,1);", $db_opcao);
                          ?>
                        </label>
    									</td>
    									<td>
    										<?php
                        $sScript = " onchange='js_pesquisasd04_i_cbo(false,1);' onFocus=\"nextfield='sd23_d_consulta'\"";
    										db_input('sd27_i_codigo',   10, $Isd27_i_codigo,   true, 'hidden', $db_opcao, "");
    										db_input('rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', $db_opcao, "");
    										db_input('rh70_estrutural', 10, $Irh70_estrutural, true, 'text',   $db_opcao, $sScript);
    										?>
    									</td>
    									<td colspan="2">
    										<?php
                        db_input('rh70_descr', 49, $Irh70_descr, true, 'text', 3, '');
                        ?>
    									</td>
    								</tr>
    								<tr>
    									<td nowrap title="<?=$Tsd23_d_consulta?>">
                        <label for="sd23_d_consulta">
                          <?=$Lsd23_d_consulta?>
                        </label>
                      </td>
    									<td>
    										<?php

                        $sd23_d_consulta_dia = !empty($sd23_d_consulta_dia) ? $sd23_d_consulta_dia : "";
                        $sd23_d_consulta_mes = !empty($sd23_d_consulta_mes) ? $sd23_d_consulta_mes : "";
                        $sd23_d_consulta_ano = !empty($sd23_d_consulta_ano) ? $sd23_d_consulta_ano : "";

                        db_inputdatasaude(
                          'document.form1.sd27_i_codigo.value',
                          'sd23_d_consulta',
                          $sd23_d_consulta_dia,
                          $sd23_d_consulta_mes,
                          $sd23_d_consulta_ano,
                          true,
                          'text',
                          $db_opcao,
                          " onchange='js_diasem(1)' onFocus=\"nextfield='sd03_i_codigo2'\" ",
                          "",
                          "",
                          "parent.js_diasem(1); ",
                          '',
                          '',
                          '',
                          false,
                          false,
													'document.form1.sd02_i_codigo.value',
													'document.form1.sd02_i_codigo.value'
                        );
                        ?>
    									</td>
    									<td colspan="2">
    										<?php
                        db_input('diasemana', 49, 'diasemana', true, 'text',   3, '');
                        db_input('dia',       10, 'dia',       true, 'hidden', 3, '');
    										?>
    									</td>
    								</tr>
    							</table>
  							</fieldset>
  						</td>
  					</tr>
  					<tr>
  						<?php
              if( !isset($gerarfaa) || $gerarfaa != true ) {

                ?>
      					<td align="center"><br>
      					 	<input type="button" name="relatorioagenda" value="Emitir Relatório" onclick="js_relatorioagenda()"  disabled >
      					</td>
  						<?php
              } else if( isset($gerarfaa) && $gerarfaa == true ) {

                ?>
                 <td align="center"><br>
                   <input type="button" name="relatoriofa" value="Gerar FA's" onclick="js_relatoriofa()"  >
                 </td>
  						<?php
              }
              ?>
  						<td align="center">
  							<input name="gerar_faa" type="hidden" value="<?=$gerarfaa ?>" >
  							<br><input type="button" name="limpar" value="Limpar" onclick="location.href='sau2_agendamento001.php?gerarfaa=<?=$gerarfaa ?>' ">
  						</td>
  					</tr>
  					<tr>
  						<td colspan="3">
  							<fieldset class="separator">
  							  <legend>Pacientes Agendados</legend>
  								<iframe id="frameagendados"
  										    name="frameagendados"
  										    src=""
  										    width="100%"
  										    height="300px"
  										    scrolling="yes"
  										    frameborder="0">
  								</iframe>
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

function js_relatorioagenda() {

 	obj             = document.form1;
 	sd23_d_consulta = document.getElementById('sd23_d_consulta').value;

	a    =  sd23_d_consulta.substr(6,4);
 	m    = (sd23_d_consulta.substr(3,2))-1;
	d    =  sd23_d_consulta.substr(0,2);
	data = new Date(a,m,d);
	dia  = data.getDay()+1;

  x  = 'sau2_agendamento003.php';
  x += '?sd27_i_codigo='+document.form1.sd27_i_codigo.value;
  x += '&chave_diasemana='+dia;
	x += '&sd23_d_consulta='+sd23_d_consulta;
	x += '&sd03_i_codgio='+obj.sd03_i_codigo.value;
	x += '&z01_nome='+obj.z01_nome.value;
	x += '&diasemana='+obj.diasemana.value;
	x += '&dia='+$F('sd23_d_consulta_dia');

	jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	jan.moveTo(0,0);
}

function js_relatoriofa() {

  oForm = document.form1;

  var oElementos = document.getElementById('frameagendados').contentDocument.getElementsByName('ckbox');
  var sCodigo    = "";
  var sSeparador = "";

  for(var iIndice = 0; iIndice < oElementos.length; iIndice++) {

    if(!oElementos[iIndice].disabled && oElementos[iIndice].checked) {

      aCodigo  = oElementos[iIndice].value.split(' ## ');
      sCodigo += sSeparador+aCodigo[0];
      sSeparador = ",";
    }
  }

  if( sCodigo == "") {
    alert( "Selecione um dos agendamentos para gerar FAA.");
  } else {

    var oParam             = new Object();
    oParam.exec            = 'gerarFAATXT';
    oParam.lAgendamentoFaa = true;
    oParam.iUnidade        = $F('sd02_i_codigo');
    oParam.iProfissional   = $F('sd27_i_codigo');
    oParam.iDiasemana      = dia;
    oParam.sd23_d_consulta = sd23_d_consulta;
    oParam.iCodAgendamento = sCodigo;
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');
  }
}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }
}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";

  WindowObjectReference = window.open(oDados.sArquivo+sChave,"CNN_WindowName", strWindowFeatures);
}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave  = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('CurrentWindow.corpo', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave,
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );
}

function js_agendados() {

 	obj             = document.form1;
 	sd23_d_consulta = document.getElementById('sd23_d_consulta').value;

	if( sd23_d_consulta != "" ) {

	  a    =  sd23_d_consulta.substr(6,4);
  	m    = (sd23_d_consulta.substr(3,2))-1;
  	d    =  sd23_d_consulta.substr(0,2);
  	data = new Date(a,m,d);
  	dia  = data.getDay()+1;

 		x  = 'sau2_agendamento002.php';
  	x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
  	x += '&chave_diasemana='+dia;
  	x += '&sd23_d_consulta='+sd23_d_consulta;
  	x += '&opcoes_no=true';
  	x += '&gerar_faa='+obj.gerar_faa.value;

  	iframe = document.getElementById('frameagendados');
  	iframe.src = x;
  }
}

function js_diasem(depara) {

	obj = document.form1;

	if( depara == 1 ) {

	  a =  obj.sd23_d_consulta_ano.value;
	  m = (obj.sd23_d_consulta_mes.value)-1;
	  d =  obj.sd23_d_consulta_dia.value;
	} else {

	  a =  obj.sd23_d_consulta2_ano.value;
	  m = (obj.sd23_d_consulta2_mes.value)-1;
	  d =  obj.sd23_d_consulta2_dia.value;
	}

	data   = new Date(a,m,d);
	dia    = data.getDay();
	semana = new Array(6);

	semana[0] = 'Domingo';
	semana[1] = 'Segunda-Feira';
	semana[2] = 'Terça-Feira';
	semana[3] = 'Quarta-Feira';
	semana[4] = 'Quinta-Feira';
	semana[5] = 'Sexta-Feira';
	semana[6] = 'Sábado';

	if ( depara == 1 ) {

	  document.form1.diasemana.value = semana[dia];
	  document.form1.dia.value       = (dia+1);
	} else {
	  document.form1.diasemana2.value = semana[dia];
	  document.form1.dia2.value       = (dia+1);
	}

	js_agendados();
}

function js_calend() {

  obj  = document.form1;
	a    =  obj.sd23_d_consulta_ano.value;
  m    = (obj.sd23_d_consulta_mes.value)-1;
  d    =  obj.sd23_d_consulta_dia.value;
  data = new Date(a,m,d);
  dia  = data.getDay()+1;

  x  = 'sau4_agendamento001.php';
  x += '?rh70_sequencial='+obj.rh70_sequencial.value;
  x += '&rh70_estrutural='+obj.rh70_estrutural.value;
  x += '&rh70_descr='+obj.rh70_descr.value;
  x += '&sd03_i_codigo='+obj.sd03_i_codigo.value;
  x += '&z01_nome='+obj.z01_nome.value;
  x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
  x += '&chave_diasemana='+dia;

  if ( obj.sd23_d_consulta_dia.value != "" ) {

	  x += '&sd23_d_consulta='+obj.sd23_d_consulta_dia.value+'/'+obj.sd23_d_consulta_mes.value+'/'+obj.sd23_d_consulta_ano.value;
    x += '&diasemana='+obj.diasemana.value;
	}

  x  = 'func_calendariosaude.php';
  x += '?nome_objeto_data=sd23_d_consulta';
  x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
  x += '&shutdown_function=parent.js_agendados()';

  iframe     = document.getElementById('framecalendario');
	iframe.src = x;
}

function js_pesquisasd04_i_cbo(mostra,depara) {

	if (mostra == true ) {

		if( depara == 2 ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_especmedico',
                           'func_especmedico.php?funcao_js=parent.js_mostrarhcbo2|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo'
                                              +'&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value
                                              +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo2.value,
                           'Pesquisa',
                           true
                         );
    } else {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_especmedico',
                           'func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo'
                                              +'&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value
                                              +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,
                           'Pesquisa',
                           true
                         );
    }
  } else {

	  if( depara == 2 ) {

		  if ( document.form1.rh70_estrutural2.value != '') {

     	  js_OpenJanelaIframe(
         	                   '',
         	                   'db_iframe_especmedico',
         	                   'func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural2.value
         	                                      +'&funcao_js=parent.js_mostrarhcbo2|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo'
         	                                      +'&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value
         	                                      +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo2.value,
         	                   'Pesquisa',
         	                   false
         	                 );
        document.form1.rh70_estrutural2.value = '';
        document.form1.rh70_descr2.value      = '';
      } else {
        document.form1.rh70_estrutural2.value = '';
      }
		} else {

      if(document.form1.rh70_estrutural.value != '') {

   		  js_OpenJanelaIframe(
   	   		                   '',
   	   		                   'db_iframe_especmedico',
   	   		                   'func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value
   	   		                                      +'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo'
   	   		                                      +'&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value
   	   		                                      +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,
   	   		                   'Pesquisa',
   	   		                   false
   	   		                 );
    	  document.form1.rh70_estrutural.value = '';
    	  document.form1.rh70_descr.value      = '';
      } else {
        document.form1.rh70_estrutural.value = '';
      }
	  }
	}
}

function js_mostrarhcbo1( chave1, chave2, chave3, chave4 ) {

  document.form1.sd27_i_codigo.value   = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value      = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if (chave2 == '' ) {

    document.form1.rh70_estrutural.focus();
    document.form1.rh70_estrutural.value = '';
  }
}

function js_mostrarhcbo2( chave1, chave2, chave3, chave4 ) {

	document.form1.sd27_i_codigo2.value   = chave1;
	document.form1.rh70_estrutural2.value = chave2;
	document.form1.rh70_descr2.value      = chave3;
	document.form1.rh70_sequencial2.value = chave4;

	db_iframe_especmedico.hide();

	if ( ( chave2 == '') || ( document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value ) ) {

		if( document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value ) {
			alert('CBO do profissional de destino difere do profissional de origem.');
		}

		document.form1.rh70_estrutural2.focus();
		document.form1.sd27_i_codigo2.value   = '';
		document.form1.rh70_estrutural2.value = '';
		document.form1.rh70_descr2.value      = '';
		document.form1.rh70_sequencial2.value = '';
	}
}

function js_pesquisasd03_i_codigo( mostra, depara ) {

	if ( mostra == true ) {

		if( depara == 2 ) {

			js_OpenJanelaIframe(
				                 	 '',
				                 	 'db_iframe_medicos',
				                 	 'func_medicos.php?funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome'
				                 	                +'&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,
				                 	 'Pesquisa',
				                 	 true
		                     );
		} else {
		  js_OpenJanelaIframe(
				                   '',
				                   'db_iframe_medicos',
				                   'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'
				                                  +'&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,
				                   'Pesquisa',
				                   true
				                 );
		}
	} else {

	  if (document.form1.sd03_i_codigo.value != '') {

		  if( depara == 2 ) {

				js_OpenJanelaIframe(
					                 	'',
					                 	'db_iframe_medicos',
					                 	'func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value
					                 	               +'&funcao_js=parent.js_mostramedicos_2'
					                 	               +'&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,
					                 	'Pesquisa',
					                 	false
			                     );
			} else {
			  js_OpenJanelaIframe(
					                   '',
					                   'db_iframe_medicos',
					                   'func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value
					                                  +'&funcao_js=parent.js_mostramedicos_1'
					                                  +'&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,
					                   'Pesquisa',
					                   false
					                 );
			}
		} else {
		  document.form1.z01_nome.value = '';
		}
	}
}

function js_mostramedicos_1( chave, erro ) {

  document.form1.z01_nome.value = chave;

  if ( erro == true ) {

    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value   = '';
    document.form1.sd27_i_codigo.value   = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value      = '';
  } else {
    js_pesquisasd04_i_cbo(true,1);
  }
}

function js_mostramedicos_2( chave, erro ) {

  document.form1.z01_nome2.value = chave;

  if ( erro == true ) {

    document.form1.sd03_i_codigo2.focus();
    document.form1.sd03_i_codigo2.value   = '';
    document.form1.sd27_i_codigo2.value   = '';
    document.form1.rh70_estrutural2.value = '';
    document.form1.rh70_descr2.value      = '';
  } else {
    js_pesquisasd04_i_cbo( true, 2 );
  }
}

function js_mostramedicos1( chave1, chave2 ) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;

  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo( true, 1 );
}

function js_mostramedicos2( chave1, chave2 ) {

  document.form1.sd03_i_codigo2.value = chave1;
  document.form1.z01_nome2.value      = chave2;

  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo( true, 2 );
}
</script>