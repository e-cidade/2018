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

//MODULO: Configuracoes
$clorcparamseqcoluna->rotulo->label();
?>
<form name="form1" method="post" action="">
	<fieldset style="width:500px;">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To115_sequencial?>">
       <?=@$Lo115_sequencial?>
    </td>
    <td> 
		<?
		db_input('o115_sequencial',10,$Io115_sequencial,true,'text',3,"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To115_anousu?>">
       <?=@$Lo115_anousu?>
    </td>
    <td> 
			<?
			$o115_anousu = db_getsession('DB_anousu');
			db_input('o115_anousu',4,$Io115_anousu,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To115_descricao?>">
       <?=@$Lo115_descricao?>
    </td>
    <td> 
			<?
			db_input('o115_descricao',50,$Io115_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To115_nomecoluna?>">
       <?=@$Lo115_nomecoluna?>
    </td>
    <td> 
      <?
      db_input('o115_nomecoluna', 50, $Io115_nomecoluna, true, 'text', $db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To115_tipo?>">
       <?=@$Lo115_tipo?>
    </td>
    <td> 
			<?
			$x = array('1'=>'Valores','2'=>'Alfanumericos');
			db_select('o115_tipo',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To115_valoresdefault?>">
       <?=@$Lo115_valoresdefault?>
    </td>
    <td> 
      <?
      db_textarea('o115_valoresdefault',5, 50,$Io115_descricao,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  </table>
  </center>
	</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseqcoluna','func_orcparamseqcoluna.php?funcao_js=parent.js_preenchepesquisa|o115_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcparamseqcoluna.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>