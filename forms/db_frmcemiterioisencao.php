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
$clcemiterioisencao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
  <legend>
    <b>Tipos de Isenção</b>
  </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tcm34_sequencial?>">
	       <?=@$Lcm34_sequencial?>
	    </td>
	    <td> 
				<?
				  db_input('cm34_sequencial',10,$Icm34_sequencial,true,'text',3,"")
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tcm34_descricao?>">
	       <?=@$Lcm34_descricao?>
	    </td>
	    <td> 
				<?
				  db_input('cm34_descricao',40,$Icm34_descricao,true,'text',$db_opcao,"")
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tcm34_tipo?>">
	      <?=@$Lcm34_tipo?>
	    </td>
	    <td> 
				<?
					$x = array('1'=>'Imune',
					           '2'=>'Isento');
					db_select('cm34_tipo',$x,true,$db_opcao,"style='width:125px;'");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tcm34_datalimite?>">
	      <?=@$Lcm34_datalimite?>
	    </td>
	    <td> 
				<?
	  			db_inputdata('cm34_datalimite',@$cm34_datalimite_dia,@$cm34_datalimite_mes,@$cm34_datalimite_ano,true,'text',$db_opcao,"")
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tcm34_obs?>">
	      <?=@$Lcm34_obs?>
	    </td>
	    <td> 
				<?
				  db_textarea('cm34_obs',5,40,$Icm34_obs,true,'text',$db_opcao,"")
				?>
	    </td>
	  </tr>
	  </table>
	  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if ( $db_opcao != 1 ) { ?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?} ?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cemiterioisencao','func_cemiterioisencao.php?funcao_js=parent.js_preenchepesquisa|cm34_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cemiterioisencao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>