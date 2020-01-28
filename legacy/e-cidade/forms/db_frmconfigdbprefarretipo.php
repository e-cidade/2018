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

//MODULO: prefeitura
$clconfigdbprefarretipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("w17_dtini");
$clrotulo->label("w17_dtfim");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || $db_opcao == 1 || (isset($incluir) && $sqlerro==false ) ){
			$w17_sequencial 	= "";
			$w17_arretipo 		= "";
			$k00_descr				= "";
			$w17_dtini_dia		= "";
			$w17_dtini_mes		= "";
			$w17_dtini_ano		= "";
			$w17_dtfim_dia		=	"";
			$w17_dtfim_mes		=	"";
			$w17_dtfim_ano		=	"";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
	<table width="730" align="center" style="margin-top: 15px;">
	<tr>
	<td>
		<fieldset>
			<legend><b>Configuração DBPref Débitos Emissão Recibos</b></legend>
		
		<table border="0" align="center">
		  <tr>
		    <td nowrap title="<?=@$Tw17_sequencial?>">
		       <?=@$Lw17_sequencial?>
		    </td>
		    <td> 
					<?
					db_input('w17_sequencial',10,$Iw17_sequencial,true,'text',3,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw17_arretipo?>">
		       <?
		       db_ancora(@$Lw17_arretipo,"js_pesquisaw17_arretipo(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					db_input('w17_arretipo',10,$Iw17_arretipo,true,'text',$db_opcao," onchange='js_pesquisaw17_arretipo(false);'")
					?>
					<?
					db_input('k00_descr',50,$Ik00_descr,true,'text',3,'')
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw17_instit?>">
		       <?
		       echo $Lw17_instit;
		       //db_ancora(@$Lw17_instit,"js_pesquisaw17_instit(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					$w17_instit = db_getsession('DB_instit');
					db_input('w17_instit',10,$Iw17_instit,true,'text',3," onchange='js_pesquisaw17_instit(false);'")
					?>
		       <?
					db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
		       ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw17_dtini?>">
		       <?=@$Lw17_dtini?>
		    </td>
		    <td> 
					<?
					db_inputdata('w17_dtini',@$w17_dtini_dia,@$w17_dtini_mes,@$w17_dtini_ano,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw17_dtfim?>">
		       <?=@$Lw17_dtfim?>
		    </td>
		    <td> 
					<?
					db_inputdata('w17_dtfim',@$w17_dtfim_dia,@$w17_dtfim_mes,@$w17_dtfim_ano,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
		  <tr align="center">
		  	<td colspan="2">
		  	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		  	<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
		  	</td>
		  </tr>
		  </table>
		  </fieldset>
	</td>
	</tr>
	</table>
	<table>
  	  <tr>
	    <td valign="top"  align="center">  
	      <?
	                    
		 	$chavepri= array("w17_sequencial"=>@$w17_sequencial);
		 	$cliframe_alterar_excluir->chavepri=$chavepri;
		 	$cliframe_alterar_excluir->sql     = $clconfigdbprefarretipo->sql_query(null,'*',"w17_sequencial", "w17_instit=".$w17_instit);
		 	//$cliframe_alterar_excluir->sql     = $clconfigdbprefagua->sql_query();
		 	$cliframe_alterar_excluir->campos  = "w17_sequencial,w17_arretipo,k00_descr,w17_dtini,w17_dtfim";
		 	$cliframe_alterar_excluir->legenda="Recibos Liberados e Período";
		 	$cliframe_alterar_excluir->iframe_height ="160";
		 	$cliframe_alterar_excluir->iframe_width ="700";
		 	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	      ?>
    	</td>
   	  </tr>
 	</table>	
  </center>
</form>
<script>
function js_pesquisaw17_arretipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.w17_arretipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.w17_arretipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.w17_arretipo.focus(); 
    document.form1.w17_arretipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.w17_arretipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisaw17_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.w17_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.w17_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.w17_instit.focus(); 
    document.form1.w17_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.w17_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_configdbprefarretipo','func_configdbprefarretipo.php?funcao_js=parent.js_preenchepesquisa|w17_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_configdbprefarretipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancelar(){
	  var opcao = document.createElement("input");
	  opcao.setAttribute("type","hidden");
	  opcao.setAttribute("name","novo");
	  opcao.setAttribute("value","true");
	  document.form1.appendChild(opcao);
	  document.form1.submit();
	}
js_pesquisaw17_instit(false);
</script>