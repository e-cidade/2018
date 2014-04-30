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

//MODULO: Laboratório
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$cllab_horario->rotulo->label ();
$clrotulo = new rotulocampo ( );
$clrotulo->label ( "la09_i_codigo" );
$clrotulo->label ( "la01_c_descr" );
$clrotulo->label ( "la08_c_descr" );
?>
<form name="form1" method="post">
<table width="100%">
	<tr>
		<td>
		<fieldset><legend>Horário</legend>
		<center>
		<table>
			<tr>
         <td nowrap title="<?=@$Tla35_i_codigo?>">
       <?=@$Lla35_i_codigo?>
    </td>
    <td> 
<?
db_input ( 'la35_i_codigo', 10, $Ila35_i_codigo, true, 'text', 3, "" )?>
    </td>
  </tr>
  
   <tr>
    <td nowrap title="<?=@$Tla09_i_setor?>">
       <b>Laboratorio:</b>
    </td>
    <td>
<?
db_input ( 'la02_i_codigo', 10, @$Ila09_i_labsetor, true, 'text', 3, "" )?>
       <?
							db_input ( 'la02_c_descr', 50, @$Ila02_c_descr, true, 'text', 3, '' )?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla35_i_setorexame?>">
       <?
							db_ancora ('<b>Exame:</b>', "js_pesquisala35_i_setorexame(true);", $db_opcao );
							?>
    </td>
    <td> 
