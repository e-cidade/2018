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

//MODULO: saude
$clunidademedicos->rotulo->label();
$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
//Nome do profissional
$clrotulo->label("z01_nome");
//Unidade/Departamento
$clrotulo->label("descrdepto");
//Rhcbo
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
//sau_turnoatend
$clrotulo->label("sd43_cod_turnat");
$clrotulo->label("sd43_v_descricao"); 
$clrotulo->label("sd43_c_horainicial");
$clrotulo->label("sd43_c_horafinal"); 

?>
<form name="form1" method="post">
  <table width="80%">
    <tr>
      <td>
         <fieldset><legend><b>Horário de Atendimento</b></legend>
         <center>
         <table>
             <tr>
                 <td nowrap title="<?=@$Tsd04_i_medico?>"><?=@$Lsd04_i_medico?></td>
                 <td>
                    <?
                      db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',3);
                      db_input('z01_nome',59,$Iz01_nome,true,'text',3,'');
                     ?>
                 </td>
             </tr>
             <tr>
                 <td nowrap title="<?=@$Tsd30_i_undmed?>">
                     <?
                        db_ancora(@$Lsd30_i_undmed,"js_pesquisasd30_i_undmed(true);",$db_opcao);
                     ?>
                 </td>
                 <td>
                     <?
                       db_input('sd30_i_codigo',10,$Isd30_i_codigo,true,'hidden',$db_opcao);
                       db_input('sd30_i_undmed',10,$Isd30_i_undmed,true,'text',$db_opcao," onchange='js_pesquisasd30_i_undmed(false);'");
                       db_input('rh70_descr',59,$Irh70_descr,true,'text',3,'');
                     ?>
                 </td>
             </tr>
             <tr>
                 <td nowrap title="<?=@$Tsd04_i_unidade?>">
                    <?=@$Lsd04_i_unidade?>
                 </td>
                 <td>
                 <?
                    db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',3)
                 ?>
                 <?
                    db_input('descrdepto',59,$Idescrdepto,true,'text',3,'');
                    db_input('sd30_i_turno',10,$Isd43_cod_turnat,true,'hidden',3);
                    db_input('sd43_v_descricao',10,$Isd43_v_descricao,true,'hidden',3);
                    db_input('sd43_c_horainicial',10,$Isd43_c_horainicial,true,'hidden',3);
                    db_input('sd43_c_horafinal',10,$Isd43_c_horafinal,true,'hidden',3);
                    
                  ?>
                 </td>
             </tr>       

             <tr>
                 <td nowrap title="escolhe se queremos ou não que apareça as grades já finalizadas na grade de hórarios.">
                    <b>Validade</b>
                 </td>
                 <td>
                 	<input name="validade" type="checkbox" <?=(isset($validade)&&$validade=="true")?"checked":"" ?> onclick="location.href='sau1_undmedhorario006.php?sd04_i_medico=<?=$sd04_i_medico?>&z01_nome=<?=$z01_nome?>&validade='+this.checked" >
                 </td>
             </tr>

             
             <tr>
                 <td colspan="2" >
                    <fieldset><legend><b>Lançamento</b></legend>
                        
                        <table border="0">
                            <tr>
                                <td rowspan="2"> 
                                    <table> 
                                      <tr>
                                          <td nowrap title="<?=@$Tsd30_c_tipograde?>"><?=@$Lsd30_c_tipograde?></td>
                                          <td>
                                             <?
                                                $x = array('I'=>'Intervalo','P'=>'Período');
                                                db_select('sd30_c_tipograde',$x,true,$db_opcao,"");
                                             ?>                                             
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Tsd30_i_tipoficha?>"><?=@$Lsd30_i_tipoficha?></td>
                                          <td>
                                            <?
                                              $result = $clsau_tipoficha->sql_record($clsau_tipoficha->sql_query("","*"));
                                              db_selectrecord("sd30_i_tipoficha",$result,true,$db_opcao,'','','','','',1);
                                            ?>                                          
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Tsd30_i_diasemana?>"><?=@$Lsd30_i_diasemana?></td>
                                          <td>
                                            <?
                                              //$result = $cldiasemana->sql_record($cldiasemana->sql_query("","*"));
                                              //db_selectrecord("sd30_i_diasemana",$result,true,$db_opcao,'','','','','',1);
                                             ?>
                                             <input type="checkbox" name="chk_seg" value="2" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==2?'checked':''?> >Seg 
                                             <input type="checkbox" name="chk_ter" value="3" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==3?'checked':''?> >Ter
                                             <input type="checkbox" name="chk_qua" value="4" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==4?'checked':''?> >Qua<br>
                                             <input type="checkbox" name="chk_qui" value="5" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==5?'checked':''?> >Qui 
                                             <input type="checkbox" name="chk_sex" value="6" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==6?'checked':''?> >Sex
                                             <input type="checkbox" name="chk_sab" value="7" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==7?'checked':''?> >Sáb 
                                             <input type="checkbox" name="chk_dom" value="1" onchange="js_diasemana(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$sd30_i_diasemana==1?'checked':''?> >Dom
                                             
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title=""><b>Periodicidade</b></td>
                                          <td>                                            
                                             <input type="radio" name="rad_periodo" value="1" onClick="js_semanames();" checked>Semanal
                                             <input type="radio" name="rad_periodo" value="2" onClick="js_semanames();" >Quinzenal
                                             <br><input type="radio" name="rad_periodo" value="3" onClick="js_semanames();" >Mensal
                                             <select id="semanames" name="semanames" disabled>
                                                 <option value="0">1°-Semana</option>
                                                 <option value="1">2°-Semana</option>
                                                 <option value="2">3°-semana</option>
                                                 <option value="3">4°-semana</option>
                                             </select>
                                          </td>
                                      </tr>
                                    </table>
                                </td>
                                <td> 
                                    <table>
                                       <tr>
                                            <td> 
                                                <fieldset><legend><b>Data Validade</b></legend>
                                                    <table>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tsd30_d_valinicial?>"><?=@$Lsd30_d_valinicial?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('sd30_d_valinicial',@$sd30_d_valinicial_dia,@$sd30_d_valinicial_mes,@$sd30_d_valinicial_ano,true,'text',$db_opcao );
                                                               ?>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tsd30_d_valfinal?>"><?=@$Lsd30_d_valfinal?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('sd30_d_valfinal',@$sd30_d_valfinal_dia,@$sd30_d_valfinal_mes,@$sd30_d_valfinal_ano,true,'text',$db_opcao );
                                                               ?>
                                                            </td>
                                                         </tr>
                                                    </table>
                                                </fieldset>
                                            </td>
                                            <td> 
                                                <fieldset><legend><b>Horário</b></legend>
                                                    <table>
                                                        <tr>
                                                            <td nowrap title="<?=@$Tsd30_c_horaini?>"> <?=@$Lsd30_c_horaini?> </td>
                                                            <td>
                                                              <?
                                                              db_input('sd30_c_horaini', 5, $Isd30_c_horaini, true, 'text', $db_opcao, 
                                                                       "onKeyUp=\"mascara_hora(this.value,'sd30_c_horaini', event)\"".
                                                                       " onBlur=\"js_verifica_hora_webseller(this.value, this.name)\" "
                                                                      );
                                                              ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap title="<?=@$Tsd30_c_horafim?>"> <?=@$Lsd30_c_horafim?> </td>
                                                            <td>
                                                              <?
                                                                db_input('sd30_c_horafim', 5, $Isd30_c_horafim, true, 'text', $db_opcao, 
                                                                         "OnKeyUp=\"mascara_hora(this.value,'sd30_c_horafim',event)\"".
                                                                         " onBlur=\"js_verifica_hora_webseller(this.value, this.name)\" " 
                                                                        );
                                                              ?>                                                            
                                                            </td>
                                                        </tr>                                                        
                                                    </table>
                                                </fieldset>
                                            </td>
                                       </tr>
                                       
                                   </table>
                                </td>
                            </tr>
                            <tr > 
                                 <td nowrap title="<?=@$Tsd30_i_reservas?>">
                                    <?=@$Lsd30_i_fichas?>
                                    <? db_input('sd30_i_fichas',10,$Isd30_i_fichas,true,'text',$db_opcao,"")?>
                                    &nbsp;&nbsp;
                                    <?=@$Lsd30_i_reservas?>
                                    <?db_input('sd30_i_reservas',10,$Isd30_i_reservas,true,'text',$db_opcao,"")?>
                                 </td>
                            </tr>

                        </table>
                        
                    </fieldset>
                 </td>
             </tr>
         </table>
         <center>
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
		       type="submit" id="db_opcao"  
		       value="<?=($db_opcao==1?"Lançar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
           onclick="return js_valida();"
           >
         <input type="button" name="limpa" value="Limpar" onclick="location.href='sau1_undmedhorario006.php?sd04_i_medico=<?=$sd04_i_medico?>&z01_nome=<?=$z01_nome?>'">
         </center>
         <table width="100%">
           <tr>
              <td valign="top"><br>
                <?
                  $chavepri= array("sd30_i_codigo"=>@$sd30_i_codigo );
                  $cliframe_alterar_excluir->chavepri=$chavepri;
                  $str_validade=" and (sd30_d_valfinal is null or sd30_d_valfinal >= '".date('Y-m-d')."' )";
                  if( isset($validade)&&$validade=="true" ){
                  	  $str_validade = " "; 
                  }
                  @$cliframe_alterar_excluir->sql = $clundmedhorario->sql_query_ext("","sd30_i_codigo, 
                                                                                    sd04_i_unidade,
                                                                                    rh70_descr,
                                                                                    sd30_d_valinicial, 
                                                                                    sd30_d_valfinal, 
                                                                                    case sd30_c_tipograde
                                                                                      when 'I' then 'Intervalo'
                                                                                      when 'P' then 'Período'
                                                                                      else 'Não Informado'
                                                                                    end as sd30_c_tipograde, 
                                                                                    sd101_c_descr, 
                                                                                    ed32_c_descr, 
                                                                                    sd30_c_horaini, 
                                                                                    sd30_c_horafim, 
                                                                                    sd30_i_fichas, 
                                                                                    sd30_i_reservas",
                                                                                    "sd04_i_unidade, sd30_i_diasemana, sd30_d_valinicial,sd30_c_horaini",
                                                                                    " sd04_i_medico = $sd04_i_medico $str_validade ");

                  @$cliframe_alterar_excluir->campos  ="sd30_i_codigo, sd04_i_unidade, rh70_descr, sd30_d_valinicial, sd30_d_valfinal, sd30_c_tipograde, sd101_c_descr, ed32_c_descr, sd30_c_horaini, sd30_c_horafim, sd30_i_fichas, sd30_i_reservas";
                  $cliframe_alterar_excluir->legenda="Grade de Horário";
                  $cliframe_alterar_excluir->alignlegenda = "left";
                  //$cliframe_alterar_excluir->iframe_height ="200";
                  $cliframe_alterar_excluir->iframe_width ="100%";
                  $cliframe_alterar_excluir->tamfontecabec = 9;
                  $cliframe_alterar_excluir->tamfontecorpo = 9;
                  $cliframe_alterar_excluir->formulario = false;
                  //$cliframe_alterar_excluir->opcoes = 3;
                  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                ?>
               </td>           
           </tr>
         </table>
         
         </fieldset>
      </td>
    </tr>
 </table>
