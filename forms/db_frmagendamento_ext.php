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

//MODULO: saude
$clagendamentos->rotulo->label();
$clrotulo = new rotulocampo;

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");
//Unidades
$clrotulo->label("sd02_c_centralagenda");

//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//undmedhorario
$clundmedhorario->rotulo->label();
//especmedico
$clrotulo->label("sd27_i_codigo");

//Procedimento
$clrotulo->label("s125_i_procedimento");
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
//CGS
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
//cartão sus
$clrotulo->label("s115_c_cartaosus");
//UPS
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("descrdepto");
$clrotulo->label("s165_formatocomprovanteagend");

?>
<form name="form1" method="post">
    <input type="hidden" name="saldo" id="saldo" value="<?=(isset($saldo))?"$saldo":"0"?>">
    <input type="hidden" name="dia_semana" id="dia_semana" value="<?=@$dia_semana?>">
    <input type="hidden" name="sd30_i_fichas" id="sd30_i_fichas" value="<?=@$sd30_i_fichas?>">
    <input type="hidden" name="sd30_i_reservas" id="sd30_i_reservas" value="<?=@$sd30_i_reservas?>">
    <input type="hidden" name="sd30_c_tipograde" id="sd30_c_tipograde" value="<?=@$sd30_c_tipograde?>">
    <input type="hidden" name="sd02_c_centralagenda" id="sd02_c_centralagenda" value="<?=$sd02_c_centralagenda?>">
	<input type="hidden" name="s125_i_procedimento" id="s125_i_procedimento" value="0">
	<input type="hidden" name="sd23_i_codigo" id="sd23_i_codigo"  value="<?=@$sd23_i_codigo?>">
	<table>
		<tr>
			<td>
				<fieldset><legend> <b>Agendamento </b></legend>
				<table border="0">
					<tr>
				       <td>
				         <fieldset>
				           <legend><b> Profissionais </b> </legend>
					       <table border="0">
					       	<!-- UPS -->
					          <tr>
					            <td nowrap title="<?=@$Tsd02_i_codigo?>" >
					              <? db_ancora (@$Lsd02_i_codigo, "js_pesquisasd02_i_codigo(true);", $db_opcao_cotas); ?>
						        </td>
						        <td>
						          <? 
						            db_input('sd02_i_codigo',10,$Isd02_i_codigo, true,'text',$db_opcao_cotas,
						                     "onchange = 'js_pesquisasd02_i_codigo(false);'"
						                    );
						          ?>
						         </td>
						         <td colspan="2">
						           <? 
						             db_input('descrdepto',50,$Idescrdepto,true,'text',3,''); 
						           ?>
					             </td>
				               </tr>
					           <tr>
					                <td><? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,2);",$db_opcao);?></td>
					                <td><? db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false,2);' onFocus=\"nextfield='rh70_descr'\" tabindex=1 ");
					                       db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");?></td>
					                <td><? db_input('rh70_descr',50,$Irh70_descr,true,'text',3," onFocus=\"nextfield='sd03_i_codigo'\" tabindex=2 "/*onchange='js_atualizaRPC(oAutoComplete,oAutoComplete1);'*/);?></td>
					           </tr>
					           <tr>
					                <td><? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo2(true);",$db_opcao); ?></td>
					                <td><? db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo2(false);' onFocus=\"nextfield='z01_nome'\" tabindex=3"); 
					                       db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcao," onchange='js_pesquisasd27_i_codigo(false);' onFocus=\"nextfield='z01_nome'\" ");?></td>
					                <td><? db_input('z01_nome',50,$Iz01_nome,true,'text',3," onFocus=\"nextfield='sd23_d_consulta'\" tabindex=4"/*onchange='js_atualizaRPC(oAutoComplete,oAutoComplete1);'*/); ?></td>
					                <td rowspan="4" style="width: 150"><center><h1> <div id="saldo_div">0 Fichas</div></h1></center></td>
					           </tr>
					            <tr>
					                <td><b><? db_ancora("Agenda","pegaPosMouse(event);js_calendario(0);\" id=\"ancora_calend",$db_opcao); ?></b></td>
					                <td><?db_inputdatasaude( 'document.form1.sd27_i_codigo.value','sd23_d_consulta',@$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,true,'text',$db_opcao," onchange=\"js_diasem(1)\" onFocus=\"nextfield='s115_c_cartaosus'\" readonly", "", "", "parent.js_diasem(); tabindex=5"); ?>
					                 </td>
					                <td><? db_input('diasemana',50,@$diasemana,true,'text',3,''); ?></td>
                        <tr>
                          <td nowrap>
                            <?
                            db_ancora('<b>Grade de Hor&aacute;rio</b>', "js_pesquisasd06_i_undmedhorario(true);", $db_opcao);
                            ?>
                          </td>
                          <td>
                            <?
                            db_input('sd06_i_undmedhorario', 10, @$Isd06_i_undmedhorario, true, 
                                     'text', $db_opcao, ' onchange="js_pesquisasd06_i_undmedhorario(false)"');
                            ?>                                             
                          </td>
                          <td>
                            <?
                            db_input('sd101_c_descr', 50, @$Isd101_c_descr, true, 'text', 3);
                            ?>
                          </td>
                        </tr>

					           </tr>
					           <?if($booProced){?>
					           <tr>
					                <td><? db_ancora ( @$Ls125_i_procedimento, "js_pesquisas125_i_procedimento(true);", $db_opcao ); ?></td>
					                <td><? db_input ( 'sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', $db_opcao, " onchange='js_pesquisas125_i_procedimento(false);'
                          onFocus=\"nextfield='s115_c_cartaosus'\" tabindex=6"); ?></td>
					                <td><? db_input ( 'sd63_c_nome', 50, $Isd63_c_nome, true, 'text', 3, 'tabindex=7'); ?> </td>
					           </tr>
					           <?}else{?>
					           <tr>
					                <td colspan="3">&nbsp;</td>
					           </tr>
					           <?}?>
					       </table>
					     </fieldset>
					   </td>
					</tr>
					<tr>
					    <td>
					       <fieldset><legend><b>Indique o Paciente</b></legend>
					          <table>
					              <tr>
					                  <td><b>Cartão SUS:</b></td>
					                  <td>
					                    <? 
					                      db_input('s115_c_cartaosus',10,$Is115_c_cartaosus,true,'text',1,
					                               "onchange='js_pesquisas115_c_cartaosus(false);' onFocus=\"".
					                               "nextfield='z01_i_cgsund'\" tabindex=8"); ?>
					                  </td>
					                  <td align="right">
					                  <?
					                      echo "&nbsp;&nbsp;&nbsp;".$Ls165_formatocomprovanteagend;
                                $aOpcoes = array("1"=>"PDF","2"=>"TXT");
                                db_select('s165_formatocomprovanteagend',$aOpcoes,true,$db_opcao,"");
					                    ?>
					                  </td>
					                   <td rowspan="3">
					                       <fieldset><legend>Gera</legend> 
					                           <table>
					                               <tr>
					                                   <td><input type="button" name="faa" id="faa" value="FAA" onclick="js_ffa();" disabled></td>
					                               </tr>
					                               <tr>
					                                   <td><input type="button" name="prontuario" id="prontuario" value="Prontuário" onclick="js_prontuario();" disabled></td>
					                               </tr>
					                               <tr>
					                                   <td><input type="button" name="comprovante" id="comprovante" value="Comprovante" onclick="js_comprovante();" disabled></td>
					                               </tr>
					                               <tr>
					                                   <td><input type="button" name="consultas" id="consultas" value="Consultas" onclick="js_calendario(2);" disabled></td>
					                               </tr>
					                           </table>
					                       </fieldset>
					                   </td>
					              </tr>
					              <tr>
					                   <td><? db_ancora(@$Lz01_i_cgsund,"js_pesquisaz01_i_cgsund(true);",$db_opcao); ?></td>
					                   <td><? db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisaz01_i_cgsund(false);' onFocus=\"nextfield='z01_v_nome'\" tabindex=9") ?></td>
					                   <td><? db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',1," onchange='js_pesquisaz01_v_nome()' onFocus=\"nextfield='rh70_estrutural'\" tabindex=10 "); ?></td>
					              </tr>
					              <tr>
					                   <td><input type="submit" value="Confirma" name="confirma" id="confirma" onclick="return js_validafichas();" disabled tabindex=11 onblur="document.form1.rh70_estrutural.focus();"></td>
					                   <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Anula" name="anula" id="anula" onclick="js_anular();" disabled></td>
					                   <td><input type="button" value="Novo Agendamento" name="nova" id="nova" onclick="js_nova();" onblur="document.form1.rh70_estrutural.focus();" tabindex=12></td>
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
<script type="text/javascript">


