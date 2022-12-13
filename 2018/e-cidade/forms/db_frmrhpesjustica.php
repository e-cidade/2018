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

//MODULO: pessoal
$clrhpesjustica->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh61_codigo?>">
       <?=@$Lrh61_codigo?>
    </td>
    <td> 
<?
db_input('rh61_codigo',6,$Irh61_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh61_regist?>">
       <?
       db_ancora(@$Lrh61_regist,"js_pesquisarh61_regist(true);",$db_opcao);
       ?>
    </td>
    <td nowrap colspan="3"> 
<?
db_input('rh61_regist',6,$Irh61_regist,true,'text',$db_opcao," onchange='js_pesquisarh61_regist(false);'")
?>
       <?
db_input('z01_nome',47,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh61_dataini?>">
       <?=@$Lrh61_dataini?>
    </td>
    <td> 
<?
db_inputdata('rh61_dataini',@$rh61_dataini_dia,@$rh61_dataini_mes,@$rh61_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Trh61_datafim?>">
       <?=@$Lrh61_datafim?>
    </td>
    <td> 
<?
db_inputdata('rh61_datafim',@$rh61_datafim_dia,@$rh61_datafim_mes,@$rh61_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh61_obs?>">
       <?=@$Lrh61_obs?>
    </td>
    <td colspan="3"> 
<?
db_textarea('rh61_obs',3,53,$Irh61_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh61_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.rh61_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh61_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh61_regist.focus(); 
    document.form1.rh61_regist.value = ''; 
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh61_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpesjustica','func_rhpesjustica.php?funcao_js=parent.js_preenchepesquisa|rh61_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpesjustica.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>