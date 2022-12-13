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

//MODULO: ISSQN
$clmunicipiosiafi->rotulo->label();
?>
<form name="form1" method="post" action="">
	  <fieldset>
	    <legend>
	      <b>Cadastro de Municipios SIAFI</b>
	    </legend>
			<table border="0">
			  <tr>
			    <td nowrap title="<?=@$Tq110_sequencial?>">
			      <?=@$Lq110_sequencial?>
			    </td>
			    <td> 
						<?
						  db_input('q110_sequencial',10,$Iq110_sequencial,true,'text',3,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tq110_codigo?>">
			      <?=@$Lq110_codigo?>
			    </td>
			    <td> 
						<?
						  db_input('q110_codigo',4,$Iq110_codigo,true,'text',$db_opcao,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tq110_descricao?>">
			      <?=@$Lq110_descricao?>
			    </td>
			    <td> 
						<?
						  db_input('q110_descricao',50,$Iq110_descricao,true,'text',$db_opcao,"")
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tq110_uf?>">
			      <?=@$Lq110_uf?>
			    </td>
			    <td>
						<?
						  require_once("classes/db_db_uf_classe.php");
						  
						  $cldb_uf = new cl_db_uf();
						  $rsUf    = $cldb_uf->sql_record($cldb_uf->sql_query_file(null,"db12_uf,db12_uf","db12_uf"));
						  
						  if ( $db_opcao == 3 ) {
						  	db_input('q110_uf',2,$Iq110_uf,true,'text',$db_opcao,"");
						  } else {
							  db_selectrecord('q110_uf',$rsUf,true,$db_opcao,'','','','','',1);
						  }
						  
						?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tq110_cnpj?>">
			      <?=@$Lq110_cnpj?>
			    </td>
			    <td> 
						<?
						  db_input('q110_cnpj',14,$Iq110_cnpj,true,'text',$db_opcao,"")
						?>
			    </td>
			  </tr>
      </table>
    </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_municipiosiafi','func_municipiosiafi.php?funcao_js=parent.js_preenchepesquisa|q110_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_municipiosiafi.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>