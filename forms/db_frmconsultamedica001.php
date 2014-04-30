<?
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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clprontproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd27_i_codigo");
$clrotulo->label("sd20_i_codigo");

$clrotulo->label("nome");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd24_i_codigo");
$clrotulo->label("sd24_i_unidade");
$clrotulo->label("sd24_v_motivo");
$clrotulo->label("sd24_v_pressao");
$clrotulo->label("sd24_f_peso");
$clrotulo->label("sd24_f_temperatura");
$clrotulo->label("sd24_i_profissional");

$clrotulo->label("sd16_i_codigo");
$clrotulo->label("sd14_i_codigo");
$clrotulo->label("sd14_c_descr");
$clrotulo->label("sd05_i_codigo");
$clrotulo->label("sd05_c_descr");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_cbo");
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("sd24_c_digitada");
$clrotulo->label("z01_nome");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_t_obs");

/*
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;

 $sd29_d_data_dia = substr($sd29_d_data,0,2);
 $sd29_d_data_mes = substr($sd29_d_data,3,2);
 $sd29_d_data_ano = substr($sd29_d_data,6,4);

}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $sd29_d_data_dia = substr($sd29_d_data,0,2);
 $sd29_d_data_mes = substr($sd29_d_data,3,2);
 $sd29_d_data_ano = substr($sd29_d_data,6,4);
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
*/
?>
<form name="form1" method="post" action="">
<center>

