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

//MODULO: saude
$clprontuarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd15_c_descr");
$clrotulo->label("sd22_c_codigo");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd24_i_codigo?>">
       <?=@$Lsd24_i_codigo?>
    </td>
    <td> 
<?
db_input('sd24_i_codigo',5,$Isd24_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_ano?>">
       <?=@$Lsd24_i_ano?>
    </td>
    <td> 
<?
db_input('sd24_i_ano',4,$Isd24_i_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_mes?>">
       <?=@$Lsd24_i_mes?>
    </td>
    <td> 
<?
db_input('sd24_i_mes',2,$Isd24_i_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_seq?>">
       <?=@$Lsd24_i_seq?>
    </td>
    <td> 
<?
db_input('sd24_i_seq',6,$Isd24_i_seq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_unidade?>">
       <?
       db_ancora(@$Lsd24_i_unidade,"js_pesquisasd24_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd24_i_unidade(false);'")
?>
       <?
db_input('sd02_c_nome',200,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_grupoatend?>">
       <?
       db_ancora(@$Lsd24_i_grupoatend,"js_pesquisasd24_i_grupoatend(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_i_grupoatend',10,$Isd24_i_grupoatend,true,'text',$db_opcao," onchange='js_pesquisasd24_i_grupoatend(false);'")
?>
       <?
db_input('sd15_c_descr',50,$Isd15_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_c_cid?>">
       <?
       db_ancora(@$Lsd24_c_cid,"js_pesquisasd24_c_cid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_c_cid',6,$Isd24_c_cid,true,'text',$db_opcao," onchange='js_pesquisasd24_c_cid(false);'")
?>
       <?
db_input('sd22_c_codigo',6,$Isd22_c_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_i_numcgm?>">
       <?
       db_ancora(@$Lsd24_i_numcgm,"js_pesquisasd24_i_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd24_i_numcgm',10,$Isd24_i_numcgm,true,'text',$db_opcao," onchange='js_pesquisasd24_i_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_v_motivo?>">
       <?=@$Lsd24_v_motivo?>
    </td>
    <td> 
<?
db_input('sd24_v_motivo',200,$Isd24_v_motivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd24_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd24_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = ''; 
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_unidade.focus(); 
    document.form1.sd24_i_unidade.value = ''; 
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd24_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd24_i_grupoatend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_grupoatend','func_grupoatend.php?funcao_js=parent.js_mostragrupoatend1|sd15_i_codigo|sd15_c_descr','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_grupoatend.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_grupoatend','func_grupoatend.php?pesquisa_chave='+document.form1.sd24_i_grupoatend.value+'&funcao_js=parent.js_mostragrupoatend','Pesquisa',false);
     }else{
       document.form1.sd15_c_descr.value = ''; 
     }
  }
}
function js_mostragrupoatend(chave,erro){
  document.form1.sd15_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_grupoatend.focus(); 
    document.form1.sd24_i_grupoatend.value = ''; 
  }
}
function js_mostragrupoatend1(chave1,chave2){
  document.form1.sd24_i_grupoatend.value = chave1;
  document.form1.sd15_c_descr.value = chave2;
  db_iframe_grupoatend.hide();
}
function js_pesquisasd24_c_cid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_cids.php?funcao_js=parent.js_mostracids1|sd22_c_codigo|sd22_c_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd24_c_cid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_cids.php?pesquisa_chave='+document.form1.sd24_c_cid.value+'&funcao_js=parent.js_mostracids','Pesquisa',false);
     }else{
       document.form1.sd22_c_codigo.value = ''; 
     }
  }
}
function js_mostracids(chave,erro){
  document.form1.sd22_c_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd24_c_cid.focus(); 
    document.form1.sd24_c_cid.value = ''; 
  }
}
function js_mostracids1(chave1,chave2){
  document.form1.sd24_c_cid.value = chave1;
  document.form1.sd22_c_codigo.value = chave2;
  db_iframe_cids.hide();
}
function js_pesquisasd24_i_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.sd24_i_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd24_i_numcgm.focus(); 
    document.form1.sd24_i_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.sd24_i_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontuarios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>