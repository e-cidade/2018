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
$clagendtransf->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd23_d_consulta");
$clrotulo->label("nome");
$clrotulo->label("sd04_i_medico");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd31_i_codigo?>">
       <?=@$Lsd31_i_codigo?>
    </td>
    <td> 
<?
db_input('sd31_i_codigo',10,$Isd31_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd31_d_dataorigem?>">
       <?=@$Lsd31_d_dataorigem?>
    </td>
    <td> 
<?
db_inputdata('sd31_d_dataorigem',@$sd31_d_dataorigem_dia,@$sd31_d_dataorigem_mes,@$sd31_d_dataorigem_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd31_i_agendamento?>">
       <?
       db_ancora(@$Lsd31_i_agendamento,"js_pesquisasd31_i_agendamento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd31_i_agendamento',10,$Isd31_i_agendamento,true,'text',$db_opcao," onchange='js_pesquisasd31_i_agendamento(false);'")
?>
       <?
db_input('sd23_d_consulta',10,$Isd23_d_consulta,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd31_i_usuario?>">
       <?
       db_ancora(@$Lsd31_i_usuario,"js_pesquisasd31_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd31_i_usuario',10,$Isd31_i_usuario,true,'text',$db_opcao," onchange='js_pesquisasd31_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd31_i_undmedorigem?>">
       <?
       db_ancora(@$Lsd31_i_undmedorigem,"js_pesquisasd31_i_undmedorigem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd31_i_undmedorigem',10,$Isd31_i_undmedorigem,true,'text',$db_opcao," onchange='js_pesquisasd31_i_undmedorigem(false);'")
?>
       <?
db_input('sd04_i_medico',5,$Isd04_i_medico,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd31_i_agendamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_agendamentos','func_agendamentos.php?funcao_js=parent.js_mostraagendamentos1|sd23_i_codigo|sd23_d_consulta','Pesquisa',true);
  }else{
     if(document.form1.sd31_i_agendamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_agendamentos','func_agendamentos.php?pesquisa_chave='+document.form1.sd31_i_agendamento.value+'&funcao_js=parent.js_mostraagendamentos','Pesquisa',false);
     }else{
       document.form1.sd23_d_consulta.value = ''; 
     }
  }
}
function js_mostraagendamentos(chave,erro){
  document.form1.sd23_d_consulta.value = chave; 
  if(erro==true){ 
    document.form1.sd31_i_agendamento.focus(); 
    document.form1.sd31_i_agendamento.value = ''; 
  }
}
function js_mostraagendamentos1(chave1,chave2){
  document.form1.sd31_i_agendamento.value = chave1;
  document.form1.sd23_d_consulta.value = chave2;
  db_iframe_agendamentos.hide();
}
function js_pesquisasd31_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd31_i_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd31_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd31_i_usuario.focus(); 
    document.form1.sd31_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd31_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisasd31_i_undmedorigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd31_i_undmedorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd31_i_undmedorigem.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_medico.value = ''; 
     }
  }
}
function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_medico.value = chave; 
  if(erro==true){ 
    document.form1.sd31_i_undmedorigem.focus(); 
    document.form1.sd31_i_undmedorigem.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd31_i_undmedorigem.value = chave1;
  document.form1.sd04_i_medico.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_agendtransf','func_agendtransf.php?funcao_js=parent.js_preenchepesquisa|sd31_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agendtransf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>