<table border="0">
 <tr>
   <td width="65%">

		<fieldset><legend><b>Consulta Médica</b></legend>
			<table border="0">
			<tr>
			    <td nowrap title="<?=@$Tsd24_i_codigo?>">
			       <?=@$Lsd24_i_codigo?>
			    </td>
			    <td colspan="3">
			     <?
			     db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',3,"");
			     if( isset( $sd24_i_codigo ) && (int)$sd24_i_codigo != 0){
			       ?>
			         <script>
			           //js_diagnostico();
			           //parent.mo_camada('a2');
			         </script>
			       <?
			     }else{
			       ?>
			         <script>
			           parent.document.formaba.a2.disabled = true;
			         </script>
			       <?       
			     }     
			     db_input('z01_v_nome',66,$Iz01_v_nome,true,'text',3);
			     db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'hidden',3,'');
			     ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tsd24_i_unidade?>">
			       <?
			         db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",3);
			       ?>
			    </td>
			    <td colspan=3>
			     <?
			     db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'text',3," onchange=alert('aquiii')");
			     @db_input('descrdepto',66,$Idescrdepto,true,'text',3,"");
			     ?>
			     
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tsd29_i_codigo?>">
			       <?=@$Lsd29_i_codigo?>
			    </td>
			    <td> 
			      <?
			        db_input('sd29_i_codigo',10,$Isd29_i_codigo,true,'text',3,"");
			      ?>
			    </td>
			  </tr>
			  <!-- PROFISSIONAL -->
			  <tr>
			    <td nowrap title="<?=@$Tsd03_i_codigo?>" >
			       <?
			       db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",isset($sd03_i_codigo)?3:$db_opcao);
			       ?>
			    </td>
			    <td valing="top" align="top" colspan="3">
			       <?
			          db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',isset($sd03_i_codigo)?3:$db_opcao," onchange='js_pesquisasd03_i_codigo(false);'")
			       ?>
			       <?
			          db_input('z01_nome',66,$Iz01_nome,true,'text',3,'');          
			       ?>
			    </td>
			  </tr>
			
			  <!-- CBO -->
			       <tr>
			         <td nowrap title="<?=@$Tsd04_i_cbo?>">
			            <?
			            db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao);
			            ?>
			         </td>
			         <td colspan="3">
			          <?
			          db_input('sd29_i_profissional',10,$Isd29_i_profissional,true,'hidden',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
			          db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
			          db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
			          db_input('rh70_descr',66,$Irh70_descr,true,'text',3,'');
			          ?>
			         </td>
			       </tr>
			
			 
			   <!-- PROCEDIMENTO -->
			  <tr>
			    <td nowrap title="<?=@$Tsd29_i_procedimento?>">
			       <?
			       db_ancora(@$Lsd29_i_procedimento,"js_pesquisasd29_i_procedimento(true);",$db_opcao);
			       ?>
			    </td>
			       <td valign="top">
			       <?
			          db_input('sd29_i_procedimento',10,$Isd29_i_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'");
			       ?>
			       </td>
			       <td valign="top">
			       <? 
			          db_input('sd63_c_procedimento',12,$Isd63_c_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'");
			       ?>
			       </td>
			       <td valign="top">
			       <?
			          //db_textarea('sd63_c_nome',1,34,@$Isd63_c_nome,true,'text',3,"")
			          db_input('sd63_c_nome',49,$Isd63_c_nome,true,'text',3,'')
			       ?>       
			       </td>
			  </tr>
			
			  <tr>
			    <td nowrap title="<?=@$Tsd29_d_data?>">
			       <?=@$Lsd29_d_data?>
			    </td>
			    <td colspan="2"> 
			       <?
			       db_inputdata('sd29_d_data',@$sd29_d_data_dia,@$sd29_d_data_mes,@$sd29_d_data_ano,true,'text',$db_opcao,"");
			       ?>
			    </td>
			    <td  nowrap title="<?=@$Tsd29_c_hora?>">
			       <?=@$Lsd29_c_hora?>
     			   <?db_input('sd29_c_hora',5,$Isd29_c_hora,true,'text',$db_opcao,"OnKeyUp=mascara_hora(this.value,'sd29_c_hora')")?>
			    </td>
			  </tr>
			  
			  <tr>
			    <td valign="top" nowrap title="<?=@$Tsd29_t_tratamento?>">
			       <b>Executado:</b>
			    </td>
			    <td colspan="3"> 
			      <?
			         $sd29_t_tratamento=!isset($sd29_t_tratamento)?' ':$sd29_t_tratamento;
			         db_textarea('sd29_t_tratamento',2,77,@$sd29_t_tratamento,true,'text',$db_opcao,"");
			      ?>
			    </td>
			  </tr>
			<!--  
			  <tr>
			    <td nowrap title="<?=@$Tsd29_t_diagnostico?>" valign="top">
			       <?=@$Lsd29_t_diagnostico?>
			    </td>
			    <td > 
			      <?
			         $sd29_t_diagnostico=!isset($sd29_t_diagnostico)?' ':$sd29_t_diagnostico;
			         db_textarea('sd29_t_diagnostico',1,70,@$sd29_t_diagnostico,true,'text',$db_opcao,"")
			      ?>
			    </td>
			  </tr>
			-->
			
			
			<!-- Fatores de Riscos  
			  <tr>
			    <td nowrap title="<?=@$Tz01_t_obs?>">
			       <b>Fatores de Riscos</b>
			    </td>
			    <td colspan="3"> 
			      <?
			         $z01_t_obs=!isset($z01_t_obs)?' ':$z01_t_obs;
			         db_textarea('z01_t_obs',2,62,@$z01_t_obs,true,'text',$db_opcao,"")
			      ?>
			    </td>
			  </tr>
			-->
			
			  
		  </table>
		</fieldset>
		<center>
		<!--   
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1==false?"disabled":"")?>  onclick="location.href='sau4_consultamedica001.php'">
		-->
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1==false?"disabled":"")?>  onclick="location.href='sau4_consultamedica001.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'">
		<input name="prosseguir" type="button" value="Prosseguir" <?=($db_botao1==true?"disabled":"")?> onclick="js_prosseguir()">
		<input name="novo" type="button" value="Consultar" <?=($db_botao1==true?"disabled":"")?> onclick="js_pesquisaprontuarios()">
		<input name="prontuarios" type="button" value="Prontuários Médicos" <?=($db_botao1==true?"disabled":"")?> onclick="js_pesquisaprontuariomedico()">
		<input name="fatoresderisco" type="button" value="Fatores de Risco" <?=($db_botao1==true?"disabled":"")?> onclick="js_fatoresderisco()">
		
		
	 </td>
	 <td width="35%"  valign="top">
	 	<table border="1" width="100%">
	 		<tr>
	 			<td>
					<fieldset><legend><b>Triagem</b></legend>
						<table>
							<tr><td colspan="2"><?=@$Lsd24_v_motivo?><?=@$sd24_v_motivo?></td></tr>
							<tr><td><?=@$Lsd24_v_pressao?><?=@$sd24_v_pressao?></td><td><?=@$Lsd24_f_peso?><?=@$sd24_f_peso?></td></tr>
							<tr><td colspan="2"><?=@$Lsd24_f_temperatura?><?=@$sd24_f_temperatura?></td></tr>
							<tr><td colspan="2"><b>Profissional:</b><?=@$profissional_triagem?></td></tr>
							<tr><td colspan="2"><b>CBO:</b><?=@$cbo_triagem?></td></tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" height="100%">
					<fieldset><legend><b>Fator de Risco</b></legend>
						<? if( isset($sd24_i_codigo) && (int)$sd24_i_codigo != 0 ) { ?>
						<iframe id="framefatorderisco" 
								name="framefatorderisco"  
								src="sau4_framefatorderisco001.php?chavepesquisacgs=<?=$z01_i_cgsund?>"   
								width="100%" 
								height="100" 
								scrolling="yes"
								marginwidth="0"
								marginheight="0" 
								frameborder="0">
						</iframe>
						<? } ?>
					
					</fieldset>
				</td>
			</tr>
		</table>				
	 </td>
  </tr>
</table>
  
  </center>
</form>

<script>
//document.form1.cancelar.style.visibility="<?=($db_botao1==false?'hidden':'visible')?>";

function js_novaficha(){
     parent.document.formaba.a1.disabled = true;
     parent.document.formaba.a2.disabled = true;
     parent.iframe_a1.location.href='sau4_consultamedica001.php';
     parent.mo_camada('a1');
}

function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
//    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_sequencial&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){ 
//        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd04_i_codigo|rh70_estrutural|rh70_descr|rh70_estrutural&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_descr.value = '';
     }else{
       document.form1.rh70_estrutural.value = '';
     }
  }
}
function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4){
  document.form1.rh70_descr.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.sd29_i_profissional.value = chave3;
  document.form1.rh70_sequencial.value = chave4;
  if(erro==true){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form1.sd29_i_profissional.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;

  document.form1.sd29_i_procedimento.value = '';
  document.form1.sd63_c_procedimento.value = '';
  document.form1.sd63_c_nome.value = '';
  db_iframe_especmedico.hide();

  if(chave2=''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}



function js_pesquisasd29_i_procedimento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
  }else{
      if(document.form1.sd29_i_procedimento.value != ''){ 
         js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&chave_sd96_i_procedimento='+document.form1.sd29_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
      }     	
    else if(document.form1.sd63_c_procedimento.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&chave_sd63_c_procedimento='+document.form1.sd63_c_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
            
     }else{     
        document.form1.sd63_c_nome.value = ''; 
     }
  }
}


