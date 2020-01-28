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
$clorcindica->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
  <fieldset>
    <legend>
      <b>Cadastro de Indicadores</b>
    </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$To10_indica?>">
	      <?=@$Lo10_indica?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_indica',10,$Io10_indica,true,'text',3,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_periodicidade?>">
	      <?
	        db_ancora($Lo10_periodicidade,"js_consultaPeriodicidade(true)",$db_opcao);
	      ?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_periodicidade',10,$Io10_periodicidade,true,'text',$db_opcao,"onChange='js_consultaPeriodicidade(false);'");
		    db_input('o09_descricao'	,35,"",true,'text',3,"");
		  ?>
	    </td>
	  </tr>	  
	  <tr>
	    <td nowrap title="<?=@$To10_descr?>">
	       <b>Denominação:</b>
	    </td>
	    <td> 
		  <?
		    db_input('o10_descr',50,$Io10_descr,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_descrunidade?>">
	       <?=@$Lo10_descrunidade?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_descrunidade',50,$Io10_descrunidade,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_valorunidade?>">
	       <?=@$Lo10_valorunidade?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_valorunidade',10,$Io10_valorunidade,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>	  	  	  
	  <tr>
	    <td nowrap title="<?=@$To10_obs?>">
	      <b>Índice de Referência:</b>
	    </td>
	    <td> 
		  <?
			db_textarea('o10_obs',0,47,$Io10_obs,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_valorindiceref?>">
	       <b>Índice de Referência ( Valor ):</b>
	    </td>
	    <td> 
		  <?
		    db_input('o10_valorindiceref',10,$Io10_valorindiceref,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_descrindicefinal?>">
	      <?=@$Lo10_descrindicefinal?>
	    </td>
	    <td> 
		  <?
			db_textarea('o10_descrindicefinal',0,47,$Io10_descrindicefinal,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$To10_valorindicefinal?>">
	      <?=@$Lo10_valorindicefinal?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_valorindicefinal',10,$Io10_valorindicefinal,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>	  	  		  	  	  	  	  	  
	  <tr>
	    <td nowrap title="<?=@$To10_fonte?>">
	      <?=@$Lo10_fonte?>
	    </td>
	    <td> 
		  <?
		    db_input('o10_fonte',50,$Io10_fonte,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>	
	  <tr>
	    <td nowrap title="<?=@$To10_basegeografica?>">
	      <?=@$Lo10_basegeografica?>
	    </td>
	    <td> 
		  <?
			db_textarea('o10_basegeografica',0,47,$Io10_basegeografica,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>	
	  <tr>
	    <td nowrap title="<?=@$To10_formulacalculo?>">
	      <?=@$Lo10_formulacalculo?>
	    </td>
	    <td> 
		  <?
			db_textarea('o10_formulacalculo',0,47,$Io10_formulacalculo,true,'text',$db_opcao,"")
		  ?>
	    </td>
	  </tr>	    
    </table>
  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_consultaPeriodicidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcindicaperiodicidade','func_orcindicaperiodicidade.php?funcao_js=parent.js_mostraorcindicaperiodicidade1|o09_sequencial|o09_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcindicaperiodicidade','func_orcindicaperiodicidade.php?pesquisa_chave='+document.form1.o10_periodicidade.value+'&funcao_js=parent.js_mostraorcindicaperiodicidade','Pesquisa',false);
  }
}
function js_mostraorcindicaperiodicidade(chave,erro){
  document.form1.o09_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o10_periodicidade.focus(); 
    document.form1.o10_periodicidade.value = ''; 
  }
}
function js_mostraorcindicaperiodicidade1(chave1,chave2){
  document.form1.o10_periodicidade.value = chave1;
  document.form1.o09_descricao.value     = chave2;
  db_iframe_orcindicaperiodicidade.hide();
}



function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_orcindica','func_orcindica.php?funcao_js=parent.js_preenchepesquisa|o10_indica','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcindica.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>