</form>


<script type="text/javascript">
function js_diasemana( opcao, obj ){
	if( opcao == 2 ){
		document.form1.chk_seg.checked = false;
		document.form1.chk_ter.checked = false;
		document.form1.chk_qua.checked = false;
		document.form1.chk_qui.checked = false;
		document.form1.chk_sex.checked = false;
		document.form1.chk_sab.checked = false;
		document.form1.chk_dom.checked = false;
		obj.checked = true;
	}
}
function js_valida(){
          cfm=false;
          F=document.form1;
          if(F.chk_seg.checked == true){cfm=true;}
          if(F.chk_ter.checked == true){cfm=true;}
          if(F.chk_qua.checked == true){cfm=true;}
          if(F.chk_qui.checked == true){cfm=true;}
          if(F.chk_sex.checked == true){cfm=true;}
          if(F.chk_sab.checked == true){cfm=true;}
          if(F.chk_dom.checked == true){cfm=true;}
          if(cfm==false){
             alert('Escolha no minimo um dia da semana!');
          }
          if((document.form1.rad_periodo[2].checked==true)||(document.form1.rad_periodo[2].checked==true)){
              if((document.form1.sd30_d_valinicial.value=="")||(document.form1.sd30_d_valfinal.value=="")){
                 alert("Entre com a data de Validade!");
                 cfm=false;
              }
          }
          return cfm;
}