/*
 * BUSCAR UPS 
 */
function js_pesquisasd02_i_codigo(mostra){

	if (mostra == true) {
		
			js_OpenJanelaIframe('','db_iframe_unidades',
					                  'func_unidades.php?iCotas=1&funcao_js=parent.js_mostraunidade|sd02_i_codigo|descrdepto',
					                  'Pesquisa',
					                  true
					               );
	} else {
		
		if (document.form1.sd02_i_codigo.value != '') {
				js_OpenJanelaIframe('', 'db_iframe_unidades', 'func_unidades.php?iCotas=1&pesquisa_chave='+
						                document.form1.sd02_i_codigo.value+'&funcao_js=parent.js_mostraunidade_2', 'Pesquisa', 
						                false
						               );
		} else {
			document.form1.descrdepto.value = '';
			js_limpar();
		}
		
	}
	
}

/*
 * MOSTRAR UPS
 */ 
function js_mostraunidade(chave1, chave2) {
	
  $('sd02_i_codigo').value = chave1;
  $('descrdepto').value    = chave2;
  db_iframe_unidades.hide();
  js_limpar();
  $('rh70_estrutural').focus();
  
}

/*
 * MOSTRAR UPS
 */ 
function js_mostraunidade_2(chave1, status) {

  if (status == false) {
    $('descrdepto').value = chave1;
  } else {
	$('descrdepto').value = '';
  }
  js_limpar();
  $('rh70_estrutural').focus();

}

