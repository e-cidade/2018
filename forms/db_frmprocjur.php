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


$clprocjur->rotulo->label();
$clprocjuradm->rotulo->label();
$clprocjurtipo->rotulo->label();
$clprocjurjudicial->rotulo->label();
$clprocjurjudicialadvog->rotulo->label();

?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastro de Processos</legend>
    <table class="form-container">
	    <tr>
	      <td nowrap title="<?=@$Tv62_usuario?>">
	        <?=@$Lv62_usuario?>
	      </td>
	      <td> 
  		    <?
    			  db_input('v62_usuario',10,$Iv62_usuario,true,'text',3,"");
    			  db_input('nome',40,"",true,'text',3,"");
		      ?>
	      </td>
	    </tr>    
	    <tr>
	      <td nowrap title="<?=@$Tv62_procjurtipo?>">
	        <?
	          db_ancora(@$Lv62_procjurtipo,"js_pesquisav62_procjurtipo(true);",$db_opcao);
	        ?>
	      </td>
	      <td> 
  		    <?
  		      db_input('v62_sequencial',10,$Iv62_sequencial,true,'hidden',$db_opcao,"");
    			  db_input('v62_procjurtipo',10,$Iv62_procjurtipo,true,'text',$db_opcao," onchange='js_pesquisav62_procjurtipo(false);'");
    			  db_input('v66_descr',40,$Iv66_descr,true,'text',3,'');
    			  db_input('v66_procjurtiporegra',10,"",true,'hidden',3,'');
          ?>
	      </td>
	    </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv62_descricao?>">
	        <?=@$Lv62_descricao?>
	      </td>
	      <td> 
  		    <?
  			    db_input('v62_descricao',54,$Iv62_descricao,true,'text',$db_opcao,"");
  		    ?>
	      </td>
	    </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv62_dataini?>">
	        <?=@$Lv62_dataini?>
	      </td>
	      <td> 
  		    <?
  		      db_inputdata('v62_dataini',@$v62_dataini_dia,@$v62_dataini_mes,@$v62_dataini_ano,true,'text',$db_opcao,"");
  		    ?>
	      </td>
	    </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv62_datafim?>">
	        <?=@$Lv62_datafim?>
	      </td>
	      <td> 
  		    <?
  			  db_inputdata('v62_datafim',@$v62_datafim_dia,@$v62_datafim_mes,@$v62_datafim_ano,true,'text',$db_opcao,"");
  		    ?>
	      </td>
	    </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv62_situacao?>">
	        <?=@$Lv62_situacao?>
	      </td>
	      <td> 
  		    <?
  		  	  $x = array('1'=>'Ativa','2'=>'Finalizada');
  			    db_select('v62_situacao',$x,true,$db_opcao,"style='width:110px;'");
  		    ?>
	      </td>
	    </tr>
	  </table>
	  <table id='tableJudicial' class="form-container" style='display:none'>
	    <tr>
	      <td nowrap title="<?=@$Tv63_processoforo?>">
	        <?=@$Lv63_processoforo?>
	      </td>
	      <td> 
		      <?
			      db_input('v63_processoforo',10,$Iv63_processoforo,true,'text',$db_opcao,"");
	        ?>
	      </td>
	    </tr>
	    <tr>
	      <td nowrap title="<?=@$Tv63_localiza?>">
	        <?
	          db_ancora(@$Lv63_localiza,"js_pesquisav63_localiza(true);",$db_opcao);
	        ?>
	      </td>
	      <td> 
  		    <?
    			  db_input('v63_localiza',10,$Iv63_localiza,true,'text',$db_opcao," onchange='js_pesquisav63_localiza(false);'");
    			  db_input('v54_descr',40,"",true,'text',3,'');
	        ?>
	      </td>
	    </tr>		  
	    <tr>
	      <td nowrap title="<?=@$Tv63_vara?>">
	        <?
	          db_ancora(@$Lv63_vara,"js_pesquisav63_vara(true);",$db_opcao);
	        ?>
	      </td>
	      <td> 
  		    <?
    			  db_input('v63_vara',10,$Iv63_vara,true,'text',$db_opcao," onchange='js_pesquisav63_vara(false);'");
    			  db_input('v53_descr',40,"",true,'text',3,'');
	        ?>
	      </td>
	    </tr>		    		    
	  </table>
	  <table id='tableAdministrativo' class="form-container" style='display:none'>		  
	    <tr>
	      <td nowrap title="<?=@$Tv64_protprocesso?>">
	        <?
	          db_ancora(@$Lv64_protprocesso,"js_pesquisav64_protprocesso();",$db_opcao);
	        ?>
	      </td>
	      <td> 
  		    <?
  			    db_input('v64_protprocesso',10,$Iv64_protprocesso,true,'text',$db_opcao,"");
	        ?>
	      </td>
	    </tr>		    		    
	  </table>		  
	  <fieldset class="separator">
  	  <legend><?=@$Lv62_obs?></legend>
  	  <table class="form-container">		    
  	    <tr>
  	      <td nowrap title="<?=@$Tv62_obs?>" colspan="2">
  		    <?
  		  	  db_textarea('v62_obs',5,51,$Iv62_obs,true,'text',$db_opcao,"");
  		    ?>
  	      </td>
  	    </tr>
  	  </table>
	  </fieldset>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <input name="novo"		type="button" id="novo"		 value="Novo"	   onclick="js_novo();" <?=($db_botao==false?"disabled":"")?> style="<?=(($db_opcao==2||$db_opcao==22) && db_permissaomenu(db_getsession('DB_anousu'),db_getsession('DB_modulo'),7078)?"display:''":"display:none")?>">
