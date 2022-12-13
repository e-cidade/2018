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

//MODULO: Cemiterio
$clsepultamentoisencao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("z01_nome");
$clrotulo->label("cm34_descricao");
?>
<form name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend>
        <b>Cadastro de Isenções</b>
      </legend>
			<table border="0">
			  <tr>
			    <td nowrap title="<?=@$Tcm33_sequencial?>">
			      <?=@$Lcm33_sequencial?>
			    </td>
			    <td> 
						<?
					    db_input('cm33_sequencial',10,$Icm33_sequencial,true,'text',3,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tcm33_processo?>">
			      <?
			        db_ancora(@$Lcm33_processo,"js_pesquisacm33_processo(true);",$db_opcao);
			      ?>
			    </td>
			    <td> 
						<?
			  			db_input('cm33_processo',10,$Icm33_processo,true,'text',$db_opcao," onchange='js_pesquisacm33_processo(false);'");
			        db_input('z01_nome_prot',40,'',true,'text',3,'');
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tcm33_sepultamento?>">
			      <?
			        db_ancora(@$Lcm33_sepultamento,"js_pesquisacm33_sepultamento(true);",$db_opcao);
			      ?>
			    </td>
			    <td> 
						<?
			  			db_input('cm33_sepultamento',10,$Icm33_sepultamento,true,'text',$db_opcao," onchange='js_pesquisacm33_sepultamento(false);'");
			        db_input('z01_nome',40,'',true,'text',3,'');
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tcm33_isencao?>">
			      <?
			        db_ancora(@$Lcm33_isencao,"js_pesquisacm33_isencao(true);",$db_opcao);
			      ?>
			    </td>
			    <td> 
						<?
			  			db_input('cm33_isencao',10,$Icm33_isencao,true,'text',$db_opcao," onchange='js_pesquisacm33_isencao(false);'");
			        db_input('cm34_descricao',40,$Icm34_descricao,true,'text',3,'');
			        db_input('cm34_tipo',10,'',true,'hidden',3,'');
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tcm33_datalanc?>">
			      <?=@$Lcm33_datalanc?>
			    </td>
			    <td> 
						<?
						  db_inputdata('cm33_datalanc',@$cm33_datalanc_dia,@$cm33_datalanc_mes,@$cm33_datalanc_ano,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
			  <tr class="validaIsento">
			    <td nowrap title="<?=@$Tcm33_datainicio?>">
			      <?=@$Lcm33_datainicio?>
			    </td>
			    <td> 
						<?
			  			db_inputdata('cm33_datainicio',@$cm33_datainicio_dia,@$cm33_datainicio_mes,@$cm33_datainicio_ano,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
			  <tr class="validaIsento">
			    <td nowrap title="<?=@$Tcm33_datafim?>">
			      <?=@$Lcm33_datafim?>
			    </td>
			    <td> 
						<?
			 		    db_inputdata('cm33_datafim',@$cm33_datafim_dia,@$cm33_datafim_mes,@$cm33_datafim_ano,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
			  <tr class="validaIsento">
			    <td nowrap title="<?=@$Tcm33_percentual?>">
			      <?=@$Lcm33_percentual?>
			    </td>
			    <td> 
						<?
						  db_input('cm33_percentual',10,$Icm33_percentual,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tcm33_obs?>">
			      <?=@$Lcm33_obs?>
			    </td>
			    <td> 
						<?
						  db_textarea('cm33_obs',3,50,$Icm33_obs,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
      </table>
    </fieldset>  
  </center>
	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onClick="return js_validaPost();">
	<? if ( $db_opcao != 1 ) { ?>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
	<? } ?>
	</form>
<script>
function js_pesquisacm33_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm33_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.cm33_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.z01_nome_prot.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.z01_nome_prot.value = chave; 
  if(erro==true){ 
    document.form1.cm33_processo.focus(); 
    document.form1.cm33_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.cm33_processo.value = chave1;
  document.form1.z01_nome_prot.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisacm33_sepultamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm33_sepultamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?pesquisa_chave='+document.form1.cm33_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrasepultamentos(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.cm33_sepultamento.focus(); 
    document.form1.cm33_sepultamento.value = ''; 
  }
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.cm33_sepultamento.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_sepultamentos.hide();
}
function js_pesquisacm33_isencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cemiterioisencao','func_cemiterioisencao.php?funcao_js=parent.js_mostracemiterioisencao1|cm34_sequencial|cm34_descricao|cm34_tipo','Pesquisa',true);
  }else{
     if(document.form1.cm33_isencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cemiterioisencao','func_cemiterioisencao.php?pesquisa_chave='+document.form1.cm33_isencao.value+'&funcao_js=parent.js_mostracemiterioisencao','Pesquisa',false);
     }else{
       document.form1.cm34_descricao.value = ''; 
     }
  }
}
function js_mostracemiterioisencao(chave,iTipo,erro){
  js_validaTela(iTipo);
  document.form1.cm34_descricao.value = chave;
  if(erro==true){ 
    document.form1.cm33_isencao.focus(); 
    document.form1.cm33_isencao.value = ''; 
  }
}
function js_mostracemiterioisencao1(chave1,chave2,iTipo){
  js_validaTela(iTipo);
  document.form1.cm33_isencao.value   = chave1;
  document.form1.cm34_descricao.value = chave2;
  db_iframe_cemiterioisencao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentoisencao','func_sepultamentoisencao.php?funcao_js=parent.js_preenchepesquisa|cm33_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sepultamentoisencao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_validaTela(iTipo){
  
  $('cm34_tipo').value = iTipo;
  
  aElem = $$('.validaIsento');
  
  if ( iTipo == 1 ) {
    aElem.each(
      function (eElem) {
        eElem.style.display = 'none';      
      }
    ) 
  } else {
    aElem.each(
      function (eElem) {
        eElem.style.display = '';      
      }
    )   
  }
  
}


function js_validaPost(){

  if ( $('cm34_tipo').value == 1 ) {
	  $('cm33_datainicio').value     = '';
	  $('cm33_datainicio_dia').value = '';
	  $('cm33_datainicio_mes').value = '';
	  $('cm33_datainicio_ano').value = '';
	  $('cm33_datafim').value        = '';
	  $('cm33_datafim_dia').value    = '';
	  $('cm33_datafim_mes').value    = '';
	  $('cm33_datafim_ano').value    = '';
	  $('cm33_percentual').value     = '';
  }
    
  return true;
    
}

</script>


<?
  if ( isset($cm34_tipo) && trim($cm34_tipo) != '' ){
  	echo "<script>js_validaTela({$cm34_tipo})</script>";
  }
?>