function js_validahora(hora,x){
	if( document.form1.sd43_c_horainicial.value == "" || document.form1.sd43_c_horafinal.value == "" ){
		alert('UPS não tem turno definido.');
	    document.form1[x].value="";  
	 	document.form1[x].focus();
	}else{
		hr_atuali  = (document.form1.sd30_c_horaini.value.substring(0,2));
	 	mi_atuali  = (document.form1.sd30_c_horaini.value.substring(3,5));
		hr_atualf  = (document.form1.sd30_c_horafim.value.substring(0,2));
	 	mi_atualf  = (document.form1.sd30_c_horafim.value.substring(3,5));

		hr_inicial = (document.form1.sd43_c_horainicial.value.substring(0,2));
	 	mi_inicial = (document.form1.sd43_c_horainicial.value.substring(3,5));
		hr_final   = (document.form1.sd43_c_horafinal.value.substring(0,2));
	 	mi_final   = (document.form1.sd43_c_horafinal.value.substring(3,5));
		/*
	 	hora_ini   = parseInt(hr_inicial)*60+parseInt(mi_inicial);
	 	hora_fin   = parseInt(hr_final)*60+parseInt(mi_final);
	 	hora_atui  = parseInt(hr_atuali)*60+parseInt(mi_atuali);
	 	hora_atuf  = parseInt(hr_atualf)*60+parseInt(mi_atualf);
	 	*/
	 	hora_ini   = (hr_inicial)*60+parseInt(mi_inicial);
	 	hora_fin   = (hr_final)*60+parseInt(mi_final);
	 	hora_atui  = (hr_atuali)*60+parseInt(mi_atuali);
	 	hora_atuf  = (hr_atualf)*60+parseInt(mi_atualf);
	 	
	 	if( ( hora_atui != 0 && hora_atui < hora_ini) || ( hora_atuf != 0 && hora_atuf > hora_fin ) ){
	 		alert('Horário informado não corresponde com o turno da UPS');
	       	document.form1[x].value="";  
	 	   	document.form1[x].focus();
	 	}
	 }
}

