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
//cgs_und
$clrotulo->label("z01_v_nome");

?>

<form name="form1" method="post">
	<table>
		<tr>
			<td>
				<fieldset><legend>Transferência de Agenda por Especialização</legend>
				<table>
					<tr>
						<td valign="top" colspan="2">
							<fieldset><legend>De</legend>
							<table>
								<!-- PROFISSIONAL -->
								<tr>
									<td nowrap title="<?=@$Tsd03_i_codigo?>" >
										<? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true,1);",$db_opcao); ?>
									</td>
									<td valing="top" align="top">
										<? 
											db_input('sd02_i_codigo',10,$Isd02_i_codigo,true,'hidden',$db_opcao,"");
											db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false,1);' onFocus=\"nextfield='rh70_estrutural'\""); 
										?>
									</td>
									<td colspan="2">
										<? db_input('z01_nome',53,$Iz01_nome,true,'text',3,''); ?>
									</td>
								</tr>
								<!-- CBO -->
								<tr>
									<td nowrap title="<?=@$Tsd04_i_cbo?>">
										<? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,1);",$db_opcao); ?>
									</td>
									<td>
										<?
											db_input('sd30_i_codigo',10,$Isd30_i_codigo,true,'hidden',$db_opcao,"");
											db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcao,"");
											db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
											db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false,1);' onFocus=\"nextfield='sd23_d_consulta'\"");
										?>
									</td>
									<td colspan="2">
										<? db_input('rh70_descr',53,$Irh70_descr,true,'text',3,''); ?>
									</td>
								</tr>
								<tr>
									<td nowrap title="<?=@$Tsd23_d_consulta?>"><?=@$Lsd23_d_consulta?></td>
									<td>
										<? //db_input('sd23_d_consulta',10,$Isd23_d_consulta,true,'text',$db_opcao," onKeyUp='js_mascaraData(this,event)' onchange='js_diasem()' onFocus=\"nextfield='done'\" "); ?>
										<? db_inputdatasaude( 'document.form1.sd27_i_codigo.value','sd23_d_consulta',@$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,true,'text',$db_opcao," onchange='js_diasem(1,".$data_ano.",".$data_mes.",".$data_dia.")' onFocus=\"nextfield='sd03_i_codigo2'\" ", "", "", "parent.js_diasem(1,".$data_ano.",".$data_mes.",".$data_dia.")"  ); ?>
									</td>
									<td>
										<? 
											db_input('diasemana',53,@$diasemana,true,'text',3,''); 
											db_input('dia',10,@$dia,true,'hidden',3,''); 
										?>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<? 
											db_input('sd23_i_codigo',10,$Isd23_i_codigo,true,'hidden',3,"");
											db_ancora(@$Lsd23_i_ficha,"js_pesquisasd23_i_ficha(true,1);",$db_opcao);
											db_input('sd23_i_ficha',10,$Isd23_i_ficha,true,'text',3,"");
											//db_ancora(@$Lsd23_c_hora,"js_pesquisasd23_i_ficha(true,1);",$db_opcao);
											  db_input('sd23_c_hora',10,$Isd23_c_hora,true,'hidden',3,"");
											db_ancora(@$Lsd23_i_numcgs,"js_pesquisasd23_i_ficha(true,1);",$db_opcao);
											db_input('sd23_i_numcgs',10,$Isd23_i_numcgs,true,'text',3,"");
											db_input('z01_v_nome',46,$Iz01_v_nome,true,'text',3,"");											
										?>
									</td>
								</tr>
							</table>
							</fieldset>							
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="2">
							<fieldset><legend>Para</legend>
							<table>
								<!-- PROFISSIONAL -->
								<tr>
									<td nowrap title="<?=@$Tsd03_i_codigo?>" >
										<? db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true,2);",$db_opcao); ?>
									</td>
									<td valing="top" align="top">
										<? db_input('sd03_i_codigo2',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false,2);' onFocus=\"nextfield='rh70_estrutural2'\"") ?>
									</td>
									<td colspan="2">
										<? db_input('z01_nome2',53,$Iz01_nome,true,'text',3,''); ?>
									</td>
								</tr>
								<!-- CBO -->
								<tr>
									<td nowrap title="<?=@$Tsd04_i_cbo?>">
										<? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,2);",$db_opcao); ?>
									</td>
									<td>
										<?
											db_input('sd30_i_codigo2',10,$Isd30_i_codigo,true,'hidden',$db_opcao,"");
											db_input('sd27_i_codigo2',10,$Isd27_i_codigo,true,'hidden',$db_opcao,"");
											db_input('rh70_sequencial2',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
											db_input('rh70_estrutural2',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false,2);' onFocus=\"nextfield='sd23_d_consulta2'\"");
										?>
									</td>
									<td colspan="2">
										<? db_input('rh70_descr2',53,$Irh70_descr,true,'text',3,''); ?>
									</td>
								</tr>
								<tr>
									<td nowrap title="<?=@$Tsd23_d_consulta?>"><?=@$Lsd23_d_consulta?></td>
									<td>
										<? //db_input('sd23_d_consulta',10,$Isd23_d_consulta,true,'text',$db_opcao," onKeyUp='js_mascaraData(this,event)' onchange='js_diasem()' onFocus=\"nextfield='done'\" "); ?>
										<? db_inputdatasaude( 'document.form1.sd27_i_codigo2.value','sd23_d_consulta2',@$sd23_d_consulta2_dia,@$sd23_d_consulta2_mes,@$sd23_d_consulta2_ano,true,'text',$db_opcao," onchange='js_diasem(2,".$data_ano.",".$data_mes.",".$data_dia.")' onFocus=\"nextfield='done'\" ", "", "", "parent.js_diasem(2,".$data_ano.",".$data_mes.",".$data_dia."); "); ?>
									</td>
									<td>
										<? 
											db_input('diasemana2',53,@$diasemana2,true,'text',3,''); 
											db_input('dia2',10,@$dia2,true,'hidden',3,''); 
										?>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<? 
											db_ancora(@$Lsd23_i_ficha,"js_pesquisasd23_i_ficha2(true,2);",$db_opcao);
											db_input('sd23_i_ficha2',10,$Isd23_i_ficha,true,'text',3,"");
											//db_ancora(@$Lsd23_c_hora,"js_pesquisasd23_i_ficha2(true,2);",$db_opcao);
											  db_input('sd23_c_hora2',10,$Isd23_c_hora,true,'hidden',3,"");
											db_ancora(@$Lsd23_i_numcgs,"js_pesquisasd23_i_ficha2(true,2);",3);
											db_input('sd23_i_numcgs2',10,$Isd23_i_numcgs,true,'text',3,"");
											db_input('z01_v_nome2',46,$Iz01_v_nome,true,'text',3,"");											
										?>
									</td>
								</tr>
							</table>
							</fieldset>							
						</td>					
					</tr>
					<tr>
						<td align="center"><br>
							<input type="button" name="lancar" value="Lançar" onclick="js_transferidos()"><br>
						</td>
						<td align="center">
							<br><input type="submit" name="limpar" value="Limpar"><br>
						</td>
					</tr>
					<tr>
						<td colspan="2"><br>
							<fieldset><legend>Agendas Transferidas</legend>
								<iframe id="frametransferidos" name="frametransferidos"  src=""   width="100%" height="100%" scrolling="yes" frameborder="0"></iframe>
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

function js_transferidos(){
 	obj = document.form1;
 	sd23_d_consulta2 = document.getElementById('sd23_d_consulta2').value;
 	
	if( sd23_d_consulta2 != "" && obj.sd23_i_codigo.value != "" ){
  	  	a =  sd23_d_consulta2.substr(6,4);
	  	m = (sd23_d_consulta2.substr(3,2))-1;
	  	d =  sd23_d_consulta2.substr(0,2);
	  	data = new Date(a,m,d);
	  	dia= data.getDay()+1;
 
 		x  = 'sau4_transfereagenda005.php';
 		x += '?sd23_i_codigo='+obj.sd23_i_codigo.value;
 		x += '&sd23_i_ficha2='+obj.sd23_i_ficha2.value;
 		x += '&sd23_c_hora='+obj.sd23_c_hora.value;
 		x += '&sd23_c_hora2='+obj.sd23_c_hora2.value;
  		//x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
  		x += '&sd30_i_codigo2='+obj.sd30_i_codigo2.value;
  		//x += '&sd03_i_codigo='+obj.sd03_i_codigo.value;
  		//x += '&z01_nome='+obj.z01_nome.value;
  		x += '&sd03_i_codigo2='+obj.sd03_i_codigo2.value;
  		x += '&z01_nome2='+obj.z01_nome2.value;
  		x += '&sd23_i_numcgs2='+obj.sd23_i_numcgs2.value;
  		x += '&z01_v_nome2='+obj.z01_v_nome2.value;
  		x += '&chave_diasemana='+dia;
  	  	x += '&sd23_d_consulta2='+sd23_d_consulta2;

  		iframe = document.getElementById('frametransferidos');
  		iframe.src = x;
  }else{
		alert('Falta informações para fazer a transferência.');
  }
}

function js_diasem(depara, ano1, mes1, dia1){

	obj = document.form1;
	if( depara == 1 ){
		a =  obj.sd23_d_consulta_ano.value;
		m = (obj.sd23_d_consulta_mes.value)-1;
		d =  obj.sd23_d_consulta_dia.value;
	}else{
		a =  obj.sd23_d_consulta2_ano.value;
		m = (obj.sd23_d_consulta2_mes.value)-1;
		d =  obj.sd23_d_consulta2_dia.value;
	}
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
	if( depara == 1 ){
		document.form1.diasemana.value = semana[dia];
		document.form1.dia.value = (dia+1);
	}else{
		document.form1.diasemana2.value = semana[dia];
		document.form1.dia2.value = (dia+1);
		//js_agendados();
	}
    dataatual = new Date(ano1, (mes1-1), dia1);
	if( data < dataatual ){
		alert('Data informada é inferior a data atual.');
		if( depara == 1 ){
			obj.sd23_d_consulta.value = '';
		}else{
			obj.sd23_d_consulta2.value = '';
		}
	}	
}

function js_calend(){
	  obj = document.form1;
  	  a =  obj.sd23_d_consulta_ano.value;
	  m = (obj.sd23_d_consulta_mes.value)-1;
	  d =  obj.sd23_d_consulta_dia.value;
	  data = new Date(a,m,d);
	  dia= data.getDay()+1;

	  x  = 'sau4_agendamento001.php';
	  x += '?rh70_sequencial='+obj.rh70_sequencial.value;
	  x += '&rh70_estrutural='+obj.rh70_estrutural.value;
	  x += '&rh70_descr='+obj.rh70_descr.value;
	  x += '&sd03_i_codigo='+obj.sd03_i_codigo.value;
	  x += '&z01_nome='+obj.z01_nome.value;
	  x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
	  x += '&chave_diasemana='+dia;
	  if( obj.sd23_d_consulta_dia.value != ""  ){
  	  	x += '&sd23_d_consulta='+obj.sd23_d_consulta_dia.value+'/'+obj.sd23_d_consulta_mes.value+'/'+obj.sd23_d_consulta_ano.value;
	    x += '&diasemana='+obj.diasemana.value;
  	  }
	  
	  //location.href = x;
	  x  = 'func_calendariosaude.php';
	  x += '?nome_objeto_data=sd23_d_consulta';
	  x += '&sd27_i_codigo='+obj.sd27_i_codigo.value;
	  x += '&shutdown_function=parent.js_agendados()';
	  
	  //iframe = document.getElementById('framecalendario');
  	  //iframe.src = x;
	  
		
}


function js_pesquisasd23_i_ficha2(){
	if( document.form1.sd27_i_codigo2.value == "" || document.form1.sd23_d_consulta2.value == "" ){
		alert("Profissional deverá ser informado e data de consulta.");
	}else{ 
		top = ( screen.availHeight-600 ) / 2;
		left = ( screen.availWidth-600 ) / 2;
 		sd23_d_consulta = document.getElementById('sd23_d_consulta2').value;
  	  	a =  sd23_d_consulta.substr(6,4);
	  	m = (sd23_d_consulta.substr(3,2))-1;
	  	d =  sd23_d_consulta.substr(0,2);
	  	data = new Date(a,m,d);
	  	dia= data.getDay()+1;
		
 
 		x  = 'sau4_transfereagenda004.php';
  		x += '?sd27_i_codigo='+obj.sd27_i_codigo2.value;
  		x += '&chave_diasemana='+dia;
  	  	x += '&sd23_d_consulta='+sd23_d_consulta;
  	  	x += '&funcao_js=parent.js_ficha2';
		
		js_OpenJanelaIframe('','db_iframe_ficha2',x,'Agenda: '+document.form1.z01_v_nome2.value,true, top, left, 600, 200);
	}
}
function js_ficha2(id, hora, sd30_i_codigo ){
  document.form1.sd23_i_ficha2.value = id;
  document.form1.sd23_c_hora2.value = hora;
  document.form1.sd30_i_codigo2.value = sd30_i_codigo;

  db_iframe_ficha2.hide();

}


function js_pesquisasd23_i_ficha(mostra,depara){
	if( document.form1.sd27_i_codigo.value == "" || document.form1.sd23_d_consulta.value == "" ){
		alert("Profissional deverá ser informado e data de consulta.");
	}else{ 
		if(mostra==true){
				js_OpenJanelaIframe('','db_iframe_agendamentos','func_agendamentos.php?funcao_js=parent.js_mostraagendamentos1|sd23_i_codigo|sd23_i_ficha|sd23_c_hora|sd23_i_numcgs|z01_v_nome&chave_sd23_i_especmed='+document.form1.sd27_i_codigo.value+'&chave_sd23_d_consulta='+document.form1.sd23_d_consulta.value ,'Pesquisa',true);
		}else{
			if(document.form1.sd03_i_codigo.value != ''){
					js_OpenJanelaIframe('','db_iframe_agendamentos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_1&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
			}else{
				document.form1.z01_nome.value = '';
			}
		}
	}
}
function js_mostraagendamentos1(chave1,chave2,chave3,chave4,chave5){
  document.form1.sd23_i_codigo.value = chave1;
  document.form1.sd23_i_ficha.value = chave2;
  document.form1.sd23_c_hora.value = chave3;
  document.form1.sd23_i_numcgs.value = chave4;
  document.form1.z01_v_nome.value = chave5;
  document.form1.sd23_i_numcgs2.value = chave4;
  document.form1.z01_v_nome2.value = chave5;

  db_iframe_agendamentos.hide();

}
function js_mostrarhcbo2(chave1,chave2,chave3,chave4){
}





function js_pesquisasd04_i_cbo(mostra,depara){
	if(mostra==true){
		if( depara == 2 ){
    		js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo2|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo2.value,'Pesquisa',true);
    	}else{
    		js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
    	}
  	}else{
		if( depara == 2 ){
	  		if(document.form1.rh70_estrutural2.value != ''){ 
	       		js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural2.value+'&funcao_js=parent.js_mostrarhcbo2|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo2.value,'Pesquisa',false);
	        	document.form1.rh70_estrutural2.value = '';
	        	document.form1.rh70_descr2.value = '';
	        }else{
	        	document.form1.rh70_estrutural2.value = '';
	        }
		}else{        		
	  		if(document.form1.rh70_estrutural.value != ''){ 
	       		js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
	        	document.form1.rh70_estrutural.value = '';
	        	document.form1.rh70_descr.value = '';
	        }else{
	        	document.form1.rh70_estrutural.value = '';
	        }
	    }
	}
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form1.sd27_i_codigo.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if(chave2==''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}
function js_mostrarhcbo2(chave1,chave2,chave3,chave4){
	document.form1.sd27_i_codigo2.value = chave1;
	document.form1.rh70_estrutural2.value = chave2;
	document.form1.rh70_descr2.value = chave3;
	document.form1.rh70_sequencial2.value = chave4;
	
	db_iframe_especmedico.hide();
	
	if((chave2=='') || (document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value) ){
		if( document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value ){
			alert('CBO do profissional de destino difere do profissional de origem.');
		}
		document.form1.rh70_estrutural2.focus();
		document.form1.sd27_i_codigo2.value = '';
		document.form1.rh70_estrutural2.value = '';
		document.form1.rh70_descr2.value = '';
		document.form1.rh70_sequencial2.value = '';
	}
}



function js_pesquisasd03_i_codigo(mostra,depara){
	if(mostra==true){
		if( depara == 2 ){
			//js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
    		js_OpenJanelaIframe('','db_iframe_medicos','func_cboups.php?chave_sd04_i_medico=0&funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_rh70_estrutural='+document.form1.rh70_estrutural.value,'Pesquisa',true);
		}else{
			js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
		}
	}else{
		if(document.form1.sd03_i_codigo.value != ''){
			if( depara == 2 ){
				//js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_2&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
        		js_OpenJanelaIframe('','db_iframe_medicos','func_cboups.php?chave_sd04_i_medico='+document.form1.sd03_i_codigo2.value+'&funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome&chave_sd04_i_unidade='+document.form1.sd02_i_codigo.value+'&chave_rh70_estrutural='+document.form1.rh70_estrutural.value,'Pesquisa',false);
				document.form1.z01_nome2.value = 'Chave ('+document.form1.sd03_i_codigo2.value+') não encontrada.';
    			document.form1.sd03_i_codigo2.value = '';
    			document.form1.rh70_estrutural2.value = '';
    			document.form1.rh70_descr2.value = '';
			}else{
				js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_1&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
			}
		}else{
		}
	}
}
function js_mostramedicos_1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value = '';
    document.form1.sd27_i_codigo.value = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value = '';
  }else{
    js_pesquisasd04_i_cbo(true,1);    
  }
}
function js_mostramedicos_2(chave,erro){
  document.form1.z01_nome2.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo2.focus();
    document.form1.sd03_i_codigo2.value = '';
    document.form1.sd27_i_codigo2.value = '';
    document.form1.sd30_i_codigo2.value = '';
    document.form1.rh70_estrutural2.value = '';
    document.form1.rh70_descr2.value = '';
  }else{
    js_pesquisasd04_i_cbo(true,2);    
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true,1);
}
function js_mostramedicos2(chave1,chave2){
  document.form1.sd03_i_codigo2.value = chave1;
  document.form1.z01_nome2.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true,2);
}
  
  
 

</script>