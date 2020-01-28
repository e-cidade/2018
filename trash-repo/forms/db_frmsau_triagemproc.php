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

$clrotulo->label("z01_nome");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("sd63_c_nome");

//Triagem
$clrotulo->label("nome");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_i_cgsund");

$clrotulo->label("sd24_i_codigo");
$clrotulo->label("sd24_i_unidade");
$clrotulo->label("sd24_v_motivo");
$clrotulo->label("sd24_v_pressao");
$clrotulo->label("sd24_f_peso");
$clrotulo->label("sd24_f_temperatura");
$clrotulo->label("sd24_i_profissional");
$clrotulo->label("sd24_c_digitada");

//Cid
$clrotulo->label ( "sd70_i_codigo" );
$clrotulo->label ( "sd70_c_cid" );
$clrotulo->label ( "sd70_c_nome" );

$descrdepto = db_getsession("DB_nomedepto");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
   <td >

		<fieldset><legend><b>Procedimento</b></legend>

		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tsd24_i_codigo?>">
		       <?=@$Lsd24_i_codigo?>
		    </td>
		    <td colspan=3>
		     <?
		     db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',3,"");
			 db_input('z01_v_nome',68,$Iz01_v_nome,true,'text',3);
			 db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'hidden',3,'');		     
		     ?>
		    </td>
		  </tr>
		  <!-- UPS -->
		  <tr>
			    <td nowrap title="<?=@$Tsd24_i_unidade?>">
			       <?
			         db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",3);
			       ?>
			    </td>
			    <td colspan=3>
			     <?
			     db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'text',3," onchange=alert('aquiii')");
                             @db_input('descrdepto',68,$Idescrdepto,true,'text',3,"");
			     db_input('sd24_c_digitada',10,$Isd24_c_digitada,true,'hidden',3);
			     ?>
			     
			    </td>
		  </tr>
		  <!-- PROFISSIONAL -->
		  <tr>
		    <td nowrap title="<?=@$Tsd03_i_codigo?>">
		       <?
		       db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",$db_opcao);
		       ?>
		    </td>
		    <td valing="top" align="top" colspan="3">
		       <? db_input('sd29_i_codigo',10,$Isd29_i_codigo,true,'hidden',3,"");
		          db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false);'")
		       ?>
		       <?
		          db_input('z01_nome',68,$Iz01_nome,true,'text',3,'')
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
		          db_input('rh70_descr',68,$Irh70_descr,true,'text',3,'');
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
					db_input('sd29_i_procedimento',10,$Isd29_i_procedimento,true,'hidden',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'"); 
					db_input('sd63_c_procedimento',10,$Isd63_c_procedimento,true,'text',$db_opcao," onchange='js_pesquisasd29_i_procedimento(false);'");
					?>
				</td>
				<td valign="top">
					<?
					db_input('sd63_c_nome',68,$Isd63_c_nome,true,'text',3,'')
					?>       
				</td>
			</tr>
			<!-- CID -->
			<tr>
				<td nowrap title="<?=@$Tsd70_c_cid?>" valign="top" align="top">
					<?
					db_ancora(@$Lsd70_c_cid,"js_pesquisasd70_c_cid(true); \" onFocus='js_foco(this, \"sd24_t_diagnostico\");' ",$db_opcao);
					?>
				</td>
				<td valign="top" align="top" colspan=3>
					<?
					db_input('sd70_i_codigo',10,$Isd70_i_codigo,true,'hidden',$db_opcao);
					db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);' onFocus='js_foco(this, \"sd29_d_data\");' onblur='js_validacid(this);'");
					db_input('sd70_c_nome',68,$Isd70_c_nome,true,'text',3,"tabIndex='0' ");
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
			       <?=@$Lsd29_c_hora?>
     			   <?db_input('sd29_c_hora',4,$Isd29_c_hora,true,'text',$db_opcao,"OnKeyUp=mascara_hora(this.value,'sd29_c_hora')")?>
			    </td>
			  </tr>

		  <tr>
		    <td valign="top" nowrap title="<?=@$Tsd29_t_tratamento?>">
		       <b>Executado:</b>
		    </td>
		    <td colspan="3"> 
		      <?
		         $sd29_t_tratamento=!isset($sd29_t_tratamento)?' ':$sd29_t_tratamento;
		         db_textarea('sd29_t_tratamento',2,79,@$sd29_t_tratamento,true,'text',$db_opcao,"");
		      ?>
		    </td>
		  </tr>		  
		</table>
		</fieldset>
		<center>
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		<input name="cancelar" type="button" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> onclick="location.href='sau4_triagemproc001.php?chavepesquisaprontuario=<?=$sd24_i_codigo?>'">
		<input name="emitir" 
				type="button"
				id="emitirfaa" 
				value="Emitir FAA" 
				onClick="js_emitirFaa();"
		>
		<input name="fatoresderisco" type="button" value="Fatores de Risco" <?=($db_botao1==true?"disabled":"")?> onclick="js_fatoresderisco()">
    <?
    $oSauConfig = loadConfig('sau_config');
    selectModelosFaa($oSauConfig->s103_i_modelofaa);
    ?>
	 </td> 
  </tr>