//Função igual a anteriror pois os ifs estao invertidos,esta é a primeira
//function js_pesquisasd29_i_procedimento(mostra){
  //if(mostra==true){
    //js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
  //}else{
    // if(document.form1.sd63_c_procedimento.value != ''){ 
       // js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&chave_sd63_c_procedimento='+document.form1.sd63_c_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
     //}              	
    //else if(document.form1.sd29_i_procedimento.value != ''){ 
       //  js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&chave_sd96_i_procedimento='+document.form1.sd29_i_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
     //}else{     
     //   document.form1.sd63_c_nome.value = ''; 
    // }
  //}
//}

//esta funcao foi modificada pela decima
//function js_pesquisasd29_i_procedimento(mostra){
 // if(mostra==true){
   // js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
 // }else{
     //if(document.form1.sd63_c_procedimento.value != ''){ 
      //  js_OpenJanelaIframe('','db_iframe_sau_proccbo','func_sau_proccbo.php?chave_rh70_sequencial='+document.form1.rh70_sequencial.value+'&chave_sd63_c_procedimento='+document.form1.sd63_c_procedimento.value+'&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome','Pesquisa',true);
    //}else{
      // document.form1.sd63_c_nome.value = ''; 
    // }
  //}
//}
function js_mostraprocedimentos(chave,erro){
  document.form1.sd63_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procedimento.focus(); 
    document.form1.sd29_i_procedimento.value = ''; 
    document.form1.sd29_c_procedimento.focus(); 
    document.form1.sd29_c_procedimento.value = ''; 
  }
}
function js_mostraprocedimentos1(chave1,chave2,chave3){
	//alert(chave1);
  if(chave1==''){
  	alert('CBO não tem ligação com procedimento');
  }
  document.form1.sd29_i_procedimento.value = chave1;
  document.form1.sd63_c_procedimento.value = chave2;
  document.form1.sd63_c_nome.value = chave3;
  db_iframe_sau_proccbo.hide();
}

