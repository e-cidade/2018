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
$cllab_ausencia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la36_c_horaini");
$clrotulo->label("la36_c_horafim");
$clrotulo->label("la36_i_tipo");
$clrotulo->label("la23_c_descr");
$clrotulo->label("la02_i_descr");



$db_botao1 = false;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla36_i_codigo?>">
       <?=@$Lla36_i_codigo?>
    </td>
    <td> 
<?
db_input('la36_i_codigo',10,$Ila36_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla09_i_setor?>">
       <b>Laboratorio:</b>
    </td>
    <td>
<?
db_input('la02_i_codigo',10,@$Ila09_i_labsetor,true,'text',3,"")
?>
       <?
db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla36_i_setorexame?>">
       <?
       db_ancora('<b>Exame:</b>',"js_pesquisala36_i_setorexame(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
       db_input('la08_i_codigo',10,$Ila36_i_setorexame,true,'text',$db_opcao," onchange='js_pesquisala36_i_setorexame(false);'");
       db_input('la36_i_setorexame',10,'',true,'hidden',$db_opcao,'');
       db_input('la08_c_descr',40,@$Ila08_c_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
               <tr>
                 <td colspan="2" >
                    <fieldset><legend>Lançamento</legend>
                        
                        <table border="0" width="100%">
                            <tr>
                                <td rowspan="2" width="25%"> 
                                    <table border="0"> 
                                      <tr>
                                          <td nowrap title="<?=@$Tla36_i_tipo?>"><b>Tipo</b></td>
                                          <td>
                                           <?
					        $sql = $clmotivo_ausencia->sql_query(null,"s139_i_codigo, s139_c_descr","s139_i_codigo");
						//echo $sql;
                                                $resultado = $clmotivo_ausencia->sql_record($sql);
						if($resultado) {
						  db_selectrecord('la36_i_tipo',$resultado,true,$db_opcao,'','la36_i_tipo','','','',1);
            }
						else {
					    db_msgbox("Ocorreu um erro na busca dos motivos de ausencia!");
            }

						//$x = array('1'=>'Folga','2'=>'Férias');
                                                //db_select('sd06_i_tipo',$x,true,$db_opcao,"");
                                             ?>                                          
                                          </td>
                                      </tr>                                      
                                    </table>
                                </td>
                                <td width="75%"> 
                                    <table border="0">
                                       <tr>
                                            <td> 
                                                <fieldset><legend>Data</legend>
                                                    <table>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tla36_d_ini?>"><?=@$Lla36_d_ini?></td>
                                                            <td>
                                                               <?
                                                               if(isset($la36_d_ini)&&($la36_d_ini!="")){
                                                                 $vet=explode("/",$la36_d_ini);
                                                                 $la36_d_ini_dia=$vet[0];
                                                                 $la36_d_ini_mes=$vet[1];
                                                                 $la36_d_ini_ano=$vet[2];
                                                               }
                                                                  db_inputdata('la36_d_ini',@$la36_d_ini_dia,@$la36_d_ini_mes,@$la36_d_ini_ano,true,'text',$db_opcao,"");
                                                               ?>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tla36_d_fim?>"><?=@$Lla36_d_fim?></td>
                                                            <td>
                                                               <?
                                                               if(isset($la36_d_fim)&&($la36_d_fim!="")){
                                                                 $vet=explode("/",$la36_d_fim);
                                                                 $la36_d_fim_dia=$vet[0];
                                                                 $la36_d_fim_mes=$vet[1];
                                                                 $la36_d_fim_ano=$vet[2];
                                                               }
                                                                  db_inputdata('la36_d_fim',@$la36_d_fim_dia,@$la36_d_fim_mes,@$la36_d_fim_ano,true,'text',$db_opcao,"","","");
                                                               ?>
                                                            </td>
                                                         </tr>
                                                    </table>
                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset><legend>Horario</legend>
                                                <table>
                                                     <tr>
                                                         <td nowrap title="<?=@$Tla36_c_horaini?>"><?=@$Lla36_c_horaini?></td>
                                                         <td><?db_input('la36_c_horaini',5,@$Ila36_c_horaini,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'la36_c_horaini', event)\"");?></td>
                                                     </tr>
                                                     <tr>
                                                         <td nowrap title="<?=@$Tla36_c_horafim?>"><?=@$Lla36_c_horafim?></td>
                                                         <td><?db_input('la36_c_horafim',5,@$Ila36_c_horafim,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'la36_c_horafim', event)\"");?></td>
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
  </center>
<input
	name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
	type="submit" id="db_opcao"
	value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
	<?=($db_botao == false ? "disabled" : "")?>
  <?=($db_opcao != 3 ? " onclick=\"return js_valida();\" " : "")?>>

  <input name="cancela" type="button" id="cancela" value="Cancelar" onclick="js_cancela();" >
<table width="100%">
	<tr>
		<td valign="top"><br>
  <?
		$chavepri = array ("la36_i_codigo" => @$la36_i_codigo, 
                       "la08_c_descr"=>@$la08_c_descr, 
                       "la02_i_codigo"=>@$la02_i_codigo, 
                       "la02_c_descr"=>@$la02_c_descr, 
                       "la36_i_tipo" => @$la36_i_tipo, 
                       "la36_i_setorexame" => @$la36_i_setorexame, 
                       "la36_d_ini" => @$la36_d_ini, 
                       "la36_d_fim" => @$la36_d_fim, 
                       "la36_c_horaini" => @$la36_c_horaini, 
                       "la36_c_horafim" => @$la36_c_horafim,
                       "la08_i_codigo" => @$la08_i_codigo);
		$cliframe_alterar_excluir->chavepri = $chavepri;
		$cliframe_alterar_excluir->sql = $cllab_ausencia->sql_query_laboratorio ("", "la36_i_codigo,
                                                                                       la08_c_descr,
                                                                                       la08_i_codigo,
                                                                                       la36_i_tipo,
                                                                                       la36_i_setorexame,
                                                                                       la02_i_codigo,
                                                                                       la02_c_descr,
                                                                                       la36_i_tipo,
                                                                                       la08_c_descr,
                                                                                       la36_d_ini,
                                                                                       la36_d_fim,
                                                                                       la36_c_horaini,
                                                                                       la36_c_horafim", "la08_c_descr"," la02_i_codigo=$la02_i_codigo ");
		$cliframe_alterar_excluir->campos = "la36_i_codigo,la36_i_tipo,la36_i_setorexame,la08_c_descr,la36_d_ini, la36_d_fim,la36_c_horaini,la36_c_horafim";
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
</center>
</form>
<script>
if(document.form1.la36_i_setorexame.value==''){
	   document.form1.la36_i_setorexame.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}

function js_valida() {
      
      if(document.form1.la36_i_setorexame.value == '' || document.form1.la08_i_codigo.value == '') {

        alert('Preencha o exame.');
        return false;

      }

      if(!js_validadata()) {
        return false;
      }
    
      if(!js_validahora()) {
        return false;
      }

}

function js_validadata() {

  if(document.form1.la36_d_ini.value == "" || document.form1.la36_d_fim.value == "" ) {

    alert('As datas devem ser preenchidas.');
    return false;

  }

  aIni = document.form1.la36_d_ini.value.split('/');
  aFim = document.form1.la36_d_fim.value.split('/');
  dIni = new Date(aIni[2], aIni[1], aIni[0]);
  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	if(dFim < dIni) {
		
    alert("Data final nao pode ser menor que a data inicial.");
	  document.form1.la36_d_fim.value = '';
	  document.form1.la36_d_fim.focus();
    return false;

	}	

  return true;
				
}

function js_validahora() {
	
  if(document.form1.la36_c_horaini.value != '' || document.form1.la36_c_horafim.value != '') {
 
    if(document.form1.la36_c_horaini.value == '' || document.form1.la36_c_horafim.value == '') {

      alert('Os dois horários devem ser preenchidos ou deixados em branco.');
      return false;

    }

    if(document.form1.la36_c_horaini.value.length != 5 || document.form1.la36_c_horafim.value.length != 5) {
      
      alert('Preencha corretamente os horarios!');
      return false;
   
    }

    hr_ini  = (document.form1.la36_c_horaini.value.substring(0,2));
	 	mi_ini  = (document.form1.la36_c_horaini.value.substring(3,5));
		hr_fim  = (document.form1.la36_c_horafim.value.substring(0,2));
	 	mi_fim  = (document.form1.la36_c_horafim.value.substring(3,5));

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

  }

  return true;

}

function js_pesquisala36_i_setorexame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?la02_i_codigo=<?=$la02_i_codigo?>&funcao_js=parent.js_mostralab_setorexame1|la08_i_codigo|la08_c_descr|la09_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la08_i_codigo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?la02_i_codigo=<?=$la02_i_codigo?>&pesquisa_chave='+document.form1.la08_i_codigo.value+'&funcao_js=parent.js_mostralab_setorexame','Pesquisa',false);
     }else{
       document.form1.la08_c_descr.value = ''; 
       document.form1.la36_i_setorexame.value = '';
     }
  }
}
function js_mostralab_setorexame(chave,erro,chave2){
  document.form1.la08_c_descr.value = chave;
  document.form1.la36_i_setorexame.value = chave2;
  if(erro==true){ 
    document.form1.lao8_i_codigo.focus(); 
    document.form1.la36_i_setorexame.value = ''; 
    document.form1.la08_i_codigo.value = ''; 
  }
}
function js_mostralab_setorexame1(chave1,chave2,chave3){
  document.form1.la08_i_codigo.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  document.form1.la36_i_setorexame.value = chave3;
  db_iframe_lab_setorexame.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_ausencia','func_lab_ausencia.php?funcao_js=parent.js_preenchepesquisa|la36_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_ausencia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){
     location.href='lab1_lab_ausencia001.php?la02_i_codigo=<?=$la02_i_codigo?>&la02_c_descr=<?=$la02_c_descr?>';
}
</script>