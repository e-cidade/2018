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
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s113_c_encaminhamento");

//Procedimento
$clrotulo->label("s125_i_procedimento");
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
//CBO
$clrotulo->label ( "rh70_sequencial" );
$clrotulo->label ( "rh70_estrutural" );
$clrotulo->label ( "rh70_descr" );
//sau_prontprograma
$clrotulo->label ( "s141_i_acaoprog" );


?>
<form name="form3" method="post" action="">
<center>
<fieldset><legend><b>Paciente</b></legend>
	<table border="0">
			 <?if($objSau_Config->s103_c_agendaprog=="S"){?>
			     <!-- Acão Programatica -->
			     <tr>
			        <td nowrap title="<?=@$Ts141_i_acaoprog?>">
			            <?=@$Ls141_i_acaoprog?>
			        </td>
			        <td>
			            <? $result_programa=pg_query("select fa12_i_codigo,fa12_c_descricao from far_programa");
			               db_selectrecord("fa12_i_codigo",$result_programa,@$Isd141_i_acaoprog,$db_opcao,"","fa12_c_descricao","","","",1);
			               echo"<script>
			                       document.form3.fa12_c_descricao.add(new Option(\"NENHUM\", \"0\"),  null);
			                    </script>";
			            ?>
			        </td>
			    </tr>
			  <?}?>
			  <!--  CGS / Nome -->
			  <tr>
			    <td nowrap title="<?=@$Ts115_c_cartaosus?>">
			       <?=@$Ls115_c_cartaosus?>
			    </td>
			    <td>
			      <?
			        db_input('s115_c_cartaosus',15,$Is115_c_cartaosus,true,'text',$db_opcao," onchange='js_pesquisas115_c_cartaosus(false);'  onFocus=\"nextfield='z01_i_cgsund'\" ")
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tz01_i_cgsund?>">
			       <?
			       db_ancora(@$Lz01_i_cgsund,"js_pesquisaz01_i_cgsund(true);",$db_opcao);
			       ?>
			    </td>
			    <td nowrap>
			      <?
			        db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisaz01_i_cgsund(false);' onFocus=\"nextfield='db_opcao'\" ")
			      ?>
			      <?
			        db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',$db_opcao," onchange='js_pesquisaz01_v_nome()'");
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Ts113_c_encaminhamento?>">
			       <?=@$Ls113_c_encaminhamento?>			       
			    </td>
			    <td>
			      <?
			        db_input('s113_c_encaminhamento',10,$Is113_c_encaminhamento,true,'text',$db_opcao,'');
					db_input ( 's125_i_procedimento', 10, $Is125_i_procedimento, true, 'hidden', $db_opcao, "" );
			       ?>
			    </td>
			  </tr>
	</table>
</fieldset>	
</center>
<p>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?>
       onFocus="nextfield='done'" 
>
<input name="fechar" type="submit" id="fechar" value="Fechar" onclick="js_fechar();">

</form>

<script type="text/javascript">

//Tempo estimado para fechar janela para não demorar no agendamento
//window.setInterval(js_fechar, 60000 );

function js_fechar(){
	parent.db_iframe_agendamento.hide();
	//window.setInterval(js_fechar, 0 );
}


//CGS
function js_pesquisaz01_i_cgsund(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und',"func_cgs_und.php?funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value",'Pesquisa',true);
  }else{
     if(document.form3.z01_i_cgsund.value != ''){ 
        //js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form3.z01_i_cgsund.value+'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs','Pesquisa',false);
        js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?chave_z01_i_cgsund='+document.form3.z01_i_cgsund.value+'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value','Pesquisa',false);
     }else{
       document.form3.z01_v_nome.value = '';
     }
  }
}

//Cartão Sus
function js_pesquisas115_c_cartaosus(mostra){
	var strParam = 'func_cgs_und.php';
	strParam += '?funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc';
	strParam += '&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value';
	strParam += '&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value';
	
	if(mostra==true){
		js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
	}else{
		if(document.form3.s115_c_cartaosus.value != ''){
			strParam += '&chave_s115_c_cartaosus='+document.form3.s115_c_cartaosus.value;
			js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und',strParam,'Pesquisa CGS',true);
		}else{
			document.form3.z01_v_nome.value = '';
		}
	}
}

//Nome
function js_pesquisaz01_v_nome(){
     if(document.form3.z01_v_nome.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?chave_z01_v_nome='+document.form3.z01_v_nome.value+'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs1|z01_i_cgsund|z01_v_nome|z01_d_nasc&retornacgs=p.p.IFdb_iframe_agendamento.document.form3.z01_i_cgsund.value&retornanome=p.p.IFdb_iframe_agendamento.document.form3.z01_v_nome.value','Pesquisa',true);
     }
}

function js_mostracgs(chave, erro){
  document.form3.z01_v_nome.value = chave;
  if(erro==true){ 
    document.form3.z01_i_cgsund.focus(); 
    document.form3.z01_v_nome.value = '';
  }
}
function js_mostracgs1(chave1,chave2,chave3){
	if( chave3 != ""  ){		
		document.form3.z01_i_cgsund.value = chave1;
		document.form3.z01_v_nome.value = chave2;
  		if( (nextfield != 'done' || document.form3.s115_c_cartaosus.value != '' ) && confirm( "Confirma paciente: \n\n "+chave1+" "+chave2 ) ){
  			document.form3.incluir.click();
  		}
		parent.db_iframe_cgs_und.hide();
	}else{
		alert("Paciente sem Data de Nascimento, por favor atualize o Cadastro");    
  	}
}

</script>