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

//MODULO: educação
$clalunopossib->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed56_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed15_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted79_i_codigo?>">
       <?=@$Led79_i_codigo?>
    </td>
    <td> 
<?
db_input('ed79_i_codigo',10,$Ied79_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_i_alunocurso?>">
       <?
       db_ancora(@$Led79_i_alunocurso,"js_pesquisaed79_i_alunocurso(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed79_i_alunocurso',10,$Ied79_i_alunocurso,true,'text',$db_opcao," onchange='js_pesquisaed79_i_alunocurso(false);'")
?>
       <?
db_input('ed56_i_codigo',10,$Ied56_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_i_serie?>">
       <?
       db_ancora(@$Led79_i_serie,"js_pesquisaed79_i_serie(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed79_i_serie',10,$Ied79_i_serie,true,'text',$db_opcao," onchange='js_pesquisaed79_i_serie(false);'")
?>
       <?
db_input('ed11_i_codigo',10,$Ied11_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_i_turno?>">
       <?
       db_ancora(@$Led79_i_turno,"js_pesquisaed79_i_turno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed79_i_turno',10,$Ied79_i_turno,true,'text',$db_opcao," onchange='js_pesquisaed79_i_turno(false);'")
?>
       <?
db_input('ed15_i_codigo',10,$Ied15_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_i_turmaant?>">
       <?=@$Led79_i_turmaant?>
    </td>
    <td> 
<?
db_input('ed79_i_turmaant',10,$Ied79_i_turmaant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_c_resulant?>">
       <?=@$Led79_c_resulant?>
    </td>
    <td> 
<?
db_input('ed79_c_resulant',1,$Ied79_c_resulant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted79_c_situacao?>">
       <?=@$Led79_c_situacao?>
    </td>
    <td> 
<?
db_input('ed79_c_situacao',1,$Ied79_c_situacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed79_i_alunocurso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_alunocurso','func_alunocurso.php?funcao_js=parent.js_mostraalunocurso1|ed56_i_codigo|ed56_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed79_i_alunocurso.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_alunocurso','func_alunocurso.php?pesquisa_chave='+document.form1.ed79_i_alunocurso.value+'&funcao_js=parent.js_mostraalunocurso','Pesquisa',false);
     }else{
       document.form1.ed56_i_codigo.value = ''; 
     }
  }
}
function js_mostraalunocurso(chave,erro){
  document.form1.ed56_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed79_i_alunocurso.focus(); 
    document.form1.ed79_i_alunocurso.value = ''; 
  }
}
function js_mostraalunocurso1(chave1,chave2){
  document.form1.ed79_i_alunocurso.value = chave1;
  document.form1.ed56_i_codigo.value = chave2;
  db_iframe_alunocurso.hide();
}
function js_pesquisaed79_i_serie(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_serie','func_serie.php?funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed79_i_serie.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_serie','func_serie.php?pesquisa_chave='+document.form1.ed79_i_serie.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
     }else{
       document.form1.ed11_i_codigo.value = ''; 
     }
  }
}
function js_mostraserie(chave,erro){
  document.form1.ed11_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed79_i_serie.focus(); 
    document.form1.ed79_i_serie.value = ''; 
  }
}
function js_mostraserie1(chave1,chave2){
  document.form1.ed79_i_serie.value = chave1;
  document.form1.ed11_i_codigo.value = chave2;
  db_iframe_serie.hide();
}
function js_pesquisaed79_i_turno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_turno','func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed79_i_turno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_turno','func_turno.php?pesquisa_chave='+document.form1.ed79_i_turno.value+'&funcao_js=parent.js_mostraturno','Pesquisa',false);
     }else{
       document.form1.ed15_i_codigo.value = ''; 
     }
  }
}
function js_mostraturno(chave,erro){
  document.form1.ed15_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed79_i_turno.focus(); 
    document.form1.ed79_i_turno.value = ''; 
  }
}
function js_mostraturno1(chave1,chave2){
  document.form1.ed79_i_turno.value = chave1;
  document.form1.ed15_i_codigo.value = chave2;
  db_iframe_turno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_alunopossib','func_alunopossib.php?funcao_js=parent.js_preenchepesquisa|ed79_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_alunopossib.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>