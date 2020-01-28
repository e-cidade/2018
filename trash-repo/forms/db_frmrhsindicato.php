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

//MODULO: pessoal
$clrhsindicato->rotulo->label();
?>
<form name="form1" method="post" action="">

<fieldset style="width:400px;">

	<legend><strong>Sindicato</strong></legend>

	<table border="0">
		<tr style="display:none;">
			<td nowrap title="<?php echo $Trh116_sequencial; ?>">
				 <?php echo $Lrh116_sequencial; ?>
			</td>
			<td> 
				<?php db_input('rh116_sequencial',10,$Irh116_sequencial,true,'text',$db_opcao,"") ?>
			</td>
		</tr>
		 
		<tr>
			<td nowrap title="<?php echo $Trh116_codigo; ?>">
				 <?php echo $Lrh116_codigo; ?>
			</td>
			<td> 
				<?php db_input('rh116_codigo',40,$Irh116_codigo,true,'text',$db_opcao,"") ?>
			</td>
		</tr>
		 
		<tr>
			<td nowrap title="<?php echo $Trh116_cnpj; ?>">
				 <?php echo $Lrh116_cnpj; ?>
			</td>
			<td> 
				<?php db_input('rh116_cnpj',40,$Irh116_cnpj,true,'text',$db_opcao,"") ?>
			</td>
		</tr>

		<tr>
			<td nowrap title="<?php echo $Trh116_descricao; ?>">
				 <?php echo $Lrh116_descricao; ?>
			</td>
			<td> 
				<?php db_input('rh116_descricao', 40, $Irh116_descricao, true, 'text', $db_opcao,"") ?>
			</td>
		</tr>
		</table>
	</fieldset>

	<br />
	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhsindicato','func_rhsindicato.php?funcao_js=parent.js_preenchepesquisa|rh116_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhsindicato.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>