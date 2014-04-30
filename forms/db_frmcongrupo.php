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

//MODULO: contabilidade
$clcongrupo->rotulo->label();
?>
<form name="form1" method="post" action="">
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc20_sequencial?>">
       <?=@$Lc20_sequencial?>
    </td>
    <td> 
<?
db_input('c20_sequencial',10,$Ic20_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc20_descr?>">
       <?=@$Lc20_descr?>
    </td>
    <td> 
<?
db_input('c20_descr',50,$Ic20_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<!--  
  <tr>
    <td nowrap title="<?=@$Tc20_tipo?>">
       <?=@$Lc20_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Interno','2'=>'Cliente');
db_select('c20_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
-->  
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_grupo','db_iframe_congrupo','func_congrupo.php?funcao_js=parent.js_preenchepesquisa|c20_sequencial','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_congrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>