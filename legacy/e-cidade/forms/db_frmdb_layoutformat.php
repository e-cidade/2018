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

//MODULO: configuracoes
$cldb_layoutformat->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb53_codigo?>">
       <?=@$Ldb53_codigo?>
    </td>
    <td> 
<?
db_input('db53_codigo',6,$Idb53_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_descr?>">
       <?=@$Ldb53_descr?>
    </td>
    <td> 
<?
db_input('db53_descr',40,$Idb53_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_mascara?>">
       <?=@$Ldb53_mascara?>
    </td>
    <td> 
<?
db_input('db53_mascara',40,$Idb53_mascara,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_tipo?>">
       <?=@$Ldb53_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'String','2'=>'Inteiro','3'=>'Decimal','4'=>'Data','5'=>'Hora','6'=>'CEP','7'=>'CGC / CPF','8'=>'Executa eval','9'=>'String livre');
db_select('db53_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_tamanho?>">
       <?=@$Ldb53_tamanho?>
    </td>
    <td> 
<?
db_input('db53_tamanho',4,$Idb53_tamanho,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_decimais?>">
       <?=@$Ldb53_decimais?>
    </td>
    <td> 
<?
db_input('db53_decimais',4,$Idb53_decimais,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_caracdec?>">
       <?=@$Ldb53_caracdec?>
    </td>
    <td> 
<?
db_input('db53_caracdec',4,$Idb53_caracdec,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb53_alinha?>">
       <?=@$Ldb53_alinha?>
    </td>
    <td> 
<?
$x = array('d'=>'Esquerda','e'=>'Direita');
db_select('db53_alinha',$x,true,$db_opcao,"");
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
  js_OpenJanelaIframe('top.corpo','db_iframe_db_layoutformat','func_db_layoutformat.php?funcao_js=parent.js_preenchepesquisa|db53_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_layoutformat.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>