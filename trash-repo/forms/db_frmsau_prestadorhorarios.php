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
$clsau_prestadorhorarios->rotulo->label();
$clrotulo = new rotulocampo;
//Nome do profissional
$clrotulo->label("z01_nome");
//Prestador Vinclos
$clrotulo->label("s111_i_prestador");
$clrotulo->label("s111_i_codigo");
$clrotulo->label("s111_i_exame");
//Exame
$clrotulo->label("s108_c_exame");




?>
<form name="form1" method="post">
  <table width="80%">
    <tr>
      <td>
         <fieldset><legend>Horário de Atendimento</legend>
         <center>
         <table>
             <tr>
                 <td nowrap title="<?=@$Ts111_i_prestador?>"><?=@$Ls111_i_prestador?></td>
                 <td>
                    <?
                      db_input('s111_i_prestador',10,@$Is111_i_prestador,true,'text',3);
                      db_input('z01_nome',59,$Iz01_nome,true,'text',3,'');
                     ?>
                 </td>
             </tr>
             <tr>
                 <td nowrap title="<?=@$Ts111_i_exame?>">
                     <?
                        db_ancora(@$Ls111_i_exame,"js_pesquisas111_i_exame(true);",$db_opcao);
                     ?>
                 </td>
                 <td>
                     <?
                       db_input('s112_i_codigo',10,$Is112_i_codigo,true,'hidden',$db_opcao);
                       db_input('s112_i_prestadorvinc',10,$Is112_i_prestadorvinc,true,'hidden',$db_opcao);
                       db_input('s111_i_exame',10,$Is111_i_exame,true,'text',$db_opcao," onchange='js_pesquisasd30_i_undmed(false);'");
                       db_input('s108_c_exame',59,$Is108_c_exame,true,'text',3,'');
                     ?>
                 </td>
             </tr>    
             <tr>
                 <td colspan="2" >
                    <fieldset><legend>Lançamento</legend>
                        
                        <table>
                            <tr>
                                <td rowspan="2"> 
                                    <table> 
                                      <tr>
                                          <td nowrap title="<?=@$Ts112_c_tipograde?>"><?=@$Ls112_c_tipograde?></td>
                                          <td>
                                             <?
                                                $x = array('I'=>'Intervalo','P'=>'Período');
                                                db_select('s112_c_tipograde',$x,true,$db_opcao,"");
                                             ?>                                             
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Ts112_i_tipoficha?>"><?=@$Ls112_i_tipoficha?></td>
                                          <td>
                                            <?
                                              $result = $clsau_tipoficha->sql_record($clsau_tipoficha->sql_query("","*"));
                                              db_selectrecord("s112_i_tipoficha",$result,true,$db_opcao,'','','','','',1);
                                            ?>                                          
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Ts112_i_diasemana?>"><?=@$Ls112_i_diasemana?></td>
                                          <td>
                                            <?
                                              $result = $cldiasemana->sql_record($cldiasemana->sql_query("","*"));
                                              db_selectrecord("s112_i_diasemana",$result,true,$db_opcao,'','','','','',1);
                                                                                        ?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Ts112_i_fichas?>"><?=@$Ls112_i_fichas?></td>
                                          <td>
                                             <?
                                                db_input('s112_i_fichas',10,$Is112_i_fichas,true,'text',$db_opcao,"")
                                             ?>                                          
                                          </td>
                                      </tr>
                                    </table>
                                </td>
                                <td> 
                                    <table>
                                       <tr>
                                            <td> 
                                                <fieldset><legend>Data Validade</legend>
                                                    <table>
                                                         <tr>
                                                            <td nowrap title="<?=@$Ts112_d_valinicial?>"><?=@$Ls112_d_valinicial?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('s112_d_valinicial',@$s112_d_valinicial_dia,@$s112_d_valinicial_mes,@$s112_d_valinicial_ano,true,'text',$db_opcao );
                                                               ?>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td nowrap title="<?=@$Ts112_d_valfinal?>"><?=@$Ls112_d_valfinal?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('s112_d_valfinal',@$s112_d_valfinal_dia,@$s112_d_valfinal_mes,@$s112_d_valfinal_ano,true,'text',$db_opcao );
                                                               ?>
                                                            </td>
                                                         </tr>
                                                    </table>
                                                </fieldset>
                                            </td>
                                            <td> 
                                                <fieldset><legend>Horário</legend>
                                                    <table>
                                                        <tr>
                                                            <td nowrap title="<?=@$Ts112_c_horaini?>"> <?=@$Ls112_c_horaini?> </td>
                                                            <td>
                                                              <?
                                                                db_input('s112_c_horaini',5,$Is112_c_horaini,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'s112_c_horaini', event)\" onBlur=\"js_validahora(this,'s112_c_horaini')\" ");
                                                              ?>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap title="<?=@$Ts112_c_horafim?>"> <?=@$Ls112_c_horafim?> </td>
                                                            <td>
                                                              <?
                                                                db_input('s112_c_horafim',5,$Is112_c_horafim,true,'text',$db_opcao,"OnKeyUp=\"mascara_hora(this.value,'s112_c_horafim',event)\" onBlur=\"js_validahora(this,'s112_c_horafim')\" " );
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
                                 <td nowrap title="<?=@$Ts112_i_reservas?>"><?=@$Ls112_i_reservas?>
                                   <?
                                     db_input('s112_i_reservas',10,$Is112_i_reservas,true,'text',$db_opcao,"")
                                   ?>                                  
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
		       value="<?=($db_opcao==1?"Lançar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" >
         <input type="button" name="limpa" value="Limpa" onclick="location.href='sau1_sau_prestadorhorarios001.php?s111_i_prestador=<?=$s111_i_prestador?>&z01_nome=<?=$z01_nome?>'">
         </center>
         
         <table width="100%">
           <tr>
              <td valign="top"><br>
                <?

                  $chavepri= array("s111_i_exame"=>@$s111_i_exame, "s112_i_codigo"=>@$s112_i_codigo );
                  $cliframe_alterar_excluir->chavepri=$chavepri;
                  @$cliframe_alterar_excluir->sql = $clsau_prestadorhorarios->sql_query("","s112_i_codigo, 
                                                                                    s111_i_exame,
                                                                                    s108_c_exame,
                                                                                    s112_d_valinicial, 
                                                                                    s112_d_valfinal, 
                                                                                    case s112_c_tipograde
                                                                                      when 'I' then 'Intervalo'
                                                                                      when 'P' then 'Período'
                                                                                      else 'Não Informado'
                                                                                    end as s112_c_tipograde, 
                                                                                    sd101_c_descr, 
                                                                                    ed32_c_descr,
                                                                                    s112_c_horaini, 
                                                                                    s112_c_horafim, 
                                                                                    s112_i_fichas, 
                                                                                    s112_i_reservas",
                                                                                    "ed32_i_codigo, s112_c_horaini"," s111_i_prestador = $s111_i_prestador");
                  @$cliframe_alterar_excluir->campos  ="s112_i_codigo, s108_c_exame, s112_d_valinicial, s112_d_valfinal, s112_c_tipograde, sd101_c_descr, ed32_c_descr, s112_c_horaini, s112_c_horafim, s112_i_fichas, s112_i_reservas";
                  $cliframe_alterar_excluir->legenda="Grade de Horário";
                  $cliframe_alterar_excluir->alignlegenda = "left";
                  //$cliframe_alterar_excluir->iframe_height ="200";
                  $cliframe_alterar_excluir->iframe_width ="100%";
                  $cliframe_alterar_excluir->tamfontecabec = 9;
                  $cliframe_alterar_excluir->tamfontecorpo = 9;
                  $cliframe_alterar_excluir->formulario = false;
                  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao2);
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
function js_validahora(hora,x){
	if( document.form1.sd43_c_horainicial.value == "" || document.form1.sd43_c_horafinal.value == "" ){
		alert('UPS não tem turno definido.');
	    document.form1[x].value="";  
	 	document.form1[x].focus();
	}else{
		hr_atuali  = (document.form1.s112_c_horaini.value.substring(0,2));
	 	mi_atuali  = (document.form1.s112_c_horaini.value.substring(3,5));
		hr_atualf  = (document.form1.s112_c_horafim.value.substring(0,2));
	 	mi_atualf  = (document.form1.s112_c_horafim.value.substring(3,5));

		hr_inicial = (document.form1.sd43_c_horainicial.value.substring(0,2));
	 	mi_inicial = (document.form1.sd43_c_horainicial.value.substring(3,5));
		hr_final   = (document.form1.sd43_c_horafinal.value.substring(0,2));
	 	mi_final   = (document.form1.sd43_c_horafinal.value.substring(3,5));
	
	 	hora_ini   = parseInt(hr_inicial)*60+parseInt(mi_inicial);
	 	hora_fin   = parseInt(hr_final)*60+parseInt(mi_final);
	 	hora_atui  = parseInt(hr_atuali)*60+parseInt(mi_atuali);
	 	hora_atuf  = parseInt(hr_atualf)*60+parseInt(mi_atualf);
	 	
	 	if( ( hora_atui != 0 && hora_atui < hora_ini) || ( hora_atuf != 0 && hora_atuf > hora_fin ) ){
	 		alert('Horário informado não corresponde com o turno da UPS');
	       	document.form1[x].value="";  
	 	   	document.form1[x].focus();
	 	}
	 }
}


function js_pesquisas111_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_prestadorvinculos','func_sau_prestadorvinculos.php?chave_s111_i_prestador='+document.form1.s111_i_prestador.value+'&funcao_js=parent.js_mostraexame1|s111_i_codigo|s111_i_exame|s108_c_exame|s111_c_situacao','Pesquisa',true);
  }else{
     if(document.form1.s111_i_exame.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_prestadorvinculos','func_sau_prestadorvinculos.php?chave_s111_i_prestador='+document.form1.s111_i_prestador.value+'&chave_s111_i_exame='+document.form1.s111_i_exame.value+'&funcao_js=parent.js_mostraexame1|s111_i_codigo|s111_i_exame|s108_c_exame|s111_c_situacao','Pesquisa',false);
     }else{
       document.form1.s111_i_exame.value = ''; 
     }
  }
}
function js_mostraexame1(chave1,chave2,chave3,chave4){

  document.form1.s112_i_prestadorvinc.value = chave1;
  document.form1.s111_i_exame.value         = chave2;
  document.form1.s108_c_exame.value         = chave3;
  
  db_iframe_sau_prestadorvinculos.hide();
}


</script>