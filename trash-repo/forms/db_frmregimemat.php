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

//MODULO: escola
$clregimemat->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td nowrap title="<?=@$Ted218_i_codigo?>" width="15%">
   <?=@$Led218_i_codigo?>
  </td>
  <td>
   <?db_input('ed218_i_codigo',20,$Ied218_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted218_c_nome?>">
   <?=@$Led218_c_nome?>
  </td>
  <td>
   <?db_input('ed218_c_nome',30,$Ied218_c_nome,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted218_c_abrev?>">
   <?=@$Led218_c_abrev?>
  </td>
  <td>
   <?db_input('ed218_c_abrev',10,$Ied218_c_abrev,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted218_c_divisao?>">
   <?=@$Led218_c_divisao?>
  </td>
  <td>
   <?
   $x = array(''=>'','S'=>'SIM','N'=>'NÃO');
   db_select('ed218_c_divisao',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" >
<br>
<table width="100%">
 <tr>
  <td align="center">
   <iframe src="" name="iframe_divisoes" id="iframe_divisoes" width="100%" height="330" frameborder="0" scrolling="no"></iframe>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_regimemat','func_regimemat.php?funcao_js=parent.js_preenchepesquisa|ed218_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_regimemat.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
function js_novo(){
 location.href="edu1_regimemat001.php";
}
</script>