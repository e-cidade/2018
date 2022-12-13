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
$clvacinasaplicadas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd07_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd08_c_vacina?>">
       <?
       db_ancora(@$Lsd08_c_vacina,"js_pesquisasd08_c_vacina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd08_c_vacina',10,$Isd08_c_vacina,true,'text',$db_opcao," onchange='js_pesquisasd08_c_vacina(false);'")
?>
       <?
db_input('sd07_c_nome',40,$Isd07_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd08_i_unidade?>">
       <?
       db_ancora(@$Lsd08_i_unidade,"js_pesquisasd08_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd08_i_unidade',10,$Isd08_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd08_i_unidade(false);'")
?>
       <?
db_input('sd02_c_nome',40,$Isd02_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd08_i_cgm?>">
       <?
       db_ancora(@$Lsd08_i_cgm,"js_pesquisasd08_i_cgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd08_i_cgm',10,$Isd08_i_cgm,true,'text',$db_opcao," onchange='js_pesquisasd08_i_cgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd08_d_data?>">
       <?=@$Lsd08_d_data?>
    </td>
    <td> 
<?
db_inputdata('sd08_d_data',@$sd08_d_data_dia,@$sd08_d_data_mes,@$sd08_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?if($db_opcao==1){echo "disabled";}?>>
</form>
<script>
function js_pesquisasd08_i_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd08_i_cgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.sd08_i_cgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd08_i_cgm.focus(); 
    document.form1.sd08_i_cgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.sd08_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisasd08_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd08_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd08_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = ''; 
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd08_i_unidade.focus(); 
    document.form1.sd08_i_unidade.value = ''; 
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd08_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd08_c_vacina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vacinas','func_vacinas.php?funcao_js=parent.js_mostravacinas1|sd07_c_codigo|sd07_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd08_c_vacina.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vacinas','func_vacinas.php?pesquisa_chave='+document.form1.sd08_c_vacina.value+'&funcao_js=parent.js_mostravacinas','Pesquisa',false);
     }else{
       document.form1.sd07_c_nome.value = ''; 
     }
  }
}
function js_mostravacinas(chave,erro){
  document.form1.sd07_c_nome.value = chave;
  if(erro==true){ 
    document.form1.sd08_c_vacina.focus(); 
    document.form1.sd08_c_vacina.value = ''; 
  }
}
function js_mostravacinas1(chave1,chave2){
  document.form1.sd08_c_vacina.value = chave1;
  document.form1.sd07_c_nome.value = chave2;
  db_iframe_vacinas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vacinasaplicadas','func_vacinasaplicadas.php?funcao_js=parent.js_preenchepesquisa|sd08_i_cgm|sd08_i_unidade|sd08_c_vacina','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_vacinasaplicadas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>