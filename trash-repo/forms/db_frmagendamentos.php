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
$clagendamentos->rotulo->label();
$clunidades->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd04_i_medico");
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd02_i_codigo?>">
       <?
       db_ancora(@$Lsd02_i_codigo,"js_pesquisasd04_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd02_i_codigo',10,$Isd02_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd04_i_unidade(false);'")
?>
       <?
db_input('descrdepto',80,@$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>

  <tr>
    <td nowrap title="<?=@$Tsd04_i_medico?>">
       <?
       db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd04_i_medico(false);'")
?>
       <?
db_input('z01_nome',80,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>


<!--
  <tr>
    <td nowrap title="<?=@$Tsd23_i_codigo?>">
       <?=@$Lsd23_i_codigo?>
    </td>
    <td> 
<?
db_input('sd23_i_codigo',5,$Isd23_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_unidmed?>">
       <?
       db_ancora(@$Lsd23_i_unidmed,"js_pesquisasd23_i_unidmed(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd23_i_unidmed',10,$Isd23_i_unidmed,true,'text',$db_opcao," onchange='js_pesquisasd23_i_unidmed(false);'")
?>
       <?
db_input('sd04_i_medico',5,$Isd04_i_medico,true,'text',3,'')
       ?>
    </td>
  </tr>
-->
  <tr>
    <td nowrap title="<?=@$Tsd23_i_usuario?>">
       <?
       db_ancora(@$Lsd23_i_usuario,"js_pesquisasd23_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd23_i_usuario',5,$Isd23_i_usuario,true,'text',$db_opcao," onchange='js_pesquisasd23_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
<!--
  <tr>
    <td nowrap title="<?=@$Tsd23_i_numcgm?>">
       <?
       db_ancora(@$Lsd23_i_numcgm,"js_pesquisasd23_i_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd23_i_numcgm',5,@$Isd23_i_numcgm,true,'text',$db_opcao," onchange='js_pesquisasd23_i_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_ano?>">
       <?=@$Lsd23_i_ano?>
    </td>
    <td>
<?
db_input('sd23_i_ano',4,$Isd23_i_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_mes?>">
       <?=@$Lsd23_i_mes?>
    </td>
    <td>
<?
db_input('sd23_i_mes',2,$Isd23_i_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_seq?>">
       <?=@$Lsd23_i_seq?>
    </td>
    <td>
<?
db_input('sd23_i_seq',5,@$Isd23_i_seq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_d_agendamento?>">
       <?=@$Lsd23_d_agendamento?>
    </td>
    <td>
<?
db_inputdata('sd23_d_agendamento',@$sd23_d_agendamento_dia,@$sd23_d_agendamento_mes,@$sd23_d_agendamento_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_d_consulta?>">
       <?=@$Lsd23_d_consulta?>
    </td>
    <td>
<?
db_inputdata('sd23_d_consulta',@$sd23_d_consulta_dia,@$sd23_d_consulta_mes,@$sd23_d_consulta_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_ficha?>">
       <?=@$Lsd23_i_ficha?>
    </td>
    <td>
<?
db_input('sd23_i_ficha',10,$Isd23_i_ficha,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_c_hora?>">
       <?=@$Lsd23_c_hora?>
    </td>
    <td>
<?
db_input('sd23_c_hora',5,$Isd23_c_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_c_pessoa?>">
       <?=@$Lsd23_c_pessoa?>
    </td>
    <td>
<?
db_input('sd23_c_pessoa',40,$Isd23_c_pessoa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_situacao?>">
       <?=@$Lsd23_i_situacao?>
    </td>
    <td>
<?
$x = array('1'=>'AGENDADO','2'=>'ATENDIDO','3'=>'CANCELADO');
db_select('sd23_i_situacao',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
-->
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd02_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd02_i_codigo.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd02_i_codigo.focus();
    document.form1.sd02_i_codigo.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd02_i_codigo.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}

function js_pesquisasd04_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_medico.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd04_i_medico.focus();
    document.form1.sd04_i_medico.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}

function js_pesquisasd23_i_unidmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_medico','Pesquisa',true);
  }else{
     if(document.form1.sd23_i_unidmed.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd23_i_unidmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_medico.value = ''; 
     }
  }
}

function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}

function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_medico.value = chave; 
  if(erro==true){ 
    document.form1.sd23_i_unidmed.focus(); 
    document.form1.sd23_i_unidmed.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd23_i_unidmed.value = chave1;
  document.form1.sd04_i_medico.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisasd23_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd23_i_usuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd23_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.sd23_i_usuario.focus(); 
    document.form1.sd23_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd23_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisasd23_i_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd23_i_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.sd23_i_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.sd23_i_numcgm.focus(); 
    document.form1.sd23_i_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.sd23_i_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_agendamentos','func_agendamentos.php?funcao_js=parent.js_preenchepesquisa|sd23_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agendamentos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>