function js_limpar() {

  $('rh70_estrutural').value = '';
  $('rh70_sequencial').value = '';
  $('rh70_descr').value = ''; 
  $('sd03_i_codigo').value = '';
  $('z01_nome').value = '';
  $('sd23_d_consulta').value = '';
  $('diasemana').value = '';	
  
}

if(document.form1.sd23_i_codigo.value==''){
	
   document.form1.nova.focus();
   document.form1.sd23_d_consulta.onFocus=" nextfield='s115_c_cartaosus' ";

}

document.onkeyup = function(evt) {
 	var evt = (evt) ? evt : (window.event) ? window.event : "";
	var array_types = new Array('button','submit','reset');
	var valor_types = js_search_in_array(array_types,evt.target.type);
	if (evt.keyCode == 13) {

		if (nextfield == 'done' || valor_types ) {
			return true;
		} else {
			troca=0
			if(nextfield=='sd23_d_consulta'){troca=1;}
			eval(" document.getElementById('"+nextfield+"').focus()" );
			<?if($booProced){?>
			    if(troca==1){nextfield='sd63_c_procedimento';}
			<?}else{?>
			    if(troca==1){nextfield='s115_c_cartaosus';}
			<?}?>
			return false;
		}
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}else if( evt.keyCode == 113  ){ //F2
		$('nova').click();
	}
}
document.form1.dtjs_sd23_d_consulta.style.display='none';

function js_pesquisasd06_i_undmedhorario(mostra) {

  sChave = '';
  
  if ($F('rh70_estrutural') == '' || $F('sd03_i_codigo') == '' || $F('sd27_i_codigo') == '') {
    
    alert('Selecione um profissional e uma especialidade.');
    $('sd06_i_undmedhorario').value = '';
    $('sd101_c_descr').value        = '';
    return false;

  }

  if($F('sd23_d_consulta') == '') {

    alert('Selecione uma data primeiro.');
    return false;

  }

  sChave = '&chave_vinculo='+$F('sd27_i_codigo')+'&chave_dia='+$F('sd23_d_consulta')+'&sTipo=P';

  if(mostra == true) {

    js_OpenJanelaIframe('','db_iframe_undmedhorario','func_undmedhorario.php?funcao_js=parent.js_mostraundmedhorario|'+
                        'sd30_i_codigo|sd101_c_descr'+sChave,'Pesquisa',true);

  } else {

    if($F('sd06_i_undmedhorario') != '') {

      js_OpenJanelaIframe('','db_iframe_undmedhorario','func_undmedhorario.php?&funcao_js='+
                         'parent.js_mostraundmedhorario|sd30_i_codigo|sd101_c_descr&nao_mostra=true'+sChave+
                          '&chave_sd30_i_codigo='+$F('sd06_i_undmedhorario'), 'Pesquisa', false);

    } else {
      $('sd101_c_descr').value = '';
    }

  }

}

function js_mostraundmedhorario(chave1, chave2) {

  document.form1.sd06_i_undmedhorario.value = chave1;
  if(chave1 == '') {

    alert('Grade de horário não encontrada.');
    chave2 = chave1;

  }
  $('sd101_c_descr').value = chave2;
  js_requisaldo();
  db_iframe_undmedhorario.hide();

}

function js_agendados(requi){

  $('sd06_i_undmedhorario').value = '';
  $('sd101_c_descr').value        = '';
  if($F('sd23_d_consulta') == '') {
    return false;
  }
 	obj = document.form1;
    //obj.saldo.value='';
  	obj.sd30_i_fichas.value='';
  	obj.sd30_i_reservas.value='';
  	obj.sd30_c_tipograde.value='';
 	sd23_d_consulta = document.getElementById('sd23_d_consulta').value;
  	a =  sd23_d_consulta.substr(6,4);
	m = (sd23_d_consulta.substr(3,2))-1;
	d =  sd23_d_consulta.substr(0,2);
	data = new Date(a,m,d);
	dia= data.getDay()+1;
	document.form1.dia_semana.value=dia;
	
    js_pesquisasd06_i_undmedhorario(true);
  	if(requi==undefined){
      document.form1.consultas.disabled=false;
  	}else{
  	   js_calendario(3);
  	}
}

