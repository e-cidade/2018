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

//MODULO: Farmacia
$clfar_prescricaomed->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa14_i_codigo");
$clrotulo->label("fa20_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa31_i_codigo?>">
       <?=@$Lfa31_i_codigo?>
    </td>
    <td> 
<?
db_input('fa31_i_codigo',10,$Ifa31_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa31_i_medanvisa?>">
       <?
       db_ancora(@$Lfa31_i_medanvisa,"js_pesquisafa31_i_medanvisa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa31_i_medanvisa',10,$Ifa31_i_medanvisa,true,'text',$db_opcao," onchange='js_pesquisafa31_i_medanvisa(false);'")
?>
       <?
db_input('fa14_c_medanvisa',40,@$Ifa14_c_medanvisa,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa31_i_prescricao?>">
       <?
       db_ancora(@$Lfa31_i_prescricao,"js_pesquisafa31_i_prescricao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa31_i_prescricao',10,$Ifa31_i_prescricao,true,'text',$db_opcao," onchange='js_pesquisafa31_i_prescricao(false);'")
?>
       <?
db_input('fa20_c_prescricao',40,@$Ifa20_c_prescricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script>
function js_pesquisafa31_i_medanvisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?funcao_js=parent.js_mostrafar_medanvisa1|fa14_i_codigo|fa14_c_medanvisa','Pesquisa',true);
  }else{
     if(document.form1.fa31_i_medanvisa.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?pesquisa_chave='+document.form1.fa31_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_medanvisa','Pesquisa',false);
     }else{
       document.form1.fa14_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_medanvisa(chave,erro){
  document.form1.fa14_c_medanvisa.value = chave; 
  if(erro==true){ 
    document.form1.fa31_i_medanvisa.focus(); 
    document.form1.fa31_i_medanvisa.value = ''; 
  }
}
function js_mostrafar_medanvisa1(chave1,chave2){
  document.form1.fa31_i_medanvisa.value = chave1;
  document.form1.fa14_c_medanvisa.value = chave2;
  db_iframe_far_medanvisa.hide();
}
function js_pesquisafa31_i_prescricao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_prescricaomedica','func_far_prescricaomedica.php?funcao_js=parent.js_mostrafar_prescricaomedica1|fa20_i_codigo|fa20_c_prescricao','Pesquisa',true);
  }else{
     if(document.form1.fa31_i_prescricao.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_prescricaomedica','func_far_prescricaomedica.php?pesquisa_chave='+document.form1.fa31_i_prescricao.value+'&funcao_js=parent.js_mostrafar_prescricaomedica','Pesquisa',false);
     }else{
       document.form1.fa20_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_prescricaomedica(chave,erro){
  document.form1.fa20_c_prescricao.value = chave; 
  if(erro==true){ 
    document.form1.fa31_i_prescricao.focus(); 
    document.form1.fa31_i_prescricao.value = ''; 
  }
}
function js_mostrafar_prescricaomedica1(chave1,chave2){
  document.form1.fa31_i_prescricao.value = chave1;
  document.form1.fa20_c_prescricao.value = chave2;
  db_iframe_far_prescricaomedica.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_far_prescricaomed','func_far_prescricaomed_ext.php?funcao_js=parent.js_preenchepesquisa|fa31_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_prescricaomed.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>