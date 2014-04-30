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

//MODULO: call_center
$clbo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p51_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbo01_codbo?>">
       <?=@$Lbo01_codbo?>
    </td>
    <td> 
<?
db_input('bo01_codbo',6,$Ibo01_codbo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo01_numcgm?>">
       <?
       db_ancora(@$Lbo01_numcgm,"js_pesquisabo01_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo01_numcgm',10,$Ibo01_numcgm,true,'text',$db_opcao," onchange='js_pesquisabo01_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo01_codtipo?>">
       <?
       db_ancora(@$Lbo01_codtipo,"js_pesquisabo01_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo01_codtipo',3,$Ibo01_codtipo,true,'text',$db_opcao," onchange='js_pesquisabo01_codtipo(false);'")
?>
       <?
db_input('p51_descr',60,$Ip51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo01_obs?>">
       <?=@$Lbo01_obs?>
    </td>
    <td> 
<?
db_textarea('bo01_obs',5,80,$Ibo01_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo01_data?>">
       <?=@$Lbo01_data?>
    </td>
    <td> 
<?
db_inputdata('bo01_data',@$bo01_data_dia,@$bo01_data_mes,@$bo01_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisabo01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.bo01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.bo01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.bo01_numcgm.focus(); 
    document.form1.bo01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.bo01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisabo01_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|p51_codigo|p51_descr','Pesquisa',true);
  }else{
     if(document.form1.bo01_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoproc','func_tipoproc.php?pesquisa_chave='+document.form1.bo01_codtipo.value+'&funcao_js=parent.js_mostratipoproc','Pesquisa',false);
     }else{
       document.form1.p51_descr.value = ''; 
     }
  }
}
function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.bo01_codtipo.focus(); 
    document.form1.bo01_codtipo.value = ''; 
  }
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.bo01_codtipo.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe_tipoproc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bo','func_bo.php?funcao_js=parent.js_preenchepesquisa|bo01_codbo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>