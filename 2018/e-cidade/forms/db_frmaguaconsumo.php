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

//MODULO: agua
$claguaconsumo->rotulo->label();
      if($db_opcao==1){
 	   $db_action="agu1_aguaconsumo004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="agu1_aguaconsumo005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="agu1_aguaconsumo006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx19_codconsumo?>">
       <?=@$Lx19_codconsumo?>
    </td>
    <td> 
<?
db_input('x19_codconsumo',5,$Ix19_codconsumo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_exerc?>">
       <?=@$Lx19_exerc?>
    </td>
    <td> 
<?
db_input('x19_exerc',4,$Ix19_exerc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_areaini?>">
       <?=@$Lx19_areaini?>
    </td>
    <td> 
<?
db_input('x19_areaini',8,$Ix19_areaini,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_areafim?>">
       <?=@$Lx19_areafim?>
    </td>
    <td> 
<?
db_input('x19_areafim',8,$Ix19_areafim,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_caract?>">
       <?=@$Lx19_caract?>
    </td>
    <td> 
<?
db_input('x19_caract',5,$Ix19_caract,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_conspadrao?>">
       <?=@$Lx19_conspadrao?>
    </td>
    <td> 
<?
db_input('x19_conspadrao',10,$Ix19_conspadrao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_descr?>">
       <?=@$Lx19_descr?>
    </td>
    <td> 
<?
db_input('x19_descr',40,$Ix19_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx19_ativo?>">
       <?=@$Lx19_ativo?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('x19_ativo',$x,true,$db_opcao,"");
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
  js_OpenJanelaIframe('top.corpo.iframe_aguaconsumo','db_iframe_aguaconsumo','func_aguaconsumo.php?funcao_js=parent.js_preenchepesquisa|x19_codconsumo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_aguaconsumo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>