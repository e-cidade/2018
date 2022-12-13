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
$clalunos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
//$clrotulo->label("z01_nome2");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted07_i_codigo?>">
       <?
       db_ancora(@$Led07_i_codigo,"js_pesquisaed07_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed07_i_codigo',5,$Ied07_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed07_i_codigo(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_senha?>">
       <?=@$Led07_c_senha?>
    </td>
    <td>
<?
db_input('ed07_c_senha',10,$Ied07_c_senha,true,'password',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_necessidades?>">
       <?=@$Led07_c_necessidades?>
    </td>
    <td>
<?
db_input('ed07_c_necessidades',50,$Ied07_c_necessidades,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_t_descr?>">
       <?=@$Led07_t_descr?>
    </td>
    <td>
<?
db_textarea('ed07_t_descr',3,40,$Ied07_t_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_i_responsavel?>">
       <?
       db_ancora(@$Led07_i_responsavel,"js_pesquisaed07_i_responsavel(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed07_i_responsavel',5,$Ied07_i_responsavel,true,'text',$db_opcao," onchange='js_pesquisaed07_i_responsavel(false);'")
?>
       <?
db_input('z01_nome2',40,$Iz01_nome2,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_certidao?>">
       <?=@$Led07_c_certidao?>
    </td>
    <td>
<?
db_input('ed07_c_certidao',10,$Ied07_c_certidao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_cartorio?>">
       <?=@$Led07_c_cartorio?>
    </td>
    <td>
<?
db_input('ed07_c_cartorio',50,$Ied07_c_cartorio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_livro?>">
       <?=@$Led07_c_livro?>
    </td>
    <td>
<?
db_input('ed07_c_livro',5,$Ied07_c_livro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_c_folha?>">
       <?=@$Led07_c_folha?>
    </td>
    <td>
<?
db_input('ed07_c_folha',5,$Ied07_c_folha,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_d_datacert?>">
       <?=@$Led07_d_datacert?>
    </td>
    <td>
<?
db_inputdata('ed07_d_datacert',@$ed07_d_datacert_dia,@$ed07_d_datacert_mes,@$ed07_d_datacert_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted07_t_pendentes?>">
       <?=@$Led07_t_pendentes?>
    </td>
    <td>
<?
db_textarea('ed07_t_pendentes',3,40,$Ied07_t_pendentes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed07_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed07_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.ed07_i_codigo.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.ed07_i_codigo.focus();
    document.form1.ed07_i_codigo.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ed07_i_codigo.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaed07_i_responsavel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm3|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed07_i_responsavel.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.ed07_i_responsavel.value+'&funcao_js=parent.js_mostracgm2','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = '';
     }
  }
}
function js_mostracgm2(chave,erro){
  document.form1.z01_nome2.value = chave;
  if(erro==true){
    document.form1.ed07_i_responsavel.focus();
    document.form1.ed07_i_responsavel.value = '';
  }
}
function js_mostracgm3(chave1,chave2){
  document.form1.ed07_i_responsavel.value = chave1;
  document.form1.z01_nome2.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_alunos','func_alunos.php?funcao_js=parent.js_preenchepesquisa|ed07_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_alunos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>