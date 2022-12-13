<?php
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

//MODULO: cadastro
$cllocalidaderural->rotulo->label();
?>
<center>

<form name="form1" method="post" action="">

	<center>
	
		<fieldset style="width:500px;">
			<legend><strong>Localidades Rurais</strong></legend>
	
			<table border="0">
			   
						<?php
							db_input('j137_sequencial',10,$Ij137_sequencial,true,'hidden',$db_opcao,"")
						?>
			  <tr>
			    <td nowrap title="<?php echo @$Tj137_descricao; ?>">
			       <?php echo @$Lj137_descricao; ?>
			    </td>
			    <td> 
						<?php
						  db_input('j137_descricao',60,$Ij137_descricao,true,'text',$db_opcao,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?php echo @$Tj137_valorminimo; ?>">
			       <?php echo  @$Lj137_valorminimo; ?>
			    </td>
			    <td> 
						<?php
							db_input('j137_valorminimo',10,$Ij137_valorminimo,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?php echo @$Tj137_valormaximo; ?>">
			       <?php echo @$Lj137_valormaximo;?>
			    </td>
			    <td> 
						<?php
						db_input('j137_valormaximo',10,$Ij137_valormaximo,true,'text',$db_opcao,"");
						?>
			    </td>
			  </tr>
		  </table>
		  
	  </fieldset>
	  
  </center>
	<input 
		name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
		type="submit" 
		id="db_opcao" 
		value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
		<?=($db_botao==false?"disabled":"")?> 
		<?php echo $db_opcao!=3 ? 'onClick="return js_processar();"':''; ?> 
	/>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_localidaderural','func_localidaderural.php?funcao_js=parent.js_preenchepesquisa|j137_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_localidaderural.hide();
  <?php
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}

function js_processar() {


	var nValorMinimo = new Number($F('j137_valorminimo')),
      nValorMaximo = new Number($F('j137_valormaximo'));

	if (nValorMinimo==0 || nValorMaximo==0) {
		return true;
	}

	if (nValorMaximo < nValorMinimo) {
		alert(_M('tributario.cadastro.db_frmlocalidaderural.valor_maximo_invalido'));
		return false;
	} 
	return true;
}
</script>