function js_requisaldo(){
      if ($F('rh70_estrutural') == '' || $F('sd03_i_codigo') == '' || $F('sd23_d_consulta') == '' ||
          $F('sd06_i_undmedhorario') == '') {
        return false;
      }

  	  var objParam                  = new Object();
			objParam.exec                 = "getSaldoconsulta";
			objParam.dia_semana           = $F('dia_semana');
			objParam.sd23_d_consulta      = $F('sd23_d_consulta');
      objParam.sd27_i_codigo        = $F('sd27_i_codigo');
      objParam.sd06_i_undmedhorario = $F('sd06_i_undmedhorario');
			js_ajax(objParam,'js_retornosaldo');
}
function js_retornosaldo(objAjax){
     var objRetorno = eval("("+objAjax.responseText+")");
     document.form1.saldo.value=objRetorno.saldo;
     document.getElementById('saldo_div').innerHTML = objRetorno.saldo+' Fichas';
     document.form1.consultas.disabled=false;
}

function js_diasem(requi){
	obj = document.form1;
	
	a =  obj.sd23_d_consulta_ano.value;
	m = (obj.sd23_d_consulta_mes.value)-1;
	d =  obj.sd23_d_consulta_dia.value;
	data = new Date(a,m,d);
	dia= data.getDay();
	semana=new Array(6);
	semana[0]='Domingo';
	semana[1]='Segunda-Feira';
	semana[2]='Terça-Feira';
	semana[3]='Quarta-Feira';
	semana[4]='Quinta-Feira';
	semana[5]='Sexta-Feira';
	semana[6]='Sábado';
	document.form1.diasemana.value = semana[dia];
	
	js_agendados(requi);
	
}

function js_pesquisasd03_i_codigo2(mostra,depara){

  if ($('sd02_i_codigo').value == "") {
    alert("Informe uma Unidade antes de selecionar o profissional");
    return;
  }
	
  if (mostra == true) {
	  
	js_OpenJanelaIframe('','db_iframe_cboups','func_medicos.php?prof_ativo=1&funcao_js=parent.js_mostramedicos_21|'
			              +'z01_nome|sd03_i_codigo|sd27_i_codigo&chave_sd06_i_unidade='
			              +$('sd02_i_codigo').value,'Pesquisa',true
			           );

  } else {
	  
	if (document.form1.sd03_i_codigo.value != '') {

      js_OpenJanelaIframe('','db_iframe_cboups','func_medicos.php?prof_ativo=1&pesquisa_chave='
    	                   +document.form1.sd03_i_codigo.value
    	                   +'&funcao_js=parent.js_mostramedicos_21&chave_sd06_i_unidade='
    	                   +$('sd02_i_codigo').value,'Pesquisa',false
    	                 );
      
	} else {

	  document.form1.z01_nome.value    = '';
      $('rh70_estrutural').value       = '';
      $('rh70_descr').value            = '';
      $('diasemana').value             = '';
      $('sd27_i_codigo').value         = '';
      $('sd23_d_consulta').value       = '';
      $('sd06_i_undmedhorario').value  = '';
      $('sd101_c_descr').value         = '';

    }

  }

}

function js_mostramedicos_21(chave1,chave2,chave3){
	document.form1.z01_nome.value = chave1;
	if(! (chave2 === true) ){
		if( chave2 != false ){
			document.form1.sd03_i_codigo.value = chave2;
	  }
	  document.form1.sd27_i_codigo.value = chave3;
	  
	  db_iframe_cboups.hide();
	  
	  js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?iFiltroHorario=1&funcao_js=parent.js_mostrarhcbo11|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);

	  document.getElementById('sd23_d_consulta').value = '';
	  document.form1.diasemana.value = '';
	  if( $('s125_i_procedimento') != undefined ){
		document.form1.sd63_c_procedimento.value = '';
		document.form1.sd63_c_nome.value = '';
		document.form1.s125_i_procedimento.value = '';
	  }
	  document.form1.rh70_estrutural.value = '';
	  document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = ''; 	  
	
	  
	  iframe = document.getElementById('frameagendados');
	  iframe.src = '';
	  document.getElementById('framecalendario').src = '';
	}
}

function js_pesquisasd27_i_codigo(mostra){
        if(document.form1.sd27_i_codigo.value != ''){
		    js_OpenJanelaIframe('','db_iframe_cboups','func_medicos.php?sd27_i_codigo='+document.form1.sd27_i_codigo.value+'&ativo=1&pesquisa_chave='+document.form1.sd27_i_codigo.value+'&funcao_js=parent.js_mostramedicos_21&chave_sd06_i_unidade='+$('sd02_i_codigo').value,'Pesquisa',true);
		}else{
			document.form1.z01_nome.value = '';
		}
}