</form>
<script>

function js_pesquisav62_procjurtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procjurtipo','func_procjurtipo.php?funcao_js=parent.js_mostraprocjurtipo1|v66_sequencial|v66_procjurtiporegra|v66_descr','Pesquisa',true);
  }else{
     if(document.form1.v62_procjurtipo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_procjurtipo','func_procjurtipo.php?pesquisa_chave='+document.form1.v62_procjurtipo.value+'&funcao_js=parent.js_mostraprocjurtipo','Pesquisa',false);
     }else{
       document.form1.v66_procjurtiporegra.value = ''; 
       document.form1.v66_descr.value 			 = '';
     }
  }
}

function js_mostraprocjurtipo(lErro,sDescr,iTipoRegra){

  if(lErro==true){ 
    document.form1.v62_procjurtipo.focus(); 
  	document.form1.v66_descr.value	     	  = sDescr;    
    document.form1.v62_procjurtipo.value 	  = ''; 
    document.form1.v66_procjurtiporegra.value = '';
  } else {
  	document.form1.v66_descr.value			  = sDescr;
  	document.form1.v66_procjurtiporegra.value = iTipoRegra; 
  }
  
  js_alteraForm(document.form1.v66_procjurtiporegra.value);
  
}

function js_mostraprocjurtipo1(iSeq,iTipoRegra,sDescr){

  document.form1.v62_procjurtipo.value	    = iSeq;
  document.form1.v66_procjurtiporegra.value = iTipoRegra;
  document.form1.v66_descr.value 			= sDescr;
  
  js_alteraForm(iTipoRegra);
  
  db_iframe_procjurtipo.hide();
  
}

function js_pesquisav63_localiza(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_localiza','func_localiza.php?funcao_js=parent.js_mostralocaliza1|v54_codlocal|v54_descr','Pesquisa',true);
  }else{
     if(document.form1.v63_localiza.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_localiza','func_localiza.php?pesquisa_chave='+document.form1.v63_localiza.value+'&funcao_js=parent.js_mostralocaliza','Pesquisa',false);
     }else{
       document.form1.v54_descr.value = ''; 
     }
  }
}

function js_mostralocaliza(sDescr,lErro){

  if(lErro==true){ 
    document.form1.v63_localiza.focus(); 
  	document.form1.v54_descr.value	  = sDescr;
    document.form1.v63_localiza.value = ''; 
  } else {
    document.form1.v54_descr.value	  = sDescr;
  }
  
}

function js_mostralocaliza1(iSeq,sDescr){

  document.form1.v63_localiza.value	= iSeq;
  document.form1.v54_descr.value 	= sDescr;
  db_iframe_localiza.hide();
  
}

function js_pesquisav63_vara(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vara','func_vara.php?funcao_js=parent.js_mostravara1|v53_codvara|v53_descr','Pesquisa',true);
  }else{
     if(document.form1.v63_vara.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_vara','func_vara.php?pesquisa_chave='+document.form1.v63_vara.value+'&funcao_js=parent.js_mostravara','Pesquisa',false);
     }else{
       document.form1.v53_descr.value = ''; 
     }
  }
  
}

function js_mostravara(sDescr,lErro){

  if(lErro==true){ 
    document.form1.v63_vara.focus(); 
  	document.form1.v53_descr.value	  = sDescr;
    document.form1.v63_vara.value 	  = ''; 
  } else {
    document.form1.v53_descr.value	  = sDescr;
  }
  
}

function js_mostravara1(iSeq,sDescr){

  document.form1.v63_vara.value	= iSeq;
  document.form1.v53_descr.value 	= sDescr;
  db_iframe_vara.hide();
  
}


function js_pesquisav64_protprocesso(){
  js_OpenJanelaIframe('','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc','Pesquisa',true);
}


function js_mostraprotprocesso1(iSeq){

  document.form1.v64_protprocesso.value	= iSeq;
  db_iframe_protprocesso.hide();
  
}





function js_alteraForm(iTipoRegra){

  if ( iTipoRegra == '') {
	document.getElementById('tableJudicial').style.display	     = 'none';  
	document.getElementById('tableAdministrativo').style.display = 'none';
  } else if (iTipoRegra == '1') {
	document.getElementById('tableJudicial').style.display	     = '';  
	document.getElementById('tableAdministrativo').style.display = 'none';
  } else if (iTipoRegra == '2') {
	document.getElementById('tableJudicial').style.display	     = 'none';  
	document.getElementById('tableAdministrativo').style.display = '';
  }	

}
 
 
js_alteraForm(document.form1.v66_procjurtiporegra.value);


function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_procjur','func_procjur.php?funcao_js=parent.js_preenchepesquisa|v62_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_procjur.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_novo(){
  location.href = 'arr1_procjur011.php'; 
}

</script>

<script>


$("v62_usuario").addClassName("field-size2");
$("nome").addClassName("field-size7");
$("v62_procjurtipo").addClassName("field-size2");
$("v66_descr").addClassName("field-size7");
$("v62_descricao").addClassName("field-size9");
$("v62_dataini").addClassName("field-size2");
$("v62_datafim").addClassName("field-size2");
$("v63_processoforo").addClassName("field-size2");
$("v63_localiza").addClassName("field-size2");
$("v54_descr").addClassName("field-size7");
$("v63_vara").addClassName("field-size2");
$("v53_descr").addClassName("field-size7");
$("v64_protprocesso").style.width = "335px";

</script>