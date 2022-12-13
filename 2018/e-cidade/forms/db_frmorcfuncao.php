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
$clorcfuncao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To52_funcao?>">
       <?=@$Lo52_funcao?>
    </td>
    <td> 
<?
if($db_opcao==1){
    $db_opcao02=1;
}else{
    $db_opcao02=3;
}
db_input('o52_funcao',2,$Io52_funcao,true,'text',$db_opcao02);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To52_descr?>">
       <?=@$Lo52_descr?>
    </td>
    <td> 
<?
db_input('o52_descr',40,$Io52_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To52_codtri?>">
       <?=@$Lo52_codtri?>
    </td>
    <td> 
<?
db_input('o52_codtri',10,$Io52_codtri,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To52_finali?>">
       <?=@$Lo52_finali?>
    </td>
    <td> 
<?
db_textarea('o52_finali',0,40,$Io52_finali,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if(empty($novo)){?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}else{?>
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcfuncao.hide();">
<?}?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcfuncao','func_orcfuncao.php?funcao_js=parent.js_preenchepesquisa|o52_funcao','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcfuncao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>