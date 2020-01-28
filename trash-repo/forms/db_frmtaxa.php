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

//MODULO: arrecadacao
$cltaxa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ar37_descricao");
$clrotulo->label("k02_descr");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="float: left; margin-left: 150px; width: 750px;">
<legend><b>Cadastro de Taxas</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tar36_sequencial?>">
       <?=@$Lar36_sequencial?>
    </td>
    <td> 
			<?
			   db_input('ar36_sequencial',10,$Iar36_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tar36_descricao?>">
       <?=@$Lar36_descricao?>
    </td>
    <td> 
      <? db_input('ar36_descricao',70,$Iar36_descricao,true,'text',$db_opcao,"") ?>
    </td>
  </tr>  
  
  <tr>
    <td nowrap title="<?=@$Tar36_grupotaxa?>">
       <?
       db_ancora(@$Lar36_grupotaxa,"js_pesquisaar36_grupotaxa(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				  db_input('ar36_grupotaxa',10,$Iar36_grupotaxa,true,'text',$db_opcao," onchange='js_pesquisaar36_grupotaxa(false);'")
				?>
       <?
          db_input('ar37_descricao',56,$Iar37_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tar36_receita?>">
       <?
       db_ancora(@$Lar36_receita,"js_pesquisaar36_receita(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
				  db_input('ar36_receita',10,$Iar36_receita,true,'text',$db_opcao," onchange='js_pesquisaar36_receita(false);'")
				?>
       <?
          db_input('k02_descr',56,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>  

  
  <tr>
    <td>
      <b>Tipo de Cobrança</b>
    </td>
    <td>
    <!-- 
      <select id='tipo_cobranca' onchange="js_tipoCobranca(this.value);">
        <option value="1">Valor Fixado</option>
        <option value="2">Percentual de Débito</option>
      </select>
    -->  
        <?   $aTipoCobranca = array('1'=>'Valor Fixado','2'=>'Percentual de Débito');
             db_select('tipo_cobranca',$aTipoCobranca,true,$db_opcao,"onchange='js_tipoCobranca(this.value);'");
        ?>      
    </td>    
  </tr> 
  
  <tr>
	  <td colspan="2" nowrap >
		  <div id ='cntValor' >
			  <table border ='0' width = '100%'>
				  <tr>
				    <td width="16%" nowrap title="<?=@$Tar36_valor?>">
				       <?=@$Lar36_valor?>
				    </td>
				    <td> 
							<? db_input('ar36_valor',10,$Iar36_valor,true,'text',$db_opcao,"") ?>
				    </td>
				  </tr>
			  </table> 
			</div>   
	  </td>
  </tr>
  
  <tr>
    <td colspan="2" nowrap >
      <div id ='cntPerc' style="display: none;">  
        <table border ='0' width = '100%'>
				  <tr>
				    <td width="16%" nowrap title="<?=@$Tar36_perc?>">
				       <?=@$Lar36_perc?>
				    </td>
				    <td> 
							<? db_input('ar36_perc',10,$Iar36_perc,true,'text',$db_opcao,"") ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Tar36_valormin?>">
				       <?=@$Lar36_valormin?>
				    </td>
				    <td> 
							<? db_input('ar36_valormin',10,$Iar36_valormin,true,'text',$db_opcao,"") ?>
				    </td>
				  </tr>
				  <tr>
				    <td nowrap title="<?=@$Tar36_valormax?>">
				       <?=@$Lar36_valormax?>
				    </td>
				    <td> 
							<?  db_input('ar36_valormax',10,$Iar36_valormax,true,'text',$db_opcao,"") ?>
				    </td>
				  </tr>
	      </table> 
      </div>   
    </td>
  </tr>  
  </table>
</fieldset>  
  
</center>
<div style="margin-top: 10px;">  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica(tipo_cobranca.value);" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</div>
</form>
<script>

function js_verifica(iTipo){
  
  var iValor = document.getElementById('ar36_valor').value    ;
  var iPerc  = document.getElementById('ar36_perc').value     ;
  var iMin   = document.getElementById('ar36_valormin').value ;
  var iMax   = document.getElementById('ar36_valormax').value ;
  var sMsg   = 'Preencha os valores relacionados ao tipo de cobrança escolhido.';
  
  if(iTipo == 1 && (iValor == '' || iValor == null ) ){
     alert(sMsg);
     return false;
  }
  
  if (iTipo == 2 && (iPerc == "" || iMin == "" || iMax == "" || iPerc == null || iMin == null || iMax == null )) {
  
    alert(sMsg);
    return false;
  }

}

function js_tipoCobranca(iTipo){
  
  if (iTipo == 1) {
    
    document.getElementById('cntPerc').style.display  = 'none';
    document.getElementById('cntValor').style.display = 'inline';
    document.getElementById('ar36_perc').value        = '';
    document.getElementById('ar36_valormin').value    = '';
    document.getElementById('ar36_valormax').value    = '';
    
  } else if (iTipo == 2) {
    
    document.getElementById('cntPerc').style.display  = 'inline';
    document.getElementById('cntValor').style.display = 'none';
    document.getElementById('ar36_valor').value       = '';
  }

}

function js_pesquisaar36_grupotaxa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_grupotaxa','func_grupotaxa.php?funcao_js=parent.js_mostragrupotaxa1|ar37_sequencial|ar37_descricao','Pesquisa',true);
  }else{
     if(document.form1.ar36_grupotaxa.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_grupotaxa','func_grupotaxa.php?pesquisa_chave='+document.form1.ar36_grupotaxa.value+'&funcao_js=parent.js_mostragrupotaxa','Pesquisa',false);
     }else{
       document.form1.ar37_descricao.value = ''; 
     }
  }
}
function js_mostragrupotaxa(chave,erro){
  document.form1.ar37_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ar36_grupotaxa.focus(); 
    document.form1.ar36_grupotaxa.value = ''; 
  }
}
function js_mostragrupotaxa1(chave1,chave2){
  document.form1.ar36_grupotaxa.value = chave1;
  document.form1.ar37_descricao.value = chave2;
  db_iframe_grupotaxa.hide();
}

function js_pesquisaar36_receita(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe_receita','func_tabrec.php?funcao_js=parent.js_mostrareceita1|k02_codigo|k02_descr','Pesquisa',true);
	  }else{
	     if(document.form1.ar36_receita.value != ''){ 
	        js_OpenJanelaIframe('','db_iframe_receita','func_tabrec.php?pesquisa_chave='+document.form1.ar36_receita.value+'&funcao_js=parent.js_mostrareceita','Pesquisa',false);
	     }else{
	       document.form1.k02_descr.value = ''; 
	     }
	  }
	}
	function js_mostrareceita(chave,erro){
	  document.form1.k02_descr.value = chave; 
	  if(erro==true){ 
	    document.form1.ar36_receita.focus(); 
	    document.form1.ar36_receita.value = ''; 
	  }
	}
	function js_mostrareceita1(chave1,chave2){
	  document.form1.ar36_receita.value = chave1;
	  document.form1.k02_descr.value = chave2;
	  db_iframe_receita.hide();
	}


function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_taxa','func_taxa.php?funcao_js=parent.js_preenchepesquisa|ar36_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_taxa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>