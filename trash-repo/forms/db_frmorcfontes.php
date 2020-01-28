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
$clorcfontes->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To57_codfon?>">
       <?=@$Lo57_codfon?>
    </td>
    <td> 
<?
db_input('o57_codfon',6,$Io57_codfon,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To57_fonte?>">
       <?=@$Lo57_fonte?>
    </td>
    <td> 
<?
if($db_opcao==1){
    $db_opcao02=1;
}else{
    $db_opcao02=3;
}
db_input('o57_fonte',22,$Io57_fonte,true,'text',$db_opcao02);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To57_descr?>">
       <?=@$Lo57_descr?>
    </td>
    <td> 
<?
db_input('o57_descr',40,$Io57_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To57_finali?>">
       <?=@$Lo57_finali?>
    </td>
    <td> 
<?
db_textarea('o57_finali',0,40,$Io57_finali,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_fonte();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_fonte(){
  fonte = document.form1.o57_fonte.value;
  if(fonte.length<13){
    for(i=fonte.length; i<13; i++){
      fonte=fonte+"0";
    } 
  }
  document.form1.o57_fonte.value=fonte;
  return true;
}  
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_preenchepesquisa|o57_codfon','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcfontes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>