<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

if(!isset($sql)){
    global $sql;
   $sql="";
}

if(isset($pesquisar)){

 global $sql;
  $sql=$cl_agendamentos_ext->sql_query_ext2("","
                                                case s114_i_situacao
                                                   when 1 then 'Cancelado'
                                                   when 2 then 'Faltou'
                                                   when 3 then 'Outros'
                                                end as dl_Situacao,
                                                sd23_d_consulta as dl_Agendado,
                                                sd23_c_hora as dl_Hora,
                                                case when s102_i_prontuario is null then
                                                    'Agendado'
                                                else
                                                    'Atendimento'
                                                end as sd97_c_tipo,
                                                       sd23_i_ficha,
                                                        z01_nome as dl_Médico,
                                                       rh70_descr as dl_Especialidade,
                                                      sd101_c_descr as dl_Ficha,
                                                       s114_d_data as dl_Data,
                                                       s114_v_motivo as dl_Motivo","","");

  $primeiro=false;
  $sql .= " where ";
  if($z01_i_cgsund!=""){

     $sql .= "z01_i_numcgs=".$z01_i_cgsund;
     $primeiro=true;
    }
    $d1=$d2="";
    if($sd23_d_consulta!="" && $sd23_d_consulta2!=""){

       if($primeiro==true){

          $sql .= " and ";
        }
        $d1=$sd23_d_consulta;
        $rest = "";
        $rest = substr($sd23_d_consulta, 6);
        $rest .="-";
        $rest .= substr($sd23_d_consulta, 3, 2);
        $rest .="-";
        $rest .= substr($sd23_d_consulta, 0, 2);
        $sql .= "sd23_d_consulta  BETWEEN '".$rest."' and";

        $d2=$sd23_d_consulta2;
        $rest = "";
        $rest = substr($sd23_d_consulta2, 6);
        $rest .="-";
        $rest .= substr($sd23_d_consulta2, 3, 2);
        $rest .="-";
        $rest .= substr($sd23_d_consulta2, 0, 2);
        $sql .= " '".$rest."'";
        $primeiro=true;
     }else{

        if(($sd23_d_consulta!="" && $sd23_d_consulta2=="")||($sd23_d_consulta=="" && $sd23_d_consulta2!="")){

           db_msgbox("Preencha no os dois campos de Data!");
           $sql="";
         }
      }
}

?>

<form name="form1" method="post">
	<table>
		<tr>
		 	<td>
		   	<fieldset><legend>Relatório de Agendamento por Período</legend>
    				<table>
		    			<tr>
				      		<td valign="top" colspan="3">
						      	<fieldset>
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
						          				<? db_input('z01_nome',49,$Iz01_nome,true,'text',3,''); ?>
						          			</td>
				            				</tr>
			                     					<!-- CBO -->
				             				<tr>
						              			<td nowrap title="<?=@$Tsd04_i_cbo?>">
				              						<? db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true,1);",$db_opcao); ?>
					          				</td>
						          			<td>
							            			<?
								            			db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'hidden',$db_opcao,"");
								            			db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
								            			db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false,1);' onFocus=\"nextfield='sd23_d_consulta'\"");
							            			?>
						          			</td>
						          			<td colspan="2">
					             					<? db_input('rh70_descr',49,$Irh70_descr,true,'text',3,''); ?>
						          			</td>
                        </tr>
                        <tr>
                           <td><b>Situação</b></td>
                           <td>
                               <?
                                 $situacao= array('0'=>'Todos','1'=>'Cancelado','2'=>'Faltou','3'=>'Outros');
                                 db_select('s114_i_situacao',$situacao,true,$db_opcao,"");
                               ?>
                           </td>
                        </tr>
          							<tr>
  				          		   <td><b>Período</b></td>
                           <td><? db_inputdata("sd23_d_consulta","","","",true,'text',"","","","","none","","","") ?></td>
                           <td><? db_inputdata("sd23_d_consulta2","","","",true,'text',"","","","","none","","","") ?></td>
                      	</tr>
					          		</table>
						    	  </fieldset>
						      </td>
					    </tr>
					    <tr>
						      <td align="center"><br>
							    <input type="button" name="relatorioagenda" value="Emitir Relatório" onclick="js_relatorioagenda()" ><br>
						  </td>
					  	<td align="center">
						    	<input name="gerar_faa" type="hidden" value="<?=@$gerarfaa ?>" >
							    <br><input type="button" name="limpar" value="limpar" onclick="location.href='sau2_agendamentoperiodo001.php?gerarfaa=<?=@$gerarfaa ?>' "><br>
				  		</td>
	            </tr>
          </table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script>

function js_relatorioagenda(){

  oObj     = document.form1;
  sParam   = 'sau2_agendamentoperiodo002.php';
  sParam  += '?sd27_i_codigo='+oObj.sd27_i_codigo.value;
  sParam  += '&s114_i_situacao='+oObj.s114_i_situacao.value;
  sParam  += '&sd03_i_codigo='+oObj.sd03_i_codigo.value;
  sParam  += '&z01_nome='+oObj.z01_nome.value;
  sParam  += '&sDatai='+oObj.sd23_d_consulta.value;
  sParam  += '&sDataf='+oObj.sd23_d_consulta2.value;
  oJan     = window.open(sParam,'', 'width = '+(screen.availWidth-5)+', height = '+(screen.availHeight-40)+', scrollbars = 1, location = 0 ');
	oJan.moveTo(0,0);

}

  if( frameagendados.document.gerafaa != undefined ){
	  var obj       = frameagendados.document.gerafaa;
		var codigo    = "";
		var separador = "";

		for (i = 0; i < obj.length; i++) {

      if(obj.elements[i].type == 'checkbox') {

    	  if( obj.elements[i].checked && parseInt(obj.elements[i].value) > 0) {
    				codigo   += separador + obj.elements[i].value;
    				separador = ",";
    		}

      }

    }

    	if( codigo == "" ) {
    		alert("Nenhum agendamento marcado.");
    	} else {

    		query += '&codigos='+codigo;
			  var WindowObjectReference;
			  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
			  WindowObjectReference = window.open(query,"CNN_WindowName", strWindowFeatures);

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

	  iframe = document.getElementById('framecalendario');
  	  iframe.src = x;


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
			js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
		}else{
			js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',true);
		}
	}else{
		if(document.form1.sd03_i_codigo.value != ''){
			if( depara == 2 ){
				js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_2&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
			}else{
				js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos_1&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value,'Pesquisa',false);
			}
		}else{
			document.form1.z01_nome.value = '';
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