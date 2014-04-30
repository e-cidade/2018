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
$clissbaselogtipo->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend><b>Cadastro de Tipo de Alteração</b></legend>
<table border="0" align="center">
  <tr>
    <td nowrap title="<?=@$Tq103_sequencial?>">
       <?=@$Lq103_sequencial?>
    </td>
    <td> 
			<?
			  db_input('q103_sequencial',10,$Iq103_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq103_descricao?>">
       <?=@$Lq103_descricao?>
    </td>
    <td> 
      <?
        db_input('q103_descricao',50,$Iq103_descricao,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq103_dataini?>">
       <?=@$Lq103_dataini?>
    </td>
    <td> 
			<?
			  db_inputdata('q103_dataini',@$q103_dataini_dia,@$q103_dataini_mes,@$q103_dataini_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq103_datafin?>">
       <?=@$Lq103_datafin?>
    </td>
    <td> 
			<?
				db_inputdata('q103_datafin',@$q103_datafin_dia,@$q103_datafin_mes,@$q103_datafin_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq103_ativo?>">
       <?=@$Lq103_ativo?>
    </td>
    <td> 
			<?
				$x = array("f"=>"NAO","t"=>"SIM");
				db_select('q103_ativo',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  </table>
</fieldset>
<table cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>>
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
             <?=($db_opcao==1?"disabled":($db_opcao!=1||$db_opcao==22?"":""))?>>
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa(){
  var sUrl = 'func_issbaselogtipo.php?funcao_js=parent.js_preenchepesquisa|q103_sequencial';
  js_OpenJanelaIframe('top.corpo','db_iframe_issbaselogtipo',sUrl,'Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issbaselogtipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>