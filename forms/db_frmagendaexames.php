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
$clrotulo = new rotulocampo;

//Exames
$clrotulo->label("s111_i_exame");
$clrotulo->label("s108_c_exame");

//Prestador
$clrotulo->label("s111_i_codigo");
$clrotulo->label("s111_i_prestador");
$clrotulo->label("z01_nome");

//Agenda Exames
$clsau_agendaexames->rotulo->label();
//Prestador Horários
$clsau_prestadorhorarios->rotulo->label();


?>

<form name="form1" method="post">
	<table>
		<tr>
			<td>
				<fieldset><legend><b>Agendamento de Exames</b></legend>
				<table>
					<tr>
						<td valign="top">
							<table>
								<!-- EXAME -->
								<tr>
									<td nowrap title="<?=@$Ts111_i_exame?>">
										<? db_ancora(@$Ls111_i_exame,"js_pesquisas111_i_exame(true);",$db_opcao); ?>
									</td>
									<td>
										<? db_input('s111_i_exame',10,$Is111_i_exame,true,'text',$db_opcao," onchange='js_pesquisas111_i_exame(false);' onFocus=\"nextfield='s111_i_prestador'\""); ?>
									</td>
									<td colspan="2">
										<? db_input('s108_c_exame',49,$Is108_c_exame,true,'text',3,''); ?>
									</td>
								</tr>
								<!-- PRESTADOR -->
								<tr>
									<td nowrap title="<?=@$Ts111_i_prestador?>" >
										<? db_ancora(@$Ls111_i_prestador,"js_pesquisas111_i_prestador(true);",$db_opcao); ?>
									</td>
									<td valing="top" align="top">
										<? db_input('s111_i_codigo',10,$Is111_i_codigo,true,'hidden',$db_opcao," onchange='js_pesquisas111_i_prestador(false);' onFocus=\"nextfield='s113_d_exame'\"") ?>
										<? db_input('s111_i_prestador',10,$Is111_i_prestador,true,'text',$db_opcao," onchange='js_pesquisas111_i_prestador(false);' onFocus=\"nextfield='s113_d_exame'\"") ?>
									</td>
									<td colspan="2">
										<? db_input('z01_nome',49,$Iz01_nome,true,'text',3,''); ?>
									</td>
								</tr>
								<tr>
									<td nowrap title="<?=@$Ts113_d_exame?>"><?=@$Ls113_d_exame?></td>
									<td>
										<? //db_input('sd23_d_consulta',10,$Isd23_d_consulta,true,'text',$db_opcao," onKeyUp='js_mascaraData(this,event)' onchange='js_diasem()' onFocus=\"nextfield='done'\" "); ?>
										<? db_inputdatasaude( 'document.form1.s111_i_codigo.value',
																's113_d_exame',
																@$s113_d_exame_dia,
																@$s113_d_exame_mes,
																@$s113_d_exame_ano,
																true,
																'text',
																$db_opcao,
																" onchange='js_diasem()' onFocus=\"nextfield='done'\" ", 
																"", 
																"", 
																"parent.js_diasem(); ",
																"",
																"",
																"",
																true																
																); ?>
									</td>
									<td>
										<? db_input('diasemana',49,@$diasemana,true,'text',3,''); ?>
									</td>
								</tr>
								
								<tr>
									<td colspan="3">
										<fieldset><legend>Agendamento na Grade de Horário do Dia</legend>
											<iframe id="frameagendados" 
													name="frameagendados"  
													src=""   
													width="100%" 
													height="250" 
													scrolling="yes" 
													frameborder="0">
											</iframe>
										</fieldset>
									</td>
								</tr>
								
								
							</table>
						</td>
						<td valign="top" height="100%">
							<fieldset><legend>Calendário</legend>
								<iframe id="framecalendario" 
										name="framecalendario"  
										src="func_calendarioexames.php?nome_objeto_data=s113_d_exame"   
										width="100%" 
										height="315" 
										scrolling="yes" 
										frameborder="0">
								</iframe>
							</fieldset>						
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%">
								<tr>
									<td width="80%" nowrap title="<?=@$Ts112_c_tipograde?>">
									    <?=@$Ls112_c_tipograde?>
										<? 
                                            db_input('s112_c_tipograde',10,$Is112_c_tipograde,true,'text',3) 
                                         ?>
									</td>
									<td>
										<fieldset><legend>Total de Fichas no Dia</legend>
										<table>
											<tr>
												<td nowrap title="<?=@$Ts112_i_fichas?>" >
													<?=@$Ls112_i_fichas?>
												</td>
												<td valing="top" align="top">
													<? db_input('s112_i_fichas',10,$Is112_i_fichas,true,'text',3) ?>
												</td>
												<td nowrap title="<?=@$Ts112_i_reservas?>" >
													<?=@$Ls112_i_reservas?>
												</td>
												<td valing="top" align="top">
													<? db_input('s112_i_reservas',10,$Is112_i_reservas,true,'text',3) ?>
												</td>
												<td nowrap title="Saldo disponível"><b>Saldo:</b></td>
												<td>
													<? db_input('saldo',10,@$saldo,true,'text',3) ?>
												</td>
											</tr>
										</table>
										</fieldset>
									</td>
								</tr>
							</table>						
						</td>
					
					</tr>
										
				</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>