function js_pesquisasd04_i_cbo(mostra,chama){
	
  if ($('sd02_i_codigo').value == "") {
	  
	alert("Informe uma Unidade antes de selecionar o profissional");
	return;
	
  }
  
  if (mostra == true) {
	  
    <?if($sd02_c_centralagenda=="S"){?>
        js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|'
                              +'rh70_estrutural|rh70_descr','Pesquisa',true
                           );
    <?}else{?>
        js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|'
                                +'rh70_estrutural|rh70_descr&chave_sd04_i_unidade='+$('sd02_i_codigo').value,'Pesquisa',
                                true
                           );
    <?}?>
    
  } else {

    if (document.form1.rh70_estrutural.value != '') { 
      
      <?if($sd02_c_centralagenda=="S"){?>

          js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?chave_rh70_estrutural='
                                 +document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|'
                                 +'rh70_sequencial|rh70_estrutural|rh70_descr','Pesquisa',false
                             );
      
      <?}else{?>
      
           js_OpenJanelaIframe('','db_iframe_cboups','func_cboups.php?chave_rh70_estrutural='
                                 +document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|'
                                 +'rh70_sequencial|rh70_estrutural|rh70_descr&chave_sd04_i_unidade='
                                 +$('sd02_i_codigo').value,'Pesquisa',false
                              );
          
      <?}?>
      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
    }else{

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';

    }

  }

}

function js_mostrarhcbo1(chave1, chave2, chave3) {
	
	document.form1.rh70_sequencial.value = chave1;
	document.form1.rh70_estrutural.value = chave2;
	document.form1.rh70_descr.value      = chave3;
	
	db_iframe_cboups.hide();
	
 	js_OpenJanelaIframe('','db_iframe_cboups','func_cboups2.php?chave_sd04_i_medico=0&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome|sd27_i_codigo&chave_sd04_i_unidade='+$('sd02_i_codigo').value + '&chave_rh70_estrutural='+document.form1.rh70_estrutural.value,'Pesquisa',true);

	document.form1.sd03_i_codigo.value = '';
	document.form1.z01_nome.value = '';
  

	<?if($sd02_c_centralagenda=="S"){?>
		js_calend();
	<?}?>

}

function js_mostramedicos1(chave1,chave2,chave3){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  document.form1.sd27_i_codigo.value = chave3;
  document.getElementById('sd23_d_consulta').value = '';
  document.form1.diasemana.value = '';
  db_iframe_cboups.hide();
  PosMouseY = js_getPosicaoElemento("ancora_calend").iTop + 15;
  PosMouseX = js_getPosicaoElemento("ancora_calend").iLeft;
  js_calendario(0);
  PosMouseY = null;
  PosMouseX = null;
  
}



function js_mostrarhcbo11(chave1,chave2,chave3,chave4){
  document.form1.sd27_i_codigo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if(chave2==''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  

  PosMouseY = js_getPosicaoElemento("ancora_calend").iTop + 15;
  PosMouseX = js_getPosicaoElemento("ancora_calend").iLeft;
  js_calendario(0);
  PosMouseY = null;
  PosMouseX = null;

}
function js_comparaDatassd23_d_consulta(dia,mes,ano){
    var objData        = document.getElementById('sd23_d_consulta');
		objData.value      = dia+"/"+mes+'/'+ano;
  document.getElementById('saldo_div').innerHTML = saldo.value+' Fichas';

}

//CGS
function js_pesquisaz01_i_cgsund(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und',"func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc",'Pesquisa',true);
  }else{
     if(document.form1.z01_i_cgsund.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?chave_z01_i_cgsund='+document.form1.z01_i_cgsund.value+'&funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc','Pesquisa',false);
     }else{
       document.form1.z01_v_nome.value = '';
     }
  }
}

function js_mostracgs(chave, erro){
  document.form1.z01_v_nome.value = chave;
  if(erro==true){ 
    document.form1.z01_i_cgsund.focus(); 
    document.form1.z01_v_nome.value = '';
  }else{
    js_calendario(1);
  }
}
function js_mostracgs1(chave1,chave2,chave3){
	if( chave3 != ""  ){		
		document.form1.z01_i_cgsund.value = chave1;
		document.form1.z01_v_nome.value = chave2;
		db_iframe_cgs_und.hide();
		js_calendario(1);
	}else{
		alert("Paciente sem Data de Nascimento, por favor atualize o Cadastro");    
  	}
  	
}
function js_load_consulta(){
	if($F('z01_i_cgsund')!=''){
	   //Pesquisa Procedimentos
	   var objParam    = new Object();
	   objParam.exec   = 'consulta';
	   objParam.cgs    = $F('z01_i_cgsund');
	   vet=$F('sd23_d_consulta').split('/');
	   objParam.data   = vet[2]+'-'+vet[1]+'-'+vet[0];
	   objParam.medico = $F('sd27_i_codigo');
		
	   js_ajax( objParam, 'js_retorno_consulta' );
	}
}
function js_ajax( objParam, jsRetorno ){
	var objAjax = new Ajax.Request(
                         'sau4_agendamento_simpleRPC.php', 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(objParam),
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax );';
                          				eval( evlJS );
                          			}
                         }
                        );
}
function js_retorno_consulta(objAjax){
   var objRetorno = eval("("+objAjax.responseText+")");
   
   if (objRetorno.status == 1) {
      //pega o valor
      document.form1.sd23_i_codigo.value  = objRetorno.cod_consulta.urlDecode();
      //liberando botoes de controle
      document.form1.anula.disabled=false;
      document.form1.nova.disabled=false;
      document.form1.faa.disabled=false;
      document.form1.prontuario.disabled=false;
      document.form1.comprovante.disabled=false;
      document.form1.confirma.disabled=true;
      document.form1.nova.focus();
   }else{
      //Debug alert msg de erro
      //alert(objRetorno.message);
      //liberando botoes de controle
      document.form1.sd23_i_codigo.value = '';
      document.form1.anula.disabled=true;
      document.form1.faa.disabled=true;
      document.form1.prontuario.disabled=true;
      document.form1.comprovante.disabled=true;
      document.form1.confirma.disabled=false;
      document.form1.confirma.focus();
   } 
}

