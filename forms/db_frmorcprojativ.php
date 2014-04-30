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

//MODULO: orcamento
$clorcprojativ->rotulo->label();
$clorcprojativunidaderesp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");

?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<legend>
  <b>Cadastro de Ações</b>
</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To55_anousu?>">
       <?=@$Lo55_anousu?>
    </td>
    <td> 
	<?
	  $o55_anousu = db_getsession('DB_anousu');
	  db_input('o55_anousu',4,$Io55_anousu,true,'text',3,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_tipo?>">
       <b>Classificação:</b>
    </td>
    <td> 
	<?
	if($db_opcao==1){
	    $db_opcao02 = 1;
	}else{
	    $db_opcao02 = 3;
	}
	if(empty($o55_tipo)){
	  $o55_tipo=2;
	}
	$x = array('2'=>'Atividade',
			   '1'=>'Projetos',
			   '3'=>'Operações especiais');
	
	  db_select('o55_tipo',$x,true,$db_opcao02,"onchange='js_trocacod(this.value);'");
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_projativ?>">
       <b>Código:</b>
    </td>
    <td>
    <!--
    <input name="digito" type="text" size="1" readonly="true" value="<?=$o55_tipo?>" style="background-color:#DEB887;">&nbsp; 
	-->
	<?

	 if ( isset($o55_tipo) && $o55_tipo == 1  ) {
	   $aInicioCod = array( 1000=>"1",
	   						3000=>"3",
	   						5000=>"5",
	   						7000=>"7");
	 } else if ( isset($o55_tipo) && $o55_tipo == 2 ) {
	   $aInicioCod = array( 2000=>"2",
	   						4000=>"4",
	   						6000=>"6",
	   						8000=>"8");
	 } else if ( isset($o55_tipo) && $o55_tipo == 3 ) {
	   $aInicioCod = array( 0=>"0");
	 }
	 
	 db_select("digito",$aInicioCod,true,$db_opcao02,"");
	
//	if(isset($calcu) && $calcu!='' && $db_opcao==1){
//	  $str=true;  
//	  if($o55_tipo==1){
//	    $ini=1000;
//	    $fim =1999 ; 
//	  }else if($o55_tipo==2){
//	    $ini=2000;
//	    $fim =2999 ; 
//	  }else if($o55_tipo==3){
//	    $ini=3000;
//	    $fim =3999 ; 
//	  }  
//	  $result=$clorcprojativ->sql_record($clorcprojativ->sql_query_file("",'',"(max(o55_projativ)-1000)+1 as o55_projativ",'',"o55_anousu=".db_getsession("DB_anousu")." and o55_projativ between $ini and $fim"));
//	  db_fieldsmemory($result,0);
//	  if($o55_projativ>$fim){
//	    $msg_erro='Não há código disponível. Verifique!';
//	    $o55_projativ='0';
//	  }else if($o55_projativ==""){
//	    $o55_projativ=$ini;
//	  }
//	}  

	if ( $db_opcao02==1 || $db_opcao02==11 ) {
		
	?>
	<input title="Projetos / Atividades do orçamento Campo:o55_projativ" name="o55_projativ"  type="text" id="o55_projativ" value="" size="3" 
 	  maxlength="3" onblur="js_ValidaMaiusculo(this,'f',event);" 
      onKeyUp="js_ValidaCampos(this,0,'Projetos / Atividades','f','f',event);"
      onKeyDown="return js_controla_tecla_enter(this,event);"
      autocomplete='off'>
	<?
	} else {
	?>
	<input title="Projetos / Atividades do orçamento Campo:o55_projativ" name="o55_projativ_rd"  type="text" id="o55_projativ_rd" value="<? echo substr($o55_projativ,1,3); ?>" size="3" 
    maxlength="3" readonly style="background-color:#DEB887;" autocomplete='off'>
	<?
	  db_input('o55_projativ',3,$Io55_projativ,true,'hidden',$db_opcao02,"");
	}
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_descr?>">
       <b>Título:</b>
    </td>
    <td> 
	<?
	  db_input('o55_descr',55,$Io55_descr,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_finali?>">
       <?=@$Lo55_finali?>
    </td>
    <td> 
	<?
	  db_textarea('o55_finali',0,52,$Io55_finali,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_orcproduto?>">
       <?
         db_ancora($Lo55_orcproduto,"js_pesquisaProduto(true)",$db_opcao);
       ?>
    </td>
    <td> 
	<?
	  db_input('o55_orcproduto',10,$Io55_orcproduto,true,'text',$db_opcao,"onChange='js_pesquisaProduto(false);'");
	  db_input('o22_descrprod',40,"",true,'text',3);
	?>
    </td>
  </tr>  
  <tr>
    <td>
       <?
         db_ancora($Lo13_unidaderesp,"js_pesquisaUnidadeResp(true)",$db_opcao);
       ?>
    </td>
    <td> 
	<?
	  db_input('o13_sequencial' ,10,"",true,'hidden',3);
	  db_input('o13_unidaderesp',10,$Io13_unidaderesp,true,'text',$db_opcao,"onChange='js_pesquisaUnidadeResp(false);'");
	  db_input('o20_descricao'  ,40,"",true,'text',3);
	?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To55_descrunidade?>">
       <?=@$Lo55_descrunidade?>
    </td>
    <td> 
	<?
	  db_textarea('o55_descrunidade',0,52,$Io55_descrunidade,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To55_valorunidade?>">
       <?=$Lo55_valorunidade?>
    </td>
    <td> 
	<?
	  db_input('o55_valorunidade',10,$Io55_valorunidade,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To55_especproduto?>">
       <?=@$Lo55_especproduto?>
    </td>
    <td> 
	<?
	  db_textarea('o55_especproduto',0,52,$Io55_especproduto,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To55_tipoacao?>">
       <?=@$Lo55_tipoacao?>
    </td>
    <td> 
	<?
	  $aTipoAcao = array('1'=>'Orçamentária',
			     		 '2'=>'Não-Orçamentária');
	
	  db_select('o55_tipoacao',$aTipoAcao,true,$db_opcao,"style='width:300px;'");
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_formaimplementacao?>">
       <?=@$Lo55_formaimplementacao?>
    </td>
    <td> 
	<?
	  $aFormaImplementacao = array('1'=>'Direta',
			     		 		   '2'=>'Descentralizada',
								   '3'=>'Transferência Obrigatória',
								   '4'=>'Transferência Voluntária',
	  							   '5'=>'Transferência em Linha de Crédito');
	
	  db_select('o55_formaimplementacao',$aFormaImplementacao,true,$db_opcao,"style='width:300px;'");
	?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$To55_detalhamentoimp?>">
       <?=@$Lo55_detalhamentoimp?>
    </td>
    <td> 
	<?
	  db_textarea('o55_detalhamentoimp',0,52,$Io55_detalhamentoimp,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_origemacao?>">
       <?=@$Lo55_origemacao?>
    </td>
    <td> 
	<?
	  db_textarea('o55_origemacao',0,52,$Io55_origemacao,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To55_baselegal?>">
       <?=@$Lo55_baselegal?>
    </td>
    <td> 
	<?
	  db_textarea('o55_baselegal',0,52,$Io55_baselegal,true,'text',$db_opcao,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap >
    </td>
    <td> 
	<?
	global $o55_instit;
	$o55_instit = db_getsession('DB_instit');
	db_input('o55_instit',2,$Io55_instit,true,'hidden',3," onchange='js_pesquisao55_instit(false);'")
	?>
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if(empty($novo)){?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}else{?>
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcprojativ.hide();">
<?}?>
</form>
<script>

function js_trocacod(tipo){
	      obj=document.createElement('input');
	      obj.setAttribute('name','calcu');
	      obj.setAttribute('type','hidden');
      	      obj.setAttribute('value','ok');
	      document.form1.appendChild(obj);
  document.form1.submit();
}

function js_pesquisao55_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.o55_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.o55_instit.focus(); 
    document.form1.o55_instit.value = ''; 
    }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.o55_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}


function js_pesquisaProduto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcproduto','func_orcproduto.php?funcao_js=parent.js_mostraorcproduto1|o22_codproduto|o22_descrprod','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcproduto','func_orcproduto.php?pesquisa_chave='+document.form1.o55_orcproduto.value+'&funcao_js=parent.js_mostraorcproduto','Pesquisa',false);
  }
}
function js_mostraorcproduto(chave,erro){
  document.form1.o22_descrprod.value = chave; 
  if(erro==true){ 
    document.form1.o55_orcproduto.focus(); 
    document.form1.o55_orcproduto.value = ''; 
    }
}
function js_mostraorcproduto1(chave1,chave2){
  document.form1.o55_orcproduto.value = chave1;
  document.form1.o22_descrprod.value = chave2;
  db_iframe_orcproduto.hide();
}


function js_pesquisaUnidadeResp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidaderesp','func_unidaderesp.php?funcao_js=parent.js_mostraunidaderesp1|o20_sequencial|o20_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_unidaderesp','func_unidaderesp.php?pesquisa_chave='+document.form1.o13_unidaderesp.value+'&funcao_js=parent.js_mostraunidaderesp','Pesquisa',false);
  }
}
function js_mostraunidaderesp(chave,erro){
  document.form1.o20_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o13_unidaderesp.focus(); 
    document.form1.o13_unidaderesp.value = ''; 
    }
}
function js_mostraunidaderesp1(chave1,chave2){
  document.form1.o13_unidaderesp.value = chave1;
  document.form1.o20_descricao.value = chave2;
  db_iframe_unidaderesp.hide();
}



function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_preenchepesquisa|o55_anousu|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){

//alert(chave1);

  db_iframe_orcprojativ.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>
<?
if(isset($msg_erro)){
  db_msgbox($msg_erro);
}
?>