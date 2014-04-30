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

//MODULO: Caixa
$clrecreparcori->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k71_recdest");
?>

<form name="form1" method="post" action="">
<fieldset style="margin-top:30px; width:520px">
<table border="0">

  <tr>
    <td> 
		<?
			db_input('k70_codigo',10,$Ik70_codigo,true,'hidden',$db_opcao,"")
		?>
    </td>
 	 </tr>
  
		<!-- Receita Origem -->
  	<tr>
    <td nowrap title="Receita de Origem">
       <?
          db_ancora("Receita de Origem:","js_pesquisak70_recori(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
				db_input('k70_recori',10,$Ik70_recori,true,'text',$db_opcao,"onchange='js_pesquisak70_recori(false)'");
				db_input('k02_descrori',40,$Ik02_descr,true,'text',3,'')
      ?>
    </td>
  	</tr>
	
  	<!-- Receita Destino -->
	  <tr>
    <td nowrap title="Receita de Destino">
       <?
       db_ancora("Receita de Destino:","js_pesquisak71_recdest(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
				db_input('k71_recdest',10,$Ik71_recdest,true,'text',$db_opcao,"onchange='js_pesquisak71_recdest(false);'");
				db_input('k02_descrdest',40,$Ik02_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
	
 	<!-- Parcela Inicial -->
  <tr>
    <td nowrap title="Parcela Inicial">
       <b> Parcela Inicial: </b>
    </td>
    <td> 
      <?
        db_input('k70_vezesini',10,$Ik70_vezesini,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  
	<!-- Parcela Final -->
	<tr>
    <td nowrap title="Parcela Final">
       <b> Parcela Final: </b>
    </td>
    <td> 
		<?
			db_input('k70_vezesfim',10,$Ik70_vezesfim,true,'text',$db_opcao,"onchange='js_valorParcela();'")
		?>
    </td>
  </tr>
	</table>

</fieldset>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
           type="submit" id="db_opcao" 
           value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
           <?=($db_botao==false?"disabled":"")?> >
           
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>


<script>

///////////// Scripts para Origem	///////////////
function js_pesquisak70_recori(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.k70_recori.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.k70_recori.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descrori.value = ''; 
     }
  }
}

function js_mostratabrec(chave,erro){
  document.form1.k02_descrori.value = chave; 
  if(erro==true){ 
    document.form1.k70_recori.focus(); 
    document.form1.k70_recori.value = ''; 
  }
}

function js_mostratabrec1(chave1,chave2){
  document.form1.k70_recori.value = chave1;
  document.form1.k02_descrori.value = chave2;
  db_iframe_tabrec.hide();
}

/////////// Scripts para Destino //////////
function js_pesquisak71_recdest(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1dest|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.k71_recdest.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.k71_recdest.value+'&funcao_js=parent.js_mostratabrecdest','Pesquisa',false);
     }else{
       document.form1.k02_descrdest.value = ''; 
     }
  }
}

function js_mostratabrecdest(chave,erro){
  document.form1.k02_descrdest.value = chave; 
  if(erro==true){ 
    document.form1.k71_recdest.focus(); 
    document.form1.k71_recdest.value = ''; 
  }
}

function js_mostratabrec1dest(chave1,chave2){
  document.form1.k71_recdest.value = chave1;
  document.form1.k02_descrdest.value = chave2;
  db_iframe_tabrec.hide();
}


// não deixa o usuário digitar uma parcela inicial maior que a final
function js_valorParcela() {
	
	var iParcIni = new Number(document.form1.k70_vezesini.value);
	var iParcFin = new Number(document.form1.k70_vezesfim.value);
	
	if ( iParcIni > iParcFin ) {
		
		document.form1.k70_vezesini.value = '';
		document.form1.k70_vezesfim.value = '';
		alert("Parcela inicial não pode ser maior que a final!")
		document.form1.k70_vezesini.focus();
		
	}
	
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_recreparcori',
                      'func_recreparcori.php?funcao_js=parent.js_preenchepesquisa|k70_codigo',
                      'Pesquisa',true,0,0);
}

function js_preenchepesquisa(chave){
  db_iframe_recreparcori.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

</script>