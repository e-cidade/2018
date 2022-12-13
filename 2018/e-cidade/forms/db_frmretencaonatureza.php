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

//MODULO: empenho
$clretencaonatureza->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend><b>C�digos do IRRF</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Te30_sequencial?>">
       		  <?=@$Le30_sequencial?>
            </td>
            <td> 
			<?
			  db_input('e30_sequencial',10,$Ie30_sequencial,true,'text',3,"");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Te30_codigo?>">
       		 <?=@$Le30_codigo?>
            </td>
            <td> 
			<?
			  db_input('e30_codigo',20,$Ie30_codigo,true,'text',$db_opcao,"")
			?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Te30_descricao?>">
       		<?=@$Le30_descricao?>
            </td>
            <td> 
			<?
			db_input('e30_descricao',50,$Ie30_descricao,true,'text',$db_opcao,"")
			?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>        
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_retencaonatureza','func_retencaonatureza.php?funcao_js=parent.js_preenchepesquisa|e30_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_retencaonatureza.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>