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
$clorcsubfuncao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To53_subfuncao?>">
       <?=@$Lo53_subfuncao?>
    </td>
    <td> 
<?
if($db_opcao==1){
    $db_opcao02=1;
}else{
    $db_opcao02=3;
}
db_input('o53_subfuncao',3,$Io53_subfuncao,true,'text',$db_opcao02);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To53_descr?>">
       <?=@$Lo53_descr?>
    </td>
    <td> 
<?
db_input('o53_descr',40,$Io53_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To53_codtri?>">
       <?=@$Lo53_codtri?>
    </td>
    <td> 
<?
db_input('o53_codtri',10,$Io53_codtri,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To53_finali?>">
       <?=@$Lo53_finali?>
    </td>
    <td> 
<?
db_textarea('o53_finali',0,40,$Io53_finali,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if(empty($novo)){?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}else{?>
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcsubfuncao.hide();">
<?}?>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcsubfuncao','func_orcsubfuncao.php?funcao_js=parent.js_preenchepesquisa|o53_subfuncao','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcsubfuncao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>