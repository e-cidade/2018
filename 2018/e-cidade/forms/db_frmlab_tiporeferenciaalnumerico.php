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

//MODULO: Laboratório
$cllab_tiporeferenciaalnumerico->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla30_i_codigo?>">
       <?=@$Lla30_i_codigo?>
    </td>
    <td> 
<?
db_input('la30_i_codigo',10,$Ila30_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_i_valorref?>">
       <?=@$Lla30_i_valorref?>
    </td>
    <td> 
<?
db_input('la30_i_valorref',10,$Ila30_i_valorref,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_i_normalmin?>">
       <?=@$Lla30_i_normalmin?>
    </td>
    <td> 
<?
db_input('la30_i_normalmin',10,$Ila30_i_normalmin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_i_normalmax?>">
       <?=@$Lla30_i_normalmax?>
    </td>
    <td> 
<?
db_input('la30_i_normalmax',10,$Ila30_i_normalmax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_c_calculavel?>">
       <?=@$Lla30_c_calculavel?>
    </td>
    <td> 
<?
db_input('la30_c_calculavel',100,$Ila30_c_calculavel,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_i_absurdomin?>">
       <?=@$Lla30_i_absurdomin?>
    </td>
    <td> 
<?
db_input('la30_i_absurdomin',10,$Ila30_i_absurdomin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_i_absurdomax?>">
       <?=@$Lla30_i_absurdomax?>
    </td>
    <td> 
<?
db_input('la30_i_absurdomax',10,$Ila30_i_absurdomax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_tiporeferenciaalnumerico','func_lab_tiporeferenciaalnumerico.php?funcao_js=parent.js_preenchepesquisa|la30_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_tiporeferenciaalnumerico.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>