<?
db_input ( 'la08_i_codigo', 10, $Ila35_i_setorexame, true, 'text', $db_opcao, " onchange='js_pesquisala35_i_setorexame(false);'" );
db_input ( 'la35_i_setorexame', 10,'', true, 'hidden', $db_opcao,'');
db_input ( 'la08_c_descr', 50, $Ila08_c_descr, true, 'text', 3, '' );
?>
    </td>
  </tr>
			<tr>
				<td colspan="2">
				<fieldset><legend>Lançamento</legend>

				<table border="0">
					<tr>
						<td rowspan="2">
						<table>
							<tr>
								<td nowrap title="<?=@$Tla35_i_diasemana?>"><?=@$Lla35_i_diasemana?></td>
								<td>
                 <input type="checkbox" name="chk_seg" value="2" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 2 ? 'checked' : ''?>>Seg 
                 <input type="checkbox" name="chk_ter" value="3" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 3 ? 'checked' : ''?>>Ter 
								 <input type="checkbox" name="chk_qua" value="4" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 4 ? 'checked' : ''?>>Qua<br>
								 <input type="checkbox" name="chk_qui" value="5" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 5 ? 'checked' : ''?>>Qui 
								 <input type="checkbox" name="chk_sex" value="6" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 6 ? 'checked' : ''?>>Sex 
								 <input type="checkbox" name="chk_sab" value="7" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 7 ? 'checked' : ''?>>Sáb 
								 <input type="checkbox" name="chk_dom" value="1" <?=$db_opcao != 1 ? 'disabled ' : ''?><?=@$la35_i_diasemana == 1 ? 'checked' : ''?>>Dom
								</td>
							</tr>
							<tr>
								<td nowrap title=""><b>Periodicidade</b></td>
								<td><input type="radio" name="rad_periodo" value="1" onClick="js_semanames();" checked <?=$db_opcao != 1 ? 'disabled ' : ''?>>Semanal 
                <input type="radio" name="rad_periodo" value="2" onClick="js_semanames();" <?=$db_opcao != 1 ? 'disabled ' : ''?>>Quinzenal
								<br>
								<input type="radio" name="rad_periodo" value="3" onClick="js_semanames();" <?=$db_opcao != 1 ? 'disabled ' : ''?>>Mensal <select id="semanames" name="semanames" disabled>
									<option value="0">1°-Semana</option>
									<option value="1">2°-Semana</option>
									<option value="2">3°-semana</option>
									<option value="3">4°-semana</option>
								</select></td>
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
										<td nowrap title="<?=@$Tla35_d_valinicio?>"><?=@$Lla35_d_valinicio?></td>
										<td>
                      <?
                      if(isset($la35_d_valinicio)) {
                        
                        $aDataTmp = explode('/', $la35_d_valinicio);
                        if(count($aDataTmp) == 3) {

                          $la35_d_valinicio_dia = $aDataTmp[0];
                          $la35_d_valinicio_mes = $aDataTmp[1];
                          $la35_d_valinicio_ano = $aDataTmp[2];

                        }

                      }

											db_inputdata ( 'la35_d_valinicio', @$la35_d_valinicio_dia, @$la35_d_valinicio_mes, @$la35_d_valinicio_ano, true, 'text', $db_opcao );
										  ?>
                                                            </td>
									</tr>
									<tr>
										<td nowrap title="<?=@$Tla35_d_valfim?>"><?=@$Lla35_d_valfim?></td>
										<td>
                      <?
                      if(isset($la35_d_valfim)) {
                        
                        $aDataTmp = explode('/', $la35_d_valfim);
                        if(count($aDataTmp) == 3) {

                          $la35_d_valfim_dia = $aDataTmp[0];
                          $la35_d_valfim_mes = $aDataTmp[1];
                          $la35_d_valfim_ano = $aDataTmp[2];

                        }

                      }

											db_inputdata ( 'la35_d_valfim', @$la35_d_valfim_dia, @$la35_d_valfim_mes, @$la35_d_valfim_ano, true, 'text', $db_opcao );
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
										<td nowrap title="<?=@$Tla35_c_horaini?>"> <?=@$Lla35_c_horaini?> </td>
										<td>
                                          <?
											db_input ( 'la35_c_horaini', 5, $Ila35_c_horaini, true, 'text', $db_opcao, "onKeyUp=\"mascara_hora(this.value,'la35_c_horaini', event)\"  " );
										   ?>                                                            
                                         </td>
									</tr>
									<tr>
										<td nowrap title="<?=@$Tla35_c_horafim?>"> <?=@$Lla35_c_horafim?> </td>
										<td>
                                          <?
											db_input ( 'la35_c_horafim', 5, $Ila35_c_horafim, true, 'text', $db_opcao, "OnKeyUp=\"mascara_hora(this.value,'la35_c_horafim',event)\" " );
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
				</table>

				</fieldset>
				</td>
			</tr>
		</table>
		<center>
		<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Lançar" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" <?=($db_opcao != 3 ? 'onclick="return js_valida();"' : '')?> >
			<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1 == false ? "disabled" : "")?> onclick="location.href='lab1_lab_horario001.php?la02_i_codigo=<?=$la02_i_codigo?>&la02_c_descr=<?=$la02_c_descr?>'">
		</center>
		<table width="100%">
			<tr>
				<td valign="top"><br>
                <?
				  $chavepri = array ('la08_i_codigo' => @$la08_i_codigo,
                             'la08_c_descr' => @$la08_c_descr, 
                             "la35_i_codigo" => @$la35_i_codigo, 
                             "la02_i_codigo" => @$la02_i_codigo, 
                             "la02_c_descr" => @$la02_c_descr, 
                             "la35_i_setorexame" => @$la35_i_setorexame, 
                             "ed32_c_descr" => @$ed32_c_descr, 
                             "la35_c_horaini" => @$la35_c_horaini, 
                             "la35_c_horafim" => @$la35_c_horafim, 
                             "la35_d_valinicio" => @$la35_d_valinicio, 
                             "la35_d_valfim" => @$la35_d_valfim,
                             "la35_i_diasemana" => @$la35_i_diasemana
                            );
				  $cliframe_alterar_excluir->chavepri = $chavepri;
				  @$cliframe_alterar_excluir->sql = $cllab_horario->sql_query_laboratorio ( "", "*", "la08_c_descr,la35_i_diasemana", "la02_i_codigo = $la02_i_codigo" );
				  $cliframe_alterar_excluir->campos = "la35_i_codigo,la08_c_descr,ed32_c_descr,la35_c_horaini,la35_c_horafim,la35_d_valinicio,la35_d_valfim";
				  $cliframe_alterar_excluir->legenda = "Registros";
   				$cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
				  $cliframe_alterar_excluir->textocabec = "#DEB887";
				  $cliframe_alterar_excluir->textocorpo = "#444444";
				  $cliframe_alterar_excluir->fundocabec = "#444444";
				  $cliframe_alterar_excluir->fundocorpo = "#eaeaea";
				  $cliframe_alterar_excluir->iframe_height = "200";
				  $cliframe_alterar_excluir->iframe_width = "100%";
				  $cliframe_alterar_excluir->tamfontecabec = 9;
				  $cliframe_alterar_excluir->tamfontecorpo = 9;
				  $cliframe_alterar_excluir->formulario = false;
				  $cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao );
				?>
               </td>
			</tr>
		</table>
		
		</fieldset>
		</td>
	</tr>
