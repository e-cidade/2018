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

//MODULO: pessoal
$clrhpagtipoocor->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh59_codigo?>">
       <?=@$Lrh59_codigo?>
    </td>
    <td> 
<?
db_input('rh59_codigo',6,$Irh59_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh59_descr?>">
       <?=@$Lrh59_descr?>
    </td>
    <td> 
<?
db_input('rh59_descr',40,$Irh59_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Trh59_obs?>">
       <?=@$Lrh59_obs?>
    </td>
    <td>
      <?
      db_textarea("rh59_obs", 3, 38, $Irh59_obs, true, 'text', $db_opcao);
      ?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Trh59_tipo?>">
       <?=@$Lrh59_tipo?>
    </td>
    <td>
      <?
      $arr_tipoocor = Array("S"=>"Somar","D"=>"Descontar");
      db_select("rh59_tipo", $arr_tipoocor, true, $db_opcao);
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
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?funcao_js=parent.js_preenchepesquisa|rh59_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpagtipoocor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>