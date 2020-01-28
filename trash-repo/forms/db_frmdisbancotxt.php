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

//MODULO: caixa
$cldisbancotxt->rotulo->label();
      if($db_opcao==1){
 	   $db_action="cai1_disbancotxt004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="cai1_disbancotxt005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="cai1_disbancotxt006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk34_sequencial?>">
       <?=@$Lk34_sequencial?>
    </td>
    <td> 
<?
db_input('k34_sequencial',10,$Ik34_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_numpremigra?>">
       <?=@$Lk34_numpremigra?>
    </td>
    <td> 
<?
db_input('k34_numpremigra',50,$Ik34_numpremigra,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_valor?>">
       <?=@$Lk34_valor?>
    </td>
    <td> 
<?
db_input('k34_valor',15,$Ik34_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_dtvenc?>">
       <?=@$Lk34_dtvenc?>
    </td>
    <td> 
<?
db_inputdata('k34_dtvenc',@$k34_dtvenc_dia,@$k34_dtvenc_mes,@$k34_dtvenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_dtpago?>">
       <?=@$Lk34_dtpago?>
    </td>
    <td> 
<?
db_inputdata('k34_dtpago',@$k34_dtpago_dia,@$k34_dtpago_mes,@$k34_dtpago_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_codret?>">
       <?=@$Lk34_codret?>
    </td>
    <td> 
<?
db_input('k34_codret',5,$Ik34_codret,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk34_diferenca?>">
       <?=@$Lk34_diferenca?>
    </td>
    <td> 
<?
db_input('k34_diferenca',15,$Ik34_diferenca,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo.iframe_disbancotxt','db_iframe_disbancotxt','func_disbancotxtalt.php?funcao_js=parent.js_preenchepesquisa|k34_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_disbancotxt.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>