</table>
</form>
<script>
function js_pesquisala35_i_setorexame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?la02_i_codigo=<?=$la02_i_codigo?>&funcao_js=parent.js_mostralab_setorexame1|la08_i_codigo|la08_c_descr|la09_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la08_i_codigo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?la02_i_codigo=<?=$la02_i_codigo?>&pesquisa_chave='+document.form1.la08_i_codigo.value+'&funcao_js=parent.js_mostralab_setorexame','Pesquisa',false);
     }else{
       document.form1.la08_c_descr.value = ''; 
       document.form1.la35_i_setorexame.value = ''; 
     }
  }
}
function js_mostralab_setorexame(chave,erro,chave2){
  document.form1.la08_c_descr.value = chave; 
  document.form1.la35_i_setorexame.value = chave2; 
  if(erro==true){ 
    document.form1.la35_i_setorexame.focus(); 
    document.form1.la35_i_setorexame.value = ''; 
    document.form1.la08_i_codigo.value = ''; 
  }
}
function js_mostralab_setorexame1(chave1,chave2,chave3){
  document.form1.la08_i_codigo.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  document.form1.la35_i_setorexame.value = chave3;
  db_iframe_lab_setorexame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_horario','func_lab_horario.php?funcao_js=parent.js_preenchepesquisa|la35_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_horario.hide();
  <?
		if ($db_opcao != 1) {
			echo " location.href = '" . basename ( $GLOBALS ["HTTP_SERVER_VARS"] ["PHP_SELF"] ) . "?chavepesquisa='+chave";
		}
		?>
}
function js_valida(){
      cfm=false;
      F=document.form1;
      
      if(document.form1.la35_i_setorexame.value == '' || document.form1.la08_i_codigo.value == '') {

        alert('Preencha o exame.');
        return false;

      }

      if(F.chk_seg.checked == true){cfm=true;}
      if(F.chk_ter.checked == true){cfm=true;}
      if(F.chk_qua.checked == true){cfm=true;}
      if(F.chk_qui.checked == true){cfm=true;}
      if(F.chk_sex.checked == true){cfm=true;}
      if(F.chk_sab.checked == true){cfm=true;}
      if(F.chk_dom.checked == true){cfm=true;}
      if(cfm==false){
          alert('Escolha no minimo um dia da semana!');
          return false;
      }
      if((document.form1.rad_periodo[2].checked==true)||(document.form1.rad_periodo[2].checked==true)){
          if((document.form1.la35_d_valinicio.value=="")||(document.form1.la35_d_valfim.value=="")){
              alert("Entre com a data de Validade!");
              return false;
          }
      }

      if(!js_validadata()) {
        return false;
      }

    
      if(!js_validahora()) {
        return false;
      }

      return cfm;

}
function js_semanames(){
     if((document.form1.la35_d_valinicio.value=="")||(document.form1.la35_d_valfim.value=="")){
         alert('Entre Com a data de validade!');
         document.form1.rad_periodo[0].checked=true;
     }
     if(document.form1.rad_periodo[2].checked==true){
         document.form1.semanames.disabled=false;
     }else{
         document.form1.semanames.disabled=true;
     }  
}

function js_validahora() {

  if(document.form1.la35_c_horaini.value == '' || document.form1.la35_c_horafim.value == '') {
      
    alert('Preencha os horarios!');
    return false;
   
  }
	
  if(document.form1.la35_c_horaini.value.length != 5 || document.form1.la35_c_horafim.value.length != 5) {
      
    alert('Preencha corretamente os horarios!');
    return false;
   
  }

  hr_ini  = (document.form1.la35_c_horaini.value.substring(0,2));
	mi_ini  = (document.form1.la35_c_horaini.value.substring(3,5));
	hr_fim  = (document.form1.la35_c_horafim.value.substring(0,2));
	mi_fim  = (document.form1.la35_c_horafim.value.substring(3,5));

  if(isNaN(hr_ini) || isNaN(mi_ini) ||  isNaN(hr_fim) || isNaN(mi_fim)) {
        
    alert('Preencha corretamente os horarios!');
    return false;

  }

	hora_ini  = parseInt(hr_ini, 10) * 60 + parseInt(mi_ini, 10);
	hora_fim  = parseInt(hr_fim, 10) * 60 + parseInt(mi_fim, 10);

  if(hora_ini > hora_fim) {
      
    alert('A hora final deve ser maior que a inicial.');
    return false;

  }

	return true;

}


function js_validadata() {

  if(document.form1.la35_d_valfim.value != "" || document.form1.la35_d_valinicio.value != "" ) {

    if(document.form1.la35_d_valfim.value == "" || document.form1.la35_d_valinicio.value == "" ) {

      alert('As duas datas devem ser preenchidas ou deixadas em branco.');
      return false;

    }

    aIni = document.form1.la35_d_valinicio.value.split('/');
    aFim = document.form1.la35_d_valfim.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

  	if(dFim < dIni) {
		
      alert("Data final nao pode ser menor que a data inicial.");
			document.form1.la35_d_valfim.value = '';
      return false;

		}	

    return true;

  }

  return true;
				
}


function formata_hora(campo){
	 digitos = campo.value.length;
	 valor = campo.value;
	 if(digitos==2){
	  campo.value = valor+':';
	 }
	}
</script>