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
$clfar_formafarmaceuticamed->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa14_i_codigo");
$clrotulo->label("fa29_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa33_i_codigo?>">
       <?=@$Lfa33_i_codigo?>
    </td>
    <td> 
<?
db_input('fa33_i_codigo',10,$Ifa33_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa33_i_medanvisa?>">
       <?
       db_ancora(@$Lfa33_i_medanvisa,"js_pesquisafa33_i_medanvisa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa33_i_medanvisa',10,$Ifa33_i_medanvisa,true,'text',$db_opcao," onchange='js_pesquisafa33_i_medanvisa(false);'")
?>
       <?
db_input('fa14_c_medanvisa',40,@$Ifa14_c_medanvisa,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa33_i_formafarmaceutica?>">
       <?
       db_ancora(@$Lfa33_i_formafarmaceutica,"js_pesquisafa33_i_formafarmaceutica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa33_i_formafarmaceutica',10,$Ifa33_i_formafarmaceutica,true,'text',$db_opcao," onchange='js_pesquisafa33_i_formafarmaceutica(false);'")
?>
       <?
db_input('fa29_c_forma',40,@$Ifa29_c_forma,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script>
function js_pesquisafa33_i_medanvisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?funcao_js=parent.js_mostrafar_medanvisa1|fa14_i_codigo|fa14_c_medanvisa','Pesquisa',true);
  }else{
     if(document.form1.fa33_i_medanvisa.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?pesquisa_chave='+document.form1.fa33_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_medanvisa','Pesquisa',false);
     }else{
       document.form1.fa14_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_medanvisa(chave,erro){
  document.form1.fa14_c_medanvisa.value = chave; 
  if(erro==true){ 
    document.form1.fa33_i_medanvisa.focus(); 
    document.form1.fa33_i_medanvisa.value = ''; 
  }
}
function js_mostrafar_medanvisa1(chave1,chave2){
  document.form1.fa33_i_medanvisa.value = chave1;
  document.form1.fa14_c_medanvisa.value = chave2;
  db_iframe_far_medanvisa.hide();
}
function js_pesquisafa33_i_formafarmaceutica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_formafarmaceutica','func_far_formafarmaceutica.php?funcao_js=parent.js_mostrafar_formafarmaceutica1|fa29_i_codigo|fa29_c_forma','Pesquisa',true);
  }else{
     if(document.form1.fa33_i_formafarmaceutica.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_formafarmaceutica','func_far_formafarmaceutica.php?pesquisa_chave='+document.form1.fa33_i_formafarmaceutica.value+'&funcao_js=parent.js_mostrafar_formafarmaceutica','Pesquisa',false);
     }else{
       document.form1.fa29_i_codigo.value = ''; 
     }
  }
}
function js_mostrafar_formafarmaceutica(chave,erro){
  document.form1.fa29_c_forma.value = chave; 
  if(erro==true){ 
    document.form1.fa33_i_formafarmaceutica.focus(); 
    document.form1.fa33_i_formafarmaceutica.value = ''; 
  }
}
function js_mostrafar_formafarmaceutica1(chave1,chave2){
  document.form1.fa33_i_formafarmaceutica.value = chave1;
  document.form1.fa29_c_forma.value = chave2;
  db_iframe_far_formafarmaceutica.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_far_formafarmaceuticamed','func_far_formafarmaceuticamed_ext.php?funcao_js=parent.js_preenchepesquisa|fa33_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_formafarmaceuticamed.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>