function js_pesquisasd30_i_undmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_sd04_i_medico='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal','Pesquisa',true);
  }else{
     if(document.form1.sd30_i_undmed.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_sd04_i_medico='+document.form1.sd04_i_medico.value+'&chave_sd27_i_codigo='+document.form1.sd30_i_undmed.value+'&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal','Pesquisa',false);
     }else{
       document.form1.sd30_i_undmed.value = ''; 
     }
  }
}
function js_mostraespecmedico(chave,erro){
  document.form1.sd30_i_undmed.value = chave; 
  if(erro==true){ 
    document.form1.sd30_i_undmed.focus(); 
    document.form1.sd30_i_undmed.value = ''; 
  }
}
function js_mostraespecmedico1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8){
  document.form1.sd30_i_undmed.value      = chave1;
  document.form1.rh70_descr.value         = chave2;
  document.form1.sd04_i_unidade.value     = chave3;
  document.form1.descrdepto.value         = chave4;
  document.form1.sd30_i_turno.value    = chave5;
  document.form1.sd43_v_descricao.value   = chave6; 
  document.form1.sd43_c_horainicial.value = chave7;
  document.form1.sd43_c_horafinal.value   = chave8; 
  
  db_iframe_especmedico.hide();
}
function js_semanames(){
    if((document.form1.sd30_d_valinicial.value=="")||(document.form1.sd30_d_valfinal.value=="")){
        alert('Entre Com a data de validade!');
        document.form1.rad_periodo[0].checked=true;
    }
    if(document.form1.rad_periodo[2].checked==true){
       document.form1.semanames.disabled=false;
    }else{
       document.form1.semanames.disabled=true;
    }  
}
</script>