//Cartão SUS
function js_pesquisas115_c_cartaosus(mostra){
	var strParam = 'func_cgs_und.php';
	strParam += '?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc';
	strParam += '&retornacgs=parent.document.form1.z01_i_cgsund.value';
	strParam += '&retornanome=parent.document.form1.z01_v_nome.value';
	
	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
	}else{
		if(document.form1.s115_c_cartaosus.value != ''){
			strParam += '&chave_s115_c_cartaosus='+document.form1.s115_c_cartaosus.value;
			js_OpenJanelaIframe('','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
		}else{
			document.form1.z01_v_nome.value = '';
		}
	}
}

//function botões

/* EMITIR FAA - COM VISUALIZADOR */
function js_ffa() {
  
  var obj = document.form1;
  if (obj.rh70_estrutural.value == '') {

     alert('Especialidade não informada!');
     return false;
  }
  if (obj.sd03_i_codigo.value == '') {

    alert('Profissional não informado!');
    return false;
   
  }
  if (obj.sd23_d_consulta.value == '') {

    alert('Data não informada!');
    return false;
   
  }
  if (obj.sd23_i_codigo == 0) {
    alert('Paciente não informado.');
  } else {

	  var iAno               =  obj.sd23_d_consulta.value.substr(6,4);
  	var iMes               = (obj.sd23_d_consulta.value.substr(3,2))-1;
  	var iDia               =  obj.sd23_d_consulta.value.substr(0,2);
  	var dData              = new Date(iAno, iMes, iDia);
    var iDiaOut            = dData.getDay() + 1;
    var oParam             = new Object();
    oParam.exec            = 'gerarFAATXT';
    oParam.lAgendamentoFaa = true;
    oParam.iUnidade        = obj.sd02_i_codigo.value;
    oParam.iProfissional   = obj.sd27_i_codigo.value;
    oParam.iDiasemana      = iDiaOut;
    oParam.sd23_d_consulta = obj.sd23_d_consulta.value;
    oParam.iCodAgendamento = obj.sd23_i_codigo.value;
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
  sChave = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('top.corpo', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );

}
/* FIM EMISSÃO FAA */

function js_prontuario(){
   obj=document.form1;
   if(obj.rh70_estrutural.value==''){
       alert('Especialidade não informada!');
       return false;
   }
   if(obj.sd03_i_codigo.value==''){
       alert('Proficional não informado!');
       return false;
   }
   if(obj.sd23_d_consulta.value==''){
       alert('Data não informada!');
       return false;
   }
   if(obj.z01_i_cgsund.value==''){
       alert('Paciente(CGS) não informado!');
       return false;
   }
   //executa
   cgs=document.form1.z01_i_cgsund.value;
   if( cgs != "" ){
		window.open('sau4_prontuariomedico003.php?cgs='+cgs,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   }else{
		alert('Deverá informar um CGS.' );
   }
}
function js_comprovante(){

  obj=document.form1;
  if (obj.rh70_estrutural.value == '') {

    alert('Especialidade não informada!');
    return false;

  }
  if (obj.sd03_i_codigo.value == '') {

    alert('Proficional não informado!');
    return false;

  }
  if (obj.sd23_d_consulta.value == '') {

    alert('Data não informada!');
    return false;

  }
  if (obj.z01_i_cgsund.value == '') {

    alert('Paciente(CGS) não informado!');
    return false;

  }
  //executa
  if (obj.s165_formatocomprovanteagend.value == 1) {

    x = 'sau2_agendamento004.php';
    x += '?sd23_i_codigo='+document.form1.sd23_i_codigo.value;
    x += '&diasemana='+document.form1.diasemana.value;
    jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  } else {

    var oParam           = new Object();
    oParam.exec          = 'gerarComprovanteTXT';
    oParam.sd23_i_codigo = document.form1.sd23_i_codigo.value;
    oParam.diasemana     = document.form1.diasemana.value;

    js_webajax(oParam, 'js_retornoComprovante', 'sau4_ambulatorial.RPC.php');

  }

}

function js_retornoComprovante(oAjax) {
  oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    iTop    = 20;
    iLeft   = 5;
    iHeight = screen.availHeight-210;
    iWidth  = screen.availWidth-35;
    sChave = 'sSessionNome='+oRetorno.sSessionNome;

    js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave, 
                         'Visualisador', true, iTop, iLeft, iWidth, iHeight
                        );

  }

}