</table>
		
</form>

<script>

/**
 * seta documento para atualizar o enter
 * set url do arquivo RPC
 */
strURL         = 'sau1_sau_individualprocedRPC.php';
booValidaCID   = false;

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno ){ 
	var objAjax = new Ajax.Request(
                         strURL, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                          				js_divCarregando( strCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
}

if( $F('sd63_c_procedimento') != '' ){
	var objParam                 = new Object();
	objParam.exec                = "getProcedimento";
	objParam.rh70_sequencial     = $F('rh70_sequencial');
	objParam.sd63_c_procedimento = $F('sd63_c_procedimento');
	objParam.rh70_descr          = $F('rh70_descr');

	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );

}

//document.getElementById('novafaa').value= 'Nova FAA';
//document.getElementById('novafaa').onclick = function(){ js_novaficha() };

/**
 *Botão Consultar
 */
function js_pesquisaprontuarios(){	
  js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios002.php?funcao_js=parent.js_preenchepesquisapront|sd24_i_codigo|z01_v_nome|sd24_i_numcgs','Pesquisa',true);
}
function js_preenchepesquisapront(chave1,chave2,chave3){
 db_iframe_prontuarios.hide();
 location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave1;
}

/**
 *Botão Prontuário Médico
 */
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

/**
 * Prosseguir Consulta Médica
 */
function js_prosseguir(aba){
  if(document.form1.sd24_i_codigo.value==""){
	 alert("FAA não informada!");	
  }else if( aba == 'a2' ){
     <?
	   if( $clprontproced->numrows > 0){
		  echo "parent.document.formaba.a2.disabled = false;";
		  echo "parent.iframe_a2.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=$chavepesquisaprontuario&chaveprofissional='+document.form1.sd03_i_codigo.value;";  
		  echo "parent.mo_camada('a2');";
	   }else{
	   	?>alert('FAA sem procedimentos.');<?
	   }   
     ?>
  }else if( aba == 'a4' ){
     <?
	   if( $clprontproced->numrows > 0){
		  echo "parent.document.formaba.a4.disabled = false;";
		  echo "parent.iframe_a4.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=$chavepesquisaprontuario&chaveprofissional='+document.form1.sd03_i_codigo.value;";  
		  echo "parent.mo_camada('a4');";
	   }else{
	   	?>alert('FAA sem procedimentos.');<?
	   }   
     ?>
  }
}
 


function js_triagem(){
	  iTop = ( screen.availHeight-800 ) / 2;
	  iLeft = ( screen.availWidth-800 ) / 2;
	  x  = 'sau4_consultamedica007.php';
	  x += '?sd24_v_motivo=<?=@$sd24_v_motivo?>';
	  x += '&sd24_v_pressao=<?=@$sd24_v_pressao?>';
	  x += '&sd24_f_peso=<?=@$sd24_f_peso?>';
	  x += '&sd24_f_temperatura=<?=@$sd24_f_temperatura?>';
	  x += '&profissional_triagem=<?=@$profissional_triagem?>';
	  x += '&cbo_triagem=<?=@$cbo_triagem?>';
	  
	  js_OpenJanelaIframe('','db_iframe_triagem',x,'Triagem',true, '40', iLeft, 800, 320);
}

function js_fatoresderisco(){
	  iTop = ( screen.availHeight-800 ) / 2;
	  iLeft = ( screen.availWidth-800 ) / 2;
	  x  = 'sau4_consultamedica006.php';
	  x += '?chavepesquisacgs='+parent.iframe_a1.document.form1.s152_i_cgsund.value;
	  js_OpenJanelaIframe('','db_iframe_fatoresderisco',x,'Fator de Risco',true, '40', iLeft, 800, 320);
}


function js_novaficha(){
     parent.document.formaba.a3.disabled = true;
     parent.document.formaba.a2.disabled = true;
     parent.iframe_a1.location.href='sau4_fichaatendabas001.php';
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
	var strParam = 'func_sau_proccbo.php';
	strParam += '?chave_rh70_sequencial='+document.form1.rh70_sequencial.value;
	strParam += '&intUnidade='+$F('sd24_i_unidade');
	strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
	
	$('sd70_i_codigo').value = '';
	$('sd70_c_cid').value = '';
	$('sd70_c_nome').value = '';
	
	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento Geral',true);
	}else{
		//if(document.form1.sd29_i_procedimento.value != ''){
		//	strParam += '&chave_sd96_i_procedimento='+document.form1.sd29_i_procedimento.value 
		//	js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento',true);
		//} else 
		if(document.form1.sd63_c_procedimento.value != ''){
			strParam += '&chave_sd63_c_procedimento='+document.form1.sd63_c_procedimento.value;
			js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimento Nome',true);
		}else{     
        	document.form1.sd63_c_nome.value = ''; 
		}
	}
}

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
  //document.form1.sd29_i_procedimento.value = chave1;
  //document.form1.sd63_c_procedimento.value = chave2;
  //document.form1.sd63_c_nome.value = chave3;
	var objParam                 = new Object();
	objParam.exec                = "getProcedimento";
	objParam.rh70_sequencial     = $F('rh70_sequencial');
	objParam.sd63_c_procedimento = chave2;
	objParam.rh70_descr          = $F('rh70_descr');
	objParam.sd24_i_unidade      = $F('sd24_i_unidade');

	js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );
	
	db_iframe_sau_proccbo.hide();
}