function js_pesquisasd03_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',true);
  }else{
     if(document.form1.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
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
    js_pesquisasd04_i_cbo(true);    
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
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


function js_pesquisasd29_i_procafaixaetaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfaixaetaria','func_procfaixaetaria.php?funcao_js=parent.js_mostraprocfaixaetaria1|sd16_i_codigo|sd16_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd29_i_procafaixaetaria.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_procfaixaetaria','func_procfaixaetaria.php?pesquisa_chave='+document.form1.sd29_i_procafaixaetaria.value+'&funcao_js=parent.js_mostraprocfaixaetaria','Pesquisa',false);
     }else{
       document.form1.sd16_i_codigo.value = ''; 
     }
  }
}
function js_mostraprocfaixaetaria(chave,erro){
  document.form1.sd16_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd29_i_procafaixaetaria.focus(); 
    document.form1.sd29_i_procafaixaetaria.value = ''; 
  }
}
function js_mostraprocfaixaetaria1(chave1,chave2){
  document.form1.sd29_i_procafaixaetaria.value = chave1;
  document.form1.sd16_i_codigo.value = chave2;
  db_iframe_procfaixaetaria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_prontproced','func_prontproced.php?funcao_js=parent.js_preenchepesquisa|sd29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontproced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
//document.form1.sd24_i_codigo.value = parent.iframe_a21.document.form1.z01_nome.value;

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
    //alert(query);
    jan = window.open('sau4_prontuariomedico003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);	
  }                                                          						
}


function js_pesquisaprontuarios(){	
  js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios002.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo|z01_v_nome|sd24_i_numcgs','Pesquisa',true);
}

function js_preenchecgs(chave){
  db_iframe_cgs_und.hide();
  
  location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+chave+'&triagem='+'<?=@$triagem?>';
}

function js_preenchepesquisa(chave1,chave2,chave3){
 db_iframe_prontuarios.hide();
 location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave1;
 //retirado pois no select da prontuarios ja vem CGS
 //+'&z01_v_nome='+chave2+'&z01_i_cgsund='+chave3
}

function js_fatoresderisco(){
  if(document.form1.sd24_i_codigo.value==""){
	 alert("FAA não informada!");	
  }else{
	  iTop = ( screen.availHeight-800 ) / 2;
	  iLeft = ( screen.availWidth-800 ) / 2; 	
	  js_OpenJanelaIframe('','db_iframe_fatoresderisco','sau4_consultamedica006.php?chavepesquisacgs='+document.form1.z01_i_cgsund.value,'Fator de Risco',true, '40', iLeft, 800, 320);
  }
}

function js_prosseguir(){
  if(document.form1.sd24_i_codigo.value==""){
	 alert("FAA não informada!");	
  }else{
     <?
	   //$clprontproced->sql_record($clprontproced->sql_query(null,"prontuarios.*, cgs_und.*, medicos.*, rhcbo.* ",null,"sd29_i_prontuario = $chavepesquisaprontuario"));
	   if( $clprontproced->numrows > 0){
		  echo "parent.document.formaba.a2.disabled = false;";
		  echo "parent.iframe_a2.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=$chavepesquisaprontuario&chaveprofissional='+document.form1.sd03_i_codigo.value;";  
		  echo "parent.mo_camada('a2');";
	   }else{
	   	?>alert('FAA sem procedimentos.');<?
	   }   
     ?>
  }
}


function js_diagnostico(){

   parent.document.formaba.a2.disabled = false;
   parent.iframe_a2.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=<?=@$chavepesquisaprontuario?>&chaveprofissional'+document.form1.sd03_i_codigo.value;  
   
}


</script>