function js_consulta(){

   obj=document.form1;
   especmedico=obj.rh70_estrutural.value;
   medico=obj.sd03_i_codigo.value;
   data=obj.sd23_d_consulta.value;
   a =  data.substr(6,4);
   m = (data.substr(3,2))-1;
   d =  data.substr(0,2);
   //data=a+'-'+m+'-'+d;
   dat = new Date(a,m,d);
   dia= dat.getDay()+1;
   
   if(especmedico==''){
       alert('Especialidade não informada!');
       return false;
   }
   if(medico==''){
       alert('Profssional não informado!');
       return false;
   }
   if(data==''){
       alert('Data não informada!');
       return false;
   }
   if($F('sd06_i_undmedhorario') == '') {

     alert('Informe a grade de horário.');
     return false;

   }
   if( sd23_d_consulta != "" && '<?=$sd02_c_centralagenda?>' == 'N' ) {

     x  = 'sau4_agendamento002.php?simplificaco=1';
     x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
     x += '&chave_diasemana='+dia;
     x += '&sd23_d_consulta='+data;
     x += '&sd02_i_codigo='+obj.sd02_i_codigo.value;
     x += '&sd06_i_undmedhorario='+$F('sd06_i_undmedhorario');
  		
   } else if( '<?=$sd02_c_centralagenda?>' == "S" ){
 		 
     x  = 'func_agendamento_consulta.php';
  	 x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
     x += '&sd27_i_rhcbo='+especmedico;
  	 x += '&chave_diasemana='+dia;
  	 x += '&sd23_d_consulta='+data;
     x += '&sd06_i_undmedhorario='+$F('sd06_i_undmedhorario');

   }
   
   //executa
   iTop = ( screen.availHeight-600 ) / 2;
   iLeft = ( screen.availWidth-600 ) / 2;
   js_OpenJanelaIframe('','db_iframe_consulta',x,'Pesquisa',true,iTop, iLeft, 600, 400);
}
function js_pesquisaz01_v_nome(){
	
     if(document.form1.z01_v_nome.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?chave_z01_v_nome='+document.form1.z01_v_nome.value+'&funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=document.form1.z01_i_cgsund.value&retornanome=document.form1.z01_v_nome.value','Pesquisa',true);
     }else{
           document.form1.rh70_estrutural.focus();
     }

}
function js_mostracgs1(chave1,chave2,chave3){
	if( chave3 != ""  ){		
		document.form1.z01_i_cgsund.value = chave1;
		document.form1.z01_v_nome.value = chave2;
		db_iframe_cgs_und.hide();
		js_calendario(1);
	}else{
		alert("Paciente sem Data de Nascimento, por favor atualize o Cadastro");    
  	}
}
function js_anular(){
    obj=document.form1;
    if( obj.sd03_i_codigo != undefined && obj.sd03_i_codigo != 0 ){
		iTop = ( screen.availHeight-600 ) / 2;
		iLeft = ( screen.availWidth-600 ) / 2;
		x  = 'sau1_agendaconsultaanula_simples001.php';
		x += '?s114_i_agendaconsulta='+obj.sd23_i_codigo.value;
		x += '&db_opcao=1';
		x += '';
		js_OpenJanelaIframe('','db_iframe_agendamento',x,'Anulação',true, iTop, iLeft, 600, 250);
	}else{
		alert('Registro não pode ser excluído.');
	}
}
function js_nova(){
    obj=document.form1;
    obj.sd23_i_codigo.value='';
    obj.z01_i_cgsund.value='';
    obj.z01_v_nome.value='';
    obj.s115_c_cartaosus.value='';
    obj.confirma.disabled=true;
    obj.anula.disabled=true;
    obj.sd06_i_undmedhorario.value='';
    
    document.getElementById('saldo_div').innerHTML = '0 Fichas';
    obj.saldo.value=0;
    obj.dia_semana.value='';
    obj.sd30_i_fichas.value='';
    obj.sd30_i_reservas.value='';
    obj.sd30_c_tipograde.value='';
  	obj.sd02_c_centralagenda.value='';
  	obj.s125_i_procedimento.value='';
  	obj.sd23_i_codigo.value='';
    obj.rh70_estrutural.value='';
    obj.rh70_sequencial.value='';
    obj.rh70_descr.value='';
    obj.sd03_i_codigo.value='';
    obj.sd27_i_codigo.value='';
    obj.z01_nome.value='';
    obj.sd23_d_consulta.value='';
  	obj.diasemana.value='';
	<?if($booProced){?>
	    obj.sd63_c_procedimento.value='';
	    obj.sd63_c_nome.value='';
	<?}?>
  	obj.rh70_estrutural.focus();
   	obj.faa.disabled=true;
    obj.prontuario.disabled=true;
    obj.comprovante.disabled=true;
    obj.consultas.disabled=true;
}
function js_calendario(load_consulta){
    if((document.form1.sd27_i_codigo.value=='')&&(document.form1.sd03_i_codigo.value!='')){
           var objParam    = new Object();
           objParam.exec   = 'medico';
           objParam.medico = $F('sd03_i_codigo');
           var objAjax = new Ajax.Request(
                         'sau4_agendamento_simpleRPC.php', 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(objParam),
                          onComplete: function(objAjax){
                          				var objRetorno = eval("("+objAjax.responseText+")");
                          				document.form1.sd27_i_codigo.value=objRetorno.sd27_i_codigo;
                          			    if(load_consulta==1){
                          			       js_load_consulta();
                          			    }else{
                          			       if(load_consulta==2){
                          			           js_consulta();
                          			       }else{
                          			           if(load_consulta==3){
                          			               js_requisaldo();
                          			           }else{
                          			               show_calendarsaude('sd23_d_consulta','parent.js_diasem(1); ',document.form1.sd27_i_codigo.value);
                          			           }
                          			       }
                          			    }
                          			  }
                         }
           );   
    }else{
           if(load_consulta==1){
                 js_load_consulta();
           }else{
               if(load_consulta==2){
                   js_consulta();
               }else{
                   if(load_consulta==3){
                      js_requisaldo();
                   }else{
                      show_calendarsaude('sd23_d_consulta','parent.js_diasem(1); ',document.form1.sd27_i_codigo.value, 
                                         $('sd02_i_codigo').value
                                        );
                   }
               }
           }
    }
}
/**
 * Pesquisa Procedimento
 */
