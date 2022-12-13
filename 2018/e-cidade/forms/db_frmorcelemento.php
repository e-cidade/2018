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

//MODULO: orcamento
$clorcelemento->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To56_codele?>">
       <?=@$Lo56_codele?>
    </td>
    <td> 
<?
db_input('o56_codele',6,$Io56_codele,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To56_elemento?>">
       <?=@$Lo56_elemento?>
    </td>
    <td> 
<?
if($db_opcao==1){
    $db_opcao02=1;
}else{
    $db_opcao02=3;
}
db_input('o56_elemento',20,$Io56_elemento,true,'text',$db_opcao02);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To56_descr?>">
       <?=@$Lo56_descr?>
    </td>
    <td> 
<?
db_input('o56_descr',40,$Io56_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To56_finali?>">
       <?=@$Lo56_finali?>
    </td>
    <td> 
<?
db_textarea('o56_finali',0,40,$Io56_finali,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To56_orcado?>">
       <?=@$Lo56_orcado?>
    </td>
    <td> 
<?
$matriz = array("t"=>"SIM","f"=>'Não');
db_select('o56_orcado',$matriz,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_elem();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_elem(){
  elem = document.form1.o56_elemento.value;
  if(elem.length<13){
    for(i=elem.length; i<13; i++){
      elem=elem+"0";
    } 
  }
  document.form1.o56_elemento.value=elem;
  return true;
}  
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_preenchepesquisa|o56_codele','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcelemento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>