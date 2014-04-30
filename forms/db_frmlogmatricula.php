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
$cllogmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("ed249_i_codigo");
$clrotulo->label("ed47_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted248_i_codigo?>">
       <?=@$Led248_i_codigo?>
    </td>
    <td> 
<?
db_input('ed248_i_codigo',20,$Ied248_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_i_usuario?>">
       <?
       db_ancora(@$Led248_i_usuario,"js_pesquisaed248_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed248_i_usuario',20,$Ied248_i_usuario,true,'text',$db_opcao," onchange='js_pesquisaed248_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_i_motivo?>">
       <?
       db_ancora(@$Led248_i_motivo,"js_pesquisaed248_i_motivo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed248_i_motivo',20,$Ied248_i_motivo,true,'text',$db_opcao," onchange='js_pesquisaed248_i_motivo(false);'")
?>
       <?
db_input('ed249_i_codigo',20,$Ied249_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_i_aluno?>">
       <?
       db_ancora(@$Led248_i_aluno,"js_pesquisaed248_i_aluno(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed248_i_aluno',20,$Ied248_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed248_i_aluno(false);'")
?>
       <?
db_input('ed47_i_codigo',20,$Ied47_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_t_origem?>">
       <?=@$Led248_t_origem?>
    </td>
    <td> 
<?
db_textarea('ed248_t_origem',0,0,$Ied248_t_origem,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_t_obs?>">
       <?=@$Led248_t_obs?>
    </td>
    <td> 
<?
db_textarea('ed248_t_obs',0,0,$Ied248_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_d_data?>">
       <?=@$Led248_d_data?>
    </td>
    <td> 
<?
db_inputdata('ed248_d_data',@$ed248_d_data_dia,@$ed248_d_data_mes,@$ed248_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted248_c_hora?>">
       <?=@$Led248_c_hora?>
    </td>
    <td> 
<?
db_input('ed248_c_hora',5,$Ied248_c_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed248_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.ed248_i_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ed248_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.ed248_i_usuario.focus(); 
    document.form1.ed248_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.ed248_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisaed248_i_motivo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_motivoexclusao','func_motivoexclusao.php?funcao_js=parent.js_mostramotivoexclusao1|ed249_i_codigo|ed249_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed248_i_motivo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_motivoexclusao','func_motivoexclusao.php?pesquisa_chave='+document.form1.ed248_i_motivo.value+'&funcao_js=parent.js_mostramotivoexclusao','Pesquisa',false);
     }else{
       document.form1.ed249_i_codigo.value = ''; 
     }
  }
}
function js_mostramotivoexclusao(chave,erro){
  document.form1.ed249_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed248_i_motivo.focus(); 
    document.form1.ed248_i_motivo.value = ''; 
  }
}
function js_mostramotivoexclusao1(chave1,chave2){
  document.form1.ed248_i_motivo.value = chave1;
  document.form1.ed249_i_codigo.value = chave2;
  db_iframe_motivoexclusao.hide();
}
function js_pesquisaed248_i_aluno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed248_i_aluno.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aluno','func_aluno.php?pesquisa_chave='+document.form1.ed248_i_aluno.value+'&funcao_js=parent.js_mostraaluno','Pesquisa',false);
     }else{
       document.form1.ed47_i_codigo.value = ''; 
     }
  }
}
function js_mostraaluno(chave,erro){
  document.form1.ed47_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed248_i_aluno.focus(); 
    document.form1.ed248_i_aluno.value = ''; 
  }
}
function js_mostraaluno1(chave1,chave2){
  document.form1.ed248_i_aluno.value = chave1;
  document.form1.ed47_i_codigo.value = chave2;
  db_iframe_aluno.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_logmatricula','func_logmatricula.php?funcao_js=parent.js_preenchepesquisa|ed248_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_logmatricula.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>