function js_pesquisas125_i_procedimento(mostra){
	var strParam = '';
	strParam += 'func_sau_proccbo.php';
	strParam += '?chave_rh70_sequencial='+$F('rh70_sequencial');
	strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
	strParam += '&campoFoco=sd63_c_procedimento';
		 
	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
	}else{
		if( $F('sd63_c_procedimento') != ''){
			//strParam += '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento'); 
			//js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
			var objParam                 = new Object();
			objParam.exec                = "getProcedimento";
			objParam.rh70_sequencial     = $F('rh70_sequencial');
			objParam.rh70_descr          = $F('rh70_descr');
			objParam.sd63_c_procedimento = $F('sd63_c_procedimento');

			js_ajax( objParam, 'js_retornoProcedimento' );
		}else{     
			$('sd63_c_nome').value = ''; 
		}
	}
	$('sd63_c_procedimento').focus(); 
}
function js_mostraprocedimentos1(chave1,chave2,chave3){
	if(chave1==''){
		alert('CBO não tem ligação com procedimento');
	}
	$('s125_i_procedimento').value = chave1;
	$('sd63_c_procedimento').value = chave2;
	$('sd63_c_nome').value         = chave3;
	db_iframe_sau_proccbo.hide();
	//js_calend();
}
/**
 * Retorno Pesquisa Procedimento
 */
function js_retornoProcedimento( objAjax ){
	var objRetorno = eval("("+objAjax.responseText+");");
	if (objRetorno.status == 1) {
    //Prenche Procedimento
		$('s125_i_procedimento').value = objRetorno.sd96_i_procedimento;
		$('sd63_c_procedimento').value = objRetorno.sd63_c_procedimento.urlDecode();
		$('sd63_c_nome').value         = objRetorno.sd63_c_nome.urlDecode();
	} else {
    alert(objRetorno.message.urlDecode());
		$('sd63_c_procedimento').focus();			
		$('sd63_c_procedimento').value = "";
    $('sd63_c_nome').value = "";
	}
}
function js_validafichas(){
    fichas=document.form1.saldo.value;
    if(fichas<=0){
        alert('Não existe fichas disponiveis!');
        return false;
    }

    if($F('sd06_i_undmedhorario') == '') {

      alert('Selecione a grade de horário.');
      return false;

    }
    <?if($booProced){?>
        if(document.form1.sd63_c_procedimento.value==''){
           alert('Campo Procedimento não informado!');
           document.form1.sd63_c_procedimento.focus();
           return false;
        }
    <?}?>
    return true;
}

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

  return {iLeft:offsetLeft, iTop:offsetTop};

}

</script>