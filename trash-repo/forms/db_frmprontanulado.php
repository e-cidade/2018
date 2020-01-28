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
$clprontanulado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("z01_v_nome");
?>

<form name="form1" method="post" action="">
<center>
<fieldset><legend><b>FAA</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd57_i_codigo?>">
       <?=@$Lsd57_i_codigo?>
    </td>
    <td> 
		<?
		db_input('sd57_i_codigo',10,$Isd57_i_codigo,true,'text',3,"")
		?>
    </td>
  </tr>
  <!-- Prontuario -->
  <tr>
    <td nowrap title="<?=@$Tsd57_i_prontuario?>">
       <?
       db_ancora(@$Lsd57_i_prontuario,"js_pesquisasd57_i_prontuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
		<?
		db_input('sd57_i_prontuario',10,$Isd57_i_prontuario,true,'text',$db_opcao," onchange='js_pesquisasd57_i_prontuario(false);'");
		
		db_input('z01_v_nome',47,$Iz01_v_nome,true,'text',3);
		?>
		
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tsd57_d_data?>">
       <?=@$Lsd57_d_data?>
    </td>
    <td> 
		<?
		db_inputdata('sd57_d_data',@$sd57_d_data_dia,@$sd57_d_data_mes,@$sd57_d_data_ano,true,'text',3,"")
		?>
		<?=@$Lsd57_c_hora?>
		<?
		db_input('sd57_c_hora',8,$Isd57_c_hora,true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd57_i_login?>">
       <?
       db_ancora(@$Lsd57_i_login,"js_pesquisasd57_i_login(true);",3);
       ?>
    </td>
    <td> 
		<?
		db_input('sd57_i_login',10,$Isd57_i_login,true,'text',3," onchange='js_pesquisasd57_i_login(false);'")
		?>
       <?
		db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td valign="top" nowrap title="<?=@$Tsd57_t_obs?>">
       <?=@$Lsd57_t_obs?>
    </td>
    <td> 
		<?
		db_textarea('sd57_t_obs',2,57,$Isd57_t_obs,true,'text',$db_opcao,"")
		?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
		type="submit" 
		id="db_opcao" 
		value="<?=($db_opcao==1?"Anular FAA":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
		<?=($db_botao==true?"disabled":"")?> 
>
</form>
<script>


//Prontuario
function js_pesquisasd57_i_prontuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_codigo|z01_v_nome','Pesquisa',true);
  }else{
     if(document.form1.sd57_i_prontuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd57_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
     }else{
       document.form1.sd57_i_prontuario.value = ''; 
     }
  }
}
function js_mostraprontuarios(chave,erro){ 
  document.form1.z01_v_nome.value = chave; 
  if(erro==true){
  	document.form1.reset();
	document.form1.z01_v_nome.value = chave; 
	document.form1.sd57_i_prontuario.focus(); 
    document.form1.sd57_i_prontuario.value = ''; 
  }else{
    js_anulado( document.form1.sd57_i_prontuario.value,document.form1.z01_v_nome.value );
  }
}
function js_mostraprontuarios1(chave1,chave2){
	obj = document.form1;
	obj.sd57_i_prontuario.value = chave1;
	obj.z01_v_nome.value = chave2;
	db_iframe_prontuarios.hide();
	js_anulado( obj.sd57_i_prontuario.value,obj.z01_v_nome.value );
}

//Verifica se ja esta anulado
function js_anulado( sd57_i_prontuario, z01_v_nome ){
	document.form1.reset();
	document.form1.sd57_i_prontuario.value = sd57_i_prontuario;
	document.form1.z01_v_nome.value = z01_v_nome;
	document.getElementById("db_opcao").disabled=false;
	document.getElementById("db_opcao").name = "incluir";
	document.getElementById("db_opcao").value = "Anular FAA";
	
	if(sd57_i_prontuario != ''){
		js_OpenJanelaIframe('','db_iframe_prontuarios','func_prontanulado.php?chave_sd57_i_prontuario='+sd57_i_prontuario+'&funcao_js=parent.js_anulado2|sd57_i_codigo|sd57_d_data|sd57_c_hora|sd57_i_login|login|sd57_t_obs','Pesquisa',false);
	}
}
function js_anulado2( sd57_i_codigo, sd57_d_data, sd57_c_hora, sd57_i_login, login, sd57_t_obs ){
	obj = document.form1;
	obj.sd57_i_codigo.value = sd57_i_codigo;
	obj.sd57_d_data.value   = sd57_d_data.substr(8,2)+'/'+sd57_d_data.substr(5,2)+'/'+sd57_d_data.substr(0,4);
	obj.sd57_c_hora.value   = sd57_c_hora
	obj.sd57_i_login.value  = sd57_i_login;
	obj.login               = login
	obj.sd57_t_obs.value    = sd57_t_obs;
	document.getElementById("db_opcao").name = "excluir";
	document.getElementById("db_opcao").value = "Cancela Anulação";
}




function js_pesquisasd57_i_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd57_i_login.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd57_i_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd57_i_login.focus(); 
    document.form1.sd57_i_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd57_i_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_prontanulado','func_prontanulado.php?funcao_js=parent.js_preenchepesquisa|sd57_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontanulado.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_fechar(){
	parent.db_iframe_prontanulado.hide()
}
</script>