function js_pesquisasd03_i_codigo(mostra){
	var strParam = 'func_medicos.php';
	strParam += '?chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value;
	if(mostra==true){
		strParam += '&funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome';
		js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa Profissional',true);
	}else{
		if(document.form1.sd03_i_codigo.value != ''){
			strParam += '&pesquisa_chave='+document.form1.sd03_i_codigo.value;
			strParam += '&funcao_js=parent.js_mostramedicos'; 
			js_OpenJanelaIframe('','db_iframe_medicos',strParam,'Pesquisa Profissional',false);
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


function js_emitirFaa() {

  if ($F('sd24_i_codigo') != '') {
     
    sUrl  = js_getArquivoFaa($F('s103_i_modelofaa'));
    sUrl += '?chave_sd29_i_prontuario='+$F('sd24_i_codigo');
    oJan  = window.open(sUrl, '',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                        ',scrollbars=1,location=0 '
                       );
    oJan.moveTo(0, 0);

  } else {

    alert('Nenhuma FAA para gerar.');

  }

}

function js_getArquivoFaa(iCodModelo) {

  oSel = $('sArquivoFaa');
  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (iCodModelo == oSel.options[iCont].value) {
      return oSel.options[iCont].text;
    }

  }

}

/**
 * Retorno Pesquisa Procedimento
 */
function js_retornoProcedimento( objAjax ){
  var objRetorno = eval("("+objAjax.responseText+")");

  if (objRetorno.status == 1) {
    if (objRetorno.itens.length > 0) {
       objRetorno.itens.each(function (objProcedimento, iIteracao) {
     			//Prenche Procedimento
				$('sd29_i_procedimento').value = objProcedimento.db_sd96_i_procedimento;
				$('sd63_c_procedimento').value = objProcedimento.sd63_c_procedimento.urlDecode();
				$('sd63_c_nome').value         = objProcedimento.sd63_c_nome.urlDecode();
			});
			booValidaCID = objRetorno.itens[0].intcid > 0;
			$('sd70_c_cid').focus();
 		}
	} else {
    	alert(objRetorno.message.urlDecode());
		$('sd63_c_procedimento').focus();			
		$('sd63_c_procedimento').select();			
	}
} 


/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
	if(mostra==true){
		var strParam = ( booValidaCID == true )?'func_sau_proccid2.php':'func_sau_cid.php';
		strParam += '?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome';
		strParam += '&chave_sd72_i_procedimento='+$F('sd29_i_procedimento');
		strParam += '&campoFoco=sd70_c_cid';
		js_OpenJanelaIframe('','db_iframe_sau_cid',strParam,'Pesquisa CID',true);
	}else{
		if(document.form1.sd70_c_cid.value != ''){
			var objParam            = new Object();
			objParam.exec           = "getCID";
			objParam.sd70_c_cid     = $F('sd70_c_cid');
			objParam.sd29_i_procedimento = $F('sd29_i_procedimento');
			objParam.booValidaCID   = booValidaCID; 
	
			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoCID' );
		}else{
			$('sd70_i_codigo').value = '';
			$('sd70_c_cid').value    = '';
			$('sd70_c_nome').value   = '';
		}
	}
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
	$('sd70_i_codigo').value = chave1;
	$('sd70_c_cid').value    = chave2;
	$('sd70_c_nome').value   = chave3;
	
	db_iframe_sau_cid.hide();
		
}

/**
 * retorno CID
 */
function js_retornoCID( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
  	var objForm    = document.form1;

	$('sd70_i_codigo').value = '';
	$('sd70_c_cid').value    = '';
	$('sd70_c_nome').value   = '';
	  	  	
  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objCID, iIteracao) {
     			$('sd70_i_codigo').value = objCID.sd70_i_codigo;
     			$('sd70_c_cid').value    = objCID.sd70_c_cid.urlDecode();
     			$('sd70_c_nome').value   = objCID.sd70_c_nome.urlDecode();
         	});
     	}
  	} else {
    	alert(objRetorno.message.urlDecode());
    	$('sd70_c_cid').focus();
  	}

}

/**
 * valida obrigatoriedade do cid
 */
function js_validacid( objCID ){
	if( booValidaCID == true && objCID.value == '' ){
		alert('CID obrigatório.');
		$('sd70_c_cid').focus();
	}
} 


</script>