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

//MODULO: Laboratório
$cllab_materialrecipiente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la14_i_codigo");
$clrotulo->label("la13_i_codigo");
$clrotulo->label("la15_c_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla17_i_codigo?>">
       <?=@$Lla17_i_codigo?>
    </td>
    <td> 
<?
db_input('la17_i_codigo',10,$Ila17_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla17_i_recipiente?>">
       <?
       db_ancora(@$Lla17_i_recipiente,"js_pesquisala17_i_recipiente(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la17_i_recipiente',10,$Ila17_i_recipiente,true,'text',$db_opcao," onchange='js_pesquisala17_i_recipiente(false);'")
?>
       <?
db_input('la14_c_descr',50,$Ila14_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla17_i_unidade?>">
       <?
       db_ancora(@$Lla17_i_unidade,"js_pesquisala17_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la17_i_unidade',10,$Ila17_i_unidade,true,'text',$db_opcao," onchange='js_pesquisala17_i_unidade(false);'")
?>
       <?
db_input('la13_c_descr',50,$Ila13_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla17_i_materialcoleta?>">
       <?
       db_ancora(@$Lla17_i_materialcoleta,"js_pesquisala17_i_materialcoleta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la17_i_materialcoleta',10,$Ila17_i_materialcoleta,true,'text',$db_opcao," onchange='js_pesquisala17_i_materialcoleta(false);'")
?>
       <?
db_input('la15_c_descr',50,$Ila15_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla17_d_inicio?>">
       <?=@$Lla17_d_inicio?>
    </td>
    <td> 
<?
db_inputdata('la17_d_inicio',@$la17_d_inicio_dia,@$la17_d_inicio_mes,@$la17_d_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla17_d_fim?>">
       <?=@$Lla17_d_fim?>
    </td>
    <td> 
<?
db_inputdata('la17_d_fim',@$la17_d_fim_dia,@$la17_d_fim_mes,@$la17_d_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisala17_i_recipiente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_recipiente','func_lab_recipiente.php?funcao_js=parent.js_mostralab_recipiente1|la14_i_codigo|la14_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la17_i_recipiente.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_recipiente','func_lab_recipiente.php?pesquisa_chave='+document.form1.la17_i_recipiente.value+'&funcao_js=parent.js_mostralab_recipiente','Pesquisa',false);
     }else{
       document.form1.la14_c_descr.value = ''; 
     }
  }
}
function js_mostralab_recipiente(chave,erro){
  document.form1.la14_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la17_i_recipiente.focus(); 
    document.form1.la17_i_recipiente.value = ''; 
  }
}
function js_mostralab_recipiente1(chave1,chave2){
  document.form1.la17_i_recipiente.value = chave1;
  document.form1.la14_c_descr.value = chave2;
  db_iframe_lab_recipiente.hide();
}
function js_pesquisala17_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?funcao_js=parent.js_mostralab_undmedida1|la13_i_codigo|la13_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la17_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?pesquisa_chave='+document.form1.la17_i_unidade.value+'&funcao_js=parent.js_mostralab_undmedida','Pesquisa',false);
     }else{
       document.form1.la13_c_descr.value = ''; 
     }
  }
}
function js_mostralab_undmedida(chave,erro){
  document.form1.la13_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la17_i_unidade.focus(); 
    document.form1.la17_i_unidade.value = ''; 
  }
}
function js_mostralab_undmedida1(chave1,chave2){
  document.form1.la17_i_unidade.value = chave1;
  document.form1.la13_c_descr.value = chave2;
  db_iframe_lab_undmedida.hide();
}
function js_pesquisala17_i_materialcoleta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_materialcoleta','func_lab_materialcoleta.php?funcao_js=parent.js_mostralab_materialcoleta1|la15_i_codigo|la15_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la17_i_materialcoleta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_materialcoleta','func_lab_materialcoleta.php?pesquisa_chave='+document.form1.la17_i_materialcoleta.value+'&funcao_js=parent.js_mostralab_materialcoleta','Pesquisa',false);
     }else{
       document.form1.la15_c_descr.value = ''; 
     }
  }
}
function js_mostralab_materialcoleta(chave,erro){
  document.form1.la15_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la17_i_materialcoleta.focus(); 
    document.form1.la17_i_materialcoleta.value = ''; 
  }
}
function js_mostralab_materialcoleta1(chave1,chave2){
  document.form1.la17_i_materialcoleta.value = chave1;
  document.form1.la15_c_descr.value = chave2;
  db_iframe_lab_materialcoleta.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_materialrecipiente','func_lab_materialrecipiente.php?funcao_js=parent.js_preenchepesquisa|la17_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_materialrecipiente.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>