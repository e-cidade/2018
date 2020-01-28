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
$cldiretores->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("ed02_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted15_i_codigo?>">
       <?
       db_ancora(@$Led15_i_codigo,"js_pesquisaed15_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed15_i_codigo',10,$Ied15_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed15_i_codigo(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted15_i_escolas?>">
       <?
       db_ancora(@$Led15_i_escolas,"js_pesquisaed15_i_escolas(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed15_i_escolas',5,$Ied15_i_escolas,true,'text',$db_opcao," onchange='js_pesquisaed15_i_escolas(false);'")
?>
       <?
db_input('z01_nome2',50,$z01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted15_c_categoria?>">
       <?=@$Led15_c_categoria?>
    </td>
    <td> 
<?
$x = array('VICE-DIRETOR'=>'VICE-DIRETOR','DIRETOR'=>'DIRETOR');
db_select('ed15_c_categoria',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted15_d_inicio?>">
       <?=@$Led15_d_inicio?>
    </td>
    <td> 
<?
db_inputdata('ed15_d_inicio',@$ed15_d_inicio_dia,@$ed15_d_inicio_mes,@$ed15_d_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted15_d_termino?>">
       <?=@$Led15_d_termino?>
    </td>
    <td> 
<?
db_inputdata('ed15_d_termino',@$ed15_d_termino_dia,@$ed15_d_termino_mes,@$ed15_d_termino_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed15_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed15_i_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.ed15_i_codigo.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed15_i_codigo.focus(); 
    document.form1.ed15_i_codigo.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ed15_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaed15_i_escolas(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_mostraescolas1|ed02_i_codigo|z01_nome2','Pesquisa',true);
  }else{
     if(document.form1.ed15_i_escolas.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?pesquisa_chave='+document.form1.ed15_i_escolas.value+'&funcao_js=parent.js_mostraescolas','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = '';
     }
  }
}
function js_mostraescolas(chave,erro){
  document.form1.z01_nome2.value = chave;
  if(erro==true){ 
    document.form1.ed15_i_escolas.focus(); 
    document.form1.ed15_i_escolas.value = ''; 
  }
}
function js_mostraescolas1(chave1,chave2){
  document.form1.ed15_i_escolas.value = chave1;
  document.form1.z01_nome2.value = chave2;
  db_iframe_escolas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_diretores','func_diretores.php?funcao_js=parent.js_preenchepesquisa|ed15_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_diretores.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>