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

//MODULO: material
$clmatunid->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm61_codmatunid?>">
       <?=@$Lm61_codmatunid?>
    </td>
    <td> 
<?
db_input('m61_codmatunid',8,$Im61_codmatunid,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm61_descr?>">
       <?=@$Lm61_descr?>
    </td>
    <td> 
<?
db_input('m61_descr',40,$Im61_descr,true,'text',$db_opcao,"onchange='js_abrev();'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm61_abrev?>">
       <?=@$Lm61_abrev?>
    </td>
    <td> 
<?
db_input('m61_abrev',6,$Im61_abrev,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm61_usaquant?>">
       <?=@$Lm61_usaquant?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('m61_usaquant',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm61_usadec?>">
       <?=@$Lm61_usadec?>
    </td>
    <td> 
<?
if(!isset($m61_usadec)){
  $m61_usadec = 't';
}
$x = array("f"=>"NAO","t"=>"SIM");
db_select('m61_usadec',$x,true,$db_opcao,"");
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
  js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_preenchepesquisa|m61_codmatunid','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_matunid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_abrev(){
  x = document.form1;
  valor = x.m61_descr.value.substr(0,6);
  if(x.m61_abrev.value==""){
    x.m61_abrev.value = valor;
  }
}
</script>