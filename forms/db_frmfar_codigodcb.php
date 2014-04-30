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
$clfar_codigodcb->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa27_i_codigo");
$clrotulo->label("fa14_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa28_i_codigo?>">
       <?=@$Lfa28_i_codigo?>
    </td>
    <td> 
<?
db_input('fa28_i_codigo',10,$Ifa28_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa28_c_numero?>">
       <?=@$Lfa28_c_numero?>
    </td>
    <td> 
<?
db_input('fa28_c_numero',10,$Ifa28_c_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa28_i_tipodcb?>">
       <?
       db_ancora(@$Lfa28_i_tipodcb,"js_pesquisafa28_i_tipodcb(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa28_i_tipodcb',10,$Ifa28_i_tipodcb,true,'text',$db_opcao," onchange='js_pesquisafa28_i_tipodcb(false);'")
?>
       <?
db_input('fa27_c_denominacao',30,@$Ifa27_c_denominacao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa28_i_medanvisa?>">
       <?
       db_ancora(@$Lfa28_i_medanvisa,"js_pesquisafa28_i_medanvisa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('fa28_i_medanvisa',10,$Ifa28_i_medanvisa,true,'text',$db_opcao," onchange='js_pesquisafa28_i_medanvisa(false);'")
?>
       <?
db_input('fa14_c_medanvisa',30,@$Ifa14_c_medanvisa,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script>
function js_pesquisafa28_i_tipodcb(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_tipodc','func_far_tipodc.php?funcao_js=parent.js_mostrafar_tipodc1|fa27_i_codigo|fa27_c_denominacao','Pesquisa',true);
  }else{
     if(document.form1.fa28_i_tipodcb.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_tipodc','func_far_tipodc.php?pesquisa_chave='+document.form1.fa28_i_tipodcb.value+'&funcao_js=parent.js_mostrafar_tipodc','Pesquisa',false);
     }else{
       document.form1.fa27_c_denominacao.value = ''; 
     }
  }
}
function js_mostrafar_tipodc(chave,erro){
  document.form1.fa27_c_denominacao.value = chave; 
  if(erro==true){ 
    document.form1.fa28_i_tipodcb.focus(); 
    document.form1.fa28_i_tipodcb.value = ''; 
  }
}
function js_mostrafar_tipodc1(chave1,chave2){
  document.form1.fa28_i_tipodcb.value = chave1;
  document.form1.fa27_c_denominacao.value = chave2;
  db_iframe_far_tipodc.hide();
}
function js_pesquisafa28_i_medanvisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?funcao_js=parent.js_mostrafar_medanvisa1|fa14_i_codigo|fa14_c_medanvisa','Pesquisa',true);
  }else{
     if(document.form1.fa28_i_medanvisa.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?pesquisa_chave='+document.form1.fa28_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_medanvisa','Pesquisa',false);
     }else{
       document.form1.fa14_c_medanvisa.value = ''; 
     }
  }
}
function js_mostrafar_medanvisa(chave,erro){
  document.form1.fa14_c_medanvisa.value = chave; 
  if(erro==true){ 
    document.form1.fa28_i_medanvisa.focus(); 
    document.form1.fa28_i_medanvisa.value = ''; 
  }
}
function js_mostrafar_medanvisa1(chave1,chave2){
  document.form1.fa28_i_medanvisa.value = chave1;
  document.form1.fa14_c_medanvisa.value = chave2;
  db_iframe_far_medanvisa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_codigodcb','func_far_codigodcb.php?funcao_js=parent.js_preenchepesquisa|fa28_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_codigodcb.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>