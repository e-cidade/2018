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

//MODULO: recursos humanos
$clflegal->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th04_codigo?>">
       <?=@$Lh04_codigo?>
    </td>
    <td> 
<?
db_input('h04_codigo',5,$Ih04_codigo,true,'text',($db_opcao == 1 ? 1 : 3),"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th04_descr?>">
       <?=@$Lh04_descr?>
    </td>
    <td> 
<?
db_input('h04_descr',35,$Ih04_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th04_tpfund?>">
       <?=@$Lh04_tpfund?>
    </td>
    <td> 
<?
$arr_tpfund = array(
                    1=>"1 - Decreto",
                    2=>"2 - Edital",
                    3=>"3 - Lei",
                    4=>"4 - Portaria",
                    5=>"5 - Resolucao",
                    9=>"9 - Outros"
                   );
if(!isset($h04_tpfund) || (isset($h04_tpfund) && trim($h04_tpfund) != "")){
  $h04_tpfund = 3;
}
db_select("h04_tpfund", $arr_tpfund, true, $db_opcao);
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
  js_OpenJanelaIframe('top.corpo','db_iframe_flegal','func_flegal.php?funcao_js=parent.js_preenchepesquisa|h04_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_flegal.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>