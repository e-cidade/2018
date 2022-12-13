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
$clconfigdbprefagua->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x43_descr");
$clrotulo->label("nomeinst");
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
			$w16_sequencial 	= "";
			$w16_aguacortesituacao = "";
			$x43_descr				= "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table width="730" align="center" style="margin-top: 15px;">
<tr align="center"><td>
	<fieldset>
		<legend><b>Configuração DBPref Módulo Água</b></legend>
		<table border="0" style="margin-top: 15px;">
		  <tr>
		    <td nowrap title="<?=@$Tw16_sequencial?>">
		       <?=@$Lw16_sequencial?>
		    </td>
		    <td> 
				<?
				db_input('w16_sequencial',10,$Iw16_sequencial,true,'text',3,"")
				?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw16_instit?>">
		       <?
		       echo @$Lw16_instit;
		       //db_ancora(@$Lw16_instit,"js_pesquisaw16_instit(true);",$db_opcao);
		       ?>
		    </td>
		    <td> 
					<?
					$w16_instit = db_getsession('DB_instit');
					db_input('w16_instit',10,$Iw16_instit,true,'text',3," onchange='js_pesquisaw16_instit(false);'")
					?>
				  <?
					db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw16_aguacortesituacao?>">
		       <?
		       	if($db_opcao == 2){
							$db_opcao1 = 3;
						}else{
							$db_opcao1 = $db_opcao;
						}
		      db_ancora(@$Lw16_aguacortesituacao,"js_pesquisaw16_aguacortesituacao(true);",$db_opcao1);
		       ?>
		    </td>
		    <td> 
				<?
				
				db_input('w16_aguacortesituacao',10,$Iw16_aguacortesituacao,true,'text',$db_opcao1," onchange='js_pesquisaw16_aguacortesituacao(false);'")
				?>
		    <?
				db_input('x43_descr',50,$Ix43_descr,true,'text',3,'')
		    ?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tw16_recibodbpref?>">
		       <?=@$Lw16_recibodbpref?>
		    </td>
		    <td>
		    <?
				    $aTipoDebito = array("1"=>"mostra os débitos e permite emitir recibo",
				    										 "2"=>"mostra os débitos, mas não permite emitir recibo",
				    										 "3"=>"não mostra os débitos");
				  	db_select("w16_recibodbpref",$aTipoDebito,true,$db_opcao,"");
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
</td></tr>
</table>
  <table>
  	  <tr>
	    <td valign="top"  align="center">  
	      <?
	                    
		 	$chavepri= array("w16_sequencial"=>@$w16_sequencial);
		 	$cliframe_alterar_excluir->chavepri=$chavepri;
		 	$cliframe_alterar_excluir->sql     = $clconfigdbprefagua->sql_query(null,'*',"w16_sequencial", "w16_instit=".$w16_instit);
		 	//$cliframe_alterar_excluir->sql     = $clconfigdbprefagua->sql_query();
		 	$cliframe_alterar_excluir->campos  = "w16_sequencial,w16_aguacortesituacao,x43_descr,w16_recibodbpref";
		 	$cliframe_alterar_excluir->legenda="SITUAÇÕES DO CORTE";
		 	$cliframe_alterar_excluir->iframe_height ="160";
		 	$cliframe_alterar_excluir->iframe_width ="700";
		 	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	      ?>
    	</td>
   	  </tr>
 	</table>
  </center>

<!-- input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" -->
</form>
<script>
function js_pesquisaw16_aguacortesituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aguacortesituacao','func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao1|x43_codsituacao|x43_descr','Pesquisa',true);
  }else{
     if(document.form1.w16_aguacortesituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aguacortesituacao','func_aguacortesituacao.php?pesquisa_chave='+document.form1.w16_aguacortesituacao.value+'&funcao_js=parent.js_mostraaguacortesituacao','Pesquisa',false);
     }else{
       document.form1.x43_descr.value = ''; 
     }
  }
}
function js_mostraaguacortesituacao(chave,erro){
  document.form1.x43_descr.value = chave; 
  if(erro==true){ 
    document.form1.w16_aguacortesituacao.focus(); 
    document.form1.w16_aguacortesituacao.value = ''; 
  }
}
function js_mostraaguacortesituacao1(chave1,chave2){
  document.form1.w16_aguacortesituacao.value = chave1;
  document.form1.x43_descr.value = chave2;
  db_iframe_aguacortesituacao.hide();
}
function js_pesquisaw16_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.w16_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.w16_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.w16_instit.focus(); 
    document.form1.w16_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.w16_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_configdbprefagua','func_configdbprefagua.php?funcao_js=parent.js_preenchepesquisa|w16_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_configdbprefagua.hide();
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
js_pesquisaw16_instit(false);
</script>