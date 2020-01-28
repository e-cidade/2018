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
$clconcur->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th06_refer?>">
       <?=@$Lh06_refer?>
    </td>
    <td> 
<?
db_input('h06_refer',5,$Ih06_refer,true,'text',($db_opcao == 1 ? 1 : 3),"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_eaber?>">
       <?=@$Lh06_eaber?>
    </td>
    <td> 
<?
db_input('h06_eaber',28,$Ih06_eaber,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_daber?>">
       <?=@$Lh06_daber?>
    </td>
    <td> 
<?
db_inputdata('h06_daber',@$h06_daber_dia,@$h06_daber_mes,@$h06_daber_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_ehomo?>">
       <?=@$Lh06_ehomo?>
    </td>
    <td> 
<?
db_input('h06_ehomo',28,$Ih06_ehomo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_dhomo?>">
       <?=@$Lh06_dhomo?>
    </td>
    <td> 
<?
db_inputdata('h06_dhomo',@$h06_dhomo_dia,@$h06_dhomo_mes,@$h06_dhomo_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_concur?>">
       <?=@$Lh06_concur?>
    </td>
    <td> 
<?
db_input('h06_concur',28,$Ih06_concur,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_dpubl?>">
       <?=@$Lh06_dpubl?>
    </td>
    <td> 
<?
db_inputdata('h06_dpubl',@$h06_dpubl_dia,@$h06_dpubl_mes,@$h06_dpubl_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_dvalid?>">
       <?=@$Lh06_dvalid?>
    </td>
    <td> 
<?
db_inputdata('h06_dvalid',@$h06_dvalid_dia,@$h06_dvalid_mes,@$h06_dvalid_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_dprorr?>">
       <?=@$Lh06_dprorr?>
    </td>
    <td> 
<?
db_inputdata('h06_dprorr',@$h06_dprorr_dia,@$h06_dprorr_mes,@$h06_dprorr_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th06_nrproc?>">
       <?=@$Lh06_nrproc?>
    </td>
    <td> 
<?
db_input('h06_nrproc',28,$Ih06_nrproc,true,'text',$db_opcao,"")
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
  js_OpenJanelaIframe('top.corpo','db_iframe_concur','func_concur.php?funcao_js=parent.js_preenchepesquisa|h06_refer','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_concur.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>