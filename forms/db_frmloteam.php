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

//MODULO: cadastro
$clloteam->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend><b>Cadastro de Loteamentos</b></legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tj34_loteam?>">
	       <?=@$Lj34_loteam?>
	    </td>
	    <td> 
				<?
				  db_input('j34_loteam',4,$Ij34_loteam,true,'text',3,"")
				?>
	    <td>
	  <tr>
	  <tr>
	    <td nowrap title="<?=@$Tj34_descr?>">
	       <?=@$Lj34_descr?>
	    </td>
	    <td> 
				<?
				  db_input('j34_descr',40,$Ij34_descr,true,'text',$db_opcao,"")
				?>
	    <td>
	  <tr>
	  <tr>
	    <td nowrap title="<?=@$Tj34_areacc?>">
	       <?=@$Lj34_areacc?>
	    </td>
	    <td> 
				<?
				  db_input('j34_areacc',15,$Ij34_areacc,true,'text',$db_opcao,"")
				?>
	    <td>
	  <tr>
	  <tr>
	    <td nowrap title="<?=@$Tj34_areapc?>">
	       <?=@$Lj34_areapc?>
	    </td>
	    <td> 
				<?
				  db_input('j34_areapc',15,$Ij34_areapc,true,'text',$db_opcao,"")
				?>
	    <td>
	  <tr>
	  <tr>
	    <td nowrap title="<?=@$Tj34_areato?>">
	       <?=@$Lj34_areato?>
	    </td>
	    <td> 
				<?
				  db_input('j34_areato',15,$Ij34_areato,true,'text',$db_opcao,"")
				?>
	    <td>
	  <tr>
	</table>
</fieldset>  
<table>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?> >    
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" 
             <?=($db_opcao==1?"disabled":"")?>>    
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_loteam','func_loteam.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true,0);
  db_iframe_loteam.mostraMsg();
  db_iframe_loteam.show();
  db_iframe_loteam.focus(); 
}
function js_preenchepesquisa(chave){
  db_iframe_loteam.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>