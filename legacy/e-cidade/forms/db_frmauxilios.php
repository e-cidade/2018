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
$clauxilios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed07_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted21_i_codigo?>">
       <?=@$Led21_i_codigo?>
    </td>
    <td> 
<?
db_input('ed21_i_codigo',5,$Ied21_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted21_i_aluno?>">
       <?
       db_ancora(@$Led21_i_aluno,"js_pesquisaed21_i_aluno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed21_i_aluno',5,$Ied21_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed21_i_aluno(false);'")
?>
       <?
db_input('ed07_c_nome',50,$Ied07_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted21_c_tipo?>">
       <?=@$Led21_c_tipo?>
    </td>
    <td> 
<?
db_input('ed21_c_tipo',50,$Ied21_c_tipo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted21_d_inicio?>">
       <?=@$Led21_d_inicio?>
    </td>
    <td> 
<?
db_inputdata('ed21_d_inicio',@$ed21_d_inicio_dia,@$ed21_d_inicio_mes,@$ed21_d_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed21_i_aluno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_alunos','func_alunos.php?funcao_js=parent.js_mostraalunos1|ed07_i_codigo|ed07_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed21_i_aluno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_alunos','func_alunos.php?pesquisa_chave='+document.form1.ed21_i_aluno.value+'&funcao_js=parent.js_mostraalunos','Pesquisa',false);
     }else{
       document.form1.ed07_c_nome.value = ''; 
     }
  }
}
function js_mostraalunos(chave,erro){
  document.form1.ed07_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed21_i_aluno.focus(); 
    document.form1.ed21_i_aluno.value = ''; 
  }
}
function js_mostraalunos1(chave1,chave2){
  document.form1.ed21_i_aluno.value = chave1;
  document.form1.ed07_c_nome.value = chave2;
  db_iframe_alunos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_auxilios','func_auxilios.php?funcao_js=parent.js_preenchepesquisa|ed21_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_auxilios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>