<script>

function js_agendados(){
 	obj = document.form1;
  	obj.saldo.value='';
  	obj.s112_i_fichas.value='';
  	obj.s112_i_reservas.value='';
  	obj.s112_c_tipograde.value='';
 	s113_d_exame = document.getElementById('s113_d_exame').value;
 	
	if( s113_d_exame != ""  ){
  	  	a =  s113_d_exame.substr(6,4);
	  	m = (s113_d_exame.substr(3,2))-1;
	  	d =  s113_d_exame.substr(0,2);
	  	data = new Date(a,m,d);
	  	dia= data.getDay()+1;
 
 		x  = 'sau4_sau_agendaexames002.php';
  		x += '?s111_i_codigo='+obj.s111_i_codigo.value;
  		x += '&chave_diasemana='+dia;
  	  	x += '&s113_d_exame='+s113_d_exame;
  		
  		iframe = document.getElementById('frameagendados');
  		iframe.src = x;
  // iframe.reload(true);  <-- even tried that
  }
}

function js_diasem(){
	obj = document.form1;
	
	a =  obj.s113_d_exame_ano.value;
	m = (obj.s113_d_exame_mes.value)-1;
	d =  obj.s113_d_exame_dia.value;
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
	
	js_agendados();
	
}

function js_calend(){
	  obj = document.form1;
  	  a =  obj.s113_d_exame_ano.value;
	  m = (obj.s113_d_exame_mes.value)-1;
	  d =  obj.s113_d_exame_dia.value;
	  data = new Date(a,m,d);
	  dia= data.getDay()+1;

	  x  = 'func_calendarioexames.php';
	  x += '?nome_objeto_data=s113_d_exame';
	  x += '&s111_i_codigo='+obj.s111_i_codigo.value;
	  x += '&shutdown_function=parent.js_agendados()';
	  
	  iframe = document.getElementById('framecalendario');
  	  iframe.src = x;	  
		
}




function js_pesquisas111_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_exames','func_sau_exames2.php?funcao_js=parent.js_mostraexame1|s108_i_codigo|s108_c_exame','Pesquisa',true);
  }else{
     if(document.form1.s111_i_exame.value != ''){ 
    	js_OpenJanelaIframe('','db_iframe_sau_exames','func_sau_exames2.php?pesquisa_chave='+document.form1.s111_i_exame.value+'&funcao_js=parent.js_mostraexame','Pesquisa',false);
        document.form1.s111_i_prestador.value = '';
        document.form1.z01_nome.value = '';
     }else{
       document.form1.s111_i_exame.value = '';
     }
  }
}
function js_mostraexame(chave,erro){
  document.form1.s108_c_exame.value = chave; 
  if(erro==true){ 
    document.form1.s111_i_exame.focus(); 
    document.form1.s111_i_exame.value = ''; 
  }
}

function js_mostraexame1(chave1,chave2){
  document.form1.s111_i_exame.value = chave1;
  document.form1.s108_c_exame.value = chave2;

  db_iframe_sau_exames.hide();
  
  document.form1.s111_i_prestador.value = '';
  document.form1.z01_nome.value = '';

}



function js_pesquisas111_i_prestador(mostra){
  if( document.form1.s111_i_exame.value == "" ){
    alert("Exame não informado." );
    document.form1.s111_i_exame.focus();
  }else{
	  if(mostra==true){
	    x  = 'func_sau_prestadorvinculos2.php';
	    x += '?chave_s111_i_exame='+document.form1.s111_i_exame.value;
	    x += '&funcao_js=parent.js_mostraprestador1|s111_i_prestador|z01_nome|s111_i_codigo';
	    js_OpenJanelaIframe('', 'db_iframe_sau_prestadorvinculos',x,'Pesquisa',true);
	  }else{
	     if(document.form1.s111_i_prestador.value != ''){
	        x  = 'func_sau_prestadorvinculos2.php';
	        x += '?chave_s111_i_exame='+document.form1.s111_i_exame.value;
	        x += '&chave_s111_i_prestador='+document.form1.s111_i_prestador.value;
	        x += '&funcao_js=parent.js_mostraprestador1|s111_i_prestador|z01_nome|s111_i_codigo';
	        js_OpenJanelaIframe('','db_iframe_sau_prestadorvinculos',x,'Pesquisa',true);
	        document.form1.s111_i_prestador.value = '';
	        document.form1.z01_nome.value = '';
	     }else{
	       document.form1.z01_nome.value = '';
	     }
	  }
  }
}
function js_mostraprestador1(chave1,chave2,chave3){
  document.form1.s111_i_prestador.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.s111_i_codigo.value = chave3;

  document.getElementById('s113_d_exame').value = '';
  document.form1.diasemana.value = '';

  db_iframe_sau_prestadorvinculos.hide();
  
  iframe = document.getElementById('frameagendados');
  iframe.src = '';
  
  js_calend();

}


</script>